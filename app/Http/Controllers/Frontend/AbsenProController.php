<?php

namespace App\Http\Controllers\Frontend;

use App\absenpro_xls;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Auth;
use Response;
use Maatwebsite\Excel\Facades\Excel;
use App\Eloquent\AbsenLog;
use App\Eloquent\Absen;
use App\Helper_function;

class AbsenProController extends Controller
{
	public function __construct()
	{
		$this->middleware('auth');
	}
    public function absenpro()
    {
        $iduser=Auth::user()->id;
        $sqlidkar="select * from p_karyawan where user_id=$iduser";
        $idkar=DB::connection()->select($sqlidkar);
        $id=$idkar[0]->p_karyawan_id;

        $sqlpa="select m_jabatan.m_pangkat_id from p_karyawan_pekerjaan 
LEFT JOIN m_jabatan on m_jabatan.m_jabatan_id=p_karyawan_pekerjaan.m_jabatan_id
LEFT JOIN m_pangkat on m_pangkat.m_pangkat_id=m_jabatan.m_pangkat_id
where p_karyawan_id=$iduser";
        $pa=DB::connection()->select($sqlpa);
        $pangkat=0;
        if(!empty($pa['m_pangkat_id'][0])){
            $pangkat=$pa['m_pangkat_id'][0];
        }

        $tgl_awal=date('Y-m-d');
        $tgl_akhir=date('Y-m-d');
        $sqlabsen="SELECT DISTINCT a.pin,c.nik,c.nama,to_char(a.date_time,'dd-mm-yyyy') as tgl_absen,to_char(a.date_time,'HH24:MI:SS') as jam_masuk,
jk.jam_keluar,case when to_char(date_time,'HH24:MI')>'07:30' then 'Terlambat' else 'OK' end as keterangan,f.nama as nmlokasi
FROM absen_log a
LEFT JOIN p_karyawan_absen b on b.no_absen=a.pin
LEFT JOIN p_karyawan c on c.p_karyawan_id=b.p_karyawan_id
LEFT JOIN p_karyawan_pekerjaan d on d.p_karyawan_id=c.p_karyawan_id
LEFT JOIN m_lokasi e on e.m_lokasi_id=d.m_lokasi_id
LEFT JOIN m_mesin_absen f on f.mesin_id=a.mesin_id
LEFT JOIN (SELECT DISTINCT a.pin,to_char(a.date_time,'HH24:MI:SS') as jam_keluar,to_char(a.date_time,'yyyy-mm-dd') as tgl
FROM absen_log a
WHERE to_char(a.date_time,'yyyy-mm-dd')>='".$tgl_awal."' and to_char(a.date_time,'yyyy-mm-dd')<='".$tgl_akhir."' and a.ver=2) as jk on jk.pin=a.pin
and jk.tgl=to_char(a.date_time,'yyyy-mm-dd')
WHERE to_char(a.date_time,'yyyy-mm-dd')>='".$tgl_awal."' and to_char(a.date_time,'yyyy-mm-dd')<='".$tgl_akhir."' and a.ver=1
and c.p_karyawan_id=$id
ORDER BY nama,to_char(a.date_time,'dd-mm-yyyy'),nmlokasi";
        $absen=DB::connection()->select($sqlabsen);

        $sqluser="SELECT p_recruitment.foto FROM users
left join p_karyawan on p_karyawan.user_id=users.id
left join p_recruitment on p_recruitment.p_recruitment_id=p_karyawan.p_recruitment_id
where users.id=$iduser";
        $user=DB::connection()->select($sqluser);
		$hari_libur_shift = array();
		$sql="select * from absen_libur_shift where tanggal >= '$tgl_awal' and tanggal <='$tgl_akhir' and p_karyawan_id=$id";
		$harilibur = DB::connection()->select($sql);
		foreach($harilibur as $libur){
			$hari_libur_shift[] = $libur->tanggal;
		}
		
		
        return view('frontend.absen.absen',compact('absen','tgl_awal','tgl_akhir','pangkat','user','hari_libur_shift'));
    }
	public function cari_absenpro(Request $request)
    {
    	$help = new Helper_function();
    	 $iduser=Auth::user()->id;
        $sqlidkar="select * from p_karyawan where user_id=$iduser";
        $idkar=DB::connection()->select($sqlidkar);
        $id=$idkar[0]->p_karyawan_id;
        //echo 'masuk';die;
        $sqlusers="SELECT p_karyawan.*,m_departemen.nama as nmdept FROM p_karyawan
LEFT JOIN p_karyawan_pekerjaan on p_karyawan_pekerjaan.p_karyawan_id=p_karyawan.p_karyawan_id
LEFT JOIN m_departemen on m_departemen.m_departemen_id=p_karyawan_pekerjaan.m_departemen_id
                WHERE 1=1 and p_karyawan.active=1 order by p_karyawan.nama";
        $users=DB::connection()->select($sqlusers);
        $tgl_awal=date('Y-m-d',strtotime($request->get('tgl_awal')));
        $tgl_akhir=date('Y-m-d',strtotime($request->get('tgl_akhir')));
       if($tgl_awal=='1970-01-01')
			$tgl_awal = date('Y-m-d');
			
		if($tgl_akhir=='1970-01-01')
			$tgl_akhir = date('Y-m-d');
        $iduser=Auth::user()->id;
        $sqluser="SELECT p_recruitment.foto FROM users
			left join p_karyawan on p_karyawan.user_id=users.id
			left join p_recruitment on p_recruitment.p_recruitment_id=p_karyawan.p_recruitment_id
			where users.id=$iduser";
        $user=DB::connection()->select($sqluser);
		$nama=$id;
    	$rekap = array();
    	$list_karyawan = array();
    	$mesin = array();
    	$hari_libur=array();
		$tanggallibur=array();
		$hari_libur_shift = array();
    	if($nama ){
			
    	$sqlusers="SELECT c.periode_gajian FROM p_karyawan_pekerjaan c
                WHERE 1=1 and c.active=1 and p_karyawan_id=$nama";
	        $search_type=DB::connection()->select($sqlusers);
	        $type = $search_type[0]->periode_gajian;
			$rekap = $help->rekap_absen($tgl_awal,$tgl_akhir,$tgl_awal,$tgl_akhir,$type,null,$nama);
			$list_karyawan = $rekap['list_karyawan'] ;
			//print_r($list_karyawan);
			//die;
			$hari_libur = $rekap['hari_libur'] ;
			$hari_libur_shift = $rekap['hari_libur_shift'] ;
			$mesin = $rekap['mesin'] ;
			$tanggallibur = $rekap['tanggallibur'] ;
			if(count($list_karyawan))
				$list_karyawan = $list_karyawan[0];
		}else{
		    
			$rekap = $help->rekap_absen($tgl_awal,$tgl_akhir,$tgl_awal,$tgl_akhir);
			$list_karyawan = $rekap['list_karyawan'] ;
			//print_r($list_karyawan);
			//die;
			$hari_libur = $rekap['hari_libur'] ;
			$hari_libur_shift = $rekap['hari_libur_shift'] ;
			$mesin = $rekap['mesin'] ;
			$tanggallibur = $rekap['tanggallibur'] ;
		}
		
		
    	if($request->get('Cari')=='Cari'){
        	$sqllistabsen = "";
        	
        	return view('frontend.absen.cek_absen',compact('id','users','tgl_awal','tgl_akhir','user','nama','rekap','help','list_karyawan','mesin','hari_libur','hari_libur','tanggallibur','hari_libur_shift'));
           
    	
		}else if($request->get('Cari')=='Excel'){
           	$nama_file = 'Absen '.date('d-m-Y',strtotime($tgl_awal)).':'.date('d-m-Y',strtotime($tgl_akhir));

            $param['id'] = $id;
            $param['tgl_awal'] = $tgl_awal;
            $param['tgl_akhir'] = $tgl_akhir;
            $param['rekap'] = $rekap;
            $param['help'] = $help;
            $param['list_karyawan'] = $list_karyawan;
            $param['mesin'] = $mesin;
            $param['hari_libur'] = $hari_libur;
            $param['hari_libur'] = $hari_libur;
            $param['tanggallibur'] = $tanggallibur;
            return Excel::download(new absenpro_xls($param), $nama_file. '.xlsx');
            
		}else{
        	return view('frontend.absen.cek_absen',compact('id','users','tgl_awal','tgl_akhir','user','nama','rekap','help','list_karyawan','mesin','hari_libur','tanggallibur'));
			
		}
        
    }
    public function cari_absenproLAST(Request $request)
    {
        $iduser=Auth::user()->id;
        $sqlidkar="select * from p_karyawan where user_id=$iduser";
        $idkar=DB::connection()->select($sqlidkar);
        $id=$idkar[0]->p_karyawan_id;

        $sqlpa="select m_jabatan.m_pangkat_id from p_karyawan_pekerjaan 
LEFT JOIN m_jabatan on m_jabatan.m_jabatan_id=p_karyawan_pekerjaan.m_jabatan_id
LEFT JOIN m_pangkat on m_pangkat.m_pangkat_id=m_jabatan.m_pangkat_id
where p_karyawan_id=$id";
        $pa=DB::connection()->select($sqlpa);
        $pangkat=0;
        if(!empty($pa['m_pangkat_id'][0])){
            $pangkat=$pa['m_pangkat_id'][0];
        }

        if($request->get('Cari')=='Cari'){
            $tgl_awal=date('Y-m-d',strtotime($request->get('tgl_awal')));
            $tgl_akhir=date('Y-m-d',strtotime($request->get('tgl_akhir')));
            $sqlabsen="SELECT DISTINCT a.pin,c.nik,c.nama,to_char(a.date_time,'dd-mm-yyyy') as tgl_absen,to_char(a.date_time,'HH24:MI:SS') as jam_masuk,jk.jam_keluar,
case when to_char(date_time,'HH24:MI')>'07:31:00' and e.m_lokasi_id<>6 then 'Terlambat' 
when to_char(date_time,'HH24:MI')>'08:31:00' and e.m_lokasi_id=6 then 'Terlambat' 
else 'OK' end as keterangan,f.nama as nmlokasi
FROM absen_log a
LEFT JOIN p_karyawan_absen b on b.no_absen=a.pin
LEFT JOIN p_karyawan c on c.p_karyawan_id=b.p_karyawan_id
LEFT JOIN p_karyawan_pekerjaan d on d.p_karyawan_id=c.p_karyawan_id
LEFT JOIN m_lokasi e on e.m_lokasi_id=d.m_lokasi_id
LEFT JOIN m_mesin_absen f on f.mesin_id=a.mesin_id
LEFT JOIN (SELECT DISTINCT a.pin,to_char(a.date_time,'HH24:MI:SS') as jam_keluar,to_char(a.date_time,'yyyy-mm-dd') as tgl
FROM absen_log a
WHERE to_char(a.date_time,'yyyy-mm-dd')>='".$tgl_awal."' and to_char(a.date_time,'yyyy-mm-dd')<='".$tgl_akhir."' and a.ver=2) as jk on jk.pin=a.pin
and jk.tgl=to_char(a.date_time,'yyyy-mm-dd')
WHERE to_char(a.date_time,'yyyy-mm-dd')>='".$tgl_awal."' and to_char(a.date_time,'yyyy-mm-dd')<='".$tgl_akhir."' and a.ver=1
and c.p_karyawan_id=$id
ORDER BY nama,to_char(a.date_time,'dd-mm-yyyy'),nmlokasi";
            $absen=DB::connection()->select($sqlabsen);

            $sqluser="SELECT p_recruitment.foto FROM users
left join p_karyawan on p_karyawan.user_id=users.id
left join p_recruitment on p_recruitment.p_recruitment_id=p_karyawan.p_recruitment_id
where users.id=$iduser";
            $user=DB::connection()->select($sqluser);

            return view('frontend.absen.absen',compact('absen','tgl_awal','tgl_akhir','pangkat','user'));
        }
        else if($request->get('Cari')=='Excel'){
            $tgl_awal=date('Y-m-d',strtotime($request->get('tgl_awal')));
            $tgl_akhir=date('Y-m-d',strtotime($request->get('tgl_akhir')));
            
            $sqlabsen="SELECT DISTINCT a.pin,c.nik,c.nama,to_char(a.date_time,'dd-mm-yyyy') as tgl_absen,to_char(a.date_time,'HH24:MI:SS') as jam_masuk,jk.jam_keluar,
case when to_char(date_time,'HH24:MI')>'07:31:00' and e.m_lokasi_id<>6 then 'Terlambat' 
when to_char(date_time,'HH24:MI')>'08:31:00' and e.m_lokasi_id=6 then 'Terlambat' 
else 'OK' end as keterangan,f.nama as nmlokasi, 
case when d.periode_gajian=0 then 'Pekanan' else 'Bulanan' end as periode_gaji
FROM absen_log a
LEFT JOIN p_karyawan_absen b on b.no_absen=a.pin
LEFT JOIN p_karyawan c on c.p_karyawan_id=b.p_karyawan_id
LEFT JOIN p_karyawan_pekerjaan d on d.p_karyawan_id=c.p_karyawan_id
LEFT JOIN m_lokasi e on e.m_lokasi_id=d.m_lokasi_id
LEFT JOIN m_mesin_absen f on f.mesin_id=a.mesin_id
LEFT JOIN (SELECT DISTINCT a.pin,to_char(a.date_time,'HH24:MI:SS') as jam_keluar,to_char(a.date_time,'yyyy-mm-dd') as tgl
FROM absen_log a
WHERE to_char(a.date_time,'yyyy-mm-dd')>='".$tgl_awal."' and to_char(a.date_time,'yyyy-mm-dd')<='".$tgl_akhir."' and a.ver=2) as jk on jk.pin=a.pin
and jk.tgl=to_char(a.date_time,'yyyy-mm-dd')
WHERE to_char(a.date_time,'yyyy-mm-dd')>='".$tgl_awal."' and to_char(a.date_time,'yyyy-mm-dd')<='".$tgl_akhir."' and a.ver=1
and c.p_karyawan_id=$id
ORDER BY nama,to_char(a.date_time,'dd-mm-yyyy'),nmlokasi";
            $absen=DB::connection()->select($sqlabsen);
			$nama_file = 'Absen '.date('d-m-Y',strtotime($tgl_awal)).':'.date('d-m-Y',strtotime($tgl_akhir));

            $param['absen'] = $absen;
            return Excel::download(new absenpro_xls($param), $nama_file. '.xlsx');
        }
    }
}

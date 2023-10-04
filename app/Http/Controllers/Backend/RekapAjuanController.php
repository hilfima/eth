<?php

namespace App\Http\Controllers\Backend;

use App\absen_excel;
use App\absen_xls;
use App\ajuan_xls;
use App\cek_absen_xls;
use App\Http\Controllers\Controller;
use App\rekap_absen2_xls;
use App\rekap_absen_xls;
use App\User;
use Illuminate\Http\Request;
use DB;
use Auth;
use PhpOffice\PhpSpreadsheet\Reader\Xml\Style\Fill;
use Response;
use Maatwebsite\Excel\Facades\Excel;
use App\Eloquent\AbsenLog;
use App\Eloquent\Absen;
use DateTime;;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PHPExcel;
use PHPExcel_IOFactory;

class RekapAjuanController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function cari_ajuan(Request $request)
    {

        $iduser=Auth::user()->id;
      	 $sqluser="SELECT p_recruitment.foto,role,p_karyawan_pekerjaan,p_karyawan_pekerjaan.m_lokasi_id,user_entitas_access FROM users
left join m_role on m_role.m_role_id=users.role
left join p_karyawan on p_karyawan.user_id=users.id
left join p_karyawan_pekerjaan on p_karyawan_pekerjaan.p_karyawan_id=p_karyawan.p_karyawan_id
left join p_recruitment on p_recruitment.p_recruitment_id=p_karyawan.p_recruitment_id
where users.id=$iduser";
        $user=DB::connection()->select($sqluser);
        
        
        $id=null;
        //echo 'masuk';die;
        if($request->get('tgl_awal') and $request->get('tgl_akhir')){
    		$tgl_awal = $request->get('tgl_awal');
    		$tgl_akhir = $request->get('tgl_akhir');
    	}else{
    		$bulan = (date('m'));
    		if($bulan==1){
				$bulan = 12;    			
    			$tahun = date('Y')-1;
    		}
    		else{
				$bulan = date('m')-1;    			
    			$tahun = date('Y');
    		}
    		$tgl_awal = $tahun.'-'.$bulan.'-25';
    		$tgl_akhir = date('Y-m-d');
    	}
            $nama=($request->get('nama'));
            if(!empty($nama)){
                $sqlnama=" and a.p_karyawan_id=$nama";
            }
            else{
                $sqlnama=" ";
            }
            $status=($request->get('status'));
 			if(!empty($status)){
                $sqlstatus=" and ((a.status_appr_1=$status and a.m_jenis_ijin_id!=22) or    (status_appr_2=$status and a.m_jenis_ijin_id=22)   )";
            }
            else{
                $sqlstatus=" ";
            }

            $tipe=($request->get('tipe'));
            if(!empty($tipe)){
                $sqltipe=" and c.tipe=$tipe";
            }
            else{
                $sqltipe=" ";
            }
            	$id_lokasi = Auth::user()->user_entitas;
        if($id_lokasi and $id_lokasi!=-1) 
			$whereLokasi = "AND e.m_lokasi_id in($id_lokasi)";					
		else
			$whereLokasi = "";
		if($request->entitas){ 
		 	$id_lokasi = $request->entitas;
			$whereLokasi2 = "AND e.m_lokasi_id = $id_lokasi";			
						
		}else{
			$whereLokasi2 = "";			
			
		}
			$periode_gajian = $request->periode_gajian;
                $sqlperioode=" ";	
            if(!empty($periode_gajian)){
			    $periode_gajian = $periode_gajian==-1?0:$periode_gajian;
                $sqlperioode=" and e.periode_gajian = $periode_gajian";
			}
			$sqldata="SELECT a.*,b.nik,b.nama_lengkap,c.nama as nama_ijin,d.nama as nama_appr,f.nama as nama_appr2,tgl_appr_1,status_appr_2,status_appr_1,b.pangkat,b.departemen,case when status_appr_1=1 then 'Disetujui' when status_appr_1=2 then 'Ditolak' when status_appr_1=3 then 'Pending' end as sts_pengajuan,case when status_appr_2=1 then 'Disetujui' when status_appr_2=2 then 'Ditolak' when status_appr_2=3 then 'Pending' end as sts_pengajuan2,b.jabatan,
c.nama as nmtipe,h.alasan,
case when e.periode_gajian=0 THEN 'PEKANAN' ELSE 'BULANAN' end as gajian
FROM t_permit a
left join get_data_karyawan() b on b.p_karyawan_id=a.p_karyawan_id
left join m_jenis_ijin c on c.m_jenis_ijin_id=a.m_jenis_ijin_id
left join p_karyawan d on d.p_karyawan_id=a.appr_1
left join p_karyawan f on f.p_karyawan_id=a.appr_2 and appr_2 is not null
left join p_karyawan_pekerjaan e on e.p_karyawan_id=a.p_karyawan_id
left join m_jenis_alasan h on h.m_jenis_alasan_id=a.m_jenis_alasan_id
WHERE 1=1 and a.active=1  and
a.tgl_awal>='".$tgl_awal."' and a.tgl_akhir<='".$tgl_akhir."'
".$sqlnama." ".$sqltipe." ".$sqlstatus." $sqlperioode $whereLokasi2 $whereLokasi
ORDER BY b.nama_lengkap";
            $data=DB::connection()->select($sqldata);
            //echo $sqlabsen;die;

           
        $sqlusers="SELECT p_karyawan.*,m_departemen.nama as nmdept FROM p_karyawan
LEFT JOIN p_karyawan_pekerjaan e on e.p_karyawan_id=p_karyawan.p_karyawan_id
LEFT JOIN m_departemen on m_departemen.m_departemen_id=e.m_departemen_id
                WHERE 1=1 and p_karyawan.active=1 $whereLokasi order by p_karyawan.nama";
        $users=DB::connection()->select($sqlusers); 

            $sqlajuan="SELECT DISTINCT tipe, CASE WHEN tipe=1 THEN 'IZIN' 
WHEN tipe=3 THEN 'CUTI'
WHEN tipe=2 THEN 'LEMBUR' END as nmtipe
FROM m_jenis_ijin WHERE 1=1";
            $ajuan=DB::connection()->select($sqlajuan);

       
       
		$entitas=DB::connection()->select("select * from m_lokasi e where active=1 and sub_entitas=0 $whereLokasi ");
        if($request->get('Cari')=='Cari'){
            return view('backend.permit.cek_ajuan',compact('data','tgl_awal','tgl_akhir','periode_gajian','user','nama','users','status','ajuan','tipe','request','entitas'));
        }
        else if($request->get('Cari')=='Excel'){
            if($request->get('tgl_awal') and $request->get('tgl_akhir')){
    		$tgl_awal = $request->get('tgl_awal');
    		$tgl_akhir = $request->get('tgl_akhir');
    	}else{
    		$bulan = (date('m'));
    		if($bulan==1){
				$bulan = 12;    			
    			$tahun = date('Y')-1;
    		}
    		else{
				$bulan = date('m')-1;    			
    			$tahun = date('Y');
    		}
    		$tgl_awal = $tahun.'-'.$bulan.'-25';
    		$tgl_akhir = date('Y-m-d');
    	}
            $nama=$request->get('nama');
            $nama_file = 'Ajuan Periode '.date('d-m-Y',strtotime($tgl_awal)).':'.date('d-m-Y',strtotime($tgl_akhir));

            if(!empty($nama)){
                $sqlnama=" and a.p_karyawan_id=$nama";
            }
            else{
                $sqlnama=" ";
            }
			$status=($request->get('status'));
 			if(!empty($status)){
                $sqlstatus=" and status_appr_1=$status";
            }
            else{
                $sqlstatus=" ";
            }

            $tipe=($request->get('tipe'));
            if(!empty($tipe)){
                $sqltipe=" and c.tipe=$tipe";
            }
            else{
                $sqltipe=" ";
            }
			$sqlperioode=" ";
			if(!empty($periode_gajian)){
			    $periode_gajian = $periode_gajian==-1?0:$periode_gajian;
                $sqlperioode=" and e.periode_gajian = $periode_gajian";
			}
            $sqldata="SELECT a.*,b.nik,b.nama_lengkap,c.nama as nama_ijin,d.nama as nama_appr,tgl_appr_1,status_appr_1,b.pangkat,b.departemen,case when status_appr_1=1 then 'Disetujui' when status_appr_1=2 then 'Ditolak'  when status_appr_1=3 then 'Pending' end as sts_pengajuan,b.jabatan,
CASE WHEN c.tipe=1 THEN 'IZIN' 
WHEN c.tipe=3 THEN 'CUTI'
WHEN c.tipe=2 THEN 'LEMBUR' END as nmtipe,
case when e.periode_gajian=0 THEN 'PEKANAN' ELSE 'BULANAN' end as gajian,alasan_idt_ipm
FROM t_permit a
left join get_data_karyawan() b on b.p_karyawan_id=a.p_karyawan_id
left join m_jenis_ijin c on c.m_jenis_ijin_id=a.m_jenis_ijin_id
left join p_karyawan d on d.p_karyawan_id=a.appr_1
left join p_karyawan_pekerjaan e on e.p_karyawan_id=a.p_karyawan_id
left join m_jenis_alasan f on f.m_jenis_alasan_id=a.m_jenis_alasan_id
WHERE 1=1 and a.active=1  and
a.tgl_awal>='".$tgl_awal."' and a.tgl_akhir<='".$tgl_akhir."'
".$sqlnama." ".$sqltipe."".$sqlstatus." $sqlperioode
ORDER BY b.nama_lengkap asc, a.tgl_awal desc";

            $data=DB::connection()->select($sqldata);

            $param['data'] = $data;
            return Excel::download(new ajuan_xls($param), $nama_file. '.xlsx');
        }
    	else{
    		return view('backend.permit.cek_ajuan',compact('data','tgl_awal','tgl_akhir','periode_gajian','user','nama','users','status','ajuan','tipe','request','entitas'));
    	}
    }
}

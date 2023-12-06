<?php
namespace App\Http\Controllers\Frontend;
use App\Http\Controllers\Controller;

use App\rekaplembur_xls;
use App\User;
use App\Helper_function;
use App\Http\Controllers\Backend\RekapAbsenController;
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

class KehadiranController extends Controller{
  
    
	public function __construct()
	{
		$this->middleware('auth');
	}
	public function rekap_absen(Request $request){
		
		$iduser=Auth::user()->id;
        $sqlidkar="select * from p_karyawan 
        	join p_karyawan_pekerjaan on p_karyawan_pekerjaan.p_karyawan_id = p_karyawan.p_karyawan_id
        where user_id=$iduser";
        $idkar=DB::connection()->select($sqlidkar);
       // echo 'id_jabatan='.$idkar[0]->m_jabatan_id;
        //echo '<br>';
        $id_karyawan = $idkar[0]->p_karyawan_id;
		$help = new Helper_function();
		$jabstruk = $help->jabatan_struktural($id_karyawan);
		$atasan = $jabstruk['atasan'];
		$bawahan = $jabstruk['bawahan'];
		$sejajar = $jabstruk['sejajar'];$tgl_awal=date('Y-m-d');
		$tgl_akhir=date('Y-m-d');
		if($request->get('tgl_awal')){
			$tgl_awal=$request->get('tgl_awal');
			$tgl_akhir=$request->get('tgl_akhir');
		}
		$tahun=date('Y');
		$bulan=date('mm');
		$periode_gajian=$request->get('periode_gajian');
		$periode_absen=$request->get('periode');
		$rekapget = $request->get('rekapget');
		//echo $periode_absen;die;
		$iduser=Auth::user()->id;
		$sqluser="SELECT p_recruitment.foto,role,p_karyawan_pekerjaan,p_karyawan_pekerjaan.m_lokasi_id FROM users
left join p_karyawan on p_karyawan.user_id=users.id
left join p_karyawan_pekerjaan on p_karyawan_pekerjaan.p_karyawan_id=p_karyawan.p_karyawan_id
		left join p_recruitment on p_recruitment.p_recruitment_id=p_karyawan.p_recruitment_id
		where users.id=$iduser";
		$user=DB::connection()->select($sqluser);
        
		$sqlperiode="SELECT m_periode_absen.*,
		case when periode=1 then 'Januari'
		when periode=2 then 'Februari'
		when periode=3 then 'Maret'
		when periode=4 then 'April'
		when periode=5 then 'Mei'
		when periode=6 then 'Juni'
		when periode=7 then 'Juli'
		when periode=8 then 'Agustus'
		when periode=9 then 'September'
		when periode=10 then 'Oktober'
		when periode=11 then 'November'
		when periode=12 then 'Desember' end as bulan,
		case when type=0 then 'Pekanan' else 'Bulanan' end as tipe
		FROM m_periode_absen WHERE tahun='".$tahun."'
		ORDER BY tahun desc,periode desc,type";
		$periode=DB::connection()->select($sqlperiode);
		$help = new Helper_function();
		if(($request->get('periode_gajian'))){
			
			$periode_absen=$request->get('periode_gajian');
			$sqlperiode="SELECT * FROM m_periode_absen where periode_absen_id=$periode_absen";
			$periodetgl=DB::connection()->select($sqlperiode);
			$type = $periodetgl[0]->type;
			$where = " d.periode_gajian = ".$type; 
			$appendwhere = "and";
			
			$periode_gajian=$periode[0]->type;
			
		}else{
			
			$where = '';
			$appendwhere = "";
		}
		$rekap = $help->rekap_absen($tgl_awal,$tgl_akhir,$tgl_awal,$tgl_akhir,-1,null,null,'rekap_atasan');
		$list_karyawan = $rekap['list_karyawan'] ;
		
		$hari_libur = $rekap['hari_libur'] ;
		$hari_libur_shift = $rekap['hari_libur_shift'] ;
		$tgl_awal = $rekap['tgl_awal'] ;
		$tgl_akhir = $rekap['tgl_akhir'] ;
		
		if($request->get('Cari')=="RekapExcel"){
		    $departemen=DB::connection()->select("select * from m_departemen where active=1 ORDER BY nama");
		
				$param['bulan'] = $bulan;
			$param['tahun'] = $tahun;
			$param['tgl_awal'] = $tgl_awal;
			$param['tgl_akhir'] = $tgl_akhir;
			$param['periode_absen'] = $periode_absen;
			$param['periode'] = $periode;
			$param['rekap'] = $rekap;
			$param['help'] = $help; 
			$param['hari_libur'] = $hari_libur; 
			$param['departemen'] = $departemen ; 
			$param['hari_libur_shift'] = $hari_libur_shift; 
			$param['list_karyawan'] = $list_karyawan; 
			$param['tgl_awal_lembur'] = $tgl_awal; 
				return RekapAbsenController::exports($param);
			
		}else{
			
				return view('frontend.rekap_absen.rekap_absen_atasan',compact('tgl_awal','tgl_akhir','bulan','tahun','periode','periode_absen','periode_gajian','help','tgl_awal','tgl_akhir','list_karyawan','rekap','user','rekapget','hari_libur','hari_libur_shift'));
			
		}
	}
	
	public function rekap_absen_tahunan(Request $request){
		
		$iduser=Auth::user()->id;
        $sqlidkar="select * from p_karyawan 
        	join p_karyawan_pekerjaan on p_karyawan_pekerjaan.p_karyawan_id = p_karyawan.p_karyawan_id
        where user_id=$iduser";
        $idkar=DB::connection()->select($sqlidkar);
       // echo 'id_jabatan='.$idkar[0]->m_jabatan_id;
        //echo '<br>';
        $id_karyawan = $idkar[0]->p_karyawan_id;
		$help = new Helper_function();
		$jabstruk = $help->jabatan_struktural($id_karyawan);
		$atasan = $jabstruk['atasan'];
		$bawahan = $jabstruk['bawahan'];
		$sejajar = $jabstruk['sejajar'];$tgl_awal=date('Y-m-d');
		$tgl_akhir=date('Y-m-d');
		if($request->get('tgl_awal')){
			$tgl_awal=$request->get('tgl_awal');
			$tgl_akhir=$request->get('tgl_akhir');
		}
		$tahun=date('Y');
		$bulan=date('mm');
		$periode_gajian=$request->get('periode_gajian');
		$periode_absen=$request->get('periode');
		$rekapget = $request->get('rekapget');
		//echo $periode_absen;die;
		$iduser=Auth::user()->id;
		$sqluser="SELECT p_recruitment.foto,role,p_karyawan_pekerjaan,p_karyawan_pekerjaan.m_lokasi_id FROM users
left join p_karyawan on p_karyawan.user_id=users.id
left join p_karyawan_pekerjaan on p_karyawan_pekerjaan.p_karyawan_id=p_karyawan.p_karyawan_id
		left join p_recruitment on p_recruitment.p_recruitment_id=p_karyawan.p_recruitment_id
		where users.id=$iduser";
		$user=DB::connection()->select($sqluser);
        
		$sqlperiode="SELECT m_periode_absen.*,
		case when periode=1 then 'Januari'
		when periode=2 then 'Februari'
		when periode=3 then 'Maret'
		when periode=4 then 'April'
		when periode=5 then 'Mei'
		when periode=6 then 'Juni'
		when periode=7 then 'Juli'
		when periode=8 then 'Agustus'
		when periode=9 then 'September'
		when periode=10 then 'Oktober'
		when periode=11 then 'November'
		when periode=12 then 'Desember' end as bulan,
		case when type=0 then 'Pekanan' else 'Bulanan' end as tipe
		FROM m_periode_absen WHERE tahun='".$tahun."'
		ORDER BY tahun desc,periode desc,type";
		$periode=DB::connection()->select($sqlperiode);
		$help = new Helper_function();
		if(($request->get('periode_gajian'))){
			
			$periode_absen=$request->get('periode_gajian');
			$sqlperiode="SELECT * FROM m_periode_absen where periode_absen_id=$periode_absen";
			$periodetgl=DB::connection()->select($sqlperiode);
			$type = $periodetgl[0]->type;
			$where = " d.periode_gajian = ".$type; 
			$appendwhere = "and";
			
			$periode_gajian=$periode[0]->type;
			
		}else{
			
			$where = '';
			$appendwhere = "";
		}
		$tahun = date('Y');
		$rekap = $help->rekap_absen($tahun.'-01-01',date('Y-m-d'),date('Y-m-d'),date('Y-m-d'),-1,null,null,'rekap_atasan');
		$list_karyawan = $rekap['list_karyawan'] ;
		
		$hari_libur = $rekap['hari_libur'] ;
		$hari_libur_shift = $rekap['hari_libur_shift'] ;
		$tgl_awal = $rekap['tgl_awal'] ;
		$tgl_akhir = $rekap['tgl_akhir'] ;
		
		if($request->get('Cari')=="RekapExcel"){
		    $departemen=DB::connection()->select("select * from m_departemen where active=1 ORDER BY nama");
		
				$param['bulan'] = $bulan;
			$param['tahun'] = $tahun;
			$param['tgl_awal'] = $tgl_awal;
			$param['tgl_akhir'] = $tgl_akhir;
			$param['periode_absen'] = $periode_absen;
			$param['periode'] = $periode;
			$param['rekap'] = $rekap;
			$param['help'] = $help; 
			$param['hari_libur'] = $hari_libur; 
			$param['departemen'] = $departemen ; 
			$param['hari_libur_shift'] = $hari_libur_shift; 
			$param['list_karyawan'] = $list_karyawan; 
			$param['tgl_awal_lembur'] = $tgl_awal; 
				return RekapAbsenController::exports($param);
			
		}else{
			
				return view('frontend.rekap_absen.rekap_absen_atasan_tahunan',compact('tgl_awal','tgl_akhir','bulan','tahun','periode','periode_absen','periode_gajian','help','tgl_awal','tgl_akhir','list_karyawan','rekap','user','rekapget','hari_libur','hari_libur_shift'));
			
		}
	}
}
	

  
<?php

namespace App\Http\Controllers\Backend;

use App\rekaplembur_xls;
use App\Http\Controllers\Controller;
use App\User;
use App\Helper_function;
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

class RekapAbsenController extends Controller{
  
    

	public function __construct()
    {
        $this->middleware('auth');
    }public function view(Request $request){
		$tgl_awal=date('Y-m-d');
		$tgl_akhir=date('Y-m-d');
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
		left join p_recruitment on p_recruitment.p_recruitment_id=p_karyawan.p_karyawan_id
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
			$tgl_awal=date('Y-m-d',strtotime($periodetgl[0]->tgl_awal));
			$tgl_akhir=date('Y-m-d',strtotime($periodetgl[0]->tgl_akhir));
		}else{
			$tgl_awal = $help->tambah_tanggal(date('Y-m-d'),-7);
			$tgl_akhir = date('Y-m-d');
			$where = '';
			$appendwhere = "";
		}
		$sqlabsen = "select * ,
		case 
  when (select count(*) from absen_shift join absen on absen.absen_id = absen_shift.absen_id where absen.active = 1 and p_karyawan_id=c.p_karyawan_id and absen.tgl_awal<=a.date_time and absen.tgl_akhir>=a.date_time)>=1 
  
  then (select jam_masuk from absen_shift join absen on absen.absen_id = absen_shift.absen_id where absen.active = 1 and p_karyawan_id=107 and absen_shift.tanggal<=a.date_time and  absen_shift.tanggal>=a.date_time limit 1)
 
  else (select jam_masuk from absen where absen.tgl_awal<=a.date_time and  absen.tgl_akhir>=a.date_time and absen.m_lokasi_id = c.m_lokasi_id and shifting = 0 limit 1)
end as jam_masuk
		
		
		
		from absen_log a 
		left join p_karyawan_absen b on b.no_absen = a.pin  
		left join p_karyawan_pekerjaan c on b.p_karyawan_id = c.p_karyawan_id  
		
		where a.date_time>='$tgl_awal' and a.date_time<='$tgl_akhir 23:59:59'";
		//echo $sqlabsen;
		$rekap = array();$absen=DB::connection()->select($sqlabsen);
		foreach($absen as $absen){
			$date = date('Y-m-d',strtotime($absen->date_time));
			$time = date('H:i:s',strtotime($absen->date_time));
			$time2 = date('H:i:s',strtotime($absen->date_time));
			if($absen->ver==1){
				
				$rekap[$absen->p_karyawan_id][$date]['a']['masuk'] = $time;
					
				if(!isset($rekap[$absen->p_karyawan_id]['total']['absen_masuk']))
				$rekap[$absen->p_karyawan_id]['total']['absen_masuk'] = 1;
				else
				$rekap[$absen->p_karyawan_id]['total']['absen_masuk'] += 1;
				
				$lokasi_id = $absen->m_lokasi_id;	
				
				if($time> $absen->jam_masuk){
					
					$rekap[$absen->p_karyawan_id][$date]['a']['terlambat'] = 1;
					if(isset($rekap[$absen->p_karyawan_id]['total']['terlambat']))
					$rekap[$absen->p_karyawan_id]['total']['terlambat'] += 1;
					else
					$rekap[$absen->p_karyawan_id]['total']['terlambat'] = 1;
				}
				else	
				$rekap[$absen->p_karyawan_id][$date]['a']['terlambat'] = 0;
				
			}
			else if($absen->ver==2)
			$rekap[$absen->p_karyawan_id][$date]['a']['keluar'] = $time;
		}
		$sqllembur = "Select m_jenis_ijin.nama as nama_ijin,m_jenis_ijin.tipe,t_permit.m_jenis_ijin_id,t_permit.lama,t_permit.keterangan,t_permit.p_karyawan_id,t_permit.tgl_awal,t_permit.tgl_akhir,t_permit.jam_awal
		 from t_permit 
		 join m_jenis_ijin on m_jenis_ijin.m_jenis_ijin_id = t_permit.m_jenis_ijin_id 
		where t_permit.tgl_awal>='$tgl_awal' and 
			t_permit.tgl_awal<='$tgl_akhir 23:59' and 
			t_permit.m_jenis_ijin_id != 22 and 
			t_permit.status_appr_1 = 1 ORDER BY t_permit.p_karyawan_id asc";
		$lembur=DB::connection()->select($sqllembur);
		$karyawan = array();
		
		//var_dump($lembur); die;
		foreach($lembur as $lembur){
			if(!in_array($lembur->p_karyawan_id,$karyawan))
			$karyawan[] = $lembur->p_karyawan_id; 
			
			$date = $lembur->tgl_awal;
			for($i = 0; $i <= $help->hitunghari($lembur->tgl_awal,$lembur->tgl_akhir); $i++){
				$rekap[$lembur->p_karyawan_id][$date]['ci']['nama_ijin'] = $lembur->nama_ijin;
				$rekap[$lembur->p_karyawan_id][$date]['ci']['keterangan'] = $lembur->keterangan;
				$rekap[$lembur->p_karyawan_id][$date]['ci']['lama'] = $lembur->lama;
				$rekap[$lembur->p_karyawan_id][$date]['ci']['jam_awal'] = $lembur->jam_awal;
				$rekap[$lembur->p_karyawan_id][$date]['ci']['tipe'] = $lembur->tipe; 
				$rekap[$lembur->p_karyawan_id][$date]['ci']['m_jenis_ijin_id'] = $lembur->m_jenis_ijin_id; 
				$date = $help->tambah_tanggal($date,1);
				
					
			}
			
		}
		$sqllembur = "Select *
		
		
		from t_permit where tgl_awal>='$tgl_awal' and tgl_awal<='$tgl_akhir 23:00' and m_jenis_ijin_id = 22   ORDER BY p_karyawan_id asc";
		$lembur=DB::connection()->select($sqllembur);
		//$karyawan = array();
		//$rekap = array();
	
		//var_dump($lembur); die;status_appr_1 = 1
		foreach($lembur as $lembur){
			if(!in_array($lembur->p_karyawan_id,$karyawan))
			$karyawan[] = $lembur->p_karyawan_id; 
			
			if(!isset($rekap[$lembur->p_karyawan_id]['lembur'])){
				$rekap[$lembur->p_karyawan_id]['lembur']['total_pengajuan'] = 0;
				$rekap[$lembur->p_karyawan_id]['lembur']['total_pending'] = 0;
				$rekap[$lembur->p_karyawan_id]['lembur']['total_approve'] = 0;
				$rekap[$lembur->p_karyawan_id]['lembur']['total_tolak'] = 0;
				$rekap[$lembur->p_karyawan_id]['lembur']['tgl_pending'] = '';
			}
				$rekap[$lembur->p_karyawan_id]['lembur']['total_pengajuan'] += $lembur->lama;
			if($lembur->status_appr_1==1){
				$rekap[$lembur->p_karyawan_id]['lembur']['total_approve'] += $lembur->lama;
				
				$rekap[$lembur->p_karyawan_id][$lembur->tgl_awal]['lama'] = $lembur->lama;
				$rekap[$lembur->p_karyawan_id][$lembur->tgl_awal]['jam_awal'] = $lembur->jam_awal;
				$rekap[$lembur->p_karyawan_id][$lembur->tgl_awal]['jam_akhir'] = $lembur->jam_akhir;
			}else if($lembur->status_appr_1==2){
				$rekap[$lembur->p_karyawan_id]['lembur']['total_tolak'] += $lembur->lama;
			}else if($lembur->status_appr_1==3){
				$rekap[$lembur->p_karyawan_id]['lembur']['total_pending'] += $lembur->lama;
				$rekap[$lembur->p_karyawan_id]['lembur']['tgl_pending'] .= $lembur->tgl_awal.' | ';
				
			}
		}
		if($user[0]->role==3	  ){ 
		 $id_lokasi = $user[0]->m_lokasi_id;
			$whereLokasi = "AND d.m_lokasi_id = $id_lokasi";			
						
		}else{
			$whereLokasi = "AND d.m_lokasi_id != 5";			
			
		}
		$sql = "SELECT c.p_karyawan_id,c.nama,c.nik,m_lokasi.kode as nmlokasi,m_departemen.nama as departemen , f.m_pangkat_id ,m_jabatan.nama as nmjabatan 
		FROM p_karyawan c 
		LEFT JOIN p_karyawan_pekerjaan d on d.p_karyawan_id=c.p_karyawan_id  
		LEFT JOIN m_departemen on m_departemen.m_departemen_id=d.m_departemen_id
		LEFT JOIN m_lokasi on m_lokasi.m_lokasi_id=d.m_lokasi_id
		LEFT JOIN m_jabatan on m_jabatan.m_jabatan_id=d.m_jabatan_id 
		LEFT JOIN m_jabatan f on d.m_jabatan_id=f.m_jabatan_id 
		WHERE $where $appendwhere '$tgl_awal' >= c.tgl_bergabung and c.active = 1
		--AND d.m_departemen_id != 17
		$whereLokasi
		AND f.m_pangkat_id != 6
		and c.p_karyawan_id not in(102,188)
		AND f.nama not ilike '%freelance%'       
		
		
		order by c.nama,m_departemen.nama";;
		$list_karyawan = DB::connection()->select($sql);
		$sql="select * from m_hari_libur where tanggal >= '$tgl_awal' and tanggal <='$tgl_akhir'";
		$harilibur = DB::connection()->select($sql);
		$hari_libur = array();
		foreach($harilibur as $libur){
			$hari_libur[] = $libur->tanggal;
		}
		
		
		
		
		$hari_libur_shift = array();
		$sql="select * from absen_libur_shift where tanggal >= '$tgl_awal' and tanggal <='$tgl_akhir'";
		$harilibur = DB::connection()->select($sql);
		foreach($hari_libur$harilibur as $libur){
			$hari_libur_shift[$libur->tanggal][$libur->p_karyawan_id] = 1;
		}
		if($request->get('Cari')=="RekapExcel"){
			$nama_file = 'Rekap Lembur ' . date('d-m-Y', strtotime($tgl_awal)) . ':' . date('d-m-Y', strtotime($tgl_akhir));
			$param['bulan'] = $bulan;
			$param['tahun'] = $tahun;
			$param['tgl_awal'] = $tgl_awal;
			$param['tgl_akhir'] = $tgl_akhir;
			$param['periode_absen'] = $periode_absen;
			$param['periode'] = $periode;
			$param['rekap'] = $rekap;
			$param['help'] = $help; 
			$param['hari_libur'] = $hari_libur; 
			$param['hari_libur_shift'] = $hari_libur_shift; 
			$param['list_karyawan'] = $list_karyawan; 
			if($rekapget =='Rekap Izin') {
				return RekapAbsenController::exportsIzin($param);
			}elseif($rekapget =='Rekap Lembur') {
				return RekapAbsenController::exportsLembur($param);
			}elseif($rekapget =='Rekap Perdin') {
				return RekapAbsenController::exportsPerdin($param);
			}elseif($rekapget =='Rekap Cuti') {
				return RekapAbsenController::exportsCuti($param);
			}else{
				return RekapAbsenController::exports($param);
			}
		}else{
			if($rekapget =='Rekap Izin') {
				
				return view('backend.rekap_absen.view_rekap_ijin',compact('tgl_awal','tgl_akhir','bulan','tahun','periode','periode_absen','periode_gajian','help','tgl_awal','tgl_akhir','list_karyawan','rekap','user','rekapget','hari_libur','hari_libur_shift'));
			}elseif($rekapget =='Rekap Lembur') {
				
				return view('backend.rekap_absen.view_rekap_lembur',compact('tgl_awal','tgl_akhir','bulan','tahun','periode','periode_absen','periode_gajian','help','tgl_awal','tgl_akhir','list_karyawan','rekap','user','rekapget','hari_libur','hari_libur_shift'));
			}elseif($rekapget =='Rekap Perdin') {
				
				return view('backend.rekap_absen.view_rekap_perdin',compact('tgl_awal','tgl_akhir','bulan','tahun','periode','periode_absen','periode_gajian','help','tgl_awal','tgl_akhir','list_karyawan','rekap','user','rekapget','hari_libur','hari_libur_shift'));
			}elseif($rekapget =='Rekap Cuti') {
				
				return view('backend.rekap_absen.view_rekap_cuti',compact('tgl_awal','tgl_akhir','bulan','tahun','periode','periode_absen','periode_gajian','help','tgl_awal','tgl_akhir','list_karyawan','rekap','user','rekapget','hari_libur','hari_libur_shift'));
			}else{
				return view('backend.rekap_absen.view_rekap_absen',compact('tgl_awal','tgl_akhir','bulan','tahun','periode','periode_absen','periode_gajian','help','tgl_awal','tgl_akhir','list_karyawan','rekap','user','rekapget','hari_libur','hari_libur_shift'));
				
			}
		}
	}

  
	public function exports($param){
			
		$bulan = $param['bulan'];
		$tahun =  $param['tahun'];
		$tgl_awal = $param['tgl_awal'];
		$tgl_akhir =$param['tgl_akhir'];
		$periode_absen   = $param['periode_absen'];
		$periode  = $param['periode'];
		$rekap = $param['rekap'];
		$list_karyawan = $param['list_karyawan'];
		$hari_libur = $param['hari_libur'];
		$hari_libur_shift = $param['hari_libur_shift'];
		$help = New Helper_function();
		$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();
		$sheet->setCellValue('A1', 'NIK');
		$sheet->setCellValue('B1', 'Nama');
		$sheet->setCellValue('C1', 'Departemen');
		$date = $tgl_awal;
		for($i = 3; $i <= $help->hitunghari($tgl_awal,$tgl_akhir)+3; $i++){
			$sheet->setCellValue($help->toAlpha($i).'1', $date);
			//echo ' <th>'.$date.'</th>';
			$date = $help->tambah_tanggal($date,1);
		}
		
		$sheet->setCellValue($help->toAlpha($i).'1', 'Absen Masuk');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'1', 'Cuti');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'1', 'IPG');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'1', 'IHK');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'1', 'IPD');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'1', 'ALPHA');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'1', 'SAKIT');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'1', 'Total Absen Masuk');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'1', 'Total Masuk');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'1', 'Hari Kerja');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'1', 'Terlambat');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'1', 'Alpha');
		$rows = 2;

		$sheet->getColumnDimension('A')->setAutoSize(true);
		$sheet->getColumnDimension('B')->setAutoSize(true);
		$sheet->getColumnDimension('C')->setAutoSize(true);
		$sheet->getColumnDimension('D')->setAutoSize(true);
		$sheet->getColumnDimension('E')->setAutoSize(true);
		$sheet->getColumnDimension('F')->setAutoSize(true);
		$sheet->getColumnDimension('G')->setAutoSize(true);
		$sheet->getColumnDimension('H')->setAutoSize(true);
		$sheet->getColumnDimension('I')->setAutoSize(true);
		$sheet->getColumnDimension('J')->setAutoSize(true);
		$sheet->getColumnDimension('K')->setAutoSize(true);
		$sheet->getColumnDimension('L')->setAutoSize(true);
		$sheet->getColumnDimension('M')->setAutoSize(true);
		$sheet->getColumnDimension('N')->setAutoSize(true);
		$sheet->getColumnDimension('O')->setAutoSize(true);
		$sheet->getColumnDimension('P')->setAutoSize(true);
		$sheet->getColumnDimension('Q')->setAutoSize(true);
		$sheet->getColumnDimension('R')->setAutoSize(true);
		$sheet->getColumnDimension('S')->setAutoSize(true);
		$sheet->getColumnDimension('T')->setAutoSize(true);
		$sheet->getColumnDimension('U')->setAutoSize(true);
		$sheet->getColumnDimension('V')->setAutoSize(true);
		$sheet->getColumnDimension('W')->setAutoSize(true);
		$sheet->getColumnDimension('X')->setAutoSize(true);
		$sheet->getColumnDimension('Y')->setAutoSize(true);
		$sheet->getColumnDimension('Z')->setAutoSize(true);
		$sheet->getColumnDimension('AA')->setAutoSize(true);
		$sheet->getColumnDimension('AB')->setAutoSize(true);
		$sheet->getColumnDimension('AC')->setAutoSize(true);
		$sheet->getColumnDimension('AD')->setAutoSize(true);
		$sheet->getColumnDimension('AE')->setAutoSize(true);
		$sheet->getColumnDimension('AF')->setAutoSize(true);
		$sheet->getColumnDimension('AG')->setAutoSize(true);
		$sheet->getColumnDimension('AH')->setAutoSize(true);
		$sheet->getColumnDimension('AI')->setAutoSize(true);
		$sheet->getColumnDimension('AJ')->setAutoSize(true);
		$sheet->getColumnDimension('AK')->setAutoSize(true);
		$sheet->getColumnDimension('AL')->setAutoSize(true);
		$sheet->getColumnDimension('AM')->setAutoSize(true);
		$sheet->getColumnDimension('AN')->setAutoSize(true);

		if(!empty($list_karyawan)){
	
			foreach($list_karyawan as $list_karyawan){

			
				$sheet->setCellValue('A' . $rows, $list_karyawan->nik);
				$sheet->setCellValue('B' . $rows, $list_karyawan->nama);
				$sheet->setCellValue('C' . $rows, $list_karyawan->departemen);
				$date = $tgl_awal;
			    $rekap[$list_karyawan->p_karyawan_id]['total']['cuti'] =0;
			                        
										$rekap[$list_karyawan->p_karyawan_id]['total']['ipd'] = 0;
										$rekap[$list_karyawan->p_karyawan_id]['total']['ihk'] = 0;
										$rekap[$list_karyawan->p_karyawan_id]['total']['ihk'] = 0;
										$rekap[$list_karyawan->p_karyawan_id]['total']['ipg'] = 0;                    
										$rekap[$list_karyawan->p_karyawan_id]['total']['sakit'] = 0;                    
										$rekap[$list_karyawan->p_karyawan_id]['total']['alpha'] = 0;                    
										$rekap[$list_karyawan->p_karyawan_id]['total']['alphaList'] = '';                    
				for($i = 3; $i <= $help->hitunghari($tgl_awal,$tgl_akhir)+3; $i++){
					$content = "";
					$warna = 'FFFFFF';
					if(isset($rekap[$list_karyawan->p_karyawan_id][$date]['a']['masuk'])){
						if($rekap[$list_karyawan->p_karyawan_id][$date]['a']['terlambat']  and $list_karyawan->m_pangkat_id!=5)
						$warna = 'FFA500';
						$content .= ' '.$rekap[$list_karyawan->p_karyawan_id][$date]['a']['masuk'];
						
					}
					if(isset($rekap[$list_karyawan->p_karyawan_id][$date]['a']['keluar'])){
											
							$content.=' 
							s/d  '.$rekap[$list_karyawan->p_karyawan_id][$date]['a']['keluar'].'
							';	
						}
					if(isset($rekap[$list_karyawan->p_karyawan_id][$date]['ci']['nama_ijin'])){
						if(!in_array($help->nama_hari($date),array('Minggu','Sabtu'))  ){
						if(in_array($rekap[$list_karyawan->p_karyawan_id][$date]['ci']['m_jenis_ijin_id'],array(4,7,12,13,14,15,16,17))){
											$rekap[$list_karyawan->p_karyawan_id]['total']['ihk'] += 1;
										}else if($rekap[$list_karyawan->p_karyawan_id][$date]['ci']['m_jenis_ijin_id']==1){
											$rekap[$list_karyawan->p_karyawan_id]['total']['ipg'] += 1;
										}else if($rekap[$list_karyawan->p_karyawan_id][$date]['ci']['m_jenis_ijin_id']==24){
											$rekap[$list_karyawan->p_karyawan_id]['total']['ipd'] += 1;
										}else if($rekap[$list_karyawan->p_karyawan_id][$date]['ci']['m_jenis_ijin_id']==20){
											$rekap[$list_karyawan->p_karyawan_id]['total']['sakit'] += 1;
										}
										
										if($rekap[$list_karyawan->p_karyawan_id][$date]['ci']['tipe']==3){
											$rekap[$list_karyawan->p_karyawan_id]['total']['cuti'] += 1;
											
											
										}
										if($rekap[$list_karyawan->p_karyawan_id][$date]['ci']['tipe']==3)
											$warna = '008000';
											else if($rekap[$list_karyawan->p_karyawan_id][$date]['ci']['tipe']==4)
											$warna = '80080';
											else if($rekap[$list_karyawan->p_karyawan_id][$date]['ci']['nama_ijin']=='IZIN DATANG TERLAMBAT')
											$warna = 'FF0000';
											else if(in_array($rekap[$list_karyawan->p_karyawan_id][$date]['ci']['m_jenis_ijin_id'],array(4,7,12,13,14,15,16,17)))
											$warna = 'fb0b7b';
											else if($rekap[$list_karyawan->p_karyawan_id][$date]['ci']['m_jenis_ijin_id']==1)
											$warna = '0000FF';
											else if($rekap[$list_karyawan->p_karyawan_id][$date]['ci']['nama_ijin']=='IZIN DATANG TERLAMBAT')
											$warna = 'FFA500';
						if($rekap[$list_karyawan->p_karyawan_id][$date]['ci']['nama_ijin']
												!= 'IZIN DATANG TERLAMBAT')
														
											$content .= ' '.$rekap[$list_karyawan->p_karyawan_id][$date]['ci']['nama_ijin'].'								';	
											if($rekap[$list_karyawan->p_karyawan_id][$date]['ci']['nama_ijin']
												== 'IZIN PERJALANAN DINAS'){
											
											}
						}
					}
										
					if($content==""){
						
						if(!in_array($help->nama_hari($date),array('Minggu','Sabtu')) and !in_array($date,$hari_libur) and !isset($hari_libur_shift[$date][$list_karyawan->p_karyawan_id])   ){
							$rekap[$list_karyawan->p_karyawan_id]['total']['alpha'] += 1;
							$rekap[$list_karyawan->p_karyawan_id]['total']['alphaList'] .= $date.' | ' ;
						}		
						if(in_array($help->nama_hari($date),array('Minggu','Sabtu')) or in_array($date,$hari_libur)or isset($hari_libur_shift[$date][$list_karyawan->p_karyawan_id])   ){
									$warna = 'FF0000';
									$content .= '';
								}else{
									$warna = 'FFFF00';
									$content .= '00:00:00';
								}			
					
					}
					
					
					if($warna!='FFFFFF' and $warna != 'FFFF00')
					$font = 'FFFFFF';
					else
					$font = '000000';
						
					$sheet->setCellValue($help->toAlpha($i) . $rows, $content);
					$spreadsheet->getActiveSheet()->getStyle($help->toAlpha($i).$rows)->applyFromArray([
							'fill' => [
								'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
								'startColor' => [
									'rgb' => $warna,
								]
							],
							'font' => [
						        'color' => [
									'rgb' => $font,
								]
							]
						]);						
											
					
					$date = $help->tambah_tanggal($date,1);
				}
                        		
			
				
				$masuk = isset($rekap[$list_karyawan->p_karyawan_id]['total']['absen_masuk'])?$rekap[$list_karyawan->p_karyawan_id]['total']['absen_masuk']:0;
				$cuti = isset($rekap[$list_karyawan->p_karyawan_id]['total']['cuti'])?$rekap[$list_karyawan->p_karyawan_id]['total']['cuti']:0;
				$ipg = isset($rekap[$list_karyawan->p_karyawan_id]['total']['ipg'])?$rekap[$list_karyawan->p_karyawan_id]['total']['ipg']:0;
				$ipd = isset($rekap[$list_karyawan->p_karyawan_id]['total']['ipd'])?$rekap[$list_karyawan->p_karyawan_id]['total']['ipd']:0;
				$izin = isset($rekap[$list_karyawan->p_karyawan_id]['total']['ihk'])?$rekap[$list_karyawan->p_karyawan_id]['total']['ihk']:0;
				$alpha = isset($rekap[$list_karyawan->p_karyawan_id]['total']['alpha'])?$rekap[$list_karyawan->p_karyawan_id]['total']['alpha']:0;
				$alphaList = isset($rekap[$list_karyawan->p_karyawan_id]['total']['alphaList'])?$rekap[$list_karyawan->p_karyawan_id]['total']['alphaList']:0;
				$sakit = isset($rekap[$list_karyawan->p_karyawan_id]['total']['sakit'])?$rekap[$list_karyawan->p_karyawan_id]['total']['sakit']:0;
				
				$terlambat = isset($rekap[$list_karyawan->p_karyawan_id]['total']['terlambat'])?$rekap[$list_karyawan->p_karyawan_id]['total']['terlambat']:0;
				
		
				$sheet->setCellValue($help->toAlpha($i). $rows, $masuk);$i++;
				$sheet->setCellValue($help->toAlpha($i). $rows, $cuti);$i++;
				$sheet->setCellValue($help->toAlpha($i). $rows, $ipg);$i++;
				$sheet->setCellValue($help->toAlpha($i). $rows, $izin);$i++;
				$sheet->setCellValue($help->toAlpha($i). $rows, $ipd);$i++;
				$sheet->setCellValue($help->toAlpha($i). $rows, $alpha);$i++;
				$sheet->setCellValue($help->toAlpha($i). $rows, $sakit);$i++;
				$sheet->setCellValue($help->toAlpha($i).$rows, $masuk+$ipd);$i++;
				$sheet->setCellValue($help->toAlpha($i).$rows, $masuk+$cuti+$ipg+$izin+$ipd);$i++;
				$sheet->setCellValue($help->toAlpha($i).$rows, $masuk+$cuti+$ipg+$izin+$ipd+$alpha);$i++;
				$sheet->setCellValue($help->toAlpha($i). $rows, $terlambat);$i++;
				$sheet->setCellValue($help->toAlpha($i). $rows, $alphaList);
				//echo substr($absen->a,0,5);die;
				$rows++;
			}
		}
		
		
		$type = 'xlsx';
		$fileName = "absen ".$tgl_awal." - ".$tgl_akhir.".".$type;
		if($type == 'xlsx'){
			$writer = new Xlsx($spreadsheet);
		} else if($type == 'xls'){
			$writer = new Xls($spreadsheet);
		}
		$writer->save("export/".$fileName);
		header("Content-Type: application/vnd.ms-excel");
		return redirect(url('/')."/export/".$fileName);
	}
    public function exportsIzin($param){
			
		$bulan = $param['bulan'];
		$tahun =  $param['tahun'];
		$tgl_awal = $param['tgl_awal'];
		$tgl_akhir =$param['tgl_akhir'];
		$periode_absen   = $param['periode_absen'];
		$periode  = $param['periode'];
		$rekap = $param['rekap'];
		$list_karyawan = $param['list_karyawan'];
		$hari_libur = $param['hari_libur'];
		$hari_libur_shift = $param['hari_libur_shift'];
		$help = New Helper_function();
		$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();
		$sheet->setCellValue('A1', 'NIK');
		$sheet->setCellValue('B1', 'Nama');
		$sheet->setCellValue('C1', 'Departemen');
		$date = $tgl_awal;
		for($i = 3; $i <= $help->hitunghari($tgl_awal,$tgl_akhir)+3; $i++){
			$sheet->setCellValue($help->toAlpha($i).'1', $date);
			//echo ' <th>'.$date.'</th>';
			$date = $help->tambah_tanggal($date,1);
		}
		
		$sheet->setCellValue($help->toAlpha($i).'1', 'IPG');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'1', 'IHK');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'1', 'IPD');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'1', 'Total');	$i++;
		$rows = 3;

		$sheet->getColumnDimension('A')->setAutoSize(true);
		$sheet->getColumnDimension('B')->setAutoSize(true);
		$sheet->getColumnDimension('C')->setAutoSize(true);
		$sheet->getColumnDimension('D')->setAutoSize(true);
		$sheet->getColumnDimension('E')->setAutoSize(true);
		$sheet->getColumnDimension('F')->setAutoSize(true);
		$sheet->getColumnDimension('G')->setAutoSize(true);
		$sheet->getColumnDimension('H')->setAutoSize(true);
		$sheet->getColumnDimension('I')->setAutoSize(true);
		$sheet->getColumnDimension('J')->setAutoSize(true);
		$sheet->getColumnDimension('K')->setAutoSize(true);
		$sheet->getColumnDimension('L')->setAutoSize(true);
		$sheet->getColumnDimension('M')->setAutoSize(true);
		$sheet->getColumnDimension('N')->setAutoSize(true);
		$sheet->getColumnDimension('O')->setAutoSize(true);
		$sheet->getColumnDimension('P')->setAutoSize(true);
		$sheet->getColumnDimension('Q')->setAutoSize(true);
		$sheet->getColumnDimension('R')->setAutoSize(true);
		$sheet->getColumnDimension('S')->setAutoSize(true);
		$sheet->getColumnDimension('T')->setAutoSize(true);
		$sheet->getColumnDimension('U')->setAutoSize(true);
		$sheet->getColumnDimension('V')->setAutoSize(true);
		$sheet->getColumnDimension('W')->setAutoSize(true);
		$sheet->getColumnDimension('X')->setAutoSize(true);
		$sheet->getColumnDimension('Y')->setAutoSize(true);
		$sheet->getColumnDimension('Z')->setAutoSize(true);
		$sheet->getColumnDimension('AA')->setAutoSize(true);
		$sheet->getColumnDimension('AB')->setAutoSize(true);
		$sheet->getColumnDimension('AC')->setAutoSize(true);
		$sheet->getColumnDimension('AD')->setAutoSize(true);
		$sheet->getColumnDimension('AE')->setAutoSize(true);
		$sheet->getColumnDimension('AF')->setAutoSize(true);
		$sheet->getColumnDimension('AG')->setAutoSize(true);
		$sheet->getColumnDimension('AH')->setAutoSize(true);
		$sheet->getColumnDimension('AI')->setAutoSize(true);
		$sheet->getColumnDimension('AJ')->setAutoSize(true);
		$sheet->getColumnDimension('AK')->setAutoSize(true);
		$sheet->getColumnDimension('AL')->setAutoSize(true);
		$sheet->getColumnDimension('AM')->setAutoSize(true);
		$sheet->getColumnDimension('AN')->setAutoSize(true);

		if(!empty($list_karyawan)){
	
			foreach($list_karyawan as $list_karyawan){

			
				$sheet->setCellValue('A' . $rows, $list_karyawan->nik);
				$sheet->setCellValue('B' . $rows, $list_karyawan->nama);
				$sheet->setCellValue('C' . $rows, $list_karyawan->departemen);
				$date = $tgl_awal;
			    $rekap[$list_karyawan->p_karyawan_id]['total']['cuti'] =0;
			                        
										$rekap[$list_karyawan->p_karyawan_id]['total']['ipd'] = 0;
										$rekap[$list_karyawan->p_karyawan_id]['total']['ihk'] = 0;
										$rekap[$list_karyawan->p_karyawan_id]['total']['ihk'] = 0;
										$rekap[$list_karyawan->p_karyawan_id]['total']['ipg'] = 0;                    
										$rekap[$list_karyawan->p_karyawan_id]['total']['sakit'] = 0;                    
										$rekap[$list_karyawan->p_karyawan_id]['total']['alpha'] = 0;                    
										$rekap[$list_karyawan->p_karyawan_id]['total']['alphaList'] = '';                    
				for($i = 3; $i <= $help->hitunghari($tgl_awal,$tgl_akhir)+3; $i++){
					$content = "";
					$warna = 'FFFFFF';
					
					if(isset($rekap[$list_karyawan->p_karyawan_id][$date]['ci']['nama_ijin'])){
						if(!in_array($help->nama_hari($date),array('Minggu','Sabtu'))  ){
						if(in_array($rekap[$list_karyawan->p_karyawan_id][$date]['ci']['m_jenis_ijin_id'],array(4,7,12,13,14,15,16,17))){
											$rekap[$list_karyawan->p_karyawan_id]['total']['ihk'] += 1;
										}else if($rekap[$list_karyawan->p_karyawan_id][$date]['ci']['m_jenis_ijin_id']==1){
											$rekap[$list_karyawan->p_karyawan_id]['total']['ipg'] += 1;
										}else if($rekap[$list_karyawan->p_karyawan_id][$date]['ci']['m_jenis_ijin_id']==24){
											$rekap[$list_karyawan->p_karyawan_id]['total']['ipd'] += 1;
										}else if($rekap[$list_karyawan->p_karyawan_id][$date]['ci']['m_jenis_ijin_id']==20){
											$rekap[$list_karyawan->p_karyawan_id]['total']['sakit'] += 1;
										}
										
										if($rekap[$list_karyawan->p_karyawan_id][$date]['ci']['tipe']==3){
											$rekap[$list_karyawan->p_karyawan_id]['total']['cuti'] += 1;
											
											
										}
										if($rekap[$list_karyawan->p_karyawan_id][$date]['ci']['tipe']==1)
						$warna = '840184';
						else if($rekap[$list_karyawan->p_karyawan_id][$date]['ci']['tipe']==4)
						$warna = '0000FF';
											
						else if($rekap[$list_karyawan->p_karyawan_id][$date]['ci']['nama_ijin']=='IZIN DATANG TERLAMBAT')
						$warna = 'red';
						if($rekap[$list_karyawan->p_karyawan_id][$date]['ci']['nama_ijin']
												!= 'IZIN DATANG TERLAMBAT' and $rekap[$list_karyawan->p_karyawan_id][$date]['ci']['tipe']!= 1)
														
											$content .= ' '.$rekap[$list_karyawan->p_karyawan_id][$date]['ci']['nama_ijin'].'								';	
											if($rekap[$list_karyawan->p_karyawan_id][$date]['ci']['nama_ijin']
												== 'IZIN PERJALANAN DINAS'){
											
											}
						}
					}
										
					if($content==""){
						$content .= '';
						if(!in_array($help->nama_hari($date),array('Minggu','Sabtu')) and !in_array($date,$hari_libur)   ){
							$rekap[$list_karyawan->p_karyawan_id]['total']['alpha'] += 1;
							$rekap[$list_karyawan->p_karyawan_id]['total']['alphaList'] .= $date.' | ' ;
						}					
					
					}
					
					
					if($warna!='FFFFFF' and $warna != 'FFFF00')
					$font = 'FFFFFF';
					else
					$font = '000000';
						
					$sheet->setCellValue($help->toAlpha($i) . $rows, $content);
					$spreadsheet->getActiveSheet()->getStyle($help->toAlpha($i).$rows)->applyFromArray([
							'fill' => [
								'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
								'startColor' => [
									'rgb' => $warna,
								]
							],
							'font' => [
						        'color' => [
									'rgb' => $font,
								]
							]
						]);						
											
					
					$date = $help->tambah_tanggal($date,1);
				}
                        		
			
				
				$masuk = isset($rekap[$list_karyawan->p_karyawan_id]['total']['absen_masuk'])?$rekap[$list_karyawan->p_karyawan_id]['total']['absen_masuk']:0;
				$cuti = isset($rekap[$list_karyawan->p_karyawan_id]['total']['cuti'])?$rekap[$list_karyawan->p_karyawan_id]['total']['cuti']:0;
				$ipg = isset($rekap[$list_karyawan->p_karyawan_id]['total']['ipg'])?$rekap[$list_karyawan->p_karyawan_id]['total']['ipg']:0;
				$ipd = isset($rekap[$list_karyawan->p_karyawan_id]['total']['ipd'])?$rekap[$list_karyawan->p_karyawan_id]['total']['ipd']:0;
				$izin = isset($rekap[$list_karyawan->p_karyawan_id]['total']['ihk'])?$rekap[$list_karyawan->p_karyawan_id]['total']['ihk']:0;
				$alpha = isset($rekap[$list_karyawan->p_karyawan_id]['total']['alpha'])?$rekap[$list_karyawan->p_karyawan_id]['total']['alpha']:0;
				$alphaList = isset($rekap[$list_karyawan->p_karyawan_id]['total']['alphaList'])?$rekap[$list_karyawan->p_karyawan_id]['total']['alphaList']:0;
				$sakit = isset($rekap[$list_karyawan->p_karyawan_id]['total']['sakit'])?$rekap[$list_karyawan->p_karyawan_id]['total']['sakit']:0;
				
				$terlambat = isset($rekap[$list_karyawan->p_karyawan_id]['total']['terlambat'])?$rekap[$list_karyawan->p_karyawan_id]['total']['terlambat']:0;
				
		
				$sheet->setCellValue($help->toAlpha($i). $rows, $ipg);$i++;
				$sheet->setCellValue($help->toAlpha($i). $rows, $izin);$i++;
				$sheet->setCellValue($help->toAlpha($i). $rows, $ipd);$i++;
				$sheet->setCellValue($help->toAlpha($i).$rows, $ipg+$izin+$ipd);$i++;
				//echo substr($absen->a,0,5);die;
				$rows++;
			}
		}
		
		
		$type = 'xlsx';
		$fileName = "Izin ".$tgl_awal." - ".$tgl_akhir.".".$type;
		if($type == 'xlsx'){
			$writer = new Xlsx($spreadsheet);
		} else if($type == 'xls'){
			$writer = new Xls($spreadsheet);
		}
		$writer->save("export/".$fileName);
		header("Content-Type: application/vnd.ms-excel");
		return redirect(url('/')."/export/".$fileName);
	}
    public function exportsPerdin($param){
			
		$bulan = $param['bulan'];
		$tahun =  $param['tahun'];
		$tgl_awal = $param['tgl_awal'];
		$tgl_akhir =$param['tgl_akhir'];
		$periode_absen   = $param['periode_absen'];
		$periode  = $param['periode'];
		$rekap = $param['rekap'];
		$list_karyawan = $param['list_karyawan'];
		$hari_libur = $param['hari_libur'];
		$hari_libur_shift = $param['hari_libur_shift'];
		$help = New Helper_function();
		$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();
		$sheet->setCellValue('A1', 'NIK');
		$sheet->setCellValue('B1', 'Nama');
		$sheet->setCellValue('C1', 'Departemen');
		$date = $tgl_awal;
		for($i = 3; $i <= $help->hitunghari($tgl_awal,$tgl_akhir)+3; $i++){
			$sheet->setCellValue($help->toAlpha($i).'1', $date);
			//echo ' <th>'.$date.'</th>';
			$date = $help->tambah_tanggal($date,1);
		}
		
		$sheet->setCellValue($help->toAlpha($i).'1', 'IPG');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'1', 'IHK');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'1', 'IPD');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'1', 'Total');	$i++;
		$rows = 3;

		$sheet->getColumnDimension('A')->setAutoSize(true);
		$sheet->getColumnDimension('B')->setAutoSize(true);
		$sheet->getColumnDimension('C')->setAutoSize(true);
		$sheet->getColumnDimension('D')->setAutoSize(true);
		$sheet->getColumnDimension('E')->setAutoSize(true);
		$sheet->getColumnDimension('F')->setAutoSize(true);
		$sheet->getColumnDimension('G')->setAutoSize(true);
		$sheet->getColumnDimension('H')->setAutoSize(true);
		$sheet->getColumnDimension('I')->setAutoSize(true);
		$sheet->getColumnDimension('J')->setAutoSize(true);
		$sheet->getColumnDimension('K')->setAutoSize(true);
		$sheet->getColumnDimension('L')->setAutoSize(true);
		$sheet->getColumnDimension('M')->setAutoSize(true);
		$sheet->getColumnDimension('N')->setAutoSize(true);
		$sheet->getColumnDimension('O')->setAutoSize(true);
		$sheet->getColumnDimension('P')->setAutoSize(true);
		$sheet->getColumnDimension('Q')->setAutoSize(true);
		$sheet->getColumnDimension('R')->setAutoSize(true);
		$sheet->getColumnDimension('S')->setAutoSize(true);
		$sheet->getColumnDimension('T')->setAutoSize(true);
		$sheet->getColumnDimension('U')->setAutoSize(true);
		$sheet->getColumnDimension('V')->setAutoSize(true);
		$sheet->getColumnDimension('W')->setAutoSize(true);
		$sheet->getColumnDimension('X')->setAutoSize(true);
		$sheet->getColumnDimension('Y')->setAutoSize(true);
		$sheet->getColumnDimension('Z')->setAutoSize(true);
		$sheet->getColumnDimension('AA')->setAutoSize(true);
		$sheet->getColumnDimension('AB')->setAutoSize(true);
		$sheet->getColumnDimension('AC')->setAutoSize(true);
		$sheet->getColumnDimension('AD')->setAutoSize(true);
		$sheet->getColumnDimension('AE')->setAutoSize(true);
		$sheet->getColumnDimension('AF')->setAutoSize(true);
		$sheet->getColumnDimension('AG')->setAutoSize(true);
		$sheet->getColumnDimension('AH')->setAutoSize(true);
		$sheet->getColumnDimension('AI')->setAutoSize(true);
		$sheet->getColumnDimension('AJ')->setAutoSize(true);
		$sheet->getColumnDimension('AK')->setAutoSize(true);
		$sheet->getColumnDimension('AL')->setAutoSize(true);
		$sheet->getColumnDimension('AM')->setAutoSize(true);
		$sheet->getColumnDimension('AN')->setAutoSize(true);

		if(!empty($list_karyawan)){
	
			foreach($list_karyawan as $list_karyawan){

			
				$sheet->setCellValue('A' . $rows, $list_karyawan->nik);
				$sheet->setCellValue('B' . $rows, $list_karyawan->nama);
				$sheet->setCellValue('C' . $rows, $list_karyawan->departemen);
				$date = $tgl_awal;
			    $rekap[$list_karyawan->p_karyawan_id]['total']['cuti'] =0;
			                        
										$rekap[$list_karyawan->p_karyawan_id]['total']['ipd'] = 0;
										$rekap[$list_karyawan->p_karyawan_id]['total']['ihk'] = 0;
										$rekap[$list_karyawan->p_karyawan_id]['total']['ihk'] = 0;
										$rekap[$list_karyawan->p_karyawan_id]['total']['ipg'] = 0;                    
										$rekap[$list_karyawan->p_karyawan_id]['total']['sakit'] = 0;                    
										$rekap[$list_karyawan->p_karyawan_id]['total']['alpha'] = 0;                    
										$rekap[$list_karyawan->p_karyawan_id]['total']['alphaList'] = '';                    
				for($i = 3; $i <= $help->hitunghari($tgl_awal,$tgl_akhir)+3; $i++){
					$content = "";
					$warna = 'FFFFFF';
					
					if(isset($rekap[$list_karyawan->p_karyawan_id][$date]['ci']['nama_ijin'])){
						if(!in_array($help->nama_hari($date),array('Minggu','Sabtu'))  ){
						if(in_array($rekap[$list_karyawan->p_karyawan_id][$date]['ci']['m_jenis_ijin_id'],array(4,7,12,13,14,15,16,17))){
											$rekap[$list_karyawan->p_karyawan_id]['total']['ihk'] += 1;
										}else if($rekap[$list_karyawan->p_karyawan_id][$date]['ci']['m_jenis_ijin_id']==1){
											$rekap[$list_karyawan->p_karyawan_id]['total']['ipg'] += 1;
										}else if($rekap[$list_karyawan->p_karyawan_id][$date]['ci']['m_jenis_ijin_id']==24){
											$rekap[$list_karyawan->p_karyawan_id]['total']['ipd'] += 1;
										}else if($rekap[$list_karyawan->p_karyawan_id][$date]['ci']['m_jenis_ijin_id']==20){
											$rekap[$list_karyawan->p_karyawan_id]['total']['sakit'] += 1;
										}
										
										if($rekap[$list_karyawan->p_karyawan_id][$date]['ci']['tipe']==3){
											$rekap[$list_karyawan->p_karyawan_id]['total']['cuti'] += 1;
											
											
										}
									
						else if($rekap[$list_karyawan->p_karyawan_id][$date]['ci']['tipe']==4)
						$warna = '0000FF';
											
						else if($rekap[$list_karyawan->p_karyawan_id][$date]['ci']['nama_ijin']=='IZIN DATANG TERLAMBAT')
						$warna = 'red';
						if($rekap[$list_karyawan->p_karyawan_id][$date]['ci']['nama_ijin'] != 'IZIN DATANG TERLAMBAT' and $rekap[$list_karyawan->p_karyawan_id][$date]['ci']['tipe']== 4)
											$content .= ' '.$rekap[$list_karyawan->p_karyawan_id][$date]['ci']['nama_ijin'].'								';	
											if($rekap[$list_karyawan->p_karyawan_id][$date]['ci']['nama_ijin']
												== 'IZIN PERJALANAN DINAS'){
											
											}
						}
					}
										
					if($content==""){
						$content .= '';
						if(!in_array($help->nama_hari($date),array('Minggu','Sabtu')) and !in_array($date,$hari_libur)  and !isset($hari_libur_shift[$date][$list_karyawan->p_karyawan_id])    ){
							$rekap[$list_karyawan->p_karyawan_id]['total']['alpha'] += 1;
							$rekap[$list_karyawan->p_karyawan_id]['total']['alphaList'] .= $date.' | ' ;
						}					
					
					}
					
					
					if($warna!='FFFFFF' and $warna != 'FFFF00')
					$font = 'FFFFFF';
					else
					$font = '000000';
						
					$sheet->setCellValue($help->toAlpha($i) . $rows, $content);
					$spreadsheet->getActiveSheet()->getStyle($help->toAlpha($i).$rows)->applyFromArray([
							'fill' => [
								'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
								'startColor' => [
									'rgb' => $warna,
								]
							],
							'font' => [
						        'color' => [
									'rgb' => $font,
								]
							]
						]);						
											
					
					$date = $help->tambah_tanggal($date,1);
				}
                        		
			
				
				$masuk = isset($rekap[$list_karyawan->p_karyawan_id]['total']['absen_masuk'])?$rekap[$list_karyawan->p_karyawan_id]['total']['absen_masuk']:0;
				$cuti = isset($rekap[$list_karyawan->p_karyawan_id]['total']['cuti'])?$rekap[$list_karyawan->p_karyawan_id]['total']['cuti']:0;
				$ipg = isset($rekap[$list_karyawan->p_karyawan_id]['total']['ipg'])?$rekap[$list_karyawan->p_karyawan_id]['total']['ipg']:0;
				$ipd = isset($rekap[$list_karyawan->p_karyawan_id]['total']['ipd'])?$rekap[$list_karyawan->p_karyawan_id]['total']['ipd']:0;
				$izin = isset($rekap[$list_karyawan->p_karyawan_id]['total']['ihk'])?$rekap[$list_karyawan->p_karyawan_id]['total']['ihk']:0;
				$alpha = isset($rekap[$list_karyawan->p_karyawan_id]['total']['alpha'])?$rekap[$list_karyawan->p_karyawan_id]['total']['alpha']:0;
				$alphaList = isset($rekap[$list_karyawan->p_karyawan_id]['total']['alphaList'])?$rekap[$list_karyawan->p_karyawan_id]['total']['alphaList']:0;
				$sakit = isset($rekap[$list_karyawan->p_karyawan_id]['total']['sakit'])?$rekap[$list_karyawan->p_karyawan_id]['total']['sakit']:0;
				
				$terlambat = isset($rekap[$list_karyawan->p_karyawan_id]['total']['terlambat'])?$rekap[$list_karyawan->p_karyawan_id]['total']['terlambat']:0;
				
		
				$sheet->setCellValue($help->toAlpha($i). $rows, $ipg);$i++;
				$sheet->setCellValue($help->toAlpha($i). $rows, $izin);$i++;
				$sheet->setCellValue($help->toAlpha($i). $rows, $ipd);$i++;
				$sheet->setCellValue($help->toAlpha($i).$rows, $ipg+$izin+$ipd);$i++;
				//echo substr($absen->a,0,5);die;
				$rows++;
			}
		}
		
		
		$type = 'xlsx';
		$fileName = "Perdin ".$tgl_awal." - ".$tgl_akhir.".".$type;
		if($type == 'xlsx'){
			$writer = new Xlsx($spreadsheet);
		} else if($type == 'xls'){
			$writer = new Xls($spreadsheet);
		}
		$writer->save("export/".$fileName);
		header("Content-Type: application/vnd.ms-excel");
		return redirect(url('/')."/export/".$fileName);
	}
    public function exportsCuti($param){
			
		$bulan 			= $param['bulan'];
		$tahun 			=  $param['tahun'];
		$tgl_awal 		= $param['tgl_awal'];
		$tgl_akhir 		=$param['tgl_akhir'];
		$periode_absen  = $param['periode_absen'];
		$periode  		= $param['periode'];
		$rekap 			= $param['rekap'];
		$list_karyawan 	= $param['list_karyawan'];
		$hari_libur = $param['hari_libur'];
		$hari_libur_shift = $param['hari_libur_shift'];
		$help = New Helper_function();
		$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();
		$sheet->setCellValue('A1', 'NIK');
		$sheet->setCellValue('B1', 'Nama');
		$sheet->setCellValue('C1', 'Departemen');
		$date = $tgl_awal;
		for($i = 3; $i <= $help->hitunghari($tgl_awal,$tgl_akhir)+3; $i++){
			$sheet->setCellValue($help->toAlpha($i).'1', $date);
			//echo ' <th>'.$date.'</th>';
			$date = $help->tambah_tanggal($date,1);
		}
		
		$sheet->setCellValue($help->toAlpha($i).'1', 'Total');	$i++;
		$rows = 3;

		$sheet->getColumnDimension('A')->setAutoSize(true);
		$sheet->getColumnDimension('B')->setAutoSize(true);
		$sheet->getColumnDimension('C')->setAutoSize(true);
		$sheet->getColumnDimension('D')->setAutoSize(true);
		$sheet->getColumnDimension('E')->setAutoSize(true);
		$sheet->getColumnDimension('F')->setAutoSize(true);
		$sheet->getColumnDimension('G')->setAutoSize(true);
		$sheet->getColumnDimension('H')->setAutoSize(true);
		$sheet->getColumnDimension('I')->setAutoSize(true);
		$sheet->getColumnDimension('J')->setAutoSize(true);
		$sheet->getColumnDimension('K')->setAutoSize(true);
		$sheet->getColumnDimension('L')->setAutoSize(true);
		$sheet->getColumnDimension('M')->setAutoSize(true);
		$sheet->getColumnDimension('N')->setAutoSize(true);
		$sheet->getColumnDimension('O')->setAutoSize(true);
		$sheet->getColumnDimension('P')->setAutoSize(true);
		$sheet->getColumnDimension('Q')->setAutoSize(true);
		$sheet->getColumnDimension('R')->setAutoSize(true);
		$sheet->getColumnDimension('S')->setAutoSize(true);
		$sheet->getColumnDimension('T')->setAutoSize(true);
		$sheet->getColumnDimension('U')->setAutoSize(true);
		$sheet->getColumnDimension('V')->setAutoSize(true);
		$sheet->getColumnDimension('W')->setAutoSize(true);
		$sheet->getColumnDimension('X')->setAutoSize(true);
		$sheet->getColumnDimension('Y')->setAutoSize(true);
		$sheet->getColumnDimension('Z')->setAutoSize(true);
		$sheet->getColumnDimension('AA')->setAutoSize(true);
		$sheet->getColumnDimension('AB')->setAutoSize(true);
		$sheet->getColumnDimension('AC')->setAutoSize(true);
		$sheet->getColumnDimension('AD')->setAutoSize(true);
		$sheet->getColumnDimension('AE')->setAutoSize(true);
		$sheet->getColumnDimension('AF')->setAutoSize(true);
		$sheet->getColumnDimension('AG')->setAutoSize(true);
		$sheet->getColumnDimension('AH')->setAutoSize(true);
		$sheet->getColumnDimension('AI')->setAutoSize(true);
		$sheet->getColumnDimension('AJ')->setAutoSize(true);
		$sheet->getColumnDimension('AK')->setAutoSize(true);
		$sheet->getColumnDimension('AL')->setAutoSize(true);
		$sheet->getColumnDimension('AM')->setAutoSize(true);
		$sheet->getColumnDimension('AN')->setAutoSize(true);

		if(!empty($list_karyawan)){
	
			foreach($list_karyawan as $list_karyawan){

			
				$sheet->setCellValue('A' . $rows, $list_karyawan->nik);
				$sheet->setCellValue('B' . $rows, $list_karyawan->nama);
				$sheet->setCellValue('C' . $rows, $list_karyawan->departemen);
				$date = $tgl_awal;
			    $rekap[$list_karyawan->p_karyawan_id]['total']['cuti'] =0;
			                        
										$rekap[$list_karyawan->p_karyawan_id]['total']['ipd'] = 0;
										$rekap[$list_karyawan->p_karyawan_id]['total']['ihk'] = 0;
										$rekap[$list_karyawan->p_karyawan_id]['total']['ihk'] = 0;
										$rekap[$list_karyawan->p_karyawan_id]['total']['ipg'] = 0;                    
										$rekap[$list_karyawan->p_karyawan_id]['total']['sakit'] = 0;                    
										$rekap[$list_karyawan->p_karyawan_id]['total']['alpha'] = 0;                    
										$rekap[$list_karyawan->p_karyawan_id]['total']['alphaList'] = '';                    
				for($i = 3; $i <= $help->hitunghari($tgl_awal,$tgl_akhir)+3; $i++){
					$content = "";
					$warna = 'FFFFFF';
					
					if(isset($rekap[$list_karyawan->p_karyawan_id][$date]['ci']['nama_ijin'])){
						if(!in_array($help->nama_hari($date),array('Minggu','Sabtu'))  ){
						if(in_array($rekap[$list_karyawan->p_karyawan_id][$date]['ci']['m_jenis_ijin_id'],array(4,7,12,13,14,15,16,17))){
											$rekap[$list_karyawan->p_karyawan_id]['total']['ihk'] += 1;
										}else if($rekap[$list_karyawan->p_karyawan_id][$date]['ci']['m_jenis_ijin_id']==1){
											$rekap[$list_karyawan->p_karyawan_id]['total']['ipg'] += 1;
										}else if($rekap[$list_karyawan->p_karyawan_id][$date]['ci']['m_jenis_ijin_id']==24){
											$rekap[$list_karyawan->p_karyawan_id]['total']['ipd'] += 1;
										}else if($rekap[$list_karyawan->p_karyawan_id][$date]['ci']['m_jenis_ijin_id']==20){
											$rekap[$list_karyawan->p_karyawan_id]['total']['sakit'] += 1;
										}
										
										if($rekap[$list_karyawan->p_karyawan_id][$date]['ci']['tipe']==3){
											$rekap[$list_karyawan->p_karyawan_id]['total']['cuti'] += 1;
											
											
										}
									if($rekap[$list_karyawan->p_karyawan_id][$date]['ci']['tipe']==3)
						$warna = '017701';
											
						else if($rekap[$list_karyawan->p_karyawan_id][$date]['ci']['nama_ijin']=='IZIN DATANG TERLAMBAT')
						$warna = 'red';
						if($rekap[$list_karyawan->p_karyawan_id][$date]['ci']['nama_ijin'] != 'IZIN DATANG TERLAMBAT' and $rekap[$list_karyawan->p_karyawan_id][$date]['ci']['tipe']== 3)
											$content .= ' '.$rekap[$list_karyawan->p_karyawan_id][$date]['ci']['nama_ijin'].'								';	
											if($rekap[$list_karyawan->p_karyawan_id][$date]['ci']['nama_ijin']
												== 'IZIN PERJALANAN DINAS'){
											
											}
						}
					}
										
					if($content==""){
						$content .= '';
						if(!in_array($help->nama_hari($date),array('Minggu','Sabtu')) and !in_array($date,$hari_libur)  and !isset($hari_libur_shift[$date][$list_karyawan->p_karyawan_id])     ){
							$rekap[$list_karyawan->p_karyawan_id]['total']['alpha'] += 1;
							$rekap[$list_karyawan->p_karyawan_id]['total']['alphaList'] .= $date.' | ' ;
						}					
					
					}
					
					
					if($warna!='FFFFFF' and $warna != 'FFFF00')
					$font = 'FFFFFF';
					else
					$font = '000000';
						
					$sheet->setCellValue($help->toAlpha($i) . $rows, $content);
					$spreadsheet->getActiveSheet()->getStyle($help->toAlpha($i).$rows)->applyFromArray([
							'fill' => [
								'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
								'startColor' => [
									'rgb' => $warna,
								]
							],
							'font' => [
						        'color' => [
									'rgb' => $font,
								]
							]
						]);						
											
					
					$date = $help->tambah_tanggal($date,1);
				}
                        		
			
				
				$masuk = isset($rekap[$list_karyawan->p_karyawan_id]['total']['absen_masuk'])?$rekap[$list_karyawan->p_karyawan_id]['total']['absen_masuk']:0;
				$cuti = isset($rekap[$list_karyawan->p_karyawan_id]['total']['cuti'])?$rekap[$list_karyawan->p_karyawan_id]['total']['cuti']:0;
				$ipg = isset($rekap[$list_karyawan->p_karyawan_id]['total']['ipg'])?$rekap[$list_karyawan->p_karyawan_id]['total']['ipg']:0;
				$ipd = isset($rekap[$list_karyawan->p_karyawan_id]['total']['ipd'])?$rekap[$list_karyawan->p_karyawan_id]['total']['ipd']:0;
				$izin = isset($rekap[$list_karyawan->p_karyawan_id]['total']['ihk'])?$rekap[$list_karyawan->p_karyawan_id]['total']['ihk']:0;
				$alpha = isset($rekap[$list_karyawan->p_karyawan_id]['total']['alpha'])?$rekap[$list_karyawan->p_karyawan_id]['total']['alpha']:0;
				$alphaList = isset($rekap[$list_karyawan->p_karyawan_id]['total']['alphaList'])?$rekap[$list_karyawan->p_karyawan_id]['total']['alphaList']:0;
				$sakit = isset($rekap[$list_karyawan->p_karyawan_id]['total']['sakit'])?$rekap[$list_karyawan->p_karyawan_id]['total']['sakit']:0;
				
				$terlambat = isset($rekap[$list_karyawan->p_karyawan_id]['total']['terlambat'])?$rekap[$list_karyawan->p_karyawan_id]['total']['terlambat']:0;
				
		
				$sheet->setCellValue($help->toAlpha($i). $rows, $cuti);$i++;
				//echo substr($absen->a,0,5);die;
				$rows++;
			}
		}
		
		
		$type = 'xlsx';
		$fileName = "Cuti ".$tgl_awal." - ".$tgl_akhir.".".$type;
		if($type == 'xlsx'){
			$writer = new Xlsx($spreadsheet);
		} else if($type == 'xls'){
			$writer = new Xls($spreadsheet);
		}
		$writer->save("export/".$fileName);
		header("Content-Type: application/vnd.ms-excel");
		return redirect(url('/')."/export/".$fileName);
	}
    public function exportsLembur($param){
			
		$bulan = $param['bulan'];
		$tahun =  $param['tahun'];
		$tgl_awal = $param['tgl_awal'];
		$tgl_akhir =$param['tgl_akhir'];
		$periode_absen   = $param['periode_absen'];
		$periode  = $param['periode'];
		$rekap = $param['rekap'];
		$list_karyawan = $param['list_karyawan'];
	    $hari_libur =   $param['hari_libur']  ;
	    $hari_libur_shift = $param['hari_libur_shift']; 
		$help = New Helper_function();
		$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();
		$sheet->setCellValue('A1', 'Nama');
		$sheet->setCellValue('B1', 'Entitas');
		$sheet->setCellValue('C1', 'Jabatan');
		$date = $tgl_awal;
		for($i = 3; $i <= $help->hitunghari($tgl_awal,$tgl_akhir)+3; $i++){
			$sheet->setCellValue($help->toAlpha($i).'1', $help->nama_hari($date));
			//echo ' <th>'.$date.'</th>';
			$date = $help->tambah_tanggal($date,1);
		}
		
		$sheet->setCellValue($help->toAlpha($i).'1', 'Hari Kerja');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'1', '');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'1', '');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'1', '');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'1', 'Hari Libur');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'1', '');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'1', '');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'1', '');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'1', '');	$i++; 
		$sheet->setCellValue($help->toAlpha($i).'1', '');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'1', 'TOTAL');$i++;	
		$sheet->setCellValue($help->toAlpha($i).'1', 'TOTAL PENGAJUAN');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'1', 'TOTAL APPROVE');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'1', 'TOTAL TOLAK');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'1', 'TOTAL PENDING');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'1', 'TANGGAL PENDING');$i++;	
		
		$sheet->setCellValue('A2', '');
		$sheet->setCellValue('B2', '');
		$sheet->setCellValue('C2', '');
		$date = $tgl_awal;
		for($i = 3; $i <= $help->hitunghari($tgl_awal,$tgl_akhir)+3; $i++){
			$sheet->setCellValue($help->toAlpha($i).'2', $help->tgl_indo_short($date));
			//echo ' <th>'.$date.'</th>';
			$date = $help->tambah_tanggal($date,1);
		}
		
		$sheet->setCellValue($help->toAlpha($i).'2', '1 jam');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'2', '>=2 jam');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'2', 'COUNT');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'2', 'SUM');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'2', '8 jam');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'2', '9 jam');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'2', '>=10 jam');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'2', 'COUNT');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'2', 'SUM');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'2', '');	
		$rows = 3;

		$sheet->getColumnDimension('A')->setAutoSize(true);
		$sheet->getColumnDimension('B')->setAutoSize(true);
		$sheet->getColumnDimension('C')->setAutoSize(true);
		$sheet->getColumnDimension('D')->setAutoSize(true);
		$sheet->getColumnDimension('E')->setAutoSize(true);
		$sheet->getColumnDimension('F')->setAutoSize(true);
		$sheet->getColumnDimension('G')->setAutoSize(true);
		$sheet->getColumnDimension('H')->setAutoSize(true);
		$sheet->getColumnDimension('I')->setAutoSize(true);
		$sheet->getColumnDimension('J')->setAutoSize(true);
		$sheet->getColumnDimension('K')->setAutoSize(true);
		$sheet->getColumnDimension('L')->setAutoSize(true);
		$sheet->getColumnDimension('M')->setAutoSize(true);
		$sheet->getColumnDimension('N')->setAutoSize(true);
		$sheet->getColumnDimension('O')->setAutoSize(true);
		$sheet->getColumnDimension('P')->setAutoSize(true);
		$sheet->getColumnDimension('Q')->setAutoSize(true);
		$sheet->getColumnDimension('R')->setAutoSize(true);
		$sheet->getColumnDimension('S')->setAutoSize(true);
		$sheet->getColumnDimension('T')->setAutoSize(true);
		$sheet->getColumnDimension('U')->setAutoSize(true);
		$sheet->getColumnDimension('V')->setAutoSize(true);
		$sheet->getColumnDimension('W')->setAutoSize(true);
		$sheet->getColumnDimension('X')->setAutoSize(true);
		$sheet->getColumnDimension('Y')->setAutoSize(true);
		$sheet->getColumnDimension('Z')->setAutoSize(true);
		$sheet->getColumnDimension('AA')->setAutoSize(true);
		$sheet->getColumnDimension('AB')->setAutoSize(true);
		$sheet->getColumnDimension('AC')->setAutoSize(true);
		$sheet->getColumnDimension('AD')->setAutoSize(true);
		$sheet->getColumnDimension('AE')->setAutoSize(true);
		$sheet->getColumnDimension('AF')->setAutoSize(true);
		$sheet->getColumnDimension('AG')->setAutoSize(true);
		$sheet->getColumnDimension('AH')->setAutoSize(true);
		$sheet->getColumnDimension('AI')->setAutoSize(true);
		$sheet->getColumnDimension('AJ')->setAutoSize(true);
		$sheet->getColumnDimension('AK')->setAutoSize(true);
		$sheet->getColumnDimension('AL')->setAutoSize(true);
		$sheet->getColumnDimension('AM')->setAutoSize(true);
		$sheet->getColumnDimension('AN')->setAutoSize(true);

		if(!empty($list_karyawan)){
	
			foreach($list_karyawan as $list_karyawan){
				$total_all=0;
				$total['<8 jam'] =0;
				$total['8 jam'] =0;
			                $total['9 jam'] =0;
			                $total['>=10 jam'] =0;
                            $total['COUNT Libur'] =0;
							$total['SUM Libur'] =0;
							$total['1jam'] =0;
							$total['>=2jam'] =0;
							$total['COUNT Kerja'] =0;
							$total['SUM Kerja'] =0;
			
				$sheet->setCellValue('A' . $rows, $list_karyawan->nama);
				$sheet->setCellValue('B' . $rows, $list_karyawan->nmlokasi);
				$sheet->setCellValue('C' . $rows, $list_karyawan->nmjabatan);
				$date = $tgl_awal;
			                        
				for($i = 3; $i <= $help->hitunghari($tgl_awal,$tgl_akhir)+3; $i++){
					$content = "";
					$warna = '';
					$font = '';
									if(in_array($help->nama_hari($date),array('Sabtu','Minggu')) or 
			                        				in_array(($date),$hari_libur)
			                        				or isset($hari_libur_shift[$date][$list_karyawan->p_karyawan_id])  
			                        				){
											$warna	='FF0101';	
											$font = 'FFFFFF';	
									}
											
										
										if(isset($rekap[$list_karyawan->p_karyawan_id][$date]['lama'])){
			                        			$total_all += $rekap[$list_karyawan->p_karyawan_id][$date]['lama'];
			                        			if(in_array($help->nama_hari($date),array('Sabtu','Minggu')) or 
			                        				in_array(($date),$hari_libur)
			                        				or isset($hari_libur_shift[$date][$list_karyawan->p_karyawan_id])  
			                        				){
			                        				
			                        				$lama = $rekap[$list_karyawan->p_karyawan_id][$date]['lama'];
			                        				if($lama>8){
														$total['8 jam'] +=8; $lama-=8;
													}else if($lama<=8){
														$total['8 jam'] +=$lama; $lama-=$lama;
													}
													if($lama){
														
													 $total['9 jam'] +=1;$lama-=1;
													}
													if($lama)
													 $total['>=10 jam'] +=$lama;
														
													
													$total['COUNT Libur'] +=1;
													$total['SUM Libur'] +=$rekap[$list_karyawan->p_karyawan_id][$date]['lama'];
												}
					                        	else{
					                        		$lama = $rekap[$list_karyawan->p_karyawan_id][$date]['lama'];
													$total['1jam'] +=1;$lama-=1;
			                        				if($lama)
													$total['>=2jam'] +=$lama;
													
													$total['COUNT Kerja'] +=1;
													$total['SUM Kerja'] +=$rekap[$list_karyawan->p_karyawan_id][$date]['lama'];
												}
												$content .= ''.$rekap[$list_karyawan->p_karyawan_id][$date]['lama'].'';
											
										}else{
											
											$content .= '0';
										}
										
						
					$sheet->setCellValue($help->toAlpha($i) . $rows, $content);
					if($warna){
						
					$spreadsheet->getActiveSheet()->getStyle($help->toAlpha($i).$rows)->applyFromArray([
							
							'font' => [
						        'color' => [
									'rgb' => $warna,
								]
							]
						]);						
											
					}
					
					$date = $help->tambah_tanggal($date,1);
				}
                        		
				$total_pengajuan  = isset($rekap[$list_karyawan->p_karyawan_id]['lembur'])? $rekap[$list_karyawan->p_karyawan_id]['lembur']['total_pengajuan']:0; 
				$total_approve = isset($rekap[$list_karyawan->p_karyawan_id]['lembur'])? $rekap[$list_karyawan->p_karyawan_id]['lembur']['total_approve']:0; 
				$total_tolak = isset($rekap[$list_karyawan->p_karyawan_id]['lembur'])? $rekap[$list_karyawan->p_karyawan_id]['lembur']['total_tolak']:0; 
                $total_pending = isset($rekap[$list_karyawan->p_karyawan_id]['lembur'])? $rekap[$list_karyawan->p_karyawan_id]['lembur']['total_pending']:0;
                $tgl_pending =                isset($rekap[$list_karyawan->p_karyawan_id]['lembur'])? $rekap[$list_karyawan->p_karyawan_id]['lembur']['tgl_pending']:'-';
                               
				$sheet->setCellValue($help->toAlpha($i). $rows, $total['1jam']);$i++;
				$sheet->setCellValue($help->toAlpha($i). $rows, $total['>=2jam']);$i++;
				$sheet->setCellValue($help->toAlpha($i). $rows, $total['COUNT Kerja']);$i++;
				$sheet->setCellValue($help->toAlpha($i). $rows, $total['SUM Kerja'].' ');$i++;
				$sheet->setCellValue($help->toAlpha($i). $rows, $total['8 jam']);$i++;
				$sheet->setCellValue($help->toAlpha($i). $rows, $total['9 jam']);$i++;
				$sheet->setCellValue($help->toAlpha($i).$rows, $total['>=10 jam']);$i++;
				$sheet->setCellValue($help->toAlpha($i).$rows, $total['COUNT Libur']);$i++;
				$sheet->setCellValue($help->toAlpha($i). $rows, $total['SUM Libur'].' ');$i++;
				$sheet->setCellValue($help->toAlpha($i). $rows, $total_all.' ');$i++;
				$sheet->setCellValue($help->toAlpha($i). $rows, $total_pengajuan.' ');$i++;
				$sheet->setCellValue($help->toAlpha($i). $rows, $total_approve.' ');$i++; 
				$sheet->setCellValue($help->toAlpha($i). $rows, $total_tolak.' ');$i++; 
				$sheet->setCellValue($help->toAlpha($i). $rows, $total_pending.' ');$i++;
				$sheet->setCellValue($help->toAlpha($i). $rows, $tgl_pending.'');$i++;
				//echo substr($absen->a,0,5);die;
				$rows++;
			}
		}
		
		
		$type = 'xlsx';
		$fileName = "Rekap Lembur ".$tgl_awal." - ".$tgl_akhir.".".$type;
		if($type == 'xlsx'){
			$writer = new Xlsx($spreadsheet);
		} else if($type == 'xls'){
			$writer = new Xls($spreadsheet);
		}
		$writer->save("export/".$fileName);
		header("Content-Type: application/vnd.ms-excel");
		return redirect(url('/')."/export/".$fileName);
	}
    
}

	
	

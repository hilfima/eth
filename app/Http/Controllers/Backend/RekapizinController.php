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

class RekapizinController extends Controller{
  
    
	public function __construct()
    {
        $this->middleware('auth');
    }
	public function view(Request $request){
		$tgl_awal=date('Y-m-d');
		$tgl_akhir=date('Y-m-d');
		$tahun=date('Y');
		$bulan=date('mm');
		$periode_gajian=$request->get('periode_gajian');
		$periode_absen=$request->get('periode');
		//echo $periode_absen;die;
		$iduser=Auth::user()->id;
		$sqluser="SELECT p_recruitment.foto FROM users
		left join p_karyawan on p_karyawan.user_id=users.id
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

			$periode_gajian=$periode[0]->type;
			$tgl_awal=date('Y-m-d',strtotime($periodetgl[0]->tgl_awal));
			$tgl_akhir=date('Y-m-d',strtotime($periodetgl[0]->tgl_akhir));
		}else{
			$tgl_awal = $help->tambah_tanggal(date('Y-m-d'),-7);
			$tgl_akhir = date('Y-m-d');
		}
		$hariLibur =array();
		$sqllibur = "Select * from m_hari_libur where tanggal>='$tgl_awal' and tanggal<='$tgl_akhir' ";
		$libur=DB::connection()->select($sqllibur);
		foreach($libur as $libur){
			$hariLibur[] = $libur->tanggal;
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
		$rekap = array();
	
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
		if(($request->get('periode_gajian'))){
			
			$periode_absen=$request->get('periode_gajian');
			$sqlperiode="SELECT * FROM m_periode_absen where periode_absen_id=$periode_absen";
			$periodetgl=DB::connection()->select($sqlperiode);
			$type = $periodetgl[0]->type;
			$where = " and d.periode_gajian = ".$type;
			$appendwhere = "and c.active=1";
			
			$periode_gajian=$periode[0]->type;
			$tgl_awal=date('Y-m-d',strtotime($periodetgl[0]->tgl_awal));
			$tgl_akhir=date('Y-m-d',strtotime($periodetgl[0]->tgl_akhir));
		}else{
			$tgl_awal = $help->tambah_tanggal(date('Y-m-d'),-7);
			$tgl_akhir = date('Y-m-d');
			$where = '';
			$appendwhere = "";
		}
		$sql = "SELECT c.p_karyawan_id,c.nama,c.nik,m_lokasi.kode as nmlokasi,m_jabatan.nama as nmjabatan,m_departemen.nama as departemen 
		FROM p_karyawan c 
		LEFT JOIN p_karyawan_pekerjaan d on d.p_karyawan_id=c.p_karyawan_id
		LEFT JOIN m_departemen on m_departemen.m_departemen_id=d.m_departemen_id
		LEFT JOIN m_jabatan on m_jabatan.m_jabatan_id=d.m_jabatan_id
		LEFT JOIN m_lokasi on m_lokasi.m_lokasi_id=d.m_lokasi_id
		WHERE 1=1 $where $appendwhere order by c.nama";
        //c.p_karyawan_id IN (" . implode(',', $karyawan) . ")
		//echo $sql;die;
		$list_karyawan = DB::connection()->select($sql);
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
	        $param['hariLibur'] = $hariLibur; 
	        $param['list_karyawan'] = $list_karyawan; 
			return RekapLemburController::exports($param);
    	    //return Excel::download(new rekaplembur_xls($param), $nama_file . '.xlsx');
		}else{
			
			return view('backend.rekap_ijin.rekap_ijin',compact('tgl_awal','tgl_akhir','bulan','tahun','periode','periode_absen','periode_gajian','help','tgl_awal','tgl_akhir','list_karyawan','rekap','user','hariLibur'));
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
	    $hariLibur =   $param['hariLibur']  ; 
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
		$sheet->setCellValue($help->toAlpha($i).'1', 'TOTAL');	
		
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
			                        				in_array(($date),$hariLibur)){
											$warna	='FF0101';	
											$font = 'FFFFFF';	
									}
											
										
										if(isset($rekap[$list_karyawan->p_karyawan_id][$date]['lama'])){
			                        			$total_all += $rekap[$list_karyawan->p_karyawan_id][$date]['lama'];
			                        			if(in_array($help->nama_hari($date),array('Sabtu','Minggu')) or 
			                        				in_array(($date),$hariLibur)){
			                        				if($rekap[$list_karyawan->p_karyawan_id][$date]['lama']==9)
													$total['8 jam'] +=1;
			                        				if($rekap[$list_karyawan->p_karyawan_id][$date]['lama']==8)
													$total['9 jam'] +=1;
			                        				if($rekap[$list_karyawan->p_karyawan_id][$date]['lama']>=10)
													$total['>=10 jam'] +=1;
													
													
													$total['COUNT Libur'] +=1;
													$total['SUM Libur'] +=$rekap[$list_karyawan->p_karyawan_id][$date]['lama'];
												}
					                        	else{
			                        				if($rekap[$list_karyawan->p_karyawan_id][$date]['lama']==1)
													$total['1jam'] +=1;
			                        				if($rekap[$list_karyawan->p_karyawan_id][$date]['lama']>=2)
													$total['>=2jam'] +=1;
													
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
                        		
			
				$sheet->setCellValue($help->toAlpha($i). $rows, $total['1jam']);$i++;
				$sheet->setCellValue($help->toAlpha($i). $rows, $total['>=2jam']);$i++;
				$sheet->setCellValue($help->toAlpha($i). $rows, $total['COUNT Kerja']);$i++;
				$sheet->setCellValue($help->toAlpha($i). $rows, $total['SUM Kerja'].' ');$i++;
				$sheet->setCellValue($help->toAlpha($i). $rows, $total['8 jam']);$i++;
				$sheet->setCellValue($help->toAlpha($i). $rows, $total['9 jam']);$i++;
				$sheet->setCellValue($help->toAlpha($i).$rows, $total['>=10 jam']);$i++;
				$sheet->setCellValue($help->toAlpha($i).$rows, $total['COUNT Libur']);$i++;
				$sheet->setCellValue($help->toAlpha($i). $rows, $total['SUM Libur'].' ');$i++;
				$sheet->setCellValue($help->toAlpha($i). $rows, $total_all.' ');
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

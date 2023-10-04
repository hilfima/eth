<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Auth;
use App\Helper_function;
use Maatwebsite\Excel\Facades\Excel;
use App\Eloquent\AbsenLog;
use App\Eloquent\Absen;
use PhpOffice\PhpSpreadsheet\Reader\Xml\Style\Fill;

use DateTime;;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use \PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PHPExcel;
use PHPExcel_IOFactory;

class GajiLaporanController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function laporan(Request $request,$type)
    {
       
		$sqlperiode="SELECT m_periode_absen.*,
		
		case when a.periode_gajian=0 then 'Pekanan' else 'Bulanan' end as tipe,
		a.tahun as tahun_gener,a.*,a.bulan as bulan_gener,b.tgl_awal as tgl_awal_lembur,b.tgl_akhir as tgl_akhir_lembur
		FROM prl_generate a 
		join m_periode_absen on m_periode_absen.periode_absen_id = a.periode_absen_id
		join m_periode_absen b on b.periode_absen_id = a.periode_lembur_id
		where a.active = 1
		ORDER BY a.create_date desc, prl_generate_id desc";
		$periode=DB::connection()->select($sqlperiode);
		 $iduser = Auth::user()->id;
        $sqluser = "SELECT p_recruitment.foto,role,p_karyawan_pekerjaan,p_karyawan_pekerjaan.m_lokasi_id,user_entitas_access,m_role_id FROM users
					left join m_role on m_role.m_role_id=users.role
					left join p_karyawan on p_karyawan.user_id=users.id
					left join p_karyawan_pekerjaan on p_karyawan_pekerjaan.p_karyawan_id=p_karyawan.p_karyawan_id
					left join p_recruitment on p_recruitment.p_recruitment_id=p_karyawan.p_recruitment_id
					where users.id=$iduser";
        $user = DB::connection()->select($sqluser);
        if ($user[0]->user_entitas_access) {
                $id_lokasi = $user[0]->user_entitas_access;
                $whereLokasikaryawan= "and m_lokasi.m_lokasi_id  = $id_lokasi";
            } else {
                $whereLokasikaryawan = "";
            }
		$lokasi=DB::connection()->select("select * from m_lokasi where active=1 and sub_entitas=0 $whereLokasikaryawan");
		
		$id_prl = $request->get('prl_generate');
		$entitas = $request->get('entitas');
		$help = new Helper_function();
		
		$array =''; 
                    $jenis =1; 
                     if($type=='koperasi_asa') 
                     	$array = 'Potongan Koperasi Asa';
                     else if($type=='bpjs_kes') 
                     	$array = 'Iuran BPJS Kesehatan';
                     else if($type=='kost') 
                     	$array = 'Sewa Kost';
                     else if($type=='koperasi_kkb') 
                     	$array = 'Potongan KKB';
                     else if($type=='pajak') 
                     	$array = 'Pajak';
                     else if($type=='bpjs_ket') 
                     	$array = 'Iuran BPJS Ketenagakerjaan';
                     else if($type=='infaq') 
                     	$array = 'Infaq';
                     else if($type=='zakat') 
                     	$array = 'Zakat';
                     else if($type=='bpjs') {
                     	$jenis =2; 
                     	$array = array('Iuran BPJS Kesehatan','Iuran BPJS Ketenaga Kerjaan');
                     }else if($type=='zakat_infaq') {
                     	$jenis =2; 
                     	$array= array('Zakat','Infaq');
                     }
		
		if($request->get('prl_generate')){
			$wherelokasi='';
			if($entitas){
				$wherelokasi = 'and m_lokasi.m_lokasi_id='.$entitas;
			}
			
       
            $wherepajak='';
            if($request->get('pajak')){
            	$wherepajak = "and d.pajak_onoff='".$request->get('pajak')."'";
            }
			$sql = "SELECT c.p_karyawan_id,c.nama as nama_lengkap,c.nik,m_lokasi.kode as nmlokasi,m_departemen.nama as departemen , f.m_pangkat_id,i.nama as nmpangkat ,m_jabatan.nama as nmjabatan,g.tgl_awal,AGE(CURRENT_DATE, c.tgl_bergabung)::VARCHAR AS umur,f.job as jobweight , j.nama_grade as grade,d.norek,d.bank	,d.m_lokasi_id, d.pajak_onoff 
			FROM p_karyawan c 
			LEFT JOIN p_karyawan_pekerjaan d on d.p_karyawan_id=c.p_karyawan_id  
			LEFT JOIN p_karyawan_kontrak g on g.p_karyawan_id=c.p_karyawan_id   and g.active=1
			LEFT JOIN m_departemen on m_departemen.m_departemen_id=d.m_departemen_id
			LEFT JOIN m_lokasi on m_lokasi.m_lokasi_id=d.m_lokasi_id
			LEFT JOIN m_jabatan on m_jabatan.m_jabatan_id=d.m_jabatan_id 
			LEFT JOIN m_jabatan f on d.m_jabatan_id=f.m_jabatan_id 
			LEFT JOIN m_pangkat i on f.m_pangkat_id=i.m_pangkat_id 
			LEFT JOIN m_karyawan_grade j on f.job>=j.job_min and f.job<= j.job_max
			WHERE 
			
			 c.p_karyawan_id in (select p_karyawan_id from prl_gaji_detail join prl_gaji on prl_gaji_detail.prl_gaji_id = prl_gaji.prl_gaji_id where prl_generate_id = $id_prl and prl_gaji_detail.active=1)
			--AND d.m_departemen_id != 17
			  and f.m_pangkat_id != 6
				 $wherelokasi
				 $whereLokasikaryawan
				 $wherepajak
			order by c.nama,m_departemen.nama";;
			//echo $sql;
			$list_karyawan = DB::connection()->select($sql);
			$sql="select *,
			case 
				when type=1 then (select nama_gaji from m_gaji_absen where b.gaji_absen_id = m_gaji_absen.m_gaji_absen_id )
				when type=2 then (select nama from prl_tunjangan join m_tunjangan on prl_tunjangan.m_tunjangan_id = m_tunjangan.m_tunjangan_id where b.prl_tunjangan_id = prl_tunjangan.prl_tunjangan_id )
				when type=3 then (select nama from m_tunjangan where b.m_tunjangan_id = m_tunjangan.m_tunjangan_id)
				when type=4 then (select nama from prl_potongan join m_potongan on prl_potongan.m_potongan_id = m_potongan.m_potongan_id where b.prl_potongan_id = prl_potongan.prl_potongan_id )
				when type=5 then (select nama from m_potongan where b.m_potongan_id = m_potongan.m_potongan_id)
			end as nama 
			from prl_gaji a 
			join prl_gaji_detail b on b.prl_gaji_id = a.prl_gaji_id 
			where prl_generate_id = $id_prl
			
			 and b.active=1
			order by prl_gaji_detail_id 
			";
			$row = DB::connection()->select($sql);
			$data= array();
			foreach($row as $row){
				$data[$row->p_karyawan_id][$row->nama] = round($row->nominal);
			
			}		
       
		}else{
			$data = array();
			$list_karyawan = array();
		}
		if($request->get('Cari')=='Excel'){
			$param['data'] = $data;
			$param['type'] = $type;
			$param['list_karyawan'] = $list_karyawan;
			$param['lokasi'] = $lokasi;
			$param['jenis'] = $jenis;
			$param['array'] = $array;
			
				return GajiLaporanController::exports($param);
		}else if($request->get('Cari')=='Cari'){
			
        	return view('backend.gaji.laporan.rekap',compact('periode','request','id_prl','type','list_karyawan','data','help','lokasi','array','jenis','user'));
		}else 
        return view('backend.gaji.laporan.rekap',compact('periode','request','id_prl','type','list_karyawan','data','help','lokasi','array','jenis','user'));
    }

    public function exports($param)
    {
    	$help = new Helper_function();
    	$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();
		$data=$param['data']  ;
		$list_karyawan=$param['list_karyawan'] ;
		$type=$param['type'] ;
		$jenis=$param['jenis'] ;
		$array=$param['array'] ;
		$i=0;						
									
				
		
		$sheet->setCellValue($help->toAlpha($i).'1', 'No');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'1', 'Nama Karyawan');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'1', 'Entitas');	$i++;
		//$sheet->setCellValue($help->toAlpha($i).'1', 'Pajak');	$i++;
		if($type=='bpjs'){
		$sheet->setCellValue($help->toAlpha($i).'1', 'Iuran BPJS Kesehatan');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'1', 'Iuran BPJS Ketenagakerjaan');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'1', 'Jumlah');	$i++;
        }else if($type=='zakat_infaq'){
		$sheet->setCellValue($help->toAlpha($i).'1', 'Zakat');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'1', 'Infaq');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'1', 'Jumlah');	$i++;
        }else {
		$sheet->setCellValue($help->toAlpha($i).'1', 'Nominal');	$i++;
        }
		
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
		$no = 0; 
                     $total = 0; 
                     $nominal = 0 ;
			foreach($list_karyawan as $list_karyawan){
						
                     //if($type=='bpjs'){echo $type;die;}
					$i=0;
					$content=array();
                   		
                     		$nominal = 0 ;
                          	//$content='';
                            $total_karyawan = 0; 
                            if($jenis==2){ 
                            	for($X=0;$X<count($array);$X++){
                            		$nominal =isset($data[$list_karyawan->p_karyawan_id][$array[$X]])?$data[$list_karyawan->p_karyawan_id][$array[$X]]:0;
									$content[] = ($nominal); 
									$total_karyawan+=$nominal;
                            	}
									$content[] = ($total_karyawan); 
								//$sheet->setCellValue($help->toAlpha($i). $rows, $total_karyawan);$i++;
							}else{
								$nominal = isset($data[$list_karyawan->p_karyawan_id][$array])?$data[$list_karyawan->p_karyawan_id][$array]:0;
								$total_karyawan+=$nominal;
								$content[] = ($nominal); 
								//$content .='<td>'.$help->rupiah($total_karyawan).'</td>';
							}
							if($total_karyawan){
                            	$no++;
								$total += $total_karyawan;
								$sheet->setCellValue($help->toAlpha($i). $rows, $no);$i++;
			                	$sheet->setCellValue($help->toAlpha($i). $rows, $list_karyawan->nama_lengkap);$i++;
			                	$sheet->setCellValue($help->toAlpha($i). $rows, $list_karyawan->nmlokasi);$i++;
			                	//$sheet->setCellValue($help->toAlpha($i). $rows, $list_karyawan->pajak_onoff);$i++;
								for($X=0;$X<count($content);$X++){
										//$sheet->setCellValue($help->toAlpha($i). $rows, $content[$X]);$i++;
										$sheet->getStyle($help->toAlpha($i). $rows)->getNumberFormat()->setFormatCode('Rp ###,###,##0');
									$sheet->setCellValueExplicit($help->toAlpha($i). $rows, $content[$X], DataType::TYPE_NUMERIC);$i++;
									if(isset($jumlahcontent[$X])) $jumlahcontent[$X] += $content[$X];
									else $jumlahcontent[$X] = $content[$X];;
								}
								$rows++;
							} 
							
                           	
                	
                	
                	
                               
				//echo substr($absen->a,0,5);die;
				
				}
				$sheet->setCellValue('A' . $rows, 'Jumlah');
            				$sheet->mergeCells('A' . $rows.':C' . $rows.'');
            				$style = array(
		            'alignment' => array(
		                'horizontal' => Alignment::HORIZONTAL_CENTER,
		            )
		        );
				$sheet->getStyle('A' . $rows.':C' . $rows)->applyFromArray($style);
            				$i=3;
								for($X=0;$X<count($content);$X++){
									$sheet->getStyle($help->toAlpha($i). $rows)->getNumberFormat()->setFormatCode('Rp ###,###,##0');
									$sheet->setCellValueExplicit($help->toAlpha($i). $rows, $jumlahcontent[$X], DataType::TYPE_NUMERIC);$i++;
								}
			}
		
		
		
		$fileName = "Rekap Gaji ".ucwords(str_replace('_',' ',$type))."  .xlsx";
		$type = 'xlsx';
		if($type == 'xlsx'){
			$writer = new Xlsx($spreadsheet);
		} else if($type == 'xls'){
			$writer = new Xls($spreadsheet);
		}else if($type == 'csv'){
			$writer = new Csv($spreadsheet,';');
		}
		//$writer->setDelimiter(';');
		$writer->save("export/".$fileName);
		header("Content-Type: application/vnd.ms-excel");
		return redirect(url('/')."/export/".$fileName);
    }
}

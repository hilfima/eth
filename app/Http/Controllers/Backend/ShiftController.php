<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Auth;
use App\Helper_function;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use PhpOffice\PhpSpreadsheet\Writer\Csv;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PHPExcel;
use PHPExcel_IOFactory;

class ShiftController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }public function shift()
    {
       $sqlshift="SELECT absen.jam_masuk,absen.jam_keluar,absen.tgl_awal,absen.tgl_akhir,absen.keterangan,absen.absen_id ,m_lokasi.nama
                       FROM absen
                       left join m_lokasi on m_lokasi.m_lokasi_id = absen.m_lokasi_id
                       where absen.active = 1  and shifting = 1
                        ORDER BY tgl_awal desc";
        $shift=DB::connection()->select($sqlshift);

        $iduser=Auth::user()->id;
        
        

        //return view('backend.shift.view_jamkerja',compact('shift'));
       
        return view('backend.shift.shift',compact('shift'));
    }

    public function shift_karyawan(Request $request)
    { 
    	
    	$help = new Helper_function();
    	
    	$entitas = DB::connection()->select('select * from m_lokasi where active=1 and sub_entitas=0 order by nama');
    	$limit = '';
    		$tgl_awal = $request->tgl_awal;
    	$tgl_akhir = $request->tgl_akhir;
    	$fentitas = $request->entitas; 
    	if(!$request->entitas and !$tgl_awal and !$tgl_akhir){
    	    $limit = 'limit 0';
    	    
    	}
    	$tgl_awal = $request->tgl_awal;
    	$tgl_akhir = $request->tgl_akhir;
    	$fentitas = $request->entitas;
    	$where ="";
    	if($tgl_awal)
    	    $where .=" and tanggal>='$tgl_awal'";
    	if($tgl_akhir)
    	    $where .=" and tanggal<='$tgl_akhir'";
    	if($fentitas)
    	    $where .=" and p_karyawan_pekerjaan.m_lokasi_id='$fentitas'";
    	$sqlshift="
        select * from absen_shift
        left join absen on absen_shift.absen_id = absen.absen_id
        left join p_karyawan on absen_shift.p_karyawan_id = p_karyawan.p_karyawan_id
        left join p_karyawan_pekerjaan on p_karyawan_pekerjaan.p_karyawan_id = p_karyawan.p_karyawan_id
        where p_karyawan.active = 1 $where and absen.active=1 and absen_shift.active=1
         order by m_departemen_id desc, m_jabatan_id,nama,tanggal desc
        $limit
        ";
                        
        $shift=DB::connection()->select($sqlshift);
    	
    	return view('backend.shift.shift_karyawan',compact('shift','help','entitas','request','tgl_awal','tgl_akhir'));
    }

    public function tambah_shift()
    {
       $sqllokasi="select * from m_lokasi where active=1";
        $lokasi=DB::connection()->select($sqllokasi);
                $id = '';
                $type = 'simpan_shift';
                $data['nama']='';
                $data['keterangan']='';
                $data['keterangan']='';
		$data['tgl_awal']='';
		$data['tgl_akhir']='';
		$data['jam_awal']='';
		$data['jam_akhir']='';
		$data['m_lokasi_id']='';
		$data['karyawan']=array();
        return view('backend.shift.tambah_shift',compact('id','data','type','lokasi'));
    } 
    public function tambah_shift_excel()
    {
    //	 $i=4;
	//echo $i!=1 and $i!=2 and $i!=3 ;die;
       $sqllokasi="select * from m_lokasi where active=1";
        $lokasi=DB::connection()->select($sqllokasi);
                $id = '';
                $type = 'simpan_jamshift';
                $data['nama']='';
                $data['keterangan']='';
		$data['tgl_awal']='';
		$data['tgl_akhir']='';
		$data['jam_awal']='';
		$data['jam_akhir']='';
		$data['m_lokasi_id']='';
        $sql = "select * from m_lokasi where active=1 and sub_entitas=0";
		$entitas=DB::connection()->select($sql);
        return view('backend.shift.tambah_shift_excel',compact('id','data','type','lokasi','entitas'));
    }

    public function simpan_shift(Request $request)
    {
    	
	  DB::beginTransaction();
        try{
            $idUser=Auth::user()->id;
           	 DB::connection()->table("absen")
                ->insert([
                   
                    "tgl_awal"=>($request->get("tgl_awal")),
                    "tgl_akhir"=>($request->get("tgl_akhir")),
                    "m_lokasi_id"=>($request->get("entitas")),
                    "jam_masuk"=>($request->get("jam_masuk")),
                    "jam_keluar"=>($request->get("jam_keluar")),
                    "keterangan"=>($request->get("keterangan")),
                    "shifting"=>($request->get("shifting")?1:0),
                   
                    "active"=>1,
                    "create_by" => $idUser,
                    "create_date" => date("Y-m-d H:i:s")
                ]);
                $SQL = " SELECT currval('hrm.seq_absen')";
                $shift=DB::connection()->select($SQL);
//                print_r($shift);
                $id = $shift[0]->currval;

           
				$karyawan_shift = $request->get("karyawan");
				for($i=0;$i<count($karyawan_shift);$i++){
					$help = new Helper_function();
				$date = $request->get("tgl_awal");
				for($j=0;$j<=$help->hitungHari($request->get("tgl_awal"),$request->get("tgl_akhir"));$j++){
					 DB::connection()->table("absen_shift")
               		 ->insert([
                   		"absen_id"=>($id),
                    	"p_karyawan_id"=>($karyawan_shift[$i]),
                    	"tanggal"=>($date),
                    ]);
				$date = $help->tambah_tanggal($date,1);
				}
				
				}
				
			
            DB::commit();
            return redirect()->route('be.shift')->with('success','Hari Libur Berhasil di input!');
        }
        catch(\Exeception $e){
            DB::rollback();
            return redirect()->back()->with('error',$e);
        }

    }
    public function edit_shift($id)
    {
       	$sql = "select * from m_lokasi where active=1 and sub_entitas=0";
		$lokasi=DB::connection()->select($sql);
		$sql = "select * from p_karyawan where active=1 ";
		$list_karyawan=DB::connection()->select($sql);
	
        $type = 'update_jamshift';
        $sqlshift="SELECT * FROM absen WHERE active=1 and absen_id = $id  ";
        $shift=DB::connection()->select($sqlshift);
        $sqlshift="SELECT * FROM absen_shift WHERE  absen_id = $id  ";
        $karyawan=DB::connection()->select($sqlshift);
        foreach($karyawan as $karyawan){
			$data['karyawan'][]=$karyawan->p_karyawan_id;
		}
		$data['nama']='';
		$data['tgl_awal']=$shift[0]->tgl_awal;
		$data['tgl_akhir']=$shift[0]->tgl_akhir;
		$data['jam_awal']=$shift[0]->jam_masuk;
		$data['jam_akhir']=$shift[0]->jam_keluar;
		$data['m_lokasi_id']=$shift[0]->m_lokasi_id;
        $data['keterangan']=$shift[0]->keterangan;
        
        return view('backend.shift.tambah_shift', compact('data','id','type','lokasi','list_karyawan'));
    }
    public function update_shift(Request $request, $id)
    {
        $idUser=Auth::user()->id;
      
		 DB::connection()->table("absen")
                ->where("absen_id",$id)
            ->update([
                    "tgl_awal"=>($request->get("tgl_awal")),
                    "tgl_akhir"=>($request->get("tgl_akhir")),
                    "m_lokasi_id"=>($request->get("entitas")),
                    "jam_masuk"=>($request->get("jam_masuk")),
                    "jam_keluar"=>($request->get("jam_keluar")),
                    "keterangan"=>($request->get("keterangan")),
                    "shifting"=>($request->get("shifting")?1:0),
                   
                ]);
           	DB::connection()->table("absen_shift")
	               		 ->where("absen_id",$id)
	               		 ->delete();
				$karyawan_shift = $request->get("karyawan");
				for($i=0;$i<count($karyawan_shift);$i++){
					$help = new Helper_function();
					$date = $request->get("tgl_awal");
					for($j=0;$j<=$help->hitungHari($request->get("tgl_awal"),$request->get("tgl_akhir"));$j++){
						 DB::connection()->table("absen_shift")
	               		 ->insert([
	                   		"absen_id"=>($id),
	                    	"p_karyawan_id"=>($karyawan_shift[$i]),
	                    	"tanggal"=>($date),
	                    ]);
					$date = $help->tambah_tanggal($date,1);
				}
				}
        return redirect()->route('be.shift')->with('success',' shift Berhasil di Ubah!');
    }
    public function hapus_shift($id)
    {
        $idUser=Auth::user()->id;
        DB::connection()->table("absen")
            ->where("absen_id",$id)
            ->update([
                "active"=>0,
               
            ]);

        return redirect()->back()->with('success',' shift Berhasil di Hapus!');
    }
	public function excel_shift(Request $request)
    {
    	$entitas = $request->get('entitas');
			return  ShiftController::excel_empty_data($entitas);
		
    }public function excel_empty_data($entitas)
    {
    	$help = new Helper_function();
    	$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();
		
		$i=0;						
									
				
		
		$sheet->setCellValue($help->toAlpha($i).'1', 'NIK');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'1', 'Nama Karyawan');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'1', 'KARYAWAN SHIFT');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'1', 'SHIFT KE');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'1', 'TANGGAL AWAL');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'1', 'TANGGAL AKHIR');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'1', 'JAM MASUK');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'1', 'JAM KELUAR');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'1', 'KETERANGAN');	$i++;
		$i=0;	
		
		$sheet->setCellValue($help->toAlpha($i).'2', 'XXXXX');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'2', 'Contoh Pengisian');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'2', '1');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'2', '1');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'2', '2022-07-25');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'2', '2022-08-14');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'2', '07:01');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'2', '10:30');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'2', 'SHIFT KARYAWAN EMM BUAH BATU PEKANAN 2');	$i++;
		$i=0;
		$sheet->setCellValue($help->toAlpha($i).'3', 'XXXXX');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'3', 'Contoh Pengisian Non SHift');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'3', '0');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'3', '');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'3', '');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'3', '');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'3', '');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'3', '');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'3', '');	$i++;
		
		$rows = 4;

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
		$sql="SELECT * FROM p_karyawan
		left join p_karyawan_pekerjaan a on a.p_karyawan_id = p_karyawan.p_karyawan_id
		where p_karyawan.active=1
			and a.m_lokasi_id = $entitas
		 order by nama asc";
        $list_karyawan=DB::connection()->select($sql);

		
		if(!empty($list_karyawan)){
	
			foreach($list_karyawan as $list_karyawan){
				
					$i=0;
                	$sheet->setCellValue($help->toAlpha($i). $rows, $list_karyawan->nik);$i++;
                	$sheet->setCellValue($help->toAlpha($i). $rows, $list_karyawan->nama);$i++;
                	
                               
                                 
                               
				//echo substr($absen->a,0,5);die;
				$rows++;
			}
		}
		
		$sql = "select * from m_lokasi where m_lokasi_id=".$entitas;
		$hisentitas=DB::connection()->select($sql);
		
		$type = 'csv';
		$fileName = "Template Data Shift ".$hisentitas[0]->nama.".csv";
		if($type == 'xlsx'){
			$writer = new Xlsx($spreadsheet);
		} else if($type == 'xls'){
			$writer = new Xls($spreadsheet);
		}else if($type == 'csv'){
			$writer = new Csv($spreadsheet,';');
		}
		$writer->setDelimiter(';');
		$writer->save("export/".$fileName);
		header("Content-Type: application/vnd.ms-excel");
		return redirect(url('/')."/export/".$fileName);
    }public function simpan_excel_shift (Request $request){

	$help = new Helper_function();

  	$mimes = ['application/vnd.ms-excel','text/xls','text/xlsx','application/vnd.oasis.opendocument.spreadsheet'];

	$file = $request->file('excel');
 
      	        // nama file
	//	echo 'File Name: '.$file->getClientOriginalName();
		
 
      	        // isi dengan nama folder tempat kemana file diupload
	$tujuan_upload = 'export';
 
                // upload file
          //       $imageName = time().'.'.$request->file->extension();  
	    	//$request->file->move(public_path('uploads/profil'), $imageName);
	$name = 'shift-'.date('YmdHis').'-'.$file->getClientOriginalName();
	$file->move($tujuan_upload,$name);
    $uploadFilePath = 'export/'.$name;

    //move_uploaded_file($_FILES['file']['tmp_name'], $uploadFilePath);
   // $uploadFilePath = 'Gaji.xlsx';

		
//require('plugins\spreadsheet-reader-master\php-excel-reader/excel_reader2.php');

//require('plugins\spreadsheet-reader-master\SpreadsheetReader.php');
   
        return view('backend.shift.export',compact('uploadFilePath','help'));
	}public function excel_exist_data()
    {
    	$help = new Helper_function();
    	$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();
		
		$i=0;						
									
				
		
		$sheet->setCellValue($help->toAlpha($i).'1', 'NIK');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'1', 'Nama Karyawan');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'1', 'SHIFT KE');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'1', 'JAM MASUK');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'1', 'JAM KELUAR');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'1', 'KETERANGAN');	$i++;
		
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
		$sql="SELECT * FROM p_karyawan
		left join absen_shift on absen_shift.p_karyawan_id=p_karyawan.p_karyawan_id
		left join absen on absen_shift.absen_id=absen.absen_id
		where p_karyawan.active=1 order by nama asc";
        $list_karyawan=DB::connection()->select($sql);

		
		if(!empty($list_karyawan)){
	
			foreach($list_karyawan as $list_karyawan){
				
					$i=0;
                	$sheet->setCellValue($help->toAlpha($i). $rows, $list_karyawan->nik);$i++;
                	$sheet->setCellValue($help->toAlpha($i). $rows, $list_karyawan->nama);$i++;
                	$sheet->setCellValue($help->toAlpha($i). $rows, $list_karyawan->shift_ke);$i++;
                	$sheet->setCellValue($help->toAlpha($i). $rows, $list_karyawan->jam_masuk);$i++;
                	$sheet->setCellValue($help->toAlpha($i). $rows, $list_karyawan->jam_keluar);$i++;
                	$sheet->setCellValue($help->toAlpha($i). $rows, $list_karyawan->keterangan);$i++;
                               
                                 
                               
				//echo substr($absen->a,0,5);die;
				$rows++;
			}
		}
		
		
		
		$type = 'csv';
		$fileName = "Template Exist Data Shift  .csv";
		if($type == 'xlsx'){
			$writer = new Xlsx($spreadsheet);
		} else if($type == 'xls'){
			$writer = new Xls($spreadsheet);
		}else if($type == 'csv'){
			$writer = new Csv($spreadsheet);
		}
		$writer->setDelimiter(';');
		$writer->save("export/".$fileName);
		header("Content-Type: application/vnd.ms-excel");
		return redirect(url('/')."/export/".$fileName);
    }
}

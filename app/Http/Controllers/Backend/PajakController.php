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

class PajakController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }public function pajak()
    {
       
        $sqlpajak="SELECT * from prl_potongan
   				join p_karyawan on prl_potongan.p_karyawan_id = p_karyawan.p_karyawan_id
                WHERE 1=1 and prl_potongan.active=1
                	and m_potongan_id = 20
                order by nama";
        $pajak=DB::connection()->select($sqlpajak);
        
        return view('backend.pajak.pajak',compact('pajak'));
    }

    public function tambah_pajak()
    {
       $sqlentitas="SELECT * from p_karyawan
                WHERE 1=1 and p_karyawan.p_karyawan_id not in (SELECT prl_potongan.p_karyawan_id from prl_potongan
   				join p_karyawan a on prl_potongan.p_karyawan_id = a.p_karyawan_id and a.active=1
                WHERE 1=1 and prl_potongan.active=1 
                	and m_potongan_id = 20) order by nama";
        $karyawan=DB::connection()->select($sqlentitas);
                $id = '';
                $type = 'simpan_pajak';
                $data['p_karyawan_id']='';
                $data['nominal']='';
                $data['nama']='';
                $data['no_anggota']='';
                $data['tgl_awal']='';
                $data['tgl_akhir']='';
                $data['m_lokasi_id']='';   $help = new Helper_function();
        return view('backend.pajak.tambah_pajak',compact('id','data','type','karyawan','help'));
    }
	public function tambah_excel_pajak()
    {
    	

        return view('backend.pajak.tambah_excel_pajak');
    }
    public function excel_empty_data()
    {
    	$help = new Helper_function();$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();
		
		$i=0;						
									
				
		
		
		$sheet->setCellValue($help->toAlpha($i).'1', 'NIK');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'1', 'Nama Karyawan');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'1', 'Nominal');	$i++;
		
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
		left join p_karyawan_gapok on p_karyawan_gapok.p_karyawan_id=p_karyawan.p_karyawan_id and p_karyawan_gapok.active=1
		where p_karyawan.active=1 order by nama asc";
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
		
		
		$type = 'csv';
		$fileName = "Template Empty Data  pajak Karyawan.csv";
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
    }
	public function excel_exist_data()
    {
    	$help = new Helper_function();
    	$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();
		
		$i=0;						
									
				
		
		$sheet->setCellValue($help->toAlpha($i).'1', 'NIK');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'1', 'Nama Karyawan');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'1', 'Nominal');	$i++;
		
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
		left join p_karyawan_gapok on p_karyawan_gapok.p_karyawan_id=p_karyawan.p_karyawan_id and p_karyawan_gapok.active=1
		where p_karyawan.active=1 order by nama asc";
        $list_karyawan=DB::connection()->select($sql);

		
		if(!empty($list_karyawan)){
	
			foreach($list_karyawan as $list_karyawan){
				
					$i=0;
                	$sheet->setCellValue($help->toAlpha($i). $rows, $list_karyawan->nik);$i++;
                	$sheet->setCellValue($help->toAlpha($i). $rows, $list_karyawan->nama);$i++;
                	$sheet->setCellValue($help->toAlpha($i). $rows, $list_karyawan->pajak	);$i++;
                               
                                 
                               
				//echo substr($absen->a,0,5);die;
				$rows++;
			}
		}
		
		
		
		$type = 'csv';
		$fileName = "Template Exist Data Pajak Karyawan .csv";
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
    public function simpan_excel(Request $request){

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
	$name = 'pajak-'.date('YmdHis').'-'.$file->getClientOriginalName();
	$file->move($tujuan_upload,$name);
    $uploadFilePath = 'export/'.$name;

    //move_uploaded_file($_FILES['file']['tmp_name'], $uploadFilePath);
   // $uploadFilePath = 'Gaji.xlsx';

		
//require('plugins\spreadsheet-reader-master\php-excel-reader/excel_reader2.php');

//require('plugins\spreadsheet-reader-master\SpreadsheetReader.php');
    
	
        return view('backend.pajak.export',compact('uploadFilePath','help'));
	}
	public function simpan_pajak(Request $request){
    	
	  // echo $kode;die;
	  $help = new Helper_function(); 
	  $idUser=Auth::user()->id;
	  $sql = "Select * from p_karyawan_gapok where p_karyawan_id=".$request->get("p_karyawan_id");
	  $pajak=DB::connection()->select($sql);
	  if(count($pajak)){
	  		DB::connection()->table("p_karyawan_gapok")
            ->where('p_karyawan_gapok_id',$pajak[0]->p_karyawan_gapok_id)
            ->update([
               
               
               
                "pajak" => $help->hapusrupiah($request->get("nominal")),
                
                "updated_date" => date('Y-m-d'),
                "updated_by" => $idUser,
                
            ]);
	  }else{
	  	 DB::connection()->table("p_karyawan_gapok")
            ->insert([
               
                "p_karyawan_id" => $request->get("p_karyawan_id"),
               
                "pajak" => $help->hapusrupiah($request->get("nominal")),
                
                "created_date" => date('Y-m-d'),
                
            ]);
	  }
         DB::connection()->table("prl_potongan")
          ->where('p_karyawan_id',$request->get("p_karyawan_id"))
          ->where('m_potongan_id',20)
          ->update([
               
                "active" => 0,
                
            ]);
         DB::connection()->table("prl_potongan")
            ->insert([
                "m_potongan_id" => 20,
                "p_karyawan_id" => $request->get("p_karyawan_id"),
               
                "nominal" => $help->hapusrupiah($request->get("nominal")),
                
                "created_date" => date('Y-m-d'),
                "active" => 1,
                
            ]);

        return redirect()->route('be.pajak')->with('success',' pajak Berhasil di input!');
    }

    public function edit_pajak($id)
    {
        
               
        $type = 'update_pajak';
        $sqlpajak="SELECT * FROM prl_potongan WHERE  prl_potongan_id = $id  ";
        $pajak=DB::connection()->select($sqlpajak);
		
       // print_r($pajak[0]);
        
        $sqlentitas="SELECT * from p_karyawan
                WHERE 1=1 ";
        $karyawan=DB::connection()->select($sqlentitas);
               
                $type = 'update_pajak';
                $data['p_karyawan_id']=$pajak[0]->p_karyawan_id;
                $data['nominal']=$pajak[0]->nominal;
                $help = new Helper_function();
        return view('backend.pajak.tambah_pajak', compact('data','id','type','karyawan','help'));
    }

    public function update_pajak(Request $request, $id){
        $idUser=Auth::user()->id; $help = new Helper_function();
        $sql = "Select * from p_karyawan_gapok where p_karyawan_id=".$request->get("p_karyawan_id").' and active=1';
	  $pajak=DB::connection()->select($sql);
	  //print_r($pajak);
	  if(count($pajak)){
	  		DB::connection()->table("p_karyawan_gapok")
            ->where('p_karyawan_gapok_id',$pajak[0]->p_karyawan_gapok_id)
            ->update([
               
               
               
                "pajak" => $help->hapusrupiah($request->get("nominal")),
                
                "updated_date" => date('Y-m-d'),
                "updated_by" => $idUser,
                
            ]);
	  }else{
	  	 DB::connection()->table("p_karyawan_gapok")
            ->insert([
               
                "p_karyawan_id" => $request->get("p_karyawan_id"),
               
                "pajak" => $help->hapusrupiah($request->get("nominal")),
                
                "created_date" => date('Y-m-d'),
                
            ]);
	  }
        DB::connection()->table("prl_potongan")
            ->where("prl_potongan_id",$id)
            ->update([
                
                "nominal" => $help->hapusrupiah($request->get("nominal")),
            ]);
            
           

        return redirect()->route('be.pajak')->with('success',' pajak Berhasil di Ubah!');
    }

    public function hapus_pajak($id)
    {
        $idUser=Auth::user()->id;
        DB::connection()->table("prl_potongan")
            ->where("prl_potongan_id",$id)
            ->update([
                "active"=>0,
               
            ]);

        return redirect()->back()->with('success',' pajak Berhasil di Hapus!');
    }
}
	
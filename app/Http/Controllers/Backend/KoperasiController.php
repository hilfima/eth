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

class KoperasiController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }public function koperasi(Request $request,$page)
    {
    	if(date('m')==1){
    		$tahun = date('Y')-1;
    		$bulan =12;
    	}else {
    		$tahun = date('Y');
    		$bulan =date('m')-1;
    	}
       	$date = $tahun.'-'.$bulan.'-26';
       	$hwere='';
       //	if($page=='asa')
       		$hwere = "and tgl_akhir >= '$date'";
       		$periode_gaji = $request->get('periode_gaji');
      	if($periode_gaji){
      		if($periode_gaji==-1)
      			$periode_gaji = 0;
       		$hwere .= " and periode_gajian = ".$periode_gaji;
      	}
      	$wherepajak='';
      	if($request->get('pajak'))
      	$wherepajak="and p_karyawan_pekerjaan.pajak_onoff='".$request->get('pajak')."'";
      	$wheregaji='';
      	if($request->get('status'))
      	$wheregaji="and p_karyawan_pekerjaan.periode_gajian='".$request->get('periode_gajian')."'";
       
       $iduser=Auth::user()->id;
         $sqluser="SELECT p_recruitment.foto,role,p_karyawan_pekerjaan,p_karyawan_pekerjaan.m_lokasi_id,user_entitas_access FROM users
left join m_role on m_role.m_role_id=users.role
left join p_karyawan on p_karyawan.user_id=users.id
left join p_karyawan_pekerjaan on p_karyawan_pekerjaan.p_karyawan_id=p_karyawan.p_karyawan_id
left join p_recruitment on p_recruitment.p_recruitment_id=p_karyawan.p_recruitment_id
where users.id=$iduser";
        $user=DB::connection()->select($sqluser);
        $whereLokasi = "";
           /* if($user[0]->user_entitas_access and (!in_array(strtoupper($page),array("ASA","KKB")))){ 
			 	$id_lokasi = $user[0]->user_entitas_access;
				$whereLokasi = "AND m_lokasi.m_lokasi_id = $id_lokasi";			
							
			}else{
				$whereLokasi = "";			
				
			}*/
			if($request->entitas){ 
			 	$id_lokasi = $request->entitas;
				$whereLokasi2 = "AND p_karyawan_pekerjaan.m_lokasi_id = $id_lokasi";			
							
			}else{
				$whereLokasi2 = "";			
				
			}
       
       	$entitas=DB::connection()->select("select * from m_lokasi where active=1 and sub_entitas=0 $whereLokasi ");
        
        
        $sqlkoperasi="
        		SELECT p_karyawan_koperasi.*,pajak_onoff,p_karyawan.nama,p_karyawan.nik,m_lokasi.nama as nmentitas, case when periode_gajian=1 then 'Bulanan ' else 'Pekanan' end as periode_gajian 
        		from p_karyawan_koperasi
   				join p_karyawan on p_karyawan_koperasi.p_karyawan_id = p_karyawan.p_karyawan_id
				left join p_karyawan_pekerjaan on p_karyawan_pekerjaan.p_karyawan_id=p_karyawan.p_karyawan_id
				left join m_lokasi on p_karyawan_pekerjaan.m_lokasi_id=m_lokasi.m_lokasi_id
				left join m_jabatan on m_jabatan.m_jabatan_id=p_karyawan_pekerjaan.m_jabatan_id
                WHERE 1=1 and p_karyawan_koperasi.active=1 and p_karyawan.active=1 and nama_koperasi ='".strtoupper($page)."' $wherepajak $hwere $whereLokasi2 $whereLokasi and m_jabatan.m_pangkat_id not in(6) order by tgl_akhir, nama asc";
        $koperasi=DB::connection()->select($sqlkoperasi);
         $help = new Helper_function();
		return view('backend.koperasi.koperasi',compact('koperasi','page','help','request','entitas'));
    }

	public function tambah_koperasi($page)
    {
       $sqlentitas="SELECT * from p_karyawan
                WHERE 1=1 and active=1  order by nama asc";
        $karyawan=DB::connection()->select($sqlentitas);
                $id = '';
                $type = 'simpan_koperasi';
                $data['p_karyawan_id']='';
                $data['nominal']='';
                $data['nama']='';
                $data['no_anggota']='';
                $data['tgl_awal']='';
                $data['tgl_akhir']='';
                $data['m_lokasi_id']='';
				return view('backend.koperasi.tambah_koperasi',compact('id','data','type','karyawan','page'));
    }
	public function tambah_excel_koperasi($page)
    {
    	

        return view('backend.koperasi.tambah_excel_koperasi',compact('page'));
    }
    public function excel_empty_data($page)
    {
    	$help = new Helper_function();$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();
		
		$i=0;						
									
				
		
		
		$sheet->setCellValue($help->toAlpha($i).'1', 'NIK');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'1', 'Nama Karyawan');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'1', 'Entitas');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'1', 'Pajak');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'1', 'Periode Gajian');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'1', 'No Anggota');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'1', 'Nominal Anguran Per Bulan');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'1', 'Tgl Awal(YYYY-MM-DD)');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'1', 'Tgl Akhir(YYYY-MM-DD)');	$i++;
		
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
		$sql="SELECT *,p_karyawan.nama,p_karyawan.nik,m_lokasi.nama as nmentitas, case when periode_gajian=1 then 'Bulanan ' else 'Pekanan' end as periode_gajian
        		FROM p_karyawan
				left join p_karyawan_pekerjaan on p_karyawan_pekerjaan.p_karyawan_id=p_karyawan.p_karyawan_id
				left join m_lokasi on p_karyawan_pekerjaan.m_lokasi_id=m_lokasi.m_lokasi_id
				
				
		where p_karyawan.active=1  and p_karyawan.p_karyawan_id not in(27,23,93,136,279) order by p_karyawan.nama asc";
        $list_karyawan=DB::connection()->select($sql);

		
		if(!empty($list_karyawan)){
	
			foreach($list_karyawan as $list_karyawan){
				
					$i=0;
                	$sheet->setCellValue($help->toAlpha($i). $rows, $list_karyawan->nik);$i++;
                	$sheet->setCellValue($help->toAlpha($i). $rows, $list_karyawan->nama);$i++;
                	$sheet->setCellValue($help->toAlpha($i). $rows, $list_karyawan->nmentitas);$i++;
                	$sheet->setCellValue($help->toAlpha($i). $rows, $list_karyawan->pajak_onoff);$i++;
                	$sheet->setCellValue($help->toAlpha($i). $rows, $list_karyawan->periode_gajian);$i++;
                	
                               
                                 
                               
				//echo substr($absen->a,0,5);die;
				$rows++;
			}
		}
		
		
		$type = 'csv';
		$fileName = "Template Empty Data  Koperasi Karyawan ".ucwords($page).".csv";
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
	
	public function excel_exist_data(Request $request,$page)
    {
    	$help = new Helper_function();
    	$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();
		
		$i=0;						
									
				
		
		$sheet->setCellValue($help->toAlpha($i).'1', 'NIK');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'1', 'Nama Karyawan');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'1', 'Entitas');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'1', 'Pajak');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'1', 'Periode Gajian');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'1', 'No Anggota');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'1', 'Nominal Anguran Per Bulan');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'1', 'Tgl Awal(YYYY-MM-DD)');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'1', 'Tgl Akhir(YYYY-MM-DD)');	$i++;
		
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
		if(date('m')==1){
    		$tahun = date('Y')-1;
    		$bulan =12;
    	}else {
    		$tahun = date('Y');
    		$bulan =date('m')-1;
    	}
       	$date = $tahun.'-'.$bulan.'-26';
       	$hwere='';
       //	if($page=='asa')
       		$hwere = "and tgl_akhir >= '$date'";
       		$periode_gaji = $request->get('periode_gaji');
      	if($periode_gaji){
      		if($periode_gaji==-1)
      			$periode_gaji = 0;
       		$hwere .= " and periode_gajian = ".$periode_gaji;
      	}
      	$wherepajak='';
      	if($request->get('pajak'))
      	$wherepajak="and p_karyawan_pekerjaan.pajak_onoff='".$request->get('pajak')."'";
      	$wheregaji='';
      	if($request->get('status'))
      	$wheregaji="and p_karyawan_pekerjaan.periode_gajian='".$request->get('periode_gajian')."'";
        $sql="SELECT p_karyawan_koperasi.*,pajak_onoff,p_karyawan.nama,p_karyawan.nik,m_lokasi.nama as nmentitas, case when periode_gajian=1 then 'Bulanan ' else 'Pekanan' end as periode_gajian
        		from p_karyawan_koperasi
   				join p_karyawan on p_karyawan_koperasi.p_karyawan_id = p_karyawan.p_karyawan_id
				left join p_karyawan_pekerjaan on p_karyawan_pekerjaan.p_karyawan_id=p_karyawan.p_karyawan_id
				left join m_lokasi on p_karyawan_pekerjaan.m_lokasi_id=m_lokasi.m_lokasi_id
				left join m_jabatan on m_jabatan.m_jabatan_id=p_karyawan_pekerjaan.m_jabatan_id
                WHERE 1=1 and p_karyawan_koperasi.active=1 
                and p_karyawan.active=1
                and nama_koperasi ='".strtoupper($page)."' $wherepajak $hwere and m_jabatan.m_pangkat_id not in(6) order by tgl_akhir, nama asc";
        $list_karyawan=DB::connection()->select($sql);
		/*SELECT * FROM p_karyawan
		left join p_karyawan_koperasi on p_karyawan_koperasi.p_karyawan_id=p_karyawan.p_karyawan_id and p_karyawan_koperasi.active=1 and nama_koperasi ='".strtoupper($page)."'
		where p_karyawan.active=1 and p_karyawan.p_karyawan_id not in(27,23,93,136,279) order by nama asc*/
		//echo $sql;die;
		/*echo '<pre>';
		var_dump($list_karyawan);
		echo '</pre>';*/

		
		if(!empty($list_karyawan)){
	
			foreach($list_karyawan as $list_karyawan){
				
					$i=0;
                	$sheet->setCellValue($help->toAlpha($i). $rows, $list_karyawan->nik);$i++;
                	$sheet->setCellValue($help->toAlpha($i). $rows, $list_karyawan->nama);$i++;
                	$sheet->setCellValue($help->toAlpha($i). $rows, $list_karyawan->nmentitas);$i++;
                	$sheet->setCellValue($help->toAlpha($i). $rows, $list_karyawan->pajak_onoff);$i++;
                	$sheet->setCellValue($help->toAlpha($i). $rows, $list_karyawan->periode_gajian);$i++;
                	$sheet->setCellValue($help->toAlpha($i). $rows, $list_karyawan->no_anggota);$i++;
                	$sheet->setCellValue($help->toAlpha($i). $rows, $list_karyawan->nominal);$i++;
                	$sheet->setCellValue($help->toAlpha($i). $rows, $list_karyawan->tgl_awal);$i++;
                	$sheet->setCellValue($help->toAlpha($i). $rows, $list_karyawan->tgl_akhir);$i++;
                               
                                 
                               
				//echo substr($absen->a,0,5);die;
				/*echo '<pre>';
				echo $list_karyawan->nik;'<br>';
				echo $list_karyawan->nama;'<br>';
				echo $list_karyawan->nominal;'<br>';
				echo '</pre>';*/
				$rows++;
			}
		}//die;
		
		
		
		$type = 'csv';
		$fileName = "Template Exist Data Karyawan ".ucwords($page)." .csv";
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
    public function simpan_excel(Request $request,$page){

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
	$name = 'koperasi-'.date('YmdHis').'-'.$file->getClientOriginalName();
	$file->move($tujuan_upload,$name);
    $uploadFilePath = 'export/'.$name;

    //move_uploaded_file($_FILES['file']['tmp_name'], $uploadFilePath);
   // $uploadFilePath = 'Gaji.xlsx';

		
//require('plugins\spreadsheet-reader-master\php-excel-reader/excel_reader2.php');

//require('plugins\spreadsheet-reader-master\SpreadsheetReader.php');
    
	
        return view('backend.koperasi.export',compact('uploadFilePath','help','page'));
	}
	public function simpan_koperasi(Request $request){
    	
	  // echo $kode;die;
	  $page = $request->get('page');
	  $help = new Helper_function(); $idUser=Auth::user()->id;
	  $koperasi=DB::connection()->select("select * from p_karyawan_koperasi where nama_koperasi='".strtoupper($page)."' and p_karyawan_id='".$request->get("p_karyawan_id")."' and active=1");
	  if(count($koperasi)){
	  	DB::connection()->table("p_karyawan_koperasi")
	  		->where('nama_koperasi',strtoupper($page))
	  		->where('p_karyawan_id',$request->get("p_karyawan_id"))
            ->update([
               
                "p_karyawan_id" => $request->get("p_karyawan_id"),
                "no_anggota" => $request->get("no_anggota"),
                "nominal" => $help->hapusrupiah($request->get("nominal")),
                "tgl_awal" => $request->get("tgl_awal"),
                "tgl_akhir" => $request->get("tgl_akhir"),
                "updated_date" => date('Y-m-d H:i:s'),
                "updated_by" => $idUser,
                
            ]);
	  }else{
	  	
         DB::connection()->table("p_karyawan_koperasi")
            ->insert([
                "p_karyawan_id" => $request->get("p_karyawan_id"),
                "no_anggota" => $request->get("no_anggota"),
				"nama_koperasi" => strtoupper($page),
                "nominal" => $help->hapusrupiah($request->get("nominal")),
                "tgl_awal" => $request->get("tgl_awal"),
                "tgl_akhir" => $request->get("tgl_akhir"),
                "created_date" => date('Y-m-d H:i:s'),
                "created_by" => $idUser,
                
            ]);
	  }

        return redirect()->route('be.koperasi',$page)->with('success',' koperasi Berhasil di input!');
    }

    public function edit_koperasi($id,$page)
    {
        
              
                $type = 'update_koperasi';
        $sqlkoperasi="SELECT * FROM p_karyawan_koperasi WHERE  p_karyawan_koperasi_id = $id  ";
        $koperasi=DB::connection()->select($sqlkoperasi);
		
        
        
        $sqlentitas="SELECT * from p_karyawan
                WHERE 1=1 and active=1 order by nama asc";
        $karyawan=DB::connection()->select($sqlentitas);
                
                $data['p_karyawan_id']=$koperasi[0]->p_karyawan_id;
                $data['nominal']=$koperasi[0]->nominal;
                $data['nama']=$koperasi[0]->nama_koperasi;
                $data['no_anggota']=$koperasi[0]->no_anggota;
                $data['tgl_awal']=$koperasi[0]->tgl_awal;
                $data['tgl_akhir']=$koperasi[0]->tgl_akhir;
               
        return view('backend.koperasi.tambah_koperasi', compact('data','id','type','karyawan','page'));
    }

    public function update_koperasi(Request $request, $id){
        $idUser=Auth::user()->id;
        $help=new Helper_function();
        DB::connection()->table("p_karyawan_koperasi")
            ->where("p_karyawan_koperasi_id",$id)
            ->update([
                "no_anggota" => $request->get("no_anggota"),
                "nominal" => $help->hapusrupiah($request->get("nominal")),
                "tgl_awal" => $request->get("tgl_awal"),
                "tgl_akhir" => $request->get("tgl_akhir"),
            ]);

        return redirect()->route('be.koperasi',$_GET['page'])->with('success',' koperasi Berhasil di Ubah!');
    }

    public function hapus_koperasi($id)
    {
        $idUser=Auth::user()->id;
        DB::connection()->table("p_karyawan_koperasi")
            ->where("p_karyawan_koperasi_id",$id)
            ->update([
                "active"=>0,
               
            ]);

        return redirect()->back()->with('success',' koperasi Berhasil di Hapus!');
    }
}
	
<?php

namespace App\Http\Controllers\Backend;

use App\ajuan_xls;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\Setting;
use DB;
use Maatwebsite\Excel\Excel;
use Mail;
use App\Helper_function;
use Response;

use App\Eloquent\AbsenLog;
use App\Eloquent\Absen;
use DateTime;;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use PhpOffice\PhpSpreadsheet\Writer\Csv;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PHPExcel;
use PHPExcel_IOFactory;

class GajiPokokPekananController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

    public function view()
    {
        $sqlkaryawan = "SELECT a.p_karyawan_id,a.nik,a.nama as nama_lengkap,case when a.active=1 then 'Active' else 'Non Active' end as status,
c.nama as nmlokasi,b.kantor,b.kota,d.nama as nmdivisi,f.nama as nmdept,g.nama as nmjabatan,
h.tgl_awal,h.tgl_akhir,i.nama as nmstatus,kg.gapok,tunjangan_grade,kg.*
FROM p_karyawan_gapok kg
LEFT JOIN p_karyawan a on kg.p_karyawan_id=a.p_karyawan_id
LEFT JOIN p_karyawan_pekerjaan b on b.p_karyawan_id=a.p_karyawan_id
LEFT JOIN m_lokasi c on c.m_lokasi_id=b.m_lokasi_id
LEFT JOIN m_divisi d on d.m_divisi_id=b.m_divisi_id
LEFT JOIN m_departemen f on f.m_departemen_id=b.m_departemen_id
LEFT JOIN m_jabatan g on g.m_jabatan_id=b.m_jabatan_id
LEFT JOIN p_karyawan_kontrak h on h.p_karyawan_id=a.p_karyawan_id
LEFT JOIN m_status_pekerjaan i on i.m_status_pekerjaan_id=h.m_status_pekerjaan_id
                    WHERE 1=1 and a.active=1 and m_pangkat_id !=6 and b.periode_gajian=0 and kg.active=1 order by a.nama";
        $karyawan = DB::connection()->select($sqlkaryawan);

        $iduser = Auth::user()->id;
        $sqluser = "SELECT p_recruitment.foto FROM users
left join p_karyawan on p_karyawan.user_id=users.id
left join p_recruitment on p_recruitment.p_recruitment_id=p_karyawan.p_recruitment_id
where users.id=$iduser";
        $user = DB::connection()->select($sqluser);
        $help = new Helper_function();
		$array = $help->element_gaji("pekanan");
        return view('backend.gaji.gapok_pekanan.viewgapok_pekanan', compact('karyawan', 'user', 'help', 'array'));
    } 
    public function lihat($id)
    {
    	 $help = new Helper_function();
    	 $sql = "select * from p_karyawan_gapok where p_karyawan_id = $id and active=1";
    	 $gapok=DB::connection()->select($sql);
    	 $gapok=$gapok[0];
    	 $disable ='disabled';
		 return view('backend.gaji.gapok_pekanan.edit_gapok_pekanan',compact('gapok','help','disable'));
    } 
	public function edit($id)
    {
    	 $help = new Helper_function();
    	 $sql = "select * from p_karyawan_gapok where p_karyawan_id = $id and active=1";
    	 $gapok=DB::connection()->select($sql);
    	 $gapok=$gapok[0];
    	 $disable ='';
		 return view('backend.gaji.gapok_pekanan.edit_gapok_pekanan',compact('gapok','help','disable'));
    } 
    public function tambah()
    {
    	 $iduser=Auth::user()->id;
      	$sqluser="SELECT p_recruitment.foto FROM users
left join p_karyawan on p_karyawan.user_id=users.id
left join p_recruitment on p_recruitment.p_recruitment_id=p_karyawan.p_recruitment_id
where users.id=$iduser";
        $user=DB::connection()->select($sqluser);

		return view('backend.gaji.gapok_pekanan.tambah_gapok_pekanan',compact('user'));
    }
    public function excel_empty_data()
    {
    	$help = new Helper_function();$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();
		
		$i=0;						
									
				
		
		$sheet->setCellValue($help->toAlpha($i).'1', 'Nama Karyawan');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'1', 'Upah Harian');	$i++;
		//$sheet->setCellValue($help->toAlpha($i).'1', 'Tunj Kost');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'1', 'Infaq');	$i++; 
		$sheet->setCellValue($help->toAlpha($i).'1', 'Zakat');	$i++;
		/*
		
		$sheet->setCellValue($help->toAlpha($i).'1', 'Iuran Kost');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'1', 'Pajak');	$i++; 
		$sheet->setCellValue($help->toAlpha($i).'1', 'Koperasi KKB');	$i++; 
		$sheet->setCellValue($help->toAlpha($i).'1', 'Koreksi Plus');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'1', 'Koreksi Min');	$i++;  
		*/
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
		$sql="SELECT *,p_karyawan.nama FROM p_karyawan
		left join p_karyawan_gapok on p_karyawan_gapok.p_karyawan_id=p_karyawan.p_karyawan_id and p_karyawan_gapok.active=1
		left join p_karyawan_pekerjaan on p_karyawan_pekerjaan.p_karyawan_id=p_karyawan.p_karyawan_id 
		LEFT JOIN m_jabatan f on p_karyawan_pekerjaan.m_jabatan_id=f.m_jabatan_id 
		where p_karyawan.active=1 and periode_gajian = 0
		and m_pangkat_id != 6
		order by p_karyawan.nama asc";
        $list_karyawan=DB::connection()->select($sql);

		
		if(!empty($list_karyawan)){
	
			foreach($list_karyawan as $list_karyawan){
				
					$i=0;
                	$sheet->setCellValue($help->toAlpha($i). $rows, $list_karyawan->nama);$i++;
                	
                               
                                 
                               
				//echo substr($absen->a,0,5);die;
				$rows++;
			}
		}
		
		
		$type = 'csv';
		$fileName = "Template Empty Data Gaji Pekanan.csv";
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
		$sheet->setCellValue($help->toAlpha($i).'1', 'Upah Harian');	$i++;
		//$sheet->setCellValue($help->toAlpha($i).'1', 'Tunj Kost');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'1', 'Infaq');	$i++; 
		$sheet->setCellValue($help->toAlpha($i).'1', 'Zakat');	$i++;
		/*
		$sheet->setCellValue($help->toAlpha($i).'1', 'Iuran Kost');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'1', 'Pajak');	$i++; 
		$sheet->setCellValue($help->toAlpha($i).'1', 'Koperasi KKB');	$i++; 
		$sheet->setCellValue($help->toAlpha($i).'1', 'Koreksi Plus');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'1', 'Koreksi Min');	$i++;  
		*/
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
		$sql="SELECT *,p_karyawan.nama FROM p_karyawan
		left join p_karyawan_gapok on p_karyawan_gapok.p_karyawan_id=p_karyawan.p_karyawan_id and p_karyawan_gapok.active=1
		left join p_karyawan_pekerjaan on p_karyawan_pekerjaan.p_karyawan_id=p_karyawan.p_karyawan_id 
		LEFT JOIN m_jabatan f on p_karyawan_pekerjaan.m_jabatan_id=f.m_jabatan_id 
		where p_karyawan.active=1 and periode_gajian = 0
		and m_pangkat_id != 6
		 order by p_karyawan.nama asc";
        $list_karyawan=DB::connection()->select($sql);

		
		if(!empty($list_karyawan)){
	
			foreach($list_karyawan as $list_karyawan){
				
					$i=0;
                	$sheet->setCellValue($help->toAlpha($i). $rows, $list_karyawan->nik);$i++;
                	$sheet->setCellValue($help->toAlpha($i). $rows, $list_karyawan->nama);$i++;
                	$sheet->setCellValue($help->toAlpha($i). $rows, $list_karyawan->upah_harian);$i++;
                	//$sheet->setCellValue($help->toAlpha($i). $rows, $list_karyawan->tunjangan_kost);$i++;
                	$sheet->setCellValue($help->toAlpha($i). $rows, $list_karyawan->infaq);$i++;
                	$sheet->setCellValue($help->toAlpha($i). $rows, $list_karyawan->zakat);$i++;
                	/*
                	$sheet->setCellValue($help->toAlpha($i). $rows, $list_karyawan->sewa_kost);$i++;
                	$sheet->setCellValue($help->toAlpha($i). $rows, $list_karyawan->pajak);$i++;
                	$sheet->setCellValue($help->toAlpha($i). $rows, $list_karyawan->koperasi_kkb);$i++;
                	$sheet->setCellValue($help->toAlpha($i). $rows, $list_karyawan->korekmin);$i++;
                	$sheet->setCellValue($help->toAlpha($i). $rows, $list_karyawan->korekplus);$i++;
                    */           
                                 
                              
				//echo substr($absen->a,0,5);die;
				$rows++;
			}
		}
		
		
		
		$type = 'csv';
		$fileName = "Template Exist Data Gaji Pekanan.csv";
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
	public function hapus($id_kary)
    { 
    	  DB::beginTransaction();
        try{
            DB::connection()->table("p_karyawan_gapok")
                ->where("p_karyawan_id",$id_kary)
                ->update([
                  
                    "active"=>0,
                ]);
                DB::connection()->table("prl_tunjangan")
                ->where("p_karyawan_id",$id_kary)
                ->update([
                  
                    "active"=>0,
                ]);
                DB::connection()->table("prl_potongan")
                ->where("p_karyawan_id",$id_kary)
                ->update([
                  
                    "active"=>0,
                ]);
            DB::commit();
            return redirect()->route('be.gapok_pekanan')->with('success','Detail Gaji Karyawan Berhasil di Hapus!');
        }
        catch(\Exeception $e){
            DB::rollback();
            return redirect()->back()->with('error',$e);
        }
    }
	public function tambah_excel()
    {
    	

        return view('backend.gaji.gapok_pekanan.tambah_excel_pekanan');
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
	    	$name = date('YmdHis').$file->getClientOriginalName();
	$file->move($tujuan_upload,$name);
    $uploadFilePath = 'export/'.$name;

    //move_uploaded_file($_FILES['file']['tmp_name'], $uploadFilePath);
   // $uploadFilePath = 'Gaji.xlsx';

		
//require('plugins\spreadsheet-reader-master\php-excel-reader/excel_reader2.php');

//require('plugins\spreadsheet-reader-master\SpreadsheetReader.php');

	
        return view('backend.gaji.gapok_pekanan.export_pekanan',compact('uploadFilePath','help'));
	}
	public function simpan(Request $request){
        DB::beginTransaction();
        try{
        	$help=new Helper_function();
			//echo $help->hapusRupiah($request->get("tunjangan_kost")); die;
            $idUser=Auth::user()->id;
            DB::connection()->table("p_karyawan_gapok")
                ->insert([
                    "upah_harian"=>$help->hapusRupiah($request->get("upah_harian")),
                    "p_karyawan_id"=>($request->get("karyawan")),
                   
                    "tunjangan_kost"=>$help->hapusRupiah($request->get("tunjangan_kost")), 
                    
                    "zakat"=> $help->hapusRupiah( $request->get("zakat")),
                    "infaq"=> $help->hapusRupiah( $request->get("infaq")),
                   
                    //"korekmin" => $help->hapusRupiah($request->get("korekmin")),
                    //"korekplus" => $help->hapusRupiah($request->get("korekplus")),
                    
                    "active"=>1,
                    
                    "created_date" => date("Y-m-d H:i:s"), "created_by" => $idUser, "updated_date" => date("Y-m-d H:i:s"), "updated_by" => $idUser,
                    "created_by" => $idUser
                ]);
                $zakat = $help->hapusRupiah( $request->get("zakat"));
                $infaq  = $help->hapusRupiah( $request->get("infaq"));
                $kkb = $help->hapusRupiah($request->get("koperasi_kkb"));
                //gapok
            DB::connection()->table("prl_tunjangan")
                ->insert([
                    "nominal"=>$help->hapusRupiah($request->get("upah_harian")),
                    "p_karyawan_id"=>($request->get("karyawan")),
                    "m_tunjangan_id"=>(18),
                    "is_gapok"=>(1),
                    "active"=>(1),
                   
                    "created_date" => date("Y-m-d H:i:s"), "created_by" => $idUser, "updated_date" => date("Y-m-d H:i:s"), "updated_by" => $idUser,
                ]);
                /*
            DB::connection()->table("prl_tunjangan")
                ->insert([
                    "nominal"=>$help->hapusRupiah($request->get("korekplus")),
                    "p_karyawan_id"=>($request->get("karyawan")),
                    "m_tunjangan_id"=>(16),
                    "is_gapok"=>(1),
                    "active"=>(1),
                   
                    "created_date" => date("Y-m-d H:i:s"), "created_by" => $idUser, "updated_date" => date("Y-m-d H:i:s"), "updated_by" => $idUser,
                ]);*/
               /* DB::connection()->table("prl_tunjangan")
                ->insert([
                    "nominal"=>$help->hapusRupiah($request->get("tunjangan_grade")),
                    "p_karyawan_id"=>($request->get("karyawan")),
                    "m_tunjangan_id"=>(11),
                    "is_gapok"=>(1),
                    "active"=>(1),
                   
                    "created_date" => date("Y-m-d H:i:s"), "created_by" => $idUser, "updated_date" => date("Y-m-d H:i:s"), "updated_by" => $idUser,
                ]);
            DB::connection()->table("prl_tunjangan")
                ->insert([
                    "nominal"=>$help->hapusRupiah($request->get("tunjangan_bpjskes")),
                    "p_karyawan_id"=>($request->get("karyawan")),
                    "m_tunjangan_id"=>(12),
                    "active"=>(1),
                   
                    "created_date" => date("Y-m-d H:i:s"), "created_by" => $idUser, "updated_date" => date("Y-m-d H:i:s"), "updated_by" => $idUser,
                ]);
            DB::connection()->table("prl_tunjangan")
                ->insert([
                    "nominal"=>$help->hapusRupiah($request->get("tunjangan_bpjsket")),
                    "p_karyawan_id"=>($request->get("karyawan")),
                    "m_tunjangan_id"=>(13),
                    "active"=>(1),
                   
                    "created_date" => date("Y-m-d H:i:s"), "created_by" => $idUser, "updated_date" => date("Y-m-d H:i:s"), "updated_by" => $idUser,
	                ]);*/
	            DB::connection()->table("prl_tunjangan")
	                ->insert([
	                    "nominal"=>$help->hapusRupiah($request->get("tunjangan_kost")),
	                    "p_karyawan_id"=>($request->get("karyawan")),
	                    "m_tunjangan_id"=>(14),
	                    "active"=>(1),
	                   
	                    "created_date" => date("Y-m-d H:i:s"), "created_by" => $idUser, "updated_date" => date("Y-m-d H:i:s"), "updated_by" => $idUser,
	                ]);
	                
	         /*       
            DB::connection()->table("prl_potongan")
                ->insert([
                    "nominal"=>$help->hapusRupiah($request->get("sewa_kost")),
                    "p_karyawan_id"=>($request->get("karyawan")),
                    "m_potongan_id"=>(16),
                    "active"=>(1),
                   
                    "created_date" => date("Y-m-d H:i:s"), "created_by" => $idUser, "updated_date" => date("Y-m-d H:i:s"), "updated_by" => $idUser,
                ]);
                
            DB::connection()->table("prl_potongan")
                ->insert([
                    "nominal"=>$help->hapusRupiah($request->get("iuran_bpjskes")),
                    "p_karyawan_id"=>($request->get("karyawan")),
                    "m_potongan_id"=>(14),
                    "active"=>(1),
                   
                    "created_date" => date("Y-m-d H:i:s"), "created_by" => $idUser, "updated_date" => date("Y-m-d H:i:s"), "updated_by" => $idUser,
                ]);
            DB::connection()->table("prl_potongan")
                ->insert([
                    "nominal"=>$help->hapusRupiah($request->get("iuran_bpjsket")),
                    "p_karyawan_id"=>($request->get("karyawan")),
                    "m_potongan_id"=>(15),
                    "active"=>(1),
                   
                    "created_date" => date("Y-m-d H:i:s"), "created_by" => $idUser, "updated_date" => date("Y-m-d H:i:s"), "updated_by" => $idUser,
                ]);
            DB::connection()->table("prl_potongan")
                ->insert([
                    "nominal"=>$help->hapusRupiah($request->get("pajak")),
                    "p_karyawan_id"=>($request->get("karyawan")),
                    "m_potongan_id"=>(20),
                    "active"=>(1),
                   
                    "created_date" => date("Y-m-d H:i:s"), "created_by" => $idUser, "updated_date" => date("Y-m-d H:i:s"), "updated_by" => $idUser,
                ]);
              
             
                DB::connection()->table("prl_potongan")
                ->insert([
                    "nominal"=>$help->hapusRupiah($kkb),
                    "p_karyawan_id"=>($request->get("karyawan")),
                    "m_potongan_id"=>(9),
                    "active"=>(1),
                   
                    "created_date" => date("Y-m-d H:i:s"), "created_by" => $idUser, "updated_date" => date("Y-m-d H:i:s"), "updated_by" => $idUser,
                ]);*/
                DB::connection()->table("prl_potongan")
                ->insert([
                    "nominal"=>$help->hapusRupiah($infaq),
                    "p_karyawan_id"=>($request->get("karyawan")),
                    "m_potongan_id"=>(19),
                    "active"=>(1),
                   
                    "created_date" => date("Y-m-d H:i:s"), "created_by" => $idUser, "updated_date" => date("Y-m-d H:i:s"), "updated_by" => $idUser,
                ]);
                /*DB::connection()->table("prl_potongan")
                ->insert([
                    "nominal"=>$help->hapusRupiah($request->get("korekmin")),
                    "p_karyawan_id"=>($request->get("karyawan")),
                    "m_potongan_id"=>(17),
                    "active"=>(1),
                   
                    "created_date" => date("Y-m-d H:i:s"), "created_by" => $idUser, "updated_date" => date("Y-m-d H:i:s"), "updated_by" => $idUser,
                ]);*/
                DB::connection()->table("prl_potongan")
                ->insert([
                    "nominal"=>$help->hapusRupiah($zakat),
                    "p_karyawan_id"=>($request->get("karyawan")),
                    "m_potongan_id"=>(18),
                    "active"=>(1),
                   
                    "created_date" => date("Y-m-d H:i:s"), "created_by" => $idUser, "updated_date" => date("Y-m-d H:i:s"), "updated_by" => $idUser,
                ]);
            DB::commit();
            return redirect()->route('be.gapok_pekanan')->with('success','Detail Gaji Berhasil di input!');
        }
        catch(\Exeception $e){
            DB::rollback();
            return redirect()->back()->with('error',$e);
        }

    }
	public function update(Request $request){
        DB::beginTransaction();
        try{
        	$help=new Helper_function();
			//echo $help->hapusRupiah($request->get("tunjangan_kost")); die;
            $idUser=Auth::user()->id;
            $id_kary = $request->get("karyawan");
            DB::connection()->table("p_karyawan_gapok")
                ->where("p_karyawan_id", $id_kary)
                ->update([

                    "active" => 0,
                ]);
            DB::connection()->table("prl_tunjangan")
                ->where("p_karyawan_id", $id_kary)
                ->update([

                    "active" => 0,
                ]);
            DB::connection()->table("prl_potongan")
                ->where("p_karyawan_id", $id_kary)
                ->update([

                    "active" => 0,
                ]);
                DB::connection()->table("p_karyawan_gapok")
                ->insert([
					"upah_harian"=>$help->hapusRupiah($request->get("upah_harian")),
                    "p_karyawan_id"=>($request->get("karyawan")),
                   
                    "tunjangan_kost"=>$help->hapusRupiah($request->get("tunjangan_kost")), 
                    
                    "zakat"=> $help->hapusRupiah( $request->get("zakat")),
                    "infaq"=> $help->hapusRupiah( $request->get("infaq")),
                   // "korekmin" => $help->hapusRupiah($request->get("korekmin")),
                    //"korekplus" => $help->hapusRupiah($request->get("korekplus")),
                   
                    "active"=>1,
                    
                    "created_date" => date("Y-m-d H:i:s"), "created_by" => $idUser, "updated_date" => date("Y-m-d H:i:s"), "updated_by" => $idUser,
                    "created_by" => $idUser
					
                ]);
                //gapok
            DB::connection()->table("prl_tunjangan")
                ->insert([
					"nominal"=>$help->hapusRupiah($request->get("upah_harian")),
                    "p_karyawan_id"=>($request->get("karyawan")),
					"m_tunjangan_id"=>(18),
                    "is_gapok"=>(1),
                    "active"=>(1),
                   
                    "created_date" => date("Y-m-d H:i:s"), "created_by" => $idUser, "updated_date" => date("Y-m-d H:i:s"), "updated_by" => $idUser,
                ]);
                /*
            DB::connection()->table("prl_tunjangan")
                ->insert([
                    "nominal"=>$help->hapusRupiah($request->get("korekplus")),
                    "p_karyawan_id"=>($request->get("karyawan")),
                    "m_tunjangan_id"=>(16),
                    "is_gapok"=>(1),
                    "active"=>(1),
                   
                    "created_date" => date("Y-m-d H:i:s"), "created_by" => $idUser, "updated_date" => date("Y-m-d H:i:s"), "updated_by" => $idUser,
                ]);
				
				DB::connection()->table("prl_potongan")
                ->insert([
                    "nominal"=>$help->hapusRupiah($request->get("korekmin")),
                    "p_karyawan_id"=>($request->get("karyawan")),
                    "m_potongan_id"=>(17),
                    "active"=>(1),
                   
                    "created_date" => date("Y-m-d H:i:s"), "created_by" => $idUser, "updated_date" => date("Y-m-d H:i:s"), "updated_by" => $idUser,
                ]);
*/
				DB::connection()->table("prl_tunjangan")
				->insert([
				"nominal"=>$help->hapusRupiah($request->get("tunjangan_kost")),
					"p_karyawan_id"=>($request->get("karyawan")),
					"m_tunjangan_id"=>(14),
					"active"=>(1),

					"created_date" => date("Y-m-d H:i:s"), "created_by" => $idUser, "updated_date" => date("Y-m-d H:i:s"), "updated_by" => $idUser,
				]);

/*
				DB::connection()->table("prl_potongan")
				->insert([
				"nominal"=>$help->hapusRupiah($request->get("sewa_kost")),
					"p_karyawan_id"=>($request->get("karyawan")),
					"m_potongan_id"=>(16),
					"active"=>(1),

					"created_date" => date("Y-m-d H:i:s"), "created_by" => $idUser, "updated_date" => date("Y-m-d H:i:s"), "updated_by" => $idUser,
				]);

				DB::connection()->table("prl_potongan")
				->insert([
				"nominal"=>$help->hapusRupiah($request->get("pajak")),
					"p_karyawan_id"=>($request->get("karyawan")),
					"m_potongan_id"=>(20),
					"active"=>(1),

					"created_date" => date("Y-m-d H:i:s"), "created_by" => $idUser, "updated_date" => date("Y-m-d H:i:s"), "updated_by" => $idUser,
				]);
				DB::connection()->table("prl_potongan")
				->insert([
				"nominal"=>$help->hapusRupiah($request->get("koperasi_kkb")),
					"p_karyawan_id"=>($request->get("karyawan")),
					"m_potongan_id"=>(9),
					"active"=>(1),

					"created_date" => date("Y-m-d H:i:s"), "created_by" => $idUser, "updated_date" => date("Y-m-d H:i:s"), "updated_by" => $idUser,
				]);*/
				DB::connection()->table("prl_potongan")
				->insert([
				"nominal"=>$help->hapusRupiah($request->get("infaq")),
					"p_karyawan_id"=>($request->get("karyawan")),
					"m_potongan_id"=>(19),
					"active"=>(1),

					"created_date" => date("Y-m-d H:i:s"), "created_by" => $idUser, "updated_date" => date("Y-m-d H:i:s"), "updated_by" => $idUser,
				]);
				DB::connection()->table("prl_potongan")
				->insert([
				"nominal"=>$help->hapusRupiah($request->get("zakat")),
					"p_karyawan_id"=>($request->get("karyawan")),
					"m_potongan_id"=>(18),
					"active"=>(1),

					"created_date" => date("Y-m-d H:i:s"), "created_by" => $idUser, "updated_date" => date("Y-m-d H:i:s"), "updated_by" => $idUser,
				]);
                /*
            DB::connection()->table("prl_potongan")
                ->insert([
                    "nominal"=>$help->hapusPersen($request->get("zakat")),
                    "p_karyawan_id"=>($request->get("karyawan")),
                    "m_potongan_id"=>(18),
                    "active"=>(1),
                   
                    "created_date" => date("Y-m-d H:i:s"), "created_by" => $idUser, "updated_date" => date("Y-m-d H:i:s"), "updated_by" => $idUser,
                ]);
            DB::connection()->table("prl_potongan")
                ->insert([
                    "nominal"=>$help->hapusPersen($request->get("infaq")),
                    "p_karyawan_id"=>($request->get("karyawan")),
                    "m_potongan_id"=>(18),
                    "active"=>(1),
                   
                    "created_date" => date("Y-m-d H:i:s"), "created_by" => $idUser, "updated_date" => date("Y-m-d H:i:s"), "updated_by" => $idUser,
                ]);*/	
            DB::commit();
            return redirect()->route('be.gapok_pekanan')->with('success','Detail Gaji Berhasil di input!');
        }
        catch(\Exeception $e){
            DB::rollback();
            return redirect()->back()->with('error',$e);
        }

    }

}

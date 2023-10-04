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
use \PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use PhpOffice\PhpSpreadsheet\Writer\Csv;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PHPExcel;
use PHPExcel_IOFactory;

class GajiPokokController extends Controller
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

    public function view($type)
    {
    	if($type=='bulanan'){
    		$periode = 1;
    	}else{
    		$periode = 0;
    		
    	}
        $sqlkaryawan = "SELECT a.p_karyawan_id,a.nik,a.nama as nama_lengkap,case when a.active=1 then 'Active' else 'Non Active' end as status,
c.nama as nmlokasi,b.kantor,b.kota,d.nama as nmdivisi,f.nama as nmdept,g.nama as nmjabatan,
h.tgl_awal,h.tgl_akhir,i.nama as nmstatus,kg.gapok,tunjangan_grade,kg.*, h.tgl_awal,h.tgl_akhir
FROM p_karyawan_gapok kg
LEFT JOIN p_karyawan a on kg.p_karyawan_id=a.p_karyawan_id
LEFT JOIN p_karyawan_pekerjaan b on b.p_karyawan_id=a.p_karyawan_id
LEFT JOIN m_lokasi c on c.m_lokasi_id=b.m_lokasi_id
LEFT JOIN m_divisi d on d.m_divisi_id=b.m_divisi_id
LEFT JOIN m_departemen f on f.m_departemen_id=b.m_departemen_id
LEFT JOIN m_jabatan g on g.m_jabatan_id=b.m_jabatan_id
LEFT JOIN p_karyawan_kontrak h on h.p_karyawan_id=a.p_karyawan_id and h.active=1
LEFT JOIN m_status_pekerjaan i on i.m_status_pekerjaan_id=h.m_status_pekerjaan_id
                    WHERE 1=1 and a.active=1 and m_pangkat_id !=6 and b.periode_gajian=$periode and kg.active=1 order by a.nama";
        $karyawan = DB::connection()->select($sqlkaryawan);

        $iduser = Auth::user()->id;
        $sqluser = "SELECT p_recruitment.foto FROM users
left join p_karyawan on p_karyawan.user_id=users.id
left join p_recruitment on p_recruitment.p_recruitment_id=p_karyawan.p_recruitment_id
where users.id=$iduser";
        $user = DB::connection()->select($sqluser);
        $help = new Helper_function();
		$array = $help->element_gaji($type);
        return view('backend.gaji.gapok.viewgapok', compact('karyawan', 'user', 'help', 'array', 'type'));
    }
    public function lihat($type,$id)
    {
        $help = new Helper_function();
        $sql = "select * from p_karyawan_gapok where p_karyawan_id = $id and active=1";
        $gapok = DB::connection()->select($sql);
        $gapok = $gapok[0];
        $disable = 'disabled';
        
        $array = $help->element_gaji($type);
        return view('backend.gaji.gapok.edit_gapok', compact('gapok', 'help', 'disable', 'array', 'type'));
    }
    public function edit($type,$id)
    {
        $help = new Helper_function();
        $sql = "select * from p_karyawan_gapok where p_karyawan_id = $id and active=1";
        $gapok = DB::connection()->select($sql);
        $gapok = $gapok[0];
        $disable = '';
        $array = $help->element_gaji($type);
        return view('backend.gaji.gapok.edit_gapok', compact('gapok', 'help', 'disable', 'array', 'type'));
    }
    public function tambah($type)
    {
         $help = new Helper_function();$iduser = Auth::user()->id;
        $sqluser = "SELECT p_recruitment.foto FROM users
left join p_karyawan on p_karyawan.user_id=users.id
left join p_recruitment on p_recruitment.p_recruitment_id=p_karyawan.p_recruitment_id
where users.id=$iduser";
        $user = DB::connection()->select($sqluser);
		$array = $help->element_gaji($type);
        return view('backend.gaji.gapok.tambah_gapok', compact('array', 'type'));
    }
    public function excel_empty_data($type)
    {
        $help = new Helper_function();
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $i = 0;



      	$array = $help->element_gaji($type);
		

        $sheet->setCellValue($help->toAlpha($i) . '1', 'NIK');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '1', 'Nama Karyawan');
        $i++;
	       for($y=0;$y<count($array);$y++){
	       	 	$sheet->setCellValue($help->toAlpha($i) . '1', $array[$y][0]);
	        	$i++;
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
        if($type=='bulanan'){
    		$periode = 1;
    	}else{
    		$periode = 0;
    		
    	}
        $sql = "SELECT p_karyawan.nama,*,p_karyawan.nama,p_karyawan.nik::text FROM p_karyawan
		left join p_karyawan_gapok on p_karyawan_gapok.p_karyawan_id=p_karyawan.p_karyawan_id and p_karyawan_gapok.active=1
		left join p_karyawan_pekerjaan on p_karyawan_pekerjaan.p_karyawan_id=p_karyawan.p_karyawan_id 
		LEFT JOIN m_jabatan f on p_karyawan_pekerjaan.m_jabatan_id=f.m_jabatan_id 
		where p_karyawan.active=1 and periode_gajian = $periode and m_pangkat_id != 6
		  order by p_karyawan.nama asc";
        $list_karyawan = DB::connection()->select($sql);

        if (!empty($list_karyawan)) {

            foreach ($list_karyawan as $list_karyawan) {
//echo $list_karyawan->nik
                $i = 0;
                echo $nik = sprintf("%08d", $list_karyawan->nik);;echo '<br>';
				
                $sheet->setCellValueExplicit($help->toAlpha($i) . $rows, $list_karyawan->nik, DataType::TYPE_STRING);
                $sheet->getStyle($help->toAlpha($i). $rows)->getNumberFormat()->setFormatCode('00000000');
                ;
                $i++;
                $sheet->setCellValue($help->toAlpha($i) . $rows, $list_karyawan->nama);
                $i++;
				



                //echo substr($absen->a,0,5);die;
                $rows++;
            }
//die;
        }

		$query = (DB::getQueryLog()); $help = new Helper_function();
		$help->historis(($_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']),json_encode($query));
            
        $type = 'csv';
        $fileName = "Template Empty Data.csv";
        if ($type == 'xlsx') {
            $writer = new Xlsx($spreadsheet);
        } else if ($type == 'xls') {
            $writer = new Xls($spreadsheet);
        } else if ($type == 'csv') {
            $writer = new Csv($spreadsheet, ';');
        }
        $writer->setDelimiter(';');
        $writer->save("export/" . $fileName);
        header("Content-Type: application/vnd.ms-excel");
        return redirect(url('/') . "/export/" . $fileName);
    }
    public function excel_exist_data($type)
    {
        $help = new Helper_function();
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $i = 0;
		$array = $help->element_gaji($type);
		

        $sheet->setCellValue($help->toAlpha($i) . '1', 'NIK');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '1', 'Nama Karyawan');
        $i++;
	       for($y=0;$y<count($array);$y++){
	       	 	$sheet->setCellValue($help->toAlpha($i) . '1', $array[$y][0]);
	        	$i++;
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
       if($type=='bulanan'){
    		$periode = 1;
    	}else{
    		$periode = 0;
    		
    	}
        $sqlkaryawan = "SELECT a.p_karyawan_id,a.nik::text,a.nama as nama_lengkap,case when a.active=1 then 'Active' else 'Non Active' end as status,
c.nama as nmlokasi,b.kantor,b.kota,d.nama as nmdivisi,f.nama as nmdept,g.nama as nmjabatan,
h.tgl_awal,h.tgl_akhir,i.nama as nmstatus,kg.gapok,tunjangan_grade,kg.*
FROM p_karyawan a
LEFT JOIN p_karyawan_gapok kg on kg.p_karyawan_id=a.p_karyawan_id and kg.active=1
LEFT JOIN p_karyawan_pekerjaan b on b.p_karyawan_id=a.p_karyawan_id
LEFT JOIN m_lokasi c on c.m_lokasi_id=b.m_lokasi_id
LEFT JOIN m_divisi d on d.m_divisi_id=b.m_divisi_id
LEFT JOIN m_departemen f on f.m_departemen_id=b.m_departemen_id
LEFT JOIN m_jabatan g on g.m_jabatan_id=b.m_jabatan_id
LEFT JOIN p_karyawan_kontrak h on h.p_karyawan_id=a.p_karyawan_id and h.active=1
LEFT JOIN m_status_pekerjaan i on i.m_status_pekerjaan_id=h.m_status_pekerjaan_id
                    WHERE 1=1 and a.active=1 and m_pangkat_id !=6 and b.periode_gajian=$periode  order by a.nama";
        $karyawan = DB::connection()->select($sqlkaryawan);
		
		$nik = array();
        if (!empty($karyawan)) {
			
            foreach ($karyawan as $list_karyawan) {
            	if(!in_array($list_karyawan->nik,$nik)){
            		
				$nik[]= $list_karyawan->nik;
                $i = 0;
                $sheet->setCellValue($help->toAlpha($i) . $rows, $list_karyawan->nik);
                $sheet->getStyle($help->toAlpha($i). $rows)->getNumberFormat()->setFormatCode('0#######');
				
                $sheet->setCellValueExplicit($help->toAlpha($i) . $rows, $list_karyawan->nik, DataType::TYPE_STRING);
                $i++;
                $sheet->setCellValue($help->toAlpha($i) . $rows, $list_karyawan->nama_lengkap);
                $i++;
                for($y=0;$y<count($array);$y++){
                		$row = $array[$y][1];
			       	 	$sheet->setCellValue($help->toAlpha($i) . $rows, $list_karyawan->$row);
			        	$i++;
			    }
               



                //echo substr($absen->a,0,5);die;
                $rows++;
            	}
            }
        }

		$query = (DB::getQueryLog()); $help = new Helper_function();
		$help->historis(($_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']),json_encode($query));
           
		$iduser=Auth::user()->id;
        $sqlidkar="select * from users where id=$iduser";
        $iduser=DB::connection()->select($sqlidkar);
        
        $type = 'csv';
        $fileName = "Template Exist Data ".date('YmdHis')."-".$iduser[0]->name."  .csv";
        if ($type == 'xlsx') {
            $writer = new Xlsx($spreadsheet);
        } else if ($type == 'xls') {
            $writer = new Xls($spreadsheet);
        } else if ($type == 'csv') {
            $writer = new Csv($spreadsheet);
        }
        $writer->setDelimiter(';');
        $writer->save("export/" . $fileName);
        header("Content-Type: application/vnd.ms-excel");
        return redirect(url('/') . "/export/" . $fileName);
    }
    public function hapus($id_kary)
    {
        DB::beginTransaction();
        try {
        	$iduser=Auth::user()->id;
        	
            DB::connection()->table("p_karyawan_gapok")
                ->where("p_karyawan_id", $id_kary)
                ->update([

                    "updated_by" => $iduser,
                    "updated_date" => date('Y-m-d H:i:s'),
                    "active" => 0,
                ]);
            DB::connection()->table("prl_tunjangan")
                ->where("p_karyawan_id", $id_kary)
                ->update([
					
                    "updated_by" => $iduser,
                    "updated_date" => date('Y-m-d H:i:s'),
                    "active" => 0,
                ]);
            DB::connection()->table("prl_potongan")
                ->where("p_karyawan_id", $id_kary)
                ->update([

                    "updated_by" => $iduser,
                    "updated_date" => date('Y-m-d H:i:s'),
                    "active" => 0,
                ]);
            DB::commit();
            return redirect()->route('be.gapok')->with('success', 'Detail Gaji Karyawan Berhasil di Hapus!');
        } catch (\Exeception $e) {
            DB::rollback();
            return redirect()->back()->with('error', $e);
        }
    }
    public function tambah_excel($type)
    {


        return view('backend.gaji.gapok.tambah_excel',compact('type'));
    }
    public function simpan_excel(Request $request,$type)
    {

        $help = new Helper_function();

        $mimes = ['application/vnd.ms-excel', 'text/xls', 'text/xlsx', 'application/vnd.oasis.opendocument.spreadsheet'];

        $file = $request->file('excel');

        // nama file
        //	echo 'File Name: '.$file->getClientOriginalName();


        // isi dengan nama folder tempat kemana file diupload
        $tujuan_upload = 'export';

        // upload file
        //       $imageName = time().'.'.$request->file->extension();  
        //$request->file->move(public_path('uploads/profil'), $imageName);
        $iduser=Auth::user()->id;
        $sqlidkar="select * from users where id=$iduser";
        $iduser=DB::connection()->select($sqlidkar);
        
        $name = 'import-gapok-'.date('YmdHis').'-'.$iduser[0]->name. $file->getClientOriginalName();
        $file->move($tujuan_upload, $name);
       
        
        $uploadFilePath = $tujuan_upload.'/'. $name;

        //move_uploaded_file($_FILES['file']['tmp_name'], $uploadFilePath);
        // $uploadFilePath = 'Gaji.xlsx';


        //require('plugins\spreadsheet-reader-master\php-excel-reader/excel_reader2.php');

        //require('plugins\spreadsheet-reader-master\SpreadsheetReader.php');
		$array = $help->element_gaji($type);
		

	       
		
		$query = (DB::getQueryLog()); $help = new Helper_function();
		$help->historis(($_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']),json_encode($query));
            
        return view('backend.gaji.gapok.export', compact('uploadFilePath', 'help', 'array', 'type'));
    }
    public function simpan(Request $request,$type)
    {
        DB::beginTransaction();
        try {
        	
DB::enableQueryLog();
            $help = new Helper_function();
            //echo $help->hapusRupiah($request->get("tunjangan_kost")); die;
            $iduser = Auth::user()->id;
             DB::connection()->table("p_karyawan_gapok")
                ->where('p_karyawan_id', $request->get("karyawan"))
                ->update(["active" => 0]);
             $array = $help->element_gaji($type);
			$input = array();
			$input['p_karyawan_id'] =($request->get("karyawan"));
			$input['active'] =1;
			$input['created_date'] =date("Y-m-d H:i:s");
			$input['created_by'] =$iduser;
			for($y=0;$y<count($array);$y++){
	       	 	$row = $array[$y][1];
	       	 	$input[$row] =$help->hapusRupiah($request->$row);
	    	}
            DB::connection()->table("p_karyawan_gapok")
                ->insert($input);
           
            //gapok
           
            DB::connection()->table("prl_tunjangan")
                ->where('p_karyawan_id', $request->get("karyawan"))
                ->update([
                    "updated_by" => $iduser,
                    "updated_date" => date('Y-m-d H:i:s'),"active" => 0]);
            DB::connection()->table("prl_potongan")
                ->where('p_karyawan_id', $request->get("karyawan"))
                ->update([
                    "updated_by" => $iduser,
                    "updated_date" => date('Y-m-d H:i:s'),"active" => 0]);
                
                for($y=0;$y<count($array);$y++){
        	$row = $array[$y][1];
        DB::connection()->table("prl_".$array[$y][2])
            ->insert([
                "nominal" => $help->hapusRupiah($request->$row),
                "p_karyawan_id" => ($request->get("karyawan")),
                "m_".$array[$y][2]."_id" => ($array[$y][3]),
                "is_gapok" => $array[$y][4],
                "active" => (1),

                "created_date" => date("Y-m-d H:i:s"),
                "created_by" => $iduser
            ]);
		}
			$query = (DB::getQueryLog()); $help = new Helper_function();
			$help->historis(($_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']),json_encode($query));
            
            DB::commit();
            return redirect()->route('be.gapok',$type)->with('success', 'Detail Gaji Berhasil di input!');
        } catch (\Exeception $e) {
            DB::rollback();
            return redirect()->back()->with('error', $e);
        }
    }
    public function update(Request $request,$type)
    {
        DB::beginTransaction();
        try {
            $help = new Helper_function();
            //echo $help->hapusRupiah($request->get("tunjangan_kost")); die;
            $iduser = Auth::user()->id;
            DB::enableQueryLog();
            $id_kary = $request->get("karyawan");
            DB::connection()->table("p_karyawan_gapok")
                ->where("p_karyawan_id", $id_kary)
                ->update([

                    "updated_by" => $iduser,
                    "updated_date" => date('Y-m-d H:i:s'),
                    "active" => 0,
                ]);
            DB::connection()->table("prl_tunjangan")
                ->where("p_karyawan_id", $id_kary)
                ->update([

                    "updated_by" => $iduser,
                    "updated_date" => date('Y-m-d H:i:s'),
                    "active" => 0,
                ]);
            DB::connection()->table("prl_potongan")
                ->where("p_karyawan_id", $id_kary)
                ->update([

                    "updated_by" => $iduser,
                    "updated_date" => date('Y-m-d H:i:s'),
                    "active" => 0,
                ]);
            $iduser = Auth::user()->id;
             $array = $help->element_gaji($type);
			$input = array();
			$input['p_karyawan_id'] =($request->get("karyawan"));
			$input['active'] =1;
			$input['updated_date'] =date("Y-m-d H:i:s");
			$input['updated_by'] =$iduser;
			for($y=0;$y<count($array);$y++){
	       	 	$row = $array[$y][1];
	       	 	$input[$row] =$help->hapusRupiah($request->$row);
	    	}
            DB::connection()->table("p_karyawan_gapok")
                ->insert($input);
           
            //gapok
            for($y=0;$y<count($array);$y++){
        	$row = $array[$y][1];
        DB::connection()->table("prl_".$array[$y][2])
            ->insert([
                "nominal" => $help->hapusRupiah($request->$row),
                "p_karyawan_id" => ($request->get("karyawan")),
                "m_".$array[$y][2]."_id" => ($array[$y][3]),
                
                "active" => (1),

                 "updated_date" => date("Y-m-d H:i:s"), "updated_by" => $iduser,
            ]);
		}
			$query = (DB::getQueryLog()); $help = new Helper_function();
			$help->historis(($_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']),json_encode($query));
            
            DB::commit();
            return redirect()->route('be.gapok',$type)->with('success', 'Detail Gaji Berhasil di input!');
        } catch (\Exeception $e) {
            DB::rollback();
            return redirect()->back()->with('error', $e);
        }
    }
}

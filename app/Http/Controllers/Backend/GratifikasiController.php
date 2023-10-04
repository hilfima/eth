<?php

namespace App\Http\Controllers\Backend;

use App\absenpro_xls;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Auth;
use Response;
use Maatwebsite\Excel\Facades\Excel;
use App\Eloquent\AbsenLog;
use App\Eloquent\Absen;
use App\Helper_function;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PHPExcel;
use PHPExcel_IOFactory;
	
use PhpOffice\PhpSpreadsheet\Worksheet\MemoryDrawing;
class GratifikasiController extends Controller
{
    public function laporan_gratifikasi()
    {
		$thn = date('Y');
    	$gratifikasi=DB::connection()->select("select *,a.nama as nama_karyawan,c.nama as nama_entitas  
    	,(select sum(perkiraan_harga::numeric) from t_gratifikasi x where tgl_diterima >= '$thn-01-01' and tgl_diterima <= '$thn-12-31' and (status=2 or status=5)  and active=1 and a.p_karyawan_id = x.p_karyawan_yg_melaporkan) as terpakai,
    	case 
    		when d.m_pangkat_id=6 then 2500000
    		when d.m_pangkat_id=5 or d.m_pangkat_id=4 then 1500000
    		else 1000000 
    			end as plafon_awal
    	from t_gratifikasi 
		--left join m_tipe_pemberian on t_gratifikasi.m_tipe_pemberian_id = m_tipe_pemberian.m_tipe_pemberian_id
		left join p_karyawan a on t_gratifikasi.p_karyawan_yg_melaporkan = a.p_karyawan_id
		left join p_karyawan_pekerjaan b on b.p_karyawan_id = a.p_karyawan_id
		left join m_lokasi c on b.m_lokasi_id = c.m_lokasi_id
		left join m_jabatan d on b.m_jabatan_id = d.m_jabatan_id
		where t_gratifikasi.active =1
		order by t_gratifikasi_id desc,tgl_pelaporan desc
		");
		$help = new Helper_function();
		return view('backend.gratifikasi.laporan_gratifikasi',compact('gratifikasi','help'));
    }public function tambah_laporan_gratifikasi()
    {
    	
    	$tipe_pemberian=DB::connection()->select("select * from m_tipe_pemberian where active=1");
    	$karyawan=DB::connection()->select("select * from p_karyawan where active=1 order by nama");
		return view('backend.gratifikasi.tambah_gratifikasi',compact('tipe_pemberian','karyawan'));
    }public function baca_laporan_gratifikasi($id)
    {
    	$gratifikasi=DB::connection()->select("select *,a.nama as karyawan_dari from t_gratifikasi 
		--left join m_tipe_pemberian on t_gratifikasi.m_tipe_pemberian_id = m_tipe_pemberian.m_tipe_pemberian_id
		left join p_karyawan a on t_gratifikasi.p_karyawan_dari = a.p_karyawan_id
		where t_gratifikasi.active =1  and t_gratifikasi.t_gratifikasi_id=$id");
    	$tipe_pemberian=DB::connection()->select("select * from m_tipe_pemberian where active=1");
    	$karyawan=DB::connection()->select("select * from p_karyawan where active=1 order by nama");
		$type='baca';
		return view('backend.gratifikasi.baca_gratifikasi',compact('tipe_pemberian','karyawan','gratifikasi','type'));
    }public function update_laporan_gratifikasi(Request $request,$id)
	{
		
		$data =  $request->get("data");
		$data2['status'] =  $data['status'];
		DB::connection()->table("t_gratifikasi")
		->where('t_gratifikasi_id',$id)
		->update($data2);
		

		return redirect()->route('be.laporan_gratifikasi')->with('success',' Laporan Gratifikasi Berhasil di input!');
	}public function edit_laporan_gratifikasi($id)
    {
    	$gratifikasi=DB::connection()->select("select *,a.nama as karyawan_dari from t_gratifikasi 
		--left join m_tipe_pemberian on t_gratifikasi.m_tipe_pemberian_id = m_tipe_pemberian.m_tipe_pemberian_id
		left join p_karyawan a on t_gratifikasi.p_karyawan_dari = a.p_karyawan_id
		where t_gratifikasi.active =1  and t_gratifikasi.t_gratifikasi_id=$id");
    	$tipe_pemberian=DB::connection()->select("select * from m_tipe_pemberian where active=1");
    	$karyawan=DB::connection()->select("select * from p_karyawan where active=1 order by nama");
    	$type='edit';
		return view('backend.gratifikasi.baca_gratifikasi',compact('tipe_pemberian','karyawan','gratifikasi','type','id'));
    }
    public function simpan_laporan_gratifikasi(Request $request)
	{
		$iduser=Auth::user()->id;
		$sqlidkar="select * from p_karyawan left  join p_karyawan_pekerjaan on p_karyawan.p_karyawan_id = p_karyawan_pekerjaan.p_karyawan_id where user_id=$iduser";
		$idkar=DB::connection()->select($sqlidkar);
		$id=$idkar[0]->p_karyawan_id;
		$data =  $request->get("data");
		$data['p_karyawan_yg_melaporkan'] =  $id;
		$data['tgl_pelaporan'] =  date('Y-m-d');
		if ($request->file('file')) { //echo 'masuk';die;
					$file = $request->file('file');
					$destination = "dist/img/file/";
					$path = 'gratifikasi-' . date('ymdhis') . '-' . $file->getClientOriginalName();
					$file->move($destination, $path);
					
					$data['bukti'] =  $path;
					
		}
		DB::connection()->table("t_gratifikasi")
		->insert($data);
		

		return redirect()->route('be.laporan_gratifikasi')->with('success',' Laporan Gratifikasi Berhasil di input!');
	}
	public function export_gratifikasi()
    {
    	ini_set('memory_limit', '4048M');

       
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
   
        $help = new Helper_function();
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $i = 0;

		
        $sheet->setCellValue($help->toAlpha($i) . '1', 'No');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '1', 'Tanggal lapor');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '1', 'Nama');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '1', 'Tanggal Terima');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '1', 'Nama lembaga/perusahaan/nama pemberi');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '1', 'nama Barang');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '1', 'Perkiraan Harga');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '1', 'Keterangan');
        $i++;
       $i++;
        $gratifikasi=DB::connection()->select("select *,a.nama as karyawan_dari from t_gratifikasi 
		--left join m_tipe_pemberian on t_gratifikasi.m_tipe_pemberian_id = m_tipe_pemberian.m_tipe_pemberian_id
		left join p_karyawan a on t_gratifikasi.p_karyawan_yg_melaporkan = a.p_karyawan_id
		where t_gratifikasi.active =1
		order by tgl_pelaporan 
		 
		");

		 $rows = 2;
        if (!empty($gratifikasi)) {
            $no = 0;
            
            foreach ($gratifikasi as $gratifikasi) {
                $no++;
                $total_all = 0;



                $i = 0;
                $sheet->setCellValue($help->toAlpha($i) . $rows, $no);
                $i++;
                $sheet->setCellValue($help->toAlpha($i) . $rows, $gratifikasi->tgl_pelaporan);
                $i++;
                $sheet->setCellValue($help->toAlpha($i) . $rows, 	$gratifikasi->nama);
                $i++;
                $sheet->setCellValue($help->toAlpha($i) . $rows, $gratifikasi->tgl_diterima);
                $i++;
                $sheet->setCellValue($help->toAlpha($i) . $rows, $gratifikasi->dari);
                $i++;
                
                $sheet->setCellValue($help->toAlpha($i) . $rows, $gratifikasi->nama_tipe_pemberian);
                $i++;
                $sheet->setCellValue($help->toAlpha($i) . $rows, $gratifikasi->perkiraan_harga);
                $i++;
                $sheet->setCellValue($help->toAlpha($i) . $rows, $gratifikasi->keterangan);
                $i++;
                $ex = explode('.',$gratifikasi->bukti);
                if($gratifikasi->bukti and in_array(strtolower($ex[count($ex)-1]),array('png','jpg','jpeg'))){
               // echo $gratifikasi->bukti;	
                
                $IMG = __DIR__ .('\../../../../public/dist/img/file/'.$gratifikasi->bukti);
				$row_num = $rows;
				if (isset($IMG) && !empty($IMG)) {
				    $imageType = $ex[count($ex)-1];

				
						
				    $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
				    $sheet->getRowDimension($row_num)->setRowHeight(200);
				    //$sheet->mergeCells('A'.$row_num.':H'.$row_num);

				    //$gdImage = ($imageType == 'png') ? imagecreatefrompng($IMG) : imagecreatefromjpeg($IMG);
				    $drawing->setName('Company Logo');
				    $drawing->setDescription('Company Logo image');
				    $drawing->setResizeProportional(true);
				   	//echo $IMG;
				   	//echo '<br>';
				    $drawing->setPath($IMG); /* put your path and image here */
				   
				    //$drawing->setRenderingFunction(\PhpOffice\PhpSpreadsheet\Worksheet\MemoryDrawing::RENDERING_JPEG);
				    //$drawing->setMimeType(\PhpOffice\PhpSpreadsheet\Worksheet\MemoryDrawing::MIMETYPE_DEFAULT);
				    
				    $drawing->setHeight(200);
				    $drawing->setOffsetX(5);
				    $drawing->setOffsetY(10);
				    $drawing->setCoordinates($help->toAlpha($i).$row_num);
				    $drawing->setWorksheet($spreadsheet->getActiveSheet());
				    $row_num++;
				
				}
				}
                     
                $rows++;
            }
		}
		
		


		$iduser=Auth::user()->id;
        $sqlidkar="select * from users where id=$iduser";
        $iduser=DB::connection()->select($sqlidkar);
        
        $type = 'xlsx';
        $fileName = "Gratifikasi ".$iduser[0]->name." "  .date('YmdHis'). "." . $type;
        if ($type == 'xlsx') {
            $writer = new Xlsx($spreadsheet);
        } else if ($type == 'xls') {
            $writer = new Xls($spreadsheet);
        }
        $writer->save("export/" . $fileName);
        header("Content-Type: application/vnd.ms-excel");
        return redirect(url('/') . "/export/" . $fileName);
    	
    		$iduser=Auth::user()->id;
        $sqlidkar="select * from users where id=$iduser";
        $iduser=DB::connection()->select($sqlidkar);
        
        $type = 'xlsx';
        $fileName = "Gratifikasi ".$iduser[0]->name." "  .date('YmdHis'). "." . $type;
        if ($type == 'xlsx') {
            $writer = new Xlsx($spreadsheet);
        } else if ($type == 'xls') {
            $writer = new Xls($spreadsheet);
        }
        $writer->save("export/" . $fileName);
        header("Content-Type: application/vnd.ms-excel");
        return redirect(url('/') . "/export/" . $fileName);
    	
    }
	public function m(Request $request)
	{
		
		

	}public function hapus_laporan_gratifikasi(Request $request,$id)
	{
		$iduser=Auth::user()->id;
		$data['active'] =  1;
		
		DB::connection()->table("t_gratifikasi")
		->where("t_gratifikasi_id",$id)
		->update($data);
		

		return redirect()->route('fe.laporan_gratifikasi')->with('success',' Laporan Gratifikasi Berhasil di input!');
	}
}
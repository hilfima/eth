<?php

namespace App\Http\Controllers\Backend;
use App\Http\Controllers\Controller;

use App\rekaplembur_xls;
use App\User;
use App\Helper_function;
use Illuminate\Http\Request;
use DB;
use Auth;
use PhpOffice\PhpSpreadsheet\Reader\Xml\Style\Fill;
use Response;
use PDF;

class permintaan_suratController extends Controller
{
	public function __construct()
    {
        $this->middleware('auth');
    }public function permintaan_surat(Request $request)
	{
		

		$sqlfasilitas="SELECT * FROM t_permintaan_surat

		WHERE 1=1  and active=1 ";
		$permintaan_surat=DB::connection()->select($sqlfasilitas);

		return view('backend.permintaan_surat.permintaan_surat',compact('permintaan_surat'));
	}
	public function edit_permintaan_surat($id)
	{
		$type = 'update_permintaan_surat';
		$surat=DB::connection()->select("select * from t_permintaan_surat where active=1 and t_permintaan_surat_id=$id  ");
		$data['jenis_surat']=$surat[0]->jenis_surat;
		$data['keterangan_surat']=$surat[0]->keterangan_surat;
		$data['status']=$surat[0]->status; 

		return view('backend.permintaan_surat.tambah_permintaan_surat',compact('id','data','type'));
	}public function pdf_permintaan_surat($id)
	{
		$type = 'update_permintaan_surat';
		$surat=DB::connection()->select("select 
			*,p_karyawan.nama as nama_lengkap, m_jabatan.nama as nama_jabatan,m_lokasi.nama as lokasi
			
			from t_permintaan_surat
		left join p_karyawan on p_karyawan.p_karyawan_id = t_permintaan_surat.p_karyawan_pengaju
		left join p_karyawan_pekerjaan  on  p_karyawan.p_karyawan_id = p_karyawan_pekerjaan.p_karyawan_id
		left join m_jabatan on  m_jabatan.m_jabatan_id = p_karyawan_pekerjaan.m_jabatan_id
		left join m_lokasi on  m_lokasi.m_lokasi_id = p_karyawan_pekerjaan.m_lokasi_id
		 where t_permintaan_surat.active=1 and t_permintaan_surat_id=$id  ");

		$direktur=DB::connection()->select("select *, p_karyawan.nama as nama_direktur, m_jabatan.nama as nama_jabatan_direktur from m_jabatan_atasan 
		left join m_jabatan on m_jabatan_atasan.m_jabatan_id = m_jabatan.m_jabatan_id
		left join p_karyawan_pekerjaan on m_jabatan.m_jabatan_id = p_karyawan_pekerjaan.m_jabatan_id
		left join p_karyawan on p_karyawan.p_karyawan_id = p_karyawan_pekerjaan.p_karyawan_id
		
		where m_atasan_id = 0 and m_jabatan.m_lokasi_id = ".$surat[0]->m_lokasi_id);
$help = new Helper_function();

		$data = ['surat' => $surat,'help' => $help,'direktur' => $direktur];
        $pdf = PDF::loadView('backend.permintaan_surat.pdf_keterangan_kerja', $data);
        $dom_pdf = $pdf->getDomPDF();
		$canvas = $dom_pdf->get_canvas();	
		if($surat[0]->m_lokasi_id==3){
			
		$image1="dist/img/logo/kop/RAM.png";
		$canvas->image($image1,0, 0, 0, 840 , 800 );
		}else
		if($surat[0]->m_lokasi_id==4){
			
		$image1="dist/img/logo/kop/EMM.png";
		$canvas->image($image1,0, 0, 0, 840 , 800 );
		}else
		if($surat[0]->m_lokasi_id==2){
			
		$image1="dist/img/logo/kop/SJP.png";
		$canvas->image($image1,0, 0, 0, 840 , 800 );
		}else
		if($surat[0]->m_lokasi_id==26){
			
		$image1="dist/img/logo/kop/Digiform.png";
		$canvas->image($image1,0, 0, 0, 840 , 800 );
		}else
		if($surat[0]->m_lokasi_id==9){
			
		$image1="dist/img/logo/kop/ASA.png";
		$canvas->image($image1,400, 25, 0, 100 , 800 );
		}else
		if($surat[0]->m_lokasi_id==7){
			
		$image1="dist/img/logo/kop/YSF.png";
		$canvas->image($image1,130, 20, 0, 70 , 800 );
		$canvas->page_text(70, 90, "Alamat : Jl, Kurdi Bar. III No.Kavling 1, Pelindung Hewan, Kec. Astanaanyar, Kota Bandung, Jawa Barat 40243, ", null, 10, array(0, 0, 0));
			$canvas->page_text(235, 105, "No. Telp : (022) 5209390", null, 10, array(0, 0, 0));
		}else
		if($surat[0]->m_lokasi_id==5){
			
		$image1="dist/img/logo/kop/CC.png";
		$canvas->image($image1,50, 50, 0, 40 , 800 );
		
		
			$image1="dist/img/logo/kop/CC Footer.png";
			$canvas->image($image1,0, 840, 600, 0 , 800 );
		}else
		if($surat[0]->m_lokasi_id==13){
			
			$image1="dist/img/logo/kop/Mafaza.png";
			$canvas->image($image1,380, 25, 0, 50 , 800 );
		
			$image1="dist/img/logo/kop/Mafaza Bottom.png";
			$canvas->image($image1,70, 750, 450, 10 , 800 );
			
			$canvas->page_text(70, 760, "Kantor:", null, 10, array(0, 0, 0));
			$canvas->page_text(70, 770, "Kurdi Barat III, Kavling 1. Kelurahan Pelindung Hewan Kecamatan Astana Anyar Kota Bandung, Jawa Barat", null, 10, array(0, 0, 0));
			$canvas->page_text(70, 780, "40243.", null, 10, array(0, 0, 0));
			

 
		}else
		if($surat[0]->m_lokasi_id==6){
			
			$image1="dist/img/logo/kop/JKA 1 Top.png";
			$canvas->image($image1,0, 0, 600, 80 , 800 );
			
			$image1="dist/img/logo/kop/JKA.png";
			$canvas->image($image1,105, 50, 0, 80 , 800 );
			
			$image1="dist/img/logo/kop/JKA 2 TOP.png";
			$canvas->image($image1,175, 80, 0, 40 , 800 );
			$image1="dist/img/logo/kop/JKA Bottom.png";
			$canvas->image($image1,0, 780, 600, 40 , 800 );
		}

		return $pdf->download("keterangan.pdf");

		
	}
	public function tambah_permintaan_surat()
	{

		$id = '';
		$type = 'simpan_permintaan_surat';
		$data['nama']='';
		$data['link']='';
		$data['keterangan']='';
		$data['status']='';
		$data['p_karyawan_id']=array();

		$karyawan=DB::connection()->select("select * from p_karyawan where active=1 order by nama ");



		return view('backend.permintaan_surat.tambah_permintaan_surat',compact('id','data','type','karyawan'));
	}public function simpan_permintaan_surat(Request $request)
	{
		
		DB::connection()->table("t_permintaan_surat")
		->insert([
			"p_karyawan_pengaju" => $request->get("karyawan"),

			"jenis_surat" => $request->get("jenis_surat"),
			"keterangan_surat" => $request->get("keterangan_surat")

		]);


		return redirect()->route('fe.permintaan_surat')->with('success',' kotak_laporan Berhasil di input!');
	}public function update_permintaan_surat (Request $request, $id)
	{
		
		DB::connection()->table("t_permintaan_surat")
		->where("t_permintaan_surat_id",$id)
		->update([
			"p_karyawan_pengaju" => $request->get("karyawan"),

			"status" => $request->get("status"),
			"jenis_surat" => $request->get("jenis_surat"),
			"keterangan_surat" => $request->get("keterangan_surat")

		]);


		return redirect()->route('fe.permintaan_surat')->with('success',' kotak_laporan Berhasil di input!');
	}public function hapus_permintaan_surat (Request $request, $id)
	{
		
		DB::connection()->table("t_permintaan_surat")
		->where("t_permintaan_surat_id",$id)
		->update([
			"active" => 0,


		]);


		return redirect()->route('fe.permintaan_surat')->with('success',' kotak_laporan Berhasil di input!');
	}
}
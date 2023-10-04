<?php

namespace App\Http\Controllers\Frontend;
use App\Http\Controllers\Controller;

use App\rekaplembur_xls;
use App\User;
use App\Helper_function;
use Illuminate\Http\Request;
use DB;
use Auth;
use PhpOffice\PhpSpreadsheet\Reader\Xml\Style\Fill;
use Response;

class permintaan_suratController extends Controller
{
	public function permintaan_surat(Request $request)
	{
		$iduser=Auth::user()->id;
		$sqlidkar="select * from p_karyawan where user_id=$iduser";
		$idkar=DB::connection()->select($sqlidkar);
		$id=$idkar[0]->p_karyawan_id;

		$sqlfasilitas="SELECT * FROM t_permintaan_surat
		
		WHERE 1=1  and p_karyawan_pengaju = $id and active=1 ";
		$permintaan_surat=DB::connection()->select($sqlfasilitas);

		return view('frontend.permintaan_surat.permintaan_surat',compact('permintaan_surat'));
	}
	public function edit_permintaan_surat($id)
	{
		$type = 'update_permintaan_surat';
		$surat=DB::connection()->select("select * from t_permintaan_surat where active=1 and t_permintaan_surat_id=$id  ");
		$data['jenis_surat']=$surat[0]->jenis_surat;
		$data['keterangan_surat']=$surat[0]->keterangan_surat;

		return view('frontend.permintaan_surat.tambah_permintaan_surat',compact('id','data','type'));
	}
	public function tambah_permintaan_surat()
	{

		$id = '';
		$type = 'simpan_permintaan_surat';
		$data['nama']='';
		$data['link']='';
		$data['keterangan']='';
		$data['jenis_surat']='';
		$data['keterangan_surat']='';
		$data['p_karyawan_id']=array();

		$karyawan=DB::connection()->select("select * from p_karyawan where active=1 order by nama ");



		return view('frontend.permintaan_surat.tambah_permintaan_surat',compact('id','data','type','karyawan'));
	}public function simpan_permintaan_surat(Request $request)
	{
		$iduser=Auth::user()->id;
		$sqlidkar="select * from p_karyawan left  join p_karyawan_pekerjaan on p_karyawan.p_karyawan_id = p_karyawan_pekerjaan.p_karyawan_id where user_id=$iduser";
		$idkar=DB::connection()->select($sqlidkar);
		$id=$idkar[0]->p_karyawan_id;
		DB::connection()->table("t_permintaan_surat")
			->insert([
			"p_karyawan_pengaju" => $id,

			"jenis_surat" => $request->get("jenis_surat"),
			"keterangan_surat" => $request->get("keterangan_surat")

			]);


		return redirect()->route('fe.permintaan_surat')->with('success',' kotak_laporan Berhasil di input!');
	}public function update_permintaan_surat (Request $request,$id)
	{
		$iduser=Auth::user()->id;
		$sqlidkar="select * from p_karyawan left  join p_karyawan_pekerjaan on p_karyawan.p_karyawan_id = p_karyawan_pekerjaan.p_karyawan_id where user_id=$iduser";
		$idkar=DB::connection()->select($sqlidkar);
		$idk=$idkar[0]->p_karyawan_id;
		DB::connection()->table("t_permintaan_surat")
		->where("t_permintaan_surat_id",$id)
			->update([
			"p_karyawan_pengaju" => $idk,

			"jenis_surat" => $request->get("jenis_surat"),
			"keterangan_surat" => $request->get("keterangan_surat")

			]);


		return redirect()->route('fe.permintaan_surat')->with('success',' kotak_laporan Berhasil di input!');
	}public function hapus_permintaan_surat (Request $request, $id)
	{
		$iduser=Auth::user()->id;
		$sqlidkar="select * from p_karyawan left  join p_karyawan_pekerjaan on p_karyawan.p_karyawan_id = p_karyawan_pekerjaan.p_karyawan_id where user_id=$iduser";
		$idkar=DB::connection()->select($sqlidkar);
		$idk=$idkar[0]->p_karyawan_id;
		DB::connection()->table("t_permintaan_surat")
		->where("t_permintaan_surat_id",$id)
			->update([
			"active" => 0,


			]);


		return redirect()->route('fe.permintaan_surat')->with('success',' kotak_laporan Berhasil di input!');
	}
	
	
}
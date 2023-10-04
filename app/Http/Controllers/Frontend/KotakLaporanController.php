<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Auth;

class KotaklaporanController extends Controller
{
	public function __construct()
	{
		$this->middleware('auth');
	}
	public function kotak_laporan()
	{
		$iduser=Auth::user()->id;
		$sqlidkar="select * from p_karyawan left  join p_karyawan_pekerjaan on p_karyawan.p_karyawan_id = p_karyawan_pekerjaan.p_karyawan_id where user_id=$iduser";
		$idkar=DB::connection()->select($sqlidkar);
		$id=$idkar[0]->p_karyawan_id;
		$sqlkotak_laporan="SELECT * from t_kotak_laporan
                WHERE 1=1 and active = 1 and p_karyawan_pengadu=$id";
		$Kotak_laporan=DB::connection()->select($sqlkotak_laporan);
		return view('frontend.kotak_laporan.kotak_laporan',compact('Kotak_laporan'));
	}

	public function tambah_kotak_laporan()
	{

		$id = '';
		$type = 'simpan_kotak_laporan';
		$data['nama']='';
		$data['link']='';
		$data['keterangan']='';
		$data['p_karyawan_id']=array();

		$karyawan=DB::connection()->select("select * from p_karyawan where active=1 order by nama ");
		

		
		return view('frontend.kotak_laporan.tambah_kotak_laporan',compact('id','data','type','karyawan'));
	}

	public function baca_kotak_laporan (Request $request, $id)
	{
		
		$sqlfasilitas="SELECT * FROM t_kotak_laporan
		WHERE 1=1  and t_kotak_laporan.t_kotak_laporan_id = $id  ";
		$Kotak_laporan=DB::connection()->select($sqlfasilitas);
		return view('frontend.kotak_laporan.baca_kotak_laporan',compact('Kotak_laporan'));
		
	}

	public function simpan_kotak_laporan(Request $request)
	{
		$iduser=Auth::user()->id;
		$sqlidkar="select * from p_karyawan left  join p_karyawan_pekerjaan on p_karyawan.p_karyawan_id = p_karyawan_pekerjaan.p_karyawan_id where user_id=$iduser";
		$idkar=DB::connection()->select($sqlidkar);
		$id=$idkar[0]->p_karyawan_id;
		DB::connection()->table("t_kotak_laporan")
		->insert([
			"p_karyawan_dilaporkan" => $request->get("p_karyawan_id"),
			"p_karyawan_pengadu" => $id,
			"laporan" => $request->get("penjelasan"),
			"tgl_kejadian" => $request->get("tgl"),
			"judul_laporan" => $request->get("judul"),

		]);
		

		return redirect()->route('fe.kotak_laporan')->with('success',' kotak_laporan Berhasil di input!');
	}

	public function edit_kotak_laporan($id)
	{


		$type = 'update_kotak_laporan';
		$sqlkotak_laporan="SELECT * FROM t_kotak_laporan WHERE active=1 and t_kotak_laporan_id = $id  ";
		$kotak_laporan=DB::connection()->select($sqlkotak_laporan);
		$data['nama']=$kotak_laporan[0]->nama_kotak_laporan;$sql="SELECT * from m_lokasi

                WHERE 1=1  and active = 1 ";
		$lokasi=DB::connection()->select($sql);
		$data['keterangan']=$kotak_laporan[0]->keterangan;

		return view('frontend.kotak_laporan.tambah_kotak_laporan', compact('data','id','type'));
	}

	public function update_kotak_laporan(Request $request, $id)
	{
		$idUser=Auth::user()->id;
		DB::connection()->table("t_kotak_laporan")
		->where("t_kotak_laporan_id",$id)
		->update([
			"nama_kotak_laporan" => $request->get("nama"),
			"link" => $request->get("link"),
			"keterangan" => $request->get("keterangan"),
		]);

		return redirect()->route('be.kotak_laporan')->with('success',' kotak_laporan Berhasil di Ubah!');
	}

	public function hapus_kotak_laporan($id)
	{
		$idUser=Auth::user()->id;
		DB::connection()->table("t_kotak_laporan")
		->where("t_kotak_laporan_id",$id)
		->update([
			"active"=>0,

		]);

		return redirect()->back()->with('success',' kotak_laporan Berhasil di Hapus!');
	}
}

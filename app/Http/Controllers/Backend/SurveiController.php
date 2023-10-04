<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Auth;

class SurveiController extends Controller
{
	public function __construct()
    {
        $this->middleware('auth');
    }public function survei()
	{

		$sqlsurvei="SELECT * from t_survei
                WHERE 1=1 and active = 1 ";
		$Survei=DB::connection()->select($sqlsurvei);
		return view('backend.survei.survei',compact('Survei'));
	}

	public function tambah_survei()
	{
		
		$id = '';
		$type = 'simpan_survei';
		$data['nama']='';
		$data['link']='';
		$data['keterangan']='';
		$data['p_karyawan_id']=array();
		
		$karyawan=DB::connection()->select("select * from p_karyawan where active=1 order by nama ");

		return view('backend.survei.tambah_survei',compact('id','data','type','karyawan'));
	}

	public function simpan_survei(Request $request)
	{
		$Survei=DB::connection()->select("select max(t_survei_id) from t_survei");
		
		DB::connection()->table("t_survei")
		->insert([
			"t_survei_id" => $Survei[0]->max+1,
			"nama_survei" => $request->get("nama"),
			"link" => $request->get("link"),
			"keterangan" => $request->get("keterangan"),

		]);
		$karyawan = $request->get("p_karyawan_id");
		for($i = 0; $i < count($karyawan); $i++){
			DB::connection()->table("t_survei_karyawan")
			->insert([
				"p_karyawan_id" => $karyawan[$i],
				"t_survei_id" => $Survei[0]->max+1,
			]);
		}

		return redirect()->route('be.survei')->with('success',' survei Berhasil di input!');
	}

	public function edit_survei($id)
	{


		$type = 'update_survei';
		$sqlsurvei="SELECT * FROM t_survei WHERE active=1 and t_survei_id = $id  ";
		$survei=DB::connection()->select($sqlsurvei);
		$data['nama']=$survei[0]->nama_survei;$sql="SELECT * from m_lokasi

                WHERE 1=1  and active = 1 ";
		$lokasi=DB::connection()->select($sql); 
		$data['keterangan']=$survei[0]->keterangan;

		return view('backend.survei.tambah_survei', compact('data','id','type'));
	}

	public function update_survei(Request $request, $id)
	{
		$idUser=Auth::user()->id;
		DB::connection()->table("t_survei")
		->where("t_survei_id",$id)
		->update([
			"nama_survei" => $request->get("nama"),
			"link" => $request->get("link"),
			"keterangan" => $request->get("keterangan"),
		]);

		return redirect()->route('be.survei')->with('success',' survei Berhasil di Ubah!');
	}

	public function hapus_survei($id)
	{
		$idUser=Auth::user()->id;
		DB::connection()->table("t_survei")
		->where("t_survei_id",$id)
		->update([
			"active"=>0,

		]);

		return redirect()->back()->with('success',' survei Berhasil di Hapus!');
	}
}

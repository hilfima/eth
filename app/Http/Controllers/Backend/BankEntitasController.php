<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Auth;

class BankEntitasController extends Controller
{
	 public function __construct()
    {
        $this->middleware('auth');
    }public function bank_entitas()
	{

		$sqlbank_entitas="SELECT * from m_bank_entitas
        		join m_lokasi on m_lokasi.m_lokasi_id = m_bank_entitas.m_lokasi_id
                WHERE 1=1  and m_bank_entitas.active=1";
		$bank_entitas=DB::connection()->select($sqlbank_entitas);

		return view('backend.bank_entitas.bank_entitas',compact('bank_entitas'));
	}

	public function tambah_bank_entitas()
	{
		$sqlentitas="SELECT * from m_lokasi
                WHERE 1=1 ";
		$entitas=DB::connection()->select($sqlentitas);
		$id = '';
		$type = 'simpan_bank_entitas';
		$data['nama']='';
		$data['status']='';
		$data['m_lokasi_id']='';
		return view('backend.bank_entitas.tambah_bank_entitas',compact('id','data','type','entitas'));
	}

	public function simpan_bank_entitas(Request $request)
	{

		// echo $kode;die;
		DB::connection()->table("m_bank_entitas")
		->insert([
			"nama_bank_entitas" => $request->get("nama"),
			"m_lokasi_id" => $request->get("m_lokasi_id"),
			"status" => $request->get("status"),

		]);

		return redirect()->route('be.bank_entitas')->with('success',' bank_entitas Berhasil di input!');
	}

	public function edit_bank_entitas($id)
	{
		$sqlentitas="SELECT * from m_lokasi
                WHERE 1=1 ";
		$entitas=DB::connection()->select($sqlentitas);

		$type = 'update_bank_entitas';
		$sqlbank_entitas="SELECT * FROM m_bank_entitas WHERE  m_bank_entitas_id = $id  ";
		$bank_entitas=DB::connection()->select($sqlbank_entitas);
		$data['nama']=$bank_entitas[0]->nama_bank_entitas;
		$data['m_lokasi_id']=$bank_entitas[0]->m_lokasi_id;
		$data['status']=$bank_entitas[0]->status;

		return view('backend.bank_entitas.tambah_bank_entitas', compact('data','id','type','entitas'));
	}

	public function update_bank_entitas(Request $request, $id)
	{
		$idUser=Auth::user()->id;
		DB::connection()->table("m_bank_entitas")
		->where("m_bank_entitas_id",$id)
		->update([
			"nama_bank_entitas" => $request->get("nama"),
			"m_lokasi_id" => $request->get("m_lokasi_id"),
			"status" => $request->get("status"),
		]);

		return redirect()->route('be.bank_entitas')->with('success',' bank_entitas Berhasil di Ubah!');
	}

	public function hapus_bank_entitas($id)
	{
		$idUser=Auth::user()->id;
		DB::connection()->table("m_bank_entitas")
		->where("m_bank_entitas_id",$id)
		->update([
			"active"=>0,

		]);

		return redirect()->back()->with('success',' bank_entitas Berhasil di Hapus!');
	}
}

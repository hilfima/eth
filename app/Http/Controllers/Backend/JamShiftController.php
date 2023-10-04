<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Auth;

class jamshiftController extends Controller
{
	public function __construct()
    {
        $this->middleware('auth');
    }public function jamshift()
	{

		$sqljamshift="SELECT * from m_jam_shift
                WHERE 1=1 and active = 1 ";
		$jamshift=DB::connection()->select($sqljamshift);
		$entitas = DB::connection()->select("select * from m_lokasi where active=1 and sub_entitas=0 order by nama");
		$lokasi = array();
		foreach($entitas as $entitas){
		    $lokasi[$entitas->m_lokasi_id] = $entitas->nama;
		}
		return view('backend.jamshift.jamshift',compact('jamshift','lokasi'));
	}

	public function tambah_jamshift()
	{
		
		$id = '';
		$type = 'simpan_jamshift';
		$data['nama_jam_shift']='';
		$data['tgl_awal']='';
		$data['tgl_akhir']='';
		$data['jam_masuk']='';
		$data['jam_keluar']='';
		$data['keterangan']='';
		$entitas = DB::connection()->select("select * from m_lokasi where active=1 and sub_entitas=0 order by nama");
		return view('backend.jamshift.tambah_jamshift',compact('id','data','type','entitas'));
	}

	public function simpan_jamshift(Request $request)
	{
        $entitas = $request->entitas;
        $list_entitas = '';
        for($i=0;$i<count($entitas);$i++){
            $list_entitas .= $entitas[$i].',';
        }
        if($list_entitas)
            $list_entitas .= '-1';
		// echo $kode;die;
		DB::connection()->table("m_jam_shift")
		->insert([
			"nama_jam_shift" => $request->get("nama"),
			"jam_masuk" => $request->get("jam_masuk"),
			"jam_keluar" => $request->get("jam_keluar"),
			"tgl_awal" => $request->get("tgl_awal"),
			"tgl_akhir" => $request->get("tgl_akhir"),
            "entitas"=> $list_entitas
 		]);

		return redirect()->route('be.jamshift')->with('success',' jamshift Berhasil di input!');
	}

	public function edit_jamshift($id)
	{


		$type = 'update_jamshift';
		$sqljamshift="SELECT * FROM m_jam_shift WHERE active=1 and m_jam_shift_id = $id  ";
		$jamshift=DB::connection()->select($sqljamshift);

		$data['nama_jam_shift']=$jamshift[0]->nama_jam_shift;
		$data['tgl_awal']=$jamshift[0]->tgl_awal;
		$data['tgl_akhir']=$jamshift[0]->tgl_akhir;
		$data['jam_masuk']=$jamshift[0]->jam_masuk;
		$data['jam_keluar']=$jamshift[0]->jam_keluar;
		$data['keterangan']=$jamshift[0]->keterangan;
		
		$entitas = DB::connection()->select("select * from m_lokasi where active=1 and sub_entitas=0 order by nama");

		return view('backend.jamshift.tambah_jamshift', compact('data','id','type','entitas'));
	}

	public function update_jamshift(Request $request, $id)
	{
		$idUser=Auth::user()->id;
		 $entitas = $request->entitas;
        $list_entitas = '';
        for($i=0;$i<count($entitas);$i++){
            $list_entitas .= $entitas[$i].',';
        }
        if($list_entitas)
            $list_entitas .= '-1';
		DB::connection()->table("m_jam_shift")
		->where("m_jam_shift_id",$id)
		->update([
			"nama_jam_shift" => $request->get("nama"),
			"keterangan" => $request->get("keterangan"),
            "entitas"=> $list_entitas,
            "jam_masuk" => $request->get("jam_masuk"),
			"jam_keluar" => $request->get("jam_keluar"),
			"tgl_awal" => $request->get("tgl_awal"),
			"tgl_akhir" => $request->get("tgl_akhir"),
		]);

		return redirect()->route('be.jamshift')->with('success',' jamshift Berhasil di Ubah!');
	}

	public function hapus_jamshift($id)
	{
		$idUser=Auth::user()->id;
		DB::connection()->table("m_jam_shift")
		->where("m_jam_shift_id",$id)
		->update([
			"active"=>0,

		]);

		return redirect()->back()->with('success',' jamshift Berhasil di Hapus!');
	}
}

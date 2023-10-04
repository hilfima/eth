<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Auth;

class Pengajuan_pelatihanController extends Controller
{
	public function __construct()
    {
        $this->middleware('auth');
    }public function pengajuan_pelatihan()
	{
		
		$sqlpengajuan_pelatihan="SELECT * from t_agenda_perusahaan
                WHERE 1=1 and active = 1 and type='pengajuan_pelatihan' ";
		$pengajuan_pelatihan=DB::connection()->select($sqlpengajuan_pelatihan);
		return view('Backend.pengajuan_pelatihan.pengajuan_pelatihan',compact('pengajuan_pelatihan'));
	}

	public function tambah_pengajuan_pelatihan()
	{

		$id = '';
		$type = 'simpan_pengajuan_pelatihan';
		$data['nama']='';
		$data['tgl_awal']='';
		$data['tgl_akhir']='';
		$data['jam_mulai']='';
		$data['jam_selesai']='';
		$data['deskripsi']='';

		$karyawan=DB::connection()->select("select * from p_karyawan where active=1 order by nama ");



		return view('Backend.pengajuan_pelatihan.tambah_pengajuan_pelatihan',compact('id','data','type','karyawan'));
	}

	public function baca_pengajuan_pelatihan (Request $request, $id)
	{

		$sqlfasilitas="SELECT * FROM t_agenda_perusahaan
		WHERE 1=1  and t_agenda_perusahaan.t_agenda_perusahaan_id = $id  ";
		$pengajuan_pelatihan=DB::connection()->select($sqlfasilitas);
		return view('Backend.pengajuan_pelatihan.baca_pengajuan_pelatihan',compact('pengajuan_pelatihan'));
	}

	public function simpan_pengajuan_pelatihan(Request $request)
	{
		$iduser=Auth::user()->id;
		$sqlidkar="select * from p_karyawan left  join p_karyawan_pekerjaan on p_karyawan.p_karyawan_id = p_karyawan_pekerjaan.p_karyawan_id where user_id=$iduser";
		$idkar=DB::connection()->select($sqlidkar);
		$id=$idkar[0]->p_karyawan_id;
		DB::connection()->table("t_agenda_perusahaan")
		->insert([
			"nama_agenda" => $request->get("nama"),
			"tgl_awal" => $request->get("tgl_awal"),
			"tgl_akhir" => $request->get("tgl_akhir"),
			"waktu_mulai" => $request->get("jam_mulai"),
			"waktu_selesai" => $request->get("jam_selesai"),
			"deskripsi" => $request->get("deskripsi"),
			"lokasi" => $request->get("lokasi"),
			"cp" => $request->get("cp"),
			"p_karyawan_pengaju" => $id,
			"status" => 2,
			"type" => 'pelatihan',

		]);


		return redirect()->route('be.pengajuan_pelatihan')->with('success',' Pengajuan Pelatihan Berhasil di input!');
	}

	public function edit_pengajuan_pelatihan($id)
	{


		$type = 'update_pengajuan_pelatihan';
		$sqlpengajuan_pelatihan="SELECT * FROM t_agenda_perusahaan WHERE active=1 and t_agenda_perusahaan_id = $id  ";
		$agenda_perusahaan=DB::connection()->select($sqlpengajuan_pelatihan);
		$data['nama']=$agenda_perusahaan[0]->nama_agenda;
		$data['nama']=$agenda_perusahaan[0]->nama_agenda;
		$data['tgl_awal']=$agenda_perusahaan[0]->tgl_awal;
		$data['tgl_akhir']=$agenda_perusahaan[0]->tgl_akhir;
		$data['jam_mulai']=$agenda_perusahaan[0]->waktu_mulai;
		$data['jam_selesai']=$agenda_perusahaan[0]->waktu_selesai;
		$data['deskripsi']=$agenda_perusahaan[0]->deskripsi;
		$data['cp']=$agenda_perusahaan[0]->cp;
		$data['lokasi']=$agenda_perusahaan[0]->lokasi;
		$data['status']=$agenda_perusahaan[0]->status;
		return view('Backend.pengajuan_pelatihan.tambah_pengajuan_pelatihan', compact('data','id','type'));
	}

	public function update_pengajuan_pelatihan(Request $request, $id)
	{
		$idUser=Auth::user()->id;
		DB::connection()->table("t_agenda_perusahaan")
		->where("t_agenda_perusahaan_id",$id)
		->update([
			"nama_agenda" => $request->get("nama"),
			"tgl_awal" => $request->get("tgl_awal"),
			"tgl_akhir" => $request->get("tgl_akhir"),
			"waktu_mulai" => $request->get("jam_mulai"),
			"waktu_selesai" => $request->get("jam_selesai"),
			"deskripsi" => $request->get("deskripsi"),
			"lokasi" => $request->get("lokasi"),
			"status" => $request->get("status"),
			"cp" => $request->get("cp"),
		]);

		return redirect()->route('be.pengajuan_pelatihan')->with('success',' Pengajuan Pelatihan Berhasil di Ubah!');
	}

	public function hapus_pengajuan_pelatihan($id)
	{
		$idUser=Auth::user()->id;
		DB::connection()->table("t_agenda_perusahaan")
		->where("t_agenda_perusahaan_id",$id)
		->update([
			"active"=>0,

		]);

		return redirect()->back()->with('success',' Pengajuan Pelatihan Berhasil di Hapus!');
	}
}

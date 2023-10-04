<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Auth;
use App\Helper_function;

class SanksiController extends Controller
{
	public function __construct()
    {
        $this->middleware('auth');
    }public function sanksi()
	{
		$help = new Helper_function();
		$sqlsanksi="SELECT * from p_karyawan_sanksi
				left join p_karyawan on p_karyawan_sanksi.p_karyawan_id = p_karyawan.p_karyawan_id
				left join m_jenis_sanksi on p_karyawan_sanksi.m_jenis_sanksi_id = m_jenis_sanksi.m_jenis_sanksi_id
				WHERE 1=1 and p_karyawan_sanksi.active = 1 ";
		$Sanksi=DB::connection()->select($sqlsanksi);
		return view('backend.sanksi.sanksi',compact('Sanksi','help'));
	}

	public function tambah_sanksi()
	{
		$type = 'simpan_sanksi';
		$id = '';
		$data['tgl_awal'] = '';;
		$data['tgl_akhir'] = '';;
		$data['p_karyawan_id'] = '';;
		$data['alasan_sanksi'] = '';;
		$data['m_jenis_sanksi_id'] = '';;
		$data['tgl'] = '';;
		$karyawan = DB::connection()->select("select * from p_karyawan where active=1 order by nama");
		$sanksi = DB::connection()->select("select * from m_jenis_sanksi where active=1 order by nama_sanksi");
		return view('backend.sanksi.tambah_sanksi',compact('id','type','data','karyawan','sanksi'));
	}

	public function simpan_sanksi(Request $request)
	{

		// echo $kode;die;
		DB::connection()->table("p_karyawan_sanksi")
		->insert([
			"tgl_awal_sanksi" => $request->get("tgl_awal"),
			"tgl_akhir_sanksi" => $request->get("tgl_akhir"),
			"alasan_sanksi" => $request->get("alasan_sanksi"),
			"m_jenis_sanksi_id" => $request->get("sanksi"),
			"p_karyawan_id" => $request->get("p_karyawan_id"),

		]);

		return redirect()->route('be.sanksi_karyawan')->with('success',' sanksi Berhasil di input!');
	}

	public function edit_sanksi($id)
	{


		$sqlsanksi="SELECT * FROM p_karyawan_sanksi WHERE active=1 and p_karyawan_sanksi_id = $id  ";
		$sanksi=DB::connection()->select($sqlsanksi);
		$data['tgl_awal'] = $sanksi[0]->tgl_awal_sanksi;;
		$data['tgl_akhir'] = $sanksi[0]->tgl_akhir_sanksi;;
		$data['alasan_sanksi'] = $sanksi[0]->alasan_sanksi;;
		$data['m_jenis_sanksi_id'] = $sanksi[0]->m_jenis_sanksi_id;;
		$data['p_karyawan_id'] = $sanksi[0]->p_karyawan_id;;
		$karyawan = $Sanksi=DB::connection()->select("select * from p_karyawan where active=1 order by nama");
		$type = 'update_sanksi';
		$sanksi = DB::connection()->select("select * from m_jenis_sanksi where active=1 order by nama_sanksi");

		return view('backend.sanksi.tambah_sanksi',compact('id','type','data','karyawan','sanksi'));
	}

	public function update_sanksi(Request $request, $id)
	{
		$idUser=Auth::user()->id;
		DB::connection()->table("p_karyawan_sanksi")
		->where("p_karyawan_sanksi_id",$id)
		->update([
		"tgl_awal_sanksi" => $request->get("tgl_awal"),
		"tgl_akhir_sanksi" => $request->get("tgl_akhir"),
		"alasan_sanksi" => $request->get("alasan_sanksi"),
		"m_jenis_sanksi_id" => $request->get("sanksi"),
		"p_karyawan_id" => $request->get("p_karyawan_id"),
		]);

		return redirect()->route('be.sanksi_karyawan')->with('success',' sanksi Berhasil di Ubah!');
	}

	public function hapus_sanksi($id)
	{
		$idUser=Auth::user()->id;
		DB::connection()->table("p_karyawan_sanksi")
		->where("p_karyawan_sanksi_id",$id)
		->update([
			"active"=>0,

		]);

		return redirect()->back()->with('success',' sanksi Berhasil di Hapus!');
	}
}

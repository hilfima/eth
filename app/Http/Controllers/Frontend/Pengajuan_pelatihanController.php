<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Auth;

class Pengajuan_pelatihanController extends Controller
{
	public function __construct()
	{
		$this->middleware('auth');
	}
	public function pengajuan_pelatihan()
	{
		$iduser=Auth::user()->id;
		$sqlidkar="select * from p_karyawan left  join p_karyawan_pekerjaan on p_karyawan.p_karyawan_id = p_karyawan_pekerjaan.p_karyawan_id where user_id=$iduser";
		$idkar=DB::connection()->select($sqlidkar);
		$id=$idkar[0]->p_karyawan_id;
		$sqlpengajuan_pelatihan="SELECT * from t_agenda_perusahaan
                WHERE 1=1 and active = 1 and type='pengajuan_pelatihan'";
		$pengajuan_pelatihan=DB::connection()->select($sqlpengajuan_pelatihan);
		return view('frontend.pengajuan_pelatihan.pengajuan_pelatihan',compact('pengajuan_pelatihan'));
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
		$data['lokasi']='';
		$data['cp']='';
		$data['alasan_pengajuan']='';

		$karyawan=DB::connection()->select("select * from p_karyawan where active=1 order by nama ");

		$list_karyawan = array();

		return view('frontend.pengajuan_pelatihan.tambah_pengajuan_pelatihan',compact('id','data','type','karyawan','list_karyawan'));
	}

	public function lihat_agenda_perusahaan (Request $request, $id)
	{

		$sqlfasilitas="SELECT * FROM t_agenda_perusahaan
		WHERE 1=1  and t_agenda_perusahaan.t_agenda_perusahaan_id = $id  ";
		$agenda_perusahaan=DB::connection()->select($sqlfasilitas);
		$data['nama']=$agenda_perusahaan[0]->nama_agenda;
		$data['nama']=$agenda_perusahaan[0]->nama_agenda;
		$data['tgl_awal']=$agenda_perusahaan[0]->tgl_awal;
		$data['tgl_akhir']=$agenda_perusahaan[0]->tgl_akhir;
		$data['jam_mulai']=$agenda_perusahaan[0]->waktu_mulai;
		$data['jam_selesai']=$agenda_perusahaan[0]->waktu_selesai;
		$data['deskripsi']=$agenda_perusahaan[0]->deskripsi;
		$data['cp']=$agenda_perusahaan[0]->cp;
		$data['lokasi']=$agenda_perusahaan[0]->lokasi;
		$data['alasan_pengajuan']=$agenda_perusahaan[0]->alasan_pengajuan;
		$sqlfasilitas="SELECT * FROM t_agenda_perusahaan_karyawan
		join p_karyawan on p_karyawan.p_karyawan_id = t_agenda_perusahaan_karyawan.p_karyawan_id
		WHERE 1=1  and t_agenda_perusahaan_karyawan.t_agenda_perusahaan_id = $id  ";
		$agenda_karyawan=DB::connection()->select($sqlfasilitas);
		$list_karyawan = array();
		foreach($agenda_karyawan as $karyawan){
			$list_karyawan[] =$karyawan->p_karyawan_id;
			$nama[] =$karyawan->nama;
		}
		$type="baca_pengajuan_pelatihan";
		$karyawan=DB::connection()->select("select * from p_karyawan where active=1 order by nama ");
		return view('frontend.pengajuan_pelatihan.tambah_pengajuan_pelatihan',compact('agenda_perusahaan','data','type','id','nama','list_karyawan','karyawan'));
	}

	public function simpan_pengajuan_pelatihan(Request $request)
	{
		$iduser=Auth::user()->id;
		$sqlidkar="select * from p_karyawan left  join p_karyawan_pekerjaan on p_karyawan.p_karyawan_id = p_karyawan_pekerjaan.p_karyawan_id where user_id=$iduser";
		$idkar=DB::connection()->select($sqlidkar);
		$id=$idkar[0]->p_karyawan_id;
		$agenda=DB::connection()->select("select max(t_agenda_perusahaan_id) from t_agenda_perusahaan");

		DB::connection()->table("t_agenda_perusahaan")
		->insert([
		"t_agenda_perusahaan_id" => $agenda[0]->max+1,
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
		"type" => 'pengajuan_pelatihan',
		"alasan_pengajuan" => $request->get("alasan"),

		]);
		if ($request->file('brosur')) {
				//echo 'masuk';die;
				$file = $request->file('brosur');
				$destination="dist/img/file/";
				$path='brosur-'.date('YmdHis').$file->getClientOriginalName();
				$file->move($destination,$path);
				//echo $path;die;
				DB::connection()->table("t_agenda_perusahaan")->where("t_agenda_perusahaan_id",$agenda[0]->max+1)
				->update([
					"brosur"=>$path
				]);
			}
		$karyawan = $request->get("karyawan");
		for ($i = 0; $i < count($karyawan); $i++) {
			DB::connection()->table("t_agenda_perusahaan_karyawan")
			->insert([
				"p_karyawan_id" => $karyawan[$i],
				"t_agenda_perusahaan_id" => $agenda[0]->max+1,
			]);
		}

		return redirect()->route('fe.pengajuan_pelatihan')->with('success',' pengajuan_pelatihan Berhasil di input!');
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
		return view('frontend.pengajuan_pelatihan.tambah_pengajuan_pelatihan', compact('data','id','type'));
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
		"cp" => $request->get("cp"),
		]);

		return redirect()->route('fe.pengajuan_pelatihan')->with('success',' pengajuan_pelatihan Berhasil di Ubah!');
	}

	public function hapus_pengajuan_pelatihan($id)
	{
		$idUser=Auth::user()->id;
		DB::connection()->table("t_agenda_perusahaan")
		->where("t_agenda_perusahaan_id",$id)
		->update([
			"active"=>0,

		]);

		return redirect()->back()->with('success',' pengajuan_pelatihan Berhasil di Hapus!');
	}
}

<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Auth;

class jadwal_trainingController extends Controller
{
	public function __construct()
    {
        $this->middleware('auth');
    }public function jadwal_training()
	{
		
		$sqljadwal_training="SELECT * from t_agenda_perusahaan
                WHERE 1=1 and active = 1 and type='pelatihan' ";
		$jadwal_training=DB::connection()->select($sqljadwal_training);
		return view('backend.jadwal_training.jadwal_training',compact('jadwal_training'));
	}

	public function tambah_jadwal_training()
	{

		$id = '';
		$type = 'simpan_jadwal_training';
		$data['nama']='';
		$data['tgl_awal']='';
		$data['tgl_akhir']='';
		$data['jam_mulai']='';
		$data['jam_selesai']='';
		$data['deskripsi']='';
		$data['lokasi']='';
		$data['cp']='';

		$karyawan=DB::connection()->select("select * from p_karyawan where active=1 order by nama ");



		return view('backend.jadwal_training.tambah_jadwal_training',compact('id','data','type','karyawan'));
	}

	public function list_konfirmasi_jadwal_training (Request $request, $id)
	{

		$sqlfasilitas="SELECT *,p_karyawan.nama  FROM t_agenda_perusahaan_karyawan
		join p_karyawan on p_karyawan.p_karyawan_id = t_agenda_perusahaan_karyawan.p_karyawan_id
		WHERE 1=1  and t_agenda_perusahaan_karyawan.t_agenda_perusahaan_id = $id  ";
		$jadwal_training=DB::connection()->select($sqlfasilitas);
		$sql="Select (select count(*) from t_agenda_perusahaan_karyawan where t_agenda_perusahaan_karyawan.t_agenda_perusahaan_id = $id ) as total_karyawan, 
			        ( Select count(*)
			from t_agenda_perusahaan_karyawan 
			    where t_agenda_perusahaan_karyawan.t_agenda_perusahaan_id = $id and (konfirmasi_kehadiran is not null ) ) as total_hadir ";
			                        
			                        
		$total=DB::connection()->select($sql);
		return view('backend.jadwal_training.list_konfirmasi',compact('jadwal_training','id','total'));
	}
	 public function absen_kehadiran_agenda_hadir (Request $request, $id,$id_karyawan)
	{

		DB::beginTransaction();
		try {
			
			
			DB::connection()->table("t_agenda_perusahaan_karyawan")
			->where("t_agenda_perusahaan_karyawan_id",$id_karyawan)
			->update([
				"absensi_kehadiran"=>1,
			]);

			DB::commit();

			return redirect()->route('be.list_konfirmasi_jadwal_training',$id)->with('success','Absensi Kehadiran  Berhasil di rubah!');
		} catch (\Exeception $e) {
			DB::rollback();
			return redirect()->back()->with('error',$e);
		}
	} public function absen_kehadiran_agenda_tdkhadir (Request $request, $id,$id_karyawan)
	{

		DB::beginTransaction();
		try {
			
			
			DB::connection()->table("t_agenda_perusahaan_karyawan")
			->where("t_agenda_perusahaan_karyawan_id",$id_karyawan)
			->update([
				"absensi_kehadiran"=>2,
			]);

			DB::commit();

			return redirect()->route('be.list_konfirmasi_jadwal_training',$id)->with('success','Absensi Kehadiran  Berhasil di rubah!');
		} catch (\Exeception $e) {
			DB::rollback();
			return redirect()->back()->with('error',$e);
		}
	}
   
	public function baca_jadwal_training (Request $request, $id)
	{

		$sqlfasilitas="SELECT * FROM t_agenda_perusahaan
		WHERE 1=1  and t_agenda_perusahaan.t_agenda_perusahaan_id = $id  ";
		$jadwal_training=DB::connection()->select($sqlfasilitas);
		return view('backend.jadwal_training.baca_jadwal_training',compact('jadwal_training'));
	}

	public function simpan_jadwal_training(Request $request)
	{
		$iduser=Auth::user()->id;
		$sqlidkar="select * from p_karyawan left  join p_karyawan_pekerjaan on p_karyawan.p_karyawan_id = p_karyawan_pekerjaan.p_karyawan_id where user_id=$iduser";
		$idkar=DB::connection()->select($sqlidkar);
	
		$iduser=Auth::user()->id;
		$sqlidkar="select * from p_karyawan left  join p_karyawan_pekerjaan on p_karyawan.p_karyawan_id = p_karyawan_pekerjaan.p_karyawan_id where user_id=$iduser";
		$idkar=DB::connection()->select($sqlidkar);
	
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
		"p_karyawan_pengaju" => $iduser,
		"status" => 1,
		"type" => 'pelatihan',

		]);
		$karyawan = $request->get("karyawan");
		for ($i = 0; $i < count($karyawan); $i++) {
			DB::connection()->table("t_agenda_perusahaan_karyawan")
			->insert([
				"p_karyawan_id" => $karyawan[$i],
				"t_agenda_perusahaan_id" => $agenda[0]->max+1,
			]);
		}


		return redirect()->route('be.jadwal_training')->with('success',' Pengajuan Pelatihan Berhasil di input!');
	}

	public function edit_jadwal_training($id)
	{


		$type = 'update_jadwal_training';
		$sqljadwal_training="SELECT * FROM t_agenda_perusahaan WHERE active=1 and t_agenda_perusahaan_id = $id  ";
		$agenda_perusahaan=DB::connection()->select($sqljadwal_training);
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
		return view('backend.jadwal_training.tambah_jadwal_training', compact('data','id','type'));
	}

	public function update_jadwal_training(Request $request, $id)
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

		return redirect()->route('be.jadwal_training')->with('success',' Pengajuan Pelatihan Berhasil di Ubah!');
	}

	public function hapus_jadwal_training($id)
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

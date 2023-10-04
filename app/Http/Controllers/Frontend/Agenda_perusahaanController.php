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
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;

class Agenda_perusahaanController extends Controller
{
	public function __construct()
	{
		Session::put('backUrl', URL::full());
		$this->middleware('auth');
	}
	public function agenda_perusahaan(Request $request)
	{
		$iduser=Auth::user()->id;
		$sqlidkar="select * from p_karyawan where user_id=$iduser";
		$idkar=DB::connection()->select($sqlidkar);
		$id=$idkar[0]->p_karyawan_id;

		$sqlfasilitas="SELECT *,t_agenda_perusahaan.t_agenda_perusahaan_id as agen FROM t_agenda_perusahaan
		join t_agenda_perusahaan_karyawan on t_agenda_perusahaan.t_agenda_perusahaan_id = t_agenda_perusahaan_karyawan.t_agenda_perusahaan_id and p_karyawan_id = $id and t_agenda_perusahaan_karyawan.active=1
		WHERE 1=1 and type='agenda' and t_agenda_perusahaan.active=1 ORDER BY tgl_awal desc";
		$agenda_perusahaan=DB::connection()->select($sqlfasilitas);
		$help = new Helper_function();
		$type = 'agenda';
		return view('frontend.agenda_perusahaan.agenda_perusahaan',compact('agenda_perusahaan','help','type'));
	}public function qr_absen (Request $request, $id)
	{
		return view('frontend.agenda_perusahaan.qr_absen',compact('id'));
		
	}public function save_absen_form_acara ($id_agenda,$barcode)
	{
		$barcode = str_replace('%20',' ',$barcode);
		 $sql="SELECT * FROM t_agenda_perusahaan WHERE  barcode_acara = '$barcode' and t_agenda_perusahaan_id=$id_agenda";
		
		$agenda=DB::connection()->select($sql);
		$status = 0;
		$jam = '';
		$tanggal = '';
		$respons = '';
		if(!count($agenda)){
			$status =5 ; // karywan tidak terdaftar
		}else {
			$iduser=Auth::user()->id;
		$sqlidkar="select * from p_karyawan where user_id=$iduser";
		$idkar=DB::connection()->select($sqlidkar);
		$id_karyawan=$idkar[0]->p_karyawan_id;
			
		$sql="SELECT * FROM t_agenda_perusahaan_karyawan  a WHERE a.active=1 and t_agenda_perusahaan_id = $id_agenda and p_karyawan_id = $id_karyawan ";
		
		$agenda_karyawan=DB::connection()->select($sql);
		
		if(!$agenda_karyawan){
			$status = 2;
		}else if($agenda_karyawan[0]->absensi_kehadiran){
			$status = 3;
			$jam = $agenda_karyawan[0]->waktu_datang;
			$tanggal = $agenda_karyawan[0]->tanggal_datang;
		}else{
			$status = 1;
			DB::connection()->table("t_agenda_perusahaan_karyawan")
			->where("t_agenda_perusahaan_karyawan_id",$agenda_karyawan[0]->t_agenda_perusahaan_karyawan_id)
			->update([
				"waktu_datang" => date('H:i:s'),
				"tanggal_datang" => date('Y-m-d'),
				"absensi_kehadiran" =>1,
			
			]);
			
			$sql="SELECT * FROM t_agenda_perusahaan  a WHERE  t_agenda_perusahaan_id = $id_agenda ";
			$agenda=DB::connection()->select($sql);
			
			$respons = $agenda[0]->nama_agenda;
		}
		
	}
	$return['success']  = $status;
		$return['respons']  = $respons;
		$return['jam']  = $jam;
		$return['tanggal']  = $tanggal;
		return json_encode($return);
	}public function lihat_agenda_perusahaan (Request $request, $id)
	{
$iduser=Auth::user()->id;
		$sqlidkar="select * from p_karyawan where user_id=$iduser";
		$idkar=DB::connection()->select($sqlidkar);
		$idkaryawan=$idkar[0]->p_karyawan_id;

		$sqlfasilitas="SELECT *,t_agenda_perusahaan.deskripsi as des FROM t_agenda_perusahaan
		left join t_agenda_perusahaan_karyawan on t_agenda_perusahaan.t_agenda_perusahaan_id = t_agenda_perusahaan_karyawan.t_agenda_perusahaan_id
		WHERE 1=1  and t_agenda_perusahaan.t_agenda_perusahaan_id = $id and t_agenda_perusahaan_karyawan.p_karyawan_id = $idkaryawan ";
		$agenda_perusahaan=DB::connection()->select($sqlfasilitas);
		$data['nama']=$agenda_perusahaan[0]->nama_agenda;
		$data['nama']=$agenda_perusahaan[0]->nama_agenda;
		$data['tgl_awal']=$agenda_perusahaan[0]->tgl_awal;
		$data['tgl_akhir']=$agenda_perusahaan[0]->tgl_akhir;
		$data['jam_mulai']=$agenda_perusahaan[0]->waktu_mulai;
		$data['jam_selesai']=$agenda_perusahaan[0]->waktu_selesai;
		$data['deskripsi']=$agenda_perusahaan[0]->des;
		$data['cp']=$agenda_perusahaan[0]->cp;
		$data['brosur']=$agenda_perusahaan[0]->brosur;
		$data['lokasi']=$agenda_perusahaan[0]->lokasi;
		$data['alasan_pengajuan']=$agenda_perusahaan[0]->alasan_pengajuan;
		$data['t_agenda_perusahaan_id']=$agenda_perusahaan[0]->t_agenda_perusahaan_id;
		$data['konfirmasi_kehadiran']=$agenda_perusahaan[0]->konfirmasi_kehadiran ;
		$data['t_agenda_perusahaan_karyawan_id']=$agenda_perusahaan[0]->t_agenda_perusahaan_karyawan_id ;
		$sqlfasilitas="SELECT * FROM t_agenda_perusahaan_karyawan
		join p_karyawan on p_karyawan.p_karyawan_id = t_agenda_perusahaan_karyawan.p_karyawan_id
		WHERE 1=1  and t_agenda_perusahaan_karyawan.t_agenda_perusahaan_id = $id  ";
		$agenda_karyawan=DB::connection()->select($sqlfasilitas);
		$list_karyawan = array();
		$nama = array();
		foreach($agenda_karyawan as $karyawan){
			$list_karyawan[] =$karyawan->p_karyawan_id;
			$nama[] =$karyawan->nama;
		}
		$type="lihat_agenda_perusahaan";
		$karyawan=DB::connection()->select("select * from p_karyawan where active=1 order by nama ");
		return view('frontend.pengajuan_pelatihan.tambah_pengajuan_pelatihan',compact('agenda_perusahaan','data','type','id','nama','list_karyawan','karyawan'));
	}
	public function jadwal_training(Request $request)
	{
		$iduser=Auth::user()->id;
		$sqlidkar="select * from p_karyawan where user_id=$iduser";
		$idkar=DB::connection()->select($sqlidkar);
		$id=$idkar[0]->p_karyawan_id;

		$sqlfasilitas="SELECT * FROM t_agenda_perusahaan
		join t_agenda_perusahaan_karyawan on t_agenda_perusahaan.t_agenda_perusahaan_id = t_agenda_perusahaan_karyawan.t_agenda_perusahaan_id and p_karyawan_id = $id and t_agenda_perusahaan_karyawan.active=1
		WHERE 1=1 and type='pelatihan' and status=1 and t_agenda_perusahaan.active=1 
		
		ORDER BY tgl_awal desc";
		$agenda_perusahaan=DB::connection()->select($sqlfasilitas);
		$type = 'training';
		$help = new Helper_function();
		return view('frontend.agenda_perusahaan.jadwal_training',compact('agenda_perusahaan','help','type'));
	}public function absen_kehadiran_agenda_konfirmasi_hadir(Request $request,$id_karyawan)
	{

		DB::beginTransaction();
		try {
			
			
			DB::connection()->table("t_agenda_perusahaan_karyawan")
			->where("t_agenda_perusahaan_karyawan_id",$id_karyawan)
			->update([
				"konfirmasi_kehadiran"=>1,
			]);

			DB::commit();

			return redirect()->route('fe.agenda_perusahaan')->with('success','Absensi Kehadiran  Berhasil di rubah!');
		} catch (\Exeception $e) {
			DB::rollback();
			return redirect()->back()->with('error',$e);
		}
	} public function absen_kehadiran_agenda_konfirmasi_tdkhadir (Request $request,$id_karyawan)
	{

		DB::beginTransaction();
		try {
			
			
			DB::connection()->table("t_agenda_perusahaan_karyawan")
			->where("t_agenda_perusahaan_karyawan_id",$id_karyawan)
			->update([
				"konfirmasi_kehadiran"=>2,
			]);

			DB::commit();

			return redirect()->route('fe.agenda_perusahaan')->with('success','Absensi Kehadiran  Berhasil di rubah!');
		} catch (\Exeception $e) {
			DB::rollback();
			return redirect()->back()->with('error',$e);
		}
	}
}
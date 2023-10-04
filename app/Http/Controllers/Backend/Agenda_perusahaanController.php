<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Auth;
use App\Helper_function;

class Agenda_perusahaanController extends Controller
{
	
	public function agenda_perusahaan()
	{

		$sqlagenda_perusahaan="SELECT * from t_agenda_perusahaan
                WHERE 1=1 and active = 1 and type='agenda'";
		$agenda_perusahaan=DB::connection()->select($sqlagenda_perusahaan);
		return view('backend.agenda_perusahaan.agenda_perusahaan',compact('agenda_perusahaan'));
	}

	public function qr_absen_acara($id)
	{
		
		$sqlagenda_perusahaan="SELECT * from t_agenda_perusahaan
                WHERE 1=1 and active = 1 and type='agenda'";
		$agenda_perusahaan=DB::connection()->select($sqlagenda_perusahaan);
		$code = $agenda_perusahaan[0]->kode_acara;
		return view('backend.jadwal_training.qr_absen_acara',compact('id','code'));
	}public function livetimeQr($id)
	{
		$help = new Helper_function();
		$code = $help->random(10);
		DB::beginTransaction();
        try {
			DB::enableQueryLog();
           
			DB::connection()->table("t_agenda_perusahaan")
				->where('t_agenda_perusahaan_id',$id)
				->update([
					"barcode_acara" => $code
				]);
		
            DB::commit();
	
		echo '<img src="'.url('bower_components/qrcode/qrcode.php?s=qrh&d='.$code).'"  width="250px"><br>';
            
        } catch (\Exeception $e) {
            DB::rollback();
            return redirect()->back()->with('error', $e);
        }
	}

	public function tambah_karyawan_agenda($id)
	{
		$sql="SELECT * from p_karyawan WHERE 1=1  and active = 1 ";
		$karyawan=DB::connection()->select($sql);
		return view('backend.jadwal_training.tambah_karyawan_agenda',compact('id','karyawan'));
	}public function rekap_kehadiran($id)
	{
		$sql="SELECT * from t_agenda_perusahaan_karyawan 
		join p_karyawan on t_agenda_perusahaan_karyawan.p_karyawan_id = p_karyawan.p_karyawan_id
		WHERE 1=1  and t_agenda_perusahaan_karyawan.active = 1 and t_agenda_perusahaan_id=$id and absensi_kehadiran in (1,2)";
		$karyawan=DB::connection()->select($sql);
		
		$sql="Select (select count(*) from t_agenda_perusahaan_karyawan where t_agenda_perusahaan_karyawan.t_agenda_perusahaan_id = $id ) as total_karyawan, 
			        ( Select count(*)
			from t_agenda_perusahaan_karyawan 
			    where t_agenda_perusahaan_karyawan.t_agenda_perusahaan_id = $id and (absensi_kehadiran is not null ) ) as total_hadir ";
			                        
			                        
		$total=DB::connection()->select($sql);
		
		
		return view('backend.jadwal_training.rekap_kehadiran',compact('id','karyawan','total'));

	}
	public function tambah_agenda_perusahaan()
	{
		$sql="SELECT * from m_lokasi

                WHERE 1=1  and active = 1 ";
		$lokasi=DB::connection()->select($sql); 
		$id = '';
		$type = 'simpan_agenda_perusahaan';
		$data['nama']='';
		$data['tgl_awal']='';
		$data['tgl_akhir']='';
		$data['jam_mulai']='';
		$data['jam_selesai']='';
		$data['deskripsi']='';
		$data['lokasi']='';
		$data['cp']='';
		$data['brosur']='';
		
		$sql="SELECT * from p_karyawan WHERE 1=1  and active = 1 ";
		$karyawan=DB::connection()->select($sql);
		$list = array();
		
		return view('backend.agenda_perusahaan.tambah_agenda_perusahaan',compact('id','data','type','karyawan','list'));
	}

	public function save_tambah_karyawan_agenda(Request $request,$id)
	{
		 DB::beginTransaction();
        try {

          
			DB::enableQueryLog();
            
		// echo $kode;die;
		
		$karyawan = $request->get("karyawan");
		for ($i = 0; $i < count($karyawan); $i++) {
			DB::connection()->table("t_agenda_perusahaan_karyawan")
			->insert([
				"p_karyawan_id" => $karyawan[$i],
				"t_agenda_perusahaan_id" => $id,
			]);
		}
            DB::commit();

				$query = (DB::getQueryLog()); $help = new Helper_function();
				$help->historis(($_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']),json_encode($query));
		return redirect()->route('be.agenda_perusahaan')->with('success',' agenda_perusahaan Berhasil di input!');
            
        } catch (\Exeception $e) {
            DB::rollback();
            return redirect()->back()->with('error', $e);
        }
	}public function simpan_agenda_perusahaan(Request $request)
	{
		 DB::beginTransaction();
        try {

          
			DB::enableQueryLog();
            $help = new Helper_function();
		// echo $kode;die;
		  $path='';
		if ($request->file('brosur')) { //echo 'masuk';die;
					$file = $request->file('brosur');
					$destination = "dist/img/file/";
					$path = 'agenda_perusahaan-' . date('ymdhis') . '-' . $file->getClientOriginalName();
					$file->move($destination, $path);
					
					
					
		}
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
			"kode_acara" => $help->random(5),
			"type" => 'agenda',
			"brosur" => $path,
		]);
		$agenda_perusahaan=DB::connection()->select("select * from seq_t_agenda_perusahaan");
		$karyawan = $request->get("karyawan");
		for ($i = 0; $i < count($karyawan); $i++) {
			DB::connection()->table("t_agenda_perusahaan_karyawan")
			->insert([
				"p_karyawan_id" => $karyawan[$i],
				"t_agenda_perusahaan_id" => $agenda_perusahaan[0]->last_value,
			]);
		}
            DB::commit();

				$query = (DB::getQueryLog()); $help = new Helper_function();
				$help->historis(($_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']),json_encode($query));
		return redirect()->route('be.agenda_perusahaan')->with('success',' agenda_perusahaan Berhasil di input!');
            
        } catch (\Exeception $e) {
            DB::rollback();
            return redirect()->back()->with('error', $e);
        }
	}

	public function save_absen_from_web ()
	{
		echo 'halloooww';
	}

	public function save_absen ($id_agenda,$nik)
	{
		$sql="SELECT * FROM p_karyawan WHERE active=1 and nik = '$nik' ";
		$p_karyawan=DB::connection()->select($sql);
		$status = 0;
		$jam = '';
		$tanggal = '';
		$respons = '';
		if(!$p_karyawan){
			$status =4 ; // karywan tidak terdaftar
		}else {
			$id_karyawan = $p_karyawan[0]->p_karyawan_id;
		$sql="SELECT * FROM t_agenda_perusahaan_karyawan  a WHERE a.active=1 and t_agenda_perusahaan_id = $id_agenda and p_karyawan_id = $id_karyawan ";
		//echo ''.$sql;
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
		$return['success']  = $status;
		$return['respons']  = $respons;
		$return['jam']  = $jam;
		$return['tanggal']  = $tanggal;
		return json_encode($return);
	}
	}

	public function qr_absen ($id)
	{
		$sqlagenda_perusahaan="SELECT * FROM t_agenda_perusahaan WHERE active=1 and t_agenda_perusahaan_id = $id  ";
		$agenda_perusahaan=DB::connection()->select($sqlagenda_perusahaan);
		return view('backend.jadwal_training.qr_absen', compact('agenda_perusahaan','id'));
	
	}

	public function edit_agenda_perusahaan($id)
	{


		$type = 'update_agenda_perusahaan';
		$sqlagenda_perusahaan="SELECT * FROM t_agenda_perusahaan WHERE active=1 and t_agenda_perusahaan_id = $id  ";
		$agenda_perusahaan=DB::connection()->select($sqlagenda_perusahaan);
		$sqlagenda_perusahaan="SELECT * FROM t_agenda_perusahaan_karyawan WHERE active=1 and t_agenda_perusahaan_id = $id  ";
		$agenda_perusahaan_karyawan=DB::connection()->select($sqlagenda_perusahaan);
		$list = array();
		foreach($agenda_perusahaan_karyawan as $apk){
			$list[]  = $apk->p_karyawan_id;
		}
		$data['nama']=$agenda_perusahaan[0]->nama_agenda;
		$data['nama']=$agenda_perusahaan[0]->nama_agenda;
		$data['tgl_awal']=$agenda_perusahaan[0]->tgl_awal;
		$data['tgl_akhir']=$agenda_perusahaan[0]->tgl_akhir;
		$data['jam_mulai']=$agenda_perusahaan[0]->waktu_mulai;
		$data['jam_selesai']=$agenda_perusahaan[0]->waktu_selesai;
		$data['deskripsi']=$agenda_perusahaan[0]->deskripsi;
		$data['lokasi']=$agenda_perusahaan[0]->lokasi;
		$data['cp']=$agenda_perusahaan[0]->cp;
		$data['brosur']=$agenda_perusahaan[0]->brosur;
		$sql="SELECT * from p_karyawan WHERE 1=1  and active = 1 ";
		$karyawan=DB::connection()->select($sql);
		
		return view('backend.agenda_perusahaan.tambah_agenda_perusahaan', compact('data','id','type','karyawan','list'));
	}

	public function update_agenda_perusahaan(Request $request, $id)
	{
		$idUser=Auth::user()->id;
		$path="";
		if ($request->file('brosur')) { //echo 'masuk';die;
			$file = $request->file('brosur');
			$destination = "dist/img/file/";
			$path = 'agenda_perusahaan-' . date('ymdhis') . '-' . $file->getClientOriginalName();
			$file->move($destination, $path);
					
					
		}
		DB::connection()->table("t_agenda_perusahaan")
		->where("t_agenda_perusahaan_id",$id)
		->update([
		"nama_agenda" => $request->get("nama"),
		"tgl_awal" => $request->get("tgl_awal"),
		"tgl_akhir" => $request->get("tgl_akhir"),
		"waktu_mulai" => $request->get("jam_mulai"),
		"waktu_selesai" => $request->get("jam_selesai"),
		"deskripsi" => $request->get("deskripsi"),
		"brosur" => $path,
			
			"type" => 'agenda',
			
		]);

		return redirect()->route('be.agenda_perusahaan')->with('success',' Agenda perusahaan Berhasil di Ubah!');
	}

	public function hapus_agenda_perusahaan($id)
	{
		$idUser=Auth::user()->id;
		DB::connection()->table("t_agenda_perusahaan")
		->where("t_agenda_perusahaan_id",$id)
		->update([
			"active"=>0,

		]);

		return redirect()->back()->with('success',' agenda perusahaan Berhasil di Hapus!');
	}public function hapus_list_karyawan_agenda($id)
	{
		$idUser=Auth::user()->id;
		DB::connection()->table("t_agenda_perusahaan_karyawan")
		->where("t_agenda_perusahaan_karyawan_id",$id)
		->update([
			"active"=>0,

		]);

		return redirect()->back()->with('success',' Karyawan Berhasil di Hapus!');
	} public function absen_kehadiran_agenda_presensi_hadir (Request $request, $id,$id_karyawan)
	{

		DB::beginTransaction();
		try {
			
			
			DB::connection()->table("t_agenda_perusahaan_karyawan")
			->where("t_agenda_perusahaan_karyawan_id",$id_karyawan)
			->update([
				"konfirmasi_kehadiran"=>1,
			]);

			DB::commit();

			return redirect()->route('be.list_konfirmasi_jadwal_training',$id)->with('success','Absensi Kehadiran  Berhasil di rubah!');
		} catch (\Exeception $e) {
			DB::rollback();
			return redirect()->back()->with('error',$e);
		}
	} public function absen_kehadiran_agenda_presensi_tdkhadir (Request $request, $id,$id_karyawan)
	{

		DB::beginTransaction();
		try {
			
			
			DB::connection()->table("t_agenda_perusahaan_karyawan")
			->where("t_agenda_perusahaan_karyawan_id",$id_karyawan)
			->update([
				"konfirmasi_kehadiran"=>2,
			]);

			DB::commit();

			return redirect()->route('be.list_konfirmasi_jadwal_training',$id)->with('success','Absensi Kehadiran  Berhasil di rubah!');
		} catch (\Exeception $e) {
			DB::rollback();
			return redirect()->back()->with('error',$e);
		}
	}
    
}

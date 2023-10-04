<?php

namespace App\Http\Controllers;

use App\User;
use App\Helper_function;
use Illuminate\Http\Request;
use DB;
use Auth;
use Hash;

class RekruitmentController extends Controller
{

	public function form_biodata_kandidat()
	{
		$agama=DB::connection()->select("select * from m_agama where active = 1 order by m_agama_id");
		$m_kota=DB::connection()->select("select * from m_kota where active = 1 order by m_kota_id");
		$m_status=DB::connection()->select("select * from m_status where active = 1 order by nama desc");
		$m_pendidikan=DB::connection()->select("select * from m_pendidikan where active = 1 order by nama desc");
		return view('form_biodata_kandidat',compact('agama','m_kota','m_status','m_pendidikan'));
	}public function edit_form_biodata_kandidat($idRec)
	{
		$p_recruitment=DB::connection()->select("select * from p_recruitment where active = 1 and p_recruitment_id=$idRec");
		$p_recruitment_kursus=DB::connection()->select("select * from p_recruitment_kursus where active = 1 and p_recruitment_id=$idRec");
		$p_recruitment_pendidikan=DB::connection()->select("select * from p_recruitment_pendidikan where active = 1 and p_recruitment_id=$idRec");
		$p_recruitment_pekerjaan=DB::connection()->select("select * from p_recruitment_pekerjaan where active = 1 and p_recruitment_id=$idRec");
		$p_recruitment_penyakit=DB::connection()->select("select * from p_recruitment_penyakit where active = 1 and p_recruitment_id=$idRec");
		$agama=DB::connection()->select("select * from m_agama where active = 1 order by m_agama_id");
		$m_kota=DB::connection()->select("select * from m_kota where active = 1 order by m_kota_id");
		$m_status=DB::connection()->select("select * from m_status where active = 1 order by nama desc");
		$m_pendidikan=DB::connection()->select("select * from m_pendidikan where active = 1 order by nama desc");
		
		$type = '';
		
		return view('edit_form_biodata_kandidat',compact('agama','m_kota','m_status','m_pendidikan','p_recruitment','p_recruitment_pendidikan','p_recruitment_pekerjaan','p_recruitment_penyakit','p_recruitment_kursus','type'));
	}public function view_form_biodata_kandidat($idRec)
	{
	    $t_karyawan_kandidat = DB::connection()->select("select * from t_karyawan_kandidat left join p_recruitment pr on pr.t_karyawan_kandidat_id =  t_karyawan_kandidat.t_karyawan_kandidat_id where t_karyawan_kandidat.t_karyawan_kandidat_id = $idRec");
	    $idRec =$t_karyawan_kandidat[0]->p_recruitment_id;
		$p_recruitment=DB::connection()->select("select * from p_recruitment where active = 1 and p_recruitment_id=$idRec");
		$p_recruitment_kursus=DB::connection()->select("select * from p_recruitment_kursus where active = 1 and p_recruitment_id=$idRec");
		$p_recruitment_pendidikan=DB::connection()->select("select * from p_recruitment_pendidikan where active = 1 and p_recruitment_id=$idRec");
		$p_recruitment_pekerjaan=DB::connection()->select("select * from p_recruitment_pekerjaan where active = 1 and p_recruitment_id=$idRec");
		$p_recruitment_penyakit=DB::connection()->select("select * from p_recruitment_penyakit where active = 1 and p_recruitment_id=$idRec");
		$agama=DB::connection()->select("select * from m_agama where active = 1 order by m_agama_id");
		$m_kota=DB::connection()->select("select * from m_kota where active = 1 order by m_kota_id");
		$m_status=DB::connection()->select("select * from m_status where active = 1 order by nama desc");
		$m_pendidikan=DB::connection()->select("select * from m_pendidikan where active = 1 order by nama desc");
		
		$type = 'disabled';
		
		return view('edit_form_biodata_kandidat',compact('agama','m_kota','m_status','m_pendidikan','p_recruitment','p_recruitment_pendidikan','p_recruitment_pekerjaan','p_recruitment_penyakit','p_recruitment_kursus','type'));
	}
	public function save_form_biodata_kandidat(Request $request)
	{
		
			$cv=DB::connection()->select("select * from t_karyawan_kandidat where kode='".strtoupper($request->get("kode"))."'");
			//print_r($cv);die;
			$t_karyawan_kandidat_id = $cv[0]->t_karyawan_kandidat_id;
			$sqlidrec="SELECT * FROM t_karyawan_kandidat where kode='".strtoupper($request->get("kode"))."' ";
			$data=DB::connection()->select($sqlidrec);
			$sqlidrec="SELECT max(p_recruitment_id)+1 as p_recruitment_id FROM p_recruitment";
			$datarec=DB::connection()->select($sqlidrec);
			$idrec=$datarec[0]->p_recruitment_id;
			$bln=date('m');
			$thn=date('y');
			$koderec='1.REC.'.$bln.'.'.$thn.'.'.$idrec;
			DB::connection()->table("t_karyawan_kandidat")
					->where("t_karyawan_kandidat_id", $t_karyawan_kandidat_id)
					->update([
						"status"=>2
					]);
					$array = ["t_karyawan_kandidat_id" => $data[0]->t_karyawan_kandidat_id,
						"p_recruitment_id" => $idrec,
						"kode" => $koderec,
						"kode_kandidat" => strtoupper($request->get("kode")),
						"t_karyawan_kandidat_id" => $t_karyawan_kandidat_id,
						"jabatan_yang_dituju" => $request->get("jabatan_yang_dituju"),
						"nama_lengkap" => ucwords($request->get("nama_lengkap")),
						"nama_panggilan" => ucwords($request->get("nama_panggilan")),
						"tgl_lahir" => date('Y-m-d', strtotime($request->get("tgl_lahir"))),
						"tempat_lahir" => $request->get("tempat_lahir"),

						"m_jenis_kelamin_id" => $request->get("jenis_kelamin"),
						"m_agama_id" => $request->get("agama"),

						"email" => $request->get("alamat_email"),
						"no_hp" => $request->get("no_hp"),


						"no_ktp" => $request->get("ktp"),
						"alamat_ktp" => $request->get("alamat_ktp"),
						"alamat_tinggal" => $request->get("alamat_tinggal"),

						"create_date" => date("Y-m-d"),


						"m_kota_id" => $request->get("m_kota_id"),
						"m_kota_id_asal" => $request->get("m_kota_asal_id"),
						"m_status_id" => $request->get("status_pernikahan"),
						"jumlah_anak" => $request->get("jml_anak"),

						"pendidikan" => $request->get("pendidikan"),
						"jurusan" => $request->get("jurusan_terakhir"),
						"nilai" => str_replace(",",".",$request->get("ipk")),
						"nama_sekolah" => $request->get("nama_sekolah")?($request->get("nama_sekolah")):'',

						"rencana_5thn" => $request->get("rencana_5thn"),
						"prinsip" => $request->get("prinsip"),
						"pekerjaan_sukai" => $request->get("pekerjaan_sukai"),
						"pekerjaan_tdk_disukai" => $request->get("pekerjaan_tdk_disukai"),
						"kelebihan" => $request->get("kelebihan"),
						"kekurangan" => $request->get("kekurangan"),
						"kepolisian" => $request->get("kepolisian"),
						"kepolisian_kapan" => $request->get("kepolisian_kapan"),
						"keberatan_perdin_tidak" => $request->get("keberatan_perdin_tidak"),
						"keberatan_perdin_ya" => $request->get("keberatan_perdin_ya"),
						"keberatan_luar_kota_tidak" => $request->get("keberatan_luar_kota_tidak"),
						"keberatan_luar_kota_ya" => $request->get("keberatan_luar_kota_ya"),
						"kapan_bekerja" => $request->get("kapan_bekerja"),
						"nego_salary" => $request->get("nego_salary"),
						"gaji_fasilitas" => $request->get("gaji_fasilitas"),
						"penyataan_setuju" => $request->get("penyataan_setuju")?1:0
					];
					
					//print_r($array);
					DB::connection()->table("p_recruitment")
					//->where("p_recruitment_id", $idrec)
					->insert($array);
					
					if ($request->file('foto')) {
						//echo 'masuk';die;
						$file = $request->file('foto');
						$destination = "dist/img/profile/";
						$path = 'profil-' . date('ymdhis') . '-' . $file->getClientOriginalName();
						$file->move($destination, $path);
						//echo $path;die;
						DB::connection()->table("p_recruitment")->where("p_recruitment_id", $idrec)
						->update([
							"foto" => $path
						]);
					}
					for ($i=0;$i<count($request->get('jenjang'));$i++) {
						$array =[
							"p_recruitment_id" => $idrec,
							"nama_sekolah" => $request->get("nama_sekolah_pendidikan")[$i],
							"jenjang" => $request->get("jenjang")[$i],
							"jurusan" => $request->get("jurusan")[$i],
							"alamat_sekolah" => $request->get("alamat_sekolah")[$i],
							"kota_sekolah" => $request->get("kotapendidikan")[$i],
							"tahun_lulus" => $request->get("Tahunlulus")[$i],
							"nilai" => str_replace(",",".",$request->get("nilai")[$i]),
							"active" => 1,
							"create_date" => date("Y-m-d")
						];
						//print_r($array);
						DB::connection()->table("p_recruitment_pendidikan")
						->insert($array);
					}
					//////
					if (count($request->get('nama_kursus'))) {
					for ($i=0;$i<count($request->get('nama_kursus'));$i++) {
						DB::connection()->table("p_recruitment_kursus")

						->insert([
						"p_recruitment_id" => $idrec,
							"nama_kursus" => $request->get("nama_kursus")[$i],
							"penyelenggara" => $request->get("penyelenggara")[$i],
							"tanggal_awal_pelatihan" => $request->get("tanggal_awal_pelatihan")[$i],
							"tanggal_akhir_pelatihan" => $request->get("tanggal_akhir_pelatihan")[$i],
							"sertifikat" => $request->get("sertifikat")[$i],
							"active" => 1,
							"create_date" => date("Y-m-d"),
						]);
					}
					}
					if (count($request->get('nama_perusahaan'))){
					    $slip=array();
					    $faklaring=array();
					    if ($request->file('fileSlip')) {
					        for ($i=0;$i<count($request->file('fileSlip'));$i++) {
        						//echo 'masuk';die;
        						$file = $request->file('fileSlip')[$i];
        						$destination = "dist/img/file/";
        						$path = 'rekrutmen_slip_gaji-' . date('ymdhis') . '-' . $file->getClientOriginalName();
        						$file->move($destination, $path);
        						//echo $path;die;
        						$slip[$i] = $path;
					        }
    					}
                        if ($request->file('suratfaklaring')) {
					        for ($i=0;$i<count($request->file('suratfaklaring'));$i++) {
        						//echo 'masuk';die;
        						$file = $request->file('suratfaklaring')[$i];
        						$destination = "dist/img/file/";
        						$path = 'rekrutmen_faklaring-' . date('ymdhis') . '-' . $file->getClientOriginalName();
        						$file->move($destination, $path);
        						//echo $path;die;
        						$faklaring[$i] = $path;
					        }
    					}

    					for ($i=0;$i<count($request->get('nama_perusahaan'));$i++) {
        					DB::connection()->table("p_recruitment_pekerjaan")
        					
        					->insert([
        					
        
            					"nama_perusahaan" => ($request->get("nama_perusahaan"))[$i],
            					"awal_periode" => $request->get("tgl_awal_kerja")[$i],
            					"akhir_periode" => $request->get("tgl_akhir_kerja")[$i],
            					"posisi_kerja" => $request->get("jabatan")[$i],
            					"lokasi" => $request->get("lokasi")[$i],
            					"ruang_lingkup_kerja" => $request->get("ruang_lingkup_kerja")[$i],
            					"prestasi_kerja" => $request->get("prestasi_kerja")[$i],
        						"p_recruitment_id" => $idrec,
        						//"deskripsi_kerja" => $request->get("deskripsi_kerja")[$i],
        						"gaji" => $request->get("gaji")[$i],
        						"nomor_ref" => $request->get("nomor_ref")[$i],
        						"nama_ref" => $request->get("nama_ref")[$i],
        						"jabatan_ref" => $request->get("jabatan_ref")[$i],
        						"alasan_resign" => $request->get("resign")[$i],
        						"slip_terakhir"=>(isset($slip[$i])?$slip[$i]:''),
        						"surat_faklaring"=>(isset($faklaring[$i])?$faklaring[$i]:''),
        
        					]);
    				    }
				    }
				if (count($request->get('jenis_penyakit'))){
				for ($i=0;$i<count($request->get('jenis_penyakit'));$i++) {
					DB::connection()->table("p_recruitment_penyakit")
					// ->where("p_karyawan_riwayat_pekerjaan_id",$id)
					->insert([
						//"nik" => $request->get("nik"),
						//"nama" => $request->get("nama_lengkap"),

						"jenis_penyakit" => ($request->get("jenis_penyakit"))[$i],
						"tahun_penyakit" => $request->get("tahun_penyakit")[$i],
						"sembuh" => $request->get("sembuh")[$i],
						"dampak_saat_ini" => $request->get("dampak_saat_ini")[$i],
						
						"p_recruitment_id" => $idrec,
						
						"create_date" => date("Y-m-d"),
						"active" =>1,
					]);
				}
				}
					
				
					
					
					
					return redirect()->route('fe.edit_profile')->with('success', ' Karyawan Berhasil di Ubah!');
					
	}
	
}
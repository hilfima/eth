<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Auth;
use Hash;
use App\Helper_function;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL; 

class ProfileController extends Controller
{
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		Session::put('backUrl', URL::full());
		$this->middleware('auth');
	}

	/**
	 * Show the application dashboard.
	 *
	 * @return \Illuminate\Contracts\Support\Renderable
	 */
	public function edit_profile(Request $request)
	{
		$iduser = Auth::user()->id;
		$sqlidkar = "select * from p_karyawan where user_id=$iduser";
		$idkar = DB::connection()->select($sqlidkar);
		$id = $idkar[0]->p_karyawan_id;

		$sqlpa = "select m_jabatan.m_pangkat_id from p_karyawan_pekerjaan 
LEFT JOIN m_jabatan on m_jabatan.m_jabatan_id=p_karyawan_pekerjaan.m_jabatan_id
LEFT JOIN m_pangkat on m_pangkat.m_pangkat_id=m_jabatan.m_pangkat_id
where p_karyawan_id=$iduser";
		$pa = DB::connection()->select($sqlpa);
		$pangkat = 0;
		if (!empty($pa['m_pangkat_id'][0])) {
			$pangkat = $pa['m_pangkat_id'][0];
		}

			$sqlkaryawan = "SELECT a.p_karyawan_id,nama_darurat,hubungan_darurat,a.nik,b.nama_lengkap,b.nama_panggilan,c.nama as nmjk,d.nama as nmkota,e.nama as nmagama,g.nama as nmlokasi,h.nama as nmjabatan,i.nama as nmdept,j.nama as nmdivisi,l.nama as nmstatus,m.no_absen,b.foto,p.password,
			case when f.is_shift=0 then 'Shift' else 'Non Shift' end as shift,k.tgl_awal,kontak_darurat,k.tgl_akhir,a.tgl_bergabung,a.email_perusahaan,k.keterangan,case when a.active=1 then 'Active' else 'Non Active' end as status,o.nama as nmpangkat,b.alamat_ktp,a.pendidikan,a.jurusan,a.nama_sekolah,a.domisili,a.jumlah_anak,b.tempat_lahir,case when m_status_id=0 then 'Belum Menikah' else 'Sudah Menikah' end as status_pernikahan,b.no_hp,c.nama as jenis_kelamin,e.nama as agama,b.email,b.tgl_lahir,f.kantor,n.ktp,n.no_npwp,n.no_bpjstk,n.no_bpjsks,b.m_status_id,b.m_kota_id,b.m_jenis_kelamin_id,b.m_agama_id,f.m_lokasi_id,f.m_jabatan_id,k.m_status_pekerjaan_id,f.m_departemen_id,f.m_divisi_id,a.active,n.no_sima,n.no_simc,n.no_pasport,n.kartu_keluarga,case when f.periode_gajian=1 then 'Bulanan' else 'Pekanan' end as periode,f.periode_gajian,q.nama as nama_kantor,f.bank,f.norek,f.kota,n.file_kk,n.file_ktp,n.file_bpjs_karyawan, aa.nama_grade as grade,o.nama as nmpangkat
			FROM p_karyawan a
			LEFT JOIN p_recruitment b on b.p_recruitment_id=a.p_recruitment_id
			LEFT JOIN m_jenis_kelamin c on c.m_jenis_kelamin_id=b.m_jenis_kelamin_id
			LEFT JOIN m_kota d on d.m_kota_id=b.m_kota_id
			LEFT JOIN m_agama e on e.m_agama_id=b.m_agama_id
			LEFT JOIN p_karyawan_pekerjaan f on f.p_karyawan_id=a.p_karyawan_id
			LEFT JOIN m_lokasi g on g.m_lokasi_id=f.m_lokasi_id
			LEFT JOIN m_jabatan h on h.m_jabatan_id=f.m_jabatan_id
			LEFT JOIN m_pangkat o on o.m_pangkat_id=h.m_pangkat_id
			LEFT JOIN m_departemen i on i.m_departemen_id=f.m_departemen_id
			LEFT JOIN m_divisi j on j.m_divisi_id=f.m_divisi_id
			LEFT JOIN p_karyawan_kontrak k on k.p_karyawan_id=a.p_karyawan_id
			LEFT JOIN m_status_pekerjaan l on l.m_status_pekerjaan_id=k.m_status_pekerjaan_id
			LEFT JOIN p_karyawan_absen m on m.p_karyawan_id=a.p_karyawan_id
			LEFT JOIN p_karyawan_kartu n on n.p_karyawan_id=a.p_karyawan_id
			LEFT JOIN m_office q on q.m_office_id=f.m_kantor_id
			LEFT JOIN users p on p.id=a.user_id	
			LEFT JOIN m_karyawan_grade aa on h.job>=aa.job_min and h.job<= aa.job_max
			WHERE a.p_karyawan_id=$id";
		$karyawan = DB::connection()->select($sqlkaryawan);

		$sql = "SELECT t_karyawan_kandidat.* FROM p_karyawan 
			left join p_recruitment  on p_karyawan.p_recruitment_id = p_recruitment.p_recruitment_id
			left join t_karyawan_kandidat on t_karyawan_kandidat.t_karyawan_kandidat_id = p_recruitment. t_karyawan_kandidat_id
			WHERE  p_karyawan.p_karyawan_id=$id ORDER BY nama ASC ";
		$cv = DB::connection()->select($sql);
		
		
		$sql = "SELECT * FROM p_karyawan_keluarga WHERE active=1 and p_karyawan_id=$id ORDER BY nama ASC ";
		$keluarga = DB::connection()->select($sql);
		$sql = "SELECT * FROM p_karyawan_pendidikan WHERE active=1 and p_karyawan_id=$id ORDER BY nama_sekolah ASC ";
		$pendidikan = DB::connection()->select($sql);
		$sql = "SELECT * FROM p_karyawan_kursus WHERE active=1 and p_karyawan_id=$id ORDER BY nama_kursus ASC ";
		$kursus = DB::connection()->select($sql);
		$sqlidkar = "SELECT * FROM p_karyawan_pakaian WHERE p_karyawan_id=$id and tipe=1";
		$datapakaian = DB::connection()->select($sqlidkar);
		$sqlidkar = "SELECT *
	    ,CASE
				    WHEN p_karyawan_pakaian.p_karyawan_keluarga_id =-1 THEN d.nama
	   				ELSE c.nama END as nama
	   				,
	   				CASE
				    WHEN p_karyawan_pakaian.p_karyawan_keluarga_id =-1 THEN 'Diri Sendiri'
	   				ELSE c.hubungan END as hubungan,p_karyawan_pakaian.p_karyawan_keluarga_id as keluarga_id
	   				
	     FROM p_karyawan_pakaian 
	     	left join p_karyawan D on p_karyawan_pakaian.p_karyawan_id = d.p_karyawan_id 
	     	left join p_karyawan_keluarga c on p_karyawan_pakaian.p_karyawan_keluarga_id = c.p_karyawan_keluarga_id 
	     
	     WHERE p_karyawan_pakaian.p_karyawan_id=$id and tipe=2 and p_karyawan_pakaian.active=1";
		$datapakaiankeluarga = DB::connection()->select($sqlidkar);

		$sql = "SELECT * FROM p_karyawan_riwayat_pekerjaan WHERE active=1 and p_karyawan_id = $id ORDER BY awal_periode ASC ";
		$datapekerjaan = DB::connection()->select($sql);
		
		$sql = "SELECT * FROM p_karyawan_award left join m_jenis_reward on m_jenis_reward.m_jenis_reward_id = p_karyawan_award.m_jenis_reward_id WHERE p_karyawan_award.active=1 and p_karyawan_id = $id ORDER BY tgl_award ASC ";
		$award = DB::connection()->select($sql);

		$sqlagama = "SELECT * FROM m_agama WHERE active=1 ORDER BY nama ASC ";
		$agama = DB::connection()->select($sqlagama);

		$sqllokasi = "SELECT * FROM m_lokasi WHERE active=1 ORDER BY nama ASC ";
		$lokasi = DB::connection()->select($sqllokasi);

		$sqlkota = "SELECT * FROM m_kota WHERE active=1 ORDER BY nama ASC ";
		$kota = DB::connection()->select($sqlkota);

		$sqljk = "SELECT * FROM m_jenis_kelamin WHERE active=1 ORDER BY nama ASC ";
		$jk = DB::connection()->select($sqljk);

		$sqldepartemen = "SELECT * FROM m_departemen WHERE active=1 ORDER BY nama ASC ";
		$departemen = DB::connection()->select($sqldepartemen);

		$sqljabatan = "SELECT m_jabatan.*,m_pangkat.nama as nmpangkat FROM m_jabatan 
                      LEFT JOIN m_pangkat on m_pangkat.m_pangkat_id=m_jabatan.m_pangkat_id
                      WHERE m_jabatan.active=1 ORDER BY m_jabatan.nama ASC ";
		$jabatan = DB::connection()->select($sqljabatan);

		$sqlgrade = "SELECT * FROM m_grade WHERE active=1 ORDER BY nama ASC ";
		$grade = DB::connection()->select($sqlgrade);

		$sql = "SELECT * FROM p_karyawan_sanksi left join m_jenis_sanksi on p_karyawan_sanksi.m_jenis_sanksi_id = m_jenis_sanksi.m_jenis_sanksi_id WHERE p_karyawan_id = $id and p_karyawan_sanksi.active=1 ORDER BY tgl_awal_sanksi ASC ";
		$sanksi = DB::connection()->select($sql);
		
		$sqlpangkat = "SELECT * FROM m_pangkat WHERE active=1 ORDER BY nama ASC ";
		$pangkats = DB::connection()->select($sqlpangkat);

		$sqldivisi = "SELECT * FROM m_divisi WHERE active=1 ORDER BY nama ASC ";
		$divisi = DB::connection()->select($sqldivisi);

		$sqlstspekerjaan = "SELECT * FROM m_status_pekerjaan WHERE active=1 ORDER BY nama ASC ";
		$stspekerjaan = DB::connection()->select($sqlstspekerjaan);
		
		$sql = "SELECT * FROM p_karyawan_kontrak where p_karyawan_id = $id";
		$kontrak = DB::connection()->select($sql);

		$sqluser = "SELECT p_recruitment.foto FROM users
left join p_karyawan on p_karyawan.user_id=users.id
left join p_recruitment on p_recruitment.p_recruitment_id=p_karyawan.p_recruitment_id
where users.id=$iduser";
		$user = DB::connection()->select($sqluser);
		$help  = new Helper_function();
		$profile_power = true;
		return view('frontend.profile.profile', compact('karyawan', 'sanksi', 'agama', 'lokasi', 'kota','kontrak', 'jk', 'departemen', 'jabatan', 'grade', 'pangkat', 'divisi', 'stspekerjaan', 'pangkats', 'user', 'keluarga', 'award', 'id', 'pendidikan', 'kursus', 'datapekerjaan', 'help', 'datapakaian', 'datapakaiankeluarga', 'cv', 'profile_power','request'));
	}

	public function update_profile_utama(Request $request, $id)
	{

		DB::beginTransaction();
		try {
		} catch (\Exeception $e) {
			DB::rollback();
			return redirect()->back()->with('error', $e);
		}
	}
	public function hapus_keluarga($id)
	{

		DB::beginTransaction();
		try {
			$idUser = Auth::user()->id;
			DB::connection()->table("p_karyawan_keluarga")
				->where("p_karyawan_keluarga_id", $id)
				->update([
					//"nik" => $request->get("nik"),
					//"nama" => $request->get("nama_lengkap"),


					"active" => 0,

					"update_date" => date("Y-m-d"),
					"update_by" => $idUser,
				]);
			DB::commit();
			return redirect()->route('fe.edit_profile')->with('success', ' Karyawan Berhasil di Ubah!');
		} catch (\Exeception $e) {
			DB::rollback();
			return redirect()->back()->with('error', $e);
		}
	}
	public function hapus_pakaian($id)
	{

		DB::beginTransaction();
		try {
			$idUser = Auth::user()->id;
			DB::connection()->table("p_karyawan_pakaian")
				->where("p_karyawan_pakaian_id", $id)
				->update([
					//"nik" => $request->get("nik"),
					//"nama" => $request->get("nama_lengkap"),


					"active" => 0,

					"update_date" => date("Y-m-d"),
					"update_by" => $idUser,
				]);
			DB::commit();
			return redirect()->route('fe.edit_profile','#data_lainnya')->with('success', ' Karyawan Berhasil di Ubah!');
		} catch (\Exeception $e) {
			DB::rollback();
			return redirect()->back()->with('error', $e);
		}
	}
	public function hapus_pendidikan($id)
	{

		DB::beginTransaction();
		try {
			echo '' . $id;
			$idUser = Auth::user()->id;
			DB::connection()->table("p_karyawan_pendidikan")
				->where("p_karyawan_pendidikan_id", $id)
				->update([


					"active" => 0,

					"update_date" => date("Y-m-d"),
					"update_by" => $idUser,
				]);
			DB::commit();
			return redirect()->route('fe.edit_profile')->with('success', ' Karyawan Berhasil di Ubah!');
		} catch (\Exeception $e) {
			DB::rollback();
			return redirect()->back()->with('error', $e);
		}
	}
	public function hapus_pekerjaan($id)
	{

		DB::beginTransaction();
		try {
			echo '' . $id;
			$idUser = Auth::user()->id;
			DB::connection()->table("p_karyawan_riwayat_pekerjaan")
				->where("p_karyawan_riwayat_pekerjaan_id", $id)
				->update([


					"active" => 0,

					"update_date" => date("Y-m-d"),
					"update_by" => $idUser,
				]);
			DB::commit();
			return redirect()->route('fe.edit_profile')->with('success', ' Karyawan Berhasil di Ubah!');
		} catch (\Exeception $e) {
			DB::rollback();
			return redirect()->back()->with('error', $e);
		}
	}
	public function hapus_kursus($id)
	{

		DB::beginTransaction();
		try {
			$idUser = Auth::user()->id;
			DB::connection()->table("p_karyawan_kursus")
				->where("p_karyawan_kursus_id", $id)
				->update([
					//"nik" => $request->get("nik"),
					//"nama" => $request->get("nama_lengkap"),


					"active" => 0,

					"update_date" => date("Y-m-d"),
					"update_by" => $idUser,
				]);
			DB::commit();
			return redirect()->route('fe.edit_profile')->with('success', ' Karyawan Berhasil di Ubah!');
		} catch (\Exeception $e) {
			DB::rollback();
			return redirect()->back()->with('error', $e);
		}
	}

	public function update_profile(Request $request, $id)
	{
		DB::beginTransaction();
		try {
			$idUser = Auth::user()->id;
			$sqlidkar = "SELECT * FROM p_karyawan WHERE p_karyawan_id=$id";
			$datakar = DB::connection()->select($sqlidkar);
			$idrec = $datakar[0]->p_recruitment_id;
			if ($request->get('type') == 'utama') {

				DB::connection()->table("p_recruitment")
					->where("p_recruitment_id", $idrec)
					->update([
						//"nama_lengkap" => $request->get("nama_lengkap"),
						"nama_panggilan" => ucwords($request->get("nama_panggilan")),
						"m_kota_id" => $request->get("kota"),
						"m_jenis_kelamin_id" => $request->get("jk"),
						"m_agama_id" => $request->get("agama"),
						"email" => $request->get("email"),
						"no_hp" => $request->get("no_hp"),
						"tempat_lahir" => $request->get("tempat_lahir"),
						"tgl_lahir" => date('Y-m-d', strtotime($request->get("tgl_lahir"))),

						"m_agama_id" => $request->get("agama"),
						"no_ktp" => $request->get("ktp"),
						"alamat_ktp" => $request->get("alamat_ktp"),
						"alamat_tinggal" => $request->get("alamat_ktp"),

						"update_date" => date("Y-m-d"),
						"update_by" => $idUser,
					]);
				DB::connection()->table("p_karyawan")
					->where("p_karyawan_id", $id)
					->update([
						//"nik" => $request->get("nik"),
						//"nama" => $request->get("nama_lengkap"),
						"email_perusahaan" => $request->get("email"),
						"nama_darurat" => $request->get("nama_darurat"),
						"hubungan_darurat" => $request->get("hubungan_darurat"),
						"kontak_darurat" => $request->get("kontak_darurat"),
						"no_hp2" => $request->get("no_hp"),
						"domisili" => $request->get("domisili"),

						"update_date" => date("Y-m-d"),
						"update_by" => $idUser,
					]);
			} 
			else if ($request->get('type') == 'riwayatpekerjaan') {
				DB::connection()->table("p_karyawan_riwayat_pekerjaan")
					// ->where("p_karyawan_riwayat_pekerjaan_id",$id)
					->insert([
						//"nik" => $request->get("nik"),
						//"nama" => $request->get("nama_lengkap"),

						"nama_perusahaan" => ucwords($request->get("namaperusahaan")),
						"awal_periode" => $request->get("tgl_awal_kerja"),
						"akhir_periode" => $request->get("tgl_akhir_kerja"),
						"posisi_kerja" => $request->get("posisi_kerja"),
						"p_karyawan_id" => $id,
						"deskripsi_kerja" => $request->get("deskripsi_kerja"),

						"create_date" => date("Y-m-d"),
						"create_by" => $idUser,
					]);
			}
			else if ($request->get('type') == 'award') {
				DB::connection()->table("p_karyawan_award")
					// ->where("p_karyawan_riwayat_pekerjaan_id",$id)
					->insert([
						//"nik" => $request->get("nik"),
						//"nama" => $request->get("nama_lengkap"),

						"nama_award" => ucwords($request->get("nama_award")),
						"tgl_award" => $request->get("tgl_award"),
						"p_karyawan_id" => $id,
					]);
			} 
			else if ($request->get('type') == 'pendidikan') {
				DB::connection()->table("p_karyawan")
					->where("p_karyawan_id", $id)
					->update([
						//"nik" => $request->get("nik"),
						//"nama" => $request->get("nama_lengkap"),

						"pendidikan" => $request->get("pendidikan"),
						"jurusan" => $request->get("jurusan"),
						"nama_sekolah" => ucwords($request->get("nama_sekolah")),

						"update_date" => date("Y-m-d"),
						"update_by" => $idUser,
					]);
			} 
			else if ($request->get('type') == 'pakaian') {
				$idUser = Auth::user()->id;
				$sqlidkar = "SELECT * FROM p_karyawan_pakaian WHERE p_karyawan_id=$id and tipe=1";
				$datapakaian = DB::connection()->select($sqlidkar);
				if (count($datapakaian)) {

					DB::connection()->table("p_karyawan_pakaian")
						->where("p_karyawan_pakaian_id", $datapakaian[0]->p_karyawan_pakaian_id)
						->update([

							"gamis" => $request->get("gamis"),
							"kemeja" => $request->get("kemeja"),
							"kaos" => $request->get("kaos"),
							"jaket" => $request->get("jaket"),
							"celana" => $request->get("celana"),
							"sepatu" => $request->get("sepatu"),
							"update_date" => date("Y-m-d"),
							"update_by" => $idUser,
						]);
				} else {
					DB::connection()->table("p_karyawan_pakaian")

						->insert([

							"p_karyawan_id" => $datakar[0]->p_karyawan_id,
							"tipe" => 1,
							"gamis" => $request->get("gamis"),
							"kemeja" => $request->get("kemeja"),
							"kemeja" => $request->get("kemeja"),
							"kaos" => $request->get("kaos"),
							"jaket" => $request->get("jaket"),
							"celana" => $request->get("celana"),
							"sepatu" => $request->get("sepatu"),
							"create_date" => date("Y-m-d"),
							"create_by" => $idUser,
						]);
				}
			} 
			else if ($request->get('type') == 'pakaian_keluarga') {
				$idUser = Auth::user()->id;
				$keluarga = $request->get("keluarga");
				$sqlidkar = "SELECT * FROM p_karyawan_pakaian WHERE p_karyawan_id=$id and tipe=1 and p_karyawan_keluarga_id=$keluarga";
				$datapakaian = DB::connection()->select($sqlidkar);
				if (count($datapakaian)) {

					DB::connection()->table("p_karyawan_pakaian")
						->where("p_karyawan_pakaian_id", $datapakaian[0]->p_karyawan_pakaian_id)
						->update([

							"gamis" => $request->get("gamis"),
							"p_karyawan_keluarga_id" => $request->get("keluarga"),
							"kemeja" => $request->get("kemeja"),
							"kaos" => $request->get("kaos"),
							"jaket" => $request->get("jaket"),
							"celana" => $request->get("celana"),
							"sepatu" => $request->get("sepatu"),
							"update_date" => date("Y-m-d"),
							"update_by" => $idUser,
						]);
				} else {
					DB::connection()->table("p_karyawan_pakaian")

						->insert([

							"p_karyawan_id" => $datakar[0]->p_karyawan_id,
							"tipe" => 2,
							"p_karyawan_keluarga_id" => $request->get("keluarga"),
							"gamis" => $request->get("gamis"),
							"kemeja" => $request->get("kemeja"),
							"kemeja" => $request->get("kemeja"),
							"kaos" => $request->get("kaos"),
							"jaket" => $request->get("jaket"),
							"celana" => $request->get("celana"),
							"sepatu" => $request->get("sepatu"),
							"create_date" => date("Y-m-d"),
							"create_by" => $idUser,
						]);
				}
			} 
			else if ($request->get('type') == 'pernikahan') {
				DB::connection()->table("p_recruitment")
					->where("p_recruitment_id", $idrec)
					->update([

						"m_status_id" => $request->get("status_pernikahan"),
						"update_date" => date("Y-m-d"),
						"update_by" => $idUser,
					]);
				DB::connection()->table("p_karyawan")
					->where("p_karyawan_id", $id)
					->update([
						//"nik" => $request->get("nik"),
						//"nama" => $request->get("nama_lengkap"),

						"jumlah_anak" => $request->get("jumlah_anak"),

						"update_date" => date("Y-m-d"),
						"update_by" => $idUser,
					]);
			} 
			else if ($request->get('type') == 'kartu') {

				DB::connection()->table("p_karyawan_kartu")
					->where("p_karyawan_id", $id)
					->update([

						"ktp" => $request->get("ktp"),
						"kartu_keluarga" => $request->get("kk"),
						"no_npwp" => $request->get("npwp"),
						"no_bpjsks" => $request->get("bpjsks"),
						"no_bpjstk" => $request->get("bpjstk"),
						"no_sima" => $request->get("sima"),
						"no_simc" => $request->get("simc"),
						"no_pasport" => $request->get("pasport"),
						"active" => 1,
						"update_date" => date("Y-m-d"),
						"update_by" => $idUser,
					]);
				if ($request->file('file_kk')) { //echo 'masuk';die;
					$file = $request->file('file_kk');
					$destination = "dist/img/file/";
					$path = 'kk-' . date('ymdhis') . '-' . $file->getClientOriginalName();
					$file->move($destination, $path);
					//echo $path;die;
					DB::connection()->table("p_karyawan_kartu")->where("p_karyawan_id", $id)
						->update([
							"file_kk" => $path
						]);
				}
				if ($request->file('file_ktp')) { //echo 'masuk';die;
					$file = $request->file('file_ktp');
					$destination = "dist/img/file/";
					$path = 'ktp-' . date('ymdhis') . '-' . $file->getClientOriginalName();
					$file->move($destination, $path);

					//echo $path;die;
					DB::connection()->table("p_karyawan_kartu")->where("p_karyawan_id", $id)
						->update([
							"file_ktp" => $path
						]);
				}
				if ($request->file('file_bpjs')) { //echo 'masuk';die;
					$file = $request->file('file_bpjs');
					$destination = "dist/img/file/";
					$path = 'bpjs_karyawan-' . date('ymdhis') . '-' . $file->getClientOriginalName();
					$file->move($destination, $path);

					//echo $path;die;
					DB::connection()->table("p_karyawan_kartu")->where("p_karyawan_id", $id)
						->update([
							"file_bpjs_karyawan" => $path
						]);
				}
			} 
			else if ($request->get('type') == 'listpendidikan') {
				DB::connection()->table("p_karyawan_pendidikan")

					->insert([

						"p_karyawan_id" => $id,
						"nama_sekolah" => $request->get("namasekolah"),
						"jenjang" => $request->get("jenjang"),
						"jurusan" => $request->get("jurusan"),
						"alamat_sekolah" => $request->get("alamat_pendidikan"),
						"kota_sekolah" => $request->get("kotapendidikan"),
						"tahun_lulus" => $request->get("Tahunlulus"),
						"active" => 1,
						"create_date" => date("Y-m-d"),
						"create_by" => $idUser,
					]);
			} 
			else if ($request->get('type') == 'kursus') {
				DB::connection()->table("p_karyawan_kursus")

					->insert([
						"p_karyawan_id" => $id,
						"nama_kursus" => $request->get("nama_kursus"),
						"penyelenggara" => $request->get("penyelenggara"),
						"tanggal_awal_pelatihan" => $request->get("tanggal_awal_pelatihan"),
						"tanggal_akhir_pelatihan" => $request->get("tanggal_akhir_pelatihan"),
						"sertifikat" => $request->get("sertifikat"),
						"active" => 1,
						"create_date" => date("Y-m-d"),
						"create_by" => $idUser,
					]);
			} 
			else if ($request->get('type') == 'keluarga') {

				//print_r($request->get("namakeluarga"));

				for ($i = 0; $i < count($request->get("namakeluarga")); $i++) {

					DB::connection()->table("p_karyawan_keluarga")

						->insert([
							//"nik" => $request->get("nik"),namakeluarga
							//"nama" => $request->get("nama_lengkap"),
							"p_karyawan_id" => $id,
							"nama" => ucwords($request->get("namakeluarga")[$i]),
							"hubungan" => $request->get("hubungankeluarga")[$i],
							"tgl_lahir" => $request->get("tgl_lahirkeluarga")[$i],
							"no_hp" => $request->get("no_hpkeluarga")[$i],

						]);
				}
			} 
			else if ($request->get('type') == 'bank') {
				DB::connection()->table("p_karyawan_pekerjaan")
					->where("p_karyawan_id", $id)
					->update([
						//"nik" => $request->get("nik"),
						//"nama" => $request->get("nama_lengkap"),

						"norek" => $request->get("norek"),
						"bank" => $request->get("bank"),

						"update_date" => date("Y-m-d"),
						"update_by" => $idUser,
					]);
			} 
			else if ($request->get('type') == 'password') {
				$password = $request->get("password");
				if ($password != '' || $password != null) {
					DB::connection()->table("users")
						->where("id", $idUser)
						->update([
							"password" => Hash::make(($request->get("password"))),
							"updated_at" => date("Y-m-d"),
						]);
				}
			} 
			else if ($request->get('type') == 'profile') {
				if ($request->file('image')) { //echo 'masuk';die;
					$file = $request->file('image');
					$destination = "dist/img/profile/";
					$path = 'profil-' . date('ymdhis') . '-' . $file->getClientOriginalName();
					$file->move($destination, $path);
					//echo $path;die;
					DB::connection()->table("p_recruitment")->where("p_recruitment_id", $idrec)
						->update([
							"foto" => $path
						]);
				}
			}



			/**/


			DB::commit();
			return redirect()->route('fe.edit_profile',['active='.$request->get('type')])->with('success', ' Karyawan Berhasil di Ubah!');
		} catch (\Exeception $e) {
			DB::rollback();
			return redirect()->back()->with('error', $e);
		}
	}

	public function edit_password()
	{
		$iduser = Auth::user()->id;
		$sqlidkar = "select * from users where id=$iduser";
		$idkar = DB::connection()->select($sqlidkar);

		$sqlpa = "select m_jabatan.m_pangkat_id from p_karyawan_pekerjaan 
LEFT JOIN m_jabatan on m_jabatan.m_jabatan_id=p_karyawan_pekerjaan.m_jabatan_id
LEFT JOIN m_pangkat on m_pangkat.m_pangkat_id=m_jabatan.m_pangkat_id
where p_karyawan_id=$iduser";
		$pa = DB::connection()->select($sqlpa);
		$pangkat = 0;
		if (!empty($pa['m_pangkat_id'][0])) {
			$pangkat = $pa['m_pangkat_id'][0];
		}

		$sqluser = "SELECT p_recruitment.foto FROM users
left join p_karyawan on p_karyawan.user_id=users.id
left join p_recruitment on p_recruitment.p_recruitment_id=p_karyawan.p_recruitment_id
where users.id=$iduser";
		$user = DB::connection()->select($sqluser);

		return view('frontend.password.edit_password', compact('idkar', 'pangkat', 'user'));
	}

	public function update_password(Request $request, $id)
	{
		//$idUser=Auth::user()->id;
		DB::beginTransaction();
		try {
			DB::connection()->table("users")
				->where("id", $id)
				->update([
					"password" => Hash::make(($request->get("password"))),
					"updated_at" => date("Y-m-d"),
				]);

			DB::commit();
			return redirect()->route('home')->with('success', 'Password Berhasil di Ubah!');
		} catch (\Exeception $e) {
			DB::rollback();
			return redirect()->back()->with('error', $e);
		}
	}
}

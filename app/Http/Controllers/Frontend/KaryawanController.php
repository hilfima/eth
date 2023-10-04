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

class KaryawanController extends Controller
{
	public function __construct()
	{
		$this->middleware('auth');
	}
	public function karyawan_bawahan()
    {
    	$iduser=Auth::user()->id;
        $sqlidkar="select * from p_karyawan 
        	join p_karyawan_pekerjaan on p_karyawan_pekerjaan.p_karyawan_id = p_karyawan.p_karyawan_id
        where user_id=$iduser";
        $idkar=DB::connection()->select($sqlidkar);
       // echo 'id_jabatan='.$idkar[0]->m_jabatan_id;
        //echo '<br>';
		$id_karyawan = $idkar[0]->p_karyawan_id;
		$help = new Helper_function();
		$jabstruk = $help->jabatan_struktural($id_karyawan);
		$atasan = $jabstruk['atasan'];
		$bawahan = $jabstruk['bawahan'];
		$sejajar = $jabstruk['sejajar'];
		$sql = "select *,p_karyawan.nama as nama_karyawan ,m_jabatan.nama as nmjabatan  from p_karyawan left join p_recruitment on p_recruitment.p_recruitment_id=p_karyawan.p_recruitment_id 
		LEFT JOIN p_karyawan_pekerjaan d on d.p_karyawan_id=p_karyawan.p_karyawan_id  
		LEFT JOIN m_jabatan on m_jabatan.m_jabatan_id=d.m_jabatan_id 
		where d.m_jabatan_id in ($bawahan) and p_karyawan.active=1";
        $karyawan=DB::connection()->select($sql);
		
		return view('frontend.profile.list_bawahan',compact('karyawan'));
    }
    public function lihat_karyawan($id)
    {
    	$sqlkaryawan = "SELECT a.p_karyawan_id,a.nik,b.nama_lengkap,b.nama_panggilan,c.nama as nmjk,d.nama as nmkota,e.nama as nmagama,g.nama as nmlokasi,h.nama as nmjabatan,i.nama as nmdept,j.nama as nmdivisi,l.nama as nmstatus,m.no_absen,b.foto,p.password,
case when f.is_shift=0 then 'Shift' else 'Non Shift' end as shift,k.tgl_awal,k.tgl_akhir,a.tgl_bergabung,a.email_perusahaan,k.keterangan,case when a.active=1 then 'Active' else 'Non Active' end as status,o.nama as nmpangkat,b.alamat_ktp,a.pendidikan,a.jurusan,a.nama_sekolah,a.domisili,a.jumlah_anak,b.tempat_lahir,case when m_status_id=0 then 'Belum Menikah' else 'Sudah Menikah' end as status_pernikahan,b.no_hp,c.nama as jenis_kelamin,e.nama as agama,b.email,b.tgl_lahir,f.kantor,n.ktp,n.no_npwp,n.no_bpjstk,n.no_bpjsks,b.m_status_id,b.m_kota_id,b.m_jenis_kelamin_id,b.m_agama_id,f.m_lokasi_id,f.m_jabatan_id,k.m_status_pekerjaan_id,f.m_departemen_id,f.m_divisi_id,a.active,n.no_sima,n.no_simc,n.no_pasport,n.kartu_keluarga,case when f.periode_gajian=1 then 'Bulanan' else 'Pekanan' end as periode,f.periode_gajian,q.nama as nama_kantor,f.bank,f.norek,f.kota,n.file_kk,n.file_ktp,n.file_bpjs_karyawan, aa.nama_grade as grade,o.nama as nmpangkat
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
        $sqlagama="SELECT * FROM m_agama WHERE active=1 ORDER BY nama ASC ";
        $agama=DB::connection()->select($sqlagama);

        $sqllokasi="SELECT * FROM m_lokasi WHERE active=1 ORDER BY nama ASC ";
        $lokasi=DB::connection()->select($sqllokasi);

        $sqlkota="SELECT * FROM m_kota WHERE active=1 ORDER BY nama ASC ";
        $kota=DB::connection()->select($sqlkota);

        $sqljk="SELECT * FROM m_jenis_kelamin WHERE active=1 ORDER BY nama ASC ";
        $jk=DB::connection()->select($sqljk);

        $sqldepartemen="SELECT * FROM m_departemen WHERE active=1 ORDER BY nama ASC ";
        $departemen=DB::connection()->select($sqldepartemen);

        $sqljabatan="SELECT m_jabatan.*,m_pangkat.nama as nmpangkat FROM m_jabatan 
                      LEFT JOIN m_pangkat on m_pangkat.m_pangkat_id=m_jabatan.m_pangkat_id
                      WHERE m_jabatan.active=1 ORDER BY m_jabatan.nama ASC ";
        $jabatan=DB::connection()->select($sqljabatan);

        $sqlgrade="SELECT * FROM m_grade WHERE active=1 ORDER BY nama ASC ";
        $grade=DB::connection()->select($sqlgrade);

        $sqlpangkat="SELECT * FROM m_pangkat WHERE active=1 ORDER BY nama ASC ";
        $pangkats=DB::connection()->select($sqlpangkat);

        $sqldivisi="SELECT * FROM m_divisi WHERE active=1 ORDER BY nama ASC ";
        $divisi=DB::connection()->select($sqldivisi);

        $sqlstspekerjaan="SELECT * FROM m_status_pekerjaan WHERE active=1 ORDER BY nama ASC ";
        $stspekerjaan=DB::connection()->select($sqlstspekerjaan);
		$help   = new Helper_function();
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
	   				ELSE c.hubungan END as hubungan
	   				
	     FROM p_karyawan_pakaian 
	     	left join p_karyawan D on p_karyawan_pakaian.p_karyawan_id = d.p_karyawan_id 
	     	left join p_karyawan_keluarga c on p_karyawan_pakaian.p_karyawan_keluarga_id = c.p_karyawan_keluarga_id 
	     
	     WHERE p_karyawan_pakaian.p_karyawan_id=$id and tipe=2 and p_karyawan_pakaian.active=1";
		$datapakaiankeluarga = DB::connection()->select($sqlidkar);

		$sql = "SELECT * FROM p_karyawan_riwayat_pekerjaan WHERE active=1 and p_karyawan_id = $id ORDER BY awal_periode ASC ";
		$datapekerjaan = DB::connection()->select($sql);
    	return view('frontend.profile.profile_bawahan',compact('karyawan','agama','lokasi','kota','jk','departemen','jabatan','grade','divisi','stspekerjaan','pangkats','help', 'pendidikan', 'kursus', 'datapekerjaan', 'help', 'datapakaian', 'datapakaiankeluarga', 'keluarga'));
    }
   
}
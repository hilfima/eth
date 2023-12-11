<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Auth;

class JabatanController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }public function jabatan()
    {
        $iduser=Auth::user()->id;
        $sqluser="SELECT p_recruitment.foto FROM users
left join p_karyawan on p_karyawan.user_id=users.id
left join p_recruitment on p_recruitment.p_recruitment_id=p_karyawan.p_recruitment_id
where users.id=$iduser";
        $user=DB::connection()->select($sqluser);

        $sqljabatan="SELECT m_jabatan.*,m_lokasi.nama as nmlokasi,m_pangkat.nama as nmpangkat,m_departemen.nama as nmdepartemen,
        m_divisi.nama as nmdivisi,m_lokasi.nama as nmentitas,m_directorat.*,m_divisi_new.*
                FROM m_jabatan
                LEFT JOIN m_pangkat on m_pangkat.m_pangkat_id=m_jabatan.m_pangkat_id
                LEFT JOIN m_departemen on m_departemen.m_departemen_id = m_jabatan.m_departemen_id
                LEFT JOIN m_divisi on m_divisi.m_divisi_id=m_departemen.m_divisi_id 
                 left join m_divisi_new on m_divisi.m_divisi_new_id =  m_divisi_new.m_divisi_new_id
                 left join m_directorat on m_directorat.m_directorat_id =  m_divisi_new.m_directorat_id
                left join m_lokasi on m_lokasi.m_lokasi_id =  m_directorat.m_lokasi_id
                WHERE 1=1 AND m_jabatan.active=1 order by  m_lokasi.nama,nama_directorat,nama_divisi,m_divisi.nama,m_departemen.nama,m_jabatan.nama";
        $jabatan=DB::connection()->select($sqljabatan);
        return view('backend.jabatan.jabatan',compact('jabatan','user'));
    }

    public function tambah_jabatan()
    {
        $sqlrumpunjabatan="SELECT * FROM m_rumpun_jabatan WHERE active='t' ORDER BY nama ASC ";
        $rumpunjabatan=DB::connection()->select($sqlrumpunjabatan);

        $sqllokasi="SELECT * FROM m_lokasi WHERE active=1 and sub_entitas=0  ORDER BY nama ASC ";
        $lokasi=DB::connection()->select($sqllokasi);

        $sqljabatan="SELECT * FROM m_jabatan WHERE active=1 ORDER BY nama ASC ";
        $jabatan=DB::connection()->select($sqljabatan);

        $sqlgrupjabatan="SELECT * FROM m_grupjabatan WHERE active=1 ORDER BY nama ASC ";
        $grupjabatan=DB::connection()->select($sqlgrupjabatan);

        $sqlpangkat="SELECT * FROM m_pangkat WHERE active=1 ORDER BY nama ASC ";
        $pangkat=DB::connection()->select($sqlpangkat);

        $sqlpangkat="SELECT a.*,e.nama as nmentitas, d.nama_directorat as nmdirectorat, c.nama_divisi as nmdivisi,b.nama as nmdepartemen FROM m_departemen a
        left join m_divisi b on b.m_divisi_id = a.m_divisi_id
        join m_divisi_new c on b.m_divisi_new_id = c.m_divisi_new_id
        join m_directorat d on d.m_directorat_id = c.m_directorat_id
        join m_lokasi e on e.m_lokasi_id = d.m_lokasi_id
        
        WHERE a.active=1 ORDER BY a.nama ASC ";
        $departemen=DB::connection()->select($sqlpangkat);

        $iduser=Auth::user()->id;
        $sqluser="SELECT p_recruitment.foto FROM users
left join p_karyawan on p_karyawan.user_id=users.id
left join p_recruitment on p_recruitment.p_recruitment_id=p_karyawan.p_recruitment_id
where users.id=$iduser";
        $user=DB::connection()->select($sqluser);

        return view('backend.jabatan.tambah_jabatan',compact('lokasi','jabatan','rumpunjabatan','grupjabatan','pangkat','user','departemen'));
    }

    public function simpan_jabatan(Request $request){
    	$lokasi = $request->get("lokasi");
       $sql="SELECT * FROM m_lokasi WHERE m_lokasi_id = $lokasi ";
       $data=DB::connection()->select($sql);
		$kode =	$data[0]->kode;
       $spasi = explode(' ',$request->get("nama"));
       for($i=0;$i<count($spasi);$i++){
	   	$kode .=ucwords($spasi[$i][0]);
	   }
	  // echo $kode;die;
       
        $idUser=Auth::user()->id;
        DB::connection()->table("m_jabatan")
            ->insert([
                "kode" => $kode,
                "nama" => $request->get("nama"),
                "job" => $request->get("job"),
                "keterangan" => $request->get("keterangan"),
                "job_deskripsi_indonesia" => $request->get("summernote"),
                "m_pangkat_id" => $request->get("pangkat"),
                "m_lokasi_id" => $request->get("lokasi"),
                "m_departemen_id" => $request->get("departement"),
                "active" => 1,
                "create_date" => date("Y-m-d"),
                "create_by" => $idUser,
            ]);

        return redirect()->route('be.jabatan')->with('success',' Jabatan Berhasil di input!');
    }

    public function edit_jabatan($id)
    {
        $sqldatajabatan="SELECT m_jabatan.*,m_lokasi.nama as nmlokasi,m_pangkat.nama as nmpangkat
                FROM m_jabatan
                LEFT JOIN m_lokasi on m_lokasi.m_lokasi_id=m_jabatan.m_lokasi_id
                LEFT JOIN m_pangkat on m_pangkat.m_pangkat_id=m_jabatan.m_pangkat_id
                WHERE 1=1 AND m_jabatan.active=1 AND m_jabatan.m_jabatan_id=$id
                order by m_jabatan.nama";
        $datajabatan=DB::connection()->select($sqldatajabatan);

        $sqlrumpunjabatan="SELECT * FROM m_rumpun_jabatan WHERE active='t' ORDER BY nama ASC ";
        $rumpunjabatan=DB::connection()->select($sqlrumpunjabatan);

        $sqllokasi="SELECT * FROM m_lokasi WHERE active=1 and sub_entitas=0 ORDER BY nama ASC ";
        $lokasi=DB::connection()->select($sqllokasi);

        $sqljabatan="SELECT * FROM m_jabatan WHERE active=1 ORDER BY nama ASC ";
        $jabatan=DB::connection()->select($sqljabatan);

        $sqlpangkat="SELECT * FROM m_pangkat WHERE active=1 ORDER BY nama ASC ";
        $pangkat=DB::connection()->select($sqlpangkat);

        $iduser=Auth::user()->id;
        $sqluser="SELECT p_recruitment.foto FROM users
left join p_karyawan on p_karyawan.user_id=users.id
left join p_recruitment on p_recruitment.p_recruitment_id=p_karyawan.p_recruitment_id
where users.id=$iduser";
        $user=DB::connection()->select($sqluser);

         $sqlpangkat="SELECT a.*,e.nama as nmentitas, d.nama_directorat as nmdirectorat, c.nama_divisi as nmdivisi,b.nama as nmdepartemen FROM m_departemen a
        left join m_divisi b on b.m_divisi_id = a.m_divisi_id
        join m_divisi_new c on b.m_divisi_new_id = c.m_divisi_new_id
        join m_directorat d on d.m_directorat_id = c.m_directorat_id
        join m_lokasi e on e.m_lokasi_id = d.m_lokasi_id
        
        WHERE a.active=1 ORDER BY a.nama ASC ";
        $departemen=DB::connection()->select($sqlpangkat);
        
        return view('backend.jabatan.edit_jabatan', compact('jabatan','lokasi','datajabatan','rumpunjabatan','pangkat','user','departemen'));
    }

    public function update_jabatan(Request $request, $id){
        $idUser=Auth::user()->id;
        DB::connection()->table("m_jabatan")
            ->where("m_jabatan_id",$id)
            ->update([
                "kode" => $request->get("kode"),
                "nama" => $request->get("nama"),
                "job" => $request->get("job"),
                "keterangan" => $request->get("keterangan"),
                "job_deskripsi_indonesia" => $request->get("summernote"),
                "m_pangkat_id" => $request->get("pangkat"),
                
                "m_departemen_id" => $request->get("departement"),
                "m_lokasi_id" => $request->get("lokasi"),
                "active" => 1,
                "update_date" => date("Y-m-d"),
                "update_by" => $idUser,
            ]);

        return redirect()->route('be.jabatan')->with('success',' Jabatan Berhasil di Ubah!');
    }

    public function hapus_jabatan($id)
    {
        $idUser=Auth::user()->id;
        DB::connection()->table("m_jabatan")
            ->where("m_jabatan_id",$id)
            ->update([
                "active"=>0,
                "update_date"=>date('Y-m-d'),
                "create_by" => $idUser,
            ]);

        return redirect()->back()->with('success',' Jabatan Berhasil di Hapus!');
    }
}

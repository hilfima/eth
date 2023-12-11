<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Auth;

class DepartemenController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function departemen()
    {
        $sqldept="SELECT m_departemen.*,m_divisi.nama as nmdivisi,m_lokasi.nama as nmentitas,m_directorat.*,m_divisi_new.* FROM m_departemen
        
        LEFT JOIN m_divisi on m_divisi.m_divisi_id=m_departemen.m_divisi_id
         left join m_divisi_new on m_divisi.m_divisi_new_id =  m_divisi_new.m_divisi_new_id
         left join m_directorat on m_directorat.m_directorat_id =  m_divisi_new.m_directorat_id
        left join m_lokasi on m_lokasi.m_lokasi_id =  m_directorat.m_lokasi_id
        
        WHERE 1=1 AND m_departemen.active=1 order by m_lokasi.nama,nama_directorat,nama_divisi,m_divisi.nama,m_departemen.nama";
        $dept=DB::connection()->select($sqldept);

        $iduser=Auth::user()->id;
        $sqluser="SELECT p_recruitment.foto FROM users
left join p_karyawan on p_karyawan.user_id=users.id
left join p_recruitment on p_recruitment.p_recruitment_id=p_karyawan.p_recruitment_id
where users.id=$iduser";
        $user=DB::connection()->select($sqluser);
        return view('backend.departemen.departemen',compact('dept','user'));
    }

    public function tambah_departemen()
    {
        $sqldivisi="SELECT *,m_divisi.nama, m_lokasi.nama as nama_entitas FROM m_divisi
                 left join m_divisi_new on m_divisi.m_divisi_new_id =  m_divisi_new.m_divisi_new_id
                 left join m_directorat on m_directorat.m_directorat_id =  m_divisi_new.m_directorat_id
                left join m_lokasi on m_lokasi.m_lokasi_id =  m_directorat.m_lokasi_id
                WHERE 1=1 AND m_divisi.active=1 order by m_divisi.nama";
        $divisi=DB::connection()->select($sqldivisi);

        $iduser=Auth::user()->id;
        $sqluser="SELECT p_recruitment.foto FROM users
left join p_karyawan on p_karyawan.user_id=users.id
left join p_recruitment on p_recruitment.p_recruitment_id=p_karyawan.p_recruitment_id
where users.id=$iduser";
        $user=DB::connection()->select($sqluser);
        return view('backend.departemen.tambah_departemen',compact('divisi','user'));
    }

    public function simpan_departemen(Request $request){
        $idUser=Auth::user()->id;
        DB::connection()->table("m_departemen")
            ->insert([
                "kode"=>($request->get("kode")),
                "nama"=>($request->get("nama")),
                "keterangan"=>($request->get("keterangan")),
                "m_divisi_id"=>($request->get("divisi")),
                "active"=>1,
                "create_by"=>$idUser,
                "create_date"=>date('Y-m-d'),
            ]);

        return redirect()->route('be.departemen')->with('success','Departemen Berhasil di input!');
    }

    public function edit_departemen($id)
    {
        $sqldepartemen="SELECT m_departemen.*,m_divisi.nama as nmdivisi FROM m_departemen
                LEFT JOIN m_divisi on m_divisi.m_divisi_id=m_departemen.m_divisi_id
                WHERE 1=1 AND m_departemen.active=1 and m_departemen.m_departemen_id=$id 
                order by m_departemen.nama";
        $departemen=DB::connection()->select($sqldepartemen);
        $sqldivisi="SELECT *,m_divisi.nama, m_lokasi.nama as nama_entitas,m_divisi.m_divisi_id FROM m_divisi
                 left join m_divisi_new on m_divisi.m_divisi_new_id =  m_divisi_new.m_divisi_new_id
                 left join m_directorat on m_directorat.m_directorat_id =  m_divisi_new.m_directorat_id
                left join m_lokasi on m_lokasi.m_lokasi_id =  m_directorat.m_lokasi_id
                WHERE 1=1 AND m_divisi.active=1 order by m_divisi.nama";
        $divisi=DB::connection()->select($sqldivisi);

        $iduser=Auth::user()->id;
        $sqluser="SELECT p_recruitment.foto FROM users
left join p_karyawan on p_karyawan.user_id=users.id
left join p_recruitment on p_recruitment.p_recruitment_id=p_karyawan.p_recruitment_id
where users.id=$iduser";
        $user=DB::connection()->select($sqluser);

        return view('backend.departemen.edit_departemen', compact('departemen','divisi','user'));
    }

    public function update_departemen(Request $request, $id){
        $idUser=Auth::user()->id;
        DB::connection()->table("m_departemen")
            ->where("m_departemen_id",$id)
            ->update([
                "kode"=>($request->get("kode")),
                "nama"=>($request->get("nama")),
                "keterangan"=>($request->get("keterangan")),
                "m_divisi_id"=>($request->get("divisi")),
                "update_date"=>date('Y-m-d'),
                "update_by"=>$idUser,
                "active"=>1,
            ]);

        return redirect()->route('be.departemen')->with('success','Departemen Berhasil di Ubah!');
    }

    public function hapus_departemen($id)
    {
        $idUser=Auth::user()->id;
        DB::connection()->table("m_departemen")
            ->where("m_departemen_id",$id)
            ->update([
                "active"=>0,
                "update_date"=>date('Y-m-d'),
                "update_by"=>$idUser,
            ]);

        return redirect()->back()->with('success','Departemen Berhasil di Hapus!');
    }
}

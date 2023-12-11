<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Auth;

class DivisiController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function divisi()
    {
        $iduser=Auth::user()->id;
        $sqluser="SELECT p_recruitment.foto FROM users
left join p_karyawan on p_karyawan.user_id=users.id
left join p_recruitment on p_recruitment.p_recruitment_id=p_karyawan.p_recruitment_id
where users.id=$iduser";
        $user=DB::connection()->select($sqluser);

        $sqldivisi="SELECT *,m_lokasi.nama as nama_entitas,m_divisi.nama FROM m_divisi
        
        left join m_divisi_new on m_divisi.m_divisi_new_id = m_divisi_new.m_divisi_new_id
        left join m_directorat on m_directorat.m_directorat_id =  m_divisi_new.m_directorat_id
        left join m_lokasi on m_lokasi.m_lokasi_id =  m_directorat.m_lokasi_id
            WHERE 1=1 AND m_divisi.active=1 order by m_lokasi.nama,nama_directorat,nama_divisi,m_divisi.nama,m_directorat";
        $divisi=DB::connection()->select($sqldivisi);
        return view('backend.divisi.divisi',compact('divisi','user'));
    }

    public function tambah_divisi()
    {
        $iduser=Auth::user()->id;
        $sqluser="SELECT p_recruitment.foto FROM users
            left join p_karyawan on p_karyawan.user_id=users.id
            left join p_recruitment on p_recruitment.p_recruitment_id=p_karyawan.p_recruitment_id
            where users.id=$iduser";
        $user=DB::connection()->select($sqluser);
        
        $sqldivisi="SELECT * FROM m_divisi_new
                left join m_directorat on m_directorat.m_directorat_id =  m_divisi_new.m_directorat_id
                left join m_lokasi on m_lokasi.m_lokasi_id =  m_directorat.m_lokasi_id
                WHERE 1=1 AND m_divisi_new.active=1 order by nama_divisi";
        $divisi=DB::connection()->select($sqldivisi);
        return view('backend.divisi.tambah_divisi',compact('user','divisi'));
    }

    public function simpan_divisi(Request $request){
        $idUser=Auth::user()->id;
        DB::connection()->table("m_divisi")
            ->insert([
                "nama"=>($request->get("nama")),
                "m_divisi_new_id"=>($request->get("divisi")),
                "created_by"=>$idUser,
                "created_at"=>date('Y-m-d'),
                "active"=>1
            ]);

        return redirect()->route('be.divisi')->with('success','Divisi Berhasil di input!');
    }

    public function edit_divisi($id)
    {
        $iduser=Auth::user()->id;
        $sqluser="SELECT p_recruitment.foto FROM users
left join p_karyawan on p_karyawan.user_id=users.id
left join p_recruitment on p_recruitment.p_recruitment_id=p_karyawan.p_recruitment_id
where users.id=$iduser";
        $user=DB::connection()->select($sqluser);

        $sqldivisi="select * from m_divisi
                  WHERE m_divisi_id=$id";
        $divisi=DB::connection()->select($sqldivisi);
$sqldivisi="SELECT * FROM m_divisi_new
left join m_directorat on m_directorat.m_directorat_id =  m_divisi_new.m_directorat_id
                left join m_lokasi on m_lokasi.m_lokasi_id =  m_directorat.m_lokasi_id
                WHERE 1=1 AND m_divisi_new.active=1 order by nama_divisi";
        $divisin=DB::connection()->select($sqldivisi);
        return view('backend.divisi.edit_divisi', compact('divisi','divisin'));
    }

    public function update_divisi(Request $request, $id){
        $idUser=Auth::user()->id;
        DB::connection()->table("m_divisi")
            ->where("m_divisi_id",$id)
            ->update([
                "nama"=>($request->get("nama")),
                "m_divisi_new_id"=>($request->get("divisi")),
                "updated_by"=>$idUser,
                "updated_at"=>date('Y-m-d'),
                "active"=>1,
            ]);

        return redirect()->route('be.divisi')->with('success','Divisi Berhasil di Ubah!');
    }

    public function hapus_divisi($id)
    {
        $idUser=Auth::user()->id;
        DB::connection()->table("m_divisi")
            ->where("m_divisi_id",$id)
            ->update([
                "updated_by"=>$idUser,
                "updated_at"=>date('Y-m-d'),
                "active"=>0
            ]);

        return redirect()->back()->with('success','Divisi Berhasil di Hapus!');
    }
}

<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Auth;

class Divisi_newController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function divisi_new()
    {
        $iduser=Auth::user()->id;
        $sqluser="SELECT p_recruitment.foto FROM users
left join p_karyawan on p_karyawan.user_id=users.id
left join p_recruitment on p_recruitment.p_recruitment_id=p_karyawan.p_recruitment_id
where users.id=$iduser";
        $user=DB::connection()->select($sqluser);

        $sqldivisi_new="SELECT * FROM m_divisi_new
            left join m_directorat on m_divisi_new.m_directorat_id = m_directorat.m_directorat_id
            left join m_lokasi on m_directorat.m_lokasi_id = m_lokasi.m_lokasi_id 
                WHERE 1=1 AND m_divisi_new.active=1 order by nama_divisi";
        $divisi_new=DB::connection()->select($sqldivisi_new);
        return view('backend.divisi_new.divisi_new',compact('divisi_new','user'));
    }

    public function tambah_divisi_new()
    {
        $iduser=Auth::user()->id;
        $sqluser="SELECT p_recruitment.foto FROM users
left join p_karyawan on p_karyawan.user_id=users.id
left join p_recruitment on p_recruitment.p_recruitment_id=p_karyawan.p_recruitment_id
where users.id=$iduser";
        $user=DB::connection()->select($sqluser);
        
        $sqldirectorat="SELECT * FROM m_directorat
                LEFT JOIN m_lokasi on m_directorat.m_lokasi_id = m_lokasi.m_lokasi_id
                WHERE 1=1 AND m_directorat.active=1 order by nama_directorat";
        $directorat =DB::connection()->select($sqldirectorat);
        
        return view('backend.divisi_new.tambah_divisi_new',compact('directorat'));
    }

    public function simpan_divisi_new(Request $request){
        $idUser=Auth::user()->id;
        DB::connection()->table("m_divisi_new")
            ->insert([
                "nama_divisi"=>($request->get("nama")),
                "m_directorat_id"=>($request->get("direktorat")),
                "create_by"=>$idUser,
                "create_date"=>date('Y-m-d'),
                "active"=>1
            ]);

        return redirect()->route('be.divisi_new')->with('success','Divisi Berhasil di input!');
    }

    public function edit_divisi_new($id)
    {
        $iduser=Auth::user()->id;
        $sqluser="SELECT p_recruitment.foto FROM users
left join p_karyawan on p_karyawan.user_id=users.id
left join p_recruitment on p_recruitment.p_recruitment_id=p_karyawan.p_recruitment_id
where users.id=$iduser";
        $user=DB::connection()->select($sqluser);

        $sqldivisi_new="select * from m_divisi_new
                  WHERE m_divisi_new_id=$id";
        $divisi_new=DB::connection()->select($sqldivisi_new);
 $sqldirectorat="SELECT * FROM m_directorat
                LEFT JOIN m_lokasi on m_directorat.m_lokasi_id = m_lokasi.m_lokasi_id
                WHERE 1=1 AND m_directorat.active=1 order by nama_directorat";
        $directorat =DB::connection()->select($sqldirectorat);
        return view('backend.divisi_new.edit_divisi_new', compact('divisi_new','directorat'));
    }

    public function update_divisi_new(Request $request, $id){
        $idUser=Auth::user()->id;
        DB::connection()->table("m_divisi_new")
            ->where("m_divisi_new_id",$id)
            ->update([
                "nama_divisi"=>($request->get("nama")),
                
                "m_directorat_id"=>($request->get("direktorat")),
                "update_by"=>$idUser,
                "update_date"=>date('Y-m-d'),
                "active"=>1,
            ]);

        return redirect()->route('be.divisi_new')->with('success','Divisi Berhasil di Ubah!');
    }

    public function hapus_divisi_new($id)
    {
        $idUser=Auth::user()->id;
        DB::connection()->table("m_divisi_new")
            ->where("m_divisi_new_id",$id)
            ->update([
                "update_by"=>$idUser,
                "update_date"=>date('Y-m-d'),
                "active"=>0
            ]);

        return redirect()->back()->with('success','Divisi Berhasil di Hapus!');
    }
}

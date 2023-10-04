<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Auth;

class DirectoratController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function directorat()
    {
        $iduser=Auth::user()->id;
        $sqluser="SELECT p_recruitment.foto FROM users
left join p_karyawan on p_karyawan.user_id=users.id
left join p_recruitment on p_recruitment.p_recruitment_id=p_karyawan.p_recruitment_id
where users.id=$iduser";
        $user=DB::connection()->select($sqluser);

        $sqldirectorat="SELECT * FROM m_directorat
                left join m_lokasi on m_lokasi.m_lokasi_id = m_directorat.m_lokasi_id
                WHERE 1=1 AND m_directorat.active=1 order by nama_directorat";
        $directorat=DB::connection()->select($sqldirectorat);
        return view('backend.directorat.directorat',compact('directorat','user'));
    }

    public function tambah_directorat()
    {
        $iduser=Auth::user()->id;
        $sqluser="SELECT p_recruitment.foto FROM users
left join p_karyawan on p_karyawan.user_id=users.id
left join p_recruitment on p_recruitment.p_recruitment_id=p_karyawan.p_recruitment_id
where users.id=$iduser";
        $user=DB::connection()->select($sqluser);
        $entitas = DB::connection()->select("select * from m_lokasi where active= 1 and sub_entitas=0");
        return view('backend.directorat.tambah_directorat',compact('entitas'));
    }

    public function simpan_directorat(Request $request){
        $idUser=Auth::user()->id;
        DB::connection()->table("m_directorat")
            ->insert([
                "nama_directorat"=>($request->get("nama")),
                "m_lokasi_id"=>($request->get("m_lokasi_id")),
                "create_by"=>$idUser,
                "create_date"=>date('Y-m-d'),
                "active"=>1
            ]);

        return redirect()->route('be.directorat')->with('success','Directorat Berhasil di input!');
    }

    public function edit_directorat($id)
    {
        $iduser=Auth::user()->id;
        $sqluser="SELECT p_recruitment.foto FROM users
left join p_karyawan on p_karyawan.user_id=users.id
left join p_recruitment on p_recruitment.p_recruitment_id=p_karyawan.p_recruitment_id
where users.id=$iduser";
        $user=DB::connection()->select($sqluser);

        $sqldirectorat="select * from m_directorat
                  WHERE m_directorat_id=$id";
        $directorat=DB::connection()->select($sqldirectorat);
        $entitas = DB::connection()->select("select * from m_lokasi where active= 1 and sub_entitas=0");
        return view('backend.directorat.edit_directorat', compact('directorat','user','entitas'));
    }

    public function update_directorat(Request $request, $id){
        $idUser=Auth::user()->id;
        DB::connection()->table("m_directorat")
            ->where("m_directorat_id",$id)
            ->update([
                "nama_directorat"=>($request->get("nama")),
                "m_lokasi_id"=>($request->get("m_lokasi_id")),
                "update_by"=>$idUser,
                "update_date"=>date('Y-m-d'),
                "active"=>1,
            ]);

        return redirect()->route('be.directorat')->with('success','Directorat Berhasil di Ubah!');
    }

    public function hapus_directorat($id)
    {
        $idUser=Auth::user()->id;
        DB::connection()->table("m_directorat")
            ->where("m_directorat_id",$id)
            ->update([
                "update_by"=>$idUser,
                "update_date"=>date('Y-m-d'),
                "active"=>0
            ]);

        return redirect()->back()->with('success','Directorat Berhasil di Hapus!');
    }
}

<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Auth;

class StatusPekerjaanController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }public function status_pekerjaan()
    {
        $iduser=Auth::user()->id;
        $sqluser="SELECT p_recruitment.foto FROM users
left join p_karyawan on p_karyawan.user_id=users.id
left join p_recruitment on p_recruitment.p_recruitment_id=p_karyawan.p_recruitment_id
where users.id=$iduser";
        $user=DB::connection()->select($sqluser);

        $sqlstatus_pekerjaan="SELECT * FROM m_status_pekerjaan
                WHERE 1=1 AND active=1 order by nama";
        $status_pekerjaan=DB::connection()->select($sqlstatus_pekerjaan);
        return view('backend.status_pekerjaan.status_pekerjaan',compact('status_pekerjaan','user'));
    }

    public function tambah_status_pekerjaan()
    {
        $iduser=Auth::user()->id;
        $sqluser="SELECT p_recruitment.foto FROM users
left join p_karyawan on p_karyawan.user_id=users.id
left join p_recruitment on p_recruitment.p_recruitment_id=p_karyawan.p_recruitment_id
where users.id=$iduser";
        $user=DB::connection()->select($sqluser);

        return view('backend.status_pekerjaan.tambah_status_pekerjaan',compact('user'));
    }

    public function simpan_status_pekerjaan(Request $request){
        $idUser=Auth::user()->id;
        DB::connection()->table("m_status_pekerjaan")
            ->insert([
                "nama"=>($request->get("nama")),
                "active"=>1
            ]);

        return redirect()->route('be.status_pekerjaan')->with('success','Status Pekerjaan Berhasil di input!');
    }

    public function edit_status_pekerjaan($id)
    {
        $sqlstatus_pekerjaan="select * from m_status_pekerjaan
                  WHERE m_status_pekerjaan_id=$id";
        $status_pekerjaan=DB::connection()->select($sqlstatus_pekerjaan);

        $iduser=Auth::user()->id;
        $sqluser="SELECT p_recruitment.foto FROM users
left join p_karyawan on p_karyawan.user_id=users.id
left join p_recruitment on p_recruitment.p_recruitment_id=p_karyawan.p_recruitment_id
where users.id=$iduser";
        $user=DB::connection()->select($sqluser);

        return view('backend.status_pekerjaan.edit_status_pekerjaan', compact('status_pekerjaan','user'));
    }

    public function update_status_pekerjaan(Request $request, $id){
        $idUser=Auth::user()->id;
        DB::connection()->table("m_status_pekerjaan")
            ->where("m_status_pekerjaan_id",$id)
            ->update([
                "nama"=>($request->get("nama")),
                "active"=>1,
            ]);

        return redirect()->route('be.status_pekerjaan')->with('success','Status Pekerjaan Berhasil di Ubah!');
    }

    public function hapus_status_pekerjaan($id)
    {
        $idUser=Auth::user()->id;
        DB::connection()->table("m_status_pekerjaan")
            ->where("m_status_pekerjaan_id",$id)
            ->update([
                "active"=>0
            ]);

        return redirect()->back()->with('success','Status Pekerjaan Berhasil di Hapus!');
    }
}

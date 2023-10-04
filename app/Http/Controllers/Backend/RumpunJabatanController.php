<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Auth;

class RumpunJabatanController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }public function rumpun_jabatan()
    {
        $iduser=Auth::user()->id;
        $sqluser="SELECT p_recruitment.foto FROM users
left join p_karyawan on p_karyawan.user_id=users.id
left join p_recruitment on p_recruitment.p_recruitment_id=p_karyawan.p_recruitment_id
where users.id=$iduser";
        $user=DB::connection()->select($sqluser);

        $sqlrumpun_jabatan="SELECT * FROM m_rumpun_jabatan
                WHERE 1=1 AND active='t' order by nama";
        $rumpun_jabatan=DB::connection()->select($sqlrumpun_jabatan);
        return view('backend.rumpun_jabatan.rumpun_jabatan',compact('rumpun_jabatan','user'));
    }

    public function tambah_rumpun_jabatan()
    {
        $iduser=Auth::user()->id;
        $sqluser="SELECT p_recruitment.foto FROM users
left join p_karyawan on p_karyawan.user_id=users.id
left join p_recruitment on p_recruitment.p_recruitment_id=p_karyawan.p_recruitment_id
where users.id=$iduser";
        $user=DB::connection()->select($sqluser);

        return view('backend.rumpun_jabatan.tambah_rumpun_jabatan',compact('user'));
    }

    public function simpan_rumpun_jabatan(Request $request){
        $idUser=Auth::user()->id;
        DB::connection()->table("m_rumpun_jabatan")
            ->insert([
                "nama"=>($request->get("nama")),
                "active"=>'t',
                "created_at"=>date('Y-m-d'),
            ]);

        return redirect()->route('be.rumpun_jabatan')->with('success','Rumpun Jabatan Berhasil di input!');
    }

    public function edit_rumpun_jabatan($id)
    {
        $sqlrumpun_jabatan="select * from m_rumpun_jabatan
                  WHERE m_rumpun_jabatan_id=$id";
        $rumpun_jabatan=DB::connection()->select($sqlrumpun_jabatan);

        $iduser=Auth::user()->id;
        $sqluser="SELECT p_recruitment.foto FROM users
left join p_karyawan on p_karyawan.user_id=users.id
left join p_recruitment on p_recruitment.p_recruitment_id=p_karyawan.p_recruitment_id
where users.id=$iduser";
        $user=DB::connection()->select($sqluser);

        return view('backend.rumpun_jabatan.edit_rumpun_jabatan', compact('rumpun_jabatan','user'));
    }

    public function update_rumpun_jabatan(Request $request, $id){
        $idUser=Auth::user()->id;
        DB::connection()->table("m_rumpun_jabatan")
            ->where("m_rumpun_jabatan_id",$id)
            ->update([
                "nama"=>($request->get("nama")),
                "updated_at"=>date('Y-m-d'),
                "active"=>'t',
            ]);

        return redirect()->route('be.rumpun_jabatan')->with('success','Rumpun Jabatan Berhasil di Ubah!');
    }

    public function hapus_rumpun_jabatan($id)
    {
        $idUser=Auth::user()->id;
        DB::connection()->table("m_rumpun_jabatan")
            ->where("m_rumpun_jabatan_id",$id)
            ->update([
                "active"=>'f',
                "updated_at"=>date('Y-m-d')
            ]);

        return redirect()->back()->with('success','Rumpun Jabatan Berhasil di Hapus!');
    }
}

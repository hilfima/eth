<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Auth;
use Hash;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }public function user()
    {
        $iduser=Auth::user()->id;
        $sqluser="SELECT p_recruitment.foto FROM users
left join p_karyawan on p_karyawan.user_id=users.id
left join p_recruitment on p_recruitment.p_recruitment_id=p_karyawan.p_recruitment_id
where users.id=$iduser";
        $user=DB::connection()->select($sqluser);

        $sqlusers="SELECT * FROM users
                WHERE 1=1 order by name";
        $users=DB::connection()->select($sqlusers);
        return view('backend.users.users',compact('users','user'));
    }

    public function tambah_user()
    {
       $sqlrole="SELECT * from m_role
                WHERE 1=1 and active = 1 ";
        $role=DB::connection()->select($sqlrole);
        $iduser=Auth::user()->id;
        $sqluser="SELECT p_recruitment.foto FROM users
left join p_karyawan on p_karyawan.user_id=users.id
left join p_recruitment on p_recruitment.p_recruitment_id=p_karyawan.p_recruitment_id
where users.id=$iduser";
        $user=DB::connection()->select($sqluser);

        $sqlusers="SELECT p_karyawan.*,m_departemen.nama as nmdept FROM p_karyawan
LEFT JOIN p_karyawan_pekerjaan on p_karyawan_pekerjaan.p_karyawan_id=p_karyawan.p_karyawan_id
LEFT JOIN m_departemen on m_departemen.m_departemen_id=p_karyawan_pekerjaan.m_departemen_id
                WHERE 1=1 and p_karyawan.p_karyawan_id IN(SELECT p_karyawan_id FROM p_karyawan WHERE active=1 )
                
                order by p_karyawan.nama";
        $users=DB::connection()->select($sqlusers);

        return view('backend.users.tambah_user',compact('user','users','role'));
    }

    public function simpan_user(Request $request){
        $idUser=Auth::user()->id;
        $id=$request->get("nama");
        $sqlidkar="SELECT * FROM p_karyawan WHERE p_karyawan_id=$id";
        //echo $sqlidkar;die;
        $datakar=DB::connection()->select($sqlidkar);
        $nama=$datakar[0]->nama;
        $username=$datakar[0]->nik;
        $email=$datakar[0]->email_perusahaan;

        DB::connection()->table("users")
            ->insert([
                "name"=>($nama),
                "username"=>($username),
                "email"=>($email),
                "password"=>Hash::make('123456'),
                "role"=>($request->get("role")),
                "updated_at"=>date('Y-m-d'),
                "active"=>1,
            ]);

        $sqlidpengguna="SELECT * FROM users WHERE name='".$nama."' ";
        $datapengguna=DB::connection()->select($sqlidpengguna);
        $idpengguna=$datapengguna[0]->id;

        DB::connection()->table("p_karyawan")
            ->where("p_karyawan_id",$id)
            ->update([
                "user_id"=>$idpengguna,
                "update_date" => date("Y-m-d"),
                "update_by" => $idUser,
            ]);

        return redirect()->route('be.user')->with('success','Pengguna Berhasil di input!');
    }

    public function edit_user($id)
    {
        $sqledituser="select * from users
                  WHERE id=$id";
        $edituser=DB::connection()->select($sqledituser);

        $sqlusers="SELECT * FROM p_karyawan
                WHERE 1=1 and p_karyawan.active=1 order by nama"; 
        $users=DB::connection()->select($sqlusers);

        $iduser=Auth::user()->id;
        $sqluser="SELECT p_recruitment.foto FROM users
left join p_karyawan on p_karyawan.user_id=users.id
left join p_recruitment on p_recruitment.p_recruitment_id=p_karyawan.p_recruitment_id
where users.id=$iduser";
        $user=DB::connection()->select($sqluser);
$sqlrole="SELECT * from m_role
                WHERE 1=1 and active = 1 ";
        $role=DB::connection()->select($sqlrole);
        return view('backend.users.edit_user', compact('user','edituser','users','role'));
    }

    public function update_user(Request $request, $idpengguna){
        $idUser=Auth::user()->id;
        $id=$request->get("nama");
        $sqlidkar="SELECT * FROM p_karyawan WHERE p_karyawan_id=$id";
        $datakar=DB::connection()->select($sqlidkar);
        $nama=$datakar[0]->nama;
        $username=$datakar[0]->nik;
        $email=$datakar[0]->email_perusahaan;

        DB::connection()->table("users")
            ->where("id",$idpengguna)
            ->update([
                "name"=>($nama),
                "username"=>($username),
                "email"=>($email),
                "password"=>Hash::make('123456'),
                "role"=>($request->get("role")),
                "updated_at"=>date('Y-m-d'),
                "active"=>1,
            ]);

        DB::connection()->table("p_karyawan")
            ->where("p_karyawan_id",$id)
            ->update([
                "user_id"=>$idpengguna,
                "update_date" => date("Y-m-d"),
                "update_by" => $idUser,
            ]);

        return redirect()->route('be.user')->with('success','Pengguna Berhasil di Ubah!');
    }

    public function hapus_user($id)
    {
        DB::connection()->table("users")
            ->where("id",$id)
            ->update([
                "active"=>0,
            ]);

        return redirect()->back()->with('success','Users Berhasil di Hapus!');
    }
}

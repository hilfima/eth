<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Auth;
use Hash;

class AkunController extends Controller
{
     public function __construct()
    {
        $this->middleware('auth');
    }
    public function akun()
    {
        $iduser=Auth::user()->id;
        $sqluser="SELECT p_recruitment.foto FROM users
left join p_karyawan on p_karyawan.user_id=users.id
left join p_recruitment on p_recruitment.p_recruitment_id=p_karyawan.p_recruitment_id
where users.id=$iduser";
        $users=DB::connection()->select($sqluser);

        $sqlusers="SELECT * FROM users
        left join m_role on m_role.m_role_id = users.role
                WHERE 1=1 and non_karyawan=1 order by name";
        $user=DB::connection()->select($sqlusers);
        return view('backend.akun.akun',compact('users','user'));
    }

    public function tambah_akun()
    {
       $sqlrole="SELECT * from m_role
                WHERE 1=1 and active = 1 ";
        $role=DB::connection()->select($sqlrole);
       $sqlrole="SELECT * from m_lokasi
                WHERE 1=1 and active = 1 and sub_entitas=0";
        $entitas=DB::connection()->select($sqlrole);
        $iduser=Auth::user()->id;

        return view('backend.akun.tambah_akun',compact('role','entitas'));
    }

    public function simpan_akun(Request $request){
        $entitas = "";
        $e = $request->entitas;
        if($request->entitas){
        for($i=0;$i<count($request->entitas);$i++){
        	$entitas .= $e[$i];
        	
        	$entitas .= ",";
        		
        	
        	
        }
        $entitas .= "-1";
        }
        DB::connection()->table("users")
            ->insert([
                "name"=>($request->get('name')),
                "username"=>($request->get('username')),
                "email"=>($request->get('email')),
                "password"=>Hash::make($request->get('password')),
                "role"=>($request->get("role")),
                "updated_at"=>date('Y-m-d'),
                "active"=>1,
                "non_karyawan"=>1,
                "user_entitas"=>$entitas,
            ]);

        

        return redirect()->route('be.akun')->with('success','Pengguna Berhasil di input!');
    }

    public function edit_akun($id)
    {
        $sqledituser="select * from users
                  WHERE id=$id";
        $edituser=DB::connection()->select($sqledituser);

        $sqlusers="SELECT * FROM p_karyawan
                WHERE 1=1 order by nama";
        $users=DB::connection()->select($sqlusers);

			$sqlrole="SELECT * from m_role
                WHERE 1=1 and active = 1 ";
        $role=DB::connection()->select($sqlrole);
       $sqlrole="SELECT * from m_lokasi
                WHERE 1=1 and active = 1 and sub_entitas=0";
        $entitas=DB::connection()->select($sqlrole);
        return view('backend.akun.edit_akun', compact('edituser','users','role','entitas','id'));
    }

    public function update_akun(Request $request, $idpengguna){
        $idUser=Auth::user()->id;
       
		 $entitas = "";
        $e = $request->entitas;
        if($e){
        for($i=0;$i<count($request->entitas);$i++){
        	$entitas .= $e[$i];
        	
        	$entitas .= ",";
        		
        	
        	
        }
            
        
        $entitas .= "-1";
        }
        DB::connection()->table("users")
            ->where("id",$idpengguna)
            ->update([
                "name"=>($request->get('name')),
                "username"=>($request->get('username')),
                "email"=>($request->get('email')),
                
                "role"=>($request->get("role")),
                "updated_at"=>date('Y-m-d H:i:s'),
                
                "user_entitas"=>$entitas,
            ]);
            
            if($request->get('password')){
            	
             DB::connection()->table("users")
	            ->where("id",$idpengguna)
	            ->update([
	                "password"=>Hash::make($request->get('password')),
	            ]);
            }


        return redirect()->route('be.akun')->with('success','Pengguna Berhasil di Ubah!');
    }

    public function hapus_akun($id)
    {
        DB::connection()->table("users")
            ->where("id",$id)
            ->delete();

        return redirect()->back()->with('success','Users Berhasil di Hapus!');
    }
}

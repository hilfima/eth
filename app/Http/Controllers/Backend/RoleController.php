<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Auth;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }public function role()
    {
       
        $sqlrole="SELECT * from m_role
                WHERE 1=1 and active = 1 ";
        $Role=DB::connection()->select($sqlrole);
        return view('backend.role.role',compact('Role'));
    }

    public function tambah_role()
    {
       $sql="SELECT * from m_lokasi
       
                WHERE 1=1  and active = 1 ";
        $lokasi=DB::connection()->select($sql); die;
                $id = '';
                $type = 'simpan_role';
                $data['nama']='';
                $data['keterangan']='';
        return view('backend.role.tambah_role',compact('id','data','type'));
    }

    public function simpan_role(Request $request){
    	
	  // echo $kode;die;
         DB::connection()->table("m_role")
            ->insert([
                "nama_role" => $request->get("nama"),
                "keterangan" => $request->get("keterangan"),
                
            ]);

        return redirect()->route('be.role')->with('success',' role Berhasil di input!');
    }

    public function edit_role($id)
    {
       

                $type = 'update_role';
        $sqlrole="SELECT * FROM m_role WHERE active=1 and m_role_id = $id  ";
        $role=DB::connection()->select($sqlrole);
		$data['nama']=$role[0]->nama_role;
        $data['keterangan']=$role[0]->keterangan;
        
        return view('backend.role.tambah_role', compact('data','id','type'));
    }

    public function update_role(Request $request, $id){
        $idUser=Auth::user()->id;
        DB::connection()->table("m_role")
            ->where("m_role_id",$id)
            ->update([
               "nama_role" => $request->get("nama"),
                "keterangan" => $request->get("keterangan"),
            ]);

        return redirect()->route('be.role')->with('success',' role Berhasil di Ubah!');
    }

    public function hapus_role($id)
    {
        $idUser=Auth::user()->id;
        DB::connection()->table("m_role")
            ->where("m_role_id",$id)
            ->update([
                "active"=>0,
               
            ]);

        return redirect()->back()->with('success',' role Berhasil di Hapus!');
    }
}

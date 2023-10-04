<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Auth;

class RMIBController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }public function rmib()
    {
        $sqlrmib="SELECT a.*, b.nama as nmjk FROM m_rmib a
                LEFT JOIN m_jenis_kelamin b on b.m_jenis_kelamin_id=a.m_jk_id
                WHERE 1=1 and a.active=1 order by a.grup";
        $rmib=DB::connection()->select($sqlrmib);

        $iduser=Auth::user()->id;
        $sqluser="SELECT p_recruitment.foto FROM users
left join p_karyawan on p_karyawan.user_id=users.id
left join p_recruitment on p_recruitment.p_recruitment_id=p_karyawan.p_recruitment_id
where users.id=$iduser";
        $user=DB::connection()->select($sqluser);

        return view('backend.rmib.rmib',compact('rmib','user'));
    }

    public function tambah_rmib()
    {
        $iduser=Auth::user()->id;
        $sqluser="SELECT p_recruitment.foto FROM users
left join p_karyawan on p_karyawan.user_id=users.id
left join p_recruitment on p_recruitment.p_recruitment_id=p_karyawan.p_recruitment_id
where users.id=$iduser";
        $user=DB::connection()->select($sqluser);

        $sqljk="SELECT * FROM m_jenis_kelamin WHERE active=1 ORDER BY nama ASC ";
        $jk=DB::connection()->select($sqljk);

        return view('backend.rmib.tambah_rmib',compact('user','jk'));
    }

    public function simpan_rmib(Request $request){
        DB::beginTransaction();
        try{
            $idUser=Auth::user()->id;
            DB::connection()->table("m_rmib")
                ->insert([
                    "nama"=>($request->get("nama")),
                    "m_jk_id"=>($request->get("m_jk_id")),
                    "active"=>1,
                    "urutan"=>($request->get("urutan")),
                    "grup"=>($request->get("grup")),
                    "created_by"=>$idUser,
                    "created_at" => date("Y-m-d H:i:s")
                ]);
            DB::commit();
            return redirect()->back()->with('success','Data RMIB Berhasil di input!');
        }
        catch(\Exeception $e){
            DB::rollback();
            return redirect()->back()->with('error',$e);
        }

    }

    public function edit_rmib($id)
    {
        $sqlrmib="SELECT * FROM m_rmib
                WHERE m_rmib_id=$id";
        $rmib=DB::connection()->select($sqlrmib);

        $iduser=Auth::user()->id;
        $sqluser="SELECT p_recruitment.foto FROM users
left join p_karyawan on p_karyawan.user_id=users.id
left join p_recruitment on p_recruitment.p_recruitment_id=p_karyawan.p_recruitment_id
where users.id=$iduser";
        $user=DB::connection()->select($sqluser);

        $sqljk="SELECT * FROM m_jenis_kelamin WHERE active=1 ORDER BY nama ASC ";
        $jk=DB::connection()->select($sqljk);

        return view('backend.rmib.edit_rmib', compact('rmib','user','jk'));
    }

    public function update_rmib(Request $request, $id){
        DB::beginTransaction();
        try{
            $idUser=Auth::user()->id;
            DB::connection()->table("m_rmib")
                ->where("m_rmib_id",$id)
                ->update([
                    "nama"=>($request->get("nama")),
                    "m_jk_id"=>($request->get("m_jk_id")),
                    "active"=>1,
                    "urutan"=>($request->get("urutan")),
                    "grup"=>($request->get("grup")),
                    "updated_by"=>$idUser,
                    "updated_at" => date("Y-m-d H:i:s")
                ]);
            DB::commit();
            return redirect()->route('be.rmib')->with('success','Data RMIB Berhasil di Ubah!');
        }
        catch(\Exeception $e){
            DB::rollback();
            return redirect()->back()->with('error',$e);
        }
    }

    public function hapus_rmib($id)
    {
        DB::beginTransaction();
        try{
            DB::connection()->table("m_rmib")
                ->where("m_rmib_id",$id)
                ->delete();
            DB::commit();
            return redirect()->route('be.rmib')->with('success','Data RMIB Berhasil di Hapus!');
        }
        catch(\Exeception $e){
            DB::rollback();
            return redirect()->back()->with('error',$e);
        }
    }
}

<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Auth;

class QouteController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }public function qoute()
    {
        $sqlqoute="SELECT * FROM m_qoute
                WHERE 1=1 and active=1 order by tgl_awal desc";
        $Qoute=DB::connection()->select($sqlqoute);

        $iduser=Auth::user()->id;
        $sqluser="SELECT p_recruitment.foto FROM users
left join p_karyawan on p_karyawan.user_id=users.id
left join p_recruitment on p_recruitment.p_recruitment_id=p_karyawan.p_recruitment_id
where users.id=$iduser";
        $user=DB::connection()->select($sqluser);

        return view('backend.qoute.qoute',compact('Qoute','user'));
    }

    public function tambah_qoute()
    {
       
        return view('backend.qoute.tambah_qoute');
    }

    public function simpan_qoute(Request $request){
        DB::beginTransaction();
        try{
            $idUser=Auth::user()->id;
            DB::connection()->table("m_qoute")
                ->insert([
                    "qoute"=>($request->get("deskripsi_qoute")),
                   
                    "tgl_awal"=>date('Y-m-d',strtotime($request->get("tgl_posting"))),
                    "tgl_akhir"=>date('Y-m-d',strtotime($request->get("tgl_posting_akhir"))),
                  
                    "create_by"=>$idUser,
                    "create_date" => date("Y-m-d H:i:s")
                ]);
            DB::commit();
            return redirect()->route('be.qoute')->with('success','Qoute Berhasil di input!');
        }
        catch(\Exeception $e){
            DB::rollback();
            return redirect()->back()->with('error',$e);
        }

    }

    public function edit_qoute($id)
    {
        $sqlqoute="SELECT * FROM m_qoute
                WHERE m_qoute_id=$id";
        $qoute=DB::connection()->select($sqlqoute);
		$Qoute = $qoute;
        $iduser=Auth::user()->id;
        $sqluser="SELECT p_recruitment.foto FROM users
left join p_karyawan on p_karyawan.user_id=users.id
left join p_recruitment on p_recruitment.p_recruitment_id=p_karyawan.p_recruitment_id
where users.id=$iduser";
        $user=DB::connection()->select($sqluser);

        return view('backend.qoute.edit_qoute', compact('qoute','user','Qoute'));
    }

    public function update_qoute(Request $request, $id){
        DB::beginTransaction();
        try{
            $idUser=Auth::user()->id;
            DB::connection()->table("m_qoute")
                ->where("m_qoute_id",$id)
                ->update([
                    "qoute"=>($request->get("deskripsi_Qoute")),
                   
                    "tgl_awal"=>date('Y-m-d',strtotime($request->get("tgl_posting"))),
                  
                    "update_by"=>$idUser,
                    "update_date" => date("Y-m-d H:i:s")
                ]);
            DB::commit();
            return redirect()->route('be.qoute')->with('success','qoute Berhasil di Ubah!');
        }
        catch(\Exeception $e){
            DB::rollback();
            return redirect()->back()->with('error',$e);
        }
    }

    public function hapus_qoute($id)
    {
        DB::beginTransaction();
        try{
            DB::connection()->table("m_qoute")
                ->where("m_qoute_id",$id)
                ->delete();
            DB::commit();
            return redirect()->route('be.qoute')->with('success','qoute Berhasil di Hapus!');
        }
        catch(\Exeception $e){
            DB::rollback();
            return redirect()->back()->with('error',$e);
        }
    }
}

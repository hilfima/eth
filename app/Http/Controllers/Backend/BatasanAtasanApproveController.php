<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Auth;

class BatasanAtasanApproveController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }public function batasan_atasan_approve()
    {
        $sqlbatasan_atasan_approve="SELECT * FROM m_batasan_atasan_approve
                WHERE 1=1 and active=1 ";
        $batasan_atasan_approve=DB::connection()->select($sqlbatasan_atasan_approve);

        $iduser=Auth::user()->id;
        $sqluser="SELECT p_recruitment.foto FROM users
left join p_karyawan on p_karyawan.user_id=users.id
left join p_recruitment on p_recruitment.p_recruitment_id=p_karyawan.p_recruitment_id
where users.id=$iduser";
        $user=DB::connection()->select($sqluser);

        return view('backend.batasan_atasan_approve.batasan_atasan_approve',compact('batasan_atasan_approve','user'));
    }

    public function tambah_batasan_atasan_approve()
    {
        $iduser=Auth::user()->id;
        $sqluser="SELECT p_recruitment.foto FROM users
left join p_karyawan on p_karyawan.user_id=users.id
left join p_recruitment on p_recruitment.p_recruitment_id=p_karyawan.p_recruitment_id
where users.id=$iduser";
        $user=DB::connection()->select($sqluser);
        return view('backend.batasan_atasan_approve.tambah_batasan_atasan_approve',compact('user'));
    }

    public function simpan_batasan_atasan_approve(Request $request){
        DB::beginTransaction();
        try{
            $idUser=Auth::user()->id;
            DB::connection()->table("m_batasan_atasan_approve")
                ->insert($request->data);
            DB::commit();
            return redirect()->route('be.batasan_atasan_approve')->with('success','Batas Pengajuan Berhasil di input!');
        }
        catch(\Exeception $e){
            DB::rollback();
            return redirect()->back()->with('error',$e);
        }

    }

    public function edit_batasan_atasan_approve($id)
    {
        $sqlbatasan_atasan_approve="SELECT * FROM m_batasan_atasan_approve
                WHERE m_batasan_atasan_approve_id=$id";
        $batasan_atasan_approve=DB::connection()->select($sqlbatasan_atasan_approve);

        $iduser=Auth::user()->id;
        $sqluser="SELECT p_recruitment.foto FROM users
left join p_karyawan on p_karyawan.user_id=users.id
left join p_recruitment on p_recruitment.p_recruitment_id=p_karyawan.p_recruitment_id
where users.id=$iduser";
        $user=DB::connection()->select($sqluser);

        return view('backend.batasan_atasan_approve.edit_batasan_atasan_approve', compact('batasan_atasan_approve','user'));
    }

    public function update_batasan_atasan_approve(Request $request, $id){
        DB::beginTransaction();
        try{
            $idUser=Auth::user()->id;
            DB::connection()->table("m_batasan_atasan_approve")
                ->where("m_batasan_atasan_approve_id",$id)
                ->update([
                    "judul"=>($request->get("judul")),
                    "deskripsi"=>($request->get("deskripsi_batasan_atasan_approve")),
                    "tgl_posting"=>date('Y-m-d',strtotime($request->get("tgl_posting"))),
                    "tgl_posting_akhir"=>date('Y-m-d',strtotime($request->get("tgl_posting_akhir"))),
                    "update_by"=>$idUser,
                    "update_date" => date("Y-m-d H:i:s")
                ]);
            DB::commit();
            return redirect()->route('be.batasan_atasan_approve')->with('success','Jenis_izin Berhasil di Ubah!');
        }
        catch(\Exeception $e){
            DB::rollback();
            return redirect()->back()->with('error',$e);
        }
    }

    public function hapus_batasan_atasan_approve($id)
    {
        DB::beginTransaction();
        try{
            DB::connection()->table("m_batasan_atasan_approve")
                ->where("m_batasan_atasan_approve_id",$id)
                ->delete();
            DB::commit();
            return redirect()->route('be.batasan_atasan_approve')->with('success','Jenis_izin Berhasil di Hapus!');
        }
        catch(\Exeception $e){
            DB::rollback();
            return redirect()->back()->with('error',$e);
        }
    }
}

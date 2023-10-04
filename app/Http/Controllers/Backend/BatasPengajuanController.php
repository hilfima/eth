<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Auth;

class BatasPengajuanController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }public function batas_pengajuan()
    {
        $sqlbatas_pengajuan="SELECT * FROM m_batas_pengajuan
                WHERE 1=1 and active=1 ";
        $batas_pengajuan=DB::connection()->select($sqlbatas_pengajuan);

        $iduser=Auth::user()->id;
        $sqluser="SELECT p_recruitment.foto FROM users
left join p_karyawan on p_karyawan.user_id=users.id
left join p_recruitment on p_recruitment.p_recruitment_id=p_karyawan.p_recruitment_id
where users.id=$iduser";
        $user=DB::connection()->select($sqluser);

        return view('backend.batas_pengajuan.batas_pengajuan',compact('batas_pengajuan','user'));
    }

    public function tambah_batas_pengajuan()
    {
        $iduser=Auth::user()->id;
        $sqluser="SELECT p_recruitment.foto FROM users
left join p_karyawan on p_karyawan.user_id=users.id
left join p_recruitment on p_recruitment.p_recruitment_id=p_karyawan.p_recruitment_id
where users.id=$iduser";
        $user=DB::connection()->select($sqluser);
        return view('backend.batas_pengajuan.tambah_batas_pengajuan',compact('user'));
    }

    public function simpan_batas_pengajuan(Request $request){
        DB::beginTransaction();
        try{
            $idUser=Auth::user()->id;
            DB::connection()->table("m_batas_pengajuan")
                ->insert($request->data);
            DB::commit();
            return redirect()->route('be.batas_pengajuan')->with('success','Batas Pengajuan Berhasil di input!');
        }
        catch(\Exeception $e){
            DB::rollback();
            return redirect()->back()->with('error',$e);
        }

    }

    public function edit_batas_pengajuan($id)
    {
        $sqlbatas_pengajuan="SELECT * FROM m_batas_pengajuan
                WHERE m_batas_pengajuan_id=$id";
        $batas_pengajuan=DB::connection()->select($sqlbatas_pengajuan);

        $iduser=Auth::user()->id;
        $sqluser="SELECT p_recruitment.foto FROM users
left join p_karyawan on p_karyawan.user_id=users.id
left join p_recruitment on p_recruitment.p_recruitment_id=p_karyawan.p_recruitment_id
where users.id=$iduser";
        $user=DB::connection()->select($sqluser);

        return view('backend.batas_pengajuan.edit_batas_pengajuan', compact('batas_pengajuan','user'));
    }

    public function update_batas_pengajuan(Request $request, $id){
        DB::beginTransaction();
        try{
            $idUser=Auth::user()->id;
            DB::connection()->table("m_batas_pengajuan")
                ->where("m_batas_pengajuan_id",$id)
                ->update([
                    "judul"=>($request->get("judul")),
                    "deskripsi"=>($request->get("deskripsi_batas_pengajuan")),
                    "tgl_posting"=>date('Y-m-d',strtotime($request->get("tgl_posting"))),
                    "tgl_posting_akhir"=>date('Y-m-d',strtotime($request->get("tgl_posting_akhir"))),
                    "update_by"=>$idUser,
                    "update_date" => date("Y-m-d H:i:s")
                ]);
            DB::commit();
            return redirect()->route('be.batas_pengajuan')->with('success','Jenis_izin Berhasil di Ubah!');
        }
        catch(\Exeception $e){
            DB::rollback();
            return redirect()->back()->with('error',$e);
        }
    }

    public function hapus_batas_pengajuan($id)
    {
        DB::beginTransaction();
        try{
            DB::connection()->table("m_batas_pengajuan")
                ->where("m_batas_pengajuan_id",$id)
                ->delete();
            DB::commit();
            return redirect()->route('be.batas_pengajuan')->with('success','Jenis_izin Berhasil di Hapus!');
        }
        catch(\Exeception $e){
            DB::rollback();
            return redirect()->back()->with('error',$e);
        }
    }
}

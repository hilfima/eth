<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Auth;

class JenisIzinController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }public function jenis_izin()
    {
        $sqljenis_izin="SELECT * FROM m_jenis_ijin
        left join m_batas_pengajuan mbp on m_jenis_ijin.m_batas_pengajuan_id = mbp.m_batas_pengajuan_id
                WHERE 1=1 and m_jenis_ijin.active=1 ";
        $jenis_izin=DB::connection()->select($sqljenis_izin);

        $iduser=Auth::user()->id;
        $sqluser="SELECT p_recruitment.foto FROM users
left join p_karyawan on p_karyawan.user_id=users.id
left join p_recruitment on p_recruitment.p_recruitment_id=p_karyawan.p_recruitment_id
where users.id=$iduser";
        $user=DB::connection()->select($sqluser);

        return view('backend.jenis_izin.jenis_izin',compact('jenis_izin','user'));
    }

    public function tambah_jenis_izin()
    {
        $iduser=Auth::user()->id;
        $sqluser="SELECT p_recruitment.foto FROM users
left join p_karyawan on p_karyawan.user_id=users.id
left join p_recruitment on p_recruitment.p_recruitment_id=p_karyawan.p_recruitment_id
where users.id=$iduser";
        $user=DB::connection()->select($sqluser);
        $sqljenis_izin="SELECT * FROM m_batas_pengajuan
                WHERE 1=1 and active=1 ";
        $batas_pengajuan=DB::connection()->select($sqljenis_izin);
        $sqljenis_izin="SELECT * FROM m_batasan_atasan_approve
                WHERE 1=1 and active=1 ";
        $batasan_atasan_approve=DB::connection()->select($sqljenis_izin);
        return view('backend.jenis_izin.tambah_jenis_izin',compact('user','batas_pengajuan','batasan_atasan_approve'));
    }

    public function simpan_jenis_izin(Request $request){
        DB::beginTransaction();
        try{
            $idUser=Auth::user()->id;
            DB::connection()->table("m_jenis_ijin")
                ->insert([
                    "judul"=>($request->get("judul")),
                    "deskripsi"=>str_ireplace(array('Powered By','Froala Editor','href="https://www.froala.com/wysiwyg-editor'),array('','','href="#"'),$request->get("deskripsi_jenis_izin")),
                    "tgl_posting"=>date('Y-m-d',strtotime($request->get("tgl_posting"))),
                    "tgl_posting_akhir"=>date('Y-m-d',strtotime($request->get("tgl_posting_akhir"))),
                    "create_by"=>$idUser,
                    "create_date" => date("Y-m-d H:i:s")
                ]);
            DB::commit();
            return redirect()->route('be.jenis_izin')->with('success','Jenis_izin Berhasil di input!');
        }
        catch(\Exeception $e){
            DB::rollback();
            return redirect()->back()->with('error',$e);
        }

    }

    public function edit_jenis_izin($id)
    {
        $sqljenis_izin="SELECT * FROM m_jenis_ijin
                WHERE m_jenis_ijin_id=$id";
        $jenis_izin=DB::connection()->select($sqljenis_izin);
        $sqljenis_izin="SELECT * FROM m_batas_pengajuan
                WHERE 1=1 and active=1 ";
        $batas_pengajuan=DB::connection()->select($sqljenis_izin);

        $iduser=Auth::user()->id;
        $sqluser="SELECT p_recruitment.foto FROM users
left join p_karyawan on p_karyawan.user_id=users.id
left join p_recruitment on p_recruitment.p_recruitment_id=p_karyawan.p_recruitment_id
where users.id=$iduser";
        $user=DB::connection()->select($sqluser);

        return view('backend.jenis_izin.edit_jenis_izin', compact('jenis_izin','id','batas_pengajuan'));
    }

    public function update_jenis_izin(Request $request, $id){
        DB::beginTransaction();
        try{
            $idUser=Auth::user()->id;
            DB::connection()->table("m_jenis_ijin")
                ->where("m_jenis_ijin_id",$id)
                ->update($request->data);
            DB::commit();
            return redirect()->route('be.jenis_izin')->with('success','Jenis_izin Berhasil di Ubah!');
        }
        catch(\Exeception $e){
            DB::rollback();
            return redirect()->back()->with('error',$e);
        }
    }

    public function hapus_jenis_izin($id)
    {
        DB::beginTransaction();
        try{
            DB::connection()->table("m_jenis_ijin")
                ->where("m_jenis_ijin_id",$id)
                ->delete();
            DB::commit();
            return redirect()->route('be.jenis_izin')->with('success','Jenis_izin Berhasil di Hapus!');
        }
        catch(\Exeception $e){
            DB::rollback();
            return redirect()->back()->with('error',$e);
        }
    }
}

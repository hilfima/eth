<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Auth;

class MasterJamKerjaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }public function masterjamkerja()
    {
        $sqlmasterjamkerja="SELECT * FROM m_jamkerja_reguler
                WHERE 1=1 and active=1 ";
        $masterjamkerja=DB::connection()->select($sqlmasterjamkerja);


        return view('backend.masterjamkerja.masterjamkerja',compact('masterjamkerja'));
    }

    public function tambah_masterjamkerja()
    {
        $iduser=Auth::user()->id;
        $sqluser="SELECT p_recruitment.foto FROM users
left join p_karyawan on p_karyawan.user_id=users.id
left join p_recruitment on p_recruitment.p_recruitment_id=p_karyawan.p_recruitment_id
where users.id=$iduser";
        $user=DB::connection()->select($sqluser);
        return view('backend.masterjamkerja.tambah_masterjamkerja',compact('user'));
    }

    public function simpan_masterjamkerja(Request $request){
        DB::beginTransaction();
        try{
            $idUser=Auth::user()->id;
            $data['m_list_seksi_id'] =implode(',',$request->list_seksi);
            DB::connection()->table("m_jamkerja_reguler")
                ->insert($request->data);
            DB::commit();
            return redirect()->route('be.masterjamkerja')->with('success','Batas Pengajuan Berhasil di input!');
        } 
        catch(\Exeception $e){
            DB::rollback();
            return redirect()->back()->with('error',$e);
        }

    }

    public function edit_masterjamkerja($id)
    {
        $sqlmasterjamkerja="SELECT * FROM m_jamkerja_reguler
                WHERE m_jamkerja_reguler_id=$id";
        $masterjamkerja=DB::connection()->select($sqlmasterjamkerja);

        $iduser=Auth::user()->id;
        $sqluser="SELECT p_recruitment.foto FROM users
left join p_karyawan on p_karyawan.user_id=users.id
left join p_recruitment on p_recruitment.p_recruitment_id=p_karyawan.p_recruitment_id
where users.id=$iduser";
        $user=DB::connection()->select($sqluser);

        return view('backend.masterjamkerja.edit_masterjamkerja', compact('masterjamkerja','user'));
    }

    public function update_masterjamkerja(Request $request, $id){
        DB::beginTransaction();
        try{
            $idUser=Auth::user()->id;
            DB::connection()->table("m_jamkerja_reguler")
                ->where("m_jamkerja_reguler_id",$id)
                ->update([
                    "judul"=>($request->get("judul")),
                    "deskripsi"=>($request->get("deskripsi_masterjamkerja")),
                    "tgl_posting"=>date('Y-m-d',strtotime($request->get("tgl_posting"))),
                    "tgl_posting_akhir"=>date('Y-m-d',strtotime($request->get("tgl_posting_akhir"))),
                    "update_by"=>$idUser,
                    "update_date" => date("Y-m-d H:i:s")
                ]);
            DB::commit();
            return redirect()->route('be.masterjamkerja')->with('success','Jenis_izin Berhasil di Ubah!');
        }
        catch(\Exeception $e){
            DB::rollback();
            return redirect()->back()->with('error',$e);
        }
    }

    public function hapus_masterjamkerja($id)
    {
        DB::beginTransaction();
        try{
            DB::connection()->table("m_jamkerja_reguler")
                ->where("m_jamkerja_reguler_id",$id)
                ->delete();
            DB::commit();
            return redirect()->route('be.masterjamkerja')->with('success','Jenis_izin Berhasil di Hapus!');
        }
        catch(\Exeception $e){
            DB::rollback();
            return redirect()->back()->with('error',$e);
        }
    }
}

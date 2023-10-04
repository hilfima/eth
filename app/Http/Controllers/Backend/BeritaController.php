<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Auth;

class BeritaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }public function berita()
    {
        $sqlberita="SELECT * FROM hr_care
                WHERE 1=1 and active=1 order by tgl_posting desc";
        $berita=DB::connection()->select($sqlberita);

        $iduser=Auth::user()->id;
        $sqluser="SELECT p_recruitment.foto FROM users
left join p_karyawan on p_karyawan.user_id=users.id
left join p_recruitment on p_recruitment.p_recruitment_id=p_karyawan.p_recruitment_id
where users.id=$iduser";
        $user=DB::connection()->select($sqluser);

        return view('backend.berita.berita',compact('berita','user'));
    }

    public function tambah_berita()
    {
        $iduser=Auth::user()->id;
        $sqluser="SELECT p_recruitment.foto FROM users
left join p_karyawan on p_karyawan.user_id=users.id
left join p_recruitment on p_recruitment.p_recruitment_id=p_karyawan.p_recruitment_id
where users.id=$iduser";
        $user=DB::connection()->select($sqluser);
        return view('backend.berita.tambah_berita',compact('user'));
    }

    public function simpan_berita(Request $request){
        DB::beginTransaction();
        try{
            $idUser=Auth::user()->id;
            DB::connection()->table("hr_care")
                ->insert([
                    "judul"=>($request->get("judul")),
                    "deskripsi"=>str_ireplace(array('Powered By','Froala Editor','href="https://www.froala.com/wysiwyg-editor'),array('','','href="#"'),$request->get("deskripsi_berita")),
                    "tgl_posting"=>date('Y-m-d',strtotime($request->get("tgl_posting"))),
                    "tgl_posting_akhir"=>date('Y-m-d',strtotime($request->get("tgl_posting_akhir"))),
                    "create_by"=>$idUser,
                    "create_date" => date("Y-m-d H:i:s")
                ]);
            DB::commit();
            return redirect()->route('be.berita')->with('success','Berita Berhasil di input!');
        }
        catch(\Exeception $e){
            DB::rollback();
            return redirect()->back()->with('error',$e);
        }

    }

    public function edit_berita($id)
    {
        $sqlberita="SELECT * FROM hr_care
                WHERE hr_care_id=$id";
        $berita=DB::connection()->select($sqlberita);

        $iduser=Auth::user()->id;
        $sqluser="SELECT p_recruitment.foto FROM users
left join p_karyawan on p_karyawan.user_id=users.id
left join p_recruitment on p_recruitment.p_recruitment_id=p_karyawan.p_recruitment_id
where users.id=$iduser";
        $user=DB::connection()->select($sqluser);

        return view('backend.berita.edit_berita', compact('berita','user'));
    }

    public function update_berita(Request $request, $id){
        DB::beginTransaction();
        try{
            $idUser=Auth::user()->id;
            DB::connection()->table("hr_care")
                ->where("hr_care_id",$id)
                ->update([
                    "judul"=>($request->get("judul")),
                    "deskripsi"=>($request->get("deskripsi_berita")),
                    "tgl_posting"=>date('Y-m-d',strtotime($request->get("tgl_posting"))),
                    "tgl_posting_akhir"=>date('Y-m-d',strtotime($request->get("tgl_posting_akhir"))),
                    "update_by"=>$idUser,
                    "update_date" => date("Y-m-d H:i:s")
                ]);
            DB::commit();
            return redirect()->route('be.berita')->with('success','Berita Berhasil di Ubah!');
        }
        catch(\Exeception $e){
            DB::rollback();
            return redirect()->back()->with('error',$e);
        }
    }

    public function hapus_berita($id)
    {
        DB::beginTransaction();
        try{
            DB::connection()->table("hr_care")
                ->where("hr_care_id",$id)
                ->delete();
            DB::commit();
            return redirect()->route('be.berita')->with('success','Berita Berhasil di Hapus!');
        }
        catch(\Exeception $e){
            DB::rollback();
            return redirect()->back()->with('error',$e);
        }
    }
}

<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Auth;

class pengajuan_bpjsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }public function pengajuan_bpjs()
    {
        $sqlpengajuan_bpjs="SELECT * FROM hr_care
                WHERE 1=1 and active=1 order by tgl_posting desc";
        $pengajuan_bpjs=DB::connection()->select($sqlpengajuan_bpjs);

        $iduser=Auth::user()->id;
        $sqluser="SELECT p_recruitment.foto FROM users
left join p_karyawan on p_karyawan.user_id=users.id
left join p_recruitment on p_recruitment.p_recruitment_id=p_karyawan.p_recruitment_id
where users.id=$iduser";
        $user=DB::connection()->select($sqluser);
	$sqlfasilitas="SELECT * FROM t_cover_bpjs
			left join p_karyawan_keluarga c on c.p_karyawan_keluarga_id = t_cover_bpjs.p_karyawan_keluarga_id
			left join p_karyawan d on d.p_karyawan_id = t_cover_bpjs.p_karyawan_id
                WHERE 1=1    ";
        $bpjs=DB::connection()->select($sqlfasilitas);
        return view('backend.pengajuan_bpjs.pengajuan_bpjs',compact('bpjs','user'));
    }

    public function tambah_pengajuan_bpjs()
    {
        $iduser=Auth::user()->id;
        $sqluser="SELECT p_recruitment.foto FROM users
left join p_karyawan on p_karyawan.user_id=users.id
left join p_recruitment on p_recruitment.p_recruitment_id=p_karyawan.p_recruitment_id
where users.id=$iduser";
        $user=DB::connection()->select($sqluser);
        return view('backend.pengajuan_bpjs.tambah_pengajuan_bpjs',compact('user'));
    }

    public function simpan_pengajuan_bpjs(Request $request){
        DB::beginTransaction();
        try{
            $idUser=Auth::user()->id;
            DB::connection()->table("hr_care")
                ->insert([
                    "judul"=>($request->get("judul")),
                    "deskripsi"=>($request->get("deskripsi_pengajuan_bpjs")),
                    "tgl_posting"=>date('Y-m-d',strtotime($request->get("tgl_posting"))),
                    "tgl_posting_akhir"=>date('Y-m-d',strtotime($request->get("tgl_posting_akhir"))),
                    "create_by"=>$idUser,
                    "create_date" => date("Y-m-d H:i:s")
                ]);
            DB::commit();
            return redirect()->route('be.pengajuan_bpjs')->with('success','pengajuan_bpjs Berhasil di input!');
        }
        catch(\Exeception $e){
            DB::rollback();
            return redirect()->back()->with('error',$e);
        }

    }

    public function edit_pengajuan_bpjs($id)
    {
        $sqlpengajuan_bpjs="SELECT * FROM hr_care
                WHERE hr_care_id=$id";
        $pengajuan_bpjs=DB::connection()->select($sqlpengajuan_bpjs);

        $iduser=Auth::user()->id;
        $sqluser="SELECT p_recruitment.foto FROM users
left join p_karyawan on p_karyawan.user_id=users.id
left join p_recruitment on p_recruitment.p_recruitment_id=p_karyawan.p_recruitment_id
where users.id=$iduser";
        $user=DB::connection()->select($sqluser);

        return view('backend.pengajuan_bpjs.edit_pengajuan_bpjs', compact('pengajuan_bpjs','user'));
    }

    public function update_pengajuan_bpjs(Request $request, $id){
        DB::beginTransaction();
        try{
            $idUser=Auth::user()->id;
            DB::connection()->table("hr_carex")
                ->where("hr_care_id",$id)
                ->update([
                    "judul"=>($request->get("judul")),
                    "deskripsi"=>($request->get("deskripsi_pengajuan_bpjs")),
                    "tgl_posting"=>date('Y-m-d',strtotime($request->get("tgl_posting"))),
                    "tgl_posting_akhir"=>date('Y-m-d',strtotime($request->get("tgl_posting_akhir"))),
                    "update_by"=>$idUser,
                    "update_date" => date("Y-m-d H:i:s")
                ]);
            DB::commit();
            return redirect()->route('be.pengajuan_bpjs')->with('success','pengajuan_bpjs Berhasil di Ubah!');
        }
        catch(\Exeception $e){
            DB::rollback();
            return redirect()->back()->with('error',$e);
        }
    }

    public function selesai_pengajuan_bpjs(Request $request, $id){
        DB::beginTransaction();
        try{
            $idUser=Auth::user()->id;
            DB::connection()->table("t_cover_bpjs")
                ->where("t_cover_bpjs_id",$id)
                ->update([
                    "status"=>(1),
                    "update_by"=>$idUser,
                    "update_date" => date("Y-m-d H:i:s")
                ]);
            DB::commit();
            return redirect()->route('be.pengajuan_bpjs')->with('success','pengajuan_bpjs Berhasil di Ubah!');
        }
        catch(\Exeception $e){
            DB::rollback();
            return redirect()->back()->with('error',$e);
        }
    }

    public function hapus_pengajuan_bpjs($id)
    {
        DB::beginTransaction();
        try{
            DB::connection()->table("hr_carexx")
                ->where("hr_care_id",$id)
                ->delete();
            DB::commit();
            return redirect()->route('be.pengajuan_bpjs')->with('success','pengajuan_bpjs Berhasil di Hapus!');
        }
        catch(\Exeception $e){
            DB::rollback();
            return redirect()->back()->with('error',$e);
        }
    }
}

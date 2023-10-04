<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Auth;
use App\Helper_function;

class PangkatController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }public function pangkat()
    {
        $iduser=Auth::user()->id;
        $sqluser="SELECT p_recruitment.foto FROM users
left join p_karyawan on p_karyawan.user_id=users.id
left join p_recruitment on p_recruitment.p_recruitment_id=p_karyawan.p_recruitment_id
where users.id=$iduser";
        $user=DB::connection()->select($sqluser);

        $sqlpangkat="SELECT * FROM m_pangkat
                WHERE 1=1 AND active=1 order by nama";
        $pangkat=DB::connection()->select($sqlpangkat);
        return view('backend.pangkat.pangkat',compact('pangkat','user'));
    }

    public function tambah_pangkat()
    {
        $iduser=Auth::user()->id;
        $sqluser="SELECT p_recruitment.foto FROM users
left join p_karyawan on p_karyawan.user_id=users.id
left join p_recruitment on p_recruitment.p_recruitment_id=p_karyawan.p_recruitment_id
where users.id=$iduser";
        $user=DB::connection()->select($sqluser);

        return view('backend.pangkat.tambah_pangkat',compact('user'));
    }

    public function simpan_pangkat(Request $request){
        $idUser=Auth::user()->id;
       $help = new Helper_function;
        DB::connection()->table("m_pangkat")
            ->insert([
                "kode"=>($request->get("kode")),
                "nama"=>($request->get("nama")),
                "uang_makan"=>$help->hapusRupiah($request->get("uangmakan")),
                "uang_saku"=>$help->hapusRupiah($request->get("uangsaku1")),
                "uang_saku2"=>$help->hapusRupiah($request->get("uangsaku2")),
                "created_by"=>$idUser,
                "created_at"=>date('Y-m-d'),
                "active"=>1
            ]);

        return redirect()->route('be.pangkat')->with('success','Pangkat Berhasil di input!');
    }

    public function edit_pangkat($id)
    {
        $iduser=Auth::user()->id;
        $sqluser="SELECT p_recruitment.foto FROM users
left join p_karyawan on p_karyawan.user_id=users.id
left join p_recruitment on p_recruitment.p_recruitment_id=p_karyawan.p_recruitment_id
where users.id=$iduser";
        $user=DB::connection()->select($sqluser);

        $sqlpangkat="select * from m_pangkat
                  WHERE m_pangkat_id=$id";
        $pangkat=DB::connection()->select($sqlpangkat);
		$help = new Helper_function();
        return view('backend.pangkat.edit_pangkat', compact('pangkat','user','help'));
    }

    public function update_pangkat(Request $request, $id){
		$help = new Helper_function();
        $idUser=Auth::user()->id;
        DB::connection()->table("m_pangkat")
            ->where("m_pangkat_id",$id)
            ->update([
                "kode"=>($request->get("kode")),
                "nama"=>($request->get("nama")),
                 "uang_makan"=>$help->hapusRupiah($request->get("uangmakan")),
                "uang_saku"=>$help->hapusRupiah($request->get("uangsaku1")),
                "uang_saku2"=>$help->hapusRupiah($request->get("uangsaku2")),
                "updated_by"=>$idUser,
                "updated_at"=>date('Y-m-d'),
                "active"=>1,
            ]);

        return redirect()->route('be.pangkat')->with('success','Pangkat Berhasil di Ubah!');
    }

    public function hapus_pangkat($id)
    {
        $idUser=Auth::user()->id;
        DB::connection()->table("m_pangkat")
            ->where("m_pangkat_id",$id)
            ->update([
                "updated_by"=>$idUser,
                "updated_at"=>date('Y-m-d'),
                "active"=>0
            ]);

        return redirect()->back()->with('success','Pangkat Berhasil di Hapus!');
    }
}

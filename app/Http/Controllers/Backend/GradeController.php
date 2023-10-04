<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Auth;

class GradeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }public function grade()
    {
        $iduser=Auth::user()->id;
        $sqluser="SELECT p_recruitment.foto FROM users
left join p_karyawan on p_karyawan.user_id=users.id
left join p_recruitment on p_recruitment.p_recruitment_id=p_karyawan.p_recruitment_id
where users.id=$iduser";
        $user=DB::connection()->select($sqluser);

        $sqlgrade="SELECT m_grade.*,m_grade_cluster.nama as nmgradecluster FROM m_grade
LEFT JOIN m_grade_cluster on m_grade_cluster.m_grade_cluster_id=m_grade.m_grade_cluster_id
                WHERE 1=1 AND m_grade.active=1 order by m_grade.nama";
        $grade=DB::connection()->select($sqlgrade);
        return view('backend.grade.grade',compact('grade','user'));
    }

    public function tambah_grade()
    {
        $iduser=Auth::user()->id;
        $sqluser="SELECT p_recruitment.foto FROM users
left join p_karyawan on p_karyawan.user_id=users.id
left join p_recruitment on p_recruitment.p_recruitment_id=p_karyawan.p_recruitment_id
where users.id=$iduser";
        $user=DB::connection()->select($sqluser);
        $sqlgradecluster="SELECT * FROM m_grade_cluster WHERE active=1 ORDER BY nama ASC ";
        $gradecluster=DB::connection()->select($sqlgradecluster);
        return view('backend.grade.tambah_grade',compact('gradecluster','user'));
    }

    public function simpan_grade(Request $request){
        $idUser=Auth::user()->id;
        DB::connection()->table("m_grade")
            ->insert([
                "kode"=>($request->get("kode")),
                "nama"=>($request->get("nama")),
                "grade2"=>($request->get("grade2")),
                "group"=>($request->get("grup")),
                "m_grade_cluster_id"=>($request->get("gradecluster")),
                "create_date" => date("Y-m-d"),
                "create_by" => $idUser,
                "active"=>1
            ]);

        return redirect()->route('be.grade')->with('success','Grade Berhasil di input!');
    }

    public function edit_grade($id)
    {
        $sqlgrade="SELECT m_grade.*,m_grade_cluster.nama as nmgradecluster 
                  FROM m_grade
                  LEFT JOIN m_grade_cluster on m_grade_cluster.m_grade_cluster_id=m_grade.m_grade_cluster_id
                  WHERE 1=1 AND m_grade.active=1 AND m_grade.m_grade_id=$id order by m_grade.nama";
        $grade=DB::connection()->select($sqlgrade);

        $sqlgradecluster="SELECT * FROM m_grade_cluster WHERE active=1 ORDER BY nama ASC ";
        $gradecluster=DB::connection()->select($sqlgradecluster);

        $iduser=Auth::user()->id;
        $sqluser="SELECT p_recruitment.foto FROM users
left join p_karyawan on p_karyawan.user_id=users.id
left join p_recruitment on p_recruitment.p_recruitment_id=p_karyawan.p_recruitment_id
where users.id=$iduser";
        $user=DB::connection()->select($sqluser);

        return view('backend.grade.edit_grade', compact('grade','gradecluster','user'));
    }

    public function update_grade(Request $request, $id){
        $idUser=Auth::user()->id;
        DB::connection()->table("m_grade")
            ->where("m_grade_id",$id)
            ->update([
                "kode"=>($request->get("kode")),
                "nama"=>($request->get("nama")),
                "grade2"=>($request->get("grade2")),
                "group"=>($request->get("grup")),
                "m_grade_cluster_id"=>($request->get("gradecluster")),
                "update_date" => date("Y-m-d"),
                "update_by" => $idUser,
                "active"=>1,
            ]);

        return redirect()->route('be.grade')->with('success','Grade Berhasil di Ubah!');
    }

    public function hapus_grade($id)
    {
        $idUser=Auth::user()->id;
        DB::connection()->table("m_grade")
            ->where("m_grade_id",$id)
            ->update([
                "update_date" => date("Y-m-d"),
                "update_by" => $idUser,
                "active"=>0
            ]);

        return redirect()->back()->with('success','Grade Berhasil di Hapus!');
    }
}

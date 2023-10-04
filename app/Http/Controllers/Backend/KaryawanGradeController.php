<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Auth;

class KaryawanGradeController extends Controller
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

        $sqlgrade="SELECT * FROM m_karyawan_grade
                WHERE 1=1order by nama_grade";
        $grade=DB::connection()->select($sqlgrade);
        return view('backend.karyawan_grade.grade',compact('grade','user'));
    }

    public function tambah_karyawan_grade()
    {
        $iduser=Auth::user()->id;
        $sqluser="SELECT p_recruitment.foto FROM users
left join p_karyawan on p_karyawan.user_id=users.id
left join p_recruitment on p_recruitment.p_recruitment_id=p_karyawan.p_recruitment_id
where users.id=$iduser";
        $user=DB::connection()->select($sqluser);
        $sqlgradecluster="SELECT * FROM m_grade_cluster WHERE active=1 ORDER BY nama ASC ";
        $gradecluster=DB::connection()->select($sqlgradecluster);
        return view('backend.karyawan_grade.tambah_grade',compact('gradecluster','user'));
    }

    public function simpan_karyawan_grade(Request $request){
        $idUser=Auth::user()->id;
        DB::connection()->table("m_karyawan_grade")
            ->insert([
                
                "nama_grade"=>($request->get("nama")),
                 "job_min"=>($request->get("min")),
                "job_max"=>($request->get("max")),
                "create_date" => date("Y-m-d"),
                "create_by" => $idUser,
                "active"=>1
            ]);

        return redirect()->route('be.karyawan_grade')->with('success','Grade Berhasil di input!');
    }

    public function edit_karyawan_grade ($id)
    {
        $sqlgrade="SELECT *
                  FROM m_karyawan_grade
                
                  WHERE 1=1 AND m_karyawan_grade_id=$id ";
        $grade=DB::connection()->select($sqlgrade);

        $sqlgradecluster="SELECT * FROM m_grade_cluster WHERE active=1 ORDER BY nama ASC ";
        $gradecluster=DB::connection()->select($sqlgradecluster);

        $iduser=Auth::user()->id;
        $sqluser="SELECT p_recruitment.foto FROM users
left join p_karyawan on p_karyawan.user_id=users.id
left join p_recruitment on p_recruitment.p_recruitment_id=p_karyawan.p_recruitment_id
where users.id=$iduser";
        $user=DB::connection()->select($sqluser);

        return view('backend.karyawan_grade.edit_grade', compact('grade','gradecluster','user'));
    }

    public function update_karyawan_grade(Request $request, $id){
        $idUser=Auth::user()->id;
        DB::connection()->table("m_karyawan_grade")
            ->where("m_karyawan_grade_id",$id)
            ->update([
                 "nama_grade"=>($request->get("nama")),
                 "job_min"=>($request->get("min")),
                "job_max"=>($request->get("max")),
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

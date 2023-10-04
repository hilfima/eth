<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Auth;

class ClusterController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }public function cluster()
    {
        $sqlcluster="SELECT * FROM m_grade_cluster
                WHERE 1=1 AND active=1 order by nama";
        $cluster=DB::connection()->select($sqlcluster);

        $iduser=Auth::user()->id;
        $sqluser="SELECT p_recruitment.foto FROM users
left join p_karyawan on p_karyawan.user_id=users.id
left join p_recruitment on p_recruitment.p_recruitment_id=p_karyawan.p_recruitment_id
where users.id=$iduser";
        $user=DB::connection()->select($sqluser);
        return view('backend.cluster.cluster',compact('cluster','user'));
    }

    public function tambah_cluster()
    {
        $iduser=Auth::user()->id;
        $sqluser="SELECT p_recruitment.foto FROM users
left join p_karyawan on p_karyawan.user_id=users.id
left join p_recruitment on p_recruitment.p_recruitment_id=p_karyawan.p_recruitment_id
where users.id=$iduser";
        $user=DB::connection()->select($sqluser);

        return view('backend.cluster.tambah_cluster',compact('user'));
    }

    public function simpan_cluster(Request $request){
        $idUser=Auth::user()->id;
        DB::connection()->table("m_grade_cluster")
            ->insert([
                "kode"=>($request->get("kode")),
                "nama"=>($request->get("nama")),
                "create_date" => date("Y-m-d"),
                "create_by" => $idUser,
                "active"=>1
            ]);

        return redirect()->route('be.cluster')->with('success','Cluster Berhasil di input!');
    }

    public function edit_cluster($id)
    {
        $sqlcluster="select * from m_grade_cluster
                  WHERE m_grade_cluster_id=$id";
        $cluster=DB::connection()->select($sqlcluster);

        $iduser=Auth::user()->id;
        $sqluser="SELECT p_recruitment.foto FROM users
left join p_karyawan on p_karyawan.user_id=users.id
left join p_recruitment on p_recruitment.p_recruitment_id=p_karyawan.p_recruitment_id
where users.id=$iduser";
        $user=DB::connection()->select($sqluser);

        return view('backend.cluster.edit_cluster', compact('cluster','user'));
    }

    public function update_cluster(Request $request, $id){
        $idUser=Auth::user()->id;
        DB::connection()->table("m_grade_cluster")
            ->where("m_grade_cluster_id",$id)
            ->update([
                "kode"=>($request->get("kode")),
                "nama"=>($request->get("nama")),
                "update_date" => date("Y-m-d"),
                "update_by" => $idUser,
                "active"=>1,
            ]);

        return redirect()->route('be.cluster')->with('success','Cluster Berhasil di Ubah!');
    }

    public function hapus_cluster($id)
    {
        $idUser=Auth::user()->id;
        DB::connection()->table("m_grade_cluster")
            ->where("m_grade_cluster_id",$id)
            ->update([
                "update_date" => date("Y-m-d"),
                "update_by" => $idUser,
                "active"=>0
            ]);

        return redirect()->back()->with('success','Cluster Berhasil di Hapus!');
    }
}

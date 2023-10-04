<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Auth;

class LokerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }public function loker()
    {
        $sqlloker="SELECT t_job_vacancy.*,m_lokasi.nama as nmlokasi,m_departemen.nama as nmdept, m_jabatan.nama as nmjabatan,m_status_pekerjaan.nama as nmstatus
                FROM t_job_vacancy
                LEFT JOIN m_lokasi on m_lokasi.m_lokasi_id=t_job_vacancy.m_lokasi_id
                LEFT JOIN m_departemen on m_departemen.m_departemen_id=t_job_vacancy.m_departemen_id
                LEFT JOIN m_jabatan on m_jabatan.m_jabatan_id=t_job_vacancy.m_jabatan_id
                LEFT JOIN m_status_pekerjaan on m_status_pekerjaan.m_status_pekerjaan_id=t_job_vacancy.m_status_pekerjaan_id
                WHERE 1=1 and t_job_vacancy.active=1 order by t_job_vacancy.tgl_awal";
        $loker=DB::connection()->select($sqlloker);

        $iduser=Auth::user()->id;
        $sqluser="SELECT p_recruitment.foto FROM users
left join p_karyawan on p_karyawan.user_id=users.id
left join p_recruitment on p_recruitment.p_recruitment_id=p_karyawan.p_recruitment_id
where users.id=$iduser";
        $user=DB::connection()->select($sqluser);

        return view('backend.loker.loker',compact('loker','user'));
    }

    public function tambah_loker()
    {
        $iduser=Auth::user()->id;
        $sqluser="SELECT p_recruitment.foto FROM users
left join p_karyawan on p_karyawan.user_id=users.id
left join p_recruitment on p_recruitment.p_recruitment_id=p_karyawan.p_recruitment_id
where users.id=$iduser";
        $user=DB::connection()->select($sqluser);

        $sqllokasi="SELECT * FROM m_lokasi where 1=1 and active=1 and sub_entitas=0 order by nama";
        $lokasi=DB::connection()->select($sqllokasi);

        $sqldepartemen="SELECT * FROM m_departemen where 1=1 order by nama";
        $departemen=DB::connection()->select($sqldepartemen);

        $sqljabatan="SELECT * FROM m_jabatan where 1=1 order by nama";
        $jabatan=DB::connection()->select($sqljabatan);

        $sqlstatus="SELECT * FROM m_status_pekerjaan where 1=1 order by nama";
        $status=DB::connection()->select($sqlstatus);

        return view('backend.loker.tambah_loker',compact('user','lokasi','jabatan','departemen','status'));
    }

    public function simpan_loker(Request $request){
        DB::beginTransaction();
        try{
            $idUser=Auth::user()->id;
            	if ($request->file('file')) {
			//echo 'masuk';die;
					$file = $request->file('file');
					$destination="dist/img/file/";
					$path='loker-'.date('ymdhis').'-'.$file->getClientOriginalName();
					$file->move($destination,$path);

					//echo $path;die;
				}
            DB::connection()->table("t_job_vacancy")
                ->insert([
                    "kode"=>($request->get("kode")),
                    "m_lokasi_id"=>($request->get("lokasi")),
                    "m_departemen_id"=>($request->get("departemen")),
                    "m_jabatan_id"=>($request->get("jabatan")),
                    "m_status_pekerjaan_id"=>($request->get("status")),
                    "keterangan_indonesia"=>($request->get("tujuan")),
                    "keterangan_indonesia"=>($request->get("tujuan")),
                    "keterangan_english"=>($request->get("tujuanen")),
                    "deskripsi_indonesia"=>($request->get("persyaratan")),
                    "deskripsi_english"=>($request->get("persyaratanen")),
                    "tgl_awal"=>date('Y-m-d',strtotime($request->get("tgl_awal"))),
                    "tgl_akhir"=>date('Y-m-d',strtotime($request->get("tgl_akhir"))),
                    "active"=>1,
                    "file"=>$path,
                    "create_by"=>$idUser,
                    "create_date" => date("Y-m-d H:i:s")
                ]);
            DB::commit();
            return redirect()->route('be.loker')->with('success','Lowowngan Pekerjaan Berhasil di input!');
        }
        catch(\Exeception $e){
            DB::rollback();
            return redirect()->back()->with('error',$e);
        }

    }

    public function edit_loker($id)
    {
        $sqlloker="SELECT * FROM t_job_vacancy
                WHERE t_job_vacancy_id=$id";
        $loker=DB::connection()->select($sqlloker);

        $iduser=Auth::user()->id;
        $sqluser="SELECT p_recruitment.foto FROM users
left join p_karyawan on p_karyawan.user_id=users.id
left join p_recruitment on p_recruitment.p_recruitment_id=p_karyawan.p_recruitment_id
where users.id=$iduser";
        $user=DB::connection()->select($sqluser);

        $sqllokasi="SELECT * FROM m_lokasi where 1=1 order by nama";
        $lokasi=DB::connection()->select($sqllokasi);

        $sqldepartemen="SELECT * FROM m_departemen where 1=1 order by nama";
        $departemen=DB::connection()->select($sqldepartemen);

        $sqljabatan="SELECT * FROM m_jabatan where 1=1 order by nama";
        $jabatan=DB::connection()->select($sqljabatan);

        $sqlstatus="SELECT * FROM m_status_pekerjaan where 1=1 order by nama";
        $status=DB::connection()->select($sqlstatus);

        return view('backend.loker.edit_loker', compact('loker','user','lokasi','jabatan','departemen','status'));
    }

    public function update_loker(Request $request, $id){
        DB::beginTransaction();
        try{
            $idUser=Auth::user()->id;
            DB::connection()->table("t_job_vacancy")
                ->where("t_job_vacancy_id",$id)
                ->update([
                    "m_lokasi_id"=>($request->get("lokasi")),
                    "m_departemen_id"=>($request->get("departemen")),
                    "m_jabatan_id"=>($request->get("jabatan")),
                    "m_status_pekerjaan_id"=>($request->get("status")),
                    "keterangan_indonesia"=>($request->get("tujuan")),
                    "keterangan_english"=>($request->get("tujuanen")),
                    "deskripsi_indonesia"=>($request->get("persyaratan")),
                    "deskripsi_english"=>($request->get("persyaratanen")),
                    "tgl_awal"=>date('Y-m-d',strtotime($request->get("tgl_awal"))),
                    "tgl_akhir"=>date('Y-m-d',strtotime($request->get("tgl_akhir"))),
                    "active"=>1,
                    "update_by"=>$idUser,
                    "update_date" => date("Y-m-d H:i:s")
                ]);
            DB::commit();
            return redirect()->route('be.loker')->with('success','Lowongan Pekerjaan Berhasil di Ubah!');
        }
        catch(\Exeception $e){
            DB::rollback();
            return redirect()->back()->with('error',$e);
        }
    }

    public function hapus_loker($id)
    {
        DB::beginTransaction();
        try{
            DB::connection()->table("t_job_vacancy")
                ->where("t_job_vacancy_id",$id)
                ->delete();
            DB::commit();
            return redirect()->route('be.loker')->with('success','Lowongan Pekerjaan Berhasil di Ubah!');
        }
        catch(\Exeception $e){
            DB::rollback();
            return redirect()->back()->with('error',$e);
        }
    }
}

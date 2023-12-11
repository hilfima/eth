<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Auth;

class MesinController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }public function mesin()
    {
        $sqlmesin_absen="SELECT m_mesin_absen.*,m_lokasi.nama as nmlokasi
        
        FROM m_mesin_absen
                left join m_lokasi on m_lokasi.m_lokasi_id=m_mesin_absen.m_lokasi_id
                WHERE 1=1 and m_mesin_absen.active=1 order by nama";
        $mesin_absen=DB::connection()->select($sqlmesin_absen);

        $iduser=Auth::user()->id;
        $sqluser="SELECT p_recruitment.foto FROM users
left join p_karyawan on p_karyawan.user_id=users.id
left join p_recruitment on p_recruitment.p_recruitment_id=p_karyawan.p_recruitment_id
where users.id=$iduser";
        $user=DB::connection()->select($sqluser);
        $jabatan = DB::connection()->select('select m_jabatan.*,m_pangkat.nama as nmpangkat,m_lokasi.nama as nmlokasi,m_lokasi.kode as kdlokasi,m_departemen.nama as nmdepartemen
                      FROM m_jabatan 
                      LEFT JOIN m_pangkat on m_pangkat.m_pangkat_id=m_jabatan.m_pangkat_id
                      LEFT JOIN m_lokasi on m_lokasi.m_lokasi_id=m_jabatan.m_lokasi_id 
                      LEFT JOIN m_departemen on m_departemen.m_departemen_id=m_jabatan.m_departemen_id 
                      
                      where m_jabatan.active=1');
        $list_jabatan=array();
                      
        foreach($jabatan as $jabatan){
                $list_jabatan[$jabatan->m_jabatan_id]=$jabatan->nama;
        }
        return view('backend.mesin_absen.mesin_absen',compact('mesin_absen','user','list_jabatan'));
    }

    public function tambah_mesin()
    {
        $sqllokasi="SELECT * FROM m_lokasi WHERE active=1 ORDER BY nama ASC ";
        $lokasi=DB::connection()->select($sqllokasi);

        $iduser=Auth::user()->id;
        $sqluser="SELECT p_recruitment.foto FROM users
left join p_karyawan on p_karyawan.user_id=users.id
left join p_recruitment on p_recruitment.p_recruitment_id=p_karyawan.p_recruitment_id
where users.id=$iduser";
        $user=DB::connection()->select($sqluser);
        
        $jabatan = DB::connection()->select('select m_jabatan.*,m_pangkat.nama as nmpangkat,m_lokasi.nama as nmlokasi,m_lokasi.kode as kdlokasi,m_departemen.nama as nmdepartemen
                      FROM m_jabatan 
                      LEFT JOIN m_pangkat on m_pangkat.m_pangkat_id=m_jabatan.m_pangkat_id
                      LEFT JOIN m_lokasi on m_lokasi.m_lokasi_id=m_jabatan.m_lokasi_id 
                      LEFT JOIN m_departemen on m_departemen.m_departemen_id=m_jabatan.m_departemen_id 
                      
                      where m_jabatan.active=1');

        return view('backend.mesin_absen.tambah_mesin', compact('lokasi','user','jabatan'));
    }

    public function simpan_mesin(Request $request){
        DB::beginTransaction();
        try{
            $idUser=Auth::user()->id;
            DB::connection()->table("m_mesin_absen")
                ->insert([
                    "nama"=>($request->get("nama")),
                    "port"=>($request->get("port")),
                    "ip"=>($request->get("ip")),
                    // "m_lokasi_id"=>($request->get("lokasi")),
                    "is_default"=>($request->get("default")),
                    // "list_jabatan"=>implode(',',array_unique($request->get("jabatan"))),
                    "created_at" => date("Y-m-d H:i:s")
                ]);
            DB::commit();
            return redirect()->route('be.mesin')->with('success','Mesin Absen Berhasil di input!');
        }
        catch(\Exeception $e){
            DB::rollback();
            return redirect()->back()->with('error',$e);
        }

    }

    public function edit_mesin($id)
    {
        $sqlmesin_absen="SELECT m_mesin_absen.*,m_lokasi.nama as nmlokasi FROM m_mesin_absen
                left join m_lokasi on m_lokasi.m_lokasi_id=m_mesin_absen.m_lokasi_id
                  WHERE mesin_id=$id";
        $mesin_absen=DB::connection()->select($sqlmesin_absen);

        $sqllokasi="SELECT * FROM m_lokasi WHERE active=1 ORDER BY nama ASC ";
        $lokasi=DB::connection()->select($sqllokasi);

        $iduser=Auth::user()->id;
        $sqluser="SELECT p_recruitment.foto FROM users
left join p_karyawan on p_karyawan.user_id=users.id
left join p_recruitment on p_recruitment.p_recruitment_id=p_karyawan.p_recruitment_id
where users.id=$iduser";
        $user=DB::connection()->select($sqluser);
        $jabatan = DB::connection()->select('select m_jabatan.*,m_pangkat.nama as nmpangkat,m_lokasi.nama as nmlokasi,m_lokasi.kode as kdlokasi,m_departemen.nama as nmdepartemen
                      FROM m_jabatan 
                      LEFT JOIN m_pangkat on m_pangkat.m_pangkat_id=m_jabatan.m_pangkat_id
                      LEFT JOIN m_lokasi on m_lokasi.m_lokasi_id=m_jabatan.m_lokasi_id 
                      LEFT JOIN m_departemen on m_departemen.m_departemen_id=m_jabatan.m_departemen_id 
                      
                      where m_jabatan.active=1');

        return view('backend.mesin_absen.edit_mesin', compact('mesin_absen','lokasi','user','jabatan'));
    }

    public function update_mesin(Request $request, $id){
        DB::beginTransaction();
        try{
            DB::connection()->table("m_mesin_absen")
                ->where("mesin_id",$id)
                ->update([
                    "nama"=>($request->get("nama")),
                    "port"=>($request->get("port")),
                    "ip"=>($request->get("ip")),
                    "active"=>1,
                    // "m_lokasi_id"=>($request->get("lokasi")),
                    "is_default"=>($request->get("default")),
                    // "list_jabatan"=>implode(',',array_unique($request->get("jabatan"))),
                    "updated_at" => date("Y-m-d H:i:s")
                ]);
            DB::commit();
            return redirect()->route('be.mesin')->with('success','Mesin Absen Berhasil di Ubah!');
        }
        catch(\Exeception $e){
            DB::rollback();
            return redirect()->back()->with('error',$e);
        }
    }

    public function hapus_mesin($id)
    {
        DB::beginTransaction();
        try{
            DB::connection()->table("m_mesin_absen")
                ->where("mesin_id",$id)
                ->update([
                    "active"=>0
                    ]);
            DB::commit();
            return redirect()->route('be.mesin')->with('success','Mesin Absen Berhasil di Ubah!');
        }
        catch(\Exeception $e){
            DB::rollback();
            return redirect()->back()->with('error',$e);
        }
    }
}

<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Auth;

class JamKerjaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }public function jam_kerja()
    {
        $sqljam_kerja="SELECT absen.jam_masuk,absen.jam_keluar,absen.tgl_awal,absen.tgl_akhir,absen.keterangan,absen.absen_id ,m_lokasi.nama
                       FROM absen
                       left join m_lokasi on m_lokasi.m_lokasi_id = absen.m_lokasi_id
                       where absen.active = 1 and shifting = 0
                        ORDER BY tgl_awal desc";
        $jam_kerja=DB::connection()->select($sqljam_kerja);

        $iduser=Auth::user()->id;
        
        

        return view('backend.jam_kerja.view_jamkerja',compact('jam_kerja'));
    }

    public function tambah_jam_kerja()
    {
        $iduser=Auth::user()->id;
        $sqluser="SELECT p_recruitment.foto FROM users
left join p_karyawan on p_karyawan.user_id=users.id
left join p_recruitment on p_recruitment.p_recruitment_id=p_karyawan.p_recruitment_id
where users.id=$iduser";
        $user=DB::connection()->select($sqluser);
		 $sqllokasi="select * from m_lokasi where active=1";
        $lokasi=DB::connection()->select($sqllokasi);

        return view('backend.jam_kerja.tambah_jam_kerja', compact('user','lokasi'));
    }

    public function simpan_jam_kerja(Request $request){
        DB::beginTransaction();
        try{
            $idUser=Auth::user()->id;
           $id =  DB::connection()->table("absen")
                ->insert([
                   
                    "tgl_awal"=>($request->get("tgl_awal")),
                    "tgl_akhir"=>($request->get("tgl_akhir")),
                    "m_lokasi_id"=>($request->get("entitas")),
                    "jam_masuk"=>($request->get("jam_masuk")),
                    "jam_keluar"=>($request->get("jam_keluar")),
                    "keterangan"=>($request->get("keterangan")),
                    "shifting"=>($request->get("shifting")?1:0),
                   
                    "active"=>1,
                    "create_by" => $idUser,
                    "create_date" => date("Y-m-d H:i:s")
                ]);
                $SQL = " SELECT currval('hrm.seq_absen')";
                $shift=DB::connection()->select($SQL);
//                print_r($shift);
                $id = $shift[0]->currval;

            
            DB::commit();
            return redirect()->route('be.jam_kerja')->with('success','Hari Libur Berhasil di input!');
        }
        catch(\Exeception $e){
            DB::rollback();
            return redirect()->back()->with('error',$e);
        }

    }

    public function edit_jam_kerja($id)
    {
        $sqljam_kerja="SELECT absen.jam_masuk,absen.jam_keluar,absen.tgl_awal,absen.tgl_akhir,absen.keterangan,absen.absen_id ,m_lokasi.nama,absen.m_lokasi_id
                       FROM absen
                       left join m_lokasi on m_lokasi.m_lokasi_id = absen.m_lokasi_id
                        where absen_id = $id
                        ORDER BY tgl_awal desc
                        ";
        $jam_kerja=DB::connection()->select($sqljam_kerja);
		 $sqllokasi="select * from m_lokasi";
        $lokasi=DB::connection()->select($sqllokasi);
        $iduser=Auth::user()->id;
        $sqluser="SELECT p_recruitment.foto FROM users
left join p_karyawan on p_karyawan.user_id=users.id
left join p_recruitment on p_recruitment.p_recruitment_id=p_karyawan.p_recruitment_id
where users.id=$iduser";
        $user=DB::connection()->select($sqluser);
		$jam_kerja = $jam_kerja[0];
        return view('backend.jam_kerja.edit_jam_kerja', compact('jam_kerja','user','id','lokasi'));
    }

    public function update_jam_kerja(Request $request, $id){
        $idUser=Auth::user()->id;
        DB::beginTransaction();
        try{
            DB::connection()->table("absen")
                ->where("absen_id",$id)
                ->update([
                    "tgl_awal"=>($request->get("tgl_awal")),
                    "tgl_akhir"=>($request->get("tgl_akhir")),
                    "m_lokasi_id"=>($request->get("entitas")),
                    "jam_masuk"=>($request->get("jam_masuk")),
                    "jam_keluar"=>($request->get("jam_keluar")),
                    "keterangan"=>($request->get("keterangan")),
                   
                    "active"=>1,
                    "create_by" => $idUser,
                    "create_date" => date("Y-m-d H:i:s")
                ]);
            DB::commit();
            return redirect()->route('be.jam_kerja')->with('success','Hari Libur Berhasil di Ubah!');
        }
        catch(\Exeception $e){
            DB::rollback();
            return redirect()->back()->with('error',$e);
        }
    }

    public function hapus_jam_kerja($id)
    {
        DB::beginTransaction();
        try{
            DB::connection()->table("absen")
                ->where("absen_id",$id)
                ->update([
                    "active" => 0
                ]);
            DB::commit();
            return redirect()->route('be.jam_kerja')->with('success','Hari Libur Berhasil di Ubah!');
        }
        catch(\Exeception $e){
            DB::rollback();
            return redirect()->back()->with('error',$e);
        }
    }
}

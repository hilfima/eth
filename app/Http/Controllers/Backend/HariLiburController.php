<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Auth;
use App\Helper_function;

class HariLiburController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }public function hari_libur()
    {
        $sqlharilibur="SELECT m_hari_libur.*, 
                       case when is_berulang=1 then 'Ya' else 'Tidak' end as berulang, 
                       case when is_cuti_bersama=1 then 'Ya' else 'Tidak' end as cuti_bersama 
                       FROM m_hari_libur ORDER BY tanggal";
        $harilibur=DB::connection()->select($sqlharilibur);

        $iduser=Auth::user()->id;
        $sqluser="SELECT p_recruitment.foto FROM users
left join p_karyawan on p_karyawan.user_id=users.id
left join p_recruitment on p_recruitment.p_recruitment_id=p_karyawan.p_recruitment_id
where users.id=$iduser";
        $user=DB::connection()->select($sqluser);

        return view('backend.hari_libur.hari_libur',compact('harilibur','user'));
    }

    public function tambah_hari_libur()
    {
        $iduser=Auth::user()->id;
        $sqluser="SELECT p_recruitment.foto FROM users
left join p_karyawan on p_karyawan.user_id=users.id
left join p_recruitment on p_recruitment.p_recruitment_id=p_karyawan.p_recruitment_id
where users.id=$iduser";
        $user=DB::connection()->select($sqluser);
        $entitas=DB::connection()->select("select * from m_lokasi where active=1 and sub_entitas=0");

        return view('backend.hari_libur.tambah_hari_libur', compact('user','entitas'));
    }

    public function simpan_hari_libur(Request $request){
        DB::beginTransaction();
        try{
            $idUser=Auth::user()->id;
            $tgl_awal = ($request->get("tanggal_awal"));
            $tgl_akhir = ($request->get("tanggal_akhir"));
            $help = new Helper_function();
            $date = $tgl_awal;
            if($tgl_awal==$tgl_akhir)
            	$hitung = 1;
        	else
        	$hitung=0;
            for($tanggal=0;$tanggal<=$help->hitungHari($tgl_awal,$tgl_akhir);$tanggal++){
            	
            DB::connection()->table("m_hari_libur")
                ->insert([
                    "tanggal"=>$date,
                    "nama"=>($request->get("nama")),
                    "jumlah"=>1,
                    "is_berulang"=>($request->get("berulang")),
                    "is_cuti_bersama"=>($request->get("cuti_bersama")),
                    "tipe_cuti_bersama"=>($request->get("tipe_cuti_bersama")),
                    "active"=>1,
                    "create_by" => $idUser,
                    "create_date" => date("Y-m-d H:i:s")
                ]);
                
                $hari_libur=DB::connection()->select("select * from seq_m_hari_libur");
                $pengkhususan = $request->pengkhususan?$request->pengkhususan:array();
                //$pengecualian = $request->pengecualian?$request->pengecualian:array();
                
            	if(count($pengkhususan)){
            		for($i=0;$i<count($pengkhususan);$i++){
            			DB::connection()->table("m_hari_libur_except")
			                ->insert([
			                    
			                    "m_hari_libur_id"=>$hari_libur[0]->last_value,
			                    "m_lokasi_id"=>$pengkhususan[$i],
			                    "jenis"=>2,
			                    "active"=>1
			                ]);
            		}
            	}
            	/*
            	if(count($pengecualian)){
            		for($i=0;$i<count($pengecualian);$i++){
            			DB::connection()->table("m_hari_libur_except")
			                ->insert([
			                    
			                    "m_hari_libur_id"=>$hari_libur[0]->last_value,
			                    "m_lokasi_id"=>$pengecualian[$i],
			                    "jenis"=>1,
			                    "active"=>1
			                ]);
            		}
            	}*/
                $date = $help->tambah_tanggal($date,1);
            }
            DB::commit();
            return redirect()->route('be.hari_libur')->with('success','Hari Libur Berhasil di input!');
        }
        catch(\Exeception $e){
            DB::rollback();
            return redirect()->back()->with('error',$e);
        }

    }

    public function edit_hari_libur($id)
    {
        $sqlharilibur="SELECT * FROM m_hari_libur WHERE m_hari_libur_id=$id ORDER BY tanggal";
        $harilibur=DB::connection()->select($sqlharilibur);

        $iduser=Auth::user()->id;
        $sqluser="SELECT p_recruitment.foto FROM users
left join p_karyawan on p_karyawan.user_id=users.id
left join p_recruitment on p_recruitment.p_recruitment_id=p_karyawan.p_recruitment_id
where users.id=$iduser";
        $user=DB::connection()->select($sqluser);

        return view('backend.hari_libur.edit_hari_libur', compact('harilibur','user'));
    }

    public function update_hari_libur(Request $request, $id){
        $idUser=Auth::user()->id;
        DB::beginTransaction();
        try{
            DB::connection()->table("m_hari_libur")
                ->where("m_hari_libur_id",$id)
                ->update([
                    "tanggal"=>date('Y-m-d',strtotime($request->get("tanggal"))),
                    "nama"=>($request->get("nama")),
                    "jumlah"=>1,
                    "is_berulang"=>($request->get("berulang")),
                    "is_cuti_bersama"=>($request->get("cuti_bersama")),
                    "active"=>1,
                    "update_by" => $idUser,
                    "update_date" => date("Y-m-d H:i:s")
                ]);
            DB::commit();
            return redirect()->route('be.hari_libur')->with('success','Hari Libur Berhasil di Ubah!');
        }
        catch(\Exeception $e){
            DB::rollback();
            return redirect()->back()->with('error',$e);
        }
    }

    public function hapus_hari_libur($id)
    {
        DB::beginTransaction();
        try{
            DB::connection()->table("m_hari_libur")
                ->where("m_hari_libur_id",$id)
                ->update([
                    "active" => 0
                ]);
            DB::commit();
            return redirect()->route('be.hari_libur')->with('success','Hari Libur Berhasil di Ubah!');
        }
        catch(\Exeception $e){
            DB::rollback();
            return redirect()->back()->with('error',$e);
        }
    }
}

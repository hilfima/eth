<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Auth;
use App\Helper_function;

class PeriodeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }public function periode($tipe)
    {
        $sqlperiode="SELECT m_periode_absen.*,
            case when periode=1 then 'Januari'
            when periode=2 then 'Februari'
            when periode=3 then 'Maret'
            when periode=4 then 'April'
            when periode=5 then 'Mei'
            when periode=6 then 'Juni'
            when periode=7 then 'Juli'
            when periode=8 then 'Agustus'
            when periode=9 then 'September'
            when periode=10 then 'Oktober'
            when periode=11 then 'November'
            when periode=12 then 'Desember' end as bulan,
            case when type=0 then 'Pekanan' else 'Bulanan' end as tipe
            FROM m_periode_absen
            where tipe_periode = '$tipe' and active=1
            ORDER BY tahun desc,periode desc";
        $periode=DB::connection()->select($sqlperiode);

        $iduser=Auth::user()->id;
        $sqluser="SELECT p_recruitment.foto FROM users
            left join p_karyawan on p_karyawan.user_id=users.id
            left join p_recruitment on p_recruitment.p_recruitment_id=p_karyawan.p_recruitment_id
            where users.id=$iduser";
        $user=DB::connection()->select($sqluser);

        return view('backend.periode_absen.periode',compact('periode','user','tipe'));
    }

    public function tambah_periode($tipe)
    {
        $iduser=Auth::user()->id;
        $sqluser="SELECT p_recruitment.foto FROM users
            left join p_karyawan on p_karyawan.user_id=users.id
            left join p_recruitment on p_recruitment.p_recruitment_id=p_karyawan.p_recruitment_id
            where users.id=$iduser";
        $user=DB::connection()->select($sqluser);
        $entitas = DB::connection()->select("select * from m_lokasi where active=1 and sub_entitas=0 order by nama");
        return view('backend.periode_absen.tambah_periode', compact('user','tipe','entitas'));
    }

    public function simpan_periode(Request $request){
        DB::beginTransaction();
        try{
            $idUser=Auth::user()->id;
             if($request->periode_aktif){
                	  DB::connection()->table("m_periode_absen")
		                ->where("type",$request->get("type"))
		                ->update(["periode_aktif"=>0]);
                }
               // echo $request->get("periode_aktif");die;
            DB::connection()->table("m_periode_absen")
                ->insert([
                    "tipe_periode"=>($request->get("tipe_periode")),
                    "tahun"=>($request->get("tahun")),
                    "periode"=>($request->get("periode")),
                    "tgl_awal"=>date('Y-m-d',strtotime($request->get("tgl_awal"))),
                    "tgl_akhir"=>date('Y-m-d',strtotime($request->get("tgl_akhir"))),
                    "type"=>($request->get("type")),
                    "pekanan_ke"=>($request->get("pekanan_ke")),
                    "active"=>1,
                    "periode_aktif"=>($request->get("periode_aktif")),
                    "entitas_type"=>($request->get("entitas")),
                    "entitas_list"=>($request->get("list_entitas")?implode(',',$request->get("list_entitas")):''),
                    "created_by" => $idUser,
                    "created_at" => date("Y-m-d H:i:s")
                ]);
                
               
            DB::commit();
            return redirect()->route('be.periode',$request->get("tipe_periode"))->with('success','Periode '.ucwords($request->get("tipe_periode")).' Berhasil di input!');
        }
        catch(\Exeception $e){
            DB::rollback();
            return redirect()->back()->with('error',$e);
        }

    }

    public function edit_periode($id,$tipe)
    {
        $sqlperiode="SELECT m_periode_absen.*,
case when periode=1 then 'Januari'
when periode=2 then 'Februari'
when periode=3 then 'Maret'
when periode=4 then 'April'
when periode=5 then 'Mei'
when periode=6 then 'Juni'
when periode=7 then 'Juli'
when periode=8 then 'Agustus'
when periode=9 then 'September'
when periode=10 then 'Oktober'
when periode=11 then 'November'
when periode=12 then 'Desember' end as bulan,
case when type=0 then 'Pekanan' else 'Bulanan' end as tipe
FROM m_periode_absen WHERE periode_absen_id=$id 
ORDER BY tahun,periode";
        $periode=DB::connection()->select($sqlperiode);

        $iduser=Auth::user()->id;
        $sqluser="SELECT p_recruitment.foto FROM users
left join p_karyawan on p_karyawan.user_id=users.id
left join p_recruitment on p_recruitment.p_recruitment_id=p_karyawan.p_recruitment_id
where users.id=$iduser";
        $user=DB::connection()->select($sqluser);
        $entitas = DB::connection()->select("select * from m_lokasi where active=1 and sub_entitas=0 order by nama");

        return view('backend.periode_absen.edit_periode', compact('periode','user','tipe','entitas'));
    }

    public function update_periode(Request $request, $id){
        $idUser=Auth::user()->id;
        DB::beginTransaction();
        try{
            if($request->periode_aktif){
                	  DB::connection()->table("m_periode_absen")
		                ->where("type",$request->get("type"))
		                ->update(["periode_aktif"=>0]);
                }
            DB::connection()->table("m_periode_absen")
                ->where("periode_absen_id",$id)
                ->update([
                    "tahun"=>($request->get("tahun")),
                    "periode"=>($request->get("periode")),
                    "tgl_awal"=>date('Y-m-d',strtotime($request->get("tgl_awal"))),
                    "tgl_akhir"=>date('Y-m-d',strtotime($request->get("tgl_akhir"))),
                    "type"=>($request->get("type")),
                    "pekanan_ke"=>($request->get("pekanan_ke")), 
                    "updated_by" => $idUser,
                    "active" => 1,
                     "periode_aktif"=>($request->get("periode_aktif")),
                     "entitas_type"=>($request->get("entitas")),
                    "entitas_list"=>implode(',',$request->get("list_entitas")),
                    "updated_at" => date("Y-m-d H:i:s")
                ]);
            DB::commit();
            return redirect()->route('be.periode',$request->get("tipe_periode"))->with('success','Periode  '.ucwords($request->get("tipe_periode")).' Berhasil di Ubah!');
        }
        catch(\Exeception $e){
            DB::rollback();
            return redirect()->back()->with('error',$e);
        }
    }

    public function periode_absen_min(Request $request)
    {
        $help = new Helper_function();
        $periode = DB::connection()->select("select * from m_periode_absen where  type=$request->periode_gajian and tipe_periode='$request->type'  and active=1 order by tgl_akhir desc ");
        
        $return['min']= $help->tambah_tanggal($periode[0]->tgl_akhir,1);
        
        echo json_encode($return);
    }

    public function periode_absen_cek_duplicate(Request $request)
    {
        $help = new Helper_function();
        $periode = DB::connection()->select("select * from m_periode_absen where  tgl_awal='$request->tgl_awal' and tgl_akhir='$request->tgl_akhir' and tipe_periode='$request->type' and active=1  ");
        
        $return['count']= count($periode);
        
        echo json_encode($return);
    }

    public function hapus_periode($id,$tipe)
    {
    	
        DB::beginTransaction();
        try{
            DB::connection()->table("m_periode_absen")
                ->where("periode_absen_id",$id)
                ->update([
                    "active" => 0
                ]);
            DB::commit();
            return redirect()->route('be.periode',$tipe)->with('success','Periode  '.ucwords($tipe).' Berhasil di Ubah!');
        }
        catch(\Exeception $e){
            DB::rollback();
            return redirect()->back()->with('error',$e);
        }
    }
}

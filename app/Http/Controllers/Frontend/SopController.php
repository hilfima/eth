<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;

class SopController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    	Session::put('backUrl', URL::full());
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function baca_sop($id)
    {
    	$iduser=Auth::user()->id;
        $sqlidkar="select * from p_karyawan 
        	left join p_karyawan_pekerjaan on p_karyawan_pekerjaan.p_karyawan_id = p_karyawan.p_karyawan_id
        where user_id=$iduser";
        $idkar=DB::connection()->select($sqlidkar);
        $idkaryawan=$idkar[0]->p_karyawan_id;
    	$sop=DB::connection()->select("select *,(select count(*) from sop_reader where p_karyawan_id = $idkaryawan) as selesai from sop where active =1 and sop_id=$id");
    	 return view('frontend.sop.baca_sop',compact('sop'));
    } public function selesai_baca_sop(Request $request,$id)
    {
    	DB::beginTransaction();
        try{
        $waktu_awal     =strtotime($request->get('waktu_awal'));
        $waktu_akhir    =strtotime(date('Y-m-d H:i:s')); // bisa juga waktu sekarang now()
        
        //menghitung selisih dengan hasil detik
        $diff    =$waktu_akhir - $waktu_awal;
        
        //membagi detik menjadi jam
        $jam    =floor($diff / (60 * 60));
        
        //membagi sisa detik setelah dikurangi $jam menjadi menit
        $menit    =$diff - $jam * (60 * 60);
		$iduser=Auth::user()->id;
        $sqlidkar="select * from p_karyawan 
        	join p_karyawan_pekerjaan on p_karyawan_pekerjaan.p_karyawan_id = p_karyawan.p_karyawan_id
        where user_id=$iduser";
        $idkar=DB::connection()->select($sqlidkar);
        $idkaryawan=$idkar[0]->p_karyawan_id;
    		 DB::connection()->table("sop_reader")
                    ->insert([
                        "sop_id"=>$id,
                        "total_membaca"=>$diff,
                        "tanggal_baca"=>date('Y-m-d'),
                        "waktu_baca"=>date('H:i:s'),
                        "p_karyawan_id"=>$idkaryawan,
                        
                    ]);
    	
    		DB::commit();
            return redirect()->route('fe.sop')->with('success','Terima kasih telah membaca Sop / IK !');
        }
        catch(\Exeception $e){
            DB::rollback();
            return redirect()->back()->with('error',$e);
        }
    	
    } public function sop()
    {
    	 $iduser=Auth::user()->id;
        $sqlidkar="select * from p_karyawan 
        	join p_karyawan_pekerjaan on p_karyawan_pekerjaan.p_karyawan_id = p_karyawan.p_karyawan_id
        where user_id=$iduser";
        $idkar=DB::connection()->select($sqlidkar);
        $id=$idkar[0]->p_karyawan_id;
		$iddept=$idkar[0]->m_departemen_id;
		$sql = "select *,(select count(*) from sop_reader where p_karyawan_id = $id) as selesai from sop where active =1 and ((select count(*) from sop_dept where sop.sop_id = sop_dept.sop_id and active =1 )=0 or (select count(*) from sop_dept where sop.sop_id = sop_dept.sop_id and active =1 and m_departement_id = $iddept)=1 )";
    	$sop=DB::connection()->select($sql);
    	
        return view('frontend.sop.sop',compact('sop'));
    }
}
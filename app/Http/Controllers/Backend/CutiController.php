<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Auth;
use App\Helper_function;

class CutiController extends Controller
{
	public function __construct()
    {
        $this->middleware('auth');
    }public function edit_cuti(Request $request,$id)
    {
		//echo $id;die;
		$sqlfasilitas="SELECT * FROM t_cuti
			  WHERE 1=1  and t_cuti.t_cuti_id = $id order by tanggal ";
        $cuti=DB::connection()->select($sqlfasilitas);
        return view('backend.cuti.edit_cuti',compact('cuti','id'));
	}public function update_cuti(Request $request,$id)
    {
    	DB::beginTransaction();
        try{
            $idUser=Auth::user()->id;
             $sqlidkar="select * from t_cuti where t_cuti_id=$id";
	        $idkar=DB::connection()->select($sqlidkar);
            
            DB::connection()->table("t_cuti")
                ->where("t_cuti_id",$id)
                ->update([
                    "nominal"=>($request->get("total")),
                    ]);
            DB::commit();
            return redirect()->route('be.laporan_cuti_karyawan',['nama='.$idkar[0]->p_karyawan_id.'&Cari=Cari'])->with('success','Laporan Cuti karyawan Berhasil di Ubah!');
        }
        catch(\Exeception $e){
            DB::rollback();
            return redirect()->back()->with('error',$e);
        }
    }public function delete_cuti (Request $request,$id)
    {
    	DB::beginTransaction();
        try{
            $idUser=Auth::user()->id;
             $sqlidkar="select * from t_cuti where t_cuti_id=$id";
	        $idkar=DB::connection()->select($sqlidkar);
            
            DB::connection()->table("t_cuti")
                ->where("t_cuti_id",$id)
                ->delete();
            DB::commit();
            return redirect()->route('be.laporan_cuti_karyawan',['nama='.$idkar[0]->p_karyawan_id.'&Cari=Cari'])->with('success','Laporan Cuti karyawan Berhasil di Ubah!');
        }
        catch(\Exeception $e){
            DB::rollback();
            return redirect()->back()->with('error',$e);
        }
    }public function tambah_laporan_cuti_karyawan (Request $request)
    {
    	return view('backend.cuti.tambah_laporan_cuti_karyawan');
    }public function simpan_cuti(Request $request)
    {
    	DB::beginTransaction();
        try{
            $idUser=Auth::user()->id;
            $nominal = $request->get("total");
	           if($request->get("tipe")==1){
			   	$keterangan='Penambahan Cuti Tahun '.$request->get("tahun");
			   }else if($request->get("tipe")==2){
			   	$keterangan='Reset Cuti Tahunan '.$request->get("tahun");
			   }else if($request->get("tipe")==3){
			   	$keterangan='Penambahan Cuti Besar ke-'.$request->get("tahun");
			   }else if($request->get("tipe")==4){
			   	$keterangan='Reset Cuti Besar ke-'.$request->get("tahun");
			   }else if($request->get("tipe")==5 or $request->get("tipe")==8){
			   	$keterangan='Rekap Sinkronisasi';
			   	$nominal =  $nominal * -1; 
			   }
            DB::connection()->table("t_cuti")
                
                ->insert([
                    "p_karyawan_id"=>($request->get("karyawan")),
                    "jenis"=>($request->get("tipe")),
                    "tahun"=>($request->get("tahun")),
                    "tanggal"=>($request->get("tanggal")),
                    "nominal"=>($nominal),
                    "keterangan"=>($keterangan),
                    "create_date"=>(date('Y-m-d H:i:s')),
                    "create_by"=>($idUser),
                    ]);
            DB::commit();
            return redirect()->route('be.laporan_cuti_karyawan',['nama='.$request->get("karyawan").'&Cari=Cari'])->with('success','Laporan Cuti karyawan Berhasil di Ubah!');
        }
        catch(\Exeception $e){
            DB::rollback();
            return redirect()->back()->with('error',$e);
        }
    	
    }public function rekap_cuti_karyawan(Request $request)
    {
    	$help = new Helper_function();
    	$iduser=Auth::user()->id;
         $sqluser="SELECT p_recruitment.foto,role,p_karyawan_pekerjaan,p_karyawan_pekerjaan.m_lokasi_id,user_entitas_access FROM users
left join m_role on m_role.m_role_id=users.role
left join p_karyawan on p_karyawan.user_id=users.id
left join p_karyawan_pekerjaan on p_karyawan_pekerjaan.p_karyawan_id=p_karyawan.p_karyawan_id
left join p_recruitment on p_recruitment.p_recruitment_id=p_karyawan.p_recruitment_id
where users.id=$iduser";
        $user=DB::connection()->select($sqluser);
            $id_lokasi = Auth::user()->user_entitas;
        if($id_lokasi and $id_lokasi!=-1) 
			$whereLokasi = "AND p_karyawan_pekerjaan.m_lokasi_id in($id_lokasi)";					
		else
			$whereLokasi = "";	
        
			if($request->entitas){ 
			 	$id_lokasi = $request->entitas;
				$whereLokasi2 = "AND p_karyawan_pekerjaan.m_lokasi_id = $id_lokasi";			
							
			}else{
				$whereLokasi2 = "";			
				
			}
        
        //$id=$idkar[0]->p_karyawan_id;
        $all = array();
        $data = array();
        
			$cuti = $help->query_cuti2(null,'all');
       	
       	
           
        $sqlusers="SELECT p_karyawan.*,m_departemen.nama as nmdept FROM p_karyawan
LEFT JOIN p_karyawan_pekerjaan on p_karyawan_pekerjaan.p_karyawan_id=p_karyawan.p_karyawan_id
LEFT JOIN m_departemen on m_departemen.m_departemen_id=p_karyawan_pekerjaan.m_departemen_id
                WHERE 1=1 and p_karyawan.active=1 $whereLokasi  order by p_karyawan.nama";
        $users=DB::connection()->select($sqlusers); 
        
        $entitas=DB::connection()->select("select * from m_lokasi where active=1 and sub_entitas=0 $whereLokasi ");
        
       	$listkaryawan = "SELECT * FROM p_karyawan join p_karyawan_pekerjaan on p_karyawan_pekerjaan.p_karyawan_id = p_karyawan.p_karyawan_id where p_karyawan.active= 1 $whereLokasi2 $whereLokasi order by p_karyawan.nama"; 
		$listkaryawan = DB::connection()->select($listkaryawan);
        return view('backend.rekap_cuti_karyawan.rekap_cuti_karyawan',compact('help','cuti','request','listkaryawan','users','entitas'));
    }public function rekap_cuti_karyawan2(Request $request)
    {
    	$help = new Helper_function();
    	$iduser=Auth::user()->id;
         $sqluser="SELECT p_recruitment.foto,role,p_karyawan_pekerjaan,p_karyawan_pekerjaan.m_lokasi_id,user_entitas_access FROM users
left join m_role on m_role.m_role_id=users.role
left join p_karyawan on p_karyawan.user_id=users.id
left join p_karyawan_pekerjaan on p_karyawan_pekerjaan.p_karyawan_id=p_karyawan.p_karyawan_id
left join p_recruitment on p_recruitment.p_recruitment_id=p_karyawan.p_recruitment_id
where users.id=$iduser";
        $user=DB::connection()->select($sqluser);
           $id_lokasi = Auth::user()->user_entitas;
	        if($id_lokasi and $id_lokasi!=-1) 
				$whereLokasi = "AND p_karyawan_pekerjaan.m_lokasi_id in($id_lokasi)";					
			else
				$whereLokasi = "";	
	        
			if($request->entitas){ 
			 	$id_lokasi = $request->entitas;
				$whereLokasi2 = "AND p_karyawan_pekerjaan.m_lokasi_id = $id_lokasi";			
							
			}else{
				$whereLokasi2 = "";			
				
			}
        
        //$id=$idkar[0]->p_karyawan_id;
        $all = array();
        $data = array();
        
			$cuti = $help->query_cuti2(null,'all');
       	
       	
           
        $sqlusers="SELECT p_karyawan.*,m_departemen.nama as nmdept FROM p_karyawan
LEFT JOIN p_karyawan_pekerjaan on p_karyawan_pekerjaan.p_karyawan_id=p_karyawan.p_karyawan_id
LEFT JOIN m_departemen on m_departemen.m_departemen_id=p_karyawan_pekerjaan.m_departemen_id
                WHERE 1=1 and p_karyawan.active=1 $whereLokasi  order by p_karyawan.nama";
        $users=DB::connection()->select($sqlusers); 
        
        $entitas=DB::connection()->select("select * from m_lokasi where active=1 and sub_entitas=0 $whereLokasi ");
        
       	$listkaryawan = "SELECT * FROM p_karyawan join p_karyawan_pekerjaan on p_karyawan_pekerjaan.p_karyawan_id = p_karyawan.p_karyawan_id where p_karyawan.active= 1 $whereLokasi2 $whereLokasi order by p_karyawan.nama"; 
		$listkaryawan = DB::connection()->select($listkaryawan);
        return view('backend.rekap_cuti_karyawan.rekap_cuti_karyawan_perhitungan2',compact('help','cuti','request','listkaryawan','users','entitas'));
    }public function laporan_cuti(Request $request)
    {
    	$help = new Helper_function();
    	$iduser=Auth::user()->id;
        $sqlidkar="select * from p_karyawan 
        join p_karyawan_pekerjaan on p_karyawan.p_karyawan_id = p_karyawan_pekerjaan.p_karyawan_id
        where user_id=$iduser";
        $id=null;
        $m_cuti=null;
        $cuti=null;
        $dcuti=null;
        $idkar=null;
        //$id=$idkar[0]->p_karyawan_id;
        $all = array();
        $data = array();
        
		$date=array();
		$tanggal_loop=array();
        if($request->get('nama')){
			
        	$id = $request->get('nama');
        	$sqlidkar="select * from p_karyawan 
		        join p_karyawan_pekerjaan on p_karyawan.p_karyawan_id = p_karyawan_pekerjaan.p_karyawan_id
		        where p_karyawan.p_karyawan_id=$id";
	        $idkar=DB::connection()->select($sqlidkar);
			$cuti = $help->query_cuti2($idkar);
			$date2 = $cuti['date'];
			$all = $cuti['all'];
			$tanggal_loop = $cuti['tanggal_loop'];
		
		 }
       
		//print_r($date);
		//$data[$]
		 $sqluser="SELECT p_recruitment.foto,role,p_karyawan_pekerjaan,p_karyawan_pekerjaan.m_lokasi_id,user_entitas_access FROM users
left join m_role on m_role.m_role_id=users.role
left join p_karyawan on p_karyawan.user_id=users.id
left join p_karyawan_pekerjaan on p_karyawan_pekerjaan.p_karyawan_id=p_karyawan.p_karyawan_id
left join p_recruitment on p_recruitment.p_recruitment_id=p_karyawan.p_recruitment_id
where users.id=$iduser";
        $user=DB::connection()->select($sqluser);
        $id_lokasi = Auth::user()->user_entitas;
        if($id_lokasi and $id_lokasi!=-1) 
			$whereLokasi = "AND p_karyawan_pekerjaan.m_lokasi_id in($id_lokasi)";					
		else
			$whereLokasi = "";	
        
		$sqlusers="SELECT p_karyawan.*,m_departemen.nama as nmdept FROM p_karyawan
LEFT JOIN p_karyawan_pekerjaan on p_karyawan_pekerjaan.p_karyawan_id=p_karyawan.p_karyawan_id
LEFT JOIN m_departemen on m_departemen.m_departemen_id=p_karyawan_pekerjaan.m_departemen_id
                WHERE 1=1  $whereLokasi order by p_karyawan.nama";
        $users=DB::connection()->select($sqlusers); 
        $nama = $id;
        //print_r($users);die;
        return view('backend.cuti.laporan_cuti_karyawan',compact('help','nama','cuti','id','data','m_cuti','dcuti','idkar','all','date','tanggal_loop','users'));
    }
}
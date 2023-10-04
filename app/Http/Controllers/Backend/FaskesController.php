<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Auth;
use App\Helper_function;

class FaskesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function saldo_faskes(Request $request)
    {
        
        $iduser=Auth::user()->id;
		$id_lokasi = Auth::user()->user_entitas;
        if($id_lokasi and $id_lokasi!=-1) 
			$whereLokasi = "AND m_lokasi_id in($id_lokasi)";					
		else
			$whereLokasi = "";			
			
		if($request->entitas){ 
		 	$id_lokasi = $request->entitas;
			$whereLokasi2 = "AND m_lokasi_id = $id_lokasi";			
						
		}else{
			$whereLokasi2 = "";			
			
		}
		
		$sqlfasilitas="SELECT * FROM p_karyawan b
				join p_karyawan_pekerjaan on p_karyawan_pekerjaan.p_karyawan_id = b.p_karyawan_id
                WHERE 1=1 and b.active=1 $whereLokasi $whereLokasi2 order by nama";
        $fasilitas=DB::connection()->select($sqlfasilitas);
		$entitas=DB::connection()->select("select * from m_lokasi where active=1 and sub_entitas=0 $whereLokasi ");
		
		$help = new Helper_function();
        return view('backend.faskes.saldo',compact('fasilitas','help','entitas','request'));
    }public function tambah_pengajuan_faskes(Request $request)
    {
    	
	    	$karyawan = "SELECT * from p_karyawan_faskes where active=1";
    	$faskes=DB::connection()->select($karyawan);
    	$list='';
		foreach($faskes as $f){
			if($f->p_karyawan_id)
				$list .= $f->p_karyawan_id.',';
		}
		$list.='-1';
	    	$karyawan = "SELECT * from p_karyawan where p_karyawan.active=1 and p_karyawan.p_karyawan_id  in($list)";
    	$karyawan=DB::connection()->select($karyawan);
		
    	return view('backend.faskes.tambah_pengajuan',compact('karyawan'));
    }public function edit_pengajuan_faskes($id)
    {
    	
    	
    	$karyawan = "(select p_karyawan_faskes.*,p_karyawan.nama from p_karyawan_faskes left join p_karyawan on p_karyawan.p_karyawan_id = p_karyawan_faskes.p_karyawan_id where p_karyawan_faskes_id=$id)";
    	$faskes=DB::connection()->select($karyawan);
    	$karyawan = "SELECT * from p_karyawan where p_karyawan.active=1 and p_karyawan.p_karyawan_id in(select p_karyawan_faskes.p_karyawan_id from p_karyawan_faskes where p_karyawan_faskes.active=1)";
    	$karyawan=DB::connection()->select($karyawan);
		$help = new Helper_function();
    	return view('backend.faskes.edit_pengajuan',compact('karyawan','faskes','help','id'));
    }public function tambah_saldo_faskes(Request $request)
    {
    	
	    	$karyawan = "SELECT * from p_karyawan_faskes where active=1";
    	$faskes=DB::connection()->select($karyawan);
    	$list='';
		foreach($faskes as $f){
			if($f->p_karyawan_id)
				$list .= $f->p_karyawan_id.',';
		}
		$list.='-1';
	    	$karyawan = "SELECT * from p_karyawan where p_karyawan.active=1 and p_karyawan.p_karyawan_id not in($list)";
    	$karyawan=DB::connection()->select($karyawan);
		
    	return view('backend.faskes.tambah_saldo',compact('karyawan'));
    }public function edit_saldo_faskes($id)
    {
    	
    	
    	$karyawan = "(select p_karyawan_faskes.*,p_karyawan.nama from p_karyawan_faskes left join p_karyawan on p_karyawan.p_karyawan_id = p_karyawan_faskes.p_karyawan_id where p_karyawan_faskes_id=$id)";
    	$faskes=DB::connection()->select($karyawan);
    	$karyawan = "SELECT * from p_karyawan where p_karyawan.active=1 and p_karyawan.p_karyawan_id in(select p_karyawan_faskes.p_karyawan_id from p_karyawan_faskes where p_karyawan_faskes.active=1)";
    	$karyawan=DB::connection()->select($karyawan);
		$help = new Helper_function();
    	return view('backend.faskes.edit_saldo',compact('karyawan','faskes','help','id'));
    }public function simpan_faskes(Request $request)
    {
    	DB::beginTransaction();
        try{
            $idUser=Auth::user()->id;
            $help = new Helper_function();
            DB::connection()->table("p_karyawan_faskes")
                ->insert([
                    "p_karyawan_id"=>($request->get("karyawan")),
                    "saldo"=>$help->hapusRupiah($request->get("saldo")),
                    "active"=>1,
                    
                ]);
            DB::commit();
            return redirect()->route('be.saldo_faskes')->with('success','Saldo Faskes Karyawan Berhasil di input!');
        }
        catch(\Exeception $e){
            DB::rollback();
            return redirect()->back()->with('error',$e);
        }
    	
    }public function simpan_pengajuan_faskes(Request $request)
    {
    	DB::beginTransaction();
        try{
            $idUser=Auth::user()->id;
            $help = new Helper_function();
            DB::connection()->table("t_faskes")
                ->insert([
                    "p_karyawan_id"=>($request->get("karyawan")),
                    "nominal"=>$help->hapusRupiah($request->get("nominal")),
                    "keterangan"=>($request->get("keterangan")),
                    "tanggal_pengajuan"=>($request->get("tanggal")),
                    "create_date"=>date('Y-m-d H:i:s'),
                    "create_by"=>$idUser,
                    "appr_status"=>1,
                    "jenis"=>2,
                    
                ]);
            DB::commit();
            return redirect()->route('be.pengajuan_faskes')->with('success','Saldo Faskes Karyawan Berhasil di input!');
        }
        catch(\Exeception $e){
            DB::rollback();
            return redirect()->back()->with('error',$e);
        }
    	
    }public function hapus_pengajuan_faskes(Request $request,$id)
    {
    	DB::beginTransaction();
        try{
            $idUser=Auth::user()->id;
            $help = new Helper_function();
            DB::connection()->table("t_faskes")
            	->where('t_faskes_id',$id)
                ->update([
                   
                    "active"=>0,
                    "update_date"=>date('Y-m-d H:i:s'),
                    "update_by"=>$idUser,
                    
                    
                ]);
            DB::commit();
            return redirect()->route('be.pengajuan_faskes')->with('success','Pengajuan Faskes Karyawan Berhasil di hapus!');
        }
        catch(\Exeception $e){
            DB::rollback();
            return redirect()->back()->with('error',$e);
        }
    	
    }public function update_faskes(Request $request,$id)
    {
    	DB::beginTransaction();
        try{
            $idUser=Auth::user()->id;
            $help = new Helper_function();
            DB::connection()->table("p_karyawan_faskes")
            	->where('p_karyawan_faskes_id',$id)
                ->update([
                    "p_karyawan_id"=>($request->get("karyawan")),
                    "saldo"=>$help->hapusRupiah($request->get("saldo")),
                    
                    
                ]);
            DB::commit();
            return redirect()->route('be.saldo_faskes')->with('success','Saldo Faskes Karyawan Berhasil di input!');
        }
        catch(\Exeception $e){
            DB::rollback();
            return redirect()->back()->with('error',$e);
        }
    	
    }public function appr_faskes(Request $request,$id)
    {
    	DB::beginTransaction();
        try{
            $idUser=Auth::user()->id;
            $help = new Helper_function();
            DB::connection()->table("t_faskes")
            	->where('t_faskes_id',$id)
                ->update([
                    "appr_status"=>1
                    
                    
                ]);
            DB::commit();
            return redirect()->route('be.saldo_faskes')->with('success','Saldo Faskes Karyawan Berhasil di input!');
        }
        catch(\Exeception $e){
            DB::rollback();
            return redirect()->back()->with('error',$e);
        }
    	
    }public function appr_tolak_faskes(Request $request,$id)
    {
    	DB::beginTransaction();
        try{
            $idUser=Auth::user()->id;
            $help = new Helper_function();
            DB::connection()->table("t_faskes")
            	->where('t_faskes_id',$id)
                ->update([
                    "appr_status"=>2
                    
                    
                ]);
            DB::commit();
            return redirect()->route('be.saldo_faskes')->with('success','Saldo Faskes Karyawan Berhasil di input!');
        }
        catch(\Exeception $e){
            DB::rollback();
            return redirect()->back()->with('error',$e);
        }
    	
    }public function hapus_faskes(Request $request,$id)
    {
    	DB::beginTransaction();
        try{
            $idUser=Auth::user()->id;
            $help = new Helper_function();
            DB::connection()->table("t_faskes")
            	->where('t_faskes_id',$id)
                ->update([
                    "active"=>0
                    
                    
                ]);
            DB::commit();
            return redirect()->route('be.laporan_faskes')->with('success','Saldo Faskes Karyawan Berhasil di input!');
        }
        catch(\Exeception $e){
            DB::rollback();
            return redirect()->back()->with('error',$e);
        }
    	
    }
    public function hapus_saldo_faskes($id)
    {
    	DB::beginTransaction();
        try{
            $idUser=Auth::user()->id;
            $help = new Helper_function();
            DB::connection()->table("p_karyawan_faskes")
            ->where('p_karyawan_faskes_id',$id)
                ->update([
                   
                    "active"=>0,
                    
                ]);
            DB::commit();
            return redirect()->route('be.saldo_faskes')->with('success','Saldo Faskes Karyawan Berhasil di hapus!');
        }
        catch(\Exeception $e){
            DB::rollback();
            return redirect()->back()->with('error',$e);
        }
    	
    }public function laporan_faskes(Request $request)
    {
        $id = $request->get('karyawan');
        $faskes ='';
		$help = new Helper_function();
        if($request->get('karyawan')){
			
        	$faskes = $help->lap_faskes($id);
		}
		
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
			$whereLokasi = "AND m_lokasi_id in($id_lokasi)";					
		else
			$whereLokasi = "";	
        
		$sqlfasilitas="SELECT * FROM p_karyawan
		join p_karyawan_pekerjaan on p_karyawan_pekerjaan.p_karyawan_id = p_karyawan.p_karyawan_id
                WHERE 1=1 and p_karyawan.active=1 $whereLokasi order by nama ";
        $karyawan=DB::connection()->select($sqlfasilitas);
		
        return view('backend.faskes.laporan',compact('faskes','help','id','karyawan'));
    }public function generate_lokasi_pengajuan(Request $request)
    {
    	
    	DB::beginTransaction();
        try{
        	$sqlfasilitas="SELECT * FROM t_faskes
        		join p_karyawan_pekerjaan on p_karyawan_pekerjaan.p_karyawan_id = t_faskes.p_karyawan_id
					 where jenis=2 ";
			$karyawan=DB::connection()->select($sqlfasilitas);
            $idUser=Auth::user()->id;
            $help = new Helper_function();
            foreach($karyawan as $karyawan){
            	
	            DB::connection()->table("t_faskes")
	            ->where('t_faskes_id',$karyawan->t_faskes_id)
	                ->update([
	                   
	                    "m_lokasi_faskes_id"=>$karyawan->m_lokasi_id,
	                    
	                ]);
            }
            DB::commit();
            return redirect()->route('be.saldo_faskes')->with('success','Saldo Faskes Karyawan Berhasil di hapus!');
        }
        catch(\Exeception $e){
            DB::rollback();
            return redirect()->back()->with('error',$e);
        }
    	
    }public function pengajuan_faskes(Request $request)
    {
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
			$whereLokasi = "AND m_lokasi.m_lokasi_id in($id_lokasi)";					
		else
			$whereLokasi = "";	
        
			
        $sqlfasilitas="SELECT *,p_karyawan.nama as nama, m_lokasi.nama as nmlokasi FROM p_karyawan
                join p_karyawan_pekerjaan on p_karyawan_pekerjaan.p_karyawan_id = p_karyawan.p_karyawan_id
                 left join m_lokasi on p_karyawan_pekerjaan.m_lokasi_id = m_lokasi.m_lokasi_id
                WHERE 1=1 and p_karyawan.active=1 $whereLokasi  order by p_karyawan.nama ";
        $karyawan=DB::connection()->select($sqlfasilitas);
		
		if($request->entitas){
			$whereentitas = "AND m_lokasi_faskes_id = ".$request->entitas;	
		}else
			$whereentitas = "";
		$where = '';
		if($request->get('karyawan'))
			$where .= ' and t_faskes.p_karyawan_id='.$request->get('karyawan');
		if($request->get('tgl_awal'))
			$where .= " and tanggal_pengajuan>='".$request->get('tgl_awal')."'";
		if($request->get('tgl_akhir'))
			$where .= "  and tanggal_pengajuan<='".$request->get('tgl_akhir')."'";
					
        $sqlfasilitas="SELECT *,m_lokasi.kode as nmlokasi,p_karyawan_keluarga.nama as nama_terkait,t_faskes.p_karyawan_keluarga_id FROM t_faskes
        left join m_lokasi on t_faskes.m_lokasi_faskes_id = m_lokasi.m_lokasi_id
        left join p_karyawan_keluarga on t_faskes.p_karyawan_keluarga_id = p_karyawan_keluarga.p_karyawan_keluarga_id
        join p_karyawan on t_faskes.p_karyawan_id = p_karyawan.p_karyawan_id
        join p_karyawan_pekerjaan on p_karyawan_pekerjaan.p_karyawan_id = p_karyawan.p_karyawan_id
                WHERE 1=1 and jenis = 2 $where $whereLokasi $whereentitas and t_faskes.active = 1 order by appr_status, tanggal_pengajuan desc,t_faskes.create_date  desc ";
        $faskes=DB::connection()->select($sqlfasilitas);
        $entitas=DB::connection()->select("select * from m_lokasi where active=1 and sub_entitas=0 $whereLokasi");
		
		
		
		$help = new Helper_function();
        return view('backend.faskes.pengajuan',compact('faskes','help','request','karyawan','entitas'));
    }
}
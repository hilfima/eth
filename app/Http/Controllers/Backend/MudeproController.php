<?php

namespace App\Http\Controllers\Backend;

use App\absenpro_xls;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Auth;
use Response;
use Maatwebsite\Excel\Facades\Excel;
use App\Eloquent\AbsenLog;
use App\Eloquent\Absen;
use App\Helper_function;

class MudeproController extends Controller
{
	public function __construct()
	{
		$this->middleware('auth');
	}
	
	public function mudepro()
    {
    	
    	$iduser=Auth::user()->id;
	
		$mudepro = DB::connection()->select("
			select *
				,m_jabatan.nama as nmjabatan
				,m_pangkat.nama as nmpangkat
				,m_lokasi.nama as nmentitas
				,m_departemen.nama as nmdepartemen
				,p_karyawan.nama as nmappr
				,a.nama as nama_pengaju
				,t_mudepro.create_date
				,(SELECT STRING_AGG(nama,',') FROM t_mudepro_karyawan join p_karyawan on t_mudepro_karyawan.p_list_karyawan_id =p_karyawan.p_karyawan_id where t_mudepro.t_mudepro_id = t_mudepro_karyawan.t_mudepro_id
) as karyawan
			 from t_mudepro 
			left join p_karyawan a on a.p_karyawan_id = t_mudepro.p_karyawan_id
			left join p_karyawan on t_mudepro.appr_by = p_karyawan.p_karyawan_id
			left join m_jabatan on t_mudepro.perpindahan_jabatan_id = m_jabatan.m_jabatan_id
			left join m_pangkat on t_mudepro.perpindahan_pangkat_id = m_pangkat.m_pangkat_id
			left join m_lokasi on t_mudepro.perpindahan_entitas_id = m_lokasi.m_lokasi_id
			left join m_departemen on t_mudepro.perpindahan_departement_id = m_departemen.m_departemen_id
			");
    	
    	return view('backend.mudepro.mudepro',compact('mudepro'));
    }
	public function finali_mudepro($id)
    {
    	$mudepro = DB::connection()->select("
			select *
				,m_jabatan.nama as nmjabatan
				,m_pangkat.nama as nmpangkat
				,m_lokasi.nama as nmentitas
				,m_departemen.nama as nmdepartemen
				,p_karyawan.nama as nmappr
				,a.nama as nama_pengaju
				,t_mudepro.create_date
				,(SELECT STRING_AGG(nama,',') FROM t_mudepro_karyawan join p_karyawan on t_mudepro_karyawan.p_list_karyawan_id =p_karyawan.p_karyawan_id where t_mudepro.t_mudepro_id = t_mudepro_karyawan.t_mudepro_id
) as karyawan
			 from t_mudepro 
			left join p_karyawan a on a.p_karyawan_id = t_mudepro.p_karyawan_id
			left join p_karyawan on t_mudepro.appr_by = p_karyawan.p_karyawan_id
			left join m_jabatan on t_mudepro.perpindahan_jabatan_id = m_jabatan.m_jabatan_id
			left join m_pangkat on t_mudepro.perpindahan_pangkat_id = m_pangkat.m_pangkat_id
			left join m_lokasi on t_mudepro.perpindahan_entitas_id = m_lokasi.m_lokasi_id
			left join m_departemen on t_mudepro.perpindahan_departement_id = m_departemen.m_departemen_id
			where t_mudepro_id = $id and t_mudepro.active=1
			
			");
    	$dept 			= "Select * from t_mudepro_karyawan where active=1 and t_mudepro_id = $id ";
        $mudepro_karyawan  	=	DB::connection()->select($dept);
        
    	$dept 			= 'Select *,m_jabatan.nama,m_lokasi.nama as nama_entitas from 
    					m_jabatan 
    					join m_lokasi on m_jabatan.m_lokasi_id = m_lokasi.m_lokasi_id
    					where m_jabatan.active=1';
        $jabatan  	=	DB::connection()->select($dept);
        
        $dept 			= 'Select * from m_departemen where active=1';
        $departemen  	=	DB::connection()->select($dept);
        
		$dept 		= 'Select * from m_lokasi where  sub_entitas=0 and active=1';
        $lokasi 	=	DB::connection()->select($dept);
        
		$dept 		= 'Select * from m_pangkat where active=1';
        $pangkat 	=	DB::connection()->select($dept);
        
        $dept 		= 'Select * from p_karyawan where active=1 order by nama';
        $karyawan 	=	DB::connection()->select($dept); 
    	return view('backend.mudepro.finalisasi',compact('mudepro','karyawan','jabatan','departemen','lokasi','pangkat','mudepro_karyawan','id'));
    }
	
	public function edit_mudepro($id)
    {
    	$mudepro = DB::connection()->select("
			select *
				,m_jabatan.nama as nmjabatan
				,m_pangkat.nama as nmpangkat
				,m_lokasi.nama as nmentitas
				,m_departemen.nama as nmdepartemen
				,p_karyawan.nama as nmappr
				,(SELECT STRING_AGG(nama,',') FROM t_mudepro_karyawan join p_karyawan on t_mudepro_karyawan.p_list_karyawan_id =p_karyawan.p_karyawan_id where t_mudepro.t_mudepro_id = t_mudepro_karyawan.t_mudepro_id
) as karyawan
			 from t_mudepro 
			left join p_karyawan on t_mudepro.appr_by = p_karyawan.p_karyawan_id
			left join m_jabatan on t_mudepro.perpindahan_jabatan_id = m_jabatan.m_jabatan_id
			left join m_pangkat on t_mudepro.perpindahan_pangkat_id = m_pangkat.m_pangkat_id
			left join m_lokasi on t_mudepro.perpindahan_entitas_id = m_lokasi.m_lokasi_id
			left join m_departemen on t_mudepro.perpindahan_departement_id = m_departemen.m_departemen_id
			where t_mudepro_id = $id and t_mudepro.active=1
			
			");
    	$dept 			= "Select * from t_mudepro_karyawan where active=1 and t_mudepro_id = $id";
        $mudepro_karyawan  	=	DB::connection()->select($dept);
        
    	$dept 			= 'Select * from m_jabatan where active=1';
        $jabatan  	=	DB::connection()->select($dept);
        
        $dept 			= 'Select * from m_departemen where active=1';
        $departemen  	=	DB::connection()->select($dept);
        
		$dept 		= 'Select * from m_lokasi where  sub_entitas=0 and active=1';
        $lokasi 	=	DB::connection()->select($dept);
        
		$dept 		= 'Select * from m_pangkat where active=1';
        $pangkat 	=	DB::connection()->select($dept);
        
        $dept 		= 'Select * from p_karyawan where active=1 order by nama';
        $karyawan 	=	DB::connection()->select($dept); 
    	return view('backend.mudepro.edit_mudepro',compact('mudepro','karyawan','jabatan','departemen','lokasi','pangkat','mudepro_karyawan','id'));
    }
    //update_mudepro 
    
    public function update_mudepro(Request $request,$id)
    {
    	DB::beginTransaction();
        try {
        	$iduser=Auth::user()->id;
        	
        	$data['status'] = $request->status;
            DB::connection()->table("t_mudepro")
            ->where('t_mudepro_id',$id)
                ->update($data);
            
            $asesmen_hc = $request->asesmen_hc; 
            foreach($asesmen_hc as $key => $value){
    	    	
            	$data2['deskripsi_asesmen_hc'] =$request->asesmen_hc[$key]; 
            	$data2['status_asesmen_hc'] =$request->asesmen_status_hc[$key];
            	
            	if ($request->file('asesmen_file_hc_'.$key)) { //echo 'masuk';die;
					$file = $request->file('asesmen_file_hc_'.$key);
					$destination = "dist/img/file/";
					$path = 'asesmen_mudepro-' . date('ymdhis') . '-' . $file->getClientOriginalName();
					$file->move($destination, $path);
					$data2['file_asesmen_hc'] =$path; 
				} 
            	
	            DB::connection()->table("t_mudepro_karyawan")
	            ->where('t_mudepro_karyawan_id',$key)
	                ->update($data2);
    	    }
                
                
                
            DB::commit();
            return redirect()->route('be.mudepro')->with('success', 'Detail Gaji Karyawan Berhasil di Hapus!');
        } catch (\Exeception $e) {
            DB::rollback();
            return redirect()->back()->with('error', $e);
        }
    	
    }
	public function update_appr_mudepro(Request $request,$id)
    {
    	DB::beginTransaction();
        try {
        	$iduser=Auth::user()->id;
        	
        	$data['status'] = 4;
            DB::connection()->table("t_mudepro")
            ->where('t_mudepro_id',$id)
                ->update($data);
            
            $asesmen_hc = $request->asesmen_hc; 
            $data = $request->data; 
            //print_r($data);
            foreach($data['jenis'] as $key => $value){
    	    	$data2['fiksasi_jenis'] =$data['jenis'][$key]; 
            	$data2['fiksasi_jabatan_id'] =$data['fiksasi_jabatan_id'][$key]; 
            	//$data2['fiksasi_departemen_id'] =$data['fiksasi_departement_id'][$key]; 
            	$data2['fiksasi_entitas_id'] =$data['fiksasi_entitas_id'][$key]; 
            	//$data2['fiksasi_pangkat_id'] =$data['fiksasi_pangkat_id'][$key]; 
            	$data2['status_perpindahan'] =$data['status_perpindahan'][$key]; 
            	if($data2['status_perpindahan']){
            		$id_karyawan = $data['karyawan'][$key]; ;
            		$data3['m_jabatan_id']=$data2['fiksasi_jabatan_id'];
            		//$data3['m_departemen_id']=$data2['fiksasi_departemen_id'];
            		$data3['m_lokasi_id']=$data2['fiksasi_entitas_id'];
            		
            		$karyawan1 = DB::connection()->select("select * from p_karyawan join p_karyawan_pekerjaan on p_karyawan.p_karyawan_id = p_karyawan_pekerjaan.p_karyawan_id where p_karyawan.p_karyawan_id  = $id_karyawan");
            		
            		DB::connection()->table("p_karyawan_pekerjaan")
		            	->where('p_karyawan_id',$id_karyawan)
		            	->where('active',1)
		                ->update($data3);
		                
		            $array = array("m_jabatan_id","m_departemen_id","m_lokasi_id","m_bank_id","m_kantor_id","m_divisi_id","m_directorat_id","m_divisi_new_id","bpjs_kantor","tgl_bpjs_kantor","norek","periode_gajian","nik","kota","is_shift","pajak_onoff");    
		          
		            $karyawan2 	= DB::connection()->select("select * from p_karyawan join p_karyawan_pekerjaan on p_karyawan.p_karyawan_id = p_karyawan_pekerjaan.p_karyawan_id where p_karyawan.p_karyawan_id  = $id_karyawan");
		            $data4['p_karyawan_id']	=$id_karyawan;
		            $data4['create_by']		=$iduser;
		            $data4['create_date']	=date('Y-m-d H:i:s');
		            foreach($karyawan1 as $karyawan){
		            	for($i=0;$i<count($array);$i++){
		            		$row = $array[$i];
			            	$data4['dari_'.$array[$i]] = $karyawan->$row;
		            	}
		            }
		            foreach($karyawan2 as $karyawan){
		            	for($i=0;$i<count($array);$i++){
		            		$row = $array[$i];
			            	$data4['ke_'.$array[$i]] = $karyawan->$row;
		            	}
		            }
		            DB::connection()->table("p_historis_pekerjaan")
		                ->insert($data4);
		                
            	}
	            
    	    }
                
                
                
            DB::commit();
            return redirect()->route('be.mudepro')->with('success', 'Detail Gaji Karyawan Berhasil di Hapus!');
        } catch (\Exeception $e) {
            DB::rollback();
            return redirect()->back()->with('error', $e);
        }
    	
    }
}
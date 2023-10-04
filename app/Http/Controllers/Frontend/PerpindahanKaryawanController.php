<?php

namespace App\Http\Controllers\Frontend;

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

class PerpindahanKaryawanController extends Controller
{
	public function __construct()
	{
		$this->middleware('auth');
	}
	
	public function mudepro()
    {
    	
    	$iduser=Auth::user()->id;
		$sqlidkar="select * from p_karyawan where user_id=$iduser";
		$idkar=DB::connection()->select($sqlidkar);
		$id=$idkar[0]->p_karyawan_id;
		
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
			where t_mudepro.p_karyawan_id = $id");
    	
    	return view('frontend.mudepro.mudepro',compact('mudepro'));
    }
    public function jabatan_entitas(Request $request)
    {
    	$lokasi_jabatan=$request->entitas;
    	$dept 			= 'Select * from m_jabatan where active=1 and m_lokasi_id='.$lokasi_jabatan;
    	$jabatan=DB::connection()->select($dept);
    	echo '<option value="">- Pilih Jabatan-</option>';
    	foreach($jabatan as $jabatan){
    		echo '<option value="'.$jabatan->m_jabatan_id.'">'.$jabatan->nama.'</option>';
    		
    	}
    }
    public function appr_mudepro()
    {
    	
    	$iduser=Auth::user()->id;
		$sqlidkar="select * from p_karyawan where user_id=$iduser";
		$idkar=DB::connection()->select($sqlidkar);
		$id=$idkar[0]->p_karyawan_id;
		
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
			where t_mudepro.appr_by = $id and status>=2");
    	
    	return view('frontend.mudepro.appr',compact('mudepro'));
    }
	public function edit_appr_mudepro($id)
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
    	$dept 			= "Select * from t_mudepro_karyawan where active=1  and status_selesai_pengajuan!=1  and t_mudepro_id = $id";
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
    	return view('frontend.mudepro.edit_appr',compact('mudepro','karyawan','jabatan','departemen','lokasi','pangkat','mudepro_karyawan','id'));
    }public function edit_mudepro($id)
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
    	$dept 			= "Select * from t_mudepro_karyawan where active=1  and t_mudepro_id = $id";
        $mudepro_karyawan  	=	DB::connection()->select($dept);
        
    	$dept 			= 'Select *,m_jabatan.nama,m_lokasi.nama as nama_entitas  from 
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
    	return view('backend.mudepro.edit_mudepro',compact('mudepro','karyawan','jabatan','departemen','lokasi','pangkat','mudepro_karyawan','id'));
    }
    public function tambah_mudepro()
    {
    	
        $dept 			= 'Select * from m_departemen where active=1';
        $departemen  	=	DB::connection()->select($dept);
        
		$dept 		= 'Select * from m_lokasi where  sub_entitas=0 and active=1';
        $lokasi 	=	DB::connection()->select($dept);
        
		$dept 		= 'Select * from m_pangkat where active=1';
        $pangkat 	=	DB::connection()->select($dept);
        
    	
    	$iduser=Auth::user()->id;
		$sqlidkar="select * from p_karyawan join p_karyawan_pekerjaan on p_karyawan_pekerjaan.p_karyawan_id = p_karyawan.p_karyawan_id where user_id=$iduser";
		$idkar=DB::connection()->select($sqlidkar);
		$id_karyawan=$idkar[0]->p_karyawan_id;
		$lokasi_jabatan=$idkar[0]->m_lokasi_id;
    	$dept 			= 'Select * from m_jabatan where active=1 and m_lokasi_id='.$lokasi_jabatan;
        $jabatan  	=	DB::connection()->select($dept);
        
    	$help = new Helper_function();
    	$jabstruk = $help->jabatan_struktural($id_karyawan);
		$atasan = $jabstruk['atasan'];
		$bawahan = $jabstruk['bawahan'];
        $dept 		= "Select *,nama_lengkap as nama from get_data_karyawan() WHERE m_jabatan_id in($bawahan) order by nama_lengkap";
        $karyawan 	=	DB::connection()->select($dept); 

		$sqlappr="SELECT * from get_data_karyawan() WHERE m_jabatan_id in($atasan) and m_pangkat_id = 6 ";
		$appr=DB::connection()->select($sqlappr);
		
    	return view('frontend.mudepro.tambah_mudepro',compact('appr','karyawan','jabatan','departemen','lokasi','pangkat'));
    
    }
    public function simpan_mudepro(Request $request)
    {
    	DB::beginTransaction();
        try {
        	$iduser=Auth::user()->id;
	    	$iduser=Auth::user()->id;
			$sqlidkar="select * from p_karyawan where user_id=$iduser";
			$idkar=DB::connection()->select($sqlidkar);
			$id_karyawan=$idkar[0]->p_karyawan_id;
        	
        	$data = $request->data;
        	$data['create_date'] = date('Y-m-d H:i:s');
        	$data['create_by'] = $iduser;
        	$data['p_karyawan_id'] = $id_karyawan;
            	$data['keterangan'] =$request->keterangan;
            DB::connection()->table("t_mudepro")
                ->insert($data);
            
            $mudepro=	DB::connection()->select(" select * from seq_t_mudepro"); 
            
            $karyawan = $request->karyawan; 
            for($i=0;$i<count($karyawan);$i++){
            	$data2['p_list_karyawan_id'] =$karyawan[$i];
            	$data2['t_mudepro_id'] =$mudepro[0]->last_value;
	            DB::connection()->table("t_mudepro_karyawan")
	                ->insert($data2);
            }
    	    
                
                
                
            DB::commit();
            return redirect()->route('fe.mudepro')->with('success', 'Detail Gaji Karyawan Berhasil di Hapus!');
        } catch (\Exeception $e) {
            DB::rollback();
            return redirect()->back()->with('error', $e);
        }
    	
    }public function update_appr_mudepro(Request $request,$id)
    {
    	DB::beginTransaction();
        try {
        	$iduser=Auth::user()->id;
	    	$iduser=Auth::user()->id;
			$sqlidkar="select * from p_karyawan where user_id=$iduser";
			$idkar=DB::connection()->select($sqlidkar);
			$id_karyawan=$idkar[0]->p_karyawan_id;
        	
        	
        	$data['appr_status'] = $request->appr;
            
            
            DB::connection()->table("t_mudepro")
			            	->where('t_mudepro_id',$id)
			                ->update($data);
                
                
            $data = $request->data;
            $count_pending = 0; 
            foreach($data['jenis'] as $key => $value){
    	    	
            	$data2['fiksasi_jenis'] =$data['jenis'][$key]; 
            	$data2['fiksasi_jabatan_id'] =$data['fiksasi_jabatan_id'][$key]; 
            	//$data2['fiksasi_departemen_id'] =$data['fiksasi_departement_id'][$key]; 
            	$data2['fiksasi_entitas_id'] =$data['fiksasi_entitas_id'][$key]; 
            	//$data2['fiksasi_pangkat_id'] =$data['fiksasi_pangkat_id'][$key]; 
            	$data2['status_perpindahan'] =$data['status_perpindahan'][$key]; 
            	if($data2['status_perpindahan']==3){
            		$data2['status_selesai_pengajuan']=0;
            		
            		$count_pending +=1; 
            	}else{
            		$data2['status_selesai_pengajuan']=1;
            	}
	            DB::connection()->table("t_mudepro_karyawan")
	            ->where('t_mudepro_karyawan_id',$key)
	                ->update($data2);
    	    }
    	    
    	    if($count_pending){
    	    	
        		$dataM['status'] = 22;
    	    }else{
        		$dataM['status'] = 3;
    	    	
    	    }
			DB::connection()->table("t_mudepro")
			->where('t_mudepro_id',$id)
			->update($dataM);   
                
                
            DB::commit();
            return redirect()->route('fe.appr_mudepro')->with('success', 'Detail Gaji Karyawan Berhasil di Hapus!');
        } catch (\Exeception $e) {
            DB::rollback();
            return redirect()->back()->with('error', $e);
        }
    	
    }
}
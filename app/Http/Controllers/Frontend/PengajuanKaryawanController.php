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

class PengajuanKaryawanController extends Controller
{
	public function __construct()
	{
		$this->middleware('auth');
	}
	public function tambah_karyawan_baru()
    {
    	$iduser=Auth::user()->id;
        $sqlidkar="select * from p_karyawan 
        	join p_karyawan_pekerjaan on p_karyawan_pekerjaan.p_karyawan_id = p_karyawan.p_karyawan_id
        	join m_jabatan on p_karyawan_pekerjaan.m_jabatan_id = m_jabatan.m_jabatan_id
        	join m_lokasi on p_karyawan_pekerjaan.m_lokasi_id = m_lokasi.m_lokasi_id
        	
        where user_id=$iduser";
        $idkar=DB::connection()->select($sqlidkar);
        $id = $idkar[0]->p_karyawan_id;
       // echo 'id_jabatan='.$idkar[0]->m_jabatan_id;
        //echo '<br>';
		$id_karyawan = $idkar[0]->p_karyawan_id;
		$m_jabatan_id = $idkar[0]->m_jabatan_id;
		
		$dept 			= 'Select * from m_departemen where active=1';
        $departemen  	=	DB::connection()->select($dept);
        
		$dept 		= 'Select * from m_lokasi where active=1';
        $lokasi 	=	DB::connection()->select($dept);
        
		$dept 		= 'Select * from m_pangkat where active=1';
        $pangkat 	=	DB::connection()->select($dept);
        
        $dept 		= 'Select * from p_karyawan join p_karyawan_pekerjaan on p_karyawan_pekerjaan.p_karyawan_id = p_karyawan.p_karyawan_id where p_karyawan.active=1 and m_lokasi_id='.($idkar[0]->m_lokasi_id).' order by nama';
        $karyawan 	=	DB::connection()->select($dept); 
        
        $dept 		= "Select * from p_karyawan_pekerjaan  where active=1 and p_karyawan_id = $id ";
        $pekerjaan 	=	DB::connection()->select($dept);
        $dept 		= "Select count(*) as count from t_karyawan  where m_lokasi_id = ".$idkar[0]->m_lokasi_id;
        $count_entitas 	=	DB::connection()->select($dept);
        
        $dept 		= "Select * from m_office  
                    left join m_office_entitas on m_office.m_office_id = m_office_entitas.m_office_id
                        where m_office.active=1 and m_office_entitas.active=1 and   m_office_entitas.m_lokasi_id = ".$pekerjaan[0]->m_lokasi_id; 
        $kantor 	=	DB::connection()->select($dept);
        //print_r($pangkat);
		
		
    	return view('frontend.pengajuan_karyawan.tambah_karyawan_baru', compact('lokasi','kantor','departemen','pangkat','pekerjaan','karyawan','idkar','count_entitas'));
    } 
	public function simpan_karyawan_baru (Request $request)
	{
        DB::beginTransaction();
        try{
            $idUser=Auth::user()->id;
            $sqlidkar="select * from p_karyawan 
        	join p_karyawan_pekerjaan on p_karyawan_pekerjaan.p_karyawan_id = p_karyawan.p_karyawan_id
        	join m_jabatan on m_jabatan.m_jabatan_id = p_karyawan_pekerjaan.m_jabatan_id
	        where user_id=$idUser";
	        $idkar=DB::connection()->select($sqlidkar);
	        
	        $insert = [
                    "m_jabatan_id"	=>	($request->get("jabatan")),
                    "posisi"		=>	($request->get("posisi")),
                    "departement_baru"		=>	($request->get("dept_baru")),
                    "m_departemen_id"		=>	($request->get("dept")),
                    "m_kantor_id"		=>	($request->get("lokasi_penempatan")),
                    "appr"		=>	($request->get("atasan")),
                    "appr_direksi"		=>	($request->get("atasan2")),
                    "status"		=>	(0),
                    "appr_status"		=>	3,
                    "m_pangkat_id"		=>	($request->get("level")),
                    "m_lokasi_id"		=>	($request->get("lokasi")),
                    "tgl_diperlukan"		=>	($request->get("tangal_diperlukan")),
                    "jumlah_dibutuhkan"		=>	trim(str_ireplace(array("A", "B","C","D","E","F","G","H","I ","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z"),array("","","","","","","","","","","","","","","","","","","","","","","","","",""),($request->get("kebutuhan")))),
                    "alasan"		=>	($request->get("alasan")),
                    "penambahan_karyawan"		=>	($request->get("alasantambah")),
                    "pergantian_karyawan_id"		=>	($request->get("alasanganti")),
                    "uraian_pekerjaan"		=>	($request->get("uraian")),
                    "kualifikasi_usia_dari"		=>	($request->get("usia_dari")),
                    "kualifikasi_usai_sampai"		=>	($request->get("usia_ke")),
                    "kualifikasi_jenis_kelamin"		=>	($request->get("jk")),
                    "kualifikasi_pendidikan"		=>	($request->get("k_pendidikan")),
                    "kualifikasi_pengalaman"		=>	($request->get("k_pengalaman")),
                    "kualifikasi_kompetisi"		=>	($request->get("k_kompetensi")),
                    "kualifikasi_keahlian"		=>	($request->get("k_keahlian")),
                    "p_karyawan_id"	=>	($idkar[0]->p_karyawan_id),
                    "deskripsi"		=>	($request->get("keterangan")),
                    "tingkat"		=>	($request->get("tingkat")),
                    
                    "create_by"		=>	$idUser,
                    "create_date" 	=> 	date("Y-m-d H:i:s")
                ];
                $kebutuhan =trim(str_ireplace(array("A", "B","C","D","E","F","G","H","I ","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z"),array("","","","","","","","","","","","","","","","","","","","","","","","","",""),($request->get("kebutuhan"))));
				if(!($request->get("atasan"))){
                    
                    $insert['final_approval'] = $kebutuhan;
                    $insert['karyawan_approve_atasan'] = $kebutuhan;
                    $insert['appr_status'] = 1;
                    $insert['status'] = 5;
                    
                }else
                if(!($request->get("atasan")) and !($request->get("atasan2"))){
                    $insert['appr'] = $idkar[0]->p_karyawan_id;
                    // $insert['appr_direksi'] = $idkar[0]->p_karyawan_id;
                    $insert['appr_date'] = date('Y-m-d');
                    // $insert['appr_direksi_date'] = date('Y-m-d');
                    $insert['appr_status'] = 1;
                    $insert['status'] = 5;
                    $insert['karyawan_approve_atasan'] = $kebutuhan;
                    // $insert['karyawan_approve_direksi'] = $kebutuhan;
                    $insert['final_approval'] = $kebutuhan;
                    // $insert['appr_direksi_status'] = 1;
                    // $insert['appr_keuangan_status'] = 1;
                }
            DB::connection()->table("t_karyawan")
                ->insert($insert);
            	 $sql = "SELECT last_value FROM seq_t_karyawan;";
                $seq=DB::connection()->select($sql);    
			if ($request->file('file')) {
				//echo 'masuk';die;
				$file = $request->file('struktur');
				$destination="dist/img/file/";
				$path='gambaran_struktur-'.date('YmdHis').$file->getClientOriginalName();
				$file->move($destination,$path);
				//echo $path;die;
				DB::connection()->table("t_karyawan")->where("t_karyawan_id",$seq[0]->last_value)
				->update([
					"file_gambaran_struktur"=>$path
				]);
			}
            DB::commit();
            return redirect()->route('fe.karyawan_baru')->with('success','Pengajuan Karyawan Berhasil di input!');
        }
        catch(\Exeception $e){
            DB::rollback();
            return redirect()->back()->with('error',$e);
        }

    }
    public function simpan_approval_karyawan_baru (Request $request,$id)
	{
        DB::beginTransaction();
        try{
            $view = $request->view;
            if($view =='approve_atasan'){
                if($request->approve_atasan==1)
                	$data['status']=5;
                else if($request->approve_atasan==2)
                	$data['status']=41;
                
                $data['appr_status']=$request->approve_atasan;
                $data['appr_date']=date('Y-m-d');
                $data['keterangan_atasan']=$request->keterangan_atasan;
                $data['final_approval']=$request->karyawan_approval_atasan;
                $data['karyawan_approve_atasan']=$request->karyawan_approval_atasan;
                
                	DB::connection()->table("t_karyawan")
            			->where("t_karyawan_id",$id)
            			->update($data);
            	$tkaryawanstatus=$data['status'];	
            			$kode = $id;
            }else if($view =='approve_keuangan'){
                if($request->approve_keuangan==1)
                	$data['status']=6;
                else if($request->approve_keuangan==2)
                	$data['status']=42;
                
                $data['appr_keuangan_status']=$request->approve_keuangan;
                $data['appr_keuangan_date']=date('Y-m-d');
                $data['keterangan_keuangan']=$request->keterangan_keuangan;
                $data['final_approval']=$request->karyawan_approval_keuangan;
                $data['karyawan_approve_keuangan']=$request->karyawan_approval_keuangan;
                
                	DB::connection()->table("t_karyawan")
            			->where("t_karyawan_id",$id)
            			->update($data);
            	$tkaryawanstatus=$data['status'];	
            	$kode = $id;
                
            }else if($view =='approve_direksi' ){
                if($request->approve_direksi==1)
                	$data['status']=2;
                else if($request->approve_direksi==2)
                	$data['status']=44;
                 
                $data['appr_direksi_status']=$request->approve_direksi;
                $data['appr_direksi_date']=date('Y-m-d');
                $data['keterangan_direksi']=$request->keterangan_direksi;
                $data['final_approval']=$request->karyawan_approval_direksi; 
                $data['karyawan_approve_direksi']=$request->karyawan_approval_direksi;
                
                	DB::connection()->table("t_karyawan")
            			->where("t_karyawan_id",$id)
            			->update($data);
            	$tkaryawanstatus=$data['status'];	
            	$kode = $id;
            }
            
            
								if($tkaryawanstatus==1)
				                        $status= 'Selesai ';
                               	else if($tkaryawanstatus==0)
                               			$status= 'Menunggu Approval Atasan'	;
                               	else if($tkaryawanstatus==5)
                               			$status= 'Sudah disetujui atasan, dan Menunggu Approval Keuangan'	;
                               	else if($tkaryawanstatus==6)
                               			$status= 'Sudah disetujui keuangan, dan Menunggu Approval Direksi'	;
								else if($tkaryawanstatus==2)
								   		$status= 'Diproses HC';	
                               	else if($tkaryawanstatus==3)
                               			$status= 'Proses Interview';	
                               	else if($tkaryawanstatus==41)
                               			$status= 'Ditolak Atasan';	
                               	else if($tkaryawanstatus==42)
                               			$status= 'Ditolak Keuangan';
                               	else if($tkaryawanstatus==44)
                               			$status= 'Ditolak Direksi';
                               	else if($tkaryawanstatus==4)
                               			$status= 'Ditolak HC';
								else
								   		$status= 'Pending'	; 
			$iduser=Auth::user()->id;
			$sqlidkar="select * from p_karyawan where user_id=$iduser";
			$idkar=DB::connection()->select($sqlidkar);
				
			$id=$idkar[0]->p_karyawan_id;
			$notifdata=DB::connection()->select("select *,CASE t_karyawan.m_jabatan_id 
			WHEN -1 THEN t_karyawan.posisi
			ELSE (select nama from m_jabatan x where t_karyawan.m_jabatan_id = x.m_jabatan_id)
		END AS namaposisi from t_karyawan where t_karyawan_id=$kode");
            DB::connection()->table("notifikasi")
                    ->insert([
                        "id_assign"=>$id,
                        "database_from"=>"t_karyawan",
                        "datebase_id"=>$kode,
                        "p_karyawan_id"=>$notifdata[0]->p_karyawan_id,
                        "date_action"=>date('Y-m-d H:i:s'),
                        "notif"=>"Pengajuan Karyawan Baru ".$notifdata[0]->namaposisi." $status",
             ]);
            DB::commit();
            return redirect()->route('fe.approval_karyawan_baru')->with('success','Pengajuan Karyawan Berhasil di input!');
        }
        catch(\Exeception $e){
            DB::rollback();
            return redirect()->back()->with('error',$e);
        }
    }
       public function update_karyawan_baru (Request $request,$id_t_karyawan)
	{
        DB::beginTransaction();
        try{
            $idUser=Auth::user()->id;
            $sqlidkar="select * from p_karyawan 
        	join p_karyawan_pekerjaan on p_karyawan_pekerjaan.p_karyawan_id = p_karyawan.p_karyawan_id
	        where user_id=$idUser";
	        $idkar=DB::connection()->select($sqlidkar);
            DB::connection()->table("t_karyawan")
            	->where("t_karyawan_id",$id_t_karyawan)
                ->update([
                    "m_jabatan_id"	=>	($request->get("jabatan")),
                    "posisi"		=>	($request->get("posisi")),
                    "m_departemen_id"		=>	($request->get("dept")),
                    "appr"		=>	($request->get("atasan")),
                    "appr_direksi"		=>	($request->get("atasan2")),
                    "status"		=>	0,
                    "appr_status"		=>	3,
                    "m_pangkat_id"		=>	($request->get("level")),
                    "m_lokasi_id"		=>	($request->get("lokasi")),
                    "tgl_diperlukan"		=>	($request->get("tangal_diperlukan")),
                    "jumlah_dibutuhkan"		=>	($request->get("kebutuhan")),
                    "alasan"		=>	($request->get("alasan")),
                    "penambahan_karyawan"		=>	($request->get("alasantambah")),
                    "pergantian_karyawan_id"		=>	($request->get("alasanganti")),
                    "uraian_pekerjaan"		=>	($request->get("uraian")),
                    "kualifikasi_usia_dari"		=>	($request->get("usia_dari")),
                    "kualifikasi_usai_sampai"		=>	($request->get("usia_ke")),
                    "kualifikasi_jenis_kelamin"		=>	($request->get("jk")),
                    "kualifikasi_pendidikan"		=>	($request->get("k_pendidikan")),
                    "kualifikasi_pengalaman"		=>	($request->get("k_pengalaman")),
                    "kualifikasi_kompetisi"		=>	($request->get("k_kompetensi")),
                    "kualifikasi_keahlian"		=>	($request->get("k_keahlian")),
                    "p_karyawan_id"	=>	($idkar[0]->p_karyawan_id),
                    "deskripsi"		=>	($request->get("keterangan")),
                    "tingkat"		=>	($request->get("tingkat")),
                    
                    "create_by"		=>	$idUser,
                    "create_date" 	=> 	date("Y-m-d H:i:s")
                ]);
            	 $sql = "SELECT last_value FROM seq_t_karyawan;";
                $seq=DB::connection()->select($sql);    
			if ($request->file('file')) {
				//echo 'masuk';die;
				$file = $request->file('struktur');
				$destination="dist/img/file/";
				$path='gambaran_struktur-'.date('YmdHis').$file->getClientOriginalName();
				$file->move($destination,$path);
				//echo $path;die;
				DB::connection()->table("t_karyawan")->where("t_karyawan_id",$id_t_karyawan)
				->update([
					"file_gambaran_struktur"=>$path
				]);
			}
            DB::commit();
            return redirect()->route('fe.karyawan_baru')->with('success','Pengajuan Karyawan Berhasil di input!');
        }
        catch(\Exeception $e){
            DB::rollback();
            return redirect()->back()->with('error',$e);
        }

    }
    public function edit_karyawan_baru($idPengjuan)
    {
    	
    	$iduser=Auth::user()->id;
        $sqlidkar="select * from p_karyawan 
        	join p_karyawan_pekerjaan on p_karyawan_pekerjaan.p_karyawan_id = p_karyawan.p_karyawan_id
        	
        where user_id=$iduser";
        $idkar=DB::connection()->select($sqlidkar);
        
		$id_karyawan = $idkar[0]->p_karyawan_id;
		$id_karyawan = $idkar[0]->p_karyawan_id;
		
		$sql = "select *
		
		
		from t_karyawan 
		where t_karyawan.active = 1 and t_karyawan_id = $idPengjuan";
		
    	$tkaryawan=DB::connection()->select($sql);
		//print_r($tkaryawan);die;
		
		$dept 			= 'Select * from m_departemen where active=1';
        $departemen  	=	DB::connection()->select($dept);
        
		$dept 		= 'Select * from m_lokasi where active=1';
        $lokasi 	=	DB::connection()->select($dept);
        
		$dept 		= 'Select * from m_pangkat where active=1';
        $pangkat 	=	DB::connection()->select($dept);
        
        $dept 		= 'Select * from p_karyawan where active=1 order by nama';
        $karyawan 	=	DB::connection()->select($dept); 
        
        $dept 		= "Select * from p_karyawan_pekerjaan  where active=1 and p_karyawan_id = $id_karyawan ";
        $pekerjaan 	=	DB::connection()->select($dept);
        //print_r($pangkat);
		
		$help=new Helper_function();
		$jabstruk = $help->jabatan_struktural($id_karyawan);
		$atasan = $jabstruk['atasan'];
		$bawahan = $jabstruk['bawahan'];
		$sejajar = $jabstruk['sejajar'];
		
		$sql = "select * from m_jabatan where active = 1 and m_jabatan_id in($bawahan)";
    	$jabatan=DB::connection()->select($sql);
    	
		
		
		$sqlappr="SELECT * from get_data_karyawan() WHERE m_jabatan_id in($atasan) ";
		$appr=DB::connection()->select($sqlappr);
		
		$sqlappr="SELECT * from get_data_karyawan() WHERE m_jabatan_id in($atasan) and m_pangkat_id = 6";
		$apprdireksi=DB::connection()->select($sqlappr);
		
    	return view('frontend.pengajuan_karyawan.edit_karyawan_baru', compact('tkaryawan','jabatan','lokasi','departemen','pangkat','pekerjaan','appr','karyawan','apprdireksi'));
    	
    }
    public function view_karyawan_baru ($id)
    {
    	$iduser=Auth::user()->id;
    	$sql = "select *,CASE t_karyawan.m_kantor_id 
		WHEN -1 THEN lokasi_penempatan
		ELSE (select c.nama from m_office c
		where t_karyawan.m_kantor_id  = c.m_office_id)
	END AS lokasi_penempatan
		
		
		from t_karyawan 
		where t_karyawan.active = 1 and t_karyawan_id = $id";
		
    	$tkaryawan=DB::connection()->select($sql);
    	$sqlidkar="select * from p_karyawan 
        	join p_karyawan_pekerjaan on p_karyawan_pekerjaan.p_karyawan_id = p_karyawan.p_karyawan_id
        where user_id=$iduser";
        $idkar=DB::connection()->select($sqlidkar);
       // echo 'id_jabatan='.$idkar[0]->m_jabatan_id;
        //echo '<br>';
		
		$dept 			= 'Select * from m_departemen where active=1';
        $departemen  	=	DB::connection()->select($dept);
        
		$dept 			= 'Select * from m_lokasi where active=1';
        $lokasi 	=	DB::connection()->select($dept);
        
		$sql = "select * from m_jabatan where active = 1 ";
    	$jabatan=DB::connection()->select($sql);
    	
    	$dept 		= 'Select * from m_pangkat where active=1';
        $pangkat 	=	DB::connection()->select($dept);
        
        $dept 		= 'Select * from p_karyawan where active=1 order by nama';
        $karyawan 	=	DB::connection()->select($dept); 
        
        $dept 		= "Select * from p_karyawan_pekerjaan  where active=1 and p_karyawan_id = $id ";
        $pekerjaan 	=	DB::connection()->select($dept);
		//return view('backend.pengajuan_karyawan.edit_karyawan_baru', );
    	$view = "";
		if(isset($_GET['view'])){
		    if($_GET['view']=='4071')
		      $view = 'approve_direksi';
		    else if($_GET['view']=='1814') 
		    $view = 'approve_atasan';
		}
    	return view('frontend.pengajuan_karyawan.view_karyawan_baru', compact('view','tkaryawan','jabatan','karyawan','pangkat','lokasi','departemen','pekerjaan'));
    } 
    public function karyawan_baru()
    {
    	
		$iduser=Auth::user()->id;
		$sqlidkar="select * from p_karyawan where user_id=$iduser";
		$idkar=DB::connection()->select($sqlidkar);
		$id=$idkar[0]->p_karyawan_id;
		$sql = "select *,
		CASE t_karyawan.m_jabatan_id 
			WHEN -1 THEN (select nama from m_lokasi a where t_karyawan.m_lokasi_id = a.m_lokasi_id)
			ELSE (select nama from m_lokasi b join p_karyawan_pekerjaan on t_karyawan.p_karyawan_id =  p_karyawan_pekerjaan.p_karyawan_id where t_karyawan.m_lokasi_id = b.m_lokasi_id)
		END AS nmlokasi,
		CASE t_karyawan.m_jabatan_id 
			WHEN -1 THEN (select nama from m_departemen a where t_karyawan.m_departemen_id = a.m_departemen_id)
			ELSE (select nama from m_departemen b join p_karyawan_pekerjaan on t_karyawan.p_karyawan_id =  p_karyawan_pekerjaan.p_karyawan_id where p_karyawan_pekerjaan.m_departemen_id= b.m_departemen_id)
		END AS nmdept,
		CASE t_karyawan.m_jabatan_id 
			WHEN -1 THEN (select nama from m_pangkat a where t_karyawan.m_pangkat_id = a.m_pangkat_id)
			ELSE (select b.nama from m_pangkat b join p_karyawan_pekerjaan on t_karyawan.p_karyawan_id =  p_karyawan_pekerjaan.p_karyawan_id 
			join m_jabatan j on  p_karyawan_pekerjaan.m_jabatan_id = j.m_jabatan_id
			where j.m_pangkat_id = b.m_pangkat_id)
		END AS nmlevel,
		CASE t_karyawan.m_jabatan_id 
			WHEN -1 THEN t_karyawan.posisi
			ELSE (select nama from m_jabatan x where t_karyawan.m_jabatan_id = x.m_jabatan_id)
		END AS namaposisi,
		
		CASE t_karyawan.m_kantor_id 
			WHEN -1 THEN lokasi_penempatan
			ELSE (select c.nama from m_office c
			where t_karyawan.m_kantor_id  = c.m_office_id)
		END AS lokasi_penempatan
		
		
		
		
		from t_karyawan 
		where t_karyawan.active = 1
		
		and p_karyawan_id = $id
		";
		
    	$tkaryawan=DB::connection()->select($sql);
		//print_r($tkaryawan);die;
		return view('frontend.pengajuan_karyawan.karyawan_baru', compact('tkaryawan'));
	}
	public function approval_karyawan_baru()
    {
    	
		$iduser=Auth::user()->id;
		$sqlidkar="select * from p_karyawan where user_id=$iduser";
		$idkar=DB::connection()->select($sqlidkar);
		$id=$idkar[0]->p_karyawan_id;
		$sql = "select *,
		CASE t_karyawan.m_jabatan_id 
			WHEN -1 THEN (select nama from m_lokasi a where t_karyawan.m_lokasi_id = a.m_lokasi_id)
			ELSE (select nama from m_lokasi b join p_karyawan_pekerjaan on t_karyawan.p_karyawan_id =  p_karyawan_pekerjaan.p_karyawan_id where t_karyawan.m_lokasi_id = b.m_lokasi_id)
		END AS nmlokasi,
		CASE t_karyawan.m_jabatan_id 
			WHEN -1 THEN (select nama from m_departemen a where t_karyawan.m_departemen_id = a.m_departemen_id)
			ELSE (select nama from m_departemen b join p_karyawan_pekerjaan on t_karyawan.p_karyawan_id =  p_karyawan_pekerjaan.p_karyawan_id where p_karyawan_pekerjaan.m_departemen_id= b.m_departemen_id)
		END AS nmdept,
		CASE t_karyawan.m_jabatan_id 
			WHEN -1 THEN (select nama from m_pangkat a where t_karyawan.m_pangkat_id = a.m_pangkat_id)
			ELSE (select b.nama from m_pangkat b join p_karyawan_pekerjaan on t_karyawan.p_karyawan_id =  p_karyawan_pekerjaan.p_karyawan_id 
			join m_jabatan j on  p_karyawan_pekerjaan.m_jabatan_id = j.m_jabatan_id
			where j.m_pangkat_id = b.m_pangkat_id)
		END AS nmlevel,
		CASE t_karyawan.m_jabatan_id 
			WHEN -1 THEN t_karyawan.posisi
			ELSE (select nama from m_jabatan x where t_karyawan.m_jabatan_id = x.m_jabatan_id)
		END AS namaposisi,CASE t_karyawan.m_kantor_id 
		WHEN -1 THEN lokasi_penempatan
		ELSE (select c.nama from m_office c
		where t_karyawan.m_kantor_id  = c.m_office_id)
	END AS lokasi_penempatan
	
		
		
		from t_karyawan 
		where t_karyawan.active = 1
		
		and (appr  = $id or appr_direksi=$id)
		
		
		";
		
    	$tkaryawan=DB::connection()->select($sql);
		//print_r($tkaryawan);die;
		return view('frontend.pengajuan_karyawan.approval_karyawan_baru', compact('tkaryawan','id'));
	}
	public function upload_interview (Request $request,$id, $id_kandidat)
    {
		
		return view('frontend.pengajuan_karyawan.upload_interview',compact('id_kandidat','id'));
	}
	public function simpan_upload_interview (Request $request,$id, $id_kandidat)
    {
		
		if ($request->file('file_interview1')) {
			//echo 'masuk';die;
			$file = $request->file('file_interview1');
			$destination="dist/img/file/";
			$path='file_interview1-'.date('ymdhis').'-'.$file->getClientOriginalName();
			$file->move($destination,$path);

			//echo $path;die;
			DB::connection()->table("t_karyawan_kandidat")->where("t_karyawan_kandidat_id",$id_kandidat)
			->update([
			"file_interview1"=>$path
			]);
		}
		return redirect()->route('fe.list_database_kandidat',$id)->with('success','Kandidat Berhasil di input!');
	}
	public function list_database_kandidat($id)
	{
		$sql=" select * from t_karyawan_kandidat
		where t_karyawan_kandidat.active = 1 and t_karyawan_id =$id";
		$cv=DB::connection()->select($sql);
		return view('frontend.pengajuan_karyawan.list_database_kandidat',compact('cv','id'));
	}
	public function approve_kandidat($id,$id_kandidat)
	{

		$help = new Helper_function();
		DB::connection()->table("t_karyawan_kandidat")
			->where("t_karyawan_kandidat_id",$id_kandidat)
			->update([
				"status"=>1,
				"kode"	=>	($help->random(4)),
			]);
			return redirect()->route('fe.list_database_kandidat',$id)->with('success','Kandidat Berhasil di input!');
	}
	public function decline_kandidat($id, $id_kandidat)
	{
		DB::connection()->table("t_karyawan_kandidat")
			->where("t_karyawan_kandidat_id",$id_kandidat)
			->update([
				"status"=>10
			]);
			return redirect()->route('fe.list_database_kandidat',$id)->with('success','Kandidat Berhasil di input!');
	}
	public function approve_review($id,$id_kandidat)
	{

		$help = new Helper_function();
		DB::connection()->table("t_karyawan_kandidat")
			->where("t_karyawan_kandidat_id",$id_kandidat)
			->update([
				"status"=>5,
			]);
			return redirect()->route('fe.list_database_kandidat',$id)->with('success','Kandidat Berhasil di input!');
	}
	public function decline_review($id, $id_kandidat)
	{
		DB::connection()->table("t_karyawan_kandidat")
			->where("t_karyawan_kandidat_id",$id_kandidat)
			->update([
				"status"=>13
			]);
			return redirect()->route('fe.list_database_kandidat',$id)->with('success','Kandidat Berhasil di input!');
	}
	public function acc_karyawan_baru($id)
	{

		$help = new Helper_function();
		DB::connection()->table("t_karyawan")
			->where("t_karyawan_id",$id)
			->update([
				"status"=>5,
				"appr_date"=>date('Y-m-d'),
				"appr_status"=>1,
			]);
			$tkaryawanstatus=5;
			$kode = $id;
								if($tkaryawanstatus==1)
				                        $status= 'Selesai ';
                               	else if($tkaryawanstatus==0)
                               			$status= 'Menunggu Approval Atasan'	;
                               	else if($tkaryawanstatus==5)
                               			$status= 'Sudah disetujui atasan, dan Menunggu Approval Keuangan'	;
                               	else if($tkaryawanstatus==6)
                               			$status= 'Sudah disetujui keuangan, dan Menunggu Approval Direksi'	;
								else if($tkaryawanstatus==2)
								   		$status= 'Diproses HC';	
                               	else if($tkaryawanstatus==3)
                               			$status= 'Proses Interview';	
                               	else if($tkaryawanstatus==41)
                               			$status= 'Ditolak Atasan';	
                               	else if($tkaryawanstatus==42)
                               			$status= 'Ditolak Keuangan';
                               	else if($tkaryawanstatus==44)
                               			$status= 'Ditolak Direksi';
                               	else if($tkaryawanstatus==4)
                               			$status= 'Ditolak HC';
								else
								   		$status= 'Pending'	; 
			$iduser=Auth::user()->id;
			$sqlidkar="select * from p_karyawan where user_id=$iduser";
			$idkar=DB::connection()->select($sqlidkar);
				
			$id=$idkar[0]->p_karyawan_id;
			$notifdata=DB::connection()->select("select *,CASE t_karyawan.m_jabatan_id 
			WHEN -1 THEN t_karyawan.posisi
			ELSE (select nama from m_jabatan x where t_karyawan.m_jabatan_id = x.m_jabatan_id)
		END AS namaposisi from t_karyawan where t_karyawan_id=$kode");
            DB::connection()->table("notifikasi")
                    ->insert([
                        "id_assign"=>$id,
                        "database_from"=>"t_karyawan",
                        "datebase_id"=>$kode,
                        "p_karyawan_id"=>$notifdata[0]->p_karyawan_id,
                        "date_action"=>date('Y-m-d H:i:s'),
                        "notif"=>"Pengajuan Karyawan Baru ".$notifdata[0]->namaposisi." $status",
             ]);
			
			return redirect()->route('fe.approval_karyawan_baru')->with('success','Approval Berhasil di update!');
	}
	public function dec_karyawan_baru($id)
	{
	DB::connection()->table("t_karyawan")
			->where("t_karyawan_id",$id)
			->update([
				"status"=>41,
				"appr_date"=>date('Y-m-d'),
				"appr_status"=>2,
			]);
			
			$tkaryawanstatus=41;
			$kode = $id;
								if($tkaryawanstatus==1)
				                        $status= 'Selesai ';
                               	else if($tkaryawanstatus==0)
                               			$status= 'Menunggu Approval Atasan'	;
                               	else if($tkaryawanstatus==5)
                               			$status= 'Sudah disetujui atasan, dan Menunggu Approval Keuangan'	;
                               	else if($tkaryawanstatus==6)
                               			$status= 'Sudah disetujui keuangan, dan Menunggu Approval Direksi'	;
								else if($tkaryawanstatus==2)
								   		$status= 'Diproses HC';	
                               	else if($tkaryawanstatus==3)
                               			$status= 'Proses Interview';	
                               	else if($tkaryawanstatus==41)
                               			$status= 'Ditolak Atasan';	
                               	else if($tkaryawanstatus==42)
                               			$status= 'Ditolak Keuangan';
                               	else if($tkaryawanstatus==44)
                               			$status= 'Ditolak Direksi';
                               	else if($tkaryawanstatus==4)
                               			$status= 'Ditolak HC';
								else
								   		$status= 'Pending'	; 
			$iduser=Auth::user()->id;
			$sqlidkar="select * from p_karyawan where user_id=$iduser";
			$idkar=DB::connection()->select($sqlidkar);
				
			$id=$idkar[0]->p_karyawan_id;
			$notifdata=DB::connection()->select("select *,CASE t_karyawan.m_jabatan_id 
			WHEN -1 THEN t_karyawan.posisi
			ELSE (select nama from m_jabatan x where t_karyawan.m_jabatan_id = x.m_jabatan_id)
		END AS namaposisi from t_karyawan where t_karyawan_id=$kode");
            DB::connection()->table("notifikasi")
                    ->insert([
                        "id_assign"=>$id,
                        "database_from"=>"t_karyawan",
                        "datebase_id"=>$kode,
                        "p_karyawan_id"=>$notifdata[0]->p_karyawan_id,
                        "date_action"=>date('Y-m-d H:i:s'),
                        "notif"=>"Pengajuan Karyawan Baru ".$notifdata[0]->namaposisi." $status",
             ]);
			return redirect()->route('fe.approval_karyawan_baru')->with('success','Approval Berhasil di update!');
	}
	public function acc_karyawan_baru2($id)
	{

		$help = new Helper_function();
		DB::connection()->table("t_karyawan")
			->where("t_karyawan_id",$id)
			->update([
				"status"=>2, 
				"appr_direksi_date"=>date('Y-m-d'),
				"appr_direksi_status"=>1,
			]);
			
		$tkaryawanstatus=2;
			$kode = $id;
								if($tkaryawanstatus==1)
				                        $status= 'Selesai ';
                               	else if($tkaryawanstatus==0)
                               			$status= 'Menunggu Approval Atasan'	;
                               	else if($tkaryawanstatus==5)
                               			$status= 'Sudah disetujui atasan, dan Menunggu Approval Keuangan'	;
                               	else if($tkaryawanstatus==6)
                               			$status= 'Sudah disetujui keuangan, dan Menunggu Approval Direksi'	;
								else if($tkaryawanstatus==2)
								   		$status= 'Menunggu Proses HC';	
                               	else if($tkaryawanstatus==3)
                               			$status= 'Proses Interview';	
                               	else if($tkaryawanstatus==41)
                               			$status= 'Ditolak Atasan';	
                               	else if($tkaryawanstatus==42)
                               			$status= 'Ditolak Keuangan';
                               	else if($tkaryawanstatus==44)
                               			$status= 'Ditolak Direksi';
                               	else if($tkaryawanstatus==4)
                               			$status= 'Ditolak HC';
								else
								   		$status= 'Pending'	; 
			$iduser=Auth::user()->id;
			$sqlidkar="select * from p_karyawan where user_id=$iduser";
			$idkar=DB::connection()->select($sqlidkar);
				
			$id=$idkar[0]->p_karyawan_id;
			$notifdata=DB::connection()->select("select *,CASE t_karyawan.m_jabatan_id 
			WHEN -1 THEN t_karyawan.posisi
			ELSE (select nama from m_jabatan x where t_karyawan.m_jabatan_id = x.m_jabatan_id)
		END AS namaposisi from t_karyawan where t_karyawan_id=$kode");
            DB::connection()->table("notifikasi")
                    ->insert([
                        "id_assign"=>$id,
                        "database_from"=>"t_karyawan",
                        "datebase_id"=>$kode,
                        "p_karyawan_id"=>$notifdata[0]->p_karyawan_id,
                        "date_action"=>date('Y-m-d H:i:s'),
                        "notif"=>"Pengajuan Karyawan Baru ".$notifdata[0]->namaposisi." $status",
             ]);
            // die;
			return redirect()->route('fe.approval_karyawan_baru')->with('success','Approval Berhasil di update!');
	}
	public function dec_karyawan_baru2($id)
	{
	DB::connection()->table("t_karyawan")
			->where("t_karyawan_id",$id)
			->update([
				"status"=>44,
				"appr_direksi_date"=>date('Y-m-d'),
				"appr_direksi_status"=>2,
			]);
			
		$tkaryawanstatus=44;
			$kode = $id;
								if($tkaryawanstatus==1)
				                        $status= 'Selesai ';
                               	else if($tkaryawanstatus==0)
                               			$status= 'Menunggu Approval Atasan'	;
                               	else if($tkaryawanstatus==5)
                               			$status= 'Sudah disetujui atasan, dan Menunggu Approval Keuangan'	;
                               	else if($tkaryawanstatus==6)
                               			$status= 'Sudah disetujui keuangan, dan Menunggu Approval Direksi'	;
								else if($tkaryawanstatus==2)
								   		$status= 'Menunggu Proses HC';	
                               	else if($tkaryawanstatus==3)
                               			$status= 'Proses Interview';	
                               	else if($tkaryawanstatus==41)
                               			$status= 'Ditolak Atasan';	
                               	else if($tkaryawanstatus==42)
                               			$status= 'Ditolak Keuangan';
                               	else if($tkaryawanstatus==44)
                               			$status= 'Ditolak Direksi';
                               	else if($tkaryawanstatus==4)
                               			$status= 'Ditolak HC';
								else
								   		$status= 'Pending'	; 
			$iduser=Auth::user()->id;
			$sqlidkar="select * from p_karyawan where user_id=$iduser";
			$idkar=DB::connection()->select($sqlidkar);
				
			$id=$idkar[0]->p_karyawan_id;
			$notifdata=DB::connection()->select("select *,CASE t_karyawan.m_jabatan_id 
			WHEN -1 THEN t_karyawan.posisi
			ELSE (select nama from m_jabatan x where t_karyawan.m_jabatan_id = x.m_jabatan_id)
		END AS namaposisi from t_karyawan where t_karyawan_id=$kode");
            DB::connection()->table("notifikasi")
                    ->insert([
                        "id_assign"=>$id,
                        "database_from"=>"t_karyawan",
                        "datebase_id"=>$kode,
                        "p_karyawan_id"=>$notifdata[0]->p_karyawan_id,
                        "date_action"=>date('Y-m-d H:i:s'), 
                        "notif"=>"Pengajuan Karyawan Baru ".$notifdata[0]->namaposisi." $status",
             ]);
			return redirect()->route('fe.approval_karyawan_baru')->with('success','Approval Berhasil di update!');
	}
}
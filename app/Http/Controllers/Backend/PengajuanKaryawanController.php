<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Auth;
use Response;
use Maatwebsite\Excel\Facades\Excel;
use App\Eloquent\AbsenLog;
use App\Eloquent\Absen;
use App\Helper_function;
use PDF;

class PengajuanKaryawanController extends Controller
{
	public function __construct()
    {
        $this->middleware('auth');
    }public function tambah_karyawan_baru()
    {
    	$iduser=Auth::user()->id;
        $sqlidkar="select * from p_karyawan  
        	join p_karyawan_pekerjaan on p_karyawan_pekerjaan.p_karyawan_id = p_karyawan.p_karyawan_id
        where user_id=$iduser";
        $idkar=DB::connection()->select($sqlidkar);
       // echo 'id_jabatan='.$idkar[0]->m_jabatan_id;
        //echo '<br>';
		$bawahan = substr($this->hirarki($idkar[0]->m_jabatan_id,''),0,-1);
		
		$dept 			= 'Select * from m_departement where active=1';
        $departemen  	=	DB::connection()->select($dept);
        
		$dept 			= 'Select * from m_lokasi where active=1';
        $lokasi 	=	DB::connection()->select($dept);
        
       
		
		$sql = "select * from m_jabatan where active = 1 and m_jabatan_id in($bawahan)";
    	$jabatan=DB::connection()->select($sql);
    	return view('backend.pengajuan_karyawan.tambah_karyawan_baru', compact('jabatan','lokasi','departemen'));
    } 
    public function selesai_karyawan_baru ($id){
        DB::beginTransaction();
        try{
           	 $idUser=Auth::user()->id;
            DB::connection()->table("t_karyawan")
            ->where ('t_karyawan_id',$id)
                ->update([
                    "status"	=>	(1),
                    
                    
                    "update_by"		=>	$idUser,
                    "update_date" 	=> 	date("Y-m-d H:i:s")
                ]);
            DB::commit();
            return redirect()->route('be.karyawan_baru')->with('success','Pengajuan Karyawan Berhasil di input!');
        }
        catch(\Exeception $e){
            DB::rollback();
            return redirect()->back()->with('error',$e);
        }

    } 
    public function update_karyawan_baru (Request $request,$id){
        DB::beginTransaction();
        try{
        	
           	 $idUser=Auth::user()->id;
            DB::connection()->table("t_karyawan")
            ->where ('t_karyawan_id',$id)
                ->update([
                    "status"	=>	$request->get('status'),
                    
                    
                    "update_by"		=>	$idUser,
                    "update_date" 	=> 	date("Y-m-d H:i:s")
                ]);
                
                $tkaryawanstatus=$request->get('status');
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
				
			$notifdata=DB::connection()->select("select *,CASE t_karyawan.m_jabatan_id 
			WHEN -1 THEN t_karyawan.posisi
			ELSE (select nama from m_jabatan x where t_karyawan.m_jabatan_id = x.m_jabatan_id)
		END AS namaposisi from t_karyawan where t_karyawan_id=$kode");
            DB::connection()->table("notifikasi")
                    ->insert([
                        "id_assign"=>$iduser,
                        "database_from"=>"t_karyawan",
                        "datebase_id"=>$kode,
                        "p_karyawan_id"=>$notifdata[0]->p_karyawan_id,
                        "date_action"=>date('Y-m-d H:i:s'), 
                        "notif"=>"Pengajuan Karyawan Baru ".$notifdata[0]->namaposisi." $status",
             ]);
            DB::commit();
            return redirect()->route('be.karyawan_baru')->with('success','Pengajuan Karyawan Berhasil di input!');
        }
        catch(\Exeception $e){
            DB::rollback();
            return redirect()->back()->with('error',$e);
        }

    }
	public function print_karyawan_baru ($id){
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
			ELSE (select nama from m_jabatan a where t_karyawan.m_jabatan_id = a.m_jabatan_id)
		END AS namaposisi,
		(select nama from p_karyawan where t_karyawan.p_karyawan_id  = p_karyawan.p_karyawan_id  ) as nmpemohon,
		(select nama from p_karyawan where t_karyawan.pergantian_karyawan_id  = p_karyawan.p_karyawan_id  )as  karyawan_pengganti,
		(select nama from m_lokasi where t_karyawan.m_lokasi_id  = m_lokasi.m_lokasi_id  )as  nmentitas
		
		
		from t_karyawan 
		where t_karyawan.active = 1 and t_karyawan_id = $id";
    	$tkaryawan=DB::connection()->select($sql);
    	
		//print_r($tkaryawan);die;
		$iduser=Auth::user()->id;
        $sqlidkar="select * from p_karyawan 
        	join p_karyawan_pekerjaan on p_karyawan_pekerjaan.p_karyawan_id = p_karyawan.p_karyawan_id
        where user_id=$iduser";
        $idkar=DB::connection()->select($sqlidkar);
       // echo 'id_jabatan='.$idkar[0]->m_jabatan_id;
        //echo '<br>';
		
		$dept 			= 'Select * from m_departemen where active=1';
        $departemen  	=	DB::connection()->select($dept);
        
		$dept 		= 'Select * from m_lokasi where active=1';
        $lokasi 	=	DB::connection()->select($dept);
        
		$sql 		= "select * from m_jabatan where active = 1 ";
    	$jabatan=DB::connection()->select($sql);
    	
    	$dept 		= 'Select * from m_pangkat where active=1';
        $pangkat 	=	DB::connection()->select($dept);
        
        $dept 		= 'Select * from p_karyawan where active=1 order by nama';
        $karyawan 	=	DB::connection()->select($dept); 
        
        $dept 		= "Select * from p_karyawan_pekerjaan  where active=1 and p_karyawan_id = $id ";
        $pekerjaan 	=	DB::connection()->select($dept);
        $help = new Helper_function();
		$data = ['tkaryawan' => $tkaryawan,'help'=>$help
          ];
       $pdf = PDF::loadView('backend.pengajuan_karyawan.pdf_pengajuan_karyawan', $data);
		return $pdf->download('Pengajuan Karyawan Baru - '.$tkaryawan[0]->namaposisi.'.pdf');
       
	}
	public function hapus_karyawan_baru	 ($id){
        DB::beginTransaction();
        try{
           	 $idUser=Auth::user()->id;
            DB::connection()->table("t_karyawan")
            ->where ('t_karyawan_id',$id)
                ->update([
                    "active"	=>	(0),
                    
                    
                    "update_by"		=>	$idUser,
                    "update_date" 	=> 	date("Y-m-d H:i:s")
                ]);
            DB::commit();
            return redirect()->route('be.karyawan_baru')->with('success','Pengajuan Karyawan Berhasil di input!');
        }
        catch(\Exeception $e){
            DB::rollback();
            return redirect()->back()->with('error',$e);
        }

    }
    public function simpan_karyawan_baru (Request $request){
        DB::beginTransaction();
        try{
            $idUser=Auth::user()->id;
            $sqlidkar="select * from p_karyawan 
        	join p_karyawan_pekerjaan on p_karyawan_pekerjaan.p_karyawan_id = p_karyawan.p_karyawan_id
	        where user_id=$idUser";
	        $idkar=DB::connection()->select($sqlidkar);
            DB::connection()->table("t_karyawan")
                ->insert([
                    "m_jabatan_id"	=>	($request->get("jabatan")),
                    "posisi"		=>	($request->get("posisi")),
                    "p_karyawan_id"	=>	($idUser),
                    "deskripsi"		=>	($request->get("keterangan")),
                    "tingkat"		=>	($request->get("tingkat")),
                    
                    "create_by"		=>	$idUser,
                    "create_date" 	=> 	date("Y-m-d H:i:s")
                ]);
            DB::commit();
            return redirect()->route('fe.karyawan_baru')->with('success','Pengajuan Karyawan Berhasil di input!');
        }
        catch(\Exeception $e){
            DB::rollback();
            return redirect()->back()->with('error',$e);
        }

	}
	public function simpan_kandidat_2 (Request $request, $id)
	{
		$periode=DB::connection()->select("Select max(t_karyawan_kandidat_id) as max from t_karyawan_kandidat");
		$id_kandidat = $periode[0]->max+1;
		$help = new Helper_function();
		DB::connection()->table("t_karyawan_kandidat")
		->insert([

			"t_karyawan_kandidat_id"	=>	($id_kandidat),
			"t_karyawan_id"	=>	($id),
			"nama"	=>	($request->get("nama")),
			"wa"	=>	($request->get("wa")),
			"email"	=>	($request->get("email")),
			
		]);
		//"kode"	=>	($help->random(6)),
		if ($request->file('file')) {
			//echo 'masuk';die;
			$file = $request->file('file');
			$destination="dist/img/file/";
			$path='kandidat-'.date('ymdhis').'-'.$file->getClientOriginalName();
			$file->move($destination,$path);

			//echo $path;die;
			DB::connection()->table("t_karyawan_kandidat")->where("t_karyawan_kandidat_id",$id_kandidat)
			->update([
				"file"=>$path
			]);
		}
		return redirect()->route('be.list_database_kandidat',$id)->with('success','Kandidat Berhasil di input!');
	}
	public function simpan_kandidat (Request $request,$id)
	{
        DB::beginTransaction();
        try{
			$help = new Helper_function();

			
            DB::connection()->table("t_karyawan_kandidat")
                ->insert([
					
                    "nama"	=>	($request->get("nama")),
                    
                    
				]);die;
				if ($request->file('file')) {
					//echo 'masuk';die;
					$file = $request->file('file');
					$destination="dist/img/file/";
					$path='kandidat-'.date('ymdhis').'-'.$file->getClientOriginalName();
					$file->move($destination,$path);

					//echo $path;die;
					DB::connection()->table("t_karyawan_kandidat")->where("t_karyawan_kandidat_id",$id_kandidat)
					->update([
						"file"=>$path
					]);
				}
            DB::commit();
            return redirect()->route('be.list_database_kandidat',$id)->with('success','Kandidat Berhasil di input!');
        }
        catch(\Exeception $e){
            DB::rollback();
            return redirect()->back()->with('error',$e);
        }

    }
	public function delete_kandidat  (Request $request, $id, $id_kandidat)
	{
		DB::connection()->table("t_karyawan_kandidat")
		->where('t_karyawan_kandidat_id',$id_kandidat)
		->update([

			"active"	=>	(0),
 
		]);
		return redirect()->route('be.list_database_kandidat',$id)->with('success','Kandidat Berhasil di update!');
    }
	public function kandidat_to_interview_acc  (Request $request, $id, $id_kandidat)
	{
		DB::connection()->table("t_karyawan_kandidat")
		->where('t_karyawan_kandidat_id',$id_kandidat)
		->update([

			"status"	=>	(3),

		]);
		return redirect()->route('be.list_database_kandidat',$id)->with('success','Kandidat Berhasil di update!');
    }
	public function kandidat_to_interview_dec  (Request $request, $id, $id_kandidat)
	{
		DB::connection()->table("t_karyawan_kandidat")
		->where('t_karyawan_kandidat_id',$id_kandidat)
		->update([

			"status"	=>	(11),

		]);
		return redirect()->route('be.list_database_kandidat',$id)->with('success','Kandidat Berhasil di update!');
	}
	public function approve_keuangan_karyawan_baru  (Request $request, $id)
	{
		$iduser=Auth::user()->id;
        if($request->approve_keuangan==1){
        $tkaryawanstatus=6;
            
        }else if($request->approve_keuangan==2){
        $tkaryawanstatus=42;
            
        }else if($request->approve_keuangan==3){
        $tkaryawanstatus=6;
            
        }
		DB::connection()->table("t_karyawan")
		->where('t_karyawan_id',$id)
		->update([

			"final_approval"	=>	$request->karyawan_approval,
			"karyawan_approve_keuangan"	=>	$request->karyawan_approval,
			"keterangan_keuangan"	=>	$request->keterangan_keuangan,
			"status"	=>	($tkaryawanstatus),
			"appr_keuangan"	=>	$iduser,
			"appr_keuangan_date"	=>	date('Y-m-d'),
			"appr_keuangan_status"	=>	($request->approve_keuangan),

		]);
		 
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
				
			//$id=$idkar[0]->p_karyawan_id;
			$notifdata=DB::connection()->select("select *,CASE t_karyawan.m_jabatan_id 
			WHEN -1 THEN t_karyawan.posisi
			ELSE (select nama from m_jabatan x where t_karyawan.m_jabatan_id = x.m_jabatan_id)
		END AS namaposisi from t_karyawan where t_karyawan_id=$kode");
            DB::connection()->table("notifikasi")
                    ->insert([
                        "id_assign"=>$iduser,
                        "database_from"=>"t_karyawan",
                        "datebase_id"=>$kode,
                        "p_karyawan_id"=>$notifdata[0]->p_karyawan_id,
                        "date_action"=>date('Y-m-d H:i:s'), 
                        "notif"=>"Pengajuan Karyawan Baru ".$notifdata[0]->namaposisi." $status",
             ]);
		return redirect()->route('be.keuangan_karyawan_baru')->with('success','Approval Berhasil di update!');
    }
	public function acc_keuangan_karyawan_baru  (Request $request, $id)
	{
		$iduser=Auth::user()->id;
        $tkaryawan = DB::connection()->select('select * from t_karyawan where t_karyawan_id = '.$id);
		DB::connection()->table("t_karyawan")
		->where('t_karyawan_id',$id)
		->update([
            "final_approval"	=>	($tkaryawan[0]->final_approval?$tkaryawan[0]->final_approval:$tkaryawan[0]->jumlah_dibutuhkan),
			"karyawan_approve_keuangan"	=>($tkaryawan[0]->final_approval?$tkaryawan[0]->final_approval:$tkaryawan[0]->jumlah_dibutuhkan),
			"status"	=>	(6),
			"appr_keuangan"	=>	$iduser,
			"appr_keuangan_date"	=>	date('Y-m-d'),
			"appr_keuangan_status"	=>	(1),

		]);
		$tkaryawanstatus=6;
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
				
		//	$id=$idkar[0]->p_karyawan_id;
			$notifdata=DB::connection()->select("select *,CASE t_karyawan.m_jabatan_id 
			WHEN -1 THEN t_karyawan.posisi
			ELSE (select nama from m_jabatan x where t_karyawan.m_jabatan_id = x.m_jabatan_id)
		END AS namaposisi from t_karyawan where t_karyawan_id=$kode");
            DB::connection()->table("notifikasi")
                    ->insert([
                        "id_assign"=>$iduser,
                        "database_from"=>"t_karyawan",
                        "datebase_id"=>$kode,
                        "p_karyawan_id"=>$notifdata[0]->p_karyawan_id,
                        "date_action"=>date('Y-m-d H:i:s'), 
                        "notif"=>"Pengajuan Karyawan Baru ".$notifdata[0]->namaposisi." $status",
             ]);
		return redirect()->route('be.keuangan_karyawan_baru')->with('success','Approval Berhasil di update!');
    }
	public function dec_keuangan_karyawan_baru  (Request $request, $id)
	{
		$iduser=Auth::user()->id;
		DB::connection()->table("t_karyawan")
		->where('t_karyawan_id',$id)
		->update([

			"status"	=>	(42),
			"appr_keuangan"	=>	$iduser,
			"appr_keuangan_date"	=>	date('Y-m-d'),
			"appr_keuangan_status"	=>	(2),


		]);
		$tkaryawanstatus=42;
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
				
		//	$id=$idkar[0]->p_karyawan_id;
			$notifdata=DB::connection()->select("select *,CASE t_karyawan.m_jabatan_id 
			WHEN -1 THEN t_karyawan.posisi
			ELSE (select nama from m_jabatan x where t_karyawan.m_jabatan_id = x.m_jabatan_id)
		END AS namaposisi from t_karyawan where t_karyawan_id=$kode");
            DB::connection()->table("notifikasi")
                    ->insert([
                        "id_assign"=>$iduser,
                        "database_from"=>"t_karyawan",
                        "datebase_id"=>$kode,
                        "p_karyawan_id"=>$notifdata[0]->p_karyawan_id,
                        "date_action"=>date('Y-m-d H:i:s'), 
                        "notif"=>"Pengajuan Karyawan Baru ".$notifdata[0]->namaposisi." $status",
             ]);
		return redirect()->route('be.keuangan_karyawan_baru')->with('success','Approval Berhasil di update!');
	}public function kandidat_penerimaan_acc  (Request $request, $id, $id_kandidat)
	{
		DB::connection()->table("t_karyawan_kandidat")
		->where('t_karyawan_kandidat_id',$id_kandidat)
		->update([

		"status"	=>	(6),

		]);
		return redirect()->route('be.list_database_kandidat',$id)->with('success','Kandidat Berhasil di update!');
    }
	public function kandidat_penerimaan_dec  (Request $request, $id, $id_kandidat)
	{
		DB::connection()->table("t_karyawan_kandidat")
		->where('t_karyawan_kandidat_id',$id_kandidat)
		->update([

		"status"	=>	(14),

		]);
		return redirect()->route('be.list_database_kandidat',$id)->with('success','Kandidat Berhasil di update!');
    }
	
   public function edit_karyawan_baru($id)
    {
    	
		$sql = "select *
		
		
		from t_karyawan 
		where t_karyawan.active = 1 and t_karyawan_id = $id";
		
    	$tkaryawan=DB::connection()->select($sql);
		//print_r($tkaryawan);die;
		$iduser=Auth::user()->id;
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
		$view = 'edit';
		return view('backend.pengajuan_karyawan.edit_karyawan_baru', compact('tkaryawan','view','jabatan','karyawan','pangkat','lokasi','departemen','pekerjaan'));
    } 
	
	public function approval_karyawan_baru_keuangan($id)
    {
    	return PengajuanKaryawanController::view_karyawan_baru($id,"approve_keuangan");
    } 
	public function view_karyawan_baru($id,$view="")
    {
    	
		$sql = "select *,CASE t_karyawan.m_kantor_id 
		WHEN -1 THEN lokasi_penempatan
		ELSE (select c.nama from m_office c
		where t_karyawan.m_kantor_id  = c.m_office_id)
		END AS lokasi_penempatan

		
		from t_karyawan 
		where t_karyawan.active = 1 and t_karyawan_id = $id";
		
    	$tkaryawan=DB::connection()->select($sql);
		//print_r($tkaryawan);die;
		$iduser=Auth::user()->id;
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
		
		return view('backend.pengajuan_karyawan.edit_karyawan_baru', compact('tkaryawan','view','jabatan','karyawan','pangkat','lokasi','departemen','pekerjaan'));
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
                
            }else if($view =='edit' ){
                DB::connection()->table("t_karyawan")
                    ->where ('t_karyawan_id',$id)
                        ->update([
                            "status"	=>	$request->get('status'),
                            "update_by"		=>	$idUser,
                            "update_date" 	=> 	date("Y-m-d H:i:s")
                        ]);
                        	$tkaryawanstatus=	$request->get('status');	
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
			
			$iduser = Auth::user()->id;
			$id=$iduser;
			$notifdata=DB::connection()->select("select *,CASE t_karyawan.m_jabatan_id 
			WHEN -1 THEN t_karyawan.posisi
			ELSE (select nama from m_jabatan x where t_karyawan.m_jabatan_id = x.m_jabatan_id)
		END AS namaposisi from t_karyawan where t_karyawan_id=$kode");
            DB::connection()->table("notifikasi")
                    ->insert([
                        "id_assign"=>$id,
                        "assign_from"=>"user",
                        "database_from"=>"t_karyawan",
                        "datebase_id"=>$kode,
                        "p_karyawan_id"=>$notifdata[0]->p_karyawan_id,
                        "date_action"=>date('Y-m-d H:i:s'),
                        "notif"=>"Pengajuan Karyawan Baru ".$notifdata[0]->namaposisi." $status",
             ]);
            DB::commit();
            return redirect()->route('be.keuangan_karyawan_baru')->with('success','Approval Berhasil di simpan!');
        }
        catch(\Exeception $e){
            DB::rollback();
            return redirect()->back()->with('error',$e);
        }
    }
	public function keuangan_karyawan_baru()
    {
    	 $iduser = Auth::user()->id;
        $sqluser = "SELECT p_recruitment.foto,role,p_karyawan_pekerjaan,p_karyawan_pekerjaan.m_lokasi_id,user_entitas_access,m_role_id FROM users
					left join m_role on m_role.m_role_id=users.role
					left join p_karyawan on p_karyawan.user_id=users.id
					left join p_karyawan_pekerjaan on p_karyawan_pekerjaan.p_karyawan_id=p_karyawan.p_karyawan_id
					left join p_recruitment on p_recruitment.p_recruitment_id=p_karyawan.p_recruitment_id
					where users.id=$iduser";
        $user = DB::connection()->select($sqluser);
        if ($user[0]->user_entitas_access) {
            $id_lokasi = $user[0]->user_entitas_access;
            
            $whereLokasirole = "AND CASE t_karyawan.m_jabatan_id
			WHEN -1 THEN (t_karyawan.m_lokasi_id = $id_lokasi)
			ELSE (select m_lokasi_id from m_jabatan b where t_karyawan.m_jabatan_id = b.m_jabatan_id ) = $id_lokasi
		END";
        } else {
            $whereLokasirole = "";
        }
        $id_lokasi = Auth::user()->user_entitas;
        if($id_lokasi and $id_lokasi!=-1) 
			$whereLokasi = "AND t_karyawan.m_lokasi_id in($id_lokasi)";					
		else
			$whereLokasi = "";	
       
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
		END AS namaposisi
		
		
		from t_karyawan 
		where t_karyawan.active = 1
		and t_karyawan.status in(5,6,2,3,42,44,4)
		$whereLokasi
	    order by appr_keuangan_status desc
		";
		
    	$tkaryawan=DB::connection()->select($sql);
		return view('backend.pengajuan_karyawan.approval_keuangan', compact('tkaryawan'));
    }
	public function database_kandidat (Request $request)
	{
		$where = '';
		if($request->get('entitas'))
		$where .= " AND CASE t_karyawan.m_jabatan_id
			WHEN -1 THEN (t_karyawan.m_lokasi_id = ".$request->get('entitas').")
			ELSE (select m_lokasi_id from m_jabatan b where t_karyawan.m_jabatan_id = b.m_jabatan_id ) = ".$request->get('entitas')."
		END
		";
		if($request->get('departemen'))
		$where .= " AND 
			CASE t_karyawan.m_jabatan_id
			WHEN -1 THEN ( t_karyawan.m_departemen_id = ".$request->get('departemen').")
			ELSE (select b.m_departemen_id from m_departemen b join p_karyawan_pekerjaan on t_karyawan.p_karyawan_id =  p_karyawan_pekerjaan.p_karyawan_id where p_karyawan_pekerjaan.m_departemen_id= b.m_departemen_id) = ".$request->get('departemen')."
		END
		";
		if($request->get('tgl_awal'))
		$where .= " and appr_keuangan_date >= '".$request->get('tgl_awal')."'";
		if($request->get('tgl_akhir'))
		$where .= " and appr_keuangan_date <= '".$request->get('tgl_akhir')."'";
		
		
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
			ELSE (select nama from m_jabatan a where t_karyawan.m_jabatan_id = a.m_jabatan_id)
		END AS namaposisi


		from t_karyawan
		where t_karyawan.active = 1 and status in(2,1) 
		$where
		
		order by t_karyawan.create_by desc
		";
		$tkaryawan=DB::connection()->select($sql);
		$help = new Helper_function();
		$entitas = DB::connection()->select("select * from m_lokasi where active=1 and sub_entitas=0");
		 $sqldepartemen="SELECT * FROM m_departemen WHERE active=1 ORDER BY nama ASC ";
        $departemen=DB::connection()->select($sqldepartemen);
        
		return view('backend.pengajuan_karyawan.cv_kandidat', compact('tkaryawan','help','entitas','request','departemen'));

		
	}
	public function list_database_kandidat($id)
    {
		$sql=" select * from t_karyawan_kandidat
		where t_karyawan_kandidat.active = 1 and t_karyawan_id =$id";
		$cv=DB::connection()->select($sql);
		return view('backend.pengajuan_karyawan.list_database_kandidat',compact('cv','id'));
    	
    }
	public function tambah_kandidat($id)
    {
		$type = 'simpan_kandidat'; 
		return view('backend.pengajuan_karyawan.tambah_kandidat',compact('type','id'));
	}public function update_kandidat_interview (Request $request,$id, $id_kandidat)
    {
		DB::connection()->table("t_karyawan_kandidat")
		->where('t_karyawan_kandidat_id',$id_kandidat)
		->update([

			"status"	=>	(4),
			"rekomendasi_hr"	=>	$request->get('rekomendasi_hr'),
			"keterangan_hr"	=>	$request->get('keterangan_hr'),
			

		]);
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
		if ($request->file('file_interview2')) {
			//echo 'masuk';die;
			$file = $request->file('file_interview2');
			$destination="dist/img/file/";
			$path='file_interview2-'.date('ymdhis').'-'.$file->getClientOriginalName();
			$file->move($destination,$path);

			//echo $path;die;
			DB::connection()->table("t_karyawan_kandidat")->where("t_karyawan_kandidat_id",$id_kandidat)
			->update([
			"file_interview2"=>$path
			]);
		}
		if ($request->file('file_psikogram')) {
			//echo 'masuk';die;
			$file = $request->file('file_psikogram');
			$destination="dist/img/file/";
			$path='file_psikogram-'.date('ymdhis').'-'.$file->getClientOriginalName();
			$file->move($destination,$path);

			//echo $path;die;
			DB::connection()->table("t_karyawan_kandidat")->where("t_karyawan_kandidat_id",$id_kandidat)
			->update([
			"file_psikogram"=>$path
			]);
		}
		return redirect()->route('be.list_database_kandidat',$id)->with('success','Kandidat Berhasil di update!');

    	
	}public function update_kandidat_offering_letter (Request $request, $id, $id_kandidat)
    {
		DB::connection()->table("t_karyawan_kandidat")
		->where('t_karyawan_kandidat_id',$id_kandidat)
		->update([

		"status"	=>	$request->get('status_kelanjutan'),
		
		]);
		if ($request->file('file_offfering_letter')) {
			//echo 'masuk';die;
			$file = $request->file('file_offfering_letter');
			$destination="dist/img/file/";
			$path='file_offfering_letter-'.date('ymdhis').'-'.$file->getClientOriginalName();
			$file->move($destination,$path);

			//echo $path;die;
			DB::connection()->table("t_karyawan_kandidat")->where("t_karyawan_kandidat_id",$id_kandidat)
			->update([
			"file_offfering_letter"=>$path
			]);
		}
		
		return redirect()->route('be.list_database_kandidat',$id)->with('success','Kandidat Berhasil di update!');

    	
	}public function update_kandidat_kontrak_kerja (Request $request, $id, $id_kandidat)
    {
		$idUser = Auth::user()->id;
		
		DB::connection()->table("t_karyawan_kandidat")
		->where('t_karyawan_kandidat_id',$id_kandidat)
		->update([

		"status"	=>	$request->get('status_kelanjutan'),
		
		]);
		if ($request->file('file_kontrak_kerja')) {
			//echo 'masuk';die;
			$file = $request->file('file_kontrak_kerja');
			$destination="dist/img/file/";
			$path='file_kontrak_kerja-'.date('ymdhis').'-'.$file->getClientOriginalName();
			$file->move($destination,$path);

			//echo $path;die;
			DB::connection()->table("t_karyawan_kandidat")->where("t_karyawan_kandidat_id",$id_kandidat)
			->update([
			"file_kontrak_kerja"=>$path
			]);
		}
		
		$sqlidkar="SELECT max(p_karyawan_id)+1 as p_karyawan_id FROM p_karyawan";
		$datakar=DB::connection()->select($sqlidkar);
		$idkar=$datakar[0]->p_karyawan_id;

		//$blnkontrak=date('m',strtotime($request->get("tgl_awal")));
		//$thnkontrak=date('Y',strtotime($request->get("tgl_awal")));
		$lokasi=$request->get("lokasi");
		$blnkontrak=date('m',strtotime($request->get("tgl_awal")));
		$thnkontrak=date('y',strtotime($request->get("tgl_awal")));
		$thnkontrak2=date('Y',strtotime($request->get("tgl_awal")));
		//echo $thnkontrak;die;
		//$kodenik=$kar->kode_nik;
		$sqlokasi1="SELECT * FROM m_lokasi WHERE m_lokasi_id=$lokasi";
		//echo $sql;
		$datalokasi1=DB::connection()->select($sqlokasi1);
		$kodenik1=$datalokasi1[0]->kode_nik;
		$sql="SELECT count(*)+1 as count FROM p_karyawan
WHERE to_char(tgl_bergabung,'yy-mm')='".$thnkontrak.'-'.$blnkontrak."' and nik ilike '".$kodenik1.$thnkontrak.$blnkontrak."%' ";
		//echo $sql;die;
		$datasql=DB::connection()->select($sql);

		$jumlah=$datasql[0]->count;
		if (strlen($jumlah)==1) {
			$nik='0'.$jumlah;
		} else {
			$nik=$jumlah;
		}

		$sqlokasi="SELECT * FROM m_lokasi WHERE m_lokasi_id=$lokasi";
		//echo $sql;
		$datalokasi=DB::connection()->select($sqlokasi);

		$kodenik=$datalokasi[0]->kode_nik;
		$nik=$kodenik.$thnkontrak.$blnkontrak.$nik;
		
		
		$sql="SELECT * FROM p_recruitment WHERE t_karyawan_kandidat_id=$id_kandidat ORDER BY p_recruitment_id desc";
		//echo $sql;
		$rec=DB::connection()->select($sql);
		$idRec = $rec[0]->p_recruitment_id;
		DB::connection()->table("p_karyawan")
		->insert([
			"p_karyawan_id" => $idkar,
			"p_recruitment_id" => $rec[0]->p_recruitment_id,
			"nik" => $nik,
			"nama" => $rec[0]->nama_lengkap,
			"email_perusahaan" => $rec[0]->email,
			"tgl_bergabung" => date('Y-m-d',strtotime($request->get("tgl_awal"))),
			"ukuran_baju"=>$request->get("ukuran_baju"),
			"pendidikan"=>$rec[0]->pendidikan,
			"jurusan"=>$rec[0]->jurusan,
			"nama_sekolah"=>$rec[0]->nama_sekolah,
			"no_hp2"=>$rec[0]->no_hp,
			"domisili"=>$rec[0]->alamat_tinggal,
			"jumlah_anak"=>$rec[0]->jumlah_anak, 
			"nilai"=>$rec[0]->nilai, 
			"active" => $request->get("status"),
			"create_date" => date("Y-m-d"),
			"create_by" => $idUser,
			"active" => 1,
		]);

		DB::connection()->table("p_karyawan_pekerjaan")
		->insert([
			"p_karyawan_id" => $idkar,
			"m_lokasi_id" => $request->get("lokasi"),
			"m_departemen_id" => $request->get("departemen"),
			"m_jabatan_id" => $request->get("jabatan"),
			"m_divisi_id" => $request->get("divisi"),

			"m_kantor_id" => $request->get("kantor"),
			"active" => 1,
			"create_date" => date("Y-m-d"),
			"create_by" => $idUser,
		]);
		DB::connection()->table("p_karyawan_kontrak")
		->insert([
			"p_karyawan_id" => $idkar,
			"tgl_awal" => date('Y-m-d',strtotime($request->get("tgl_awal"))),
			"tgl_akhir" => date('Y-m-d',strtotime($request->get("tgl_akhir"))),
			"m_status_pekerjaan_id" => $request->get("status_pekerjaan"),
			"active" => 1,
			"create_date" => date("Y-m-d"),
			"create_by" => $idUser,
		]);

		DB::connection()->table("p_karyawan_absen")
		->insert([
			"p_karyawan_id" => $idkar,
			"no_absen" => $request->get("no_absen"),
			"active" => 1,
			"create_date" => date("Y-m-d"),
			"create_by" => $idUser,
		]);
		
		$id=$idkar;
		
		$sql="SELECT * FROM p_recruitment_pekerjaan WHERE  p_recruitment_id=$idRec";
		//echo $sql;
		$migrasi=DB::connection()->select($sql);
		foreach ($migrasi as $migrasi) {
			
			DB::connection()->table("p_karyawan_riwayat_pekerjaan")
			// ->where("p_karyawan_riwayat_pekerjaan_id",$id)
			->insert([
				//"nik" => $request->get("nik"),
				//"nama" => $request->get("nama_lengkap"),

				"nama_perusahaan" => ucwords($migrasi->nama_perusahaan),
				"awal_periode" => $migrasi->awal_periode,
				"akhir_periode" => $migrasi->akhir_periode,
				"posisi_kerja" => $migrasi->posisi_kerja,
				"p_karyawan_id" => $id,
				"deskripsi_kerja" => $migrasi->deskripsi_kerja,
				"ruang_lingkup_kerja" => $migrasi->ruang_lingkup_kerja,
				"prestasi_kerja" => $migrasi->prestasi_kerja,
				"gaji" 			=> $migrasi->gaji,
				"lokasi" 		=> $migrasi->lokasi,
				"nomor_ref" => $migrasi->nomor_ref,
				"nama_ref" => $migrasi->nama_ref,
				"jabatan_ref" => $migrasi->jabatan_ref,
				"alasan_resign" => $migrasi->alasan_resign,
				"create_date" => date("Y-m-d"),
				"create_by" => $idUser,
			]);

		}
		
		
		$sql="SELECT * FROM p_recruitment_pendidikan WHERE p_recruitment_id=$idRec";
		$migrasi=DB::connection()->select($sql);
		foreach ($migrasi as $migrasi) {
			DB::connection()->table("p_karyawan_pendidikan")

			->insert([

				"p_karyawan_id" => $id,
				"nama_sekolah" => $migrasi->nama_sekolah,
				"jenjang" => $migrasi->jenjang,
				"jurusan" => $migrasi->jurusan,
				"alamat_sekolah" => $migrasi->alamat_sekolah,
				"kota_sekolah" => $migrasi->kota_sekolah,
				"tahun_lulus" => $migrasi->tahun_lulus,
				"nilai" => $migrasi->nilai,
				"active" => 1,
				"create_date" => date("Y-m-d"),
				"create_by" => $idUser,
			]);
		}
		$sql="SELECT * FROM p_recruitment_kursus WHERE  p_recruitment_id=$idRec";
		$migrasi=DB::connection()->select($sql);
		foreach ($migrasi as $migrasi) {
			DB::connection()->table("p_karyawan_kursus")
			->insert([

				"p_karyawan_id" => $id,
				"nama_kursus" => $migrasi->nama_kursus,
				"penyelenggara" => $migrasi->penyelenggara,
				"tanggal_awal_pelatihan" => $migrasi->tanggal_awal_pelatihan,
				"tanggal_akhir_pelatihan" => $migrasi->tanggal_akhir_pelatihan,
				"sertifikat" => $migrasi->sertifikat,
				
				"active" => 1,
				"create_date" => date("Y-m-d"),
				"create_by" => $idUser,
			]);
		}
		$sql="SELECT * FROM p_recruitment_penyakit WHERE  p_recruitment_id=$idRec";
		$migrasi=DB::connection()->select($sql);
		foreach ($migrasi as $migrasi) {
			DB::connection()->table("p_karyawan_penyakit")
			// ->where("p_karyawan_riwayat_pekerjaan_id",$id)
			->insert([
				//"nik" => $request->get("nik"),
				//"nama" => $request->get("nama_lengkap"),

				"jenis_penyakit" 	=> $migrasi->jenis_penyakit,
				"tahun_penyakit" 	=> $migrasi->tahun_penyakit,
				"sembuh" 			=> $migrasi->sembuh,
				"dampak_saat_ini" 	=> $migrasi->dampak_saat_ini,

				"p_karyawan_id" => $id,

				"create_date" => date("Y-m-d"),
				"active" =>1,
				"create_by" => $idUser,
			]);
		}
		return redirect()->route('be.list_database_kandidat',$id)->with('success','Kandidat Berhasil di update!');

    	
    }public function kandidat_interview ($id,$id_kandidat)
    {
    	$sql="SELECT * FROM t_karyawan_kandidat WHERE t_karyawan_kandidat_id=$id_kandidat ";
		$kandidat=DB::connection()->select($sql);
		$type = 'simpan_kandidat'; 
		return view('backend.pengajuan_karyawan.kandidat_interview',compact('type','id','id_kandidat','kandidat'));
	}public function kandidat_offering_letter  ($id, $id_kandidat)
    {
		$type = 'simpan_kandidat'; 
		return view('backend.pengajuan_karyawan.kandidat_offering_letter ',compact('type','id','id_kandidat'));
	}public function kandidat_kontrak_kerja  ($id, $id_kandidat)
    {
		$type = 'simpan_kandidat'; 
		$sqllokasi="SELECT * FROM m_lokasi WHERE active=1 and sub_entitas=0 ORDER BY nama ASC ";
		$lokasi=DB::connection()->select($sqllokasi);
		$sqldepartemen="SELECT * FROM m_departemen WHERE active=1 ORDER BY nama ASC ";
		$departemen=DB::connection()->select($sqldepartemen);

		$sqljabatan="SELECT m_jabatan.*,m_pangkat.nama as nmpangkat,m_lokasi.nama as nmlokasi,m_lokasi.kode as kdlokasi
                      FROM m_jabatan
                      LEFT JOIN m_pangkat on m_pangkat.m_pangkat_id=m_jabatan.m_pangkat_id
                      LEFT JOIN m_lokasi on m_lokasi.m_lokasi_id=m_jabatan.m_lokasi_id
                      WHERE m_jabatan.active=1 ORDER BY m_jabatan.nama ASC ";
		$jabatan=DB::connection()->select($sqljabatan);

		$sqlgrade="SELECT * FROM m_grade WHERE active=1 ORDER BY nama ASC ";
		$grade=DB::connection()->select($sqlgrade);

		$sqlpangkat="SELECT * FROM m_pangkat WHERE active=1 ORDER BY nama ASC ";
		$pangkat=DB::connection()->select($sqlpangkat);

		$sqldivisi="SELECT * FROM m_divisi WHERE active=1 ORDER BY nama ASC ";
		$divisi=DB::connection()->select($sqldivisi);
		$sqlkantor="SELECT * FROM m_office WHERE active=1 ORDER BY nama ASC ";
		$kantor=DB::connection()->select($sqlkantor);
		$sqlstspekerjaan="SELECT * FROM m_status_pekerjaan WHERE active=1 ORDER BY nama ASC ";
		$stspekerjaan=DB::connection()->select($sqlstspekerjaan);
		return view('backend.pengajuan_karyawan.kandidat_kontrak_kerja ',compact('type','id','id_kandidat','lokasi','jabatan','grade','pangkat','divisi','departemen','kantor','stspekerjaan'));
    }
	public function karyawan_baru()
    {
    	
		
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
			ELSE (select nama from m_jabatan a where t_karyawan.m_jabatan_id = a.m_jabatan_id)
		END AS namaposisi,t_karyawan.create_date
		
		
		from t_karyawan 
		left join p_karyawan on t_karyawan.p_karyawan_id = p_karyawan.p_karyawan_id
		where t_karyawan.active = 1
		order by t_karyawan.t_karyawan_id desc
		";
    	$tkaryawan=DB::connection()->select($sql);
		return view('backend.pengajuan_karyawan.karyawan_baru', compact('tkaryawan'));
	}
}
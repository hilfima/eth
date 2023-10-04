<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use PDF;
use Auth;
use App\Helper_function;

class KPIController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }public function approval_kpi(Request $request)
    {
        $iduser=Auth::user()->id;
			$sqlidkar="select * from p_karyawan where user_id=$iduser";
			$idkar=DB::connection()->select($sqlidkar);
			$id=$idkar[0]->p_karyawan_id;
			
			$sqlkpi="SELECT *,e.nama as jabatan
                       FROM t_kpi a
                       left join p_karyawan b on a.p_karyawan_id = b.p_karyawan_id 
                       left join m_jabatan e on a.m_jabatan_id	 = e.m_jabatan_id
                       
                       where (atasan_1 = $id or atasan_2=$id)  and a.active = 1 order by  a.create_date";
        $kpi=DB::connection()->select($sqlkpi);
		
       

        return view('backend.kpi.approval',compact('kpi','request','id'));
    }public function approval_parameter_kpi(Request $request)
    {
        $iduser=Auth::user()->id;
			$sqlidkar="select * from p_karyawan where user_id=$iduser";
			$idkar=DB::connection()->select($sqlidkar);
			$id=$idkar[0]->p_karyawan_id;
			
			$sqlkpi="SELECT *
                       FROM t_kpi_appr a
                       left join t_kpi_detail e on a.t_kpi_detail_id = e.t_kpi_detail_id
                       left join t_kpi f on e.t_kpi_id = f.t_kpi_id
                       left join p_karyawan b on e.p_karyawan_id = b.p_karyawan_id 
                       
                       where (appr = $id )  ";
        $kpi=DB::connection()->select($sqlkpi);
		
       

        return view('backend.kpi.approval_parameter_kpi',compact('kpi','request','id'));
    }
    public function acc_kpi_parameter (Request $request, $detail,$appr,$ke)
	{

		DB::beginTransaction();
		try {
			$iduser=Auth::user()->id;
			$sqlidkar="select * from p_karyawan where user_id=$iduser";
			$idkar=DB::connection()->select($sqlidkar);
			$id=$idkar[0]->p_karyawan_id;
			DB::connection()->table("t_kpi_detail")
			->where("t_kpi_detail_id",$detail)
			->update([
				"approve_tw".$ke=>1,
				"appr_date_tw".$ke => date("Y-m-d H:i:s"),
				"update_by" => $id,
				"update_date" => date("Y-m-d H:i:s"),
			]);
			DB::connection()->table("t_kpi_appr")
			->where("t_kpi_appr_id",$appr)
			->update([
				"appr_status"=>1,
				"appr_date" => date("Y-m-d H:i:s"),
			]);

			DB::commit();

			return redirect()->route('fe.approval_parameter_kpi')->with('success','Approval  Berhasil di rubah!');
		} catch (\Exeception $e) {
			DB::rollback();
			return redirect()->back()->with('error',$e);
		}
	}
    public function dec_kpi_parameter (Request $request, $detail,$appr,$ke)
	{

		DB::beginTransaction();
		try {
			$iduser=Auth::user()->id;
			$sqlidkar="select * from p_karyawan where user_id=$iduser";
			$idkar=DB::connection()->select($sqlidkar);
			$id=$idkar[0]->p_karyawan_id;
			DB::connection()->table("t_kpi_detail")
			->where("t_kpi_detail_id",$detail)
			->update([
				"approve_tw".$ke=>2,
				"appr_date_tw".$ke => date("Y-m-d H:i:s"),
				"update_by" => $id,
				"update_date" => date("Y-m-d H:i:s"),
			]);
			DB::connection()->table("t_kpi_appr")
			->where("t_kpi_appr_id",$appr)
			->update([
				"appr_status"=>2,
				"appr_date" => date("Y-m-d H:i:s"),
			]);

			DB::commit();

			return redirect()->route('fe.approval_parameter_kpi')->with('success','Approval  Berhasil di rubah!');
		} catch (\Exeception $e) {
			DB::rollback();
			return redirect()->back()->with('error',$e);
		}
	}public function acc_kpi_1(Request $request, $kode)
	{

		DB::beginTransaction();
		try {
			$iduser=Auth::user()->id;
			$sqlidkar="select * from p_karyawan where user_id=$iduser";
			$idkar=DB::connection()->select($sqlidkar);
			$id=$idkar[0]->p_karyawan_id;
			DB::connection()->table("t_kpi")
			->where("t_kpi_id",$kode)
			->update([
				"status_appr_1"=>1,
				"appr_date_1" => date("Y-m-d H:i:s"),
				"update_by" => $id,
				"update_date" => date("Y-m-d H:i:s"),
			]);

			DB::commit();

			return redirect()->route('fe.approval_kpi')->with('success','Approval  Berhasil di rubah!');
		} catch (\Exeception $e) {
			DB::rollback();
			return redirect()->back()->with('error',$e);
		}
	}public function dec_kpi_1(Request $request, $kode)
	{

		DB::beginTransaction();
		try {
			$iduser=Auth::user()->id;
			$sqlidkar="select * from p_karyawan where user_id=$iduser";
			$idkar=DB::connection()->select($sqlidkar);
			$id=$idkar[0]->p_karyawan_id;
			DB::connection()->table("t_kpi")
			->where("t_kpi_id",$kode)
			->update([
				"status_appr_1"=>2,
				"appr_date_1" => date("Y-m-d"),
				"update_by" => $id,
				"update_date" => date("Y-m-d H:i:s"),
			]);

			DB::commit();

			return redirect()->route('fe.approval_kpi')->with('success','Approval  Berhasil di rubah!');
		} catch (\Exeception $e) {
			DB::rollback();
			return redirect()->back()->with('error',$e);
		}
	} public function acc_kpi_2(Request $request, $kode)
	{

		DB::beginTransaction();
		try {
			$iduser=Auth::user()->id;
			$sqlidkar="select * from p_karyawan where user_id=$iduser";
			$idkar=DB::connection()->select($sqlidkar);
			$id=$idkar[0]->p_karyawan_id;
			DB::connection()->table("t_kpi")
			->where("t_kpi_id",$kode)
			->update([
				"status_appr_2"=>1,
				"appr_date_2" => date("Y-m-d H:i:s"),
				"update_by" => $id,
				"update_date" => date("Y-m-d H:i:s"),
			]);

			DB::commit();

			return redirect()->route('fe.approval_kpi')->with('success','Approval  Berhasil di rubah!');
		} catch (\Exeception $e) {
			DB::rollback();
			return redirect()->back()->with('error',$e);
		}
	}public function dec_kpi_2(Request $request, $kode)
	{

		DB::beginTransaction();
		try {
			$iduser=Auth::user()->id;
			$sqlidkar="select * from p_karyawan where user_id=$iduser";
			$idkar=DB::connection()->select($sqlidkar);
			$id=$idkar[0]->p_karyawan_id;
			DB::connection()->table("t_kpi")
			->where("t_kpi_id",$kode)
			->update([
				"status_appr_2"=>2,
				"appr_date_2" => date("Y-m-d"),
				"update_by" => $id,
				"update_date" => date("Y-m-d H:i:s"),
			]);

			DB::commit();

			return redirect()->route('fe.approval_kpi')->with('success','Approval  Berhasil di rubah!');
		} catch (\Exeception $e) {
			DB::rollback();
			return redirect()->back()->with('error',$e);
		}
	}
    
    public function parameter_penilaian_kpi  (Request $request)
    {
    	$sqlkpi="SELECT * from m_parameter_penilaian_kpi  puk
    	left join m_point_utama_kpi jpk on jpk.m_point_utama_kpi_id = puk.m_point_utama_kpi_id
    	left join m_jenis_penilaian_kpi jpk2 on jpk.m_jenis_penilaian_id = jpk2.m_jenis_penilaian_kpi_id
    	";
        $penilaian=DB::connection()->select($sqlkpi);
        return view('backend.kpi.parameter_penilaian_kpi ',compact('penilaian')); 
    }
    
    public function tambah_parameter_penilaian_kpi(Request $request)
    {
    	$sqlkpi="SELECT * from m_point_utama_kpi puk
    	left join m_jenis_penilaian_kpi jpk on jpk.m_jenis_penilaian_kpi_id = puk.m_jenis_penilaian_id
    	";
        $penilaian=DB::connection()->select($sqlkpi);
        return view('backend.kpi.tambah_parameter_penilaian_kpi',compact('penilaian')); 
    }
    public function simpan_parameter_penilaian_kpi (Request $request)
    {
    	
        DB::beginTransaction();
		try {
			$data = $request->data;
			DB::connection()->table("m_parameter_penilaian_kpi")
			
			->insert($data);

			DB::commit();

			return redirect()->route('be.parameter_penilaian_kpi')->with('success','Parameter Penilaian  Berhasil di tambahkan!');
		} catch (\Exeception $e) {
			DB::rollback();
			return redirect()->back()->with('error',$e);
		}
    }
    public function masterpoinkpi (Request $request)
    {
    	$sqlkpi="SELECT * from m_point_utama_kpi puk
    	left join m_jenis_penilaian_kpi jpk on jpk.m_jenis_penilaian_kpi_id = puk.m_jenis_penilaian_id
    	";
        $penilaian=DB::connection()->select($sqlkpi);
        return view('backend.kpi.masterpoinkpi',compact('penilaian')); 
    }
    
    public function tambah_masterpoinkpi(Request $request)
    {
    	$sqlkpi="SELECT * from m_jenis_penilaian_kpi";
        $penilaian=DB::connection()->select($sqlkpi);
        return view('backend.kpi.tambah_masterpoinkpi',compact('penilaian')); 
    } public function simpan_masterpoinkpi(Request $request)
    {
    	
        DB::beginTransaction();
		try {
			$data = $request->data;
			DB::connection()->table("m_point_utama_kpi")
			
			->insert($data);

			DB::commit();

			return redirect()->route('be.masterpoinutamakpi')->with('success','Jenis  Berhasil di tambahkan!');
		} catch (\Exeception $e) {
			DB::rollback();
			return redirect()->back()->with('error',$e);
		}
    }public function jenis_penilaian_kpi(Request $request)
    {
    	$sqlkpi="SELECT * from m_jenis_penilaian_kpi";
        $penilaian=DB::connection()->select($sqlkpi);
        return view('backend.kpi.jenis_penilaian_kpi',compact('penilaian')); 
    }
    
    public function tambah_jenis_penilaian_kpi(Request $request)
    {
    	
        return view('backend.kpi.tambah_jenis_penilaian_kpi'); 
    } public function simpan_jenis_penilaian_kpi(Request $request)
    {
    	
        DB::beginTransaction();
		try {
			DB::connection()->table("m_jenis_penilaian_kpi")
			
			->insert([
				"nama_penilaian"=>$request->nama_penilaian,
				
			]);

			DB::commit();

			return redirect()->route('be.jenis_penilaian_kpi')->with('success','Jenis  Berhasil di tambahkan!');
		} catch (\Exeception $e) {
			DB::rollback();
			return redirect()->back()->with('error',$e);
		}
    }
    
    public function kpi(Request $request)
    {
        
        $sqlkpi="SELECT a.*,b.*,c.nama as appr_1,d.nama as appr_2,e.nama as jabatan
                       FROM t_kpi a
                       left join p_karyawan b on a.p_karyawan_id = b.p_karyawan_id
                       left join p_karyawan c on a.atasan_1 = c.p_karyawan_id
                       left join p_karyawan d on a.atasan_2 = d.p_karyawan_id
                       left join m_jabatan e on a.m_jabatan_id	 = e.m_jabatan_id
                       
                       where a.active = 1  order by a.create_date
                        ";
        $kpi=DB::connection()->select($sqlkpi);
        return view('backend.kpi.kpi',compact('kpi','request'));
        
    }
    
    
    public function tambah_kpi_detail (Request $request,$id)
    {  
    	 $sqlkpi="SELECT *
                       FROM t_kpi a
                       
                       where  t_kpi_id = $id";
        $kpi=DB::connection()->select($sqlkpi);
         $iduser=Auth::user()->id;
			$sqlidkar="select * from p_karyawan where user_id=$iduser";
			$idkar=DB::connection()->select($sqlidkar);
			$idkary=$idkar[0]->p_karyawan_id;
		$sqlkpi="SELECT a.*
                       FROM t_kpi_area_kerja a
                       
                       where a.active = 1 and p_karyawan_id = $idkary
                        ";
        $area=DB::connection()->select($sqlkpi);
		
    	return view('backend.kpi.tambah_kpi_detail',compact('kpi','id','area'));
    }public function edit_kpi_detail (Request $request,$id,$id2)
    {  
    	 $sqlkpi="SELECT *
                       FROM t_kpi a
                       
                       where  t_kpi_id = $id";
        $kpi=DB::connection()->select($sqlkpi);
         $iduser=Auth::user()->id;
			$sqlidkar="select * from p_karyawan where user_id=$iduser";
			$idkar=DB::connection()->select($sqlidkar);
			$idkary=$idkar[0]->p_karyawan_id;
		$sqlkpi="SELECT a.*
                       FROM t_kpi_area_kerja a
                       
                       where a.active = 1 and p_karyawan_id = $idkary
                        ";
        $area=DB::connection()->select($sqlkpi);
		$sqlkpi="SELECT a.*
                       FROM t_kpi_detail a
                       
                       where a.active = 1 and t_kpi_detail_id = $id2
                        ";
        $detail=DB::connection()->select($sqlkpi);
		
    	return view('backend.kpi.edit_kpi_detail',compact('kpi','id','id2','area','detail'));
    }public function kpi_detail(Request $request,$id)
    {
        $sqlkpi="SELECT *
                       FROM t_kpi a
                       
                       where  t_kpi_id = $id 
                       order by create_date";
        $kpi=DB::connection()->select($sqlkpi);
        $kpi = $kpi[0];
		$sqlkpi="SELECT *,(select sum(prioritas) from t_kpi_detail where t_kpi_id = $id)
                       FROM t_kpi_detail a
                       join p_karyawan b on a.p_karyawan_id = b.p_karyawan_id
                       join t_kpi_area_kerja c on a.t_kpi_area_kerja_id = c.t_kpi_area_kerja_id
                       where a.active = 1 and t_kpi_id= $id order by t_kpi_detail_id, a.create_date
                        ";
        $kpi_detail=DB::connection()->select($sqlkpi);
		
       
       	$sqlappr="SELECT * from t_kpi_pencapaian where t_kpi_id = $id";
		$pencapaian=DB::connection()->select($sqlappr);
		$capaian= array();
		foreach($pencapaian as $pencapaian){
			$capaian[$pencapaian->t_kpi_detail_id][$pencapaian->tahun][$pencapaian->tw_ke]['realisasi']		= $pencapaian->realisasi;
			$capaian[$pencapaian->t_kpi_detail_id][$pencapaian->tahun][$pencapaian->tw_ke]['rencana'] 		= $pencapaian->rencana;
			$capaian[$pencapaian->t_kpi_detail_id][$pencapaian->tahun][$pencapaian->tw_ke]['pencapaian'] 	= $pencapaian->pencapaian;
			$capaian[$pencapaian->t_kpi_detail_id][$pencapaian->tahun][$pencapaian->tw_ke]['hasil'] 		= $pencapaian->hasil;
			$capaian[$pencapaian->t_kpi_detail_id][$pencapaian->tahun][$pencapaian->tw_ke]['appr_status'] 	= $pencapaian->appr_status;
			$capaian[$pencapaian->t_kpi_detail_id][$pencapaian->tahun][$pencapaian->tw_ke]['t_kpi_pencapaian_id'] 	= $pencapaian->t_kpi_pencapaian_id;
		}
        $help =new Helper_function();
        $view = 'detail';
        $idkaryawan=null;
        return view('backend.kpi.kpi_detail',compact('kpi_detail','kpi','id','capaian','help','view','idkaryawan'));

        //return view('backend.kpi.kpi_detail',compact('kpi_detail','kpi','id'));
    }

    public function evaluasi_tahunan(Request $request,$id)
    {   
    	$sqlkpi="SELECT *,(select sum(prioritas) from t_kpi_detail where t_kpi_id = $id)
                       FROM t_kpi_detail a
                       join p_karyawan b on a.p_karyawan_id = b.p_karyawan_id
                       join t_kpi_area_kerja c on a.t_kpi_area_kerja_id = c.t_kpi_area_kerja_id
                       join t_kpi d on a.t_kpi_id = d.t_kpi_id
                       where a.active = 1 and a.t_kpi_id= $id order by a.create_date
                        ";
        $kpi_detail=DB::connection()->select($sqlkpi);
		 $iduser=Auth::user()->id;
       
        $sqlidkar="select * from p_karyawan
		join p_karyawan_pekerjaan on p_karyawan_pekerjaan.p_karyawan_id = p_karyawan.p_karyawan_id
		where user_id=$iduser";
		$idkar=DB::connection()->select($sqlidkar);
		$idkaryawan=$idkar[0]->p_karyawan_id;
		$karyawan=DB::connection()->select("select * from get_data_karyawan() where p_karyawan_id = $idkaryawan");
    	$sqlkpi="SELECT *,m_pa_grup.nama as nmgroup,
    	
    			(100/(select count(*) from m_pa_grup)) as bobot,
    			(select (sum(m_pa_jawaban1.jawaban)/count(m_pa_jawaban1.m_pa_jawaban_id)) from m_pa_jawaban1 
    			join m_pa on m_pa_jawaban1.m_pa_id = m_pa.m_pa_id 
    			join m_pa_jawaban on m_pa_jawaban1.m_pa_jawaban_id = m_pa_jawaban.m_pa_jawaban_id 
    			where m_grup_pa_id = m_pa_grup_id 
    				and p_karyawan_id = $idkaryawan
    				and tahun = '".$kpi_detail[0]->tahun."'
    			 ) as nilai
    			
    			from m_pa_grup
    	 ";
        $kemampuan=DB::connection()->select($sqlkpi);
		//,sum(e.jawaban)/count(e.m_pa_jawaban_id) as rata2
		if($request->Cari=='pdf'){
			$data = [
			'id' => $id,
           'kpi_detail' => $kpi_detail,
           'kemampuan' => $kemampuan, 
           'karyawan' => $karyawan];
          
			 $pdf = PDF::loadView('backend.kpi.content_evaluasi_tahunan', $data);
			 return $pdf->download('Evaluasai Tahunan.pdf');
		}else{
			
    	return view('backend.kpi.evaluasi_tahunan',compact('id','kpi_detail','kemampuan','karyawan'));
		}
    }

    public function mentoring_kpi(Request $request,$id)
    {   
    	$sqlkpi="SELECT *
                       FROM t_kpi_mentoring a
                      
                       where a.active = 1 and a.t_kpi_id= $id order by a.create_date
                        ";
        $mentoring=DB::connection()->select($sqlkpi);
    	$sqlkpi="SELECT *
                       FROM t_kpi a
                      
                       where a.active = 1 and a.t_kpi_id= $id 
                        ";
        $kpi=DB::connection()->select($sqlkpi);
        
        if($request->Cari=='pdf'){
			$data = [
			'id' => $id,
           'mentoring' => $mentoring,
           'kpi' => $kpi];
          
			 $pdf = PDF::loadView('backend.kpi.content_mentoring_kpi', $data);
			 return $pdf->download('Evaluasai Tahunan.pdf');
		}else{
			
    	return view('backend.kpi.mentoring_kpi',compact('id','mentoring','kpi'));
		}
    	
    }

    public function tambah_kpi()
    {
        $iduser=Auth::user()->id;
       
        $sqlidkar="select * from p_karyawan
		join p_karyawan_pekerjaan on p_karyawan_pekerjaan.p_karyawan_id = p_karyawan.p_karyawan_id
		where user_id=$iduser";
		$idkar=DB::connection()->select($sqlidkar);
		$id=$idkar[0]->p_karyawan_id;

		$id_karyawan = $idkar[0]->p_karyawan_id;
        $help=new Helper_function();
		$jabstruk = $help->jabatan_struktural($id_karyawan);
		$atasan = $jabstruk['atasan'];
		$bawahan = $jabstruk['bawahan'];
		$sejajar = $jabstruk['sejajar'];

		$sqlappr="SELECT * from get_data_karyawan() WHERE m_jabatan_id in($atasan) ";
		$appr=DB::connection()->select($sqlappr);
      
		$sqlkar="SELECT * from get_data_karyawan() WHERE p_karyawan_id=$id ";
		$kar=DB::connection()->select($sqlkar);
		
		$help = new Helper_function();
		if ($idkar[0]->periode_gajian==1) {
			$sql="select * from m_gaji_bulanan order by tanggal desc limit 1";
			$gapen=DB::connection()->select($sql);
			$help = new Helper_function();
			$tgl_awal_gaji = $gapen[0]->tanggal;
			$next_gajian = $help->tambah_bulan($gapen[0]->tanggal,1);
			if ($next_gajian<date('Y-m-d')) {

				$tgl_awal_gaji = $next_gajian;
				DB::connection()->table("m_gaji_bulanan")
				->insert([
					"tanggal"=>$next_gajian,
				]);

				$next_gajian = $help->tambah_bulan($next_gajian,1);
			}
			$tgl_akhir_gaji = $next_gajian;
			$tgl_akhir_gaji = date('Y-m-d');
			$tgl_awal_gaji2 = $help->tambah_tanggal($tgl_awal_gaji,3);
			$tgl_akhir_gaji2 = $help->tambah_tanggal($next_gajian,3);
			$tgl_awal_cut_off  = $help->tambah_tanggal($tgl_akhir_gaji2,-3);
		} else if ($idkar[0]->periode_gajian==0) {


			$sql="select * from m_gaji_pekanan order by tanggal limit 1";
			$gapen=DB::connection()->select($sql);
			$help = new Helper_function();
			$tgl_awal_gaji = $gapen[0]->tanggal;
			$next_gajian = $help->tambah_tanggal($gapen[0]->tanggal,14);
			if ($next_gajian<date('Y-m-d')) {
				$tgl_awal_gaji = $next_gajian;
				DB::connection()->table("m_gaji_pekanan")
				->insert([
					"tanggal"=>$next_gajian,
				]);

				$next_gajian = $help->tambah_tanggal($next_gajian,14);
			}
			$tgl_akhir_gaji = $next_gajian;
			$tgl_akhir_gaji = date('Y-m-d');
			$tgl_awal_gaji2 = $help->tambah_tanggal($tgl_awal_gaji,1);
			$tgl_akhir_gaji2 = $help->tambah_tanggal($next_gajian,1);
			$tgl_awal_cut_off  = $help->tambah_tanggal($next_gajian,-1);
		}



		$tgl_akhir_cut_off  = $next_gajian;
		$tgl_cut_off ='';
		if ($tgl_awal_cut_off<=date('Y-m-d') and $tgl_akhir_cut_off>=date('Y-m-d'))
			$tgl_cut_off = $tgl_awal_cut_off;
		else
			$tgl_cut_off = $help->tambah_tanggal($tgl_awal_gaji,-1);

      return view('backend.kpi.tambah_kpi', compact('appr','kar','tgl_cut_off'));
    }

    public function simpan_kpi(Request $request){
        DB::beginTransaction();
        try{
            $iduser=Auth::user()->id;
			$sqlidkar="select * from p_karyawan where user_id=$iduser";
			$idkar=DB::connection()->select($sqlidkar);
			$id=$idkar[0]->p_karyawan_id;
            DB::connection()->table("t_kpi")
                ->insert([
                   
                    "p_karyawan_id"=>$id,
                    "m_jabatan_id"=>($request->get("jabatan")),
                    "tahun"=>($request->get("tahun")),
                    "atasan_1"=>($request->get("atasan")),
                    "atasan_2"=>($request->get("atasan2")),
                    "goals_utama"=>($request->get("goal")),
                    "tahun"=>($request->get("tahun")),
                  
                    "active"=>1,
                    "create_by" => $iduser,
                    "create_date" => date("Y-m-d H:i:s")
                ]);
                $sql = "SELECT last_value FROM seq_t_kpi;";
                $seq=DB::connection()->select($sql);
                if ($request->file('file')) {
				//echo 'masuk';die;
				$file = $request->file('file');
				$destination="dist/img/file/";
				$path='kpi-'.date('YmdHis').$file->getClientOriginalName();
				$file->move($destination,$path);
				//echo $path;die;
				DB::connection()->table("t_kpi")->where("t_kpi_id",$seq[0]->last_value)
				->update([
					"file"=>$path
				]);
			}
            DB::commit();
            return redirect()->route('fe.kpi')->with('success','KPI Berhasil di input!');
        }
        catch(\Exeception $e){
            DB::rollback();
            return redirect()->back()->with('error',$e);
        }

    }
    public function simpan_kpi_pencapaian(Request $request,$id,$id2,$type){
        DB::beginTransaction();
        try{
            
            DB::connection()->table("t_kpi_detail")
            	->where('t_kpi_detail_id',$id2)
                ->update([
                    "pencapaian_tw".$type=>($request->get("pencapaian")),
                  
                ]);
                DB::connection()->table("t_kpi_appr")
                ->insert([
                    "appr"=>($request->get("atasan")),
                    "t_kpi_detail_id"=>$id2,
                    "tw_ke"=>($type),
                    "appr_status"=>(3),
                  
                ]);
              
			
            DB::commit();
            return redirect()->route('fe.kpi_detail',$id)->with('success','KPI Berhasil di input!');
        }
        catch(\Exeception $e){
            DB::rollback();
            return redirect()->back()->with('error',$e);
        }

    }public function simpan_kpi_pencapaian_all(Request $request,$id,$type){
        DB::beginTransaction();
        try{
            $collect = $request->get('id');
            for($i=0;$i<count($collect);$i++){
            	
            DB::connection()->table("t_kpi_detail")
            	->where('t_kpi_detail_id',$collect[$i])
                ->update([
                    "pencapaian_tw".$type=>($request->get("pencapaian")[$collect[$i]]),
                  
                ]);
                
                DB::connection()->table("t_kpi_appr")
                ->insert([
                    "appr"=>($request->get("atasan")),
                    "t_kpi_detail_id"=>$collect[$i],
                    "tw_ke"=>($type),
                    "appr_status"=>(3),
                  
                ]);
                
                
            }
              
			
            DB::commit();
            return redirect()->route('fe.kpi_detail',$id)->with('success','KPI Berhasil di input!');
        }
        catch(\Exeception $e){
            DB::rollback();
            return redirect()->back()->with('error',$e);
        }

    }
    public function edit_pencapaian(Request $request,$id,$id2,$type){
    	$sqlkpi="SELECT *
                       FROM t_kpi_detail a
                       join p_karyawan b on a.p_karyawan_id = b.p_karyawan_id
                       join t_kpi_area_kerja c on a.t_kpi_area_kerja_id = c.t_kpi_area_kerja_id
                       where a.active = 1 and t_kpi_detail_id= $id2 order by a.create_date
                        ";
        $kpi_detail=DB::connection()->select($sqlkpi);
        $iduser=Auth::user()->id;
       
        $sqlidkar="select * from p_karyawan
		join p_karyawan_pekerjaan on p_karyawan_pekerjaan.p_karyawan_id = p_karyawan.p_karyawan_id
		where user_id=$iduser";
		$idkar=DB::connection()->select($sqlidkar);
		

		$id_karyawan = $idkar[0]->p_karyawan_id;
       $help=new Helper_function();
		$jabstruk = $help->jabatan_struktural($id_karyawan);
		$atasan = $jabstruk['atasan'];
		$bawahan = $jabstruk['bawahan'];
		$sejajar = $jabstruk['sejajar'];

		$sqlappr="SELECT * from get_data_karyawan() WHERE m_jabatan_id in($atasan) ";
		$appr=DB::connection()->select($sqlappr);
    	return view('backend.kpi.edit_pencapaian',compact('id','id2','type','kpi_detail','appr'));
    } 
    public function edit_pencapaian_all (Request $request,$id,$type){
    	$sqlkpi="SELECT *
                       FROM t_kpi_detail a
                       join p_karyawan b on a.p_karyawan_id = b.p_karyawan_id
                       join t_kpi_area_kerja c on a.t_kpi_area_kerja_id = c.t_kpi_area_kerja_id
                       where a.active = 1 and t_kpi_id= $id  and approve_tw$type = 3
                       order by a.create_date
                      
                        ";
        $kpi_detail=DB::connection()->select($sqlkpi);
        //print_r($kpi_detail);
        $iduser=Auth::user()->id;
       
        $sqlidkar="select * from p_karyawan
		join p_karyawan_pekerjaan on p_karyawan_pekerjaan.p_karyawan_id = p_karyawan.p_karyawan_id
		where user_id=$iduser";
		$idkar=DB::connection()->select($sqlidkar);
		
		$id_karyawan = $idkar[0]->p_karyawan_id;
        $help=new Helper_function();
		$jabstruk = $help->jabatan_struktural($id_karyawan);
		$atasan = $jabstruk['atasan'];
		$bawahan = $jabstruk['bawahan'];
		$sejajar = $jabstruk['sejajar'];

		$sqlappr="SELECT * from get_data_karyawan() WHERE m_jabatan_id in($atasan) ";
		$appr=DB::connection()->select($sqlappr);
    	return view('backend.kpi.edit_pencapaian_all ',compact('id','type','kpi_detail','appr'));
    }
    public function update_kpi_detail(Request $request,$id,$id2){
        DB::beginTransaction();
        try{
            $iduser=Auth::user()->id;
			$sqlidkar="select * from p_karyawan where user_id=$iduser";
			$idkar=DB::connection()->select($sqlidkar);
			$idkayr=$idkar[0]->p_karyawan_id;
			if($request->get('area_kerja')=='Lainnya'){
				 DB::connection()->table("t_kpi_area_kerja")
                ->insert([
                   
                    
                    "p_karyawan_id"=>$idkayr,
                    "nama_area_kerja"=>($request->get("lainnya_area_kerja")),
                   
                    "active"=>1,
                ]);
                $sql = DB::connection()->select("SELECT last_value FROM seq_t_kpi_area_kerja;");;
				 
				$area = $sql[0]->last_value;
			}else{
				$area = $request->get('area_kerja');
			}
            DB::connection()->table("t_kpi_detail")
            ->where('t_kpi_detail_id',$id2)
                ->update([
                   
                    "t_kpi_area_kerja_id"=>($area),
                    "sasaran_kerja"=>($request->get("sasaran_kerja")),
                    "definisi"=>($request->get("definisi")),
                    "target"=>($request->get("target")),
                    "satuan"=>($request->get("satuan")),
                    "prioritas"=>($request->get("prioritas")),
                  
                    "active"=>1,
                    "update_by" => $iduser,
                    "update_date" => date("Y-m-d H:i:s")
                ]);
                $sql = "SELECT last_value FROM seq_t_kpi;";
                $seq=DB::connection()->select($sql);
                
			
            DB::commit();
            return redirect()->route('fe.kpi_detail',$id)->with('success','KPI Berhasil di input!');
        }
        catch(\Exeception $e){
            DB::rollback();
            return redirect()->back()->with('error',$e);
        }

    }
    public function hapus_kpi_detail(Request $request,$id,$id2){
        DB::beginTransaction();
        try{
               $iduser=Auth::user()->id;
            DB::connection()->table("t_kpi_detail")
            ->where('t_kpi_detail_id',$id2)
                ->update([
                   
                   
                    "active"=>0,
                    "update_by" => $iduser,
                    "update_date" => date("Y-m-d H:i:s")
                ]);
                $sql = "SELECT last_value FROM seq_t_kpi;";
                $seq=DB::connection()->select($sql);
                
			
            DB::commit();
            return redirect()->route('fe.kpi_detail',$id)->with('success','KPI Berhasil di hapus!');
        }
        catch(\Exeception $e){
            DB::rollback();
            return redirect()->back()->with('error',$e);
        }

    }
	public function simpan_kpi_detail(Request $request,$id){
        DB::beginTransaction();
        try{
            $iduser=Auth::user()->id;
			$sqlidkar="select * from p_karyawan where user_id=$iduser";
			$idkar=DB::connection()->select($sqlidkar);
			$idkayr=$idkar[0]->p_karyawan_id;
			$area_kerja=DB::connection()->select(
			"select * from t_kpi_area_kerja where nama_area_kerja ='".($request->get("area_kerja"))."' ");  
			
			
			
			
			if(!count($area_kerja)){
				 DB::connection()->table("t_kpi_area_kerja")
                ->insert([
                    "p_karyawan_id"=>$idkayr,
                    "nama_area_kerja"=>($request->get("area_kerja")),
                    "active"=>1,
                ]);
                $sql = DB::connection()->select("SELECT last_value FROM seq_t_kpi_area_kerja;");;
				 
				$area = $sql[0]->last_value;
			}else{
				$area = $area_kerja[0]->t_kpi_area_kerja_id;
			}
            DB::connection()->table("t_kpi_detail")
                ->insert([
                   
                    "t_kpi_id"=>$id,
                    "p_karyawan_id"=>$idkayr,
                    "t_kpi_area_kerja_id"=>($area),
                    "sasaran_kerja"=>($request->get("sasaran_kerja")),
                    "definisi"=>($request->get("definisi")),
                    "target"=>($request->get("target")),
                    "satuan"=>($request->get("satuan")),
                    "prioritas"=>($request->get("prioritas")),
                  
                    "active"=>1,
                    "create_by" => $iduser,
                    "create_date" => date("Y-m-d H:i:s")
                ]);
                $sql = "SELECT last_value FROM seq_t_kpi;";
                $seq=DB::connection()->select($sql);
                
			
            DB::commit();
            return redirect()->route('fe.kpi_detail',$id)->with('success','KPI Berhasil di input!');
        }
        catch(\Exeception $e){
            DB::rollback();
            return redirect()->back()->with('error',$e);
        }

    }

    public function edit_kpi($id)
    {
        $sqlkpi="SELECT * FROM t_kpi WHERE t_kpi_id=$id ORDER BY tanggal";
        $kpi=DB::connection()->select($sqlkpi);

        $iduser=Auth::user()->id;
        $sqluser="SELECT p_recruitment.foto FROM users
left join p_karyawan on p_karyawan.user_id=users.id
left join p_recruitment on p_recruitment.p_recruitment_id=p_karyawan.p_recruitment_id
where users.id=$iduser";
        $user=DB::connection()->select($sqluser);

        return view('backend.kpi.edit_kpi', compact('kpi','user'));
    }

    public function update_kpi(Request $request, $id){
        $idUser=Auth::user()->id;
        DB::beginTransaction();
        try{
            DB::connection()->table("t_kpi")
                ->where("t_kpi_id",$id)
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
            return redirect()->route('be.kpi')->with('success','KPI Berhasil di Ubah!');
        }
        catch(\Exeception $e){
            DB::rollback();
            return redirect()->back()->with('error',$e);
        }
    }

    public function hapus_kpi($id)
    {
        DB::beginTransaction();
        try{
            DB::connection()->table("t_kpi")
                ->where("t_kpi_id",$id)
                ->update([
                    "active" => 0
                ]);
            DB::commit();
            return redirect()->route('fe.kpi')->with('success','KPI Berhasil di Ubah!');
        }
        catch(\Exeception $e){
            DB::rollback();
            return redirect()->back()->with('error',$e);
        }
    }
}

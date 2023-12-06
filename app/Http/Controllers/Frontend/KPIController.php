<?php

namespace App\Http\Controllers\Frontend;

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
			
			$sqlkpi="SELECT *,e.nama as jabatan,b.nama as nama
                       FROM t_kpi a
                       left join p_karyawan b on a.p_karyawan_id = b.p_karyawan_id 
                       left join m_jabatan e on a.m_jabatan_id	 = e.m_jabatan_id
                       
                       where ((atasan_1 = $id and status_appr_1=3) or (atasan_2=$id  and ((status_appr_1=1 and atasan_1 is not null) or atasan_1 is null) ))  and a.active = 1 order by  a.create_date desc";
        $kpi=DB::connection()->select($sqlkpi);
		
       

        return view('frontend.kpi.approval',compact('kpi','request','id'));
    }public function approval_parameter_kpi(Request $request)
    {
        $iduser=Auth::user()->id;
			$sqlidkar="select * from p_karyawan where user_id=$iduser";
			$idkar=DB::connection()->select($sqlidkar);
			$id=$idkar[0]->p_karyawan_id;
			
			$sqlkpi="SELECT *
                       FROM t_kpi_pengajuan_appr a
                        left join t_kpi f on a.t_kpi_id = f.t_kpi_id
                       left join p_karyawan b on f.p_karyawan_id = b.p_karyawan_id 
                       
                       where ((atasan_1 = $id and status_appr_pengajuan1=3) or (atasan_2=$id  and ((a.status_appr_pengajuan1=1 and atasan_1 is not null) or atasan_1 is null) ))  ";
        $kpi=DB::connection()->select($sqlkpi);
		
       

        return view('frontend.kpi.approval_parameter_kpi',compact('kpi','request','id'));
    }
    public function acc_kpi_pengajuan  (Request $request, $detail,$ke)
	{
	    	DB::beginTransaction();
		try {
			$iduser=Auth::user()->id;
			$sqlidkar="select * from p_karyawan where user_id=$iduser";
			$idkar=DB::connection()->select($sqlidkar);
			$id=$idkar[0]->p_karyawan_id;
			DB::connection()->table("t_kpi_pengajuan_appr")
			->where("t_kpi_pengajuan_appr_id",$detail)
			->update([
				"status_appr_pengajuan".$ke=>1,
				"date_appr_pengajuan".$ke => date("Y-m-d H:i:s"),
			
			]);
			
			DB::commit();

			return redirect()->route('fe.approval_parameter_kpi')->with('success','Approval  Berhasil di rubah!');
		} catch (\Exeception $e) {
			DB::rollback();
			return redirect()->back()->with('error',$e);
		}
	}
    public function dec_kpi_pengajuan  (Request $request, $detail,$ke)
	{
	    	DB::beginTransaction();
		try {
			$iduser=Auth::user()->id;
			$sqlidkar="select * from p_karyawan where user_id=$iduser";
			$idkar=DB::connection()->select($sqlidkar);
			$id=$idkar[0]->p_karyawan_id;
			DB::connection()->table("t_kpi_pengajuan")
			->where("t_kpi_pengajuan_id",$detail)
			->update([
				"status_appr_pengajuan".$ke=>2,
				"date_appr_pengajuan".$ke => date("Y-m-d H:i:s"),
			
			]);
			
			DB::commit();

			return redirect()->route('fe.approval_parameter_kpi')->with('success','Approval  Berhasil di rubah!');
		} catch (\Exeception $e) {
			DB::rollback();
			return redirect()->back()->with('error',$e);
		}
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
    
    public function kpi(Request $request)
    {
        $iduser=Auth::user()->id;
			$sqlidkar="select * from p_karyawan where user_id=$iduser";
			$idkar=DB::connection()->select($sqlidkar);
			$idkary=$idkar[0]->p_karyawan_id;
        $sqlkpi="SELECT a.*,b.*,c.nama as appr_1,d.nama as appr_2,e.nama as jabatan,a.create_date
                       FROM t_kpi a
                       left join p_karyawan b on a.p_karyawan_id = b.p_karyawan_id
                       left join p_karyawan c on a.atasan_1 = c.p_karyawan_id
                       left join p_karyawan d on a.atasan_2 = d.p_karyawan_id
                       left join m_jabatan e on a.m_jabatan_id	 = e.m_jabatan_id
                       
                       where a.active = 1 and a.p_karyawan_id = $idkary order by a.create_date
                        ";
        $kpi=DB::connection()->select($sqlkpi);

        

        return view('frontend.kpi.kpi',compact('kpi','request'));
    }public function tambah_kpi_detail (Request $request,$id)
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
		
    	return view('frontend.kpi.tambah_kpi_detail',compact('kpi','id','area'));
    }
    
    public function edit_kpi_detail (Request $request,$id,$id2)
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
                       
                       where a.active = 1 
                        ";
        $area=DB::connection()->select($sqlkpi);
		$sqlkpi="SELECT a.*
                       FROM t_kpi_detail a
                       
                       where a.active = 1 and t_kpi_detail_id = $id2
                        ";
        $detail=DB::connection()->select($sqlkpi);
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
    	return view('frontend.kpi.edit_kpi_detail',compact('kpi','id','id2','area','detail','capaian'));
    }
    public function penilaian_kpi(Request $request)
    {
        
        $help =new Helper_function();
        $view = 'detail';
        $iduser=Auth::user()->id;
			$sqlidkar="select * from p_karyawan where user_id=$iduser";
			$idkar=DB::connection()->select($sqlidkar);
			$idkary=$idkar[0]->p_karyawan_id;
        $idkaryawan=$idkar[0]->p_karyawan_id;
        
        $sqlkpi="SELECT *
                       FROM t_kpi_penilaian_karyawan a
                       join t_kpi on a.t_kpi_id = t_kpi.t_kpi_id
                       join p_karyawan b on t_kpi.p_karyawan_id = b.p_karyawan_id
                       join m_jenis_penilaian_kpi c on c.m_jenis_penilaian_kpi_id = a.m_jenis_penilaian_kpi_id
                       where  p_karyawan_penilai_id = $idkaryawan";
        $kpi=DB::connection()->select($sqlkpi);
         
        return view('frontend.kpi.penilaian_kpi',compact('kpi','help','view','idkaryawan'));
    
    }
    public function form_penilaian_kpi (Request $request,$id)
    {
        
        $help =new Helper_function();
        $view = 'detail';
        $iduser=Auth::user()->id;
			$sqlidkar="select * from p_karyawan where user_id=$iduser";
			$idkar=DB::connection()->select($sqlidkar);
			$idkary=$idkar[0]->p_karyawan_id;
        $idkaryawan=$idkar[0]->p_karyawan_id;
        $kpi=DB::connection()->select("select * from t_kpi_penilaian_karyawan_penilai where t_kpi_penilaian_karyawan_id = $id");
        $penilaian = array();
        foreach($kpi as $kpi){
            $penilaian[$kpi->m_point_utama_kpi_id] = $kpi->penilaian_point;
        }
        $sqlkpi="SELECT *,e.point
                       FROM t_kpi_penilaian_karyawan a
                       left join t_kpi on a.t_kpi_id = t_kpi.t_kpi_id
                       left join p_karyawan b on t_kpi.p_karyawan_id = b.p_karyawan_id
                       left join m_jenis_penilaian_kpi c on c.m_jenis_penilaian_kpi_id = a.m_jenis_penilaian_kpi_id
                       left join m_point_utama_kpi  d on d.m_jenis_penilaian_id  = c.m_jenis_penilaian_kpi_id
                       left join m_parameter_penilaian_kpi    e on d.m_point_utama_kpi_id = e.m_point_utama_kpi_id
                       where  a.t_kpi_penilaian_karyawan_id = $id";
        $kpi=DB::connection()->select($sqlkpi);
        foreach($kpi as $kpi){
            $content[($kpi->nama_point.'||'.$kpi->keterangan_point.'||'.$kpi->m_point_utama_kpi_id)][$kpi->point] = array($kpi->key,$kpi->deskripsi); 
        }
         
        return view('frontend.kpi.form_penilaian_kpi',compact('kpi','help','view','idkaryawan','penilaian','content'));
    
    }
    
    public function kpi_detail(Request $request,$id)
    {
        $sqlkpi="SELECT *
                       FROM t_kpi a
                       
                       where  t_kpi_id = $id";
        $kpi=DB::connection()->select($sqlkpi);
        $kpi = $kpi[0];
		$sqlkpi="SELECT *,(select sum(prioritas) from t_kpi_detail where t_kpi_id = $id)
                       FROM t_kpi_detail a
                       join p_karyawan b on a.p_karyawan_id = b.p_karyawan_id
                       join t_kpi_area_kerja c on a.t_kpi_area_kerja_id = c.t_kpi_area_kerja_id
                       where a.active = 1 and t_kpi_id= $id order by  a.create_date
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
         $iduser=Auth::user()->id;
			$sqlidkar="select * from p_karyawan where user_id=$iduser";
			$idkar=DB::connection()->select($sqlidkar);
			$idkary=$idkar[0]->p_karyawan_id;
        $idkaryawan=$idkar[0]->p_karyawan_id;
        return view('frontend.kpi.kpi_detail',compact('kpi_detail','kpi','id','capaian','help','view','idkaryawan'));
    
    }
    public function kpi_review(Request $request,$id)
    {
        $sqlkpi="SELECT *
                       FROM t_kpi a
                       
                       where  t_kpi_id = $id";
        $kpi=DB::connection()->select($sqlkpi);
        $kpi = $kpi[0];
		$sqlkpi="SELECT *,(select sum(prioritas) from t_kpi_detail where t_kpi_id = $id)
                       FROM t_kpi_detail a
                       join p_karyawan b on a.p_karyawan_id = b.p_karyawan_id
                       join t_kpi_area_kerja c on a.t_kpi_area_kerja_id = c.t_kpi_area_kerja_id
                       where a.active = 1 and t_kpi_id= $id 
                       
                       order by  a.create_date
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
        $iduser=Auth::user()->id;
			$sqlidkar="select * from p_karyawan where user_id=$iduser";
			$idkar=DB::connection()->select($sqlidkar);
			$idkary=$idkar[0]->p_karyawan_id;
        $idkaryawan=$idkar[0]->p_karyawan_id;
        return view('frontend.kpi.kpi_review',compact('kpi_detail','kpi','id','capaian','help','view','idkaryawan'));
    
    }public function kpi_review_detail(Request $request,$id)
    {
        $sqlkpi="SELECT *
                       FROM t_kpi a
                       
                       where  t_kpi_id = $id";
        $kpi=DB::connection()->select($sqlkpi);
        $kpi = $kpi[0];
		$sqlkpi="SELECT *,(select sum(prioritas) from t_kpi_detail where t_kpi_id = $id)
                       FROM t_kpi_detail a
                       join p_karyawan b on a.p_karyawan_id = b.p_karyawan_id
                       join t_kpi_area_kerja c on a.t_kpi_area_kerja_id = c.t_kpi_area_kerja_id
                       where a.active = 1 and t_kpi_id= $id order by  a.create_date
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
        return view('frontend.kpi.kpi_review',compact('kpi_detail','kpi','id','capaian','help','view','idkaryawan'));
    
    }public function approval_kpi_detail(Request $request,$id)
    {
        $sqlkpi="SELECT *
                       FROM t_kpi a
                       
                       where  t_kpi_id = $id";
        $kpi=DB::connection()->select($sqlkpi);
        $kpi = $kpi[0];
		$sqlkpi="SELECT *,(select sum(prioritas) from t_kpi_detail where t_kpi_id = $id)
                       FROM t_kpi_detail a
                       join p_karyawan b on a.p_karyawan_id = b.p_karyawan_id
                       join t_kpi_area_kerja c on a.t_kpi_area_kerja_id = c.t_kpi_area_kerja_id
                       where a.active = 1 and t_kpi_id= $id order by nama_area_kerja,sasaran_kerja, a.create_date
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
        $iduser=Auth::user()->id;
			$sqlidkar="select * from p_karyawan where user_id=$iduser";
			$idkar=DB::connection()->select($sqlidkar);
			$idkaryawan=$idkar[0]->p_karyawan_id;
			
        $view = 'approval';
        return view('frontend.kpi.kpi_detail',compact('kpi_detail','kpi','id','capaian','help','view','idkaryawan'));
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
          
			 $pdf = PDF::loadView('frontend.kpi.content_evaluasi_tahunan', $data);
			 return $pdf->download('Evaluasai Tahunan.pdf');
		}else{
			
    	return view('frontend.kpi.evaluasi_tahunan',compact('id','kpi_detail','kemampuan','karyawan'));
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
          
			 $pdf = PDF::loadView('frontend.kpi.content_mentoring_kpi', $data);
			 return $pdf->download('Evaluasai Tahunan.pdf');
		}else{
			
    	return view('frontend.kpi.mentoring_kpi',compact('id','mentoring','kpi'));
		}
    	
    }

    public function tambah_kpi()
    {
        $iduser=Auth::user()->id;
       
        $sqlidkar="select * from p_karyawan
		join p_karyawan_pekerjaan on p_karyawan_pekerjaan.p_karyawan_id = p_karyawan.p_karyawan_id
		left join m_jabatan on p_karyawan_pekerjaan.m_jabatan_id = m_jabatan.m_jabatan_id
		where user_id=$iduser";
		$idkar=DB::connection()->select($sqlidkar);
		$id=$idkar[0]->p_karyawan_id;

		$id_karyawan = $idkar[0]->p_karyawan_id;
        $help=new Helper_function();
        if($idkar[0]->m_pangkat_id!=6){
		$jabstruk = $help->jabatan_struktural($id_karyawan);
		$atasan = $jabstruk['atasan'];
		$bawahan = $jabstruk['bawahan'];
		$sejajar = $jabstruk['sejajar'];
        $atasan_layer = $jabstruk['atasan_layer'];
		
		if(isset($atasan_layer[2])){
		 	$atasan = isset($atasan_layer[1])?$atasan_layer[1]:'-1';
		    $atasan2 = $atasan_layer[2];
		}else{
		$atasan = '-1';
		
		$atasan2 = isset($atasan_layer[1])?$atasan_layer[1]:'-1';
		}
		
		$sqlappr="SELECT * from get_data_karyawan() WHERE m_jabatan_id in($atasan)   and m_pangkat_id not in (1,2,3)";
		$appr1=DB::connection()->select($sqlappr);
		$sqlappr="SELECT * from get_data_karyawan() WHERE m_jabatan_id in($atasan2) and m_pangkat_id in(5,6) ";
		$appr2=DB::connection()->select($sqlappr);

		$sqlappr="SELECT * from get_data_karyawan() WHERE m_jabatan_id in($atasan) ";
		$appr=DB::connection()->select($sqlappr);
        }else{
            $appr=null;
            $appr1=null;
            $appr2=null;
        }
        
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

      return view('frontend.kpi.tambah_kpi', compact('appr','kar','tgl_cut_off','appr1','appr2'));
    }
    public function simpan_kpi_penilaian(Request $request,$id){
        DB::beginTransaction();
        try{
            $penilaian = $request->penilaian;
            DB::connection()->table("t_kpi_penilaian_karyawan_penilai")->where("t_kpi_penilaian_karyawan_id",$id)->update(["active"=>0]);
            $total_value =0;
            $total =count($penilaian);;
            foreach($penilaian as $key=>$value){
                $total_value+=$value;
                DB::connection()->table("t_kpi_penilaian_karyawan_penilai")
                    ->insert([
                        "penilaian_point"=>$value,
                        "t_kpi_penilaian_karyawan_id"=>$id,
                        "m_point_utama_kpi_id"=>$key
                    ]);
             
                
            }
            DB::connection()->table("t_kpi_penilaian_karyawan")
                    ->where("t_kpi_penilaian_karyawan_id",$id)
                    ->update([
                        "point"=>($total>0?$total_value/$total:0),
                        "penilaian"=>2
                    ]);
             DB::commit();
            return redirect()->route('fe.penilaian_kpi')->with('success','KPI Berhasil di input!');
        }
        catch(\Exeception $e){
            DB::rollback();
            return redirect()->back()->with('error',$e);
        }

    }
    public function simpan_kpi(Request $request){
        DB::beginTransaction();
        try{
            $iduser=Auth::user()->id;
			$sqlidkar="select * from p_karyawan  pk
			left join p_karyawan_pekerjaan pkp on pk.p_karyawan_id = pkp.p_karyawan_id
			left join m_jabatan mj on mj.m_jabatan_id = pkp.m_jabatan_id
			where user_id=$iduser";
			$idkar=DB::connection()->select($sqlidkar);
			$id=$idkar[0]->p_karyawan_id;
			$data = [
                   
                    "p_karyawan_id"=>$id,
                    "m_jabatan_id"=>($request->get("jabatan")),
                    "tahun"=>($request->get("tahun")),
                    "atasan_1"=>($request->get("atasan")),
                    "atasan_2"=>($request->get("atasan2")),
                    "goals_utama"=>($request->get("goal")),
                    // "tahun_awal"=>($request->get("tahun_awal")),
                    // "tahun_akhir"=>($request->get("tahun_akhir")),
                    // "triwulan_awal"=>($request->get("triwulan_awal")),
                    // "triwulan_akhir"=>($request->get("triwulan_akhir")),
                    
                    "tahun"=>(date('Y')),
                    "bulan"=>(date('m')),
                    "tanggal_awal"=>($request->tanggal_awal),
                    "tanggal_akhir"=>($request->tanggal_akhir),
                    "periode_kpi"=>($request->get("periode")),
                    "tipe_pencapaian"=>($request->get("tipe_pencapaian")),
                  
                    "active"=>1,
                    "create_by" => $iduser,
                    "create_date" => date("Y-m-d H:i:s")
                ];
            if($idkar[0]->m_pangkat_id==6){
                $data["atasan_2"]=-1;
            }
            DB::connection()->table("t_kpi")
                ->insert($data);
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

    }public function simpan_kpi_pencapaian_all(Request $request,$id,$tahun,$type){
        DB::beginTransaction();
        try{
            $collect = $request->get('id');
            for($i=0;$i<count($collect);$i++){
            	
            	//DB::connection()->table("t_kpi_detail")
            	//->where('t_kpi_detail_id',$collect[$i])
                //->update([
                 //   "pencapaian_tw".$type=>($request->get("pencapaian")[$collect[$i]]),
                //]);
                if($request->get('ada')[$collect[$i]]){
                	 DB::connection()->table("t_kpi_pencapaian")
                	 ->where("t_kpi_pencapaian_id",$request->get('capai_id')[$collect[$i]])
	                ->update([
	                	"realisasi"=>(float) str_replace(",",".",$request->get("realisasi")[$collect[$i]]),
	                	//"rencana"=>($request->get("rencana")[$collect[$i]]),
	                	"hasil"=>(float) str_replace(",",".",$request->get("hasil")[$collect[$i]]),
	                	"pencapaian"=>(float) str_replace(",",".",$request->get("pencapaian")[$collect[$i]]),
	                    
	                  
	                ]);
                }else{
	                DB::connection()->table("t_kpi_pencapaian")
	                ->insert([
	                	"realisasi"=>($request->get("satuan")=='nominal'?
                                ($help->hapusRupiah(($request->get("realisasi")[$collect[$i]]))) :
                                    ((float) str_replace(",",".",str_replace('.','',($request->get("realisasi")[$collect[$i]])))))
                                    
                                    ,
	                	//"rencana"=>($request->get("rencana")[$collect[$i]]),
	                	"hasil"=>($request->get("satuan")=='nominal'?
                                ($help->hapusRupiah(($request->get("hasil")[$collect[$i]]))) :
                                    ((float) str_replace(",",".",str_replace('.','',($request->get("hasil")[$collect[$i]])))))
                                    ,
	                	"pencapaian"=>($request->get("satuan")=='nominal'?
                                ($help->hapusRupiah(($request->get("pencapaian")[$collect[$i]]))) :
                                    ((float) str_replace(",",".",str_replace('.','',($request->get("pencapaian")[$collect[$i]])))))
                                    ,
	                    "appr"=>($request->get("atasan")),
	                    "t_kpi_detail_id"=>$collect[$i],
	                    "tahun"=>($tahun),
	                    "tw_ke"=>($type),
	                    "appr_status"=>(3),
	                    "t_kpi_id"=>$id,
	                  
	                ]);
                }
                
            }
            DB::connection()->table("t_kpi_pengajuan_appr")
	                ->insert([
	                    "tahun"=>$tahun,
	                    "tw"=>$type,
	                    "t_kpi_id"=>$id,
	                    "tanggal_pengajuan"=>date('Y-m-d'),
	                    ]);
	                    $sqlkpi="SELECT *
                       FROM t_kpi a
                       
                       where  t_kpi_id = $id";
                        $kpi=DB::connection()->select($sqlkpi);
                        $kpi = $kpi[0];
			                $tahun_awal = date('Y',strtotime(date($kpi->tanggal_awal)));
                            $bulan_awal = date('m',strtotime(date($kpi->tanggal_awal)));
                            $tahun_akhir = date('Y',strtotime(date($kpi->tanggal_akhir)));
                            $bulan_akhir = date('m',strtotime(date($kpi->tanggal_akhir)));
                            $total=0;
                            for($i=$tahun_awal;$i<=$tahun_akhir;$i++){
                            $tw = 1;
                            $tw_akhir = 4;
                            if($i==$tahun_awal){
                                if(in_array($bulan_awal,array(1,2,3))){
                                    $tw=1;
                                }else if(in_array($bulan_awal,array(4,5,6))){
                                    $tw=2;
                                }else if(in_array($bulan_awal,array(7,8,9))){
                                    $tw=3;
                                }else if(in_array($bulan_awal,array(10,11,12))){
                                    $tw=4;
                                }
                            }elseif($i==$tahun_akhir){
                               if(in_array($bulan_akhir,array(1,2,3))){
                                    $tw_akhir=1;
                                }else if(in_array($bulan_akhir,array(4,5,6))){
                                    $tw_akhir=2;
                                }else if(in_array($bulan_akhir,array(7,8,9))){
                                    $tw_akhir=3;
                                }else if(in_array($bulan_akhir,array(10,11,12))){
                                    $tw_akhir=4;
                                }
                            }
                              for($j=$tw;$j<=$tw_akhir;$j++){
                                
                                	$total+=1;
                              }
                            }
                             echo 'HASDA'.$tahun."=".$tahun_akhir;
                             echo '<br>';
            if($tahun==$tahun_akhir and $type==$tw_akhir){
                $iduser=Auth::user()->id;
		$sqlidkar="select *,p_karyawan.p_karyawan_id as karyawan_id from p_karyawan
		join p_karyawan_pekerjaan on p_karyawan_pekerjaan.p_karyawan_id = p_karyawan.p_karyawan_id
		join m_jabatan on p_karyawan_pekerjaan.m_jabatan_id = m_jabatan.m_jabatan_id
		where user_id=$iduser";
		$idkar=DB::connection()->select($sqlidkar);
			$id_karyawan = $idkar[0]->p_karyawan_id;
			$help = new Helper_function();
                echo 'HASDA';
                if($idkar[0]->m_pangkat_id==6 and !count($appr)){
        		    $atasan =  -1;
            	}else{
        		$jabstruk = $help->jabatan_struktural($id_karyawan);
        		$atasan = $jabstruk['atasan'];
        		$bawahan = $jabstruk['bawahan'];
        		$sejajar = $jabstruk['sejajar'];
        		$atasan_layer = $jabstruk['atasan_layer'];
        		
        	
        		 $atasan_1 = isset($atasan_layer[1])?$atasan_layer[1]:'-1';
        		 $atasan2 = $atasan_layer[2];
        	
        		
        		$sqlappr="SELECT * from get_data_karyawan() WHERE m_jabatan_id in($atasan_1)   and m_pangkat_id not in (1,2,3) order by RANDOM () limit 1 ";
        		$penatasan=DB::connection()->select($sqlappr);
                foreach($penatasan as $penatasan){
                
                DB::connection()->table("t_kpi_penilaian_karyawan")->insert([
                        "t_kpi_id"=>$id,
                        "m_jenis_penilaian_kpi_id"=>1,
                        "p_karyawan_penilai_id"=>$penatasan->p_karyawan_id,
                    ]);
                }
                $sqlappr="SELECT * from get_data_karyawan() WHERE m_jabatan_id in($atasan,$bawahan,$sejajar)   and m_pangkat_id not in (1,2,3) order by RANDOM () limit 2 ";
        		$penatasan=DB::connection()->select($sqlappr);
        	//	print_r($penatasan);
                foreach($penatasan as $penatasan){
                DB::connection()->table("t_kpi_penilaian_karyawan")->insert([
                        "t_kpi_id"=>$id,
                        "m_jenis_penilaian_kpi_id"=>2,
                        "p_karyawan_penilai_id"=>$penatasan->p_karyawan_id,
                    ]);
                }
            }
          
        }
         DB::commit();
            
            return redirect()->route('fe.kpi_review',[$id,'tahun_tw='.$tahun.'-'.$type])->with('success','KPI Berhasil di input!');
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
    	return view('frontend.kpi.edit_pencapaian',compact('id','id2','type','kpi_detail','appr'));
    } 
    public function edit_pencapaian_all (Request $request,$id,$tahun,$type){
    	$sqlkpi="SELECT *
                       FROM t_kpi_detail a
                       join p_karyawan b on a.p_karyawan_id = b.p_karyawan_id
                       join t_kpi_area_kerja c on a.t_kpi_area_kerja_id = c.t_kpi_area_kerja_id
                       where a.active = 1 and t_kpi_id= $id  
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
		
		$sqlappr="SELECT * from t_kpi_pencapaian where t_kpi_id = $id and active=1";
		$pencapaian=DB::connection()->select($sqlappr);
		foreach($pencapaian as $pencapaian){
			$capaian[$pencapaian->t_kpi_detail_id][$pencapaian->tahun][$pencapaian->tw_ke]['realisasi']		= $pencapaian->realisasi;
			$capaian[$pencapaian->t_kpi_detail_id][$pencapaian->tahun][$pencapaian->tw_ke]['rencana'] 		= $pencapaian->rencana;
			$capaian[$pencapaian->t_kpi_detail_id][$pencapaian->tahun][$pencapaian->tw_ke]['pencapaian'] 	= $pencapaian->pencapaian;
			$capaian[$pencapaian->t_kpi_detail_id][$pencapaian->tahun][$pencapaian->tw_ke]['hasil'] 		= $pencapaian->hasil;
			$capaian[$pencapaian->t_kpi_detail_id][$pencapaian->tahun][$pencapaian->tw_ke]['appr_status'] 	= $pencapaian->appr_status;
			$capaian[$pencapaian->t_kpi_detail_id][$pencapaian->tahun][$pencapaian->tw_ke]['t_kpi_pencapaian_id'] 	= $pencapaian->t_kpi_pencapaian_id;
		}
    	return view('frontend.kpi.edit_pencapaian_all ',compact('id','type','tahun','kpi_detail','appr','capaian'));
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
                    "target"=>(float) str_replace(",",".",$request->get("target")),
                    "satuan"=>($request->get("satuan")),
                    "prioritas"=>($request->get("prioritas")),
                  
                    "active"=>1,
                    "update_by" => $iduser,
                    "update_date" => date("Y-m-d H:i:s")
                ]);
                $sql = "SELECT last_value FROM seq_t_kpi;";
                $seq=DB::connection()->select($sql);
                
			    $rencana = $request->rencana;
			    $id_pencapaian = $request->id_pencapaian;
		
			foreach($rencana as $key=>$value){
				foreach($rencana[$key] as $key2=>$value2){
				   echo $id_pencapaian[$key][$key2];echo '<br>';
				   if($id_pencapaian[$key][$key2]){
					DB::connection()->table("t_kpi_pencapaian")
			                ->where("t_kpi_pencapaian_id",$id_pencapaian[$key][$key2])
			                ->update([
			                	//"realisasi"=>($request->get("realisasi")[$collect[$i]]),
			                	"rencana"=>(float) str_replace(",",".",$rencana[$key][$key2]),
			                	//"hasil"=>($request->get("hasil")[$collect[$i]]),
			                	//"pencapaian"=>($request->get("pencapaian")[$collect[$i]]),
			                    "t_kpi_detail_id"=>$id2,
			                    "tahun"=>($key),
			                    "tw_ke"=>($key2),
			                    "appr_status"=>(3),
			                    "t_kpi_id"=>$id,
			                  
			                ]);
				   }else{
				       DB::connection()->table("t_kpi_pencapaian")
			                
			                ->insert([
			                	//"realisasi"=>($request->get("realisasi")[$collect[$i]]),
			                	"rencana"=>(float) str_replace(",",".",$rencana[$key][$key2]),
			                	//"hasil"=>($request->get("hasil")[$collect[$i]]),
			                	//"pencapaian"=>($request->get("pencapaian")[$collect[$i]]),
			                    "t_kpi_detail_id"=>$id2,
			                    "tahun"=>($key),
			                    "tw_ke"=>($key2),
			                    "appr_status"=>(3),
			                    "t_kpi_id"=>$id,
			                  
			                ]);
				   }
				}
			}
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
            $help = new Helper_function();
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
                    "target"=>($request->get("satuan")=='nominal'?
                                ($help->hapusRupiah($request->get("target"))) :
                                    ((float) str_replace(",",".",$request->get("target")))),
                    "satuan"=>($request->get("satuan")),
                    "prioritas"=>($request->get("prioritas")),
                  
                    "active"=>1,
                    "create_by" => $iduser,
                    "create_date" => date("Y-m-d H:i:s")
                ]);
                $sql = "SELECT last_value FROM seq_t_kpi_detail;";
                $seq=DB::connection()->select($sql);
                
            $rencana = $request->rencana;
		//	print_r($rencana);
			foreach($rencana as $key=>$value){
				foreach($rencana[$key] as $key2=>$value2){
					DB::connection()->table("t_kpi_pencapaian")
			                ->insert([
			                	//"realisasi"=>($request->get("realisasi")[$collect[$i]]),
			                	"rencana"=>($request->get("satuan")=='nominal'?
                                ($help->hapusRupiah($rencana[$key][$key2])) :
                                    ((float) str_replace(",",".",str_replace('.','',$rencana[$key][$key2])))),
			                	//"hasil"=>($request->get("hasil")[$collect[$i]]),
			                	//"pencapaian"=>($request->get("pencapaian")[$collect[$i]]),
			                    "t_kpi_detail_id"=>$seq[0]->last_value,
			                    "tahun"=>($key),
			                    "tw_ke"=>($key2),
			                    "appr_status"=>(3),
			                    "t_kpi_id"=>$id,
			                  
			                ]);
				}
			}
            DB::commit();
           return redirect()->route('fe.kpi_detail',$id)->with('success','KPI Berhasil di input!');
        }
        catch(\Exeception $e){
            DB::rollback();
            return redirect()->back()->with('error',$e);
        }

    }

    // public function edit_kpi($id)
    // {
//         $sqlkpi="SELECT * FROM t_kpi WHERE t_kpi_id=$id ORDER BY tanggal";
//         $kpi=DB::connection()->select($sqlkpi);

//         $iduser=Auth::user()->id;
//         $sqluser="SELECT p_recruitment.foto FROM users
// left join p_karyawan on p_karyawan.user_id=users.id
// left join p_recruitment on p_recruitment.p_recruitment_id=p_karyawan.p_recruitment_id
// where users.id=$iduser";
//         $user=DB::connection()->select($sqluser);

    //     return view('frontend.kpi.edit_kpi', compact('kpi','user'));
    // }
    public function edit_kpi (Request $request,$id)
    {  
    	 $sqlkpi="SELECT *
                      FROM t_kpi a
                       
                      where  t_kpi_id = $id";
        $kpi=DB::connection()->select($sqlkpi);
         $iduser=Auth::user()->id;
			$sqlidkar="select *,p_karyawan.nama as nama_lengkap from p_karyawan where user_id=$iduser";
			$idkar=DB::connection()->select($sqlidkar);
			$kar = $idkar;
			$idkary=$idkar[0]->p_karyawan_id;
			$sqlkar="SELECT * from get_data_karyawan() WHERE p_karyawan_id=$idkary ";
			$id_karyawan = $idkary;
		    $kar=DB::connection()->select($sqlkar);
		    $help=new Helper_function();
		$jabstruk = $help->jabatan_struktural($id_karyawan);
		$atasan = $jabstruk['atasan'];
		$bawahan = $jabstruk['bawahan'];
		$sejajar = $jabstruk['sejajar'];
        $atasan_layer = $jabstruk['atasan_layer'];
		
		if(isset($atasan_layer[2])){
		 	$atasan = isset($atasan_layer[1])?$atasan_layer[1]:'-1';
		    $atasan2 = $atasan_layer[2];
		}else{
		$atasan = '-1';
		
		$atasan2 = isset($atasan_layer[1])?$atasan_layer[1]:'-1';
		}
		
		$sqlappr="SELECT * from get_data_karyawan() WHERE m_jabatan_id in($atasan)   and m_pangkat_id not in (1,2,3)";
		$appr1=DB::connection()->select($sqlappr);
		$sqlappr="SELECT * from get_data_karyawan() WHERE m_jabatan_id in($atasan2) and m_pangkat_id in(5,6) ";
		$appr2=DB::connection()->select($sqlappr);

		$sqlappr="SELECT * from get_data_karyawan() WHERE m_jabatan_id in($atasan) ";
		$appr=DB::connection()->select($sqlappr);
		
    	return view('frontend.kpi.edit_kpi',compact('kpi','id','kar','appr1','appr2','id_karyawan'));
    }

    public function update_kpi(Request $request, $id){
        $idUser=Auth::user()->id;
        DB::beginTransaction();
        try{
            DB::connection()->table("t_kpi")
                ->where("t_kpi_id",$id)
                ->update([
                    "tahun"=>($request->get("tahun")),
                    "atasan_1"=>($request->get("atasan")),
                    "atasan_2"=>($request->get("atasan2")),
                    "goals_utama"=>($request->get("goal")),
                    // "tahun_awal"=>($request->get("tahun_awal")),
                    // "tahun_akhir"=>($request->get("tahun_akhir")),
                    // "triwulan_awal"=>($request->get("triwulan_awal")),
                    // "triwulan_akhir"=>($request->get("triwulan_akhir")),
                    
                    "tahun"=>(date('Y')),
                    "bulan"=>(date('m')),
                    "tanggal_awal"=>($request->tanggal_awal),
                    "tanggal_akhir"=>($request->tanggal_akhir),
                    "periode_kpi"=>($request->get("periode")),
                    "tipe_pencapaian"=>($request->get("tipe_pencapaian")),
                    
                    "active"=>1,
                    "update_by" => $idUser,
                    "update_date" => date("Y-m-d H:i:s")
                ]);
            DB::commit();
            return redirect()->route('fe.kpi')->with('success','KPI Berhasil di Ubah!');
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

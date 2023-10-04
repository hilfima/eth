<?php

namespace App\Http\Controllers\Backend;

use App\ajuan_xls;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\Setting;
use DB;
use Maatwebsite\Excel\Excel;
use Mail;
use App\Helper_function;
use Response;

class GajiGenerateController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
   

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

    public function repost_generate(Request $request, $id_generate)
    {
        $list_karyawan = $request->get("karyawan");
		DB::enableQueryLog();

        for ($i = 0; $i < count($list_karyawan); $i++) {
            $gen = DB::connection()->select("select * from prl_generate_karyawan where prl_generate_id=$id_generate and p_karyawan_id = " . $list_karyawan[$i]);
            if (count($gen)) {

                DB::connection()->table("prl_generate_karyawan")
                    ->where('p_karyawan_id', $list_karyawan[$i])
                    ->where('prl_generate_id', $id_generate)
                    ->update([
                        "status" => 0,
                    ]);
            } else {
                DB::connection()->table("prl_generate_karyawan")

                    ->insert([
                        "p_karyawan_id" => $list_karyawan[$i],
                        "prl_generate_id" => $id_generate,
                        "status" => 0,
                    ]);
            }
        }
	        $query = (DB::getQueryLog()); $help = new Helper_function();
			$help->historis(($_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']),json_encode($query));
        return redirect()->route('be.proses_generate', $id_generate)->with('success', ' Generate Sedang di proses mohon tunggu sebentar!');
    }
    public function post_generate(Request $request)
    {
    	DB::enableQueryLog();
        $tahun = $request->get("tahun");
        $bulan = $request->get("bulan");
        $periode_gajian = $request->get("periode_gajian");
        $periode_absen = $request->get("periode_absen");
        $periode_lembur = $request->get("periode_lembur");
        $sql = "select appr_status from prl_generate where tahun=$tahun and bulan = $bulan and periode_gajian=$periode_gajian
    		and periode_absen_id=$periode_absen and periode_lembur_id=$periode_lembur and active != 0
    	";
        $gen = DB::connection()->select($sql);
        if (!count($gen)) {
            $acc = true;
        } else {

            if ($gen[0]->appr_status == 1) {
                $acc = false;
            } else {
                $acc = true;
            }
        }
        if ($acc) {

            $sql = "select max(prl_generate_id) from prl_generate ";
            $searchid = DB::connection()->select($sql);
            $id = 1;
            if (count($searchid))
                $id = ($searchid[0]->max) + 1;
         


            DB::connection()->table("prl_generate")
                ->where("tahun", $request->get("tahun"))
                ->where("bulan", $request->get("bulan"))
                ->where("periode_gajian", $request->get("periode_gajian"))
                ->where("periode_absen_id", $request->get("periode_absen"))
                ->where("periode_lembur_id", $request->get("periode_lembur"))
                ->update([
                    "active" => 0,


                ]);
            DB::connection()->table("prl_generate")
                ->insert([
                    "tahun" => $request->get("tahun"),
                    "bulan" => $request->get("bulan"),
                    "periode_gajian" => $request->get("periode_gajian"),
                    "periode_absen_id" => $request->get("periode_absen"),
                    "periode_lembur_id" => $request->get("periode_lembur"),
                    "prl_generate_id" => $id,
                    "create_date" => date('Y-m-d H:i:s'),

                ]);
            $sql = "select DISTINCT(b.p_karyawan_id) 
		from p_karyawan a 
		join p_karyawan_gapok b on b.p_karyawan_id = a.p_karyawan_id 
		join p_karyawan_pekerjaan c on b.p_karyawan_id = a.p_karyawan_id 
		where a.active=1 and c.periode_gajian= " . $request->get("periode_gajian");
            
            return redirect()->route('be.proses_generate', $id)->with('success', ' Generate Sedang di proses mohon tunggu sebentar!');
            $query = (DB::getQueryLog()); $help = new Helper_function();
			$help->historis(($_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']),json_encode($query));
        } else {
            return redirect()->route('be.tambah_generate')->with('success', ' Generate Gagal Dibuat! Periode Generate sudah di Approve!!');
        }
    }
    public function to_generate(Request $request)
    {
		DB::enableQueryLog();
        $tahun = $request->get("tahun");
        $bulan = $request->get("bulan");
        $periode_gajian = $request->get("periode_gajian");
        $pekanan_ke = $request->get("pekanan_ke");
        $periode_absen = $request->get("periode_absen");
        $periode_lembur = $request->get("periode_lembur");
        $periode_absen_bulanan = $request->get("periode_absen_bulanan");
        $periode_lembur_bulanan = $request->get("periode_lembur_bulanan");
        $periode_absen_pekanan = $request->get("periode_absen_pekanan");
        $periode_lembur_pekanan = $request->get("periode_lembur_pekanan");
        if($periode_gajian==2){
        		
		        $sql = "select appr_status from prl_generate where tahun=$tahun and bulan = $bulan and periode_gajian=$periode_gajian
		    		and periode_absen_bulanan_id=$periode_absen_bulanan and periode_lembur_bulanan_id=$periode_lembur_bulanan and periode_absen_pekanan_id=$periode_absen_pekanan and periode_lembur_pekanan_id=$periode_lembur_pekanan and active != 0
		    	";
		        $gen = DB::connection()->select($sql);
		        if (!count($gen)) {
		            $acc = true;
		        } else {

		            if ($gen[0]->appr_status == 1) {
		                $acc = false;
		            } else {
		                $acc = true;
		            }
		        }
		        
		        if($periode_gajian==1){
		        	$periode_absen_search = 'periode_absen_bulanan';
		        	$periode_lembur_search = 'periode_lembur_bulanan';
		        }else{
		        	$periode_absen_search = 'periode_absen_pekanan';
		        	$periode_lembur_search = 'periode_lembur_pekanan';
		        	
		        }
		        $sql = "select appr_status from prl_generate where tahun=$tahun and bulan = $bulan and periode_gajian=1
		    		and periode_absen_id=".$periode_absen_bulanan." and periode_lembur_id=".$periode_lembur_bulanan." and active != 0
		    	";
		        $gen = DB::connection()->select($sql);
		        if (!count($gen)) {
		            $acc_bulanan = true;
		        } else {

		            if ($gen[0]->appr_status == 1) {
		                $acc_bulanan = false;
		            } else {
		                $acc_bulanan = true;
		            }
		        }
		        $sql = "select appr_status from prl_generate where tahun=$tahun and bulan = $bulan and periode_gajian=0
		    		and periode_absen_id=".$periode_absen_pekanan." and periode_lembur_id=".$periode_lembur_pekanan." and active != 0
		    	";
		        $gen = DB::connection()->select($sql);
		        if (!count($gen)) {
		            $acc_pekanan = true;
		        } else {

		            if ($gen[0]->appr_status == 1) {
		                $acc_pekanan = false;
		            } else {
		                $acc_pekanan = true;
		            }
		        }
		        if ($acc and $acc_bulanan and $acc_pekanan) {

		            $sql = "select max(prl_generate_id) from prl_generate ";
		            $searchid = DB::connection()->select($sql);
		            $id = 1;
		            if (count($searchid))
		                $id = ($searchid[0]->max) + 1;
		         

					

		            DB::connection()->table("prl_generate")
		                ->where("tahun", $request->get("tahun"))
		                ->where("bulan", $request->get("bulan"))
		                ->where("periode_gajian", $request->get("periode_gajian"))
		                ->where("periode_lembur_bulanan_id", $request->get("periode_lembur_bulanan"))
		                ->where("periode_absen_bulanan_id", $request->get("periode_absen_bulanan"))
		                ->where("periode_absen_pekanan_id", $request->get("periode_absen_pekanan"))
		                ->where("periode_lembur_pekanan_id", $request->get("periode_lembur_pekanan"))
		                ->update([
		                    "active" => 0,


		                ]);
		            
	            DB::connection()->table("prl_generate")
	                ->where("tahun", $request->get("tahun"))
	                ->where("bulan", $request->get("bulan"))
	                ->where("periode_gajian", 1)
	                ->where("periode_absen_id", $request->get("periode_absen_bulanan"))
	                ->where("periode_lembur_id", $request->get("periode_lembur_bulanan"))
	                ->update([
	                    "active" => 0,


	                ]);
		            
	            DB::connection()->table("prl_generate")
	                ->where("tahun", $request->get("tahun"))
	                ->where("bulan", $request->get("bulan"))
	                ->where("periode_gajian", 0)
	                ->where("periode_absen_id", $request->get("periode_absen_pekanan"))
	                ->where("periode_lembur_id", $request->get("periode_lembur_pekanan"))
	                ->update([
	                    "active" => 0,


	                ]);
		            DB::connection()->table("prl_generate")
		                ->insert([
		                    "tahun" => $request->get("tahun"),
		                    "bulan" => $request->get("bulan"),
		                    "periode_gajian" => $request->get("periode_gajian"),
		                    "periode_absen_bulanan_id" => $request->get("periode_absen_bulanan"),
		                    "periode_lembur_bulanan_id" => $request->get("periode_lembur_bulanan"),
		                    "periode_absen_pekanan_id" => $request->get("periode_absen_pekanan"),
		                    "periode_lembur_pekanan_id" => $request->get("periode_lembur_pekanan"),
		                    "generate_pekanan_ke" => $request->get("pekanan_ke"),
		                    "prl_generate_id" => $id,
		                    "create_date" => date('Y-m-d H:i:s'),

		                ]);
		             if($request->periode_gajian==2)
        $where = '';
        else
        $where = " and c.periode_gajian= " . $request->get("periode_gajian");
        
        $sql = "select DISTINCT(a.p_karyawan_id) , m_bank_id,norek,m_lokasi_id,pajak_onoff,m_jabatan_id,periode_gajian,nik
			from p_karyawan a 
			join p_karyawan_pekerjaan c on a.p_karyawan_id = c.p_karyawan_id 
			where a.active=1  $where";

            $search_karyawan = DB::connection()->select($sql);
            foreach ($search_karyawan as $karyawan) {
                DB::connection()->table("prl_generate_karyawan")
                    ->insert([
                        "p_karyawan_id" => $karyawan->p_karyawan_id,
                        "prl_generate_id" => $id,
                        "m_bank_id" => $karyawan->m_bank_id,
                        "no_rek" => $karyawan->norek,
                        "lokasi_id" => $karyawan->m_lokasi_id,
                        "on_off" => $karyawan->pajak_onoff,
                        "jabatan_id" => $karyawan->m_jabatan_id,
                        "periode_gajian" => $karyawan->periode_gajian,
                        "nikgenerate" => $karyawan->nik,

                    ]);
            }
        $query = (DB::getQueryLog()); $help = new Helper_function();
		$help->historis(($_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']),json_encode($query));
		            return redirect()->route('be.proses_generate', $id)->with('success', ' Generate Sedang di proses mohon tunggu sebentar!');
		        } else {
		           
		            return redirect()->route('be.tambah_generate_gaji')->with('success', ' Generate Gagal Dibuat! Terdapat Periode Generate yang sama yang sudah di Approve!!');
		        }
        
        }else{
        	
        $sql = "select appr_status from prl_generate where tahun=$tahun and bulan = $bulan and periode_gajian=$periode_gajian
    		and periode_absen_id=$periode_absen and periode_lembur_id=$periode_lembur and active != 0
    	";
        $gen = DB::connection()->select($sql);
        if (!count($gen)) {
            $acc = true;
        } else {

            if ($gen[0]->appr_status == 1) {
                $acc = false;
            } else {
                $acc = true;
            }
        }
        
        if($periode_gajian==1){
        	$periode_absen_search = 'periode_absen_bulanan';
        	$periode_lembur_search = 'periode_lembur_bulanan';
        }else{
        	$periode_absen_search = 'periode_absen_pekanan';
        	$periode_lembur_search = 'periode_lembur_pekanan';
        	
        }
        $sql = "select appr_status from prl_generate where tahun=$tahun and bulan = $bulan and periode_gajian=2
    		and ".$periode_absen_search."_id=".$periode_absen." and ".$periode_lembur_search."_id=".$periode_lembur." and active != 0
    	";
        $gen = DB::connection()->select($sql);
        if (!count($gen)) {
            $acc_bupek = true;
        } else {

            if ($gen[0]->appr_status == 1) {
                $acc_bupek = false;
                
            } else {
                $acc_bupek = true;
            }
        }
        if ($acc and $acc_bupek) {

            $sql = "select max(prl_generate_id) from prl_generate ";
            $searchid = DB::connection()->select($sql);
            $id = 1;
            if (count($searchid))
                $id = ($searchid[0]->max) + 1;
         


            DB::connection()->table("prl_generate")
                ->where("tahun", $request->get("tahun"))
                ->where("bulan", $request->get("bulan"))
                ->where("periode_gajian", $request->get("periode_gajian"))
                ->where("periode_absen_id", $request->get("periode_absen"))
                ->where("periode_lembur_id", $request->get("periode_lembur"))
                ->update([
                    "active" => 0,


                ]);
            DB::connection()->table("prl_generate")
                ->insert([
                    "tahun" => $request->get("tahun"),
                    "bulan" => $request->get("bulan"),
                    "periode_gajian" => $request->get("periode_gajian"),
                    "periode_absen_id" => $request->get("periode_absen"),
                    "periode_lembur_id" => $request->get("periode_lembur"),
		                    "generate_pekanan_ke" => $request->get("pekanan_ke"),
                    "prl_generate_id" => $id,
                    "create_date" => date('Y-m-d H:i:s'),

                ]);
             if($request->periode_gajian==2)
        $where = '';
        else
        $where = " and c.periode_gajian= " . $request->get("periode_gajian");
        
        $sql = "select DISTINCT(a.p_karyawan_id) , m_bank_id,norek,m_lokasi_id,pajak_onoff,m_jabatan_id,periode_gajian,nik
			from p_karyawan a 
			join p_karyawan_pekerjaan c on a.p_karyawan_id = c.p_karyawan_id 
			where a.active=1  $where";

            $search_karyawan = DB::connection()->select($sql);
            foreach ($search_karyawan as $karyawan) {
                DB::connection()->table("prl_generate_karyawan")
                    ->insert([
                        "p_karyawan_id" => $karyawan->p_karyawan_id,
                        "prl_generate_id" => $id,
                        "m_bank_id" => $karyawan->m_bank_id,
                        "no_rek" => $karyawan->norek,
                        "lokasi_id" => $karyawan->m_lokasi_id,
                        "on_off" => $karyawan->pajak_onoff,
                        "jabatan_id" => $karyawan->m_jabatan_id,
                        "periode_gajian" => $karyawan->periode_gajian,
                        "nikgenerate" => $karyawan->nik,

                    ]);
            }
        $query = (DB::getQueryLog()); $help = new Helper_function();
		$help->historis(($_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']),json_encode($query));
            return redirect()->route('be.proses_generate', $id)->with('success', ' Generate Sedang di proses mohon tunggu sebentar!');
        } else {
            return redirect()->route('be.tambah_generate_gaji')->with('success', ' Generate Gagal Dibuat! Periode Generate sudah di Approve!!');
        }
        }
       
        
        
    }
    public function getkaryawanprosesgenerate(Request $request,$id)
    {
		DB::enableQueryLog();
		
		$gaji = DB::connection()->select("select prl_gaji_detail.p_karyawan_id,nama 
		from prl_gaji
		join prl_gaji_detail on prl_gaji.prl_gaji_id = prl_gaji_detail.prl_gaji_id 
		join p_karyawan on p_karyawan.p_karyawan_id = prl_gaji_detail.p_karyawan_id
		where prl_generate_id=$id and appr_on_direktur_status=1
		group by prl_gaji_detail.p_karyawan_id,nama");
		$karyawan_approve = array();
		foreach($gaji as $gaji){
			$karyawan_approve[] = $gaji->p_karyawan_id;
			$karyawan_approve_nama[] = $gaji->nama;
		}
			echo $request->entitas;
		//print_r($karyawan_approve_nama);
    	if($request->periode_gajian=='All' and $request->entitas=='All'){
    		echo '1';
    		echo '<br>';
    		$where 	="";
    		DB::connection()->table("prl_generate_karyawan")
    			
    			->whereNotIn('p_karyawan_id',$karyawan_approve)
    			
    			->where('prl_generate_id',$id)
                    ->update(["status"=>0]);
    		echo '<br>';
                    echo '11';
    	}else if($request->entitas!='All' and $request->periode_gajian=='All' ){
    		echo '2';
    		$where 	="and c.lokasi_id=".$request->entitas;
    		DB::connection()->table("prl_generate_karyawan")
    			->where('prl_generate_id',$id)
    			->whereNotIn('p_karyawan_id',$karyawan_approve)
    			
    			->where('lokasi_id',$request->entitas)
                    ->update(["status"=>0]);
            echo '22';
    	}else if($request->periode_gajian!='All' and $request->entitas=='All' ){
            echo '3';
    		$where 	="and c.periode_gajian=".$request->periode_gajian;
    		DB::connection()->table("prl_generate_karyawan")
    			->where('prl_generate_id',$id)
    			->whereNotIn('p_karyawan_id',$karyawan_approve)
    			
    			->where('periode_gajian',$request->periode_gajian)
                    ->update(["status"=>0]);
            echo '33';
    	}else{
            echo '4';
    		$where 	="and c.periode_gajian=".$request->periode_gajian;
    		$where 	.="and c.m_lokasi_id=".$request->entitas;
    		DB::connection()->table("prl_generate_karyawan")
    			->where('prl_generate_id',$id)
    			->whereNotIn('p_karyawan_id',$karyawan_approve)
    			->where('periode_gajian',$request->periode_gajian)
    			->where('lokasi_id',$request->entitas)
                   ->update(["status"=>0]);
            echo '5';
    	}
    	$query = (DB::getQueryLog()); $help = new Helper_function();
		$help->historis(($_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']),json_encode($query));
    }
    public function generate_gaji_sebelumnya(Request $request)
    {
    	$sql = "select DISTINCT(prl_gaji_detail.p_karyawan_id),prl_generate.prl_generate_id,prl_gaji.m_lokasi_id,pajak_onoff,m_jabatan_id,prl_generate.periode_gajian,norek,no_rek,p_karyawan_pekerjaan.m_bank_id, prl_generate_karyawan.m_bank_id as bank_id,prl_generate.prl_generate_id 
    	from prl_gaji_detail 
			join prl_gaji on prl_gaji.prl_gaji_id = prl_gaji_detail.prl_gaji_id

			join prl_generate on prl_gaji.prl_generate_id = prl_generate.prl_generate_id
			join p_karyawan_pekerjaan on prl_gaji_detail.p_karyawan_id = p_karyawan_pekerjaan.p_karyawan_id
			join prl_generate_karyawan on prl_gaji_detail.p_karyawan_id = prl_generate_karyawan.p_karyawan_id and prl_generate_karyawan.prl_generate_id = prl_generate.prl_generate_id
			where prl_generate.tahun =2023

			ORDER BY bank_id desc,prl_generate_id,m_lokasi_id";

        $generate = DB::connection()->select($sql);
        foreach($generate as $generate){
        	
        	$update = array();
        	
        	
        	$update["periode_gajian"] = $generate->periode_gajian;
        	//$update["kantor_id"] = $generate->m_office_id;
        	//$update["Jw"] = $generate->m_office_id;
        	
        	
        	DB::connection()->table("prl_generate_karyawan")
                   	->where('prl_generate_id',$generate->prl_generate_id)
                   	->where('p_karyawan_id',$generate->p_karyawan_id)
                    ->update($update);
        }
        
    }
    public function generate_gaji_sebelumnya2023(Request $request)
    {
    	$sql = "select DISTINCT(prl_gaji_detail.p_karyawan_id),prl_generate.prl_generate_id,prl_gaji.m_lokasi_id,pajak_onoff,m_jabatan_id,prl_generate.periode_gajian,norek,no_rek,p_karyawan_pekerjaan.m_bank_id, prl_generate_karyawan.m_bank_id as bank_id,prl_generate.prl_generate_id 
    	from prl_gaji_detail 
			join prl_gaji on prl_gaji.prl_gaji_id = prl_gaji_detail.prl_gaji_id

			join prl_generate on prl_gaji.prl_generate_id = prl_generate.prl_generate_id
			join p_karyawan_pekerjaan on prl_gaji_detail.p_karyawan_id = p_karyawan_pekerjaan.p_karyawan_id
			join prl_generate_karyawan on prl_gaji_detail.p_karyawan_id = prl_generate_karyawan.p_karyawan_id and prl_generate_karyawan.prl_generate_id = prl_generate.prl_generate_id
			where prl_generate.tahun =2023

			ORDER BY bank_id desc,prl_generate_id,m_lokasi_id";

        $generate = DB::connection()->select($sql);
        foreach($generate as $generate){
        	
        	$update = array();
        	
        	$update["periode_gajian"] = $generate->periode_gajian;
        	//$update["kantor_id"] = $generate->m_office_id;
        	//$update["Jw"] = $generate->m_office_id;
        	
        	
        	DB::connection()->table("prl_generate_karyawan")
                   	->where('prl_generate_id',$generate->prl_generate_id)
                   	->where('p_karyawan_id',$generate->p_karyawan_id)
                    ->update($update);
        }
        
    }
    public function cariPeriode(Request $request)
    {
        $tahun  = $request->get('tahun');
        $bulan  = $request->get('bulan');
        $periode_gajian  = $request->get('periode_gajian');
	        $help = new Helper_function();
        if($periode_gajian==2){
        	$sqlperiode = "SELECT m_periode_absen.*,
			case when type=0 then 'Pekanan' else 'Bulanan' end as tipe
			FROM m_periode_absen WHERE tahun='" . $tahun . "' and periode='" . $bulan . "'
			and m_periode_absen.periode_absen_id not in(select periode_absen_id from prl_generate where prl_generate.active=1)
			and m_periode_absen.periode_absen_id not in(select periode_lembur_id from prl_generate where prl_generate.active=1)
			ORDER BY tahun desc,periode desc,type";
	        $periode = DB::connection()->select($sqlperiode);
	        $periode_absen_pekanan = '';
	        $periode_lembur_pekanan = '';
	        $periode_absen_bulanan = '';
	        $periode_lembur_bulanan = '';
	        foreach ($periode  as $periode) {
	        	if($periode->type==1){
	        		$visible = true;
	        		$extend = '_bulanan';
	        	}else{
	        		if($request->pekanan_ke==$periode->pekanan_ke){
	        			
	        		$visible = true;
	        		}else{
	        			
	        		$visible = false;
	        		}
	        		
	        		$extend = '_pekanan';
	        	}
	        	if($visible){
	        		
	        	if ($periode->tipe_periode == 'absen'){
	        		$name = "periode_absen".$extend;
	                $$name .= '<option value="' . $periode->periode_absen_id . '">' . ucfirst($periode->tipe_periode) . ' | ' . $help->bulan($periode->periode) . ' - ' . $periode->tahun . ' - ' . $periode->tipe . ' - ' . $periode->tgl_awal . ' - ' . $periode->tgl_akhir . '</option>';
	        	}
	            else if ($periode->tipe_periode == 'lembur'){
	            	
	        		$name = "periode_lembur".$extend;
	                $$name .= '<option value="' . $periode->periode_absen_id . '">' . ucfirst($periode->tipe_periode) . ' | ' . $help->bulan($periode->periode) . ' - ' . $periode->tahun . ' - ' . $periode->tipe . ' - ' . $periode->tgl_awal . ' - ' . $periode->tgl_akhir . '</option>';
	            }
	        	
	        	}
	        	
	        	
	        	
	        	
			}
	        echo json_encode(array("periode_lembur_bulanan" => $periode_lembur_bulanan,"periode_absen_bulanan" => $periode_absen_bulanan,"periode_absen_pekanan" => $periode_absen_pekanan, "periode_lembur_pekanan" => $periode_lembur_pekanan));
        }else{
        	$where='';
        	if($periode_gajian==0){
        		$where = ' and pekanan_ke='.$request->pekanan_ke;
        	}
        	$generate_sudah_ada = DB::connection()->select("select * from prl_generate where prl_generate.active=1");
        	$periode_sudah ="-1";
        	foreach($generate_sudah_ada as $generate){
        	    if($generate->periode_absen_id)
        	    $periode_sudah .=','.$generate->periode_absen_id;
            	if($generate->periode_lembur_id)
            	$periode_sudah .=','.$generate->periode_lembur_id;
        	    
        	}
        	
        	
	        $sqlperiode = "SELECT m_periode_absen.*,
			case when type=0 then 'Pekanan' else 'Bulanan' end as tipe
			FROM m_periode_absen 
			WHERE tahun='" . $tahun . "' 
			    and periode='" . $bulan . "' 
			    and type = $periode_gajian $where
    			and m_periode_absen.periode_absen_id not in($periode_sudah)
			ORDER BY tahun desc,periode desc,type";
	        $periode = DB::connection()->select($sqlperiode);
	        
	        $periode_absen = '';
	        $periode_lembur = '';
	        foreach ($periode  as $periode) {
	            if ($periode->tipe_periode == 'absen')
	                $periode_absen .= '
                    <input type="hidden" id="generate_at-' . $periode->periode_absen_id . '" value="' . $periode->generate_at . '">
                    <input type="hidden" id="terakhir_generate_absen-' . $periode->periode_absen_id . '" value="' . $periode->terakhir_generate_absen . '">
                    <input type="hidden" id="status_generate_gaji-' . $periode->status_generate_gaji . '" value="' . $periode->status_generate_gaji . '">
                    <option value="' . $periode->periode_absen_id . '">' . ucfirst($periode->tipe_periode) . ' | ' . $help->bulan($periode->periode) . ' - ' . $periode->tahun . ' - ' . $periode->tipe . ' - ' . $periode->tgl_awal . ' - ' . $periode->tgl_akhir . '</option>';
	            else if ($periode->tipe_periode == 'lembur')
	                $periode_lembur .= '
                    <input type="hidden" id="generate_at-' . $periode->periode_absen_id . '" value="' . $periode->generate_at . '">
                    <input type="hidden" id="terakhir_generate_absen-' . $periode->periode_absen_id . '" value="' . $periode->terakhir_generate_absen . '">
                    <input type="hidden" id="status_generate_gaji-' . $periode->status_generate_gaji . '" value="' . $periode->status_generate_gaji . '">
                    
                    <option value="' . $periode->periode_absen_id . '">' . ucfirst($periode->tipe_periode) . ' | ' . $help->bulan($periode->periode) . ' - ' . $periode->tahun . ' - ' . $periode->tipe . ' - ' . $periode->tgl_awal . ' - ' . $periode->tgl_akhir . '</option>';
	        }

	        echo json_encode(array("periode_absen" => $periode_absen, "periode_lembur" => $periode_lembur));
        }
    }
    public function hitung_absen(Request $request,$id)
    {
        $help = New Helper_function();
        $where_generate = "";
        if($request->entitas!='All'){
        	$where_generate .= " and a.lokasi_id=".$request->entitas;
        	
        }
        if($request->periode_gajian!='All'){
        	$where_generate .= " and a.periode_gajian=".$request->periode_gajian;
        	
        }
        $sqluser = "SELECT * FROM prl_generate_karyawan a 
        join prl_generate b on b.prl_generate_id = a.prl_generate_id
        left join m_jabatan on jabatan_id = m_jabatan.m_jabatan_id 
        where a.prl_generate_id = $id and status = 0 $where_generate 
        
        order by b.prl_generate_id asc ";
        $generate = DB::connection()->select($sqluser);

        $info_generate = DB::connection()->select("select * from prl_generate where prl_generate_id = $id");

		if($info_generate[0]->periode_gajian==2){
			$periode_absen = $info_generate[0]->periode_absen_bulanan_id;
        	$periode_lembur = $info_generate[0]->periode_lembur_bulanan_id;
					
		}else{
			
       	 	$periode_absen = $info_generate[0]->periode_absen_id;
        	$periode_lembur = $info_generate[0]->periode_lembur_id;
		}
        $sqlperiode = "SELECT * FROM m_periode_absen where periode_absen_id=$periode_absen";
        $periodetgl = DB::connection()->select($sqlperiode);
        $type = $periodetgl[0]->type;
        $where = " d.periode_gajian = " . $type;
        $appendwhere = "and";

        $periode_gajian = $periodetgl[0]->type;
        $tgl_awal = date('Y-m-d', strtotime($periodetgl[0]->tgl_awal));
        $tgl_akhir = date('Y-m-d', strtotime($periodetgl[0]->tgl_akhir));

        $sqlperiode = "SELECT * FROM m_periode_absen where periode_absen_id=$periode_lembur";
        $periodetgl_lembur = DB::connection()->select($sqlperiode);
        $tgl_awal_lembur = date('Y-m-d', strtotime($periodetgl_lembur[0]->tgl_awal));
        $tgl_akhir_lembur = date('Y-m-d', strtotime($periodetgl_lembur[0]->tgl_akhir)); 
        
        $gen_karyawan=array();
        foreach ($generate as $g) {
            $gen_karyawan[] = $g->p_karyawan_id;
        }
        print_r($gen_karyawan);
        $date = $tgl_awal;
        for($i=0;$i<$help->hitungHari($tgl_awal,$tgl_akhir);$i++){
            $tanggal=$date;
            DB::connection()->table("absen_master_generate_tanggal")->where("tanggal",$tanggal)->update(["active"=>0]);
            DB::connection()->table("absen_master_rekap")->where('periode_absen_id',$periode_absen)->where("tanggal",$tanggal)->update(["active"=>0]);
	        
	           // $date = $periode[0]->tgl_awal;
    	       // for($i=0;$i<$help->hitungHari($periode[0]->tgl_awal,$periode[0]->tgl_akhir);$i++){
    	            
    	            $data_tanggal['tanggal'] = $tanggal;
    	            $data_tanggal['status'] = 0;
    	            $data_tanggal['active'] = 1;
    	            $data_tanggal['periode_absen_id'] = $periode_absen;
    	            $data_tanggal['create_date'] = date('Y-m-d H:i:s');
    	            DB::connection()->table("absen_master_generate_tanggal")->insert($data_tanggal);
            $help->generate_rekap_absen_tanggal($request,$date,$gen_karyawan,null,$periode_absen);
            $date = $help->tambah_tanggal($date,1);
        }

        
        $date = $tgl_awal_lembur;
        for($i=0;$i<$help->hitungHari($tgl_awal_lembur,$tgl_akhir_lembur);$i++){
            $tanggal=$date;
            DB::connection()->table("absen_master_generate_tanggal")->where("tanggal",$tanggal)->update(["active"=>0]);
            DB::connection()->table("absen_master_rekap")->where('periode_absen_id',$periode_lembur)->where("tanggal",$tanggal)->update(["active"=>0]);
	        
	           // $date = $periode[0]->tgl_awal;
    	       // for($i=0;$i<$help->hitungHari($periode[0]->tgl_awal,$periode[0]->tgl_akhir);$i++){
    	            
    	            $data_tanggal['tanggal'] = $tanggal;
    	            $data_tanggal['status'] = 0;
    	            $data_tanggal['active'] = 1;
    	            $data_tanggal['periode_absen_id'] = $periode_lembur;
    	            $data_tanggal['create_date'] = date('Y-m-d H:i:s');
    	            DB::connection()->table("absen_master_generate_tanggal")->insert($data_tanggal);
            $help->generate_rekap_absen_tanggal($request,$date,$gen_karyawan,null,$periode_lembur);
            $date = $help->tambah_tanggal($date,1);
        }

    }
    public function hitung(Request $request,$id)
    {
        $help = new Helper_function();
        $where = "";
        if($request->entitas!='All'){
        	$where .= " and a.lokasi_id=".$request->entitas;
        	
        }
        if($request->periode_gajian!='All'){
        	$where .= " and a.periode_gajian=".$request->periode_gajian;
        	
        }
        $sqluser = "SELECT *,a.periode_gajian as periode_generate  
        FROM prl_generate_karyawan a
        join prl_generate b on b.prl_generate_id = a.prl_generate_id 
                    where a.prl_generate_id = $id and status = 0  $where 
                    order by b.prl_generate_id asc limit 1";
        
        $generate = DB::connection()->select($sqluser);
        if(count($generate)){
        	
		if($generate[0]->periode_generate==1){
			$this->hitungbulanan_optimasi($request,$id,$where);
		}else if($generate[0]->periode_generate==0){
			$this->hitung_pekanan($request,$id,$where);
		} 
        }else{
        	echo '<div class="card"><div class="card-body text-center"><h3>Tidak ada data karyawan yang dapat di generate</h3>hal ini dimungkinkan beberapa entitas sudah terapprove data dari pihak direksi</div></div>';
        }

        
    }
    public function hitungbulanan_optimasi($request,$id,$where_generate)
    {
        $help = new Helper_function();
        $sqluser = "SELECT * FROM prl_generate_karyawan a 
        join prl_generate b on b.prl_generate_id = a.prl_generate_id
        left join m_jabatan on jabatan_id = m_jabatan.m_jabatan_id 
        where a.prl_generate_id = $id and status = 0 $where_generate 
        
        order by b.prl_generate_id asc limit 1";
        $generate = DB::connection()->select($sqluser);


        $info_generate = DB::connection()->select("select * from prl_generate where prl_generate_id = $id");

		if($info_generate[0]->periode_gajian==2){
			$periode_absen = $info_generate[0]->periode_absen_bulanan_id;
        	$periode_lembur = $info_generate[0]->periode_lembur_bulanan_id;
					
		}else{
			
       	 	$periode_absen = $info_generate[0]->periode_absen_id;
        	$periode_lembur = $info_generate[0]->periode_lembur_id;
		}

        $sqlperiode = "SELECT * FROM m_periode_absen where periode_absen_id=$periode_absen";
        $periodetgl = DB::connection()->select($sqlperiode);
        $type = $periodetgl[0]->type;
        $where = " d.periode_gajian = " . $type;
        $appendwhere = "and";

        $periode_gajian = $periodetgl[0]->type;
        $tgl_awal = date('Y-m-d', strtotime($periodetgl[0]->tgl_awal));
        $tgl_akhir = date('Y-m-d', strtotime($periodetgl[0]->tgl_akhir));

        $sqlperiode = "SELECT * FROM m_periode_absen where periode_absen_id=$periode_lembur";
        $periodetgl_lembur = DB::connection()->select($sqlperiode);
        $tgl_awal_lembur = date('Y-m-d', strtotime($periodetgl_lembur[0]->tgl_awal));
        $tgl_akhir_lembur = date('Y-m-d', strtotime($periodetgl_lembur[0]->tgl_akhir)); 
        
        $gen_karyawan=array();
        foreach ($generate as $g) {
            $gen_karyawan[] = $g->p_karyawan_id;
        }
        

        foreach ($generate as $g) {
            $id = $g->p_karyawan_id;
            $rekap = $help->rekap_absen_optimasi($periode_absen,$tgl_awal, $tgl_akhir, $type,null,$id);
            $rekap_lembur = $help->rekap_absen_optimasi($periode_lembur,$tgl_awal_lembur, $tgl_akhir_lembur, $type,null,$id);
             $hari_libur = $rekap['hari_libur'];
       		 $hari_libur_shift = $rekap['hari_libur_shift'];
            //////echo $id;
            $sql = 'SELECT c.p_karyawan_id,c.nama as nama_lengkap,c.nik,f.m_pangkat_id,i.nama as nm_pangkat ,f.nama as nmjabatan,AGE(CURRENT_DATE, c.tgl_bergabung)::VARCHAR AS umur
			FROM p_karyawan c 
			LEFT JOIN p_karyawan_pekerjaan d on d.p_karyawan_id=c.p_karyawan_id  
			LEFT JOIN m_jabatan f on d.m_jabatan_id=f.m_jabatan_id 
			LEFT JOIN m_pangkat i on f.m_pangkat_id=i.m_pangkat_id 
			where c.p_karyawan_id = ' . $id;
            $detail_karyawan = DB::connection()->select($sql);
            $detail_karyawan = $detail_karyawan[0];

            $return = $help->total_rekap_absen_optimasi($rekap, $id);
            $return_lembur = $help->total_rekap_absen_optimasi($rekap_lembur, $id);
           

            $masuk                 = $return['total']['masuk'];
            $cuti                 = $return['total']['cuti'];
            $ipg                 = $return['total']['ipg'];
            $izin                 = $return['total']['izin'];
            $ipd                 = $return['total']['ipd'];
            $ipc                 = $return['total']['ipc'];
            $idt                 = $return['total']['idt'];
            $ipm                = $return['total']['ipm'];
            $pm                 = $return['total']['pm'];
            $sakit                 = $return['total']['sakit'];
            $alpha                 = $return['total']['alpha'];
            $terlambat             = $return['total']['terlambat'];
            $tabsen             = $return['total']['Total Absen'];
            $tmasuk             = $return['total']['Total Masuk'];
            $tkerja             = $return['total']['Total Hari Kerja'];
            $fingerprint         = $return['total']['fingerprint'];
            $total_all            = $return_lembur['total']['total_all'];
            $total['<8 jam']     = $return_lembur['total']['<8 jam'];
            $total['8 jam']        = $return_lembur['total']['8 jam'];
            $total['9 jam']        = $return_lembur['total']['9 jam'];
            $total['>=10 jam']     = $return_lembur['total']['>=10 jam'];
            $total['total_lembur_proposional']     = $return_lembur['total_lembur']['total_lembur_proposional'];


            $total['1jam']         = $return_lembur['total']['1jam'];
            $total['>=2jam']     = $return_lembur['total']['>=2jam'];
            $total['SUM Libur'] = $return_lembur['total']['SUM Libur'];
            $total['COUNT Libur'] = $return_lembur['total']['COUNT Libur'];
            $total['COUNT Kerja'] = $return_lembur['total']['COUNT Kerja'];
            $total['SUM Kerja'] = $return_lembur['total']['SUM Kerja'];



            $karyawan = "select a.nama as nama,c.nama as nama_jabatan, d.nama as nama_pangkat
			from p_karyawan a 
			join p_karyawan_pekerjaan b on a.p_karyawan_id = b.p_karyawan_id
			join m_jabatan c on c.m_jabatan_id = b.m_jabatan_id
			join m_pangkat d on c.m_pangkat_id = d.m_pangkat_id
			where a.p_karyawan_id = $id";
            $karyawan = DB::connection()->select($karyawan);


            $id_prl = $this->prl_gaji($g->prl_generate_id, $g->p_karyawan_id);
           
            $this->prl_gaji_detail($id_prl, $g->p_karyawan_id, 1, 1, ($masuk + $cuti + $ipg + $izin + $ipd + $alpha));
           
            $this->prl_gaji_detail($id_prl, $g->p_karyawan_id, 1, 2, ($masuk + $ipd));
            
            $this->prl_gaji_detail($id_prl, $g->p_karyawan_id, 1, 3, ($sakit));
           
            $this->prl_gaji_detail($id_prl, $g->p_karyawan_id, 1, 4, ($cuti));
            
            $this->prl_gaji_detail($id_prl, $g->p_karyawan_id, 1, 5, ($ipd));
          
            $this->prl_gaji_detail($id_prl, $g->p_karyawan_id, 1, 6, ($izin));
          
            $this->prl_gaji_detail($id_prl, $g->p_karyawan_id, 1, 7, ($ipg));
           
          
            $this->prl_gaji_detail($id_prl, $g->p_karyawan_id, 1, 9, ($total_all));
            
            $this->prl_gaji_detail($id_prl, $g->p_karyawan_id, 1, 10, ($total['1jam']));
            
            $this->prl_gaji_detail($id_prl, $g->p_karyawan_id, 1, 11, ($total['>=2jam']));
           
            $this->prl_gaji_detail($id_prl, $g->p_karyawan_id, 1, 12, ($total['8 jam']));
            
            $this->prl_gaji_detail($id_prl, $g->p_karyawan_id, 1, 13, ($total['9 jam']));
           
            $this->prl_gaji_detail($id_prl, $g->p_karyawan_id, 1, 14, ($total['>=10 jam']));
            $this->prl_gaji_detail($id_prl, $g->p_karyawan_id, 1, 22, ($total['SUM Libur']));
            $this->prl_gaji_detail($id_prl, $g->p_karyawan_id, 1, 21, ($total['COUNT Libur']));
            $this->prl_gaji_detail($id_prl, $g->p_karyawan_id, 1, 19, ($total['COUNT Kerja']));
            $this->prl_gaji_detail($id_prl, $g->p_karyawan_id, 1, 20, ($total['SUM Kerja']));
            $this->prl_gaji_detail($id_prl, $g->p_karyawan_id, 1, 31, ($return['total']['IPGCuti']));
            $this->prl_gaji_detail($id_prl, $g->p_karyawan_id, 1, 32, 0,($return['total']['IPGCutiList']));
            $this->prl_gaji_detail($id_prl, $g->p_karyawan_id, 1, 37, ($total['total_lembur_proposional']));
            
            foreach($return['string'] as $string => $value){
            	$this->prl_gaji_detail($id_prl, $g->p_karyawan_id, 1, 35, $string,($value));
            	
            }


            $tunjangan = "select *
			from m_tunjangan a 
			join prl_tunjangan b on a.m_tunjangan_id = b.m_tunjangan_id
			where b.p_karyawan_id = $id and b.active=1 and b.m_tunjangan_id not in(16,14)";
            //////echo $potongan;
            $tunjangan = DB::connection()->select($tunjangan);
            $gapok = 0;
            //print_r($tunjanga)
            foreach ($tunjangan as $tunjangan) {
               // echo ''.$tunjangan->nama;
                $this->prl_gaji_detail($id_prl, $g->p_karyawan_id, 2, $tunjangan->prl_tunjangan_id, ($tunjangan->nominal));
                if ($tunjangan->is_tunjangan_gapok) $gapok += $tunjangan->nominal;
                //echo $tunjangan->is_tunjangan_gapok;
               
            }

            $lembur_kerja = ($total['1jam'] * (1.5 / 173) * $gapok) + ($total['>=2jam'] * (2 / 173) * $gapok);
            $lembur_libur = (($total['8 jam'] * (2 / 173) * $gapok) + (($total['9 jam'] * (3 / 173) * $gapok) + (($total['>=10 jam'] * (4 / 173) * $gapok))));
            
            $lembur_proposional = ($gapok / 22) * $total['total_lembur_proposional'];
           
            $this->prl_gaji_detail($id_prl, $g->p_karyawan_id, 1, 17, ($lembur_kerja));
            $this->prl_gaji_detail($id_prl, $g->p_karyawan_id, 1, 18, ($lembur_libur));
            $this->prl_gaji_detail($id_prl, $g->p_karyawan_id, 1, 36, ($lembur_proposional));
            $lembur = $lembur_kerja + $lembur_libur+$lembur_proposional;
            $this->prl_gaji_detail($id_prl, $g->p_karyawan_id, 3, 15, ($lembur));

           
            $id_pangkat = $detail_karyawan->m_pangkat_id;
          

            $sql = "SELECT 
            	(select nominal from m_potongan_absen a where a.m_pangkat_id = $id_pangkat and type_absen='absen') as nominal_absen,
            	(select nominal from m_potongan_absen b where b.m_pangkat_id = $id_pangkat and type_absen='izin') as nominal_izin,
            	(select nominal from m_potongan_absen c where c.m_pangkat_id = $id_pangkat and type_absen='alpha') as nominal_alpha,
            	(select nominal from m_potongan_absen d where d.m_pangkat_id = $id_pangkat and type_absen='fingerprint') as nominal_fingerprint,
            	(select nominal from m_potongan_absen e where e.m_pangkat_id = $id_pangkat and type_absen='pm') as nominal_pm,
            	
            	(select type_nominal from m_potongan_absen f where f.m_pangkat_id = $id_pangkat and type_absen='absen') as type_nominal_absen,
            	(select type_nominal from m_potongan_absen g where g.m_pangkat_id = $id_pangkat and type_absen='izin') as type_nominal_izin,
            	(select type_nominal from m_potongan_absen h where h.m_pangkat_id = $id_pangkat and type_absen='alpha') as type_nominal_alpha,
            	(select type_nominal from m_potongan_absen i where i.m_pangkat_id = $id_pangkat and type_absen='fingerprint') as type_nominal_fingerprint,
            	(select type_nominal from m_potongan_absen j where j.m_pangkat_id = $id_pangkat and type_absen='pm') as type_nominal_pm
            	
            	
            	 ";
            $potongan_absen = DB::connection()->select($sql);
            //print_r($id_pangkat);
            //echo $g->p_karyawan_id;
            if ($potongan_absen[0]->type_nominal_absen == 1) {
                $potabsen = $potongan_absen[0]->nominal_absen * $terlambat;
            } else if ($potongan_absen[0]->type_nominal_absen == 2) {
                $potabsen = ($potongan_absen[0]->nominal_absen / 100 * $gapok) * $terlambat;
            } else if ($potongan_absen[0]->type_nominal_absen == 3) {
                $potabsen = ($gapok / 22) * $terlambat;
            }
            if ($potongan_absen[0]->type_nominal_pm == 1) {
                $potmendahului = $potongan_absen[0]->nominal_pm * $pm;
            } else if ($potongan_absen[0]->type_nominal_pm == 2) {
                $potmendahului = ($potongan_absen[0]->nominal_pm / 100 * $gapok) * $pm;
            } else if ($potongan_absen[0]->type_nominal_pm == 3) {
                $potmendahului = ($gapok / 22) * $pm;
            }

            if ($potongan_absen[0]->type_nominal_izin == 1) {
                $potizin = $potongan_absen[0]->nominal_izin * $ipg;
            } else if ($potongan_absen[0]->type_nominal_izin == 2) {
                $potizin = ($potongan_absen[0]->nominal_izin / 100 * $gapok) * $ipg;
            } else if ($potongan_absen[0]->type_nominal_izin == 3) {
                $potizin = ($gapok / 22) * $ipg;
            }

            if ($potongan_absen[0]->type_nominal_alpha == 1) {
                $potalpha = $potongan_absen[0]->nominal_alpha * $alpha;
            } else if ($potongan_absen[0]->type_nominal_alpha == 2) {
                $potalpha = ($potongan_absen[0]->nominal_alpha / 100 * $gapok) * $alpha;
            } else if ($potongan_absen[0]->type_nominal_alpha == 3) {
                $potalpha = ($gapok / 22) * $alpha;
            }
            if ($potongan_absen[0]->type_nominal_fingerprint == 1) {
                $potfingerprint = $potongan_absen[0]->nominal_fingerprint * $fingerprint;
            } else if ($potongan_absen[0]->type_nominal_fingerprint == 2) {
                $potfingerprint = ($potongan_absen[0]->nominal_fingerprint / 100 * $gapok) * $fingerprint;
            } else if ($potongan_absen[0]->type_nominal_fingerprint == 3) {
                $potfingerprint = ($gapok / 22) * $fingerprint;
            }

//echo $gapok;

            $this->prl_gaji_detail($id_prl, $g->p_karyawan_id, 1, 26, ($idt));
            $this->prl_gaji_detail($id_prl, $g->p_karyawan_id, 1, 27, ($ipm));
            $this->prl_gaji_detail($id_prl, $g->p_karyawan_id, 1, 28, ($ipc));
            $this->prl_gaji_detail($id_prl, $g->p_karyawan_id, 1, 24, ($fingerprint));
             if ($g->m_pangkat_id == 5 or $g->m_pangkat_id == 6  ) {
             } else if (($g->lokasi_id==5)) {
            
            } else 
            if ((($g->p_karyawan_id == 269 or $g->p_karyawan_id == 1 or $g->p_karyawan_id == 270) ) or ($g->lokasi_id==5) or  ($g->lokasi_id==28) ){
                
            }else{
                $this->prl_gaji_detail($id_prl, $g->p_karyawan_id, 5, 12, ($potabsen));
                $this->prl_gaji_detail($id_prl, $g->p_karyawan_id, 1, 23, ($terlambat));
                $this->prl_gaji_detail($id_prl, $g->p_karyawan_id, 1, 25, ($pm));
                $this->prl_gaji_detail($id_prl, $g->p_karyawan_id, 5, 24, ($potmendahului));
                
            }
             if (($g->lokasi_id==7) and ($g->p_karyawan_id != 130)) {
             }else{
                $this->prl_gaji_detail($id_prl, $g->p_karyawan_id, 1, 8, ($alpha));
                $this->prl_gaji_detail($id_prl, $g->p_karyawan_id, 5, 13, ($potalpha));
             }
            $this->prl_gaji_detail($id_prl, $g->p_karyawan_id, 5, 22, ($potizin));
            $this->prl_gaji_detail($id_prl, $g->p_karyawan_id, 5, 23, ($potfingerprint));


          

            $potongan = "select *
			from m_potongan a 
			right join prl_potongan b on a.m_potongan_id = b.m_potongan_id
			where b.p_karyawan_id = $id and b.active=1 and a.m_potongan_id not in (20,21,9,16,17,16,19,18)";
            //////echo $potongan;
            $potongan = DB::connection()->select($potongan);
            foreach ($potongan as $potongan) {
                $this->prl_gaji_detail($id_prl, $g->p_karyawan_id, 4, $potongan->prl_potongan_id, ($potongan->nominal));
                
            }
            
            //tambahin dari p_karyawan Gapok
           
            $Now = date('Y-m-d');
            $potongan = "select *
			from p_karyawan_koperasi a 
			where a.p_karyawan_id = $id and  tgl_akhir>='$tgl_awal' and a.active=1";
           
            $potongan = DB::connection()->select($potongan);
            foreach ($potongan as $potongan) {
            	$jenis = 5;
                if ($potongan->nama_koperasi == 'ASA') {
                    $type = 21;
                } else  if ($potongan->nama_koperasi == 'KKB') {
                    $type = 9;
                } else if ($potongan->nama_koperasi == 'SEWA KOST') {
                    $type = 16;
                }else if ($potongan->nama_koperasi == 'PAJAK') {
                    $type = 20;
                }else if ($potongan->nama_koperasi == 'ZAKAT') {
                    $type = 18;
                }else if ($potongan->nama_koperasi == 'INFAQ') {
                    $type = 19;
                }else if ($potongan->nama_koperasi == 'BONUS') {
                    $type = 20;
            		$jenis = 3;
                }else if ($potongan->nama_koperasi == 'TUNJANGAN KOST') {
                    $type = 14;
            		$jenis = 3;
                }
                $this->prl_gaji_detail($id_prl, $g->p_karyawan_id, $jenis, $type, ($potongan->nominal));
               
            }

          
            DB::connection()->table("prl_generate_karyawan")
                ->where("prl_generate_karyawan_id", $g->prl_generate_karyawan_id)
                ->update([
                    "status" => 1,

                ]);
        }
       $time = $request->get('time');
         $time = $time?$time:5000;
         $time = $time/1000;
        $id_generate = $g->prl_generate_id;
        $sqlgenerate = "SELECT count(*) as jumlah_semua_karyawan,count(CASE WHEN status = 1 THEN 1 END)  as yang_sudah FROM prl_generate_karyawan a where prl_generate_id = $id_generate $where_generate";
        //////echo $sqluser;
        $generete = DB::connection()->select($sqlgenerate);
        $persen = round($generete[0]->yang_sudah / $generete[0]->jumlah_semua_karyawan * 100, 2);
        $init = ($generete[0]->jumlah_semua_karyawan - $generete[0]->yang_sudah) * $time;
        $hours = floor($init / 3600);
        $minutes = floor(($init / 60) % 60);
        $seconds = $init % 60;

        $Jam = $hours ? $hours . ' Jam ' : '';
        $menit = $minutes ? $minutes . ' Menit ' : '';
        $sekon = $seconds ? $seconds . ' Detik ' : '';
        //echo "$hours:$minutes:$seconds";
        echo '<div class="card">
										<div class="card-body text-center">
											<br>
											<h3>' . $persen . '%</h3>
											<h4 class="holiday-title mb-0">' . $generete[0]->yang_sudah . ' dari ' . $generete[0]->jumlah_semua_karyawan . '</h4>
											<div>' . $Jam . $menit . $sekon . ' </div>
											<div class="text-red" style="color:red;">PERINGATAN!!! TAB JANGAN DI TUTUP SEBELUM SELESAI</div>
										</div>
									</div>
									<input type="hidden" value="'.$persen.'" id="generate">
									';
    }
    
    public function hitung_pekanan($request,$id,$where_generate)
    {
        $help = new Helper_function();
        $sqluser = "SELECT * FROM prl_generate_karyawan a join prl_generate b on b.prl_generate_id = a.prl_generate_id where a.prl_generate_id = $id and status !=1 $where_generate order by b.prl_generate_id asc limit 1";
        $generate = DB::connection()->select($sqluser);
        $id_generate = $id;

        $info_generate = DB::connection()->select("select * from prl_generate where prl_generate_id = $id");

		if($info_generate[0]->periode_gajian==2){
			$periode_absen = $info_generate[0]->periode_absen_pekanan_id;
	        $periode_lembur = $info_generate[0]->periode_lembur_pekanan_id;
					
		}else{
			
        $periode_absen = $info_generate[0]->periode_absen_id;
        $periode_lembur = $info_generate[0]->periode_lembur_id;
		}
        $sqlperiode = "SELECT * FROM m_periode_absen where periode_absen_id=$periode_absen";
        $periodetgl = DB::connection()->select($sqlperiode);
        $type = $periodetgl[0]->type;
        $where = " d.periode_gajian = " . $type;
        $appendwhere = "and";

        $periode_gajian = $periodetgl[0]->type;
        $tgl_awal = date('Y-m-d', strtotime($periodetgl[0]->tgl_awal));
        $tgl_akhir = date('Y-m-d', strtotime($periodetgl[0]->tgl_akhir));

        $sqlperiode = "SELECT * FROM m_periode_absen where periode_absen_id=$periode_lembur";
        $periodetgl_lembur = DB::connection()->select($sqlperiode);
        $tgl_awal_lembur = date('Y-m-d', strtotime($periodetgl_lembur[0]->tgl_awal));
        $tgl_akhir_lembur = date('Y-m-d', strtotime($periodetgl_lembur[0]->tgl_akhir));


       

        foreach ($generate as $g) {
            
            $id = $g->p_karyawan_id;
            
             $rekap = $help->rekap_absen($tgl_awal, $tgl_akhir, $tgl_awal_lembur, $tgl_akhir_lembur, $type,null,$id);

        $hari_libur = $rekap['hari_libur'];
        $hari_libur_shift = $rekap['hari_libur_shift'];
            //////echo $id;
            $sql = 'SELECT c.p_karyawan_id,c.nama as nama_lengkap,c.nik,f.m_pangkat_id,i.nama as nm_pangkat ,f.nama as nmjabatan,AGE(CURRENT_DATE, c.tgl_bergabung)::VARCHAR AS umur
			FROM p_karyawan c 
			LEFT JOIN p_karyawan_pekerjaan d on d.p_karyawan_id=c.p_karyawan_id  
			LEFT JOIN m_jabatan f on d.m_jabatan_id=f.m_jabatan_id 
			LEFT JOIN m_pangkat i on f.m_pangkat_id=i.m_pangkat_id 
			where c.p_karyawan_id = ' . $id;
            $detail_karyawan = DB::connection()->select($sql);
            $detail_karyawan = $detail_karyawan[0];


            $return = $help->total_rekap_absen($rekap, $id);


            $masuk                 = $return['total']['masuk'];
            $cuti                 = $return['total']['cuti'];
            $ipg                 = $return['total']['ipg'];
            $izin                 = $return['total']['izin'];
            $pm                 = $return['total']['pm'];
            $ipm                 = $return['total']['ipm'];
            $idt                 = $return['total']['idt'];
            $ipd                 = $return['total']['ipd'];
            $ipc                 = $return['total']['ipc'];
            $sakit                 = $return['total']['sakit'];
            $alpha                 = $return['total']['alpha'];
            $terlambat             = $return['total']['terlambat'];
            $tabsen             = $return['total']['Total Absen'];
            $tmasuk             = $return['total']['Total Masuk'];
            $tkerja             = $return['total']['Total Hari Kerja'];
            $fingerprint         = $return['total']['fingerprint'];
            $total_all            = $return['total']['total_all'];
            $total['<8 jam']     = $return['total']['<8 jam'];
            $total['8 jam']        = $return['total']['8 jam'];
            $total['9 jam']        = $return['total']['9 jam'];
            $total['>=10 jam']     = $return['total']['>=10 jam'];
            
            $total['total_lembur_proposional']     = $return['total_lembur']['total_lembur_proposional'];


            $total['1jam']         = $return['total']['1jam'];
            $total['>=2jam']     = $return['total']['>=2jam'];
            $total['SUM Libur'] = $return['total']['SUM Libur'];
            $total['COUNT Libur'] = $return['total']['COUNT Libur'];
            $total['COUNT Kerja'] = $return['total']['COUNT Kerja'];
            $total['SUM Kerja'] = $return['total']['SUM Kerja'];
            $total['SUM Kerja'] = $return['total']['SUM Kerja'];


            $karyawan = "select a.nama as nama,c.nama as nama_jabatan, d.nama as nama_pangkat
			from p_karyawan a 
			join p_karyawan_pekerjaan b on a.p_karyawan_id = b.p_karyawan_id
			join m_jabatan c on c.m_jabatan_id = b.m_jabatan_id
			join m_pangkat d on c.m_pangkat_id = d.m_pangkat_id
			where a.p_karyawan_id = $id";
            $karyawan = DB::connection()->select($karyawan);


            $id_prl = $this->prl_gaji($g->prl_generate_id, $g->p_karyawan_id);
            //	$this->prl_gaji_detail($id_prl,1,1,($masuk+$cuti+$ipg+$izin+$ipd+$alpha));

            ////echo '<div class="card">';
            ////echo '<div class="card-body">';
            //echo '<h3>';
            //echo $karyawan[0]->nama;
            //echo '</h3>';
            //echo $karyawan[0]->nama_jabatan;
            //echo '<br>';
            //echo $karyawan[0]->nama_pangkat;

            //////echo $id_prl;

            //echo '<div class="row">';
            //echo '<div class="col-md-4">';
            //echo '<strong>Hari Kerja: </strong>';
            //echo ($masuk+$cuti+$ipg+$izin+$ipd+$alpha);
            //echo '<br>';
            $this->prl_gaji_detail($id_prl, $g->p_karyawan_id, 1, 1, ($masuk + $cuti + $ipg + $izin + $ipd + $alpha));
            //echo '<strong>Hari Absen: </strong>';
            //echo ($masuk+$ipd);
            $this->prl_gaji_detail($id_prl, $g->p_karyawan_id, 1, 2, ($masuk + $ipd));
            //$this->prl_gaji_detail($id_prl,$g->p_karyawan_id,1,2,($masuk+$ipd));
            //echo '<br>';
            //echo '<strong>Sakit: </strong>'.$sakit;
            $this->prl_gaji_detail($id_prl, $g->p_karyawan_id, 1, 3, ($sakit));
            //echo '<br>';
            //echo '<strong>Cuti: </strong>'.$cuti;
            $this->prl_gaji_detail($id_prl, $g->p_karyawan_id, 1, 4, ($cuti));
            //echo '<br>';
            //echo '<strong>Izin Perjalanan Dinas: </strong>'.$ipd;
            $this->prl_gaji_detail($id_prl, $g->p_karyawan_id, 1, 5, ($ipd));
            //echo '<br>';
            //echo '<strong>Izin Hitung Kerja: </strong>'.$izin;
            $this->prl_gaji_detail($id_prl, $g->p_karyawan_id, 1, 6, ($izin));
            //echo '<br>';
            //echo '<strong>Izin Potongan Tanpa Keterangan: </strong>'.$ipg;
            $this->prl_gaji_detail($id_prl, $g->p_karyawan_id, 1, 7, ($ipg));
            //echo '<br>';
            //echo '<strong>Tanpa Keterangan: </strong>'.$alpha;
            $this->prl_gaji_detail($id_prl, $g->p_karyawan_id, 1, 8, ($alpha));
            ////echo '<strong>Tanpa Keterangan: </strong>'.$alphaList;
            //echo '<br>';
            //echo '<strong>Jam Lembur: </strong>'.$total_all;
            $this->prl_gaji_detail($id_prl, $g->p_karyawan_id, 1, 9, ($total_all));
            ////echo '<br>';
            ////echo '<strong>Lembur 1 Jam: </strong>'.$total['1jam'];
            $this->prl_gaji_detail($id_prl, $g->p_karyawan_id, 1, 10, ($total['1jam']));
            ////echo '<br>';
            ////echo '<strong>Lembur >2 Jam: </strong>'.$total['>=2jam'];
            $this->prl_gaji_detail($id_prl, $g->p_karyawan_id, 1, 11, ($total['>=2jam']));
            ////echo '<br>';
            ////echo '<strong>Lembur 8 Jam: </strong>'.$total['8 jam'];
            $this->prl_gaji_detail($id_prl, $g->p_karyawan_id, 1, 12, ($total['8 jam']));
            ////echo '<br>';
            ////echo '<strong>Lembur 9 Jam: </strong>'.$total['9 jam'];
            $this->prl_gaji_detail($id_prl, $g->p_karyawan_id, 1, 13, ($total['9 jam']));
            ////echo '<br>';
            ////echo '<strong>Lembur 10 Jam: </strong>'.$total['>=10 jam'];
            $this->prl_gaji_detail($id_prl, $g->p_karyawan_id, 1, 14, ($total['>=10 jam']));
            $this->prl_gaji_detail($id_prl, $g->p_karyawan_id, 1, 22, ($total['SUM Libur']));
            $this->prl_gaji_detail($id_prl, $g->p_karyawan_id, 1, 21, ($total['COUNT Libur']));
            $this->prl_gaji_detail($id_prl, $g->p_karyawan_id, 1, 19, ($total['COUNT Kerja']));
            $this->prl_gaji_detail($id_prl, $g->p_karyawan_id, 1, 20, ($total['SUM Kerja']));
			foreach($return['string'] as $string => $value){
            	$this->prl_gaji_detail($id_prl, $g->p_karyawan_id, 1, 35, $string,($value));
            }



            ////echo '<br>';
            ////echo '<br>';
            ////echo '</div>';
            ////echo '<div class="col-md-4">';
            $total['SUM Libur'] = 0;
            $total['COUNT Libur'] = 0;
            $total['COUNT Kerja'] = 0;
            $total['SUM Kerja'] = 0;
            $tunjangan = "select *
			from m_tunjangan a 
			left join prl_tunjangan b on a.m_tunjangan_id = b.m_tunjangan_id
			where b.p_karyawan_id = $id and b.active=1";
            //////echo $potongan;
            $tunjangan = DB::connection()->select($tunjangan);
            $gapok = 0;
            //print_r($tunjanga)($masuk+$ipd)
            foreach ($tunjangan as $tunjangan) {
               
                $this->prl_gaji_detail($id_prl, $g->p_karyawan_id, 2, $tunjangan->prl_tunjangan_id, ($tunjangan->nominal));
                if ($tunjangan->is_gapok) $gapok += $tunjangan->nominal;
                ////echo '<strong>'.$tunjangan->nama.': </strong>'.$help->rupiah($tunjangan->nominal);
                ////echo '<br>';
            }
            $gapok = $gapok * 22;
            //////echo $gapok;
            $lembur_kerja = ($total['1jam'] * (1.5 / 173) * $gapok) + ($total['>=2jam'] * (2 / 173) * $gapok);
            $lembur_libur = (($total['8 jam'] * (2 / 173) * $gapok) + (($total['9 jam'] * (3 / 173) * $gapok) + (($total['>=10 jam'] * (4 / 173) * $gapok))));
            $lembur_proposional = ($gapok / 22) * $total['total_lembur_proposional'];
            $lembur = $lembur_kerja + $lembur_libur+$lembur_proposional;
            $this->prl_gaji_detail($id_prl, $g->p_karyawan_id, 1, 17, ($lembur_kerja));
            $this->prl_gaji_detail($id_prl, $g->p_karyawan_id, 1, 18, ($lembur_libur));
            $this->prl_gaji_detail($id_prl, $g->p_karyawan_id, 1, 36, ($lembur_proposional));
            
            $this->prl_gaji_detail($id_prl, $g->p_karyawan_id, 3, 15, ($lembur));
            
            $type_nominal_absen = 2;
            $type_nominal_fingerprint = 2;
           
            $id_pangkat = $detail_karyawan->m_pangkat_id;
            $sql = "SELECT *,
            	(select nominal from m_potongan_absen a where a.m_pangkat_id = m_pangkat.m_pangkat_id and type_absen='absen') as nominal_absen,
            	(select nominal from m_potongan_absen a where a.m_pangkat_id = m_pangkat.m_pangkat_id and type_absen='izin') as nominal_izin,
            	(select nominal from m_potongan_absen a where a.m_pangkat_id = m_pangkat.m_pangkat_id and type_absen='alpha') as nominal_alpha,
            	(select nominal from m_potongan_absen a where a.m_pangkat_id = m_pangkat.m_pangkat_id and type_absen='fingerprint') as nominal_fingerprint,
            	(select nominal from m_potongan_absen a where a.m_pangkat_id = m_pangkat.m_pangkat_id and type_absen='pm') as nominal_pm,
            	
            	(select type_nominal from m_potongan_absen a where a.m_pangkat_id = m_pangkat.m_pangkat_id and type_absen='absen') as type_nominal_absen,
            	(select type_nominal from m_potongan_absen a where a.m_pangkat_id = m_pangkat.m_pangkat_id and type_absen='izin') as type_nominal_izin,
            	(select type_nominal from m_potongan_absen a where a.m_pangkat_id = m_pangkat.m_pangkat_id and type_absen='alpha') as type_nominal_alpha,
            	(select type_nominal from m_potongan_absen a where a.m_pangkat_id = m_pangkat.m_pangkat_id and type_absen='fingerprint') as type_nominal_fingerprint,
            	(select type_nominal from m_potongan_absen a where a.m_pangkat_id = m_pangkat.m_pangkat_id and type_absen='pm') as type_nominal_pm
            	
            	
            	FROM m_pangkat where active=1 and m_pangkat_id = $id_pangkat";
            $potongan_absen = DB::connection()->select($sql);
            if ($potongan_absen[0]->type_nominal_absen == 1) {
                $potabsen = $potongan_absen[0]->nominal_absen * $terlambat;
            } else if ($potongan_absen[0]->type_nominal_absen == 2) {
                $potabsen = ($potongan_absen[0]->nominal_absen / 100 * $gapok) * $terlambat;
            } else if ($potongan_absen[0]->type_nominal_absen == 3) {
                $potabsen = ($gapok / 22) * $terlambat;
            }
            if ($potongan_absen[0]->type_nominal_pm == 1) {
                $potmendahului = $potongan_absen[0]->nominal_pm * $pm;
            } else if ($potongan_absen[0]->type_nominal_pm == 2) {
                $potmendahului = ($potongan_absen[0]->nominal_pm / 100 * $gapok) * $pm;
            } else if ($potongan_absen[0]->type_nominal_pm == 3) {
                $potmendahului = ($gapok / 22) * $pm;
            }

            if ($potongan_absen[0]->type_nominal_fingerprint == 1) {
                $potfingerprint = $potongan_absen[0]->nominal_fingerprint * $fingerprint;
            } else if ($potongan_absen[0]->type_nominal_fingerprint == 2) {
                $potfingerprint = ($potongan_absen[0]->nominal_fingerprint / 100 * $gapok) * $fingerprint;
            } else if ($potongan_absen[0]->type_nominal_fingerprint == 3) {
                $potfingerprint = ($gapok / 22) * $fingerprint;
            }

            $this->prl_gaji_detail($id_prl, $g->p_karyawan_id, 1, 24, ($fingerprint));
            $this->prl_gaji_detail($id_prl, $g->p_karyawan_id, 1, 26, ($idt));
            $this->prl_gaji_detail($id_prl, $g->p_karyawan_id, 1, 27, ($ipm));
            if(($g->lokasi_id==5) or  ($g->lokasi_id==28) or $g->p_karyawan_id == 270 ){
                
            }else{
            $this->prl_gaji_detail($id_prl, $g->p_karyawan_id, 1, 23, ($terlambat));
            $this->prl_gaji_detail($id_prl, $g->p_karyawan_id, 5, 12, ($potabsen));
            $this->prl_gaji_detail($id_prl, $g->p_karyawan_id, 1, 25, ($pm));
            $this->prl_gaji_detail($id_prl, $g->p_karyawan_id, 5, 24, ($potmendahului));
            }
            $this->prl_gaji_detail($id_prl, $g->p_karyawan_id, 5, 23, ($potfingerprint));
            //$this->prl_gaji_detail($id_prl,$g->p_karyawan_id,1,15,($total['1jam']+$total['>=2jam']));
            //	$this->prl_gaji_detail($id_prl,$g->p_karyawan_id,1,16,($lembur_libur));
            

            ////echo '<br>';
            ////echo '<strong>Lembur: </strong>'.$help->rupiah($lembur);
            ////echo '</div>';

            ////echo '<div class="col-md-4">';

            $potongan = "select *
			from m_potongan a 
			right join prl_potongan b on a.m_potongan_id = b.m_potongan_id
			where b.p_karyawan_id = $id and b.active=1 and b.m_potongan_id not in (20,21,9,16,17,16,19,18)";
            //////echo $potongan;
            $potongan = DB::connection()->select($potongan);
            foreach ($potongan as $potongan) {
                $this->prl_gaji_detail($id_prl, $g->p_karyawan_id, 4, $potongan->prl_potongan_id, ($potongan->nominal));
                //echo '<strong>'.$potongan->nama.': </strong>'.$help->rupiah($potongan->nominal)	;
                //echo '<br>';
            }
            //////echo $tgl_akhir;
            $Now = date('Y-m-d');
            $potongan = "select *
			from p_karyawan_koperasi a 
			where a.p_karyawan_id = $id and  tgl_akhir>='$tgl_awal'  and a.active=1";
            //////echo $potongan;
            $potongan = DB::connection()->select($potongan);
            foreach ($potongan as $potongan) {
               $jenis = 5;
                if ($potongan->nama_koperasi == 'ASA') {
                    $type = 21;
                } else  if ($potongan->nama_koperasi == 'KKB') {
                    $type = 9;
                } else if ($potongan->nama_koperasi == 'SEWA KOST') {
                    $type = 16;
                }else if ($potongan->nama_koperasi == 'PAJAK') {
                    $type = 20;
                }else if ($potongan->nama_koperasi == 'ZAKAT') {
                    $type = 18;
                }else if ($potongan->nama_koperasi == 'INFAQ') {
                    $type = 19;
                }else if ($potongan->nama_koperasi == 'BONUS') {
                    $type = 19;
            		$jenis = 3;
                }else if ($potongan->nama_koperasi == 'TUNJANGAN KOST') {
                    $type = 14;
            		$jenis = 3;
                }
                if(in_array($potongan->nama_koperasi,array('ASA','KKB','SEWA KOST','TUNJANGAN KOST')) and $periodetgl[0]->pekanan_ke==1){
                	
                }else{
                	
                $this->prl_gaji_detail($id_prl, $g->p_karyawan_id, $jenis, $type, ($potongan->nominal));
                }
               
            }

            ////echo '</div>';


            ////echo '</div>';
            ////echo '</div>';
            ////echo '</div>';
            DB::connection()->table("prl_generate_karyawan")
                ->where("prl_generate_karyawan_id", $g->prl_generate_karyawan_id)
                ->update([
                    "status" => 1,

                ]);
            //DB::connection()->table("t_permit")
            //	->where("t_form_exit_id", $list_permit)
            //	->update([
            //		"status_generate" => 1,
            //
            //	]);

            $id_generate = $g->prl_generate_id;
        }
        $time = $request->get('time');
         $time = $time?$time:5000;
         $time = $time/1000;
        $sqlgenerate = "SELECT count(*) as jumlah_semua_karyawan,count(CASE WHEN status = 1 THEN 1 END)  as yang_sudah FROM prl_generate_karyawan a where prl_generate_id = $id_generate $where_generate";
        //////echo $sqluser;
        $generete = DB::connection()->select($sqlgenerate);
        $persen = round($generete[0]->yang_sudah / $generete[0]->jumlah_semua_karyawan * 100, 2);
        $init = ($generete[0]->jumlah_semua_karyawan - $generete[0]->yang_sudah) * $time;
        $hours = floor($init / 3600);
        $minutes = floor(($init / 60) % 60);
        $seconds = $init % 60;

        $Jam = $hours ? $hours . ' Jam ' : '';
        $menit = $minutes ? $minutes . ' Menit ' : '';
        $sekon = $seconds ? $seconds . ' Detik ' : '';
        //echo "$hours:$minutes:$seconds";
        echo '<div class="card">
										<div class="card-body text-center">
											<br>
											<h3>' . $persen . '%</h3>
											<h4 class="holiday-title mb-0">' . $generete[0]->yang_sudah . ' dari ' . $generete[0]->jumlah_semua_karyawan . '</h4>
											<div>' . $Jam . $menit . $sekon . ' </div>
											<div class="text-red" style="color:red;">PERINGATAN!!! TAB JANGAN DI TUTUP SEBELUM SELESAI</div>
										</div>
									</div>
									';
    }
    public function prl_gaji_detail($id_prl, $id_karyawan, $type, $id, $nominal,$keterangan=null)
    {
        //$id_prl,$g->p_karyawan_id,1,1,($masuk+$cuti+$ipg+$izin+$ipd+$alpha)
        //////echo $nominal;
        if($type==1 and $id==35){
        	$row = "string_jenis_ijin";
		}else if ($type == 1) {
            $row = 'gaji_absen_id';
        } else if ($type == 2) {
            $row = 'prl_tunjangan_id';
        } else if ($type == 3) {
            $row = 'm_tunjangan_id';
        } else if ($type == 4) {
            $row = 'prl_potongan_id';
        } else if ($type == 5) {
            $row = 'm_potongan_id';
        }
		if($type==1 and $id==35){
        	$prl_detail = DB::connection()->select("select * from prl_gaji_detail where prl_gaji_id = $id_prl and type=$type and p_karyawan_id = $id_karyawan and gaji_absen_id=$id and string_jenis_ijin='$nominal'");	
		}else{			
        	$prl_detail = DB::connection()->select("select * from prl_gaji_detail where prl_gaji_id = $id_prl and type=$type and p_karyawan_id = $id_karyawan and $row=$id");
		}
		if(!($type==1 and $id==35)){
	        if (count($prl_detail)) 								{
            DB::connection()->table("prl_gaji_detail")
                ->where('prl_gaji_detail_id', $prl_detail[0]->prl_gaji_detail_id)
                ->update([
                    "nominal" => $nominal,
                    "keterangan" => $keterangan,
                    "update_date" => date("Y-m-d H:i:s")
                ]);
        } 
	        else 											{

            DB::connection()->table("prl_gaji_detail")

                ->insert([

                    "prl_gaji_id" => $id_prl,
                    "type" => $type,
                    "p_karyawan_id" => $id_karyawan,
                     $row => $id,
                    "keterangan" => $keterangan,

                    "nominal" => $nominal,
                    "create_date" => date("Y-m-d H:i:s")
                ]);
        }
	    
    	}else{
	    		if (count($prl_detail)) 								{
	            DB::connection()->table("prl_gaji_detail")
	                ->where('prl_gaji_detail_id', $prl_detail[0]->prl_gaji_detail_id)
	                ->update([
	                    "$row" => $nominal,
	                    "keterangan" => $keterangan,
	                    "update_date" => date("Y-m-d H:i:s")
	                ]);
	        } 
		        else 											{

	            DB::connection()->table("prl_gaji_detail")

	                ->insert([

	                    "prl_gaji_id" => $id_prl,
	                    "type" => $type,
	                    "p_karyawan_id" => $id_karyawan,
	                    "gaji_absen_id" => $id,
	                    "keterangan" => $keterangan,

	                    "$row" => $nominal,
	                    "create_date" => date("Y-m-d H:i:s")
	                ]);
	        }
	    
    	}
    }
    public function prl_gaji($id_generate, $id)
    {
        DB::beginTransaction();
        try {
            $sqluser = "SELECT a.lokasi_id as lokasi_id, periode_gajian ,
            (select count(*) from prl_gaji b  where 
            	a.lokasi_id = b.m_lokasi_id and 
            	a.periode_gajian = b.periode_gajian_in_gajian and 
            	prl_generate_id = $id_generate ) as jumlah, 
            (select max(prl_gaji_id)  from prl_gaji b) as max 
            FROM prl_generate_karyawan a 
            
            where p_karyawan_id = $id 
            	and prl_generate_id = $id_generate
             and lokasi_id is not null 
             and periode_gajian is not null";
           //echo $sqluser;
            $karyawan = DB::connection()->select($sqluser);
            //print_r($karyawan);
            //jumlah adalah?
            if (!$karyawan[0]->jumlah) {
                DB::connection()->table("prl_gaji")

                    ->insert([
                        "m_lokasi_id" => $karyawan[0]->lokasi_id,
                        "prl_gaji_id" => $karyawan[0]->max + 1,
                        "periode_gajian_in_gajian" => $karyawan[0]->periode_gajian,
                        "prl_generate_id" => $id_generate,

                        "active" => 1,
                        "create_date" => date("Y-m-d H:i:s")
                    ]);
                $id_prl_Gaji = $karyawan[0]->max + 1;
            } else {
                $id_lokasi = $karyawan[0]->lokasi_id;
                $periode_gajian = $karyawan[0]->periode_gajian;
                $sqluser = "select (prl_gaji_id) from prl_gaji b  where m_lokasi_id = $id_lokasi and periode_gajian_in_gajian=$periode_gajian and prl_generate_id = $id_generate";
                $karyawan = DB::connection()->select($sqluser);
                $id_prl_Gaji = $karyawan[0]->prl_gaji_id;
            }

            DB::commit();
            return $id_prl_Gaji;
            // return redirect()->route('be.kontrak')->with('success',' Kontrak Berhasil diperbaharui!');
        } catch (\Exeception $e) {
            DB::rollback();
            return redirect()->back()->with('error', $e);
        }
    }
    public function hitungbulanan($request,$id,$where_generate)
    {
        $help = new Helper_function();
        $sqluser = "SELECT * FROM prl_generate_karyawan a 
        join prl_generate b on b.prl_generate_id = a.prl_generate_id
        left join m_jabatan on jabatan_id = m_jabatan.m_jabatan_id 
        where a.prl_generate_id = $id and status = 0 $where_generate 
        
        order by b.prl_generate_id asc limit 1";
        $generate = DB::connection()->select($sqluser);


        $info_generate = DB::connection()->select("select * from prl_generate where prl_generate_id = $id");

		if($info_generate[0]->periode_gajian==2){
			$periode_absen = $info_generate[0]->periode_absen_bulanan_id;
        	$periode_lembur = $info_generate[0]->periode_lembur_bulanan_id;
					
		}else{
			
       	 	$periode_absen = $info_generate[0]->periode_absen_id;
        	$periode_lembur = $info_generate[0]->periode_lembur_id;
		}

        $sqlperiode = "SELECT * FROM m_periode_absen where periode_absen_id=$periode_absen";
        $periodetgl = DB::connection()->select($sqlperiode);
        $type = $periodetgl[0]->type;
        $where = " d.periode_gajian = " . $type;
        $appendwhere = "and";

        $periode_gajian = $periodetgl[0]->type;
        $tgl_awal = date('Y-m-d', strtotime($periodetgl[0]->tgl_awal));
        $tgl_akhir = date('Y-m-d', strtotime($periodetgl[0]->tgl_akhir));

        $sqlperiode = "SELECT * FROM m_periode_absen where periode_absen_id=$periode_lembur";
        $periodetgl_lembur = DB::connection()->select($sqlperiode);
        $tgl_awal_lembur = date('Y-m-d', strtotime($periodetgl_lembur[0]->tgl_awal));
        $tgl_akhir_lembur = date('Y-m-d', strtotime($periodetgl_lembur[0]->tgl_akhir)); 


        foreach ($generate as $g) {
            $id = $g->p_karyawan_id;
            $rekap = $help->rekap_absen($tgl_awal, $tgl_akhir, $tgl_awal_lembur, $tgl_akhir_lembur, $type,null,$id);
             $hari_libur = $rekap['hari_libur'];
       		 $hari_libur_shift = $rekap['hari_libur_shift'];
            //////echo $id;
            $sql = 'SELECT c.p_karyawan_id,c.nama as nama_lengkap,c.nik,f.m_pangkat_id,i.nama as nm_pangkat ,f.nama as nmjabatan,AGE(CURRENT_DATE, c.tgl_bergabung)::VARCHAR AS umur
			FROM p_karyawan c 
			LEFT JOIN p_karyawan_pekerjaan d on d.p_karyawan_id=c.p_karyawan_id  
			LEFT JOIN m_jabatan f on d.m_jabatan_id=f.m_jabatan_id 
			LEFT JOIN m_pangkat i on f.m_pangkat_id=i.m_pangkat_id 
			where c.p_karyawan_id = ' . $id;
            $detail_karyawan = DB::connection()->select($sql);
            $detail_karyawan = $detail_karyawan[0];

            $return = $help->total_rekap_absen($rekap, $id);


            $masuk                 = $return['total']['masuk'];
            $cuti                 = $return['total']['cuti'];
            $ipg                 = $return['total']['ipg'];
            $izin                 = $return['total']['izin'];
            $ipd                 = $return['total']['ipd'];
            $ipc                 = $return['total']['ipc'];
            $idt                 = $return['total']['idt'];
            $ipm                = $return['total']['ipm'];
            $pm                 = $return['total']['pm'];
            $sakit                 = $return['total']['sakit'];
            $alpha                 = $return['total']['alpha'];
            $terlambat             = $return['total']['terlambat'];
            $tabsen             = $return['total']['Total Absen'];
            $tmasuk             = $return['total']['Total Masuk'];
            $tkerja             = $return['total']['Total Hari Kerja'];
            $fingerprint         = $return['total']['fingerprint'];
            $total_all            = $return['total']['total_all'];
            $total['<8 jam']     = $return['total']['<8 jam'];
            $total['8 jam']        = $return['total']['8 jam'];
            $total['9 jam']        = $return['total']['9 jam'];
            $total['>=10 jam']     = $return['total']['>=10 jam'];
            $total['total_lembur_proposional']     = $return['total_lembur']['total_lembur_proposional'];


            $total['1jam']         = $return['total']['1jam'];
            $total['>=2jam']     = $return['total']['>=2jam'];
            $total['SUM Libur'] = $return['total']['SUM Libur'];
            $total['COUNT Libur'] = $return['total']['COUNT Libur'];
            $total['COUNT Kerja'] = $return['total']['COUNT Kerja'];
            $total['SUM Kerja'] = $return['total']['SUM Kerja'];



            $karyawan = "select a.nama as nama,c.nama as nama_jabatan, d.nama as nama_pangkat
			from p_karyawan a 
			join p_karyawan_pekerjaan b on a.p_karyawan_id = b.p_karyawan_id
			join m_jabatan c on c.m_jabatan_id = b.m_jabatan_id
			join m_pangkat d on c.m_pangkat_id = d.m_pangkat_id
			where a.p_karyawan_id = $id";
            $karyawan = DB::connection()->select($karyawan);


            $id_prl = $this->prl_gaji($g->prl_generate_id, $g->p_karyawan_id);
           
            $this->prl_gaji_detail($id_prl, $g->p_karyawan_id, 1, 1, ($masuk + $cuti + $ipg + $izin + $ipd + $alpha));
           
            $this->prl_gaji_detail($id_prl, $g->p_karyawan_id, 1, 2, ($masuk + $ipd));
            
            $this->prl_gaji_detail($id_prl, $g->p_karyawan_id, 1, 3, ($sakit));
           
            $this->prl_gaji_detail($id_prl, $g->p_karyawan_id, 1, 4, ($cuti));
            
            $this->prl_gaji_detail($id_prl, $g->p_karyawan_id, 1, 5, ($ipd));
          
            $this->prl_gaji_detail($id_prl, $g->p_karyawan_id, 1, 6, ($izin));
          
            $this->prl_gaji_detail($id_prl, $g->p_karyawan_id, 1, 7, ($ipg));
           
          
            $this->prl_gaji_detail($id_prl, $g->p_karyawan_id, 1, 9, ($total_all));
            
            $this->prl_gaji_detail($id_prl, $g->p_karyawan_id, 1, 10, ($total['1jam']));
            
            $this->prl_gaji_detail($id_prl, $g->p_karyawan_id, 1, 11, ($total['>=2jam']));
           
            $this->prl_gaji_detail($id_prl, $g->p_karyawan_id, 1, 12, ($total['8 jam']));
            
            $this->prl_gaji_detail($id_prl, $g->p_karyawan_id, 1, 13, ($total['9 jam']));
           
            $this->prl_gaji_detail($id_prl, $g->p_karyawan_id, 1, 14, ($total['>=10 jam']));
            $this->prl_gaji_detail($id_prl, $g->p_karyawan_id, 1, 22, ($total['SUM Libur']));
            $this->prl_gaji_detail($id_prl, $g->p_karyawan_id, 1, 21, ($total['COUNT Libur']));
            $this->prl_gaji_detail($id_prl, $g->p_karyawan_id, 1, 19, ($total['COUNT Kerja']));
            $this->prl_gaji_detail($id_prl, $g->p_karyawan_id, 1, 20, ($total['SUM Kerja']));
            $this->prl_gaji_detail($id_prl, $g->p_karyawan_id, 1, 31, ($return['total']['IPGCuti']));
            $this->prl_gaji_detail($id_prl, $g->p_karyawan_id, 1, 32, 0,($return['total']['IPGCutiList']));
            $this->prl_gaji_detail($id_prl, $g->p_karyawan_id, 1, 37, ($total['total_lembur_proposional']));
            
            foreach($return['string'] as $string => $value){
            	$this->prl_gaji_detail($id_prl, $g->p_karyawan_id, 1, 35, $string,($value));
            	
            }


            $tunjangan = "select *
			from m_tunjangan a 
			join prl_tunjangan b on a.m_tunjangan_id = b.m_tunjangan_id
			where b.p_karyawan_id = $id and b.active=1 and b.m_tunjangan_id not in(16,14)";
            //////echo $potongan;
            $tunjangan = DB::connection()->select($tunjangan);
            $gapok = 0;
            //print_r($tunjanga)
            foreach ($tunjangan as $tunjangan) {
               // echo ''.$tunjangan->nama;
                $this->prl_gaji_detail($id_prl, $g->p_karyawan_id, 2, $tunjangan->prl_tunjangan_id, ($tunjangan->nominal));
                if ($tunjangan->is_tunjangan_gapok) $gapok += $tunjangan->nominal;
                //echo $tunjangan->is_tunjangan_gapok;
               
            }

            $lembur_kerja = ($total['1jam'] * (1.5 / 173) * $gapok) + ($total['>=2jam'] * (2 / 173) * $gapok);
            $lembur_libur = (($total['8 jam'] * (2 / 173) * $gapok) + (($total['9 jam'] * (3 / 173) * $gapok) + (($total['>=10 jam'] * (4 / 173) * $gapok))));
            
            $lembur_proposional = ($gapok / 22) * $total['total_lembur_proposional'];
           
            $this->prl_gaji_detail($id_prl, $g->p_karyawan_id, 1, 17, ($lembur_kerja));
            $this->prl_gaji_detail($id_prl, $g->p_karyawan_id, 1, 18, ($lembur_libur));
            $this->prl_gaji_detail($id_prl, $g->p_karyawan_id, 1, 36, ($lembur_proposional));
            $lembur = $lembur_kerja + $lembur_libur+$lembur_proposional;
            $this->prl_gaji_detail($id_prl, $g->p_karyawan_id, 3, 15, ($lembur));

           
            $id_pangkat = $detail_karyawan->m_pangkat_id;
          

            $sql = "SELECT 
            	(select nominal from m_potongan_absen a where a.m_pangkat_id = $id_pangkat and type_absen='absen') as nominal_absen,
            	(select nominal from m_potongan_absen b where b.m_pangkat_id = $id_pangkat and type_absen='izin') as nominal_izin,
            	(select nominal from m_potongan_absen c where c.m_pangkat_id = $id_pangkat and type_absen='alpha') as nominal_alpha,
            	(select nominal from m_potongan_absen d where d.m_pangkat_id = $id_pangkat and type_absen='fingerprint') as nominal_fingerprint,
            	(select nominal from m_potongan_absen e where e.m_pangkat_id = $id_pangkat and type_absen='pm') as nominal_pm,
            	
            	(select type_nominal from m_potongan_absen f where f.m_pangkat_id = $id_pangkat and type_absen='absen') as type_nominal_absen,
            	(select type_nominal from m_potongan_absen g where g.m_pangkat_id = $id_pangkat and type_absen='izin') as type_nominal_izin,
            	(select type_nominal from m_potongan_absen h where h.m_pangkat_id = $id_pangkat and type_absen='alpha') as type_nominal_alpha,
            	(select type_nominal from m_potongan_absen i where i.m_pangkat_id = $id_pangkat and type_absen='fingerprint') as type_nominal_fingerprint,
            	(select type_nominal from m_potongan_absen j where j.m_pangkat_id = $id_pangkat and type_absen='pm') as type_nominal_pm
            	
            	
            	 ";
            $potongan_absen = DB::connection()->select($sql);
            //print_r($id_pangkat);
            //echo $g->p_karyawan_id;
            if ($potongan_absen[0]->type_nominal_absen == 1) {
                $potabsen = $potongan_absen[0]->nominal_absen * $terlambat;
            } else if ($potongan_absen[0]->type_nominal_absen == 2) {
                $potabsen = ($potongan_absen[0]->nominal_absen / 100 * $gapok) * $terlambat;
            } else if ($potongan_absen[0]->type_nominal_absen == 3) {
                $potabsen = ($gapok / 22) * $terlambat;
            }
            if ($potongan_absen[0]->type_nominal_pm == 1) {
                $potmendahului = $potongan_absen[0]->nominal_pm * $pm;
            } else if ($potongan_absen[0]->type_nominal_pm == 2) {
                $potmendahului = ($potongan_absen[0]->nominal_pm / 100 * $gapok) * $pm;
            } else if ($potongan_absen[0]->type_nominal_pm == 3) {
                $potmendahului = ($gapok / 22) * $pm;
            }

            if ($potongan_absen[0]->type_nominal_izin == 1) {
                $potizin = $potongan_absen[0]->nominal_izin * $ipg;
            } else if ($potongan_absen[0]->type_nominal_izin == 2) {
                $potizin = ($potongan_absen[0]->nominal_izin / 100 * $gapok) * $ipg;
            } else if ($potongan_absen[0]->type_nominal_izin == 3) {
                $potizin = ($gapok / 22) * $ipg;
            }

            if ($potongan_absen[0]->type_nominal_alpha == 1) {
                $potalpha = $potongan_absen[0]->nominal_alpha * $alpha;
            } else if ($potongan_absen[0]->type_nominal_alpha == 2) {
                $potalpha = ($potongan_absen[0]->nominal_alpha / 100 * $gapok) * $alpha;
            } else if ($potongan_absen[0]->type_nominal_alpha == 3) {
                $potalpha = ($gapok / 22) * $alpha;
            }
            if ($potongan_absen[0]->type_nominal_fingerprint == 1) {
                $potfingerprint = $potongan_absen[0]->nominal_fingerprint * $fingerprint;
            } else if ($potongan_absen[0]->type_nominal_fingerprint == 2) {
                $potfingerprint = ($potongan_absen[0]->nominal_fingerprint / 100 * $gapok) * $fingerprint;
            } else if ($potongan_absen[0]->type_nominal_fingerprint == 3) {
                $potfingerprint = ($gapok / 22) * $fingerprint;
            }

//echo $gapok;

            $this->prl_gaji_detail($id_prl, $g->p_karyawan_id, 1, 26, ($idt));
            $this->prl_gaji_detail($id_prl, $g->p_karyawan_id, 1, 27, ($ipm));
            $this->prl_gaji_detail($id_prl, $g->p_karyawan_id, 1, 28, ($ipc));
            $this->prl_gaji_detail($id_prl, $g->p_karyawan_id, 1, 24, ($fingerprint));
             if ($g->m_pangkat_id == 5 or $g->m_pangkat_id == 6  ) {
             } else if (($g->lokasi_id==5)) {
            
            } else 
            if ((($g->p_karyawan_id == 269 or $g->p_karyawan_id == 1 or $g->p_karyawan_id == 270) ) or ($g->lokasi_id==5) or  ($g->lokasi_id==28) ){
                
            }else{
                $this->prl_gaji_detail($id_prl, $g->p_karyawan_id, 5, 12, ($potabsen));
                $this->prl_gaji_detail($id_prl, $g->p_karyawan_id, 1, 23, ($terlambat));
                $this->prl_gaji_detail($id_prl, $g->p_karyawan_id, 1, 25, ($pm));
                $this->prl_gaji_detail($id_prl, $g->p_karyawan_id, 5, 24, ($potmendahului));
                
            }
             if (($g->lokasi_id==7) and ($g->p_karyawan_id != 130)) {
             }else{
                $this->prl_gaji_detail($id_prl, $g->p_karyawan_id, 1, 8, ($alpha));
                $this->prl_gaji_detail($id_prl, $g->p_karyawan_id, 5, 13, ($potalpha));
             }
            $this->prl_gaji_detail($id_prl, $g->p_karyawan_id, 5, 22, ($potizin));
            $this->prl_gaji_detail($id_prl, $g->p_karyawan_id, 5, 23, ($potfingerprint));


          

            $potongan = "select *
			from m_potongan a 
			right join prl_potongan b on a.m_potongan_id = b.m_potongan_id
			where b.p_karyawan_id = $id and b.active=1 and a.m_potongan_id not in (20,21,9,16,17,16,19,18)";
            //////echo $potongan;
            $potongan = DB::connection()->select($potongan);
            foreach ($potongan as $potongan) {
                $this->prl_gaji_detail($id_prl, $g->p_karyawan_id, 4, $potongan->prl_potongan_id, ($potongan->nominal));
                
            }
            
            //tambahin dari p_karyawan Gapok
           
            $Now = date('Y-m-d');
            $potongan = "select *
			from p_karyawan_koperasi a 
			where a.p_karyawan_id = $id and  tgl_akhir>='$tgl_awal' and a.active=1";
           
            $potongan = DB::connection()->select($potongan);
            foreach ($potongan as $potongan) {
            	$jenis = 5;
                if ($potongan->nama_koperasi == 'ASA') {
                    $type = 21;
                } else  if ($potongan->nama_koperasi == 'KKB') {
                    $type = 9;
                } else if ($potongan->nama_koperasi == 'SEWA KOST') {
                    $type = 16;
                }else if ($potongan->nama_koperasi == 'PAJAK') {
                    $type = 20;
                }else if ($potongan->nama_koperasi == 'ZAKAT') {
                    $type = 18;
                }else if ($potongan->nama_koperasi == 'INFAQ') {
                    $type = 19;
                }else if ($potongan->nama_koperasi == 'BONUS') {
                    $type = 20;
            		$jenis = 3;
                }else if ($potongan->nama_koperasi == 'TUNJANGAN KOST') {
                    $type = 14;
            		$jenis = 3;
                }
                $this->prl_gaji_detail($id_prl, $g->p_karyawan_id, $jenis, $type, ($potongan->nominal));
               
            }

          
            DB::connection()->table("prl_generate_karyawan")
                ->where("prl_generate_karyawan_id", $g->prl_generate_karyawan_id)
                ->update([
                    "status" => 1,

                ]);
        }
       $time = $request->get('time');
         $time = $time?$time:5000;
         $time = $time/1000;
        $id_generate = $g->prl_generate_id;
        $sqlgenerate = "SELECT count(*) as jumlah_semua_karyawan,count(CASE WHEN status = 1 THEN 1 END)  as yang_sudah FROM prl_generate_karyawan a where prl_generate_id = $id_generate $where_generate";
        //////echo $sqluser;
        $generete = DB::connection()->select($sqlgenerate);
        $persen = round($generete[0]->yang_sudah / $generete[0]->jumlah_semua_karyawan * 100, 2);
        $init = ($generete[0]->jumlah_semua_karyawan - $generete[0]->yang_sudah) * $time;
        $hours = floor($init / 3600);
        $minutes = floor(($init / 60) % 60);
        $seconds = $init % 60;

        $Jam = $hours ? $hours . ' Jam ' : '';
        $menit = $minutes ? $minutes . ' Menit ' : '';
        $sekon = $seconds ? $seconds . ' Detik ' : '';
        //echo "$hours:$minutes:$seconds";
        echo '<div class="card">
										<div class="card-body text-center">
											<br>
											<h3>' . $persen . '%</h3>
											<h4 class="holiday-title mb-0">' . $generete[0]->yang_sudah . ' dari ' . $generete[0]->jumlah_semua_karyawan . '</h4>
											<div>' . $Jam . $menit . $sekon . ' </div>
											<div class="text-red" style="color:red;">PERINGATAN!!! TAB JANGAN DI TUTUP SEBELUM SELESAI</div>
										</div>
									</div>
									<input type="hidden" value="'.$persen.'" id="generate">
									';
    }
    public function proses($id)
    {
        $help = new Helper_function();
        $sqluser = "SELECT * FROM prl_generate where prl_generate_id = $id";
        $generate = DB::connection()->select($sqluser);
        $sqluser = "SELECT * FROM m_lokasi where active=1 and sub_entitas=0 and (select count(*) from p_karyawan join p_karyawan_pekerjaan on  p_karyawan_pekerjaan.p_karyawan_id = p_karyawan.p_karyawan_id where p_karyawan_pekerjaan.m_lokasi_id = m_lokasi.m_lokasi_id and p_karyawan.active=1 and periode_gajian=".$generate[0]->periode_gajian.")>0 order by m_lokasi_id desc";
        $entitas = DB::connection()->select($sqluser);
        $g = $generate[0];
       
        if ($g->periode_gajian)
            $pekanan = '';
        else if ($g->periode_gajian == 0)
            $pekanan = '_pekanan';

        return view('backend.gaji.generate.proses', compact('id', 'g',  'help', 'pekanan', 'entitas'));
    }
    public function index(Request $request)
    {
        $sqluser = "SELECT prl_generate.*,case when prl_generate.periode_gajian=0 then 'Pekanan' else 'Bulanan' end as tipe,
    	a.tgl_awal as tgl_awal_absen, 
    	a.tgl_akhir as tgl_akhir_absen, 
    	b.tgl_awal as tgl_awal_lembur, 
    	b.tgl_akhir as tgl_akhir_lembur
    	FROM prl_generate 
    		left join m_periode_absen a on prl_generate.periode_absen_id = a.periode_absen_id
    		left join m_periode_absen b on prl_generate.periode_lembur_id = b.periode_absen_id
    		where prl_generate.active = 1
    		order by prl_generate_id desc
    	";
        $generate = DB::connection()->select($sqluser);

        return view('backend.gaji.generate.index', compact('generate'));
    }
    public function nonaktifgenerate($id_generate)
    {
        DB::connection()->table("prl_generate")
            ->where('prl_generate_id', $id_generate)
            ->update([

                "active" => 0,

            ]);
        return redirect()->route('be.generategaji')->with('success', ' Generate dinonaktifkan!');
    }
    public function revisi_generate($id_generate)
    {
        $sqlperiode = "SELECT * FROM prl_gaji where prl_generate_id=$id_generate";
        $sudahappr = DB::connection()->select($sqlperiode);
        foreach ($sudahappr as $apprs) {
            if ($apprs->appr_on_direktur_status == 1)
                $sudah_appr[$apprs->m_lokasi_id]['ON'] = 1;
            else
                $sudah_appr[$apprs->m_lokasi_id]['ON'] = 0;

            if ($apprs->appr_off_direktur_status == 1)
                $sudah_appr[$apprs->m_lokasi_id]['OFF'] = 1;
            else
                $sudah_appr[$apprs->m_lokasi_id]['OFF'] = 0;
        }

        $sql = "SELECT prl_generate.*,case when prl_generate.periode_gajian=0 then 'Pekanan' else 'Bulanan' end as tipe,
    	a.tgl_awal as tgl_awal, 
    	a.tgl_akhir as tgl_akhir, 
    	b.tgl_awal as tgl_awal_lembur, prl_generate.tahun as tahun_gener,prl_generate.*,prl_generate.bulan as bulan_gener,
    	b.tgl_akhir as tgl_akhir_lembur
    	FROM prl_generate 
    		left join m_periode_absen a on prl_generate.periode_absen_id = a.periode_absen_id
    		left join m_periode_absen b on prl_generate.periode_lembur_id = b.periode_absen_id
    		
    		where prl_generate_id = $id_generate
    	";
        $generate = DB::connection()->select($sql);
        $periode_gajian = $generate[0]->periode_gajian;
        //echo $periode_gajian;die;
        $sql = "Select * from p_karyawan join p_karyawan_pekerjaan a on a.p_karyawan_id = p_karyawan.p_karyawan_id  
    			where p_karyawan.p_karyawan_id  in(select a.p_karyawan_id from prl_generate_karyawan where prl_generate_id = $id_generate and status = 1 and periode_gajian = $periode_gajian )
    			order by nama
    	";
        $karyawan = DB::connection()->select($sql);
        return view('backend.gaji.generate.revisi', compact('karyawan', 'generate', 'id_generate', 'sudah_appr'));
    }
    public function revisi_data_generate(Request $request, $id_prl)
    {
        $sqlperiode = "SELECT * FROM prl_gaji where prl_generate_id=$id_prl";
        $sudahappr = DB::connection()->select($sqlperiode);
        foreach ($sudahappr as $apprs) {
            if ($apprs->appr_on_direktur_status)
                $sudah_appr[$apprs->m_lokasi_id]['ON'] = 1;
            else
                $sudah_appr[$apprs->m_lokasi_id]['ON'] = 0;

            if ($apprs->appr_off_direktur_status)
                $sudah_appr[$apprs->m_lokasi_id]['OFF'] = 1;
            else
                $sudah_appr[$apprs->m_lokasi_id]['OFF'] = 0;
        }

        $id = $request->get('karyawan');
        $sql = "select *,
			case when type=1 then (select nama_gaji from m_gaji_absen where b.gaji_absen_id = m_gaji_absen.m_gaji_absen_id )
		when type=2 then (select nama from prl_tunjangan join m_tunjangan on prl_tunjangan.m_tunjangan_id = m_tunjangan.m_tunjangan_id where b.prl_tunjangan_id = prl_tunjangan.prl_tunjangan_id )
		when type=3 then (select nama from m_tunjangan where b.m_tunjangan_id = m_tunjangan.m_tunjangan_id)
		when type=4 then (select nama from prl_potongan join m_potongan on prl_potongan.m_potongan_id = m_potongan.m_potongan_id where b.prl_potongan_id = prl_potongan.prl_potongan_id )
		when type=5 then (select nama from m_potongan where b.m_potongan_id = m_potongan.m_potongan_id)
		 end as nama from prl_gaji a join prl_gaji_detail b on b.prl_gaji_id = a.prl_gaji_id where prl_generate_id = $id_prl and p_karyawan_id = $id
		 and b.active=1
		 ";
        $row = DB::connection()->select($sql);
        $data = array();
        foreach ($row as $row) {
            $data[$row->p_karyawan_id][$row->nama] = round($row->nominal);
        }
        $sql = "SELECT prl_generate.*,case when prl_generate.periode_gajian=0 then 'Pekanan' else 'Bulanan' end as tipe,
    	a.tgl_awal as tgl_awal, 
    	a.tgl_akhir as tgl_akhir, 
    	b.tgl_awal as tgl_awal_lembur, prl_generate.tahun as tahun_gener,prl_generate.*,prl_generate.bulan as bulan_gener,
    	b.tgl_akhir as tgl_akhir_lembur
    	FROM prl_generate 
    		left join m_periode_absen a on prl_generate.periode_absen_id = a.periode_absen_id
    		left join m_periode_absen b on prl_generate.periode_lembur_id = b.periode_absen_id
    		
    		where prl_generate_id = $id_prl
    	";
        $generate = DB::connection()->select($sql);
        $periode_gajian = $generate[0]->periode_gajian;
        //echo $periode_gajian;die;
        $sql = "Select p_karyawan.nama, p_karyawan.p_karyawan_id ,c.nama as nm_pangkat,c.m_pangkat_id from p_karyawan 
				join p_karyawan_pekerjaan a on a.p_karyawan_id = p_karyawan.p_karyawan_id 
				join m_jabatan b on a.m_jabatan_id = b.m_jabatan_id 
				join m_pangkat c on c.m_pangkat_id = b.m_pangkat_id 
    			where p_karyawan.p_karyawan_id  in(select a.p_karyawan_id from prl_generate_karyawan where prl_generate_id = $id_prl and status = 1 and periode_gajian = $periode_gajian ) and p_karyawan.p_karyawan_id = $id
    			order by p_karyawan.nama
    	";
        $karyawan = DB::connection()->select($sql);
        $help = new Helper_function();
        $id_pangkat = $karyawan[0]->m_pangkat_id;
        $tunjangan = "select *
			from m_tunjangan a 
			left join prl_tunjangan b on a.m_tunjangan_id = b.m_tunjangan_id
			where b.p_karyawan_id = $id and b.active=1";
        //////echo $potongan;
        $tunjangan = DB::connection()->select($tunjangan);
        $gapok = 0;
        //print_r($tunjanga)
        foreach ($tunjangan as $tunjangan) {
            if ($tunjangan->is_gapok) $gapok += $tunjangan->nominal;
            ////echo '<strong>'.$tunjangan->nama.': </strong>'.$help->rupiah($tunjangan->nominal);
            ////echo '<br>';
        }
        $sql = "SELECT *,
            	(select nominal from m_potongan_absen a where a.m_pangkat_id = m_pangkat.m_pangkat_id and type_absen='absen') as nominal_absen,
            	(select nominal from m_potongan_absen a where a.m_pangkat_id = m_pangkat.m_pangkat_id and type_absen='izin') as nominal_izin,
            	(select nominal from m_potongan_absen a where a.m_pangkat_id = m_pangkat.m_pangkat_id and type_absen='alpha') as nominal_alpha,
            	(select nominal from m_potongan_absen a where a.m_pangkat_id = m_pangkat.m_pangkat_id and type_absen='fingerprint') as nominal_fingerprint,
            	(select type_nominal from m_potongan_absen a where a.m_pangkat_id = m_pangkat.m_pangkat_id and type_absen='absen') as type_nominal_absen,
            	(select type_nominal from m_potongan_absen a where a.m_pangkat_id = m_pangkat.m_pangkat_id and type_absen='izin') as type_nominal_izin,
            	(select type_nominal from m_potongan_absen a where a.m_pangkat_id = m_pangkat.m_pangkat_id and type_absen='alpha') as type_nominal_alpha,
            	(select type_nominal from m_potongan_absen a where a.m_pangkat_id = m_pangkat.m_pangkat_id and type_absen='fingerprint') as type_nominal_fingerprint
            	
            	FROM m_pangkat where active=1 and m_pangkat_id = $id_pangkat";
        $potongan_absen = DB::connection()->select($sql);
        if ($potongan_absen[0]->type_nominal_absen == 1) {
            $potabsen = $potongan_absen[0]->nominal_absen;
        } else if ($potongan_absen[0]->type_nominal_absen == 2) {
            $potabsen = ($potongan_absen[0]->nominal_absen / 100 * $gapok);
        } else if ($potongan_absen[0]->type_nominal_absen == 3) {
            $potabsen = ($gapok / 22);
        }
        if ($potongan_absen[0]->type_nominal_absen == 1) {
            $potmendahului = $potongan_absen[0]->nominal_absen;
        } else if ($potongan_absen[0]->type_nominal_absen == 2) {
            $potmendahului = ($potongan_absen[0]->nominal_absen / 100 * $gapok);
        } else if ($potongan_absen[0]->type_nominal_absen == 3) {
            $potmendahului = ($gapok / 22);
        }

        if ($potongan_absen[0]->type_nominal_izin == 1) {
            $potizin = $potongan_absen[0]->nominal_izin;
        } else if ($potongan_absen[0]->type_nominal_izin == 2) {
            $potizin = ($potongan_absen[0]->nominal_izin / 100 * $gapok);
        } else if ($potongan_absen[0]->type_nominal_izin == 3) {
            $potizin = ($gapok / 22);
        }

        if ($potongan_absen[0]->type_nominal_alpha == 1) {
            $potalpha = $potongan_absen[0]->nominal_alpha;
        } else if ($potongan_absen[0]->type_nominal_alpha == 2) {
            $potalpha = ($potongan_absen[0]->nominal_alpha / 100 * $gapok);
        } else if ($potongan_absen[0]->type_nominal_alpha == 3) {
            $potalpha = ($gapok / 22);
        }
        if ($potongan_absen[0]->type_nominal_fingerprint == 1) {
            $potfingerprint = $potongan_absen[0]->nominal_fingerprint;
        } else if ($potongan_absen[0]->type_nominal_fingerprint == 2) {
            $potfingerprint = ($potongan_absen[0]->nominal_fingerprint / 100 * $gapok);
        } else if ($potongan_absen[0]->type_nominal_fingerprint == 3) {
            $potfingerprint = ($gapok / 22);
        }
        $datapot['absen'] = $potabsen;
        $datapot['izin'] = $potizin;
        $datapot['mendahului'] = $potmendahului;
        $datapot['alpha'] = $potalpha;
        $datapot['fingerprint'] = $potfingerprint;
        //print_r($datapot);die;
        return view('backend.gaji.generate.data_gaji', compact('karyawan', 'generate', 'id_prl', 'id', 'data', 'help', 'datapot', 'sudah_appr'));
    }
    public function send_revisi(Request $request)
    {
        foreach ($request->get('summary') as $key => $value) {
            //echo $key.'='.$value.'<br>';
            $generate = DB::connection()->select("select * from prl_gaji_detail where prl_gaji_detail_id=$key");


            DB::connection()->table("prl_gaji_detail")
                ->where('gaji_absen_id', $generate[0]->gaji_absen_id)
                ->where('p_karyawan_id', $generate[0]->p_karyawan_id)
                ->where('prl_gaji_id', $generate[0]->prl_gaji_id)
                ->update([

                    "nominal" => $value,

                ]);
        }
        foreach ($request->get('potongan') as $key => $value) {
            $generate = DB::connection()->select("select * from prl_gaji_detail where prl_gaji_detail_id=$key");

            DB::connection()->table("prl_gaji_detail")
                ->where('m_potongan_id', $generate[0]->m_potongan_id)
                ->where('prl_potongan_id', $generate[0]->prl_potongan_id)
                ->where('p_karyawan_id', $generate[0]->p_karyawan_id)
                ->where('prl_gaji_id', $generate[0]->prl_gaji_id)
                ->update([

                    "nominal" => $value,

                ]);
        }
        return redirect()->route('be.previewgaji', 'non_ajuan')->with('success', ' Generate diubah!');
    }
    public function regenerate_field($id_generate)
    {
        $sqlperiode = "SELECT m_periode_absen.*,

		case when a.periode_gajian=0 then 'Pekanan' else 'Bulanan' end as tipe,
		a.tahun as tahun_gener,a.*,a.bulan as bulan_gener,b.tgl_awal as tgl_awal_lembur,b.tgl_akhir as tgl_akhir_lembur
		FROM prl_generate a
		join m_periode_absen on m_periode_absen.periode_absen_id = a.periode_absen_id
		join m_periode_absen b on b.periode_absen_id = a.periode_lembur_id
		where a.active = 1
		ORDER BY a.create_date desc, prl_generate_id desc";
        $periode = DB::connection()->select($sqlperiode);
        $tunjangan = DB::connection()->select("select * from m_tunjangan where active=1");
        $potongan = DB::connection()->select("select * from m_potongan where active=1");


        return view('backend.gaji.generate.regenerate_field', compact('periode', 'id_generate', 'tunjangan', 'potongan'));
    }
    public function regenerate_pajak($id_generate)
    {
    	$gaji = DB::connection()->select("select prl_gaji_detail.p_karyawan_id,nama from prl_gaji join prl_gaji_detail on prl_gaji.prl_gaji_id = prl_gaji_detail.prl_gaji_id 
		join p_karyawan on p_karyawan.p_karyawan_id = prl_gaji_detail.p_karyawan_id
		where prl_generate_id=$id_generate and appr_on_direktur_status=1
		group by prl_gaji_detail.p_karyawan_id,nama");
		$karyawan_approve = array();
		foreach($gaji as $gaji){
			$karyawan_approve[] = $gaji->p_karyawan_id;
			$karyawan_approve_nama[] = $gaji->nama;
		}
		
        DB::connection()->table("p_karyawan_gapok")
        	->whereNotIn('p_karyawan_id',$karyawan_approve)
            ->update([
                "generate_pajak" => 0,
            ]);
        return view('backend.gaji.generate.proses_generate_pajak', compact('id_generate'));
    }
    public function hitung_pajak($id_generate)
    {
    	//on koperasi
        $help = new Helper_function();
        $sqluser = "SELECT * FROM prl_generate 
        join m_periode_absen on m_periode_absen.periode_absen_id = prl_generate.periode_absen_id
         where prl_generate_id = $id_generate";
        $generate = DB::connection()->select($sqluser);
        $g = $generate[0];
        $periode_gajian = $g->periode_gajian;

        $sqluser = "SELECT * FROM prl_gaji where prl_generate_id = $id_generate";
        $gaji = DB::connection()->select($sqluser);
        $prl_gaji_id = array();
        foreach ($gaji as $gaji) {
            $prl_gaji_id[] = $gaji->prl_gaji_id;
        }
        //print_r($periode);die;



        $list_karyawan = "SELECT p_karyawan_koperasi.*,pajak_onoff,p_karyawan.nama,p_karyawan.nik,m_lokasi.nama as nmentitas, case when periode_gajian=1 then 'Bulanan ' else 'Pekanan' end as periode_gajian
from p_karyawan_koperasi
join p_karyawan on p_karyawan_koperasi.p_karyawan_id = p_karyawan.p_karyawan_id
left join p_karyawan_pekerjaan on p_karyawan_pekerjaan.p_karyawan_id=p_karyawan.p_karyawan_id
left join m_lokasi on p_karyawan_pekerjaan.m_lokasi_id=m_lokasi.m_lokasi_id
left join m_jabatan on m_jabatan.m_jabatan_id=p_karyawan_pekerjaan.m_jabatan_id
WHERE 1=1 and p_karyawan_koperasi.active=1 and p_karyawan.active=1 and nama_koperasi ='PAJAK'  and tgl_akhir >= '".$generate[0]->tgl_akhir."' and p_karyawan_koperasi.generate_pajak=0 and m_jabatan.m_pangkat_id not in(6) order by tgl_akhir, nama asc
			 ";
        $list_karyawan = DB::connection()->select($list_karyawan);
        foreach ($list_karyawan as $karyawan) {

            $gaji = DB::connection()->select("select * from prl_gaji_detail join prl_gaji on prl_gaji_detail.prl_gaji_id = prl_gaji.prl_gaji_id where prl_gaji.prl_generate_id = $id_generate  and prl_gaji_detail.p_karyawan_id =" . $karyawan->p_karyawan_id);

            $count = array();
            foreach ($gaji as $gaji) {
                if (isset($count[$gaji->prl_gaji_id]))
                    $count[$gaji->prl_gaji_id] += 1;
                else
                    $count[$gaji->prl_gaji_id] = 1;
            }

            asort($count);
            //print_r($count);
            foreach ($count as $x => $x_value) {
                $prl_gaji_id = $x;
            }


            $prl = DB::connection()->select("select * FROM prl_gaji_detail where m_potongan_id = 20 and type = 5 and p_karyawan_id = " . $karyawan->p_karyawan_id . " and prl_gaji_id = " . $prl_gaji_id . "  and active=1");
           
            //print_r($potongan);
            //echo '<br>';
            if (count($prl)) {
                //print_r($prl);
                DB::connection()->table("prl_gaji_detail")


                    ->where('p_karyawan_id', $karyawan->p_karyawan_id)
                    ->where('prl_gaji_id', $prl_gaji_id)
                    ->update([

                        "nominal" => $karyawan->nominal,
                        "type" => 5,
                        "m_potongan_id" => 20,
                        "active" => 1,
                        
                    ]);
            } else {
                DB::connection()->table("prl_gaji_detail")
                    ->insert([

                        "m_potongan_id" => 20,
                        "p_karyawan_id" => $karyawan->p_karyawan_id,
                        "prl_gaji_id" => $prl_gaji_id,
                        "nominal" => $karyawan->nominal,
                        "type" => 5,
                        "active" => 1,

                    ]);
            }

            DB::connection()->table("p_karyawan_koperasi")
                ->where('p_karyawan_id', $karyawan->p_karyawan_id)
                ->update([
                    "generate_pajak" => 1,
                ]);
        }
        $sqlgenerate = "SELECT 
			 count(*) as jumlah_semua_karyawan,
			 count(CASE WHEN generate_pajak = 1 THEN 1 END)  as yang_sudah 
			 	FROM p_karyawan_koperasi 
				 
				 where nama_koperasi ='PAJAK'";
        //////echo $sqluser;
        $generete = DB::connection()->select($sqlgenerate);
        $persen = round($generete[0]->yang_sudah / $generete[0]->jumlah_semua_karyawan * 100, 2);
        $init = ($generete[0]->jumlah_semua_karyawan - $generete[0]->yang_sudah) * 1.5;
        $hours = floor($init / 3600);
        $minutes = floor(($init / 60) % 60);
        $seconds = $init % 60;

        $Jam = $hours ? $hours . ' Jam ' : '';
        $menit = $minutes ? $minutes . ' Menit ' : '';
        $sekon = $seconds ? $seconds . ' Detik ' : '';
        //echo "$hours:$minutes:$seconds";
        echo '<div class="card">
										<div class="card-body text-center">
											<br>
											<h3>' . $persen . '%</h3>
											<h4 class="holiday-title mb-0">' . $generete[0]->yang_sudah . ' dari ' . $generete[0]->jumlah_semua_karyawan . '</h4>
											<div>' . $Jam . $menit . $sekon . ' </div>
											<div class="text-red" style="color:red;">PERINGATAN!!! TAB JANGAN DI TUTUP SEBELUM SELESAI</div>
										</div>
									</div>
									';
    }
    public function hitung_pajak_on_prl_potongan($id_generate)
    {
        $help = new Helper_function();
        $sqluser = "SELECT * FROM prl_generate where prl_generate_id = $id_generate";
        $generate = DB::connection()->select($sqluser);
        $g = $generate[0];
        $periode_gajian = $g->periode_gajian;

        $sqluser = "SELECT * FROM prl_gaji where prl_generate_id = $id_generate";
        $gaji = DB::connection()->select($sqluser);
        $prl_gaji_id = array();
        foreach ($gaji as $gaji) {
            $prl_gaji_id[] = $gaji->prl_gaji_id;
        }
        //print_r($periode);die;



        $list_karyawan = "select * from  p_karyawan
			left join p_karyawan_pekerjaan on p_karyawan_pekerjaan.p_karyawan_id = p_karyawan.p_karyawan_id
			left join p_karyawan_gapok on p_karyawan_gapok.p_karyawan_id = p_karyawan.p_karyawan_id 
			left join prl_potongan on prl_potongan.p_karyawan_id = p_karyawan.p_karyawan_id and  m_potongan_id = 20 and prl_potongan.active= 1
			 
			where periode_gajian=$periode_gajian
			and generate_pajak=0
			and nominal!=0
			limit 1
			
			 ";
        $list_karyawan = DB::connection()->select($list_karyawan);
        foreach ($list_karyawan as $karyawan) {

            $gaji = DB::connection()->select("select * from prl_gaji_detail join prl_gaji on prl_gaji_detail.prl_gaji_id = prl_gaji.prl_gaji_id where prl_gaji.prl_generate_id = $id_generate  and prl_gaji_detail.p_karyawan_id =" . $karyawan->p_karyawan_id);

            $count = array();
            foreach ($gaji as $gaji) {
                if (isset($count[$gaji->prl_gaji_id]))
                    $count[$gaji->prl_gaji_id] += 1;
                else
                    $count[$gaji->prl_gaji_id] = 1;
            }

            asort($count);
            //print_r($count);
            foreach ($count as $x => $x_value) {
                $prl_gaji_id = $x;
            }


            $prl = DB::connection()->select("select * FROM prl_gaji_detail where m_potongan_id = 20 and p_karyawan_id = " . $karyawan->p_karyawan_id . " and prl_gaji_id = " . $prl_gaji_id . "  and active=1");
            $potongan = DB::connection()->select("select * FROM prl_potongan where m_potongan_id = 20 and p_karyawan_id = " . $karyawan->p_karyawan_id . "  and active = 1");
            //print_r($potongan);
            //echo '<br>';
            if (count($prl)) {
                //print_r($prl);
                DB::connection()->table("prl_gaji_detail")


                    ->where('prl_potongan_id', $potongan[0]->prl_potongan_id)
                    ->where('p_karyawan_id', $karyawan->p_karyawan_id)
                    ->where('prl_gaji_id', $prl_gaji_id)
                    ->update([

                        "nominal" => $karyawan->nominal,
                        "type" => 4,
                        "active" => 1,
                    ]);
            } else {
                DB::connection()->table("prl_gaji_detail")
                    ->insert([

                        "prl_potongan_id" => $potongan[0]->prl_potongan_id,
                        "m_potongan_id" => 20,
                        "p_karyawan_id" => $karyawan->p_karyawan_id,
                        "prl_gaji_id" => $prl_gaji_id,
                        "nominal" => $karyawan->nominal,
                        "type" => 4,
                        "active" => 1,

                    ]);
            }

            DB::connection()->table("p_karyawan_gapok")
                ->where('p_karyawan_id', $karyawan->p_karyawan_id)
                ->update([
                    "generate_pajak" => 1,
                ]);
        }
        $sqlgenerate = "SELECT 
			 count(*) as jumlah_semua_karyawan,
			 count(CASE WHEN generate_pajak = 1 THEN 1 END)  as yang_sudah 
			 	FROM p_karyawan_gapok 
				 left join p_karyawan_pekerjaan on p_karyawan_pekerjaan.p_karyawan_id = p_karyawan_gapok.p_karyawan_id
			 	 
				 where periode_gajian = $periode_gajian and pajak!=0";
        //////echo $sqluser;
        $generete = DB::connection()->select($sqlgenerate);
        $persen = round($generete[0]->yang_sudah / $generete[0]->jumlah_semua_karyawan * 100, 2);
        $init = ($generete[0]->jumlah_semua_karyawan - $generete[0]->yang_sudah) * 1.5;
        $hours = floor($init / 3600);
        $minutes = floor(($init / 60) % 60);
        $seconds = $init % 60;

        $Jam = $hours ? $hours . ' Jam ' : '';
        $menit = $minutes ? $minutes . ' Menit ' : '';
        $sekon = $seconds ? $seconds . ' Detik ' : '';
        //echo "$hours:$minutes:$seconds";
        echo '<div class="card">
										<div class="card-body text-center">
											<br>
											<h3>' . $persen . '%</h3>
											<h4 class="holiday-title mb-0">' . $generete[0]->yang_sudah . ' dari ' . $generete[0]->jumlah_semua_karyawan . '</h4>
											<div>' . $Jam . $menit . $sekon . ' </div>
											<div class="text-red" style="color:red;">PERINGATAN!!! TAB JANGAN DI TUTUP SEBELUM SELESAI</div>
										</div>
									</div>
									';
    }
    public function regenerate($id_generate)
    {
        $sqlperiode = "SELECT * FROM prl_gaji where prl_generate_id=$id_generate";
        $sudahappr = DB::connection()->select($sqlperiode);
        foreach ($sudahappr as $apprs) {
            if ($apprs->appr_on_direktur_status == 1)
                $sudah_appr[$apprs->m_lokasi_id]['ON'] = 1;
            else
                $sudah_appr[$apprs->m_lokasi_id]['ON'] = 0;

            if ($apprs->appr_off_direktur_status == 1)
                $sudah_appr[$apprs->m_lokasi_id]['OFF'] = 1;
            else
                $sudah_appr[$apprs->m_lokasi_id]['OFF'] = 0;
        }
        $sql = "SELECT prl_generate.*,case when prl_generate.periode_gajian=0 then 'Pekanan' else 'Bulanan' end as tipe,
    	a.tgl_awal as tgl_awal_absen, 
    	a.tgl_akhir as tgl_akhir_absen, 
    	b.tgl_awal as tgl_awal_lembur, 
    	b.tgl_akhir as tgl_akhir_lembur
    	FROM prl_generate 
    		left join m_periode_absen a on prl_generate.periode_absen_id = a.periode_absen_id
    		left join m_periode_absen b on prl_generate.periode_lembur_id = b.periode_absen_id
    		
    		where prl_generate_id = $id_generate
    	";
        $generate = DB::connection()->select($sql);
        $periode_gajian = $generate[0]->periode_gajian;
        //echo $periode_gajian;die;
        $sql = "Select * from p_karyawan join p_karyawan_pekerjaan a on a.p_karyawan_id = p_karyawan.p_karyawan_id  
    			where p_karyawan.p_karyawan_id  in(select a.p_karyawan_id from prl_generate_karyawan where prl_generate_id = $id_generate and status = 1 and periode_gajian = $periode_gajian )
    			order by nama
    	";
        $karyawan = DB::connection()->select($sql);
        return view('backend.gaji.generate.regenerate', compact('karyawan', 'generate', 'id_generate', 'sudah_appr'));
    }
    public function tambah(Request $request)
    {
        $sqlperiode = "SELECT m_periode_absen.*,
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
		FROM m_periode_absen  where tipe_periode='absen' and active=1
		ORDER BY tahun desc,periode desc,type";
        $periode = DB::connection()->select($sqlperiode);
        $sqlperiode = "SELECT m_periode_absen.*,
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
		FROM m_periode_absen where tipe_periode='lembur' and active=1
		ORDER BY tahun desc,periode desc,type";
        $lembur = DB::connection()->select($sqlperiode);
        $lokasi = DB::connection()->select("select * from m_lokasi where active = 1 and sub_entitas=0");
        $iduser = Auth::user()->id;

        return view('backend.gaji.generate.tambah_generate', compact('periode', 'lembur', 'lokasi'));
    }
}

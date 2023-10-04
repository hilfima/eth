<?php

namespace App\Http\Controllers\Frontend;

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
	}

	public function laporan_cuti()
    {
    	$help = new Helper_function();
    	$iduser=Auth::user()->id;
        $sqlidkar="select * from p_karyawan 
        join p_karyawan_pekerjaan on p_karyawan.p_karyawan_id = p_karyawan_pekerjaan.p_karyawan_id
        where user_id=$iduser";
        $idkar=DB::connection()->select($sqlidkar);
        $id=$idkar[0]->p_karyawan_id;
		$date=array();
		$cuti = $help->query_cuti2($idkar);
			$date2 = $cuti['date'];
			$all = $cuti['all'];
			$tanggal_loop = $cuti['tanggal_loop'];
		
		
		//print_r($date);
		//$data[$]
		
        return view('frontend.cuti.laporan_cuti',compact('help','cuti','idkar','all','date','tanggal_loop'));
    }public function generate_cuti()
    {
    	$sqlfasilitas="SELECT * FROM p_karyawan 
				join p_karyawan_pekerjaan a on a.p_karyawan_id = p_karyawan.p_karyawan_id
				WHERE 1=1 and a.active=1 ";
        $karyawan=DB::connection()->select($sqlfasilitas);
        $iduser=Auth::user()->id;
    	$help = new Helper_function();
        foreach($karyawan as $karyawan){
        	$date 		= $karyawan->tgl_bergabung;
        	$dateBesar 	= $karyawan->tgl_bergabung;
        	$id_karyawan 	= $karyawan->p_karyawan_id;
        	$x = 1;
        	while($date <= date('Y-m-d')) {
        		if($x==1){
					$nominal=0;
				}else{
					$nominal=12;
				}
				if($x==3){
					$tahun_date = explode('-',($date))[0];
					$date = $tahun_date.'-1-1';
				}
					
					$generate_check = $help->tambah_bulan($date,12);
				
				$sqlfasilitas="SELECT * FROM m_cuti
		                WHERE 1=1 and active=1 and p_karyawan_id = $id_karyawan ";
		        $count=DB::connection()->select($sqlfasilitas);
		       
		        	
		        if(count($count)){
					DB::connection()->table("m_cuti")
						->where('m_cuti_id',$count[0]->m_cuti_id)
	               		->update([
	                  "tanggal"=>($date),
	                  "tgl_generete_add"=>($generate_check),
	                ]);
				}
				else{
					
				  DB::connection()->table("m_cuti")
	               	->insert([
	                 
	                  "p_karyawan_id"=>($karyawan->p_karyawan_id),
	                  "tgl_generete_add"=>($generate_check),
	                ]);
				}
				//dia akan direset kalau setelah desember adalah tanggal tahun
				//jenis 1 adalah penambahan plafon tahunan
				// jenis 2 adalah reset plafon
				$ex_date = explode('-',$date);
				$ex_generate_check = explode('-',$generate_check);
				$tgl_reset = $ex_generate_check[0].'-12-31';
				DB::connection()->table("t_cuti")
	               	->insert([
	                  "p_karyawan_id"=>($karyawan->p_karyawan_id),
	                  "nominal"=>($nominal),
	                  "keterangan"=>$x==1?'Karyawan Bergabung ke Perusahaan':'Penambahan Cuti Tahun '.$ex_date[0],
	                  "tanggal"=>($date),
	                  "jenis"=>$x==1?0:(1),
	                  "tgl_reset"=>$x==1?null:$tgl_reset,
	                  "tahun"=>$x==1?null:$ex_date[0],
	                  
	                  "create_date"=>date('Y-m-d'),
	                  "create_by"=>$iduser,
	                ]);
	                if($x!=1){
						
				DB::connection()->table("t_cuti")
	               	->insert([
	                  "p_karyawan_id"=>($karyawan->p_karyawan_id),
	                  "nominal"=>($nominal),
	                  "keterangan"=>$x==1?'Karyawan Bergabung ke Perusahaan':'Reset Cuti Tahunan '.$ex_date[0],
	                  "tanggal"=>($tgl_reset	),
	                  "jenis"=>$x==1?0:(2),
	                  "tgl_reset"=>$x==1?null:$tgl_reset,
	                  "tahun"=>$x==1?null:$ex_date[0],
	                  
	                  "create_date"=>date('Y-m-d'),
	                  "create_by"=>$iduser,
	                ]);
				
					}
				
				
				
				
				$x++;
				$date = $generate_check;
			}
			
			$date = $help->tambah_tanggal($dateBesar,1);
			$x = 1;
        	while($date < date('Y-m-d')) {
        		if($x==1){
					$nominal=0;
				}else{
					$nominal=10;
				}
				$generate_check = $help->tambah_bulan($date,12*5);
				$sqlfasilitas="SELECT * FROM m_cuti
		                WHERE 1=1 and active=1 and p_karyawan_id = $id_karyawan ";
		        $count=DB::connection()->select($sqlfasilitas);
		       
		        	
		        if(count($count)){
					DB::connection()->table("m_cuti")
						->where('m_cuti_id',$count[0]->m_cuti_id)
	               		->update([
	                  "tanggal"=>($date),
	                  "tgl_generete_add_besar"=>($generate_check),
	                ]);
				}
				else{
					
				  DB::connection()->table("m_cuti")
	               	->insert([
	                 
	                  "p_karyawan_id"=>($karyawan->p_karyawan_id),
	                  "tgl_generete_add_besar"=>($generate_check),
	                ]);
				}
				//dia akan direset kalau setelah desember adalah tanggal tahun
				//jenis 1 adalah penambahan plafon tahunan
				// jenis 2 adalah reset plafon
				$ex_date = explode('-',$date);
				$tgl_reset = $generate_check;
				if($x!=1){
					$ke = $x-1;
				DB::connection()->table("t_cuti")
	               	->insert([
	                  "p_karyawan_id"=>($karyawan->p_karyawan_id),
	                  "nominal"=>($nominal),
	                  "keterangan"=>'Penambahan Cuti Besar ke-'.$ke,
	                  "tanggal"=>($date),
	                  "jenis"=>$x==1?0:(3),
	                  "tgl_reset"=>$x==1?null:$tgl_reset,
	                  "tahun"=>$x==1?null:$ke,
	                  
	                  "create_date"=>date('Y-m-d'),
	                  "create_by"=>$iduser,
	                ]);
				DB::connection()->table("t_cuti")
	               	->insert([
	                  "p_karyawan_id"=>($karyawan->p_karyawan_id),
	                  "nominal"=>($nominal),
	                  "keterangan"=>'Reset Cuti Besar ke-'.$ke,
	                  "tanggal"=>($tgl_reset),
	                  "jenis"=>$x==1?0:(4),
	                  "tgl_reset"=>$x==1?null:$tgl_reset,
	                  "tahun"=>$x==1?null:$ke,
	                  
	                  "create_date"=>date('Y-m-d'),
	                  "create_by"=>$iduser,
	                ]);
				
				}
				
				
				
				
				$x++;
				$date = $generate_check;
			}
		}
    }public function generate_cuti2()
    {
    	$sqlfasilitas="SELECT * FROM p_karyawan 
				join p_karyawan_pekerjaan a on a.p_karyawan_id = p_karyawan.p_karyawan_id
				WHERE 1=1 and a.active=1 ";
        $karyawan=DB::connection()->select($sqlfasilitas);
        $iduser=Auth::user()->id;
    	$help = new Helper_function();
        foreach($karyawan as $karyawan){
        	$date 		= $karyawan->tgl_bergabung;
        	$dateBesar 	= $karyawan->tgl_bergabung;
        	$id_karyawan 	= $karyawan->p_karyawan_id;
        	$x = 1;
        	while($date <= date('Y-m-d')) {
        		if($x==1){
					$nominal=0;
				}else{
					$nominal=12;
				}
				$generate_check = $help->tambah_bulan($date,12);
				$sqlfasilitas="SELECT * FROM m_cuti
		                WHERE 1=1 and active=1 and p_karyawan_id = $id_karyawan ";
		        $count=DB::connection()->select($sqlfasilitas);
		       
		        	
		        if(count($count)){
					DB::connection()->table("m_cuti")
						->where('m_cuti_id',$count[0]->m_cuti_id)
	               		->update([
	                  "tanggal"=>($date),
	                  "tgl_generete_add"=>($generate_check),
	                ]);
				}
				else{
					
				  DB::connection()->table("m_cuti")
	               	->insert([
	                 
	                  "p_karyawan_id"=>($karyawan->p_karyawan_id),
	                  "tgl_generete_add"=>($generate_check),
	                ]);
				}
				//dia akan direset kalau setelah desember adalah tanggal tahun
				//jenis 1 adalah penambahan plafon tahunan
				// jenis 2 adalah reset plafon
				$ex_date = explode('-',$date);
				$ex_generate_check = explode('-',$generate_check);
				$tgl_reset = $ex_generate_check[0].'-12-31';
				DB::connection()->table("t_cuti")
	               	->insert([
	                  "p_karyawan_id"=>($karyawan->p_karyawan_id),
	                  "nominal"=>($nominal),
	                  "keterangan"=>$x==1?'Karyawan Bergabung ke Perusahaan':'Penambahan Cuti Tahun '.$ex_date[0],
	                  "tanggal"=>($date),
	                  "jenis"=>$x==1?0:(1),
	                  "tgl_reset"=>$x==1?null:$tgl_reset,
	                  "tahun"=>$x==1?null:$ex_date[0],
	                  
	                  "create_date"=>date('Y-m-d'),
	                  "create_by"=>$iduser,
	                ]);
	                if($x!=1){
						
				DB::connection()->table("t_cuti")
	               	->insert([
	                  "p_karyawan_id"=>($karyawan->p_karyawan_id),
	                  "nominal"=>($nominal),
	                  "keterangan"=>$x==1?'Karyawan Bergabung ke Perusahaan':'Reset Cuti Tahunan '.$ex_date[0],
	                  "tanggal"=>($tgl_reset	),
	                  "jenis"=>$x==1?0:(2),
	                  "tgl_reset"=>$x==1?null:$tgl_reset,
	                  "tahun"=>$x==1?null:$ex_date[0],
	                  
	                  "create_date"=>date('Y-m-d'),
	                  "create_by"=>$iduser,
	                ]);
				
					}
				
				
				
				
				$x++;
				$date = $generate_check;
			}
			
			$date = $help->tambah_tanggal($dateBesar,1);
			$x = 1;
        	while($date < date('Y-m-d')) {
        		if($x==1){
					$nominal=0;
				}else{
					$nominal=10;
				}
				$generate_check = $help->tambah_bulan($date,12*5);
				$sqlfasilitas="SELECT * FROM m_cuti
		                WHERE 1=1 and active=1 and p_karyawan_id = $id_karyawan ";
		        $count=DB::connection()->select($sqlfasilitas);
		       
		        	
		        if(count($count)){
					DB::connection()->table("m_cuti")
						->where('m_cuti_id',$count[0]->m_cuti_id)
	               		->update([
	                  "tanggal"=>($date),
	                  "tgl_generete_add_besar"=>($generate_check),
	                ]);
				}
				else{
					
				  DB::connection()->table("m_cuti")
	               	->insert([
	                 
	                  "p_karyawan_id"=>($karyawan->p_karyawan_id),
	                  "tgl_generete_add_besar"=>($generate_check),
	                ]);
				}
				//dia akan direset kalau setelah desember adalah tanggal tahun
				//jenis 1 adalah penambahan plafon tahunan
				// jenis 2 adalah reset plafon
				$ex_date = explode('-',$date);
				$tgl_reset = $generate_check;
				if($x!=1){
					$ke = $x-1;
				DB::connection()->table("t_cuti")
	               	->insert([
	                  "p_karyawan_id"=>($karyawan->p_karyawan_id),
	                  "nominal"=>($nominal),
	                  "keterangan"=>'Penambahan Cuti Besar ke-'.$ke,
	                  "tanggal"=>($date),
	                  "jenis"=>$x==1?0:(3),
	                  "tgl_reset"=>$x==1?null:$tgl_reset,
	                  "tahun"=>$x==1?null:$ke,
	                  
	                  "create_date"=>date('Y-m-d'),
	                  "create_by"=>$iduser,
	                ]);
				DB::connection()->table("t_cuti")
	               	->insert([
	                  "p_karyawan_id"=>($karyawan->p_karyawan_id),
	                  "nominal"=>($nominal),
	                  "keterangan"=>'Reset Cuti Besar ke-'.$ke,
	                  "tanggal"=>($tgl_reset),
	                  "jenis"=>$x==1?0:(4),
	                  "tgl_reset"=>$x==1?null:$tgl_reset,
	                  "tahun"=>$x==1?null:$ke,
	                  
	                  "create_date"=>date('Y-m-d'),
	                  "create_by"=>$iduser,
	                ]);
				
				}
				
				
				
				
				$x++;
				$date = $generate_check;
			}
		}
    }
}
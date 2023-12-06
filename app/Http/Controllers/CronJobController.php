<?php

namespace App\Http\Controllers;

use App\User;
use App\Helper_function;
use Illuminate\Http\Request;
use DB;
use Auth;
use Hash;
class CronJobController extends Controller
{
    public static function generate_hari_ini(){
		Helper_function::generate_rekap_absen_tanggal(date('Y-m-d'));
	 
	 
	 
 	}public static function generate_absen_tanggal($tanggal){
		Helper_function::generate_rekap_absen_tanggal($tanggal);
 	}
 	public  function generate_rekap_absen_hari_ini($tanggal){
	    
	    
	        
	        
            // DB::connection()->table("absen_master_generate")->where("periode_absen_id",$periode_absen)->update(["active"=>0]);
            DB::connection()->table("absen_master_generate_tanggal")->where("tanggal",$tanggal)->update(["active"=>0]);
            DB::connection()->table("absen_master_rekap")->where("tanggal",$tanggal)->update(["active"=>0]);
	        
	           // $date = $periode[0]->tgl_awal;
    	       // for($i=0;$i<$help->hitungHari($periode[0]->tgl_awal,$periode[0]->tgl_akhir);$i++){
    	            
    	            $data_tanggal['tanggal'] = $tanggal;
    	            $data_tanggal['status'] = 0;
    	            $data_tanggal['create_date'] = date('Y-m-d H:i:s');
    	            DB::connection()->table("absen_master_generate_tanggal")->insert($data_tanggal);
	        // foreach($karyawan as $list_karyawan){
    	    //         $data['p_karyawan_id'] = $list_karyawan->p_karyawan_id;
    	    //        // $data['tanggal'] = $date;
    	    //         $data['status'] = 0;
    	    //         $data['periode_absen_id'] = $periode_absen;
    	    //         $data['create_date'] = date('Y-m-d H:i:s');
            //         DB::connection()->table("absen_master_generate")->insert($data);
    	            
    	    //     }
    	       
    	   //         $date=$help->tambah_tanggal($date,1);
	       // }
	    //}
	    //}
	}
    public static function hitung_rekap_absen_hari_ini($tanggal){
		$help = new Helper_function();
		if($tanggal==-1){
			$tanggal=date('Y-m-d');
		}
	    $help->generate_rekap_absen_tanggal($tanggal);
	
 	}
 	public static function generate_sp_st(){
 		$karyawan = "select * from p_karyawan where active=1";
 		$karyawan = DB::connection()->select($karyawan);
 		foreach($karyawan as $karyawan){
 			$m = date('m');
 			DB::table("queue")->insert([
 				"function_call"=>"sp_st_auto",
 				"parameter_1"=>$karyawan->p_karyawan_id,
 				"parameter_2"=>date("Y-").$m-1."01",
 				"parameter_3"=>date('Y-m-')."01",
 				
 			]);
 			
 		}
	}
	public function generate_jam_finger ()
	{
	    
	    $help = new Helper_function();
	   
	   $month  = date('m');
	   $year  = date('Y');
	   $month = $month-1;
	   
	   $permit = DB::connection()->select("select * from t_permit 
	                                left join p_karyawan_pekerjaan on t_permit.p_karyawan_id = p_karyawan_pekerjaan.p_karyawan_id 
	                                where generate_jam_finger!=4 and tgl_awal >= '$year-$month-24'and m_jenis_ijin_id = 22 ");
	   //print_r($permit);die;
	   //1 : belum keduanya
	   //2 : belum keluar
	   //3 : belum masuk
	   //4  : udah semuanya
	   foreach($permit as $permit)
	   {
	        $data= array();
	        $status_masuk=0;
	        $status_keluar=0;
	        $rekap = $rekap = $help->rekap_absen($permit->tgl_awal,$permit->tgl_akhir,$permit->tgl_awal,$permit->tgl_akhir,$permit->periode_gajian,null,$permit->p_karyawan_id);
	        echo $permit->generate_jam_finger;
	        if(isset($rekap[$permit->p_karyawan_id][$permit->tgl_awal]['a']['masuk']) and ($permit->generate_jam_finger==1 or $permit->generate_jam_finger==3 )){
	            $data['jam_masuk_finger']  = $rekap[$permit->p_karyawan_id][$permit->tgl_awal]['a']['masuk'];
	            $status_masuk = 1;
	        }
	        if(isset($rekap[$permit->p_karyawan_id][$permit->tgl_awal]['a']['keluar'])  and ($permit->generate_jam_finger==1 or $permit->generate_jam_finger==2 )){
	           
	            $data['jam_keluar_finger']  = $rekap[$permit->p_karyawan_id][$permit->tgl_awal]['a']['keluar'];
	            $status_keluar = 1; 
	        }
	        if(count($data)){
	            if($status_masuk and $status_keluar){
	                $data['generate_jam_finger'] = 4;
	            }else if($status_masuk and !$status_keluar){
	                $data['generate_jam_finger'] = 2; 
	            }else if(!$status_masuk and $status_keluar){
	                $data['generate_jam_finger'] = 3; 
	            }
	            print_r($data);
	            echo '<br>';
	            echo '<br>';
    	        DB::connection()->table("t_permit")
        			->where("t_form_exit_id",$permit->t_form_exit_id)
        			->update($data);
	        }
		   
	   }
	}
	
	public function generate_jam_finger_klarifikasi()
	{
	    
	    $help = new Helper_function();
	   echo 'hallow';
	   $month  = date('m');
	   $year  = date('Y');
	   $month = $month-3;
	   
	   $permit = DB::connection()->select("select * from chat_room 
	                               where tanggal >= '$year-$month-24' and selesai!=3 and tujuan in(1,2,3,4,5) ");
	   //print_r($permit);die;
	   //1 : belum keduanya
	   //2 : belum keluar
	   //3 : belum masuk
	   //4  : udah semuanya
	   foreach($permit as $permit)
	   {
	        $data= array();
	        $status_masuk=0;
	        $status_keluar=0;
	        $rekap = $rekap = $help->rekap_absen($permit->tanggal,$permit->tanggal,$permit->tanggal,$permit->tanggal,-1,null,$permit->p_karyawan_create_id);
	       
	        if(isset($rekap[$permit->p_karyawan_create_id][$permit->tanggal]['a']['masuk'])){
	            $data['jam_masuk_finger']  = $rekap[$permit->p_karyawan_create_id][$permit->tanggal]['a']['masuk'];
	            $data['selesai']=3;
				print_r($permit);
				echo '<br>';
				DB::connection()->table("chat_room")
        			->where("chat_room_id",$permit->chat_room_id)
        			->update($data);
	            $status_masuk = 1;
	        }
	        
	        
		   
	   }
	}
	}
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

	public function generate_jam_finger ()
	{
	    
	    $help = new Helper_function();
	   
	   $month  = date('m');
	   $year  = date('Y');
	   $month = $month-1;
	   
	   $permit = DB::connection()->select("select * from t_permit 
	                                left join p_karyawan_pekerjaan on t_permit.p_karyawan_id = p_karyawan_pekerjaan.p_karyawan_id 
	                                where generate_jam_finger!=4 and tgl_awal >= '$year-$month-24' ");
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
	}public static function hitung_rekap_absen_hari_ini($tanggal){
		$help = new Helper_function();
		if($tanggal==-1){
			$tanggal=date('Y-m-d');
		}
	    $help->generate_rekap_absen_tanggal($tanggal);
	    
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
<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Auth;

class Struktur_organisasiController extends Controller
{
    public function index(Request $request)
    {
    	
    	
      $e = $request->get('entitas');
      
      if($e){
	
		$struktur = $this->hirarki(0,$e);
	  }else{
	  	
		$struktur = $this->hirarki(-1); 
	  }
         $sqljabatan="SELECT * FROM m_lokasi where sub_entitas = 0 ";
      	 //echo $sqljabatan;
      	 $entitas=DB::connection()->select($sqljabatan);
      	 $no_js ='';
        return view('backend.struktur_organisasi.struktur_organisasi',compact('struktur','entitas','e','no_js'));
    }
    public function hirarki($id,$e=null)
    {
    		
		$filter_Entitas = '';
		if($e)
		$filter_Entitas = 'and b.m_lokasi_id = '.$e;
			
      	 $sqljabatan="SELECT *,(select count(*) from m_jabatan_atasan where m_jabatan_atasan.m_atasan_id = a.m_jabatan_id) as countjabatan
      	 FROM m_jabatan_atasan a 
      	 join m_jabatan b on b.m_jabatan_id = a.m_jabatan_id 
      	 where m_atasan_id = $id $filter_Entitas";
      	//echo $sqljabatan;
      	 $jabatan=DB::connection()->select($sqljabatan);
		  $return = array(); 
      	 if($id==-1)
		 	$return[] = array("name"=>"Ethics Group","title"=>"","children"=>$this->hirarki(0,$e));
      	
      	 foreach($jabatan as $j){
      	 	$Mjabatan = $j-> m_jabatan_id;
      	 	$sqljabatan="SELECT * FROM p_karyawan_pekerjaan a 
	      	 join p_karyawan b on b.p_karyawan_id = a.p_karyawan_id 
	      	 where m_jabatan_id = $Mjabatan and b.active=1";
	      	 
	      	 //echo $sqljabatan;
	      	 $karyawan=DB::connection()->select($sqljabatan);
	      	 $name='';
	      	 if(count($karyawan)){
			 	
      	 	foreach($karyawan as $k){
				$name = $k->nama;
				if($j->countjabatan )
      				$return[] = array("name"=>$j->nama,"title"=>$name,"children"=>$this->hirarki($j->m_jabatan_id));
		 		else 
      			$return[] = array("name"=>$j->nama,"title"=>$name);
				if($name)
					$name .='  <br>';
				$name = $k->nama;
			}
			 }else{
			 	
      	 	if($j->countjabatan)
      		$return[] = array("name"=>$j->nama,"title"=>$name,"children"=>$this->hirarki($j->m_jabatan_id));
		 	else 
      		$return[] = array("name"=>$j->nama,"title"=>$name);
			 }
		 	
		 }
		 return $return; 
		// print_r($return);
		
    }
}
<?php

namespace App\Http\Controllers\Frontend;
use App\Http\Controllers\Controller;

use App\rekaplembur_xls;
use App\User;
use App\Helper_function;
use Illuminate\Http\Request;
use DB;
use Auth;
use PhpOffice\PhpSpreadsheet\Reader\Xml\Style\Fill;
use Response;

class PekerjaanController extends Controller
{
	public function __construct()
	{
		$this->middleware('auth');
	}
	public function struktur_organisasi(Request $request)
    {
    	$iduser=Auth::user()->id;
        $sqlidkar="select * from p_karyawan 
        	join p_karyawan_pekerjaan on p_karyawan_pekerjaan.p_karyawan_id = p_karyawan.p_karyawan_id
        where user_id=$iduser";
        $idkar=DB::connection()->select($sqlidkar);
       // echo 'id_jabatan='.$idkar[0]->m_jabatan_id;
        //echo '<br>';
		
    	
      $e = $idkar[0]->m_lokasi_id;
      
      if($e){
	
		$struktur = $this->hirarki(0,$e);
	  }else{
	  	
		$struktur = $this->hirarki(-1);
	  }
         $sqljabatan="SELECT * FROM m_lokasi where sub_entitas = 0 ";
      	 //echo $sqljabatan;
      	 $entitas=DB::connection()->select($sqljabatan);
      	 $nojs ='';
        return view('frontend.struktur.struktur',compact('struktur','entitas','e','nojs'));
    }public function hirarki($id,$e=null)
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
		
    }public function jobdesk(Request $request)
    {
    	
		
    	$iduser=Auth::user()->id;
        $sqlidkar="select * from p_karyawan 
        	join p_karyawan_pekerjaan on p_karyawan_pekerjaan.p_karyawan_id = p_karyawan.p_karyawan_id
        where user_id=$iduser";
        $idkar=DB::connection()->select($sqlidkar);
       // echo 'id_jabatan='.$idkar[0]->m_jabatan_id;
        //echo '<br>';
		$id_karyawan = $idkar[0]->p_karyawan_id;
		$bawahan = "(select m_jabatan_terkait from m_jabatan_struktural where tipe_struktural=2 and m_jabatan_id = (select m_jabatan_id from p_karyawan_pekerjaan pkp where p_karyawan_id = $id_karyawan))";
		
		$hi = $idkar[0]->m_jabatan_id;
		//if($bawahan)
		// $hi .= ','.$bawahan;
         $sqljabatan="SELECT * FROM m_jabatan where (m_jabatan_id in ($bawahan) or m_jabatan_id = ($hi)  ) ";
      	 //echo $sqljabatan;
      	 $jabatan=DB::connection()->select($sqljabatan);
      	 $nojs ='';
        return view('frontend.struktur.jobdesk',compact('jabatan'));
    }
	
}
<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Auth;
use App\Helper_function;

class JabatanStrukturalController extends Controller
{
	public function __construct()
    {
        $this->middleware('auth');
    }public function view_jabatan_struktural(){
    	$help = new Helper_function();
    	$help->jabatan_struktural(95);
    }public function jabatan_struktural(){
		
		$jabatan_struktrular = "select a.nama as nm_jabatan,tipe_struktural,b.nama as jabatan_terkait,m_jabatan_struktural_id,c.nama as namaentitas,d.nama as namaentitas2
		
		  from m_jabatan_struktural 
				join m_jabatan a on m_jabatan_struktural.m_jabatan_id 		=a.m_jabatan_id
				join m_jabatan b on m_jabatan_struktural.m_jabatan_terkait 	=b.m_jabatan_id
				join m_lokasi c on a.m_lokasi_id = c.m_lokasi_id
				join m_lokasi d on b.m_lokasi_id = d.m_lokasi_id
			";
			$jabatan_struktrular=DB::connection()->select($jabatan_struktrular);
		return view('backend.jabatan_struktural.jabatan_struktural',compact('jabatan_struktrular'));
	}
	public function simpan_jabatan_struktural(Request $request){
		
		
		try{
       		DB::connection()->table("m_jabatan_struktural")
		                ->insert([
		                    "m_jabatan_id"=>($request->get('jabatan')),
		                    "m_jabatan_terkait"=>($request->get('terkait')),
		                    "tipe_struktural"=>$request->get('status')
		                ]);
				return redirect()->route('be.jabatan_struktural')->with('success',' Jabatan Struktural Berhasil di input!');
        }
        catch(\Exeception $e){
         
            return redirect()->back()->with('error',$e);
        }
	}
	public function hapus_jabatan_struktural($id){
		
		
		try{
       		DB::connection()->table("m_jabatan_struktural")
       					->where("m_jabatan_struktural_id",$id)
		                ->delete();
				return redirect()->route('be.jabatan_struktural')->with('success',' Jabatan Struktural Berhasil di input!');
        }
        catch(\Exeception $e){
         
            return redirect()->back()->with('error',$e);
        }
	}
	public function tambah_jabatan_struktural(){
		$jabatan = "select *,b.nama as nama_jabatan,d.nama as namaentitas ,d.nama as namaentitas2  from m_jabatan b
				join m_lokasi d on b.m_lokasi_id = d.m_lokasi_id where b.active=1 ORDER BY b.nama";
		$jabatan=DB::connection()->select($jabatan);
		return view('backend.jabatan_struktural.tambah_jabatan_struktural',compact('jabatan'));
	}
	public function generate(){
		
		$sql="SELECT m_jabatan_id from m_jabatan  where active=1 ";
        $jabatan=DB::connection()->select($sql);
        DB::connection()->table("m_jabatan_struktural")
		                ->delete();
        foreach($jabatan as $jabatan){
			JabatanStrukturalController::jabatan_atasan($jabatan->m_jabatan_id,$jabatan->m_jabatan_id);
			JabatanStrukturalController::jabatan_bawahan($jabatan->m_jabatan_id,$jabatan->m_jabatan_id);
			JabatanStrukturalController::jabatan_sejajar($jabatan->m_jabatan_id,$jabatan->m_jabatan_id);
        	
		}

	}
	
	public function generate_jabatan_atasan($id){
		
		$sql="SELECT m_jabatan_id from m_jabatan  where active=1 and m_jabatan_id=$id";
        $jabatan=DB::connection()->select($sql);
        DB::connection()->table("m_jabatan_struktural")
        				->where('m_jabatan_id',$id)
		                ->delete();
		               
        foreach($jabatan as $jabatan){
			JabatanStrukturalController::jabatan_atasan($jabatan->m_jabatan_id,$jabatan->m_jabatan_id);
			JabatanStrukturalController::jabatan_bawahan($jabatan->m_jabatan_id,$jabatan->m_jabatan_id);
			JabatanStrukturalController::jabatan_sejajar($jabatan->m_jabatan_id,$jabatan->m_jabatan_id);
        	
		}

	}
	public function jabatan_atasan($idj,$id){
		
		
			
      	 $sqljabatan="SELECT *
      	 FROM m_jabatan_atasan a 
      	 join m_jabatan b on b.m_jabatan_id = a.m_jabatan_id 
      	 where a.m_jabatan_id = $id ";
      	//echo $sqljabatan;
      	 $jabatan=DB::connection()->select($sqljabatan);
		  
      	$e='';
      	 foreach($jabatan as $j){
      	 	$Mjabatan = $j-> m_jabatan_id;
      	 	
				//echo $k->p_karyawan_id;
				//echo '<br>';
				
					
					$e .= $j->m_jabatan_id.',';
					
					 DB::connection()->table("m_jabatan_struktural")
		                ->insert([
		                    "m_jabatan_id"=>($idj),
		                    "m_jabatan_terkait"=>($j->m_atasan_id),
		                    "tipe_struktural"=>1
		                ]);
				
				
				
				if($j->m_atasan_id){
      				$e .= $this->jabatan_atasan($idj,$j->m_atasan_id,$e);
				}
      		//$this->hirarki($j->m_jabatan_id,$e);
		 	 
      		
			 }
		 	
		 
		// echo '<br>';
		 //echo '<br>';
		// print_r($e);
		 return $e;
	}
	public function jabatan_bawahan($idj,$id)
    {
    		//echo 'e adalah'.$e.'id adalah '.$id.'<br>';
		
		
			
      	 $sqljabatan="SELECT *,(select count(*) from m_jabatan_atasan where m_jabatan_atasan.m_atasan_id = a.m_jabatan_id) as countjabatan
      	 FROM m_jabatan_atasan a 
      	 join m_jabatan b on b.m_jabatan_id = a.m_jabatan_id 
      	 where m_atasan_id = $id ";
      //	echo $sqljabatan;
      	//echo '<br>';
      //	echo '<br>';
       $jabatan=DB::connection()->select($sqljabatan);
		  $return = array(); 
      	
      	$e = '';
      	 foreach($jabatan as $j){
      	 	$Mjabatan = $j-> m_jabatan_id;
      	 	
				
					 DB::connection()->table("m_jabatan_struktural")
		                ->insert([
		                    "m_jabatan_id"=>($idj),
		                    "m_jabatan_terkait"=>($j->m_jabatan_id),
		                    "tipe_struktural"=>2
		                ]);
      			if($j->countjabatan ){	
      				$e .= $this->jabatan_bawahan($idj,$j->m_jabatan_id,$e);
				}
		 		
				
			
			}
      		//$this->hirarki($j->m_jabatan_id,$e);
		 	 
      		
			
		 	
		
		// echo '<br>';
		 //echo '<br>';
		// print_r($e);
		 return $e; 
		// print_r($return);
		
    }
	public function jabatan_sejajar($idj,$id)
    {
    		//echo 'e adalah'.$e.'id adalah '.$id.'<br>';
		
		
			
      	 $sqljabatan="SELECT *,(select count(*) from m_jabatan_atasan where m_jabatan_atasan.m_atasan_id = a.m_jabatan_id) as countjabatan
      	 FROM m_jabatan_atasan a 
      	 join m_jabatan b on b.m_jabatan_id = a.m_jabatan_id 
      	 where m_atasan_id = (select DISTINCT(m_atasan_id) from m_jabatan_atasan where m_jabatan_id =$id) ";
      	//echo $sqljabatan;
      	 $jabatan=DB::connection()->select($sqljabatan);
		  $return = array(); 
      	
      	$e = '';
      	 foreach($jabatan as $j){
      	 	$Mjabatan = $j-> m_jabatan_id;
      	 	
				
					 DB::connection()->table("m_jabatan_struktural")
		                ->insert([
		                    "m_jabatan_id"=>($idj),
		                    "m_jabatan_terkait"=>($j->m_jabatan_id),
		                    "tipe_struktural"=>3
		                ]);
      				if($j->countjabatan ){
      				//$e .= $this->jabatan_bawahan($idj,$j->m_jabatan_id,$e);
				}
		 		
				
			
			}
      		//$this->hirarki($j->m_jabatan_id,$e);
		 	 
      		
			
		 	
		
		// echo '<br>';
		 //echo '<br>';
		// print_r($e);
		 return $e; 
		// print_r($return);
		
    }
}
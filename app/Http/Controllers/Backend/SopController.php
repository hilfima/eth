<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use DB;

class SopController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
   public function sop()
    {
    	$sop=DB::connection()->select("select * from sop 
		
		where sop.active =1 ");
        return view('backend.sop.sop',compact('sop'));
    }
    public function simpan_sop(Request $request)
    {  
    	 DB::beginTransaction();
        try{
        	
			$sop=DB::connection()->select("select max(sop_id) from sop");
			$id = $sop[0]->max+1;
    		 DB::connection()->table("sop")
                    ->insert([
					"sop_id"=>$id,
                        "judul_sop"=>$request->get('judul'),
                        "deskripsi"=>$request->get('deskripsi_sop'),
						
                    ]);
                    $dept = $request->get('dept');
                    if(!empty($dept)){
                    	if(count($dept)){
				             for($i=0;$i<count($dept);$i++){
				             	DB::connection()->table("sop_dept")
				                    ->insert([
									"sop_id"=>$id,
				                    "m_departement_id"=>$dept[$i],
				                ]);
				                    
				            }
						}
					}       
			if ($request->file('file')) {
						//echo 'masuk';die;
						$file = $request->file('file');
						$destination="dist/img/file/";
						$path='sop-'.date('ymdhis').'-'.$file->getClientOriginalName();
						$file->move($destination,$path);
						//echo $path;die;
						DB::connection()->table("sop")
						->where("sop_id",$id)
						->update([
							"file"=>$path
						]);
					}
					
    		DB::commit();
            return redirect()->route('be.sop')->with('success','Sop / IK Berhasil di simpan!');
        }
        catch(\Exeception $e){
            DB::rollback();
            return redirect()->back()->with('error',$e);
        }
    }public function update_sop(Request $request,$id)
    {  
    	 DB::beginTransaction();
        try{
    		 DB::connection()->table("sop")
                    ->where('sop_id',$id)
                    ->update([
                        "judul_sop"=>$request->get('judul'),
                        "deskripsi"=>$request->get('deskripsi_sop'),
                        
                    ]);
    		 DB::connection()->table("sop_dept")
                    ->where('sop_id',$id)
                    ->update([
                        "active"=>0,
                    ]);	
            $dept = $request->get('dept');
                    if(count($dept)){
                    	
			             for($i=0;$i<count($dept);$i++){
			             	DB::connection()->table("sop_dept")
			                    ->insert([
								"sop_id"=>$id,
			                    "m_departement_id"=>$dept[$i],
			                ]);
			                    
			            }
					}             
             if ($request->file('file')) {
						//echo 'masuk';die;
						$file = $request->file('file');
						$destination="dist/img/file/";
						$path='sop-'.date('ymdhis').'-'.$file->getClientOriginalName();
						$file->move($destination,$path);
						//echo $path;die;
						DB::connection()->table("sop")
						->where("sop_id",$id)
						->update([
							"file"=>$path
						]);
					}       
    		DB::commit();
            return redirect()->route('be.sop')->with('success','Sop / IK Berhasil di simpan!');
        }
        catch(\Exeception $e){
            DB::rollback();
            return redirect()->back()->with('error',$e);
        }
    }public function hapus_sop(Request $request,$id)
    {  
    	 DB::beginTransaction();
        try{
    		 DB::connection()->table("sop")
                    ->where('sop_id',$id)
                    ->update([
                        "active"=>0,
                        
                    ]);
    	
    		DB::commit();
            return redirect()->route('be.sop')->with('success','Sop / IK Berhasil di simpan!');
        }
        catch(\Exeception $e){
            DB::rollback();
            return redirect()->back()->with('error',$e);
        }
    }
    public function tambah_sop()
    { 
		$departement =DB::connection()->select("select * from m_departemen where active =1");
    	return view('backend.sop.tambah_sop',compact('departement'));
    }public function reader_sop($id)
    { 
    	$sop=DB::connection()->select("select * from sop_reader join p_karyawan on sop_reader.p_karyawan_id = p_karyawan.p_karyawan_id where active =1 and sop_id=$id");
    	return view('backend.sop.reader_sop',compact('sop'));
    }
    public function edit_sop($id)
    { 
    	$sop=DB::connection()->select("select * from sop where active =1 and sop_id=$id");
    	$sop_dept=DB::connection()->select("select * from sop_dept where active=1 and sop_id=$id"); 
    	$sopdept=array();
    	foreach($sop_dept as $sop_dept){
    		$sopdept[] = $sop_dept->m_departement_id;
    	} 
    	$departement =DB::connection()->select("select * from m_departemen where active =1");
    	return view('backend.sop.edit_sop',compact('sop','sopdept','departement'));
    }
}
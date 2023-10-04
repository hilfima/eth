<?php

namespace App\Http\Controllers\Backend\Gaji;

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

class Master_potonganController extends Controller
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

    public function master_potongan (Request $request)
    {
    	
		 $iduser=Auth::user()->id;
      	$sqluser="SELECT p_recruitment.foto FROM users
			left join p_karyawan on p_karyawan.user_id=users.id
			left join p_recruitment on p_recruitment.p_recruitment_id=p_karyawan.p_karyawan_id
			where users.id=$iduser"; $user=DB::connection()->select($sqluser);
		$title='master potongan';
		$sql="SELECT * FROM m_potongan"; 
		$row=DB::connection()->select($sql);
        return view('backend.gaji.master.potongan.potongan',compact('user','row','title'));
    } public function tambah(){
		$data['nama']='';
		$data['nominal']='';
		
		$data['active']='';
		$id='';
		$title='master potongan';
		$type='simpan_'.str_replace(' ','_',$title);
		$page='Tambah';
		return view('backend.gaji.master.potongan.tambah_edit_potongan',compact('data','type','id','page','title','title'));
	}public function edit($id){
		$page='Edit';
		$title='master potongan';
		$type='update_'.str_replace(' ','_',$title);
		$sql = "SELECT * FROM m_potongan where m_potongan_id=$id ";
    	$cos=DB::connection()->select($sql);
		$data['nama']=$cos[0]->nama;
		$data['nominal']=$cos[0]->nominal;
		
		$data['active']=$cos[0]->active;
		
		return view('backend.gaji.master.potongan.tambah_edit_potongan',compact('data','type','id','page','title'));
	}public function simpan(Request $request){
		$title='master potongan';
        DB::beginTransaction();
        try{
            $idUser=Auth::user()->id;
            DB::connection()->table("m_potongan")
                ->insert([
                    "nama"=>($request->get("nama")),
                    "nominal"=>($request->get("nominal")),
                    "active"=>(1),
                   
                    "create_date" => date("Y-m-d H:i:s")
                ]);
            DB::commit();
            return redirect()->route('be.'.str_replace(' ','_',$title))->with('success',ucwords($title).' Berhasil di input!');
        }
        catch(\Exeception $e){
            DB::rollback();
            return redirect()->back()->with('error',$e);
        }

    }public function update(Request $request, $id){
		$title='master potongan';
        DB::beginTransaction();
        try{
            $idUser=Auth::user()->id;
            DB::connection()->table("m_potongan")
                ->where("m_potongan_id",$id)
                ->update([
                    "nama"=>($request->get("nama")),
                    "nominal"=>($request->get("nominal")),
                    
                    "update_date" => date("Y-m-d H:i:s")
                ]);
            DB::commit();
            return redirect()->route('be.'.str_replace(' ','_',$title))->with('success',ucwords($title).' Berhasil di ubah!');
     
        } catch(\Exeception $e){
            DB::rollback();
            return redirect()->back()->with('error',$e);
        }
        } 
    public function hapus($id)
    {
		$title='master potongan';
        DB::beginTransaction();
        try{
            DB::connection()->table("m_potongan")
                ->where("m_potongan_id",$id)
                ->delete();
            DB::commit();
            return redirect()->route('be.'.str_replace(' ','_',$title))->with('success',ucwords($title).' Berhasil dihapus!');
        }
        catch(\Exeception $e){
            DB::rollback();
            return redirect()->back()->with('error',$e);
        }
    }
} 
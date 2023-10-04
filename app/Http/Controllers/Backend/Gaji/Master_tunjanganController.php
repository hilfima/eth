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

class Master_TunjanganController extends Controller
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

    public function master_tunjangan (Request $request)
    {
    	
		 $iduser=Auth::user()->id;
      	$sqluser="SELECT p_recruitment.foto FROM users
			left join p_karyawan on p_karyawan.user_id=users.id
			left join p_recruitment on p_recruitment.p_recruitment_id=p_karyawan.p_karyawan_id
			where users.id=$iduser"; $user=DB::connection()->select($sqluser);

		$sql="SELECT * FROM m_tunjangan"; 
		$row=DB::connection()->select($sql);
        return view('backend.gaji.master.tunjangan.tunjangan',compact('user','row'));
    } public function tambah(){
		$data['nama']='';
		
		$data['active']='';
		$id='';
		$title='master tunjangan';
		$type='simpan_'.str_replace(' ','_',$title);
		$page='Tambah';
		return view('backend.gaji.master.tunjangan.tambah_edit_tunjangan',compact('data','type','id','page','title','title'));
	}public function edit($id){
		$page='Edit';
		$title='master tunjangan';
		$type='update_'.str_replace(' ','_',$title);
		$sql = "SELECT * FROM m_tunjangan where m_tunjangan_id=$id ";
    	$cos=DB::connection()->select($sql);
		$data['nama']=$cos[0]->nama;
		
		$data['active']=$cos[0]->active;
		
		return view('backend.gaji.master.tunjangan.tambah_edit_tunjangan',compact('data','type','id','page','title'));
	}public function simpan(Request $request){
		$title='master tunjangan';
        DB::beginTransaction();
        try{
            $idUser=Auth::user()->id;
            DB::connection()->table("m_tunjangan")
                ->insert([
                    "nama"=>($request->get("nama")),
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
		$title='master tunjangan';
        DB::beginTransaction();
        try{
            $idUser=Auth::user()->id;
            DB::connection()->table("m_tunjangan")
                ->where("m_tunjangan_id",$id)
                ->update([
                    "nama"=>($request->get("nama")),
                    
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
		$title='master tunjangan';
        DB::beginTransaction();
        try{
            DB::connection()->table("m_beban")
                ->where("m_beban_id",$id)
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
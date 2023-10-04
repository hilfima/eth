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

class Master_statusController extends Controller
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

    public function master_status (Request $request)
    {
    	
		 $iduser=Auth::user()->id;
      	$sqluser="SELECT p_recruitment.foto FROM users
			left join p_karyawan on p_karyawan.user_id=users.id
			left join p_recruitment on p_recruitment.p_recruitment_id=p_karyawan.p_karyawan_id
			where users.id=$iduser"; $user=DB::connection()->select($sqluser);
		$title='master status';
		$sql="SELECT * FROM m_status"; 
		$row=DB::connection()->select($sql);
        return view('backend.gaji.master.status.status',compact('user','row','title'));
    } public function tambah(){
		$data['nama']='';
		
		$data['active']='';
		$data['kode']='';
		$data['is_status_karyawan']='';
		$id='';
		$title='master status';
		$type='simpan_'.str_replace(' ','_',$title);
		$page='Tambah';
		return view('backend.gaji.master.status.tambah_edit_status',compact('data','type','id','page','title','title'));
	}public function edit($id){
		$page='Edit';
		$title='master status';
		$type='update_'.str_replace(' ','_',$title);
		$sql = "SELECT * FROM m_status where m_status_id=$id ";
    	$cos=DB::connection()->select($sql);
		$data['nama']=$cos[0]->nama;
		$data['kode']=$cos[0]->kode;
		
		$data['active']=$cos[0]->active;
		
		return view('backend.gaji.master.status.tambah_edit_status',compact('data','type','id','page','title'));
	}public function simpan(Request $request){
		$title='master status';
        DB::beginTransaction();
        try{
            $idUser=Auth::user()->id;
            DB::connection()->table("m_status")
                ->insert([
                    "nama"=>($request->get("nama")),
                    "kode"=>($request->get("kode")),
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
		$title='master status';
        DB::beginTransaction();
        try{
            $idUser=Auth::user()->id;
            DB::connection()->table("m_status")
                ->where("m_status_id",$id)
                ->update([
                    "nama"=>($request->get("nama")),
                     "kode"=>($request->get("kode")),
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
		$title='master status';
        DB::beginTransaction();
        try{
            DB::connection()->table("m_status")
                ->where("m_status_id",$id)
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
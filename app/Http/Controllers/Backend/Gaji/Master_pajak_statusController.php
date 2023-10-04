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

class Master_pajak_statusController extends Controller
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

    public function master_pajak_status (Request $request)
    {
    	
		 $iduser=Auth::user()->id;
      	$sqluser="SELECT p_recruitment.foto FROM users
			left join p_karyawan on p_karyawan.user_id=users.id
			left join p_recruitment on p_recruitment.p_recruitment_id=p_karyawan.p_karyawan_id
			where users.id=$iduser"; $user=DB::connection()->select($sqluser);
		$title='master pajak status';
		$sql="SELECT * FROM m_pajak_status a join m_status b on a.m_status_id = b.m_status_id  join m_pajak_ptkp c on c.m_pajak_ptkp_id = a.m_pajak_ptkp_id "; 
		$row=DB::connection()->select($sql);
        return view('backend.gaji.master.pajak_status.pajak_status',compact('user','row','title'));
    } public function tambah(){
		$data['m_pajak_ptkp_id']='';
		
		$data['active']='';
		$data['m_status_id']='';
		$data['is_pajak_status_karyawan']='';
		$id='';
		$title='master pajak status';
		$type='simpan_'.str_replace(' ','_',$title);
		$page='Tambah';
		return view('backend.gaji.master.pajak_status.tambah_edit_pajak_status',compact('data','type','id','page','title','title'));
	}public function edit($id){
		$page='Edit';
		$title='master pajak status';
		$type='update_'.str_replace(' ','_',$title);
		$sql = "SELECT * FROM m_pajak_status where m_pajak_status_id=$id ";
    	$cos=DB::connection()->select($sql);
		$data['m_pajak_ptkp_id']=$cos[0]->m_pajak_ptkp_id;
		$data['m_status_id']=$cos[0]->m_status_id;
		
		$data['active']=$cos[0]->active;
		
		return view('backend.gaji.master.pajak_status.tambah_edit_pajak_status',compact('data','type','id','page','title'));
	}public function simpan(Request $request){
		$title='master pajak status';
        DB::beginTransaction();
        try{
            $idUser=Auth::user()->id;
            DB::connection()->table("m_pajak_status")
                ->insert([
                    "m_pajak_ptkp_id"=>($request->get("m_pajak_ptkp_id")),
                    "m_status_id"=>($request->get("m_status_id")),
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
		$title='master pajak status';
        DB::beginTransaction();
        try{
            $idUser=Auth::user()->id;
            DB::connection()->table("m_pajak_status")
                ->where("m_pajak_status_id",$id)
                ->update([
                    "m_status_id"=>($request->get("m_status_id")),
                     "m_pajak_ptkp_id"=>($request->get("m_pajak_ptkp_id")),
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
		$title='master pajak status';
        DB::beginTransaction();
        try{
            DB::connection()->table("m_pajak_status")
                ->where("m_pajak_status_id",$id)
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
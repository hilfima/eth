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

class Master_pajakptkpController extends Controller
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

    public function master_pajak_ptkp(Request $request)
    {
    	
		 $iduser=Auth::user()->id;
      	$sqluser="SELECT p_recruitment.foto FROM users
			left join p_karyawan on p_karyawan.user_id=users.id
			left join p_recruitment on p_recruitment.p_recruitment_id=p_karyawan.p_karyawan_id
			where users.id=$iduser"; $user=DB::connection()->select($sqluser);
		$title='master pajak ptkp';
		$sql="SELECT * FROM m_pajak_ptkp  "; 
		$row=DB::connection()->select($sql);
        return view('backend.gaji.master.pajak_ptkp.pajak_ptkp',compact('user','row','title'));
    } public function tambah(){
		$data['kode']='';
		$data['ptkp']='';
		$data['keterangan']='';
		
		$data['active']='';
		$id='';
		$title='master pajak ptkp';
		$type='simpan_'.str_replace(' ','_',$title);
		$page='Tambah';
		return view('backend.gaji.master.pajak_ptkp.tambah_edit_pajak_ptkp',compact('data','type','id','page','title','title'));
	}public function edit($id){
		$page='Edit';
		$title='master pajak ptkp';
		$type='update_'.str_replace(' ','_',$title);
		$sql = "SELECT * FROM m_pajak_ptkp where m_pajak_ptkp_id=$id ";
    	$cos=DB::connection()->select($sql);
		$data['kode']=$cos[0]->kode;
		
		$data['ptkp']=$cos[0]->ptkp;
		$data['keterangan']=$cos[0]->keterangan;
		
		return view('backend.gaji.master.pajak_ptkp.tambah_edit_pajak_ptkp',compact('data','type','id','page','title'));
	}public function simpan(Request $request){
		$title='master pajak ptkp';
        DB::beginTransaction();
        try{
            $idUser=Auth::user()->id;
            DB::connection()->table("m_pajak_ptkp")
                ->insert([
                    "kode"=>($request->get("kode")),
                    "ptkp"=>($request->get("ptkp")),
                    "keterangan"=>($request->get("keterangan")),
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
		$title='master pajak ptkp';
        DB::beginTransaction();
        try{
            $idUser=Auth::user()->id;
            DB::connection()->table("m_pajak_ptkp")
                ->where("m_pajak_ptkp_id",$id)
                ->update([
                    "kode"=>($request->get("kode")),
                    "ptkp"=>($request->get("ptkp")),
                    "keterangan"=>($request->get("keterangan")),
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
		$title='master pajak ptkp';
        DB::beginTransaction();
        try{
            DB::connection()->table("m_pajak_ptkp")
                ->where("m_pajak_ptkp_id",$id)
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
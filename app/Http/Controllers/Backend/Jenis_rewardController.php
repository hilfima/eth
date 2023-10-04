<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Auth;

class jenis_rewardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }public function jenis_reward()
    {
       
        $sqljenis_reward="SELECT * from m_jenis_reward
        		
                WHERE 1=1  and m_jenis_reward.active=1";
        $jenis_reward=DB::connection()->select($sqljenis_reward);
         
        return view('backend.jenis_reward.jenis_reward',compact('jenis_reward'));
    }

    public function tambah_jenis_reward()
    {
       $sqlentitas="SELECT * from m_lokasi
                WHERE 1=1 ";
        $entitas=DB::connection()->select($sqlentitas);
                $id = '';
                $type = 'simpan_jenis_reward';
                $data['nama']='';
                $data['status']='';
                $data['m_lokasi_id']='';
        return view('backend.jenis_reward.tambah_jenis_reward',compact('id','data','type','entitas'));
    }

    public function simpan_jenis_reward(Request $request){
    	
	  // echo $kode;die;
         DB::connection()->table("m_jenis_reward")
            ->insert([
                "nama_jenis_reward" => $request->get("nama"),
                
            ]);

        return redirect()->route('be.jenis_reward')->with('success',' Jenis reward Berhasil di input!');
    }

    public function edit_jenis_reward($id)
    {
      
                $type = 'update_jenis_reward';
        $sqljenis_reward="SELECT * FROM m_jenis_reward WHERE  m_jenis_reward_id = $id  ";
        $jenis_reward=DB::connection()->select($sqljenis_reward);
		$data['nama']=$jenis_reward[0]->nama_jenis_reward;
        
        return view('backend.jenis_reward.tambah_jenis_reward', compact('data','id','type'));
    }

    public function update_jenis_reward(Request $request, $id){
        $idUser=Auth::user()->id;
        DB::connection()->table("m_jenis_reward")
            ->where("m_jenis_reward_id",$id)
            ->update([
                "nama_jenis_reward" => $request->get("nama"),
            ]);

        return redirect()->route('be.jenis_reward')->with('success',' Jenis reward Berhasil di Ubah!');
    }

    public function hapus_jenis_reward($id)
    {
        $idUser=Auth::user()->id;
        DB::connection()->table("m_jenis_reward")
            ->where("m_jenis_reward_id",$id)
            ->update([
                "active"=>0,
               
            ]);

        return redirect()->back()->with('success',' Jenis reward Berhasil di Hapus!');
    }
}

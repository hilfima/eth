<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Auth;

class bankController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function bank()
    {
       
        $sqlbank="SELECT * from m_bank
        		
                WHERE 1=1  and m_bank.active=1";
        $bank=DB::connection()->select($sqlbank);
         
        return view('backend.bank.bank',compact('bank'));
    }

    public function tambah_bank()
    {
       $sqlentitas="SELECT * from m_lokasi
                WHERE 1=1 ";
        $entitas=DB::connection()->select($sqlentitas);
                $id = '';
                $type = 'simpan_bank';
                $data['nama']='';
                $data['status']='';
                $data['m_lokasi_id']='';
        return view('backend.bank.tambah_bank',compact('id','data','type','entitas'));
    }

    public function simpan_bank(Request $request){
    	
	  // echo $kode;die;
         DB::connection()->table("m_bank")
            ->insert([
                "nama_bank" => $request->get("nama"),
                
            ]);

        return redirect()->route('be.bank')->with('success',' bank Berhasil di input!');
    }

    public function edit_bank($id)
    {
       $sqlentitas="SELECT * from m_lokasi
                WHERE 1=1 ";
		$entitas=DB::connection()->select($sqlentitas);
               
                $type = 'update_bank';
        $sqlbank="SELECT * FROM m_bank WHERE  m_bank_id = $id  ";
        $bank=DB::connection()->select($sqlbank);
		$data['nama']=$bank[0]->nama_bank;
        $data['m_lokasi_id']=$bank[0]->m_lokasi_id;
        $data['status']=$bank[0]->status;
        
        return view('backend.bank.tambah_bank', compact('data','id','type','entitas'));
    }

    public function update_bank(Request $request, $id){
        $idUser=Auth::user()->id;
        DB::connection()->table("m_bank")
            ->where("m_bank_id",$id)
            ->update([
                "nama_bank" => $request->get("nama"),
            ]);

        return redirect()->route('be.bank')->with('success',' bank Berhasil di Ubah!');
    }

    public function hapus_bank($id)
    {
        $idUser=Auth::user()->id;
        DB::connection()->table("m_bank")
            ->where("m_bank_id",$id)
            ->update([
                "active"=>0,
               
            ]);

        return redirect()->back()->with('success',' bank Berhasil di Hapus!');
    }
}

<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Auth;

class jenis_sanksiController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }public function jenis_sanksi()
    {
       
        $sqljenis_sanksi="SELECT * from m_jenis_sanksi
        		
                WHERE 1=1  and m_jenis_sanksi.active=1";
        $jenis_sanksi=DB::connection()->select($sqljenis_sanksi);
         
        return view('backend.jenis_sanksi.jenis_sanksi',compact('jenis_sanksi'));
    }

    public function tambah_jenis_sanksi()
    {
       $sqlentitas="SELECT * from m_lokasi
                WHERE 1=1 ";
        $entitas=DB::connection()->select($sqlentitas);
                $id = '';
                $type = 'simpan_jenis_sanksi';
                $data['nama']='';
                $data['status']='';
                $data['m_lokasi_id']='';
        return view('backend.jenis_sanksi.tambah_jenis_sanksi',compact('id','data','type','entitas'));
    }

    public function simpan_jenis_sanksi(Request $request){
    	
	  // echo $kode;die;
         DB::connection()->table("m_jenis_sanksi")
            ->insert([
                "nama_sanksi" => $request->get("nama"),
                
            ]);

        return redirect()->route('be.jenis_sanksi')->with('success',' Jenis Sanksi Berhasil di input!');
    }

    public function edit_jenis_sanksi($id)
    {
      
                $type = 'update_jenis_sanksi';
        $sqljenis_sanksi="SELECT * FROM m_jenis_sanksi WHERE  m_jenis_sanksi_id = $id  ";
        $jenis_sanksi=DB::connection()->select($sqljenis_sanksi);
		$data['nama']=$jenis_sanksi[0]->nama_sanksi;
        
        return view('backend.jenis_sanksi.tambah_jenis_sanksi', compact('data','id','type'));
    }

    public function update_jenis_sanksi(Request $request, $id){
        $idUser=Auth::user()->id;
        DB::connection()->table("m_jenis_sanksi")
            ->where("m_jenis_sanksi_id",$id)
            ->update([
                "nama_sanksi" => $request->get("nama"),
            ]);

        return redirect()->route('be.jenis_sanksi')->with('success',' Jenis Sanksi Berhasil di Ubah!');
    }

    public function hapus_jenis_sanksi($id)
    {
        $idUser=Auth::user()->id;
        DB::connection()->table("m_jenis_sanksi")
            ->where("m_jenis_sanksi_id",$id)
            ->update([
                "active"=>0,
               
            ]);

        return redirect()->back()->with('success',' Jenis Sanksi Berhasil di Hapus!');
    }
}

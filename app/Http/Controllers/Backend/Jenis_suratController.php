<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Auth;

class jenis_suratController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }public function jenis_surat()
    {
       
        $sqljenis_surat="SELECT * from m_jenis_surat
        		
                WHERE 1=1  and m_jenis_surat.active=1";
        $jenis_surat=DB::connection()->select($sqljenis_surat);
         
        return view('backend.jenis_surat.jenis_surat',compact('jenis_surat'));
    }

    public function tambah_jenis_surat()
    {
       $sqlentitas="SELECT * from m_lokasi
                WHERE 1=1 ";
        $entitas=DB::connection()->select($sqlentitas);
                $id = '';
                $type = 'simpan_jenis_surat';
                $data['nama']='';
                $data['status']='';
                $data['m_lokasi_id']='';
        return view('backend.jenis_surat.tambah_jenis_surat',compact('id','data','type','entitas'));
    }

    public function simpan_jenis_surat(Request $request){
    	
	  // echo $kode;die;
         DB::connection()->table("m_jenis_surat")
            ->insert([
                "nama_jenis_surat" => $request->get("nama"),
                
            ]);

        return redirect()->route('be.jenis_surat')->with('success',' Jenis Surat Berhasil di input!');
    }

    public function edit_jenis_surat($id)
    {
      
                $type = 'update_jenis_surat';
        $sqljenis_surat="SELECT * FROM m_jenis_surat WHERE  m_jenis_surat_id = $id  ";
        $jenis_surat=DB::connection()->select($sqljenis_surat);
		$data['nama']=$jenis_surat[0]->nama_jenis_surat;
        
        return view('backend.jenis_surat.tambah_jenis_surat', compact('data','id','type'));
    }

    public function update_jenis_surat(Request $request, $id){
        $idUser=Auth::user()->id;
        DB::connection()->table("m_jenis_surat")
            ->where("m_jenis_surat_id",$id)
            ->update([
                "nama_jenis_surat" => $request->get("nama"),
            ]);

        return redirect()->route('be.jenis_surat')->with('success',' Jenis Surat Berhasil di Ubah!');
    }

    public function hapus_jenis_surat($id)
    {
        $idUser=Auth::user()->id;
        DB::connection()->table("m_jenis_surat")
            ->where("m_jenis_surat_id",$id)
            ->update([
                "active"=>0,
               
            ]);

        return redirect()->back()->with('success',' Jenis Surat Berhasil di Hapus!');
    }
}

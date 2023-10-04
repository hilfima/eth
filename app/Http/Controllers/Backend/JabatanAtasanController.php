<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Backend\JabatanStrukturalController;
use Illuminate\Http\Request;
use DB;
use Auth;

class JabatanAtasanController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }public function jabatan()
    {
        $sqlberita="SELECT *,b.nama as jabatan_child, c.nama as jabatan_parent,d.nama as nama_entitas
        		 FROM m_jabatan_atasan a 
        		join m_jabatan b on b.m_jabatan_id = a.m_jabatan_id 
        		join m_lokasi d on b.m_lokasi_id = d.m_lokasi_id 
        		left join m_jabatan c on c.m_jabatan_id = a.m_atasan_id 
        		 ";
        $jabatan=DB::connection()->select($sqlberita);

        

        return view('backend.jabatan_atasan.jabatan',compact('jabatan'));
    }

   

    public function tambah()
    {
         $sqlberita="SELECT *,a.nama as nama,d.nama as nama_entitas
        		 FROM m_jabatan a 
        		join m_lokasi d on a.m_lokasi_id = d.m_lokasi_id
        		where a.active=1 
        		";
        $jabatan=DB::connection()->select($sqlberita);
        $data['jabatan'] = "";
        $data['hirarki'] = "";
        $data['id'] = "";
		$data['post'] = 'simpan_atasan';
        return view('backend.jabatan_atasan.tambah_atasan',compact('jabatan','data'));
    }

    public function simpan(Request $request){
        DB::beginTransaction();
        try{
            $idUser=Auth::user()->id;
            DB::connection()->table("m_jabatan_atasan")
                ->insert([
                    "m_jabatan_id"=>($request->get("jabatan")),
                    "m_atasan_id"=>($request->get("hirarki")),
                    "create_by"=>$idUser,
                    "create_date" => date("Y-m-d H:i:s")
                ]);
            DB::commit();
            $st = new JabatanStrukturalController();
            $st->generate_jabatan_atasan($request->get("jabatan"));
           return redirect()->route('be.hirarki_jabatan')->with('success','Atasan Berhasil di input!');
        }
        catch(\Exeception $e){
            DB::rollback();
            return redirect()->back()->with('error',$e);
        }

    }

    public function edit($id)
    {
       $sqlberita="SELECT *,a.nama as nama,d.nama as nama_entitas
        		 FROM m_jabatan a 
        		join m_lokasi d on a.m_lokasi_id = d.m_lokasi_id 
        		";
        $jabatan=DB::connection()->select($sqlberita);
        $sqlberita="SELECT *
        		 FROM m_jabatan_atasan a where a.m_jabatan_atasan_id = $id 
        		";
        $atasan=DB::connection()->select($sqlberita);
		$data['post'] = 'update_atasan';
		$data['id'] = $id;
		$data['jabatan'] = $atasan[0]->m_jabatan_id;
        $data['hirarki'] = $atasan[0]->m_atasan_id;
        return view('backend.jabatan_atasan.tambah_atasan', compact('atasan','jabatan','data'));
    }

    public function update(Request $request, $id){
        DB::beginTransaction();
        try{
            $idUser=Auth::user()->id;
            DB::connection()->table("m_jabatan_atasan")
                ->where("m_jabatan_atasan_id",$id)
                ->update([
                     "m_jabatan_id"=>($request->get("jabatan")),
                    "m_atasan_id"=>($request->get("hirarki")),
                    "update_by"=>$idUser,
                    "update_date" => date("Y-m-d H:i:s")
                ]);
            $st = new JabatanStrukturalController();
            $st->generate_jabatan_atasan($request->get("jabatan"));
            DB::commit();
            return redirect()->route('be.hirarki_jabatan')->with('success','Berita Berhasil di Ubah!');
        }
        catch(\Exeception $e){
            DB::rollback();
            return redirect()->back()->with('error',$e);
        }
    }

    public function hapus($id)
    {
        DB::beginTransaction();
        try{
            DB::connection()->table("m_jabatan_atasan")
                ->where("m_jabatan_atasan_id",$id)
                ->delete();
            DB::connection()->table("m_jabatan_struktural")
        				->where('m_jabatan_id',$id)
		                ->delete();
            DB::commit();
            return redirect()->route('be.hirarki_jabatan')->with('success','Berita Berhasil di Hapus!');
        }
        catch(\Exeception $e){
            DB::rollback();
            return redirect()->back()->with('error',$e);
        }
    }
  
}

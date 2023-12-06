<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Auth;

class AlasanJenisIzinController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }public function alasan_jenis_ijin()
    {
        $sqlberita="SELECT *,mja.alasan,mja.m_jenis_alasan_id as m_jenis_alasan_id_id FROM m_jenis_alasan mja
                left join m_jenis_ijin mji on mja.jenis = mji.m_jenis_ijin_id 
                WHERE 1=1 and mja.active=1 ";
        $alasan_jenis_ijin=DB::connection()->select($sqlberita);

      
        return view('backend.alasan_jenis_ijin.alasan_jenis_ijin',compact('alasan_jenis_ijin'));
    }

    public function tambah_alasan_jenis_ijin ()
    {
        $iduser=Auth::user()->id;
        $sqluser="select * from m_jenis_ijin where active=1";
        $jenis_ijin=DB::connection()->select($sqluser);
        return view('backend.alasan_jenis_ijin.tambah_alasan_jenis_ijin',compact('jenis_ijin'));
    }

    public function simpan_alasan_jenis_ijin (Request $request){
        DB::beginTransaction();
        try{
            $idUser=Auth::user()->id;
            $data =$request->data;
            DB::connection()->table("m_jenis_alasan")
                ->insert($data);
            DB::commit();
            return redirect()->route('be.jenis_alasan')->with('success','Alasan jenis Izin Berhasil di input!');
        }
        catch(\Exeception $e){
            DB::rollback();
            return redirect()->back()->with('error',$e);
        }

    }

    public function edit_alasan_jenis_ijin ($id)
    {
        $sqlberita="SELECT * FROM m_jenis_alasan
                WHERE m_jenis_alasan_id=$id";
        $alasan_jenis_ijin=DB::connection()->select($sqlberita);

        $sqluser="select * from m_jenis_ijin where active=1";
        $jenis_ijin=DB::connection()->select($sqluser);
        

        return view('backend.alasan_jenis_ijin.edit_alasan_jenis_ijin', compact('alasan_jenis_ijin','jenis_ijin','id'));
    }

    public function update_alasan_jenis_ijin (Request $request, $id){
        DB::beginTransaction();
        try{
            $idUser=Auth::user()->id;
            $data =$request->data;
            DB::connection()->table("m_jenis_alasan")
                ->where("m_jenis_alasan_id",$id)
                ->update($data);
            DB::commit();
            return redirect()->route('be.jenis_alasan')->with('success','Alasan jenis Izin  Berhasil di Ubah!');
        }
        catch(\Exeception $e){
            DB::rollback();
            return redirect()->back()->with('error',$e);
        }
    }

    public function hapus_alasan_jenis_ijin ($id)
    {
        DB::beginTransaction();
        try{
            DB::connection()->table("m_jenis_alasan")
                ->where("m_jenis_alasan_id",$id)
                ->update(["active"=>0]);
            DB::commit();
            return redirect()->route('be.jenis_alasan')->with('success','Berita Berhasil di Hapus!');
        }
        catch(\Exeception $e){
            DB::rollback();
            return redirect()->back()->with('error',$e);
        }
    }
}

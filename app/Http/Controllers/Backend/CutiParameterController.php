<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Auth;

class CutiParameterController extends Controller
{
	public function __construct()
    {
        $this->middleware('auth');
    }
    public function parameter_reset_cuti()
    {
    	$sql ="select * from m_parameter_cuti where active= 1";
    	$cuti=DB::connection()->select($sql);
    	return view('backend.cuti.cuti_parameter',compact('cuti'));
	}public function tambah_parameter_reset_cuti()
    {
    	
    	return view('backend.cuti.tambah_cuti_parameter'); 
	}public function edit_parameter_reset_cuti ($id)
    {
    	$sql ="select * from m_parameter_cuti where active= 1 and m_parameter_cuti_id = $id";
    	$cuti=DB::connection()->select($sql);
    	return view('backend.cuti.edit_cuti_parameter',compact('cuti','id')); 
	} public function simpan_parameter_reset_cuti(Request $request){
        DB::beginTransaction();
        try{
            $idUser=Auth::user()->id;
            DB::connection()->table("m_parameter_cuti")
                ->insert([
                    "tgl_reset"=>($request->get("tgl")),
                    "ditahun"=>($request->get("thn")),
                   
                ]);
            DB::commit();
            return redirect()->route('be.parameter_cuti')->with('success','Berita Berhasil di input!');
        }
        catch(\Exeception $e){
            DB::rollback();
            return redirect()->back()->with('error',$e);
        }

    }public function update_parameter_reset_cuti (Request $request,$id){
        DB::beginTransaction();
        try{
            $idUser=Auth::user()->id;
            DB::connection()->table("m_parameter_cuti")
                ->where('m_parameter_cuti_id',$id)
                ->update([
                    "tgl_reset"=>($request->get("tgl")),
                    "ditahun"=>($request->get("thn")),
                   
                ]);
            DB::commit();
            return redirect()->route('be.parameter_cuti')->with('success','Berita Berhasil di input!');
        }
        catch(\Exeception $e){
            DB::rollback();
            return redirect()->back()->with('error',$e);
        }

    }
}
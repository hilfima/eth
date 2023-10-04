<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Auth;

class KaryawanShiftController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }public function karyawan_shift()
    {
        $sqlkaryawan_shift="SELECT * FROM m_karyawan_shift
        join p_karyawan on m_karyawan_shift.p_karyawan_id = p_karyawan.p_karyawan_id
                WHERE 1=1 and m_karyawan_shift.active=1 "; 
        $karyawan_shift=DB::connection()->select($sqlkaryawan_shift);

       

        return view('backend.karyawan_shift.karyawan_shift',compact('karyawan_shift'));
    }

    public function tambah_karyawan_shift()
    {
       	$sqlkaryawan_shift="SELECT * FROM p_karyawan
                WHERE p_karyawan.active=1";
        $karyawan=DB::connection()->select($sqlkaryawan_shift);

        return view('backend.karyawan_shift.tambah_karyawan_shift',compact('karyawan'));
    }

    public function simpan_karyawan_shift(Request $request){
        DB::beginTransaction();
        try{
        	$karyawan = $request->get('karyawan');
            $idUser=Auth::user()->id;
            for($i = 0; $i < count($karyawan); $i++){
				  DB::connection()->table("m_karyawan_shift")
                		->insert([
                    "p_karyawan_id"=>($karyawan[$i]),
                    "active"=>(1),
                   
                ]);
				
			}
          
            DB::commit();
            return redirect()->route('be.karyawan_shift')->with('success','karyawan_shift Berhasil di input!');
        }
        catch(\Exeception $e){
            DB::rollback();
            return redirect()->back()->with('error',$e);
        }

    }

    public function edit_karyawan_shift($id)
    {
        $sqlkaryawan_shift="SELECT * FROM m_karyawan_shift
                WHERE m_karyawan_shift_id=$id";
        $karyawan_shift=DB::connection()->select($sqlkaryawan_shift);

        $iduser=Auth::user()->id;
        $sqluser="SELECT p_recruitment.foto FROM users
left join p_karyawan on p_karyawan.user_id=users.id
left join p_recruitment on p_recruitment.p_recruitment_id=p_karyawan.p_recruitment_id
where users.id=$iduser";
        $user=DB::connection()->select($sqluser);

        return view('backend.karyawan_shift.edit_karyawan_shift', compact('karyawan_shift','user'));
    }

    public function update_karyawan_shift(Request $request, $id){
        DB::beginTransaction();
        try{
            $idUser=Auth::user()->id;
            DB::connection()->table("m_karyawan_shift")
                ->where("m_karyawan_shift_id",$id)
                ->update([
                    "judul"=>($request->get("judul")),
                    "deskripsi"=>($request->get("deskripsi_karyawan_shift")),
                    "tgl_posting"=>date('Y-m-d',strtotime($request->get("tgl_posting"))),
                    "tgl_posting_akhir"=>date('Y-m-d',strtotime($request->get("tgl_posting_akhir"))),
                    "update_by"=>$idUser,
                    "update_date" => date("Y-m-d H:i:s")
                ]);
            DB::commit();
            return redirect()->route('be.karyawan_shift')->with('success','karyawan_shift Berhasil di Ubah!');
        }
        catch(\Exeception $e){
            DB::rollback();
            return redirect()->back()->with('error',$e);
        }
    }

    public function hapus_karyawan_shift($id)
    {
        DB::beginTransaction();
        try{
            DB::connection()->table("m_karyawan_shift")
                ->where("m_karyawan_shift_id",$id)
                ->delete();
            DB::commit();
            return redirect()->route('be.karyawan_shift')->with('success','karyawan_shift Berhasil di Hapus!');
        }
        catch(\Exeception $e){
            DB::rollback();
            return redirect()->back()->with('error',$e);
        }
    }
}

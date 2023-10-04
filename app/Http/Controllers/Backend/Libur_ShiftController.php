<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Auth;
use App\Helper_function;

class Libur_shiftController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }public function libur_shift(Request $request)
    {
        $sqlLibur_shift="SELECT absen_libur_shift.*,a.nama
                       FROM absen_libur_shift
                    	left join p_karyawan a on absen_libur_shift.p_karyawan_id = a.p_karyawan_id
                        where absen_libur_shift.active=1
                        ORDER BY tanggal";
        $Libur_shift=DB::connection()->select($sqlLibur_shift);

        $iduser=Auth::user()->id;
        $sqluser="SELECT p_recruitment.foto FROM users
left join p_karyawan on p_karyawan.user_id=users.id
left join p_recruitment on p_recruitment.p_recruitment_id=p_karyawan.p_recruitment_id
where users.id=$iduser";
        $user=DB::connection()->select($sqluser);

        return view('backend.libur_shift.libur_shift',compact('Libur_shift','request','user'));
    }

    public function tambah_libur_shift()
    {
        $type = 'simpan_libur_shift';
        $id = '';
		$sqlusers="SELECT p_karyawan.*,m_departemen.nama as nmdept FROM p_karyawan
LEFT JOIN p_karyawan_pekerjaan on p_karyawan_pekerjaan.p_karyawan_id=p_karyawan.p_karyawan_id
LEFT JOIN m_departemen on m_departemen.m_departemen_id=p_karyawan_pekerjaan.m_departemen_id
                WHERE 1=1 and p_karyawan.active=1  order by p_karyawan.nama";
        $karyawan=DB::connection()->select($sqlusers); 
        $data['tanggal'] = '';
        $data['p_karyawan'] = '';
        $data['keterangan'] = '';
        return view('backend.libur_shift.tambah_libur_shift', compact('karyawan','id','data','type'));
    }

    public function simpan_libur_shift(Request $request){
        DB::beginTransaction();
        try{
        	$help = new Helper_function();
            $idUser=Auth::user()->id;
            $karyawan_shift = $request->get("karyawan");
			for($i=0;$i<count($karyawan_shift);$i++){
				DB::connection()->table("absen_libur_shift")
               		 ->insert([
                   		"tanggal"=>($request->get('tanggal')),
                    	"p_karyawan_id"=>($karyawan_shift[$i]),
                    	"keterangan"=>($request->get('keterangan')),
                    ]);
				
				}
           
            DB::commit();
            return redirect()->route('be.libur_shift')->with('success','Hari Libur Berhasil di input!');
        }
        catch(\Exeception $e){
            DB::rollback();
            return redirect()->back()->with('error',$e);
        }

    }

    public function edit_libur_shift($id)
    {
        $sqlLibur_shift="SELECT * FROM absen_libur_shift WHERE absen_libur_shift_id=$id ORDER BY tanggal";
        $Libur_shift=DB::connection()->select($sqlLibur_shift);
		$type = 'update_libur_shift';
      
		$sqlusers="SELECT p_karyawan.*,m_departemen.nama as nmdept FROM p_karyawan
LEFT JOIN p_karyawan_pekerjaan on p_karyawan_pekerjaan.p_karyawan_id=p_karyawan.p_karyawan_id
LEFT JOIN m_departemen on m_departemen.m_departemen_id=p_karyawan_pekerjaan.m_departemen_id
                WHERE 1=1 and p_karyawan.active=1  order by p_karyawan.nama";
        $karyawan=DB::connection()->select($sqlusers); 
        $data['tanggal'] = $Libur_shift[0]->tanggal;
        $data['p_karyawan'] = $Libur_shift[0]->p_karyawan_id;
        $data['keterangan'] = $Libur_shift[0]->keterangan;
        
        
        $iduser=Auth::user()->id;
        $sqluser="SELECT p_recruitment.foto FROM users
left join p_karyawan on p_karyawan.user_id=users.id
left join p_recruitment on p_recruitment.p_recruitment_id=p_karyawan.p_recruitment_id
where users.id=$iduser";
        $user=DB::connection()->select($sqluser);

        return view('backend.libur_shift.tambah_libur_shift', compact('Libur_shift','user','type','id'));
    }

    public function update_libur_shift(Request $request, $id){
        $idUser=Auth::user()->id;
        DB::beginTransaction();
        try{
            DB::connection()->table("absen_libur_shift")
                ->where("absen_libur_shift_id",$id)
                ->update([
                    "tanggal"=>date('Y-m-d',strtotime($request->get("tanggal"))),
                    "nama"=>($request->get("nama")),
                    "jumlah"=>1,
                    "is_berulang"=>($request->get("berulang")),
                    "is_cuti_bersama"=>($request->get("cuti_bersama")),
                    "active"=>1,
                    "update_by" => $idUser,
                    "update_date" => date("Y-m-d H:i:s")
                ]);
            DB::commit();
            return redirect()->route('be.libur_shift')->with('success','Hari Libur Berhasil di Ubah!');
        }
        catch(\Exeception $e){
            DB::rollback();
            return redirect()->back()->with('error',$e);
        }
    }

    public function hapus_libur_shift($id)
    {
        DB::beginTransaction();
        try{
            DB::connection()->table("absen_libur_shift")
                ->where("absen_libur_shift_id",$id)
                ->update([
                    "active" => 0
                ]);
            DB::commit();
            return redirect()->route('be.libur_shift')->with('success','Hari Libur Berhasil di Ubah!');
        }
        catch(\Exeception $e){
            DB::rollback();
            return redirect()->back()->with('error',$e);
        }
    }
}

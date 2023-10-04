<?php

namespace App\Http\Controllers\Frontend;
use App\Http\Controllers\Controller;

use App\rekaplembur_xls;
use App\User;
use App\Helper_function;
use Illuminate\Http\Request;
use DB;
use Auth;
use PhpOffice\PhpSpreadsheet\Reader\Xml\Style\Fill;
use Response;

class BpjsController extends Controller
{
	public function __construct()
	{
		$this->middleware('auth');
	}
	public function bpjs(Request $request)
    {
    	$iduser=Auth::user()->id;
        $sqlidkar="select * from p_karyawan where user_id=$iduser";
        $idkar=DB::connection()->select($sqlidkar);
        $id=$idkar[0]->p_karyawan_id;
        $kar = "Select * from p_karyawan_kartu where p_karyawan_id = $id";
        $karyawan=DB::connection()->select($kar);
    	$sqlfasilitas="SELECT * FROM t_cover_bpjs
			left join p_karyawan_keluarga c on c.p_karyawan_keluarga_id = t_cover_bpjs.p_karyawan_keluarga_id
			left join p_karyawan d on d.p_karyawan_id = t_cover_bpjs.p_karyawan_id
                WHERE 1=1  and t_cover_bpjs.p_karyawan_id = $id  ";
        $bpjs=DB::connection()->select($sqlfasilitas);
		$page = DB::connection()->select('select * from pengaturan_page_bpjs');
    	return view('frontend.bpjs.cover',compact('bpjs','karyawan','page'));
    }public function simpan_cover_bpjs(Request $request)
    { 
    	
    	DB::beginTransaction();
        try{
        	$idUser=Auth::user()->id;
	        $sqlidkar="select * from p_karyawan where user_id=$idUser";
	        $idkar=DB::connection()->select($sqlidkar);
	        $id=$idkar[0]->p_karyawan_id;
	        $sql="select max(t_cover_bpjs_id) as max from t_cover_bpjs";
	        $idcover=DB::connection()->select($sql);
	        $max = $idcover[0]->max+1;
        	DB::connection()->table("t_cover_bpjs")
	                
	                ->insert([
	                    //"nik" => $request->get("nik"),
	                    //"nama" => $request->get("nama_lengkap"),
	                   
	                    "t_cover_bpjs_id"=>$max,
	                    "p_karyawan_id"=>$id,
	                    "p_karyawan_keluarga_id"=>$request->get("keluarga"),
	                    "alamat"=>$request->get("alamat"),
	                    "tanggal_lahir"=>$request->get("tanggal_lahir"),
	                    "nik"=>$request->get("nik"),
	                    
	                    "create_date" => date("Y-m-d"),
	                    "create_by" => $idUser,
                ]);
        	
        	
        	if($request->file('kk')){//echo 'masuk';die;
	                $file = $request->file('kk');
	                $destination="dist/img/cover/";
	                $path='kk-'.date('ymdhis').'-'.$file->getClientOriginalName();
	                $file->move($destination,$path);
	                //echo $path;die;
	                DB::connection()->table("t_cover_bpjs")->where("t_cover_bpjs_id",$max)
	                    ->update([
	                        "file_kk"=>$path
	                ]);
            }if($request->file('ktp')){//echo 'masuk';die;
	                $file = $request->file('ktp');
	                $destination="dist/img/cover/";
	                $path='ktp-'.date('ymdhis').'-'.$file->getClientOriginalName();
	                $file->move($destination,$path);
	                
	                //echo $path;die;
	                DB::connection()->table("t_cover_bpjs")->where("t_cover_bpjs_id",$max)
	                    ->update([
	                        "file_ktp"=>$path
	                ]);
            }if($request->file('bpjs_karyawan')){//echo 'masuk';die;
	                $file = $request->file('bpjs_karyawan');
	                $destination="dist/img/cover/";
	                 $path='bpjs_karyawan-'.date('ymdhis').'-'.$file->getClientOriginalName();
	                $file->move($destination,$path);
	               
	                //echo $path;die;
	                DB::connection()->table("t_cover_bpjs")->where("t_cover_bpjs_id",$max)
	                    ->update([
	                        "file_bpjs_karyawan"=>$path
	                ]);
            }if($request->file('bpjs_induk')){//echo 'masuk';die;
	                $file = $request->file('bpjs_induk');
	                $destination="dist/img/cover/";
	                $path='bpjs_induk-'.date('ymdhis').'-'.$file->getClientOriginalName();
	                $file->move($destination,$path);
	                
	                //echo $path;die;
	                DB::connection()->table("t_cover_bpjs")->where("t_cover_bpjs_id",$max)
	                    ->update([
	                        "file_bpjs_induk"=>$path
	                ]);
            }
            DB::commit();
            return redirect()->route('fe.bpjs')->with('success',' BPJS Berhasil di Ubah!');
       
        }
        catch(\Exeception $e){
            DB::rollback();
            return redirect()->back()->with('error',$e);
        }
    	
    }public function tambah_cover_bpjs()
    {
    	$iduser=Auth::user()->id;
        $sqlidkar="select * from p_karyawan 
        	join p_karyawan_pekerjaan on p_karyawan_pekerjaan.p_karyawan_id = p_karyawan.p_karyawan_id
        	where user_id=$iduser";
        $idkar=DB::connection()->select($sqlidkar);
    	$id = $idkar[0]->p_karyawan_id;
        //echo '<br>';
		$sql="SELECT * FROM p_karyawan_keluarga WHERE active=1 and p_karyawan_id=$id ORDER BY nama ASC ";
        $keluarga=DB::connection()->select($sql);
        
    	return view('frontend.bpjs.tambah_cover_bpjs', compact('keluarga'));
    }
}
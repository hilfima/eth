<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Auth;

class LokasiController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }public function lokasi()
    {
        $iduser=Auth::user()->id;
        $sqluser="SELECT p_recruitment.foto FROM users
left join p_karyawan on p_karyawan.user_id=users.id
left join p_recruitment on p_recruitment.p_recruitment_id=p_karyawan.p_recruitment_id
where users.id=$iduser";
        $user=DB::connection()->select($sqluser);

        $sqllokasi="SELECT * FROM m_lokasi
                WHERE 1=1 AND active=1 AND sub_entitas=0 order by nama";
        $lokasi=DB::connection()->select($sqllokasi);
        return view('backend.lokasi.lokasi',compact('lokasi','user'));
    }

    public function karyawan_entitas(Request $request)
    {
        $karyawan=DB::connection()->select("SELECT * from p_karyawan
                join p_karyawan_pekerjaan on p_karyawan.p_karyawan_id = p_karyawan_pekerjaan.p_karyawan_id
                WHERE 1=1 and p_karyawan.active=1 and p_karyawan_pekerjaan.m_lokasi_id = ".$request->entitas." order by nama");
        echo '<select class="form-control select3 karyawan" name="karyawan[]" style="width: 100%;" >';
        echo '<option value="">Pilih Karyawan</option>';
        foreach($karyawan as $karyawan ){
        	echo '<option value="'.$karyawan->p_karyawan_id.'">'.$karyawan->nama.'</option>';
        	
        }
        echo '</select>';
    	
    	
    }

    public function tambah_lokasi()
    {
        $iduser=Auth::user()->id;
        $sqluser="SELECT p_recruitment.foto FROM users
left join p_karyawan on p_karyawan.user_id=users.id
left join p_recruitment on p_recruitment.p_recruitment_id=p_karyawan.p_recruitment_id
where users.id=$iduser";
        $user=DB::connection()->select($sqluser);

        return view('backend.lokasi.tambah_lokasi',compact('user'));
    }

    public function simpan_lokasi(Request $request){
        $idUser=Auth::user()->id;
        $data = [

             "kode"=>($request->get("kode")),
             "kode_nik"=>($request->get("kode_nik")),
             "nama"=>($request->get("nama")),
             "email"=>($request->get("email")),
             "alamat"=>($request->get("alamat")),
             "title"=>($request->get("title")),
             "no_telp"=>($request->get("no_telp")),
             "fax"=>($request->get("fax")),
             "wa"=>($request->get("wa")), 
             "active"=>1,
             "create_by"=>$idUser,
             "create_date"=>date('Y-m-d'),
         
        ];
        if ($request->file('logo')) { //echo 'masuk';die;
                 $file = $request->file('logo');
                 $destination = "dist/img/file/";
                 $path = 'logo-' . date('ymdhis') . '-' . $file->getClientOriginalName();
                 $file->move($destination, $path);
                 //echo $path;die;
                 $data["logo"]=$path;
     }
    
     DB::connection()->table("m_lokasi")
         ->insert($data);

        return redirect()->route('be.lokasi')->with('success','Lokasi Berhasil di input!');
    }

    public function edit_lokasi($id)
    {
        $sqllokasi="select * from m_lokasi
                  WHERE m_lokasi_id=$id";
        $lokasi=DB::connection()->select($sqllokasi);

        $iduser=Auth::user()->id;
        $sqluser="SELECT p_recruitment.foto FROM users
left join p_karyawan on p_karyawan.user_id=users.id
left join p_recruitment on p_recruitment.p_recruitment_id=p_karyawan.p_recruitment_id
where users.id=$iduser";
        $user=DB::connection()->select($sqluser);

        return view('backend.lokasi.edit_lokasi', compact('lokasi','user'));
    }

    public function update_lokasi(Request $request, $id){
        $idUser=Auth::user()->id;
        $data = [
            
             "kode"=>($request->get("kode")),
             "kode_nik"=>($request->get("kode_nik")),
             "nama"=>($request->get("nama")),
             "email"=>($request->get("email")),
             "alamat"=>($request->get("alamat")),
             "title"=>($request->get("title")),
             "no_telp"=>($request->get("no_telp")),
             "fax"=>($request->get("fax")),
             "wa"=>($request->get("wa")),
             "update_date"=>date('Y-m-d'),
             "update_by"=>$idUser,
             "active"=>1,
         
        ];
        if ($request->file('logo')) { //echo 'masuk';die;
                 $file = $request->file('logo');
                 $destination = "dist/img/file/";
                 $path = 'logo-' . date('ymdhis') . '-' . $file->getClientOriginalName();
                 $file->move($destination, $path);
                 //echo $path;die;
                 $data["logo"]=$path;
     }
    
     DB::connection()->table("m_lokasi")
         ->insert($data);

        return redirect()->route('be.lokasi')->with('success','Lokasi Berhasil di Ubah!');
    }

    public function hapus_lokasi($id)
    {
        $idUser=Auth::user()->id;
        DB::connection()->table("m_lokasi")
            ->where("m_lokasi_id",$id)
            ->update([
                "active"=>0,
                "update_date"=>date('Y-m-d'),
                "update_by"=>$idUser,
            ]);

        return redirect()->back()->with('success','Lokasi Berhasil di Hapus!');
    }
}

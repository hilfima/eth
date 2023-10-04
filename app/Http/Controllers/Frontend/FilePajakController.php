<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Auth;
use App\Helper_function;

class filepajakController extends Controller
{
	public function __construct()
	{
		$this->middleware('auth');
	}public function filepajak()
    {
       $iduser=Auth::user()->id;
        $sqlidkar="select * from p_karyawan left  join p_karyawan_pekerjaan on p_karyawan.p_karyawan_id = p_karyawan_pekerjaan.p_karyawan_id where user_id=$iduser";
        $idkar=DB::connection()->select($sqlidkar);
        
        	
        $id=$idkar[0]->p_karyawan_id;
         $sqlfilepajak="SELECT *
		 from prl_pajak 
        		left join p_karyawan on prl_pajak.p_karyawan_pemilik_id = p_karyawan.p_karyawan_id
        		WHERE 1=1  and prl_pajak.active=1 and prl_pajak.p_karyawan_pemilik_id=$id";
        $filepajak=DB::connection()->select($sqlfilepajak);
         $help = new Helper_function();
        
        return view('frontend.filepajak.filepajak',compact('filepajak','help'));
    }

    public function tambah_filepajak()
    {
      
      $sqlperiode="SELECT m_periode_absen.*,
		case when periode=1 then 'Januari'
		when periode=2 then 'Februari'
		when periode=3 then 'Maret'
		when periode=4 then 'April'
		when periode=5 then 'Mei'
		when periode=6 then 'Juni'
		when periode=7 then 'Juli'
		when periode=8 then 'Agustus'
		when periode=9 then 'Septemfer'
		when periode=10 then 'Oktofer'
		when periode=11 then 'Novemfer'
		when periode=12 then 'Desemfer' end as bulan,
		case when type=0 then 'Pekanan' else 'Bulanan' end as tipe
		FROM m_periode_absen 
		where tipe_periode='absen'
		ORDER BY tahun desc,periode desc,type";
		$periode=DB::connection()->select($sqlperiode);
		 $sqlentitas="SELECT * from p_karyawan
                WHERE 1=1 and active=1";
        $karyawan=DB::connection()->select($sqlentitas);
                $id = '';
                $type = 'simpan_filepajak';
                $data['p_karyawan_id']='';
                $data['m_periode_absen_id']='';
                $data['file']='';
        return view('frontend.filepajak.tambah_filepajak',compact('id','data','type','karyawan','periode'));
    }

    public function simpan_filepajak(Request $request){
    	
	  // echo $kode;die; 
	  $idUser=Auth::user()->id;
	  
	  $periode=DB::connection()->select("Select max(prl_pajak_id) as max from prl_pajak");
	  $id = $periode[0]->max+1;
         DB::connection()->table("prl_pajak")
            ->insert([
                "prl_pajak_id" => $id,
                "m_periode_absen_id" => $request->get("periode"),
                "p_karyawan_pemilik_id" => $request->get("karyawan"),
                "file" 			=> $request->get("file"),
                "create_date" 	=> date('Y-m-d'),
                "create_by" 	=> $idUser,
            ]);
			if($request->file('file')){//echo 'masuk';die;
	                $file = $request->file('file');
	                $destination="dist/img/file/";
	                $path='pajak-'.date('ymdhis').'-'.$file->getClientOriginalName();
	                $file->move($destination,$path);
	                
	                //echo $path;die;
	                DB::connection()->table("prl_pajak")->where("prl_pajak_id",$id)
	                    ->update([
	                        "file"=>$path
	                ]);
            }
        return redirect()->route('fe.filepajak')->with('success',' filepajak ferhasil di input!');
    }

    public function edit_filepajak($id)
    {
       $sqlentitas="SELECT * from m_lokasi
                WHERE 1=1 ";
		$entitas=DB::connection()->select($sqlentitas);
               
                $type = 'update_filepajak';
        $sqlfilepajak="SELECT * FROM prl_pajak WHERE  prl_pajak_id = $id  ";
        $filepajak=DB::connection()->select($sqlfilepajak);
		$data['nama']=$filepajak[0]->nama_filepajak;
        $data['m_lokasi_id']=$filepajak[0]->m_lokasi_id;
        $data['status']=$filepajak[0]->status;
        
        return view('frontend.filepajak.tambah_filepajak', compact('data','id','type','entitas'));
    }

    public function update_filepajak(Request $request, $id){
        $idUser=Auth::user()->id;
        DB::connection()->table("prl_pajak")
            ->where("prl_pajak_id",$id)
            ->update([
                "nama_filepajak" => $request->get("nama"),
                "m_lokasi_id" => $request->get("m_lokasi_id"),
                "status" => $request->get("status"),
            ]);

        return redirect()->route('fe.filepajak')->with('success',' filepajak ferhasil di Ubah!');
    }

    public function hapus_filepajak($id)
    {
        $idUser=Auth::user()->id;
        DB::connection()->table("prl_pajak")
            ->where("prl_pajak_id",$id)
            ->update([
                "active"=>0,
               
            ]);

        return redirect()->back()->with('success',' filepajak ferhasil di Hapus!');
    }
}

<?php

namespace App\Http\Controllers\Backend;

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
    }
    public function filepajak()
    {
       
        $sqlfilepajak="SELECT *
		 from prl_pajak 
        		left join p_karyawan on prl_pajak.p_karyawan_pemilik_id = p_karyawan.p_karyawan_id
        		WHERE 1=1  and prl_pajak.active=1";
        $filepajak=DB::connection()->select($sqlfilepajak);
         $help = new Helper_function();
        
        return view('backend.filepajak.filepajak',compact('filepajak','help'));
    }

    public function tambah_filepajak()
    {
      
      $sqlperiode="SELECT m_periode_absen.*,
		
		case when a.periode_gajian=0 then 'Pekanan' else 'Bulanan' end as tipe,
		a.tahun as tahun_gener,a.*,a.bulan as bulan_gener,b.tgl_awal as tgl_awal_lembur,b.tgl_akhir as tgl_akhir_lembur
		FROM prl_generate a 
		join m_periode_absen on m_periode_absen.periode_absen_id = a.periode_absen_id
		join m_periode_absen b on b.periode_absen_id = a.periode_lembur_id
		where a.active = 1
		ORDER BY a.create_date desc, prl_generate_id desc";
		$periode=DB::connection()->select($sqlperiode);
		 $sqlentitas="SELECT * from p_karyawan
                WHERE 1=1 and active=1 order by nama";
        $karyawan=DB::connection()->select($sqlentitas);
		 $sqlentitas="SELECT * from m_lokasi
                WHERE 1=1 and active=1 and sub_entitas=0";
        $entitas=DB::connection()->select($sqlentitas);
                $id = '';
                $type = 'simpan_multifilepajak';
                $data['p_karyawan_id']='';
                $data['m_periode_absen_id']='';
                $data['file']='';
        return view('backend.filepajak.tambah_multi_pajak',compact('id','data','type','karyawan','entitas','periode'));
    }

    public function simpan_filepajak(Request $request){
    	
	  // echo $kode;die; 
	  $idUser=Auth::user()->id;
	  
        if($request->file('file')){//echo 'masuk';die;
	                $file = $request->file('file');
	                $destination="dist/img/file/";
	                $path='pajak-'.date('ymdhis').'-'.$file->getClientOriginalName();
	                $file->move($destination,$path);
	                
            }else{
            	$path = '';
            }
         DB::connection()->table("prl_pajak")
            ->insert([
                
                "m_periode_absen_id" => $request->get("periode"),
                "p_karyawan_pemilik_id" => $request->get("karyawan"),
                "create_date" 	=> date('Y-m-d'),
                "create_by" 	=> $idUser,
                "file"=>$path
            ]);
			
        return redirect()->route('be.filepajak')->with('success',' filepajak Berhasil di input!');
    }
 	public function simpan_multifilepajak(Request $request){
    	
	  // echo $kode;die; 
	  $idUser=Auth::user()->id;
	  $karyawan = $request->get("karyawan");
	 $file2 = $request->get("file");
	  for($i=0;$i<count($karyawan);$i++){
	  	echo $karyawan[$i].'<br>';
	  	//print_r($request->get("bukti".$i));
       	 if(!empty($karyawan[$i])){
       	 	
       	 if($request->file("bukti".$i)){
       	 	echo 'masuk';
	                $file = $request->file("bukti".$i);
	                $destination="dist/img/file/";
	                $path='pajak-'.date('ymdhis').'-'.$file->getClientOriginalName();
	                $file->move($destination,$path);
	       }else{
	       	$path='';
	       }          
          
				
         DB::connection()->table("prl_pajak")
            ->insert([
                
                "tahun" => $request->get("periode"),
                "p_karyawan_pemilik_id" => $karyawan[$i],
                "create_date" 	=> date('Y-m-d'),
                "create_by" 	=> $idUser,
                "file"=>$path
            ]);
        }
       
	  }
			
        return redirect()->route('be.filepajak')->with('success',' filepajak Berhasil di input!');
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
        
        return view('backend.filepajak.tambah_filepajak', compact('data','id','type','entitas'));
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

        return redirect()->route('be.filepajak')->with('success',' filepajak Berhasil di Ubah!');
    }

    public function hapus_filepajak($id)
    {
        $idUser=Auth::user()->id;
        DB::connection()->table("prl_pajak")
            ->where("prl_pajak_id",$id)
            ->update([
                "active"=>0,
               
            ]);

        return redirect()->back()->with('success',' filepajak Berhasil di Hapus!');
    }
}

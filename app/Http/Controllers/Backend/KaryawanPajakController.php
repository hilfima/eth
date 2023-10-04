<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Auth;

class KaryawanpajakController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }public function pajak()
    {
    	$iduser=Auth::user()->id; 
		$sqluser="SELECT p_recruitment.foto,role,p_karyawan_pekerjaan,p_karyawan_pekerjaan.m_lokasi_id,user_entitas_access FROM users
				left join m_role on m_role.m_role_id=users.role
				left join p_karyawan on p_karyawan.user_id=users.id
				left join p_karyawan_pekerjaan on p_karyawan_pekerjaan.p_karyawan_id=p_karyawan.p_karyawan_id
				left join p_recruitment on p_recruitment.p_recruitment_id=p_karyawan.p_recruitment_id
				where users.id=$iduser";
        $user=DB::connection()->select($sqluser);
		$id_lokasi = Auth::user()->user_entitas;
        if($id_lokasi and $id_lokasi!=-1) 
			$whereLokasi = "AND m_lokasi.m_lokasi_id in($id_lokasi)";					
		else
			$whereLokasi = "";	
        $sqlpajak="SELECT *,p_karyawan.nama as nama,m_lokasi.nama as nmlokasi FROM p_karyawan 
        		join p_karyawan_pekerjaan on p_karyawan_pekerjaan.p_karyawan_id=p_karyawan.p_karyawan_id
        		left join m_lokasi  on p_karyawan_pekerjaan.m_lokasi_id=m_lokasi.m_lokasi_id
                WHERE 1=1 and p_karyawan.active=1 $whereLokasi order by p_karyawan.nama ";
        $pajak=DB::connection()->select($sqlpajak);
       
     

        return view('backend.gaji.karyawan_pajak.pajak',compact('pajak'));
    }

    public function tambah_pajak()
    {
        $iduser=Auth::user()->id;
        $sqluser="SELECT p_recruitment.foto FROM users
left join p_karyawan on p_karyawan.user_id=users.id
left join p_recruitment on p_recruitment.p_recruitment_id=p_karyawan.p_recruitment_id
where users.id=$iduser";
        $user=DB::connection()->select($sqluser);
        return view('backend.gaji.karyawan_pajak.tambah_pajak',compact('user'));
    }

    public function simpan_pajak(Request $request){
        DB::beginTransaction();
        try{
            $idUser=Auth::user()->id;
            DB::connection()->table("hr_care")
                ->insert([
                    "judul"=>($request->get("judul")),
                    "deskripsi"=>($request->get("deskripsi_pajak")),
                    "tgl_posting"=>date('Y-m-d',strtotime($request->get("tgl_posting"))),
                    "tgl_posting_akhir"=>date('Y-m-d',strtotime($request->get("tgl_posting_akhir"))),
                    "create_by"=>$idUser,
                    "create_date" => date("Y-m-d H:i:s")
                ]);
            DB::commit();
            return redirect()->route('be.pajak')->with('success','Berita Berhasil di input!');
        }
        catch(\Exeception $e){
            DB::rollback();
            return redirect()->back()->with('error',$e);
        }

    }

    public function edit_pajak($id)
    {
         $sqlpajak="SELECT * FROM p_karyawan 
        		join p_karyawan_pekerjaan on p_karyawan_pekerjaan.p_karyawan_id=p_karyawan.p_karyawan_id
                WHERE 1=1 and p_karyawan.active=1 and p_karyawan.p_karyawan_id = $id order by nama ";
        $pajak=DB::connection()->select($sqlpajak);

        
        return view('backend.gaji.karyawan_pajak.edit_pajak', compact('pajak'));
    }

    public function update_pajak(Request $request, $id){
        DB::beginTransaction();
        try{
            $idUser=Auth::user()->id;
             $karyawan1 = DB::connection()->select("select * from p_karyawan join p_karyawan_pekerjaan on p_karyawan.p_karyawan_id = p_karyawan_pekerjaan.p_karyawan_id where p_karyawan_pekerjaan_id  = $id");
            
            DB::connection()->table("p_karyawan_pekerjaan")
                ->where("p_karyawan_pekerjaan_id",$id)
                ->update([
                    "pajak_onoff"=>($request->get("pajak")),
                    
                ]);
                 $array = array("m_jabatan_id","m_departemen_id","m_lokasi_id","m_bank_id","m_kantor_id","m_divisi_id","m_directorat_id","m_divisi_new_id","bpjs_kantor","tgl_bpjs_kantor","norek","periode_gajian","nik","kota","is_shift","pajak_onoff");    
		          
		            $karyawan2 	= DB::connection()->select("select * from p_karyawan join p_karyawan_pekerjaan on p_karyawan.p_karyawan_id = p_karyawan_pekerjaan.p_karyawan_id where p_karyawan_pekerjaan_id  = $id");
		           
		            $data4['p_karyawan_id']	=$karyawan1[0]->p_karyawan_id;
		            $data4['create_by']		=$idUser;
		            $data4['create_date']	=date('Y-m-d H:i:s');
		            foreach($karyawan1 as $karyawan){
		            	for($i=0;$i<count($array);$i++){
		            		$row = $array[$i];
			            	$data4['dari_'.$array[$i]] = $karyawan->$row;
		            	}
		            }
		            foreach($karyawan2 as $karyawan){
		            	for($i=0;$i<count($array);$i++){
		            		$row = $array[$i];
			            	$data4['ke_'.$array[$i]] = $karyawan->$row;
		            	}
		            }
		            print_r($data4);
		            DB::connection()->table("p_historis_pekerjaan")
		                ->insert($data4);
            DB::commit();
            //return redirect()->route('be.karyawan_pajak')->with('success','Pajak Karyawan Berhasil di Ubah!');
        }
        catch(\Exeception $e){
            DB::rollback();
            return redirect()->back()->with('error',$e);
        }
    }

    public function hapus_pajak($id)
    {
        DB::beginTransaction();
        try{
            DB::connection()->table("hr_care")
                ->where("hr_care_id",$id)
                ->delete();
            DB::commit();
            return redirect()->route('be.pajak')->with('success','Berita Berhasil di Hapus!');
        }
        catch(\Exeception $e){
            DB::rollback();
            return redirect()->back()->with('error',$e);
        }
    }
}

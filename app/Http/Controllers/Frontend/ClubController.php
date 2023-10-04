<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Auth;
use App\Helper_function;
class ClubController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }public function club()
    {
    	$iduser=Auth::user()->id;
		$sqlidkar="select * from p_karyawan where user_id=$iduser";
		$idkar=DB::connection()->select($sqlidkar);
		$id=$idkar[0]->p_karyawan_id;
		$id_karyawan=$idkar[0]->p_karyawan_id;
		
        $sqlclub="SELECT * FROM club
        		join club_karyawan  on club_karyawan.p_karyawan_id = $id_karyawan and club_karyawan.club_id = club.club_id
                WHERE 1=1 and active=1";
        $club=DB::connection()->select($sqlclub);
		$help = new Helper_function();

        return view('frontend.club.club',compact('club',"help"));
    }public function anggota_club($id)
    {
    	$iduser=Auth::user()->id;
		$sqlidkar="select * from p_karyawan where user_id=$iduser";
		$idkar=DB::connection()->select($sqlidkar);
		$id_karyawan=$idkar[0]->p_karyawan_id;
		
        $sqlclub="SELECT * FROM club_karyawan
        		join p_karyawan on club_karyawan.p_karyawan_id = p_karyawan.p_karyawan_id
                WHERE 1=1  and p_karyawan.p_karyawan_id = $id_karyawan";
        $meclub=DB::connection()->select($sqlclub);
		$sqlclub="SELECT * FROM club_karyawan
        		join p_karyawan on club_karyawan.p_karyawan_id = p_karyawan.p_karyawan_id
                WHERE 1=1 and club_id = $id ";
        $club=DB::connection()->select($sqlclub);


        return view('frontend.club.anggota_club',compact('club','meclub','id'));
    }
	public function foto_kegiatan_club($id,$id_kegiatan)
    {
        $sqlclub="SELECT * FROM club_foto
        		
                WHERE 1=1 and active=1 and club_kegiatan_id = $id_kegiatan";
        $club=DB::connection()->select($sqlclub);
		$help = new Helper_function();

        return view('frontend.club.foto_kegiatan_club',compact('club','id','id_kegiatan','help'));
    }

	public function kegiatan_club($id)
    {
    	$iduser=Auth::user()->id;
		$sqlidkar="select * from p_karyawan where user_id=$iduser";
		$idkar=DB::connection()->select($sqlidkar);
		$id_karyawan=$idkar[0]->p_karyawan_id;
		
        $sqlclub="SELECT * FROM club_karyawan
        		join p_karyawan on club_karyawan.p_karyawan_id = p_karyawan.p_karyawan_id
                WHERE 1=1  and p_karyawan.p_karyawan_id = $id_karyawan";
        $meclub=DB::connection()->select($sqlclub);
        $sqlclub="SELECT * FROM club_kegiatan
        		
                WHERE 1=1 and club_id = $id";
        $club=DB::connection()->select($sqlclub);
		$help = new Helper_function();

        return view('frontend.club.kegiatan_club',compact('club','id','help','meclub'));
    }public function galeri_club($id)
    {
        $sqlclub="SELECT * FROM club_foto
        		
                WHERE 1=1 and club_id = $id";
        $club=DB::connection()->select($sqlclub);
		$help = new Helper_function();

        return view('frontend.club.galeri',compact('club','id','help'));
    }

    public function tambah_kegiatan_club($id)
    {
        $sqlclub="SELECT * FROM p_karyawan
                WHERE 1=1 and active=1";
        $karyawan=DB::connection()->select($sqlclub);

        return view('frontend.club.tambah_kegiatan_club',compact('karyawan','id'));
    }public function tambah_foto_kegiatan_club ($id,$id_kegiatan)
    {
        return view('frontend.club.tambah_foto_kegiatan_club ',compact('id','id_kegiatan'));
    }public function simpan_foto_kegiatan_club(Request $request,$id,$id_kegiatan){
    	DB::beginTransaction(); 
        try{
            
            
            $idUser=Auth::user()->id;
            
    		if ($request->file('file')) { //echo 'masuk';die;
    			for($i=0;$i<count($request->file('file'));$i++){
    				
					$file = $request->file('file')[$i];
					$destination = "dist/img/file/";
					$path = 'club-' . date('ymdhis') . '-' . $file->getClientOriginalName();
					$file->move($destination, $path);
					
					
					
		            $data['club_id'] 			=$id;
		            $data['club_kegiatan_id'] 	=$id_kegiatan;
		            $data['active'] 			=1;
		            $data['foto'] 				=$path;
			        $data['upload_by'] 			=$idUser;
	            	$data['upload_date'] 		=date("Y-m-d H:i:s");
					DB::connection()->table("club_foto")
                		->insert($data);
    			}
			}
            
                
           
                
            DB::commit();
            return redirect()->route('fe.foto_kegiatan_club',[$id,$id_kegiatan])->with('success','Club Berhasil di input!');
        }
        catch(\Exeception $e){
            DB::rollback();
            return redirect()->back()->with('error',$e);
        }
    }public function simpan_kegiatan_club(Request $request,$id){
        DB::beginTransaction(); 
        try{
            $idUser=Auth::user()->id;
            $data = $request->get('data');
            $data['club_id'] =$id;
            $data['create_by'] =$idUser;
            $data['create_date'] =date("Y-m-d H:i:s");
            DB::connection()->table("club_kegiatan")
                ->insert($data);
                
           
                
            DB::commit();
            return redirect()->route('fe.kegiatan_club',$id)->with('success','Club Berhasil di input!');
        }
        catch(\Exeception $e){
            DB::rollback();
            return redirect()->back()->with('error',$e);
        }

    }
public function tambah_anggota_club($id)
    {
        $sqlclub="SELECT * FROM p_karyawan
                WHERE 1=1 and active=1";
        $karyawan=DB::connection()->select($sqlclub);

        return view('frontend.club.tambah_anggota_club',compact('karyawan','id'));
    }
     public function simpan_anggota_club(Request $request,$id){
        DB::beginTransaction();
        try{
            $idUser=Auth::user()->id;
            
            if(isset($request->karyawan_admin[0])){
            	for($i=0;$i<count($request->karyawan_admin);$i++){
            		DB::connection()->table("club_karyawan")
                		->insert([
                			"club_id"=>$id,
                			"p_karyawan_id"=>$request->karyawan_admin[$i],
                			"role_manager_club"=>2,
                			]);
            	}
            }
            if(isset($request->karyawan_anggota[0])){
            	for($i=0;$i<count($request->karyawan_anggota);$i++){
            		DB::connection()->table("club_karyawan")
                		->insert([
                			"club_id"=>$id,
                			"p_karyawan_id"=>$request->karyawan_anggota[$i],
                			"role_manager_club"=>1,
                			]);
            	}
            }
                
            DB::commit();
            return redirect()->route('fe.anggota_club',$id)->with('success','Club Berhasil di input!');
        }
        catch(\Exeception $e){
            DB::rollback();
            return redirect()->back()->with('error',$e);
        }

    }public function hapus_anggota_club($id,$id_anggota)
    {
        DB::beginTransaction();
        try{
            DB::connection()->table("club_karyawan")
                ->where("club_karyawan_id",$id_anggota)
                ->delete();
            DB::commit();
            return redirect()->route('fe.anggota_club',$id)->with('success','Club Berhasil di Hapus!');
        }
        catch(\Exeception $e){
            DB::rollback();
            return redirect()->back()->with('error',$e);
        }
    }public function hapus_foto_club($id,$id_kegiatan,$id_foto)
    {
        DB::beginTransaction();
        try{
            DB::connection()->table("club_foto")
                ->where("club_foto_id",$id_foto)
                ->update(["active"=>0]);
            DB::commit();
            return redirect()->route('fe.foto_kegiatan_club',[$id,$id_kegiatan])->with('success','Club Berhasil di Hapus!');
        }
        catch(\Exeception $e){
            DB::rollback();
            return redirect()->back()->with('error',$e);
        }
    }

    public function tambah_club()
    {
        $sqlclub="SELECT * FROM p_karyawan
                WHERE 1=1 and active=1";
        $karyawan=DB::connection()->select($sqlclub);

        return view('frontend.club.tambah_club',compact('karyawan'));
    }

    public function simpan_club(Request $request){
        DB::beginTransaction();
        try{
            $idUser=Auth::user()->id;
            $data = $request->get('data');
            $data['create_by'] =$idUser;
            $data['create_date'] =date("Y-m-d H:i:s");
            DB::connection()->table("club")
                ->insert($data);
                
            $seq =     DB::connection()->select("select * from seq_club");
            if(isset($request->karyawan_admin[0])){
            	for($i=0;$i<count($request->karyawan_admin);$i++){
            		DB::connection()->table("club_karyawan")
                		->insert([
                			"club_id"=>$seq[0]->last_value,
                			"p_karyawan_id"=>$request->karyawan_admin[$i],
                			"role_manager_club"=>2,
                			]);
            	}
           }
            if(isset($request->karyawan_anggota[0])){
            	for($i=0;$i<count($request->karyawan_anggota);$i++){
            		DB::connection()->table("club_karyawan")
                		->insert([
                			"club_id"=>$seq[0]->last_value,
                			"p_karyawan_id"=>$request->karyawan_anggota[$i],
                			"role_manager_club"=>1,
                			]);
            	}
            }
                
            DB::commit();
            return redirect()->route('fe.club')->with('success','Club Berhasil di input!');
        }
        catch(\Exeception $e){
            DB::rollback();
            return redirect()->back()->with('error',$e);
        }

    }

    public function edit_club($id)
    {
        $sqlclub="SELECT * FROM club
                WHERE club_id=$id";
        $club=DB::connection()->select($sqlclub);

        $iduser=Auth::user()->id;
        $sqluser="SELECT p_recruitment.foto FROM users
left join p_karyawan on p_karyawan.user_id=users.id
left join p_recruitment on p_recruitment.p_recruitment_id=p_karyawan.p_recruitment_id
where users.id=$iduser";
        $user=DB::connection()->select($sqluser);

        return view('frontend.club.edit_club', compact('club','id'));
    }

    public function update_club(Request $request, $id){
        DB::beginTransaction();
        try{
            $idUser=Auth::user()->id;
            $data = $request->get('data');
            $data['update_by'] =$idUser;
            $data['update_date'] =date("Y-m-d H:i:s");
            DB::connection()->table("club")
                ->where("club_id",$id)
                ->update($data);
            DB::commit();
            return redirect()->route('fe.club')->with('success','Club Berhasil di Ubah!');
        }
        catch(\Exeception $e){
            DB::rollback();
            return redirect()->back()->with('error',$e);
        }
    }

    public function hapus_club($id)
    {
        DB::beginTransaction();
        try{
            DB::connection()->table("club")
                ->where("club_id",$id)
                ->delete();
            DB::commit();
            return redirect()->route('fe.club')->with('success','Club Berhasil di Hapus!');
        }
        catch(\Exeception $e){
            DB::rollback();
            return redirect()->back()->with('error',$e);
        }
    }
}

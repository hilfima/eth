<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Auth;

class GalleryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }public function Gallery()
    {
        $sqlgallery="SELECT * FROM gallery
                WHERE 1=1 and active=1 order by created_at";
        $gallery=DB::connection()->select($sqlgallery);

        $iduser=Auth::user()->id;
        $sqluser="SELECT p_recruitment.foto FROM users
left join p_karyawan on p_karyawan.user_id=users.id
left join p_recruitment on p_recruitment.p_recruitment_id=p_karyawan.p_recruitment_id
where users.id=$iduser";
        $user=DB::connection()->select($sqluser);

        return view('backend.gallery.gallery',compact('gallery','user'));
    }

    public function tambah_gallery()
    {
        $iduser=Auth::user()->id;
        $sqluser="SELECT p_recruitment.foto FROM users
left join p_karyawan on p_karyawan.user_id=users.id
left join p_recruitment on p_recruitment.p_recruitment_id=p_karyawan.p_recruitment_id
where users.id=$iduser";
        $user=DB::connection()->select($sqluser);
        return view('backend.gallery.tambah_gallery',compact('user'));
    }

    public function simpan_gallery(Request $request){
        try{
            $request->validate([
                'foto' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);
            $data = $request->all();
            $idUser=Auth::User()->id;

            if(empty($request->input('foto'))){
                $data['foto'] = $request->input('foto');
            }
            DB::connection()->beginTransaction();
            DB::connection()->table("gallery")
                ->insert([
                    "m_lokasi_id"=>3,
                    "active"=>1,
                    "created_by"=>$idUser,
                    "created_at"=>date("Y-m-d H:i:s"),
                ]);

            if($request->file('foto')){//echo 'masuk';die;
                $file = $request->file('foto');
                $destination="dist/img/gallery/";
                $file->move($destination,$file->getClientOriginalName());
                $path=$file->getClientOriginalName();
                //echo $path;die;
                DB::connection()->table("gallery")->where("nama",$request->input("foto"))
                    ->update([
                        "nama"=>$path
                    ]);
            }
            $Err=error_get_last();
            if(isset($Err['message']) && trim($Err['message'])!=''){
                throw new \Exception('file:'.$Err['file'].'<br>line:'.$Err['line'].'<br>msg:'.preg_replace('/[\r\n]+/', "", addslashes($Err['message'])));
            }
            DB::connection()->commit();
            session()->flash('status', 'Task was successful!');
            return redirect()->route('be.gallery');
        }
        catch(\Exeception $e){
            DB::rollback();
            return redirect()->back()->with('error',$e);
        }

    }

    public function edit_gallery($id)
    {
        $sqlgallery="SELECT * FROM gallery
                WHERE gallery_id=$id";
        $gallery=DB::connection()->select($sqlgallery);

        $iduser=Auth::user()->id;
        $sqluser="SELECT p_recruitment.foto FROM users
left join p_karyawan on p_karyawan.user_id=users.id
left join p_recruitment on p_recruitment.p_recruitment_id=p_karyawan.p_recruitment_id
where users.id=$iduser";
        $user=DB::connection()->select($sqluser);

        return view('backend.gallery.edit_gallery', compact('gallery','user'));
    }

    public function update_gallery(Request $request, $id){
        DB::beginTransaction();
        try{
            $idUser=Auth::user()->id;
            DB::connection()->table("hr_care")
                ->where("hr_care_id",$id)
                ->update([
                    "judul"=>($request->get("judul")),
                    "deskripsi"=>($request->get("deskripsi_berita")),
                    "tgl_posting"=>date('Y-m-d',strtotime($request->get("tgl_posting"))),
                    "update_by"=>$idUser,
                    "update_date" => date("Y-m-d H:i:s")
                ]);
            DB::commit();
            return redirect()->route('be.gallery')->with('success','Gallery Berhasil di Ubah!');
        }
        catch(\Exeception $e){
            DB::rollback();
            return redirect()->back()->with('error',$e);
        }
    }

    public function hapus_gallery($id)
    {
        DB::beginTransaction();
        try{
            DB::connection()->table("gallery")
                ->where("gallery_id",$id)
                ->delete();
            DB::commit();
            return redirect()->route('be.gallery')->with('success','Gallery Berhasil di Ubah!');
        }
        catch(\Exeception $e){
            DB::rollback();
            return redirect()->back()->with('error',$e);
        }
    }
}

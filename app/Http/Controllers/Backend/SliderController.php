<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Auth;

class SliderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }public function slider()
    {
        $sqlslider="SELECT * FROM m_slider
                WHERE 1=1 and active=1 order by m_slider_id";
        $slider=DB::connection()->select($sqlslider);

        $iduser=Auth::user()->id;
        $sqluser="SELECT p_recruitment.foto FROM users
left join p_karyawan on p_karyawan.user_id=users.id
left join p_recruitment on p_recruitment.p_recruitment_id=p_karyawan.p_recruitment_id
where users.id=$iduser";
        $user=DB::connection()->select($sqluser);

        return view('backend.slider.slider',compact('slider','user'));
    }

    public function tambah_slider()
    {
        $iduser=Auth::user()->id;
        $sqluser="SELECT p_recruitment.foto FROM users
left join p_karyawan on p_karyawan.user_id=users.id
left join p_recruitment on p_recruitment.p_recruitment_id=p_karyawan.p_recruitment_id
where users.id=$iduser";
        $user=DB::connection()->select($sqluser);
        return view('backend.slider.tambah_slider',compact('user'));
    }

    public function simpan_slider(Request $request){
        DB::beginTransaction();
        try{
            $idUser=Auth::user()->id;
            DB::connection()->table("m_slider")
                ->insert([
                    "nama"=>($request->get("nama")),
                    "created_by"=>$idUser,
                    "created_at" => date("Y-m-d H:i:s")
                ]);

            if($request->file('file')){//echo 'masuk';die;
                $file = $request->file('file');
                $destination="dist/img/landingpage/";
                $file->move($destination,$file->getClientOriginalName());
                $path=$file->getClientOriginalName();
                //echo $path;die;
                DB::connection()->table("m_slider")->where("nama",$request->input("nama"))
                    ->update([
                        "file"=>$path
                    ]);
            }

            DB::commit();
            return redirect()->route('be.slider')->with('success','Slider Berhasil di input!');
        }
        catch(\Exeception $e){
            DB::rollback();
            return redirect()->back()->with('error',$e);
        }

    }

    public function edit_slider($id)
    {
        $sqlslider="SELECT * FROM m_slider
                WHERE m_slider_id=$id";
        $slider=DB::connection()->select($sqlslider);

        $iduser=Auth::user()->id;
        $sqluser="SELECT p_recruitment.foto FROM users
left join p_karyawan on p_karyawan.user_id=users.id
left join p_recruitment on p_recruitment.p_recruitment_id=p_karyawan.p_recruitment_id
where users.id=$iduser";
        $user=DB::connection()->select($sqluser);

        return view('backend.slider.edit_slider', compact('slider','user'));
    }

    public function update_slider(Request $request, $id){
        DB::beginTransaction();
        try{
            $idUser=Auth::user()->id;
            DB::connection()->table("m_slider")
                ->where("m_slider_id",$id)
                ->update([
                    "nama"=>($request->get("nama")),
                    "updated_by"=>$idUser,
                    "updated_at" => date("Y-m-d H:i:s")
                ]);

            if($request->file('file')){//echo 'masuk';die;
                $file = $request->file('file');
                $destination="dist/img/landingpage/";
                $file->move($destination,$file->getClientOriginalName());
                $path=$file->getClientOriginalName();
                //echo $path;die;
                DB::connection()->table("m_slider")->where("nama",$request->input("nama"))
                    ->update([
                        "file"=>$path
                    ]);
            }

            DB::commit();
            return redirect()->route('be.slider')->with('success','Slider Berhasil di Ubah!');
        }
        catch(\Exeception $e){
            DB::rollback();
            return redirect()->back()->with('error',$e);
        }
    }

    public function hapus_slider($id)
    {
        DB::beginTransaction();
        try{
            DB::connection()->table("m_slider")
                ->where("m_slider_id",$id)
                ->delete();
            DB::commit();
            return redirect()->route('be.slider')->with('success','Slider Berhasil di Hapus!');
        }
        catch(\Exeception $e){
            DB::rollback();
            return redirect()->back()->with('error',$e);
        }
    }
}

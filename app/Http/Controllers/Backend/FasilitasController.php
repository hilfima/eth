<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Auth;

class FasilitasController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function fasilitas()
    {
        $sqlfasilitas="SELECT * FROM m_fasilitas
                WHERE 1=1 and active=1 order by m_fasilitas_id";
        $fasilitas=DB::connection()->select($sqlfasilitas);

        $iduser=Auth::user()->id;
        $sqluser="SELECT p_recruitment.foto FROM users
left join p_karyawan on p_karyawan.user_id=users.id
left join p_recruitment on p_recruitment.p_recruitment_id=p_karyawan.p_recruitment_id
where users.id=$iduser";
        $user=DB::connection()->select($sqluser);

        return view('backend.fasilitas.fasilitas',compact('fasilitas','user'));
    }

    public function tambah_fasilitas()
    {
        $iduser=Auth::user()->id;
        $sqluser="SELECT p_recruitment.foto FROM users
left join p_karyawan on p_karyawan.user_id=users.id
left join p_recruitment on p_recruitment.p_recruitment_id=p_karyawan.p_recruitment_id
where users.id=$iduser";
        $user=DB::connection()->select($sqluser);
        return view('backend.fasilitas.tambah_fasilitas',compact('user'));
    }

    public function simpan_fasilitas(Request $request){
        DB::beginTransaction();
        try{
            $idUser=Auth::user()->id;
            DB::connection()->table("m_fasilitas")
                ->insert([
                    "nama"=>($request->get("nama")),
                    "created_by"=>$idUser,
                    "created_at" => date("Y-m-d H:i:s")
                ]);

            if($request->file('file')){//echo 'masuk';die;
                $file = $request->file('file');
                $destination="dist/img/fasilitas/";
                $file->move($destination,$file->getClientOriginalName());
                $path=$file->getClientOriginalName();
                //echo $path;die;
                DB::connection()->table("m_fasilitas")->where("nama",$request->input("nama"))
                    ->update([
                        "file"=>$path
                    ]);
            }

            DB::commit();
            return redirect()->route('be.fasilitas')->with('success','Fasilitas Berhasil di input!');
        }
        catch(\Exeception $e){
            DB::rollback();
            return redirect()->back()->with('error',$e);
        }

    }

    public function edit_fasilitas($id)
    {
        $sqlfasilitas="SELECT * FROM m_fasilitas
                WHERE m_fasilitas_id=$id";
        $fasilitas=DB::connection()->select($sqlfasilitas);

        $iduser=Auth::user()->id;
        $sqluser="SELECT p_recruitment.foto FROM users
left join p_karyawan on p_karyawan.user_id=users.id
left join p_recruitment on p_recruitment.p_recruitment_id=p_karyawan.p_recruitment_id
where users.id=$iduser";
        $user=DB::connection()->select($sqluser);

        return view('backend.fasilitas.edit_fasilitas', compact('fasilitas','user'));
    }

    public function update_fasilitas(Request $request, $id){
        DB::beginTransaction();
        try{
            $idUser=Auth::user()->id;
            DB::connection()->table("m_fasilitas")
                ->where("m_fasilitas_id",$id)
                ->update([
                    "nama"=>($request->get("nama")),
                    "updated_by"=>$idUser,
                    "updated_at" => date("Y-m-d H:i:s")
                ]);

            if($request->file('file')){//echo 'masuk';die;
                $file = $request->file('file');
                $destination="dist/img/fasilitas/";
                $file->move($destination,$file->getClientOriginalName());
                $path=$file->getClientOriginalName();
                //echo $path;die;
                DB::connection()->table("m_fasilitas")->where("nama",$request->input("nama"))
                    ->update([
                        "file"=>$path
                    ]);
            }

            DB::commit();
            return redirect()->route('be.fasilitas')->with('success','Fasilitas Berhasil di Ubah!');
        }
        catch(\Exeception $e){
            DB::rollback();
            return redirect()->back()->with('error',$e);
        }
    }

    public function hapus_fasilitas($id)
    {
        DB::beginTransaction();
        try{
            DB::connection()->table("m_fasilitas")
                ->where("m_fasilitas_id",$id)
                ->delete();
            DB::commit();
            return redirect()->route('be.fasilitas')->with('success','Fasilitas Berhasil di Hapus!');
        }
        catch(\Exeception $e){
            DB::rollback();
            return redirect()->back()->with('error',$e);
        }
    }
}

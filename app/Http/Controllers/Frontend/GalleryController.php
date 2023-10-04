<?php

namespace App\Http\Controllers\Frontend;

use App\absenpro_xls;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Auth;
use Response;
use Maatwebsite\Excel\Facades\Excel;
use App\Eloquent\AbsenLog;
use App\Eloquent\Absen;

class GalleryController extends Controller
{
    public function show_gallery()
    {
        $iduser=Auth::user()->id;
        $sqlidkar="select * from p_karyawan where user_id=$iduser";
        $idkar=DB::connection()->select($sqlidkar);
        $id=$idkar[0]->p_karyawan_id;

        //$sqlgallery="select * from gallery WHERE active=1 ORDER BY nama";
        //$gallery=DB::connection()->select($sqlgallery)->paginate(10);
        $gallery = DB::table('gallery')->paginate(10);
        //var_dump($gallery);die;
        $sqluser="SELECT p_recruitment.foto FROM users
left join p_karyawan on p_karyawan.user_id=users.id
left join p_recruitment on p_recruitment.p_recruitment_id=p_karyawan.p_recruitment_id
where users.id=$iduser";
        $user=DB::connection()->select($sqluser);

        return view('frontend.gallery.gallery',compact('gallery','user'));
    }

    public function show_gallery_detail($idgallery)
    {
        $iduser=Auth::user()->id;
        $sqlidkar="select * from p_karyawan where user_id=$iduser";
        $idkar=DB::connection()->select($sqlidkar);
        $id=$idkar[0]->p_karyawan_id;

        $sqlgallery="select * from gallery WHERE gallery_id=$idgallery and active=1 ORDER BY nama";
        $gallery=DB::connection()->select($sqlgallery);
        //var_dump($gallery);die;

        $sqluser="SELECT p_recruitment.foto FROM users
left join p_karyawan on p_karyawan.user_id=users.id
left join p_recruitment on p_recruitment.p_recruitment_id=p_karyawan.p_recruitment_id
where users.id=$iduser";
        $user=DB::connection()->select($sqluser);

        return view('frontend.gallery.gallery_detail',compact('gallery','user'));
    }

}

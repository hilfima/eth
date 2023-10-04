<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Auth;
use File;

class FasilitasController extends Controller
{
	public function __construct()
	{
		$this->middleware('auth');
	}
	public function show_fasilitas()
    {
        $sqlfasilitas="SELECT * FROM m_fasilitas
                WHERE 1=1 and active=1 order by m_fasilitas_id";
        $fasilitas=DB::connection()->select($sqlfasilitas);

        return view('frontend.fasilitas.fasilitas',compact('fasilitas'));
    }

    public function download_fasilitas($id){
        $query=DB::connection()->select("select * from m_fasilitas WHERE m_fasilitas_id='".$id."' ");
        //return $query;die;
        if (File::exists('dist/img/fasilitas/'.$query[0]->file)) {
            return response()->download('dist/img/fasilitas/'.$query[0]->file);
        }else{
            return false;
        }
    }
}

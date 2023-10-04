<?php

namespace App\Http\Controllers\Frontend;
use App\Http\Controllers\Controller;

use App\rekaplembur_xls;
use App\User;
use App\Helper_function;
use Illuminate\Http\Request;
use DB;
use Auth;
use PhpOffice\PhpSpreadsheet\Reader\Xml\Style\Fill;
use Response;

class SurveiController extends Controller
{
	public function survei(Request $request)
	{
		$iduser=Auth::user()->id;
		$sqlidkar="select * from p_karyawan where user_id=$iduser";
		$idkar=DB::connection()->select($sqlidkar);
		$id=$idkar[0]->p_karyawan_id;

		$sqlfasilitas="SELECT * FROM t_survei
		join t_survei_karyawan on t_survei_karyawan.t_survei_id = t_survei.t_survei_id
		WHERE 1=1  and t_survei_karyawan.p_karyawan_id = $id  ";
		$survei=DB::connection()->select($sqlfasilitas);

		return view('frontend.survei.survei',compact('survei'));
	}
}
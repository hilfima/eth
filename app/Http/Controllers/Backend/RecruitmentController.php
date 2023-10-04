<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Auth;

class RecruitmentController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function recruitment()
    {
        $iduser=Auth::user()->id;
        $sqluser="SELECT p_recruitment.foto FROM users
left join p_karyawan on p_karyawan.user_id=users.id
left join p_recruitment on p_recruitment.p_recruitment_id=p_karyawan.p_recruitment_id
where users.id=$iduser";
        $user=DB::connection()->select($sqluser);

        $sqlrec="SELECT a.*, b.nama as nmkota,c.nama as nmagama,d.nama as nmjk FROM p_recruitment a
LEFT JOIN m_kota b on b.m_kota_id=a.m_kota_id
LEFT JOIN m_agama c on c.m_agama_id=a.m_agama_id
LEFT JOIN m_jenis_kelamin d on d.m_jenis_kelamin_id=a.m_jenis_kelamin_id WHERE 1=1 ORDER BY 1";
        $rec=DB::connection()->select($sqlrec);
        return view('backend.karyawan.recruitment', compact('rec','user'));
    }
}

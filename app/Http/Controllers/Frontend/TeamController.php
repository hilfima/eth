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

class TeamController extends Controller
{
	public function __construct()
	{
		$this->middleware('auth');
	}
	public function team()
    {
    	$iduser=Auth::user()->id;
        $sqlidkar="select * from p_karyawan 
        	join p_karyawan_pekerjaan on p_karyawan_pekerjaan.p_karyawan_id = p_karyawan.p_karyawan_id
        where user_id=$iduser";
        $idkar=DB::connection()->select($sqlidkar);
       // echo 'id_jabatan='.$idkar[0]->m_jabatan_id;
        //echo '<br>';
		$id_karyawan = $idkar[0]->p_karyawan_id;
		$help = new Helper_function();
		$jabstruk = $help->jabatan_struktural($id_karyawan);
		$atasan = $jabstruk['atasan'];
		$bawahan = $jabstruk['bawahan'];
		$sejajar = $jabstruk['sejajar'];
		
		$sql = "select *,p_karyawan.nama as nama_karyawan ,m_jabatan.nama as nmjabatan  from p_karyawan left join p_recruitment on p_recruitment.p_recruitment_id=p_karyawan.p_recruitment_id 
		LEFT JOIN p_karyawan_pekerjaan d on d.p_karyawan_id=p_karyawan.p_karyawan_id  
		LEFT JOIN m_jabatan on m_jabatan.m_jabatan_id=d.m_jabatan_id 
		where (d.m_jabatan_id in ($sejajar) or d.m_jabatan_id in($bawahan)) and d.m_lokasi_id = ".$idkar[0]->m_lokasi_id." and p_karyawan.active=1";
        $karyawan=DB::connection()->select($sql);
    	return view('frontend.team.team',compact('karyawan'));
    }
    	
}
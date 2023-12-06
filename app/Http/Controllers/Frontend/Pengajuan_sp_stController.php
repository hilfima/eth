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
use PDF;

class Pengajuan_sp_stController extends Controller
{
	public function __construct()
    {
        $this->middleware('auth');
    }public function pengajuan_sp_st(Request $request)
	{
		
		$iduser=Auth::user()->id;
		$sqlidkar="select * from p_karyawan 
					join p_karyawan_pekerjaan a on p_karyawan.p_karyawan_id = a.p_karyawan_id 
					where user_id=$iduser";
		
		$idkar=DB::connection()->select($sqlidkar);
		$idkaryawan=$idkar[0]->p_karyawan_id;
		
		$sqlfasilitas="SELECT * FROM p_karyawan_sanksi
		left join p_karyawan on p_karyawan_sanksi.p_karyawan_id = p_karyawan.p_karyawan_id
		left join m_jenis_sanksi on m_jenis_sanksi.m_jenis_sanksi_id = p_karyawan_sanksi.m_jenis_sanksi_id
		WHERE 1=1  and p_karyawan_sanksi.active=1 and p_karyawan_id_yang_mengajukan=$idkaryawan";
		$pengajuan_sp_st=DB::connection()->select($sqlfasilitas);

		return view('frontend.pengajuan_sp_st.pengajuan_sp_st',compact('pengajuan_sp_st'));
	}
	public function edit_pengajuan_sp_st($id)
	{
		$type = 'update_pengajuan_sp_st';
		$sanksi=DB::connection()->select("select * from p_karyawan_sanksi where active=1 and p_karyawan_sanksi_id=$id  ");
		$data['m_jenis_sanksi_id']=$sanksi[0]->m_jenis_sanksi_id;
		$data['p_karyawan_id']=$sanksi[0]->p_karyawan_id;
		$data['tindakan']=$sanksi[0]->tindakan;
		$data['alasan_sanksi']=$sanksi[0]->alasan_sanksi;
		$data['p_karyawan_id']=$sanksi[0]->p_karyawan_id;
		$karyawan=DB::connection()->select("select * from p_karyawan where active=1 order by nama ");
		$jenis_sanksi=DB::connection()->select("select * from m_jenis_sanksi where active=1 order by nama_sanksi ");

		return view('frontend.pengajuan_sp_st.tambah_pengajuan_sp_st',compact('id','data','type','karyawan','jenis_sanksi'));
	}
	public function tambah_pengajuan_sp_st()
	{

		$id = '';
		$type = 'simpan_pengajuan_sp_st';
		$data['m_jenis_sanksi_id']=array();
		$data['p_karyawan_id']='';
		$data['tindakan']='';
		$data['alasan_sanksi']='';
		$data['p_karyawan_id']=array();

		$karyawan=DB::connection()->select("select * from p_karyawan where active=1 order by nama ");
		$jenis_sanksi=DB::connection()->select("select * from m_jenis_sanksi where active=1 order by nama_sanksi ");



		return view('frontend.pengajuan_sp_st.tambah_pengajuan_sp_st',compact('id','data','type','karyawan','jenis_sanksi'));
	}public function simpan_pengajuan_sp_st(Request $request)
	{
		$iduser=Auth::user()->id;
		$sqlidkar="select * from p_karyawan 
					join p_karyawan_pekerjaan a on p_karyawan.p_karyawan_id = a.p_karyawan_id 
					where user_id=$iduser";
		
		$idkar=DB::connection()->select($sqlidkar);
		$idkaryawan=$idkar[0]->p_karyawan_id;
		$data = $request->data;
		if($data['m_jenis_sanksi_id']!=7)
			$data['status'] = 1;
		else
			$data['status'] = 2;
		$data['p_karyawan_id_yang_mengajukan']=$idkaryawan;
		$data['created_date']= date('Y-m-d H:i:s');
		$data['created_by']= $iduser;
		DB::connection()->table("p_karyawan_sanksi")
		->insert($data);


		return redirect()->route('fe.pengajuan_sp_st')->with('success',' Pengajuan Berhasil di input!');
	}public function update_pengajuan_sp_st (Request $request, $id)
	{
		
		DB::connection()->table("p_karyawan_sanksi")
		->where("t_pengajuan_sp_st_id",$id)
		->update([
			"p_karyawan_pengaju" => $request->get("karyawan"),

			"status" => $request->get("status"),
			"jenis_surat" => $request->get("jenis_surat"),
			"keterangan_surat" => $request->get("keterangan_surat")

		]);


		return redirect()->route('fe.pengajuan_sp_st')->with('success',' kotak_laporan Berhasil di input!');
	}public function hapus_pengajuan_sp_st (Request $request, $id)
	{
		
		DB::connection()->table("p_karyawan_sanksi")
		->where("t_pengajuan_sp_st_id",$id)
		->update([
			"active" => 0,


		]);


		return redirect()->route('fe.pengajuan_sp_st')->with('success',' kotak_laporan Berhasil di input!');
	}
}
<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\Setting;
use App\Helper_function;
use DB;
use Mail;

class PermitController extends Controller
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
	
	public function email_appr()
	{
		$sqldata="SELECT a.*,b.nik,b.nama_lengkap,b.jabatan,b.departemen,c.kode as kdpermit,c.nama as nama_ijin,d.nama as nama_appr,tgl_appr_1,status_appr_1 FROM t_permit a
		left join get_data_karyawan() b on b.p_karyawan_id=a.p_karyawan_id
		left join m_jenis_ijin c on c.m_jenis_ijin_id=a.m_jenis_ijin_id
		left join p_karyawan d on d.p_karyawan_id=a.appr_1
		WHERE 1=1 and a.kode='PIZIN.07.21.100001' and a.active=1 and c.tipe=1 ORDER BY a.tgl_awal desc ";
		$data=DB::connection()->select($sqldata);
		return view('email_appr_izin',compact('data'));
	}

	public function status_persetujuan(Request $request)
	{
		$iduser=Auth::user()->id;
		$sqlidkar="select * from p_karyawan where user_id=$iduser";
		$idkar=DB::connection()->select($sqlidkar);
		$id=$idkar[0]->p_karyawan_id;
		$where = '';
		if ($request->get('tgl_awal') and !$request->get('tgl_akhir'))
			$where = "and tgl_awal >= '".$request->get('tgl_awal')."'";
		else if ($request->get('tgl_akhir') and !$request->get('tgl_awal'))
			$where = "and tgl_awal <= '".$request->get('tgl_akhir')."'";
		else if ($request->get('tgl_akhir') and $request->get('tgl_awal'))
			$where = "and tgl_awal >= '".$request->get('tgl_awal')."' and tgl_awal <= '".$request->get('tgl_akhir')."'";

		if ($request->get('ajuan'))
			$where .= ' and c.tipe = '.$request->get('ajuan');
		if (($request->get('status')))
			$where .= ' and a.status_appr_1 = '.$request->get('status');



		$sqlcuti="SELECT a.*,b.nik,b.nama,c.kode,c.nama as nama_ijin,d.nama as nama_appr,tgl_appr_1,status_appr_1 FROM t_permit a
				left join p_karyawan b on b.p_karyawan_id=a.p_karyawan_id
				left join m_jenis_ijin c on c.m_jenis_ijin_id=a.m_jenis_ijin_id
				left join p_karyawan d on d.p_karyawan_id=a.appr_1
				WHERE 1=1 and a.p_karyawan_id=$id $where and a.active=1 ORDER BY a.tgl_awal desc";
		$permit=DB::connection()->select($sqlcuti);
		$help = new Helper_function();
		return view('frontend.permit.status_persetujuan',compact('permit','help','request'));
	}

	public function permit()
	{
		$iduser=Auth::user()->id;
		$sqlidkar="select * from p_karyawan where user_id=$iduser";
		$idkar=DB::connection()->select($sqlidkar);
		$id=$idkar[0]->p_karyawan_id;

		$sqldatakar="select * from get_data_karyawan() where p_karyawan_id=$id and m_pangkat_id IN(3,4,5,6)";
		$datakar=DB::connection()->select($sqldatakar);

		$sqldw="select * from get_data_karyawan() where p_karyawan_id=$id";
		$dw=DB::connection()->select($sqldw);

		$tahun=date('Y');
		$sqlcuti="SELECT * FROM get_list_cuti_tahunan_new($tahun,$id) ";
		$datacuti=DB::connection()->select($sqlcuti);
		if (!empty($datacuti)) {
			$cuti=$datacuti[0]->sisa_cuti;
		} else {
			$cuti=0;
		}


		return view('frontend.permit.permit', compact('datakar','cuti','dw'));
	}
	public function listed(Request $request)
	{
		$iduser=Auth::user()->id;
		$sqlidkar="select * from p_karyawan where user_id=$iduser";
		$idkar=DB::connection()->select($sqlidkar);
		$id=$idkar[0]->p_karyawan_id;
		$where = '';
		if ($request->get('tgl_awal') and !$request->get('tgl_akhir'))
			$where = "and tgl_awal >= '".$request->get('tgl_awal')."'";
		else if ($request->get('tgl_akhir') and !$request->get('tgl_awal'))
			$where = "and tgl_awal <= '".$request->get('tgl_akhir')."'";
		else if ($request->get('tgl_akhir') and $request->get('tgl_awal'))
			$where = "and tgl_awal >= '".$request->get('tgl_awal')."' and tgl_awal <= '".$request->get('tgl_akhir')."'";

		if ($request->get('ajuan'))
			$where .= ' and c.tipe = '.$request->get('ajuan');

		if (($request->get('status')))
			$where .= ' and ((a.status_appr_1 = '.$request->get('status').' and a.m_jenis_ijin_id !=22) or

			((a.status_appr_1 = '.$request->get('status').' and a.appr_2 is null  and a.m_jenis_ijin_id =22) or
			(a.status_appr_2 = '.$request->get('status').'   and a.m_jenis_ijin_id =22)  )

			)';

		$date = date('Y-m-d');
		$sqldata="SELECT a.*,b.nik,b.nama_lengkap,c.kode,c.nama as nama_ijin,appr_2,d.nama as nama_appr,f.nama as nama_appr2,tgl_appr_1,status_appr_1,1,status_appr_2,b.pangkat,b.departemen,appr_1, case when status_appr_1=1 then 'Disetujui' when status_appr_1=2 then 'Ditolak' end as sts_pengajuan,

		case when status_appr_2=1 then 'Disetujui' when status_appr_2=2 then 'Ditolak' end as sts_pengajuan2,e.nama as pjs
		FROM t_permit a
		left join get_data_karyawan() b on b.p_karyawan_id=a.p_karyawan_id
		left join m_jenis_ijin c on c.m_jenis_ijin_id=a.m_jenis_ijin_id
		left join p_karyawan d on d.p_karyawan_id=a.appr_1
		left join p_karyawan f on f.p_karyawan_id=a.appr_2
		left join p_karyawan e on e.p_karyawan_id=a.pjs
		WHERE 1=1 and (a.appr_1=$id or a.appr_2=$id ) $where
			and ((tgl_awal<='$date' and c.tipe=3) or c.tipe!=3)
			and a.active=1  ORDER BY a.status_appr_1 desc, a.status_appr_2 desc,a.tgl_awal desc";
		$data=DB::connection()->select($sqldata);
		//echo $sqldata;
		$where = '';
		if ($request->get('tgl_awal2') and !$request->get('tgl_akhir2'))
			$where = "and tgl_awal >= '".$request->get('tgl_awal2')."'";
		else if ($request->get('tgl_akhir2') and !$request->get('tgl_awal2'))
			$where = "and tgl_awal <= '".$request->get('tgl_akhir2')."'";
		else if ($request->get('tgl_akhir2') and $request->get('tgl_awal2'))
			$where = "and tgl_awal >= '".$request->get('tgl_awal2')."' and tgl_awal <= '".$request->get('tgl_akhir2')."'";

		if ($request->get('ajuan2'))
			$where .= ' and c.tipe = '.$request->get('ajuan2');

		if ($request->get('status2')==-1)
			$where .= ' and a.status_appr_1 = 0';
		else if (($request->get('status2')))
			$where .= ' and a.status_appr_1 = '.$request->get('status2');
		$sqldata="SELECT a.*,b.nik,b.nama_lengkap,c.kode,c.nama as nama_ijin,d.nama as nama_appr,tgl_appr_1,status_appr_1,b.pangkat,b.departemen, case when status_appr_2=1 then 'Disetujui' when status_appr_2=2 then 'Ditolak' end as sts_pengajuan,e.nama as pjs
		FROM t_permit a
		left join get_data_karyawan() b on b.p_karyawan_id=a.p_karyawan_id
		left join m_jenis_ijin c on c.m_jenis_ijin_id=a.m_jenis_ijin_id
		left join p_karyawan d on d.p_karyawan_id=a.appr_2
		left join p_karyawan e on e.p_karyawan_id=a.pjs
		WHERE 1=1 and a.appr_2=$id $where and a.active=1  ORDER BY a.tgl_awal desc";
		$data2=DB::connection()->select($sqldata);
		$help = new Helper_function();
		return view('frontend.permit.list',compact('data','data2','request','help','id'));
		//return view('undermaintenance',compact('data','data2','request','help','id'));
	}

	public function list_cuti(Request $request)
	{
		$iduser=Auth::user()->id;
		$sqlidkar="select * from p_karyawan where user_id=$iduser";
		$idkar=DB::connection()->select($sqlidkar);
		$id=$idkar[0]->p_karyawan_id;
		$where = '';
		$where = '';
		if ($request->get('tgl_awal') and !$request->get('tgl_akhir'))
			$where = "and tgl_awal >= '".$request->get('tgl_awal')."'";
		else if ($request->get('tgl_akhir') and !$request->get('tgl_awal'))
			$where = "and tgl_awal <= '".$request->get('tgl_akhir')."'";
		else if ($request->get('tgl_akhir') and $request->get('tgl_awal'))
			$where = "and tgl_awal >= '".$request->get('tgl_awal')."' and tgl_awal <= '".$request->get('tgl_akhir')."'";
		$sqlcuti="SELECT a.*,b.nik,b.nama,c.kode,c.nama as nama_ijin,d.nama as nama_appr,tgl_appr_1,status_appr_1,e.nama as pjs FROM t_permit a
		left join p_karyawan b on b.p_karyawan_id=a.p_karyawan_id
		left join m_jenis_ijin c on c.m_jenis_ijin_id=a.m_jenis_ijin_id
		left join p_karyawan d on d.p_karyawan_id=a.appr_1
		left join p_karyawan e on e.p_karyawan_id=a.pjs
		WHERE 1=1 and a.p_karyawan_id=$id $where  and a.active=1 and c.tipe=3 ORDER BY a.tgl_awal desc";
		$cuti=DB::connection()->select($sqlcuti);

		return view('frontend.permit.list_cuti',compact('cuti','request'));
	}

	public function list_izin(Request $request)
	{
		$iduser=Auth::user()->id;
		$sqlidkar="select * from p_karyawan where user_id=$iduser";
		$idkar=DB::connection()->select($sqlidkar);
		$id=$idkar[0]->p_karyawan_id;


		$where = '';
		if ($request->get('tgl_awal') and !$request->get('tgl_akhir'))
			$where = "and tgl_awal >= '".$request->get('tgl_awal')."'";
		else if ($request->get('tgl_akhir') and !$request->get('tgl_awal'))
			$where = "and tgl_awal <= '".$request->get('tgl_akhir')."'";
		else if ($request->get('tgl_akhir') and $request->get('tgl_awal'))
			$where = "and tgl_awal >= '".$request->get('tgl_awal')."' and tgl_awal <= '".$request->get('tgl_akhir')."'";
		$sqlizin="SELECT a.*,b.nik,b.nama,c.kode,c.nama as nama_ijin,d.nama as nama_appr,tgl_appr_1,status_appr_1,e.nama as pjs FROM t_permit a
		left join p_karyawan b on b.p_karyawan_id=a.p_karyawan_id
		left join m_jenis_ijin c on c.m_jenis_ijin_id=a.m_jenis_ijin_id
		left join p_karyawan d on d.p_karyawan_id=a.appr_1
		left join p_karyawan e on e.p_karyawan_id=a.pjs
		WHERE 1=1 and a.p_karyawan_id=$id $where and a.active=1 and c.tipe=1 ORDER BY a.tgl_awal desc";
		$izin=DB::connection()->select($sqlizin);

		return view('frontend.permit.list_izin',compact('izin','request'));
	}

	public function list_lembur(Request $request)
	{
		$iduser=Auth::user()->id;
		$sqlidkar="select * from p_karyawan where user_id=$iduser";
		$idkar=DB::connection()->select($sqlidkar);
		$id=$idkar[0]->p_karyawan_id;
		$where = '';
		if ($request->get('tgl_awal') and !$request->get('tgl_akhir'))
			$where = "and tgl_awal >= '".$request->get('tgl_awal')."'";
		else if ($request->get('tgl_akhir') and !$request->get('tgl_awal'))
			$where = "and tgl_awal <= '".$request->get('tgl_akhir')."'";
		else if ($request->get('tgl_akhir') and $request->get('tgl_awal'))
			$where = "and tgl_awal >= '".$request->get('tgl_awal')."' and tgl_awal <= '".$request->get('tgl_akhir')."'";

		$sqllembur="SELECT a.*,b.nik,b.nama,c.kode,c.nama as nama_lembur,d.nama as nama_appr,tgl_appr_1,status_appr_1,
		f.nama as nama_appr2,tgl_appr_2,status_appr_2 ,e.nama as pjs

		FROM t_permit a
		left join p_karyawan b on b.p_karyawan_id=a.p_karyawan_id
		left join m_jenis_ijin c on c.m_jenis_ijin_id=a.m_jenis_ijin_id
		left join p_karyawan d on d.p_karyawan_id=a.appr_1
		left join p_karyawan f on f.p_karyawan_id=a.appr_2
		left join p_karyawan e on e.p_karyawan_id=a.pjs
		WHERE 1=1 and a.p_karyawan_id=$id $where and a.active=1 and c.tipe=2 ORDER BY a.tgl_awal desc";
		$lembur=DB::connection()->select($sqllembur);

		return view('frontend.permit.list_lembur',compact('lembur','request'));
	}
	public function list_perdin(Request $request)
	{
		$iduser=Auth::user()->id;
		$sqlidkar="select * from p_karyawan where user_id=$iduser";
		$idkar=DB::connection()->select($sqlidkar);
		$id=$idkar[0]->p_karyawan_id;
		$where = '';
		if ($request->get('tgl_awal') and !$request->get('tgl_akhir'))
			$where = "and tgl_awal >= '".$request->get('tgl_awal')."'";
		else if ($request->get('tgl_akhir') and !$request->get('tgl_awal'))
			$where = "and tgl_awal <= '".$request->get('tgl_akhir')."'";
		else if ($request->get('tgl_akhir') and $request->get('tgl_awal'))
			$where = "and tgl_awal >= '".$request->get('tgl_awal')."' and tgl_awal <= '".$request->get('tgl_akhir')."'";

		$sqlizin="SELECT a.*,b.nik,b.nama,c.kode,c.nama as nama_ijin,d.nama as nama_appr,tgl_appr_1,status_appr_1,e.nama as pjs FROM t_permit a
		left join p_karyawan b on b.p_karyawan_id=a.p_karyawan_id
		left join m_jenis_ijin c on c.m_jenis_ijin_id=a.m_jenis_ijin_id
		left join p_karyawan d on d.p_karyawan_id=a.appr_1
		left join p_karyawan e on e.p_karyawan_id=a.pjs
		WHERE 1=1 and a.p_karyawan_id=$id $where and a.active=1 and c.tipe=4 ORDER BY a.tgl_awal desc";
		$izin=DB::connection()->select($sqlizin);

		return view('frontend.permit.list_perdin',compact('izin','request'));
	}
	public function setujui_ajuan (Request $request, $kode)
	{

		DB::beginTransaction();
		try {
			$iduser=Auth::user()->id;
			$sqlidkar="select * from p_karyawan where user_id=$iduser";
			$idkar=DB::connection()->select($sqlidkar);
			$id=$idkar[0]->p_karyawan_id;
			DB::connection()->table("t_permit")
			->where("t_form_exit_id",$kode)
			->update([
				"status_appr_1"=>1,
				"appr_1"=>$id,
				"tgl_appr_1" => date("Y-m-d"),
				"update_by" => $id,
				"update_date" => date("Y-m-d H:i:s"),
			]);

			DB::commit();

			return redirect()->route('fe.listed')->with('success','Approval  Berhasil di rubah!');
		} catch (\Exeception $e) {
			DB::rollback();
			return redirect()->back()->with('error',$e);
		}
	}public function setujui_ajuan_2(Request $request, $kode)
	{

		DB::beginTransaction();
		try {
			$iduser=Auth::user()->id;
			$sqlidkar="select * from p_karyawan where user_id=$iduser";
			$idkar=DB::connection()->select($sqlidkar);
			$id=$idkar[0]->p_karyawan_id;
			DB::connection()->table("t_permit")
			->where("t_form_exit_id",$kode)
			->update([
				"status_appr_2"=>1,
				"appr_2"=>$id,
				"tgl_appr_2" => date("Y-m-d"),
				"update_by" => $id,
				"update_date" => date("Y-m-d H:i:s"),
			]);

			DB::commit();

			return redirect()->route('fe.listed')->with('success','Approval  Berhasil di rubah!');
		} catch (\Exeception $e) {
			DB::rollback();
			return redirect()->back()->with('error',$e);
		}
	}public function tolak_ajuan(Request $request, $kode)
	{

		DB::beginTransaction();
		try {
			$iduser=Auth::user()->id;
			$sqlidkar="select * from p_karyawan where user_id=$iduser";
			$idkar=DB::connection()->select($sqlidkar);
			$id=$idkar[0]->p_karyawan_id;
			DB::connection()->table("t_permit")
			->where("t_form_exit_id",$kode)
			->update([
				"status_appr_1"=>2,
				"appr_1"=>$id,
				"tgl_appr_1" => date("Y-m-d"),
				"update_by" => $id,
				"update_date" => date("Y-m-d H:i:s"),
			]);

			DB::commit();

			return redirect()->route('fe.listed')->with('success','Approval  Berhasil di rubah!');
		} catch (\Exeception $e) {
			DB::rollback();
			return redirect()->back()->with('error',$e);
		}
	}public function tolak_ajuan_2(Request $request, $kode)
	{

		DB::beginTransaction();
		try {
			$iduser=Auth::user()->id;
			$sqlidkar="select * from p_karyawan where user_id=$iduser";
			$idkar=DB::connection()->select($sqlidkar);
			$id=$idkar[0]->p_karyawan_id;
			DB::connection()->table("t_permit")
			->where("t_form_exit_id",$kode)
			->update([
				"status_appr_2"=>2,
				"appr_2"=>$id,
				"tgl_appr_2" => date("Y-m-d"),
				"update_by" => $id,
				"update_date" => date("Y-m-d H:i:s"),
			]);

			DB::commit();
			return redirect()->route('fe.listed')->with('success','Approval  Berhasil di rubah!');
			//return redirect()->route('be.list_ajuan',$request->get('type'))->with('success','Pengajuan Berhasil di simpan!');
		} catch (\Exeception $e) {
			DB::rollback();
			return redirect()->back()->with('error',$e);
		}
	}
	public function hirarkiBawahan($id, $e)
	{
		//echo 'e adalah'.$e.'id adalah '.$id.'<br>';
		$filter_Entitas = '';


		$sqljabatan="SELECT *,(select count(*) from m_jabatan_atasan where m_jabatan_atasan.m_atasan_id = a.m_jabatan_id) as countjabatan
		FROM m_jabatan_atasan a
		join m_jabatan b on b.m_jabatan_id = a.m_jabatan_id
		where m_atasan_id = $id $filter_Entitas";
		//echo $sqljabatan;
		$jabatan=DB::connection()->select($sqljabatan);
		$return = array();


		foreach ($jabatan as $j) {
			$Mjabatan = $j-> m_jabatan_id;
			$sqljabatan="SELECT * FROM p_karyawan_pekerjaan a
			join p_karyawan b on b.p_karyawan_id = a.p_karyawan_id
			where m_jabatan_id = $Mjabatan and b.active=1";

			//echo $sqljabatan;
			//echo '<br>';
			$karyawan=DB::connection()->select($sqljabatan);
			$name='';
			if (count($karyawan)) {

				foreach ($karyawan as $k) {
					//echo $k->p_karyawan_id;
					//echo '<br>';
					$e .= $k->p_karyawan_id.',';
					if ($j->countjabatan ) {


						$e .= $this->hirarkiBawahan($j->m_jabatan_id,$e);
					}
				}
			} else {

				if ($j->countjabatan) {
					$e .= $this->hirarkiBawahan($j->m_jabatan_id,$e);
				}
				//$this->hirarki($j->m_jabatan_id,$e);
			}
		}
		// echo '<br>';
		//echo '<br>';
		// print_r($e);
		return $e;
		// print_r($return);
	}
	public function hitung_hari(Request $request)
	{

		$iduser=Auth::user()->id;
		$sqlidkar="select * from p_karyawan
		join p_karyawan_pekerjaan on p_karyawan_pekerjaan.p_karyawan_id = p_karyawan.p_karyawan_id
		where user_id=$iduser";
		$idkar=DB::connection()->select($sqlidkar);
		$id=$idkar[0]->p_karyawan_id;

		$cuti = $request->get('cuti');
		$tgl_awal = $request->get('tgl_awal');
		$tgl_akhir = $request->get('tgl_akhir');
		$date = $tgl_awal;
		$help = new Helper_function();
		$sql="select * from m_hari_libur where tanggal >= '$tgl_awal' and tanggal <='$tgl_akhir'";
		$harilibur = DB::connection()->select($sql);
		$hari_libur = array();
		foreach ($harilibur as $libur) {
			$hari_libur[] = $libur->tanggal;
		}
		$hari_libur_shift = array();
		$sql="select * from absen_libur_shift where tanggal >= '$tgl_awal' and tanggal <='$tgl_akhir'";
		$harilibur = DB::connection()->select($sql);
		foreach ($harilibur as $libur) {
			$hari_libur_shift[$libur->tanggal][$libur->p_karyawan_id] = 1;
		}
		$info_karyawan 		= DB::connection()->select("select * ,(select count(*) from m_karyawan_shift where p_karyawan.p_karyawan_id = m_karyawan_shift.p_karyawan_id and m_karyawan_shift.active=1) as is_karyawan_shift
								from p_karyawan
								left join p_karyawan_absen on p_karyawan.p_karyawan_id = p_karyawan_absen.p_karyawan_id
								left join p_karyawan_pekerjaan on p_karyawan.p_karyawan_id = p_karyawan_pekerjaan.p_karyawan_id
								left join p_karyawan_kontrak on p_karyawan.p_karyawan_id = p_karyawan_kontrak.p_karyawan_id
								left join m_jabatan on m_jabatan.m_jabatan_id = p_karyawan_pekerjaan.m_jabatan_id
								where p_karyawan.p_karyawan_id = $id");




		$count = 0;
		for ($i=0;$i<=$help->hitungHari($tgl_awal,$tgl_akhir);$i++) {

			if (!$info_karyawan[0]->is_karyawan_shift)
				$bool_hari_libur = !(in_array(Helper_function::nama_hari($date),array('Minggu','Sabtu')) or in_array($date,$hari_libur) or isset($hari_libur_shift[$date][$id]) );
			else
				$bool_hari_libur = !(isset($hari_libur_shift[$date][$id])) ;
			if ($bool_hari_libur)
				$count +=1;
			$date = 	$help->tambah_tanggal($date,1);
		}
		echo json_encode(array('count'=>$count));
	}
	public function hitung_hari_tanpa_libur(Request $request)
	{

		$iduser=Auth::user()->id;
		$sqlidkar="select * from p_karyawan
		join p_karyawan_pekerjaan on p_karyawan_pekerjaan.p_karyawan_id = p_karyawan.p_karyawan_id
		where user_id=$iduser";
		$idkar=DB::connection()->select($sqlidkar);
		$id=$idkar[0]->p_karyawan_id;

		$tgl_awal = $request->get('tgl_awal');
		$tgl_akhir = $request->get('tgl_akhir');
		$date = $tgl_awal;
		$help = new Helper_function();

		$count = 0;
		for ($i=0;$i<=$help->hitungHari($tgl_awal,$tgl_akhir);$i++) {

			$count +=1;
			$date = 	$help->tambah_tanggal($date,1);
		}
		echo json_encode(array('count'=>$count));
	}
	public function tambah_cuti()
	{
		$iduser=Auth::user()->id;
		$sqlidkar="select * from p_karyawan where user_id=$iduser";
		$idkar=DB::connection()->select($sqlidkar);
		$id=$idkar[0]->p_karyawan_id;
		$iduser=Auth::user()->id;
		$sqlidkar="select *,p_karyawan_id as karyawan_id from p_karyawan
		join p_karyawan_pekerjaan on p_karyawan_pekerjaan.p_karyawan_id = p_karyawan.p_karyawan_id
		where user_id=$iduser";
		$idkar=DB::connection()->select($sqlidkar);
		$id=$idkar[0]->p_karyawan_id;

		$id_karyawan = $idkar[0]->p_karyawan_id;
		$bawahan = "((select p_karyawan_pekerjaan.p_karyawan_id from p_karyawan_pekerjaan where m_jabatan_id in (select m_jabatan_terkait from m_jabatan_struktural where tipe_struktural=2 and m_jabatan_id = (select m_jabatan_id from p_karyawan_pekerjaan pkp where p_karyawan_id = $id_karyawan))))";
		$atasan = "(((select m_jabatan_terkait from m_jabatan_struktural where tipe_struktural=1 and m_jabatan_id = (select m_jabatan_id from p_karyawan_pekerjaan pkp where p_karyawan_id = $id_karyawan))))";
		$sejajar = "(((select m_jabatan_terkait from m_jabatan_struktural where tipe_struktural=3 and m_jabatan_id = (select m_jabatan_id from p_karyawan_pekerjaan pkp where p_karyawan_id = $id_karyawan))))";
		//$atasan= substr($this->hirarki($idkar[0]->m_jabatan_id,''),0,-1);
		//$bawahan = substr($this->hirarkiBawahan($idkar[0]->m_jabatan_id,''),0,-1);
		//echo $atasan;die;

		$sqlappr="SELECT * from get_data_karyawan() WHERE m_jabatan_id in($atasan) ";
		$appr=DB::connection()->select($sqlappr);


		$sqlkar="SELECT * from get_data_karyawan() WHERE p_karyawan_id=$id ";
		$kar=DB::connection()->select($sqlkar);
		
		$where = '';

		$sqlkar="SELECT * from get_data_karyawan() where (p_karyawan_id in($bawahan) or m_jabatan_id in($atasan) or m_jabatan_id in($sejajar))  and (m_departemen_id = (select m_departemen_id from p_karyawan_pekerjaan where p_karyawan_id=$id_karyawan)  or m_jabatan_id in($atasan))  order by nama_lengkap";
		//echo $sqlkar;die;
		$karyawan=DB::connection()->select($sqlkar);



		$tahun=date('Y');
		//$sqlcuti="SELECT * FROM get_list_cuti_tahunan_new($tahun,$id) ";
		//$datacuti=DB::connection()->select($sqlcuti);
		//var_dump($datacuti);die;
		//$cuti=$datacuti[0]->sisa_cuti;
/*if($cuti>0){
$cuti=$datacuti[0]->sisa_cuti;
}
else{
$cuti=0;
}*/
		$help = new Helper_function();
		$cuti = $help->query_cuti2($idkar);
		$date2 = $cuti['date'];
		$all = $cuti['all'];
		$tanggal_loop = $cuti['tanggal_loop'];
		$no=0;$nominal=0;
		$tahun = array();
		$tahunbesar = array();
		$datasisa = array();
		$hutang = 0;
		$jumlah = 0;
		foreach ($tanggal_loop as $i=> $loop) {
			if ($all[$i]['tanggal']<=date('Y-m-d')) {
				$return = $help->perhitungan_cuti2($all,$datasisa,$hutang,$date2,$i,$nominal,$jumlah);
				$datasisa =$return['datasisa'];
				$hutang =$return['hutang'];
				$nominal =$return['nominal'];
				$jumlah =$return['jumlah'];
			}
		}


		if (isset($datasisa)) {
			asort($datasisa);
			$totalcuti = 0;
			foreach ($datasisa as $value=>$key) {
				$tahun = $value;
				if ($value>2000)
					$value = 'Sisa Cuti Tahun '.$value;
				else
					$value = 'Sisa Cuti Besar ke '.$value;
				//echo $value.' : 	'.$key.'<br>';
				if ($key  or $tahun>=date('Y')-1) {
					$totalcuti +=$key;
				}
			}
		}
		$totalcuti -=$hutang;
		$cuti = $totalcuti;
		if ($cuti>0) {
			$sqljeniscuti="SELECT * from m_jenis_ijin WHERE tipe=3 order by nama";
			$jeniscuti=DB::connection()->select($sqljeniscuti);
		} else {
			$sqljeniscuti="SELECT * from m_jenis_ijin WHERE tipe=3 and m_jenis_ijin_id IN(7,8,9,10) order by nama";
			$jeniscuti=DB::connection()->select($sqljeniscuti);
		}
		$help = new Helper_function();
		if ($idkar[0]->periode_gajian==1) {
			$sql="select * from m_gaji_bulanan order by tanggal desc limit 1";
			$gapen=DB::connection()->select($sql);
			$help = new Helper_function();
			$tgl_awal_gaji = $gapen[0]->tanggal;
			$next_gajian = $help->tambah_bulan($gapen[0]->tanggal,1);
			if ($next_gajian<date('Y-m-d')) {

				$tgl_awal_gaji = $next_gajian;
				DB::connection()->table("m_gaji_bulanan")
				->insert([
					"tanggal"=>$next_gajian,
				]);

				$next_gajian = $help->tambah_bulan($next_gajian,1);
			}
			$tgl_akhir_gaji = $next_gajian;
			$tgl_akhir_gaji = date('Y-m-d');
			$tgl_awal_gaji2 = $help->tambah_tanggal($tgl_awal_gaji,3);
			$tgl_akhir_gaji2 = $help->tambah_tanggal($next_gajian,3);
			$tgl_awal_cut_off  = $help->tambah_tanggal($tgl_akhir_gaji2,-3);
		} else if ($idkar[0]->periode_gajian==0) {


			$sql="select * from m_gaji_pekanan order by tanggal limit 1";
			$gapen=DB::connection()->select($sql);
			$help = new Helper_function();
			$tgl_awal_gaji = $gapen[0]->tanggal;
			$next_gajian = $help->tambah_tanggal($gapen[0]->tanggal,14);
			if ($next_gajian<date('Y-m-d')) {
				$tgl_awal_gaji = $next_gajian;
				DB::connection()->table("m_gaji_pekanan")
				->insert([
					"tanggal"=>$next_gajian,
				]);

				$next_gajian = $help->tambah_tanggal($next_gajian,14);
			}
			$tgl_akhir_gaji = $next_gajian;
			$tgl_akhir_gaji = date('Y-m-d');
			$tgl_awal_gaji2 = $help->tambah_tanggal($tgl_awal_gaji,1);
			$tgl_akhir_gaji2 = $help->tambah_tanggal($next_gajian,1);
			$tgl_awal_cut_off  = $help->tambah_tanggal($next_gajian,-1);
		}



		$tgl_akhir_cut_off  = $next_gajian;
		$tgl_cut_off ='';
		if ($tgl_awal_cut_off<=date('Y-m-d') and $tgl_akhir_cut_off>=date('Y-m-d'))
			$tgl_cut_off = $tgl_awal_cut_off;
		else
			$tgl_cut_off = $help->tambah_tanggal($tgl_awal_gaji,-1);

		return view('frontend.permit.tambah_cuti',compact('kar','jeniscuti','appr','help','karyawan','totalcuti','tgl_cut_off'));
	}
	public function tambah_izin()
	{
		$iduser=Auth::user()->id;
		$sqlidkar="select * from p_karyawan where user_id=$iduser";
		$idkar=DB::connection()->select($sqlidkar);
		$id=$idkar[0]->p_karyawan_id;
		$iduser=Auth::user()->id;
		$sqlidkar="select *,p_karyawan_id as karyawan_id from p_karyawan
		join p_karyawan_pekerjaan on p_karyawan_pekerjaan.p_karyawan_id = p_karyawan.p_karyawan_id
		where user_id=$iduser";
		$idkar=DB::connection()->select($sqlidkar);
		$id=$idkar[0]->p_karyawan_id;

		$id_karyawan = $idkar[0]->p_karyawan_id;
		$bawahan = "((select p_karyawan_pekerjaan.p_karyawan_id from p_karyawan_pekerjaan where m_jabatan_id in (select m_jabatan_terkait from m_jabatan_struktural where tipe_struktural=2 and m_jabatan_id = (select m_jabatan_id from p_karyawan_pekerjaan pkp where p_karyawan_id = $id_karyawan))))";
		$atasan = "(((select m_jabatan_terkait from m_jabatan_struktural where tipe_struktural=1 and m_jabatan_id = (select m_jabatan_id from p_karyawan_pekerjaan pkp where p_karyawan_id = $id_karyawan))))";
		$sejajar = "(((select m_jabatan_terkait from m_jabatan_struktural where tipe_struktural=3 and m_jabatan_id = (select m_jabatan_id from p_karyawan_pekerjaan pkp where p_karyawan_id = $id_karyawan))))";
		$sqlappr="SELECT * from get_data_karyawan() WHERE m_jabatan_id in($atasan) ";
		$appr=DB::connection()->select($sqlappr);
		//echo $id;die;
		$sqlkar="SELECT * from get_data_karyawan() WHERE p_karyawan_id=$id";
		$kar=DB::connection()->select($sqlkar);

		$sqlkar="SELECT * from get_data_karyawan() where (p_karyawan_id in($bawahan) or m_jabatan_id in($atasan) or m_jabatan_id in($sejajar)) and (m_departemen_id = (select m_departemen_id from p_karyawan_pekerjaan where p_karyawan_id=$id_karyawan)  or m_jabatan_id in($atasan))  order by nama_lengkap";
		$karyawan=DB::connection()->select($sqlkar);


		$help = new Helper_function();
		$sqljenisizin="SELECT * from m_jenis_ijin WHERE tipe=1 and active=1  order by urutan asc";
		$jenisizin=DB::connection()->select($sqljenisizin);
		$tahun=date('Y');
		$cuti = $help->query_cuti2($idkar);
		$date2 = $cuti['date'];
		$all = $cuti['all'];
		$tanggal_loop = $cuti['tanggal_loop'];
		$no=0;$nominal=0;
		$tahun = array();
		$tahunbesar = array();
		$datasisa = array();
		$hutang = 0;
		$jumlah = 0;
		foreach ($tanggal_loop as $i=> $loop) {
			if ($all[$i]['tanggal']<=date('Y-m-d')) {
				$return = $help->perhitungan_cuti2($all,$datasisa,$hutang,$date2,$i,$nominal,$jumlah);
				$datasisa =$return['datasisa'];
				$hutang =$return['hutang'];
				$nominal =$return['nominal'];
				$jumlah =$return['jumlah'];
			}
		}


		if (isset($datasisa)) {
			asort($datasisa);
			$totalcuti = 0;
			foreach ($datasisa as $value=>$key) {
				$tahun = $value;
				if ($value>2000)
					$value = 'Sisa Cuti Tahun '.$value;
				else
					$value = 'Sisa Cuti Besar ke '.$value;
				//echo $value.' : 	'.$key.'<br>';
				if ($key  or $tahun>=date('Y')-1) {
					$totalcuti +=$key;
				}
			}
		}
		$totalcuti -=$hutang;
		$tolcut = $totalcuti;
/*
$sqlcuti="SELECT * FROM get_list_cuti_tahunan_new($tahun,$id) ";
$cuti=DB::connection()->select($sqlcuti);
$sqldw="SELECT * FROM get_data_karyawan() WHERE p_karyawan_id=$id";
$dw=DB::connection()->select($sqldw);
// print_r($cuti);die;

if($dw[0]->m_status_pekerjaan_id==5)
$tolcut = 0;
else{
if($cuti[0]->total_cuti_tahunan<1)
$tolcut = $cuti[0]->total_cuti_tahunan;
else
$tolcut = $cuti[0]->sisa_cuti;
}*/
		if ($idkar[0]->periode_gajian==1) {
			$sql="select * from m_gaji_bulanan order by tanggal desc limit 1";
			$gapen=DB::connection()->select($sql);
			$help = new Helper_function();
			$tgl_awal_gaji = $gapen[0]->tanggal;
			$next_gajian = $help->tambah_bulan($gapen[0]->tanggal,1);
			if ($next_gajian<date('Y-m-d')) {

				$tgl_awal_gaji = $next_gajian;
				DB::connection()->table("m_gaji_bulanan")
				->insert([
					"tanggal"=>$next_gajian,
				]);

				$next_gajian = $help->tambah_bulan($next_gajian,1);
			}
			$tgl_akhir_gaji = $next_gajian;
			$tgl_akhir_gaji = date('Y-m-d');
			$tgl_awal_gaji2 = $help->tambah_tanggal($tgl_awal_gaji,3);
			$tgl_akhir_gaji2 = $help->tambah_tanggal($next_gajian,3);
			$tgl_awal_cut_off  = $help->tambah_tanggal($tgl_akhir_gaji2,-2);
		} else if ($idkar[0]->periode_gajian==0) {


			$sql="select * from m_gaji_pekanan order by tanggal limit 1";
			$gapen=DB::connection()->select($sql);
			$help = new Helper_function();
			$tgl_awal_gaji = $gapen[0]->tanggal;
			$next_gajian = $help->tambah_tanggal($gapen[0]->tanggal,14);
			if ($next_gajian<date('Y-m-d')) {
				$tgl_awal_gaji = $next_gajian;
				DB::connection()->table("m_gaji_pekanan")
				->insert([
					"tanggal"=>$next_gajian,
				]);

				$next_gajian = $help->tambah_tanggal($next_gajian,14);
			}
			$tgl_akhir_gaji = $next_gajian;
			$tgl_akhir_gaji = date('Y-m-d');
			$tgl_awal_gaji2 = $help->tambah_tanggal($tgl_awal_gaji,1);
			$tgl_akhir_gaji2 = $help->tambah_tanggal($next_gajian,1);
			$tgl_awal_cut_off  = $help->tambah_tanggal($next_gajian,-1);
		}



		$tgl_akhir_cut_off  = $next_gajian;
		$tgl_cut_off ='';
		if ($tgl_awal_cut_off<=date('Y-m-d') and $tgl_akhir_cut_off>=date('Y-m-d'))
			$tgl_cut_off = $tgl_awal_cut_off;
		else
			$tgl_cut_off = $help->tambah_tanggal($tgl_awal_gaji,1);

		return view('frontend.permit.tambah_izin',compact('kar','jenisizin','appr','tolcut','karyawan','totalcuti','tgl_cut_off'));
	}
	public function tambah_perdin()
	{
		$iduser=Auth::user()->id;
		$sqlidkar="select * from p_karyawan
		join p_karyawan_pekerjaan on p_karyawan_pekerjaan.p_karyawan_id = p_karyawan.p_karyawan_id
		where user_id=$iduser";
		$idkar=DB::connection()->select($sqlidkar);
		$id=$idkar[0]->p_karyawan_id;

		$id_karyawan = $idkar[0]->p_karyawan_id;
		$bawahan = "((select p_karyawan_pekerjaan.p_karyawan_id from p_karyawan_pekerjaan where m_jabatan_id in (select m_jabatan_terkait from m_jabatan_struktural where tipe_struktural=2 and m_jabatan_id = (select m_jabatan_id from p_karyawan_pekerjaan pkp where p_karyawan_id = $id_karyawan))))";
		$atasan = "(((select m_jabatan_terkait from m_jabatan_struktural where tipe_struktural=1 and m_jabatan_id = (select m_jabatan_id from p_karyawan_pekerjaan pkp where p_karyawan_id = $id_karyawan))))";
		$sejajar = "(((select m_jabatan_terkait from m_jabatan_struktural where tipe_struktural=3 and m_jabatan_id = (select m_jabatan_id from p_karyawan_pekerjaan pkp where p_karyawan_id = $id_karyawan))))";
		$sqlappr="SELECT * from get_data_karyawan() WHERE m_pangkat_id not in(1) and m_jabatan_id in($atasan) ";
		$appr=DB::connection()->select($sqlappr);


		$sqlkar="SELECT * from get_data_karyawan() WHERE p_karyawan_id=$id";
		$kar=DB::connection()->select($sqlkar);

		$sqlkar="SELECT * from get_data_karyawan()  where (p_karyawan_id in($bawahan) or m_jabatan_id in($atasan) or m_jabatan_id in($sejajar)) and (m_departemen_id = (select m_departemen_id from p_karyawan_pekerjaan where p_karyawan_id=$id_karyawan)  or m_jabatan_id in($atasan)) order by nama_lengkap";
		$karyawan=DB::connection()->select($sqlkar);


		$sqljenisizin="SELECT * from m_jenis_ijin WHERE tipe=1 and active=1 order by urutan asc";
		$jenisizin=DB::connection()->select($sqljenisizin);
		if ($idkar[0]->periode_gajian==1) {
			$sql="select * from m_gaji_bulanan order by tanggal desc limit 1";
			$gapen=DB::connection()->select($sql);
			$help = new Helper_function();
			$tgl_awal_gaji = $gapen[0]->tanggal;
			$next_gajian = $help->tambah_bulan($gapen[0]->tanggal,1);
			if ($next_gajian<date('Y-m-d')) {

				$tgl_awal_gaji = $next_gajian;
				DB::connection()->table("m_gaji_bulanan")
				->insert([
					"tanggal"=>$next_gajian,
				]);

				$next_gajian = $help->tambah_bulan($next_gajian,1);
			}
			$tgl_akhir_gaji = $next_gajian;
			$tgl_akhir_gaji = date('Y-m-d');
			$tgl_awal_gaji2 = $help->tambah_tanggal($tgl_awal_gaji,3);
			$tgl_akhir_gaji2 = $help->tambah_tanggal($next_gajian,3);
			$tgl_awal_cut_off  = $help->tambah_tanggal($tgl_akhir_gaji2,-2);
		} else if ($idkar[0]->periode_gajian==0) {


			$sql="select * from m_gaji_pekanan order by tanggal limit 1";
			$gapen=DB::connection()->select($sql);
			$help = new Helper_function();
			$tgl_awal_gaji = $gapen[0]->tanggal;
			$next_gajian = $help->tambah_tanggal($gapen[0]->tanggal,14);
			if ($next_gajian<date('Y-m-d')) {
				$tgl_awal_gaji = $next_gajian;
				DB::connection()->table("m_gaji_pekanan")
				->insert([
					"tanggal"=>$next_gajian,
				]);

				$next_gajian = $help->tambah_tanggal($next_gajian,14);
			}
			$tgl_akhir_gaji = $next_gajian;
			$tgl_akhir_gaji = date('Y-m-d');
			$tgl_awal_gaji2 = $help->tambah_tanggal($tgl_awal_gaji,1);
			$tgl_akhir_gaji2 = $help->tambah_tanggal($next_gajian,1);
			$tgl_awal_cut_off  = $help->tambah_tanggal($next_gajian,-1);
		}



		$tgl_akhir_cut_off  = $next_gajian;
		$tgl_cut_off ='';
		if ($tgl_awal_cut_off<=date('Y-m-d') and $tgl_akhir_cut_off>=date('Y-m-d'))
			$tgl_cut_off = $tgl_awal_cut_off;
		else
			$tgl_cut_off = $help->tambah_tanggal($tgl_awal_gaji,1);

		//echo $tgl_awal_cut_off<=date('Y-m-d');die;
		return view('frontend.permit.tambah_perdin',compact('kar','jenisizin','appr','karyawan','tgl_cut_off'));
	}

	public function tambah_lembur()
	{
		$iduser=Auth::user()->id;
		$sqlidkar="select * from p_karyawan where user_id=$iduser";
		$idkar=DB::connection()->select($sqlidkar);
		$id=$idkar[0]->p_karyawan_id;
		$iduser=Auth::user()->id;
		$sqlidkar="select * from p_karyawan
		join p_karyawan_pekerjaan on p_karyawan_pekerjaan.p_karyawan_id = p_karyawan.p_karyawan_id
		where user_id=$iduser";
		$idkar=DB::connection()->select($sqlidkar);
		$id=$idkar[0]->p_karyawan_id;
		$id_karyawan = $idkar[0]->p_karyawan_id;
		$bawahan = "((select p_karyawan_pekerjaan.p_karyawan_id from p_karyawan_pekerjaan where m_jabatan_id in (select m_jabatan_terkait from m_jabatan_struktural where tipe_struktural=2 and m_jabatan_id = (select m_jabatan_id from p_karyawan_pekerjaan pkp where p_karyawan_id = $id_karyawan))))";
		$atasan = "(((select m_jabatan_terkait from m_jabatan_struktural left join m_jabatan on m_jabatan_terkait=m_jabatan.m_jabatan_id where m_pangkat_id not in(5,6) and tipe_struktural=1 and m_jabatan_struktural.m_jabatan_id = (select m_jabatan_id from p_karyawan_pekerjaan pkp where p_karyawan_id = $id_karyawan))))";
		$atasan2 = "(((select m_jabatan_terkait
		from m_jabatan_struktural
		left join m_jabatan on m_jabatan_terkait=m_jabatan.m_jabatan_id
		where m_pangkat_id in(5,6)
		and tipe_struktural=1
		and m_jabatan_struktural.m_jabatan_id = (select pkp.m_jabatan_id from p_karyawan_pekerjaan pkp where p_karyawan_id = $id_karyawan))))";
		$sejajar = "(((select m_jabatan_terkait from m_jabatan_struktural where tipe_struktural=3 and m_jabatan_id = (select m_jabatan_id from p_karyawan_pekerjaan pkp where p_karyawan_id = $id_karyawan))))";
		$sqlappr="SELECT * from get_data_karyawan() WHERE m_jabatan_id in($atasan) ";
		$appr1=DB::connection()->select($sqlappr);
		$sqlappr="SELECT * from get_data_karyawan() WHERE m_jabatan_id in($atasan2) ";
		$appr2=DB::connection()->select($sqlappr);

		$sqlkar="SELECT * from get_data_karyawan() WHERE p_karyawan_id=$id";
		$kar=DB::connection()->select($sqlkar);

		$sqlkar="SELECT * from get_data_karyawan() where (p_karyawan_id in($bawahan) or m_jabatan_id in($atasan) or m_jabatan_id in($sejajar)) and (m_departemen_id = (select m_departemen_id from p_karyawan_pekerjaan where p_karyawan_id=$id_karyawan)  or m_jabatan_id in($atasan))  order by nama_lengkap";
		$karyawan=DB::connection()->select($sqlkar);


		$sqljenisizin="SELECT * from m_jenis_ijin WHERE tipe=2  and active=1 order by urutan asc";
		$jenisizin=DB::connection()->select($sqljenisizin);
		if ($idkar[0]->periode_gajian==1) {
			$sql="select * from m_gaji_bulanan order by tanggal desc limit 1";
			$gapen=DB::connection()->select($sql);
			$help = new Helper_function();
			$tgl_awal_gaji = $gapen[0]->tanggal;
			$next_gajian = $help->tambah_bulan($gapen[0]->tanggal,1);
			if ($next_gajian<date('Y-m-d')) {

				$tgl_awal_gaji = $next_gajian;
				DB::connection()->table("m_gaji_bulanan")
				->insert([
					"tanggal"=>$next_gajian,
				]);

				$next_gajian = $help->tambah_bulan($next_gajian,1);
			}
			$tgl_akhir_gaji = $next_gajian;
			$tgl_akhir_gaji = date('Y-m-d');
			$tgl_awal_gaji2 = $help->tambah_tanggal($tgl_awal_gaji,3);
			$tgl_akhir_gaji2 = $help->tambah_tanggal($next_gajian,3);
			$tgl_awal_cut_off  = $help->tambah_tanggal($tgl_akhir_gaji2,-3);
		} else if ($idkar[0]->periode_gajian==0) {


			$sql="select * from m_gaji_pekanan order by tanggal limit 1";
			$gapen=DB::connection()->select($sql);
			$help = new Helper_function();
			$tgl_awal_gaji = $gapen[0]->tanggal;
			$next_gajian = $help->tambah_tanggal($gapen[0]->tanggal,14);
			if ($next_gajian<date('Y-m-d')) {
				$tgl_awal_gaji = $next_gajian;
				DB::connection()->table("m_gaji_pekanan")
				->insert([
					"tanggal"=>$next_gajian,
				]);

				$next_gajian = $help->tambah_tanggal($next_gajian,14);
			}
			$tgl_akhir_gaji = $next_gajian;
			$tgl_akhir_gaji = date('Y-m-d');
			$tgl_awal_gaji2 = $help->tambah_tanggal($tgl_awal_gaji,1);
			$tgl_akhir_gaji2 = $help->tambah_tanggal($next_gajian,1);
			$tgl_awal_cut_off  = $help->tambah_tanggal($next_gajian,-1);
		}



		$tgl_akhir_cut_off  = $next_gajian;
		$tgl_cut_off ='';
		if ($tgl_awal_cut_off<=date('Y-m-d') and $tgl_akhir_cut_off>=date('Y-m-d'))
			$tgl_cut_off = $tgl_awal_cut_off;
		else
			$tgl_cut_off = $help->tambah_tanggal($tgl_awal_gaji,-1);
		return view('frontend.permit.tambah_lembur',compact('kar','jenisizin','appr1','appr2','karyawan','tgl_cut_off'));
	}

	public function simpan_cuti(Request $request)
	{
		DB::beginTransaction();
		try {
			$iduser=Auth::user()->id;
			$sqlidkar="select * from p_karyawan where user_id=$iduser";
			$idkar=DB::connection()->select($sqlidkar);
			$id=$idkar[0]->p_karyawan_id;

			$this->validate($request, [
				'cuti' => 'required',
				'tgl_awal' => 'required',
				'tgl_akhir' => 'required',
				'lama' => 'required',
				'atasan' => 'required',
			]);

			$data = $request->all();
			if (empty($request->input('cuti'))) {
				$data['cuti'] = $request->input('cuti');
			}
			if (empty($request->input('tgl_awal'))) {
				$data['tgl_awal'] = $request->input('tgl_awal');
			}
			if (empty($request->input('tgl_akhir'))) {
				$data['tgl_akhir'] = $request->input('tgl_akhir');
			}
			if (empty($request->input('atasan'))) {
				$data['atasan'] = $request->input('atasan');
			}
			if (empty($request->input('lama'))) {
				$data['lama'] = $request->input('lama');
			}
			date_default_timezone_set('Asia/Jakarta');
			$time=date('Y-m-d H:i:s');

			$Bulan=date('m');
			$Tahun2=date('y');

			$sqlnocuti="SELECT COALESCE(MAX(substr(kode, 14 , 5)::NUMERIC),10000)+1 AS counter
			FROM t_permit
			WHERE substr(kode, 8 , 2) ILIKE '$Bulan'
			AND substr(kode, 11 , 2) ILIKE '$Tahun2' ";
			//echo $sqlnopengajuan;die;
			$datanocuti=DB::connection()->select($sqlnocuti);
			$Counter=$datanocuti[0]->counter;
			$nocuti='PERMIT.'.$Bulan.'.'.$Tahun2.'.'.$Counter;

			DB::connection()->table("t_permit")
			->insert([
				"m_jenis_ijin_id"=>$request->get('cuti'),
				"p_karyawan_id"=>$id,
				"tgl_awal"=>date('Y-m-d',strtotime($request->get('tgl_awal'))),
				"tgl_akhir"=>date('Y-m-d',strtotime($request->get('tgl_akhir'))),
				"appr_1"=>$request->get('atasan'),
				"lama"=>$request->get('lama'),
				"pjs"=>$request->get('pjs'),
				"keterangan"=>$request->get('alasan'),
				"create_date"=>date('Y-m-d  H:i:s'),
				"kode"=>$nocuti,
				"create_by"=>$id,
				"active"=>1,
			]);

			if ($request->file('file')) {
				//echo 'masuk';die;
				$file = $request->file('file');
				$destination="dist/img/file/";
				$path='permit-'.date('YmdHis').$file->getClientOriginalName();
				$file->move($destination,$path);
				//echo $path;die;
				DB::connection()->table("t_permit")->where("kode",$nocuti)
				->update([
					"foto"=>$path
				]);
			}

			DB::commit();

			$sqldata="SELECT a.*,b.nik,b.nama_lengkap,b.jabatan,b.departemen,c.kode as kdpermit,c.nama as nama_ijin,d.nama as nama_appr,tgl_appr_1,status_appr_1,coalesce(b.email_perusahaan,'mankewink@gmail.com') as email_perusahaan,e.email_perusahaan as email_appr
			FROM t_permit a
			left join get_data_karyawan() b on b.p_karyawan_id=a.p_karyawan_id
			left join m_jenis_ijin c on c.m_jenis_ijin_id=a.m_jenis_ijin_id
			left join p_karyawan d on d.p_karyawan_id=a.appr_1
			LEFT JOIN get_data_karyawan () e ON e.p_karyawan_id = A.appr_1
			WHERE 1=1 and a.kode='$nocuti' and a.active=1 and c.tipe=3 ORDER BY a.tgl_awal desc ";
			$data=DB::connection()->select($sqldata);

			//Mail::send('email_appr', ['data' => $data], function ($mail) use ($data){
			//		$mail->from('ethicagroup1@gmail.com', 'Ethica');
			//		$mail->to($data[0]->email_appr)->subject('Pengajuan Cuti');
			//	});-->

			return redirect()->route('fe.list_cuti')->with('success','Pengajuan Cuti Berhasil di simpan!');
		} catch (\Exeception $e) {
			DB::rollback();
			return redirect()->back()->with('error',$e);
		}
	}

	public function simpan_izin(Request $request)
	{
		DB::beginTransaction();
		try {
			$iduser=Auth::user()->id;
			$sqlidkar="select * from p_karyawan where user_id=$iduser";
			$idkar=DB::connection()->select($sqlidkar);
			$id=$idkar[0]->p_karyawan_id;

			$this->validate($request, [
				'cuti' => 'required',
				'tgl_awal' => 'required',
				'tgl_akhir' => 'required',
				'lama' => 'required',
				'atasan' => 'required',
			]);

			$data = $request->all();
			if (empty($request->input('cuti'))) {
				$data['cuti'] = $request->input('cuti');
			}
			if (empty($request->input('tgl_awal'))) {
				$data['tgl_awal'] = $request->input('tgl_awal');
			}
			if (empty($request->input('tgl_akhir'))) {
				$data['tgl_akhir'] = $request->input('tgl_akhir');
			}
			if (empty($request->input('atasan'))) {
				$data['atasan'] = $request->input('atasan');
			}
			if (empty($request->input('lama'))) {
				$data['lama'] = $request->input('lama');
			}

			date_default_timezone_set('Asia/Jakarta');
			$time=date('Y-m-d H:i:s');

			$Bulan=date('m');
			$Tahun2=date('y');

			$sqlnocuti="SELECT COALESCE(MAX(substr(kode, 14 , 5)::NUMERIC),10000)+1 AS counter
			FROM t_permit
			WHERE substr(kode, 8 , 2) ILIKE '$Bulan'
			AND substr(kode, 11 , 2) ILIKE '$Tahun2' ";
			//echo $sqlnopengajuan;die;
			$datanocuti=DB::connection()->select($sqlnocuti);
			$Counter=$datanocuti[0]->counter;
			$nocuti='PERMIT.'.$Bulan.'.'.$Tahun2.'.'.$Counter;

			DB::connection()->table("t_permit")
			->insert([
				"m_jenis_ijin_id"=>$request->get('cuti'),
				"p_karyawan_id"=>$id,
				"kode"=>$nocuti,
				"tgl_awal"=>date('Y-m-d',strtotime($request->get('tgl_awal'))),
				"tgl_akhir"=>date('Y-m-d',strtotime($request->get('tgl_akhir'))),
				"keterangan"=>$request->get('alasan'),
				"appr_1"=>$request->get('atasan'),
				"lama"=>$request->get('lama'),
				"pjs"=>$request->get('pjs'),
				"create_date"=>date('Y-m-d  H:i:s'),
				"create_by"=>$id,
				"active"=>1,
			]);

			if ($request->file('file')) {
				//echo 'masuk';die;
				$file = $request->file('file');
				$destination="dist/img/file/";
				$path = 'permit-'.date('YmdHis').$file->getClientOriginalName();$file->move($destination,$path);
				//$path=$file->getClientOriginalName();
				//echo $path;die;
				DB::connection()->table("t_permit")->where("kode",$nocuti)
				->update([
					"foto"=>$path
				]);
			}

			DB::commit();

			$sqldata="SELECT a.*,b.nik,b.nama_lengkap,b.jabatan,b.departemen,c.kode as kdpermit,c.nama as nama_ijin,d.nama as nama_appr,tgl_appr_1,status_appr_1,coalesce(b.email_perusahaan,'mankewink@gmail.com') as email_perusahaan,e.email_perusahaan as email_appr
			FROM t_permit a
			left join get_data_karyawan() b on b.p_karyawan_id=a.p_karyawan_id
			left join m_jenis_ijin c on c.m_jenis_ijin_id=a.m_jenis_ijin_id
			left join p_karyawan d on d.p_karyawan_id=a.appr_1
			LEFT JOIN get_data_karyawan () e ON e.p_karyawan_id = A.appr_1
			WHERE 1=1 and a.kode='$nocuti' and a.active=1 and c.tipe=1 ORDER BY a.tgl_awal desc ";
			$data=DB::connection()->select($sqldata);

			//Mail::send('email_appr', ['data' => $data], function ($mail) use ($data){
			//		$mail->from('ethicagroup1@gmail.com', 'Ethica');
			//		$mail->to($data[0]->email_appr)->subject('Pengajuan Izin');
			//		if($data[0]->foto!='' || $data[0]->foto!=null){
			//			$mail->attach('dist/img/file/'.$data[0]->foto);
			//		}

			//	});

			return redirect()->route('fe.list_izin')->with('success','Pengajuan Izin Berhasil di simpan!');
		} catch (\Exeception $e) {
			DB::rollback();
			return redirect()->back()->with('error',$e);
		}
	}

	public function simpan_lembur(Request $request)
	{
		DB::beginTransaction();
		try {
			$iduser=Auth::user()->id;
			$sqlidkar="select * from p_karyawan where user_id=$iduser";
			$idkar=DB::connection()->select($sqlidkar);
			$id=$idkar[0]->p_karyawan_id;

			$this->validate($request, [
				'jam_awal' => 'required',
				'jam_akhir' => 'required',
				'tgl' => 'required',

				'lama' => 'required',

			]);

			$data = $request->all();
			if (empty($request->input('jam_awal'))) {
				$data['jam_awal'] = $request->input('jam_awal');
			}
			if (empty($request->input('jam_akhir'))) {
				$data['jam_akhir'] = $request->input('jam_akhir');
			}
			if (empty($request->input('tgl_awal'))) {
				$data['tgl_awal'] = $request->input('tgl_awal');
			}
			if (empty($request->input('tgl_akhir'))) {
				$data['tgl_akhir'] = $request->input('tgl_akhir');
			}
			if (empty($request->input('atasan'))) {
				$data['atasan'] = $request->input('atasan');
			}
			if (empty($request->input('lama'))) {
				$data['lama'] = $request->input('lama');
			}

			date_default_timezone_set('Asia/Jakarta');
			$time=date('Y-m-d H:i:s');

			$Bulan=date('m');
			$Tahun2=date('y');

			$sqlnocuti="SELECT COALESCE(MAX(substr(kode, 14 , 5)::NUMERIC),10000)+1 AS counter
			FROM t_permit
			WHERE substr(kode, 8 , 2) ILIKE '$Bulan'
			AND substr(kode, 11 , 2) ILIKE '$Tahun2' ";
			//echo $sqlnopengajuan;die;
			$datanocuti=DB::connection()->select($sqlnocuti);
			$Counter=$datanocuti[0]->counter;
			$nocuti='PERMIT.'.$Bulan.'.'.$Tahun2.'.'.$Counter;
$help = new Helper_function();
          	$keluar = strtotime($request->get('jam_awal'));
		    $masuk = strtotime($request->get('jam_akhir'));
			 $total_jam =  $masuk-$keluar;
	        $lama_php =  gmdate('G', $total_jam	); ;
			$lama_input = $request->get('lama');
			if($lama_input!=$lama_php)
				$lama_input = $lama_php;
			DB::connection()->table("t_permit")
			->insert([
				"m_jenis_ijin_id"=>22,
				"p_karyawan_id"=>$id,
				"kode"=>$nocuti,
				"tgl_awal"=>date('Y-m-d',strtotime($request->get('tgl'))),
				"tgl_akhir"=>date('Y-m-d',strtotime($request->get('tgl'))),
				"keterangan"=>$request->get('alasan'),
				"appr_1"=>$request->get('atasan'),
				"appr_2"=>$request->get('atasan2'),
				"lama"=>$lama_input,
				"pjs"=>$request->get('pjs'),
				"create_date"=>date('Y-m-d  H:i:s'),
				"create_by"=>$id,
				"active"=>1,
				"tipe_lembur"=>$request->get('tipe_lembur'),
				"jam_awal"=>$request->get('jam_awal'),
				"jam_akhir"=>$request->get('jam_akhir'),
			]);
			DB::commit();

			$sqldata="SELECT a.*,b.nik,b.nama_lengkap,b.jabatan,b.departemen,c.kode as kdpermit,c.nama as nama_ijin,d.nama as nama_appr,tgl_appr_1,status_appr_1,coalesce(b.email_perusahaan,'mankewink@gmail.com') as email_perusahaan,e.email_perusahaan as email_appr
			FROM t_permit a
			left join get_data_karyawan() b on b.p_karyawan_id=a.p_karyawan_id
			left join m_jenis_ijin c on c.m_jenis_ijin_id=a.m_jenis_ijin_id
			left join p_karyawan d on d.p_karyawan_id=a.appr_1
			LEFT JOIN get_data_karyawan () e ON e.p_karyawan_id = A.appr_1
			WHERE 1=1 and a.kode='$nocuti' and a.active=1 and c.tipe=2	 ORDER BY a.tgl_awal desc ";
			$data=DB::connection()->select($sqldata);

			//Mail::send('email_appr_lembur', ['data' => $data], function ($mail) use ($data){
			//		$mail->from('ethicagroup1@gmail.com', 'Ethica');
			//		$mail->to($data[0]->email_appr)->subject('Pengajuan Lembur');
			//		//$mail->attach('dist/img/file/'.$data[0]->foto);

			//	});

			return redirect()->route('fe.list_lembur')->with('success','Pengajuan Lembur Berhasil di simpan!');
		} catch (\Exeception $e) {
			DB::rollback();
			return redirect()->back()->with('error',$e);
		}
	}

	public function lihat_cuti($id)
	{
		$sqlcuti="SELECT a.*,b.nik,b.nama_lengkap,b.jabatan,b.departemen,c.kode,c.nama as nama_ijin,d.nama as nama_appr,tgl_appr_1,status_appr_1,e.nama as pjs,
		case when status_appr_1=1 then 'Disetujui' when status_appr_1=2 then 'Ditolak' when status_appr_1=3 then 'Pending' end as sts_pengajuan
		FROM t_permit a
		left join get_data_karyawan() b on b.p_karyawan_id=a.p_karyawan_id
		left join m_jenis_ijin c on c.m_jenis_ijin_id=a.m_jenis_ijin_id
		left join p_karyawan d on d.p_karyawan_id=a.appr_1
		left join p_karyawan e on e.p_karyawan_id=a.pjs
		WHERE 1=1 and a.t_form_exit_id=$id and a.active=1 and c.tipe=3 ORDER BY a.tgl_awal desc";
		$cuti=DB::connection()->select($sqlcuti);

		return view('frontend.permit.lihat_cuti',compact('cuti'));
	}

	public function lihat_lembur($id)
	{
		$sqlizin="SELECT a.*,b.nik,b.nama_lengkap,b.jabatan,b.departemen,c.kode,c.nama as nama_ijin,d.nama as nama_appr,tgl_appr_1,status_appr_1,e.nama as pjs,
		case when status_appr_1=1 then 'Disetujui' when status_appr_1=2 then 'Ditolak' when status_appr_1=3 then 'Pending' end as sts_pengajuan
		FROM t_permit a
		left join get_data_karyawan() b on b.p_karyawan_id=a.p_karyawan_id
		left join m_jenis_ijin c on c.m_jenis_ijin_id=a.m_jenis_ijin_id
		left join p_karyawan d on d.p_karyawan_id=a.appr_1
		left join p_karyawan e on e.p_karyawan_id=a.pjs
		WHERE 1=1 and a.t_form_exit_id=$id and a.active=1 and c.tipe=2 ORDER BY a.tgl_awal desc";
		$Lembur=DB::connection()->select($sqlizin);

		return view('frontend.permit.lihat_lembur',compact('Lembur'));
	}
	public function lihat_izin($id)
	{
		$sqlizin="SELECT a.*,b.nik,b.nama_lengkap,b.jabatan,b.departemen,c.kode,c.nama as nama_ijin,d.nama as nama_appr,tgl_appr_1,status_appr_1,e.nama as pjs,
		case when status_appr_1=1 then 'Disetujui' when status_appr_1=2 then 'Ditolak' when status_appr_1=3 then 'Pending' end as sts_pengajuan
		FROM t_permit a
		left join get_data_karyawan() b on b.p_karyawan_id=a.p_karyawan_id
		left join m_jenis_ijin c on c.m_jenis_ijin_id=a.m_jenis_ijin_id
		left join p_karyawan d on d.p_karyawan_id=a.appr_1
		left join p_karyawan e on e.p_karyawan_id=a.pjs
		WHERE 1=1 and a.t_form_exit_id=$id and a.active=1 and c.tipe=1 ORDER BY a.tgl_awal desc";
		$izin=DB::connection()->select($sqlizin);

		return view('frontend.permit.lihat_izin',compact('izin'));
	}

	public function hapus_cuti($id)
	{
		DB::beginTransaction();
		try {
			DB::connection()->table("t_permit")
			->where("t_form_exit_id",$id)
			->delete();
			DB::commit();

			return redirect()->back()->with('success','Pengajuan Cuti Berhasil di Hapus!');
		} catch (\Exeception $e) {
			DB::rollback();
			return redirect()->back()->with('error',$e);
		}
	}

	public function hapus_izin($id)
	{
		DB::beginTransaction();
		try {
			DB::connection()->table("t_permit")
			->where("t_form_exit_id",$id)
			->delete();
			DB::commit();

			return redirect()->back()->with('success','Pengajuan Izin Berhasil di Hapus!');
		} catch (\Exeception $e) {
			DB::rollback();
			return redirect()->back()->with('error',$e);
		}
	}

	public function hapus_lembur($id)
	{
		DB::beginTransaction();
		try {
			DB::connection()->table("t_permit")
			->where("t_form_exit_id",$id)
			->delete();
			DB::commit();

			return redirect()->back()->with('success','Pengajuan Lembur Berhasil di Hapus!');
		} catch (\Exeception $e) {
			DB::rollback();
			return redirect()->back()->with('error',$e);
		}
	}


	public function lihat_ajuan($kode)
	{
		$sqldata="SELECT a.*,b.nik,b.nama_lengkap,c.kode,c.tipe,c.nama as nama_ijin,d.nama as nama_appr,tgl_appr_1,status_appr_1,b.pangkat,b.departemen,case when status_appr_1=1 then 'Disetujui' when status_appr_1=2 then 'Ditolak' end as sts_pengajuan,b.jabatan,e.nama as pjs
		FROM t_permit a
		left join get_data_karyawan() b on b.p_karyawan_id=a.p_karyawan_id
		left join m_jenis_ijin c on c.m_jenis_ijin_id=a.m_jenis_ijin_id
		left join p_karyawan d on d.p_karyawan_id=a.appr_1
		left join p_karyawan e on e.p_karyawan_id=a.pjs
		WHERE 1=1 and a.t_form_exit_id=$kode and a.active=1  ORDER BY a.tgl_awal desc";
		$data=DB::connection()->select($sqldata);
		$help = new Helper_function();
		return view('frontend.permit.lihat',compact('data'));
	}
	public function lihat_ajuan_2($kode)
	{
		$sqldata="SELECT a.*,b.nik,b.nama_lengkap,c.kode,c.tipe,c.nama as nama_ijin,d.nama as nama_appr,tgl_appr_1,status_appr_1,b.pangkat,b.departemen,case when status_appr_1=1 then 'Disetujui' when status_appr_1=2 then 'Ditolak' end as sts_pengajuan,b.jabatan,e.nama as pjs
		FROM t_permit a
		left join get_data_karyawan() b on b.p_karyawan_id=a.p_karyawan_id
		left join m_jenis_ijin c on c.m_jenis_ijin_id=a.m_jenis_ijin_id
		left join p_karyawan d on d.p_karyawan_id=a.appr_1
		left join p_karyawan e on e.p_karyawan_id=a.pjs
		WHERE 1=1 and a.t_form_exit_id=$kode and a.active=1  ORDER BY a.tgl_awal desc";
		$data=DB::connection()->select($sqldata);
		$help = new Helper_function();
		return view('frontend.permit.lihat_2',compact('data'));
	}
	public function lihat($kode)
	{
		$sqldata="SELECT a.*,b.nik,b.nama_lengkap,c.kode,c.tipe,c.nama as nama_ijin,d.nama as nama_appr,tgl_appr_1,status_appr_1,b.pangkat,b.departemen,case when status_appr_1=1 then 'Disetujui' when status_appr_1=2 then 'Ditolak' end as sts_pengajuan,b.jabatan,e.nama as pjs
		FROM t_permit a
		left join get_data_karyawan() b on b.p_karyawan_id=a.p_karyawan_id
		left join m_jenis_ijin c on c.m_jenis_ijin_id=a.m_jenis_ijin_id
		left join p_karyawan d on d.p_karyawan_id=a.appr_1
		left join p_karyawan e on e.p_karyawan_id=a.pjs
		WHERE 1=1 and a.t_form_exit_id=$kode and a.active=1  ORDER BY a.tgl_awal desc";
		$data=DB::connection()->select($sqldata);
		$help = new Helper_function();
		return view('frontend.permit.lihat_permit',compact('data','help'));
	}

	public function edit_ajuan($kode)
	{
		$sqldata="SELECT a.*,b.nik,b.nama_lengkap,c.kode,c.nama as nama_ijin,c.tipe,d.nama as nama_appr,tgl_appr_1,status_appr_1,b.pangkat,b.departemen,case when status_appr_1=1 then 'Disetujui' when status_appr_1=2 then 'Ditolak' end as sts_pengajuan,b.jabatan,e.nama as pjs
		FROM t_permit a
		left join get_data_karyawan() b on b.p_karyawan_id=a.p_karyawan_id
		left join m_jenis_ijin c on c.m_jenis_ijin_id=a.m_jenis_ijin_id
		left join p_karyawan d on d.p_karyawan_id=a.appr_1
		left join p_karyawan e on e.p_karyawan_id=a.pjs
		WHERE 1=1 and a.t_form_exit_id=$kode and a.active=1  ORDER BY a.tgl_awal desc";
		$data=DB::connection()->select($sqldata);
		$help = new Helper_function();
		if ($data[0]->m_jenis_ijin_id ==21) {
			$rekap = $help->rekap_absen($data[0]->tgl_awal,$data[0]->tgl_akhir,$data[0]->tgl_awal,$data[0]->tgl_akhir,-1);
		} else {
			$rekap= array();
		}
		//$rekap = $help->rekap_absen($data[0]->tgl_awal,$data[0]->tgl_akhir,$data[0]->tgl_awal,$data[0]->tgl_akhir,-1);
		return view('frontend.permit.edit',compact('data','rekap','help'));
	}
	public function edit_ajuan_2($kode)
	{
		$sqldata="SELECT a.*,b.nik,b.nama_lengkap,c.kode,c.nama as nama_ijin,c.tipe,d.nama as nama_appr,tgl_appr_1,status_appr_1,b.pangkat,b.departemen,case when status_appr_1=1 then 'Disetujui' when status_appr_1=2 then 'Ditolak' end as sts_pengajuan,b.jabatan,e.nama as pjs
		FROM t_permit a
		left join get_data_karyawan() b on b.p_karyawan_id=a.p_karyawan_id
		left join m_jenis_ijin c on c.m_jenis_ijin_id=a.m_jenis_ijin_id
		left join p_karyawan d on d.p_karyawan_id=a.appr_1
		left join p_karyawan e on e.p_karyawan_id=a.pjs
		WHERE 1=1 and a.t_form_exit_id=$kode and a.active=1  ORDER BY a.tgl_awal desc";
		$data=DB::connection()->select($sqldata);

		return view('frontend.permit.edit_2',compact('data'));
	}

	public function approve_ajuan(Request $request, $kode)
	{
		DB::beginTransaction();
		try {
			$iduser=Auth::user()->id;
			$sqlidkar="select * from p_karyawan where user_id=$iduser";
			$idkar=DB::connection()->select($sqlidkar);
			$id=$idkar[0]->p_karyawan_id;

			DB::connection()->table("t_permit")
			->where("t_form_exit_id",$kode)
			->update([
				"keterangan_atasan"=>$request->get('keterangan_atasan'),
				"status_appr_1"=>$request->get('sts_pengajuan'),
				"jam_awal"=>$request->get('jam_awal'),
				"jam_akhir"=>$request->get('jam_akhir'),
				"tgl_appr_1" => date("Y-m-d"),
				"update_by" => $id,
				"update_date" => date("Y-m-d H:i:s"),
			]);
			DB::commit();

			$sqldata="SELECT a.*,b.nik,b.nama_lengkap,b.jabatan,b.departemen,c.kode as kdpermit,c.nama as nama_ijin,d.nama as nama_appr,tgl_appr_1,status_appr_1 , case when status_appr_1=1 then 'Disetujui' when status_appr_1=2 then 'Ditolak' end as sts_pengajuan ,coalesce(b.email_perusahaan,'mankewink@gmail.com') as email_perusahaan
			FROM t_permit a
			left join get_data_karyawan() b on b.p_karyawan_id=a.p_karyawan_id
			left join m_jenis_ijin c on c.m_jenis_ijin_id=a.m_jenis_ijin_id
			left join p_karyawan d on d.p_karyawan_id=a.appr_1
			WHERE 1=1 and a.t_form_exit_id=$kode and a.active=1 ORDER BY a.tgl_awal desc ";
			$data=DB::connection()->select($sqldata);

			$appr=Setting::where(['nama'=>'email_hrd'])->first();
			$emailhrd=$appr->val1;

			//Mail::send('email_bls_cuti', ['data' => $data,'emailhrd'=>$emailhrd], function ($mail) use ($data,$emailhrd){
			//		$mail->from('ethicagroup1@gmail.com', 'Ethica');
			//		$mail->to($data[0]->email_perusahaan)->subject('Status Pengajuan');
			//		$mail->cc($emailhrd);
			//	});
			return redirect()->route('fe.listed')->with('success','Pengajuan Berhasil di !'.$data[0]->sts_pengajuan);
		} catch (\Exeception $e) {
			DB::rollback();
			return redirect()->back()->with('error',$e);
		}
	}
	public function approve_ajuan_2(Request $request, $kode)
	{
		DB::beginTransaction();
		try {
			$iduser=Auth::user()->id;
			$sqlidkar="select * from p_karyawan where user_id=$iduser";
			$idkar=DB::connection()->select($sqlidkar);
			$id=$idkar[0]->p_karyawan_id;

			DB::connection()->table("t_permit")
			->where("t_form_exit_id",$kode)
			->update([
				"keterangan_atasan_2"=>$request->get('keterangan_atasan2'),
				"status_appr_2"=>$request->get('sts_pengajuan2'),
				"jam_awal"=>$request->get('jam_awal'),
				"jam_akhir"=>$request->get('jam_akhir'),
				"tgl_appr_2" => date("Y-m-d"),
				"update_by" => $id,
				"update_date" => date("Y-m-d H:i:s"),
			]);
			DB::commit();

			$sqldata="SELECT a.*,b.nik,b.nama_lengkap,b.jabatan,b.departemen,c.kode as kdpermit,c.nama as nama_ijin,d.nama as nama_appr,tgl_appr_1,status_appr_1 , case when status_appr_1=1 then 'Disetujui' when status_appr_1=2 then 'Ditolak' end as sts_pengajuan ,coalesce(b.email_perusahaan,'mankewink@gmail.com') as email_perusahaan
			FROM t_permit a
			left join get_data_karyawan() b on b.p_karyawan_id=a.p_karyawan_id
			left join m_jenis_ijin c on c.m_jenis_ijin_id=a.m_jenis_ijin_id
			left join p_karyawan d on d.p_karyawan_id=a.appr_1
			WHERE 1=1 and a.t_form_exit_id=$kode and a.active=1 ORDER BY a.tgl_awal desc ";
			$data=DB::connection()->select($sqldata);

			$appr=Setting::where(['nama'=>'email_hrd'])->first();
			$emailhrd=$appr->val1;

			//Mail::send('email_bls_cuti', ['data' => $data,'emailhrd'=>$emailhrd], function ($mail) use ($data,$emailhrd){
			//		$mail->from('ethicagroup1@gmail.com', 'Ethica');
			//		$mail->to($data[0]->email_perusahaan)->subject('Status Pengajuan');
			//		$mail->cc($emailhrd);
			//	});
			return redirect()->route('fe.listed')->with('success','Pengajuan Berhasil di !'.$data[0]->sts_pengajuan);
		} catch (\Exeception $e) {
			DB::rollback();
			return redirect()->back()->with('error',$e);
		}
	}

	public function simpan_perdin(Request $request)
	{
		DB::beginTransaction();
		try {
			$iduser=Auth::user()->id;
			$sqlidkar="select * from p_karyawan where user_id=$iduser";
			$idkar=DB::connection()->select($sqlidkar);
			$id=$idkar[0]->p_karyawan_id;

			$this->validate($request, [
				'cuti' => 'required',
				'tgl_awal' => 'required',
				'tgl_akhir' => 'required',
				'lama' => 'required',
				'atasan' => 'required',
			]);

			$data = $request->all();
			if (empty($request->input('cuti'))) {
				$data['cuti'] = $request->input('cuti');
			}
			if (empty($request->input('tgl_awal'))) {
				$data['tgl_awal'] = $request->input('tgl_awal');
			}
			if (empty($request->input('tgl_akhir'))) {
				$data['tgl_akhir'] = $request->input('tgl_akhir');
			}
			if (empty($request->input('atasan'))) {
				$data['atasan'] = $request->input('atasan');
			}
			if (empty($request->input('lama'))) {
				$data['lama'] = $request->input('lama');
			}

			date_default_timezone_set('Asia/Jakarta');
			$time=date('Y-m-d H:i:s');

			$Bulan=date('m');
			$Tahun2=date('y');

			$sqlnocuti="SELECT COALESCE(MAX(substr(kode, 14 , 5)::NUMERIC),10000)+1 AS counter
			FROM t_permit
			WHERE substr(kode, 8 , 2) ILIKE '$Bulan'
			AND substr(kode, 11 , 2) ILIKE '$Tahun2' ";
			//echo $sqlnopengajuan;die;
			$datanocuti=DB::connection()->select($sqlnocuti);
			$Counter=$datanocuti[0]->counter;
			$nocuti='PERMIT.'.$Bulan.'.'.$Tahun2.'.'.$Counter;

			DB::connection()->table("t_permit")
			->insert([
				"m_jenis_ijin_id"=>$request->get('cuti'),
				"p_karyawan_id"=>$id,
				"kode"=>$nocuti,
				"jam_awal"=>date('H:i:s'),
				"tgl_awal"=>date('Y-m-d',strtotime($request->get('tgl_awal'))),
				"tgl_akhir"=>date('Y-m-d',strtotime($request->get('tgl_akhir'))),
				"keterangan"=>$request->get('alasan'),
				"appr_1"=>$request->get('atasan'),
				"lama"=>$request->get('lama'),
				"pjs"=>$request->get('pjs'),
				"create_date"=>date('Y-m-d  H:i:s'),
				"create_by"=>$id,
				"active"=>1,
			]);

			if ($request->file('file')) {
				//echo 'masuk';die;
				$file = $request->file('file');
				$destination="dist/img/file/";
				$path = 'permit-'.date('YmdHis').$file->getClientOriginalName();$file->move($destination,$path);
				//$path=$file->getClientOriginalName();
				//echo $path;die;
				DB::connection()->table("t_permit")->where("kode",$nocuti)
				->update([
					"foto"=>$path
				]);
			}

			DB::commit();

			$sqldata="SELECT a.*,b.nik,b.nama_lengkap,b.jabatan,b.departemen,c.kode as kdpermit,c.nama as nama_ijin,d.nama as nama_appr,tgl_appr_1,status_appr_1,coalesce(b.email_perusahaan,'mankewink@gmail.com') as email_perusahaan,e.email_perusahaan as email_appr
			FROM t_permit a
			left join get_data_karyawan() b on b.p_karyawan_id=a.p_karyawan_id
			left join m_jenis_ijin c on c.m_jenis_ijin_id=a.m_jenis_ijin_id
			left join p_karyawan d on d.p_karyawan_id=a.appr_1
			LEFT JOIN get_data_karyawan () e ON e.p_karyawan_id = A.appr_1
			WHERE 1=1 and a.kode='$nocuti' and a.active=1 and c.tipe=4 ORDER BY a.tgl_awal desc ";
			$data=DB::connection()->select($sqldata);

			//Mail::send('email_appr', ['data' => $data], function ($mail) use ($data){
			//		$mail->from('ethicagroup1@gmail.com', 'Ethica');
			//		$mail->to($data[0]->email_appr)->subject('Pengajuan Perjalanan Dinas');
			//		if($data[0]->foto!='' || $data[0]->foto!=null){
			//			$mail->attach('dist/img/file/'.$data[0]->foto);
			//		}
			//
			//				});

			return redirect()->route('fe.list_perdin')->with('success','Pengajuan Perjalanan Dinas Berhasil di simpan!');
		} catch (\Exeception $e) {
			DB::rollback();
			return redirect()->back()->with('error',$e);
		}
	}
	public function lihat_perdin($id)
	{
		$sqlizin="SELECT a.*,b.nik,b.nama_lengkap,b.jabatan,b.departemen,c.kode,c.nama as nama_ijin,d.nama as nama_appr,tgl_appr_1,status_appr_1,e.nama as pjs,
		case when status_appr_1=1 then 'Disetujui' when status_appr_1=2 then 'Ditolak' when status_appr_1=3 then 'Pending' end as sts_pengajuan
		FROM t_permit a
		left join get_data_karyawan() b on b.p_karyawan_id=a.p_karyawan_id
		left join m_jenis_ijin c on c.m_jenis_ijin_id=a.m_jenis_ijin_id
		left join p_karyawan d on d.p_karyawan_id=a.appr_1
		left join p_karyawan e on e.p_karyawan_id=a.pjs
		WHERE 1=1 and a.t_form_exit_id=$id and a.active=1 and c.tipe=4

		ORDER BY a.tgl_awal desc";
		$cuti=DB::connection()->select($sqlizin);

		return view('frontend.permit.lihat_perdin',compact('cuti'));
	}

	public function hapus_perdin($id)
	{
		DB::beginTransaction();
		try {
			DB::connection()->table("t_permit")
			->where("t_form_exit_id",$id)
			->delete();
			DB::commit();

			return redirect()->back()->with('success','Pengajuan Izin Berhasil di Hapus!');
		} catch (\Exeception $e) {
			DB::rollback();
			return redirect()->back()->with('error',$e);
		}
	}
}

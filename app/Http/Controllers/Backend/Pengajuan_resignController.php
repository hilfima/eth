<?php

namespace App\Http\Controllers\Backend;
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

class Pengajuan_resignController extends Controller
{
	public function __construct()
    {
        $this->middleware('auth');
    }public function pengajuan_resign(Request $request)
	{
		
		$iduser=Auth::user()->id;
		$sqlidkar="select * from p_karyawan 
					join p_karyawan_pekerjaan a on p_karyawan.p_karyawan_id = a.p_karyawan_id 
					where user_id=$iduser";
		
		$idkar=DB::connection()->select($sqlidkar);
		$idkaryawan=$idkar[0]->p_karyawan_id;
		
		$sqlfasilitas="SELECT * FROM t_resign
		left join p_karyawan on t_resign.p_karyawan_id = p_karyawan.p_karyawan_id
		WHERE 1=1  and t_resign.active=1 and t_resign.p_karyawan_id=$idkaryawan";
		$pengajuan_resign=DB::connection()->select($sqlfasilitas);
		$page ="karyawan";
		$type='karyawan';
		return view('backend.pengajuan_resign.pengajuan_resign',compact('pengajuan_resign','page','type','idkaryawan'));
	}
	public function approval_pengajuan_resign(Request $request)
	{
		
		$iduser=Auth::user()->id;
		$sqlidkar="select * from p_karyawan 
					join p_karyawan_pekerjaan a on p_karyawan.p_karyawan_id = a.p_karyawan_id 
					where user_id=$iduser";
		
		$idkar=DB::connection()->select($sqlidkar);
		$idkaryawan=$idkar[0]->p_karyawan_id;
		
		$sqlfasilitas="SELECT * FROM t_resign
		left join p_karyawan on t_resign.p_karyawan_id = p_karyawan.p_karyawan_id
		WHERE 1=1  and t_resign.active=1 and (t_resign.appr_direksi=$idkaryawan or t_resign.appr_atasan=$idkaryawan)";
		$pengajuan_resign=DB::connection()->select($sqlfasilitas);
		$type='Approval';
		return view('backend.pengajuan_resign.pengajuan_resign',compact('pengajuan_resign','type','idkaryawan'));
	}
	public function edit_pengajuan_resign($id,$page)
	{
		$type = 'update_pengajuan_resign';
		$sanksi=DB::connection()->select("select *,p_karyawan.nama as nmk, m_jabatan.nama as nmj from t_resign 
		left join p_karyawan on p_karyawan.p_karyawan_id = t_resign.p_karyawan_id 
		left join p_karyawan_pekerjaan on p_karyawan_pekerjaan.p_karyawan_id = t_resign.p_karyawan_id 
		left join m_jabatan on p_karyawan_pekerjaan.m_jabatan_id = m_jabatan.m_jabatan_id 
		where t_resign.active=1 and t_resign_id=$id  ");

		$data['p_karyawan_id']=$sanksi[0]->p_karyawan_id;
		$data['tanggal_terakhir_kerja']=$sanksi[0]->tanggal_terakhir_kerja;
		$data['alasan_mengundurkan_diri']=$sanksi[0]->alasan_mengundurkan_diri;
		$data['status_appr_direksi']=$sanksi[0]->status_appr_direksi;
		$data['tgl_appr_direksi']=$sanksi[0]->tgl_appr_direksi;
		$data['keterangan_direksi']=$sanksi[0]->keterangan_direksi;
		$data['status_appr_atasan']=$sanksi[0]->status_appr_atasan;
		$data['tgl_appr_atasan']=$sanksi[0]->tgl_appr_atasan;
		$data['keterangan_atasan']=$sanksi[0]->keterangan_atasan;
		$data['nama_karyawan']=$sanksi[0]->nmk;
		$data['nama_jabatan']=$sanksi[0]->nmj;
		$data['nik']=$sanksi[0]->nik;
		$periode = DB::connection()->select("select *,case when periode=1 then 'Januari'
            when periode=2 then 'Februari'
            when periode=3 then 'Maret'
            when periode=4 then 'April'
            when periode=5 then 'Mei'
            when periode=6 then 'Juni'
            when periode=7 then 'Juli'
            when periode=8 then 'Agustus'
            when periode=9 then 'September'
            when periode=10 then 'Oktober'
            when periode=11 then 'November'
            when periode=12 then 'Desember' end as bulan, case when type=0 then 'Pekanan' else 'Bulanan' end as tipe from m_periode_absen where active=1 and tgl_akhir >= now() and tipe_periode='absen'");
		return view('backend.pengajuan_resign.tambah_pengajuan_resign',compact('id','data','type','page','periode'));
	}
	public function tambah_pengajuan_resign()
	{
$iduser=Auth::user()->id;
		$sqlidkar="select * from p_karyawan 
					join p_karyawan_pekerjaan a on p_karyawan.p_karyawan_id = a.p_karyawan_id 
					where user_id=$iduser";
		
		$idkar=DB::connection()->select($sqlidkar);
		$id_karyawan=$idkar[0]->p_karyawan_id;
		$id = '';
		$type = 'simpan_pengajuan_resign';
		$data['p_karyawan_id']='';
		$data['tanggal_terakhir_kerja']='';
		$data['alasan_mengundurkan_diri']='';

		$karyawan=DB::connection()->select("select * from p_karyawan where active=1 order by nama ");
		$jenis_sanksi=DB::connection()->select("select * from m_jenis_sanksi where active=1 order by nama_sanksi ");

		$help=new Helper_function();
		$jabstruk = $help->jabatan_struktural($id_karyawan);
		$atasan = $jabstruk['atasan'];
		$bawahan = $jabstruk['bawahan'];
		$sejajar = $jabstruk['sejajar'];
		$atasan_layer = $jabstruk['atasan_layer'];
		
		if(isset($atasan_layer[2])){
		 	$atasan = isset($atasan_layer[1])?$atasan_layer[1]:'-1';
		    $atasan2 = $atasan_layer[2];
		}else{
		$atasan = '-1';
		
		$atasan2 = isset($atasan_layer[1])?$atasan_layer[1]:'-1';
		}
		
		$sqlappr="SELECT * from get_data_karyawan() WHERE m_jabatan_id in($atasan)   and m_pangkat_id not in (1,2,3)";
		$appr1=DB::connection()->select($sqlappr);
		$sqlappr="SELECT * from get_data_karyawan() WHERE m_jabatan_id in($atasan2) and m_pangkat_id in(5,6) ";
		$appr2=DB::connection()->select($sqlappr);
		$page = 'karyawan';
		return view('backend.pengajuan_resign.tambah_pengajuan_resign',compact('id','page','data','type','karyawan','jenis_sanksi','appr1','appr2'));
	}public function simpan_pengajuan_resign(Request $request)
	{
		$iduser=Auth::user()->id;
		$sqlidkar="select * from p_karyawan 
					join p_karyawan_pekerjaan a on p_karyawan.p_karyawan_id = a.p_karyawan_id 
					where user_id=$iduser";
		
		$idkar=DB::connection()->select($sqlidkar);
		$idkaryawan=$idkar[0]->p_karyawan_id;
		$data = $request->data;
		$data['status'] =1;
		$data['p_karyawan_id']=$idkaryawan;
		$data['created_date']= date('Y-m-d H:i:s');
		$data['created_by']= $iduser;
		DB::connection()->table("t_resign")
		->insert($data);


		return redirect()->route('be.pengajuan_resign')->with('success',' Pengajuan Berhasil di input!');
	}public function update_pengajuan_resign (Request $request, $id)
	{
		$data = $request->data;
		DB::connection()->table("t_resign")
		->where("t_resign_id",$id)
		->update($data);


		return redirect()->route('be.pengajuan_resign')->with('success',' kotak_laporan Berhasil di input!');
	}public function hapus_pengajuan_resign (Request $request, $id)
	{
		
		DB::connection()->table("t_resign")
		->where("t_resign_id",$id)
		->update([
			"active" => 0,


		]);


		return redirect()->route('be.pengajuan_resign')->with('success',' kotak_laporan Berhasil di input!');
	}
}
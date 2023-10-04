<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Auth;

class KonfirmasiController extends Controller
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
    public function lihat(Request $request, $id)
    {
    	$type = $request->get('type');
    	if($type=='shift'){
			$jenis=1;
			$join='join absen_shift a on absen_permit.awal_absen_shift = a.absen_shift_id
					join p_karyawan aa on a.p_karyawan_id = aa.p_karyawan_id
					join absen aaa on a.absen_id = aaa.absen_id
					
					join absen_shift b on absen_permit.ganti_absen_shift = b.absen_shift_id 
					join p_karyawan bb on b.p_karyawan_id = bb.p_karyawan_id
					join absen bbb on b.absen_id = bbb.absen_id
					
					left join p_karyawan c on c.p_karyawan_id = absen_permit.appr
			';
			$select = '
				aa.nama as nama_pengaju, aaa.jam_masuk as jam_masuk_pengaju, aaa.jam_keluar as jam_keluar_pengaju, aaa.tgl_awal as tgl_awal_pengaju, aaa.tgl_akhir as tgl_akhir_pengaju, 
				bb.nama as nama_pengganti, bbb.jam_masuk as jam_masuk_pengganti, bbb.jam_keluar as jam_keluar_pengganti, bbb.tgl_awal as tgl_awal_pengganti, bbb.tgl_akhir as tgl_akhir_pengganti,
				c.nama as nama_appr
			';
		}else if($type=='absen'){
			$jenis=2;
			$join='';
			$select = '';
		} 
    		
    	$sql = "select absen_permit.*,$select from absen_permit $join where jenis = $jenis";
    	$data=DB::connection()->select($sql);
 		return view('backend.konfirmasi.lihat',compact('type','id','data'));
    	
    }public function konfirmasi($type)
    {
    	if($type=='shift'){
			$jenis=1;
			$join='join absen_shift a on absen_permit.awal_absen_shift = a.absen_shift_id
					join p_karyawan aa on a.p_karyawan_id = aa.p_karyawan_id
					join absen aaa on a.absen_id = aaa.absen_id
					
					join absen_shift b on absen_permit.ganti_absen_shift = b.absen_shift_id 
					join p_karyawan bb on b.p_karyawan_id = bb.p_karyawan_id
					join absen bbb on b.absen_id = bbb.absen_id
					
					left join p_karyawan c on c.p_karyawan_id = absen_permit.appr
			';
			$select = '
				aa.nama as nama_pengaju, aaa.jam_masuk as jam_masuk_pengaju, aaa.jam_keluar as jam_keluar_pengaju, aaa.tgl_awal as tgl_awal_pengaju, aaa.tgl_akhir as tgl_akhir_pengaju, 
				bb.nama as nama_pengganti, bbb.jam_masuk as jam_masuk_pengganti, bbb.jam_keluar as jam_keluar_pengganti, bbb.tgl_awal as tgl_awal_pengganti, bbb.tgl_akhir as tgl_akhir_pengganti,
				c.nama as nama_appr
			';
		}else if($type=='absen'){
			$jenis=2;
			$join='';
			$select = '';
		} 
    		
    	$sql = "select absen_permit.*,$select from absen_permit $join where jenis = $jenis";
    	$data=DB::connection()->select($sql);
 		return view('backend.konfirmasi.'.$type,compact('type','data'));
 	}
}

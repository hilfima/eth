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
use App\Helper_function;
class GratifikasiController extends Controller
{
    public function laporan_gratifikasi()
    {
    	$iduser=Auth::user()->id;
		$sqlidkar="select * from p_karyawan where user_id=$iduser";
		$idkar=DB::connection()->select($sqlidkar);
		$id=$idkar[0]->p_karyawan_id;
		$id_karyawan=$idkar[0]->p_karyawan_id;
		$thn = date('Y');
    	$gratifikasi_sisa_plafon = DB::connection()->select("select (select sum(perkiraan_harga::numeric) from t_gratifikasi where tgl_diterima >= '$thn-01-01' and tgl_diterima <= '$thn-12-31' and (status=2 or status=5) and active=1 and p_karyawan_yg_melaporkan = $id_karyawan ) as terpakai,
    	case 
    		when c.m_pangkat_id=6 then 2500000
    		when c.m_pangkat_id=5 or c.m_pangkat_id=4 then 1500000
    		else 1000000 
    			end as plafon_awal
    		
    	
    	 from p_karyawan_pekerjaan b 
    	 left join m_jabatan c on b.m_jabatan_id = c.m_jabatan_id
    	 where p_karyawan_id = $id_karyawan 
    	 ");
    	
    	$gratifikasi=DB::connection()->select("select *,a.nama as karyawan_dari
    	
    	
    	 from t_gratifikasi 
		--left join m_tipe_pemberian on t_gratifikasi.m_tipe_pemberian_id = m_tipe_pemberian.m_tipe_pemberian_id
		left join p_karyawan a on t_gratifikasi.p_karyawan_dari = a.p_karyawan_id
		left join p_karyawan_pekerjaan b on t_gratifikasi.p_karyawan_yg_melaporkan = b.p_karyawan_id
		left join m_jabatan c on b.m_jabatan_id = c.m_jabatan_id
		
		where t_gratifikasi.active =1  and t_gratifikasi.p_karyawan_yg_melaporkan=$id_karyawan");
		$help = new Helper_function();
		return view('frontend.gratifikasi.laporan_gratifikasi',compact('gratifikasi',"gratifikasi_sisa_plafon",'help'));
    }public function tambah_laporan_gratifikasi()
    {
    	
    	$tipe_pemberian=DB::connection()->select("select * from m_tipe_pemberian where active=1");
    	$karyawan=DB::connection()->select("select * from p_karyawan where active=1 order by nama");
		return view('frontend.gratifikasi.tambah_gratifikasi',compact('tipe_pemberian','karyawan'));
    }public function baca_laporan_gratifikasi($id)
    {
    	$gratifikasi=DB::connection()->select("select *,a.nama as karyawan_dari from t_gratifikasi 
		left join m_tipe_pemberian on t_gratifikasi.m_tipe_pemberian_id = m_tipe_pemberian.m_tipe_pemberian_id
		left join p_karyawan a on t_gratifikasi.p_karyawan_dari = a.p_karyawan_id
		where t_gratifikasi.active =1  and t_gratifikasi.t_gratifikasi_id=$id");
    	$tipe_pemberian=DB::connection()->select("select * from m_tipe_pemberian where active=1");
    	$karyawan=DB::connection()->select("select * from p_karyawan where active=1 order by nama");
		return view('frontend.gratifikasi.baca_gratifikasi',compact('tipe_pemberian','karyawan','gratifikasi'));
    }
    public function simpan_laporan_gratifikasi(Request $request)
	{
		$iduser=Auth::user()->id;
		$sqlidkar="select * from p_karyawan left  join p_karyawan_pekerjaan on p_karyawan.p_karyawan_id = p_karyawan_pekerjaan.p_karyawan_id where user_id=$iduser";
		$idkar=DB::connection()->select($sqlidkar);
		$id=$idkar[0]->p_karyawan_id;
		$data =  $request->get("data");
		$data['p_karyawan_yg_melaporkan'] =  $id;
		$data['tgl_pelaporan'] =  date('Y-m-d');
		if ($request->file('file')) { //echo 'masuk';die;
					$file = $request->file('file');
					$destination = "dist/img/file/";
					$path = 'gratifikasi-' . date('ymdhis') . '-' . $file->getClientOriginalName();
					$file->move($destination, $path);
					
					$data['bukti'] =  $path;
					
		}
		DB::connection()->table("t_gratifikasi")
		->insert($data);
		

		return redirect()->route('fe.laporan_gratifikasi')->with('success',' Laporan Gratifikasi Berhasil di input!');
	}public function update_laporan_gratifikasi(Request $request,$id)
	{
		
		$data =  $request->get("data");
		$data2['status'] =  $data['status'];
		DB::connection()->table("t_gratifikasi")
			->where('t_gratifikasi_id',$id)
			->update($data2);
		

		return redirect()->route('fe.laporan_gratifikasi')->with('success',' Laporan Gratifikasi Berhasil di input!');
	}public function kembalikan_laporan_gratifikasi(Request $request,$id)
	{
		
		//$data =  $request->get("data");
		$data2['status'] =  4;
		DB::connection()->table("t_gratifikasi")
		->where('t_gratifikasi_id',$id)
		->update($data2);
		

		return redirect()->route('fe.laporan_gratifikasi')->with('success',' Laporan Gratifikasi Berhasil di input!');
	}public function konfirmasi_laporan_gratifikasi(Request $request,$id)
	{
		
		$data2['status'] =  5;
		DB::connection()->table("t_gratifikasi")
		->where('t_gratifikasi_id',$id)
		->update($data2);
		

		return redirect()->route('fe.laporan_gratifikasi')->with('success',' Laporan Gratifikasi Berhasil di input!');
	}public function hapus_laporan_gratifikasi(Request $request,$id)
	{
		$iduser=Auth::user()->id;
		$data['active'] =  0;
		DB::connection()->table("t_gratifikasi")
		->where("t_gratifikasi_id",$id)
		->update($data);
		

		return redirect()->route('fe.laporan_gratifikasi')->with('success',' Laporan Gratifikasi Berhasil di hapus!');
	}
}
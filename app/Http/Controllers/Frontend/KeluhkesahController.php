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

class KeluhkesahController extends Controller
{
	public function __construct()
	{
		$this->middleware('auth');
	}
	public function keluhkesah(Request $request)
	{
		$iduser=Auth::user()->id;
		$sqlidkar="select * from p_karyawan where user_id=$iduser";
		$idkar=DB::connection()->select($sqlidkar);
		$id=$idkar[0]->p_karyawan_id;
		
		$sqlfasilitas="SELECT * FROM t_keluh_kesah
                WHERE 1=1  and t_keluh_kesah.p_karyawan_id = $id  ";
		$keluh_kesah=DB::connection()->select($sqlfasilitas);

		return view('frontend.keluhkesah.keluhkesah',compact('keluh_kesah'));
	}public function simpan_keluhkesah(Request $request)
	{

		DB::beginTransaction();
		try {
			$idUser=Auth::user()->id;
			$sqlidkar="select * from p_karyawan where user_id=$idUser";
			$idkar=DB::connection()->select($sqlidkar);
			$id=$idkar[0]->p_karyawan_id;
			DB::connection()->table("t_keluh_kesah")

			->insert([

				"p_karyawan_id"=>$id,
				"keluh_kesah"=>$request->get("isi"),
				"judul"=>$request->get("judul"),

				"create_date" => date("Y-m-d"),
				"create_by" => $idUser,
			]);

			DB::commit();
			return redirect()->route('fe.keluh_kesah')->with('success',' BPJS Berhasil di Ubah!');
		} catch (\Exeception $e) {
			DB::rollback();
			return redirect()->back()->with('error',$e);
		}
	}public function baca_keluh_kesah ($id)
	{
		$sqlfasilitas="SELECT * FROM t_keluh_kesah
		WHERE 1=1  and t_keluh_kesah.t_keluh_kesah_id = $id  ";
		$keluh_kesah=DB::connection()->select($sqlfasilitas);
		
		
		
		$sqlfasilitas="SELECT * FROM t_keluh_kesah_detail
		WHERE 1=1  and t_keluh_kesah_id = $id  order by create_date ";
		$balasan=DB::connection()->select($sqlfasilitas);
		
		return view('frontend.keluhkesah.baca_keluhkesah',compact('keluh_kesah','balasan'));
		
		
	}public function simpan_keluh_kesah_detail (Request $request)
	{
	    DB::beginTransaction();
		try {
		    $data['t_keluh_kesah_id'] = $request->t_keluh_kesah_id;
		    $data['pesan'] = str_ireplace(array('Powered By','Froala Editor','href="https://www.froala.com/wysiwyg-editor'),array('','','href="#"'),$request->get("pesan"));
		    $data['create_date'] = date('Y-m-d H:i:s');
		    $data['create_by'] = Auth::user()->id;
		    $data['type'] = 1;
			DB::connection()->table("t_keluh_kesah_detail")
		
			->insert($data);
			unset($data);
		    $data['status'] = 'Dijawab Karyawan';
		    DB::connection()->table('t_keluh_kesah')->where('t_keluh_kesah_id',$request->t_keluh_kesah_id)->update($data);
			DB::commit();

			return redirect()->back()->with('success','Pengajuan Izin Berhasil di Hapus!');
		} catch (\Exeception $e) {
			DB::rollback();
			return redirect()->back()->with('error',$e);
		}
	}
	public function tambah_keluhkesah()
	{
		
		return view('frontend.keluhkesah.tambah_keluhkesah');
	}
}
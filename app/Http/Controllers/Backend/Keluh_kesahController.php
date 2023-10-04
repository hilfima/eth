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

class Keluh_kesahController extends Controller
{
	public function __construct()
    {
        $this->middleware('auth');
    }public function keluh_kesah (Request $request)
	{
		
		$sqlfasilitas="SELECT *,t_keluh_kesah.create_date FROM t_keluh_kesah
				join p_karyawan on t_keluh_kesah.p_karyawan_id = p_karyawan.p_karyawan_id
                WHERE 1=1  order by t_keluh_kesah_id desc ";
		$keluh_kesah=DB::connection()->select($sqlfasilitas);

		return view('backend.keluh_kesah.keluh_kesah',compact('keluh_kesah'));
	}public function simpan_keluh_kesah_detail (Request $request)
	{
	    DB::beginTransaction();
		try {
		    $data['t_keluh_kesah_id'] = $request->t_keluh_kesah_id;
		    $data['pesan'] = str_ireplace(array('Powered By','Froala Editor','href="https://www.froala.com/wysiwyg-editor'),array('','','href="#"'),$request->get("pesan"));
		    $data['create_date'] = date('Y-m-d H:i:s');
		    $data['create_by'] = Auth::user()->id;
		    $data['type'] = 2;
			DB::connection()->table("t_keluh_kesah_detail")
		
			->insert($data);
			
			unset($data);
		    $data['status'] = 'Dijawab HC';
		    DB::connection()->table('t_keluh_kesah')->where('t_keluh_kesah_id',$request->t_keluh_kesah_id)->update($data);
			DB::commit();

			return redirect()->back()->with('success','Balasan Berhasil diTambahkan!');
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
		
		    $data['status'] = 'Sudah Terbaca HC';
		    DB::connection()->table('t_keluh_kesah')->where('t_keluh_kesah_id',$id)->update($data);
		return view('backend.keluh_kesah.baca_keluhkesah',compact('keluh_kesah','balasan'));
	}
}
<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Auth;
use Response;
use Maatwebsite\Excel\Facades\Excel;
use App\Eloquent\AbsenLog;
use App\Eloquent\Absen;
use App\Helper_function;
use PDF;

class PengaturanBPJSController extends Controller
{
	public function __construct()
    {
        $this->middleware('auth');
    }public function save_pengaturan_page_bpjs(Request $request)
    {
        DB::connection()->table("pengaturan_page_bpjs")->where("pengaturan_page_bpjs_id",1)->update(["html"=>str_ireplace(array('Powered By','Froala Editor','href="https://www.froala.com/wysiwyg-editor'),array('','','href="#"'),$request->get("html"))]);
        return redirect()->back()->with('success','Pengajuan Karyawan Berhasil di input!');
    }public function pengaturan_page_bpjs()
    {
        
        $page = DB::connection()->select('select * from pengaturan_page_bpjs');
        return view('backend.pengaturan_page.bpjs',compact('page'));
        
    }
}
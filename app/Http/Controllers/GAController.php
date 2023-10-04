<?php

namespace App\Http\Controllers;

use App\GADetil;
use App\GADetilPinjaman;
use App\list_ga_print_xls;
use App\User;
use Illuminate\Http\Request;
use DB;
use Validator;
use Auth;
use Maatwebsite\Excel\Facades\Excel;

class GAController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function ga()
    {
        $sqllokasi="SELECT * FROM m_lokasi
                    WHERE 1=1 and active=1";
        $lokasi=DB::connection()->select($sqllokasi);

        return view('general_affair',compact('lokasi'));
    }

    public function ga_pembelian()
    {
        $sqlkaryawan="SELECT * from hrm.p_karyawan where active =1 ORDER by nama";
        $karyawan=DB::connection()->select($sqlkaryawan);

        $sqldepartemen="SELECT * from hrm.m_departemen where active =1 ORDER by nama";
        $departemen=DB::connection()->select($sqldepartemen);
        return view('general_affair.ga_pembelian', compact('karyawan','departemen'));
    }

    public function simpan_ga_pembelian(Request $request){
        //echo 'masuk';die;
        $sqlid="SELECT COALESCE(MAX(req_ga_id),0)+1 AS counter FROM requisition_ga";
        $dataid=DB::connection()->select($sqlid);
        $id=$dataid[0]->counter;
        DB::connection()->table("requisition_ga")
            ->insert([
                "req_ga_id"=>($id),
                "p_karyawan_id"=>($request->get("pengaju")),
                "pengajuan"=>($request->get("pengajuan")),
                "m_departemen_id"=>($request->get("unit_kerja")),
                "tgl_pengajuan"=>date('Y-m-d',strtotime($request->get("tanggal"))),
                "created_at" => date("Y-m-d H:i:s"),
                "created_by" => ($request->get("karyawan"))
            ]);

        $rules = array(
            'nama.*' => 'required',
            'qty.*' => 'required'
        );
        $error = Validator::make($request->all(), $rules);
        if ($error->fails()) {
            return response()->json([
                'error' => $error->errors()->all()
            ]);
        }

        $nama = $request->nama;
        $qty = $request->qty;
        $desc = $request->desc;
        $link = $request->link;
        $tgl = $request->tgl;
        for ($count = 0; $count < count($nama); $count++) {
            $data = array(
                'req_ga_id' => $id,
                'nama_barang' => $nama[$count],
                'qty' => $qty[$count],
                'deskripsi' => $desc[$count],
                'link' => $link[$count],
                'tgl_digunakan' => $tgl[$count],
            );
            $insert_data[] = $data;
        }

        GADetil::insert($insert_data);
        /*return response()->json([
            'success' => 'Data Added successfully.'
        ]);*/

        return redirect()->back()->with('success',' Pengajuan Pembelian Berhasil Di Ajukan!');
    }

    public function list_ga(){
        $tgl_awal=date('Y-m-d');
        $tgl_akhir=date('Y-m-d');
        $pengajuan='';
        $unitkerja='';

        $sqlga="SELECT c.nama,d.nama as unit_kerja,
case when pengajuan=1 then 'ATK BULANAN'
when pengajuan=2 then 'ASSET'
when pengajuan=3 then 'TOOLS MARKETING' end as sifat,
a.tgl_pengajuan, b.nama_barang,b.qty,b.deskripsi,b.link,b.tgl_digunakan
FROM requisition_ga a
LEFT JOIN requisition_detil_ga b on b.req_ga_id=a.req_ga_id
LEFT JOIN p_karyawan c on c.p_karyawan_id=a.p_karyawan_id
LEFT JOIN m_departemen d on d.m_departemen_id=a.m_departemen_id
WHERE a.tgl_pengajuan>='".$tgl_awal."' and a.tgl_pengajuan<='".$tgl_akhir."'
ORDER BY a.tgl_pengajuan,c.nama";
        $ga=DB::connection()->select($sqlga);

        $sqldepartemen="SELECT * FROM m_departemen WHERE active=1 ORDER BY nama";
        $departemen=DB::connection()->select($sqldepartemen);

        return view('general_affair.list_ga', compact('ga','tgl_awal','tgl_akhir','pengajuan','departemen','unitkerja'));
    }

    public function cari_list_ga(Request $request){
        if($request->get('Cari')=='Cari'){
            $tgl_awal=date('Y-m-d',strtotime($request->get('tanggal_awal')));
            $tgl_akhir=date('Y-m-d',strtotime($request->get('tanggal_akhir')));
            $unitkerja=$request->get('departemen');

            if($unitkerja=='Pilih'){
                $sqlunitkerja= " ";
            }
            else{
                $sqlunitkerja= "and a.m_departemen_id=".$unitkerja."";
            }

            $sqlga="SELECT c.nama,d.nama as unit_kerja,
case when pengajuan=1 then 'ATK BULANAN'
when pengajuan=2 then 'ASSET'
when pengajuan=3 then 'TOOLS MARKETING' end as sifat,
a.tgl_pengajuan, b.nama_barang,b.qty,b.deskripsi,b.link,b.tgl_digunakan
FROM requisition_ga a
LEFT JOIN requisition_detil_ga b on b.req_ga_id=a.req_ga_id
LEFT JOIN p_karyawan c on c.p_karyawan_id=a.p_karyawan_id
LEFT JOIN m_departemen d on d.m_departemen_id=a.m_departemen_id
WHERE a.tgl_pengajuan>='".$tgl_awal."' and a.tgl_pengajuan<='".$tgl_akhir."' ".$sqlunitkerja."
ORDER BY a.tgl_pengajuan,c.nama";
            //echo $sqlga;die;
            $ga=DB::connection()->select($sqlga);

            $sqldepartemen="SELECT * FROM m_departemen WHERE active=1 ORDER BY nama";
            $departemen=DB::connection()->select($sqldepartemen);

            return view('general_affair.list_ga', compact('ga','tgl_awal','tgl_akhir','departemen','unitkerja'));
        }
        else if($request->get('Cari')=='Excel'){
            $tgl_awal=date('Y-m-d',strtotime($request->get('tanggal_awal')));
            $tgl_akhir=date('Y-m-d',strtotime($request->get('tanggal_akhir')));
            $unitkerja=$request->get('departemen');

            if($unitkerja=='Pilih'){
                $sqlunitkerja= " ";
            }
            else{
                $sqlunitkerja= "and a.m_departemen_id=".$unitkerja."";
            }

            $nama_file = 'List General Affair - '.$tgl_awal.'-'.$tgl_akhir;
            $sqlga="SELECT c.nama,d.nama as unit_kerja,
case when pengajuan=1 then 'ATK BULANAN'
when pengajuan=2 then 'ASSET'
when pengajuan=3 then 'TOOLS MARKETING' end as sifat,
a.tgl_pengajuan, b.nama_barang,b.qty,b.deskripsi,b.link,b.tgl_digunakan
FROM requisition_ga a
LEFT JOIN requisition_detil_ga b on b.req_ga_id=a.req_ga_id
LEFT JOIN p_karyawan c on c.p_karyawan_id=a.p_karyawan_id
LEFT JOIN m_departemen d on d.m_departemen_id=a.m_departemen_id
WHERE a.tgl_pengajuan>='".$tgl_awal."' and a.tgl_pengajuan<='".$tgl_akhir."' ".$sqlunitkerja."
ORDER BY a.tgl_pengajuan,c.nama";
            //echo $sqlga;die;
            $ga=DB::connection()->select($sqlga);
            $param['ga'] = $ga;
            return Excel::download(new list_ga_print_xls($param), $nama_file. '.xlsx');
        }
    }

    public function ga_pinjaman()
    {
        $Bulan=date('m');
        $Tahun2=date('y');

        $sqlnopengajuan="SELECT COALESCE(MAX(substr(requisition_ga_pinjaman.no_pengajuan, 13 , 5)::NUMERIC),10000)+1 AS counter
                        FROM requisition_ga_pinjaman
                        WHERE substr(requisition_ga_pinjaman.no_pengajuan, 7 , 2) ILIKE '$Bulan'
                        AND substr(requisition_ga_pinjaman.no_pengajuan, 10 , 2) ILIKE '$Tahun2' ";
        //echo $sqlnopengajuan;die;
        $nopengajuan=DB::connection()->select($sqlnopengajuan);
        $Counter=$nopengajuan[0]->counter;
        $NoDokumen='GAPIN.'.$Bulan.'.'.$Tahun2.'.'.$Counter;

        $sqlkaryawan="SELECT * from hrm.p_karyawan where active =1 ORDER by nama";
        $karyawan=DB::connection()->select($sqlkaryawan);

        $sqldepartemen="SELECT * from hrm.m_departemen where active =1 ORDER by nama";
        $departemen=DB::connection()->select($sqldepartemen);
        return view('general_affair.ga_pinjaman', compact('karyawan','departemen','NoDokumen'));
    }

    public function simpan_ga_pinjaman(Request $request){
        //echo 'masuk';die;
        $sqlid="SELECT COALESCE(MAX(req_ga_pinjaman_id),0)+1 AS counter FROM requisition_ga_pinjaman";
        $dataid=DB::connection()->select($sqlid);
        $id=$dataid[0]->counter;
        DB::connection()->table("requisition_ga_pinjaman")
            ->insert([
                "req_ga_pinjaman_id"=>($id),
                "p_karyawan_id"=>($request->get("pengaju")),
                "no_pengajuan"=>($request->get("no_pengajuan")),
                "m_departemen_id"=>($request->get("unit_kerja")),
                "tgl_pengajuan"=>date('Y-m-d',strtotime($request->get("tanggal"))),
                "created_at" => date("Y-m-d H:i:s"),
                "created_by" => ($request->get("pengaju"))
            ]);

        $rules = array(
            'nama.*' => 'required',
            'peruntukan.*' => 'required',
            'lama_pinjam.*' => 'required',
            'tgl_digunakan.*' => 'required',
            'tgl_dikembalikan.*' => 'required',
        );
        $error = Validator::make($request->all(), $rules);
        if ($error->fails()) {
            return response()->json([
                'error' => $error->errors()->all()
            ]);
        }

        $nama = $request->nama;
        $peruntukan = $request->peruntukan;
        $lama_pinjam = $request->lama_pinjam;
        $tgl_digunakan = $request->tgl_digunakan;
        $tgl_dikembalikan = $request->tgl_dikembalikan;
        for ($count = 0; $count < count($nama); $count++) {
            $data = array(
                'req_ga_pinjaman_id' => $id,
                'nama_barang' => $nama[$count],
                'peruntukan' => $peruntukan[$count],
                'lama_pinjam' => $lama_pinjam[$count],
                'tgl_digunakan' => $tgl_digunakan[$count],
                'tgl_dikembalikan' => $tgl_dikembalikan[$count],
            );
            $insert_data[] = $data;
        }

        GADetilPinjaman::insert($insert_data);
        /*return response()->json([
            'success' => 'Data Added successfully.'
        ]);*/

        return redirect()->back()->with('success',' Pengajuan Pinjaman Berhasil Di Ajukan!');
    }

    public function ga_perdin()
    {
        $Bulan=date('m');
        $Tahun2=date('y');

        $sqlnopengajuan="SELECT COALESCE(MAX(substr(requisition_ga_perdin.no_pengajuan, 13 , 5)::NUMERIC),10000)+1 AS counter
                        FROM requisition_ga_perdin
                        WHERE substr(requisition_ga_perdin.no_pengajuan, 7 , 2) ILIKE '$Bulan'
                        AND substr(requisition_ga_perdin.no_pengajuan, 10 , 2) ILIKE '$Tahun2' ";
        //echo $sqlnopengajuan;die;
        $nopengajuan=DB::connection()->select($sqlnopengajuan);
        $Counter=$nopengajuan[0]->counter;
        $NoDokumen='PERDIN.'.$Bulan.'.'.$Tahun2.'.'.$Counter;

        $sqldepartemen="SELECT * from hrm.m_departemen where active =1 ORDER by nama";
        $departemen=DB::connection()->select($sqldepartemen);

        $sqllokasi="SELECT * from hrm.m_lokasi where active =1 ORDER by nama";
        $lokasi=DB::connection()->select($sqllokasi);

        $sqlkaryawan="SELECT * from hrm.p_karyawan where active =1 ORDER by nama";
        $karyawan=DB::connection()->select($sqlkaryawan);

        $sqlpengikut="SELECT * from hrm.p_karyawan where active =1 ORDER by nama";
        $pengikut=DB::connection()->select($sqlpengikut);

        $sqljabatan="SELECT * from hrm.m_jabatan where active =1 ORDER by nama";
        $jabatan=DB::connection()->select($sqljabatan);

        return view('general_affair.ga_perdin',compact('NoDokumen','lokasi','departemen','karyawan','jabatan','pengikut'));
    }
}

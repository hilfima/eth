<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\Setting;
use DB;
use Mail;

class ApprPermitController extends Controller
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
    public function email_approve_cuti($kode){
        //echo $kode;die;
        //$idUser=Auth::user()->id;
        $sqldata="SELECT a.*,b.nik,b.nama_lengkap,b.jabatan,b.departemen,c.kode as kdpermit,c.nama as nama_ijin,d.nama as nama_appr, case when status_appr_1=1 then 'Disetujui' when status_appr_1=2 then 'Ditolak' end as sts_pengajuan ,coalesce(b.email_perusahaan,'mankewink@gmail.com') as email_perusahaan
FROM t_permit a
left join get_data_karyawan() b on b.p_karyawan_id=a.p_karyawan_id
left join m_jenis_ijin c on c.m_jenis_ijin_id=a.m_jenis_ijin_id
left join p_karyawan d on d.p_karyawan_id=a.appr_1
WHERE 1=1 and a.kode='$kode' and a.active=1 ORDER BY a.tgl_awal desc ";
        $data=DB::connection()->select($sqldata);

        DB::connection()->table("t_permit")
            ->where("kode",$kode)
            ->update([
                "status_appr_1"=>1,
                "tgl_appr_1" => date("Y-m-d H:i:s"),
                "update_by" => $data[0]->appr_1,
                "update_date" => date("Y-m-d H:i:s"),
            ]);

        $appr=Setting::where(['nama'=>'email_hrd'])->first();
        $emailhrd=$appr->val1;

        if($data[0]->status_appr_1==1){
            Mail::send('email_bls_cuti', ['data' => $data,'emailhrd'=>$emailhrd], function ($mail) use ($data,$emailhrd) {
                $mail->from('ethicagroup1@gmail.com', 'Ethica');
                $mail->to($data[0]->email_perusahaan)->subject('Status Pengajuan');
                $mail->cc('rekapabsenethics@gmail.com')->subject('Status Pengajuan');
            });
        }
        return view('approved');
    }

    public function email_reject_cuti($kode){
        //$idUser=Auth::user()->id;
        $sqldata="SELECT a.*,b.nik,b.nama_lengkap,b.jabatan,b.departemen,c.kode as kdpermit,c.nama as nama_ijin,d.nama as nama_appr,tgl_appr_1,status_appr_1, case when status_appr_1=1 then 'Disetujui' when status_appr_1=2 then 'Ditolak' end as sts_pengajuan ,coalesce(b.email_perusahaan,'mankewink@gmail.com') as email_perusahaan 
FROM t_permit a
left join get_data_karyawan() b on b.p_karyawan_id=a.p_karyawan_id
left join m_jenis_ijin c on c.m_jenis_ijin_id=a.m_jenis_ijin_id
left join p_karyawan d on d.p_karyawan_id=a.appr_1
WHERE 1=1 and a.kode='$kode' and a.active=1  ORDER BY a.tgl_awal desc ";
        $data=DB::connection()->select($sqldata);

        DB::connection()->table("t_permit")
            ->where("kode",$kode)
            ->update([
                "status_appr_1"=>2,
                "tgl_appr_1" => date("Y-m-d H:i:s"),
                "update_by" => $data[0]->appr_1,
                "update_date" => date("Y-m-d H:i:s"),
            ]);

        $appr=Setting::where(['nama'=>'email_hrd'])->first();
        $emailhrd=$appr->val1;

        Mail::send('email_bls_cuti', ['data' => $data,'emailhrd'=>$emailhrd], function ($mail) use ($data,$emailhrd) {
            $mail->from('ethicagroup1@gmail.com', 'Ethica');
            $mail->to($data[0]->email_perusahaan)->subject('Status Pengajuan');
            $mail->cc('rekapabsenethics@gmail.com')->subject('Status Pengajuan');
        });

        return view('rejected');
    }
public function email_approve_perdin($kode){
        //echo $id.'-'.$approve;die;
        //$idUser=Auth::user()->id;
        $sqldata="SELECT a.*,b.nik,b.nama_lengkap,b.jabatan,b.departemen,c.kode as kdpermit,c.nama as nama_ijin,d.nama as nama_appr,tgl_appr_1,status_appr_1 , case when status_appr_1=1 then 'Disetujui' when status_appr_1=2 then 'Ditolak' end as sts_pengajuan ,coalesce(b.email_perusahaan,'mankewink@gmail.com') as email_perusahaan
FROM t_permit a
left join get_data_karyawan() b on b.p_karyawan_id=a.p_karyawan_id
left join m_jenis_ijin c on c.m_jenis_ijin_id=a.m_jenis_ijin_id
left join p_karyawan d on d.p_karyawan_id=a.appr_1
WHERE 1=1 and a.kode='$kode' and a.active=1 ORDER BY a.tgl_awal desc ";
        $data=DB::connection()->select($sqldata);

        DB::connection()->table("t_permit")
            ->where("kode",$kode)
            ->update([
                "status_appr_1"=>1,
                "tgl_appr_1" => date("Y-m-d H:i:s"),
                "update_by" => $data[0]->appr_1,
                "update_date" => date("Y-m-d H:i:s"),
            ]);

        $appr=Setting::where(['nama'=>'email_hrd'])->first();
        $emailhrd=$appr->val1;

        Mail::send('email_bls_cuti', ['data' => $data,'emailhrd'=>$emailhrd], function ($mail) use ($data,$emailhrd) {
            $mail->from('ethicagroup1@gmail.com', 'Ethica');
            $mail->to($data[0]->email_perusahaan)->subject('Status Pengajuan');
            $mail->cc('rekapabsenethics@gmail.com')->subject('Status Pengajuan');
        });

        return view('approved');
    }

    public function email_reject_perdin($kode){
        //$idUser=Auth::user()->id;
        $sqldata="SELECT a.*,b.nik,b.nama_lengkap,b.jabatan,b.departemen,c.kode as kdpermit,c.nama as nama_ijin,d.nama as nama_appr,tgl_appr_1,status_appr_1 , case when status_appr_1=1 then 'Disetujui' when status_appr_1=2 then 'Ditolak' end as sts_pengajuan ,coalesce(b.email_perusahaan,'mankewink@gmail.com') as email_perusahaan
FROM t_permit a
left join get_data_karyawan() b on b.p_karyawan_id=a.p_karyawan_id
left join m_jenis_ijin c on c.m_jenis_ijin_id=a.m_jenis_ijin_id
left join p_karyawan d on d.p_karyawan_id=a.appr_1
WHERE 1=1 and a.kode='$kode' and a.active=1 ORDER BY a.tgl_awal desc ";
        $data=DB::connection()->select($sqldata);

        DB::connection()->table("t_permit")
            ->where("kode",$kode)
            ->update([
                "status_appr_1"=>2,
                "tgl_appr_1" => date("Y-m-d H:i:s"),
                "update_by" => $data[0]->appr_1,
                "update_date" => date("Y-m-d H:i:s"),
            ]);

        $appr=Setting::where(['nama'=>'email_hrd'])->first();
        $emailhrd=$appr->val1;

        Mail::send('email_bls_cuti', ['data' => $data,'emailhrd'=>$emailhrd], function ($mail) use ($data,$emailhrd) {
            $mail->from('ethicagroup1@gmail.com', 'Ethica');
            $mail->to($data[0]->email_perusahaan)->subject('Status Pengajuan');
            $mail->cc('rekapabsenethics@gmail.com')->subject('Status Pengajuan');
        });

        return view('rejected');
    }

    public function email_approve_izin($kode){
        //echo $id.'-'.$approve;die;
        //$idUser=Auth::user()->id;
        $sqldata="SELECT a.*,b.nik,b.nama_lengkap,b.jabatan,b.departemen,c.kode as kdpermit,c.nama as nama_ijin,d.nama as nama_appr,tgl_appr_1,status_appr_1 , case when status_appr_1=1 then 'Disetujui' when status_appr_1=2 then 'Ditolak' end as sts_pengajuan ,coalesce(b.email_perusahaan,'mankewink@gmail.com') as email_perusahaan
FROM t_permit a
left join get_data_karyawan() b on b.p_karyawan_id=a.p_karyawan_id
left join m_jenis_ijin c on c.m_jenis_ijin_id=a.m_jenis_ijin_id
left join p_karyawan d on d.p_karyawan_id=a.appr_1
WHERE 1=1 and a.kode='$kode' and a.active=1 ORDER BY a.tgl_awal desc ";
        $data=DB::connection()->select($sqldata);

        DB::connection()->table("t_permit")
            ->where("kode",$kode)
            ->update([
                "status_appr_1"=>1,
                "tgl_appr_1" => date("Y-m-d H:i:s"),
                "update_by" => $data[0]->appr_1,
                "update_date" => date("Y-m-d H:i:s"),
            ]);

        $appr=Setting::where(['nama'=>'email_hrd'])->first();
        $emailhrd=$appr->val1;

        Mail::send('email_bls_cuti', ['data' => $data,'emailhrd'=>$emailhrd], function ($mail) use ($data,$emailhrd) {
            $mail->from('ethicagroup1@gmail.com', 'Ethica');
            $mail->to($data[0]->email_perusahaan)->subject('Status Pengajuan');
            $mail->cc('rekapabsenethics@gmail.com')->subject('Status Pengajuan');
        });

        return view('approved');
    }

    public function email_reject_izin($kode){
        //$idUser=Auth::user()->id;
        $sqldata="SELECT a.*,b.nik,b.nama_lengkap,b.jabatan,b.departemen,c.kode as kdpermit,c.nama as nama_ijin,d.nama as nama_appr,tgl_appr_1,status_appr_1 , case when status_appr_1=1 then 'Disetujui' when status_appr_1=2 then 'Ditolak' end as sts_pengajuan ,coalesce(b.email_perusahaan,'mankewink@gmail.com') as email_perusahaan
FROM t_permit a
left join get_data_karyawan() b on b.p_karyawan_id=a.p_karyawan_id
left join m_jenis_ijin c on c.m_jenis_ijin_id=a.m_jenis_ijin_id
left join p_karyawan d on d.p_karyawan_id=a.appr_1
WHERE 1=1 and a.kode='$kode' and a.active=1 ORDER BY a.tgl_awal desc ";
        $data=DB::connection()->select($sqldata);

        DB::connection()->table("t_permit")
            ->where("kode",$kode)
            ->update([
                "status_appr_1"=>2,
                "tgl_appr_1" => date("Y-m-d H:i:s"),
                "update_by" => $data[0]->appr_1,
                "update_date" => date("Y-m-d H:i:s"),
            ]);

        $appr=Setting::where(['nama'=>'email_hrd'])->first();
        $emailhrd=$appr->val1;

        Mail::send('email_bls_cuti', ['data' => $data,'emailhrd'=>$emailhrd], function ($mail) use ($data,$emailhrd) {
            $mail->from('ethicagroup1@gmail.com', 'Ethica');
            $mail->to($data[0]->email_perusahaan)->subject('Status Pengajuan');
            $mail->cc('rekapabsenethics@gmail.com')->subject('Status Pengajuan');
        });

        return view('rejected');
    }

    public function email_approve_lembur($kode){
        //echo $id.'-'.$approve;die;
        //$idUser=Auth::user()->id;
        $sqldata="SELECT a.*,b.nik,b.nama_lengkap,b.jabatan,b.departemen,c.kode as kdpermit,c.nama as nama_ijin,d.nama as nama_appr,tgl_appr_1,status_appr_1 , case when status_appr_1=1 then 'Disetujui' when status_appr_1=2 then 'Ditolak' end as sts_pengajuan ,coalesce(b.email_perusahaan,'mankewink@gmail.com') as email_perusahaan
FROM t_permit a
left join get_data_karyawan() b on b.p_karyawan_id=a.p_karyawan_id
left join m_jenis_ijin c on c.m_jenis_ijin_id=a.m_jenis_ijin_id
left join p_karyawan d on d.p_karyawan_id=a.appr_1
WHERE 1=1 and a.kode='$kode' and a.active=1 ORDER BY a.tgl_awal desc ";
        $data=DB::connection()->select($sqldata);

        DB::connection()->table("t_permit")
            ->where("kode",$kode)
            ->update([
                "status_appr_1"=>1,
                "tgl_appr_1" => date("Y-m-d H:i:s"),
                "update_by" => $data[0]->appr_1,
                "update_date" => date("Y-m-d H:i:s"),
            ]);

        $appr=Setting::where(['nama'=>'email_hrd'])->first();
        $emailhrd=$appr->val1;

        Mail::send('email_bls_lembur', ['data' => $data,'emailhrd'=>$emailhrd], function ($mail) use ($data,$emailhrd) {
            $mail->from('ethicagroup1@gmail.com', 'Ethica');
            $mail->to($data[0]->email_perusahaan)->subject('Status Pengajuan');
            $mail->cc('rekapabsenethics@gmail.com')->subject('Status Pengajuan');
        });

        return view('approved_lembur');
    }

    public function email_reject_lembur($kode){
        //$idUser=Auth::user()->id;
        $sqldata="SELECT a.*,b.nik,b.nama_lengkap,b.jabatan,b.departemen,c.kode as kdpermit,c.nama as nama_ijin,d.nama as nama_appr,tgl_appr_1,status_appr_1 , case when status_appr_1=1 then 'Disetujui' when status_appr_1=2 then 'Ditolak' end as sts_pengajuan ,coalesce(b.email_perusahaan,'mankewink@gmail.com') as email_perusahaan
FROM t_permit a
left join get_data_karyawan() b on b.p_karyawan_id=a.p_karyawan_id
left join m_jenis_ijin c on c.m_jenis_ijin_id=a.m_jenis_ijin_id
left join p_karyawan d on d.p_karyawan_id=a.appr_1
WHERE 1=1 and a.kode='$kode' and a.active=1 ORDER BY a.tgl_awal desc ";
        $data=DB::connection()->select($sqldata);

        DB::connection()->table("t_permit")
            ->where("kode",$kode)
            ->update([
                "status_appr_1"=>2,
                "tgl_appr_1" => date("Y-m-d H:i:s"),
                "update_by" => $data[0]->appr_1,
                "update_date" => date("Y-m-d H:i:s"),
            ]);

        $appr=Setting::where(['nama'=>'email_hrd'])->first();
        $emailhrd=$appr->val1;

        Mail::send('email_bls_lembur', ['data' => $data,'emailhrd'=>$emailhrd], function ($mail) use ($data,$emailhrd) {
            $mail->from('ethicagroup1@gmail.com', 'Ethica');
            $mail->to($data[0]->email_perusahaan)->subject('Status Pengajuan');
            $mail->cc('rekapabsenethics@gmail.com')->subject('Status Pengajuan');
        });

        return view('rejected_lembur');
    }
}

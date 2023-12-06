<?php

namespace App\Http\Controllers\Backend;

use App\ajuan_xls;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\Setting;
use App\Helper_function;
use DB;
use Maatwebsite\Excel\Excel;
use Mail;
use Response;

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

    public function list_ajuan(Request $request)
    {
    	$type='Direksi';
        $sqldata="SELECT a.*,b.nik,b.nama,a.m_jenis_ijin_id,c.kode,c.nama as nama_ijin,d.nama as nama_appr,e.nama as nama_appr2,tgl_appr_1,status_appr_1,status_appr_2 FROM t_permit a 
left join p_karyawan b on b.p_karyawan_id=a.p_karyawan_id
left join m_jenis_ijin c on c.m_jenis_ijin_id=a.m_jenis_ijin_id
left join p_karyawan d on d.p_karyawan_id=a.appr_1
left join p_karyawan e on e.p_karyawan_id=a.appr_2
WHERE 1=1 and a.active=1 and a.appr_1=-1  ORDER BY a.create_date desc";
        $data=DB::connection()->select($sqldata);

        $iduser=Auth::user()->id;
        $sqluser="SELECT p_recruitment.foto FROM users
left join p_karyawan on p_karyawan.user_id=users.id
left join p_recruitment on p_recruitment.p_recruitment_id=p_karyawan.p_recruitment_id
where users.id=$iduser";
        $user=DB::connection()->select($sqluser);
		$help  =new Helper_function();
        return view('backend.permit.list',compact('data','user','type','help','request'));
    }
	public function list_ajuan2(Request $request,$type)
    {
    	if($type=='cuti')
    		$sqlin= ' and c.tipe =3';
    	else if($type=='ijin')
    		$sqlin= ' and c.tipe =1';
    	else if($type=='lembur')
    		$sqlin= ' and c.tipe =2';
    	else if($type=='perdin')
    		$sqlin= ' and c.tipe =4';
        $sqldata="SELECT a.*,b.nik,b.nama,c.kode,c.nama as nama_ijin,d.nama as nama_appr,e.nama as nama_appr2,tgl_appr_1,status_appr_1,status_appr_2 FROM t_permit a 
left join p_karyawan b on b.p_karyawan_id=a.p_karyawan_id
left join m_jenis_ijin c on c.m_jenis_ijin_id=a.m_jenis_ijin_id
left join p_karyawan d on d.p_karyawan_id=a.appr_1
left join p_karyawan e on e.p_karyawan_id=a.appr_2
WHERE 1=1 and a.active=1 $sqlin ORDER BY a.create_date desc";
        $data=DB::connection()->select($sqldata);

        $iduser=Auth::user()->id;
        $sqluser="SELECT p_recruitment.foto FROM users
left join p_karyawan on p_karyawan.user_id=users.id
left join p_recruitment on p_recruitment.p_recruitment_id=p_karyawan.p_recruitment_id
where users.id=$iduser";
        $user=DB::connection()->select($sqluser);
		$help  =new Helper_function();
        return view('backend.permit.list',compact('data','user','type','help','request'));
    }

    public function lihat($kode)
    {
    	$help = new Helper_function();
        $sqldata="SELECT a.*,alasan_idt_ipm,b.nik,b.nama_lengkap,c.kode,c.nama as nama_ijin,d.nama as nama_appr,tgl_appr_1,status_appr_1,b.pangkat,b.departemen,case when status_appr_1=1 then 'Disetujui' when status_appr_1=2 then 'Ditolak' end as sts_pengajuan,b.jabatan,f.uang_makan,f.uang_saku,f.uang_saku2, h.alasan as alasan_idt_ipm
FROM t_permit a
left join get_data_karyawan() b on b.p_karyawan_id=a.p_karyawan_id
left join m_jenis_ijin c on c.m_jenis_ijin_id=a.m_jenis_ijin_id
left join p_karyawan d on d.p_karyawan_id=a.appr_1
left join m_pangkat f on b.m_pangkat_id=f.m_pangkat_id
left join m_jenis_alasan h on h.m_jenis_alasan_id=a.m_jenis_alasan_id
WHERE 1=1 and a.t_form_exit_id=$kode and a.active=1  ORDER BY a.tgl_awal desc";
        $data=DB::connection()->select($sqldata);


        $iduser=Auth::user()->id;
        $sqluser="SELECT p_recruitment.foto FROM users
left join p_karyawan on p_karyawan.user_id=users.id
left join p_recruitment on p_recruitment.p_recruitment_id=p_karyawan.p_recruitment_id
where users.id=$iduser";
        $user=DB::connection()->select($sqluser);
		$type = $_GET['type'];
		if($type=='perdin'){
			
			$uangmakan=$data[0]->uang_makan;
			$uangsaku1=$data[0]->uang_saku;
			$uangsaku2=$data[0]->uang_saku2;
		}else{
			
			$uangmakan=0;
			$uangsaku1=0;
			$uangsaku2=0;
		}
        return view('backend.permit.lihat',compact('data','kode','user','type','uangmakan','uangsaku1','uangsaku2','help'));
    }public function edit($kode)
    {
    	$help = new Helper_function();
        $sqldata="SELECT a.*,b.nik,b.nama_lengkap,c.kode,c.nama as nama_ijin,d.nama as nama_appr
        ,tgl_appr_1,status_appr_1,b.pangkat,b.departemen,case when status_appr_1=1 then 'Disetujui' when status_appr_1=2 then 'Ditolak' end as sts_pengajuan
        ,b.jabatan,f.uang_makan,f.uang_saku,f.uang_saku2,c.m_jenis_ijin_id
FROM t_permit a
left join get_data_karyawan() b on b.p_karyawan_id=a.p_karyawan_id
left join m_jenis_ijin c on c.m_jenis_ijin_id=a.m_jenis_ijin_id
left join p_karyawan d on d.p_karyawan_id=a.appr_1
left join m_pangkat f on b.m_pangkat_id=f.m_pangkat_id
WHERE 1=1 and a.t_form_exit_id=$kode and a.active=1  ORDER BY a.tgl_awal desc";
        $data=DB::connection()->select($sqldata);


        $iduser=Auth::user()->id;
        $sqluser="SELECT p_recruitment.foto FROM users
left join p_karyawan on p_karyawan.user_id=users.id
left join p_recruitment on p_recruitment.p_recruitment_id=p_karyawan.p_recruitment_id
where users.id=$iduser";
        $user=DB::connection()->select($sqluser);
        $jenis_ijin=DB::connection()->select("select * from m_jenis_ijin where active=1");
		$type = $_GET['type'];
		if($type=='perdin'){
			
			$uangmakan=$data[0]->uang_makan;
			$uangsaku1=$data[0]->uang_saku;
			$uangsaku2=$data[0]->uang_saku2;
		}else{
			
			$uangmakan=0;
			$uangsaku1=0;
			$uangsaku2=0;
		}
        return view('backend.permit.edit',compact('data','kode','user','type','uangmakan','uangsaku1','uangsaku2','help','jenis_ijin'));
    }

    public function pengajuan($type)
    {
        $sqldata="SELECT a.*,b.nik,b.nama_lengkap,c.kode,c.nama as nama_ijin,d.nama as nama_appr,tgl_appr_1,status_appr_1,b.pangkat,b.departemen,case when status_appr_1=1 then 'Disetujui' when status_appr_1=2 then 'Ditolak' end as sts_pengajuan,b.jabatan
FROM t_permit a
left join get_data_karyawan() b on b.p_karyawan_id=a.p_karyawan_id
left join m_jenis_ijin c on c.m_jenis_ijin_id=a.m_jenis_ijin_id
left join p_karyawan d on d.p_karyawan_id=a.appr_1
WHERE 1=1 and a.active=1  ORDER BY a.tgl_awal desc";
        $data=DB::connection()->select($sqldata);

        $iduser=Auth::user()->id;
        $sqluser="SELECT p_recruitment.foto FROM users
left join p_karyawan on p_karyawan.user_id=users.id
left join p_recruitment on p_recruitment.p_recruitment_id=p_karyawan.p_recruitment_id
where users.id=$iduser";
        $user=DB::connection()->select($sqluser);

		if($type=='cuti')
    		$sqlin= ' and m_jenis_ijin.tipe =3';
    	else if($type=='ijin')
    		$sqlin= ' and m_jenis_ijin.tipe =1';
    	else if($type=='lembur')
    		$sqlin= ' and m_jenis_ijin.tipe =2';
    	else if($type=='perdin')
    		$sqlin= ' and m_jenis_ijin.tipe =4';
    		
        $sqljenisizin="SELECT * from m_jenis_ijin WHERE 1=1 $sqlin order by nama";
        $jeniscuti=DB::connection()->select($sqljenisizin);

        $sqlappr="SELECT * from get_data_karyawan() WHERE m_pangkat_id not in(1,2) ORDER BY nama_lengkap";
        $appr=DB::connection()->select($sqlappr);

        $sqlkaryawan="SELECT * from get_data_karyawan() WHERE 1=1 ORDER BY nama_lengkap";
        $karyawan=DB::connection()->select($sqlkaryawan);

        return view('backend.permit.tambah_ajuan',compact('data','type','user','jeniscuti','appr','karyawan'));
    }
    public function historis_absen (Request $request)
    {
         $historis_log = DB::connection()->select("select * from absen_historis_log a left join m_mesin_absen on a.mesin_id = m_mesin_absen.mesin_id where tanggal='$request->tgl' order by create_date");
    	echo '<table class="table">
                    <tr>
                        <th>No</th>
                        <th>Waktu</th>
                        <th>Mesin</th>
                        <th>Method</th>
                        <th>Log</th>
                    </tr>';
                    $no = 0;
                    foreach($historis_log as $log){
                    $no++;
                    ?>
                        <tr>
                            <td><?=$no;?></td>
                            <td><?=$log->create_date;?></td>
                            <td><?=$log->nama;?></td>
                            <td><?=$log->input_by;?></td>
                            <td><pre><?php $log_array = (json_decode(str_replace('\"','"',str_replace("\'","'",($log->log))),true));
                            if(isset($log_array[0])){
                            for($i=0;$i<count($log_array);$i++){
                                echo $log_array[$i];
                                echo '<br>';
                            }
                            }
                            
                            ?></pre></td>
                        </tr>
                    <?php }?>
                </table>
            <?php
    }
    public function tarik_absen ()
    {
        $historis_log = DB::connection()->select("select * from absen_historis_log a left join m_mesin_absen on a.mesin_id = m_mesin_absen.mesin_id where tanggal='".date('Y-m-d')."' order by create_date");
    	return view('backend.permit.tarik_absen',compact("historis_log"));
        
    }
    public function delete_pengajuan(Request $request,$kode)
    {
		
		DB::beginTransaction();
        try{
			$iduser=Auth::user()->id;
			DB::connection()->table("t_permit")
                ->where("t_form_exit_id",$kode)
				->update([
					"active"=>0,
					"update_date"=>date("Y-m-d H:i:s"),
					"update_by"=>$iduser
				]);
				
		 DB::commit();

            return redirect()->route('be.ajuan','tgl_awal='.$request->tgl_awal.'&tgl_akhir='.$request->tgl_akhir.'&nama='.$request->nama.'&Cari=Cari')->with('success','Pengajuan Berhasil di simpan!');
        }
        catch(\Exeception $e){
            DB::rollback();
            return redirect()->back()->with('error',$e);
        }
	}
 	public function simpan_perubahan_permit(Request $request,$kode)
    {
		
		DB::beginTransaction();
        try{
			$iduser=Auth::user()->id;
			DB::connection()->table("t_permit")
                ->where("t_form_exit_id",$kode)
				->update([
					"jam_awal"=>$request->jam_awal,
					"jam_akhir"=>$request->jam_akhir,
					"tgl_appr_1"=>$request->tgl_appr_1,
					"tgl_appr_2"=>$request->tgl_appr_2,
					"lama"=>$request->lama,
					"update_date"=>date("Y-m-d H:i:s"),
					"update_by"=>$iduser
				]);
				
		 DB::commit();

            return redirect()->route('be.cari_ajuan','nama='.$request->p_karyawan_id.'&Cari=Cari')->with('success','Pengajuan Berhasil di simpan!');
        }
        catch(\Exeception $e){
            DB::rollback();
            return redirect()->back()->with('error',$e);
        }
	}
 	public function update_pengajuan_permit(Request $request,$kode)
    {
		
		DB::beginTransaction();
        try{
			$iduser=Auth::user()->id;
			DB::connection()->table("t_permit")
                ->where("t_form_exit_id",$kode)
				->update([
					"tgl_appr_1"=>date("Y-m-d H:i:s"),
					"m_jenis_ijin_id"=>$request->jenis_izin,
					"m_jenis_ijin_before_id"=>$request->jenis_ijin_val,
					"update_date"=>date("Y-m-d H:i:s"),
					"update_by"=>$iduser
				]);
				
		 DB::commit();

            return redirect()->route('be.cari_ajuan','nama='.$request->p_karyawan_id.'&Cari=Cari')->with('success','Pengajuan Berhasil di simpan!');
        }
        catch(\Exeception $e){
            DB::rollback();
            return redirect()->back()->with('error',$e);
        }
	}
 
    
	
 
    public function simpan_pengajuan(Request $request,$type)
    {
        DB::beginTransaction();
        try{
            $iduser=Auth::user()->id;
            $sqlidkar="select * from p_karyawan where user_id=$iduser";
            $idkar=DB::connection()->select($sqlidkar);
            $id=$idkar[0]->p_karyawan_id;

            $this->validate($request, [
                'nama' => 'required',
                'tgl_pengajuan' => 'required',
                'tgl_awal' => 'required',
                'tgl_akhir' => 'required',
                'jenis' => 'required',
                'alasan' => 'required',
                'atasan' => 'required',
                'lama' => 'required',
            ]);

            $data = $request->all();
            if(empty($request->input('jenis'))){
                $data['jenis'] = $request->input('jenis');
            }
            if(empty($request->input('tgl_pengajuan'))){
                $data['tgl_pengajuan'] = $request->input('tgl_pengajuan');
            }
            if(empty($request->input('tgl_awal'))){
                $data['tgl_awal'] = $request->input('tgl_awal');
            }
            if(empty($request->input('tgl_akhir'))){
                $data['tgl_akhir'] = $request->input('tgl_akhir');
            }
            if(empty($request->input('atasan'))){
                $data['atasan'] = $request->input('atasan');
            }
            if(empty($request->input('alasan'))){
                $data['alasan'] = $request->input('alasan');
            }
            if(empty($request->input('lama'))){
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
			$lama_input = $request->get('lama');
            DB::connection()->table("t_permit")
                ->insert([
                    "m_jenis_ijin_id"=>$request->get('jenis'),
					"p_karyawan_id"=>$request->get('nama'),
                    "tgl_awal"=>date('Y-m-d',strtotime($request->get('tgl_awal'))),
                    "tgl_akhir"=>date('Y-m-d',strtotime($request->get('tgl_akhir'))),
                    "appr_1"=>$request->get('atasan'),
                    "tgl_appr_1"=>date('Y-m-d'),
                    "lama"=>$lama_input,
                    "keterangan"=>$request->get('alasan'),
                    "create_date"=>date('Y-m-d  H:i:s'),
                    "kode"=>$nocuti,
                    "create_by"=>$id,
                    "active"=>1,
                ]);

            if($request->file('file')){//echo 'masuk';die;
                $file = $request->file('file');
                $destination="dist/img/file/";
                $file->move($destination,$file->getClientOriginalName());
                $path=$file->getClientOriginalName();
                //echo $path;die;
                DB::connection()->table("t_permit")->where("kode",$nocuti)
                    ->update([
                        "foto"=>$path
                    ]);
            }

            DB::commit();

            return redirect()->route('be.list_ajuan',$type)->with('success','Pengajuan Berhasil di simpan!');
        }
        catch(\Exeception $e){
            DB::rollback();
            return redirect()->back()->with('error',$e);
        }
    }

}

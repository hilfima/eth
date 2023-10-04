<?php

namespace App\Http\Controllers\Backend;

use App\ajuan_xls;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\Setting;
use DB;
use Maatwebsite\Excel\Excel;
use Mail;
use Response;
use App\Helper_function;

class HrApprController extends Controller{
	/**
	* Create a new controller instance.
	*
	* @return void
	*/
	public function __construct(){
		$this->middleware('auth');
	}

	/**
	* Show the application dashboard.
	*
	* @return \Illuminate\Contracts\Support\Renderable
	*/

	public function hr_appr(Request $request){
    	if($request->get('tgl_awal') and $request->get('tgl_akhir')){
    		$tgl_awal = $request->get('tgl_awal');
    		$tgl_akhir = $request->get('tgl_akhir');
    	}else{
    		$bulan = (date('m'));
    		if($bulan==1){
				$bulan = 12;    			
    			$tahun = date('Y')-1;
    		}
    		else{
				$bulan = date('m')-1;    			
    			$tahun = date('Y');
    		}
    		$tgl_awal = $tahun.'-'.$bulan.'-25';
    		$tgl_akhir = date('Y-m-d');
    	}
    	$where ='';
    	if($request->get('pengajuan')){
	    	$where .=' and a.m_jenis_ijin_id='.$request->get('pengajuan');
    	}
    	if($request->get('status')){
	    	$where .=' and status_appr_hr='.$request->get('status');
    	}if($request->get('p_karyawan_id')){
	    	$where .=' and a.p_karyawan_id='.$request->get('p_karyawan_id');
    	}
		$sqldata="SELECT a.*,b.nik,b.nama,c.kode,c.nama as nama_ijin,d.nama as nama_appr,e.nama as nama_appr2,tgl_appr_1,status_appr_1,status_appr_hr,status_after_hr FROM t_permit a
		left join p_karyawan b on b.p_karyawan_id=a.p_karyawan_id
		left join m_jenis_ijin c on c.m_jenis_ijin_id=a.m_jenis_ijin_id
		left join p_karyawan d on d.p_karyawan_id=a.appr_1
		left join p_karyawan e on e.p_karyawan_id=a.appr_2
		WHERE 1=1 and a.active=1 and 
		((
		a.m_jenis_ijin_id =22 and
		(case
	WHEN status_appr_1=1 and appr_2 is null THEN tgl_appr_1>='$tgl_awal' and  tgl_appr_1<='$tgl_akhir'
		WHEN status_appr_1=1 and status_appr_2=1 THEN tgl_appr_2>='$tgl_awal' and  tgl_appr_2<='$tgl_akhir'
		WHEN appr_1 is null and status_appr_2=1 THEN tgl_appr_2>='$tgl_awal' and  tgl_appr_2<='$tgl_akhir'
		end)
		
		) or (((tgl_awal>='$tgl_awal' and tgl_awal<='$tgl_akhir') or (tgl_akhir>='$tgl_awal' and tgl_akhir<='$tgl_akhir')) and a.m_jenis_ijin_id !=22)) $where ORDER BY a.status_appr_hr desc,a.tgl_awal desc,a.m_jenis_ijin_id";
		$data=DB::connection()->select($sqldata); 
		$list_izin=DB::connection()->select("select * from m_jenis_ijin where active=1"); 

		$iduser=Auth::user()->id;
		$sqluser="SELECT p_recruitment.foto FROM users
		left join p_karyawan on p_karyawan.user_id=users.id
		left join p_recruitment on p_recruitment.p_recruitment_id=p_karyawan.p_recruitment_id
		where users.id=$iduser";
		$user=DB::connection()->select($sqluser);
		$karyawan = DB::connection()->select("select * from p_karyawan where active=1 order by nama");
		return view('backend.approval_hr.list',compact('data','user','list_izin','request','tgl_awal','tgl_akhir','karyawan'));
	}

	public function daftarKaryawan($kode){
		$sql = "Select * from p_karyawan a join p_karyawan_pekerjaan b on a.p_karyawan_id = b.p_karyawan_id where m_lokasi_id = $kode and a.active=1";	
		$karyawan=DB::connection()->select($sql);
		$content ='';
		foreach($karyawan as $karyawan){
			$content .='<option value="'.$karyawan->p_karyawan_id.'">'.$karyawan->nama.'</option>';
		}
		$data['respon'] = $content;
		echo json_encode($data);
	}

	public function migrasi(){
		
		$sqldata="select * from t_permit where m_jenis_ijin_id = 20 ";
		$data=DB::connection()->select($sqldata);
		foreach($data as $data){
			if($data->tgl_awal < "2022-08-26" and $data->status_appr_2 == null){
				$data->status_appr_2 = 1;
			}
			DB::connection()->table("t_permit")
			->where("t_form_exit_id",$data->t_form_exit_id		)
			->update([
					"appr_hr"=>$data->appr_2,
					"tgl_appr_hr"=>$data->tgl_appr_2,
					"status_appr_hr"=>$data->status_appr_2
                   
                   
				]);
		}
	}

	public function lihat($kode){
		$sqldata="SELECT a.*,h.alasan as alasan_idt_ipm,b.nik,b.nama_lengkap,c.kode,c.nama as nama_ijin,d.nama as nama_appr,tgl_appr_1,status_appr_1,b.pangkat,b.departemen,case when status_appr_1=1 then 'Disetujui' when status_appr_1=2 then 'Ditolak' end as sts_pengajuan,b.jabatan,a.m_jenis_ijin_id,b.m_status_pekerjaan_id
		FROM t_permit a
		left join get_data_karyawan() b on b.p_karyawan_id=a.p_karyawan_id
		left join m_jenis_ijin c on c.m_jenis_ijin_id=a.m_jenis_ijin_id
		left join p_karyawan d on d.p_karyawan_id=a.appr_1
        left join m_jenis_alasan h on h.m_jenis_alasan_id=a.m_jenis_alasan_id
		WHERE 1=1 and a.t_form_exit_id=$kode and a.active=1  ORDER BY a.tgl_awal desc";
		$data=DB::connection()->select($sqldata);


		$iduser=Auth::user()->id;
		$sqluser="SELECT p_recruitment.foto FROM users
		left join p_karyawan on p_karyawan.user_id=users.id
		left join p_recruitment on p_recruitment.p_recruitment_id=p_karyawan.p_recruitment_id
		where users.id=$iduser";
		$user=DB::connection()->select($sqluser);
		$type = 'Izin';
		$sqljenisizin="SELECT * from m_jenis_ijin  order by urutan asc";
		$pengajuan=DB::connection()->select($sqljenisizin);
		$id = $data[0]->p_karyawan_id;
		$tahun = date('Y',strtotime($data[0]->tgl_awal));
		// print_r($cuti);die;
		$sqlcuti="SELECT * FROM get_list_cuti_tahunan_new($tahun,$id) ";
		$cuti=DB::connection()->select($sqlcuti);
		if($data[0]->m_status_pekerjaan_id==5)
		$tolcut = 0;
		else{
			if($cuti[0]->total_cuti_tahunan<1)
			$tolcut = $cuti[0]->total_cuti_tahunan;
			else
			$tolcut = $cuti[0]->sisa_cuti;
		}
		$sqlidkar="select *,p_karyawan.p_karyawan_id as karyawan_id from p_karyawan
		join p_karyawan_pekerjaan on p_karyawan_pekerjaan.p_karyawan_id = p_karyawan.p_karyawan_id
		join m_jabatan on p_karyawan_pekerjaan.m_jabatan_id = m_jabatan.m_jabatan_id
		where p_karyawan.p_karyawan_id=$id";
		$idkar=DB::connection()->select($sqlidkar);
		$help = new Helper_function();
		$cuti = $help->query_cuti2($idkar);
		$date2 = $cuti['date'];
		$all = $cuti['all'];
		$tanggal_loop = $cuti['tanggal_loop'];
		$no=0;$nominal=0;
		$tahun = array();
		$tahunbesar = array();
		$datasisa = array();
		$ipg = array();
		$potong_gaji = array();
		$hutang = 0;
		$jumlah = 0;
		foreach ($tanggal_loop as $i=> $loop) {
			if ($all[$i]['tanggal']<=date('Y-m-d')) {
				$return = $help->perhitungan_cuti2($all,$datasisa,$hutang,$date2,$i,$nominal,$jumlah,$ipg,$potong_gaji);
				$datasisa =$return['datasisa'];
				$hutang =$return['hutang'];
				$nominal =$return['nominal'];
				$jumlah =$return['jumlah'];
				$ipg =$return['ipg'];
				$potong_gaji =$return['potong_gaji'];
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
		return view('backend.approval_hr.lihat',compact('data','user','type','pengajuan','kode','tolcut'));
	}

	public function pengajuan($type){
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
 
	public function simpan_hr_appr(Request $request,$type){
		DB::beginTransaction();
		try{
			$iduser=Auth::user()->id;
			$sqlidkar="select * from p_karyawan where user_id=$iduser";
			$idkar=DB::connection()->select($sqlidkar);
			

            

			$data = $request->all();
           
			date_default_timezone_set('Asia/Jakarta');
			$time=date('Y-m-d H:i:s');

			$Bulan=date('m');
			$Tahun2=date('y');

           
		  $kode = $request->get('idform');
          $notifdata=DB::connection()->select("select * from t_permit left join m_jenis_ijin on m_jenis_ijin.m_jenis_ijin_id = t_permit.m_jenis_ijin_id where t_form_exit_id=$kode");
          	$array = [
					
					"m_jenis_ijin_id"=>"Jenis Ajuan",
					"lama"=>"Lama",
					"jam_awal"=>"Jam Awal",
					"jam_akhir"=>"Jam Akhir",
					"tgl_awal"=>"Tanggal Awal",
					"tgl_akhir"=>"Tanggal Akhir",
                   
                   
				];
			DB::connection()->table("t_permit")
			->where("t_form_exit_id",$request->get('idform')		)
			->update([
					"appr_hr"=>$iduser,
					"appr_hr"=>$iduser,
					"tgl_appr_hr"=>date('Y-m-d'),
					"status_appr_hr"=>$request->get('status_apprhr'),
					"keterangan_hr"=>$request->get('keteranganhr'),
					"m_jenis_ijin_id"=>$request->get('pengajuan'),
					"lama"=>$request->get('lama'),
					"jam_awal"=>$request->get('jam_awal'),
					"jam_akhir"=>$request->get('jam_akhir'),
					"tgl_awal"=>$request->get('tgl_awal'),
					"tgl_akhir"=>$request->get('tgl_akhir'),
					"status_after_hr"=>1,
                   
                   
				]);
			$notifdataperubahan=DB::connection()->select("select * from t_permit left join m_jenis_ijin on m_jenis_ijin.m_jenis_ijin_id = t_permit.m_jenis_ijin_id where t_form_exit_id=$kode");
			$string_perubahan ="Perubahan";
			foreach($notifdata as $temp_data){
				foreach($array as $key => $value){
				if($notifdataperubahan[0]->$key != $temp_data->$key){
					if($key=='m_jenis_ijin_id'){
						$awal=DB::connection()->select("select * from m_jenis_ijin where m_jenis_ijin_id=".$temp_data->$key);
						$akhir=DB::connection()->select("select * from m_jenis_ijin where m_jenis_ijin_id=".$notifdataperubahan[0]->$key);
					$string_perubahan .= "<br>".$value.' : '.$awal[0]->nama .'--> '.$akhir[0]->nama;
					}else
					$string_perubahan .= "<br>".$value.' : '.$temp_data->$key .'--> '.$notifdataperubahan[0]->$key;
				}
				}
			}
			
				
		
            DB::connection()->table("notifikasi")
                    ->insert([
                        "id_assign"=>$iduser,
                        "database_from"=>"t_permit",
                        "datebase_id"=>$kode,
                        "p_karyawan_id"=>$notifdata[0]->p_karyawan_id,
                        "date_action"=>date('Y-m-d H:i:s'),
                        "notif"=>"Pengajuan ".$notifdata[0]->nama." pada tanggal ".($notifdata[0]->tgl_awal!=$notifdata[0]->tgl_akhir?$notifdata[0]->tgl_awal.' s/d '.$notifdata[0]->tgl_akhir:$notifdata[0]->tgl_awal)." sudah di ".($request->get('status_apprhr')==1?'Disetujui':'Ditolak') ." (Approval HC)"."<br> Keterangan HR ".$request->get('keteranganhr')."<br> ".($string_perubahan!='Perubahan'?$string_perubahan:''),
             ]);

			DB::commit();

			return redirect()->route('be.hr_appr',$type)->with('success','Pengajuan Berhasil di simpan!');
		}
		catch(\Exeception $e){
			DB::rollback();
			return redirect()->back()->with('error',$e);
		}
	}

}

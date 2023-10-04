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
use App\Helper_function;
use Response;
use PhpOffice\PhpSpreadsheet\Reader\Xml\Style\Fill;

use App\Eloquent\AbsenLog;
use App\Eloquent\Absen;
use DateTime;;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PHPExcel;
use PHPExcel_IOFactory;

class ThrController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

    public function update_nominal($id_prl, $id, $nominal)
    {
        DB::beginTransaction();
        try {

            //print_r($appr);
            DB::connection()->table("prl_gaji_detail")
                ->where('prl_gaji_detail_id', $id)
                ->update([
                    "nominal" => $nominal,

                ]);


            DB::commit();
            return redirect()->route('be.previewgaji', ['non_ajuan', 'prl_generate=' . $id_prl . '&menu=Gaji&Cari=Cari'])->with('success', 'Approval Gaji Berhasil di Update!');
        } catch (\Exeception $e) {
            DB::rollback();
            return redirect()->back()->with('error', $e);
        }
    }
    public function hapus_approval($id, $id_prl)
    {
        DB::beginTransaction();
        try {
            $idUser = Auth::user()->id;
            $sql = "select * from prl_generate_appr where prl_generate_appr_id = '$id'";
            $appr = DB::connection()->select($sql);
            $pajak = $appr[0]->pajak;

            //print_r($appr);
            DB::connection()->table("prl_gaji")
                ->where('prl_generate_id', $id_prl)
                ->where('m_lokasi_id', $appr[0]->m_lokasi_id)
                ->update([
                    "appr_" . $pajak . "_direktur_status" => 0,
                    "appr_" . $pajak . "_direktur_keterangan" => 'Hapus Approval',
                    "appr_" . $pajak . "_direktur_by" => $idUser,
                    "appr_" . $pajak . "_direktur_date" => date("Y-m-d H:i:s"),

                ]);

            DB::connection()->table("prl_generate_appr")
                ->where('prl_generate_appr_id', $id)
                ->delete();
            DB::commit();
            return redirect()->route('be.previewgaji', ['non_ajuan', 'prl_generate=' . $id_prl . '&menu=Gaji&Cari=Approval'])->with('success', 'Approval Gaji Berhasil di Update!');
        } catch (\Exeception $e) {
            DB::rollback();
            return redirect()->back()->with('error', $e);
        }
    }
    public function simpan_appr_gaji($request)
    {
        DB::beginTransaction();
        try {
            $idUser = Auth::user()->id;
            $id = $request->get('prl_generate');
            $id_prl = $request->get('prl_generate');
            $help = new Helper_function();
            $entitas = ($request->get('entitas'));
            $pajakonoff= $request->get('pajakonoff');
            $list_pajak = array();
            if($pajakonoff==''){
            	$list_pajak[] = 'on';
            	$list_pajak[] = 'off';
            }else if($pajakonoff=='ON'){
            	$list_pajak[] = 'on';
            }else if($pajakonoff=='OFF'){
            	$list_pajak[] = 'off';
            }
            
           
            for($i=0;$i<count($list_pajak);$i++){
            	$pajak = $list_pajak[$i];
            DB::connection()->table("prl_gaji")
                ->where('prl_generate_id', $id)
                ->where('m_lokasi_id', $entitas)
                ->update([
                    "appr_" . $pajak . "_direktur_status" => (1),
                    "appr_" . $pajak . "_direktur_by" => $idUser,
                    "appr_" . $pajak . "_direktur_date" => date('Y-m-d H:i:s'),

                ]);
            $sql = "select (select count(*) from prl_gaji where prl_generate_id = $id_prl ) as count_lokasi,
					(select count(*) from prl_gaji where prl_generate_id = $id_prl and appr_on_direktur_status=1) as count_on,
					(select count(*) from prl_gaji where prl_generate_id = $id_prl and appr_off_direktur_status=1) as count_off
			 ";
            $count = DB::connection()->select($sql);

            $count_lokasi = ($count[0]->count_lokasi) * 2;
            $count_on = $count[0]->count_on;
            $count_off = $count[0]->count_off;

            if (($count_lokasi) == $count_off + $count_on) {


                DB::connection()->table("prl_generate")
                    ->where('prl_generate_id', $id)
                    ->update([
                        "appr_status" => (1),
                        "appr_by" => $idUser,
                        "appr_date" => date("Y-m-d H:i:s"),
                        "appr_date_lock" => $help->tambah_tanggal(date("Y-m-d"), 3)
                    ]);
            }
            DB::connection()->table("prl_generate_appr")
                ->insert([
                    "pajak" => ($pajak),
                    "m_lokasi_id" => ($entitas),
                    "prl_generate_id" => ($id),
                    "appr_status" => (1),
                    "appr_by" => $idUser,
                    "appr_type" => "direktur",
                    "appr_date" => date("Y-m-d H:i:s")
                ]);
			}
            DB::commit();
            //echo 'Hai';
            return redirect()->route('be.previewgaji', ['non_ajuan', 'prl_generate=' . $id_prl . '&menu=Gaji&Cari=Cari'])->with('success', 'Data Gaji Di Ajukan!');
        } catch (\Exeception $e) {
            DB::rollback();
            return redirect()->back()->with('error', $e);
        }
    }
    public function simpan_konfirm_gaji($request)
    {
        DB::beginTransaction();
        try {
        	
            $idUser = Auth::user()->id;
             $id = $request->get('prl_generate');
            $id_prl = $request->get('prl_generate');
            $help = new Helper_function();
            $entitas = ($request->get('entitas'));
            $pajakonoff= $request->get('pajakonoff');
            $list_pajak = array();
            if($pajakonoff==''){
            	$list_pajak[] = 'on';
            	$list_pajak[] = 'off';
            }else if($pajakonoff=='ON'){
            	$list_pajak[] = 'on';
            }else if($pajakonoff=='OFF'){
            	$list_pajak[] = 'off';
            }
            
         
            for($i=0;$i<count($list_pajak);$i++){
            	$pajak = $list_pajak[$i];
	            DB::connection()->table("prl_gaji")
	                ->where('prl_generate_id', $id)
	                ->where('m_lokasi_id', $entitas)
	                ->update([
	                    "appr_" . $pajak . "_keuangan_status" => 1,
	                    "appr_" . $pajak . "_keuangan_by" => $idUser,
	                    "appr_" . $pajak . "_keuangan_date" => date('Y-m-d H:i:s'),

	                ]);

	            DB::connection()->table("prl_generate_appr")
	                ->insert([
	                    "pajak" => ($pajak),
	                    "m_lokasi_id" => ($entitas),
	                    "prl_generate_id" => ($id),
	                    "appr_status" => (1),
	                    "appr_by" => $idUser,
	                    "appr_date" => date("Y-m-d H:i:s"),
	                    "appr_type" => "keuangan",
	                ]);
			}
            DB::commit();
            return redirect()->route('be.previewgaji', ['non_ajuan', 'prl_generate=' . $id_prl . '&menu=Gaji&Cari=Konfirmasi'])->with('success', 'Konfirmasi Transfer Gaji Berhasil di Update!');
        } catch (\Exeception $e) {
            DB::rollback();
            return redirect()->back()->with('error', $e);
        }
    }
    public function simpan_konfirm_gaji_hr($request)
    {
        DB::beginTransaction();
        try {
        	//prl_generate=59&menu=Gaji&entitas=3&pajakonoff=&bank=
            $idUser = Auth::user()->id;
            $id = $request->get('prl_generate');
            $id_prl = $request->get('prl_generate');
            $help = new Helper_function();
            $entitas = ($request->get('entitas'));
            $pajakonoff= $request->get('pajakonoff');
            $list_pajak = array();
            if($pajakonoff==''){
            	$list_pajak[] = 'on';
            	$list_pajak[] = 'off';
            }else if($pajakonoff=='ON'){
            	$list_pajak[] = 'on';
            }else if($pajakonoff=='OFF'){
            	$list_pajak[] = 'off';
            }
            
           
            for($i=0;$i<count($list_pajak);$i++){
            	$pajak = $list_pajak[$i];
	            DB::connection()->table("prl_gaji")
	                ->where('prl_generate_id', $id)
	                ->where('m_lokasi_id', $entitas)
	                ->update([
	                    "appr_" . $pajak . "_hr_status" => (1),
	                    "appr_" . $pajak . "_hr_by" => $idUser,
	                    "appr_" . $pajak . "_hr_date" => date('Y-m-d H:i:s'),
	                ]);

	            DB::connection()->table("prl_generate_appr")
	                ->insert([
	                    "pajak" => ($pajak),
	                    "m_lokasi_id" => ($entitas),
	                    "prl_generate_id" => ($id),
	                    "appr_status" => (1),
	                    "appr_by" => $idUser,
	                    "appr_date" => date("Y-m-d H:i:s"),
	                    "appr_type" => "hr",
	                ]);
            }
            DB::commit();
            return redirect()->route('be.previewgaji', ['non_ajuan', 'prl_generate=' . $id_prl . '&menu=Gaji&Cari=Cari'])->with('success', 'Data Gaji Di Ajukan!');
        } catch (\Exeception $e) {
            DB::rollback();
            return redirect()->back()->with('error', $e);
        }
    }
    public function post_generate_thr (Request $request)
    {
        $tahun = $request->get("tahun");
        $tanggal = $request->get("tanggal");
        $sql = "select appr_status from prl_generate where tahun=$tahun and is_thr=1 and active != 0";
        $gen = DB::connection()->select($sql);
        if (!count($gen)) {
            $acc = true;
        } else {

            if ($gen[0]->appr_status == 1) {
                $acc = false;
            } else {
                $acc = true;
            }
        }
        if ($acc) {

            $sql = "select max(prl_generate_id) from prl_generate ";
            $searchid = DB::connection()->select($sql);
            $id = 1;
            if (count($searchid))
                $id = ($searchid[0]->max) + 1;
            //////echo print_r($searchid);	die;
            ////if(date('Y-m-d')<=$generate->appr_date_lock )




            DB::connection()->table("prl_generate")
                ->where("tahun", $request->get("tahun"))
                
                ->where("is_thr", 1)
                ->update([
                    "active" => 0,


                ]);
            DB::connection()->table("prl_generate")
                ->insert([
                    "tahun" => $request->get("tahun"),
                    "tgl_patokan" => $tanggal,
                    
                    "prl_generate_id" => $id,
                    "is_thr" => 1,
                    "create_date" => date('Y-m-d H:i:s'),

                ]);
           
            $sql = "select DISTINCT(a.p_karyawan_id) , m_bank_id,norek,m_lokasi_id,pajak_onoff,m_jabatan_id,periode_gajian,nik
			from p_karyawan a 
			join p_karyawan_pekerjaan c on a.p_karyawan_id = c.p_karyawan_id 
			where a.active=1 and tgl_bergabung <='$tanggal' and a.p_karyawan_id in(select p_karyawan_id from p_karyawan_gapok where active=1)";

            $search_karyawan = DB::connection()->select($sql);
            foreach ($search_karyawan as $karyawan) {
                DB::connection()->table("prl_generate_karyawan")
                    ->insert([
                        "p_karyawan_id" => $karyawan->p_karyawan_id,
                        "prl_generate_id" => $id,
                        "m_bank_id" => $karyawan->m_bank_id,
                        "no_rek" => $karyawan->norek,
                        "lokasi_id" => $karyawan->m_lokasi_id,
                        "on_off" => $karyawan->pajak_onoff,
                        "jabatan_id" => $karyawan->m_jabatan_id,
                        "periode_gajian" => $karyawan->periode_gajian,
                        "nikgenerate" => $karyawan->nik,


                    ]);
            }
            return redirect()->route('be.proses_generate_thr', $id)->with('success', ' Generate Sedang di proses mohon tunggu sebentar!');
        } else {
            return redirect()->route('be.tambah_generate')->with('success', ' Generate Gagal Dibuat! Periode Generate sudah di Approve!!');
        }
    }
    public function tambah_generate_thr ()
    { 
    	 return view('backend.thr.preview.tambah_generate_thr' );
    }public function proses_generate_thr ($id)
    { 
    	 $help = new Helper_function();	
        $sqluser = "SELECT * FROM prl_generate where prl_generate_id = $id";
        $generate = DB::connection()->select($sqluser);
        
        $sqluser = "SELECT * FROM m_lokasi where active=1 and sub_entitas=0";
        $entitas = DB::connection()->select($sqluser);
    	 return view('backend.thr.preview.proses_generate_thr',compact('generate','help','id','entitas') );
    }
    public function getkaryawanprosesgenerate_thr(Request $request,$id)
    {
		DB::enableQueryLog();
		
		$gaji = DB::connection()->select("select prl_gaji_detail.p_karyawan_id,nama from prl_gaji join prl_gaji_detail on prl_gaji.prl_gaji_id = prl_gaji_detail.prl_gaji_id 
		join p_karyawan on p_karyawan.p_karyawan_id = prl_gaji_detail.p_karyawan_id
		where prl_generate_id=$id and appr_on_direktur_status=1
		group by prl_gaji_detail.p_karyawan_id,nama");
		$karyawan_approve = array();
		foreach($gaji as $gaji){
			$karyawan_approve[] = $gaji->p_karyawan_id;
			$karyawan_approve_nama[] = $gaji->nama;
		}
		
    	if($request->periode_gajian=='All' and $request->entitas=='All'){
    		$where 	="";
    		/*DB::connection()->table("prl_generate_karyawan")
    			
    			->whereNotIn('p_karyawan_id',$karyawan_approve)
    			
    			->where('prl_generate_id',$id)
                    ->update(["status"=>0]); */
    	}else if($request->entitas!='All' and $request->periode_gajian=='All' ){
    		//die;
    		$where 	="and lokasi_id=".$request->entitas;
    		/*DB::connection()->table("prl_generate_karyawan")
    			->where('prl_generate_id',$id)
    			->whereNotIn('p_karyawan_id',$karyawan_approve)
    			
    			->where('lokasi_id',$request->entitas)
                    ->update(["status"=>0]); */
    	}else if($request->periode_gajian!='All' and $request->entitas=='All' ){
    		$where 	="and periode_gajian=".$request->periode_gajian;
    		/*DB::connection()->table("prl_generate_karyawan")
    			->where('prl_generate_id',$id)
    			->whereNotIn('p_karyawan_id',$karyawan_approve)
    			
    			->where('periode_gajian',$request->periode_gajian)
                    ->update(["status"=>0]); */
    	}else{
    		$where 	="and periode_gajian=".$request->periode_gajian;
    		$where 	.="and lokasi_id=".$request->entitas;
    		/*DB::connection()->table("prl_generate_karyawan")
    			->where('prl_generate_id',$id)
    			->whereNotIn('p_karyawan_id',$karyawan_approve)
    			->where('periode_gajian',$request->periode_gajian)
    			->where('lokasi_id',$request->entitas)
                   ->update(["status"=>0]);
               */    
    	}
    	$generate = DB::connection()->select("update prl_generate_karyawan set status = 0 where 1 = 1 and prl_generate_id = $id $where");;
    	//echo "update prl_generate_karyawan set status = 0 where 1 = 1 and prl_generate_id = $id $where";
    	$query = (DB::getQueryLog()); $help = new Helper_function();
		$help->historis(($_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']),json_encode($query));
    }
    
    
    public function hitung_thr (Request $request,$id)
    {
    	$id_prl_generate = $id;
        $help = new Helper_function();
        $where = "";
        if($request->entitas!='All' and $request->entitas){
        	$where .= " and a.lokasi_id=".$request->entitas;
        	
        }
        if($request->periode_gajian!='All' and $request->periode_gajian){
        	$where .= " and a.periode_gajian=".$request->periode_gajian;
        	
        }
        $sqluser = "SELECT * FROM prl_generate_karyawan a join prl_generate b on b.prl_generate_id = a.prl_generate_id where a.prl_generate_id = $id_prl_generate and status = 0  $where 
       
        order by b.prl_generate_id asc  limit 1";
        
        $generate = DB::connection()->select($sqluser);
		
		

        $info_generate = DB::connection()->select("select * from prl_generate where prl_generate_id = $id_prl_generate");


        foreach ($generate as $g) {
            $id = $g->p_karyawan_id;
            //////echo $id;
            $sql = 'SELECT c.p_karyawan_id,tgl_bergabung,c.nama as nama_lengkap,c.nik,f.m_pangkat_id,i.nama as nm_pangkat ,f.nama as nmjabatan,AGE(CURRENT_DATE, c.tgl_bergabung)::VARCHAR AS umur
			FROM p_karyawan c 
			LEFT JOIN p_karyawan_pekerjaan d on d.p_karyawan_id=c.p_karyawan_id  
			LEFT JOIN m_jabatan f on d.m_jabatan_id=f.m_jabatan_id 
			LEFT JOIN m_pangkat i on f.m_pangkat_id=i.m_pangkat_id 
			where c.p_karyawan_id = ' . $id;
            $detail_karyawan = DB::connection()->select($sql);
            $detail_karyawan = $detail_karyawan[0];

            $id_prl = $this->prl_gaji($g->prl_generate_id, $g->p_karyawan_id);

            $tunjangan = "select *
			from m_tunjangan a 
			left join prl_tunjangan b on a.m_tunjangan_id = b.m_tunjangan_id
			left join p_karyawan_pekerjaan on b.p_karyawan_id = p_karyawan_pekerjaan.p_karyawan_id
			where b.p_karyawan_id = $id and b.active=1 and b.m_tunjangan_id in(18,17,11,19) ";
            //////echo $potongan;
            $tunjangan = DB::connection()->select($tunjangan);
            $gapok = 0;
            $yang_sudah = array();
            //print_r($tunjanga)
            foreach ($tunjangan as $tunjangan) {
            	
            	
            	
            	if($tunjangan->periode_gajian==0){
            		
	            	if($tunjangan->m_tunjangan_id==17){
					}else if($tunjangan->m_tunjangan_id==18){
	            		$tunjangan->nominal =$tunjangan->nominal*22;
	            		$tunjangan->m_tunjangan_id=17; 
	            		
	            	}
            	}
            	if(!in_array($tunjangan->m_tunjangan_id,$yang_sudah)){
            		
	            	//if(in_array($tunjangan->m_tunjangan_id,array(17,11))){
	            		$gapok+=$tunjangan->nominal;
	            		$yang_sudah[] = $tunjangan->m_tunjangan_id;
	            	//}
            	}
                $this->prl_gaji_detail($id_prl, $g->p_karyawan_id, 3, $tunjangan->m_tunjangan_id, ($tunjangan->nominal));
            }
             $Now = date('Y-m-d');
            $potongan = "select *
			from p_karyawan_koperasi a 
			where a.p_karyawan_id = $id and  tgl_akhir>='".$info_generate[0]->tgl_patokan."' and a.active=1";
           
            $potongan = DB::connection()->select($potongan);
             $zakat = 0;
            /*foreach ($potongan as $potongan) {
            	$jenis = 5;
                if ($potongan->nama_koperasi == 'ZAKAT') {
                    $type = 18;
                    $zakat = $potongan->nominal;
                }
            }*/
            /*
            $potongan = "select *
			from m_potongan a 
			left join prl_potongan b on a.m_potongan_id = b.m_potongan_id
			where b.p_karyawan_id = $id and b.active=1 and a.m_potongan_id in (18)";
            //////echo $potongan;
            $potongan = DB::connection()->select($potongan);
           
            foreach ($potongan as $potongan) {
            	if($potongan->m_potongan_id==18){
            		
            		
            	}
               // $this->prl_gaji_detail($id_prl, $g->p_karyawan_id, 4, $potongan->prl_potongan_id, ($potongan->nominal));
                
            }*/
           // echo $g->p_karyawan_id;
           // echo 'gapok'.$gapok;
           // echo '$zakat'.$zakat;
            if($gapok>=7000000){
            		$zakat += (0.025 * $gapok);
            }
          //  echo '$zakat'.$zakat;
          //  echo '<br>';
            $this->prl_gaji_detail($id_prl, $g->p_karyawan_id, 5,18, ($zakat));
          //  die;
            
           // die;
			/*
			if($tunjangan->nama=='Zakat'){
            		$zakat = $tunjangan->nominal;
            		if($gapok>=7000000){
            			$zakat += (0.025 * $gapok);
            		}
            		$tunjangan->nominal = $zakat;
            	}
			*/
            $Now = date('Y-m-d');
             $selisih = $help->selisih_tanggal($detail_karyawan->tgl_bergabung,$info_generate[0]->tgl_patokan,'object');
             
            $total_bulan =($selisih->y*12)+$selisih->m+($selisih->d?1:0) ;
            
            //$help->hitungBulan($detail_karyawan->tgl_bergabung,$info_generate[0]->tgl_patokan);
            
            $presentase = $total_bulan/12*100;
            $presentase = $presentase>100?100:$presentase;
            
            $total_bulan = $total_bulan>12?12:$total_bulan;
            
            $this->prl_gaji_detail($id_prl, $g->p_karyawan_id, 1, 29, ($total_bulan));
            $this->prl_gaji_detail($id_prl, $g->p_karyawan_id, 1, 30, ($presentase));
            
            
            
            
            
            DB::connection()->table("prl_generate_karyawan")
                ->where("prl_generate_karyawan_id", $g->prl_generate_karyawan_id)
                ->update([
                    "status" => 1,

                ]);
        }
        $id_generate = $id_prl_generate;
        $sqlgenerate = "SELECT count(*) as jumlah_semua_karyawan,count(CASE WHEN status = 1 THEN 1 END)  as yang_sudah FROM prl_generate_karyawan a where prl_generate_id = $id_generate $where";
        //////echo $sqluser;
        $generete = DB::connection()->select($sqlgenerate);
        $persen = round($generete[0]->yang_sudah / $generete[0]->jumlah_semua_karyawan * 100, 2);
        $init = ($generete[0]->jumlah_semua_karyawan - $generete[0]->yang_sudah) * 0.1;
        $hours = floor($init / 3600);
        $minutes = floor(($init / 60) % 60);
        $seconds = $init % 60;

        $Jam = $hours ? $hours . ' Jam ' : '';
        $menit = $minutes ? $minutes . ' Menit ' : '';
        $sekon = $seconds ? $seconds . ' Detik ' : '';
        //echo "$hours:$minutes:$seconds";
        echo '<div class="card">
										<div class="card-body text-center">
											<br>
											<h3>' . $persen . '%</h3>
											<h4 class="holiday-title mb-0">' . $generete[0]->yang_sudah . ' dari ' . $generete[0]->jumlah_semua_karyawan . '</h4>
											<div>' . $Jam . $menit . $sekon . ' </div>
											<div class="text-red" style="color:red;">PERINGATAN!!! TAB JANGAN DI TUTUP SEBELUM SELESAI</div>
										</div>
									</div>
									<input type="hidden" value="'.$persen.'" id="generate">
									';
    }public function prl_gaji_detail($id_prl, $id_karyawan, $type, $id, $nominal)
    {
        //$id_prl,$g->p_karyawan_id,1,1,($masuk+$cuti+$ipg+$izin+$ipd+$alpha)
        //////echo $nominal;
        if ($type == 1) {
            $row = 'gaji_absen_id';
        } else if ($type == 2) {
            $row = 'prl_tunjangan_id';
        } else if ($type == 3) {
            $row = 'm_tunjangan_id';
        } else if ($type == 4) {
            $row = 'prl_potongan_id';
        } else if ($type == 5) {
            $row = 'm_potongan_id';
        }

        $prl_detail = DB::connection()->select("select * from prl_gaji_detail where prl_gaji_id = $id_prl and type=$type and p_karyawan_id = $id_karyawan and $row=$id");
        if (count($prl_detail)) {
            DB::connection()->table("prl_gaji_detail")
                ->where('prl_gaji_detail_id', $prl_detail[0]->prl_gaji_detail_id)
                ->update([
                    "nominal" => $nominal,
                    "update_date" => date("Y-m-d H:i:s")
                ]);
        } else {


            DB::connection()->table("prl_gaji_detail")

                ->insert([

                    "prl_gaji_id" => $id_prl,
                    "type" => $type,
                    "p_karyawan_id" => $id_karyawan,
                    $row => $id,

                    "nominal" => $nominal,
                    "create_date" => date("Y-m-d H:i:s")
                ]);
        }
    }
    public function prl_gaji($id_generate, $id)
    {
        DB::beginTransaction();
        try {
            $sqluser = "SELECT a.m_lokasi_id, (select count(*) from prl_gaji b  where a.m_lokasi_id = b.m_lokasi_id and prl_generate_id = $id_generate ) as jumlah, (select max(prl_gaji_id)  from prl_gaji b) as max FROM p_karyawan_pekerjaan a where p_karyawan_id = $id ";
            //////echo $sqluser;
            $karyawan = DB::connection()->select($sqluser);
            //print_r($karyawan);
            //jumlah adalah?
            if (!$karyawan[0]->jumlah) {
                DB::connection()->table("prl_gaji")

                    ->insert([
                        "m_lokasi_id" => $karyawan[0]->m_lokasi_id,
                        "prl_gaji_id" => $karyawan[0]->max + 1,
                        "prl_generate_id" => $id_generate,

                        "active" => 1,
                        "create_date" => date("Y-m-d")
                    ]);
                $id_prl_Gaji = $karyawan[0]->max + 1;
            } else {
                $id_lokasi = $karyawan[0]->m_lokasi_id;
                $sqluser = "select (prl_gaji_id) from prl_gaji b  where m_lokasi_id = $id_lokasi and prl_generate_id = $id_generate  ";
                $karyawan = DB::connection()->select($sqluser);
                $id_prl_Gaji = $karyawan[0]->prl_gaji_id;
            }

            DB::commit();
            return $id_prl_Gaji;
            // return redirect()->route('be.kontrak')->with('success',' Kontrak Berhasil diperbaharui!');
        } catch (\Exeception $e) {
            DB::rollback();
            return redirect()->back()->with('error', $e);
        }
    }
    public function view(Request $request, $page)
    {

        $help = new Helper_function();

        $pekanan = '';
        $iduser = Auth::user()->id;
        $sqluser = "SELECT p_recruitment.foto,role,p_karyawan_pekerjaan,p_karyawan_pekerjaan.m_lokasi_id,user_entitas_access FROM users
					left join m_role on m_role.m_role_id=users.role
					left join p_karyawan on p_karyawan.user_id=users.id
					left join p_karyawan_pekerjaan on p_karyawan_pekerjaan.p_karyawan_id=p_karyawan.p_karyawan_id
					left join p_recruitment on p_recruitment.p_recruitment_id=p_karyawan.p_recruitment_id
					where users.id=$iduser";
        $user = DB::connection()->select($sqluser);
		$id_lokasi = Auth::user()->user_entitas;
        if($id_lokasi and $id_lokasi!=-1) 
            $whereLokasirole = "and m_lokasi_id in($id_lokasi)";
        } else {
            $whereLokasirole = "";
        }
        $sqluser = "SELECT p_recruitment.foto,role,p_karyawan_pekerjaan,p_karyawan_pekerjaan.m_lokasi_id,user_entitas_access,m_role_id FROM users
left join m_role on m_role.m_role_id=users.role
left join p_karyawan on p_karyawan.user_id=users.id
left join p_karyawan_pekerjaan on p_karyawan_pekerjaan.p_karyawan_id=p_karyawan.p_karyawan_id
left join p_recruitment on p_recruitment.p_recruitment_id=p_karyawan.p_recruitment_id
where users.id=$iduser";
        $user = DB::connection()->select($sqluser);
        $sql = "select * from m_lokasi where active=1 and sub_entitas = 0 $whereLokasirole";
        $entitas = DB::connection()->select($sql);
		$sudah_appr_hr = array();
        if ($request->get('prl_generate')) {
            $id_prl = $request->get('prl_generate');

            $sql = "select * from m_lokasi where active=1 and sub_entitas = 0 and m_lokasi_id in (select m_lokasi_id from prl_gaji where prl_generate_id = $id_prl) $whereLokasirole";
            $entitas = DB::connection()->select($sql);

           
            	
                $pekanan = '';
 
 				    $data_row = array();
                    $data_head = array();
                    $data_head[] = array('Pendapatan',3,'col');
                    $data_head[] = array('Potongan',4,'col');
                    $data_head[] = array('Total',3,'col');
                    
                    $data_row[] = array('Gaji Pokok', 'gapok', 2, 'pendapatan');
                    $data_row[] = array('Tunjangan Grade', 'tunjangan_grade', 2, 'pendapatan');


                    $data_row[] = array(
                        'Total Pendapatan', 'jumlah_pendapatan', 4, 'total_pendapatan_karyawan',
                        array(
                            array(
                                "Tambah", array(
                                    array("field", 'gapok'),
                                    array("field", 'tunjangan_grade'),
                                )
                            )
                        )
                    ); 
                    $data_row[] = array('Pajak', 'pajak', 2, 'potongan');
                    $data_row[] = array('Infaq', 'infaq', 2, 'potongan');
                    $data_row[] = array('Zakat', 'zakat', 2, 'potongan');
                    
                    $data_row[] = array(
                        'Total Potongan', 'jumlah_potongan', 4, 'total_potongan_karyawan',
                        array(
                            array(
                                "Tambah", array(
                                    array("field", 'pajak'),
                                    array("field", 'infaq'),
                                    array("field", 'zakat'),
                                )
                            )
                        )
                    ); 
                    
                    $data_row[] = array('Total Bulan Gabung', 'total_bulan_gabung', 2, 'absensi');
                    $data_row[] = array('Persentasi Pendapatan', 'presentase_pendapatan', 2, 'absensi');
                    

                   

                    $data_row[] = array(
                        'THP', 'thp_karyawan', 4, 'thp_karyawan',
                        array(
                        		array("Kali", array(
                                    array("field", 'jumlah_pendapatan'),
                                    array("field", 'presentase_pendapatan')
                                    )),
                                 array("Bagi", array(
                                    array("num", '100'),
                                )),
                                 array("Kurang", array(
                                    array("field", 'jumlah_potongan'))
                                )
                            )
                       
                    );
 
            
            
           

            $sqlperiode = "SELECT * FROM prl_gaji where prl_generate_id=$id_prl";
            $sudahappr = DB::connection()->select($sqlperiode);
            $sudah_appr = array();
            $sudah_appr_keuangan = array();
            $m_lokasi_hr_appr_on = '';
            $m_lokasi_hr_appr_off = '';
            foreach ($sudahappr as $apprs) {
               if ($apprs->appr_on_direktur_status == 1)
                    $sudah_appr[$apprs->m_lokasi_id]['ON'] = 1;
                else
                    $sudah_appr[$apprs->m_lokasi_id]['ON'] = 0;

                if ($apprs->appr_off_direktur_status == 1)
                    $sudah_appr[$apprs->m_lokasi_id]['OFF'] = 1;
                else
                    $sudah_appr[$apprs->m_lokasi_id]['OFF'] = 0;




                if ($apprs->appr_on_keuangan_status == 1)
                    $sudah_appr_keuangan[$apprs->m_lokasi_id]['ON'] = 1;
                else
                    $sudah_appr_keuangan[$apprs->m_lokasi_id]['ON'] = 0;

                if ($apprs->appr_off_keuangan_status == 1)
                    $sudah_appr_keuangan[$apprs->m_lokasi_id]['OFF'] = 1;
                else
                    $sudah_appr_keuangan[$apprs->m_lokasi_id]['OFF'] = 0;

                if ($apprs->appr_on_hr_status == 1)
                    $sudah_appr_hr[$apprs->m_lokasi_id]['ON'] = 1;
                else
                    $sudah_appr_hr[$apprs->m_lokasi_id]['ON'] = 0;

                if ($apprs->appr_off_hr_status == 1)
                    $sudah_appr_hr[$apprs->m_lokasi_id]['OFF'] = 1;
                else
                    $sudah_appr_hr[$apprs->m_lokasi_id]['OFF'] = 0;
                
                
                
               
				 if ($apprs->appr_on_voucher_status == 1)
                    $sudah_appr_voucher[$apprs->m_lokasi_id]['ON'] = 1;
                else
                    $sudah_appr_voucher[$apprs->m_lokasi_id]['ON'] = 0;

                
                if ($apprs->appr_off_voucher_status == 1)
                    $sudah_appr_voucher[$apprs->m_lokasi_id]['OFF'] = 1;
                else
                    $sudah_appr_voucher[$apprs->m_lokasi_id]['OFF'] = 0;
               
               
                if ($apprs->appr_on_hr_status == 1) {
                    $m_lokasi_hr_appr_on .= $apprs->m_lokasi_id . ',';
                }
                if ($apprs->appr_off_hr_status == 1) {
                    $m_lokasi_hr_appr_off .= $apprs->m_lokasi_id . ',';
                }
            }
            $m_lokasi_hr_appr_on .= '-1';
            $m_lokasi_hr_appr_off .= '-1';

           
            
            $wherelokasi = '';
            $wherepajak = '';
            $wherebank = '';
            if ($request->get('pajakonoff'))
                $wherepajak = "and d.pajak_onoff='" . $request->get('pajakonoff') . "'";
            if ($request->get('bank'))
                $wherebank = "and r.m_bank_id='" . $request->get('bank') . "'";
            if ($request->get('entitas'))
                $wherelokasi = 'and c.p_karyawan_id in(select p_karyawan_id from prl_gaji_detail join prl_gaji on prl_gaji.prl_gaji_id = prl_gaji_detail.prl_gaji_id where prl_generate_id = ' . $id_prl . ' and m_lokasi_id = ' . $request->get('entitas') . ') ';
            $iduser = Auth::user()->id;
		$id_lokasi = Auth::user()->user_entitas;
        	if($id_lokasi and $id_lokasi!=-1) 
                $whereLokasirole = "and c.p_karyawan_id in(select p_karyawan_id from prl_gaji_detail join prl_gaji on prl_gaji.prl_gaji_id = prl_gaji_detail.prl_gaji_id where prl_generate_id = $id_prl and m_lokasi_id  in($id_lokasi))";
            } else {
                $whereLokasirole = "";
            }
            //echo print_r($user[0]);die;

            if ($user[0]->m_role_id == 12 or ($page=='direksi')) {
			
                $where_directur = " and  (
				(c.p_karyawan_id in(select p_karyawan_id from prl_gaji_detail join prl_gaji on prl_gaji.prl_gaji_id = prl_gaji_detail.prl_gaji_id where prl_generate_id = $id_prl and m_lokasi_id  in ($m_lokasi_hr_appr_on) and pajak_onoff='ON')) 
					or (c.p_karyawan_id in(select p_karyawan_id from prl_gaji_detail join prl_gaji on prl_gaji.prl_gaji_id = prl_gaji_detail.prl_gaji_id where prl_generate_id = $id_prl and m_lokasi_id  in ($m_lokasi_hr_appr_off) and pajak_onoff='OFF'))
					)";
            } else {
                $where_directur = "";
            }

            $sql = "SELECT c.p_karyawan_id,c.nama as nama_lengkap,c.nik,m_lokasi.kode as nmlokasi,m_departemen.nama as departemen , f.m_pangkat_id,i.nama as nmpangkat ,m_jabatan.nama as nmjabatan,g.tgl_awal,AGE(CURRENT_DATE, c.tgl_bergabung)::VARCHAR AS umur,f.job as jobweight , j.nama_grade as grade,d.norek,d.bank	,d.m_lokasi_id, d.pajak_onoff,r.nama_bank
			FROM p_karyawan c 
			LEFT JOIN p_karyawan_pekerjaan d on d.p_karyawan_id=c.p_karyawan_id  
			LEFT JOIN p_karyawan_kontrak g on g.p_karyawan_id=c.p_karyawan_id  
			LEFT JOIN m_departemen on m_departemen.m_departemen_id=d.m_departemen_id
			LEFT JOIN m_lokasi on m_lokasi.m_lokasi_id=d.m_lokasi_id
			LEFT JOIN m_jabatan on m_jabatan.m_jabatan_id=d.m_jabatan_id 
			LEFT JOIN m_jabatan f on d.m_jabatan_id=f.m_jabatan_id 

			LEFT JOIN m_bank r on r.m_bank_id=d.m_bank_id
			LEFT JOIN m_pangkat i on f.m_pangkat_id=i.m_pangkat_id 
			LEFT JOIN m_karyawan_grade j on f.job>=j.job_min and f.job<= j.job_max
			WHERE 
			
			 c.p_karyawan_id in (select p_karyawan_id from prl_gaji_detail join prl_gaji on prl_gaji_detail.prl_gaji_id = prl_gaji.prl_gaji_id where prl_generate_id = $id_prl and prl_gaji_detail.active=1)
			--AND d.m_departemen_id != 17
			  and f.m_pangkat_id != 6
			$wherelokasi
			$whereLokasirole
			$wherepajak
			$wherebank
			$where_directur
			order by c.nama,m_departemen.nama";;
            //echo $sql;
            $list_karyawan = DB::connection()->select($sql);
            $where_prl = '';
            if ($request->get('entitas'))
                $where_prl = 'and a.m_lokasi_id = ' . $request->get('entitas');

            $sql = "select *, (select count(*) from p_karyawan_koperasi where nama_koperasi='ASA' and active=1 and b.p_karyawan_id = p_karyawan_koperasi.p_karyawan_id) as is_koperasi_asa, (select count(*) from p_karyawan_koperasi where nama_koperasi='KKB' and active=1 and b.p_karyawan_id = p_karyawan_koperasi.p_karyawan_id) as is_koperasi_kkb,
			case 
				when type=1 then (select nama_gaji from m_gaji_absen where b.gaji_absen_id = m_gaji_absen.m_gaji_absen_id )
				when type=2 then (select nama from prl_tunjangan join m_tunjangan on prl_tunjangan.m_tunjangan_id = m_tunjangan.m_tunjangan_id where b.prl_tunjangan_id = prl_tunjangan.prl_tunjangan_id )
				when type=3 then (select nama from m_tunjangan where b.m_tunjangan_id = m_tunjangan.m_tunjangan_id)
				when type=4 then (select nama from prl_potongan join m_potongan on prl_potongan.m_potongan_id = m_potongan.m_potongan_id where b.prl_potongan_id = prl_potongan.prl_potongan_id )
				when type=5 then (select nama from m_potongan where b.m_potongan_id = m_potongan.m_potongan_id)
				end as nama,m_lokasi.kode as nm_lokasi
			from prl_gaji a 
			join m_lokasi on a.m_lokasi_id = m_lokasi.m_lokasi_id
			join prl_gaji_detail b on b.prl_gaji_id = a.prl_gaji_id 
			where prl_generate_id = $id_prl
			$where_prl
			and b.active=1
			
			order by prl_gaji_detail_id 
			";
            $row = DB::connection()->select($sql);
            $data = array();
            foreach ($row as $row) {

                $data[$row->p_karyawan_id][$row->nama] = round($row->nominal);
                $data[$row->p_karyawan_id]['Entitas'][$row->nama] = ($row->nm_lokasi);
                $data[$row->p_karyawan_id]['id'][$row->nama] = ($row->prl_gaji_detail_id);
            }

        } else {
            $id = '';
            $data = array();
            $sudah_appr_keuangan = array();
            $sudah_appr_hr = array();
            $sudah_appr = array();
            $list_karyawan = '';
            $generate = '';
            $id_prl = '';
            $data_row = array();
                 $data_head = array();
        }
        
        $iduser = Auth::user()->id;
        $sqluser = "SELECT p_recruitment.foto FROM users
		left join p_karyawan on p_karyawan.user_id=users.id
		left join p_recruitment on p_recruitment.p_recruitment_id=p_karyawan.p_recruitment_id
		where users.id=$iduser";
		$user = DB::connection()->select($sqluser);
		
		
        $sqlperiode = "SELECT *,a.tahun as tahun_gener
		FROM prl_generate a 
		
		where a.active = 1 and is_thr = 1
		ORDER BY a.create_date desc, prl_generate_id desc";
        $periode = DB::connection()->select($sqlperiode);
        $periode_absen = $request->get('periode_gajian');
        

        if ($request->get('entitas')) {

            $sql = "select * from m_lokasi where m_lokasi_id=" . $request->get('entitas');
            $hisentitas = DB::connection()->select($sql);
        } else {
            $hisentitas = "";
        }
        $perigen = '';
       
        $sqluser = "SELECT prl_generate.*,prl_gaji.*,case when prl_generate.periode_gajian=0 then 'Pekanan' else 'Bulanan' end as tipe,m_lokasi.nama as nmlokasi,
    	a.tgl_awal as tgl_awal_absen, 
    	a.tgl_akhir as tgl_akhir_absen, 
    	b.tgl_awal as tgl_awal_lembur, 
    	b.tgl_akhir as tgl_akhir_lembur,
    	prl_generate.tahun,
    	a.pekanan_ke,
    	(select count(*) from p_karyawan_pekerjaan where p_karyawan_pekerjaan.m_lokasi_id =prl_gaji.m_lokasi_id and pajak_onoff='ON' and p_karyawan_id in(select p_karyawan_id from prl_gaji_detail where prl_gaji_detail.prl_gaji_id = prl_gaji.prl_gaji_id )) is_on,
    	(select count(*) from p_karyawan_pekerjaan where p_karyawan_pekerjaan.m_lokasi_id =prl_gaji.m_lokasi_id and pajak_onoff='OFF' and p_karyawan_id in(select p_karyawan_id from prl_gaji_detail where prl_gaji_detail.prl_gaji_id = prl_gaji.prl_gaji_id )) is_off
    	FROM prl_gaji 
    		left join m_lokasi  on prl_gaji.m_lokasi_id = m_lokasi.m_lokasi_id
    		left join prl_generate  on prl_gaji.prl_generate_id = prl_generate.prl_generate_id
    		left join m_periode_absen a on prl_generate.periode_absen_id = a.periode_absen_id
    		left join m_periode_absen b on prl_generate.periode_lembur_id = b.periode_absen_id
    		where prl_generate.active = 1 and is_thr = 1
    		order by prl_generate.prl_generate_id desc
    	";
        $generate_gaji = DB::connection()->select($sqluser);
        
        $data['page'] = $page;
		
        //echo ''.$request->get('menu');die;
         if ($request->get('submit_appr') == "AjukanHR") {
            GajiPreviewController::simpan_konfirm_gaji_hr($request);
            /*$generate = $generate[0];
            $sql = "SELECT prl_generate_appr.*,a.name as appr_nama,m_lokasi.nama as nmlokasi 
			FROM prl_generate_appr 
				left join users a on prl_generate_appr.appr_by = a.id 
				left join m_lokasi  on prl_generate_appr.m_lokasi_id = m_lokasi.m_lokasi_id 
				
				where prl_generate_id=$id_prl and appr_type='hr'";
            $appr = DB::connection()->select($sql);
            return view('backend.thr.preview.approval_hr', compact('data', 'entitas', 'user', 'id_prl', 'help', 'periode', 'periode_absen', 'request', 'list_karyawan', 'generate', 'appr', 'sudah_appr', 'sudah_appr_keuangan', 'sudah_appr_hr'));*/
        }else if ($request->get('submit_appr') == "Approve") {
            GajiPreviewController::simpan_appr_gaji($request);
            /*$generate = $generate[0];
            $sql = "SELECT prl_generate_appr.*,a.name as appr_nama,m_lokasi.nama as nmlokasi 
			FROM prl_generate_appr 
				left join users a on prl_generate_appr.appr_by = a.id 
				left join m_lokasi  on prl_generate_appr.m_lokasi_id = m_lokasi.m_lokasi_id 
				
				where prl_generate_id=$id_prl and appr_type='hr'";
            $appr = DB::connection()->select($sql);
            return view('backend.thr.preview.approval_hr', compact('data', 'entitas', 'user', 'id_prl', 'help', 'periode', 'periode_absen', 'request', 'list_karyawan', 'generate', 'appr', 'sudah_appr', 'sudah_appr_keuangan', 'sudah_appr_hr'));*/
        }else if ($request->get('submit_appr') == "Konfirmasi") {
            GajiPreviewController::simpan_konfirm_gaji($request);
            /*$generate = $generate[0];
            $sql = "SELECT prl_generate_appr.*,a.name as appr_nama,m_lokasi.nama as nmlokasi 
			FROM prl_generate_appr 
				left join users a on prl_generate_appr.appr_by = a.id 
				left join m_lokasi  on prl_generate_appr.m_lokasi_id = m_lokasi.m_lokasi_id 
				
				where prl_generate_id=$id_prl and appr_type='hr'";
            $appr = DB::connection()->select($sql);
            return view('backend.thr.preview.approval_hr', compact('data', 'entitas', 'user', 'id_prl', 'help', 'periode', 'periode_absen', 'request', 'list_karyawan', 'generate', 'appr', 'sudah_appr', 'sudah_appr_keuangan', 'sudah_appr_hr'));*/
        }
        if ($request->get('Cari') == "RekapExcel") {

            $param['data'] = $data;
            $param['user'] = $user;
            $param['help'] = $help;
            $param['id_prl'] = $id_prl;
            $param['periode'] = $periode;
            $param['entitas'] = $entitas;
            $param['periode_absen'] = $periode_absen;
            $param['request'] = $request;
            $param['list_karyawan'] = $list_karyawan;
            $param['hisentitas'] = $hisentitas;
            $param['perigen'] = $perigen;
            $param['generate_gaji'] = $generate_gaji;
            $param['data_row'] = $data_row;
            $param['data_head'] = $data_head;
            if ($request->get('menu') == 'Presensi') {
                //die;
                if ($pekanan)
                    return GajiPreviewController::exportsPresensi_Pekanan($param);
                else
                    return GajiPreviewController::exportsPresensi($param);
            } elseif ($request->get('menu') == 'previewpajak') {
                return GajiPreviewController::exportsPajak($param);
            } elseif ($request->get('menu') == 'Gaji') {
                if ($pekanan)
                    return GajiPreviewController::exportsGaji_pekanan($param);
                else
                    return GajiPreviewController::exportsGaji($param);
            }
        } else if ($request->get('Cari') == "RekapExcelRp") {

            $param['data'] = $data;
            $param['user'] = $user;
            $param['help'] = $help;
            $param['id_prl'] = $id_prl;
            $param['periode'] = $periode;
            $param['entitas'] = $entitas;
            $param['periode_absen'] = $periode_absen;
            $param['request'] = $request;
            $param['list_karyawan'] = $list_karyawan;
            $param['hisentitas'] = $hisentitas;
            $param['perigen'] = $perigen;
            $param['data_row'] = $data_row;
            $param['data_head'] = $data_head;
            
            $param['generate_gaji'] = $generate_gaji;
            if ($request->get('menu') == 'Presensi') {
                //die;
                if ($pekanan)
                    return GajiPreviewController::exportsPresensiRp_Pekanan($param);
                else
                    return GajiPreviewController::exportsPresensiRp($param);
            } elseif ($request->get('menu') == 'previewpajak') {
                return GajiPreviewController::exportsPajakRp($param);
            } elseif ($request->get('menu') == 'Gaji') {
                if ($pekanan)
                    return GajiPreviewController::exportsGajiRp_pekanan($param);
                else
                    return GajiPreviewController::exportsGajiRp($param);
            }
        } else  if ($request->get('Cari') == "Ajuan HR") {
             
           $generate = $generate[0];
            $sql = "SELECT prl_generate_appr.*,a.name as appr_nama,m_lokasi.nama as nmlokasi 
			FROM prl_generate_appr 
				left join users a on prl_generate_appr.appr_by = a.id 
				left join m_lokasi  on prl_generate_appr.m_lokasi_id = m_lokasi.m_lokasi_id 
				
				where prl_generate_id=$id_prl and appr_type='hr'";
            $appr = DB::connection()->select($sql);
            return view('backend.thr.preview.approval_hr', compact('data', 'data_row', 'data_head', 'entitas', 'user', 'id_prl', 'help', 'periode', 'periode_absen', 'request', 'list_karyawan', 'generate', 'appr', 'sudah_appr', 'sudah_appr_keuangan', 'sudah_appr_hr','generate_gaji'));
        }else if ($request->get('Cari') == "Approval") {
            $generate = $generate[0];
            $sql = "SELECT prl_generate_appr.*,a.name as appr_nama,m_lokasi.nama as nmlokasi 
			FROM prl_generate_appr 
				left join users a on prl_generate_appr.appr_by = a.id 
				left join m_lokasi  on prl_generate_appr.m_lokasi_id = m_lokasi.m_lokasi_id 
				
				where prl_generate_id=$id_prl and appr_type='direktur'";
            $appr = DB::connection()->select($sql);
            return view('backend.thr.preview.approval', compact('data', 'data_row', 'data_head', 'entitas', 'user', 'id_prl', 'help', 'periode', 'periode_absen', 'request', 'list_karyawan', 'generate', 'appr', 'sudah_appr', 'sudah_appr_keuangan', 'sudah_appr_hr','generate_gaji'));
        }  else if ($request->get('Cari') == "Konfirmasi") {
            $generate = $generate[0];
            $sql = "
			SELECT prl_generate_appr.*,a.name as appr_nama,m_lokasi.nama as nmlokasi FROM prl_generate_appr 
				left join users a on prl_generate_appr.appr_by = a.id 
				left join m_lokasi  on prl_generate_appr.m_lokasi_id = m_lokasi.m_lokasi_id 
				
				where prl_generate_id=$id_prl and appr_type='keuangan'";
            $appr = DB::connection()->select($sql);

            return view('backend.thr.preview.approval_keuangan', compact('data', 'data_row', 'data_head', 'entitas', 'user', 'id_prl', 'help', 'periode', 'periode_absen', 'request', 'list_karyawan', 'generate', 'appr', 'sudah_appr', 'sudah_appr_keuangan', 'sudah_appr_hr','generate_gaji'));
        } else if ($request->get('Cari') == "Ajuan") {
            if ($request->get('entitas')) {

                $sqlperiode = "SELECT *,a.name as appr_nama_on,b.name as appr_nama_off FROM prl_gaji  
					left join users a on prl_gaji.appr_on_direktur_by = a.id 
					left join users b on prl_gaji.appr_off_direktur_by = b.id 
					where prl_generate_id=$id_prl and m_lokasi_id = " . $request->get('entitas') . "";
                $sudahappr = DB::connection()->select($sqlperiode);
            } else {
                $sudahappr = array();
            }

            return view('backend.thr.preview.ajuan' . $pekanan, compact('data', 'data_row', 'data_head', 'entitas', 'user', 'id_prl', 'help', 'periode', 'periode_absen', 'request', 'list_karyawan', 'generate', 'sudahappr','generate_gaji'));
        } else if ($request->get('Cari') == "ExcelAjuan") {
            $param['data'] = $data;
            $param['user'] = $user;
            $param['help'] = $help;
            $param['id_prl'] = $id_prl;
            $param['periode'] = $periode;
            $param['perigen'] = $perigen;
            $param['entitas'] = $entitas;
            $param['hisentitas'] = $hisentitas;
            $param['periode_absen'] = $periode_absen;
            $param['request'] = $request;
            $param['list_karyawan'] = $list_karyawan;
            $param['data_row'] = $data_row;
            $param['data_head'] = $data_head;
            if ($pekanan)
                return GajiPreviewController::exportsAjuan_pekanan($param);
            else
                return GajiPreviewController::exportsAjuan($param);
        } else {
            if ($request->get('menu') == 'Presensi') {

                return view('backend.thr.preview.view_presensi' . $pekanan, compact('data', 'data_row', 'data_head', 'entitas', 'user', 'id_prl', 'help', 'periode', 'periode_absen', 'request', 'list_karyawan', 'sudah_appr', 'sudah_appr_keuangan', 'sudah_appr_hr','generate_gaji'));
            } else if ($request->get('menu') == 'previewpajak') {
                return view('backend.thr.preview.view_previewpajak' . $pekanan, compact('data', 'data_row', 'data_head', 'entitas', 'user', 'id_prl', 'help', 'periode', 'periode_absen', 'request', 'list_karyawan', 'sudah_appr', 'sudah_appr_keuangan', 'sudah_appr_hr','generate_gaji'));
            } else {

                return view('backend.gaji.preview.viewgapreview' . $pekanan, compact('data', 'data_row', 'data_head', 'entitas', 'user', 'id_prl', 'help', 'periode', 'periode_absen', 'request', 'list_karyawan', 'sudah_appr', 'sudah_appr_keuangan', 'sudah_appr_hr','generate_gaji'));
            }
        }
    }
    public function save_change_nominal(Request $request)
    {
        DB::beginTransaction();
        try {
            $idUser = Auth::user()->id;
            $help = new Helper_function();


            $id_nominal = $request->get('id_nominal');
            $nominal_now = $request->get('nominal_now');
            $id_karyawan = $request->get('id_karyawan');
            $id_prl = $request->get('id_prl');
            $field = $request->get('field');
            if ($id_nominal != -1) {

                DB::connection()->table("prl_gaji_detail")
                    ->where('prl_gaji_detail_id', $id_nominal)
                    ->update([
                        "nominal" => ($nominal_now),
                        "update_date" => date('Y-m-d H:i:s'),

                    ]);
            } else {
                if ($field == 'KKB') {
                    $type = 5;
                    $id = 9;
                } else if ($field == 'ASA') {
                    $id = 21;
                    $type = 5;
                } else if ($field == 'sewa_kost') {
                    $id = 16;
                    $type = 5;
                } else if ($field == 'telat') {
                    $id = 12;
                    $type = 5;
                } else if ($field == 'potfinger') {
                    $id = 23;
                    $type = 5;
                } else if ($field == 'potpm') {
                    $id = 24;
                    $type = 5;
                } else if ($field == 'potizin') {
                    $id = 22;
                    $type = 5;
                } else if ($field == 'absen') {
                    $id = 13;
                    $type = 5;
                } else  if ($field == 'iuran_bpjskes') {
                    $id = 14;
                    $type = 4;
                } else  if ($field == 'iuran_bpjsket') {
                    $id = 15;
                    $type = 4;
                } else if ($field == 'zakat') {
                    $type = 4;

                    $id = 18;
                } else if ($field == 'infaq') {
                    $id = 18;
                    $type = 4;
                } else if ($field == 'korekmin') {
                    $id = 17;
                    $type = 5;
                } else if ($field == 'tunjangan_grade') {
                    $id = 11;
                    $type = 2;
                } else if ($field == 'gapok') {
                    $id = 17;
                    $type = 2;
                } else if ($field == 'lembur') {
                    $id = 15;
                    $type = 3;
                } else if ($field == 'tunjangan_bpjskes') {
                    $id = 12;
                    $type = 2;
                } else if ($field == 'tunjangan_bpjsket') {
                    $id = 13;
                    $type = 2;
                } else if ($field == 'tunjangan_kost') {

                    $type = 2;
                    $id = 14;
                } else if ($field == 'korekplus') {
                    $type = 3;
                    $id = 16;
                }




                if ($type == 1) {
                    $row = 'gaji_absen_id';
                } else if ($type == 2) {
                    $row = 'prl_tunjangan_id';
                } else if ($type == 3) {
                    $row = 'm_tunjangan_id';
                } else if ($type == 4) {
                    $row = 'prl_potongan_id';
                } else if ($type == 5) {
                    $row = 'm_potongan_id';
                }
                $id_generate = $id_prl;
                $sqluser = "SELECT a.m_lokasi_id, (select count(*) from prl_gaji b  where a.m_lokasi_id = b.m_lokasi_id and prl_generate_id = $id_generate ) as jumlah, (select max(prl_gaji_id)  from prl_gaji b) as max FROM p_karyawan_pekerjaan a where p_karyawan_id = $id_karyawan ";
                //////echo $sqluser;
                $karyawan = DB::connection()->select($sqluser);



                if (!$karyawan[0]->jumlah) {
                    DB::connection()->table("prl_gaji")

                        ->insert([
                            "m_lokasi_id" => $karyawan[0]->m_lokasi_id,
                            "prl_gaji_id" => $karyawan[0]->max + 1,
                            "prl_generate_id" => $id_generate,

                            "active" => 1,
                            "create_date" => date("Y-m-d")
                        ]);
                    $id_prl_Gaji = $karyawan[0]->max + 1;
                } else {
                    $id_lokasi = $karyawan[0]->m_lokasi_id;
                    $sqluser = "select (prl_gaji_id) from prl_gaji b  where m_lokasi_id = $id_lokasi and prl_generate_id = $id_generate  ";
                    $karyawan = DB::connection()->select($sqluser);
                    $id_prl_Gaji = $karyawan[0]->prl_gaji_id;
                }

                $prl_detail = DB::connection()->select("select * from prl_gaji_detail where prl_gaji_id = $id_prl_Gaji and type=$type and p_karyawan_id = $id_karyawan and $row=$id");
                if (count($prl_detail)) {
                    DB::connection()->table("prl_gaji_detail")
                        ->where('prl_gaji_detail_id', $prl_detail[0]->prl_gaji_detail_id)
                        ->update([
                            "nominal" => $nominal_now,
                            "update_date" => date("Y-m-d H:i:s")
                        ]);
                } else {


                    DB::connection()->table("prl_gaji_detail")

                        ->insert([

                            "prl_gaji_id" => $id_prl_Gaji,
                            "type" => $type,
                            "p_karyawan_id" => $id_karyawan,
                            $row => $id,

                            "nominal" => $nominal_now,
                            "create_date" => date("Y-m-d H:i:s")
                        ]);
                }
            }
            DB::commit();
        } catch (\Exeception $e) {
            DB::rollback();
            return redirect()->back()->with('error', $e);
        }
    }
    public function ajuan_gaji(Request $request)
    {

        $help = new Helper_function();

        $pekanan = '';
        if ($request->get('prl_generate')) {
            $id_prl = $request->get('prl_generate');
            $sql = "SELECT prl_generate.*,a.name as appr_nama FROM prl_generate left join users a on prl_generate.appr_by = a.id where prl_generate_id=$id_prl";
            $generate = DB::connection()->select($sql);
           $periode_absen = $generate[0]->periode_absen_id;
            $sqlperiode = "SELECT * FROM m_periode_absen where periode_absen_id=$periode_absen";
            $periodetgl = DB::connection()->select($sqlperiode);
            $type = $periodetgl[0]->type;
            $where = " d.periode_gajian = " . $type;
            $appendwhere = "and";

            $periode_gajian = $periodetgl[0]->type;
            $tgl_awal = date('Y-m-d', strtotime($periodetgl[0]->tgl_awal));
            $tgl_akhir = date('Y-m-d', strtotime($periodetgl[0]->tgl_akhir));
            $wherelokasi = '';
            $wherepajak = '';
            if ($request->get('pajakonoff'))
                $wherepajak = "and d.pajak_onoff='" . $request->get('pajakonoff') . "'";
            if ($request->get('entitas'))
                $wherelokasi = 'and c.p_karyawan_id in(select p_karyawan_id from prl_gaji_detail join prl_gaji on prl_gaji.prl_gaji_id = prl_gaji_detail.prl_gaji_id where prl_generate_id = ' . $id_prl . ' and m_lokasi_id = ' . $request->get('entitas') . ') ';
            $iduser = Auth::user()->id;
            $sqluser = "SELECT p_recruitment.foto,role,p_karyawan_pekerjaan,p_karyawan_pekerjaan.m_lokasi_id,user_entitas_access FROM users
left join m_role on m_role.m_role_id=users.role
left join p_karyawan on p_karyawan.user_id=users.id
left join p_karyawan_pekerjaan on p_karyawan_pekerjaan.p_karyawan_id=p_karyawan.p_karyawan_id
left join p_recruitment on p_recruitment.p_recruitment_id=p_karyawan.p_recruitment_id
where users.id=$iduser";
            $user = DB::connection()->select($sqluser);
            $id_lokasi = Auth::user()->user_entitas;
        	if($id_lokasi and $id_lokasi!=-1) 
                $whereLokasirole = "and c.p_karyawan_id in(select p_karyawan_id from prl_gaji_detail join prl_gaji on prl_gaji.prl_gaji_id = prl_gaji_detail.prl_gaji_id where prl_generate_id = $id_prl and m_lokasi_id  in($id_lokasi))";
            } else {
                $whereLokasirole = "";
            }
            $sql = "SELECT c.p_karyawan_id,c.nama as nama_lengkap,c.nik,m_lokasi.kode as nmlokasi,m_departemen.nama as departemen , f.m_pangkat_id,i.nama as nmpangkat ,m_jabatan.nama as nmjabatan,g.tgl_awal,AGE(CURRENT_DATE, c.tgl_bergabung)::VARCHAR AS umur,f.job as jobweight , j.nama_grade as grade,d.norek,d.bank	
			FROM p_karyawan c 
			LEFT JOIN p_karyawan_pekerjaan d on d.p_karyawan_id=c.p_karyawan_id  
			LEFT JOIN p_karyawan_kontrak g on g.p_karyawan_id=c.p_karyawan_id  
			LEFT JOIN m_departemen on m_departemen.m_departemen_id=d.m_departemen_id
			LEFT JOIN m_lokasi on m_lokasi.m_lokasi_id=d.m_lokasi_id
			LEFT JOIN m_jabatan on m_jabatan.m_jabatan_id=d.m_jabatan_id 
			LEFT JOIN m_jabatan f on d.m_jabatan_id=f.m_jabatan_id 
			LEFT JOIN m_pangkat i on f.m_pangkat_id=i.m_pangkat_id 
			LEFT JOIN m_karyawan_grade j on f.job>=j.job_min and f.job<= j.job_max
			WHERE $where $appendwhere   c.active = 1
			--AND d.m_departemen_id != 17
			  and f.m_pangkat_id != 6
			$wherelokasi
			$whereLokasirole
			$wherepajak
			order by c.nama,m_departemen.nama";;
            //echo $sql;
            $list_karyawan = DB::connection()->select($sql);
            $where_prl = '';
            if ($request->get('entitas'))
                $where_prl = 'and a.m_lokasi_id = ' . $request->get('entitas');

            $sql = "select *,
			case 
				when type=1 then (select nama_gaji from m_gaji_absen where b.gaji_absen_id = m_gaji_absen.m_gaji_absen_id )
				when type=2 then (select nama from prl_tunjangan join m_tunjangan on prl_tunjangan.m_tunjangan_id = m_tunjangan.m_tunjangan_id where b.prl_tunjangan_id = prl_tunjangan.prl_tunjangan_id )
				when type=3 then (select nama from m_tunjangan where b.m_tunjangan_id = m_tunjangan.m_tunjangan_id)
				when type=4 then (select nama from prl_potongan join m_potongan on prl_potongan.m_potongan_id = m_potongan.m_potongan_id where b.prl_potongan_id = prl_potongan.prl_potongan_id )
				when type=5 then (select nama from m_potongan where b.m_potongan_id = m_potongan.m_potongan_id)
			end as nama 
			from prl_gaji a 
			join prl_gaji_detail b on b.prl_gaji_id = a.prl_gaji_id 
			where prl_generate_id = $id_prl
			$where_prl
			 and b.active=1
			order by prl_gaji_detail_id 
			";
            $row = DB::connection()->select($sql);
            $data = array();
            foreach ($row as $row) {
                $data[$row->p_karyawan_id][$row->nama] = round($row->nominal);
            }
        } else {
            $id = '';
            $data = array();
            $list_karyawan = '';
            $generate = '';
            $id_prl = '';
        }

        $iduser = Auth::user()->id;
        $sqluser = "SELECT p_recruitment.foto FROM users
		left join p_karyawan on p_karyawan.user_id=users.id
		left join p_recruitment on p_recruitment.p_recruitment_id=p_karyawan.p_recruitment_id
		where users.id=$iduser";
        $sqlperiode = "SELECT m_periode_absen.*,
		
		case when a.periode_gajian=0 then 'Pekanan' else 'Bulanan' end as tipe,
		a.tahun as tahun_gener,a.*,a.bulan as bulan_gener,b.tgl_awal as tgl_awal_lembur,b.tgl_akhir as tgl_akhir_lembur
		FROM prl_generate a 
		join m_periode_absen on m_periode_absen.periode_absen_id = a.periode_absen_id
		join m_periode_absen b on b.periode_absen_id = a.periode_lembur_id
		where a.active = 1
		ORDER BY a.create_date desc, prl_generate_id desc";
        $periode = DB::connection()->select($sqlperiode);
        $periode_absen = $request->get('periode_gajian');
        $user = DB::connection()->select($sqluser);
        $sql = "select * from m_lokasi where active=1 and sub_entitas = 0";
        $entitas = DB::connection()->select($sql);
        if ($request->get('entitas')) {

            $sql = "select * from m_lokasi where m_lokasi_id=" . $request->get('entitas');
            $hisentitas = DB::connection()->select($sql);
        } else {
            $hisentitas = "";
        }
        $perigen = '';
        foreach ($periode as $periode2) {
            if ($periode2->prl_generate_id == $id_prl) {
                $perigen = 'Periode: ' . $periode2->tahun_gener . ' Bulan: ' . $periode2->bulan_gener . ' | Absen:' . $periode2->tgl_awal . ' - ' . $periode2->tgl_akhir . ' | Lembur:' . $periode2->tgl_awal_lembur . ' - ' . $periode2->tgl_akhir_lembur . '';
            }
        }
        $data['page'] = 'ajuan';


        //echo ''.$request->get('menu');die;
        if ($request->get('Cari') == "Ajuan") {

            return view('backend.thr.preview.ajuan' . $pekanan, compact('data', 'data_row', 'data_head', 'entitas', 'user', 'id_prl', 'help', 'periode', 'periode_absen', 'request', 'list_karyawan', 'generate'));
        } else if ($request->get('Cari') == "ExcelAjuan") {
            $param['data'] = $data;
            $param['user'] = $user;
            $param['help'] = $help;
            $param['id_prl'] = $id_prl;
            $param['periode'] = $periode;
            $param['perigen'] = $perigen;
            $param['entitas'] = $entitas;
            $param['hisentitas'] = $hisentitas;
            $param['periode_absen'] = $periode_absen;
            $param['request'] = $request;
            $param['data_row'] = $data_row;
            $param['data_head'] = $data_head;
            $param['list_karyawan'] = $list_karyawan;

            if ($pekanan)
                return GajiPreviewController::exportsAjuan_pekanan($param);
            else
                return GajiPreviewController::exportsAjuan($param);
        } else {
            return view('backend.thr.preview.ajuan' . $pekanan, compact('data', 'data_row', 'data_head', 'entitas', 'data_row', 'data_head', 'user', 'id_prl', 'help', 'periode', 'periode_absen', 'request', 'list_karyawan', 'generate'));
        }
    }
    public function exportsAjuan($param)
    {

        $data = $param['data'];
        $user = $param['user'];
        $help = $param['help'];
        $entitas = $param['entitas'];
        $id_prl = $param['id_prl'];
        $hisentitas = $param['hisentitas'];

        $perigen = $param['perigen'];
        $periode = $param['periode'];
        $periode_absen = $param['periode_absen'];
        $request = $param['request'];
        $list_karyawan = $param['list_karyawan'];


        $help = new Helper_function();
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getColumnDimension('B')->setAutoSize(true);
        $sheet->getColumnDimension('C')->setAutoSize(true);
        $sheet->getColumnDimension('D')->setAutoSize(true);
        $sheet->getColumnDimension('E')->setAutoSize(true);
        $sheet->getColumnDimension('F')->setAutoSize(true);
        $sheet->getColumnDimension('G')->setAutoSize(true);
        $sheet->getColumnDimension('H')->setAutoSize(true);
        $sheet->getColumnDimension('I')->setAutoSize(true);
        $sheet->getColumnDimension('J')->setAutoSize(true);
        $sheet->getColumnDimension('K')->setAutoSize(true);
        $sheet->getColumnDimension('L')->setAutoSize(true);
        $sheet->getColumnDimension('M')->setAutoSize(true);
        $sheet->getColumnDimension('N')->setAutoSize(true);
        $sheet->getColumnDimension('O')->setAutoSize(true);
        $sheet->getColumnDimension('P')->setAutoSize(true);
        $sheet->getColumnDimension('Q')->setAutoSize(true);
        $sheet->getColumnDimension('R')->setAutoSize(true);
        $sheet->getColumnDimension('S')->setAutoSize(true);
        $sheet->getColumnDimension('T')->setAutoSize(true);
        $sheet->getColumnDimension('U')->setAutoSize(true);
        $sheet->getColumnDimension('V')->setAutoSize(true);
        $sheet->getColumnDimension('W')->setAutoSize(true);
        $sheet->getColumnDimension('X')->setAutoSize(true);
        $sheet->getColumnDimension('Y')->setAutoSize(true);
        $sheet->getColumnDimension('Z')->setAutoSize(true);
        $sheet->getColumnDimension('AA')->setAutoSize(true);
        $sheet->getColumnDimension('AB')->setAutoSize(true);
        $sheet->getColumnDimension('AC')->setAutoSize(true);
        $sheet->getColumnDimension('AD')->setAutoSize(true);
        $sheet->getColumnDimension('AE')->setAutoSize(true);
        $sheet->getColumnDimension('AF')->setAutoSize(true);
        $sheet->getColumnDimension('AG')->setAutoSize(true);
        $sheet->getColumnDimension('AH')->setAutoSize(true);
        $sheet->getColumnDimension('AI')->setAutoSize(true);
        $sheet->getColumnDimension('AJ')->setAutoSize(true);
        $sheet->getColumnDimension('AK')->setAutoSize(true);
        $sheet->getColumnDimension('AL')->setAutoSize(true);
        $sheet->getColumnDimension('AM')->setAutoSize(true);
        $sheet->getColumnDimension('AN')->setAutoSize(true);


        $i = 0;



        $sheet->setCellValue($help->toAlpha($i) . '1', 'REKENING');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '1', 'PLUS');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '1', 'NOMINAL');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '1', 'CD');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '1', 'NO');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '1', 'NAMA');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '1', 'KETERANGAN');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '1', 'BANK');
        $i++;


        $rows = 2;



        if (!empty($list_karyawan)) {

            $no = 0;
            $nominal = 0;
            foreach ($list_karyawan as $list_karyawan) {
                $total_all = 0;
                $help->rupiah(isset($data[$list_karyawan->p_karyawan_id]['Lembur']) ? $lembur = $data[$list_karyawan->p_karyawan_id]['Lembur'] : $lembur = 0);
                $help->rupiah(isset($data[$list_karyawan->p_karyawan_id]['Tunjangan BPJS Kesehatan']) ? $tunkes = $data[$list_karyawan->p_karyawan_id]['Tunjangan BPJS Kesehatan'] : $tunkes = 0);
                $help->rupiah(isset($data[$list_karyawan->p_karyawan_id]['Tunjangan BPJS Ketenaga Kerjaan']) ? $tunket = $data[$list_karyawan->p_karyawan_id]['Tunjangan BPJS Ketenaga Kerjaan'] : $tunket = 0);
                $help->rupiah(isset($data[$list_karyawan->p_karyawan_id]['Koreksi(+)']) ? $korekplus = $data[$list_karyawan->p_karyawan_id]['Koreksi(+)'] : $korekplus = 0);
                $help->rupiah(isset($data[$list_karyawan->p_karyawan_id]['Tunjangan Kost']) ? $tunkost = $data[$list_karyawan->p_karyawan_id]['Tunjangan Kost']    : $tunkost = 0);
                $help->rupiah($tunjangan = $tunkost + $korekplus + $tunket + $tunkes + $lembur);

                $help->rupiah(isset($data[$list_karyawan->p_karyawan_id]['Potongan Telat']) ? $telat = $data[$list_karyawan->p_karyawan_id]['Potongan Telat'] : $telat = 0);
                $help->rupiah(isset($data[$list_karyawan->p_karyawan_id]['Potongan Absen']) ? $absen = $data[$list_karyawan->p_karyawan_id]['Potongan Absen'] : $absen = 0);
                $help->rupiah(isset($data[$list_karyawan->p_karyawan_id]['Sewa Kost']) ? $sewakost = $data[$list_karyawan->p_karyawan_id]['Sewa Kost'] : $sewakost = 0);
                $help->rupiah(isset($data[$list_karyawan->p_karyawan_id]['Iuran BPJS Kesehatan']) ? $ibpjs = $data[$list_karyawan->p_karyawan_id]['Iuran BPJS Kesehatan'] : $ibpjs = 0);
                $help->rupiah(isset($data[$list_karyawan->p_karyawan_id]['Iuran BPJS Ketenaga Kerjaan']) ? $ibpjt = $data[$list_karyawan->p_karyawan_id]['Iuran BPJS Ketenaga Kerjaan'] : $ibpjt = 0);
                $help->rupiah(isset($data[$list_karyawan->p_karyawan_id]['Zakat']) ? $zakat = $data[$list_karyawan->p_karyawan_id]['Zakat'] : $zakat = 0);
                $help->rupiah(isset($data[$list_karyawan->p_karyawan_id]['Infaq']) ? $infaq = $data[$list_karyawan->p_karyawan_id]['Infaq'] : $infaq = 0);
                $help->rupiah(isset($data[$list_karyawan->p_karyawan_id]['Koreksi(-)']) ? $korekmin = $data[$list_karyawan->p_karyawan_id]['Koreksi(-)'] : $korekmin = 0);
                (isset($data[$list_karyawan->p_karyawan_id]['Simpanan Wajib KKB']) ? $kkp = $data[$list_karyawan->p_karyawan_id]['Simpanan Wajib KKB'] : $kkp = 0);
                (isset($data[$list_karyawan->p_karyawan_id]['Potongan Koperasi Asa']) ? $asa = $data[$list_karyawan->p_karyawan_id]['Potongan Koperasi Asa'] : $asa = 0);
                (isset($data[$list_karyawan->p_karyawan_id]['Pajak']) ? $pajak = $data[$list_karyawan->p_karyawan_id]['Pajak'] : $pajak = 0);
                isset($data[$list_karyawan->p_karyawan_id]['Potongan Fingerprint']) ? $finger = $data[$list_karyawan->p_karyawan_id]['Potongan Fingerprint'] : $finger = 0;
                (isset($data[$list_karyawan->p_karyawan_id]['Potongan Fingerprint']) ? $potfinger = $data[$list_karyawan->p_karyawan_id]['Potongan Fingerprint'] : $potfinger = 0);
                $potpm = (isset($data[$list_karyawan->p_karyawan_id]['Potongan Pulang Mendahului']) ? $potpm = $data[$list_karyawan->p_karyawan_id]['Potongan Pulang Mendahului'] : 0);
                $potizin = (isset($data[$list_karyawan->p_karyawan_id]['Potongan Izin']) ? $data[$list_karyawan->p_karyawan_id]['Potongan Izin'] : 0);
                ($potongan = $telat + $absen + $sewakost + $ibpjs + $ibpjt + $zakat + $infaq + $korekmin + $kkp + $asa + $pajak + $potizin + $potfinger + $potpm);
                $potongan = $telat + $absen + $sewakost + $ibpjs + $ibpjt + $zakat + $infaq + $korekmin + $kkp + $asa + $pajak + $potizin + $potfinger + $potpm;
                $grade = isset($data[$list_karyawan->p_karyawan_id]['Gaji Pokok']) ? $data[$list_karyawan->p_karyawan_id]['Gaji Pokok'] : 0;
                $gapok = isset($data[$list_karyawan->p_karyawan_id]['Tunjangan Grade']) ? $data[$list_karyawan->p_karyawan_id]['Tunjangan Grade'] : 0;

                //$nominal +=(($grade +$gapok+$tunjangan)-$potongan); 
                $no++;
                $sheet->setCellValue('A' . $rows, $list_karyawan->norek);
                $sheet->setCellValue('B' . $rows, '+');
                $sheet->setCellValue('C' . $rows, (($grade + $gapok + $tunjangan) - $potongan));
                $sheet->setCellValue('D' . $rows, 'C');
                $sheet->setCellValue('E' . $rows, $no);
                $sheet->setCellValue('F' . $rows, $list_karyawan->nama_lengkap);
                $sheet->setCellValue('G' . $rows, '');
                $sheet->setCellValue('H' . $rows, $list_karyawan->bank);
                $nominal += (($grade + $gapok + $tunjangan) - $potongan);

                //echo substr($absen->a,0,5);die;
                $rows++;
            }
            $sheet->setCellValue('A' . $rows, 'Jumlah');
            $sheet->setCellValue('B' . $rows, '');
            $sheet->setCellValue('C' . $rows, $nominal);
            $sheet->setCellValue('D' . $rows, '');
            $sheet->setCellValue('E' . $rows, '');
            $sheet->setCellValue('F' . $rows, '');
            $sheet->setCellValue('G' . $rows, '');
            $sheet->setCellValue('H' . $rows, '');
        }

        if (isset($hisentitas->nama))
            $nama = $hisentitas->nama;
        else
            $nama = '';

        $type = 'xlsx';
        $fileName = "Ajuan Entitas  " . $nama . ' ' . str_replace(':', '', str_replace('|', '', $perigen)) . "." . $type;
        if ($type == 'xlsx') {
            $writer = new Xlsx($spreadsheet);
        } else if ($type == 'xls') {
            $writer = new Xls($spreadsheet);
        }
        $writer->save("export/" . $fileName);
        header("Content-Type: application/vnd.ms-excel");
        return redirect(url('/') . "/export/" . $fileName);
    }
    public function exportsAjuan_pekanan($param)
    {
        //echo 'hallooo';die;
        $data = $param['data'];
        $user = $param['user'];
        $help = $param['help'];
        $entitas = $param['entitas'];
        $id_prl = $param['id_prl'];
        $hisentitas = $param['hisentitas'];

        $perigen = $param['perigen'];
        $periode = $param['periode'];
        $periode_absen = $param['periode_absen'];
        $request = $param['request'];
        $list_karyawan = $param['list_karyawan'];


        $help = new Helper_function();
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getColumnDimension('B')->setAutoSize(true);
        $sheet->getColumnDimension('C')->setAutoSize(true);
        $sheet->getColumnDimension('D')->setAutoSize(true);
        $sheet->getColumnDimension('E')->setAutoSize(true);
        $sheet->getColumnDimension('F')->setAutoSize(true);
        $sheet->getColumnDimension('G')->setAutoSize(true);
        $sheet->getColumnDimension('H')->setAutoSize(true);
        $sheet->getColumnDimension('I')->setAutoSize(true);
        $sheet->getColumnDimension('J')->setAutoSize(true);
        $sheet->getColumnDimension('K')->setAutoSize(true);
        $sheet->getColumnDimension('L')->setAutoSize(true);
        $sheet->getColumnDimension('M')->setAutoSize(true);
        $sheet->getColumnDimension('N')->setAutoSize(true);
        $sheet->getColumnDimension('O')->setAutoSize(true);
        $sheet->getColumnDimension('P')->setAutoSize(true);
        $sheet->getColumnDimension('Q')->setAutoSize(true);
        $sheet->getColumnDimension('R')->setAutoSize(true);
        $sheet->getColumnDimension('S')->setAutoSize(true);
        $sheet->getColumnDimension('T')->setAutoSize(true);
        $sheet->getColumnDimension('U')->setAutoSize(true);
        $sheet->getColumnDimension('V')->setAutoSize(true);
        $sheet->getColumnDimension('W')->setAutoSize(true);
        $sheet->getColumnDimension('X')->setAutoSize(true);
        $sheet->getColumnDimension('Y')->setAutoSize(true);
        $sheet->getColumnDimension('Z')->setAutoSize(true);
        $sheet->getColumnDimension('AA')->setAutoSize(true);
        $sheet->getColumnDimension('AB')->setAutoSize(true);
        $sheet->getColumnDimension('AC')->setAutoSize(true);
        $sheet->getColumnDimension('AD')->setAutoSize(true);
        $sheet->getColumnDimension('AE')->setAutoSize(true);
        $sheet->getColumnDimension('AF')->setAutoSize(true);
        $sheet->getColumnDimension('AG')->setAutoSize(true);
        $sheet->getColumnDimension('AH')->setAutoSize(true);
        $sheet->getColumnDimension('AI')->setAutoSize(true);
        $sheet->getColumnDimension('AJ')->setAutoSize(true);
        $sheet->getColumnDimension('AK')->setAutoSize(true);
        $sheet->getColumnDimension('AL')->setAutoSize(true);
        $sheet->getColumnDimension('AM')->setAutoSize(true);
        $sheet->getColumnDimension('AN')->setAutoSize(true);


        $i = 0;



        $sheet->setCellValue($help->toAlpha($i) . '1', 'REKENING');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '1', 'PLUS');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '1', 'NOMINAL');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '1', 'CD');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '1', 'NO');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '1', 'NAMA');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '1', 'KETERANGAN');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '1', 'BANK');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '1', 'ENTITAS');
        $i++;


        $rows = 2;



        if (!empty($list_karyawan)) {

            $no = 0;
            $nominal = 0;
            foreach ($list_karyawan as $list_karyawan) {
                $total_all = 0;
                $help->rupiah(isset($data[$list_karyawan->p_karyawan_id]['Lembur']) ? $lembur = $data[$list_karyawan->p_karyawan_id]['Lembur'] : $lembur = 0);
                $help->rupiah(isset($data[$list_karyawan->p_karyawan_id]['Tunjangan BPJS Kesehatan']) ? $tunkes = $data[$list_karyawan->p_karyawan_id]['Tunjangan BPJS Kesehatan'] : $tunkes = 0);
                $help->rupiah(isset($data[$list_karyawan->p_karyawan_id]['Tunjangan BPJS Ketenaga Kerjaan']) ? $tunket = $data[$list_karyawan->p_karyawan_id]['Tunjangan BPJS Ketenaga Kerjaan'] : $tunket = 0);
                $help->rupiah(isset($data[$list_karyawan->p_karyawan_id]['Koreksi(+)']) ? $korekplus = $data[$list_karyawan->p_karyawan_id]['Koreksi(+)'] : $korekplus = 0);
                $help->rupiah(isset($data[$list_karyawan->p_karyawan_id]['Tunjangan Kost']) ? $tunkost = $data[$list_karyawan->p_karyawan_id]['Tunjangan Kost']    : $tunkost = 0);
                $help->rupiah($tunjangan = $tunkost + $korekplus + $tunket + $tunkes + $lembur);

                $help->rupiah(isset($data[$list_karyawan->p_karyawan_id]['Potongan Telat']) ? $telat = $data[$list_karyawan->p_karyawan_id]['Potongan Telat'] : $telat = 0);
                $help->rupiah(isset($data[$list_karyawan->p_karyawan_id]['Potongan Absen']) ? $absen = $data[$list_karyawan->p_karyawan_id]['Potongan Absen'] : $absen = 0);
                $help->rupiah(isset($data[$list_karyawan->p_karyawan_id]['Sewa Kost']) ? $sewakost = $data[$list_karyawan->p_karyawan_id]['Sewa Kost'] : $sewakost = 0);
                $help->rupiah(isset($data[$list_karyawan->p_karyawan_id]['Iuran BPJS Kesehatan']) ? $ibpjs = $data[$list_karyawan->p_karyawan_id]['Iuran BPJS Kesehatan'] : $ibpjs = 0);
                $help->rupiah(isset($data[$list_karyawan->p_karyawan_id]['Iuran BPJS Ketenaga Kerjaan']) ? $ibpjt = $data[$list_karyawan->p_karyawan_id]['Iuran BPJS Ketenaga Kerjaan'] : $ibpjt = 0);
                $help->rupiah(isset($data[$list_karyawan->p_karyawan_id]['Zakat']) ? $zakat = $data[$list_karyawan->p_karyawan_id]['Zakat'] : $zakat = 0);
                $help->rupiah(isset($data[$list_karyawan->p_karyawan_id]['Infaq']) ? $infaq = $data[$list_karyawan->p_karyawan_id]['Infaq'] : $infaq = 0);
                $help->rupiah(isset($data[$list_karyawan->p_karyawan_id]['Koreksi(-)']) ? $korekmin = $data[$list_karyawan->p_karyawan_id]['Koreksi(-)'] : $korekmin = 0);
                $help->rupiah(isset($data[$list_karyawan->p_karyawan_id]['Simpanan Wajib KKB']) ? $kkp = $data[$list_karyawan->p_karyawan_id]['Simpanan Wajib KKB'] : $kkp = 0);
                $help->rupiah(isset($data[$list_karyawan->p_karyawan_id]['Potongan Koperasi Asa']) ? $asa = $data[$list_karyawan->p_karyawan_id]['Potongan Koperasi Asa'] : $asa = 0);
                isset($data[$list_karyawan->p_karyawan_id]['Potongan Fingerprint']) ? $finger = $data[$list_karyawan->p_karyawan_id]['Potongan Fingerprint'] : $finger = 0;
                $help->rupiah(isset($data[$list_karyawan->p_karyawan_id]['Pajak']) ? $pajak = $data[$list_karyawan->p_karyawan_id]['Pajak'] : $pajak = 0);
                $help->rupiah2(isset($data[$list_karyawan->p_karyawan_id]['Potongan Pulang Mendahului']) ? $potpm = $data[$list_karyawan->p_karyawan_id]['Potongan Pulang Mendahului'] : $potpm = 0);
                $help->rupiah($potongan = $telat + $finger + $sewakost + $zakat + $infaq + $korekmin + $kkp + $asa + $potpm);
                $ha = isset($data[$list_karyawan->p_karyawan_id]['Hari Absen']) ? $data[$list_karyawan->p_karyawan_id]['Hari Absen'] : 0;
                $uh = isset($data[$list_karyawan->p_karyawan_id]['Upah Harian']) ? $data[$list_karyawan->p_karyawan_id]['Upah Harian'] : 0;

                //$grade= isset($data[$list_karyawan->p_karyawan_id]['Gaji Pokok'])?$data[$list_karyawan->p_karyawan_id]['Gaji Pokok']:0;
                $gapok = $ha * $uh;
                $no++;
                $sheet->setCellValue('A' . $rows, $list_karyawan->norek);
                $sheet->setCellValue('B' . $rows, '+');
                $sheet->setCellValue('C' . $rows, (($gapok + $tunjangan) - $potongan));
                $sheet->setCellValue('D' . $rows, 'C');
                $sheet->setCellValue('E' . $rows, $no);
                $sheet->setCellValue('F' . $rows, $list_karyawan->nama_lengkap);
                $sheet->setCellValue('G' . $rows, '');
                $sheet->setCellValue('H' . $rows, $list_karyawan->bank);
                $sheet->setCellValue('I' . $rows, $list_karyawan->nmlokasi);
                $nominal += (($gapok + $tunjangan) - $potongan);

                //echo substr($absen->a,0,5);die;
                $rows++;
            }
            $sheet->setCellValue('A' . $rows, 'Jumlah');
            $sheet->setCellValue('B' . $rows, '');
            $sheet->setCellValue('C' . $rows, $nominal);
            $sheet->setCellValue('D' . $rows, '');
            $sheet->setCellValue('E' . $rows, '');
            $sheet->setCellValue('F' . $rows, '');
            $sheet->setCellValue('G' . $rows, '');
            $sheet->setCellValue('H' . $rows, '');
        }

        if (isset($hisentitas->nama))
            $nama = $hisentitas->nama;
        else
            $nama = '';

        $type = 'xlsx';
        $fileName = "Ajuan Entitas  " . $nama . ' ' . str_replace(':', '', str_replace('|', '', $perigen)) . "." . $type;
        if ($type == 'xlsx') {
            $writer = new Xlsx($spreadsheet);
        } else if ($type == 'xls') {
            $writer = new Xls($spreadsheet);
        }
        $writer->save("export/" . $fileName);
        header("Content-Type: application/vnd.ms-excel");
        return redirect(url('/') . "/export/" . $fileName);
    }
    public function exportsPresensi($param)
    {

        $data = $param['data'];
        $user = $param['user'];
        $help = $param['help'];
        $id_prl = $param['id_prl'];
        $periode = $param['periode'];
        $periode_absen = $param['periode_absen'];
        $request = $param['request'];
        $list_karyawan = $param['list_karyawan'];
        $hisentitas = $param['hisentitas'];
        //$hisentitas = $hisentitas[0];
        $perigen = $param['perigen'];


        $help = new Helper_function();
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'NAMA PEGAWAI');
        $sheet->setCellValue('B1', 'Masa Kerja');
        $sheet->setCellValue('C1', 'THP');
        $i = 3;



        $sheet->setCellValue($help->toAlpha($i) . '1', 'PANGKAT');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '1', 'Total');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '1', 'DATA ABSENSI');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '1', '');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '1', '');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '1', '');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '1', '');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '1', '');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '1', 'POTONGAN ABSEN');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '1', '');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '1', '');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '1', 'JAM LEMBUR HARI KERJA');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '1', '');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '1', '');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '1', '');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '1', 'Jumlah');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '1', 'JAM LEMBUR HARI LIBUR');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '1', '');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '1', '');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '1', '');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '1', 'Jumlah');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '1', 'Total Lembur');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '1', '');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '1', 'TERLAMBAT');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '1', '');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '1', 'Potongan Fingerprint');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '1', '');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '1', 'Total');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '1', 'cek');
        $i++;

        $sheet->setCellValue('A2', '');
        $sheet->setCellValue('B2', '');
        $sheet->setCellValue('C2', '');
        $i = 3;
        $sheet->setCellValue($help->toAlpha($i) . '2', '');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '2', '');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '2', 'Sakit');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '2', 'IHK');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '2', 'CUTI');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '2', 'IPG');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '2', 'TK');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '2', 'Total');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '2', 'IPG');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '2', 'TK');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '2', 'Total');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '2', '1 jam');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '2', '>=2 jam');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '2', 'COUNT');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '2', 'SUM');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '2', '');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '2', '8 jam');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '2', '9 jam');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '2', '>=10 jam');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '2', 'TOTAL');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '2', '');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '2', 'Jam');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '2', 'Jumlah');
        $i++;

        $sheet->setCellValue($help->toAlpha($i) . '2', 'JUMLAH');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '2', 'TOTAL');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '2', 'JUMLAH');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '2', 'TOTAL');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '2', '');
        $rows = 3;

        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getColumnDimension('B')->setAutoSize(true);
        $sheet->getColumnDimension('C')->setAutoSize(true);
        $sheet->getColumnDimension('D')->setAutoSize(true);
        $sheet->getColumnDimension('E')->setAutoSize(true);
        $sheet->getColumnDimension('F')->setAutoSize(true);
        $sheet->getColumnDimension('G')->setAutoSize(true);
        $sheet->getColumnDimension('H')->setAutoSize(true);
        $sheet->getColumnDimension('I')->setAutoSize(true);
        $sheet->getColumnDimension('J')->setAutoSize(true);
        $sheet->getColumnDimension('K')->setAutoSize(true);
        $sheet->getColumnDimension('L')->setAutoSize(true);
        $sheet->getColumnDimension('M')->setAutoSize(true);
        $sheet->getColumnDimension('N')->setAutoSize(true);
        $sheet->getColumnDimension('O')->setAutoSize(true);
        $sheet->getColumnDimension('P')->setAutoSize(true);
        $sheet->getColumnDimension('Q')->setAutoSize(true);
        $sheet->getColumnDimension('R')->setAutoSize(true);
        $sheet->getColumnDimension('S')->setAutoSize(true);
        $sheet->getColumnDimension('T')->setAutoSize(true);
        $sheet->getColumnDimension('U')->setAutoSize(true);
        $sheet->getColumnDimension('V')->setAutoSize(true);
        $sheet->getColumnDimension('W')->setAutoSize(true);
        $sheet->getColumnDimension('X')->setAutoSize(true);
        $sheet->getColumnDimension('Y')->setAutoSize(true);
        $sheet->getColumnDimension('Z')->setAutoSize(true);
        $sheet->getColumnDimension('AA')->setAutoSize(true);
        $sheet->getColumnDimension('AB')->setAutoSize(true);
        $sheet->getColumnDimension('AC')->setAutoSize(true);
        $sheet->getColumnDimension('AD')->setAutoSize(true);
        $sheet->getColumnDimension('AE')->setAutoSize(true);
        $sheet->getColumnDimension('AF')->setAutoSize(true);
        $sheet->getColumnDimension('AG')->setAutoSize(true);
        $sheet->getColumnDimension('AH')->setAutoSize(true);
        $sheet->getColumnDimension('AI')->setAutoSize(true);
        $sheet->getColumnDimension('AJ')->setAutoSize(true);
        $sheet->getColumnDimension('AK')->setAutoSize(true);
        $sheet->getColumnDimension('AL')->setAutoSize(true);
        $sheet->getColumnDimension('AM')->setAutoSize(true);
        $sheet->getColumnDimension('AN')->setAutoSize(true);

        if (!empty($list_karyawan)) {

            foreach ($list_karyawan as $list_karyawan) {
                $total_all = 0;
                $help->rupiah(isset($data[$list_karyawan->p_karyawan_id]['Lembur']) ? $lembur = $data[$list_karyawan->p_karyawan_id]['Lembur'] : $lembur = 0);
                $help->rupiah(isset($data[$list_karyawan->p_karyawan_id]['Tunjangan BPJS Kesehatan']) ? $tunkes = $data[$list_karyawan->p_karyawan_id]['Tunjangan BPJS Kesehatan'] : $tunkes = 0);
                $help->rupiah(isset($data[$list_karyawan->p_karyawan_id]['Tunjangan BPJS Ketenaga Kerjaan']) ? $tunket = $data[$list_karyawan->p_karyawan_id]['Tunjangan BPJS Ketenaga Kerjaan'] : $tunket = 0);
                $help->rupiah(isset($data[$list_karyawan->p_karyawan_id]['Koreksi(+)']) ? $korekplus = $data[$list_karyawan->p_karyawan_id]['Koreksi(+)'] : $korekplus = 0);
                $help->rupiah(isset($data[$list_karyawan->p_karyawan_id]['Tunjangan Kost']) ? $tunkost = $data[$list_karyawan->p_karyawan_id]['Tunjangan Kost']    : $tunkost = 0);
                $help->rupiah($tunjangan = $tunkost + $korekplus + $tunket + $tunkes + $lembur);

                $help->rupiah(isset($data[$list_karyawan->p_karyawan_id]['Potongan Telat/ Ijin']) ? $telat = $data[$list_karyawan->p_karyawan_id]['Potongan Telat/ Ijin'] : $telat = 0);
                $help->rupiah(isset($data[$list_karyawan->p_karyawan_id]['Potongan Absen']) ? $absen = $data[$list_karyawan->p_karyawan_id]['Potongan Absen'] : $absen = 0);
                $help->rupiah(isset($data[$list_karyawan->p_karyawan_id]['Sewa Kost']) ? $sewakost = $data[$list_karyawan->p_karyawan_id]['Sewa Kost'] : $sewakost = 0);
                $help->rupiah(isset($data[$list_karyawan->p_karyawan_id]['Iuran BPJS Kesehatan']) ? $ibpjs = $data[$list_karyawan->p_karyawan_id]['Iuran BPJS Kesehatan'] : $ibpjs = 0);
                $help->rupiah(isset($data[$list_karyawan->p_karyawan_id]['Iuran BPJS Ketenaga Kerjaan']) ? $ibpjt = $data[$list_karyawan->p_karyawan_id]['Iuran BPJS Ketenaga Kerjaan'] : $ibpjt = 0);
                $help->rupiah(isset($data[$list_karyawan->p_karyawan_id]['Zakat']) ? $zakat = $data[$list_karyawan->p_karyawan_id]['Zakat'] : $zakat = 0);
                $help->rupiah(isset($data[$list_karyawan->p_karyawan_id]['Infaq']) ? $infaq = $data[$list_karyawan->p_karyawan_id]['Infaq'] : $infaq = 0);
                $help->rupiah(isset($data[$list_karyawan->p_karyawan_id]['Koreksi(-)']) ? $korekmin = $data[$list_karyawan->p_karyawan_id]['Koreksi(-)'] : $korekmin = 0);
                $help->rupiah(isset($data[$list_karyawan->p_karyawan_id]['Simpanan Wajib KKB']) ? $kkp = $data[$list_karyawan->p_karyawan_id]['Simpanan Wajib KKB'] : $kkp = 0);
                $help->rupiah(isset($data[$list_karyawan->p_karyawan_id]['Potongan Koperasi Asa']) ? $asa = $data[$list_karyawan->p_karyawan_id]['Potongan Koperasi Asa'] : $asa = 0);
                $help->rupiah(isset($data[$list_karyawan->p_karyawan_id]['Pajak']) ? $pajak = $data[$list_karyawan->p_karyawan_id]['Pajak'] : $pajak = 0);
                $help->rupiah($potongan = $telat + $absen + $sewakost + $ibpjs + $ibpjt + $zakat + $infaq + $korekmin + $kkp + $asa + $pajak);
                $grade = isset($data[$list_karyawan->p_karyawan_id]['Gaji Pokok']) ? $data[$list_karyawan->p_karyawan_id]['Gaji Pokok'] : 0;
                $gapok = isset($data[$list_karyawan->p_karyawan_id]['Tunjangan Grade']) ? $data[$list_karyawan->p_karyawan_id]['Tunjangan Grade'] : 0;

                $sheet->setCellValue('A' . $rows, $list_karyawan->nama_lengkap);
                $sheet->setCellValue('B' . $rows, $list_karyawan->umur);
                $sheet->setCellValue('C' . $rows, ($grade + $gapok + $tunjangan) - $potongan);
                $sheet->setCellValue('D' . $rows, $list_karyawan->nmpangkat);
                $i = 4;


                $sheet->setCellValue($help->toAlpha($i) . $rows, ($absen = isset($data[$list_karyawan->p_karyawan_id]['Hari Absen']) ? $data[$list_karyawan->p_karyawan_id]['Hari Absen'] : 0));
                $i++;
                $sheet->setCellValue($help->toAlpha($i) . $rows, ($sakit = isset($data[$list_karyawan->p_karyawan_id]['Sakit']) ? $data[$list_karyawan->p_karyawan_id]['Sakit'] : 0));
                $i++;

                $sheet->setCellValue($help->toAlpha($i) . $rows, ($ihk = isset($data[$list_karyawan->p_karyawan_id]['Izin Hitung Kerja']) ? $data[$list_karyawan->p_karyawan_id]['Izin Hitung Kerja'] : 0));
                $i++;
                $sheet->setCellValue($help->toAlpha($i) . $rows, ($cuti = isset($data[$list_karyawan->p_karyawan_id]['Cuti']) ? $data[$list_karyawan->p_karyawan_id]['Cuti'] : 0));
                $i++;
                $sheet->setCellValue($help->toAlpha($i) . $rows, ($ipg = isset($data[$list_karyawan->p_karyawan_id]['Izin Perjalanan Dinas']) ? $data[$list_karyawan->p_karyawan_id]['Izin Perjalanan Dinas'] : 0));
                $i++;
                $sheet->setCellValue($help->toAlpha($i) . $rows, ($tk = isset($data[$list_karyawan->p_karyawan_id]['Tanpa Keterangan']) ? $data[$list_karyawan->p_karyawan_id]['Tanpa Keterangan'] : 0));
                $i++;
                $sheet->setCellValue($help->toAlpha($i) . $rows, ($sakit + $ihk + $cuti + $ipg + $tk));
                $i++;


                $sheet->setCellValue($help->toAlpha($i) . $rows, ($potizin = isset($data[$list_karyawan->p_karyawan_id]['Potongan Izin']) ? $data[$list_karyawan->p_karyawan_id]['Potongan Izin'] : 0));
                $i++;
                $sheet->setCellValue($help->toAlpha($i) . $rows, ($potabsen = isset($data[$list_karyawan->p_karyawan_id]['Potongan Absen']) ? $data[$list_karyawan->p_karyawan_id]['Potongan Absen'] : 0));
                $i++;
                $sheet->setCellValue($help->toAlpha($i) . $rows, ($potizin + $potabsen));
                $i++;
                $sheet->setCellValue($help->toAlpha($i) . $rows, ($lembur1 = isset($data[$list_karyawan->p_karyawan_id]['Lembur 1 Jam']) ? $data[$list_karyawan->p_karyawan_id]['Lembur 1 Jam'] : 0));
                $i++;
                $sheet->setCellValue($help->toAlpha($i) . $rows, ($lembur2 = isset($data[$list_karyawan->p_karyawan_id]['Lembur >=2 Jam']) ? $data[$list_karyawan->p_karyawan_id]['Lembur >=2 Jam'] : 0));
                $i++;
                $sheet->setCellValue($help->toAlpha($i) . $rows, (isset($data[$list_karyawan->p_karyawan_id]['COUNT Kerja']) ? $data[$list_karyawan->p_karyawan_id]['COUNT Kerja'] : 0));
                $i++;
                $sheet->setCellValue($help->toAlpha($i) . $rows, (isset($data[$list_karyawan->p_karyawan_id]['SUM Kerja']) ? $data[$list_karyawan->p_karyawan_id]['SUM Kerja'] : 0));
                $i++;

                $sheet->setCellValue($help->toAlpha($i) . $rows, (isset($data[$list_karyawan->p_karyawan_id]['Jumlah Lembur Kerja']) ? $data[$list_karyawan->p_karyawan_id]['Jumlah Lembur Kerja'] : 0));
                $i++;
                $sheet->setCellValue($help->toAlpha($i) . $rows, ($lembur8 = isset($data[$list_karyawan->p_karyawan_id]['Lembur 8 Jam']) ? $lembur = $data[$list_karyawan->p_karyawan_id]['Lembur 8 Jam'] : $lembur = 0));
                $i++;
                $sheet->setCellValue($help->toAlpha($i) . $rows, ($lembur9 = isset($data[$list_karyawan->p_karyawan_id]['Lembur 9 Jam']) ? $lembur = $data[$list_karyawan->p_karyawan_id]['Lembur 9 Jam'] : $lembur = 0));
                $i++;
                $sheet->setCellValue($help->toAlpha($i) . $rows, ($lembur10 = isset($data[$list_karyawan->p_karyawan_id]['Lembur >=10 Jam']) ? $data[$list_karyawan->p_karyawan_id]['Lembur >=10 Jam'] : 0));
                $i++;

                $sheet->setCellValue($help->toAlpha($i) . $rows, $lembur8 + $lembur10 + $lembur9);
                $i++;
                $sheet->setCellValue($help->toAlpha($i) . $rows, ((isset($data[$list_karyawan->p_karyawan_id]['Jumlah Lembur Libur']) ? $data[$list_karyawan->p_karyawan_id]['Jumlah Lembur Libur'] : 0)));
                $i++;
                $sheet->setCellValue($help->toAlpha($i) . $rows, (isset($data[$list_karyawan->p_karyawan_id]['Jam Lembur']) ? $data[$list_karyawan->p_karyawan_id]['Jam Lembur'] : 0));
                $i++;
                $sheet->setCellValue($help->toAlpha($i) . $rows, (($lembur = isset($data[$list_karyawan->p_karyawan_id]['Lembur']) ? $data[$list_karyawan->p_karyawan_id]['Lembur'] : 0)));
                $i++;
                $sheet->setCellValue($help->toAlpha($i) . $rows, (isset($data[$list_karyawan->p_karyawan_id]['Terlambat']) ? $data[$list_karyawan->p_karyawan_id]['Terlambat'] : 0));
                $i++;
                $sheet->setCellValue($help->toAlpha($i) . $rows, (($telat = isset($data[$list_karyawan->p_karyawan_id]['Potongan Telat']) ? $data[$list_karyawan->p_karyawan_id]['Potongan Telat'] : 0)));
                $i++;
                $sheet->setCellValue($help->toAlpha($i) . $rows, (isset($data[$list_karyawan->p_karyawan_id]['Fingerprint']) ? $data[$list_karyawan->p_karyawan_id]['Fingerprint'] : 0));
                $i++;
                $sheet->setCellValue($help->toAlpha($i) . $rows, (($finger = isset($data[$list_karyawan->p_karyawan_id]['Total Fingerprint']) ? $data[$list_karyawan->p_karyawan_id]['Potongan Fingerprint'] : 0)));
                $i++;
                $sheet->setCellValue($help->toAlpha($i) . $rows, (($telat + $finger)));
                $i++;
                $sheet->setCellValue($help->toAlpha($i) . $rows, ($absen + $sakit + $ihk + $cuti + $ipg + $tk));
                $i++;

                //echo substr($absen->a,0,5);die;
                $rows++;
            }
        }


        $type = 'xlsx';
        $fileName = "Presensi Gaji " . str_replace(':', '', str_replace('|', '', $perigen)) . "." . $type;
        if ($type == 'xlsx') {
            $writer = new Xlsx($spreadsheet);
        } else if ($type == 'xls') {
            $writer = new Xls($spreadsheet);
        }
        $writer->save("export/" . $fileName);
        header("Content-Type: application/vnd.ms-excel");
        return redirect(url('/') . "/export/" . $fileName);
    }
    public function exportsPresensi_Pekanan($param)
    {

        $data = $param['data'];
        $user = $param['user'];
        $help = $param['help'];
        $id_prl = $param['id_prl'];
        $periode = $param['periode'];
        $periode_absen = $param['periode_absen'];
        $request = $param['request'];
        $list_karyawan = $param['list_karyawan'];
        $hisentitas = $param['hisentitas'];
        //$hisentitas = $hisentitas[0];
        $perigen = $param['perigen'];


        $help = new Helper_function();
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'NAMA PEGAWAI');
        $sheet->setCellValue('B1', 'GAJI TOTAL PERBULAN');
        $sheet->setCellValue('C1', 'TOTAL KEHADIRAN');
        $i = 3;



        $sheet->setCellValue($help->toAlpha($i) . '1', 'Keterangan');
        $i++; //1
        $sheet->setCellValue($help->toAlpha($i) . '1', '');
        $i++; //2
        $sheet->setCellValue($help->toAlpha($i) . '1', '');
        $i++; //3
        $sheet->setCellValue($help->toAlpha($i) . '1', '');
        $i++; //4
        $sheet->setCellValue($help->toAlpha($i) . '1', '');
        $i++; //5
        $sheet->setCellValue($help->toAlpha($i) . '1', '');
        $i++; //6
        $sheet->setCellValue($help->toAlpha($i) . '1', '');
        $i++; //7
        $sheet->setCellValue($help->toAlpha($i) . '1', '');
        $i++; //8	
        $sheet->setCellValue($help->toAlpha($i) . '1', 'JAM LEMBUR HARI KERJA');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '1', '');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '1', '');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '1', '');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '1', 'JAM LEMBUR HARI LIBUR');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '1', '');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '1', '');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '1', '');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '1', '');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '1', 'Total Lembur');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '1', 'cek');
        $i++;

        $sheet->setCellValue('A2', '');
        $sheet->setCellValue('B2', '');
        $sheet->setCellValue('C2', '');
        $i = 3;
        $sheet->setCellValue($help->toAlpha($i) . '2', 'Sakit');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '2', 'IHK');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '2', 'ALPHA');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '2', 'Terlambat');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '2', 'IDT');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '2', 'IPM');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '2', 'PM');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '2', 'Total');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '2', '1 jam');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '2', '>=2 jam');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '2', 'TOTAL');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '2', 'NOMINAL');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '2', '');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '2', '8 jam');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '2', '9 jam');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '2', '>=10 jam');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '2', 'TOTAL');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '2', 'NOMINAL');
        $i++;
        $rows = 3;

        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getColumnDimension('B')->setAutoSize(true);
        $sheet->getColumnDimension('C')->setAutoSize(true);
        $sheet->getColumnDimension('D')->setAutoSize(true);
        $sheet->getColumnDimension('E')->setAutoSize(true);
        $sheet->getColumnDimension('F')->setAutoSize(true);
        $sheet->getColumnDimension('G')->setAutoSize(true);
        $sheet->getColumnDimension('H')->setAutoSize(true);
        $sheet->getColumnDimension('I')->setAutoSize(true);
        $sheet->getColumnDimension('J')->setAutoSize(true);
        $sheet->getColumnDimension('K')->setAutoSize(true);
        $sheet->getColumnDimension('L')->setAutoSize(true);
        $sheet->getColumnDimension('M')->setAutoSize(true);
        $sheet->getColumnDimension('N')->setAutoSize(true);
        $sheet->getColumnDimension('O')->setAutoSize(true);
        $sheet->getColumnDimension('P')->setAutoSize(true);
        $sheet->getColumnDimension('Q')->setAutoSize(true);
        $sheet->getColumnDimension('R')->setAutoSize(true);
        $sheet->getColumnDimension('S')->setAutoSize(true);
        $sheet->getColumnDimension('T')->setAutoSize(true);
        $sheet->getColumnDimension('U')->setAutoSize(true);
        $sheet->getColumnDimension('V')->setAutoSize(true);
        $sheet->getColumnDimension('W')->setAutoSize(true);
        $sheet->getColumnDimension('X')->setAutoSize(true);
        $sheet->getColumnDimension('Y')->setAutoSize(true);
        $sheet->getColumnDimension('Z')->setAutoSize(true);
        $sheet->getColumnDimension('AA')->setAutoSize(true);
        $sheet->getColumnDimension('AB')->setAutoSize(true);
        $sheet->getColumnDimension('AC')->setAutoSize(true);
        $sheet->getColumnDimension('AD')->setAutoSize(true);
        $sheet->getColumnDimension('AE')->setAutoSize(true);
        $sheet->getColumnDimension('AF')->setAutoSize(true);
        $sheet->getColumnDimension('AG')->setAutoSize(true);
        $sheet->getColumnDimension('AH')->setAutoSize(true);
        $sheet->getColumnDimension('AI')->setAutoSize(true);
        $sheet->getColumnDimension('AJ')->setAutoSize(true);
        $sheet->getColumnDimension('AK')->setAutoSize(true);
        $sheet->getColumnDimension('AL')->setAutoSize(true);
        $sheet->getColumnDimension('AM')->setAutoSize(true);
        $sheet->getColumnDimension('AN')->setAutoSize(true);

        if (!empty($list_karyawan)) {

            foreach ($list_karyawan as $list_karyawan) {
                $total_all = 0;
                $help->rupiah(isset($data[$list_karyawan->p_karyawan_id]['Upah Harian']) ? $uh = $data[$list_karyawan->p_karyawan_id]['Upah Harian'] : $uh = 0);
                $help->rupiah(isset($data[$list_karyawan->p_karyawan_id]['Lembur']) ? $lembur = $data[$list_karyawan->p_karyawan_id]['Lembur'] : $lembur = 0);
                $help->rupiah(isset($data[$list_karyawan->p_karyawan_id]['Tunjangan BPJS Kesehatan']) ? $tunkes = $data[$list_karyawan->p_karyawan_id]['Tunjangan BPJS Kesehatan'] : $tunkes = 0);
                $help->rupiah(isset($data[$list_karyawan->p_karyawan_id]['Tunjangan BPJS Ketenaga Kerjaan']) ? $tunket = $data[$list_karyawan->p_karyawan_id]['Tunjangan BPJS Ketenaga Kerjaan'] : $tunket = 0);
                $help->rupiah(isset($data[$list_karyawan->p_karyawan_id]['Koreksi(+)']) ? $korekplus = $data[$list_karyawan->p_karyawan_id]['Koreksi(+)'] : $korekplus = 0);
                $help->rupiah(isset($data[$list_karyawan->p_karyawan_id]['Tunjangan Kost']) ? $tunkost = $data[$list_karyawan->p_karyawan_id]['Tunjangan Kost']    : $tunkost = 0);
                $help->rupiah($tunjangan = $tunkost + $korekplus + $tunket + $tunkes + $lembur);

                $help->rupiah(isset($data[$list_karyawan->p_karyawan_id]['Potongan Telat/ Ijin']) ? $telat = $data[$list_karyawan->p_karyawan_id]['Potongan Telat/ Ijin'] : $telat = 0);
                $help->rupiah(isset($data[$list_karyawan->p_karyawan_id]['Potongan Absen']) ? $absen = $data[$list_karyawan->p_karyawan_id]['Potongan Absen'] : $absen = 0);
                $help->rupiah(isset($data[$list_karyawan->p_karyawan_id]['Sewa Kost']) ? $sewakost = $data[$list_karyawan->p_karyawan_id]['Sewa Kost'] : $sewakost = 0);
                $help->rupiah(isset($data[$list_karyawan->p_karyawan_id]['Iuran BPJS Kesehatan']) ? $ibpjs = $data[$list_karyawan->p_karyawan_id]['Iuran BPJS Kesehatan'] : $ibpjs = 0);
                $help->rupiah(isset($data[$list_karyawan->p_karyawan_id]['Iuran BPJS Ketenaga Kerjaan']) ? $ibpjt = $data[$list_karyawan->p_karyawan_id]['Iuran BPJS Ketenaga Kerjaan'] : $ibpjt = 0);
                $help->rupiah(isset($data[$list_karyawan->p_karyawan_id]['Zakat']) ? $zakat = $data[$list_karyawan->p_karyawan_id]['Zakat'] : $zakat = 0);
                $help->rupiah(isset($data[$list_karyawan->p_karyawan_id]['Infaq']) ? $infaq = $data[$list_karyawan->p_karyawan_id]['Infaq'] : $infaq = 0);
                $help->rupiah(isset($data[$list_karyawan->p_karyawan_id]['Koreksi(-)']) ? $korekmin = $data[$list_karyawan->p_karyawan_id]['Koreksi(-)'] : $korekmin = 0);
                $help->rupiah(isset($data[$list_karyawan->p_karyawan_id]['Simpanan Wajib KKB']) ? $kkp = $data[$list_karyawan->p_karyawan_id]['Simpanan Wajib KKB'] : $kkp = 0);
                $help->rupiah(isset($data[$list_karyawan->p_karyawan_id]['Potongan Koperasi Asa']) ? $asa = $data[$list_karyawan->p_karyawan_id]['Potongan Koperasi Asa'] : $asa = 0);
                $help->rupiah(isset($data[$list_karyawan->p_karyawan_id]['Pajak']) ? $pajak = $data[$list_karyawan->p_karyawan_id]['Pajak'] : $pajak = 0);
                $help->rupiah($potongan = $telat + $absen + $sewakost + $ibpjs + $ibpjt + $zakat + $infaq + $korekmin + $kkp + $asa + $pajak);
                $ihk = isset($data[$list_karyawan->p_karyawan_id]['Izin Hitung Kerja']) ? $data[$list_karyawan->p_karyawan_id]['Izin Hitung Kerja'] : 0;
                $ipg = isset($data[$list_karyawan->p_karyawan_id]['Izin Perjalanan Dinas']) ? $data[$list_karyawan->p_karyawan_id]['Izin Perjalanan Dinas'] : 0;
                $cuti = 0;


                $sheet->setCellValue('A' . $rows, $list_karyawan->nama_lengkap);
                $sheet->setCellValue('B' . $rows, $uh * 22);

                $absen = isset($data[$list_karyawan->p_karyawan_id]['Hari Absen']) ? $data[$list_karyawan->p_karyawan_id]['Hari Absen'] : 0;
                $sheet->setCellValue('C' . $rows, $absen);

                $sakit = isset($data[$list_karyawan->p_karyawan_id]['Sakit']) ? $data[$list_karyawan->p_karyawan_id]['Sakit'] : 0;
                $sheet->setCellValue('D' . $rows, $sakit);
                $i = 4;


                $sheet->setCellValue($help->toAlpha($i) . $rows, $ihk + $ipg);
                $i++;

                $tk = isset($data[$list_karyawan->p_karyawan_id]['Tanpa Keterangan']) ? $data[$list_karyawan->p_karyawan_id]['Tanpa Keterangan'] : 0;
                $sheet->setCellValue($help->toAlpha($i) . $rows, $tk);
                $i++;

                $terlambat = isset($data[$list_karyawan->p_karyawan_id]['Terlambat']) ? $data[$list_karyawan->p_karyawan_id]['Terlambat'] : 0;
                $sheet->setCellValue($help->toAlpha($i) . $rows, $terlambat);
                $i++;

                $idt = isset($data[$list_karyawan->p_karyawan_id]['Izin Datang Terlambat']) ? $data[$list_karyawan->p_karyawan_id]['Izin Datang Terlambat'] : 0;
                $sheet->setCellValue($help->toAlpha($i) . $rows, $idt);
                $i++;

                $ipm = isset($data[$list_karyawan->p_karyawan_id]['Izin Pulang Mendahului ']) ? $data[$list_karyawan->p_karyawan_id]['Izin Pulang Mendahului '] : 0;
                $sheet->setCellValue($help->toAlpha($i) . $rows, $ipm);
                $i++;

                $pm = isset($data[$list_karyawan->p_karyawan_id]['Pulang Mendahului Tanpa Izin']) ? $data[$list_karyawan->p_karyawan_id]['Pulang Mendahului Tanpa Izin'] : 0;
                $sheet->setCellValue($help->toAlpha($i) . $rows, $pm);
                $i++;


                $sheet->setCellValue($help->toAlpha($i) . $rows, ($tk + $ihk + $ipg + $sakit));
                $i++;

                $lembur1 = isset($data[$list_karyawan->p_karyawan_id]['Lembur 1 Jam']) ? $data[$list_karyawan->p_karyawan_id]['Lembur 1 Jam'] : 0;
                $sheet->setCellValue($help->toAlpha($i) . $rows, ($lembur1));
                $i++;

                $lembur2 = isset($data[$list_karyawan->p_karyawan_id]['Lembur >=2 Jam']) ? $data[$list_karyawan->p_karyawan_id]['Lembur >=2 Jam'] : 0;
                $sheet->setCellValue($help->toAlpha($i) . $rows, ($lembur2));
                $i++;

                $total_lembur_kerja = isset($data[$list_karyawan->p_karyawan_id]['SUM Kerja']) ? $data[$list_karyawan->p_karyawan_id]['SUM Kerja'] : 0;
                $sheet->setCellValue($help->toAlpha($i) . $rows, ($total_lembur_kerja));
                $i++;

                $nominal_lembur_kerja = isset($data[$list_karyawan->p_karyawan_id]['Jumlah Lembur Kerja']) ? $data[$list_karyawan->p_karyawan_id]['Jumlah Lembur Kerja'] : 0;
                $sheet->setCellValue($help->toAlpha($i) . $rows, ($nominal_lembur_kerja));
                $i++;

                $lembur8 = isset($data[$list_karyawan->p_karyawan_id]['Lembur 8 Jam']) ? $lembur8 = $data[$list_karyawan->p_karyawan_id]['Lembur 8 Jam'] : $lembur8 = 0;
                $sheet->setCellValue($help->toAlpha($i) . $rows, ($lembur8));
                $i++;

                $lembur9 = isset($data[$list_karyawan->p_karyawan_id]['Lembur 9 Jam']) ? $lembur9 = $data[$list_karyawan->p_karyawan_id]['Lembur 9 Jam'] : $lembur9 = 0;
                $sheet->setCellValue($help->toAlpha($i) . $rows, ($lembur9));
                $i++;

                $lembur10 = isset($data[$list_karyawan->p_karyawan_id]['Lembur >=10 Jam']) ? $data[$list_karyawan->p_karyawan_id]['Lembur >=10 Jam'] : 0;
                $sheet->setCellValue($help->toAlpha($i) . $rows, ($lembur10));
                $i++;

                $sheet->setCellValue($help->toAlpha($i) . $rows, ($lembur8 + $lembur10 + $lembur9));
                $i++;
                $sheet->setCellValue($help->toAlpha($i) . $rows, (isset($data[$list_karyawan->p_karyawan_id]['Jumlah Lembur Libur']) ? $data[$list_karyawan->p_karyawan_id]['Jumlah Lembur Libur'] : 0));
                $i++;

                $sheet->setCellValue($help->toAlpha($i) . $rows, ($lembur));
                $i++;
                $sheet->setCellValue($help->toAlpha($i) . $rows, ($absen + $sakit + $ihk + $cuti + $ipg + $tk));
                $i++;

                //echo substr($absen->a,0,5);die;
                $rows++;
            }
        }


        $type = 'xlsx';
        $fileName = "Presensi Gaji " . str_replace(':', '', str_replace('|', '', $perigen)) . "." . $type;
        if ($type == 'xlsx') {
            $writer = new Xlsx($spreadsheet);
        } else if ($type == 'xls') {
            $writer = new Xls($spreadsheet);
        }
        $writer->save("export/" . $fileName);
        header("Content-Type: application/vnd.ms-excel");
        return redirect(url('/') . "/export/" . $fileName);
    }
    public function exportsPajak($param)
    {
        $help = new Helper_function();
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $i = 0;

        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getColumnDimension('B')->setAutoSize(true);
        $sheet->getColumnDimension('C')->setAutoSize(true);
        $sheet->getColumnDimension('D')->setAutoSize(true);
        $sheet->getColumnDimension('E')->setAutoSize(true);
        $sheet->getColumnDimension('F')->setAutoSize(true);
        $sheet->getColumnDimension('G')->setAutoSize(true);
        $sheet->getColumnDimension('H')->setAutoSize(true);
        $sheet->getColumnDimension('I')->setAutoSize(true);
        $sheet->getColumnDimension('J')->setAutoSize(true);
        $sheet->getColumnDimension('K')->setAutoSize(true);
        $sheet->getColumnDimension('L')->setAutoSize(true);
        $sheet->getColumnDimension('M')->setAutoSize(true);
        $sheet->getColumnDimension('N')->setAutoSize(true);
        $sheet->getColumnDimension('O')->setAutoSize(true);
        $sheet->getColumnDimension('P')->setAutoSize(true);
        $sheet->getColumnDimension('Q')->setAutoSize(true);
        $sheet->getColumnDimension('R')->setAutoSize(true);
        $sheet->getColumnDimension('S')->setAutoSize(true);
        $sheet->getColumnDimension('T')->setAutoSize(true);
        $sheet->getColumnDimension('U')->setAutoSize(true);
        $sheet->getColumnDimension('V')->setAutoSize(true);
        $sheet->getColumnDimension('W')->setAutoSize(true);
        $sheet->getColumnDimension('X')->setAutoSize(true);
        $sheet->getColumnDimension('Y')->setAutoSize(true);
        $sheet->getColumnDimension('Z')->setAutoSize(true);
        $sheet->getColumnDimension('AA')->setAutoSize(true);
        $sheet->getColumnDimension('AB')->setAutoSize(true);
        $sheet->getColumnDimension('AC')->setAutoSize(true);
        $sheet->getColumnDimension('AD')->setAutoSize(true);
        $sheet->getColumnDimension('AE')->setAutoSize(true);
        $sheet->getColumnDimension('AF')->setAutoSize(true);
        $sheet->getColumnDimension('AG')->setAutoSize(true);
        $sheet->getColumnDimension('AH')->setAutoSize(true);
        $sheet->getColumnDimension('AI')->setAutoSize(true);
        $sheet->getColumnDimension('AJ')->setAutoSize(true);
        $sheet->getColumnDimension('AK')->setAutoSize(true);
        $sheet->getColumnDimension('AL')->setAutoSize(true);
        $sheet->getColumnDimension('AM')->setAutoSize(true);
        $sheet->getColumnDimension('AN')->setAutoSize(true);

        $data = $param['data'];
        $user = $param['user'];
        $help = $param['help'];
        $id_prl = $param['id_prl'];
        $periode = $param['periode'];
        $periode_absen = $param['periode_absen'];
        $request = $param['request'];
        $list_karyawan = $param['list_karyawan'];
        $hisentitas = $param['hisentitas'];
        //$hisentitas = $hisentitas[0];
        $perigen = $param['perigen'];





        $sheet->setCellValue($help->toAlpha($i) . '1', 'No');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '1', 'Nama');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '1', 'Umur Kerja');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '1', 'Tanggal Masuk');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '1', 'Perusahaan');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '1', 'Pajak');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '1', 'TOTAL PENDAPATAN');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '1', 'TOTAL TUNJANGAN');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '1', 'TOTAL POTONGAN');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '1', 'TOTAL');
        $i++;
        $rows = 2;


        if (!empty($list_karyawan)) {
            $no = 0;
            foreach ($list_karyawan as $list_karyawan) {
                if ($list_karyawan->pajak_onoff == 'ON') {
                    $no++;
                    $total_all = 0;

                    $i = 0;

                    $sheet->setCellValue($help->toAlpha($i) . $rows, $no);
                    $i++;
                    $sheet->setCellValue($help->toAlpha($i) . $rows, $list_karyawan->nama_lengkap);
                    $i++;
                    $sheet->setCellValue($help->toAlpha($i) . $rows, $list_karyawan->umur);
                    $i++;
                    $sheet->setCellValue($help->toAlpha($i) . $rows, $list_karyawan->tgl_awal);
                    $i++;
                    $sheet->setCellValue($help->toAlpha($i) . $rows, $list_karyawan->nmlokasi);
                    $i++;
                    $sheet->setCellValue($help->toAlpha($i) . $rows, $list_karyawan->pajak_onoff);
                    $i++;



                    isset($data[$list_karyawan->p_karyawan_id]['Gaji Pokok']) ? $grade = $data[$list_karyawan->p_karyawan_id]['Gaji Pokok'] : $grade = 0;
                    (isset($data[$list_karyawan->p_karyawan_id]['Tunjangan Grade']) ? $gapok = $data[$list_karyawan->p_karyawan_id]['Tunjangan Grade'] : $gapok = 0);

                    (isset($data[$list_karyawan->p_karyawan_id]['Lembur']) ? $lembur = $data[$list_karyawan->p_karyawan_id]['Lembur'] : $lembur = 0);
                    (isset($data[$list_karyawan->p_karyawan_id]['Tunjangan BPJS Kesehatan']) ? $tunkes = $data[$list_karyawan->p_karyawan_id]['Tunjangan BPJS Kesehatan'] : $tunkes = 0);
                    (isset($data[$list_karyawan->p_karyawan_id]['Tunjangan BPJS Ketenaga Kerjaan']) ? $tunket = $data[$list_karyawan->p_karyawan_id]['Tunjangan BPJS Ketenaga Kerjaan'] : $tunket = 0);
                    (isset($data[$list_karyawan->p_karyawan_id]['Koreksi(+)']) ? $korekplus = $data[$list_karyawan->p_karyawan_id]['Koreksi(+)'] : $korekplus = 0);
                    (isset($data[$list_karyawan->p_karyawan_id]['Tunjangan Kost']) ? $tunkost = $data[$list_karyawan->p_karyawan_id]['Tunjangan Kost']    : $tunkost = 0);
                    ($tunjangan = $tunkost + $korekplus + $tunket + $tunkes);

                    (isset($data[$list_karyawan->p_karyawan_id]['Potongan Telat']) ? $telat = $data[$list_karyawan->p_karyawan_id]['Potongan Telat'] : $telat = 0);
                    (isset($data[$list_karyawan->p_karyawan_id]['Potongan Fingerprint']) ? $potfinger = $data[$list_karyawan->p_karyawan_id]['Potongan Fingerprint'] : $potfinger = 0);
                    (isset($data[$list_karyawan->p_karyawan_id]['Potongan Pulang Mendahului']) ? $potpm = $data[$list_karyawan->p_karyawan_id]['Potongan Pulang Mendahului'] : $potpm = 0);
                    (isset($data[$list_karyawan->p_karyawan_id]['Potongan Izin']) ? $potizin = $data[$list_karyawan->p_karyawan_id]['Potongan Izin'] : $potizin = 0);
                    (isset($data[$list_karyawan->p_karyawan_id]['Potongan Absen']) ? $absen = $data[$list_karyawan->p_karyawan_id]['Potongan Absen'] : $absen = 0);
                    (isset($data[$list_karyawan->p_karyawan_id]['Sewa Kost']) ? $sewakost = $data[$list_karyawan->p_karyawan_id]['Sewa Kost'] : $sewakost = 0);
                    (isset($data[$list_karyawan->p_karyawan_id]['Iuran BPJS Kesehatan']) ? $ibpjs = $data[$list_karyawan->p_karyawan_id]['Iuran BPJS Kesehatan'] : $ibpjs = 0);
                    (isset($data[$list_karyawan->p_karyawan_id]['Iuran BPJS Ketenaga Kerjaan']) ? $ibpjt = $data[$list_karyawan->p_karyawan_id]['Iuran BPJS Ketenaga Kerjaan'] : $ibpjt = 0);
                    ($zakat = isset($data[$list_karyawan->p_karyawan_id]['Zakat']) ? $data[$list_karyawan->p_karyawan_id]['Zakat'] : 0);
                    ($infaq = isset($data[$list_karyawan->p_karyawan_id]['Infaq']) ? $data[$list_karyawan->p_karyawan_id]['Infaq'] : 0);
                    ($korekmin = isset($data[$list_karyawan->p_karyawan_id]['Koreksi(-)']) ? $korekmin = $data[$list_karyawan->p_karyawan_id]['Koreksi(-)'] : 0);
                    ($kkp = isset($data[$list_karyawan->p_karyawan_id]['Simpanan Wajib KKB']) ? $data[$list_karyawan->p_karyawan_id]['Simpanan Wajib KKB'] : 0);
                    ($asa = isset($data[$list_karyawan->p_karyawan_id]['Potongan Koperasi Asa']) ? $data[$list_karyawan->p_karyawan_id]['Potongan Koperasi Asa'] : 0);
                    ($pajak = isset($data[$list_karyawan->p_karyawan_id]['Pajak']) ? $data[$list_karyawan->p_karyawan_id]['Pajak'] : 0);

                    $sheet->setCellValue($help->toAlpha($i) . $rows, (($grade + $gapok + $lembur)));
                    $i++;
                    $sheet->setCellValue($help->toAlpha($i) . $rows, ($tunjangan));
                    $i++;
                    $sheet->setCellValue($help->toAlpha($i) . $rows, $potongan = $telat + $absen + $potizin + $potfinger + $potpm);
                    $i++;
                    $sheet->setCellValue($help->toAlpha($i) . $rows, ($grade + $gapok + $lembur + $tunjangan) - $potongan);
                    $i++;

                    //echo substr($absen->a,0,5);die;
                    $rows++;
                }
            }
        }


        $type = 'xlsx';
        $fileName = "Preview Pajak " . str_replace(':', '', str_replace('|', '', $perigen)) . "." . $type;
        if ($type == 'xlsx') {
            $writer = new Xlsx($spreadsheet);
        } else if ($type == 'xls') {
            $writer = new Xls($spreadsheet);
        }
        $writer->save("export/" . $fileName);
        header("Content-Type: application/vnd.ms-excel");
        return redirect(url('/') . "/export/" . $fileName);
    }
    }
    

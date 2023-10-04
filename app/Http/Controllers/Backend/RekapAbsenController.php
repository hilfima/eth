<?php

namespace App\Http\Controllers\Backend;

use App\rekaplembur_xls;
use App\Http\Controllers\Controller;
use App\User;
use App\Helper_function;
use Illuminate\Http\Request;
use DB;
use Auth;
use PhpOffice\PhpSpreadsheet\Reader\Xml\Style\Fill;
use Response;
use Maatwebsite\Excel\Facades\Excel;
use App\Eloquent\AbsenLog;
use App\Eloquent\Absen;
use DateTime;;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PHPExcel;
use PHPExcel_IOFactory;

class RekapAbsenController extends Controller{
  
     

	public function view(Request $request){
		$tgl_awal=date('Y-m-d');
		$tgl_akhir=date('Y-m-d');
		$tahun=date('Y');
		$bulan=date('mm');
		$periode_gajian=$request->get('periode_gajian');
		$periode_absen=$request->get('periode');
		$rekapget = $request->get('rekapget');
		//echo $periode_absen;die;
		$iduser=Auth::user()->id;
		$sqluser="SELECT p_recruitment.foto,role,p_karyawan_pekerjaan,p_karyawan_pekerjaan.m_lokasi_id,user_entitas_access FROM users
		left join m_role on m_role.m_role_id=users.role
		left join p_karyawan on p_karyawan.user_id=users.id
		left join p_karyawan_pekerjaan on p_karyawan_pekerjaan.p_karyawan_id=p_karyawan.p_karyawan_id
		left join p_recruitment on p_recruitment.p_recruitment_id=p_karyawan.p_recruitment_id
		where users.id=$iduser";
		$user=DB::connection()->select($sqluser);
        
		$sqlperiode="SELECT m_periode_absen.*,
		case when periode=1 then 'Januari'
		when periode=2 then 'Februari'
		when periode=3 then 'Maret'
		when periode=4 then 'April'
		when periode=5 then 'Mei'
		when periode=6 then 'Juni'
		when periode=7 then 'Juli'
		when periode=8 then 'Agustus'
		when periode=9 then 'September'
		when periode=10 then 'Oktober'
		when periode=11 then 'November'
		when periode=12 then 'Desember' end as bulan,
		case when type=0 then 'Pekanan' else 'Bulanan' end as tipe
		FROM m_periode_absen 
		ORDER BY tahun desc,periode desc,type";
		$periode=DB::connection()->select($sqlperiode);
		$help = new Helper_function();
		$pekanan ='';
		$periode_absen=$request->get('periode_gajian');
		
		if(($periode_absen)){
			
			$sqlperiode="SELECT * FROM m_periode_absen where periode_absen_id=$periode_absen";
			$periodetgl=DB::connection()->select($sqlperiode);
			$type = $periodetgl[0]->type;
			$where = " d.periode_gajian = ".$type; 
			$appendwhere = "and";
			if(!$type) $pekanan = '_pekanan';
			$periode_gajian=$periodetgl[0]->type;
			$tgl_awal=date('Y-m-d',strtotime($periodetgl[0]->tgl_awal));
			$tgl_akhir=date('Y-m-d',strtotime($periodetgl[0]->tgl_akhir));
			//print_r($periodetgl);
			//echo $tgl_awal;
			//echo $tgl_akhir;die;
		}
		else{
			$tgl_awal = Helper_function::tambah_tanggal(date('Y-m-d'),-7);
			$tgl_akhir = date('Y-m-d');
			$where = '';
			$appendwhere = "";
			$type	 = -1;
		}
		
		
		$id_lokasi = Auth::user()->user_entitas;
        if($id_lokasi and $id_lokasi!=-1) 
			$whereLokasi = "AND c.m_lokasi_id in($id_lokasi)";					
		else
			$whereLokasi = "";	
			
		$departemen=DB::connection()->select("select * from m_departemen where active=1 ORDER BY nama");
		$entitas=DB::connection()->select("select * from m_lokasi c where active=1 and sub_entitas=0 $whereLokasi ORDER BY nama");
		$jabatan=DB::connection()->select("select * from m_jabatan where active=1 ORDER BY nama");
		
		if($request->get('Cari')!='Generate' and !empty($request->get('Cari'))){
    		$rekap = $help->rekap_absen_optimasi($periode_absen,$tgl_awal,$tgl_akhir,$type);
    		
    		$list_karyawan = $rekap['list_karyawan'] ;
    		
    		$hari_libur = $rekap['hari_libur'] ;
    		$hari_libur_shift = $rekap['hari_libur_shift'] ;
    	     $tgl_awal_lembur = $rekap['tgl_awal_lembur'] ;
    		$tgl_awal = $rekap['tgl_awal'] ;
    		$tgl_akhir = $rekap['tgl_akhir'] ;
		    
		}
		
		if($request->get('Cari')=="GET"){
			$param['rekap'] = $rekap;
			return $rekap;
		}else 
		if($request->get('Cari')=="Generate"){
		    	return view('backend.rekap_absen.view_generate',compact('departemen','entitas','jabatan','request','tgl_awal','tgl_akhir','bulan','tahun','periode','periode_absen','periode_gajian','help','tgl_awal','tgl_akhir','user','rekapget','help'));
				
		}else 
		if($request->get('Cari')=="RekapExcel"){
			$nama_file = 'Rekap Lembur ' . date('d-m-Y', strtotime($tgl_awal)) . ':' . date('d-m-Y', strtotime($tgl_akhir));
			$param['bulan'] = $bulan;
			$param['tahun'] = $tahun;
			$param['tgl_awal'] = $tgl_awal;
			$param['tgl_akhir'] = $tgl_akhir;
			$param['periode_absen'] = $periode_absen;
			$param['periode'] = $periode;
			$param['rekap'] = $rekap;
			$param['help'] = $help; 
			$param['hari_libur'] = $hari_libur; 
			$param['departemen'] = $departemen ; 
			$param['hari_libur_shift'] = $hari_libur_shift; 
			$param['list_karyawan'] = $list_karyawan; 
			$param['tgl_awal_lembur'] = $tgl_awal_lembur; 
			if($rekapget =='Rekap Izin'){
				return RekapAbsenController::exportsIzin($param);
			}elseif($rekapget =='Rekap Lembur s/ Ajuan'){
				return RekapAbsenController::exportsLembur($param);
			}elseif($rekapget =='Rekap Lembur s/ Approve'){
				return RekapAbsenController::exportsLemburApprove($param);
			}elseif($rekapget =='Rekap Perdin'){
				return RekapAbsenController::exportsPerdin($param);
			}elseif($rekapget =='Rekap Cuti'){
				return RekapAbsenController::exportsCuti($param);
			}else{
				return RekapAbsenController::exports($param);
			}
		}else{
		  
			if($rekapget =='Rekap Izin'){
				
				return view('backend.rekap_absen.view_rekap_ijin',compact('tgl_awal','tgl_akhir','bulan','tahun','periode','periode_absen','periode_gajian','help','tgl_awal','tgl_akhir','list_karyawan','rekap','user','rekapget','hari_libur','hari_libur_shift'));
			} 
			elseif ($rekapget =='Rekap Lembur s/ Ajuan') {
				
				return view('backend.rekap_absen.view_rekap_lembur_ajuan',compact('tgl_awal','tgl_akhir','bulan','tahun','periode','periode_absen','periode_gajian','help','tgl_awal','tgl_akhir','list_karyawan','rekap','user','rekapget','hari_libur','hari_libur_shift'));
			} 
			elseif ($rekapget =='Rekap Lembur s/ Approve') {
				 $tgl_awal = $tgl_awal_lembur;
				
				return view('backend.rekap_absen.view_rekap_lembur_approve',compact('tgl_awal','departemen','entitas','jabatan','request','tgl_akhir','bulan','tahun','periode','periode_absen','periode_gajian','help','tgl_awal','tgl_akhir','list_karyawan','rekap','user','rekapget','hari_libur','hari_libur_shift'));
			}
			elseif($rekapget =='Rekap Perdin'){
				
				return view('backend.rekap_absen.view_rekap_perdin',compact('tgl_awal','tgl_akhir','bulan','tahun','periode','periode_absen','periode_gajian','help','tgl_awal','tgl_akhir','list_karyawan','rekap','user','rekapget','hari_libur','hari_libur_shift'));
			
			}elseif($rekapget =='Rekap Cuti'){
				
				return view('backend.rekap_absen.view_rekap_cuti',compact('tgl_awal','tgl_akhir','bulan','tahun','periode','periode_absen','periode_gajian','help','tgl_awal','tgl_akhir','list_karyawan','rekap','user','rekapget','hari_libur','hari_libur_shift'));
			
			}elseif($rekapget =='Absen'){
			    
				return view('backend.rekap_absen.view_rekap_absen'.$pekanan,compact('departemen','entitas','jabatan','request','tgl_awal','tgl_akhir','bulan','tahun','periode','periode_absen','periode_gajian','help','tgl_awal','tgl_akhir','list_karyawan','rekap','rekap','user','rekapget','hari_libur','hari_libur_shift','help'));
				
			}else{
				return view('backend.rekap_absen.view_rekap_absen'.$pekanan,compact('departemen','entitas','jabatan','request','tgl_awal','tgl_akhir','bulan','tahun','periode','periode_absen','periode_gajian','help','tgl_awal','tgl_akhir','help','rekapget'));
				
			}
		}
	}

    public static function generate_all_periode(){
	    
	    $periode = DB::connection()->select('select * from m_periode_absen ');
	    foreach($periode as $periode){
	       // RekapAbsenController::generate_rekap_absen($periode->periode_absen_id);
	    }
	    
	    
	}
	public static function generate_periode_aktif(){
	    
	    $periode = DB::connection()->select('select * from m_periode_absen where periode_aktif=1');
	    foreach($periode as $periode){
	      //  RekapAbsenController::generate_rekap_absen($periode->periode_absen_id);
	    }
	    
	    
	}

  
	public  function generate_rekap_absen(Request $request){
	    
	    $help = new Helper_function();
	    
	      echo  $periode_absen_id = $request->periode_absen_id;
	    
	    $where = '';
	    
	    if($periode_absen_id){
	        $where = 'where periode_absen_id='.$periode_absen_id;
	    }
	    
	    if(!$request->existing){
	        
	        $periode = DB::connection()->select('select * from m_periode_absen '.$where);
	        foreach($periode as $periode){
	        $type = $periode->type;
	        $periode_absen = $periode->periode_absen_id;
	        
	        $karyawan = DB::connection()->select('select * from p_karyawan left join p_karyawan_pekerjaan a on a.p_karyawan_id = p_karyawan.p_karyawan_id  where p_karyawan.active=1 and periode_gajian='.$type);
	        
             DB::connection()->table("absen_master_generate")->where("periode_absen_id",$periode_absen)->update(["active"=>0]);
             DB::connection()->table("absen_master_generate_tanggal")->where("periode_absen_id",$periode_absen)->update(["active"=>0]);
             DB::connection()->table("absen_master_rekap")->where("periode_absen_id",$periode_absen)->update(["active"=>0]);
	        
	           // $date = $periode[0]->tgl_awal;
    	       // for($i=0;$i<$help->hitungHari($periode[0]->tgl_awal,$periode[0]->tgl_akhir);$i++){
    	            
    	       //     $data_tanggal['tanggal'] = $date;
    	       //     $data_tanggal['status'] = 0;
    	       //     $data_tanggal['periode_absen_id'] = $periode_absen_id;
    	       //     $data_tanggal['create_date'] = date('Y-m-d H:i:s');
    	       //     DB::connection()->table("absen_master_generate_tanggal")->insert($data_tanggal);
	        foreach($karyawan as $list_karyawan){
    	            $data['p_karyawan_id'] = $list_karyawan->p_karyawan_id;
    	           // $data['tanggal'] = $date;
    	            $data['status'] = 0;
    	            $data['periode_absen_id'] = $periode_absen;
    	            $data['create_date'] = date('Y-m-d H:i:s');
                    DB::connection()->table("absen_master_generate")->insert($data);
    	            
    	        }
    	        
    	   //         $date=$help->tambah_tanggal($date,1);
	       // }
	    }
	    }
	}

   public static function hitung_rekap_absen(Request $request,$periode_absen_id=null,$waktu=null){
	   if(!$periode_absen_id){
	       $periode_absen_id = $request->periode_absen_id;
	   }
	   if(!$waktu){
	       $waktu = $request->waktu;
	   }
	   $where ="";
	   $limit = 1;
	   if($periode_absen_id){
	       
	    $where ="and periode_absen_id = $periode_absen_id";
	   }else{
	       $limit = 15;
	   }
	    $absen_master_gen = DB::Connection()->select("select * from absen_master_generate where status=0 and active=1 $where order by periode_absen_id desc limit $limit");
	    if(count($absen_master_gen)){
	    $periode_absen_id = $absen_master_gen[0]->periode_absen_id;
	    $periode = DB::Connection()->select("select * from m_periode_absen where periode_absen_id = ".$periode_absen_id);
	   // echo $periode_absen_id;
       
        
	    foreach($absen_master_gen as $g){
	        
	        $tgl_awal = $periode[0]->tgl_awal;
	        $tgl_akhir = $periode[0]->tgl_akhir;
	        
	       $id_karyawan = $g->p_karyawan_id;
	        $sql = "select * from m_hari_libur where tanggal >= '$tgl_awal'  and active=1 and tanggal <='$tgl_akhir'";
        $harilibur = DB::connection()->select($sql);
        $hari_libur = array();
        $hari_libur_except_pengkhususan = array();
        $hari_libur_except_pengecualian = array();
        $tanggallibur = array();
        $hr = 0;
        foreach ($harilibur as $libur) {
        	$sql = "select * from m_hari_libur_except where active = 1 and m_hari_libur_id = $libur->m_hari_libur_id";
        	$hariliburexcept = DB::connection()->select($sql);
        	foreach($hariliburexcept as $except){
        		if($except->jenis==1)
            		$hari_libur_except_pengecualian[$libur->tanggal][] = $except->m_lokasi_id;
        		if($except->jenis==2)
            		$hari_libur_except_pengkhususan[$libur->tanggal][] = $except->m_lokasi_id;
        		
        	}
            $hari_libur[$hr] = $libur->tanggal;
            $tanggallibur[$libur->tanggal] = $libur->nama;
        	$hr++;
        }
        $hari_libur_shift = array();
        $sql = "select * from absen_libur_shift where tanggal >= '$tgl_awal' and tanggal <='$tgl_akhir' and absen_libur_shift.active = 1";
        $harilibur = DB::connection()->select($sql);
        foreach ($harilibur as $libur) {
            $hari_libur_shift[$libur->tanggal][$libur->p_karyawan_id] = 1;
        }

        $sql = "Select * from m_mesin_absen";
        $dmesin    = DB::connection()->select($sql);
        foreach ($dmesin as $dmesin) {
            $mesin[$dmesin->mesin_id] = $dmesin->nama;
        }
	       
	       
	        $sqlabsen = "

		select a.*,c.p_karyawan_id,c.m_lokasi_id, case
				when
					(select count(*)
							from absen_shift
								join absen on absen.absen_id = absen_shift.absen_id
								where absen.active = 1
									and absen_shift.active = 1
									and absen_shift.p_karyawan_id=c.p_karyawan_id
									and absen_shift.tanggal = to_char(a.date_time, 'YYYY-MM-DD')::date

					 )>=1

				then
					(select jam_masuk
						from absen_shift
							join absen on absen.absen_id = absen_shift.absen_id
							where absen.active = 1
								and absen_shift.active = 1
								and absen_shift.p_karyawan_id=c.p_karyawan_id
								and absen_shift.tanggal=to_char(a.date_time, 'YYYY-MM-DD')::date limit 1)
				else (select jam_masuk
						from absen
							where absen.tgl_awal<=a.date_time and absen.tgl_akhir>=a.date_time
								and absen.m_lokasi_id = c.m_lokasi_id
								and shifting = 0 limit 1)
				end as jam_masuk

			, case
					when
						(select count(*)
							from absen_shift
								join absen on absen.absen_id = absen_shift.absen_id
								where absen.active = 1
									and absen_shift.active = 1
									and absen_shift.p_karyawan_id=c.p_karyawan_id
									and absen_shift.tanggal=to_char(a.date_time, 'YYYY-MM-DD')::date )>=1

					then (select jam_keluar
							from absen_shift
								join absen on absen.absen_id = absen_shift.absen_id
								where absen.active = 1
									and absen_shift.active = 1
									and absen_shift.p_karyawan_id=c.p_karyawan_id
									and absen_shift.tanggal=to_char(a.date_time, 'YYYY-MM-DD')::date limit 1)
					else
						(select jam_keluar
							from absen
								where absen.tgl_awal<=a.date_time
									 and absen.tgl_akhir>=a.date_time and absen.m_lokasi_id = c.m_lokasi_id
									 and shifting = 0 limit 1)

					end as jam_keluar


			 from absen_log a
			 left join p_karyawan_absen b on b.no_absen = a.pin
			 left join p_karyawan_pekerjaan c on b.p_karyawan_id = c.p_karyawan_id
			 left join p_karyawan d on b.p_karyawan_id = d.p_karyawan_id


			 where a.date_time>='$tgl_awal' and a.date_time<='$tgl_akhir 23:59:59'
			 and d.p_karyawan_id = $id_karyawan
			 
    		order by date_time desc
    		
    		";
            $absen = DB::connection()->select($sqlabsen);
            foreach($absen as $absen){
            $date = date('Y-m-d', strtotime($absen->date_time));
            $time = date('H:i:s', strtotime($absen->date_time));
            $time2 = date('H:i:s', strtotime($absen->date_time));

            if ($absen->ver == 1) {
                
                $masuk = $absen->jam_masuk;
                $rekap[$id_karyawan][$date]['a']['masuk'] = $time;
                $rekap[$id_karyawan][$date]['a']['jam_masuk'] = $masuk;
                $lokasi_id = $absen->m_lokasi_id;
                $rekap[$id_karyawan][$date]['a']['status_masuk'] = $absen->status_absen_id;
                $rekap[$id_karyawan][$date]['a']['updated_at_masuk'] = $absen->updated_at;
                $rekap[$id_karyawan][$date]['a']['updated_by_masuk'] = $absen->updated_by;
                $rekap[$id_karyawan][$date]['a']['time_before_update_masuk'] = $absen->time_before_update;
                $rekap[$id_karyawan][$date]['a']['mesin_id'] = $absen->mesin_id;
                $rekap[$id_karyawan][$date]['a']['absen_log_id_masuk'] = $absen->absen_log_id;
                
            $rekap[$id_karyawan][$date]['a']['tipe_master'] = 1;
            } else if ($absen->ver == 2) {
                
              
                $keluar = $absen->jam_keluar;
                $rekap[$id_karyawan][$date]['a']['keluar'] = $time;
                $rekap[$id_karyawan][$date]['a']['jam_keluar'] = $keluar;
                $rekap[$id_karyawan][$date]['a']['absen_log_id_keluar'] = $absen->absen_log_id;

                $rekap[$id_karyawan][$date]['a']['status_keluar'] = $absen->status_absen_id;
                $rekap[$id_karyawan][$date]['a']['updated_at_keluar'] = $absen->updated_at;
                $rekap[$id_karyawan][$date]['a']['updated_by_keluar'] = $absen->updated_by;
                $rekap[$id_karyawan][$date]['a']['time_before_update_keluar'] = $absen->time_before_update;
                $rekap[$id_karyawan][$date]['a']['tipe_master'] = 2;
            }
            $rekap[$id_karyawan][$date]['a']['m_periode_absen_id'] = $periode_absen_id;
            $rekap[$id_karyawan][$date]['a']['p_karyawan_id'] = $id_karyawan;
            $rekap[$id_karyawan][$date]['a']['tanggal'] = $date;
            DB::connection()->table("absen_master")->insert($rekap[$id_karyawan][$date]['a']);
            
            
            DB::connection()->table("absen_log")->where("absen_log_id",$absen->absen_log_id)->update(["master"=>1]);
            
            
            
        }		
           
	       if(isset($rekap)){
	    $array['periode_absen_id'] = $periode_absen_id;
	    $array['array_rekap'] = json_encode($rekap);
	    $array['create_date'] = date('Y-m-d H:i:s');
	    $array['tanggal'] = $tgl_awal;
	    $array['p_karyawan_id'] = $g->p_karyawan_id;
	    $array['jenis'] = 'absen';
	    DB::connection()->table('absen_master_rekap')->insert($array);
	    }
	    unset($rekap);
	       
	       
	       
	       
	        $sqllembur = "Select m_jenis_ijin.nama as nama_ijin,m_jenis_ijin.tipe,c.t_form_exit_id,c.m_jenis_ijin_id,c.lama,c.keterangan,c.p_karyawan_id,c.tgl_awal,c.tgl_akhir,c.jam_awal,c.status_appr_hr,a.periode_gajian,status_appr_1,m_jenis_ijin.kode as string_kode_ijin
        		from t_permit c
        		left join m_jenis_ijin on m_jenis_ijin.m_jenis_ijin_id = c.m_jenis_ijin_id
        		left join p_karyawan_pekerjaan a on c.p_karyawan_id = a.p_karyawan_id 
        		where ((c.tgl_awal>='$tgl_awal' and c.tgl_awal<='$tgl_akhir 23:59') or
        		(c.tgl_akhir>='$tgl_awal' and c.tgl_akhir>='$tgl_akhir 23:59') or
        		(c.tgl_akhir>='$tgl_awal' and c.tgl_akhir<='$tgl_akhir 23:59') or
        		(c.tgl_awal<='$tgl_awal' and c.tgl_akhir<='$tgl_awal 23:59' and c.tgl_akhir is not null)) and
        		c.m_jenis_ijin_id != 22
        	    and c.p_karyawan_id = $id_karyawan
        		ORDER BY c.p_karyawan_id asc ";
            $lembur = DB::connection()->select($sqllembur); 
            
            $karyawan = array();
            foreach ($lembur as $lembur) {
               
        	$date = $lembur->tgl_awal;
        	if($lembur->status_appr_1==1){
        		
            if (!in_array($id_karyawan, $karyawan))
                $karyawan[] = $id_karyawan;
            if ($lembur->status_appr_hr != 2) {
                $date = $lembur->tgl_awal;
                if (!$lembur->tgl_akhir)
                    $lembur->tgl_akhir = $lembur->tgl_awal;
                
                if ($lembur->tgl_akhir=='1970-01-01')
                    $lembur->tgl_akhir = $lembur->tgl_awal;
                for ($i = 0; $i <= Helper_function::hitunghari($lembur->tgl_awal, $lembur->tgl_akhir); $i++) {
                    
                    if (in_array(Helper_function::nama_hari($date), array('Minggu', 'Sabtu')) and in_array($date, $hari_libur) and isset($hari_libur_shift[$date][$id_karyawan])) {
                    	$rekap[$id_karyawan]['total']['ijin_libur'][]= $date;
                    	
                    }
                   
                    	$rekap[$id_karyawan]['total_id'][$lembur->m_jenis_ijin_id][]=$date;	
                   

					if(in_array($lembur->m_jenis_ijin_id,array(25,5)) and $lembur->periode_gajian==0){
						
					}else{
					if($date >= $tgl_awal){	
                     $rekap[$id_karyawan][$date]['ci']['nama_ijin'] = $lembur->nama_ijin . '<br> ' . $lembur->tgl_awal . ' sd ' . $lembur->tgl_akhir . '<br> Total: ' . $lembur->lama.' Hari';
                    $rekap[$id_karyawan][$date]['ci']['nama_ijin_only'] = $lembur->nama_ijin ;
                    $rekap[$id_karyawan][$date]['ci']['keterangan'] = $lembur->keterangan;
                    $rekap[$id_karyawan][$date]['ci']['lama'] = $lembur->lama;
                    $rekap[$id_karyawan][$date]['ci']['jam_awal'] = $lembur->jam_awal;
                    $rekap[$id_karyawan][$date]['ci']['tipe'] = $lembur->tipe;
                    $rekap[$id_karyawan][$date]['ci']['m_jenis_ijin_id'] = $lembur->m_jenis_ijin_id;
	                   $rekap[$id_karyawan][$date]['ci']['tipe_master'] = 3;
                  
                    $rekap[$id_karyawan][$date]['ci']['p_karyawan_id'] = $id_karyawan;
                    $rekap[$id_karyawan][$date]['ci']['m_periode_absen_id'] = $periode_absen_id;
                    $rekap[$id_karyawan][$date]['ci']['tanggal'] = $date;
                    DB::connection()->table("absen_master")->insert($rekap[$id_karyawan][$date]['ci']);
            
					}
					}
                   
                    $date = Helper_function::tambah_tanggal($date, 1);
                }
            }
        	}else{
        		$date = $lembur->tgl_awal;
        		if($date >= $tgl_awal){
        		     for ($i = 0; $i <= Helper_function::hitunghari($lembur->tgl_awal, $lembur->tgl_akhir); $i++) {
                    
            		$rekap['pending_pengajuan'][$id_karyawan][$date]['nama_izin'] = $lembur->nama_ijin;
            		$rekap['pending_pengajuan'][$id_karyawan][$date]['status'] = $lembur->status_appr_1;
            		
            		$rekap['pending_pengajuan'][$id_karyawan][$date]['tipe_master'] = 4;
            		$rekap['pending_pengajuan'][$id_karyawan][$date]['m_periode_absen_id'] = $periode_absen_id;
                      
                    $rekap['pending_pengajuan'][$id_karyawan][$date]['p_karyawan_id'] = $id_karyawan;
                    $rekap['pending_pengajuan'][$id_karyawan][$date]['tanggal'] = $date;
                    DB::connection()->table("absen_master")->insert($rekap['pending_pengajuan'][$id_karyawan][$date]);
                
                    $date = Helper_function::tambah_tanggal($date, 1);
        		}
        	}
        	}
        
            
            DB::connection()->table("t_permit")->where("t_form_exit_id",$lembur->t_form_exit_id)->update(["master"=>1]);        
        
        }
	    if(isset($rekap)){
	       
	    $array['periode_absen_id'] = $periode_absen_id;
	    $array['array_rekap'] = json_encode($rekap);
	    $array['create_date'] = date('Y-m-d H:i:s');
	    $array['tanggal'] = $tgl_awal;
	    $array['jenis'] = 'pengajuan';
	    
	    DB::connection()->table('absen_master_rekap')->insert($array);
	    }
	    unset($rekap);
	    
	        
	        
	        
	        
	        
	        
	        
	        $sqllembur = "Select c.*,m_jenis_ijin.kode as string_kode_ijin 

		from t_permit  c
		left join m_jenis_ijin on m_jenis_ijin.m_jenis_ijin_id = c.m_jenis_ijin_id
		
		join p_karyawan_pekerjaan d on c.p_karyawan_id = d.p_karyawan_id
		where 
	(case
	WHEN status_appr_1=1 and appr_2 is null THEN tgl_appr_1>='$tgl_awal' and  tgl_appr_1<='$tgl_akhir'
		WHEN status_appr_1=1 and status_appr_2=1 THEN tgl_appr_2>='$tgl_awal' and  tgl_appr_2<='$tgl_akhir'
		WHEN appr_1 is null and status_appr_2=1 THEN tgl_appr_2>='$tgl_awal' and  tgl_appr_2<='$tgl_akhir'
		end)
		and c.m_jenis_ijin_id = 22
	    and c.p_karyawan_id = $id_karyawan
		and c.active=1
		
		
		ORDER BY c.p_karyawan_id asc";
        $lembur = DB::connection()->select($sqllembur);
        //$karyawan = array();
        //$rekap = array();
            //echo $sqllembur;
        // echo '<pre>';print_r($lembur); echo '</pre>';
        $array_tgl = array();
        $tgl_awal_lembur_ajuan = $tgl_awal;
        
        foreach ($lembur as $lembur) {
            if (!in_array($id_karyawan, $karyawan))
                $karyawan[] = $id_karyawan;

            if (!isset($rekap[$id_karyawan]['lembur'])) {
                $rekap[$id_karyawan]['lembur']['total_pengajuan'] = 0;
                $rekap[$id_karyawan]['lembur']['total_pending'] = 0;
                $rekap[$id_karyawan]['lembur']['total_approve'] = 0;
                $rekap[$id_karyawan]['lembur']['total_tolak'] = 0;
                $rekap[$id_karyawan]['lembur']['tgl_pending'] = '';
            }
            $rekap[$id_karyawan]['lembur']['total_pengajuan'] += (int)$lembur->lama;
            if (($lembur->status_appr_1 == 1 and $lembur->appr_2 == null)
                or ($lembur->status_appr_1 == 1 and $lembur->status_appr_2 == 1)
                or  ($lembur->appr_1 == null and $lembur->status_appr_2 == 1)
            ) {

                if ($lembur->tgl_awal >= '2022-09-27') {
                    if ($lembur->status_appr_1 == 1 and $lembur->appr_2 == null)
                        $tgl = $lembur->tgl_appr_1;
                    else if ($lembur->status_appr_1 == 1 and $lembur->status_appr_2 == 1)
                        $tgl = $lembur->tgl_appr_2;
                    else if ($lembur->appr_1 == null and $lembur->status_appr_2 == 1)
                        $tgl = $lembur->tgl_appr_2;
                } else {
                    $tgl = $lembur->tgl_awal;
                }
                $list_permit[] = $lembur->t_form_exit_id;
                $rekap[$id_karyawan]['lembur']['total_approve'] += (int)$lembur->lama;

                $array_tgl[] =  $lembur->tgl_awal;;
                $tgl_appr = $lembur->tgl_awal;

                $rekap['approve'][$id_karyawan][$tgl_appr]['tipe_lembur'] = $lembur->tipe_lembur;
                $rekap['approve'][$id_karyawan][$tgl_appr]['tgl_awal'] = $lembur->tgl_awal;
                $rekap['approve'][$id_karyawan][$tgl_appr]['tgl_akhir'] = $lembur->tgl_akhir;
                
                
                $rekap['ajuan'][$id_karyawan][$lembur->tgl_awal]['tipe_lembur'] = $lembur->tipe_lembur;
                $rekap['ajuan'][$id_karyawan][$lembur->tgl_awal]['tgl_awal'] = $lembur->tgl_awal;
                $rekap['ajuan'][$id_karyawan][$lembur->tgl_awal]['tgl_akhir'] = $lembur->tgl_akhir;
                
                
                if(!isset( $rekap['approve'][$id_karyawan][$tgl_appr]['lama'])){
                
	                $rekap['approve'][$id_karyawan][$tgl_appr]['lama'] = $lembur->lama;
	                $rekap['approve'][$id_karyawan][$tgl_appr]['keterangan'] = $lembur->keterangan;
	                $rekap['approve'][$id_karyawan][$tgl_appr]['jam_awal'] = $lembur->jam_awal;
	                $rekap['approve'][$id_karyawan][$tgl_appr]['jam_akhir'] = $lembur->jam_akhir;
	                $rekap['approve'][$id_karyawan][$tgl_appr]['total_ajuan'] = 1;
	                
					$rekap['ajuan'][$id_karyawan][$lembur->tgl_awal]['lama'] = $lembur->lama;
	                $rekap['ajuan'][$id_karyawan][$lembur->tgl_awal]['keterangan'] = $lembur->keterangan;
	                $rekap['ajuan'][$id_karyawan][$lembur->tgl_awal]['jam_awal'] = $lembur->jam_awal;
	                $rekap['ajuan'][$id_karyawan][$lembur->tgl_awal]['jam_akhir'] = $lembur->jam_akhir;
	                $rekap['ajuan'][$id_karyawan][$lembur->tgl_awal]['total_ajuan'] = 1;
	                
                }else{
                	
	                $rekap['approve'][$id_karyawan][$tgl_appr]['total_ajuan'] += 1;
	                $rekap['ajuan'][$id_karyawan][$lembur->tgl_awal]['total_ajuan'] += 1;
	            
	                $rekap['approve'][$id_karyawan][$tgl_appr]['lama'] += $lembur->lama;
	                $rekap['approve'][$id_karyawan][$tgl_appr]['keterangan'] = str_replace(',','','<br><b style="font-weight:700">'.
	                			$rekap['ajuan'][$id_karyawan][$lembur->tgl_awal]['jam_awal'].'</b><br>'.
	                			$rekap['ajuan'][$id_karyawan][$lembur->tgl_awal]['keterangan']
	                			.'  |  '.'<b style="font-weight:700">'.$lembur->jam_awal.'</b><br>'.$lembur->keterangan);	 
	                $rekap['approve'][$id_karyawan][$tgl_appr]['jam_awal'] = $rekap['approve'][$id_karyawan][$tgl_appr]['jam_awal'].' s/d '.$rekap['approve'][$id_karyawan][$tgl_appr]['jam_akhir'].' | '.$lembur->jam_awal.' s/d '.$lembur->jam_akhir;
	                $rekap['approve'][$id_karyawan][$tgl_appr]['jam_akhir'] = '';
	                
					$rekap['ajuan'][$id_karyawan][$lembur->tgl_awal]['lama'] += $lembur->lama;
	                $rekap['ajuan'][$id_karyawan][$lembur->tgl_awal]['keterangan']= str_replace(',','','<br><b style="font-weight:700">'.
	                			$rekap['ajuan'][$id_karyawan][$lembur->tgl_awal]['jam_awal'].'</b><br>'.
	                			$rekap['ajuan'][$id_karyawan][$lembur->tgl_awal]['keterangan']
	                			
	                			.'  |  '
	                			.'<b style="font-weight:700">'.$lembur->jam_awal.'</b><br>'.$lembur->keterangan);
	                $rekap['ajuan'][$id_karyawan][$lembur->tgl_awal]['jam_awal'] =$rekap['approve'][$id_karyawan][$tgl_appr]['jam_awal'].' s/d '.$rekap['approve'][$id_karyawan][$tgl_appr]['jam_akhir'].' | '.$lembur->jam_awal.' s/d '.$lembur->jam_akhir;
	                $rekap['ajuan'][$id_karyawan][$lembur->tgl_awal]['jam_akhir'] ='';

                }
            
            
            DB::connection()->table("t_permit")->where("t_form_exit_id",$lembur->t_form_exit_id)->update(["master"=>1]);     
                		
                if($tgl_awal_lembur_ajuan>=$lembur->tgl_awal)
                		$tgl_awal_lembur_ajuan = $lembur->tgl_awal;
            } else if (
                ($lembur->status_appr_1 == 2 and $lembur->appr_2 == null)
                or  ($lembur->status_appr_1 == 2 and $lembur->status_appr_2 == 2)
                or  ($lembur->appr_1 == null and $lembur->status_appr_2 == 2)
                or  ($lembur->status_appr_1 == 1 and $lembur->status_appr_2 == 2)
            ) {
                $rekap[$id_karyawan]['lembur']['total_tolak'] += (int)$lembur->lama;
            } else {
                $rekap[$id_karyawan]['lembur']['total_pending'] += (int)$lembur->lama;
                $rekap[$id_karyawan]['lembur']['tgl_pending'] .= $lembur->tgl_awal . ' | ';
            }
        }
	    if(isset($rekap)){
	    $array['periode_absen_id'] = $periode_absen_id;
	    $array['array_rekap'] = json_encode($rekap);
	    $array['create_date'] = date('Y-m-d H:i:s');
	    $array['tanggal'] = $tgl_awal;
	    $array['jenis'] = 'lembur';
	    
	    DB::connection()->table('absen_master_rekap')->insert($array);
	    }
	    unset($rekap);
	        
	    $countklari        = DB::connection()->select("select * from chat_room where  tanggal >= '$tgl_awal' and  tanggal<='$tgl_akhir' and p_karyawan_create_id=$id_karyawan ");
        foreach($countklari as $klarifi){
         	$rekap['klarifikasi'][$id_karyawan][$klarifi->tanggal]['topik'] = $klarifi->topik;
            $rekap['klarifikasi'][$id_karyawan][$klarifi->tanggal]['deskripsi'] = $klarifi->deskripsi;
            
            $rekap['klarifikasi'][$id_karyawan][$klarifi->tanggal]['tipe_master'] = 7;
            $rekap['klarifikasi'][$id_karyawan][$klarifi->tanggal]['m_periode_absen_id'] = $periode_absen_id;
            $rekap['klarifikasi'][$id_karyawan][$klarifi->tanggal]['p_karyawan_create_id'] = $id_karyawan;
            $rekap['klarifikasi'][$id_karyawan][$klarifi->tanggal]['tanggal'] = $klarifi->tanggal;
            
            DB::connection()->table("absen_master")->insert($rekap['klarifikasi'][$id_karyawan][$klarifi->tanggal]);
            
            
            DB::connection()->table("chat_room")->where("chat_room_id",$klarifi->chat_room_id)->update(["master"=>1]);     
             	
         }
	    
	    
	    
	      DB::connection()->table("absen_master_generate")->where("absen_master_generate_id",$g->absen_master_generate_id)->update(["status"=>1]);     
               
	    }
	    if(isset($rekap)){
	    $array['periode_absen_id'] = $periode_absen_id;
	    $array['array_rekap'] = json_encode($rekap);
	    $array['create_date'] = date('Y-m-d H:i:s');
	    
	    DB::connection()->table('absen_master_rekap')->insert($array);
	    }
	    unset($rekap);
	    $time = $waktu;
         $time = $time?$time:60000;
         $time = $time/1000/$limit;
        $sqlgenerate = "SELECT count(*) as jumlah_semua_karyawan,count(CASE WHEN status = 1 THEN 1 END)  as yang_sudah FROM absen_master_generate a where active=1 $where";
        //////echo $sqluser;
        $generete = DB::connection()->select($sqlgenerate);
        $persen = round($generete[0]->yang_sudah / $generete[0]->jumlah_semua_karyawan * 100, 2);
        $init = ($generete[0]->jumlah_semua_karyawan - $generete[0]->yang_sudah) * $time;
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
	    }else{
	        echo 'Generate selesai';
	    }
	    
	}
  
	public static function hitung_rekap_absen2(Request $request){
	    $help = new Helper_function();
	   $absen_master_gen = DB::Connection()->select("select * from absen_master_generate_tanggal where periode_absen_id = $request->periode_absen_id and status=0 and active=1 order by tanggal limit 1");
	    
       
        
	    foreach($absen_master_gen as $g){
            $date = $g->tanggal;
            RekapAbsenController::hitung_generate_rekap_absen($date,$date,$request->periode_absen_id);
             DB::connection()->table("absen_master_generate_tanggal")->where("absen_master_generate_tanggal_id",$g->absen_master_generate_tanggal_id)->update(["status"=>1]);     
             
            $date=$help->tambah_tanggal($date,1);
             $time = $request->get('waktu');
         $time = $time?$time:5000;
         $time = $time/1000;
        
        $sqlgenerate = "SELECT count(*) as jumlah_semua_karyawan,count(CASE WHEN status = 1 THEN 1 END)  as yang_sudah FROM absen_master_generate_tanggal a where periode_absen_id = $request->periode_absen_id and active=1";
        //////echo $sqluser;
        $generete = DB::connection()->select($sqlgenerate);
        $persen = round($generete[0]->yang_sudah / $generete[0]->jumlah_semua_karyawan * 100, 2);
        $init = ($generete[0]->jumlah_semua_karyawan - $generete[0]->yang_sudah) * $time;
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
        }
	}

  
	public static function hitung_generate_rekap_absen($tgl_awal,$tgl_akhir,$periode_absen_id){
	   
	    
	        $sql = "select * from m_hari_libur where tanggal >= '$tgl_awal'  and active=1 and tanggal <='$tgl_akhir'";
        $harilibur = DB::connection()->select($sql);
        $hari_libur = array();
        $hari_libur_except_pengkhususan = array();
        $hari_libur_except_pengecualian = array();
        $tanggallibur = array();
        $hr = 0;
        foreach ($harilibur as $libur) {
        	$sql = "select * from m_hari_libur_except where active = 1 and m_hari_libur_id = $libur->m_hari_libur_id";
        	$hariliburexcept = DB::connection()->select($sql);
        	foreach($hariliburexcept as $except){
        		if($except->jenis==1)
            		$hari_libur_except_pengecualian[$libur->tanggal][] = $except->m_lokasi_id;
        		if($except->jenis==2)
            		$hari_libur_except_pengkhususan[$libur->tanggal][] = $except->m_lokasi_id;
        		
        	}
            $hari_libur[$hr] = $libur->tanggal;
            $tanggallibur[$libur->tanggal] = $libur->nama;
        	$hr++;
        }
        $hari_libur_shift = array();
        $sql = "select * from absen_libur_shift where tanggal >= '$tgl_awal' and tanggal <='$tgl_akhir' and absen_libur_shift.active = 1";
        $harilibur = DB::connection()->select($sql);
        foreach ($harilibur as $libur) {
            $hari_libur_shift[$libur->tanggal][$libur->p_karyawan_id] = 1;
        }

        $sql = "Select * from m_mesin_absen";
        $dmesin    = DB::connection()->select($sql);
        foreach ($dmesin as $dmesin) {
            $mesin[$dmesin->mesin_id] = $dmesin->nama;
        }
	       
	       
	        $sqlabsen = "

		select a.*,d.p_karyawan_id,c.m_lokasi_id, case
				when
					(select count(*)
							from absen_shift
								join absen on absen.absen_id = absen_shift.absen_id
								where absen.active = 1
									and absen_shift.active = 1
									and absen_shift.p_karyawan_id=c.p_karyawan_id
									and absen_shift.tanggal = to_char(a.date_time, 'YYYY-MM-DD')::date

					 )>=1

				then
					(select jam_masuk
						from absen_shift
							join absen on absen.absen_id = absen_shift.absen_id
							where absen.active = 1
								and absen_shift.active = 1
								and absen_shift.p_karyawan_id=c.p_karyawan_id
								and absen_shift.tanggal=to_char(a.date_time, 'YYYY-MM-DD')::date limit 1)
				else (select jam_masuk
						from absen
							where absen.tgl_awal<=a.date_time and absen.tgl_akhir>=a.date_time
								and absen.m_lokasi_id = c.m_lokasi_id
								and shifting = 0 limit 1)
				end as jam_masuk

			, case
					when
						(select count(*)
							from absen_shift
								join absen on absen.absen_id = absen_shift.absen_id
								where absen.active = 1
									and absen_shift.active = 1
									and absen_shift.p_karyawan_id=c.p_karyawan_id
									and absen_shift.tanggal=to_char(a.date_time, 'YYYY-MM-DD')::date )>=1

					then (select jam_keluar
							from absen_shift
								join absen on absen.absen_id = absen_shift.absen_id
								where absen.active = 1
									and absen_shift.active = 1
									and absen_shift.p_karyawan_id=c.p_karyawan_id
									and absen_shift.tanggal=to_char(a.date_time, 'YYYY-MM-DD')::date limit 1)
					else
						(select jam_keluar
							from absen
								where absen.tgl_awal<=a.date_time
									 and absen.tgl_akhir>=a.date_time and absen.m_lokasi_id = c.m_lokasi_id
									 and shifting = 0 limit 1)

					end as jam_keluar


			 from absen_log a
			 left join p_karyawan_absen b on b.no_absen = a.pin
			 left join p_karyawan_pekerjaan c on b.p_karyawan_id = c.p_karyawan_id
			 left join p_karyawan d on b.p_karyawan_id = d.p_karyawan_id


			 where a.date_time>='$tgl_awal' and a.date_time<='$tgl_akhir 23:59:59'
			 
			 and master = 0
    		group by date_time,status_absen_id,absen_log_id,mesin_id,time_before_update,updated_by,updated_at,jam_masuk,jam_keluar,ver,d.p_karyawan_id,c.m_lokasi_id
    		order by date_time desc
    		
    		";
            $absen = DB::connection()->select($sqlabsen);
            foreach($absen as $absen){
            $date = date('Y-m-d', strtotime($absen->date_time));
            $time = date('H:i:s', strtotime($absen->date_time));
            $time2 = date('H:i:s', strtotime($absen->date_time));

            if ($absen->ver == 1) {
                $id_karyawan = $absen->p_karyawan_id;
                /*if($id_karyawan){
$jam_masuk = "select case
when (select count(*) from absen_shift join absen on absen.absen_id = absen_shift.absen_id where absen.active = 1 and absen_shift.p_karyawan_id=".$absen->p_karyawan_id." and absen_shift.tanggal='$date' )>=1

then (select jam_masuk from absen_shift join absen on absen.absen_id = absen_shift.absen_id where absen.active = 1 and absen_shift.p_karyawan_id=".$absen->p_karyawan_id." and absen_shift.tanggal='$date' limit 1)


end as jam_masuk";
$jam_masuk=DB::connection()->select($jam_masuk);
if($jam_masuk[0]->jam_masuk){
$masuk = $jam_masuk[0]->jam_masuk;
}else{
$masuk = $absen->jam_masuk;
}
}else*/
                $masuk = $absen->jam_masuk;
                $rekap[$absen->p_karyawan_id][$date]['a']['masuk'] = $time;
                $rekap[$absen->p_karyawan_id][$date]['a']['jam_masuk'] = $masuk;
                $lokasi_id = $absen->m_lokasi_id;
                $rekap[$absen->p_karyawan_id][$date]['a']['status_masuk'] = $absen->status_absen_id;
                $rekap[$absen->p_karyawan_id][$date]['a']['updated_at_masuk'] = $absen->updated_at;
                $rekap[$absen->p_karyawan_id][$date]['a']['updated_by_masuk'] = $absen->updated_by;
                $rekap[$absen->p_karyawan_id][$date]['a']['time_before_update_masuk'] = $absen->time_before_update;
                $rekap[$absen->p_karyawan_id][$date]['a']['mesin_id'] = $absen->mesin_id;
                $rekap[$absen->p_karyawan_id][$date]['a']['absen_log_id_masuk'] = $absen->absen_log_id;
                
            $rekap[$absen->p_karyawan_id][$date]['a']['tipe_master'] = 1;
            } else if ($absen->ver == 2) {
                $id_karyawan = $absen->p_karyawan_id;
                /*if($id_karyawan){
$jam_masuk = "select case
when (select count(*) from absen_shift join absen on absen.absen_id = absen_shift.absen_id where absen.active = 1 and absen_shift.p_karyawan_id=".$absen->p_karyawan_id." and absen_shift.tanggal='$date' )>=1

then (select jam_keluar from absen_shift join absen on absen.absen_id = absen_shift.absen_id where absen.active = 1 and absen_shift.p_karyawan_id=".$absen->p_karyawan_id." and absen_shift.tanggal='$date' limit 1)


end as jam_keluar";
$jam_masuk=DB::connection()->select($jam_masuk);
if($jam_masuk[0]->jam_keluar){
$keluar = $jam_masuk[0]->jam_keluar;
}else{
$keluar = $absen->jam_keluar;
}
}else
$masuk = $absen->jam_masuk;*/
                $keluar = $absen->jam_keluar;
                $rekap[$absen->p_karyawan_id][$date]['a']['keluar'] = $time;
                $rekap[$absen->p_karyawan_id][$date]['a']['jam_keluar'] = $keluar;
                $rekap[$absen->p_karyawan_id][$date]['a']['absen_log_id_keluar'] = $absen->absen_log_id;

                $rekap[$absen->p_karyawan_id][$date]['a']['status_keluar'] = $absen->status_absen_id;
                $rekap[$absen->p_karyawan_id][$date]['a']['updated_at_keluar'] = $absen->updated_at;
                $rekap[$absen->p_karyawan_id][$date]['a']['updated_by_keluar'] = $absen->updated_by;
                $rekap[$absen->p_karyawan_id][$date]['a']['time_before_update_keluar'] = $absen->time_before_update;
                $rekap[$absen->p_karyawan_id][$date]['a']['tipe_master'] = 2;
            }
            $rekap[$absen->p_karyawan_id][$date]['a']['m_periode_absen_id'] = $periode_absen_id;
            $rekap[$absen->p_karyawan_id][$date]['a']['p_karyawan_id'] = $absen->p_karyawan_id;
            $rekap[$absen->p_karyawan_id][$date]['a']['tanggal'] = $date;
            DB::connection()->table("absen_master")->insert($rekap[$absen->p_karyawan_id][$date]['a']);
            
            
            DB::connection()->table("absen_log")->where("absen_log_id",$absen->absen_log_id)->update(["master"=>1]);
            
            
            
        }		
           
	       
	       
	       
	       
	       
	        $sqllembur = "Select m_jenis_ijin.nama as nama_ijin,m_jenis_ijin.tipe,c.t_form_exit_id,c.m_jenis_ijin_id,c.lama,c.keterangan,c.p_karyawan_id,c.tgl_awal,c.tgl_akhir,c.jam_awal,c.status_appr_hr,a.periode_gajian,status_appr_1,m_jenis_ijin.kode as string_kode_ijin
        		from t_permit c
        		left join m_jenis_ijin on m_jenis_ijin.m_jenis_ijin_id = c.m_jenis_ijin_id
        		left join p_karyawan_pekerjaan a on c.p_karyawan_id = a.p_karyawan_id 
        		where ((c.tgl_awal>='$tgl_awal' and c.tgl_awal<='$tgl_akhir 23:59') or
        		(c.tgl_akhir>='$tgl_awal' and c.tgl_akhir>='$tgl_akhir 23:59') or
        		(c.tgl_akhir>='$tgl_awal' and c.tgl_akhir<='$tgl_akhir 23:59') or
        		(c.tgl_awal<='$tgl_awal' and c.tgl_akhir<='$tgl_awal 23:59' and c.tgl_akhir is not null)) and
        		c.m_jenis_ijin_id != 22
        		ORDER BY c.p_karyawan_id asc ";
            $lembur = DB::connection()->select($sqllembur); 
            
            $karyawan = array();
            foreach ($lembur as $lembur) {
        	$date = $lembur->tgl_awal;
        	if($lembur->status_appr_1==1){
        		
            if (!in_array($lembur->p_karyawan_id, $karyawan))
                $karyawan[] = $lembur->p_karyawan_id;
            if ($lembur->status_appr_hr != 2) {
                $date = $lembur->tgl_awal;
                if (!$lembur->tgl_akhir)
                    $lembur->tgl_akhir = $lembur->tgl_awal;
                
                if ($lembur->tgl_akhir=='1970-01-01')
                    $lembur->tgl_akhir = $lembur->tgl_awal;
                for ($i = 0; $i <= Helper_function::hitunghari($lembur->tgl_awal, $lembur->tgl_akhir); $i++) {
                    if (in_array(Helper_function::nama_hari($date), array('Minggu', 'Sabtu')) and in_array($date, $hari_libur) and isset($hari_libur_shift[$date][$id_karyawan])) {
                    	if(isset($rekap[$lembur->p_karyawan_id]['total']['ijin_libur']))
                        $rekap[$lembur->p_karyawan_id]['total']['ijin_libur'] += 1;
                    	else
                        $rekap[$lembur->p_karyawan_id]['total']['ijin_libur'] = 1;
                    }
                    if(isset($rekap[$lembur->p_karyawan_id]['total_id'][$lembur->m_jenis_ijin_id])){
                    	$rekap[$lembur->p_karyawan_id]['total_id'][$lembur->m_jenis_ijin_id]+=1;	
                    }else{
                    	$rekap[$lembur->p_karyawan_id]['total_id'][$lembur->m_jenis_ijin_id]=1;	
                    }

					if(in_array($lembur->m_jenis_ijin_id,array(25,5)) and $lembur->periode_gajian==0){
						
					}else{
						
                    $rekap[$lembur->p_karyawan_id][$date]['ci']['nama_ijin'] = $lembur->nama_ijin . '<br> ' . $lembur->tgl_awal . ' sd ' . $lembur->tgl_akhir . '<br> Total: ' . $lembur->lama.' Hari';
                    $rekap[$lembur->p_karyawan_id][$date]['ci']['nama_ijin_only'] = $lembur->nama_ijin ;
                    $rekap[$lembur->p_karyawan_id][$date]['ci']['keterangan'] = $lembur->keterangan;
                    $rekap[$lembur->p_karyawan_id][$date]['ci']['lama'] = $lembur->lama;
                    $rekap[$lembur->p_karyawan_id][$date]['ci']['jam_awal'] = $lembur->jam_awal;
                    $rekap[$lembur->p_karyawan_id][$date]['ci']['tipe'] = $lembur->tipe;
                    $rekap[$lembur->p_karyawan_id][$date]['ci']['m_jenis_ijin_id'] = $lembur->m_jenis_ijin_id;
	                   $rekap[$lembur->p_karyawan_id][$date]['ci']['tipe_master'] = 3;
                  
                    $rekap[$lembur->p_karyawan_id][$date]['ci']['p_karyawan_id'] = $lembur->p_karyawan_id;
                    $rekap[$lembur->p_karyawan_id][$date]['ci']['m_periode_absen_id'] = $periode_absen_id;
                    $rekap[$lembur->p_karyawan_id][$date]['ci']['tanggal'] = $date;
                    DB::connection()->table("absen_master")->insert($rekap[$lembur->p_karyawan_id][$date]['ci']);
            
					}
                    $date = Helper_function::tambah_tanggal($date, 1);
                }
            }
        	}else{
        		$date = $lembur->tgl_awal;
        		$rekap['pending_pengajuan'][$lembur->p_karyawan_id][$date]['nama_izin'] = $lembur->nama_ijin;
        		$rekap['pending_pengajuan'][$lembur->p_karyawan_id][$date]['status'] = $lembur->status_appr_1;
        		
        		$rekap['pending_pengajuan'][$lembur->p_karyawan_id][$date]['tipe_master'] = 4;
        		$rekap['pending_pengajuan'][$lembur->p_karyawan_id][$date]['m_periode_absen_id'] = $periode_absen_id;
                  
                $rekap['pending_pengajuan'][$lembur->p_karyawan_id][$date]['p_karyawan_id'] = $lembur->p_karyawan_id;
                $rekap['pending_pengajuan'][$lembur->p_karyawan_id][$date]['tanggal'] = $date;
                DB::connection()->table("absen_master")->insert($rekap['pending_pengajuan'][$lembur->p_karyawan_id][$date]);
        	}
        
            
            DB::connection()->table("t_permit")->where("t_form_exit_id",$lembur->t_form_exit_id)->update(["master"=>1]);        
        }
	    
	        
	        
	        
	        
	        
	        
	        
	        $sqllembur = "Select c.*,m_jenis_ijin.kode as string_kode_ijin 

		from t_permit  c
		left join m_jenis_ijin on m_jenis_ijin.m_jenis_ijin_id = c.m_jenis_ijin_id
		
		join p_karyawan_pekerjaan d on c.p_karyawan_id = d.p_karyawan_id
		where 
	(case
	WHEN status_appr_1=1 and appr_2 is null THEN tgl_appr_1>='$tgl_awal' and  tgl_appr_1<='$tgl_akhir'
		WHEN status_appr_1=1 and status_appr_2=1 THEN tgl_appr_2>='$tgl_awal' and  tgl_appr_2<='$tgl_akhir'
		WHEN appr_1 is null and status_appr_2=1 THEN tgl_appr_2>='$tgl_awal' and  tgl_appr_2<='$tgl_akhir'
		end)
		and c.m_jenis_ijin_id = 22
		and c.active=1
		
		
		ORDER BY c.p_karyawan_id asc";
        $lembur = DB::connection()->select($sqllembur);
        //$karyawan = array();
        //$rekap = array();

        //echo '<pre>';print_r($lembur); echo '</pre>';die;
        $array_tgl = array();
        $tgl_awal_lembur_ajuan = $tgl_awal;
        
        foreach ($lembur as $lembur) {
            if (!in_array($lembur->p_karyawan_id, $karyawan))
                $karyawan[] = $lembur->p_karyawan_id;

            if (!isset($rekap[$lembur->p_karyawan_id]['lembur'])) {
                $rekap[$lembur->p_karyawan_id]['lembur']['total_pengajuan'] = 0;
                $rekap[$lembur->p_karyawan_id]['lembur']['total_pending'] = 0;
                $rekap[$lembur->p_karyawan_id]['lembur']['total_approve'] = 0;
                $rekap[$lembur->p_karyawan_id]['lembur']['total_tolak'] = 0;
                $rekap[$lembur->p_karyawan_id]['lembur']['tgl_pending'] = '';
            }
            $rekap[$lembur->p_karyawan_id]['lembur']['total_pengajuan'] += (int)$lembur->lama;
            if (($lembur->status_appr_1 == 1 and $lembur->appr_2 == null)
                or ($lembur->status_appr_1 == 1 and $lembur->status_appr_2 == 1)
                or  ($lembur->appr_1 == null and $lembur->status_appr_2 == 1)
            ) {

                if ($lembur->tgl_awal >= '2022-09-27') {
                    if ($lembur->status_appr_1 == 1 and $lembur->appr_2 == null)
                        $tgl = $lembur->tgl_appr_1;
                    else if ($lembur->status_appr_1 == 1 and $lembur->status_appr_2 == 1)
                        $tgl = $lembur->tgl_appr_2;
                    else if ($lembur->appr_1 == null and $lembur->status_appr_2 == 1)
                        $tgl = $lembur->tgl_appr_2;
                } else {
                    $tgl = $lembur->tgl_awal;
                }
                $list_permit[] = $lembur->t_form_exit_id;
                $rekap[$lembur->p_karyawan_id]['lembur']['total_approve'] += (int)$lembur->lama;

                $array_tgl[] =  $lembur->tgl_awal;;
                $tgl_appr = $lembur->tgl_awal;

                $rekap[$lembur->p_karyawan_id]['lembur'][$tgl_appr]['tipe_lembur'] = $lembur->tipe_lembur;
                $rekap[$lembur->p_karyawan_id]['lembur'][$tgl_appr]['tgl_awal'] = $lembur->tgl_awal;
                $rekap[$lembur->p_karyawan_id]['lembur'][$tgl_appr]['tgl_akhir'] = $lembur->tgl_akhir;
                
                
                $rekap[$lembur->p_karyawan_id][$lembur->tgl_awal]['ajuan']['tipe_lembur'] = $lembur->tipe_lembur;
                $rekap[$lembur->p_karyawan_id][$lembur->tgl_awal]['ajuan']['tgl_awal'] = $lembur->tgl_awal;
                $rekap[$lembur->p_karyawan_id][$lembur->tgl_awal]['ajuan']['tgl_akhir'] = $lembur->tgl_akhir;
                
                
                if(!isset( $rekap[$lembur->p_karyawan_id]['lembur'][$tgl_appr]['lama'])){
                
	                $rekap[$lembur->p_karyawan_id]['lembur'][$tgl_appr]['lama'] = $lembur->lama;
	                $rekap[$lembur->p_karyawan_id]['lembur'][$tgl_appr]['keterangan'] = $lembur->keterangan;
	                $rekap[$lembur->p_karyawan_id]['lembur'][$tgl_appr]['jam_awal'] = $lembur->jam_awal;
	                $rekap[$lembur->p_karyawan_id]['lembur'][$tgl_appr]['jam_akhir'] = $lembur->jam_akhir;
	                $rekap[$lembur->p_karyawan_id]['lembur'][$tgl_appr]['total_ajuan'] = 1;
	                
					$rekap[$lembur->p_karyawan_id][$lembur->tgl_awal]['ajuan']['lama'] = $lembur->lama;
	                $rekap[$lembur->p_karyawan_id][$lembur->tgl_awal]['ajuan']['keterangan'] = $lembur->keterangan;
	                $rekap[$lembur->p_karyawan_id][$lembur->tgl_awal]['ajuan']['jam_awal'] = $lembur->jam_awal;
	                $rekap[$lembur->p_karyawan_id][$lembur->tgl_awal]['ajuan']['jam_akhir'] = $lembur->jam_akhir;
	                $rekap[$lembur->p_karyawan_id][$lembur->tgl_awal]['ajuan']['total_ajuan'] = 1;
	                
                }else{
                	
	                $rekap[$lembur->p_karyawan_id]['lembur'][$tgl_appr]['total_ajuan'] += 1;
	                $rekap[$lembur->p_karyawan_id][$lembur->tgl_awal]['ajuan']['total_ajuan'] += 1;
	            
	                $rekap[$lembur->p_karyawan_id]['lembur'][$tgl_appr]['lama'] += $lembur->lama;
	                $rekap[$lembur->p_karyawan_id]['lembur'][$tgl_appr]['keterangan'] = '<br><b style="font-weight:700">'.
	                			$rekap[$lembur->p_karyawan_id][$lembur->tgl_awal]['ajuan']['jam_awal'].'</b><br>'.
	                			$rekap[$lembur->p_karyawan_id][$lembur->tgl_awal]['ajuan']['keterangan']
	                			
	                			.'  |  '
	                			.'<b style="font-weight:700">'.$lembur->jam_awal.'</b><br>'.$lembur->keterangan;	 
	                $rekap[$lembur->p_karyawan_id]['lembur'][$tgl_appr]['jam_awal'] = $rekap[$lembur->p_karyawan_id]['lembur'][$tgl_appr]['jam_awal'].' s/d '.$rekap[$lembur->p_karyawan_id]['lembur'][$tgl_appr]['jam_akhir'].' | '.$lembur->jam_awal.' s/d '.$lembur->jam_akhir;
	                $rekap[$lembur->p_karyawan_id]['lembur'][$tgl_appr]['jam_akhir'] = '';
	                
					$rekap[$lembur->p_karyawan_id][$lembur->tgl_awal]['ajuan']['lama'] += $lembur->lama;
	                $rekap[$lembur->p_karyawan_id][$lembur->tgl_awal]['ajuan']['keterangan']= '<br><b style="font-weight:700">'.
	                			$rekap[$lembur->p_karyawan_id][$lembur->tgl_awal]['ajuan']['jam_awal'].'</b><br>'.
	                			$rekap[$lembur->p_karyawan_id][$lembur->tgl_awal]['ajuan']['keterangan']
	                			
	                			.'  |  '
	                			.'<b style="font-weight:700">'.$lembur->jam_awal.'</b><br>'.$lembur->keterangan;
	                $rekap[$lembur->p_karyawan_id][$lembur->tgl_awal]['ajuan']['jam_awal'] =$rekap[$lembur->p_karyawan_id]['lembur'][$tgl_appr]['jam_awal'].' s/d '.$rekap[$lembur->p_karyawan_id]['lembur'][$tgl_appr]['jam_akhir'].' | '.$lembur->jam_awal.' s/d '.$lembur->jam_akhir;
	                $rekap[$lembur->p_karyawan_id][$lembur->tgl_awal]['ajuan']['jam_akhir'] ='';

                }
            
            $rekap[$lembur->p_karyawan_id]['lembur'][$tgl_appr]['tipe_master'] = 5;
            $rekap[$lembur->p_karyawan_id]['lembur'][$tgl_appr]['m_periode_absen_id'] = $periode_absen_id;
            $rekap[$lembur->p_karyawan_id]['lembur'][$tgl_appr]['p_karyawan_id'] = $lembur->p_karyawan_id;
            $rekap[$lembur->p_karyawan_id]['lembur'][$tgl_appr]['tanggal'] = $date;
           // print_r($rekap[$lembur->p_karyawan_id]['lembur'][$tgl_appr]);
            DB::connection()->table("absen_master")->insert($rekap[$lembur->p_karyawan_id]['lembur'][$tgl_appr]);
            
            $rekap[$lembur->p_karyawan_id][$lembur->tgl_awal]['ajuan']['tipe_master'] = 6;
            $rekap[$lembur->p_karyawan_id][$lembur->tgl_awal]['ajuan']['m_periode_absen_id'] = $periode_absen_id;
            $rekap[$lembur->p_karyawan_id][$lembur->tgl_awal]['ajuan']['p_karyawan_id'] = $lembur->p_karyawan_id;
            $rekap[$lembur->p_karyawan_id][$lembur->tgl_awal]['ajuan']['tanggal'] = $date;
            DB::connection()->table("absen_master")->insert($rekap[$lembur->p_karyawan_id][$lembur->tgl_awal]['ajuan']);
            
            DB::connection()->table("t_permit")->where("t_form_exit_id",$lembur->t_form_exit_id)->update(["master"=>1]);     
                		
                if($tgl_awal_lembur_ajuan>=$lembur->tgl_awal)
                		$tgl_awal_lembur_ajuan = $lembur->tgl_awal;
            } else if (
                ($lembur->status_appr_1 == 2 and $lembur->appr_2 == null)
                or  ($lembur->status_appr_1 == 2 and $lembur->status_appr_2 == 2)
                or  ($lembur->appr_1 == null and $lembur->status_appr_2 == 2)
                or  ($lembur->status_appr_1 == 1 and $lembur->status_appr_2 == 2)
            ) {
                $rekap[$lembur->p_karyawan_id]['lembur']['total_tolak'] += (int)$lembur->lama;
            } else {
                $rekap[$lembur->p_karyawan_id]['lembur']['total_pending'] += (int)$lembur->lama;
                $rekap[$lembur->p_karyawan_id]['lembur']['tgl_pending'] .= $lembur->tgl_awal . ' | ';
            }
        }
	        
	    $countklari        = DB::connection()->select("select * from chat_room where  tanggal >= '$tgl_awal' and  tanggal<='$tgl_akhir'  ");
        foreach($countklari as $klarifi){
         	$rekap['klarifikasi'][$klarifi->p_karyawan_create_id][$klarifi->tanggal]['topik'] = $klarifi->topik;
            $rekap['klarifikasi'][$klarifi->p_karyawan_create_id][$klarifi->tanggal]['deskripsi'] = $klarifi->deskripsi;
            
            $rekap['klarifikasi'][$klarifi->p_karyawan_create_id][$klarifi->tanggal]['tipe_master'] = 7;
            $rekap['klarifikasi'][$klarifi->p_karyawan_create_id][$klarifi->tanggal]['m_periode_absen_id'] = $periode_absen_id;
            $rekap['klarifikasi'][$klarifi->p_karyawan_create_id][$klarifi->tanggal]['p_karyawan_create_id'] = $klarifi->p_karyawan_create_id;
            $rekap['klarifikasi'][$klarifi->p_karyawan_create_id][$klarifi->tanggal]['tanggal'] = $date;
            
            DB::connection()->table("absen_master")->insert($rekap['klarifikasi'][$klarifi->p_karyawan_create_id][$klarifi->tanggal]);
            
            
            DB::connection()->table("chat_room")->where("chat_room_id",$klarifi->chat_room_id)->update(["master"=>1]);     
             	
         }
	    
	    
	    
	      DB::connection()->table("absen_master_generate")->where("absen_master_generate_id",$g->absen_master_generate_id)->update(["status"=>1]);     
               
	    
	    if(isset($rekap)){
	    $array['periode_absen_id'] = $periode_absen_id;
	    $array['tanggal'] = $tgl_awal;
	    $array['array_rekap'] = json_encode($rekap);
	    
	    DB::connection()->table('absen_master_rekap')->insert($array);
	    }
	   
	    
	}
	
	public static function exports($param){
			
		$bulan = $param['bulan'];
		$tahun =  $param['tahun'];
		$tgl_awal = $param['tgl_awal'];
		$tgl_akhir =$param['tgl_akhir'];
		$periode_absen   = $param['periode_absen'];
		$periode  = $param['periode'];
		$rekap = $param['rekap'];
		$list_karyawan = $param['list_karyawan'];
		$hari_libur = $param['hari_libur'];
		$hari_libur_shift = $param['hari_libur_shift'];
		$help = New Helper_function();
		$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();
		$sheet->setCellValue('A1', 'NIK');
		$sheet->setCellValue('B1', 'Nama');
		$sheet->setCellValue('C1', 'Departemen');
		$date = $tgl_awal;
		for($i = 3; $i <= $help->hitunghari($tgl_awal,$tgl_akhir)+3; $i++){
			$sheet->setCellValue($help->toAlpha($i).'1', $date);
			//echo ' <th>'.$date.'</th>';
			$date = $help->tambah_tanggal($date,1);
		}
		
		$sheet->setCellValue($help->toAlpha($i).'1','Absen');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'1','Cuti');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'1','IPG');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'1','IHK');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'1','IPD');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'1','IPC');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'1','SAKIT');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'1','ALPA');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'1','TP');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'1','T Absen ');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'1','T Masuk');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'1','T H Kerja');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'1','Terlambat');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'1','IDT');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'1','IPM');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'1','PM');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'1', 'Alpha');
		$rows = 2;

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
		$sheet->getColumnDimension('AO')->setAutoSize(true);
		$sheet->getColumnDimension('AP')->setAutoSize(true);
		$sheet->getColumnDimension('AQ')->setAutoSize(true);
		$sheet->getColumnDimension('AR')->setAutoSize(true);
		$sheet->getColumnDimension('AS')->setAutoSize(true);
		$sheet->getColumnDimension('AT')->setAutoSize(true);
		$sheet->getColumnDimension('AU')->setAutoSize(true);
		$sheet->getColumnDimension('AV')->setAutoSize(true);
		$sheet->getColumnDimension('AW')->setAutoSize(true);
		$sheet->getColumnDimension('AX')->setAutoSize(true);
		$sheet->getColumnDimension('AY')->setAutoSize(true);
		$sheet->getColumnDimension('AZ')->setAutoSize(true);

		if(!empty($list_karyawan)){
	
			foreach($list_karyawan as $list_karyawan){

			
				$sheet->setCellValue('A' . $rows, $list_karyawan->nik);
				$sheet->setCellValue('B' . $rows, $list_karyawan->nama);
				$sheet->setCellValue('C' . $rows, $list_karyawan->departemen);
				$date = $tgl_awal;
				$return = $help->total_rekap_absen_optimasi($rekap,$list_karyawan->p_karyawan_id,'excel',$sheet,$rows);
				$sheet = $return['sheet'];
				$masuk 	= $return['total']['masuk'] ;
				$cuti = $return['total']['cuti'] ;
				$fingerprint = $return['total']['fingerprint'] ;
				$ipg = $return['total']['ipg'] ;
				$ihk = $return['total']['izin'] ;
				$ipd = $return['total']['ipd'] ;
				$ipc = $return['total']['ipc'] ;
				$sakit = $return['total']['sakit'] ;
				$alpha = $return['total']['alpha'] ;
				$terlambat = $return['total']['terlambat'] ;
				$idt = $return['total']['idt'] ;
				$ipm = $return['total']['ipm'] ;
				$pm = $return['total']['pm'] ;
				$alphaList = $return['total']['alphaList'];
				$tabsen = $return['total']['Total Absen'] ;
				$tmasuk = $return['total']['Total Masuk'] ;
				$tkerja = $return['total']['Total Hari Kerja'] ;
				$content_sheet = $return['content_sheet'] ;
				$warna_sheet = $return['warna_sheet'] ;
				$i = $return['i'] ;
				
				$date = $tgl_awal;
		for($i = 3; $i <= Helper_function::hitunghari($tgl_awal, $tgl_akhir) + 3; $i++){
			if(!isset($content_sheet[$date])){
				$content_sheet[$date]='';
				
			}	if(!isset($warna_sheet[$date] )){
				$warna_sheet[$date] = 'FFFFFF';
				
			}	
				if($warna_sheet[$date]!='FFFFFF' and $warna_sheet[$date] != 'FFFF00')
					$font = 'FFFFFF';
					else
					$font = '000000';
				$sheet->setCellValue(Helper_function::toAlpha($i) . $rows, $content_sheet[$date]);
					$spreadsheet->getActiveSheet()->getStyle(Helper_function::toAlpha($i).$rows)->applyFromArray([
							'fill' => [
								'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
								'startColor' => [
									'rgb' => $warna_sheet[$date],
								]
							],
							'font' => [
								'color' => [
									'rgb' => $font,
								]
							]
						]);			
				$date = Helper_function::tambah_tanggal($date, 1);
			
		}
		$sheet->setCellValue($help->toAlpha($i). $rows, $masuk);$i++;
									$sheet->setCellValue($help->toAlpha($i). $rows, $cuti);$i++;
									$sheet->setCellValue($help->toAlpha($i). $rows, $ipg);$i++;
									$sheet->setCellValue($help->toAlpha($i). $rows, $ihk);$i++;
									$sheet->setCellValue($help->toAlpha($i). $rows, $ipd);$i++;
									$sheet->setCellValue($help->toAlpha($i). $rows, $ipc);$i++;
									$sheet->setCellValue($help->toAlpha($i). $rows, $sakit);$i++;
									$sheet->setCellValue($help->toAlpha($i). $rows, $alpha);$i++;
									$sheet->setCellValue($help->toAlpha($i). $rows, $fingerprint);$i++;
									$sheet->setCellValue($help->toAlpha($i). $rows, $tabsen);$i++;
									$sheet->setCellValue($help->toAlpha($i). $rows, $tmasuk);$i++;
									$sheet->setCellValue($help->toAlpha($i). $rows, $tkerja);$i++;
									$sheet->setCellValue($help->toAlpha($i). $rows, $terlambat);$i++;
									$sheet->setCellValue($help->toAlpha($i). $rows, $idt);$i++;
									$sheet->setCellValue($help->toAlpha($i). $rows, $ipm);$i++;
									$sheet->setCellValue($help->toAlpha($i). $rows, $pm);$i++;
									$sheet->setCellValue($help->toAlpha($i). $rows, ''.$alphaList);$i++;
				//$sheet->setCellValue($help->toAlpha($i). $rows, $alphaList);
				//echo substr($absen->a,0,5);die;
				$rows++;
			}
		}
		
		
		$type = 'xlsx';
		$fileName = "absen ".$tgl_awal." - ".$tgl_akhir.".".$type;
		if($type == 'xlsx'){
			$writer = new Xlsx($spreadsheet);
		} else if($type == 'xls'){
			$writer = new Xls($spreadsheet);
		}
		$writer->save("export/".$fileName);
		header("Content-Type: application/vnd.ms-excel");
		return redirect(url('/')."/export/".$fileName);
	}
	public function exportsIzin($param){
			
		$bulan = $param['bulan'];
		$tahun =  $param['tahun'];
		$tgl_awal = $param['tgl_awal'];
		$tgl_akhir =$param['tgl_akhir'];
		$periode_absen   = $param['periode_absen'];
		$periode  = $param['periode'];
		$rekap = $param['rekap'];
		$list_karyawan = $param['list_karyawan'];
		$hari_libur = $param['hari_libur'];
		$hari_libur_shift = $param['hari_libur_shift'];
		$help = New Helper_function();
		$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();
		$sheet->setCellValue('A1', 'NIK');
		$sheet->setCellValue('B1', 'Nama');
		$sheet->setCellValue('C1', 'Departemen');
		$date = $tgl_awal;
		for($i = 3; $i <= $help->hitunghari($tgl_awal,$tgl_akhir)+3; $i++){
			$sheet->setCellValue($help->toAlpha($i).'1', $date);
			//echo ' <th>'.$date.'</th>';
			$date = $help->tambah_tanggal($date,1);
		}
		
		$sheet->setCellValue($help->toAlpha($i).'1', 'IPG');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'1', 'IHK');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'1', 'IPD');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'1', 'Total');	$i++;
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

		if(!empty($list_karyawan)){
	
			foreach($list_karyawan as $list_karyawan){

			
				$sheet->setCellValue('A' . $rows, $list_karyawan->nik);
				$sheet->setCellValue('B' . $rows, $list_karyawan->nama);
				$sheet->setCellValue('C' . $rows, $list_karyawan->departemen);
				$date = $tgl_awal;
				$rekap[$list_karyawan->p_karyawan_id]['total']['cuti'] =0;
			                        
				$rekap[$list_karyawan->p_karyawan_id]['total']['ipd'] = 0;
				$rekap[$list_karyawan->p_karyawan_id]['total']['ihk'] = 0;
				$rekap[$list_karyawan->p_karyawan_id]['total']['ihk'] = 0;
				$rekap[$list_karyawan->p_karyawan_id]['total']['ipg'] = 0;                    
				$rekap[$list_karyawan->p_karyawan_id]['total']['sakit'] = 0;                    
				$rekap[$list_karyawan->p_karyawan_id]['total']['alpha'] = 0;                    
				$rekap[$list_karyawan->p_karyawan_id]['total']['alphaList'] = '';                    
				for($i = 3; $i <= $help->hitunghari($tgl_awal,$tgl_akhir)+3; $i++){
					$content = "";
					$warna = 'FFFFFF';
					
					if(isset($rekap[$list_karyawan->p_karyawan_id][$date]['ci']['nama_ijin'])){
						if(!in_array($help->nama_hari($date),array('Minggu','Sabtu'))  ){
							if(in_array($rekap[$list_karyawan->p_karyawan_id][$date]['ci']['m_jenis_ijin_id'],array(4,7,12,13,14,15,16,17))){
								$rekap[$list_karyawan->p_karyawan_id]['total']['ihk'] += 1;
							}else if($rekap[$list_karyawan->p_karyawan_id][$date]['ci']['m_jenis_ijin_id']==1){
								$rekap[$list_karyawan->p_karyawan_id]['total']['ipg'] += 1;
							}else if($rekap[$list_karyawan->p_karyawan_id][$date]['ci']['m_jenis_ijin_id']==24){
								$rekap[$list_karyawan->p_karyawan_id]['total']['ipd'] += 1;
							}else if($rekap[$list_karyawan->p_karyawan_id][$date]['ci']['m_jenis_ijin_id']==20){
								$rekap[$list_karyawan->p_karyawan_id]['total']['sakit'] += 1;
							}
										
							if($rekap[$list_karyawan->p_karyawan_id][$date]['ci']['tipe']==3){
								$rekap[$list_karyawan->p_karyawan_id]['total']['cuti'] += 1;
											
											
							}
							if($rekap[$list_karyawan->p_karyawan_id][$date]['ci']['tipe']==1)
							$warna = '840184';
							else if($rekap[$list_karyawan->p_karyawan_id][$date]['ci']['tipe']==4)
							$warna = '0000FF';
											
							else if($rekap[$list_karyawan->p_karyawan_id][$date]['ci']['nama_ijin']=='IZIN DATANG TERLAMBAT')
							$warna = 'red';
							if($rekap[$list_karyawan->p_karyawan_id][$date]['ci']['nama_ijin']
								!= 'IZIN DATANG TERLAMBAT' and $rekap[$list_karyawan->p_karyawan_id][$date]['ci']['tipe']!= 1)
														
							$content .= ' '.$rekap[$list_karyawan->p_karyawan_id][$date]['ci']['nama_ijin'].'								';	
							if($rekap[$list_karyawan->p_karyawan_id][$date]['ci']['nama_ijin']
								== 'IZIN PERJALANAN DINAS'){
											
							}
						}
					}
										
					if($content==""){
						$content .= '';
						if(!in_array($help->nama_hari($date),array('Minggu','Sabtu')) and !in_array($date,$hari_libur)   ){
							$rekap[$list_karyawan->p_karyawan_id]['total']['alpha'] += 1;
							$rekap[$list_karyawan->p_karyawan_id]['total']['alphaList'] .= $date.' | ' ;
						}					
					
					}
					
					
					if($warna!='FFFFFF' and $warna != 'FFFF00')
					$font = 'FFFFFF';
					else
					$font = '000000';
						
					$sheet->setCellValue($help->toAlpha($i) . $rows, $content);
					$spreadsheet->getActiveSheet()->getStyle($help->toAlpha($i).$rows)->applyFromArray([
							'fill' => [
								'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
								'startColor' => [
									'rgb' => $warna,
								]
							],
							'font' => [
								'color' => [
									'rgb' => $font,
								]
							]
						]);						
											
					
					$date = $help->tambah_tanggal($date,1);
				}
                        		
			
				
				$masuk = isset($rekap[$list_karyawan->p_karyawan_id]['total']['absen_masuk'])?$rekap[$list_karyawan->p_karyawan_id]['total']['absen_masuk']:0;
				$cuti = isset($rekap[$list_karyawan->p_karyawan_id]['total']['cuti'])?$rekap[$list_karyawan->p_karyawan_id]['total']['cuti']:0;
				$ipg = isset($rekap[$list_karyawan->p_karyawan_id]['total']['ipg'])?$rekap[$list_karyawan->p_karyawan_id]['total']['ipg']:0;
				$ipd = isset($rekap[$list_karyawan->p_karyawan_id]['total']['ipd'])?$rekap[$list_karyawan->p_karyawan_id]['total']['ipd']:0;
				$izin = isset($rekap[$list_karyawan->p_karyawan_id]['total']['ihk'])?$rekap[$list_karyawan->p_karyawan_id]['total']['ihk']:0;
				$alpha = isset($rekap[$list_karyawan->p_karyawan_id]['total']['alpha'])?$rekap[$list_karyawan->p_karyawan_id]['total']['alpha']:0;
				$alphaList = isset($rekap[$list_karyawan->p_karyawan_id]['total']['alphaList'])?$rekap[$list_karyawan->p_karyawan_id]['total']['alphaList']:0;
				$sakit = isset($rekap[$list_karyawan->p_karyawan_id]['total']['sakit'])?$rekap[$list_karyawan->p_karyawan_id]['total']['sakit']:0;
				
				$terlambat = isset($rekap[$list_karyawan->p_karyawan_id]['total']['terlambat'])?$rekap[$list_karyawan->p_karyawan_id]['total']['terlambat']:0;
				
		
				$sheet->setCellValue($help->toAlpha($i). $rows, $ipg);$i++;
				$sheet->setCellValue($help->toAlpha($i). $rows, $izin);$i++;
				$sheet->setCellValue($help->toAlpha($i). $rows, $ipd);$i++;
				$sheet->setCellValue($help->toAlpha($i).$rows, $ipg+$izin+$ipd);$i++;
				//echo substr($absen->a,0,5);die;
				$rows++;
			}
		}
		
		
		$type = 'xlsx';
		$fileName = "Izin ".$tgl_awal." - ".$tgl_akhir.".".$type;
		if($type == 'xlsx'){
			$writer = new Xlsx($spreadsheet);
		} else if($type == 'xls'){
			$writer = new Xls($spreadsheet);
		}
		$writer->save("export/".$fileName);
		header("Content-Type: application/vnd.ms-excel");
		return redirect(url('/')."/export/".$fileName);
	}
	public function exportsPerdin($param){
			
		$bulan = $param['bulan'];
		$tahun =  $param['tahun'];
		$tgl_awal = $param['tgl_awal'];
		$tgl_akhir =$param['tgl_akhir'];
		$periode_absen   = $param['periode_absen'];
		$periode  = $param['periode'];
		$rekap = $param['rekap'];
		$list_karyawan = $param['list_karyawan'];
		$hari_libur = $param['hari_libur'];
		$hari_libur_shift = $param['hari_libur_shift'];
		$help = New Helper_function();
		$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();
		$sheet->setCellValue('A1', 'NIK');
		$sheet->setCellValue('B1', 'Nama');
		$sheet->setCellValue('C1', 'Departemen');
		$date = $tgl_awal;
		for($i = 3; $i <= $help->hitunghari($tgl_awal,$tgl_akhir)+3; $i++){
			$sheet->setCellValue($help->toAlpha($i).'1', $date);
			//echo ' <th>'.$date.'</th>';
			$date = $help->tambah_tanggal($date,1);
		}
		
		$sheet->setCellValue($help->toAlpha($i).'1', 'IPG');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'1', 'IHK');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'1', 'IPD');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'1', 'Total');	$i++;
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

		if(!empty($list_karyawan)){
	
			foreach($list_karyawan as $list_karyawan){

			
				$sheet->setCellValue('A' . $rows, $list_karyawan->nik);
				$sheet->setCellValue('B' . $rows, $list_karyawan->nama);
				$sheet->setCellValue('C' . $rows, $list_karyawan->departemen);
				$date = $tgl_awal;
				$rekap[$list_karyawan->p_karyawan_id]['total']['cuti'] =0;
			                        
				$rekap[$list_karyawan->p_karyawan_id]['total']['ipd'] = 0;
				$rekap[$list_karyawan->p_karyawan_id]['total']['ihk'] = 0;
				$rekap[$list_karyawan->p_karyawan_id]['total']['ihk'] = 0;
				$rekap[$list_karyawan->p_karyawan_id]['total']['ipg'] = 0;                    
				$rekap[$list_karyawan->p_karyawan_id]['total']['sakit'] = 0;                    
				$rekap[$list_karyawan->p_karyawan_id]['total']['alpha'] = 0;                    
				$rekap[$list_karyawan->p_karyawan_id]['total']['alphaList'] = '';                    
				for($i = 3; $i <= $help->hitunghari($tgl_awal,$tgl_akhir)+3; $i++){
					$content = "";
					$warna = 'FFFFFF';
					
					if(isset($rekap[$list_karyawan->p_karyawan_id][$date]['ci']['nama_ijin'])){
						if(!in_array($help->nama_hari($date),array('Minggu','Sabtu'))  ){
							if(in_array($rekap[$list_karyawan->p_karyawan_id][$date]['ci']['m_jenis_ijin_id'],array(4,7,12,13,14,15,16,17))){
								$rekap[$list_karyawan->p_karyawan_id]['total']['ihk'] += 1;
							}else if($rekap[$list_karyawan->p_karyawan_id][$date]['ci']['m_jenis_ijin_id']==1){
								$rekap[$list_karyawan->p_karyawan_id]['total']['ipg'] += 1;
							}else if($rekap[$list_karyawan->p_karyawan_id][$date]['ci']['m_jenis_ijin_id']==24){
								$rekap[$list_karyawan->p_karyawan_id]['total']['ipd'] += 1;
							}else if($rekap[$list_karyawan->p_karyawan_id][$date]['ci']['m_jenis_ijin_id']==20){
								$rekap[$list_karyawan->p_karyawan_id]['total']['sakit'] += 1;
							}
										
							if($rekap[$list_karyawan->p_karyawan_id][$date]['ci']['tipe']==3){
								$rekap[$list_karyawan->p_karyawan_id]['total']['cuti'] += 1;
											
											
							}
									
							else if($rekap[$list_karyawan->p_karyawan_id][$date]['ci']['tipe']==4)
							$warna = '0000FF';
											
							else if($rekap[$list_karyawan->p_karyawan_id][$date]['ci']['nama_ijin']=='IZIN DATANG TERLAMBAT')
							$warna = 'red';
							if($rekap[$list_karyawan->p_karyawan_id][$date]['ci']['nama_ijin'] != 'IZIN DATANG TERLAMBAT' and $rekap[$list_karyawan->p_karyawan_id][$date]['ci']['tipe']== 4)
							$content .= ' '.$rekap[$list_karyawan->p_karyawan_id][$date]['ci']['nama_ijin'].'								';	
							if($rekap[$list_karyawan->p_karyawan_id][$date]['ci']['nama_ijin']
								== 'IZIN PERJALANAN DINAS'){
											
							}
						}
					}
										
					if($content==""){
						$content .= '';
						if(!in_array($help->nama_hari($date),array('Minggu','Sabtu')) and !in_array($date,$hari_libur)  and !isset($hari_libur_shift[$date][$list_karyawan->p_karyawan_id])    ){
							$rekap[$list_karyawan->p_karyawan_id]['total']['alpha'] += 1;
							$rekap[$list_karyawan->p_karyawan_id]['total']['alphaList'] .= $date.' | ' ;
						}					
					
					}
					
					
					if($warna!='FFFFFF' and $warna != 'FFFF00')
					$font = 'FFFFFF';
					else
					$font = '000000';
						
					$sheet->setCellValue($help->toAlpha($i) . $rows, $content);
					$spreadsheet->getActiveSheet()->getStyle($help->toAlpha($i).$rows)->applyFromArray([
							'fill' => [
								'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
								'startColor' => [
									'rgb' => $warna,
								]
							],
							'font' => [
								'color' => [
									'rgb' => $font,
								]
							]
						]);						
											
					
					$date = $help->tambah_tanggal($date,1);
				}
                        		
			
				
				$masuk = isset($rekap[$list_karyawan->p_karyawan_id]['total']['absen_masuk'])?$rekap[$list_karyawan->p_karyawan_id]['total']['absen_masuk']:0;
				$cuti = isset($rekap[$list_karyawan->p_karyawan_id]['total']['cuti'])?$rekap[$list_karyawan->p_karyawan_id]['total']['cuti']:0;
				$ipg = isset($rekap[$list_karyawan->p_karyawan_id]['total']['ipg'])?$rekap[$list_karyawan->p_karyawan_id]['total']['ipg']:0;
				$ipd = isset($rekap[$list_karyawan->p_karyawan_id]['total']['ipd'])?$rekap[$list_karyawan->p_karyawan_id]['total']['ipd']:0;
				$izin = isset($rekap[$list_karyawan->p_karyawan_id]['total']['ihk'])?$rekap[$list_karyawan->p_karyawan_id]['total']['ihk']:0;
				$alpha = isset($rekap[$list_karyawan->p_karyawan_id]['total']['alpha'])?$rekap[$list_karyawan->p_karyawan_id]['total']['alpha']:0;
				$alphaList = isset($rekap[$list_karyawan->p_karyawan_id]['total']['alphaList'])?$rekap[$list_karyawan->p_karyawan_id]['total']['alphaList']:0;
				$sakit = isset($rekap[$list_karyawan->p_karyawan_id]['total']['sakit'])?$rekap[$list_karyawan->p_karyawan_id]['total']['sakit']:0;
				
				$terlambat = isset($rekap[$list_karyawan->p_karyawan_id]['total']['terlambat'])?$rekap[$list_karyawan->p_karyawan_id]['total']['terlambat']:0;
				
		
				$sheet->setCellValue($help->toAlpha($i). $rows, $ipg);$i++;
				$sheet->setCellValue($help->toAlpha($i). $rows, $izin);$i++;
				$sheet->setCellValue($help->toAlpha($i). $rows, $ipd);$i++;
				$sheet->setCellValue($help->toAlpha($i).$rows, $ipg+$izin+$ipd);$i++;
				//echo substr($absen->a,0,5);die;
				$rows++;
			}
		}
		
		
		$type = 'xlsx';
		$fileName = "Perdin ".$tgl_awal." - ".$tgl_akhir.".".$type;
		if($type == 'xlsx'){
			$writer = new Xlsx($spreadsheet);
		} else if($type == 'xls'){
			$writer = new Xls($spreadsheet);
		}
		$writer->save("export/".$fileName);
		header("Content-Type: application/vnd.ms-excel");
		return redirect(url('/')."/export/".$fileName);
	}
	public function exportsCuti($param){
			
		$bulan 			= $param['bulan'];
		$tahun 			=  $param['tahun'];
		$tgl_awal 		= $param['tgl_awal'];
		$tgl_akhir 		=$param['tgl_akhir'];
		$periode_absen  = $param['periode_absen'];
		$periode  		= $param['periode'];
		$rekap 			= $param['rekap'];
		$list_karyawan 	= $param['list_karyawan'];
		$hari_libur = $param['hari_libur'];
		$hari_libur_shift = $param['hari_libur_shift'];
		$help = New Helper_function();
		$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();
		$sheet->setCellValue('A1', 'NIK');
		$sheet->setCellValue('B1', 'Nama');
		$sheet->setCellValue('C1', 'Departemen');
		$date = $tgl_awal;
		for($i = 3; $i <= $help->hitunghari($tgl_awal,$tgl_akhir)+3; $i++){
			$sheet->setCellValue($help->toAlpha($i).'1', $date);
			//echo ' <th>'.$date.'</th>';
			$date = $help->tambah_tanggal($date,1);
		}
		
		$sheet->setCellValue($help->toAlpha($i).'1', 'Total');	$i++;
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

		if(!empty($list_karyawan)){
	
			foreach($list_karyawan as $list_karyawan){

			
				$sheet->setCellValue('A' . $rows, $list_karyawan->nik);
				$sheet->setCellValue('B' . $rows, $list_karyawan->nama);
				$sheet->setCellValue('C' . $rows, $list_karyawan->departemen);
				$date = $tgl_awal;
				$rekap[$list_karyawan->p_karyawan_id]['total']['cuti'] =0;
			                        
				$rekap[$list_karyawan->p_karyawan_id]['total']['ipd'] = 0;
				$rekap[$list_karyawan->p_karyawan_id]['total']['ihk'] = 0;
				$rekap[$list_karyawan->p_karyawan_id]['total']['ihk'] = 0;
				$rekap[$list_karyawan->p_karyawan_id]['total']['ipg'] = 0;                    
				$rekap[$list_karyawan->p_karyawan_id]['total']['sakit'] = 0;                    
				$rekap[$list_karyawan->p_karyawan_id]['total']['alpha'] = 0;                    
				$rekap[$list_karyawan->p_karyawan_id]['total']['alphaList'] = '';                    
				for($i = 3; $i <= $help->hitunghari($tgl_awal,$tgl_akhir)+3; $i++){
					$content = "";
					$warna = 'FFFFFF';
					
					if(isset($rekap[$list_karyawan->p_karyawan_id][$date]['ci']['nama_ijin'])){
						if(!in_array($help->nama_hari($date),array('Minggu','Sabtu'))  ){
							if(in_array($rekap[$list_karyawan->p_karyawan_id][$date]['ci']['m_jenis_ijin_id'],array(4,7,12,13,14,15,16,17))){
								$rekap[$list_karyawan->p_karyawan_id]['total']['ihk'] += 1;
							}else if($rekap[$list_karyawan->p_karyawan_id][$date]['ci']['m_jenis_ijin_id']==1){
								$rekap[$list_karyawan->p_karyawan_id]['total']['ipg'] += 1;
							}else if($rekap[$list_karyawan->p_karyawan_id][$date]['ci']['m_jenis_ijin_id']==24){
								$rekap[$list_karyawan->p_karyawan_id]['total']['ipd'] += 1;
							}else if($rekap[$list_karyawan->p_karyawan_id][$date]['ci']['m_jenis_ijin_id']==20){
								$rekap[$list_karyawan->p_karyawan_id]['total']['sakit'] += 1;
							}
										
							if($rekap[$list_karyawan->p_karyawan_id][$date]['ci']['tipe']==3){
								$rekap[$list_karyawan->p_karyawan_id]['total']['cuti'] += 1;
											
											
							}
							if($rekap[$list_karyawan->p_karyawan_id][$date]['ci']['tipe']==3)
							$warna = '017701';
											
							else if($rekap[$list_karyawan->p_karyawan_id][$date]['ci']['nama_ijin']=='IZIN DATANG TERLAMBAT')
							$warna = 'red';
							if($rekap[$list_karyawan->p_karyawan_id][$date]['ci']['nama_ijin'] != 'IZIN DATANG TERLAMBAT' and $rekap[$list_karyawan->p_karyawan_id][$date]['ci']['tipe']== 3)
							$content .= ' '.$rekap[$list_karyawan->p_karyawan_id][$date]['ci']['nama_ijin'].'								';	
							if($rekap[$list_karyawan->p_karyawan_id][$date]['ci']['nama_ijin']
								== 'IZIN PERJALANAN DINAS'){
											
							}
						}
					}
										
					if($content==""){
						$content .= '';
						if(!in_array($help->nama_hari($date),array('Minggu','Sabtu')) and !in_array($date,$hari_libur)  and !isset($hari_libur_shift[$date][$list_karyawan->p_karyawan_id])     ){
							$rekap[$list_karyawan->p_karyawan_id]['total']['alpha'] += 1;
							$rekap[$list_karyawan->p_karyawan_id]['total']['alphaList'] .= $date.' | ' ;
						}					
					
					}
					
					
					if($warna!='FFFFFF' and $warna != 'FFFF00')
					$font = 'FFFFFF';
					else
					$font = '000000';
						
					$sheet->setCellValue($help->toAlpha($i) . $rows, $content);
					$spreadsheet->getActiveSheet()->getStyle($help->toAlpha($i).$rows)->applyFromArray([
							'fill' => [
								'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
								'startColor' => [
									'rgb' => $warna,
								]
							],
							'font' => [
								'color' => [
									'rgb' => $font,
								]
							]
						]);						
											
					
					$date = $help->tambah_tanggal($date,1);
				}
                        		
			
				
				$masuk = isset($rekap[$list_karyawan->p_karyawan_id]['total']['absen_masuk'])?$rekap[$list_karyawan->p_karyawan_id]['total']['absen_masuk']:0;
				$cuti = isset($rekap[$list_karyawan->p_karyawan_id]['total']['cuti'])?$rekap[$list_karyawan->p_karyawan_id]['total']['cuti']:0;
				$ipg = isset($rekap[$list_karyawan->p_karyawan_id]['total']['ipg'])?$rekap[$list_karyawan->p_karyawan_id]['total']['ipg']:0;
				$ipd = isset($rekap[$list_karyawan->p_karyawan_id]['total']['ipd'])?$rekap[$list_karyawan->p_karyawan_id]['total']['ipd']:0;
				$izin = isset($rekap[$list_karyawan->p_karyawan_id]['total']['ihk'])?$rekap[$list_karyawan->p_karyawan_id]['total']['ihk']:0;
				$alpha = isset($rekap[$list_karyawan->p_karyawan_id]['total']['alpha'])?$rekap[$list_karyawan->p_karyawan_id]['total']['alpha']:0;
				$alphaList = isset($rekap[$list_karyawan->p_karyawan_id]['total']['alphaList'])?$rekap[$list_karyawan->p_karyawan_id]['total']['alphaList']:0;
				$sakit = isset($rekap[$list_karyawan->p_karyawan_id]['total']['sakit'])?$rekap[$list_karyawan->p_karyawan_id]['total']['sakit']:0;
				
				$terlambat = isset($rekap[$list_karyawan->p_karyawan_id]['total']['terlambat'])?$rekap[$list_karyawan->p_karyawan_id]['total']['terlambat']:0;
				
		
				$sheet->setCellValue($help->toAlpha($i). $rows, $cuti);$i++;
				//echo substr($absen->a,0,5);die;
				$rows++;
			}
		}
		
		
		$type = 'xlsx';
		$fileName = "Cuti ".$tgl_awal." - ".$tgl_akhir.".".$type;
		if($type == 'xlsx'){
			$writer = new Xlsx($spreadsheet);
		} else if($type == 'xls'){
			$writer = new Xls($spreadsheet);
		}
		$writer->save("export/".$fileName);
		header("Content-Type: application/vnd.ms-excel");
		return redirect(url('/')."/export/".$fileName);
	}
	public function exportsLembur($param){
			
		$bulan = $param['bulan'];
		$tahun =  $param['tahun'];
		$tgl_awal = $param['tgl_awal'];
		$tgl_akhir =$param['tgl_akhir'];
		$periode_absen   = $param['periode_absen'];
		$periode  = $param['periode'];
		$rekap = $param['rekap'];
		$list_karyawan = $param['list_karyawan'];
		$hari_libur =   $param['hari_libur']  ;
		$hari_libur_shift = $param['hari_libur_shift']; 
		$help = New Helper_function();
		$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();
		$sheet->setCellValue('A1', 'Nama');
		$sheet->setCellValue('B1', 'Entitas');
		$sheet->setCellValue('C1', 'Jabatan');
		$date = $tgl_awal;
		for($i = 3; $i <= $help->hitunghari($tgl_awal,$tgl_akhir)+3; $i++){
			$sheet->setCellValue($help->toAlpha($i).'1', $help->nama_hari($date));
			//echo ' <th>'.$date.'</th>';
			$date = $help->tambah_tanggal($date,1);
		}
		
		$sheet->setCellValue($help->toAlpha($i).'1', 'Hari Kerja');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'1', '');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'1', '');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'1', '');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'1', 'Hari Libur');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'1', '');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'1', '');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'1', '');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'1', '');	$i++; 
		$sheet->setCellValue($help->toAlpha($i).'1', '');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'1', 'TOTAL');$i++;	
		$sheet->setCellValue($help->toAlpha($i).'1', 'TOTAL PENGAJUAN');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'1', 'TOTAL APPROVE');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'1', 'TOTAL TOLAK');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'1', 'TOTAL PENDING');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'1', 'TANGGAL PENDING');$i++;	
		
		$sheet->setCellValue('A2', '');
		$sheet->setCellValue('B2', '');
		$sheet->setCellValue('C2', '');
		$date = $tgl_awal;
		for($i = 3; $i <= $help->hitunghari($tgl_awal,$tgl_akhir)+3; $i++){
			$sheet->setCellValue($help->toAlpha($i).'2', $help->tgl_indo_short($date));
			//echo ' <th>'.$date.'</th>';
			$date = $help->tambah_tanggal($date,1);
		}
		
		$sheet->setCellValue($help->toAlpha($i).'2', '1 jam');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'2', '>=2 jam');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'2', 'COUNT');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'2', 'SUM');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'2', '8 jam');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'2', '9 jam');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'2', '>=10 jam');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'2', 'COUNT');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'2', 'SUM');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'2', '');	
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

		if(!empty($list_karyawan)){
	
			foreach($list_karyawan as $list_karyawan){
				$total_all=0;
				$total['<8 jam'] =0;
				$total['8 jam'] =0;
				$total['9 jam'] =0;
				$total['>=10 jam'] =0;
				$total['COUNT Libur'] =0;
				$total['SUM Libur'] =0;
				$total['1jam'] =0;
				$total['>=2jam'] =0;
				$total['COUNT Kerja'] =0;
				$total['SUM Kerja'] =0;
			
				$sheet->setCellValue('A' . $rows, $list_karyawan->nama);
				$sheet->setCellValue('B' . $rows, $list_karyawan->nmlokasi);
				$sheet->setCellValue('C' . $rows, $list_karyawan->nmjabatan);
				$date = $tgl_awal;
			                        
				for($i = 3; $i <= $help->hitunghari($tgl_awal,$tgl_akhir)+3; $i++){
					$content = "";
					$warna = '';
					$font = '';
					if(in_array($help->nama_hari($date),array('Sabtu','Minggu')) or 
						in_array(($date),$hari_libur)
						or isset($hari_libur_shift[$date][$list_karyawan->p_karyawan_id])  
					){
						$warna	='FF0101';	
						$font = 'FFFFFF';	
					}
											
										
					if(isset($rekap['lembur']['ajuan'][$list_karyawan->p_karyawan_id][$date]['lama'])){
						$total_all += $rekap['lembur']['ajuan'][$list_karyawan->p_karyawan_id][$date]['lama'];
						if(in_array($help->nama_hari($date),array('Sabtu','Minggu')) or 
							in_array(($date),$hari_libur)
							or isset($hari_libur_shift[$date][$list_karyawan->p_karyawan_id])  
						){
			                        				
							$lama = $rekap['lembur']['ajuan'][$list_karyawan->p_karyawan_id][$date]['lama'];
							if($lama>8){
								$total['8 jam'] +=8; $lama-=8;
							}else if($lama<=8){
								$total['8 jam'] +=$lama; $lama-=$lama;
							}
							if($lama){
														
								$total['9 jam'] +=1;$lama-=1;
							}
							if($lama)
							$total['>=10 jam'] +=$lama;
														
													
							$total['COUNT Libur'] +=1;
							$total['SUM Libur'] +=$rekap['lembur']['ajuan'][$list_karyawan->p_karyawan_id][$date]['lama'];
						}
						else{
							$lama = $rekap['lembur']['ajuan'][$list_karyawan->p_karyawan_id][$date]['lama'];
							$total['1jam'] +=1;$lama-=1;
							if($lama)
							$total['>=2jam'] +=$lama;
													
							$total['COUNT Kerja'] +=1;
							$total['SUM Kerja'] +=$rekap['lembur']['ajuan'][$list_karyawan->p_karyawan_id][$date]['lama'];
						}
						$content .= ''.$rekap['lembur']['ajuan'][$list_karyawan->p_karyawan_id][$date]['lama'].'';
											
					}else{
											
						$content .= '0';
					}
										
						
					$sheet->setCellValue($help->toAlpha($i) . $rows, $content);
					if($warna){
						
						$spreadsheet->getActiveSheet()->getStyle($help->toAlpha($i).$rows)->applyFromArray([
							
								'font' => [
									'color' => [
										'rgb' => $warna,
									]
								]
							]);						
											
					}
					
					$date = $help->tambah_tanggal($date,1);
				}
                        		
				$total_pengajuan  = isset($rekap['lembur']['ajuan'][$list_karyawan->p_karyawan_id])? $rekap['lembur']['ajuan'][$list_karyawan->p_karyawan_id]['total_pengajuan']:0; 
				$total_approve = isset($rekap['lembur']['ajuan'][$list_karyawan->p_karyawan_id])? $rekap['lembur']['ajuan'][$list_karyawan->p_karyawan_id]['total_approve']:0; 
				$total_tolak = isset($rekap['lembur']['ajuan'][$list_karyawan->p_karyawan_id])? $rekap['lembur']['ajuan'][$list_karyawan->p_karyawan_id]['total_tolak']:0; 
				$total_pending = isset($rekap['lembur']['ajuan'][$list_karyawan->p_karyawan_id])? $rekap['lembur']['ajuan'][$list_karyawan->p_karyawan_id]['total_pending']:0;
				$tgl_pending =                isset($rekap['lembur']['ajuan'][$list_karyawan->p_karyawan_id])? $rekap['lembur']['ajuan'][$list_karyawan->p_karyawan_id]['tgl_pending']:'-';
                               
				$sheet->setCellValue($help->toAlpha($i). $rows, $total['1jam']);$i++;
				$sheet->setCellValue($help->toAlpha($i). $rows, $total['>=2jam']);$i++;
				$sheet->setCellValue($help->toAlpha($i). $rows, $total['COUNT Kerja']);$i++;
				$sheet->setCellValue($help->toAlpha($i). $rows, $total['SUM Kerja'].' ');$i++;
				$sheet->setCellValue($help->toAlpha($i). $rows, $total['8 jam']);$i++;
				$sheet->setCellValue($help->toAlpha($i). $rows, $total['9 jam']);$i++;
				$sheet->setCellValue($help->toAlpha($i).$rows, $total['>=10 jam']);$i++;
				$sheet->setCellValue($help->toAlpha($i).$rows, $total['COUNT Libur']);$i++;
				$sheet->setCellValue($help->toAlpha($i). $rows, $total['SUM Libur'].' ');$i++;
				$sheet->setCellValue($help->toAlpha($i). $rows, $total_all.' ');$i++;
				$sheet->setCellValue($help->toAlpha($i). $rows, $total_pengajuan.' ');$i++;
				$sheet->setCellValue($help->toAlpha($i). $rows, $total_approve.' ');$i++; 
				$sheet->setCellValue($help->toAlpha($i). $rows, $total_tolak.' ');$i++; 
				$sheet->setCellValue($help->toAlpha($i). $rows, $total_pending.' ');$i++;
				$sheet->setCellValue($help->toAlpha($i). $rows, $tgl_pending.'');$i++;
				//echo substr($absen->a,0,5);die;
				$rows++;
			}
		}
		
		
		$type = 'xlsx';
		$fileName = "Rekap Lembur ".$tgl_awal." - ".$tgl_akhir.".".$type;
		if($type == 'xlsx'){
			$writer = new Xlsx($spreadsheet);
		} else if($type == 'xls'){
			$writer = new Xls($spreadsheet);
		}
		$writer->save("export/".$fileName);
		header("Content-Type: application/vnd.ms-excel");
		return redirect(url('/')."/export/".$fileName);
	}
    public function exportsLemburApprove($param){
			
		$bulan = $param['bulan'];
		$tahun =  $param['tahun'];
		$tgl_awal = $param['tgl_awal'];
		$tgl_akhir =$param['tgl_akhir'];
		
		$tgl_awal_lembur = $param['tgl_awal_lembur'] ;
		$periode_absen   = $param['periode_absen'];
		$periode  = $param['periode'];
		$rekap = $param['rekap'];
		$list_karyawan = $param['list_karyawan'];
		$hari_libur =   $param['hari_libur']  ;
		$hari_libur_shift = $param['hari_libur_shift']; 
		$help = New Helper_function();
		$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();
		$sheet->setCellValue('A1', 'Nama');
		$sheet->setCellValue('B1', 'Entitas');
		$sheet->setCellValue('C1', 'Jabatan');
		$date = $tgl_awal_lembur;
		for($i = 3; $i <= $help->hitunghari($tgl_awal_lembur,$tgl_akhir)+3; $i++){
			$sheet->setCellValue($help->toAlpha($i).'1', $help->nama_hari($date));
			//echo ' <th>'.$date.'</th>';
			$date = $help->tambah_tanggal($date,1);
		}
		
		$sheet->setCellValue($help->toAlpha($i).'1', 'Hari Kerja');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'1', '');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'1', '');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'1', '');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'1', 'Hari Libur');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'1', '');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'1', '');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'1', '');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'1', '');	$i++; 
		$sheet->setCellValue($help->toAlpha($i).'1', 'TOTAL');$i++;	
		$sheet->setCellValue($help->toAlpha($i).'1', 'TOTAL PENGAJUAN');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'1', 'TOTAL APPROVE');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'1', 'TOTAL TOLAK');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'1', 'TOTAL PENDING');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'1', 'TANGGAL PENDING');$i++;	
		
		$sheet->setCellValue('A2', '');
		$sheet->setCellValue('B2', '');
		$sheet->setCellValue('C2', '');
		$date = $tgl_awal_lembur;
		for($i = 3; $i <= $help->hitunghari($tgl_awal_lembur,$tgl_akhir)+3; $i++){
			$sheet->setCellValue($help->toAlpha($i).'2', $help->tgl_indo_short($date));
			//echo ' <th>'.$date.'</th>';
			$date = $help->tambah_tanggal($date,1);
		}
		
		$sheet->setCellValue($help->toAlpha($i).'2', '1 jam');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'2', '>=2 jam');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'2', 'COUNT');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'2', 'SUM');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'2', '8 jam');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'2', '9 jam');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'2', '>=10 jam');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'2', 'COUNT');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'2', 'SUM');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'2', '');	
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

		if(!empty($list_karyawan)){
	
			foreach($list_karyawan as $list_karyawan){
				$total_all=0;
				$total['<8 jam'] =0;
				$total['8 jam'] =0;
				$total['9 jam'] =0;
				$total['>=10 jam'] =0;
				$total['COUNT Libur'] =0;
				$total['SUM Libur'] =0;
				$total['1jam'] =0;
				$total['>=2jam'] =0;
				$total['COUNT Kerja'] =0;
				$total['SUM Kerja'] =0;
			
				$sheet->setCellValue('A' . $rows, $list_karyawan->nama);
				$sheet->setCellValue('B' . $rows, $list_karyawan->nmlokasi);
				$sheet->setCellValue('C' . $rows, $list_karyawan->nmjabatan);
				$date = $tgl_awal_lembur;
			                        
				for($i = 3; $i <= $help->hitunghari($tgl_awal_lembur,$tgl_akhir)+3; $i++){
					$content = "";
					$warna = '';
					$font = '';
					if(in_array($help->nama_hari($date),array('Sabtu','Minggu')) or 
						in_array(($date),$hari_libur)
						or isset($hari_libur_shift[$date][$list_karyawan->p_karyawan_id])  
					){
						$warna	='FF0101';	
						$font = 'FFFFFF';	
					}
											
						$id_karyawan = $list_karyawan->p_karyawan_id;
									
									if (!$list_karyawan->is_karyawan_shift)
										$bool_hari_libur = !(in_array($help->nama_hari($date),array('Minggu','Sabtu')) or in_array($date,$hari_libur) or isset($hari_libur_shift[$date][$id_karyawan]) );
									else
										$bool_hari_libur =!(isset($hari_libur_shift[$date][$id_karyawan])) ;
									
									
									if (!$bool_hari_libur)
										$color = 'Style="background:red;color:white"';
									else
										$color='';
				
					if(isset($rekap[$list_karyawan->p_karyawan_id][$date]['lama'])){
						$total_all += $rekap[$list_karyawan->p_karyawan_id][$date]['lama'];
						if(in_array($help->nama_hari($date),array('Sabtu','Minggu')) or 
							in_array(($date),$hari_libur)
							or isset($hari_libur_shift[$date][$list_karyawan->p_karyawan_id])  
						){
			                        				
							$lama = $rekap[$list_karyawan->p_karyawan_id][$date]['lama'];
							if($lama>8){
								$total['8 jam'] +=8; $lama-=8;
							}else if($lama<=8){
								$total['8 jam'] +=$lama; $lama-=$lama;
							}
							if($lama){
														
								$total['9 jam'] +=1;$lama-=1;
							}
							if($lama)
							$total['>=10 jam'] +=$lama;
														
													
							$total['COUNT Libur'] +=1;
							$total['SUM Libur'] +=$rekap[$list_karyawan->p_karyawan_id][$date]['lama'];
						}
						else{
							$lama = $rekap[$list_karyawan->p_karyawan_id][$date]['lama'];
							$total['1jam'] +=1;$lama-=1;
							if($lama)
							$total['>=2jam'] +=$lama;
													
							$total['COUNT Kerja'] +=1;
							$total['SUM Kerja'] +=$rekap[$list_karyawan->p_karyawan_id][$date]['lama'];
						}
						$content .= ''.$rekap[$list_karyawan->p_karyawan_id][$date]['lama'].'';
											
					}else if (isset($rekap['lembur']['approve'][$list_karyawan->p_karyawan_id][$date]['lama'])) {
										$total_all += $rekap['lembur']['approve'][$list_karyawan->p_karyawan_id][$date]['lama'];
										
										
										if (!$bool_hari_libur) {
											$lama = $rekap['lembur']['approve'][$list_karyawan->p_karyawan_id][$date]['lama'];
											if ($lama>8) {
												$total['8 jam'] +=8; $lama-=8;
											} else if ($lama<=8) {
												$total['8 jam'] +=$lama; $lama-=$lama;
											}
											if ($lama) {

												$total['9 jam'] +=1;$lama-=1;
											}
											if ($lama)
												$total['>=10 jam'] +=$lama;


											$total['COUNT Libur'] +=1;
											$total['SUM Libur'] +=$rekap['lembur']['approve'][$list_karyawan->p_karyawan_id][$date]['lama'];
										} else {
											$lama = $rekap['lembur']['approve'][$list_karyawan->p_karyawan_id][$date]['lama'];
											$total['1jam'] +=1;$lama-=1;
											if ($lama)
												$total['>=2jam'] +=$lama;


											$total['COUNT Kerja'] +=1;
											$total['SUM Kerja'] +=$rekap['lembur']['approve'][$list_karyawan->p_karyawan_id][$date]['lama'];
										}
										$content .= ''.$rekap['lembur']['approve'][$list_karyawan->p_karyawan_id][$date]['lama'].'';
					}else{
											
						$content .= '0';
					}
										
						
					$sheet->setCellValue($help->toAlpha($i) . $rows, $content);
					if($warna){
						
						$spreadsheet->getActiveSheet()->getStyle($help->toAlpha($i).$rows)->applyFromArray([
							
								'font' => [
									'color' => [
										'rgb' => $warna,
									]
								]
							]);						
											
					}
					
					$date = $help->tambah_tanggal($date,1);
				}
                        		
				$total_pengajuan  = isset($rekap[$list_karyawan->p_karyawan_id]['lembur'])? $rekap[$list_karyawan->p_karyawan_id]['lembur']['total_pengajuan']:0; 
				$total_approve = isset($rekap[$list_karyawan->p_karyawan_id]['lembur'])? $rekap[$list_karyawan->p_karyawan_id]['lembur']['total_approve']:0; 
				$total_tolak = isset($rekap[$list_karyawan->p_karyawan_id]['lembur'])? $rekap[$list_karyawan->p_karyawan_id]['lembur']['total_tolak']:0; 
				$total_pending = isset($rekap[$list_karyawan->p_karyawan_id]['lembur'])? $rekap[$list_karyawan->p_karyawan_id]['lembur']['total_pending']:0;
				$tgl_pending =                isset($rekap[$list_karyawan->p_karyawan_id]['lembur'])? $rekap[$list_karyawan->p_karyawan_id]['lembur']['tgl_pending']:'-';
                               
				$sheet->setCellValue($help->toAlpha($i). $rows, $total['1jam']);$i++;
				$sheet->setCellValue($help->toAlpha($i). $rows, $total['>=2jam']);$i++;
				$sheet->setCellValue($help->toAlpha($i). $rows, $total['COUNT Kerja']);$i++;
				$sheet->setCellValue($help->toAlpha($i). $rows, $total['SUM Kerja'].' ');$i++;
				$sheet->setCellValue($help->toAlpha($i). $rows, $total['8 jam']);$i++;
				$sheet->setCellValue($help->toAlpha($i). $rows, $total['9 jam']);$i++;
				$sheet->setCellValue($help->toAlpha($i).$rows, $total['>=10 jam']);$i++;
				$sheet->setCellValue($help->toAlpha($i).$rows, $total['COUNT Libur']);$i++;
				$sheet->setCellValue($help->toAlpha($i). $rows, $total['SUM Libur'].' ');$i++;
				$sheet->setCellValue($help->toAlpha($i). $rows, $total_all.' ');$i++;
				$sheet->setCellValue($help->toAlpha($i). $rows, $total_pengajuan.' ');$i++;
				$sheet->setCellValue($help->toAlpha($i). $rows, $total_approve.' ');$i++; 
				$sheet->setCellValue($help->toAlpha($i). $rows, $total_tolak.' ');$i++; 
				$sheet->setCellValue($help->toAlpha($i). $rows, $total_pending.' ');$i++;
				$sheet->setCellValue($help->toAlpha($i). $rows, $tgl_pending.'');$i++;
				//echo substr($absen->a,0,5);die;
				$rows++;
			}
		}
		
		
		$type = 'xlsx';
		$fileName = "Rekap Lembur ".$tgl_awal." - ".$tgl_akhir.".".$type;
		if($type == 'xlsx'){
			$writer = new Xlsx($spreadsheet);
		} else if($type == 'xls'){
			$writer = new Xls($spreadsheet);
		}
		$writer->save("export/".$fileName);
		header("Content-Type: application/vnd.ms-excel");
		return redirect(url('/')."/export/".$fileName);
	}
    
}

	
	

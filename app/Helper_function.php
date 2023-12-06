<?php

namespace App;

use Illuminate\Http\Request;
use DB;
use Auth;
use App\Generate_rekap_absen;
class Helper_function
{
    public static function element_gaji($periode_gajian)
    {
    	if($periode_gajian =='bulanan'){
    		
	    	$array = array(
	    		array("Gaji Pokok","gapok","tunjangan",17,1),
	    		array("Tunjangan Grade","tunjangan_grade","tunjangan",11,1),
	    		array("Tunjangan Entitas","tunjangan_entitas","tunjangan",19,1),
	    		array("Tunjangan BPJS Kesehatan","tunjangan_bpjskes","tunjangan",12,0),
	    		array("Tunjangan BPJS Ketenaga Kerjaan","tunjangan_bpjsket","tunjangan",13,0),
	    		array("Iuran BPJS Kesehatan","iuran_bpjskes","potongan",14,0),
	    		array("Iuran BPJS Ketenaga Kerjaan","iuran_bpjsket","potongan",15,0),
	    	);
    	}else{
    		$array = array(
    		array("Upah Harian","upah_harian","tunjangan",18,1),
    		
    	);
    	}
    	return $array;
        
    }
    public static function rekap_absen($tgl_awal, $tgl_akhir, $tgl_awal_lembur, $tgl_akhir_lembur,$type=-1,  $user = null, $id_karyawan_search = null, $get = null,$request=null)
    {
        //$request = new Request;
        
        if(!$tgl_akhir or $tgl_akhir=='1970-01-01'){
            $tgl_akhir = $tgl_awal;
        }
        if(isset( Auth::user()->id)){
        $iduser = Auth::user()->id;
        $sqluser = "SELECT p_recruitment.foto,role,p_karyawan_pekerjaan,p_karyawan.p_karyawan_id,p_karyawan_pekerjaan.m_lokasi_id,user_entitas_access FROM users
		left join m_role on m_role.m_role_id=users.role
		left join p_karyawan on p_karyawan.user_id=users.id
		left join p_karyawan_pekerjaan on p_karyawan_pekerjaan.p_karyawan_id=p_karyawan.p_karyawan_id
		left join p_recruitment on p_recruitment.p_recruitment_id=p_karyawan.p_recruitment_id
		where users.id=$iduser";
        $user = DB::connection()->select($sqluser);
        $id_karyawan = $user[0]->p_karyawan_id;
        }else{
            $id_karyawan=$id_karyawan_search;
        }
        if($id_karyawan){
       
		$jabstruk = Helper_function::jabatan_struktural($id_karyawan);
		$atasan = $jabstruk['atasan'];
		$bawahan = $jabstruk['bawahan'];
		$sejajar = $jabstruk['sejajar'];
        }else{
            $atasan = "";
            $bawahan = "";
            $sejajar = "";
        }
		$wherebawahan = '';
		$wherebawahan2 = '';
		$wherebawahan3 = '';
        
         $rekap[$id_karyawan]['string'] = array();
        if (!$get) {

            if ($type != -1) {

                $where = " d.periode_gajian = " . $type;
                $appendwhere = "and";
            } else {
                $where = "";
                $appendwhere = "";
            }
        } else {
            $where = "";
            $appendwhere = "";
            $wherebawahan = "and c.m_jabatan_id in ($bawahan)";
            $wherebawahan2 = "and a.m_jabatan_id in ($bawahan)";
            $wherebawahan3 = "and d.m_jabatan_id in ($bawahan)";
        }
        $where_karyawan = '';
        $where_karyawan_2 = '';
        if ($id_karyawan_search){
        	
            $where_karyawan =  ' and c.p_karyawan_id =' . $id_karyawan_search;
            $where_karyawan_2 = 'and p_karyawan_create_id ='.$id_karyawan_search;
        }
        //$where_karyawan='';
        
      
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
        $where_departement = '';
        if (isset($_GET['departemen'])) {
            if ($_GET['departemen'])
                $where_departement = ' and m_departemen.m_departemen_id=' . $_GET['departemen'];
        }
        //echo $tgl_akhir; die;
        $whereMesin="";
        if(isset($request->lokasi_absen)){
        if($request->lokasi_absen){
		  //  $whereMesin .= " AND a.mesin_id = (".$request->lokasi_absen.")";
		    $whereMesin .= " AND m_mesin_absen_seharusnya_id = (".$request->lokasi_absen.")";
		}
		}$where_periode_gajian ="";
         if ($type != -1) {

                $where_periode_gajian = "and d.periode_gajian = " . $type;
             
            }
        $help = new Helper_function();
       // echo $sqlabsen; 
      // echo '<pre>';
        $rekap = array();
        $rekap[$id_karyawan]['total']['ijin_libur']=0;
        $array_karyawan = array();
        $list_permit = array();
        $list_karyawan = array();
        $karyawan = array();
       	$rekap_absen = Generate_rekap_absen::rekap_absensi($tgl_awal,$tgl_akhir,$where_karyawan,$wherebawahan,$whereMesin);
       	$rekap['absen'] =$rekap_absen;
       	$rekap_izin = Generate_rekap_absen::rekap_izin($tgl_awal,$tgl_akhir,$where_karyawan,$wherebawahan2,$hari_libur,$hari_libur_shift,$karyawan);
       	$rekap['pengajuan'] =$rekap_izin['rekap'];
       	$karyawan = $rekap_izin['karyawan'];
       	$rekap_lembur = Generate_rekap_absen::rekap_lembur($tgl_awal_lembur,$tgl_akhir_lembur,$where_karyawan,$where_periode_gajian,$wherebawahan3,$karyawan);
       	$rekap['lembur'] =$rekap_lembur['rekap'];
       	$karyawan = $rekap_izin['karyawan'];
       	$rekap_klarifikasi = Generate_rekap_absen::rekap_klarifikasi($tgl_awal,$tgl_akhir,$where_karyawan_2);
       	$rekap['klarifikasi'] =$rekap_klarifikasi;
       	$rekap_perganitan = Generate_rekap_absen::rekap_pergantian_hari_libur($tgl_awal,$tgl_akhir);
       	$rekap['pergantian_hari_libur'] =$rekap_perganitan;
       
       	
       	$array_tgl = $rekap_lembur['array_tgl'];
        
        //print_r($rekap_izin	);
        $tgl_awal_lembur_ajuan = $tgl_awal_lembur;
        //print_r($array_tgl);
        if (count($array_tgl))
            $min = min($array_tgl) > $tgl_awal_lembur ? $tgl_awal_lembur : min($array_tgl);
        else
            $min = $tgl_awal_lembur;

        
        if (isset($_GET['entitas_absen_search'])) {

            
                $id_lokasi = $user[0]->user_entitas_access;
                $whereLokasi = "AND d.m_lokasi_id = $id_lokasi";
           
        }else if ($user) {

            if ($user[0]->user_entitas_access) {
                $id_lokasi = $user[0]->user_entitas_access;
                $whereLokasi = "AND d.m_lokasi_id = $id_lokasi";
            } else {
                $whereLokasi = "AND d.m_lokasi_id != 5";
                $whereLokasi = "";
            }
        }  else {
            $whereLokasi = "";
        }
        $where_filter_entitas = '';
        $where_filter_jabatan = '';
        if(isset($_GET['filterentitas'])){
        if(!empty($_GET['filterentitas'])){
        	$where_filter_entitas = " AND d.m_lokasi_id = ".$_GET['filterentitas'];
        }
        }
        if(isset($_GET['filterjabatan'])){
        if(!empty($_GET['filterjabatan'])){
        	$where_filter_jabatan = " AND d.m_jabatan_id = ".$_GET['filterjabatan'];
        }
        }
        
        if(isset(Auth::user()->user_entitas))
		$id_lokasi = Auth::user()->user_entitas;
		else{
			$id_lokasi=NULL;
		}
        if($id_lokasi and $id_lokasi!=-1) 
			$whereLokasi = "AND d.m_lokasi_id in($id_lokasi)";					
		else
			$whereLokasi = "";	
			
		if(isset($request->entitas_absen_search)){
		    
		    if($request->entitas_absen_search)
		    $whereLokasi .= " AND d.m_lokasi_id in(".$request->entitas_absen_search.")";
		}	
		if(isset($request->periode_gajian_search)){
		    
		    if($request->periode_gajian_search==2){
		       $request->periode_gajian_search=0;
		    }
		    $whereLokasi .= " AND d.periode_gajian in(".$request->periode_gajian_search.")";
		}	
		if(isset($request->entitas)){
		    if($request->entitas)
		    $whereLokasi .= " AND d.m_lokasi_id = (".$request->entitas.")";
		}
		$WhereKaryawanMesin='';
		if(isset($request->lokasi_absen)){
		   
		    //print_r($array_karyawan);
		    //echo (implode(',',array_unique($array_karyawan)));
		    if($request->lokasi_absen and count($array_karyawan))
		    $WhereKaryawanMesin = " and c.p_karyawan_id in(".str_replace(',,',',',implode(',',array_unique($array_karyawan))).")";
		}
		$whereentitas_periode = "";
		if(isset($request->list_entitas_periode)){
			if($request->list_entitas_periode){
			$whereentitas_periode = " and m_d.m_lokasi_id = (".$request->list_entitas_periode.")";
			}
        }
        $sql = "SELECT c.p_karyawan_id,c.nama,c.nik,m_lokasi.kode as nmlokasi,m_departemen.nama as departemen , f.m_pangkat_id ,m_jabatan.nama as nmjabatan,is_shift as is_karyawan_shift,foto,no_absen
		FROM p_karyawan c
		LEFT JOIN p_karyawan_pekerjaan d on d.p_karyawan_id=c.p_karyawan_id
		LEFT JOIN p_karyawan_absen i on i.p_karyawan_id=c.p_karyawan_id
		LEFT JOIN p_recruitment h on h.p_recruitment_id=c.p_recruitment_id
		
		LEFT JOIN m_departemen on m_departemen.m_departemen_id=d.m_departemen_id
		LEFT JOIN m_lokasi on m_lokasi.m_lokasi_id=d.m_lokasi_id
		LEFT JOIN m_jabatan on m_jabatan.m_jabatan_id=d.m_jabatan_id
		LEFT JOIN m_jabatan f on d.m_jabatan_id=f.m_jabatan_id
		LEFT JOIN p_karyawan_kontrak g on d.p_karyawan_id=g.p_karyawan_id and g.active=1
		WHERE $where $appendwhere  c.active = 1
		
		$whereLokasi
		$where_departement
		AND f.m_pangkat_id != 6
		$where_filter_entitas
		$where_filter_jabatan
        $whereentitas_periode
		----
		$where_karyawan
		$wherebawahan3
		$WhereKaryawanMesin
		order by c.nama,m_departemen.nama
		
		";;
        $list_karyawan = DB::connection()->select($sql);
        


        $rekap['rekap_json']     = json_encode($rekap);
        $rekap['list_karyawan']     = $list_karyawan;
        $rekap['hari_libur']         = $hari_libur;
        $rekap['hari_libur_except_pengecualian']         = $hari_libur_except_pengecualian;
        $rekap['hari_libur_except_pengkhususan']         = $hari_libur_except_pengkhususan;
        $rekap['hari_libur_shift']     = $hari_libur_shift;
        $rekap['tgl_awal']             = $tgl_awal;
        $rekap['tgl_akhir']         = $tgl_akhir;
        $rekap['tgl_awal_lembur']     = $tgl_awal_lembur_ajuan;
        $rekap['tgl_akhir_lembur']     = $tgl_akhir_lembur;
        $rekap['list_permit']         = $list_permit;
        $rekap['mesin']             = $mesin;
        $rekap['tanggallibur']         = $tanggallibur;

        return $rekap;
    }
    public static function bool_hari_libur($rekap,$date,$id_karyawan,$info_karyawan,$hari_libur,$hari_libur_shift,$hari_libur_except_pengecualian,$hari_libur_except_pengkhususan,$potong_gaji){
    	if (!$info_karyawan[0]->is_karyawan_shift){
            	
            	if(!isset($rekap['absen']['libur'])){
            		$rekap['absen']['libur'][] = "Sabtu";
            		$rekap['absen']['libur'][] = "Ahad";
            	}
                $bool_hari_libur = !(
                			in_array(Helper_function::nama_hari($date), $rekap['absen']['libur']) 
                			or in_array($date, $hari_libur) 
                			or (isset($hari_libur_shift[$date][$id_karyawan]))
                		) and !(in_array($date,$potong_gaji));
               //or !isset($rekap['perganitan_hari_libur_awal'][$id_karyawan][$date])
	          //      			or isset($rekap['perganitan_hari_libur_ke'][$id_karyawan][$date])
                if(isset($rekap['pergantian_hari_libur']['perganitan_hari_libur_awal'][$id_karyawan][$date]) ){
					$bool_hari_libur = false;
				}
                if(isset($rekap['pergantian_hari_libur']['perganitan_hari_libur_ke'][$id_karyawan][$date]) ){
                	$bool_hari_libur = false;
				}
                if(isset($hari_libur_except_pengecualian[$date]) ){
                	
                	if(in_array($info_karyawan[0]->m_lokasi_id,$hari_libur_except_pengecualian[$date])){
                		$bool_hari_libur=true;
                	}else{
                		$bool_hari_libur=false;
                		
                	}
                }
                if(isset($hari_libur_except_pengkhususan[$date]) ){
                	
                	if(in_array($info_karyawan[0]->m_lokasi_id,$hari_libur_except_pengkhususan[$date])){
                		$bool_hari_libur=false;
                	}else{
                		$bool_hari_libur=true;
                		
                	}
                }
            }
            else{
             
                $bool_hari_libur = !(isset($hari_libur_shift[$date][$id_karyawan]));
                
            }   
            return $bool_hari_libur;
    }
    public static
    function total_rekap_absen($rekap, $id_karyawan, $type = "rekap", $sheet = null, $rows = 0)
    {
        $help = new Helper_function();
        //$list_karyawan = $rekap['list_karyawan'] ;
        //echo print_r($id_karyawan); die;
        $hari_libur         = $rekap['hari_libur'];
        $hari_libur_except_pengecualian         = $rekap['hari_libur_except_pengecualian'];
        $hari_libur_except_pengkhususan         = $rekap['hari_libur_except_pengkhususan'];
        $hari_libur_shift     = $rekap['hari_libur_shift'];
        $mesin                 = $rekap['mesin'];
        $tanggallibur         = $rekap['tanggallibur'];
        $tgl_awal             = $rekap['tgl_awal'];
        $tgl_akhir             = $rekap['tgl_akhir'];
        $tgl_awal_lembur     = $rekap['tgl_awal_lembur'];
        $tgl_akhir_lembur     = $rekap['tgl_akhir_lembur'];
        $date = $tgl_awal;
        //$list_karyawan = $list_karyawan[0]; 
        $info_karyawan         = DB::connection()->select("select * ,p_karyawan.nama as p_karyawan_nama , is_shift as is_karyawan_shift,m_mesin_absen.nama as nama_mesin_absen,mk.m_mesin_absen_seharusnya_id
								from p_karyawan
								left join p_karyawan_absen on p_karyawan.p_karyawan_id = p_karyawan_absen.p_karyawan_id
								left join p_karyawan_pekerjaan pkp on p_karyawan.p_karyawan_id = pkp.p_karyawan_id
								left join m_office mk on pkp.m_kantor_id = mk.m_office_id 
								left join m_mesin_absen on mk.m_mesin_absen_seharusnya_id = m_mesin_absen.mesin_id 
								left join p_karyawan_kontrak on p_karyawan.p_karyawan_id = p_karyawan_kontrak.p_karyawan_id and p_karyawan_kontrak.active=1
								left join m_jabatan on m_jabatan.m_jabatan_id = pkp.m_jabatan_id
								where p_karyawan.p_karyawan_id = $id_karyawan");
								
		$id = $id_karyawan;
		$sql="select * from m_periode_absen where type= ".$info_karyawan[0]->periode_gajian." and  ((periode_aktif=1 and tgl_akhir >= '".date('Y-m-d')."')  or (periode_aktif=0 and tgl_akhir <= '".date('Y-m-d')."' )) order by tgl_akhir desc limit 1";
		$gapen=DB::connection()->select($sql);
		$ipg_cutber         = DB::connection()->select("select * from m_hari_libur_cuti_ipg where tanggal >='$tgl_awal' and tanggal<='$tgl_akhir' and p_karyawan_id = $id_karyawan and active=1");
		//print_r($ipg_cutber);
		if($gapen[0]->tgl_awal<$tgl_awal_lembur)
		    $tgl_awal_lembur = $gapen[0]->tgl_awal;
		$list_fix_ipg_cutber = array();
		foreach($ipg_cutber as $ipg_cutber){
		$list_fix_ipg_cutber[]=$ipg_cutber->tanggal;
			
		}
		$count_cutber         = DB::connection()->select("select count(*) from m_hari_libur where tanggal >='$tgl_awal' and tanggal<='$tgl_akhir' and is_cuti_bersama = 1");
			$potong_gaji = array();
		if($count_cutber[0]->count){
        	$sqlidkar="select * from p_karyawan 
		        left join p_karyawan_pekerjaan on p_karyawan.p_karyawan_id = p_karyawan_pekerjaan.p_karyawan_id
		        where p_karyawan.p_karyawan_id=$id";
	        $idkar=DB::connection()->select($sqlidkar);
			$cuti = Helper_function::query_cuti2($idkar);
			$date2 = $cuti['date'];
			$all = $cuti['all'];
			$tanggal_loop = $cuti['tanggal_loop'];
			
			$no=0;$nominal=0;
			$tahun = array();
			$tahunbesar = array();
			$datasisa = array();
			$hutang = 0;
			$jumlah = 0;
			$ipg = array();
		
			foreach($tanggal_loop as $i=> $loop){
				if($all[$i]['tanggal']<=date('Y-m-d')){
				$return = Helper_function::perhitungan_cuti2($all,$datasisa,$hutang,$date,$i,$nominal,$jumlah,$ipg,$potong_gaji);
				$datasisa =$return['datasisa'];
				$hutang =$return['hutang'];
				$nominal =$return['nominal'];
				$jumlah =$return['jumlah'];
				$ipg =$return['ipg'];
				$potong_gaji =$return['potong_gaji'];
				
				}
			}
		}

        $rekap['total'][$id_karyawan]['cuti'] = 0;

        $rekap['total'][$id_karyawan]['ipd'] = 0;
        $rekap['total'][$id_karyawan]['ihk'] = 0;
        $rekap['total'][$id_karyawan]['ihk'] = 0;
        $rekap['total'][$id_karyawan]['ipg'] = 0;
        $rekap['total'][$id_karyawan]['ipc'] = 0;
        $rekap['total'][$id_karyawan]['idt'] = 0;
        $rekap['total'][$id_karyawan]['ipm'] = 0;
        $rekap['total'][$id_karyawan]['sakit'] = 0;
        $rekap['total'][$id_karyawan]['alpha'] = 0;
        $rekap['total'][$id_karyawan]['pm'] = 0;
        $rekap['total'][$id_karyawan]['terlambat'] = 0;
        $rekap['total'][$id_karyawan]['fingerprint'] = 0;
        $rekap['total'][$id_karyawan]['absen_masuk'] = 0;
        $rekap['total'][$id_karyawan]['alphaList'] = '';
        $rekap['total'][$id_karyawan]['terlambatList'] = '';
        $rekap['total'][$id_karyawan]['IPGCuti'] = 0;
        $rekap['total'][$id_karyawan]['IPGCutiList'] = '';
        $rekap['total'][$id_karyawan]['masuk_libur'] = 0;
        $rekap['total'][$id_karyawan]['ijin_libur'] = 0;
        $rekap['total'][$id_karyawan]['hari_bulan'] = 0;
        $rekap['total'][$id_karyawan]['hari_kerja'] = 0;
        $rekap['total'][$id_karyawan]['mesin'] = 0;
        for($i=1;$i<=12;$i++){
        $rekap['total_tahunan'][$id_karyawan][date('Y')][sprintf('%02d',$i)]['ipg'] = 0;
        $rekap['total_tahunan'][$id_karyawan][date('Y')][sprintf('%02d',$i)]['ihk'] = 0;
        $rekap['total_tahunan'][$id_karyawan][date('Y')][sprintf('%02d',$i)]['cuti'] = 0;
        $rekap['total_tahunan'][$id_karyawan][date('Y')][sprintf('%02d',$i)]['ipd'] = 0;
        $rekap['total_tahunan'][$id_karyawan][date('Y')][sprintf('%02d',$i)]['ipc'] = 0;
        $rekap['total_tahunan'][$id_karyawan][date('Y')][sprintf('%02d',$i)]['idt'] = 0;
        $rekap['total_tahunan'][$id_karyawan][date('Y')][sprintf('%02d',$i)]['ipm'] = 0;
        $rekap['total_tahunan'][$id_karyawan][date('Y')][sprintf('%02d',$i)]['sakit'] = 0;
        $rekap['total_tahunan'][$id_karyawan][date('Y')][sprintf('%02d',$i)]['alpha'] = 0;
        $rekap['total_tahunan'][$id_karyawan][date('Y')][sprintf('%02d',$i)]['pm'] = 0;
        $rekap['total_tahunan'][$id_karyawan][date('Y')][sprintf('%02d',$i)]['terlambat'] = 0;
        $rekap['total_tahunan'][$id_karyawan][date('Y')][sprintf('%02d',$i)]['fingerprint'] = 0;
        $rekap['total_tahunan'][$id_karyawan][date('Y')][sprintf('%02d',$i)]['absen_masuk'] = 0;
        $rekap['total_tahunan'][$id_karyawan][date('Y')][sprintf('%02d',$i)]['alphaList'] = 0;
        $rekap['total_tahunan'][$id_karyawan][date('Y')][sprintf('%02d',$i)]['IPGCuti'] = 0;
        $rekap['total_tahunan'][$id_karyawan][date('Y')][sprintf('%02d',$i)]['IPGCutiList'] = 0;
        $rekap['total_tahunan'][$id_karyawan][date('Y')][sprintf('%02d',$i)]['masuk_libur'] = 0;
        $rekap['total_tahunan'][$id_karyawan][date('Y')][sprintf('%02d',$i)]['ijin_libur'] = 0;
        $rekap['total_tahunan'][$id_karyawan][date('Y')][sprintf('%02d',$i)]['hari_bulan'] = 0;
        $rekap['total_tahunan'][$id_karyawan][date('Y')][sprintf('%02d',$i)]['hari_kerja'] = 0;
        $rekap['total_tahunan'][$id_karyawan][date('Y')][sprintf('%02d',$i)]['mesin'] = 0;
		}
		$beruntun['alpha'] = 0; 
		$beruntun_literasi['alpha'] = 0; 
		$beruntun['terlambat'] = 0; 
		$beruntun_literasi['terlambat'] = 0; 

        $all_content = '';
        $all_content_cek_absen = '';
        if(!(isset($no))) $no = 0;
        $warna_sheet = array();
        
        /*
        $rekap['perganitan_hari_libur_awal'][$pengganti_hari->p_karyawan_id][]=$pengganti_hari->tgl_pengganti_hari;
			$rekap['perganitan_hari_libur_ke'][$pengganti_hari->p_karyawan_id][]=$pengganti_hari->tgl_pengajuan;
			*/
            if(isset($hari_libur_shift)){
               
                //print_r($hari_libur_shift);
            }
        for ($i = 0; $i <= Helper_function::hitunghari($tgl_awal, $tgl_akhir); $i++) {
            if(!isset($rekap['absen']['a'][$date][$id_karyawan]['jam_masuk'])){
                $rekap['absen']['a'][$date][$id_karyawan]['jam_masuk'] = "07:31:00";
                
                $rekap['absen']['a'][$date][$id_karyawan]['jam_form'] = "Entitas";
            }
            if(!isset($rekap['absen']['a'][$date][$id_karyawan]['jam_keluar'])){
                    $rekap['absen']['a'][$date][$id_karyawan]['jam_keluar'] = "16:30:00";
                    
            }
            $rekap['total'][$id_karyawan]['hari_bulan'] += 1;
            $bool_hari_libur =  Helper_function::bool_hari_libur($rekap,$date,$id_karyawan,$info_karyawan,$hari_libur,$hari_libur_shift,$hari_libur_except_pengecualian,$hari_libur_except_pengkhususan,$potong_gaji);
             

            if($id_karyawan==442){
              //  echo '<br>'.$info_karyawan[0]->is_shift.'=>'.$date.'=>'.$bool_hari_libur;
            }

			//
        //$hari_libur_except_pengecualian         = $rekap['hari_libur_except_pengecualian'];
        //$hari_libur_except_pengkhususan         = $rekap['hari_libur_except_pengkhususan'];

            $content = "<td style='background-color: STR;SRT2'>";
            $content_sheet[$date] = "";
            $warna = '';
            $status_absen = 'OK<br>';
            $status_libur = '';;
            
            
		if($count_cutber[0]->count){
            if(in_array($date,$potong_gaji)){
				$rekap['total'][$id_karyawan]['ipg'] += 1;
				
		        $rekap['total'][$id_karyawan]['IPGCuti'] += 1;
		        $rekap['total'][$id_karyawan]['IPGCutiList'] .= $date.'|';
		        
		        $rekap['total_tahunan'][$id_karyawan][date('Y',strtotime($date))][date('m',strtotime($date))]['ipg'] +=1;
		        $rekap['total_tahunan'][$id_karyawan][date('Y',strtotime($date))][date('m',strtotime($date))]['IPGCuti'] +=1;
		        $rekap['total_tahunan'][$id_karyawan][date('Y',strtotime($date))][date('m',strtotime($date))]['IPGCutiList'] .= $date.'|';
		        $warna = 'blue';
                $warna_sheet[$date] = '0000FF';
                
                 $content .= ' Potong Gaji Cuti Bersama								';
                $content_sheet[$date] .= ' Potong Gaji Cuti Bersama								';
                
                $string_jenis_ijin='IPGCuti';
                if(isset($rekap[$id_karyawan]['string'][$string_jenis_ijin])){
	                    		$rekap[$id_karyawan]['string'][$string_jenis_ijin] .= " ". $date.' | ';
	            }else if(!isset($rekap[$id_karyawan]['string'][$string_jenis_ijin])){
	                    		$rekap[$id_karyawan]['string'][$string_jenis_ijin] = $date.' | ';
				}	
						
                    
			}
			}
			if(in_array($date,$list_fix_ipg_cutber)){
				
                $warna = 'blue';
                $warna_sheet[$date] = '0000FF';
                
                 $content .= ' Potong Gaji Cuti Bersama(v)								';
                $content_sheet[$date] .= ' Potong Gaji Cuti Bersama(v)								';
                
                $string_jenis_ijin='IPGCuti';
                if(isset($rekap[$id_karyawan]['string'][$string_jenis_ijin])){
	                    		$rekap[$id_karyawan]['string'][$string_jenis_ijin] .= " ". $date.' | ';
	            }else if(!isset($rekap[$id_karyawan]['string'][$string_jenis_ijin])){
	                    		$rekap[$id_karyawan]['string'][$string_jenis_ijin] = $date.' | ';
				}
				
			}
			
            if (!isset($rekap['absen']['a'][$date][$id_karyawan]['masuk']) and isset($rekap['absen']['a'][$date][$id_karyawan]['keluar']) and $bool_hari_libur) {
                if (isset($rekap['pengajuan']['ci'][$date][$id_karyawan]['nama_ijin'])) {
                    if ($rekap['pengajuan']['ci'][$date][$id_karyawan]['m_jenis_ijin_id'] != 24) {
                        $warna = 'darkgray';
                        $warna_sheet[$date] = 'A9A9A9';
                        $rekap['total'][$id_karyawan]['fingerprint'] += 1;
                        $rekap['total_tahunan'][$id_karyawan][date('Y',strtotime($date))][date('m',strtotime($date))]['fingerprint'] +=1;
		        
                         $status_absen = 'TIDAK OK<br>';
                         $string_jenis_ijin='TANPA FINGERPRINT';
			                if(isset($rekap[$id_karyawan]['string'][$string_jenis_ijin])){
				                    		$rekap[$id_karyawan]['string'][$string_jenis_ijin] .= " ". $date.' | ';
				            }else if(!isset($rekap[$id_karyawan]['string'][$string_jenis_ijin])){
				                    		$rekap[$id_karyawan]['string'][$string_jenis_ijin] = $date.' | ';
							}
                    }
                } else {
                    $warna = 'darkgray';
                    $warna_sheet[$date] = 'A9A9A9';
                    $rekap['total'][$id_karyawan]['fingerprint'] += 1; 
                    $rekap['total_tahunan'][$id_karyawan][date('Y',strtotime($date))][date('m',strtotime($date))]['fingerprint'] +=1;
                    $status_absen = 'TIDAK OK<br>';
                     $string_jenis_ijin='TANPA FINGERPRINT';
			                if(isset($rekap[$id_karyawan]['string'][$string_jenis_ijin])){
				                    		$rekap[$id_karyawan]['string'][$string_jenis_ijin] .= " ". $date.' | ';
				            }else if(!isset($rekap[$id_karyawan]['string'][$string_jenis_ijin])){
				                    		$rekap[$id_karyawan]['string'][$string_jenis_ijin] = $date.' | ';
							}
                }
            } else if (isset($rekap['absen']['a'][$date][$id_karyawan]['masuk']) and isset($rekap['absen']['a'][$date][$id_karyawan]['keluar']) and $bool_hari_libur) {
                if ($rekap['absen']['a'][$date][$id_karyawan]['keluar'] < $rekap['absen']['a'][$date][$id_karyawan]['jam_keluar']) {

                    if (isset($rekap['pengajuan']['ci'][$date][$id_karyawan]['m_jenis_ijin_id'])) {
                        if ($rekap['pengajuan']['ci'][$date][$id_karyawan]['m_jenis_ijin_id'] != 26) {

                            $rekap['total'][$id_karyawan]['pm'] += 1; 
                            $rekap['total_tahunan'][$id_karyawan][date('Y',strtotime($date))][date('m',strtotime($date))]['pm'] +=1;
                            $warna = 'orange';
                            $warna_sheet[$date] = 'FFA500';
                        	$status_absen = 'PULANG MENDAHULUI<br>';
                        	$status_absen = 'TIDAK OK<br>';
                        	 $string_jenis_ijin='PULANG MENDAHULUI';
			                if(isset($rekap[$id_karyawan]['string'][$string_jenis_ijin])){
				                    		$rekap[$id_karyawan]['string'][$string_jenis_ijin] .= " ". $date.' | ';
				            }else if(!isset($rekap[$id_karyawan]['string'][$string_jenis_ijin])){
				                    		$rekap[$id_karyawan]['string'][$string_jenis_ijin] = $date.' | ';
							}
                        }
                    } else {
                        $rekap['total'][$id_karyawan]['pm'] += 1; 
                        $rekap['total_tahunan'][$id_karyawan][date('Y',strtotime($date))][date('m',strtotime($date))]['pm'] +=1;
                        $warna = 'orange';
                        $warna_sheet[$date] = 'FFA500';
                        $status_absen = 'PULANG MENDAHULUI<br>';
                         $status_absen = 'TIDAK OK<br>';
                         
                         $string_jenis_ijin='PULANG MENDAHULUI';
			                if(isset($rekap[$id_karyawan]['string'][$string_jenis_ijin])){
				                    		$rekap[$id_karyawan]['string'][$string_jenis_ijin] .= " ". $date.' | ';
				            }else if(!isset($rekap[$id_karyawan]['string'][$string_jenis_ijin])){
				                    		$rekap[$id_karyawan]['string'][$string_jenis_ijin] = $date.' | ';
							}
                    }
                }
            }


            
            if ($bool_hari_libur){
                $rekap['total'][$id_karyawan]['hari_kerja'] += 1;
				$rekap['total_tahunan'][$id_karyawan][date('Y',strtotime($date))][date('m',strtotime($date))]['hari_kerja'] +=1;
			
            }
            else
                $status_libur = 'Hari Libur';
                
                
			if (isset($rekap['absen']['a'][$date][$id_karyawan]['masuk'])) {
                $rekap['total'][$id_karyawan]['absen_masuk'] += 1;
				$rekap['total_tahunan'][$id_karyawan][date('Y',strtotime($date))][date('m',strtotime($date))]['absen_masuk'] +=1;
			}else if (!isset($rekap['absen']['a'][$date][$id_karyawan]['masuk']) and isset($rekap['absen']['a'][$date][$id_karyawan]['keluar'])) {
                $rekap['total'][$id_karyawan]['absen_masuk'] += 1;
				$rekap['total_tahunan'][$id_karyawan][date('Y',strtotime($date))][date('m',strtotime($date))]['absen_masuk'] +=1;
			}
            if (isset($rekap['absen']['a'][$date][$id_karyawan]['masuk'])) {
                $status_absen = 'OK<br>';
                $content_sheet[$date] .= $rekap['absen']['a'][$date][$id_karyawan]['masuk'];
                $content .= ' ' . $rekap['absen']['a'][$date][$id_karyawan]['masuk'];
                // $rekap[$id_karyawan]['total']['absen_masuk'] += 1;
               

                if ($rekap['absen']['a'][$date][$id_karyawan]['masuk'] > $rekap['absen']['a'][$date][$id_karyawan]['jam_masuk'] and  $bool_hari_libur) {
                   if (isset($rekap['pengajuan']['ci'][$date][$id_karyawan]['m_jenis_ijin_id'])) {
                        if ($rekap['pengajuan']['ci'][$date][$id_karyawan]['m_jenis_ijin_id'] == 21 or $rekap['pengajuan']['ci'][$date][$id_karyawan]['m_jenis_ijin_id'] == 24 ) {
                        } else {
                            $status_absen = 'TERLAMBAT<br>';
                            $rekap['absen']['a'][$date][$id_karyawan]['terlambat'] = 1;

                            $rekap['total'][$id_karyawan]['terlambat'] += 1;
	                        $rekap['total'][$id_karyawan]['terlambatList'] .= $date . ' | '; 
	                        $datebefore = $help->tambah_tanggal($date,-1);
	                        $bool_hari_libur_terlambat =  Helper_function::bool_hari_libur($rekap,$datebefore,$id_karyawan,$info_karyawan,$hari_libur,$hari_libur_shift,$hari_libur_except_pengecualian,$hari_libur_except_pengkhususan,$potong_gaji);
	                        if(!$bool_hari_libur_terlambat){
	                        	
								$loop = true;
								while($loop==true) {
									$datebefore = $help->tambah_tanggal($datebefore,-1);
									$bool_hari_libur_terlambat =  Helper_function::bool_hari_libur($rekap,$datebefore,$id_karyawan,$info_karyawan,$hari_libur,$hari_libur_shift,$hari_libur_except_pengecualian,$hari_libur_except_pengkhususan,$potong_gaji);
									if($bool_hari_libur_terlambat)
								    	$loop=false;
								 // echo "The number is: $x <br>";
								 
								} 
	                        }
	                        if(in_array($datebefore, explode(' | ', $rekap['total'][$id_karyawan]['terlambatList'])) and $tgl_akhir!=$date){
	                        	if($beruntun['terlambat']==0){
	                        		$beruntun_literasi['terlambat']+=1;
	                        	}
	                        	if(!isset($rekap['total_beruntun'][$id_karyawan]['terlambat']['tangggal'][$beruntun_literasi['terlambat']])){
                        			$total_beruntun['terlambat']['tangggal'][$beruntun_literasi['terlambat']][] = $datebefore;
	                        	}else
	                        	if(in_array($datebefore,$rekap['total_beruntun'][$id_karyawan]['terlambat']['tangggal'][$beruntun_literasi['terlambat']]))
	                        		$total_beruntun['terlambat']['tangggal'][$beruntun_literasi['terlambat']][] = $datebefore;
	                        	
	                        	if(!isset($rekap['total_beruntun'][$id_karyawan]['terlambat']['tangggal'][$beruntun_literasi['terlambat']])){
	                        		$total_beruntun['terlambat']['tangggal'][$beruntun_literasi['terlambat']][] = $date;
								}else
	                        	if(in_array($date,$rekap['total_beruntun'][$id_karyawan]['terlambat']['tangggal'][$beruntun_literasi['terlambat']]))
	                        		$total_beruntun['terlambat']['tangggal'][$beruntun_literasi['terlambat']][] = $date;
	                        	
	                        	$beruntun['terlambat']+=1;
	                        }else if($beruntun['terlambat']>0 or $tgl_akhir==$date){
                        	if(in_array($date, explode(' | ', $rekap['total'][$id_karyawan]['terlambatList'])) ){
								$total_beruntun['terlambat']['tangggal'][$beruntun_literasi['terlambat']][] = $date;
							}
	                        	$rekap['total_beruntun'][$id_karyawan]['terlambat']['tangggal'][$beruntun_literasi['terlambat']][] = $total_beruntun['terlambat']['tangggal'][$beruntun_literasi['terlambat']];
	                        	$rekap['total_beruntun'][$id_karyawan]['terlambat']['total'][$beruntun_literasi['terlambat']] = $beruntun['terlambat'];
	                        	$beruntun['terlambat']=0;
	                        }
							$rekap['total_tahunan'][$id_karyawan][date('Y',strtotime($date))][date('m',strtotime($date))]['terlambat'] +=1;
                            $warna = 'orange';
                            $warna_sheet[$date] = 'FFA500';
                            
                         $string_jenis_ijin='TERLAMBAT';
			                if(isset($rekap[$id_karyawan]['string'][$string_jenis_ijin])){
				                    		$rekap[$id_karyawan]['string'][$string_jenis_ijin] .= " ". $date.' | ';
				            }else if(!isset($rekap[$id_karyawan]['string'][$string_jenis_ijin])){
				                    		$rekap[$id_karyawan]['string'][$string_jenis_ijin] = $date.' | ';
							}
                        }
                    } else {
                        $status_absen = 'TERLAMBAT<br>';
                       // $rekap['absen']['a'][$date][$id_karyawan]['terlambat'] = 1;
						$rekap['total_tahunan'][$id_karyawan][date('Y',strtotime($date))][date('m',strtotime($date))]['terlambat'] +=1;

                        $rekap['total'][$id_karyawan]['terlambat'] += 1;
                        $rekap['total'][$id_karyawan]['terlambatList'] .= $date . ' | '; 
                       // print_r($beruntun['terlambat']);
                       $datebefore = $help->tambah_tanggal($date,-1);
                        $bool_hari_libur_terlambat =  Helper_function::bool_hari_libur($rekap,$datebefore,$id_karyawan,$info_karyawan,$hari_libur,$hari_libur_shift,$hari_libur_except_pengecualian,$hari_libur_except_pengkhususan,$potong_gaji);
                        if(!$bool_hari_libur_terlambat){
                        	
							$loop = true;
							while($loop==true) {
								$datebefore = $help->tambah_tanggal($datebefore,-1);
								$bool_hari_libur_terlambat =  Helper_function::bool_hari_libur($rekap,$datebefore,$id_karyawan,$info_karyawan,$hari_libur,$hari_libur_shift,$hari_libur_except_pengecualian,$hari_libur_except_pengkhususan,$potong_gaji);
								if($bool_hari_libur_terlambat)
							    	$loop=false;
							 // echo "The number is: $x <br>";
							 
							} 
                        }
                        if(in_array($datebefore, explode(' | ', $rekap['total'][$id_karyawan]['terlambatList'])) and $tgl_akhir!=$date){
                        	if($beruntun['terlambat']==0){
                        		$beruntun_literasi['terlambat']+=1;
                        	}
                        	if(!isset($rekap['total_beruntun'][$id_karyawan]['terlambat']['tangggal'][$beruntun_literasi['terlambat']])){
                        		$total_beruntun['terlambat']['tangggal'][$beruntun_literasi['terlambat']][] = $datebefore;
                        	}else
                        	if(in_array($datebefore,$rekap['total_beruntun'][$id_karyawan]['terlambat']['tangggal'][$beruntun_literasi['terlambat']]))
                        	$total_beruntun['terlambat']['tangggal'][$beruntun_literasi['terlambat']][] = $datebefore;
                        	
                        	if(!isset($rekap['total_beruntun'][$id_karyawan]['terlambat']['tangggal'][$beruntun_literasi['terlambat']])){
                        	$total_beruntun['terlambat']['tangggal'][$beruntun_literasi['terlambat']][] = $date;
							}else
                        	if(in_array($date,$rekap['total_beruntun'][$id_karyawan]['terlambat']['tangggal'][$beruntun_literasi['terlambat']]))
                        	$total_beruntun['terlambat']['tangggal'][$beruntun_literasi['terlambat']][] = $date;
                        	
                        	$beruntun['terlambat']+=1;
                        	//echo 'hallow';
                        }else if($beruntun['terlambat']>0 or $tgl_akhir==$date){
                        	if(in_array($date, explode(' | ', $rekap['total'][$id_karyawan]['terlambatList'])) ){
								$total_beruntun['terlambat']['tangggal'][$beruntun_literasi['terlambat']][] = $date;
							}
                        	//print_r($total_beruntun);
                        	$rekap['total_beruntun'][$id_karyawan]['terlambat']['tangggal'][$beruntun_literasi['terlambat']][] = 
                        			array_unique($total_beruntun['terlambat']['tangggal'][$beruntun_literasi['terlambat']]);
                        	$rekap['total_beruntun'][$id_karyawan]['terlambat']['total'][$beruntun_literasi['terlambat']] = $beruntun['terlambat'];
                        	$beruntun['terlambat']=0;
                        }
                        $warna = 'orange';
                        $warna_sheet[$date] = 'FFA500';
                        
                         $string_jenis_ijin='TERLAMBAT';
			                if(isset($rekap[$id_karyawan]['string'][$string_jenis_ijin])){
				                    		$rekap[$id_karyawan]['string'][$string_jenis_ijin] .= " ". $date.' | ';
				            }else if(!isset($rekap[$id_karyawan]['string'][$string_jenis_ijin])){
				                    		$rekap[$id_karyawan]['string'][$string_jenis_ijin] = $date.' | ';
							}
                    }
                }
                if (!$bool_hari_libur){
                    $rekap['total'][$id_karyawan]['masuk_libur'] += 1;
					$rekap['total_tahunan'][$id_karyawan][date('Y',strtotime($date))][date('m',strtotime($date))]['masuk_libur'] +=1;
				}
            }
			
            
            if (isset($rekap['absen']['a'][$date][$id_karyawan]['keluar'])) {

                $content .= '
				s/d  ' . $rekap['absen']['a'][$date][$id_karyawan]['keluar'] . '
				';
                $content_sheet[$date] .= '
				s/d  ' . $rekap['absen']['a'][$date][$id_karyawan]['keluar'] . '
				';
            }
            if (isset($rekap['pengajuan']['ci'][$date][$id_karyawan]['nama_ijin'])) {
                 $status_absen = 'OK<br>';
				 $status_libur  =  $rekap['pengajuan']['ci'][$date][$id_karyawan]['nama_ijin'];
                if ($bool_hari_libur) {
                    if (in_array($rekap['pengajuan']['ci'][$date][$id_karyawan]['m_jenis_ijin_id'], array(4, 7, 12, 13, 14, 15, 16, 17))) {
                        $rekap['total'][$id_karyawan]['ihk'] += 1;
                        $rekap['total_tahunan'][$id_karyawan][date('Y',strtotime($date))][date('m',strtotime($date))]['ihk'] +=1;
                        $warna = '#fb0b7b';
                        $warna_sheet[$date] = 'fb0b7b';
                        
                        
                         $string_jenis_ijin='IHK';
			                if(isset($rekap[$id_karyawan]['string'][$string_jenis_ijin])){
				                    		$rekap[$id_karyawan]['string'][$string_jenis_ijin] .= " ". $date.' | ';
				            }else if(!isset($rekap[$id_karyawan]['string'][$string_jenis_ijin])){
				                    		$rekap[$id_karyawan]['string'][$string_jenis_ijin] = $date.' | ';
							}
                    } else if ($rekap['pengajuan']['ci'][$date][$id_karyawan]['m_jenis_ijin_id'] == 1) {
                        $rekap['total'][$id_karyawan]['ipg'] += 1;
                        $rekap['total_tahunan'][$id_karyawan][date('Y',strtotime($date))][date('m',strtotime($date))]['ipg'] +=1;
                        $warna = 'blue';
                        $warna_sheet[$date] = '0000FF';
                        
                        $string_jenis_ijin='IPG';
			                if(isset($rekap[$id_karyawan]['string'][$string_jenis_ijin])){
				                    		$rekap[$id_karyawan]['string'][$string_jenis_ijin] .= " ". $date.' | ';
				            }else if(!isset($rekap[$id_karyawan]['string'][$string_jenis_ijin])){
				                    		$rekap[$id_karyawan]['string'][$string_jenis_ijin] = $date.' | ';
							}
                    } else if ($rekap['pengajuan']['ci'][$date][$id_karyawan]['m_jenis_ijin_id'] == 20) {
                        $rekap['total'][$id_karyawan]['sakit'] += 1;
                        $rekap['total_tahunan'][$id_karyawan][date('Y',strtotime($date))][date('m',strtotime($date))]['sakit'] +=1;
                        $warna = 'darkcyan';
                        $warna_sheet[$date] = '008B8B';
                        $string_jenis_ijin='SAKIT';
			                if(isset($rekap[$id_karyawan]['string'][$string_jenis_ijin])){
				                    		$rekap[$id_karyawan]['string'][$string_jenis_ijin] .= " ". $date.' | ';
				            }else if(!isset($rekap[$id_karyawan]['string'][$string_jenis_ijin])){
				                    		$rekap[$id_karyawan]['string'][$string_jenis_ijin] = $date.' | ';
							}
                    } else if ($rekap['pengajuan']['ci'][$date][$id_karyawan]['m_jenis_ijin_id'] == 25) {
                        $rekap['total'][$id_karyawan]['ipc'] += 1;
                        $rekap['total_tahunan'][$id_karyawan][date('Y',strtotime($date))][date('m',strtotime($date))]['ipc'] +=1;
                        $warna = 'teal';
                        $warna_sheet[$date] = '008080';
                        $string_jenis_ijin='IPC';
			                if(isset($rekap[$id_karyawan]['string'][$string_jenis_ijin])){
				                    		$rekap[$id_karyawan]['string'][$string_jenis_ijin] .= " ". $date.' | ';
				            }else if(!isset($rekap[$id_karyawan]['string'][$string_jenis_ijin])){
				                    		$rekap[$id_karyawan]['string'][$string_jenis_ijin] = $date.' | ';
							}
                    } else if ($rekap['pengajuan']['ci'][$date][$id_karyawan]['tipe'] == 3) {
                        $rekap['total'][$id_karyawan]['cuti'] += 1;
                        $rekap['total_tahunan'][$id_karyawan][date('Y',strtotime($date))][date('m',strtotime($date))]['cuti'] +=1;
                        $warna = 'green';
                        $warna_sheet[$date] = '008000';
                        $string_jenis_ijin='CUTI';
			                if(isset($rekap[$id_karyawan]['string'][$string_jenis_ijin])){
				                    		$rekap[$id_karyawan]['string'][$string_jenis_ijin] .= " ". $date.' | ';
				            }else if(!isset($rekap[$id_karyawan]['string'][$string_jenis_ijin])){
				                    		$rekap[$id_karyawan]['string'][$string_jenis_ijin] = $date.' | ';
							}
                    } else if ($rekap['pengajuan']['ci'][$date][$id_karyawan]['m_jenis_ijin_id'] == 21) {
                        $warna = 'brown';
                        $warna_sheet[$date] = 'D2691E';
                        $rekap['total'][$id_karyawan]['idt'] += 1; 
                        $rekap['total_tahunan'][$id_karyawan][date('Y',strtotime($date))][date('m',strtotime($date))]['idt'] +=1;
                        $string_jenis_ijin='IDT';
			                if(isset($rekap[$id_karyawan]['string'][$string_jenis_ijin])){
				                    		$rekap[$id_karyawan]['string'][$string_jenis_ijin] .= " ". $date.' | ';
				            }else if(!isset($rekap[$id_karyawan]['string'][$string_jenis_ijin])){
				                    		$rekap[$id_karyawan]['string'][$string_jenis_ijin] = $date.' | ';
							}
                    } else if ($rekap['pengajuan']['ci'][$date][$id_karyawan]['m_jenis_ijin_id'] == 26) {
                        $warna = 'chocolate';
                        $warna_sheet[$date] = 'D2691E';
                        $rekap['total'][$id_karyawan]['ipm'] += 1;
                        $rekap['total_tahunan'][$id_karyawan][date('Y',strtotime($date))][date('m',strtotime($date))]['ipm'] +=1;
                        $string_jenis_ijin='IPM';
			                if(isset($rekap[$id_karyawan]['string'][$string_jenis_ijin])){
				                    		$rekap[$id_karyawan]['string'][$string_jenis_ijin] .= " ". $date.' | ';
				            }else if(!isset($rekap[$id_karyawan]['string'][$string_jenis_ijin])){
				                    		$rekap[$id_karyawan]['string'][$string_jenis_ijin] = $date.' | ';
							}
                    }

                    //if($rekap['pengajuan']['ci'][$date][$id_karyawan]['nama_ijin'] != 'IZIN DATANG TERLAMBAT')

                    $content .= ' ' . $rekap['pengajuan']['ci'][$date][$id_karyawan]['nama_ijin'] . '								';
                    $content_sheet[$date] .= ' ' . $rekap['pengajuan']['ci'][$date][$id_karyawan]['nama_ijin'] . '								';
                    //if($rekap['pengajuan']['ci'][$date][$id_karyawan]['nama_ijin'] == 'IZIN PERJALANAN DINAS'){

                    //}
                }
                //if(!$bool_hari_libur)
                //	$rekap['total']['ijin_libur'] += 1;

                if ($rekap['pengajuan']['ci'][$date][$id_karyawan]['m_jenis_ijin_id'] == 24) {
                    $rekap['total'][$id_karyawan]['ipd'] += 1;
                    $warna = 'purple';
                    $warna_sheet[$date] = '800080';
                        $string_jenis_ijin='IPD';
			                if(isset($rekap[$id_karyawan]['string'][$string_jenis_ijin])){
				                    		$rekap[$id_karyawan]['string'][$string_jenis_ijin] .= " ". $date.' | ';
				            }else if(!isset($rekap[$id_karyawan]['string'][$string_jenis_ijin])){
				                    		$rekap[$id_karyawan]['string'][$string_jenis_ijin] = $date.' | ';
							}
                }
            }
              

		//	echo '<pre>';print_r($rekap);die;
            if ($content == "<td style='background-color: STR;SRT2'>") {

                if ($bool_hari_libur  and !in_array($date, explode(' | ', $rekap['total'][$id_karyawan]['alphaList'])) and !isset($rekap['absen']['a'][$date][$id_karyawan]['masuk'])) {
                    if ($info_karyawan[0]->tgl_bergabung > $date) {
                    } else {

                        $rekap['total_tahunan'][$id_karyawan][date('Y',strtotime($date))][date('m',strtotime($date))]['alpha'] +=1;
                        $rekap['total_tahunan'][$id_karyawan][date('Y',strtotime($date))][date('m',strtotime($date))]['alphaList'] +=1;
                        $rekap['total'][$id_karyawan]['alpha'] += 1;
                        $rekap['total'][$id_karyawan]['alphaList'] .= $date . ' | '; 
                        
                        $datebefore = $help->tambah_tanggal($date,-1);
                        $bool_hari_libur_alpha =  Helper_function::bool_hari_libur($rekap,$datebefore,$id_karyawan,$info_karyawan,$hari_libur,$hari_libur_shift,$hari_libur_except_pengecualian,$hari_libur_except_pengkhususan,$potong_gaji);
                        if(!$bool_hari_libur_alpha){
                        	
							$loop = true;
							while($loop==true) {
								$datebefore = $help->tambah_tanggal($datebefore,-1);
								$bool_hari_libur_alpha =  Helper_function::bool_hari_libur($rekap,$datebefore,$id_karyawan,$info_karyawan,$hari_libur,$hari_libur_shift,$hari_libur_except_pengecualian,$hari_libur_except_pengkhususan,$potong_gaji);
								if($bool_hari_libur_alpha)
							    	$loop=false;
							 // echo "The number is: $x <br>";
							 
							} 
                        }
                        //echo $datebefore.'<br>';
                        if(in_array($datebefore, explode(' | ', $rekap['total'][$id_karyawan]['alphaList'])) and $tgl_akhir!=$date){
                        	
                        	if($beruntun['alpha']==0){
								$beruntun_literasi['alpha']+=1;
                        	}
                        	if(!isset($rekap['total_beruntun'][$id_karyawan]['alpha']['tangggal'][$beruntun_literasi['alpha']])){
                        	$total_beruntun['alpha']['tangggal'][$beruntun_literasi['alpha']][] = $datebefore;                       	
    	                    	
                        	}else
                        	if(in_array($datebefore,$rekap['total_beruntun'][$id_karyawan]['alpha']['tangggal'][$beruntun_literasi['alpha']])){
        	                	$total_beruntun['alpha']['tangggal'][$beruntun_literasi['alpha']][] = $datebefore;
    	                    	
							}
							
                        	if(!isset($rekap['total_beruntun'][$id_karyawan]['alpha']['tangggal'][$beruntun_literasi['alpha']])){
                        	$total_beruntun['alpha']['tangggal'][$beruntun_literasi['alpha']][] = $date;
                        	
							}else if(in_array($date,$rekap['total_beruntun'][$id_karyawan]['alpha']['tangggal'][$beruntun_literasi['alpha']])){
								
	                        	$total_beruntun['alpha']['tangggal'][$beruntun_literasi['alpha']][] = $date;
    	                    	
							}
                        	$beruntun['alpha']+=1;
                        	
                        }else if($beruntun['alpha']>0 or $tgl_akhir==$date){
                        	if(in_array($date, explode(' | ', $rekap['total'][$id_karyawan]['alphaList'])) ){
								$total_beruntun['alpha']['tangggal'][$beruntun_literasi['alpha']][] = $date;
							}
                        
                        	$rekap['total_beruntun'][$id_karyawan]['alpha']['tangggal'][$beruntun_literasi['alpha']][] = array_unique($total_beruntun['alpha']['tangggal'][$beruntun_literasi['alpha']]);
                        	$rekap['total_beruntun'][$id_karyawan]['alpha']['total'][$beruntun_literasi['alpha']] = count(array_unique($total_beruntun['alpha']['tangggal'][$beruntun_literasi['alpha']]));
                        	$beruntun['alpha']=0;
                        }
                    }
                }


                if (!$bool_hari_libur) {

                    $warna = 'red';
                    $content .= '</td>';
                    $warna_sheet[$date] = 'FF0000';
                } else {
                    if ($info_karyawan[0]->tgl_bergabung > $date) {

                        $warna = 'red';
                        $content .= '</td>';
                        $content_sheet[$date] .= '';
                        $warna_sheet[$date] = 'FF0000';
                    } else {
                        
                         if ((isset($rekap['klarifikasi']['list'][$date][$id_karyawan]['deskripsi']))){
                        	if($rekap['klarifikasi']['list'][$date][$id_karyawan]['selesai']==1){
                        	     $status_absen = 'KLARIFIKASI<br>';
                            $status_libur  = 'Sudah Terdapat Chat Klarifikasi, klarifikasi dikembalikan ke atasan, silahkan konfirmasi ke atasan untuk melakukan approval<br>';
                        	}else if($rekap['klarifikasi']['list'][$date][$id_karyawan]['selesai']==6){
                        	     $status_absen = 'KLARIFIKASI<br>';
                                 $status_libur  = 'Sudah Terdapat Chat Klarifikasi, klarifikasi dikembalikan ke karyawan, silahkan konfirmasi di menu pesan(dengan tanggal terkait)<br>';
                        	}else if($rekap['klarifikasi']['list'][$date][$id_karyawan]['selesai']==5){
                        	    $status_absen = 'KLARIFIKASI<br>';
                                $status_libur  = 'Sudah Terdapat Chat Klarifikasi, Klarifikasi tidak bisa ditindak lanjuti<br>';
                                 if($rekap['klarifikasi']['list'][$date][$id_karyawan]['keterangan_hr']){
                                    $status_libur  .= 'Keterangan HR: '.$rekap['klarifikasi']['list'][$date][$id_karyawan]['keterangan_hr'];
                                 }
                                 if($rekap['klarifikasi']['list'][$date][$id_karyawan]['keterangan_atasan']){
                                    $status_libur  .= 'Keterangan Atasan: '.$rekap['klarifikasi']['list'][$date][$id_karyawan]['keterangan_atasan'];
                                 }
                        	}else if($rekap['klarifikasi']['list'][$date][$id_karyawan]['selesai']==0){
                            $status_absen = 'KLARIFIKASI<br>';
                            $status_libur  = 'Sudah Terdapat Chat Klarifikasi, hubungi HC untuk lebih lanjut<br>';
                        	}else if($rekap['klarifikasi']['list'][$date][$id_karyawan]['selesai']==3){
                            $status_absen = 'KLARIFIKASI<br>';
                            $status_libur  = 'Sudah Terdapat Chat Klarifikasi, klarifikasi selesai<br>';
                        	}
                        }
                         else if (isset($rekap['pengajuan']['pending_pengajuan'][$date][$id_karyawan]['status'])) {
                            if ($rekap['pengajuan']['pending_pengajuan'][$date][$id_karyawan]['status'] == 1) {
                                $status = 'Disetujui';
                            } else if ($rekap['pengajuan']['pending_pengajuan'][$date][$id_karyawan]['status'] == 2) {
                                $status = 'Ditolak';
                            } else if ($rekap['pengajuan']['pending_pengajuan'][$date][$id_karyawan]['status'] == 3) {
                                $status = 'Pending';
                            }
                            $status_absen = 'PENGAJUAN<br>';
                            $status_libur = 'Terdapat Pengajuan ' . $rekap['pengajuan']['pending_pengajuan'][$date][$id_karyawan]['nama_izin']. ' dengan Status ' . $status . '<br>';
                        }
                        
                         //if (!(isset($rekap['klarifikasi']['list'][$date][$id_karyawan]['deskripsi']) or isset($rekap['pending_pengajuan'][$id_karyawan][$date]['status'])))
                       
                        $warna = 'yellow';
                        $content .= '00:00:00</td>';
                        $content_sheet[$date] .= '00:00:00';
                        $warna_sheet[$date] = 'FFFF00';
                    }
                }
            } else {
                if (isset($rekap['absen']['a'][$date][$id_karyawan]['masuk']))
                    $content .= '<br><br>Masuk : ' . (($rekap['absen']['a'][$date][$id_karyawan]['jam_masuk'])) . '<br>';
                if (isset($rekap['absen']['a'][$date][$id_karyawan]['keluar']))
                    $content .=        'Keluar : ' . (($rekap['absen']['a'][$date][$id_karyawan]['jam_keluar'])) . '<br>';
                
                if (isset($rekap['absen']['a'][$date][$id_karyawan]['masuk']) or isset($rekap['absen']['a'][$date][$id_karyawan]['keluar']))
                    $content .=        'Jam : ' . (strtoupper($rekap['absen']['a'][$date][$id_karyawan]['jam_form'])) . '<br>';
                $content .= '</td>';
            }
            if(isset($rekap['perganitan_hari_libur_ke'][$id_karyawan][$date])){
                            // $status_absen = 'PERGANTIAN HARI LIBUR<br>';
                          
                            $status_libur .= '<br><br>PERGANTIAN HARI LIBUR Dari  ' . $rekap['perganitan_hari_libur_ke'][$id_karyawan]['awal_libur'][$date]. '<br>';
                            	//$rekap['perganitan_hari_libur_awal'][$pengganti_hari->p_karyawan_id][$pengganti_hari->tgl_pengganti_hari]=true;
                    			//$rekap['perganitan_hari_libur_awal'][$pengganti_hari->p_karyawan_id]['pengganti'][$pengganti_hari->tgl_pengganti_hari]=$pengganti_hari->tgl_pengajuan;
                    			//$rekap['perganitan_hari_libur_ke'][$pengganti_hari->p_karyawan_id][$pengganti_hari->tgl_pengajuan]=true;
                    			//$rekap['perganitan_hari_libur_ke'][$pengganti_hari->p_karyawan_id]['awal_libur'][$pengganti_hari->tgl_pengajuan]=$pengganti_hari->tgl_pengganti_hari;
                        }
            $content = str_ireplace('STR', $warna, $content);
            if ($warna != '' and $warna != 'yellow') {
                $content = str_ireplace('SRT2', 'color: white;', $content);
            }

            $no++;
            $status = '';

            $action = '';
            $lokasi_mesin     = isset($rekap['absen']['a'][$date][$id_karyawan]['mesin_id']) ?
                            $mesin[$rekap['absen']['a'][$date][$id_karyawan]['mesin_id']] : '';
            $masuk_aben     = isset($rekap['absen']['a'][$date][$id_karyawan]['masuk']) ? 
            	' <strong style="font-weight:800; font-size:20px">'.$rekap['absen']['a'][$date][$id_karyawan]['masuk'].' </strong><br><br> <strong style="font-weight:800">Masuk(Kantor)</strong>: '.date('H:i', strtotime($rekap['absen']['a'][$date][$id_karyawan]['jam_masuk'] . ' -1 minutes'))
             : '<strong style="font-weight:800; font-size:20px">00:00:00 </strong><br><br>';
             $keluar_absen     = isset($rekap['absen']['a'][$date][$id_karyawan]['keluar']) ? 
            ' <strong style="font-weight:800; font-size:20px">'.$rekap['absen']['a'][$date][$id_karyawan]['keluar'].' </strong><br><br> <strong style="font-weight:800">Keluar(Kantor)</strong>: '.
            (isset($rekap['absen']['a'][$date][$id_karyawan]['jam_keluar'])?$rekap['absen']['a'][$date][$id_karyawan]['jam_keluar']:'00:00:00') : '<strong style="font-weight:800; font-size:20px">00:00:00 </strong><br><br>';
            //$keluar_absen='';
            if(isset($rekap['absen']['a'][$date][$id_karyawan]['mesin_id'])){
                if($rekap['absen']['a'][$date][$id_karyawan]['mesin_id']!=$info_karyawan[0]->m_mesin_absen_seharusnya_id and $rekap['absen']['a'][$date][$id_karyawan]['appr_status']!=1){
                    $masuk_aben ="<b>00:00:00<br><br>Absen Mesin Tidak Sesuai</b>";
                    $status_absen = 'ABSEN TIDAK DI LOKASI SEHARUSNYA<br>';
                    $status_libur = "Menunggu approval Atasan";
                    
                    $rekap['total'][$id_karyawan]['mesin'] += 1; 
                    $rekap['total_tahunan'][$id_karyawan][date('Y',strtotime($date))][date('m',strtotime($date))]['mesin'] +=1;
                }
                }
                if(empty($masuk_aben) and $status_absen=='OK<br>' and !$status_libur){
                    $status_absen = 'TIDAK OK<br>';
                }
            
                if ($status_absen == 'TIDAK OK<br>' and $date>=$gapen[0]->tgl_awal and (Auth::user()->role==2 or Auth::user()->role==-1) ) {
                $action = '<a class="btn btn-primary" href="' . route('fe.tambah_chat', ['', 'key=Klarifikasi Absen Tanggal ' . $date]) . '">Klarifikasi</a>';
            }
            if(isset(Auth::user()->role)){
            if(Auth::user()->role==3 or Auth::user()->role==-1 or Auth::user()->role==5  ) {
            	
            if(isset($rekap['absen']['a'][$date][$id_karyawan]['absen_log_id_masuk'])){ 
            	$action .='
								<strong>MASUK</strong><br>								
                               <a href="'. route('be.edit_cari_absen_hr',$rekap['absen']['a'][$date][$id_karyawan]['absen_log_id_masuk']) .'" title="Ubah" data-toggle="tooltip"><span class="fa fa-edit"></span></a>
                                    <a href="'. route('be.hapus_cari_absen_hr',$rekap['absen']['a'][$date][$id_karyawan]['absen_log_id_masuk']) .'" title="Hapus" data-toggle="tooltip"><span class="fa fa-trash"></span></a>';
                    }
          if(isset($rekap['absen']['a'][$date][$id_karyawan]['absen_log_id_keluar'])){
				$action .='					
								<br><strong>KELUAR</strong>	<br>							
                                <a href="'. route('be.edit_cari_absen_hr',$rekap['absen']['a'][$date][$id_karyawan]['absen_log_id_keluar']) .'" title="Ubah" data-toggle="tooltip"><span class="fa fa-edit"></span></a>
                                    <a href="'. route('be.hapus_cari_absen_hr',$rekap['absen']['a'][$date][$id_karyawan]['absen_log_id_keluar']) .'" title="Hapus" data-toggle="tooltip"><span class="fa fa-trash"></span></a>';
                                }
                            } 
                             }
                            /**/
             $all_content_cek_absen .= "
			<tr ".($status_libur=='Hari Libur'?'style="color:red"':"").">
		";
			if($type=="AllKaryawan"){
			    $all_content_cek_absen .= "
			<td>" . $info_karyawan[0]->p_karyawan_nama . "</td>";
			}
			 
			 
			  $all_content_cek_absen .= "
			<td>" . $date . "</td>
			<td>" . $masuk_aben . "</td>
			<td>" . $keluar_absen . "</td>
			<td><div style='font-weight:700'>$status_absen</div><br>$status_libur</td>
			
			<td>" . $info_karyawan[0]->nama_mesin_absen . "</td>
			
						<td>" . $lokasi_mesin . "</td>
			<td>" . $action . "</td>

			</tr>";

            $all_content     .= $content;


            $date             = Helper_function::tambah_tanggal($date, 1);
        }
        if(isset($rekap['total_beruntun'][$id_karyawan]['terlambat'])){
            $array = $rekap['total_beruntun'][$id_karyawan]['terlambat']['total'];
            $maxValue = max($array);
            $maxIndex = array_search(max($array), $array);
            }else{
                $maxIndex=0;
                $maxValue=0;
            }
            $rekap['total_beruntun'][$id_karyawan]['terlambat']['maxValue'] =$maxValue ; 
            $rekap['total_beruntun'][$id_karyawan]['terlambat']['maxIndex'] =$maxIndex ; 
    
            if(isset($rekap['total_beruntun'][$id_karyawan]['alpha'])){
            $array = $rekap['total_beruntun'][$id_karyawan]['alpha']['total'];
            $maxValue = max($array);
            $maxIndex = array_search(max($array), $array);
            }else{
                $maxIndex=0;
                $maxValue=0;
            }
            $rekap['total_beruntun'][$id_karyawan]['alpha']['maxValue'] =$maxValue ; 
            $rekap['total_beruntun'][$id_karyawan]['alpha']['maxIndex'] =$maxIndex ; 
    

        $total_all = 0;
        $total['<8 jam'] = 0;
        $total['8 jam'] = 0;
        $total['9 jam'] = 0;
        $total['>=10 jam'] = 0;
        $total['1jam'] = 0;
        $total['>=2jam'] = 0;
        $total['SUM Libur'] = 0;
        $total['COUNT Libur'] = 0;
        $total['COUNT Kerja'] = 0;
        $total['SUM Kerja'] = 0;
        $content_approve="";
        //////echo $id;
        //////echo '<pre>';////echo print_r($rekap);
        $date = $tgl_awal;
        $total_all = 0;
        $help = new Helper_function();
        for ($i = 0; $i <= $help->hitunghari($tgl_awal,$tgl_akhir); $i++) {
            $content = "";
            $warna = '';
            $font = '';
            $bool_hari_libur = Helper_function::bool_hari_libur($rekap,$date,$id_karyawan,$info_karyawan,$hari_libur,$hari_libur_shift,$hari_libur_except_pengecualian,$hari_libur_except_pengkhususan,$potong_gaji);
									
									if (!$bool_hari_libur)
										$color = 'Style="background:red;color:white"';
									else
										$color='';
                                        if (isset($rekap['lembur']['Normal']['approve'][$date][$id_karyawan]['lama'])) {
									 
                                            $total_all += $rekap['lembur']['Normal']['approve'][$date][$id_karyawan]['lama'];
                                               
                                               
                                               if (!$bool_hari_libur) {
                                                   $lama = $rekap['lembur']['Normal']['approve'][$date][$id_karyawan]['lama'];
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
                                                   $total['SUM Libur'] +=$rekap['lembur']['Normal']['approve'][$date][$id_karyawan]['lama'];
                                               } else {
                                                   $lama = $rekap['lembur']['Normal']['approve'][$date][$id_karyawan]['lama'];
                                                   $total['1jam'] +=1;$lama-=1;
                                                   if ($lama)
                                                       $total['>=2jam'] +=$lama;
       
       
                                                   $total['COUNT Kerja'] +=1;
                                                   $total['SUM Kerja'] +=$rekap['lembur']['Normal']['approve'][$date][$id_karyawan]['lama'];
                                               }
                                           //$content .= '' . $rekap[$id_karyawan][$date]['lama'] . '';
                                           $string_jenis_ijin='LEMBUR';
                                                       if(isset($rekap[$id_karyawan]['string'][$string_jenis_ijin])){
                                                                       $rekap[$id_karyawan]['string'][$string_jenis_ijin] .= " ". $date.' : '.$rekap['lembur']['Normal']['approve'][$date][$id_karyawan]['lama'].' Jam | ';
                                                       }else if(!isset($rekap[$id_karyawan]['string'][$string_jenis_ijin])){
                                                                       $rekap[$id_karyawan]['string'][$string_jenis_ijin] = $date.' : '.$rekap['lembur']['Normal']['approve'][$date][$id_karyawan]['lama'].' Jam | ';
                                                       }
                                                       
                                           $content_approve .= ' <td '.$color.'>
                                                       
                                                       '.$rekap['lembur']['Normal']['approve'][$date][$id_karyawan]['lama'].' Jam
                                                           <br> '.$rekap['lembur']['Normal']['approve'][$date][$id_karyawan]['jam_awal'].'
                                                                '.($rekap['lembur']['Normal']['approve'][$date][$id_karyawan]['jam_akhir']?' s/d '.$rekap['lembur']['Normal']['approve'][$date][$id_karyawan]['jam_akhir']:'').'
                                                               <br>  '.$rekap['lembur']['Normal']['approve'][$date][$id_karyawan]['keterangan'].'
                                                       </td >';
                                           } else if (isset($rekap['lembur']['Normal']['Proposional']['approve'][$id_karyawan][$date]['lama'])) {
                                               $content_approve .= '<td style="background:purple;color:white"> Lembur Proposional </td>';
                                           } else {
       
                                               $content_approve .= '<td '.$color.'>-</td>';
                                           }
                                           $date = $help->tambah_tanggal($date,1);
                                       }

        $ipd = isset($rekap['total'][$id_karyawan]['ipd']) ? $rekap['total'][$id_karyawan]['ipd'] : 0;
        $masuk = isset($rekap['total'][$id_karyawan]['absen_masuk']) ? $rekap['total'][$id_karyawan]['absen_masuk'] : 0;
        $cuti            = isset($rekap['total'][$id_karyawan]['cuti']) ? $rekap['total'][$id_karyawan]['cuti'] : 0;
        $ipg            = isset($rekap['total'][$id_karyawan]['ipg']) ? $rekap['total'][$id_karyawan]['ipg'] : 0;
        $izin            = isset($rekap['total'][$id_karyawan]['ihk']) ? $rekap['total'][$id_karyawan]['ihk'] : 0;
        $ipc            = isset($rekap['total'][$id_karyawan]['ipc']) ? $rekap['total'][$id_karyawan]['ipc'] : 0;
        $ipd            = isset($rekap['total'][$id_karyawan]['ipd']) ? $rekap['total'][$id_karyawan]['ipd'] : 0;
        $idt            = isset($rekap['total'][$id_karyawan]['idt']) ? $rekap['total'][$id_karyawan]['idt'] : 0;
        $ipm            = isset($rekap['total'][$id_karyawan]['ipm']) ? $rekap['total'][$id_karyawan]['ipm'] : 0;
        $pm                = isset($rekap['total'][$id_karyawan]['pm']) ? $rekap['total'][$id_karyawan]['pm'] : 0;
        $sakit            = isset($rekap['total'][$id_karyawan]['sakit']) ? $rekap['total'][$id_karyawan]['sakit'] : 0;
        $alpha            = isset($rekap['total'][$id_karyawan]['alpha']) ? $rekap['total'][$id_karyawan]['alpha'] : 0;
        $terlambat         = isset($rekap['total'][$id_karyawan]['terlambat']) ? $rekap['total'][$id_karyawan]['terlambat'] : 0;
        $fingerprint     = isset($rekap['total'][$id_karyawan]['fingerprint']) ? $rekap['total'][$id_karyawan]['fingerprint'] : 0;
        $ijin_libur     = isset($rekap['total'][$id_karyawan]['ijin_libur']) ? $rekap['total'][$id_karyawan]['ijin_libur'] : 0;
        $masuk_libur     = isset($rekap['total'][$id_karyawan]['masuk_libur']) ? $rekap['total'][$id_karyawan]['masuk_libur'] : 0;
        $hari_bulan     = isset($rekap['total'][$id_karyawan]['hari_bulan']) ? $rekap['total'][$id_karyawan]['hari_bulan'] : 0;
        $hari_kerja     = isset($rekap['total'][$id_karyawan]['hari_kerja']) ? $rekap['total'][$id_karyawan]['hari_kerja'] : 0;
        $mesin     = isset($rekap['total'][$id_karyawan]['mesin']) ? $rekap['total'][$id_karyawan]['mesin'] : 0;
       
		$return['total_tahunan'] = $rekap['total_tahunan'][$id_karyawan];
		$return['total_beruntun'] = $rekap['total_beruntun'][$id_karyawan];

        $return['all_content'] = $all_content;
        $return['all_content_cek_absen'] = $all_content_cek_absen;
        $return['all_content_approve'] = $content_approve;
        $return['content_sheet'] = $content_sheet;
        $return['total']['masuk'] = $masuk;
        $return['total']['cuti'] = $cuti;
        $return['total']['ipg'] = $ipg;
        $return['total']['izin'] = $izin;
        $return['total']['mesin'] = $mesin;
        $return['total']['ipd'] = $ipd;
        $return['total']['ipc'] = $ipc;
        $return['total']['sakit'] = $sakit;
        $return['total']['alpha'] = $alpha;
        $return['total']['idt'] = $idt;
        $return['total']['ipm'] = $ipm;
        $return['total']['pm'] = $pm;
        $return['total']['fingerprint'] = $fingerprint;
        $return['total']['terlambat'] = $terlambat;
        $return['total']['ijin_libur'] = $ijin_libur;
        $return['total']['masuk_libur'] = $masuk_libur;
        $return['total']['hari_bulan'] = $hari_bulan;
        $return['total']['hari_kerja'] = $hari_kerja;
        $return['total']['Total Absen'] = $masuk + $ipd + $fingerprint;
        $return['total']['Total Masuk'] = $masuk + $cuti + $ipg + $izin + $ipd + $sakit + $ipc + $fingerprint;
        $return['total']['Total Hari Kerja'] = $masuk + $cuti + $ipg + $izin + $ipd + $sakit + $alpha + $ipc + $fingerprint;
        $return['total']['terlambatList'] 			= $rekap['total'][$id_karyawan]['terlambatList'];
        $return['total']['alphaList'] 			= $rekap['total'][$id_karyawan]['alphaList'];
        $return['total']['IPGCuti'] 			= $rekap['total'][$id_karyawan]['IPGCuti'];
        $return['total']['IPGCutiList'] 		= $rekap['total'][$id_karyawan]['IPGCutiList'];
        
        $return['total_lembur']		= isset($rekap[$id_karyawan]['lembur'])?$rekap[$id_karyawan]['lembur']:
        array("total_pengajuan"=>0,"total_pending"=>0,"total_approve"=>0,"total_tolak"=>0,"total_lembur_proposional"=>0,"tgl_pending"=>'');;
        
        
        $return['string'] = isset($rekap[$id_karyawan]['string'])?$rekap[$id_karyawan]['string']:array();
        $return['string']['AlPHA'] = $return['total']['alphaList'];
        $return['string']['IPGCuti'] = $return['total']['IPGCutiList'];

        $return['total']['total_all']    = $total_all;
        $return['total']['<8 jam']        = $total['<8 jam'];
        $return['total']['8 jam']        = $total['8 jam'];
        $return['total']['9 jam']        = $total['9 jam'];
        $return['total']['>=10 jam']    = $total['>=10 jam'];
        $return['total']['1jam']        = $total['1jam'];
        $return['total']['>=2jam']        = $total['>=2jam'];
        $return['total']['SUM Libur']        = $total['SUM Libur'];
        $return['total']['COUNT Libur']        = $total['COUNT Libur'];
        $return['total']['SUM Kerja']        = $total['SUM Kerja'];
        $return['total']['COUNT Kerja']        = $total['COUNT Kerja'];

        $return['sheet'] = $sheet;
        $return['warna_sheet'] = $warna_sheet;
        $return['i'] = $i;



        return $return;
    }
    
    public static function generate_rekap_absen_tanggal($tanggal){
    	$help = new Helper_function();
    	$return = $help->rekap_absen($tanggal,$tanggal,$tanggal,$tanggal);
    	$count = DB::connection()->select("select * from absen_master_rekap_tanggal where tanggal= '$tanggal' ");
    	if(count($count)){
    		//->where('periode_gajian',$periode_gajian)
    	    	DB::table("absen_master_rekap_tanggal")->where('tanggal',$tanggal)->where('active',1)->update([
				"tanggal"=>$tanggal,
				//"periode_gajian"=>$periode_gajian,
				"json_rekap"=>$return['rekap_json'],
				"update_date"=>date('Y-m-d H:i:s'),
			]);
    	}else{
		DB::table("absen_master_rekap_tanggal")->insert(
			[
				"tanggal"=>$tanggal,
				//"periode_gajian"=>$periode_gajian,
				"json_rekap"=>$return['rekap_json'],
				"create_date"=>date('Y-m-d H:i:s'),
			]
			);
		}
	}
   
    public static function rekap_absen_optimasi($id_periode_absen,$tgl_awal, $tgl_akhir, $type,$get = null,$karyawan=null)
    {
        $rekap = array();
		$absen = DB::connection()->select("select * 
		from absen_master_rekap_tanggal 
			where tanggal>='$tgl_awal' and tanggal<='$tgl_akhir'
			
			");
		foreach($absen as $absen){
			//echo '<pre>';
			//print_r(json_decode($absen->json_rekap,true));die;
			$rekap[] = $absen->json_rekap;
		}
		$array = array();
		
		
	 	for($i=0;$i<count($rekap);$i++){
	 		//$rekap[$i] = trim(preg_replace('/\s+/', ' ', $rekap[$i]));
	 		
	 		$array =array_merge_recursive($array,json_decode($rekap[$i],true));
	 	}
	 	//echo $rekap[2];
	 	$rekap = $array;
	 	//echo '<pre>';
	 	//print_r($rekap['absen']['a']['2023-10-04']);die;
        $request = new Request;
        $list_permit = array();
        $iduser = Auth::user()->id;
        $sqluser = "SELECT p_recruitment.foto,role,p_karyawan_pekerjaan,p_karyawan.p_karyawan_id,p_karyawan_pekerjaan.m_lokasi_id,user_entitas_access FROM users
		left join m_role on m_role.m_role_id=users.role
		left join p_karyawan on p_karyawan.user_id=users.id
		left join p_karyawan_pekerjaan on p_karyawan_pekerjaan.p_karyawan_id=p_karyawan.p_karyawan_id
		left join p_recruitment on p_recruitment.p_recruitment_id=p_karyawan.p_recruitment_id
		where users.id=$iduser";
        $user = DB::connection()->select($sqluser);
        $id_karyawan = $user[0]->p_karyawan_id;
        if($id_karyawan){
       
		$jabstruk = Helper_function::jabatan_struktural($id_karyawan);
		$atasan = $jabstruk['atasan'];
		$bawahan = $jabstruk['bawahan'];
		$sejajar = $jabstruk['sejajar'];
        }else{
            $atasan = "";
            $bawahan = "";
            $sejajar = "";
        }
		$wherebawahan = '';
		$wherebawahan2 = '';
		$wherebawahan3 = '';
        
         $rekap[$id_karyawan]['string'] = array();
        if (!$get) {

            if ($type != -1) {

                $where = " d.periode_gajian = " . $type;
                $appendwhere = "and";
            } else {
                $where = "";
                $appendwhere = "";
            }
        } else {
            $where = "";
            $appendwhere = "";
            $wherebawahan = "and c.m_jabatan_id in ($bawahan)";
            $wherebawahan2 = "and a.m_jabatan_id in ($bawahan)";
            $wherebawahan3 = "and d.m_jabatan_id in ($bawahan)";
        }
        $where_karyawan = '';
        $where_karyawan_2 = '';
        $where_departement = '';
        if (isset($_GET['departemen'])) {
            if ($_GET['departemen'])
                $where_departement = ' and m_departemen.m_departemen_id=' . $_GET['departemen'];
        }  
        
        $sql = "select * from m_hari_libur where tanggal >= '$tgl_awal'  and active=1 and tanggal <='$tgl_akhir' ";
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
            $min = $tgl_awal;

        
        if ($request->entitas_absen_search) {

            
                $id_lokasi = $user[0]->user_entitas_access;
                $whereLokasi = "AND d.m_lokasi_id = $id_lokasi";
           
        }else if ($user) {

            if ($user[0]->user_entitas_access) {
                $id_lokasi = $user[0]->user_entitas_access;
                $whereLokasi = "AND d.m_lokasi_id = $id_lokasi";
            } else {
                $whereLokasi = "AND d.m_lokasi_id != 5";
                $whereLokasi = "";
            }
        }  else {
            $whereLokasi = "";
        }

        	 $id_lokasi = Auth::user()->user_entitas; 
            
            if($id_lokasi and $id_lokasi!=-1) 
    			$whereLokasi .= "AND d.m_lokasi_id in($id_lokasi)";					
    		else
    			$whereLokasi .= "";	
    			
    		
        $where_filter_entitas = '';
        $where_filter_jabatan = '';
        if(isset($_GET['filterentitas'])){
        if(!empty($_GET['filterentitas'])){
        	$where_filter_entitas = " AND d.m_lokasi_id = ".$_GET['filterentitas'];
        }
        }
        if(isset($_GET['filterjabatan'])){
        if(!empty($_GET['filterjabatan'])){
        	$where_filter_jabatan = " AND d.m_jabatan_id = ".$_GET['filterjabatan'];
        }
        }
        $periode = DB::connection()->select("select * from m_periode_absen where periode_absen_id=$id_periode_absen");
        $whereentitas_periode = "";
        if( $periode[0]->entitas_list){
            
        
            $whereentitas_periode = " and d.m_lokasi_id in (".$periode[0]->entitas_list.")
            --periode
            ";
        }
        $sql = "SELECT c.p_karyawan_id,c.nama,c.nik,m_lokasi.kode as nmlokasi,m_departemen.nama as departemen , f.m_pangkat_id ,m_jabatan.nama as nmjabatan,is_shift as is_karyawan_shift,foto,no_absen
		FROM p_karyawan c
		LEFT JOIN p_karyawan_pekerjaan d on d.p_karyawan_id=c.p_karyawan_id
		LEFT JOIN p_karyawan_absen i on i.p_karyawan_id=c.p_karyawan_id
		LEFT JOIN p_recruitment h on h.p_recruitment_id=c.p_recruitment_id
		
		LEFT JOIN m_departemen on m_departemen.m_departemen_id=d.m_departemen_id
		LEFT JOIN m_lokasi on m_lokasi.m_lokasi_id=d.m_lokasi_id
		LEFT JOIN m_jabatan on m_jabatan.m_jabatan_id=d.m_jabatan_id
		LEFT JOIN m_jabatan f on d.m_jabatan_id=f.m_jabatan_id
		LEFT JOIN p_karyawan_kontrak g on d.p_karyawan_id=g.p_karyawan_id and g.active=1
		WHERE $where $appendwhere  c.active = 1
		
		$whereLokasi
		$where_departement
		AND f.m_pangkat_id != 6
		$where_filter_entitas
		$where_filter_jabatan
        $whereentitas_periode
		----
		$where_karyawan
		$wherebawahan3
		order by c.nama,m_departemen.nama
		
		";;
        $list_karyawan = DB::connection()->select($sql);
        $sql = "select * from t_pergantian_hari_libur 
        		where tgl_pengganti_hari>='$tgl_awal' and tgl_pengganti_hari<='$tgl_akhir'
        		and status_appr=1
        		and active=1
        ";
        $pengganti_hari = DB::connection()->select($sql);
        
		foreach($pengganti_hari as $pengganti_hari){
			$rekap['perganitan_hari_libur_awal'][$pengganti_hari->p_karyawan_id][$pengganti_hari->tgl_pengganti_hari]=true;
			$rekap['perganitan_hari_libur_ke'][$pengganti_hari->p_karyawan_id][$pengganti_hari->tgl_pengajuan]=true;
		}

        

        $rekap['list_karyawan']     = $list_karyawan;
        $rekap['hari_libur']         = $hari_libur;
        $rekap['hari_libur_except_pengecualian']         = $hari_libur_except_pengecualian;
        $rekap['hari_libur_except_pengkhususan']         = $hari_libur_except_pengkhususan;
        $rekap['hari_libur_shift']     = $hari_libur_shift;
        $rekap['tgl_awal']             = $tgl_awal;
        $rekap['tgl_akhir']         = $tgl_akhir;
        $rekap['tgl_awal_lembur']     = null;
        $rekap['tgl_akhir_lembur']     = null;
        $rekap['list_permit']         = $list_permit;
        $rekap['mesin']             = $mesin;
        $rekap['tanggallibur']         = $tanggallibur;

        return $rekap;
    }
    public static
    function total_rekap_absen_optimasi($rekap, $id_karyawan, $type = "rekap", $sheet = null, $rows = 0)
    {
        
		Return Helper_function::total_rekap_absen($rekap,$id_karyawan,$type,$sheet,$rows);
	}   
    
    public static function preview_gaji($data_row, $type,$total_field=array(),$data=null, $list_karyawan=null, $sudah_appr_directur=null, $id_prl=null,$total=null)
    {
        
        $return['content'] = '';
        $field = array();
        $listkaryawan = $list_karyawan;
        for ($x = 0; $x < count($data_row); $x++) {
            $row1 = $data_row[$x][0];
            $row2 = $data_row[$x][1];
            $row3 = $data_row[$x][2];
			$row4 = $data_row[$x][3];
			$row5 = isset($data_row[$x][4])?$data_row[$x][4]:array();
			if($type==2){
				 $return['total'][$row1] = 0 ;
			}else if($type==3){
				if($row4=='absensi')
							$nominal = $total_field[$row1];
						else
							$nominal = Helper_function::rupiah2($total_field[$row1]);
				 $return['content'] .= ' <td id="total_field-'.$row2.'" style="font-size:13px;text-align:align">'. ($nominal) .'</td>';
			}else if($type==1){
	           $tooltip = '';
	      	  if (isset($data[$list_karyawan->p_karyawan_id]['Keterangan'][$row1])){
	           $tooltip = ' data-toggle="tooltip" data-placement="top" title="'.$data[$list_karyawan->p_karyawan_id]['Keterangan'][$row1].'" ';
	           
	      	      $keterangan = $data[$list_karyawan->p_karyawan_id]['Keterangan'][$row1]; 
	      	  }else{
	      	      $keterangan = '';
	      	  }
				$return['content'] .= '<td style="font-size:13px">';
				
				
					if($row3==3){
						
						$$row2= 0;
						$$row2= 0;
						for($i1=0;$i1<count($row5);$i1++){
							$operator = $row5[$i1][0];
							for($i2=0;$i2<count($row5[$i1][1]);$i2++){
								$type_field = $row5[$i1][1][$i2][0];
								$row = $row5[$i1][1][$i2][1];
						//print_r($row5[$i1][1][$i2]);die;
								if($type_field=='field')
									$nom = $field[$list_karyawan->p_karyawan_id][$row];
								else
									$nom = $row;
								
								//if($i1==1);echo $operator;die;
								
								
								if($$row2==0){
									$$row2 = $nom;	
								}else{
									if($operator=='Kali')
									$$row2*= $nom;
									else if($operator=='Tambah')
									$$row2+= $nom;
									else if($operator=='Kurang')
									$$row2-= $nom;
									else if($operator=='Bagi')
									$$row2 = $$row2/$nom;
								}
							}
						}
						if($row2=='gaji_total_upah_harian'){
							if($ha==0){
								$$row2 = 0;
							}
						}
							
					 $return['content'] .= ' 
					
                                
                                <div  id="'. $row2 .'-'. $list_karyawan->p_karyawan_id.'" '.$tooltip.'>'.Helper_function::rupiah2($$row2).'</div>
                                <input  id="input-'. $row2 .'-'. $list_karyawan->p_karyawan_id.'" 
                                class="total_'.$row4.' total_'.$row4.'-'.$list_karyawan->nmlokasi.' total_'.$row4.'-'.$list_karyawan->p_karyawan_id.' total_'.$row4.'-'. $row2 .'"
                                value="'.$$row2.'" type="hidden">
                                
                                ';	
					}
					else if($row3==4){
						$$row2= 0;
						// print_r($row5);echo '<br>';
						 //echo '<br>';
						// echo '<br>';
						for($i1=0;$i1<count($row5);$i1++){
							$operator = $row5[$i1][0];
							for($i2=0;$i2<count($row5[$i1][1]);$i2++){
								$type_field = $row5[$i1][1][$i2][0];
								$row = $row5[$i1][1][$i2][1];
								
								if($type_field=='field')
									$nom = $field[$list_karyawan->p_karyawan_id][$row];
								else
									$nom = $row;
								//echo '<br>$row'.$row;echo '$nom'.$nom;echo '$$row'.$$row2;
								if($$row2==0){
									$$row2 = $nom;	
								}else{
									if($operator=='Kali')
									$$row2*= $nom;
									else if($operator=='Tambah')
									$$row2+= $nom;
									else if($operator=='Kurang')
									$$row2-= $nom;
									else if($operator=='Bagi')
									$$row2 /= $nom;
								}
								
							}
						}
						
					 $return['content'] .= '
					<div id="'.$row4.'-'.$list_karyawan->p_karyawan_id.'" '.$tooltip.'>'.Helper_function::rupiah2($$row2).'</div>
                        <input class="'.$row2.'" type="hidden" value="'.$$row2.'" id="input-'.$row2.'-'.$list_karyawan->p_karyawan_id.'">
                               
                                ';	
					}
					else if($row3==1 or $row3==2){
					 $$row2 = isset($data[$list_karyawan->p_karyawan_id][$row1]) ? $data[$list_karyawan->p_karyawan_id][$row1] :   0;
					 
					 if($row4=='absensi')
							$nominal = $$row2;
						else
							$nominal = Helper_function::rupiah2($$row2);
						if (!$list_karyawan->pajak_onoff) {
			                 $return['content'] .= '<div style="font-size:8px">Edit On Offnya dl</div>';
			            } else if (!$sudah_appr_directur[$list_karyawan->m_lokasi_id][$list_karyawan->pajak_onoff]) 
			            {

		             
		             if (!isset($data[$list_karyawan->p_karyawan_id]['id'][$row1]))
		                $data[$list_karyawan->p_karyawan_id]['id'][$row1] = -1;
							
						
					 $return['content'] .= '
					 <a 
						 '.$tooltip.'
					 	href="javascript:void(0)" class="text-black '. $row2 .'-'. $list_karyawan->p_karyawan_id .'" 
					 	id="'. $row2 .'-'. $list_karyawan->p_karyawan_id 
					 		 .'-'. $data[$list_karyawan->p_karyawan_id]['id'][$row1];
					 if($row4=='absensi' or $row2=='korekplus' or $row2=='korekmin'){		 
					$return['content'] .='"  
					 	onclick="change_nominal('.
							$data[$list_karyawan->p_karyawan_id]['id'][$row1].','.
							$$row2.','."'".
							$row2."'".','
							."'".$row1."'".','
							."'".$list_karyawan->p_karyawan_id."'".','
							."'".$list_karyawan->nmlokasi."'".','
							."'".$keterangan."'".
						
						')';
					 }
					$return['content'] .='">
	                        '. $nominal .'
	                 </a>
					<input class="total_'. $row4 .
								 ' total_'. $row4 .'-'. $list_karyawan->nmlokasi .
								 ' total_'. $row4 .'-'. $list_karyawan->p_karyawan_id .
								 ' total_'. $row4 .'-'. $row2 .
								 ' total_'. $row4 .'-'. $row2 .'-'. $list_karyawan->p_karyawan_id .
								 ' total_'. $row4 .'-'. $row1 .' " 
						type="hidden" 
						value="'. $$row2 .'" 
						id="input-'. $row2 
								.'-'. $list_karyawan->p_karyawan_id 
								.'-'. $data[$list_karyawan->p_karyawan_id]['id'][$row1] .'">';
			        	if ($row3==1) 
			        	{
				            $nama_row = $row2;
				            if (!isset($data[$list_karyawan->p_karyawan_id][$nama_row]['master']))
				            	$data[$list_karyawan->p_karyawan_id][$nama_row]['master'] = 0;
				            
				            if ($$row2 != $data[$list_karyawan->p_karyawan_id][$nama_row]['master'])
				                 $return['content'] .= '
				            			<div style="color:red">
				            				<a href="#"><i class="fa fa-lightbulb-o "></i>
				                            </a> ' .
											Helper_function::rupiah2($data[$list_karyawan->p_karyawan_id][$nama_row]['master']) .
										'</div>';
						}else if($row3==2 and $row2=='lembur'){
							if($list_karyawan->kperiode_gajian==1){
							$HITUNG_gapok = isset($data[$list_karyawan->p_karyawan_id]['Gaji Pokok'])?$data[$list_karyawan->p_karyawan_id]['Gaji Pokok']:0;
							$HITUNG_gapok += isset($data[$list_karyawan->p_karyawan_id]['Tunjangan Grade'])?$data[$list_karyawan->p_karyawan_id]['Tunjangan Grade']:0;
							$HITUNG_gapok += isset($data[$list_karyawan->p_karyawan_id]['Tunjangan Entitas'])?$data[$list_karyawan->p_karyawan_id]['Tunjangan Entitas']:0;
						}
							else if($list_karyawan->kperiode_gajian==0){
								
							$HITUNG_gapok = isset($data[$list_karyawan->p_karyawan_id]['Upah Harian'])?$data[$list_karyawan->p_karyawan_id]['Upah Harian']:0;
							$HITUNG_gapok *=22;
							}
							else
								$HITUNG_gapok = 0;
							if(!isset($data[$list_karyawan->p_karyawan_id]['Lembur 1 Jam']))
							    $data[$list_karyawan->p_karyawan_id]['Lembur 1 Jam']=0;
							if(!isset($data[$list_karyawan->p_karyawan_id]['Lembur >=2 Jam']))
							    $data[$list_karyawan->p_karyawan_id]['Lembur >=2 Jam']=0;
							if(!isset($data[$list_karyawan->p_karyawan_id]['Lembur 8 Jam']))
							    $data[$list_karyawan->p_karyawan_id]['Lembur 8 Jam']=0;
							if(!isset($data[$list_karyawan->p_karyawan_id]['Lembur 9 Jam']))
							    $data[$list_karyawan->p_karyawan_id]['Lembur 9 Jam']=0;
							if(!isset($data[$list_karyawan->p_karyawan_id]['Lembur >=10 Jam']))
							    $data[$list_karyawan->p_karyawan_id]['Lembur >=10 Jam']=0;
							 $HITUNG_lembur_kerja = ($data[$list_karyawan->p_karyawan_id]['Lembur 1 Jam'] * (1.5 / 173) * $HITUNG_gapok) + ($data[$list_karyawan->p_karyawan_id]['Lembur >=2 Jam'] * (2 / 173) * $HITUNG_gapok);
            				$HITUNG_libur = (($data[$list_karyawan->p_karyawan_id]['Lembur 8 Jam'] * (2 / 173) * $HITUNG_gapok) + (($data[$list_karyawan->p_karyawan_id]['Lembur 9 Jam'] * (3 / 173) * $HITUNG_gapok) + (($data[$list_karyawan->p_karyawan_id]['Lembur >=10 Jam'] * (4 / 173) * $HITUNG_gapok))));
            				$HITUNG_lembur = round($HITUNG_lembur_kerja + $HITUNG_libur);
            				
            				if (($$row2 - $HITUNG_lembur> 10) or (($$row2 - $HITUNG_lembur> -10) and ($$row2 - $HITUNG_lembur< 0)) )
				                 $return['content'] .= '
				            			<div style="color:red">
				            				<a href="#"><i class="fa fa-lightbulb-o "></i>
				                            </a> ' .
											Helper_function::rupiah2($HITUNG_lembur) .
										'</div>';
							else if(!$HITUNG_gapok){
								$return['content'] .= '
				            			<div style="color:red;font-size:9px">
				            				<a href="#"><i class="fa fa-lightbulb-o "></i>
				                            </a> !PERHATIKAN GAPOK, DAN HITUNG&INPUTKAN LEMBUR</div>';
							}
							//$data[$row->p_karyawan_id][$row->nama]
						}
					} else {                                                                                                                                    $return['content'] .= $nominal;
					}
	           	$return['content'] .= '</td>';
	           }	
	           if(isset($$row2))
	           $total_field[$row1] +=  $$row2;
	           
	           
	           $field [$list_karyawan->p_karyawan_id][$row2] =  $$row2;
	            
				
				
	           $return['total'] = $total_field;
			   $return['field'] = $field;
		}
    }
    	//print_r($list_karyawan);die;
    	
    	
    
    return $return;
    }
    public static function query_cuti2($idkar,$type='karyawan')
    {
	
        $date2 = array();
        if($type=='karyawan'){
        	if(isset($idkar[0]->p_karyawan_id)){
        		
		        	$id  = $idkar[0]->p_karyawan_id;
		        	if(!$id)
		        	$id  = $idkar[0]->karyawan_id;
        	}else{
        		$id  = $idkar[0]->karyawan_id;
        		
        	}
        	$where = "and p_karyawan_id = $id";
            $pekerjaan = DB::connection()->select("select * from p_karyawan_pekerjaan left join m_departemen on p_karyawan_pekerjaan.m_departemen_id = m_departemen.m_departemen_id where 1=1 $where");
        	$where_2 = "and t_permit.p_karyawan_id = $id";
        	$tahun_bergabung = explode('-', $idkar[0]->tgl_bergabung)[0];
        	$where_reset = " and ditahun > $tahun_bergabung ";
        	$tgl_awal_cuti = Helper_function::tambah_bulan($idkar[0]->tgl_bergabung, 12);
        	$where_hari_libur = " and tanggal >='$tgl_awal_cuti'";
        	$visible = $idkar[0]->periode_gajian==1 ? true:false;
        }else{
        	$visible = true;
        	$where="";
        	$where_2="";
        	$where_reset="";
        	$where_hari_libur="";
            $pekerjaan = array();
        }
        $sqlfasilitas = "SELECT * FROM m_cuti
                WHERE 1=1 and active=1  $where";
        $m_cuti = DB::connection()->select($sqlfasilitas);

        $all = array();
        $rekap_cuti = array();
        $tanggal_loop = array();
        $sqlfasilitas = "SELECT * FROM t_cuti
			  WHERE 1=1  $where and  jenis != 2 order by tanggal ";
        $cuti = DB::connection()->select($sqlfasilitas);
        $if = 'CASE';
        $x = 0;
        if($visible){
        	
        
        foreach ($cuti as $c) {
            $all[$x]['id'] = $c->t_cuti_id;
            $all[$x]['tanggal'] = $c->tanggal;
            $all[$x]['tahun'] = $c->tahun;
            $all[$x]['keterangan'] = $c->keterangan;
            $all[$x]['jenis'] = $c->jenis;
            $all[$x]['lama'] = $c->nominal;
            $all[$x]['status'] = 1;
            
            
            $thn = date('Y',strtotime($c->tanggal));
            $bln = date('m',strtotime($c->tanggal));
            if($c->jenis==1){
					
					$rekap_cuti[$c->p_karyawan_id]['rekap_cuti_tahunan'][$thn][$bln] = $c->nominal;
				
            }else if($c->jenis==3){
					
					$rekap_cuti[$c->p_karyawan_id]['rekap_cuti_besar'][$thn][$bln] = $c->nominal;
				
            }else if($c->jenis==4){
					
					//$rekap_cuti[$c->p_karyawan_id]['reset_cuti_besar'][$thn][$bln] = $c->nominal;
				
            }else if($c->jenis==8){
					if(isset($rekap_cuti[$c->p_karyawan_id]['sinkronisasi'][$thn][$bln])){
					$rekap_cuti[$c->p_karyawan_id]['sinkronisasi'][$thn][$bln] += (int)$c->nominal;
					}else
					$rekap_cuti[$c->p_karyawan_id]['sinkronisasi'][$thn][$bln] = (int)$c->nominal;
            }
			

            $tanggal_loop[$x] = $c->tanggal;
            $date2[] = $c->tanggal;
            $x++;
        }
        $sqlfasilitas = "SELECT * FROM m_parameter_cuti
			  WHERE 1=1 $where_reset order by ditahun ";
        $pcuti = DB::connection()->select($sqlfasilitas);
        $if = 'CASE';

        foreach ($pcuti as $c) {
            $tgl = $c->tgl_reset;

            $all[$x]['tanggal'] = $tgl;
            $all[$x]['tahun'] = $c->ditahun;
            $all[$x]['keterangan'] = 'Reset Cuti Tahunan ' . $c->ditahun;
            $all[$x]['jenis'] = 2;
            $all[$x]['status'] = 1;
            $all[$x]['lama'] = 0;
            $tanggal_loop[$x] = $tgl;
            	 $thn = date('Y',strtotime($tgl));
            	$bln = date('m',strtotime($tgl));
            	$rekap_cuti['reset'][$x]['reset_cuti_tahun'][$thn][$bln] = 0;
            
            $date2[] = $tgl;
            $x++;
        }
        //echo '<pre>';
        //print_r($all);
        //echo '</pre>';
        //die;
        $wherebergabung = '';
        if(isset($idkar[0]->tgl_bergabung)){
            
        $wherebergabung = " and tanggal >= '".$idkar[0]->tgl_bergabung."'";
        }
        $sql = "select * from m_hari_libur where  is_cuti_bersama = 1
			    $wherebergabung
			  and active=1";
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
        $ipg_cuti = '';
        
        $sqlfasilitas = "SELECT * FROM m_hari_libur_cuti_ipg
			  WHERE 1=1 
			  $where
			  
			  and m_hari_libur_cuti_ipg.active=1
			  ";
        $lcuti = DB::connection()->select($sqlfasilitas);
        
        foreach ($lcuti as $c) {
			$tgl = $c->tanggal;
			$ipg_cuti.= "'".$tgl."'".',';
            $all[$x]['tanggal'] = $tgl;
            $all[$x]['keterangan'] = "Potongan IPG Cuti Bersama <br>Data Ini fixed setelah transaksi penggajian..";
            $all[$x]['jenis'] = 7; 
            $all[$x]['lama'] = 0;
            $all[$x]['status'] = 1;
            $tanggal_loop[$x] = $tgl;
	            $date2[] = $tgl;
	            $x++;
		}
        $ipg_cuti.="'1970-01-01'";
        $sqlfasilitas = "SELECT * FROM m_hari_libur
			  WHERE 1=1 and is_cuti_bersama = 1
			  and tanggal not in($ipg_cuti)
			  $wherebergabung
			  and active=1";
        $lcuti = DB::connection()->select($sqlfasilitas);
        $if = 'CASE';

        foreach ($lcuti as $c) {
        	 $date = $c->tanggal;
                	$ada=0;
        		if($type=='karyawan'){
        			
	        	 	if(isset($hari_libur_except_pengecualian[$date]) ){
	                	$ada++;
	                	if(in_array($idkar[0]->m_lokasi_id,$hari_libur_except_pengecualian[$date])){
	                		
	                		$bool_hari_libur=true;
	                	}else{
	                		$bool_hari_libur=false;
	                		
	                	}
	                }
	                if(isset($hari_libur_except_pengkhususan[$date]) ){
	                	$ada++;  
	                	        	
	                	if(in_array($idkar[0]->m_lokasi_id,$hari_libur_except_pengkhususan[$date])){
	                		$bool_hari_libur=false;
	                	}else{
	                		
	                		//die;
	                		$bool_hari_libur=true;
	                		
	                	}
	                }
        		}
                
                if(!$ada)
                		$bool_hari_libur=false;
                        $test = "";
                if(count($pekerjaan)){
                    if($pekerjaan[0]->m_departemen_id ==17 or $pekerjaan[0]->kode=='STORE')
                        $bool_hari_libur=true;
                        
                    }
        	if(!$bool_hari_libur){
        		
	            $tgl = $c->tanggal;
	            $all[$x]['tanggal'] = $tgl;
	            $all[$x]['tahun'] = explode('-', $tgl)[0];
	            $all[$x]['bulan'] = explode('-', $tgl)[1];
	            $all[$x]['keterangan'] = 'Cuti Bersama ' . $c->nama.' '.$test;
	            $all[$x]['jenis'] = 6;
	            if($type=='karyawan')
	           		$all[$x]['lama_gabung'] = Helper_function::hitungBulan($idkar[0]->tgl_bergabung, $tgl);
	            $all[$x]['tipe_cuti'] = $c->tipe_cuti_bersama;
	            $all[$x]['hari_libur_except_pengkhususan'] = isset($hari_libur_except_pengkhususan[$date])?($hari_libur_except_pengkhususan[$date]):array();
	            $all[$x]['hari_libur_except_pengecualian'] = isset($hari_libur_except_pengecualian[$date])?($hari_libur_except_pengecualian[$date]):array();
	            $all[$x]['status'] = 1;
	            $all[$x]['lama'] = 1;
	            $all[$x]['potong_gaji'] = 0;
	            
	            $thn = date('Y',strtotime($tgl));
	            $bln = date('m',strtotime($tgl));
				if(isset($rekap_cuti['all']['rekap_ajuan'][$thn][$bln])){
				$rekap_cuti['all']['rekap_ajuan'][$thn][$bln] += 1;
				}else
				$rekap_cuti['all']['rekap_ajuan'][$thn][$bln] = 1;
				
	            $tanggal_loop[$x] = $tgl;
	            $date2[] = $tgl;
	            $x++;
			}
        }
        $sqlfasilitas = "SELECT * FROM t_permit
			  join m_jenis_ijin on t_permit.m_jenis_ijin_id = m_jenis_ijin.m_jenis_ijin_id
			  WHERE 1=1  $where_2 
			  and (m_jenis_ijin.m_jenis_ijin_id = 5 or m_jenis_ijin.m_jenis_ijin_id = 25)  
			  and t_permit.active=1 
			  and t_permit.status_appr_1 !=2  
			  and ((tgl_awal>='2023-01-01' and (status_appr_hr!=2 or status_appr_hr is null)) or tgl_awal<'2023-01-01') order by tgl_awal ";
        //$help = new Helper_function();
        $dcuti = DB::connection()->select($sqlfasilitas);
        foreach ($dcuti as $c) {

            $tgl = $c->tgl_awal;

            $all[$x]['id'] = $c->t_form_exit_id;
            $all[$x]['tanggal'] = $tgl;
            $all[$x]['keterangan'] = $c->nama . '<br>' . Helper_function::tgl_indo_short($c->tgl_awal) . ' s/d ' . Helper_function::tgl_indo_short($c->tgl_akhir) . '<br>' . $c->keterangan;
            $all[$x]['lama'] = $c->lama;
            $all[$x]['jenis'] = 5;
            $all[$x]['status'] = $c->status_appr_1;
            
            $thn = date('Y',strtotime($c->tgl_awal));
            $bln = date('m',strtotime($c->tgl_awal));
			if(isset($rekap_cuti[$c->p_karyawan_id]['rekap_ajuan'][$thn][$bln])){
			$rekap_cuti[$c->p_karyawan_id]['rekap_ajuan'][$thn][$bln] += (int)$c->lama;
			}else
			$rekap_cuti[$c->p_karyawan_id]['rekap_ajuan'][$thn][$bln] = (int)$c->lama;
			
            $date2[] = $tgl;
            $tanggal_loop[$x] = $tgl;
            $x++;
        }


        asort($tanggal_loop);
        //print_r($tanggal_loop);

        sort($date2);
		}
        return array(
            "all" => $all,
            "rekap_cuti" => $rekap_cuti,
            "date" => $date2,
            "tanggal_loop" => $tanggal_loop,
        );
    }
    public static
    function perhitungan_cuti2($all, $datasisa, $hutang, $date, $i, $nominal, $jumlah,$ipg,$potong_gaji)
    {

		
        if (in_array($all[$i]['jenis'], [8])) {
            $nominal = $nominal * -1;
        }
        if (in_array($all[$i]['jenis'], [1, 3])) {
            if ($hutang < $all[$i]['lama']) {
                //hutang lebih kecil
				
                $datasisa[$all[$i]['tahun']] = $all[$i]['lama'] - $hutang;
                $hutang -= $hutang;
            } else {
                //hutang lebih besar
                $datasisa[$all[$i]['tahun']] = $all[$i]['lama'] - $all[$i]['lama'];
                $hutang -= $all[$i]['lama'];
            }
            $nominal += $all[$i]['lama'];
            $jumlah = $all[$i]['lama'];
            if ($all[$i]['jenis'] == 1) {
                $tahun[] = array();
            } else {
                $tahunbesar = array();
            }
        } else if (in_array($all[$i]['jenis'], [2, 4])) {
            if (!isset($datasisa[$all[$i]['tahun']])) {
                $datasisa[$all[$i]['tahun']] = 0;
            }
            $jumlah = -$datasisa[$all[$i]['tahun']];
            $nominal -= $datasisa[$all[$i]['tahun']];
            $datasisa[$all[$i]['tahun']] -= $datasisa[$all[$i]['tahun']];
         
        } else {
            $jumlah = -$all[$i]['lama'];
            $nominal -= $all[$i]['lama'];
            if (isset($datasisa)) {
                $l = 0;
                $tahun_terpilih = 0;
                foreach ($datasisa as $value => $key) {
                	/*cari cuti tahunan*/
                    if ($datasisa[$value] > 0 and $l == 0 and $value > 2000 and $all[$i]['lama'] <= $datasisa[$value]) {
                        $l++;
                        $tahun_terpilih = $value;
                    }
                }

                if ($tahun_terpilih and $datasisa[$tahun_terpilih] != 0) {
                   
                    $datasisa[$tahun_terpilih] -= $all[$i]['lama'];
                } else {
                    
                    if (count($datasisa)) {
                        $l = 0;
                        foreach ($datasisa as $value => $key) {
                            /*cari cuti besa*/
                            if ($datasisa[$value] > 0 and $l == 0) {
                                $l++;
                                $tahun_terpilih = $value;
                            }
                        }
                        if ($tahun_terpilih) {

                            if ($datasisa[$tahun_terpilih] <= $all[$i]['lama']) {

                                $sisa = ($all[$i]['lama'] - $datasisa[$tahun_terpilih]);
                                $datasisa[$tahun_terpilih] -= $datasisa[$tahun_terpilih];
                                $tahun_terpilih = null;
                                $l = 0;
                                foreach ($datasisa as $value => $key) {

                                    if ($datasisa[$value] > 0 and $l == 0) {
                                        $l++;
                                        $tahun_terpilih = $value;
                                    }
                                }

                                if ($tahun_terpilih) {
                                    $datasisa[$tahun_terpilih] -= $sisa;
                                } else {
                                    $hutang += $sisa;
                                }
                            } else {
                                $datasisa[$tahun_terpilih] -= $all[$i]['lama'];
                            }
                        } else {
                        	/*
                        	1 > Cuti Untuk Semua karyawan Kategori Tidak punya cuti = hutang cuti
                        	2 > Cuti Untuk Semua karyawan Karyawan >6 Bulan & kurang sisa = Hutang cuti, < 6 Bulan =potong gaji
                            3 > Cuti Untuk yang mempunyai Hak Cuti, yang tidak mempunyai & kurang sisa =  potong gaji
                            4 > Cuti Untuk yang mempunyai Hak Cuti, yang tidak mempunyai =  potong gaji, kurang sisa = Hutang Cuti
                            5 > Cuti Untuk yang mempunyai Hak Cuti, yang tidak potong gaji</option>
                            */
                            
                            /**
							* 
							* pehitungan kurang sisa
							* 
							*/
	                        	if (in_array($all[$i]['jenis'], [6])) {
	                        		;
	                        		$tahun_ = $all[$i]['tahun'];
	                        		$bulan_ = $all[$i]['bulan'];
	                        		if($all[$i]['tipe_cuti']==1){
	                        			$hutang += $all[$i]['lama'];
	                        		}else if($all[$i]['tipe_cuti']==2){
	                        			if($all[$i]['lama_gabung']>=6){
	                        				$hutang += $all[$i]['lama'];
	                        				
	                        			}else{
	                        				if(isset($ipg[$tahun_][$bulan_]))
		                        				$ipg[$tahun_][$bulan_] += $all[$i]['lama'];
		                        			else 
		                        				$ipg[$tahun_][$bulan_] = $all[$i]['lama'];
		                        			$potong_gaji[] = $all[$i]['tanggal'];
	                        			}
	                        		}else if($all[$i]['tipe_cuti']==3){
	                        			
	                        			if(isset($ipg[$tahun_][$bulan_]))
		                        				$ipg[$tahun_][$bulan_] += $all[$i]['lama'];
		                        			else 
		                        				$ipg[$tahun_][$bulan_] = $all[$i]['lama'];
		                        			$potong_gaji[] = $all[$i]['tanggal'];
	                        		}else if($all[$i]['tipe_cuti']==4){
	                        			$hutang += $all[$i]['lama'];
	                        		}else if($all[$i]['tipe_cuti']==5){
	                        			
	                        		}
	                        		
								}else{
									/* bukan hari libur */
	                           		 $hutang += $all[$i]['lama'];
								}
                        	
                        }
                    } 
                    else {
                    	
                    	/*
                        	1 > Cuti Untuk Semua karyawan Kategori Tidak punya cuti = hutang cuti
                        	2 > Cuti Untuk Semua karyawan Karyawan >6 Bulan & kurang sisa = Hutang cuti, < 6 Bulan =potong gaji
                            3 > Cuti Untuk yang mempunyai Hak Cuti, yang tidak mempunyai & kurang sisa =  potong gaji
                            4 > Cuti Untuk yang mempunyai Hak Cuti, yang tidak mempunyai =  potong gaji, kurang sisa = Hutang Cuti
                            5 > Cuti Untuk yang mempunyai Hak Cuti, yang tidak potong gaji</option>
                            */
                            
                            
                    	/*tidak ada*/
                        if (in_array($all[$i]['jenis'], [6])) {
	                        		if($all[$i]['tipe_cuti']==1){
	                        			$hutang += $all[$i]['lama'];
	                        		}else if($all[$i]['tipe_cuti']==2){
	                        			if($all[$i]['lama_gabung']>=6){
	                        				$hutang += $all[$i]['lama'];
	                        				
	                        			}else{
	                        				if(isset($ipg[$all[$i]['tahun']][$all[$i]['bulan']]))
		                        				$ipg[$all[$i]['tahun']][$all[$i]['bulan']] += $all[$i]['lama'];
		                        			else 
		                        				$ipg[$all[$i]['tahun']][$all[$i]['bulan']] = $all[$i]['lama'];
		                        			$potong_gaji[] = $all[$i]['tanggal'];
	                        			}
	                        		}else if($all[$i]['tipe_cuti']==3){
	                        			if(isset($ipg[$all[$i]['tahun']][$all[$i]['bulan']]))
	                        			$ipg[$all[$i]['tahun']][$all[$i]['bulan']] += $all[$i]['lama'];
	                        			else 
	                        			$ipg[$all[$i]['tahun']][$all[$i]['bulan']] = $all[$i]['lama'];
		                        			$potong_gaji[] = $all[$i]['tanggal'];
	                        		}else if($all[$i]['tipe_cuti']==4){
	                        			if(isset($ipg[$all[$i]['tahun']][$all[$i]['bulan']]))
	                        				$ipg[$all[$i]['tahun']][$all[$i]['bulan']] += $all[$i]['lama'];
	                        			else 
	                        				$ipg[$all[$i]['tahun']][$all[$i]['bulan']] = $all[$i]['lama'];
		                        			$potong_gaji[] = $all[$i]['tanggal'];
	                        		}else if($all[$i]['tipe_cuti']==5){
	                        			
	                        		}
	                        		
								}else{
									/* bukan hari libur */
	                           		 $hutang += $all[$i]['lama'];
								}
                        
                       // $hutang += $all[$i]['lama'];
                    }
                }
            }

            //$datasisa[$all[$i]['tahun']] -= $all[$i]['lama'];
        }
        
        if ($hutang < 0) {
            $datasisa[date('Y')] = $hutang * -1;
            $hutang = 0;
        }
        $return['datasisa'] = $datasisa;
        
        $return['hutang'] = $hutang;
        $return['nominal'] = $nominal;
        $return['jumlah'] = $jumlah;
        $return['ipg'] = $ipg;
        $return['potong_gaji'] = $potong_gaji;
        return $return;
    }
    public static
    function cuti(){
    	$iduser = Auth::user()->id;
        $sqlidkar = "select *,p_karyawan.p_karyawan_id as karyawan_id from p_karyawan 
        			left join p_karyawan_pekerjaan a on a.p_karyawan_id = p_karyawan.p_karyawan_id 
        			left join m_jabatan on m_jabatan.m_jabatan_id = a.m_jabatan_id
        			left join p_karyawan_kontrak on p_karyawan_kontrak.p_karyawan_id = p_karyawan.p_karyawan_id and p_karyawan_kontrak.active=1
        			left join p_karyawan_gapok on p_karyawan_gapok.p_karyawan_id = p_karyawan.p_karyawan_id and p_karyawan_gapok.active=1
        			where user_id=$iduser";
        $idkar = DB::connection()->select($sqlidkar);
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
											foreach ($datasisa as $value => $key) {
												$tahun = $value;
												if ($value > 2000)
													$value = 'Sisa Cuti Tahun ' . $value;
												else
													$value = 'Sisa Cuti Besar ke ' . $value;
												//echo $value.' : 	'.$key.'<br>';
												if ($key or $tahun >= date('Y') - 1) {
													$totalcuti += $key;
												}
			}
			}
			$return['totalcuti'] 	= $totalcuti;
			$return['hutang'] 		= $hutang;
			return $return;
    }
    public static function lap_faskes($id)
    {
    	$sqlfasilitas="SELECT * FROM t_faskes
        		left join p_karyawan_pekerjaan a on t_faskes.p_karyawan_id = a.p_karyawan_id  
                WHERE 1=1 and t_faskes.active=1 and t_faskes.p_karyawan_id = $id order by tanggal_pengajuan,t_faskes.create_date ";
        $faskes=DB::connection()->select($sqlfasilitas);
        $no=0;$nominal=0; $bpjs = 0;$kontent='';
            if(!empty($faskes)){
            	
				foreach($faskes as $faskes):
                    $no++;
                           if($faskes->appr_status==1){
						   	
                            if($faskes->jenis==1)
                            	$nominal=$faskes->nominal;
                            else if($faskes->jenis==2)
                            	$nominal-=$faskes->nominal;
                             else if($faskes->jenis==3)
                            	$nominal+=$faskes->nominal;
                             	
						   }
						   if($bpjs==1){
						   	$nominal = 0;
						   }						   
						   if($faskes->tanggal_pengajuan>= $faskes->tgl_bpjs_kantor and !$bpjs and $faskes->bpjs_kantor){
						   		$nominal = 0;
						   $kontent .='<tr>
						   		<td>'.$no.'</td>
                                <td>'.Helper_function::tgl_indo($faskes->tgl_bpjs_kantor).'</td>
                                <td>Sudah Mempunyai BPJS Dari Kantor</td>
                                <td style="text-align: center;width: 30px;"></td>
                                <td><span class="fa fa-check-circle"> Disetujui</span></td>
                                
                                <td>0</td>
                                <td>0</td>
                                
                                <td>'.Helper_function::rupiah2($nominal).'</td>
						   </tr>';
						   
						   
						  
						   $bpjs=1;
						   }
                            $kontent .='
                            <tr>
                                <td>'.$no .'</td>
                                <td>'.Helper_function::tgl_indo($faskes->tanggal_pengajuan) .'</td>
                                <td  style="width: 30px;">'.$faskes->keterangan.'</td>';
                               if(!empty($faskes->foto))
                                      $kontent .='<td style="text-align: center;width: 30px;"><a href="'.asset('dist/img/file/'.$faskes->foto) .'" target="_blank" title="Download"><span class="fa fa-download"></span></a></td>';
                                else
                                    $kontent .='<td style="text-align: center;width: 30px;"></td>';
                               
                               $kontent .=' <td style="text-align: center;width: 30px;">';
                                if($faskes->appr_status==1)
                               	$kontent .=' <span class="fa fa-check-circle"> Disetujui</span>';
                                elseif($faskes->appr_status==2)
                               	$kontent .=' <span class="fa fa-check-circle"> Ditolak</span>';
                                else
                               	$kontent .='  <span class="fa fa-edit"> Pending</span>';
                                       
                                       
                                  
                               	$kontent .='   </td>';
                                   
                                 	$kontent .='            
                                     
                                <td>'.Helper_function::rupiah2(($faskes->jenis==1 or $faskes->jenis==3)?$faskes->nominal:0).'</td>
                                <td>'.Helper_function::rupiah2($faskes->jenis==2?$faskes->nominal:0).'</td>
                                
                                <td>'.Helper_function::rupiah2($nominal).' '.(Auth::user()->role==-1?'<a href="'.route('be.hapus_faskes',$faskes->t_faskes_id).'"><i class="fa fa-trash"></a>':'').'</td>
                            </tr>
                            ';
                            
                       endforeach;
                       if($faskes->tgl_bpjs_kantor and !$bpjs and $faskes->bpjs_kantor){
                        		$nominal = 0;
						    $kontent .='<tr>
						   		<td>'.$no.'</td>
                                <td>'.Helper_function::tgl_indo($faskes->tgl_bpjs_kantor).'</td>
                                <td>Sudah Mempunyai BPJS Dari Kantor</td>
                                <td style="text-align: center;width: 30px;"></td>
                                <td><span class="fa fa-check-circle"> Disetujui</span></td>
                                
                                <td>0</td>
                                <td>0</td>
                                
                                <td>'.Helper_function::rupiah2($nominal).'</td>
						   </tr>'; 
						}
                            
        }
         $return['nominal'] = $nominal;
         $return['kontent'] = $kontent;
        return $return;
    }
    
    public static function historis($page,$keterangan=null)
    {
    	DB::beginTransaction();
        try {
        	
            $iduser = Auth::user()->id;
           	DB::connection()->table("users_historis")
            	->insert([
                        "user_id" => $iduser,
                        "waktu_akses" => date("Y-m-d H:i:s"),
                        "page_akses" => ($page),
                        "keterangan" => $keterangan,
                       
                    ]);
                DB::commit();
           
        } catch (\Exeception $e) {
            DB::rollback();
            return redirect()->back()->with('error', $e);
        }
    	
    }
    
    public static function jabatan_struktural($id_karyawan)
    {
    	$sqljabatan="SELECT * FROM p_karyawan_pekerjaan a  where a.p_karyawan_id = $id_karyawan ";
      	$karyawan=DB::connection()->select($sqljabatan);
      	$id_jabatan = $karyawan[0]->m_jabatan_id;
    	$temp = $id_jabatan;
    	$sqljabatan="SELECT *,(select count(*) from p_karyawan_pekerjaan join p_karyawan on p_karyawan.p_karyawan_id =p_karyawan_pekerjaan.p_karyawan_id where a.m_atasan_id =p_karyawan_pekerjaan.m_jabatan_id and p_karyawan.active=1 ) as total_yg_menjabat FROM m_jabatan_atasan a join m_jabatan on m_jabatan.m_jabatan_id = a.m_atasan_id where a.m_jabatan_id = $id_jabatan ";
      	$jabatan=DB::connection()->select($sqljabatan);
		//  echo $jabatan[0]->m_pangkat_id;
     	$atasan =array();
     	$atasan_langsung = isset($jabatan[0]->m_atasan_id)?$jabatan[0]->m_atasan_id:-1;
     	$id_jabatan = $atasan_langsung;
     	$atasan[] =$id_jabatan;
     	$atasan_string =$id_jabatan.',';
     	$visible = true;
     	$atasan_layer = array();
     	$layer = 1;
     	$direksi = "";
     	while($visible){
     	    if(count($jabatan)){
     	        if($jabatan[0]->m_pangkat_id==6){
     	            $direksi .= "$id_jabatan,";
     	        }
     	        
     	        if(!in_array($jabatan[0]->m_pangkat_id,array(1,2,3,7)) and $jabatan[0]->total_yg_menjabat){
	      	 	    $atasan_layer[$layer] = $id_jabatan;
	      	 	    $layer++;
	      	 	}
	      	 }
	     	 $sqljabatan="SELECT *,(select count(*) from p_karyawan_pekerjaan join p_karyawan on p_karyawan.p_karyawan_id =p_karyawan_pekerjaan.p_karyawan_id where a.m_atasan_id =p_karyawan_pekerjaan.m_jabatan_id and p_karyawan.active=1 ) as total_yg_menjabat
	      	 FROM m_jabatan_atasan a 
	      	 join m_jabatan on m_jabatan.m_jabatan_id = a.m_atasan_id
	      	 where a.m_jabatan_id = $id_jabatan ";
	      	 $jabatan=DB::connection()->select($sqljabatan);
	      	 if(count($jabatan)){
	      	    $id_jabatan = $jabatan[0]->m_atasan_id;
	      	 
     			$atasan_string .=$id_jabatan.',';
     			$atasan[] =$id_jabatan;
	      	 }
	      	 else
	      	 	$visible=false;
     	}
     	 $sqljabatan="SELECT *
      	 	FROM m_jabatan_atasan a  where a.m_atasan_id = $atasan_langsung ";
      	 $jabatan=DB::connection()->select($sqljabatan);
      	 
      	 $sejajar = array();
      	 $sejajar[] = $atasan_langsung;
      	 $sejajar_string = $atasan_langsung.',';
      	 foreach($jabatan as $j){
      	 	$sejajar[] = $j->m_jabatan_id;
      	 	$sejajar_string .= $j->m_jabatan_id.',';
      	 }
      	 $atasan_string .='-1';
      	 $sejajar_string .='-1';
      	 $direksi .='-1';
      	 $bawahan_string = str_replace(',,',',',Helper_function::jabatan_bawahan($temp,$temp,'')).'-1';
      	 $bawahan_string = implode(',',array_unique(explode(',',$bawahan_string)));
      	 $sejajar_string = str_replace(',,',',',$sejajar_string);
      	 $atasan_string = str_replace(',,',',',$atasan_string);
      	 $bawahan_string = str_replace(',,',',',$bawahan_string);
      	 $return['sejajar'] = $sejajar_string;
      	 $return['bawahan'] = $bawahan_string;
      	 $return['atasan'] = $atasan_string;
      	 $return['max_layer'] = $layer;
      	 $return['atasan_layer'] = $atasan_layer;
      	 $return['direksi'] = $direksi;
     	return ($return);
    } 
    
    public static function jabatan_bawahan($idj,$id,$e)
    {
    		//echo 'e adalah'.$e.'id adalah '.$id.'<br>';
		
		
			
      	 $sqljabatan="SELECT *,(select count(*) from m_jabatan_atasan where m_jabatan_atasan.m_atasan_id = a.m_jabatan_id) as countjabatan
      	 FROM m_jabatan_atasan a 
      	left join m_jabatan b on b.m_jabatan_id = a.m_jabatan_id 
      	 where m_atasan_id = $id ";
       $jabatan=DB::connection()->select($sqljabatan);
		  $return = array(); 
      	 
      	 foreach($jabatan as $j){
      	 	$Mjabatan = $j-> m_jabatan_id;
      	 	//echo '<br>'.$j->nama.' '.$j->countjabatan;
      			if($j->countjabatan ){	
      				$e .= $j->m_jabatan_id.','.Helper_function::jabatan_bawahan($idj,$j->m_jabatan_id,$e).',';
				}else{
					$e.=$j->m_jabatan_id.',';
				}
			}
      		
		
		 return $e; 
		
    }
    public static function rupiah($angka, $decimal = 0, $mata_uang = 'Rp ')
    {
        $ex_dec     = explode('.', $angka);
        if (isset($ex_dec[1]))
            $angka = $ex_dec[0];
        $angka = !empty($angka) ? $angka : 0;
        $ex_angka     = explode(' - ', $angka);

        $hasil_rupiah = '';
        for ($i = 0; $i < count($ex_angka); $i++) {
            $hasil_rupiah .= $mata_uang . number_format($ex_angka[$i], $decimal, ',', '.') . 'STRDEC';
            if ($i != count($ex_angka) - 1)
                $hasil_rupiah .= ' - ';
        }
        //echo $hasil_rupiah;
        if ($decimal) {

            if (isset($ex_dec[1]))
                $hasil_rupiah = str_replace('STRDEC', ',' . $ex_dec[1], $hasil_rupiah);
            else
                $hasil_rupiah = str_replace('STRDEC', '', $hasil_rupiah);
        } else
            $hasil_rupiah = str_replace('STRDEC', '', $hasil_rupiah);
        return $hasil_rupiah;
    }
    public static function rupiah2($angka, $decimal = 0, $left = 30, $right = 70)
    {
        $ex_dec     = explode('.', $angka);
        if (isset($ex_dec[1]))
            $angka = $ex_dec[0];
        $angka = !empty($angka) ? $angka : 0;
        $ex_angka     = explode(' - ', $angka);

        $hasil_rupiah = '';
        for ($i = 0; $i < count($ex_angka); $i++) {
            $hasil_rupiah .= "<div style='width:100%;display: inline-flex;'><span class='text-left' style='width: $left%;'>Rp </span><span class='text-right' style='width: $right%;'>" . number_format($ex_angka[$i], $decimal, ',', '.') . 'STRDEC</span></div>';
            if ($i != count($ex_angka) - 1)
                $hasil_rupiah .= ' - ';
        }
        //echo $hasil_rupiah;
        if ($decimal) {

            if (isset($ex_dec[1]))
                $hasil_rupiah = str_replace('STRDEC', ',' . $ex_dec[1], $hasil_rupiah);
            else
                $hasil_rupiah = str_replace('STRDEC', '', $hasil_rupiah);
        } else
            $hasil_rupiah = str_replace('STRDEC', '', $hasil_rupiah);
        return $hasil_rupiah;
    }
    public static function  resize_image($file, $w, $h, $crop=FALSE) {
	    list($width, $height) = getimagesize($file);
	    $r = $width / $height;
	    if ($crop) {
	        if ($width > $height) {
	            $width = ceil($width-($width*abs($r-$w/$h)));
	        } else {
	            $height = ceil($height-($height*abs($r-$w/$h)));
	        }
	        $newwidth = $w;
	        $newheight = $h;
	    } else {
	        if ($w/$h > $r) {
	            $newwidth = $h*$r;
	            $newheight = $h;
	        } else {
	            $newheight = $w/$r;
	            $newwidth = $w;
	        }
	    }
	    $src = imagecreatefromjpeg($file);
	    $dst = imagecreatetruecolor($newwidth, $newheight);
	    imagecopyresampled($dst, $src, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);

	    return $dst;
	}
   
    public static function random($panjang_karakter)
    {
        $karakter      = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
        $string        = '';
        $split         = str_split($karakter);
        $panjang_split = count($split);
        for ($i = 0; $i < $panjang_karakter; $i++) {
            $pos = rand(0, $panjang_split - 1);
            $string .= $split[$pos];
        }
        return $string;
    }
    public static function tanggal_format_from_csv($tgl)
    {
        $tanggal = explode('/', $tgl);
        if (count($tanggal) >= 2) {

            $day = $tanggal[0];
            $bln = $tanggal[1];
            $thn = $tanggal[2];
            return date('Y-m-d', strtotime($thn . '-' . $bln . '-' . $day));
        } else {
            return $tgl;
        }
    }
    public static function hapusRupiah($angka)
    {

        $angka = str_replace('RP', '', $angka);
        $angka = str_replace('Rp', '', $angka);
        $angka = str_replace('.', '', $angka);
        $angka = str_replace(',', '.', $angka);
        $angka = str_replace(' ', '', $angka);
        if (!$angka)
            $angka = 0;
        return ($angka);
    }
    public static function hapusPersen($angka)
    {
        $angka = str_replace('%', '', $angka);
        $angka = str_replace(' ', '', $angka);
        $angka = str_replace(',', '.', $angka);
        if (!$angka)
            $angka = 0;
        return ($angka);
    }
    public static function rupiah_asa_standar($angka, $decimal = 0)
    {
        $ex_dec     = explode('.', $angka);
        if (isset($ex_dec[1]))
            $angka = $ex_dec[0];
        $angka = !empty($angka) ? $angka : 0;
        $ex_angka     = explode(' - ', $angka);

        $hasil_rupiah = '';
        for ($i = 0; $i < count($ex_angka); $i++) {
            $hasil_rupiah .=  number_format($ex_angka[$i], $decimal, '.', ',');
            if ($i != count($ex_angka) - 1)
                $hasil_rupiah .= ' - ';
        }
        //echo $hasil_rupiah;
        if (isset($ex_dec[1]))
            $hasil_rupiah = $hasil_rupiah . '.' . $ex_dec[1];
        return $hasil_rupiah;
    }
    public static function rupiah_tikom($angka, $decimal = 0)
    {

        $hasil_rupiah = Helper_function::rupiah($angka, $decimal);
        return $hasil_rupiah . ',-';
    }
    public static function tambah_tanggal($tanggal, $jumlah_hari)
    {
        $tgl1 = $tanggal; // pendefinisian tanggal awal
        //echo $jumlah_hari;
        $tgl2 = date('Y-m-d', strtotime('+' . $jumlah_hari . ' days', strtotime($tgl1))); //operasi penjumlahan tanggal sebanyak 6 hari
        return $tgl2;
    }
    public static function penyebut($nilai)
    {
        $nilai = abs($nilai);
        $huruf = array("", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
        $temp = "";
        if ($nilai < 12) {
            $temp = " " . $huruf[$nilai];
        } else if ($nilai < 20) {
            $temp = Helper_function::penyebut($nilai - 10) . " belas";
        } else if ($nilai < 100) {
            $temp = Helper_function::penyebut($nilai / 10) . " puluh" . Helper_function::penyebut($nilai % 10);
        } else if ($nilai < 200) {
            $temp = " seratus" . Helper_function::penyebut($nilai - 100);
        } else if ($nilai < 1000) {
            $temp = Helper_function::penyebut($nilai / 100) . " ratus" . Helper_function::penyebut($nilai % 100);
        } else if ($nilai < 2000) {
            $temp = " seribu" . Helper_function::penyebut($nilai - 1000);
        } else if ($nilai < 1000000) {
            $temp = Helper_function::penyebut($nilai / 1000) . " ribu" . Helper_function::penyebut($nilai % 1000);
        } else if ($nilai < 1000000000) {
            $temp = Helper_function::penyebut($nilai / 1000000) . " juta" . Helper_function::penyebut($nilai % 1000000);
        } else if ($nilai < 1000000000000) {
            $temp = Helper_function::penyebut($nilai / 1000000000) . " milyar" . Helper_function::penyebut(fmod($nilai, 1000000000));
        } else if ($nilai < 1000000000000000) {
            $temp = Helper_function::penyebut($nilai / 1000000000000) . " trilyun" . Helper_function::penyebut(fmod($nilai, 1000000000000));
        }
        return $temp;
    }
    public static function terbilang($nilai)
    {
        if ($nilai < 0) {
            $hasil = "minus " . trim(Helper_function::penyebut($nilai));
        } else {
            $hasil = trim(Helper_function::penyebut($nilai));
        }
        return ucwords($hasil . ' rupiah');
    }
    public static function toAlpha($number)
    {
        $alphabet =   array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');
        $alpha_flip = array_flip($alphabet);
        if ($number <= 25) {
            return $alphabet[$number];
        } elseif ($number > 25) {
            $dividend = ($number + 1);
            $alpha = '';
            $modulo;
            while ($dividend > 0) {
                $modulo = ($dividend - 1) % 26;
                $alpha = $alphabet[$modulo] . $alpha;
                $dividend = floor((($dividend - $modulo) / 26));
            }
            return $alpha;
        }
    }

   public static function tgl_appr($tgl, $rekap, $p_karyawan_id)
    {

        if (isset($rekap[$p_karyawan_id][$tgl]['tipe_lembur'])) {



            Helper_function::tgl_appr(Helper_function::tambah_tanggal($tgl, -1), $rekap, $p_karyawan_id);
        } else {
            return $tgl;
        }
    }
    public static
    function hitungHari($awal, $akhir)
    {
        $awal    = date("Y-m-d", strtotime($awal));
        $akhir   = date("Y-m-d", strtotime($akhir));
        //$akhir = date_format($akhir,"Y - m - d");

        $tglAwal = strtotime($awal);
        $tglAkhir = strtotime($akhir);
        $jeda    = abs($tglAkhir - $tglAwal);
        return floor($jeda / (60 * 60 * 24));
    }
    public static
    function hitungHarinoabs($awal, $akhir)
    {
        $awal    = date("Y-m-d", strtotime($awal));
        $akhir   = date("Y-m-d", strtotime($akhir));
        //$akhir = date_format($akhir,"Y - m - d");

        $tglAwal = strtotime($awal);
        $tglAkhir = strtotime($akhir);
        $jeda    = ($tglAkhir - $tglAwal);
        return floor($jeda / (60 * 60 * 24));
    }
    public static function hitungBulan($awal, $akhir)
    {

        $timeStart = strtotime($awal);
        $timeEnd   = strtotime($akhir);
        // Menambah bulan ini + semua bulan pada tahun sebelumnya
        $numBulan  = 1 + (date("Y", $timeEnd) - date("Y", $timeStart)) * 12;
        // menghitung selisih bulan
        $numBulan += date("m", $timeEnd) - date("m", $timeStart);

        return $numBulan;
    }
    public static
    function tambah_bulan($tgl1, $jumlah)
    {
        // pendefinisian tanggal awal
        $tgl2 = date('Y-m-d', strtotime('+' . $jumlah . ' months', strtotime($tgl1))); //operasi penjumlahan tanggal sebanyak 6 hari
        return $tgl2;
    }
    public static function format_tgl($tanggal, $format)
    {
        return date($format, strtotime($tanggal));
        //return date_format(date_create($tanggal,$format));
    }
    public static function pecah($tanggal)
    {
        $ubah      = gmdate($tanggal, time() + 60 * 60 * 8);
        $pecah     = explode("-", $ubah);
        return $pecah;
    }
    public static function tgl_bulan($tanggal)
    {
        $ubah      = gmdate($tanggal, time() + 60 * 60 * 8);
        $pecah     = explode("-", $ubah);
        $tgl       = $pecah[2];
        $bln       = $pecah[1];
        return Helper_function::bulan($bln);
    }
    public static function nama_hari($tanggal)
    {
        if($tanggal){
        $ubah      = gmdate($tanggal, time() + 60 * 60 * 8);
        $pecah     = explode("-", $ubah);
        $tgl       = $pecah[2];
        $bln       = $pecah[1];
        $thn       = $pecah[0];

        $nama      = date("l", mktime(0, 0, 0, $bln, $tgl, $thn));
        $nama_hari = "";
        if ($nama == "Sunday") {
            $nama_hari = "Minggu";
        } else if ($nama == "Monday") {
            $nama_hari = "Senin";
        } else if ($nama == "Tuesday") {
            $nama_hari = "Selasa";
        } else if ($nama == "Wednesday") {
            $nama_hari = "Rabu";
        } else if ($nama == "Thursday") {
            $nama_hari = "Kamis";
        } else if ($nama == "Friday") {
            $nama_hari = "Jumat";
        } else if ($nama == "Saturday") {
            $nama_hari = "Sabtu";
        }
        return $nama_hari;
        }else
        return null;
    }
    public static function  tgl_indo($tgl)
    {
    	
        if ($tgl) { 
        $tgl = date('Y-m-d', strtotime($tgl));
        $ubah   = gmdate($tgl, time() + 60 * 60 * 8);
        $pecah  = explode(" ", $ubah);
        $pecah2 = explode("-", $pecah[0]);

            $tanggal = $pecah2[2];
            $bulan  = Helper_function::bulan($pecah2[1]);
            $tahun  = $pecah2[0];
            return $tanggal . ' ' . $bulan . ' ' . $tahun;
        } else {
            return '';
        }
    }
    public static function  tgl_indo_short($tgl)
    {
        $tgl = date('Y-m-d', strtotime($tgl));
        $ubah   = gmdate($tgl, time() + 60 * 60 * 8);
        $pecah  = explode(" ", $ubah);
        $pecah2 = explode("-", $pecah[0]);
        $tanggal = $pecah2[2];
        $bulan  = Helper_function::bulan_short($pecah2[1]);
        $tahun  = $pecah2[0];
        return $tanggal . ' ' . $bulan . ' ' . $tahun;
    }
    public static function  tgl_indo_short_no_tahun($tgl)
    {

        $ubah   = gmdate($tgl, time() + 60 * 60 * 8);
        $pecah  = explode(" ", $ubah);
        $pecah2 = explode("-", $pecah[0]);
        $tanggal = $pecah2[2];
        $bulan  = Helper_function::bulan_short($pecah2[1]);
        $tahun  = $pecah2[0];
        return $tanggal . ' ' . $bulan;
    }
    public static function  selisih_tanggal($tanggal_awal,$tanggal_akhir=NULL,$result = 'full')
    {
    	$awal  = date_create($tanggal_awal);
    	if($tanggal_akhir)
    	$akhir  = date_create($tanggal_akhir);
    	else 
		$akhir = date_create(); // waktu sekarang
		$diff  = date_diff( $awal, $akhir );
		if($result=='full'){
			
		
		$return =  $diff->y . ' tahun  ';
		$return .=  $diff->m . ' bulan  ';
		$return .= $diff->d . ' hari  ';
		return $return;
		}else{
			return $diff;
		}
    }
    public static function  bulan($bln)
    {
        switch ($bln) {
            case 1:
                return "Januari";
                break;
            case 2:
                return "Februari";
                break;
            case 3:
                return "Maret";
                break;
            case 4:
                return "April";
                break;
            case 5:
                return "Mei";
                break;
            case 6:
                return "Juni";
                break;
            case 7:
                return "Juli";
                break;
            case 8:
                return "Agustus";
                break;
            case 9:
                return "September";
                break;
            case 10:
                return "Oktober";
                break;
            case 11:
                return "November";
                break;
            case 12:
                return "Desember";
                break;
        }
    }
    public static function bulan_short($bln)
    {
        switch ($bln) {
            case 1:
                return "Jan";
                break;
            case 2:
                return "Feb";
                break;
            case 3:
                return "Mar";
                break;
            case 4:
                return "Apr";
                break;
            case 5:
                return "Mei";
                break;
            case 6:
                return "Jun";
                break;
            case 7:
                return "Jul";
                break;
            case 8:
                return "Agu";
                break;
            case 9:
                return "Sep";
                break;
            case 10:
                return "Okt";
                break;
            case 11:
                return "Nov";
                break;
            case 12:
                return " Des";
                break;
        }
    }
}

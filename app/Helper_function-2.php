<?php

namespace App;

use Illuminate\Http\Request;
use DB;
use Auth;

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
    public static function rekap_absen($tgl_awal, $tgl_akhir, $tgl_awal_lembur, $tgl_akhir_lembur, $type, $user = null, $id_karyawan_search = null, $get = null)
    {
        $request = new Request;

        $iduser = Auth::user()->id;
        $sqluser = "SELECT p_recruitment.foto,role,p_karyawan_pekerjaan,p_karyawan.p_karyawan_id,p_karyawan_pekerjaan.m_lokasi_id,user_entitas_access FROM users
		left join m_role on m_role.m_role_id=users.role
		left join p_karyawan on p_karyawan.user_id=users.id
		left join p_karyawan_pekerjaan on p_karyawan_pekerjaan.p_karyawan_id=p_karyawan.p_karyawan_id
		left join p_recruitment on p_recruitment.p_recruitment_id=p_karyawan.p_recruitment_id
		where users.id=$iduser";
        $user = DB::connection()->select($sqluser);
        $id_karyawan = $user[0]->p_karyawan_id;
        $bawahan = "((select p_karyawan_pekerjaan.p_karyawan_id from p_karyawan_pekerjaan where m_jabatan_id in (select m_jabatan_terkait from m_jabatan_struktural where tipe_struktural=2 and m_jabatan_id = (select m_jabatan_id from p_karyawan_pekerjaan pkp where p_karyawan_id = $id_karyawan))))";
        $wherebawahan = '';
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
            $wherebawahan = "and c.p_karyawan_id in ($bawahan)";
        }
        $where_karyawan = '';
        if ($id_karyawan_search)
            $where_karyawan =  ' and c.p_karyawan_id =' . $id_karyawan_search;
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
        $sqlabsen = "

select * , case
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

		$where_karyawan
		$wherebawahan
		order by date_time desc
		";
        $help = new Helper_function();
        //echo $sqlabsen; die;
        $rekap = array();
        $rekap[$id_karyawan]['total']['ijin_libur']=0;
        $absen = DB::connection()->select($sqlabsen);
        foreach ($absen as $absen) {
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
            }
        }
        $sqllembur = "Select m_jenis_ijin.nama as nama_ijin,m_jenis_ijin.tipe,c.m_jenis_ijin_id,c.lama,c.keterangan,c.p_karyawan_id,c.tgl_awal,c.tgl_akhir,c.jam_awal,c.status_appr_hr,a.periode_gajian,status_appr_1,m_jenis_ijin.kode as string_kode_ijin
		from t_permit c
		left join m_jenis_ijin on m_jenis_ijin.m_jenis_ijin_id = c.m_jenis_ijin_id
		left join p_karyawan_pekerjaan a on c.p_karyawan_id = a.p_karyawan_id 
		where ((c.tgl_awal>='$tgl_awal' and c.tgl_awal<='$tgl_akhir 23:59') or
		(c.tgl_akhir>='$tgl_awal' and c.tgl_akhir>='$tgl_akhir 23:59') or
		(c.tgl_akhir>='$tgl_awal' and c.tgl_akhir<='$tgl_akhir 23:59') or
		(c.tgl_awal<='$tgl_awal' and c.tgl_akhir<='$tgl_awal 23:59' and c.tgl_akhir is not null)) and
		c.m_jenis_ijin_id != 22
		$where_karyawan and c.active=1
		$wherebawahan
		ORDER BY c.p_karyawan_id asc ";
        $lembur = DB::connection()->select($sqllembur);
        $list_permit = array();
        $karyawan = array();
       
        /*((c.status_appr_1 = 1 and c.m_jenis_ijin_id not in (20,21,26))
or (c.status_appr_hr = 1 and c.m_jenis_ijin_id in (20,21,26)) )*/
        //var_dump($lembur); die;
        
        //echo '<pre>';print_r($lembur);echo '</pre>';die;
        foreach ($lembur as $lembur) {
        	$date = $lembur->tgl_awal;
        	if($lembur->status_appr_1==1){
        		
            if (!in_array($lembur->p_karyawan_id, $karyawan))
                $karyawan[] = $lembur->p_karyawan_id;
            if ($lembur->status_appr_hr != 2) {
                $date = $lembur->tgl_awal;
                if (!$lembur->tgl_akhir)
                    $lembur->tgl_akhir = $lembur->tgl_awal;
                for ($i = 0; $i <= Helper_function::hitunghari($lembur->tgl_awal, $lembur->tgl_akhir); $i++) {
                    if (in_array(Helper_function::nama_hari($date), array('Minggu', 'Sabtu')) and in_array($date, $hari_libur) and isset($hari_libur_shift[$date][$id_karyawan])) {
                    	if(isset($rekap[$lembur->p_karyawan_id]['total']['ijin_libur']))
                        $rekap[$lembur->p_karyawan_id]['total']['ijin_libur'] += 1;
                    	else
                        $rekap[$lembur->p_karyawan_id]['total']['ijin_libur'] = 1;
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
	                   
					}
                    $date = Helper_function::tambah_tanggal($date, 1);
                }
            }
        	}else{
        		$date = $lembur->tgl_awal;
        		$rekap['pending_pengajuan'][$lembur->p_karyawan_id][$date]['nama_izin'] = $lembur->nama_ijin;
        		$rekap['pending_pengajuan'][$lembur->p_karyawan_id][$date]['status'] = $lembur->status_appr_1;
        	}
        }
       
       	$where_periode_gajian ="";
         if ($type != -1) {

                $where_periode_gajian = "and d.periode_gajian = " . $type;
             
            }
        $sqllembur = "Select c.*,m_jenis_ijin.kode as string_kode_ijin 

		from t_permit  c
		left join m_jenis_ijin on m_jenis_ijin.m_jenis_ijin_id = c.m_jenis_ijin_id
		
		join p_karyawan_pekerjaan d on c.p_karyawan_id = d.p_karyawan_id
		where 
	(case
	WHEN status_appr_1=1 and appr_2 is null THEN tgl_appr_1>='$tgl_awal_lembur' and  tgl_appr_1<='$tgl_akhir_lembur'
		WHEN status_appr_1=1 and status_appr_2=1 THEN tgl_appr_2>='$tgl_awal_lembur' and  tgl_appr_2<='$tgl_akhir_lembur'
		WHEN appr_1 is null and status_appr_2=1 THEN tgl_appr_2>='$tgl_awal_lembur' and  tgl_appr_2<='$tgl_akhir_lembur'
		end)
		and c.m_jenis_ijin_id = 22
		$where_karyawan
		and c.active=1
		$where_periode_gajian
		$wherebawahan
		
		
		ORDER BY c.p_karyawan_id asc";
        $lembur = DB::connection()->select($sqllembur);
        //$karyawan = array();
        //$rekap = array();

        //echo '<pre>';print_r($lembur); echo '</pre>';die;
        $array_tgl = array();
        $tgl_awal_lembur_ajuan = $tgl_awal_lembur;
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

                $rekap[$lembur->p_karyawan_id][$tgl_appr]['tipe_lembur'] = $lembur->tipe_lembur;
                $rekap[$lembur->p_karyawan_id][$tgl_appr]['tgl_awal'] = $lembur->tgl_awal;
                $rekap[$lembur->p_karyawan_id][$tgl_appr]['tgl_akhir'] = $lembur->tgl_akhir;
                
                
                $rekap[$lembur->p_karyawan_id][$lembur->tgl_awal]['ajuan']['tipe_lembur'] = $lembur->tipe_lembur;
                $rekap[$lembur->p_karyawan_id][$lembur->tgl_awal]['ajuan']['tgl_awal'] = $lembur->tgl_awal;
                $rekap[$lembur->p_karyawan_id][$lembur->tgl_awal]['ajuan']['tgl_akhir'] = $lembur->tgl_akhir;
                
                
                if(!isset( $rekap[$lembur->p_karyawan_id][$tgl_appr]['lama'])){
                
	                $rekap[$lembur->p_karyawan_id][$tgl_appr]['lama'] = $lembur->lama;
	                $rekap[$lembur->p_karyawan_id][$tgl_appr]['keterangan'] = $lembur->keterangan;
	                $rekap[$lembur->p_karyawan_id][$tgl_appr]['jam_awal'] = $lembur->jam_awal;
	                $rekap[$lembur->p_karyawan_id][$tgl_appr]['jam_akhir'] = $lembur->jam_akhir;
	                $rekap[$lembur->p_karyawan_id][$tgl_appr]['total_ajuan'] = 1;
	                
					$rekap[$lembur->p_karyawan_id][$lembur->tgl_awal]['ajuan']['lama'] = $lembur->lama;
	                $rekap[$lembur->p_karyawan_id][$lembur->tgl_awal]['ajuan']['keterangan'] = $lembur->keterangan;
	                $rekap[$lembur->p_karyawan_id][$lembur->tgl_awal]['ajuan']['jam_awal'] = $lembur->jam_awal;
	                $rekap[$lembur->p_karyawan_id][$lembur->tgl_awal]['ajuan']['jam_akhir'] = $lembur->jam_akhir;
	                $rekap[$lembur->p_karyawan_id][$lembur->tgl_awal]['ajuan']['total_ajuan'] = 1;
	                
                }else{
                	
	                $rekap[$lembur->p_karyawan_id][$tgl_appr]['total_ajuan'] += 1;
	                $rekap[$lembur->p_karyawan_id][$lembur->tgl_awal]['ajuan']['total_ajuan'] += 1;
	            
	                $rekap[$lembur->p_karyawan_id][$tgl_appr]['lama'] += $lembur->lama;
	                $rekap[$lembur->p_karyawan_id][$tgl_appr]['keterangan'] = '<br><b style="font-weight:700">'.
	                			$rekap[$lembur->p_karyawan_id][$lembur->tgl_awal]['ajuan']['jam_awal'].'</b><br>'.
	                			$rekap[$lembur->p_karyawan_id][$lembur->tgl_awal]['ajuan']['keterangan']
	                			
	                			.'  |  '
	                			.'<b style="font-weight:700">'.$lembur->jam_awal.'</b><br>'.$lembur->keterangan;	 
	                $rekap[$lembur->p_karyawan_id][$tgl_appr]['jam_awal'] = $rekap[$lembur->p_karyawan_id][$tgl_appr]['jam_awal'].' s/d '.$rekap[$lembur->p_karyawan_id][$tgl_appr]['jam_akhir'].' | '.$lembur->jam_awal.' s/d '.$lembur->jam_akhir;
	                $rekap[$lembur->p_karyawan_id][$tgl_appr]['jam_akhir'] = '';
	                
					$rekap[$lembur->p_karyawan_id][$lembur->tgl_awal]['ajuan']['lama'] += $lembur->lama;
	                $rekap[$lembur->p_karyawan_id][$lembur->tgl_awal]['ajuan']['keterangan']= '<br><b style="font-weight:700">'.
	                			$rekap[$lembur->p_karyawan_id][$lembur->tgl_awal]['ajuan']['jam_awal'].'</b><br>'.
	                			$rekap[$lembur->p_karyawan_id][$lembur->tgl_awal]['ajuan']['keterangan']
	                			
	                			.'  |  '
	                			.'<b style="font-weight:700">'.$lembur->jam_awal.'</b><br>'.$lembur->keterangan;
	                $rekap[$lembur->p_karyawan_id][$lembur->tgl_awal]['ajuan']['jam_awal'] =$rekap[$lembur->p_karyawan_id][$tgl_appr]['jam_awal'].' s/d '.$rekap[$lembur->p_karyawan_id][$tgl_appr]['jam_akhir'].' | '.$lembur->jam_awal.' s/d '.$lembur->jam_akhir;
	                $rekap[$lembur->p_karyawan_id][$lembur->tgl_awal]['ajuan']['jam_akhir'] ='';

                }
                
                ;	
                		
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
        
         $countklari        = DB::connection()->select("select * from chat_room where  tanggal >= '$tgl_awal' and  tanggal<='$tgl_akhir'");
         foreach($countklari as $klarifi){
         	$rekap['klarifikasi'][$klarifi->p_karyawan_create_id][$klarifi->tanggal]['topik'] = $klarifi->topik;
         	$rekap['klarifikasi'][$klarifi->p_karyawan_create_id][$klarifi->tanggal]['deskripsi'] = $klarifi->deskripsi;
         }
                      
        
        
        
        //print_r($array_tgl);
        if (count($array_tgl))
            $min = min($array_tgl) > $tgl_awal_lembur ? $tgl_awal_lembur : min($array_tgl);
        else
            $min = $tgl_awal_lembur;


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
		----
		$where_karyawan
		$wherebawahan
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
        $rekap['tgl_awal_lembur']     = $tgl_awal_lembur_ajuan;
        $rekap['tgl_akhir_lembur']     = $tgl_akhir_lembur;
        $rekap['list_permit']         = $list_permit;
        $rekap['mesin']             = $mesin;
        $rekap['tanggallibur']         = $tanggallibur;

        return $rekap;
    }
    public static
    function total_rekap_absen($rekap, $id_karyawan, $type = "rekap", $sheet = null, $rows = 0)
    {
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
        $info_karyawan         = DB::connection()->select("select * ,p_karyawan.nama as p_karyawan_nama , is_shift as is_karyawan_shift
								from p_karyawan
								left join p_karyawan_absen on p_karyawan.p_karyawan_id = p_karyawan_absen.p_karyawan_id
								left join p_karyawan_pekerjaan on p_karyawan.p_karyawan_id = p_karyawan_pekerjaan.p_karyawan_id
								left join p_karyawan_kontrak on p_karyawan.p_karyawan_id = p_karyawan_kontrak.p_karyawan_id and p_karyawan_kontrak.active=1
								left join m_jabatan on m_jabatan.m_jabatan_id = p_karyawan_pekerjaan.m_jabatan_id
								where p_karyawan.p_karyawan_id = $id_karyawan");
								
		$id = $id_karyawan;
		$ipg_cutber         = DB::connection()->select("select * from m_hari_libur_cuti_ipg where tanggal >='$tgl_awal' and tanggal<='$tgl_akhir' and p_karyawan_id = $id_karyawan and active=1");
		//print_r($ipg_cutber);
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

        $rekap[$id_karyawan]['total']['cuti'] = 0;

        $rekap[$id_karyawan]['total']['ipd'] = 0;
        $rekap[$id_karyawan]['total']['ihk'] = 0;
        $rekap[$id_karyawan]['total']['ihk'] = 0;
        $rekap[$id_karyawan]['total']['ipg'] = 0;
        $rekap[$id_karyawan]['total']['ipc'] = 0;
        $rekap[$id_karyawan]['total']['idt'] = 0;
        $rekap[$id_karyawan]['total']['ipm'] = 0;
        $rekap[$id_karyawan]['total']['sakit'] = 0;
        $rekap[$id_karyawan]['total']['alpha'] = 0;
        $rekap[$id_karyawan]['total']['pm'] = 0;
        $rekap[$id_karyawan]['total']['terlambat'] = 0;
        $rekap[$id_karyawan]['total']['fingerprint'] = 0;
        $rekap[$id_karyawan]['total']['absen_masuk'] = 0;
        $rekap[$id_karyawan]['total']['alphaList'] = '';
        $rekap[$id_karyawan]['total']['IPGCuti'] = 0;
        $rekap[$id_karyawan]['total']['IPGCutiList'] = '';
        $rekap[$id_karyawan]['total']['masuk_libur'] = 0;
        $rekap[$id_karyawan]['total']['ijin_libur'] = 0;
        $rekap[$id_karyawan]['total']['hari_bulan'] = 0;
        $rekap[$id_karyawan]['total']['hari_kerja'] = 0;
        
        $all_content = '';
        $all_content_cek_absen = '';
        $no = 0;
        $warna_sheet = array();
        
        /*
        $rekap['perganitan_hari_libur_awal'][$pengganti_hari->p_karyawan_id][]=$pengganti_hari->tgl_pengganti_hari;
			$rekap['perganitan_hari_libur_ke'][$pengganti_hari->p_karyawan_id][]=$pengganti_hari->tgl_pengajuan;
			*/
            if(isset($hari_libur_shift)){
               
                //print_r($hari_libur_shift);
            }
        for ($i = 0; $i <= Helper_function::hitunghari($tgl_awal, $tgl_akhir); $i++) {

            $rekap[$id_karyawan]['total']['hari_bulan'] += 1;
            if (!$info_karyawan[0]->is_karyawan_shift){
            	
                $bool_hari_libur = !(
                			in_array(Helper_function::nama_hari($date), array('Minggu', 'Sabtu')) 
                			or in_array($date, $hari_libur) 
                			or (isset($hari_libur_shift[$date][$id_karyawan]))
                		) and !(in_array($date,$potong_gaji));
               //or !isset($rekap['perganitan_hari_libur_awal'][$id_karyawan][$date])
	          //      			or isset($rekap['perganitan_hari_libur_ke'][$id_karyawan][$date])
                if(isset($rekap['perganitan_hari_libur_awal'][$id_karyawan][$date]) ){
					$bool_hari_libur = false;
				}
                if(isset($rekap['perganitan_hari_libur_ke'][$id_karyawan][$date]) ){
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
				$rekap[$id_karyawan]['total']['ipg'] += 1;
				
		        $rekap[$id_karyawan]['total']['IPGCuti'] += 1;
		        $rekap[$id_karyawan]['total']['IPGCutiList'] .= $date.'|';
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
			
            if (!isset($rekap[$id_karyawan][$date]['a']['masuk']) and isset($rekap[$id_karyawan][$date]['a']['keluar']) and $bool_hari_libur) {
                if (isset($rekap[$id_karyawan][$date]['ci']['nama_ijin'])) {
                    if ($rekap[$id_karyawan][$date]['ci']['m_jenis_ijin_id'] != 24) {
                        $warna = 'darkgray';
                        $warna_sheet[$date] = 'A9A9A9';
                        $rekap[$id_karyawan]['total']['fingerprint'] += 1;
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
                    $rekap[$id_karyawan]['total']['fingerprint'] += 1;
                     $status_absen = 'TIDAK OK<br>';
                     $string_jenis_ijin='TANPA FINGERPRINT';
			                if(isset($rekap[$id_karyawan]['string'][$string_jenis_ijin])){
				                    		$rekap[$id_karyawan]['string'][$string_jenis_ijin] .= " ". $date.' | ';
				            }else if(!isset($rekap[$id_karyawan]['string'][$string_jenis_ijin])){
				                    		$rekap[$id_karyawan]['string'][$string_jenis_ijin] = $date.' | ';
							}
                }
            } else if (isset($rekap[$id_karyawan][$date]['a']['masuk']) and isset($rekap[$id_karyawan][$date]['a']['keluar']) and $bool_hari_libur) {
                if ($rekap[$id_karyawan][$date]['a']['keluar'] < $rekap[$id_karyawan][$date]['a']['jam_keluar']) {

                    if (isset($rekap[$id_karyawan][$date]['ci']['m_jenis_ijin_id'])) {
                        if ($rekap[$id_karyawan][$date]['ci']['m_jenis_ijin_id'] != 26) {

                            $rekap[$id_karyawan]['total']['pm'] += 1;
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
                        $rekap[$id_karyawan]['total']['pm'] += 1;
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


            
            if ($bool_hari_libur)
                $rekap[$id_karyawan]['total']['hari_kerja'] += 1;
            else
                $status_libur = 'Hari Libur';
			
            if (isset($rekap[$id_karyawan][$date]['a']['masuk'])) {
                $content_sheet[$date] .= $rekap[$id_karyawan][$date]['a']['masuk'];
                $content .= ' ' . $rekap[$id_karyawan][$date]['a']['masuk'];
                $rekap[$id_karyawan]['total']['absen_masuk'] += 1;

                if ($rekap[$id_karyawan][$date]['a']['masuk'] > $rekap[$id_karyawan][$date]['a']['jam_masuk'] and  $bool_hari_libur) {
                    if ($info_karyawan[0]->m_pangkat_id == 5 or $info_karyawan[0]->m_pangkat_id == 6  ) {
                    } else if ((($info_karyawan[0]->p_karyawan_id == 269 or $info_karyawan[0]->p_karyawan_id == 1) and $date>= '2022-12-26') or ($info_karyawan[0]->m_lokasi_id==5)) {
                    } else if (isset($rekap[$id_karyawan][$date]['ci']['m_jenis_ijin_id'])) {
                        if ($rekap[$id_karyawan][$date]['ci']['m_jenis_ijin_id'] == 21 or $rekap[$id_karyawan][$date]['ci']['m_jenis_ijin_id'] == 24 ) {
                        } else {
                            $status_absen = 'TERLAMBAT<br>';
                            $rekap[$id_karyawan][$date]['a']['terlambat'] = 1;

                            $rekap[$id_karyawan]['total']['terlambat'] += 1;
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
                        $rekap[$id_karyawan][$date]['a']['terlambat'] = 1;

                        $rekap[$id_karyawan]['total']['terlambat'] += 1;
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
                if (!$bool_hari_libur)
                    $rekap[$id_karyawan]['total']['masuk_libur'] += 1;
            }


            if (isset($rekap[$id_karyawan][$date]['a']['keluar'])) {

                $content .= '
				s/d  ' . $rekap[$id_karyawan][$date]['a']['keluar'] . '
				';
                $content_sheet[$date] .= '
				s/d  ' . $rekap[$id_karyawan][$date]['a']['keluar'] . '
				';
            }
            if (isset($rekap[$id_karyawan][$date]['ci']['nama_ijin'])) {
                 $status_absen = 'OK<br>';
				 $status_libur  =  $rekap[$id_karyawan][$date]['ci']['nama_ijin'];
                if ($bool_hari_libur) {
                    if (in_array($rekap[$id_karyawan][$date]['ci']['m_jenis_ijin_id'], array(4, 7, 12, 13, 14, 15, 16, 17))) {
                        $rekap[$id_karyawan]['total']['ihk'] += 1;
                        $warna = '#fb0b7b';
                        $warna_sheet[$date] = 'fb0b7b';
                        
                        
                         $string_jenis_ijin='IHK';
			                if(isset($rekap[$id_karyawan]['string'][$string_jenis_ijin])){
				                    		$rekap[$id_karyawan]['string'][$string_jenis_ijin] .= " ". $date.' | ';
				            }else if(!isset($rekap[$id_karyawan]['string'][$string_jenis_ijin])){
				                    		$rekap[$id_karyawan]['string'][$string_jenis_ijin] = $date.' | ';
							}
                    } else if ($rekap[$id_karyawan][$date]['ci']['m_jenis_ijin_id'] == 1) {
                        $rekap[$id_karyawan]['total']['ipg'] += 1;
                        $warna = 'blue';
                        $warna_sheet[$date] = '0000FF';
                        
                        $string_jenis_ijin='IPG';
			                if(isset($rekap[$id_karyawan]['string'][$string_jenis_ijin])){
				                    		$rekap[$id_karyawan]['string'][$string_jenis_ijin] .= " ". $date.' | ';
				            }else if(!isset($rekap[$id_karyawan]['string'][$string_jenis_ijin])){
				                    		$rekap[$id_karyawan]['string'][$string_jenis_ijin] = $date.' | ';
							}
                    } else if ($rekap[$id_karyawan][$date]['ci']['m_jenis_ijin_id'] == 20) {
                        $rekap[$id_karyawan]['total']['sakit'] += 1;
                        $warna = 'darkcyan';
                        $warna_sheet[$date] = '008B8B';
                        $string_jenis_ijin='SAKIT';
			                if(isset($rekap[$id_karyawan]['string'][$string_jenis_ijin])){
				                    		$rekap[$id_karyawan]['string'][$string_jenis_ijin] .= " ". $date.' | ';
				            }else if(!isset($rekap[$id_karyawan]['string'][$string_jenis_ijin])){
				                    		$rekap[$id_karyawan]['string'][$string_jenis_ijin] = $date.' | ';
							}
                    } else if ($rekap[$id_karyawan][$date]['ci']['m_jenis_ijin_id'] == 25) {
                        $rekap[$id_karyawan]['total']['ipc'] += 1;
                        $warna = 'teal';
                        $warna_sheet[$date] = '008080';
                        $string_jenis_ijin='IPC';
			                if(isset($rekap[$id_karyawan]['string'][$string_jenis_ijin])){
				                    		$rekap[$id_karyawan]['string'][$string_jenis_ijin] .= " ". $date.' | ';
				            }else if(!isset($rekap[$id_karyawan]['string'][$string_jenis_ijin])){
				                    		$rekap[$id_karyawan]['string'][$string_jenis_ijin] = $date.' | ';
							}
                    } else if ($rekap[$id_karyawan][$date]['ci']['tipe'] == 3) {
                        $rekap[$id_karyawan]['total']['cuti'] += 1;
                        $warna = 'green';
                        $warna_sheet[$date] = '008000';
                        $string_jenis_ijin='CUTI';
			                if(isset($rekap[$id_karyawan]['string'][$string_jenis_ijin])){
				                    		$rekap[$id_karyawan]['string'][$string_jenis_ijin] .= " ". $date.' | ';
				            }else if(!isset($rekap[$id_karyawan]['string'][$string_jenis_ijin])){
				                    		$rekap[$id_karyawan]['string'][$string_jenis_ijin] = $date.' | ';
							}
                    } else if ($rekap[$id_karyawan][$date]['ci']['m_jenis_ijin_id'] == 21) {
                        $warna = 'brown';
                        $warna_sheet[$date] = 'D2691E';
                        $rekap[$id_karyawan]['total']['idt'] += 1;
                        $string_jenis_ijin='IDT';
			                if(isset($rekap[$id_karyawan]['string'][$string_jenis_ijin])){
				                    		$rekap[$id_karyawan]['string'][$string_jenis_ijin] .= " ". $date.' | ';
				            }else if(!isset($rekap[$id_karyawan]['string'][$string_jenis_ijin])){
				                    		$rekap[$id_karyawan]['string'][$string_jenis_ijin] = $date.' | ';
							}
                    } else if ($rekap[$id_karyawan][$date]['ci']['m_jenis_ijin_id'] == 26) {
                        $warna = 'chocolate';
                        $warna_sheet[$date] = 'D2691E';
                        $rekap[$id_karyawan]['total']['ipm'] += 1;
                        $string_jenis_ijin='IPM';
			                if(isset($rekap[$id_karyawan]['string'][$string_jenis_ijin])){
				                    		$rekap[$id_karyawan]['string'][$string_jenis_ijin] .= " ". $date.' | ';
				            }else if(!isset($rekap[$id_karyawan]['string'][$string_jenis_ijin])){
				                    		$rekap[$id_karyawan]['string'][$string_jenis_ijin] = $date.' | ';
							}
                    }

                    //if($rekap[$id_karyawan][$date]['ci']['nama_ijin'] != 'IZIN DATANG TERLAMBAT')

                    $content .= ' ' . $rekap[$id_karyawan][$date]['ci']['nama_ijin'] . '								';
                    $content_sheet[$date] .= ' ' . $rekap[$id_karyawan][$date]['ci']['nama_ijin'] . '								';
                    //if($rekap[$id_karyawan][$date]['ci']['nama_ijin'] == 'IZIN PERJALANAN DINAS'){

                    //}
                }
                //if(!$bool_hari_libur)
                //	$rekap['total']['ijin_libur'] += 1;

                if ($rekap[$id_karyawan][$date]['ci']['m_jenis_ijin_id'] == 24) {
                    $rekap[$id_karyawan]['total']['ipd'] += 1;
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

			

            if ($content == "<td style='background-color: STR;SRT2'>") {

                if ($bool_hari_libur  and !in_array($date, explode(' | ', $rekap[$id_karyawan]['total']['alphaList'])) and !isset($rekap[$id_karyawan][$date]['a']['masuk'])) {
                    if ($info_karyawan[0]->tgl_bergabung > $date) {
                    } else {

                        $rekap[$id_karyawan]['total']['alpha'] += 1;
                        $rekap[$id_karyawan]['total']['alphaList'] .= $date . ' | ';
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
                        
                        if (!(isset($rekap['klarifikasi'][$id_karyawan][$date]['deskripsi']) or isset($rekap['pending_pengajuan'][$id_karyawan][$date]['status'])))
                            $status_absen = 'TIDAK OK<br>';
                        else if ((isset($rekap['klarifikasi'][$id_karyawan][$date]['deskripsi']))){
                        	
                            $status_absen = 'KLARIFIKASI<br>';
                            $status_libur  = 'Sudah Terdapat Chat Klarifikasi, hubungi HC untuk lebih lanjut<br>';
                        }
                        else if (isset($rekap['pending_pengajuan'][$id_karyawan][$date]['status'])) {
                            if ($rekap['pending_pengajuan'][$id_karyawan][$date]['status'] == 1) {
                                $status = 'Disetujui';
                            } else if ($rekap['pending_pengajuan'][$id_karyawan][$date]['status'] == 2) {
                                $status = 'Ditolak';
                            } else if ($rekap['pending_pengajuan'][$id_karyawan][$date]['status'] == 3) {
                                $status = 'Pending';
                            }
                            $status_absen = 'PENGAJUAN<br>';
                            $status_libur = 'Terdapat Pengajuan ' . $rekap['pending_pengajuan'][$id_karyawan][$date]['nama_izin']. ' dengan Status ' . $status . '<br>';
                        }
                        $warna = 'yellow';
                        $content .= '00:00:00</td>';
                        $content_sheet[$date] .= '00:00:00';
                        $warna_sheet[$date] = 'FFFF00';
                    }
                }
            } else {
                if (isset($rekap[$id_karyawan][$date]['a']['jam_masuk']))
                    $content .= '<br><br>Masuk : ' . (($rekap[$id_karyawan][$date]['a']['jam_masuk'])) . '<br>';
                if (isset($rekap[$id_karyawan][$date]['a']['jam_keluar']))
                    $content .=        'Keluar : ' . (($rekap[$id_karyawan][$date]['a']['jam_keluar'])) . '<br>';
                $content .= '</td>';
            }

            $content = str_ireplace('STR', $warna, $content);
            if ($warna != '' and $warna != 'yellow') {
                $content = str_ireplace('SRT2', 'color: white;', $content);
            }

            $no++;
            $status = '';

            $action = '';
           
            $lokasi_mesin     = isset($rekap[$id_karyawan][$date]['a']['mesin_id']) ? $mesin[$rekap[$id_karyawan][$date]['a']['mesin_id']] : '';
            $masuk_aben     = isset($rekap[$id_karyawan][$date]['a']['masuk']) ? 
            	' <strong style="font-weight:800; font-size:20px">'.$rekap[$id_karyawan][$date]['a']['masuk'].' </strong><br><br> <strong style="font-weight:800">Masuk(Kantor)</strong>: '.date('H:i', strtotime($rekap[$id_karyawan][$date]['a']['jam_masuk'] . ' -1 minutes'))
             : '';
            $keluar_absen     = isset($rekap[$id_karyawan][$date]['a']['keluar']) ? ' <strong style="font-weight:800; font-size:20px">'.$rekap[$id_karyawan][$date]['a']['keluar'].' </strong><br><br> <strong style="font-weight:800">Keluar(Kantor)</strong>: '.$rekap[$id_karyawan][$date]['a']['jam_keluar'] : '';
            if(empty($masuk_aben) and $status_absen=='OK<br>' and !$status_libur){
            	$status_absen = 'TIDAK OK<br>';
            }
            if ($status_absen == 'TIDAK OK<br>') {
                $action = '<a class="btn btn-primary" href="' . route('fe.tambah_chat', ['', 'key=Klarifikasi Absen Tanggal ' . $date]) . '">Klarifikasi</a>';
            }
           
            if(Auth::user()->role==3 or Auth::user()->role==-1 or Auth::user()->role==5  ) {
            	
            if(isset($rekap[$id_karyawan][$date]['a']['absen_log_id_masuk'])){ 
            	$action .='
								<strong>MASUK</strong><br>								
                               <a href="'. route('be.edit_cari_absen_hr',$rekap[$id_karyawan][$date]['a']['absen_log_id_masuk']) .'" title="Ubah" data-toggle="tooltip"><span class="fa fa-edit"></span></a>
                                    <a href="'. route('be.hapus_cari_absen_hr',$rekap[$id_karyawan][$date]['a']['absen_log_id_masuk']) .'" title="Hapus" data-toggle="tooltip"><span class="fa fa-trash"></span></a>';
                    }
          if(isset($rekap[$id_karyawan][$date]['a']['absen_log_id_keluar'])){
				$action .='					
								<br><strong>KELUAR</strong>	<br>							
                                <a href="'. route('be.edit_cari_absen_hr',$rekap[$id_karyawan][$date]['a']['absen_log_id_keluar']) .'" title="Ubah" data-toggle="tooltip"><span class="fa fa-edit"></span></a>
                                    <a href="'. route('be.hapus_cari_absen_hr',$rekap[$id_karyawan][$date]['a']['absen_log_id_keluar']) .'" title="Hapus" data-toggle="tooltip"><span class="fa fa-trash"></span></a>';
            }
           } /**/
             $all_content_cek_absen .= "
			<tr>
			<td>$no</td>
			<td>" . $info_karyawan[0]->no_absen . "</td>
			<td>" . $lokasi_mesin . "</td>
			<td>" . $date . "</td>
			<td>" . $masuk_aben . "</td>
			<td>" . $keluar_absen . "</td>
			<td><div style='font-weight:700'>$status_absen</div><br>$status_libur</td>
			
			<td>" . $action . "</td>

			</tr>";

            $all_content     .= $content;


            $date             = Helper_function::tambah_tanggal($date, 1);
        }


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
        //////echo $id;
        //////echo '<pre>';////echo print_r($rekap);
        $date = $tgl_awal_lembur;
        for ($i = 3; $i <= Helper_function::hitunghari($tgl_awal_lembur, $tgl_akhir_lembur) + 3; $i++) {
            $content = "";
            $warna = '';
            $font = '';
            if (!$info_karyawan[0]->is_karyawan_shift)
                $bool_hari_libur = !(in_array(Helper_function::nama_hari($date), array('Minggu', 'Sabtu')) or in_array($date, $hari_libur) or isset($hari_libur_shift[$date][$id_karyawan]));
            else
                $bool_hari_libur = !(isset($hari_libur_shift[$date][$id_karyawan]));

            if (isset($rekap[$id_karyawan][$date]['lama'])) {
                $total_all += $rekap[$id_karyawan][$date]['lama'];
                if (!$bool_hari_libur) {

                    $lama = $rekap[$id_karyawan][$date]['lama'];
                    if ($lama > 8) {
                        $total['8 jam'] += 8;
                        $lama -= 8;
                    } else if ($lama <= 8) {
                        $total['8 jam'] += $lama;
                        $lama -= $lama;
                    }
                    if ($lama) {

                        $total['9 jam'] += 1;
                        $lama -= 1;
                    }
                    if ($lama)
                        $total['>=10 jam'] += $lama;


                    $total['COUNT Libur'] += 1;
                    $total['SUM Libur'] += $rekap[$id_karyawan][$date]['lama'];
                } else {
                    $lama = $rekap[$id_karyawan][$date]['lama'];
                    $total['1jam'] += 1;
                    $lama -= 1;
                    if ($lama)
                        $total['>=2jam'] += $lama;

                    $total['COUNT Kerja'] += 1;
                    $total['SUM Kerja'] += $rekap[$id_karyawan][$date]['lama'];
                }
                $content .= '' . $rekap[$id_karyawan][$date]['lama'] . '';
                $string_jenis_ijin='LEMBUR';
			                if(isset($rekap[$id_karyawan]['string'][$string_jenis_ijin])){
				                    		$rekap[$id_karyawan]['string'][$string_jenis_ijin] .= " ". $date.' : '.$rekap[$id_karyawan][$date]['lama'].' Jam | ';
				            }else if(!isset($rekap[$id_karyawan]['string'][$string_jenis_ijin])){
				                    		$rekap[$id_karyawan]['string'][$string_jenis_ijin] = $date.' : '.$rekap[$id_karyawan][$date]['lama'].' Jam | ';
							}
            } else {

                $content .= '0';
            }
            $date = Helper_function::tambah_tanggal($date, 1);
        }

        $ipd = isset($rekap[$id_karyawan]['total']['ipd']) ? $rekap[$id_karyawan]['total']['ipd'] : 0;
        $masuk = isset($rekap[$id_karyawan]['total']['absen_masuk']) ? $rekap[$id_karyawan]['total']['absen_masuk'] : 0;
        $cuti            = isset($rekap[$id_karyawan]['total']['cuti']) ? $rekap[$id_karyawan]['total']['cuti'] : 0;
        $ipg            = isset($rekap[$id_karyawan]['total']['ipg']) ? $rekap[$id_karyawan]['total']['ipg'] : 0;
        $izin            = isset($rekap[$id_karyawan]['total']['ihk']) ? $rekap[$id_karyawan]['total']['ihk'] : 0;
        $ipc            = isset($rekap[$id_karyawan]['total']['ipc']) ? $rekap[$id_karyawan]['total']['ipc'] : 0;
        $ipd            = isset($rekap[$id_karyawan]['total']['ipd']) ? $rekap[$id_karyawan]['total']['ipd'] : 0;
        $idt            = isset($rekap[$id_karyawan]['total']['idt']) ? $rekap[$id_karyawan]['total']['idt'] : 0;
        $ipm            = isset($rekap[$id_karyawan]['total']['ipm']) ? $rekap[$id_karyawan]['total']['ipm'] : 0;
        $pm                = isset($rekap[$id_karyawan]['total']['pm']) ? $rekap[$id_karyawan]['total']['pm'] : 0;
        $sakit            = isset($rekap[$id_karyawan]['total']['sakit']) ? $rekap[$id_karyawan]['total']['sakit'] : 0;
        $alpha            = isset($rekap[$id_karyawan]['total']['alpha']) ? $rekap[$id_karyawan]['total']['alpha'] : 0;
        $terlambat         = isset($rekap[$id_karyawan]['total']['terlambat']) ? $rekap[$id_karyawan]['total']['terlambat'] : 0;
        $fingerprint     = isset($rekap[$id_karyawan]['total']['fingerprint']) ? $rekap[$id_karyawan]['total']['fingerprint'] : 0;
        $ijin_libur     = isset($rekap[$id_karyawan]['total']['ijin_libur']) ? $rekap[$id_karyawan]['total']['ijin_libur'] : 0;
        $masuk_libur     = isset($rekap[$id_karyawan]['total']['masuk_libur']) ? $rekap[$id_karyawan]['total']['masuk_libur'] : 0;
        $hari_bulan     = isset($rekap[$id_karyawan]['total']['hari_bulan']) ? $rekap[$id_karyawan]['total']['hari_bulan'] : 0;
        $hari_kerja     = isset($rekap[$id_karyawan]['total']['hari_kerja']) ? $rekap[$id_karyawan]['total']['hari_kerja'] : 0;


        $return['all_content'] = $all_content;
        $return['all_content_cek_absen'] = $all_content_cek_absen;
        $return['content_sheet'] = $content_sheet;
        $return['total']['masuk'] = $masuk;
        $return['total']['cuti'] = $cuti;
        $return['total']['ipg'] = $ipg;
        $return['total']['izin'] = $izin;
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
        $return['total']['alphaList'] 			= $rekap[$id_karyawan]['total']['alphaList'];
        $return['total']['IPGCuti'] 			= $rekap[$id_karyawan]['total']['IPGCuti'];
        $return['total']['IPGCutiList'] 		= $rekap[$id_karyawan]['total']['IPGCutiList'];
        
        
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
	      	  if (isset($data[$list_karyawan->p_karyawan_id]['Keterangan'][$row1]))
	           $tooltip = ' data-toggle="tooltip" data-placement="top" title="'.$data[$list_karyawan->p_karyawan_id]['Keterangan'][$row1].'" ';

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
					 		 .'-'. $data[$list_karyawan->p_karyawan_id]['id'][$row1] 
					 		 .'"  
					 	onclick="change_nominal('.
							$data[$list_karyawan->p_karyawan_id]['id'][$row1].','.
							$$row2.','."'".
							$row2."'".','
							."'".$row1."'".','
							."'".$list_karyawan->p_karyawan_id."'".','
							."'".$list_karyawan->nmlokasi."'".
						')">
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
            $pekerjaan = DB::connection()->select("select * from p_karyawan_pekerjaan where 1=1 $where");
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
					
					$rekap_cuti[$c->p_karyawan_id]['reset_cuti_besar'][$thn][$bln] = $c->nominal;
				
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
        $sql = "select * from m_hari_libur where  is_cuti_bersama = 1
			  
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
                    if($pekerjaan[0]->m_departemen_id ==17)
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
			  WHERE 1=1  $where_2 and (m_jenis_ijin.m_jenis_ijin_id = 5 or m_jenis_ijin.m_jenis_ijin_id = 25)  and t_permit.active=1 and t_permit.status_appr_1 =1 order by tgl_awal ";
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
    
    public static function lap_faskes($id)
    {
    	$sqlfasilitas="SELECT * FROM t_faskes
        		left join p_karyawan_pekerjaan a on t_faskes.p_karyawan_id = a.p_karyawan_id  
                WHERE 1=1 and t_faskes.p_karyawan_id = $id order by tanggal_pengajuan,t_faskes.create_date ";
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
                                
                                <td>'.Helper_function::rupiah2($nominal).'</td>
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
    	$sqljabatan="SELECT * FROM m_jabatan_atasan a  where a.m_jabatan_id = $id_jabatan ";
      	$jabatan=DB::connection()->select($sqljabatan);
		  
     	$atasan =array();
     	$atasan_langsung = isset($jabatan[0]->m_atasan_id)?$jabatan[0]->m_atasan_id:-1;
     	$id_jabatan = $atasan_langsung;
     	$atasan[] =$id_jabatan;
     	$atasan_string =$id_jabatan.',';
     	$visible = true;
     	while($visible){
	     	 $sqljabatan="SELECT *
	      	 FROM m_jabatan_atasan a  where a.m_jabatan_id = $id_jabatan ";
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
      	 $sejajar_string = '';
      	 foreach($jabatan as $j){
      	 	$sejajar[] = $j->m_jabatan_id;
      	 	$sejajar_string .= $j->m_jabatan_id.',';
      	 }
      	 $atasan_string .='-1';
      	 $sejajar_string .='-1';
      	 $bawahan_string = str_replace(',,',',',Helper_function::jabatan_bawahan($temp,$temp,'')).'-1';
      	 
      	 $sejajar_string = str_replace(',,',',',$sejajar_string);
      	 $atasan_string = str_replace(',,',',',$atasan_string);
      	 $bawahan_string = str_replace(',,',',',$bawahan_string);
      	 $return['sejajar'] = $sejajar_string;
      	 $return['bawahan'] = $bawahan_string;
      	 $return['atasan'] = $atasan_string;
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

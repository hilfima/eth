<?php

namespace App;

use Illuminate\Http\Request;
use DB;
use Auth;

class Generate_rekap_absen
{

	public static function rekap_absensi($tgl_awal, $tgl_akhir, $where_karyawan, $wherebawahan, $whereMesin)
	{
		$rekap = array();
		$help = new Helper_function();
		$sqlabsen = "
			select * from absen_shift
					join absen on absen.absen_id = absen_shift.absen_id
					left join p_karyawan_pekerjaan c on absen_shift.p_karyawan_id = c.p_karyawan_id
			where tanggal>='" . $help->tambah_tanggal($tgl_awal, -1) . "' and tanggal<='$tgl_akhir'
			$where_karyawan
			order by tanggal
			";

		$jam_shift = DB::connection()->select($sqlabsen);
		foreach ($jam_shift as $absen) {
			$date = $absen->tanggal;
			$masuk = $absen->jam_masuk;
			$selectedTime = $masuk;
			$startTime = strtotime("-210 minutes", strtotime($selectedTime));
			$minus1 = strtotime("-1 minutes", strtotime($selectedTime));
			$endTime = strtotime("+210 minutes", strtotime($selectedTime));
			$masuk_minus1 =  date('H:i:s', $minus1);
			$masuk_limit_awal =  date('H:i:s', $startTime);
			$masuk_limit_akhir =  date('H:i:s', $endTime);


			$keluar = $absen->jam_keluar;
			$selectedTime_keluar = $keluar;

			$startTime_keluar = strtotime("-210 minutes", strtotime($selectedTime_keluar));
			$endTime = strtotime("+210 minutes", strtotime($selectedTime_keluar));
			$add1 = strtotime("+1 minutes", strtotime($selectedTime_keluar));
			$keluar_limit_awal =  date('H:i:s', $startTime_keluar);

			$keluar_limit_akhir =  date('H:i:s', $endTime);

			$rekap['a'][$date][$absen->p_karyawan_id]['jam_form'] = 'shift';
			$rekap['a'][$date][$absen->p_karyawan_id]['jam_masuk'] = $masuk;
			$rekap['a'][$date][$absen->p_karyawan_id]['masuk_limit_awal'] = $masuk_limit_awal;
			$rekap['a'][$date][$absen->p_karyawan_id]['masuk_limit_akhir'] = $masuk_limit_akhir;
			$rekap['a'][$date][$absen->p_karyawan_id]['masuk_minus1'] = $masuk_minus1;
			$rekap['a'][$date][$absen->p_karyawan_id]['jam_keluar'] = $keluar;
			$rekap['a'][$date][$absen->p_karyawan_id]['keluar_limit_awal'] = $keluar_limit_awal;
			$rekap['a'][$date][$absen->p_karyawan_id]['keluar_limit_akhir'] = $keluar_limit_akhir;
		}
		/*
	$sqlabsen = "select * from m_jamkerja_reguler mjr
			left join  m_jamkerja_reguler_departemen mjrd on mjr.m_jamkerja_reguler_id = mjrd.m_jamkerja_reguler_id and mjrd.active=1
			 join p_karyawan_pekerjaan c on mjrd.m_departement_id = c.m_departemen_id
			 where mjr.tgl_awal<='$tgl_awal'
				and mjr.tgl_akhir>='$tgl_awal' and mjr.active=1
			 $where_karyawan
			 
			 ";
			 //echo $sqlabsen;
	$absen_entitas = DB::connection()->select($sqlabsen);
	foreach($absen_entitas as $absen){
			$masuk = $absen->jam_masuk;
            $selectedTime = $masuk;
			$startTime = strtotime("-210 minutes", strtotime($selectedTime));
			$minus1 = strtotime("-1 minutes", strtotime($selectedTime));
			$endTime = strtotime("+210 minutes", strtotime($selectedTime));
			$masuk_minus1 =  date('H:i:s', $minus1);
			$masuk_limit_awal =  date('H:i:s', $startTime);
			$masuk_limit_akhir =  $absen->jam_batas_masuk;
			
			
			 $keluar = $absen->jam_keluar;
            $selectedTime_keluar = $keluar;
            $jam_keluar_seharusnya= date("h",strtotime($keluar));
          
			$startTime_keluar = strtotime("-210 minutes", strtotime($selectedTime_keluar));
			$endTime = strtotime("+210 minutes", strtotime($selectedTime_keluar));
			$add1 = strtotime("+1 minutes", strtotime($selectedTime_keluar));
			$keluar_limit_awal =  date('H:i:s', $startTime_keluar);
			
			$keluar_limit_akhir =  $absen->jam_batas_keluar;
		$date = $help->tambah_tanggal($tgl_awal,-2);
		for($i=0;$i<=$help->hitungHari($tgl_awal,$tgl_akhir);$i++){
			if(!isset($rekap['a'][$date][$absen->p_karyawan_id]['jam_masuk'])){
				$rekap['a'][$date][$absen->p_karyawan_id]['jam_masuk'] = $absen->jam_masuk;
				$rekap['a'][$date][$absen->p_karyawan_id]['masuk_limit_awal'] = $masuk_limit_awal;
				$rekap['a'][$date][$absen->p_karyawan_id]['masuk_limit_akhir'] = $masuk_limit_akhir;
				$rekap['a'][$date][$absen->p_karyawan_id]['masuk_minus1'] = $masuk_minus1;
				$rekap['a'][$date][$absen->p_karyawan_id]['jam_keluar'] = $absen->jam_keluar;
				$rekap['a'][$date][$absen->p_karyawan_id]['keluar_limit_awal'] = $keluar_limit_awal;
				$rekap['a'][$date][$absen->p_karyawan_id]['keluar_limit_akhir'] = $keluar_limit_akhir;
				$rekap['a'][$date][$absen->p_karyawan_id]['jam_form'] = 'reguler';
			$date = $help->tambah_tanggal($date,1);
				}
			
		}
	}
	*/
		$sqlabsen = "select * from absen 
			 left join absen_list_entitas abe on absen.absen_id = abe.absen_id
			 left join absen_list_departemen abd on absen.absen_id = abd.absen_id
			 left join p_karyawan_pekerjaan c on 
			 	CASE 
		           WHEN absen.tipe_list_entitas IN (1) AND  absen.m_lokasi_id = c.m_lokasi_id THEN 1
		           WHEN absen.tipe_list_entitas IN (2) AND  semua_seksi = 0 and abe.m_lokasi_id = c.m_lokasi_id THEN 1
		           WHEN absen.tipe_list_entitas IN (2) AND  semua_seksi = 1 and abd.m_departement_id = c.m_departemen_id THEN 1
		           ELSE 0
		           END = 1
			 
			
			 
			 
			 where absen.tgl_awal<='$tgl_awal'
				and absen.tgl_akhir>='$tgl_awal' 
				
				and absen.active=1
			 $where_karyawan
			  and shifting=0 
			  and absen.tipe_list_entitas IN (1)
			 
			 ";
		//echo $sqlabsen;
		$absen_entitas = DB::connection()->select($sqlabsen);
		foreach ($absen_entitas as $absen) {
			$masuk = $absen->jam_masuk;
			$selectedTime = $masuk;
			$startTime = strtotime("-210 minutes", strtotime($selectedTime));
			$minus1 = strtotime("-1 minutes", strtotime($selectedTime));
			$endTime = strtotime("+210 minutes", strtotime($selectedTime));
			$masuk_minus1 =  date('H:i:s', $minus1);
			$masuk_limit_awal =  date('H:i:s', $startTime);
			$masuk_limit_akhir =  date('H:i:s', $endTime);


			$keluar = $absen->jam_keluar;
			$selectedTime_keluar = $keluar;
			$jam_keluar_seharusnya = date("h", strtotime($keluar));

			$startTime_keluar = strtotime("-210 minutes", strtotime($selectedTime_keluar));
			$endTime = strtotime("+210 minutes", strtotime($selectedTime_keluar));
			$add1 = strtotime("+1 minutes", strtotime($selectedTime_keluar));
			$keluar_limit_awal =  date('H:i:s', $startTime_keluar);

			$keluar_limit_akhir =  date('H:i:s', $endTime);

			$date = $help->tambah_tanggal($tgl_awal, -1);
			
			for ($i = 0; $i <= $help->hitungHari($tgl_awal, $tgl_akhir) + 1; $i++) {

				if (!isset($rekap['a'][$date][$absen->p_karyawan_id]['jam_masuk']) or !isset($rekap['a'][$date][$absen->p_karyawan_id]['jam_keluar'])  ) {
					
					$rekap['a'][$date][$absen->p_karyawan_id]['jam_masuk'] = $masuk;
					$rekap['a'][$date][$absen->p_karyawan_id]['masuk_limit_awal'] = $masuk_limit_awal;
					$rekap['a'][$date][$absen->p_karyawan_id]['masuk_limit_akhir'] = $masuk_limit_akhir;
					$rekap['a'][$date][$absen->p_karyawan_id]['masuk_minus1'] = $masuk_minus1;
					$rekap['a'][$date][$absen->p_karyawan_id]['jam_keluar'] = $keluar;
					$rekap['a'][$date][$absen->p_karyawan_id]['keluar_limit_awal'] = $keluar_limit_awal;
					$rekap['a'][$date][$absen->p_karyawan_id]['keluar_limit_akhir'] = $keluar_limit_akhir;
					$rekap['a'][$date][$absen->p_karyawan_id]['jam_form'] = 'entitas';
				}


				$date = $help->tambah_tanggal($date, 1);
			}

			if ($absen->masuk_senin != null) {

				if ($absen->masuk_senin)
					$rekap['masuk'][] = 'Senin';
				else
					$rekap['libur'][] = 'Senin';
			} else {
				$rekap['masuk'][] = 'Senin';
			}
			if ($absen->masuk_selasa != null) {

				if ($absen->masuk_selasa)
					$rekap['masuk'][] = 'Selasa';
				else
					$rekap['libur'][] = 'Selasa';
			} else {
				$rekap['masuk'][] = 'Selasa';
			}
			if ($absen->masuk_rabu != null) {
				if ($absen->masuk_rabu)
					$rekap['masuk'][] = 'Rabu';
				else
					$rekap['libur'][] = 'Rabu';
			} else {
				$rekap['masuk'][] = 'Rabu';
			}
			if ($absen->masuk_kamis != null) {
				if ($absen->masuk_kamis)
					$rekap['masuk'][] = 'Kamis';
				else
					$rekap['libur'][] = 'Kamis';
			} else {
				$rekap['masuk'][] = 'Kamis';
			}
			if ($absen->masuk_jumat != null) {
				if ($absen->masuk_jumat)
					$rekap['masuk'][] = 'Jumat';
				else
					$rekap['libur'][] = 'Jumat';
			} else {
				$rekap['masuk'][] = 'Jumat';
			}
			if ($absen->masuk_sabtu != null) {
				if ($absen->masuk_sabtu)
					$rekap['masuk'][] = 'Sabtu';
				else
					$rekap['libur'][] = 'Sabtu';
			} else {
				$rekap['libur'][] = 'Sabtu';
			}
			if ($absen->masuk_ahad != null) {
				if ($absen->masuk_ahad)
					$rekap['masuk'][] = 'Minggu';
				else
					$rekap['libur'][] = 'Minggu';
			} else {
				$rekap['libur'][] = 'Minggu';
			}
		}
		if (!isset($rekap['masuk'])) {

			$rekap['masuk'][] = 'Senin';
			$rekap['masuk'][] = 'Selasa';
			$rekap['masuk'][] = 'Rabu';
			$rekap['masuk'][] = 'Kamis';
			$rekap['masuk'][] = 'Jumat';
			$rekap['libur'][] = 'Sabtu';
			$rekap['libur'][] = 'Minggu';
		}
		//	echo '<pre>';	print_r($rekap);
		$sqlabsen = "

		select a.*,d.p_karyawan_id,c.m_lokasi_id,is_shift as is_karyawan_shift

			 from absen_log a
			 join p_karyawan_absen b on b.no_absen = a.pin
			 join p_karyawan_pekerjaan c on c.p_karyawan_id = b.p_karyawan_id
			 left join m_office mk on c.m_kantor_id = mk.m_office_id 
			 left join m_mesin_absen on mk.m_mesin_absen_seharusnya_id = m_mesin_absen.mesin_id 
			 left join p_karyawan d on b.p_karyawan_id = d.p_karyawan_id


			 where a.date_time>='$tgl_awal' and a.date_time<='$tgl_akhir 23:59:59' and d.active=1 and a.active=1 and ver!=0

		$where_karyawan
		$wherebawahan
		$whereMesin
		group by date_time,a.mesin_id,a.pin,c.is_shift,a.generate_absen,a.created_at,a.deleted_at,a.permit,a.active,a.master,status_absen_id,absen_log_id,m_mesin_absen.mesin_id,time_before_update,a.updated_by,a.updated_at,ver,d.p_karyawan_id,c.m_lokasi_id
		order by date_time asc
		
		";

		$absen = DB::connection()->select($sqlabsen);
		foreach ($absen as $absen) {
			$array_karyawan[] = $absen->p_karyawan_id;
			$date = date('Y-m-d', strtotime($absen->date_time));
			$time = date('H:i:s', strtotime($absen->date_time));
			$time2 = date('H:i:s', strtotime($absen->date_time));
			$datebefore = Helper_function::tambah_tanggal($date, -1);
			$lokasi_id = $absen->m_lokasi_id;

			$id_karyawan = $absen->p_karyawan_id;
			$masuk = false;
			if (isset($rekap['a'][$date][$absen->p_karyawan_id]['masuk_limit_awal'])) {
				$masuk = true;
			}

			$keluar = false;
			if (isset($rekap['a'][$date][$absen->p_karyawan_id]['keluar_limit_awal'])) {
				$keluar = true;
			}
			if ($keluar and $masuk) {
				if ($rekap['a'][$date][$absen->p_karyawan_id]['masuk_limit_awal'] <= $time and $rekap['a'][$date][$absen->p_karyawan_id]['masuk_limit_akhir'] >= $time) {
					$pra['a'][$date][$absen->p_karyawan_id]['masuk'] = $time;
					$pra['a'][$date][$absen->p_karyawan_id]['status_masuk'] = $absen->status_absen_id;
					$pra['a'][$date][$absen->p_karyawan_id]['appr_status'] = $absen->appr_status;
					$pra['a'][$date][$absen->p_karyawan_id]['updated_at_masuk'] = $absen->updated_at;
					$pra['a'][$date][$absen->p_karyawan_id]['updated_by_masuk'] = $absen->updated_by;
					$pra['a'][$date][$absen->p_karyawan_id]['time_before_update_masuk'] = $absen->time_before_update;
					$pra['a'][$date][$absen->p_karyawan_id]['mesin_id'] = $absen->mesin_id;
					$pra['a'][$date][$absen->p_karyawan_id]['absen_log_id_masuk'] = $absen->absen_log_id;
				} else if ($rekap['a'][$date][$absen->p_karyawan_id]['keluar_limit_awal'] <= $time and $rekap['a'][$date][$absen->p_karyawan_id]['keluar_limit_akhir'] >= $time) {
					//echo 'HALLOW';
					$id_karyawan = $absen->p_karyawan_id;

					$pra['a'][$date][$absen->p_karyawan_id]['keluar'] = $time;
					$pra['a'][$date][$absen->p_karyawan_id]['absen_log_id_keluar'] = $absen->absen_log_id;

					$pra['a'][$date][$absen->p_karyawan_id]['status_keluar'] = $absen->status_absen_id;
					$pra['a'][$date][$absen->p_karyawan_id]['updated_at_keluar'] = $absen->updated_at;
					$pra['a'][$date][$absen->p_karyawan_id]['updated_by_keluar'] = $absen->updated_by;
					$pra['a'][$date][$absen->p_karyawan_id]['mesin_id_keluar'] = $absen->mesin_id;
					$pra['a'][$date][$absen->p_karyawan_id]['time_before_update_keluar'] = $absen->time_before_update;
				}
			} else {
				if ($absen->ver == 1) {
					$pra['a'][$date][$absen->p_karyawan_id]['masuk'] = $time;
					$pra['a'][$date][$absen->p_karyawan_id]['status_masuk'] = $absen->status_absen_id;
					$pra['a'][$date][$absen->p_karyawan_id]['appr_status'] = $absen->appr_status;
					$pra['a'][$date][$absen->p_karyawan_id]['updated_at_masuk'] = $absen->updated_at;
					$pra['a'][$date][$absen->p_karyawan_id]['updated_by_masuk'] = $absen->updated_by;
					$pra['a'][$date][$absen->p_karyawan_id]['time_before_update_masuk'] = $absen->time_before_update;
					$pra['a'][$date][$absen->p_karyawan_id]['mesin_id'] = $absen->mesin_id;
					$pra['a'][$date][$absen->p_karyawan_id]['absen_log_id_masuk'] = $absen->absen_log_id;
				} else if ($absen->ver == 2) {
					$pra['a'][$date][$absen->p_karyawan_id]['keluar'] = $time;
					$pra['a'][$date][$absen->p_karyawan_id]['absen_log_id_keluar'] = $absen->absen_log_id;

					$pra['a'][$date][$absen->p_karyawan_id]['status_keluar'] = $absen->status_absen_id;
					$pra['a'][$date][$absen->p_karyawan_id]['updated_at_keluar'] = $absen->updated_at;
					$pra['a'][$date][$absen->p_karyawan_id]['updated_by_keluar'] = $absen->updated_by;
					$pra['a'][$date][$absen->p_karyawan_id]['mesin_id_keluar'] = $absen->mesin_id;
					$pra['a'][$date][$absen->p_karyawan_id]['time_before_update_keluar'] = $absen->time_before_update;
				}
			}
		}
		//echo '<pre>';print_r($pra);die;
		$sqlabsen = "

		select a.*,a.p_karyawan_id,c.m_lokasi_id,is_shift as is_karyawan_shift

			 from p_karyawan a
			 left join p_karyawan_pekerjaan c on a.p_karyawan_id = c.p_karyawan_id
			 


			 where a.active=1

			$where_karyawan
			$wherebawahan
		
		";

		$absen = DB::connection()->select($sqlabsen);
		foreach ($absen as $absen) {

			$date = ($tgl_awal);
			for ($i = 0; $i <= $help->hitungHari($tgl_awal, $tgl_akhir); $i++) {
				$array_karyawan[] = $absen->p_karyawan_id;

				$datebefore = Helper_function::tambah_tanggal($date, -1);
				$dateafter = Helper_function::tambah_tanggal($date, 1);

				if ($absen->is_karyawan_shift) {
					$lintas = false;
					$is = false;
					if (isset($rekap['a'][$datebefore][$absen->p_karyawan_id]) and isset($rekap['a'][$date][$absen->p_karyawan_id]['masuk_minus1'])) {
						if(!isset($rekap['a'][$datebefore][$absen->p_karyawan_id]['jam_keluar'])){
							print_r($rekap['a'][$date][$absen->p_karyawan_id]);
							echo $datebefore;
							echo $absen->p_karyawan_id;
						}
						if ($rekap['a'][$datebefore][$absen->p_karyawan_id]['jam_keluar'] <= $rekap['a'][$datebefore][$absen->p_karyawan_id]['jam_masuk']) {
							$lintas = true;
						}


						$is = ($rekap['a'][$date][$absen->p_karyawan_id]['masuk_minus1'] == ($rekap['a'][$datebefore][$absen->p_karyawan_id]['jam_keluar']));
					}
					if ($is) {

						//echo 'hallow';
						//tgl 15
						//di cek kalau 
						//echo $date;
						if (isset($pra['a'][$date][$absen->p_karyawan_id]))
							$rekap['a'][$date][$absen->p_karyawan_id] +=  $pra['a'][$date][$absen->p_karyawan_id];
						if (!isset($pra['a'][$date][$absen->p_karyawan_id]['masuk']) and isset($pra['a'][$datebefore][$absen->p_karyawan_id]['keluar'])) {

							$rekap['a'][$date][$absen->p_karyawan_id]['masuk'] 				= $pra['a'][$datebefore][$absen->p_karyawan_id]['keluar'];
							$rekap['a'][$date][$absen->p_karyawan_id]['status_masuk'] 		= $pra['a'][$datebefore][$absen->p_karyawan_id]['status_keluar'];
							$rekap['a'][$date][$absen->p_karyawan_id]['updated_at_masuk'] 	= $pra['a'][$datebefore][$absen->p_karyawan_id]['updated_at_keluar'];
							$rekap['a'][$date][$absen->p_karyawan_id]['updated_by_masuk'] 	= $pra['a'][$datebefore][$absen->p_karyawan_id]['updated_by_keluar'];
							$rekap['a'][$date][$absen->p_karyawan_id]['time_before_update_masuk'] = $pra['a'][$datebefore][$absen->p_karyawan_id]['time_before_update_keluar'];
							$rekap['a'][$date][$absen->p_karyawan_id]['mesin_id'] 			= $pra['a'][$datebefore][$absen->p_karyawan_id]['mesin_id_keluar'];
							$rekap['a'][$date][$absen->p_karyawan_id]['absen_log_id_masuk'] = $pra['a'][$datebefore][$absen->p_karyawan_id]['absen_log_id_keluar'];
						} else if (isset($pra['a'][$date][$absen->p_karyawan_id]['masuk']) and !isset($pra['a'][$datebefore][$absen->p_karyawan_id]['keluar'])) {

							$rekap['a'][$datebefore][$absen->p_karyawan_id]['keluar'] 				= $pra['a'][$date][$absen->p_karyawan_id]['masuk'];
							$rekap['a'][$datebefore][$absen->p_karyawan_id]['status_keluar'] 		= $pra['a'][$date][$absen->p_karyawan_id]['status_masuk'];
							$rekap['a'][$datebefore][$absen->p_karyawan_id]['updated_at_keluar'] 	= $pra['a'][$date][$absen->p_karyawan_id]['updated_at_masuk'];
							$rekap['a'][$datebefore][$absen->p_karyawan_id]['updated_by_keluar'] 	= $pra['a'][$date][$absen->p_karyawan_id]['updated_by_masuk'];
							$rekap['a'][$datebefore][$absen->p_karyawan_id]['time_before_update_keluar'] = $pra['a'][$date][$absen->p_karyawan_id]['time_before_update_masuk'];
							$rekap['a'][$datebefore][$absen->p_karyawan_id]['mesin_id_keluar'] 			= $pra['a'][$date][$absen->p_karyawan_id]['mesin_id'];
							$rekap['a'][$datebefore][$absen->p_karyawan_id]['absen_log_id_keluar'] = $pra['a'][$date][$absen->p_karyawan_id]['absen_log_id_masuk'];
						}
					} else if ($lintas) {
						if (!isset($pra['a'][$dateafter][$absen->p_karyawan_id]['masuk']) and isset($pra['a'][$dateafter][$absen->p_karyawan_id]['keluar']) and !isset($pra['a'][$date][$absen->p_karyawan_id]['keluar'])) {
							$rekap['a'][$date][$absen->p_karyawan_id]['keluar'] 			= $pra['a'][$dateafter][$absen->p_karyawan_id]['keluar'];
							$rekap['a'][$date][$absen->p_karyawan_id]['status_keluar'] 		= $pra['a'][$dateafter][$absen->p_karyawan_id]['status_keluar'];
							$rekap['a'][$date][$absen->p_karyawan_id]['updated_at_keluar'] 	= $pra['a'][$dateafter][$absen->p_karyawan_id]['updated_at_keluar'];
							$rekap['a'][$date][$absen->p_karyawan_id]['updated_by_keluar'] 	= $pra['a'][$dateafter][$absen->p_karyawan_id]['updated_by_keluar'];
							$rekap['a'][$date][$absen->p_karyawan_id]['time_before_update_keluar'] = $pra['a'][$dateafter][$absen->p_karyawan_id]['time_before_update_keluar'];
							$rekap['a'][$date][$absen->p_karyawan_id]['mesin_id_keluar'] 			= $pra['a'][$dateafter][$absen->p_karyawan_id]['mesin_id_keluar'];
							$rekap['a'][$date][$absen->p_karyawan_id]['absen_log_id_keluar'] = $pra['a'][$dateafter][$absen->p_karyawan_id]['absen_log_id_keluar'];

							$rekap['a'][$dateafter][$absen->p_karyawan_id]['keluar'] 			= "";
							$rekap['a'][$dateafter][$absen->p_karyawan_id]['status_keluar'] 		= "";
							$rekap['a'][$dateafter][$absen->p_karyawan_id]['updated_at_keluar'] 	= "";
							$rekap['a'][$dateafter][$absen->p_karyawan_id]['updated_by_keluar'] 	= "";
							$rekap['a'][$dateafter][$absen->p_karyawan_id]['time_before_update_keluar'] = "";
							$rekap['a'][$dateafter][$absen->p_karyawan_id]['mesin_id_keluar'] 			= "";
							$rekap['a'][$dateafter][$absen->p_karyawan_id]['absen_log_id_keluar'] = "";
						}
					} else {

						if (isset($pra['a'][$date][$absen->p_karyawan_id])) {
							if (isset($rekap['a'][$date][$absen->p_karyawan_id]))
								$rekap['a'][$date][$absen->p_karyawan_id] +=  $pra['a'][$date][$absen->p_karyawan_id];
							else
								$rekap['a'][$date][$absen->p_karyawan_id] =  $pra['a'][$date][$absen->p_karyawan_id];
						}
					}
				} else {
					if (isset($pra['a'][$date][$absen->p_karyawan_id])) {
						if (isset($rekap['a'][$date][$absen->p_karyawan_id]))
							$rekap['a'][$date][$absen->p_karyawan_id] +=  $pra['a'][$date][$absen->p_karyawan_id];
						else
							$rekap['a'][$date][$absen->p_karyawan_id] =  $pra['a'][$date][$absen->p_karyawan_id];
					}
					//else 
					// $rekap['a'][$date][$absen->p_karyawan_id] =  $pra['a'][$date][$absen->p_karyawan_id];



				}
				$date = $help->tambah_tanggal($date, 1);
			}
		}
		//die;
		return $rekap;
	}
	public static function rekap_izin($tgl_awal, $tgl_akhir, $where_karyawan, $wherebawahan2, $hari_libur, $hari_libur_shift, $karyawan)
	{
		$rekap = array();
		$sqllembur = "Select
		 m_jenis_ijin.nama as nama_ijin,m_jenis_ijin.tipe,c.m_jenis_ijin_id,c.lama,c.keterangan,
		 c.p_karyawan_id,c.tgl_awal,c.tgl_akhir,c.jam_awal,c.status_appr_hr,a.periode_gajian,
		 status_appr_1,m_jenis_ijin.kode as string_kode_ijin,c.appr_1
		from t_permit c
		left join m_jenis_ijin on m_jenis_ijin.m_jenis_ijin_id = c.m_jenis_ijin_id
		left join p_karyawan_pekerjaan a on c.p_karyawan_id = a.p_karyawan_id 
		where ((c.tgl_awal>='$tgl_awal' and c.tgl_awal<='$tgl_akhir 23:59') or
		(c.tgl_akhir>='$tgl_awal' and c.tgl_akhir>='$tgl_akhir 23:59') or
		(c.tgl_akhir>='$tgl_awal' and c.tgl_akhir<='$tgl_akhir 23:59') or
		(c.tgl_awal<='$tgl_awal' and c.tgl_akhir<='$tgl_awal 23:59' and c.tgl_akhir is not null)) and
		c.m_jenis_ijin_id != 22
		and tgl_awal >= '" . Helper_function::tambah_bulan($tgl_awal, -5) . "'
		$where_karyawan and c.active=1
		$wherebawahan2
		and ((tgl_awal>='2023-01-01' and (status_appr_hr!=2 or status_appr_hr is null)) or tgl_awal<'2023-01-01')
		ORDER BY c.p_karyawan_id asc ";

		$lembur = DB::connection()->select($sqllembur);

		$array_tgl = array();

		/*((c.status_appr_1 = 1 and c.m_jenis_ijin_id not in (20,21,26))
or (c.status_appr_hr = 1 and c.m_jenis_ijin_id in (20,21,26)) )*/
		//var_dump($lembur); die;

		//echo '<pre>';print_r($lembur);echo '</pre>';die;
		foreach ($lembur as $lembur) {
			$date = $lembur->tgl_awal;
			if ($lembur->status_appr_1 == 1) {

				if (!in_array($lembur->p_karyawan_id, $karyawan))
					$karyawan[] = $lembur->p_karyawan_id;
				if ($lembur->status_appr_hr != 2) {
					$date = $lembur->tgl_awal;
					if (!$lembur->tgl_akhir)
						$lembur->tgl_akhir = $lembur->tgl_awal;

					if ($lembur->tgl_akhir == '1970-01-01')
						$lembur->tgl_akhir = $lembur->tgl_awal;
					for ($i = 0; $i <= Helper_function::hitunghari($lembur->tgl_awal, $lembur->tgl_akhir); $i++) {
						if ($date >= $tgl_awal and $date <= $tgl_akhir) {
							if (in_array(Helper_function::nama_hari($date), array('Minggu', 'Sabtu')) and in_array($date, $hari_libur) and isset($hari_libur_shift[$date][$id_karyawan])) {
								if (isset($rekap['total'][$lembur->p_karyawan_id]['ijin_libur']))
									$rekap['total'][$lembur->p_karyawan_id]['ijin_libur'] += 1;
								else
									$rekap['total'][$lembur->p_karyawan_id]['ijin_libur'] = 1;
							}
							if (isset($rekap['total_id'][$lembur->p_karyawan_id][$lembur->m_jenis_ijin_id])) {
								$rekap['total_id'][$lembur->p_karyawan_id][$lembur->m_jenis_ijin_id] += 1;
							} else {
								$rekap['total_id'][$lembur->p_karyawan_id][$lembur->m_jenis_ijin_id] = 1;
							}

							if (in_array($lembur->m_jenis_ijin_id, array(25, 5)) and $lembur->periode_gajian == 0) {
							} else {

								$rekap['ci'][$date][$lembur->p_karyawan_id]['nama_ijin'] = $lembur->nama_ijin . '<br> ' . $lembur->tgl_awal . ' sd ' . $lembur->tgl_akhir . '<br> Total: ' . $lembur->lama . ' Hari';
								$rekap['ci'][$date][$lembur->p_karyawan_id]['nama_ijin_only'] = $lembur->nama_ijin;
								$rekap['ci'][$date][$lembur->p_karyawan_id]['keterangan'] = $lembur->keterangan;
								$rekap['ci'][$date][$lembur->p_karyawan_id]['lama'] = $lembur->lama;
								$rekap['ci'][$date][$lembur->p_karyawan_id]['jam_awal'] = $lembur->jam_awal;
								$rekap['ci'][$date][$lembur->p_karyawan_id]['tipe'] = $lembur->tipe;
								$rekap['ci'][$date][$lembur->p_karyawan_id]['m_jenis_ijin_id'] = $lembur->m_jenis_ijin_id;
							}
						}
						$date = Helper_function::tambah_tanggal($date, 1);
					}
				}
			} else {
				$date = $lembur->tgl_awal;
				for ($i = 0; $i <= Helper_function::hitunghari($lembur->tgl_awal, $lembur->tgl_akhir); $i++) {
					if ($date >= $tgl_awal and $date <= $tgl_akhir) {

						$rekap['pending_pengajuan'][$date][$lembur->p_karyawan_id]['nama_izin'] = $lembur->nama_ijin;
						$rekap['pending_pengajuan'][$date][$lembur->p_karyawan_id]['status'] = $lembur->status_appr_1;
						$rekap['pending_pengajuan'][$date][$lembur->p_karyawan_id]['atasan_1'] = $lembur->appr_1;
						$rekap['pending_pengajuan'][$date][$lembur->p_karyawan_id]['jenis_ijin'] = $lembur->m_jenis_ijin_id;
					}
					$date = Helper_function::tambah_tanggal($date, 1);
				}
			}
		}
		$pending = DB::connection()->select("select * from t_permit  c 
        where 
        (status_appr_1=3 or status_appr_2=3) 
        and ((c.tgl_awal>='$tgl_awal' and c.tgl_awal<='$tgl_akhir 23:59') or
		(c.tgl_akhir>='$tgl_awal' and c.tgl_akhir>='$tgl_akhir 23:59') or
		(c.tgl_akhir>='$tgl_awal' and c.tgl_akhir<='$tgl_akhir 23:59') or
		(c.tgl_awal<='$tgl_awal' and c.tgl_akhir<='$tgl_awal 23:59' and c.tgl_akhir is not null)) and
		
		c.m_jenis_ijin_id != 22
		and tgl_awal >= '" . Helper_function::tambah_bulan($tgl_awal, -5) . "'");
		foreach ($pending as $pending) {
			if ($pending->status_appr_1 == 3) {
				$rekap['pendding_approval'][$pending->appr_1]['tgl_awal'][] = $pending->tgl_awal;
				$rekap['pendding_approval'][$pending->appr_1]['tgl_akhir'][] = $pending->tgl_akhir;
				$rekap['pendding_approval'][$pending->appr_1]['m_jenis_ijin_id'][] = $pending->m_jenis_ijin_id;
				$rekap['pendding_approval'][$pending->appr_1]['appr'][] = "1";
			}
			if ($pending->status_appr_2 == 3) {
				$rekap['pendding_approval'][$pending->appr_2]['appr'][] = "2";
				$rekap['pendding_approval'][$pending->appr_2]['tgl_awal'][] = $pending->tgl_awal;
				$rekap['pendding_approval'][$pending->appr_2]['tgl_akhir'][] = $pending->tgl_akhir;
				$rekap['pendding_approval'][$pending->appr_2]['m_jenis_ijin_id'][] = $pending->m_jenis_ijin_id;
			}
		}

		$return['karyawan'] = $karyawan;
		$return['rekap'] = $rekap;
		return $return;
	}
	public static function rekap_lembur($tgl_awal_lembur, $tgl_akhir_lembur, $where_karyawan, $where_periode_gajian, $wherebawahan3, $karyawan)
	{
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
		$wherebawahan3
		and ((tgl_awal>='2023-01-01' and (status_appr_hr!=2 or status_appr_hr is null)) or tgl_awal<'2023-01-01')
		and ((intruksi_atasan is null or intruksi_atasan!=1) or (intruksi_atasan=1 and status_appr_intruksi=1))
		ORDER BY c.p_karyawan_id asc";
		$lembur = DB::connection()->select($sqllembur);
		//$rekap = array();

		//echo '<pre>';print_r($lembur); echo '</pre>';die;
		$rekap = array();
		$array_tgl = array();
		$tgl_awal_lembur_ajuan = $tgl_awal_lembur;
		//print_r($tgl_akhir_lembur);$die;
		foreach ($lembur as $lembur) {
			$id_karyawan = $lembur->p_karyawan_id;
			//if($lembur->tgl_awal >=$tgl_awal_lembur){
			if (!in_array($lembur->p_karyawan_id, $karyawan))
				$karyawan[] = $lembur->p_karyawan_id;
			if (!isset($rekap['total_lembur'][$lembur->p_karyawan_id])) {
				$rekap['total_lembur'][$lembur->p_karyawan_id]['total_pengajuan'] = 0;
				$rekap['total_lembur'][$lembur->p_karyawan_id]['total_pending'] = 0;
				$rekap['total_lembur'][$lembur->p_karyawan_id]['total_approve'] = 0;
				$rekap['total_lembur'][$lembur->p_karyawan_id]['total_tolak'] = 0;
				$rekap['total_lembur'][$lembur->p_karyawan_id]['total_lembur_proposional'] = 0;
				$rekap['total_lembur'][$lembur->p_karyawan_id]['tgl_pending'] = '';
			}
			$rekap['total_lembur'][$lembur->p_karyawan_id]['total_pengajuan'] += (int)$lembur->lama;
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
				$rekap['total_lembur'][$lembur->p_karyawan_id]['total_approve'] += (int)$lembur->lama;

				$array_tgl[] =  $lembur->tgl_awal;;
				$tgl_appr = $lembur->tgl_awal;
				if ($lembur->tipe_lembur == 'Lembur Proposional') {
					$rekap['Proposional']['ajuan'][$lembur->tgl_awal][$lembur->p_karyawan_id]['tipe_lembur'] = $lembur->tipe_lembur;
					$rekap['Proposional']['ajuan'][$lembur->tgl_awal][$lembur->p_karyawan_id]['tgl_awal'] = $lembur->tgl_awal;
					$rekap['Proposional']['ajuan'][$lembur->tgl_awal][$lembur->p_karyawan_id]['tgl_akhir'] = $lembur->tgl_akhir;

					$rekap['Proposional']['ajuan'][$lembur->tgl_awal][$lembur->p_karyawan_id]['lama'] = $lembur->lama;
					$rekap['Proposional']['ajuan'][$lembur->tgl_awal][$lembur->p_karyawan_id]['keterangan'] = $lembur->keterangan;
					$rekap['Proposional']['ajuan'][$lembur->tgl_awal][$lembur->p_karyawan_id]['jam_awal'] = $lembur->jam_awal;
					$rekap['Proposional']['ajuan'][$lembur->tgl_awal][$lembur->p_karyawan_id]['jam_akhir'] = $lembur->jam_akhir;

					$rekap['Proposional']['approve'][$tgl_appr][$lembur->p_karyawan_id]['tipe_lembur'] = $lembur->tipe_lembur;
					$rekap['Proposional']['approve'][$tgl_appr][$lembur->p_karyawan_id]['tgl_awal'] = $lembur->tgl_awal;
					$rekap['Proposional']['approve'][$tgl_appr][$lembur->p_karyawan_id]['tgl_akhir'] = $lembur->tgl_akhir;

					$rekap['Proposional']['approve'][$tgl_appr][$lembur->p_karyawan_id]['lama'] = $lembur->lama;
					$rekap['Proposional']['approve'][$tgl_appr][$lembur->p_karyawan_id]['keterangan'] = $lembur->keterangan;
					$rekap['Proposional']['approve'][$tgl_appr][$lembur->p_karyawan_id]['jam_awal'] = $lembur->jam_awal;
					$rekap['Proposional']['approve'][$tgl_appr][$lembur->p_karyawan_id]['jam_akhir'] = $lembur->jam_akhir;


					$rekap['total_lembur'][$lembur->p_karyawan_id]['total_lembur_proposional'] += 1;
				} else {
					$rekap['Normal']['approve'][$tgl_appr][$id_karyawan]['tipe_lembur'] = $lembur->tipe_lembur;
					$rekap['Normal']['approve'][$tgl_appr][$id_karyawan]['tgl_awal'] = $lembur->tgl_awal;
					$rekap['Normal']['approve'][$tgl_appr][$id_karyawan]['tgl_akhir'] = $lembur->tgl_akhir;


					$rekap['Normal']['ajuan'][$lembur->tgl_awal][$id_karyawan]['tipe_lembur'] = $lembur->tipe_lembur;
					$rekap['Normal']['ajuan'][$lembur->tgl_awal][$id_karyawan]['tgl_awal'] = $lembur->tgl_awal;
					$rekap['Normal']['ajuan'][$lembur->tgl_awal][$id_karyawan]['tgl_akhir'] = $lembur->tgl_akhir;




					if (!isset($rekap['Normal']['approve'][$tgl_appr][$id_karyawan]['lama'])) {

						$rekap['Normal']['approve'][$tgl_appr][$id_karyawan]['lama'] = $lembur->lama;
						$rekap['Normal']['approve'][$tgl_appr][$id_karyawan]['keterangan'] = $lembur->keterangan;
						$rekap['Normal']['approve'][$tgl_appr][$id_karyawan]['jam_awal'] = $lembur->jam_awal;
						$rekap['Normal']['approve'][$tgl_appr][$id_karyawan]['jam_akhir'] = $lembur->jam_akhir;
						$rekap['Normal']['approve'][$tgl_appr][$id_karyawan]['total_ajuan'] = 1;

						$rekap['Normal']['ajuan'][$lembur->tgl_awal][$id_karyawan]['lama'] = $lembur->lama;
						$rekap['Normal']['ajuan'][$lembur->tgl_awal][$id_karyawan]['keterangan'] = $lembur->keterangan;
						$rekap['Normal']['ajuan'][$lembur->tgl_awal][$id_karyawan]['jam_awal'] = $lembur->jam_awal;
						$rekap['Normal']['ajuan'][$lembur->tgl_awal][$id_karyawan]['jam_akhir'] = $lembur->jam_akhir;
						$rekap['Normal']['ajuan'][$lembur->tgl_awal][$id_karyawan]['total_ajuan'] = 1;
					} else {

						$rekap['Normal']['approve'][$tgl_appr][$id_karyawan]['total_ajuan'] += 1;
						$rekap['Normal']['ajuan'][$lembur->tgl_awal][$id_karyawan]['total_ajuan'] += 1;

						$rekap['Normal']['approve'][$tgl_appr][$id_karyawan]['lama'] += $lembur->lama;
						$rekap['Normal']['approve'][$tgl_appr][$id_karyawan]['keterangan'] = str_replace(',', '', '<br><b style="font-weight:700">' .

							$rekap['Normal']['approve'][$lembur->tgl_awal][$id_karyawan]['jam_awal'] . '</b><br>' .
							$rekap['Normal']['approve'][$lembur->tgl_awal][$id_karyawan]['keterangan']
							. '  |  ' . '<b style="font-weight:700">' . $lembur->jam_awal . '</b><br>' . $lembur->keterangan);

						$rekap['Normal']['approve'][$tgl_appr][$id_karyawan]['jam_awal'] = $rekap['Normal']['approve'][$tgl_appr][$id_karyawan]['jam_awal'] . ' s/d ' . $rekap['Normal']['approve'][$tgl_appr][$id_karyawan]['jam_akhir'] . ' | ' . $lembur->jam_awal . ' s/d ' . $lembur->jam_akhir;
						$rekap['Normal']['approve'][$tgl_appr][$id_karyawan]['jam_akhir'] = '';

						$rekap['Normal']['ajuan'][$lembur->tgl_awal][$id_karyawan]['lama'] += $lembur->lama;
						$rekap['Normal']['ajuan'][$lembur->tgl_awal][$id_karyawan]['keterangan'] = str_replace(',', '', '<br><b style="font-weight:700">' .
							$rekap['Normal']['ajuan'][$lembur->tgl_awal][$id_karyawan]['jam_awal'] . '</b><br>' .
							$rekap['Normal']['ajuan'][$lembur->tgl_awal][$id_karyawan]['keterangan']

							. '  |  '
							. '<b style="font-weight:700">' . $lembur->jam_awal . '</b><br>' . $lembur->keterangan);
						$rekap['Normal']['ajuan'][$lembur->tgl_awal][$id_karyawan]['jam_awal'] = $rekap['Normal']['approve'][$tgl_appr][$id_karyawan]['jam_awal'] . ' s/d ' . $rekap['Normal']['approve'][$tgl_appr][$id_karyawan]['jam_akhir'] . ' | ' . $lembur->jam_awal . ' s/d ' . $lembur->jam_akhir;
						$rekap['Normal']['ajuan'][$lembur->tgl_awal][$id_karyawan]['jam_akhir'] = '';
					};
				} //else lembur proposional	
				if ($tgl_awal_lembur_ajuan >= $lembur->tgl_awal)
					$tgl_awal_lembur_ajuan = $lembur->tgl_awal;
			} else if (
				($lembur->status_appr_1 == 2 and $lembur->appr_2 == null)
				or  ($lembur->status_appr_1 == 2 and $lembur->status_appr_2 == 2)
				or  ($lembur->appr_1 == null and $lembur->status_appr_2 == 2)
				or  ($lembur->status_appr_1 == 1 and $lembur->status_appr_2 == 2)
			) {
				$rekap['total_lembur'][$lembur->p_karyawan_id]['total_tolak'] += (int)$lembur->lama;
			} else {
				$rekap['total_lembur'][$lembur->p_karyawan_id]['total_pending'] += (int)$lembur->lama;
				$rekap['total_lembur'][$lembur->p_karyawan_id]['tgl_pending'] .= $lembur->tgl_awal . ' | ';
			}
			//  }
		}
		$return['rekap'] = $rekap;
		$return['array_tgl'] = $array_tgl;
		$return['karyawan'] = $karyawan;
		return $return;
	}
	public static function rekap_klarifikasi($tgl_awal, $tgl_akhir, $where_karyawan_2)
	{
		$countklari        = DB::connection()->select("select * from chat_room where  tanggal >= '$tgl_awal' and  tanggal<='$tgl_akhir' $where_karyawan_2 ");
		$rekap = array();
		foreach ($countklari as $klarifi) {
			$rekap['list'][$klarifi->tanggal][$klarifi->p_karyawan_create_id]['topik'] = $klarifi->topik;
			$rekap['list'][$klarifi->tanggal][$klarifi->p_karyawan_create_id]['deskripsi'] = $klarifi->deskripsi;
			$rekap['list'][$klarifi->tanggal][$klarifi->p_karyawan_create_id]['selesai'] = $klarifi->selesai;
			$rekap['list'][$klarifi->tanggal][$klarifi->p_karyawan_create_id]['keterangan_hr'] = $klarifi->keterangan_hr;
			$rekap['list'][$klarifi->tanggal][$klarifi->p_karyawan_create_id]['keterangan_atasan'] = $klarifi->keterangan_atasan;
		}
		return $rekap;
	}
	public static function rekap_pergantian_hari_libur($tgl_awal, $tgl_akhir)
	{
		$sql = "select * from t_pergantian_hari_libur 
        		where 
        		(
        		    (tgl_pengganti_hari>='$tgl_awal' and tgl_pengganti_hari<='$tgl_akhir')
        		    OR
        		    (tgl_pengajuan>='$tgl_awal' and tgl_pengajuan<='$tgl_akhir')
        		 )
        		and status_appr=1
        		and active=1
        ";
		$pengganti_hari = DB::connection()->select($sql);
		$rekap = array();
		foreach ($pengganti_hari as $pengganti_hari) {
			$rekap['perganitan_hari_libur_awal'][$pengganti_hari->p_karyawan_id][$pengganti_hari->tgl_pengganti_hari] = true;
			$rekap['perganitan_hari_libur_awal'][$pengganti_hari->p_karyawan_id]['pengganti'][$pengganti_hari->tgl_pengganti_hari] = $pengganti_hari->tgl_pengajuan;
			$rekap['perganitan_hari_libur_ke'][$pengganti_hari->p_karyawan_id][$pengganti_hari->tgl_pengajuan] = true;
			$rekap['perganitan_hari_libur_ke'][$pengganti_hari->p_karyawan_id]['awal_libur'][$pengganti_hari->tgl_pengajuan] = $pengganti_hari->tgl_pengganti_hari;
		}
		return $rekap;
	}
}

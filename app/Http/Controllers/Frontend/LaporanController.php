<?php

namespace App\Http\Controllers\Frontend;
use App\Http\Controllers\Controller;

use App\rekaplembur_xls;
use App\User;
use App\Helper_function;
use Illuminate\Http\Request;
use DB;
use Auth;
use PhpOffice\PhpSpreadsheet\Reader\Xml\Style\Fill;
use Response;

class LaporanController extends Controller
{
    public function laporan_atasan ()
    {
    	
    	$help=new Helper_function();
		$iduser=Auth::user()->id;
        $sqlidkar="select * from p_karyawan 
        	join p_karyawan_pekerjaan on p_karyawan_pekerjaan.p_karyawan_id = p_karyawan.p_karyawan_id
        where user_id=$iduser";
        $idkar=DB::connection()->select($sqlidkar);
       // echo 'id_jabatan='.$idkar[0]->m_jabatan_id;
        //echo '<br>';
		$id_karyawan = $idkar[0]->p_karyawan_id;
		$bawahan = "((select p_karyawan_pekerjaan.p_karyawan_id from p_karyawan_pekerjaan where m_jabatan_id in (select m_jabatan_terkait from m_jabatan_struktural where tipe_struktural=2 and m_jabatan_id = (select m_jabatan_id from p_karyawan_pekerjaan pkp where p_karyawan_id = $id_karyawan))))";
		$tgl_awal= $help->tambah_bulan(date('Y-m-d'),-6);
		$tgl_akhir= date('Y-m-d');
    	$sql="SELECT * FROM m_periode_absen WHERE 1=1 and  tgl_awal>='$tgl_awal' and tgl_awal <='$tgl_akhir' and tipe_periode='absen' and active = 1
		order by tgl_awal
		";
		
        $periode_absen=DB::connection()->select($sql);
        $color =  array('#373651','#E65A26','#a1a1a1','#0078AA','#112B3C','#FCD900','#247881','#5584AC','#008E89','#DA1212','#FC9918','#577BC1','#373651','#E65A26','#a1a1a1','#0078AA','#112B3C','#FCD900','#247881','#5584AC','#008E89','#DA1212','#FC9918','#577BC1','#373651','#E65A26','#a1a1a1','#0078AA','#112B3C','#FCD900','#247881','#5584AC','#008E89','#DA1212','#FC9918','#577BC1','#373651','#E65A26','#a1a1a1','#0078AA','#112B3C','#FCD900','#247881','#5584AC','#008E89','#DA1212','#FC9918','#577BC1','#373651','#E65A26','#a1a1a1','#0078AA','#112B3C','#FCD900','#247881','#5584AC','#008E89','#DA1212','#FC9918','#577BC1');
        	$rekap = array();
        	$x1=-1;
        	$x2=-1;
        foreach($periode_absen as $perabsen){
        	
        	//echo '<br>';
        	//echo '<br>';
        	//echo '<br>';
			//echo $perabsen->periode_absen_id;
			$tgl_awal = $perabsen->tgl_awal;
			$tgl_akhir = $perabsen->tgl_akhir;
			
			$sqlabsen = "
			select * ,
				case 
				  when (select count(*) from absen_shift join absen on absen.absen_id = absen_shift.absen_id where absen.active = 1 and p_karyawan_id=c.p_karyawan_id and absen.tgl_awal<=a.date_time and absen.tgl_akhir>=a.date_time)>=1 
				  
				  then (select jam_masuk from absen_shift join absen on absen.absen_id = absen_shift.absen_id where absen.active = 1 and p_karyawan_id=107 and absen_shift.tanggal<=a.date_time and  absen_shift.tanggal>=a.date_time limit 1)
				 
				  else (select jam_masuk from absen where absen.tgl_awal<=a.date_time and  absen.tgl_akhir>=a.date_time and absen.m_lokasi_id = c.m_lokasi_id and shifting = 0 limit 1)
				end as jam_masuk
			
			from absen_log a 
			left join p_karyawan_absen b on b.no_absen = a.pin  
			left join p_karyawan_pekerjaan c on b.p_karyawan_id = c.p_karyawan_id  
			
			where a.date_time>='$tgl_awal' and a.date_time<='$tgl_akhir 23:59:59'
			and b.p_karyawan_id in($bawahan)
			";
			
			
			
		
			$absen=DB::connection()->select($sqlabsen);
			$rekap[$perabsen->periode_absen_id]['total']['ihk'] = 0;
			$rekap[$perabsen->periode_absen_id]['total']['ipg'] = 0;
			$rekap[$perabsen->periode_absen_id]['total']['ipd'] = 0;
			$rekap[$perabsen->periode_absen_id]['total']['sakit'] = 0;
			$rekap[$perabsen->periode_absen_id]['total']['cuti'] = 0;
			$rekap[$perabsen->periode_absen_id]['total']['lembur'] = 0;
			$rekap[$perabsen->periode_absen_id]['total']['absen_masuk'] = 0;
			$rekap[$perabsen->periode_absen_id]['total']['terlambat'] = 0;
			foreach($absen as $absen){
				$date = date('Y-m-d',strtotime($absen->date_time));
				$time = date('H:i:s',strtotime($absen->date_time));
				$time2 = date('H:i:s',strtotime($absen->date_time));
				if($absen->ver==1){
					
					
					if(!isset($rekap[$perabsen->periode_absen_id]['total']['absen_masuk']))
					$rekap[$perabsen->periode_absen_id]['total']['absen_masuk'] = 1;
					else
					$rekap[$perabsen->periode_absen_id]['total']['absen_masuk'] += 1;
					
					
					
					if($time> $absen->jam_masuk){
						
						if(isset($rekap[$perabsen->periode_absen_id]['total']['terlambat']))
							$rekap[$perabsen->periode_absen_id]['total']['terlambat'] += 1;
						else
							$rekap[$perabsen->periode_absen_id]['total']['terlambat'] = 1;
					}
					
				}
				
			}
			$sqllembur = "Select m_jenis_ijin.nama as nama_ijin,m_jenis_ijin.tipe,t_permit.m_jenis_ijin_id,t_permit.lama,t_permit.keterangan,t_permit.p_karyawan_id,t_permit.tgl_awal,t_permit.tgl_akhir,t_permit.jam_awal
			 from t_permit 
			 join m_jenis_ijin on m_jenis_ijin.m_jenis_ijin_id = t_permit.m_jenis_ijin_id 
			where t_permit.tgl_awal>='$tgl_awal' and 
				t_permit.tgl_awal<='$tgl_akhir 23:59' and 
				t_permit.m_jenis_ijin_id != 22 and t_permit.p_karyawan_id in($bawahan) and 
				t_permit.status_appr_1 = 1 ORDER BY t_permit.p_karyawan_id asc";
			$lembur=DB::connection()->select($sqllembur);
			$karyawan = array();
			
			//var_dump($lembur); die;
			foreach($lembur as $lembur){
				
				$date = $lembur->tgl_awal;
				for($i = 0; $i <= $help->hitunghari($lembur->tgl_awal,$lembur->tgl_akhir); $i++){
					if(!in_array($help->nama_hari($date),array('Minggu','Sabtu'))  ){
						if(in_array($lembur->m_jenis_ijin_id,array(4,7,12,13,14,15,16,17))){
							$rekap[$perabsen->periode_absen_id]['total']['ihk'] += 1;
						}else if($lembur->m_jenis_ijin_id==1){
							$rekap[$perabsen->periode_absen_id]['total']['ipg'] += 1;
						}else if($lembur->m_jenis_ijin_id==24){
							$rekap[$perabsen->periode_absen_id]['total']['ipd'] += 1;
						}else if($lembur->m_jenis_ijin_id==20){
							$rekap[$perabsen->periode_absen_id]['total']['sakit'] += 1;
						}
						if( $lembur->tipe==3){
							$rekap[$perabsen->periode_absen_id]['total']['cuti'] += 1;
						}
											
							
				}
					$date = $help->tambah_tanggal($date,1);
					
						
				}
				
			}
			$sqllembur = "Select *
			
			from t_permit 
				where tgl_awal>='$tgl_awal' 
					and tgl_awal<='$tgl_akhir 23:00' 
					and m_jenis_ijin_id = 22  
					and t_permit.p_karyawan_id in($bawahan) 
				ORDER BY p_karyawan_id asc";
			$lembur=DB::connection()->select($sqllembur);
			//$karyawan = array();
			//$rekap = array();
		
			//var_dump($lembur); die;status_appr_1 = 1
			foreach($lembur as $lembur){
				
				if($lembur->status_appr_1==1)
					$rekap[$perabsen->periode_absen_id]['total']['lembur'] += 1;
					
			}
		}
		//print_r($rekap);
		 $data_pekanan=array();
		 $data_bulanan=array();
		 foreach($periode_absen as $perabsen){
		 	$cuti = isset($rekap[$perabsen->periode_absen_id]['total']['cuti'])? $rekap[$perabsen->periode_absen_id]['total']['cuti']:0;
		 	$ipg = isset($rekap[$perabsen->periode_absen_id]['total']['ipg'])?$rekap[$perabsen->periode_absen_id]['total']['ipg']:0;
		 	$ihk = isset($rekap[$perabsen->periode_absen_id]['total']['ihk'])?$rekap[$perabsen->periode_absen_id]['total']['ihk']:0;
		 	$ipd = isset($rekap[$perabsen->periode_absen_id]['total']['ipd'])?$rekap[$perabsen->periode_absen_id]['total']['ipd']:0;
		 	$terlambat = isset($rekap[$perabsen->periode_absen_id]['total']['terlambat'])?$rekap[$perabsen->periode_absen_id]['total']['terlambat']:0;
		 	$absen_masuk = isset($rekap[$perabsen->periode_absen_id]['total']['absen_masuk'])?$rekap[$perabsen->periode_absen_id]['total']['absen_masuk']:0;
		 	$lembur2 = isset($rekap[$perabsen->periode_absen_id]['total']['lembur'])?$rekap[$perabsen->periode_absen_id]['total']['lembur']:0;
		 	
		 	if($perabsen->type ==0){
					$bulan='';
					$x2++;
				if($help->bulan(date('m',strtotime($perabsen->tgl_awal)))!= $help->bulan(date('m',strtotime($perabsen->tgl_akhir))))
					$bulan=$help->bulan(date('m',strtotime($perabsen->tgl_awal)));
			 	$data_pekanan[] = array("label"=> 
			 			date('d',strtotime($perabsen->tgl_awal)).' '.$bulan.' s/d '.
			 			date('d',strtotime($perabsen->tgl_akhir)).' '.$help->bulan(date('m',strtotime($perabsen->tgl_akhir))).' '.
			 			$perabsen->tahun
			 			
			 	
			 	, 
			 	"data"=> array($absen_masuk, $terlambat,$ipd,$ihk,$ipg,$cuti,$lembur2),
		 		"fill"=> false,
		 		"borderColor"=> $color[$x2],
		 		"backgroundColor"=> $color[$x2],
		 		"borderWidth"=> 1,
			 	);
			}else{
				$x1++;
				$data_bulanan[] = array("label"=> $help->bulan($perabsen->periode).' '.$perabsen->tahun, 
		 		"data"=> array($absen_masuk, $terlambat,$ipd,$ihk,$ipg,$cuti,$lembur2),
		 		"fill"=> false,
		 		"borderColor"=> $color[$x1],
		 		"backgroundColor"=> $color[$x1],
		 		"borderWidth"=> 1,
		 	);
			}
			
		 }
		
		
		 //ykeys: ['a', 'b'],
		//labels: ['Total Sales', 'Total Revenue'],
	
		 $datalabel = ['Absen','Telambat','Perjalanan Dinas','IHK','IPG','Cuti','Lembur'];
		 $ykeys = ['absen','terlambat','perdin','ihk','ipg','cuti','lembur'];
			$graph['pekanan']['data'] = json_encode($data_pekanan);
			$graph['bulanan']['data'] = json_encode($data_bulanan);
			$graph['labelabsen'] = json_encode($datalabel);
			$graph['ykeys'] = json_encode($ykeys);
			
		//print_r($graph);die;
		
		$sql = "select (SELECT count(*) from p_karyawan_pekerjaan c join m_jabatan on m_jabatan.m_jabatan_id = c.m_jabatan_id join p_karyawan d on d.p_karyawan_id = c.p_karyawan_id and d.active=1 WHERE m_pangkat.m_pangkat_id= m_jabatan.m_pangkat_id and c.p_karyawan_id in($bawahan)) as jumlah,m_pangkat.nama
from m_pangkat 
where active= 1
";
		$karyawan_pangkat= DB::connection()->select($sql);
			$x1=0;
			foreach($karyawan_pangkat as $ke){
				
				$datalabelpangkat[] = $ke->nama;
				$datax[] = $ke->jumlah;
			}
			$data2[] = array(
					"label"=> 'Jumlah Karyawan', 
			 		"data"=> $datax,
			 		"fill"=> false,
			 		"borderColor"=> $color,
			 		"backgroundColor"=> $color,
			 		"borderWidth"=> 1,
		 		);
			
			$graph['pangkat']['label'] = json_encode($datalabelpangkat);
			$graph['pangkat']['data'] = json_encode($data2);
			
			
		$sql = "select (SELECT count(*) from p_recruitment c 
			join p_karyawan d on d.p_recruitment_id = c.p_recruitment_id and d.active=1 
		WHERE c.m_status_id= m_status.m_status_id and d.p_karyawan_id in($bawahan)
		) as jumlah,m_status.nama
		from m_status ";
		$karyawan_pernikahan= DB::connection()->select($sql);
		
			foreach($karyawan_pernikahan as $ke){
				$datalabelnikah[] = $ke->nama;
				$datax1[] = $ke->jumlah;
			}
			$data3[] = array(
					"label"=> 'Jumlah Karyawan', 
			 		"data"=> $datax1,
			 		"fill"=> false,
			 		"borderColor"=> $color,
			 		"backgroundColor"=> $color,
			 		"borderWidth"=> 1,
		 		);
				
			$graph['pernikahan']['label'] = json_encode($datalabelnikah);
			$graph['pernikahan']['data'] = json_encode($data3);
			
		$sql = "select (SELECT count(*) from p_recruitment c 
	join p_karyawan d on d.p_recruitment_id = c.p_recruitment_id and d.active=1 
		WHERE c.m_jenis_kelamin_id= m_jenis_kelamin.m_jenis_kelamin_id and d.p_karyawan_id in($bawahan)
	) as jumlah,m_jenis_kelamin.nama
from m_jenis_kelamin
WHERE m_jenis_kelamin.active= 1

";
			$karyawan_kelamin= DB::connection()->select($sql);
			foreach($karyawan_kelamin as $ke){
				$datalabelkelamin[] = $ke->nama;
				$datax2[] = $ke->jumlah;
			}
			$data4[] = array(
					"label"=> 'Jumlah Karyawan', 
			 		"data"=> $datax2,
			 		"fill"=> false,
			 		"borderColor"=> $color,
			 		"backgroundColor"=> $color,
			 		"borderWidth"=> 1,
		 		);
				
			$graph['kelamin']['label'] = json_encode($datalabelkelamin);
			$graph['kelamin']['data'] = json_encode($data4);
				
		
			//$graph['kelamin'] = json_encode($data4);
			$sql = "select (SELECT count(*) from p_karyawan a 
				JOIN p_karyawan_kontrak h on h.p_karyawan_id=a.p_karyawan_id
					WHERE h.m_status_pekerjaan_id= m_status_pekerjaan.m_status_pekerjaan_id and a.active=1 and a.p_karyawan_id in($bawahan)
				) as jumlah,m_status_pekerjaan.nama
			from m_status_pekerjaan
			WHERE m_status_pekerjaan.active= 1

			";
			$karyawan_status_kerja= DB::connection()->select($sql);
			foreach($karyawan_status_kerja as $ke){
				$datalabelkerja[] = $ke->nama;
				$datax4[] = $ke->jumlah;
			}
			$data5[] = array(
					"label"=> 'Jumlah Karyawan', 
			 		"data"=> $datax4,
			 		"fill"=> false,
			 		"borderColor"=> $color,
			 		"backgroundColor"=> $color,
			 		"borderWidth"=> 1,
		 		);
				
			$graph['status_kerja']['label'] = json_encode($datalabelkerja);
			$graph['status_kerja']['data'] = json_encode($data5);
				
				
        return view('frontend.laporan.laporan',compact('graph'));
    }
	public function hirarki($id,$e)
    {
    		//echo 'e adalah'.$e.'id adalah '.$id.'<br>';
		$filter_Entitas = '';
		
			
      	 $sqljabatan="SELECT *,(select count(*) from m_jabatan_atasan where m_jabatan_atasan.m_atasan_id = a.m_jabatan_id) as countjabatan
      	 FROM m_jabatan_atasan a 
      	 join m_jabatan b on b.m_jabatan_id = a.m_jabatan_id 
      	 where m_atasan_id = $id $filter_Entitas";
      	//echo $sqljabatan;
      	 $jabatan=DB::connection()->select($sqljabatan);
		  $return = array(); 
      	
      	
      	 foreach($jabatan as $j){
      	 	$Mjabatan = $j-> m_jabatan_id;
      	 	$sqljabatan="SELECT * FROM p_karyawan_pekerjaan a 
	      	 join p_karyawan b on b.p_karyawan_id = a.p_karyawan_id 
	      	 where m_jabatan_id = $Mjabatan and b.active=1";
	      	 
	      	//echo $sqljabatan;
	      	//echo '<br>';
	      	 $karyawan=DB::connection()->select($sqljabatan);
	      	 $name='';
	      	 if(count($karyawan)){
			 	
      	 	foreach($karyawan as $k){
				//echo $k->p_karyawan_id;
				//echo '<br>';
				$e .= $k->p_karyawan_id.',';
				if($j->countjabatan ){
					
      				
      				$e .= $this->hirarki($j->m_jabatan_id,$e);
				}
		 		
				
			}
			 }else{
			 	
      	 	if($j->countjabatan){
				$e .= $this->hirarki($j->m_jabatan_id,$e);
			}
      		//$this->hirarki($j->m_jabatan_id,$e);
		 	 
      		
			 }
		 	
		 }
		// echo '<br>';
		 //echo '<br>';
		// print_r($e);
		 return $e; 
		// print_r($return);
		
    }
	
}
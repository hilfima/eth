<?php
	
//require('plugins\spreadsheet-reader-master\php-excel-reader/excel_reader2.php');
//require('plugins\spreadsheet-reader-master\SpreadsheetReader.php');
//$Reader = new SpreadsheetReader($uploadFilePath);
$file_open = fopen($uploadFilePath,"r");
//print_r(fgetcsv($file_open, 1000, ";"));
		$idUser=Auth::user()->id;
				DB::connection()->table("p_karyawan_koperasi")
               ->where("active",1)
                ->update([
                   
                   
                    "active"=>0,
                   
                ]);
                $i=0;
               
 while(($csv = fgetcsv($file_open, 1000, ";")) !== false)
 {
                $i++;
 	//prinNIK	Nama Karyawan	KARYAWAN SHIFT	SHIFT KE	TANGGAL AWAL	TANGGAL AKHIR	JAM MASUK	JAM KELUAR	KETERANGAN

 	if($i!=1 and $i!=2 and $i!=3  ){
		
  $NIK = isset($csv[0])?$csv[0]:0;
  $name = str_replace("'","%%",$csv[1]);
  $KARYAWANSHIFT = isset($csv[2])?$csv[2]:0;
  $SHIFTKE = isset($csv[3])?$csv[3]:0;
  $tgl_awal= isset($csv[4])?$help->tanggal_format_from_csv(($csv[4])):0;
  $tgl_akhir= isset($csv[5])?$help->tanggal_format_from_csv(($csv[5])):0;
  $JAMMASUK= isset($csv[6])?date('H:i:s',strtotime($csv[6])):0;
  $JAMKELUAR= isset($csv[7])?date('H:i:s',strtotime($csv[7])):0;
  $KETERANGAN= isset($csv[8])?$csv[8]:0;
 // $country = $csv[2];
 //echo $csv[4];
 $sqluser="SELECT * FROM p_karyawan 
 		left join p_karyawan_pekerjaan a on a.p_karyawan_id = p_karyawan.p_karyawan_id
  		where nama like '%$name%'";
  		//echo $sqluser;
 $user=DB::connection()->select($sqluser);
 //print_r($user);
        if(count($user) and $KARYAWANSHIFT){
        	echo 'masuk';
        	$id_lokasi = $user[0]->m_lokasi_id;
			$sql="SELECT * FROM absen where m_lokasi_id=$id_lokasi and tgl_awal = '$tgl_awal' and tgl_akhir = '$tgl_akhir'
				and jam_masuk='$JAMMASUK' and jam_keluar='$JAMKELUAR' and keterangan='$KETERANGAN'
			  ";
 			$absen=DB::connection()->select($sql);
 			//print_r($absen);
 			if(count($absen)){
				$id = $absen[0]->absen_id;
			}else{	
				
 				DB::connection()->table("absen")
                ->insert([
                    
                    "m_lokasi_id"=>$id_lokasi,
                    "jam_masuk"=>$JAMMASUK,
                    "jam_keluar"=>$JAMKELUAR,
                    "shifting"=>1,
                    "tgl_awal"=>date('Y-m-d',strtotime($tgl_awal)), 
                    "tgl_akhir"=>date('Y-m-d',strtotime($tgl_akhir)), 
                    "keterangan"=>$KETERANGAN,
                    "create_date" => date("Y-m-d H:i:s"),
                    "create_by" => $idUser
                ]);
                
                $SQL = " SELECT currval('hrm.seq_absen')";
                $shift=DB::connection()->select($SQL);
//                print_r($shift);
                $id = $shift[0]->currval;
                //gapok
			}
			
				$date = $tgl_awal;
				for($j=0;$j<=$help->hitungHari($tgl_awal,$tgl_akhir);$j++){
					echo '<br>'.$date;
					 DB::connection()->table("absen_shift")
		                ->insert([
		                    "absen_id"=>$id,
		                    "p_karyawan_id"=>$user[0]->p_karyawan_id,
		                    "tanggal"=>($date),
		                    "create_date" => date("Y-m-d H:i:s"),
		                    "created_by" => $idUser
		                ]);
							
				$date = $help->tambah_tanggal($date,1);
				}
          	
		}
		}

 // mysql_query("INSERT INTO employee VALUES ('$name','$age','country')");
 }

       // header("Location: " . URL::to('/backend/gapok'), true, 302);
//
       
  ;
?><script>window.location = "{{ route('fe.shift') }}";</script>
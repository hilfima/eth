<?php
	
//require('plugins\spreadsheet-reader-master\php-excel-reader/excel_reader2.php');
//require('plugins\spreadsheet-reader-master\SpreadsheetReader.php');
//$Reader = new SpreadsheetReader($uploadFilePath);
$file_open = fopen($uploadFilePath,"r");
//print_r(fgetcsv($file_open, 1000, ";"));
$karyawan = "select DISTINCT(a.p_karyawan_id) from p_karyawan a join p_karyawan_pekerjaan c on a.p_karyawan_id = c.p_karyawan_id where a.active=1 and c.periode_gajian= 1";
$list_karyawan=DB::connection()->select($karyawan);
foreach($list_karyawan as $karyawan){
	$kary[] = $karyawan->p_karyawan_id;
}

		$idUser=Auth::user()->id;
				DB::connection()->table("p_karyawan_gapok")
               ->whereIn("p_karyawan_id",$kary)
                ->update([
                   
                   
                    "active"=>0,
                   
                ]);DB::connection()->table("prl_tunjangan")
                ->whereIn("p_karyawan_id",$kary)
                ->update([
                   
                   
                    "active"=>0,
                    
                    "updated_date" => date("Y-m-d H:i:s")
                ]);DB::connection()->table("prl_potongan")
                ->whereIn("p_karyawan_id",$kary)
                ->update([
                   
                   
                    "active"=>0,
                   
                    "updated_date" => date("Y-m-d H:i:s")
                ]);
 while(($csv = fgetcsv($file_open, 1000, ";")) !== false)
 {
 	//prin
 	
  $name 				= str_replace("'","%%",$csv[3]);
 	$x=3;
  $x++;
  echo '<br>gapok:';echo $gapok 				= isset($csv[$x])?$csv[$x]:0;
  $x++;
  echo '<br>$tunjangan_grade:';echo $tunjangan_grade 		= isset($csv[$x])?$csv[$x]:0;;
  $x++;
 echo '<br>$tunjangan_bpjskes:';echo  $tunjangan_bpjskes 	= isset($csv[$x])?$csv[$x]:0;;
  $x++;
  echo '<br>$tunjangan_bpjsket:';echo $tunjangan_bpjsket 	=isset($csv[$x])?$csv[$x]:0;;
  $x++;
  echo '<br>$tunjangan_kost:';echo $tunjangan_kost 		= isset($csv[$x])?$csv[$x]:0;;
  $x++;
  echo '<br>$sewa_kost:';echo $sewa_kost 			= isset($csv[$x])?$csv[$x]:0;;
  $x++;
  echo '<br>$iuran_bpjskes:';echo $iuran_bpjskes 		= isset($csv[$x])?$csv[$x]:0;;
  $x++;
  echo '<br>$iuran_bpjsket:';echo $iuran_bpjsket 		=isset($csv[$x])?$csv$x]:0;;
  $x++;
  echo '<br>$pajak:';echo $pajak 				= isset($csv[$x])?$csv[$x]:0;;;
  $x++;
  echo '<br>$infaq:';echo $kkb = isset($csv[$x])?$csv[$x]:0;;;
  $x++;
  echo '<br>$infaq:';echo $infaq = isset($csv[$x])?$csv[$x]:0;;;
  $x++;
  echo '<br>$zakat:';echo $zakat = isset($csv[$x])?$csv[$x]:0;;;
  $x++;
 	$cuti21 = isset($csv[$x])?$csv[$x]:0;;;$x++;
 	$cuti22 = isset($csv[$x])?$csv[$x]:0;;;$x++;
 	$cutibesar = isset($csv[$x])?$csv[$x]:0;;;$x++;
 	$kantor = isset($csv[$x])?$csv[$x]:0;;;
 	$x++;$bank = isset($csv[$x])?$csv[$x]:0;;;
 	$x++;$norek = isset($csv[$x])?$csv[$x]:0;;;
 	$x++;$pemilik = isset($csv[$x])?$csv[$x]:0;;;
 	$x++;$pajak = isset($csv[$x])?$csv[$x]:0;;;
 	$x++;$faskes = isset($csv[$x])?$csv[$x]:0;;;
 	$x++;$job = isset($csv[$x])?$csv[$x]:0;;;
 	
 	
 	
 // $country = $csv[2];
 $sqluser="SELECT * FROM p_karyawan where nama like '%$name%'";
 $user=DB::connection()->select($sqluser);
 
        if(count($user)){
			 
 				
                //gapok
            DB::connection()->table("prl_tunjangan")
                ->insert([
                    "nominal"=>$help->hapusRupiah($gapok),
                    "p_karyawan_id"=>$user[0]->p_karyawan_id,
                    "m_tunjangan_id"=>(17),
                    "is_gapok"=>(1),
                    "active"=>(1),
                   
                    "created_date" => date("Y-m-d H:i:s")
                ]);
               
		}

 // mysql_query("INSERT INTO employee VALUES ('$name','$age','country')");
 }

       // header("Location: " . URL::to('/backend/gapok'), true, 302);
       
  ;
?>
<script>window.location = "{{ route('be.gapok') }}";</script>
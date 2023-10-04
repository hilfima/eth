<?php
	
//require('plugins\spreadsheet-reader-master\php-excel-reader/excel_reader2.php');
//require('plugins\spreadsheet-reader-master\SpreadsheetReader.php');
//$Reader = new SpreadsheetReader($uploadFilePath);
$file_open = fopen($uploadFilePath,"r");
//print_r(fgetcsv($file_open, 1000, ";"));
		$idUser=Auth::user()->id;
				DB::connection()->table("p_karyawan_koperasi")
               ->where("active",1)
			   ->where("nama_koperasi" ,strtoupper($page))
                ->update([
                   
                   
                    "active"=>0,
                   
                ]);
 while(($csv = fgetcsv($file_open, 1000, ";")) !== false)
 {
 	//prin
 	$i=0;
  $nik = str_replace("'","%%",$csv[$i]);$i++;
  $name = str_replace("'","%%",$csv[$i]);$i++;
  $entitas = isset($csv[$i])?$csv[$i]:0;$i++;
  $pajak = isset($csv[$i])?$csv[$i]:0;$i++;
  $periode = isset($csv[$i])?$csv[$i]:0;$i++;
  $no_anggota = isset($csv[$i])?$csv[$i]:0;$i++;
  $nominal = isset($csv[$i])?$csv[$i]:0;$i++;
  $tgl_awal= isset($csv[$i])?$csv[$i]:0;$i++;
  $tgl_akhir= isset($csv[$i])?$csv[$i]:0;$i++;
 // $country = $csv[2];
 $sqluser = "SELECT * FROM p_karyawan where (nik = '0$nik')";
    //echo $sqluser;echo '<br>';
    $user = DB::connection()->select($sqluser);
    if (!count($user)) {
        $sqluser = "SELECT * FROM p_karyawan where (nik = '$nik')";
        //echo $sqluser;echo '<br>';
        $user = DB::connection()->select($sqluser);
    }
    if (!count($user)) {
        $sqluser = "SELECT * FROM p_karyawan where (nama like '$name')";
        //echo $sqluser;echo '<br>';
        $user = DB::connection()->select($sqluser);
    }
    
    if(count($user) and $nominal){
			$koperasi=DB::connection()->select("select * from p_karyawan_koperasi where nama_koperasi='".strtoupper($page)."' and p_karyawan_id='".$user[0]->p_karyawan_id."' and active=1");
	  if(count($koperasi)){
	  	DB::connection()->table("p_karyawan_koperasi")
	  		->where('nama_koperasi',strtoupper($page))
	  		->where('p_karyawan_id',$user[0]->p_karyawan_id)
            ->update([
               
                "p_karyawan_id" => $user[0]->p_karyawan_id,
                "no_anggota" => $no_anggota,
                "nominal" => $help->hapusrupiah($nominal),
                "tgl_awal" => date('Y-m-d',strtotime($tgl_awal)),
                "tgl_akhir" => date('Y-m-d',strtotime($tgl_akhir)),
                "updated_date" => date('Y-m-d H:i:s'),
                "updated_by" => $idUser,
                
            ]);
	  }else{
	  	
         DB::connection()->table("p_karyawan_koperasi")
            ->insert([
                "p_karyawan_id" => $user[0]->p_karyawan_id,
                "no_anggota" => $no_anggota,
				"nama_koperasi" => strtoupper($page),
                "nominal" => $help->hapusrupiah($nominal),
                "tgl_awal" => date('Y-m-d',strtotime($tgl_awal)),
                "tgl_akhir" => date('Y-m-d',strtotime($tgl_akhir)),
                "created_date" => date('Y-m-d H:i:s'),
                "created_by" => $idUser,
                
            ]);
	  }
	  
 				
                //gapok
           
		}

 // mysql_query("INSERT INTO employee VALUES ('$name','$age','country')");
 }

       // header("Location: " . URL::to('/backend/gapok'), true, 302);
       
  ;
?>
<script>window.location = "{{ route('be.koperasi',$page) }}";</script>
<?php
	
//require('plugins\spreadsheet-reader-master\php-excel-reader/excel_reader2.php');
//require('plugins\spreadsheet-reader-master\SpreadsheetReader.php');
//$Reader = new SpreadsheetReader($uploadFilePath);
$file_open = fopen($uploadFilePath,"r");
//print_r(fgetcsv($file_open, 1000, ";"));
		$idUser=Auth::user()->id;
				DB::connection()->table("prl_potongan")
               ->where("m_potongan_id",20)
                ->update([
                   
                   
                    "active"=>0,
                   
                ]);
 while(($csv = fgetcsv($file_open, 1000, ";")) !== false)
 {
 	//prin
 	
  $name = str_replace("'","%%",$csv[1]);
 
  $nominal = isset($csv[2])?$csv[2]:0;
 // $country = $csv[2];
 $sqluser="SELECT * FROM p_karyawan where nama like '%$name%'";
 $user=DB::connection()->select($sqluser);
 
        if(count($user)){
			 
 				 $sql = "Select * from p_karyawan_gapok where p_karyawan_id=".$user[0]->p_karyawan_id;
				  $pajak=DB::connection()->select($sql);
				  if(count($pajak)){
				  		DB::connection()->table("p_karyawan_gapok")
			            ->where('p_karyawan_gapok_id',$pajak[0]->p_karyawan_gapok_id)
			            ->update([
			               
			               
			               
			                "pajak" => $help->hapusrupiah($nominal),
			                
			                "updated_date" => date('Y-m-d'),
			                "updated_by" => $idUser,
			                
			            ]);
				  }else{
				  	 DB::connection()->table("p_karyawan_gapok")
			            ->insert([
			               
			                "p_karyawan_id" => $user[0]->p_karyawan_id,
			               
			                "pajak" => $help->hapusrupiah($nominal),
			                
			                "created_date" => date('Y-m-d'),
			                
			            ]);
				  }
			         DB::connection()->table("prl_potongan")
			            ->insert([
			                "m_potongan_id" => 20,
			                "p_karyawan_id" => $user[0]->p_karyawan_id,
			               
			                "nominal" => $help->hapusrupiah($nominal),
			                
			                "created_date" => date('Y-m-d'),
			                "active" => 1,
			                
			            ]);
 
                //gapok
           
		}

 // mysql_query("INSERT INTO employee VALUES ('$name','$age','country')");
 }

       // header("Location: " . URL::to('/backend/gapok'), true, 302);
       
  ;
?>
<script>window.location = "{!! route('be.pajak') !!}";</script>
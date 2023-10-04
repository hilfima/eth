<?php
	
//require('plugins\spreadsheet-reader-master\php-excel-reader/excel_reader2.php');
//require('plugins\spreadsheet-reader-master\SpreadsheetReader.php');
//$Reader = new SpreadsheetReader($uploadFilePath);
$file_open = fopen($uploadFilePath,"r");
//print_r(fgetcsv($file_open, 1000, ";"));
$karyawan = "select DISTINCT(a.p_karyawan_id) from p_karyawan a join p_karyawan_pekerjaan c on a.p_karyawan_id = c.p_karyawan_id where a.active=1 and c.periode_gajian= 0";
$list_karyawan=DB::connection()->select($karyawan);
foreach($list_karyawan as $karyawan){
	$kary[] = $karyawan->p_karyawan_id;
}
		$iduser=Auth::user()->id;
				DB::connection()->table("p_karyawan_gapok")
               ->whereIn("p_karyawan_id",$kary)
                ->update([
                   
                   
                    "active"=>0,
                   
                ]);
               
                DB::connection()->table("prl_tunjangan")
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
 	$ii=0;
 	
  $nik =  isset($csv[$ii])?$csv[$ii]:0; $ii++;
  $name = str_replace("'","%%",$csv[$ii]); $ii++;
  $uh = isset($csv[$ii])?$csv[$ii]:0; $ii++;
 // $tunjangan_kost = isset($csv[$ii])?$csv[$ii]:0;; $ii++;
  $infaq = isset($csv[$ii])?$csv[$ii]:0;;; $ii++;
  $zakat = isset($csv[$ii])?$csv[$ii]:0;;; $ii++;
  /*$sewa_kost = isset($csv[$ii])?$csv[$ii]:0;; $ii++;
  $pajak = isset($csv[$ii])?$csv[$ii]:0;;; $ii++;
  $kkb = isset($csv[$ii])?$csv[$ii]:0;;; $ii++;
  $korekplus = isset($csv[$ii])?$csv[$ii]:0;;;$ii++;
  $korekmin = isset($csv[$ii])?$csv[$ii]:0;;;$ii++;*/
 // $country = $csv[2];
 $sqluser="SELECT * FROM p_karyawan where (nik = '0$nik')";
 //echo $sqluser;echo '<br>';
 $user=DB::connection()->select($sqluser);
 if(!count($user)){
 	$sqluser="SELECT * FROM p_karyawan where (nik = '$nik')";
 //echo $sqluser;echo '<br>';
 	$user=DB::connection()->select($sqluser);
 }
        if(count($user)){
			
 				DB::connection()->table("p_karyawan_gapok")
                ->insert([
                    "upah_harian"=>$help->hapusRupiah($uh),
                    "p_karyawan_id"=>$user[0]->p_karyawan_id,
                    //"tunjangan_kost"=>$help->hapusRupiah($tunjangan_kost), 
                    //"sewa_kost"=>$help->hapusRupiah($sewa_kost),
                    //"pajak"=>$help->hapusRupiah($pajak),
                    //"koperasi_kkb"=>$help->hapusRupiah($kkb),
                    "infaq"=>$help->hapusRupiah($infaq),
                    "zakat"=>$help->hapusRupiah($zakat),
					//"korekplus"=>$help->hapusRupiah($korekplus),
					//"korekmin"=>$help->hapusRupiah($korekmin),
                    "active"=>(1),
                    
                    "created_date" => date("Y-m-d H:i:s"),
                    "created_by" => $iduser
                ]);
                //gapok
            DB::connection()->table("prl_tunjangan")
                ->insert([
                    "nominal"=>$help->hapusRupiah($uh),
                    "p_karyawan_id"=>$user[0]->p_karyawan_id,
                    "m_tunjangan_id"=>(18),
                    "is_gapok"=>(1),
                    "active"=>(1),
                   
                    "created_date" => date("Y-m-d H:i:s")
                ]);
				
           
	           /* DB::connection()->table("prl_tunjangan")
	                ->insert([
	                    "nominal"=>$help->hapusRupiah($tunjangan_kost),
	                    "p_karyawan_id"=>$user[0]->p_karyawan_id,
	                    "m_tunjangan_id"=>(14),
	                    "active"=>(1),
	                   
	                    "created_date" => date("Y-m-d H:i:s")
	                ]);*/
	                
	          /* 
                DB::connection()->table("prl_potongan")
                ->insert([
                    "nominal"=>$help->hapusRupiah($infaq),
                    "p_karyawan_id"=>$user[0]->p_karyawan_id,
                    "m_potongan_id"=>(19),
                    "active"=>(1),
                   
                    "created_date" => date("Y-m-d H:i:s")
                ]);
                DB::connection()->table("prl_potongan")
                ->insert([
                    "nominal"=>$help->hapusRupiah($zakat),
                    "p_karyawan_id"=>$user[0]->p_karyawan_id,
                    "m_potongan_id"=>(18),
                    "active"=>(1),
                   
                    "created_date" => date("Y-m-d H:i:s")
                ]);*/
                
                
                
               /*  DB::connection()->table("prl_tunjangan")
				->insert([
				"nominal"=>$help->hapusRupiah($korekplus),
				"p_karyawan_id"=>$user[0]->p_karyawan_id,
				"m_tunjangan_id"=>(16),
				"is_gapok"=>(0),
				"active"=>(1),

				"created_date" => date("Y-m-d H:i:s")
				]);     
            DB::connection()->table("prl_potongan")
                ->insert([
                    "nominal"=>$help->hapusRupiah($sewa_kost),
                    "p_karyawan_id"=>$user[0]->p_karyawan_id,
                    "m_potongan_id"=>(16),
                    "active"=>(1),
                   
                    "created_date" => date("Y-m-d H:i:s")
                ]);
				DB::connection()->table("prl_potongan")
				->insert([
				"nominal"=>$help->hapusRupiah($korekmin),
				"p_karyawan_id"=>$user[0]->p_karyawan_id,
				"m_potongan_id"=>(17),
				"active"=>(1),

				"created_date" => date("Y-m-d H:i:s")
				]);
            DB::connection()->table("prl_potongan")
                ->insert([
                    "nominal"=>$help->hapusRupiah($pajak),
                    "p_karyawan_id"=>$user[0]->p_karyawan_id,
                    "m_potongan_id"=>(20),
                    "active"=>(1),
                   
                    "created_date" => date("Y-m-d H:i:s")
                ]); 
                DB::connection()->table("prl_potongan")
                ->insert([
                    "nominal"=>$help->hapusRupiah($kkb),
                    "p_karyawan_id"=>$user[0]->p_karyawan_id,
                    "m_potongan_id"=>(9),
                    "active"=>(1),
                   
                    "created_date" => date("Y-m-d H:i:s")
                ]);*/
                
		}

 // mysql_query("INSERT INTO employee VALUES ('$name','$age','country')");
 }

 // header("Location: " . URL::to('/backend/gapok'), true, 302);
 //
       
  ;
  //
  ?>
  <script>window.location = "{{ route('be.gapok_pekanan') }}"; </script>

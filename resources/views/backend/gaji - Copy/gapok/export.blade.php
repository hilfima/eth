<?php

//require('plugins\spreadsheet-reader-master\php-excel-reader/excel_reader2.php');
//require('plugins\spreadsheet-reader-master\SpreadsheetReader.php');
//$Reader = new SpreadsheetReader($uploadFilePath);
$file_open = fopen($uploadFilePath, "r");
//print_r(fgetcsv($file_open, 1000, ";"));
if($type=='bulanan'){
    		$periode = 1;
    	}else{
    		$periode = 0;
    		
    	}
$karyawan = "select DISTINCT(a.p_karyawan_id) from p_karyawan a join p_karyawan_pekerjaan c on a.p_karyawan_id = c.p_karyawan_id where a.active=1 and c.periode_gajian= $periode";
$list_karyawan = DB::connection()->select($karyawan);
foreach ($list_karyawan as $karyawan) {
    $kary[] = $karyawan->p_karyawan_id;
}

$idUser = Auth::user()->id;
DB::connection()->table("p_karyawan_gapok")
    ->whereIn("p_karyawan_id", $kary)
    ->update([


        "active" => 0,

    ]);
DB::connection()->table("prl_tunjangan")
    ->whereIn("p_karyawan_id", $kary)
    ->update([


        "active" => 0,

        "updated_date" => date("Y-m-d H:i:s")
    ]);
DB::connection()->table("prl_potongan")
    ->whereIn("p_karyawan_id", $kary)
    ->update([


        "active" => 0,

        "updated_date" => date("Y-m-d H:i:s")
    ]);
while (($csv = fgetcsv($file_open, 1000, ";")) !== false) {
    //prin
    $i = 0;
    $nik = $csv[$i];
    $i++;
    $name = str_replace("'", "%%", $csv[$i]);
    $i++;
   for($y=0;$y<count($array);$y++){
	       	 	$row = $array[$y][1];
	       	 	$$row  = isset($csv[$i]) ? $csv[$i]? $csv[$i] : 0 : 0;
    			$i++;
	       	 	
	}
   
    $sqluser = "SELECT * FROM p_karyawan where (nik = '0$nik')";
    $user = DB::connection()->select($sqluser);
    if (!count($user)) {
        $sqluser = "SELECT * FROM p_karyawan where (nik = '$nik')";
        $user = DB::connection()->select($sqluser);
    }
    if (!count($user)) {
        $sqluser = "SELECT * FROM p_karyawan where (nama like '$name')";
        $user = DB::connection()->select($sqluser);
    }
    if (count($user)) {
$input = array();
		$input['p_karyawan_id'] =$user[0]->p_karyawan_id;
		$input['active'] =1;
		$input['created_date'] =date("Y-m-d H:i:s");
		$input['created_by'] =$idUser;
		for($y=0;$y<count($array);$y++){
	       	 	$row = $array[$y][1];
	       	 	$input[$row] =$$row;
	    }
        DB::connection()->table("p_karyawan_gapok")
            ->insert($input);
        //gapok
        for($y=0;$y<count($array);$y++){
        	$row = $array[$y][1];
        DB::connection()->table("prl_".$array[$y][2])
            ->insert([
                "nominal" => $help->hapusRupiah($$row),
                "p_karyawan_id" => $user[0]->p_karyawan_id,
                "m_".$array[$y][2]."_id" => ($array[$y][3]),
                "is_gapok" => $array[$y][4],
                "active" => (1),

                "created_date" => date("Y-m-d H:i:s"),
                "created_by" => $idUser
            ]);
		}
    }

    // mysql_query("INSERT INTO employee VALUES ('$name','$age','country')");
}

    // header("Location: " . URL::to('/backend/gapok'), true, 302);

;
?>
<script>
    window.location = "{{ route('be.gapok',$type) }}";
</script>
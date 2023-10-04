<?php

//require('plugins\spreadsheet-reader-master\php-excel-reader/excel_reader2.php');
//require('plugins\spreadsheet-reader-master\SpreadsheetReader.php');
//$Reader = new SpreadsheetReader($uploadFilePath);
$file_open = fopen($uploadFilePath, "r");
//print_r(fgetcsv($file_open, 1000, ";"));
$karyawan = "select DISTINCT(a.p_karyawan_id) from p_karyawan a join p_karyawan_pekerjaan c on a.p_karyawan_id = c.p_karyawan_id where a.active=1 and c.periode_gajian= 1";
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
    $gapok = isset($csv[$i]) ? $csv[$i] : 0;
    $i++;
    $tunjangan_grade = isset($csv[$i]) ? $csv[$i] : 0;;
    $i++;
    $tunjangan_bpjskes = isset($csv[$i]) ? $csv[$i] : 0;;
    $i++;
    $tunjangan_bpjsket = isset($csv[$i]) ? $csv[$i] : 0;;
    $i++;
    $tunjangan_kost = isset($csv[$i]) ? $csv[$i] : 0;;
    $i++;
    $iuran_bpjskes = isset($csv[$i]) ? $csv[$i] : 0;;
    $i++;
    $iuran_bpjsket = isset($csv[$i]) ? $csv[$i] : 0;;
    $i++;
    $infaq = isset($csv[$i]) ? $csv[$i] : 0;;;
    $i++;
    $zakat = isset($csv[$i]) ? $csv[$i] : 0;;;
    $i++;
    
    /*
    $sewa_kost = isset($csv[$i]) ? $csv[$i] : 0;;
    $i++;
    $pajak = isset($csv[$i]) ? $csv[$i] : 0;;;
    $i++;
    $kkb = isset($csv[$i]) ? $csv[$i] : 0;;;
    $i++;
    $korekplus = isset($csv[$i]) ? $csv[$i] : 0;;;
    $i++;
    $korekmin = isset($csv[$i]) ? $csv[$i] : 0;;;
    $i++;*/
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
    if (count($user)) {

        DB::connection()->table("p_karyawan_gapok")
            ->insert([
                "gapok" => $help->hapusRupiah($gapok),
                "p_karyawan_id" => $user[0]->p_karyawan_id,
                "tunjangan_grade" => $help->hapusRupiah($tunjangan_grade),
                "tunjangan_kost" => $help->hapusRupiah($tunjangan_kost),
                //"sewa_kost" => $help->hapusRupiah($sewa_kost),
                "tunjangan_bpjskes" => $help->hapusRupiah($tunjangan_bpjskes),
                "iuran_bpjskes" => $help->hapusRupiah($iuran_bpjskes),
                "iuran_bpjsket" => $help->hapusRupiah($iuran_bpjsket),
                "tunjangan_bpjsket" => $help->hapusRupiah($tunjangan_bpjsket),
                //"pajak" => $help->hapusRupiah($pajak),
                //"koperasi_kkb" => $help->hapusRupiah($kkb),
                "infaq" => $help->hapusRupiah($infaq),
                "zakat" => $help->hapusRupiah($zakat),
                //"korekplus" => $help->hapusRupiah($korekplus),
                //"korekmin" => $help->hapusRupiah($korekmin),

                "active" => (1),

                "created_date" => date("Y-m-d H:i:s"),
                "created_by" => $idUser
            ]);
        //gapok
        DB::connection()->table("prl_tunjangan")
            ->insert([
                "nominal" => $help->hapusRupiah($gapok),
                "p_karyawan_id" => $user[0]->p_karyawan_id,
                "m_tunjangan_id" => (17),
                "is_gapok" => (1),
                "active" => (1),

                "created_date" => date("Y-m-d H:i:s")
            ]);

        
        //$row = 'm_tunjangan_id';
        //$value= 16;
        DB::connection()->table("prl_tunjangan")
            ->insert([
                "nominal" => $help->hapusRupiah($tunjangan_grade),
                "p_karyawan_id" => $user[0]->p_karyawan_id,
                "m_tunjangan_id" => (11),
                "is_gapok" => (1),
                "active" => (1),

                "created_date" => date("Y-m-d H:i:s")
            ]);
        DB::connection()->table("prl_tunjangan")
            ->insert([
                "nominal" => $help->hapusRupiah($tunjangan_bpjskes),
                "p_karyawan_id" => $user[0]->p_karyawan_id,
                "m_tunjangan_id" => (12),
                "active" => (1),

                "created_date" => date("Y-m-d H:i:s")
            ]);
        DB::connection()->table("prl_tunjangan")
            ->insert([
                "nominal" => $help->hapusRupiah($tunjangan_bpjsket),
                "p_karyawan_id" => $user[0]->p_karyawan_id,
                "m_tunjangan_id" => (13),
                "active" => (1),

                "created_date" => date("Y-m-d H:i:s")
            ]);
        DB::connection()->table("prl_tunjangan")
            ->insert([
                "nominal" => $help->hapusRupiah($tunjangan_kost),
                "p_karyawan_id" => $user[0]->p_karyawan_id,
                "m_tunjangan_id" => (14),
                "active" => (1),

                "created_date" => date("Y-m-d H:i:s")
            ]);


        
        DB::connection()->table("prl_potongan")
            ->insert([
                "nominal" => $help->hapusRupiah($iuran_bpjskes),
                "p_karyawan_id" => $user[0]->p_karyawan_id,
                "m_potongan_id" => (14),
                "active" => (1),

                "created_date" => date("Y-m-d H:i:s")
            ]);
        DB::connection()->table("prl_potongan")
            ->insert([
                "nominal" => $help->hapusRupiah($iuran_bpjsket),
                "p_karyawan_id" => $user[0]->p_karyawan_id,
                "m_potongan_id" => (15),
                "active" => (1),

                "created_date" => date("Y-m-d H:i:s")
            ]);
        
        DB::connection()->table("prl_potongan")
            ->insert([
                "nominal" => $help->hapusRupiah($infaq),
                "p_karyawan_id" => $user[0]->p_karyawan_id,
                "m_potongan_id" => (19),
                "active" => (1),

                "created_date" => date("Y-m-d H:i:s")
            ]);
        DB::connection()->table("prl_potongan")
            ->insert([
                "nominal" => $help->hapusRupiah($zakat),
                "p_karyawan_id" => $user[0]->p_karyawan_id,
                "m_potongan_id" => (18),
                "active" => (1),

                "created_date" => date("Y-m-d H:i:s")
            ]);
            
            /*
            DB::connection()->table("prl_potongan")
            ->insert([
                "nominal" => $help->hapusRupiah($sewa_kost),
                "p_karyawan_id" => $user[0]->p_karyawan_id,
                "m_potongan_id" => (16),
                "active" => (1),

                "created_date" => date("Y-m-d H:i:s")
            ]);
        //korekmin
        DB::connection()->table("prl_potongan")
            ->insert([
                "nominal" => $help->hapusRupiah($korekmin),
                "p_karyawan_id" => $user[0]->p_karyawan_id,
                "m_potongan_id" => (17),
                "active" => (1),

                "created_date" => date("Y-m-d H:i:s")
            ]);
            DB::connection()->table("prl_potongan")
            ->insert([
                "nominal" => $help->hapusRupiah($pajak),
                "p_karyawan_id" => $user[0]->p_karyawan_id,
                "m_potongan_id" => (20),
                "active" => (1),

                "created_date" => date("Y-m-d H:i:s")
            ]);
        DB::connection()->table("prl_potongan")
            ->insert([
                "nominal" => $help->hapusRupiah($kkb),
                "p_karyawan_id" => $user[0]->p_karyawan_id,
                "m_potongan_id" => (9),
                "active" => (1),

                "created_date" => date("Y-m-d H:i:s")
            ]);
            DB::connection()->table("prl_tunjangan")
            ->insert([
                "nominal" => $help->hapusRupiah($korekplus),
                "p_karyawan_id" => $user[0]->p_karyawan_id,
                "m_tunjangan_id" => (16),
                "active" => (1),

                "created_date" => date("Y-m-d H:i:s")
            ]);*/
    }

    // mysql_query("INSERT INTO employee VALUES ('$name','$age','country')");
}

    // header("Location: " . URL::to('/backend/gapok'), true, 302);

;
?>
<script>
    window.location = "{{ route('be.gapok') }}";
</script>
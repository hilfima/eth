

<div class="content container-fluid">
    <link rel="stylesheet" href="{!! asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') !!}">

    <!-- Select2 .table-bordered td,
.table-bordered th {
 border:1px solid #dee2e6
 
}-->


    <link rel="stylesheet" href="{!! asset('plugins/fontawesome-free/css/all.min.css') !!}">
    <style>
        .list-unstyled {
            padding-left: 0;
            list-style: none
        }

        .table {
            width: 100%;
            margin-bottom: 1rem;
            color: #212529;
        }

        table {
            border-collapse: collapse;
        }

        .table-bordered td,
        .table-bordered th {
            border-bottom: 1px solid #dee2e6
        }

        td {
            font-size: 11px
        }
    </style>

    <!-- /Page Title -->

    <div class="row">
        <div class="col-md-12">
            <div class="card-box">
                <?php
                $view = isset($view) ? $view : 'pdf';
                
               
                if ($karyawan[0]->m_lokasi_id == 3) {
                    $logo = 'Logo Rea Arta Mulia.png';
                } else if ($karyawan[0]->m_lokasi_id == 4) {
                    $logo = 'Logo EMM_Page12.png';
                } else if ($karyawan[0]->m_lokasi_id == 5) {
                    $logo = 'cc.png';
                } else if ($karyawan[0]->m_lokasi_id == 2) {
                    $logo = 'Logo SJP Guideline.png';
                } else  if ($karyawan[0]->m_lokasi_id == 9) {
                    $logo = 'Logo ASA.png';
                } else if ($karyawan[0]->m_lokasi_id == 13) {
                    $logo = 'Logo Mafaza Hires.png';
                } else if ($karyawan[0]->m_lokasi_id == 6) {
                    $logo = 'JKA LOGO.png';
                }else if ($karyawan[0]->m_lokasi_id == 26) {
                    $logo = 'digifrom.png';
                }else if ($karyawan[0]->m_lokasi_id == 7) {
                    $logo = 'logo_sahabat_fillah.png';
                }else if ($karyawan[0]->m_lokasi_id == 27) {
                    $logo = 'MSP.png';
                } else
                    $logo = 'logo.png';
                $type = pathinfo(url('dist/img/logo/' . $logo), PATHINFO_EXTENSION);
                //$data = file_get_contents(url('dist/img/logo/logo/'.$logo));
                //$base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
                ?>
                <!--<img style="width: auto; height: 100%" src="{{ url('dist/img/logo/'.$logo) }}">!-->
                <table style="width:100%">
                    <tr>


                        <td style="width: 25%">
						
                            <img style="width: 100px; " src="{{ url('dist/img/logo/'.$logo) }}">
						

                        </td>
                        <td style="text-align: center;width: 50%">


                            <h3 style="margin-bottom: 0"><?= $karyawan[0]->nmlokasi; ?></h3>
                            <div style="margin-bottom: 0"><?= $karyawan[0]->alamatlokasi; ?></div>

                        </td>
                        <td style="text-align: center; color: #fff;width: 25%">
                            Mohon Dirahasiakan
                        </td>
                    </tr>
                </table>
                <hr>
                <h4 class="payslip-title" style="margin-bottom: 0; padding:0;margin:0; text-align:center">Slip <?=$generate[0]->is_thr?'THR':'Gaji'?> </h4>
               

              
				 
                <?php

                $sql = "select *, nama_gaji as nama
												from prl_gaji a 
											 	join prl_gaji_detail b on b.prl_gaji_id = a.prl_gaji_id 
											 	join m_gaji_absen on b.gaji_absen_id = m_gaji_absen.m_gaji_absen_id 
											 	where prl_generate_id = $id_prl and 
											 		p_karyawan_id = $id_kary  and 
											 		b.type=1  and 
											 		m_gaji_absen.active =1    
											 		and b.active=1
											 		order by urutan,nominal  desc
											 		";
											 		$where = "";
											 		
                $datakerja = DB::connection()->select($sql);
                $sql = "select *,case when type=1 then (select nama_gaji from m_gaji_absen where b.gaji_absen_id = m_gaji_absen.m_gaji_absen_id )
		when type=2 then (select nama from prl_tunjangan join m_tunjangan on prl_tunjangan.m_tunjangan_id = m_tunjangan.m_tunjangan_id where b.prl_tunjangan_id = prl_tunjangan.prl_tunjangan_id )
		when type=3 then (select nama from m_tunjangan where b.m_tunjangan_id = m_tunjangan.m_tunjangan_id)
		when type=4 then (select nama from prl_potongan join m_potongan on prl_potongan.m_potongan_id = m_potongan.m_potongan_id where b.prl_potongan_id = prl_potongan.prl_potongan_id )
		when type=5 then (select nama from m_potongan where b.m_potongan_id = m_potongan.m_potongan_id)
		 end as nama
		 
		 
												from prl_gaji a 
											 	join prl_gaji_detail b on b.prl_gaji_id = a.prl_gaji_id 
											 	
											 	where prl_generate_id = $id_prl and 
											 		p_karyawan_id = $id_kary  and 
											 		b.type in (2,3)    
											 		and b.active=1
											 		order by case 
		when type=2 then (select urutan from prl_tunjangan join m_tunjangan on prl_tunjangan.m_tunjangan_id = m_tunjangan.m_tunjangan_id where b.prl_tunjangan_id = prl_tunjangan.prl_tunjangan_id )
		when type=3 then (select urutan from m_tunjangan where b.m_tunjangan_id = m_tunjangan.m_tunjangan_id)
		
		 end,b.create_date desc,nominal  desc
											 		";
                    $tunjangan = DB::connection()->select($sql);
                     $sql = "select *,case when type=1 then (select nama_gaji from m_gaji_absen where b.gaji_absen_id = m_gaji_absen.m_gaji_absen_id )
		when type=2 then (select nama from prl_tunjangan join m_tunjangan on prl_tunjangan.m_tunjangan_id = m_tunjangan.m_tunjangan_id where b.prl_tunjangan_id = prl_tunjangan.prl_tunjangan_id )
		when type=3 then (select nama from m_tunjangan where b.m_tunjangan_id = m_tunjangan.m_tunjangan_id)
		when type=4 then (select nama from prl_potongan join m_potongan on prl_potongan.m_potongan_id = m_potongan.m_potongan_id where b.prl_potongan_id = prl_potongan.prl_potongan_id )
		when type=5 then (select nama from m_potongan where b.m_potongan_id = m_potongan.m_potongan_id)
		 end as nama,
		 case 
		 when type=4 then (select urutan from prl_potongan join m_potongan on prl_potongan.m_potongan_id = m_potongan.m_potongan_id where b.prl_potongan_id = prl_potongan.prl_potongan_id )
		when type=5 then (select urutan from m_potongan where b.m_potongan_id = m_potongan.m_potongan_id)
		 end as urutan
												from prl_gaji a 
											 	join prl_gaji_detail b on b.prl_gaji_id = a.prl_gaji_id 
											 	
											 	where prl_generate_id = $id_prl and 
											 		p_karyawan_id = $id_kary  and 
											 		b.type in (4,5)      
											 		and b.active=1
											 		order by urutan,nominal desc,b.create_date
											 		";
                    $potongan = DB::connection()->select($sql);
                $data = array();
                $sudah = array();
                $i = 0;
              	  if($generate[0]->is_thr){
					$row = $tunjangan;
					}else{
					$row = $datakerja;
						
					}
                if($generate[0]->is_thr)
                $table2[$i][] = '<h4 class="m-b-10"><strong>Tunjangan</strong></h4>';
                else
                $table2[$i][] = '<h4 class="m-b-10"><strong>Data Kerja</strong></h4>';
                $table2[$i][] = '';
                $table2[$i][] = '';
                $table2[$i][] = '<span style="color:#fff">slip</span>';

                $i++;
                $table1_total_nominal=0;
                foreach ($row as $row) {
                	
                    if (!in_array($row->nama, $sudah)) {
                    	$name =  $row->nama;
                    	$data_nominal['absen'][$row->m_gaji_absen_id] = $row->nominal;
                        	$visible=true;
                        if($row->nama=='Tunjangan Entitas' and $row->nominal ==0){
                        	$visible=false;
                        } 
                        if($row->nama=='Tunjangan Grade' and $karyawan[0]->periode_gajian ==0){
                        	$visible=false;
                        } 
                        if($row->nama=='Tunjangan BPJS Kesehatan' and $generate[0]->is_thr){
                        	$visible=false;
                        }if($row->nama=='Tunjangan BPJS Ketenaga Kerjaan'  and $generate[0]->is_thr){
                        	$visible=false;
                        }
                        if($visible){
                        	
                        $table2[$i][] = $row->nama;
                        if($generate[0]->is_thr){
	                        $table2[$i][] = 'Rp ';
	                        
	                        $table2[$i][] = $help->rupiah($row->nominal, 0, '');
	                        $table1_total_nominal += $row->nominal;
                        }
                        else{
                        	
	                        $table2[$i][] = ' ';
	                        $table2[$i][] = $row->nominal;
                        }
                        $table2[$i][] = '';
                        $i++;
                        $sudah[] = $row->nama;
                        }
                    }
               
                } ?>


                <div>

                    <br>

                    <?php

                    
                    $data = array();
                    $total_tunjangan = 0;
                    $i = 0;
                    if(!$generate[0]->is_thr){
					$row = $tunjangan;
                    $table[$i][] = '<h4 class="m-b-10"><strong>Tunjangan</strong></h4>';
                    $table[$i][] = '';
                    $table[$i][] = '';
                    $table[$i][] = '<span style="color:#fff">slip</span>';
                    
                    $i++;
                    
					
                    foreach ($row as $row) {
                    		if($row->nama=='Tunjangan BPJS Ketenaga Kerjaan'){
                        		$row->nama =	$nama = 'Tunjangan BPJS Ketenagakerjaan';
                        	}
                        if (!in_array($row->nama, $sudah)) {
                        	if($row->nama=='Upah Harian'){
	                        	if(isset($data_nominal['absen'][2])){
	                        		$row->nominal = $data_nominal['absen'][2]*$row->nominal;
	                        	}else{
	                        		$row->nominal=0;
	                        	}
                        	}
                                            
                        
                            $total_tunjangan += $row->nominal;
                            $table[$i][] = $row->nama;
                            $table[$i][] = 'Rp ';
                            $table[$i][] = $help->rupiah($row->nominal, 0, '');
                            $table[$i][] = '';

                            //echo '<tr>
                            //	<td>'.$row->nama.'</td>
                            //	<td>'.$help->rupiah($row->nominal).'</td>
                            //</tr>';
                            $i++;
                            $sudah[] = $row->nama;
                        }
                    }
                    
                    }else{
					 $table[$i][] = '<h4 class="m-b-10"><strong>THR</strong></h4>';
                    $table[$i][] = '';
                    $table[$i][] = '';
                    $table[$i][] = '<span style="color:#fff">slip</span>';
                    $bulan = 0;
                    foreach($datakerja as $row){
                    	if($row->nama =='Total Bulan Gabung')
                    		$bulan = $row->nominal;
                    		
                    }                    
                    $i++;
                    $t = $help->rupiah(($bulan/12*$table1_total_nominal), 0, '');
                    $table[$i][] = 'Pendapatan THR';
                    $table[$i][] = 'Rp ';
                    $table[$i][] =   $help->rupiah($t =	($bulan/12*$table1_total_nominal), 0, '');
                    $table[$i][] = '';
                    $total_tunjangan+=$t;
						
					}
                    $table4[] = '<b>TOTAL '.($generate[0]->is_thr?'THR':'Tunjangan').'</b>';
                    $table4[] = 'Rp ';
                    $table4[] = '<b>' . $help->rupiah($total_tunjangan, 0, '') . '</b>';
                    $table4[] = '';
                    //echo '<tr>
                    //	<td><h4><b>TOTAL TUNJANGAN</b></h4></td>
                    //	<td>'.$help->rupiah($total_tunjangan).'</td>
                    //</tr>'

					$i = 0;
                   	$table1[$i][] = '<h4 class="m-b-10"><strong>Potongan</strong></h4>';
                    $table1[$i][] = '';
                    $table1[$i][] = '';
                    $data = array();
                    $total_potongan = 0;
                    $i = 1;
                    
                    
                    $row = $potongan;
                    foreach ($row as $row) {
                        if($row->nama=='Iuran BPJS Ketenaga Kerjaan')
                        		$row->nama = 'Iuran BPJS Ketenagakerjaan';
                        if (!in_array($row->nama, $sudah)) {
                        	
                            $total_potongan += $row->nominal;
                            $table1[$i][] = $row->nama;
                            $table1[$i][] = 'Rp ';
                            $table1[$i][] = $help->rupiah($row->nominal, 0, '');


                            $i++;
                            $sudah[] = $row->nama;
                        }
                    }
                    $table5[] = '<b>TOTAL POTONGAN</b>';
                    $table5[] = 'Rp ';
                    $table5[] = '<b>' . $help->rupiah($total_potongan, 0, '') . '</b>';
                    //echo '<tr>
                    //	<td><h4><b>TOTAL Potongan</b></h4></td>
                    //	<td>'.$help->rupiah($total_potongan).'</td>
                    //</tr>'
                    ?>

                    <?php
                    if ($view == 'pdf') {
                    	?>
                    	<table>
                    		<tr>
                    			
                    		</tr>
                    	</table>
                    <table class="w-100" style="width: 100%">
					<tr>
						<td >NIK</td>
						<td>:</td>
						<td><?= $karyawan[0]->nik; ?></td>
						<td style="width: 25%;"><div  style="color:#fff; width: 20%">.</div></td>
						<td>Periode <?=$generate[0]->is_thr?'THR':'Gaji'?></td>
						<td>:</td>
						<td> <div class="" style=""> <span><?= !$generate[0]->is_thr?$help->bulan($generate[0]->bulan).', ':''; ?> <?= ($generate[0]->tahun); ?></span></div></td>
					</tr>
					<tr>
						<td>Nama</td>
						<td>:</td>
						<td ><?= $karyawan[0]->nama_lengkap; ?></td>
						<td style="width: 25%;"><div  style="color:#fff; width: 60%">.</div></td>
						<td>Tanggal Cetak</td>
						<td>:</td>
						<td><?= (date('Y-m-d')); ?></td>
					</tr>
					<tr>
						<td>Jabatan</td>
						<td>:</td>
						<td><?= $karyawan[0]->nmpangkat; ?></td>
						<td style="width: 25%;"><div style="color:#fff; width: 20%">.</div></td>
						<td>Masa Kerja</td>
						<td>:</td>
						<td><?= $generate[0]->is_thr?$help->selisih_tanggal($karyawan[0]->tgl_bergabung,$generate[0]->tgl_patokan):$help->selisih_tanggal($karyawan[0]->tgl_bergabung); ?></td>
						
					</tr>
				</table> 
				<script>
						
				</script>
                    	<?php 
                    	
                    	
                        echo '<table class="table " >
												<tbody>';
                            echo '<tr>';
 echo '<td style=";border-right:1px solid #fff;color:#fff;border-bottom: 1px solid #dee2e6;"></td>';
                                echo '<td 	style=";border-right:1px solid #fff;border-bottom: 1px solid #dee2e6;"></td>';
                                echo '<td style=";border-right:1px solid #fff;border-bottom: 1px solid #dee2e6;"></td>';
                                echo '<td style=";border-bottom: 1px solid #dee2e6;"></td>';
 echo '<td style=";border-right:1px solid #fff;color:#fff;border-bottom: 1px solid #dee2e6;"></td>';
                                echo '<td 	style=";border-right:1px solid #fff;border-bottom: 1px solid #dee2e6;"></td>';
                                echo '<td style=";border-right:1px solid #fff;border-bottom: 1px solid #dee2e6;"></td>';
                                echo '<td style=";border-bottom: 1px solid #dee2e6;"></td>';
 echo '<td style=";border-right:1px solid #fff;color:#fff;border-bottom: 1px solid #dee2e6;"></td>';
                                echo '<td 	style=";border-right:1px solid #fff;border-bottom: 1px solid #dee2e6;"></td>';
                                echo '<td style=";border-right:1px solid #fff;border-bottom: 1px solid #dee2e6;"></td>';
                                echo '<td style=";border-bottom: 1px solid #dee2e6;"></td>';
                            echo '</tr>';
                        for ($i = 0; $i < count($table2); $i++) {
                            echo '<tr>';
                            $style = 'style="border-bottom: 1px solid #dee2e6;"';

                            if (!isset($table2[$i])) {

                                echo '<td style=";border-bottom:1px solid #fff!important;border-right:1px solid #fff"></td>';
                                echo '<td 	style=";border-bottom:1px solid #fff!important;border-right:1px solid #fff"></td>';
                                echo '<td style=";border-bottom:1px solid #fff!important;border-right:1px solid #fff"></td>';
                                echo '<td style=";border-bottom:1px solid #fff!important"></td>';
                            } else {

                                for ($j = 0; $j < count($table2[$i]); $j++) {


                                    if ($j == 2)
                                        $style = 'style="text-align:right;border-bottom: 1px solid #dee2e6;"';
                                    echo '<td ' . $style . '>' . $table2[$i][$j] . '</td>';
                                }
                            }
                            if (!isset($table[$i])) {

                                echo '<td style=";border-bottom:1px solid #fff!important;border-right:1px solid #fff"></td>';
                                echo '<td 	style=";border-bottom:1px solid #fff!important;border-right:1px solid #fff"></td>';
                                echo '<td style=";border-bottom:1px solid #fff!important;border-right:1px solid #fff"></td>';
                                echo '<td style=";border-bottom:1px solid #fff!important"></td>';
                            } else {
                                $style = 'style="border-bottom: 1px solid #dee2e6;"';

                                for ($j = 0; $j < count($table[$i]); $j++) {


                                    if ($j == 2)
                                        $style = 'style="text-align:right;border-bottom: 1px solid #dee2e6;"';
                                    echo '<td ' . $style . '>' . $table[$i][$j] . '</td>';
                                }
                            }
                            if (!isset($table1[$i])) {

                                echo '<td style=";border-bottom:1px solid #fff!important;border-right:1px solid #fff"></td>';
                                echo '<td 	style=";border-bottom:1px solid #fff!important;border-right:1px solid #fff"></td>';
                                echo '<td style=";border-bottom:1px solid #fff!important;border-right:1px solid #fff"></td>';
                                echo '<td style=";border-bottom:1px solid #fff!important"></td>';
                            } else {
                                for ($j = 0; $j < count($table1[$i]); $j++) {

                                    if ($j == 0)
                                        $style = 'style="text-align:left;border-bottom: 1px solid #dee2e6;"';
                                    if ($j == 2)
                                        $style = 'style="text-align:right;border-bottom: 1px solid #dee2e6;"';
                                    echo '<td  ' . $style . '>' . $table1[$i][$j] . '</td>';
                                }
                            }

                            echo '</tr>';
                        }
                        echo '<tr>';
                        echo '<td style=";border-bottom:1px solid #fff!important;;border-right:1px solid #fff"></td>';
                        echo '<td 	style=";border-bottom:1px solid #fff!important;;border-right:1px solid #fff"></td>';
                        echo '<td style=";border-bottom:1px solid #fff!important;;border-right:1px solid #fff"></td>';
                        echo '<td style=";border-bottom:1px solid #fff!important;"></td>';
                        for ($j = 0; $j < count($table4); $j++) {

                            if ($j == 0)
                                $style = 'style="text-align:left;border-top: 2px solid #000; font-weight:bold"';
                            if ($j == 2)
                                $style = 'style="text-align:right;border-top: 2px solid #000;"';
                            echo '<td  ' . $style . '>' . $table4[$j] . '</td>';
                        }
                        for ($j = 0; $j < count($table5); $j++) {

                            if ($j == 0)
                                $style = 'style="text-align:left;border-top: 2px solid #000; font-weight:bold"';
                            if ($j == 2)
                                $style = 'style="text-align:right;border-top: 2px solid #000;"';
                            echo '<td  ' . $style . '>' . $table5[$j] . '</td>';
                        }
                        echo '</tr></tbody>
											</table>';
                    } 
                    else {
?>

<div class="row">
<div class="col-md-4">
<table class="w-100 pt-3">
					<tr>
						<td>NIK</td>
						<td>:</td>
						<td><?= $karyawan[0]->nik; ?></td>
					</tr>
					<tr>
						<td>Nama</td>
						<td>:</td>
						<td ><?= $karyawan[0]->nama_lengkap; ?></td>
					</tr>
					<tr>
						<td>Jabatan</td>
						<td>:</td>
						<td><?= $karyawan[0]->nmpangkat; ?></td>
					</tr>
				</table>
</div>
<div class="col-md-4">

</div>
<div class="col-md-4">
<table class="w-100 pt-3">
					<tr>
						
						
						<td>Periode </td>
						<td>:</td>
						<td> <?= $help->bulan($generate[0]->bulan); ?>, <?= ($generate[0]->tahun); ?></td>
					</tr>
					<tr>
						
						<td>Tanggal Cetak</td>
						<td>:</td>
						<td><?= (date('Y-m-d')); ?></td>
					</tr>
					<tr>
						
						<td>Masa Kerja</td>
						<td>:</td> 
						<td><?= $help->selisih_tanggal($karyawan[0]->tgl_bergabung); ?></td>
					</tr>
					
				</table>
</div>
</div>


<br>
<?php 

                        echo '<div class="row">';
                        echo '<div class="col-md-4">';
                        echo '<table class="table" style="width:100%">';
                        echo '<tr>';
                        echo '<td style=";border-right:1px solid #fff;color:#fff;border-bottom: 1px solid #dee2e6;"></td>';
                                echo '<td 	style=";border-right:1px solid #fff;border-bottom: 1px solid #dee2e6;"></td>';
                                echo '<td style=";border-right:1px solid #fff;border-bottom: 1px solid #dee2e6;"></td>';
                                echo '<td style=";border-bottom: 1px solid #dee2e6;"></td>';
                        echo '</tr>';
                        for ($i = 0; $i < count($table2); $i++) {
                            echo '<tr>';
                            if (!isset($table2[$i])) {

                                echo '<td style=";border-bottom:1px solid #fff!important;border-right:1px solid #fff;color:#fff">.</td>';
                                echo '<td 	style=";border-bottom:1px solid #fff!important;border-right:1px solid #fff"></td>';
                                echo '<td style=";border-bottom:1px solid #fff!important;border-right:1px solid #fff"></td>';
                                echo '<td style=";border-bottom:1px solid #fff!important"></td>';
                            } else {
                                for ($j = 0; $j < count($table2[$i]); $j++) {

                                    $style = 'style="border-bottom: 1px solid #dee2e6;"';

                                    if ($j == 2)
                                        $style = 'style="text-align:right;border-bottom: 1px solid #dee2e6;"';
                                    echo '<td ' . $style . '>' . $table2[$i][$j] . '</td>';
                                }
                            }
                            echo '<tr>';
                        }
                        echo '</table>';

                        echo '</div>';
                        echo '<div class="col-md-4">';

                        echo '<table class="table" style="width:100%">';
                        echo '<tr>';
                        echo '<td style=";border-right:1px solid #fff;color:#fff;border-bottom: 1px solid #dee2e6;"></td>';
                                echo '<td 	style=";border-right:1px solid #fff;border-bottom: 1px solid #dee2e6;"></td>';
                                echo '<td style=";border-right:1px solid #fff;border-bottom: 1px solid #dee2e6;"></td>';
                                echo '<td style=";border-bottom: 1px solid #dee2e6;"></td>';
                        echo '</tr>';
                        for ($i = 0; $i < count($table2); $i++) {
                            echo '<tr>';
                            if (!isset($table[$i])) {
                                echo '<td style=";border-bottom:1px solid #fff!important;border-right:1px solid #fff;color:#fff">.</td>';
                                echo '<td 	style=";border-bottom:1px solid #fff!important;border-right:1px solid #fff"></td>';
                                echo '<td style=";border-bottom:1px solid #fff!important;border-right:1px solid #fff"></td>';
                                echo '<td style=";border-bottom:1px solid #fff!important"></td>';
                            } else {
                                for ($j = 0; $j < count($table[$i]); $j++) {


                                    $style = 'style="border-bottom: 1px solid #dee2e6;"';

                                    if ($j == 2)
                                        $style = 'style="text-align:right;border-bottom: 1px solid #dee2e6;"';
                                    echo '<td ' . $style . '>' . $table[$i][$j] . '</td>';
                                }
                            }
                            echo '<tr>';
                        }
                        for ($j = 0; $j < count($table4); $j++) {

                            if ($j == 0)
                                $style = 'style="text-align:left;border-top: 2px solid #000; font-weight:bold"';
                            if ($j == 2)
                                $style = 'style="text-align:right;border-top: 2px solid #000;"';
                            echo '<td  ' . $style . '>' . $table4[$j] . '</td>';
                        }
                        echo '</table>';
                        echo '</div>';
                        echo '<div class="col-md-4">';

                        echo '<table class="table" style="width:100%">'; echo '<tr>';
                        echo '<td style=";border-right:1px solid #fff;color:#fff;border-bottom: 1px solid #dee2e6;"></td>';
                                echo '<td 	style=";border-right:1px solid #fff;border-bottom: 1px solid #dee2e6;"></td>';
                                echo '<td style=";border-right:1px solid #fff;border-bottom: 1px solid #dee2e6;"></td>';
                                echo '<td style=";border-bottom: 1px solid #dee2e6;"></td>';
                        echo '</tr>';
                        for ($i = 0; $i < count($table2); $i++) {
                            echo '<tr>';
                            if (!isset($table1[$i])) {
                                echo '<td style=";border-bottom:1px solid #fff!important;border-right:1px solid #fff;color:#fff">.</td>';
                                echo '<td 	style=";border-bottom:1px solid #fff!important;border-right:1px solid #fff"></td>';
                                echo '<td style=";border-bottom:1px solid #fff!important;border-right:1px solid #fff"></td>';
                                echo '<td style=";border-bottom:1px solid #fff!important"></td>';
                            } else {
                                for ($j = 0; $j < count($table1[$i]); $j++) {


                                    $style = 'style="border-bottom: 1px solid #dee2e6;"';

                                    if ($j == 2)
                                        $style = 'style="text-align:right;border-bottom: 1px solid #dee2e6;"';
                                    echo '<td ' . $style . '>' . $table1[$i][$j] . '</td>';
                                }
                            }
                        	echo '</tr>';
                        }
                         echo '<tr>';
                    for ($j = 0; $j < count($table5); $j++) {

                        if ($j == 0)
                            $style = 'style="text-align:left;border-top: 2px solid #000; font-weight:bold"';
                        if ($j == 2)
                            $style = 'style="text-align:right;border-top: 2px solid #000;"';
                        echo '<td  ' . $style . '>' . $table5[$j] . '</td>';
                    }
                        echo '</tr>';
                        echo '</table>';
                        echo '</div>';
                    }
                   
                    ?>


                    <?php

                    ?>


                </div>
           
        <div style="border-top: 2px solid #000;border-bottom: 2px solid #000; margin-bottom: 20px;<?= $view=='pdf'?'font-size:12px;':'';?>">

            <div style="font-weight: 700; <?= $view=='pdf'?'font-size:12px;':'font-size: 16px';?>">Take Home Pay: <?= $help->rupiah($total_tunjangan - $total_potongan); ?></div>
            <?= $help->terbilang($total_tunjangan - $total_potongan) . ''; ?>
        </div>
<div style=" margin-bottom: 20px; font-size:10px;<?= $view=='pdf'?'font-size:9px;':'';?>">

   <?php 
   if($karyawan[0]->periode_gajian==0){
   		echo '   Catatan:<br>
   		*Gaji Pokok = Upah Harian x 22<br>';
   }else  if($generate[0]->is_thr){
   ?>
      Catatan:<br>
*Perhitungan THR dengan masa kerja di atas 1 tahun : 1x Upah/Bulan
<br>*Perhitungan THR dengan masa kerja di bawah 1 tahun : masa kerja/12 x 1 bulan upah
<br>*Zakat Perhasilan : 2,5% dari penghasilan perbulan yang setara dengan 85gr emas (+-7.000.000)
 <?php }?>
 </div>
     
        <table style="width: 100%">
            <tr>
                <td style="text-align: center;font-weight: 700;">ATASAN</td>
                <td style="text-align: center;font-weight: 700;">HRD</td>
            </tr>
            <tr>
                <td>
                    <br>
                    <br>
                    <br>
                    <br>
                    <br>

                </td>
                <td><br>
                    <br>
                    <br>
                    <br>
                    <br>
                </td>
            </tr>
            <tr>
                <td style="text-align: center;">
                    _____________________________
                </td>
                <td style="text-align: center;">_____________________________
                </td>
            </tr>
        </table>
    </div>
</div>

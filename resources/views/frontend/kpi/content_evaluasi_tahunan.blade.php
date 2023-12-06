<div class="card">
       
        <!-- /.card-header -->
        <div class="card-body">
        	<h3 style="text-align: center;">Evaluasi Tahunan</h3>
           <table>
           	<tr>
           	
           		<td>Nama</td>
           		<td><?= $karyawan[0]->nama_lengkap?></td>
           	</tr>
           	<tr>
           		<td>Jabatan</td>
           		<td><?= $karyawan[0]->jabatan?></td>
           	</tr>
           	<tr>
           		<td>Unit Kerja</td>
           		<td><?= $karyawan[0]->departemen?></td>
           	</tr>
           	<tr>
           		<td>Periode Penilaian</td>
           		<td><?= $kpi_detail[0]->tahun?></td>
           	</tr>
           </table>
           <?php 
           	$jenis_penilaian = DB::connection()->select("select * from m_jenis_penilaian_kpi where is_pencapaian=1");?>
           <table class="table " border="1" style="border-collapse: collapse;">
           	<tr>
           		<th>A.</th>
           		<th> Pencapaian / Achievement</th>
           		<th> Presentase Pencapaian</th>
           		<th> Bobot</th>
           		<th> </th>
           	</tr><?php 
           			$total_bobot =0;
           			$total =0;
           			foreach($kpi_detail as $kpi_d){
           	$tw4 = $kpi_d->pencapaian_tw4/$kpi_d->target*100;
            $bobot = ($kpi_d->prioritas/$kpi_d->sum*100);
            $bobot4 = $tw4*$bobot/100;
            ?>
                    
                    <tr>
                        <td></td>
                        <td><?=$kpi_d->sasaran_kerja ?></td>
                        <td style="text-align: center"><?=round($tw4,2) ?>%</td>
                        <td style="text-align: center"><?=round($bobot,2) ?>%</td>
                        <td style="text-align: center"><?=round($bobot4,2) ?>%</td>
           			</tr>
           			<?php 
           			//echo $bobot;
           			$total_bobot +=$bobot;
           			$total +=$bobot4;
           			?>
             <?php }?>  
           	<tr>
           		<th></th>
           		<th></th>
           		<th></th>
           		<th><?=$total_bobot?>%</th>
           		<th><?=round($total,2)?>%</th>
           	</tr>
           	<tr>
           		<th></th>
           		<th>Sub Total</th>
           		<th>Nilai : <?php 
           		
           		IF($total<75)
           			$nilai= 45;
           		else IF($total<86)
           			$nilai= (540-(10*(85-$total)))/9;
           		else IF($total<95)
           			$nilai= (560-(10*(94-$total)))/8;
           		else IF($total<103)
           			$nilai= (560-(10*(102-$total)))/7;
           		else IF($total<110)
           			$nilai= (540-(10*(109-$total)))/6;
           		else IF($total<114)
           			$nilai= (500-(10*(114-$total)))/5;
           		else 
           			$nilai= 100;
           		$nilai = $total;
           		echo $nilai;
           		?></th>
           		<th>x<?=$jenis_penilaian[0]->persen;?>%</th>
           		<th><?=$total_a=($jenis_penilaian[0]->persen/100*$nilai);?></th>
           	</tr>
           	<tr>
           		<th></th>
           		<th></th>
           		<th></th>
           		<th>Kondisi Pencapaian</th>
           		<th><?php
           		IF($nilai<50)
           			echo "TIDAK BAIK";
           		else IF($nilai<60)
           			echo "HAMPIR BAIK";
           		else IF($nilai<70)
           			echo "BAIK (-)";
           		else IF($nilai<80)
           			echo "BAIK (+)";
           		else IF($nilai<90)
           			echo "LEBIH BAIK";
           		else 
           			echo "SANGAT BAIK";
           		?></th>
           	</tr>
           	<tr>
           		<th></th>
           		<th></th>
           		<th> </th>
           		<th> </th>
           		<th> </th>
           	</tr>
           	<?php
           	$total_all =0;
           	$jenis_penilaian = DB::connection()->select("select * from m_jenis_penilaian_kpi where is_pencapaian=0");
           	foreach($jenis_penilaian as $jenis_penilaian){
           	?>
           	
           	
           	<tr>
           		<th>B.</th>
           		<th> <?=$jenis_penilaian->nama_penilaian;?></th>
           		<th> Nilai (0-100)</th>
           		<th> Bobot</th>
           		<th> Nilai X Bobot</th>
           	</tr><?php 
           			$total_bobot =0;
           			$total =0;
           		
           			$kemampuan = DB::connection()->select("select * from m_point_utama_kpi where m_jenis_penilaian_id=$jenis_penilaian->m_jenis_penilaian_kpi_id");
           			foreach($kemampuan as $kemampuans){
           			  $penilaian =   DB::connection()->select("select sum(penilaian_point) as nilai from t_kpi_penilaian_karyawan_penilai
           			            left join t_kpi_penilaian_karyawan on t_kpi_penilaian_karyawan_penilai.t_kpi_penilaian_karyawan_id = t_kpi_penilaian_karyawan.t_kpi_penilaian_karyawan_id
           			            where m_point_utama_kpi_id=$kemampuans->m_point_utama_kpi_id and t_kpi_penilaian_karyawan_penilai.active=1 and t_kpi_id=$id");
           				$nilai =  $penilaian[0]->nilai/count($kemampuan)*100;
           				$bobot = $kemampuans->bobot;
           				$bobot4 = $nilai*$bobot/100;
           			?>
          
                    
                    <tr>
                        <td></td>
                        <td><?=$kemampuans->nama_point; ?></td>
                        <td style="text-align: center">{!! round($nilai)  !!}%</td>
                        <td style="text-align: center">{!! round($bobot) !!}%</td>
                        <td style="text-align: center">{!! round($bobot4,2)  !!}%</td>
           			</tr>
           			<?php 
           			$total_bobot +=$bobot;
           			$total +=$bobot4;
}
           			?>
           	<tr>
           		<th></th>
           		<th></th>
           		<th></th>
           		<th><?=$total_bobot?>%</th>
           		<th><?=round($total,2)?>%</th>
           	</tr>
           	<tr>
           		<th></th>
           		<th>Sub Total</th>
           		<th>Nilai : <?php 
           		
           		IF($total<75)
           			$nilai= 45;
           		else IF($total<86)
           			$nilai= (540-(10*(85-$total)))/9;
           		else IF($total<95)
           			$nilai= (560-(10*(94-$total)))/8;
           		else IF($total<103)
           			$nilai= (560-(10*(102-$total)))/7;
           		else IF($total<110)
           			$nilai= (540-(10*(109-$total)))/6;
           		else IF($total<114)
           			$nilai= (500-(10*(114-$total)))/5;
           		else 
           			$nilai= 100;
           			
           		$nilai = $total;
           		echo $nilai;
           		?></th>
           		<th>x<?=$jenis_penilaian->persen;?>%</th>
           		<th><?=$total_all +=($jenis_penilaian->persen/100*$nilai);?></th>
           	</tr>
           	<tr>
           		<th></th>
           		<th></th>
           		<th></th>
           		<th>Kondisi Pencapaian</th>
           		<th><?php
           		IF($nilai<50)
           			echo "TIDAK BAIK";
           		else IF($nilai<60)
           			echo "HAMPIR BAIK";
           		else IF($nilai<70)
           			echo "BAIK (-)";
           		else IF($nilai<80)
           			echo "BAIK (+)";
           		else IF($nilai<90)
           			echo "LEBIH BAIK";
           		else 
           			echo "SANGAT BAIK";
           		?></th>
           	</tr>
           
           	<tr>
           		<th></th>
           		<th></th>
           		<th> </th>
           		<th> </th>
           		<th> </th>
           	</tr>
           	<?php }?>
           
           	
           	
           	
           	
           	<tr>
           		<th></th>
           		<th></th>
           		<th> </th>
           		<th> </th>
           		<th> </th>
           	</tr>
           	
           	<tr>
           		<th></th>
           		<th></th>
           		<th></th>
           		<th>Total Nilai</th>
           		<th><?= $total_all += $total_a 
           		
           		?></th>
           	</tr>
           	<tr>
           		<th></th>
           		<th></th>
           		<th></th>
           		<th>Kelompok</th>
           		<th><?php
           		IF($total_all<50)
           			echo "TIDAK BAIK";
           		else IF($total_all<60)
           			echo "HAMPIR BAIK";
           		else IF($total_all<70)
           			echo "BAIK (-)";
           		else IF($total_all<80)
           			echo "BAIK (+)";
           		else IF($total_all<90)
           			echo "LEBIH BAIK";
           		else 
           			echo "SANGAT BAIK";
           		?></th>
           	</tr>
           
           </table>
			<table style="width: 100%;margin-top: 20px">
				<tr>
					<td style="text-align: center;"></td>
					<td style="text-align: center;">Menyetujui,<br>
					___________ ,_______________________
					</td>
					<td style="text-align: center;"></td>
				</tr>
				<tr>
					<td style="text-align: center;">Direktur Holding</td>
					<td style="text-align: center;">Atasan Langsung</td>
					<td style="text-align: center;">Pegawai</td>
				</tr>
				<tr>
					<td style="text-align: center;"><br /><br /><br /><br />(.......................................)</td>
					<td style="text-align: center;"><br /><br /><br /><br />(.......................................)</td>
					<td style="text-align: center;"><br /><br /><br /><br />(.......................................)</td>
					
				</tr>
			</table>
        </div>
        <!-- /.card-body -->
    </div>
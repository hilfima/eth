<div class="card">
       
        <!-- /.card-header -->
        <div class="card-body">
        <h4 class=" mb-3" style="font-weight: 700;">A.     KOMENTAR DAN SARAN</h4>
          <table class="table table-striped" style="width: 100%">
          	<tr>
          		<th style="text-align:center">HAL YANG POSITIF</th>
          		<th style="text-align:center">HAL YANG PERLU DITINGKATKAN</th>
          	</tr>
          	<?php 
          	$positif = array();
          	$ditingkatkan = array();
          	foreach($mentoring as $mentoring){
          		$positif[] = $mentoring->hal_positif;
          		$ditingkatkan[] = $mentoring->hal_ditingkatkan;
          	}
          	if(count($positif)>count($ditingkatkan)){
          		$count = count($positif);
          	}else{
          		$count = count($ditingkatkan);
          	}
          	
          	
          	
          		
          	
          	?>
          	<tr>
          		<style>
ol.s li {list-style-type: upper-greek !important; padding-left: 50px}
</style>
          		<td >
          		<ol class="s">
          			
          		<?php 
					       			
          		for($i=0;$i<count($positif);$i++){
					echo '<li>'.$positif[$i].'</li>';          			
				}
				if(!count($positif)) echo '<span style="text-align:center">Data tidak ada</span>';
          		?>
          		</ol>
          			
          		</td>
          		<td >
          		



          		<ol class="s">
          			
          		<?php 
					        			
          		for($i=0;$i<count($ditingkatkan);$i++){
					echo '<li>'.$ditingkatkan[$i].'</li>';          			
				}
				if(!count($ditingkatkan)) echo '<span style="text-align:center">Data tidak ada</span>';
          		?>
          		</ol></td>
          	</tr>
          	
          </table>
          <h4 class=" mb-2" style="font-weight: 700;">B.      TINDAK LANJUT</h4>
          <?php 
          $list = array(
          	"Bonus","Kenaikan gaji karena Appraisal","Kenaikan Grade","Mutasi Rotasi","Diangkat menjadi pegawai tetap","Perpanjangan Kontrak Kerja","Pemutusan hubungan kerja","Training yang perlu diikuti","Usulan karier berikutnya"
          	);
          	for($i=0;$i<count($list);$i++){
          		$row = str_replace(" ","_",strtolower($list[$i]));
          		$selected = $kpi[0]->$row?'checked':'';
          		echo "<br><input type='checkbox' value=1 style='width:50px' disabled $selected> ".$list[$i].'<hr>'; 
          	}
          ?>
          
          <table style="width: 100%;margin-top: 30px">
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
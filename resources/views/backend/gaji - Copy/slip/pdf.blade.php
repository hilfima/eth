<div class="content container-fluid">
			
        <link rel="stylesheet" href="<?= url('plugins/purple/assets/css/style.css')?>">
		<link rel="stylesheet" href="{!! asset('plugins/fontawesome-free/css/all.min.css') !!}">	
        <link rel="stylesheet" href="<?= url('plugins/purple/assets/css/font-awesome.min.css')?>">
		<link rel="stylesheet" href="{!! asset('plugins/select2/css/select2.min.css') !!}">
	    <link rel="stylesheet" href="{!! asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') !!}">

		<!-- Lineawesome CSS -->
        <link rel="stylesheet" href="<?= url('plugins/purple/assets/css/line-awesome.min.css')?>">
		<link rel="stylesheet" href="{!! asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') !!}">
    	<link rel="stylesheet" href="{!! asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') !!}">

		<!-- Main CSS -->
        <link rel="stylesheet" href="<?= url('plugins/purple/assets/css/style.css')?>">
		<!-- Datatable CSS -->
		<link rel="stylesheet" href="<?= url('plugins/purple/assets/css/dataTables.bootstrap4.min.css')?>">
		 <!-- Select2 .table-bordered td,
.table-bordered th {
 border:1px solid #dee2e6
 
}-->
	    
		
		<link rel="stylesheet" href="{!! asset('plugins/fontawesome-free/css/all.min.css') !!}">
		<style>
			.list-unstyled {
 padding-left:0;
 list-style:none
}.table {
  width: 100%;
  margin-bottom: 1rem;
  color: #212529;
}
table {
  border-collapse: collapse;
}.table-bordered td,
.table-bordered th {
 border-bottom:1px solid #dee2e6
 
}

		</style>
				
					<!-- /Page Title -->
					
					<div class="row">
						<div class="col-md-12">
							<div class="card-box">
							<?php 
						if($karyawan[0]->m_lokasi_id==3){
							$logo = 'Logo Rea Arta Mulia.png';
						}else if($karyawan[0]->m_lokasi_id==4){
							$logo = 'Logo EMM_Page12.png';
						}else if($karyawan[0]->m_lokasi_id==5){
							$logo = 'cc.png';
						}else if($karyawan[0]->m_lokasi_id==2){
							$logo = 'Logo SJP Guideline.png';
						}else  if($karyawan[0]->m_lokasi_id==9){
							$logo = 'Logo ASA.png';
						}else if($karyawan[0]->m_lokasi_id==13){
							$logo = 'Logo Mafaza Hires.png';
						}else if($karyawan[0]->m_lokasi_id==6){
							$logo = 'JKA LOGO.png';
						}else 
							$logo = 'logo.png';
						

						?>
							<table style="width:100%">
							<tr>
								
								<td><img src="<?= url('dist/img/logo/'.$logo);?>" style="height:70px;margin-left:5%;vertical-align:middle" alt=""></td>
								<td style="text-align: center;">
									<div style="margin-left: -20%">	
				
					<h3 style="margin-bottom: 0"><?=$karyawan[0]->nmlokasi;?></h3>
					<div  style="margin-bottom: 0"><?=$karyawan[0]->alamatlokasi;?></div>
									</div>
								</td>
							</tr>
							</table>
							<hr>
								<h4 class="payslip-title" style="margin-bottom: 0">Slip Gaji	</h4>
								<div class="" style="text-align: center"> <span><?=$help->bulan($generate[0]->bulan);?>, <?=($generate[0]->tahun);?></span></div>
								
								<div class="row">
										
									<div class="col-lg-12 m-b-20">
										<ul class="list-unstyled">
											<li><h4 class="mb-0" style="margin-bottom: 0"><strong><?=$karyawan[0]->nama_lengkap;?></strong></h4></li>
											<li><span><?=$karyawan[0]->nmjabatan;?> </span></li>
											<li>Departement <?=$karyawan[0]->nmdept;?></li>
											<li><?=$karyawan[0]->nmpangkat;?></li>
											<li><?=$karyawan[0]->nama_grade;?></li>
										</ul>
									</div>
								</div>
								
								<div class="row">
									<div class="col-sm-12">
										<div>
											<h4 class="m-b-10"><strong>Data Kerja</strong></h4>
											<table class="table table-bordered">
												<tbody>
													<?php 
													$id_kary = $karyawan[0]->p_karyawan_id;;
													$sql = "select *
										from prl_gaji a 
										join prl_gaji_detail b on b.prl_gaji_id = a.prl_gaji_id 
										join m_gaji_absen on b.gaji_absen_id = m_gaji_absen.m_gaji_absen_id 
										where prl_generate_id = $id_prl and 
										p_karyawan_id = $id_kary  and 
										b.type=1 and b.active=1 and 
										m_gaji_absen.active =1
										 and b.active=1
										order by prl_gaji_detail_id  desc,nominal desc
		 
										";
                                                $row = DB::connection()->select($sql);
                                                $data = array();
                                                $masuk = 0;
                                                $sudah = array();
                                                foreach ($row as $row) {
                                                    if (!in_array($row->m_gaji_absen_id, $sudah)) {
                                                        if ($row->nama_gaji == 'Hari Absen')
                                                            $masuk = round($row->nominal);
															echo '<tr>
																<td>'.$row->nama_gaji.'</td>
																<td >'.round($row->nominal).'</td>
															</tr>';
														 $sudah[] = $row->m_gaji_absen_id;
													}
												}
												?>
										</tbody>
									</table>
								</div>
							</div>
								<div class="col-sm-12">
										<div>
											
											<table class="table table-bordered">
												<tbody>
												
												<?php 
													$id_kary =$karyawan[0]->p_karyawan_id;;
													$sql="select *,case when type=1 then (select nama_gaji from m_gaji_absen where b.gaji_absen_id = m_gaji_absen.m_gaji_absen_id )
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
													and b.active=1 and b.active=1
													order by prl_gaji_detail_id  desc,nominal desc
											 		";
											$row = DB::connection()->select($sql);
											$data= array();
											$total_tunjangan = 0;
											$i=0;
											$table[$i][] = '<h4 class="m-b-10"><strong>Tunjangan</strong></h4>';
											$table[$i][] = '';
											$table[$i][] = '';
											$table[$i][] = '<span style="color:#fff">slip</span>';
											$table1[$i][] = '<h4 class="m-b-10"><strong>Potongan</strong></h4>';
											$table1[$i][] = '';
											$table1[$i][] = '';
											$i++;
											 $data = array();
                                                $total_tunjangan = 0;
                                                $sudah = array();
                                                foreach ($row as $row) {
                                                    if (!isset($data[$row->nama]))
                                                        $data[$row->nama] = 0;


                                                    if (!in_array($row->nama, $sudah)) {
                                                        if ($row->nama == 'Upah Harian') {
                                                            $row->nominal = $row->nominal * $masuk;
                                                        }
                                                        
														$total_tunjangan += $row->nominal;
														$table[$i][] = $row->nama;
														$table[$i][] = 'Rp ';
														$table[$i][] = $help->rupiah($row->nominal,0,'');
														$table[$i][] = '';
															//echo '<tr>
															//	<td>'.$row->nama.'</td>
															//	<td>'.$help->rupiah($row->nominal).'</td>
															//</tr>';
														$i++;
			 											$sudah[] = $row->nama;
                                                        $data[$row->nama] = $row->nominal;
                                                    }
												}
												$table[$i][] = '<b>TOTAL TUNJANGAN</b>';
												$table[$i][] = 'Rp ';
												$table[$i][] = '<b>'.$help->rupiah($total_tunjangan,0,'').'</b>';
												$table[$i][] = '';
												//echo '<tr>
												//	<td><h4><b>TOTAL TUNJANGAN</b></h4></td>
												//	<td>'.$help->rupiah($total_tunjangan).'</td>
												//</tr>'
												?>
												
											<!--	</tbody>
											</table>
										</div>
									</div>
									<div class="">
										<div>
											<h4 class="m-b-10"><strong>Potongan</strong></h4>
											<table class="table table-bordered">
												<tbody>-->
												<?php 
													$id_kary = $karyawan[0]->p_karyawan_id;;
													$sql="select *,case when type=1 then (select nama_gaji from m_gaji_absen where b.gaji_absen_id = m_gaji_absen.m_gaji_absen_id ) 
		when type=2 then (select nama from prl_tunjangan join m_tunjangan on prl_tunjangan.m_tunjangan_id = m_tunjangan.m_tunjangan_id where b.prl_tunjangan_id = prl_tunjangan.prl_tunjangan_id )
		when type=3 then (select nama from m_tunjangan where b.m_tunjangan_id = m_tunjangan.m_tunjangan_id)
		when type=4 then (select nama from prl_potongan join m_potongan on prl_potongan.m_potongan_id = m_potongan.m_potongan_id where b.prl_potongan_id = prl_potongan.prl_potongan_id )
		when type=5 then (select nama from m_potongan where b.m_potongan_id = m_potongan.m_potongan_id)
		 end as nama
												from prl_gaji a 
											 	join prl_gaji_detail b on b.prl_gaji_id = a.prl_gaji_id 
											 	
											 	where prl_generate_id = $id_prl and 
											 		p_karyawan_id = $id_kary  and 
											 		b.type in (4,5)   and b.active=1
												order by prl_gaji_detail_id  desc,nominal desc 
											 		";
											$row = DB::connection()->select($sql);
											$data= array();
											$total_potongan = 0;
											$data = array();
                                                $total_potongan = 0;
                                                $sudah = array();
											$i=1;
											foreach($row as $row){
												if (!in_array($row->nama, $sudah)) {

													$total_potongan += $row->nominal;
													$table1[$i][] = $row->nama;
													$table1[$i][] = 'Rp ';
													$table1[$i][] = $help->rupiah($row->nominal,0,'');
													$i++;
													 $sudah[] = $row->nama;
                                                    }
												}
												$table1[$i][] ='<b>TOTAL POTONGAN</b>'; 
												$table1[$i][] = 'Rp ';
												$table1[$i][] ='<b>'.$help->rupiah($total_potongan,0,'').'</b>'; 
												//echo '<tr>
												//	<td><h4><b>TOTAL Potongan</b></h4></td>
												//	<td>'.$help->rupiah($total_potongan).'</td>
												//</tr>'
												?>
											
												<?php 
											
												for($i=0;$i<count($table1);$i++){
													echo '<tr>';
													$style='';
													
													if(!isset($table[$i])){
														
														echo '<td style=";border-bottom:1px solid #fff!important;border-right:1px solid #fff"></td>';
														echo '<td 	style=";border-bottom:1px solid #fff!important;border-right:1px solid #fff"></td>';
														echo '<td style=";border-bottom:1px solid #fff!important;border-right:1px solid #fff"></td>';
														echo '<td style=";border-bottom:1px solid #fff!important"></td>';
													}
													else{
														
														for($j=0;$j<count($table[$i]);$j++){
															
															
															if($j==2)
															$style = 'style="text-align:right"';
															echo '<td '.$style.'>'.$table[$i][$j].'</td>';
															
														}
													}
													for($j=0;$j<count($table1[$i]);$j++){
														
														if($j==0)
															$style = 'style="text-align:left"';
															if($j==2)
															$style = 'style="text-align:right"';
														echo '<td  '.$style.'>'.$table1[$i][$j].'</td>';
													}
													
													echo '</tr>';
												}?>
													
												
												</tbody>
											</table>
										</div>
									</div>
						</div>
					<b>THP: <?=$help->rupiah($total_tunjangan-$total_potongan).'</b><br>'.$help->terbilang($total_tunjangan-$total_potongan).'';?>
					</div>
										
                </div>

				<!-- /Page Content -->
            </div>
	<!-- /.card -->
	<!-- /.card -->
	<!-- /.content -->
</div>
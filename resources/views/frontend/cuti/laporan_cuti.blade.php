@extends('layouts.app_fe')

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content ">
	@include('flash-message')
<div class="row">
	<div class="col-xl-3 col-lg-4 col-md-12 theiaStickySidebar">
		<?= view('layouts.app_side'); ?>
	</div>
	<div class="col-xl-9 col-lg-8 col-md-12">

	<!-- Content Header (Page header) -->
	<div class="card shadow-sm ctm-border-radius">
		<div class="card-body align-center">
			<h4 class="card-title float-left mb-0 mt-2">Laporan Cuti</h4>
			<ul class="nav nav-tabs float-right border-0 tab-list-emp">

<li class="nav-item pl-3">

</li>
</ul>
		</div>
	</div>
	<div class="row"> 
	<?php $no=0;$nominal=0;
	$tahun = array();
                   		$tahunbesar = array();
                   		$datasisa = array();
                   		$hutang = 0;
                   		$jumlah = 0;
                   		foreach($tanggal_loop as $i=> $loop){
						
							if($all[$i]['tanggal']<=date('Y-m-d')){
							$return = $help->perhitungan_cuti2($all,$datasisa,$hutang,$date,$i,$nominal,$jumlah);
							$datasisa =$return['datasisa'];
							$hutang =$return['hutang'];
							$nominal =$return['nominal'];
							$jumlah =$return['jumlah'];
							}
							}
                   				
                   				
                                if(isset($datasisa)){
                                	asort($datasisa);
									$totalcuti = 0;
                                	foreach($datasisa as $value=>$key){
                                		$tahun = $value; 
                                		if($value>2000)
                                			$value = 'Sisa Cuti Tahun '.$value;
                                		else
                                			$value = 'Sisa Cuti Besar ke '.$value;
										//echo $value.' : 	'.$key.'<br>';
										if($key  or $tahun>=date('Y')-1){
											$totalcuti +=$key;
										?>
									
											<div class="col-md-3">
												
											<div class="card dash-widget ctm-border-radius shadow-sm">
												<div class="card-body">
													 
													<div class="card-right">
														<h4 class="card-title"><?=$value?></h4>
														<p class="card-text"> <?=$key?> <span class="info-box-number"></span>
														</p>
													</div>
												</div>
											</div>
											</div>
											<?php 
											}
										}

								if($hutang){?>
									<div class="col-md-3">
		
	<div class="card dash-widget ctm-border-radius shadow-sm">
		<div class="card-body">
			 
			<div class="card-right">
				<h4 class="card-title">Hutang Cuti</h4>
				<p class="card-text"> <?=$hutang?> <span class="info-box-number"></span>
				</p>
			</div>
		</div>
	</div>
	</div>
								<?php }
}

                                ?>
                   		
	<div class="col-md-12">
		
	<div class="card dash-widget ctm-border-radius shadow-sm">
		<div class="card-body">
			<div class="card-icon bg-primary">
				<i class="fa fa-users" aria-hidden="true"></i>
			</div>
			<div class="card-right">
				<h4 class="card-title">Total Sisa Cuti</h4>
				<p class="card-text">Sisa Cuti Anda : <?=$totalcuti?><span class="info-box-number"></span>
				</p>
			</div>
		</div>
	</div>
	</div>
	
	</div>
	<div class="card">
		<div class="card-header">
		</div>
		<div class="card-body">
                <table id="" class="table table-bordered table-striped">
                    <thead>
                    <tr>
                        <th>No.</th>
                        <th>Tanggal</th>
                        <th>Keterangan</th>
                        <th>Status Pengajuan</th>
                        <th>Jumlah</th>
                        <th>Sisa</th>
                        <th>Rekap</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $no=0;$nominal=0; ?>
                    @if(!empty($cuti))
                   		<?php 
                   		$tahun = array();
                   		$tahunbesar = array();
                   		$datasisa = array();
                   		$hutang = 0;
                   		foreach($tanggal_loop as $i=> $loop){
						
							if($all[$i]['tanggal']<=date('Y-m-d')){
							$return = $help->perhitungan_cuti2($all,$datasisa,$hutang,$date,$i,$nominal,$jumlah);
							$datasisa =$return['datasisa'];
							$hutang =$return['hutang'];
							$nominal =$return['nominal'];
							$jumlah =$return['jumlah'];
                   				
                   			?>
                   			<tr>
                            <?php $no++;?>
                                <td>{!! $no !!}</td>
					<td>{!! $help->tgl_indo($all[$i]['tanggal']) !!}</td>
					<td>{!! $all[$i]['keterangan'] !!}</td>
					<td >
						@if($all[$i]['status']==1)
						<span class="fa fa-check-circle"> Disetujui</span>
						@elseif($all[$i]['status']==2)
						<span class="fa fa-window-close"> Ditolak</span>
						@else
						<span class="fa fa-edit"> Pending</span>
						@endif
					</td>
                               
                                
					<td style="width: 120px">{!! $jumlah !!}</td>
                                
					<td style="width: 120px">{!! ($nominal) !!}</td>
					<td><?php
						if(isset($datasisa)){
									
						foreach($datasisa as $value=>$key){
						if($value>2000)
						$value = 'Sisa Cuti Tahun '.$value;
						else
						$value = 'Sisa Cuti Besar ke '.$value;
						if($key)
						echo $value.' : 	'.$key.'<br>';
						}
						//$tahun_akhir =$value;
						}
						if($hutang)
						echo 'Hutang : '.$hutang;
						//print_r($datasisa);
						?></td>
					
                            </tr>
                   		<?php }?>
                   		<?php }?>
                    @endif
                </table>
            </div>
	</div>
</div>

	@endsection
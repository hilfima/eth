@extends('layouts.appsA')

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content ">
	@include('flash-message')
	<!-- Content Header (Page header) -->
	<div class="card shadow-sm ctm-border-radius">
		<div class="card-body align-center">
			<h4 class="card-title float-left mb-0 mt-2">Laporan Cuti</h4>
			<ul class="nav nav-tabs float-right border-0 tab-list-emp">

				<li class="nav-item pl-3">
					<a href="{!! route('be.tambah_laporan_cuti_karyawan') !!}" class="btn btn-primary text-white">+ Input Cuti</a>
				</li>
			</ul>
		</div>
	</div>
	
	<div class="card">
		<div class="card-body">
			<form id="cari_absen" class="form-horizontal" method="get" action="{!! route('be.laporan_cuti_karyawan') !!}">
				<div class="form-group">
					<label>Nama</label>
					<select class="form-control select2" name="nama" style="width: 100%;">
						<option value="">Pilih Nama</option>
						<?php
						foreach($users AS $users){
						if($users->p_karyawan_id==$nama){
						echo '<option selected="selected" value="'.$users->p_karyawan_id.'">'.$users->nama.' ('.$users->nmdept.') '. '</option>';
						}
						else{
						echo '<option value="'.$users->p_karyawan_id.'">'.$users->nama.' ('.$users->nmdept.') '. '</option>';
						}
						}
						?>
					</select>
				</div>
				<a href="{!! route('be.laporan_cuti_karyawan') !!}" class="btn btn-primary"><span class="fa fa-sync"></span> Reset</a>
				<button type="submit" name="Cari" class="btn btn-primary" value="Cari"><span class="fa fa-search"></span> Cari</button>
			</form>
		</div> 
	</div> 
	<div class="row"> 
		<?php $no=0;$nominal=0;
		$tahun = array();
		$tahunbesar = array();
		$datasisa = array();
		$potong_gaji = array();
		$hutang = 0;
		$jumlah = 0;
		$ipg = array();
		foreach($tanggal_loop as $i=> $loop){
		if($all[$i]['tanggal']<=date('Y-m-d')){
		$return = $help->perhitungan_cuti2($all,$datasisa,$hutang,$date,$i,$nominal,$jumlah,$ipg,$potong_gaji);
		$datasisa =$return['datasisa'];
		$hutang =$return['hutang'];
		$nominal =$return['nominal'];
		$jumlah =$return['jumlah'];
		$ipg =$return['ipg'];
				$potong_gaji = $return['potong_gaji'];
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
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
				<?php $no=0;$nominal=0; ?>
				@if(!empty($cuti))
				<?php 
				$tahun = array();
				$tahunbesar = array();
				$datasisa = array();
				$ipg = array();
				$potong_gaji = array();
				$hutang = 0;
				$jumlah = 0;
				//for($i=0;$i<count($date);$i++){
				foreach($tanggal_loop as $i=> $loop)
				{
						
				if($all[$i]['tanggal']<=date('Y-m-d'))
				{
					$return 		= $help->perhitungan_cuti2($all,$datasisa,$hutang,$date,$i,$nominal,$jumlah,$ipg,$potong_gaji);
					$datasisa 		= $return['datasisa'];
					$hutang 		= $return['hutang'];
					$nominal 		= $return['nominal'];
					$jumlah 		= $return['jumlah'];
					$ipg 			= $return['ipg'];
					$potong_gaji 	= $return['potong_gaji'];
				
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
						
						$ex = explode('-',$all[$i]['tanggal']);
						if(count($ex)>=2){
							if(!isset($ex[1])){
							    print_r($ex);die;
							}
    						if(isset($ipg[$ex[0]][$ex[1]]))
    							echo '<br>IPG Bln '.($ex[1]).' : '.$ipg[$ex[0]][$ex[1]];						
						}
						?></td>
					<td>  
						<?php if(in_array($all[$i]['jenis'],[1,3,8,5])){?>
							<a href="{!! route('be.edit_cuti',$all[$i]['id']) !!}" title='Ubah' data-toggle='tooltip'><span class='fa fa-edit'></span></a>
							<a href="{!! route('be.delete_cuti',$all[$i]['id']) !!}" title='Ubah' data-toggle='tooltip'><span class='fa fa-trash'></span></a>
						
						<?php }?>
                                	
					</td>
				</tr>
				<?php }?>
				<?php }?>
				@endif
			</table>
		</div>
	</div>
</div>

@endsection
@extends('layouts.appsA')

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	@include('flash-message')
	<!-- Content Header (Page header) -->
	<div class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6">
					<h1 class="m-0 text-dark"> Rekap Absensi Kehadiran</h1>
				</div><!-- /.col -->
				
			</div><!-- /.row -->
		</div><!-- /.container-fluid -->
	</div>
	<!-- /.content-header -->

	<!-- Main content -->
	<div class="row">
	<div class="col-md-6">
	<div class="dash-widget clearfix card-box" href="http://203.210.84.185:84/backend/karyawan">
								<span class="dash-widget-icon" style="vertical-align: middle;display: flex;justify-content: center;align-content: center;align-items: center;"><i class="fa fa-user"></i></span>
								<div class="dash-widget-info">
									<h3> <?=round($total[0]->total_hadir/$total[0]->total_karyawan*100,2);?>%</h3>
									<span>Presentase Hadir</span>
								</div>
							</div>
	</div>
	<div class="col-md-6">
	<div class="dash-widget clearfix card-box" href="http://203.210.84.185:84/backend/karyawan">
								<span class="dash-widget-icon" style="vertical-align: middle;display: flex;justify-content: center;align-content: center;align-items: center;"><i class="fa fa-user"></i></span>
								<div class="dash-widget-info">
									<h3> <?=$total[0]->total_hadir;?> Dari <?=$total[0]->total_karyawan;?></h3>
									<span>Total Karyawan</span>
								</div>
							</div>
	</div>
	</div>
	<div class="card">
		<div class="card-header">
			<!--<h3 class="card-title">DataTable with default features</h3>-->
			<a href="{!! route('be.tambah_karyawan_agenda',$id) !!}" title='Tambah' data-toggle='tooltip'><span class='fa fa-plus'></span>  Tambah Karyawan  </a>
			<a href="{!! route('be.qr_absen',$id) !!}" title='Tambah' data-toggle='tooltip'><span class='fa fa-qrcode'></span>  Absen Kehadiran Karyawan  </a>
		</div> 
		
		<!-- /.card-header -->
		<div class="card-body">

			<table id="example1" class="table table-striped custom-table mb-0">
				<thead>
					<tr>
						<th>No.</th>

						<th>Nama Karyawan</th>
						<th>Status</th>
						<th>Tanggal</th>
						<th>Jam</th>
					</tr>
				</thead>
				<tbody>
				<?php $no=0 ?>
				@if(!empty($karyawan))
				@foreach($karyawan as $jadwal_training)
				<?php $no++ ?>
				<tr>
					<td>{!! $no !!}</td>
					<td>{!! $jadwal_training->nama !!}</td>
					<td>
						@if($jadwal_training->absensi_kehadiran==1)
							Hadir
						@elseif($jadwal_training->absensi_kehadiran==2)
							Tidak Hadir
						@endif
					</td>
					<td>{!! $jadwal_training->tanggal_datang !!}</td>
					<td>{!! $jadwal_training->waktu_datang !!}</td>
					
				
					

					

				</tr>
				
				@endforeach
				@endif
			</table>
		</div>
		<?php
		// print_r($agenda_perusahaan);
		?>
		<!-- /.card-body -->
	</div>
	<!-- /.card -->
	<!-- /.card -->
	<!-- /.content -->
</div>
<!-- /.content-wrapper -->
@endsection
3333
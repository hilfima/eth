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
					<h1 class="m-0 text-dark"> List Konfirmasi Kehadiran</h1>
				</div><!-- /.col -->
				
			</div><!-- /.row -->
		</div><!-- /.container-fluid -->
	</div>
	<!-- /.content-header -->
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
	<!-- Main content -->
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
						<th>Konfirmasi Kehadiran</th>
						<th>Absensi Kehadiran</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
				<?php $no=0 ?>
				@if(!empty($jadwal_training))
				@foreach($jadwal_training as $jadwal_training)
				<?php $no++ ?>
				<tr>
					<td>{!! $no !!}</td>
					<td>{!! $jadwal_training->nama !!}</td>
					<td>
						@if($jadwal_training->konfirmasi_kehadiran==1)
							Hadir
						@elseif($jadwal_training->konfirmasi_kehadiran==2)
							Tidak Hadir
						@else
							Belum Konfirmasi
						@endif
						
						
						
					</td>
					<td>
						@if($jadwal_training->absensi_kehadiran==1)
							Hadir
						@elseif($jadwal_training->absensi_kehadiran==2)
							Tidak Hadir
						@else
							Belum Konfirmasi
						@endif
					</td>
				
					

					<td style="text-align: center">
						<a href="{!! route('be.hapus_list_karyawan_agenda',[$id,$jadwal_training->t_agenda_perusahaan_karyawan_id]) !!}" title='Hadir' data-toggle='tooltip'>
						<i class="fa fa-trash" aria-hidden="true"></i>
					</a><a href="{!! route('be.absen_kehadiran_agenda_presensi_hadir',[$id,$jadwal_training->t_agenda_perusahaan_karyawan_id]) !!}" title='Presensi -> Hadir' data-toggle='tooltip'>
						<i class="fa fa-check" aria-hidden="true"></i>
					</a>
					<a href="{!! route('be.absen_kehadiran_agenda_presensi_tdkhadir',[$id,$jadwal_training->t_agenda_perusahaan_karyawan_id]) !!}" title='Presensi -> Tidak Hadir' data-toggle='tooltip'>
						<i class="fa fa-times" aria-hidden="true"></i>
					</a>
					</td>

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

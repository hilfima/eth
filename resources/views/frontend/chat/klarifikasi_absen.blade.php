@extends('layouts.app_fe')

@section('content')
<!-- Content Wrapper. Contains page content -->

	@include('flash-message')
	<!-- Content Header (Page header) -->
	<div class="row">
	<div class="col-xl-3 col-lg-4 col-md-12 theiaStickySidebar">
		<?= view('layouts.app_side'); ?>
	</div>
	<div class="col-xl-9 col-lg-8 col-md-12">

	<!-- Content Wrapper. Contains page content -->
	<div class="content">
	@include('flash-message')
	<!-- Content Header (Page header) -->
	<div class="card shadow-sm ctm-border-radius">
		<div class="card-body align-center">
			<h4 class="card-title float-left mb-0 mt-2">Klarifikasi Absen</h4>

		</div>
	</div>
	<style>
		strong{
			font-weight: 900;
		}
	</style>

	<!-- /.content-header -->

	<!-- Main content -->
	<div class="card">
	<div class="card-header">
		<h3 class="card-title">Klarifikasi Absen</h3>
	</div>
	<div class="card-body">

			<table id="example1" class="table table-striped custom-table mb-0">
				<thead>
					<tr>
						<th>No.</th>

						<th>Nama Karyawan</th>
						<th>Topik</th>
						<th>Tanggal</th>
						<th>Klarifikasi</th>
						<th>Deskripsi</th>
						<th>Status</th>
						<th>Atasan</th>
						<th>Approval Atasan</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
				<?php $no=0 ?>
				@if(!empty($chat_list))
				@foreach($chat_list as $chat_room)
				<?php $no++ ?>
				<tr>
					<td>{!! $no !!}</td>
					<td><?= $chat_room->nama; ?></td>
					<td><?= $chat_room->topik; ?></td>
					<td><?= str_replace('Klarifikasi Absen Tanggal','',$chat_room->topik)?str_replace('Klarifikasi Absen Tanggal','',$chat_room->topik):$chat_room->tanggal; ?></td>
					<td><?php
					if ($chat_room->tujuan==1)
						echo 'Absensi - Finger tidak terbaca'; else if ($chat_room->tujuan==2)
						echo 'Absensi - Izin'; else if ($chat_room->tujuan==3)
						echo 'Absensi - Sakit'; else if ($chat_room->tujuan==4)
						echo 'Absensi - Mesin absen Error'; else if ($chat_room->tujuan==5)
						echo 'Absensi - Lainnya'; else if ($chat_room->tujuan==6)
						echo 'Gaji -  Kelebihan bayar'; else if ($chat_room->tujuan==7)
						echo 'Gaji -  Kekurangan bayar'; else if ($chat_room->tujuan==8)
						echo 'Gaji -  Lainnya'; else if ($chat_room->tujuan==9)
						echo 'Lainnya'; ?></td>
					<td><?= $chat_room->deskripsi; ?></td>
					<td><?php
					if ($chat_room->selesai==0) {
						echo 'Proses HR';
					} else if ($chat_room->selesai==1) {
						echo 'Proses Atasan';
					} else if ($chat_room->selesai==2) {
						echo 'Entry Perubahan Absen';
					} else if ($chat_room->selesai==3) {
						echo 'Selesai';
					}; ?></td>
					
					<td><?= $chat_room->nama_atasan; ?></td>

					<td><?php
					if ($chat_room->appr_status==3) {
						echo 'Pending';
					} else if ($chat_room->appr_status==1) {
						echo 'Setuju';
					} else if ($chat_room->appr_status==2) {
						echo 'Tolak';
					}; ?></td>


					<td style="text-align: center">
						
						<a href="{!!route('fe.appr_atasan_klarifikasi',$chat_room->chat_room_id)!!}" class="btn btn-theme button-1 ctm-border-radiusfloat-right">
							<i class="fa fa-edit"></i></a>
						
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

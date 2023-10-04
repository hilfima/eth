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
					<h1 class="m-0 text-dark">Kotak Laporan</h1>
				</div><!-- /.col -->
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><a href="{!! route('admin') !!}">Admin</a></li>
						<li class="breadcrumb-item active">Kotak Laporan</li>
					</ol>
				</div><!-- /.col -->
			</div><!-- /.row -->
		</div><!-- /.container-fluid -->
	</div>
	<!-- /.content-header -->

	<!-- Main content -->
	<div class="card">

		<!-- /.card-header -->
		<div class="card-body">
			<table id="example1" class="table table-striped custom-table mb-0">
				<thead>
					<tr>
						<th>Kotak Laporan ID</th>
						<th>Nama</th>
						<th>Judul</th>
						<th>Tanggal</th>
						<th>Status</th>

						<th>Action</th>
					</tr>
				</thead>
				<tbody>
				<?php $no=0 ?>
				@if(!empty($Kotak_laporan))
				<?php
				foreach ($Kotak_laporan as $Kotak) { ?>
				<?php $no++ ?>
				<tr>
					<td>Kotak Laporan <?=sprintf('%03d',$Kotak->t_kotak_laporan_id) ?></td>
					<td><?=$Kotak->nama; ?></td>
					<td><?=$Kotak->judul_laporan; ?></td>
					<td><?=$help->tgl_indo($Kotak->tgl_kejadian); ?></td>
					<td><?php 
						if ($Kotak->status_appr==1)
							echo 'Pending';
						else if ($Kotak->status_appr==2)
							echo 'Diterima';
						else if ($Kotak->status_appr==3)
							echo 'Diproses';
						else if ($Kotak->status_appr==4)
							echo 'Selesai';
					 ?></td>

					<td>
						<a href="{!! route('be.baca_kotak_laporan',$Kotak->t_kotak_laporan_id) !!}" target="_blank" title="Download"><span class="fa fa-search"></span></a>


					</td>



					</td>
				</tr>
				<?php
			} ?>
				@endif
			</table>
		</div>
		<!-- /.card-body -->
	</div>
	<!-- /.card -->
	<!-- /.card -->
	<!-- /.content -->
</div>
<!-- /.content-wrapper -->
@endsection

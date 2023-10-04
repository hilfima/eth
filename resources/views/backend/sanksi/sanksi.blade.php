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
					<h1 class="m-0 text-dark"> Sanksi</h1>
				</div><!-- /.col -->
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><a href="{!! route('be.role') !!}">Sanksi</a></li>
						<li class="breadcrumb-item active"> Sanksi</li>
					</ol>
				</div><!-- /.col -->
			</div><!-- /.row -->
		</div><!-- /.container-fluid -->
	</div>
	<!-- /.content-header -->

	<!-- Main content -->
	<div class="card">
		<div class="card-header">
			<!--<h3 class="card-title">DataTable with default features</h3>-->
			<a href="{!! route('be.tambah_sanksi') !!}" title='Tambah' data-toggle='tooltip'><span class='fa fa-plus'></span>  Sanksi </a>
		</div>
		<!-- /.card-header -->
		<div class="card-body">

			<table id="example1" class="table table-striped custom-table mb-0">
				<thead>
					<tr>
						<th>No.</th>

						<th>Nama Karyawan</th>
						<th>Sanksi</th>
						<th>Tanggal Belaku Sanksi</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
				<?php $no=0 ?>
				@if(!empty($Sanksi))
				@foreach($Sanksi as $Sanksi)
				<?php $no++ ?>
				<tr>
					<td>{!! $no !!}</td>
					<td>{!! $Sanksi->nama !!}</td>
					<td>{!! $Sanksi->nama_sanksi !!}</td>
					<td>{!! $help->tgl_indo($Sanksi->tgl_awal_sanksi) !!} s/d {!! $help->tgl_indo($Sanksi->tgl_akhir_sanksi) !!}</td>
					<td style="text-align: center">

						<a href="{!! route('be.edit_sanksi',$Sanksi->p_karyawan_sanksi_id) !!}" title='Ubah' data-toggle='tooltip'><span class='fa fa-edit'></span></a>
						<a href="{!! route('be.hapus_sanksi',$Sanksi->p_karyawan_sanksi_id) !!}" title='Hapus' data-toggle='tooltip'><span class='fa fa-trash'></span></a>
					</td>

				</tr>
				@endforeach
				@endif
			</table>
		</div>
		<?php
		// print_r($Sanksi);
		?>
		<!-- /.card-body -->
	</div>
	<!-- /.card -->
	<!-- /.card -->
	<!-- /.content -->
</div>
<!-- /.content-wrapper -->
@endsection

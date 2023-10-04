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
					<h1 class="m-0 text-dark">Employee Care</h1>
				</div><!-- /.col -->
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><a href="{!! route('admin') !!}">Admin</a></li>
						<li class="breadcrumb-item active">Employe Care</li>
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
					<th>No</th>
					<th>Tanggal</th>
					<th>Nama</th>

                    <th>Judul</th>
                    <th>Status</th>
					
					<th>Action</th>
				</tr>
			</thead>
			<tbody>
			<?php $no=0 ?>
			@if(!empty($keluh_kesah))
			<?php
			foreach ($keluh_kesah as $keluh) { ?>
			<?php $no++ ?>
			<tr>
				<td><?=$no ?></td>
				<td><?=$keluh->create_date;?></td>
				<td><?=$keluh->nama;?></td>
				<td><?=$keluh->judul;?></td>
				<td><?=$keluh->status;?></td>


				<td>
					<a href="{!! route('be.baca_keluh_kesah',$keluh->t_keluh_kesah_id) !!}" target="_blank" title="Download"><span class="fa fa-search"></span></a>


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

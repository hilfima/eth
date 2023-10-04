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
					<h1 class="m-0 text-dark"> Reward</h1>
				</div><!-- /.col -->
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><a href="{!! route('be.role') !!}">Reward</a></li>
						<li class="breadcrumb-item active"> Reward</li>
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
			<a href="{!! route('be.tambah_reward') !!}" title='Tambah' data-toggle='tooltip'><span class='fa fa-plus'></span>  Reward </a>
		</div>
		<!-- /.card-header -->
		<div class="card-body">

			<table id="example1" class="table table-striped custom-table mb-0">
				<thead>
					<tr>
						<th>No.</th>

						<th>Nama Karyawan</th>
						<th>Penghargaan</th>
						<th>Tanggal Penghargaan</th>
						<th>Hadiah</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
				<?php $no=0 ?>
				@if(!empty($Reward))
				@foreach($Reward as $Reward)
				<?php $no++ ?>
				<tr>
					<td>{!! $no !!}</td>
					<td>{!! $Reward->nama !!}</td>
					<td>{!! $Reward->nama_jenis_reward !!}</td>
					<td>{!! $Reward->tgl_award !!}</td>
					<td>{!! $Reward->hadiah !!}</td>
					<td style="text-align: center">

<a href="{!! route('be.edit_reward',$Reward->p_karyawan_award_id) !!}" title='Ubah' data-toggle='tooltip'><span class='fa fa-edit'></span></a>
<a href="{!! route('be.hapus_reward',$Reward->p_karyawan_award_id) !!}" title='Hapus' data-toggle='tooltip'><span class='fa fa-trash'></span></a>
					</td>

				</tr>
				@endforeach
				@endif
			</table>
		</div>
		<?php
		// print_r($Reward);
		?>
		<!-- /.card-body -->
	</div>
	<!-- /.card -->
	<!-- /.card -->
	<!-- /.content -->
</div>
<!-- /.content-wrapper -->
@endsection

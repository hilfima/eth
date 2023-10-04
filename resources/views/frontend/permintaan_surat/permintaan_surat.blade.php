@extends('layouts.app_fe')



@section('content')
	<div class="row">
	<div class="col-xl-3 col-lg-4 col-md-12 theiaStickySidebar">
		<?= view('layouts.app_side'); ?>
	</div>
	<div class="col-xl-9 col-lg-8 col-md-12">

<div class="content-wrapper">
@include('flash-message')
<!-- Content Header (Page header) -->

<div class="card shadow-sm ctm-border-radius">
	<div class="card-body align-center">
		<h4 class="card-title float-left mb-0 mt-2">Permintaan Surat</h4>
		<ul class="nav nav-tabs float-right border-0 tab-list-emp">

			<li class="nav-item pl-3">
			<a href="{!! route('fe.tambah_permintaan_surat') !!}" class="btn btn-theme button-1 text-white ctm-border-radius p-2 add-person ctm-btn-padding">Tambah Permintaan Surat</a>
			</li>
		</ul>

	</div>
</div>
<div class="card">

	<!-- /.card-header -->
	<div class="card-body">
		<table id="example1" class="table table-bordered table-striped">
			<thead>
				<tr>
					<th>No</th>
					<th>Jenis Surat</th>
					<th>Status</th>

					<th>Action</th>
				</tr>
			</thead>
			<tbody>
			<?php $no=0 ?>
			@if(!empty($permintaan_surat))
			<?php
			foreach ($permintaan_surat as $data) { ?>
			<?php $no++ ?>
			<tr>
				<td><?=$no;?></td>
				<td><?=$data->jenis_surat;?></td>
				<td><?php
					if ($data->status==0) {
					echo 'Pending';
					}else if ($data->status==1) {
					echo 'Selesai';
					}else if ($data->status==2) {
					echo 'Ditolak';
					} ; ?></td>
				<td>
					<a href="{!! route('fe.edit_permintaan_surat',$data->t_permintaan_surat_id) !!}" target="_blank" title="Edit"><span class="fa fa-edit"></span></a>
					<a href="{!! route('fe.hapus_permintaan_surat',$data->t_permintaan_surat_id) !!}" target="_blank" title="Hapus"><span class="fa fa-trash"></span></a>


				</td>

			</tr>
			<?php
		} ?>
			@endif
		</table>
	</div>


</div>
@endsection
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
		<h4 class="card-title float-left mb-0 mt-2">Pengajuan Surat Teguran dan Peringatan</h4>
		<ul class="nav nav-tabs float-right border-0 tab-list-emp">

			<li class="nav-item pl-3">
			<a href="{!! route('fe.tambah_pengajuan_sp_st') !!}" class="btn btn-theme button-1 text-white ctm-border-radius p-2 add-person ctm-btn-padding">Tambah Pengajuan Surat Teguran dan Peringatan</a>
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
					<th>Nama Karyawan Yg Dilaporkan</th>
					<th>Jenis Sanksi</th>
					<th>Alasan</th>
					<th>Saran Tindakan</th>
					<th>Status</th>

					<th>Action</th>
				</tr>
			</thead>
			<tbody>
			<?php $no=0 ?>
			@if(!empty($pengajuan_sp_st))
			<?php
			foreach ($pengajuan_sp_st as $data) { ?>
			<?php $no++ ?>
			<tr>
				<td><?=$no;?></td>
				<td><?=$data->nama; ?></td>
				<td><?=$data->nama_sanksi; ?></td>
				<td><?=$data->alasan_sanksi; ?></td>
				<td><?=$data->tindakan; ?></td>
				<td><?php 
				
				if($data->status==1){
					echo 'Menunggu Approval Direksi';
				}else
				if($data->status==2){
					echo 'Menunggu Proses HC';
				}else
				if($data->status==3){
					echo 'Sanksi di proses HC';
				}else
				if($data->status==4){
					echo 'Sanksi ditolak';
				}else
				if($data->status==5){
					echo 'Sanksi Selesai & Disetujui';
				}
				 ?></td>
				<td>
					<?php if(($data->status==1 and $data->status_appr_direksi==3) ){?>
					<a href="{!! route('fe.edit_pengajuan_sp_st',$data->p_karyawan_sanksi_id) !!}" target="_blank" title="Edit"><span class="fa fa-edit"></span></a>
					<?php }?>
					
			</td>

			</tr>
			<?php
		} ?>
			@endif
		</table>
	</div>


</div>
@endsection
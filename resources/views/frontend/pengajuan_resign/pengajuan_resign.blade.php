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
		<h4 class="card-title float-left mb-0 mt-2">Pengajuan Resign</h4>
		<ul class="nav nav-tabs float-right border-0 tab-list-emp">

			<li class="nav-item pl-3">
			<a href="{!! route('fe.tambah_pengajuan_resign') !!}" class="btn btn-theme button-1 text-white ctm-border-radius p-2 add-person ctm-btn-padding">Tambah Pengajuan Resign</a>
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
				<?php if($type=='approval'){?>
					<th>Nama Karyawan</th>
				<?php }?>
					<th>Tanggal Terakhir Kerja</th>
					<th>Alasan Mengundurkan Diri</th>
					<th>Status</th>
					<th>Keterangan</th>
					<th>Action</th>
				</tr>
			</thead>
			<tbody>
			<?php $no=0 ?>
			@if(!empty($pengajuan_resign))
			<?php
			foreach ($pengajuan_resign as $data) { ?>
			<?php $no++ ?>
			<tr>
				<td><?=$no;?></td>
				<?php if($type=='approval'){?>
				<td><?=$data->nama; ?></td>
				<?php }?>
				<td><?=$data->tanggal_terakhir_kerja; ?></td>
				<td><?=$data->alasan_mengundurkan_diri; ?></td>
				<td><?php 
				
				if($data->status==1){
					echo 'Menunggu Approval Atasan';
				}elseif($data->status==11){
					echo 'Ditolak Atasan';
				}else
				if($data->status==2){
					echo 'Menunggu Atasan Direksi';
				}else
				if($data->status==22){
					echo 'Ditolak Direksi';
				}else
				if($data->status==3){
					echo 'Di proses HC';
				}else
				if($data->status==33){
					echo 'Ditolak HC';
				}else
				if($data->status==4){
					echo 'Pengajuan resign disetujui.';
				}
				 ?></td>
				 <td>
				 	<?php
				 		if($data->keterangan_atasan){
				 			echo "Atasan: ".$data->keterangan_atasan.'<br>';	
				 		}else if($data->keterangan_direksi){
				 			echo "Direksi: ".$data->keterangan_direksi.'<br>';	
				 		}else if($data->keterangan_hc){
				 			echo "HC: ".$data->keterangan_hc.'<br>';	
				 		}
				 	?>
				 </td>
				<td>
					<?php if(($data->status==1 and $data->status_appr_atasan==3) and $data->appr_atasan!=$idkaryawan){?>
					<a href="{!! route('fe.edit_pengajuan_resign',[$data->t_resign_id,'karyawan']) !!}" target="_blank" title="Edit"><span class="fa fa-edit"></span></a>
					<?php }else if(($data->status==1 and $data->status_appr_atasan==3) and $data->appr_atasan==$idkaryawan){?>
					<a href="{!! route('fe.edit_pengajuan_resign',[$data->t_resign_id,'atasan']) !!}" target="_blank" title="Approve Atasan"><span class="fa fa-edit"></span></a>
					<?php }else if(($data->status==2 and $data->status_appr_direksi==3) and $data->appr_direksi==$idkaryawan){?>
					<a href="{!! route('fe.edit_pengajuan_resign',[$data->t_resign_id,'direksi']) !!}" target="_blank" title="Approve Direksi"><span class="fa fa-edit"></span></a>
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
@extends('layouts.appsA')



@section('content')
	
<div class="content-wrapper">
@include('flash-message')
<!-- Content Header (Page header) -->

<div class="card shadow-sm ctm-border-radius">
	<div class="card-body align-center">
		<h4 class="card-title float-left mb-0 mt-2">Pengajuan Resign</h4>
		<ul class="nav nav-tabs float-right border-0 tab-list-emp">

			
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
					<th>Nama Karyawan</th>
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
				
				<td><?=$data->nama; ?></td>
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
					<?php if(($data->status==3 and $data->status_appr_direksi==3) ){?>
					<a href="{!! route('be.edit_pengajuan_resign',[$data->t_resign_id,'hc']) !!}" target="_blank" title="Edit"><span class="fa fa-edit"></span></a>
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
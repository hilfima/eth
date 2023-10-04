  @extends('layouts.app_fe')



@section('content')
					<!-- Page Title -->
					 <div class="content-wrapper">
					 <div class="row">
			<div class="col-xl-3 col-lg-4 col-md-12 theiaStickySidebar">
						<?= view('layouts.app_side');?>
					</div>
<div class="col-xl-9 col-lg-8 col-md-12">
    @include('flash-message')
   
    
	<!-- Main content -->
	<div class="card shadow-sm ctm-border-radius">
<div class="card-body align-center">
<h4 class="card-title float-left mb-0 mt-2">Mutasi, Demosi, dan Promosi</h4>
<ul class="nav nav-tabs float-right border-0 tab-list-emp">
	<li class="nav-item pl-3">
				<a href="{!! route('fe.tambah_mudepro') !!}" class="btn btn-theme button-1 text-white ctm-border-radius p-2 add-person ctm-btn-padding">Tambah</a>
			</li>
</ul>
</div>
</div>
<div class="card">
	<div class="card-body">
		<table id="example1" class="table table-bordered table-striped">
			<thead>
				<tr>
					<th>No.</th>
					<th>Jenis</th>
					<th>Jabatan</th>
					<th>Nama Karyawan</th>
					<th>Entitas</th>
					<th>Departement</th>
					<th>Pangkat</th>
					<th>Status Pengajuan</th>
					<th>Action</th>
				</tr>
			</thead>
			<tbody>
			<?php $no =0;


			?>
			@if(!empty($mudepro))
			@foreach($mudepro as $mudepro)
			<?php $no++; ?>
				<tr>
					<td><?=$no?></td>
					<td><?php
					if($mudepro->jenis==1)
						echo 'Mutasi';
					if($mudepro->jenis==2)
						echo 'Demosi';
					if($mudepro->jenis==3)
						echo 'Promosi';
					;?></td>
					<td><?=$mudepro->nmjabatan;?></td>
					<td><?=$mudepro->karyawan;?></td>
					<td><?=$mudepro->nmentitas;?></td>
					<td><?=$mudepro->nmdepartemen;?></td>
					<td><?=$mudepro->nmpangkat;?></td>
					<td><?php 
					
					if($mudepro->status==1){
						echo 'Menunggu Asesment HC';
					}else
					if($mudepro->status==11){
						echo 'Asesment HC Belum Final';
					}else
					if($mudepro->status==2){
						echo 'Menunggu Review Direksi';
					}else
					if($mudepro->status==22){
						echo 'Review Direksi Belum Selesai';
					}else
					if($mudepro->status==3){
						echo 'Finalisasi perubahan jabatan pihak HC';
					}else
					if($mudepro->status==4){
						echo 'Selesai';
					}
					?></td>
					

					<td></td>
				</tr>
			
			@endforeach
			@endif
		</table>
	</div>
</div>
@endsection
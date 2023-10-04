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
					<h1 class="m-0 text-dark"> Jam Shift</h1>
				</div><!-- /.col -->
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><a href="{!! route('be.jamshift') !!}">jamshift</a></li>
						<li class="breadcrumb-item active"> Jam Shift</li>
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
			<a href="{!! route('be.tambah_jamshift') !!}" title='Tambah' data-toggle='tooltip'><span class='fa fa-plus'></span>  Jam Shift </a>
		</div>
		<!-- /.card-header -->
		<div class="card-body">

			<table id="example1" class="table table-striped custom-table mb-0">
				<thead>
					<tr>
						<th>No.</th>

						<th>Nama</th>
						<th>Jam</th>
						<th>Keterangan</th>
						<th>Tanggal</th>
						<th>Entitas</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
				<?php $no=0 ?>
				@if(!empty($jamshift))
				@foreach($jamshift as $jamshift)
				<?php $no++ ?>
				<tr>
					<td>{!! $no !!}</td>
					<td>{!! $jamshift->nama_jam_shift !!}</td>
					<td>{!! $jamshift->jam_masuk !!} - {!! $jamshift->jam_keluar !!}</td>
					<td>{!! $jamshift->keterangan !!}</td>
					<td>{!! $jamshift->tgl_awal !!} s/d {!! $jamshift->tgl_akhir !!}</td>
					<td><?php
					if($jamshift->entitas){
					    $entitas = explode(",",$jamshift->entitas);
					    for($i=0;$i<count($entitas);$i++){
					        if($entitas[$i]!=-1){
					            echo $lokasi[$entitas[$i]];
					            echo '<br>';
					            
					        }
					    }
					}else{
					    echo 'Semua Entitas';
					}
					?></td>
					<td style="text-align: center">

						<a href="{!! route('be.edit_jamshift',$jamshift->m_jam_shift_id) !!}" title='Ubah' data-toggle='tooltip'><span class='fa fa-edit'></span></a>
						<a href="{!! route('be.hapus_jamshift',$jamshift->m_jam_shift_id) !!}" title='Hapus' data-toggle='tooltip'><span class='fa fa-trash'></span></a>
					</td>

				</tr>
				@endforeach
				@endif
			</table>
		</div>
		<?php
		// print_r($jamshift);
		?>
		<!-- /.card-body -->
	</div>
	<!-- /.card -->
	<!-- /.card -->
	<!-- /.content -->
</div>
<!-- /.content-wrapper -->
@endsection

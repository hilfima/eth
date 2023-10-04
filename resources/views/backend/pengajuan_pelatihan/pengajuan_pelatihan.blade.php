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
					<h1 class="m-0 text-dark"> Pelatihan</h1>
				</div><!-- /.col -->
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><a href="{!! route('be.agenda_perusahaan') !!}">Pelatihan</a></li>
						<li class="breadcrumb-item active"> Pelatihan</li>
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
			<a href="{!! route('be.tambah_agenda_perusahaan') !!}" title='Tambah' data-toggle='tooltip'><span class='fa fa-plus'></span>  Pelatihan </a>
		</div>
		<!-- /.card-header -->
		<div class="card-body">

			<table id="example1" class="table table-striped custom-table mb-0">
				<thead>
					<tr>
						<th>No.</th>

						<th>Nama</th>
						<th>Tanggal</th>
						<th>Waktu</th>
						<th>CP</th>
						<th>Lokasi</th>
						<th>Alasan Pengajuan</th>
						<th>Status</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
				<?php $no=0 ?>
				@if(!empty($pengajuan_pelatihan))
				@foreach($pengajuan_pelatihan as $pengajuan_pelatihan)
				<?php $no++ ?>
				<tr>
					<td>{!! $no !!}</td>
					<td>{!! $pengajuan_pelatihan->nama_agenda !!}
					@if(!empty($pengajuan_pelatihan->brosur))
					
					<a href="{!! asset('dist/img/file/'.$pengajuan_pelatihan->brosur) !!}" target="_blank" title="Download"><span class="fa fa-download"></span></a>
					@endif
					</td>
					<td>{!! $pengajuan_pelatihan->tgl_awal !!} s/d {!! $pengajuan_pelatihan->tgl_akhir !!}</td>
					<td>{!! $pengajuan_pelatihan->waktu_mulai !!} - {!! $pengajuan_pelatihan->waktu_selesai != '00:00:00' ?$pengajuan_pelatihan->waktu_selesai:'Selesai'; !!}</td>
					
					<td>{!! $pengajuan_pelatihan->cp !!}</td>
					<td>{!! $pengajuan_pelatihan->lokasi !!}</td>
					<td>{!! $pengajuan_pelatihan->status==2?'Pending':'Disetujui' !!}</td>

					<td style="text-align: center">

<a href="{!! route('be.edit_pengajuan_pelatihan',$pengajuan_pelatihan->t_agenda_perusahaan_id) !!}" title='Ubah' data-toggle='tooltip'><span class='fa fa-edit'></span></a>
<a href="{!! route('be.hapus_pengajuan_pelatihan',$pengajuan_pelatihan->t_agenda_perusahaan_id) !!}" title='Hapus' data-toggle='tooltip'><span class='fa fa-trash'></span></a>
					</td>

				</tr>
				@endforeach
				@endif
			</table>
		</div>
		<?php
		// print_r($agenda_perusahaan);
		?>
		<!-- /.card-body -->
	</div>
	<!-- /.card -->
	<!-- /.card -->
	<!-- /.content -->
</div>
<!-- /.content-wrapper -->
@endsection

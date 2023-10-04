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
					<h1 class="m-0 text-dark"> Agenda Perusahaan</h1>
				</div><!-- /.col -->
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><a href="{!! route('be.agenda_perusahaan') !!}">Agenda Perusahaan</a></li>
						<li class="breadcrumb-item active"> Agenda Perusahaan</li>
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
			<a href="{!! route('be.tambah_agenda_perusahaan') !!}" title='Tambah' data-toggle='tooltip'><span class='fa fa-plus'></span>  Agenda Perusahaan </a>
		</div>
		<!-- /.card-header -->
		<div class="card-body">

			<table id="example1" class="table table-striped custom-table mb-0">
				<thead>
					<tr>
						<th>No.</th>

						<th>Nama</th>
						<th>Kode</th>
						<th>Tanggal</th>
						<th>Waktu</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
				<?php $no=0 ?>
				@if(!empty($agenda_perusahaan))
				@foreach($agenda_perusahaan as $agenda_perusahaan)
				<?php $no++ ?>
				<tr>
					<td>{!! $no !!}</td>
					<td>{!! $agenda_perusahaan->nama_agenda !!}
					@if(!empty($agenda_perusahaan->brosur))
                                   <a href="{!! asset('dist/img/file/'.$agenda_perusahaan->brosur	) !!}" target="_blank" title="Download"><span class="fa fa-download"></span></a>
                                   @endif</td>
					<td>{!! $agenda_perusahaan->kode_acara !!}</td>
					<td>{!! $agenda_perusahaan->tgl_awal !!} s/d {!! $agenda_perusahaan->tgl_akhir !!}</td>
					<td>{!! $agenda_perusahaan->waktu_mulai !!} - {!! $agenda_perusahaan->waktu_selesai != '00:00:00' ?$agenda_perusahaan->waktu_selesai:'Selesai'; !!}</td>
					
					<td style="text-align: center">
						<a href="{!! route('be.qr_absen_acara',$agenda_perusahaan->t_agenda_perusahaan_id) !!}" title='QRCode Absen' data-toggle='tooltip'><span class='fa fa-qrcode'></span></a>
						<a href="{!! route('be.list_konfirmasi_jadwal_training',$agenda_perusahaan->t_agenda_perusahaan_id) !!}" title='Lihat Konfirmasi Kehadiran & List Karyawan' data-toggle='tooltip'><span class='fa fa-check-square'></span></a>
						<a href="{!! route('be.rekap_kehadiran',$agenda_perusahaan->t_agenda_perusahaan_id) !!}" title='Rekap Kehadiran' data-toggle='tooltip'><span class='fa fa-file'></span></a>
						<a href="{!! route('be.edit_agenda_perusahaan',$agenda_perusahaan->t_agenda_perusahaan_id) !!}" title='Ubah' data-toggle='tooltip'><span class='fa fa-edit'></span></a>
						<a href="{!! route('be.hapus_agenda_perusahaan',$agenda_perusahaan->t_agenda_perusahaan_id) !!}" title='Hapus' data-toggle='tooltip'><span class='fa fa-trash'></span></a>
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

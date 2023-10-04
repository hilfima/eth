@extends('layouts.app_fe')



@section('content')
<div class="content-wrapper">
@include('flash-message')
<!-- Content Header (Page header) -->

<div class="row">
	<div class="col-xl-3 col-lg-4 col-md-12 theiaStickySidebar">
		<?= view('layouts.app_side'); ?>
	</div>
	<div class="col-xl-9 col-lg-8 col-md-12">

<div class="card shadow-sm ctm-border-radius">
	<div class="card-body align-center">
		<h4 class="card-title float-left mb-0 mt-2">Jadwal Pelatihan</h4>
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
				<th>Nama</th>
				<th>Tanggal</th>
				<th>Waktu</th>
				<th>CP</th>
				<th>Lokasi</th>
				<th>Konfirmasi kehadiran</th>
					<th>Action</th>
				</tr>
			</thead>
			<tbody>
			<?php $no=0 ?>
			@if(!empty($agenda_perusahaan))
			<?php
			foreach ($agenda_perusahaan as $kotak) { ?>
			<?php $no++ ?>
			<tr>
				<td><?=$no?></td>
				<td>{!! $kotak->nama_agenda !!}</td>
				<td>{!! $kotak->tgl_awal !!} s/d {!! $kotak->tgl_akhir !!}</td>
				<td>{!! $kotak->waktu_mulai !!} - {!! $kotak->waktu_selesai != '00:00:00' ?$kotak->waktu_selesai:'Selesai'; !!}</td>
				<td>{!! $kotak->cp !!}</td>
				<td>{!! $kotak->lokasi !!}</td>
				<td>
				@if($kotak->konfirmasi_kehadiran==1)
							Hadir
						@elseif($kotak->konfirmasi_kehadiran==2)
							Tidak Hadir
						@else
							Belum Konfirmasi
						@endif
						</td>

				<td>
					<a href="{!! route('fe.baca_pengajuan_pelatihan',$kotak->t_agenda_perusahaan_id	) !!}" target="_blank" title="Konfirmasi" ><span class="fa fa-search"></span> </a>
					
					@if(!in_array($kotak->konfirmasi_kehadiran,array(1,2)))
<a href="{!! route('fe.absen_kehadiran_pelatihan_hadir',$kotak->t_agenda_perusahaan_karyawan_id) !!}" title='Hadir' data-toggle='tooltip'>
						<i class="fa fa-check" aria-hidden="true"></i>
					</a>
					<a href="{!! route('fe.absen_kehadiran_pelatihan_tdkhadir',$kotak->t_agenda_perusahaan_karyawan_id) !!}" title='Tidak Hadir' data-toggle='tooltip'>
						<i class="fa fa-times" aria-hidden="true"></i>
					</a>
					@endif
					


				</td>



				</td>
			</tr>
			<?php
		} ?>
			@endif
		</table>
	</div>


</div>
@endsection
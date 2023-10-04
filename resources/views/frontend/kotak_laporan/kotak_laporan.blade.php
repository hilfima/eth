@extends('layouts.app_fe')



@section('content')
<div class="content-wrapper">
@include('flash-message')
	<div class="row">
	<div class="col-xl-3 col-lg-4 col-md-12 theiaStickySidebar">
		<?= view('layouts.app_side'); ?>
	</div>
	<div class="col-xl-9 col-lg-8 col-md-12">

<!-- Content Header (Page header) -->

<div class="card shadow-sm ctm-border-radius">
	<div class="card-body align-center">
		<h4 class="card-title float-left mb-0 mt-2">Kotak Laporan</h4>
		<ul class="nav nav-tabs float-right border-0 tab-list-emp">

			<li class="nav-item pl-3">
			<a href="{!! route('fe.tambah_kotak_laporan') !!}" class="btn btn-theme button-1 text-white ctm-border-radius p-2 add-person ctm-btn-padding">Tambah Kotak Laporan</a>
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
					<th>Kotak Laporan ID</th>

					<th>Action</th>
				</tr>
			</thead>
			<tbody>
			<?php $no=0 ?>
			@if(!empty($Kotak_laporan))
			<?php
			foreach ($Kotak_laporan as $kotak) { ?>
			<?php $no++ ?>
			<tr>
				<td>Kotak Laporan <?=sprintf('%03d',$kotak->t_kotak_laporan_id) ?></td>

				<td>
					<a href="{!! route('fe.baca_kotak_laporan',$kotak->t_kotak_laporan_id	) !!}" target="_blank" title="Download"><span class="fa fa-search"></span></a>


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
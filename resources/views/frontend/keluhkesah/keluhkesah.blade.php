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
		<h4 class="card-title float-left mb-0 mt-2">Keluh Kesah</h4>
		<ul class="nav nav-tabs float-right border-0 tab-list-emp">

			<li class="nav-item pl-3">
			<a href="{!! route('fe.tambah_keluhkesah') !!}" class="btn btn-theme button-1 text-white ctm-border-radius p-2 add-person ctm-btn-padding">Tambah Keluh Kesah</a>
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
				<!--<th>ID</th>-->
				<th>Judul</th>
				
				<th>Action</th>
			</tr>
		</thead>
		<tbody>
		<?php $no=0 ?>
		@if(!empty($keluh_kesah))
		<?php
		foreach ($keluh_kesah as $keluh) { ?>
		<?php $no++ ?>
		<tr>
			<!--<td><?=sprintf('%03d',$keluh->t_keluh_kesah_id) ?></td>-->
			<td><?=($keluh->judul) ?></td>

			<td>
				<a href="{!! route('fe.baca_keluh_kesah',$keluh->t_keluh_kesah_id) !!}" target="_blank" title="Download"><span class="fa fa-search"></span></a>

				
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
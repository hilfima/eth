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
		<h4 class="card-title float-left mb-0 mt-2">List Survei</h4>
		<ul class="nav nav-tabs float-right border-0 tab-list-emp">

			<li class="nav-item pl-3">
			
			</li>
		</ul>

	</div>
</div>
<div class="row">
	<?php
	foreach ($survei as $survei) { ?>

	<div class="col-xl-4col-lg-4 col-md-4 d-flex">
		<div class="card ctm-border-radius shadow-sm flex-fill">
			<div class="card-header">
				<h4 class="card-title mb-0"><b><?=$survei->nama_survei ?></h4>
			</div>
			<div class="card-body">
				<p class="card-text"><?= $survei->keterangan; ?></p>
				
				<div class="m-2 p-4">
					
					<a href="<?= $survei->link; ?>" target="_blank" class="btn btn-theme button-1 ctm-border-radius text-white float-right text-white">Kunjungi</a>
				</div>
			</div>
		</div>
	</div>
	<?php
} ?>

</div>
@endsection
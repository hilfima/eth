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
		<h3 class="card-title  mb-0 mt-2 text-center"  style="font-weight: 800">Kebijakan dan Prosedur</h3>
		<ul class="nav nav-tabs float-right border-0 tab-list-emp">

			
		</ul>

	</div>
</div>
<div class="row">
	<?php foreach($sop as $sop){?>

	<div class="col-xl-6 col-lg-6 col-md-6 d-flex">
		<div class="card ctm-border-radius shadow-sm flex-fill">
			<div class="card-header">

				<h4 class="card-title mb-0"><b><?= $sop->judul_sop;?></h4>
			</div>
			<div class="card-body">
				<p class="card-text"></p>
				<div class="mt-2">
					<span class="text-bold" style="font-weight: bold;"><?= $sop->selesai==0?'Belum Baca': 'Selesai	Baca';?></b></span>
					<a href="{!!route('fe.baca_sop',$sop->sop_id)!!}" class="btn btn-theme button-1 ctm-border-radius text-white float-right text-white">Baca</a>
				</div>
			</div>
		</div>
	</div>
	<?php }?>

</div>
@endsection
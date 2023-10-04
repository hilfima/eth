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
		<h3 class="card-title text-center mb-0 mt-2 " style="font-weight: bold; font-family: Calibri">AGENDA PERUSAHAAN</h3>
		

	</div>
</div>
<?php
if (!count($agenda_perusahaan))
	echo '<div class="card p-2">Belum ada Data</div>';
?>
<div class="row">
	<div class="col-xl-12 col-lg-12 col-md-12 mb-3">

<div class="card shadow-sm ctm-border-radius" style="background: #dff8ff">
			<div class="card-body align-center">
				<h4 class="card-title float-left text-center mb-0 mt-2" >Agenda Yang Akan Datang</h4>


			</div>
		</div>
	</div>
	<?php
	$x=0;
	$i=0;
	$array = array('#16b1ff','#9155fd','#56ca00','#ff3e1d');
	foreach ($agenda_perusahaan as $data) { 
		if($data->tgl_awal<date('Y-m-d') and $x==0){
		echo '<div class="col-xl-12 col-lg-12 col-md-12 mb-3">
		<div class="card shadow-sm ctm-border-radius" style="background: #dff8ff">
			<div class="card-body align-center">
			<h4 class="card-title text-center float-left mb-0 mt-2" >Agenda Terlaksana</h4>


			</div>
		</div>
		</div>
		';
			$x++;
		}
		?>
	
	<div class="col-xl-4 col-lg-4 col-md-4 d-flex">
		<div class="card ctm-border-radius shadow-sm flex-fill" style="background: #b5d9db;">
			<div class="card-header">

<h4 class="card-title mb-0">
	<p class="card-text text-center  "style="color:#474747;font-weight: 700;">
	<?php 
	if(($data->tgl_awal)==($data->tgl_akhir)){
		echo $help->tgl_indo($data->tgl_akhir);
	}else
	if(date('Y-m',strtotime($data->tgl_awal)==date('Y-m',strtotime($data->tgl_akhir)))){
	echo date('d',strtotime($data->tgl_awal)).' - '.date('d',strtotime($data->tgl_akhir)).' '.$help->bulan(date('m',strtotime($data->tgl_akhir))).' '.date('Y',strtotime($data->tgl_akhir));
	}else {
		echo $help->tgl_indo($data->tgl_awal); ?> <?= $data->tgl_awal!= $data->tgl_akhir?' s/d '.$help->tgl_indo($data->tgl_akhir):''; 
	}
	?>
	<!--<?= $help->tgl_indo($data->tgl_awal); ?> <?= $data->tgl_awal!= $data->tgl_akhir?' s/d '.$help->tgl_indo($data->tgl_akhir):''; ?> --><br><span style="font-size: 13px">(<?= date('H:i',strtotime($data->waktu_mulai)); ?> - <?= $data->waktu_selesai!='00:00:00'?date('H:i',strtotime($data->waktu_selesai)):'Selesai'; ?>) </span></p></h4>
			</div>
			<div class="card-body  text-center text-" style="color:#474747">
				<b style="font-weight: bold; font-family: Calibri"><?= $data->nama_agenda;?></b>
			
				<p class="card-text"><?= $data->deskripsi; ?> </p>
				
				<!--<div class="mt-2">
					
					<a href="{!!route('fe.chat_room',$data->t_agenda_perusahaan_id)!!}" class="btn btn-theme button-1 ctm-border-radius text-white float-right text-white">Lihat Detail</a>
				</div>-->
			</div>
		</div>
	</div>
	<?php
	$i++;
	if($i>=count($array)){
	$i=0;
	}
} ?>

</div>
@endsection
@extends('layouts.app_fe')



@section('content')
<div class="content-wrapper">
@include('flash-message')
<!-- Content Header (Page header) -->
<div class="row">
<div class="col-xl-3 col-lg-4 col-md-12 theiaStickySidebar">
	<?= view('layouts.app_side');?>
</div>
<div class="col-xl-9 col-lg-8 col-md-12">
<div class="card shadow-sm ctm-border-radius">
	<div class="card-body align-center">
		<h4 class="card-title float-left mb-0 mt-2">Pesan Klarifikasi</h4>
		<ul class="nav nav-tabs float-right border-0 tab-list-emp">

			<li class="nav-item pl-3">
				<a href="{!! route('fe.tambah_chat') !!}" class="btn btn-theme button-1 text-white ctm-border-radius p-2 add-person ctm-btn-padding">Tambah Pesan Klarifikasi</a>
			</li>
		</ul>

	</div>
</div>
<div class="row">
	<?php foreach($chat_list as $chat_room){?>

	<div class="col-xl-6 col-lg-6 col-md-6 d-flex">
		<div class="card ctm-border-radius shadow-sm flex-fill">
			<div class="card-header">

				<h4 class="card-title mb-0"><b><?php  if($chat_room->tujuan==1) echo 'Absensi - Finger tidak terbaca'; else 
						if($chat_room->tujuan==2) echo 'Absensi - Izin'; else 
						if($chat_room->tujuan==3) echo 'Absensi - Sakit'; else 
						if($chat_room->tujuan==4) echo 'Absensi - Mesin absen Error'; else 
						if($chat_room->tujuan==5) echo 'Absensi - Lainnya'; else 
						if($chat_room->tujuan==6) echo 'Gaji -  Kelebihan bayar'; else 
						if($chat_room->tujuan==7) echo 'Gaji -  Kekurangan bayar'; else 
						if($chat_room->tujuan==8) echo 'Gaji -  Lainnya'; else 
						if($chat_room->tujuan==9) echo 'Lainnya';  ?></b> - <?= $chat_room->topik;?></h4>
			</div>
			<div class="card-body">
				<p class="card-text"><?= $chat_room->deskripsi;?></p>
				<div class="mt-2">
					<span class="text-bold" style="font-weight: bold;"><?php
					if ($chat_room->selesai==0) {
					echo 'Proses HR';
					} else if ($chat_room->selesai==1) {
					echo 'Proses Atasan';
					} else if ($chat_room->selesai==2) {
					echo 'Entry Perubahan Absen';
					} else if ($chat_room->selesai==3) {
					echo 'Selesai';
					}; ?></b></span>
					<a href="{!!route('fe.chat_room',$chat_room->chat_room_id)!!}" class="btn btn-theme button-1 ctm-border-radius text-white float-right text-white">Chat</a>
				</div>
			</div>
		</div>
	</div>
	<?php }?>

</div>
@endsection
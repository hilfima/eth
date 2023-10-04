@extends('layouts.app_fe')

@section('content')
	<div class="row">
	<div class="col-xl-3 col-lg-4 col-md-12 theiaStickySidebar">
		<?= view('layouts.app_side'); ?>
	</div>
	<div class="col-xl-9 col-lg-8 col-md-12">

<style>
	.card-box {
		background-color: #fff;
		border: 1px solid #ededed;
		border-radius: 4px;
		box-shadow: 0 1px 1px 0 rgba(0, 0, 0, 0.2);
		margin-bottom: 30px;
		padding: 20px;
		position: relative;
	}

	.profile-details {
		text-align: center;
	}

	.personal-info li .title {
		color: #4f4f4f;
		float: left;
		font-weight: 500;
		margin-right: 30px;
		width: 45%;
	}

	.personal-info li .text {
		color: #8e8e8e;
		display: block;
		overflow: hidden;
	}

	.personal-info li {
		margin-bottom: 10px;
	}

	.personal-info {
		list-style: none;
		margin-bottom: 0;
		padding: 0;
	}

	.profile-view {
		position: relative;
	}

	.profile-view .profile-img-wrap {
		height: 120px;
		width: 120px;
	}

	.profile-view .profile-img {
		width: 120px;
		height: 120px;
	}

	.profile-view .profile-img .avatar {
		font-size: 24px;
		height: 120px;
		line-height: 120px;
		margin: 0;
		width: 120px;
	}

	.profile-view .profile-basic {
		margin-left: 140px;
		padding-right: 50px;
	}

	.profile-view .pro-edit {
		position: absolute;
		right: 0;
		top: 0;
	}

	.edit-icon {
		background-color: #eee;
		border: 1px solid #e3e3e3;
		border-radius: 24px;
		color: #bbb;
		float: right;
		font-size: 12px;
		line-height: 24px;
		min-height: 26px;
		text-align: center;
		width: 26px;
	}

	.edit-icon:hover {
		background-color: #667eea;
		border-color: #667eea;
		color: #fff;
	}

	.delete-icon {
		color: #e30b0b;
		float: right;
		font-size: 18px;
	}

	.delete-icon:hover {
		color: #e30b0b;
	}

	.staff-msg {
		margin-top: 30px;
	}

	.experience-box {
		position: relative;
	}

	.experience-list {
		list-style: none;
		margin: 0;
		padding: 0;
		position: relative;
	}

	.experience-list::before {
		background: #ddd;
		bottom: 0;
		content: "";
		left: 8px;
		position: absolute;
		top: 8px;
		width: 2px;
	}

	.experience-list>li {
		position: relative;
	}

	.experience-list>li:last-child .experience-content {
		margin-bottom: 0;
	}

	.experience-user .avatar {
		height: 32px;
		line-height: 32px;
		margin: 0;
		width: 32px;
	}

	.experience-list>li .experience-user {
		background: #fff;
		height: 10px;
		left: 4px;
		margin: 0;
		padding: 0;
		position: absolute;
		top: 4px;
		width: 10px;
	}

	.experience-list>li .experience-content {
		background-color: #fff;
		margin: 0 0 20px 40px;
		padding: 0;
		position: relative;
	}

	.experience-list>li .experience-content .timeline-content {
		color: #9e9e9e;
	}

	.experience-list>li .experience-content .timeline-content a.name {
		color: #616161;
		font-weight: bold;
	}

	.experience-list>li .time {
		color: #bdbdbd;
		display: block;
		font-size: 12px;
		line-height: 1.35;
	}

	.before-circle {
		background-color: #ddd;
		border-radius: 50%;
		height: 10px;
		width: 10px;
	}

	.skills>span {
		border: 1px solid #ccc;
		border-radius: 500px;
		display: inline-block;
		margin-bottom: 10px;
		padding: 6px 12px;
		text-align: center;
	}

	.profile-info-left {
		border-right: 2px dashed #ccc;
	}

	.card-box.user-box {
		min-height: 240px;
	}

	.bootstrap-tagsinput {
		background-color: #fff;
		border: 1px solid #e3e3e3;
		border-radius: 0;
		box-shadow: unset;
		display: block;
		min-height: 44px;
		padding: 6px 6px 0;
	}

	.bootstrap-tagsinput .badge {
		font-size: 14px;
		font-weight: normal;
		margin-bottom: 6px;
		padding: 10px 15px;
	}

	.add-more a {
		color: #667eea;
	}

	.add-more a:hover {
		color: #4b68e7;
	}

	.avatar-box {
		float: left;
	}

	.pro-overview .personal-info li .title {
		width: 50%;
	}

	.profile-box {
	}

	.profile-view {
		position: relative;
	}

	.profile-view .profile-img-wrap {
		height: 120px;
		width: 120px;
	}

	.profile-view .profile-img {
		width: 120px;
		height: 120px;
	}

	.profile-view .profile-img .avatar {
		font-size: 24px;
		height: 120px;
		line-height: 120px;
		margin: 0;
		width: 120px;
	}

	.profile-view .profile-basic {
		margin-left: 40px;
		padding-right: 50px;
		width: 100%;
	}

	.profile-view .pro-edit {
		position: absolute;
		right: 0;
		top: 0;
	}

	.profile-user-img {
		border-radius: 50%;
	}

	.nav-tabs.nav-justified.nav-tabs-top {
		border-bottom: 1px solid #ddd;
	}

	.nav-tabs.nav-justified.nav-tabs-top>li>a,
	.nav-tabs.nav-justified.nav-tabs-top>li>a:hover,
	.nav-tabs.nav-justified.nav-tabs-top>li>a:focus {
		border-width: 2px 0 0 0;
	}

	.nav-tabs.nav-tabs-top>li {
		margin-bottom: 0;
	}

	.nav-tabs.nav-tabs-top>li>a,
	.nav-tabs.nav-tabs-top>li>a:hover,
	.nav-tabs.nav-tabs-top>li>a:focus {
		border-width: 2px 0 0 0;
	}

	.nav-tabs.nav-tabs-top>li.open>a,
	.nav-tabs.nav-tabs-top>li>a:hover,
	.nav-tabs.nav-tabs-top>li>a:focus {
		border-top-color: #ddd;
	}

	.nav-tabs.nav-tabs-top>li+li>a {
		margin-left: 1px;
	}

	.nav-tabs.nav-tabs-top>li>a.active,
	.nav-tabs.nav-tabs-top>li>a.active:hover,
	.nav-tabs.nav-tabs-top>li>a.active:focus {
		border-top-color: #667eea;
	}

	.nav-tabs.nav-tabs-bottom>li {
		margin-bottom: -1px;
	}

	.nav-tabs.nav-tabs-bottom>li>a.active,
	.nav-tabs.nav-tabs-bottom>li>a.active:hover,
	.nav-tabs.nav-tabs-bottom>li>a.active:focus {
		border-bottom-width: 2px;
		border-color: transparent;
		border-bottom-color: #667eea;
		background-color: transparent;
		transition: none 0s ease 0s;
		-moz-transition: none 0s ease 0s;
		-o-transition: none 0s ease 0s;
		-ms-transition: none 0s ease 0s;
		-webkit-transition: none 0s ease 0s;
	}

	.nav-tabs.nav-tabs-solid {
		background-color: #fafafa;
		border: 0;
	}

	.nav-tabs.nav-tabs-solid>li {
		margin-bottom: 0;
	}

	.nav-tabs.nav-tabs-solid>li>a {
		border-color: transparent;
	}

	.nav-tabs.nav-tabs-solid>li>a:hover,
	.nav-tabs.nav-tabs-solid>li>a:focus {
		background-color: #f5f5f5;
	}

	.nav-tabs.nav-tabs-solid>.open:not(.active)>a {
		background-color: #f5f5f5;
		border-color: transparent;
	}

	.nav-tabs-justified.nav-tabs-top {
		border-bottom: 1px solid #ddd;
	}

	.nav-tabs-justified.nav-tabs-top>li>a,
	.nav-tabs-justified.nav-tabs-top>li>a:hover,
	.nav-tabs-justified.nav-tabs-top>li>a:focus {
		border-width: 2px 0 0 0;
	}

	.nav {
		display: -ms-flexbox;
		display: flex;
		-ms-flex-wrap: wrap;
		flex-wrap: wrap;
		padding-left: 0;
		margin-bottom: 0;
		list-style: none
	}

	.nav-link {
		display: block;
		padding: .5rem 1rem
	}

	.nav-link:focus,
	.nav-link:hover {
		text-decoration: none
	}

	.nav-link.disabled {
		color: #6c757d;
		pointer-events: none;
		cursor: default
	}

	.nav-tabs {
		border-bottom: 1px solid #dee2e6
	}

	.nav-tabs .nav-item {
		margin-bottom: -1px
	}

	.nav-tabs .nav-link {
		border: 1px solid transparent;
		border-top-left-radius: 0;
		border-top-right-radius: 0;
	}

	.nav-tabs .nav-link:focus,
	.nav-tabs .nav-link:hover {
		border-color: #e9ecef #e9ecef #dee2e6
	}

	.nav-tabs .nav-link:focus,
	.nav-tabs .nav-link:hover {
		color: #555 !important;
		border-bottom-left-radius: 0;
		border-bottom-right-radius: 0;
	}

	.nav-tabs .nav-link.disabled {
		color: #6c757d;
		background-color: transparent;
		border-color: transparent
	}

	.nav-tabs .nav-item.show .nav-link,
	.nav-tabs .nav-link.active {
		color: #495057;
		background-color: #fff;
		border-color: #dee2e6 #dee2e6 #fff
	}

	.nav-tabs .dropdown-menu {
		margin-top: -1px;
		border-top-left-radius: 0;
		border-top-right-radius: 0
	}

	.nav-pills .nav-link {
		border-radius: .25rem
	}

	.nav-pills .nav-link.active,
	.nav-pills .show>.nav-link {
		color: #fff;
		background-color: #007bff
	}

	.nav-fill .nav-item {
		-ms-flex: 1 1 auto;
		flex: 1 1 auto;
		text-align: center
	}

	.nav-justified .nav-item {
		-ms-flex-preferred-size: 0;
		flex-basis: 0;
		-ms-flex-positive: 1;
		flex-grow: 1;
		text-align: center
	}

	.tab-content>.tab-pane {
		display: none
	}

	.tab-content>.active {
		display: block
	}.profile-pic {
	 color: transparent;
	 transition: all 0.3s ease;
	 display: flex;
	 justify-content: center;
	 align-items: center;
	 position: relative;
	 transition: all 0.3s ease;
}
 .profile-pic input {
	 display: none;
}
 .profile-pic img {
	 position: absolute;
	 object-fit: cover;
	 width: 130px;
	 height: 130px;
	 border-radius: 100%;
	 z-index: 0;
}
 .profile-pic .-label {
	 cursor: pointer;
	 height: 130px;
	 width: 130px;
}
 .profile-pic:hover .-label {
	 display: flex;
	 justify-content: center;
	 align-items: center;
	 background-color: rgba(0, 0, 0, .8);
	 z-index: 10000;
	 color: #fafafa;
	 transition: background-color 0.2s ease-in-out;
	 border-radius: 100px;
	 margin-bottom: 0;
}
 .profile-pic span {
	 display: inline-flex;
	 padding: 0.2em;
	 height: 2em;
}
</style>
<script>
    var loadFile = function (event) {
  var image = document.getElementById("output");
  image.src = URL.createObjectURL(event.target.files[0]);
  $('#profileButton').show();
};

</script>
<div class="content">
	<?php
	$svgicon = '
	<svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" style="enable-background:new 0 0 194.436 194.436;fill: white;width: 12px;"	 viewBox="0 0 194.436 194.436" style="enable-background:new 0 0 194.436 194.436;" xml:space="preserve">
		<g> <path d="M192.238,34.545L159.894,2.197C158.487,0.79,156.579,0,154.59,0c-1.989,0-3.897,0.79-5.303,2.196l-32.35,32.35 		c-0.004,0.004-0.008,0.01-0.013,0.014L54.876,96.608c-1.351,1.352-2.135,3.166-2.193,5.076l-1.015,33.361 		c-0.063,2.067,0.731,4.068,2.193,5.531c1.409,1.408,3.317,2.196,5.303,2.196c0.076,0,0.153-0.001,0.229-0.004l33.36-1.018 		c1.909-0.058,3.724-0.842,5.075-2.192l94.41-94.408C195.167,42.223,195.167,37.474,192.238,34.545z M154.587,61.587L132.847,39.85 		l21.743-21.743l21.738,21.741L154.587,61.587z M89.324,126.85l-22.421,0.685l0.682-22.422l54.655-54.656l21.741,21.738 		L89.324,126.85z"/> 	<path d="M132.189,117.092c-4.142,0-7.5,3.357-7.5,7.5v54.844H15.001V69.748h54.844c4.142,0,7.5-3.357,7.5-7.5s-3.358-7.5-7.5-7.5 		H7.501c-4.142,0-7.5,3.357-7.5,7.5v124.687c0,4.143,3.358,7.5,7.5,7.5h124.687c4.142,0,7.5-3.357,7.5-7.5v-62.344 		C139.689,120.449,136.331,117.092,132.189,117.092z"/> </g>
		<g> </g>
		<g> </g>
		<g> </g>
		<g> </g>
		<g> </g>
		<g> </g>
		<g> </g>
		<g> </g>
		<g> </g>
		<g> </g>
		<g> </g>
		<g> </g>
		<g> </g>
		<g> </g>
		<g> </g> </svg>';
	?>

	<!-- Page Content -->
	<div class="content container-fluid">

		<!-- Page Title -->
		@include('flash-message')
		<!-- Content Header (Page header) -->
		<div class="card shadow-sm ctm-border-radius">
			<div class="card-body align-center">
				<h4 class="card-title float-left mb-0 mt-2">Profile</h4>

			</div>
		</div>
		<!-- /Page Title -->

		<div class="card-box mb-0">
			<div class="row">
				<div class="col-md-12">
					<div class="profile-view d-flex">
						<div class="profile-img-wrap">
							<div class="profile-img" style="
margin-top: 32px;">
							    <form action="{!!route('fe.update_profile',[$karyawan[0]->p_karyawan_id,'type=profile'])!!}" method="post" enctype="multipart/form-data">
					{{csrf_field()}}
								<a href="#">
								    
									

<div class="profile-pic">
  <label class="-label" for="file">
    <span class="glyphicon glyphicon-camera"></span>
    <span>Change Image</span>
  </label>
  
  <input id="file" type="file" onchange="loadFile(event)" name="image" />
    @if($karyawan[0]->foto!=null)
        <img src="{!! asset('dist/img/profile/'.$karyawan[0]->foto) !!}" id="output" class="profile-user-img img-fluid img-circle" alt="File Belum diupload" />
  	@else
		<img src="{!! asset('dist/img/profile/user.png') !!}" class="profile-user-img img-fluid img-circle" alt="File Belum diupload">
	@endif
</div>
<div id="profileButton" style="display:none">
    
    <button type="submit" class="btn btn-primary btn-xs"> Simpan</button>
</div>
									<!--<img src="{!! asset('dist/img/profile/'.$karyawan[0]->foto) !!}" alt="User Avatar" class="profile-user-img img-fluid img-thumbnail">-->
								

								</a>
	</form>
							</div>
						</div>
						<div class="profile-basic">
							<div class="row">
								<div class="col-md-7">
									<div class="profile-info-left2">
										<div class="staff-id">{!! $karyawan[0]->nik !!}</div>
										<h3 class="user-name m-t-0" style="margin-bottom: 10px;">{!! $karyawan[0]->nama_lengkap !!}</h3>
										<h6 class="text-bold  ">{!! $karyawan[0]->nmjabatan !!} </h6>
										<div class="text-muted">{!! $karyawan[0]->nmlokasi !!}<br>Departement {!! $karyawan[0]->nmdept !!}<br>{!! $karyawan[0]->nmdivisi !!}</div>
										<div class="small doj text-muted">Tanggal Bergabung : {!! $karyawan[0]->tgl_bergabung !!}</div>

									</div>
								</div>
								<div class="col-5 text-right">
									<?php

?>
								</div>
							</div>
						</div>
						<!--<div class="pro-edit">
							<a data-target="#profile_info" data-toggle="modal" class="edit-icon" style="background-color: #007bff;border-color: #007bff;border-bottom: 5px solid #007bff;" href="#">
								<i><?= $svgicon; ?></i></a></div>-->
					</div>
				</div>
			</div>
		</div>

		<div class="card-box tab-box p-0">
			<div class="row user-tabs">
				<div class="col-lg-12 col-md-12 col-sm-12 line-tabs">
					<ul class="nav nav-tabs nav-tabs-bottom">
						<li class="nav-item"><a href="#emp_profile" data-toggle="tab" class="nav-link  <?=((!$request->active) or (in_array($request->active,array('utama','pernikahan'))))?'active':''?>">Data Pribadi</a></li>
						<li class="nav-item"><a href="#bank_statutory" data-toggle="tab" class="nav-link <?=$request->active=='kartu'?'active show':''?>">Kartu Identitas</a></li>
						<li class="nav-item"><a href="#keluarga_projects" data-toggle="tab" class="nav-link <?=$request->active=='keluarga'?'active show':''?>">Data Keluarga</a></li>
						<li class="nav-item"><a href="#emp_projects" data-toggle="tab" class="nav-link <?=$request->active=='riwayatpekerjaan'?'active show':''?>">Data Pekerjaan</a></li>
						<li class="nav-item"><a href="#pendidikan" data-toggle="tab" class="nav-link <?=in_array($request->active,array('listpendidikan','kursus'))?'active show':''?>">Informasi Pendidikan</a></li>
						<li class="nav-item"><a href="#data_reward_sanksi" data-toggle="tab" class="nav-link <?=$request->active=='award'?'active show':''?>">Data Reward Dan Sanksi</a></li>
						<li class="nav-item"><a href="#data_lainnya" data-toggle="tab" class="nav-link <?=$request->active=='pakaian_keluarga'?'active show':''?>">Data Lainnya</a></li>
						<li class="nav-item"><a href="#password_statutory" data-toggle="tab" class="nav-link <?=$request->active=='password'?'active show':''?>">Ubah Password</a></li>
						<li class="nav-item"><a href="#profile_statutory" data-toggle="tab" class="nav-link <?=$request->active=='profile'?'active show':''?>">Ubah Foto Profil</a></li>
					</ul>
				</div>
			</div>
		</div>

		<div class="tab-content">


			<!-- Profile Info Tab -->
			<div id="emp_profile" class="tab-pane fade <?=((!$request->active) or (in_array($request->active,array('utama','pernikahan'))))?'active show':''?>">
				<div class="row">
					<div class="col-md-6">
						<div class="card-box profile-box">
							<h3 class="card-title">Data Utama
								<a href="#" class="edit-icon" style="background-color: #007bff;border-color: #007bff;border-bottom: 5px solid #007bff;" data-toggle="modal" data-target="#personal_info_modal">
									<i><?= $svgicon; ?></i></a></h3>
							<ul class="personal-info">
								<li>
								<div class="title">NIK</div>
								<div class="text">: {!! $karyawan[0]->nik !!}</div>
								</li>
								<li>
								<li>
								<div class="title">Nama Lengkap *</div>
								<div class="text">: {!! $karyawan[0]->nama_lengkap !!}</div>
								</li>
								<li>
								<div class="title">Nama Panggilan</div>
								<div class="text">: {!! $karyawan[0]->nama_panggilan !!}</div>
								</li>
								<li>
								<div class="title">Agama</div>
								<div class="text">: {!! $karyawan[0]->nmagama !!}</div>
								</li>
								</li>
								<li>
								<div class="title">Tempat Lahir</div>
								<div class="text">: {!! $karyawan[0]->tempat_lahir !!}</div>
								</li>
								<li>
								<div class="title">Tanggal Lahir</div>
								<div class="text">: {!! $help->tgl_indo($karyawan[0]->tgl_lahir) !!}</div>
								</li>
								<li>
								<div class="title">Jenis Kelamin</div>
								<div class="text">: <?php
								foreach ($jk as $jk2) {
								if ($jk2->m_jenis_kelamin_id == $karyawan[0]->m_jenis_kelamin_id) {
								echo $jk2->nama;
								}
								}
								?></div>
								</li>
								<li>
								<div class="title">Alamat Sesuai KTP</div>
								<div class="text">: {!! Strip_tags($karyawan[0]->alamat_ktp) !!}</div>
								</li>
								<li>
								<div class="title">Domisili</div>
								<div class="text">: {!! $karyawan[0]->domisili !!}</div>
								</li>
								<li>
								<div class="title">Email</div>
								<div class="text">: {!! $karyawan[0]->email_perusahaan !!}</div>
								</li>
								<li>
								<div class="title">No. HP</div>
								<div class="text">: {!! $karyawan[0]->no_hp !!}</div>
								</li>
								</li>
								<li>
								<div class="title">Nama Darurat</div>
								<div class="text">: {!! $karyawan[0]->nama_darurat !!}</div>
								</li>
								<li>
								<div class="title">No. Kontak Darurat</div>
								<div class="text">: {!! $karyawan[0]->kontak_darurat !!}</div>
								</li>
								
								<li>
								<div class="title">Hub Darurat</div>
								<div class="text">: {!! $karyawan[0]->hubungan_darurat !!}</div>
								</li>

							</ul>
						</div>
					</div>


					<div class="col-md-6">
						<div class="card-box profile-box text-center">
							
<?php  
echo '<img src="'.url('bower_components/qrcode/qrcode.php?s=qrh&d='.$karyawan[0]->nik).'"  width="250px"><br>';;?>
						</div>
						<div class="card-box profile-box">
							<h3 class="card-title">Data Pernikahan
								<a href="#" class="edit-icon" style="background-color: #007bff;border-color: #007bff;border-bottom: 5px solid #007bff;" data-toggle="modal" data-target="#datapernikahan">
									<i><?= $svgicon; ?></i></a></h3>

							<ul class="personal-info">
								<li>
								<div class="title">Status Pernikahan</div>
								<div class="text">: <?php
							    if ($karyawan[0]->m_status_id == 1) {
								echo 'Menikah ';
								}else {
								echo 'Belum Menikah ';
								}   ?>
								</div>
								</li>
								<li>
								<div class="title">Jumlah Anak</div>
								<div class="text">: <?php 
								if($karyawan[0]->jumlah_anak==-1){
								    echo 0;
								}else {
								    echo $karyawan[0]->jumlah_anak;
								}
								?></div>
								</li>

							</ul>


						</div>
					</div>
				</div>

			</div>

			<!-- /Profile Info Tab -->

			<!-- Projects Tab -->
			<div class="tab-pane fade <?=$request->active=='keluarga'?'active show':''?>" id="keluarga_projects">
				<div class="card-box profile-box">
					<h3 class="card-title text-center">Data Keluarga
						<a href="#" class="edit-icon" style="background-color: #007bff;border-color: #007bff;border-bottom: 5px solid #007bff;" data-toggle="modal" data-target="#family_info_modal">
							<i><?= $svgicon; ?></i></a></h3>

					<table id="example1" class="table table-bordered table-striped" style="width: 100%">
						<tr>
							<th>Hubungan</th>
							<th>Nama</th>
							<th>No Hp</th>
							<th>Tanggal Lahir</th>
							<th>Aksi</th>
						</tr>
						<?php
						if (!count($keluarga)) echo '';
						foreach ($keluarga as $keluarga2) { ?>
						<tr>
							<td><?= $keluarga2->hubungan; ?></td>
							<td class="text"> <?= ucwords($keluarga2->nama); ?></td>
							<td class="text"> <?= $keluarga2->no_hp; ?></td>
							<td class="text"><?= $help->tgl_indo($keluarga2->tgl_lahir); ?></td>
							<td class="text">
								<a href="<?= route('fe.hapus_keluarga', $keluarga2->p_karyawan_keluarga_id); ?>" title="" data-toggle="tooltip" data-original-title="Hapus"><span class="fa fa-trash"></span></a></td>
						</tr>
						<?php } ?>


					</table>
				</div>
			</div>
			<div class="tab-pane fade  <?=in_array($request->active,array('listpendidikan','kursus'))?'active show':''?>" id="pendidikan">
				<!--<div class="card-box profile-box">
				<h3 class="card-title">Data Pendidikan Terakhir<a href="#" class="edit-icon" style="background-color: #007bff;border-color: #007bff;border-bottom: 5px solid #007bff;" data-toggle="modal" data-target="#datapendidikan"><i><?= $svgicon; ?></i></a></h3>

				<ul class="personal-info">
				<li>
				<div class="title">Pendidikan</div>
				<div class="text">: <?= $karyawan[0]->pendidikan ?></div>
				</li>
				<li>
				<div class="title">Jurusan</div>
				<div class="text">: {!! $karyawan[0]->jurusan !!}</div>
				</li>
				<li>
				<div class="title">Nama Sekolah/PT </div>
				<div class="text">: {!! $karyawan[0]->nama_sekolah !!}</div>
				</li>
				</ul>

				</div>-->
				<div class="card-box profile-box">
					<h3 class="card-title text-center">Data Pendidikan
						<a href="#" class="edit-icon" style="background-color: #007bff;border-color: #007bff;border-bottom: 5px solid #007bff;" data-toggle="modal" data-target="#pendidikan_info_modal">
							<i><?= $svgicon; ?></i></a>
					</h3>

					<table id="example1" class="table table-bordered table-striped" style="width: 100%">
						<tr>
							<th>Jenjang</th>
							<th>Nama Sekolah</th>
							<th>Jurusan</th>
							<th>Tempat</th>
							<th>Kota</th>
							<th>Tahun Lulus</th>
							<th>Aksi</th>
						</tr>
						<?php
						if (!count($pendidikan)) echo '';
						foreach ($pendidikan as $pendidikan) { ?>
						<tr>
							<td class="text"> <?= $pendidikan->jenjang; ?></td>
							<td><?= $pendidikan->nama_sekolah; ?></td>
							<td class="text"> <?= $pendidikan->jurusan; ?></td>
							<td class="text"> <?= $pendidikan->alamat_sekolah; ?></td>
							<td class="text"> <?= $pendidikan->kota_sekolah; ?></td>
							<td class="text"><?= $pendidikan->tahun_lulus; ?></td>
							<td class="text">
								<a href="<?= route('fe.hapus_pendidikan', $pendidikan->p_karyawan_pendidikan_id); ?>" title="" data-toggle="tooltip" data-original-title="Hapus"><span class="fa fa-trash"></span></a></td>

						</tr>
						<?php } ?>


					</table>
				</div>
				<div class="card-box profile-box">
					<h3 class="card-title text-center">Data Kursus dan Pelatihan
						<a href="#" class="edit-icon" style="background-color: #007bff;border-color: #007bff;border-bottom: 5px solid #007bff;" data-toggle="modal" data-target="#kursus_info_modal_edit">
							<i><?= $svgicon; ?></i></a>
					</h3>

					<table id="example1" class="table table-bordered table-striped" style="width: 100%">
						<tr>
							<th>Nama Kursus</th>
							<th>Penyelenggaran</th>
							<th>Tanggal Pelatihan</th>
							<th>Sertifikat</th>
							<th>Aksi</th>
						</tr>
						<?php
						if (!count($kursus)) echo '';
						foreach ($kursus as $kursus) { ?>
						<tr>
							<td><?= $kursus->nama_kursus; ?></td>
							<td class="text"> <?= $kursus->penyelenggara; ?></td>
							<td class="text"> <?= $help->tgl_indo_short($kursus->tanggal_awal_pelatihan); ?> <?= $kursus->tanggal_awal_pelatihan != $kursus->tanggal_akhir_pelatihan ? 's/d ' . $help->tgl_indo_short($kursus->tanggal_akhir_pelatihan) : ''; ?></td>
							<td class="text"><?= $kursus->sertifikat ? 'Ya' : 'Tidak'; ?></td>
							<td class="text">
								<a href="<?= route('fe.hapus_kursus', $kursus->p_karyawan_kursus_id); ?>" title="" data-toggle="tooltip" data-original-title="Hapus"><span class="fa fa-trash"></span></a></td>
						</tr>
						<?php } ?>


					</table>
				</div>
			</div>

			<div class="tab-pane fade  <?=$request->active=='riwayatpekerjaan'?'active show':''?>" id="emp_projects">
				<div class="row">
					<div class="col-md-12">

						<div class="card-box profile-box">
							<h3 class="card-title">Data Jabatan</h3>

							<ul class="personal-info">
								<li>
								<div class="title">Entitas</div>
								<div class="text">: {!! $karyawan[0]->nmlokasi !!}</div>
								</li>
								<li>
								<div class="title">Departemen</div>
								<div class="text">: {!! $karyawan[0]->nmdivisi !!}</div>
								</li>
								<li>
								<div class="title">Unit Kerja</div>
								<div class="text">: {!! $karyawan[0]->nmdept !!}</div>
								</li>
								<li>
								<div class="title">Jabatan/Pangkat</div>
								<div class="text">: {!! $karyawan[0]->nmjabatan !!}</div>
								</li>
								<li>
								<div class="title">Level</div>
								<div class="text">: {!! $karyawan[0]->nmpangkat !!}</div>
								</li>
								<li>
								<div class="title">Grade</div>
								<div class="text">: {!! $karyawan[0]->grade !!}</div>
								</li>
								<li>
								<div class="title">Keterangan</div>
								<div class="text">: -{!! $karyawan[0]->keterangan !!}</div>
								</li>
							</ul>
						</div>
					</div>
					<div class="col-md-6">

						<div class="card-box profile-box">
							<h3 class="card-title">Data Kontak Kerja</h3>

							<ul class="personal-info">
								<li>
								<div class="title">Tanggal Masuk</div>
								<div class="text">: {!! date('d-m-Y',strtotime($karyawan[0]->tgl_awal)) !!}</div>
								</li>
								<li>
								<div class="title">Tanggal Keluar</div>
								<div class="text">: {!! date('d-m-Y',strtotime($karyawan[0]->tgl_akhir)) !!}</div>
								</li>
								<li>
								<div class="title">Status Pekerjaan</div>
								<div class="text">: {!! $karyawan[0]->nmstatus !!}</div>
								</li>
								<li>
								<div class="title">Periode Gajian</div>
								<div class="text">: {!! $karyawan[0]->periode !!}</div>
								</li>


							</ul>
						</div>
					</div>
					<div class="col-md-6">
						<div class="card-box profile-box">
							<h3 class="card-title">Data Lokasi Kerja </h3>

							<ul class="personal-info">
								<li>
								<div class="title">Kota</div>
								<div class="text">: {!! $karyawan[0]->kota !!}</div>
								</li>
								<li>
								<div class="title">Kantor</div>
								<div class="text">: {!! $karyawan[0]->nama_kantor !!}</div>
								</li>


							</ul>
						</div>
					</div>


					<div class="col-md-12">
						<div class="card-box profile-box">
							<h3 class="card-title">Data Kontrak Kerja
							</h3>

							<table id="example3" class="table table-bordered table-striped" style="width: 100%">
								<tr>
									<th>Tanggal Awal</th>
									<th>Tanggal Akhir</th>
									<th>File</th>
								</tr>
								<?php
								if (!count($kontrak)) echo '';
								foreach ($kontrak as $kontrak) { ?>
								<tr>
									<td class="text"><?= $help->tgl_indo($kontrak->tgl_awal) ?> </td>
									<td class="text"><?= $help->tgl_indo($kontrak->tgl_akhir) ?> </td>
									<td class="text">
									        @if($kontrak->file_kontrak_kerja)
									        <a href="{{url('dist/img/file/'.$kontrak->file_kontrak_kerja)}}" download>View File</a>
									        @endif
									</td>
								</tr>
								<?php } ?>


							</table>

							<!--<a href="#" class="btn btn-primary" style="background-color: #007bff;border-color: #007bff;border-bottom: 5px solid #007bff;"  data-toggle="modal" data-target="#datapakaiankeluarga"><i ><?= $svgicon; ?></i>>Tambah Data Pakaian Keluarga</a>/-->
						</div>
					</div>
					<div class="col-md-12">
						<div class="card-box profile-box">
							<h3 class="card-title">Data Pengalaman Kerja
								<a href="#" class="edit-icon" style="background-color: #007bff;border-color: #007bff;border-bottom: 5px solid #007bff;" data-toggle="modal" data-target="#datapengalamankerja">
									<i><?= $svgicon; ?></i></a></h3>

							<table id="example3" class="table table-bordered table-striped" style="width: 100%">
								<tr>
									<th>Perusahaan</th>
									<th>Periode Kerja</th>
									<th>Posisi Kerja</th>
									<th>Deskripsi Kerja</th>
									<th>Aksi</th>
								</tr>
								<?php
								if (!count($datapekerjaan)) echo '';
								foreach ($datapekerjaan as $pekerjaan) { ?>
								<tr>
									<td class="text"><?= ucwords($pekerjaan->nama_perusahaan) ?></td>
									<td class="text"><?= $help->tgl_indo($pekerjaan->awal_periode) ?> s/d <?= $help->tgl_indo($pekerjaan->akhir_periode) ?></td>
									<td class="text"><?= $pekerjaan->posisi_kerja ?></td>
									<td class="text"><?= $pekerjaan->deskripsi_kerja ?></td>
									<td>
										<a href="<?= route('fe.hapus_pekerjaan', $pekerjaan->p_karyawan_riwayat_pekerjaan_id); ?>">
											<i class="fa fa-trash"></i></a></td>
								</tr>
								<?php } ?>


							</table>

							<!--<a href="#" class="btn btn-primary" style="background-color: #007bff;border-color: #007bff;border-bottom: 5px solid #007bff;"  data-toggle="modal" data-target="#datapakaiankeluarga"><i ><?= $svgicon; ?></i>>Tambah Data Pakaian Keluarga</a>/-->
						</div>
					</div>

				</div>
			</div>
			<!-- /Projects Tab -->

			<!-- Bank Statutory Tab -->
			<div class="tab-pane fade <?=$request->active=='award'?'active show':''?>" id="data_reward_sanksi">

				<div class="card-box profile-box">
					<h3 class="card-title text-center">Data Reward Perusahaan
					<a href="#" class="edit-icon" style="background-color: #007bff;border-color: #007bff;border-bottom: 5px solid #007bff;" data-toggle="modal" data-target="#awardperusahaan">
						<i><?= $svgicon; ?></i></a>
							</h3>

					<table id="example3" class="table table-bordered table-striped" style="width: 100%">
						<tr>
							<th>Nama Penghargaan</th>
							<th>Hadiah</th>
							<th>Tanggal Penghargaan</th>

							
						</tr>
						<?php
						if (!count($award)) echo '';
						foreach ($award as $award) { ?>
						<tr>
							<td class="text"><?= ucwords($award->nama_jenis_reward) ?></td>
							<td class="text"><?= ucwords($award->hadiah) ?></td>
							<td class="text"><?= $help->tgl_indo($award->tgl_award) ?> </td>
							
							<!--<td><a href="<?= route('fe.hapus_award', $award->p_karyawan_award_id); ?>">
								<i class="fa fa-trash"></i></a></td>-->
						</tr>
						<?php } ?>


					</table>

					<!--<a href="#" class="btn btn-primary" style="background-color: #007bff;border-color: #007bff;border-bottom: 5px solid #007bff;"  data-toggle="modal" data-target="#datapakaiankeluarga"><i ><?= $svgicon; ?></i>>Tambah Data Pakaian Keluarga</a>/-->
				</div><div class="card-box profile-box">
					<h3 class="card-title text-center">Data Sanksi Perusahaan
						<!--<a href="#" class="edit-icon" style="background-color: #007bff;border-color: #007bff;border-bottom: 5px solid #007bff;" data-toggle="modal" data-target="#awardperusahaan">
							<i><?= $svgicon; ?></i></a>
							
							--></h3>

					<table id="example3" class="table table-bordered table-striped" style="width: 100%">
						<tr>
							<th>Jenis Sanksi</th>
							<th>Tanggal Belaku Sanksi</th>
							<th>Alasan Sanksi</th>

						</tr>
						<?php
						if (!count($sanksi)) echo '';
						foreach ($sanksi as $sanksi) { ?>
						<tr>
						<td class="text"><?= ucwords($sanksi->nama_sanksi) ?></td>
						<td class="text"><?= $help->tgl_indo($sanksi->tgl_awal_sanksi) ?> s/d <?= $help->tgl_indo($sanksi->tgl_akhir_sanksi) ?></td>
						<td class="text"><?= ($sanksi->alasan_sanksi) ?></td>
							
						</tr>
						<?php } ?>


					</table>

					<!--<a href="#" class="btn btn-primary" style="background-color: #007bff;border-color: #007bff;border-bottom: 5px solid #007bff;"  data-toggle="modal" data-target="#datapakaiankeluarga"><i ><?= $svgicon; ?></i>>Tambah Data Pakaian Keluarga</a>/-->
				</div>
			</div>
			<div class="tab-pane fade <?=$request->active=='pakaian_keluarga'?'active show':''?>" id="data_lainnya">


				<div class="card-box profile-box">
					<h3 class="card-title">Data Pakaian
						<a href="#" class="edit-icon" style="background-color: #007bff;border-color: #007bff;border-bottom: 5px solid #007bff;" data-toggle="modal" data-target="#datapakaiankeluarga">
							<i><?= $svgicon; ?></i></a></h3>

					<table id="example3" class="table table-bordered table-striped" style="width: 100%">
						<tr>
							<th>Nama</th>
							<th>Gamis</th>
							<th>Kemeja</th>
							<th>Kaos</th>
							<th>Jaket</th>
							<th>Celana</th>
							<th>Sepatu</th>
							<th>Aksi</th>
						</tr>
						<?php
						if (!count($datapakaiankeluarga)) echo '';
							$pakaian_sudah_ada = array();
						
						foreach ($datapakaiankeluarga as $pakaiank) { 
							$pakaian_sudah_ada[]= $pakaiank->keluarga_id; 
							?>
						<tr>
							<td><?= ucwords($pakaiank->nama) ?> (<?= $pakaiank->hubungan ?>)</td>
							<td class="text"> <?= $pakaiank->gamis ?></td>
							<td class="text"><?= $pakaiank->kemeja ?></td>
							<td class="text"><?= $pakaiank->kaos ?></td>
							<td class="text"><?= $pakaiank->jaket ?></td>
							<td class="text"><?= $pakaiank->celana ?></td>
							<td class="text"><?= $pakaiank->sepatu ?></td>
							<td>
								<a href="<?= route('fe.hapus_pakaian', $pakaiank->p_karyawan_pakaian_id); ?>">
									<i class="fa fa-trash"></i></a></td>
						</tr>
						<?php } ?>


					</table>

					<!--<a href="#" class="btn btn-primary" style="background-color: #007bff;border-color: #007bff;border-bottom: 5px solid #007bff;"  data-toggle="modal" data-target="#datapakaiankeluarga"><i ><?= $svgicon; ?></i>>Tambah Data Pakaian Keluarga</a>/-->
				</div>
				
			</div>
			<div class="tab-pane fade  <?=in_array($request->active,array('kartu',"bank"))?'active show':''?>" id="bank_statutory">
				<div class="card-box profile-box">
					<h3 class="card-title">Data Bank
						<!--<a href="#" class="edit-icon" style="background-color: #007bff;border-color: #007bff;border-bottom: 5px solid #007bff;"  data-toggle="modal" data-target="#databank"><i ><?= $svgicon; ?></i></a>-->
					</h3>

					<ul class="personal-info">
						<li>
						<div class="title">Bank</div>
						<div class="text">: {!! $karyawan[0]->bank !!}</div>
						</li>
						<li>
						<div class="title">No Rek</div>
						<div class="text">: {!! $karyawan[0]->norek !!}</div>
						</li>




					</ul>
				</div>
				<div class="card-box profile-box">
					<h3 class="card-title">Data Kartu Identitas
						<a href="#" class="edit-icon" style="background-color: #007bff;border-color: #007bff;border-bottom: 5px solid #007bff;"  data-toggle="modal" data-target="#datakartu">
							<i ><?= $svgicon; ?></i></a>
					</h3>
					<div class="row">
						<div class="col-md-6">
							<ul class="personal-info">
								<li>
								<div class="title">No. KK</div>
								<div class="text">: {!! $karyawan[0]->kartu_keluarga !!}</div>
								</li>
								<li>
								<div class="title">No. KTP</div>
								<div class="text">: {!! $karyawan[0]->ktp !!}</div>
								</li>
								<li>
								<div class="title">No. NPWP</div>
								<div class="text">: {!! $karyawan[0]->no_npwp !!}</div>
								</li>

								<li>
								<div class="title">No. SIM A</div>
								<div class="text">: {!! $karyawan[0]->no_sima !!}</div>
								</li>
								<li>
								<div class="title">No. SIM C</div>
								<div class="text">: {!! $karyawan[0]->no_simc !!}</div>
								</li>



							</ul>
						</div>
						<div class="col-md-6">
							<ul class="personal-info">
								<li>
								<div class="title">No. BPJS Ketenagakerjaan</div>
								<div class="text">: {!! $karyawan[0]->no_bpjstk !!}</div>
								</li>
								<li>
								<div class="title">No. BPJS Kesehatan</div>
								<div class="text">: {!! $karyawan[0]->no_bpjsks !!}</div>
								</li>
								<li>
								<div class="title">No. Pasport</div>
								<div class="text">: {!! $karyawan[0]->no_pasport !!}</div>
								</li>



							</ul>
						</div>
						<div class="col-md-12">
							<ul class="personal-info">
								<li>
								<div class="title">File KK</div>

								<div class="text">: 
								@if($karyawan[0]->file_kk!=null)
								<img src="{!! asset('dist/img/file/'.$karyawan[0]->file_kk) !!}" alt="File Terupload" class="">

								@else
								<img src="{!! asset('dist/img/noimage.png') !!}" class="" alt="File Belum Diupload">
								@endif
								</div>
								</li>
								<li>
								<div class="title">File KTP</div>
								<div class="text">: @if($karyawan[0]->file_ktp!=null)

								<img src="{!! asset('dist/img/file/'.$karyawan[0]->file_ktp) !!}" alt="File Terupload" class="">

								@else
								<img src="{!! asset('dist/img/noimage.png') !!}" class="" alt="File Belum Diupload">
								@endif
								</div>
								</li>
								<li>
								<div class="title">File BPJS</div>
								<div class="text">: @if($karyawan[0]->file_bpjs_karyawan!=null)

									<img src="{!! asset('dist/img/file/'.$karyawan[0]->file_bpjs_karyawan) !!}" alt="File Terupload" class="">

									@else
									<img src="{!! asset('dist/img/noimage.png') !!}" class="" alt="File Belum Diupload">
									@endif
								</div>

							</ul>
						</div>
					</div>

				</div>
			</div>
			<div class="tab-pane fade  <?=in_array($request->active,array('password'))?'active show':''?>" id="password_statutory">
				<div class="card-box profile-box">
					<form action="{!!route('fe.update_profile',[$karyawan[0]->p_karyawan_id,'type=password'])!!}" method="post" enctype="multipart/form-data">
						{{ csrf_field() }}
						<h3 class="card-title">Data Ubah Password</h3>
						<div class="form-group">
							<label>Password</label>
							<input type="password" class="form-control" placeholder="Password ..." id="password" name="password" value="">
						</div>
						<div class="submit-section">
							<button class="btn btn-primary submit-btn">Submit</button>
						</div>
					</form>

				</div>
			</div>
			<div class="tab-pane fade <?=in_array($request->active,array('profile'))?'active show':''?>" id="profile_statutory">
				<div class="card-box profile-box">
					<form action="{!!route('fe.update_profile',[$karyawan[0]->p_karyawan_id,'type=profile'])!!}" method="post" enctype="multipart/form-data">
						{{ csrf_field() }}
						<h3 class="card-title">Data Ubah Profile</h3>
						<div class="form-group">
							<label>Upload Foto</label><br>
							@if($karyawan[0]->foto!=null)

							@if (file_exists(asset('dist/img/profile/'.$karyawan[0]->foto))) {
							$filefound = '0';
							}
							<img src="{!! asset('dist/img/profile/'.$karyawan[0]->foto) !!}" alt="User Avatar" class="profile-user-img img-fluid img-thumbnail" style="max-width: 151px;">
							@else
							<img src="{!! asset('dist/img/profile/user.png') !!}" class="profile-user-img img-fluid img-circle" alt="User Image" style="max-width: 151px;">
							@endif

							@else
							<img src="{!! asset('dist/img/profile/user.png') !!}" class="img-size-64 mr-3 img-circle elevation-2" alt="User Image" style="max-width: 151px;">
							@endif
							<input type="file" class="form-control" id="image" name="image" value="{!! $karyawan[0]->foto !!}">
						</div>
						<div class="submit-section">
							<button class="btn btn-primary submit-btn">Submit</button>
						</div>
					</form>
				</div>
			</div>
			<!-- /Bank Statutory Tab -->

		</div>
	</div>
	<!-- /Page Content -->

	<!-- Profile Modal -->


	<!-- Family Info Modal -->
	<div id="awardperusahaan" class="modal custom-modal fade" role="dialog">
		<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Data Award Perusahaan</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">x</span>
					</button>
				</div>
				<div class="modal-body">
					<form action="{!!route('fe.update_profile',[$karyawan[0]->p_karyawan_id,'type=award'])!!}" method="post" enctype="multipart/form-data">
						{{ csrf_field() }}
						<div class="form-scroll">



							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<label>Nama Award<span class="text-danger">*</span></label>
										<input class="form-control" type="text" name="nama_award" value="">
									</div>
								</div>

								<div class="col-md-6">
									<div class="form-group">
										<label>Tanggal Award </label>
										<input class="form-control" type="date" name="tgl_award" value="">
									</div>
								</div>

							</div>

							<!--<div class="add-more mb-2">
							<a href="javascript:void(0);" onclick="add_more_keluarga()"><i class="fa fa-plus-circle"></i> Add More</a>
							</div>-->

						</div>
						<div class="submit-section">
							<button class="btn btn-primary submit-btn">Submit</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
	<div id="family_info_modal" class="modal custom-modal fade" role="dialog">
		<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Data Keluarga</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">x</span>
					</button>
				</div>
				<div class="modal-body">
					<form action="{!!route('fe.update_profile',[$karyawan[0]->p_karyawan_id,'type=keluarga'])!!}" method="post" enctype="multipart/form-data">
						{{ csrf_field() }}
						<div class="form-scroll">

							<div class="card-box">
								<h3 class="card-title">Data Anggota Keluarga
									<!--<a href="javascript:void(0);" class="delete-icon" onclick="hapus_keluarga(this)">x</a>-->
								</h3>
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<label>Nama <span class="text-danger">*</span></label>
											<input class="form-control" type="text" name="namakeluarga[]" value="">
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label>Hubungan <span class="text-danger">*</span></label>
											<select class="form-control" type="text" required="" name="hubungankeluarga[]">
												<option value="">- Pilih Hubungan-</option>
												<option>Anak</option>
												<option>Suami/Istri</option>
												<option>Ayah</option>
												<option>Ibu</option>
												<option>Nenek</option>
												<option>Kakek</option>
												<option>Adik</option>
												<option>Kakak</option>
												<!--<option >Kakak perempuan ayah</option>
												<option >Adik perempuan ayah</option>
												<option >Kakak laki laki dari ibu</option>
												<option >Adik laki laki dari Ibu</option>-->
											</select>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label>Tanggal Lahir </label>
											<input class="form-control" type="date" name="tgl_lahirkeluarga[]" value="">
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label>No Hp</label>
											<input class="form-control" type="number" name="no_hpkeluarga[]" value="">
										</div>
									</div>
								</div>
							</div>

							<div id="kontenkeluarga"></div>
							<!--<div class="add-more mb-2">
							<a href="javascript:void(0);" onclick="add_more_keluarga()"><i class="fa fa-plus-circle"></i> Add More</a>
							</div>-->

						</div>
						<div class="submit-section">
							<button class="btn btn-primary submit-btn">Submit</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
	<div id="datapengalamankerja" class="modal custom-modal fade" role="dialog">
		<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Data Pengalaman Pekerjaan</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">x</span>
					</button>
				</div>
				<div class="modal-body">
					<form action="{!!route('fe.update_profile',[$karyawan[0]->p_karyawan_id,'type=riwayatpekerjaan'])!!}" method="post" enctype="multipart/form-data">
						{{ csrf_field() }}
						<div class="form-scroll">

							<div class="card-box">
								<h3 class="card-title">Data Pengalaman Pekerjaan
									<!--<a href="javascript:void(0);" class="delete-icon" onclick="hapus_keluarga(this)">x</a>-->
								</h3>
								<div class="row">
									<div class="col-md-12">
										<div class="form-group">
											<label>Nama Perusahaan<span class="text-danger">*</span></label>
											<input class="form-control" type="text" name="namaperusahaan" value="" placeholder="Nama Perusahaan...">
										</div>
									</div>

									<div class="col-md-6">
										<div class="form-group">
											<label>Tanggal Awal Periode Kerja </label>
											<input class="form-control" type="date" name="tgl_awal_kerja" value=""  placeholder="Tanggal Awal Periode Kerja...">
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label>Tanggal Akhir Periode Kerja </label>
											<input class="form-control" type="date" name="tgl_akhir_kerja" value="" placeholder="Tanggal Akhir Periode Kerja...">
										</div>
									</div>
									<div class="col-md-12">
										<div class="form-group">
											<label>Posisi Kerja</label>
											<input class="form-control" type="text" name="posisi_kerja" value="" placeholder="Posisi Kerja...">
										</div>
									</div>
									<div class="col-md-12">
										<div class="form-group">
											<label>Deskripsi Kerja</label>
											<textarea class="form-control" type="text" name="deskripsi_kerja" value=""></textarea>
										</div>
									</div>
								</div>
							</div>

							<div id="kontenkeluarga"></div>
							<!--<div class="add-more mb-2">
							<a href="javascript:void(0);" onclick="add_more_keluarga()"><i class="fa fa-plus-circle"></i> Add More</a>
							</div>-->

						</div>
						<div class="submit-section">
							<button class="btn btn-primary submit-btn">Submit</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
	<div id="pendidikan_info_modal" class="modal custom-modal fade" role="dialog">
		<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Data Pendidikan</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">x</span>
					</button>
				</div>
				<div class="modal-body">
					<form action="{!!route('fe.update_profile',[$karyawan[0]->p_karyawan_id,'type=listpendidikan'])!!}" method="post" enctype="multipart/form-data">
						{{ csrf_field() }}
						<div class="form-scroll">

							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<label>Jenjang <span class="text-danger">*</span></label>
										<select class="form-control" type="text" required="" name="jenjang">
											<option value="">- Pilih Jenjang-</option>
											<option value="SD">SD Sederajat</option>
											<option value="SMP">SMP Sederajat</option>
											<option value="SMA">SMA/SMK Sederajat</option>
											<option value="D1">D1</option>
											<option value="D2">D2</option>
											<option value="D3">D3</option>
											<option value="D4">D4</option>
											<option value="S1">S1</option>
											<option value="S2">S2</option>
											<option value="S3">S3</option>

										</select>
									</div>
								</div>

								<div class="col-md-6">
									<div class="form-group">
										<label>Nama Sekolah<span class="text-danger">*</span></label>
										<input class="form-control" type="text" name="namasekolah" value="" placeholder="Nama Sekolah"  required="" >
									</div>
								</div>

								<div class="col-md-12">
									<div class="form-group">
										<label>Jurusan</label>
										<input class="form-control" type="text" name="jurusan" value=""  placeholder="Jurusan..">
									</div>
								</div>

								<div class="col-md-12">
									<div class="form-group">
										<label>Alamat </label>
										<textarea class="form-control" type="" name="alamat_pendidikan" placeholder="Alamat.."></textarea>
									</div>
								</div>
								<div class="col-md-12">
									<div class="form-group">
										<label>Kota</label>
										<input class="form-control" type="text" name="kotapendidikan" placeholder="Kota..">
									</div>
								</div>
								<div class="col-md-12">
									<div class="form-group">
										<label>Tahun Lulus<span class="text-danger">*</span></label>
										<input class="form-control" type="text" name="Tahunlulus" required="" placeholder="Tahun Lulus..">
									</div>
								</div>
							</div>

						</div>
						<div class="submit-section">
							<button class="btn btn-primary submit-btn">Submit</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
	<div id="kursus_info_modal_edit" class="modal custom-modal fade" role="dialog">
		<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Data Kursus dan Pelatihan</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">x</span>
					</button>
				</div>
				<div class="modal-body">
					<form action="{!!route('fe.update_profile',[$karyawan[0]->p_karyawan_id,'type=kursus'])!!}" method="post" enctype="multipart/form-data">
						{{ csrf_field() }}
						<div class="form-scroll">

							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<label>Nama Kursus<span class="text-danger">*</span></label>
										<input class="form-control" type="text" name="nama_kursus" placeholder="Nama Kursus.." value="">
									</div>
								</div>

								<div class="col-md-12">
									<div class="form-group">
										<label>Penyelenggara </label>
										<input class="form-control" type="" name="penyelenggara" placeholder="Penyelenggara.."></input>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label>Tanggal Awal</label>
										<input class="form-control" type="date" name="tanggal_awal_pelatihan" placeholder="Tanggal Awal..">
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label>Tanggal Akhir</label>
										<input class="form-control" type="date" name="tanggal_akhir_pelatihan" placeholder="Tanggal Akhir..">
									</div>
								</div>
								<div class="col-md-12">
									<div class="form-group">
										<label>Sertifikat </label>
										<select class="form-control" type="" name="sertifikat" placeholder="Alamat..">
											<option value="">-Pilih Sertifikat-</option>
											<option value="1">Ada</option>
											<option value="0">Tidak</option>
										</select>
									</div>
								</div>
							</div>

						</div>
						<div class="submit-section">
							<button class="btn btn-primary submit-btn">Submit</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
	<div id="datapakaian" class="modal custom-modal fade" role="dialog">
		<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Data Pakaian Diri Sendiri</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">x</span>
					</button>
				</div>
				<div class="modal-body">
					<form action="{!!route('fe.update_profile',[$karyawan[0]->p_karyawan_id,'type=pakaian'])!!}" method="post" enctype="multipart/form-data">
						{{ csrf_field() }}
						<div class="form-scroll">

							<div class="row">
								<div class="col-md-12">
									<div class="form-group">
										<label>Ukuran Gamis<span class="text-danger">*</span></label>
										<select class="form-control" type="" name="gamis" placeholder="Alamat..">
											<option value="">-Pilih Ukuran Gamis-</option>
											<option value="XS">XS</option>
											<option value="S">S</option>
											<option value="M">M</option>
											<option value="L">L</option>
											<option value="XL">XL</option>
											<option value="XXL">XXL</option>
											<option value="XXXL">XXXL</option>
											<option value="1">1 (Ukuran Anak)</option>
											<option value="2">2 (Ukuran Anak)</option>
											<option value="3">3 (Ukuran Anak)</option>
											<option value="4">4 (Ukuran Anak)</option>
											<option value="5">5 (Ukuran Anak)</option>
											<option value="6">6 (Ukuran Anak)</option>
											<option value="7">7 (Ukuran Anak)</option>
											<option value="8">8 (Ukuran Anak)</option>
											<option value="9">9 (Ukuran Anak)</option>
											<option value="10">10 (Ukuran Anak)</option>
											<option value="11">11 (Ukuran Anak)</option>
											<option value="12">12 (Ukuran Anak)</option>
										</select>
									</div>

									<div class="form-group">
										<label>Ukuran Kemeja<span class="text-danger">*</span></label>
										<select class="form-control" type="" name="kemeja" placeholder="Alamat..">
											<option value="">-Pilih Ukuran kemeja-</option>
											<option value="XS">XS</option>
											<option value="S">S</option>
											<option value="M">M</option>
											<option value="L">L</option>
											<option value="XL">XL</option>
											<option value="XXL">XXL</option>
											<option value="XXXL">XXXL</option>
											<option value="1">1 (Ukuran Anak)</option>
											<option value="2">2 (Ukuran Anak)</option>
											<option value="3">3 (Ukuran Anak)</option>
											<option value="4">4 (Ukuran Anak)</option>
											<option value="5">5 (Ukuran Anak)</option>
											<option value="6">6 (Ukuran Anak)</option>
											<option value="7">7 (Ukuran Anak)</option>
											<option value="8">8 (Ukuran Anak)</option>
											<option value="9">9 (Ukuran Anak)</option>
											<option value="10">10 (Ukuran Anak)</option>
											<option value="11">11 (Ukuran Anak)</option>
											<option value="12">12 (Ukuran Anak)</option>

										</select>
									</div>

									<div class="form-group">
										<label>Ukuran Kaos<span class="text-danger">*</span></label>
										<select class="form-control" type="" name="kaos" placeholder="Alamat..">
											<option value="">-Pilih Ukuran Kaos-</option>
											<option value="XS">XS</option>
											<option value="S">S</option>
											<option value="M">M</option>
											<option value="L">L</option>
											<option value="XL">XL</option>
											<option value="XXL">XXL</option>
											<option value="XXXL">XXXL</option>
											<option value="1">1 (Ukuran Anak)</option>
											<option value="2">2 (Ukuran Anak)</option>
											<option value="3">3 (Ukuran Anak)</option>
											<option value="4">4 (Ukuran Anak)</option>
											<option value="5">5 (Ukuran Anak)</option>
											<option value="6">6 (Ukuran Anak)</option>
											<option value="7">7 (Ukuran Anak)</option>
											<option value="8">8 (Ukuran Anak)</option>
											<option value="9">9 (Ukuran Anak)</option>
											<option value="10">10 (Ukuran Anak)</option>
											<option value="11">11 (Ukuran Anak)</option>
											<option value="12">12 (Ukuran Anak)</option>
										</select>
									</div>
									<div class="form-group">
										<label>Ukuran Jaket<span class="text-danger">*</span></label>
										<select class="form-control" type="" name="jaket" placeholder="Alamat..">
											<option value="">-Pilih Ukuran Jaket-</option>
											<option value="XS">XS</option>
											<option value="S">S</option>
											<option value="M">M</option>
											<option value="L">L</option>
											<option value="XL">XL</option>
											<option value="XXL">XXL</option>
											<option value="XXXL">XXXL</option>
											<option value="1">1 (Ukuran Anak)</option>
											<option value="2">2 (Ukuran Anak)</option>
											<option value="3">3 (Ukuran Anak)</option>
											<option value="4">4 (Ukuran Anak)</option>
											<option value="5">5 (Ukuran Anak)</option>
											<option value="6">6 (Ukuran Anak)</option>
											<option value="7">7 (Ukuran Anak)</option>
											<option value="8">8 (Ukuran Anak)</option>
											<option value="9">9 (Ukuran Anak)</option>
											<option value="10">10 (Ukuran Anak)</option>
											<option value="11">11 (Ukuran Anak)</option>
											<option value="12">12 (Ukuran Anak)</option>
										</select>
									</div>
									<div class="form-group">
										<label>Ukuran Sepatu<span class="text-danger">*</span></label>
										<input class="form-control" type="number" name="sepatu" placeholder="Ukuran Sepatu..">

									</div>
									<div class="form-group">
										<label>Ukuran Celana<span class="text-danger">*</span></label>
										<input class="form-control" type="number" name="celana" placeholder="Ukuran Celana..">

									</div>
								</div>

							</div>

						</div>
						<div class="submit-section">
							<button class="btn btn-primary submit-btn">Submit</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
	<div id="datapakaiankeluarga" class="modal custom-modal fade" role="dialog">
		<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Data Pakaian Keluarga</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">x</span>
					</button>
				</div>
				<div class="modal-body">
					<form action="{!!route('fe.update_profile',[$karyawan[0]->p_karyawan_id,'type=pakaian_keluarga'])!!}" method="post" enctype="multipart/form-data">
						{{ csrf_field() }}
						<div class="form-scroll">

							<div class="row">
								<div class="col-md-12">
									<div class="form-group">
										<label>Nama<span class="text-danger">*</span></label>
										<select class="form-control" type="" name="keluarga" placeholder="Alamat.." required="">
											<option value="">-Pilih Anggota Keluarga-</option>
											<?php if(!in_array(-1,$pakaian_sudah_ada)){?>
											<option value="-1">{!! $karyawan[0]->nama_lengkap !!}(Diri sendiri)</option>
											<?php }?>
											<?php foreach ($keluarga as $keluarga2) {
												if(!in_array($keluarga2->p_karyawan_keluarga_id,$pakaian_sudah_ada)){ ?>
											<option value="<?= $keluarga2->p_karyawan_keluarga_id ?>"><?= ucwords($keluarga2->nama); ?>(<?= $keluarga2->hubungan; ?>)</option>
											<?php } ?>
											<?php } ?>

										</select>
									</div>
									<div class="form-group">
										<label>Ukuran Gamis<span class="text-danger">*</span></label>
										<select class="form-control" type="" name="gamis" placeholder="Alamat..">
											<option value="">-Pilih Ukuran Gamis-</option>
											<option value="XS">XS</option>
											<option value="S">S</option>
											<option value="M">M</option>
											<option value="L">L</option>
											<option value="XL">XL</option>
											<option value="XXL">XXL</option>
											<option value="XXXL">XXXL</option>
											<option value="1">1 (Ukuran Anak)</option>
											<option value="2">2 (Ukuran Anak)</option>
											<option value="3">3 (Ukuran Anak)</option>
											<option value="4">4 (Ukuran Anak)</option>
											<option value="5">5 (Ukuran Anak)</option>
											<option value="6">6 (Ukuran Anak)</option>
											<option value="7">7 (Ukuran Anak)</option>
											<option value="8">8 (Ukuran Anak)</option>
											<option value="9">9 (Ukuran Anak)</option>
											<option value="10">10 (Ukuran Anak)</option>
											<option value="11">11 (Ukuran Anak)</option>
											<option value="12">12 (Ukuran Anak)</option>
										</select>
									</div>

									<div class="form-group">
										<label>Ukuran Kemeja<span class="text-danger">*</span></label>
										<select class="form-control" type="" name="kemeja" placeholder="Alamat..">
											<option value="">-Pilih Ukuran kemeja-</option>
											<option value="XS">XS</option>
											<option value="S">S</option>
											<option value="M">M</option>
											<option value="L">L</option>
											<option value="XL">XL</option>
											<option value="XXL">XXL</option>
											<option value="XXXL">XXXL</option>
											<option value="1">1 (Ukuran Anak)</option>
											<option value="2">2 (Ukuran Anak)</option>
											<option value="3">3 (Ukuran Anak)</option>
											<option value="4">4 (Ukuran Anak)</option>
											<option value="5">5 (Ukuran Anak)</option>
											<option value="6">6 (Ukuran Anak)</option>
											<option value="7">7 (Ukuran Anak)</option>
											<option value="8">8 (Ukuran Anak)</option>
											<option value="9">9 (Ukuran Anak)</option>
											<option value="10">10 (Ukuran Anak)</option>
											<option value="11">11 (Ukuran Anak)</option>
											<option value="12">12 (Ukuran Anak)</option>
										</select>
									</div>

									<div class="form-group">
										<label>Ukuran Kaos<span class="text-danger">*</span></label>
										<select class="form-control" type="" name="kaos" placeholder="Alamat..">
											<option value="">-Pilih Ukuran Kaos-</option>
											<option value="XS">XS</option>
											<option value="S">S</option>
											<option value="M">M</option>
											<option value="L">L</option>
											<option value="XL">XL</option>
											<option value="XXL">XXL</option>
											<option value="XXXL">XXXL</option>
											<option value="1">1 (Ukuran Anak)</option>
											<option value="2">2 (Ukuran Anak)</option>
											<option value="3">3 (Ukuran Anak)</option>
											<option value="4">4 (Ukuran Anak)</option>
											<option value="5">5 (Ukuran Anak)</option>
											<option value="6">6 (Ukuran Anak)</option>
											<option value="7">7 (Ukuran Anak)</option>
											<option value="8">8 (Ukuran Anak)</option>
											<option value="9">9 (Ukuran Anak)</option>
											<option value="10">10 (Ukuran Anak)</option>
											<option value="11">11 (Ukuran Anak)</option>
											<option value="12">12 (Ukuran Anak)</option>
										</select>
									</div>
									<div class="form-group">
										<label>Ukuran Jaket<span class="text-danger">*</span></label>
										<select class="form-control" type="" name="jaket" placeholder="Alamat..">
											<option value="">-Pilih Ukuran Jaket-</option>
											<option value="XS">XS</option>
											<option value="S">S</option>
											<option value="M">M</option>
											<option value="L">L</option>
											<option value="XL">XL</option>
											<option value="XXL">XXL</option>
											<option value="XXXL">XXXL</option>
											<option value="1">1 (Ukuran Anak)</option>
											<option value="2">2 (Ukuran Anak)</option>
											<option value="3">3 (Ukuran Anak)</option>
											<option value="4">4 (Ukuran Anak)</option>
											<option value="5">5 (Ukuran Anak)</option>
											<option value="6">6 (Ukuran Anak)</option>
											<option value="7">7 (Ukuran Anak)</option>
											<option value="8">8 (Ukuran Anak)</option>
											<option value="9">9 (Ukuran Anak)</option>
											<option value="10">10 (Ukuran Anak)</option>
											<option value="11">11 (Ukuran Anak)</option>
											<option value="12">12 (Ukuran Anak)</option>
										</select>
									</div>
									<div class="form-group">
										<label>Ukuran Sepatu<span class="text-danger">*</span></label>
										<input class="form-control" type="number" name="sepatu" placeholder="Ukuran Sepatu..">

									</div>
									<div class="form-group">
										<label>Ukuran Celana<span class="text-danger">*</span></label>
										<input class="form-control" type="number" name="celana" placeholder="Ukuran Celana..">

									</div>
								</div>

							</div>

						</div>
						<div class="submit-section">
							<button class="btn btn-primary submit-btn">Submit</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
	<!-- /Family Info Modal -->

	<!-- Emergency Contact Modal -->
	<div id="datapendidikan" class="modal custom-modal fade" role="dialog" style="display: none;" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Data Pendidikan</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">x</span>
					</button>
				</div>
				<div class="modal-body">
					<form action="{!!route('fe.update_profile',[$karyawan[0]->p_karyawan_id,'type=pendidikan'])!!}" method="post" enctype="multipart/form-data">
						{{ csrf_field() }}
						<div class="card-box">
							<div class="form-group">
								<label>Pendidikan</label>
								<select class="form-control select2" name="pendidikan" style="width: 100%;">
									<option value="">Pilih Pendidikan</option>
									<option value="SD" <?php if ($karyawan[0]->pendidikan == 'SD') {
										echo 'selected="selected" ';
										} ?>>SD</option>
									<option value="SMP" <?php if ($karyawan[0]->pendidikan == 'SMP') {
										echo 'selected="selected" ';
										} ?>>SMP</option>
									<option value="SMA" <?php if ($karyawan[0]->pendidikan == 'SMA') {
										echo 'selected="selected" ';
										} ?>>SMA</option>
									<option value="D1" <?php if ($karyawan[0]->pendidikan == 'D1') {
										echo 'selected="selected" ';
										} ?>>D1</option>
									<option value="D2" <?php if ($karyawan[0]->pendidikan == 'D2') {
										echo 'selected="selected" ';
										} ?>>D2</option>
									<option value="D3" <?php if ($karyawan[0]->pendidikan == 'D3') {
										echo 'selected="selected" ';
										} ?>>D3</option>
									<option value="S1" <?php if ($karyawan[0]->pendidikan == 'S1') {
										echo 'selected="selected" ';
										} ?>>S1</option>
									<option value="S2" <?php if ($karyawan[0]->pendidikan == 'S2') {
										echo 'selected="selected" ';
										} ?>>S2</option>
									<option value="S3" <?php if ($karyawan[0]->pendidikan == 'S3') {
										echo 'selected="selected" ';
										} ?>>S3</option>
								</select>
							</div>
							<div class="form-group">
								<label>Jurusan</label>
								<input type="text" class="form-control" placeholder="Jurusan..." id="jurusan" name="jurusan" value="{!! $karyawan[0]->jurusan !!}">
							</div>
							<div class="form-group">
								<label>Nama Sekolah/PT</label>
								<input type="text" class="form-control" placeholder="Nama Sekolah/PT..." id="nama_sekolah" name="nama_sekolah" value="{!! $karyawan[0]->nama_sekolah !!}">
							</div>
							<div class="submit-section">
								<button class="btn btn-primary submit-btn">Submit</button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
	<div id="datakartu" class="modal custom-modal fade" role="dialog" style="display: none;" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Data Kartu Identitas</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">x</span>
					</button>
				</div>
				<div class="modal-body">
					<form action="{!!route('fe.update_profile',[$karyawan[0]->p_karyawan_id,'type=kartu'])!!}" method="post" enctype="multipart/form-data">
						{{ csrf_field() }}
						<div class="row">
							<div class="col-sm-6">
								<div class="form-group">
									<label>No. KK *</label>
									<input type="text" class="form-control" placeholder="KK..." id="kk" name="kk" value="{!! $karyawan[0]->kartu_keluarga !!}" required>

								</div>
								<div class="form-group">
									<label>No. KTP *</label>
									<input type="text" class="form-control" placeholder="KTP..." id="ktp" name="ktp" value="{!! $karyawan[0]->ktp !!}" required>
								</div>
								<div class="form-group">
									<label>No. NPWP</label>
									<input type="text" class="form-control" placeholder="NPWP..." id="npwp" name="npwp" value="{!! $karyawan[0]->no_npwp !!}">
								</div>
								<div class="form-group">
									<label>No. SIM A</label>
									<input type="text" class="form-control" placeholder="SIM A..." id="sima" name="sima" value="{!! $karyawan[0]->no_sima !!}">
								</div>

								<div class="form-group">
									<label>No. SIM C</label>
									<input type="text" class="form-control" placeholder="SIM C..." id="simc" name="simc" value="{!! $karyawan[0]->no_simc !!}">
								</div>
							</div>
							<div class="col-sm-6">

								<div class="form-group">
									<label>No. BPJS Ketenagakerjaan</label>
									<input type="text" class="form-control" placeholder="BPJS Ketenagakerjaan..." id="bpjstk" name="bpjstk" value="{!! $karyawan[0]->no_bpjstk !!}">
								</div>

								<div class="form-group">
									<label>No. BPJS Kesehatan</label>
									<input type="text" class="form-control" placeholder="BPJS Kesehatan..." id="bpjsks" name="bpjsks" value="{!! $karyawan[0]->no_bpjsks!!}">
								</div>
								<div class="form-group">
									<label>No. Passport</label>
									<input type="text" class="form-control" placeholder="Pasport..." id="pasport" name="pasport" value="{!! $karyawan[0]->no_pasport !!}">
								</div>
								<div class="form-group">
									<label>No. Absen</label>
									<input type="text" class="form-control" placeholder="No. Absen..." id="no_absen" name="no_absen" required value="{!! $karyawan[0]->no_absen !!}" readonly>
								</div>
								<div class="form-group">
									<label>File KK *</label>
									<input type="file" class="form-control" placeholder="KK..." id="kk" name="file_kk" vrequired>

								</div>
								<div class="form-group">
									<label>File KTP *</label>
									<input type="file" class="form-control" placeholder="KK..." id="kk" name="file_ktp" vrequired>

								</div>
								<div class="form-group">
									<label>File BPJS *</label>
									<input type="file" class="form-control" placeholder="KK..." id="kk" name="file_bpjs" vrequired>

								</div>
							</div>
						</div>
						<div class="submit-section">
							<button class="btn btn-primary submit-btn">Submit</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
	<div id="datapernikahan" class="modal custom-modal fade" role="dialog" style="display: none;" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Data Pernikahan</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">x</span>
					</button>
				</div>
				<div class="modal-body">
					<form action="{!!route('fe.update_profile',[$karyawan[0]->p_karyawan_id,'type=pernikahan'])!!}" method="post" enctype="multipart/form-data">
						{{ csrf_field() }}
						<div class="card-box">
							<div class="form-group">
								<label>Status Pernikahan *</label>
								<select class="form-control select2" name="status_pernikahan" style="width: 100%;" required>
								    <option value="">- Pilih -</option>
									<option value="2" <?php if ($karyawan[0]->m_status_id == 2) {
										echo 'selected="selected" ';
										} ?>>Belum Menikah</option>
									<option value="1" <?php if ($karyawan[0]->m_status_id == 1) {
										echo 'selected="selected" ';
										} ?>>Menikah</option>
								</select>
							</div>
							<div class="form-group">
								<label>Jumlah Anak *</label>
								<input type="text" class="form-control" placeholder="Jumlah Anak..." id="jumlah_anak" name="jumlah_anak" value="{!! $karyawan[0]->jumlah_anak !!}" required>
							</div>
							<div class="submit-section">
								<button class="btn btn-primary submit-btn">Submit</button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
	<div id="databank" class="modal custom-modal fade" role="dialog" style="display: none;" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Data Pernikahan</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">x</span>
					</button>
				</div>
				<div class="modal-body">
					<form action="{!!route('fe.update_profile',[$karyawan[0]->p_karyawan_id,'type=bank'])!!}" method="post" enctype="multipart/form-data">
						{{ csrf_field() }}
						<div class="">
							<div class="form-group">
								<label>No Rekening</label>
								<input type="text" class="form-control" placeholder="No Rekening..." id="norek" name="norek" value="{!! $karyawan[0]->norek !!}" required>
							</div>
							<div class="form-group">
								<label>Bank</label>
								<select type="text" class="form-control select2" id="bank" style="width: 100%;" name="bank" placeholder="Nama bank">
									<option value="">- Pilih Bank - </option>
									<?php
									$databank = array("Bank Mandiri", "BSI", "BRI", "BNI", "Panin Bank.", "BCA", "CIMB Niaga", "Bank Permata", "OCBC NISP", "BTPN", "DBS", "Bank Ganesha", "Bank NOBU", "Bank Victoria", "Bank Sampoerna", "IBK Bank", "Bank Capital", "Bank Bukopin", "Bank Mega", "Bank Mayora", "Bank UOB", "Bank Fama", "Bank Mayapada International", "Bank Mandiri Taspen", "Bank Resona Perdania", "Bank BKE", "BRI Agro", "Bank SBI Indonesia", "Bank Artha Graha", "Commonwealth Bank", "HSBC Indonesia", "ICBC Indonesia", "JP Morgan Chase", "Bank Oke Indonesia", "MNC Bank", "KEB Hana Bank", "Shinhan Bank", "Standard Chartered Bank Indonesia", "Bank of China", "BNPParibas", "Bank Jasa Jakarta", "Bank Index", "Bank Artos", "Bank Ina", "Bank Mestika", ".Bank Mas", "CTBC Bank", "Bank Sinarmas", "Maybank Indonesia", "Bank of India Indonesia", "Bank QNB Indonesia", "Bank JTrust Indonesia", "Bank Woori Saudara", "Bank Amar Indonesia", "Prima Master Bank", "Citibank Indonesia", "Daftar update  Bank Umum Syariah yang ikut program relaksasi kredit", "Bank Syariah Mandiri", "Bank BNI syariah", "Bank Bukopin Syariah", "Bank NTB Syariah", "Permata Bank Syariah", "Bank Muamalat", "Bank Mega Syariah", ".Bank BJB Syariah", "BRI Syariah", " BTPN Syariah", "Bank Net Syariah", "BCA Syariah", "Panin Dubai Syariah Bank");
									for ($i = 0; $i < count($databank); $i++) {
									$selected = '';
									if ($karyawan[0]->bank == $databank[$i])
									$selected = 'selected';

									echo "
									<option value='" . $databank[$i] . "' $selected>" . $databank[$i] . "</option>";
									}
									?>

								</select>
							</div>
							<div class="submit-section">
								<button class="btn btn-primary submit-btn">Submit</button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
	<div id="personal_info_modal" class="modal custom-modal fade" role="dialog" style="display: none;" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Edit Data Utama</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">x</span>
					</button>
				</div>
				<div class="modal-body">


					<form action="{!!route('fe.update_profile',[$karyawan[0]->p_karyawan_id,'type=utama'])!!}" method="post" enctype="multipart/form-data">
						{{ csrf_field() }}

						<div class="form-group">
							<label>NIK</label>
							<input type="text" class="form-control" placeholder="NIK..." id="nik" name="nik" value="{!! $karyawan[0]->nik !!}" disabled="disabled">
						</div>
						<div class="form-group">
							<label>Nama Lengkap *</label>
							<input type="text" class="form-control" placeholder="Nama Lengkap..." id="nama_lengkap" name="nama_lengkap" required value="{!! $karyawan[0]->nama_lengkap !!}" disabled="disabled">
						</div>
						<div class="form-group">
							<label>Nama Panggilan</label>
							<input type="text" class="form-control" placeholder="Nama Panggilan..." id="nama_panggilan" name="nama_panggilan" required value="{!! $karyawan[0]->nama_panggilan !!}">
						</div>
						<div class="form-group">
							<label>Tempat Lahir</label>
							<input type="text" class="form-control" placeholder="Tempat Lahir..." id="tempat_lahir" name="tempat_lahir" value="{!! $karyawan[0]->tempat_lahir !!}">
						</div>
						<div class="form-group">
							<label>Tanggal Lahir</label>
							<div class="input-group date" id="tgl_lahir" data-target-input="nearest">
								<input type="date" class="form-control -input" id="tgl_lahir" name="tgl_lahir" data-target="#tgl_lahir" value="{!! $karyawan[0]->tgl_lahir !!}">
								<div class="input-group-append" data-target="#tgl_lahir" data-toggle="datetimepicker">
									<div class="input-group-text">
										<i class="fa fa-calendar"></i></div>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label>Agama</label>
							<select class="form-control select2" name="agama" style="width: 100%;" required>
								<?php
								foreach ($agama as $agama) {
								if ($agama->m_agama_id == $karyawan[0]->m_agama_id) {
								echo '
								<option selected="selected" value="' . $agama->m_agama_id . '">' . $agama->nama . '</option>';
								} else {
								echo '
								<option value="' . $agama->m_agama_id . '">' . $agama->nama . '</option>';
								}
								}
								?>
							</select>
						</div>
						<div class="form-group">
							<label>Jenis Kelamin</label>
							<select class="form-control select2" name="jk" style="width: 100%;" required>
								<?php
								foreach ($jk as $jk) {
								if ($jk->m_jenis_kelamin_id == $karyawan[0]->m_jenis_kelamin_id) {
								echo '
								<option selected="selected" value="' . $jk->m_jenis_kelamin_id . '">' . $jk->nama . '</option>';
								} else {
								echo '
								<option value="' . $jk->m_jenis_kelamin_id . '">' . $jk->nama . '</option>';
								}
								}
								?>
							</select>
						</div>
						<div class="form-group">
							<label>Alamat Sesuai KTP</label>
							<textarea class="form-control" placeholder="Alamat Sesuai KTP..." id="alamat_ktp" name="alamat_ktp">{!! $karyawan[0]->alamat_ktp !!}</textarea>
						</div>



						<div class="form-group">
							<label>Domisili</label>
							<input type="text" class="form-control" placeholder="Domisili..." id="domisili" name="domisili" value="{!! $karyawan[0]->domisili !!}">
						</div>

						<div class="form-group">
							<label>Email * </label>
							<input type="email" class="form-control" placeholder="Email..." id="email" name="email" value="{!! $karyawan[0]->email_perusahaan !!}" required>
						</div>

						<div class="form-group">
							<label>No. HP *</label>
							<input type="text" class="form-control" placeholder="No. HP..." id="no_hp" name="no_hp" value="{!! $karyawan[0]->no_hp !!}" required>
						</div>

						<div class="form-group">
							<label>Nama Kontak Darurat *</label>
							<input type="text" class="form-control" placeholder="Nama Kontak Darurat..." id="no_hp" name="nama_darurat" value="{!! $karyawan[0]->nama_darurat !!}" required>
						</div>

						<div class="form-group">
							<label>No. Kontak Darurat *</label>
							<input type="text" class="form-control" placeholder="No. Kontak Darurat..." id="no_hp" name="kontak_darurat" value="{!! $karyawan[0]->kontak_darurat !!}" required>
						</div>

						<div class="form-group">
							<label>Hubungan Darurat *</label>
							<select class="form-control" type="text" required="" name="hubungan_darurat">
												<option value="">- Pilih Hubungan-</option>
												<option <?=$karyawan[0]->hubungan_darurat=='Anak'?'selected':''?>>Anak</option>
												<option <?=$karyawan[0]->hubungan_darurat=='Suami/Istri'?'selected':''?>>Suami/Istri</option>
												<option <?=$karyawan[0]->hubungan_darurat=='Ayah'?'selected':''?>>Ayah</option>
												<option <?=$karyawan[0]->hubungan_darurat=='Ibu'?'selected':''?>>Ibu</option>
												<option <?=$karyawan[0]->hubungan_darurat=='Nenek'?'selected':''?>>Nenek</option>
												<option <?=$karyawan[0]->hubungan_darurat=='Kakek'?'selected':''?>>Kakek</option>
												<option <?=$karyawan[0]->hubungan_darurat=='Adik'?'selected':''?>>Adik</option>
												<option <?=$karyawan[0]->hubungan_darurat=='Kakak'?'selected':''?>>Kakak</option>
												<!--<option >Kakak perempuan ayah</option>
												<option >Adik perempuan ayah</option>
												<option >Kakak laki laki dari ibu</option>
												<option >Adik laki laki dari Ibu</option>-->
											</select>
						</div>
						<div class="submit-section">
							<button class="btn btn-primary submit-btn">Submit</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
	<!-- /Emergency Contact Modal -->

	<!-- Education Modal -->
	<div id="education_info" class="modal custom-modal fade" role="dialog">
		<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title"> Education Informations</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">×</span>
					</button>
				</div>
				<div class="modal-body">
					<form>
						<div class="form-scroll">
							<div class="card-box">
								<h3 class="card-title">Education Informations
									<a href="javascript:void(0);" class="delete-icon">
										<i class="fa fa-trash-o"></i></a></h3>
								<div class="row">
									<div class="col-md-6">
										<div class="form-group form-focus focused">
											<input type="text" value="Oxford University" class="form-control floating">
											<label class="focus-label">Institution</label>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group form-focus focused">
											<input type="text" value="Computer Science" class="form-control floating">
											<label class="focus-label">Subject</label>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group form-focus focused">
											<div class="cal-icon">
												<input type="text" value="01/06/2002" class="form-control floating datetimepicker">
											</div>
											<label class="focus-label">Starting Date</label>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group form-focus focused">
											<div class="cal-icon">
												<input type="text" value="31/05/2006" class="form-control floating datetimepicker">
											</div>
											<label class="focus-label">Complete Date</label>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group form-focus focused">
											<input type="text" value="BE Computer Science" class="form-control floating">
											<label class="focus-label">Degree</label>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group form-focus focused">
											<input type="text" value="Grade A" class="form-control floating">
											<label class="focus-label">Grade</label>
										</div>
									</div>
								</div>
							</div>
							<div class="card-box">
								<h3 class="card-title">Education Informations
									<a href="javascript:void(0);" class="delete-icon">
										<i class="fa fa-trash-o"></i></a></h3>
								<div class="row">
									<div class="col-md-6">
										<div class="form-group form-focus focused">
											<input type="text" value="Oxford University" class="form-control floating">
											<label class="focus-label">Institution</label>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group form-focus focused">
											<input type="text" value="Computer Science" class="form-control floating">
											<label class="focus-label">Subject</label>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group form-focus focused">
											<div class="cal-icon">
												<input type="text" value="01/06/2002" class="form-control floating datetimepicker">
											</div>
											<label class="focus-label">Starting Date</label>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group form-focus focused">
											<div class="cal-icon">
												<input type="text" value="31/05/2006" class="form-control floating datetimepicker">
											</div>
											<label class="focus-label">Complete Date</label>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group form-focus focused">
											<input type="text" value="BE Computer Science" class="form-control floating">
											<label class="focus-label">Degree</label>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group form-focus focused">
											<input type="text" value="Grade A" class="form-control floating">
											<label class="focus-label">Grade</label>
										</div>
									</div>
								</div>
								<div class="add-more">
									<a href="javascript:void(0);">
										<i class="fa fa-plus-circle"></i> Add More</a>
								</div>
							</div>
						</div>
						<div class="submit-section">
							<button class="btn btn-primary submit-btn">Submit</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
	<!-- /Education Modal -->

	<!-- Experience Modal -->
	<div id="experience_info" class="modal custom-modal fade" role="dialog" style="display: none;" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Experience Informations</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">×</span>
					</button>
				</div>
				<div class="modal-body">
					<form>
						<div class="form-scroll">
							<div class="card-box">
								<h3 class="card-title">Experience Informations
									<a href="javascript:void(0);" class="delete-icon">
										<i class="fa fa-trash-o"></i></a></h3>
								<div class="row">
									<div class="col-md-6">
										<div class="form-group form-focus focused">
											<input type="text" class="form-control floating" value="Digital Devlopment Inc">
											<label class="focus-label">Company Name</label>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group form-focus focused">
											<input type="text" class="form-control floating" value="United States">
											<label class="focus-label">Location</label>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group form-focus focused">
											<input type="text" class="form-control floating" value="Web Developer">
											<label class="focus-label">Job Position</label>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group form-focus focused">
											<div class="cal-icon">
												<input type="text" class="form-control floating datetimepicker" value="01/07/2007">
											</div>
											<label class="focus-label">Period From</label>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group form-focus focused">
											<div class="cal-icon">
												<input type="text" class="form-control floating datetimepicker" value="08/06/2018">
											</div>
											<label class="focus-label">Period To</label>
										</div>
									</div>
								</div>
							</div>
							<div class="card-box">
								<h3 class="card-title">Experience Informations
									<a href="javascript:void(0);" class="delete-icon">
										<i class="fa fa-trash-o"></i></a></h3>
								<div class="row">
									<div class="col-md-6">
										<div class="form-group form-focus focused">
											<input type="text" class="form-control floating" value="Digital Devlopment Inc">
											<label class="focus-label">Company Name</label>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group form-focus focused">
											<input type="text" class="form-control floating" value="United States">
											<label class="focus-label">Location</label>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group form-focus focused">
											<input type="text" class="form-control floating" value="Web Developer">
											<label class="focus-label">Job Position</label>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group form-focus focused">
											<div class="cal-icon">
												<input type="text" class="form-control floating datetimepicker" value="01/07/2007">
											</div>
											<label class="focus-label">Period From</label>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group form-focus focused">
											<div class="cal-icon">
												<input type="text" class="form-control floating datetimepicker" value="08/06/2018">
											</div>
											<label class="focus-label">Period To</label>
										</div>
									</div>
								</div>
								<div class="add-more">
									<a href="javascript:void(0);">
										<i class="fa fa-plus-circle"></i> Add More</a>
								</div>
							</div>
						</div>
						<div class="submit-section">
							<button class="btn btn-primary submit-btn">Submit</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
	<!-- /Experience Modal -->

</div>
<script>
	function hapus_keluarga(e)
	{
		$(e).parent().parent().remove();
	}

	function add_more_keluarga()
	{
		$('#kontenkeluarga').append('<div class="card-box" id=""> 											<h3 class="card-title">Data Anggota Keluarga <a href="javascript:void(0);"  onclick="hapus_keluarga(this)" class="delete-icon">x</a></h3> 											<div class="row"> 												<div class="col-md-6"> 													<div class="form-group"> 														<label>Nama <span class="text-danger">*</span></label> 														<input class="form-control" type="text" name="namakeluarga[]"> 													</div> 												</div> 												<div class="col-md-6"> 													<div class="form-group"> 														<label>Hubungan <span class="text-danger">*</span></label> 														<select class="form-control" type="text" required="" name="hubungankeluarga[]"> 															<option value="">- Pilih Hubungan-</option> 															<option>Ayah</option> 															<option>Ibu</option> 															<option>Nenek</option> 															<option>Kakek</option> 															<option>Adik</option> 															<option>Kakak</option> 															<option>Kakak perempuan ayah</option> 															<option>Adik perempuan ayah</option> 															<option>Kakak laki laki dari ayah</option> 															<option>Adik laki laki dari Ibu</option> 														</select> 													</div> 												</div> 												<div class="col-md-6"> 													<div class="form-group"> 														<label>Tanggal Lahir </label> 														<input class="form-control" type="date" name="tgl_lahirkeluarga[]"> 													</div> 												</div> 												<div class="col-md-6"> 													<div class="form-group"> 														<label>No Hp</label> 														<input class="form-control" type="number"  name="no_hpkeluarga[]"> 													</div> 												</div> 											</div> 										</div>');
	}
</script>
@endsection
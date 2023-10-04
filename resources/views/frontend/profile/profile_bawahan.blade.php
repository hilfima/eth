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
.experience-list > li {
	position: relative;
}
.experience-list > li:last-child .experience-content {
	margin-bottom: 0;
}
.experience-user .avatar {
	height: 32px;
	line-height: 32px;
	margin: 0;
	width: 32px;
}
.experience-list > li .experience-user {
	background: #fff;
	height: 10px;
	left: 4px;
	margin: 0;
	padding: 0;
	position: absolute;
	top: 4px;
	width: 10px;
}
.experience-list > li .experience-content {
	background-color: #fff;
	margin: 0 0 20px 40px;
	padding: 0;
	position: relative;
}
.experience-list > li .experience-content .timeline-content {
	color: #9e9e9e;
}
.experience-list > li .experience-content .timeline-content a.name {
	color: #616161;
	font-weight: bold;
}
.experience-list > li .time {
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
.skills > span {
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
}.profile-user-img{
	border-radius: 50%;
}.nav-tabs.nav-justified.nav-tabs-top {
		border-bottom: 1px solid #ddd;
	}
	.nav-tabs.nav-justified.nav-tabs-top > li > a,
	.nav-tabs.nav-justified.nav-tabs-top > li > a:hover,
	.nav-tabs.nav-justified.nav-tabs-top > li > a:focus {
		border-width: 2px 0 0 0;
	}
	.nav-tabs.nav-tabs-top > li {
		margin-bottom: 0;
	}
	.nav-tabs.nav-tabs-top > li > a,
	.nav-tabs.nav-tabs-top > li > a:hover,
	.nav-tabs.nav-tabs-top > li > a:focus {
		border-width: 2px 0 0 0;
	}
	.nav-tabs.nav-tabs-top > li.open > a,
	.nav-tabs.nav-tabs-top > li > a:hover,
	.nav-tabs.nav-tabs-top > li > a:focus {
		border-top-color: #ddd;
	}
	.nav-tabs.nav-tabs-top > li+li > a {
		margin-left: 1px;
	}
	.nav-tabs.nav-tabs-top > li > a.active,
	.nav-tabs.nav-tabs-top > li > a.active:hover,
	.nav-tabs.nav-tabs-top > li > a.active:focus {
		border-top-color: #667eea;
	}
	.nav-tabs.nav-tabs-bottom > li {
		margin-bottom: -1px;
	}
	.nav-tabs.nav-tabs-bottom > li > a.active, 
	.nav-tabs.nav-tabs-bottom > li > a.active:hover, 
	.nav-tabs.nav-tabs-bottom > li > a.active:focus {
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
	.nav-tabs.nav-tabs-solid > li {
		margin-bottom: 0;
	}
	.nav-tabs.nav-tabs-solid > li > a {
		border-color: transparent;
	}
	.nav-tabs.nav-tabs-solid > li > a:hover,
	.nav-tabs.nav-tabs-solid > li > a:focus {
		background-color: #f5f5f5;
	}
	.nav-tabs.nav-tabs-solid > .open:not(.active) > a {
		background-color: #f5f5f5;
		border-color: transparent;
	}
	.nav-tabs-justified.nav-tabs-top {
		border-bottom: 1px solid #ddd;
	}
	.nav-tabs-justified.nav-tabs-top > li > a,
	.nav-tabs-justified.nav-tabs-top > li > a:hover,
	.nav-tabs-justified.nav-tabs-top > li > a:focus {
		border-width: 2px 0 0 0;
	}
.nav {
 display:-ms-flexbox;
 display:flex;
 -ms-flex-wrap:wrap;
 flex-wrap:wrap;
 padding-left:0;
 margin-bottom:0;
 list-style:none
}
.nav-link {
 display:block;
 padding:.5rem 1rem
}
.nav-link:focus,
.nav-link:hover {
 text-decoration:none
}
.nav-link.disabled {
 color:#6c757d;
 pointer-events:none;
 cursor:default
}
.nav-tabs {
 border-bottom:1px solid #dee2e6
}
.nav-tabs .nav-item {
 margin-bottom:-1px
}
.nav-tabs .nav-link {
 border:1px solid transparent;
 border-top-left-radius:0;
 border-top-right-radius:0;
}
.nav-tabs .nav-link:focus,
.nav-tabs .nav-link:hover {
 border-color:#e9ecef #e9ecef #dee2e6
}.nav-tabs .nav-link:focus, .nav-tabs .nav-link:hover {
  color: #555 !important;
   border-bottom-left-radius:0;
 border-bottom-right-radius:0;
}
.nav-tabs .nav-link.disabled {
 color:#6c757d;
 background-color:transparent;
 border-color:transparent
}
.nav-tabs .nav-item.show .nav-link,
.nav-tabs .nav-link.active {
 color:#495057;
 background-color:#fff;
 border-color:#dee2e6 #dee2e6 #fff
}
.nav-tabs .dropdown-menu {
 margin-top:-1px;
 border-top-left-radius:0;
 border-top-right-radius:0
}
.nav-pills .nav-link {
 border-radius:.25rem
}
.nav-pills .nav-link.active,
.nav-pills .show>.nav-link {
 color:#fff;
 background-color:#007bff
}
.nav-fill .nav-item {
 -ms-flex:1 1 auto;
 flex:1 1 auto;
 text-align:center
}
.nav-justified .nav-item {
 -ms-flex-preferred-size:0;
 flex-basis:0;
 -ms-flex-positive:1;
 flex-grow:1;
 text-align:center
}
.tab-content>.tab-pane {
 display:none
}
.tab-content>.active {
 display:block
}
</style>
 <div class="content">

			
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
										<a href="#"> 
										@if($karyawan[0]->foto!=null)
										 @if (file_exists(asset('dist/img/profile/'.$karyawan[0]->foto))) {   
$filefound = '0';
}
                                        <img src="{!! asset('dist/img/profile/'.$karyawan[0]->foto) !!}" alt="User Avatar" class="profile-user-img img-fluid img-thumbnail">
                                    @else
                                        <img src="{!! asset('dist/img/profile/user.png') !!}" class="profile-user-img img-fluid img-circle" alt="User Image">
                                    @endif
                                    
                                    @else
                                        <img src="{!! asset('dist/img/profile/user.png') !!}" class="profile-user-img img-fluid img-circle" alt="User Image">
                                    @endif</a>
										</div>
									</div>
									<div class="profile-basic">
										<div class="row">
											<div class="col-md-5">
												<div class="profile-info-left">
													<div class="staff-id">{!! $karyawan[0]->nik !!}</div>
													<h3 class="user-name m-t-0 mb-0">{!! $karyawan[0]->nama_lengkap !!}</h3>
													<h6 class="text-bold mb-0 ">{!! $karyawan[0]->nmjabatan !!} </h6>
													<div class="text-muted">{!! $karyawan[0]->nmlokasi !!}<br>Departement {!! $karyawan[0]->nmdept !!}<br>{!! $karyawan[0]->nmdivisi !!}</div>
													<div class="small doj text-muted">Tanggal Bergabung : {!! $karyawan[0]->tgl_bergabung !!}</div>
													
												</div>
											</div>
											<div class="col-md-7">
												<ul class="personal-info">
													
													<li>
														<div class="title">No. Hp:</div>
														<div class="text">: {!! $karyawan[0]->no_hp !!}</div>
													</li><li>
														<div class="title">Email:</div>
														<div class="text">: {!! $karyawan[0]->email_perusahaan !!}</div>
													</li>
													<li>
														<div class="title">Tempat, Tanggal Lahir:</div>
														<div class="text">: {!! $karyawan[0]->tempat_lahir !!}, {!! $karyawan[0]->tgl_lahir !!}</div>
													</li>
													<li>
														<div class="title">Alamat Seusai KTP:</div>
														<div class="text">: {!! $karyawan[0]->alamat_ktp !!}</div>
													</li>
													<li>
														<div class="title">Jenis Kelamin:</div>
														<div class="text">: <?php
														 foreach($jk AS $jk2){
                                                                            if($jk2->m_jenis_kelamin_id==$karyawan[0]->m_jenis_kelamin_id){
                                                                                echo $jk2->nama;
                                                                            }
                                                                            }
														?></div>
													</li>
													
												</ul>
											</div>
										</div>
									</div>
									<div class="pro-edit"></div>
								</div>
							</div>
						</div>
					</div>
					
					<div class="card-box tab-box p-0">
			<div class="row user-tabs">
				<div class="col-lg-12 col-md-12 col-sm-12 line-tabs">
					<ul class="nav nav-tabs nav-tabs-bottom">
						<li class="nav-item"><a href="#emp_profile" data-toggle="tab" class="nav-link active">Data Pribadi</a></li>
						<li class="nav-item"><a href="#keluarga_projects" data-toggle="tab" class="nav-link">Data Keluarga</a></li>
						<li class="nav-item"><a href="#emp_projects" data-toggle="tab" class="nav-link">Data Pekerjaan</a></li>
						<li class="nav-item"><a href="#pendidikan" data-toggle="tab" class="nav-link">Informasi Pendidikan</a></li>
						<li class="nav-item"><a href="#data_lainnya" data-toggle="tab" class="nav-link">Data Lainnya</a></li>
								</ul>
				</div>
			</div>
		</div>

		<div class="tab-content">

			<!-- Profile Info Tab -->
			<div id="emp_profile" class="pro-overview tab-pane fade active show">
				<div class="row">
					<div class="col-md-6">
						<div class="card-box profile-box">
							<h3 class="card-title">Data Utama</h3>
							<ul class="personal-info">
								<li>
									<div class="title">NIK</div>
									<div class="text">: {!! $karyawan[0]->nik !!}</div>
								</li>
								<li>
									<div class="title">Nama Lengkap *</div>
									<div class="text">: {!! $karyawan[0]->nama_lengkap !!}</div>
								</li>
								<li>
									<div class="title">Nama Panggilan</div>
									<div class="text">: {!! $karyawan[0]->nama_panggilan !!}</div>
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

							</ul>
						</div>
					</div>


					<div class="col-md-6">
						<div class="card-box profile-box">
							<h3 class="card-title">Data Pernikahan</h3>

							<ul class="personal-info">
								<li>
									<div class="title">Status Pernikahan</div>
									<div class="text">: <?php
														if ($karyawan[0]->m_status_id == 0) {
															echo 'Belum Menikah ';
														} else if ($karyawan[0]->m_status_id == 1) {
															echo 'Menikah ';
														} ?>
									</div>
								</li>
								<li>
									<div class="title">Jumlah Anak</div>
									<div class="text">: {!! $karyawan[0]->jumlah_anak !!}</div>
								</li>

							</ul>


						</div>
					</div>
				</div>

			</div>

			<!-- /Profile Info Tab -->

			<!-- Projects Tab -->
			<div class="tab-pane fade" id="keluarga_projects">
				<div class="card-box profile-box">
					<h3 class="card-title text-center">Data Keluarga</h3>

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
								<td class="text"><a href="<?= route('fe.hapus_keluarga', $keluarga2->p_karyawan_keluarga_id); ?>" title="" data-toggle="tooltip" data-original-title="Hapus"><span class="fa fa-trash"></span></a></td>
							</tr>
						<?php } ?>


					</table>
				</div>
			</div>
			<div class="tab-pane fade" id="pendidikan">
				<!--<div class="card-box profile-box">
					</h3>

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
								<td class="text"><a href="<?= route('fe.hapus_pendidikan', $pendidikan->p_karyawan_pendidikan_id); ?>" title="" data-toggle="tooltip" data-original-title="Hapus"><span class="fa fa-trash"></span></a></td>

							</tr>
						<?php } ?>


					</table>
				</div>
				<div class="card-box profile-box">
					<h3 class="card-title text-center">Data Kursus dan Pelatihan
						
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
								<td class="text"><a href="<?= route('fe.hapus_kursus', $kursus->p_karyawan_kursus_id); ?>" title="" data-toggle="tooltip" data-original-title="Hapus"><span class="fa fa-trash"></span></a></td>
							</tr>
						<?php } ?>


					</table>
				</div>
			</div>

			<div class="tab-pane fade" id="emp_projects">
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
							<h3 class="card-title">Data Pengalaman Kerja</h3>

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
										<td><a href="<?= route('fe.hapus_pekerjaan', $pekerjaan->p_karyawan_riwayat_pekerjaan_id); ?>"><i class="fa fa-trash"></i></a></td>
									</tr>
								<?php } ?>


							</table>

						
						</div>
					</div>

				</div>
			</div>
			<!-- /Projects Tab -->

			<!-- Bank Statutory Tab -->
			<div class="tab-pane fade" id="data_lainnya">
				<div class="card-box profile-box">
					<h3 class="card-title">Data Pakaian</h3>

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
						foreach ($datapakaiankeluarga as $pakaiank) { ?>
							<tr>
								<td><?= ucwords($pakaiank->nama) ?> (<?= $pakaiank->hubungan ?>)</td>
								<td class="text"> <?= $pakaiank->gamis ?></td>
								<td class="text"><?= $pakaiank->kemeja ?></td>
								<td class="text"><?= $pakaiank->kaos ?></td>
								<td class="text"><?= $pakaiank->jaket ?></td>
								<td class="text"><?= $pakaiank->celana ?></td>
								<td class="text"><?= $pakaiank->sepatu ?></td>
								<td><a href="<?= route('fe.hapus_pakaian', $pakaiank->p_karyawan_pakaian_id); ?>"><i class="fa fa-trash"></i></a></td>
							</tr>
						<?php } ?>


					</table>

					
				</div>
				<div class="card-box profile-box">
					<h3 class="card-title">Data Kandidat
						<a href="#" class="edit-icon" style="background-color: #007bff;border-color: #007bff;border-bottom: 5px solid #007bff;" data-toggle="modal" data-target="#datapernikahan">
							<i></i></a></h3>
							<?php 
							if(isset($cv[0])){
							$cv = $cv[0]?>
							<?php if(!empty($cv->file)){?>
							CV:
							<a href="{!! asset('dist/img/file/'.$cv->file) !!}" target="_blank" title="Download"><span class="fa fa-download"></span></a>
							<br>
							<?php }?>
							
							<?php if(!empty($cv->file)){?>
							
Form :
<a href="{!! route('be.list_database_kandidat',$cv->t_karyawan_id) !!}" target="_blank" title="Download"><span class="fa fa-search"></span></a>
<br>
<?php }?>
<?php if(!empty($cv->file_interview1)){?>
Hasil Interview 1 :
<a href="{!! asset('dist/img/file/'.$cv->file_interview1) !!}" target="_blank" title="Download"><span class="fa fa-download"></span></a>
<br>

<?php }?>
<?php if(!empty($cv->file_interview2)){?>
Hasil Interview 2 :
<a href="{!! asset('dist/img/file/'.$cv->file_interview2) !!}" target="_blank" title="Download"><span class="fa fa-download"></span></a>
<br>

<?php }?>
<?php if(!empty($cv->file_psikogram)){?>
Hasil Psikogram :
<a href="{!! asset('dist/img/file/'.$cv->file_psikogram) !!}" target="_blank" title="Download"><span class="fa fa-download"></span></a>

<?php }?>
<?php }?>

				</div>
			</div>
			<div class="tab-pane fade" id="bank_statutory">
				<div class="card-box profile-box">
					<h3 class="card-title">Data Bank
						
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
			</div>
			<!-- /Bank Statutory Tab -->

		</div>
	 </div>
				<!-- /Page Content -->
				
				<!-- Profile Modal -->
				<div id="profile_info" class="modal custom-modal fade" role="dialog">
					<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title">Profile Information</h5>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">×</span>
								</button>
							</div>
							<div class="modal-body">
								<form>
									<div class="row">
										<div class="col-md-12">
											<div class="profile-img-wrap edit-img">
												<img class="inline-block" src="assets/img/profiles/avatar-02.jpg" alt="user">
												<div class="fileupload btn">
													<span class="btn-text">edit</span>
													<input class="upload" type="file">
												</div>
											</div>
											<div class="row">
												<div class="col-md-6">
													<div class="form-group">
														<label>First Name</label>
														<input type="text" class="form-control" value="John">
													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group">
														<label>Last Name</label>
														<input type="text" class="form-control" value="Doe">
													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group">
														<label>Birth Date</label>
														<div class="cal-icon">
															<input class="form-control datetimepicker" type="text" value="05/06/1985">
														</div>
													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group">
														<label>Gender</label>
														<select class="select form-control select2-hidden-accessible" data-select2-id="37" tabindex="-1" aria-hidden="true">
															<option value="male selected" data-select2-id="39">Male</option>
															<option value="female">Female</option>
														</select><span class="select2 select2-container select2-container--default" dir="ltr" data-select2-id="38" style="width: 100%;"><span class="selection"><span class="select2-selection select2-selection--single" role="combobox" aria-haspopup="true" aria-expanded="false" tabindex="0" aria-labelledby="select2-x3fx-container"><span class="select2-selection__rendered" id="select2-x3fx-container" role="textbox" aria-readonly="true" title="Male">Male</span><span class="select2-selection__arrow" role="presentation"><b role="presentation"></b></span></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-12">
											<div class="form-group">
												<label>Address</label>
												<input type="text" class="form-control" value="4487 Snowbird Lane">
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label>State</label>
												<input type="text" class="form-control" value="New York">
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label>Country</label>
												<input type="text" class="form-control" value="United States">
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label>Pin Code</label>
												<input type="text" class="form-control" value="10523">
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label>Phone Number</label>
												<input type="text" class="form-control" value="631-889-3206">
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label>Department <span class="text-danger">*</span></label>
												<select class="select select2-hidden-accessible" data-select2-id="40" tabindex="-1" aria-hidden="true">
													<option data-select2-id="42">Select Department</option>
													<option>Web Development</option>
													<option>IT Management</option>
													<option>Marketing</option>
												</select><span class="select2 select2-container select2-container--default" dir="ltr" data-select2-id="41" style="width: 100%;"><span class="selection"><span class="select2-selection select2-selection--single" role="combobox" aria-haspopup="true" aria-expanded="false" tabindex="0" aria-labelledby="select2-rw66-container"><span class="select2-selection__rendered" id="select2-rw66-container" role="textbox" aria-readonly="true" title="Select Department">Select Department</span><span class="select2-selection__arrow" role="presentation"><b role="presentation"></b></span></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span>
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label>Designation <span class="text-danger">*</span></label>
												<select class="select select2-hidden-accessible" data-select2-id="43" tabindex="-1" aria-hidden="true">
													<option data-select2-id="45">Select Designation</option>
													<option>Web Designer</option>
													<option>Web Developer</option>
													<option>Android Developer</option>
												</select><span class="select2 select2-container select2-container--default" dir="ltr" data-select2-id="44" style="width: 100%;"><span class="selection"><span class="select2-selection select2-selection--single" role="combobox" aria-haspopup="true" aria-expanded="false" tabindex="0" aria-labelledby="select2-7ccw-container"><span class="select2-selection__rendered" id="select2-7ccw-container" role="textbox" aria-readonly="true" title="Select Designation">Select Designation</span><span class="select2-selection__arrow" role="presentation"><b role="presentation"></b></span></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span>
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label>Reports To <span class="text-danger">*</span></label>
												<select class="select select2-hidden-accessible" data-select2-id="46" tabindex="-1" aria-hidden="true">
													<option data-select2-id="48">-</option>
													<option>Wilmer Deluna</option>
													<option>Lesley Grauer</option>
													<option>Jeffery Lalor</option>
												</select><span class="select2 select2-container select2-container--default" dir="ltr" data-select2-id="47" style="width: 100%;"><span class="selection"><span class="select2-selection select2-selection--single" role="combobox" aria-haspopup="true" aria-expanded="false" tabindex="0" aria-labelledby="select2-q2br-container"><span class="select2-selection__rendered" id="select2-q2br-container" role="textbox" aria-readonly="true" title="-">-</span><span class="select2-selection__arrow" role="presentation"><b role="presentation"></b></span></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span>
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
				<!-- /Profile Modal -->
				
				<!-- Personal Info Modal -->
				<div id="personal_info_modal" class="modal custom-modal fade" role="dialog" style="display: none;" aria-hidden="true">
					<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title">Personal Information</h5>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">×</span>
								</button>
							</div>
							<div class="modal-body">
								<form>
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<label>Passport No</label>
												<input type="text" class="form-control">
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label>Passport Expiry Date</label>
												<div class="cal-icon">
													<input class="form-control datetimepicker" type="text">
												</div>
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label>Tel</label>
												<input class="form-control" type="text">
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label>Nationality <span class="text-danger">*</span></label>
												<input class="form-control" type="text">
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label>Religion</label>
												<div class="cal-icon">
													<input class="form-control" type="text">
												</div>
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label>Marital status <span class="text-danger">*</span></label>
												<select class="select form-control select2-hidden-accessible" data-select2-id="49" tabindex="-1" aria-hidden="true">
													<option data-select2-id="51">-</option>
													<option>Single</option>
													<option>Married</option>
												</select><span class="select2 select2-container select2-container--default" dir="ltr" data-select2-id="50" style="width: 100%;"><span class="selection"><span class="select2-selection select2-selection--single" role="combobox" aria-haspopup="true" aria-expanded="false" tabindex="0" aria-labelledby="select2-ji29-container"><span class="select2-selection__rendered" id="select2-ji29-container" role="textbox" aria-readonly="true" title="-">-</span><span class="select2-selection__arrow" role="presentation"><b role="presentation"></b></span></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span>
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label>Employment of spouse</label>
												<input class="form-control" type="text">
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label>No. of children </label>
												<input class="form-control" type="text">
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
				<!-- /Personal Info Modal -->
				
				<!-- Family Info Modal -->
				<div id="family_info_modal" class="modal custom-modal fade" role="dialog">
					<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title"> Family Informations</h5>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">×</span>
								</button>
							</div>
							<div class="modal-body">
								<form>
									<div class="form-scroll">
										<div class="card-box">
											<h3 class="card-title">Family Member <a href="javascript:void(0);" class="delete-icon"><i class="fa fa-trash-o"></i></a></h3>
											<div class="row">
												<div class="col-md-6">
													<div class="form-group">
														<label>Name <span class="text-danger">*</span></label>
														<input class="form-control" type="text">
													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group">
														<label>Relationship <span class="text-danger">*</span></label>
														<input class="form-control" type="text">
													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group">
														<label>Date of birth <span class="text-danger">*</span></label>
														<input class="form-control" type="text">
													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group">
														<label>Phone <span class="text-danger">*</span></label>
														<input class="form-control" type="text">
													</div>
												</div>
											</div>
										</div>
										<div class="card-box">
											<h3 class="card-title">Education Informations <a href="javascript:void(0);" class="delete-icon"><i class="fa fa-trash-o"></i></a></h3>
											<div class="row">
												<div class="col-md-6">
													<div class="form-group">
														<label>Name <span class="text-danger">*</span></label>
														<input class="form-control" type="text">
													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group">
														<label>Relationship <span class="text-danger">*</span></label>
														<input class="form-control" type="text">
													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group">
														<label>Date of birth <span class="text-danger">*</span></label>
														<input class="form-control" type="text">
													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group">
														<label>Phone <span class="text-danger">*</span></label>
														<input class="form-control" type="text">
													</div>
												</div>
											</div>
											<div class="add-more">
												<a href="javascript:void(0);"><i class="fa fa-plus-circle"></i> Add More</a>
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
				<div id="emergency_contact_modal" class="modal custom-modal fade" role="dialog" style="display: none;" aria-hidden="true">
					<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title">Personal Information</h5>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">×</span>
								</button>
							</div>
							<div class="modal-body">
								<form>
									<div class="card-box">
										<h3 class="card-title">Primary Contact</h3>
										<div class="row">
											<div class="col-md-6">
												<div class="form-group">
													<label>Name <span class="text-danger">*</span></label>
													<input type="text" class="form-control">
												</div>
											</div>
											<div class="col-md-6">
												<div class="form-group">
													<label>Relationship <span class="text-danger">*</span></label>
													<input class="form-control" type="text">
												</div>
											</div>
											<div class="col-md-6">
												<div class="form-group">
													<label>Phone <span class="text-danger">*</span></label>
													<input class="form-control" type="text">
												</div>
											</div>
											<div class="col-md-6">
												<div class="form-group">
													<label>Phone 2</label>
													<input class="form-control" type="text">
												</div>
											</div>
										</div>
									</div>
									<div class="card-box">
										<h3 class="card-title">Primary Contact</h3>
										<div class="row">
											<div class="col-md-6">
												<div class="form-group">
													<label>Name <span class="text-danger">*</span></label>
													<input type="text" class="form-control">
												</div>
											</div>
											<div class="col-md-6">
												<div class="form-group">
													<label>Relationship <span class="text-danger">*</span></label>
													<input class="form-control" type="text">
												</div>
											</div>
											<div class="col-md-6">
												<div class="form-group">
													<label>Phone <span class="text-danger">*</span></label>
													<input class="form-control" type="text">
												</div>
											</div>
											<div class="col-md-6">
												<div class="form-group">
													<label>Phone 2</label>
													<input class="form-control" type="text">
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
											<h3 class="card-title">Education Informations <a href="javascript:void(0);" class="delete-icon"><i class="fa fa-trash-o"></i></a></h3>
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
											<h3 class="card-title">Education Informations <a href="javascript:void(0);" class="delete-icon"><i class="fa fa-trash-o"></i></a></h3>
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
												<a href="javascript:void(0);"><i class="fa fa-plus-circle"></i> Add More</a>
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
											<h3 class="card-title">Experience Informations <a href="javascript:void(0);" class="delete-icon"><i class="fa fa-trash-o"></i></a></h3>
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
											<h3 class="card-title">Experience Informations <a href="javascript:void(0);" class="delete-icon"><i class="fa fa-trash-o"></i></a></h3>
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
												<a href="javascript:void(0);"><i class="fa fa-plus-circle"></i> Add More</a>
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
@endsection
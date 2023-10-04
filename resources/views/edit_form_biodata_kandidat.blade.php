<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
	<meta name="description" content="Smarthr - Bootstrap Admin Template">
	<meta name="keywords" content="admin, estimates, bootstrap, business, corporate, creative, management, minimal, modern, accounts, invoice, html5, responsive, CRM, Projects">
	<meta name="author" content="Dreamguys - Bootstrap Admin Template">
	<meta name="robots" content="noindex, nofollow">
	<title>FORM BIODATA KANDIDAT - HCMS ETHICS GROUP</title>

	<!-- Favicon -->
	<link rel="shortcut icon" type="image/x-icon" href="assets/img/favicon.png">

	<!-- Bootstrap CSS -->
	<link rel="stylesheet" href="<?= url('plugins/purple/assets/css/bootstrap.min.css') ?>">

	<!-- Fontawesome CSS -->
	<link rel="stylesheet" href="<?= url('plugins/purple/assets/css/font-awesome.min.css') ?>">

	<!-- Main CSS -->
	<link rel="stylesheet" href="<?= url('plugins/purple/assets/css/style.css') ?>">

	<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
	<!--[if lt IE 9]>
	<script src="assets/js/html5shiv.min.js"></script>
	<script src="assets/js/respond.min.js"></script>
	<![endif]-->
	<style>
		.wpforms-field-label{
			margin-top: 10px
		}
	</style>
</head>
<body class="account-page">

<!-- Main Wrapper -->
<div class="main-wrapper">
	<div class="account-content">
		<!-- Account Logo
		<a href="job-list.html" class="btn btn-primary apply-btn">Apply Job</a>
		-->
		<div class="container">


			<div class="account-logo" style="margin-bottom: 0;">
				<!-- Account Logo -->
				<img src="<?= asset('/dist/img/logo/logo.png'); ?>" style="height:auto; width: 173px;">
			</div>
			<div data-elementor-type="wp-page" data-elementor-id="1893" class="elementor elementor-1893">

				<section class="elementor-section elementor-top-section elementor-element elementor-element-11b1f2b elementor-section-boxed elementor-section-height-default elementor-section-height-default" data-id="11b1f2b" data-element_type="section">
				<?php //print_r($p_recruitment);die;?>
				<div class="container">
					<div class="card" id="wpforms-1892">
						<div class="card-body" id="wpforms-1892">
							<h3 class="text-center">Form Biodata Kandidat</h3>
							<form id="wpforms-form-1892" class="wpforms-validate wpforms-form" data-formid="1892" method="post" enctype="multipart/form-data" action="<?=route('save_form_biodata_kandidat') ?>" data-token="77bac59dbcc9ff92c27e95052f0f5a26" novalidate="novalidate">
							{{ csrf_field() }}
							<noscript class="wpforms-error-noscript">Please enable JavaScript in your browser to complete this form.</noscript>
							<div class="wpforms-field-container">
								<div id="wpforms-1892-field_116-container" class="wpforms-field wpforms-field-text" data-field-id="116">
									<label class="wpforms-field-label" for="wpforms-1892-field_116">Kode<span class="wpforms-required-label">*</span></label>
									<input <?=$type?> type="text" id="wpforms-1892-field_116" class="form-control form-control " placeholder="Kode" name="kode" required="" value="<?= $p_recruitment[0]->kode_kandidat; ?>">
								</div>
								<div id="wpforms-1892-field_116-container" class="wpforms-field wpforms-field-text" data-field-id="116">
									<label class="wpforms-field-label" for="wpforms-1892-field_116">Jabatan yang dituju <span class="wpforms-required-label">*</span></label>
									<input <?=$type?> type="text" id="wpforms-1892-field_116" class="form-control form-control " placeholder="Jabatan yang dituju" name="jabatan_yang_dituju" required="" value="<?= $p_recruitment[0]->jabatan_yang_dituju; ?>">
								</div>

								<div id="wpforms-1892-field_14-container" class="wpforms-field wpforms-field-divider" data-field-id="14">
									<h3 id="wpforms-1892-field_14" name="wpforms[fields][14]">Data Diri</h3>
								</div>
								<div id="wpforms-1892-field_3-container" class="wpforms-field wpforms-field-name" data-field-id="3">
									<label class="wpforms-field-label" for="wpforms-1892-field_3">Nama Lengkap <span class="wpforms-required-label">*</span></label>
									<input <?=$type?> type="text" id="wpforms-1892-field_3" class="form-control form-control " placeholder="Nama Lengkap" name="nama_lengkap" required="" value="<?= $p_recruitment[0]->nama_lengkap; ?>">
								</div>
								<div id="wpforms-1892-field_3-container" class="wpforms-field wpforms-field-name" data-field-id="3">
									<label class="wpforms-field-label" for="wpforms-1892-field_3">Nama Panggilan <span class="wpforms-required-label">*</span></label>
									<input <?=$type?> type="text" id="wpforms-1892-field_3" class="form-control form-control " placeholder="Nama Panggilan" name="nama_panggilan" required="" value="<?= $p_recruitment[0]->nama_panggilan; ?>">
								</div>
								<div id="wpforms-1892-field_3-container" class="wpforms-field wpforms-field-name" data-field-id="3">
									<label class="wpforms-field-label" for="wpforms-1892-field_3">Tempat Lahir<span class="wpforms-required-label">*</span></label>
									<input <?=$type?> type="text" id="wpforms-1892-field_3" class="form-control form-control " placeholder="Tempat Lahir" name="tempat_lahir" required="" value="<?= $p_recruitment[0]->tempat_lahir; ?>">
								</div>
								<div id="wpforms-1892-field_5-container" class="wpforms-field wpforms-field-date-time" data-field-id="5">
									<label class="wpforms-field-label" for="wpforms-1892-field_5">Tanggal Lahir <span class="wpforms-required-label">*</span></label>
									<input <?=$type?> type="date" name="tgl_lahir"  class="wpforms-field-date-time-date-month form-control " required="" value="<?= $p_recruitment[0]->tgl_lahir; ?>">
								</div>
							</div>
							<div id="wpforms-1892-field_6-container" class="wpforms-field wpforms-field-select wpforms-field-select-style-classic" data-field-id="6">
								<label class="wpforms-field-label" for="wpforms-1892-field_6">Jenis Kelamin <span class="wpforms-required-label">*</span></label>
								<select <?=$type?> id="wpforms-1892-field_6" class="form-control form-control " name="jenis_kelamin" required="required">
									<option value="">- Pilih Jenis Kelamin -</option>
									<option value="1" <?= $p_recruitment[0]->m_jenis_kelamin_id ==1?'selected':''; ?>>Laki-laki</option>
									<option value="2" <?= $p_recruitment[0]->m_jenis_kelamin_id ==2?'selected':''; ?>>Perempuan</option></select>
							</div>
							<div id="wpforms-1892-field_7-container" class="wpforms-field wpforms-field-text" data-field-id="7">
								<label class="wpforms-field-label" for="wpforms-1892-field_7">Agama <span class="wpforms-required-label">*</span></label>
								<select <?=$type?> id="wpforms-1892-field_6" class="form-control form-control " name="agama" required="required">
									<option value="">- Pilih Agama -</option>
									<?php

									foreach ($agama as $agama) {
									?>
									<option value="<?=$agama->m_agama_id ?>" <?= $p_recruitment[0]->m_agama_id ==$agama->m_agama_id?'selected':''; ?>><?=$agama->nama ?></option>
									<?php
									} ?></select>



							</div>
							<div id="wpforms-1892-field_112-container" class="wpforms-field wpforms-field-textarea" data-field-id="112">
								<label class="wpforms-field-label" for="wpforms-1892-field_112">No KTP<span class="wpforms-required-label">*</span></label>
								<input <?=$type?> id="wpforms-1892-field_112" class="form-control form-control " name="no_ktp" placeholder="No KTP" required="" value="<?= $p_recruitment[0]->no_ktp; ?>">
							</div>
							<div id="wpforms-1892-field_112-container" class="wpforms-field wpforms-field-textarea" data-field-id="112">
								<label class="wpforms-field-label" for="wpforms-1892-field_112">Alamat KTP <span class="wpforms-required-label">*</span></label>
								<textarea <?=$type?> id="wpforms-1892-field_112" class="form-control form-control " name="alamat_ktp" placeholder="Alamat Lengkap" required=""><?= $p_recruitment[0]->alamat_ktp; ?></textarea>
							</div>
							<div id="wpforms-1892-field_7-container" class="wpforms-field wpforms-field-text" data-field-id="7">
								<label class="wpforms-field-label" for="wpforms-1892-field_7">Kota KTP<span class="wpforms-required-label">*</span></label>
								<select <?=$type?> id="wpforms-1892-field_6" class="form-control  select2 " name="m_kota_id" required="required">
									<option value="">- Pilih Kota KTP-</option>
									<?php
									foreach ($m_kota as $kota) {
									?>
									<option value="<?=$kota->m_kota_id ?>" <?= $p_recruitment[0]->m_kota_id ==$kota->m_kota_id?'selected':''; ?>><?=$kota->nama ?></option>
									<?php
									} ?></select>



							</div>
							<div id="wpforms-1892-field_112-container" class="wpforms-field wpforms-field-textarea" data-field-id="112">
								<label class="wpforms-field-label" for="wpforms-1892-field_112">Alamat Tinggal <span class="wpforms-required-label">*</span></label>
								<textarea <?=$type?> id="wpforms-1892-field_112" class="form-control form-control " name="alamat_tinggal" placeholder="Alamat Lengkap" required=""><?= $p_recruitment[0]->alamat_tinggal; ?></textarea>
							</div>
							<div id="wpforms-1892-field_7-container" class="wpforms-field wpforms-field-text" data-field-id="7">
								<label class="wpforms-field-label" for="wpforms-1892-field_7">Kota Tinggal<span class="wpforms-required-label">*</span></label>
								<select <?=$type?> id="wpforms-1892-field_6" class="form-control select2 " name="m_kota_asal_id" required="required">
									<option value="">- Pilih Kota Tinggal-</option>
									<?php
									foreach ($m_kota as $kota) {
									?>
									<option value="<?=$kota->m_kota_id ?>" <?= $p_recruitment[0]->m_kota_id_asal ==$kota->m_kota_id?'selected':''?>><?=$kota->nama ?></option>
									<?php
									} ?></select>



							</div>
							<div id="wpforms-1892-field_11-container" class="wpforms-field wpforms-field-text" data-field-id="11">
								<label class="wpforms-field-label" for="wpforms-1892-field_11">Nomor Handphone <span class="wpforms-required-label">*</span></label>
								<input <?=$type?> type="text" id="wpforms-1892-field_11" class="form-control form-control " name="no_hp" required="" placeholder="Nomor Handphone" value="<?= $p_recruitment[0]->no_hp; ?>">
							</div>
							<div id="wpforms-1892-field_12-container" class="wpforms-field wpforms-field-email" data-field-id="12">
								<label class="wpforms-field-label" for="wpforms-1892-field_12">Alamat Email <span class="wpforms-required-label">*</span></label>
								<input <?=$type?> type="email" id="wpforms-1892-field_12" class="form-control form-control " name="alamat_email" required="" placeholder="Alamat Email" value="<?= $p_recruitment[0]->email; ?>">

							</div>
							<div id="wpforms-1892-field_8-container" class="wpforms-field wpforms-field-select wpforms-field-select-style-classic" data-field-id="8">
								<label class="wpforms-field-label" for="wpforms-1892-field_8">Golongan Darah</label>
								<input <?=$type?> type="text" id="wpforms-1892-field_12" class="form-control form-control " name="golongan_darah" required="" placeholder="Golongan Darah" value="<?= $p_recruitment[0]->golongan_darah; ?>">

							</div>
							<div id="wpforms-1892-field_8-container" class="wpforms-field wpforms-field-select wpforms-field-select-style-classic" data-field-id="8">
								<label class="wpforms-field-label" for="wpforms-1892-field_8">Pendidikan Terakhir</label>
								<select <?=$type?> id="wpforms-1892-field_16" class="form-control " name="pendidikan" required="">
									<option value="">- Jenjang pendidikan Terakhir -</option>
									<option value="SD" <?= $p_recruitment[0]->pendidikan =='SD'?'selected':''; ?>>SD</option>
									<option value="SMP" <?= $p_recruitment[0]->pendidikan =='SMP'?'selected':''; ?>>SMP</option>
									<option value="SMA" <?= $p_recruitment[0]->pendidikan =='SMA'?'selected':''; ?>>SMA</option>
									<option value="S1" <?= $p_recruitment[0]->pendidikan =='S1'?'selected':''; ?>>S1</option>
									<option value="S2" <?= $p_recruitment[0]->pendidikan =='S2'?'selected':''; ?>>S2</option>
									<option value="S3" <?= $p_recruitment[0]->pendidikan =='S3'?'selected':''; ?>>S3</option></select>

							</div>
							<div id="wpforms-1892-field_8-container" class="wpforms-field wpforms-field-select wpforms-field-select-style-classic" data-field-id="8">
								<label class="wpforms-field-label" for="wpforms-1892-field_8">Nama Sekolah Terakhir</label>
								<input <?=$type?> type="text" id="wpforms-1892-field_12" class="form-control form-control " name="nama_sekolah" required="" placeholder="Nama Sekolah" value="<?= $p_recruitment[0]->nama_sekolah; ?>">

							</div>
							<div id="wpforms-1892-field_8-container" class="wpforms-field wpforms-field-select wpforms-field-select-style-classic" data-field-id="8">
								<label class="wpforms-field-label" for="wpforms-1892-field_8">Jurusan  Terakhir</label>
								<input <?=$type?> type="text" id="wpforms-1892-field_12" class="form-control form-control " name="jurusan_terakhir" required="" placeholder="Jurusan" value="<?= $p_recruitment[0]->jurusan; ?>">

							</div>
							<div id="wpforms-1892-field_9-container" class="wpforms-field wpforms-field-select wpforms-conditional-trigger wpforms-field-select-style-classic" data-field-id="9">
								<label class="wpforms-field-label" for="wpforms-1892-field_9">Status Perkawinan <span class="wpforms-required-label">*</span></label>
								<select <?=$type?> id="wpforms-1892-field_9" class="form-control form-control " name="status_pernikahan" required="required">
									<option value="">- Status Perkawinan -</option>
									<?php
									foreach ($m_status as $status) {
									?>
									<option value="<?=$status->m_status_id ?>" <?= $p_recruitment[0]->m_status_id ==$status->m_status_id?'selected':'' ?>><?=$status->nama ?></option>
									<?php
									} ?>
								</select>
							</div>
							<div id="wpforms-1892-field_10-container" class="wpforms-field wpforms-field-select wpforms-conditional-field wpforms-field-select-style-classic wpforms-conditional-hide" data-field-id="10" style="display:none;">
								<label class="wpforms-field-label" for="wpforms-1892-field_10">Jumlah Anak</label>
								<select <?=$type?> id="wpforms-1892-field_10" class="form-control " name="jml_anak" data-default-value="0">
									<option value="">- Jumlah Anak -</option>
									<option value="0" <?= $p_recruitment[0]->m_jenis_kelamin_id ==0?'selected':''; ?>>0</option>
									<option value="1" <?= $p_recruitment[0]->m_jenis_kelamin_id ==1?'selected':''; ?>>1</option>
									<option value="2" <?= $p_recruitment[0]->m_jenis_kelamin_id ==2?'selected':''; ?>>2</option>
									<option value="3" <?= $p_recruitment[0]->m_jenis_kelamin_id ==3?'selected':''; ?>>3</option>
									<option value="4" <?= $p_recruitment[0]->m_jenis_kelamin_id ==4?'selected':''; ?>>4</option>
									<option value="5" <?= $p_recruitment[0]->m_jenis_kelamin_id ==5?'selected':''; ?>>5</option>
									<option value="6" <?= $p_recruitment[0]->m_jenis_kelamin_id ==6?'selected':''; ?>>6</option>
									<option value="7" <?= $p_recruitment[0]->m_jenis_kelamin_id ==7?'selected':''; ?>>7</option>
									<option value="8" <?= $p_recruitment[0]->m_jenis_kelamin_id ==8?'selected':''; ?>>8</option>
									<option value="9" <?= $p_recruitment[0]->m_jenis_kelamin_id ==9?'selected':''; ?>>9</option>
									<option value="10" <?= $p_recruitment[0]->m_jenis_kelamin_id ==10?'selected':''; ?>>10</option>
									<option value="11" <?= $p_recruitment[0]->m_jenis_kelamin_id ==11?'selected':''; ?>>11</option>

								</select>
							</div>
							<div id="wpforms-1892-field_115-container" class="wpforms-field wpforms-field-file-upload" data-field-id="115">
								<label class="wpforms-field-label" for="wpforms-1892-field_115">Foto Diri (Terbaru) <span class="wpforms-required-label">*</span></label>
								<input <?=$type?> type="file" class="dropzone-input form-control" style="" id="wpforms-1892-field_115" name="foto" required="" value="<?= $p_recruitment[0]->nama_panggilan; ?>">
							</div>


							<?php

							foreach($p_recruitment_pendidikan as $data){?>
							<div id="wpforms-1892-field_13-container" class="wpforms-field wpforms-field-divider" data-field-id="13">
								<h3 id="wpforms-1892-field_13" name="wpforms[fields][13]">Pendidikan</h3></div>
							<div id="wpforms-1892-field_16-container" class="wpforms-field wpforms-field-select wpforms-field-select-style-classic" data-field-id="16">
								<label class="wpforms-field-label" for="wpforms-1892-field_16">Jenjang Pendidikan </label>
								<select <?=$type?> id="wpforms-1892-field_16" class="form-control " name="jenjang[]" required="">
									<option value="">- Jenjang pendidikan  -</option>
									<option value="SD" <?=$data->jenjang=='SD'?'selected':''?>>SD</option>
									<option value="SMP" <?=$data->jenjang=='SMP'?'selected':''?>>SMP</option>
									<option value="SMA"  <?=$data->jenjang=='SMA'?'selected':''?>>SMA</option>
									<option value="S1"  <?=$data->jenjang=='S1'?'selected':''?>>S1</option>
									<option value="S2"  <?=$data->jenjang=='S2'?'selected':''?>>S2</option>
									<option value="S3"  <?=$data->jenjang=='S3'?'selected':''?>>S3</option></select></div>
							<div id="wpforms-1892-field_15-container" class="wpforms-field wpforms-field-text" data-field-id="15">
								<label class="wpforms-field-label" for="wpforms-1892-field_15">Nama Sekolah Pendidikan </label>
								<input <?=$type?> type="text" id="wpforms-1892-field_15" class="form-control " name="nama_sekolah_pendidikan[]" placeholder="Nama Sekolah Pendidikan " required="" value="<?= $data->nama_sekolah; ?>">
							</div>
							<div id="wpforms-1892-field_17-container" class="wpforms-field wpforms-field-text" data-field-id="17">
								<label class="wpforms-field-label" for="wpforms-1892-field_17">Fakultas/Jurusan</label>
								<input <?=$type?> type="text" id="wpforms-1892-field_17" class="form-control " name="jurusan[]" placeholder="Falkultas/Jurusan" required="" value="<?= $data->jurusan; ?>">
							</div>
							<div id="wpforms-1892-field_18-container" class="wpforms-field wpforms-field-text" data-field-id="18">
								<label class="wpforms-field-label" for="wpforms-1892-field_18">Alamat Sekolah</label>
								<textarea <?=$type?> type="text" id="wpforms-1892-field_18" class="form-control " name="alamat_sekolah[]" placeholder="Alamat Sekolah" required="" ><?=$data->alamat_sekolah;?></textarea>

							</div>
							<div id="wpforms-1892-field_18-container" class="wpforms-field wpforms-field-text" data-field-id="18">
								<label class="wpforms-field-label" for="wpforms-1892-field_18">Kota Sekolah</label>
								<input <?=$type?> type="text" id="wpforms-1892-field_18" class="form-control " name="kotapendidikan[]" placeholder="Kota Sekolah" required="" value="<?= $data->kota_sekolah; ?>">

							</div>
							<div id="wpforms-1892-field_18-container" class="wpforms-field wpforms-field-text" data-field-id="18">
								<label class="wpforms-field-label" for="wpforms-1892-field_18">Nilai IPK</label>
								<input <?=$type?> type="text" id="wpforms-1892-field_18" class="form-control " name="nilai[]" placeholder="IPK" required="" value="<?= $data->nilai; ?>">

							</div>
							<div id="wpforms-1892-field_18-container" class="wpforms-field wpforms-field-text" data-field-id="18">
								<label class="wpforms-field-label" for="wpforms-1892-field_18">Tahun Lulus</label>
								<input <?=$type?> type="text" id="wpforms-1892-field_18" class="form-control " name="Tahunlulus[]" placeholder="Tahun Lulus" required="" value="<?= $data->tahun_lulus; ?>"></div>
							<?php }?>
							<?php if($type==''){?>
							<div id="content_pendidikan"></div>
							<button type="button" class="btn btn-primary mt-3 mb-3" onclick="tambah_pendidikan()">
								<i class="fa fa-plus"></i> Tambah Pendidikan</button>
							<?php }?>
							<script>
								function tambah_pendidikan()
								{
									$('#content_pendidikan').append('<h3 id="wpforms-1892-field_13" name="wpforms[fields][13]">Pendidikan</h3></div><div id="wpforms-1892-field_16-container" class="wpforms-field wpforms-field-select wpforms-field-select-style-classic" data-field-id="16"> 								<label class="wpforms-field-label" for="wpforms-1892-field_16">Jenjang Pendidikan </label> 								<select <?=$type?> id="wpforms-1892-field_16" class="form-control " name="jenjang[]" required=""> 									<option value="">- Jenjang pendidikan  -</option> 									<option value="SD">SD</option> 									<option value="SMP">SMP</option> 									<option value="SMA">SMA</option> 									<option value="S1">S1</option> 									<option value="S2">S2</option> 									<option value="S3">S3</option></select></div> 							<div id="wpforms-1892-field_15-container" class="wpforms-field wpforms-field-text" data-field-id="15"> 								<label class="wpforms-field-label" for="wpforms-1892-field_15">Nama Sekolah Pendidikan </label> 								<input <?=$type?> type="text" id="wpforms-1892-field_15" class="form-control " name="nama_sekolah_pendidikan[]" placeholder="Nama Sekolah Pendidikan " required=""> 							</div> 							<div id="wpforms-1892-field_17-container" class="wpforms-field wpforms-field-text" data-field-id="17"> 								<label class="wpforms-field-label" for="wpforms-1892-field_17">Fakultas/Jurusan</label> 								<input <?=$type?> type="text" id="wpforms-1892-field_17" class="form-control " name="jurusan[]" placeholder="Falkultas/Jurusan" required=""> 							</div> 							<div id="wpforms-1892-field_18-container" class="wpforms-field wpforms-field-text" data-field-id="18"> 								<label class="wpforms-field-label" for="wpforms-1892-field_18">Alamat Sekolah</label> 								<textarea <?=$type?> type="text" id="wpforms-1892-field_18" class="form-control " name="alamat_sekolah[]" placeholder="Alamat Sekolah" required=""></textarea>  							</div> 							<div id="wpforms-1892-field_18-container" class="wpforms-field wpforms-field-text" data-field-id="18"> 								<label class="wpforms-field-label" for="wpforms-1892-field_18">Kota Sekolah</label> 								<input <?=$type?> type="text" id="wpforms-1892-field_18" class="form-control " name="kotapendidikan[]" placeholder="Kota Sekolah" required="">  							</div> 							<div id="wpforms-1892-field_18-container" class="wpforms-field wpforms-field-text" data-field-id="18"> 								<label class="wpforms-field-label" for="wpforms-1892-field_18">Nilai IPK</label> 								<input <?=$type?> type="text" id="wpforms-1892-field_18" class="form-control " name="nilai[]" placeholder="IPK" required="">  							</div><div id="wpforms-1892-field_18-container" class="wpforms-field wpforms-field-text" data-field-id="18"> 								<label class="wpforms-field-label" for="wpforms-1892-field_18">Tahun Lulus</label> 								<input <?=$type?> type="text" id="wpforms-1892-field_18" class="form-control " name="Tahunlulus[]" placeholder="Tahun Lulus" required=""></div>');
								}
							</script>

							<?php foreach($p_recruitment_kursus as $data){?>
							<div id="wpforms-1892-field_19-container" class="wpforms-field wpforms-field-divider" data-field-id="19">
								<h3 id="wpforms-1892-field_19" name="wpforms[fields][19]">Kursus/Pelatihan/Sertifikasi </h3></div>
							<div id="wpforms-1892-field_20-container" class="wpforms-field wpforms-field-text" data-field-id="20">
								<label class="wpforms-field-label" for="wpforms-1892-field_20">Nama Kursus/Pelatihan/Sertifikasi </label>
								<input <?=$type?> type="text" id="wpforms-1892-field_20" class="form-control " name="nama_kursus[]" placeholder="Nama Kursus/Pelatihan/Sertifikasi" value="<?=$data->nama_kursus;?>">

							</div>
							<div id="wpforms-1892-field_21-container" class="wpforms-field wpforms-field-text" data-field-id="21">
								<label class="wpforms-field-label" for="wpforms-1892-field_21">Penyelenggara Kursus/Pelatihan/Sertifikasi </label>
								<input <?=$type?> type="text" id="wpforms-1892-field_21" class="form-control " name="penyelenggara[]" placeholder="Penyelenggara Kursus/Pelatihan/Sertifikasi" value="<?=$data->penyelenggara;?>">
							</div>
							<div class="form-group">
								<label>Tanggal Awal</label>
								<input <?=$type?> class="form-control" type="date" name="tanggal_awal_pelatihan[]" placeholder="Tanggal Awal.." value="<?=$data->tanggal_awal_pelatihan;?>">
							</div>
							<div class="form-group">
								<label>Tanggal Akhir</label>
								<input <?=$type?> class="form-control" type="date" name="tanggal_akhir_pelatihan[]" placeholder="Tanggal Akhir.." value="<?=$data->tanggal_akhir_pelatihan;?>">
							</div>
							<div class="form-group">
								<label>Sertifikat </label>
								<select <?=$type?> class="form-control" type="" name="sertifikat[]" placeholder="Alamat..">
									<option value="">-Pilih Sertifikat-</option>
									<option value="1" <?=$data->sertifikat==1?'selected':''?>>Ada</option>
									<option value="0" <?=$data->sertifikat==0?'selected':''?>>Tidak</option>
								</select>
							</div>
							<?php }?>
							<div id="content_kursus"></div>
							<?php if($type==''){?><button type="button" class="btn btn-primary mt-3 mb-3" onclick="tambah_kursus()">
								<i class="fa fa-plus"></i> Tambah Kursus</button>
							<?php }?>
							<script>
								function tambah_kursus()
								{
									$('#content_kursus').append('<div id="wpforms-1892-field_20-container" class="wpforms-field wpforms-field-text" data-field-id="20"> 								<label class="wpforms-field-label" for="wpforms-1892-field_20">Nama Kursus/Pelatihan/Sertifikasi </label> 								<input <?=$type?> type="text" id="wpforms-1892-field_20" class="form-control " name="nama_kursus[]" placeholder="Nama Kursus/Pelatihan/Sertifikasi">  							</div> 							<div id="wpforms-1892-field_21-container" class="wpforms-field wpforms-field-text" data-field-id="21"> 								<label class="wpforms-field-label" for="wpforms-1892-field_21">Penyelenggara Kursus/Pelatihan/Sertifikasi </label> 								<input <?=$type?> type="text" id="wpforms-1892-field_21" class="form-control " name="penyelenggara[]" placeholder="Penyelenggara Kursus/Pelatihan/Sertifikasi"> 							</div> 								<div class="form-group"> 									<label>Tanggal Awal</label> 									<input <?=$type?> class="form-control" type="date" name="tanggal_awal_pelatihan[]" placeholder="Tanggal Awal.."> 								</div> 								<div class="form-group"> 									<label>Tanggal Akhir</label> 									<input <?=$type?> class="form-control" type="date" name="tanggal_akhir_pelatihan[]" placeholder="Tanggal Akhir.."> 								</div> 								<div class="form-group"> 									<label>Sertifikat </label> 									<select <?=$type?> class="form-control" type="" name="sertifikat[]" placeholder="Alamat.."> 										<option value="">-Pilih Sertifikat-</option> 										<option value="1">Ada</option> 										<option value="0">Tidak</option> 									</select> 								</div>');
								}
							</script>

							<?php foreach($p_recruitment_pekerjaan as $data){?>
							<div id="wpforms-1892-field_29-container" class="wpforms-field wpforms-field-divider" data-field-id="29">
								<h3 id="wpforms-1892-field_29" name="wpforms[fields][29]">Pengalaman Kerja 1</h3>
								<div class="wpforms-field-description">Urutkan dari pengalaman kerja paling akhir</div></div>
							<div id="wpforms-1892-field_30-container" class="wpforms-field wpforms-field-text" data-field-id="30">
								<label class="wpforms-field-label" for="wpforms-1892-field_30">Nama Perusahaan</label>
								<input <?=$type?> type="text" id="wpforms-1892-field_30" class="form-control " name="nama_perusahaan[]" placeholder="Nama Perusahaan" value="<?=$data->nama_perusahaan;?>">

							</div>
							<div id="wpforms-1892-field_35-container" class="wpforms-field wpforms-field-text" data-field-id="35">
								<label class="wpforms-field-label" for="wpforms-1892-field_35">Jabatan</label>
								<input <?=$type?> type="text" id="wpforms-1892-field_35" class="form-control " name="jabatan[]" placeholder="Jabatan" value="<?=$data->nama_perusahaan;?>">

							</div>
							<div id="wpforms-1892-field_31-container" class="wpforms-field wpforms-field-text" data-field-id="31">
								<label class="wpforms-field-label" for="wpforms-1892-field_31">Masa Awal kerja (Perkiraan)</label>
								<input <?=$type?> type="date" id="wpforms-1892-field_31" class="form-control " name="tgl_awal_kerja[]" placeholder="Masa kerja " value="<?=$data->nama_perusahaan;?>">

							</div>
							<div id="wpforms-1892-field_31-container" class="wpforms-field wpforms-field-text" data-field-id="31">
								<label class="wpforms-field-label" for="wpforms-1892-field_31">Masa Akhir kerja (Perkiraan)</label>
								<input <?=$type?> type="date" id="wpforms-1892-field_31" class="form-control " name="tgl_akhir_kerja[]" placeholder="Masa kerja " value="<?=$data->nama_perusahaan;?>">

							</div>
							<div id="wpforms-1892-field_34-container" class="wpforms-field wpforms-field-text" data-field-id="34">
								<label class="wpforms-field-label" for="wpforms-1892-field_34">Lokasi Kerja (kota)</label>
								<input <?=$type?> type="text" id="wpforms-1892-field_34" class="form-control " name="lokasi[]" placeholder="Lokasi Kerja" value="<?=$data->nama_perusahaan;?>">

							</div>
							<div id="wpforms-1892-field_47-container" class="wpforms-field wpforms-field-textarea" data-field-id="47">
								<label class="wpforms-field-label" for="wpforms-1892-field_47">Ruang Lingkup Pekerjaan</label>
								<textarea <?=$type?> id="wpforms-1892-field_47" class="form-control " name="ruang_lingkup_kerja[]" placeholder="Ruang Lingkup Pekerjaan"><?=$data->ruang_lingkup_kerja;?></textarea>
							</div>
							<div id="wpforms-1892-field_37-container" class="wpforms-field wpforms-field-text" data-field-id="37">
								<label class="wpforms-field-label" for="wpforms-1892-field_37">Prestasi Kerja</label>
								<input <?=$type?> type="text" id="wpforms-1892-field_37" class="form-control " name="prestasi_kerja[]" placeholder="Prestasi Kerja" value="<?=$data->prestasi_kerja;?>">

							</div>
							<div id="wpforms-1892-field_38-container" class="wpforms-field wpforms-field-text" data-field-id="38">
								<label class="wpforms-field-label" for="wpforms-1892-field_38">Gaji yang diterima <span class="wpforms-required-label">*</span></label>
								<input <?=$type?> type="text" id="wpforms-1892-field_38" class="form-control form-control " name="gaji[]" required="" placeholder="Gaji yang diterima" value="<?=$data->gaji;?>">

							</div>
							<div id="wpforms-1892-field_38-container" class="wpforms-field wpforms-field-text" >
								<label class="wpforms-field-label" for="wpforms-1892-field_38">Alasan Berhenti <span class="wpforms-required-label">*</span></label>
								<textarea <?=$type?> type="text" class="form-control form-control " name="resign[]" required="" placeholder="Alasan Berhenti"><?=$data->alasan_resign;?></textarea>

							</div>
							<div id="wpforms-1892-field_38-container" class="wpforms-field wpforms-field-text" >
								<label class="wpforms-field-label" for="wpforms-1892-field_38">Slip Gaji Tarakhir <span class="wpforms-required-label">*</span></label>
								<a href="{!! asset('dist/img/file/'.$data->slip_terakhir) !!}" target="_blank"><i class="fa fa-download"></i></a>
							</div>
							<div id="wpforms-1892-field_38-container" class="wpforms-field wpforms-field-text" >
								<label class="wpforms-field-label" for="wpforms-1892-field_38">Surat Faklaring <span class="wpforms-required-label">*</span></label>
								<a href="{!! asset('dist/img/file/'.$data->surat_faklaring) !!}" target="_blank"><i class="fa fa-download"></i></a>

							</div>
							<?php }?>
							<script src="http://code.jquery.com/jquery-1.11.3.min.js"> </script>
							<div id="content_pekerjaan"></div>
							<?php if($type==''){?><button type="button" class="btn btn-primary mt-3 mb-3" onclick="tambah_pekerjaan()">
								<i class="fa fa-plus"></i> Tambah pekerjaan</button>
							<?php }?>
							<script>
								function tambah_pekerjaan()
								{
									$('#content_pekerjaan').append('<h3 id="wpforms-1892-field_29" name="wpforms[fields][29]">Pengalaman Kerja </h3><div id="wpforms-1892-field_30-container" class="wpforms-field wpforms-field-text" data-field-id="30"> 								<label class="wpforms-field-label" for="wpforms-1892-field_30">Nama Perusahaan</label> 								<input <?=$type?> type="text" id="wpforms-1892-field_30" class="form-control " name="nama_perusahaan[]" placeholder="Nama Perusahaan">  							</div> 							<div id="wpforms-1892-field_35-container" class="wpforms-field wpforms-field-text" data-field-id="35"> 								<label class="wpforms-field-label" for="wpforms-1892-field_35">Jabatan</label> 								<input <?=$type?> type="text" id="wpforms-1892-field_35" class="form-control " name="jabatan[]" placeholder="Jabatan">  							</div> 							<div id="wpforms-1892-field_31-container" class="wpforms-field wpforms-field-text" data-field-id="31"> 								<label class="wpforms-field-label" for="wpforms-1892-field_31">Masa Awal kerja (Perkiraan)</label> 								<input <?=$type?> type="date" id="wpforms-1892-field_31" class="form-control " name="tgl_awal_kerja[]" placeholder="Masa kerja ">  							</div> 							<div id="wpforms-1892-field_31-container" class="wpforms-field wpforms-field-text" data-field-id="31"> 								<label class="wpforms-field-label" for="wpforms-1892-field_31">Masa Akhir kerja (Perkiraan)</label> 								<input <?=$type?> type="date" id="wpforms-1892-field_31" class="form-control " name="tgl_akhir_kerja[]" placeholder="Masa kerja ">  							</div> 							<div id="wpforms-1892-field_34-container" class="wpforms-field wpforms-field-text" data-field-id="34"> 								<label class="wpforms-field-label" for="wpforms-1892-field_34">Lokasi Kerja (kota)</label> 								<input <?=$type?> type="text" id="wpforms-1892-field_34" class="form-control " name="lokasi[]" placeholder="Lokasi Kerja">  							</div> 							<div id="wpforms-1892-field_47-container" class="wpforms-field wpforms-field-textarea" data-field-id="47"> 								<label class="wpforms-field-label" for="wpforms-1892-field_47">Ruang Lingkup Pekerjaan</label> 								<textarea <?=$type?> id="wpforms-1892-field_47" class="form-control " name="ruang_lingkup_kerja[]" placeholder="Ruang Lingkup Pekerjaan"></textarea> 							</div> 							<div id="wpforms-1892-field_37-container" class="wpforms-field wpforms-field-text" data-field-id="37"> 								<label class="wpforms-field-label" for="wpforms-1892-field_37">Prestasi Kerja</label> 								<input <?=$type?> type="text" id="wpforms-1892-field_37" class="form-control " name="prestasi_kerja[]" placeholder="Prestasi Kerja">  							</div> 							<div id="wpforms-1892-field_38-container" class="wpforms-field wpforms-field-text" data-field-id="38"> 								<label class="wpforms-field-label" for="wpforms-1892-field_38">Gaji yang diterima <span class="wpforms-required-label">*</span></label> 								<input <?=$type?> type="text" id="wpforms-1892-field_38" class="form-control form-control " name="gaji[]" required="" placeholder="Gaji yang diterima">  							</div>');
								}
							</script>
							<div id="wpforms-1892-field_80-container" class="wpforms-field wpforms-field-divider" data-field-id="80">
								<h3 id="wpforms-1892-field_80" name="wpforms[fields][80]">Kesehatan</h3></div>
							<div id="wpforms-1892-field_111-container" class="wpforms-field wpforms-field-select wpforms-field-select-style-classic" data-field-id="111">
								<label class="wpforms-field-label" for="wpforms-1892-field_111">Apakah Anda merokok?</label>
								<select <?=$type?> id="wpforms-1892-field_111" class="form-control " name="wpforms[fields][111]">
									<option value="Ya">Ya</option>
									<option value="Tidak">Tidak</option></select></div>

							<?php foreach($p_recruitment_penyakit as $data){?>
							<div id="wpforms-1892-field_81-container" class="wpforms-field wpforms-field-divider" data-field-id="81">
								<h3 id="wpforms-1892-field_81" name="wpforms[fields][81]">Riwayat Penyakit/Kecelakaan 1</h3>
								<div class="wpforms-field-description">Sebutkan penyakit berat atau kecelakaan yang pernah Anda alami
									(Penyakit berat adalah kondisi sakit yang mengharuskan adanya perawatan khusus baik di rumah sakit atau di rumah, dan berakibat pada kemampuan Anda menjalankan kehidupan sehari-hari).</div></div>
							<div id="wpforms-1892-field_82-container" class="wpforms-field wpforms-field-text" data-field-id="82">
								<label class="wpforms-field-label" for="wpforms-1892-field_82">Jenis Penyakit/Kecelakaan</label>
								<input <?=$type?> type="text" id="wpforms-1892-field_82" class="form-control " name="jenis_penyakit[]" value="<?=$data->jenis_penyakit;?>">

							</div>
							<div id="wpforms-1892-field_83-container" class="wpforms-field wpforms-field-text" data-field-id="83">
								<label class="wpforms-field-label" for="wpforms-1892-field_83">Tahun mengalami:</label>
								<input <?=$type?> type="text" id="wpforms-1892-field_83" class="form-control " name="tahun_penyakit[]" value="<?=$data->tahun_penyakit;?>">

							</div>
							<div id="wpforms-1892-field_83-container" class="wpforms-field wpforms-field-text" data-field-id="83">
								<label class="wpforms-field-label" for="wpforms-1892-field_83">Sembuh </label>
								<input <?=$type?> type="text" id="wpforms-1892-field_83" class="form-control " name="sembuh[]"  value="<?=$data->sembuh;?>">

							</div>
							<div id="wpforms-1892-field_84-container" class="wpforms-field wpforms-field-text" data-field-id="84">
								<label class="wpforms-field-label" for="wpforms-1892-field_84">Dampak yang diraskan saat ini:</label>
								<input <?=$type?> type="text" id="wpforms-1892-field_84" class="form-control " name="dampak_saat_ini[]"  value="<?=$data->dampak_saat_ini;?>"></div>
							<?php }?>
							<?php if($type==''){?>
							<div id="content_penyakit"></div>
							<button type="button" class="btn btn-primary mt-3 mb-3" onclick="tambah_penyakit()">
								<i class="fa fa-plus"></i> Tambah Penyakit</button>
							<?php }?>
								
							<script>
								function tambah_penyakit()
								{
									$('#content_penyakit').append('							<div id="wpforms-1892-field_81-container" class="wpforms-field wpforms-field-divider" data-field-id="81"> 								<h3 id="wpforms-1892-field_81" name="wpforms[fields][81]">Riwayat Penyakit/Kecelakaan 1</h3> 							</div>	 							<div id="wpforms-1892-field_82-container" class="wpforms-field wpforms-field-text" data-field-id="82"> 								<label class="wpforms-field-label" for="wpforms-1892-field_82">Jenis Penyakit/Kecelakaan</label> 								<input <?=$type?> type="text" id="wpforms-1892-field_82" class="form-control " name="jenis_penyakit[]">  							</div> 							<div id="wpforms-1892-field_83-container" class="wpforms-field wpforms-field-text" data-field-id="83"> 								<label class="wpforms-field-label" for="wpforms-1892-field_83">Tahun mengalami:</label> 								<input <?=$type?> type="text" id="wpforms-1892-field_83" class="form-control " name="tahun_penyakit[]">  							</div> 							<div id="wpforms-1892-field_83-container" class="wpforms-field wpforms-field-text" data-field-id="83"> 								<label class="wpforms-field-label" for="wpforms-1892-field_83">Sembuh </label> 								<input <?=$type?> type="text" id="wpforms-1892-field_83" class="form-control " name="sembuh[]">  							</div> 							<div id="wpforms-1892-field_84-container" class="wpforms-field wpforms-field-text" data-field-id="84"> 								<label class="wpforms-field-label" for="wpforms-1892-field_84">Dampak yang diraskan saat ini:</label> 								<input <?=$type?> type="text" id="wpforms-1892-field_84" class="form-control " name="dampak_saat_ini[]"></div>');
								}
							</script>


							<div id="wpforms-1892-field_93-container" class="wpforms-field wpforms-field-divider" data-field-id="93">
								<h3 id="wpforms-1892-field_93" name="wpforms[fields][93]">Informasi Lainnya</h3></div>
							<div id="wpforms-1892-field_94-container" class="wpforms-field wpforms-field-textarea" data-field-id="94">
								<label class="wpforms-field-label" for="wpforms-1892-field_94">1. Apa rencana Anda dalam 3-5 tahun ke depan?</label>
								<textarea <?=$type?> id="wpforms-1892-field_94" class="form-control " name="rencana_5thn" placeholder="Apa rencana Anda dalam 3-5 tahun ke depan?"><?= $p_recruitment[0]->rencana_5thn; ?></textarea></div>
							<div id="wpforms-1892-field_95-container" class="wpforms-field wpforms-field-textarea" data-field-id="95">
								<label class="wpforms-field-label" for="wpforms-1892-field_95">2. Apa yang menjadi prinsip dalam hidup Anda?</label>
								<textarea <?=$type?> id="wpforms-1892-field_95" class="form-control " name="prinsip" placeholder="Apa yang menjadi prinsip dalam hidup Anda?"><?= $p_recruitment[0]->prinsip; ?></textarea></div>
							<div id="wpforms-1892-field_96-container" class="wpforms-field wpforms-field-textarea" data-field-id="96">
								<label class="wpforms-field-label" for="wpforms-1892-field_96">3. Sebutkan 3 jenis pekerjaan yang Anda sukai!</label>
								<textarea <?=$type?> id="wpforms-1892-field_96" class="form-control " name="pekerjaan_sukai" placeholder="Sebutkan 3 jenis pekerjaan yang Anda sukai"><?= $p_recruitment[0]->pekerjaan_sukai; ?></textarea></div>
							<div id="wpforms-1892-field_97-container" class="wpforms-field wpforms-field-textarea" data-field-id="97">
								<label class="wpforms-field-label" for="wpforms-1892-field_97">4. Sebutkan 3 jenis pekerjaan yang tidak anda sukai!</label>
								<textarea <?=$type?> id="wpforms-1892-field_97" class="form-control " name="pekerjaan_tdk_disukai" placeholder="Sebutkan 3 jenis pekerjaan yang tidak anda sukai"><?= $p_recruitment[0]->pekerjaan_tdk_disukai; ?></textarea></div>
							<div id="wpforms-1892-field_98-container" class="wpforms-field wpforms-field-textarea" data-field-id="98">
								<label class="wpforms-field-label" for="wpforms-1892-field_98">5. Menurut Anda, apa saja kelebihan Anda? (Sebutkan 3)</label>
								<textarea <?=$type?> id="wpforms-1892-field_98" class="form-control " name="kelebihan" placeholder="apa saja kelebihan Anda"><?= $p_recruitment[0]->kelebihan; ?></textarea></div>
							<div id="wpforms-1892-field_99-container" class="wpforms-field wpforms-field-textarea" data-field-id="99">
								<label class="wpforms-field-label" for="wpforms-1892-field_99">6. Menurut Anda, apa saja kekurangan Anda? (Sebutkan 3) </label>
								<textarea <?=$type?> id="wpforms-1892-field_99" class="form-control " name="kekurangan" placeholder="apa saja kekurangan Anda"><?= $p_recruitment[0]->kekurangan; ?></textarea></div>
							<div id="wpforms-1892-field_101-container" class="wpforms-field wpforms-field-select wpforms-conditional-trigger wpforms-field-select-style-classic" data-field-id="101">
								<label class="wpforms-field-label" for="wpforms-1892-field_101">7. Apakah Anda pernah berurusan dengan pihak kepolisian karena tindakan kejahatan?</label>
								<select <?=$type?> id="wpforms-1892-field_101" class="form-control " name="kepolisian">
									<option value="Tidak Pernah" <?= $p_recruitment[0]->kepolisian=='Tidak Pernah'?'selected':''; ?>>Tidak Pernah</option>
									<option value="Pernah" <?= $p_recruitment[0]->kepolisian=='Pernah'?'selected':''; ?>>Pernah</option></select></div>
							<div id="wpforms-1892-field_100-container" class="wpforms-field wpforms-field-textarea wpforms-conditional-field wpforms-conditional-hide" data-field-id="100" style="display:none;">
								<label class="wpforms-field-label" for="wpforms-1892-field_100">Jika pernah, kapan dan terkait hal apa?</label>
								<textarea <?=$type?> id="wpforms-1892-field_100" class="form-control " name="kepolisian_kapan" placeholder="Apakah Anda pernah berurusan dengan pihak kepolisian karena tindakan kejahatan?Jika pernah, kapan dan terkait hal apa?"><?= $p_recruitment[0]->kepolisian_kapan; ?></textarea></div>
							<div id="wpforms-1892-field_102-container" class="wpforms-field wpforms-field-select wpforms-conditional-trigger wpforms-field-select-style-classic" data-field-id="102">
								<label class="wpforms-field-label" for="wpforms-1892-field_102">8. Apakah Anda keberatan untuk melakukan perjalanan dinas ke luar kota?</label>
								<select <?=$type?> id="wpforms-1892-field_102" class="form-control " name="keberatan_perdin_tidak">
									<option value="Tidak Keberatan" <?= $p_recruitment[0]->keberatan_perdin_tidak=='Tidak Keberatan'?'selected':''; ?>>Tidak Keberatan</option>
									<option value="Keberatan" <?= $p_recruitment[0]->keberatan_perdin_tidak=='Keberatan'?'selected':''; ?>>Keberatan</option></select></div>
							<div id="wpforms-1892-field_103-container" class="wpforms-field wpforms-field-textarea wpforms-conditional-field wpforms-conditional-hide" data-field-id="103" style="display:none;">
								<label class="wpforms-field-label" for="wpforms-1892-field_103">Jika keberatan, mengapa?</label>
								<textarea <?=$type?> id="wpforms-1892-field_103" class="form-control " name="keberatan_perdin_ya" placeholder="Apakah Anda keberatan untuk melakukan perjalanan dinas ke luar kota?Jika keberatan, mengapa?"><?= $p_recruitment[0]->keberatan_perdin_ya; ?></textarea></div>
							<div id="wpforms-1892-field_104-container" class="wpforms-field wpforms-field-select wpforms-conditional-trigger wpforms-field-select-style-classic" data-field-id="104">
								<label class="wpforms-field-label" for="wpforms-1892-field_104">9. Apakah Anda keberatan jika ditempatkan di luar kota? </label>
								<select <?=$type?> id="wpforms-1892-field_104" class="form-control " name="keberatan_luar_kota_tidak">
									<option value="Tidak Keberatan" <?= $p_recruitment[0]->keberatan_luar_kota_tidak=='Tidak Keberatan'?'selected':''; ?>>Tidak Keberatan</option>
									<option value="Keberatan" <?= $p_recruitment[0]->keberatan_luar_kota_tidak=='Keberatan'?'selected':''; ?>>Keberatan</option></select></div>
							<div id="wpforms-1892-field_105-container" class="wpforms-field wpforms-field-textarea wpforms-conditional-field wpforms-conditional-hide" data-field-id="105" style="display:none;">
								<label class="wpforms-field-label" for="wpforms-1892-field_105">Jika keberatan, mengapa?</label>
								<textarea <?=$type?> id="wpforms-1892-field_105" class="form-control " name="keberatan_luar_kota_ya" placeholder="Apakah Anda keberatan jika ditempatkan di luar kota?Jika keberatan, mengapa?"><?= $p_recruitment[0]->keberatan_luar_kota_ya; ?></textarea></div>
							<div id="wpforms-1892-field_106-container" class="wpforms-field wpforms-field-textarea" data-field-id="106">
								<label class="wpforms-field-label" for="wpforms-1892-field_106">10.	Bila diterima, berapa besar gaji dan fasilitas apa yang Anda harapkan? <span class="wpforms-required-label">*</span></label>
								<textarea <?=$type?> id="wpforms-1892-field_106" class="form-control form-control " name="gaji_fasilitas" required="" placeholder="Bila diterima, berapa besar gaji dan fasilitas apa yang Anda harapkan? "><?= $p_recruitment[0]->gaji_fasilitas; ?></textarea></div>
							<div id="wpforms-1892-field_113-container" class="wpforms-field wpforms-field-select wpforms-field-select-style-classic" data-field-id="113">
								<label class="wpforms-field-label" for="wpforms-1892-field_113">11. Apakah nilai salary yang Anda harapkan tersebut dapat dinegosiasikan?</label>
								<select <?=$type?> id="wpforms-1892-field_113" class="form-control " name="nego_salary">
									<option value="Ya"<?= $p_recruitment[0]->nego_salary=='Ya'?'selected':''; ?>>Ya</option>
 <option value="Tidak" <?= $p_recruitment[0]->nego_salary=='Tidak'?'selected':''; ?>>Tidak</option></select></div>
							<div id="wpforms-1892-field_107-container" class="wpforms-field wpforms-field-textarea" data-field-id="107">
								<label class="wpforms-field-label" for="wpforms-1892-field_107">12. Bila diterima, kapan Anda dapat mulai bekerja? <span class="wpforms-required-label">*</span></label>
								<textarea <?=$type?> id="wpforms-1892-field_107" class="form-control form-control " name="kapan_bekerja" required="" placeholder="Bila diterima, kapan Anda dapat mulai bekerja?"><?= $p_recruitment[0]->kapan_bekerja; ?></textarea></div>
							<div id="wpforms-1892-field_109-container" class="wpforms-field wpforms-field-checkbox" data-field-id="109">
								<label class="wpforms-field-label" for="wpforms-1892-field_109">Dengan ini saya menyatakan bahwa data &amp; informasi yang saya sampaikan benar adanya dan saya bersedia dituntut di kemudian hari jika informasi &amp; data yang saya sampaikan di form ini tidak benar <span class="wpforms-required-label">*</span></label>
								<ul id="wpforms-1892-field_109" class="form-control "><li class="choice-1 depth-1" style="overflow: hidden;"><input <?=$type?> type="checkbox" id="wpforms-1892-field_109_1" name="penyataan_setuju" value="1" required="" <?= $p_recruitment[0]->penyataan_setuju?'checked':''; ?>><label class="wpforms-field-label-inline" for="wpforms-1892-field_109_1"> Setuju</label></li></ul></div>
						<div class="wpforms-submit-container"><input <?=$type?> type="hidden" name="wpforms[id]" value="1892"><input <?=$type?> type="hidden" name="wpforms[author]" value="2"><input <?=$type?> type="hidden" name="wpforms[post_id]" value="1893">
							<?php if($type==''){?><button type="submit" name="wpforms[submit]" class="wpforms-submit btn btn-primary" id="wpforms-submit-1892" value="wpforms-submit" aria-live="assertive" data-alt-text="Sending..." data-submit-text="Submit">Submit</button>
							<?php }?></div>
							</div></form></div> <!-- .wpforms-container --> </div>
			</div>
		</div>
	</div>
</div>
</section>
</div>
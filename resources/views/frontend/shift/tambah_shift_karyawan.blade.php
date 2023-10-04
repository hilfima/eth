@extends('layouts.app_fe')

@section('content')
<link rel='stylesheet' href='https://weareoutman.github.io/clockpicker/dist/jquery-clockpicker.min.css'>
<link rel="stylesheet" href="./style.css">

<!-- Content Wrapper. Contains page content -->
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
					<h4 class="card-title float-left mb-0 mt-2">Shift Kerja</h4>

				</div>
			</div>

			<!-- /.content-header -->

			<!-- Main content -->
			<div class="card">
				<div class="card-header">
					<!--<h3 class="card-title">DataTable with default features</h3>-->
				</div>
				<!-- /.card-header -->
				<div class="card-body">
					<!-- form start -->
					<form class="form-horizontal" method="POST" action="{!! route('fe.update_shift_karyawan',$id) !!}" enctype="multipart/form-data">
					{{ csrf_field() }}
					<div class="row">



						<input type="hidden" class="form-control " style="width: 20px" id="shifting" value="1" name="shifting" onclick="check(this)"  required=""/>
						<div class="col-sm-12" id="karyawanKonten">
							<!-- text input -->
							<div class="form-group">
								<label>Karyawan</label>

								<select class="form-control select2" name="karyawan" id="karyawan" disabled  style="width: 100%;" required>
									<option value="" disabled="">Pilih Karyawn</option>
									<?php
							foreach ($karyawan as $kar) { ?>
							<option value="<?=$kar->p_karyawan_id; ?>" <?=$kar->p_karyawan_id==$data['p_karyawan_id']?'selected':'' ?>><?=$kar->nama; ?></option>
									<?php
							} ?>

								</select>

							</div>
							<div class="form-group">
								<label>Absen </label>

<select class="form-control select2" name="absen_id" id="karyawan"  style="width: 100%;" required>
									<option value="" >Pilih Absen</option>
									<?php
									foreach ($absen as $absen) { ?>
									<option value="<?=$absen->absen_id; ?>" <?=$absen->absen_id==$data['absen_id']?'selected':'' ?>><?=$help->tgl_indo($absen->tgl_awal); ?> - <?=$help->tgl_indo($absen->tgl_akhir); ?> Jam <?=$absen->jam_masuk; ?> - <?=$absen->jam_keluar; ?> <?=$absen	->keterangan; ?></option>
									<?php
								} ?>

								</select>

							</div>
						</div>
						<div class="col-sm-6">
							<div class="form-group">
								<label>Tanggal</label>
								<div class="input-group date" id="tgl_posting" >
									<input type="date" class="form-control " id="tgl" name="tanggal"  value="<?=$data['tanggal'] ?>"  required=""/>

								</div>
							</div>
						</div>
						
					</div>
					<div class="col-sm-12">

					</div>

					<a href="{!! route('fe.jadwal_shift') !!}" class="btn btn-default pull-left"><span class="fa fa-times"></span> Kembali</a>
					<button type="submit" class="btn btn-info pull-right"><span class="fa fa-check"></span> Simpan</button>
				</div>
				<!-- /.box-footer -->
				</form>
			</div>
			<!-- /.card-body -->
		</div>
		<!-- /.content -->
	</div>
</div>
<script>
	function check(e)
	{
		if ($(e).is(':checked')) {
			$('#karyawanKonten').show();
		} else
			$('#karyawanKonten').hide();


		$('#karyawan').val('');
		changeentitas();
	}
	function changeentitas()
	{
		var entitas = $('#entitas').val();

		$('#karyawan').val('');

		$.ajax({
			type: 'get',

			url: 'daftarKaryawan/'+entitas+'/',
			dataType: 'json',
			success: function(data) {
				//alert(data.respon)
				$('#karyawan').html(data.respon);
			}
		});
	}

</script>
<!-- /.content-wrapper -->
@endsection

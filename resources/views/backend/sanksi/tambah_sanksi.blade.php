@extends('layouts.appsA')

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	@include('flash-message')
	<!-- Content Header (Page header) -->
	<div class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6">
					<h1 class="m-0 text-dark">Sanksi</h1>
				</div><!-- /.col -->
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><a href="{!! route('admin') !!}">Admin</a></li>
						<li class="breadcrumb-item active">Sanksi</li>
					</ol>
				</div><!-- /.col -->
			</div><!-- /.row -->
		</div><!-- /.container-fluid -->
	</div>
	<!-- /.content-header -->

	<!-- Main content -->
	<div class="card">

		<form class="form-horizontal" method="POST" action="{!! route('be.'.$type,$id) !!}" enctype="multipart/form-data">

			<div class="card-header">
				<h3 class="card-title mb-0	">Sanksi</h3>
			</div>
			<!-- /.card-header -->
			<div class="card-body">
				<!-- form start -->
				{{ csrf_field() }}

				<!-- ./row -->
				<div class="row">

					<div class="col-md-12">
						<div class="form-group">
							<label> Karyawan</label>
							<select type="text" class="form-control select2" id="nama" name="p_karyawan_id" required>
								<option value="">- Pilih Karyawan - </option>
								<?php
								foreach ($karyawan as $karyawan) { ?>
								<option value="<?=$karyawan->p_karyawan_id ?>" <?=$data['p_karyawan_id']==$karyawan->p_karyawan_id?'selected':''; ?>><?=$karyawan->nama ?></option>
								<?php
							} ?>

							</select>
						</div>
					</div><div class="col-md-12">
						<div class="form-group">
							<label> Sanksi</label>
							<select type="text" class="form-control select2" id="sanksi" name="sanksi"  required>
								<option value="">- Pilih Sanksi - </option>
								<?php
								foreach ($sanksi as $sanksi) { ?>
								<option value="<?=$sanksi->m_jenis_sanksi_id ?>" <?=$data['m_jenis_sanksi_id']==$sanksi->m_jenis_sanksi_id?'selected':''; ?>><?=$sanksi->nama_sanksi ?></option>
								<?php
							} ?>

							</select>
						</div>
					</div>
					<div class="col-md-12">
						<div class="form-group">
							<label>Alasan</label>
							<textarea type="text" class="form-control" id="nama" name="alasan_sanksi"  placeholder="Alasan Sanksi"  required><?=$data['alasan_sanksi']; ?></textarea>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label>Tgl Awal Belaku</label>
							<input type="date" class="form-control" id="nama" name="tgl_awal" placeholder="Tanggal" value="<?=$data['tgl_awal']; ?>"  required>
						</div>
					</div><div class="col-md-6">
						<div class="form-group">
							<label>Tgl Akhir Belaku</label>
							<input type="date" class="form-control" id="nama" name="tgl_akhir" placeholder="Tanggal" value="<?=$data['tgl_akhir']; ?>" required>
						</div>
					</div>
				</div>
				<div class="form-group">
							<label>File</label>
							<input type="file" class="form-control" id="nama" name="file" placeholder="Tanggal"  required>
						</div>
				<a href="{!! route('be.role') !!}" class="btn btn-default pull-left"><span class="fa fa-times"></span> Kembali</a>
				<button type="submit" class="btn btn-info pull-right"><span class="fa fa-edit"></span> Simpan</button>
				<br>
				<br>
			</div>
			<!-- /.box-footer -->
		</form>
	</div>
	<!-- /.card-body -->
</div>
<!-- /.content -->
</div>
<!-- /.content-wrapper -->
@endsection

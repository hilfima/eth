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
					<h1 class="m-0 text-dark">Agenda Perusahaan</h1>
				</div><!-- /.col -->
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><a href="{!! route('admin') !!}">Admin</a></li>
						<li class="breadcrumb-item active">Agenda Perusahaan</li>
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
				<h3 class="card-title mb-0	">Agenda Perusahaan</h3>
			</div>
			<!-- /.card-header -->
			<div class="card-body">
				<!-- form start -->
				{{ csrf_field() }}

				<!-- ./row -->
				<div class="row">

					<div class="col-md-12">
						<div class="form-group">
							<label>Nama Agenda </label>
							<input type="text" class="form-control" id="nama" name="nama" placeholder="Nama Agenda Perusahaan" value="<?=$data['nama']; ?>">
						</div>
					</div><div class="col-md-6">
						<div class="form-group">
							<label>Tanggal Awal</label>
							<input type="date" class="form-control" id="nama" name="tgl_awal" placeholder="Tanggal Awal" value="<?=$data['tgl_awal']; ?>">
						</div>
					</div><div class="col-md-6">
						<div class="form-group">
							<label>Tanggal Akhir</label>
							<input type="date" class="form-control" id="nama" name="tgl_akhir" placeholder="Nama Agenda Perusahaan" value="<?=$data['tgl_akhir']; ?>">
						</div>
					</div><div class="col-md-6">
						<div class="form-group">
							<label>Jam Mulai</label>
							<input type="time" class="form-control" id="nama" name="jam_mulai" placeholder="Nama Agenda Perusahaan" value="<?=$data['jam_mulai']; ?>">
						</div>
					</div><div class="col-md-6">
						<div class="form-group">
							<label>Jam Selesai </label>
							<input type="time" class="form-control" id="nama" name="jam_selesai" placeholder="Jam Selesai" value="<?=$data['jam_selesai']; ?>">
						</div>
					</div>
					<div class="col-md-12">
						<div class="form-group">
							<label>Kontak Person  </label>
							<input type="text" class="form-control" id="nama" name="cp" placeholder="Kontak Person" value="<?=$data['cp']; ?>">
						</div>
					</div><div class="col-md-12">
						<div class="form-group">
							<label>Lokasi  </label>
							<input type="text" class="form-control" id="nama" name="lokasi" placeholder="Lokasi Diajukan" value="<?=$data['lokasi']; ?>">
						</div>
					</div>
					<div class="col-md-12">
						<div class="form-group">
							<label>Brosur</label>
							<input type="file" class="form-control" id="nama" name="brosur" placeholder="Brosur" value="<?=$data['brosur']; ?>">
						</div>
					</div>
					<?php if(isset($karyawan)){?>
						
					<div class="col-md-12">
						<div class="form-group">
							<label>Karyawan Yang diundang</label>
							<select type="number" class="form-control select2" multiple="" id="nama" name="karyawan[]" placeholder="Keterangan" value="">
								<option value="">- Pilih Karyawan -</option>
								<?php foreach($karyawan as $karyawan){?>
								<option value="<?=$karyawan->p_karyawan_id?>" <?=in_array($karyawan->p_karyawan_id,$list)?'selected':''?>><?=$karyawan->nama?></option>
								<?php }?>
							</select>
						</div>
					</div>
					<?php }?>
					<div class="col-md-12">
						<div class="form-group">
							<label>Deskripsi agenda</label>
							<textarea type="number" class="form-control summernote" id="nama" name="deskripsi" placeholder="Keterangan" value=""><?=$data['deskripsi']; ?></textarea>
						</div>
					</div>





				</div>

				<a href="{!! route('be.agenda_perusahaan') !!}" class="btn btn-default pull-left"><span class="fa fa-times"></span> Kembali</a>
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

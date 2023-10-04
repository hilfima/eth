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
					<h1 class="m-0 text-dark">Tahap Interview</h1>
				</div><!-- /.col -->
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><a href="{!! route('admin') !!}">Admin</a></li>
						<li class="breadcrumb-item active">Tahap Interview</li>
					</ol>
				</div><!-- /.col -->
			</div><!-- /.row -->
		</div><!-- /.container-fluid -->
	</div>
	<!-- /.content-header -->

	<!-- Main content -->
	<div class="card">

		<form class="form-horizontal" method="POST" action="{!! route('be.update_kandidat_interview',[$id,$id_kandidat]) !!}" enctype="multipart/form-data">

		<div class="card-header">
			<h3 class="card-title mb-0	">Tahap Interview</h3>
		</div>
		<!-- /.card-header -->
		<div class="card-body">
			<!-- form start -->
			{{ csrf_field() }}

			<!-- ./row -->

			
			<!--<div class="form-group">
				<label>File Interview 1</label>
				<input class="form-control " id="tgl_absen" name="file_interview1" type="file" >
			</div>-->

			<div class="form-group">
				<label>File Interview 2</label>
				<input class="form-control " id="tgl_absen" name="file_interview2" type="file" >
			</div>

			<div class="form-group">
				<label>File Psikogram</label>
				<input class="form-control " id="tgl_absen" name="file_psikogram" type="file" >
			</div>


	<div class="form-group">
		<label>Rekomendasi HR</label>
		<select class="form-control " id="tgl_absen" name="rekomendasi_hr"  required="" >
			<option value="">Pilih Rekomendasi HR</option>
			<option value="1">Disarankan</option>
			<option value="2">Dapat Dipertimbangkan</option>
			<option value="3">Tidak Disarankan</option>
		</select>

	</div>

	<div class="form-group">
		<label>Keterangan HR</label>
		<textarea class="form-control " id="tgl_absen" name="keterangan_hr" placeholder="Deskripsi" ></textarea>
	</div>
	

		</div>

		<a href="{!! route('be.database_kandidat') !!}" class="btn btn-default pull-left"><span class="fa fa-times"></span> Kembali</a>
			<?php if($kandidat[0]->file_interview1){?>
			<button type="submit" class="btn btn-info pull-right"><span class="fa fa-edit"></span> Simpan</button>
			<?php }else{
				echo 'Hanya Bisa di Simpan ketika Atasan sudah memberikan file interview ke 1';
			}?>
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

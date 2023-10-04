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
					<h1 class="m-0 text-dark">Offering Letter</h1>
				</div><!-- /.col -->
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><a href="{!! route('admin') !!}">Admin</a></li>
						<li class="breadcrumb-item active">Offering Letter</li>
					</ol>
				</div><!-- /.col -->
			</div><!-- /.row -->
		</div><!-- /.container-fluid -->
	</div>
	<!-- /.content-header -->

	<!-- Main content -->
	<div class="card">

		<form class="form-horizontal" method="POST" action="{!! route('be.update_kandidat_offering_letter',[$id,$id_kandidat]) !!}" enctype="multipart/form-data">

		<div class="card-header">
			<h3 class="card-title mb-0	">Offering Letter</h3>
		</div>
		<!-- /.card-header -->
		<div class="card-body">
			<!-- form start -->
			{{ csrf_field() }}

			<!-- ./row -->


			

			<!--<div class="form-group">
				<label>File Offering Letter</label>
				<input class="form-control " id="tgl_absen" name="file_offfering_letter" type="file" >
			</div>-->


			<div class="form-group">
				<label>Status Kelanjutan</label>
				<select class="form-control " id="tgl_absen" name="status_kelanjutan"  required="" >
					<option value="">Pilih Status Kelanjutan</option>
					<option value="7">Lanjut</option>
					<option value="15">Tidak Dilanjutkan</option>
				</select>

			</div>


		</div>

		<a href="{!! route('be.database_kandidat') !!}" class="btn btn-default pull-left"><span class="fa fa-times"></span> Kembali</a>
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

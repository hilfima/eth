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
					<h1 class="m-0 text-dark"><?=ucwords('Tambah Kandidiat Karyawan'); ?></h1>
				</div><!-- /.col -->
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><a href="{!! route('admin') !!}">Admin</a></li>
						<li class="breadcrumb-item active"><?=ucwords('Tambah Kandidiat Karyawan'); ?></li>
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
				<h3 class="card-title mb-0	"><?=ucwords('Tambah Kandidiat Karyawan'); ?></h3>
			</div>
			<!-- /.card-header -->
			<div class="card-body">
				<!-- form start -->
				{{ csrf_field() }}

				<!-- ./row -->
				<div class="row">

					<div class="col-md-12">
						<div class="form-group">
							<label>Nama Kandidat</label>
							<input type="text" class="form-control" id="nama" name="nama" placeholder="Nama Kandidat"  value="" required>
						</div><div class="form-group">
							<label>No WA Kandidat</label>
							<input type="text" class="form-control" id="wa" name="wa" placeholder="Wa Kandidat"  value="" required>
						</div><div class="form-group">
							<label>Email Kandidat</label>
							<input type="text" class="form-control" id="email" name="email" placeholder="Email Kandidat"  value="" required>
						</div>

						<div class="form-group">
							<label>File CV</label>
							<input class="form-control" type="file" name="file" style="width: 100%;" required>

							</input>
						</div>
					</div>





				</div>

				<a href="{!! route('be.bank') !!}" class="btn btn-default pull-left"><span class="fa fa-times"></span> Kembali</a>
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

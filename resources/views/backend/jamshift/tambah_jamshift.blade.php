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
					<h1 class="m-0 text-dark">Jam Shift</h1>
				</div><!-- /.col -->
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><a href="{!! route('admin') !!}">Admin</a></li>
						<li class="breadcrumb-item active">Jam Shift</li>
					</ol>
				</div><!-- /.col -->
			</div><!-- /.row -->
		</div><!-- /.container-fluid -->
	</div>
	<!-- /.content-header -->

	<!-- Main content -->
	<div class="card">
		<div class="card-header">
			Jam Shift
		</div>
		<!-- /.card-header -->
		<div class="card-body">
			<!-- form start -->
			<form class="form-horizontal" method="POST" action="{!! route('be.'.$type,$id) !!}" enctype="multipart/form-data">
				{{ csrf_field() }}
				<div class="row">
					<div class="col-sm-12">
						<!-- text input -->
						<div class="form-group">
							<label>Nama Jam Shift</label>
							<input type="text" class="form-control" placeholder="Nama Jam Shift ..." id="judul" name="nama" required value="<?=$data['nama_jam_shift'];?>">
						</div>
					</div>
				
					<div class="col-sm-6">
						<div class="form-group">
							<label>Jam Masuk</label>
							<input type="time" class="form-control" placeholder="" id="judul" name="jam_masuk" required  value="<?=$data['jam_masuk']; ?>">
						</div>
					</div><div class="col-sm-6">
						<div class="form-group">
							<label>Jam Keluar</label>
							<input type="time" class="form-control" placeholder="Nama Jam Shift ..." id="judul" name="jam_keluar" required value="<?=$data['jam_keluar']; ?>">
						</div>
					</div>
					<div class="col-sm-12">
						<div class="form-group">
							<label>Keterangan</label>
							<textarea  class="form-control" placeholder="Keterangan" name="keterangan" ><?=$data['jam_masuk']; ?></textarea>
						</div>
					</div>
					<div class="col-sm-12">
						<div class="form-group">
							<label>Entitas</label>
							<select  class="form-control select2" multiple placeholder="Keterangan" name="entitas[]" >
							    <option value="" disabled> - Entitas - </option>
							    <?php foreach($entitas as $lokasi){?>
							        <option value="<?=$lokasi->m_lokasi_id?>"><?=$lokasi->nama?></option>
							    <?php }?>
							</select>
						</div>
					</div>
					
					
					<div class="col-sm-6">
						<div class="form-group">
							<label>Tanggal Masuk(Optional)</label>
							<input type="date" class="form-control" placeholder="" name="tgl_awal"  value="<?=$data['tgl_awal']; ?>">
						</div>
					</div><div class="col-sm-6">
						<div class="form-group">
							<label>Tanggal Keluar(Optional)</label>
							<input type="date" class="form-control" placeholder=""  name="tgl_akhir"  value="<?=$data['tgl_akhir']; ?>">
						</div>
						</div>
						<div class="col-sm-12">
							Tanggal awal tanggal akhir diperuntukan untuk range tanggal kemunculan shift pada pemilihhan tanggal .
						</div>
				</div>
				<!-- /.box-body -->
				<div class="box-footer">
					<a href="{!! route('be.jamshift') !!}" class="btn btn-default pull-left"><span class="fa fa-times"></span> Kembali</a>
					<button type="submit" class="btn btn-info pull-right"><span class="fa fa-check"></span> Simpan</button>
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

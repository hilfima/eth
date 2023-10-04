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
					<h1 class="m-0 text-dark">Kontrak Kerja</h1>
				</div><!-- /.col -->
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><a href="{!! route('admin') !!}">Admin</a></li>
						<li class="breadcrumb-item active">Kontrak Kerja</li>
					</ol>
				</div><!-- /.col -->
			</div><!-- /.row -->
		</div><!-- /.container-fluid -->
	</div>
	<!-- /.content-header -->

	<!-- Main content -->
	<div class="card">

		<form class="form-horizontal" method="POST" action="{!! route('be.update_kandidat_kontrak_kerja',[$id,$id_kandidat]) !!}" enctype="multipart/form-data">

		<div class="card-header">
			<h3 class="card-title mb-0	">Kontrak Kerja</h3>
		</div>
		<!-- /.card-header -->
		<div class="card-body">
			<!-- form start -->
			{{ csrf_field() }}

			<!-- ./row -->




			<!--<div class="form-group">
				<label>File Kontrak Kerja</label>
				<input class="form-control " id="tgl_absen" name="file_kontrak_kerja" type="file" >
			</div>-->


			<div class="form-group">
				<label>Status Kelanjutan</label>
				<select class="form-control " id="tgl_absen" name="status_kelanjutan"  required="" >
					<option value="">Pilih Status Kelanjutan</option>
					<option value="8">Terima Menjadi Karyawan</option>
					<option value="16">Tidak Dilanjutkan</option>
				</select>
			</div>
			<div class="form-group">
				<label>Entitas</label>
				<select class="form-control select2" name="lokasi" style="width: 100%;" required>
					<?php
			foreach ($lokasi AS $lokasi) {
				
					echo '<option value="'.$lokasi->m_lokasi_id.'">'.$lokasi->nama.'</option>';
				
			}
			?>
				</select>
			</div>
			<div class="form-group">
				<label>Departemen</label>
				<select class="form-control select2" name="departemen" style="width: 100%;" required>
					<?php
					foreach ($departemen AS $departemen) {

						echo '<option value="'.$departemen->m_departemen_id.'">'.$departemen->nama.'</option>';
					}
					?>
				</select>
			</div>
			<div class="form-group">
				<label>Unit Kerja</label>
				<select class="form-control select2" name="divisi" style="width: 100%;" required>
					<?php
					foreach ($divisi AS $divisi) {

						echo '<option value="'.$divisi->m_divisi_id.'">'.$divisi->nama.'</option>';
					}
					?>
				</select>
			</div>
			<div class="form-group">
				<label>Jabatan/Pangkat</label>
				<select class="form-control select2" name="jabatan" style="width: 100%;" required>
					<?php
			foreach ($jabatan AS $jabatan) {
				
					echo '<option value="'.$jabatan->m_jabatan_id.'">'.$jabatan->nama.' - '.$jabatan->nmpangkat.' - '.$jabatan->nmlokasi.'</option>';
				
			}
			?>
				</select>
			</div>
			<div class="form-group">
				<label>Kantor</label>
				<select class="form-control select2" name="kantor" style="width: 100%;" required>
					<option value="1">- Pilih Kantor</option>
					<?php
			foreach ($kantor AS $kantor) {
				$selected ='';

				echo '<option value="'.$kantor->m_office_id.'" '.$selected.'>'.$kantor->nama.'</option>';
			}
			?>
				</select>
				<!-- <input type="text" class="form-control" placeholder="Kantor..." id="kantor" name="kantor" required>-->
			</div>
			<div class="form-group">
				<label>Status Pekerjaan</label>
				<select class="form-control select2" name="status_pekerjaan" style="width: 100%;" required>
					<?php
			foreach ($stspekerjaan AS $stspekerjaan) {
				
					echo '<option value="'.$stspekerjaan->m_status_pekerjaan_id.'">'.$stspekerjaan->nama.'</option>';
				
			}
			?>
				</select>
			</div>
			<div class="form-group">
				<label>Kota</label>
				<input type="text" class="form-control" placeholder="Kota..." id="kotakerja" name="kotakerja" value="">
			</div>
			<div class="form-group">
				<label>Tanggal Masuk</label>
				<div class="input-group date" id="tgl_awal" data-target-input="nearest">
					<input type="date" class="form-control " id="tgl_awal" name="tgl_awal" data-target="#tgl_awal" required value=""/>
					
				</div>
			</div>
			<div class="form-group">
				<label>Tanggal Keluar</label>
				<div class="input-group date" id="tgl_akhir" data-target-input="nearest">
					<input type="date" class="form-control " id="tgl_akhir" name="tgl_akhir" data-target="#tgl_akhir" value=""/>
					
				</div>
			</div>
			<div class="form-group">
				<label>No. Absen</label>
				<input type="text" class="form-control" placeholder="No. Absen..." id="no_absen" name="no_absen" required value="">
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

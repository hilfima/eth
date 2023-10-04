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
				<h1 class="m-0 text-dark"> Regenerate Field</h1>
			</div><!-- /.col -->
			
		</div><!-- /.row -->
	</div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<!-- Main content -->
<div class="card">
	
	<!-- /.card-header -->
	<div class="card-body">
		<form id="cari_absen" class="form-horizontal" method="get" action="{!! route('be.Koreksi') !!}">
			<div class="row">
				<div class="col-lg-12">
					<div class="form-group">
						<label>Periode Generate</label>
						<select class="form-control select2" name="prl_generate" style="width: 100%;" required>
							<?php
							foreach ($periode AS $periode) {
								if ($periode->prl_generate_id==$id_generate) {
									echo '<option selected="selected" value="'.$periode->prl_generate_id.'">Periode: '.$periode->tahun_gener.' Bulan: '.$periode->bulan_gener.' | Absen:'.$periode->tgl_awal.' - '.$periode->tgl_akhir.' | Lembur:'.$periode->tgl_awal_lembur.' - '.$periode->tgl_akhir_lembur.'</option>';
								} else {
									//echo '<option  value="'.$periode->prl_generate_id.'">Periode: '.$periode->tahun_gener.' Bulan: '.$periode->bulan_gener.' | Absen:'.$periode->tgl_awal.' - '.$periode->tgl_akhir.' | Lembur:'.$periode->tgl_awal_lembur.' - '.$periode->tgl_akhir_lembur.'</option>';
								}
							}
							?>
						</select>
					</div>
					<div class="form-group">
						<label>Field</label>
						<select class="form-control select2" name="prl_generate" style="width: 100%;" required>
							<option value="">Pilih Field</option>
							<?php
								foreach($tunjangan as $tunjangan){
									echo '<option value="0-'.$tunjangan->m_tunjangan_id.'">Tunjangan : '.$tunjangan->nama.'</option>';
								}foreach($potongan as $potongan){
									echo '<option value="1-'.$potongan->m_potongan_id.'">Potongan : '.$potongan->nama.'</option>';
								}
							?>
						</select>
					</div>
				</div>
			</div>
			<button type="submit" name="Cari" class="btn btn-primary" value="Cari"><span class="fa fa-search"></span> Cari</button>
		</form>
	</div>
</div>

@endsection
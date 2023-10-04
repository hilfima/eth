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
					<h1 class="m-0 text-dark"><?=ucwords('Bank Entitas'); ?></h1>
				</div><!-- /.col -->
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><a href="{!! route('admin') !!}">Admin</a></li>
						<li class="breadcrumb-item active"><?=ucwords('Bank Entitas'); ?></li>
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
				<h3 class="card-title mb-0	"><?=ucwords('Bank Entitas'); ?></h3>
			</div>
			<!-- /.card-header -->
			<div class="card-body">
				<!-- form start -->
				{{ csrf_field() }}

				<!-- ./row -->
				<div class="row">

					<div class="col-md-12">
						<div class="form-group">
							<label>Nama bank</label>
							<select type="text" class="form-control select2" id="nama" name="nama" placeholder="Nama bank" value="">
								<option value="">- Pilih Bank - </option>
								<?php
								$databank = array("Cash","Bank Mandiri","BSI","BRI","BNI","Panin Bank.","BCA","CIMB Niaga","Bank Permata","OCBC NISP","BTPN","DBS","Bank Ganesha","Bank NOBU","Bank Victoria","Bank Sampoerna","IBK Bank","Bank Capital","Bank Bukopin","Bank Mega","Bank Mayora","Bank UOB","Bank Fama","Bank Mayapada International","Bank Mandiri Taspen","Bank Resona Perdania","Bank BKE","BRI Agro","Bank SBI Indonesia","Bank Artha Graha","Commonwealth Bank","HSBC Indonesia","ICBC Indonesia","JP Morgan Chase","Bank Oke Indonesia","MNC Bank","KEB Hana Bank","Shinhan Bank","Standard Chartered Bank Indonesia","Bank of China","BNPParibas","Bank Jasa Jakarta","Bank Index","Bank Artos","Bank Ina","Bank Mestika",".Bank Mas","CTBC Bank","Bank Sinarmas","Maybank Indonesia","Bank of India Indonesia","Bank QNB Indonesia","Bank JTrust Indonesia","Bank Woori Saudara","Bank Amar Indonesia","Prima Master Bank","Citibank Indonesia","Daftar update  Bank Umum Syariah yang ikut program relaksasi kredit","Bank Syariah Mandiri","Bank BNI syariah","Bank Bukopin Syariah","Bank NTB Syariah","Permata Bank Syariah","Bank Muamalat","Bank Mega Syariah",".Bank BJB Syariah","BRI Syariah"," BTPN Syariah","Bank Net Syariah","BCA Syariah","Panin Dubai Syariah Bank");
								for ($i=0;$i<count($databank);$i++) {
									$selected ='';
									if ($data['nama']==$databank[$i]) {
										$selected ='selected';
									}
									echo "<option value='".$databank[$i]."' $selected>".$databank[$i]."</option>";
								}
								?>

							</select>
						</div>
					</div>
					<div class="col-md-12">
						<div class="form-group">
							<label>Entitas</label>
							<select class="form-control select2" name="m_lokasi_id" style="width: 100%;" required>
								<option value="">Pilih Entitas</option>
								<?php
								foreach ($entitas AS $entitas) {
									if ($entitas->m_lokasi_id==$data['m_lokasi_id']) {
										echo '<option selected="selected" value="'.$entitas->m_lokasi_id.'">'.$entitas->nama.'</option>';
									} else {
										echo '<option value="'.$entitas->m_lokasi_id.'">'.$entitas->nama.'</option>';
									}
								}
								?>
							</select>
						</div>
					</div>
					<div class="col-md-12">
						<div class="form-group">
							<label>Status</label>
							<select class="form-control" name="status" style="width: 100%;" required>
								<option value="">Pilih Status</option>
								<option value="ON" <?=$data['status']=='ON'?'selected':''; ?>>ON</option>
								<option value="OFF" <?=$data['status']=='OFF'?'selected':''; ?>>OFF</option>

							</select>
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

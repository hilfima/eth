@extends('layouts.appsA')



@section('content')
<style>
	.trr {
		background-color: #0099FF;
		color: #ffffff;
		align: center;
		padding: 10px;
		height: 20px;
	}
	
	tr.odd > td {
		background-color: #E3F2FD;
	}

	tr.even > td {
		background-color: #BBDEFB;
	}
	.fixedTable .table {
  background-color: white;
  width: auto;
  display: table;
}
.fixedTable .table tr td,
.fixedTable .table tr th {
  min-width: 100px;
  width: 100px;
  min-height: 20px;
  height: 20px;
  padding: 5px;
  max-width: 100px;
}
.fixedTable-header {
  width: 100%;
  height: 60px;
  /*margin-left: 150px;*/
  overflow: hidden;
  border-bottom: 1px solid #CCC;
}
.fixedTable-sidebar {
  width: 0;
  height: 510px;
  float: left;
  overflow: hidden;
  border-right: 1px solid #CCC;
}
@media screen and (max-height: 700px) {
 .fixedTable-body {
  overflow: auto;
  width: 100%;
  height: 410px;
  float: left;
}
}
@media screen and (min-height: 700px) {
 .fixedTable-body {
  overflow: auto;
  width: 100%;
  height: 510px;
  float: left;
}
</style>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	@include('flash-message')
	<!-- Content Header (Page header) -->
	<div class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6">
					<h1 class="m-0 text-dark">Rekap Absen</h1>
				</div><!-- /.col -->
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><a href="{!! route('admin') !!}">Admin</a></li>
						<li class="breadcrumb-item active">Rekap Absen</li>
					</ol>
				</div><!-- /.col -->
			</div><!-- /.row -->
		</div><!-- /.container-fluid -->
	</div>
	<!-- /.content-header -->

	<!-- Main content -->
	<div class="card">
		<div class="card-header">
			<!--<h3 class="card-title">DataTable with default features</h3>-->
			<!--<a href="http://localhost/get-data/udp.php" target="_blank" title='Sync Absen' data-toggle='tooltip'><span class='fa fa-sync'></span> Sync Absen </a>-->
			<form id="cari_absen" class="form-horizontal" method="get" action="{!! route('be.previewgaji') !!}">
				{{ csrf_field() }}
				<div class="row">
					<div class="col-lg-6">
						<div class="form-group">
							<label>Karyawan</label>
							 <?php 
                            
                            	
									$sqlkaryawans="SELECT a.p_karyawan_id,a.nik,a.nama as nama_lengkap
FROM p_karyawan a
                    					WHERE 1=1 and a.active=1 order by a.nama";
        								$karyawans=DB::connection()->select($sqlkaryawans);?>
								 <select class="form-control select2" name="karyawan" style="width: 100%;" placeholder="Pilih Karyawan" required>
                                    <option value="" >Pilih Karyawan</option>
                                    <?php
                                    foreach($karyawans AS $karyawans){
                                        echo '<option value="'.$karyawans->p_karyawan_id.'">'.$karyawans->nama_lengkap.'</option>';
                                    }
                                    ?>
                                </select>
						</div>
					</div><div class="col-lg-6">
						<div class="form-group">
							<label>Periode Absen</label>
							<select class="form-control select2" name="periode_gajian" style="width: 100%;" required>
								<option value="">Pilih Periode</option>
								<?php
								foreach($periode AS $periode){
									if($periode->periode_absen_id==$periode_absen){
										echo '<option selected="selected" value="'.$periode->periode_absen_id.'">'.$periode->bulan.' - '.$periode->tahun.' - '.$periode->tipe.' - '.$periode->tgl_awal.' - '.$periode->tgl_akhir.'</option>';
									}
									else{
										echo '<option value="'.$periode->periode_absen_id.'">'.$periode->bulan.' - '.$periode->tahun.' - '.$periode->tipe.' - '.$periode->tgl_awal.' - '.$periode->tgl_akhir.'</option>';
									}
								}
								?>
							</select>
						</div>
					</div>
				</div>
				<div class="form-group row">
					<div class="col-md-8">
						<a href="{!! route('be.previewgaji') !!}" class="btn btn-primary"><span class="fa fa-sync"></span> Reset</a>
						<button type="submit" name="Cari" class="btn btn-primary" value="Cari"><span class="fa fa-search"></span> Cari</button>
						
						
					</div>
				</div>
			</form>
		</div>
		<div class="card-body d-none">
		<div class="row">
		<div class="col-sm-12">
	
		<table  border="0" style="width: 100%;outline: none; border:0">
			<tr>
				<td>Entitas</td>
				<td><?= $data["entitas"];;?></td>
				
				<td>Nama Karyawan</td>
				<td></td>
			</tr>
			
			<tr>
				<td>No</td>
				<td><?= $data["No"];;?></td>
				
				<td></td>
				<td></td>
			</tr>
			<tr>
				
				
				<td>Tgl Cetak</td>
				<td><?= $data["tglcetak"];;?></td>
				
				<td>Grade</td>
				<td></td>
			</tr>
			
			
			<tr>
				
				
			</tr>
		</table>
		<table>
			<tr>
				<td>Hari Kerja</td>
				<td><?= $data["hari_kerja"];;?></td>
				
				<td>Gaji Pokok</td>
				<td><?= $data["gaji_pokok"];;?></td>
				
				<td>Potongan Telat/Izin</td>
				<td><?= $data["potongan_telat"];;?></td>
				
				
			</tr>
			
			<tr>
				<td>Hari Absen</td>
				<td><?= $data["hari_absen"];;?></td>
				
				<td>Tunjangan Grade</td>
				<td><?= $data["tunjangan_grade"];;?></td>
				
				<td>Potongan Absen</td>
				<td><?= $data["potongan_absen"];;?></td>
				
				
			</tr>
				<tr>
					<td>Cuti</td>
					<td><?= $data["cuti"];;?></td>
					
					<td>Tunjangan BPJS Kesehatan</td>
					<td><?= $data["tunj_bpjs"];;?></td>
					
					<td>Potongan Tidak Fingerprint</td>
					<td><?= $data["potongan_tidak_fingerprint"];;?></td>
					
					
				</tr><tr>
				<td>Izin Hitung Kerja</td>
				<td><?= $data["izin_hitung_kerja"];;?></td>
				
				<td>Tunjangan BPJS Ketenaga Kerjaan</td>
				<td><?= $data["tunjangan_bpjsket"];;?></td>
				
				<td>Iuran BPJS Kesehatan</td>
				<td><?= $data["iuran_bpjskes"];;?></td>
				
				
			</tr><tr>
				<td>Izin Potongan Tanpa Keterangan</td>
				<td><?= $data["izin_hitung_kerja"];;?></td>
				
				<td>Tunjangan Kost</td>
				<td><?= $data["tunjangan_kost"];;?></td>
				
				<td>Iuran BPJS Ketenagakerjaan</td>
				<td><?= $data["iuran_bpjs_ket"];;?></td>
				
				
			</tr><tr>
				<td>Sakit</td>
				<td><?= $data["sakit"];;?></td>
				
				<td>Lembur</td>
				<td><?= $data["lembur"];;?></td>
				
				<td>Sewa Kost</td>
				<td><?= $data["sewa_kost"];;?></td>
				
				
			</tr><tr>
				<td>Jam Lembur</td>
				<td><?= $data["jam_lembur"];;?></td>
				
				<td>Koreksi(+)</td>
				<td><?= $data["koreksi"];;?></td>
				
				<td>Pajak</td>
				<td><?= $data["pajak"];;?></td>
				
				
			</tr><tr>
				<td></td>
				<td></td>
				
				<td></td>
				<td></td>
				
				<td>Kperasi KBB</td>
				<td><?= $data["koperasi_kbb"];;?></td>
				
				
			</tr><tr>
				<td></td>
				<td></td>
				
				<td></td>
				<td></td>
				
				<td>Kperasi ASA</td>
				<td><?= $data["koperasi_asa"];;?></td>
				
				
			</tr><tr>
				<td></td>
				<td></td>
				
				<td></td>
				<td></td>
				
				<td> Zakat</td>
				<td><?= $data["zakat"];;?></td>
				
				
			</tr><tr>
				<td></td>
				<td></td>
				
				<td></td>
				<td></td>
				
				<td>Infaq</td>
				<td><?= $data["infaq"];;?></td>
				
				
			</tr><tr>
				<td></td>
				<td></td>
				
				<td></td>
				<td></td>
				
				<td>Koreksi(-)</td>
				<td><?= $data["koreksi_min"];;?></td>
				
				
			</tr><tr>
				<td></td>
				<td></td>
				
				<td></td>
				<td></td>
				
				<td></td>
				<td></td>
				
				
			</tr><tr>
				<td></td>
				<td></td>
				
				<td>Total Pendapatan</td>
				<td></td>
				
				<td>Total Potongan</td>
				<td></td>
				
				
			</tr>
			
		</table>
			
		</div>
		</div>
		</div>
		<!-- /.card-body -->
	</div>
	<?php if(!empty($request->get('periode_gajian') and $request->get('karyawan'))){?>
	<div class="content container-fluid">
				
					<!-- Page Title -->
					<div class="row">
						<div class="col-sm-5 col-4">
							<h4 class="page-title">Preview Gaji</h4>
						</div>
						<div class="col-sm-7 col-8 text-right m-b-30">
							<div class="btn-group btn-group-sm">
								<button class="btn btn-white">Edit</button>
								<button class="btn btn-white">Approval</button>
								
							</div>
						</div>
					</div>
					<!-- /Page Title -->
					
					<div class="row">
						<div class="col-md-12">
							<div class="card-box">
								<h4 class="payslip-title">Preview Gaji	</h4>
								<div class="row">
									<div class="col-sm-6 m-b-20">
										<img src="assets/img/logo.png" class="inv-logo" alt="">
										<ul class="list-unstyled mb-0">
											<li><?= $data["entitas"];;?></li>
											<li></li>
											<li><?= $data["alamat_entitas"];;?></li>
										</ul>
									<br>
										<ul class="list-unstyled">
											<li><h5 class="mb-0"><strong><?= $data["nama"];;?></strong></h5></li>
											<li><span>Jabatan : <?= $data["jabatan"];;?></span></li>
											<li> <?= $data["grade"];;?></li>
						
										</ul>
									</div>
									<div class="col-sm-6 m-b-20">
										<div class="invoice-details">
											<table>
			
			</table>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-sm-12">
										<div>
											<h4 class="m-b-10"><strong>Summary</strong></h4>
											<table class="table table-bordered">
												<tbody>
													<tr>
				<td>Hari Kerja</td>
				<td><?= $data["hari_kerja"];;?></td>
			</tr>
				<tr>
				<td>Sakit</td>
				<td><?= $data["sakit"];;?></td>
				</tr>
			
			<tr>
				<td>Hari Absen</td>
				<td><?= $data["hari_absen"];;?></td>
				</tr>
				<tr>
					<td>Cuti</td>
					<td><?= $data["cuti"];;?></td>
					</tr><tr>
				<td>Izin Hitung Kerja</td>
				<td><?= $data["izin_hitung_kerja"];;?></td>
					
			</tr><tr>
				<td>Izin Potongan Tanpa Keterangan</td>
				<td><?= $data["izin_hitung_kerja"];;?></td>
				</tr><tr>
				<td>Jam Lembur</td>
				<td><?= $data["jam_lembur"];;?></td>
				
			</tr>
												</tbody>
											</table>
										</div>
									</div>
									<div class="col-sm-6">
										<div>
											<h4 class="m-b-10"><strong>Earnings</strong></h4>
											
				
				
				
											<table class="table table-bordered">
												<tbody>
													<tr>
														<td><strong>Gaji Pokok</strong> <span class="float-right"><?= $help->rupiah($data["gaji_pokok"]);;?></span></td>
													</tr>
													<tr>
														<td><strong>Tunjangan Grade</strong> <span class="float-right"><?= $help->rupiah($data["tunjangan_grade"]);;?></span></td>
													</tr>
													<tr>
														<td><strong>Tunjangan BPJS Kesehatan</strong> <span class="float-right"><?= $help->rupiah($data["tunj_bpjs"]);;?></span></td>
													</tr>
													<tr>
														<td><strong>Tunjangan BPJS Ketenaga Kerjaan</strong> <span class="float-right"><?= $help->rupiah($data["tunjangan_bpjsket"]);;?></span></td>
													</tr><tr>
														<td><strong>Tunjangan Kost</strong> <span class="float-right"><?= $help->rupiah($data["tunjangan_kost"]);;?></span></td>
													</tr><tr>
														<td><strong>Lembur</strong> <span class="float-right"><?= $help->rupiah($data["lembur"]);;?></span></td>
													</tr><tr>
														<td><strong>Koreksi(+)</strong> <span class="float-right"><?= $help->rupiah($data["koreksi"]);;?></span></td>
													</tr>
													<tr>
														<td><strong>Total</strong> <span class="float-right"><strong><?=$help->rupiah($earn=$data["gaji_pokok"]+$data["tunjangan_grade"]+$data["tunj_bpjs"]+$data["tunjangan_kost"]+$data["tunjangan_bpjsket"]+$data["lembur"]+$data["koreksi"]);?></strong></span></td>
													</tr>
												</tbody>
											</table>
										</div>
									</div>
									<div class="col-sm-6">
										<div>
											<h4 class="m-b-10"><strong>Deductions</strong></h4>
											<table class="table table-bordered">
												<tbody>
													<tr>
														<td><strong>Potongan Telat/Izin</strong> <span class="float-right"><?= $help->rupiah($data["potongan_telat"]);;?></span></td>
													</tr>
													<tr>
														<td><strong>Potongan Absen</strong> <span class="float-right"><?= $help->rupiah($data["potongan_absen"]);;?></span></td>
													</tr>
													<tr>
														<td><strong>Potongan Tidak Fingerprint</strong> <span class="float-right"><?= $help->rupiah($data["potongan_tidak_fingerprint"]);;?></span></td>
													</tr>
													<tr>
														<td><strong>Iuran BPJS Kesehatan</strong> <span class="float-right"><?= $help->rupiah($data["iuran_bpjskes"]);;?></span></td>
													</tr><tr>
														<td><strong>Iuran BPJS Ketenagakerjaan</strong> <span class="float-right"><?= $help->rupiah($data["iuran_bpjs_ket"]);;?></span></td>
													</tr><tr>
														<td><strong>Sewa Kost</strong> <span class="float-right"><?= $help->rupiah($data["sewa_kost"]);;?></span></td>
													</tr><tr>
														<td><strong>Pajak</strong> <span class="float-right"><?= $help->rupiah($data["pajak"]);;?></span></td>
													</tr><tr>
														<td><strong>Koperasi KBB</strong> <span class="float-right"><?= $help->rupiah($data["koperasi_kbb"]);;?></span></td>
													</tr><tr>
														<td><strong>Kperasi ASA</strong> <span class="float-right"><?= $help->rupiah($data["koperasi_asa"]);;?></span></td>
													</tr><tr>
														<td><strong>Zakat</strong> <span class="float-right"><?= $help->rupiah($data["zakat"]);;?></span></td>
													</tr><tr>
														<td><strong>Infaq</strong> <span class="float-right"><?= $help->rupiah($data["infaq"]);;?></span></td>
													</tr><tr>
														<td><strong>Koreksi(-)</strong> <span class="float-right"><?= $help->rupiah($data["koreksi_min"]);;?></span></td>
													</tr>
													<tr>
														<td><strong>Total Deductions</strong> <span class="float-right"><strong><?=$deduc = $data["potongan_telat"]+$data["potongan_absen"]+$data["potongan_tidak_fingerprint"]+$data["iuran_bpjskes"]+$data["iuran_bpjs_ket"]+$data["sewa_kost"]+$data["pajak"]+$data["koperasi_kbb"]+$data["koperasi_asa"]+$data["zakat"]+$data["infaq"]+$data["koreksi_min"]?></strong></span></td>
													</tr>
												</tbody>
											</table>
										</div>
									</div>
									<div class="col-sm-12">
										<p><strong>Take Home Pay: <?=$help->rupiah($earn+$deduc);?></strong>(<?=$help->terbilang($earn+$deduc);?>)</p>
									</div>
								</div>
							</div>
						</div>
					</div>
                </div>
	<?php }?>			<!-- /Page Content -->
				
            </div>
	<!-- /.card -->
	<!-- /.card -->
	<!-- /.content -->
</div>
<!-- /.content-wrapper -->
@endsection

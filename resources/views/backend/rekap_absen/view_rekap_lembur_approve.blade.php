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
	td {
		border: 1px solid #040404;
	}
	tr.odd > td {
		background-color: #E3F2FD;
	}

	tr.even > td {
		background-color: #BBDEFB;
	}
</style>
<style>
	.trr {
		background-color: #0099FF;
		color: #ffffff;
		align: center;
		padding: 10px;
		height: 20px;
	}
	td {
		border: 1px solid #040404;
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
		height: 70px;
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
	table th{
		text-align: center;
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
					<h1 class="m-0 text-dark">Rekap Lembur</h1>
				</div><!-- /.col -->
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><a href="{!! route('admin') !!}">Admin</a></li>
						<li class="breadcrumb-item active">Rekap Lembur</li>
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
		    	<?=view('backend.rekap_absen.filter',compact('jabatan','departemen','periode','periode_absen','rekapget','entitas','request'));?>
		</div>
		<div class="card-body">
			<div class="fixedTable" id="demo">
				<header class="fixedTable-header">
					<table class="table table-bordered">
						<thead>
							<tr>

								<th rowspan="2">No </th>
								<th rowspan="2">Nama</th>

								<th rowspan="2">Entitas</th>
								<th rowspan="2">Jabatan</th>
								<?php
								
								 $date = $tgl_awal;
								for ($i = 0; $i <= $help->hitunghari($tgl_awal,$tgl_akhir); $i++) {
									echo ' <th>'.$help->nama_hari($date).'

									</th>';
									$date = $help->tambah_tanggal($date,1);
								}
								?>


								<th colspan="4" style="text-align: center;">HARI KERJA</th>
								<th colspan="6" style="text-align: center;">HARI LIBUR</th>
								<th rowspan="2">TOTAL</th>
								<th rowspan="2"  style="text-align: center; font-size:12px">TOTAL PROPOSIONAL</th>
								<th rowspan="2"  style="text-align: center; font-size:12px">TOTAL PENGAJUAN</th>
								<th rowspan="2" style="text-align: center; font-size:12px">TOTAL APPROVE</th>
								<th rowspan="2" style="text-align: center; font-size:12px">TOTAL PENDING</th>
								<th rowspan="2" style="text-align: center; font-size:12px">TOTAL TOLAK</th>
							</tr>
							<tr>


								<?php
								$date = $tgl_awal;
								for ($i = 0; $i <= $help->hitunghari($tgl_awal,$tgl_akhir); $i++) {
									echo ' <th>'.$help->tgl_indo_short_no_tahun	($date).'</th>';
									$date = $help->tambah_tanggal($date,1);
								}
								?>


								<th style="width: 30px;min-width: 30px;max-width: 30px;font-size: 8px">1 jam</th>
								<th style="width: 30px;min-width: 30px;max-width: 30px;font-size: 8px">>=2 jam</th>
								<th style="width: 30px;min-width: 30px;max-width: 30px;font-size: 8px">COUNT</th>
								<th>SUM</th>
								<th style="width: 30px;min-width: 30px;max-width: 30px;font-size: 8px">8 jam</th>
								<th style="width: 30px;min-width: 30px;max-width: 30px;font-size: 8px">9 jam</th>
								<th style="width: 30px;min-width: 30px;max-width: 30px;font-size: 8px">>=10 jam</th>
								<th style="width: 30px;min-width: 30px;max-width: 30px;font-size: 8px">COUNT</th>
								<th>SUM</th>
							</tr>
						</thead>
					</table>
				</header>
				<aside class="fixedTable-sidebar" style="display: none">
					<table class="table table-bordered">
						<tbody>

						</tbody>
					</table>
				</aside>
				<div class="fixedTable-body">
					<table class="table table-bordered">
						<tbody>
							<?php $no=0;
				// 			if(Auth::user()->role==-1){
				// 			    echo '<pre>';
				// 		print_r($rekap['lembur']);die;
				// 			}
							?>
							@if(!empty($list_karyawan))
							@foreach($list_karyawan as $list_karyawan)
							<?php $no++;
							$total['<8 jam'] =0;
							$total['8 jam'] =0;
							$total['9 jam'] =0;
							$total['>=10 jam'] =0;
							$total['COUNT Libur'] =0;
							$total['SUM Libur'] =0;
							$total['1jam'] =0;
							$total['>=2jam'] =0;
							$total['COUNT Kerja'] =0;
							$total['SUM Kerja'] =0;
							?>
							<tr>
								<td>{!! $no !!}</td>

								<td>{!! $list_karyawan->nama !!}</td>
								<td>{!! $list_karyawan->nmlokasi !!}</td>
								<td>{!! $list_karyawan->nmjabatan !!}</td>
								<?php
								$date = $tgl_awal;
								$total_all = 0;
								//print_r($rekap['lembur']['Proposional']['approve']);
									for ($i = 0; $i <= $help->hitunghari($tgl_awal,$tgl_akhir); $i++) {
									$id_karyawan = $list_karyawan->p_karyawan_id;
									
									if (!$list_karyawan->is_karyawan_shift)
										$bool_hari_libur = !(in_array($help->nama_hari($date),array('Minggu','Sabtu')) or in_array($date,$hari_libur) or isset($hari_libur_shift[$date][$id_karyawan]) );
									else
										$bool_hari_libur =!(isset($hari_libur_shift[$date][$id_karyawan])) ;
									
									
									if (!$bool_hari_libur)
										$color = 'Style="background:red;color:white"';
									else
										$color='';

									if (isset($rekap['lembur']['approve'][$list_karyawan->p_karyawan_id][$date]['lama'])) {
										$total_all += $rekap['lembur']['approve'][$list_karyawan->p_karyawan_id][$date]['lama'];
										
										
										if (!$bool_hari_libur) {
											$lama = $rekap['lembur']['approve'][$list_karyawan->p_karyawan_id][$date]['lama'];
											if ($lama>8) {
												$total['8 jam'] +=8; $lama-=8;
											} else if ($lama<=8) {
												$total['8 jam'] +=$lama; $lama-=$lama;
											}
											if ($lama) {

												$total['9 jam'] +=1;$lama-=1;
											}
											if ($lama)
												$total['>=10 jam'] +=$lama;


											$total['COUNT Libur'] +=1;
											$total['SUM Libur'] +=$rekap['lembur']['approve'][$list_karyawan->p_karyawan_id][$date]['lama'];
										} else {
											$lama = $rekap['lembur']['approve'][$list_karyawan->p_karyawan_id][$date]['lama'];
											$total['1jam'] +=1;$lama-=1;
											if ($lama)
												$total['>=2jam'] +=$lama;


											$total['COUNT Kerja'] +=1;
											$total['SUM Kerja'] +=$rekap['lembur']['approve'][$list_karyawan->p_karyawan_id][$date]['lama'];
										}
										echo ' <td '.$color.'>
												
												'.$rekap['lembur']['approve'][$list_karyawan->p_karyawan_id][$date]['lama'].' Jam
													<br> '.$rekap['lembur']['approve'][$list_karyawan->p_karyawan_id][$date]['jam_awal'].'
														 '.($rekap['lembur']['approve'][$list_karyawan->p_karyawan_id][$date]['jam_akhir']?' s/d '.$rekap['lembur']['approve'][$list_karyawan->p_karyawan_id][$date]['jam_akhir']:'').'
														<br>  '.$rekap['lembur']['approve'][$list_karyawan->p_karyawan_id][$date]['keterangan'].'
												</td >';
									} else if (isset($rekap['lembur']['Proposional']['approve'][$list_karyawan->p_karyawan_id][$date]['lama'])) {
										echo '<td style="background:purple;color:white"> Lembur Proposional </td>';
									} else {

										echo '<td '.$color.'>-</td>';
									}
									$date = $help->tambah_tanggal($date,1);
								}
								?>

								<td style="width: 30px;min-width: 30px;max-width: 30px;">{!!$total['1jam']!!}</td>
								<td style="width: 30px;min-width: 30px;max-width: 30px;">{!!$total['>=2jam']!!}</td>
								<td style="width: 30px;min-width: 30px;max-width: 30px;">{!!$total['COUNT Kerja']!!}</td>
								<td>{!!$total['SUM Kerja']!!} Jam </td>
								<td style="width: 30px;min-width: 30px;max-width: 30px;">{!!$total['8 jam']!!}</td>
								<td style="width: 30px;min-width: 30px;max-width: 30px;">{!!$total['9 jam']!!}</td>
								<td style="width: 30px;min-width: 30px;max-width: 30px;">{!!$total['>=10 jam']!!}</td>
								<td style="width: 30px;min-width: 30px;max-width: 30px;">{!!$total['COUNT Libur']!!}</td>
								<td>{!!$total['SUM Libur']!!} Jam</td>
								<td>{!!$total_all!!} Jam</td>
								<?php 
								
								?>
								<td><?=isset($rekap['lembur']['total_lembur'][$list_karyawan->p_karyawan_id])? $rekap['lembur']['total_lembur'][$list_karyawan->p_karyawan_id]['total_lembur_proposional']:0; ?> Hari</td>
								<td><?=isset($rekap['lembur']['total_lembur'][$list_karyawan->p_karyawan_id])? $rekap['lembur']['total_lembur'][$list_karyawan->p_karyawan_id]['total_pengajuan']:0; ?> Jam</td>
								<td><?=isset($rekap['lembur']['total_lembur'][$list_karyawan->p_karyawan_id])? $rekap['lembur']['total_lembur'][$list_karyawan->p_karyawan_id]['total_approve']:0; ?> Jam</td>
								<td><?=isset($rekap['lembur']['total_lembur'][$list_karyawan->p_karyawan_id])? $rekap['lembur']['total_lembur'][$list_karyawan->p_karyawan_id]['total_pending']:0; ?> Jam</td>
								<td><?=isset($rekap['lembur']['total_lembur'][$list_karyawan->p_karyawan_id])? $rekap['lembur']['total_lembur'][$list_karyawan->p_karyawan_id]['total_tolak']:0; ?> Jam</td>


							</tr>
							@endforeach
							@endif

						</tbody>
					</table>
				</div>
			</div>

		</div>
		<!-- /.card-body -->
	</div>
	<!-- /.card -->
	<!-- /.card -->
	<!-- /.content -->
</div>
<!-- /.content-wrapper -->
@endsection

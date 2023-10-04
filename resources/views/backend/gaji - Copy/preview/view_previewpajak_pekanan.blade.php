@extends('layouts.appsA')

@section('content')

<style>
	html {
		box-sizing: border-box;
	}
	*,
	*:before,
	*:after {
		box-sizing: inherit;
	}
	.intro {
		max-width: 1280px;
		margin: 1em auto;
	}
	.table-scroll {
		position: relative;
		width: 100%;
		z-index: 1;
		margin: auto;
		overflow: auto;
		height: 655px;
	}
	.table-scroll table {
		width: 100%;
		min-width: 1280px;
		margin: auto;
		border-collapse: separate;
		border-spacing: 0;
	}
	.table-wrap {
		position: relative;
	}
	.table-scroll td {
		min-width: 120px
	}
	.table-scroll th,

	.table-scroll td {
		padding: 5px 10px;
		border: 1px solid #000;
		background: #fff;
		vertical-align: top;
	}
	.table-scroll thead th {
		background: #333;
		color: #fff;
		position: -webkit-sticky;
		position: sticky;
		top: 0;
	}
	.table-scroll thead tr.secondline th {
		top: 35px;
	}
	/* safari and ios need the tfoot itself to be position:sticky also */
	.table-scroll tfoot,
	.table-scroll tfoot th,
	.table-scroll tfoot td {
		position: -webkit-sticky;
		position: sticky;
		bottom: 0;
		background: #666;
		color: #fff;
		z-index: 4;
	}

	th.statictable:first-child {
		position: -webkit-sticky;
		position: sticky;
		left: 0;
		z-index: 2;
		background: #ccc;
	}

	thead th.statictable:first-child{
		background: #333;
		position: sticky;
		left: 0;
		z-index: 3;
	}
	thead th:first-child,
	tfoot th:first-child {
		z-index: 5;
	}

</style>

<div class="content-wrapper">
	@include('flash-message')
	<!-- Content Header (Page header) -->
	<div class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6">
					<h1 class="m-0 text-dark">Generate, Preview, Aproval, dan Ajuan  Gaji</h1>
				</div><!-- /.col -->
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><a href="{!! route('admin') !!}">Admin</a></li>
						<li class="breadcrumb-item active">Generate, Preview, Aproval, dan Ajuan Gaji</li>
					</ol>
				</div><!-- /.col -->
			</div><!-- /.row -->
		</div><!-- /.container-fluid -->
	</div>
	<!-- /.content-header -->

	<!-- Main content -->
	<form id="cari_absen" class="form-horizontal" method="get" action="{!! route('be.previewgaji',$data['page']) !!}">
		<div class="card">
			<div class="card-header">
				<!--<h3 class="card-title">DataTable with default features</h3>-->
				<a href="{!! route('be.tambah_generate') !!}" target="_blank" title='Sync Absen' data-toggle='tooltip'><span class='fa fa-plus'></span> Tambah Generate Gaji </a>
				<a href="{!! route('be.generategaji') !!}" target="_blank" title='Sync Absen' data-toggle='tooltip'><span class='fa fa-plus'></span> Edit Data  </a>
			</div>
			<?php 
			echo  view('backend.gaji.preview.filter',compact('data','entitas','user','id_prl','help','periode','periode_absen','request','list_karyawan'));
			?>
           
			@if(!empty($list_karyawan))
			<div class="card-body">
           
				<div id="table-scroll" class="table-scroll">
					<table id="main-table" class="main-table">
						<thead>
							<tr>
							
							

								<th rowspan="2" class="statictable" >Nama</th>
								<th rowspan="2" style="width: 30px;max-width: 30px;min-width: 30px">No </th>
								
								<th rowspan="2">NIP</th>
								<th rowspan="2">Perusahaan</th>
								<th rowspan="2">MASA KERJA</th>
								
											  		
		


								<th rowspan="2" style="font-size: 12px">TOTAL PENDAPATAN</th>
								<th rowspan="2" style="font-size: 12px">TOTAL TUNJANGAN</th>
								<th rowspan="2" style="font-size: 12px">TOTAL POTONGAN</th>
								<th rowspan="2">TOTAL</th>
							</tr>
							
						</thead>
						<tbody>
							<?php $no=0;
							$gaji_entitas = array();
                            $total['Total Pendapatan'] = 0;
                            $total['Total Pendapatan']=0;
								$total['GAJI TOTAL PER BULAN']=0;
								$total['UPAH LEMBUR']=0;
								$total['JUMLAH PENDAPATAN']=0;
								$total['KOST']=0;
								$total['KOREKSI (+)']=0;
								$total['JUMLAH TUNJANGAN']=0;
								$total['P.TELAT']=0;
								$total['SEWA KOST']=0;
								$total['TIDAK FINGERPRINT']=0;
								$total['PM']=0;
								$total['KOPERASI KKB']=0;
								$total['KOPERASI ASA']=0;
								$total['ZAKAT']=0;
								$total['INFAQ']=0;
								$total['KOREKSI (-)']=0;
								$total['JUMLAH POTONGAN']=0;
								$total['TOTAL PENDAPATAN']=0;
								$total['THP']=0;
							?>
							@foreach($list_karyawan as $list_karyawan)
							<?php $no++;	
							 $color = '';
							 if($list_karyawan->pajak_onoff){
							 	
                            	if($sudah_appr[$list_karyawan->m_lokasi_id][$list_karyawan->pajak_onoff])
                            		$color = 'background: orange;color: white;';
                            	if($sudah_appr_keuangan[$list_karyawan->m_lokasi_id][$list_karyawan->pajak_onoff])
	                            	$color = 'background: green;color: white;';
							 }
							?>
							<tr>
								<th style="font-size: 11px;<?=$color?>" class="statictable">{!! $list_karyawan->nama_lengkap !!}</th>
								<td style="width: 50px;max-width: 50px;min-width: 50px">{!! $no !!}</td> 
								<td>{!! $list_karyawan->nik !!}</td>
								<td>{!! $list_karyawan->nmlokasi !!} {!! $list_karyawan->pajak_onoff !!}</td>
								<td style="font-size: 10px">{!! $list_karyawan->umur !!}</td>
                                <?php 
                                $ha= isset($data[$list_karyawan->p_karyawan_id]['Hari Absen'])?$data[$list_karyawan->p_karyawan_id]['Hari Absen']:0;
                                
								$uh=isset($data[$list_karyawan->p_karyawan_id]['Upah Harian'])?$data[$list_karyawan->p_karyawan_id]['Upah Harian']:0;  $help->rupiah2($gapok = $ha*$uh);
								$help->rupiah2(isset($data[$list_karyawan->p_karyawan_id]['Lembur'])?$lembur=$data[$list_karyawan->p_karyawan_id]['Lembur']:$lembur=0);
								$help->rupiah2(isset($data[$list_karyawan->p_karyawan_id]['Tunjangan Kost'])?$tunkost=$data[$list_karyawan->p_karyawan_id]['Tunjangan Kost']	:$tunkost=0);
								$help->rupiah2(isset($data[$list_karyawan->p_karyawan_id]['Koreksi(+)'])?$korekplus=$data[$list_karyawan->p_karyawan_id]['Koreksi(+)']:$korekplus=0);
								$help->rupiah2($tunjangan = $tunkost+$korekplus);
								     
								$help->rupiah2(isset($data[$list_karyawan->p_karyawan_id]['Potongan Telat'])?$telat= $data[$list_karyawan->p_karyawan_id]['Potongan Telat']:$telat=0);
								$help->rupiah2(isset($data[$list_karyawan->p_karyawan_id]['Sewa Kost'])?$sewakost = $data[$list_karyawan->p_karyawan_id]['Sewa Kost']:$sewakost =0);
								$help->rupiah2(isset($data[$list_karyawan->p_karyawan_id]['Potongan Fingerprint'])?$finger= $data[$list_karyawan->p_karyawan_id]['Potongan Fingerprint']:$finger=0);
								$help->rupiah2(isset($data[$list_karyawan->p_karyawan_id]['Potongan Pulang Mendahului'])?$potpm= $data[$list_karyawan->p_karyawan_id]['Potongan Pulang Mendahului']:$potpm=0);
								$help->rupiah2(isset($data[$list_karyawan->p_karyawan_id]['Koreksi(-)'])?$korekmin = $data[$list_karyawan->p_karyawan_id]['Koreksi(-)']:$korekmin =0);?>
                               
								<td style="font-size:13px"><?=  $help->rupiah2(($gapok+$lembur));?></td>
								<td style="font-size:13px"><?=  $help->rupiah2(($tunjangan));?></td>
								<td style="font-size:13px"><?=  $help->rupiah2($potongan = $telat+$finger+$potpm);?></td>
								<td style="font-size:13px"><?=  $help->rupiah2(($gapok+$tunjangan+$lembur)-$potongan);?></td>
								<?php 
								$total['Total Pendapatan']+=$ha*$uh;
								$total['GAJI TOTAL PER BULAN']+=$uh*22;
								$total['UPAH LEMBUR']+=$lembur;
								$total['JUMLAH PENDAPATAN']+=$gapok+$lembur;
								$total['KOST']+=$tunkost;
								$total['KOREKSI (+)']+=$korekplus;
								$total['JUMLAH TUNJANGAN']+=$tunjangan;
								$total['P.TELAT']+=$telat;
								$total['SEWA KOST']+=$sewakost;
								$total['TIDAK FINGERPRINT']+=$finger;
								$total['PM']+=$potpm;
								//$total['KOPERASI KKB']+=$kkp;
								//$total['KOPERASI ASA']+=$asa;
								//$total['ZAKAT']+=$zakat;
								//$total['INFAQ']+=$infaq;
								$total['KOREKSI (-)']+=$korekmin;
								$total['JUMLAH POTONGAN']+=$potongan;
								$total['TOTAL PENDAPATAN']+=($gapok+$tunjangan+$lembur);
								$total['THP']+=($gapok+$tunjangan+$lembur)-$potongan;
								if(isset($gaji_entitas[$list_karyawan->nmlokasi])){
									$gaji_entitas[$list_karyawan->nmlokasi] += ($gapok+$tunjangan+$lembur)-$potongan;
								}else{
									$gaji_entitas[$list_karyawan->nmlokasi] = ($gapok+$tunjangan+$lembur)-$potongan;
									
								}
								
								?>
								
								
							</tr>
							@endforeach
							
							
						</tbody>
  
					</table>
					
					
				</div>

            	
TOTAL GAJI PER ENTITAS
					<table style="width: 100%" class="table table-striped">
            			<tbody>
            			<?php 
            			
            			foreach($gaji_entitas as $key => $value){
						 	echo '<tr>
            				<th>'.$key.'</th>
            				<td>'.$help->rupiah($value).'</td>
            			</tr>';
						}
            			?>
            			
            			
            		</tbody></table>              
					@endif
			</div>
			<!-- /.card-body -->
		</div>
	</form>
	<!-- /.card -->
	<!-- /.card -->
	<!-- /.content -->
</div>
<!-- /.content-wrapper -->
@endsection

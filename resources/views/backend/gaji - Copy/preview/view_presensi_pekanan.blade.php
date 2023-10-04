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
		top: 33px;
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

	thead th.statictable:first-child,
	tfoot th.statictable:first-child{
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
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	@include('flash-message')
	<!-- Content Header (Page header) -->
	<div class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6">
					<h1 class="m-0 text-dark">Generate,Preview, Edit, dan Approve Gaji</h1>
				</div><!-- /.col -->
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><a href="{!! route('admin') !!}">Admin</a></li>
						<li class="breadcrumb-item active">Generate,Preview, Edit, dan Approve Gaji</li>
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
			
			<?php 
			echo  view('backend.gaji.preview.filter',compact('data','entitas','user','id_prl','help','periode','periode_absen','request','list_karyawan'));
			?>
		
           
		@if(!empty($list_karyawan))
		<div class="card-body">
			<div id="table-scroll" class="table-scroll">
				<table id="main-table" class="main-table">
					<thead>
						<tr>
							<th rowspan="2" class="statictable">NAMA PEGAWAI</th>
							<th rowspan="2">No </th>
							<th rowspan="2">GAJI TOTAL PERBULAN</th>
							<th rowspan="2">TOTAL KEHADIRAN</th>
								
                       
                        
							<th colspan="8">Keterangan</th>
							<th colspan="4">JAM LEMBUR HARI KERJA</th>
							<th colspan="5">JAM LEMBUR HARI LIBUR</th>
							<th rowspan="2">Total Lembur</th>
							<th rowspan="2">cek</th>
								
																								

						</tr><tr  class="secondline">
							
							<th style="width: 40px;min-width: 40px;max-width: 40px;font-size: 9px;vertical-align: top;">Sakit</th>
							<th style="width: 40px;min-width: 40px;max-width: 40px;font-size: 9px;vertical-align: top;">Izin</th>
							<th style="width: 40px;min-width: 40px;max-width: 40px;font-size: 9px;vertical-align: top;">Alpa</th>
							<th style="width: 40px;min-width: 40px;max-width: 40px;font-size: 9px;vertical-align: top;">Terlambat</th>
							<th style="width: 40px;min-width: 40px;max-width: 40px;font-size: 9px;vertical-align: top;">IDT</th>
							<th style="width: 40px;min-width: 40px;max-width: 40px;font-size: 9px;vertical-align: top;">IPM</th>
							<th style="width: 40px;min-width: 40px;max-width: 40px;font-size: 9px;vertical-align: top;">PM</th>
							<th style="width: 40px;min-width: 40px;max-width: 40px;font-size: 9px;vertical-align: top;">Total</th>
								
								

							<th style="width: 40px;min-width: 40px;max-width: 40px;font-size: 9px;vertical-align: top;">1 jam</th>
							<th style="width: 40px;min-width: 40px;max-width: 40px;font-size: 9px;vertical-align: top;">>=2 jam</th>	
	

							<th style="width: 40px;min-width: 40px;max-width: 40px;font-size: 8px;vertical-align: top;">TOTAL</th>
							<th style="width: 40px;min-width: 40px;max-width: 40px;font-size: 9px;vertical-align: top;">NOMINAL</th>
								
							<th style="width: 40px;min-width: 40px;max-width: 40px;font-size: 9px;vertical-align: top;">8 jam</th>
							<th style="width: 40px;min-width: 40px;max-width: 40px;font-size: 9px;vertical-align: top;">9 jam</th>
							<th style="width: 40px;min-width: 40px;max-width: 40px;font-size: 8px;vertical-align: top;">>=10 jam</th>
								
									
	

							<th style="width: 40px;min-width: 40px;max-width: 40px;font-size: 9px;vertical-align: top;">TOTAL</th> 
								
							<th style="width: 40px;min-width: 40px;max-width: 40px;font-size: 9px;vertical-align: top;">NOMINAL</th>
								
						</tr>
					</thead>
					<tbody>
						<?php $no=0;
						
						
						$total['Absen'] =0; 
						$total['Sakit'] =0; 
						$total['Izin']  =0; 
						$total['Alpa']  =0;  
						$total['IDT']  =0;  
						$total['IPM']  =0;  
						$total['PM']  =0;  
						$total['Terlambat']  =0; 
						$total['Total']  =0; 
						$total['1 jam'] =0;  
						$total['>=2 jam']  =0;  
						$total['TOTAL LEMBUR HARI KERJA']  =0;  
						$total['NOMINAL LEMBUR HARI KERJA']  =0;  
						$total['8 jam']  =0; 
						$total['9 jam']  =0; 
						$total['>=10 jam']  =0;  
						$total['TOTAL LEMBUR HARI LIBUR']  =0;  
						$total['NOMINAL LEMBUR HARI LIBUR']  =0; 
						$total['Total Lembur']  =0; 
						?>
						@foreach($list_karyawan as $list_karyawan)
						<?php $no++;	 
                           //print_r($data[$list_karyawan->p_karyawan_id]);    
                           //echo '<br>';
                           //echo '<br>';
						$help->rupiah(isset($data[$list_karyawan->p_karyawan_id]['Upah Harian'])?$uh=$data[$list_karyawan->p_karyawan_id]['Upah Harian']:$uh=0);
						$help->rupiah(isset($data[$list_karyawan->p_karyawan_id]['Lembur'])?$lembur=$data[$list_karyawan->p_karyawan_id]['Lembur']:$lembur=0);
						$help->rupiah(isset($data[$list_karyawan->p_karyawan_id]['Tunjangan BPJS Kesehatan'])?$tunkes=$data[$list_karyawan->p_karyawan_id]['Tunjangan BPJS Kesehatan']:$tunkes=0);
						$help->rupiah(isset($data[$list_karyawan->p_karyawan_id]['Tunjangan BPJS Ketenaga Kerjaan'])?$tunket=$data[$list_karyawan->p_karyawan_id]['Tunjangan BPJS Ketenaga Kerjaan']:$tunket=0);
						$help->rupiah(isset($data[$list_karyawan->p_karyawan_id]['Koreksi(+)'])?$korekplus=$data[$list_karyawan->p_karyawan_id]['Koreksi(+)']:$korekplus=0);
						$help->rupiah(isset($data[$list_karyawan->p_karyawan_id]['Tunjangan Kost'])?$tunkost=$data[$list_karyawan->p_karyawan_id]['Tunjangan Kost']	:$tunkost=0);
						$help->rupiah($tunjangan = $tunkost+$korekplus+$tunket+$tunkes+$lembur );
                               
						$help->rupiah(isset($data[$list_karyawan->p_karyawan_id]['Potongan Telat/ Ijin'])?$telat= $data[$list_karyawan->p_karyawan_id]['Potongan Telat/ Ijin']:$telat=0);
						$help->rupiah(isset($data[$list_karyawan->p_karyawan_id]['Potongan Absen'])?$absen = $data[$list_karyawan->p_karyawan_id]['Potongan Absen']:$absen =0);
						$help->rupiah(isset($data[$list_karyawan->p_karyawan_id]['Sewa Kost'])?$sewakost = $data[$list_karyawan->p_karyawan_id]['Sewa Kost']:$sewakost =0);
						$help->rupiah(isset($data[$list_karyawan->p_karyawan_id]['Iuran BPJS Kesehatan'])?$ibpjs = $data[$list_karyawan->p_karyawan_id]['Iuran BPJS Kesehatan']:$ibpjs =0);
						$help->rupiah(isset( $data[$list_karyawan->p_karyawan_id]['Iuran BPJS Ketenaga Kerjaan'])?$ibpjt = $data[$list_karyawan->p_karyawan_id]['Iuran BPJS Ketenaga Kerjaan']:$ibpjt =0);
						$help->rupiah(isset($data[$list_karyawan->p_karyawan_id]['Zakat'])?$zakat = $data[$list_karyawan->p_karyawan_id]['Zakat']:$zakat =0);
						$help->rupiah(isset($data[$list_karyawan->p_karyawan_id]['Infaq'])?$infaq = $data[$list_karyawan->p_karyawan_id]['Infaq']:$infaq =0);
						$help->rupiah(isset($data[$list_karyawan->p_karyawan_id]['Koreksi(-)'])?$korekmin = $data[$list_karyawan->p_karyawan_id]['Koreksi(-)']:$korekmin =0);
						$help->rupiah(isset($data[$list_karyawan->p_karyawan_id]['Simpanan Wajib KKB'])?$kkp = $data[$list_karyawan->p_karyawan_id]['Simpanan Wajib KKB']:$kkp =0);
						$help->rupiah(isset($data[$list_karyawan->p_karyawan_id]['Potongan Koperasi Asa'])?$asa = $data[$list_karyawan->p_karyawan_id]['Potongan Koperasi Asa']:$asa =0);
						$help->rupiah(isset($data[$list_karyawan->p_karyawan_id]['Pajak'])?$pajak = $data[$list_karyawan->p_karyawan_id]['Pajak']:$pajak =0);
						$help->rupiah($potongan = $telat+$absen+$sewakost+$ibpjs+$ibpjt+$zakat+$infaq+$korekmin+$kkp+$asa+$pajak);
						$ihk = isset($data[$list_karyawan->p_karyawan_id]['Izin Hitung Kerja'])?$data[$list_karyawan->p_karyawan_id]['Izin Hitung Kerja']:0; $ipg=isset($data[$list_karyawan->p_karyawan_id]['Izin Perjalanan Dinas'])?$data[$list_karyawan->p_karyawan_id]['Izin Perjalanan Dinas']:0;
						$cuti=0;
						
						
                            $color = '';
							 if($list_karyawan->pajak_onoff){
							 	
	                            if($sudah_appr[$list_karyawan->m_lokasi_id][$list_karyawan->pajak_onoff])
	                            	$color = 'background: orange;color: white;';
	                            if($sudah_appr_keuangan[$list_karyawan->m_lokasi_id][$list_karyawan->pajak_onoff])
	                            	$color = 'background: green;color: white;';
							 }
                            ?>
                            
						<tr>
							<th style="font-size:13px;width: 140px;<?=$color?>;min-width: 140px" class="statictable">{!! $list_karyawan->nama_lengkap !!}</th>
							<td  style="width: 40px;min-width: 40px;max-width: 40px;">{!! $no !!}</td> 
							<td style="font-size:13px">{!! $help->rupiah2($uh*22) !!}</td>
                                
                               
							<td style="width: 40px;min-width: 40px;max-width: 40px;"><?= $absen = isset($data[$list_karyawan->p_karyawan_id]['Hari Absen'])?$data[$list_karyawan->p_karyawan_id]['Hari Absen']:0;?></td>
							<td style="width: 40px;min-width: 40px;max-width: 40px;"><?= $sakit = isset($data[$list_karyawan->p_karyawan_id]['Sakit'])?$data[$list_karyawan->p_karyawan_id]['Sakit']:0;?></td>
                                
							<td style="width: 40px;min-width: 40px;max-width: 40px;"><?=$ihk+$ipg?></td>
							<td style="width: 40px;min-width: 40px;max-width: 40px;"><?= $tk = isset($data[$list_karyawan->p_karyawan_id]['Tanpa Keterangan'])?$data[$list_karyawan->p_karyawan_id]['Tanpa Keterangan']:0;?></td>
							<td style="width: 40px;min-width: 40px;max-width: 40px;"><?= $terlambat = isset($data[$list_karyawan->p_karyawan_id]['Terlambat'])?$data[$list_karyawan->p_karyawan_id]['Terlambat']:0;?></td>
							<td style="width: 40px;min-width: 40px;max-width: 40px;"><?= $idt = isset($data[$list_karyawan->p_karyawan_id]['Izin Datang Terlambat'])?$data[$list_karyawan->p_karyawan_id]['Izin Datang Terlambat']:0;?></td>
							<td style="width: 40px;min-width: 40px;max-width: 40px;"><?= $ipm = isset($data[$list_karyawan->p_karyawan_id]['Izin Pulang Mendahului '])?$data[$list_karyawan->p_karyawan_id]['Izin Pulang Mendahului ']:0;?></td>
							<td style="width: 40px;min-width: 40px;max-width: 40px;"><?= $pm = isset($data[$list_karyawan->p_karyawan_id]['Pulang Mendahului Tanpa Izin'])?$data[$list_karyawan->p_karyawan_id]['Pulang Mendahului Tanpa Izin']:0;?></td>
							<td style="width: 40px;min-width: 40px;max-width: 40px;"><?=$tk+$ihk+$ipg+$sakit?></td>
                                
							<td style="width: 40px;min-width: 40px;max-width: 40px;"><?= $lembur1= isset($data[$list_karyawan->p_karyawan_id]['Lembur 1 Jam'])?$data[$list_karyawan->p_karyawan_id]['Lembur 1 Jam']:0;?></td>
							<td style="width: 40px;min-width: 40px;max-width: 40px;"><?= $lembur2 = isset($data[$list_karyawan->p_karyawan_id]['Lembur >=2 Jam'])?$data[$list_karyawan->p_karyawan_id]['Lembur >=2 Jam']:0;?></td>
                               
							<td style="width: 40px;min-width: 40px;max-width: 40px;"><?=  $total_lembur_kerja = isset($data[$list_karyawan->p_karyawan_id]['SUM Kerja'])?$data[$list_karyawan->p_karyawan_id]['SUM Kerja']:0;?></td>
                               
							<td  style="font-size:13px"><?=  $help->rupiah2($nominal_lembur_kerja = isset($data[$list_karyawan->p_karyawan_id]['Jumlah Lembur Kerja'])?$data[$list_karyawan->p_karyawan_id]['Jumlah Lembur Kerja']:0);?></td>
							<td style="width: 40px;min-width: 40px;max-width: 40px;"><?= $lembur8=isset($data[$list_karyawan->p_karyawan_id]['Lembur 8 Jam'])?$lembur8=$data[$list_karyawan->p_karyawan_id]['Lembur 8 Jam']:$lembur8=0;?></td>
							<td style="width: 40px;min-width: 40px;max-width: 40px;"><?= $lembur9=isset($data[$list_karyawan->p_karyawan_id]['Lembur 9 Jam'])?$lembur9=$data[$list_karyawan->p_karyawan_id]['Lembur 9 Jam']:$lembur9=0;?></td>
							<td style="width: 40px;min-width: 40px;max-width: 40px;"><?= $lembur10= isset($data[$list_karyawan->p_karyawan_id]['Lembur >=10 Jam'])?$data[$list_karyawan->p_karyawan_id]['Lembur >=10 Jam']:0;?> </td>
                                
							<td style="width: 40px;min-width: 40px;max-width: 40px;"><?= $lembur8+$lembur10+$lembur9;?></td>
							<td  style="font-size:13px"><?=  $help->rupiah2(isset($data[$list_karyawan->p_karyawan_id]['Jumlah Lembur Libur'])?$data[$list_karyawan->p_karyawan_id]['Jumlah Lembur Libur']:0);?></td>
							<td style=""><?=  $help->rupiah2($lembur);?></td>
							<td><?=$absen+$sakit +$ihk+$cuti+$ipg+$tk?></td>
                                
                               
						</tr>
						<?php 
						$total['Absen'] += $absen; 
						$total['Sakit'] += $sakit; 
						$total['Izin'] += $ihk+$ipg; 
						$total['Alpa'] += $tk; 
						$total['IDT'] += $idt; 
						$total['IPM'] += $ipm; 
						$total['PM'] += $pm; 
						$total['Terlambat'] += $terlambat; 
						$total['Total'] += $tk+$ihk+$ipg+$sakit; 
						$total['1 jam'] += $lembur1; 
						$total['>=2 jam'] += $lembur2; 
						$total['TOTAL LEMBUR HARI KERJA'] += $total_lembur_kerja; 
						$total['NOMINAL LEMBUR HARI KERJA'] += $nominal_lembur_kerja; 
						$total['8 jam'] += $lembur8; 
						$total['9 jam'] += $lembur9; 
						$total['>=10 jam'] += $lembur10; 
						$total['TOTAL LEMBUR HARI LIBUR'] += $lembur8+$lembur10+$lembur9; 
						$total['NOMINAL LEMBUR HARI LIBUR'] += isset($data[$list_karyawan->p_karyawan_id]['Jumlah Lembur Libur'])?$data[$list_karyawan->p_karyawan_id]['Jumlah Lembur Libur']:0; 
						$total['Total Lembur'] = $lembur; 
						?>
						@endforeach
						<th style="font-size:13px;width: 140px;
min-width: 140px; color:red" class="statictable">TOTAL</th>
							<td  style="width: 40px;min-width: 40px;max-width: 40px;"></td> 
							<td style="font-size:13px"></td>
                                
                               
							<td style="width: 40px;min-width: 40px;max-width: 40px;"><?= $total['Absen']?></td>
							<td style="width: 40px;min-width: 40px;max-width: 40px;"><?= $total['Sakit']?></td>
                                
							<td style="width: 40px;min-width: 40px;max-width: 40px;"><?=$total['Izin']?></td>
							<td style="width: 40px;min-width: 40px;max-width: 40px;"><?= $total['Alpa'];?></td>
							<td style="width: 40px;min-width: 40px;max-width: 40px;"><?= $total['Terlambat'];?></td>
							<td style="width: 40px;min-width: 40px;max-width: 40px;"><?= $total['IDT'];?></td>
							<td style="width: 40px;min-width: 40px;max-width: 40px;"><?= $total['IPM'];?></td>
							<td style="width: 40px;min-width: 40px;max-width: 40px;"><?= $total['PM'];?></td>
							<td style="width: 40px;min-width: 40px;max-width: 40px;"><?=$total['Total']?></td>
                                
							<td style="width: 40px;min-width: 40px;max-width: 40px;"><?= $total['1 jam'];?></td>
							<td style="width: 40px;min-width: 40px;max-width: 40px;"><?= $total['>=2 jam'];?></td>
                               
							<td style="width: 40px;min-width: 40px;max-width: 40px;"><?=  $total['TOTAL LEMBUR HARI KERJA'];?></td>
                               
							<td  style="font-size:13px"><?=  $help->rupiah2($total['NOMINAL LEMBUR HARI KERJA']);?></td>
							<td style="width: 40px;min-width: 40px;max-width: 40px;"><?= $total['8 jam'];?></td>
							<td style="width: 40px;min-width: 40px;max-width: 40px;"><?= $total['9 jam'];?></td>
							<td style="width: 40px;min-width: 40px;max-width: 40px;"><?= $total['>=10 jam'];?> </td>
                                
							<td style="width: 40px;min-width: 40px;max-width: 40px;"><?= $total['TOTAL LEMBUR HARI LIBUR'];?></td>
							<td  style="font-size:13px"><?=  $help->rupiah2($total['NOMINAL LEMBUR HARI LIBUR']);?></td>
							<td style=""><?=  $help->rupiah2($total['Total Lembur']);?></td>
							<td><?=$absen+$sakit +$ihk+$cuti+$ipg+$tk?></td>
                                
                             
						@endif
							
					</tbody>
  
				</table>
 
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

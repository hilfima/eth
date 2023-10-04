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
  width:100%;
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
}.table-scroll td {
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
}.table-scroll thead tr.secondline th {
	 top: 57px;
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
  z-index:4;
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
								<th rowspan="2">Masa Kerja</th>
								<th rowspan="2">THP</th>
								<th rowspan="2">PANGKAT</th>
								<th rowspan="2"  style="width: 40px;min-width: 40px;max-width: 40px;font-size: 9px;vertical-align: top;">Total</th>
								
                       
                        
								<th colspan="6">DATA ABSENSI</th>
								<th colspan="3">POTONGAN ABSEN</th>
								<th colspan="4">JAM LEMBUR HARI KERJA</th>
								<th rowspan="2">Jumlah</th>
								<th colspan="4">JAM LEMBUR HARI LIBUR</th>
								<th rowspan="2">Jumlah</th>
								<th colspan="2">Total Lembur</th>
								<th colspan="2">TERLAMBAT</th>
								<th colspan="2">Potongan Fingerprint</th>
								<th rowspan="2">Total</th>
								<th rowspan="2">cek</th>
								
																								

							</tr><tr  class="secondline">
							
								<th style="width: 40px;min-width: 40px;max-width: 40px;font-size: 9px;vertical-align: top;">Sakit</th>
								<th style="width: 40px;min-width: 40px;max-width: 40px;font-size: 9px;vertical-align: top;">IHK</th>
								<th style="width: 40px;min-width: 40px;max-width: 40px;font-size: 9px;vertical-align: top;">CUTI</th>
								<th style="width: 40px;min-width: 40px;max-width: 40px;font-size: 9px;vertical-align: top;">IPG</th>  	  	 
								<th style="width: 40px;min-width: 40px;max-width: 40px;font-size: 9px;vertical-align: top;">TK</th>
								<th style="width: 40px;min-width: 40px;max-width: 40px;font-size: 9px;vertical-align: top;">Total</th>
								
								
								<th >IPG</th>
								<th >TK</th>
								<th >Total</th>
								
								<th style="width: 40px;min-width: 40px;max-width: 40px;font-size: 9px;vertical-align: top;">1 jam</th>
								<th style="width: 40px;min-width: 40px;max-width: 40px;font-size: 9px;vertical-align: top;">>=2 jam</th>
								<th style="width: 40px;min-width: 40px;max-width: 40px;font-size: 8px;vertical-align: top;">COUNT</th>
								<th style="width: 40px;min-width: 40px;max-width: 40px;font-size: 9px;vertical-align: top;">SUM</th>
								
								<th style="width: 40px;min-width: 40px;max-width: 40px;font-size: 9px;vertical-align: top;">8 jam</th>
								<th style="width: 40px;min-width: 40px;max-width: 40px;font-size: 9px;vertical-align: top;">9 jam</th>
								<th style="width: 40px;min-width: 40px;max-width: 40px;font-size: 8px;vertical-align: top;">>=10 jam</th>
								<th style="width: 40px;min-width: 40px;max-width: 40px;font-size: 9px;vertical-align: top;">TOTAL</th> 
								
								<th style="width: 40px;min-width: 40px;max-width: 40px;font-size: 9px;vertical-align: top;">Jam</th>
								<th>Jumlah</th>
								<th style="width: 40px;min-width: 40px;max-width: 40px;font-size: 8px;vertical-align: top;">JUMLAH</th>
								<th>TOTAL</th>
								<th style="width: 40px;min-width: 40px;max-width: 40px;font-size: 8px;vertical-align: top;">JUMLAH</th>
								<th>TOTAL</th>
								
								</tr>
						</thead>
   	<tbody>
							 <?php $no=0 ?>
							 <?php 
								$total['THP']=0;
								$total['Hari Absen']=0;
								$total['Sakit']=0;
								$total['Izin Hitung Kerja']=0;
								$total['Cuti']=0;
								$total['Izin Perjalanan Dinas']=0;
								$total['Tanpa Keterangan']=0;
								$total['Total Absen']=0;
								$total['Potongan Izin']=0;
								$total['Potongan Absen']=0;
								$total['TOTAL POTONGAN']=0;
								$total['TOTAL 1 JAM']=0;
								$total['TOTAL 2 JAM']=0;
								$total['COUNT L K']=0;
								$total['COUNT S K']=0;
								$total['Jumlah Lembur Kerja']=0;
								$total['TOTAL 8 JAM']=0;
								$total['TOTAL 9 JAM']=0;
								$total['TOTAL 10 JAM']=0;
								$total['TOTAL LEMBUR LIBUR']=0;
								$total['Jumlah Lembur Libur']=0;
								$total['Jam Lembur']=0;
								$total['Lembur']=0;
								$total['Terlambat']=0;
								$total['Potongan Telat']=0;
								$total['Fingerprint']=0;
								$total['Potongan Fingerprint']=0;
								$total['TOTAL TELAT FINGER']=0;
								
								?>
                        @foreach($list_karyawan as $list_karyawan)
                            <?php $no++;	 
                            
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
                            
                            $color = '';
							 if($list_karyawan->pajak_onoff){
							 	
                            	if($sudah_appr[$list_karyawan->m_lokasi_id][$list_karyawan->pajak_onoff])
                            		$color = 'background: orange;color: white;';
                            	if($sudah_appr_keuangan[$list_karyawan->m_lokasi_id][$list_karyawan->pajak_onoff])
	                            	$color = 'background: green;color: white;';
							 }
                            ?>
                            <tr>
                                <th style="font-size:13px;<?=$color?>" class="statictable">{!! $list_karyawan->nama_lengkap !!}</th>
                                <td>{!! $no !!}</td> 
                                <td style="font-size:13px">{!! $list_karyawan->umur !!}</td>
                                <?php 
                                $grade= isset($data[$list_karyawan->p_karyawan_id]['Gaji Pokok'])?$data[$list_karyawan->p_karyawan_id]['Gaji Pokok']:0;
                               	$gapok= isset($data[$list_karyawan->p_karyawan_id]['Tunjangan Grade'])?$data[$list_karyawan->p_karyawan_id]['Tunjangan Grade']:0;?>
                               <!-- <td>{!! $help->rupiah($grade+$gapok) !!}</td>-->
                               	<td style="font-size:13px"><?=  $help->rupiah2(($grade +$gapok+$tunjangan)-$potongan);?></td>
                                <td>{!! $list_karyawan->nmpangkat !!}</td>
                                <td style="width: 40px;min-width: 40px;max-width: 40px;"><?= $absen = isset($data[$list_karyawan->p_karyawan_id]['Hari Absen'])?$data[$list_karyawan->p_karyawan_id]['Hari Absen']:0;?></td>
                                <td style="width: 40px;min-width: 40px;max-width: 40px;"><?= $sakit = isset($data[$list_karyawan->p_karyawan_id]['Sakit'])?$data[$list_karyawan->p_karyawan_id]['Sakit']:0;?></td>
                                
                                <td style="width: 40px;min-width: 40px;max-width: 40px;"><?= $ihk = isset($data[$list_karyawan->p_karyawan_id]['Izin Hitung Kerja'])?$data[$list_karyawan->p_karyawan_id]['Izin Hitung Kerja']:0;?></td>
                                <td style="width: 40px;min-width: 40px;max-width: 40px;"><?= $cuti = isset($data[$list_karyawan->p_karyawan_id]['Cuti'])?$data[$list_karyawan->p_karyawan_id]['Cuti']:0;?></td>
                                <td style="width: 40px;min-width: 40px;max-width: 40px;"><?= $ipg=isset($data[$list_karyawan->p_karyawan_id]['Izin Perjalanan Dinas'])?$data[$list_karyawan->p_karyawan_id]['Izin Perjalanan Dinas']:0;?></td>
                                <td style="width: 40px;min-width: 40px;max-width: 40px;"><?= $tk = isset($data[$list_karyawan->p_karyawan_id]['Tanpa Keterangan'])?$data[$list_karyawan->p_karyawan_id]['Tanpa Keterangan']:0;?></td>
                                <td style="width: 40px;min-width: 40px;max-width: 40px;"><?= ($sakit +$ihk+$cuti+$ipg+$tk);?></td>
                         		<td style="font-size:13px"><?= $help->rupiah2($potizin= isset($data[$list_karyawan->p_karyawan_id]['Potongan Izin'])?$data[$list_karyawan->p_karyawan_id]['Potongan Izin']:0);?></td>
                         		<td style="font-size:13px"><?= $help->rupiah2($potabsen= isset($data[$list_karyawan->p_karyawan_id]['Potongan Absen'])?$data[$list_karyawan->p_karyawan_id]['Potongan Absen']:0);?></td>
                         		<td style="font-size:13px"><?=$help->rupiah2($potizin+$potabsen);?></td>
                                <td style="width: 40px;min-width: 40px;max-width: 40px;"><?= $lembur1= isset($data[$list_karyawan->p_karyawan_id]['Lembur 1 Jam'])?$data[$list_karyawan->p_karyawan_id]['Lembur 1 Jam']:0;?></td>
                                <td style="width: 40px;min-width: 40px;max-width: 40px;"><?= $lembur2 = isset($data[$list_karyawan->p_karyawan_id]['Lembur >=2 Jam'])?$data[$list_karyawan->p_karyawan_id]['Lembur >=2 Jam']:0;?></td>
                                <td style="width: 40px;min-width: 40px;max-width: 40px;"><?=  isset($data[$list_karyawan->p_karyawan_id]['COUNT Kerja'])?$data[$list_karyawan->p_karyawan_id]['COUNT Kerja']:0;?></td>
                                <td style="width: 40px;min-width: 40px;max-width: 40px;"><?=  isset($data[$list_karyawan->p_karyawan_id]['SUM Kerja'])?$data[$list_karyawan->p_karyawan_id]['SUM Kerja']:0;?></td>
                               
                                <td  style="font-size:13px"><?=  $help->rupiah2(isset($data[$list_karyawan->p_karyawan_id]['Jumlah Lembur Kerja'])?$data[$list_karyawan->p_karyawan_id]['Jumlah Lembur Kerja']:0);?></td>
                                <td style="width: 40px;min-width: 40px;max-width: 40px;"><?= $lembur8=isset($data[$list_karyawan->p_karyawan_id]['Lembur 8 Jam'])?$lembur=$data[$list_karyawan->p_karyawan_id]['Lembur 8 Jam']:$lembur8=0;?></td>
                                <td style="width: 40px;min-width: 40px;max-width: 40px;"><?= $lembur9=isset($data[$list_karyawan->p_karyawan_id]['Lembur 9 Jam'])?$lembur=$data[$list_karyawan->p_karyawan_id]['Lembur 9 Jam']:$lembur9=0;?></td>
                                <td style="width: 40px;min-width: 40px;max-width: 40px;"><?= $lembur10= isset($data[$list_karyawan->p_karyawan_id]['Lembur >=10 Jam'])?$data[$list_karyawan->p_karyawan_id]['Lembur >=10 Jam']:0;?> </td>
                                
                                <td style="width: 40px;min-width: 40px;max-width: 40px;"><?= $lembur8+$lembur10+$lembur9;?></td>
                                <td  style="font-size:13px"><?=  $help->rupiah2(isset($data[$list_karyawan->p_karyawan_id]['Jumlah Lembur Libur'])?$data[$list_karyawan->p_karyawan_id]['Jumlah Lembur Libur']:0);?></td>
                                <td style="width: 40px;min-width: 40px;max-width: 40px;"><?=  isset($data[$list_karyawan->p_karyawan_id]['Jam Lembur'])?$data[$list_karyawan->p_karyawan_id]['Jam Lembur']:0;?></td>
                                <td  style="font-size:13px"><?= $help->rupiah2($lembur= isset($data[$list_karyawan->p_karyawan_id]['Lembur'])?$data[$list_karyawan->p_karyawan_id]['Lembur']:0);?></td>
                                <td style="width: 40px;min-width: 40px;max-width: 40px;"><?=  isset($data[$list_karyawan->p_karyawan_id]['Terlambat'])?$data[$list_karyawan->p_karyawan_id]['Terlambat']:0;?></td>
                                <td style="font-size:13px"><?= $help->rupiah2($telat= isset($data[$list_karyawan->p_karyawan_id]['Potongan Telat'])?$data[$list_karyawan->p_karyawan_id]['Potongan Telat']:0);?></td>
                                <td style="width: 40px;min-width: 40px;max-width: 40px;"><?=  isset($data[$list_karyawan->p_karyawan_id]['Fingerprint'])?$data[$list_karyawan->p_karyawan_id]['Fingerprint']:0;?></td>
                                <td style="font-size:13px"><?= $help->rupiah2($finger= isset($data[$list_karyawan->p_karyawan_id]['Potongan Fingerprint'])?$data[$list_karyawan->p_karyawan_id]['Potongan Fingerprint']:0);?></td>
                                <td style="font-size:13px"><?=$help->rupiah2($telat+$finger);?></td>
                                <td><?=$absen+$sakit +$ihk+$cuti+$ipg+$tk?></td>
                                
                               
                            </tr>
							<?php 
								$total['THP']+=($grade +$gapok+$tunjangan)-$potongan;
								$total['Hari Absen']+=$absen;
								$total['Sakit']+=$sakit;
								$total['Izin Hitung Kerja']+=$ihk;
								$total['Cuti']+=$cuti;
								$total['Izin Perjalanan Dinas']+=$ipg;
								$total['Tanpa Keterangan']+=$tk;
								$total['Total Absen']+=$sakit +$ihk+$cuti+$ipg+$tk;
								$total['Potongan Izin']+=$potizin;
								$total['Potongan Absen']+=$potabsen;
								$total['TOTAL POTONGAN']+=$potizin+$potabsen;
								$total['TOTAL 1 JAM']+=$lembur1;
								$total['TOTAL 2 JAM']+=$lembur2;
								$total['COUNT L K']+=isset($data[$list_karyawan->p_karyawan_id]['COUNT Kerja'])?$data[$list_karyawan->p_karyawan_id]['COUNT Kerja']:0;
								$total['COUNT S K']+=isset($data[$list_karyawan->p_karyawan_id]['SUM Kerja'])?$data[$list_karyawan->p_karyawan_id]['SUM Kerja']:0;
								$total['Jumlah Lembur Kerja']+=isset($data[$list_karyawan->p_karyawan_id]['Jumlah Lembur Kerja'])?$data[$list_karyawan->p_karyawan_id]['Jumlah Lembur Kerja']:0;
								$total['TOTAL 8 JAM']+=$lembur8;
								$total['TOTAL 9 JAM']+=$lembur9;
								$total['TOTAL 10 JAM']+=$lembur10;
								$total['TOTAL LEMBUR LIBUR']+=$lembur8+$lembur10+$lembur9;;
								$total['Jumlah Lembur Libur']+=isset($data[$list_karyawan->p_karyawan_id]['Jumlah Lembur Libur'])?$data[$list_karyawan->p_karyawan_id]['Jumlah Lembur Libur']:0;;
								$total['Jam Lembur']+=isset($data[$list_karyawan->p_karyawan_id]['Jam Lembur'])?$data[$list_karyawan->p_karyawan_id]['Jam Lembur']:0;;
								$total['Lembur']+=isset($data[$list_karyawan->p_karyawan_id]['Lembur'])?$data[$list_karyawan->p_karyawan_id]['Lembur']:0;;
								$total['Terlambat']+=isset($data[$list_karyawan->p_karyawan_id]['Terlambat'])?$data[$list_karyawan->p_karyawan_id]['Terlambat']:0;
								$total['Potongan Telat']+=$telat;
								$total['Fingerprint']+=isset($data[$list_karyawan->p_karyawan_id]['Fingerprint'])?$data[$list_karyawan->p_karyawan_id]['Fingerprint']:0;;
								$total['Potongan Fingerprint']+=$finger;
								$total['TOTAL TELAT FINGER']+=$telat+$finger;
								
								?>
                        @endforeach
                         <tr>
                                <th style="font-size:13px" class="statictable"></th>
                                <td>{!! $no !!}</td> 
                                <td style="font-size:13px"></td>
                                
                               <!-- <td>{!! $help->rupiah($grade+$gapok) !!}</td>-->
                               	<td style="font-size:13px"><?=  $help->rupiah2($total['THP']);?></td>
                                <td></td>
                                <td style="width: 40px;min-width: 40px;max-width: 40px;"><?= $total['Hari Absen'];?></td>
                                <td style="width: 40px;min-width: 40px;max-width: 40px;"><?=$total['Sakit'];?></td>
                                
                                <td style="width: 40px;min-width: 40px;max-width: 40px;"><?= $total['Izin Hitung Kerja'];?></td>
                                <td style="width: 40px;min-width: 40px;max-width: 40px;"><?= $total['Cuti'];?></td>
                                <td style="width: 40px;min-width: 40px;max-width: 40px;"><?= $total['Izin Perjalanan Dinas'];?></td>
                                <td style="width: 40px;min-width: 40px;max-width: 40px;"><?= $total['Tanpa Keterangan'];?></td>
                                <td style="width: 40px;min-width: 40px;max-width: 40px;"><?= $total['Total Absen'];?></td>
                         		<td style="font-size:13px"><?= $help->rupiah2($total['Potongan Izin']);?></td>
                         		<td style="font-size:13px"><?= $help->rupiah2($total['Potongan Absen']);?></td>
                         		<td style="font-size:13px"><?=$help->rupiah2($total['TOTAL POTONGAN']);?></td>
                                <td style="width: 40px;min-width: 40px;max-width: 40px;"><?= $total['TOTAL 1 JAM'];?></td>
                                <td style="width: 40px;min-width: 40px;max-width: 40px;"><?= $total['TOTAL 2 JAM'];?></td>
                                <td style="width: 40px;min-width: 40px;max-width: 40px;"><?= $total['COUNT L K'] ;?></td>
                                <td style="width: 40px;min-width: 40px;max-width: 40px;"><?= $total['COUNT S K'] ;?></td>
                                
                                <td  style="font-size:13px"><?=  $help->rupiah2($total['Jumlah Lembur Kerja']);?></td>
                                <td style="width: 40px;min-width: 40px;max-width: 40px;"><?= $total['TOTAL 8 JAM']?></td>
                                <td style="width: 40px;min-width: 40px;max-width: 40px;"><?= $total['TOTAL 9 JAM']?></td>
                                <td style="width: 40px;min-width: 40px;max-width: 40px;"><?= $total['TOTAL 10 JAM']?></td>
                                
                                
                                <td style="width: 40px;min-width: 40px;max-width: 40px;"><?= $total['TOTAL LEMBUR LIBUR'];?></td>
                                <td  style="font-size:13px"><?=  $help->rupiah2($total['Jumlah Lembur Libur']);?></td>
                                <td style="width: 40px;min-width: 40px;max-width: 40px;"><?= $total['Jam Lembur'];?></td>
                                <td  style="font-size:13px"><?= $help->rupiah2($total['Lembur']);?></td>
                                <td style="width: 40px;min-width: 40px;max-width: 40px;"><?=  $total['Terlambat']?></td>
                                <td style="font-size:13px"><?= $help->rupiah2($total['Potongan Telat']);?></td>
                                <td style="width: 40px;min-width: 40px;max-width: 40px;"><?=  $total['Fingerprint']?></td>
                                <td style="font-size:13px"><?= $help->rupiah2($total['Potongan Fingerprint']);?></td>
                                <td style="font-size:13px"><?=$help->rupiah2($total['TOTAL TELAT FINGER']);?></td>
                                <td></td>
                                
                               
                            </tr>
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
		
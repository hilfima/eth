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
  z-index:4;
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
								
								
								<th rowspan="2">Tanggal Masuk</th>
								<th rowspan="2">Masa Kerja</th>
								<th rowspan="2">Perusahaan</th>
								
																								

								<th rowspan="2" style="font-size: 12px">TOTAL PENDAPATAN</th>
								<th rowspan="2" style="font-size: 12px">TOTAL TUNJANGAN</th>
								<th rowspan="2" style="font-size: 12px">TOTAL POTONGAN</th>
								<th rowspan="2" style="font-size: 12px">TOTAL </th>
								
							</tr>
						</thead>
    					<tbody>
							 <?php $no=0;
							$gaji_entitas = array();
                            $total['H. ABSEN']=0;
                            $total['IHK']=0;
                            $total['SAKIT']=0;
                            $total['CUTI']=0;
                            $total['IPG']=0;
                            $total['TK']=0;
                            $total['TP']=0;
                            $total['PM']=0;
                            $total['Terlambat']=0;
                            $total['J.LEMBUR']=0;
                            $total['Gaji Pokok']=0;
							$total['Tunjangan Grade']=0;
								$total['JUMLAH PENDAPATAN']=0;
								$total['LEMBUR']=0;
								$total['Tunjangan BPJS Kesehatan']=0;
								$total['Tunjangan BPJS Ketenaga Kerjaan']=0;
								$total['Koreksi(+)']=0;
								$total['Tunjangan Kost']=0;
								$total['TOTAL TUNJANGAN']=0;
								$total['Potongan Telat']=0;
								$total['Potongan Izin']=0;
								$total['Potongan Fingerprint']=0;
								$total['Potongan PM']=0;
								$total['Potongan Absen']=0;
								$total['Sewa Kost']=0;
								$total['Iuran BPJS Kesehatan']=0;
								$total['Iuran BPJS Ketenaga Kerjaan']=0;
								$total['Zakat']=0;
								$total['Infaq']=0;
								$total['Koreksi(-)']=0;
								$total['Simpanan Wajib KKB']=0;
								$total['Potongan Koperasi Asa']=0;
								$total['Pajak']=0;
								$total['TOTAL POTONGAN']=0;
								$total['TOTAL PENDAPATAN']=0;
								$total['THP']=0; ?>
                        @foreach($list_karyawan as $list_karyawan)
                            <?php 
                            if($list_karyawan->pajak_onoff=='ON'){
                            	
                            $no++;
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
                                <td>{!! $list_karyawan->tgl_awal !!}</td>
                                <td style="font-size: 10px">{!! $list_karyawan->umur !!}</td>
                                <td>{!! $list_karyawan->nmlokasi !!} {!! $list_karyawan->pajak_onoff !!}</td>
                                <?php  $help->rupiah2(isset($data[$list_karyawan->p_karyawan_id]['Gaji Pokok'])?$grade=$data[$list_karyawan->p_karyawan_id]['Gaji Pokok']:$grade=0);
                                  $help->rupiah2(isset($data[$list_karyawan->p_karyawan_id]['Tunjangan Grade'])?$gapok=$data[$list_karyawan->p_karyawan_id]['Tunjangan Grade']:$gapok=0);
                              
                               $help->rupiah2(isset($data[$list_karyawan->p_karyawan_id]['Lembur'])?$lembur=$data[$list_karyawan->p_karyawan_id]['Lembur']:$lembur=0);
                                 $help->rupiah2(isset($data[$list_karyawan->p_karyawan_id]['Tunjangan BPJS Kesehatan'])?$tunkes=$data[$list_karyawan->p_karyawan_id]['Tunjangan BPJS Kesehatan']:$tunkes=0);
                                $help->rupiah2(isset($data[$list_karyawan->p_karyawan_id]['Tunjangan BPJS Ketenaga Kerjaan'])?$tunket=$data[$list_karyawan->p_karyawan_id]['Tunjangan BPJS Ketenaga Kerjaan']:$tunket=0);
                                $help->rupiah2(isset($data[$list_karyawan->p_karyawan_id]['Koreksi(+)'])?$korekplus=$data[$list_karyawan->p_karyawan_id]['Koreksi(+)']:$korekplus=0);
                                  $help->rupiah2(isset($data[$list_karyawan->p_karyawan_id]['Tunjangan Kost'])?$tunkost=$data[$list_karyawan->p_karyawan_id]['Tunjangan Kost']	:$tunkost=0);
                                $help->rupiah2($tunjangan = $tunkost+$korekplus+$tunket+$tunkes );
                               
                               $help->rupiah2(isset($data[$list_karyawan->p_karyawan_id]['Potongan Telat'])?$telat= $data[$list_karyawan->p_karyawan_id]['Potongan Telat']:$telat=0);
                               $help->rupiah2(isset($data[$list_karyawan->p_karyawan_id]['Potongan Fingerprint'])?$potfinger= $data[$list_karyawan->p_karyawan_id]['Potongan Fingerprint']:$potfinger=0);
                                 $help->rupiah2(isset($data[$list_karyawan->p_karyawan_id]['Potongan Pulang Mendahului'])?$potpm= $data[$list_karyawan->p_karyawan_id]['Potongan Pulang Mendahului']:$potpm=0);
                               $help->rupiah2(isset($data[$list_karyawan->p_karyawan_id]['Potongan Izin'])?$potizin= $data[$list_karyawan->p_karyawan_id]['Potongan Izin']:$potizin=0);
                                 $help->rupiah2(isset($data[$list_karyawan->p_karyawan_id]['Potongan Absen'])?$absen = $data[$list_karyawan->p_karyawan_id]['Potongan Absen']:$absen =0);
                                  $help->rupiah2(isset($data[$list_karyawan->p_karyawan_id]['Sewa Kost'])?$sewakost = $data[$list_karyawan->p_karyawan_id]['Sewa Kost']:$sewakost =0);
                                   $help->rupiah2(isset($data[$list_karyawan->p_karyawan_id]['Iuran BPJS Kesehatan'])?$ibpjs = $data[$list_karyawan->p_karyawan_id]['Iuran BPJS Kesehatan']:$ibpjs =0);
                                    $help->rupiah2(isset( $data[$list_karyawan->p_karyawan_id]['Iuran BPJS Ketenaga Kerjaan'])?$ibpjt = $data[$list_karyawan->p_karyawan_id]['Iuran BPJS Ketenaga Kerjaan']:$ibpjt =0);
                                    $help->rupiah2($zakat =isset($data[$list_karyawan->p_karyawan_id]['Zakat'])? $data[$list_karyawan->p_karyawan_id]['Zakat']:0);
                                    $help->rupiah2($infaq = isset($data[$list_karyawan->p_karyawan_id]['Infaq'])?$data[$list_karyawan->p_karyawan_id]['Infaq']:0);
                                    $help->rupiah2($korekmin=isset($data[$list_karyawan->p_karyawan_id]['Koreksi(-)'])?$korekmin = $data[$list_karyawan->p_karyawan_id]['Koreksi(-)']:0);
                                    $help->rupiah2($kkp = isset($data[$list_karyawan->p_karyawan_id]['Simpanan Wajib KKB'])?$data[$list_karyawan->p_karyawan_id]['Simpanan Wajib KKB']:0);
                                    $help->rupiah2($asa = isset($data[$list_karyawan->p_karyawan_id]['Potongan Koperasi Asa'])?$data[$list_karyawan->p_karyawan_id]['Potongan Koperasi Asa']:0);
                                    $help->rupiah2($pajak = isset($data[$list_karyawan->p_karyawan_id]['Pajak'])?$data[$list_karyawan->p_karyawan_id]['Pajak']:0);?></td>
                                <td style="font-size:13px"><?=  $help->rupiah2(($grade +$gapok+$lembur));?></td>
                                <td style="font-size:13px"><?=  $help->rupiah2(($tunjangan));?></td>
                                <td style="font-size:13px"><?=  $help->rupiah2($potongan = $telat+$absen+$potizin+$potfinger+$potpm);?></td>
								<td style="font-size:13px"><?=  $help->rupiah2(($grade +$gapok+$lembur+$tunjangan)-$potongan);?></td>
								
                            </tr>
                            <?php }?>
                            @endforeach
						</tfoot>
  </table>
</div>
   
            			
            	
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

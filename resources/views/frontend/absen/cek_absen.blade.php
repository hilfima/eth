@extends('layouts.app_fe')

@section('content')
	<div class="row">
	<div class="col-xl-3 col-lg-4 col-md-12 theiaStickySidebar">
		<?= view('layouts.app_side'); ?>
	</div>
	<div class="col-xl-9 col-lg-8 col-md-12">

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
    @include('flash-message')
    <?php 
    if(!isset($hari_libur_shift)){$hari_libur_shift=array();}
    ?>
        <!-- Content Header (Page header) -->
      <div class="card shadow-sm ctm-border-radius">
<div class="card-body align-center">
<h4 class="card-title float-left mb-0 mt-2">Cek Absen</h4>

</div>
</div> 

        <!-- /.content-header -->

        <!-- Main content -->
        <div class="card">
            <div class="card-header">
                <!--<h3 class="card-title">DataTable with default features</h3>-->
                <!--<a href="http://localhost/get-data/udp.php" target="_blank" title='Sync Absen' data-toggle='tooltip'><span class='fa fa-sync'></span> Sync Absen </a>-->
                <form id="cari_absen" class="form-horizontal" method="get" action="{!! route('fe.cari_absenpro') !!}">
                    {{ csrf_field() }}
                    <div class="row">
                        
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Tanggal Awal</label>
                                <div class="input-group date" id="tgl_awal" data-target-input="nearest">
                                    <input type="date" class="form-control" id="tgl_awal" name="tgl_awal" value="{!! $tgl_awal !!}"  />
                                   
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Tanggal Akhir</label>
                                <div class="input-group date" id="tgl_akhir" data-target-input="nearest">
                                    <input type="date" class="form-control" id="tgl_akhir" name="tgl_akhir" value="{!! $tgl_akhir !!}" />
                                   
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-12">
                            <a href="{!! route('fe.absenpro') !!}" class="btn btn-primary"><span class="fa fa-sync"></span> Reset</a>
                            <button type="submit" name="Cari" class="btn btn-primary" value="Cari"><span class="fa fa-search"></span> Cari</button>
                            <button type="submit" name="Cari" class="btn btn-primary" value="Excel"><span class="fa fa-file-excel"></span> Excel</button>

                        </div>
                    </div>
                </form>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <table id="example1" class="table table-striped custom-table mb-0">
                    <thead>
                    <tr>
                       
                        <th>Tgl. Absen</th>
                        <th>Jam Masuk</th>
                        <th>Jam Keluar</th>
                        <th>Status</th>
                        <th>Lokasi Absen Seharusnya</th>
                        <th>Lokasi Absen </th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $no=0;
                   // print_r($rekap);
                    ?>
                    @if(!empty($rekap))
                       <?php $date = $tgl_awal;
                       $return = $help->total_rekap_absen($rekap,$nama);
									
									
									
									echo $return['all_content_cek_absen'];
									$masuk = $return['total']['masuk'] ;
									$cuti = $return['total']['cuti'] ;
									$fingerprint = $return['total']['fingerprint'] ;
									$ipg = $return['total']['ipg'] ;
									$izin = $return['total']['izin'] ;
									$ipd = $return['total']['ipd'] ;
									$ipc = $return['total']['ipc'] ;
									$idt = $return['total']['idt'] ;
									$ipm = $return['total']['ipm'] ;
									$pm = $return['total']['pm'] ;
									$sakit = $return['total']['sakit'] ;
									$alpha = $return['total']['alpha'] ;
									$terlambat = $return['total']['terlambat'] ;
									$tabsen = $return['total']['Total Absen'] ;
									$tmasuk = $return['total']['Total Masuk'] ;
									$tkerja = $return['total']['Total Hari Kerja'] ;?>
                    @endif
                    </tbody>
                </table>
            </div>
            <div class="row ">
            	<div class="col-md-6" style="padding: 50px">
            	Rekap Kehadiran : 
            		<table style="width: 100%" class="table table-striped">
            			<tr>
            				<th>Total Absen Masuk</th>
            				<td><?=$masuk;?></td>
            			</tr><tr>
            				<th>Terlambat</th>
            				<td><?=$terlambat;?></td>
            			</tr><tr>
            			
            				<th>Cuti</th>
            				<td><?=$cuti;?></td>
            			</tr><tr>
            				<th>IHK(Izin Hak Karyawan)</th>
            				<td><?=$izin;?></td>
            			</tr><tr>
            				<th>IPC(Izin Potong Cuti)</th>
            				<td><?=$ipc;?></td>
            			</tr><tr>
            				<th>IPD(Izin Perjalanan Dinas)</th>
            				<td><?=$ipd;?></td>
            			</tr><tr>
            				<th>IPG(Izin Potong Gaji)</th>
            				<td><?=$ipg;?></td>
            			</tr><tr>
            				<th>IDT(Izin Datang Terlambat)</th>
            				<td><?=$idt;?></td>
            			</tr><tr>
            				<th>IPM(Izin Pulang Mendahului)</th>
            				<td><?=$ipm;?></td>
            			</tr><tr>
            				<th>PM(Pulang Mendahului Tanpa Izin)</th>
            				<td><?=$pm;?></td>
            			</tr><tr>
            				<th>Sakit</th>
            				<td><?=$sakit;?></td>
            			</tr><tr>
            				<th>Alpha</th>
            				<td><?=$alpha;?></td>
            			</tr><tr>
            				<th>Tidak Finger Print</th>
            				<td><?=$fingerprint;?></td>
            			</tr>
            			
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

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
                        <h1 class="m-0 text-dark">Dashboard</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Admin</a></li>
                            <li class="breadcrumb-item active">Dashboard</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <!-- Small boxes (Stat box) -->
                <div class="row">
                    <div class="col-lg-3 col-6">
                        <!-- small box -->
                        <div class="small-box bg-info">
                            <div class="inner">
                                <h3>{!! $totalkontrak[0]->total !!}</h3>

                                <p>Kontrak H-30</p>
                            </div>
                            <div class="icon">
                                <i class="far fa-file"></i>
                            </div>
                            <a href="{!! route('be.kontrak') !!}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <!-- ./col -->
                    <div class="col-lg-3 col-6">
                        <!-- small box -->
                        <div class="small-box bg-warning">
                            <div class="inner">
                                <h3>{!! $jmlkaryawan[0]->jmlkaryawan !!}</h3>

                                <p>Total Karyawan</p>
                            </div>
                            <div class="icon">
                                <i class="fa fa-users"></i>
                            </div>
                            <a href="{!! route('be.karyawan') !!}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <!-- ./col -->
                    <div class="col-lg-3 col-6">
                        <!-- small box -->
                        <div class="small-box bg-success">
                            <div class="inner">
                                <h3>{!! $jmllokasi[0]->jmllokasi !!}</h3>

                                <p>Entitas</p>
                            </div>
                            <div class="icon">
                                <i class="far fa-building"></i>
                            </div>
                            <a href="{!! route('be.lokasi') !!}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <!-- ./col -->
                    <div class="col-lg-3 col-6">
                        <!-- small box -->
                        <div class="small-box bg-danger">
                            <div class="inner">
                                <h3>{!! $jmldepartemen[0]->jmldepartemen !!}</h3>

                                <p>Departemen</p>
                            </div>
                            <div class="icon">
                                <i class="fa fa-tags"></i>
                            </div>
                            <a href="{!! route('be.departemen') !!}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <!-- ./col -->
                </div>
                <!-- Main content -->
                <section class="content">
                    <div class="container-fluid">
                        <h5 class="mb-2"><a href="{!! route('be.absen') !!}">Daftar Kehadiran Karyawan</a></h5>
                        <div class="row">
                        
                         @if(!empty($list_karyawan))
							@foreach($list_karyawan as $list_karyawan)
                                    <div class="col-md-3 col-sm-6 col-12">
                                    <div class="card card-widget widget-user-2">
                                                <!-- Add the bg color to the header using any of the bg-* classes -->
                                                <div class="widget-user-header">
                                                    <div class="widget-user-image">
                                                        @if($list_karyawan->foto!=null)
                                                            <img src="{!! asset('dist/img/profile/'.$list_karyawan->foto) !!}" alt="User Avatar" class="img-size-32 mr-3 img-thumbnail" style="height: 75px;">
                                                        @else
                                                            <img src="{!! asset('dist/img/profile/user.png') !!}" class="img-size-32 mr-3 img-thumbnail elevation-2" alt="User Image" style="height: 75px;">
                                                        @endif
                                                    </div>
                                                    <span class="info-box-text" style="font-size: small">&nbsp;{!! $list_karyawan->nama !!}</span><br>
                                    	<?php
                                    	$date = date('Y-m-d');
                                    	$main = ' <span class="info-box-number" style="font-size: small">&nbsp;STR1</span><br>STRLINK<span class="badge " style="font-size: small;color:white;STRBG">&nbsp;STRKETERANGAN</span>STRENDLINK';
									$warna = '';
									$ket = '';
									$content = '';
									if(isset($rekap[$list_karyawan->p_karyawan_id][$date]['a']['masuk'])){
										if($rekap[$list_karyawan->p_karyawan_id][$date]['a']['terlambat'] and $list_karyawan->m_pangkat_id!=5){
											$ket = 'Terlambat';
											$warna = 'red';
										}else{
											$warna = 'green';
											$ket = 'Masuk';
										}
										$content .= ' '.$rekap[$list_karyawan->p_karyawan_id][$date]['a']['masuk'];
										if(isset($rekap[$list_karyawan->p_karyawan_id][$date]['a']['keluar'])){
											
											$content.=' 
											s/d  '.$rekap[$list_karyawan->p_karyawan_id][$date]['a']['keluar'].'
											';	
										}
									}
									if(isset($rekap[$list_karyawan->p_karyawan_id][$date]['ci']['nama_ijin'])){
										if(!in_array($help->nama_hari($date),array('Minggu','Sabtu'))  ){
											if($rekap[$list_karyawan->p_karyawan_id][$date]['ci']['tipe']==3)
											$warna = 'green';
											else if($rekap[$list_karyawan->p_karyawan_id][$date]['ci']['tipe']==4)
											$warna = 'blue';
											else if($rekap[$list_karyawan->p_karyawan_id][$date]['ci']['tipe']==1)
											$warna = 'purple';
														
											$ket .= ' '.$rekap[$list_karyawan->p_karyawan_id][$date]['ci']['nama_ijin'].' 
											
										
											';	
											if($rekap[$list_karyawan->p_karyawan_id][$date]['ci']['nama_ijin']
												== 'IZIN PERJALANAN DINAS'){
												$ket .= '<br> dari jam '.$rekap[$list_karyawan->p_karyawan_id][$date]['ci']['jam_awal'];
											}
										}
													
									}
									if(!$warna){
										$warna = 'red';
										$ket = 'No Information';
										$link = '<a href="'. route('be.input_absen',$list_karyawan->no_absen) .'" target="_blank" title="Input Absen">';
										$endlink = '</a>';
									}else{
										$link = '';
										$endlink = '';
									}	
									
											
									$main = str_ireplace('STR1',$content,$main);
									$main = str_ireplace('STRKETERANGAN',$ket,$main);
									$main = str_ireplace('STRBG','background:'.$warna,$main);
									$main = str_ireplace('STRLINK',$link,$main);
									$main = str_ireplace('STRENDLINK',$endlink,$main);
									
											
											
									echo $main;
                                    	?>
                                    	</div>
                                            </div>
                                             </div>
                                @endforeach
                            @endif
                                             
                            <!-- /.col -->
                        </div>
                    </div>
                </section>
            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
@endsection
<!-- ChartJS -->
<script src="{!! asset('plugins/chart.js/Chart.min.js') !!}"></script>
<script>
    $(function () {
        /* ChartJS
         * -------
         * Here we will create a few charts using ChartJS
         */

        //--------------
        //- AREA CHART -
        //--------------

        // Get context with jQuery - using jQuery's .get() method.
        var areaChartCanvas = $('#areaChart').get(0).getContext('2d')

        var areaChartData = {
            labels  : ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
            datasets: [
                {
                    label               : 'Digital Goods',
                    backgroundColor     : 'rgba(60,141,188,0.9)',
                    borderColor         : 'rgba(60,141,188,0.8)',
                    pointRadius          : false,
                    pointColor          : '#3b8bba',
                    pointStrokeColor    : 'rgba(60,141,188,1)',
                    pointHighlightFill  : '#fff',
                    pointHighlightStroke: 'rgba(60,141,188,1)',
                    data                : [28, 48, 40, 19, 86, 27, 90]
                },
                {
                    label               : 'Electronics',
                    backgroundColor     : 'rgba(210, 214, 222, 1)',
                    borderColor         : 'rgba(210, 214, 222, 1)',
                    pointRadius         : false,
                    pointColor          : 'rgba(210, 214, 222, 1)',
                    pointStrokeColor    : '#c1c7d1',
                    pointHighlightFill  : '#fff',
                    pointHighlightStroke: 'rgba(220,220,220,1)',
                    data                : [65, 59, 80, 81, 56, 55, 40]
                },
            ]
        }

        var areaChartOptions = {
            maintainAspectRatio : false,
            responsive : true,
            legend: {
                display: false
            },
            scales: {
                xAxes: [{
                    gridLines : {
                        display : false,
                    }
                }],
                yAxes: [{
                    gridLines : {
                        display : false,
                    }
                }]
            }
        }

        // This will get the first returned node in the jQuery collection.
        var areaChart       = new Chart(areaChartCanvas, {
            type: 'line',
            data: areaChartData,
            options: areaChartOptions
        })

        //-------------
        //- LINE CHART -
        //--------------
        var lineChartCanvas = $('#lineChart').get(0).getContext('2d')
        var lineChartOptions = $.extend(true, {}, areaChartOptions)
        var lineChartData = $.extend(true, {}, areaChartData)
        lineChartData.datasets[0].fill = false;
        lineChartData.datasets[1].fill = false;
        lineChartOptions.datasetFill = false

        var lineChart = new Chart(lineChartCanvas, {
            type: 'line',
            data: lineChartData,
            options: lineChartOptions
        })

        //-------------
        //- DONUT CHART -
        //-------------
        // Get context with jQuery - using jQuery's .get() method.
        var laki=('{!! $jmllaki[0]->laki !!}')
        var wanita=('{!! $jmlwanita[0]->wanita !!}')
        var donutChartCanvas = $('#donutChart').get(0).getContext('2d')
        var donutData        = {
            labels: [
                'Laki-Laki',
                'Perempuan',
            ],
            datasets: [
                {
                    data: [laki,wanita],
                    backgroundColor : ['#00c0ef', '#3c8dbc'],
                }
            ]
        }
        var donutOptions     = {
            maintainAspectRatio : false,
            responsive : true,
        }
        //Create pie or douhnut chart
        // You can switch between pie and douhnut using the method below.
        var donutChart = new Chart(donutChartCanvas, {
            type: 'doughnut',
            data: donutData,
            options: donutOptions
        })


        //-------------
        //- BAR CHART -
        //-------------
        var barChartCanvas = $('#barChart').get(0).getContext('2d')
        var barChartData = $.extend(true, {}, areaChartData)
        var temp0 = areaChartData.datasets[0]
        var temp1 = areaChartData.datasets[1]
        barChartData.datasets[0] = temp1
        barChartData.datasets[1] = temp0

        var barChartOptions = {
            responsive              : true,
            maintainAspectRatio     : false,
            datasetFill             : false
        }

        var barChart = new Chart(barChartCanvas, {
            type: 'bar',
            data: barChartData,
            options: barChartOptions
        })
    })
</script>

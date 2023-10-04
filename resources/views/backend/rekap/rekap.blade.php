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
<style>
	.dash-widget-icon {
  background-color: rgba(102, 126, 234, 0.2);
  border-radius: 100%;
  color: #667eea;
  display: flex;
  float: left;
  font-size: 30px;
  height: 60px;
  line-height: 60px;
  margin-right: 10px;
  text-align: center;
  width: 60px;
  align-content: center;
  justify-items: center;
  align-items: center;
  align-self: center;
  float: center;
  justify-items: center;
  justify-content: center;
}
</style>
        <!-- Main content -->
            	
        <section class="content">
            <div class="container-fluid">
                <!-- Small boxes (Stat box) -->
                <div class="row">
                
						<div class="col-md-12">
							<div class="card card-table">
								<div class="card-header">
									<h3 class="card-title mb-0">Rekap Karyawan</h3>
								</div>
								</div>
								</div>
								
								
								<?php
								
								 if($page=='entitas'){?>
								<div class="col-md-12 text-center">
									<div class="card-box">
										<h3 class="card-title">Jumlah Karyawan Entitas</h3>
										<div id="pie-charts"></div>
									</div>
								</div>
								<?php }else if($page=='status_kerja'){?>
								<div class="col-md-12 text-center">
									<div class="card-box">
										<h3 class="card-title">Jumlah Karyawan Berdasarkan Status Kerja</h3>
										<div id="bar2-charts"></div>
									</div>
								</div>
								<?php }else if($page=='pangkat'){?>
								<div class="col-md-12 text-center">
									<div class="card-box">
										<h3 class="card-title">Jumlah Karyawan Berdasarkan Pangkat</h3>
										<div id="bar-charts"></div>
									</div>
								</div>
								<?php }else if($page=='pernikahan'){?>
								<div class="col-md-12 text-center">
									<div class="card-box">
										<h3 class="card-title">Jumlah Karyawan Berdasarkan Status Pernikahan</h3>
										<div id="line-charts"></div>
									</div>
								</div>
								<?php }else if($page=='kelamin'){?>
								<div class="col-md-12 text-center">
									<div class="card-box">
										<h3 class="card-title">Jumlah Karyawan Berdasarkan Jenis Kelamin</h3>
										<div id="kelamin-charts"></div>
									</div>
								</div>
								<?php }else if($page=='masuk_resign'){?><div class="col-md-12 text-center">
									<div class="card-box">
										<h3 class="card-title">Karyawan Baru dan Resign</h3>
										<div id="masuk_resign-charts"></div>
									</div>
								</div>
								<?php }else if($page=='pekanan'){?><div class="col-md-12 text-center">
									<div class="card-box">
										<h3 class="card-title">Absen pekanan</h3>
										<div id="pekanan-charts"></div>
									</div>
								</div>
								<?php }else if($page=='bulanan'){?><div class="col-md-12 text-center">
									<div class="card-box">
										<h3 class="card-title">Absen Bulan</h3>
										<div id="bulanan-charts"></div>
									</div>
								</div>
								
								<?php }?>
								
									</div>
								</div>
								
							</div>
						</div>
					</div>
                <!-- Main content -->
                
             
            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
<!-- ChartJS -->
 <script src="{!! asset('plugins/purple/assets/js/jquery-3.2.1.min.js') !!}"></script>
		
		<!-- Bootstrap Core JS -->
        <script src="{!! asset('plugins/purple/assets/js/popper.min.js') !!}"></script>
        <script src="{!! asset('plugins/purple/assets/js/bootstrap.min.js') !!}"></script>
		
		<!-- Slimscroll JS -->
		<script src="{!! asset('plugins/purple/assets/js/jquery.slimscroll.min.js') !!}"></script>
<script src="{!! asset('plugins/purple/assets/plugins/chart.js/Chart.min.js') !!}"></script>
<script src="{!! asset('plugins/purple/assets/plugins/morris/morris.min.js') !!}"></script>
<script src="{!! asset('plugins/purple/assets/plugins/raphael/raphael.min.js') !!}"></script>
<script>
	var $arrColors = ['#7A4495', '#A10035',  '#FFC23C','#34495E', '#26B99A','#D1512D','#EAF6F6','#EB4747','#377D71','#610C63','#1A4D2E','#B25068','#7A4495','#34495E', '#26B99A',  '#666', '#3498DB'];
</script>
<?php 

if($page=='kelamin'){?>


<script>
$(document).ready(function() {
	
	// Area Chart
	var $arrColors = ['#7A4495', '#A10035',  '#FFC23C','#34495E', '#26B99A','#D1512D','#EAF6F6','#EB4747','#377D71','#610C63','#1A4D2E','#B25068','#7A4495','#34495E', '#26B99A',  '#666', '#3498DB'];
    Morris.Bar({
		element: 'kelamin-charts',
		data: <?=$graph['kelamin']?>,
		xkey: 'y',
		ykeys: ['a'],
		labels: ['Total Karyawan'],
		barColors: function (row, series, type) {
	        return $arrColors[row.x];
	    },
		lineWidth: '3px',
		resize: true,
    }); 
    }); 
</script>
<?php }else if($page=='pernikahan'){?>
<script>
$(document).ready(function() {
	// Bar Chart
	Morris.Bar({
		element: 'line-charts',
		data: <?=$graph['pernikahan']?>,
		xkey: 'y',
		ykeys: ['a'],
		labels: ['Total Karyawan'],
		lineColors: ['#667eea','#764ba2','#355764','#069A8E','#069A8E','#26B99A','#D1512D','#EAF6F6'],
		lineWidth: '3px',
		resize: true,
		redraw: true
	}); 
	}); 
</script>
<?php }else if($page=='pekanan'){?>
<script>
$(document).ready(function() {
	Morris.Line({
		element: 'pekanan-charts',
		data: <?=$graph['pekanan']['data']?>,
		xkey: 'y',
		ykeys: <?=$graph['pekanan']['ykeys']?>,
		labels: <?=$graph['pekanan']['labelabsen']?>,
		lineColors: ['#34495E', '#26B99A',  '#666', '#3498DB','#667eea','#764ba2','#355764','#069A8E','#069A8E'],
		lineWidth: '3px',
		resize: true,
		redraw: true
	});	
	});	
	</script>
<?php }else if($page=='bulanan'){?>
<script>
$(document).ready(function() {
	Morris.Bar({
		element: 'bulanan-charts',
		data: <?=$graph['bulanan']['data']?>,
		xkey: 'y',
		ykeys: <?=$graph['bulanan']['ykeys']?>,
		labels: <?=$graph['bulanan']['labelabsen']?>,
		lineColors: ['#34495E', '#26B99A',  '#666', '#3498DB','#667eea','#764ba2','#355764','#069A8E','#069A8E'],
		lineWidth: '3px',
		resize: true,
		redraw: true
	});	
	});	
	</script>
<?php }else if($page=='entitas'){?>
<script>
$(document).ready(function() {
	Morris.Donut({
		element: 'pie-charts',
		colors: ['#7A4495','#34495E', '#26B99A',  '#666', '#3498DB','#FFC23C','#3FA796','#D1512D','#EB4747','#377D71','#610C63','#1A4D2E','#B25068'],
		data: <?=$graph['entitas']?>,
		resize: true,
		redraw: true
	});
	});
	</script>
<?php }else if($page=='pangkat'){?>
<script>
$(document).ready(function() {
	Morris.Bar({
		element: 'bar-charts',
		data: <?=$graph['pangkat']?>,
		xkey: 'y',
		ykeys: ['a'],
		labels: ['Total Karyawan'],
		lineColors: ['#667eea','#764ba2','#355764','#069A8E','#069A8E'],
		lineWidth: '3px',
		barColors: function (row, series, type) {
	        return $arrColors[row.x];
	    },
		resize: true
	});
	});
	</script>
<?php }else if($page=='status_kerja'){?>
<script>
$(document).ready(function() {
	Morris.Bar({
		element: 'bar2-charts',
		data: <?=$graph['status_kerja']?>,
		xkey: 'y',
		ykeys: ['a'],
		labels: ['Total Karyawan'],
		lineColors: ['#667eea','#764ba2','#355764','#069A8E','#069A8E'],
		lineWidth: '3px',
		barColors: function (row, series, type) {
	        return $arrColors[row.x];
	    },
		resize: true,
		redraw: true
	});
	});
	</script>
<?php }else if($page=='masuk_resign'){?>
<script>
$(document).ready(function() {
	Morris.Bar({
		element: 'masuk_resign-charts',
		data: <?=$graph['masuk_resign']?>,
		xkey: 'y',
		ykeys: ['a','b'],
		labels: [' Karyawan Baru','Karyawan Resign'],
		barColors: function (row, series, type) {
	        return $arrColors[row.x];
	    },
		lineWidth: '3px',
		resize: true,
    });
	// Line Chart
	
	
	
	// Donut Chart
	
		
});
</script>
<?php }?>

			
@endsection
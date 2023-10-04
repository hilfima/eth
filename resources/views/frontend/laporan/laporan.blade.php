@extends('layouts.app_fe')

@section('content')
	<div class="row">
	<div class="col-xl-3 col-lg-4 col-md-12 theiaStickySidebar">
		<?= view('layouts.app_side'); ?>
	</div>
	<div class="col-xl-9 col-lg-8 col-md-12">

    <!-- Content Wrapper. Contains page content -->
    <div class="content">
    @include('flash-message')
    <!-- Content Header (Page header) -->
    <div class="card shadow-sm ctm-border-radius">
<div class="card-body align-center">
<h4 class="card-title float-left mb-0 mt-2">Absensi</h4>

</div>
</div>

<div class="row">
<div class="col-md-6">
<div class="card ctm-border-radius shadow-sm flex-fill">
	<div class="card-header">
		<h4 class="card-title mb-0">Absen Bulanan</h4>
	</div>
	<div class="card-body">
		<canvas id="BulananChart"></canvas>
	</div>
</div>
</div>
<div class="col-md-6">
<div class="card ctm-border-radius shadow-sm flex-fill">
	<div class="card-header">
		<h4 class="card-title mb-0">Absen Pekanan</h4>
	</div>
	<div class="card-body">
		<canvas id="PekananChart"></canvas>
	</div>
</div>
</div>
</div>


<div class="card shadow-sm ctm-border-radius">
<div class="card-body align-center">
<h4 class="card-title float-left mb-0 mt-2">Karyawan</h4>
	
</div>
</div>

<div class="row">
<div class="col-md-6">
<div class="card ctm-border-radius shadow-sm flex-fill">
	<div class="card-header">
		<h4 class="card-title mb-0">Status Pekerjaan</h4>
	</div>
	<div class="card-body">
		<canvas id="StatusChart"></canvas>
	</div>
</div>
</div>
<div class="col-md-6">
<div class="card ctm-border-radius shadow-sm flex-fill">
	<div class="card-header">
		<h4 class="card-title mb-0">Jenis Kelamin</h4>
	</div>
	<div class="card-body">
		<canvas id="JKChart"></canvas>
	</div>
</div>
</div><div class="col-md-6">
<div class="card ctm-border-radius shadow-sm flex-fill">
	<div class="card-header">
		<h4 class="card-title mb-0">Pangkat</h4>
	</div>
	<div class="card-body">
		<canvas id="PangkatChart"></canvas>
	</div>
</div>
</div><div class="col-md-6">
<div class="card ctm-border-radius shadow-sm flex-fill">
	<div class="card-header">
		<h4 class="card-title mb-0">Status Pernikahan</h4>
	</div>
	<div class="card-body">
		<canvas id="PernikahanChart"></canvas>
	</div>
</div>
</div>
</div>
<script src="<?= url('plugins/dleohr/assets/js/Chart.min.js') ?>"></script>
<script src="assets/js/Chart.min.js"></script>
</div>
<script>
	var ctx = document.getElementById("BulananChart").getContext('2d');
	var lineChart = new Chart(ctx, {
		type: 'line',
		data: {
			labels: <?=$graph['labelabsen']?>,
			datasets: <?=$graph['bulanan']['data']?>
		},
		options: {
		  responsive: true,
			legend: {
				display: false
			}
		}
	});
	var ctx = document.getElementById("PekananChart").getContext('2d');
	var lineChart = new Chart(ctx, {
		type: 'line',
		data: {
			labels: <?=$graph['labelabsen']?>,
			datasets: <?=$graph['pekanan']['data']?>
		},
		options: {
		  responsive: true,
			legend: {
				display: false
			}
		}
	});
	
	var ctx = document.getElementById("PangkatChart").getContext('2d');
	var lineChart = new Chart(ctx, {
		type: 'bar',
		data: {
			labels: <?=$graph['pangkat']['label']?>,
			datasets: <?=$graph['pangkat']['data']?>
		},
		options: {
		  responsive: true,
			legend: {
				display: false
			}
		}
	});
	var ctx = document.getElementById("PernikahanChart").getContext('2d');
	var lineChart = new Chart(ctx, {
		type: 'bar',
		data: {
			labels: <?=$graph['pernikahan']['label']?>,
			datasets: <?=$graph['pernikahan']['data']?>
		},
		options: {
		  responsive: true,
			legend: {
				display: false
			}
		}
	});var ctx = document.getElementById("JKChart").getContext('2d');
	var lineChart = new Chart(ctx, {
		type: 'bar',
		data: {
			labels: <?=$graph['kelamin']['label']?>,
			datasets: <?=$graph['kelamin']['data']?>
		},
		options: {
		  responsive: true,
			legend: {
				display: false
			}
		}
	}); var ctx = document.getElementById("StatusChart").getContext('2d');
	var lineChart = new Chart(ctx, {
		type: 'bar',
		data: {
			labels: <?=$graph['status_kerja']['label']?>,
			datasets: <?=$graph['status_kerja']['data']?>
		},
		options: {
		  responsive: true,
			legend: {
				display: false
			}
		}
	}); 
</script>
@endsection

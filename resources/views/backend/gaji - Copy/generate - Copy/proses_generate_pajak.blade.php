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
					<h1 class="m-0 text-dark">Proses Generate</h1>
				</div><!-- /.col -->
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><a href="{!! route('admin') !!}">Admin</a></li>
						<li class="breadcrumb-item active">Proses Generate</li>
					</ol>
				</div><!-- /.col -->
			</div><!-- /.row -->
		</div><!-- /.container-fluid -->
	</div>
	<!-- /.content-header -->

	<!-- Main content -->
	<div class="card">
		<div class="card-body">


			<h3>Proses Generate PAJAK</h3>
			
		</div>


	</div>
	<div class="col-sm-12" id="contentGenerate">
	</div>

	<!-- /.card-body -->
</div>
<!-- /.card -->
<!-- /.card -->
<!-- /.content -->
</div>
<!-- /.content-wrapper -->
<?php

?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js">
</script>
<script>
	function hitung()
	{
		//alert();
		$.ajax({
			type: 'get',
			//data: {'id': id},
			url: '<?=route('be.hitung_pajak',$id_generate);?>',
			dataType: 'html',
			success: function(data) {
				$('#contentGenerate').html(data);

				//var tgl_cicilan = myDate.addMonths(cicilan);



				//console.log(data);
			},
			error: function (error) {
				console.log('error; ' + eval(error));
				//alert(2);
			}
		});
	}

	$(document).ready(function() {
		hitung();
		setInterval(hitung, 1500);

	});
</script>
@endsection

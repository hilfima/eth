@extends('layouts.app_fe')



@section('content')
	<div class="row">
	<div class="col-xl-3 col-lg-4 col-md-12 theiaStickySidebar">
		<?= view('layouts.app_side'); ?>
	</div>
	<div class="col-xl-9 col-lg-8 col-md-12">

					<!-- Page Title -->
					 <div class="content-wrapper">
    @include('flash-message')
   
  <link rel="stylesheet" href="<?=url('plugins\orgchart\css/jquery.orgchart.css')?>">
  <link rel="stylesheet" href="<?=url('plugins\orgchart\css/style.css')?>">
   <style>
    	.orgchart .node .content {
  height: auto !important;
  min-height: 20px;
  }
    </style>
    
	<!-- Main content -->
	<div class="card shadow-sm ctm-border-radius">
<div class="card-body align-center">
<h4 class="card-title float-left mb-0 mt-2">Struktur Organisasi</h4>

</div>
</div>
			
    
  <div id="chart-container"></div> 
		</div>
	</div>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <script type="text/javascript" src="<?=url('plugins\orgchart\js/jquery.min.js')?>"></script>
  <script type="text/javascript" src="<?=url('plugins\orgchart\js/jquery.orgchart.js')?>"></script>
  <script type="text/javascript">
    $(function() {

    var datasource = <?=(substr(json_encode($struktur),1,-1))?>;

    $('#chart-container').orgchart({
      'data' : datasource,
      'nodeContent': 'title'
    });

  });
  </script>
@endsection
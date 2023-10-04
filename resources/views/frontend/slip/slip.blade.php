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
      
    <!-- Content Header (Page header) -->
        <div class="card shadow-sm ctm-border-radius">
<div class="card-body align-center">
<h4 class="card-title float-left mb-0 mt-2">Slip Gaji</h4>

</div>
</div><div class="row">
<?php foreach($slip as $slip){?>
<div class="col-md-3 text-left" onclick="location.href='{!!route('fe.lihat_slip',$slip->prl_generate_id)!!}'">
<div class="card ctm-border-radius shadow-sm">
	<div class="card-body py-4">
		<div class=" " style="min-height: 100px">
			Gaji Periode 
			<h4 class="text-dark"><?=$slip->bulan.' '.$slip->tahun;?> </h4>
		</div>
										</div>
									</div>
								</div>
<?php }?>
							</div>
							@endsection
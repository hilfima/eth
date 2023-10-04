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
    <Style>
    	.profile-user-img{
			height: 50px;
			border-radius: 50%;
		}
    </Style>  
    <!-- Content Header (Page header) -->
        <div class="card shadow-sm ctm-border-radius">
<div class="card-body align-center">
<h4 class="card-title float-left mb-0 mt-2">Jobdesk Jabatan dan Bawahannya</h4>

</div>
</div><div class="row">
<?php foreach($jabatan as $jabatan){?>
<div class="col-md-12 text-left" >
<div class="card ctm-border-radius shadow-sm">
	<div class="card-body py-4">
		<div style="width: 100%;margin-bottom: 20px">
			
		</div>
		<div class="h5" style="">
			<?= $jabatan->nama?><br>
		</div>
			<div class="text-muted"><?= $jabatan->job_deskripsi_indonesia?></div>
										</div>
									</div>
								</div>
<?php }?>
							</div>
							@endsection
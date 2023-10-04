@extends('layouts.app_fe')

@section('content')
	<div class="row">
	<div class="col-xl-3 col-lg-4 col-md-12 theiaStickySidebar">
		<?= view('layouts.app_side'); ?>
	</div>
	<div class="col-xl-9 col-lg-8 col-md-12">

<!-- Content Wrapper. Contains page content -->
>  @include('flash-message')
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
		<h4 class="card-title float-left mb-0 mt-2">Team</h4>

	</div>
</div><div class="row">
	<?php foreach($karyawan as $karyawan){?>
		<div class="col-md-3 text-left" >
			<div class="card ctm-border-radius shadow-sm">
				<div class="card-body py-4 text-center">
					<div style="width: 100%;margin-bottom: 20px">
						@if($karyawan->foto!=null)
						@if (file_exists(asset('dist/img/profile/'.$karyawan->foto))) {   
						$filefound = '0';
						}
						<img src="{!! asset('dist/img/profile/'.$karyawan->foto) !!}" alt="User Avatar" class="profile-user-img img-fluid img-thumbnail">
						@else
						<img src="{!! asset('dist/img/profile/user.png') !!}" class="profile-user-img img-fluid img-circle" alt="User Image">
						@endif
                                    
						@else
						<img src="{!! asset('dist/img/profile/user.png') !!}" class="profile-user-img img-fluid img-circle" alt="User Image">
						@endif
					</div>
					<div class="h5" style="">
						<?= $karyawan->nama_karyawan?><br>
					</div>
					<div class="text-muted"><?= $karyawan->nmjabatan?></div>
				</div>
			</div>
		</div>
		<?php }?>
</div><!--onclick="location.href='{!!route('fe.lihat_karyawan',$karyawan->p_karyawan_id)!!}'"-->
@endsection
@extends('layouts.app_fe')



@section('content')
<div class="content-wrapper">
	@include('flash-message')
	<div class="row">
	<div class="col-xl-3 col-lg-4 col-md-12 theiaStickySidebar">
		<?= view('layouts.app_side'); ?>
	</div>
	<div class="col-xl-9 col-lg-8 col-md-12">

	<!-- Content Header (Page header) -->
	<style>
		.sticky {
			position: fixed;
			top: 100px;
			width: 96%;
			z-index: 999
		}

		/* Add some top padding to the page content to prevent sudden quick movement (as the header gets a new position at the top of the page (position:fixed and top:0) */
		.sticky + .content {
			padding-top: 102px;
		}
	</style>
	<div class="card ">
		<div class="card-body ">
			<h6 class="card-title "> Kotak Laporan <?=sprintf('%03d',$Kotak_laporan[0]->t_kotak_laporan_id) ?></h6>
			<h4 class="card-title float-left mb-0 mt-2"><?=$Kotak_laporan[0]->judul_laporan ?></h4>


		</div>
	</div>
	<div class="card ctm-border-radius shadow-sm flex-fill">

		<div class="card-body">
			<?=$Kotak_laporan[0]->laporan; ?>
		</div>

	</div>
</div>
</div>
</form>
<script src="http://code.jquery.com/jquery-1.11.3.min.js"> </script>

@endsection
@extends('layouts.appsA')

@section('content')
<div class="content-wrapper">
	@include('flash-message')
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
			<!--<div class="mr-auto right text-right">
				<a href="{!! route('be.edit_kotak_laporan',$Kotak_laporan[0]->t_kotak_laporan_id) !!}" target="_blank" title="Download"><span class="fa fa-edit"></span></a>
			</div>-->
			<!--<div> Kotak Laporan <?=sprintf('%03d',$Kotak_laporan[0]->t_kotak_laporan_id) ?></div>-->
			<h4 class="card-title mb-0"><?=ucwords($Kotak_laporan[0]->judul_laporan) ?></h4>
			 Tanggal Kejadian <?=$help->tgl_indo($Kotak_laporan[0]->tgl_kejadian) ?>


		</div>
	</div>
	<div class="card ctm-border-radius shadow-sm flex-fill">

		<div class="card-body">
			<?=$Kotak_laporan[0]->laporan; ?>
		</div>

	</div>
	<div class="card ctm-border-radius shadow-sm flex-fill">

		<div class="card-body"> 
			<form class="form-horizontal" method="POST" action="{!! route('be.update_kotak_laporan',$Kotak_laporan[0]->t_kotak_laporan_id) !!}" enctype="multipart/form-data">
				{{ csrf_field() }}
				<div class="form-group">
					<label>Tindakan</label>
					
						<select  class="form-control" id="gapok" name="status">
							<option value="">- Tindakan - </option>
							<option value="2">Diterima</option>
							<option value="3">Diproses</option>
							<option value="4">selesai</option>
						</select>

				</div> 
				<button type="submit" class="btn btn-info "> Simpan</button>
			</form>
			
		</div>

	</div>
</div>
</div>
</form>
<script src="http://code.jquery.com/jquery-1.11.3.min.js"> </script>

@endsection
  @extends('layouts.app_fe')



@section('content')
					<!-- Page Title -->
					 <div class="content-wrapper">
					 <div class="row">
			<div class="col-xl-3 col-lg-4 col-md-12 theiaStickySidebar">
						<?= view('layouts.app_side');?>
					</div>
<div class="col-xl-9 col-lg-8 col-md-12">
    @include('flash-message')
   
    
	<!-- Main content -->
	<div class="card shadow-sm ctm-border-radius">
<div class="card-body align-center">
<h4 class="card-title float-left mb-0 mt-2">Upload Interview</h4>
<ul class="nav nav-tabs float-right border-0 tab-list-emp">

</ul>
</div>
</div>
<div class="card">
<form class="form-horizontal" method="POST" action="{!! route('fe.simpan_upload_interview',[$id,$id_kandidat]) !!}" enctype="multipart/form-data">
	{{ csrf_field() }}{{ csrf_field() }}<div class="card-body">
		<div class="form-group">
				<label>File Interview 1</label>
				<input class="form-control " id="tgl_absen" name="file_interview1" type="file" >
			</div>
	<button type="submit" class="pull-right btn btn-theme button-1 text-white ctm-border-radius p-2 add-person ctm-btn-padding"><span class="fa fa-check"></span> Simpan</button>
	</div>
</div>
</form
@endsection
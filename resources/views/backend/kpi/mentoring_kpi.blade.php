@extends('layouts.appsA')

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content">
    @include('flash-message')
    <!-- Content Header (Page header) -->
    <div class="card shadow-sm ctm-border-radius">
        <div class="card-body align-center">
            <h4 class="card-title float-left mb-0 mt-2">Mentoring Key Perfomance Index</h4>
            <ul class="nav nav-tabs float-right border-0 tab-list-emp">
				<li class="nav-item pl-3">
									<a href="{!! route('be.mentoring_kpi',$id).'?Cari=pdf' !!}" title='Tambah'  class="btn btn-theme button-1 text-white ctm-border-radius p-2 add-person ctm-btn-padding">PDF</a>
				</li>
				
			</ul>

        </div>
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <?php 
    echo view('frontend.kpi.content_mentoring_kpi',compact('id','mentoring','kpi'));
    ?>
    <!-- /.card -->
    <!-- /.card -->
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
@endsection
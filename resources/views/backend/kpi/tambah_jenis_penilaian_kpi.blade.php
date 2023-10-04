@extends('layouts.appsA')

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content">
    @include('flash-message')
    <!-- Content Header (Page header) -->
    <div class="card shadow-sm ctm-border-radius">
        <div class="card-body align-center">
            <h4 class="card-title float-left mb-0 mt-2">Jenis Penilaian</h4>
            <ul class="nav nav-tabs float-right border-0 tab-list-emp">

				<li class="nav-item pl-3">
					<a href="{!! route('be.tambah_jenis_penilaian_kpi') !!}" title='Tambah'  class="btn btn-primary">Tambah KPI</a>
				</li>
			</ul>

        </div>
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="card">
       
        <!-- /.card-header -->
        <div class="card-body">
           

           <form class="form-horizontal" method="POST" action="{!! route('be.simpan_jenis_penilaian_kpi') !!}" enctype="multipart/form-data">
                    {{ csrf_field() }}
                            <!-- text input -->
                            <div class="form-group">
                                <label>Nama</label>
                                <input type="text" class="form-control" placeholder="Nama Penilaian ..." id="judul" name="nama_penilaian" required>
                            </div>
                   	     <div class="box-footer">
                        <a href="{!! route('be.jenis_penilaian_kpi') !!}" class="btn btn-default pull-left"><span class="fa fa-times"></span> Kembali</a>
                        <button type="submit" class="btn btn-primary"><span class="fa fa-check"></span> Simpan</button>
                    </div>
            </form>
        </div>
        <!-- /.card-body -->
    </div>
    <!-- /.card -->
    <!-- /.card -->
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
@endsection
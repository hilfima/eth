@extends('layouts.appsA')

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content">
    @include('flash-message')
    <!-- Content Header (Page header) -->
    <div class="card shadow-sm ctm-border-radius">
        <div class="card-body align-center">
            <h4 class="card-title float-left mb-0 mt-2">Parameter Penilaian</h4>
            <ul class="nav nav-tabs float-right border-0 tab-list-emp">

				<li class="nav-item pl-3">
					<a href="{!! route('be.tambah_parameter_penilaian_kpi') !!}" title='Tambah'  class="btn btn-primary">Tambah Point</a>
				</li>
			</ul>

        </div>
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="card">
       
        <!-- /.card-header -->
        <div class="card-body">
           

            <table id="example1" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Point</th>
                        <th>Nama </th>
                        <th>Keterangan</th>
                        <th>Klasifikasi</th>
                        
                    </tr>
                    
                </thead>
                <tbody>
                    <?php $no = 0 ?>
                    @if(!empty($penilaian))
                    @foreach($penilaian as $kpi)
                    <?php $no++ ?>
                    <tr>
                        <td>{!! $no !!}</td>
                        <td>{!! $kpi->point !!}</td>
                        <td>{!! $kpi->key !!}</td>
                        <td>{!! $kpi->deskripsi !!}</td>
                        <td>{!! $kpi->nama_point !!} - {!! $kpi->nama_penilaian !!}</td>
                        
                        <td style="text-align: center; display: flex;">
                           
                            
                        </td>
                    </tr>
                    @endforeach
                    @endif
            </table>
        </div>
        <!-- /.card-body -->
    </div>
    <!-- /.card -->
    <!-- /.card -->
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
@endsection
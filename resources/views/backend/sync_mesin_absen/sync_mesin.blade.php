@extends('layouts.appsA')

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
    @include('flash-message')
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark">Sync Absen</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{!! route('admin') !!}">Admin</a></li>
                            <li class="breadcrumb-item active">Sync Absen</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <div class="card">
            <div class="card-header">
                <!--<h3 class="card-title">DataTable with default features</h3>-->
                <a href="http://203.210.84.185/get-data/udp.php" target="_blank" title='Sync Absen' data-toggle='tooltip'><span class='fa fa-sync'></span> Sync Absen </a>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <table id="example1" class="table table-striped custom-table mb-0">
                    <thead>
                    <tr>
                        <th>No.</th>
                        <th>Mesin </th>
                        <th>PIN</th>
                        <th>Nama</th>
                        <th>Tgl. Absen</th>
                        <th>Jam Absen</th>
                        <th>Status Absen</th>
                        <th>Keterangan</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $no=0 ?>
                    @if(!empty($sync))
                        @foreach($sync as $sync)
                            <?php $no++ ?>
                            <tr>
                                <td>{!! $no !!}</td>
                                <td>{!! $sync->nmlokasi !!}</td>
                                <td>{!! $sync->pin !!}</td>
                                <td>{!! $sync->nmkaryawan !!}</td>
                                <td>{!! date('d-m-Y',strtotime($sync->date_time)) !!}</td>
                                <td>{!! date('H:i:s',strtotime($sync->date_time)) !!}</td>
                                <td>{!! $sync->sts_absen !!}</td>
                                @if(date('H:i',strtotime($sync->date_time))>'7:30')
                                    <td>Terlambat</td>
                                @else
                                    <td>-</td>
                                @endif
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

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
                        <h1 class="m-0 text-dark">Kebijakan Dan Prosedur</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{!! route('admin') !!}">Admin</a></li>
                            <li class="breadcrumb-item active">Kebijakan Dan Prosedur</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <div class="card">
            
            <!-- /.card-header -->
            <div class="card-body">
                <table id="example1" class="table table-striped custom-table mb-0">
                    <thead>
                    <tr>
                        <th>No.</th>
                        <th>Karyawan </th>
                        <th>Tanggal Baca </th>
                        <th>Waktu Baca </th>
                        <th>Menit Membaca </th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $no=0 ?>
                    @if(!empty($sop))
                        @foreach($sop as $sop)
                            <?php $no++ ?>
                            <tr>
                                <td>{!! $no !!}</td>
                                <td>{!! $sop->nama !!}</td>
                                <td>{!! $sop->tanggal_baca !!}</td>
                                <td>{!! $sop->waktu_baca !!}</td>
                                <td>{!! $sop->total_membaca !!} Detik
                                
                                <?php 
                                        $diff    =$sop->total_membaca;
                                        $jam    =floor($diff / (60 * 60));
        
								        //membagi sisa detik setelah dikurangi $jam menjadi menit
								        $menit    =$diff - $jam * (60 * 60);
										echo $jam .  ' jam dan ' . floor( $menit / 60 ) . ' menit';
                                ?></td>
                               
                              
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

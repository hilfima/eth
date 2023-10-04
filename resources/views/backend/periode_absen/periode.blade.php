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
                        <h1 class="m-0 text-dark">Periode {!!ucwords($tipe)!!}</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{!! route('admin') !!}">Admin</a></li>
                            <li class="breadcrumb-item active">Periode {!!ucwords($tipe)!!}</li>
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
                <a href="{!! route('be.tambah_periode',$tipe) !!}" title='Tambah' data-toggle='tooltip'><span class='fa fa-plus'></span> Periode {!!ucwords($tipe)!!} </a>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <table id="example1" class="table table-striped custom-table mb-0">
                    <thead>
                    <tr>
                        <th>No.</th>
                        <th>Tahun </th>
                        <th>Bulan </th>
                        <th>Tgl Awal</th>
                        <th>Tgl Akhir</th>
                        <th>Type</th>
                        <th>Periode Aktif</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $no=0 ?>
                    @if(!empty($periode))
                        @foreach($periode as $periode)
                            <?php $no++ ?>
                            <tr>
                                <td>{!! $no !!}</td>
                                <td>{!! $periode->tahun !!}</td>
                                <td>{!! $periode->bulan !!}</td>
                                <td>{!! date('d-m-Y',strtotime($periode->tgl_awal)) !!}</td>
                                <td>{!! date('d-m-Y',strtotime($periode->tgl_akhir)) !!}</td>
                                <td>{!! $periode->tipe !!}</td>
                                <td style="text-align: center">
                                    @if($periode->periode_aktif==1)
                                        Ya
                                    @else
                                        Tidak
                                    @endif
                                </td>
                                <td style="text-align: center">
                                    <a href="{!! route('be.edit_periode',[$periode->periode_absen_id,$tipe]) !!}" title='Ubah' data-toggle='tooltip'><span class='fa fa-edit'></span></a>
                                    <a href="{!! route('be.hapus_periode',[$periode->periode_absen_id,$tipe]) !!}" title='Hapus' data-toggle='tooltip'><span class='fa fa-trash'></span></a>
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

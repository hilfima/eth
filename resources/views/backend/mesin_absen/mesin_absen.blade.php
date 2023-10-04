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
                        <h1 class="m-0 text-dark">Mesin Absen</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{!! route('admin') !!}">Admin</a></li>
                            <li class="breadcrumb-item active">Mesin Absen</li>
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
                <a href="{!! route('be.tambah_mesin') !!}" title='Tambah' data-toggle='tooltip'><span class='fa fa-plus'></span> Mesin Absen </a>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <table id="example1" class="table table-striped custom-table mb-0">
                    <thead>
                    <tr>
                        <th>No.</th>
                        <th>Lokasi </th>
                        <th>Nama </th>
                        <th>IP</th>
                        <th>Port</th>
                        <th>Status</th>
                        <th>Jabatan Seharusnya</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $no=0 ?>
                    @if(!empty($mesin_absen))
                        @foreach($mesin_absen as $mesin_absen)
                            <?php $no++ ?>
                            <tr>
                                <td>{!! $no !!}</td>
                                <td>{!! $mesin_absen->nmlokasi !!}</td>
                                <td>{!! $mesin_absen->nama !!}</td>
                                <td>{!! $mesin_absen->ip !!}</td>
                                <td>{!! $mesin_absen->port !!}</td>
                                <td style="text-align: center">
                                    @if($mesin_absen->is_default==1)
                                        <label>Default</label>
                                    @else
                                        <label>-</label>
                                    @endif
                                </td>
                                <td>
                                    <?php 
                                    $jabatan = explode(',',$mesin_absen->list_jabatan);
                                    for($i=0;$i<count($jabatan);$i++){
                                        if($jabatan[$i]){
                                            echo $list_jabatan[$jabatan[$i]];
                                            if($i!=count($jabatan)-1){
                                                echo ',';
                                            }
                                        }
                                    }
                                    
                                    ?>
                                </td>
                                
                                <td style="text-align: center">
                                    <a href="{!! route('be.edit_mesin',$mesin_absen->mesin_id) !!}" title='Ubah' data-toggle='tooltip'><span class='fa fa-edit'></span></a>
                                    <a href="{!! route('be.hapus_mesin',$mesin_absen->mesin_id) !!}" title='Hapus' data-toggle='tooltip'><span class='fa fa-trash'></span></a>
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

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
                        <h1 class="m-0 text-dark">Pajak</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{!! route('admin') !!}">Admin</a></li>
                            <li class="breadcrumb-item active">Pajak</li>
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
                <!--<a href="{!! route('be.tambah_pajak') !!}" title='Tambah' data-toggle='tooltip'><span class='fa fa-plus'></span> Pajak </a>-->
            </div>
            <!-- /.card-header -->
            <?php 
             $editing_3 =(in_array(date('d'),array(10,11,12,13,25,26,27,28)))?true:false;
                if(!($editing_3)){
            ?>
            <div class="card-body">
                <table id="example1" class="table table-striped custom-table mb-0">
                    <thead>
                    <tr>
                        <th>No.</th>
                        <th>Nama Karyawan </th>
                        <th>Entitas</th>
                        <th>Pajak</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $no=0 ?>
                    @if(!empty($pajak))
                        @foreach($pajak as $pajak)
                            <?php $no++ ?>
                            <tr>
                                <td>{!! $no !!}</td>
                                <td>{!! $pajak->nama !!}</td>
                                <td>{!! $pajak->nmlokasi !!}</td>
                                <td>{!! $pajak->pajak_onoff !!}</td>
                                <td style="text-align: center">
                                    <a href="{!! route('be.edit_karyawan_pajak',$pajak->p_karyawan_id) !!}" title='Ubah' data-toggle='tooltip'><span class='fa fa-edit'></span></a>
                                    
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </table>
            </div>
            <?php }else{
                echo ' <div class="card-body">Untuk perubahan pajak on off di dalam rentang periode gajian tidak dapat dimenu ini, anda bisa merubahnya pada preview gaji di button "Edit Karyawan"</div>';
            }?>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
        <!-- /.card -->
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
@endsection

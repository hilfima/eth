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
                        <h1 class="m-0 text-dark"> <?=ucwords($title);?> Gaji</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{!! route('admin') !!}">Admin</a></li>
                            <li class="breadcrumb-item active"> <?=ucwords($title);?> Gaji</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <div class="card">
           <!-- <div class="card-header">
                
                <a href="{!! route('be.tambah_'.str_replace(' ','_',$title)) !!}" title='Tambah' data-toggle='tooltip'><span class='fa fa-plus'></span> <?=ucwords($title);?> </a>
            </div>-->
            <!-- /.card-header -->
            <div class="card-body">
                <table id="example1" class="table table-striped custom-table mb-0">
                    <thead>
                    <tr>
                        <th>No.</th>
                        <th>Nama </th>
                       
                        <th>Active</th>
                        <!--<th>Action</th>-->
                    </tr>
                    </thead>
                    <tbody>
                    <?php $no=0 ?>
                    @if(!empty($row))
                        @foreach($row as $data)
                            <?php $no++ ?>
                            <tr>
                                <td>{!! $no !!}</td>
                                <td>{!! $data->nama !!}</td>
                               
                                <td style="text-align: center">
                                    @if($data->active==1)
                                        <span class="fa fa-check-circle"></span>
                                    @else
                                        <span class="fa fa-window-close"></span>
                                    @endif
                                </td>
                               <!-- <td style="text-align: center">
                                    <a href="{!! route('be.edit_'.str_replace(' ','_',$title),$data->m_potongan_id) !!}" title='Ubah' data-toggle='tooltip'><span class='fa fa-edit'></span></a>
                                    <a href="{!! route('be.hapus_'.str_replace(' ','_',$title),$data->m_potongan_id) !!}" title='Hapus' data-toggle='tooltip'><span class='fa fa-trash'></span></a>
                                </td>-->
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

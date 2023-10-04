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
                        <h1 class="m-0 text-dark"> Admin</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{!! route('admin') !!}">Admin</a></li>
                            <li class="breadcrumb-item active"> Admin</li>
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
                <a href="{!! route('be.tambah_admin') !!}" title='Tambah' data-toggle='tooltip'><span class='fa fa-plus'></span>  Admin </a>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
           <!-- <div>
            	Ket.
            	<br>
            	untuk edit gunakan fitur tambah, dengan menginputkan kembali sesuai user<br>
            	untuk hapus gunakan juga fitur tambah dengan tidak mencentang menu apapun
            	
            	<br>
            	<br>
            	Terima Kasih
            </div>-->
                <table id="example1" class="table table-striped custom-table mb-0">
                    <thead>
                    <tr>
                        <th>No.</th>
                      
                        <th>Nama</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $no=0 ?>
                    @if(!empty($Admin))
                        @foreach($Admin as $Admin)
                            <?php $no++ ?>
                            <tr>
                                <td>{!! $no !!}</td>
                                <td>{!! $Admin->nama_role !!}</td>
                                <td>  <a href="{!! route('be.lihat_admin',[$Admin->m_role_id]) !!}" title='Lihat' data-toggle='tooltip'><span class='fa fa-search'></span></a>
                                  <a href="{!! route('be.edit_admin',[$Admin->m_role_id]) !!}" title='Lihat' data-toggle='tooltip'><span class='fa fa-edit'></span></a>
                                  <a href="{!! route('be.hapus_admin',[$Admin->m_role_id]) !!}" title='Lihat' data-toggle='tooltip'><span class='fa fa-trash'></span></a>
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

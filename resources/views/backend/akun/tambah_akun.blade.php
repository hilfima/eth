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
                        <h1 class="m-0 text-dark">Akun</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{!! route('admin') !!}">Admin</a></li>
                            <li class="breadcrumb-item active">Akun</li>
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
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <!-- form start -->
                <form class="form-horizontal" method="POST" action="{!! route('be.simpan_akun') !!}" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="row">
                        <div class="col-sm-12">
                            <!-- text input -->
                            <div class="form-group">
                                <label>Nama</label>
                                <input type="text" class="form-control" placeholder="Nama ..." id="judul" name="name" required>
                            </div>
                        </div>
                       
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label>Email</label>
                                <input class="form-control" name="Email"  type="email" placeholder="Email">
                            </div>
                        </div><div class="col-sm-12">
                            <div class="form-group">
                                <label>Username</label>
                                <input id="deskripsi_berita" class="form-control" name="username" required placeholder="Username">
                            </div>
                        </div> <div class="col-sm-12">
                            <div class="form-group">
                                <label>Password</label>
                                <input type="password" class="form-control" name="password" required placeholder="password">
                            </div>
                        </div>
                        <div class="col-sm-12">
                        <div class="form-group">
                                <label>Entitas</label>
                                <select class="form-control select2" multiple="" name="entitas[]" style="width: 100%;" >
                                    <option value="" disabled>Pilih Entitas</option>
                                    <?php 
                                    foreach($entitas AS $entitas){
                                        echo '<option value="'.$entitas->m_lokasi_id.'">'.$entitas->nama. '</option>';
                                    }
                                    ?>
                                    
                                </select>
                            </div>
                            </div>
                        <div class="col-sm-12">
                        <div class="form-group">
                                <label>Role</label>
                                <select class="form-control select2" name="role" style="width: 100%;" required>
                                    <option value="">Pilih Role</option>
                                    <?php 
                                    foreach($role AS $role){
                                        echo '<option value="'.$role->m_role_id.'">'.$role->nama_role. '</option>';
                                    }
                                    ?>
                                    
                                </select>
                            </div>
                            </div>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <a href="{!! route('be.akun') !!}" class="btn btn-default pull-left"><span class="fa fa-times"></span> Kembali</a>
                        <button type="submit" class="btn btn-info pull-right"><span class="fa fa-check"></span> Simpan</button>
                    </div>
                    <!-- /.box-footer -->
                </form>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
@endsection

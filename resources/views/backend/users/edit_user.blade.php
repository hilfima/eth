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
                        <h1 class="m-0 text-dark">Pengguna</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{!! route('admin') !!}">Admin</a></li>
                            <li class="breadcrumb-item active">Pengguna</li>
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
                <form class="form-horizontal" method="POST" action="{!! route('be.update_user',$edituser[0]->id) !!}" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label>Nama</label>
                                <select class="form-control select2" name="nama" style="width: 100%;" required>
                                    <option value="">Pilih Nama</option>
                                    <?php
                                    foreach($users AS $users){
                                        if($users->user_id==$edituser[0]->id){
                                            echo '<option selected="selected" value="'.$users->p_karyawan_id.'">'.$users->nama.'</option>';
                                        }
                                        else{
                                            echo '<option value="'.$users->p_karyawan_id.'">'.$users->nama.'</option>';
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                            <!--<div class="form-group">
                                <label>Username</label>
                                <input type="text" class="form-control" placeholder="Username..." id="username" name="username" required value="{!! $edituser[0]->username !!}">
                            </div>
                            <div class="form-group">
                                <label>Email</label>
                                <input type="email" class="form-control" placeholder="Email ..." id="email" name="email" required value="{!! $edituser[0]->email !!}">
                            </div>
                            <div class="form-group">
                                <label>Password</label>
                                <input type="password" class="form-control" placeholder="Password ..." id="password" name="password" required value="{!! $edituser[0]->password !!}">
                            </div>-->
                            <div class="form-group">
                                <label>Role</label>
                                <select class="form-control select2" name="role" style="width: 100%;" required>
                                    <option value="">Pilih Role</option>
                                    <?php 
                                     foreach($role AS $role){
                                        if($role->m_role_id==$edituser[0]->role){
                                            echo '<option selected="selected" value="'.$role->m_role_id.'">'.$role->nama_role.'</option>';
                                        }
                                        else{
                                            echo '<option value="'.$role->m_role_id.'">'.$role->nama_role.'</option>';
                                        }
                                    }
                                    ?>
                                    
                                </select>
                            </div>
                        </div>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <a href="{!! route('be.user') !!}" class="btn btn-default pull-left"><span class="fa fa-times"></span> Kembali</a>
                        <button type="submit" class="btn btn-info pull-right"><span class="fa fa-edit"></span> Ubah</button>
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

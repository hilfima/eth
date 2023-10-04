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
                        <h1 class="m-0 text-dark">Grup Jabatan</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{!! route('admin') !!}">Admin</a></li>
                            <li class="breadcrumb-item active">Grup Jabatan</li>
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
                <form class="form-horizontal" method="POST" action="{!! route('be.update_grup_jabatan',$grup_jabatan[0]->m_grupjabatan_id) !!}" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label>Nama</label>
                                <input type="text" class="form-control" placeholder="Nama Grup Jabatan..." id="nama" name="nama" value="{!! $grup_jabatan[0]->nama !!}" required>
                            </div>
                            <div class="form-group">
                                <label>Nama Jabatan</label>
                                <select class="form-control select2" name="jabatan" style="width: 100%;" required>
                                    <?php
                                    foreach($jabatan AS $jabatan){
                                        if($jabatan->m_jabatan_id==$grup_jabatan[0]->m_jabatan_id){
                                            echo '<option selected="selected" value="'.$jabatan->m_jabatan_id.'">'.$jabatan->nama.'</option>';
                                        }
                                        else{
                                            echo '<option value="'.$jabatan->m_jabatan_id.'">'.$jabatan->nama.'</option>';
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Nama Lokasi</label>
                                <select class="form-control select2" name="lokasi" style="width: 100%;" required>
                                    <?php
                                    foreach($lokasi AS $lokasi){
                                        if($lokasi->m_lokasi_id==$grup_jabatan[0]->m_lokasi_id){
                                            echo '<option selected="selected" value="'.$lokasi->m_lokasi_id.'">'.$lokasi->nama.'</option>';
                                        }
                                        else{
                                            echo '<option value="'.$lokasi->m_lokasi_id.'">'.$lokasi->nama.'</option>';
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <a href="{!! route('be.grup_jabatan') !!}" class="btn btn-default pull-left"><span class="fa fa-times"></span> Kembali</a>
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

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
                        <h1 class="m-0 text-dark">Hirarki Jabatan</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{!! route('admin') !!}">Admin</a></li>
                            <li class="breadcrumb-item active">Hirarki Jabatan</li>
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
                <form class="form-horizontal" method="POST" action="{!! route('be.'.$data['post'],$data['id']) !!}" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="row">
                        <div class="col-sm-6">
                            <!-- text input -->
                            <div class="form-group">
                                <label>Jabatan</label>
                                <select name="jabatan" class="form-control select2">
                               		<option value=""> -  Pilih Jabatan - </option>
                               		<?php 
                               		foreach($jabatan as $j){?>
                               		<option value="<?= $j->m_jabatan_id; ?>" <?= $j->m_jabatan_id==$data['jabatan']?'selected':'';?>><?= $j->nama.' - '.$j->nama_entitas ; ?></option>
                               		
                               		<?php }?>
                               	</select>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Hirarki Atasan</label>
                               	<select name="hirarki" class="form-control select2">
                               		<option value=""> -  Pilih Atasan - </option>
                               		<option value="0">Tidak Ada Atasan</option>
                               		<?php 
                               		foreach($jabatan as $j){?>
                               		<option value="<?= $j->m_jabatan_id; ?>"  <?= $j->m_jabatan_id==$data['hirarki']?'selected':'';?>><?= $j->nama.' - '.$j->nama_entitas ; ?></option>
                               		<?php }?>
                               	</select>
                            </div>
                        </div>
                        
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <a href="{!! route('be.berita') !!}" class="btn btn-default pull-left"><span class="fa fa-times"></span> Kembali</a>
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

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
                        <h1 class="m-0 text-dark">Kebijakan dan Prosedur</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{!! route('admin') !!}">Admin</a></li>
                            <li class="breadcrumb-item active">	Kebijakan dan Prosedur</li>
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
                <form class="form-horizontal" method="POST" action="{!! route('be.simpan_sop') !!}" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="row">
                        <div class="col-sm-12">
                            <!-- text input -->
                            <div class="form-group">
                                <label>Judul </label>
                                <input type="text" class="form-control" placeholder="Judul  ..." id="judul" name="judul" required>
                            </div>
                        </div><div class="col-sm-12">
                            <!-- text input -->
                            <div class="form-group">
                                <label>Unit Kerja </label>
                                <select type="text" class="form-control select2" multiple="" placeholder="Judul  ..." id="judul" name="dept[]" >
                                	<option value="" disabled>- Pilih Unit Kerja - </option>
                                	<?php foreach($departement as $dept){?>
										<option value="<?=$dept->m_departemen_id;?>"><?=$dept->nama;?></option>
                                	<?php }?>
                                </select>
                            </div>
							</div>
							<div class="col-sm-12">
								<!-- text input -->
								<div class="form-group">
									<label>File </label>
									<input type="file" class="form-control" placeholder="Judul  ..." id="judul" name="file" required accept="application/pdf">
								</div>
							</div><!--
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label>Deskripsi</label>
                                <textarea id="deskripsi_sop" name="deskripsi_sop" class="form-control summernote" required></textarea>
                            </div>
                        </div>-->
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <a href="{!! route('be.sop') !!}" class="btn btn-default pull-left"><span class="fa fa-times"></span> Kembali</a>
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

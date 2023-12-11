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
                        <h1 class="m-0 text-dark">	Kantor</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{!! route('admin') !!}">Admin</a></li>
                            <li class="breadcrumb-item active">Kantor</li>
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
                <form class="form-horizontal" method="POST" action="{!! route('be.simpan_Kantor') !!}" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="row">
                        <div class="col-sm-12">
                            
                            <div class="form-group">
                                <label>Nama Kantor</label>
                                <input type="text" class="form-control" placeholder="Nama Kantor..." id="nama" name="nama" required>
                            </div>
                           
                            <div class="form-group">
                                <label>Alamat</label>
                                <textarea id="alamat" class="form-control" name="alamat" placeholder="Alamat..."></textarea>
                            </div>
                            <div class="form-group">
                                                    <label>Lokasi Absen </label>
                                                    <select class="form-control select2 " placeholder="No. Absen..." id="no_absen" name="lokasi_absen" style="width:100%" >
                                                        <option value="">Pilih</option>
                                                        <?php foreach($absen as $absen){?>
                                                        <option value="<?=$absen->mesin_id;?>" ><?=$absen->nama;?></option>
                                                        <?php }?>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label>Entitas </label>
                                                    <select class="form-control select2 " multiple placeholder="Entitas..." id="entitas" name="entitas[]" style="width:100%" >
                                                        <option value="">Pilih</option>
                                                        <?php foreach($entitas as $entitas){?>
                                                        <option value="<?=$entitas->m_lokasi_id;?>" ><?=$entitas->nama;?></option>
                                                        <?php }?>
                                                    </select>
                                                </div>
                        </div>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <a href="{!! route('be.kantor') !!}" class="btn btn-default pull-left"><span class="fa fa-times"></span> Kembali</a>
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

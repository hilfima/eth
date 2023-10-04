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
                        <h1 class="m-0 text-dark">Hari Libur</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{!! route('admin') !!}">Admin</a></li>
                            <li class="breadcrumb-item active">Hari Libur</li>
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
                <form class="form-horizontal" method="POST" action="{!! route('be.update_hari_libur',$harilibur[0]->m_hari_libur_id) !!}" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label>Tanggal *</label>
                                <div class="input-group date" id="tanggal" data-target-input="nearest">
                                    <input type="date" class="form-control " id="tanggal" name="tanggal" value="{!! date('d-m-Y',strtotime($harilibur[0]->tanggal)) !!}" data-target="#tanggal" />
                                   
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <!-- text input -->
                            <div class="form-group">
                                <label>Nama *</label>
                                <input type="text" class="form-control" placeholder="Nama ..." id="nama" name="nama" value="{!! $harilibur[0]->nama !!}" required>
                            </div>
                        </div>

                        <div class="col-sm-4">
                            <div class="form-group">
                                <label>Berulang *</label>
                                <select class="form-control select2" name="berulang" style="width: 100%;" required>
                                    <option value="">Pilih</option>
                                    <option <?php if($harilibur[0]->is_berulang==1){ echo 'selected="selected" ';} ?> value="1">Ya</option>
                                    <option <?php if($harilibur[0]->is_berulang==0){ echo 'selected="selected" ';} ?> value="0">Tidak</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label>Cuti Bersama *</label>
                                <select class="form-control select2" name="cuti_bersama" style="width: 100%;" required>
                                    <option value="">Pilih</option>
                                    <option <?php if($harilibur[0]->is_cuti_bersama==1){ echo 'selected="selected" ';} ?> value="1">Ya</option>
                                    <option <?php if($harilibur[0]->is_cuti_bersama==0){ echo 'selected="selected" ';} ?> value="0">Tidak</option>
                                </select>
                            </div>
                        </div>
                         <div class="col-sm-4">
                            <div class="form-group">
                                <label>Tipe Cuti Beersama *</label>
                                <select class="form-control select2" name="tipe_cuti_bersama" style="width: 100%;" required>
                                    <option value="">Pilih</option>
                                    
                                    <option value="1">Cuti Untuk Semua karyawan Kategori, Tidak punya cuti = hutang cuti</option>
                                    <option value="2">Cuti Untuk Semua karyawan Karyawan >6 Bulan & kurang sisa = Hutang cuti, < 6 Bulan =potong gaji</option>
                                    <option value="3">Cuti Untuk yang mempunyai Hak Cuti, yang tidak mempunyai & kurang sisa =  potong gaji</option>
                                    <option value="4">Cuti Untuk yang mempunyai Hak Cuti, yang tidak mempunyai =  potong gaji, kurang sisa = Hutang Cuti </option>
                                    <option value="5">Cuti Untuk yang mempunyai Hak Cuti, yang tidak potong gaji</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <a href="{!! route('be.hari_libur') !!}" class="btn btn-default pull-left"><span class="fa fa-times"></span> Kembali</a>
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

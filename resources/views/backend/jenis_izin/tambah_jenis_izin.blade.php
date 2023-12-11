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
                        <h1 class="m-0 text-dark">jenis_izin</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{!! route('admin') !!}">Admin</a></li>
                            <li class="breadcrumb-item active">jenis_izin</li>
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
                <form class="form-horizontal" method="POST" action="{!! route('be.simpan_jenis_izin') !!}" enctype="multipart/form-data">
                    {{ csrf_field() }}
                            <!-- text input -->
                            <div class="form-group">
                                <label>Nama Pengajuan</label>
                                <input type="text" class="form-control" placeholder="Nama Pengajuan ..." id="judul" name="data[nama]" required>
                            </div>
                            <div class="form-group">
                                <label>Kode Pengajuan</label>
                                <input type="text" class="form-control" placeholder="Kode Pengajuan ..." id="judul" name="data[kode]" required>
                            </div>
                            <div class="form-group">
                                <label>Jumlah Batasan Hari Pengajuan</label>
                                <input type="text" class="form-control" placeholder="Jumlah Batasan Hari Pengajuan ..." id="judul" name="data[kode]" required>
                            </div>
                            
                            
                       
                            <div class="form-group">
                                <label>Tipe</label>
                                <select class="form-control" placeholder="Nama Batas ..." id="judul" name="data[tipe]" required>
                                    <option value="">Pilih</option>
                                    <option value="1">Izin</option>
                                    <option value="2">Lembur</option>
                                    <option value="3">Perdin</option>
                                    <option value="4">Cuti</option>
                                </select>
                            </div>
                       
                            
                            <div class="form-group">
                                <label>Wajib File</label>
                                <select class="form-control" placeholder="Nama Batas ..." id="judul" name="data[wajib_file]" required>
                                    <option value="">Pilih</option>
                                    <option value="1">Izin</option>
                                    <option value="2">Lembur</option>
                                    <option value="3">Cuti</option>
                                    <option value="4">Perdin</option>
                                    <option value="5">IDT IPM</option>
                                </select>
                            </div>
                       
                            <div class="form-group">
                                <label>Batas Pengajuan</label>
                                <select class="form-control" placeholder="Nama Batas ..." id="judul" name="data[m_batas_pengajuan_id]" required>
                                    <option value="">Pilih</option>
                                    <?php foreach($batas_pengajuan as $batas){?>
                                        
                                        <option value="<?=$batas->m_batas_pengajuan_id;?>"><?=$batas->nama_batas;?></option>
                                    <?php }?>
                                </select>
                            </div>
                       
                            <div class="form-group">
                                <label>Batas Atasan Approve</label>
                                <select class="form-control" placeholder="Nama Batas ..." id="judul" name="data[m_batasan_atasan_approve_id]" required>
                                    <option value="">Pilih</option>
                                    <?php foreach($batasan_atasan_approve as $batas){?>
                                        
                                        <option value="<?=$batas->m_batasan_atasan_approve_id;?>"><?=$batas->nama_batasan;?></option>
                                    <?php }?>
                                </select>
                            </div>
                       
                            
                       
                        
                    </div>
                    <!-- /.box-body -->
                    <div class="card-footer">
                        <a href="{!! route('be.jenis_izin') !!}" class="btn btn-default pull-left"><span class="fa fa-times"></span> Kembali</a>
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

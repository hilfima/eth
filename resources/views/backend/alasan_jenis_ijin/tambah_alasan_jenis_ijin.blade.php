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
                        <h1 class="m-0 text-dark">Alasan Jenis Izin</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{!! route('admin') !!}">Admin</a></li>
                            <li class="breadcrumb-item active">Alasan Jenis Izin</li>
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
                <form class="form-horizontal" method="POST" action="{!! route('be.simpan_alasan_jenis_ijin') !!}" enctype="multipart/form-data">
                    {{ csrf_field() }}
                            <!-- text input -->
                            <div class="form-group">
                                <label>Alasan</label>
                                <input type="text" class="form-control" placeholder="Alasan..." id="judul" name="data[alasan]" required>
                            </div>
                       
                            <div class="form-group">
                                <label>Jenis Izin</label>
                                <select class="form-control select2" placeholder="Nama Batas ..." id="batas_tipe" name="data[jenis]"   required>
                                    
                                    <option value="">Pilih</option>
                                    <?php foreach($jenis_ijin as $jenis){?>
                                    <option value="<?=$jenis->m_jenis_ijin_id;?>"><?= $jenis->nama;?></option>
                                    <?php }?>
                                </select>
                            </div>
                       
                        
                    </div>
                    <!-- /.box-body -->
                    <div class="card-footer">
                        <a href="{!! route('be.jenis_alasan') !!}" class="btn btn-default pull-left"><span class="fa fa-times"></span> Kembali</a>
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

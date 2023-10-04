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
                        <h1 class="m-0 text-dark">Libur Shift</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{!! route('admin') !!}">Admin</a></li>
                            <li class="breadcrumb-item active">Libur Shift</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <div class="card">
            
            <form class="form-horizontal" method="POST" action="{!! route('be.'.$type,$id) !!}" enctype="multipart/form-data">
            
            <div class="card-header">
                <h3 class="card-title mb-0	">Libur Shift</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <!-- form start -->
                    {{ csrf_field() }}
                    
                    <!-- ./row -->
                    <div class="row">
                     
                            <div class="col-md-12">
                            <div class="form-group">
                                <label>Tanggal</label>
                                <input type="date" class="form-control" id="tanggal" name="tanggal" placeholder="Nama Libur Shift" value="<?=$data['tanggal'];?>">
                            </div>
                        </div><div class="col-md-12">
                            <div class="form-group">
                                <label>Karyawan</label>
                                <select class="form-control select2" name="karyawan[]" multiple="" style="width: 100%;">
                                    <option value="" disabled>Pilih Nama</option>
                                    <?php
                                    foreach($karyawan AS $karyawan){
                                        if($karyawan->p_karyawan_id==$data['p_karyawan']){
                                            echo '<option selected="selected" value="'.$karyawan->p_karyawan_id.'">'.$karyawan->nama.' ('.$users->nmdept.') '. '</option>';
                                        }
                                        else{
                                            echo '<option value="'.$karyawan->p_karyawan_id.'">'.$karyawan->nama.' ('.$karyawan->nmdept.') '. '</option>';
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Keterangan</label>
                                <textarea type="number" class="form-control" id="nama" name="keterangan" placeholder="Keterangan" value=""><?=$data['keterangan'];?></textarea>
                            </div>
                        </div>
                       
                        
                        
                        
                           
                    </div>
                 
                        <a href="{!! route('be.libur_shift') !!}" class="btn btn-default pull-left"><span class="fa fa-times"></span> Kembali</a>
                        <button type="submit" class="btn btn-info pull-right"><span class="fa fa-edit"></span> Simpan</button>
                  <br>
                  <br>
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
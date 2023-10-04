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
                        <h1 class="m-0 text-dark">Club</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{!! route('admin') !!}">Admin</a></li>
                            <li class="breadcrumb-item active">Club</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <div class="card">
           
            <div class="card-body">
           
                <form class="form-horizontal" method="POST" action="{!! route('be.simpan_anggota_club',$id) !!}" enctype="multipart/form-data">
                    {{ csrf_field() }}
                   
                        
                        <div class="form-group">
                                                        <label>Admin</label>
                                                        <select class="form-control select2" name="karyawan_admin[]" multiple  style="width: 100%;" >
                                                           	<option value="">- Pilih Karyawan Admin- </option>
                                                            <?php
                                                            foreach($karyawan AS $karyawan2){
                                                               
                                                                    echo '<option value="'.$karyawan2->p_karyawan_id.'">'.$karyawan2->nama.'</option>';
                                                               
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                        <div class="form-group">
                                                        <label>Anggota</label>
                                                        <select class="form-control select2" name="karyawan_anggota[]" multiple style="width: 100%;" >
                                                           	<option value="">- Pilih Karyawan Anggota- </option>
                                                            <?php
                                                            foreach($karyawan AS $karyawan){
                                                               
                                                                    echo '<option value="'.$karyawan->p_karyawan_id.'">'.$karyawan->nama.'</option>';
                                                               
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <a href="{!! route('be.club') !!}" class="btn btn-default pull-left"><span class="fa fa-times"></span> Kembali</a>
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

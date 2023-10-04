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
                        <h1 class="m-0 text-dark">Jabatan</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{!! route('admin') !!}">Admin</a></li>
                            <li class="breadcrumb-item active">Jabatan</li>
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
                <form class="form-horizontal" method="POST" action="{!! route('be.update_jabatan',$datajabatan[0]->m_jabatan_id) !!}" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label>Kode</label>
                                <input type="text" class="form-control" placeholder="Kode Jabatan..." id="kode" name="kode" required value="{!! $datajabatan[0]->kode !!}">
                            </div>
                            <div class="form-group">
                                <label>Nama</label>
                                <input type="text" class="form-control" placeholder="Nama Jabatan..." id="nama" name="nama" required value="{!! $datajabatan[0]->nama !!}">
                            </div>
                            <div class="form-group">
                                <label>Pangkat</label>
                                <select class="form-control select2" name="pangkat" style="width: 100%;" required>
                                    <option value="">Pilih Pangkat</option>
                                    <?php
                                    foreach($pangkat AS $pangkat){
                                        if($pangkat->m_pangkat_id==$datajabatan[0]->m_pangkat_id){
                                            echo '<option selected="selected" value="'.$pangkat->m_pangkat_id.'">'.$pangkat->nama.'</option>';
                                        }
                                        else{
                                            echo '<option value="'.$pangkat->m_pangkat_id.'">'.$pangkat->nama.'</option>';
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Entitas</label>
                                <select class="form-control select2" name="lokasi" style="width: 100%;" required>
                                    <option value="">Pilih Entitas</option>
                                    <?php
                                    foreach($lokasi AS $lokasi){
                                        if($lokasi->m_lokasi_id==$datajabatan[0]->m_lokasi_id){
                                            echo '<option selected="selected" value="'.$lokasi->m_lokasi_id.'">'.$lokasi->nama.'</option>';
                                        }
                                        else{
                                            echo '<option value="'.$lokasi->m_lokasi_id.'">'.$lokasi->nama.'</option>';
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <label>Seksi</label>
                                <select class="form-control select2" name="departement" style="width: 100%;" required>
                                    <option value="">Pilih Seksi</option>
                                    <?php
                                    foreach($departemen AS $departement){
                                        $selected = "";
                                        if($departement->m_departemen_id==$datajabatan[0]->m_departemen_id){
                                             $selected = "selected";
                                        }
                                        echo '<option value="'.$departement->m_departemen_id.'" '.$selected.'>'.$departement->nama.' ('.$departement->nmentitas.' | '.$departement->nmdirectorat.' | '.$departement->nmdivisi.' | '.$departement->nmdepartemen.')</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                             <div class="form-group">
                                <label>Job Weight</label>
                                <input type="text" class="form-control" placeholder="Job Weight..." id="nama" name="job" value="{!! $datajabatan[0]->job !!}" required>
                            </div>
                            <div class="form-group">
                                <label>Keterangan</label>
                                <textarea id="keterangan" name="keterangan" class="form-control summernote" placeholder="Keterangan...">{!! $datajabatan[0]->keterangan !!}</textarea>
                            </div>
                            <div class="form-group">
                                <label>Job Desk/Tanggung Jawab Utama</label>
                                <textarea id="summernote" name="summernote" class="form-control summernote" placeholder="Job Deskripsi...">{!! $datajabatan[0]->job_deskripsi_indonesia !!}</textarea>
                            </div>
                        </div>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <a href="{!! route('be.jabatan') !!}" class="btn btn-default pull-left"><span class="fa fa-times"></span> Kembali</a>
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
    <link rel="stylesheet" href="{!! asset('plugins/purple/assets/plugins/summernote/dist/summernote-bs4.css')!!}">
		<script src="{!! asset('plugins/purple/assets/plugins/summernote/dist/summernote-bs4.min.js')!!}"></script>
@endsection

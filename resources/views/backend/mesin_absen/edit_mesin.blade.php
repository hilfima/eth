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
                        <h1 class="m-0 text-dark">Mesin Absen</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{!! route('admin') !!}">Admin</a></li>
                            <li class="breadcrumb-item active">Mesin Absen</li>
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
                <form class="form-horizontal" method="POST" action="{!! route('be.update_mesin',$mesin_absen[0]->mesin_id) !!}" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Nama</label>
                                <select class="form-control select2" name="lokasi" style="width: 100%;" required>
                                    <option value="">Pilih Lokasi</option>
                                    <?php
                                    foreach($lokasi AS $lokasi){
                                        if($lokasi->m_lokasi_id==$mesin_absen[0]->m_lokasi_id){
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
                        <div class="col-sm-6">
                            <!-- text input -->
                            <div class="form-group">
                                <label>Nama</label>
                                <input type="text" class="form-control" placeholder="Nama ..." id="nama" name="nama" required value="{!! $mesin_absen[0]->nama !!}">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>IP</label>
                                <input type="text" class="form-control" placeholder="IP ..." id="ip" name="ip" required value="{!! $mesin_absen[0]->ip !!}">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Port</label>
                                <input type="text" class="form-control" placeholder="Port ..." id="port" name="port" required value="{!! $mesin_absen[0]->port !!}">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Default</label>
                                <select class="form-control select2" name="default" style="width: 100%;" required>
                                    <option value="">Pilih</option>
                                    <option <?php if($mesin_absen[0]->is_default==1){ echo 'selected="selected" ';} ?> value="1">Ya</option>
                                    <option <?php if($mesin_absen[0]->is_default==0){ echo 'selected="selected" ';} ?> value="0">Tidak</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Jabatan</label>
                                <select class="form-control select2" name="jabatan[]" multiple style="width: 100%;" required>
                                    <option value="">Pilih Jabatan</option>
                                    <?php
                                    foreach($jabatan AS $jabatan){
                                        echo '<option value="'.$jabatan->m_jabatan_id.'" '.(in_array($jabatan->m_jabatan_id,explode(',', $mesin_absen[0]->list_jabatan))?'selected':'').'>'.$jabatan->nama.' - '.$jabatan->nmpangkat.'(Entitas:'.$jabatan->nmlokasi.' | Departemen : '.$jabatan->nmdepartemen.')</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <a href="{!! route('be.mesin') !!}" class="btn btn-default pull-left"><span class="fa fa-times"></span> Kembali</a>
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

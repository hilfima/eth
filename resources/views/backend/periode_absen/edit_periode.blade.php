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
                        <h1 class="m-0 text-dark">Periode {!!ucwords($tipe)!!}</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{!! route('admin') !!}">Admin</a></li>
                            <li class="breadcrumb-item active">Periode {!!ucwords($tipe)!!}</li>
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
                <form class="form-horizontal" method="POST" action="{!! route('be.update_periode',$periode[0]->periode_absen_id) !!}" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="row">
                        <div class="col-sm-3">
                            <!-- text input -->
                            <div class="form-group">
                                <label>Tahun</label>
                                <input type="text" class="form-control" placeholder="Tahun ..." id="tahun" name="tahun" value="{!! $periode[0]->tahun !!}" required>
                                <input type="hidden" class="form-control" name="tipe_periode" value="{!!$tipe!!}" required>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label>Bulan</label>
                                <select class="form-control select2" name="periode" style="width: 100%;" required>
                                    <option value="">Pilih</option>
                                    <option value="1" <?php if($periode[0]->periode==1){ echo 'selected="selected" ';} ?>>Januari</option>
                                    <option value="2" <?php if($periode[0]->periode==2){ echo 'selected="selected" ';} ?>>Februari</option>
                                    <option value="3" <?php if($periode[0]->periode==3){ echo 'selected="selected" ';} ?>>Maret</option>
                                    <option value="4" <?php if($periode[0]->periode==4){ echo 'selected="selected" ';} ?>>April</option>
                                    <option value="5" <?php if($periode[0]->periode==5){ echo 'selected="selected" ';} ?>>Mei</option>
                                    <option value="6" <?php if($periode[0]->periode==6){ echo 'selected="selected" ';} ?>>Juni</option>
                                    <option value="7" <?php if($periode[0]->periode==7){ echo 'selected="selected" ';} ?>>Juli</option>
                                    <option value="8" <?php if($periode[0]->periode==8){ echo 'selected="selected" ';} ?>>Agustus</option>
                                    <option value="9" <?php if($periode[0]->periode==9){ echo 'selected="selected" ';} ?>>September</option>
                                    <option value="10" <?php if($periode[0]->periode==10){ echo 'selected="selected" ';} ?>>Oktobe</option>
                                    <option value="11" <?php if($periode[0]->periode==11){ echo 'selected="selected" ';} ?>>November</option>
                                    <option value="12" <?php if($periode[0]->periode==12){ echo 'selected="selected" ';} ?>>Desember</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label>Tanggal Awal</label>
                                <div class="input-group date" id="tgl_awal" data-target-input="nearest">
                                    <input type="text" class="form-control datetimepicker-input" id="tgl_awal" name="tgl_awal" value="{!! $periode[0]->tgl_awal !!}" data-target="#tgl_awal" />
                                    <div class="input-group-append" data-target="#tgl_awal" data-toggle="datetimepicker">
                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label>Tanggal Akhir</label>
                                <div class="input-group date" id="tgl_akhir" data-target-input="nearest">
                                    <input type="text" class="form-control datetimepicker-input" id="tgl_akhir" name="tgl_akhir" value="{!! $periode[0]->tgl_akhir !!}" data-target="#tgl_akhir" />
                                    <div class="input-group-append" data-target="#tgl_akhir" data-toggle="datetimepicker">
                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label>Type Gajian</label>
                                <select class="form-control select2" name="type" style="width: 100%;" required>
                                    <option value="">Pilih</option>
                                    <option <?php if($periode[0]->type==1){ echo 'selected="selected" ';} ?> value="1">Bulanan</option>
                                    <option <?php if($periode[0]->type==0){ echo 'selected="selected" ';} ?> value="0">Pekanan</option>
                                </select>
                            </div>
                        </div>
                         <div class="col-sm-4">
                            <!-- text input -->
                            <div class="form-group">
                                <label>Pekanan Ke</label>
                                <input type="number" class="form-control" placeholder="Pekanan Ke ..." id="tahun" name="pekanan_ke" value="{!! $periode[0]->pekanan_ke !!}" required>
                            </div>
                        </div>
                         <div class="col-sm-4">
                            <!-- text input -->
                            <div class="form-group">
                                <label>Periode Aktif</label>
                                <select type="number" class="form-control" placeholder="Periode Aktif ..." id="tahun" name="periode_aktif" required>
                                <option value="">- Periode Aktif -</option>
                                <option value="1" <?php if($periode[0]->periode_aktif==1){ echo 'selected="selected" ';} ?> >Ya</option>
                                <option value="0"<?php if($periode[0]->periode_aktif==0){ echo 'selected="selected" ';} ?> >No</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-2">
                        	<div class="form-group">
                                <label>Entitas</label>
                                <select class="form-control" onchange="change_entitas()" onclick="change_entitas()" id="change_entitas_id" name="entitas" required>
                                <option value="">- Entitas -</option>
                                <option value="1" <?php if($periode[0]->entitas_type==1){ echo 'selected="selected" ';} ?>>Semua Entitas</option>
                                <option value="2" <?php if($periode[0]->entitas_type==2){ echo 'selected="selected" ';} ?>>Entitas Tertentu</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6">
                        <div id="kontent-entitas" style="width: 100%" >
                            <!-- text input -->
                            <div class="form-group" >
                                <label>Pilih Entitas</label>
                                <select type="number" class="form-control select2" multiple="" placeholder="Periode Aktif ..." id="pilih_entitas" name="list_entitas[]" >
                                
                                <?php

                                 foreach($entitas as $entitas){?>
                                <option value="<?=$entitas->m_lokasi_id;?>" <?php if(in_array($entitas->m_lokasi_id,explode(',',$periode[0]->entitas_list))){ echo 'selected="selected" ';} ?>><?=$entitas->nama;?></option>
                                <?php }?>
                                </select>
                            </div>
                        </div>
                        </div>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <a href="{!! route('be.periode',$tipe) !!}" class="btn btn-default pull-left"><span class="fa fa-times"></span> Kembali</a>
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

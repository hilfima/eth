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
                        <h1 class="m-0 text-dark">Potongan Absen Bulanan/Pekanan</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{!! route('admin') !!}">Admin</a></li>
                            <li class="breadcrumb-item active">Potongan Absen Bulanan</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <div class="card">
            <form class="form-horizontal" method="POST" action="{!! route('be.master_potongan_absen_simpan') !!}" enctype="multipart/form-data">
             {{ csrf_field() }}
                    
            <!-- /.card-header -->
            <div class="card-body">
            	<div class="row">

                    <div class="col-sm-12">
                        <div class="form-group">
                            <label>Pangkat</label>
                            <select class="form-control select2" name="pangkat" style="width: 100%;" required>
                                <option value="">Pilih Pangkat</option>
                                <?php
                                foreach($pangkat AS $pangkat){
                                    echo '<option value="'.$pangkat->m_pangkat_id.'">'.$pangkat->nama.'</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="form-group">
                             <label>Type Absen</label>
                             <select type="text" class="form-control select2"  name="type_absen" style="width: 100%;" value="" title="Pilih Type Absen">
                                <option value="absen">ABSEN</option>
                                <option value="alpha">ALPHA</option>
                                <option value="fingerprint">FINGERPRINT</option>
                                <option value="izin">IZIN</option>
                                <option value="pm">PM</option>
                             </select>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="form-group">
                            <label>Type Potongan</label>
                            <select type="text" class="form-control select2"  name="type_potongan" value="" style="width: 100%;" title="Pilih Potongan">
                                <option value="1">Nominal</option>
                                <option value="2">Persen</option>
                                <option value="3">Prorata</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="form-group">
                            <label>Absen Bulanan/Pekanan</label>
                            <select type="text" class="form-control select2"  name="periode_gajian" value="" style="width: 100%;" title="Pilih Periode Gajian">
                                <option value="0">Bulanan</option>
                                <option value="1">Pekanan</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="form-group">
                            <label>Nominal</label>
                            <input type="text" class="form-control"  name="nominal" value="" placeholder="Nominal">
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-info pull-right"><span class="fa fa-edit"></span> Simpan</button><br>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
        <!-- /.card -->
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
@endsection

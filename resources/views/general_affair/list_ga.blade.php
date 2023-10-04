@extends('layouts.app4')

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper" style="margin-left: 0px">
    @include('flash-message')
    <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-12" style="text-align: center">
                        <h1 class="m-2 text-dark"><b>LIST PENGAJUAN PEMBELIAN GENERAL AFFAIR</b></h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <div class="card">
            @include('flash-message')
            <div class="card-header">
                <!--<h3 class="card-title">DataTable with default features</h3>-->
                <form id="cari_list_ga" class="form-horizontal" method="get" action="{!! route('cari_list_ga') !!}">
                    {{ csrf_field() }}
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Tanggal Awal *</label>
                                <div class="input-group date" id="tanggal_awal" data-target-input="nearest">
                                    <input type="text" class="form-control datetimepicker-input" id="tanggal_awal" name="tanggal_awal" data-target="#tanggal_awal"  value="{!! $tgl_awal !!}"/>
                                    <div class="input-group-append" data-target="#tanggal_awal" data-toggle="datetimepicker">
                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Tanggal Akhir *</label>
                                <div class="input-group date" id="tanggal_akhir" data-target-input="nearest">
                                    <input type="text" class="form-control datetimepicker-input" id="tanggal_akhir" name="tanggal_akhir" data-target="#tanggal_akhir"  value="{!! $tgl_akhir !!}"/>
                                    <div class="input-group-append" data-target="#tanggal_akhir" data-toggle="datetimepicker">
                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label>Unit Kerja</label>
                                <select class="form-control select2" name="departemen" style="width: 100%;" required>
                                    <option>Pilih</option>
                                    <?php
                                    foreach($departemen AS $departemen){
                                        if($departemen->m_departemen_id==$unitkerja){
                                            echo '<option selected="selected" value="'.$departemen->m_departemen_id.'">'.$departemen->nama.'</option>';
                                        }
                                        else{
                                            echo '<option value="'.$departemen->m_departemen_id.'">'.$departemen->nama.'</option>';
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <button type="submit" name="Cari" class="btn btn-warning" value="Cari"><span class="fa fa-search"></span> Cari</button>
                                <button type="submit" name="Cari" class="btn btn-success" value="Excel"><span class="fa fa-file-excel"></span> Excel</button>
                                <a href="{!! route('ga_pembelian') !!}" class="btn btn-primary"><span class="fa fa-plus"></span> Tambah</a>
                                <a href="{!! route('ga') !!}" class="btn btn-danger"><span class="fa fa-backward"></span> Kembali</a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <table id="example1" class="table table-bordered table-striped">
                    <thead>
                    <tr>
                        <th>No.</th>
                        <th>Tanggal Pengajuan</th>
                        <th>Nama</th>
                        <th>Unit Kerja</th>
                        <th>Pengajuan</th>
                        <th>Nama Barang</th>
                        <th>Qty</th>
                        <th>Deskripsi</th>
                        <th>Link</th>
                        <th>Tanggal Digunakan</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $no=0 ?>
                    @if(!empty($ga))
                        @foreach($ga as $ga)
                            <?php $no++ ?>
                            <tr>
                                <td>{!! $no !!}</td>
                                <td>{!! date('d-m-Y',strtotime($ga->tgl_pengajuan)) !!}</td>
                                <td>{!! $ga->nama  !!}</td>
                                <td>{!! $ga->unit_kerja !!}</td>
                                <td>{!! $ga->sifat !!}</td>
                                <td>{!! $ga->nama_barang !!}</td>
                                <td>{!! $ga->qty !!}</td>
                                <td>{!! $ga->deskripsi !!}</td>
                                <td>{!! $ga->link !!}</td>
                                <td>{!! date('d-m-Y',strtotime($ga->tgl_digunakan)) !!}</td>
                            </tr>
                    @endforeach
                    @endif
                </table>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
@endsection

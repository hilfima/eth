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
                        <h1 class="m-0 text-dark">Cek Ajuan</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{!! route('admin') !!}">Admin</a></li>
                            <li class="breadcrumb-item active">Cek Ajuan</li>
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
                <!--<a href="http://localhost/get-data/udp.php" target="_blank" title='Sync Absen' data-toggle='tooltip'><span class='fa fa-sync'></span> Sync Absen </a>-->
                <form id="cari_absen" class="form-horizontal" method="get" action="{!! route('be.cari_ajuan') !!}">
                    {{ csrf_field() }}
                    <div class="row">
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label>Nama</label>
                                <select class="form-control select2" name="nama" style="width: 100%;">
                                    <option value="">Pilih Nama</option>
                                    <?php
                                    foreach($users AS $users){
                                        if($users->p_karyawan_id==$nama){
                                            echo '<option selected="selected" value="'.$users->p_karyawan_id.'">'.$users->nama.' ('.$users->nmdept.') '. '</option>';
                                        }
                                        else{
                                            echo '<option value="'.$users->p_karyawan_id.'">'.$users->nama.' ('.$users->nmdept.') '. '</option>';
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label>Jenis Ajuan</label>
                                <select class="form-control select2" name="tipe" style="width: 100%;">
                                    <option value="">Pilih Jenis Ajuan</option>
                                    <?php
                                    foreach($ajuan AS $ajuans){
                                        if($ajuans->tipe==$tipe){
                                            echo '<option selected="selected" value="'.$ajuans->tipe.'">'.$ajuans->nmtipe.'</option>';
                                        }
                                        else{
                                            echo '<option value="'.$ajuans->tipe.'">'.$ajuans->nmtipe. '</option>';
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label>Tanggal Awal</label>
                                <div class="input-group date" id="tgl_awal" data-target-input="nearest">
                                    <input type="date" class="form-control " id="tgl_awal" name="tgl_awal" value="{!! $tgl_awal!='1970-01-01'?$tgl_awal:date('Y-m-d') !!}" data-target="#tgl_awal"  required/>
                                  
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label>Tanggal Akhir</label>
                                <div class="input-group date" id="tgl_akhir" data-target-input="nearest">
                                    <input type="date" class="form-control " id="tgl_akhir" name="tgl_akhir" value="{!! $tgl_akhir!='1970-01-01'?$tgl_akhir:date('Y-m-d') !!}" data-target="#tgl_akhir" required/>
                                   
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-12">
                            <a href="{!! route('be.ajuan') !!}" class="btn btn-primary"><span class="fa fa-sync"></span> Reset</a>
                            <button type="submit" name="Cari" class="btn btn-primary" value="Cari"><span class="fa fa-search"></span> Cari</button>
                            <button type="submit" name="Cari" class="btn btn-primary" value="Excel"><span class="fa fa-file-excel"></span> Excel</button>

                        </div>
                    </div>
                </form>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <table id="example1" class="table table-striped custom-table mb-0">
                    <thead>
                    <tr>
                        <th>No.</th>
                        <th>Kode </th>
                        <th>Nama </th>
                        <th>Jenis </th>
                        <th>Tgl. Ajuan</th>
                        <th>Tgl Awal</th>
                        <th>Tgl Akhir</th>
                        <th>Jam Awal</th>
                        <th>Jam Akhir</th>
                        <th>Lama</th>
                        <th>Approval</th>
                        <th>Status Appr</th>
                        <th>Keterangan</th>
                        <th>Tipe Lembur</th>
                        <th>Gajian</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $no=0 ?>
                    @if(!empty($data))
                        @foreach($data as $datas)
                            <?php $no++ ?>
                            <tr>
                                <td>{!! $no !!}</td>
                                <td>{!! $datas->kode !!}</td>
                                <td>{!! $datas->nama_lengkap !!}</td>
                                <td>{!! $datas->nmtipe !!}</td>
                                <td>{!! date('d-m-Y', strtotime($datas->create_date)) !!}</td>
                                <td>{!! date('d-m-Y', strtotime($datas->tgl_awal)) !!}</td>
                                <td>{!! date('d-m-Y', strtotime($datas->tgl_akhir)) !!}</td>
                                <td>{!! $datas->jam_awal !!}</td>
                                <td>{!! $datas->jam_akhir !!}</td>
                                <td>{!! $datas->lama !!}</td>
                                <td>{!! $datas->nama_appr !!}</td>
                                <td>{!! $datas->sts_pengajuan !!}</td>
                                <td>{!! $datas->keterangan !!}</td>
                                <td>{!! $datas->tipe_lembur !!}</td>
                                <td>{!! $datas->gajian !!}</td>
                            </tr>
                        @endforeach
                    @endif
                </table>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
        <!-- /.card -->
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
@endsection

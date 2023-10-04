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
                        <h1 class="m-0 text-dark">Gaji Pokok Karyawan Pekanan</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{!! route('admin') !!}">Admin</a></li>
                            <li class="breadcrumb-item active">Gaji Pokok  Karyawan Pekanan</li>
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
                <a href="{!! route('be.tambah_gapok_pekanan') !!}" title='Tambah' data-toggle='tooltip'><span class='fa fa-plus'></span>Gaji Pokok  Karyawan Pekanan </a><a href="{!! route('be.tambah_excel_gapok_pekanan') !!}" title='Tambah' data-toggle='tooltip'><span class='fa fa-plus'></span>Import Excel</a>
                <!--<a href="{!! route('be.generate_nik') !!}" title='Generate' data-toggle='tooltip'><span class='fa fa-gear'></span> Generate NIK</a>-->
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <table id="example1" class="table table-striped custom-table mb-0">
                    <thead>
                    <tr>
                        <th>No.</th>
                        <th>NIK</th>
                        <th>Nama</th>
                        <th>Upah Harian</th>
                        <th>Status</th>
                        <th>Entitas</th>
                       
                        <th>Jabatan</th>
                        <th>Tgl Masuk</th>
                        <th>Tgl Keluar</th>
                        <th>Status Pekerjaan</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $no=0 ?>
                    @if(!empty($karyawan))
                        @foreach($karyawan as $karyawan)
                            <?php $no++ ?>
                            <tr>
                                <td>{!! $no !!}</td>
                                <td>{!! $karyawan->nik !!}</td>
                                <td>{!! $karyawan->nama_lengkap !!}</td>
                                <td>{!! $help->rupiah($karyawan->upah_harian) !!}</td>
                                <td>{!! $karyawan->status !!}</td>
                                <td>{!! $karyawan->nmlokasi !!}<br>{!! $karyawan->kantor !!}<br>{!! $karyawan->kota !!}</td>
                                <td>{!! $karyawan->nmjabatan !!}<br> Divisi: {!! $karyawan->nmdivisi !!}<br>Dept: {!! $karyawan->nmdept !!}</td>
                               
                                <td>{!! date('d-m-Y',strtotime($karyawan->tgl_awal)) !!}</td>
                                @if(!empty($karyawan->tgl_akhir))
                                <td>{!! date('d-m-Y',strtotime($karyawan->tgl_akhir)) !!}</td>
                                @else
                                    <td></td>
                                @endif
                                <td>{!! $karyawan->nmstatus !!}</td>
                                <td style="text-align: center">
                                    <a href="{!! route('be.lihat_gapok_pekanan',$karyawan->p_karyawan_id) !!}" title='Lihat' data-toggle='tooltip'><span class='fa fa-search'></span></a>
                                    <a href="{!! route('be.edit_gapok_pekanan',$karyawan->p_karyawan_id) !!}" title='Ubah' data-toggle='tooltip'><span class='fa fa-edit'></span></a>
                                    <a href="{!! route('be.hapus_gapok_pekanan',$karyawan->p_karyawan_id) !!}" title='Hapus' data-toggle='tooltip'><span class='fa fa-trash'></span></a>
                                </td>
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
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
                        <h1 class="m-0 text-dark">Karyawan</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{!! route('admin') !!}">Admin</a></li>
                            <li class="breadcrumb-item active">Karyawan</li>
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
                <a href="{!! route('be.tambah_karyawan') !!}" title='Tambah' data-toggle='tooltip' class="btn btn-danger  btn-sm"><span class='fa fa-plus'></span> Karyawan </a>
                <a href="{!! route('be.excel_karyawan') !!}" title='Tambah' data-toggle='tooltip' class="btn btn-primary  btn-sm"><span class='fa fa-download'></span> Excel Data Master Karyawan </a>
                <a href="{!! route('be.export_excel_karyawan') !!}" title='Tambah' data-toggle='tooltip' class="btn btn-success btn-sm"><span class='fa fa-download'></span> Export Karyawan </a>
                <a href="{!! route('be.file_karyawan') !!}" title='Tambah' data-toggle='tooltip' class="btn btn-warning btn-sm text-white"><span class='fa fa-download'></span> File Karyawan </a>
                <a href="{!! route('be.report_absen_karyawan') !!}" title='Tambah' data-toggle='tooltip' class="btn btn-warning btn-sm text-white"><span class='fa fa-download'></span> Report Absen Karyawan </a>
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
                        <th>Pajak</th>
                        <th>Bank</th>
                        <th>No Rek</th>
                        <th>No Absen</th>
                        <th>Entitas</th>
                        <th>Kantor</th>
                        <th>Kota</th>
                        <th>Divisi</th>
                        <th>Departemen</th>
                        <th>Jabatan</th>
                        <th>Tgl Bergabung</th>
                        <th>Tgl Awal Kontrak</th>
                        <th>Tgl Akhir Kontrak</th>
                        <th>Status Pekerjaan</th>
                        <th>Periode Gajian</th>
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
                                <td>{!! $karyawan->pajak_onoff !!}</td>
                                <td>{!! $karyawan->nama_bank !!}</td>
                                <td>{!! $karyawan->norek !!}</td>
								<td>{!! $karyawan->no_absen !!}</td>
                                <td>{!! $karyawan->nmlokasi !!}</td>
                                <td>{!! $karyawan->nama_kantor !!}</td>
                                <td>{!! $karyawan->kota !!}</td>
                                <td>{!! $karyawan->nmdivisi !!}</td>
                                <td>{!! $karyawan->nmdept !!}</td>
                                <td>{!! $karyawan->nmjabatan !!}</td>
                                <td>{!! date('d-m-Y',strtotime($karyawan->tgl_bergabung)) !!}</td>
                                <td>{!! date('d-m-Y',strtotime($karyawan->tgl_awal)) !!}</td>
                                @if(!empty($karyawan->tgl_akhir))
                                <td>{!! date('d-m-Y',strtotime($karyawan->tgl_akhir)) !!}</td>
                                @else
                                    <td></td>
                                @endif
                                <td>{!! $karyawan->nmstatus !!}</td>
                                <td>{!! $karyawan->periode_gajian !!}</td>
                                <td style="text-align: center">
                                    <a href="{!! route('be.view_karyawan',$karyawan->p_karyawan_id) !!}" title='Lihat' data-toggle='tooltip'><span class='fa fa-search'></span></a>
                                    <a href="{!! route('be.edit_karyawan',$karyawan->p_karyawan_id) !!}" title='Ubah' data-toggle='tooltip'><span class='fa fa-edit'></span></a>
                                    <a href="{!! route('be.hapus_karyawan',$karyawan->p_karyawan_id) !!}" title='Hapus' data-toggle='tooltip'><span class='fa fa-trash'></span></a>
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
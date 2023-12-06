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
                        <th>Data Karyawan</th>
                        
                        <th>Data Bank</th>
                        <th>Data Pekerjaan</th>
                        <th>Data Jabatan</th>
                        <th>Data Kontrak</th>
                        
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
                                <td>{!! $karyawan->nik !!}<br>
                                    {!! $karyawan->nama_lengkap !!}<br>
                                </td>
                               
                                <td>
                                    {!! $karyawan->nama_bank !!}<br>
                                    {!! $karyawan->norek !!}<br>
                                    {!! $karyawan->pajak_onoff !!}
                                </td>
                                 <td>
                                    
                                    {!! $karyawan->no_absen !!}<br>
                                    {!! $karyawan->nama_kantor !!}<br>
                                    {!! $karyawan->periode_gajian !!}<br>
                                    {!! $karyawan->nmstatus !!}<br>
								    {!! $karyawan->kota !!}
                                </td>
								<td>{!! $karyawan->nmlokasi !!}<br>
								    {!! $karyawan->nmdirectorat !!}<br>
								    {!! $karyawan->nama_divisi !!}<br>
								    {!! $karyawan->nmdivisi !!}<br>
								    {!! $karyawan->nmdept !!}<br>
								    {!! $karyawan->nmjabatan !!}<br>
								</td>
                               
                                <td>Bergabung : {!! date('d-m-Y',strtotime($karyawan->tgl_bergabung)) !!}<br>
                                    Awal Kontrak : {!! date('d-m-Y',strtotime($karyawan->tgl_awal)) !!}<br>
                                    Akhir Kontrak : @if(!empty($karyawan->tgl_akhir))
                                                        {!! date('d-m-Y',strtotime($karyawan->tgl_akhir)) !!}
                                                    @else
                                                        -
                                                    @endif
                                    
                               
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
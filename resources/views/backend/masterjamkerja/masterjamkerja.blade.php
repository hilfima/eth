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
                        <h1 class="m-0 text-dark">Jam Master Reguler</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{!! route('admin') !!}">Admin</a></li>
                            <li class="breadcrumb-item active">Jam Master Reguler</li>
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
                <a href="{!! route('be.tambah_masterjamkerja') !!}" title='Tambah' data-toggle='tooltip'><span class='fa fa-plus'></span> Jam Master Reguler </a>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <table id="example1" class="table table-striped custom-table mb-0">
                    <thead>
                    <tr>
                        <th>No.</th>
                        <th>Nama </th>
                        <th>Hari Masuk</th>
                        <th>Jam Kerja</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $no=0 ?>
                    @if(!empty($masterjamkerja))
                        @foreach($masterjamkerja as $masterjamkerja)
                            <?php $no++ ?>
                            <tr>
                                <td>{!! $no !!}</td>
                                <td>{!! $masterjamkerja->nama_master !!}</td>
                                <td> 
                                    Senin : {!! (($masterjamkerja->masuk_senin?'Masuk':'Libur')) !!}<br>
                                    Selasa : {!! (($masterjamkerja->masuk_selasa?'Masuk':'Libur')) !!}<br>
                                    Rabu : {!! (($masterjamkerja->masuk_rabu?'Masuk':'Libur')) !!}<br>
                                    Kamis : {!! (($masterjamkerja->masuk_kamis?'Masuk':'Libur')) !!}<br>
                                    Jumat : {!! (($masterjamkerja->masuk_jumat?'Masuk':'Libur')) !!}<br>
                                    Sabtu : {!! (($masterjamkerja->masuk_sabtu?'Masuk':'Libur')) !!}<br>
                                    Ahad : {!! (($masterjamkerja->masuk_ahad?'Masuk':'Libur')) !!}<br>
                                
                                </td>
                                <td>
                                    Jam Masuk : {!! (($masterjamkerja->jam_masuk)) !!}<br>
                                    Jam Keluar : {!! (($masterjamkerja->jam_keluar)) !!}<br>
                                    Batas Jam Masuk Kerja : {!! (($masterjamkerja->jam_batas_masuk)) !!}<br>
                                    Batas Jam Keluar Kerja : {!! (($masterjamkerja->jam_batas_keluar)) !!}<br>
                                </td>
                                
                                <td style="text-align: center">
                                    <!-- <a href="{!! route('be.edit_masterjamkerja',$masterjamkerja->m_jamkerja_reguler_id) !!}" title='Ubah' data-toggle='tooltip'><span class='fa fa-edit'></span></a> -->
                                    <a href="{!! route('be.hapus_masterjamkerja',$masterjamkerja->m_jamkerja_reguler_id) !!}" title='Hapus' data-toggle='tooltip'><span class='fa fa-trash'></span></a>
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

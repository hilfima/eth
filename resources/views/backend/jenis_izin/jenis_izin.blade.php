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
                        <h1 class="m-0 text-dark">Jenis Izin</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{!! route('admin') !!}">Admin</a></li>
                            <li class="breadcrumb-item active">Jenis Izin</li>
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
                <a href="{!! route('be.tambah_jenis_izin') !!}" title='Tambah' data-toggle='tooltip'><span class='fa fa-plus'></span> Jenis Izin </a>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <table id="example1" class="table table-striped custom-table mb-0">
                    <thead>
                    <tr>
                        <th>No.</th>
                        <th>Kode </th>
                        <th>Nama Izin </th>
                        <th>Jumlah</th>
                        <th>Satuan</th>
                        <th>Tipe</th>
                        <th>Batas Pengajuan</th>
                        <th>Wajib File</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $no=0 ?>
                    @if(!empty($jenis_izin))
                        @foreach($jenis_izin as $jenis_izin)
                            <?php $no++ ?>
                            <tr>
                                <td>{!! $no !!}</td>
                                <td>{!! $jenis_izin->kode !!}</td>
                                <td>{!! $jenis_izin->nama !!}</td>
                                <td>{!! (($jenis_izin->jumlah)) !!}</td>
                                <td>{!! (($jenis_izin->satuan)) !!}</td>
                                <td style="text-align: center">
                                    @if($jenis_izin->tipe==1)
                                        Izin
                                    @elseif($jenis_izin->tipe==2)
                                        Lembur
                                    @elseif($jenis_izin->tipe==3)
                                        Cuti
                                    @elseif($jenis_izin->tipe==4)
                                        Perdin
                                    @elseif($jenis_izin->tipe==5)
                                        IDT IPM
                                    @endif
                                </td>
                                <td>{!! (($jenis_izin->nama_batas)) !!}</td>
                                <td style="text-align: center">
                                    @if($jenis_izin->wajib_file==1)
                                        Ya
                                    @else
                                        Tidak
                                    @endif
                                </td>
                                <td style="text-align: center">
                                    <a href="{!! route('be.edit_jenis_izin',$jenis_izin->m_jenis_ijin_id) !!}" title='Ubah' data-toggle='tooltip'><span class='fa fa-edit'></span></a>
                                    <a href="{!! route('be.hapus_jenis_izin',$jenis_izin->m_jenis_ijin_id) !!}" title='Hapus' data-toggle='tooltip'><span class='fa fa-trash'></span></a>
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

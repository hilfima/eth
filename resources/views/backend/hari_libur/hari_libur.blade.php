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
                        <h1 class="m-0 text-dark">Hari Libur</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{!! route('admin') !!}">Admin</a></li>
                            <li class="breadcrumb-item active">Hari Libur</li>
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
                <a href="{!! route('be.tambah_hari_libur') !!}" title='Tambah' data-toggle='tooltip'><span class='fa fa-plus'></span> Hari Libur </a>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <table id="example1" class="table table-striped custom-table mb-0">
                    <thead>
                    <tr>
                        <th>No.</th>
                        <th>Tanggal </th>
                        <th>Nama </th>
                        <th>Jumlah</th>
                        <th>Berulang</th>
                        <th>Cuti Bersama</th>
                        <th>Tipe</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $no=0;
                    $type = array("Cuti Untuk Semua karyawan Kategori Tidak punya cuti = hutang cuti","Cuti Untuk Semua karyawan Karyawan >6 Bulan & kurang sisa = Hutang cuti, < 6 Bulan =potong gaji","Cuti Untuk yang mempunyai Hak Cuti, yang tidak mempunyai & kurang sisa =  potong gaji","Cuti Untuk yang mempunyai Hak Cuti, yang tidak mempunyai =  potong gaji, kurang sisa = Hutang Cuti","Cuti Untuk yang mempunyai Hak Cuti, yang tidak potong gaji");
                     ?>
                    @if(!empty($harilibur))
                        @foreach($harilibur as $harilibur)
                            <?php $no++ ?>
                            <tr>
                                <td>{!! $no !!}</td>
                                <td>{!! date('d-m-Y',strtotime($harilibur->tanggal)) !!}</td>
                                <td>{!! $harilibur->nama !!}</td>
                                <td>{!! $harilibur->jumlah !!}</td>
                                <td>{!! $harilibur->berulang !!}</td>
                                <td>{!! $harilibur->cuti_bersama !!}</td>
                                <td>{!! $harilibur->is_cuti_bersama?$type[$harilibur->tipe_cuti_bersama-1]:'' !!}</td>
                                <td style="text-align: center">
                                    @if($harilibur->active==1)
                                        <span class="fa fa-check-circle"></span>
                                    @else
                                        <span class="fa fa-window-close"></span>
                                    @endif
                                </td>
                                <td style="text-align: center">
                                    <a href="{!! route('be.edit_hari_libur',$harilibur->m_hari_libur_id) !!}" title='Ubah' data-toggle='tooltip'><span class='fa fa-edit'></span></a>
                                    <a href="{!! route('be.hapus_hari_libur',$harilibur->m_hari_libur_id) !!}" title='Hapus' data-toggle='tooltip'><span class='fa fa-trash'></span></a>
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

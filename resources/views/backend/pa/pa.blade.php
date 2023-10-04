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
                        <h1 class="m-0 text-dark">Penilaian Kinerja</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{!! route('admin') !!}">Admin</a></li>
                            <li class="breadcrumb-item active">Penilaian Kinerja</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <div class="card">
            <div class="card-header">
                <!--<h3 class="card-title">DataTable with default features</h3>
                
                <a href="{!! route('be.tambah_pa') !!}" title='Tambah' data-toggle='tooltip'><span class='fa fa-plus'></span> Penilaian Kinerja </a>-->
            </div>
            <!-- /.card-header --> 
            <div class="card-body">
                <table id="example1" class="table table-striped custom-table mb-0">
                    <thead>
                    <tr>
                        <th>No.</th>
                        <th>NIK</th>
                        <th>Nama</th>
                        <th>Tanggal</th>
                        <th>Bulan</th>
                        <th>Tahun</th>
                        <th>Total</th>
                        <th>Rata2</th>
                        <th>Penilai</th>
                        <th>Approve</th>
                        <th>Status</th>
                        <th>Active</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $no=0 ?>
                    @if(!empty($pa))
                        @foreach($pa as $pa)
                            <?php $no++ ?>
                            <tr>
                                <td>{!! $no !!}</td>
                                <td>{!! $pa->nik !!}</td>
                                <td>{!! $pa->nama !!}</td>
                                <td>{!! date('d-m-Y', strtotime($pa->tanggal)) !!}</td>
                                <td>{!! $pa->bulan !!}</td>
                                <td>{!! $pa->tahun !!}</td>
                                <td style="text-align: right">{!! number_format($pa->total,0) !!}</td>
                                <td style="text-align: right">{!! number_format($pa->rata2,2) !!}</td>
                                <td>{!! $pa->penilai !!}</td>
                                <td>{!! $pa->approve !!}</td>
                                <td>{!! $pa->sts_pa !!}</td>
                                <td style="text-align: center">
                                    @if($pa->active==1)
                                        <span class="fa fa-check-circle"></span>
                                    @else
                                        <span class="fa fa-window-close"></span>
                                    @endif
                                </td>
                                <td style="text-align: center">
                                    <a href="{!! route('be.view_pa',[$pa->m_pa_jawaban_id,'Cari=lihat']) !!}" title='Ubah' data-toggle='tooltip'><span class='fa fa-search'></span></a>
                                    <a href="{!! route('be.view_pa',[$pa->m_pa_jawaban_id,'Cari=print']) !!}" title='Ubah' data-toggle='tooltip'><span class='fa fa-print'></span></a>
                                    @if($pa->status==0)
                                        <!--<a href="{!! route('be.edit_pa',$pa->m_pa_jawaban_id) !!}" title='Ubah' data-toggle='tooltip'><span class='fa fa-edit'></span></a>-->
                                        <a href="{!! route('be.approve_pa',$pa->m_pa_jawaban_id) !!}" title='Approve' data-toggle='tooltip'><span class='fa fa-check'></span></a>
                                        <a href="{!! route('be.hapus_pa',$pa->m_pa_jawaban_id) !!}" title='Hapus' data-toggle='tooltip'><span class='fa fa-trash'></span></a>
                                    @endif
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

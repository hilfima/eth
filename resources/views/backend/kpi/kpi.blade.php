@extends('layouts.appsA')

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content">
    @include('flash-message')
    <!-- Content Header (Page header) -->
    <div class="card shadow-sm ctm-border-radius">
        <div class="card-body align-center">
            <h4 class="card-title float-left mb-0 mt-2">Key Perfomance Index</h4>
            <ul class="nav nav-tabs float-right border-0 tab-list-emp">

				<li class="nav-item pl-3">
					<a href="{!! route('be.tambah_kpi') !!}" title='Tambah'  class="btn btn-theme button-1 text-white ctm-border-radius p-2 add-person ctm-btn-padding">Tambah KPI</a>
				</li>
			</ul>

        </div>
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="card">
       
        <!-- /.card-header -->
        <div class="card-body">
           

            <table id="example1" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Nama</th>
                        <th>Jabatan</th>
                        <th>Tahun </th>
                        <th>Atasan 1</th>
                        <th>Status Approval 1</th>
                        <th>Atasan 2</th>
                        <th>Status Approval 2</th>
                        <th>Action </th>
                    </tr>
                    
                </thead>
                <tbody>
                    <?php $no = 0 ?>
                    @if(!empty($kpi))
                    @foreach($kpi as $kpi)
                    <?php $no++ ?>
                    <tr>
                        <td>{!! $no !!}</td>
                        <td>{!! $kpi->nama !!}</td>
                        <td>{!! $kpi->jabatan !!}</td>
                        <td>{!! $kpi->tahun !!}</td>
                        <td>{!! $kpi->appr_1 !!}</td>
                        <td style="text-align: center">
                            @if($kpi->status_appr_1==1)
                            <span class="fa fa-check-circle"> Disetujui</span>
                            @elseif($kpi->status_appr_1==2)
                            <span class="fa fa-window-close"> Ditolak</span>
                            @else
                            <span class="fa fa-edit"> Pending</span>
                            @endif
                        </td>
                        <td>{!! $kpi->appr_2 !!}</td>
                        <td style="text-align: center">
                            @if($kpi->status_appr_2==1)
                            <span class="fa fa-check-circle"> Disetujui</span>
                            @elseif($kpi->status_appr_2==2)
                            <span class="fa fa-window-close"> Ditolak</span>
                            @else
                            <span class="fa fa-edit"> Pending</span>
                            @endif
                        </td>
                        <td style="text-align: center; display: flex;">
                           
                            <div class="d-flex">
                            	
                            <a href="{!! route('be.kpi_detail',$kpi->t_kpi_id) !!}" class="p-2" title='Detail Capaian' data-toggle='tooltip'><span class='fa fa-eye'></span></a>
                            
                            
                            
                            <a href="{!! route('be.evaluasi_tahunan',$kpi->t_kpi_id).'?Cari=View' !!}" class="p-2" title='Evaluasi Tahunan' data-toggle='tooltip'><span class='fa fa-file'></span></a>
                            <a href="{!! route('be.mentoring_kpi',$kpi->t_kpi_id).'?Cari=View' !!}" class="p-2" title='Coaching & Mentoring' data-toggle='tooltip'><span class='fa fa-user '></span></a>
                            
                            </div>
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
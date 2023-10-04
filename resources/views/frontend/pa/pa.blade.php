@extends('layouts.app_fe')

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
    @include('flash-message')
    
        <!-- Content Header (Page header) -->
     <div class="row">
			<div class="col-xl-3 col-lg-4 col-md-12 theiaStickySidebar">
						<?= view('layouts.app_side');?>
					</div>
<div class="col-xl-9 col-lg-8 col-md-12">
 <div class="card shadow-sm ctm-border-radius">
        <div class="card-body align-center">
            <h4 class="card-title float-left mb-0 mt-2">Penilaian Kinerja</h4>
            <ul class="nav nav-tabs float-right border-0 tab-list-emp">

                <li class="nav-item pl-3">
                    <a href="{!! route('fe.tambah_pa') !!}" title='Tambah' class="btn btn-theme button-1 text-white ctm-border-radius p-2 add-person ctm-btn-padding"><span class='fa fa-plus'></span>Penilaian Kinerja</a>
                </li>
            </ul>

        </div>
    </div>
    
        <!-- /.content-header -->

        <!-- Main content -->
        <div class="card">
           <!-- <div class="card-header">
            
               
               
                <a href="{!! route('fe.tambah_pa') !!}" title='Tambah' data-toggle='tooltip'><span class='fa fa-plus'></span> Penilaian Kinerja </a>
               
            </div>-->
            <!-- /.card-header -->
            <div class="card-body">
                <table id="example1" class="table table-bordered table-striped">
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
                                    <a href="{!! route('fe.view_pa',$pa->m_pa_jawaban_id) !!}" title='Ubah' data-toggle='tooltip'><span class='fa fa-search'></span></a>
                                    @if($pa->status==0)
                                        <a href="{!! route('fe.edit_pa',$pa->m_pa_jawaban_id) !!}" title='Ubah' data-toggle='tooltip'><span class='fa fa-edit'></span></a>
                                          @if($pa->idapprove==$id)
                                        <a href="{!! route('fe.approve_pa',$pa->m_pa_jawaban_id) !!}" title='Approve' data-toggle='tooltip'><span class='fa fa-check'></span></a>
                                    	@endif
                                        
                                        <a href="{!! route('fe.hapus_pa',$pa->m_pa_jawaban_id) !!}" title='Hapus' data-toggle='tooltip'><span class='fa fa-trash'></span></a>
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

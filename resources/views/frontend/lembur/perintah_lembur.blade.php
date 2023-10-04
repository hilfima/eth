@extends('layouts.app_fe')

@section('content')
    <!-- Content Wrapper. Contains page content -->
<div class="row">
			<div class="col-xl-3 col-lg-4 col-md-12 theiaStickySidebar">
						<?= view('layouts.app_side',compact('help'));?>
					</div>
<div class="col-xl-9 col-lg-8 col-md-12">
    <div class="content-wrapper">
    @include('flash-message')
        <!-- Content Header (Page header) -->
        <div class="card shadow-sm ctm-border-radius">
<div class="card-body align-center">
<h4 class="card-title float-left mb-0 mt-2">Perintah Lembur</h4>

</div>
</div>

        <!-- /.content-header -->

        <!-- Main content -->
        <div class="card">
            <div class="card-header">
                <!--<h3 class="card-title">DataTable with default features</h3>-->
                
                <a href="{!! route('fe.tambah_perintah_lembur') !!}" title='Tambah' data-toggle='tooltip'><span class='fa fa-plus'></span> Perintah Lembur Satuan</a>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
               <table id="example1" class="table table-bordered table-striped">
                    <thead>
                    <tr>
                        <th>No.</th>
                        <th>NIK</th>
                        <th>Nama</th>
                        <th>Lembur</th>
                        <th>Tgl Awal</th>
                        <th>Tgl Akhir</th>
                        <th>PJS</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $no=0 ?>
                    @if(!empty($lembur))
                        @foreach($lembur as $lembur)
                            <?php $no++ ?>
                            <tr>
                                <td>{!! $no !!}</td>
                                <td>{!! $lembur->nik !!}</td>
                                <td>{!! $lembur->nama !!}</td>
                                <td>{!! $lembur->nama_lembur !!}</td>
                                <td>{!! date('d-m-Y', strtotime($lembur->tgl_awal)) !!}</td>
                                <td>{!! date('d-m-Y', strtotime($lembur->tgl_akhir)) !!}</td>
                                <td>{!! $lembur->pjs !!}</td><td>{!! $lembur->nama_appr !!}</td>
                                <td style="text-align: center">
                                    @if($lembur->status_appr_1==1)
                                        <span class="fa fa-check-circle"> Disetujui</span>
                                    @elseif($lembur->status_appr_1==2)
                                        <span class="fa fa-window-close"> Ditolak</span>
                                    @else
                                        <span class="fa fa-edit"> Pending</span>
                                    @endif
                                </td>
                                <td>{!! $lembur->nama_appr2 !!}</td>
                                <td style="text-align: center">
                                    @if($lembur->status_appr_2==1)
                                        <span class="fa fa-check-circle"> Disetujui</span>
                                    @elseif($lembur->status_appr_2==2)
                                        <span class="fa fa-window-close"> Ditolak</span>
                                    @else
                                        <span class="fa fa-edit"> Pending</span>
                                    @endif
                                </td>
                                <td style="text-align: center">
                                    <a href="{!! route('fe.lihat_lembur',$lembur->t_form_exit_id) !!}" title='Lihat' data-toggle='tooltip'><span class='fa fa-search'></span></a>
                                    @if($lembur->status_appr_1==3)
                                        <a href="{!! route('fe.hapus_lembur',$lembur->t_form_exit_id) !!}" title='Hapus' data-toggle='tooltip'><span class='fa fa-trash'></span></a>
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
 </div>   
 </div>   
 <!-- /.content-wrapper -->
@endsection

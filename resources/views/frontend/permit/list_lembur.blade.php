@extends('layouts.app_fe')

@section('content')
	<div class="row">
	<div class="col-xl-3 col-lg-4 col-md-12 theiaStickySidebar">
		<?= view('layouts.app_side'); ?>
	</div>
	<div class="col-xl-9 col-lg-8 col-md-12">

<!-- Content Wrapper. Contains page content -->
<div class="content">
    @include('flash-message')
    <!-- Content Header (Page header) -->
    <div class="card shadow-sm ctm-border-radius">
        <div class="card-body align-center">
            <h4 class="card-title float-left mb-0 mt-2">Perintah Lembur</h4>
            <ul class="nav nav-tabs float-right border-0 tab-list-emp">

                <li class="nav-item pl-3">
                    <a href="{!! route('fe.tambah_lembur') !!}" title='Tambah' class="btn btn-theme button-1 text-white ctm-border-radius p-2 add-person ctm-btn-padding">Tambah Perintah Lembur</a>
                </li>
            </ul>
        </div>
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="card">

        <!-- /.card-header -->
        <div class="card-body">
            <form id="cari_absen" class="form-horizontal" method="get" action="{!!route('fe.list_lembur')!!}">
                <input type="hidden" name="_token" value="BU37mt9onXbQGdPbalUa4Cr97nDgjboBBu7BSYp7">
                <div class="row">

                    <div class="col-lg-6">
                        <div class="form-group">
                            <label>Tanggal Awal</label>
                            <div class="input-group date" id="tgl_awal" data-target-input="nearest">
                                <input type="date" class="form-control" id="tgl_awal" name="tgl_awal" value="<?= $request->get('tgl_awal') ? $request->get('tgl_awal') : date('Y-m-d'); ?>">

                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="form-group">
                            <label>Tanggal Akhir</label>
                            <div class="input-group date" id="tgl_akhir" data-target-input="nearest">
                                <input type="date" class="form-control" id="tgl_akhir" name="tgl_akhir" value="<?= $request->get('tgl_akhir') ? $request->get('tgl_akhir') : date('Y-m-d'); ?>">

                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-12">
                        <a href="{!!route('fe.list_lembur')!!}" class="btn btn-theme button-1 text-white ctm-border-radius p-2 add-person ctm-btn-padding"><span class="fa fa-sync"></span> Reset</a>
                        <button type="submit" name="Cari" class="btn btn-theme button-1 text-white ctm-border-radius p-2 add-person ctm-btn-padding" value="Cari"><span class="fa fa-search"></span> Cari</button>


                    </div>
                </div>
            </form>
            <table id="example1" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Lembur</th>
                        <th>Tgl </th>
                        <th>Total Jam</th>
                       
                        <th>Approve 1</th>
                        <th>Status Approve</th>
                        <th>Approve 2</th>
                        <th>Status Approve</th>
                        <th>Approve HC</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 0 ?>
                    @php($total_tolak = 0)
                    @php($total_disetujui = 0)
                    @php($total_pending = 0)
                    @if(!empty($lembur))
                    @foreach($lembur as $lembur)
                    <?php $no++ ?>
                    <tr>
                        <td>{!! $no !!}</td>
                        <td>{!! $lembur->tipe_lembur !!}</td>
                        <td>{!! $help->tgl_indo(($lembur->tgl_awal)) !!}</td>
                        <td style="text-align: center;">{!! $lembur->lama !!}</td>
                        <td>{!! $lembur->nama_appr !!}</td>
                        <td style="text-align: center">
                            @if(!$lembur->nama_appr)
                            -
                            @elseif($lembur->status_appr_1==1)
                            <span class="fa fa-check-circle"> Disetujui</span>
                            @elseif($lembur->status_appr_1==2)
                            <span class="fa fa-window-close"> Ditolak</span>
                            	@php($total_tolak += 1)
                            @else
                            <span class="fa fa-edit"> Pending</span>
                            @endif
                        </td>
                        <td>{!! $lembur->nama_appr2 !!}</td>
                        <td style="text-align: center">
                            @if($lembur->status_appr_2==1)
                            <span class="fa fa-check-circle"> Disetujui</span>
                            	@php($total_disetujui += 1)
                            @elseif($lembur->status_appr_2==2)
                            <span class="fa fa-window-close"> Ditolak</span>
                            	@php($total_tolak += 1)
                            @else
                            <span class="fa fa-edit"> Pending</span>
                            	@if($lembur->status_appr_1!=2)
                            		@php($total_pending += 1)
                            	@endif
                            @endif
                        </td>
                        <td style="text-align: center">
                            @if($lembur->status_appr_2==1)
                                @if($lembur->status_appr_hr==2)
                                <span class="fa fa-window-close"> Ditolak</span><br>
                                @else
                                <span class="fa fa-check-circle"> Disetujui</span>
                                @endif
                            @endif
                        </td>
                        <td style="text-align: center">
                            <a href="{!! route('fe.lihat_lembur',$lembur->t_form_exit_id) !!}" class="btn btn-theme button-1 text-white ctm-border-radius p-2 add-person ctm-btn-padding" title='Lihat' data-toggle='tooltip'><span class='fa fa-search'></span></a>
                            @if($lembur->status_appr_1==3)
                            <a href="{!! route('fe.hapus_lembur',$lembur->t_form_exit_id) !!}" class="btn btn-theme button-1 text-white ctm-border-radius p-2 add-person ctm-btn-padding" title='Hapus' data-toggle='tooltip'><span class='fa fa-trash'></span></a>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                    @endif
            </table>
        </div>
        <div class="row ">
            	<div class="col-md-6" style="padding: 50px">
            	Rekap : 
            		<table style="width: 100%" class="table table-striped">
            			<tbody><tr>
            				<th>Total Pengajuan</th>
            				<td>{!! $no !!}</td>
            			</tr><tr>
            				<th>Total Disetujui</th>
            				<td><?=($total_disetujui)?></td>
            			</tr><tr>
            			
            				<th>Total Ditolak</th>
            				<td><?=($total_tolak)?></td>
            			</tr><tr>
            				<th>Total Pending</th>
            				<td><?=($total_pending)?></td>
            			</tr>
            			
            		</tbody></table>
            	</div>
            </div>
        <!-- /.card-body -->
    </div>
    <!-- /.card -->
    <!-- /.card -->
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

@endsection
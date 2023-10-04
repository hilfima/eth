@extends('layouts.app_fe')

@section('content')
    <!-- Content Wrapper. Contains page content -->
<div class="row">
			<div class="col-xl-3 col-lg-4 col-md-12 theiaStickySidebar">
						<?= view('layouts.app_side');?>
					</div>
<div class="col-xl-9 col-lg-8 col-md-12">
    <div class="content-wrapper">
    @include('flash-message')
        <!-- Content Header (Page header) -->
        <div class="card shadow-sm ctm-border-radius">
<div class="card-body align-center">
<h4 class="card-title float-left mb-0 mt-2">Penjadwalan Shift Kerja</h4>

</div>
</div>

        <!-- /.content-header -->

        <!-- Main content -->
        <div class="card">
            <div class="card-header">
                <!--<h3 class="card-title">DataTable with default features</h3>-->
                
                <a href="{!! route('fe.tambah_shift') !!}" title='Tambah' data-toggle='tooltip'><span class='fa fa-plus'></span> Shift Kerja Satuan</a>
               <!-- <a href="{!! route('fe.tambah_shift_excel') !!}" title='Tambah Excel' data-toggle='tooltip'><span class='fa fa-plus'></span> Excel</a>-->
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <table id="example1" class="table table-striped custom-table mb-0">
                    <thead>
                    <tr>
                        <th>No.</th>
                        <th>Nama</th>
                        <th>Tanggal </th>
                        <th>Jam Masuk</th>
                        <th>Jam Keluar</th>
                        <th>Keterangan</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $no=0 ?>
                    @if(!empty($shift))
                        @foreach($shift as $shift)
                            <?php $no++ ?>
                            <tr>
                                <td>{!! $no !!}</td>
                                <td>{!! $shift->nama !!}</td>
                                <td>{!! date('d-m-Y',strtotime($shift->tanggal)) !!}</td>
                                
                                <td>{!! $shift->jam_masuk !!}</td>
                                <td>{!! $shift->jam_keluar !!}</td>
                                <td>{!! $shift->keterangan !!}</td>
                                
                                <td style="text-align: center">
                                    <a href="{!! route('fe.edit_shift',$shift->absen_shift_id) !!}" title='Ubah' data-toggle='tooltip'><span class='fa fa-edit'></span></a>
                                    <a href="{!! route('fe.hapus_shift',$shift->absen_shift_id) !!}" title='Hapus' data-toggle='tooltip'><span class='fa fa-trash'></span></a>
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

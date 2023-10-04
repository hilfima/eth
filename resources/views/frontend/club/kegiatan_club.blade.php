@extends('layouts.app_fe')

@section('content')
<div class="row">
    <div class="col-md-3">
    	
   <?= view('frontend.club.sidebar',compact('id')); ?>
    </div>
    <!-- Content Wrapper. Contains page content -->
   <div class="col-md-9">
        <div class="card shadow-sm ctm-border-radius">
<div class="card-body align-center">
<h4 class="card-title float-left mb-0 mt-2">Kegiatan Club</h4>

</div>
</div>
        <!-- /.content-header -->

        <!-- Main content -->
        <div class="card">
            <div class="card-header">
            @if($meclub[0]->role_manager_club==2)
                <!--<h3 class="card-title">DataTable with default features</h3>-->
                <a href="{!! route('fe.tambah_kegiatan_club',$id) !!}" title='Tambah' data-toggle='tooltip'><span class='fa fa-plus'></span> Kegiatan Club </a>
                @endif
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <table id="example1" class="table table-striped custom-table mb-0">
                    <thead>
                    <tr>
                        <th>No.</th>
                        <th>Waktu </th>
                        <th>Nama Kegiatan Club </th>
                        <th>Deksripsi </th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $no=0 ?>
                    @if(!empty($club))
                        @foreach($club as $club)
                            <?php $no++ ?>
                            <tr>
                                <td>{!! $no !!}</td>
                                <td>{!! $help->tgl_indo($club->tgl) !!}<br>
                                	{!! date('H:i',strtotime($club->jam_awal)) !!} s/d {!! date('H:i',strtotime($club->jam_akhir)) !!} 
                                </td>
                                <td>{!! $club->nama_kegiatan_club !!}</td>
                                <td>{!! $club->deskripsi !!}</td>
                               
                                <td style="text-align: center">
                                   
                                    <a href="{!! route('fe.foto_kegiatan_club',[$club->club_id,$club->club_kegiatan_id]) !!}" title='foto' data-toggle='tooltip'><span class='fa fa-picture-o'></span></a>
                                    @if($meclub[0]->role_manager_club==2)
                                    <a href="{!! route('fe.hapus_kegiatan_club',[$club->club_id,$club->club_kegiatan_id]) !!}" title='Hapus' data-toggle='tooltip'><span class='fa fa-trash'></span></a>
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

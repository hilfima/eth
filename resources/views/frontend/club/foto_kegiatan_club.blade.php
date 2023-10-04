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
<h4 class="card-title float-left mb-0 mt-2">Foto Kegiatan Club</h4>

</div>
</div>
        <!-- /.content-header -->

        <!-- Main content -->
        <div class="card">
            <div class="card-header">
                <!--<h3 class="card-title">DataTable with default features</h3>-->
                <a href="{!! route('fe.tambah_foto_kegiatan_club',[$id,$id_kegiatan]) !!}" title='Tambah' data-toggle='tooltip'><span class='fa fa-plus'></span> Foto Kegiatan Club </a>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <table id="example1" class="table table-striped custom-table mb-0">
                    <thead>
                    <tr>
                        <th>No.</th>
                        <th> Foto Kegiatan Club </th>
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
                              
                                <td><img src="{!! url('dist/img/file/'.$club->foto) !!}" width="100px"></td>
                               
                                <td style="text-align: center">
                                   
                                    <a href="{!! route('fe.hapus_foto_club',[$club->club_id,$club->club_kegiatan_id,$club->club_foto_id]) !!}" title='Hapus' data-toggle='tooltip'><span class='fa fa-trash'></span></a>
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

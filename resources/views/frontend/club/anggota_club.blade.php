@extends('layouts.app_fe')

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
    @include('flash-message')
        <!-- Content Header (Page header) -->
    <div class="row">
    <div class="col-md-3">
    	
   <?= view('frontend.club.sidebar',compact('id')); ?>
    </div>
    
        <!-- Content Header (Page header) -->
        <div class="col-md-9">
        <div class="card shadow-sm ctm-border-radius">
<div class="card-body align-center">
<h4 class="card-title float-left mb-0 mt-2">Anggota Club</h4>

</div>
</div>
       
        <!-- /.content-header -->

        <!-- Main content -->
        <div class="card">
                 @if($meclub[0]->role_manager_club==2)
            <div class="card-header">
                <!--<h3 class="card-title">DataTable with default features</h3>-->
                <a href="{!! route('fe.tambah_anggota_club',$id) !!}" title='Tambah' data-toggle='tooltip'><span class='fa fa-plus'></span> Anggota Club </a>
            </div>
                 @endif
            <!-- /.card-header -->
            <div class="card-body">
                <table id="example1" class="table table-striped custom-table mb-0">
                    <thead>
                    <tr>
                        <th>No.</th>
                        <th>Nama Anggota Club </th>
                        <th>Role </th>
                 @if($meclub[0]->role_manager_club==2)
                        <th>Action</th>
                 @endif
                    </tr>
                    </thead>
                    <tbody>
                    <?php $no=0 ?>
                    @if(!empty($club))
                        <?php foreach($club as $club){?>
                            <?php $no++ ?>
                            <tr>
                                <td>{!! $no !!}</td>
                                <td>{!! $club->nama !!}</td>
                                <td>{!! $club->role_manager_club==1?'Anggota':'Admin' !!}</td>
                               
                                <td style="text-align: center">
                                   @if($meclub[0]->role_manager_club==2)
                                    <a href="{!! route('fe.hapus_anggota_club',[$club->club_id,$club->club_karyawan_id]) !!}" title='Hapus' data-toggle='tooltip'><span class='fa fa-trash'></span></a>
                                    @endif
                                </td>
                            </tr>
                        <?php }?>
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

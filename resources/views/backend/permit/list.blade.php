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
                        <h1 class="m-0 text-dark">Pengajuan {!!ucwords($type)!!}</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{!! route('admin') !!}">Admin</a></li>
                            <li class="breadcrumb-item active">List Permit {!!ucwords($type)!!}</li>
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
				<?php if($type=='ijin'){?>
                	<a href="{!! route('be.pengajuan',$type) !!}" title='Tambah' data-toggle='tooltip'>
                		<span class='fa fa-plus'></span> Buat Ajuan {!!ucwords($type)!!}
                	</a>
                <?php }?>
            </div> 
            <!-- /.card-header -->
            <div class="card-body">
                <table id="example1" class="table table-striped custom-table mb-0">
                    <thead>
                    <tr>
                        <th>No.</th>
                        <th>Nama</th>
                        <th>Cuti</th>
                        <th>Tgl Pengajuan</th>
                        <th>Tanggal</th>
                        <th>Jam</th>
                        <?php if($type!='Direksi'){?>
                        <th>Approve</th>
                        <?php }?>
                        <th>Status Approve</th>
                        <?php 
                        	if(ucwords($type)=='Lembur'){
								echo '<th>Approve 2 </th>';
								echo '<th>Status Approve 2 </th>';
							}
                        ?>
                        <th>File</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $no=0 ?>
                    @if(!empty($data))
                        @foreach($data as $data)
                            <?php $no++ ?>
                            <tr>
                                <td>{!! $no !!}</td>
                                <td>{!! $data->nama!!}</td>
                                <td>{!! $data->nama_ijin !!}</td>
                                <td>{!! $help->tgl_indo($data->create_date) !!}</td>
                                <td>{!! $help->tgl_indo($data->tgl_awal) !!} {!! $data->tgl_awal!=$data->tgl_akhir?'S/d '.$help->tgl_indo($data->tgl_akhir):''; !!}</td>
                                <td>{!! $data->jam_awal !!} - {!! $data->jam_akhir !!}</td>
                                <?php if($type!='Direksi'){?>
                                <td>{!! $data->nama_appr !!}</td>
                               <?php }?>
                                <td style="text-align: center">
                                    
	                                    @if($data->status_appr_1==1)
	                                        <span class="fa fa-check-circle"> Disetujui</span>
	                                        @elseif($data->status_appr_1==2)
	                                            <span class="fa fa-window-close"> Ditolak</span>
	                                    @elseif($data->status_appr_1==3)
	                                        <span class="fa fa-edit"> Pending</span>
	                                    @endif
                                
                                </td>
                                 <?php 
                        	if(ucwords($type)=='Lembur'){ ?>
								<td>{!! $data->nama_appr2 !!}</td>
                               
                                <td style="text-align: center">
                                    @if($data->status_appr_2==1)
                                        <span class="fa fa-check-circle"> Disetujui</span>
                                        @elseif($data->status_appr_2==2)
                                            <span class="fa fa-window-close"> Ditolak</span>
                                    @elseif($data->status_appr_2==3)
                                        <span class="fa fa-edit"> Pending</span>
                                    @endif
                                </td>
							<?php }?>
                                
                                
                                 @if(!empty($data->foto))
								 <td style="text-align: center">
									 <a href="<?=url('/dist/img/file/');?>{!! $data->foto !!}" target="_blank" title="Download"><span class="fa fa-download"></span></a></td>
                                @else
                                    <td></td>
                                @endif
                                <td style="text-align: center">
                                    <a href="{!! route('be.lihat',[$data->t_form_exit_id,'type='.$type]) !!}" title='Lihat' data-toggle='tooltip'><span class='fa fa-search'></span></a>
                                    <a href="{!! route('be.edit_ajuan_direksi',[$data->t_form_exit_id,'type='.$type]) !!}" title='Lihat' data-toggle='tooltip'><span class='fa fa-edit'></span></a>
                                @if(Auth::user()->role==3 or Auth::user()->role==-1 or Auth::user()->role==5  ) 
                                    <a href="{!! route('be.delete_pengajuan',[$data->t_form_exit_id,'type='.$type]) !!}" title='Lihat' data-toggle='tooltip'><span class='fa fa-trash'></span></a>
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

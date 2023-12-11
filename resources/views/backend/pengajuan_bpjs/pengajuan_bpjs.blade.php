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
                        <h1 class="m-0 text-dark">Pengajuan BPJS</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{!! route('admin') !!}">Admin</a></li>
                            <li class="breadcrumb-item active">Pengajuan BPJS</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header --> 

        <!-- Main content -->
        <div class="card">
            <div class="row">
					<div class="col-lg-5">
						<div class="form-group">
							<label>Periode Absen</label>
							<select class="form-control select2" name="periode_gajian" style="width: 100%;" required onchange="list_entitas(this)">
								<option value="">Pilih Periode</option>
								<?php
								foreach($periode AS $periode){
								if($periode->periode_absen_id==$periode_absen){
								echo '<option selected="selected" value="'.$periode->periode_absen_id.'">'.ucfirst($periode->tipe_periode).' | '.$periode->bulan.' - '.$periode->tahun.' - '.$periode->tipe.' - '.$periode->tgl_awal.' - '.$periode->tgl_akhir.'</option>';
								}
								else{
								echo '<option value="'.$periode->periode_absen_id.'">'.ucfirst($periode->tipe_periode).' | '.$periode->bulan.' - '.$periode->tahun.' - '.$periode->tipe.' - '.$periode->tgl_awal.' - '.$periode->tgl_akhir.'</option>';
								}
								}
								?>
							</select>
						</div>
					</div>
            <!-- /.card-header -->
            <div class="card-body"> 
                 <table id="example1" class="table table-striped custom-table mb-0">
                    <thead>
                    <tr>
                        <th>No.</th>
                        <th>Nama</th>
                        <th>Nama Pengaju </th>
                        <th>Hubungan </th>
                        <th>Alamat </th>
                        <th>NIK </th>
                        <th>Tanggal Lahir </th>
                        <th>File kk</th>
                        <th>File ktp</th>
                        <th>File bpjs karyawan</th>
                        <th>File bpjs induk</th>
                        <th>Status</th>
                        <th>Action</th>
                        
                    </tr>
                    </thead>
                    <tbody>
                    <?php $no=0 ?>
                    @if(!empty($bpjs))
                        @foreach($bpjs as $bpjs)
                            <?php $no++ ?>
                            <tr>
                                <td>{!! $no !!}</td>
                                <td>{!! $bpjs->nama !!}</td>
                                
                                <td>{!! $bpjs->hubungan !!}</td>
                                <td>{!! $bpjs->hubungan !!}</td>
                                <td>{!! $bpjs->alamat !!}</td>
                                <td>{!! $bpjs->nik !!}</td>
                                <td>{!! $bpjs->tanggal_lahir !!}</td>
                                @if(!empty($bpjs->file_kk))
                                    <td style="text-align: center"><a href="{!! asset('dist/img/cover/'.$bpjs->file_kk) !!}" target="_blank" title="Download"><span class="fa fa-download"></span></a></td>
                                @else
                                    <td></td>
                                @endif
                                @if(!empty($bpjs->file_ktp))
                                    <td style="text-align: center"><a href="{!! asset('dist/img/cover/'.$bpjs->file_ktp) !!}" target="_blank" title="Download"><span class="fa fa-download"></span></a></td>
                                @else
                                    <td></td>
                                @endif
                                @if(!empty($bpjs->file_bpjs_karyawan))
                                    <td style="text-align: center"><a href="{!! asset('dist/img/cover/'.$bpjs->file_bpjs_karyawan) !!}" target="_blank" title="Download"><span class="fa fa-download"></span></a></td>
                                @else
                                    <td></td>
                                @endif
                                @if(!empty($bpjs->file_bpjs_induk))
                                    <td style="text-align: center"><a href="{!! asset('dist/img/cover/'.$bpjs->file_bpjs_induk) !!}" target="_blank" title="Download"><span class="fa fa-download"></span></a></td>
                                @else
                                    <td></td>
                                @endif
                                <td>
                                	<?php  if($bpjs->status==1){
                                		echo 'Selesai';
                                	}else{
                                		echo 'Pending';

									}?>
                                </td>
                                    <td>
                                    <?php if($bpjs->status==3){?>
                                    	<a href="<?=route('be.selesai_pengajuan_bpjs',$bpjs->t_cover_bpjs_id)?>" class="btn btn-primary">Selesai</a>
                                    <?php }?>
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

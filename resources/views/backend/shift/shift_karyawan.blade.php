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
                        <h1 class="m-0 text-dark">Shift Kerja</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{!! route('admin') !!}">Admin</a></li>
                            <li class="breadcrumb-item active">Shift Kerja</li>
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
                
                <a href="{!! route('be.tambah_shift') !!}" title='Tambah' data-toggle='tooltip'><span class='fa fa-plus'></span> Shift Kerja Satuan</a>
                <a href="{!! route('be.tambah_shift_excel') !!}" title='Tambah Excel' data-toggle='tooltip'><span class='fa fa-plus'></span> Excel</a>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <form action="{{route('be.shift_karyawan')}}" method="get">
                <div class="row">
                <div class="col-lg-4">
                            <div class="form-group">
                                <label>Entitas</label>
                                   <select class="form-control select2" name="entitas" style="width: 100%;" >
    									<option value="">Pilih Entitas</option>
    									<?php
    									foreach($entitas AS $entitas){
    									$selected = '';
    									if($entitas->m_lokasi_id==$request->entitas)
    										$selected = 'selected';
    										echo '<option value="'.$entitas->m_lokasi_id.'" '.$selected.'>'.$entitas->nama.'</option>';
    									
    									}
    									?>
    								</select>
                            </div>
                        </div>
                <div class="col-lg-4">
                            <div class="form-group">
                                <label>Tanggal Awal</label>
                                <div class="input-group date" id="tgl_awal" data-target-input="nearest">
                                    <input type="date" class="form-control " id="tgl_awal" name="tgl_awal" value="{!! $tgl_awal!='1970-01-01'?$tgl_awal:date('Y-m-d') !!}"  />
                                    
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label>Tanggal Akhir</label>
                                <div class="input-group date" id="tgl_akhir" data-target-input="nearest">
                                    <input type="date" class="form-control " id="tgl_akhir" name="tgl_akhir" value="{!! $tgl_akhir !!}" data-target="#tgl_akhir" />
                                   
                                </div>
                            </div>
                        </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        </form>
                        <br>
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
    <!-- /.content-wrapper -->
@endsection

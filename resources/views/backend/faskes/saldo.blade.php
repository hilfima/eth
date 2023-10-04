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
                        <h1 class="m-0 text-dark">Saldo Faskes</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{!! route('admin') !!}">Admin</a></li>
                            <li class="breadcrumb-item active">Saldo Faskes</li>
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
                <a href="{!! route('be.tambah_saldo_faskes') !!}" title='Tambah' data-toggle='tooltip'><span class='fa fa-plus'></span> Saldo Faskes </a>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
            <form id="cari_absen" class="form-horizontal" method="get" action="{!!route('be.saldo_faskes')!!}">
                
            <div class="form-group">
							<label>Entitas</label>
							<select class="form-control select2" name="entitas" style="width: 100%;" >
								<option value="">Pilih Entitas</option>
								<?php
								foreach($entitas AS $entitas){
										$selected = '';
									if($entitas->m_lokasi_id== $request->get('entitas')){
										$selected = 'selected';
										
									}
									
										echo '<option value="'.$entitas->m_lokasi_id.'" '.$selected.'>'.$entitas->nama.'</option>';
								}
								?>
							</select>
						</div>
						<div class="form-group">
                    <div class="col-md-12">
                        <a href="{!!route('be.saldo_faskes')!!}" class="btn btn-primary"><span class="fa fa-sync"></span> Reset</a>
                        <button type="submit" name="Cari" class="btn btn-primary" value="Cari"><span class="fa fa-search"></span> Cari</button>


                    </div>
                </div>
                </form>
                <table id="example1" class="table table-striped custom-table mb-0">
                    <thead>
                    <tr>
                        <th>No.</th>
                        <th>Nama </th>
                        <th>Saldo</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $no=0 ?>
                    @if(!empty($fasilitas))
                        @foreach($fasilitas as $fasilitas)
                            <?php $no++ ?>
                            <tr>
                                <td>{!! $no !!}</td>
                                <td>{!! $fasilitas->nama !!}</td>
                                <td>{!! $help->rupiah2($help->lap_faskes($fasilitas->p_karyawan_id)['nominal']) !!}</td>
                                
                                
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

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
                
            <form action="<?=route('be.laporan_faskes');?>" method="get" enctype="multipart/form-data">
            	<div class="form-group">
							<label>Karyawan</label>
							<select class="form-control select2" name="karyawan" style="width: 100%;" required>
								<option value="">Pilih Karyawan</option>
								<?php
								foreach($karyawan AS $karyawan){
										$selected = '';
									if($karyawan->p_karyawan_id==$id){
										$selected = 'selected';
										
									}
									
										echo '<option value="'.$karyawan->p_karyawan_id.'" '.$selected.'>'.$karyawan->nama.'</option>';
								}
								?>
							</select>
						</div>
						<button type="submit" class="btn btn-primary">Cari</button>
						
						</form>
            </div>
            <div class="card-body">
            
						
                <table id="example1" class="table table-striped custom-table mb-0">
                    <thead>
                    <tr>
                        <th>No.</th>
                        <th>Tanggal</th>
                        <th>Keterangan</th>
                        <th>Foto</th>
                        <th>Status Pengajuan</th>
                        <th>Debit</th>
                        <th>Kredit</th>
                        <th>Nominal</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php 
                    if($faskes)
                    echo $faskes['kontent'];
                    ?>
                   </tbody>
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

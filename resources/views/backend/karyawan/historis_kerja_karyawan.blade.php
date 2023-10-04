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
                        <h1 class="m-0 text-dark">Pengajuan Faskes</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{!! route('admin') !!}">Admin</a></li>
                            <li class="breadcrumb-item active">Pengajuan Faskes</li>
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
                <a href="{!! route('be.historis_kerja_karyawan') !!}" title='Tambah' data-toggle='tooltip'><span class='fa fa-plus'></span> Pengajuan Faskes </a>
            </div>
            <div class="card-body">
            	<form id="cari_absen" class="form-horizontal" method="get" action="{!!route('be.historis_kerja_karyawan')!!}">
               @csrf
                <div class="row">
                    <div class="col-lg-4">
					<div class="form-group">
							<label>Karyawan</label>
							<select class="form-control select2" name="karyawan" style="width: 100%;" >
								<option value="">Pilih Karyawan</option>
								<?php
								foreach($karyawan AS $karyawan){
										$selected = '';
									if($karyawan->p_karyawan_id== $request->get('karyawan')){
										$selected = 'selected';
										
									}
									echo '<option value="'.$karyawan->p_karyawan_id.'" '.$selected.'>'.$karyawan->nama_lengkap.'</option>';
								}
								?>
							</select>
						</div>
					</div>
                    
                </div>
				<div class="form-group">
                    <div class="col-md-12">
                        <a href="{!!route('be.historis_kerja_karyawan')!!}" class="btn btn-primary"><span class="fa fa-sync"></span> Reset</a>
                        <button type="submit" name="Cari" class="btn btn-primary" value="Cari"><span class="fa fa-search"></span> Cari</button>


                    </div>
                </div>
            </form>
            <?php if($request->karyawan){?>
                <table id="example1" class="table table-striped custom-table mb-0">
                    <thead>
                    <tr>
                        <th>No.</th>
                        <th>Tanggal</th>
                        <th>Awal</th>
                        <th>Perpindahan</th>
                       
                    </tr>
                    </thead>
                    <tbody>
                   <?php $no=0;$nominal=0; 
                    $array = array("m_jabatan"=>"m_jabatan_id"
				        	,"m_departemen"=>"m_departemen_id"
				        	,"m_lokasi"=>"m_lokasi_id"
				        	
				        	,"m_bank"=>"m_bank_id"
				        	,"m_kantor"=>"m_kantor_id"
				        	,"m_divisi"=>"m_divisi_id"
				        	,"-1"=>"m_directorat_id"
				        	,"-1"=>"bpjs_kantor"
				        	,"-1"=>"tgl_bpjs_kantor"
				        	,"-1"=>"norek"
				        	,"-1"=>"periode_gajian"
				        	,"-1"=>"nik"
				        	,"-1"=>"kota"
				        	,"-1"=>"is_shift"
				        	,"-1"=>"pajak_onoff");
                   ?>
                    @if(!empty($historis))
                        @foreach($historis as $data)
                            <?php $no++;?>
                            <tr>
                                <td>{!! $no !!}</td>
                                <td>{!! $data->create_date !!}</td>
                                <td >
                               		<?php 
                               		foreach($array as $key => $value){
                               			echo ucwords(str_replace("m_","",$key));
                               			echo ':';
                               			$row = "dari_".$value;
                               			$data_value[$value] = $data->$row;
                               			echo $data_value[$value];
                               			echo '<br>';
                               		}
                               		?>
                               	</td>
                                
                                <td >
                               		<?php 
                               		foreach($array as $key => $value){
                               			echo ucwords(str_replace("m_","",$key));
                               			echo ':';
                               			$row = "ke_".$value;
                               			if($data_value[$value] != $data->$row)
                               				echo '<span style="font-weight:800">';
                               			echo  $data->$row;
                               			if($data_value[$value] != $data->$row)
                               				echo '</span>';
                               			echo '<br>';
                               		}
                               		?>
                               	</td>
                                
                                
                            </tr>
                        @endforeach
                    @endif
                </table>
                
            </div>
            <?php }?>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
        <!-- /.card -->
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
@endsection

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
                        <h1 class="m-0 text-dark">Edit Gaji</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{!! route('admin') !!}">Admin</a></li>
                            <li class="breadcrumb-item active">Gaji Pokok</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <div class="card">
            
            <form class="form-horizontal" method="POST" action="{!! route('be.simpan_gapok') !!}" enctype="multipart/form-data">
            
            <div class="card-header">
                <h3 class="card-title">Tambah</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <!-- form start -->
                    {{ csrf_field() }}
                    
                    <!-- ./row -->
                    <div class="row">
                        <div class="col-12 col-sm-12">
                            
                        <div class="form-group">
                                <label>Nama</label>
                                 <?php 
                            
                            	
									$sqlkaryawans="SELECT a.p_karyawan_id,a.nik,a.nama as nama_lengkap
FROM p_karyawan a
                    					WHERE 1=1 and a.active=1 order by a.nama";
        								$karyawans=DB::connection()->select($sqlkaryawans);?>
								 <select class="form-control select2" name="karyawan" style="width: 100%;" placeholder="Pilih Karyawan" required>
                                    <option value="" >Pilih Karyawan</option>
                                    <?php
                                    foreach($karyawans AS $karyawans){
                                        echo '<option value="'.$karyawans->p_karyawan_id.'">'.$karyawans->nama_lengkap.'</option>';
                                    }
                                    ?>
                                </select>
                               
                           
                               
                            
                        </div>
                        </div>
                             
                             <div class="col-sm-6">
                            <div class="form-group">
                                <label>Tanggal Awal Kontrak Gaji*</label>
                                <input type="date" class="form-control" id="tgl_pengajuan" name="tgl_awal" value="{!!date('Y-m-d')!!}">
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label>Tanggal Akhir Kontrak Gaji*</label>
                                <input type="date" class="form-control" id="tgl_pengajuan" name="tgl_akhir" value="{!!date('Y-m-d')!!}">
                            </div>
                        </div> <div class="col-sm-2">
                            <div class="form-group">
                                <label>Status</label>
                                <select name="active" class="form-control">
                                	<option value="1">Aktif</option>
                                	<option value="0">Tidak Aktif</option>
                                </select>
                            </div>
                        </div> 
                        <div class="col-sm-6">
                        	<div class="form-group">
                                <label>Gaji Pokok</label>
                                <div class="input-group date" id="tgl_posting" data-target-input="nearest">
                                    <input type="text" class="form-control" id="gapok" name="gapok"  value="Rp "    onkeypress="handleNumber(event, 'Rp {15,3}')"   />
                                    
                                </div>
                            </div> 
                        </div><div class="col-sm-6">
                        	 
                        </div> 
                        
                        <div class="col-sm-6">
                        	<div class="form-group">
                                <label>Tunjangan Grade</label>
                                <div class="input-group date" id="tgl_posting" data-target-input="nearest">
                                    <input type="text" class="form-control" id="tunjangan_grade" name="tunjangan_grade" 
                                     value="Rp "    onkeypress="handleNumber(event, 'Rp {15,3}')"   />
                                    
                                </div>
                            </div> 
                        </div><div class="col-sm-6">
                        	
                        </div> 
                         
                        
                        <div class="col-sm-6">
                        	<div class="form-group">
                                <label>Tunjangan Kost</label>
                                <div class="input-group date" id="tgl_posting" data-target-input="nearest">
                                    <input type="text" class="form-control" id="tunjangan_kost" name="tunjangan_kost"  value="Rp "    onkeypress="handleNumber(event, 'Rp {15,3}')"   />
                                    
                                </div>
                            </div> 
                        </div><div class="col-sm-6">
                        	<div class="form-group">
                                <label>Sewa Kost</label>
                                <div class="input-group date" id="tgl_posting" data-target-input="nearest">
                                    <input type="text" class="form-control" id="sewa_kost	" name="sewa_kost"  value="Rp "    onkeypress="handleNumber(event, 'RP {15,3}')"   />
                                    
                                </div>
                            </div> 
                        </div> 
                        
                        <div class="col-sm-6">
                        	<div class="form-group">
                                <label>Tunjangan BPJS Kesehatan</label>
                                <div class="input-group date" id="tgl_posting" data-target-input="nearest">
                                    <input type="text" class="form-control" id="tunjangan_bpjskes" name="tunjangan_bpjskes"  value=" %"    onkeypress="handleNumber(event, '{3} %')"   />
                                    
                                </div>
                            </div>
                        </div><div class="col-sm-6">
                        	<div class="form-group">
                                <label>Iuran BPJS Kesehatan</label>
                                <div class="input-group date" id="tgl_posting" data-target-input="nearest">
                                    <input type="text" class="form-control" id="iuran_bpjskes" name="iuran_bpjskes"  value=" %"    onkeypress="handleNumber(event, '{3} %')"   />
                                    
                                </div>
                            </div>
                        </div> 
                        
                        
                        <div class="col-sm-6">
                        	<div class="form-group">
                                <label>Tunjangan BPJS Ketenagakerjaan</label>
                                <div class="input-group date" id="tgl_posting" data-target-input="nearest">
                                    <input type="text" class="form-control" id="tunjangan_bpjsket" name="tunjangan_bpjsket"  value=" %"    onkeypress="handleNumber(event, '{3} %')"   />
                                    
                                </div>
                            </div>
                        </div><div class="col-sm-6">
                        	<div class="form-group">
                                <label>Iuran BPJS Ketenagakerjaan</label>
                                <div class="input-group date" id="tgl_posting" data-target-input="nearest">
                                    <input type="text" class="form-control" id="iuran_bpjsket" name="iuran_bpjsket"  value=" %"    onkeypress="handleNumber(event, '{3} %')"   />
                                    
                                </div>
                            </div>
                        </div> 
                        
                        <div class="col-sm-6">
                        	
                        </div><div class="col-sm-6">
                        	<div class="form-group">
                                <label>Pajak</label>
                                <div class="input-group date" id="tgl_posting" data-target-input="nearest">
                                    <input type="text" class="form-control" id="pajak" name="pajak"  value=" %"    onkeypress="handleNumber(event, '{3} %')"   />
                                    
                                </div>
                            </div>
                        </div> 
                        
                        
                        <div class="col-sm-6">
                        	
                        </div><div class="col-sm-6">
                        	<div class="form-group">
                                <label>Kooperasi KBB</label>
                                <div class="input-group date" id="tgl_posting" data-target-input="nearest">
                                    <input type="text" class="form-control" id="koperasi_kbb" name="koperasi_kbb"  value=" %"    onkeypress="handleNumber(event, '{3} %')"   />
                                    
                                </div>
                            </div>
                        </div> 
                         
                        
                        <div class="col-sm-6">
                        	
                        </div><div class="col-sm-6">
                        	<div class="form-group">
                                <label>Kooperasi ASA</label>
                                <div class="input-group date" id="tgl_posting" data-target-input="nearest">
                                    <input type="text" class="form-control" id="koperasi_asa" name="koperasi_asa"  value=" %"    onkeypress="handleNumber(event, '{3} %')"   />
                                    
                                </div>
                            </div>
                        </div> 
                        
                        
                        
                           
                    </div>
                  </div>
                      
                    <!-- /.box-body -->
                    <div class="card-footer">
                        <a href="{!! route('be.karyawan') !!}" class="btn btn-default pull-left"><span class="fa fa-times"></span> Kembali</a>
                        <button type="submit" class="btn btn-info pull-right"><span class="fa fa-edit"></span> Ubah</button>
                    </div>
                    <!-- /.box-footer -->
                </form>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
@endsection

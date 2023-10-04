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
                        <h1 class="m-0 text-dark">Koreksi</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{!! route('admin') !!}">Admin</a></li>
                            <li class="breadcrumb-item active">Koreksi</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <div class="card">
            
            <form class="form-horizontal" method="POST" action="{!! route('be.'.$type,$id) !!}" enctype="multipart/form-data">
            
            <div class="card-header">
                <h3 class="card-title mb-0	">Koreksi</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <!-- form start -->
                    {{ csrf_field() }}
                    
                    <!-- ./row -->
                    <div class="row">
                     
                            <div class="col-md-12">
                            <div class="form-group">
                                <label>Nama Karyawan</label>
                               <select name="karyawan[]"style="width: 100%;" required <?= $type=='update_Koreksi'?'disabled class="form-control " ':'multiple; class="form-control select2" '?> multiple="">
                                    <option value="">Pilih Karyawan</option> 
                                    <?php
                                    foreach($list_karyawan	 AS $karyawan){
                                           $selected = '';
                                        if($karyawan->p_karyawan_id==$data['karyawan'])
                                           $selected = 'Selected';
                                        
                                           echo '<option  value="'.$karyawan->p_karyawan_id.'-'.$karyawan->m_lokasi_id.'" '.$selected.'>'.$karyawan->nama.'</option>';
                                        
                                    }
                                    ?>
                                </select>
                            </div>
                        </div><div class="col-md-12">
                            <div class="form-group">
                                <label>Sumber Dana</label>
                                <select class="form-control select2" name="sumber_data" style="width: 100%;" required>
                                    <option value="">Pilih Entitas Sumber Data</option>
                                    <?php
                                    foreach($entitas	 AS $entitas){
                                           $selected = '';
                                        if($entitas->m_lokasi_id==$data['m_lokasi_sumber_data_id'])
                                           $selected = 'Selected';
                                        
                                           echo '<option  value="'.$entitas->m_lokasi_id.'" '.$selected.'>'.$entitas->nama.'</option>';
                                        
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-6">
                        	
                            <div class="form-group">
                                <label>Tipe Koreksi</label>
                                <select class="form-control select2" name="type" style="width: 100%;" required>
                                    <option value="">Pilih Tipe</option>
                                    <option value="5" <?=$data['type']==5?'selected':'';?>>Koreksi Min</option>
                                    <option value="3" <?=$data['type']==3?'selected':'';?>>Koreksi Plus</option>
                                    
                                </select>
                                
                            </div>
                            </div>
                            
                       <div class="col-lg-6">
                            <div class="form-group">
                                <label>Nominal</label>
                                <input type="text" class="form-control" id="nama" name="nominal" placeholder="Nominal" value="<?=$data['nominal'];?>" onkeypress="handleNumber(event, 'Rp {15,3}')">
                                <input  type="hidden" name="id_prl" value="<?=$data['prl_generate_id'];?>"/>
                            </div>
                       
                        
                        
                        
                           
                    </div> <div class="col-lg-12">
                            <div class="form-group">
                                <label>Keterangan</label>
                                <textarea type="text" class="form-control "  id="" name="keterangan" placeholder="Keterangan" value="" ><?=$data['keterangan'];?></textarea>
                              
                            </div>
                       
                        
                        
                        
                           
                    </div>
                    </div>
                 
                        <a href="{!! route('be.Koreksi') !!}" class="btn btn-default pull-left"><span class="fa fa-times"></span> Kembali</a>
                        <button type="submit" class="btn btn-info pull-right"><span class="fa fa-edit"></span> Simpan</button>
                  <br>
                  <br>
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
q
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
                        <h1 class="m-0 text-dark"><?=ucwords($title);?></h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <div class="card">
            
            <form class="form-horizontal" method="POST" action="{!! route('be.'.$type,$id) !!}" enctype="multipart/form-data">
            
            <div class="card-header">
                <h3 class="card-title mb-0"><?=$page;?></h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <!-- form start -->
                    {{ csrf_field() }}
                    
                    <!-- ./row -->
                    <div class="row">
                     
                            <div class="col-md-12">
                            <div class="form-group">
                                <label>Nama Menu</label>
                                <input type="text" class="form-control" id="nama" name="nama_menu" placeholder="Nama Menu" value="<?=$data['nama_menu'];?>">
                            </div>
                        </div><div class="col-md-12">
                            <div class="form-group">
                                <label>Icon</label>
                                <input type="text" class="form-control" id="nama" name="icon" placeholder="icon" value="<?=$data['icon'];?>">
                            </div>
                        </div><div class="col-md-12">
                            <div class="form-group">
                                <label>Link</label>
                                <input type="text" class="form-control" id="nama" name="link" placeholder="Link" value="<?=$data['link'];?>">
                            </div>
                        </div><div class="col-md-12">
                            <div class="form-group">
                                <label>Parent</label>
                                <select  class="form-control select2" id="is_beban_karyawan" name="parent" placeholder="Presentase Beban" value="">
                                
                                <option value="0" <?php if($data['parent']) echo 'selected'?>>Tidak Ada Parent</option>
                                <?php 
                                //print_r($menu);
                                foreach($menu as $menu){?>	
                                <option value="<?=$menu->m_menu_id;?>" <?php if($menu->m_menu_id==$data['parent']) echo 'selected'?>><?=$menu->nama_menu;?>(<?=$menu->nama_parent;?>)</option>
								<?php }?>
                                </select>
                            </div>
                        </div><div class="col-md-12">
                            <div class="form-group">
                                <label>Link Sub</label>
                                <input type="text" class="form-control" id="nama" name="link_sub" placeholder="Link Sub" value="<?=$data['link_sub'];?>">
                            </div>
                        </div><div class="col-md-12">
                            <div class="form-group">
                                <label>Urutan</label>
                                <input type="text" class="form-control" id="nama" name="urutan" placeholder=Urutan" value="<?=$data['urutan'];?>">
                            </div>
                        </div><div class="col-md-12">
                            <div class="form-group">
                                <label>Type Frontend/Backend</label>
                                <input type="text" class="form-control" id="nama" name="type" placeholder="Type" value="<?=$data['type'];?>">
                            </div>
                        </div><div class="col-md-12">
                       
                        
                        
                        <button type="submit" class="btn btn-primary  pull-left"><span class="fa fa-edit"></span> Simpan</button>
                          <a href="{!! route('be.'.str_replace(' ','_',$title)) !!}" class="btn btn-default  pull-right"><span class="fa fa-times"></span> Kembali</a>
                           
                        </div>
                    </div>
                  </div>
                      
                    <!-- /.box-body -->
                  
                    <!-- /.box-footer -->
                </form>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
@endsection

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
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{!! route('admin') !!}">Admin</a></li>
                            <li class="breadcrumb-item active"><?=ucwords($title);?></li>
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
                <h3 class="card-title"><?=$page;?></h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <!-- form start -->
                    {{ csrf_field() }}
                    
                    <!-- ./row -->
                    <div class="row">
                     <div class="col-md-12">
                            <div class="form-group">
                                <label>Status</label>
                                <select type="text" class="form-control" id="m_status_id" name="m_status_id" placeholder="Kode" >
                                <option value="">-Pilih Nama Status-</option>
                                <?php $sql="SELECT * FROM m_status where m_status.active=1 "; 
		$row=DB::connection()->select($sql);
		foreach($row as $base){?>
                                <option value="<?=$base->m_status_id;?>" <?=$base->m_status_id==$data['m_status_id']?'selected':'';?>><?=$base->nama;?></option>
                                <?php }?>
                                </select>
                            </div>
                        </div><div class="col-md-12">
                            <div class="form-group">
                                <label>Katerangan</label>
                               <select type="text" class="form-control" id="m_pajak_ptkp_id" name="m_pajak_ptkp_id" placeholder="Kode">
                                <option value="">-Pilih Keterangan-</option>
                                <?php $sql="SELECT * FROM m_pajak_ptkp where active=1 "; 
		$row=DB::connection()->select($sql);
		foreach($row as $base){?>
                                <option value="<?=$base->m_pajak_ptkp_id;?>" <?=$base->m_pajak_ptkp_id==$data['m_pajak_ptkp_id']?'selected':'';?>><?=$base->keterangan;?></option>
                                <?php }?>
                                </select>
                            </div>
                        </div>
                       
                        
                        
                        
                           
                    </div>
                  </div>
                      
                    <!-- /.box-body -->
                    <div class="card-footer">
                        <a href="{!! route('be.'.str_replace(' ','_',$title)) !!}" class="btn btn-default pull-left"><span class="fa fa-times"></span> Kembali</a>
                        <button type="submit" class="btn btn-info pull-right"><span class="fa fa-edit"></span> Simpan</button>
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

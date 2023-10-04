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
                        <h1 class="m-0 text-dark">Gaji Pokok</h1>
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
            
            <form class="form-horizontal" method="POST" action="{!! route('be.simpan_excel_gapok',$type) !!}" enctype="multipart/form-data">
            
            <div class="card-header">
               
                <a href="{!! route('be.excel_exist_data',$type) !!}" title='Tambah' data-toggle='tooltip'><span class='fa fa-plus'></span>Download Template Existing Data</a>
                <a href="{!! route('be.excel_empty_data',$type) !!}" title='Tambah' data-toggle='tooltip'><span class='fa fa-plus'></span>Download Template Empty Data</a>
              
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <!-- form start -->
                    {{ csrf_field() }}
                    
                    <!-- ./row -->
                    <div class="row">
                        <div class="col-12 col-sm-12">
                            
                       <div class="col-sm-6">
                        	<div class="form-group">
                                <label>Data Excel</label>
                                <div class="input-group date" id="tgl_posting" data-target-input="nearest">
                                    <input type="file" class="form-control" id="gapok" name="excel"     />
                                    
                                </div>
                            </div> 
                        </div>
                        
                        
                           
                    </div>
                  </div>
                 
                     
                        <button type="submit" class="btn btn-info pull-right"> Import</button>
                        <br>
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

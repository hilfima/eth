@extends('layouts.app_fe')

@section('content')
<link rel='stylesheet' href='https://weareoutman.github.io/clockpicker/dist/jquery-clockpicker.min.css'>
<link rel="stylesheet" href="./style.css">

    <!-- Content Wrapper. Contains page content -->
    <div class="row">
			<div class="col-xl-3 col-lg-4 col-md-12 theiaStickySidebar">
						<?= view('layouts.app_side');?>
					</div>
<div class="col-xl-9 col-lg-8 col-md-12">
<div class="content-wrapper">
    @include('flash-message')
        <!-- Content Header (Page header) -->
         <div class="card shadow-sm ctm-border-radius">
<div class="card-body align-center">
<h4 class="card-title float-left mb-0 mt-2">Shift Kerja</h4>

</div>
</div>

        <!-- Main content -->
        <div class="card">
        	<div class="card-body">
        		<form class="form-horizontal" method="get" action="{!! route('fe.excel_shift') !!}" enctype="multipart/form-data">
        		
                            
                             <button type="submit" name="Cari" class="btn btn-primary" value="Empty"><span class="fa fa-file-excel"></span> Template Data</button>
                    
                       </form>
                    </div>
                            
        	</div>
       
        
        <div class="card">
            
            <form class="form-horizontal" method="POST" action="{!! route('fe.simpan_excel_shift') !!}" enctype="multipart/form-data">
             
           
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

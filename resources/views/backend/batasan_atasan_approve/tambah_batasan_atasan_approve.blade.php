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
                        <h1 class="m-0 text-dark">Batasan Atasan Approve</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{!! route('admin') !!}">Admin</a></li>
                            <li class="breadcrumb-item active">Batasan Atasan Approve</li>
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
            </div>
            <!-- /.card-header -->
            <div class="card-body">
          
                <!-- form start -->
                <form class="form-horizontal" method="POST" action="{!! route('be.simpan_batasan_atasan_approve') !!}" enctype="multipart/form-data">
                    {{ csrf_field() }}
                            <!-- text input -->
                            <div class="form-group">
                                <label>Nama Batas</label>
                                <input type="text" class="form-control" placeholder="Nama Batas ..." id="judul" name="data[nama_batasan]" required>
                            </div>
                       
                            <div class="form-group">
                                <label>Tipe</label>
                                <select class="form-control" placeholder="Nama Batas ..." id="batas_tipe" name="data[batas_tipe]"  required>
                                    <option value="">Pilih</option>
                                    <option value="+">Plus</option>
                                    <option value="-">Minus</option>
                                    <option value="0">Tidak ada</option>
                                </select>
                            </div>
                            <div id="single_plus_min">
                                <div class="form-group">
                                    <label>Jumlah Hari</label>
                                    <input type="number" class="form-control" placeholder="Jumlah Hari ..." id="judul" name="data[batas_hari]" >
                            
                                </div>
                            </div>
                       
                        
                    </div>
                    <!-- /.box-body -->
                    <div class="card-footer">
                        <a href="{!! route('be.batasan_atasan_approve') !!}" class="btn btn-default pull-left"><span class="fa fa-times"></span> Kembali</a>
                        <button type="submit" class="btn btn-info pull-right"><span class="fa fa-check"></span> Simpan</button>
                    </div>
                    <!-- /.box-footer -->
                </form>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.content -->
    </div>
    <script>
        function change_batas_tipe(){
            var val = $('#batas_tipe').val();
            //alert(val);
            if(val=='+-'){
                $('#double_plus_min').show();
                $('#single_plus_min').hide();
            }else{
                $('#double_plus_min').hide();
                $('#single_plus_min').show();
            }
        }
    </script>
    <!-- /.content-wrapper -->
@endsection

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
                        <h1 class="m-0 text-dark">RMIB</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{!! route('admin') !!}">Admin</a></li>
                            <li class="breadcrumb-item active">RMIB</li>
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
                <form class="form-horizontal" method="POST" action="{!! route('be.update_rmib',$rmib[0]->m_rmib_id) !!}" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="row">
                        <div class="col-sm-5">
                            <!-- text input -->
                            <div class="form-group">
                                <label>Nama</label>
                                <input type="text" class="form-control" placeholder="Nama..." id="nama" name="nama" required value="{!! $rmib[0]->nama !!}">
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <!-- text input -->
                            <div class="form-group">
                                <label>Grup</label>
                                <select class="form-control select2" name="grup" style="width: 100%;" >
                                    <option value="A" <?php if($rmib[0]->grup=='A'){ echo 'selected="selected" ';} ?> >A</option>
                                    <option value="B" <?php if($rmib[0]->grup=='B'){ echo 'selected="selected" ';} ?>>B</option>
                                    <option value="C" <?php if($rmib[0]->grup=='C'){ echo 'selected="selected" ';} ?>>C</option>
                                    <option value="D" <?php if($rmib[0]->grup=='D'){ echo 'selected="selected" ';} ?>>D</option>
                                    <option value="E" <?php if($rmib[0]->grup=='E'){ echo 'selected="selected" ';} ?>>E</option>
                                    <option value="F" <?php if($rmib[0]->grup=='F'){ echo 'selected="selected" ';} ?>>F</option>
                                    <option value="G" <?php if($rmib[0]->grup=='G'){ echo 'selected="selected" ';} ?>>G</option>
                                    <option value="H" <?php if($rmib[0]->grup=='H'){ echo 'selected="selected" ';} ?>>H</option>
                                    <option value="I" <?php if($rmib[0]->grup=='I'){ echo 'selected="selected" ';} ?>>I</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <!-- text input -->
                            <div class="form-group">
                                <label>Urutan</label>
                                <input type="number" class="form-control" placeholder="Urutan..." id="urutan" name="urutan" required value="{!! $rmib[0]->urutan !!}">
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <!-- text input -->
                            <div class="form-group">
                                <label>Jenis Kelamin</label>
                                <select class="form-control select2" name="m_jk_id" style="width: 100%;" required>
                                    <?php
                                    foreach($jk AS $jk){
                                        if($jk->m_jenis_kelamin_id==$rmib[0]->m_jk_id){
                                            echo '<option selected="selected" value="'.$jk->m_jenis_kelamin_id.'">'.$jk->nama.'</option>';
                                        }
                                        else{
                                            echo '<option value="'.$jk->m_jenis_kelamin_id.'">'.$jk->nama.'</option>';
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <a href="{!! route('be.rmib') !!}" class="btn btn-default pull-left"><span class="fa fa-times"></span> Kembali</a>
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

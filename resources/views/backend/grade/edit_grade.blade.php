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
                        <h1 class="m-0 text-dark">Grade</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{!! route('admin') !!}">Admin</a></li>
                            <li class="breadcrumb-item active">Grade</li>
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
                <form class="form-horizontal" method="POST" action="{!! route('be.update_grade',$grade[0]->m_grade_cluster_id) !!}" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label>Kode</label>
                                <input type="text" class="form-control" placeholder="Kode Grade..." id="kode" name="kode" value="{!! $grade[0]->kode !!}" required>
                            </div>
                            <div class="form-group">
                                <label>Nama</label>
                                <input type="text" class="form-control" placeholder="Nama Grade..." id="nama" name="nama" value="{!! $grade[0]->nama !!}" required>
                            </div>
                            <div class="form-group">
                                <label>Grade2</label>
                                <input type="text" class="form-control" placeholder="Nama Grade2..." id="grade2" name="grade2"  value="{!! $grade[0]->grade2 !!}" required>
                            </div>
                            <div class="form-group">
                                <label>Group</label>
                                <select class="form-control select2" name="grup" style="width: 100%" required>
                                    <option selected="selected">Pilih Group</option>
                                    <option <?php if($grade[0]->group=='I'){ echo 'selected="selected" ';} ?> value="I">I</option>
                                    <option <?php if($grade[0]->group=='II'){ echo 'selected="selected" ';} ?> value="II">II</option>
                                    <option <?php if($grade[0]->group=='III'){ echo 'selected="selected" ';} ?> value="III">III</option>
                                    <option <?php if($grade[0]->group=='IV'){ echo 'selected="selected" ';} ?> value="IV">IV</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Cluster</label>
                                <select class="form-control select2" name="gradecluster" style="width: 100%;" required>
                                    <option selected="selected">Pilih Cluster</option>
                                    <?php
                                    foreach($gradecluster AS $gradecluster){
                                        if($gradecluster->m_grade_cluster_id==$grade[0]->m_grade_cluster_id){
                                            echo '<option selected="selected" value="'.$gradecluster->m_grade_cluster_id.'">'.$gradecluster->nama.'</option>';
                                        }
                                        else{
                                            echo '<option value="'.$gradecluster->m_grade_cluster_id.'">'.$gradecluster->nama.'</option>';
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <a href="{!! route('be.grade') !!}" class="btn btn-default pull-left"><span class="fa fa-times"></span> Kembali</a>
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

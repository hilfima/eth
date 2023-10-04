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
                        <h1 class="m-0 text-dark">Saldo Faskes</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{!! route('admin') !!}">Admin</a></li>
                            <li class="breadcrumb-item active">Saldo Faskes</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <div class="card">
           <form class="form-horizontal" method="POST" action="{!! route('be.simpan_faskes') !!}" enctype="multipart/form-data">
             {{ csrf_field() }}
            <!-- /.card-header -->
            <div class="card-body">
               <div class="form-group">
                      <label>Karyawan</label>
                      <select type="text" class="form-control select2" id="nama" name="karyawan" required>
                      	<option value="">- Pilih Karyawan -</option>
                      	<?php foreach($karyawan as $k){?>
                      	<option value="<?=$k->p_karyawan_id;?>"><?=$k->nama;?></option>
						<?php }?>
                      </select>
                </div>
                <div class="form-group">
                       <label>Saldo</label>
                       <input type="text" class="form-control" placeholder="Saldo ..." id="nama" name="saldo" value="Rp "    onkeypress="handleNumber(event, 'Rp {15,3}')"  required>
                </div>
            <button type="submit" class="btn btn-info pull-right"><span class="fa fa-edit"></span> Ubah</button>
            </div>
            </form>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
        <!-- /.card -->
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
@endsection

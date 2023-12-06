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
                        <h1 class="m-0 text-dark">Periode  {!!ucwords($tipe)!!}</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{!! route('admin') !!}">Admin</a></li>
                            <li class="breadcrumb-item active">Periode  {!!ucwords($tipe)!!}</li>
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
                <form class="form-horizontal" method="POST" action="{!! route('be.simpan_periode') !!}" enctype="multipart/form-data" id="formAdd">
                    {{ csrf_field() }}
                    <div class="row">
                        <div class="col-sm-2">
                            <div class="form-group">
                                <label>Type Gajian</label>
                                <select class="form-control select2" name="type" id="type_gajian" onchange="change_type_gajian(this)" style="width: 100%;" required>
                                    <option value="">Pilih</option>
                                    <option value="1">Bulanan</option>
                                    <option value="0">Pekanan</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <!-- text input -->
                            <div class="form-group">
                                <label>Tahun</label>
                                <input type="hidden" class="form-control" name="tipe_periode" value="{!!$tipe!!}" required>
                                <input type="text" class="form-control" placeholder="Tahun ..." id="tahun" name="tahun" required>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group">
                                <label>Bulan</label>
                                <select class="form-control select2" name="periode" style="width: 100%;" required>
                                    <option value="">Pilih</option>
                                    <option value="1">Januari</option>
                                    <option value="2">Februari</option>
                                    <option value="3">Maret</option>
                                    <option value="4">April</option>
                                    <option value="5">Mei</option>
                                    <option value="6">Juni</option>
                                    <option value="7">Juli</option>
                                    <option value="8">Agustus</option>
                                    <option value="9">September</option>
                                    <option value="10">Oktober</option>
                                    <option value="11">November</option>
                                    <option value="12">Desember</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label>Tanggal Awal</label>
                                <div class="input-group date" >
                                    <input type="date" class="form-control " id="tgl_awal" name="tgl_awal" data-target="#tgl_awal" />
                                    
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label>Tanggal Akhir</label>
                                <div class="input-group date" >
                                    <input type="date" class="form-control " id="tgl_akhir" name="tgl_akhir" data-target="#tgl_akhir" />
                                    
                                </div>
                            </div>
                        </div>
                         <div class="col-sm-2">
                            <!-- text input -->
                            <div class="form-group">
                                <label>Pekanan Ke</label>
                                <input type="number" class="form-control" placeholder="Pekanan Ke ..." id="tahun" name="pekanan_ke" required>
                            </div>
                        </div> <div class="col-sm-2">
                            <!-- text input -->
                            <div class="form-group">
                                <label>Periode Aktif</label>
                                <select type="number" class="form-control" placeholder="Periode Aktif ..." id="tahun" name="periode_aktif" required>
                                <option value="">- Periode Aktif -</option>
                                <option value="1">Ya</option>
                                <option value="0">No</option>
                                </select>
                            </div>
                        </div>
                        
                        
                        <div class="col-sm-6">
                       
                            <!-- text input -->
                            <div class="form-group" >
                                <label>Pilih Entitas</label>
                                <select type="number" class="form-control select2" multiple="" placeholder="Periode Aktif ..." id="pilih_entitas" name="list_entitas[]" required="">
                                
                                <?php

                                 foreach($entitas as $entitas){?>
                                <option value="<?=$entitas->m_lokasi_id;?>"><?=$entitas->nama;?></option>
                                <?php }?>
                                </select>
                            </div>
                        
                        </div>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <a href="{!! route('be.periode',$tipe) !!}" class="btn btn-default pull-left"><span class="fa fa-times"></span> Kembali</a>
                        <button type="button" class="btn btn-info pull-right" onclick="submit_form();"><span class="fa fa-check"></span> Simpan</button>
                    </div>
                    <!-- /.box-footer -->
                </form>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
    <script>
    change_type_gajian();
    change_entitas();
    	function change_entitas(){
    		if($('#change_entitas_id').val()==2){
    			$('#kontent-entitas').show();	
    		}else{
    			$('#kontent-entitas').hide();	
    			$('#pilih_entitas').val().triger('change');
    			
    		}
    	}
        function change_type_gajian(){
           
            $.ajax({
				type: 'get',
				data: {'periode_gajian': $('#type_gajian').val(),type:'<?=$tipe?>'},
				url: '<?=route('be.periode_absen_min');?>',
				dataType: 'json',
				success: function(data){
				    
					$('#tgl_awal').attr('min',data.min);
					$('#tgl_akhir').attr('min',data.min);
				},
				error: function (error) {
				    console.log('error; ' + eval(error));
				    //alert(2);
				}
			});
        }
        function submit_form(){
             $.ajax({
				type: 'get',
				data: {'tgl_awal': $('#tgl_awal').val(),tgl_akhir:$('#tgl_akhir').val(),type:'<?=$tipe?>'},
				url: '<?=route('be.periode_absen_cek_duplicate');?>',
				dataType: 'json',
				success: function(data){
				    if(data.count==0)
				     document.getElementById('formAdd').submit();
				},
				error: function (error) {
				    console.log('error; ' + eval(error));
				    //alert(2);
				}
			});
        }
    </script>
@endsection

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
                        <h1 class="m-0 text-dark">Tarik Absen</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{!! route('admin') !!}">Admin</a></li>
                            <li class="breadcrumb-item active">Tarik Absen</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <div class="card">
        <div class="card-header">
                <div class="form-group">
                                <label>Tanggal</label>
                                <div class="input-group date" id="tgl_awal" data-target-input="nearest">
                                    <input type="date" class="form-control " id="tgl" name="tgl" value="<?=date('Y-m-d');?>">
                                    
                                </div>
                            </div>
                            
                            <button type="button" onclick="tarik_absen();load_table();" name="Cari" class="btn btn-primary" value="Cari"><span class="fa fa-search"></span> Sync</button>
                            <button type="button" onclick="load_table();" name="Cari" class="btn btn-primary" value="Cari"><span class="fa fa-search"></span> Load Histori</button>
                            
                           </div>
        <div class="card-body" id="massage">
                
        </div>
        </div>
        <div class="card">
            <div class="card-body" id="historis_absen">
                <table class="table">
                    <tr>
                        <th>No</th>
                        <th>Waktu</th>
                        <th>Mesin</th>
                        <th>Method</th>
                        <th>Log</th>
                    </tr>
                    <?php 
                    $no = 0;
                    foreach($historis_log as $log){
                    $no++;
                    ?>
                        <tr>
                            <td><?=$no;?></td>
                            <td><?=$log->create_date;?></td>
                            <td><?=$log->nama;?></td>
                            <td><?=$log->input_by;?></td>
                            <td><pre><?php $log_array = (json_decode(str_replace('\"','"',str_replace("\'","'",($log->log))),true));
                            if(isset($log_array[0])){
                            for($i=0;$i<count($log_array);$i++){
                                echo $log_array[$i];
                                echo '<br>';
                            }
                            }
                            
                            ?></pre></td>
                        </tr>
                    <?php }?>
                </table>
            </div>
        </div>
        <script>
            function tarik_absen(){
                $.ajax({
    				type: 'get',
    				data: {'tgl': $('#tgl').val()},
    				url: 'https://absen.mafazaperforma.com/api/read.php',
    				dataType: 'html',
    				success: function(data){
    					$('#massage').append(data);
    				},
    				error: function (error) {
    				    console.log('error; ' + eval(error));
    				    //alert(2);
    				}
    			});
            }
            function load_table(){
                $.ajax({
    				type: 'get',
    				data: {'tgl': $('#tgl').val()},
    				url: '{{route('be.historis_absen')}}',
    				dataType: 'html',
    				success: function(data){
    					$('#historis_absen').html(data);
    				},
    				error: function (error) {
    				    console.log('error; ' + eval(error));
    				    //alert(2);
    				}
    			});
            }
        </script>
@endsection
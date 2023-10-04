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
                        <h1 class="m-0 text-dark">Kontrak</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{!! route('admin') !!}">Admin</a></li>
                            <li class="breadcrumb-item active">Kontrak</li>
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
                <form class="form-horizontal" method="POST" action="{!! route('be.simpan_kontrak') !!}" enctype="multipart/form-data">
                    {{ csrf_field() }}
                     <div class="form-group">
                                                        <label>Karyawan</label>
                                                        <select class="form-control select2" name="karyawan" style="width: 100%;" required onchange="view_data_karyawan(this.value)">
                                                           	<option value="">- Pilih Karyawan - </option>
                                                            <?php
                                                            foreach($karyawan AS $karyawan){
                                                               
                                                                    echo '<option value="'.$karyawan->p_karyawan_id.'" '.(isset($_GET['id'])?($_GET['id']==$karyawan->p_karyawan_id?'selected':''):'').'>'.$karyawan->nama.'</option>';
                                                               
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                   
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
                                                    <script>
                                                     <?php 
                                                    if(isset($_GET['id'])){
                                                    ?>
                                                    
                                                        view_data_karyawan('<?=$_GET['id']?>');
                                                    
                                                    <?php }?>
                                                    	function view_data_karyawan(value){
                                                    		
                                                    		$.ajax({
															type: 'get',
															data: {'value': value},
															url: '<?=route('be.get_data_karyawan');?>',
															dataType: 'json',
															success: function(data){
																
																$("#lokasi").val(data.m_lokasi_id).select2();
																$("#directorat").val(data.m_directorat_id).select2();;
																$("#seksi").val(data.m_departemen_id).select2();
																$("#divisi_new").val(data.m_divisi_new_id).select2();
																$("#departemen").val(data.m_divisi_id).select2();
																$("#jabatan").val(data.m_jabatan_id).select2();
																$("#status_pekerjaan").val(data.m_status_pekerjaan_id).trigger('change');
																$("#kantor").val(data.m_kantor_id).trigger('change');
															},
															error: function (error) {
															    console.log('error; ' + eval(error));
															    //alert(2);
															}
														});
                                                    	}
                                                    </script>
                                                    <div class="form-group">
                                                        <label>Tanggal Awal Kontrak</label>
                                                        <div class="input-group " id="tgl_akhir" data-target-input="nearest">
                                                            <input type="date" class="form-control" id="tgl_akhir" name="tgl_awal" data-target="#tgl_akhir" value="" required="">
                                                            
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Tanggal Akhir Kontrak</label>
                                                        <div class="input-group " id="tgl_akhir" data-target-input="nearest">
                                                            <input type="date" class="form-control" id="tgl_akhir" name="tgl_akhir" data-target="#tgl_akhir" value="" required="">
                                                            
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>File Kontrak kerja</label>
                                                            <input type="file" class="form-control" id="tgl_akhir" name="file" data-target="#tgl_akhir" value="" >
                                                            
                                                      
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Keterangan</label>
                                                            <textarea type="file" class="form-control" id="tgl_akhir" name="keterangan" data-target="#tgl_akhir" value="" required="" placeholder="Keterangan"></textarea>
                                                    </div>
                                                     <div class="form-group">
                                                    <label>Entitas</label>
                                                    <select class="form-control select2" id="lokasi" name="lokasi" style="width: 100%;" required onchange="finddirectorat(this)">
                                                        <option value="">- Entitas -</option>
                                                        <?php
                                                        foreach ($lokasi as $lokasi) {
                                                           // if ($lokasi->m_lokasi_id == $karyawan[0]->m_lokasi_id) {
                                                             //   echo '<option selected="selected" value="' . $lokasi->m_lokasi_id . '">' . $lokasi->nama . '</option>';
                                                            //} else {
                                                                echo '<option value="' . $lokasi->m_lokasi_id . '">' . $lokasi->nama . '</option>';
                                                            //}
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label>Directorat</label>
                                                    <select class="form-control select2" name="directorat" id="directorat" style="width: 100%;" onchange="finddivisi(this.value)">
                                                        <option value="">- Directorat -</option>
                                                        <?php
                                                        foreach ($directorat as $directorat) {
            //                                                 if ($directorat->m_directorat_id == $karyawan[0]->m_directorat_id) {
            //                                                     echo '
												// 			<option selected="selected" value="' . $directorat->m_directorat_id . '">' . $directorat->nama_directorat . '</option>';
            //                                                 } 
            // //                                                 else {
                                                                echo '
															<option value="' . $directorat->m_directorat_id . '">' . $directorat->nama_directorat . '</option>';
                                                            // }
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label>Divisi</label>
                                                    <select class="form-control select2" name="divisi_new" id="divisi_new" style="width: 100%;"  onchange="finddepartement(this.value)" >
                                                        <option value="">- Divisi -</option>
                                                        <?php
                                                        foreach ($divisi_new as $divisi_new) {
            //                                                 if ($divisi_new->m_divisi_new_id == $karyawan[0]->m_divisi_new_id) {
            //                                                     echo '
												// 			<option selected="selected" value="' . $divisi_new->m_divisi_new_id . '">' . $divisi_new->nama_divisi . '</option>';
            //                                                 } 
            //                                                 else {
                                                                echo '
															<option value="' . $divisi_new->m_divisi_new_id . '">' . $divisi_new->nama_divisi . '</option>';
            //                                                 }
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label>Departemen</label>
                                                    <select class="form-control select2" name="divisi" id="departemen" style="width: 100%;" onchange="findseksi(this.value)" >
                                                        <option value="">- Departement -</option>
                                                        <?php
                                                        foreach ($divisi as $divisi) {
                                                            //if ($divisi->m_divisi_id == $karyawan[0]->m_divisi_id) {
                                                             //   echo '
															//<option selected="selected" value="' . $divisi->m_divisi_id . '">' . $divisi->nama . '</option>';
                                                            //}
        
                                                                echo '
															<option value="' . $divisi->m_divisi_id . '">' . $divisi->nama . '</option>';
                                                            // }
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label>Seksi</label>
                                                    <select class="form-control select2" name="departemen" id="seksi" style="width: 100%;"  onchange="findjabatan(this.value)">
                                                        <option value="">- Seksi -</option>
                                                        
                                                        
                                                        <?php
                                                        foreach ($departemen as $departemen) {
                                                           
                                                            
                                                                echo '
															<option value="' . $departemen->m_departemen_id . '">' . $departemen->nama . '</option>';
                                                            
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                                 <div class="form-group">
                                                    <label>Jabatan/Pangkat</label>
                                                    <select class="form-control select2" name="jabatan" id="jabatan" style="width: 100%;" required>
                                                        <option value="">- Jabatan -</option>
                                                        <?php
                                                        foreach ($jabatan as $jabatan) {
                                                          
                                                                echo '<option value="' . $jabatan->m_jabatan_id . '">' . $jabatan->nama . '</option>';
                                                          
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                                    <div class="form-group">
                                                        <label>Status Pekerjaan</label>
                                                        <select class="form-control select2" id="status_pekerjaan" name="status_pekerjaan" style="width: 100%;" required>	
                                                         <option value="">- Pilih Status Pekerjaan - </option>
                                                            <?php
                                                            foreach($stspekerjaan AS $stspekerjaan){
                                                               
                                                                    echo '<option value="'.$stspekerjaan->m_status_pekerjaan_id.'">'.$stspekerjaan->nama.'</option>';
                                                                
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Kantor</label>
                                                        <select class="form-control select2" id="kantor"  name="kantor" style="width: 100%;" required>	
                                                         <option value="">- Pilih Kantor - </option>
                                                            <?php
                                                            foreach($kantor AS $kantor){
                                                               
                                                                    echo '<option value="'.$kantor->m_office_id.'">'.$kantor->nama.'</option>';
                                                                
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <a href="{!! route('be.kontrak') !!}" class="btn btn-default pull-left"><span class="fa fa-times"></span> Kembali</a>
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
      <script>
                                                        function finddirectorat(e){
                                                            $.ajax({
                                                                type : 'get',
                                                                url : "{{ route('be.finddirectorat') }}",
                                                                data : {
                                                                    id:$(e).val(),
                                                                   
                                                                    
                                                                },
                                                                cache : false,
                                                                headers: {
                                                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                                                },
                                                                success: function(msg){
                                                                    //console.log(msg);
                                                                    $('#directorat').html(msg);
                                                                    
                                                                },
                                                                error: function(data){
                                                                    console.log('error:', data)
                                                                },
                                                            })
                                                        }
                                                        function finddivisi(e){
                                                            $.ajax({
                                                                type : 'get',
                                                                url : "{{ route('be.finddivisi') }}",
                                                                data : {
                                                                    id:e,
                                                                   
                                                                    
                                                                },
                                                                cache : false,
                                                                headers: {
                                                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                                                },
                                                                success: function(msg){
                                                                    //console.log(msg);
                                                                    $('#divisi_new').html(msg);
                                                                },
                                                                error: function(data){
                                                                    console.log('error:', data)
                                                                },
                                                            })
                                                        }
                                                        function finddepartement(e){
                                                            $.ajax({
                                                                type : 'get',
                                                                url : "{{ route('be.finddepartement') }}",
                                                                data : {
                                                                    id:e,
                                                                   
                                                                    
                                                                },
                                                                cache : false,
                                                                headers: {
                                                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                                                },
                                                                success: function(msg){
                                                                    //console.log(msg);
                                                                    $('#departemen').html(msg);
                                                                },
                                                                error: function(data){
                                                                    console.log('error:', data)
                                                                },
                                                            })
                                                        }
                                                        function findseksi(e){
                                                            $.ajax({
                                                                type : 'get',
                                                                url : "{{ route('be.findseksi') }}",
                                                                data : {
                                                                    id:e,
                                                                   
                                                                    
                                                                },
                                                                cache : false,
                                                                headers: {
                                                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                                                },
                                                                success: function(msg){
                                                                    //console.log(msg);
                                                                    $('#seksi').html(msg);
                                                                },
                                                                error: function(data){
                                                                    console.log('error:', data)
                                                                },
                                                            })
                                                        }
                                                        function findjabatan(e){
                                                            $.ajax({
                                                                type : 'get',
                                                                url : "{{ route('be.findjabatan') }}",
                                                                data : {
                                                                    id:e,
                                                                   
                                                                    
                                                                },
                                                                cache : false,
                                                                headers: {
                                                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                                                },
                                                                success: function(msg){
                                                                    //console.log(msg);
                                                                    $('#jabatan').html(msg);
                                                                },
                                                                error: function(data){
                                                                    console.log('error:', data)
                                                                },
                                                            })
                                                        }
                                                    </script>
@endsection

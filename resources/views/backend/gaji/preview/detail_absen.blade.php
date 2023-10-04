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
                        <h1 class="m-0 text-dark">Edit Data Karyawan</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{!! route('admin') !!}">Admin</a></li>
                            <li class="breadcrumb-item active">Edit Data Karyawan</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <div class="card">
           
              
             
          <?php 
            echo  view('backend.gaji.preview.filter',compact('data','entitas','user','id_prl','help','periode','periode_absen','request','list_karyawan'));
            ?>
               
                </div>
        <!-- Main content -->
        @if(!empty($list_karyawan))
        <?php 
        	
				
        	//if()
        ?>
          
           
             <div class="card">
            <!--<div class="card-header">
                
                
            </div>-->
            <!-- /.card-header -->
            <div class="card-body">
                <!-- form start -->
               <table id="example1" class="table table-striped custom-table mb-0">
				  <thead>
				    <tr>
				       <th scope="col">NO</th>
				      <th scope="col">NAMA</th>
				      <th scope="col">ABSEN</th>
				      <th scope="col">KETERANGAN</th>
				     	     
				      <?php if($data['page']=='non_ajuan' or $data['page']=='ajuan' ) echo '<th>Action</th>';?>
				    </tr>
				  </thead>
				  <tbody>
				  <?php
				  $no=0;
				  $total_nominal=0;
				   foreach($detail_absen as $list_karyawan){
				   	if($list_karyawan->keterangan){
				  $no++;
				 
				   ?>
				    <tr>
				     
				      <td><?=$no?></td>
				      <td><?=$list_karyawan->nama_lengkap;?></td>
					  <td><?=$list_karyawan->string_jenis_ijin;?></td>
					  <td><?=$list_karyawan->keterangan;?></td>
				       <?php if($data['page']=='non_ajuan' or $data['page']=='ajuan'){?>
				       <td><a href="javascript:void(0)" onclick="keterangan('')"><span class="fa fa-edit"></span></a></td><?php }?>
				    </tr>
				  <?php }?>
				  <?php }?>
				   	
				  </tbody>
				</table>
            </div>
            <!-- /.card-body -->
        </div>
       
         @endif
         </form>
<div class="modal fade" id="changeKeteranganModal" tabindex="-1" role="dialog" aria-labelledby="changeKeteranganModalLabel" aria-hidden="true">
<form id="editkar"  method="post" enctype="multipart/form-data">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Keterangan Ajuan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            
            <div class="modal-body">
            <div class="form-group">
            	<label>Entitas</label>
                <select class="form-control select2" name="lokasi" id="m_lokasi_id" style="width: 100%;" >
                	<option value="">- Entitas -</option>
                    <?php
                    	foreach ($lokasi as $lokasi) {
                        	echo '<option value="' . $lokasi->m_lokasi_id . '">' . $lokasi->nama . '</option>';
                        }
                    ?>
            	</select>
            </div>
            <div class="form-group">
            	<label>Jabatan/Pangkat</label>
                <select class="form-control select2" name="jabatan" id="m_jabatan_id" style="width: 100%;" required>
                	<option value="">- Jabatan -</option>
                    <?php
                    	foreach ($jabatan as $jabatan) {
                        	
                            	echo '<option value="' . $jabatan->m_jabatan_id . '">' . $jabatan->nama . ' - ' . $jabatan->nmpangkat . ' - ' . $jabatan->nmlokasi . '</option>';
                           
                        }
                    ?>
                    </select>
            </div>
            <div class="form-group">
               	 	<label>No Rekening</label>
                    <input type="text" class="form-control" placeholder="No Rekening..." id="norek" name="norek" value="">
                    	
            </div>
            <div class="form-group">
            	<label>Bank</label>
                <select type="text" class="form-control select2" id="m_bank_id" style="width: 100%;" name="bank" placeholder="Nama bank">
                	<option value="">- Pilih Bank - </option>
                	<?php foreach ($bank as $bank) {
                        	$selected = '';
                            
                        	echo "<option value='" . $bank->m_bank_id . "' $selected>" . $bank->nama_bank . "</option>";
                        }
                    ?>
                    </select>
            </div>
            <div class="form-group">
            	<label>Pajak</label>
                <select class="form-control select2" name="pajakonoff" id="pajak_onoff" style="width: 100%;">
                	<option value="">Pilih Pajak</option>
                    <option value="ON" >ON</option>
                    <option value="OFF">OFF</option>
                </select>
            </div>
            <div class="form-group">
            	<label>Periode Gajian</label>
                <select class="form-control select2" name="periode_gajian" id="type_gaji" style="width: 100%;" required>
                	<option value="1" >Bulanan</option>
                	<option value="0">Pekanan</option>
                </select>
            </div>
            
            
            
            <div class="form-group">
               	 	<label>Update Ke data Master Karyawan</label><br>
                    <input type="radio"  placeholder="No Rekening..." id="norek" name="update_master" value="1"> Data Nomor Rekening , Bank dan Pajak simpan kepada master karyawan karena perubahan data yang menetap, dan saya siap bertanggung jawab terhadap perubahan data ini<br>
                    <input type="radio"  placeholder="No Rekening..." id="norek" name="update_master" value="0"> Data Nomor Rekening , Bank dan Pajak hanya data periode gajiann ini saja..
                    	
            </div>
            
            <input type="hidden" name="id_generate_karyawan" id="id_generate_karyawan" value="">
            <input type="hidden" name="id_karyawan" id="id_karyawan" value="">
            <input type="hidden" name="id_prl" value="<?=$id_prl;?>">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="save_keterangan()">Save changes</button>
            </div>
        </div>
    </div>
</form>
</div>

    <script>
    	function save_keterangan(){
			
    		  $.ajax({
	            type: 'get',
	            data: $('#editkar').serialize(),
	            url: '<?= route('be.save_keterangan_edit_ajuan'); ?>',
	            dataType: 'html',
	            success: function(data) {
	               	alert("Data berhasil diubah, refesh page ketika semua data sudah berubah..");
	                $('#changeKeteranganModal').modal('toggle');
	            },
	            error: function(error) {
	               	alert("Data GAGAL diubah");
	                console.log('error; ' + eval(error));
	                //alert(2);
	            }
	        });
		}
    	function keterangan(id,id_karyawan,m_lokasi_id,nmentitas,pajak_onoff,m_jabatan_id,nmjabatan,m_bank_id,nmbank,norek,type_gaji){
    		var $newOption = $("<option selected='selected'></option>").val(m_lokasi_id).text(nmentitas);
 			$("#m_lokasi_id").append($newOption).trigger('change');
 			
    		var $newOption = $("<option selected='selected'></option>").val(pajak_onoff).text(pajak_onoff);
 			$("#pajak_onoff").append($newOption).trigger('change');
 			
 			var $newOption = $("<option selected='selected'></option>").val(m_jabatan_id).text(nmjabatan);
 			$("#m_jabatan_id").append($newOption).trigger('change');
 			
 			var $newOption = $("<option selected='selected'></option>").val(m_bank_id).text(nmbank);
 			$("#m_bank_id").append($newOption).trigger('change');
 			
    		
    		//$('#m_jabatan_id').val(m_jabatan_id);
    		///$('#m_bank_id').val(m_bank_id);
    		$('#norek').val(norek);
    		$('#type_gaji').val(type_gaji);
    		$('#id_generate_karyawan').val(id);
    		$('#id_karyawan').val(id_karyawan);
    		$('#changeKeteranganModal').modal('toggle');
    	}
    	
    </script>    	
        	
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
@endsection

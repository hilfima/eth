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
                        <h1 class="m-0 text-dark">Pengajuan Gaji Keuangan</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{!! route('admin') !!}">Admin</a></li>
                            <li class="breadcrumb-item active">Pengajuan Gaji Keuangan</li>
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
        	if($request->get('entitas') and $request->get('pajakonoff')){
				
        	//if()
        ?>
            <div class="card">
            <!-- /.card-header -->
            <div class="card-body">
				<div class="request-btn">
					<div class="dash-stats-list">
						<p>Status Approval Direktur </p>
						<?php 
						//print_r($generate);
						//echo print_r($sudahappr);
						//if()
						$pajak = strtolower($request->get('pajakonoff'));
						$var = 'appr_'.strtolower($request->get('pajakonoff')).'_direktur_status';
						$appr_date = 'appr_'.strtolower($request->get('pajakonoff')).'_direktur_date';
						$appr_keterangan = 'appr_'.strtolower($request->get('pajakonoff')).'_direktur_keterangan';
						$appr_nama = 'appr_nama_'.strtolower($request->get('pajakonoff')).'';
						if($sudahappr[0]->$var==0 or $sudahappr[0]->$var==null  ){
							echo '<h4>Belum Terdapat Approval</h4>';
							echo 'Data Ajuan Gaji Baru bisa dilihat setelah terdapat approval dari direktur';
							
							 
                                        $visible = false;	
							}else if($sudahappr[0]->$var==1){
							echo '<h4>Data Gaji Disetujui</h4>';	
							echo '<div>Oleh : '.($sudahappr[0]->$appr_nama).'</div>';	
							echo '<div>Pada Tanggal: '.$help->tgl_indo($sudahappr[0]->$appr_date).'</div>';	
							echo '<div>Keterangan; '.$sudahappr[0]->$appr_keterangan.'</div>';	
                                        
                                        $visible = true;
							}else if($sudahappr[0]->$var==2){
                                        $visible = false;	
							echo '<h4>Data Gaji Ditolak</h4>';	
							echo '<div>Oleh : '.($sudahappr[0]->$appr_nama).'</div>';	
							echo '<div>Pada Tanggal: '.$help->tgl_indo($sudahappr[0]->$appr_date).'</div>';	
							echo '<div>Keterangan; '.$sudahappr[0]->$appr_keterangan.'</div>';	
							}
							?>
						</div>
					</div>
				</div>
        </div>
            <?php if($sudahappr[0]->$var==1 or $request->get('view')=='view' or $data['page']=='non_ajuan'){?>
           
             <div class="card">
            <!--<div class="card-header">
                
                
            </div>-->
            <!-- /.card-header -->
            <div class="card-body">
                <!-- form start -->
               <table class="table table-striped">
				  <thead>
				    <tr>
				       <th scope="col">NO</th>
				      <th scope="col">NAMA</th>
				      <th scope="col">BANK</th>
				      <th scope="col">REKENING</th>
				      <th scope="col">NOMINAL</th>
				     
				      <th scope="col">KETERANGAN</th>
				      <?php if($data['page']=='non_ajuan') echo '<th>Action</th>';?>
				    </tr>
				  </thead>
				  <tbody>
				  <?php
				  $no=0;
				  $total_nominal=0;
				   $return = $help->preview_gaji($data_row, 2);
                    $total = $return['total'];
				   foreach($list_karyawan as $list_karyawan){
				  $no++;
				   $return = $help->preview_gaji($data_row, 1, $total, $data, $list_karyawan, $sudah_appr_voucher, $id_prl);
                   $total = $return['total'];
                    $nominal=0;
                   if (isset($return['field'][$list_karyawan->p_karyawan_id])) {


                            $field = $return['field'][$list_karyawan->p_karyawan_id];
                            if($list_karyawan->gajian_type==-1){
							$nominal = $field['thp_karyawan'];
							}else {
                            
                               $nominal = $field['thp_karyawan']+ $field['korekmin'] - $field['korekplus'];
                           
                            
                           
                            if (isset($data[$list_karyawan->p_karyawan_id]['Entitas_id']['Koreksi(+)'])) {
                                $nmlok = $data[$list_karyawan->p_karyawan_id]['Entitas_id']['Koreksi(+)'];
                                if ($nmlok==$request->get('entitas')) {
                                    $nominal += $field['korekplus'];
                                }
                            }
                            if (isset($data[$list_karyawan->p_karyawan_id]['Entitas_id']['Koreksi(-)'])) {
                                $nmlok = $data[$list_karyawan->p_karyawan_id]['Entitas_id']['Koreksi(-)'];
                              if ($nmlok==$request->get('entitas')) {
                                    $nominal -= $field['korekmin'];
                                } 
                            }
                            }
                            
                        }
				  $total_nominal +=$nominal;
				   ?>
				    <tr>
				     
				      <td><?=$no?></td>
				      <td><?=$list_karyawan->nama_lengkap;?></td>
					  <td><?=$list_karyawan->nama_bank;?></td>
				      <td><?=$list_karyawan->norek;?></td>
				      <td><?=  $help->rupiah2( $nominal);?></td>
				      <td id="keterangan-<?=$list_karyawan->prl_generate_karyawan_id?>"><?= $list_karyawan->keterangan_ajuan;?></td>
				       <?php if($data['page']=='non_ajuan'){?><td><a href="javascript:void(0)" onclick="keterangan('<?=$list_karyawan->prl_generate_karyawan_id;?>','<?=$list_karyawan->keterangan_ajuan?>')"><span class="fa fa-edit"></span></a></td><?php }?>
				    </tr>
				  <?php }?>
				   	 <td><b>JUMLAH</b></td>
				      <td></td>
				      <td></td>
				      <td></td>
				      <td><?=  $help->rupiah2($total_nominal);?></td>
				      <td></td>
				  </tbody>
				</table>
            </div>
            <!-- /.card-body -->
        </div>
        <?php }?>
         <?php }else{?>
         <div class="card">
            <!-- /.card-header -->
            <div class="card-body">
					SIlahkan Pilih Entitas dan Pajak terlebih dahulu
				</div>
				</div>
         <?php }?>
         @endif
         </form>
<div class="modal fade" id="changeKeteranganModal" tabindex="-1" role="dialog" aria-labelledby="changeKeteranganModalLabel" aria-hidden="true">

    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Keterangan Ajuan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <textarea type="number" id="keterangan_ajuan_now" value="" class="form-control" placeholder="Masukan Keterangan"></textarea>
                <input type="hidden" id="id_generate_karyawan" value="">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="save_keterangan()">Save changes</button>
            </div>
        </div>
    </div>
</div>

    <script>
    	function save_keterangan(){
			keterangan = $('#keterangan_ajuan_now').val();
    		id_generate_karyawan = $('#id_generate_karyawan').val();
    		  $.ajax({
	            type: 'get',
	            data: {
	                'keterangan': keterangan,
	                'id_generate_karyawan': id_generate_karyawan
	            },
	            url: '<?= route('be.save_keterangan_ajuan'); ?>',
	            dataType: 'html',
	            success: function(data) {
	                $('#keterangan-'+id_generate_karyawan).html(keterangan);
	                $('#changeKeteranganModal').modal('toggle');
	            },
	            error: function(error) {
	                console.log('error; ' + eval(error));
	                //alert(2);
	            }
	        });
		}
    	function keterangan(id,keterangan){
    			
    		$('#keterangan_ajuan_now').val(keterangan);
    		$('#id_generate_karyawan').val(id);
    		$('#changeKeteranganModal').modal('toggle');
    	}
    	
    </script>    	
        	
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
@endsection

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
                        <h1 class="m-0 text-dark">Generate,Preview, Edit, dan Approve Gaji</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{!! route('admin') !!}">Admin</a></li>
                            <li class="breadcrumb-item active">Generate,Preview, Edit, dan Approve Gaji</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div>
        <div class="card">
           
              
               <form id="cari_absen" class="form-horizontal" method="get" action="{!! route('be.previewgaji',$data['page']) !!}">
              
          <?php 
            echo  view('backend.gaji.preview.filter',compact('data','entitas','user','id_prl','help','periode','periode_absen','request','list_karyawan'));
            ?>
               
                </div>
        <!-- Main content -->
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
						//echo print_r($sudahappr);die;
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
            <?php if($sudahappr[0]->$var==1){?>
           
             <div class="card">
            <div class="card-header">
                <button type="submit" name="Cari" class="btn btn-primary" value="ExcelAjuan"><span class="fa fa-search"></span> Excel</button>
                
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <!-- form start -->
               <table class="table table-striped">
				  <thead>
				    <tr>
				      <th scope="col">REKENING</th>
				      <th scope="col">PLUS</th>
				      <th scope="col">NOMINAL</th>
				      <th scope="col">CD</th>
				      <th scope="col">NO</th>
				      <th scope="col">NAMA</th>
				      <th scope="col">KETERANGAN</th>
				      <th scope="col">BANK</th>
				    </tr>
				  </thead>
				  <tbody>
				  <?php
				  $no=0;
				  $nominal=0;
				   foreach($list_karyawan as $list_karyawan){
				  $no++;
				  $help->rupiah(isset($data[$list_karyawan->p_karyawan_id]['Lembur'])?$lembur=$data[$list_karyawan->p_karyawan_id]['Lembur']:$lembur=0);
                                  $help->rupiah(isset($data[$list_karyawan->p_karyawan_id]['Tunjangan BPJS Kesehatan'])?$tunkes=$data[$list_karyawan->p_karyawan_id]['Tunjangan BPJS Kesehatan']:$tunkes=0);
                                  $help->rupiah(isset($data[$list_karyawan->p_karyawan_id]['Tunjangan BPJS Ketenaga Kerjaan'])?$tunket=$data[$list_karyawan->p_karyawan_id]['Tunjangan BPJS Ketenaga Kerjaan']:$tunket=0);
                                  $help->rupiah(isset($data[$list_karyawan->p_karyawan_id]['Koreksi(+)'])?$korekplus=$data[$list_karyawan->p_karyawan_id]['Koreksi(+)']:$korekplus=0);
                                  $help->rupiah(isset($data[$list_karyawan->p_karyawan_id]['Tunjangan Kost'])?$tunkost=$data[$list_karyawan->p_karyawan_id]['Tunjangan Kost']	:$tunkost=0);
                                  $help->rupiah($tunjangan = $tunkost+$korekplus+$lembur );
                               
                                  $help->rupiah(isset($data[$list_karyawan->p_karyawan_id]['Potongan Telat'])?$telat= $data[$list_karyawan->p_karyawan_id]['Potongan Telat']:$telat=0);
                                 
                                  $help->rupiah(isset($data[$list_karyawan->p_karyawan_id]['Sewa Kost'])?$sewakost = $data[$list_karyawan->p_karyawan_id]['Sewa Kost']:$sewakost =0);
                                 $help->rupiah2(isset($data[$list_karyawan->p_karyawan_id]['Potongan Pulang Mendahului'])?$potpm= $data[$list_karyawan->p_karyawan_id]['Potongan Pulang Mendahului']:$potpm=0);
                                  $help->rupiah(isset($data[$list_karyawan->p_karyawan_id]['Zakat'])?$zakat = $data[$list_karyawan->p_karyawan_id]['Zakat']:$zakat =0);
                                  $help->rupiah(isset($data[$list_karyawan->p_karyawan_id]['Infaq'])?$infaq = $data[$list_karyawan->p_karyawan_id]['Infaq']:$infaq =0);
                                  $help->rupiah(isset($data[$list_karyawan->p_karyawan_id]['Koreksi(-)'])?$korekmin = $data[$list_karyawan->p_karyawan_id]['Koreksi(-)']:$korekmin =0);
                                  $help->rupiah(isset($data[$list_karyawan->p_karyawan_id]['Simpanan Wajib KKB'])?$kkp = $data[$list_karyawan->p_karyawan_id]['Simpanan Wajib KKB']:$kkp =0);
                                  $help->rupiah(isset($data[$list_karyawan->p_karyawan_id]['Potongan Koperasi Asa'])?$asa = $data[$list_karyawan->p_karyawan_id]['Potongan Koperasi Asa']:$asa =0);
                                  $help->rupiah(isset($data[$list_karyawan->p_karyawan_id]['Pajak'])?$pajak = $data[$list_karyawan->p_karyawan_id]['Pajak']:$pajak =0);
                                  isset($data[$list_karyawan->p_karyawan_id]['Potongan Fingerprint'])?$finger= $data[$list_karyawan->p_karyawan_id]['Potongan Fingerprint']:$finger=0;
                                  $help->rupiah($potongan = $telat+$finger+$sewakost+$zakat+$infaq+$korekmin+$kkp+$asa+$potpm);
				 				
				 				$uh= isset($data[$list_karyawan->p_karyawan_id]['Upah Harian'])?$data[$list_karyawan->p_karyawan_id]['Upah Harian']:0;
                               $ha= isset($data[$list_karyawan->p_karyawan_id]['Hari Absen'])?$data[$list_karyawan->p_karyawan_id]['Hari Absen']:0;
                               $gapok= $uh*$ha;
                              $nominal +=(($gapok+$tunjangan)-$potongan); ?>
				    <tr>
				     
				      <td><?=$list_karyawan->norek;?></td>
				      <td>+</td>
				      <td><?=  $help->rupiah2( (($gapok+$tunjangan)-$potongan));?></td>
				      <td>C</td>
				      <td><?=$no?></td>
				      <td><?=$list_karyawan->nama_lengkap;?></td>
				      <td></td>
				      <td><?=$list_karyawan->nama_bank;?></td>
				    </tr>
				  <?php }?>
				   	 <td><b>JUMLAH</b></td>
				      <td></td>
				      <td><?=  $help->rupiah2($nominal);?></td>
				      <td></td>
				      <td></td>
				      <td></td>
				      <td></td>
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
         </form>
        	
        	
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
@endsection

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
                        <h5 class="m-0 text-dark"> Rekap Transaksi</h5>
                        <h1 class="mb-2 text-dark"><?= 
                        	ucwords(str_replace('_',' ',$type));
                        
                        ?></h1>
                    </div><!-- /.col -->
                   
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <div class="card">
                @if($request->get('prl_generate'))
            <div class="card-header">
                <!--<h3 class="card-title">DataTable with default features</h3>-->
                <?php 
                $tambah = $request->get('prl_generate')?$request->get('prl_generate'):0;;
                
                ?>
                
                
            </div>
                @endif
            <!-- /.card-header -->
            <div class="card-body">
             <form id="cari_absen" class="form-horizontal" method="get" action="{!! route('be.laporan_gaji',$type) !!}">
            	 <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Periode Generate</label>
                                <select class="form-control select2" name="prl_generate" style="width: 100%;" required>
                                    <option value="">Pilih Periode Generate</option>
                                    <?php
                                    foreach($periode AS $periode){
                                        if($periode->prl_generate_id==$id_prl){
                                            echo '<option selected="selected" value="'.$periode->prl_generate_id.'">Periode: '.$periode->tahun_gener.' Bulan: '.$periode->bulan_gener.' | Absen:'.$periode->tgl_awal.' - '.$periode->tgl_akhir.' | Lembur:'.$periode->tgl_awal_lembur.' - '.$periode->tgl_akhir_lembur.'</option>';
                                        }
                                        else{	
                                           echo '<option  value="'.$periode->prl_generate_id.'">Periode: '.$periode->tahun_gener.' Bulan: '.$periode->bulan_gener.' | Absen:'.$periode->tgl_awal.' - '.$periode->tgl_akhir.' | Lembur:'.$periode->tgl_awal_lembur.' - '.$periode->tgl_akhir_lembur.'</option>';
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div><div class="col-lg-4">
                            <div class="form-group">
                                <label>Entitas</label>
                                <select class="form-control select2" name="entitas" style="width: 100%;" >
                                    <option value="">Pilih Entitas</option>
                                    <?php
                                    foreach($lokasi AS $lokasi){
                                    	$selected = '';
                                        if($lokasi->m_lokasi_id==$request->get('entitas')){
                                    		$selected = 'selected';
                                            
                                        }
                                       
                                           echo '<option  value="'.$lokasi->m_lokasi_id.'" '.$selected.'>'.$lokasi->nama.'</option>';
                                        
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>	
                        <div class="col-lg-2">
                            <div class="form-group">
                                <label>Pajak</label>
                                <select class="form-control select2" name="pajak" style="width: 100%;" >
                                    <option value="">Pilih Pajak</option>
                                    <option value="ON" <?=$request->get('pajak')=='ON'?'selected':'';?>>ON</option>
                                    <option value="OFF" <?=$request->get('pajak')=='OFF'?'selected':'';?>>OFF</option>
                                   
                                </select>
                            </div>
                        </div>	
                    </div>	
                     <button type="submit" name="Cari" class="btn btn-primary mb-3" value="Cari"><span class="fa fa-search"></span> Cari</button>
                     <button type="submit" name="Cari" class="btn btn-primary mb-3" value="Excel"><span class="fa fa-search"></span> Excel</button>
                     </form>
                     @if($request->get('prl_generate'))
                <table id="example1" class="table table-striped custom-table mb-0">
                    <thead>
                    <tr>
                        <th>No.</th>
                        <th>Nama</th>
                        <th>Entitas</th>
                        <th>Pajak</th>
                         <?php if($type=='bpjs'){ ?>
                        <th>BPJS Kesehatan</th>
                        <th>BPJS Ketenagakerjaan</th>
                        <th>Jumlah</th>
                         <?php }else if($type=='zakat_infaq'){ ?>
                                <th>Zakat</th>
		                        <th>Infaq</th>
		                        <th>Jumlah</th>
                                <?php }else { ?>
                                <th>Nominal</th>
                                <?php }?>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $no=0;
                     $total = 0; 
                     $nominal = 0 ;
                     //if($type=='bpjs'){echo $type;die;}
                     ?>
                    @if(!empty($list_karyawan))
                        @foreach($list_karyawan as $rekap)
                            <?php 
                     		$nominal = 0 ;
                          	$content='';
                            $total_karyawan = 0; 
                            if($jenis==2){ 
                            	for($i=0;$i<count($array);$i++){
                            		$nominal =isset($data[$rekap->p_karyawan_id][$array[$i]])?$data[$rekap->p_karyawan_id][$array[$i]]:0;
									$content .= '<td>'.$help->rupiah($nominal).'</td>'; 
									$total_karyawan+=$nominal;
                            	}
                            	$content .='<td>'.$help->rupiah($total_karyawan).'</td>';
							}else{
								$nominal = isset($data[$rekap->p_karyawan_id][$array])?$data[$rekap->p_karyawan_id][$array]:0;
								$total_karyawan+=$nominal;
								$content .='<td>'.$help->rupiah($total_karyawan).'</td>';
							}
                           	if($total_karyawan){
                            $no++;
								$total += $total_karyawan;
                             ?>
                            <tr>
                                <td>{!! $no !!}</td>
                                <td>{!! $rekap->nama_lengkap !!}</td>
                                <td>{!! $rekap->nmlokasi !!}</td>
                                <td>{!! $rekap->pajak_onoff !!}</td>
                                <?=$content?>
                                
                               
                            </tr>
                            <?php }?>
                    @endforeach
                    @endif
                </table>
                <div class="h2 pt-2"><span >Total :</span><?=$help->rupiah($total) ?></div>
                
           			@endif
            </div>
            <?php
           // print_r($rekap);
            ?>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
        <!-- /.card -->
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
@endsection

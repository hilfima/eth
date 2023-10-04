<div class="card">
           <div class="card-body">        
                	<form class="form-horizontal" method="POST" action="{!! route('fe.simpan_shift_multiple') !!}" enctype="multipart/form-data">
                         {{ csrf_field() }}
                         <input type="hidden" class="form-control " style="width: 20px" id="shifting" value="1" name="shifting" onclick="check(this)"  required=""/>
                        <?php 
	                        //$bulan = $_GET['bulan'] ;
	                        $tgl_awal = $request->get('tgl_awal');
	                        $tgl_akhir = $request->get('tgl_akhir');
	                        $date = $tgl_awal;?>
	                        <input type="hidden" name="tgl_awal" value="<?=$tgl_awal;?>">
	                        <input type="hidden" name="tgl_akhir" value="<?=$tgl_akhir;?>">
                                    
                        <div class="row">
                        
                        
                        <?php foreach($karyawan as $kar){?>
                        <input type="hidden" name="karyawan[]" value="<?=$kar->p_karyawan_id;?>"/>
                        	<div class="col-sm-12" >
                            <!-- text input -->
	                            <div class="form-group d-flex">
	                                
	                                <label style="display: flex;vertical-align: middle;align-items: center;">
	                                
	                                <input type="checkbox" class="form-control"  name="status-<?=$kar->p_karyawan_id;?>"  value="1" style="width: 15px;" onclick="karyawan_shift(this,<?=$kar->p_karyawan_id;?>)"> 
	                                <span style="margin-left: 10px" class="h6">
	                                <?=$kar->nama;?>
	                                	</span>
	                                	
	                                </label> 
	                        	</div>
	                        </div>
	                        
	                        
	                        <div id="kontent-tanggal-<?=$kar->p_karyawan_id;?>" style="display: none">
	                        
	                        <div class="row" >
	                        <?php 
	                        $date = $tgl_awal;
	                        for($i=0;$i<=$help->hitunghari($tgl_awal,$tgl_akhir);$i++){?>
	                        <div class="col-sm-1" >
	                        </div>
	                        <div class="col-sm-4" >
	                        <?= $help->tgl_indo($date);?>
	                        <div class="text-muted small"><?=$kar->nama;?></div>
	                        </div><div class="col-sm-7" >
	                        	<div class="form-group">
                                
								 <select class="form-control " id="bulan" name="shift[<?=$kar->p_karyawan_id;?>][<?=$date;?>]" style="width: 100%;"  >
                                    <option value="">Pilih Shift Tanggal <?= $help->tgl_indo($date);?></option>
                                  	<?php 
                                  	foreach($jam_shift as $jam){?> 
									  <option value="<?=$jam->m_jam_shift_id;?>"><?=$jam->nama_jam_shift;?> (<?=date('H:i',strtotime($jam->jam_masuk));?> - <?=date('H:i',strtotime($jam->jam_keluar));?>)  <?=$jam->keterangan?'-->'.$jam->keterangan:'';?></option>
                                    <?php }?>
                                    <option value="-1">Libur</option>
                                </select>
                        	        
                        </div>
	                        </div>
	                        
                        <?php 
                        $date = $help->tambah_tanggal($date,1);
                        }?>
                            </div>
                            </div>
                        <?php }?>
                        
                            </div>
                   
                        <a href="{!! route('fe.jadwal_shift') !!}" class="btn btn-default pull-left"><span class="fa fa-times"></span> Kembali</a>
                        <button type="submit" class="btn btn-info pull-right"><span class="fa fa-check"></span> Simpan</button>
                    </div>
                    <!-- /.box-footer -->
                </form>
            </div>
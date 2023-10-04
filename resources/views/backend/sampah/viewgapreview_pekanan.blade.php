@extends('layouts.appsA')

@section('content')

<style>
    html {
        box-sizing: border-box;
    }

    *,
    *:before,
    *:after {
        box-sizing: inherit;
    }

    .intro {
        max-width: 1280px;
        margin: 1em auto;
    }

    .table-scroll {
        position: relative;
        width: 100%;
        z-index: 1;
        margin: auto;
        overflow: auto;
        height: 655px;
    }

    .table-scroll table {
        width: 100%;
        min-width: 1280px;
        margin: auto;
        border-collapse: separate;
        border-spacing: 0;
    }

    .table-wrap {
        position: relative;
    }

    .table-scroll td {
        min-width: 120px
    }

    .table-scroll th,

    .table-scroll td {
        padding: 5px 10px;
        border: 1px solid #000;
        background: #fff;
        vertical-align: top;
    }

    .table-scroll thead th {
        background: #333;
        color: #fff;
        position: -webkit-sticky;
        position: sticky;
        top: 0;
    }

    .table-scroll thead tr.secondline th {
        top: 35px;
    }

    /* safari and ios need the tfoot itself to be position:sticky also */
    .table-scroll tfoot,
    .table-scroll tfoot th,
    .table-scroll tfoot td {
        position: -webkit-sticky;
        position: sticky;
        bottom: 0;
        background: #666;
        color: #fff;
        z-index: 4;
    }

    th.statictable:first-child {
        position: -webkit-sticky;
        position: sticky;
        left: 0;
        z-index: 2;
        background: #ccc;
    }

    thead th.statictable:first-child {
        background: #333;
        position: sticky;
        left: 0;
        z-index: 3;
    }

    thead th:first-child,
    tfoot th:first-child {
        z-index: 5;
    }.text-black {
        color: #012851
    }
</style>

<div class="content-wrapper">
    @include('flash-message')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Generate, Preview, Aproval, dan Ajuan Gaji</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{!! route('admin') !!}">Admin</a></li>
                        <li class="breadcrumb-item active">Generate, Preview, Aproval, dan Ajuan Gaji</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    
            <?php
            echo  view('backend.gaji.preview.filter', compact('data', 'entitas', 'user', 'id_prl', 'help', 'periode', 'periode_absen', 'request', 'list_karyawan'));
            ?>

            @if(!empty($list_karyawan))
            <div class="card-body">
 
                <div id="table-scroll" class="table-scroll">
                    <table id="main-table" class="main-table">
                        <thead>
                            <tr>



                                <th rowspan="2" class="statictable">Nama</th>
                               <!-- <th rowspan="2"  style="width: 30px;max-width: 30px;min-width: 30px">No </th>
-->
                                <th rowspan="2">NIP</th>
                                <th rowspan="2">Perusahaan</th>
                                <th rowspan="2">Pajak</th>
                                <th rowspan="2">JABATAN</th>
                                <th rowspan="2">TGL MASUK</th>
                                <th rowspan="2">MASA KERJA</th>
                                <th rowspan="2">Grade</th>

                               <!-- GAJI TOTAL PER BULAN



                                <th colspan="2">DATA ABSENSI</th>
                                <th rowspan="2">UPAH HARIAN</th>
                                <th rowspan="2">GAJI TOTAL PER BULAN </th>
                                <th colspan="3"> PENDAPATAN </th>
                                <th colspan="3"> TUNJANGAN</th>
                                <th colspan="9"> POTONGAN </th>





                                <th rowspan="2" style="font-size: 12px">TOTAL POTONGAN</th>
                                <th rowspan="2" style="font-size: 12px">TOTAL PENDAPATAN</th>
                                <th rowspan="2">THP</th>-->
                                <?php 
									 for ($x = 0; $x < count($data_head); $x++) {
									 	echo '<th colspan="'.$data_head[$x][1].'" style="text-align: center;">'.$data_head[$x][0].'</th>';
									}
									?>
                            </tr>
                            <tr class="secondline">




                                <?php 
								 for ($x = 0; $x < count($data_row); $x++) {
								 	echo '<th style="width: 40px;min-width: 40px;max-width: 40px;font-size: 9px;vertical-align: top;z-index:1;text-align: center;">'.$data_row[$x][0].'</th>';
								}
								?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 0;
                            $gaji_entitas = array();
                            
                             $return = $help->preview_gaji($data_row,2);
                             $total = $return['total'];    					
                             				
                                					
                            ?>
                            @foreach($list_karyawan as $list_karyawan)
                            <?php $no++;
                            $color = '';
                            if ($list_karyawan->pajak_onoff) {

                                 if ($sudah_appr_keuangan[$list_karyawan->m_lokasi_id][$list_karyawan->pajak_onoff])
                                    $color = 'background: green;color: white;';
                                 else if ($sudah_appr[$list_karyawan->m_lokasi_id][$list_karyawan->pajak_onoff])
                                    $color = 'background: orange;color: white;';
                               	 else  if ($sudah_appr_hr[$list_karyawan->m_lokasi_id][$list_karyawan->pajak_onoff])
                                    $color = 'background: blue;color: white;';
                            }
                            ?>
                            <tr>
                                <th style="font-size: 11px;<?= $color ?>" class="statictable">{!! $list_karyawan->nama_lengkap !!}</th>
                               <!-- <td style="width: 50px;max-width: 50px;min-width: 50px">{!! $no !!}</td>
                                --><td>{!! $list_karyawan->nik !!}</td>
                                <td>{!! $list_karyawan->nmlokasi !!} </td>
                                <td>{!! $list_karyawan->pajak_onoff !!}</td>
                                <td style="font-size: 10px">{!! $list_karyawan->nmjabatan !!}</td>
                                <td>{!! $list_karyawan->tgl_awal !!}</td>
                                <td style="font-size: 10px">{!! $list_karyawan->umur !!}</td>
                                <td>{!! $list_karyawan->grade !!}</td>

                               
                                <?php
                              

                               $return = $help->preview_gaji($data_row,1,$total,$data,$list_karyawan,$sudah_appr,$id_prl);
                               $total = $return['total'];
                                //print_r($total);die;   
                               echo $return['content'];

                               if(isset($return['field'][$list_karyawan->p_karyawan_id])){
                               	
                               $field = $return['field'][$list_karyawan->p_karyawan_id];
                              if (isset($gaji_entitas[$list_karyawan->nmlokasi])) {
                                    $gaji_entitas[$list_karyawan->nmlokasi] += ($field['gaji_total_upah_harian'] +$field['jumlah_tunjangan'] +$field['lembur'] ) - $field['jumlah_potongan']  + $field['korekmin']  - $field['korekplus'] ;
                                } 
                                else {
                                    $gaji_entitas[$list_karyawan->nmlokasi] = ($field['gaji_total_upah_harian'] + $field['jumlah_tunjangan'] + $field['lembur'] ) - $field['jumlah_potongan'] + $field['korekmin'] - $field['korekplus'];
                                };
                                if (isset($data[$list_karyawan->p_karyawan_id]['Entitas']['Koreksi(+)'])) {
                                    $nmlok = $data[$list_karyawan->p_karyawan_id]['Entitas']['Koreksi(+)'];
                                    if (isset($gaji_entitas[$nmlok])) {
                                        $gaji_entitas[$nmlok] += $field['korekplus'];
                                    } else {
                                        $gaji_entitas[$nmlok] = $field['korekplus'];
                                    }
                                }
                                if (isset($data[$list_karyawan->p_karyawan_id]['Entitas']['Koreksi(-)'])) {
                                    $nmlok = $data[$list_karyawan->p_karyawan_id]['Entitas']['Koreksi(-)'];
                                    if (isset($gaji_entitas[$nmlok])) {
                                        $gaji_entitas[$nmlok] -= $field['korekmin'];
                                    } else {
                                        $gaji_entitas[$nmlok] = -$field['korekmin'];
                                    }
                                }
                               }

					/*
                                if (isset($gaji_entitas[$list_karyawan->nmlokasi])) {
                                    $gaji_entitas[$list_karyawan->nmlokasi] += ($gapok + $tunjangan + $lembur) - $potongan + $korekmin - $korekplus;
                                } else {
                                    $gaji_entitas[$list_karyawan->nmlokasi] = ($gapok + $tunjangan + $lembur) - $potongan + $korekmin - $korekplus;
                                };
                                if (isset($data[$list_karyawan->p_karyawan_id]['Entitas']['Koreksi(+)'])) {
                                    $nmlok = $data[$list_karyawan->p_karyawan_id]['Entitas']['Koreksi(+)'];
                                    if (isset($gaji_entitas[$nmlok])) {
                                        $gaji_entitas[$nmlok] += $korekplus;
                                    } else {
                                        $gaji_entitas[$nmlok] = $korekplus;
                                    }
                                }
                                if (isset($data[$list_karyawan->p_karyawan_id]['Entitas']['Koreksi(-)'])) {
                                    $nmlok = $data[$list_karyawan->p_karyawan_id]['Entitas']['Koreksi(-)'];
                                    if (isset($gaji_entitas[$nmlok])) {
                                        $gaji_entitas[$nmlok] -= $korekmin;
                                    } else {
                                        $gaji_entitas[$nmlok] = -$korekmin;
                                    }
                                }
//if($no==15) die;
*/
                                ?>


                            </tr>
                            @endforeach
                            <tr>
                                <th style="font-size: 11px" class="statictable">Total</th>
                                <!--<td style="width: 50px;max-width: 50px;min-width: 50px"></td>
                                --><td></td>
                                <td></td>
                                <td style="font-size: 10px"></td>
                                <td></td>
                                <td style="font-size: 10px"></td>
                                <td></td>

                                <td style="width: 40px;min-width: 40px;max-width: 40px;"></td>
                                <?php 
                                
                                 $return = $help->preview_gaji($data_row,3,$total);
                               	echo $return['content'];
                               /*
                               <!--/*
                                <td style="width: 40px;min-width: 40px;max-width: 40px;"></td>
                                <td style="width: 40px;min-width: 40px;max-width: 40px;"></td>
                                <td id="total_field-uh" style="font-size:13px"><?= $help->rupiah2($total['UPAH HARIAN']) ?></td>
                                <td id="total_field-gaji_total_perbulan" style="font-size:13px"><?= $help->rupiah2($total['GAJI TOTAL PER BULAN']); ?></td>
                                <td id="total_field-gaji_total_upah_harian" style="font-size:13px"><?= $help->rupiah2($total['Total Pendapatan']) ?></td>
                                <td id="total_field-lembur" style="font-size:13px"><?= $help->rupiah2($total['UPAH LEMBUR']); ?></td>
                                <td id="total_field-total_pendapatan_all" style="font-size:13px"><?= $help->rupiah2($total['JUMLAH PENDAPATAN']); ?></td>
                                <td id="total_field-tunjangan_kost"><?= $help->rupiah2($total['KOST']); ?></td>
                                <td id="total_field-korekplus"><?= $help->rupiah2($total['KOREKSI (+)']); ?></td>
                                <td id="total_field-total_tunjangan_all" style="font-size:13px"><?= $help->rupiah2($total['JUMLAH TUNJANGAN']); ?></td>
                                <td id="total_field-telat"><?= $help->rupiah2($total['P.TELAT']); ?></td>
                                <td id="total_field-sewa_kost"><?= $help->rupiah2($total['SEWA KOST']); ?></td>
                                <td id="total_field-potfinger"><?= $help->rupiah2($total['TIDAK FINGERPRINT']); ?></td>
                                <td id="total_field-potpm"><?= $help->rupiah2($total['PM']); ?></td>

                                <td id="total_field-KKB"><?= $help->rupiah2($total['KOPERASI KKB']); ?></td>
                                <td id="total_field-ASA"><?= $help->rupiah2($total['KOPERASI ASA']); ?></td>
                                <td id="total_field-zakat"><?= $help->rupiah2($total['ZAKAT']); ?></td>
                                <td id="total_field-infaq"><?= $help->rupiah2($total['INFAQ']); ?></td>
                                <td id="total_field-korekmin"><?= $help->rupiah2($total['KOREKSI (-)']); ?></td>

                                <td style="font-size:13px" id="total_field-total_potongan_all"><?= $help->rupiah2($total['JUMLAH POTONGAN']); ?></td>
                                <td style="font-size:13px" id="total_field-total_pendapatan_tunjangan_all"><?= $help->rupiah2($total['TOTAL PENDAPATAN']); ?></td>
                                <td style="font-size:13px" id="total_field-thp_all"><?= $help->rupiah2($total['THP']); ?></td>
								-->
								*/
								?>
                            </tr>

                        </tbody>
						<tfoot>
                    <tr>

                        <th rowspan="2" class="statictable">Nama</th>
                        <th rowspan="2" style="width: 30px;max-width: 30px;min-width: 30px">No </th>

                        <th rowspan="2">Jabatan</th>
                        <th rowspan="2">Tanggal Masuk</th>
                        <th rowspan="2">Masa Kerja</th>
                        <th rowspan="2">Perusahaan</th>
                        <th rowspan="2">Pajak</th>
                        <th rowspan="2">Job Weight</th>
                        <th rowspan="2">Grade</th>



                        <?php
                        $return = $help->preview_gaji($data_row, 3, $total);
                        echo $return['content']; ?>
                    </tr>
                    <tr class="secondline">

						<?php 
						 for ($x = 0; $x < count($data_row); $x++) {
						 	echo '<th style="width: 40px;min-width: 40px;max-width: 40px;font-size: 9px;vertical-align: top;text-align: center;">'.$data_row[$x][0].'</th>';
						}
						?>



                        
                    </tr>
                </tfoot>
                    </table>


                </div>


                TOTAL GAJI PER ENTITAS
                <table style="width: 100%" class="table table-striped">
                    <tbody>
                        <?php
                        $t = 0;
                        foreach ($gaji_entitas as $key => $value) {
                            $t += $value;

                            echo '<tr>
            				<th style="width:10%">' . $key . '</th>
            				<td id="total_entitas-' . $key . '">' . $help->rupiah($value) . '</td>
            			</tr>';
                        }
                        ?>
                        <tr>
                            <td style="width:10%"> TOTAL</td>
                            <td> <?= $help->rupiah($t) ?></td>
                        </tr>


                    </tbody>
                </table>
                @endif
            </div>
            <!-- /.card-body -->
        </div>
    </form>
    <!-- /.card -->
    <!-- /.card -->
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<div class="modal fade" id="changeNominalModal" tabindex="-1" role="dialog" aria-labelledby="changeNominalModalLabel" aria-hidden="true">

    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Change Nominal: <span id="nama_field"></span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <input type="number" id="nominal_now" value="" class="form-control">
                <input type="hidden" id="id_prl_gaji" value="">
                <input type="hidden" id="field" value="">
                <input type="hidden" id="id_karyawan" value="">
                <input type="hidden" id="entitas" value="">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="save_change_nominal()">Save changes</button>
            </div>
        </div>
    </div>
</div>

<script>
    function save_change_nominal() {
		var id_nominal = $('#id_prl_gaji').val();
    	var nominal_now = $('#nominal_now').val();
    	var field = $('#field').val();
    	var id_karyawan = $('#id_karyawan').val();
    	var entitas = $('#entitas').val();
    	//alert(id_nominal) 
    	$.ajax({
			type: 'get',
			data: {
					'id_nominal': id_nominal,
					'nominal_now': nominal_now,
					'id_karyawan': id_karyawan,
					'id_prl': '<?=$id_prl;?>',
					'field': field
				},
			url: '<?=route('be.save_change_nominal');?>',
			dataType: 'html',
			success: function(data) {
				//
				//var tgl_cicilan = myDate.addMonths(cicilan);
				$('#input-'+field+'-'+id_karyawan+'-'+id_nominal).val(nominal_now);
				ha = $('.total_absensi-ha-'+id_karyawan).val();
				uh = $('.total_master-upah_harian-'+id_karyawan).val();
				
				$('#gaji_total_per_bulan-'+id_karyawan+'').val(parseFloat(uh)*22);
				gaji_total_per_bulan = parseFloat(uh)*22;
				rp = (new Intl.NumberFormat().format(gaji_total_per_bulan));
				$('#gaji_total_per_bulan-'+id_karyawan).html('<div style="width:100%;display: inline-flex;"><span class="text-left" style="width: 30%;">Rp </span><span class="text-right" style="width: 70%;">'+rp+'</span></div>  ');
				
				gaji_total_upah_harian = parseFloat(uh)*ha;
				rp = (new Intl.NumberFormat().format(gaji_total_upah_harian));
				$('#gaji_total_upah_harian-'+id_karyawan).html('<div style="width:100%;display: inline-flex;"><span class="text-left" style="width: 30%;">Rp </span><span class="text-right" style="width: 70%;">'+rp+'</span></div>  ');
				$('#input-gaji_total_upah_harian-'+id_karyawan+'').val(parseFloat(uh)*ha);
				
				
				var rp = formatRupiah(nominal_now,'');
				//alert(rp);
				if(field=='ha' )
				$('#'+field+'-'+id_karyawan+'-'+id_nominal).html(nominal_now);
					
				else
				$('#'+field+'-'+id_karyawan+'-'+id_nominal).html('<div style="width:100%;display: inline-flex;"><span class="text-left" style="width: 30%;">Rp </span><span class="text-right" style="width: 70%;">'+rp+'</span></div>  ');
				//alert(''+field+'-'+id_karyawan+'-'+id_nominal);
				total_pendapatan_karyawan = 0;
				$('.total_pendapatan-'+id_karyawan).each(function(){
				    total_pendapatan_karyawan += parseFloat(this.value);
				});
				
				total_pendapatan_field = 0;
				$('.total_pendapatan-'+field).each(function(){
				    total_pendapatan_field += parseFloat(this.value);
				});
				total_pendapatan_entitas = 0;
				$('.total_pendapatan-'+entitas).each(function(){
				    total_pendapatan_entitas += parseFloat(this.value);
				});
				total_pendapatan = 0;
				$('.total_pendapatan').each(function(){
				    total_pendapatan += parseFloat(this.value);
				});
				
				total_tunjangan_karyawan = 0;
				$('.total_tunjangan-'+id_karyawan).each(function(){
				    total_tunjangan_karyawan += parseFloat(this.value);
				});
				
				total_tunjangan_field = 0;
				$('.total_tunjangan-'+field).each(function(){
				    total_tunjangan_field += parseFloat(this.value);
				});
				total_tunjangan_entitas = 0;
				$('.total_tunjangan-'+entitas).each(function(){
				    total_tunjangan_entitas += parseFloat(this.value);
				});
				total_tunjangan = 0;
				$('.total_tunjangan').each(function(){
				    total_tunjangan += parseFloat(this.value);
				});
				
				total_potongan_karyawan = 0;
				$('.total_potongan-'+id_karyawan).each(function(){
				    total_potongan_karyawan += parseFloat(this.value);
				});
				
				total_potongan_field = 0;
				$('.total_potongan-'+field).each(function(){
				    total_potongan_field += parseFloat(this.value);
				});
				total_potongan_entitas = 0;
				$('.total_potongan-'+entitas).each(function(){
				    total_potongan_entitas += parseFloat(this.value);
				});
				total_potongan = 0;
				$('.total_potongan').each(function(){
				    total_potongan += parseFloat(this.value);
				});
				
				
				rp = (new Intl.NumberFormat().format(total_potongan_karyawan));
				$('#total_potongan_karyawan-'+id_karyawan).html('<div style="width:100%;display: inline-flex;"><span class="text-left" style="width: 30%;">Rp </span><span class="text-right" style="width: 70%;">'+rp+'</span></div>  ');
				//alert(rp);
				rp = (new Intl.NumberFormat().format(total_pendapatan_karyawan));
				$('#total_pendapatan_karyawan-'+id_karyawan).html('<div style="width:100%;display: inline-flex;"><span class="text-left" style="width: 30%;">Rp </span><span class="text-right" style="width: 70%;">'+rp+'</span></div>  ');
					
				
				rp = new Intl.NumberFormat().format(total_tunjangan_karyawan);
				//total_pendapatan_tunjangan_karyawan-394
				$('#total_tunjangan_karyawan-'+id_karyawan).html('<div style="width:100%;display: inline-flex;"><span class="text-left" style="width: 30%;">Rp </span><span class="text-right" style="width: 70%;">'+rp+'</span></div>  ');
				
				rp = new Intl.NumberFormat().format((total_pendapatan_karyawan+total_tunjangan_karyawan));
				$('#total_pendapatan_tunjangan_karyawan-'+id_karyawan).html('<div style="width:100%;display: inline-flex;"><span class="text-left" style="width: 30%;">Rp </span><span class="text-right" style="width: 70%;">'+rp+'</span></div>  ');
				
				rp = new Intl.NumberFormat().format((total_pendapatan_karyawan+total_tunjangan_karyawan-total_potongan_karyawan))
				$('#thp_karyawan-'+id_karyawan).html('<div style="width:100%;display: inline-flex;"><span class="text-left" style="width: 30%;">Rp </span><span class="text-right" style="width: 70%;">'+rp+'</span></div>  ');
				
				rp = new Intl.NumberFormat().format((total_pendapatan_entitas+total_tunjangan_entitas-total_potongan_entitas))
				$('#total_entitas-'+entitas).html('Rp. '+rp+'');
				
				rp = new Intl.NumberFormat().format((total_pendapatan_field+total_tunjangan_field-total_potongan_field))
				$('#total_field-'+field).html('<div style="width:100%;display: inline-flex;"><span class="text-left" style="width: 30%;">Rp </span><span class="text-right" style="width: 70%;">'+rp+'</span></div>');
				
				$('#input-jumlah_pendapatan-'+id_karyawan).val(total_pendapatan_karyawan);
				$('#input-jumlah_tunjangan-'+id_karyawan).val(total_tunjangan_karyawan);
				$('#input-jumlah_potongan-'+id_karyawan).val(total_potongan_karyawan);
				
				jumlah_pendapatan = 0;
				$('.jumlah_pendapatan').each(function(){
				    jumlah_pendapatan += parseFloat(this.value);
				});
				jumlah_tunjangan = 0;
				$('.jumlah_tunjangan').each(function(){
				    jumlah_tunjangan += parseFloat(this.value);
				});
				jumlah_potongan = 0;
				$('.jumlah_potongan').each(function(){
				    jumlah_potongan += parseFloat(this.value);
				});
				
				
				
				rp = new Intl.NumberFormat().format((jumlah_potongan))
				$('#total_field-total_potongan_all').html('<div style="width:100%;display: inline-flex;"><span class="text-left" style="width: 30%;">Rp </span><span class="text-right" style="width: 70%;">'+rp+'</span></div>');
				rp = new Intl.NumberFormat().format((jumlah_pendapatan))
				$('#total_field-total_pendapatan_all').html('<div style="width:100%;display: inline-flex;"><span class="text-left" style="width: 30%;">Rp </span><span class="text-right" style="width: 70%;">'+rp+'</span></div>');
				rp = new Intl.NumberFormat().format((jumlah_tunjangan))
				$('#total_field-total_tunjangan_all').html('<div style="width:100%;display: inline-flex;"><span class="text-left" style="width: 30%;">Rp </span><span class="text-right" style="width: 70%;">'+rp+'</span></div>');
				rp = new Intl.NumberFormat().format((jumlah_pendapatan+jumlah_tunjangan))
				$('#total_field-total_pendapatan_tunjangan_all').html('<div style="width:100%;display: inline-flex;"><span class="text-left" style="width: 30%;">Rp </span><span class="text-right" style="width: 70%;">'+rp+'</span></div>');
				rp = new Intl.NumberFormat().format((jumlah_pendapatan+jumlah_tunjangan-jumlah_potongan))
				$('#total_field-thp_all').html('<div style="width:100%;display: inline-flex;"><span class="text-left" style="width: 30%;">Rp </span><span class="text-right" style="width: 70%;">'+rp+'</span></div>');
				
		$('#changeNominalModal').modal('toggle');
				
				
				
				//console.log(data);
			},
			error: function (error) {
				console.log('error; ' + eval(error));
				//alert(2);
			}
		});
	}
    function change_nominal(id,nominal_now,field,fieldtext,id_karyawan,entitas) {
    	$('#id_prl_gaji').val(id);
    	$('#nominal_now').val($('#input-'+field+'-'+id_karyawan+'-'+id).val());
    	$('#field').val(field);
    	$('#nama_field').html(fieldtext);
    	$('#id_karyawan').val(id_karyawan);
    	$('#entitas').val(entitas);
		$('#changeNominalModal').modal('toggle');
		
    }
    		function formatRupiah(angka, prefix){
			var number_string = angka.replace(/[^,\d]/g, '').toString(),
			split   		= number_string.split(','),
			sisa     		= split[0].length % 3,
			rupiah     		= split[0].substr(0, sisa),
			ribuan     		= split[0].substr(sisa).match(/\d{3}/gi);
 
			// tambahkan titik jika yang di input sudah menjadi angka ribuan
			if(ribuan){
				separator = sisa ? '.' : '';
				rupiah += separator + ribuan.join('.');
			}
 
			rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
			return prefix == undefined ? rupiah : (rupiah ? '' + rupiah : '');
		}
</script>
@endsection
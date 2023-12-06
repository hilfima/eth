@extends('layouts.appsA')



@section('content')
<style>
    .trr {
        background-color: #0099FF;
        color: #ffffff;
        align: center;
        padding: 10px;
        height: 20px;
    }

    tr.odd>td {
        background-color: #E3F2FD;
    }

    tr.even>td {
        background-color: #BBDEFB;
    }

    .fixedTable .table {
        background-color: white;
        width: auto;
        display: table;
    }

    .fixedTable .table tr td,
    .fixedTable .table tr th {
        min-width: 100px;
        width: 100px;
        min-height: 20px;
        height: 20px;
        padding: 5px;
        max-width: 100px;
    }

    .fixedTable-header {
        width: 100%;
        height: 60px;
        /*margin-left: 150px;*/
        overflow: hidden;
        border-bottom: 1px solid #CCC;
    }

    .fixedTable-sidebar {
        width: 0;
        height: 510px;
        float: left;
        overflow: hidden;
        border-right: 1px solid #CCC;
    }

    @media screen and (max-height: 700px) {
        .fixedTable-body {
            overflow: auto;
            width: 100%;
            height: 410px;
            float: left;
        }
    }

    @media screen and (min-height: 700px) {
        .fixedTable-body {
            overflow: auto;
            width: 100%;
            height: 510px;
            float: left;
        }
</style>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    @include('flash-message')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Rekap Slip</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{!! route('admin') !!}">Admin</a></li>
                        <li class="breadcrumb-item active">Rekap Slip</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <form id="cari_absen" class="form-horizontal" method="get" action="{!! route('be.slip_gaji') !!}">
        <div class="card">
            <div class="card-header">
                <!--<h3 class="card-title">DataTable with default features</h3>-->
                <!--<a href="http://localhost/get-data/udp.php" target="_blank" title='Sync Absen' data-toggle='tooltip'><span class='fa fa-sync'></span> Sync Absen </a>-->
                {{ csrf_field() }}
                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label>Periode Generate</label>
                            <select class="form-control select2" name="prl_generate" style="width: 100%;" onchange="change_prl_generate(this)" required>
                                <option value="">Pilih Periode Generate</option>
                                <?php
                                foreach ($periode as $periode) {
                                    	$selected = '';
                                    if ($periode->prl_generate_id == $id_prl) { 
                                    	$selected = 'selected';   
                                    } 
                                    $tipe = $periode->is_thr?'THR':'Gaji';
                                    $detail = "";
                                    if(!$periode->is_thr)
                                    $detail = ' Bulan: ' . $periode->bulan_gener . ' | Absen:' . $periode->tgl_awal . ' - ' . $periode->tgl_akhir . ' | Lembur:' . $periode->tgl_awal_lembur . ' - ' . $periode->tgl_akhir_lembur ;
                                    echo '<option  value="' . $periode->prl_generate_id . '" '.$selected.'>'.$tipe.' |' . $periode->tipe . ' - Periode: ' . $periode->tahun_gener . $detail .'</option>';
                                   
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label>Karyawan</label>
                            <select class="form-control select3" name="p_karyawan" id="p_karyawan" style="width: 100%;" required>
                                <!--<option value="">Pilih Karyawan</option>-->
                                <?php
                                // foreach ($list_karyawan as $users) {
                                //     if ($users->p_karyawan_id == $request->get('p_karyawan')) {
                                //         echo '<option selected="selected" value="' . $users->p_karyawan_id . '">' . $users->nama . '  ' . '</option>';
                                //     } else {
                                //         echo '<option value="' . $users->p_karyawan_id . '">' . $users->nama . '  ' . '</option>';
                                //     }
                                // }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-md-9">
                        <a href="{!! route('be.slip_gaji') !!}" class="btn btn-primary"><span class="fa fa-sync"></span> Reset</a>
                        <button type="submit" name="Cari" class="btn btn-primary" value="Cari"><span class="fa fa-search"></span> Cari</button>
						<?php if (!empty($request->get('prl_generate') and $request->get('p_karyawan'))) { ?>
						  <button type="submit" name="Cari" class="btn btn-primary" value="PDF"><span class="fa fa-search"></span> PDF</button>
						<?php }?>
                    </div>

                </div>
            </div>
            <script>
                function change_prl_generate(e){
                    $.ajax({
        				type: 'post',
        				data:{_token: "{{ csrf_token() }}", id_prl:$(e).val()},
                        cache : false,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
        				url: '<?=route('be.karyawan_gaji')?>',
        				dataType: 'html',
        				success: function(data){
        					//alert(data.respon)
        					$('#p_karyawan').html(data);
        					$(".select3").select2();
        					
        				}
        			});
                }
            </script>

        </div>
        <div class="card-body d-none">
            <div class="row">
                <div class="col-sm-12">


                </div>
            </div>
        </div>
        <!-- /.card-body -->

        
            <?php if (!empty($request->get('prl_generate') and $request->get('p_karyawan'))) { ?>

                <!-- Page Title -->
                <div class="row">


                    
                    <div class="col-sm-7 col-8 text-right m-b-30">
                        <div class="btn-group btn-group-sm">
                          

                        </div>

                    </div>
                </div>

                <!-- /Page Title -->

                <?php 
                //$id_prl = $request
                $id_kary = $request->get('p_karyawan');
                
				$view = 'slip'; 
                echo view('frontend.slip.pdf_slip',compact('karyawan','request','id_prl','id_kary','help','generate','view'));?>
                <!-- /.card -->
                <!-- /.card -->
                <!-- /.content -->

        <?php }?>
    </form>
    <!-- /.content-wrapper -->
    @endsection
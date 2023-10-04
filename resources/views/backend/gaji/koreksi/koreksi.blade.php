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
                        <h1 class="m-0 text-dark"> Koreksi</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{!! route('be.Koreksi') !!}">Koreksi</a></li>
                            <li class="breadcrumb-item active"> Koreksi</li>
                        </ol>
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
                
                <a href="{!! route('be.tambah_Koreksi',$tambah) !!}" title='Tambah' data-toggle='tooltip'><span class='fa fa-plus'></span>  Koreksi </a>
                <a href="{!! route('be.tambah_Koreksi_pajak',$tambah) !!}" title='Tambah' data-toggle='tooltip'><span class='fa fa-plus'></span>  Koreksi Pajak </a>
            </div>
                @endif
            <!-- /.card-header -->
            <div class="card-body">
             <form id="cari_absen" class="form-horizontal" method="get" action="{!! route('be.Koreksi') !!}">
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
                        </div>	
                    </div>	
                     <button type="submit" name="Cari" class="btn btn-primary" value="Cari"><span class="fa fa-search"></span> Cari</button>
                     </form>
                     @if($request->get('prl_generate'))
                <table id="example1" class="table table-striped custom-table mb-0">
                    <thead>
                    <tr>
                        <th>No.</th>
                      
                        <th>Nama</th>
                        <th>Nama Lokasi</th>
                        <th>Sumber Dana</th>
                        <th>Tipe</th>
                        <th>Nominal</th>
                        <th>Keterangan</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $no=0 ?>
                    @if(!empty($Koreksi))
                        @foreach($Koreksi as $Koreksi)
                           @if(!(empty($Koreksi->nama_type) and empty($Koreksi->keterangan)))
                            <?php $no++ ?>
                            <tr>
                                <td>{!! $no !!}</td>
                                <td>{!! $Koreksi->nama !!}</td>
                                <td>{!! $Koreksi->nmlokasi !!}</td>
                                <td>{!! $Koreksi->sumber_dana !!}</td>
                                <td>{!! $Koreksi->nama_type; !!}</td>
                                <td style='text-align:right'>{!! number_format($Koreksi->nominal,0) !!}</td>
                                <td>{!! $Koreksi->keterangan !!}</td>
                                <td style="text-align: center">
                                   
                                    <a href="{!! route('be.edit_Koreksi',$Koreksi->prl_gaji_detail_id) !!}" title='Ubah' data-toggle='tooltip'><span class='fa fa-edit'></span></a>
                                    <a href="{!! route('be.hapus_Koreksi',$Koreksi->prl_gaji_detail_id) !!}" title='Hapus' data-toggle='tooltip'><span class='fa fa-trash'></span></a>
                                </td>
                               
                            </tr>
                            @endif
                    @endforeach
                    @endif
                </table>
           			@endif
            </div>
            <?php
           // print_r($Koreksi);
            ?>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
        <!-- /.card -->
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
@endsection

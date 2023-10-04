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
                        <h1 class="m-0 text-dark">Pengajuan Faskes</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{!! route('admin') !!}">Admin</a></li>
                            <li class="breadcrumb-item active">Pengajuan Faskes</li>
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
                <a href="{!! route('be.tambah_pengajuan_faskes') !!}" title='Tambah' data-toggle='tooltip'><span class='fa fa-plus'></span> Pengajuan Faskes </a>
            </div>
            <div class="card-body">
            	<form id="cari_absen" class="form-horizontal" method="get" action="{!!route('be.pengajuan_faskes')!!}">
                <input type="hidden" name="_token" value="BU37mt9onXbQGdPbalUa4Cr97nDgjboBBu7BSYp7">
                <div class="row">
                    <div class="col-lg-4">
					<div class="form-group">
							<label>Karyawan</label>
							<select class="form-control select2" name="karyawan" style="width: 100%;" >
								<option value="">Pilih Karyawan</option>
								<?php
								foreach($karyawan AS $karyawan){
										$selected = '';
									if($karyawan->p_karyawan_id== $request->get('karyawan')){
										$selected = 'selected';
										
									}
									
										echo '<option value="'.$karyawan->p_karyawan_id.'" '.$selected.'>'.$karyawan->nama.'</option>';
								}
								?>
							</select>
						</div>
						</div>
                    <div class="col-lg-3">
					<div class="form-group">
							<label>Entitas</label>
							<select class="form-control select2" name="entitas" style="width: 100%;" >
								<option value="">Pilih Entitas</option>
								<?php
								foreach($entitas AS $entitas){
										$selected = '';
									if($entitas->m_lokasi_id== $request->get('entitas')){
										$selected = 'selected';
										
									}
									
										echo '<option value="'.$entitas->m_lokasi_id.'" '.$selected.'>'.$entitas->nama.'</option>';
								}
								?>
							</select>
						</div>
						</div>
                    <div class="col-lg-2">
                        <div class="form-group">
                            <label>Tanggal Awal</label>
                            <div class="input-group date" id="tgl_awal" data-target-input="nearest">
                                <input type="date" class="form-control" id="tgl_awal" name="tgl_awal" value="<?= $request->get('tgl_awal') ? $request->get('tgl_awal') : ''; ?>">

                            </div>
                        </div>
                    </div>

                    <div class="col-lg-2">
                        <div class="form-group">
                            <label>Tanggal Akhir</label>
                            <div class="input-group date" id="tgl_akhir" data-target-input="nearest">
                                <input type="date" class="form-control" id="tgl_akhir" name="tgl_akhir" value="<?= $request->get('tgl_akhir') ? $request->get('tgl_akhir') : ''; ?>">

                            </div>
                        </div>
                    </div>
                </div>
				<div class="form-group">
                    <div class="col-md-12">
                        <a href="{!!route('be.pengajuan_faskes')!!}" class="btn btn-primary"><span class="fa fa-sync"></span> Reset</a>
                        <button type="submit" name="Cari" class="btn btn-primary" value="Cari"><span class="fa fa-search"></span> Cari</button>


                    </div>
                </div>
            </form>
                <table id="example1" class="table table-striped custom-table mb-0">
                    <thead>
                    <tr>
                        <th>No.</th>
                        <th>Nama</th>
                        <th>Entitas Pengajuan</th>
                        <th>Pasien</th>
                        <th>Penyakit</th>
                        <th>Tanggal</th>
                        <th>Nominal</th>
                        <th>Keterangan</th>
                        <th>Foto</th>
                        <th>Status Pengajuan</th>
                        <th>Action</th>
                       
                    </tr>
                    </thead>
                    <tbody>
                   <?php $no=0;$nominal=0; ?>
                    @if(!empty($faskes))
                        @foreach($faskes as $faskes)
                            <?php $no++;
                           $nominal += $faskes->nominal;
                            ?>
                            <tr>
                                <td>{!! $no !!}</td>
                                <td >{!! $faskes->nama !!}</td>
                                <td >{!! $faskes->nmlokasi !!}</td>
                                <td>{!! ($faskes->p_karyawan_keluarga_id==-1?$faskes->nama.'(Karyawan)':$faskes->nama_terkait.'('.$faskes->hubungan.')') !!}</td>
                                <td>{!! ($faskes->nama_penyakit) !!}</td>
                                <td>{!! $help->tgl_indo($faskes->tanggal_pengajuan) !!}</td>
                                <td>{!! $help->rupiah2($faskes->nominal) !!}</td>
                                <td  style="width: 30px;">{!! $faskes->keterangan !!}</td>
                               
                                @if(!empty($faskes->foto))
                                    <td style="text-align: center;width: 30px;"><a href="{!! asset('dist/img/file/'.$faskes->foto) !!}" target="_blank" title="Download"><span class="fa fa-download"></span></a></td>
                                @else
                                    <td style="text-align: center;width: 30px;"></td>
                                @endif
                                
                                <td style="text-align: center;width: 30px;">
                                	@if($faskes->appr_status==1)
                                        <span class="fa fa-check-circle"> Disetujui</span>
                                    @elseif($faskes->appr_status==2)
                                            <span class="fa fa-window-close"> Ditolak</span>
                                    @else
                                        <span class="fa fa-edit"> Pending</span>
                                    @endif
                                </td>
                                <td style="text-align: center;width: 30px;">
                                	@if($faskes->appr_status==0)
                                      <a href="{{route('be.appr_faskes',$faskes->t_faskes_id)}}" title='Approve' data-toggle='tooltip'><span class='fa fa-check'></span></a>
                                      <a href="{{route('be.appr_tolak_faskes',$faskes->t_faskes_id)}}" title='Tolak' data-toggle='tooltip'><span class='fa fa-times'></span></a>
                                    @endif
                                      <a href="#" title='Lihat' data-toggle='tooltip'><span class='fa fa-search'></span></a>
                                      <a href="{{route('be.hapus_pengajuan_faskes',$faskes->t_faskes_id)}}" title='Lihat' data-toggle='tooltip'><span class='fa fa-trash'></span></a>
                                </td>
                                
                            </tr>
                        @endforeach
                    @endif
                </table>
                <h3>Total: <?=$help->rupiah($nominal)?></h3>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
        <!-- /.card -->
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
@endsection

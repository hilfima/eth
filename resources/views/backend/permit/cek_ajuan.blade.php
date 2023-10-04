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
                        <h1 class="m-0 text-dark">Cek Ajuan</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{!! route('admin') !!}">Admin</a></li>
                            <li class="breadcrumb-item active">Cek Ajuan</li>
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
                <!--<a href="http://localhost/get-data/udp.php" target="_blank" title='Sync Absen' data-toggle='tooltip'><span class='fa fa-sync'></span> Sync Absen </a>-->
                <form id="cari_absen" class="form-horizontal" method="get" action="{!! route('be.cari_ajuan') !!}">
                    {{ csrf_field() }}
                    <div class="row">
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label>Nama</label>
                                <select class="form-control select2" name="nama" style="width: 100%;">
                                    <option value="">Pilih Nama</option>
                                    <?php
                                    foreach($users AS $users){
                                        if($users->p_karyawan_id==$request->nama ){
                                            echo '<option selected="selected" value="'.$users->p_karyawan_id.'">'.$users->nama.' ('.$users->nmdept.') '. '</option>';
                                        }
                                        else{
                                            echo '<option value="'.$users->p_karyawan_id.'">'.$users->nama.' ('.$users->nmdept.') '. '</option>';
                                        }
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
                                <label>Jenis Ajuan</label>
                                <select class="form-control select2" name="tipe" style="width: 100%;">
                                    <option value="">Pilih Jenis Ajuan</option>
                                    <?php
                                    foreach($ajuan AS $ajuans){
                                        if($ajuans->tipe==$tipe){
                                            echo '<option selected="selected" value="'.$ajuans->tipe.'">'.$ajuans->nmtipe.'</option>';
                                        }
                                        else{
                                            echo '<option value="'.$ajuans->tipe.'">'.$ajuans->nmtipe. '</option>';
                                        }
                                    }
                                  
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <div class="form-group">
                                <label>Periode Gajian</label>
                                <select class="form-control select2" name="periode_gajian" style="width: 100%;">
                                    <option value="">Pilih Periode Gajian</option>
                                    <option value="1" <?=$request->periode_gajian==1?'selected':'';?>>Bulanan</option>
                                    <option value="-1" <?=$request->periode_gajian==-1?'selected':'';?>>Pekanan</option>
                                   
                                </select>
                            </div>
                        </div> 
                        <div class="col-lg-2">
                            <div class="form-group">
                                <label>Status Approve</label>
                                <select class="form-control select2" name="status" style="width: 100%;">
                                    <option value="">Pilih Status Approve</option>
                                    <option value="3" <?=$status==3?'selected':'';?>>Pending</option>
                                    <option value="1" <?=$status==1?'selected':'';?>>Setuju</option>
                                    <option value="2" <?=$status==2?'selected':'';?>>Tolak</option>
                                   
                                </select>
                            </div>
                        </div> 
                       <div class="col-lg-6">
                            <div class="form-group">
                                <label>Tanggal Awal</label>
                                <div class="input-group date" id="tgl_awal" data-target-input="nearest">
                                    <input type="date" class="form-control " id="tgl_awal" name="tgl_awal" value="{!! $tgl_awal!='1970-01-01'?$tgl_awal:date('Y-m-d') !!}" data-target="#tgl_awal"  required/>
                                  
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Tanggal Akhir</label>
                                <div class="input-group date" id="tgl_akhir" data-target-input="nearest">
                                    <input type="date" class="form-control " id="tgl_akhir" name="tgl_akhir" value="{!! $tgl_akhir!='1970-01-01'?$tgl_akhir:date('Y-m-d') !!}" data-target="#tgl_akhir" required/>
                                   
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-12">
                            <a href="{!! route('be.ajuan') !!}" class="btn btn-primary"><span class="fa fa-sync"></span> Reset</a>
                            <button type="submit" name="Cari" class="btn btn-primary" value="Cari"><span class="fa fa-search"></span> Cari</button>
                            <button type="submit" name="Cari" class="btn btn-primary" value="Excel"><span class="fa fa-file-excel"></span> Excel</button>

                        </div>
                    </div>
                </form>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <table id="example1" class="table table-striped custom-table mb-0">
                    <thead>
                    <tr>
                        <th>No.</th>
                        <th>Kode </th>
                        <th>Nama </th>
                        <th>Jenis </th>
                        <th>File</th>
                        <th>Tgl. Ajuan</th>
                        <th>Tgl Awal</th>
                        <th>Tgl Akhir</th>
                        <th>Jam Awal</th>
                        <th>Jam Akhir</th>
                        <th>Lama</th>
                        <th>Approval</th>
                        <th>Status Appr</th>
                        <th>Tanggal Appr</th>
                        <th>Approval 2</th>
                        <th>Status Appr 2</th>
                        <th>Tanggal Appr 2</th>
                        <th>Alasan<span style="font-size:10px">(Hanya IDT& IPM)</span></th>
                        <th>Jam Masuk Finger<span style="font-size:10px">(Hanya IDT& IPM)</span></th>
                        <th>Jam Keluar Finger<span style="font-size:10px">(Hanya IDT& IPM)</span></th>
                        <th>Keterangan</th>
                        <th>Tipe Lembur</th>
                        <th>Gajian</th>
                        <th>Approval HR</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $no=0 ?>
                    @if(!empty($data))
                        @foreach($data as $datas)
                            <?php $no++ ?>
                            <tr>
                                <td>{!! $no !!}</td>
                                <td>{!! $datas->kode !!}</td>
                                <td>{!! $datas->nama_lengkap !!}</td>
                                <td>{!! $datas->nmtipe !!}</td>
                                @if(!empty($datas->foto))
                                    <td style="text-align: center"><a href="{!! asset('dist/img/file/'.$datas->foto) !!}" target="_blank" title="Download"><span class="fa fa-download"></span></a></td>
                                @else
                                    <td></td>
                                @endif
                                
                                <td>{!! date('d-m-Y', strtotime($datas->create_date)) !!}</td>
                                <td>{!! date('d-m-Y', strtotime($datas->tgl_awal)) !!}</td>
                                <td>{!! date('d-m-Y', strtotime($datas->tgl_akhir)) !!}</td>
                                <td>{!! $datas->jam_awal !!}</td>
                                <td>{!! $datas->jam_akhir !!}</td>
                                <td>{!! $datas->lama !!}</td>
                                <td>{!! $datas->nama_appr !!}</td>
								<td>{!! $status = ($datas->nama_appr?$datas->sts_pengajuan:'') !!}</td>
                                <td>{!! $datas->tgl_appr_1 !!}</td>
                                <td>{!! $datas->nama_appr2 !!}</td>
								<td>{!! $status!='Ditolak'?($datas->nama_appr2?$datas->sts_pengajuan2:''):'' !!}</td>
                                <td>{!! $datas->tgl_appr_2 !!}</td>
                                <td>{!! $datas->alasan_idt_ipm !!}</td>
                                <td>{!! $datas->jam_masuk_finger !!}</td>
                                <td>{!! $datas->jam_keluar_finger !!}</td>
                                <td>{!! $datas->keterangan !!}</td>
                                <td>{!! $datas->tipe_lembur !!}</td>
                                <td>{!! $datas->gajian !!}</td>
                                <td style="text-align: center">
                                    @if($datas->status_appr_hr==1)
                                        <span class="fa fa-check-circle"> Disetujui</span>
                                        @elseif($datas->status_appr_hr==2)
                                            <span class="fa fa-window-close"> Ditolak</span>
                                    @else
                                        <span class="fa fa-edit"> Pending</span>
                                    @endif
                                </td>
                                <td style="text-align: center">
                                    <a href="{!! route('be.lihat',[$datas->t_form_exit_id,'type=-1']) !!}" title='Lihat' data-toggle='tooltip'><span class='fa fa-search'></span></a>
                                @if(Auth::user()->role==3 or Auth::user()->role==-1 or Auth::user()->role==5  ) 
                                    <a href="{!! route('be.delete_pengajuan',[$datas->t_form_exit_id,'tgl_awal='.$request->tgl_awal.'&tgl_akhir='.$request->tgl_akhir.'&nama='.$request->nama]) !!}" title='Lihat' data-toggle='tooltip'><span class='fa fa-trash'></span></a>
                                 @endif
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </table>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
        <!-- /.card -->
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
@endsection

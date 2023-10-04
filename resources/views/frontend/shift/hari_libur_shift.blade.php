@extends('layouts.app_fe')

@section('content')
    <!-- Content Wrapper. Contains page content -->
<div class="row">
			<div class="col-xl-3 col-lg-4 col-md-12 theiaStickySidebar">
						<?= view('layouts.app_side',compact('help'));?>
					</div>
<div class="col-xl-9 col-lg-8 col-md-12">
    @include('flash-message')
   
    
	<!-- Main content -->
	<div class="card shadow-sm ctm-border-radius">
<div class="card-body align-center">
<h4 class="card-title float-left mb-0 mt-2">Libur Karyawan Shift & Pergantian Libur Karyawan</h4>

</div>
</div>
<div class="card">
            <div class="card-header">
                <!--<h3 class="card-title">DataTable with default features</h3>-->
                <a href="{!! route('fe.tambah_libur_shift') !!}" title='Tambah' data-toggle='tooltip'><span class='fa fa-plus'></span> Libur Shift </a>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
            <form id="cari_absen" class="form-horizontal" method="get" action="{!!route('fe.hari_libur_shift')!!}">
                    <input type="hidden" name="_token" value="BU37mt9onXbQGdPbalUa4Cr97nDgjboBBu7BSYp7">
                     <div class="row">
                        
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Tanggal Awal </label>
                                <div class="input-group date" id="tgl_awal" data-target-input="nearest">
                                    <input type="date" class="form-control" id="tgl_awal" name="tgl_awal" value="<?= $request->get('tgl_awal')?$request->get('tgl_awal'):date("Y-m-d")?>">
                                   
                                </div>
                            </div>
                        </div>
                      
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Tanggal Akhir</label>
                                <div class="input-group date" id="tgl_akhir" data-target-input="nearest">
                                    <input type="date" class="form-control" id="tgl_akhir" name="tgl_akhir" value="<?= $request->get('tgl_akhir')?$request->get('tgl_akhir'):date("Y-m-d")?>">
                                   
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-12">
                            <a href="{!!route('fe.jadwal_shift')!!}" class="btn btn-primary"><span class="fa fa-sync"></span> Reset</a>
                            <button type="submit" name="Cari" class="btn btn-primary" value="Cari"><span class="fa fa-search"></span> Cari</button>
                            

                        </div>
                    </div>
                </form>
                <table id="example1" class="table table-striped custom-table mb-0">
                    <thead>
                    <tr>
                        <th>No.</th>
                        <th>Tanggal </th>
                        <th>Nama </th>
                        <th>Keterangan</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $no=0 ?>
                    @if(!empty($Libur_shift))
                        @foreach($Libur_shift as $Libur_shift)
                            <?php $no++ ?>
                            <tr>
                                <td>{!! $no !!}</td>
                                <td>{!! date('d-m-Y',strtotime($Libur_shift->tanggal)) !!}</td>
                                <td>{!! $Libur_shift->nama !!} ({!! $Libur_shift->m_jabatan_id !!})</td>
                                <td>{!! $Libur_shift->keterangan !!}</td>
                                
                                <td style="text-align: center">
                                    <a href="{!! route('fe.edit_libur_shift',$Libur_shift->absen_libur_shift_id) !!}" title='Ubah' data-toggle='tooltip'><span class='fa fa-edit'></span></a>
                                    <a href="{!! route('fe.hapus_libur_shift',$Libur_shift->absen_libur_shift_id) !!}" title='Hapus' data-toggle='tooltip'><span class='fa fa-trash'></span></a>
                                </td>
                            </tr>
                    @endforeach
                    @endif
                </table>
            </div>
            <!-- /.card-body -->
        </div>

@endsection
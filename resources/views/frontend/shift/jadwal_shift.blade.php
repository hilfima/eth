 @extends('layouts.app_fe')



@section('content')
<div class="row">
			<div class="col-xl-3 col-lg-4 col-md-12 theiaStickySidebar">
						<?= view('layouts.app_side',compact('help'));?>
					</div>
<div class="col-xl-9 col-lg-8 col-md-12">
					<!-- Page Title -->
					 <div class="content-wrapper">
    @include('flash-message')
   
    
	<!-- Main content -->
	<div class="card shadow-sm ctm-border-radius">
<div class="card-body align-center">
<h4 class="card-title float-left mb-0 mt-2">Jadwal Shift</h4>

</div>
</div>
<div class="card">
<div class="card-body">
<form id="cari_absen" class="form-horizontal" method="get" action="{!!route('fe.jadwal_shift')!!}">
                    <input type="hidden" name="_token" value="BU37mt9onXbQGdPbalUa4Cr97nDgjboBBu7BSYp7">
                     <div class="row">
                        
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Tanggal Awal</label>
                                <div class="input-group date" id="tgl_awal" data-target-input="nearest">
                                    <input type="date" class="form-control" id="tgl_awal" name="tgl_awal" value="<?= $request->get('tgl_awal')?>">
                                   
                                </div>
                            </div>
                        </div>
                      
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Tanggal Akhir</label>
                                <div class="input-group date" id="tgl_akhir" data-target-input="nearest">
                                    <input type="date" class="form-control" id="tgl_akhir" name="tgl_akhir" value="<?= $request->get('tgl_akhir')?>">
                                   
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
                <table id="example1" class="table table-bordered table-striped">
                    <thead>
                    <tr>
                        <th>No.</th>
                        <th>Tanggal</th>
                        <th>Jam Masuk</th>
                        <th>Jam Keluar</th>
                        <th>Keterangan</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $no =0;?>
                    		@foreach($shift as $shift)
                    <?php $no++;?>
                    		<tr>
                                <td><?=$no;?></td>
                                <td><?=$shift->tanggal?$help->tgl_indo($shift->tanggal):'';?></td>
                                <td><?=$shift->jam_masuk;?></td>
                                <td><?=$shift->jam_keluar;?></td>
                                <td><?=$shift->keterangan;?></td>
                            </tr>
                            @endforeach
                   </table>
            </div>
            </div>
@endsection
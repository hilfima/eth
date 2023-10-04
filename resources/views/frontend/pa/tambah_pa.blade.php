@extends('layouts.app_fe')

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
    @include('flash-message')
    <div class="row">
			<div class="col-xl-3 col-lg-4 col-md-12 theiaStickySidebar">
						<?= view('layouts.app_side');?>
					</div>
<div class="col-xl-9 col-lg-8 col-md-12">
        <!-- Content Header (Page header) -->
      <div class="card shadow-sm ctm-border-radius">
<div class="card-body align-center">
<h4 class="card-title float-left mb-0 mt-2">Penilaian Kinerja</h4>

</div>
</div> 

        <!-- /.content-header -->

        <!-- Main content -->
        <div class="card">
            <div class="card-header">
                <!--<h3 class="card-title">DataTable with default features</h3>-->
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <!-- form start -->
                <form class="form-horizontal" method="POST" action="{!! route('fe.simpan_pa') !!}" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="row">
                        <div class="col-sm-6">
                            <!-- text input -->
                            <div class="form-group">
                                <label>Nama</label>
                                <select class="form-control select2" name="nama" style="width: 100%;" required>
                                    <option value="">Pilih Karyawan</option>
                                    <?php
                                    foreach($karyawan AS $karyawan){
                                        echo '<option value="'.$karyawan->p_karyawan_id.'">'.$karyawan->nama.'</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Penilai</label>
                                <select class="form-control select2" name="manager" style="width: 100%;" required>
                                    
                                    <?php
                                        echo '<option value="'.$idkar[0]->p_karyawan_id.'">'.$idkar[0]->nama.'</option>';
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Tanggal</label>
                                <div class="input-group date" id="reservationdate" data-target-input="nearest">
                                    <input type="date" class="form-control " id="tanggal" name="tanggal" required/>
                                    
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Approved</label>
                                <select class="form-control select2" name="direktur" style="width: 100%;" required>
                                    <option value="">Pilih Approved</option>
                                    <?php
                                    foreach($direktur AS $direktur){
                                        echo '<option value="'.$direktur->p_karyawan_id.'">'.$direktur->nama.'</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="card-body">
                        <table id="example1" class="table table-bordered table-striped">
                            <tbody>
                            <?php $no=0 ?>
                            @foreach($pa as $pa)
                                <?php
                                $id=$pa->m_pa_id;
                                $pertanyaan=$pa->pertanyaan;
                                $pertanyaan=$pa->sub1;
                                $pertanyaan=$pa->sub2;
                                $pilihan_a=4;
                                $pilihan_b=3;
                                $pilihan_c=2;
                                $pilihan_d=1;
                                ?>
                                <p style="text-align: center"><strong>{{ $pa->nama }}</strong></p>
                                <p style="text-align: center"><strong>{{ $pa->pertanyaan }}</strong></p>
                                <input type="hidden" id="jumlah" name="jumlah" value="{{ $jumlah }}" >
                                <input type="hidden" id="id_soal" name="id_soal[]" value="{{ $pa->m_pa_id }}" >
                                <div class="form-group clearfix">
                                    <div class="row">
                                        <div class="col-lg-4">
                                            {!! $pa->sub1 !!}
                                        </div>
                                        <div class="col-lg-4" style="text-align: center">
                                            <div class="icheck-primary d-inline">
                                                <input type="radio" id="[{{$pa->m_pa_id.'_radioPrimary4'}}]" name="pilihan[{{$pa->m_pa_id}}]" value="{!! $pilihan_a !!}"  required="">
                                                <label for="[{{$pa->m_pa_id.'_radioPrimary4'}}]">4
                                                </label>
                                            </div>
                                            <div class="icheck-primary d-inline">
                                                <input type="radio" id="[{{$pa->m_pa_id.'_radioPrimary3'}}]" name="pilihan[{{$pa->m_pa_id}}]" value="{!! $pilihan_b !!}" required="">
                                                <label for="[{{$pa->m_pa_id.'_radioPrimary3'}}]">3
                                                </label>
                                            </div>
                                            <div class="icheck-primary d-inline">
                                                <input type="radio" id="[{{$pa->m_pa_id.'_radioPrimary2'}}]" name="pilihan[{{$pa->m_pa_id}}]" value="{!! $pilihan_c !!}"  required="">
                                                <label for="[{{$pa->m_pa_id.'_radioPrimary2'}}]">2
                                                </label>
                                            </div>
                                            <div class="icheck-primary d-inline">
                                                <input type="radio" id="[{{$pa->m_pa_id.'_radioPrimary1'}}]" name="pilihan[{{$pa->m_pa_id}}]" value="{!! $pilihan_d !!}"  required="">
                                                <label for="[{{$pa->m_pa_id.'_radioPrimary1'}}]">1
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            {!! $pa->sub2 !!}
                                        </div>
                                    </div>
                                </div>
                                <hr>
                            @endforeach
                        </table>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <!-- text input -->
                            <p><b>Catatan Khusus Karyawan</b></p>
                            <p><b>Rekomendasi Status Kerja* :</b></p>
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="icheck-primary d-inline">
                                        <input type="radio" id="rekomendasi1" name="rekomendasi" value="Putus Hubungan Kerja">
                                        <label for="rekomendasi1"> Putus Hubungan Kerja </label>
                                    </div>
                                    <div class="icheck-primary d-inline">
                                        <input type="radio" id="rekomendasi2" name="rekomendasi" value="Perpanjangan Kontrak">
                                        <label for="rekomendasi2"> Perpanjangan Kontrak </label>
                                    </div>
                                    <div class="icheck-primary d-inline">
                                        <input type="radio" id="rekomendasi3" name="rekomendasi" value="Lainnya">
                                        <label for="rekomendasi3"> Lainnya... </label>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label for="deskripsi">Keterangan :</label>
                                        <input type="text" id="deskripsi" name="deskripsi" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <hr>
                        </div>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <a href="{!! route('fe.pa') !!}" class="btn btn-default pull-left"><span class="fa fa-times"></span> Kembali</a>
                        <button type="submit"  class="pull-right btn btn-theme button-1 text-white ctm-border-radius p-2 add-person ctm-btn-padding"><span class="fa fa-check"></span> Simpan</button>
                    </div>
                    <!-- /.box-footer -->
                </form>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
        <!-- /.card -->
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
@endsection
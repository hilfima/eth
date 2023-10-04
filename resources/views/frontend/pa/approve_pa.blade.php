@extends('layouts.app2')

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content">
    @include('flash-message')
	<div class="row">
	<div class="col-xl-3 col-lg-4 col-md-12 theiaStickySidebar">
		<?= view('layouts.app_side'); ?>
	</div>
	<div class="col-xl-9 col-lg-8 col-md-12">

        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark">Penilaian Kinerja</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{!! route('home') !!}">Home</a></li>
                            <li class="breadcrumb-item active"><a href="{!! route('fe.pa') !!}">Penilaian Kinerja</a></li>
                            <li class="breadcrumb-item active">Approve Penilaian Kinerja</li>
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
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <!-- form start -->
                <form class="form-horizontal" method="POST" action="{!! route('fe.save_approve_pa',$pa[0]->m_pa_jawaban_id) !!}" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="row">
                        <div class="col-sm-6">
                            <!-- text input -->
                            <div class="form-group">
                                <label>Nama</label>
                                <select class="form-control select2" name="nama" style="width: 100%;" required disabled>
                                    <option value="">Pilih Karyawan</option>
                                    <?php
                                    foreach($karyawan AS $karyawan){
                                        if($karyawan->p_karyawan_id==$pa[0]->p_karyawan_id){
                                            echo '<option selected="selected" value="'.$karyawan->p_karyawan_id.'">'.$karyawan->nama.'</option>';
                                        }
                                        else{
                                            echo '<option value="'.$karyawan->p_karyawan_id.'">'.$karyawan->nama.'</option>';
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Penilai</label>
                                <select class="form-control select2" name="manager" style="width: 100%;" required disabled>
                                    <option value="">Pilih Nama</option>
                                    <?php
                                    foreach($manager AS $manager){
                                        if($manager->p_karyawan_id==$pa[0]->penilai){
                                            echo '<option selected="selected" value="'.$manager->p_karyawan_id.'">'.$manager->nama.'</option>';
                                        }
                                        else{
                                            echo '<option value="'.$manager->p_karyawan_id.'">'.$manager->nama.'</option>';
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Tanggal</label>
                                <div class="input-group date" id="reservationdate" data-target-input="nearest" disabled>
                                    <input type="text" class="form-control datetimepicker-input" id="tanggal" name="tanggal" data-target="#reservationdate" required value="{!! date('d-m-Y',strtotime($pa[0]->tanggal)) !!}" readonly/>
                                    <div class="input-group-append" data-target="#reservationdate" data-toggle="datetimepicker">
                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Approved</label>
                                <select class="form-control select2" name="direktur" style="width: 100%;" required disabled>
                                    <option value="">Pilih Approved</option>
                                    <?php
                                    foreach($direktur AS $direktur){
                                        if($direktur->p_karyawan_id==$pa[0]->approve){
                                            echo '<option selected="selected" value="'.$direktur->p_karyawan_id.'">'.$direktur->nama.'</option>';
                                        }
                                        else{
                                            echo '<option value="'.$direktur->p_karyawan_id.'">'.$direktur->nama.'</option>';
                                        }
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
                            @foreach($padetil as $padetil)
                                <?php
                                $id=$padetil->m_pa_id;
                                $pertanyaan=$padetil->pertanyaan;
                                $pertanyaan=$padetil->sub1;
                                $pertanyaan=$padetil->sub2;
                                $pilihan_a=4;
                                $pilihan_b=3;
                                $pilihan_c=2;
                                $pilihan_d=1;
                                ?>
                                <p style="text-align: center"><strong>{{ $padetil->nama }}</strong></p>
                                <p style="text-align: center"><strong>{{ $padetil->pertanyaan }}</strong></p>
                                <input type="hidden" id="jumlah" name="jumlah" value="{{ $jumlah }}" >
                                <input type="hidden" id="id_soal" name="id_soal[]" value="{{ $padetil->m_pa_id }}" >
                                <div class="form-group clearfix">
                                    <div class="row">
                                        <div class="col-lg-4">
                                            {!! $padetil->sub1 !!}
                                        </div>
                                        <div class="col-lg-4" style="text-align: center">
                                            <div class="icheck-primary d-inline">
                                                <input type="radio" id="[{{$padetil->m_pa_id.'_radioPrimary4'}}]" name="pilihan[{{$padetil->m_pa_id}}]" value="{!! $pilihan_a !!}"
                                                       @if($padetil->jawaban==4)
                                                       checked
                                                        @endif
                                                       disabled
                                                >
                                                <label for="[{{$padetil->m_pa_id.'_radioPrimary4'}}]">4
                                                </label>
                                            </div>
                                            <div class="icheck-primary d-inline">
                                                <input type="radio" id="[{{$padetil->m_pa_id.'_radioPrimary3'}}]" name="pilihan[{{$padetil->m_pa_id}}]" value="{!! $pilihan_b !!}"
                                                       @if($padetil->jawaban==3)
                                                       checked
                                                        @endif
                                                       disabled
                                                >
                                                <label for="[{{$padetil->m_pa_id.'_radioPrimary3'}}]">3
                                                </label>
                                            </div>
                                            <div class="icheck-primary d-inline">
                                                <input type="radio" id="[{{$padetil->m_pa_id.'_radioPrimary2'}}]" name="pilihan[{{$padetil->m_pa_id}}]" value="{!! $pilihan_c !!}"
                                                       @if($padetil->jawaban==2)
                                                       checked
                                                        @endif
                                                       disabled
                                                >
                                                <label for="[{{$padetil->m_pa_id.'_radioPrimary2'}}]">2
                                                </label>
                                            </div>
                                            <div class="icheck-primary d-inline">
                                                <input type="radio" id="[{{$padetil->m_pa_id.'_radioPrimary1'}}]" name="pilihan[{{$padetil->m_pa_id}}]" value="{!! $pilihan_d !!}"
                                                       @if($padetil->jawaban==1)
                                                       checked
                                                        @endif
                                                       disabled
                                                >
                                                <label for="[{{$padetil->m_pa_id.'_radioPrimary1'}}]">1
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            {!! $padetil->sub2 !!}
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
                                        <input type="radio" id="rekomendasi1" name="rekomendasi" value="Putus Hubungan Kerja"
                                            @if($pa[0]->rekomendasi=='Putus Hubungan Kerja')
                                                checked
                                            @endif
                                               disabled
                                        >
                                        <label for="rekomendasi1"> Putus Hubungan Kerja </label>
                                    </div>
                                    <div class="icheck-primary d-inline">
                                        <input type="radio" id="rekomendasi2" name="rekomendasi" value="Perpanjangan Kontrak"
                                               @if($pa[0]->rekomendasi=='Perpanjangan Kontrak')
                                               checked
                                                @endif
                                               disabled
                                        >
                                        <label for="rekomendasi2"> Perpanjangan Kontrak </label>
                                    </div>
                                    <div class="icheck-primary d-inline">
                                        <input type="radio" id="rekomendasi3" name="rekomendasi" value="Lainnya"
                                               @if($pa[0]->rekomendasi=='Lainnya')
                                               checked
                                                @endif
                                               disabled
                                        >
                                        <label for="rekomendasi3"> Lainnya... </label>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label for="deskripsi">Keterangan :</label>
                                        <input type="text" id="deskripsi" name="deskripsi" class="form-control" value="{!! $pa[0]->keterangan !!}" readonly>
                                    </div>

                                    <div class="form-group">
                                        <label for="status">Status :</label>
                                        <select class="form-control select2" name="status" style="width: 100%;" required>
                                            <option value="0">Pending</option>
                                            <option value="1">Approve</option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="deskripsi_dir">Keterangan Direktur :</label>
                                        <input type="text" id="deskripsi_dir" name="deskripsi_dir" class="form-control" value="{!! $pa[0]->keterangan_direktur !!}" required>
                                    </div>
                                </div>
                            </div>
                            <hr>
                        </div>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <a href="{!! route('fe.pa') !!}" class="btn btn-default pull-left"><span class="fa fa-times"></span> Kembali</a>
                        <button type="submit" class="btn btn-info pull-right"><span class="fa fa-check"></span> Approve</button>
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
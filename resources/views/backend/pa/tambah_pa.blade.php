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
                        <h1 class="m-0 text-dark">Penilaian Kinerja</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{!! route('admin') !!}">Admin</a></li>
                            <li class="breadcrumb-item active"><a href="{!! route('be.pa') !!}">Penilaian Kinerja</a></li>
                            <li class="breadcrumb-item active">Tambah Penilaian Kinerja</li>
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
                <form class="form-horizontal" method="POST" action="{!! route('be.simpan_pa') !!}" enctype="multipart/form-data">
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
                                    <option value="">Pilih Nama</option>
                                    <?php
                                    foreach($manager AS $manager){
                                        echo '<option value="'.$manager->p_karyawan_id.'">'.$manager->nama.'</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Tanggal</label>
                                <div class="input-group " id="reservationdate" data-target-input="nearest">
                                    <input type="date" class="form-control" id="tanggal" name="tanggal" value="<?=date('Y-m-d');?>" required/>
                                   
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
                        <table id="example1" class="table table-striped custom-table mb-0">
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
                                                <input type="radio" id="[{{$pa->m_pa_id.'_radioPrimary4'}}]" name="pilihan[{{$pa->m_pa_id}}]" value="{!! $pilihan_a !!}">
                                                <label for="[{{$pa->m_pa_id.'_radioPrimary4'}}]">4
                                                </label>
                                            </div>
                                            <div class="icheck-primary d-inline">
                                                <input type="radio" id="[{{$pa->m_pa_id.'_radioPrimary3'}}]" name="pilihan[{{$pa->m_pa_id}}]" value="{!! $pilihan_b !!}">
                                                <label for="[{{$pa->m_pa_id.'_radioPrimary3'}}]">3
                                                </label>
                                            </div>
                                            <div class="icheck-primary d-inline">
                                                <input type="radio" id="[{{$pa->m_pa_id.'_radioPrimary2'}}]" name="pilihan[{{$pa->m_pa_id}}]" value="{!! $pilihan_c !!}">
                                                <label for="[{{$pa->m_pa_id.'_radioPrimary2'}}]">2
                                                </label>
                                            </div>
                                            <div class="icheck-primary d-inline">
                                                <input type="radio" id="[{{$pa->m_pa_id.'_radioPrimary1'}}]" name="pilihan[{{$pa->m_pa_id}}]" value="{!! $pilihan_d !!}">
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
                        <a href="{!! route('be.pa') !!}" class="btn btn-default pull-left"><span class="fa fa-times"></span> Kembali</a>
                        <button type="submit" class="btn btn-info pull-right"><span class="fa fa-check"></span> Simpan</button>
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
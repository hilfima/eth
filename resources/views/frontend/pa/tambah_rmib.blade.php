@extends('layouts.app2')

@section('content')
	<div class="row">
	<div class="col-xl-3 col-lg-4 col-md-12 theiaStickySidebar">
		<?= view('layouts.app_side', compact('help')); ?>
	</div>
	<div class="col-xl-9 col-lg-8 col-md-12">

    <!-- Content Wrapper. Contains page content -->
    <div class="content">
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
                            <li class="breadcrumb-item"><a href="{!! route('home') !!}">Home</a></li>
                            <li class="breadcrumb-item">Performance & Test</li>
                            <li class="breadcrumb-item">RMIB Grup {!! $grup !!}</li>
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
                <form class="form-horizontal" method="POST" action="{!! route('fe.simpan_rmib') !!}" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="row">
                        <div class="col-sm-3">
                            <!-- text input -->
                            <div class="form-group">
                                <label>NIK</label>
                                <input type="text" id="nik" name="nik" value="{!! $datakar[0]->nik !!}" class="form-control" readonly>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <!-- text input -->
                            <div class="form-group">
                                <label>Nama</label>
                                <input type="text" id="nama" name="nama" value="{!! $datakar[0]->nama_lengkap !!}" class="form-control" readonly>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <!-- text input -->
                            <div class="form-group">
                                <label>Umur Kerja</label>
                                <input type="text" id="umur" name="umur" value="{!! $datakar[0]->umur_kerja !!}" class="form-control" readonly>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <!-- text input -->
                            <div class="form-group">
                                <label>Tanggal</label>
                                <input type="text" id="tanggal" name="tanggal" value="{!! date('d-m-Y') !!}" class="form-control" readonly>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <p style="text-align:center"><b>PETUNJUK</b></p>
                    <p style="text-align:justify">Dibawah ini anda akan menemui daftar-daftar berbagai macam pekerjaan yang tersusun dalam berbagai kelompok. Setiap kelompok terdiri dari 12 macam pekerjaan. Setiap kelompok terdiri dari 12 khusus yang memerlukan latihan atau pendidikan keahlian sendiri. Mungkin hanya beberapa diantaranya yang anda sukai. Disini anda diminta untuk memilih pekerjaan mana yang ingin anda lakukan atau pekerjaaan yang anda sukai, terlepas dari besarnya upah gaji yang akan anda terima. Juga terlepas apakah anda berhasil atau tidak dalam mengerjakan pekerjaan tersebut.</p>
                    <p style="text-align:justify">Tugas anda adalah mencantumkan nomor atau angka pada tiap pekerjaan dalam kelompok-kelompok yang tersedia. Berikanlah nomor (angka) 1 untuk pekerjaan yang paling anda sukai diantara ke 12 pekerjaan yang tersedia pada setiap kelompok, dan dilanjutkan dengan pemberian nomor 2, 3, dan seterusnya berurutan berdasarkan besarnya kadar kesukaan/minat anda terhadap pekerjaan itu, dan nomor/angka 12 anda cantumkan untuk pekerjaan yang paling tidak disukai dari daftar pekerjaan yang tersedia pada kelompok-kelompok tersebut.</p>
                    <p style="text-align:justify">Bekerjalah secepatnya dan tulislah nomor-nomor (angka-angka) sesuai dengan kesan dan keinginan anda yang pertama muncul</p>
                    <hr>
                    <div class="card-body">
                        <table id="example1" class="table table-bordered table-striped">
                            <tbody>
                            <?php $no=0 ?>
                                @if(!empty($datarmib))
                                    @foreach($datarmib as $rmib)
                                        <?php $no++; ?>
                                        <input type="hidden" id="jumlah" name="jumlah" value="{{ $jumlah }}" >
                                        <input type="hidden" id="id_soal" name="id_soal[]" value="{{ $rmib->m_rmib_id }}" >
                                        <div class="form-group clearfix">
                                            <div class="row">
                                                <div class="col-lg-4">
                                                    {!! $no.'. ' !!}
                                                    {!! $rmib->nama !!}
                                                </div>
                                                <div class="col-lg-4">
                                                    {!! $rmib->grup !!}
                                                </div>
                                                <div class="col-lg-4" style="text-align: center">
                                                    <input type="text" id="pilihan[{{$rmib->m_rmib_id}}]" name="pilihan[{{$rmib->m_rmib_id}}]" value="" required>
                                                </div>
                                            </div>
                                            <?php if($no==12){?>
                                                <hr>
                                            <?php } ?>
                                        </div>
                                        <?php if($no==12){
                                            $no=0;
                                        } ?>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                    <!--<div class="row">
                        <div class="col-sm-12">
                            <p>Tulislah dibawah ini tiga (3) macam pekerjaan yang paling ingin anda lakukan atau yang paling anda sukai (tidak harus pekerjaan yang tercantum di dalam daftar yangg ada) :</p>
                            <input type="text" id="alt1" name="alt1" class="form-control" placeholder="(1)" required>
                            <input type="text" id="alt2" name="alt2" class="form-control" placeholder="(2)" required>
                            <input type="text" id="alt3" name="alt3" class="form-control" placeholder="(3)" required>
                            <input type="hidden" id="idkar" nama="idkar" value="{!! $datakar[0]->p_karyawan_id !!}" class="form-control">
                            <hr>
                        </div>
                    </div>-->
                    <input type="hidden" id="idkar" name="idkar" value="{!! $datakar[0]->p_karyawan_id !!}" class="form-control">
                    <input type="hidden" id="grup" name="grup" value="{!! $grup !!}" class="form-control">
                    <input type="hidden" id="idrmib" name="idrmib" value="{!! $idrmib !!}" class="form-control">
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <!--<a href="{!! route('fe.ptest') !!}" class="btn btn-default pull-left"><span class="fa fa-times"></span> Kembali</a>-->
                        <button type="submit" class="btn btn-info pull-right"><span class="fa fa-forward"></span> Selanjutnya</button>
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
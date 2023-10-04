@extends('layouts.appsA')

<style>
    .trr {
        background-color: #0099FF;
        color: #ffffff;
        align: center;
        padding: 10px;
        height: 20px;
    }
    td {
        border: 1px solid #040404;
    }
    tr.odd > td {
        background-color: #E3F2FD;
    }

    tr.even > td {
        background-color: #BBDEFB;
    }
</style>

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
    @include('flash-message')
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark">Rekap Absen</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{!! route('admin') !!}">Admin</a></li>
                            <li class="breadcrumb-item active">Rekap Absen</li>
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
                <form id="cari_absen" class="form-horizontal" method="get" action="{!! route('be.cari_absen') !!}">
                    {{ csrf_field() }}
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Periode Absen</label>
                                <select class="form-control select2" name="periode_gajian" style="width: 100%;" required>
                                    <option value="">Pilih Periode</option>
                                    <?php
                                    foreach($periode AS $periode){
                                        if($periode->periode_absen_id==$periode_absen){
                                            echo '<option selected="selected" value="'.$periode->periode_absen_id.'">'.$periode->bulan.' - '.$periode->tahun.' - '.$periode->tipe.' - '.$periode->tgl_awal.' - '.$periode->tgl_akhir.'</option>';
                                        }
                                        else{
                                            echo '<option value="'.$periode->periode_absen_id.'">'.$periode->bulan.' - '.$periode->tahun.' - '.$periode->tipe.' - '.$periode->tgl_awal.' - '.$periode->tgl_akhir.'</option>';
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-12">
                            <a href="{!! route('be.rekap_absen') !!}" class="btn btn-primary"><span class="fa fa-sync"></span> Reset</a>
                            <button type="submit" name="Cari" class="btn btn-primary" value="Cari"><span class="fa fa-search"></span> Cari</button>
                            <button type="submit" name="Cari" class="btn btn-primary" value="RekapExcel"><span class="fa fa-file-excel"></span> Excel</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th>No.</th>
                        <th>NIK</th>
                        <th>Nama</th>
                        <th>Departemen</th>
                        <th>{!! $days1 !!}</th>
                        <th>{!! $days2 !!}</th>
                        <th>{!! $days3 !!}</th>
                        <th>{!! $days4 !!}</th>
                        <th>{!! $days5 !!}</th>
                        <th>{!! $days6 !!}</th>
                        <th>{!! $days7 !!}</th>
                        <th>{!! $days8 !!}</th>
                        <th>{!! $days9 !!}</th>
                        <th>{!! $days10 !!}</th>
                        <th>{!! $days11 !!}</th>
                        <th>{!! $days12 !!}</th>
                        <th>{!! $days13 !!}</th>
                        <th>{!! $days14 !!}</th>
                        <th>{!! $days15 !!}</th>
                        <th>{!! $days16 !!}</th>
                        <th>{!! $days17 !!}</th>
                        <th>{!! $days18 !!}</th>
                        <th>{!! $days19 !!}</th>
                        <th>{!! $days20 !!}</th>
                        <th>{!! $days21 !!}</th>
                        <th>{!! $days22 !!}</th>
                        <th>{!! $days23 !!}</th>
                        <th>{!! $days24 !!}</th>
                        <th>{!! $days25 !!}</th>
                        <th>{!! $days26 !!}</th>
                        <th>{!! $days27 !!}</th>
                        <th>{!! $days28 !!}</th>
                        <th>{!! $days29 !!}</th>
                        <th>{!! $days30 !!}</th>
                        <th>{!! $days31 !!}</th>
                        <th>Absen Masuk</th>
                        <th>Cuti</th>
                        <th>IPG</th>
                        <th>IHK</th>
                        <th>Total</th>
                        <th>Terlambat</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $no=0 ?>
                    @if(!empty($rekapabsen))
                        @foreach($rekapabsen as $rekapabsen)
                            <?php $no++ ?>
                            <tr>
                                <td>{!! $no !!}</td>
                                <td>{!! $rekapabsen->nik !!}</td>
                                <td>{!! $rekapabsen->nama !!}</td>
                                <td>{!! $rekapabsen->departemen !!}</td>
                                @if(substr($rekapabsen->a,0,5)>'07:30')
                                    <td style="background-color: red">{!! $rekapabsen->a !!}</td>
                                @else
                                    <td class="{{substr($rekapabsen->a,0,5)>'07:30' ? "even": "odd"}}">{!! $rekapabsen->a !!}</td>
                                @endif
                                @if(substr($rekapabsen->b,0,5)>'07:30')
                                    <td style="background-color: red">{!! $rekapabsen->b !!}</td>
                                @else
                                    <td class="{{substr($rekapabsen->b,0,5)>'07:30' ? "even": "odd"}}">{!! $rekapabsen->b !!}</td>
                                @endif
                                @if(substr($rekapabsen->c,0,5)>'07:30')
                                    <td style="background-color: red">{!! $rekapabsen->c !!}</td>
                                @else
                                    <td class="{{substr($rekapabsen->c,0,5)>'07:30' ? "even": "odd"}}">{!! $rekapabsen->c !!}</td>
                                @endif
                                @if(substr($rekapabsen->d,0,5)>'07:30')
                                    <td style="background-color: red">{!! $rekapabsen->d !!}</td>
                                @else
                                    <td>{!! $rekapabsen->d !!}</td>
                                @endif
                                @if(substr($rekapabsen->e,0,5)>'07:30')
                                    <td style="background-color: red">{!! $rekapabsen->e !!}</td>
                                @else
                                    <td>{!! $rekapabsen->e !!}</td>
                                @endif
                                @if(substr($rekapabsen->f,0,5)>'07:30')
                                    <td style="background-color: red">{!! $rekapabsen->f !!}</td>
                                @else
                                    <td>{!! $rekapabsen->f !!}</td>
                                @endif
                                @if(substr($rekapabsen->g,0,5)>'07:30')
                                    <td style="background-color: red">{!! $rekapabsen->g !!}</td>
                                @else
                                    <td>{!! $rekapabsen->g !!}</td>
                                @endif
                                @if(substr($rekapabsen->h,0,5)>'07:30')
                                    <td style="background-color: red">{!! $rekapabsen->h !!}</td>
                                @else
                                    <td>{!! $rekapabsen->h !!}</td>
                                @endif
                                @if(substr($rekapabsen->i,0,5)>'07:30')
                                    <td style="background-color: red">{!! $rekapabsen->i !!}</td>
                                @else
                                    <td>{!! $rekapabsen->i !!}</td>
                                @endif
                                @if(substr($rekapabsen->j,0,5)>'07:30')
                                    <td style="background-color: red">{!! $rekapabsen->j !!}</td>
                                @else
                                    <td>{!! $rekapabsen->j !!}</td>
                                @endif
                                @if(substr($rekapabsen->k,0,5)>'07:30')
                                    <td style="background-color: red">{!! $rekapabsen->k !!}</td>
                                @else
                                    <td>{!! $rekapabsen->k !!}</td>
                                @endif
                                @if(substr($rekapabsen->l,0,5)>'07:30')
                                    <td style="background-color: red">{!! $rekapabsen->l !!}</td>
                                @else
                                    <td>{!! $rekapabsen->l !!}</td>
                                @endif
                                @if(substr($rekapabsen->m,0,5)>'07:30')
                                    <td style="background-color: red">{!! $rekapabsen->m !!}</td>
                                @else
                                    <td>{!! $rekapabsen->m !!}</td>
                                @endif
                                @if(substr($rekapabsen->n,0,5)>'07:30')
                                    <td style="background-color: red">{!! $rekapabsen->n !!}</td>
                                @else
                                    <td>{!! $rekapabsen->n !!}</td>
                                @endif
                                @if(substr($rekapabsen->o,0,5)>'07:30')
                                    <td style="background-color: red">{!! $rekapabsen->o !!}</td>
                                @else
                                    <td>{!! $rekapabsen->o !!}</td>
                                @endif
                                @if(substr($rekapabsen->p,0,5)>'07:30')
                                    <td style="background-color: red">{!! $rekapabsen->p !!}</td>
                                @else
                                    <td>{!! $rekapabsen->p !!}</td>
                                @endif
                                @if(substr($rekapabsen->q,0,5)>'07:30')
                                    <td style="background-color: red">{!! $rekapabsen->q !!}</td>
                                @else
                                    <td>{!! $rekapabsen->q !!}</td>
                                @endif
                                @if(substr($rekapabsen->r,0,5)>'07:30')
                                    <td style="background-color: red">{!! $rekapabsen->r !!}</td>
                                @else
                                    <td>{!! $rekapabsen->r !!}</td>
                                @endif
                                @if(substr($rekapabsen->s,0,5)>'07:30')
                                    <td style="background-color: red">{!! $rekapabsen->s !!}</td>
                                @else
                                    <td>{!! $rekapabsen->s !!}</td>
                                @endif
                                @if(substr($rekapabsen->t,0,5)>'07:30')
                                    <td style="background-color: red">{!! $rekapabsen->t !!}</td>
                                @else
                                    <td>{!! $rekapabsen->t !!}</td>
                                @endif
                                @if(substr($rekapabsen->u,0,5)>'07:30')
                                    <td style="background-color: red">{!! $rekapabsen->u !!}</td>
                                @else
                                    <td>{!! $rekapabsen->u !!}</td>
                                @endif
                                @if(substr($rekapabsen->v,0,5)>'07:30')
                                    <td style="background-color: red">{!! $rekapabsen->v !!}</td>
                                @else
                                    <td>{!! $rekapabsen->v !!}</td>
                                @endif
                                @if(substr($rekapabsen->w,0,5)>'07:30')
                                    <td style="background-color: red">{!! $rekapabsen->w !!}</td>
                                @else
                                    <td>{!! $rekapabsen->w !!}</td>
                                @endif
                                @if(substr($rekapabsen->x,0,5)>'07:30')
                                    <td style="background-color: red">{!! $rekapabsen->x !!}</td>
                                @else
                                    <td>{!! $rekapabsen->x !!}</td>
                                @endif
                                @if(substr($rekapabsen->y,0,5)>'07:30')
                                    <td style="background-color: red">{!! $rekapabsen->y !!}</td>
                                @else
                                    <td>{!! $rekapabsen->y !!}</td>
                                @endif
                                @if(substr($rekapabsen->z,0,5)>'07:30')
                                    <td style="background-color: red">{!! $rekapabsen->z !!}</td>
                                @else
                                    <td>{!! $rekapabsen->z !!}</td>
                                @endif
                                @if(substr($rekapabsen->aa,0,5)>'07:30')
                                    <td style="background-color: red">{!! $rekapabsen->aa !!}</td>
                                @else
                                    <td>{!! $rekapabsen->aa !!}</td>
                                @endif
                                @if(substr($rekapabsen->ab,0,5)>'07:30')
                                    <td style="background-color: red">{!! $rekapabsen->ab !!}</td>
                                @else
                                    <td>{!! $rekapabsen->ab !!}</td>
                                @endif
                                @if(substr($rekapabsen->ac,0,5)>'07:30')
                                    <td style="background-color: red">{!! $rekapabsen->ac !!}</td>
                                @else
                                    <td>{!! $rekapabsen->ac !!}</td>
                                @endif
                                @if(substr($rekapabsen->ad,0,5)>'07:30')
                                    <td style="background-color: red">{!! $rekapabsen->ad !!}</td>
                                @else
                                    <td>{!! $rekapabsen->ad !!}</td>
                                @endif
                                @if(substr($rekapabsen->ae,0,5)>'07:30')
                                    <td style="background-color: red">{!! $rekapabsen->ae !!}</td>
                                @else
                                    <td>{!! $rekapabsen->ae !!}</td>
                                @endif
                                <td>{!! $rekapabsen->masuk !!}</td>
                                <td>{!! $rekapabsen->cuti !!}</td>
                                <td>{!! $rekapabsen->ipg !!}</td>
                                <td>{!! $rekapabsen->izin !!}</td>
                                <td>{!! $rekapabsen->masuk+$rekapabsen->cuti+$rekapabsen->ipg+$rekapabsen->izin !!}</td>
                                <td>{!! $rekapabsen->terlambat !!}</td>
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

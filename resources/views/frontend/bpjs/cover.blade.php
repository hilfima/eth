@extends('layouts.app_fe')

@section('content')
    <!-- Content Wrapper. Contains page content -->

<div class="row">
	<div class="col-xl-3 col-lg-4 col-md-12 theiaStickySidebar">
		<?= view('layouts.app_side'); ?>
	</div>
	<div class="col-xl-9 col-lg-8 col-md-12">

			
    <div class="content">
    <div class="content-wrapper">
    @include('flash-message')
        <!-- Content Header (Page header) -->
        <div class="card shadow-sm ctm-border-radius">
<div class="card-body align-center ">
    <?php 
    echo str_ireplace('contenteditable="true"',"",$page[0]->html);
    ?>
</div>
</div>

<div class="card shadow-sm ctm-border-radius">
<div class="card-body align-center">
<h4 class="card-title float-left mb-0 mt-2">Penambahan Anggota Keluarga BPJS Kesehatan</h4>

</div>
</div>

        <!-- /.content-header -->

        <!-- Main content -->
        <div class="card">
            <div class="card-header">
                <!--<h3 class="card-title">DataTable with default features</h3>-->
                
                <a href="{!! route('fe.tambah_cover_bpjs') !!}" title='Tambah' data-toggle='tooltip'><span class='fa fa-plus'></span> Tambah</a>
              
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <table id="example1" class="table table-striped custom-table mb-0">
                    <thead>
                    <tr>
                        <th>No.</th>
                        <th>Nama</th>
                        <th>Nama Pengaju </th>
                        <th>Hubungan </th>
                        <th>Alamat </th>
                        <th>NIK </th>
                        <th>Tanggal Lahir </th>
                        <th>File kk</th>
                        <th>File ktp</th>
                        <th>File bpjs karyawan</th>
                        <th>File bpjs induk</th>
                        
                    </tr>
                    </thead>
                    <tbody>
                    <?php $no=0 ?>
                    @if(!empty($bpjs))
                        @foreach($bpjs as $bpjs)
                            <?php $no++ ?>
                            <tr>
                                <td>{!! $no !!}</td>
                                <td>{!! $bpjs->nama !!}</td>
                                
                                <td>{!! $bpjs->hubungan !!}</td>
                                <td>{!! $bpjs->hubungan !!}</td>
                                <td>{!! $bpjs->alamat !!}</td>
                                <td>{!! $bpjs->nik !!}</td>
                                <td>{!! $bpjs->tanggal_lahir !!}</td>
                                @if(!empty($bpjs->file_kk))
                                    <td style="text-align: center"><a href="{!! asset('dist/img/cover/'.$bpjs->file_kk) !!}" target="_blank" title="Download"><span class="fa fa-download"></span></a></td>
                                @else
                                    <td></td>
                                @endif
                                @if(!empty($bpjs->file_ktp))
                                    <td style="text-align: center"><a href="{!! asset('dist/img/cover/'.$bpjs->file_ktp) !!}" target="_blank" title="Download"><span class="fa fa-download"></span></a></td>
                                @else
                                    <td></td>
                                @endif
                                @if(!empty($bpjs->file_bpjs_karyawan))
                                    <td style="text-align: center"><a href="{!! asset('dist/img/cover/'.$bpjs->file_bpjs_karyawan) !!}" target="_blank" title="Download"><span class="fa fa-download"></span></a></td>
                                @else
                                    <td></td>
                                @endif
                                @if(!empty($bpjs->file_bpjs_induk))
                                    <td style="text-align: center"><a href="{!! asset('dist/img/cover/'.$bpjs->file_bpjs_induk) !!}" target="_blank" title="Download"><span class="fa fa-download"></span></a></td>
                                @else
                                    <td></td>
                                @endif
                               
                                
                                
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
 </div>   
 </div>   
 <!-- /.content-wrapper -->
@endsection

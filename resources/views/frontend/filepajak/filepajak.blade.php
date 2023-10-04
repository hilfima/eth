@extends('layouts.app_fe')

@section('content')
    <!-- Content Wrapper. Contains page content -->

			
    <div class="content">
    <div class="content-wrapper">
    @include('flash-message')
	<div class="row">
	<div class="col-xl-3 col-lg-4 col-md-12 theiaStickySidebar">
		<?= view('layouts.app_side'); ?>
	</div>
	<div class="col-xl-9 col-lg-8 col-md-12">

        <!-- Content Header (Page header) -->
        <div class="card shadow-sm ctm-border-radius">
<div class="card-body align-center">
<h4 class="card-title float-left mb-0 mt-2">Bukti Potongan Pajak</h4>

</div>
</div>

        <!-- /.content-header -->

        <!-- Main content -->
        <div class="card">
            
            <!-- /.card-header -->
            <div class="card-body">
                 <table id="example1" class="table table-striped custom-table mb-0">
                    <thead>
                    <tr>
                        <th>No.</th>
                      
                        <th>Nama Karyawan</th>
                        <th>Periode Bulan</th>
                        
                        <th>File</th>
                        
                    </tr>
                    </thead>
                    <tbody>
                    <?php $no=0 ?>
                    @if(!empty($filepajak))
                        @foreach($filepajak as $filepajak)
                            <?php $no++ ?>
                            <tr>
                                <td>{!! $no !!}</td>
                                <td>{!! $filepajak->nama !!}</td>
                                <td>{!! $filepajak->tahun !!}</td>
                                 @if(!empty($filepajak->file))
                                    <td style="text-align: center"><a href="{!! asset('dist/img/file/'.$filepajak->file) !!}" target="_blank" title="Download"><span class="fa fa-download"></span></a></td>
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

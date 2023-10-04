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
                        <h1 class="m-0 text-dark">Kebijakan dan Prosedur</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{!! route('admin') !!}">Admin</a></li>
                            <li class="breadcrumb-item active">Kebijakan dan Prosedur</li>
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
                <a href="{!! route('be.tambah_sop') !!}" title='Tambah' data-toggle='tooltip'><span class='fa fa-plus'></span> Kebijakan dan Prosedur </a>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <table id="example1" class="table table-striped custom-table mb-0">
                    <thead>
                    <tr>
                        <th>No.</th>
                        <th>Judul </th>
                        <th>Departemen </th>
                        <th>File </th>
                        <th>Action </th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $no=0 ?>
                    @if(!empty($sop))
                        @foreach($sop as $sop)
                            <?php $no++;
                            $dept=DB::connection()->select("select m_departemen.nama from sop_dept join m_departemen on sop_dept.m_departement_id = m_departemen.m_departemen_id where sop_id = ".$sop->sop_id);
							
$list_dept = '';
$nod=0;
$count=count($dept);
foreach($dept as $dept){
$nod++;
	$list_dept .= $dept->nama;
	if($nod !=$count)
	$list_dept .= ',';
	
}
                            ?>
                            <tr>
                                <td>{!! $no !!}</td>
                                <td>{!! $sop->judul_sop !!}</td>
                                <td>{!! $list_dept !!}</td>
								@if(!empty($sop->file))
								
								<td style="text-align: center">
								
									<a href="<?=url('/dist/img/file/'. $sop->file );?>" target="_blank" title="Download"><span class="fa fa-download"></span></a></td>
								@else
								<td></td>
								@endif
                                <td style="text-align: center;">
                                    <a href="{!! route('be.reader_sop',$sop->sop_id) !!}" title='Reader' data-toggle='tooltip'><span class="fa fa-book" aria-hidden="true"></span>
</a>
                                    <a href="{!! route('be.edit_sop',$sop->sop_id) !!}" title='Ubah' data-toggle='tooltip'><span class='fa fa-edit'></span></a>
                                    <a href="{!! route('be.hapus_sop',$sop->sop_id) !!}" title='Hapus' data-toggle='tooltip'><span class='fa fa-trash'></span></a>
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

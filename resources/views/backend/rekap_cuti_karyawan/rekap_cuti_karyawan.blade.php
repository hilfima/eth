@extends('layouts.appsA')



@section('content')
<style>
	html {
		box-sizing: border-box;
	}
	*,
	*:before,
	*:after {
		box-sizing: inherit;
	}
	.intro {
		max-width: 1280px;
		margin: 1em auto;
	}
	.table-scroll {
		position: relative;
		width: 100%;
		z-index: 1;
		margin: auto;
		overflow: auto;
		height: 655px;
	}
	.table-scroll table {
		width: 100%;
		min-width: 1280px;
		margin: auto;
		border-collapse: separate;
		border-spacing: 0;
	}
	.table-wrap {
		position: relative;
	}
	.table-scroll th,
	.table-scroll td {
		padding: 5px 10px;
		border: 1px solid #000;
		background: #fff;
		vertical-align: top;
	}
	.table-scroll thead th {
		background: #333;
		color: #fff;
		position: -webkit-sticky;
		position: sticky;
		top: 0;
	}
	/* safari and ios need the tfoot itself to be position:sticky also */
	.table-scroll tfoot,
	.table-scroll tfoot th,
	.table-scroll tfoot td {
		position: -webkit-sticky;
		position: sticky;
		bottom: 0;
		background: #666;
		color: #fff;
		z-index: 4;
	}

	th:first-child {
		position: -webkit-sticky;
		position: sticky;
		left: 0;
		z-index: 2;
		background: #ccc;
	}
	thead th:first-child,
	tfoot th:first-child {
		z-index: 5;
	}

</style>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	@include('flash-message')
	<!-- Content Header (Page header) -->
	<div class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6">
					<h1 class="m-0 text-dark">Rekap Cuti</h1>
				</div><!-- /.col -->
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><a href="{!! route('admin') !!}">Admin</a></li>
						<li class="breadcrumb-item active">Rekap Cuti</li>
					</ol>
				</div><!-- /.col -->
			</div><!-- /.row -->
		</div><!-- /.container-fluid -->
	</div>
	
	<div class="card">
		<div class="card-header">
			<!--<h3 class="card-title">DataTable with default features</h3>-->
			<!--<a href="http://localhost/get-data/udp.php" target="_blank" title='Sync Absen' data-toggle='tooltip'><span class='fa fa-sync'></span> Sync Absen </a>-->
			
		</div>
		<div class="card-body">
			 <form id="cari_absen" class="form-horizontal" method="get" action="{!! route('be.rekap_cuti_karyawan') !!}">
                  
		
		
		
			<div class="row">
				
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Nama</label>
                                <select class="form-control select2" name="nama" style="width: 100%;">
                                    <option value="">Pilih Nama</option>
                                    <?php
                                    foreach($users AS $users){
                                        if($users->p_karyawan_id==$request->nama ){
                                            echo '<option selected="selected" value="'.$users->p_karyawan_id.'">'.$users->nama.' ('.$users->nmdept.') '. '</option>';
                                        }
                                        else{
                                            echo '<option value="'.$users->p_karyawan_id.'">'.$users->nama.' ('.$users->nmdept.') '. '</option>';
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-6">
                        <div class="form-group">
							<label>Entitas</label>
							<select class="form-control select2" name="entitas" style="width: 100%;" >
								<option value="">Pilih Entitas</option>
								<?php
								foreach($entitas AS $entitas){
										$selected = '';
									if($entitas->m_lokasi_id== $request->get('entitas')){
										$selected = 'selected';
										
									}
									
										echo '<option value="'.$entitas->m_lokasi_id.'" '.$selected.'>'.$entitas->nama.'</option>';
								}
								?>
							</select>
						</div>
						</div>
			
                    <div class="form-group">
                        <div class="col-md-12">
                            <a href="{!! route('be.ajuan') !!}" class="btn btn-primary"><span class="fa fa-sync"></span> Reset</a>
                            <button type="submit" name="Cari" class="btn btn-primary" value="Cari"><span class="fa fa-search"></span> Cari</button>
                            
                        </div>
                    </div>
                </form>
					<div id="table-scroll" class="table-scroll">
						<table id="main-table" class="main-table">
							<thead>
								<tr>
							
									<th scope="col" rowspan="2">Nama</th>
									<th scope="col" rowspan="2" >No </th>
									<th scope="col" rowspan="2" >NIK</th>
									<th scope="col" rowspan="2" >TANGAAL BERGABUNG</th>
									<?php 
									$tahun = date('Y');
									echo '
									<th scope="col" rowspan="2" >'.($tahun-1).'</th>
									<th scope="col" rowspan="2" >'.$tahun.'</th>
									<th scope="col" rowspan="2" >Cuti Besar</th>
									<th scope="col" rowspan="2" >TOTAL</th>';
									
									?>
                       
                        
									
								</tr>
							</thead>
							<tbody>
								<?php 
								
								$no=0;
								$rekap_cuti = $cuti['rekap_cuti'];
								//print_r($rekap_cuti['all']['rekap_ajuan'][2022]['05']);
								?>
								@if(!empty($listkaryawan))
								@foreach($listkaryawan as $list_karyawan)
								<?php $no++ ?>
                         
							
								<tr>
									<th>{!! $list_karyawan->nama !!}</th>
									<td scope="col">{!! $no !!}</td>
							
									<td>{!! $list_karyawan->nik !!}</td>
									<td>{!! $list_karyawan->tgl_bergabung !!}</td>
									<?php
									$sqlidkar = "select *,p_karyawan.p_karyawan_id as karyawan_id from p_karyawan 
        			left join p_karyawan_pekerjaan a on a.p_karyawan_id = p_karyawan.p_karyawan_id 
        			left join p_karyawan_kontrak on p_karyawan_kontrak.p_karyawan_id = p_karyawan.p_karyawan_id
        			left join p_karyawan_gapok on p_karyawan_gapok.p_karyawan_id = p_karyawan.p_karyawan_id and p_karyawan_gapok.active=1
        			where p_karyawan.p_karyawan_id=".$list_karyawan->p_karyawan_id;
        			$idkar = DB::connection()->select($sqlidkar);
							        $cuti = $help->query_cuti2($idkar);
							        $date2 = $cuti['date'];
							        $all = $cuti['all'];
							        $tanggal_loop = $cuti['tanggal_loop'];
							       
										$nominal = 0;
										$tahun = array();
										$tahunbesar = array();
										$datasisa = array();
		$ipg = array();
										$potong_gaji = array();
										$hutang = 0;
										$jumlah = 0;
										foreach ($tanggal_loop as $i => $loop) {

											if ($all[$i]['tanggal'] <= date('Y-m-d')) {
												$return = $help->perhitungan_cuti2($all, $datasisa, $hutang, $date2, $i, $nominal, $jumlah,$ipg,$potong_gaji);
												$datasisa = $return['datasisa'];
												$hutang = $return['hutang'];
												$nominal = $return['nominal'];
												$jumlah = $return['jumlah'];
												$jumlah =$return['jumlah'];
												$ipg =$return['ipg'];
												$potong_gaji =$return['potong_gaji'];
											}
										
										}
										$temp= -1;
										$besar=0;
										foreach($datasisa as $key=>$value){
											
											if($key<1000 and $key>$temp){
											$besar = $value;
											$temp = $key; 
											}
										}

										$thnkemarin = isset($datasisa[date('Y')-1])?$datasisa[date('Y')-1]:0;
										$thnsekarang = isset($datasisa[date('Y')])?$datasisa[date('Y')]:0;
										$totalsisa = $thnkemarin+$thnsekarang+$besar;
									if($request->view!='rekapall'){
											echo '<td >'.$thnkemarin.'</td>';
											echo '<td>'.$thnsekarang.'</td>';
											echo '<td>'.$besar.'</td>';
											echo '<td>'.$totalsisa.'</td>';
										}
									?>
								</tr>
								@endforeach
								@endif
							
							</tbody>
						
						</table>
					</div>

			
				</div>
			</div>
		</div>
		<!-- /.card-body -->
	</div>
	<!-- /.card -->
	<!-- /.card -->
	<!-- /.content -->
</div>
<!-- /.content-wrapper -->
@endsection

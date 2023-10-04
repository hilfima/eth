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
                        <h1 class="m-0 text-dark">Dashboard</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Admin</a></li>
                            <li class="breadcrumb-item active">Dashboard</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->
<style>
	.dash-widget-icon {
  background-color: rgba(102, 126, 234, 0.2);
  border-radius: 100%;
  color: #667eea;
  display: flex;
  float: left;
  font-size: 30px;
  height: 60px;
  line-height: 60px;
  margin-right: 10px;
  text-align: center;
  width: 60px;
  align-content: center;
  justify-items: center;
  align-items: center;
  align-self: center;
  float: center;
  justify-items: center;
  justify-content: center;
}
</style>
        <!-- Main content -->
            	<?php if($user[0]->role==-1 or $user[0]->role==1 or $user[0]->role==3 or $user[0]->role==12 or $user[0]->role==5){?>
        <section class="content">
            <div class="container-fluid">
                <!-- Small boxes (Stat box) -->
                <div class="row">
                <div class="col-md-6 col-sm-6 col-lg-6 col-xl-3" onclick="location.href='{!! route('be.kontrak');!!}'">
							<div class="dash-widget clearfix card-box" href="{!! route('be.kontrak') !!}">
								<span class="dash-widget-icon"><i class="fa fa-cubes"></i></span>
								<div class="dash-widget-info">
								<h3>{!! $totalkontrak[0]->total !!}</h3>
									<span>Kontrak H-30</span>
								</div>
							</div>
						</div>
						<div class="col-md-6 col-sm-6 col-lg-6 col-xl-3"  onclick="location.href='{!! route('be.karyawan');!!}'">
							<div class="dash-widget clearfix card-box" href="{!! route('be.karyawan') !!}">
								<span class="dash-widget-icon"><i class="fa fa-user"></i></span>
								<div class="dash-widget-info">
									<h3>{!! $jmlkaryawan[0]->jmlkaryawan !!}</h3>
									<span>Total Karyawan</span>
								</div>
							</div>
						</div>
						<div class="col-md-6 col-sm-6 col-lg-6 col-xl-3"   onclick="location.href='{!! route('be.lokasi');!!}'">
							<div class="dash-widget clearfix card-box" href="{!! route('be.lokasi') !!}">
								<span class="dash-widget-icon"><i class="la la-building"></i></span>
								<div class="dash-widget-info">
									<h3>{!! $jmllokasi[0]->jmllokasi !!}</h3>
									<span>Entitas</span>
								</div>
							</div>
						</div>
                          <div class="col-md-6 col-sm-6 col-lg-6 col-xl-3" onclick="location.href='{!! route('be.departemen');!!}'">
							<div class="dash-widget clearfix card-box" href="{!! route('be.departemen') !!}" >
								<span class="dash-widget-icon"><i class="la la-tasks"></i></span>
								<div class="dash-widget-info">
									<h3>{!! $jmldepartemen[0]->jmldepartemen !!}</h3>
									<span>Departemen</span>
								</div>
							</div>
						</div>
					
					
					
                    
						
								
								
								<div class="col-md-12">
							<div class="card card-table">
								<div class="card-header">
									<h3 class="card-title mb-0">Absensi Karyawan</h3>
									
								</div>
								</div>
								</div>
								
										
											 @if(!empty($list_karyawan))
							@foreach($list_karyawan as $list_karyawan)
                <div class="col-md-6 col-sm-6 col-lg-6 col-xl-4">
							<div class="dash-widget clearfix card-box" href="{!! route('be.kontrak') !!}">
								<span class=""> @if($list_karyawan->foto!=null)
                                                            <img src="{!! asset('dist/img/profile/'.$list_karyawan->foto) !!}"  class="dash-widget-icon" style="height: 75px;">
                                                        @else
                                                            <img src="{!! asset('dist/img/profile/user.png') !!}" class="dash-widget-icon"  style="height: 75px;">
                                                        @endif</span>
								<div class="dash-widget-info">
								<h5>{!! $list_karyawan->nama !!}</h5>
									<span><?php
                                    	$date = date('Y-m-d');
                                    	$main = 'STR1
                                    			STRLINK 
                                    			<br><div class="badge " style="font-size: small;color:white;STRBG"> STRKETERANGAN </div> STRENDLINK</td>	
                                    	';
									$warna = '';
									$ket = '';
									$content = '';
									if(isset($rekap[$list_karyawan->p_karyawan_id][$date]['a']['masuk'])){
										if($rekap[$list_karyawan->p_karyawan_id][$date]['a']['masuk'] >= $rekap[$list_karyawan->p_karyawan_id][$date]['a']['jam_masuk'] and  $list_karyawan->m_pangkat_id!=5){
											$ket = 'Terlambat';
											$warna = 'orange';
										}else{
											$warna = 'green';
											$ket = 'Masuk';
										}
										$content .= ' '.$rekap[$list_karyawan->p_karyawan_id][$date]['a']['masuk'];
										if(isset($rekap[$list_karyawan->p_karyawan_id][$date]['a']['keluar'])){
											
											$content.=' 
											s/d  '.$rekap[$list_karyawan->p_karyawan_id][$date]['a']['keluar'].'
											';	
										}
									}
									if(isset($rekap[$list_karyawan->p_karyawan_id][$date]['ci']['nama_ijin'])){
										if(!in_array($help->nama_hari($date),array('Minggu','Sabtu'))  ){
											if($rekap[$list_karyawan->p_karyawan_id][$date]['ci']['tipe']==3)
											$warna = 'green';
											else if($rekap[$list_karyawan->p_karyawan_id][$date]['ci']['tipe']==4)
											$warna = 'blue';
											else if($rekap[$list_karyawan->p_karyawan_id][$date]['ci']['tipe']==1)
											$warna = 'purple';
														
											$ket .= ' '.$rekap[$list_karyawan->p_karyawan_id][$date]['ci']['nama_ijin_only'].'<br> 
											
										
											';	
											if($rekap[$list_karyawan->p_karyawan_id][$date]['ci']['nama_ijin']
												== 'IZIN PERJALANAN DINAS'){
												$ket .= '<br> dari jam '.$rekap[$list_karyawan->p_karyawan_id][$date]['ci']['jam_awal'];
											}
										}
													
									}
									if(!$warna){
										$warna = 'red';
										$ket = 'No Information';
										$link = '<a href="'. route('be.input_absen',$list_karyawan->no_absen) .'" target="_blank" title="Input Absen">';
										$endlink = '</a>';
									}else{
										$link = '';
										$endlink = '';
									}	
									
											
									$main = str_ireplace('STR1',$content,$main);
									$main = str_ireplace('STRKETERANGAN',$ket,$main);
									$main = str_ireplace('STRBG','background:'.$warna,$main);
									$main = str_ireplace('STRLINK',$link,$main);
									$main = str_ireplace('STRENDLINK',$endlink,$main);
									
											
											
									echo $main;
                                    	?></span>
								</div>
							</div>
						</div>
						
						
							
							
                                @endforeach
                            @endif
												
												
											</tbody>
										</table>
									</div>
								</div>
								
							</div>
						</div>
					</div>
                <!-- Main content -->
                
             
            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
<!-- ChartJS -->
		<!-- Slimscroll JS -->
		
		<?php }else{?>
		<div class="card">
						<div class="card-body">
							Selamat Datang di Dashboard Accounting  Keuangan
							
							<br>
							<br>
							
						</div>
						</div>
		<?php }?>
@endsection
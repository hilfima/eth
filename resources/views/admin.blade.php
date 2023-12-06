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
					
					
					
                    
						
								
							<div id="ContentAbsen"></div>
												
												
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
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
		<script>
			absensi()
			function absensi(){
				//alert();
				$.ajax({
				type: 'get',
				url: '<?=route('be.absensi');?>',
				dataType: 'html',
				success: function(data){
					
					$('#ContentAbsen').html(data);
					
				    //console.log(data);
				},
				error: function (error) {
				    console.log('error; ' + eval(error));
				    //alert(2);
				}
				});
			}
		</script>
@endsection
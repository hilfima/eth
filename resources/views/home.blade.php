@extends('layouts.app_fe')

@section('content')


<div class="row">
	<div class="col-xl-3 col-lg-4 col-md-12 theiaStickySidebar">
		<?= view('layouts.app_side', compact('help')); ?>
	</div>
	<div class="col-xl-9 col-lg-8 col-md-12">




		<div class="row">
			<div class="col-xl-6 col-lg-12 col-md-6">
				<!--<div class="card ">

				<div class="card-body">
				<h4 class="card-title mb-0">Data Absen Hari ini</h4>
				</div>
 

				</div>-->
				<div class="row">
					<div class="col-lg-12 col-md-12 col-sm-12 col-12">
						<div class="card ctm-border-radius shadow-sm">
							<div class="card-header">
								<h4 class="card-title mb-0 d-inline-block">Quote</h4>

							</div>
							<div class="card-body recent-active text-center">
								<?= (count($qoute)) ? str_ireplace(array('Powered By','Froala Editor','href="https://www.froala.com/wysiwyg-editor'),array('','','href="#"'),$qoute[0]->qoute) : 'Qoute belum ada, tapi kita harus terus termotivasi oleh diri kita sendiri, tetap semangat <Br>Jangan lupa senyum'; ?>
								<!--"Hidup ini terlalu singkat untuk mengejar sesuatu yang tidak membawa kita semakin dekat dengan surga." - Boona Mohammed-->
							</div>
						</div>
					</div>
					<div class="col-lg-6 col-md-6 col-sm-6 col-12">
						<div class="card dash-widget ctm-border-radius shadow-sm">
							<div class="card-body">
								<div class="card-icon bg-primary">
									<i class="fa fa-users" aria-hidden="true"></i>
								</div>
								<div class="card-right w-100">
									<h4 class="card-title" style="font-weight: 600; text-align:center">Absen Masuk </h4>
									<p class="card-text" style="text-align:center">@if(!empty($absenin))
										<span class="info-box-number" style="text-align:center">{!! $absenin[0]->jam_masuk !!}</span>
										@else
										<span class="info-box-number" style="text-align:center">00:00:00</span>
										@endif
									</p>
								</div>
							</div>
						</div>
					</div>
					<div class=" col-lg-6 col-sm-6 col-12">
						<div class="card dash-widget ctm-border-radius shadow-sm">
							<div class="card-body">
								<div class="card-icon bg-warning">
									<i class="fa fa-users"></i>
								</div>
								<div class="card-right w-100">
									<h4 class="card-title" style="font-weight: 600; text-align:center">Absen Keluar</h4>
									<p class="card-text" style="text-align:center">@if(!empty($absenout))
										<span class="info-box-number" style="text-align:center">{!! $absenout[0]->jam_keluar !!}</span>
										@else
										<span class="info-box-number">00:00:00</span>
										@endif
									</p>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!--<div class="card ">

				<div class="card-body">
				<h4 class="card-title mb-0">Data Perusahaan</h4>
				</div>


				</div>-->
				<div class="row">
					<div class="col-lg-12 col-md-12 col-sm-12 col-12">
						
						
						<div class="card dash-widget ctm-border-radius shadow-sm">
							<div class="card-body">
								<div class="card-icon bg-danger">
									<i class="fa fa-suitcase" aria-hidden="true"></i>
								</div>
								<div class="card-right">
									<h4 class="card-title"></h4>
									<p class="card-text" id="content_cuti">
										

						</p>
								</div>
							</div>
						</div>
					</div>
					<div class=" col-lg-12 col-sm-12 col-12">
						<div class="card dash-widget ctm-border-radius shadow-sm">
							<div class="card-body">
								<div class="card-icon bg-secondary">
									<i class="fa"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" style="fill: rgb(255, 255, 255);transform: ;msFilter:;">
											<path d="m20.145 8.27 1.563-1.563-1.414-1.414L18.586 7c-1.05-.63-2.274-1-3.586-1-3.859 0-7 3.14-7 7s3.141 7 7 7 7-3.14 7-7a6.966 6.966 0 0 0-1.855-4.73zM15 18c-2.757 0-5-2.243-5-5s2.243-5 5-5 5 2.243 5 5-2.243 5-5 5z"></path>
											<path d="M14 10h2v4h-2zm-1-7h4v2h-4zM3 8h4v2H3zm0 8h4v2H3zm-1-4h3.99v2H2z"></path>
										</svg></i>
								</div>
								<div class="card-right">
									<h4 class="card-title">Lama Kerja</h4>
									<p class="card-text">{!! $umurkerja[0]->umur !!}</p>
								</div>
							</div>
						</div>
					</div>
					<!--<div class="card ctm-border-radius shadow-sm">
					<div class="card-header">
						<h4 class="card-title mb-0 d-inline-block">Today</h4>

					</div>
					<div class="card-body recent-active">
						<div class="recent-comment">
							<a href="javascript:void(0)" class="dash-card text-dark">
								<div class="dash-card-container">
									<div class="dash-card-icon text-primary">
										<i class="fa fa-money text-success" style="font-size: 22px;">
										</i>
									</div>
									<div class="dash-card-content">
										<h6 class="mb-0"><?php // $help->hitungHari(date('Y-m-d'), $tgl_akhir_gaji2); ?> Hari Lagi <b class="text-success" style="color: #3e007c;">Gajian</b></h6>
									</div>
								</div>
							</a>
						</div>
					</div>
				</div>
-->
					<!--<div class=" col-lg-12 col-sm-12 col-12">
						<div class="card dash-widget ctm-border-radius shadow-sm">
							<div class="card-body">
								<div class="card-icon bg-success">
									<i class="fa fa-heartbeat" aria-hidden="true"></i>

								</div>
								<div class="card-right">
									<h4 class="card-title">Sisa Plafon Fasilitas Kesehatan	</h4>
									<p class="card-text"><?php // $help->rupiah(isset($fasilitas[0]) ? $fasilitas[0]->saldo : 0); ?></p>
								</div>
							</div>
						</div>
					</div>-->

				</div>
			</div>


			<div class="col-xl-6 col-lg-12 col-md-12">

					
				
						
					<div class="card">
					<div class="card-header">
								<h4 class="card-title mb-0">Rekap Absen <?=$help->bulan(date('m'));?></h4>
							</div>
							<div class="card-body" id="rekap_absen">
								

							</div>
					</div>
							
				<!--	<div class="card ctm-border-radius shadow-sm">
					<div class="card-header">
						<h4 class="card-title mb-0 d-inline-block">Today Birthday</h4>

					</div>
					<div class="card-body recent-active">
						<div class="recent-comment">
							<a href="javascript:void(0)" class="dash-card text-dark">
								<div class="dash-card-container">
									<div class="dash-card-icon text-primary">
										<i class="fa fa-money text-success" style="font-size: 22px;">
										</i>
									</div>
									<div class="dash-card-content">
										<h6 class="mb-0"><?php //$help->hitungHari(date('Y-m-d'), $tgl_akhir_gaji2); ?> Hari Lagi <b class="text-success" style="color: #3e007c;">Gajian</b></h6>
									</div>
										
								</div>
							</a>
							
						</div>
					</div>
				</div>
				-->

				</div>

				
			</div>
			<style>
				button box-shadow: none border: none outline: none cursor: pointer background: #ddd padding: 10 px font-weight: 700 margin-top: 20 px &:hover background: darken(#ddd, 5%) .hidden-text {

					width: 80%;
					margin: 0 auto;
					margin-top: 20px;
					overflow: hidden;
					font-size: 20px;

				}
			</style>
			<div class="col-xl-12 col-lg-12 col-md-12">
				
			</div>

		</div>
<Style>
								.more {
									overflow: hidden;
								}

								.hide {
									display: none;
								}

								.readmore,
								.readless,
								.readmuchmore,
								.readmuchless {
									text-align: right;
								}

								.wrapper {
									max-width: 960px;
									margin: auto;
								}

								.amarillo {
									background: url("//placehold.it/960x150") center center no-repeat;
									background-size: contain;
									overflow: hidden;
									padding: 0 20%;
								}

								h1 {
									text-align: center;
									font-size: 22px;
									padding: 10px;
								}

								h2 {
									font-size: 20px;
									padding-bottom: 20px;
									padding-top: 8px;
									font-weight: 400;
								}

								h3 {
									font-size: 16px;
									padding-bottom: 10px;
								}

								.toggle-text {
									max-width: 400px;
									margin: 50px auto;
									text-align: center;
								}

								.toggle-text-content {

									span {
										display: none;
									}
								}

								.toggle-text-link {
									display: block;
									margin: 20px 0;
								}
							</Style>
	</div>



<!-- Modal -->
<div class="modal fade" id="ultahModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog  modal-dialog-centered" role="document">
    <div class="modal-content">
     
      <div class="modal-body text-center">
       <h5 class="modal-title text-bold" id="exampleModalLabel">Barakallah Fii Umrik</h5>
       Semoga Allaah senantiasa melimpahkan rahmat dan keberkahan
      </div>
      
    </div>
  </div>
</div>	

	<!-- Modal -->
	
</div>
<script src="<?= url('plugins/dleohr/assets/js/Chart.min.js') ?>"></script>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>

<div class="modal fade" id="GratifikasiModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog  modal-dialog-centered" role="document">
     
    <div class="modal-content" style="border-radius: 13px;">
    
   
      <div class="modal-body text-center">
      <div class="text-right">
     	
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="font-size: 20px">
          <span aria-hidden="true" style="font-size: 35px;">&times;</span>
        </button>
      
     </div>
       <!--<h5 class="modal-title text-bold h2 bold" id="exampleModalLabel">Laporkan Gratifikasi</h5>
       --><img src="{{asset('dist/img/menu/banner-gratifikasi.jpg')}}">
       <!--Jika menemukan gratifikasi, segera laporkan -->
      </div>
      <a href="{!! route('fe.laporan_gratifikasi') !!}" class="btn btn-theme button-1 text-white ctm-border-radius p-2 add-person ctm-btn-padding">Laporkan Sekarang!</a>
    </div>
  </div>
</div>	

<script>
	$(document).ready(function() {
		// Configure/customize these variables.
		var showChar = 287; // How many characters are shown by default
		var ellipsestext = "";
		var moretext = "Read More";
		var lesstext = "Read Less";
        optimasi_total_cuti();
        optimasi_rekap_absen();
        optimasi_fasilitas();
        
        
        
        
        
        function optimasi_total_cuti(){
            $.ajax({
				type: 'get',
			
				url: '<?=route('optimasi_total_cuti');?>',
				dataType: 'html',
				success: function(data){
					$('#content_cuti').html(data);
					
				},
				error: function (error) {
				    console.log('error; ' + eval(error));
				    //alert(2);
				}
			});
        }
        function optimasi_rekap_absen(){
            $.ajax({
				type: 'get',
			
				url: '<?=route('optimasi_rekap_absen');?>',
				dataType: 'html',
				success: function(data){
					$('#rekap_absen').html(data);
					
				},
				error: function (error) {
				    console.log('error; ' + eval(error));
				    //alert(2);
				}
			});
        }
        function optimasi_fasilitas(){
            $.ajax({
				type: 'get',
			
				url: '<?=route('optimasi_fasilitas');?>',
				dataType: 'html',
				success: function(data){
					
					
				},
				error: function (error) {
				    console.log('error; ' + eval(error));
				    //alert(2);
				}
			});
        }
		//$('#ultahModal').modal();
		//$('#GratifikasiModal').modal();

	});

	function readmore(id, e) {
		var berita = $('#contentberita-' + id).attr("data-visible");
		//alert(berita);
		if (berita == "0") {
			//alert();
			$('#contentberita-' + id).show();
			$('#lessberita-' + id).hide();
			$('#contentberita-' + id).attr("data-visible", 1);
		} else {
			$('#contentberita-' + id).attr("data-visible", 0);
			//$('#lessberita-'+id).show();
			$('#contentberita-' + id).hide();

		}
	}
</script>
<script>
	function read_more() {
		var readmore = $('.read_more');
		var comment = $('.review_comment p').text();

		//goes through each index of the array of 'review_comment p'
		$('.review_comment p').each(function(i) {
			//calculates height of comment variable
			var commentheight = $(this).height();
			//calculates scroll height of comment on each div
			var scrollcommentheight = $('.review_comment p')[i].scrollHeight;
			console.log("This is the comment height" + ' - ' + commentheight);
			console.log("This is the scroll height" + ' - ' + scrollcommentheight);
			//if comment height is same as scroll height then hide read more button
			if (commentheight == scrollcommentheight) {
				$(this).siblings(readmore).hide();
			}
			//otherwise read more button shows
			else {
				$(this).siblings(readmore).text("Read More");
			}
		});


		readmore.on('click', function() {
			var $this = $(this);
			event.preventDefault();

			$this.siblings('.review_comment p').toggleClass('active');

			if ($this.siblings('.review_comment p').text().length < 230) {
				$this.text("Read More");
			}
			if ($('.review_comment p').hasClass('active')) {
				$this.text("Read Less");
			} else {
				$this.text("Read More");
			}
		});
	};

	$(function() {
		//Calling function after Page Load
		read_more();
	});
</script>

@endsection
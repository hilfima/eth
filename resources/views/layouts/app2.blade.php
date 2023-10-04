<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">

		<!-- CSRF Token -->
		<meta name="csrf-token" content="{{ csrf_token() }}">

		<!--<title>{{ config('app.name', 'ES-HRMS') }}</title>-->
		<title>ES-iOS || HRMS </title>

		<!-- Favicon -->
		<link href="{!! asset('logo.png') !!}" rel="shortcut icon" />

		<!-- Google Font: Source Sans Pro -->
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
		<!-- Font Awesome -->
		<link rel="stylesheet" href="{!! asset('plugins/fontawesome-free/css/all.min.css') !!}">
		<!-- Ionicons -->
		<link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
		<!-- iCheck -->
		<link rel="stylesheet" href="{!! asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css') !!}">
		<!-- JQVMap -->
		<link rel="stylesheet" href="{!! asset('plugins/jqvmap/jqvmap.min.css') !!}">
		<!-- Theme style -->
		<link rel="stylesheet" href="{!! asset('dist/css/adminlte.min.css') !!}">
		<!-- overlayScrollbars -->
		<link rel="stylesheet" href="{!! asset('plugins/overlayScrollbars/css/OverlayScrollbars.min.css') !!}">
		<!-- Daterange picker -->
		<link rel="stylesheet" href="{!! asset('plugins/daterangepicker/daterangepicker.css') !!}">
		<!-- summernote -->
		<link rel="stylesheet" href="{!! asset('plugins/summernote/summernote-bs4.min.css') !!}">
		<!-- Scripts -->
		<!--<script src="{{ asset('js/app.js') }}" defer></script>-->

		<!-- DataTables -->
		<link rel="stylesheet" href="{!! asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') !!}">
		<link rel="stylesheet" href="{!! asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') !!}">

		<!-- Fonts -->
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
		<!--<link rel="dns-prefetch" href="//fonts.gstatic.com">
		<link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">-->

		<!-- Bootstrap Color Picker -->
		<link rel="stylesheet" href="{!! asset('plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css') !!}">
		<!-- Tempusdominus Bootstrap 4 -->
		<link rel="stylesheet" href="{!! asset('plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') !!}">
		<!-- Select2 -->
		<link rel="stylesheet" href="{!! asset('plugins/select2/css/select2.min.css') !!}">
		<link rel="stylesheet" href="{!! asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') !!}">

		<!-- Fonts -->
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
		<!--<link rel="dns-prefetch" href="//fonts.gstatic.com">
		<link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">-->

		<!-- Bootstrap4 Duallistbox -->
		<link rel="stylesheet" href="{!! asset('plugins/bootstrap4-duallistbox/bootstrap-duallistbox.min.css') !!}">
		<!-- BS Stepper -->
		<link rel="stylesheet" href="{!! asset('plugins/bs-stepper/css/bs-stepper.min.css') !!}">
		<!-- dropzonejs -->
		<link rel="stylesheet" href="{!! asset('plugins/dropzone/min/dropzone.min.css') !!}">
		<!-- Theme style -->
		<link rel="stylesheet" href="{!! asset('dist/css/adminlte.min.css') !!}">

		<!-- pace-progress -->
		<link rel="stylesheet" href="{!! asset('plugins/pace-progress/themes/black/pace-theme-flat-top.css') !!}">

		<!-- SweetAlert2 -->
		<link rel="stylesheet" href="{!! asset('plugins/sweetalert2/sweetalert2.min.css') !!}">
		<!-- Toastr -->
		<link rel="stylesheet" href="{!! asset('plugins/toastr/toastr.min.css') !!}">
		<!-- Ekko Lightbox -->
		<link rel="stylesheet" href="{!! asset('plugins/ekko-lightbox/ekko-lightbox.css') !!}">

		<!-- fullCalendar -->
		<link rel="stylesheet" href="{!! asset('plugins/fullcalendar/main.min.css') !!}">
		<link rel="stylesheet" href="{!! asset('plugins/fullcalendar-daygrid/main.min.css') !!}">
		<link rel="stylesheet" href="{!! asset('plugins/fullcalendar-timegrid/main.min.css') !!}">
		<link rel="stylesheet" href="{!! asset('plugins/fullcalendar-bootstrap/main.min.css') !!}">

		<link rel="stylesheet" type="text/css" href="{!! asset('fancybox/jquery.fancybox.css') !!}">
		<!-- library JS -->
		<script src="//code.jquery.com/jquery-3.2.0.min.js"></script>
		<!-- library JS fancybox -->
		<script src="{!! asset('fancybox/jquery.fancybox.js') !!}"></script>

		<script type="text/javascript">
			$("[data-fancybox]").fancybox({ });
		</script>

		<style type="text/css">
			.gallery img {
				width: 20%;
				height: auto;
				border-radius: 5px;
				cursor: pointer;
				transition: .3s;
			}
		</style>
		<!--<link href="{{ asset('css/app.css') }}" rel="stylesheet">-->
	</head>
	<body class="hold-transition sidebar-mini pace-primary">
		<div id="app">
			<!-- Navbar -->
			<nav class="navbar navbar-expand navbar-white navbar-light">
				<!-- Left navbar links -->
				<ul class="navbar-nav">
					<li class="nav-item">
						<a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
					</li>
					<!--<li class="nav-item d-none d-sm-inline-block">
					<a href="index3.html" class="nav-link">Home</a>
					</li>
					<li class="nav-item d-none d-sm-inline-block">
					<a href="#" class="nav-link">Contact</a>
					</li>-->
				</ul>

				<!-- SEARCH FORM -->
				<!--<form class="form-inline ml-3">
				<div class="input-group input-group-sm">
				<input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search">
				<div class="input-group-append">
				<button class="btn btn-navbar" type="submit">
				<i class="fas fa-search"></i>
				</button>
				</div>
				</div>
				</form>-->

				<!-- Right navbar links -->
				<?php
						$idusernotifi=Auth::user()->id;
						$sqlidkarnotifi="select * from p_karyawan where user_id=$idusernotifi";
						$idkarnotifi=DB::connection()->select($sqlidkarnotifi);
						$idnotifi=$idkarnotifi[0]->p_karyawan_id;
						$notifi = "SELECT a.*,b.nik,b.nama,c.kode,c.nama as nama_ijin,d.nama as nama_appr,tgl_appr_1,tgl_awal,tgl_akhir,status_appr_1,c.tipe,(select count(*) FROM t_permit where t_permit.p_karyawan_id = $idnotifi and status_appr_1 = 3) as countapprove FROM t_permit a
						left join p_karyawan b on b.p_karyawan_id=a.p_karyawan_id
						left join m_jenis_ijin c on c.m_jenis_ijin_id=a.m_jenis_ijin_id
						left join p_karyawan d on d.p_karyawan_id=a.appr_1
						WHERE 1=1 and a.active=1 and a.p_karyawan_id=$idnotifi  ORDER BY a.tgl_awal desc limit 5";
						$notifi=DB::connection()->select($notifi); 
						
						$appr = "SELECT a.*,b.nik,b.nama,c.kode,c.nama as nama_ijin,d.nama as nama_appr,tgl_appr_1,tgl_awal,tgl_akhir,status_appr_1,c.tipe,(select count(*) FROM t_permit where t_permit.p_karyawan_id = $idnotifi and status_appr_1 = 3) as countapprove FROM t_permit a
						left join p_karyawan b on b.p_karyawan_id=a.p_karyawan_id
						left join m_jenis_ijin c on c.m_jenis_ijin_id=a.m_jenis_ijin_id
						left join p_karyawan d on d.p_karyawan_id=a.appr_1
						WHERE 1=1 and a.active=1 and a.appr_1=$idnotifi  ORDER BY a.tgl_awal desc limit 5
						";
						$notifiappr=DB::connection()->select($appr); 
						
						 $sqlberitia="SELECT * FROM hr_care where active=1 and tgl_posting>='".date('Y-m-d')."' or tgl_posting_akhir>='".date('Y-m-d')." 24:00' ";
       					 $beritia=DB::connection()->select($sqlberitia);
						?>
				<ul class="navbar-nav ml-auto">
					<!-- Messages Dropdown Menu -->
					<li class="nav-item dropdown">
						<a class="nav-link" data-toggle="dropdown" href="#">
							<i class="far fa-comments"></i>
							<span class="badge badge-danger navbar-badge"><?=count($beritia)?></span>
						</a>
						
						@if(count($beritia))
					
						<div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
						<?php foreach($beritia as $beritia){
							?>
								<a href=#" class="dropdown-item dropdown-header text-left" style="font-size: 12px;">
								<p>
									
									{!! $beritia->judul!!}                        			                                       		 							
								</p>
								
                        	                                                 	
								</a>
									<div class="dropdown-divider"></div>			
							<?php
							}
							?>
							</div>
						@endif
					</li>
					<!-- Notifications Dropdown Menu -->
					<li class="nav-item dropdown">
                 
						<a class="nav-link" data-toggle="dropdown" href="#">
							<i class="far fa-bell"></i>
							@if(!empty($notifi)   )
							<span class="badge badge-warning navbar-badge"><?=($notifi[0]->countapprove);?></span>
							@else
							<span class="badge badge-warning navbar-badge">0</span>
							@endif
						</a>
						<div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                    	

							<?php
							foreach($notifi as $notifi){
							if($notifi->tipe==1)
							$link = 'izin';
							else if($notifi->tipe==2)
							$link = 'lembur';
							else
							$link = 'cuti';
							?>
							<a href="{!! route('fe.lihat_'.$link,$notifi->t_form_exit_id) !!}" class="dropdown-item dropdown-header text-left" style="font-size: 10px">
								<?= $notifi->nama_ijin?>
								@if($notifi->status_appr_1==1)
								<span class="badge badge-success" style="font-size: 10px">Disetujui</span>
                                       
								@elseif($notifi->status_appr_1==2)
								<span cclass="badge badge-danger" style="font-size: 10px"> Ditolak</span>
								@else
								<span class="badge badge-warning" style="font-size: 10px"> Pending</span>
								@endif
								<br>
								@if($notifi->tgl_awal==$notifi->tgl_akhir)
								{!! date('d-m-Y', strtotime($notifi->tgl_awal)) !!} 
								@else
								{!! date('d-m-Y', strtotime($notifi->tgl_awal)) !!} s/d {!! date('d-m-Y', strtotime($notifi->tgl_akhir )) !!}
								@endif
								<?= $notifi->tgl_appr_1?'Aproved tgl:'.$notifi->tgl_appr_1:'';?>
                        	
							</a>
							<div class="dropdown-divider"></div>
							<?php
							}
							
							?>
							<!--frontend/lihat_cuti/893-->
                       
							<!-- <div class="dropdown-divider"></div>
							<a href="#" class="dropdown-item dropdown-footer">See All Notifications</a>-->
						</div>
					</li>
<li class="nav-item dropdown">
                 
						<a class="nav-link" data-toggle="dropdown" href="#">
							<i class="fa fa-exclamation "></i>
							@if(!empty($notifiappr)   )
							<span class="badge badge-warning navbar-badge"><?=($notifiappr[0]->countapprove);?></span>
							@else
							<span class="badge badge-warning navbar-badge">0</span>
							@endif
						</a>
						<div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                    	

							<?php
							foreach($notifiappr as $notifi){
							if($notifi->tipe==1)
							$link = 'izin';
							else if($notifi->tipe==2)
							$link = 'lembur';
							else
							$link = 'cuti';
							?>
							<a href="{!! route('fe.edit_ajuan',$notifi->t_form_exit_id) !!}" class="dropdown-item dropdown-header text-left" style="font-size: 10px">
								<?= $notifi->nama_ijin?>
								@if($notifi->status_appr_1==1)
								<span class="badge badge-success" style="font-size: 10px">Disetujui</span>
                                       
								@elseif($notifi->status_appr_1==2)
								<span cclass="badge badge-danger" style="font-size: 10px"> Ditolak</span>
								@else
								<span class="badge badge-warning" style="font-size: 10px"> Pending</span>
								@endif
								<br>
								@if($notifi->tgl_awal==$notifi->tgl_akhir)
								{!! date('d-m-Y', strtotime($notifi->tgl_awal)) !!} 
								@else
								{!! date('d-m-Y', strtotime($notifi->tgl_awal)) !!} s/d {!! date('d-m-Y', strtotime($notifi->tgl_akhir )) !!}
								@endif
								<br>
								<?= 'Pengaju '.$notifi->nama;?>
								
                        	
							</a>
							<div class="dropdown-divider"></div>
							<?php
							}
							
							?>
							<!--frontend/lihat_cuti/893-->
                       
							<!-- <div class="dropdown-divider"></div>
							<a href="#" class="dropdown-item dropdown-footer">See All Notifications</a>-->
						</div>
					</li>

				</ul>
			</nav>
			<!-- /.navbar -->

			<main class="py-4">
				@yield('content')
			</main>
		</div>

		<footer class="footer">
			<strong>Copyright &copy; 2020 - {!! date('Y') !!} <a href="{!! route('home') !!}"> ES-iOS || HRMS </a>.</strong>
			All rights reserved.
			<div class="float-right d-none d-sm-inline-block">
				<b>Version</b> 2.1.0
			</div>
		</footer>

		<!-- Control Sidebar -->
		<aside class="control-sidebar control-sidebar-dark">
			<!-- Control sidebar content goes here -->
		</aside>
		<!-- /.control-sidebar -->

		<!-- jQuery -->
		<script src="{!! asset('plugins/jquery/jquery.min.js') !!}"></script>
		<!-- jQuery UI 1.11.4 -->
		<script src="{!! asset('plugins/jquery-ui/jquery-ui.min.js') !!}"></script>
		<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
		<script>
			$.widget.bridge('uibutton', $.ui.button)
		</script>
		<!-- Bootstrap 4 -->
		<script src="{!! asset('plugins/bootstrap/js/bootstrap.bundle.min.js') !!}"></script>
		<!-- pace-progress -->
		<script src="{!! asset('plugins/pace-progress/pace.min.js') !!}"></script>
		<!-- ChartJS -->
		<script src="{!! asset('plugins/chart.js/Chart.min.js') !!}"></script>
		<!-- JQVMap -->
		<script src="{!! asset('plugins/jqvmap/jquery.vmap.min.js') !!}"></script>
		<script src="{!! asset('plugins/jqvmap/maps/jquery.vmap.usa.js') !!}"></script>
		<!-- jQuery Knob Chart -->
		<script src="{!! asset('plugins/jquery-knob/jquery.knob.min.js') !!}"></script>
		<!-- daterangepicker -->
		<script src="{!! asset('plugins/moment/moment.min.js') !!}"></script>
		<script src="{!! asset('plugins/daterangepicker/daterangepicker.js') !!}"></script>
		<!-- Tempusdominus Bootstrap 4 -->
		<script src="{!! asset('plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') !!}"></script>
		<!-- Summernote -->
		<script src="{!! asset('plugins/summernote/summernote-bs4.min.js') !!}"></script>
		<!-- overlayScrollbars -->
		<script src="{!! asset('plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') !!}"></script>
		<!-- AdminLTE App -->
		<script src="{!! asset('dist/js/adminlte.js') !!}"></script>
		<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
		<script src="{!! asset('dist/js/pages/dashboard.js') !!}"></script>
		<!-- AdminLTE for demo purposes -->
		<script src="{!! asset('dist/js/demo.js') !!}"></script>
		<!-- DataTables -->
		<script src="{!! asset('plugins/datatables/jquery.dataTables.min.js') !!}"></script>
		<script src="{!! asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') !!}"></script>
		<script src="{!! asset('plugins/datatables-responsive/js/dataTables.responsive.min.js') !!}"></script>
		<script src="{!! asset('plugins/datatables-responsive/js/responsive.bootstrap4.min.js') !!}"></script>
		<!-- bootstrap color picker -->
		<script src="{!! asset('plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js') !!}"></script>
		<!-- Select2 -->
		<script src="{!! asset('plugins/select2/js/select2.full.min.js') !!}"></script>
		<!-- Bootstrap Switch -->
		<script src="{!! asset('plugins/bootstrap-switch/js/bootstrap-switch.min.js') !!}"></script>
		<!-- BS-Stepper -->
		<script src="{!! asset('plugins/bs-stepper/js/bs-stepper.min.js') !!}"></script>
		<!-- dropzonejs -->
		<script src="{!! asset('plugins/dropzone/min/dropzone.min.js') !!}"></script>
		<!-- ChartJS -->
		<script src="{!! asset('plugins/chart.js/Chart.min.js') !!}"></script>
		<!-- Filterizr-->
		<script src="{!! asset('plugins/filterizr/jquery.filterizr.min.js') !!}"></script>
		<!-- page script -->
		<!--Masking-->
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.15/jquery.mask.min.js"></script>
		<script>
			$(document).ready(function(){
					$('.masking0').mask('###', {reverse: true});
					$('.masking1').mask('#.##0', {reverse: true});
					$('.masking2').mask('#.##0,0', {reverse: true});
					$('.masking3').mask('#,##0', {reverse: true});
				})

			$(function () {
					$("#example1").DataTable({
							"responsive": true,
							"autoWidth": false,
						});
					$('#example2').DataTable({
							"paging": true,
							"lengthChange": false,
							"searching": false,
							"ordering": true,
							"info": true,
							"autoWidth": false,
							"responsive": true,
						});
				});
		</script>
		<script type="text/javascript">
			//Initialize Select2 Elements
			$('.select2').select2()

			//Initialize Select2 Elements
			$('.select2bs4').select2({
					theme: 'bootstrap4'
				})

			// Summernote
			$('#summernote').summernote()
			$('#keterangan').summernote()
			$('#alamat_ktp').summernote()

			// CodeMirror
			CodeMirror.fromTextArea(document.getElementById("codeMirrorDemo"), {
					mode: "htmlmixed",
					theme: "monokai"
				});

			// SimpleMDE
			new SimpleMDE({ element: document.getElementById("simpleMDE") });

			//Datemask dd/mm/yyyy
			$('#datemask').inputmask('dd/mm/yyyy', { 'placeholder': 'dd/mm/yyyy' })
			//Datemask2 mm/dd/yyyy
			$('#datemask2').inputmask('mm/dd/yyyy', { 'placeholder': 'mm/dd/yyyy' })
			//Money Euro
			$('[data-mask]').inputmask()

			//Date range picker
			$('#reservationdate').datetimepicker({
					format: 'dd/mm/yyyy',
					multiselect: true,
				});

			//Date range picker
			$('#reservation').daterangepicker()
			//Date range picker with time picker
			$('#reservationtime').daterangepicker({
					timePicker: true,
					timePickerIncrement: 30,
					locale: {
						format: 'MM/DD/YYYY hh:mm A'
					}
				})
			//Date range as a button
			$('#daterange-btn').daterangepicker(
				{
					ranges   : {
						'Today'       : [moment(), moment()],
						'Yesterday'   : [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
						'Last 7 Days' : [moment().subtract(6, 'days'), moment()],
						'Last 30 Days': [moment().subtract(29, 'days'), moment()],
						'This Month'  : [moment().startOf('month'), moment().endOf('month')],
						'Last Month'  : [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
					},
					startDate: moment().subtract(29, 'days'),
					endDate  : moment()
				},
				function (start, end) {
					$('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'))
				}
			)

			//Timepicker
			$('#timepicker').datetimepicker({
					format: 'LT'
				})

			//Bootstrap Duallistbox
			$('.duallistbox').bootstrapDualListbox()

			//Colorpicker
			$('.my-colorpicker1').colorpicker()
			//color picker with addon
			$('.my-colorpicker2').colorpicker()

			$('.my-colorpicker2').on('colorpickerChange', function(event) {
					$('.my-colorpicker2 .fa-square').css('color', event.color.toString());
				});

			$("input[data-bootstrap-switch]").each(function(){
					$(this).bootstrapSwitch('state', $(this).prop('checked'));
				});

			// BS-Stepper Init
			document.addEventListener('DOMContentLoaded', function () {
					window.stepper = new Stepper(document.querySelector('.bs-stepper'))
				});

			$(document).ready(function () {
					var stepper = new Stepper($('.bs-stepper')[0])
				})

			// DropzoneJS Demo Code Start
			Dropzone.autoDiscover = false;

			// Get the template HTML and remove it from the doumenthe template HTML and remove it from the doument
			var previewNode = document.querySelector("#template");
			previewNode.id = "";
			var previewTemplate = previewNode.parentNode.innerHTML;
			previewNode.parentNode.removeChild(previewNode);

			var myDropzone = new Dropzone(document.body, { // Make the whole body a dropzone
					url: "/target-url", // Set the url
					thumbnailWidth: 80,
					thumbnailHeight: 80,
					parallelUploads: 20,
					previewTemplate: previewTemplate,
					autoQueue: false, // Make sure the files aren't queued until manually added
					previewsContainer: "#previews", // Define the container to display the previews
					clickable: ".fileinput-button" // Define the element that should be used as click trigger to select files.
				});

			myDropzone.on("addedfile", function(file) {
					// Hookup the start button
					file.previewElement.querySelector(".start").onclick = function() { myDropzone.enqueueFile(file); };
				});

			// Update the total progress bar
			myDropzone.on("totaluploadprogress", function(progress) {
					document.querySelector("#total-progress .progress-bar").style.width = progress + "%";
				});

			myDropzone.on("sending", function(file) {
					// Show the total progress bar when upload starts
					document.querySelector("#total-progress").style.opacity = "1";
					// And disable the start button
					file.previewElement.querySelector(".start").setAttribute("disabled", "disabled");
				});

			// Hide the total progress bar when nothing's uploading anymore
			myDropzone.on("queuecomplete", function(progress) {
					document.querySelector("#total-progress").style.opacity = "0";
				});

			// Setup the buttons for all transfers
			// The "add files" button doesn't need to be setup because the config
			// `clickable` has already been specified.
			document.querySelector("#actions .start").onclick = function() {
				myDropzone.enqueueFiles(myDropzone.getFilesWithStatus(Dropzone.ADDED));
			};
			document.querySelector("#actions .cancel").onclick = function() {
				myDropzone.removeAllFiles(true);
			};
			// DropzoneJS Demo Code End
		</script>
		<script type="text/javascript">
			//Initialize Select2 Elements
			$('.select2').select2()

			//Initialize Select2 Elements
			$('.select2bs4').select2({
					theme: 'bootstrap4'
				})
		</script>
		<script>
			$(function () {
					$(document).on('click', '[data-toggle="lightbox"]', function(event) {
							event.preventDefault();
							$(this).ekkoLightbox({
									alwaysShowClose: true
								});
						});

					$('.filter-container').filterizr({gutterPixels: 3});
					$('.btn[data-filter]').on('click', function() {
							$('.btn[data-filter]').removeClass('active');
							$(this).addClass('active');
						});
				})
		</script>
		<!-- fullCalendar 2.2.5 -->
		<script src="{!! asset('plugins/moment/moment.min.js') !!}"></script>
		<script src="{!! asset('plugins/fullcalendar/main.min.js') !!}]"></script>
		<script src="{!! asset('plugins/fullcalendar-daygrid/main.min.js') !!}"></script>
		<script src="{!! asset('plugins/fullcalendar-timegrid/main.min.js') !!}"></script>
		<script src="{!! asset('plugins/fullcalendar-interaction/main.min.js') !!}"></script>
		<script src="{!! asset('plugins/fullcalendar-bootstrap/main.min.js') !!}"></script>
		<!-- Page specific script -->
		<script>
			$(function () {

					/* initialize the external events
					-----------------------------------------------------------------*/
					function ini_events(ele) {
						ele.each(function () {

								// create an Event Object (https://fullcalendar.io/docs/event-object)
								// it doesn't need to have a start or end
								var eventObject = {
									title: $.trim($(this).text()) // use the element's text as the event title
								}

								// store the Event Object in the DOM element so we can get to it later
								$(this).data('eventObject', eventObject)

								// make the event draggable using jQuery UI
								$(this).draggable({
										zIndex        : 1070,
										revert        : true, // will cause the event to go back to its
										revertDuration: 0  //  original position after the drag
									})

							})
					}

					ini_events($('#external-events div.external-event'))

					/* initialize the calendar
					-----------------------------------------------------------------*/
					//Date for the calendar events (dummy data)
					var date = new Date()
					var d    = date.getDate(),
					m    = date.getMonth(),
					y    = date.getFullYear()

					var Calendar = FullCalendar.Calendar;
					var Draggable = FullCalendarInteraction.Draggable;

					var containerEl = document.getElementById('external-events');
					var checkbox = document.getElementById('drop-remove');
					var calendarEl = document.getElementById('calendar');

					// initialize the external events
					// -----------------------------------------------------------------

					new Draggable(containerEl, {
							itemSelector: '.external-event',
							eventData: function(eventEl) {
								return {
									title: eventEl.innerText,
									backgroundColor: window.getComputedStyle( eventEl ,null).getPropertyValue('background-color'),
									borderColor: window.getComputedStyle( eventEl ,null).getPropertyValue('background-color'),
									textColor: window.getComputedStyle( eventEl ,null).getPropertyValue('color'),
								};
							}
						});

					var calendar = new Calendar(calendarEl, {
							plugins: [ 'bootstrap', 'interaction', 'dayGrid', 'timeGrid' ],
							header    : {
								left  : 'prev,next today',
								center: 'title',
								right : 'dayGridMonth,timeGridWeek,timeGridDay'
							},
							'themeSystem': 'bootstrap',
							//Random default events
							events    : [
								{
									title          : 'All Day Event',
									start          : new Date(y, m, 1),
									backgroundColor: '#f56954', //red
									borderColor    : '#f56954', //red
									allDay         : true
								},
								{
									title          : 'Long Event',
									start          : new Date(y, m, d - 5),
									end            : new Date(y, m, d - 2),
									backgroundColor: '#f39c12', //yellow
									borderColor    : '#f39c12' //yellow
								},
								{
									title          : 'Meeting',
									start          : new Date(y, m, d, 10, 30),
									allDay         : false,
									backgroundColor: '#0073b7', //Blue
									borderColor    : '#0073b7' //Blue
								},
								{
									title          : 'Lunch',
									start          : new Date(y, m, d, 12, 0),
									end            : new Date(y, m, d, 14, 0),
									allDay         : false,
									backgroundColor: '#00c0ef', //Info (aqua)
									borderColor    : '#00c0ef' //Info (aqua)
								},
								{
									title          : 'Birthday Party',
									start          : new Date(y, m, d + 1, 19, 0),
									end            : new Date(y, m, d + 1, 22, 30),
									allDay         : false,
									backgroundColor: '#00a65a', //Success (green)
									borderColor    : '#00a65a' //Success (green)
								},
								{
									title          : 'Click for Google',
									start          : new Date(y, m, 28),
									end            : new Date(y, m, 29),
									url            : 'https://www.google.com/',
									backgroundColor: '#3c8dbc', //Primary (light-blue)
									borderColor    : '#3c8dbc' //Primary (light-blue)
								}
							],
							editable  : true,
							droppable : true, // this allows things to be dropped onto the calendar !!!
							drop      : function(info) {
								// is the "remove after drop" checkbox checked?
								if (checkbox.checked) {
									// if so, remove the element from the "Draggable Events" list
									info.draggedEl.parentNode.removeChild(info.draggedEl);
								}
							}
						});

					calendar.render();
					// $('#calendar').fullCalendar()

					/* ADDING EVENTS */
					var currColor = '#3c8dbc' //Red by default
					// Color chooser button
					$('#color-chooser > li > a').click(function (e) {
							e.preventDefault()
							// Save color
							currColor = $(this).css('color')
							// Add color effect to button
							$('#add-new-event').css({
									'background-color': currColor,
									'border-color'    : currColor
								})
						})
					$('#add-new-event').click(function (e) {
							e.preventDefault()
							// Get value and make sure it is not null
							var val = $('#new-event').val()
							if (val.length == 0) {
								return
							}

							// Create events
							var event = $('<div />')
							event.css({
									'background-color': currColor,
									'border-color'    : currColor,
									'color'           : '#fff'
								}).addClass('external-event')
							event.text(val)
							$('#external-events').prepend(event)

							// Add draggable funtionality
							ini_events(event)

							// Remove event from text input
							$('#new-event').val('')
						})
				})
		</script>
	</body>
</html>

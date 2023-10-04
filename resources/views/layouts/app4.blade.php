<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!--<title>{{ config('app.name', 'ES-iOS GA') }}</title>-->
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

    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="{!! asset('plugins/sweetalert2/sweetalert2.min.css') !!}">
    <!-- Toastr -->
    <link rel="stylesheet" href="{!! asset('plugins/toastr/toastr.min.css') !!}">

    <!-- Styles -->
<!--<link href="{{ asset('css/app.css') }}" rel="stylesheet">-->
</head>
<body>
<div id="app">
    <main class="py-4">
        @yield('content')
    </main>
</div>

<footer class="main-footer" style="margin-left: 0px">
    <strong>Copyright &copy; 2020 - {!! date('Y') !!} <a href="http://203.210.84.185:84" target="_blank">ES-iOS || HRMS</a>.</strong>
    All rights reserved.
    <div class="float-right d-none d-sm-inline-block">
        <b>Version</b> 1.1.0
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
<!-- page script -->
<script>
    $(function () {
        $("#example1").DataTable({
            "responsive": true,
            "autoWidth": false,
            "aLengthMenu": [
                [10,25, 50, 100, 200, -1],
                [10,25, 50, 100, 200, "All"]
            ],
            "paging":true,
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
    $('#summernote').summernote();
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
        format: 'dd/mm/yyyy'
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
</body>
</html>

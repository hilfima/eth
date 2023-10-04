@extends('layouts.app2')

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content">
    @include('flash-message')
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark">Performance & Physico Test</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{!! route('home') !!}">Home</a></li>
                            <li class="breadcrumb-item active">Performance & Test</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">
                                        <i class="fas fa-clipboard-list mr-1"></i>
                                        Performance & Physico Test
                                    </h3>
                                </div><!-- /.card-header -->
                                <div class='containers-fluid text-center'>
                                    <div class='row'>
                                        <div id="menu_tengah" class='col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center' style="color: #16465e; text-align: -webkit-center; padding-left: 0px" >
                                            <div class="card-body">
                                                <div class="tab-content p-0">
                                                    <div class="containers" style="display: inline-block;float: none;">
                                                        <a href="{!! route("fe.pa") !!}" title="Performance">
                                                            <img src="{{ url('dist/img/menu/performance.png') }}" alt="Performance" class="image-icon img-responsive img-fluid" >
                                                            <p>Performance</p>
                                                        </a>
                                                    </div>

                                                    <div class="containers" style="display: inline-block;float: none;">
                                                        <a href="{!! route("fe.rmib") !!}" title="RMIB">
                                                            <img src="{{ url('dist/img/menu/testing.png') }}" alt="RMIB" class="image-icon img-responsive img-fluid" >
                                                            <p>R M I B</p>
                                                        </a>
                                                    </div>

                                                    <div class="containers" style="display: inline-block;float: none;">
                                                        <a href="{!! route("fe.pa") !!}" title="DISC">
                                                            <img src="{{ url('dist/img/menu/checklist.png') }}" alt="DISC" class="image-icon img-responsive img-fluid" >
                                                            <p>D I S C</p>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- /.card-body -->
                            </div>
                            <!-- /.card -->
                        </div>
                    <!-- /.col -->
                    </div>
                </div>
            </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
@endsection

<style type="text/css">
    body {margin:0;}

    .icon-bar {
        position: relative;
        width: 100%;

    }

    .icon-bar a {
        float: left;
        width: 11%;

    }

    .VideoWrapper {
        position: relative;
        padding-bottom: 56.25%; /* 16:9 */
        padding-top: 25px;
        height: 0;
    }
    .VideoWrapper iframe, video, object, embed {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        autoplay:0;
    }

    .video-container {
        position: relative;
        padding-bottom: 56.25%;
        padding-top: 30px;
        height: 0;
        overflow: hidden;
        float: start;
    }

    .video-container iframe,
    .video-container object,
    .video-container embed {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
    }

    .video-wrapper {
        position: relative;
        width: 100%;
        max-width: 100%;
        cursor: pointer;
        margin-top: 0;
        margin-bottom: 0;
        margin-left: 0px;
        margin-right: 0px;
    }

    .floating-box {
        display: inline-block;
        width: 1px;
        height: auto;
        margin: 10px;
        border: 0 solid #73AD21;
        min-width: 5% !important;
    }

    .containers {
        float: inherit;
        position: relative;
        max-width: 10%;
        min-width: 100px !important;
        height: auto;
        padding: 12px;
        cursor: pointer;
        overflow: hidden;
        text-align: center;
    }

    /*
  ##Device = Desktops
  ##Screen = 1281px to higher resolution desktops
*/

    @media (min-width: 1281px) {
        #menu_tengah{
            float: left;
            padding-left: 18%;
        }
    }

    /*
      ##Device = Laptops, Desktops
      ##Screen = B/w 1025px to 1280px
    */

    @media (min-width: 1025px) and (max-width: 1280px) {
        #menu_tengah{
            float: left;
            padding-left: 18%;
        }
    }

    /*
      ##Device = Tablets, Ipads (portrait)
      ##Screen = B/w 768px to 1024px
    */

    @media (min-width: 768px) and (max-width: 1024px) {
        #menu_tengah{
            float: left;
            padding-left: 18%;
        }
    }

    /*
      ##Device = Tablets, Ipads (landscape)
      ##Screen = B/w 768px to 1024px
    */

    @media (min-width: 768px) and (max-width: 1024px) and (orientation: landscape) {
        #menu_tengah{
            float: left;
            padding-left: 18%;
        }
    }

    /*
      ##Device = Low Resolution Tablets, Mobiles (Landscape)
      ##Screen = B/w 481px to 767px
    */

    @media (min-width: 481px) and (max-width: 767px) {
        #menu_tengah{
            float: left;
            padding-left: 10%;
        }
    }

    /*
      ##Device = Most of the Smartphones Mobiles (Portrait)
      ##Screen = B/w 320px to 479px
    */

    @media (min-width: 320px) and (max-width: 480px) {
        #menu_tengah{
            float: left;
            padding-left: 15%;
        }
    }

    @media only screen and (min-width: 768px) and (max-width: 1024px) {
        /* Desktop & Tablet
        img.image-icon {
            position: relative;
            max-width: 100%;
            min-width: 60px;
            min-height: 10px !important;
            display: block;
            cursor: pointer;
            margin: auto;
            padding-left: 20%;
        }
        #menu_tengah{
            float: left;
            padding-left: 5%;
        }*/
    }

    .overlay {
        position: absolute;
        top: 0;
        bottom: 0;
        left: 0;
        right: 0;
        height: 100%;
        width: 90%;
        opacity: 0;
    <!--transition: .3s ease;-->
        display: block;
        min-width: 20px !important;
    }

    .containers:hover .overlay {
        opacity: 1;
        width: 100%;
        display: block;
        min-width: 70px !important;
        min-height: 16px !important;
    }
</style>
@extends('layouts.app2-2')

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <link href="{!! asset('plugins\lightgallery\static\css/main.css') !!}" rel="stylesheet" />
    <link href="{!! asset('plugins\lightgallery\static\css/animate.css') !!}" rel="stylesheet" />
        <link
            href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700"
            rel="stylesheet"
            type="text/css"
        />
		
    <link href="{!! asset('plugins\lightgallery\lightgallery\css/lightgallery.css') !!}" rel="stylesheet" />
    <link href="{!! asset('plugins\lightgallery\lightgallery\css/lg-transitions.css') !!}" rel="stylesheet" />
    @include('flash-message')
    <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark">Gallery</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{!! route('home') !!}">Home</a></li>
                            <li class="breadcrumb-item active">Gallery</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card card-primary">
                            <div class="card-body">
									 <div class="demo-gallery">
                                    @if(!empty($gallery))
                                <ul id="lightgallery" class="list-unstyled row">
                                        @foreach($gallery as $gallerys)
                                    <li
                                        class="col-xs-6 col-sm-4 col-md-3"
                                        data-responsive="{!! asset('dist/img/gallery/'.$gallerys->nama) !!} 375, {!! asset('dist/img/gallery/'.$gallerys->nama) !!} 480, {!! asset('dist/img/gallery/'.$gallerys->nama) !!} 800"
                                        data-src="{!! asset('dist/img/gallery/'.$gallerys->nama) !!}"
                                        data-sub-html=""
                                        data-pinterest-text="Pin it1"
                                        data-tweet-text="share on twitter 1"
                                    >
                                        <a href="">
                                            <img
                                                class="img-responsive"
                                                src="{!! asset('dist/img/gallery/'.$gallerys->nama) !!}"
                                            />
                                            <div class="demo-gallery-poster">
                                                <img
                                                    src="{!! asset('dist/img/gallery/'.$gallerys->nama) !!}"
                                                />
                                            </div>
                                        </a>
                                    </li>
                                
                        
                                            
                                        @endforeach
                                    </ul>
                                    @endif
                            </div>
                                </div>
                            </div>
                        </div>
                        {!! $gallery->links() !!}
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

        <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
        <script src="{!! asset('plugins\lightgallery\static/js/transition.js') !!}"></script>
        <script src="{!! asset('plugins\lightgallery\static/js/wow.js') !!}"></script>
        <script src="{!! asset('plugins\lightgallery\static/js/collapse.js') !!}"></script>
        <script src="{!! asset('plugins\lightgallery\static/js/jquery.sharrre.min.js') !!}"></script>
        <script src="{!! asset('plugins\lightgallery\lightgallery/js/lightgallery.js') !!}"></script>
        <script src="{!! asset('plugins\lightgallery\lightgallery/js/lg-fullscreen.js') !!}"></script>
        <script src="{!! asset('plugins\lightgallery\lightgallery/js/lg-thumbnail.js') !!}"></script>
        <script src="{!! asset('plugins\lightgallery\lightgallery/js/lg-video.js') !!}"></script>
        <script src="{!! asset('plugins\lightgallery\lightgallery/js/lg-autoplay.js') !!}"></script>
        <script src="{!! asset('plugins\lightgallery\lightgallery/js/lg-share.js') !!}"></script>
        <script src="{!! asset('plugins\lightgallery\lightgallery/js/lg-zoom.js') !!}"></script>
        <script src="{!! asset('plugins\lightgallery\lightgallery/js/lg-hash.js') !!}"></script>
        <script src="{!! asset('plugins\lightgallery\lightgallery/js/lg-pager.js') !!}"></script>
        <script src="{!! asset('plugins\lightgallery\lightgallery/js/lg-rotate.js') !!}"></script>
        <script src="{!! asset('plugins\lightgallery\external/jquery.mousewheel.min.js') !!}"></script>
        <script src="{!! asset('plugins\lightgallery\static/js/main.js') !!}"></script>
       
        <script src="https://cdn.jsdelivr.net/picturefill/2.3.1/picturefill.min.js"></script>
      
@endsection
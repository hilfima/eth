<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>ES-iOS HRMS</title>
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Bootstrap core CSS -->
    <link href="{{ asset('landing/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">

    <link rel="icon" type="image/png" href="{!! asset('logo.png') !!}"/>

    <!-- Custom fonts for this template -->
    <link href="{{ asset('landing/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css">
    <link href='https://fonts.googleapis.com/css?family=Kaushan+Script' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Droid+Serif:400,700,400italic,700italic' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Roboto+Slab:400,100,300,700' rel='stylesheet' type='text/css'>

    <!-- Custom styles for this template -->
    <link href="{{ asset('landing/css/agency.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/gallery.css') }}" rel="stylesheet">

    <!-- Add the slick-theme.css if you want default styling -->
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/foundation/5.5.0/css/foundation.css"/>
    <link rel="stylesheet" type="text/css" href="//kenwheeler.github.io/slick/slick/slick-theme.css"/>
    <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/jquery.slick/1.5.9/slick.css"/>

    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-144505606-1"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'UA-144505606-1');
    </script>
    <style>
        /*@import url(https://fonts.googleapis.com/css?family=Open+Sans);font-family: 'Open Sans', sans-serif;*/
        /*@import url(https://fonts.googleapis.com/css?family=Fjalla+One);/*font-family: 'Fjalla One', sans-serif;*/
        body{
            margin:0;
            padding:0;
            background:#fff;
            overflow-x:hidden;
        }
        body::-webkit-scrollbar {
            width: 10px;
        }
        body::-webkit-scrollbar-track {
            -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.3);
            border-radius: 10px;
        }
        body::-webkit-scrollbar-thumb {
            border-radius: 10px;
            -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.5);
        }
        *{
            font-family: 'Open Sans', sans-serif;
        }

        .modal{
            position: absolute;
            display:block;
        }
        .modal-content{
            height: auto;
            width: 20em;
            left: 25em;
            top: 0.5em;
            /*background: rgba(0,0,0,0.5);*/
            padding: 10px;
            filter: alpha(opacity=50);
            opacity: 0.9;
        }
        #wrapper-inner-modal{
            height: 24.5em auto;
            margin: 5px;
            background: rgba(255,255,255,0.85);
            border-radius: 5px;
            padding: 15px 10px 10px 10px;
        }
        #logo-wrapper{
            width: 90%;
        }
        #wrapper-form{
            padding: 10px 0 10px 0;
        }
        .input-text{
            border: none;
            outline: none;
            padding: 5px;
            margin: 5px 0 5px 0;
            width: 230px;
            height: 35px;
        }
        #checkbox-st{
            text-align: left;
            top:-12px;
            left: 10px;
        }
        .submit{
            border-radius: 4px;
            margin-top: 5px;
            border: 0;
            background: #25ca7b;
            outline: none;
            box-shadow: none;
            text-transform: uppercase;
            font-weight: bold;
            height: 2.7em;
            width: 10em;
            color: #fff;
            -webkit-transition:all 0.2s ease;
            -moz-transition:all 0.2s ease;
            transition:all 0.2s ease;
        }
        .submit:hover{
            background: #f42a2a;
        }
        #wrapper-container-quotes{
            background: #6d3878;
            border-bottom: 2px solid #FFF;
            padding: 8px 0 8px 0;
        }
        #quote{
            color: #fff;
            width: 65%;
            font-size: 12pt;
            /*margin-bottom: 15px;*/
        }
        #from{
            margin-bottom: 20px;
            font-size: 10pt;
            color: #fff000;
        }
        #warning{
            /*padding: 10px;*/
            text-align: center;
            font-size: 10pt;
            color: #373737;
        }
        #wrapper-address{
            height: 3.15em;
            overflow: hidden;
            background:#6d3878;
            padding: 10px;
        }
        .txt{
            color: #fff;
            font-size: 7pt;
        }
        #copy{
            color: #f2f2f2;
        }
        #link{
            color: #f2f2f2;
        }
        #log{
            display: none;
        }
        #mobile-indicator {
            display: none;
        }
        /* xs =  phone <768 and down */
        @media (max-width: 768px) {
            #mobile-indicator {
                display: block;
            }
            #wrapper-carousel{
                height: 9.5em;
            }
            #wrapper-container-quotes{
                padding: 5px 0 5px 0;
            }
            #quote{
                width: 100%;
                font-size: 15pt;
                margin-bottom: 8px;
            }
            #from{
                margin-bottom: 15px;
                font-size: 8pt;
            }
            #warning{
                padding: 6px;
                font-size: 7pt;
            }
            .txt{
                color: #fff;
                font-size: 6pt;
            }
            .modal{
                display: none;
            }
            #wrapper-address{
                padding: 5px;
            }
            #log{
                display: block;
                padding: 30px 0 30px 0;
                border-top: 1px solid #ccc;
            }
            #warning{
                padding:10px 0 0 0;
            }
            #wrapper-login-sec{
                background: #eeeeee;
                padding: 10px;
            }
            .input-text-sec{
                border: none;
                outline: none;
                padding: 3px;
                margin:10px 0 3px 0;
                width: 180px;
                height: 30px;
            }
            #checkbox-nd{
                top:-0.5em;
                left: -2em;
            }
            .submit-sec{
                margin-bottom: 1em;
                border: 0;
                background: #e20c0c;
                outline: none;
                box-shadow: none;
                text-transform: uppercase;
                font-weight: bold;
                height: 2.5em;
                width: 10em;
                color: #fff;
                -webkit-transition:all 0.2s ease;
                -moz-transition:all 0.2s ease;
                transition:all 0.2s ease;
            }
            .submit-sec:hover{
                background: #f42a2a;
            }
            #logo-wrapper-sec{
                width: 30%;
                margin: 10px 0 10px 0;
            }
            #mylogo-sec{
                width: 100%;
            }
            #wrapper-container-quotes,#warning,#wrapper-address{
                width:100%;
            }
        }
        /* sm =  tablet 768 and up */
        @media (min-width: 768px) and (max-width: 992px) {
            #wrapper-carousel{
                height: 26em;
            }
            #wrapper-container-quotes{
                padding: 5px 0 5px 0;
            }
            #quote{
                width: 50%;
                font-size: 15pt;
                margin-bottom: 8px;
            }
            #from{
                margin-bottom: 15px;
                font-size: 8pt;
            }
            #warning{
                padding: 6px;
                font-size: 7pt;
            }
            .txt{
                color: #fff;
                font-size: 6pt;
            }
            .modal-content{
                height: auto;
                width: 20em;
                left: 10em;
                top: 0.5em;
                padding: 10px auto;
            }
            #wrapper-inner-modal{
                height: 21em auto;
                margin: 5px;
                padding: 10px 5px 10px 5px;
            }
            .input-text{
                padding: 5px;
                margin: 3px 0 3px 0;
                width: 175px;
                height: 25px;
            }
            #checkbox-st{
                left: 8px;
            }
            .submit{
                margin-top: 3px;
                height: 2.2em;
                width: 8em;
                font-size: 9pt;
            }
            #wrapper-container-quotes,#warning,#wrapper-address{
                width:100%;
            }
        }
        /* md =  dekstop 992 and up */
        @media (min-width: 992px) and (max-width: 1200px) {
            #wrapper-carousel{
                height: 28em;
            }
            #wrapper-container-quotes{
                padding: 5px 0 5px 0;
            }
            #quote{
                width: 55%;
                font-size: 16pt;
                margin-bottom: 8px;
            }
            #from{
                margin-bottom: 15px;
                font-size: 10pt;
            }
            #warning{
                padding: 8px;
                font-size: 9pt;
            }
            .txt{
                color: #fff;
                font-size: 6pt;
            }
            .modal-content{
                height: auto;
                width: 20em;
                left: 25em;
                top: 0.5em;
                padding: 10px;
            }
            #wrapper-inner-modal{
                height: 23.5em auto;
                margin: 5px;
                padding: 15px 8px 10px 8px;
            }
            .input-text{
                padding: 5px;
                margin: 3px 0 3px 0;
                width: 200px;
                height: 30px;
            }
            #checkbox-st{
                left: 8px;
            }
            .submit{
                height: 2.8em;
                width: 10em;
            }
        }
        /* lg =  HD 1200 and up */
        @media (min-width: 1200px) {

        }
    </style>
</head>

<body id="page-top">

<div class="row" id="about" style="margin-right: 0; margin-left: 0; max-width: 100%">
    <div class="container-fluid">
        <div id="carouselExampleControls" class="carousel slide" data-ride="carousel">
            <div class="carousel-inner">
                @if(!empty($slider))
                    @foreach( $slider as $slider )
                        <div class="carousel-item {{ $loop->first ? ' active' : '' }}" >
                            <img class="d-block w-100" src="{!! URL::asset("dist/img/landingpage/". $slider->file) !!}" alt="{{ $slider->nama }}">
                            <div class="carousel-caption" style="color: #000">
                                <h2></h2>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
            <!--<a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="sr-only">Previous</span>
            </a>
            <a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="sr-only">Next</span>
            </a>-->
        </div>
    </div>
</div>

<div class="modal" id="myModal" role="dialog">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div id="wrapper-inner-modal">
                <div class="modal-header" align="center">
                    <div id="logo-wrapper">
                        <img src="{!! asset('dist/img/logo/logoethica.png') !!}" style="height:50px" id="mylogo2">
                    </div>
                </div>
                <div class="modal-body">
                    <div id="wrapper-form" style="height: auto;" align="center">
                        <p name="error" style="color: rgb(219, 16, 16); font-size: 8pt; margin: 0px; padding: 0px; text-align: left; display: none;">*</p>
                        <form name="formLogin" action="{{ route('login') }}" method="post" novalidate="novalidate">
                            @csrf
                            @if (session('error'))
                                <div class="alert alert-danger">{{ session('error') }}</div>
                            @endif
                            <fieldset>
                                <label class="block clearfix">
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                                        </div>
                                        <input type="text" class="form-control" placeholder="Username" name="username" id="username" required>
                                    </div>
                                </label>

                                <label class="block clearfix">
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                        </div>
                                        <input type="password" class="form-control" placeholder="Password" name="password" id="password" required>
                                    </div>
                                </label>

                                <div class="clearfix">
                                    <button type="submit" name="submit" class="btn btn-success" autocomplete="none">Login <i class="fa fa-key"></i></button>
                                </div>

                            </fieldset>
                        </form>
                        <div class="social-or-login center">
                            <span class="bigger-110"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!--
<div class="container">
    <div class="row justify-content-center mt-5">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-transparent mb-0"><h5 class="text-center">Please <span class="font-weight-bold text-primary">LOGIN</span></h5></div>
                <div class="card-body">
                    <form action="">
                        <div class="form-group">
                            <input type="text" name="" class="form-control" placeholder="Username">
                        </div>
                        <div class="form-group">
                            <input type="text" name="" class="form-control" placeholder="Password">
                        </div>
                        <div class="form-group custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="customControlAutosizing">
                            <label class="custom-control-label" for="customControlAutosizing">Remember me</label>
                        </div>
                        <div class="form-group">
                            <input type="submit" name="" value="Login" class="btn btn-primary btn-block">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
-->
<!-- Footer -->
<footer class="footer" style="background-color: #FFFAE3">
    <div class="row">
        <div class="col-sm-1"></div>
        <div class="col-sm-2">
            <img src="dist/img/logo/logoethica.png" alt="" style="width: 100px">
        </div>
        <div class="col-sm-2">
            <img src="dist/img/logo/seply.png" alt="" style="width: 100px">
        </div>
        <div class="col-sm-2">
            <img src="dist/img/logo/kagumi.png" alt="" style="width: 100px">
        </div>
        <div class="col-sm-2">
            <img src="dist/img/logo/kahfi.png" alt="" style="width: 100px">
        </div>
        <div class="col-sm-2">
            <img src="dist/img/logo/majory.png" alt="" style="width: 100px">
        </div>
        <div class="col-sm-1"></div>
        <!--<div class="col-sm-2">
            <img src="dist/img/logo/ELFA.png" alt="" style="width: 100px">
        </div>-->
    </div>
</footer>

<!-- Bootstrap core JavaScript -->
<script src="{{ asset('landing/vendor/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('landing/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

<!-- Plugin JavaScript -->
<script src="{{ asset('landing/vendor/jquery-easing/jquery.easing.min.js') }}"></script>

<!-- Contact form JavaScript -->
<script src="{{ asset('landing/js/jqBootstrapValidation.js') }}"></script>
<script src="{{ asset('landing/js/contact_me.js') }}"></script>

<!-- Custom scripts for this template -->
<script src="{{ asset('landing/js/agency.min.js') }}"></script>

<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.3/modernizr.min.js"></script>
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<script type="text/javascript" src="//cdn.jsdelivr.net/jquery.slick/1.5.9/slick.min.js"></script>
<script type="text/javascript" async src="//cdn.youracclaim.com/assets/utilities/embed.js"></script>
<script type="text/javascript" async src="//cdn.youracclaim.com/assets/utilities/embed.js"></script>
<script type="text/javascript" async src="//cdn.youracclaim.com/assets/utilities/embed.js"></script>
<script type="text/javascript" async src="//cdn.youracclaim.com/assets/utilities/embed.js"></script>

<script type="text/javascript">
    $('.slider-single').slick({
        slidesToShow: 1,
        slidesToScroll: 1,
        arrows: true,
        fade: false,
        adaptiveHeight: true,
        infinite: false,
        useTransform: true,
        speed: 400,
        cssEase: 'cubic-bezier(0.77, 0, 0.18, 1)',
    });

    $('.slider-nav')
        .on('init', function(event, slick) {
            $('.slider-nav .slick-slide.slick-current').addClass('is-active');
        })
        .slick({
            slidesToShow: 10,
            slidesToScroll: 10,
            dots: false,
            focusOnSelect: false,
            infinite: false,
            responsive: [{
                breakpoint: 1024,
                settings: {
                    slidesToShow: 5,
                    slidesToScroll: 5,
                }
            }, {
                breakpoint: 640,
                settings: {
                    slidesToShow: 4,
                    slidesToScroll: 4,
                }
            }, {
                breakpoint: 420,
                settings: {
                    slidesToShow: 3,
                    slidesToScroll: 3,
                }
            }]
        });

    $('.slider-single').on('afterChange', function(event, slick, currentSlide) {
        $('.slider-nav').slick('slickGoTo', currentSlide);
        var currrentNavSlideElem = '.slider-nav .slick-slide[data-slick-index="' + currentSlide + '"]';
        $('.slider-nav .slick-slide.is-active').removeClass('is-active');
        $(currrentNavSlideElem).addClass('is-active');
    });

    $('.slider-nav').on('click', '.slick-slide', function(event) {
        event.preventDefault();
        var goToSingleSlide = $(this).data('slick-index');

        $('.slider-single').slick('slickGoTo', goToSingleSlide);
    });
</script>

<script type="text/javascript">
    $(document).ready( function() {
        $('#myCarousel').carousel({
            interval:   4000
        });

        var clickEvent = false;
        $('#myCarousel').on('click', '.nav a', function() {
            clickEvent = true;
            $('.nav li').removeClass('active');
            $(this).parent().addClass('active');
        }).on('slid.bs.carousel', function(e) {
            if(!clickEvent) {
                var count = $('.nav').children().length -1;
                var current = $('.nav li.active');
                current.removeClass('active').next().addClass('active');
                var id = parseInt(current.data('slide-to'));
                if(count == id) {
                    $('.nav li').first().addClass('active');
                }
            }
            clickEvent = false;
        });
    });

    $(document).ready(function() {
        $('#pinBoot').pinterest_grid({
            no_columns: 4,
            padding_x: 10,
            padding_y: 10,
            margin_bottom: 50,
            single_column_breakpoint: 700
        });
    });

    (function ($, window, document, undefined) {
        var pluginName = 'pinterest_grid',
            defaults = {
                padding_x: 10,
                padding_y: 10,
                no_columns: 3,
                margin_bottom: 50,
                single_column_breakpoint: 700
            },
            columns,
            $article,
            article_width;

        function Plugin(element, options) {
            this.element = element;
            this.options = $.extend({}, defaults, options) ;
            this._defaults = defaults;
            this._name = pluginName;
            this.init();
        }

        Plugin.prototype.init = function () {
            var self = this,
                resize_finish;

            $(window).resize(function() {
                clearTimeout(resize_finish);
                resize_finish = setTimeout( function () {
                    self.make_layout_change(self);
                }, 11);
            });

            self.make_layout_change(self);

            setTimeout(function() {
                $(window).resize();
            }, 500);
        };

        Plugin.prototype.calculate = function (single_column_mode) {
            var self = this,
                tallest = 0,
                row = 0,
                $container = $(this.element),
                container_width = $container.width();
            $article = $(this.element).children();

            if(single_column_mode === true) {
                article_width = $container.width() - self.options.padding_x;
            } else {
                article_width = ($container.width() - self.options.padding_x * self.options.no_columns) / self.options.no_columns;
            }

            $article.each(function() {
                $(this).css('width', article_width);
            });

            columns = self.options.no_columns;

            $article.each(function(index) {
                var current_column,
                    left_out = 0,
                    top = 0,
                    $this = $(this),
                    prevAll = $this.prevAll(),
                    tallest = 0;

                if(single_column_mode === false) {
                    current_column = (index % columns);
                } else {
                    current_column = 0;
                }

                for(var t = 0; t < columns; t++) {
                    $this.removeClass('c'+t);
                }

                if(index % columns === 0) {
                    row++;
                }

                $this.addClass('c' + current_column);
                $this.addClass('r' + row);

                prevAll.each(function(index) {
                    if($(this).hasClass('c' + current_column)) {
                        top += $(this).outerHeight() + self.options.padding_y;
                    }
                });

                if(single_column_mode === true) {
                    left_out = 0;
                } else {
                    left_out = (index % columns) * (article_width + self.options.padding_x);
                }

                $this.css({
                    'left': left_out,
                    'top' : top
                });
            });

            this.tallest($container);
            $(window).resize();
        };

        Plugin.prototype.tallest = function (_container) {
            var column_heights = [],
                largest = 0;

            for(var z = 0; z < columns; z++) {
                var temp_height = 0;
                _container.find('.c'+z).each(function() {
                    temp_height += $(this).outerHeight();
                });
                column_heights[z] = temp_height;
            }

            largest = Math.max.apply(Math, column_heights);
            _container.css('height', largest + (this.options.padding_y + this.options.margin_bottom));
        };

        Plugin.prototype.make_layout_change = function (_self) {
            if($(window).width() < _self.options.single_column_breakpoint) {
                _self.calculate(true);
            } else {
                _self.calculate(false);
            }
        };

        $.fn[pluginName] = function (options) {
            return this.each(function () {
                if (!$.data(this, 'plugin_' + pluginName)) {
                    $.data(this, 'plugin_' + pluginName,
                        new Plugin(this, options));
                }
            });
        }

    })(jQuery, window, document);
</script>

</body>

</html>

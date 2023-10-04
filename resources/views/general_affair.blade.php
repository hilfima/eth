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
        .dropbtn {
            background-color: transparent;
            color: purple;
            padding: 16px;
            font-size: 16px;
            border: none;
        }

        .dropdown {
            position: relative;
            display: inline-block;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            background-color: #fff;
            min-width: 160px;
            box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
            z-index: 1;
        }

        .dropdown-content a {
            color: black;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
        }

        .dropdown-content a:hover {background-color: whitesmoke;}

        .dropdown:hover .dropdown-content {display: block;}

        .dropdown:hover .dropbtn {background-color: purple;}
    </style>
</head>

<body id="page-top">

<!-- Navigation -->
<nav class="navbar navbar-expand-lg navbar-dark fixed-top" style="background-color: #ffffff; " id="mainNav">
    <div class="container-fluid">
        <a class="navbar-brand js-scroll-trigger" href="#page-top">
            <img class="img-fluid" src="{!! asset('logoethica.png') !!}" alt="" style="height: 40px;">
        </a>
        <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
            Menu
            <i class="fas fa-bars"></i>
        </button>
        <div class="collapse navbar-collapse" id="navbarResponsive">
            <ul class="navbar-nav text-uppercase ml-auto">
                <li class="nav-item">
                    <a class="nav-link js-scroll-trigger" href="#about" style="color: purple"><b>Tentang Kami</b></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link js-scroll-trigger" style="color: purple" href="#properti"><b>Properti</b></a>
                </li>
                <!--<div class="dropdown">
                    <li class="nav-item"><a class="nav-link js-scroll-trigger" href="#" style="color: purple">Foundation</a>
                    <div class="dropdown-content">
                        <a href="#about" style="color: purple">About</a>
                        <a href="#core_value" style="color: purple">Mission & Core Value</a>
                    </div>
                    </li>
                </div>-->
                <li class="nav-item">
                    <a class="nav-link js-scroll-trigger" href="#budaya" style="color: purple"><b>Budaya</b></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link js-scroll-trigger" href="#brand" style="color: purple"><b>Brand</b></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link js-scroll-trigger" href="#" style="color: purple"><b>Karir</b></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link js-scroll-trigger" href="#gallery" style="color: purple"><b>Gallery</b></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link js-scroll-trigger" href="{!! route('ga') !!}" style="color: purple"><b>General Affair</b></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link js-scroll-trigger" href="{!! route('login') !!}" style="color: purple"><b>Login</b></a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Header -->
<!--<header class="masthead bg-light">
    <div class="container-fluid">
        <div class="intro-text">
            <div class="intro-lead-in" style="color: #000">Welcome Good People!</div>
            <div class="intro-heading" style="color: #000">Your One Stop People Management Solutions</div>
        </div>
        <a class="btn btn-primary btn-xl text-uppercase js-scroll-trigger" style="background-color: purple" href="#services">Tell Me More</a>
    </div>
</header>-->

<!-- Properti -->
<section class="page-section" id="properti">
    <div class="row" style="margin-right: 0; margin-left: 0; max-width: 100%">
        <div class="container-fluid">
            <hr>
            <div class="row">
                <div class="col-lg-12 text-center">
                    <h3 class="section-heading text-uppercase"><b>FORM PENGAJUAN GENERAL AFFAIR</b></h3>
                    <h3 class="section-heading text-uppercase"><b>ETHICA FASHION GROUP</b></h3>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-lg-4 text-center">
                    <p><a class="btn btn-md" href="{!! route('list_ga') !!}" style="background-color: purple; color: white"><b>PENGAJUAN PEMBELIAN</b></a></p>
                </div>
                <div class="col-lg-4 text-center">
                    <p><a class="btn btn-md" href="{!! route('ga_pinjaman') !!}" style="background-color: purple; color: white"><b>PENGAJUAN PINJAMAN</b></a></p>
                </div>
                <div class="col-lg-4 text-center">
                    <p><a class="btn btn-md" href="{!! route('ga_perdin') !!}" style="background-color: purple; color: white"><b>PENGAJUAN PERJALANAN DINAS</b></a></p>
                </div>
            </div>
            <hr>
            <div class="row text-center">
                @if(!empty($lokasi))
                    @foreach($lokasi as $lokasi)
                        <div class="col-md-3">
                      <span class="fa-stack fa-4x">
                        <!--<i class="fas fa-circle fa-stack-2x text-primary"></i>
                        <i class="fas fa-users fa-stack-1x fa-inverse"></i>-->
                        <img class="img-fluid img-responsive" src="{!! asset('dist/img/logo/'.$lokasi->logo) !!}" alt="">
                      </span>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
</section>



<!-- Footer -->
<footer class="footer" style="background-color: purple">
    <div class="row" style="margin-right: 0; margin-left: 0; max-width: 100%">
        <div class="container-fluid" style="width: 100%;">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <span style="font-size: small;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,'Helvetica Neue',Arial,sans-serif,'Apple Color Emoji','Segoe UI Emoji','Segoe UI Symbol','Noto Color Emoji'; color: white">Copyright &copy; PT. REA ARTA</span><br>
                    <span class="copyright" style="font-size: small; color: white;">ES-iOS 2021</span><br>
                    <span class="copyright" style="font-size: small; color: white;">Office Operational, Jl. Kurdi Barat III, Kavling I</span>
                </div>
                <!--<div class="col-md-4">
                    <div data-iframe-width="150" data-iframe-height="270" data-share-badge-id="30f5678c-3073-4669-8586-b3f90ab90ded"></div>
                </div>-->
                <div class="col-md-6">
                    <ul class="list-inline quicklinks">
                        <li class="list-inline-item">
                            <a href="#">Privacy Policy</a>
                        </li>
                        <li class="list-inline-item">
                            <a href="#">Terms of Use</a>
                        </li>
                        <li class="list-inline-item">
                            <a href="{{ route('login') }}">Admin</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
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

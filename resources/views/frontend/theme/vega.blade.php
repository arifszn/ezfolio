<!--

=========================================================
* startbootstrap-resume - v6.0.1
=========================================================

* Product Page: https://startbootstrap.com/themes/resume/
* Copyright 2013-2020 Start Bootstrap LLC (https://startbootstrap.com)
* Licensed under MIT (https://github.com/StartBootstrap/startbootstrap-resume/blob/master/LICENSE)

=========================================================

* The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

-->

@php
    $accentColor = $portfolioConfig['accentColor'];;
    $accentColorRGB = Utils::getRgbValue($accentColor);
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
    @include('common.googleAnalytics')
    @if (!empty($portfolioConfig['script']['header']) && $portfolioConfig['script']['header'] != '')
        <script>
            {!!$portfolioConfig['script']['header']!!}
        </script>
    @endif
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta property="og:title" content="{{$portfolioConfig['seo']['title']}}"/>
    <meta property="title" content="{{$portfolioConfig['seo']['title']}}"/>
    <meta name="description" content="{{$portfolioConfig['seo']['description']}}" />
    <meta property="og:description" content="{{$portfolioConfig['seo']['description']}}"/>
    <meta name="author" content="{{$portfolioConfig['seo']['author']}}" />
    <meta property="og:image" content="{{asset($portfolioConfig['seo']['image'])}}" />
    <meta property="og:image:secure_url" content="{{asset($portfolioConfig['seo']['image'])}}" />
    <title>{{$about->name}}</title>
    <link rel="shortcut icon" type="image/x-icon"  href="{{ Utils::getFavicon() }}">
    <link href="https://fonts.googleapis.com/css?family=Saira+Extra+Condensed:500,700" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/common/lib/mdi-icon/css/materialdesignicons.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/common/lib/fontawesome/css/all.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/common/lib/aos/aos.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/common/lib/iziToast/css/iziToast.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/themes/vega/css/styles.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/themes/vega/css/custom.css') }}" rel="stylesheet" />
    <style>
        :root {
          --z-accent-color: {{$accentColor}};
        }
        .bg-primary, .progress-bar {
            background-color: {{$accentColor.' !important'}};
        }

        a {
            color: {{$accentColor}};
        }

        a:hover {
            color: rgba({{$accentColorRGB}}, .7);
        }

        .form-control:focus {
            border-color: rgba({{$accentColorRGB}}, .5) !important;
            box-shadow: none;
        }

        .text-primary {
            color: {{$accentColor.' !important'}};
        }

        .social-icons .social-icon:hover {
            text-decoration: none;
            background-color: {{$accentColor.' !important'}};
        }
    </style>
</head>
<body id="page-top">
    @include('common.preloader2')
    <!-- Navigation-->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary fixed-top" id="sideNav">
        <a class="navbar-brand js-scroll-trigger" href="#page-top">
            <span class="d-block d-lg-none">{{ $about->name }}</span>
            <span class="d-none d-lg-block"><img class="lazy img-fluid img-profile rounded-circle mx-auto mb-2" data-src="{{asset($about->avatar)}}" src="{{asset('assets/common/img/lazyloader.gif')}}" alt="" /></span>
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav">
                @if ($portfolioConfig['visibility']['about'])
                    <li class="nav-item"><a class="nav-link js-scroll-trigger" href="#about">About</a></li>
                @endif
                @if ($portfolioConfig['visibility']['experiences'])
                    <li class="nav-item"><a class="nav-link js-scroll-trigger" href="#experience">Experience</a></li>
                @endif
                @if ($portfolioConfig['visibility']['education'])
                    <li class="nav-item"><a class="nav-link js-scroll-trigger" href="#education">Education</a></li>
                @endif
                @if ($portfolioConfig['visibility']['skills'])
                    <li class="nav-item"><a class="nav-link js-scroll-trigger" href="#skills">Skills</a></li>
                @endif
                @if ($portfolioConfig['visibility']['projects'])
                    <li class="nav-item"><a class="nav-link js-scroll-trigger" href="#projects">Projects</a></li>
                @endif
                @if ($portfolioConfig['visibility']['services'])
                    <li class="nav-item"><a class="nav-link js-scroll-trigger" href="#services">Services</a></li>
                @endif
                @if ($portfolioConfig['visibility']['contact'])
                    <li class="nav-item"><a class="nav-link js-scroll-trigger" href="#contact">Contact</a></li>
                @endif
            </ul>
        </div>
    </nav>
    <!-- Page Content-->
    <div class="container-fluid p-0">
        @if ($portfolioConfig['visibility']['about'])
            <!-- About-->
            <section class="resume-section" id="about">
                <div class="resume-section-content">
                    <h2 class="mb-0" data-aos="fade-up" data-aos-anchor-placement="top-bottom">
                        {{ $about->name }}
                    </h2>
                    <div class="subheading mb-3">
                        {{ $about->address ? $about->address . ' · ' : '' }} {{ $about->phone ? $about->phone . ' · ' : '' }}
                        <a href="mailto:{{ $about->email }}">{{ $about->email }}</a>
                    </div>
                    <p>
                        <span id="typed-strings"></span>
                    </p>
                    @if ($portfolioConfig['visibility']['cv'])
                        <div class="mb-3">
                            <a href="{{$about->cv}}" class="btn btn-light btn-sm" download>Download CV</a>
                        </div>
                    @endif
                    <p class="lead mb-5">{{ $about->description }}</p>
                        @if ($about->social_links)
                        <div class="social-icons" data-aos="zoom-in">
                            @foreach (json_decode($about->social_links) as $social)
                                <a class="social-icon" href="{{$social->link}}" target=="_blank">
                                    <i class="{{$social->iconClass}}"></i>
                                </a>
                            @endforeach
                        </div>
                        @endif
                </div>
            </section>
            <hr class="m-0" />
        @endif
        @if ($portfolioConfig['visibility']['experiences'])
            <!-- Experience-->
            <section class="resume-section" id="experience">
                <div class="resume-section-content">
                    <h2 class="mb-5">Experience</h2>
                    @if ($experiences)
                        @foreach ($experiences as $experience)
                            <div class="d-flex flex-column flex-md-row justify-content-between mb-5" data-aos="fade-up">
                                <div class="flex-grow-1">
                                    {!! $experience->position ? '<h3 class="mb-0">'.$experience->position.'</h3>' : '' !!}
                                    {!! $experience->company ? '<div class="subheading mb-3">'.$experience->company.'</div>' : '' !!}
                                    {!! $experience->details ? '<p>'.$experience->details.'</p>' : '' !!} 
                                </div>
                                {!! $experience->period ? '<div class="flex-shrink-0"><span class="text-primary">'.$experience->period.'</span></div>' : '' !!} 
                            </div>
                        @endforeach
                    @endif
                </div>
            </section>
            <hr class="m-0" />
        @endif
        @if ($portfolioConfig['visibility']['education'])
            <!-- Education-->
            <section class="resume-section" id="education">
                <div class="resume-section-content">
                    <h2 class="mb-5">Education</h2>
                    @if ($education)
                        @foreach ($education as $value)
                            <div class="d-flex flex-column flex-md-row justify-content-between mb-5" data-aos="fade-up">
                                <div class="flex-grow-1">
                                    {!! $value->institution ? '<h3 class="mb-0">'.$value->institution.'</h3>' : '' !!}
                                    {!! $value->degree ? '<div class="subheading mb-3">'.$value->degree.'</div>' : '' !!}
                                    {!! $value->department ? '<div>'.$value->department.'</div>' : '' !!}
                                    {!! $value->cgpa ? '<p>'.$value->cgpa.'</p>' : '' !!}
                                </div>
                                <div class="flex-shrink-0"><span class="text-primary">{!! $value->period ? $value->period : '' !!}</span></div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </section>
            <hr class="m-0" />
        @endif
        @if ($portfolioConfig['visibility']['skills'])
            <!-- Skills-->
            <section class="resume-section" id="skills">
                <div class="resume-section-content">
                    <h2 class="mb-5">Skills</h2>
                    <div class="row"> 
                         @if (!empty($skills))
                            @foreach ($skills as $skill)
                                <div class="col-6 col-md-4 col-lg-2" data-aos="zoom-in">
                                    <div class="card z-hover skill-card text-center">
                                        <div class="card-body{{ !(int)$portfolioConfig['visibility']['skillProficiency'] ? ' center-this' : '' }}">
                                            @if ((int)$portfolioConfig['visibility']['skillProficiency'])
                                                <div class="skill-progress-wrapper mb-2">
                                                    <div class="progress" data-percentage="{{$skill->proficiency}}">
                                                        <span class="progress-left">
                                                            <span class="progress-bar"></span>
                                                        </span>
                                                        <span class="progress-right">
                                                            <span class="progress-bar"></span>
                                                        </span>
                                                        <div class="progress-value">
                                                            <div>
                                                                {{$skill->proficiency}}%
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div> 
                                            @endif
                                            {{$skill->name}}
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </section>
            <hr class="m-0" />
        @endif
        @if ($portfolioConfig['visibility']['projects'])
            <!-- projects-->
            <section class="resume-section" id="projects">
                <div class="resume-section-content">
                    <h2 class="mb-5">Projects</h2>
                    <div 
                        id="react-project-root" 
                        data-accentcolor="{{$accentColor}}" 
                        data-demomode="{{$demoMode}}"
                    />
                    <div class="mb-5"></div>
                    <hr class="m-0" />
                </div>
            </section>
        @endif
        @if ($portfolioConfig['visibility']['services'])
            <!-- Services-->
            <section class="resume-section" id="services">
                <div class="resume-section-content">
                    <h2 class="mb-5">Services</h2>
                    <div class="row"> 
                         @if (!empty($services))
                            @foreach ($services as $service)
                                <div class="col-lg-4 col-md-6 d-flex align-items-stretch icon-box-wrapper" data-aos="zoom-in">
                                    <div class="icon-box iconbox-blue z-hover">
                                        <div class="icon">
                                            <svg width="100" height="100" viewBox="0 0 600 600" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke="none" stroke-width="0" fill="#f5f5f5" d="M300,521.0016835830174C376.1290562159157,517.8887921683347,466.0731472004068,529.7835943286574,510.70327084640275,468.03025145048787C554.3714126377745,407.6079735673963,508.03601936045806,328.9844924480964,491.2728898941984,256.3432110539036C474.5976632858925,184.082847569629,479.9380746630129,96.60480741107993,416.23090153303,58.64404602377083C348.86323505073057,18.502131276798302,261.93793281208167,40.57373210992963,193.5410806939664,78.93577620505333C130.42746243093433,114.334589627462,98.30271207620316,179.96522072025542,76.75703585869454,249.04625023123273C51.97151888228291,328.5150500222984,13.704378332031375,421.85034740162234,66.52175969318436,486.19268352777647C119.04800174914682,550.1803526380478,217.28368757567262,524.383925680826,300,521.0016835830174"></path>
                                            </svg>
                                            <i class="{{$service->icon}}"></i>
                                        </div>
                                        <h4 class="my-3 text-muted">{{$service->title}}</h4>
                                        <p>{{$service->details}}</p>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </section>
            <hr class="m-0" />
        @endif
        @if ($portfolioConfig['visibility']['contact'])
            <!-- contact-->
            <section class="resume-section" id="contact" data-aos="zoom-in">
                <div class="resume-section-content">
                    <h2 class="mb-5">Contact</h2>
                    <div class="card border-0 mb-0">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card-body">
                                    <form action="#" id="contact-me-form" method="POST">
                                        @csrf
                                        <div class="p pb-3">
                                            <strong>Send Me A Message</strong>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col">
                                                <div class="form-group">
                                                    <input class="form-control" type="text" id="name" name="name" placeholder="Name" required="">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col">
                                                <div class="form-group">
                                                    <input class="form-control" type="text" id="subject" name="subject" placeholder="Subject" required="">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col">
                                                <div class="form-group">
                                                    <input class="form-control" type="email" id="email" name="email" placeholder="E-mail" required="">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col">
                                                <div class="form-group">
                                                    <textarea class="form-control" id="body" name="body" placeholder="Body" required=""></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col">
                                                <button type="submit" class="submit-button">Send Message</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card-body">
                                    @if ($about->address)
                                        <p class="mb-0"><strong>Address</strong></p>
                                        <p class="pb-2 text-muted">{{$about->address }}</p>
                                    @endif
                                    @if ($about->email)
                                        <p class="mb-0"><strong>Email</strong></p>
                                        <p class="pb-2 text-muted">{{$about->email }}</p>
                                    @endif
                                    @if ($about->phone)
                                        <p class="mb-0"><strong>Phone</strong></p>
                                        <p class="pb-2 text-muted">{{$about->phone }}</p>
                                    @endif
                                    @if ($portfolioConfig['visibility']['cv'])
                                        <p class="mb-0"><strong>CV</strong></p>
                                        <div>
                                            <a href="{{$about->cv}}" class="text-muted" download>
                                                <svg stroke="currentColor" fill="currentColor" stroke-width="0" version="1.1" viewBox="0 0 16 16" height="1.5rem" width="1.5rem" xmlns="http://www.w3.org/2000/svg">
                                                    <path
                                                        d="M13.156 9.211c-0.213-0.21-0.686-0.321-1.406-0.331-0.487-0.005-1.073 0.038-1.69 0.124-0.276-0.159-0.561-0.333-0.784-0.542-0.601-0.561-1.103-1.34-1.415-2.197 0.020-0.080 0.038-0.15 0.054-0.222 0 0 0.339-1.923 0.249-2.573-0.012-0.089-0.020-0.115-0.044-0.184l-0.029-0.076c-0.092-0.212-0.273-0.437-0.556-0.425l-0.171-0.005c-0.316 0-0.573 0.161-0.64 0.403-0.205 0.757 0.007 1.889 0.39 3.355l-0.098 0.239c-0.275 0.67-0.619 1.345-0.923 1.94l-0.040 0.077c-0.32 0.626-0.61 1.157-0.873 1.607l-0.271 0.144c-0.020 0.010-0.485 0.257-0.594 0.323-0.926 0.553-1.539 1.18-1.641 1.678-0.032 0.159-0.008 0.362 0.156 0.456l0.263 0.132c0.114 0.057 0.234 0.086 0.357 0.086 0.659 0 1.425-0.821 2.48-2.662 1.218-0.396 2.604-0.726 3.819-0.908 0.926 0.521 2.065 0.883 2.783 0.883 0.128 0 0.238-0.012 0.327-0.036 0.138-0.037 0.254-0.115 0.325-0.222 0.139-0.21 0.168-0.499 0.13-0.795-0.011-0.088-0.081-0.196-0.157-0.271zM3.307 12.72c0.12-0.329 0.596-0.979 1.3-1.556 0.044-0.036 0.153-0.138 0.253-0.233-0.736 1.174-1.229 1.642-1.553 1.788zM7.476 3.12c0.212 0 0.333 0.534 0.343 1.035s-0.107 0.853-0.252 1.113c-0.12-0.385-0.179-0.992-0.179-1.389 0 0-0.009-0.759 0.088-0.759v0zM6.232 9.961c0.148-0.264 0.301-0.543 0.458-0.839 0.383-0.724 0.624-1.29 0.804-1.755 0.358 0.651 0.804 1.205 1.328 1.649 0.065 0.055 0.135 0.111 0.207 0.166-1.066 0.211-1.987 0.467-2.798 0.779v0zM12.952 9.901c-0.065 0.041-0.251 0.064-0.37 0.064-0.386 0-0.864-0.176-1.533-0.464 0.257-0.019 0.493-0.029 0.705-0.029 0.387 0 0.502-0.002 0.88 0.095s0.383 0.293 0.318 0.333v0z">
                                                    </path>
                                                    <path
                                                        d="M14.341 3.579c-0.347-0.473-0.831-1.027-1.362-1.558s-1.085-1.015-1.558-1.362c-0.806-0.591-1.197-0.659-1.421-0.659h-7.75c-0.689 0-1.25 0.561-1.25 1.25v13.5c0 0.689 0.561 1.25 1.25 1.25h11.5c0.689 0 1.25-0.561 1.25-1.25v-9.75c0-0.224-0.068-0.615-0.659-1.421v0zM12.271 2.729c0.48 0.48 0.856 0.912 1.134 1.271h-2.406v-2.405c0.359 0.278 0.792 0.654 1.271 1.134v0zM14 14.75c0 0.136-0.114 0.25-0.25 0.25h-11.5c-0.135 0-0.25-0.114-0.25-0.25v-13.5c0-0.135 0.115-0.25 0.25-0.25 0 0 7.749-0 7.75 0v3.5c0 0.276 0.224 0.5 0.5 0.5h3.5v9.75z">
                                                    </path>
                                                </svg>
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        @endif
        @if ($portfolioConfig['visibility']['footer'])
            <footer class="footer">
                <div class="h4 title text-center text-muted">{{$about->name}}</div>
                <div class="text-center text-muted"><p>©{{ now()->year }} All rights reserved.</p></div>
            </footer>
        @endif
    </div>
    <!-- Bootstrap core JS-->
    <script src="{{ asset('assets/common/lib/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/common/lib/bootstrap/js/bootstrap.min.js') }}"></script>
    <!-- Third party plugin JS-->
    <script src="{{ asset('assets/common/lib/jquery.easing/jquery.easing.min.js') }}"></script>
    <script src="{{ asset('assets/common/lib/typed/typed.js') }}"></script>
    <script src="{{ asset('assets/common/lib/aos/aos.js') }}"></script>
    <script src="{{ asset('assets/common/lib/iziToast/js/iziToast.min.js') }}"></script>
    <script src="{{ asset('assets/common/lib/jquery-validation/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('assets/common/lib/jquery.lazy/jquery.lazy.min.js') }}"></script>
    <!-- Core theme JS-->
    <script src="{{ asset('assets/themes/vega/js/scripts.js') }}"></script>
    <script src="{{ asset('js/client/frontend/roots/projects.js') }}"></script>
    <script>
        $(function() {
            if ($('#szn-preloader').length) {
                $('#szn-preloader').delay(100).fadeOut('slow', function() {
                    $(this).remove();
                });
            }

            AOS.init();

            $('.lazy').lazy();
            
            if ($('#typed-strings').length) {
                @if($about->taglines)
                    var typedStrings = new Typed('#typed-strings', {
                        strings: {!! json_encode(json_decode($about->taglines)) !!},
                        typeSpeed: 70,
                        backSpeed: 40,
                        smartBackspace: true,
                        loop: true
                    });
                @endif
            }

            $('#contact-me-form').validate({
                rules: {
                    name: {
                        required: true
                    },
                    email: {
                        required: true,
                        email: true,
                    },
                    subject: {
                        required: true
                    },
                    body: {
                        required: true
                    }
                },
                submitHandler: function(form, event)
                {
                    const button = $('#contact-me-form #submit');

                    button.attr('disabled', true);
                    button.html(`<span class="content">
                        Sending <i class="fas fa-spinner fa-spin"></i>
                    </span>`);

                    $.ajax({
                        url: '{!! route('contact-me') !!}',
                        dataType: 'json',
                        data: $('#contact-me-form').serialize(),
                        type:'post',
                        success: function(response) {
                            if (response.status === 400) {
                                var errorArray = response.payload;
                                $.each( errorArray, function( key, errors ) {
                                    $.each( errors, function( key2, errorMessage ) {
                                        showNotification( errorMessage, 'error', false);
                                    });
                                });
                            } else if (response.status !== 200) {
                                showNotification(response.message, 'error', false);
                            } else if (response.status === 200) {
                                showNotification(response.message, 'success', false);
                                $('#contact-me-form').trigger('reset');
                            }
                        },
                        error: function (jqXHR, exception) {
                            console.log(jqXHR);
                        },
                        complete: function(data) {
                            button.attr('disabled', false);
                            button.html(`<span class="content">
                                Send <svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 512 512" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M476 3.2L12.5 270.6c-18.1 10.4-15.8 35.6 2.2 43.2L121 358.4l287.3-253.2c5.5-4.9 13.3 2.6 8.6 8.3L176 407v80.5c0 23.6 28.5 32.9 42.5 15.8L282 426l124.6 52.2c14.2 6 30.4-2.9 33-18.2l72-432C515 7.8 493.3-6.8 476 3.2z">
                                    </path>
                                </svg>
                            </span>`);
                        }
                    });
                }
            });
            
            function showNotification(message = 'Something went wrong', type = 'error', sticky = false) {
                iziToast.show({
                    title: '',
                    message: message,
                    messageSize: 12,
                    position: 'topRight',
                    theme: 'dark',
                    pauseOnHover: true,
                    timeout: sticky === false ? 5000 : false,
                    progressBarColor: type === 'success' ? '#00ffb8' : '#ffafb4',
                    color: type === 'success' ? '#565c70' : '#565c70',
                    messageColor: type === 'success' ? '#00ffb8' : '#ffafb4',
                    icon: type === 'success' ? 'fas fa-check' : 'fas fa-times-circle'
                });
            }    
        });
    </script>
    @if (!empty($portfolioConfig['script']['footer']) && $portfolioConfig['script']['footer'] != '')
        <script>
            {!!$portfolioConfig['script']['footer']!!}
        </script>
    @endif
    @include('common.pixelTracking')
</body>
</html>

<!-- =======================================================
* Template Name: MyResume - v2.1.0
* Template URL: https://bootstrapmade.com/free-html-bootstrap-template-my-resume/
* Author: BootstrapMade.com
* License: https://bootstrapmade.com/license/
* The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
======================================================== -->

@php
    $accentColor = $portfolioConfig['accentColor'];
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
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta property="og:title" content="{{$portfolioConfig['seo']['title']}}"/>
    <meta property="title" content="{{$portfolioConfig['seo']['title']}}"/>
    <meta name="description" content="{{$portfolioConfig['seo']['description']}}" />
    <meta property="og:description" content="{{$portfolioConfig['seo']['description']}}"/>
    <meta name="author" content="{{$portfolioConfig['seo']['author']}}" />
    <meta property="og:image" content="{{asset($portfolioConfig['seo']['image'])}}" />
    <meta property="og:image:secure_url" content="{{asset($portfolioConfig['seo']['image'])}}" />

    <title>{{$about->name}}</title>

    <link rel="shortcut icon" type="image/x-icon"  href="{{ Utils::getFavicon() }}">

    <!-- Vendor CSS Files -->
    <link href="{{ asset('assets/common/lib/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/common/lib/fontawesome/css/all.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/common/lib/mdi-icon/css/materialdesignicons.min.css') }}" rel="stylesheet"/>
    <link href="{{ asset('assets/common/lib/boxicons/css/boxicons.min.css') }}" rel="stylesheet"/>
    <link href="{{ asset('assets/common/lib/iziToast/css/iziToast.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/common/lib/aos/aos.css') }}" rel="stylesheet">

    <!-- Template Main CSS File -->
    <link href="{{ asset('assets/themes/rigel/css/styles.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/themes/rigel/css/custom.css') }}" rel="stylesheet">
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
    </style>
</head>

<body>
    <!-- ======= Mobile nav toggle button ======= -->
    <button type="button" class="mobile-nav-toggle d-xl-none"><i class="fas fa-bars"></i></button>

    <!-- ======= Header ======= -->
    <header id="header" class="d-flex flex-column justify-content-center">
        <nav class="nav-menu">
            <ul>
                <li class="active"><a href="#hero"><i class="bx bx-home"></i> <span>Home</span></a></li>
                @if ($portfolioConfig['visibility']['about'])
                <li><a href="#about"><i class="bx bx-user"></i> <span>About</span></a></li>
                @endif
                @if ($portfolioConfig['visibility']['skills'])
                <li><a href="#skills"><i class='bx bx-code-block'></i> <span>Skills</span></a></li>
                @endif
                @if ($portfolioConfig['visibility']['experiences'])
                <li><a href="#experiences"><i class='bx bx-briefcase'></i> <span>Experiences</span></a></li>
                @endif
                @if ($portfolioConfig['visibility']['education'])
                <li><a href="#education"><i class='bx bx-book' ></i> <span>Education</span></a></li>
                @endif
                @if ($portfolioConfig['visibility']['projects'])
                <li><a href="#projects"><i class='bx bxs-package'></i> <span>Projects</span></a></li>
                @endif
                @if ($portfolioConfig['visibility']['services'])
                <li><a href="#services"><i class="bx bx-server"></i> <span>Services</span></a></li>
                @endif
                @if ($portfolioConfig['visibility']['contact'])
                <li><a href="#contact"><i class="bx bx-envelope"></i> <span>Contact</span></a></li>
                @endif
            </ul>
        </nav><!-- .nav-menu -->
    </header><!-- End Header -->

    <!-- ======= hero Section ======= -->
    <section id="hero" class="d-flex flex-column justify-content-center" style="background-image: url('{{asset($about->cover)}}');">
        <div class="container" data-aos="fade-up" data-aos-anchor-placement="top-bottom">
            <h1>{{ $about->name }}</h1>
            <p><span class="typed"></span></p>
            @if ($portfolioConfig['visibility']['cv'])
                <div class="my-3">
                    <a href="{{$about->cv}}" class="btn btn-light btn-sm" download>Download CV</a>
                </div>
            @endif
            @if ($about->social_links)
                <div class="social-links">
                    @foreach (json_decode($about->social_links) as $social)
                        <a href="{{$social->link}}" target=="_blank" class="social-icon">
                            <i class="{{$social->iconClass}}"></i>
                        </a>
                    @endforeach
                </div>
            @endif
        </div>
    </section><!-- End Hero -->

    <main id="main">
        @if ($portfolioConfig['visibility']['about'])
        <!-- ======= About Section ======= -->
        <section id="about" class="about">
            <div class="container" data-aos="zoom-in">
                <div class="section-title">
                    <h2>About</h2>
                </div>

                <div class="row">
                    <div class="col-lg-4 text-center">
                        <img data-src="{{asset($about->avatar)}}" src="{{asset('assets/common/img/lazyloader.gif')}}" class="lazy img-fluid rounded-circle img-profile" alt="">
                    </div>
                    <div class="col-lg-8 pt-4 pt-lg-0 content my-lg-auto">
                        <div class="row">
                            <div class="col-lg-12">
                                <ul>
                                    @if ($about->phone && $about->phone !== '')
                                        <li><i class="fas fa-angle-right"></i> <strong>Phone:</strong> {{$about->phone}}</li>
                                    @endif
                                    @if ($about->email && $about->email !== '')
                                        <li><i class="fas fa-angle-right"></i> <strong>Email:</strong> {{$about->email}}</li>
                                    @endif
                                    @if ($about->address && $about->address !== '')
                                        <li><i class="fas fa-angle-right"></i> <strong>Address:</strong> {{$about->address}}</li>
                                    @endif
                                </ul>
                            </div>
                        </div>
                        <p>{{ $about->description }}</p>
                    </div>
                </div>
            </div>
        </section><!-- End About Section -->
        @endif

        @if ($portfolioConfig['visibility']['skills'])
        <!-- ======= Skills Section ======= -->
        <section id="skills" class="skills section-bg">
            <div class="container">
                <div class="section-title">
                    <h2>Skills</h2>
                </div>
                <div class="row skills-content">
                    @if (!empty($skills))
                        @foreach ($skills as $skill)
                            @if ((int)$portfolioConfig['visibility']['skillProficiency'])
                            <div class="col-lg-6">
                                <div class="progress" data-aos="fade-up">
                                    <span class="skill">{{$skill->name}} <i class="val">{{$skill->proficiency}}%</i></span>
                                    <div class="progress-bar-wrap">
                                        <div class="progress-bar" role="progressbar" aria-valuenow="{{$skill->proficiency}}" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                            </div>
                            @else
                            <div class="col-6 col-md-4 col-lg-2" data-aos="zoom-in" data-aos-delay="100">
                                <div class="card z-hover skill-card text-center">
                                    <div class="card-body center-this">
                                        {{$skill->name}}
                                    </div>
                                </div>
                            </div>
                            @endif
                        @endforeach
                    @endif
                </div>
            </div>
        </section><!-- End Skills Section -->
        @endif

        @if ($portfolioConfig['visibility']['experiences'])
        <!-- ======= Experiences Section ======= -->
        <section id="experiences" class="resume">
            <div class="container">
                <div class="section-title">
                    <h2>Experiences</h2>
                </div>
                <div>
                    @if ($experiences)
                        @foreach ($experiences as $experience)
                            <div class="resume-item" data-aos="fade-up">
                                <h4>{{$experience->position ? $experience->position : ''}}</h4>
                                <h5>{{$experience->period ? $experience->period : ''}}</h5>
                                <p class="font-weight-bold text-muted">{{$experience->company ? $experience->company : ''}}</p>
                                <p>{{$experience->details ? $experience->details : ''}}</p>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </section><!-- End Experiences Section -->
        @endif

        @if ($portfolioConfig['visibility']['education'])
        <!-- ======= Education Section ======= -->
        <section id="education" class="resume">
            <div class="container">
                <div class="section-title">
                    <h2>Education</h2>
                </div>
                <div>
                    @if ($education)
                        @foreach ($education as $value)
                            <div class="resume-item" data-aos="fade-up">
                                <h4>{{$value->degree ? $value->degree : ''}}</h4>
                                <h5>{{$value->period ? $value->period : ''}}</h5>
                                <p class="font-weight-bold text-muted">{{$value->institution ? $value->institution : ''}}</p>
                                <span>{{$value->degree ? $value->degree : ''}}</span>
                                <p>{{$value->cgpa ? $value->cgpa : ''}}</p>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </section><!-- End Education Section -->
        @endif

        @if ($portfolioConfig['visibility']['projects'])
        <!-- ======= Projects Section ======= -->
        <section id="projects" class="resume">
            <div class="container">
                <div class="section-title">
                    <h2>Projects</h2>
                </div>
                <div>
                    <div 
                        id="react-project-root" 
                        data-accentcolor="{{$accentColor}}" 
                        data-demomode="{{$demoMode}}"
                    />
                    <div class="mb-5"></div>
                </div>
            </div>
        </section><!-- End Projects Section -->
        @endif

        @if ($portfolioConfig['visibility']['services'])
        <!-- ======= Services Section ======= -->
        <section id="services" class="services">
            <div class="container">
                <div class="section-title">
                    <h2>Services</h2>
                </div>
                <div class="row">
                    @if (!empty($services))
                        @foreach ($services as $service)
                            <div class="col-lg-4 col-md-6 d-flex align-items-stretch icon-box-wrapper" data-aos="zoom-in">
                                <div class="icon-box z-hover">
                                    <div class="icon">
                                        <svg width="100" height="100" viewBox="0 0 600 600" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke="none" stroke-width="0" fill="#f5f5f5" d="M300,521.0016835830174C376.1290562159157,517.8887921683347,466.0731472004068,529.7835943286574,510.70327084640275,468.03025145048787C554.3714126377745,407.6079735673963,508.03601936045806,328.9844924480964,491.2728898941984,256.3432110539036C474.5976632858925,184.082847569629,479.9380746630129,96.60480741107993,416.23090153303,58.64404602377083C348.86323505073057,18.502131276798302,261.93793281208167,40.57373210992963,193.5410806939664,78.93577620505333C130.42746243093433,114.334589627462,98.30271207620316,179.96522072025542,76.75703585869454,249.04625023123273C51.97151888228291,328.5150500222984,13.704378332031375,421.85034740162234,66.52175969318436,486.19268352777647C119.04800174914682,550.1803526380478,217.28368757567262,524.383925680826,300,521.0016835830174">
                                            </path>
                                        </svg>
                                        <i class="{{$service->icon}}"></i>
                                    </div>
                                    <h4><a href="">{{$service->title}}</a></h4>
                                    <p>{{$service->details}}</p>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </section><!-- End Services Section -->
        @endif

        @if ($portfolioConfig['visibility']['contact'])
        <!-- ======= Contact Section ======= -->
        <section id="contact" class="contact">
            <div class="container" data-aos="zoom-in">
                <div class="section-title">
                    <h2>Contact</h2>
                </div>
                <div class="row mt-1">
                    <div class="col-lg-4">
                        <div class="info">
                            @if ($about->phone && $about->phone !== '')
                                <div class="phone">
                                    <i class='bx bxs-phone'></i>
                                    <h4>Call:</h4>
                                    <p>{{$about->phone}}</p>
                                </div>
                            @endif
                            @if ($about->email && $about->email !== '')
                                <div class="email">
                                    <i class='bx bxs-envelope' ></i>
                                    <h4>Email:</h4>
                                    <p>{{$about->email}}</p>
                                </div>
                            @endif
                            @if ($about->address && $about->address !== '')
                                <div class="address">
                                    <i class='bx bx-current-location' ></i>
                                    <h4>Address:</h4>
                                    <p>{{$about->address}}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="col-lg-8 mt-5 mt-lg-0">
                        <form action="forms/contact.php" method="post" id="contact-me-form" role="form" class="php-email-form">
                            @csrf
                            <div class="form-row">
                                <div class="col-md-6 form-group">
                                    <input type="text" name="name" class="form-control" id="name" placeholder="Your Name" data-rule="required" data-msg="Please enter your name" />
                                    <div class="validate"></div>
                                </div>
                                <div class="col-md-6 form-group">
                                    <input type="email" class="form-control" name="email" id="email" placeholder="Your Email" data-rule="email" data-msg="Please enter a valid email" />
                                    <div class="validate"></div>
                                </div>
                            </div>
                            <div class="form-group">
                                <input type="text" class="form-control" name="subject" id="subject" placeholder="Subject" data-rule="required" data-msg="Please enter subject" />
                                <div class="validate"></div>
                            </div>
                            <div class="form-group">
                                <textarea class="form-control" name="body" id="body" rows="5" data-rule="required" data-msg="Please write something" placeholder="Body"></textarea>
                                <div class="validate"></div>
                            </div>
                            <div class="text-center"><button type="submit" class="submit-button">Send Message</button></div>
                        </form>
                    </div>
                </div>
            </div>
        </section><!-- End Contact Section -->
        @endif
    </main><!-- End #main -->

    @if ($portfolioConfig['visibility']['footer'])
    <!-- ======= Footer ======= -->
    <footer id="footer">
        <div class="container">
            <h3>{{$about->name}}</h3>
            @if ($about->social_links)
                <div class="social-links">
                    @foreach (json_decode($about->social_links) as $social)
                        <a href="{{$social->link}}" target=="_blank" class="social-icon">
                            <i class="{{$social->iconClass}}"></i>
                        </a>
                    @endforeach
                </div>
            @endif
            <div class="copyright">
                ©{{ now()->year }} All rights reserved.
            </div>
            <div class="credits">
                <!-- All the links in the footer should remain intact. -->
                <!-- You can delete the links only if you purchased the pro version. -->
                <!-- Licensing information: [license-url] -->
                <!-- Purchase the pro version with working PHP/AJAX contact form: https://bootstrapmade.com/free-html-bootstrap-template-my-resume/ -->
                Designed by <a href="https://bootstrapmade.com/">BootstrapMade</a>
            </div>
        </div>
    </footer><!-- End Footer -->
    @else
    <!-- ======= Footer ======= -->
    <footer id="footer" class="py-1">
        <div class="container">
            <div class="credits">
                <!-- All the links in the footer should remain intact. -->
                <!-- You can delete the links only if you purchased the pro version. -->
                <!-- Licensing information: [license-url] -->
                <!-- Purchase the pro version with working PHP/AJAX contact form: https://bootstrapmade.com/free-html-bootstrap-template-my-resume/ -->
                Designed by <a href="https://bootstrapmade.com/">BootstrapMade</a>
            </div>
        </div>
    </footer><!-- End Footer -->
    @endif

    <a href="#" class="back-to-top"><i class="bx bx-up-arrow-alt"></i></a>
    @include('common.preloader2')

    <!-- Vendor JS Files -->
    <script src="{{ asset('assets/common/lib/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/common/lib/bootstrap/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/common/lib/jquery.easing/jquery.easing.min.js') }}"></script>
    <script src="{{ asset('assets/common/lib/typed/typed.js') }}"></script>
    <script src="{{ asset('assets/common/lib/iziToast/js/iziToast.min.js') }}"></script>
    <script src="{{ asset('assets/common/lib/jquery-validation/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('assets/common/lib/waypoints/jquery.waypoints.min.js') }}"></script>
    <script src="{{ asset('assets/common/lib/isotope-layout/isotope.pkgd.min.js') }}"></script>
    <script src="{{ asset('assets/common/lib/aos/aos.js') }}"></script>
    <script src="{{ asset('assets/common/lib/jquery.lazy/jquery.lazy.min.js') }}"></script>

    <!-- Template Main JS File -->
    <script src="{{ asset('assets/themes/rigel/js/main.js') }}"></script>

    <script src="{{ asset('js/client/frontend/roots/projects.js') }}"></script>
    <script>
        $(function() {
            $('.lazy').lazy();
            
            @if($about->taglines)
                if ($('.typed').length) {
                    new Typed('.typed', {
                        strings: {!! json_encode(json_decode($about->taglines)) !!},
                        loop: true,
                        typeSpeed: 100,
                        backSpeed: 50,
                        backDelay: 2000
                    });
                }
            @endif

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
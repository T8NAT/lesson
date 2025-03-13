<!DOCTYPE html>
<html dir="rtl" lang="ar">
<!--begin::Head-->
<head><base href="../../../"/>
    <title>Lesson | تسجيل دخول</title>
    <meta charset="utf-8" />
{{--    <meta name="description" content="" />--}}
{{--    <meta name="keywords" content="" />--}}
{{--    <meta name="viewport" content="width=device-width, initial-scale=1" />--}}
{{--    <meta property="og:locale" content="en_US" />--}}
{{--    <meta property="og:type" content="article" />--}}
{{--    <meta property="og:title" content="" />--}}
{{--    <meta property="og:url" content="{{settings()->url ?? ''}}" />--}}
{{--    <meta property="og:site_name" content="{{settings()->name ?? 'تطبيق Lesson'}}" />--}}
{{--    <link rel="canonical" href="{{settings()->url ?? ''}}" />--}}
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" href="{{Storage::url(settings()->favicon ?? '')}}" />
    <style>

        body {
            background-color: #f0f8ff;
        }

        .card {
            border-radius: 15px;
        }

        .btn-primary {
            background-color: #4682B4;
            border-color: #4682B4;
        }

        .btn-primary:hover {
            background-color: #2E64FE;
            border-color: #2E64FE;
        }

        .form-control:focus {
            border-color: #4682B4;
            box-shadow: 0 0 0 0.2rem rgba(70, 130, 180, 0.25);
        }
    </style>
    <!--begin::Fonts(mandatory for all pages)-->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" />
    <!--end::Fonts-->
    <!--begin::Global Stylesheets Bundle(mandatory for all pages)-->
        <!--AR files-->
        <link href="{{asset('assets/plugins/global/plugins.bundle.rtl.css')}}" rel="stylesheet" type="text/css">
        <link href="{{asset('assets/css/style.bundle.rtl.css')}}" rel="stylesheet" type="text/css">
    <!--end::Fonts-->
    <!--end::Global Stylesheets Bundle-->
    <script>// Frame-busting to prevent site from being loaded within a frame without permission (click-jacking) if (window.top != window.self) { window.top.location.replace(window.self.location.href); }</script>
</head>
<!--end::Head-->
<!--begin::Body-->
<body id="kt_body" class="auth-bg">
<!--begin::Theme mode setup on page load-->
<script>var defaultThemeMode = "light"; var themeMode; if ( document.documentElement ) { if ( document.documentElement.hasAttribute("data-bs-theme-mode")) { themeMode = document.documentElement.getAttribute("data-bs-theme-mode"); } else { if ( localStorage.getItem("data-bs-theme") !== null ) { themeMode = localStorage.getItem("data-bs-theme"); } else { themeMode = defaultThemeMode; } } if (themeMode === "system") { themeMode = window.matchMedia("(prefers-color-scheme: dark)").matches ? "dark" : "light"; } document.documentElement.setAttribute("data-bs-theme", themeMode); }</script>
<!--end::Theme mode setup on page load-->
<!--begin::Main-->
<!--begin::Root-->
<div class="d-flex flex-column min-vh-100 align-items-center justify-content-center py-5" style="background-color: #f8f9fa;">

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="card rounded-4 shadow-lg border-0">
                    <div class="card-body p-4 p-lg-5">

                        <div class="text-center mb-4">
                            <img src="{{Storage::url(settings()->logo ?? '')}}" alt="{{settings()->alt ?? ''}}" class="img-fluid mb-3" style="max-width: 150px;">
                            <h2 class="card-title fw-bold text-primary">{{settings()->name ?? 'اسم التطبيق'}}</h2>
                            <p class="text-muted">{{settings()->about ?? 'وصف قصير للتطبيق'}}</p>
                        </div>
                        @if(session()->has('alert-type'))
                            <div class="alert {{session()->get('alert-type')}} alert-custom alert-notice alert-light-primary fade show" role="alert">
                                <div class="alert-icon"><i class="flaticon-warning"></i></div>
                                <div class="alert-text"> {{session()->get('message')}}</div>
                            </div>
                        @endif
                        <!-- نموذج تسجيل الدخول -->
                        <form  id="kt_cms_login_admin_form" class="form w-100" novalidate="novalidate" data-kt-redirect="{{route('dashboard')}}" action="{{route('admin-login')}}" method="POST">
                            @csrf
                            <!-- اختيار دور المستخدم (طالب / معلم) -->
                            <div class="d-flex justify-content-center mb-4">
                                <button type="button" class="btn btn-outline-primary rounded-pill px-4 mx-2 role-btn active" data-role="student">
                                    <i class="fas fa-user-graduate me-2"></i> طالب
                                </button>
                                <button type="button" class="btn btn-outline-primary rounded-pill px-4 mx-2 role-btn" data-role="teacher">
                                    <i class="fas fa-chalkboard-teacher me-2"></i> معلم
                                </button>
                            </div>
                            <div class="mb-3">
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                    <input type="email" id="email" class="form-control form-control-lg" placeholder="البريد الإلكتروني" name="email" required>

                                </div>
                                @error('email')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                    <input type="password" id="password" class="form-control form-control-lg" placeholder="كلمة المرور" name="password" required>

                                </div>
                                @error('password')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- تذكر كلمة المرور و زر تسجيل الدخول-->
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <a href="#" class="text-primary fw-semibold">هل نسيت كلمة المرور؟</a>
                            </div>

                            <button type="submit" class="btn btn-lg btn-primary w-100 mb-5">
                                <span class="indicator-label" >{{'تسجيل دخول'}}</span>
                            </button>

                            <!-- رابط الاشتراك -->
                            <div class="text-center">
                                <span class="text-muted">لست عضواً؟</span>
                                <a href="#" class="text-primary fw-semibold">اشترك الآن</a>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!--end::Main-->
<!--begin::Javascript-->
<script>
    // JavaScript لتفعيل الأزرار
    const roleButtons = document.querySelectorAll('.role-btn');
    const userRoleInput = document.getElementById('user-role');

    roleButtons.forEach(button => {
        button.addEventListener('click', function() {
            roleButtons.forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');
            userRoleInput.value = this.dataset.role;
        });
    });
</script>

<script>var hostUrl = "assets/";</script>
<!--begin::Global Javascript Bundle(mandatory for all pages)-->
<script src="{{asset('assets/plugins/global/plugins.bundle.js')}}"></script>
<script src="{{asset('assets/js/scripts.bundle.js')}}"></script>
<!--end::Global Javascript Bundle-->
<!--begin::Custom Javascript(used for this page only)-->
<script src="{{asset('assets/js/custom/authentication/sign-in/general.js')}}"></script>
<script src="{{asset('assets/js/custom/authentication/sign-in/i18n.js')}}"></script>
<!--end::Custom Javascript-->
<!--end::Javascript-->
</body>

<!--end::Body-->
</html>

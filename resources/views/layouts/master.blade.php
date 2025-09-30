<!DOCTYPE html>
<html lang="en" direction="rtl" dir="rtl" style="direction: rtl">
<head>
    <base href="" />
    <title>@yield('title')</title>
    <meta charset="utf-8" />
    <meta name="description" content="Iraqi Childern's Hope " />
    <meta name="keywords" content="Iraqi ,Childern" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta property="og:locale" content="ar_EGy" />
    <meta property="og:type" content="article" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @include('layouts.head-css')
    <script>
        if (window.top != window.self) { 
            window.top.location.replace(window.self.location.href); 
        }
    </script>
</head>

<body id="kt_app_body" data-kt-app-header-fixed="true" data-kt-app-header-fixed-mobile="true"
    data-kt-app-sidebar-enabled="true" data-kt-app-sidebar-fixed="true" data-kt-app-sidebar-hoverable="true"
    data-kt-app-sidebar-push-toolbar="true" data-kt-app-sidebar-push-footer="true" data-kt-app-toolbar-enabled="true"
    data-kt-app-aside-enabled="true" data-kt-app-aside-fixed="true" data-kt-app-aside-push-toolbar="true"
    data-kt-app-aside-push-footer="true" class="app-default">
    
    <script>
        var defaultThemeMode = "light"; 
        var themeMode; 
        if (document.documentElement) { 
            if (document.documentElement.hasAttribute("data-bs-theme-mode")) { 
                themeMode = document.documentElement.getAttribute("data-bs-theme-mode"); 
            } else { 
                if (localStorage.getItem("data-bs-theme") !== null) { 
                    themeMode = localStorage.getItem("data-bs-theme"); 
                } else { 
                    themeMode = defaultThemeMode; 
                } 
            } 
            if (themeMode === "system") { 
                themeMode = window.matchMedia("(prefers-color-scheme: dark)").matches ? "dark" : "light"; 
            } 
            document.documentElement.setAttribute("data-bs-theme", themeMode); 
        }
    </script>
    
    <div class="d-flex flex-column flex-root app-root" id="kt_app_root">
        <div class="app-page flex-column flex-column-fluid" id="kt_app_page">
            
            <!--begin::Header-->
            @include('layouts.header')
            <!--end::Header-->
            
            <div class="app-wrapper flex-column flex-row-fluid" id="kt_app_wrapper">
                
                <!--begin::Sidebar-->
                @include('layouts.sidebar')
                <!--end::Sidebar-->
                
                <div class="app-main flex-column flex-row-fluid" id="kt_app_main">
                    <div class="d-flex flex-column flex-column-fluid">
                        
                        <!--begin::Toolbar-->
                        <div id="kt_app_toolbar" class="app-toolbar pt-5">
                            <div id="kt_app_toolbar_container"
                                class="app-container container-fluid d-flex align-items-stretch">
                                <div class="app-toolbar-wrapper d-flex flex-stack flex-wrap gap-4 w-100">
                                    @yield('page-header')
                                </div>
                            </div>
                        </div>
                        <!--end::Toolbar-->
                        
                        <!--begin::Content-->
                        <div id="kt_app_content" class="app-content flex-column-fluid">
                            @yield('content')
                        </div>
                        <!--end::Content-->
                    </div>
                    
                    <!--begin::Footer-->
                    <div id="kt_app_footer"
                        class="app-footer align-items-center justify-content-center justify-content-md-between flex-column flex-md-row py-3">
                        <div class="text-dark order-2 order-md-1">
                            <span class="text-muted fw-semibold me-1">2025&copy;</span>
                            <a href="#" class="text-gray-800 text-hover-success">صحتي</a>
                        </div>
                    </div>
                    <!--end::Footer-->
                </div>
            </div>
        </div>
    </div>
    
    @include('layouts.footer-script')
</body>
</html>
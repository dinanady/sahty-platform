<div id="kt_app_sidebar" class="app-sidebar flex-column" data-kt-drawer="true" data-kt-drawer-name="app-sidebar"
    data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true" data-kt-drawer-width="250px"
    data-kt-drawer-direction="start" data-kt-drawer-toggle="#kt_app_sidebar_mobile_toggle">
    <!--begin::Main-->
    <div class="d-flex flex-column justify-content-between h-100 hover-scroll-overlay-y my-2 d-flex flex-column"
        id="kt_app_sidebar_main" data-kt-scroll="true" data-kt-scroll-activate="true" data-kt-scroll-height="auto"
        data-kt-scroll-dependencies="#kt_app_header" data-kt-scroll-wrappers="#kt_app_main" data-kt-scroll-offset="5px">
        <!--begin::Sidebar menu-->
        <div id="#kt_app_sidebar_menu" data-kt-menu="true" data-kt-menu-expand="false"
            class="flex-column-fluid menu menu-sub-indention menu-column menu-rounded menu-active-bg mb-7">

            <!--begin:Dashboard-->
            <div class="menu-item">
                <a class="menu-link {{ Request::is('admin/dashboard') ? 'active' : '' }}"
                    href="{{ route('admin.dashboard') }}" data-bs-toggle="tooltip" data-bs-trigger="hover"
                    data-bs-dismiss="click" data-bs-placement="right">
                    <span class="menu-icon">
                        <i class="ki-duotone ki-element-11 fs-1">
                            <span class="path1"></span>
                            <span class="path2"></span>
                            <span class="path3"></span>
                            <span class="path4"></span>
                        </i>
                    </span>
                    <span class="menu-title">الرئيسية</span>
                </a>
            </div>
            <!--end:Dashboard-->

            <!--begin:Health Center Managers-->
            <div data-kt-menu-trigger="click"
                class="menu-item menu-accordion {{ Route::is('admin.health-center-managers.*') ? 'here show' : '' }}">
                <span class="menu-link">
                    <span class="menu-icon">
                        <i class="ki-duotone ki-user-tick fs-1">
                            <span class="path1"></span>
                            <span class="path2"></span>
                            <span class="path3"></span>
                        </i>
                    </span>
                    <span class="menu-title">مديري الوحدات</span>
                    <span class="menu-arrow"></span>
                </span>

                <div class="menu-sub menu-sub-accordion">
                    <div class="menu-item">
                        <a class="menu-link {{ Route::is('admin.health-center-managers.index') ? 'active' : '' }}"
                            href="{{ route('admin.health-center-managers.index') }}" data-bs-toggle="tooltip"
                            data-bs-trigger="hover" data-bs-dismiss="click" data-bs-placement="right">
                            <span class="menu-bullet">
                                <span class="bullet bullet-dot"></span>
                            </span>
                            <span class="menu-title">قائمة المديرين</span>
                        </a>
                    </div>

                    <div class="menu-item">
                        <a class="menu-link {{ Route::is('admin.health-center-managers.create') ? 'active' : '' }}"
                            href="{{ route('admin.health-center-managers.create') }}" data-bs-toggle="tooltip"
                            data-bs-trigger="hover" data-bs-dismiss="click" data-bs-placement="right">
                            <span class="menu-bullet">
                                <span class="bullet bullet-dot"></span>
                            </span>
                            <span class="menu-title">إضافة مدير جديد</span>
                        </a>
                    </div>
                </div>
            </div>
            <!--end:Health Center Managers-->

            <!--begin:Health Centers-->
            <div data-kt-menu-trigger="click"
                class="menu-item menu-accordion {{ Route::is('admin.health-centers.*') ? 'here show' : '' }}">
                <span class="menu-link">
                    <span class="menu-icon">
                        <i class="ki-duotone ki-hospital fs-1">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                    </span>
                    <span class="menu-title">الوحدات الصحية</span>
                    <span class="menu-arrow"></span>
                </span>

                <div class="menu-sub menu-sub-accordion">
                    <div class="menu-item">
                        <a class="menu-link {{ Route::is('admin.health-centers.index') ? 'active' : '' }}"
                            href="{{ route('admin.health-centers.index') }}" data-bs-toggle="tooltip"
                            data-bs-trigger="hover" data-bs-dismiss="click" data-bs-placement="right">
                            <span class="menu-bullet">
                                <span class="bullet bullet-dot"></span>
                            </span>
                            <span class="menu-title">قائمة الوحدات</span>
                        </a>
                    </div>

                    <div class="menu-item">
                        <a class="menu-link {{ Route::is('admin.health-centers.create') ? 'active' : '' }}"
                            href="{{ route('admin.health-centers.create') }}" data-bs-toggle="tooltip"
                            data-bs-trigger="hover" data-bs-dismiss="click" data-bs-placement="right">
                            <span class="menu-bullet">
                                <span class="bullet bullet-dot"></span>
                            </span>
                            <span class="menu-title">إضافة وحدة صحية</span>
                        </a>
                    </div>
                </div>
            </div>
            <!--end:Health Centers-->

            <!--begin:Locations-->
            <div data-kt-menu-trigger="click"
                class="menu-item menu-accordion {{ Route::is('admin.governorates.*', 'admin.cities.*') ? 'here show' : '' }}">
                <span class="menu-link">
                    <span class="menu-icon">
                        <i class="ki-duotone ki-geolocation fs-1">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                    </span>
                    <span class="menu-title">المحافظات والمدن</span>
                    <span class="menu-arrow"></span>
                </span>

                <div class="menu-sub menu-sub-accordion">
                    <div class="menu-item">
                        <a class="menu-link {{ Route::is('admin.governorates.*') ? 'active' : '' }}"
                            href="{{ route('admin.governorates.index') }}" data-bs-toggle="tooltip"
                            data-bs-trigger="hover" data-bs-dismiss="click" data-bs-placement="right">
                            <span class="menu-bullet">
                                <span class="bullet bullet-dot"></span>
                            </span>
                            <span class="menu-title">المحافظات</span>
                        </a>
                    </div>

                    <div class="menu-item">
                        <a class="menu-link {{ Route::is('admin.cities.*') ? 'active' : '' }}"
                            href="{{ route('admin.cities.index') }}" data-bs-toggle="tooltip" data-bs-trigger="hover"
                            data-bs-dismiss="click" data-bs-placement="right">
                            <span class="menu-bullet">
                                <span class="bullet bullet-dot"></span>
                            </span>
                            <span class="menu-title">المدن</span>
                        </a>
                    </div>
                </div>
            </div>
            <!--end:Locations-->
            {{-- في sidebar --}}
            <div data-kt-menu-trigger="click"
                class="menu-item menu-accordion {{ Route::is('admin.drugs.*', 'admin.vaccines.*', 'admin.request-drugs', 'admin.approved-drugs', 'admin.rejected-drugs') ? 'here show' : '' }}">
                <span class="menu-link">
                    <span class="menu-icon">
                        <i class="ki-duotone ki-pill fs-1">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                    </span>
                    <span class="menu-title">الأدوية واللقاحات</span>
                    <span class="menu-arrow"></span>
                </span>

                <div class="menu-sub menu-sub-accordion">
                    <div class="menu-item">
                        <a class="menu-link {{ Route::is('admin.drugs.*') ? 'active' : '' }}"
                            href="{{ route('admin.drugs.index') }}">
                            <span class="menu-bullet">
                                <span class="bullet bullet-dot"></span>
                            </span>
                            <span class="menu-title">الأدوية</span>
                        </a>
                    </div>
                    <div class="menu-item">
                        <a class="menu-link {{ Route::is('admin.request-drugs') ? 'active' : '' }}"
                            href="{{ route('admin.request-drugs') }}">
                            <span class="menu-bullet">
                                <span class="bullet bullet-dot"></span>
                            </span>
                            <span class="menu-title">طلبات الادوية</span>
                        </a>
                    </div>
                    <div class="menu-item">
                        <a class="menu-link {{ Route::is('admin.approved-drugs') ? 'active' : '' }}"
                            href="{{ route('admin.approved-drugs') }}">
                            <span class="menu-bullet">
                                <span class="bullet bullet-dot"></span>
                            </span>
                            <span class="menu-title">الأدوية المقبولة</span>
                        </a>
                    </div>
                    <div class="menu-item">
                        <a class="menu-link {{ Route::is('admin.rejected-drugs') ? 'active' : '' }}"
                            href="{{ route('admin.rejected-drugs') }}">
                            <span class="menu-bullet">
                                <span class="bullet bullet-dot"></span>
                            </span>
                            <span class="menu-title">الأدوية المرفوضة</span>
                        </a>
                    </div>


                    <div class="menu-item">
                        <a class="menu-link {{ Route::is('admin.vaccines.*') ? 'active' : '' }}"
                            href="{{ route('admin.vaccines.index') }}">
                            <span class="menu-bullet">
                                <span class="bullet bullet-dot"></span>
                            </span>
                            <span class="menu-title">لقاحات الأطفال</span>
                        </a>
                    </div>
                </div>
            </div>

        </div>
        <!--end::Sidebar menu-->

        <!--begin::Footer-->
        <div class="app-sidebar-project-default app-sidebar-project-minimize text-center min-h-lg-400px flex-column-auto d-flex flex-column justify-content-end" id="kt_app_sidebar_footer">
            <h2 class="fw-bold text-gray-800">خير الناس أنفعهم للناس</h2>
            <img class="mx-auto h-150px h-lg-175px mb-4" src="{{ asset('assets/images/logo.png') }}" alt="" />
        </div>
        <!--end::Footer-->
    </div>
    <!--end::Main-->
</div>

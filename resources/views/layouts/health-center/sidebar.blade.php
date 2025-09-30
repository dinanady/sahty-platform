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
            <!--begin:Menu item-->
            <!--begin:Menu item-->
            <div class="menu-item">
                <!--begin:Menu link-->
                <a class="menu-link {{ Request::is('health-center') ? 'active' : '' }}"
                    href="{{ route('health-center.dashboard') }}" data-bs-toggle="tooltip" data-bs-trigger="hover"
                    data-bs-dismiss="click" data-bs-placement="right">
                    <span class="menu-icon">
                        <i class="ki-duotone ki-element-11 fs-1">
                            <span class="path1"></span>
                            <span class="path2"></span>
                            <span class="path3"></span>
                            <span class="path4"></span>
                        </i>
                    </span>
                    <span class="menu-title">المركز الصحي</span>
                </a>
                <!--end:Menu link-->
            </div>

            <!--begin:Menu item - الأدوية-->
            @can('hc-view-drugs')
                <div data-kt-menu-trigger="click"
                    class="menu-item menu-accordion {{ Route::is('health-center.drugs.*') ? 'show' : '' }}">
                    <!--begin:Menu link-->
                    <span class="menu-link">
                        <span class="menu-icon">
                            <i class="ki-duotone ki-capsule fs-1">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                        </span>
                        <span class="menu-title">الأدوية</span>
                        <span class="menu-arrow"></span>
                    </span>
                    <!--end:Menu link-->
                    @can('hc-view-drugs')
                        <!--begin:Menu sub-->
                        <div class="menu-sub menu-sub-accordion">
                            <!--begin:Menu item-->
                            <div class="menu-item">
                                <!--begin:Menu link-->
                                <a class="menu-link {{ Route::is('health-center.drugs.index') ? 'active' : '' }}"
                                    href="{{ route('health-center.drugs.index') }}" data-bs-toggle="tooltip"
                                    data-bs-trigger="hover" data-bs-dismiss="click" data-bs-placement="right">
                                    <span class="menu-bullet">
                                        <span class="bullet bullet-dot"></span>
                                    </span>
                                    <span class="menu-title">جميع الأدوية</span>
                                </a>
                                <!--end:Menu link-->
                            </div>
                            <!--end:Menu item-->

                            <!--begin:Menu item-->
                            <div class="menu-item">
                                <!--begin:Menu link-->
                                <a class="menu-link {{ Route::is('health-center.drugs.pending') ? 'active' : '' }}"
                                    href="{{ route('health-center.drugs.pending') }}" data-bs-toggle="tooltip"
                                    data-bs-trigger="hover" data-bs-dismiss="click" data-bs-placement="right">
                                    <span class="menu-bullet">
                                        <span class="bullet bullet-dot"></span>
                                    </span>
                                    <span class="menu-title">الأدوية المعلقة</span>
                                </a>
                                <!--end:Menu link-->
                            </div>
                            <!--end:Menu item-->

                            <!--begin:Menu item-->
                            <div class="menu-item">
                                <!--begin:Menu link-->
                                <a class="menu-link {{ Route::is('health-center.drugs.rejected') ? 'active' : '' }}"
                                    href="{{ route('health-center.drugs.rejected') }}" data-bs-toggle="tooltip"
                                    data-bs-trigger="hover" data-bs-dismiss="click" data-bs-placement="right">
                                    <span class="menu-bullet">
                                        <span class="bullet bullet-dot"></span>
                                    </span>
                                    <span class="menu-title">الأدوية المرفوضة</span>
                                </a>
                                <!--end:Menu link-->
                            </div>
                            <!--end:Menu item-->
                    @endcan
                    </div>
                    <!--end:Menu sub-->
                </div>
            @endcan
            <!--end:Menu item-->

            <!--begin:Menu item - التطعيمات-->
            @can('hc-view-vaccines')
                <div class="menu-item">
                    <!--begin:Menu link-->
                    <a class="menu-link {{ Route::is('health-center.vaccines.*') ? 'active' : '' }}"
                        href="{{ route('health-center.vaccines.index') }}" data-bs-toggle="tooltip" data-bs-trigger="hover"
                        data-bs-dismiss="click" data-bs-placement="right">
                        <span class="menu-icon">
                            <i class="ki-duotone ki-syringe fs-1">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                        </span>
                        <span class="menu-title">التطعيمات</span>
                    </a>
                    <!--end:Menu link-->
                </div>
            @endcan
            <!--end:Menu item-->

            <!--begin:Menu item - المواعيد-->
            @can('hc-view-appointments')
                <div class="menu-item">
                    <!--begin:Menu link-->
                    <a class="menu-link {{ Route::is('health-center.appointments.*') ? 'active' : '' }}"
                        href="{{ route('health-center.appointments.index') }}" data-bs-toggle="tooltip"
                        data-bs-trigger="hover" data-bs-dismiss="click" data-bs-placement="right">
                        <span class="menu-icon">
                            <i class="ki-duotone ki-calendar fs-1">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                        </span>
                        <span class="menu-title">مواعيد اللقاحات</span>
                    </a>
                    <!--end:Menu link-->
                </div>
            @endcan
            <!--end:Menu item-->

            <!--begin:Menu item - الأطباء-->
            @can('hc-view-doctors')
                <div class="menu-item">
                    <!--begin:Menu link-->
                    <a class="menu-link {{ Route::is('health-center.doctors.*') ? 'active' : '' }}"
                        href="{{ route('health-center.doctors.index') }}" data-bs-toggle="tooltip" data-bs-trigger="hover"
                        data-bs-dismiss="click" data-bs-placement="right">
                        <span class="menu-icon">
                            <i class="fas fa-stethoscope fs-2 "></i>
                        </span>
                        <span class="menu-title">الأطباء</span>
                    </a>
                    <!--end:Menu link-->
                </div>
            @endcan

            <!--end:Menu item-->

            <!--begin:Menu item - إدارة النظام-->
            @canany(['hc-view-users', 'hc-view-roles'])
                <div data-kt-menu-trigger="click"
                    class="menu-item menu-accordion {{ Route::is('health-center.users.*') ? 'show' : '' }}">
                    <!--begin:Menu link-->
                    <span class="menu-link">
                        <span class="menu-icon">
                            <i class="ki-duotone ki-shield-tick fs-1">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                        </span>
                        <span class="menu-title">إدارة النظام</span>
                        <span class="menu-arrow"></span>
                    </span>
                    <!--end:Menu link-->

                    <!--begin:Menu sub-->
                    <div class="menu-sub menu-sub-accordion">
                        <!--begin:Menu item-->
                        @can('hc-view-users')
                            <div class="menu-item">
                                <!--begin:Menu link-->
                                <a class="menu-link {{ Route::is('health-center.users.index') ? 'active' : '' }}"
                                    href="{{ route('health-center.users.index') }}" data-bs-toggle="tooltip"
                                    data-bs-trigger="hover" data-bs-dismiss="click" data-bs-placement="right">
                                    <span class="menu-bullet">
                                        <span class="bullet bullet-dot"></span>
                                    </span>
                                    <span class="menu-title">المستخدمين</span>
                                </a>
                                <!--end:Menu link-->
                            </div>
                        @endcan
                        <!--end:Menu item-->

                        <!--begin:Menu item-->
                        {{-- @can('hc-view-roles')
                        <div class="menu-item">
                            <!--begin:Menu link-->
                            <a class="menu-link {{ Route::is('health-center.roles.index') ? 'active' : '' }}"
                                href="{{ route('health-center.roles.index') }}" data-bs-toggle="tooltip"
                                data-bs-trigger="hover" data-bs-dismiss="click" data-bs-placement="right">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">الأدوار والصلاحيات</span>
                            </a>
                            <!--end:Menu link-->
                        </div>
                        @endcan --}}
                        <!--end:Menu item-->
                    </div>
                    <!--end:Menu sub-->
                </div>
            @endcanany
            <!--end:Menu item-->



            <!--end:Menu item-->
        </div>
        <!--end::Sidebar menu-->
    </div>
    <!--end::Main-->
</div

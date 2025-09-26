<div id="kt_app_sidebar" class="app-sidebar flex-column" data-kt-drawer="true" data-kt-drawer-name="app-sidebar" data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true" data-kt-drawer-width="250px" data-kt-drawer-direction="start" data-kt-drawer-toggle="#kt_app_sidebar_mobile_toggle">
    <!--begin::Main-->
    <div class="d-flex flex-column justify-content-between h-100 hover-scroll-overlay-y my-2 d-flex flex-column" id="kt_app_sidebar_main" data-kt-scroll="true" data-kt-scroll-activate="true" data-kt-scroll-height="auto" data-kt-scroll-dependencies="#kt_app_header" data-kt-scroll-wrappers="#kt_app_main" data-kt-scroll-offset="5px">
        <!--begin::Sidebar menu-->
        <div id="#kt_app_sidebar_menu" data-kt-menu="true" data-kt-menu-expand="false" class="flex-column-fluid menu menu-sub-indention menu-column menu-rounded menu-active-bg mb-7">
            <!--begin:Menu item-->
              <div class="menu-item">
                <!--begin:Menu link-->
               <a class="menu-link {{ Request::is('/') ? 'active' : '' }}" href="/loading" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-dismiss="click" data-bs-placement="right">
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
                <!--end:Menu link-->
            </div>
           

       
            <div data-kt-menu-trigger="click" class="menu-item menu-accordion {{ Route::is('governorates.create', 'governorates.index', 'governorates.edit') ? 'show' : '' }}">                <!--begin:Menu link-->
                <span class="menu-link">
                    <span class="menu-icon">
                        <i class="ki-duotone ki-rescue fs-1">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                    </span>
                    <span class="menu-title">الاستمارات</span>
                    <span class="menu-arrow"></span>
                </span>
                <!--end:Menu link-->
                <!--begin:Menu sub-->
                <div class="menu-sub menu-sub-accordion">
                    <!--begin:Menu item-->
                   
                    <div class="menu-item">
                        <!--begin:Menu link-->
                        <a class="menu-link {{ Route::is('admin.governorates.create') ? 'active' : '' }}" href="/loading" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-dismiss="click" data-bs-placement="right">
                            <span class="menu-bullet">
                                <span class="bullet bullet-dot"></span>
                            </span>
                            <span class="menu-title">تسجيل الاستمارات</span>
                        </a>
                        <!--end:Menu link-->
                    </div>
                    

                    {{-- @can('عرض الاستمارات')
                    <div class="menu-item">
                        <!--begin:Menu link-->
                        <a class="menu-link {{ Route::is('mainform.index') ? 'active' : '' }}" href="/loading" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-dismiss="click" data-bs-placement="right">
                            <span class="menu-bullet">
                                <span class="bullet bullet-dot"></span>
                            </span>
                            <span class="menu-title">عرض الاستمارات</span>
                        </a>
                        <!--end:Menu link-->
                    </div>
                    @endcan --}}

                   

                    <!--end:Menu item-->
                </div>
                <!--end:Menu sub-->
            </div>
          

            <!--begin:Menu item-->
            @can('ادارة الكفالات')
            <div data-kt-menu-trigger="click" class="menu-item menu-accordion {{ Route::is('sponsors.index', 'sponsorships.index', 'payments.index') ? 'show' : '' }}">
                <!--begin:Menu link-->
                <span class="menu-link">
                    <span class="menu-icon">
                        <i class="ki-duotone ki-chart-line-star fs-1">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                    </span>
                    <span class="menu-title"> الكفالات</span>
                    <span class="menu-arrow"></span>
                </span>
                <!--end:Menu link-->

                <!--begin:Menu sub-->
                <div class="menu-sub menu-sub-accordion">
                    <!--begin:Menu item-->
                    @can('عرض الكفلاء')
                    <div class="menu-item">
                        <!--begin:Menu link-->
                        <a class="menu-link {{ Route::is('sponsors.index') ? 'active' : '' }}" href="/loading" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-dismiss="click" data-bs-placement="right">
                            <span class="menu-bullet">
                                <span class="bullet bullet-dot"></span>
                            </span>
                            <span class="menu-title">الكفلاء</span>
                        </a>
                        <!--end:Menu link-->
                    </div>
                    @endcan
                    <!--end:Menu item-->

                    <!--begin:Menu item-->
                    @can('عرض الكفالات')
                    <div class="menu-item">
                        <!--begin:Menu link-->
                        <a class="menu-link {{ Route::is('sponsorships.index') ? 'active' : '' }}" href="/loading" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-dismiss="click" data-bs-placement="right">
                            <span class="menu-bullet">
                                <span class="bullet bullet-dot"></span>
                            </span>
                            <span class="menu-title">الكفالات</span>
                        </a>
                        <!--end:Menu link-->
                    </div>
                    @endcan
                    <!--end:Menu item-->

                    <!--begin:Menu item-->
                    @can('عرض الدفعات')
                    <div class="menu-item">
                        <!--begin:Menu link-->
                        <a class="menu-link {{ Route::is('payments.index') ? 'active' : '' }}" href="/loading" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-dismiss="click" data-bs-placement="right">
                            <span class="menu-bullet">
                                <span class="bullet bullet-dot"></span>
                            </span>
                            <span class="menu-title">الدفعات</span>
                        </a>
                        <!--end:Menu link-->
                    </div>
                    @endcan
                    <!--end:Menu item-->
                </div>
                <!--end:Menu sub-->
            </div>
           @endcan
           <div data-kt-menu-trigger="click" class="menu-item menu-accordion">
            <!--begin:Menu item-->
            <div class="menu-item">
                <!--begin:Menu link-->
                <a class="menu-link {{ Route::is('study.create') ? 'active' : '' }}" href="/loading" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-dismiss="click" data-bs-placement="right">
                    <span class="menu-bullet">
                        <span class="bullet bullet-dot"></span>
                    </span>
                    <span class="menu-title">صفحة الدراسات</span>
                </a>
                <!--end:Menu link-->
            </div>
            <!--end:Menu item-->
        </div>
           @can('التقارير')
           <div data-kt-menu-trigger="click" class="menu-item menu-accordion {{ Route::is('reports.data') || Route::is('reports.data.families') || Route::is('reports.data.sponser') || Route::is('reports.data.account') || Route::is('reports.data.study') ? 'show' : '' }}">
               <!--begin:Menu link-->
               <span class="menu-link">
                   <span class="menu-icon">
                       <i class="ki-duotone ki-rescue fs-1">
                           <span class="path1"></span>
                           <span class="path2"></span>
                       </i>
                   </span>
                   <span class="menu-title">التقارير</span>
                   <span class="menu-arrow"></span>
               </span>
               <!--end:Menu link-->

               <!--begin:Menu sub-->
               <div class="menu-sub menu-sub-accordion">
                   <!--begin:Menu item-->
                   @can('تقارير الاطفال')
                   <div class="menu-item">
                       <!--begin:Menu link-->
                       <a class="menu-link {{ Route::is('reports.data') ? 'active' : '' }}" href="/loading" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-dismiss="click" data-bs-placement="right">
                           <span class="menu-bullet">
                               <span class="bullet bullet-dot"></span>
                           </span>
                           <span class="menu-title">تقارير الاطفال</span>
                       </a>
                       <!--end:Menu link-->
                   </div>
                   @endcan
                   @can('تقارير الاطفال')
                   <div class="menu-item">
                       <!--begin:Menu link-->
                       <a class="menu-link {{ Route::is('reports.data.study') ? 'active' : '' }}" href="/loading" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-dismiss="click" data-bs-placement="right">
                           <span class="menu-bullet">
                               <span class="bullet bullet-dot"></span>
                           </span>
                           <span class="menu-title">تقارير الدراسة</span>
                       </a>
                       <!--end:Menu link-->
                   </div>
                   @endcan
                   <!--end:Menu item-->

                   <!--begin:Menu item-->
                   @can('تقارير العائلات')
                   <div class="menu-item">
                       <!--begin:Menu link-->
                       <a class="menu-link {{ Route::is('reports.data.families') ? 'active' : '' }}" href="/loading" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-dismiss="click" data-bs-placement="right">
                           <span class="menu-bullet">
                               <span class="bullet bullet-dot"></span>
                           </span>
                           <span class="menu-title">تقارير العائلات</span>
                       </a>
                       <!--end:Menu link-->
                   </div>
                   @endcan
                   <!--end:Menu item-->

                   <!--begin:Menu item-->
                   @can('تقارير الكفلاءوالكفالات')
                   <div class="menu-item">
                       <!--begin:Menu link-->
                       <a class="menu-link {{ Route::is('reports.data.sponser') ? 'active' : '' }}" href="/loading" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-dismiss="click" data-bs-placement="right">
                           <span class="menu-bullet">
                               <span class="bullet bullet-dot"></span>
                           </span>
                           <span class="menu-title">تقارير الكفالات و الكفلاء</span>
                       </a>
                       <!--end:Menu link-->
                   </div>
                   @endcan
                   <!--end:Menu item-->

                   <!--begin:Menu item-->
                   @can('تقارير الحسابات')
                   <div class="menu-item">
                       <!--begin:Menu link-->
                       <a class="menu-link {{ Route::is('reports.data.account') ? 'active' : '' }}" href="/loading" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-dismiss="click" data-bs-placement="right">
                           <span class="menu-bullet">
                               <span class="bullet bullet-dot"></span>
                           </span>
                           <span class="menu-title">تقارير الحسابات</span>
                       </a>
                       <!--end:Menu link-->
                   </div>
                   @endcan
                   <!--end:Menu item-->
               </div>
               <!--end:Menu sub-->
           </div>
           @endcan

            @can('الحسابات')
            <div data-kt-menu-trigger="click" class="menu-item menu-accordion {{ Route::is('accounts.index') ? 'show' : '' }}">
                <!--begin:Menu link-->
                <span class="menu-link">
                    <span class="menu-icon">
                        <i class="fa-solid fa-money-bill-wave fs-3"></i>
                        <span class="path1"></span>
                        <span class="path2"></span>
                    </i>
                </span>
                <span class="menu-title"> الحسابات</span>
                <span class="menu-arrow"></span>
                </span>
                <!--end:Menu link-->

                <!--begin:Menu sub-->
                <div class="menu-sub menu-sub-accordion">
                    <!--begin:Menu item-->
                    <div class="menu-item">
                        <!--begin:Menu link-->
                        <a class="menu-link {{ Route::is('accounts.create') ? 'active' : '' }}" href="/loading" title="اضافة حساب" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-dismiss="click" data-bs-placement="right">
                            <span class="menu-bullet">
                                <span class="bullet bullet-dot"></span>
                            </span>
                            <span class="menu-title">اضافة حساب</span>
                        </a>
                        <!--end:Menu link-->
                    </div>
                    <!--end:Menu item-->
                    <div class="menu-item">
                        <!--begin:Menu link-->
                        <a class="menu-link {{ Route::is('accounts.index') ? 'active' : '' }}" href="/loading" title="الحسابات" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-dismiss="click" data-bs-placement="right">
                            <span class="menu-bullet">
                                <span class="bullet bullet-dot"></span>
                            </span>
                            <span class="menu-title">كل الحسابات</span>
                        </a>
                        <!--end:Menu link-->
                    </div>
                    <div class="menu-item">
                        <!--begin:Menu link-->
                        <a class="menu-link {{ Route::is('accounts.monthly-annual') ? 'active' : '' }}" href="/loading" title=" الحسابات الشهرية والسنوية" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-dismiss="click" data-bs-placement="right">
                            <span class="menu-bullet">
                                <span class="bullet bullet-dot"></span>
                            </span>
                            <span class="menu-title">الحسابات الشهرية والسنوية</span>
                        </a>
                        <!--end:Menu link-->
                    </div>
                </div>
                <!--end:Menu sub-->
            </div>
            @endcan
            @can('ادارة المستخدمين')
            <div data-kt-menu-trigger="click" class="menu-item menu-accordion {{ Route::is('users.index') || Route::is('roles.index') ? 'show' : '' }}">
                <!--begin:Menu link-->
                <span class="menu-link">
                    <span class="menu-icon">
                        <i class="fa-solid fa-users fs-3"></i>
                        <span class="path1"></span>
                        <span class="path2"></span>
                    </span>
                    <span class="menu-title">ادارة المستخدمين</span>
                    <span class="menu-arrow"></span>
                </span>
                <!--end:Menu link-->

                <!--begin:Menu sub-->
                <div class="menu-sub menu-sub-accordion">
                    <!--begin:Menu item-->
                    @can('عرض المستخدمين')
                    <div class="menu-item">
                        <!--begin:Menu link-->
                        <a class="menu-link {{ Route::is('users.index') ? 'active' : '' }}" href="/loading" title="المستخدمين" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-dismiss="click" data-bs-placement="right">
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
                    @can('عرض الصلاحيات')
                    <div class="menu-item">
                        <!--begin:Menu link-->
                        <a class="menu-link {{ Route::is('roles.index') ? 'active' : '' }}" href="/loading" title="ادارة الصلاحيات" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-dismiss="click" data-bs-placement="right">
                            <span class="menu-bullet">
                                <span class="bullet bullet-dot"></span>
                            </span>
                            <span class="menu-title">الصلاحيات</span>
                        </a>
                        <!--end:Menu link-->
                    </div>
                    @endcan
                    <!--end:Menu item-->
                </div>
                <!--end:Menu sub-->
            </div>
            @endcan
            @can('عرض عناصر القوائم')

            <div data-kt-menu-trigger="click" class="menu-item menu-accordion {{ Route::is('mainform.create', 'mainform.index') ? 'show' : '' }}">                <!--begin:Menu link-->
                <span class="menu-link">
                    <span class="menu-icon">
                        <i class="ki-duotone ki-rescue fs-1">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                    </span>
                    <span class="menu-title">الخيارات
                    </span>
                    <span class="menu-arrow"></span>
                </span>
                <!--end:Menu link-->
                <!--begin:Menu sub-->
                <div class="menu-sub menu-sub-accordion">
                    <!--begin:Menu item-->


                    <div data-kt-menu-trigger="click" class="menu-item menu-accordion ">
                        <!--begin:Menu link-->
                        <span class="menu-link">
                            <span class="menu-bullet">
                                <span class="bullet bullet-dot"></span>
                            </span>
                            <span class="menu-title">لحذف وتعديل االاختيارات</span>
                            <span class="menu-arrow"></span>
                        </span>
                        <!--end:Menu link-->
                        <!--begin:Menu sub-->
                        <div class="menu-sub menu-sub-accordion">
                            <!--begin:Menu item for Years-->
                            <div class="menu-item">
                                <a class="menu-link {{ request()->routeIs('years.index') ? 'active' : '' }}" href="/loading">
                                    <span class="menu-bullet">
                                        <span class="bullet bullet-dot"></span>
                                    </span>
                                    <span class="menu-title">السنين</span>
                                </a>
                            </div>

                            <!--begin:Menu item for Organization Type-->
                            <div class="menu-item">
                                <a class="menu-link {{ request()->routeIs('organization-type.index') ? 'active' : '' }}" href="/loading">
                                    <span class="menu-bullet">
                                        <span class="bullet bullet-dot"></span>
                                    </span>
                                    <span class="menu-title">نوع المنظمة</span>
                                </a>
                            </div>
                            <!--end:Menu item-->

                            <!--begin:Menu item for Family Type-->
                            <div class="menu-item">
                                <a class="menu-link {{ request()->routeIs('family-type.index') ? 'active' : '' }}" href="/loading">
                                    <span class="menu-bullet">
                                        <span class="bullet bullet-dot"></span>
                                    </span>
                                    <span class="menu-title">نوع العائلة</span>
                                </a>
                            </div>
                            <!--end:Menu item-->

                            <!--begin:Menu item for Development Courses-->
                            <div class="menu-item">
                                <a class="menu-link {{ request()->routeIs('development-courses.index') ? 'active' : '' }}" href="/loading">
                                    <span class="menu-bullet">
                                        <span class="bullet bullet-dot"></span>
                                    </span>
                                    <span class="menu-title">الدورات التنموية</span>
                                </a>
                            </div>
                            <!--end:Menu item-->

                            <!--begin:Menu item for Diseases-->
                            <div class="menu-item">
                                <a class="menu-link {{ request()->routeIs('disease-types.index') ? 'active' : '' }}" href="/loading">
                                    <span class="menu-bullet">
                                        <span class="bullet bullet-dot"></span>
                                    </span>
                                    <span class="menu-title">الأمراض</span>
                                </a>
                            </div>
                            <!--end:Menu item-->

                            <!--begin:Menu item for Talents-->
                            <div class="menu-item">
                                <a class="menu-link {{ request()->routeIs('talents.index') ? 'active' : '' }}" href="/loading">
                                    <span class="menu-bullet">
                                        <span class="bullet bullet-dot"></span>
                                    </span>
                                    <span class="menu-title">المواهب</span>
                                </a>
                            </div>
                            <!--end:Menu item-->

                            <!--begin:Menu item for Supplies-->
                            <div class="menu-item">
                                <a class="menu-link {{ request()->routeIs('supplies.index') ? 'active' : '' }}" href="/loading">
                                    <span class="menu-bullet">
                                        <span class="bullet bullet-dot"></span>
                                    </span>
                                    <span class="menu-title">المواد الاساسية</span>
                                </a>
                            </div>
                            <!--end:Menu item-->

                            <!--begin:Menu item for Lessons-->
                            <div class="menu-item">
                                <a class="menu-link {{ request()->routeIs('lessons.index') ? 'active' : '' }}" href="/loading">
                                    <span class="menu-bullet">
                                        <span class="bullet bullet-dot"></span>
                                    </span>
                                    <span class="menu-title">الدروس</span>
                                </a>
                            </div>
                            <!--end:Menu item-->

                            <!--begin:Menu item for Housing Type-->
                            <div class="menu-item">
                                <a class="menu-link {{ request()->routeIs('housing-type.index') ? 'active' : '' }}" href="/loading">
                                    <span class="menu-bullet">
                                        <span class="bullet bullet-dot"></span>
                                    </span>
                                    <span class="menu-title">نوع السكن</span>
                                </a>
                            </div>
                            <!--end:Menu item-->

                        </div>
                        <!--end:Menu sub-->
                    </div>



                </div>

            </div>
           @endcan
            
    <div data-kt-menu-trigger="click" class="menu-item menu-accordion {{ Route::is('admin.governorates.create', 'admin.governorates.index', 'admin.cities.index') ? 'here show' : '' }}">
    <!--begin:Menu link-->
    <span class="menu-link">
        <span class="menu-icon">
            <i class="fa-solid fa-earth-americas fs-3"></i>
        </span>
        <span class="menu-title">المحافظات والمدن</span>
        <span class="menu-arrow"></span>
    </span>
    <!--end:Menu link-->

    <!--begin:Menu sub-->
    <div class="menu-sub menu-sub-accordion">
        <!--begin:Menu item-->
        <div class="menu-item">
            <!--begin:Menu link-->
            <a class="menu-link {{ Route::is('admin.governorates.create', 'admin.governorates.index') ? 'active' : '' }}" 
               href="{{ route('admin.governorates.index') }}" 
               title="ادارة المحافظات" 
               data-bs-toggle="tooltip" 
               data-bs-trigger="hover" 
               data-bs-dismiss="click" 
               data-bs-placement="right">
                <span class="menu-bullet">
                    <span class="bullet bullet-dot"></span>
                </span>
                <span class="menu-title">المحافظات</span>
            </a>
            <!--end:Menu link-->
        </div>
        <!--end:Menu item-->

        <!--begin:Menu item-->
        <div class="menu-item">
            <!--begin:Menu link-->
            <a class="menu-link {{ Route::is('admin.cities.index') ? 'active' : '' }}" 
               href="{{ route('admin.cities.index') }}" 
               title="ادارة المدن" 
               data-bs-toggle="tooltip" 
               data-bs-trigger="hover" 
               data-bs-dismiss="click" 
               data-bs-placement="right">
                <span class="menu-bullet">
                    <span class="bullet bullet-dot"></span>
                </span>
                <span class="menu-title">المدن</span>
            </a>
            <!--end:Menu link-->
        </div>
        <!--end:Menu item-->
    </div>
    <!--end:Menu sub-->
</div>
            @can('إدارة النسخ الاحتياطية')
            <div data-kt-menu-trigger="click" class="menu-item menu-accordion {{ Route::is('accounts.index') ? 'show' : '' }}">
                <!--begin:Menu link-->
                <span class="menu-link">
                    <span class="menu-icon">
                        <i class="fa-solid fa-money-bill-wave fs-3"></i>
                        <span class="path1"></span>
                        <span class="path2"></span>
                    </i>
                </span>
                <span class="menu-title">إدارة النسخ الاحتياطية</span>
                <span class="menu-arrow"></span>
                </span>
                <!--end:Menu link-->

                <!--begin:Menu sub-->
                <div class="menu-sub menu-sub-accordion">
                    <!--begin:Menu item-->
                    <div class="menu-item">
                        <!--begin:Menu link-->
                        <a class="menu-link {{ Route::is('backups.index') ? 'active' : '' }}" href="/loading" title="النسخة الاحتياطية" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-dismiss="click" data-bs-placement="right">
                            <span class="menu-bullet">
                                <span class="bullet bullet-dot"></span>
                            </span>
                            <span class="menu-title">النسخة الاحتياطية</span>
                        </a>
                        <!--end:Menu link-->
                    </div>
                    <!--end:Menu item-->
                </div>
                <!--end:Menu sub-->
            </div>
            @endcan
            <!--end:Menu item-->
        </div>
        <!--end::Sidebar menu-->
        <!--begin::Footer-->
        <div class="app-sidebar-project-default app-sidebar-project-minimize text-center min-h-lg-400px flex-column-auto d-flex flex-column justify-content-end" id="kt_app_sidebar_footer">
            <!--begin::Title-->
            <h2 class="fw-bold text-gray-800">قال رسول الله ﷺ</h2>
            <!--end::Title-->
            <!--begin::Description-->
            <div class="fw-semibold text-gray-700 fs-7 lh-2 px-7 mb-2">أنا وكافل اليتيم في الجنة كهاتين</div>
            <!--end::Description-->
            <!--begin::Illustration-->
            <img class="mx-auto h-150px h-lg-175px mb-4" src="{{ asset('assets/images/ORG-1024x844.png') }}" alt="" />

        </div>
        <!--end::Footer-->
    </div>
    <!--end::Main-->
</div
@extends('layouts.master')
@section('title')
    تفاصيل مدير الوحدة الصحية
@endsection
@section('page-header')
    <!--begin::Page title-->
    <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
        <!--begin::Title-->
        <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">
            تفاصيل المدير
        </h1>
        <!--end::Title-->
        <!--begin::Breadcrumb-->
        <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
            <!--begin::Item-->
            <li class="breadcrumb-item text-muted">
                <a href="{{route('admin.dashboard')}}" class="text-muted text-hover-primary">الصفحة الرئيسية</a>
            </li>
            <!--end::Item-->
            <!--begin::Item-->
            <li class="breadcrumb-item">
                <span class="bullet bg-gray-200 w-5px h-2px"></span>
            </li>
            <!--end::Item-->
            <!--begin::Item-->
            <li class="breadcrumb-item text-muted">
                <a href="{{route('admin.health-center-managers.index')}}" class="text-muted text-hover-primary">مديري الوحدات الصحية</a>
            </li>
            <!--end::Item-->
            <!--begin::Item-->
            <li class="breadcrumb-item">
                <span class="bullet bg-gray-200 w-5px h-2px"></span>
            </li>
            <!--end::Item-->
            <!--begin::Item-->
            <li class="breadcrumb-item text-dark">تفاصيل المدير</li>
            <!--end::Item-->
        </ul>
        <!--end::Breadcrumb-->
    </div>
    <!--end::Page title-->
@endsection
@section('content')
    <!--begin::Post-->
    <div class="post d-flex flex-column-fluid" id="kt_post">
        <!--begin::Container-->
        <div id="kt_content_container" class="container-xxl">
            
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!--begin::Profile Layout-->
            <div class="d-flex flex-column flex-xl-row">
                <!--begin::Sidebar-->
                <div class="flex-column flex-lg-row-auto w-xl-350px mb-10">
                    <!--begin::Card-->
                    <div class="card mb-5 mb-xl-8">
                        <!--begin::Card body-->
                        <div class="card-body">
                            <!--begin::Summary-->
                            <div class="d-flex flex-center flex-column py-5">
                                <!--begin::Avatar-->
                                <div class="symbol symbol-100px symbol-circle mb-7">
                                    <div class="symbol-label bg-light-info">
                                        <i class="ki-duotone ki-profile-user fs-1 text-info">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                            <span class="path3"></span>
                                            <span class="path4"></span>
                                        </i>
                                    </div>
                                </div>
                                <!--end::Avatar-->
                                <!--begin::Name-->
                                <span class="fs-3 text-gray-800 text-hover-primary fw-bold mb-3">{{ $healthCenterManager->full_name }}</span>
                                <!--end::Name-->
                                <!--begin::Position-->
                                <div class="mb-9">
                                    <div class="badge badge-lg badge-light-primary d-inline">مدير وحدة صحية</div>
                                    @if($healthCenterManager->is_verified)
                                        <div class="badge badge-lg badge-light-success d-inline ms-2">نشط</div>
                                    @else
                                        <div class="badge badge-lg badge-light-danger d-inline ms-2">غير نشط</div>
                                    @endif
                                </div>
                                <!--end::Position-->
                            </div>
                            <!--end::Summary-->
                            <!--begin::Details toggle-->
                            <div class="d-flex flex-stack fs-4 py-3">
                                <div class="fw-bold rotate collapsible" data-bs-toggle="collapse" href="#kt_user_view_details" role="button" aria-expanded="false" aria-controls="kt_user_view_details">
                                    التفاصيل
                                    <span class="ms-2 rotate-180">
                                        <i class="ki-duotone ki-down fs-3"></i>
                                    </span>
                                </div>
                            </div>
                            <!--end::Details toggle-->
                            <div class="separator"></div>
                            <!--begin::Details content-->
                            <div id="kt_user_view_details" class="collapse show">
                                <div class="pb-5 fs-6">
                                    <!--begin::Details item-->
                                    <div class="fw-bold mt-5">رقم الهاتف</div>
                                    <div class="text-gray-600">{{ $healthCenterManager->phone }}</div>
                                    <!--begin::Details item-->
                                    <!--begin::Details item-->
                                    <div class="fw-bold mt-5">البريد الإلكتروني</div>
                                    <div class="text-gray-600">{{ $healthCenterManager->email ?? 'غير محدد' }}</div>
                                    <!--begin::Details item-->
                                    <!--begin::Details item-->
                                    <div class="fw-bold mt-5">الرقم القومي</div>
                                    <div class="text-gray-600">{{ $healthCenterManager->national_id }}</div>
                                    <!--begin::Details item-->
                                    <!--begin::Details item-->
                                    <div class="fw-bold mt-5">تاريخ الإنشاء</div>
                                    <div class="text-gray-600">{{ $healthCenterManager->created_at->format('Y-m-d H:i') }}</div>
                                    <!--begin::Details item-->
                                    <!--begin::Details item-->
                                    <div class="fw-bold mt-5">آخر تحديث</div>
                                    <div class="text-gray-600">{{ $healthCenterManager->updated_at->format('Y-m-d H:i') }}</div>
                                    <!--begin::Details item-->
                                </div>
                            </div>
                            <!--end::Details content-->
                        </div>
                        <!--end::Card body-->
                    </div>
                    <!--end::Card-->
                    <!--begin::Connected Accounts-->
                    <div class="card mb-5 mb-xl-8">
                        <!--begin::Card header-->
                        <div class="card-header border-0">
                            <div class="card-title">
                                <h3 class="fw-bold m-0">إجراءات سريعة</h3>
                            </div>
                        </div>
                        <!--end::Card header-->
                        <!--begin::Card body-->
                        <div class="card-body pt-2">
                            <!--begin::Notice-->
                            <div class="d-flex flex-stack">
                                <a href="{{ route('admin.health-center-managers.edit', $healthCenterManager->id) }}" class="btn btn-sm btn-light-primary">
                                    <i class="ki-duotone ki-pencil fs-3"></i>
                                    تعديل البيانات
                                </a>
                            </div>
                            <!--end::Notice-->
                            <div class="separator separator-dashed my-5"></div>
                            <div class="d-flex flex-stack">
                                <form action="{{ route('admin.health-center-managers.toggle-status', $healthCenterManager->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-sm {{ $healthCenterManager->is_verified ? 'btn-light-warning' : 'btn-light-success' }}">
                                        <i class="ki-duotone ki-toggle-{{ $healthCenterManager->is_verified ? 'off' : 'on' }} fs-3"></i>
                                        {{ $healthCenterManager->is_verified ? 'تعطيل المدير' : 'تفعيل المدير' }}
                                    </button>
                                </form>
                            </div>
                            <div class="separator separator-dashed my-5"></div>
                            <div class="d-flex flex-stack">
                                <button type="button" class="btn btn-sm btn-light-danger" onclick="deleteManager({{ $healthCenterManager->id }})">
                                    <i class="ki-duotone ki-trash fs-3"></i>
                                    حذف المدير
                                </button>
                            </div>
                        </div>
                        <!--end::Card body-->
                    </div>
                    <!--end::Connected Accounts-->
                </div>
                <!--end::Sidebar-->
                <!--begin::Content-->
                <div class="flex-lg-row-fluid ms-lg-15">
                    <!--begin::Health Center Info-->
                    <div class="card mb-5 mb-xl-10">
                        <!--begin::Card header-->
                        <div class="card-header border-0 cursor-pointer">
                            <div class="card-title m-0">
                                <h3 class="fw-bold m-0">معلومات الوحدة الصحية</h3>
                            </div>
                        </div>
                        <!--begin::Card header-->
                        <!--begin::Card body-->
                        <div class="card-body border-0 p-9">
                            @if($healthCenterManager->healthCenter)
                                <!--begin::Row-->
                                <div class="row mb-7">
                                    <!--begin::Label-->
                                    <label class="col-lg-4 fw-semibold text-muted">اسم الوحدة الصحية</label>
                                    <!--end::Label-->
                                    <!--begin::Col-->
                                    <div class="col-lg-8">
                                        <span class="fw-bold fs-6 text-gray-800">{{ $healthCenterManager->healthCenter->name }}</span>
                                    </div>
                                    <!--end::Col-->
                                </div>
                                <!--end::Row-->
                                <!--begin::Row-->
                                <div class="row mb-7">
                                    <!--begin::Label-->
                                    <label class="col-lg-4 fw-semibold text-muted">العنوان</label>
                                    <!--end::Label-->
                                    <!--begin::Col-->
                                    <div class="col-lg-8 fv-row">
                                        <span class="fw-semibold text-gray-800 fs-6">{{ $healthCenterManager->healthCenter->address }}</span>
                                    </div>
                                    <!--end::Col-->
                                </div>
                                <!--end::Row-->
                                <!--begin::Row-->
                                <div class="row mb-7">
                                    <!--begin::Label-->
                                    <label class="col-lg-4 fw-semibold text-muted">المحافظة والمدينة</label>
                                    <!--end::Label-->
                                    <!--begin::Col-->
                                    <div class="col-lg-8 fv-row"></div>
                                        <span class="fw-semibold text-gray-800 fs-6">
                                            {{ $healthCenterManager->healthCenter->governorate->name }} - {{ $healthCenterManager->healthCenter->city->name }}
                                        </span>
                                    </div>
                                    <!--end::Col-->
                                </div>
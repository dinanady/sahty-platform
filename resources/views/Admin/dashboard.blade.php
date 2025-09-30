@extends('layouts.master')
@section('title')
    لوحة التحكم
@endsection

@section('page-header')
    <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
        <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">
            لوحة التحكم
        </h1>
        <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
            <li class="breadcrumb-item text-muted">
                <a href="{{route('admin.dashboard')}}" class="text-muted text-hover-primary">الصفحة الرئيسية</a>
            </li>
            <li class="breadcrumb-item">
                <span class="bullet bg-gray-200 w-5px h-2px"></span>
            </li>
            <li class="breadcrumb-item text-dark">لوحة التحكم</li>
        </ul>
    </div>
@endsection

@section('content')
    <div class="post d-flex flex-column-fluid" id="kt_post">
        <div id="kt_content_container" class="container-xxl">
            
            {{-- Statistics Cards --}}
            <div class="row g-6 mb-6">
                {{-- Total Health Centers --}}
                <div class="col-xl-3 col-md-6">
                    <div class="card card-flush h-100">
                        <div class="card-header pt-5">
                            <div class="card-title d-flex flex-column">
                                <span class="fs-2hx fw-bold text-dark me-2 lh-1">{{ $stats['total_health_centers'] }}</span>
                                <span class="text-gray-400 pt-1 fw-semibold fs-6">إجمالي الوحدات الصحية</span>
                            </div>
                        </div>
                        <div class="card-body d-flex align-items-end pt-0">
                            <div class="d-flex align-items-center flex-column w-100">
                                <div class="d-flex justify-content-between fw-bold fs-6 text-gray-400 w-100 mt-auto mb-2">
                                    <span>نشط: {{ $stats['active_health_centers'] }}</span>
                                    <span>معطل: {{ $stats['inactive_health_centers'] }}</span>
                                </div>
                                <div class="h-8px mx-3 w-100 bg-light-success rounded">
                                    <div class="bg-success rounded h-8px" role="progressbar" 
                                         style="width: {{ $stats['total_health_centers'] > 0 ? ($stats['active_health_centers'] / $stats['total_health_centers']) * 100 : 0 }}%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Total Managers --}}
                <div class="col-xl-3 col-md-6">
                    <div class="card card-flush h-100">
                        <div class="card-header pt-5">
                            <div class="card-title d-flex flex-column">
                                <span class="fs-2hx fw-bold text-dark me-2 lh-1">{{ $stats['total_managers'] }}</span>
                                <span class="text-gray-400 pt-1 fw-semibold fs-6">مديري الوحدات</span>
                            </div>
                        </div>
                        <div class="card-body d-flex align-items-end pt-0">
                            <div class="d-flex align-items-center flex-column w-100">
                                <div class="d-flex justify-content-between fw-bold fs-6 text-gray-400 w-100 mt-auto mb-2">
                                    <span>نشط: {{ $stats['active_managers'] }}</span>
                                    <span>بدون وحدة: {{ $managersWithoutHealthCenter }}</span>
                                </div>
                                <div class="h-8px mx-3 w-100 bg-light-primary rounded">
                                    <div class="bg-primary rounded h-8px" role="progressbar" 
                                         style="width: {{ $stats['total_managers'] > 0 ? ($stats['active_managers'] / $stats['total_managers']) * 100 : 0 }}%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Total Governorates --}}
                <div class="col-xl-3 col-md-6">
                    <div class="card card-flush h-100">
                        <div class="card-header pt-5">
                            <div class="card-title d-flex flex-column">
                                <span class="fs-2hx fw-bold text-dark me-2 lh-1">{{ $stats['total_governorates'] }}</span>
                                <span class="text-gray-400 pt-1 fw-semibold fs-6">المحافظات</span>
                            </div>
                        </div>
                        <div class="card-body d-flex align-items-end pt-0">
                            <a href="{{ route('admin.governorates.index') }}" class="btn btn-sm btn-light-primary w-100">
                                عرض الكل
                            </a>
                        </div>
                    </div>
                </div>

                {{-- Total Cities --}}
                <div class="col-xl-3 col-md-6">
                    <div class="card card-flush h-100">
                        <div class="card-header pt-5">
                            <div class="card-title d-flex flex-column">
                                <span class="fs-2hx fw-bold text-dark me-2 lh-1">{{ $stats['total_cities'] }}</span>
                                <span class="text-gray-400 pt-1 fw-semibold fs-6">المدن</span>
                            </div>
                        </div>
                        <div class="card-body d-flex align-items-end pt-0">
                            <a href="{{ route('admin.cities.index') }}" class="btn btn-sm btn-light-info w-100">
                                عرض الكل
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Alerts --}}
            @if($stats['health_centers_without_manager'] > 0)
            <div class="alert alert-warning d-flex align-items-center mb-6">
                <i class="ki-duotone ki-information-5 fs-2hx text-warning me-4">
                    <span class="path1"></span>
                    <span class="path2"></span>
                    <span class="path3"></span>
                </i>
                <div class="d-flex flex-column">
                    <h4 class="mb-1 text-dark">تنبيه</h4>
                    <span>يوجد {{ $stats['health_centers_without_manager'] }} وحدة صحية بدون مدير</span>
                </div>
            </div>
            @endif

            <div class="row g-6">
                {{-- Recent Health Centers --}}
                <div class="col-xl-8">
                    <div class="card card-flush h-xl-100">
                        <div class="card-header pt-7">
                            <h3 class="card-title align-items-start flex-column">
                                <span class="card-label fw-bold text-gray-800">أحدث الوحدات الصحية</span>
                                <span class="text-gray-400 mt-1 fw-semibold fs-6">آخر {{ $recentHealthCenters->count() }} وحدات تم إضافتها</span>
                            </h3>
                            <div class="card-toolbar">
                                <a href="{{ route('admin.health-centers.index') }}" class="btn btn-sm btn-light">
                                    عرض الكل
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-row-bordered table-row-gray-100 align-middle gs-0 gy-3">
                                    <thead>
                                        <tr class="fw-bold text-muted">
                                            <th class="min-w-150px">الاسم</th>
                                            <th class="min-w-120px">الموقع</th>
                                            <th class="min-w-120px">المدير</th>
                                            <th class="min-w-100px">الحالة</th>
                                            <th class="min-w-100px text-end">الإجراءات</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($recentHealthCenters as $center)
                                        <tr>
                                            <td>
                                                <a href="{{ route('admin.health-centers.show', $center->id) }}" class="text-gray-800 text-hover-primary fw-bold">
                                                    {{ $center->name }}
                                                </a>
                                            </td>
                                            <td>
                                                <span class="text-gray-600 fw-bold">
                                                    {{ $center->city->name }}, {{ $center->city->governorate->name }}
                                                </span>
                                            </td>
                                            <td>
                                                @if($center->manager)
                                                    <span class="text-gray-800 fw-bold">
                                                        {{ $center->manager->first_name }} {{ $center->manager->last_name }}
                                                    </span>
                                                @else
                                                    <span class="badge badge-light-warning">لا يوجد</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($center->is_active)
                                                    <span class="badge badge-light-success">نشط</span>
                                                @else
                                                    <span class="badge badge-light-danger">معطل</span>
                                                @endif
                                            </td>
                                            <td class="text-end">
                                                <a href="{{ route('admin.health-centers.show', $center->id) }}" class="btn btn-sm btn-light btn-active-light-primary">
                                                    عرض
                                                </a>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="5" class="text-center text-muted py-5">
                                                لا توجد وحدات صحية
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Distribution by Governorate --}}
                <div class="col-xl-4">
                    <div class="card card-flush h-xl-100">
                        <div class="card-header pt-7">
                            <h3 class="card-title align-items-start flex-column">
                                <span class="card-label fw-bold text-gray-800">توزيع الوحدات</span>
                                <span class="text-gray-400 mt-1 fw-semibold fs-6">حسب المحافظات</span>
                            </h3>
                        </div>
                        <div class="card-body pt-5">
                            @forelse($healthCentersByGovernorate as $governorate)
                            <div class="d-flex align-items-center mb-7">
                                <div class="symbol symbol-50px me-5">
                                    <span class="symbol-label bg-light-primary">
                                        <i class="ki-duotone ki-geolocation fs-2x text-primary">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                        </i>
                                    </span>
                                </div>
                                <div class="flex-grow-1">
                                    <span class="text-gray-800 fw-bold fs-6 d-block">{{ $governorate->name }}</span>
                                    <span class="text-muted fw-semibold d-block">
                                        {{ $governorate->health_centers_count }} وحدة صحية
                                    </span>
                                </div>
                                <span class="badge badge-light-primary fs-8 fw-bold">
                                    {{ $stats['total_health_centers'] > 0 ? round(($governorate->health_centers_count / $stats['total_health_centers']) * 100) : 0 }}%
                                </span>
                            </div>
                            @empty
                            <div class="text-center text-muted py-10">
                                لا توجد بيانات
                            </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            {{-- Quick Actions --}}
            <div class="row g-6 mt-6">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">إجراءات سريعة</h3>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-3">
                                    <a href="{{ route('admin.health-centers.create') }}" class="btn btn-light-primary w-100">
                                        <i class="ki-duotone ki-plus fs-2"></i>
                                        إضافة وحدة صحية
                                    </a>
                                </div>
                                <div class="col-md-3">
                                    <a href="{{ route('admin.health-center-managers.create') }}" class="btn btn-light-success w-100">
                                        <i class="ki-duotone ki-user-tick fs-2"></i>
                                        إضافة مدير
                                    </a>
                                </div>
                                <div class="col-md-3">
                                    <a href="{{ route('admin.governorates.index') }}" class="btn btn-light-info w-100">
                                        <i class="ki-duotone ki-geolocation fs-2"></i>
                                        إدارة المحافظات
                                    </a>
                                </div>
                                <div class="col-md-3">
                                    <a href="{{ route('admin.cities.index') }}" class="btn btn-light-warning w-100">
                                        <i class="ki-duotone ki-map fs-2"></i>
                                        إدارة المدن
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
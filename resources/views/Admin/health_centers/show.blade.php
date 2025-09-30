@extends('layouts.master')
@section('title')
    تفاصيل الوحدة الصحية
@endsection
@section('page-header')
    <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
        <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">
            تفاصيل الوحدة الصحية: {{ $healthCenter->name }}
        </h1>
        <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
            <li class="breadcrumb-item text-muted">
                <a href="{{route('admin.dashboard')}}" class="text-muted text-hover-primary">الصفحة الرئيسية</a>
            </li>
            <li class="breadcrumb-item">
                <span class="bullet bg-gray-200 w-5px h-2px"></span>
            </li>
            <li class="breadcrumb-item text-muted">
                <a href="{{route('admin.health-centers.index')}}" class="text-muted text-hover-primary">الوحدات الصحية</a>
            </li>
            <li class="breadcrumb-item">
                <span class="bullet bg-gray-200 w-5px h-2px"></span>
            </li>
            <li class="breadcrumb-item text-dark">تفاصيل الوحدة</li>
        </ul>
    </div>
@endsection

@section('content')
    <div class="post d-flex flex-column-fluid" id="kt_post">
        <div id="kt_content_container" class="container-xxl">
            
            {{-- Header Actions --}}
            <div class="d-flex justify-content-end mb-6">
                <a href="{{route('admin.health-centers.edit', $healthCenter->id)}}" class="btn btn-primary me-3">
                    <i class="ki-duotone ki-pencil fs-2"></i>
                    تعديل
                </a>
                <a href="{{route('admin.health-centers.index')}}" class="btn btn-secondary">
                    <i class="ki-duotone ki-arrow-left fs-2"></i>
                    العودة للقائمة
                </a>
            </div>

            <div class="row g-6">
                {{-- Basic Information --}}
                <div class="col-xl-8">
                    <div class="card mb-6">
                        <div class="card-header">
                            <div class="card-title">
                                <h3>المعلومات الأساسية</h3>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row mb-5">
                                <label class="col-lg-4 fw-bold text-muted">اسم الوحدة الصحية</label>
                                <div class="col-lg-8">
                                    <span class="fw-bolder fs-6 text-gray-800">{{ $healthCenter->name }}</span>
                                </div>
                            </div>
                            
                            <div class="row mb-5">
                                <label class="col-lg-4 fw-bold text-muted">رقم التسجيل</label>
                                <div class="col-lg-8">
                                    <span class="fw-bolder fs-6 text-gray-800">{{ $healthCenter->registration_number }}</span>
                                </div>
                            </div>

                            <div class="row mb-5">
                                <label class="col-lg-4 fw-bold text-muted">العنوان</label>
                                <div class="col-lg-8">
                                    <span class="fw-bolder fs-6 text-gray-800">{{ $healthCenter->address }}</span>
                                </div>
                            </div>

                            <div class="row mb-5">
                                <label class="col-lg-4 fw-bold text-muted">رقم الهاتف</label>
                                <div class="col-lg-8">
                                    <span class="fw-bolder fs-6 text-gray-800">{{ $healthCenter->phone }}</span>
                                </div>
                            </div>

                            <div class="row mb-5">
                                <label class="col-lg-4 fw-bold text-muted">الحالة</label>
                                <div class="col-lg-8">
                                    @if($healthCenter->is_active)
                                        <span class="badge badge-light-success">نشط</span>
                                    @else
                                        <span class="badge badge-light-danger">غير نشط</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Location Information --}}
                    <div class="card mb-6">
                        <div class="card-header">
                            <div class="card-title">
                                <h3>معلومات الموقع</h3>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row mb-5">
                                <label class="col-lg-4 fw-bold text-muted">المحافظة</label>
                                <div class="col-lg-8">
                                    <span class="fw-bolder fs-6 text-gray-800">{{ $healthCenter->city->governorate->name }}</span>
                                </div>
                            </div>

                            <div class="row mb-5">
                                <label class="col-lg-4 fw-bold text-muted">المدينة</label>
                                <div class="col-lg-8">
                                    <span class="fw-bolder fs-6 text-gray-800">{{ $healthCenter->city->name }}</span>
                                </div>
                            </div>

                            @if($healthCenter->latitude && $healthCenter->longitude)
                            <div class="row mb-5">
                                <label class="col-lg-4 fw-bold text-muted">الإحداثيات</label>
                                <div class="col-lg-8">
                                    <span class="fw-bolder fs-6 text-gray-800">
                                        {{ $healthCenter->latitude }}, {{ $healthCenter->longitude }}
                                    </span>
                                    <a href="https://www.google.com/maps?q={{ $healthCenter->latitude }},{{ $healthCenter->longitude }}" target="_blank" class="btn btn-sm btn-light-primary ms-2">
                                        <i class="ki-duotone ki-map fs-3"></i>
                                        عرض على الخريطة
                                    </a>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>

                    {{-- Working Hours --}}
                    <div class="card mb-6">
                        <div class="card-header">
                            <div class="card-title">
                                <h3>مواعيد العمل</h3>
                            </div>
                        </div>
                        <div class="card-body">
                            @php
                                $daysInArabic = [
                                    'sunday' => 'الأحد',
                                    'monday' => 'الاثنين',
                                    'tuesday' => 'الثلاثاء',
                                    'wednesday' => 'الأربعاء',
                                    'thursday' => 'الخميس',
                                    'friday' => 'الجمعة',
                                    'saturday' => 'السبت'
                                ];
                                $workingHours = $healthCenter->working_hours ?? [];
                            @endphp

                            @if(!empty($workingHours))
                                <div class="table-responsive">
                                    <table class="table table-row-bordered table-row-gray-100 align-middle gs-0 gy-3">
                                        <thead>
                                            <tr class="fw-bolder text-muted">
                                                <th class="min-w-150px">اليوم</th>
                                                <th class="min-w-120px">من</th>
                                                <th class="min-w-120px">إلى</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($workingHours as $day => $hours)
                                                <tr>
                                                    <td>
                                                        <span class="text-gray-800 fw-bolder">{{ $daysInArabic[$day] ?? $day }}</span>
                                                    </td>
                                                    <td>
                                                        <span class="badge badge-light-primary">{{ $hours['start_time'] }}</span>
                                                    </td>
                                                    <td>
                                                        <span class="badge badge-light-primary">{{ $hours['end_time'] }}</span>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="text-center text-muted py-10">
                                    <i class="ki-duotone ki-calendar fs-3x mb-5">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                    </i>
                                    <p class="fw-bold">لا توجد مواعيد عمل محددة</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Sidebar --}}
                <div class="col-xl-4">
                    {{-- Manager Info --}}
                    <div class="card mb-6">
                        <div class="card-header">
                            <div class="card-title">
                                <h3>مدير الوحدة الصحية</h3>
                            </div>
                        </div>
                        <div class="card-body text-center">
                            @if($healthCenter->manager)
                                <div class="symbol symbol-100px symbol-circle mb-5">
                                    <span class="symbol-label bg-light-primary text-primary fs-1 fw-bold">
                                        {{ substr($healthCenter->manager->first_name, 0, 1) }}{{ substr($healthCenter->manager->last_name, 0, 1) }}
                                    </span>
                                </div>
                                <div class="mb-3">
                                    <span class="fs-4 fw-bolder text-gray-800">
                                        {{ $healthCenter->manager->first_name }} {{ $healthCenter->manager->last_name }}
                                    </span>
                                </div>
                                <div class="mb-2">
                                    <i class="ki-duotone ki-phone fs-4 text-muted me-2"></i>
                                    <span class="text-gray-600">{{ $healthCenter->manager->phone }}</span>
                                </div>
                                @if($healthCenter->manager->email)
                                <div class="mb-5">
                                    <i class="ki-duotone ki-sms fs-4 text-muted me-2"></i>
                                    <span class="text-gray-600">{{ $healthCenter->manager->email }}</span>
                                </div>
                                @endif
                                <a href="{{ route('admin.health-center-managers.show', $healthCenter->manager->id) }}" class="btn btn-sm btn-light-primary">
                                    عرض الملف الشخصي
                                </a>
                            @else
                                <div class="text-muted py-10">
                                    <i class="ki-duotone ki-user fs-3x mb-5">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                    </i>
                                    <p class="fw-bold">لا يوجد مدير معين</p>
                                    <a href="{{ route('admin.health-center-managers.create') }}" class="btn btn-sm btn-primary mt-3">
                                        إضافة مدير
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Statistics --}}
                    <div class="card mb-6">
                        <div class="card-header">
                            <div class="card-title">
                                <h3>الإحصائيات</h3>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-7">
                                <div class="symbol symbol-50px me-5">
                                    <span class="symbol-label bg-light-primary">
                                        <i class="ki-duotone ki-doctor fs-2x text-primary">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                        </i>
                                    </span>
                                </div>
                                <div class="flex-grow-1">
                                    <span class="text-gray-800 fw-bolder fs-6">عدد الأطباء</span>
                                    <span class="text-muted fw-bold d-block">الأطباء المسجلين</span>
                                </div>
                                <span class="badge badge-light-primary fs-5">{{ $healthCenter->doctors->count() }}</span>
                            </div>

                            <div class="d-flex align-items-center">
                                <div class="symbol symbol-50px me-5">
                                    <span class="symbol-label bg-light-success">
                                        <i class="ki-duotone ki-calendar fs-2x text-success">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                        </i>
                                    </span>
                                </div>
                                <div class="flex-grow-1">
                                    <span class="text-gray-800 fw-bolder fs-6">المواعيد</span>
                                    <span class="text-muted fw-bold d-block">إجمالي المواعيد</span>
                                </div>
                                <span class="badge badge-light-success fs-5">{{ $healthCenter->appointments->count() }}</span>
                            </div>
                        </div>
                    </div>

                  {{-- Quick Actions --}}
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">
                                <h3>إجراءات سريعة</h3>
                            </div>
                        </div>
                        <div class="card-body">
                            <a href="{{ route('admin.health-centers.edit', $healthCenter->id) }}" class="btn btn-light-primary w-100 mb-3">
                                <i class="ki-duotone ki-pencil fs-2"></i>
                                تعديل البيانات
                            </a>
                            
                            <button type="button" class="btn btn-light-danger w-100" onclick="confirmDelete({{ $healthCenter->id }}, '{{ $healthCenter->name }}')">
                                <i class="ki-duotone ki-trash fs-2"></i>
                                حذف الوحدة الصحية
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Drugs and Vaccines Section --}}
            <div class="row g-6 mt-6">
                {{-- Drugs --}}
                <div class="col-xl-6">
                    <div class="card card-flush h-100">
                        <div class="card-header pt-7">
                            <h3 class="card-title align-items-start flex-column">
                                <span class="card-label fw-bold text-gray-800">الأدوية المتوفرة</span>
                                <span class="text-gray-400 mt-1 fw-semibold fs-6">{{ $healthCenter->drugs->count() }} دواء</span>
                            </h3>
                        </div>
                        <div class="card-body">
                            @if($healthCenter->drugs->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-row-bordered align-middle gs-0 gy-3">
                                        <thead>
                                            <tr class="fw-bold text-muted bg-light">
                                                <th class="min-w-150px">الدواء</th>
                                                <th class="min-w-80px">الكمية</th>
                                                <th class="min-w-80px">الحالة</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($healthCenter->drugs as $drug)
                                            <tr>
                                                <td>
                                                    <div class="d-flex flex-column">
                                                        <span class="text-gray-800 fw-bold">{{ $drug->name }}</span>
                                                        <span class="text-muted fs-7">{{ $drug->category ?? 'غير محدد' }}</span>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="badge badge-light-primary fs-6">{{ $drug->pivot->stock }}</span>
                                                </td>
                                                <td>
                                                    @if($drug->pivot->availability)
                                                        <span class="badge badge-light-success">متوفر</span>
                                                    @else
                                                        <span class="badge badge-light-danger">غير متوفر</span>
                                                    @endif
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="text-center py-10">
                                    <i class="ki-duotone ki-pill fs-3x text-muted mb-5">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                    </i>
                                    <p class="text-muted fw-bold">لا توجد أدوية موزعة على هذه الوحدة</p>
                                    <a href="{{ route('admin.drugs.index') }}" class="btn btn-sm btn-light-primary mt-3">
                                        توزيع أدوية
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Vaccines --}}
                <div class="col-xl-6">
                    <div class="card card-flush h-100">
                        <div class="card-header pt-7">
                            <h3 class="card-title align-items-start flex-column">
                                <span class="card-label fw-bold text-gray-800">اللقاحات المتوفرة</span>
                                <span class="text-gray-400 mt-1 fw-semibold fs-6">{{ $healthCenter->vaccines->count() }} لقاح</span>
                            </h3>
                        </div>
                        <div class="card-body">
                            @if($healthCenter->vaccines->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-row-bordered align-middle gs-0 gy-3">
                                        <thead>
                                            <tr class="fw-bold text-muted bg-light">
                                                <th class="min-w-150px">اللقاح</th>
                                                <th class="min-w-80px">الكمية</th>
                                                <th class="min-w-80px">الحالة</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($healthCenter->vaccines as $vaccine)
                                            <tr>
                                                <td>
                                                    <div class="d-flex flex-column">
                                                        <span class="text-gray-800 fw-bold">{{ $vaccine->name }}</span>
                                                        <span class="text-muted fs-7">{{ $vaccine->age_months_min }}-{{ $vaccine->age_months_max }} شهر</span>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="badge badge-light-success fs-6">{{ $vaccine->pivot->stock ?? 0 }}</span>
                                                </td>
                                                <td>
                                                    @if($vaccine->pivot->availability)
                                                        <span class="badge badge-light-success">متوفر</span>
                                                    @else
                                                        <span class="badge badge-light-danger">غير متوفر</span>
                                                    @endif
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="text-center py-10">
                                    <i class="ki-duotone ki-shield-tick fs-3x text-muted mb-5">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                    </i>
                                    <p class="text-muted fw-bold">لا توجد لقاحات موزعة على هذه الوحدة</p>
                                    <a href="{{ route('admin.vaccines.index') }}" class="btn btn-sm btn-light-success mt-3">
                                        توزيع لقاحات
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Delete Modal --}}
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">تأكيد الحذف</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p class="text-center mb-0">هل أنت متأكد من حذف الوحدة الصحية <strong id="centerName"></strong>؟</p>
                    <p class="text-danger text-center mt-3 mb-0">لا يمكن التراجع عن هذا الإجراء!</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <form id="deleteForm" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="ki-duotone ki-trash fs-2"></i>
                            حذف
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
function confirmDelete(id, name) {
    const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
    document.getElementById('centerName').textContent = name;
    document.getElementById('deleteForm').action = `/admin/health-centers/${id}`;
    deleteModal.show();
}
</script>
@endsection
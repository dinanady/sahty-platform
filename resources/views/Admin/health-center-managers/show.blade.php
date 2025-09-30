@extends('layouts.master')
@section('title')
    ملف مدير الوحدة الصحية
@endsection
@section('page-header')
    <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
        <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">
            ملف المدير: {{ $healthCenterManager->first_name }} {{ $healthCenterManager->last_name }}
        </h1>
        <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
            <li class="breadcrumb-item text-muted">
                <a href="{{route('admin.dashboard')}}" class="text-muted text-hover-primary">الصفحة الرئيسية</a>
            </li>
            <li class="breadcrumb-item">
                <span class="bullet bg-gray-200 w-5px h-2px"></span>
            </li>
            <li class="breadcrumb-item text-muted">
                <a href="{{route('admin.health-center-managers.index')}}" class="text-muted text-hover-primary">مديري الوحدات الصحية</a>
            </li>
            <li class="breadcrumb-item">
                <span class="bullet bg-gray-200 w-5px h-2px"></span>
            </li>
            <li class="breadcrumb-item text-dark">عرض الملف</li>
        </ul>
    </div>
@endsection

@section('content')
    <div class="post d-flex flex-column-fluid" id="kt_post">
        <div id="kt_content_container" class="container-xxl">
            
            {{-- Header Actions --}}
            <div class="d-flex justify-content-end mb-6">
                <a href="{{route('admin.health-center-managers.edit', $healthCenterManager->id)}}" class="btn btn-primary me-3">
                    <i class="ki-duotone ki-pencil fs-2"></i>
                    تعديل
                </a>
                <button type="button" class="btn btn-light-danger me-3" onclick="toggleStatus({{ $healthCenterManager->id }}, {{ $healthCenterManager->is_active ? 'false' : 'true' }})">
                    <i class="ki-duotone ki-toggle-{{ $healthCenterManager->is_active ? 'off' : 'on' }} fs-2"></i>
                    {{ $healthCenterManager->is_active ? 'تعطيل الحساب' : 'تفعيل الحساب' }}
                </button>
                <a href="{{route('admin.health-center-managers.index')}}" class="btn btn-secondary">
                    <i class="ki-duotone ki-arrow-left fs-2"></i>
                    العودة للقائمة
                </a>
            </div>

            <div class="row g-6">
                {{-- Profile Card --}}
                <div class="col-xl-4">
                    <div class="card mb-6">
                        <div class="card-body text-center pt-10">
                            <div class="symbol symbol-150px symbol-circle mb-5">
                                <span class="symbol-label bg-light-primary text-primary fs-1 fw-bold">
                                    {{ substr($healthCenterManager->first_name, 0, 1) }}{{ substr($healthCenterManager->last_name, 0, 1) }}
                                </span>
                            </div>
                            
                            <div class="mb-5">
                                <span class="fs-3 fw-bolder text-gray-800 d-block">
                                    {{ $healthCenterManager->first_name }} {{ $healthCenterManager->last_name }}
                                </span>
                                <span class="fs-6 text-muted">مدير وحدة صحية</span>
                            </div>

                            <div class="mb-5">
                                @if($healthCenterManager->is_active)
                                    <span class="badge badge-light-success fs-7 fw-bold">نشط</span>
                                @else
                                    <span class="badge badge-light-danger fs-7 fw-bold">معطل</span>
                                @endif
                            </div>

                            <div class="d-flex justify-content-center mb-5">
                                <div class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-3">
                                    <div class="fs-6 text-gray-800 fw-bolder">
                                        {{ $healthCenterManager->created_at->format('Y-m-d') }}
                                    </div>
                                    <div class="fw-bold text-gray-400">تاريخ التعيين</div>
                                </div>
                            </div>

                            <div class="d-grid gap-2">
                                <a href="{{route('admin.health-center-managers.edit', $healthCenterManager->id)}}" class="btn btn-sm btn-primary">
                                    تعديل البيانات
                                </a>
                                <button type="button" class="btn btn-sm btn-light-danger" onclick="deleteManager({{ $healthCenterManager->id }})">
                                    حذف المدير
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- Contact Info --}}
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">
                                <h3>معلومات الاتصال</h3>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-5">
                                <div class="symbol symbol-40px me-3">
                                    <span class="symbol-label bg-light-primary">
                                        <i class="ki-duotone ki-phone fs-2x text-primary"></i>
                                    </span>
                                </div>
                                <div class="flex-grow-1">
                                    <span class="text-muted fw-bold d-block">رقم الهاتف</span>
                                    <span class="text-gray-800 fw-bold">{{ $healthCenterManager->phone }}</span>
                                </div>
                            </div>

                            @if($healthCenterManager->email)
                            <div class="d-flex align-items-center mb-5">
                                <div class="symbol symbol-40px me-3">
                                    <span class="symbol-label bg-light-success">
                                        <i class="ki-duotone ki-sms fs-2x text-success"></i>
                                    </span>
                                </div>
                                <div class="flex-grow-1">
                                    <span class="text-muted fw-bold d-block">البريد الإلكتروني</span>
                                    <span class="text-gray-800 fw-bold">{{ $healthCenterManager->email }}</span>
                                </div>
                            </div>
                            @endif

                            <div class="d-flex align-items-center">
                                <div class="symbol symbol-40px me-3">
                                    <span class="symbol-label bg-light-info">
                                        <i class="ki-duotone ki-profile-user fs-2x text-info"></i>
                                    </span>
                                </div>
                                <div class="flex-grow-1">
                                    <span class="text-muted fw-bold d-block">الرقم القومي</span>
                                    <span class="text-gray-800 fw-bold">{{ $healthCenterManager->national_id }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Main Content --}}
                <div class="col-xl-8">
                    {{-- Health Center Info --}}
                    <div class="card mb-6">
                        <div class="card-header">
                            <div class="card-title">
                                <h3>الوحدة الصحية</h3>
                            </div>
                        </div>
                        <div class="card-body">
                            @if($healthCenterManager->healthCenter)
                                <div class="d-flex align-items-center mb-5">
                                    <div class="symbol symbol-50px me-5">
                                        <span class="symbol-label bg-light-primary">
                                            <i class="ki-duotone ki-hospital fs-2x text-primary">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                            </i>
                                        </span>
                                    </div>
                                    <div class="flex-grow-1">
                                        <a href="{{ route('admin.health-centers.show', $healthCenterManager->healthCenter->id) }}" class="text-gray-800 text-hover-primary fw-bolder fs-5">
                                            {{ $healthCenterManager->healthCenter->name }}
                                        </a>
                                        <span class="text-muted fw-bold d-block mt-1">
                                            <i class="ki-duotone ki-geolocation fs-6 me-1"></i>
                                            {{ $healthCenterManager->healthCenter->city->name }}, {{ $healthCenterManager->healthCenter->city->governorate->name }}
                                        </span>
                                    </div>
                                    <span class="badge badge-light-success">مسؤول</span>
                                </div>

                                <div class="separator separator-dashed my-5"></div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="d-flex align-items-center mb-3">
                                            <i class="ki-duotone ki-phone fs-3 text-primary me-2"></i>
                                            <div>
                                                <span class="text-muted fw-bold d-block">هاتف الوحدة</span>
                                                <span class="text-gray-800">{{ $healthCenterManager->healthCenter->phone }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="d-flex align-items-center mb-3">
                                            <i class="ki-duotone ki-document fs-3 text-info me-2"></i>
                                            <div>
                                                <span class="text-muted fw-bold d-block">رقم التسجيل</span>
                                                <span class="text-gray-800">{{ $healthCenterManager->healthCenter->registration_number }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-5">
                                    <a href="{{ route('admin.health-centers.show', $healthCenterManager->healthCenter->id) }}" class="btn btn-sm btn-light-primary">
                                        <i class="ki-duotone ki-eye fs-4"></i>
                                        عرض تفاصيل الوحدة الصحية
                                    </a>
                                </div>
                            @else
                                <div class="text-center py-10">
                                    <i class="ki-duotone ki-information-5 fs-5x text-muted mb-5">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                        <span class="path3"></span>
                                    </i>
                                    <p class="text-muted fw-bold fs-5">لم يتم تعيين وحدة صحية لهذا المدير بعد</p>
                                    <a href="{{ route('admin.health-center-managers.edit', $healthCenterManager->id) }}" class="btn btn-sm btn-primary mt-3">
                                        تعيين وحدة صحية
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Account Details --}}
                    <div class="card mb-6">
                        <div class="card-header">
                            <div class="card-title">
                                <h3>تفاصيل الحساب</h3>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-row-bordered table-row-gray-100 align-middle gs-0 gy-3">
                                    <tbody>
                                        <tr>
                                            <td class="fw-bold text-muted">حالة الحساب</td>
                                            <td class="text-end">
                                                @if($healthCenterManager->is_active)
                                                    <span class="badge badge-light-success">نشط</span>
                                                @else
                                                    <span class="badge badge-light-danger">معطل</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold text-muted">الدور</td>
                                            <td class="text-end">
                                                <span class="badge badge-light-primary">{{ $healthCenterManager->role }}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold text-muted">تاريخ الإنشاء</td>
                                            <td class="text-end text-gray-800">{{ $healthCenterManager->created_at->format('Y-m-d H:i') }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold text-muted">آخر تحديث</td>
                                            <td class="text-end text-gray-800">{{ $healthCenterManager->updated_at->format('Y-m-d H:i') }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    {{-- Activity Timeline (Optional) --}}
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">
                                <h3>السجل الزمني</h3>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="timeline-label">
                                <div class="timeline-item">
                                    <div class="timeline-label fw-bold text-gray-800 fs-6">
                                        {{ $healthCenterManager->created_at->format('H:i') }}
                                    </div>
                                    <div class="timeline-badge">
                                        <i class="fa fa-genderless text-success fs-1"></i>
                                    </div>
                                    <div class="timeline-content fw-bold text-gray-800">
                                        تم إنشاء الحساب
                                        <span class="text-muted">{{ $healthCenterManager->created_at->diffForHumans() }}</span>
                                    </div>
                                </div>

                                @if($healthCenterManager->updated_at != $healthCenterManager->created_at)
                                <div class="timeline-item">
                                    <div class="timeline-label fw-bold text-gray-800 fs-6">
                                        {{ $healthCenterManager->updated_at->format('H:i') }}
                                    </div>
                                    <div class="timeline-badge">
                                        <i class="fa fa-genderless text-primary fs-1"></i>
                                    </div>
                                    <div class="timeline-content fw-bold text-gray-800">
                                        تم تحديث البيانات
                                        <span class="text-muted">{{ $healthCenterManager->updated_at->diffForHumans() }}</span>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Delete Form --}}
    <form id="delete-form" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>

    {{-- Toggle Status Form --}}
    <form id="toggle-status-form" method="POST" style="display: none;">
        @csrf
    </form>
@endsection

@section('scripts')
<script>
function deleteManager(id) {
    Swal.fire({
        title: 'هل أنت متأكد؟',
        text: "سيتم حذف هذا المدير نهائياً!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'نعم، احذف!',
        cancelButtonText: 'إلغاء',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.getElementById('delete-form');
            form.action = `/admin/health-center-managers/${id}`;
            form.submit();
        }
    });
}

function toggleStatus(id, newStatus) {
    const statusText = newStatus === 'true' ? 'تفعيل' : 'تعطيل';
    
    Swal.fire({
        title: `هل تريد ${statusText} هذا الحساب؟`,
        text: newStatus === 'false' ? "لن يتمكن المدير من تسجيل الدخول" : "سيتمكن المدير من تسجيل الدخول",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: `نعم، ${statusText}!`,
        cancelButtonText: 'إلغاء',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.getElementById('toggle-status-form');
            form.action = `/admin/health-center-managers/${id}/toggle-status`;
            form.submit();
        }
    });
}
</script>
@endsection
@extends('layouts.master')
@section('title')
    إضافة وحدة صحية جديدة
@endsection
@section('page-header')
    <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
        <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">
            إضافة وحدة صحية جديدة
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
            <li class="breadcrumb-item text-dark">إضافة وحدة جديدة</li>
        </ul>
    </div>
@endsection
@section('content')
    <div class="post d-flex flex-column-fluid" id="kt_post">
        <div id="kt_content_container" class="container-xxl">
            <div class="card">
                <div class="card-header">
                    <div class="card-title fs-3 fw-bold">بيانات الوحدة الصحية الجديدة</div>
                </div>
                <div class="card-body pt-6">

                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form action="{{route('admin.health-centers.store')}}" method="post" id="create_health_center_form">
                        @csrf
                        
                        <!--begin::Basic Information-->
                        <div class="card mb-6">
                            <div class="card-header">
                                <div class="card-title">
                                    <h3>المعلومات الأساسية</h3>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row mb-6">
                                    <div class="col-md-6 fv-row">
                                        <label class="required fs-5 fw-bold mb-2">اسم الوحدة الصحية</label>
                                        <input type="text" name="name" class="form-control form-control-solid @error('name') is-invalid @enderror" placeholder="ادخل اسم الوحدة الصحية" value="{{old('name')}}" required/>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 fv-row">
                                        <label class="required fs-5 fw-bold mb-2">رقم التسجيل</label>
                                        <input type="text" name="registration_number" class="form-control form-control-solid @error('registration_number') is-invalid @enderror" placeholder="رقم التسجيل الرسمي" value="{{old('registration_number')}}" required/>
                                        @error('registration_number')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="row mb-6">
                                    <div class="col-md-12 fv-row">
                                        <label class="required fs-5 fw-bold mb-2">العنوان</label>
                                        <textarea name="address" class="form-control form-control-solid @error('address') is-invalid @enderror" rows="3" placeholder="العنوان التفصيلي للوحدة الصحية" required>{{old('address')}}</textarea>
                                        @error('address')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row mb-6">
                                    <div class="col-md-6 fv-row">
                                        <label class="required fs-5 fw-bold mb-2">رقم الهاتف</label>
                                        <input type="text" name="phone" class="form-control form-control-solid @error('phone') is-invalid @enderror" placeholder="رقم هاتف الوحدة الصحية" value="{{old('phone')}}" required/>
                                        @error('phone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 fv-row">
                                        <label class="fs-5 fw-bold mb-2">ملاحظات</label>
                                        <input type="text" name="notes" class="form-control form-control-solid" placeholder="ملاحظات إضافية (اختياري)" value="{{old('notes')}}"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--end::Basic Information-->

                        <!--begin::Location Information-->
                        <div class="card mb-6">
                            <div class="card-header">
                                <div class="card-title">
                                    <h3>معلومات الموقع</h3>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row mb-6">
                                    <div class="col-md-6 fv-row">
                                        <label class="required fs-5 fw-bold mb-2">المحافظة</label>
                                        <select name="governorate_id" id="governorate_select" class="form-select form-select-solid @error('governorate_id') is-invalid @enderror" data-control="select2" data-placeholder="اختر المحافظة" required>
                                            <option value="">اختر المحافظة</option>
                                            @foreach($governorates as $governorate)
                                                <option value="{{$governorate->id}}" {{ old('governorate_id') == $governorate->id ? 'selected' : '' }}>
                                                    {{$governorate->name}}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('governorate_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 fv-row">
                                        <label class="required fs-5 fw-bold mb-2">المدينة</label>
                                        <select name="city_id" id="city_select" class="form-select form-select-solid @error('city_id') is-invalid @enderror" data-control="select2" data-placeholder="اختر المدينة" required>
                                            <option value="">اختر المحافظة أولاً</option>
                                        </select>
                                        @error('city_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row mb-6">
                                    <div class="col-md-6 fv-row">
                                        <label class="fs-5 fw-bold mb-2">خط العرض (اختياري)</label>
                                        <input type="number" name="latitude" class="form-control form-control-solid @error('latitude') is-invalid @enderror" placeholder="مثال: 30.0444" value="{{old('latitude')}}" step="any" min="-90" max="90"/>
                                        @error('latitude')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 fv-row">
                                        <label class="fs-5 fw-bold mb-2">خط الطول (اختياري)</label>
                                        <input type="number" name="longitude" class="form-control form-control-solid @error('longitude') is-invalid @enderror" placeholder="مثال: 31.2357" value="{{old('longitude')}}" step="any" min="-180" max="180"/>
                                        @error('longitude')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--end::Location Information-->

                        <!--begin::Working Hours-->
                        <div class="card mb-6">
                            <div class="card-header">
                                <div class="card-title">
                                    <h3>مواعيد العمل</h3>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row mb-6">
                                    @php
                                        $days = [
                                            'sunday' => 'الأحد',
                                            'monday' => 'الاثنين',
                                            'tuesday' => 'الثلاثاء',
                                            'wednesday' => 'الأربعاء',
                                            'thursday' => 'الخميس',
                                            'friday' => 'الجمعة',
                                            'saturday' => 'السبت'
                                        ];
                                    @endphp
                                    @foreach($days as $dayKey => $dayName)
                                    <div class="col-md-12 mb-4">
                                        <div class="d-flex align-items-center mb-3">
                                            <div class="form-check me-4">
                                                <input class="form-check-input" type="checkbox" name="working_days[]" value="{{ $dayKey }}" id="day_{{ $dayKey }}">
                                                <label class="form-check-label fw-bold" for="day_{{ $dayKey }}">
                                                    {{ $dayName }}
                                                </label>
                                            </div>
                                            <div class="d-flex align-items-center">
                                                <label class="me-2">من:</label>
                                                <input type="time" name="working_hours[{{ $dayKey }}][start_time]" class="form-control form-control-sm me-3" style="width: 120px;" disabled>
                                                <label class="me-2">إلى:</label>
                                                <input type="time" name="working_hours[{{ $dayKey }}][end_time]" class="form-control form-control-sm" style="width: 120px;" disabled>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <!--end::Working Hours-->

                        <!--begin::Status-->
                        <div class="card mb-6">
                            <div class="card-header">
                                <div class="card-title">
                                    <h3>الحالة</h3>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row mb-6">
                                    <div class="col-md-12 fv-row">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                                            <label class="form-check-label fw-bold" for="is_active">
                                                الوحدة الصحية نشطة
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--end::Status-->

                        <div class="row">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">
                                    <span class="indicator-label">
                                        <i class="ki-duotone ki-check fs-2"></i>
                                        إنشاء الوحدة الصحية
                                    </span>
                                    <span class="indicator-progress">
                                        يرجى الانتظار...
                                        <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                    </span>
                                </button>
                                <a href="{{route('admin.health-centers.index')}}" class="btn btn-secondary ms-3">
                                    <i class="ki-duotone ki-arrow-left fs-2"></i>
                                    العودة للقائمة
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
// Form validation
document.getElementById('create_health_center_form').addEventListener('submit', function() {
    const submitButton = this.querySelector('button[type="submit"]');
    const indicator = submitButton.querySelector('.indicator-label');
    const progress = submitButton.querySelector('.indicator-progress');
    
    indicator.style.display = 'none';
    progress.style.display = 'inline-block';
    submitButton.disabled = true;
});

// Handle governorate change with AJAX
$('#governorate_select').on('change', function() {
    const governorateId = $(this).val();
    const citySelect = $('#city_select');
    
    // Clear and disable city select
    citySelect.empty().append('<option value="">جاري التحميل...</option>');
    citySelect.prop('disabled', true);
    
    if (governorateId) {
        fetch(`/admin/cities-by-governorate?governorate_id=${governorateId}`)
            .then(response => response.json())
            .then(cities => {
                citySelect.empty().append('<option value="">اختر المدينة</option>');
                
                if (cities.length > 0) {
                    cities.forEach(city => {
                        citySelect.append(`<option value="${city.id}">${city.name}</option>`);
                    });
                } else {
                    citySelect.append('<option value="">لا توجد مدن في هذه المحافظة</option>');
                }
                
                citySelect.prop('disabled', false);
                
                // Restore old value if exists
                const oldCityId = '{{ old("city_id") }}';
                if (oldCityId) {
                    citySelect.val(oldCityId);
                }
                
                // Trigger Select2 update
                citySelect.trigger('change');
            })
            .catch(error => {
                console.error('Error:', error);
                citySelect.empty().append('<option value="">حدث خطأ في التحميل</option>');
                citySelect.prop('disabled', false);
            });
    } else {
        citySelect.empty().append('<option value="">اختر المحافظة أولاً</option>');
        citySelect.prop('disabled', false);
        citySelect.trigger('change');
    }
});

// Handle working days checkboxes
document.querySelectorAll('input[name="working_days[]"]').forEach(checkbox => {
    checkbox.addEventListener('change', function() {
        const dayKey = this.value;
        const startTimeInput = document.querySelector(`input[name="working_hours[${dayKey}][start_time]"]`);
        const endTimeInput = document.querySelector(`input[name="working_hours[${dayKey}][end_time]"]`);
        
        if (this.checked) {
            startTimeInput.disabled = false;
            endTimeInput.disabled = false;
            startTimeInput.required = true;
            endTimeInput.required = true;
            if (!startTimeInput.value) startTimeInput.value = '09:00';
            if (!endTimeInput.value) endTimeInput.value = '17:00';
        } else {
            startTimeInput.disabled = true;
            endTimeInput.disabled = true;
            startTimeInput.required = false;
            endTimeInput.required = false;
            startTimeInput.value = '';
            endTimeInput.value = '';
        }
    });
});

// Phone number validation
document.querySelector('input[name="phone"]').addEventListener('input', function(e) {
    this.value = this.value.replace(/[^0-9\s\+\-\(\)]/g, '');
});

// Load old values if validation fails
$(document).ready(function() {
    const oldGovernorateId = '{{ old("governorate_id") }}';
    
    if (oldGovernorateId) {
        $('#governorate_select').val(oldGovernorateId).trigger('change');
    }
});
</script>
@endsection
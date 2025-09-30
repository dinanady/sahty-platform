@extends('layouts.master')
@section('title')
    إضافة مدير وحدة صحية
@endsection
@section('page-header')
    <!--begin::Page title-->
    <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
        <!--begin::Title-->
        <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">
            إضافة مدير وحدة صحية جديد
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
            <li class="breadcrumb-item text-dark">إضافة مدير جديد</li>
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
            <!--begin::Card-->
            <div class="card">
                <!--begin::Card header-->
                <div class="card-header">
                    <div class="card-title fs-3 fw-bold">بيانات المدير الجديد</div>
                </div>
                <!--end::Card header-->
                <!--begin::Card body-->
                <div class="card-body pt-6">

                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form action="{{route('admin.health-center-managers.store')}}" method="post" id="create_manager_form">
                        @csrf
                        
                        <!--begin::Personal Information-->
                        <div class="card mb-6">
                            <div class="card-header">
                                <div class="card-title">
                                    <h3>البيانات الشخصية</h3>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row mb-6">
                                    <div class="col-md-6 fv-row">
                                        <label class="required fs-5 fw-bold mb-2">الاسم الأول</label>
                                        <input type="text" name="first_name" class="form-control form-control-solid @error('first_name') is-invalid @enderror" placeholder="ادخل الاسم الأول" value="{{old('first_name')}}" required/>
                                        @error('first_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 fv-row">
                                        <label class="required fs-5 fw-bold mb-2">الاسم الأخير</label>
                                        <input type="text" name="last_name" class="form-control form-control-solid @error('last_name') is-invalid @enderror" placeholder="ادخل الاسم الأخير" value="{{old('last_name')}}" required/>
                                        @error('last_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="row mb-6">
                                    <div class="col-md-6 fv-row">
                                        <label class="required fs-5 fw-bold mb-2">رقم الهاتف</label>
                                        <input type="text" name="phone" class="form-control form-control-solid @error('phone') is-invalid @enderror" placeholder="01xxxxxxxxx" value="{{old('phone')}}" required/>
                                        @error('phone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 fv-row">
                                        <label class="required fs-5 fw-bold mb-2">الرقم القومي</label>
                                        <input type="text" name="national_id" class="form-control form-control-solid @error('national_id') is-invalid @enderror" placeholder="14 رقم" value="{{old('national_id')}}" maxlength="14" required/>
                                        @error('national_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row mb-6">
                                    <div class="col-md-12 fv-row">
                                        <label class="fs-5 fw-bold mb-2">البريد الإلكتروني (اختياري)</label>
                                        <input type="email" name="email" class="form-control form-control-solid @error('email') is-invalid @enderror" placeholder="example@domain.com" value="{{old('email')}}"/>
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--end::Personal Information-->

                        <!--begin::Work Information-->
                        <div class="card mb-6">
                            <div class="card-header">
                                <div class="card-title">
                                    <h3>بيانات العمل</h3>
                                </div>
                            </div>
                            <div class="card-body">
                               <div class="col-md-6 fv-row">
    <label class="required fs-5 fw-bold mb-2">الوحدة الصحية</label>
    <select name="health_center_id" class="form-select form-select-solid @error('health_center_id') is-invalid @enderror" data-control="select2" data-placeholder="اختر الوحدة الصحية" required>
        <option value="">اختر الوحدة الصحية</option>
        @forelse($healthCenters as $healthCenter)
            <option value="{{$healthCenter->id}}" {{ old('health_center_id') == $healthCenter->id ? 'selected' : '' }}>
                {{$healthCenter->name}} - {{$healthCenter->city->name}}
            </option>
        @empty
            <option value="" disabled>جميع الوحدات الصحية لديها مديرين</option>
        @endforelse
    </select>
    @error('health_center_id')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
    @if($healthCenters->isEmpty())
        <div class="form-text text-warning">
            جميع الوحدات الصحية لديها مديرين. 
            <a href="{{ route('admin.health-centers.create') }}" target="_blank">أضف وحدة صحية جديدة</a>
        </div>
    @endif
</div>
                            </div>
                        </div>
                        <!--end::Work Information-->

                        <!--begin::Security Information-->
                        <div class="card mb-6">
                            <div class="card-header">
                                <div class="card-title">
                                    <h3>بيانات الأمان</h3>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row mb-6">
                                    <div class="col-md-6 fv-row">
                                        <label class="required fs-5 fw-bold mb-2">كلمة المرور</label>
                                        <input type="password" name="password" class="form-control form-control-solid @error('password') is-invalid @enderror" placeholder="ادخل كلمة المرور" required/>
                                        @error('password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <div class="form-text">يجب أن تكون كلمة المرور 6 أحرف على الأقل</div>
                                    </div>
                                    <div class="col-md-6 fv-row">
                                        <label class="required fs-5 fw-bold mb-2">تأكيد كلمة المرور</label>
                                        <input type="password" name="password_confirmation" class="form-control form-control-solid @error('password_confirmation') is-invalid @enderror" placeholder="أعد إدخال كلمة المرور" required/>
                                        @error('password_confirmation')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--end::Security Information-->

                        <div class="row">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">
                                    <span class="indicator-label">
                                        <i class="ki-duotone ki-check fs-2"></i>
                                        إنشاء المدير
                                    </span>
                                    <span class="indicator-progress">
                                        يرجى الانتظار...
                                        <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                    </span>
                                </button>
                                <a href="{{route('admin.health-center-managers.index')}}" class="btn btn-secondary ms-3">
                                    <i class="ki-duotone ki-arrow-left fs-2"></i>
                                    العودة للقائمة
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
                <!--end::Card body-->
            </div>
            <!--end::Card-->
        </div>
        <!--end::Container-->
    </div>
    <!--end::Post-->
@endsection

@section('scripts')
<script>
// Form validation
document.getElementById('create_manager_form').addEventListener('submit', function() {
    const submitButton = this.querySelector('button[type="submit"]');
    const indicator = submitButton.querySelector('.indicator-label');
    const progress = submitButton.querySelector('.indicator-progress');
    
    // Hide label and show progress
    indicator.style.display = 'none';
    progress.style.display = 'inline-block';
    
    // Disable button
    submitButton.disabled = true;
});

// National ID validation
document.querySelector('input[name="national_id"]').addEventListener('input', function(e) {
    // Only allow numbers
    this.value = this.value.replace(/[^0-9]/g, '');
    
    // Limit to 14 characters
    if (this.value.length > 14) {
        this.value = this.value.slice(0, 14);
    }
});

// Phone validation
document.querySelector('input[name="phone"]').addEventListener('input', function(e) {
    // Only allow numbers
    this.value = this.value.replace(/[^0-9]/g, '');
    
    // Egyptian phone number format
    if (this.value.length > 11) {
        this.value = this.value.slice(0, 11);
    }
});
</script>
@endsection
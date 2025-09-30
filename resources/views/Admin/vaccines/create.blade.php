@extends('layouts.master')
@section('title')
    إضافة لقاح جديد
@endsection

@section('page-header')
    <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
        <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">
            إضافة لقاح جديد للأطفال
        </h1>
        <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
            <li class="breadcrumb-item text-muted">
                <a href="{{route('admin.dashboard')}}" class="text-muted text-hover-primary">الصفحة الرئيسية</a>
            </li>
            <li class="breadcrumb-item">
                <span class="bullet bg-gray-200 w-5px h-2px"></span>
            </li>
            <li class="breadcrumb-item text-muted">
                <a href="{{route('admin.vaccines.index')}}" class="text-muted text-hover-primary">اللقاحات</a>
            </li>
            <li class="breadcrumb-item">
                <span class="bullet bg-gray-200 w-5px h-2px"></span>
            </li>
            <li class="breadcrumb-item text-dark">إضافة لقاح</li>
        </ul>
    </div>
@endsection

@section('content')
    <div class="post d-flex flex-column-fluid" id="kt_post">
        <div id="kt_content_container" class="container-xxl">
            <div class="card">
                <div class="card-header">
                    <div class="card-title fs-3 fw-bold">بيانات اللقاح الجديد</div>
                </div>
                <div class="card-body pt-6">
                    <form action="{{route('admin.vaccines.store')}}" method="post" id="create_vaccine_form">
                        @csrf
                        
                        {{-- Basic Information --}}
                        <div class="card mb-6">
                            <div class="card-header">
                                <div class="card-title">
                                    <h3>المعلومات الأساسية</h3>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row mb-6">
                                    <div class="col-md-12 fv-row">
                                        <label class="required fs-5 fw-bold mb-2">اسم اللقاح</label>
                                        <input type="text" name="name" class="form-control form-control-solid @error('name') is-invalid @enderror" placeholder="مثال: لقاح شلل الأطفال" value="{{old('name')}}" required/>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="row mb-6">
                                    <div class="col-md-12 fv-row">
                                        <label class="required fs-5 fw-bold mb-2">الوصف</label>
                                        <textarea name="description" class="form-control form-control-solid @error('description') is-invalid @enderror" rows="4" placeholder="وصف اللقاح والأمراض التي يقي منها" required>{{old('description')}}</textarea>
                                        @error('description')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Age and Doses --}}
                        <div class="card mb-6">
                            <div class="card-header">
                                <div class="card-title">
                                    <h3>العمر المناسب والجرعات</h3>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="alert alert-info">
                                    <i class="ki-duotone ki-information-5 fs-2 text-info me-2"></i>
                                    حدد العمر المناسب لتطعيم الطفل بالأشهر
                                </div>

                                <div class="row mb-6">
                                    <div class="col-md-6 fv-row">
                                        <label class="required fs-5 fw-bold mb-2">العمر الأدنى (بالأشهر)</label>
                                        <input type="number" name="age_months_min" class="form-control form-control-solid @error('age_months_min') is-invalid @enderror" placeholder="0" value="{{old('age_months_min')}}" min="0" required/>
                                        @error('age_months_min')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <div class="form-text">مثال: 0 للمولود حديثاً</div>
                                    </div>
                                    <div class="col-md-6 fv-row">
                                        <label class="required fs-5 fw-bold mb-2">العمر الأقصى (بالأشهر)</label>
                                        <input type="number" name="age_months_max" class="form-control form-control-solid @error('age_months_max') is-invalid @enderror" placeholder="12" value="{{old('age_months_max')}}" min="0" required/>
                                        @error('age_months_max')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <div class="form-text">مثال: 12 لعام واحد</div>
                                    </div>
                                </div>

                                <div class="row mb-6">
                                    <div class="col-md-6 fv-row">
                                        <label class="required fs-5 fw-bold mb-2">عدد الجرعات المطلوبة</label>
                                        <input type="number" name="doses_required" class="form-control form-control-solid @error('doses_required') is-invalid @enderror" placeholder="1" value="{{old('doses_required', 1)}}" min="1" required/>
                                        @error('doses_required')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 fv-row">
                                        <label class="fs-5 fw-bold mb-2">الفترة بين الجرعات (بالأيام)</label>
                                        <input type="number" name="interval_days" class="form-control form-control-solid @error('interval_days') is-invalid @enderror" placeholder="30" value="{{old('interval_days')}}" min="0"/>
                                        @error('interval_days')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <div class="form-text">اتركه فارغاً إذا كانت جرعة واحدة</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Side Effects and Precautions --}}
                        <div class="card mb-6">
                            <div class="card-header">
                                <div class="card-title">
                                    <h3>الأعراض الجانبية والاحتياطات</h3>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row mb-6">
                                    <div class="col-md-12 fv-row">
                                        <label class="fs-5 fw-bold mb-2">الأعراض الجانبية المحتملة</label>
                                        <textarea name="side_effects" class="form-control form-control-solid @error('side_effects') is-invalid @enderror" rows="3" placeholder="مثال: حمى خفيفة، احمرار مكان الحقن">{{old('side_effects')}}</textarea>
                                        @error('side_effects')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row mb-6">
                                    <div class="col-md-12 fv-row">
                                        <label class="fs-5 fw-bold mb-2">الاحتياطات والتحذيرات</label>
                                        <textarea name="precautions" class="form-control form-control-solid @error('precautions') is-invalid @enderror" rows="3" placeholder="مثال: يجب استشارة الطبيب في حالة الحساسية">{{old('precautions')}}</textarea>
                                        @error('precautions')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Status --}}
                        <div class="card mb-6">
                            <div class="card-header">
                                <div class="card-title">
                                    <h3>الحالة</h3>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                                    <label class="form-check-label fw-bold" for="is_active">
                                        اللقاح نشط ومتاح
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">
                                    <i class="ki-duotone ki-check fs-2"></i>
                                    إضافة اللقاح
                                </button>
                                <a href="{{route('admin.vaccines.index')}}" class="btn btn-secondary ms-3">
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
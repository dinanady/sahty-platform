@extends('layouts.master')
@section('title')
    تعديل اللقاح
@endsection

@section('page-header')
    <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
        <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">
            تعديل: {{ $vaccine->name }}
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
            <li class="breadcrumb-item text-dark">تعديل</li>
        </ul>
    </div>
@endsection

@section('content')
    <div class="post d-flex flex-column-fluid" id="kt_post">
        <div id="kt_content_container" class="container-xxl">
            <div class="card">
                <div class="card-header">
                    <div class="card-title fs-3 fw-bold">تعديل بيانات اللقاح</div>
                </div>
                <div class="card-body pt-6">
                    <form action="{{route('admin.vaccines.update', $vaccine->id)}}" method="post">
                        @csrf
                        @method('PUT')
                        
                        <div class="card mb-6">
                            <div class="card-header">
                                <div class="card-title"><h3>المعلومات الأساسية</h3></div>
                            </div>
                            <div class="card-body">
                                <div class="row mb-6">
                                    <div class="col-md-12 fv-row">
                                        <label class="required fs-5 fw-bold mb-2">اسم اللقاح</label>
                                        <input type="text" name="name" class="form-control form-control-solid @error('name') is-invalid @enderror" value="{{old('name', $vaccine->name)}}" required/>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="row mb-6">
                                    <div class="col-md-12 fv-row">
                                        <label class="required fs-5 fw-bold mb-2">الوصف</label>
                                        <textarea name="description" class="form-control form-control-solid @error('description') is-invalid @enderror" rows="4" required>{{old('description', $vaccine->description)}}</textarea>
                                        @error('description')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card mb-6">
                            <div class="card-header">
                                <div class="card-title"><h3>العمر والجرعات</h3></div>
                            </div>
                            <div class="card-body">
                                <div class="row mb-6">
                                    <div class="col-md-6 fv-row">
                                        <label class="required fs-5 fw-bold mb-2">العمر الأدنى (بالأشهر)</label>
                                        <input type="number" name="age_months_min" class="form-control form-control-solid" value="{{old('age_months_min', $vaccine->age_months_min)}}" min="0" required/>
                                    </div>
                                    <div class="col-md-6 fv-row">
                                        <label class="required fs-5 fw-bold mb-2">العمر الأقصى (بالأشهر)</label>
                                        <input type="number" name="age_months_max" class="form-control form-control-solid" value="{{old('age_months_max', $vaccine->age_months_max)}}" min="0" required/>
                                    </div>
                                </div>

                                <div class="row mb-6">
                                    <div class="col-md-6 fv-row">
                                        <label class="required fs-5 fw-bold mb-2">عدد الجرعات</label>
                                        <input type="number" name="doses_required" class="form-control form-control-solid" value="{{old('doses_required', $vaccine->doses_required)}}" min="1" required/>
                                    </div>
                                    <div class="col-md-6 fv-row">
                                        <label class="fs-5 fw-bold mb-2">الفترة بين الجرعات (بالأيام)</label>
                                        <input type="number" name="interval_days" class="form-control form-control-solid" value="{{old('interval_days', $vaccine->interval_days)}}" min="0"/>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card mb-6">
                            <div class="card-header">
                                <div class="card-title"><h3>الأعراض والاحتياطات</h3></div>
                            </div>
                            <div class="card-body">
                                <div class="row mb-6">
                                    <div class="col-md-12 fv-row">
                                        <label class="fs-5 fw-bold mb-2">الأعراض الجانبية</label>
                                        <textarea name="side_effects" class="form-control form-control-solid" rows="3">{{old('side_effects', $vaccine->side_effects)}}</textarea>
                                    </div>
                                </div>

                                <div class="row mb-6">
                                    <div class="col-md-12 fv-row">
                                        <label class="fs-5 fw-bold mb-2">الاحتياطات</label>
                                        <textarea name="precautions" class="form-control form-control-solid" rows="3">{{old('precautions', $vaccine->precautions)}}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card mb-6">
                            <div class="card-header">
                                <div class="card-title"><h3>الحالة</h3></div>
                            </div>
                            <div class="card-body">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="is_active" value="1" {{ old('is_active', $vaccine->is_active) ? 'checked' : '' }}>
                                    <label class="form-check-label fw-bold">اللقاح نشط</label>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">
                                    <i class="ki-duotone ki-check fs-2"></i>
                                    حفظ التعديلات
                                </button>
                                <a href="{{route('admin.vaccines.index')}}" class="btn btn-secondary ms-3">
                                    <i class="ki-duotone ki-arrow-left fs-2"></i>
                                    العودة
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
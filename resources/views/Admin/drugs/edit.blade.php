@extends('layouts.master')
@section('title')
    تعديل الدواء
@endsection

@section('page-header')
    <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
        <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">
            تعديل: {{ $drug->name }}
        </h1>
        <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
            <li class="breadcrumb-item text-muted">
                <a href="{{route('admin.dashboard')}}" class="text-muted text-hover-primary">الصفحة الرئيسية</a>
            </li>
            <li class="breadcrumb-item">
                <span class="bullet bg-gray-200 w-5px h-2px"></span>
            </li>
            <li class="breadcrumb-item text-muted">
                <a href="{{route('admin.drugs.index')}}" class="text-muted text-hover-primary">الأدوية</a>
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
                    <div class="card-title fs-3 fw-bold">تعديل بيانات الدواء</div>
                </div>
                <div class="card-body pt-6">
                    <form action="{{route('admin.drugs.update', $drug->id)}}" method="post">
                        @csrf
                        @method('PUT')
                        
                        <div class="card mb-6">
                            <div class="card-header">
                                <div class="card-title"><h3>المعلومات الأساسية</h3></div>
                            </div>
                            <div class="card-body">
                                <div class="row mb-6">
                                    <div class="col-md-6 fv-row">
                                        <label class="required fs-5 fw-bold mb-2">الاسم التجاري</label>
                                        <input type="text" name="name" class="form-control form-control-solid @error('name') is-invalid @enderror" value="{{old('name', $drug->name)}}" required/>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 fv-row">
                                        <label class="fs-5 fw-bold mb-2">الاسم العلمي</label>
                                        <input type="text" name="scientific_name" class="form-control form-control-solid" value="{{old('scientific_name', $drug->scientific_name)}}"/>
                                    </div>
                                </div>
                                
                                <div class="row mb-6">
                                    <div class="col-md-12 fv-row">
                                        <label class="fs-5 fw-bold mb-2">الوصف</label>
                                        <textarea name="description" class="form-control form-control-solid" rows="3">{{old('description', $drug->description)}}</textarea>
                                    </div>
                                </div>

                                <div class="row mb-6">
                                    <div class="col-md-6 fv-row">
                                        <label class="fs-5 fw-bold mb-2">الشركة المصنعة</label>
                                        <input type="text" name="manufacturer" class="form-control form-control-solid" value="{{old('manufacturer', $drug->manufacturer)}}"/>
                                    </div>
                                    <div class="col-md-6 fv-row">
                                        <label class="fs-5 fw-bold mb-2">السعر (ج.م)</label>
                                        <input type="number" name="price" class="form-control form-control-solid" value="{{old('price', $drug->price)}}" step="0.01" min="0"/>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card mb-6">
                            <div class="card-header">
                                <div class="card-title"><h3>التصنيف</h3></div>
                            </div>
                            <div class="card-body">
                                <div class="row mb-6">
                                    <div class="col-md-6 fv-row">
                                        <label class="fs-5 fw-bold mb-2">التصنيف</label>
                                        <select name="category" class="form-select form-select-solid" data-control="select2">
                                            <option value="">اختر التصنيف</option>
                                            @foreach($categories as $category)
                                                <option value="{{ $category }}" {{ old('category', $drug->category) == $category ? 'selected' : '' }}>{{ $category }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6 fv-row">
                                        <label class="fs-5 fw-bold mb-2">الشكل الدوائي</label>
                                        <select name="dosage_form" class="form-select form-select-solid" data-control="select2">
                                            <option value="">اختر الشكل</option>
                                            @foreach($dosageForms as $form)
                                                <option value="{{ $form }}" {{ old('dosage_form', $drug->dosage_form) == $form ? 'selected' : '' }}>{{ $form }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card mb-6">
                            <div class="card-header">
                                <div class="card-title"><h3>الخيارات</h3></div>
                            </div>
                            <div class="card-body">
                                <div class="row mb-6">
                                    <div class="col-md-6 fv-row">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" name="insurance_covered" value="1" {{ old('insurance_covered', $drug->insurance_covered) ? 'checked' : '' }}>
                                            <label class="form-check-label fw-bold">مشمول بالتأمين</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6 fv-row">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" name="is_active" value="1" {{ old('is_active', $drug->is_active) ? 'checked' : '' }}>
                                            <label class="form-check-label fw-bold">نشط</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">
                                    <i class="ki-duotone ki-check fs-2"></i>
                                    حفظ التعديلات
                                </button>
                                <a href="{{route('admin.drugs.index')}}" class="btn btn-secondary ms-3">
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
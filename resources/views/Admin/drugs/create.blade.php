@extends('layouts.master')
@section('title')
    إضافة دواء جديد
@endsection

@section('page-header')
    <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
        <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">
            إضافة دواء جديد
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
            <li class="breadcrumb-item text-dark">إضافة دواء</li>
        </ul>
    </div>
@endsection

@section('content')
    <div class="post d-flex flex-column-fluid" id="kt_post">
        <div id="kt_content_container" class="container-xxl">
            <div class="card">
                <div class="card-header">
                    <div class="card-title fs-3 fw-bold">بيانات الدواء الجديد</div>
                </div>
                <div class="card-body pt-6">
                    <form action="{{route('admin.drugs.store')}}" method="post" id="create_drug_form">
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
                                    <div class="col-md-6 fv-row">
                                        <label class="required fs-5 fw-bold mb-2">الاسم التجاري</label>
                                        <input type="text" name="name" class="form-control form-control-solid @error('name') is-invalid @enderror" placeholder="ادخل الاسم التجاري" value="{{old('name')}}" required/>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 fv-row">
                                        <label class="fs-5 fw-bold mb-2">الاسم العلمي</label>
                                        <input type="text" name="scientific_name" class="form-control form-control-solid @error('scientific_name') is-invalid @enderror" placeholder="الاسم العلمي (اختياري)" value="{{old('scientific_name')}}"/>
                                        @error('scientific_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="row mb-6">
                                    <div class="col-md-12 fv-row">
                                        <label class="fs-5 fw-bold mb-2">الوصف</label>
                                        <textarea name="description" class="form-control form-control-solid @error('description') is-invalid @enderror" rows="3" placeholder="وصف الدواء واستخداماته">{{old('description')}}</textarea>
                                        @error('description')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row mb-6">
                                    <div class="col-md-6 fv-row">
                                        <label class="fs-5 fw-bold mb-2">الشركة المصنعة</label>
                                        <input type="text" name="manufacturer" class="form-control form-control-solid @error('manufacturer') is-invalid @enderror" placeholder="اسم الشركة المصنعة" value="{{old('manufacturer')}}"/>
                                        @error('manufacturer')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 fv-row">
                                        <label class="fs-5 fw-bold mb-2">السعر (ج.م)</label>
                                        <input type="number" name="price" class="form-control form-control-solid @error('price') is-invalid @enderror" placeholder="0.00" value="{{old('price')}}" step="0.01" min="0"/>
                                        @error('price')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Classification --}}
                        <div class="card mb-6">
                            <div class="card-header">
                                <div class="card-title">
                                    <h3>التصنيف والشكل الدوائي</h3>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row mb-6">
                                    <div class="col-md-6 fv-row">
                                        <label class="fs-5 fw-bold mb-2">التصنيف</label>
                                        <select name="category" class="form-select form-select-solid @error('category') is-invalid @enderror" data-control="select2">
                                            <option value="">اختر التصنيف</option>
                                            @foreach($categories as $category)
                                                <option value="{{ $category }}" {{ old('category') == $category ? 'selected' : '' }}>{{ $category }}</option>
                                            @endforeach
                                        </select>
                                        @error('category')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 fv-row">
                                        <label class="fs-5 fw-bold mb-2">الشكل الدوائي</label>
                                        <select name="dosage_form" class="form-select form-select-solid @error('dosage_form') is-invalid @enderror" data-control="select2">
                                            <option value="">اختر الشكل الدوائي</option>
                                            @foreach($dosageForms as $form)
                                                <option value="{{ $form }}" {{ old('dosage_form') == $form ? 'selected' : '' }}>{{ $form }}</option>
                                            @endforeach
                                        </select>
                                        @error('dosage_form')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Additional Options --}}
                        <div class="card mb-6">
                            <div class="card-header">
                                <div class="card-title">
                                    <h3>خيارات إضافية</h3>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row mb-6">
                                    <div class="col-md-6 fv-row">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" name="insurance_covered" id="insurance_covered" value="1" {{ old('insurance_covered') ? 'checked' : '' }}>
                                            <label class="form-check-label fw-bold" for="insurance_covered">
                                                مشمول بالتأمين الصحي
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-6 fv-row">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                                            <label class="form-check-label fw-bold" for="is_active">
                                                الدواء نشط
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">
                                    <i class="ki-duotone ki-check fs-2"></i>
                                    إضافة الدواء
                                </button>
                                <a href="{{route('admin.drugs.index')}}" class="btn btn-secondary ms-3">
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
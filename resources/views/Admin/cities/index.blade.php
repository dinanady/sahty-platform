
@extends('layouts.master')
@section('title')
    المدن
@endsection
@section('page-header')
    <!--begin::Page title-->
    <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
        <!--begin::Title-->
        <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">
            إدارة المدن
        </h1>
        <!--end::Title-->
        <!--begin::Breadcrumb-->
        <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
            <!--begin::Item-->
            <li class="breadcrumb-item text-muted">
                <a href="{{ route('admin.dashboard') }}" class="text-muted text-hover-primary">الصفحة الرئيسية</a>
            </li>
            <!--end::Item-->
            <!--begin::Item-->
            <li class="breadcrumb-item">
                <span class="bullet bg-gray-200 w-5px h-2px"></span>
            </li>
            <!--end::Item-->
            <!--begin::Item-->
            <li class="breadcrumb-item text-dark">المدن</li>
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
                <div class="card-header border-0 pt-6">
                    <!--begin::Card title-->
                    <div class="card-title">
                        <!--begin::Search-->
                        <div class="d-flex align-items-center position-relative my-1">
                            <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-5">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                            <form method="GET" action="{{ route('admin.cities.index') }}" class="d-flex">
                                <input type="text" 
                                       name="search" 
                                       value="{{ request('search') }}" 
                                       data-kt-customer-table-filter="search" 
                                       class="form-control form-control-solid w-250px ps-13" 
                                       placeholder="البحث في المدن"/>
                                <button type="submit" class="btn btn-light-primary ms-2">بحث</button>
                                @if(request('search'))
                                    <a href="{{ route('admin.cities.index') }}" class="btn btn-light-secondary ms-2">إلغاء</a>
                                @endif
                            </form>
                        </div>
                        <!--end::Search-->
                    </div>
                    <!--end::Card title-->
                    <!--begin::Card toolbar-->
                    <div class="card-toolbar">
                        <!--begin::Toolbar-->
                        <div class="d-flex justify-content-end" data-kt-customer-table-toolbar="base">
                            <!--begin::Add city-->
                            <button type="button" class="btn btn-primary" id="add_city_btn">
                                <i class="ki-duotone ki-plus fs-2"></i>
                                إضافة مدينة جديدة
                            </button>
                            <!--end::Add city-->
                        </div>
                        <!--end::Toolbar-->
                    </div>
                    <!--end::Card toolbar-->
                </div>
                <!--end::Card header-->
                <!--begin::Card body-->
                <div class="card-body pt-0">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <!--begin::Table-->
                    <div class="table-responsive">
                        <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_customers_table">
                            <!--begin::Table head-->
                            <thead>
                                <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                                    <th class="min-w-125px">#</th>
                                    <th class="min-w-200px">اسم المدينة</th>
                                    <th class="min-w-200px">المحافظة</th>
                                    <th class="min-w-150px">عدد الوحدات الصحية</th>
                                    <th class="min-w-150px">تاريخ الإضافة</th>
                                    <th class="text-end min-w-150px">الإجراءات</th>
                                </tr>
                            </thead>
                            <!--end::Table head-->
                            <!--begin::Table body-->
                            <tbody class="fw-semibold text-gray-600">
                                @forelse($cities as $key => $city)
                                <tr>
                                    <!--begin::ID-->
                                    <td>
                                        {{ $cities->firstItem() + $key }}
                                    </td>
                                    <!--end::ID-->
                                    <!--begin::Name-->
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="symbol symbol-50px me-3">
                                                <div class="symbol-label bg-light-primary">
                                                    <i class="ki-duotone ki-geolocation fs-2 text-primary">
                                                        <span class="path1"></span>
                                                        <span class="path2"></span>
                                                    </i>
                                                </div>
                                            </div>
                                            <div class="d-flex flex-column">
                                                <span class="text-gray-800 fw-bold fs-6">{{ $city->name }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <!--end::Name-->
                                    <!--begin::Governorate-->
                                    <td>
                                        <span class="badge badge-light-primary">{{ $city->governorate->name }}</span>
                                    </td>
                                    <!--end::Governorate-->
                                    <!--begin::Health Centers Count-->
                                    <td>
                                        <span class="badge badge-light-info">{{ $city->health_centers_count }} وحدة صحية</span>
                                    </td>
                                    <!--end::Health Centers Count-->
                                    <!--begin::Created Date-->
                                    <td>
                                        {{ $city->created_at->format('Y-m-d') }}
                                    </td>
                                    <!--end::Created Date-->
                                    <!--begin::Action-->
                                    <td class="text-end">
                                        <button type="button" class="btn btn-sm btn-primary me-2" 
                                                data-edit-id="{{ $city->id }}" 
                                                data-edit-name="{{ $city->name }}" 
                                                data-edit-governorate-id="{{ $city->governorate_id }}">
                                            <i class="ki-duotone ki-pencil fs-5"></i>
                                            تعديل
                                        </button>
                                        <button type="button" class="btn btn-sm btn-danger" 
                                                data-delete-id="{{ $city->id }}" 
                                                data-health-centers-count="{{ $city->health_centers_count }}">
                                            <i class="ki-duotone ki-trash fs-5"></i>
                                            حذف
                                        </button>
                                    </td>
                                    <!--end::Action-->
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-10">
                                        <div class="d-flex flex-column align-items-center">
                                            <i class="ki-duotone ki-file-deleted fs-4x text-gray-400 mb-3">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                            </i>
                                            <span class="text-gray-400 fs-6">لا توجد مدن</span>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                            <!--end::Table body-->
                        </table>
                    </div>
                    <!--end::Table-->

                    <!--begin::Pagination-->
                    @if($cities->hasPages())
                    <div class="d-flex justify-content-center">
                        {{ $cities->links() }}
                    </div>
                    @endif
                    <!--end::Pagination-->
                </div>
                <!--end::Card body-->
            </div>
            <!--end::Card-->
        </div>
        <!--end::Container-->
    </div>
    <!--end::Post-->

    <!-- Delete Form -->
    <form id="delete-form" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>

    <!-- Modal for Add/Edit City -->
    <div class="modal fade" id="city_modal" tabindex="-1" aria-labelledby="modal_title" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal_title">إضافة مدينة جديدة</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="city_form">
                        @csrf
                        <input type="hidden" id="city_id" name="city_id">
                        <input type="hidden" id="form_method" name="_method">
                        <div class="mb-3">
                            <label for="governorate_id" class="form-label fw-bold">المحافظة</label>
                            <select class="form-select form-select-solid" id="governorate_id" name="governorate_id">
                                <option value="">اختر المحافظة</option>
                                @foreach($governorates as $governorate)
                                    <option value="{{ $governorate->id }}">{{ $governorate->name }}</option>
                                @endforeach
                            </select>
                            <div id="governorate_error" class="text-danger mt-2"></div>
                        </div>
                        <div class="mb-3">
                            <label for="city_name" class="form-label fw-bold">اسم المدينة</label>
                            <input type="text" class="form-control form-control-solid" id="city_name" name="name" placeholder="ادخل اسم المدينة">
                            <div id="name_error" class="text-danger mt-2"></div>
                        </div>
                        <div class="text-end">
                            <button type="button" class="btn btn-light" data-kt-customers-modal-action="cancel">إلغاء</button>
                            <button type="button" class="btn btn-primary" id="submit_text" data-kt-customers-modal-action="submit">حفظ</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    "use strict";

    // التحقق من وجود Bootstrap
    if (typeof bootstrap === 'undefined') {
        console.error('Bootstrap JavaScript is not loaded. Please include bootstrap.bundle.min.js.');
        return;
    }

    // التحقق من وجود SweetAlert2
    if (typeof Swal === 'undefined') {
        console.error('SweetAlert2 is not loaded. Please include sweetalert2.min.js.');
        return;
    }

    // Modal elements
    const modal = document.querySelector('#city_modal');
    if (!modal) {
        console.error('Modal element (#city_modal) not found in DOM.');
        return;
    }

    const modalTitle = document.querySelector('#modal_title');
    const form = document.querySelector('#city_form');
    const submitButton = document.querySelector('[data-kt-customers-modal-action="submit"]');
    const cancelButton = document.querySelector('[data-kt-customers-modal-action="cancel"]');
    const closeButton = document.querySelector('[data-kt-customers-modal-action="close"]');
    const cityId = document.querySelector('#city_id');
    const cityName = document.querySelector('#city_name');
    const governorateId = document.querySelector('#governorate_id');
    const formMethod = document.querySelector('#form_method');
    const submitText = document.querySelector('#submit_text');
    const nameError = document.querySelector('#name_error');
    const governorateError = document.querySelector('#governorate_error');

    // التحقق من وجود جميع العناصر
    if (!form || !submitButton || !cancelButton || !cityId || !cityName || !governorateId || !formMethod || !submitText || !nameError || !governorateError) {
        console.error('One or more form elements are missing in the modal.');
        return;
    }

    // Add button event listener
    const addButton = document.querySelector('#add_city_btn');
    if (addButton) {
        addButton.addEventListener('click', function() {
            window.openAddModal();
        });
    } else {
        console.warn('Add button (#add_city_btn) not found in DOM.');
    }

    // Edit buttons event listeners
    const editButtons = document.querySelectorAll('[data-edit-id]');
    if (editButtons.length > 0) {
        editButtons.forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-edit-id');
                const name = this.getAttribute('data-edit-name');
                const governorateIdValue = this.getAttribute('data-edit-governorate-id');
                window.openEditModal(id, name, governorateIdValue);
            });
        });
    } else {
        console.warn('No edit buttons ([data-edit-id]) found in DOM.');
    }

    // Delete buttons event listeners
    const deleteButtons = document.querySelectorAll('[data-delete-id]');
    if (deleteButtons.length > 0) {
        deleteButtons.forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-delete-id');
                const healthCentersCount = this.getAttribute('data-health-centers-count');
                window.deleteCity(id, healthCentersCount);
            });
        });
    } else {
        console.warn('No delete buttons ([data-delete-id]) found in DOM.');
    }

    // Open Add Modal
    window.openAddModal = function() {
        modalTitle.textContent = 'إضافة مدينة جديدة';
        submitText.textContent = 'حفظ';
        cityId.value = '';
        cityName.value = '';
        governorateId.value = '';
        formMethod.value = '';
        clearErrors();
        const modalInstance = new bootstrap.Modal(modal);
        modalInstance.show();
    };

    // Open Edit Modal
    window.openEditModal = function(id, name, governorateIdValue) {
        modalTitle.textContent = 'تعديل المدينة';
        submitText.textContent = 'تحديث';
        cityId.value = id;
        cityName.value = name;
        governorateId.value = governorateIdValue;
        formMethod.value = 'PUT';
        clearErrors();
        const modalInstance = new bootstrap.Modal(modal);
        modalInstance.show();
    };

    // Delete function
    window.deleteCity = function(id, healthCentersCount) {
        let message = "سيتم حذف هذه المدينة نهائياً!";
        
        if (healthCentersCount > 0) {
            Swal.fire({
                icon: 'warning',
                title: 'تنبيه!',
                text: `لا يمكن حذف هذه المدينة لأنها تحتوي على ${healthCentersCount} وحدة صحية`,
                confirmButtonText: 'حسناً'
            });
            return;
        }

        Swal.fire({
            title: 'هل أنت متأكد؟',
            text: message,
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
                if (form) {
                    form.action = `/admin/cities/${id}`;
                    form.submit();
                } else {
                    console.error('Delete form (#delete-form) not found in DOM.');
                }
            }
        });
    };

    // Clear errors
    function clearErrors() {
        cityName.classList.remove('is-invalid');
        governorateId.classList.remove('is-invalid');
        nameError.textContent = '';
        governorateError.textContent = '';
    }

    // Show error
    function showError(field, message) {
        if (field === 'name') {
            cityName.classList.add('is-invalid');
            nameError.textContent = message;
        } else if (field === 'governorate_id') {
            governorateId.classList.add('is-invalid');
            governorateError.textContent = message;
        }
    }

    // Submit form
    submitButton.addEventListener('click', function(e) {
        e.preventDefault();
        
        const name = cityName.value.trim();
        const governorate_id = governorateId.value;
        const id = cityId.value;
        
        if (!name) {
            showError('name', 'اسم المدينة مطلوب');
            return;
        }
        if (!governorate_id) {
            showError('governorate_id', 'المحافظة مطلوبة');
            return;
        }
        
        submitButton.setAttribute('data-kt-indicator', 'on');
        submitButton.disabled = true;
        
        const formData = new FormData();
        formData.append('name', name);
        formData.append('governorate_id', governorate_id);
        formData.append('_token', '{{ csrf_token() }}');
        
        let url = '{{ route("admin.cities.store") }}';
        if (id) {
            url = `/admin/cities/${id}`;
            formData.append('_method', 'PUT');
        }
        
        fetch(url, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            if (response.status === 422) {
                return response.json().then(data => {
                    if (data.errors) {
                        if (data.errors.name) {
                            showError('name', data.errors.name[0]);
                        }
                        if (data.errors.governorate_id) {
                            showError('governorate_id', data.errors.governorate_id[0]);
                        }
                    }
                    throw new Error('Validation failed');
                });
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'تم بنجاح!',
                    text: data.message,
                    timer: 2000,
                    showConfirmButton: false
                }).then(() => {
                    location.reload();
                });
            } else if (data.errors) {
                if (data.errors.name) {
                    showError('name', data.errors.name[0]);
                }
                if (data.errors.governorate_id) {
                    showError('governorate_id', data.errors.governorate_id[0]);
                }
            } else if (data.message) {
                Swal.fire({
                    icon: 'error',
                    title: 'خطأ!',
                    text: data.message
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            if (error.message !== 'Validation failed') {
                Swal.fire({
                    icon: 'error',
                    title: 'خطأ!',
                    text: 'حدث خطأ غير متوقع'
                });
            }
        })
        .finally(() => {
            submitButton.removeAttribute('data-kt-indicator');
            submitButton.disabled = false;
        });
    });

    // Cancel button
    cancelButton.addEventListener('click', function(e) {
        e.preventDefault();
        Swal.fire({
            text: "هل أنت متأكد من إلغاء العملية؟",
            icon: "warning",
            showCancelButton: true,
            buttonsStyling: false,
            confirmButtonText: "نعم، إلغاء!",
            cancelButtonText: "لا، العودة",
            customClass: {
                confirmButton: "btn btn-primary",
                cancelButton: "btn btn-active-light"
            }
        }).then(function(result) {
            if (result.value) {
                form.reset();
                const modalInstance = bootstrap.Modal.getInstance(modal);
                modalInstance.hide();
            }
        });
    });

    // Close button
    if (closeButton) {
        closeButton.addEventListener('click', function(e) {
            e.preventDefault();
            cancelButton.click();
        });
    }
});
</script>
@endsection

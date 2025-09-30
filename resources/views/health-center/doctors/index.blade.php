@extends('layouts.health-center.master')

@section('content')
    <div class="container-fluid py-4">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-md-6">
                <h2 class="mb-0 text-primary">
                    <i class="fas fa-user-md me-2"></i>إدارة الأطباء
                </h2>
                <p class="text-muted mb-0">عرض وإدارة معلومات الأطباء</p>
            </div>
            <div class="col-md-6 text-end">
                <button onclick="openCreateModal()" class="btn btn-primary btn-lg shadow-sm">
                    <i class="fas fa-plus-circle me-2"></i>إضافة طبيب جديد
                </button>
            </div>
        </div>

        <!-- الفلاتر -->
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-gradient text-white py-3"
                style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <h6 class="mb-0">
                    <i class="fas fa-filter me-2"></i>البحث والفلترة السريعة
                </h6>
            </div>
            <div class="card-body p-4">
                <div class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label class="form-label fw-semibold text-secondary">
                            <i class="fas fa-search text-primary me-2"></i>البحث بالاسم
                        </label>
                        <input type="text" id="filter_name" name="search_name" class="form-control form-control-lg"
                            placeholder="ابحث عن طبيب..." value="{{ $filters['name'] ?? '' }}">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold text-secondary">
                            <i class="fas fa-stethoscope text-info me-2"></i>التخصص
                        </label>
                        <select id="filter_specialty" name="filter_specialty" class="form-select form-select-lg">
                            <option value="">جميع التخصصات</option>
                            @foreach($specialties as $specialty)
                                <option value="{{ $specialty }}" {{ ($filters['specialty'] ?? '') == $specialty ? 'selected' : '' }}>
                                    {{ $specialty }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label class="form-label fw-semibold text-secondary">
                            <i class="fas fa-toggle-on text-success me-2"></i>الحالة
                        </label>
                        <select id="filter_status" name="status" class="form-select form-select-lg">
                            <option value="">الكل</option>
                            <option value="1" {{ ($filters['status'] ?? '') == '1' ? 'selected' : '' }}>نشط</option>
                            <option value="0" {{ ($filters['status'] ?? '') == '0' ? 'selected' : '' }}>غير نشط</option>
                        </select>
                    </div>

                    <div class="col-md-2">
                        <button type="reset" class="btn btn-outline-secondary btn-lg w-100" id="clearFilters">
                            <i class="fas fa-times me-1"></i>مسح
                        </button>
                    </div>
                </div>

            </div>
        </div>

        <!-- عداد النتائج -->
        <div class="col-md-2">
            <label class="form-label fw-semibold text-secondary">
                <i class="fas fa-list-ol text-warning me-2"></i>عدد النتائج
            </label>
            <select name="per_page" id="per_page" class="form-select form-select-lg">
                @foreach([10, 15, 25, 50, 100] as $size)
                    <option value="{{ $size }}" {{ ($filters['per_page'] ?? 15) == $size ? 'selected' : '' }}>
                        {{ $size }}
                    </option>
                @endforeach
            </select>
        </div>



        <!-- الجدول -->
        <div class="card shadow-sm border-0">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                            <tr class="text-white">
                                <th class="text-center py-3">#</th>
                                <th class="text-end py-3">
                                    <i class="fas fa-user me-2"></i>الاسم
                                </th>
                                <th class="text-end py-3">
                                    <i class="fas fa-stethoscope me-2"></i>التخصص
                                </th>
                                <th class="text-end py-3">
                                    <i class="fas fa-phone me-2"></i>الهاتف
                                </th>
                                <th class="text-center py-3">
                                    <i class="fas fa-calendar-week me-2"></i>أيام العمل
                                </th>
                                <th class="text-center py-3">
                                    <i class="fas fa-toggle-on me-2"></i>الحالة
                                </th>
                                <th class="text-center py-3">
                                    <i class="fas fa-cogs me-2"></i>الإجراءات
                                </th>
                            </tr>
                        </thead>
                        <tbody id="doctors-table-body">
                            @include('health-center.doctors.partials.table-rows', ['doctors' => $doctors])
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Pagination -->
        <div class="mt-4 d-flex justify-content-center" id="pagination-links">
            {{ $doctors->links() }}
        </div>
    </div>


    <!-- Modal إضافة طبيب -->
    <div class="modal fade" id="createModal" tabindex="-1">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header text-white py-3"
                    style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <h5 class="modal-title">
                        <i class="fas fa-user-plus me-2"></i>إضافة طبيب جديد
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form id="createDoctorForm" onsubmit="submitCreate(event)">
                    @csrf
                    <div class="modal-body p-4">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">
                                    <i class="fas fa-user text-primary me-2"></i>الاسم
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="name" class="form-control form-control-lg" required>
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">
                                    <i class="fas fa-stethoscope text-info me-2"></i>التخصص
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="specialty" class="form-control form-control-lg" required>
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label fw-bold">
                                    <i class="fas fa-phone text-success me-2"></i>رقم الهاتف
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="phone" class="form-control form-control-lg" dir="ltr" required>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>

                        <hr class="my-4">

                        <div>
                            <h6 class="fw-bold mb-3">
                                <i class="fas fa-calendar-alt text-warning me-2"></i>جدول العمل الأسبوعي
                            </h6>
                            @php
                                $days = [
                                    'saturday' => 'السبت',
                                    'sunday' => 'الأحد',
                                    'monday' => 'الاثنين',
                                    'tuesday' => 'الثلاثاء',
                                    'wednesday' => 'الأربعاء',
                                    'thursday' => 'الخميس',
                                    'friday' => 'الجمعة'
                                ];
                            @endphp

                            @foreach($days as $day => $dayName)
                                <div class="card mb-2 border">
                                    <div class="card-body py-3">
                                        <div class="row align-items-center">
                                            <div class="col-md-3">
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox"
                                                        name="schedules[{{ $loop->index }}][enabled]" id="create_day_{{ $day }}"
                                                        value="1" onchange="toggleDayInputs('create', '{{ $day }}')">
                                                    <label class="form-check-label fw-bold" for="create_day_{{ $day }}">
                                                        {{ $dayName }}
                                                    </label>
                                                </div>
                                            </div>
                                            <input type="hidden" name="schedules[{{ $loop->index }}][day_of_week]"
                                                value="{{ $day }}">
                                            <div class="col-md-9 create-day-inputs-{{ $day }}" style="display: none;">
                                                <div class="row g-2">
                                                    <div class="col-md-6">
                                                        <div class="input-group">
                                                            <span class="input-group-text bg-light">
                                                                <i class="fas fa-clock text-primary"></i>
                                                            </span>
                                                            <input type="time" name="schedules[{{ $loop->index }}][start_time]"
                                                                class="form-control" dir="ltr" placeholder="من">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="input-group">
                                                            <span class="input-group-text bg-light">
                                                                <i class="fas fa-clock text-danger"></i>
                                                            </span>
                                                            <input type="time" name="schedules[{{ $loop->index }}][end_time]"
                                                                class="form-control" dir="ltr" placeholder="إلى">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-times me-2"></i>إلغاء
                        </button>
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-save me-2"></i>حفظ البيانات
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal عرض التفاصيل -->
    <div class="modal fade" id="showModal" tabindex="-1">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header text-white py-3"
                    style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <h5 class="modal-title">
                        <i class="fas fa-info-circle me-2"></i>تفاصيل الطبيب
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4" id="showModalContent"></div>
            </div>
        </div>
    </div>

    <!-- Modal التعديل -->
    <div class="modal fade" id="editModal" tabindex="-1">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header text-white py-3"
                    style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <h5 class="modal-title">
                        <i class="fas fa-edit me-2"></i>تعديل بيانات الطبيب
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4" id="editModalContent"></div>
            </div>
        </div>
    </div>

    <!-- Modal إضافة اعتذار -->
    <div class="modal fade" id="exceptionModalIndex" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-warning text-dark py-3">
                    <h5 class="modal-title">
                        <i class="fas fa-calendar-times me-2"></i>إضافة اعتذار
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="exceptionFormIndex" onsubmit="submitExceptionFromIndex(event)">
                    @csrf
                    <input type="hidden" id="exception_doctor_id_index" name="doctor_id">
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label fw-bold">
                                <i class="fas fa-calendar text-primary me-2"></i>التاريخ
                                <span class="text-danger">*</span>
                            </label>
                            <input type="date" id="exception_date_picker" name="exception_date" class="form-control"
                                required>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">
                                <i class="fas fa-comment text-info me-2"></i>السبب (اختياري)
                            </label>
                            <textarea name="reason" class="form-control" rows="3"
                                placeholder="اكتب سبب الاعتذار..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                        <button type="submit" class="btn btn-warning">
                            <i class="fas fa-save me-2"></i>حفظ الاعتذار
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        let createModalInstance, showModalInstance, editModalInstance, exceptionModalInstance;
        let filterTimeout;

        document.addEventListener('DOMContentLoaded', function () {
            createModalInstance = new bootstrap.Modal(document.getElementById('createModal'));
            showModalInstance = new bootstrap.Modal(document.getElementById('showModal'));
            editModalInstance = new bootstrap.Modal(document.getElementById('editModal'));
            exceptionModalInstance = new bootstrap.Modal(document.getElementById('exceptionModalIndex'));

            // الفلترة التلقائية
            document.getElementById('filter_name').addEventListener('input', debounceFilter);
            document.getElementById('filter_specialty').addEventListener('change', applyFilters);
            document.getElementById('filter_status').addEventListener('change', applyFilters);
            document.getElementById('per_page').addEventListener('change', applyFilters);
        });

        function debounceFilter() {
            clearTimeout(filterTimeout);
            filterTimeout = setTimeout(applyFilters, 500);
        }

        function applyFilters() {
            const name = document.getElementById('filter_name').value;
            const specialty = document.getElementById('filter_specialty').value;
            const status = document.getElementById('filter_status').value;
            const per_page = document.getElementById('per_page').value;

            const params = new URLSearchParams({
                name: name,
                specialty: specialty,
                status: status,
                per_page: per_page
            });

            fetch(`{{ route('health-center.doctors.index') }}?${params}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
                .then(response => response.json())
                .then(data => {
                    document.getElementById('doctors-table-body').innerHTML = data.html;
                    document.getElementById('pagination-links').innerHTML = data.pagination;
                    document.getElementById('total-count').textContent = data.total;
                });
        }

        function openCreateModal() {
            createModalInstance.show();
        }


        function toggleDayInputs(type, day) {
            const checkbox = document.getElementById(type + '_day_' + day);
            const inputs = document.querySelector('.' + type + '-day-inputs-' + day);

            if (checkbox.checked) {
                inputs.style.display = 'block';
                inputs.querySelectorAll('input[type="time"]').forEach(input => {
                    input.required = true;
                    input.setAttribute('data-required', 'true');
                });
            } else {
                inputs.style.display = 'none';
                inputs.querySelectorAll('input[type="time"]').forEach(input => {
                    input.required = false;
                    input.removeAttribute('data-required');
                    input.value = '';
                });
            }
        }

        function prepareScheduleData() {
            const formData = new FormData();

            // البيانات الأساسية
            formData.append('name', document.querySelector('[name="name"]').value);
            formData.append('specialty', document.querySelector('[name="specialty"]').value);
            formData.append('phone', document.querySelector('[name="phone"]').value);
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);

            // بيانات الجدول
            const scheduleInputs = document.querySelectorAll('[name^="schedules"]');
            let scheduleIndex = 0;

            // إعادة تنظيم بيانات الجدول
            const days = ['saturday', 'sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday'];

            days.forEach((day, index) => {
                const checkbox = document.getElementById(`create_day_${day}`);
                const startTime = document.querySelector(`[name="schedules[${index}][start_time]"]`);
                const endTime = document.querySelector(`[name="schedules[${index}][end_time]"]`);

                formData.append(`schedules[${index}][day_of_week]`, day);

                if (checkbox && checkbox.checked) {
                    formData.append(`schedules[${index}][enabled]`, '1');
                    formData.append(`schedules[${index}][start_time]`, startTime ? startTime.value : '');
                    formData.append(`schedules[${index}][end_time]`, endTime ? endTime.value : '');
                } else {
                    formData.append(`schedules[${index}][enabled]`, '0');
                    formData.append(`schedules[${index}][start_time]`, '');
                    formData.append(`schedules[${index}][end_time]`, '');
                }
            });

            return formData;
        }

        function submitCreate(event) {
            event.preventDefault();
            const form = event.target;

            // مسح الأخطاء السابقة
            form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
            form.querySelectorAll('.invalid-feedback').forEach(el => el.textContent = '');

            const formData = prepareScheduleData();

            fetch('{{ route('health-center.doctors.store') }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            })
                .then(async response => {
                    let data;
                    try {
                        data = await response.json();
                    } catch (e) {
                        throw new Error('Invalid JSON response');
                    }

                    if (!response.ok) {
                        if (response.status === 422 && data.errors) {
                            let allErrors = [];
                            Object.values(data.errors).forEach(errArray => {
                                allErrors = allErrors.concat(errArray);
                            });

                            Swal.fire({
                                icon: 'error',
                                title: 'خطأ في الإدخال!',
                                html: allErrors.join('<br>'),
                                confirmButtonText: 'حسناً'
                            });
                        } else {
                            Swal.fire('خطأ!', data.message || 'حدث خطأ أثناء الحفظ', 'error');
                        }
                        return; // مهم علشان ما يكملش
                    }

                    // لو كل شيء تمام
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'تم بنجاح!',
                            text: data.message,
                            showConfirmButton: false,
                            timer: 2000
                        }).then(() => {
                            createModalInstance.hide();
                            form.reset();
                            document.querySelectorAll('[class*="create-day-inputs-"]').forEach(el => {
                                el.style.display = 'none';
                            });
                            applyFilters();
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire('خطأ!', 'حدث خطأ أثناء الحفظ', 'error');
                });

        }
        function toggleStatus(doctorId) {
            fetch(`/health-center/doctors/${doctorId}/toggle-status`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json'
                }
            })
                .then(response => response.json())
                .then(data => {

                    if (data.success) {
                        const label = document.querySelector(`#status-label-${doctorId}`);
                        console.log(label);
                        if (data.is_active) {
                            label.innerText = 'نشط';
                        } else {
                            label.innerText = 'غير نشط';
                        }

                        Swal.fire({
                            icon: 'success',
                            title: data.message,
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 2000
                        });
                    }
                })
                .catch(() => Swal.fire('خطأ!', 'حدث خطأ أثناء تغيير الحالة', 'error'));
        }

        function showDoctor(id) {
            fetch(`/health-center/doctors/${id}/show-details`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('showModalContent').innerHTML = data.html;
                    showModalInstance.show();
                })
                .catch(() => Swal.fire('خطأ!', 'حدث خطأ أثناء جلب البيانات', 'error'));
        }

        function editDoctor(id) {
            fetch(`/health-center/doctors/${id}/edit-form`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('editModalContent').innerHTML = data.html;
                    editModalInstance.show();
                })
                .catch(() => Swal.fire('خطأ!', 'حدث خطأ أثناء جلب البيانات', 'error'));
        }

        function closeEditModal() {
            editModalInstance.hide();
        }

        function deleteDoctor(id, name) {
            Swal.fire({
                title: 'هل أنت متأكد؟',
                text: `سيتم حذف الطبيب: ${name}`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'نعم، احذف',
                cancelButtonText: 'إلغاء'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`/health-center/doctors/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        },
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire('تم الحذف!', data.message, 'success').then(() => applyFilters());
                            } else {
                                Swal.fire('خطأ!', data.message, 'error');
                            }
                        })
                        .catch(() => Swal.fire('خطأ!', 'حدث خطأ أثناء الحذف', 'error'));
                }
            });
        }

        function openExceptionModalFromIndex(doctorId) {
            document.getElementById('exception_doctor_id_index').value = doctorId;
            exceptionModalInstance.show();
            fetch(`/health-center/doctors/${doctorId}/work-days`)
                .then(resp => resp.json())
                .then(data => {
                    const allowedDays = data.days;
                    console.log('Allowed days from server:', allowedDays); // شوف الأرقام اللي جاية

                    flatpickr("#exception_date_picker", {
                        dateFormat: "Y-m-d",
                        minDate: "today",
                        disable: [
                            function (date) {
                                const dayNum = date.getDay();
                                return !allowedDays.includes(dayNum);
                            }
                        ]
                    });
                })

                .catch(() => {
                    Swal.fire('خطأ!', 'تعذر تحميل أيام عمل الطبيب', 'error');
                });
        }


        function submitExceptionFromIndex(event) {
            event.preventDefault();
            const form = event.target;
            const formData = new FormData(form);
            const doctorId = document.getElementById('exception_doctor_id_index').value;

            form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));

            fetch(`/health-center/doctors/${doctorId}/exception`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
                body: formData
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'تم بنجاح!',
                            text: data.message,
                            showConfirmButton: false,
                            timer: 2000
                        }).then(() => {
                            exceptionModalInstance.hide();
                            form.reset();
                        });
                    } else if (data.errors) {
                        Object.keys(data.errors).forEach(key => {
                            const input = form.querySelector(`[name="${key}"]`);
                            if (input) {
                                input.classList.add('is-invalid');
                                const feedback = input.nextElementSibling;
                                if (feedback && feedback.classList.contains('invalid-feedback')) {
                                    feedback.textContent = data.errors[key][0];
                                }
                            }
                        });
                    }
                })
                .catch(() => Swal.fire('خطأ!', 'حدث خطأ أثناء الحفظ', 'error'));
        }

        @if(session('success'))
            Swal.fire({ icon: 'success', title: 'نجح!', text: '{{ session('success') }}', timer: 3000 });
        @endif

        @if(session('error'))
            Swal.fire({ icon: 'error', title: 'خطأ!', text: '{{ session('error') }}', timer: 3000 });
        @endif

                                                                         const exceptionModalEl = document.getElementById('exceptionModalShow');
        window.exceptionModalShowInstance = exceptionModalEl ? new bootstrap.Modal(exceptionModalEl) : null;

        window.deleteExceptionInShow = function (doctorId, exceptionId) {
            Swal.fire({
                title: 'هل أنت متأكد؟',
                text: 'سيتم إلغاء هذا الاعتذار',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'نعم، احذف',
                cancelButtonText: 'إلغاء'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`/health-center/doctors/${doctorId}/exception/${exceptionId}`, {
                        method: 'DELETE',
                        headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
                    })
                        .then(resp => resp.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire('تم الحذف!', data.message, 'success').then(() => {
                                    if (typeof showDoctor === 'function') showDoctor(doctorId);
                                });
                            } else {
                                Swal.fire('خطأ!', data.message || 'حدث خطأ', 'error');
                            }
                        })
                        .catch(() => Swal.fire('خطأ!', 'حدث خطأ أثناء الحذف', 'error'));
                }
            });
        }
        function submitEdit(event, doctorId) {
            event.preventDefault();
            const form = event.target;

            // إنشاء FormData جديد
            const formData = new FormData();

            // إضافة البيانات الأساسية
            formData.append('name', form.querySelector('[name="name"]').value);
            formData.append('specialty', form.querySelector('[name="specialty"]').value);
            formData.append('phone', form.querySelector('[name="phone"]').value);
            formData.append('is_active', form.querySelector('[name="is_active"]').checked ? '1' : '0');
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);
            formData.append('_method', 'PUT');

            // إضافة بيانات الجدول
            const days = ['saturday', 'sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday'];

            days.forEach((day, index) => {
                const checkbox = form.querySelector(`#edit_day_${day}`);
                const startTime = form.querySelector(`[name="schedules[${index}][start_time]"]`);
                const endTime = form.querySelector(`[name="schedules[${index}][end_time]"]`);

                formData.append(`schedules[${index}][day_of_week]`, day);

                if (checkbox && checkbox.checked) {
                    formData.append(`schedules[${index}][enabled]`, '1');
                    formData.append(`schedules[${index}][start_time]`, startTime ? startTime.value : '');
                    formData.append(`schedules[${index}][end_time]`, endTime ? endTime.value : '');
                } else {
                    formData.append(`schedules[${index}][enabled]`, '0');
                    formData.append(`schedules[${index}][start_time]`, '');
                    formData.append(`schedules[${index}][end_time]`, '');
                }
            });

            fetch(`/health-center/doctors/${doctorId}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
                body: formData
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire('نجح!', data.message, 'success').then(() => {
                            closeEditModal();
                            applyFilters(); // بدل location.reload() علشان أسرع
                        });
                    } else {
                        Swal.fire('خطأ!', data.message, 'error');
                    }
                })
                .catch(() => Swal.fire('خطأ!', 'حدث خطأ أثناء التحديث', 'error'));
        }
    </script>

    <style>
        /* الأيام الممنوعة */
        .flatpickr-day.flatpickr-disabled {
            color: #ccc !important;
            /* اrgb(106, 104, 104)رمادي */
            background: #4e4b4b !important;
            /* خلفية خفيفة حمراء */
            cursor: not-allowed;
        }

        /* الأيام المسموح بيها */
        .flatpickr-day {
            font-weight: bold;
            color: #198754;
            /* أخضر */
        }

        /* اليوم الحالي */
        .flatpickr-day.today {
            border: 2px solid #0d6efd;
            /* أزرق */
        }
    </style>
@endsection

@extends('layouts.app')

@section('title', 'إدارة الحجوزات')

@section('content')
<div class="container-fluid">
    <!-- فلترة الحجوزات -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="card-title"><i class="fas fa-filter me-2"></i>فلترة الحجوزات</h5>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('appointments.index') }}" id="filterForm">
                <div class="row">
                    <div class="col-md-3">
                        <label for="child_name" class="form-label">اسم الطفل</label>
                        <input type="text" class="form-control" id="child_name" name="child_name"
                               value="{{ request('child_name') }}">
                    </div>
                    <div class="col-md-3">
                        <label for="national_id" class="form-label">الرقم القومي</label>
                        <input type="text" class="form-control" id="national_id" name="national_id"
                               value="{{ request('national_id') }}">
                    </div>
                    <div class="col-md-3">
                        <label for="vaccine_id" class="form-label">نوع اللقاح</label>
                        <select class="form-select" id="vaccine_id" name="vaccine_id">
                            <option value="">جميع الأنواع</option>
                            @foreach($vaccines as $vaccine)
                                <option value="{{ $vaccine->id }}"
                                    {{ request('vaccine_id') == $vaccine->id ? 'selected' : '' }}>
                                    {{ $vaccine->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="status" class="form-label">الحالة</label>
                        <select class="form-select" id="status" name="status">
                            <option value="">جميع الحالات</option>
                            @foreach($statuses as $status)
                                <option value="{{ $status }}"
                                    {{ request('status') == $status ? 'selected' : '' }}>
                                    {{ $status }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-3">
                        <label for="appointment_date" class="form-label">تاريخ الموعد</label>
                        <input type="date" class="form-control" id="appointment_date" name="appointment_date"
                               value="{{ request('appointment_date') }}">
                    </div>
                    <div class="col-md-3">
                        <label for="child_birth_date" class="form-label">تاريخ ميلاد الطفل</label>
                        <input type="date" class="form-control" id="child_birth_date" name="child_birth_date"
                               value="{{ request('child_birth_date') }}">
                    </div>
                    <div class="col-md-3">
                        <label for="dose_number" class="form-label">رقم الجرعة</label>
                        <input type="number" class="form-control" id="dose_number" name="dose_number"
                               value="{{ request('dose_number') }}" min="1">
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary me-2">
                            <i class="fas fa-search me-1"></i>بحث
                        </button>
                        <a href="{{ route('appointments.index') }}" class="btn btn-secondary">
                            <i class="fas fa-undo me-1"></i>إعادة تعيين
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- جدول الحجوزات -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0"><i class="fas fa-list me-2"></i>قائمة الحجوزات</h5>
            <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#createAppointmentModal">
                <i class="fas fa-plus me-1"></i>حجز جديد
            </button>
        </div>
        <div class="card-body">
            @if($appointments->count() > 0)
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>اسم الطفل</th>
                                <th>الرقم القومي</th>
                                <th>تاريخ الميلاد</th>
                                <th>اللقاح</th>
                                <th>المركز الصحي</th>
                                <th>تاريخ الموعد</th>
                                <th>الوقت</th>
                                <th>الحالة</th>
                                <th>الجرعة</th>
                                <th>الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody id="appointmentsTable">
                            @include('appointments.partials.table_rows', ['appointments' => $appointments])
                        </tbody>
                    </table>
                </div>

                <!-- الترقيم -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $appointments->links() }}
                </div>
            @else
                <div class="alert alert-info text-center">
                    <i class="fas fa-info-circle me-2"></i>لا توجد حجوزات
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal إنشاء حجز جديد -->
<div class="modal fade" id="createAppointmentModal" tabindex="-1" aria-labelledby="createAppointmentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createAppointmentModalLabel">
                    <i class="fas fa-plus me-2"></i>إنشاء حجز جديد
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="createAppointmentForm">
                <div class="modal-body">
                    <div id="createFormErrors" class="alert alert-danger d-none"></div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="create_child_name" class="form-label">اسم الطفل <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="create_child_name" name="child_name" required>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="create_national_id" class="form-label">الرقم القومي <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="create_national_id" name="national_id"
                                       maxlength="14" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="create_child_birth_date" class="form-label">تاريخ ميلاد الطفل <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="create_child_birth_date" name="child_birth_date" required>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="create_dose_number" class="form-label">رقم الجرعة <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="create_dose_number" name="dose_number"
                                       value="1" min="1" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="create_vaccine_id" class="form-label">نوع اللقاح <span class="text-danger">*</span></label>
                                <select class="form-select" id="create_vaccine_id" name="vaccine_id" required>
                                    <option value="">اختر نوع اللقاح</option>
                                    @foreach($vaccines as $vaccine)
                                        <option value="{{ $vaccine->id }}">{{ $vaccine->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="create_health_center_id" class="form-label">المركز الصحي <span class="text-danger">*</span></label>
                                <select class="form-select" id="create_health_center_id" name="health_center_id" required>
                                    <option value="">اختر المركز الصحي</option>
                                    @foreach($healthCenters as $center)
                                        <option value="{{ $center->id }}">{{ $center->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="create_appointment_date" class="form-label">تاريخ الموعد <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="create_appointment_date" name="appointment_date"
                                       min="{{ date('Y-m-d') }}" required>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="create_appointment_time" class="form-label">وقت الموعد <span class="text-danger">*</span></label>
                                <input type="time" class="form-control" id="create_appointment_time" name="appointment_time" required>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="create_notes" class="form-label">ملاحظات</label>
                        <textarea class="form-control" id="create_notes" name="notes" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-primary" id="createAppointmentBtn">
                        <i class="fas fa-save me-1"></i>حفظ
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal تعديل الحجز -->
<div class="modal fade" id="editAppointmentModal" tabindex="-1" aria-labelledby="editAppointmentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editAppointmentModalLabel">
                    <i class="fas fa-edit me-2"></i>تعديل الحجز
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editAppointmentForm">
                @method('PUT')
                <div class="modal-body">
                    <div id="editFormErrors" class="alert alert-danger d-none"></div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_child_name" class="form-label">اسم الطفل <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="edit_child_name" name="child_name" required>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_national_id" class="form-label">الرقم القومي <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="edit_national_id" name="national_id"
                                       maxlength="14" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_child_birth_date" class="form-label">تاريخ ميلاد الطفل <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="edit_child_birth_date" name="child_birth_date" required>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_dose_number" class="form-label">رقم الجرعة <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="edit_dose_number" name="dose_number"
                                       min="1" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_vaccine_id" class="form-label">نوع اللقاح <span class="text-danger">*</span></label>
                                <select class="form-select" id="edit_vaccine_id" name="vaccine_id" required>
                                    <option value="">اختر نوع اللقاح</option>
                                    @foreach($vaccines as $vaccine)
                                        <option value="{{ $vaccine->id }}">{{ $vaccine->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_health_center_id" class="form-label">المركز الصحي <span class="text-danger">*</span></label>
                                <select class="form-select" id="edit_health_center_id" name="health_center_id" required>
                                    <option value="">اختر المركز الصحي</option>
                                    @foreach($healthCenters as $center)
                                        <option value="{{ $center->id }}">{{ $center->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_appointment_date" class="form-label">تاريخ الموعد <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="edit_appointment_date" name="appointment_date" required>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_appointment_time" class="form-label">وقت الموعد <span class="text-danger">*</span></label>
                                <input type="time" class="form-control" id="edit_appointment_time" name="appointment_time" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_status" class="form-label">الحالة <span class="text-danger">*</span></label>
                                <select class="form-select" id="edit_status" name="status" required>
                                    @foreach($statuses as $status)
                                        <option value="{{ $status }}">{{ $status }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="edit_notes" class="form-label">ملاحظات</label>
                        <textarea class="form-control" id="edit_notes" name="notes" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-primary" id="editAppointmentBtn">
                        <i class="fas fa-save me-1"></i>حفظ التغييرات
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal عرض التفاصيل -->
<div class="modal fade" id="showAppointmentModal" tabindex="-1" aria-labelledby="showAppointmentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="showAppointmentModalLabel">
                    <i class="fas fa-eye me-2"></i>تفاصيل الحجز
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="showAppointmentContent">
                <!-- سيتم تحميل المحتوى هنا عبر AJAX -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal تأكيد الحذف -->
<div class="modal fade" id="deleteAppointmentModal" tabindex="-1" aria-labelledby="deleteAppointmentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteAppointmentModalLabel">تأكيد الحذف</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>هل أنت متأكد من أنك تريد حذف هذا الحجز؟</p>
                <p class="text-danger">هذا الإجراء لا يمكن التراجع عنه.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                <form id="deleteAppointmentForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">حذف</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // حساب تاريخ الميلاد من الرقم القومي
    function calculateBirthDateFromNationalId(nationalId) {
        if (nationalId.length === 14) {
            const yearPrefix = parseInt(nationalId.charAt(0)) < 3 ? '19' : '20';
            const year = yearPrefix + nationalId.substring(1, 3);
            const month = nationalId.substring(3, 5);
            const day = nationalId.substring(5, 7);
            return `${year}-${month}-${day}`;
        }
        return null;
    }

    // تطبيق حساب تاريخ الميلاد للحقول
    $('#create_national_id, #edit_national_id').on('blur', function() {
        const nationalId = $(this).val();
        const birthDate = calculateBirthDateFromNationalId(nationalId);
        if (birthDate) {
            const prefix = $(this).attr('id').split('_')[0];
            $(`#${prefix}_child_birth_date`).val(birthDate);
        }
    });

    // إنشاء حجز جديد
    $('#createAppointmentForm').on('submit', function(e) {
        e.preventDefault();

        const btn = $('#createAppointmentBtn');
        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i>جاري الحفظ...');

        $.ajax({
            url: '{{ route("appointments.store") }}',
            method: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                $('#createAppointmentModal').modal('hide');
                $('#createAppointmentForm')[0].reset();
                showAlert('success', response.message);
                // إعادة تحميل الجدول
                loadAppointments();
            },
            error: function(xhr) {
                let errors = xhr.responseJSON.errors;
                let errorHtml = '';
                for (let field in errors) {
                    errorHtml += errors[field][0] + '<br>';
                }
                $('#createFormErrors').html(errorHtml).removeClass('d-none');
            },
            complete: function() {
                btn.prop('disabled', false).html('<i class="fas fa-save me-1"></i>حفظ');
            }
        });
    });

    // فتح modal التعديل
    $(document).on('click', '.edit-appointment', function() {
        const appointmentId = $(this).data('id');

        $.ajax({
            url: `/appointments/${appointmentId}/edit`,
            method: 'GET',
            success: function(response) {
                const appointment = response.appointment;

                $('#edit_child_name').val(appointment.child_name);
                $('#edit_national_id').val(appointment.national_id);
                $('#edit_child_birth_date').val(appointment.child_birth_date);
                $('#edit_dose_number').val(appointment.dose_number);
                $('#edit_vaccine_id').val(appointment.vaccine_id);
                $('#edit_health_center_id').val(appointment.health_center_id);
                $('#edit_appointment_date').val(appointment.appointment_date);
                $('#edit_appointment_time').val(appointment.appointment_time);
                $('#edit_status').val(appointment.status);
                $('#edit_notes').val(appointment.notes);

                $('#editAppointmentForm').attr('action', `/appointments/${appointmentId}`);
                $('#editAppointmentModal').modal('show');
            }
        });
    });

    // تحديث الحجز
    $('#editAppointmentForm').on('submit', function(e) {
        e.preventDefault();

        const btn = $('#editAppointmentBtn');
        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i>جاري الحفظ...');

        $.ajax({
            url: $(this).attr('action'),
            method: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                $('#editAppointmentModal').modal('hide');
                showAlert('success', response.message);
                loadAppointments();
            },
            error: function(xhr) {
                let errors = xhr.responseJSON.errors;
                let errorHtml = '';
                for (let field in errors) {
                    errorHtml += errors[field][0] + '<br>';
                }
                $('#editFormErrors').html(errorHtml).removeClass('d-none');
            },
            complete: function() {
                btn.prop('disabled', false).html('<i class="fas fa-save me-1"></i>حفظ التغييرات');
            }
        });
    });

    // فتح modal العرض
    $(document).on('click', '.show-appointment', function() {
        const appointmentId = $(this).data('id');

        $.ajax({
            url: `/appointments/${appointmentId}`,
            method: 'GET',
            success: function(response) {
                $('#showAppointmentContent').html(`
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <tr><th class="bg-light">اسم الطفل</th><td>${response.appointment.child_name}</td></tr>
                                <tr><th class="bg-light">الرقم القومي</th><td>${response.appointment.national_id}</td></tr>
                                <tr><th class="bg-light">تاريخ الميلاد</th><td>${response.appointment.child_birth_date}</td></tr>
                                <tr><th class="bg-light">نوع اللقاح</th><td>${response.appointment.vaccine.name}</td></tr>
                                <tr><th class="bg-light">المركز الصحي</th><td>${response.appointment.health_center.name}</td></tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <tr><th class="bg-light">تاريخ الموعد</th><td>${response.appointment.appointment_date}</td></tr>
                                <tr><th class="bg-light">وقت الموعد</th><td>${response.appointment.appointment_time}</td></tr>
                                <tr><th class="bg-light">الحالة</th><td>${response.appointment.status}</td></tr>
                                <tr><th class="bg-light">الجرعة</th><td>${response.appointment.dose_number}</td></tr>
                                <tr><th class="bg-light">تاريخ الإنشاء</th><td>${response.appointment.created_at}</td></tr>
                            </table>
                        </div>
                    </div>
                    ${response.appointment.notes ? `
                    <div class="row mt-3">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header bg-light"><h6 class="mb-0">ملاحظات</h6></div>
                                <div class="card-body">${response.appointment.notes}</div>
                            </div>
                        </div>
                    </div>
                    ` : ''}
                `);
                $('#showAppointmentModal').modal('show');
            }
        });
    });

    // فتح modal الحذف
    $(document).on('click', '.delete-appointment', function() {
        const appointmentId = $(this).data('id');
        $('#deleteAppointmentForm').attr('action', `/appointments/${appointmentId}`);
        $('#deleteAppointmentModal').modal('show');
    });

    // تأكيد الحذف
    $('#deleteAppointmentForm').on('submit', function(e) {
        e.preventDefault();

        $.ajax({
            url: $(this).attr('action'),
            method: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                $('#deleteAppointmentModal').modal('hide');
                showAlert('success', response.message);
                loadAppointments();
            },
            error: function(xhr) {
                showAlert('error', 'حدث خطأ أثناء حذف الحجز');
            }
        });
    });

    // إعادة تحميل الجدول
    function loadAppointments() {
        $.ajax({
            url: '{{ route("appointments.index") }}',
            method: 'GET',
            data: $('#filterForm').serialize(),
            success: function(response) {
                $('#appointmentsTable').html($(response).find('#appointmentsTable').html());
            }
        });
    }

    // عرض التنبيهات
    function showAlert(type, message) {
        const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
        const alertHtml = `
            <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `;
        $('.container-fluid').prepend(alertHtml);

        setTimeout(() => {
            $('.alert').alert('close');
        }, 5000);
    }

    // إخفاء أخطاء النماذج عند إغلاق الـ modals
    $('.modal').on('hidden.bs.modal', function() {
        $(this).find('.alert-danger').addClass('d-none');
    });
});
</script>
@endsection

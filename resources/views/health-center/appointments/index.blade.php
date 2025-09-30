@extends('layouts.health-center.master')

@section('title', 'إدارة الحجوزات')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-md-6">
            <h2 class="mb-0 text-primary">
                <i class="fas fa-calendar-check me-2"></i>إدارة الحجوزات
            </h2>
            <p class="text-muted mb-0">عرض وإدارة حجوزات التطعيمات</p>
        </div>
        <div class="col-md-6 text-end">
            <button type="button" class="btn btn-primary btn-lg shadow-sm" data-bs-toggle="modal" data-bs-target="#createAppointmentModal">
                <i class="fas fa-plus-circle me-2"></i>إنشاء حجز جديد
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
                <div class="col-md-3">
                    <label class="form-label fw-semibold text-secondary">
                        <i class="fas fa-child text-primary me-2"></i>اسم الطفل
                    </label>
                    <input type="text" class="form-control form-control-lg filter-input" id="child_name" name="child_name"
                        placeholder="ابحث باسم الطفل...">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold text-secondary">
                        <i class="fas fa-id-card text-info me-2"></i>الرقم القومي
                    </label>
                    <input type="text" class="form-control form-control-lg filter-input" id="national_id" name="national_id"
                        placeholder="الرقم القومي...">
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-semibold text-secondary">
                        <i class="fas fa-syringe text-success me-2"></i>نوع اللقاح
                    </label>
                    <select class="form-select form-select-lg filter-input" id="vaccine_id" name="vaccine_id">
                        <option value="">جميع الأنواع</option>
                        @foreach($vaccines as $vaccine)
                            <option value="{{ $vaccine->id }}">{{ $vaccine->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-semibold text-secondary">
                        <i class="fas fa-info-circle text-warning me-2"></i>الحالة
                    </label>
                    <select class="form-select form-select-lg filter-input" id="status" name="status">
                        <option value="">جميع الحالات</option>
                        @foreach($statuses as $status)
                            <option value="{{ $status }}">{{ $status }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-outline-secondary btn-lg w-100" id="clearFilters">
                        <i class="fas fa-times me-1"></i>مسح
                    </button>
                </div>
            </div>
            <div class="row g-3 mt-2">
                <div class="col-md-3">
                    <label class="form-label fw-semibold text-secondary">
                        <i class="fas fa-calendar text-primary me-2"></i>تاريخ الموعد
                    </label>
                    <input type="date" class="form-control form-control-lg filter-input" id="appointment_date" name="appointment_date">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold text-secondary">
                        <i class="fas fa-birthday-cake text-info me-2"></i>تاريخ ميلاد الطفل
                    </label>
                    <input type="date" class="form-control form-control-lg filter-input" id="child_birth_date" name="child_birth_date">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold text-secondary">
                        <i class="fas fa-hashtag text-success me-2"></i>رقم الجرعة
                    </label>
                    <input type="number" class="form-control form-control-lg filter-input" id="dose_number" name="dose_number" min="1">
                </div>
            </div>
        </div>
    </div>

    <!-- عداد النتائج -->
    <div class="mb-3">
        <div class="alert alert-info border-0 shadow-sm d-inline-block">
            <i class="fas fa-info-circle me-2"></i>
            إجمالي الحجوزات: <strong id="appointmentsCount">{{ $appointments->total() }}</strong>
        </div>
    </div>

    <!-- الجدول -->
    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div id="appointmentsTableContainer">
                @if($appointments->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped table-hover" id="appointmentsTable">
                            <thead style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                <tr class="text-white">
                                    <th class="py-3">اسم الطفل</th>
                                    <th class="py-3">الرقم القومي</th>
                                    <th class="py-3">تاريخ الميلاد</th>
                                    <th class="py-3">اللقاح</th>
                                    <th class="py-3">تاريخ الموعد</th>
                                    <th class="py-3">الوقت</th>
                                    <th class="py-3">الحالة</th>
                                    <th class="py-3">الجرعة</th>
                                    <th class="py-3">الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody id="appointmentsTableBody">
                                @foreach($appointments as $appointment)
                                    <tr data-child-name="{{ strtolower($appointment->child_name) }}"
                                        data-national-id="{{ $appointment->national_id }}"
                                        data-vaccine-id="{{ $appointment->vaccine_id }}"
                                        data-status="{{ $appointment->status }}"
                                        data-appointment-date="{{ $appointment->appointment_date }}"
                                        data-child-birth-date="{{ $appointment->child_birth_date }}"
                                        data-dose-number="{{ $appointment->dose_number }}">
                                        <td class="child-name">{{ $appointment->child_name }}</td>
                                        <td class="national-id">{{ $appointment->national_id }}</td>
                                        <td>{{ $appointment->child_birth_date }}</td>
                                        <td>{{ $appointment->vaccine->name }}</td>
                                        <td>{{ $appointment->appointment_date }}</td>
                                        <td>
                                            <span class="">
                                                {{ $appointment->appointment_time->format('h:i A') }}
                                            </span>
                                        </td>
                                        <td>
                                            @switch($appointment->status)
                                                @case('مجدول')
                                                    <span class="badge bg-primary">{{ $appointment->status }}</span>
                                                    @break
                                                @case('مكتمل')
                                                    <span class="badge bg-success">{{ $appointment->status }}</span>
                                                    @break
                                                @case('ملغي')
                                                    <span class="badge bg-danger">{{ $appointment->status }}</span>
                                                    @break
                                                @case('لم يحضر')
                                                    <span class="badge bg-warning">{{ $appointment->status }}</span>
                                                    @break
                                                @default
                                                    <span class="badge bg-secondary">{{ $appointment->status }}</span>
                                            @endswitch
                                        </td>
                                        <td>
                                            <span class="badge">الجرعة {{ $appointment->dose_number }}</span>
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <button type="button" class="btn btn-outline-info show-appointment"
                                                        data-id="{{ $appointment->id }}" title="عرض التفاصيل">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <button type="button" class="btn btn-outline-warning edit-appointment"
                                                        data-id="{{ $appointment->id }}" title="تعديل">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button type="button" class="btn btn-outline-danger delete-appointment"
                                                        data-id="{{ $appointment->id }}"
                                                        data-child-name="{{ $appointment->child_name }}"
                                                        title="حذف">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-5" id="emptyState">
                        <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">لا توجد حجوزات</h5>
                        <p class="text-muted">لم يتم العثور على حجوزات بالمعايير المحددة</p>
                        <button type="button" class="btn btn-success mt-3" data-bs-toggle="modal" data-bs-target="#createAppointmentModal">
                            <i class="fas fa-plus me-1"></i>إنشاء حجز جديد
                        </button>
                    </div>
                @endif
            </div>

            @if($appointments->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{ $appointments->links() }}
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
                                <input type="date" class="form-control" id="create_child_birth_date" name="child_birth_date" readonly>
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
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="create_vaccine_id" class="form-label">نوع اللقاح <span class="text-danger">*</span></label>
                                <select id="create_vaccine_id" name="vaccine_id" class="form-control">
                                    <option value="">اختر اللقاح</option>
                                    @foreach($vaccines as $vaccine)
                                        <option value="{{ $vaccine->id }}">{{ $vaccine->name }}</option>
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
                        <span class="spinner-border spinner-border-sm me-1" style="display: none;"></span>
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
                                <input type="date" class="form-control" id="edit_child_birth_date" name="child_birth_date" readonly>
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
                                 <select id="edit_vaccine_id" name="vaccine_id" class="form-control">
                                    <option value="">اختر اللقاح</option>
                                    @foreach($vaccines as $vaccine)
                                        <option value="{{ $vaccine->id }}">{{ $vaccine->name }}</option>
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
                        <span class="spinner-border spinner-border-sm me-1" style="display: none;"></span>
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

@endsection

@section('styles')
<!-- SweetAlert2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">

<style>
.search-highlight {
    background-color: #fff3cd;
    padding: 2px 4px;
    border-radius: 3px;
}

.table tbody tr {
    transition: all 0.2s ease;
}

.table tbody tr:hover {
    background-color: #f8f9fa;
}

.badge {
    font-size: 0.75rem;
    font-weight: 500;
}

.btn-group-sm .btn {
    padding: 0.25rem 0.5rem;
}
</style>
@endsection

@section('scripts')
<!-- SweetAlert2 JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(document).ready(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('#create_vaccine_id').select2({
        dropdownParent: $('#createAppointmentModal'), // 👈 مهم علشان المودال
        placeholder: "اختر اللقاح",
        allowClear: true,
        width: '100%'
    });
    // الفلترة المباشرة أثناء الكتابة
    $('.filter-input').on('input change', function() {
        setTimeout(() => {
            filterAppointments();
        }, 300); // تأخير قصير لتحسين الأداء
    });

    // مسح الفلاتر
    $('#clearFilters').click(function() {
        $('.filter-input').val('');
        filterAppointments();
    });

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

    // فلترة الحجوزات
    function filterAppointments() {
        const childName = $('#child_name').val().toLowerCase();
        const nationalId = $('#national_id').val();
        const vaccineId = $('#vaccine_id').val();
        const status = $('#status').val();
        const appointmentDate = $('#appointment_date').val();
        const childBirthDate = $('#child_birth_date').val();
        const doseNumber = $('#dose_number').val();

        let visibleCount = 0;

        $('#appointmentsTableBody tr').each(function() {
            const row = $(this);
            const rowChildName = row.data('child-name');
            const rowNationalId = row.data('national-id');
            const rowVaccineId = row.data('vaccine-id');
            const rowStatus = row.data('status');
            const rowAppointmentDate = row.data('appointment-date');
            const rowChildBirthDate = row.data('child-birth-date');
            const rowDoseNumber = row.data('dose-number');

            let showRow = true;

            // فلترة اسم الطفل
            if (childName && !rowChildName.includes(childName)) {
                showRow = false;
            }

            // فلترة الرقم القومي
            if (nationalId && !rowNationalId.includes(nationalId)) {
                showRow = false;
            }

            // فلترة نوع اللقاح
            if (vaccineId && rowVaccineId != vaccineId) {
                showRow = false;
            }

            // فلترة الحالة
            if (status && rowStatus !== status) {
                showRow = false;
            }

            // فلترة تاريخ الموعد
            if (appointmentDate && rowAppointmentDate !== appointmentDate) {
                showRow = false;
            }

            // فلترة تاريخ ميلاد الطفل
            if (childBirthDate && rowChildBirthDate !== childBirthDate) {
                showRow = false;
            }

            // فلترة رقم الجرعة
            if (doseNumber && rowDoseNumber != doseNumber) {
                showRow = false;
            }

            if (showRow) {
                row.show();
                visibleCount++;

                // تمييز النص المطابق
                if (childName) {
                    highlightText(row.find('.child-name'), childName);
                } else {
                    removeHighlight(row.find('.child-name'));
                }

                if (nationalId) {
                    highlightText(row.find('.national-id'), nationalId);
                } else {
                    removeHighlight(row.find('.national-id'));
                }
            } else {
                row.hide();
            }
        });

        // تحديث العداد
        $('#appointmentsCount').text(visibleCount);

        // إظهار/إخفاء الجدول حسب النتائج
        if (visibleCount === 0) {
            $('#appointmentsTable').hide();
            if ($('#noResults').length === 0) {
                $('#appointmentsTableContainer').append(`
                    <div class="text-center py-5" id="noResults">
                        <i class="fas fa-search fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">لا توجد نتائج</h5>
                        <p class="text-muted">لم يتم العثور على حجوزات بالمعايير المحددة</p>
                    </div>
                `);
            }
        } else {
            $('#appointmentsTable').show();
            $('#noResults').remove();
        }
    }

    // تمييز النص
    function highlightText(element, searchText) {
        const originalText = element.data('original-text') || element.text();
        if (!element.data('original-text')) {
            element.data('original-text', originalText);
        }

        const regex = new RegExp(`(${searchText})`, 'gi');
        const highlightedText = originalText.replace(regex, '<span class="search-highlight">$1</span>');
        element.html(highlightedText);
    }

    // إزالة التمييز
    function removeHighlight(element) {
        const originalText = element.data('original-text');
        if (originalText) {
            element.text(originalText);
        }
    }

    // إنشاء حجز جديد
    $('#createAppointmentForm').on('submit', function(e) {
        e.preventDefault();

        const btn = $('#createAppointmentBtn');
        const spinner = btn.find('.spinner-border');
        const icon = btn.find('.fas');

        btn.prop('disabled', true);
        spinner.show();
        icon.hide();

        $.ajax({
            url: '{{ route("health-center.appointments.store") }}',
            method: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                $('#createAppointmentModal').modal('hide');
                $('#createAppointmentForm')[0].reset();

                Swal.fire({
                    icon: 'success',
                    title: 'تم بنجاح',
                    text: response.message,
                    timer: 2000,
                    showConfirmButton: false
                }).then(() => {
                    location.reload();
                });
            },
            error: function(xhr) {
                let errors = xhr.responseJSON?.errors || {};
                let errorHtml = '';
                for (let field in errors) {
                    errorHtml += errors[field][0] + '<br>';
                }
                $('#createFormErrors').html(errorHtml).removeClass('d-none');

                if (!errorHtml) {
                    Swal.fire({
                        icon: 'error',
                        title: 'خطأ',
                        text: 'حدث خطأ أثناء إنشاء الحجز'
                    });
                }
            },
            complete: function() {
                btn.prop('disabled', false);
                spinner.hide();
                icon.show();
            }
        });
    });

    // فتح modal التعديل
    $(document).on('click', '.edit-appointment', function() {
        const appointmentId = $(this).data('id');

        Swal.fire({
            title: 'جاري التحميل...',
            allowOutsideClick: false,
            allowEscapeKey: false,
            showConfirmButton: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        $.ajax({
            url: `/health-center/appointments/${appointmentId}/edit`,
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
                $('#edit_vaccine_id').select2({
                    dropdownParent: $('#editAppointmentModal'),
                    placeholder: "اختر اللقاح",
                    allowClear: true,
                    width: '100%'
                });

                $('#editAppointmentForm').attr('action', `/health-center/appointments/${appointmentId}`);

                Swal.close();
                $('#editAppointmentModal').modal('show');
            },
            error: function() {
                Swal.fire({
                    icon: 'error',
                    title: 'خطأ',
                    text: 'حدث خطأ أثناء تحميل بيانات الحجز'
                });
            }
        });
    });

    // تحديث الحجز
    $('#editAppointmentForm').on('submit', function(e) {
        e.preventDefault();

        const btn = $('#editAppointmentBtn');
        const spinner = btn.find('.spinner-border');
        const icon = btn.find('.fas');

        btn.prop('disabled', true);
        spinner.show();
        icon.hide();

        $.ajax({
            url: $(this).attr('action'),
            method: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                $('#editAppointmentModal').modal('hide');

                Swal.fire({
                    icon: 'success',
                    title: 'تم التحديث',
                    text: response.message,
                    timer: 2000,
                    showConfirmButton: false
                }).then(() => {
                    location.reload();
                });
            },
            error: function(xhr) {
                let errors = xhr.responseJSON?.errors || {};
                let errorHtml = '';
                for (let field in errors) {
                    errorHtml += errors[field][0] + '<br>';
                }
                $('#editFormErrors').html(errorHtml).removeClass('d-none');

                if (!errorHtml) {
                    Swal.fire({
                        icon: 'error',
                        title: 'خطأ',
                        text: 'حدث خطأ أثناء تحديث الحجز'
                    });
                }
            },
            complete: function() {
                btn.prop('disabled', false);
                spinner.hide();
                icon.show();
            }
        });
    });

    // فتح modal العرض
    $(document).on('click', '.show-appointment', function() {
        const appointmentId = $(this).data('id');

        Swal.fire({
            title: 'جاري التحميل...',
            allowOutsideClick: false,
            allowEscapeKey: false,
            showConfirmButton: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        $.ajax({
            url: `/health-center/appointments/${appointmentId}`,
            method: 'GET',
            success: function(response) {
                const appointment = response.appointment;
                const formattedTime = new Date(`1970-01-01T${appointment.appointment_time}`).toLocaleTimeString('ar-EG', {
                    hour: '2-digit',
                    minute: '2-digit',
                    hour12: true
                });

                $('#showAppointmentContent').html(`
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <tr><th class="bg-light">اسم الطفل</th><td>${appointment.child_name}</td></tr>
                                <tr><th class="bg-light">الرقم القومي</th><td>${appointment.national_id}</td></tr>
                                <tr><th class="bg-light">تاريخ الميلاد</th><td>${appointment.child_birth_date}</td></tr>
                                <tr><th class="bg-light">نوع اللقاح</th><td>${appointment.vaccine.name}</td></tr>
                                <tr><th class="bg-light">المركز الصحي</th><td>${appointment.health_center.name}</td></tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <tr><th class="bg-light">تاريخ الموعد</th><td>${appointment.appointment_date}</td></tr>
                                <tr><th class="bg-light">وقت الموعد</th><td><span class="badge bg-info">${formattedTime}</span></td></tr>
                                <tr><th class="bg-light">الحالة</th><td>
                                    ${getStatusBadge(appointment.status)}
                                </td></tr>
                                <tr><th class="bg-light">الجرعة</th><td><span class="badge ">الجرعة ${appointment.dose_number}</span></td></tr>
                                <tr><th class="bg-light">تاريخ الإنشاء</th><td>${new Date(appointment.created_at).toLocaleDateString('ar-EG')}</td></tr>
                            </table>
                        </div>
                    </div>
                    ${appointment.notes ? `
                    <div class="row mt-3">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header bg-light"><h6 class="mb-0">ملاحظات</h6></div>
                                <div class="card-body">${appointment.notes}</div>
                            </div>
                        </div>
                    </div>
                    ` : ''}
                `);

                Swal.close();
                $('#showAppointmentModal').modal('show');
            },
            error: function() {
                Swal.fire({
                    icon: 'error',
                    title: 'خطأ',
                    text: 'حدث خطأ أثناء تحميل تفاصيل الحجز'
                });
            }
        });
    });

    // دالة لإرجاع badge الحالة
    function getStatusBadge(status) {
        switch(status) {
            case 'مجدول':
                return '<span class="badge bg-primary">مجدول</span>';
            case 'مكتمل':
                return '<span class="badge bg-success">مكتمل</span>';
            case 'ملغي':
                return '<span class="badge bg-danger">ملغي</span>';
            case 'لم يحضر':
                return '<span class="badge bg-warning">لم يحضر</span>';
            default:
                return '<span class="badge bg-secondary">' + status + '</span>';
        }
    }

    // فتح تأكيد الحذف
    $(document).on('click', '.delete-appointment', function() {
        const appointmentId = $(this).data('id');
        const childName = $(this).data('child-name');

        Swal.fire({
            title: 'تأكيد الحذف',
            text: `هل أنت متأكد من حذف حجز "${childName}"؟`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'نعم، احذف',
            cancelButtonText: 'إلغاء',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                deleteAppointment(appointmentId, childName);
            }
        });
    });

    // حذف الحجز
    function deleteAppointment(appointmentId, childName) {
        Swal.fire({
            title: 'جاري الحذف...',
            allowOutsideClick: false,
            allowEscapeKey: false,
            showConfirmButton: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        $.ajax({
            url: `/health-center/appointments/${appointmentId}`,
            method: 'DELETE',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                Swal.fire({
                    icon: 'success',
                    title: 'تم الحذف',
                    text: response.message,
                    timer: 2000,
                    showConfirmButton: false
                }).then(() => {
                    // إزالة الصف من الجدول
                    $(`button[data-id="${appointmentId}"]`).closest('tr').fadeOut(400, function() {
                        $(this).remove();
                        filterAppointments(); // إعادة حساب العداد
                    });
                });
            },
            error: function(xhr) {
                let message = 'حدث خطأ أثناء حذف الحجز';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    message = xhr.responseJSON.message;
                }
                Swal.fire({
                    icon: 'error',
                    title: 'خطأ',
                    text: message
                });
            }
        });
    }

    // إخفاء أخطاء النماذج عند إغلاق الـ modals
    $('.modal').on('hidden.bs.modal', function() {
        $(this).find('.alert-danger').addClass('d-none');
        $(this).find('form')[0]?.reset();
    });

    // تحسين عرض الوقت في الجدول
    function formatTime(timeString) {
        const time = new Date(`1970-01-01T${timeString}`);
        return time.toLocaleTimeString('ar-EG', {
            hour: '2-digit',
            minute: '2-digit',
            hour12: true
        });
    }

    // تطبيق تنسيق الوقت على الجدول الحالي
    $('.table tbody tr').each(function() {
        const timeCell = $(this).find('td:nth-child(7)');
        const timeText = timeCell.find('.badge').text();
        if (timeText && timeText.includes(':')) {
            const formattedTime = formatTime(timeText);
            timeCell.find('.badge').text(formattedTime);
        }
    });
});
</script>
@endsection

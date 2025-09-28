@extends('layouts.master')

@section('title', 'إدارة الأدوية - المركز الصحي')

@section('content')
<div class="container-fluid py-4">
    <!-- عنوان الصفحة -->
    <div class="row mb-4">
        <div class="col-md-6">
            <h2 class="text-primary">إدارة الأدوية</h2>
            <p class="text-muted">عرض وإدارة أدوية المركز الصحي</p>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ route('health-center.drugs.create') }}" class="btn btn-success me-2">
                <i class="fas fa-plus me-1"></i>إضافة دواء جديد
            </a>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addDrugModal">
                <i class="fas fa-list-plus me-1"></i>إضافة دواء موجود
            </button>
        </div>
    </div>

    <!-- فلاتر البحث -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">بحث بالاسم</label>
                    <input type="text" class="form-control" id="searchInput"
                           placeholder="اسم الدواء أو الاسم العلمي">
                </div>
                <div class="col-md-3">
                    <label class="form-label">الفئة</label>
                    <select class="form-select" id="categoryFilter">
                        <option value="">جميع الفئات</option>
                        @foreach($categories as $category)
                            <option value="{{ $category }}">{{ $category }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">التوفر</label>
                    <select class="form-select" id="availabilityFilter">
                        <option value="">الكل</option>
                        <option value="available">متاح</option>
                        <option value="unavailable">غير متاح</option>
                    </select>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="button" class="btn btn-outline-secondary w-100" id="clearFilters">
                        <i class="fas fa-times me-1"></i>مسح الفلاتر
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- قائمة الأدوية -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">الأدوية المتاحة (<span id="drugsCount">{{ $drugs->total() }}</span> دواء)</h5>
            <small class="text-muted">الأدوية الموجودة في مركزك الصحي</small>
        </div>
        <div class="card-body p-0">
            <div id="drugsTableContainer">
                @if($drugs->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover mb-0" id="drugsTable">
                            <thead class="table-light">
                                <tr>
                                    <th>اسم الدواء</th>
                                    <th>الاسم العلمي</th>
                                    <th>الفئة</th>
                                    <th>الشركة المصنعة</th>
                                    <th>الكمية</th>
                                    <th>السعر</th>
                                    <th>التوفر</th>
                                    <th>الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody id="drugsTableBody">
                                @foreach($drugs as $drug)
                                    @php
                                        $centerDrug = $drug->healthCenters->first();
                                    @endphp
                                    <tr data-drug-name="{{ strtolower($drug->name) }}"
                                        data-scientific-name="{{ strtolower($drug->scientific_name ?: '') }}"
                                        data-category="{{ $drug->category }}"
                                        data-availability="{{ $centerDrug->pivot->availability ? 'available' : 'unavailable' }}">
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <strong class="drug-name">{{ $drug->name }}</strong>
                                                @if($drug->is_government_approved)
                                                    <span class="badge bg-success ms-2" title="معتمد حكومياً">
                                                        <i class="fas fa-certificate"></i>
                                                    </span>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="text-muted scientific-name">{{ $drug->scientific_name ?: 'غير محدد' }}</td>
                                        <td>
                                            @if($drug->category)
                                                <span class="badge bg-secondary">{{ $drug->category }}</span>
                                            @else
                                                <span class="text-muted">غير محدد</span>
                                            @endif
                                        </td>
                                        <td>{{ $drug->manufacturer ?: 'غير محدد' }}</td>
                                        <td>
                                            <div class="input-group input-group-sm" style="width: 120px;">
                                                <input type="number" class="form-control stock-input"
                                                       value="{{ $centerDrug->pivot->stock }}"
                                                       data-drug-id="{{ $drug->id }}"
                                                       data-original-value="{{ $centerDrug->pivot->stock }}"
                                                       min="0">
                                                <button class="btn btn-outline-primary update-stock-btn"
                                                        data-drug-id="{{ $drug->id }}"
                                                        type="button"
                                                        style="display: none;">
                                                    <i class="fas fa-save"></i>
                                                </button>
                                            </div>
                                        </td>
                                        <td>
                                            @if($drug->price)
                                                <span class="fw-bold">{{ number_format($drug->price, 2) }} ج.م</span>
                                            @else
                                                <span class="text-muted">غير محدد</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="form-check form-switch">
                                                <input class="form-check-input availability-toggle"
                                                       type="checkbox"
                                                       data-drug-id="{{ $drug->id }}"
                                                       {{ $centerDrug->pivot->availability ? 'checked' : '' }}>
                                                <label class="form-check-label">
                                                    <span class="availability-text">
                                                        {{ $centerDrug->pivot->availability ? 'متاح' : 'غير متاح' }}
                                                    </span>
                                                </label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ route('health-center.drugs.show', $drug->id) }}"
                                                   class="btn btn-outline-info" title="عرض التفاصيل">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <button type="button"
                                                        class="btn btn-outline-danger remove-drug-btn"
                                                        data-drug-id="{{ $drug->id }}"
                                                        data-drug-name="{{ $drug->name }}"
                                                        title="حذف من المركز">
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
                        <i class="fas fa-pills fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">لا توجد أدوية</h5>
                        <p class="text-muted">لم يتم العثور على أدوية بالمعايير المحددة</p>
                        <div class="mt-3">
                            <a href="{{ route('health-center.drugs.create') }}" class="btn btn-success me-2">
                                <i class="fas fa-plus me-1"></i>إضافة دواء جديد
                            </a>
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addDrugModal">
                                <i class="fas fa-list-plus me-1"></i>إضافة دواء موجود
                            </button>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        @if($drugs->hasPages())
            <div class="card-footer">
                {{ $drugs->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Modal إضافة دواء موجود -->
<div class="modal fade" id="addDrugModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">إضافة دواء موجود للمركز</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="addDrugForm">
                    <div class="mb-3">
                        <label class="form-label">اختر الدواء</label>
                        <select class="form-select" name="drug_id" required>
                            <option value="">-- اختر الدواء --</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">الكمية المتاحة</label>
                        <input type="number" class="form-control" name="stock" min="0" value="0" required>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="1" name="availability" checked>
                        <label class="form-check-label">متاح للمرضى</label>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                <button type="button" class="btn btn-success" id="addDrugBtn">
                    <span class="spinner-border spinner-border-sm me-1" style="display: none;"></span>
                    إضافة الدواء
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('styles')
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

.update-stock-btn {
    transition: all 0.3s ease;
}

.stock-input.changed {
    border-color: #ffc107;
    box-shadow: 0 0 0 0.2rem rgba(255, 193, 7, 0.25);
}
</style>
@endsection

@section('scripts')
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(document).ready(function() {
    // تحميل الأدوية المتاحة عند فتح المودال
    $('#addDrugModal').on('show.bs.modal', function() {
        loadAvailableDrugs();
    });

    // الفلترة المباشرة أثناء الكتابة
    $('#searchInput, #categoryFilter, #availabilityFilter').on('input change', function() {
        filterDrugs();
    });

    // مسح الفلاتر
    $('#clearFilters').click(function() {
        $('#searchInput').val('');
        $('#categoryFilter').val('');
        $('#availabilityFilter').val('');
        filterDrugs();
    });

    // تتبع تغييرات الكمية
    $('.stock-input').on('input', function() {
        const originalValue = $(this).data('original-value');
        const currentValue = $(this).val();
        const updateBtn = $(this).siblings('.update-stock-btn');

        if (currentValue != originalValue) {
            $(this).addClass('changed');
            updateBtn.show();
        } else {
            $(this).removeClass('changed');
            updateBtn.hide();
        }
    });

    // تحديث الكمية
    $('.update-stock-btn').click(function() {
        const drugId = $(this).data('drug-id');
        const stockInput = $(`.stock-input[data-drug-id="${drugId}"]`);
        const stock = stockInput.val();
        updateStock(drugId, stock, $(this));
    });

    // تغيير حالة التوفر
    $('.availability-toggle').change(function() {
        const drugId = $(this).data('drug-id');
        toggleAvailability(drugId, $(this));
    });

    // حذف الدواء
    $('.remove-drug-btn').click(function() {
        const drugId = $(this).data('drug-id');
        const drugName = $(this).data('drug-name');
        confirmRemoveDrug(drugId, drugName);
    });

    // إضافة دواء جديد
    $('#addDrugBtn').click(function() {
        addDrug();
    });
});

// فلترة الأدوية
function filterDrugs() {
    const searchText = $('#searchInput').val().toLowerCase();
    const selectedCategory = $('#categoryFilter').val();
    const selectedAvailability = $('#availabilityFilter').val();

    let visibleCount = 0;

    $('#drugsTableBody tr').each(function() {
        const row = $(this);
        const drugName = row.data('drug-name');
        const scientificName = row.data('scientific-name');
        const category = row.data('category');
        const availability = row.data('availability');

        let showRow = true;

        // فلترة النص
        if (searchText && !drugName.includes(searchText) && !scientificName.includes(searchText)) {
            showRow = false;
        }

        // فلترة الفئة
        if (selectedCategory && category !== selectedCategory) {
            showRow = false;
        }

        // فلترة التوفر
        if (selectedAvailability && availability !== selectedAvailability) {
            showRow = false;
        }

        if (showRow) {
            row.show();
            visibleCount++;

            // تمييز النص المطابق
            if (searchText) {
                highlightText(row.find('.drug-name'), searchText);
                highlightText(row.find('.scientific-name'), searchText);
            } else {
                removeHighlight(row.find('.drug-name'));
                removeHighlight(row.find('.scientific-name'));
            }
        } else {
            row.hide();
        }
    });

    // تحديث العداد
    $('#drugsCount').text(visibleCount);

    // إظهار/إخفاء الجدول حسب النتائج
    if (visibleCount === 0) {
        $('#drugsTable').hide();
        if ($('#noResults').length === 0) {
            $('#drugsTableContainer').append(`
                <div class="text-center py-5" id="noResults">
                    <i class="fas fa-search fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">لا توجد نتائج</h5>
                    <p class="text-muted">لم يتم العثور على أدوية بالمعايير المحددة</p>
                </div>
            `);
        }
    } else {
        $('#drugsTable').show();
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

function loadAvailableDrugs() {
    $.get('/health-center/drugs-available')
        .done(function(drugs) {
            const select = $('select[name="drug_id"]');
            select.empty().append('<option value="">-- اختر الدواء --</option>');

            drugs.forEach(function(drug) {
                select.append(`<option value="${drug.id}">${drug.name} (${drug.scientific_name || 'غير محدد'})</option>`);
            });
        })
        .fail(function() {
            showToast('خطأ في تحميل الأدوية', 'error');
        });
}

function addDrug() {
    const form = document.getElementById('addDrugForm');
    const formData = new FormData(form);
    const btn = $('#addDrugBtn');
    const spinner = btn.find('.spinner-border');

    // التحقق من صحة البيانات
    if (!formData.get('drug_id')) {
        Swal.fire({
            icon: 'warning',
            title: 'تنبيه',
            text: 'يرجى اختيار الدواء أولاً'
        });
        return;
    }

    btn.prop('disabled', true);
    spinner.show();

    $.ajax({
        url: '/health-center/drugs',
        method: 'POST',
        data: {
            drug_id: formData.get('drug_id'),
            stock: formData.get('stock'),
            availability: formData.has('availability') ? 1 : 0,
            _token: $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if (response.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'تم بنجاح',
                    text: response.message,
                    timer: 2000,
                    showConfirmButton: false
                }).then(() => {
                    $('#addDrugModal').modal('hide');
                    location.reload();
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'خطأ',
                    text: response.message
                });
            }
        },
        error: function(xhr) {
            let message = 'حدث خطأ أثناء إضافة الدواء';
            if (xhr.responseJSON && xhr.responseJSON.message) {
                message = xhr.responseJSON.message;
            }
            Swal.fire({
                icon: 'error',
                title: 'خطأ',
                text: message
            });
        },
        complete: function() {
            btn.prop('disabled', false);
            spinner.hide();
        }
    });
}

function updateStock(drugId, stock, button) {
    const spinner = $('<span class="spinner-border spinner-border-sm me-1"></span>');
    button.prepend(spinner);
    button.prop('disabled', true);

    $.ajax({
        url: `/health-center/drugs/${drugId}`,
        method: 'PUT',
        data: {
            stock: stock,
            _token: $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if (response.success) {
                const stockInput = $(`.stock-input[data-drug-id="${drugId}"]`);
                stockInput.data('original-value', stock);
                stockInput.removeClass('changed');
                button.hide();

                showToast(response.message, 'success');
            }
        },
        error: function() {
            showToast('حدث خطأ أثناء تحديث الكمية', 'error');
        },
        complete: function() {
            spinner.remove();
            button.prop('disabled', false);
        }
    });
}

function toggleAvailability(drugId, toggleElement) {
    // إضافة تأثير تحميل
    toggleElement.prop('disabled', true);

    $.ajax({
        url: `/health-center/drugs/${drugId}/toggle`,
        method: 'POST',
        data: {
            _token: $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if (response.success) {
                const textElement = toggleElement.siblings('.form-check-label').find('.availability-text');
                textElement.text(response.availability ? 'متاح' : 'غير متاح');

                // تحديث data attribute للفلترة
                const row = toggleElement.closest('tr');
                row.data('availability', response.availability ? 'available' : 'unavailable');

                showToast(response.message, 'success');
            }
        },
        error: function() {
            // إرجاع التوجل لحالته الأصلية في حالة الخطأ
            toggleElement.prop('checked', !toggleElement.is(':checked'));
            showToast('حدث خطأ أثناء تحديث حالة التوفر', 'error');
        },
        complete: function() {
            toggleElement.prop('disabled', false);
        }
    });
}

function confirmRemoveDrug(drugId, drugName) {
    Swal.fire({
        title: 'تأكيد الحذف',
        text: `هل أنت متأكد من حذف الدواء "${drugName}" من مركزك؟`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'نعم، احذف',
        cancelButtonText: 'إلغاء',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            removeDrug(drugId, drugName);
        }
    });
}

function removeDrug(drugId, drugName) {
    // إظهار تحميل
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
        url: `/health-center/drugs/${drugId}`,
        method: 'DELETE',
        data: {
            _token: $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if (response.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'تم الحذف',
                    text: response.message,
                    timer: 2000,
                    showConfirmButton: false
                }).then(() => {
                    // إزالة الصف من الجدول
                    $(`button[data-drug-id="${drugId}"]`).closest('tr').fadeOut(400, function() {
                        $(this).remove();
                        filterDrugs(); // إعادة حساب العداد
                    });
                });
            }
        },
        error: function(xhr) {
            let message = 'حدث خطأ أثناء حذف الدواء';
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

function showToast(message, type = 'info') {
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
    });

    Toast.fire({
        icon: type,
        title: message
    });
}
</script>
@endsection

@extends('layouts.app')

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
            <form method="GET" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">بحث بالاسم</label>
                    <input type="text" class="form-control" name="search"
                           value="{{ request('search') }}"
                           placeholder="اسم الدواء أو الاسم العلمي">
                </div>
                <div class="col-md-3">
                    <label class="form-label">الفئة</label>
                    <select class="form-select" name="category">
                        <option value="">جميع الفئات</option>
                        @foreach($categories as $category)
                            <option value="{{ $category }}"
                                    {{ request('category') == $category ? 'selected' : '' }}>
                                {{ $category }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">التوفر</label>
                    <select class="form-select" name="availability">
                        <option value="">الكل</option>
                        <option value="1" {{ request('availability') == '1' ? 'selected' : '' }}>متاح</option>
                        <option value="0" {{ request('availability') == '0' ? 'selected' : '' }}>غير متاح</option>
                    </select>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-search me-1"></i>بحث
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- قائمة الأدوية -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">الأدوية المتاحة ({{ $drugs->total() }} دواء)</h5>
            <small class="text-muted">الأدوية الموجودة في مركزك الصحي</small>
        </div>
        <div class="card-body p-0">
            @if($drugs->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>اسم الدواء</th>
                                <th>الاسم العلمي</th>
                                <th>الفئة</th>
                                <th>الشركة المصنعة</th>
                                <th>الكمية</th>
                                <th>السعر</th>
                                <th>التوفر</th>
                                <th>الحالة</th>
                                <th>الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($drugs as $drug)
                                @php
                                    $centerDrug = $drug->healthCenters->first();
                                @endphp
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <strong>{{ $drug->name }}</strong>
                                            @if($drug->is_government_approved)
                                                <span class="badge bg-success ms-2" title="معتمد حكومياً">
                                                    <i class="fas fa-certificate"></i>
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="text-muted">{{ $drug->scientific_name ?: 'غير محدد' }}</td>
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
                                                   min="0">
                                            <button class="btn btn-outline-primary update-stock-btn"
                                                    data-drug-id="{{ $drug->id }}" type="button">
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
                                        @if($centerDrug->pivot->stock > 0)
                                            <span class="badge bg-success">متوفر</span>
                                        @else
                                            <span class="badge bg-danger">نفدت الكمية</span>
                                        @endif
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
                <div class="text-center py-5">
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
                        <input class="form-check-input" type="checkbox" name="availability" checked>
                        <label class="form-check-label">متاح للمرضى</label>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                <button type="button" class="btn btn-success" onclick="addDrug()">إضافة الدواء</button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // تحميل الأدوية المتاحة عند فتح المودال
    $('#addDrugModal').on('show.bs.modal', function() {
        loadAvailableDrugs();
    });

    // تحديث الكمية
    $('.update-stock-btn').click(function() {
        const drugId = $(this).data('drug-id');
        const stock = $(`.stock-input[data-drug-id="${drugId}"]`).val();
        updateStock(drugId, stock);
    });

    // تغيير حالة التوفر
    $('.availability-toggle').change(function() {
        const drugId = $(this).data('drug-id');
        toggleAvailability(drugId, $(this));
    });

    // حذف الدواء
    $('.remove-drug-btn').click(function() {
        const drugId = $(this).data('drug-id');
        if (confirm('هل أنت متأكد من حذف هذا الدواء من مركزك؟')) {
            removeDrug(drugId);
        }
    });
});

function loadAvailableDrugs() {
    $.get('/health-center/drugs/available')
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

    $.ajax({
        url: '/health-center/drugs',
        method: 'POST',
        data: {
            drug_id: formData.get('drug_id'),
            stock: formData.get('stock'),
            availability: formData.has('availability'),
            _token: $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if (response.success) {
                showToast(response.message, 'success');
                $('#addDrugModal').modal('hide');
                location.reload();
            } else {
                showToast(response.message, 'error');
            }
        },
        error: function() {
            showToast('حدث خطأ أثناء إضافة الدواء', 'error');
        }
    });
}

function updateStock(drugId, stock) {
    $.ajax({
        url: `/health-center/drugs/${drugId}`,
        method: 'PUT',
        data: {
            stock: stock,
            _token: $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if (response.success) {
                showToast(response.message, 'success');
            }
        },
        error: function() {
            showToast('حدث خطأ أثناء تحديث الكمية', 'error');
        }
    });
}

function toggleAvailability(drugId, toggleElement) {
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
                showToast(response.message, 'success');
            }
        },
        error: function() {
            // إرجاع التوجل لحالته الأصلية في حالة الخطأ
            toggleElement.prop('checked', !toggleElement.is(':checked'));
            showToast('حدث خطأ أثناء تحديث حالة التوفر', 'error');
        }
    });
}

function removeDrug(drugId) {
    $.ajax({
        url: `/health-center/drugs/${drugId}`,
        method: 'DELETE',
        data: {
            _token: $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if (response.success) {
                showToast(response.message, 'success');
                location.reload();
            }
        },
        error: function() {
            showToast('حدث خطأ أثناء حذف الدواء', 'error');
        }
    });
}
</script>
@endpush

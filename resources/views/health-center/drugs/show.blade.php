@extends('layouts.health-center.master')

@section('title', 'تفاصيل الدواء - ' . $drug->name)

@section('content')
<div class="container-fluid py-4">
    <!-- عنوان الصفحة -->
    <div class="row mb-4">
        <div class="col-md-6">
            <h2 class="text-primary">{{ $drug->name }}</h2>
            <p class="text-muted">تفاصيل الدواء</p>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ route('health-center.drugs.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-right me-1"></i>العودة لقائمة الأدوية
            </a>
        </div>
    </div>

    <div class="row">
        <!-- معلومات الدواء الأساسية -->
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>معلومات الدواء</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">الاسم التجاري</label>
                            <p class="fw-bold">{{ $drug->name }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">الاسم العلمي</label>
                            <p class="fw-bold">{{ $drug->scientific_name ?: 'غير محدد' }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">الفئة</label>
                            <p>
                                @if($drug->category)
                                    <span class="badge bg-secondary">{{ $drug->category }}</span>
                                @else
                                    <span class="text-muted">غير محدد</span>
                                @endif
                            </p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">الشكل الدوائي</label>
                            <p>{{ $drug->dosage_form ?: 'غير محدد' }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">الشركة المصنعة</label>
                            <p>{{ $drug->manufacturer ?: 'غير محدد' }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">السعر</label>
                            <p>
                                @if($drug->price)
                                    <span class="fw-bold text-success">{{ number_format($drug->price, 2) }} ج.م</span>
                                @else
                                    <span class="text-muted">غير محدد</span>
                                @endif
                            </p>
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label text-muted">الوصف</label>
                            <p>{{ $drug->description ?: 'لا يوجد وصف متاح' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- حالة الاعتماد والتأمين -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-certificate me-2"></i>حالة الاعتماد والتأمين</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label text-muted">الاعتماد الحكومي</label>
                            <div>
                                @if($drug->is_government_approved)
                                    <span class="badge bg-success fs-6">
                                        <i class="fas fa-check-circle me-1"></i>معتمد حكومياً
                                    </span>
                                @else
                                    <span class="badge bg-warning fs-6">
                                        <i class="fas fa-clock me-1"></i>في انتظار الاعتماد
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label text-muted">التغطية التأمينية</label>
                            <div>
                                @if($drug->insurance_covered)
                                    <span class="badge bg-info fs-6">
                                        <i class="fas fa-shield-alt me-1"></i>مغطى بالتأمين
                                    </span>
                                @else
                                    <span class="badge bg-secondary fs-6">
                                        <i class="fas fa-times me-1"></i>غير مغطى
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label text-muted">حالة الموافقة</label>
                            <div>
                                @switch($drug->approval_status)
                                    @case('approved')
                                        <span class="badge bg-success fs-6">
                                            <i class="fas fa-check me-1"></i>موافق عليه
                                        </span>
                                        @break
                                    @case('pending')
                                        <span class="badge bg-warning fs-6">
                                            <i class="fas fa-hourglass-half me-1"></i>في الانتظار
                                        </span>
                                        @break
                                    @case('rejected')
                                        <span class="badge bg-danger fs-6">
                                            <i class="fas fa-times me-1"></i>مرفوض
                                        </span>
                                        @break
                                @endswitch
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- إدارة المخزون -->
        <div class="col-md-4">
            @php
                $centerDrug = $drug->healthCenters->first();
            @endphp

            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-warehouse me-2"></i>إدارة المخزون</h5>
                </div>
                <div class="card-body">
                    <!-- حالة التوفر -->
                    <div class="mb-4">
                        <label class="form-label">حالة التوفر</label>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox"
                                   id="availabilityToggle"
                                   {{ $centerDrug->pivot->availability ? 'checked' : '' }}>
                            <label class="form-check-label" for="availabilityToggle">
                                <span id="availabilityText">
                                    {{ $centerDrug->pivot->availability ? 'متاح للمرضى' : 'غير متاح' }}
                                </span>
                            </label>
                        </div>
                    </div>

                    <!-- الكمية المتاحة -->
                    <div class="mb-4">
                        <label class="form-label">الكمية المتاحة</label>
                        <div class="input-group">
                            <input type="number" class="form-control" id="stockInput"
                                   value="{{ $centerDrug->pivot->stock }}" min="0">
                            <button class="btn btn-outline-primary" type="button" onclick="updateStock()">
                                <i class="fas fa-save"></i>
                            </button>
                        </div>
                        <small class="text-muted">أدخل الكمية الجديدة واضغط حفظ</small>
                    </div>

                    <!-- حالة المخزون -->
                    <div class="mb-3">
                        <label class="form-label">حالة المخزون</label>
                        <div>
                            @if($centerDrug->pivot->stock > 10)
                                <span class="badge bg-success fs-6">
                                    <i class="fas fa-check-circle me-1"></i>متوفر
                                </span>
                            @elseif($centerDrug->pivot->stock > 0)
                                <span class="badge bg-warning fs-6">
                                    <i class="fas fa-exclamation-triangle me-1"></i>كمية قليلة
                                </span>
                            @else
                                <span class="badge bg-danger fs-6">
                                    <i class="fas fa-times-circle me-1"></i>نفدت الكمية
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- إجراءات سريعة -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-tools me-2"></i>إجراءات سريعة</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <button type="button" class="btn btn-success" onclick="quickStock(50)">
                            <i class="fas fa-plus me-1"></i>إضافة 50 وحدة
                        </button>
                        <button type="button" class="btn btn-info" onclick="quickStock(100)">
                            <i class="fas fa-plus me-1"></i>إضافة 100 وحدة
                        </button>
                        <button type="button" class="btn btn-warning" onclick="setStock(0)">
                            <i class="fas fa-minus me-1"></i>تصفير المخزون
                        </button>
                        <hr>
                        <button type="button" class="btn btn-danger" onclick="removeDrug()">
                            <i class="fas fa-trash me-1"></i>حذف من المركز
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // تغيير حالة التوفر
    $('#availabilityToggle').change(function() {
        toggleAvailability();
    });
});

function updateStock() {
    const stock = $('#stockInput').val();

    $.ajax({
        url: `/health-center/drugs/{{ $drug->id }}`,
        method: 'PUT',
        data: {
            stock: stock,
            _token: $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if (response.success) {
                showToast(response.message, 'success');
                updateStockStatus(stock);
            }
        },
        error: function() {
            showToast('حدث خطأ أثناء تحديث الكمية', 'error');
        }
    });
}

function toggleAvailability() {
    $.ajax({
        url: `/health-center/drugs/{{ $drug->id }}/toggle`,
        method: 'POST',
        data: {
            _token: $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if (response.success) {
                $('#availabilityText').text(response.availability ? 'متاح للمرضى' : 'غير متاح');
                showToast(response.message, 'success');
            }
        },
        error: function() {
            // إرجاع التوجل لحالته الأصلية
            $('#availabilityToggle').prop('checked', !$('#availabilityToggle').is(':checked'));
            showToast('حدث خطأ أثناء تحديث حالة التوفر', 'error');
        }
    });
}

function quickStock(amount) {
    const currentStock = parseInt($('#stockInput').val()) || 0;
    setStock(currentStock + amount);
}

function setStock(amount) {
    $('#stockInput').val(amount);
    updateStock();
}

function removeDrug() {
    if (confirm('هل أنت متأكد من حذف هذا الدواء من مركزك؟\nسيتم فقدان جميع بيانات المخزون المرتبطة به.')) {
        $.ajax({
            url: `/health-center/drugs/{{ $drug->id }}`,
            method: 'DELETE',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    showToast(response.message, 'success');
                    setTimeout(() => {
                        window.location.href = '{{ route("health-center.drugs.index") }}';
                    }, 1500);
                }
            },
            error: function() {
                showToast('حدث خطأ أثناء حذف الدواء', 'error');
            }
        });
    }
}

function updateStockStatus(stock) {
    // يمكن إضافة منطق لتحديث حالة المخزون بناءً على الكمية الجديدة
    location.reload();
}
</script>
@endsection

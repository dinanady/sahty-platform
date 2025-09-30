@extends('layouts.health-center.master')

@section('title', 'الأدوية المرفوضة - المركز الصحي')

@section('content')
<div class="container-fluid py-4">
    <!-- عنوان الصفحة -->
    <div class="row mb-4">
        <div class="col-md-6">
            <h2 class="text-danger">الأدوية المرفوضة</h2>
            <p class="text-muted">الأدوية التي رفضتها الجهات الحكومية</p>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ route('health-center.drugs.pending') }}" class="btn btn-outline-warning me-2">
                <i class="fas fa-hourglass-half me-1"></i>الأدوية المعلقة
            </a>
            <a href="{{ route('health-center.drugs.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-right me-1"></i>العودة لقائمة الأدوية
            </a>
        </div>
    </div>

    <!-- إحصائيات سريعة -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card bg-danger text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="card-title">{{ $rejectedDrugs->total() }}</h4>
                            <p class="card-text mb-0">أدوية مرفوضة</p>
                        </div>
                        <i class="fas fa-times-circle fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="card-title">{{ $rejectedDrugs->where('updated_at', '>=', now()->subDays(7))->count() }}</h4>
                            <p class="card-text mb-0">مرفوضة هذا الأسبوع</p>
                        </div>
                        <i class="fas fa-calendar-week fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="card-title">{{ $rejectedDrugs->whereNotNull('rejection_reason')->count() }}</h4>
                            <p class="card-text mb-0">بأسباب واضحة</p>
                        </div>
                        <i class="fas fa-comment-alt fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- قائمة الأدوية المرفوضة -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">الأدوية المرفوضة</h5>
            <div>
                <small class="text-muted">يمكنك تعديل البيانات وإعادة الإرسال</small>
            </div>
        </div>
        <div class="card-body p-0">
            @if($rejectedDrugs->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>اسم الدواء</th>
                                <th>الاسم العلمي</th>
                                <th>الفئة</th>
                                <th>تاريخ الرفض</th>
                                <th>سبب الرفض</th>
                                <th>الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($rejectedDrugs as $drug)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <strong>{{ $drug->name }}</strong>
                                            <span class="badge bg-danger ms-2">
                                                <i class="fas fa-times"></i>
                                            </span>
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
                                    <td>{{ $drug->updated_at->format('Y/m/d H:i') }}</td>
                                    <td>
                                        @if($drug->rejection_reason)
                                            <button type="button" class="btn btn-sm btn-outline-info"
                                                    onclick="showRejectionReason('{{ addslashes($drug->rejection_reason) }}')">
                                                <i class="fas fa-eye me-1"></i>عرض السبب
                                            </button>
                                        @else
                                            <span class="text-muted">غير محدد</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('health-center.drugs.show', $drug->id) }}"
                                               class="btn btn-outline-info" title="عرض التفاصيل">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <button type="button" class="btn btn-outline-danger"
                                                    onclick="deletePermanently({{ $drug->id }})"
                                                    title="حذف نهائي">
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
                    <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                    <h5 class="text-muted">لا توجد أدوية مرفوضة</h5>
                    <p class="text-muted">جميع الأدوية المرسلة تمت الموافقة عليها أو لا تزال في الانتظار</p>
                    <a href="{{ route('health-center.drugs.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-1"></i>إضافة دواء جديد
                    </a>
                </div>
            @endif
        </div>

        @if($rejectedDrugs->hasPages())
            <div class="card-footer">
                {{ $rejectedDrugs->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Modal سبب الرفض -->
<div class="modal fade" id="rejectionReasonModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title"><i class="fas fa-times-circle me-2"></i>سبب الرفض</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="rejectionReasonContent" class="alert alert-danger">
                    <!-- سيتم ملء المحتوى بـ JavaScript -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
function showRejectionReason(reason) {
    $('#rejectionReasonContent').html('<i class="fas fa-exclamation-triangle me-2"></i>' + reason);
    $('#rejectionReasonModal').modal('show');
}

function deletePermanently(drugId) {
    if (confirm('هل أنت متأكد من الحذف النهائي لهذا الدواء؟\nلن يمكن التراجع عن هذا الإجراء.')) {
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
                showToast('حدث خطأ أثناء الحذف', 'error');
            }
        });
    }
}
</script>
@endpush

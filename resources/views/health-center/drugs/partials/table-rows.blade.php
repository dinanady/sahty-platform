{{-- resources/views/health-center/drugs/partials/table-rows.blade.php --}}
@forelse($drugs as $drug)
    @php
        $centerDrug = $drug->healthCenters->first();
    @endphp
    <tr data-drug-id="{{ $drug->id }}">
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
                @can('hc-edit-drugs')
                <button class="btn btn-outline-primary update-stock-btn"
                        data-drug-id="{{ $drug->id }}"
                        type="button"
                        style="display: none;">
                    <i class="fas fa-save"></i>
                </button>
                @endcan
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
                @can('hc-delete-drugs')
                <button type="button"
                        class="btn btn-outline-danger remove-drug-btn"
                        data-drug-id="{{ $drug->id }}"
                        data-drug-name="{{ $drug->name }}"
                        title="حذف من المركز">
                    <i class="fas fa-trash"></i>
                </button>
                @endcan
            </div>
        </td>
    </tr>
@empty
    <tr id="noDrugsRow">
        <td colspan="8" class="text-center py-5">
            <div class="text-muted">
                <i class="fas fa-pills fa-3x mb-3"></i>
                <h5 class="text-muted">
                    @if(request()->hasAny(['search', 'category', 'availability']))
                        لا توجد أدوية تطابق معايير البحث
                    @else
                        لم تقم بإضافة أي أدوية بعد
                    @endif
                </h5>
                <p class="text-muted">
                    @if(request()->hasAny(['search', 'category', 'availability']))
                        جرب تغيير معايير البحث أو إزالة الفلاتر
                    @else
                        ابدأ بإضافة أدوية لمركزك الصحي
                    @endif
                </p>
                @if(!request()->hasAny(['search', 'category', 'availability']))
                    <div class="mt-3">
                        @can('hc-submit-new-drug', $post)
                        <a href="{{ route('health-center.drugs.create') }}" class="btn btn-success me-2">
                            <i class="fas fa-plus me-1"></i>إضافة دواء جديد
                        </a>
                        @endcan
                        @can('hc-create-drugs')
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addDrugModal">
                            <i class="fas fa-list-plus me-1"></i>إضافة دواء موجود
                        </button>
                        @endcan
                    </div>
                @else
                    <button type="button" class="btn btn-outline-secondary mt-3" onclick="document.getElementById('clearFilters').click()">
                        <i class="fas fa-times me-1"></i>مسح الفلاتر
                    </button>
                @endif
            </div>
        </td>
    </tr>
@endforelse

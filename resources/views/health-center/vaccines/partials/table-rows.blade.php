{{-- resources/views/health-center/vaccines/partials/table-rows.blade.php --}}
@forelse($centerVaccines as $vaccine)
    <tr data-vaccine-id="{{ $vaccine->id }}">
        <td class="text-center">{{ $vaccine->id }}</td>
        <td>
            <strong>{{ $vaccine->name }}</strong>
            <br>
            <small class="text-muted">{{ Str::limit($vaccine->description, 50) }}</small>
        </td>
        <td class="text-center">
            <span class="badge bg-info">
                {{ $vaccine->age_months_min }} - {{ $vaccine->age_months_max }} شهر
            </span>
        </td>
        <td class="text-center">
            <span class="badge bg-secondary">
                {{ $vaccine->doses_required }} جرعة
            </span>
        </td>
        <td class="text-center">
            @if($vaccine->interval_days)
                <span class="badge bg-warning text-dark">
                    {{ $vaccine->interval_days }} يوم
                </span>
            @else
                <span class="text-muted">-</span>
            @endif
        </td>
        <td class="text-center">
            <div class="form-check form-switch d-inline-block">
                <input class="form-check-input availability-toggle"
                       type="checkbox"
                       data-vaccine-id="{{ $vaccine->id }}"
                       {{ $vaccine->pivot->availability ? 'checked' : '' }}>
                <label class="form-check-label availability-label">
                    {{ $vaccine->pivot->availability ? 'متوفر' : 'غير متوفر' }}
                </label>
            </div>
        </td>
        <td class="text-center">
            {{ $vaccine->pivot->created_at
                ? $vaccine->pivot->created_at->format('Y-m-d')
                : '-' }}
        </td>
        <td class="text-center">
            <div class="btn-group btn-group-sm">
                <button type="button" class="btn btn-outline-info"
                        onclick="showVaccineDetails({{ $vaccine->id }})" title="عرض التفاصيل">
                    <i class="fas fa-eye"></i>
                </button>
                <button type="button" class="btn btn-outline-danger"
                        onclick="removeVaccine({{ $vaccine->id }})" title="إزالة من المركز">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </td>
    </tr>
@empty
    <tr id="noVaccinesRow">
        <td colspan="8" class="text-center py-5">
            <div class="text-muted">
                <i class="fas fa-inbox fa-3x mb-3"></i>
                <h5 class="text-muted">
                    @if(request()->hasAny(['search', 'age', 'availability']))
                        لا توجد لقاحات تطابق معايير البحث
                    @else
                        لم تقم بإضافة أي لقاحات بعد
                    @endif
                </h5>
                <p class="text-muted">
                    @if(request()->hasAny(['search', 'age', 'availability']))
                        جرب تغيير معايير البحث أو إزالة الفلاتر
                    @else
                        ابدأ بإضافة لقاحات لمركزك الصحي
                    @endif
                </p>
                @if(!request()->hasAny(['search', 'age', 'availability']))
                    <button type="button" class="btn btn-primary mt-3" data-bs-toggle="modal" data-bs-target="#addVaccineModal">
                        <i class="fas fa-plus me-1"></i>إضافة أول لقاح
                    </button>
                @else
                    <button type="button" class="btn btn-outline-secondary mt-3" onclick="document.getElementById('clearFilters').click()">
                        <i class="fas fa-times me-1"></i>مسح الفلاتر
                    </button>
                @endif
            </div>
        </td>
    </tr>
@endforelse

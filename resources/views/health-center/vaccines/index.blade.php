@extends('layouts.master')

@section('title', 'إدارة اللقاحات')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col">
                            <h3 class="card-title mb-0">
                                <i class="fas fa-syringe"></i> إدارة اللقاحات - {{ $healthCenter->name }}
                            </h3>
                        </div>
                        <div class="col-auto">
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addVaccineModal">
                                <i class="fas fa-plus"></i> إضافة لقاح
                            </button>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-hover" id="vaccinesTable">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>اسم اللقاح</th>
                                    <th>العمر المناسب</th>
                                    <th>عدد الجرعات</th>
                                    <th>الفترة بين الجرعات</th>
                                    <th>الحالة</th>
                                    <th>تاريخ الإضافة</th>
                                    <th>الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($centerVaccines as $vaccine)
                                    <tr data-vaccine-id="{{ $vaccine->id }}">
                                        <td>{{ $vaccine->id }}</td>
                                        <td>
                                            <strong>{{ $vaccine->name }}</strong>
                                            <br>
                                            <small class="text-muted">{{ Str::limit($vaccine->description, 50) }}</small>
                                        </td>
                                        <td>
                                            <span class="badge bg-info">
                                                {{ $vaccine->age_months_min }} - {{ $vaccine->age_months_max }} شهر
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary">
                                                {{ $vaccine->doses_required }} جرعة
                                            </span>
                                        </td>
                                        <td>
                                            @if($vaccine->interval_days)
                                                <span class="badge bg-warning text-dark">
                                                    {{ $vaccine->interval_days }} يوم
                                                </span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="form-check form-switch">
                                                <input class="form-check-input availability-toggle"
                                                       type="checkbox"
                                                       data-vaccine-id="{{ $vaccine->id }}"
                                                       {{ $vaccine->pivot->availability ? 'checked' : '' }}>
                                                <label class="form-check-label availability-label">
                                                    {{ $vaccine->pivot->availability ? 'متوفر' : 'غير متوفر' }}
                                                </label>
                                            </div>
                                        </td>
                                        <td>
                                            {{ $vaccine->pivot->created_at
                                                ? $vaccine->pivot->created_at->format('Y-m-d')
                                                : '-' }}
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm" role="group">
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
                                        <td colspan="8" class="text-center py-4">
                                            <div class="text-muted">
                                                <i class="fas fa-inbox fa-3x mb-3"></i>
                                                <p>لم تقم بإضافة أي لقاحات بعد</p>
                                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addVaccineModal">
                                                    إضافة أول لقاح
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal إضافة لقاح -->
<div class="modal fade" id="addVaccineModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">إضافة لقاح جديد</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="addVaccineForm">
                    <div class="row">
                        <div class="col-12 mb-3">
                            <label for="vaccine_search" class="form-label">البحث عن اللقاحات المتاحة</label>
                            <input type="text" class="form-control" id="vaccine_search" placeholder="ابحث عن اللقاح...">
                        </div>
                    </div>

                    <div class="row" id="availableVaccines">
                        @foreach($allVaccines as $vaccine)
                            @if(!$centerVaccines->contains($vaccine->id))
                                <div class="col-md-6 mb-3 vaccine-card" data-vaccine-name="{{ strtolower($vaccine->name) }}">
                                    <div class="card h-100">
                                        <div class="card-body">
                                            <div class="form-check">
                                                <input class="form-check-input vaccine-checkbox" type="checkbox"
                                                       value="{{ $vaccine->id }}" id="vaccine_{{ $vaccine->id }}">
                                                <label class="form-check-label" for="vaccine_{{ $vaccine->id }}">
                                                    <strong>{{ $vaccine->name }}</strong>
                                                </label>
                                            </div>
                                            <small class="text-muted d-block mt-1">
                                                العمر: {{ $vaccine->age_months_min }}-{{ $vaccine->age_months_max }} شهر
                                            </small>
                                            <small class="text-muted d-block">
                                                عدد الجرعات: {{ $vaccine->doses_required }}
                                            </small>
                                            <p class="card-text mt-2">
                                                <small>{{ Str::limit($vaccine->description, 80) }}</small>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>

                    <div class="row mt-3" id="noAvailableVaccines" style="display: none;">
                        <div class="col-12 text-center">
                            <div class="text-muted">
                                <i class="fas fa-check-circle fa-3x mb-3"></i>
                                <p>تم إضافة جميع اللقاحات المتاحة لمركزكم</p>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                <button type="button" class="btn btn-primary" onclick="addSelectedVaccines()">
                    <i class="fas fa-plus"></i> إضافة اللقاحات المختارة
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal عرض تفاصيل اللقاح -->
<div class="modal fade" id="vaccineDetailsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">تفاصيل اللقاح</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="vaccineDetailsContent">
                <!-- سيتم ملؤها بـ JavaScript -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
.vaccine-card {
    transition: all 0.3s ease;
}
.vaccine-card:hover {
    transform: translateY(-2px);
}
.availability-toggle:checked + .availability-label {
    color: #28a745;
    font-weight: bold;
}
.availability-toggle:not(:checked) + .availability-label {
    color: #dc3545;
    font-weight: bold;
}
.form-check-input:checked {
    background-color: #0d6efd;
    border-color: #0d6efd;
}
.card {
    transition: box-shadow 0.3s ease;
}
.card:hover {
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
}
</style>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // البحث في اللقاحات المتاحة
    const searchInput = document.getElementById('vaccine_search');
    const vaccineCards = document.querySelectorAll('.vaccine-card');

    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            let visibleCount = 0;

            vaccineCards.forEach(card => {
                const vaccineName = card.dataset.vaccineName;
                if (vaccineName.includes(searchTerm)) {
                    card.style.display = 'block';
                    visibleCount++;
                } else {
                    card.style.display = 'none';
                }
            });

            // عرض رسالة عدم وجود نتائج
            const noResults = document.getElementById('noSearchResults');
            if (visibleCount === 0 && searchTerm !== '') {
                if (!noResults) {
                    const noResultsDiv = document.createElement('div');
                    noResultsDiv.id = 'noSearchResults';
                    noResultsDiv.className = 'col-12 text-center text-muted';
                    noResultsDiv.innerHTML = '<p>لا توجد لقاحات تطابق البحث</p>';
                    document.getElementById('availableVaccines').appendChild(noResultsDiv);
                }
            } else if (noResults) {
                noResults.remove();
            }
        });
    }

    // تبديل حالة توفر اللقاح
    const availabilityToggles = document.querySelectorAll('.availability-toggle');
    availabilityToggles.forEach(toggle => {
        toggle.addEventListener('change', function() {
            const vaccineId = this.dataset.vaccineId;
            const availability = this.checked;

            updateVaccineAvailability(vaccineId, availability, this);
        });
    });

    // التحقق من وجود لقاحات متاحة للإضافة
    checkAvailableVaccines();
});

// إضافة اللقاحات المختارة
function addSelectedVaccines() {
    const selectedVaccines = document.querySelectorAll('.vaccine-checkbox:checked');

    if (selectedVaccines.length === 0) {
        Swal.fire({
            icon: 'warning',
            title: 'تنبيه',
            text: 'يرجى اختيار لقاح واحد على الأقل'
        });
        return;
    }

    const promises = Array.from(selectedVaccines).map(checkbox => {
        return addVaccineToCenter(checkbox.value);
    });

    Promise.all(promises).then(results => {
        const successCount = results.filter(r => r.success).length;
        const failCount = results.length - successCount;

        let message = `تم إضافة ${successCount} لقاح بنجاح`;
        if (failCount > 0) {
            message += ` و فشل في إضافة ${failCount} لقاح`;
        }

        Swal.fire({
            icon: successCount > 0 ? 'success' : 'error',
            title: successCount > 0 ? 'تم بنجاح' : 'فشل',
            text: message
        }).then(() => {
            if (successCount > 0) {
                location.reload();
            }
        });
    });
}

// إضافة لقاح واحد للمركز
async function addVaccineToCenter(vaccineId) {
    try {
        const response = await fetch('/health-center/vaccines', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                vaccine_id: vaccineId,
                availability: true
            })
        });

        const data = await response.json();
        return { success: response.ok, data };
    } catch (error) {
        console.error('Error:', error);
        return { success: false, error };
    }
}

// تحديث حالة توفر اللقاح
async function updateVaccineAvailability(vaccineId, availability, toggleElement) {
    const originalChecked = toggleElement.checked;
    const label = toggleElement.nextElementSibling;

    try {
        const response = await fetch(`/health-center/vaccines/${vaccineId}/availability`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ availability })
        });

        const data = await response.json();

        if (response.ok) {
            label.textContent = availability ? 'متوفر' : 'غير متوفر';

            Swal.fire({
                icon: 'success',
                title: 'تم التحديث',
                text: data.message,
                timer: 2000,
                showConfirmButton: false
            });
        } else {
            // إرجاع الحالة الأصلية في حالة الفشل
            toggleElement.checked = !originalChecked;

            Swal.fire({
                icon: 'error',
                title: 'خطأ',
                text: data.error || 'فشل في تحديث حالة اللقاح'
            });
        }
    } catch (error) {
        // إرجاع الحالة الأصلية في حالة الخطأ
        toggleElement.checked = !originalChecked;

        Swal.fire({
            icon: 'error',
            title: 'خطأ',
            text: 'حدث خطأ أثناء تحديث حالة اللقاح'
        });
    }
}

// إزالة لقاح من المركز
function removeVaccine(vaccineId) {
    Swal.fire({
        title: 'تأكيد الإزالة',
        text: 'هل أنت متأكد من إزالة هذا اللقاح من مركزكم؟',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'نعم، قم بالإزالة',
        cancelButtonText: 'إلغاء'
    }).then(async (result) => {
        if (result.isConfirmed) {
            try {
                const response = await fetch(`/health-center/vaccines/${vaccineId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });

                const data = await response.json();

                if (response.ok) {
                    // إزالة الصف من الجدول
                    const row = document.querySelector(`tr[data-vaccine-id="${vaccineId}"]`);
                    row.remove();

                    // التحقق من وجود صفوف أخرى
                    const remainingRows = document.querySelectorAll('#vaccinesTable tbody tr[data-vaccine-id]');
                    if (remainingRows.length === 0) {
                        const tbody = document.querySelector('#vaccinesTable tbody');
                        tbody.innerHTML = `
                            <tr id="noVaccinesRow">
                                <td colspan="8" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="fas fa-inbox fa-3x mb-3"></i>
                                        <p>لم تقم بإضافة أي لقاحات بعد</p>
                                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addVaccineModal">
                                            إضافة أول لقاح
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        `;
                    }

                    Swal.fire({
                        icon: 'success',
                        title: 'تم بنجاح',
                        text: data.message
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'خطأ',
                        text: data.error || 'فشل في إزالة اللقاح'
                    });
                }
            } catch (error) {
                Swal.fire({
                    icon: 'error',
                    title: 'خطأ',
                    text: 'حدث خطأ أثناء إزالة اللقاح'
                });
            }
        }
    });
}

// عرض تفاصيل اللقاح
async function showVaccineDetails(vaccineId) {
    try {
        const response = await fetch(`/health-center/vaccines/${vaccineId}`);
        const vaccine = await response.json();

        if (response.ok) {
            const content = `
                <div class="row">
                    <div class="col-md-6">
                        <h6><i class="fas fa-syringe"></i> معلومات أساسية</h6>
                        <table class="table table-sm">
                            <tr><td><strong>اسم اللقاح:</strong></td><td>${vaccine.name}</td></tr>
                            <tr><td><strong>العمر المناسب:</strong></td><td>${vaccine.age_months_min} - ${vaccine.age_months_max} شهر</td></tr>
                            <tr><td><strong>عدد الجرعات:</strong></td><td>${vaccine.doses_required}</td></tr>
                            <tr><td><strong>الفترة بين الجرعات:</strong></td><td>${vaccine.interval_days || 'غير محدد'} يوم</td></tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h6><i class="fas fa-info-circle"></i> الوصف</h6>
                        <p class="text-muted">${vaccine.description}</p>
                    </div>
                </div>

                ${vaccine.side_effects ? `
                <div class="row mt-3">
                    <div class="col-12">
                        <h6><i class="fas fa-exclamation-triangle text-warning"></i> الآثار الجانبية</h6>
                        <div class="alert alert-warning">
                            ${vaccine.side_effects}
                        </div>
                    </div>
                </div>
                ` : ''}

                ${vaccine.precautions ? `
                <div class="row mt-3">
                    <div class="col-12">
                        <h6><i class="fas fa-shield-alt text-info"></i> الاحتياطات والتحذيرات</h6>
                        <div class="alert alert-info">
                            ${vaccine.precautions}
                        </div>
                    </div>
                </div>
                ` : ''}
            `;

            document.getElementById('vaccineDetailsContent').innerHTML = content;

            const modal = new bootstrap.Modal(document.getElementById('vaccineDetailsModal'));
            modal.show();
        }
    } catch (error) {
        Swal.fire({
            icon: 'error',
            title: 'خطأ',
            text: 'فشل في جلب تفاصيل اللقاح'
        });
    }
}

// التحقق من وجود لقاحات متاحة للإضافة
function checkAvailableVaccines() {
    const vaccineCards = document.querySelectorAll('.vaccine-card');
    const noAvailableDiv = document.getElementById('noAvailableVaccines');

    if (vaccineCards.length === 0) {
        document.getElementById('availableVaccines').style.display = 'none';
        noAvailableDiv.style.display = 'block';
    }
}
</script>
@endsection

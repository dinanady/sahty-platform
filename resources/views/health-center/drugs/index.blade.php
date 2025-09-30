@extends('layouts.health-center.master')

@section('title', 'إدارة الأدوية - المركز الصحي')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-md-6">
            <h2 class="mb-0 text-primary">
                <i class="fas fa-pills me-2"></i>إدارة الأدوية
            </h2>
            <p class="text-muted mb-0">عرض وإدارة أدوية المركز الصحي</p>
        </div>
        <div class="col-md-6 text-end">
            @can('hc-submit-new-drug')
            <a href="{{ route('health-center.drugs.create') }}" class="btn btn-success btn-lg shadow-sm me-2">
                <i class="fas fa-plus-circle me-2"></i>إضافة دواء جديد
            </a>
            @endcan
            @can('hc-create-drugs')
            <button type="button" class="btn btn-primary btn-lg shadow-sm" data-bs-toggle="modal" data-bs-target="#addDrugModal">
                <i class="fas fa-list-plus me-2"></i>إضافة دواء موجود
            </button>
            @endcan
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
            <form id="filterForm">
                <div class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label class="form-label fw-semibold text-secondary">
                            <i class="fas fa-search text-primary me-2"></i>بحث بالاسم
                        </label>
                        <input type="text" class="form-control form-control-lg" id="searchInput" name="search"
                            placeholder="اسم الدواء أو الاسم العلمي" value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold text-secondary">
                            <i class="fas fa-tags text-info me-2"></i>الفئة
                        </label>
                        <select class="form-select form-select-lg" id="categoryFilter" name="category">
                            <option value="">جميع الفئات</option>
                            @foreach ($categories as $category)
                            <option value="{{$category}}" {{ request('category') == $category ? 'selected' : '' }}>{{$category}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold text-secondary">
                            <i class="fas fa-check-circle text-success me-2"></i>التوفر
                        </label>
                        <select class="form-select form-select-lg" id="availabilityFilter" name="availability">
                            <option value="">الكل</option>
                            <option value="1" {{ request('availability') == '1' ? 'selected' : '' }}>متاح</option>
                            <option value="0" {{ request('availability') == '0' ? 'selected' : '' }}>غير متاح</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="button" class="btn btn-outline-secondary btn-lg w-100" id="clearFilters">
                            <i class="fas fa-times me-1"></i>مسح
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- عداد النتائج -->
    <div class="row mb-3">
         <div class="col-md-2">

            <select name="per_page" id="per_page" class="form-select form-select-lg">
                @foreach([10, 15, 25, 50, 100] as $size)
                    <option value="{{ $size }}" {{ ($filters['per_page'] ?? 15) == $size ? 'selected' : '' }}>
                        {{ $size }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2 alert alert-info border-0 shadow-sm d-inline-block">
            <i class="fas fa-info-circle me-2"></i>
            إجمالي الأدوية: <strong id="drugsCount">{{ $drugs->total() }}</strong>
            @if(request()->hasAny(['search', 'category', 'availability']))
                <span class="text-muted">
                    (مفلترة من {{ $drugs->total() }} دواء)
                </span>
            @endif
        </div>

    </div>

    <!-- Loading indicator -->
    <div id="loadingIndicator" class="text-center py-3" style="display: none;">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">جاري التحميل...</span>
        </div>
    </div>

    <!-- الجدول -->
    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div id="drugsTableContainer">
                <div class="table-responsive">
                    <table class="table table-hover mb-0" id="drugsTable">
                        <thead style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                            <tr class="text-white">
                                <th class="py-3">اسم الدواء</th>
                                <th class="py-3">الاسم العلمي</th>
                                <th class="py-3">الفئة</th>
                                <th class="py-3">الشركة المصنعة</th>
                                <th class="py-3">الكمية</th>
                                <th class="py-3">السعر</th>
                                <th class="py-3">التوفر</th>
                                <th class="py-3">الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody id="drugsTableBody">
                            @include('health-center.drugs.partials.table-rows', ['drugs' => $drugs])
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-center mt-4" id="paginationContainer">
        {{ $drugs->appends(request()->query())->links() }}
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

.filter-active {
    background-color: #e3f2fd !important;
    border-color: #2196f3 !important;
}
</style>
@endsection

@section('scripts')
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Filter elements
    const searchInput = document.getElementById('searchInput');
    const categoryFilter = document.getElementById('categoryFilter');
    const availabilityFilter = document.getElementById('availabilityFilter');
    const perPageFilter = document.getElementById('per_page');
    const clearFiltersBtn = document.getElementById('clearFilters');
    const loadingIndicator = document.getElementById('loadingIndicator');
    const drugsTableBody = document.getElementById('drugsTableBody');
    const paginationContainer = document.getElementById('paginationContainer');
    const drugsCount = document.getElementById('drugsCount');

    let filterTimeout;

    // Apply filters function
    function applyFilters() {
        clearTimeout(filterTimeout);
        filterTimeout = setTimeout(() => {
            const formData = new FormData();

            const search = searchInput.value.trim();
            const category = categoryFilter.value;
            const availability = availabilityFilter.value;
            const per_page = perPageFilter.value;

            if (search) formData.append('search', search);
            if (category) formData.append('category', category);
            if (availability !== '') formData.append('availability', availability);
            if (per_page) formData.append('per_page', per_page);

            // Show loading
            loadingIndicator.style.display = 'block';
            drugsTableBody.style.opacity = '0.5';

            // Build query string
            const params = new URLSearchParams();
            for (let [key, value] of formData.entries()) {
                params.append(key, value);
            }

            // Make AJAX request
            fetch(`{{ route('health-center.drugs.index') }}?${params.toString()}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                // Update table content
                drugsTableBody.innerHTML = data.drugs.html;

                // Update pagination
                paginationContainer.innerHTML = data.pagination;

                // Update count
                drugsCount.textContent = data.drugs.total;

                // Hide loading
                loadingIndicator.style.display = 'none';
                drugsTableBody.style.opacity = '1';

                // Re-initialize event listeners for new content
                initializeTableEvents();

                // Update URL without page reload
                const newUrl = new URL(window.location);
                if (search) newUrl.searchParams.set('search', search);
                else newUrl.searchParams.delete('search');

                if (category) newUrl.searchParams.set('category', category);
                else newUrl.searchParams.delete('category');

                if (availability !== '') newUrl.searchParams.set('availability', availability);
                else newUrl.searchParams.delete('availability');

                window.history.pushState({}, '', newUrl);
            })
            .catch(error => {
                console.error('Error:', error);
                loadingIndicator.style.display = 'none';
                drugsTableBody.style.opacity = '1';

                Swal.fire({
                    icon: 'error',
                    title: 'خطأ',
                    text: 'حدث خطأ أثناء تطبيق الفلاتر'
                });
            });
        }, 300); // Debounce for 300ms
    }

    // Event listeners for filters
    searchInput.addEventListener('input', applyFilters);
    categoryFilter.addEventListener('change', applyFilters);
    availabilityFilter.addEventListener('change', applyFilters);
    per_page.addEventListener('change', applyFilters);

    // Clear filters
    clearFiltersBtn.addEventListener('click', function() {
        searchInput.value = '';
        categoryFilter.value = '';
        availabilityFilter.value = '';

        // Remove filter styling
        document.querySelectorAll('.filter-active').forEach(el => {
            el.classList.remove('filter-active');
        });

        applyFilters();
    });

    // Add visual feedback for active filters
    function updateFilterStyling() {
        // Search input
        if (searchInput.value.trim()) {
            searchInput.classList.add('filter-active');
        } else {
            searchInput.classList.remove('filter-active');
        }

        // Category filter
        if (categoryFilter.value) {
            categoryFilter.classList.add('filter-active');
        } else {
            categoryFilter.classList.remove('filter-active');
        }

        // Availability filter
        if (availabilityFilter.value !== '') {
            availabilityFilter.classList.add('filter-active');
        } else {
            availabilityFilter.classList.remove('filter-active');
        }
    }

    // Update styling on input
    searchInput.addEventListener('input', updateFilterStyling);
    categoryFilter.addEventListener('change', updateFilterStyling);
    availabilityFilter.addEventListener('change', updateFilterStyling);

    // Initialize styling on page load
    updateFilterStyling();

    // Initialize table events
    function initializeTableEvents() {
        // تتبع تغييرات الكمية
        document.querySelectorAll('.stock-input').forEach(input => {
            input.addEventListener('input', function() {
                const originalValue = this.dataset.originalValue;
                const currentValue = this.value;
                const updateBtn = this.parentNode.querySelector('.update-stock-btn');

                if (currentValue != originalValue) {
                    this.classList.add('changed');
                    updateBtn.style.display = 'block';
                } else {
                    this.classList.remove('changed');
                    updateBtn.style.display = 'none';
                }
            });
        });

        // تحديث الكمية
        document.querySelectorAll('.update-stock-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const drugId = this.dataset.drugId;
                const stockInput = document.querySelector(`.stock-input[data-drug-id="${drugId}"]`);
                const stock = stockInput.value;
                updateStock(drugId, stock, this);
            });
        });

        // تغيير حالة التوفر
        document.querySelectorAll('.availability-toggle').forEach(toggle => {
            toggle.addEventListener('change', function() {
                const drugId = this.dataset.drugId;
                toggleAvailability(drugId, this);
            });
        });

        // حذف الدواء
        document.querySelectorAll('.remove-drug-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const drugId = this.dataset.drugId;
                const drugName = this.dataset.drugName;
                confirmRemoveDrug(drugId, drugName);
            });
        });
    }

    // Initialize events on page load
    initializeTableEvents();

    // Handle pagination clicks
    document.addEventListener('click', function(e) {
        if (e.target.matches('.pagination a') || e.target.closest('.pagination a')) {
            e.preventDefault();
            const link = e.target.matches('a') ? e.target : e.target.closest('a');
            const url = new URL(link.href);

            // Add current filters to pagination URL
            const currentParams = new URLSearchParams(window.location.search);
            for (let [key, value] of currentParams.entries()) {
                if (!url.searchParams.has(key)) {
                    url.searchParams.set(key, value);
                }
            }

            // Load the page
            loadingIndicator.style.display = 'block';
            drugsTableBody.style.opacity = '0.5';

            fetch(url.toString(), {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                drugsTableBody.innerHTML = data.drugs.html;
                paginationContainer.innerHTML = data.pagination;
                drugsCount.textContent = data.drugs.total;

                loadingIndicator.style.display = 'none';
                drugsTableBody.style.opacity = '1';

                initializeTableEvents();

                // Update URL
                window.history.pushState({}, '', url);
            })
            .catch(error => {
                console.error('Error:', error);
                loadingIndicator.style.display = 'none';
                drugsTableBody.style.opacity = '1';
            });
        }
    });

    // Modal events
    const addDrugModal = document.getElementById('addDrugModal');
    if (addDrugModal) {
        addDrugModal.addEventListener('show.bs.modal', function() {
            loadAvailableDrugs();
        });
    }

    // إضافة دواء جديد
    const addDrugBtn = document.getElementById('addDrugBtn');
    if (addDrugBtn) {
        addDrugBtn.addEventListener('click', function() {
            addDrug();
        });
    }
});

// Function definitions
function loadAvailableDrugs() {
    fetch('/health-center/drugs-available')
        .then(response => response.json())
        .then(drugs => {
            const select = document.querySelector('select[name="drug_id"]');
            select.innerHTML = '<option value="">-- اختر الدواء --</option>';

            drugs.forEach(function(drug) {
                const option = document.createElement('option');
                option.value = drug.id;
                option.textContent = `${drug.name} (${drug.scientific_name || 'غير محدد'})`;
                select.appendChild(option);
            });
        })
        .catch(error => {
            showToast('خطأ في تحميل الأدوية', 'error');
        });
}

function addDrug() {
    const form = document.getElementById('addDrugForm');
    const formData = new FormData(form);
    const btn = document.getElementById('addDrugBtn');
    const spinner = btn.querySelector('.spinner-border');

    // التحقق من صحة البيانات
    if (!formData.get('drug_id')) {
        Swal.fire({
            icon: 'warning',
            title: 'تنبيه',
            text: 'يرجى اختيار الدواء أولاً'
        });
        return;
    }

    btn.disabled = true;
    spinner.style.display = 'inline-block';

    fetch('/health-center/drugs', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'تم بنجاح',
                text: data.message,
                timer: 2000,
                showConfirmButton: false
            }).then(() => {
                const modal = bootstrap.Modal.getInstance(document.getElementById('addDrugModal'));
                modal.hide();
                location.reload();
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'خطأ',
                text: data.message
            });
        }
    })
    .catch(error => {
        Swal.fire({
            icon: 'error',
            title: 'خطأ',
            text: 'حدث خطأ أثناء إضافة الدواء'
        });
    })
    .finally(() => {
        btn.disabled = false;
        spinner.style.display = 'none';
    });
}

function updateStock(drugId, stock, button) {
    const spinner = document.createElement('span');
    spinner.className = 'spinner-border spinner-border-sm me-1';
    button.insertBefore(spinner, button.firstChild);
    button.disabled = true;

    fetch(`/health-center/drugs/${drugId}`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ stock: stock })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const stockInput = document.querySelector(`.stock-input[data-drug-id="${drugId}"]`);
            stockInput.dataset.originalValue = stock;
            stockInput.classList.remove('changed');
            button.style.display = 'none';

            showToast(data.message, 'success');
        }
    })
    .catch(error => {
        showToast('حدث خطأ أثناء تحديث الكمية', 'error');
    })
    .finally(() => {
        spinner.remove();
        button.disabled = false;
    });
}

function toggleAvailability(drugId, toggleElement) {
    toggleElement.disabled = true;

    fetch(`/health-center/drugs/${drugId}/toggle`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const textElement = toggleElement.parentNode.querySelector('.availability-text');
            textElement.textContent = data.availability ? 'متاح' : 'غير متاح';

            showToast(data.message, 'success');
        }
    })
    .catch(error => {
        // إرجاع التوجل لحالته الأصلية في حالة الخطأ
        toggleElement.checked = !toggleElement.checked;
        showToast('حدث خطأ أثناء تحديث حالة التوفر', 'error');
    })
    .finally(() => {
        toggleElement.disabled = false;
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

    fetch(`/health-center/drugs/${drugId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'تم الحذف',
                text: data.message,
                timer: 2000,
                showConfirmButton: false
            }).then(() => {
                // إزالة الصف من الجدول
                const button = document.querySelector(`button[data-drug-id="${drugId}"]`);
                const row = button.closest('tr');
                row.style.transition = 'opacity 0.4s';
                row.style.opacity = '0';
                setTimeout(() => {
                    row.remove();
                    // Re-apply filters to update count
                    document.getElementById('searchInput').dispatchEvent(new Event('input'));
                }, 400);
            });
        }
    })
    .catch(error => {
        Swal.fire({
            icon: 'error',
            title: 'خطأ',
            text: 'حدث خطأ أثناء حذف الدواء'
        });
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

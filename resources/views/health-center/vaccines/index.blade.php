@extends('layouts.health-center.master')

@section('title', 'إدارة اللقاحات')

@section('content')
    <div class="container-fluid py-4">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-md-6">
                <h2 class="mb-0 text-primary">
                    <i class="fas fa-syringe me-2"></i>إدارة اللقاحات
                </h2>
                <p class="text-muted mb-0">عرض وإدارة اللقاحات - {{ $healthCenter->name }}</p>
            </div>
            <div class="col-md-6 text-end">
                <button type="button" class="btn btn-primary btn-lg shadow-sm" data-bs-toggle="modal"
                    data-bs-target="#addVaccineModal">
                    <i class="fas fa-plus-circle me-2"></i>إضافة لقاح جديد
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
                <form id="filterForm">
                    <div class="row g-3 align-items-end">
                        <div class="col-md-4">
                            <label class="form-label fw-semibold text-secondary">
                                <i class="fas fa-search text-primary me-2"></i>البحث باسم اللقاح او الوصف
                            </label>
                            <input type="text" class="form-control form-control-lg" id="searchVaccine" name="search"
                                placeholder="ابحث عن لقاح..." value="{{ request('search') }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold text-secondary">
                                <i class="fas fa-calendar text-info me-2"></i>العمر المناسب بالشهور
                            </label>
                            <input type="number" class="form-control form-control-lg" id="ageFilter" name="age">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold text-secondary">
                                <i class="fas fa-toggle-on text-success me-2"></i>الحالة
                            </label>
                            <select class="form-select form-select-lg" id="availabilityFilter" name="availability">
                                <option value="">الكل</option>
                                <option value="1" {{ request('availability') == '1' ? 'selected' : '' }}>متوفر</option>
                                <option value="0" {{ request('availability') == '0' ? 'selected' : '' }}>غير متوفر</option>
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

        <!-- Success Alert -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- عداد النتائج -->
        <div class="row">
            <div class="col-md-2">
                <select name="per_page" id="per_page" class="form-select form-select-lg">
                    @foreach([10, 15, 25, 50, 100] as $size)
                        <option value="{{ $size }}" {{ ($filters['per_page'] ?? 15) == $size ? 'selected' : '' }}>
                            {{ $size }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3 mb-3">
                <div class="alert alert-info border-0 shadow-sm d-inline-block">
                    <i class="fas fa-info-circle me-2"></i>
                    إجمالي اللقاحات: <strong id="vaccinesCount">{{ $centerVaccines->total() }}</strong>
                    @if(request()->hasAny(['search', 'age', 'availability']))
                        <span class="text-muted">
                            (مفلترة من {{ $centerVaccines->total() }} لقاح)
                        </span>
                    @endif
                </div>
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
                <div class="table-responsive mx-2 rounded">
                    <table class="table table-hover align-middle mb-0" id="vaccinesTable">
                        <thead style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                            <tr class="text-white">
                                <th class="text-center py-3">#</th>
                                <th class="py-3">
                                    <i class="fas fa-syringe me-2"></i>اسم اللقاح
                                </th>
                                <th class="text-center py-3">
                                    <i class="fas fa-calendar me-2"></i>العمر المناسب
                                </th>
                                <th class="text-center py-3">
                                    <i class="fas fa-hashtag me-2"></i>عدد الجرعات
                                </th>
                                <th class="text-center py-3">
                                    <i class="fas fa-clock me-2"></i>الفترة بين الجرعات
                                </th>
                                <th class="text-center py-3">
                                    <i class="fas fa-toggle-on me-2"></i>الحالة
                                </th>
                                <th class="text-center py-3">
                                    <i class="fas fa-calendar-plus me-2"></i>تاريخ الإضافة
                                </th>
                                <th class="text-center py-3">
                                    <i class="fas fa-cogs me-2"></i>الإجراءات
                                </th>
                            </tr>
                        </thead>
                        <tbody id="vaccinesTableBody">
                            @include('health-center.vaccines.partials.table-rows', ['centerVaccines' => $centerVaccines])
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-4" id="paginationContainer">
            {{ $centerVaccines->appends(request()->query())->links() }}
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
                                @if(!$centerVaccines->pluck('id')->contains($vaccine->id))
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

    <!-- Create the table rows partial file -->
    @if(!View::exists('health-center.vaccines.partials.table-rows'))
        <!-- This should be in a separate file: resources/views/health-center/vaccines/partials/table-rows.blade.php -->
        <script>
            console.warn('Please create the partial file: resources/views/health-center/vaccines/partials/table-rows.blade.php');
        </script>
    @endif

@endsection

@section('styles')
    <style>
        .vaccine-card {
            transition: all 0.3s ease;
        }

        .vaccine-card:hover {
            transform: translateY(-2px);
        }

        .availability-toggle:checked+.availability-label {
            color: #28a745;
            font-weight: bold;
        }

        .availability-toggle:not(:checked)+.availability-label {
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

        .filter-active {
            background-color: #e3f2fd !important;
            border-color: #2196f3 !important;
        }
    </style>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Filter elements
            const searchInput = document.getElementById('searchVaccine');
            const ageFilter = document.getElementById('ageFilter');
            const availabilityFilter = document.getElementById('availabilityFilter');
            const perPageFilter = document.getElementById('per_page');
            const clearFiltersBtn = document.getElementById('clearFilters');
            const loadingIndicator = document.getElementById('loadingIndicator');
            const vaccinesTableBody = document.getElementById('vaccinesTableBody');
            const paginationContainer = document.getElementById('paginationContainer');
            const vaccinesCount = document.getElementById('vaccinesCount');

            let filterTimeout;

            // Apply filters function
            function applyFilters() {
                clearTimeout(filterTimeout);
                filterTimeout = setTimeout(() => {
                    const formData = new FormData();

                    const search = searchInput.value.trim();
                    const age = ageFilter.value;
                    const availability = availabilityFilter.value;
                    const perPage = perPageFilter.value;

                    if (search) formData.append('search', search);
                    if (age) formData.append('age', age);
                    if (availability !== '') formData.append('availability', availability);
                    if (perPage) formData.append('per_page', perPage);

                    // Show loading
                    loadingIndicator.style.display = 'block';
                    vaccinesTableBody.style.opacity = '0.5';

                    // Build query string
                    const params = new URLSearchParams();
                    for (let [key, value] of formData.entries()) {
                        params.append(key, value);
                    }

                    // Make AJAX request
                    fetch(`{{ route('health-center.vaccines.index') }}?${params.toString()}`, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        }
                    })
                        .then(response => response.json())
                        .then(data => {
                            // Update table content
                            vaccinesTableBody.innerHTML = data.vaccines.html;

                            // Update pagination
                            paginationContainer.innerHTML = data.pagination;

                            // Update count
                            vaccinesCount.textContent = data.vaccines.total;

                            // Hide loading
                            loadingIndicator.style.display = 'none';
                            vaccinesTableBody.style.opacity = '1';

                            // Re-initialize event listeners for new content
                            initializeTableEvents();

                            // Update URL without page reload
                            const newUrl = new URL(window.location);
                            if (search) newUrl.searchParams.set('search', search);
                            else newUrl.searchParams.delete('search');

                            if (age) newUrl.searchParams.set('age', age);
                            else newUrl.searchParams.delete('age');

                            if (availability !== '') newUrl.searchParams.set('availability', availability);
                            else newUrl.searchParams.delete('availability');

                            window.history.pushState({}, '', newUrl);
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            loadingIndicator.style.display = 'none';
                            vaccinesTableBody.style.opacity = '1';

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
            ageFilter.addEventListener('change', applyFilters);
            availabilityFilter.addEventListener('change', applyFilters);
            perPageFilter.addEventListener('change', applyFilters);

            // Clear filters
            clearFiltersBtn.addEventListener('click', function () {
                searchInput.value = '';
                ageFilter.value = '';
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

                // Age filter
                if (ageFilter.value) {
                    ageFilter.classList.add('filter-active');
                } else {
                    ageFilter.classList.remove('filter-active');
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
            ageFilter.addEventListener('change', updateFilterStyling);
            availabilityFilter.addEventListener('change', updateFilterStyling);

            // Initialize styling on page load
            updateFilterStyling();

            // Initialize table events
            function initializeTableEvents() {
                // تبديل حالة توفر اللقاح
                const availabilityToggles = document.querySelectorAll('.availability-toggle');
                availabilityToggles.forEach(toggle => {
                    toggle.addEventListener('change', function () {
                        const vaccineId = this.dataset.vaccineId;
                        const availability = this.checked;
                        updateVaccineAvailability(vaccineId, availability, this);
                    });
                });
            }

            // Initialize events on page load
            initializeTableEvents();

            // Handle pagination clicks
            document.addEventListener('click', function (e) {
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
                    vaccinesTableBody.style.opacity = '0.5';

                    fetch(url.toString(), {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        }
                    })
                        .then(response => response.json())
                        .then(data => {
                            vaccinesTableBody.innerHTML = data.vaccines.html;
                            paginationContainer.innerHTML = data.pagination;
                            vaccinesCount.textContent = data.vaccines.total;

                            loadingIndicator.style.display = 'none';
                            vaccinesTableBody.style.opacity = '1';

                            initializeTableEvents();

                            // Update URL
                            window.history.pushState({}, '', url);
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            loadingIndicator.style.display = 'none';
                            vaccinesTableBody.style.opacity = '1';
                        });
                }
            });

            // البحث في اللقاحات المتاحة في المودال
            const modalSearchInput = document.getElementById('vaccine_search');
            const vaccineCards = document.querySelectorAll('.vaccine-card');

            if (modalSearchInput) {
                modalSearchInput.addEventListener('input', function () {
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

            // التحقق من وجود لقاحات متاحة للإضافة
            checkAvailableVaccines();
        });

        // Rest of your existing functions...
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

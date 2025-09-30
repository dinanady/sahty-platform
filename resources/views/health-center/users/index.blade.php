@extends('layouts.health-center.master')

@section('content')
    <div class="container-fluid py-4">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-md-6">
                <h2 class="mb-0 text-primary">
                    <i class="fas fa-users me-2"></i>إدارة المستخدمين
                </h2>
                <p class="text-muted mb-0">عرض وإدارة مستخدمي النظام</p>
            </div>
            <div class="col-md-6 text-end">
                @can('hc-create-users')
                    <button class="btn btn-primary btn-lg shadow-sm" data-bs-toggle="modal" data-bs-target="#userModal"
                        onclick="openCreateModal()">
                        <i class="fas fa-plus-circle me-2"></i>إضافة مستخدم جديد
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
                <div class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label class="form-label fw-semibold text-secondary">
                            <i class="fas fa-search text-primary me-2"></i>البحث
                        </label>
                        <input type="text" class="form-control form-control-lg" id="searchInput"
                            placeholder="بحث بالاسم أو البريد...">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold text-secondary">
                            <i class="fas fa-user-tag text-info me-2"></i>الدور
                        </label>
                        <select class="form-select form-select-lg" id="roleFilter">
                            <option value="">كل الأدوار</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}">{{ $role->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold text-secondary">
                            <i class="fas fa-toggle-on text-success me-2"></i>الحالة
                        </label>
                        <select class="form-select form-select-lg" id="statusFilter">
                            <option value="">الكل</option>
                            <option value="1">نشط</option>
                            <option value="0">معطل</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-outline-secondary btn-lg w-100" onclick="resetFilters()">
                            <i class="fas fa-times me-1"></i>مسح
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- عداد النتائج -->
        <div class="mb-3">
            <div class="alert alert-info border-0 shadow-sm d-inline-block">
                <i class="fas fa-info-circle me-2"></i>
                إجمالي المستخدمين: <strong id="total-users">{{ $users->total() }}</strong>
            </div>
        </div>

        <!-- الجدول -->
        <div class="card shadow-sm border-0">
            <div class="card-body p-0">
                <!-- Loading indicator -->
                <div id="loadingIndicator" class="text-center py-4" style="display: none;">
                    <i class="fas fa-spinner fa-spin fa-2x text-primary"></i>
                    <div class="mt-2 text-muted">جاري التحميل...</div>
                </div>

                <!-- Users table container -->
                <div id="usersTableContainer">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0" id="usersTable">
                            <thead style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                <tr class="text-white">
                                    <th width="5%" class="text-center py-3">#</th>
                                    <th width="20%" class="py-3">المستخدم</th>
                                    <th width="20%" class="py-3">البريد الإلكتروني</th>
                                    <th width="12%" class="py-3">الهاتف</th>
                                    <th width="18%" class="py-3">الأدوار</th>
                                    <th width="10%" class="py-3">الحالة</th>
                                    <th width="12%" class="py-3">تاريخ الإنشاء</th>
                                    <th width="13%" class="text-center py-3">الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody id="usersTableBody">
                                @include('health-center.users.partials.table-rows')
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination container -->
                    <div id="paginationContainer" class="d-flex justify-content-between align-items-center mt-3 px-3 pb-3">
                        <div class="text-muted small" id="paginationInfo">
                            عرض {{ $users->firstItem() ?? 0 }} إلى {{ $users->lastItem() ?? 0 }} من
                            {{ $users->total() }} مستخدم
                        </div>
                        <div id="paginationLinks">
                            {{ $users->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal للإضافة والتعديل -->
    <div class="modal fade" id="userModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="modalTitle">
                        <i class="fas fa-user-plus me-2"></i>إضافة مستخدم جديد
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form id="userForm">
                    @csrf
                    <input type="hidden" id="user_id" name="user_id">
                    <input type="hidden" id="form_method" value="POST">

                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">
                                    <i class="fas fa-user text-primary me-1"></i>
                                    الاسم الاول <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control" id="first_name" name="first_name" required
                                    placeholder="أدخل الاسم الاول">
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">
                                    <i class="fas fa-user text-primary me-1"></i>
                                    الاسم الاخير <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control" id="last_name" name="last_name" required
                                    placeholder="أدخل الاسم الاخير">
                                <div class="invalid-feedback"></div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">
                                    <i class="fas fa-envelope text-primary me-1"></i>
                                    البريد الإلكتروني <span class="text-danger">*</span>
                                </label>
                                <input type="email" class="form-control" id="email" name="email" required
                                    placeholder="example@domain.com">
                                <div class="invalid-feedback"></div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">
                                    <i class="fas fa-phone text-primary me-1"></i>
                                    رقم الهاتف
                                </label>
                                <input type="text" class="form-control" id="phone" name="phone" placeholder="01XXXXXXXXX">
                                <div class="invalid-feedback"></div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">
                                    <i class="fas fa-id-card text-primary me-1"></i>
                                    الرقم الوطني
                                </label>
                                <input type="text" class="form-control" id="national_id" name="national_id"
                                    placeholder="الرقم الوطني">
                                <div class="invalid-feedback"></div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">
                                    <i class="fas fa-lock text-primary me-1"></i>
                                    كلمة المرور <span class="text-danger" id="password_required">*</span>
                                </label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="password" name="password"
                                        placeholder="********">
                                    <button class="btn btn-outline-secondary" type="button"
                                        onclick="togglePassword('password')">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                                <div class="invalid-feedback"></div>
                                <small class="text-muted" id="password_hint" style="display:none;">
                                    <i class="fas fa-info-circle me-1"></i>اتركه فارغاً إذا لم ترغب في التغيير
                                </small>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">
                                    <i class="fas fa-lock text-primary me-1"></i>
                                    تأكيد كلمة المرور <span class="text-danger" id="password_confirmation_required">*</span>
                                </label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="password_confirmation"
                                        name="password_confirmation" placeholder="********">
                                    <button class="btn btn-outline-secondary" type="button"
                                        onclick="togglePassword('password_confirmation')">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                                <div class="invalid-feedback"></div>
                            </div>

                            <div class="col-12">
                                <hr class="my-3">
                                <label class="form-label fw-semibold">
                                    <i class="fas fa-user-shield text-primary me-1"></i>
                                    الأدوار والصلاحيات
                                </label>
                                <div class="border rounded p-3 bg-light" style="max-height: 250px; overflow-y: auto;">
                                    @forelse($roles as $role)
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="checkbox" name="roles[]"
                                                value="{{ $role->id }}" id="role_{{ $role->id }}">
                                            <label class="form-check-label fw-semibold" for="role_{{ $role->id }}">
                                                <i class="fas fa-shield-alt text-info me-1"></i>
                                                {{ $role->name }}
                                                <small class="text-muted d-block ms-4">{{ $role->permissions->count() }}
                                                    صلاحية</small>
                                            </label>
                                        </div>
                                    @empty
                                        <div class="text-center text-muted py-3">
                                            <i class="fas fa-info-circle me-1"></i>
                                            لا توجد أدوار متاحة
                                        </div>
                                    @endforelse
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="is_active" value="1"
                                        name="is_active" checked>
                                    <label class="form-check-label fw-semibold" for="is_active">
                                        <i class="fas fa-toggle-on text-success me-1"></i>
                                        تفعيل المستخدم
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-times me-1"></i>إلغاء
                        </button>
                        <button type="submit" class="btn btn-primary" id="submitBtn">
                            <i class="fas fa-save me-1"></i>
                            <span id="submitBtnText">حفظ المستخدم</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal تعيين الأدوار السريع -->
    <div class="modal fade" id="assignRolesModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-user-shield me-2"></i>تعيين الأدوار
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form id="assignRolesForm">
                    @csrf
                    <input type="hidden" id="assign_user_id" name="user_id">

                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">المستخدم:</label>
                            <div class="alert alert-light" id="assign_user_name"></div>
                        </div>

                        <label class="form-label fw-semibold">اختر الأدوار:</label>
                        <div class="border rounded p-3 bg-light" style="max-height: 300px; overflow-y: auto;">
                            @foreach($roles as $role)
                                <div class="form-check mb-2">
                                    <input class="form-check-input assign-role-checkbox" type="checkbox" name="assign_roles[]"
                                        value="{{ $role->id }}" id="assign_role_{{ $role->id }}">
                                    <label class="form-check-label" for="assign_role_{{ $role->id }}">
                                        <i class="fas fa-shield-alt text-info me-1"></i>{{ $role->name }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                        <button type="submit" class="btn btn-info" id="assignRolesBtn">
                            <i class="fas fa-check me-1"></i>تعيين
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .avatar {
            transition: transform 0.2s;
        }

        .avatar:hover {
            transform: scale(1.1);
        }

        .btn-group-sm>.btn {
            padding: 0.25rem 0.5rem;
        }

        .table-hover tbody tr:hover {
            background-color: rgba(0, 123, 255, 0.05);
        }

        .form-check-input:checked {
            background-color: #0d6efd;
            border-color: #0d6efd;
        }

        .modal-header.bg-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
        }

        .modal-header.bg-info {
            background: linear-gradient(135deg, #17a2b8 0%, #138496 100%) !important;
        }

        /* Filters animation */
        #filtersSection {
            transition: all 0.3s ease;
            overflow: hidden;
            max-height: 0;
            opacity: 0;
        }

        /* Filter toggle button active state */
        #toggleFilters.active {
            background-color: #0d6efd;
            color: white;
            border-color: #0d6efd;
        }

        /* Loading animation */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .fade-in {
            animation: fadeIn 0.3s ease;
        }
    </style>
@endpush

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // متغيرات عامة
        let filtersVisible = false;
        let currentPage = 1;

        // تطبيق الفلاتر
        function applyFilters(page = 1) {
            const searchValue = document.getElementById('searchInput').value;
            const roleValue = document.getElementById('roleFilter').value;
            const statusValue = document.getElementById('statusFilter').value;

            const params = new URLSearchParams({
                page: page,
                search: searchValue,
                role: roleValue,
                status: statusValue
            });

            // إزالة القيم الفارغة
            for (let [key, value] of [...params]) {
                if (!value) params.delete(key);
            }

            showLoading();

            fetch(`/health-center/users?${params.toString()}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
                .then(response => response.json())
                .then(data => {
                    updateUsersTable(data.users);
                    updatePagination(data.pagination, data.users);
                    hideLoading();
                })
                .catch(error => {
                    console.error('Error:', error);
                    hideLoading();
                    Swal.fire({
                        icon: 'error',
                        title: 'خطأ!',
                        text: 'حدث خطأ أثناء تحميل البيانات',
                        confirmButtonText: 'حسناً'
                    });
                });
        }

        // تحديث جدول المستخدمين
        function updateUsersTable(users) {
            const tbody = document.getElementById('usersTableBody');

            // تحديث الجدول بالـ HTML الجديد
            tbody.innerHTML = users.html;
        }
        // تحديث التصفح (Pagination)
        function updatePagination(paginationHtml, usersData) {
            document.getElementById('paginationLinks').innerHTML = paginationHtml;
            document.getElementById('total-users').textContent = usersData.total;
            document.getElementById('paginationInfo').innerHTML =
                `عرض ${usersData.from || 0} إلى ${usersData.to || 0} من ${usersData.total} مستخدم`;

            // إضافة event listeners للتصفح
            document.querySelectorAll('#paginationLinks a').forEach(link => {
                link.addEventListener('click', function (e) {
                    e.preventDefault();
                    const url = new URL(this.href);
                    const page = url.searchParams.get('page');
                    if (page) {
                        applyFilters(page);
                    }
                });
            });
        }

        // إظهار مؤشر التحميل
        function showLoading() {
            document.getElementById('loadingIndicator').style.display = 'block';
            document.getElementById('usersTableContainer').style.opacity = '0.5';
        }

        // إخفاء مؤشر التحميل
        function hideLoading() {
            document.getElementById('loadingIndicator').style.display = 'none';
            document.getElementById('usersTableContainer').style.opacity = '1';
        }

        // مسح الفلاتر
        function resetFilters() {
            document.getElementById('searchInput').value = '';
            document.getElementById('roleFilter').value = '';
            document.getElementById('statusFilter').value = '';
            applyFilters();
        }

        // البحث التلقائي عند الكتابة (مع تأخير)
        let searchTimeout;
        document.getElementById('searchInput').addEventListener('input', function () {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                applyFilters();
            }, 500); // تأخير نصف ثانية
        });

        // تطبيق الفلاتر عند تغيير القائمة المنسدلة
        document.getElementById('roleFilter').addEventListener('change', applyFilters);
        document.getElementById('statusFilter').addEventListener('change', applyFilters);

        // فتح Modal الإضافة
        function openCreateModal() {
            document.getElementById('userForm').reset();
            document.getElementById('modalTitle').innerHTML = '<i class="fas fa-user-plus me-2"></i>إضافة مستخدم جديد';
            document.getElementById('user_id').value = '';
            document.getElementById('form_method').value = 'POST';
            document.getElementById('password').required = true;
            document.getElementById('password_confirmation').required = true;
            document.getElementById('password_required').style.display = 'inline';
            document.getElementById('password_confirmation_required').style.display = 'inline';
            document.getElementById('password_hint').style.display = 'none';
            document.getElementById('submitBtnText').textContent = 'حفظ المستخدم';
            document.getElementById('is_active').checked = true;

            document.querySelectorAll('input[name="roles[]"]').forEach(checkbox => {
                checkbox.checked = false;
            });

            document.querySelectorAll('.is-invalid').forEach(el => {
                el.classList.remove('is-invalid');
                const feedback = el.nextElementSibling;
                if (feedback && feedback.classList.contains('invalid-feedback')) {
                    feedback.textContent = '';
                }
            });
        }

        // فتح Modal التعديل
        function openEditModal(userId) {
            document.getElementById('submitBtn').disabled = true;
            document.getElementById('submitBtnText').innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>جاري التحميل...';

            fetch(`/health-center/users/${userId}/edit`, {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            })
                .then(response => {
                    if (!response.ok) throw new Error('فشل في تحميل البيانات');
                    return response.json();
                })
                .then(data => {
                    document.getElementById('modalTitle').innerHTML = '<i class="fas fa-user-edit me-2"></i>تعديل المستخدم';
                    document.getElementById('user_id').value = data.user.id;
                    document.getElementById('form_method').value = 'PUT';
                    document.getElementById('first_name').value = data.user.first_name || '';
                    document.getElementById('last_name').value = data.user.last_name || '';
                    document.getElementById('email').value = data.user.email || '';
                    document.getElementById('phone').value = data.user.phone || '';
                    document.getElementById('national_id').value = data.user.national_id || '';
                    document.getElementById('password').value = '';
                    document.getElementById('password_confirmation').value = '';
                    document.getElementById('password').required = false;
                    document.getElementById('password_confirmation').required = false;
                    document.getElementById('password_required').style.display = 'none';
                    document.getElementById('password_confirmation_required').style.display = 'none';
                    document.getElementById('password_hint').style.display = 'block';
                    document.getElementById('submitBtnText').textContent = 'تحديث المستخدم';
                    document.getElementById('is_active').checked = data.user.is_active !== false;

                    document.querySelectorAll('input[name="roles[]"]').forEach(checkbox => {
                        checkbox.checked = data.userRoles.includes(parseInt(checkbox.value));
                    });

                    document.querySelectorAll('.is-invalid').forEach(el => {
                        el.classList.remove('is-invalid');
                        const feedback = el.nextElementSibling;
                        if (feedback && feedback.classList.contains('invalid-feedback')) {
                            feedback.textContent = '';
                        }
                    });

                    document.getElementById('submitBtn').disabled = false;
                    new bootstrap.Modal(document.getElementById('userModal')).show();
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'خطأ!',
                        text: 'فشل في تحميل بيانات المستخدم',
                        confirmButtonText: 'حسناً'
                    });
                    document.getElementById('submitBtn').disabled = false;
                    document.getElementById('submitBtnText').textContent = 'حفظ المستخدم';
                });
        }

        // Modal تعيين الأدوار السريع
        function openAssignRolesModal(userId) {
            fetch(`/health-center/users/${userId}/edit`, {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            })
                .then(response => response.json())
                .then(data => {
                    document.getElementById('assign_user_id').value = data.user.id;
                    document.getElementById('assign_user_name').textContent = data.user.name;

                    document.querySelectorAll('.assign-role-checkbox').forEach(checkbox => {
                        checkbox.checked = data.userRoles.includes(parseInt(checkbox.value));
                    });

                    new bootstrap.Modal(document.getElementById('assignRolesModal')).show();
                })
                .catch(error => {
                    Swal.fire({
                        icon: 'error',
                        title: 'خطأ!',
                        text: 'فشل في تحميل البيانات',
                        confirmButtonText: 'حسناً'
                    });
                });
        }

        // إرسال نموذج تعيين الأدوار
        document.getElementById('assignRolesForm').addEventListener('submit', function (e) {
            e.preventDefault();

            const userId = document.getElementById('assign_user_id').value;
            const formData = new FormData(this);
            formData.append('_method', 'PUT');

            document.getElementById('assignRolesBtn').disabled = true;

            fetch(`/health-center/users/${userId}`, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'نجح!',
                            text: data.message,
                            confirmButtonText: 'حسناً',
                            timer: 2000
                        }).then(() => {
                            location.reload();
                        });
                    }
                })
                .catch(error => {
                    Swal.fire({
                        icon: 'error',
                        title: 'خطأ!',
                        text: 'حدث خطأ أثناء العملية',
                        confirmButtonText: 'حسناً'
                    });
                })
                .finally(() => {
                    document.getElementById('assignRolesBtn').disabled = false;
                });
        });

        // إرسال النموذج الرئيسي
        document.getElementById('userForm').addEventListener('submit', function (e) {
            e.preventDefault();

            const userId = document.getElementById('user_id').value;
            const method = document.getElementById('form_method').value;
            const url = userId ? `/health-center/users/${userId}` : '/health-center/users';

            const formData = new FormData(this);
            if (method === 'PUT') {
                formData.append('_method', 'PUT');
            }

            const submitBtn = document.getElementById('submitBtn');
            const submitBtnText = document.getElementById('submitBtnText');
            submitBtn.disabled = true;
            submitBtnText.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>جاري الحفظ...';

            document.querySelectorAll('.is-invalid').forEach(el => {
                el.classList.remove('is-invalid');
                const feedback = el.nextElementSibling;
                if (feedback && feedback.classList.contains('invalid-feedback')) {
                    feedback.textContent = '';
                }
            });

            fetch(url, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
                .then(response => {
                    if (!response.ok && response.status === 422) {
                        return response.json().then(data => {
                            throw { validation: true, errors: data.errors };
                        });
                    }
                    if (!response.ok) throw new Error('فشلت العملية');
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        bootstrap.Modal.getInstance(document.getElementById('userModal')).hide();
                        Swal.fire({
                            icon: 'success',
                            title: 'تم بنجاح!',
                            text: data.message,
                            confirmButtonText: 'حسناً',
                            timer: 2000
                        }).then(() => {
                            location.reload();
                        });
                    }
                })
                .catch(error => {
                    if (error.validation) {
                        Object.keys(error.errors).forEach(key => {
                            const input = document.getElementById(key);
                            if (input) {
                                input.classList.add('is-invalid');
                                const feedback = input.closest('.col-md-6, .col-12').querySelector('.invalid-feedback') ||
                                    input.nextElementSibling;
                                if (feedback && feedback.classList.contains('invalid-feedback')) {
                                    feedback.textContent = error.errors[key][0];
                                }
                            }
                            errorMessages += '• ' + error.errors[key][0] + '<br>';
                        });
                        Swal.fire({
                            icon: 'warning',
                            title: 'تنبيه!',
                            html: errorMessages, // استخدم html بدلاً من text
                            confirmButtonText: 'حسناً'
                        });
                    } else {
                        console.error('Error:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'خطأ!',
                            text: 'حدث خطأ أثناء العملية',
                            confirmButtonText: 'حسناً'
                        });
                    }
                })
                .finally(() => {
                    submitBtn.disabled = false;
                    submitBtnText.innerHTML = '<i class="fas fa-save me-1"></i>' +
                        (userId ? 'تحديث المستخدم' : 'حفظ المستخدم');
                });
        });

        // حذف المستخدم
        function deleteUser(userId) {
            Swal.fire({
                title: 'هل أنت متأكد من الحذف؟',
                text: "لن تتمكن من التراجع عن هذا الإجراء!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: '<i class="fas fa-trash me-1"></i>نعم، احذف!',
                cancelButtonText: '<i class="fas fa-times me-1"></i>إلغاء',
                showLoaderOnConfirm: true,
                preConfirm: () => {
                    return fetch(`/health-center/users/${userId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        }
                    })
                        .then(response => {
                            if (!response.ok) throw new Error('فشل الحذف');
                            return response.json();
                        })
                        .catch(error => {
                            Swal.showValidationMessage(`خطأ: ${error}`);
                        });
                },
                allowOutsideClick: () => !Swal.isLoading()
            }).then((result) => {
                if (result.isConfirmed && result.value) {
                    if (result.value.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'تم الحذف!',
                            text: result.value.message,
                            confirmButtonText: 'حسناً',
                            timer: 2000
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'خطأ!',
                            text: result.value.message,
                            confirmButtonText: 'حسناً'
                        });
                    }
                }
            });
        }

        // إظهار/إخفاء كلمة المرور
        function togglePassword(fieldId) {
            const field = document.getElementById(fieldId);
            const icon = event.currentTarget.querySelector('i');

            if (field.type === 'password') {
                field.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                field.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }

        // Event listeners إضافية
        document.addEventListener('DOMContentLoaded', function () {
            // إضافة تأثير Fade In للجدول
            const table = document.querySelector('#usersTable');
            if (table) {
                table.style.opacity = '0';
                setTimeout(() => {
                    table.style.transition = 'opacity 0.5s';
                    table.style.opacity = '1';
                }, 100);
            }

            // Tooltips initialization
            const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
            const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));
        });

        // إغلاق Modal عند الضغط على Escape
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                const modals = document.querySelectorAll('.modal.show');
                modals.forEach(modal => {
                    const modalInstance = bootstrap.Modal.getInstance(modal);
                    if (modalInstance) modalInstance.hide();
                });
            }
        });

        // تنظيف النموذج عند إغلاق Modal
        document.getElementById('userModal').addEventListener('hidden.bs.modal', function () {
            document.getElementById('userForm').reset();
            document.querySelectorAll('.is-invalid').forEach(el => {
                el.classList.remove('is-invalid');
                const feedback = el.nextElementSibling;
                if (feedback && feedback.classList.contains('invalid-feedback')) {
                    feedback.textContent = '';
                }
            });
        });

    </script>
@endsection

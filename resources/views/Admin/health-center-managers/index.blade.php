@extends('layouts.master')
@section('title')
    مديري الوحدات الصحية
@endsection
@section('page-header')
    <!--begin::Page title-->
    <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
        <!--begin::Title-->
        <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">
            إدارة مديري الوحدات الصحية
        </h1>
        <!--end::Title-->
        <!--begin::Breadcrumb-->
        <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
            <!--begin::Item-->
            <li class="breadcrumb-item text-muted">
                <a href="{{route('admin.dashboard')}}" class="text-muted text-hover-primary">الصفحة الرئيسية</a>
            </li>
            <!--end::Item-->
            <!--begin::Item-->
            <li class="breadcrumb-item">
                <span class="bullet bg-gray-200 w-5px h-2px"></span>
            </li>
            <!--end::Item-->
            <!--begin::Item-->
            <li class="breadcrumb-item text-dark">مديري الوحدات الصحية</li>
            <!--end::Item-->
        </ul>
        <!--end::Breadcrumb-->
    </div>
    <!--end::Page title-->
@endsection
@section('content')
    <!--begin::Post-->
    <div class="post d-flex flex-column-fluid" id="kt_post">
        <!--begin::Container-->
        <div id="kt_content_container" class="container-xxl">
            <!--begin::Card-->
            <div class="card">
                <!--begin::Card header-->
                <div class="card-header border-0 pt-6">
                    <!--begin::Card title-->
                    <div class="card-title">
                        <!--begin::Search-->
                        <div class="d-flex align-items-center position-relative my-1">
                            <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-5">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                            <input type="text" data-kt-customer-table-filter="search" class="form-control form-control-solid w-250px ps-13" placeholder="البحث في المديرين"/>
                        </div>
                        <!--end::Search-->
                    </div>
                    <!--end::Card title-->
                    <!--begin::Card toolbar-->
                    <div class="card-toolbar">
                        <!--begin::Toolbar-->
                        <div class="d-flex justify-content-end" data-kt-customer-table-toolbar="base">
                            <!--begin::Add manager-->
                            <a href="{{ route('admin.health-center-managers.create') }}" class="btn btn-primary">
                                <i class="ki-duotone ki-plus fs-2"></i>
                                إضافة مدير جديد
                            </a>
                            <!--end::Add manager-->
                        </div>
                        <!--end::Toolbar-->
                    </div>
                    <!--end::Card toolbar-->
                </div>
                <!--end::Card header-->
                <!--begin::Card body-->
                <div class="card-body pt-0">

                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <!--begin::Table-->
                    <div class="table-responsive">
                        <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_customers_table">
                            <!--begin::Table head-->
                            <thead>
                                <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                                    <th class="min-w-125px">#</th>
                                    <th class="min-w-200px">الاسم الكامل</th>
                                    <th class="min-w-150px">البريد الإلكتروني</th>
                                    <th class="min-w-150px">رقم الهاتف</th>
                                    <th class="min-w-200px">الوحدة الصحية</th>
                                    <th class="min-w-100px">الحالة</th>
                                    <th class="min-w-150px">تاريخ الإنشاء</th>
                                    <th class="text-end min-w-100px">الإجراءات</th>
                                </tr>
                            </thead>
                            <!--end::Table head-->
                            <!--begin::Table body-->
                            <tbody class="fw-semibold text-gray-600">
                                @forelse($managers as $key => $manager)
                                <tr>
                                    <!--begin::ID-->
                                    <td>
                                        {{ $managers->firstItem() + $key }}
                                    </td>
                                    <!--end::ID-->
                                    <!--begin::Name-->
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="symbol symbol-50px me-3">
                                                <div class="symbol-label bg-light-info">
                                                    <i class="ki-duotone ki-profile-user fs-2 text-info">
                                                        <span class="path1"></span>
                                                        <span class="path2"></span>
                                                        <span class="path3"></span>
                                                        <span class="path4"></span>
                                                    </i>
                                                </div>
                                            </div>
                                            <div class="d-flex flex-column">
                                                <span class="text-gray-800 fw-bold fs-6">{{ $manager->full_name }}</span>
                                                <span class="text-muted fs-7">{{ $manager->national_id }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <!--end::Name-->
                                    <!--begin::Email-->
                                    <td>
                                        <span class="text-gray-800">{{ $manager->email ?? 'غير محدد' }}</span>
                                    </td>
                                    <!--end::Email-->
                                    <!--begin::Phone-->
                                    <td>
                                        <span class="text-gray-800">{{ $manager->phone }}</span>
                                    </td>
                                    <!--end::Phone-->
                                    <!--begin::Health Center-->
                                    <td>
                                        @if($manager->healthCenter)
                                            <div class="d-flex flex-column">
                                                <span class="text-gray-800 fw-bold">{{ $manager->healthCenter->name }}</span>
                                                <span class="text-muted fs-7">{{ $manager->healthCenter->governorate->name ?? '' }}</span>
                                            </div>
                                        @else
                                            <span class="badge badge-light-warning">غير مُعين</span>
                                        @endif
                                    </td>
                                    <!--end::Health Center-->
                                    <!--begin::Status-->
                                    <td>
                                        @if($manager->is_verified)
                                            <span class="badge badge-light-success">نشط</span>
                                        @else
                                            <span class="badge badge-light-danger">غير نشط</span>
                                        @endif
                                    </td>
                                    <!--end::Status-->
                                    <!--begin::Created Date-->
                                    <td>
                                        {{ $manager->created_at ? $manager->created_at->format('Y-m-d') : '-' }}
                                    </td>
                                    <!--end::Created Date-->
                                    <!--begin::Action-->
                                    <td class="text-end">
                                        <a href="#" class="btn btn-sm btn-light btn-active-light-primary btn-flex btn-center" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                            الإجراءات
                                            <i class="ki-duotone ki-down fs-5 ms-1"></i>
                                        </a>
                                        <!--begin::Menu-->
                                        <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-125px py-4" data-kt-menu="true">
                                            <!--begin::Menu item-->
                                            <div class="menu-item px-3">
                                                <a href="{{ route('admin.health-center-managers.show', $manager->id) }}" class="menu-link px-3">
                                                    عرض
                                                </a>
                                            </div>
                                            <!--end::Menu item-->
                                            <!--begin::Menu item-->
                                            <div class="menu-item px-3">
                                                <a href="{{ route('admin.health-center-managers.edit', $manager->id) }}" class="menu-link px-3">
                                                    تعديل
                                                </a>
                                            </div>
                                            <!--end::Menu item-->
                                            <!--begin::Menu item-->
                                            <div class="menu-item px-3">
                                                <form action="{{ route('admin.health-center-managers.toggle-status', $manager->id) }}" method="POST" style="display: inline;">
                                                    @csrf
                                                    <button type="submit" class="menu-link px-3 border-0 bg-transparent text-start w-100">
                                                        {{ $manager->is_active ? 'تعطيل' : 'تفعيل' }}
                                                    </button>
                                                </form>
                                            </div>
                                            <!--end::Menu item-->
                                            <!--begin::Menu item-->
                                            <div class="menu-item px-3">
                                                <a href="#" class="menu-link px-3 text-danger" onclick="deleteManager({{ $manager->id }})">
                                                    حذف
                                                </a>
                                            </div>
                                            <!--end::Menu item-->
                                        </div>
                                        <!--end::Menu-->
                                    </td>
                                    <!--end::Action-->
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center py-10">
                                        <div class="d-flex flex-column align-items-center">
                                            <i class="ki-duotone ki-file-deleted fs-4x text-gray-400 mb-3">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                            </i>
                                            <span class="text-gray-400 fs-6">لا توجد مديرين للوحدات الصحية</span>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                            <!--end::Table body-->
                        </table>
                    </div>
                    <!--end::Table-->

                    <!--begin::Pagination-->
                    @if($managers->hasPages())
                    <div class="d-flex justify-content-center">
                        {{ $managers->links() }}
                    </div>
                    @endif
                    <!--end::Pagination-->
                </div>
                <!--end::Card body-->
            </div>
            <!--end::Card-->
        </div>
        <!--end::Container-->
    </div>
    <!--end::Post-->

    <!-- Delete Form -->
    <form id="delete-form" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>
@endsection

@section('scripts')
<script>
function deleteManager(id) {
    Swal.fire({
        title: 'هل أنت متأكد؟',
        text: "سيتم حذف هذا المدير نهائياً!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'نعم، احذف!',
        cancelButtonText: 'إلغاء',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.getElementById('delete-form');
            form.action = `/admin/health-center-managers/${id}`;
            form.submit();
        }
    });
}

// Search functionality
document.querySelector('[data-kt-customer-table-filter="search"]').addEventListener('keyup', function(e) {
    const searchText = e.target.value.toLowerCase();
    const tableRows = document.querySelectorAll('#kt_customers_table tbody tr');

    tableRows.forEach(row => {
        const text = row.textContent.toLowerCase();
        if (text.includes(searchText)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
});
</script>
@endsection

@extends('layouts.master')
@section('title')
    إدارة اللقاحات
@endsection

@section('page-header')
    <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
        <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">
            إدارة لقاحات الأطفال
        </h1>
        <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
            <li class="breadcrumb-item text-muted">
                <a href="{{route('admin.dashboard')}}" class="text-muted text-hover-primary">الصفحة الرئيسية</a>
            </li>
            <li class="breadcrumb-item">
                <span class="bullet bg-gray-200 w-5px h-2px"></span>
            </li>
            <li class="breadcrumb-item text-dark">اللقاحات</li>
        </ul>
    </div>
@endsection

@section('content')
    <div class="post d-flex flex-column-fluid" id="kt_post">
        <div id="kt_content_container" class="container-xxl">
            
            {{-- Header Actions --}}
            <div class="card mb-6">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h3 class="card-title">قائمة اللقاحات</h3>
                        <p class="text-muted mb-0">إجمالي اللقاحات: {{ $vaccines->total() }}</p>
                    </div>
                    <a href="{{ route('admin.vaccines.create') }}" class="btn btn-primary">
                        <i class="ki-duotone ki-plus fs-2"></i>
                        إضافة لقاح جديد
                    </a>
                </div>
            </div>

            {{-- Success Message --}}
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            {{-- Vaccines Table --}}
            <div class="card">
                <div class="card-body pt-6">
                    <div class="table-responsive">
                        <table class="table table-row-bordered table-row-gray-100 align-middle gs-0 gy-3">
                            <thead>
                                <tr class="fw-bold text-muted bg-light">
                                    <th class="min-w-200px">اسم اللقاح</th>
                                    <th class="min-w-120px">العمر المناسب</th>
                                    <th class="min-w-100px">عدد الجرعات</th>
                                    <th class="min-w-100px">الفترة بين الجرعات</th>
                                    <th class="min-w-100px">الوحدات المتوفر بها</th>
                                    <th class="min-w-100px">الحالة</th>
                                    <th class="min-w-100px text-end">الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($vaccines as $vaccine)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="symbol symbol-50px me-3">
                                                <span class="symbol-label bg-light-success">
                                                    <i class="ki-duotone ki-shield-tick fs-2x text-success">
                                                        <span class="path1"></span>
                                                        <span class="path2"></span>
                                                    </i>
                                                </span>
                                            </div>
                                            <div class="d-flex flex-column">
                                                <span class="text-gray-800 fw-bold">{{ $vaccine->name }}</span>
                                                <span class="text-muted fs-7">{{ Str::limit($vaccine->description, 50) }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge badge-light-info">
                                            من {{ $vaccine->age_months_min }} إلى {{ $vaccine->age_months_max }} شهر
                                        </span>
                                    </td>
                                    <td>
                                        <span class="text-gray-800 fw-bold">{{ $vaccine->doses_required }} جرعة</span>
                                    </td>
                                    <td>
                                        @if($vaccine->interval_days)
                                            <span class="text-gray-600">{{ $vaccine->interval_days }} يوم</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge badge-light-primary">{{ $vaccine->health_centers_count }} وحدة</span>
                                    </td>
                                    <td>
                                        @if($vaccine->is_active)
                                            <span class="badge badge-light-success">نشط</span>
                                        @else
                                            <span class="badge badge-light-danger">معطل</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <a href="#" class="btn btn-light btn-active-light-primary btn-sm" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                            الإجراءات
                                            <i class="ki-duotone ki-down fs-5 m-0"></i>
                                        </a>
                                        
                                        <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-200px py-4" data-kt-menu="true">
                                            <div class="menu-item px-3">
                                                <a href="{{ route('admin.vaccines.edit', $vaccine->id) }}" class="menu-link px-3">
                                                    <i class="ki-duotone ki-pencil fs-5 me-2"></i>
                                                    تعديل
                                                </a>
                                            </div>
                                            
                                            <div class="menu-item px-3">
                                                <a href="#" class="menu-link px-3" onclick="showDistributeModal({{ $vaccine->id }}, '{{ $vaccine->name }}')">
                                                    <i class="ki-duotone ki-send fs-5 me-2"></i>
                                                    توزيع على الوحدات
                                                </a>
                                            </div>
                                            
                                            <div class="separator my-2"></div>
                                            
                                            <div class="menu-item px-3">
                                                <a href="#" class="menu-link px-3 text-danger" onclick="confirmDelete({{ $vaccine->id }}, '{{ $vaccine->name }}')">
                                                    <i class="ki-duotone ki-trash fs-5 me-2"></i>
                                                    حذف
                                                </a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted py-10">
                                        لا توجد لقاحات مضافة
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination --}}
                    <div class="d-flex justify-content-between align-items-center mt-5">
                        <div class="text-muted">
                            عرض {{ $vaccines->firstItem() ?? 0 }} إلى {{ $vaccines->lastItem() ?? 0 }} من أصل {{ $vaccines->total() }}
                        </div>
                        {{ $vaccines->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Distribute Modal --}}
    <div class="modal fade" id="distributeModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <form id="distributeForm" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">توزيع اللقاح على الوحدات الصحية</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p class="mb-5">توزيع لقاح: <strong id="vaccineName"></strong></p>
                        
                        <div id="assignmentsContainer">
                            <div class="assignment-row mb-4 border rounded p-3">
                                <div class="row">
                                    <div class="col-md-8 mb-3">
                                        <label class="required fs-6 fw-bold mb-2">الوحدة الصحية</label>
                                        <select name="assignments[0][health_center_id]" class="form-select" required>
                                            <option value="">اختر الوحدة</option>
                                            @foreach(\App\Models\HealthCenter::with('city')->get() as $center)
                                                <option value="{{ $center->id }}">{{ $center->name }} - {{ $center->city->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="required fs-6 fw-bold mb-2">الكمية</label>
                                        <input type="number" name="assignments[0][stock]" class="form-control" min="0" placeholder="0" required>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <button type="button" class="btn btn-sm btn-light-primary" onclick="addAssignment()">
                            <i class="ki-duotone ki-plus fs-3"></i>
                            إضافة وحدة أخرى
                        </button>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                        <button type="submit" class="btn btn-primary">توزيع اللقاح</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Delete Form --}}
    <form id="deleteForm" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>
@endsection

@section('scripts')
<script>
function showDistributeModal(vaccineId, vaccineName) {
    document.getElementById('vaccineName').textContent = vaccineName;
    document.getElementById('distributeForm').action = `/admin/vaccines/${vaccineId}/assign`;
    
    const modal = new bootstrap.Modal(document.getElementById('distributeModal'));
    modal.show();
}

function confirmDelete(id, name) {
    Swal.fire({
        title: 'هل أنت متأكد؟',
        text: `سيتم حذف اللقاح: ${name}`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'نعم، احذف!',
        cancelButtonText: 'إلغاء',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.getElementById('deleteForm');
            form.action = `/admin/vaccines/${id}`;
            form.submit();
        }
    });
}
</script>
@endsection
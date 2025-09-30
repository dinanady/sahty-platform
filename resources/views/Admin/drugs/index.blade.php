@extends('layouts.master')
@section('title')
    إدارة الأدوية
@endsection

@section('page-header')
    <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
        <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">
            إدارة الأدوية
        </h1>
        <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
            <li class="breadcrumb-item text-muted">
                <a href="{{route('admin.dashboard')}}" class="text-muted text-hover-primary">الصفحة الرئيسية</a>
            </li>
            <li class="breadcrumb-item">
                <span class="bullet bg-gray-200 w-5px h-2px"></span>
            </li>
            <li class="breadcrumb-item text-dark">الأدوية</li>
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
                        <h3 class="card-title">قائمة الأدوية</h3>
                        <p class="text-muted mb-0">إجمالي الأدوية: {{ $drugs->total() }}</p>
                    </div>
                    <a href="{{ route('admin.drugs.create') }}" class="btn btn-primary">
                        <i class="ki-duotone ki-plus fs-2"></i>
                        إضافة دواء جديد
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

            {{-- Search and Filter Card --}}
            <div class="card mb-6">
                <div class="card-body">
                    <form method="GET" action="{{ route('admin.drugs.index') }}" id="searchForm">
                        <div class="row g-3 align-items-end">
                            {{-- Search Input --}}
                            <div class="col-md-6">
                                <label class="form-label fw-bold">البحث عن دواء</label>
                                <div class="position-relative">
                                    <i class="ki-duotone ki-magnifier fs-3 position-absolute top-50 translate-middle-y ms-4">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                    </i>
                                    <input type="text" 
                                           name="search" 
                                           class="form-control form-control-solid ps-12" 
                                           placeholder="ابحث بالاسم أو الاسم العلمي..." 
                                           value="{{ request('search') }}">
                                </div>
                            </div>

                            {{-- Category Filter --}}
                            <div class="col-md-4">
                                <label class="form-label fw-bold">التصنيف</label>
                                <select name="category" class="form-select form-select-solid">
                                    <option value="">جميع التصنيفات</option>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>
                                            {{ $cat }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Action Buttons --}}
                            <div class="col-md-2">
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="ki-duotone ki-magnifier fs-2"></i>
                                        بحث
                                    </button>
                                    @if(request('search') || request('category'))
                                        <a href="{{ route('admin.drugs.index') }}" 
                                           class="btn btn-light-secondary" 
                                           title="مسح الفلاتر">
                                            <i class="ki-duotone ki-cross fs-2"></i>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Search Results Info --}}
            @if(request('search') || request('category'))
                <div class="alert alert-info d-flex align-items-center mb-6">
                    <i class="ki-duotone ki-information-5 fs-2hx text-info me-4">
                        <span class="path1"></span>
                        <span class="path2"></span>
                        <span class="path3"></span>
                    </i>
                    <div class="d-flex flex-column">
                        <h4 class="mb-1">نتائج البحث والفلترة</h4>
                        <span>
                            تم العثور على <strong>{{ $drugs->total() }}</strong> نتيجة
                            @if(request('search'))
                                للبحث عن: <strong>"{{ request('search') }}"</strong>
                            @endif
                            @if(request('category'))
                                @if(request('search')) و @endif
                                في التصنيف: <strong>{{ request('category') }}</strong>
                            @endif
                        </span>
                    </div>
                </div>
            @endif

            {{-- Drugs Table --}}
            <div class="card">
                <div class="card-body pt-6">
                    <div class="table-responsive">
                        <table class="table table-row-bordered table-row-gray-100 align-middle gs-0 gy-3">
                            <thead>
                                <tr class="fw-bold text-muted bg-light">
                                    <th class="min-w-150px">الاسم التجاري</th>
                                    <th class="min-w-150px">الاسم العلمي</th>
                                    <th class="min-w-100px">التصنيف</th>
                                    <th class="min-w-100px">الشكل الدوائي</th>
                                    <th class="min-w-100px">السعر</th>
                                    <th class="min-w-100px">الوحدات</th>
                                    <th class="min-w-100px">الحالة</th>
                                    <th class="min-w-100px text-end">الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($drugs as $drug)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="symbol symbol-45px me-3">
                                                <span class="symbol-label bg-light-primary">
                                                    <i class="ki-duotone ki-capsule fs-2x text-primary">
                                                        <span class="path1"></span>
                                                        <span class="path2"></span>
                                                    </i>
                                                </span>
                                            </div>
                                            <span class="text-gray-800 fw-bold">{{ $drug->name }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="text-gray-600">{{ $drug->scientific_name ?? '-' }}</span>
                                    </td>
                                    <td>
                                        <span class="badge badge-light-info">{{ $drug->category ?? 'غير محدد' }}</span>
                                    </td>
                                    <td>
                                        <span class="text-gray-600">{{ $drug->dosage_form ?? '-' }}</span>
                                    </td>
                                    <td>
                                        @if($drug->price)
                                            <span class="text-gray-800 fw-bold">{{ number_format($drug->price, 2) }} ج.م</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge badge-light-primary">{{ $drug->health_centers_count }} وحدة</span>
                                    </td>
                                    <td>
                                        @if($drug->is_active)
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
                                                <a href="{{ route('admin.drugs.edit', $drug->id) }}" class="menu-link px-3">
                                                    <i class="ki-duotone ki-pencil fs-5 me-2"></i>
                                                    تعديل
                                                </a>
                                            </div>
                                            
                                            <div class="menu-item px-3">
                                                <a href="#" class="menu-link px-3" onclick="showDistributeModal({{ $drug->id }}, '{{ $drug->name }}')">
                                                    <i class="ki-duotone ki-send fs-5 me-2"></i>
                                                    توزيع على وحدة صحية
                                                </a>
                                            </div>
                                            
                                            <div class="separator my-2"></div>
                                            
                                            <div class="menu-item px-3">
                                                <a href="#" class="menu-link px-3 text-danger" onclick="confirmDelete({{ $drug->id }}, '{{ $drug->name }}')">
                                                    <i class="ki-duotone ki-trash fs-5 me-2"></i>
                                                    حذف
                                                </a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center text-muted py-10">
                                        @if(request('search') || request('category'))
                                            <i class="ki-duotone ki-file-deleted fs-5x text-muted mb-5">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                            </i>
                                            <p class="fw-bold fs-4">لا توجد نتائج للبحث</p>
                                            <p>حاول البحث بكلمات مختلفة أو اختر تصنيف آخر</p>
                                            <a href="{{ route('admin.drugs.index') }}" class="btn btn-sm btn-light-primary mt-3">
                                                <i class="ki-duotone ki-arrow-left fs-3"></i>
                                                عرض جميع الأدوية
                                            </a>
                                        @else
                                            <i class="ki-duotone ki-capsule fs-5x text-muted mb-5">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                            </i>
                                            <p class="fw-bold fs-4">لا توجد أدوية مضافة</p>
                                            <a href="{{ route('admin.drugs.create') }}" class="btn btn-sm btn-primary mt-3">
                                                <i class="ki-duotone ki-plus fs-3"></i>
                                                إضافة دواء جديد
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination --}}
                    @if($drugs->hasPages())
                        <div class="d-flex justify-content-between align-items-center mt-5">
                            <div class="text-muted">
                                عرض {{ $drugs->firstItem() ?? 0 }} إلى {{ $drugs->lastItem() ?? 0 }} من أصل {{ $drugs->total() }}
                            </div>
                            {{ $drugs->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Distribute Modal --}}
    <div class="modal fade" id="distributeModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form id="distributeForm" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">توزيع الدواء</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p class="mb-5">توزيع دواء: <strong id="drugName"></strong></p>
                        
                        <div class="mb-5">
                            <label class="required fs-6 fw-bold mb-2">الوحدة الصحية</label>
                            <select name="health_center_id" class="form-select" data-control="select2" required>
                                <option value="">اختر الوحدة الصحية</option>
                                @foreach(\App\Models\HealthCenter::all() as $center)
                                    <option value="{{ $center->id }}">{{ $center->name }} - {{ $center->city->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-5">
                            <label class="required fs-6 fw-bold mb-2">الكمية</label>
                            <input type="number" name="stock" class="form-control" min="0" placeholder="أدخل الكمية" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                        <button type="submit" class="btn btn-primary">توزيع</button>
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
function showDistributeModal(drugId, drugName) {
    document.getElementById('drugName').textContent = drugName;
    document.getElementById('distributeForm').action = `/admin/drugs/${drugId}/assign`;
    
    const modal = new bootstrap.Modal(document.getElementById('distributeModal'));
    modal.show();
}

function confirmDelete(id, name) {
    Swal.fire({
        title: 'هل أنت متأكد؟',
        text: `سيتم حذف الدواء: ${name}`,
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
            form.action = `/admin/drugs/${id}`;
            form.submit();
        }
    });
}
</script>
@endsection
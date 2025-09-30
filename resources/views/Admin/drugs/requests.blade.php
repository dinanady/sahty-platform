@extends('layouts.master')

@section('title', 'الطلبات في الانتظار')

    @section('css')
        <style>
            table td,
            table th {
                text-align: right !important;
                vertical-align: middle;
            }
        </style>

@endsection
@section('content')
    <div class="container-fluid px-4">
        <!-- Header Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h1 class="h3 mb-0 text-primary">
                            طلبات الأدوية من المراكز الصحية
                        </h1>
                        <p class="text-muted mb-0">إدارة وموافقة طلبات الأدوية المقدمة من المراكز الصحية</p>
                    </div>
                    <div class="badge bg-info fs-6">
                        {{ $drugs->count() }} طلب في الانتظار
                    </div>
                </div>
            </div>
        </div>

        <!-- Success Alert -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show shadow-sm mb-4" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Main Content Card -->
        <div class="card shadow-sm border-0">
            <div class="card-header bg-gradient-primary text-white py-3">
                <h5 class="mb-0">
                    <i class="fas fa-list-check me-2"></i>
                    قائمة الطلبات
                </h5>
            </div>

            <div class="card-body p-0">
                @if($drugs->count() > 0)
                    <div class="table-responsive">
                        <table id="requestedDrugsTable" class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="py-3 px-4 fw-semibold">
                                        الاسم التجاري
                                    </th>
                                    <th class="py-3 px-4 fw-semibold">
                                        الاسم العلمي
                                    </th>
                                    <th class="py-3 px-4 fw-semibold">
                                        المركز الصحي
                                    </th>
                                    <th class="py-3 px-4 fw-semibold">
                                        تاريخ التقديم
                                    </th>
                                    <th class="py-3 px-4 fw-semibold text-center">
                                        الإجراء
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($drugs as $drug)
                                    <tr class="border-bottom">
                                        <td class="py-3 px-4">
                                            <div class="fw-semibold text-dark">{{ $drug->name }}</div>
                                        </td>
                                        <td class="py-3 px-4">
                                            <span class="text-muted">{{ $drug->scientific_name }}</span>
                                        </td>
                                        <td class="py-3 px-4">
                                            <span class="badge bg-light text-dark border">
                                                <i class="fas fa-building me-1"></i>
                                                {{ $drug->submittedByCenter?->name ?? 'غير محدد' }}
                                            </span>
                                        </td>
                                        <td class="py-3 px-4">
                                            <small class="text-muted">
                                                <i class="fas fa-clock me-1"></i>
                                                {{ $drug->created_at->format('Y-m-d') }}
                                            </small>
                                        </td>
                                        <td class="py-3 px-4 text-center">
                                            <div class="btn-group" role="group">
                                                <!-- زر الموافقة -->
                                                <form action="{{ route('admin.approve-drugs.update', $drug) }}" method="POST"
                                                    class="d-inline approve-form">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="hidden" name="approval_status" value="approved">
                                                    <button type="submit" class="btn btn-success btn-sm px-3">
                                                        <i class="fas fa-check me-1"></i> موافقة
                                                    </button>
                                                </form>

                                                <!-- زر الرفض -->
                                                <form action="{{ route('admin.approve-drugs.update', $drug) }}" method="POST"
                                                    class="d-inline reject-form">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="hidden" name="approval_status" value="rejected">
                                                    <button type="submit" class="btn btn-outline-danger btn-sm px-3 ms-2">
                                                        <i class="fas fa-times me-1"></i> رفض
                                                    </button>
                                                </form>

                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <!-- Empty State -->
                    <div class="text-center py-5">
                        <div class="mb-3">
                            <i class="fas fa-inbox fa-3x text-muted"></i>
                        </div>
                        <h5 class="text-muted">لا توجد طلبات في الانتظار</h5>
                        <p class="text-muted mb-0">سيتم عرض الطلبات الجديدة هنا عند وصولها</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

@endsection
@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11">
    </script>
    <script>
        $('#requestedDrugsTable').DataTable({
                language: {
                    url: "//cdn.datatables.net/plug-ins/1.13.6/i18n/ar.json"
                },
                pageLength: 10,
                lengthMenu: [5, 10, 25, 50],
            });

        // Initialize tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })

        // Auto hide alerts after 5 seconds
        setTimeout(function () {
            $('.alert').fadeOut('slow');
        }, 5000);

        document.querySelectorAll('.approve-form').forEach(form => {
            form.addEventListener('submit', function (e) {
                e.preventDefault();
                Swal.fire({
                    title: 'تأكيد الموافقة',
                    text: "هل أنت متأكد من الموافقة على هذا الطلب؟",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#198754',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'نعم، موافقة',
                    cancelButtonText: 'إلغاء'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });

        document.querySelectorAll('.reject-form').forEach(form => {
            form.addEventListener('submit', function (e) {
                e.preventDefault();
                Swal.fire({
                    title: 'تأكيد الرفض',
                    text: "هل أنت متأكد من رفض هذا الطلب؟",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'نعم، رفض',
                    cancelButtonText: 'إلغاء'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });

    </script>
@endsection

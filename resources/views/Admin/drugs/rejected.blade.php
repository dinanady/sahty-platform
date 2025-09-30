@extends('layouts.master')

@section('title', 'الطلبات المرفوضة')

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
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h3 text-danger">
                الطلبات المرفوضة
            </h1>
            <p class="text-muted">جميع الطلبات التي تم رفضها</p>
        </div>
    </div>

    <!-- Main Card -->
    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            @if($drugs->count() > 0)
                <div class="table-responsive">
                    <table id="rejectedDrugsTable" class="table table-striped table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>الاسم التجاري</th>
                                <th>الاسم العلمي</th>
                                <th>المركز الصحي</th>
                                <th>تاريخ الرفض</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($drugs as $drug)
                            <tr>
                                <td>{{ $drug->name }}</td>
                                <td>{{ $drug->scientific_name }}</td>
                                <td>{{ $drug->submittedByCenter?->name ?? 'غير محدد' }}</td>
                                <td>{{ $drug->approved_at?->format('Y-m-d') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <!-- Empty State -->
                <div class="text-center py-5">
                    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">لا توجد طلبات مرفوضة</h5>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('scripts')
    <!-- DataTables CSS & JS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

    <script>
        $(document).ready(function () {
            $('#rejectedDrugsTable').DataTable({
                language: {
                    url: "//cdn.datatables.net/plug-ins/1.13.6/i18n/ar.json"
                },
                pageLength: 10,
                lengthMenu: [5, 10, 25, 50],
            });
        });
    </script>
@endsection

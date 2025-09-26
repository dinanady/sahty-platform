@extends('layouts.master')

@section('title')
    إدارة الحسابات
@endsection

@section('css')
    <style>
        #datatable {
            width: 100%;
        }
        .table th, .table td {
            vertical-align: middle;
        }
        .table .actions-column {
            min-width: 100px;
        }
        .table .btn-group {
            display: flex;
            gap: 5px;
        }
        .btn-custom {
            height: 30px;
            line-height: 1.5;
            padding: 0 10px;
            font-size: 0.875rem;
            display: flex;
            align-items: center;
        }
        .pagination .page-link {
            color: #198754;
        }
        .pagination .page-link:hover {
            background-color: #e2f0d4;
            color: #155724;
        }
        .pagination .page-item.active .page-link {
            background-color: #198754;
            color: white;
        }
        .pagination .page-item.disabled .page-link {
            color: #6c757d;
        }
    </style>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/dataTables.bootstrap5.min.css">
@endsection

@section('page-header')
    <div class="page-title d-flex flex-column gap-1 me-3 mb-2">
        <ul class="breadcrumb breadcrumb-separatorless fw-semibold mb-6">
            <li class="breadcrumb-item text-gray-700 fw-bold lh-1">إدارة الحسابات</li>
            <li class="breadcrumb-item">
                <i class="ki-duotone ki-left fs-4 text-gray-700 mx-n1"></i>
            </li>
            <li class="breadcrumb-item text-gray-700 fw-bold lh-1">الحسابات</li>
        </ul>
        <h1 class="page-heading d-flex flex-column justify-content-center text-dark fw-bolder fs-1 lh-0">الحسابات</h1>
    </div>
    {{-- <div class="d-flex gap-3">
        <a href="{{ route('accounts.create') }}" class="btn btn-sm btn-success px-4 py-3">إضافة حساب</a>
    </div> --}}
@endsection

@section('content')
<div id="kt_app_content_container" class="app-container container-fluid">
    <div class="row">
        <div class="col-md-12 mb-3">
            <!-- نموذج التصفية -->
            {{-- <form method="GET" action="{{ route('accounts.index') }}" class="d-flex gap-3 mb-4">
                <div class="form-group">
                    <label for="province_id">اختر المحافظة:</label>
                    <select name="province_id" id="province_id" class="form-control">
                        <option value="">جميع المحافظات</option>
                        @foreach($availableProvinces as $province)
                            <option value="{{ $province->id }}" {{ request('province_id') == $province->id ? 'selected' : '' }}>
                                {{ $province->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn btn-success align-self-end">بحث</button>
            </form> --}}
        {{-- </div>
        <div class="col-md-12 mb-30">
            <div class="card card-statistics h-100 bg-light">
                <div class="card-body">
                    <div class="container">
                        <div class="table-responsive">
                            <table id="datatable" class="table table-responsive table-bordered table-striped border-light mb-4">
                                <thead class="table-success">
                                    <tr>
                                        <th>التاريخ</th>
                                        <th>المبلغ</th>
                                        <th>العملة</th>
                                        <th>النوع</th>
                                        <th>التفاصيل</th>
                                        <th>المحافظة</th>
                                        <th>من أين</th>
                                        <th>رقم الإيصال</th>
                                        <th>الملاحظات</th>
                                        <th>الإجراءات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($accounts as $account)
                                        <tr>
                                            <td>{{ \Carbon\Carbon::parse($account->date)->format('d-m-Y') }}</td>
                                            <td>
                                                @if ($account->currency == 'دولار')
                                                    {{ number_format($account->amount, 0) }} $
                                                @elseif ($account->currency == 'دينار')
                                                    {{ number_format($account->amount * 1000, 0) }} د.ع
                                                @endif
                                            </td>
                                            <td>{{ $account->currency }}</td>
                                            <td>{{ $account->type }}</td>
                                            <td>
                                                @if($account->accountDetails->count() > 0)
                                                    <table class="table table-sm mb-0">
                                                        <thead>
                                                            <tr>
                                                                <th>التفاصيل</th>
                                                                <th>المبلغ</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach($account->accountDetails as $accountDetail)
                                                                <tr>
                                                                    <td>{{ $accountDetail->detail->name ?? 'غير معروف' }}</td>
                                                                    <td>{{ $accountDetail->amount }}</td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                @elseif($account->detail)
                                                    {{ $account->detail->name }}
                                                @else
                                                    لا توجد تفاصيل
                                                @endif
                                            </td>
                                            <td>{{ $account->province->name ?? 'غير متوفر' }}</td>
                                            <td>{{ $account->person_name ?? '-' }}</td>
                                            <td>{{ $account->receipt_number }}</td>
                                            <td>{{ $account->notes }}</td>
                                            <td>
                                                <div class="btn-group">
                                                    @can("تعديل الحسابات")
                                                        <a href="{{ route('accounts.edit', $account->id) }}" class="btn btn-sm btn-success btn-custom">تعديل</a>
                                                    @endcan
                                                    @can("حذف الحسابات")
                                                        <button type="button" class="btn btn-success btn-custom" data-bs-toggle="modal" data-bs-target="#deleteAccountModal" data-account-id="{{ $account->id }}">
                                                            حذف
                                                        </button>
                                                    @endcan
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> --}}
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteAccountModal" tabindex="-1" role="dialog" aria-labelledby="deleteAccountModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteAccountModalLabel">تأكيد الحذف</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="delete-account-form" method="POST" action="">
                @csrf
                @method('DELETE')
                <div class="modal-body">
                    هل أنت متأكد أنك تريد حذف هذا الحساب؟
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-success">حذف</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('javascript')
    <script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.5/js/dataTables.bootstrap5.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#datatable').DataTable({
                "language": {
                    "sProcessing": "جارٍ التحميل...",
                    "sLengthMenu": "أظهر _MENU_ مدخلات",
                    "sZeroRecords": "لم يعثر على أية سجلات",
                    "sInfo": "إظهار _START_ إلى _END_ من أصل _TOTAL_ مدخل",
                    "sInfoEmpty": "يعرض 0 إلى 0 من أصل 0 سجل",
                    "sInfoFiltered": "(منتقاة من مجموع _MAX_ مُدخل)",
                    "sSearch": "ابحث:",
                    "oPaginate": {
                        "sFirst": "الأول",
                        "sPrevious": "السابق",
                        "sNext": "التالي",
                        "sLast": "الأخير"
                    }
                }
            });

            var deleteAccountModal = document.getElementById('deleteAccountModal');
            deleteAccountModal.addEventListener('show.bs.modal', function(event) {
                var button = event.relatedTarget;
                var accountId = button.getAttribute('data-account-id');
                var form = document.getElementById('delete-account-form');
                form.action = '/accounts/' + accountId;
            });
        });
    </script>
@endsection
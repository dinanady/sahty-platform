@extends('layouts.health-center.master')

@section('title', 'الأدوية المعلقة - المركز الصحي')

@section('content')
    <div class="container-fluid py-4">
        <!-- عنوان الصفحة -->
        <div class="row mb-4">
            <div class="col-md-6">
                <h2 class="text-warning">الأدوية المعلقة</h2>
                <p class="text-muted">الأدوية المرسلة للمراجعة الحكومية</p>
            </div>
            <div class="col-md-6 text-end">
                <a href="{{ route('health-center.drugs.create') }}" class="btn btn-success me-2">
                    <i class="fas fa-plus me-1"></i>إضافة دواء جديد
                </a>
                <a href="{{ route('health-center.drugs.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-right me-1"></i>العودة لقائمة الأدوية
                </a>
            </div>
        </div>

        <!-- إحصائيات سريعة -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card bg-warning text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h4 class="card-title">{{ $pendingDrugs->total() }}</h4>
                                <p class="card-text mb-0">أدوية في الانتظار</p>
                            </div>
                            <i class="fas fa-hourglass-half fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-info text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h4 class="card-title">
                                    {{ $pendingDrugs->where('created_at', '>=', now()->subDays(7))->count() }}</h4>
                                <p class="card-text mb-0">مرسلة هذا الأسبوع</p>
                            </div>
                            <i class="fas fa-calendar-week fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-secondary text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h4 class="card-title">
                                    {{ $pendingDrugs->where('created_at', '<', now()->subDays(30))->count() }}</h4>
                                <p class="card-text mb-0">أكثر من شهر</p>
                            </div>
                            <i class="fas fa-clock fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- قائمة الأدوية المعلقة -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">الأدوية في انتظار المراجعة</h5>
                <div>
                    <a href="{{ route('health-center.drugs.rejected') }}" class="btn btn-outline-danger btn-sm">
                        <i class="fas fa-times-circle me-1"></i>الأدوية المرفوضة
                    </a>
                </div>
            </div>
            <div class="card-body p-0">
                @if($pendingDrugs->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>اسم الدواء</th>
                                    <th>الاسم العلمي</th>
                                    <th>الفئة</th>
                                    <th>الشركة المصنعة</th>
                                    <th>الكمية</th>
                                    <th>تاريخ الإرسال</th>
                                    <th>مدة الانتظار</th>
                                    <th>الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pendingDrugs as $drug)
                                    @php
                                        $centerDrug = $drug->healthCenters->first();
                                        $daysPending = $drug->created_at->diffInRealDays(now());
                                    @endphp
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <strong>{{ $drug->name }}</strong>
                                                <span class="badge bg-warning ms-2">
                                                    <i class="fas fa-hourglass-half"></i>
                                                </span>
                                            </div>
                                        </td>
                                        <td class="text-muted">{{ $drug->scientific_name ?: 'غير محدد' }}</td>
                                        <td>
                                            @if($drug->category)
                                                <span class="badge bg-secondary">{{ $drug->category }}</span>
                                            @else
                                                <span class="text-muted">غير محدد</span>
                                            @endif
                                        </td>
                                        <td>{{ $drug->manufacturer ?: 'غير محدد' }}</td>
                                        <td>
                                            <span class="badge bg-info">{{ $centerDrug->pivot->stock }} وحدة</span>
                                        </td>
                                        <td>{{ $drug->created_at->format('Y/m/d') }}</td>
                                        <td>
                                           @if($daysPending < 7)
                                                <span class="badge bg-success">{{ number_format($daysPending, 3) }} يوم</span>
                                            @elseif($daysPending < 30)
                                                <span class="badge bg-warning">{{ number_format($daysPending, 3) }} يوم</span>
                                            @else
                                                <span class="badge bg-danger">{{ number_format($daysPending, 3) }} يوم</span>
                                            @endif

                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ route('health-center.drugs.show', $drug->id) }}"
                                                    class="btn btn-outline-info" title="عرض التفاصيل">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-hourglass-half fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">لا توجد أدوية معلقة</h5>
                        <p class="text-muted">جميع الأدوية المرسلة تمت مراجعتها</p>
                        <a href="{{ route('health-center.drugs.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-1"></i>إضافة دواء جديد
                        </a>
                    </div>
                @endif
            </div>

            @if($pendingDrugs->hasPages())
                <div class="card-footer">
                    {{ $pendingDrugs->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection

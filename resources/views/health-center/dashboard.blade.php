@extends('layouts.health-center.master')

@section('title', 'لوحة التحكم - ' . $healthCenter->name)

@section('css')
    <style>
        #doctorTabs .nav-link {
            cursor: pointer;
        }

        .border-right-primary {
            border-right: 0.25rem solid #4e73df !important;
        }

        .border-right-success {
            border-right: 0.25rem solid #1cc88a !important;
        }

        .border-right-info {
            border-right: 0.25rem solid #36b9cc !important;
        }

        .border-right-warning {
            border-right: 0.25rem solid #f6c23e !important;
        }

        .text-gray-800 {
            color: #5a5c69 !important;
        }

        .text-gray-300 {
            color: #dddfeb !important;
        }

        .tab-pane {
            display: none;
        }

        .tab-pane.active {
            display: block;
        }
    </style>
@endsection
@section('content')
    <div class="container-fluid" dir="rtl">

        {{-- Header --}}
        <div class="row mb-4">
            <div class="col-12">
                <h1 class="h3 mb-2 text-gray-800">مرحباً، {{ auth()->user()->full_name }}</h1>
                <p class="text-muted">{{ $healthCenter->name }} - {{ $healthCenter->city->name ?? 'غير محدد' }}</p>
            </div>
        </div>

        {{-- الإحصائيات الرئيسية --}}
        <div class="row mb-4">

            {{-- إجمالي الأطباء --}}
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-right-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    إجمالي الأطباء
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total_doctors'] }}</div>
                                <div class="mt-2 text-xs text-success">
                                    <strong>{{ $stats['active_doctors'] }}</strong> نشط
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-user-md fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- إجمالي المواعيد --}}
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-right-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                    إجمالي المواعيد
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total_appointments'] }}</div>
                                <div class="mt-2 text-xs text-primary">
                                    <strong>{{ $appointmentStats['today_scheduled'] }}</strong> اليوم
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-calendar fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- اللقاحات المتوفرة --}}
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-right-info shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                    اللقاحات المتوفرة
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['available_vaccines'] }}</div>
                                <div class="mt-2 text-xs text-muted">
                                    من <strong>{{ $stats['total_vaccines'] }}</strong> لقاح
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-syringe fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- الأدوية المتوفرة --}}
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-right-warning shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                    الأدوية المتوفرة
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['available_drugs'] }}</div>
                                <div class="mt-2 text-xs text-danger">
                                    <strong>{{ $lowStockDrugs->count() }}</strong> مخزون منخفض
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-pills fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="row mb-4">

            {{-- إحصائيات المواعيد --}}
            <div class="col-lg-8 mb-4">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">إحصائيات المواعيد</h6>
                    </div>
                    <div class="card-body">
                        {{-- 4 boxes للحالات --}}
                        <div class="row text-center mb-4">
                            <div class="col-md-3 col-6 mb-3">
                                <div class="border rounded p-3 bg-light">
                                    <h4 class="text-primary font-weight-bold mb-1">{{ $appointmentStats['scheduled'] }}</h4>
                                    <small class="text-muted">مجدول</small>
                                </div>
                            </div>
                            <div class="col-md-3 col-6 mb-3">
                                <div class="border rounded p-3 bg-light">
                                    <h4 class="text-success font-weight-bold mb-1">{{ $appointmentStats['completed'] }}</h4>
                                    <small class="text-muted">مكتمل</small>
                                </div>
                            </div>
                            <div class="col-md-3 col-6 mb-3">
                                <div class="border rounded p-3 bg-light">
                                    <h4 class="text-danger font-weight-bold mb-1">{{ $appointmentStats['cancelled'] }}</h4>
                                    <small class="text-muted">ملغي</small>
                                </div>
                            </div>
                            <div class="col-md-3 col-6 mb-3">
                                <div class="border rounded p-3 bg-light">
                                    <h4 class="text-secondary font-weight-bold mb-1">{{ $appointmentStats['no_show'] }}</h4>
                                    <small class="text-muted">لم يحضر</small>
                                </div>
                            </div>
                        </div>

                        {{-- رسم بياني --}}
                        <div class="mt-4">
                            <h6 class="font-weight-bold text-gray-800 mb-3">المواعيد خلال الأسبوع</h6>
                            <canvas id="weeklyChart" height="80"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            {{-- أكثر اللقاحات طلباً --}}
            <div class="col-lg-4 mb-4">
                <div class="card shadow h-100">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">أكثر اللقاحات طلباً</h6>
                    </div>
                    <div class="card-body">
                        @forelse($topVaccines as $item)
                            <div class="d-flex align-items-center justify-content-between p-2 mb-2 bg-light rounded">
                                <div class="flex-grow-1">
                                    <div class="font-weight-bold text-gray-800">{{ $item->vaccine->name }}</div>
                                    <small class="text-muted">{{ $item->total }} موعد</small>
                                </div>
                                <span class="badge badge-primary badge-pill">{{ $item->total }}</span>
                            </div>
                        @empty
                            <p class="text-center text-muted py-4">لا توجد بيانات</p>
                        @endforelse
                    </div>
                </div>
            </div>

        </div>

        <div class="row mb-4">

            {{-- مواعيد اليوم --}}
            <div class="col-lg-6 mb-4">
                <div class="card shadow h-100">
                    <div class="card-header py-3 d-flex justify-content-between align-items-center">
                        <h6 class="m-0 font-weight-bold text-primary">مواعيد اليوم</h6>
                        <a href="{{ route('health-center.appointments.index') }}" class="btn btn-sm btn-outline-primary">
                            عرض الكل ←
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="list-group list-group-flush">
                            @forelse($todayAppointments as $appointment)
                                <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                                    <div>
                                        <h6 class="mb-1 font-weight-bold">{{ $appointment->child_name }}</h6>
                                        <small class="text-muted">{{ $appointment->vaccine->name }}</small>
                                    </div>
                                    <div class="text-left">
                                        <div class="font-weight-bold text-gray-700 mb-1">
                                            {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}
                                        </div>
                                        <span class="badge badge-primary">جرعة {{ $appointment->dose_number }}</span>
                                    </div>
                                </div>
                            @empty
                                <p class="text-center text-muted py-5">لا توجد مواعيد مجدولة اليوم</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            {{-- تنبيهات المخزون --}}
            <div class="col-lg-6 mb-4">
                <div class="card shadow h-100">
                    <div class="card-header py-3 d-flex justify-content-between align-items-center">
                        <h6 class="m-0 font-weight-bold text-danger">تنبيهات المخزون</h6>
                        <a href="{{ route('health-center.drugs.index') }}" class="btn btn-sm btn-outline-danger">
                            عرض الكل ←
                        </a>
                    </div>
                    <div class="card-body">
                        @forelse($lowStockDrugs as $drug)
                            <div class="alert alert-warning mb-2" role="alert">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="alert-heading mb-0">{{ $drug->name }}</h6>
                                        <small class="text-muted">{{ $drug->category ?? 'غير محدد' }}</small>
                                    </div>
                                    <div class="text-right">
                                        @php
                                            $stock = $drug->healthCenters->first()->pivot->stock ?? 0;
                                        @endphp
                                        <h5 class="mb-0 text-danger font-weight-bold">{{ $stock }}</h5>
                                        <small class="text-muted">متبقي</small>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-5">
                                <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                                <p class="text-muted">جميع المخزونات جيدة</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

        </div>

        {{-- الأطباء النشطين حسب التخصص --}}
        <div class="row mb-4">
            <div class="col-12">
                <div class="card shadow">
                    <div class="card-header py-3 d-flex justify-content-between align-items-center">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-user-md ml-2"></i>
                            الأطباء النشطين ({{ $activeDoctors->flatten()->count() }} طبيب)
                        </h6>
                        <a href="{{ route('health-center.doctors.index') }}" class="btn btn-sm btn-outline-primary">
                            عرض الكل ←
                        </a>
                    </div>
                    <div class="card-body">
                        @if($activeDoctors->isNotEmpty())
                            <!-- Nav Tabs -->
                            <ul class="nav nav-tabs mb-3" id="doctorTabs">
                                @foreach($activeDoctors as $specialty => $doctors)
                                    <li class="nav-item"">
                                                    <a class=" nav-link {{ $loop->first ? 'active' : '' }}"
                                        data-tab="content-{{ $loop->index }}">
                                        <i class="fas fa-stethoscope ml-2"></i>
                                        {{ $specialty }}
                                        <span class="badge badge-light ml-2">{{ $doctors->count() }}</span>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>

                            <!-- Tab Content -->
                            <div class="tab-content">
                                @foreach($activeDoctors as $specialty => $doctors)
                                    <div class="tab-pane {{ $loop->first ? 'active' : '' }}" id="content-{{ $loop->index }}">
                                        <div class="row">
                                            @foreach($doctors as $doctor)
                                                <div class="col-xl-3 col-lg-4 col-md-6 mb-3">
                                                    <div class="card h-100 doctor-card">
                                                        <div class="card-body p-3">
                                                            <div class="d-flex justify-content-between align-items-start mb-2">
                                                                <div class="flex-grow-1">
                                                                    <h6 class="font-weight-bold mb-0 text-primary">
                                                                        <i class="fas fa-user-doctor ml-1 mx-3"></i>
                                                                        {{ $doctor->name }}
                                                                    </h6>
                                                                </div>
                                                                <span class="badge badge-success">
                                                                    <i class="fas fa-check-circle mx-3"></i>
                                                                </span>
                                                            </div>
                                                            <div class="mt-3 doctor-info">
                                                                <div class="d-flex align-items-center mb-2">
                                                                    <i class="fas fa-phone text-primary ml-2 mx-3"></i>
                                                                    <small class="text-muted">{{ $doctor->phone }}</small>
                                                                </div>
                                                                <div class="d-flex align-items-center">
                                                                    <i class="fas fa-calendar-check text-success ml-2 mx-3"></i>
                                                                    <small class="text-muted">{{ $doctor->schedules->count() }} أيام
                                                                        عمل</small>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="fas fa-user-md fa-3x text-muted mb-3"></i>
                                <p class="text-muted">لا يوجد أطباء نشطين حالياً</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

    </div>

@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const tabs = document.querySelectorAll("#doctorTabs .nav-link");
            const panes = document.querySelectorAll(".tab-pane");

            tabs.forEach(tab => {
                tab.addEventListener("click", function () {
                    // إزالة active من الكل
                    tabs.forEach(t => t.classList.remove("active"));
                    panes.forEach(p => p.classList.remove("active"));

                    // تفعيل التاب الحالي
                    this.classList.add("active");
                    const targetId = this.getAttribute("data-tab");
                    document.getElementById(targetId).classList.add("active");
                });
            });

            // رسم بياني للمواعيد الأسبوعية
            const weeklyData = @json($weeklyStats);

            const ctx = document.getElementById('weeklyChart');
            if (ctx) {
                new Chart(ctx.getContext('2d'), {
                    type: 'line',
                    data: {
                        labels: weeklyData.map(d => {
                            const date = new Date(d.date);
                            return date.toLocaleDateString('ar-EG', { weekday: 'short', day: 'numeric' });
                        }),
                        datasets: [
                            {
                                label: 'إجمالي المواعيد',
                                data: weeklyData.map(d => d.total),
                                borderColor: '#4e73df',
                                backgroundColor: 'rgba(78, 115, 223, 0.05)',
                                borderWidth: 2,
                                tension: 0.3,
                                fill: true,
                                pointRadius: 3,
                                pointBackgroundColor: '#4e73df',
                                pointBorderColor: '#fff',
                                pointHoverRadius: 5,
                                pointHoverBackgroundColor: '#4e73df',
                                pointHoverBorderColor: '#fff'
                            },
                            {
                                label: 'مكتمل',
                                data: weeklyData.map(d => d.completed),
                                borderColor: '#1cc88a',
                                backgroundColor: 'rgba(28, 200, 138, 0.05)',
                                borderWidth: 2,
                                tension: 0.3,
                                fill: true,
                                pointRadius: 3,
                                pointBackgroundColor: '#1cc88a',
                                pointBorderColor: '#fff',
                                pointHoverRadius: 5,
                                pointHoverBackgroundColor: '#1cc88a',
                                pointHoverBorderColor: '#fff'
                            },
                            {
                                label: 'مجدول',
                                data: weeklyData.map(d => d.scheduled),
                                borderColor: '#36b9cc',
                                backgroundColor: 'rgba(54, 185, 204, 0.05)',
                                borderWidth: 2,
                                tension: 0.3,
                                fill: true,
                                pointRadius: 3,
                                pointBackgroundColor: '#36b9cc',
                                pointBorderColor: '#fff',
                                pointHoverRadius: 5,
                                pointHoverBackgroundColor: '#36b9cc',
                                pointHoverBorderColor: '#fff'
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        plugins: {
                            legend: {
                                position: 'bottom',
                                rtl: true,
                                labels: {
                                    usePointStyle: true,
                                    padding: 15,
                                    font: {
                                        size: 12,
                                        family: 'Arial, sans-serif'
                                    }
                                }
                            },
                            tooltip: {
                                rtl: true,
                                textDirection: 'rtl',
                                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                                padding: 12,
                                cornerRadius: 4
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    stepSize: 1,
                                    font: {
                                        size: 11
                                    }
                                },
                                grid: {
                                    drawBorder: false,
                                    color: 'rgba(0, 0, 0, 0.05)'
                                }
                            },
                            x: {
                                grid: {
                                    display: false,
                                    drawBorder: false
                                },
                                ticks: {
                                    font: {
                                        size: 11
                                    }
                                }
                            }
                        }
                    }
                });
            }
        });
    </script>
@endsection

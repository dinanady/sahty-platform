<div class="row g-4">
    <!-- معلومات الطبيب -->
    <div class="col-lg-4">
        <div class="card bg-light border-primary">
            <div class="card-body text-center">
                <div class="mb-3">
                    <div class="bg-white rounded-circle d-inline-flex align-items-center justify-content-center shadow"
                        style="width: 80px; height: 80px;">
                        <i class="fas fa-user-md text-primary" style="font-size: 2.5rem;"></i>
                    </div>
                </div>

                <h4 class="card-title mb-3">{{ $doctor->name }}</h4>

                <div class="text-start">
                    <div class="mb-3">
                        <div class="d-flex align-items-start">
                            <i class="fas fa-stethoscope text-primary mt-1 ms-2"></i>
                            <div>
                                <small class="text-muted d-block">التخصص</small>
                                <strong>{{ $doctor->specialty }}</strong>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="d-flex align-items-start">
                            <i class="fas fa-phone text-primary mt-1 ms-2"></i>
                            <div>
                                <small class="text-muted d-block">الهاتف</small>
                                <strong dir="ltr">{{ $doctor->phone }}</strong>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="d-flex align-items-start">
                            <i class="fas fa-hospital text-primary mt-1 ms-2"></i>
                            <div>
                                <small class="text-muted d-block">المركز الصحي</small>
                                <strong>{{ $doctor->healthCenter->name }}</strong>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="d-flex align-items-start">
                            <i class="fas fa-toggle-on text-primary mt-1 ms-2"></i>
                            <div>
                                <small class="text-muted d-block">الحالة</small>
                                @if($doctor->is_active)
                                    <span class="badge bg-success">نشط</span>
                                @else
                                    <span class="badge bg-danger">غير نشط</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- الجدول والاستثناءات -->
    <div class="col-lg-8">
        <!-- جدول العمل الأسبوعي -->
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">
                    <i class="fas fa-calendar-week ms-2"></i>
                    جدول العمل الأسبوعي
                </h5>
            </div>
            <div class="card-body">
                <div class="row g-2">
                    @foreach($allDays as $day => $dayName)
                        @php
                            $schedule = $doctor->schedules->firstWhere('day_of_week', $day);
                        @endphp
                        <div class="col-md-6">
                            <div class="card {{ $schedule ? 'border-primary' : '' }}">
                                <div class="card-body py-2 {{ $schedule ? 'bg-light' : '' }}">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <strong class="small">{{ $dayName }}</strong>
                                        @if($schedule)
                                            <small class="text-primary fw-bold" dir="ltr">
                                                {{ date('g:i A', strtotime($schedule->start_time)) }} -
                                                {{ date('g:i A', strtotime($schedule->end_time)) }}
                                            </small>
                                        @else
                                            <small class="text-muted">إجازة</small>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- الاعتذارات القادمة -->
        <div class="card">
            <div class="card-header bg-warning d-flex justify-content-between align-items-center">
                <h5 class="mb-0 text-dark">
                    <i class="fas fa-exclamation-triangle ms-2"></i>
                    الاعتذارات القادمة
                </h5>
            </div>
            <div class="card-body" id="exceptions-list-{{ $doctor->id }}">
                @if($upcomingExceptions->count() > 0)
                    <div class="list-group">
                        @foreach($upcomingExceptions as $exception)
                            <div class="list-group-item list-group-item-warning d-flex justify-content-between align-items-center">
                                <div>
                                    <strong class="d-block">
                                        {{ $exception->exception_date->locale('ar')->isoFormat('dddd، D MMMM YYYY') }}
                                    </strong>
                                    @if($exception->reason)
                                        <small class="text-muted">{{ $exception->reason }}</small>
                                    @endif
                                </div>
                                <button onclick="deleteExceptionInShow({{ $doctor->id }}, {{ $exception->id }})"
                                    class="btn btn-sm btn-outline-danger">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-center text-muted py-3 mb-0">لا توجد اعتذارات قادمة</p>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Modal إضافة اعتذار داخل Show -->
<div class="modal fade" id="exceptionModalShow" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">إضافة اعتذار</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="exceptionFormShow" onsubmit="submitExceptionInShow(event)">
                @csrf
                <input type="hidden" id="exception_doctor_id_show" name="doctor_id">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">التاريخ <span class="text-danger">*</span></label>
                        <input type="date" name="exception_date" class="form-control" required min="{{ date('Y-m-d') }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">السبب (اختياري)</label>
                        <textarea name="reason" class="form-control" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-warning">حفظ</button>
                </div>
            </form>
        </div>
    </div>
</div>

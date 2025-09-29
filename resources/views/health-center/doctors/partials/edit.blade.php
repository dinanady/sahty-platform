<form id="editDoctorForm" onsubmit="submitEdit(event, {{ $doctor->id }})">
    @csrf
    @method('PUT')

    <div class="row g-3 mb-4">
        <div class="col-md-6">
            <label class="form-label fw-bold">الاسم <span class="text-danger">*</span></label>
            <input type="text" name="name" value="{{ $doctor->name }}" class="form-control" required>
        </div>

        <div class="col-md-6">
            <label class="form-label fw-bold">التخصص <span class="text-danger">*</span></label>
            <input type="text" name="specialty" value="{{ $doctor->specialty }}" class="form-control" required>
        </div>

        <div class="col-md-12">
            <label class="form-label fw-bold">رقم الهاتف <span class="text-danger">*</span></label>
            <input type="text" name="phone" value="{{ $doctor->phone }}" class="form-control" dir="ltr" required>
        </div>


        <div class="col-12">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="is_active" value="1"
                    id="is_active" {{ $doctor->is_active ? 'checked' : '' }}>
                <label class="form-check-label fw-bold" for="is_active">
                    الطبيب نشط
                </label>
            </div>
        </div>
    </div>

    <div class="mb-4">
        <h6 class="fw-bold mb-3">جدول العمل الأسبوعي</h6>
        @foreach($days as $day => $dayName)
            @php
                $schedule = $doctor->schedules->firstWhere('day_of_week', $day);
            @endphp

            <div class="card mb-2">
                <div class="card-body py-2">
                    <div class="row align-items-center">
                        <div class="col-md-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox"
                                    name="schedules[{{ $loop->index }}][enabled]"
                                    id="edit_day_{{ $day }}" value="1"
                                    {{ $schedule ? 'checked' : '' }}
                                    onchange="toggleDayInputs('edit', '{{ $day }}')">
                                <label class="form-check-label fw-bold" for="edit_day_{{ $day }}">
                                    {{ $dayName }}
                                </label>
                            </div>
                        </div>

                        <input type="hidden" name="schedules[{{ $loop->index }}][day_of_week]" value="{{ $day }}">

                        <div class="col-md-9 edit-day-inputs-{{ $day }}" style="display: {{ $schedule ? 'block' : 'none' }};">
                            <div class="row g-2">
                                <div class="col-md-6">
                                    <label class="form-label small mb-1">من</label>
                                    <input type="time" name="schedules[{{ $loop->index }}][start_time]"
                                        value="{{ $schedule ? $schedule->start_time : '' }}"
                                        {{ $schedule ? 'required' : '' }}
                                        class="form-control form-control-sm" dir="ltr">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small mb-1">إلى</label>
                                    <input type="time" name="schedules[{{ $loop->index }}][end_time]"
                                        value="{{ $schedule ? $schedule->end_time : '' }}"
                                        {{ $schedule ? 'required' : '' }}
                                        class="form-control form-control-sm" dir="ltr">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="d-flex gap-2 justify-content-end">
        <button type="button" onclick="closeEditModal()" class="btn btn-secondary">
            إلغاء
        </button>
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-save"></i> حفظ التعديلات
        </button>
    </div>
</form>

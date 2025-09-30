{{-- resources/views/admin/doctors/partials/table-rows.blade.php --}}
@forelse($doctors as $doctor)
    <tr>
        <td class="text-end">{{ $loop->iteration }}</td>
        <td class="text-end fw-bold">{{ $doctor->name }}</td>
        <td class="text-end">{{ $doctor->specialty }}</td>
        <td class="text-end" dir="ltr">{{ $doctor->phone }}</td>
        <td class="text-end">
            @foreach($doctor->schedules as $schedule)
                <span class="badge bg-info text-dark me-1 mb-1">
                    {{ __('days.' . $schedule->day_of_week) }}
                </span>
            @endforeach
        </td>
        <td class="text-end">
            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" id="status-switch-{{ $doctor->id }}"
                    onchange="toggleStatus({{ $doctor->id }})" {{ $doctor->is_active ? 'checked' : '' }}>
                <label class="form-check-label" id="status-label-{{ $doctor->id }}" for="status-switch-{{ $doctor->id }}">
                    {{ $doctor->is_active ? 'نشط' : 'غير نشط' }}
                </label>
            </div>

        </td>
        <td class="text-end">
            <div class="btn-group" role="group">
                <button onclick="showDoctor({{ $doctor->id }})" class="btn btn-sm btn-outline-primary" title="عرض">
                    <i class="fas fa-eye"></i>
                </button>
                <button onclick="editDoctor({{ $doctor->id }})" class="btn btn-sm btn-outline-info" title="تعديل">
                    <i class="fas fa-edit"></i>
                </button>
                <button onclick="openExceptionModalFromIndex({{ $doctor->id }})" class="btn btn-sm btn-outline-warning"
                    title="إضافة اعتذار">
                    <i class="fas fa-calendar-times"></i>
                </button>
                <button onclick="deleteDoctor({{ $doctor->id }}, '{{ $doctor->name }}')"
                    class="btn btn-sm btn-outline-danger" title="حذف">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="7" class="text-center py-4 text-muted">لا توجد بيانات</td>
    </tr>
@endforelse

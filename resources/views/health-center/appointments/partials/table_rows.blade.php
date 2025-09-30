@foreach($appointments as $appointment)
    <tr>
        <td>{{ $appointment->child_name }}</td>
        <td>{{ $appointment->national_id }}</td>
        <td>{{ $appointment->child_birth_date->format('Y-m-d') }}</td>
        <td>{{ $appointment->vaccine->name }}</td>
        <td>{{ $appointment->appointment_date->format('Y-m-d') }}</td>
        <td>{{ $appointment->appointment_time }}</td>
        <td>
            <span class="badge
                    @if($appointment->status == 'مكتمل') bg-success
                    @elseif($appointment->status == 'ملغي') bg-danger
                    @elseif($appointment->status == 'لم يحضر') bg-warning
                    @else bg-primary @endif">
                {{ $appointment->status }}
            </span>
        </td>
        <td>{{ $appointment->dose_number }}</td>
        <td>
            <div class="btn-group btn-group-sm">
                <button type="button" class="btn btn-info show-appointment" data-id="{{ $appointment->id }}" title="عرض">
                    <i class="fas fa-eye"></i>
                </button>
                <button type="button" class="btn btn-warning edit-appointment" data-id="{{ $appointment->id }}"
                    title="تعديل">
                    <i class="fas fa-edit"></i>
                </button>
                <button type="button" class="btn btn-danger delete-appointment" data-id="{{ $appointment->id }}"
                    title="حذف">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        </td>
    </tr>
@endforeach

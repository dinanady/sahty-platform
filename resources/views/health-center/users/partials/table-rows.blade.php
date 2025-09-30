@forelse($users as $index => $user)
<tr data-user-id="{{ $user->id }}" class="fade-in">
    <td>{{ ($users->currentPage() - 1) * $users->perPage() + $loop->iteration }}</td>
    <td>
        <div class="d-flex align-items-center">
            <div class="avatar bg-gradient-primary rounded-circle text-white d-flex align-items-center justify-content-center me-2"
                 style="width: 40px; height: 40px; font-size: 16px; font-weight: bold;">
                {{ strtoupper(substr($user->name, 0, 2)) }}
            </div>
            <div>
                <div class="fw-semibold">{{ $user->name }}</div>
                @if($user->id === auth()->id())
                    <small class="badge bg-success">أنت</small>
                @endif
            </div>
        </div>
    </td>
    <td>
        <i class="fas fa-envelope text-muted me-1"></i>
        {{ $user->email }}
    </td>
    <td>
        @if($user->phone)
            <i class="fas fa-phone text-muted me-1"></i>{{ $user->phone }}
        @else
            <span class="text-muted">غير محدد</span>
        @endif
    </td>
    <td>
        @if($user->roles->count() > 0)
            @foreach($user->roles as $role)
                <span class="badge bg-info me-1">{{ $role->display_name }}</span>
            @endforeach
        @else
            <span class="badge bg-secondary">لا يوجد دور</span>
        @endif
    </td>
    <td>
        @if($user->is_active)
            <span class="badge bg-success"><i class="fas fa-check-circle me-1"></i>نشط</span>
        @else
            <span class="badge bg-danger"><i class="fas fa-times-circle me-1"></i>معطل</span>
        @endif
    </td>
    <td>
        <small class="text-muted">
            <i class="far fa-calendar-alt me-1"></i>
            {{ \Carbon\Carbon::parse($user->created_at)->locale('ar')->format('d/m/Y') }}
        </small>
    </td>
    <td class="text-center">
        <div class="btn-group btn-group-sm" role="group">
            @can('hc-edit-users')
                <button class="btn btn-outline-warning" onclick="openEditModal({{ $user->id }})" title="تعديل">
                    <i class="fas fa-edit"></i>
                </button>
            @endcan

            @can('hc-assign-roles')
                <button class="btn btn-outline-info" onclick="openAssignRolesModal({{ $user->id }})" title="تعيين الأدوار">
                    <i class="fas fa-user-shield"></i>
                </button>
            @endcan

            @can('hc-delete-users')
                @if($user->id !== auth()->id())
                    <button class="btn btn-outline-danger" onclick="deleteUser({{ $user->id }})" title="حذف">
                        <i class="fas fa-trash"></i>
                    </button>
                @endif
            @endcan
        </div>
    </td>
</tr>
@empty
<tr>
    <td colspan="8" class="text-center py-5">
        <i class="fas fa-users fa-4x text-muted mb-3 d-block"></i>
        <h6 class="text-muted">لا توجد نتائج</h6>
        <p class="text-muted small">جرب تغيير معايير البحث</p>
    </td>
</tr>
@endforelse

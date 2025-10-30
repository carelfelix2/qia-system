@extends('layouts.admin')

@section('title', 'User Management')

@section('content')
@if(request('user'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Scroll to the specific user row
            const userRow = document.querySelector('tr[data-user-id="{{ request("user") }}"]');
            if (userRow) {
                userRow.scrollIntoView({ behavior: 'smooth', block: 'center' });
                userRow.style.backgroundColor = '#fff3cd'; // Highlight the row
                setTimeout(() => {
                    userRow.style.backgroundColor = '';
                }, 3000);
            }
        });
    </script>
@endif
<div class="row row-deck row-cards">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">User Management</h3>
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success mb-4">
                        {{ session('success') }}
                    </div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger mb-4">
                        {{ session('error') }}
                    </div>
                @endif

                <div class="table-responsive draggable-table">
                    <table class="table table-vcenter">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Requested Email</th>
                                <th>Role yang Diminta</th>
                                <th>Status</th>
                                <th>Role Saat Ini</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                            <tr data-user-id="{{ $user->id }}" @if(request('user') == $user->id) class="table-warning" @endif>
                                <td>
                                    {{ $user->registration_date ? $user->registration_date->format('d/m/Y H:i') : '-' }}
                                </td>
                                <td class="text-secondary">
                                    {{ $user->name }}
                                </td>
                                <td class="text-secondary">
                                    {{ $user->email }}
                                </td>
                                <td>
                                    @if($user->requested_email)
                                        <span class="badge bg-warning">{{ $user->requested_email }}</span>
                                        <form method="POST" action="{{ route('admin.users.approve-email-change', $user) }}" style="display: inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success">Approve</button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.users.reject-email-change', $user) }}" style="display: inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-danger">Reject</button>
                                        </form>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    @if($user->requested_role)
                                        <span class="badge bg-blue text-blue-fg">
                                            {{ ucfirst($user->requested_role) }}
                                        </span>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    @if($user->status === 'pending')
                                        <span class="badge bg-yellow text-yellow-fg"></span>Peding
                                    @elseif($user->status === 'approved')
                                        <span class="badge bg-green text-green-fg"></span>Approved
                                    @elseif($user->status === 'rejected')
                                        <span class="badge bg-red text-red-fg"></span>Rejected
                                    @else
                                        <span class="badge bg-gray text-gray-fg"></span>Unknown
                                    @endif
                                </td>
                                <td>
                                    @if($user->roles->count() > 0)
                                        @foreach($user->roles as $role)
                                            <span class="badge bg-purple text-purple-fg mr-1"></span>{{ $role->name }}
                                        @endforeach
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                @if($user->status === 'pending')
                                    <form method="POST" action="{{ route('admin.users.approve', $user) }}" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-success btn-sm mr-2">
                                            Terima
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.users.reject', $user) }}" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-danger btn-sm">
                                            Tolak
                                        </button>
                                    </form>
                                @else
                                    @if(!$user->hasRole('admin'))
                                        <form method="POST" action="{{ route('admin.users.assign-role', $user) }}" class="d-inline mr-2">
                                            @csrf
                                            <select name="role" class="form-select form-select-sm d-inline w-auto mr-1">
                                                <option value="">Pilih Role</option>
                                                @foreach($roles as $role)
                                                    <option value="{{ $role->name }}">{{ $role->name }}</option>
                                                @endforeach
                                            </select>
                                            <button type="submit" class="btn btn-primary btn-sm">
                                                Assign
                                            </button>
                                        </form>
                                        @if($user->roles->count() > 0)
                                            <form method="POST" action="{{ route('admin.users.remove-role', $user) }}" class="d-inline mr-2">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-warning btn-sm">
                                                    Remove Role
                                                </button>
                                            </form>
                                        @endif
                                        <form method="POST" action="{{ route('admin.users.delete', $user) }}" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this user? This will permanently delete the user and all associated data including quotations, files, and records. This action cannot be undone.')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">
                                                Delete User
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-muted">Full Access</span>
                                    @endif
                                @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $users->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
<style>
.draggable-table {
    overflow-x: auto;
    cursor: grab;
    user-select: none;
}

.draggable-table:active {
    cursor: grabbing;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Draggable table functionality
    const draggableTable = document.querySelector('.draggable-table');
    let isDragging = false;
    let startX;
    let scrollLeft;

    draggableTable.addEventListener('mousedown', (e) => {
        isDragging = true;
        startX = e.pageX - draggableTable.offsetLeft;
        scrollLeft = draggableTable.scrollLeft;
        draggableTable.style.cursor = 'grabbing';
    });

    draggableTable.addEventListener('mouseleave', () => {
        isDragging = false;
        draggableTable.style.cursor = 'grab';
    });

    draggableTable.addEventListener('mouseup', () => {
        isDragging = false;
        draggableTable.style.cursor = 'grab';
    });

    draggableTable.addEventListener('mousemove', (e) => {
        if (!isDragging) return;
        e.preventDefault();
        const x = e.pageX - draggableTable.offsetLeft;
        const walk = (x - startX) * 2; // Scroll speed multiplier
        draggableTable.scrollLeft = scrollLeft - walk;
    });
});
</script>
@endsection

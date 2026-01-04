@extends('layouts.app')

@section('title')
    <title>User Management - Admin Dashboard</title>
@endsection

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom">
        <div>
            <h1 class="h3 mb-0 text-gray-800 fw-bold">User Management</h1>
            <p class="text-muted small mb-0">Manage system access, roles, and administrative permissions.</p>
        </div>
        <div class="btn-group shadow-sm">
            @can('report_users')
                <a href="{{ route('employees.invoices.report') }}" class="btn btn-white border">
                    <i class="fas fa-chart-pie me-2 text-secondary"></i>Activity Report
                </a>
            @endcan
            @can('create_users')
                <a href="{{ route('users.create') }}" class="btn btn-primary px-4">
                    <i class="fas fa-plus-circle me-2"></i>Create New User
                </a>
            @endcan
        </div>
    </div>

    <div class="card border-0 shadow-sm" style="border-radius: 12px; overflow: hidden;">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4 py-3 text-muted small text-uppercase" style="width: 80px;">UID</th>
                            <th class="py-3 text-muted small text-uppercase">Identity</th>
                            <th class="py-3 text-muted small text-uppercase">Email Address</th>
                            <th class="py-3 text-muted small text-uppercase">Access Level</th>
                            <th class="py-3 text-muted small text-uppercase text-end pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                            <tr class="hover-row">
                                <td class="ps-4 text-muted font-monospace small">#{{ $user->id }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="user-icon me-3 {{ auth()->id() === $user->id ? 'bg-primary' : 'bg-secondary-subtle text-secondary' }}">
                                            <i class="fas fa-user small"></i>
                                        </div>
                                        <div>
                                            <div class="fw-bold text-dark">{{ $user->name }}</div>
                                            @if(auth()->id() === $user->id)
                                                <span class="badge bg-primary-subtle text-primary border-0 px-2" style="font-size: 10px;">CURRENT USER</span>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="text-muted small">{{ $user->email }}</td>
                                <td>
                                    @foreach($user->roles as $role)
                                        <span class="badge rounded-pill bg-dark-subtle text-dark border px-3 py-2 fw-normal">
                                            <i class="fas fa-key me-1 small opacity-50"></i>{{ ucfirst($role->name) }}
                                        </span>
                                    @endforeach
                                </td>
                                <td class="text-end pe-4">
                                    <div class="btn-group">
                                        @can('update_users')
                                            <a href="{{ route('users.edit', $user->id) }}" class="btn btn-outline-primary btn-sm border-0" title="Modify User">
                                                Edit
                                            </a>
                                        @endcan

                                        @can('delete_users')
                                            @if(auth()->id() !== $user->id)
                                                <button type="button" class="btn btn-outline-danger btn-sm border-0" 
                                                    onclick="confirmDelete('delete-user-{{ $user->id }}')" title="Revoke Access">
                                                   Delete
                                                </button>
                                                <form id="delete-user-{{ $user->id }}" action="{{ route('users.destroy', $user->id) }}"
                                                    method="POST" class="d-none">
                                                    @csrf
                                                    @method('DELETE')
                                                </form>
                                            @else
                                                <button class="btn btn-sm border-0 disabled" title="You cannot delete your own account">
                                                    <i class="fas fa-lock text-muted opacity-50"></i>
                                                </button>
                                            @endif
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @if($users->hasPages())
            <div class="card-footer bg-white border-top py-3">
                {{ $users->links() }}
            </div>
        @endif
    </div>
</div>

<style>
    body { background-color: #f8f9fc; }
    .btn-white { background-color: #fff; color: #444; }
    .hover-row:hover { background-color: #fbfcfe; transition: 0.2s; }

    /* Identity Icon Style */
    .user-icon {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
    }
    
    .bg-secondary-subtle { background-color: #e9ecef !important; }
    .bg-primary-subtle { background-color: #eef2ff !important; }
    .bg-dark-subtle { background-color: #f8f9fa !important; color: #333 !important; }

    .table td { border-bottom: 1px solid #f2f4f8; }
</style>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function confirmDelete(formId) {
            Swal.fire({
                title: 'Terminate User Access?',
                text: "This user will be logged out and cannot re-enter the system.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, Delete User',
                customClass: {
                    confirmButton: 'btn btn-danger px-4 mx-2',
                    cancelButton: 'btn btn-light px-4 border mx-2'
                },
                buttonsStyling: false
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById(formId).submit();
                }
            })
        }
    </script>
@endsection
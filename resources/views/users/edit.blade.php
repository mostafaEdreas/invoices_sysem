@extends('layouts.app')

@section('title')
    <title>Edit User: {{ $user->name }} - Admin Dashboard</title>
@endsection

@section('content')
<div class="container py-5">
    <div class="row justify-content-center mb-4">
        <div class="col-md-10 col-lg-8">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-1 text-gray-800 fw-bold">Edit System User</h1>
                    <p class="text-muted small mb-0">Updating profile and access for <span class="text-primary fw-bold">{{ $user->email }}</span></p>
                </div>
                <a href="{{ route('users.index') }}" class="btn btn-white border shadow-sm btn-sm">
                    <i class="fas fa-times me-1"></i> Cancel
                </a>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-10 col-lg-8">
            <form action="{{ route('users.update', $user->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="row g-4">
                    <div class="col-md-7">
                        <div class="card border-0 shadow-sm h-100" style="border-radius: 12px; border-top: 4px solid #f6c23e !important;">
                            <div class="card-body p-4">
                                <h6 class="fw-bold mb-4 text-dark"><i class="fas fa-user-edit me-2 text-warning"></i>Identity Details</h6>
                                
                                <div class="mb-3">
                                    <label for="name" class="form-label small fw-bold text-uppercase text-muted">Full Name</label>
                                    <input type="text" name="name" id="name" 
                                           class="form-control bg-light border-0 py-2 @error('name') is-invalid @enderror" 
                                           value="{{ old('name', $user->name) }}" required>
                                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <div class="mb-3">
                                    <label for="email" class="form-label small fw-bold text-uppercase text-muted">Email Address</label>
                                    <input type="email" name="email" id="email" 
                                           class="form-control bg-light border-0 py-2 @error('email') is-invalid @enderror" 
                                           value="{{ old('email', $user->email) }}" required>
                                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <hr class="my-4 opacity-25">

                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h6 class="fw-bold mb-0 text-dark"><i class="fas fa-key me-2 text-warning"></i>Change Password</h6>
                                    <span class="badge bg-light text-muted border fw-normal">Optional</span>
                                </div>

                                <div class="bg-warning-subtle p-2 rounded small text-warning-emphasis mb-3 border border-warning-subtle">
                                    <i class="fas fa-exclamation-triangle me-1"></i> Leave blank to keep the current password.
                                </div>
                                
                                <div class="row">
                                    <div class="col-sm-6 mb-3">
                                        <label for="password" class="form-label small fw-bold text-uppercase text-muted">New Password</label>
                                        <input type="password" name="password" id="password" 
                                               class="form-control bg-light border-0 py-2 @error('password') is-invalid @enderror" 
                                               placeholder="••••••••">
                                        @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                    <div class="col-sm-6 mb-3">
                                        <label for="password_confirmation" class="form-label small fw-bold text-uppercase text-muted">Confirm</label>
                                        <input type="password" name="password_confirmation" id="password_confirmation" 
                                               class="form-control bg-light border-0 py-2" placeholder="••••••••">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-5">
                        <div class="card border-0 shadow-sm h-100" style="border-radius: 12px;">
                            <div class="card-body p-4 d-flex flex-column">
                                <h6 class="fw-bold mb-4 text-dark"><i class="fas fa-shield-alt me-2 text-warning"></i>Update Authority</h6>
                                
                                <p class="text-muted small">Current Role: 
                                    <span class="badge bg-dark text-white">{{ ucfirst($user->roles->first()->name ?? 'None') }}</span>
                                </p>
                                
                                <div class="role-grid flex-grow-1">
                                    @foreach($roles as $role)
                                        <div class="role-option mb-2">
                                            <input type="radio" class="btn-check" name="role" id="role_{{ $role->id }}" 
                                                   value="{{ $role->id }}" 
                                                   {{ (old('role') ?? ($user->roles->first()->id ?? '')) == $role->id ? 'checked' : '' }} required>
                                            <label class="btn btn-outline-light text-dark border w-100 text-start p-3 role-label" for="role_{{ $role->id }}">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <span class="fw-bold">{{ ucfirst($role->name) }}</span>
                                                    <i class="fas fa-check-circle check-icon opacity-0"></i>
                                                </div>
                                            </label>
                                        </div>
                                    @endforeach
                                </div>

                                <div class="d-grid gap-2 mt-5">
                                    <button type="submit" class="btn btn-warning btn-lg fw-bold shadow-sm">
                                        <i class="fas fa-sync-alt me-2 small"></i>Update Permissions
                                    </button>
                                    @if($user->updated_at)
                                        <p class="text-center text-muted small mt-2 mb-0">Modified: {{ $user->updated_at->diffForHumans() }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    body { background-color: #f8f9fc; }
    .btn-white { background-color: #fff; color: #444; }
    .bg-warning-subtle { background-color: #fffbf0 !important; }
    .text-warning-emphasis { color: #856404 !important; }
    
    .form-control:focus {
        background-color: #fff !important;
        border: 1px solid #f6c23e !important;
        box-shadow: none;
    }

    /* Selection Cards */
    .role-label {
        border-radius: 10px;
        transition: all 0.2s ease;
        border-color: #eaecf4 !important;
    }

    .btn-check:checked + .role-label {
        border-color: #f6c23e !important;
        background-color: #fffbf0 !important;
    }

    .btn-check:checked + .role-label .check-icon {
        opacity: 1 !important;
        color: #f6c23e;
    }
</style>
@endsection
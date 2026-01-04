@extends('layouts.app')

@section('title')
    <title>Register User - User Management System</title>
@endsection

@section('content')
<div class="container py-5">
    <div class="row justify-content-center mb-4">
        <div class="col-md-10 col-lg-8">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-1 text-gray-800 fw-bold">Register New User</h1>
                    <p class="text-muted small mb-0">Establish credentials and system access permissions.</p>
                </div>
                <a href="{{ route('users.index') }}" class="btn btn-white border shadow-sm btn-sm">
                    <i class="fas fa-arrow-left me-1"></i> Return to List
                </a>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-10 col-lg-8">
            <form action="{{ route('users.store') }}" method="POST">
                @csrf
                
                <div class="row g-4">
                    <div class="col-md-7">
                        <div class="card border-0 shadow-sm h-100" style="border-radius: 12px;">
                            <div class="card-body p-4">
                                <h6 class="fw-bold mb-4 text-dark"><i class="fas fa-id-card me-2 text-primary"></i>Identity Details</h6>
                                
                                <div class="mb-3">
                                    <label for="name" class="form-label small fw-bold text-uppercase text-muted">Full Name</label>
                                    <input type="text" name="name" id="name" 
                                           class="form-control bg-light border-0 py-2 @error('name') is-invalid @enderror" 
                                           placeholder="e.g. Alexander Hamilton" value="{{ old('name') }}" required>
                                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <div class="mb-3">
                                    <label for="email" class="form-label small fw-bold text-uppercase text-muted">Email Address</label>
                                    <input type="email" name="email" id="email" 
                                           class="form-control bg-light border-0 py-2 @error('email') is-invalid @enderror" 
                                           placeholder="alex@company.com" value="{{ old('email') }}" required>
                                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <hr class="my-4 opacity-25">

                                <h6 class="fw-bold mb-3 text-dark"><i class="fas fa-lock me-2 text-primary"></i>Security</h6>
                                
                                <div class="row">
                                    <div class="col-sm-6 mb-3">
                                        <label for="password" class="form-label small fw-bold text-uppercase text-muted">Password</label>
                                        <input type="password" name="password" id="password" 
                                               class="form-control bg-light border-0 py-2 @error('password') is-invalid @enderror" 
                                               placeholder="••••••••" required>
                                        @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                    <div class="col-sm-6 mb-3">
                                        <label for="password_confirmation" class="form-label small fw-bold text-uppercase text-muted">Confirm</label>
                                        <input type="password" name="password_confirmation" id="password_confirmation" 
                                               class="form-control bg-light border-0 py-2" placeholder="••••••••" required>
                                    </div>
                                </div>
                                <div class="bg-light p-2 rounded small text-muted">
                                    <i class="fas fa-info-circle me-1"></i> Passwords must be at least 8 characters.
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-5">
                        <div class="card border-0 shadow-sm h-100" style="border-radius: 12px;">
                            <div class="card-body p-4">
                                <h6 class="fw-bold mb-4 text-dark"><i class="fas fa-user-shield me-2 text-primary"></i>System Role</h6>
                                
                                <p class="text-muted small">Select the primary authority level for this user:</p>
                                
                                <div class="role-grid">
                                    @foreach($roles as $role)
                                        <div class="role-option mb-2">
                                            <input type="radio" class="btn-check" name="role" id="role_{{ $role->id }}" value="{{ $role->id }}" {{ old('role') == $role->id ? 'checked' : '' }} required>
                                            <label class="btn btn-outline-light text-dark border w-100 text-start p-3 role-label" for="role_{{ $role->id }}">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <span class="fw-bold">{{ ucfirst($role->name) }}</span>
                                                    <i class="fas fa-check-circle check-icon opacity-0"></i>
                                                </div>
                                                <div class="small text-muted mt-1 fw-normal" style="font-size: 0.75rem;">
                                                    Standard access to system features.
                                                </div>
                                            </label>
                                        </div>
                                    @endforeach
                                </div>

                                <div class="d-grid gap-2 mt-5">
                                    <button type="submit" class="btn btn-primary btn-lg fw-bold shadow-sm">
                                        <i class="fas fa-user-plus me-2 small"></i>Create Account
                                    </button>
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
    
    .form-control:focus {
        background-color: #fff !important;
        border: 1px solid #4e73df !important;
        box-shadow: none;
    }

    /* Modern Radio Styling */
    .role-label {
        border-radius: 10px;
        transition: all 0.2s ease;
        border-color: #eaecf4 !important;
    }

    .role-label:hover {
        background-color: #f8f9fa !important;
        border-color: #d1d3e2 !important;
    }

    .btn-check:checked + .role-label {
        border-color: #4e73df !important;
        background-color: #f0f3ff !important;
    }

    .btn-check:checked + .role-label .check-icon {
        opacity: 1 !important;
        color: #4e73df;
    }

    .role-option .btn {
        text-transform: none;
        letter-spacing: normal;
    }
</style>
@endsection
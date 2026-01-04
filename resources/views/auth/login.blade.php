@extends('layouts.app')

@section('title')
    <title>Login - Small Printing System</title>
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center align-items-center" style="min-height: 80vh;">
        <div class="col-md-5 col-lg-4">
            
            <div class="text-center mb-4">
                <div class="bg-primary d-inline-block rounded-circle mb-3 shadow-sm" style="width: 60px; height: 60px; line-height: 60px;">
                    <i class="fas fa-print text-white fa-lg"></i>
                </div>
                <h2 class="fw-bold text-dark">Welcome Back</h2>
                <p class="text-muted small">Enter your credentials to access the system</p>
            </div>

            <div class="card border-0 shadow-lg" style="border-radius: 15px;">
                <div class="card-body p-4 p-md-5">

                    <form action="{{ route('login.check') }}" method="post">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="email" class="form-label fw-bold small text-uppercase">Email Address</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 text-muted"><i class="fas fa-envelope"></i></span>
                                <input type="email" name="email" 
                                    class="form-control bg-light border-start-0 ps-0 @error('email') is-invalid @enderror" 
                                    id="email" value="{{ old('email') }}" 
                                    placeholder="name@company.com" required autofocus>
                            </div>
                        </div>

                        <div class="mb-4">
                            <div class="d-flex justify-content-between">
                                <label for="password" class="form-label fw-bold small text-uppercase">Password</label>
                                {{-- Optional: <a href="#" class="small text-decoration-none">Forgot?</a> --}}
                            </div>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 text-muted"><i class="fas fa-lock"></i></span>
                                <input type="password" name="password" 
                                    class="form-control bg-light border-start-0 ps-0" 
                                    id="password" placeholder="••••••••" required>
                            </div>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg shadow-sm fw-bold">
                                Sign In <i class="fas fa-sign-in-alt ms-2 small"></i>
                            </button>
                        </div>
                    </form>

                </div>
            </div>

            <div class="text-center mt-4">
                <p class="text-muted small">&copy; {{ date('Y') }} Small Printing System</p>
            </div>

        </div>
    </div>
</div>

<style>
    body {
        background-color: #f8f9fc;
        /* Adds a subtle geometric background pattern */
        background-image: radial-gradient(#4e73df0a 2px, transparent 2px);
        background-size: 30px 30px;
    }
    
    .card {
        transition: transform 0.2s ease;
    }

    .form-control:focus {
        background-color: #fff !important;
        border-color: #4e73df;
        box-shadow: none;
    }

    .input-group-text {
        border: 1px solid #ced4da;
    }

    .btn-primary {
        background-color: #4e73df;
        border: none;
        padding: 12px;
    }

    .btn-primary:hover {
        background-color: #2e59d9;
        transform: translateY(-1px);
    }
</style>
@endsection
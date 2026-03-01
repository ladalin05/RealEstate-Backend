<x-guest-layout>
    <style>
        /* Modern Background Gradient */
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            font-family: 'Inter', sans-serif;
        }

        /* Card Styling */
        .login-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        /* Input Styling */
        .form-control {
            border-radius: 8px;
            padding: 12px 12px 12px 45px; /* Space for icons */
            border: 1px solid #e0e0e0;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            box-shadow: 0 0 0 0.25 margin rgba(2, 42, 225, 0.1);
            border-color: #022ae1;
        }

        /* Icon Positioning */
        .form-control-feedback-icon {
            position: absolute;
            top: 50%;
            left: 2px;
            transform: translateY(-50%);
            z-index: 10;
            color: #6c757d;
        }

        /* Button Styling */
        .btn-login {
            background-color: #022ae1;
            border: none;
            border-radius: 8px;
            padding: 12px;
            font-weight: 600;
            letter-spacing: 0.5px;
            transition: transform 0.2s, background-color 0.2s;
            width: 100%;
            color: white;
        }

        .btn-login:hover {
            background-color: #011eb3;
            transform: translateY(-1px);
            color: white;
        }

        /* Checkbox & Links */
        .form-check-input:checked {
            background-color: #022ae1;
            border-color: #022ae1;
        }

        .btn-link {
            color: #022ae1;
            text-decoration: none;
            font-weight: 500;
        }

        .btn-link:hover {
            text-decoration: underline;
        }
    </style>

    <div class="container d-flex justify-content-center align-items-center" style="min-height: 90vh;">
        <div class="col-12 col-sm-10 col-md-8 col-lg-5 col-xl-4 mt-5">
            
            <form class="login-form needs-validation {{ !empty($errors->toArray()) ? 'was-validated' : '' }}" action="{{ route('login') }}" method="POST" novalidate>
                @csrf
                <div class="card login-card">
                    <div class="card-body px-4 py-2 px-md-5 py-md-4">
                        
                        <div class="text-center mb-4">
                            <div class="mb-3">
                                <i class="ph ph-shield-check" style="font-size: 3rem; color: #022ae1;"></i>
                            </div>
                            <h3 class="fw-bold">{{ __('global.login_to_your_account') }}</h3>
                            <p class="text-muted small">{{ __('global.enter_your_credentials_below') }}</p>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold small">{{ __('global.email') }}</label>
                            <div class="position-relative">
                                <div class="form-control-feedback-icon">
                                    <i class="ph ph-user-circle"></i>
                                </div>
                                <input type="email" class="form-control" placeholder="name@company.com" name="email" autocomplete="email" required value="{{ old('email') }}">
                                <div class="invalid-feedback">
                                    {{ $errors->has('email') ? $errors->first('email') : __('email_is_required') }}
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold small">{{ __('global.password') }}</label>
                            <div class="position-relative">
                                <div class="form-control-feedback-icon">
                                    <i class="ph ph-lock"></i>
                                </div>
                                <input type="password" class="form-control" placeholder="•••••••••••" name="password" autocomplete="current-password" required>
                                <div class="invalid-feedback">Please enter your password.</div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="remember" id="check-l">
                                <label class="form-check-label small" for="check-l">
                                    Remember me
                                </label>
                            </div>
                            <a href="{{ route('password.request') }}" class="small btn-link">{{ __('global.forgot_password') }}?</a>
                        </div>

                        <div class="mb-4">
                            <button type="submit" class="btn btn-login shadow-sm">
                                {{ __('global.login') }}
                            </button>
                        </div>

                        <div class="text-center">
                            <p class="text-muted small mb-0">
                                Don't have an account? 
                                <a href="{{ url('register') }}" class="btn-link">Sign up</a>
                            </p>
                        </div>

                    </div>
                </div>
            </form>
            </div>
    </div>
</x-guest-layout>
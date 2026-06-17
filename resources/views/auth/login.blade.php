<x-guest-layout>

<style>
    .login-page-wrapper {
        min-height: 90vh;
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 2rem 1rem;
        background-color: #f0f2f5;
    }

    .login-card {
        background: #ffffff;
        border: 1px solid #dde1e7;
        border-radius: 14px;
        padding: 2.5rem 2rem;
        width: 100%;
        max-width: 420px;
    }

    /* Brand icon */
    .login-brand-icon {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        background: #eef3fb;
        border: 1px solid #d0dff5;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.2rem;
    }
    .login-brand-icon i {
        font-size: 1.45rem;
        color: #185FA5;
    }

    .login-title {
        font-size: 1.2rem;
        font-weight: 600;
        color: #111827;
        text-align: center;
        margin-bottom: 3px;
    }
    .login-subtitle {
        font-size: 0.8125rem;
        color: #6b7280;
        text-align: center;
        margin-bottom: 1.8rem;
    }

    /* Field label */
    .login-label {
        display: block;
        font-size: 0.78rem;
        font-weight: 500;
        color: #4b5563;
        margin-bottom: 5px;
        letter-spacing: 0.01em;
    }

    /* Input wrapper */
    .login-input-wrapper {
        position: relative;
    }
    .login-input-wrapper .input-icon {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        font-size: 1rem;
        color: #9ca3af;
        pointer-events: none;
        z-index: 1;
    }

    /* The input itself — blends with page bg at rest, brightens on interaction */
    .login-input-wrapper .form-control {
        height: 42px;
        padding-left: 37px;
        padding-right: 38px;
        border: 1.5px solid #e5e7eb;
        border-radius: 9px;
        background-color: #f7f8fa;
        color: #111827;
        font-size: 0.845rem;
        transition: border-color .15s ease, background-color .15s ease, box-shadow .15s ease;
        box-shadow: none;
    }
    .login-input-wrapper .form-control:hover:not(:focus) {
        border-color: #c0c5cc;
        background-color: #f2f4f7;
    }
    .login-input-wrapper .form-control:focus {
        border-color: #378ADD;
        background-color: #ffffff;
        box-shadow: 0 0 0 3px rgba(55, 138, 221, 0.10);
        outline: none;
    }
    .login-input-wrapper .form-control.is-invalid {
        border-color: #e24b4a;
        background-color: #ffffff;
        background-image: none;
    }
    .login-input-wrapper .form-control.is-invalid:focus {
        box-shadow: 0 0 0 3px rgba(226, 75, 74, 0.10);
    }

    /* Password toggle button */
    .login-input-wrapper .toggle-password {
        position: absolute;
        right: 10px;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        padding: 0;
        cursor: pointer;
        color: #9ca3af;
        font-size: 1rem;
        display: flex;
        align-items: center;
        z-index: 1;
        line-height: 1;
    }
    .login-input-wrapper .toggle-password:hover { color: #374151; }

    /* Invalid feedback */
    .login-invalid-feedback {
        font-size: 0.72rem;
        color: #dc2626;
        margin-top: 4px;
        display: block;
    }

    /* ── Custom checkbox ── */
    .login-check-wrap {
        display: flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
    }
    .login-check-wrap input[type="checkbox"] {
        appearance: none;
        -webkit-appearance: none;
        width: 17px;
        height: 17px;
        border: 1.5px solid #d1d5db;
        border-radius: 5px;
        background: #f7f8fa;       /* matches input rest state */
        cursor: pointer;
        flex-shrink: 0;
        position: relative;
        transition: border-color .15s ease, background-color .15s ease;
        margin: 0;
    }
    .login-check-wrap input[type="checkbox"]:hover {
        border-color: #9ca3af;
        background: #eef3fb;
    }
    .login-check-wrap input[type="checkbox"]:checked {
        background-color: #185FA5;
        border-color: #185FA5;
    }
    .login-check-wrap input[type="checkbox"]:checked::after {
        content: '';
        display: block;
        width: 4px;
        height: 8px;
        border: 2px solid #ffffff;
        border-top: none;
        border-left: none;
        transform: rotate(45deg) translate(-1px, -1px);
        position: absolute;
        top: 4px;
        left: 6px;
    }
    .login-check-wrap input[type="checkbox"]:focus-visible {
        outline: none;
        box-shadow: 0 0 0 3px rgba(55, 138, 221, 0.15);
    }
    .login-check-wrap .check-label-text {
        font-size: 0.8125rem;
        color: #6b7280;
        user-select: none;
    }

    /* Forgot / links */
    .login-forgot {
        font-size: 0.8125rem;
        color: #185FA5;
        text-decoration: none;
    }
    .login-forgot:hover { text-decoration: underline; color: #0C447C; }

    /* Submit button */
    .btn-login-submit {
        width: 100%;
        height: 43px;
        background-color: #185FA5;
        color: #ffffff;
        border: none;
        border-radius: 9px;
        font-size: 0.875rem;
        font-weight: 500;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        transition: background-color .15s ease, transform .1s ease;
    }
    .btn-login-submit:hover  { background-color: #0C447C; color: #fff; }
    .btn-login-submit:active { transform: scale(0.98); }

    /* Divider */
    .login-divider {
        display: flex;
        align-items: center;
        gap: 10px;
        margin: 1.25rem 0;
    }
    .login-divider hr {
        flex: 1;
        border: none;
        border-top: 1px solid #e9ebee;
        margin: 0;
    }
    .login-divider span { font-size: 0.75rem; color: #9ca3af; }

    /* Sign-up row */
    .login-signup-row { text-align: center; font-size: 0.8125rem; color: #6b7280; }
    .login-signup-row a { color: #185FA5; font-weight: 500; text-decoration: none; }
    .login-signup-row a:hover { text-decoration: underline; }
</style>

<div class="login-page-wrapper">
    <div class="login-card">

        <form class="needs-validation {{ !empty($errors->toArray()) ? 'was-validated' : '' }}"
              action="{{ route('login') }}"
              method="POST"
              novalidate>
            @csrf

            {{-- Brand icon --}}
            <div class="login-brand-icon">
                <i class="ph ph-shield-check"></i>
            </div>
            <h3 class="login-title">{{ __('global.login_to_your_account') }}</h3>
            <p class="login-subtitle">{{ __('global.enter_your_credentials_below') }}</p>

            {{-- Email --}}
            <div class="mb-3">
                <label class="login-label">{{ __('global.email') }}</label>
                <div class="login-input-wrapper">
                    <i class="ph ph-envelope input-icon"></i>
                    <input
                        type="email"
                        name="email"
                        class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}"
                        placeholder="name@company.com"
                        autocomplete="email"
                        value="{{ old('email') }}"
                        required
                    >
                    @if ($errors->has('email'))
                        <span class="login-invalid-feedback">{{ $errors->first('email') }}</span>
                    @else
                        <div class="invalid-feedback">{{ __('global.email_is_required') }}</div>
                    @endif
                </div>
            </div>

            {{-- Password --}}
            <div class="mb-3">
                <label class="login-label">{{ __('global.password') }}</label>
                <div class="login-input-wrapper">
                    <i class="ph ph-lock input-icon"></i>
                    <input
                        type="password"
                        name="password"
                        id="passwordInput"
                        class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}"
                        placeholder="••••••••••"
                        autocomplete="current-password"
                        required
                    >
                    <button type="button" class="toggle-password" onclick="togglePassword()" aria-label="Toggle password visibility">
                        <i class="ph ph-eye" id="toggleIcon"></i>
                    </button>
                    @if ($errors->has('password'))
                        <span class="login-invalid-feedback">{{ $errors->first('password') }}</span>
                    @else
                        <div class="invalid-feedback">{{ __('global.password_is_required') }}</div>
                    @endif
                </div>
            </div>

            {{-- Remember me + Forgot password --}}
            <div class="d-flex justify-content-between align-items-center mb-4">
                <label class="login-check-wrap">
                    <input type="checkbox" name="remember" id="rememberMe">
                    <span class="check-label-text">{{ __('global.remember_me') }}</span>
                </label>
                <a href="{{ route('password.request') }}" class="login-forgot">
                    {{ __('global.forgot_password') }}?
                </a>
            </div>

            {{-- Submit --}}
            <button type="submit" class="btn-login-submit mb-3">
                <i class="ph ph-sign-in"></i>
                {{ __('global.login') }}
            </button>

            {{-- Divider --}}
            <div class="login-divider">
                <hr><span>or</span><hr>
            </div>

            {{-- Sign up --}}
            <p class="login-signup-row mb-0">
                Don't have an account?
                <a href="{{ url('register') }}">Sign up</a>
            </p>

        </form>
    </div>
</div>

<script>
    function togglePassword() {
        var input = document.getElementById('passwordInput');
        var icon  = document.getElementById('toggleIcon');
        if (input.type === 'password') {
            input.type = 'text';
            icon.className = 'ph ph-eye-slash';
        } else {
            input.type = 'password';
            icon.className = 'ph ph-eye';
        }
    }
</script>

</x-guest-layout>
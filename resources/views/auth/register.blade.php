<x-guest-layout>
    <!-- Content area -->
    <div class="content d-flex justify-content-center align-items-center">
        <form class="login-form needs-validation {{ !empty($errors->toArray()) ? 'was-validated' : '' }}" method="POST" action="{{ route('register') }}">
            @csrf
            <div class="card mb-0">
                <div class="card-body">
                    <div class="text-center mb-3">
                        <div class="d-inline-flex align-items-center justify-content-center mb-4 mt-2">
                            <img src="{{ asset('assets/images/default/LE-book.png') }}" class="h-48px">
                        </div>
                        <h5 class="mb-0">Register Account</h5>
                        <span class="d-block text-muted">{{ __('global.enter_your_credentials_below') }}</span>
                    </div>
                    <div class="mb-3">
                        <label for="name" class="form-label" style="font-weight: 600;">Name</label>
                        <div class="form-control-feedback form-control-feedback-start">
                            <input type="text" class="form-control" placeholder="John Doe" name="name" autocomplete="name" required>
                            <div class="form-control-feedback-icon">
                                <i class="ph ph-user-circle text-muted"></i>
                            </div>
                            <div class="invalid-feedback">{{ $errors->has('name') ? $errors->first('name') : __('name_is_required') }}</div>
                            <div class="valid-feedback">Valid feedback</div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">{{ __('global.email') }}</label>
                        <div class="form-control-feedback form-control-feedback-start">
                            <input type="text" class="form-control" placeholder="xxx@edu.kh" name="email" autocomplete="email" required>
                            <div class="form-control-feedback-icon">
                                <i class="ph ph-user-circle text-muted"></i>
                            </div>
                            <div class="invalid-feedback">{{ $errors->has('email') ? $errors->first('email') : __('email_is_required') }}</div>
                            <div class="valid-feedback">Valid feedback</div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">{{ __('global.password') }}</label>
                        <div class="form-control-feedback form-control-feedback-start">
                            <input type="password" class="form-control" placeholder="•••••••••••" name="password" autocomplete="current-password" required>
                            <div class="form-control-feedback-icon">
                                <i class="ph ph-lock text-muted"></i>
                            </div>
                            <div class="invalid-feedback">Invalid feedback</div>
                            <div class="valid-feedback">Valid feedback</div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="password_confirmation" class="form-label" style="font-weight: 600;">Confirm Password</label>
                        <div class="form-control-feedback form-control-feedback-start">
                            <input type="password" class="form-control"  id="password_confirmation" placeholder="•••••••••••" autocomplete="new-password" name="password_confirmation" required>
                            <div class="form-control-feedback-icon">
                                <i class="ph ph-lock text-muted"></i>
                            </div>
                            <div class="invalid-feedback">Invalid feedback</div>
                            <div class="valid-feedback">Valid feedback</div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <button type="submit" style="background-color: #022ae1" class="btn btn-primary w-100">{{ __('global.register') }}</button>
                    </div>

                    <div class="text-center">
                        <a class="text-decoration-none me-3" href="{{ route('login') }}" style="color: #6c757d; font-size: 0.9rem;">
                            Already registered?
                        </a>
                    </div>
                    <br>
                    <span class="form-text text-center text-muted">By continuing, you're confirming that you've read our <a href="#">Terms &amp; Conditions</a> and <a href="#">Cookie Policy</a></span>
                </div>
            </div>
        </form>
    </div>
</x-guest-layout>

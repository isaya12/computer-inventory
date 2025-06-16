<div>
    <div class="account-content">
        <div class="login-wrapper">
            <div class="login-content">
                <div class="login-userset">
                    <div class="login-logo">
                        <img src="{{ asset('assets/img/logo.png') }}" alt="img">
                    </div>
                    <div class="login-userheading">
                        <h3>Sign In</h3>
                        <h4>Please login to your account</h4>
                        @error('email')
                        <div class="alert alert-{{ str_contains($message, 'banned') ? 'danger' : 'warning' }} mt-2">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            {{ $message }}
                        </div>
                    @enderror
                    </div>
                    <form wire:submit.prevent="login">
                        @csrf
                        <div class="form-login">
                            <label>Email</label>
                            <div class="form-addons">
                                <input type="text" wire:model="email" placeholder="Enter your email address"
                                    required>
                                <img src="{{ asset('assets/img/icons/mail.svg') }}" alt="img">
                            </div>
                        </div>
                        <div class="form-login">
                            <label>Password</label>
                            <div class="pass-group">
                                <input type="password" wire:model="password" class="pass-input"
                                    placeholder="Enter your password" required>
                                <span class="fas toggle-password fa-eye-slash"></span>
                            </div>
                            @error('password')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-login">
                            <div class="alreadyuser">
                                <h4><a href="" class="hover-a">Forgot Password?</a></h4>
                            </div>
                        </div>
                        <div class="form-login">
                            <button type="submit" class="btn btn-login">
                                <span wire:loading.remove>Sign In</span>
                                <span wire:loading>Signing in...</span>
                            </button>
                        </div>
                        <div class="signinform text-center">
                            <h4>Don't have an account? <a href="" class="hover-a">Sign Up</a></h4>
                        </div>
                    </form>
                </div>
            </div>
            <div class="login-img">
                <img src="{{ asset('assets/img/login.jpg') }}" alt="img">
            </div>
        </div>
    </div>
</div>

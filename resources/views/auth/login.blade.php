@extends('layouts.guest')

@section('content')
    <form class="card border-0 p-2" action="{{ route('login') }}" method="post" autocomplete="off">
        @csrf
        <div class="card-body">
            <div class="mb-3">
                <label for="email" class="form-label">{{ __('Alamat Email') }}</label>
                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"" id="email"
                    placeholder="name@example.com" autofocus>
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">
                    {{ __('Password') }}
                    @if (Route::has('password.request'))
                        <span class="form-label-description">
                            <a href="{{ route('password.request') }}">{{ __('Lupa Password?') }}</a>
                        </span>
                    @endif
                </label>
                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                    placeholder="{{ __('Password') }}">
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-check">
                    <input type="checkbox" class="form-check-input" name="remember" />
                    <span class="form-check-label">{{ __('Ingat saya') }}</span>
                </label>
            </div>

            <div class="form-footer">
                <button type="submit" class="btn btn-primary w-100">{{ __('Masuk') }}</button>
            </div>
        </div>
    </form>
    {{-- @if (Route::has('register'))
        <div class="text-center text-muted mt-3">
            {{ __("Don't have account yet?") }} <a href="{{ route('register') }}">{{ __('Sign up') }}</a>
        </div>
    @endif --}}
@endsection

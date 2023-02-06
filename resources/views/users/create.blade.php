@extends('layouts.app')

@section('content')
    <div class="page-header d-print-none">
        <div class="row align-items-center">
            <div class="col">
                <h2 class="page-title">
                    {{ __('Tambah Pengguna') }}
                </h2>
            </div>
        </div>
    </div>
    <hr>
    <div class="page-body">
        @if ($message = Session::get('success'))
            <div class="alert alert-success alert-dismissible">
                <div class="d-flex">
                    <div>
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon alert-icon" width="24" height="24"
                            viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round"
                            stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                            <path d="M5 12l5 5l10 -10"></path>
                        </svg>
                    </div>
                    <div>
                        {{ $message }}
                    </div>
                </div>
                <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
            </div>
        @endif

        <form action="{{ route('users.store') }}" method="POST" class="card" autocomplete="off">
            @csrf

            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label required">{{ __('Nama Lengkap') }}</label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                        placeholder="{{ __('Nama Lengkap') }}" value="{{ old('name') }}" required>
                </div>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror

                <div class="mb-3">
                    <label class="form-label required">{{ __('Alamat Email') }}</label>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                        placeholder="{{ __('Alamat Email') }}" value="{{ old('email') }}" required>
                </div>
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror

                <div class="mb-3">
                    <label class="form-label required">{{ __('Nomor Telepon') }}</label>
                    <input type="phone_number" name="phone_number"
                        class="form-control @error('phone_number') is-invalid @enderror"
                        placeholder="{{ __('Nomor Telepon') }}" value="{{ old('phone_number') }}" required>
                </div>
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror

                <div class="mb-3">
                    <label class="form-label required">{{ __('Hak Akses') }}</label>
                    <select name="role" class="form-control @error('role') is-invalid @enderror">
                        @foreach (['admin', 'laundry', 'user'] as $item)
                            <option @selected(old('role') == $item)>{{ $item }}</option>
                        @endforeach
                    </select>
                </div>
                @error('role')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror

                <div class="mb-3">
                    <label class="form-label required">{{ __('Password Baru') }}</label>
                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                        placeholder="{{ __('Password Baru') }}">
                </div>
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror

                <div class="mb-3">
                    <label class="form-label required">{{ __('Ulangi Password') }}</label>
                    <input type="password" name="password_confirmation"
                        class="form-control @error('password_confirmation') is-invalid @enderror"
                        placeholder="{{ __('Ulangi Password') }}" autocomplete="new-password">
                </div>
                @error('password_confirmation')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror

            </div>

            <div class="card-footer">
                <a href="{{ route('users.index') }}" class="btn btn-secondary">{{ __('Kembali') }}</a>
                <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
            </div>
        </form>
    </div>
@endsection

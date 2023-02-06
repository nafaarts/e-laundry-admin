@extends('layouts.app')

@section('content')
    <div class="page-header d-print-none mb-3">
        <div class="row align-items-center">
            <div class="col">
                <h2 class="page-title">
                    Ubah Layanan
                </h2>
            </div>
        </div>
    </div>
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

        <form action="{{ route('service.update', [$service, 'laundry' => request('laundry')]) }}" method="POST"
            class="card" autocomplete="off" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label required">{{ __('Laundry') }}</label>
                    <input type="text" readonly class="form-control" value="{{ $laundry->name }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label required">{{ __('Nama Layanan') }}</label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                        placeholder="{{ __('Nama Layanan') }}" value="{{ old('name', $service->name) }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label required">{{ __('Satuan') }}</label>
                            <input type="text" name="unit" class="form-control @error('unit') is-invalid @enderror"
                                placeholder="{{ __('Satuan') }}" value="{{ old('unit', $service->unit) }}" required>
                            <small>*Contoh: KG, Pcs, dsb</small>
                            @error('unit')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label required">{{ __('Harga') }}</label>
                            <input type="number" name="price" class="form-control @error('price') is-invalid @enderror"
                                placeholder="{{ __('Harga') }}" value="{{ old('price', $service->price) }}" required>
                            <small>*Harga Satuan</small>
                            @error('price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label required">{{ __('Deskripsi') }}</label>
                    <input type="text" name="description" class="form-control @error('description') is-invalid @enderror"
                        placeholder="{{ __('Deskripsi') }}" value="{{ old('description', $service->description) }}"
                        required>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>


                <div class="mb-3">
                    <label class="form-label">{{ __('Icon') }}</label>
                    <input type="file" name="icon" class="form-control @error('icon') is-invalid @enderror"
                        onchange="preview()">
                    <small>*Ukuran icon harus 100*100 px</small>
                    @error('icon')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <img id="frame" src="{{ asset('img/icon/' . $service->icon) }}" style="max-height: 150px" />
            </div>

            <div class="card-footer">
                <a href="{{ route('laundry.show', $laundry) }}" class="btn btn-secondary">{{ __('Kembali') }}</a>
                <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
            </div>
        </form>
    </div>
@endsection

@section('custom_scripts')
    <script>
        function preview() {
            frame.src = URL.createObjectURL(event.target.files[0]);
        }
    </script>
@endsection

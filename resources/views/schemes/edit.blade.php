@extends('layouts.app')

@section('content')
    <div class="container px-5">
        <div class="card" style="background: #1F2544">
            <div class="card-header text-white border-white">{{ __('Modify Scheme') }}</div>

            <div class="card-body">
                <form method="POST" action="{{ route('schemes.update', [$scheme->id]) }}">
                    @csrf

                    <!-- Scheme Name -->
                    <div class="row mb-3">
                        <label for="name" class="col-md-4 col-form-label text-md-end">{{ __('Name') }}</label>

                        <div class="col-md-6">
                            <input id="name" type="text" class="form-control @error('name') is-invalid @enderror"
                                   name="name" value="{{ $scheme->name }}" required autofocus>

                            @error('name')
                            <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <!-- Type of Disability -->
                    <div class="row mb-3">
                        <label for="disability"
                               class="col-md-4 col-form-label text-md-end">{{ __('Type of Disability') }}</label>

                        <div class="col-md-6">
                            <input id="disability" type="text"
                                   class="form-control @error('disability') is-invalid @enderror"
                                   name="disability" value="{{ $scheme->disability }}" required>

                            @error('disability')
                            <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <!-- Scheme Description -->
                    <div class="row mb-3">
                        <label for="description"
                               class="col-md-4 col-form-label text-md-end">{{ __('Description') }}</label>

                        <div class="col-md-6">
                            <textarea id="description" class="form-control @error('description') is-invalid @enderror"
                                      name="description" required>{{ $scheme->description }}</textarea>

                            @error('description')
                            <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <!-- How to apply the scheme -->
                    <div class="row mb-3">
                        <label for="how_to_apply"
                               class="col-md-4 col-form-label text-md-end">{{ __('How to Apply') }}</label>

                        <div class="col-md-6">
                            <textarea id="how_to_apply" class="form-control @error('how_to_apply') is-invalid @enderror"
                                      name="how_to_apply"
                                      required>{{ empty($scheme->link) ? $scheme->how_to_apply : $scheme->link }}</textarea>

                            @error('how_to_apply')
                            <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <!-- Update Button and Back Button -->
                    <div class="text-center">
                        <button type="submit" class="btn btn-outline-primary">
                            {{ __('Update') }}
                        </button>
                        <a href="{{ url('/schemes') }}" class="btn btn-outline-primary">
                            {{ __('Back') }}
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

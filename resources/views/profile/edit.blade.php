@extends('layouts.app')

@section('title', 'Edit Profile')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Edit Profile</h2>
<a href="{{ route('profile.edit') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Profile
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-8">
            <form id="send-verification" method="POST" action="{{ route('verification.resend') }}">
                @csrf
            </form>

            <form method="POST" action="{{ route('profile.update') }}" novalidate>
                @csrf
                @method('PATCH')

                <div class="card">
                    <div class="card-header">
                        <h5>Profile Information</h5>
                    </div>
                    <div class="card-body">

                        <div class="mb-3">
                            <label for="name" class="form-label">{{ __('Name') }} *</label>
                            <input id="name" name="name" type="text" class="form-control" 
                                value="{{ old('name', $user->name) }}" required autofocus autocomplete="name">
                            @error('name')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">{{ __('Email') }} *</label>
                            <input id="email" name="email" type="email" class="form-control"
                                value="{{ old('email', $user->email) }}" required autocomplete="username">
                            @error('email')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror

                            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                                <div class="mt-2">
                                    <p class="text-muted mb-1">
                                        {{ __('Your email address is unverified.') }}
                                        <button form="send-verification" type="submit" class="btn btn-link p-0 align-baseline">
                                            {{ __('Click here to re-send the verification email.') }}
                                        </button>
                                    </p>

                                    @if (session('status') === 'verification-link-sent')
                                        <p class="text-success small mt-1">
                                            {{ __('A new verification link has been sent to your email address.') }}
                                        </p>
                                    @endif
                                </div>
                            @endif
                        </div>

                    </div>
                    <div class="card-footer d-flex justify-content-between align-items-center">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i> {{ __('Save') }}
                        </button>

                        @if (session('status') === 'profile-updated')
                            <span class="text-success small" x-data="{ show: true }" x-show="show" 
                                  x-init="setTimeout(() => show = false, 2000)" x-transition>
                                {{ __('Saved.') }}
                            </span>
                        @endif
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>
@endsection

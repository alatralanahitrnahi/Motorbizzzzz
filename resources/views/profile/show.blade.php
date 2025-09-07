@extends('layouts.app')

@section('title', 'Profile Details')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Profile Details</h2>
        <a href="{{ route('profile.edit') }}" class="btn btn-warning">
            <i class="fas fa-edit"></i> Edit Profile
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-8">

            <div class="card">
                <div class="card-header">
                    <h5>User Information</h5>
                </div>
                <div class="card-body">
                    <p><strong>Name:</strong> {{ $user->name }}</p>
                    <p><strong>Email:</strong> {{ $user->email }}</p>
                    <p><strong>Joined On:</strong> {{ $user->created_at->format('M d, Y') }}</p>
                    @if($user->email_verified_at)
                        <p><strong>Email Verified:</strong> Yes ({{ $user->email_verified_at->format('M d, Y') }})</p>
                    @else
                        <p><strong>Email Verified:</strong> No</p>
                    @endif
                </div>
            </div>

            <!-- Optional: Add more profile info/cards here -->

        </div>
    </div>
</div>
@endsection

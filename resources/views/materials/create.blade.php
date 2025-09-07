@extends('layouts.app')
@section('title', 'Add Material')
@section('content')
<div class="container">
    <div class="card">
        <div class="card-header"><h3>Add New Material</h3></div>
        <div class="card-body">
            <form action="{{ route('materials.store') }}" method="POST">
                @csrf
                @include('materials.partials.form')
            </form>
        </div>
    </div>
</div>
@endsection

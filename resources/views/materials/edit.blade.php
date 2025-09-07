@extends('layouts.app')
@section('title', 'Edit Material')
@section('content')
<div class="container">
    <div class="card">
        <div class="card-header"><h3>Edit Material</h3></div>
        <div class="card-body">
           <form id="materialForm" action="{{ route('materials.update', $material) }}" method="POST">
    @csrf 
    @method('PUT')
    @include('materials.partials.form', ['material' => $material])
    
</form>

        </div>
    </div>
</div>
@endsection

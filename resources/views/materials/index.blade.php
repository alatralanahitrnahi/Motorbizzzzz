@extends('layouts.app')
@section('title', 'Materials List')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3>Materials</h3>
            <a href="{{ route('materials.create') }}" class="btn btn-primary">Add New Material</a>
        </div>

        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Code</th>
                        <th>Unit</th>
                        <th>Unit Price</th>
                        <th>GST Rate (%)</th>
                        <th>Available</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($materials as $material)
                        <tr>
                            <td>{{ $material->name }}</td>
                            <td>{{ $material->code }}</td>
                            <td>{{ $material->unit }}</td>
                            <td>{{ number_format($material->unit_price, 2) }}</td>
                            <td>{{ $material->gst_rate }}%</td>
                            <td>
                                <span class="badge bg-{{ $material->is_available ? 'success' : 'secondary' }}">
                                    {{ $material->is_available ? 'Yes' : 'No' }}
                                </span>
                            </td>
                            <td>
                                {{-- Always show View --}}
                                @can('view', $material)
                                    <a href="{{ route('materials.show', $material->id) }}" class="btn btn-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                @endcan

                                {{-- Show Edit only if permitted --}}
                                @can('update', $material)
                                    <a href="{{ route('materials.edit', $material->id) }}" class="btn btn-warning">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                @endcan

                                {{-- Show Delete only if permitted --}}
                                @can('delete', $material)
                                    <form action="{{ route('materials.destroy', $material->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                @endcan
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">No materials found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            {{-- Pagination with Bootstrap 5 style and centered --}}
            <div class="d-flex justify-content-center mt-4">
                {{ $materials->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>
@endsection

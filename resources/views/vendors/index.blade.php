@extends('layouts.app')

@section('title', 'Vendor List')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3>Vendors</h3>
            <a href="{{ route('vendors.create') }}" class="btn btn-primary">Add New Vendor</a>
        </div>

        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @forelse($vendors as $vendor)
                @if($loop->first)
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Business Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Company Address</th>
                            <th>Warehouse Address</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                @endif
                        <tr>
                            <td>{{ $vendor->name }}</td>
                            <td>{{ $vendor->business_name }}</td>
                            <td>{{ $vendor->email }}</td>
                            <td>{{ $vendor->phone }}</td>
                            <td>{{ $vendor->company_address }}</td>
                            <td>{{ $vendor->warehouse_address }}</td>
                          <td>
    <div class="d-flex align-items-center gap-1">
      {{-- View Button --}}
@canAccess('view', 'vendors')
    <a href="{{ route('vendors.show', $vendor) }}" class="btn btn-info btn-sm" title="View">
        <i class="fas fa-eye"></i>
    </a>
@endcanAccess

{{-- Edit Button --}}
@canAccess('edit', 'vendors')
    <a href="{{ route('vendors.edit', $vendor) }}" class="btn btn-warning btn-sm" title="Edit">
        <i class="fas fa-edit"></i>
    </a>
@endcanAccess

{{-- Delete Button --}}
@canAccess('delete', 'vendors')
    <form action="{{ route('vendors.destroy', $vendor) }}" method="POST" onsubmit="return confirm('Delete this vendor?');" style="display:inline;">
        @csrf
        @method('DELETE')
        <button class="btn btn-sm btn-danger" title="Delete">
            <i class="fas fa-trash-alt"></i>
        </button>
    </form>
@endcanAccess

    </div>
</td>


                        </tr>
                @if($loop->last)
                    </tbody>
                </table>

                {{ $vendors->links() }}
                @endif
            @empty
                <p class="text-center">No vendors found.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection

@extends('layouts.app')
@section('title', 'Purchase Orders')

@section('content')
<div class="container-fluid">

    {{-- Success/Error Messages --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">Purchase Orders</h2>
            <p class="text-muted mb-0">Manage all purchase orders</p>
        </div>
        <a href="{{ route('purchase-orders.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i> Create Purchase Order
        </a>
    </div>

    {{-- Filters Section --}}
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('purchase-orders.index') }}" class="row g-3">

                {{-- PO Number --}}
                <div class="col-md-3">
                    <label for="po_number" class="form-label">PO Number</label>
                    <input type="text" class="form-control" id="po_number" name="po_number"
                           value="{{ request('po_number') }}" placeholder="Search PO Number">
                </div>

                {{-- Vendor --}}
                <div class="col-md-3">
                    <label for="vendor" class="form-label">Vendor</label>
                    <input type="text" class="form-control" id="vendor" name="vendor"
                           value="{{ request('vendor') }}" placeholder="Search Vendor Name">
                </div>

                {{-- Item Name --}}
                <div class="col-md-3">
                    <label for="item_name" class="form-label">Item Name</label>
                    <input type="text" class="form-control" id="item_name" name="item_name"
                           value="{{ request('item_name') }}" placeholder="Search Item Name">
                </div>

                {{-- Status --}}
                <div class="col-md-3">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-select" id="status" name="status">
                        <option value="">All Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="ordered" {{ request('status') == 'ordered' ? 'selected' : '' }}>Ordered</option>
                        <option value="received" {{ request('status') == 'received' ? 'selected' : '' }}>Received</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>

                {{-- Date From --}}
                <div class="col-md-3">
                    <label for="date_from" class="form-label">Date From</label>
                    <input type="date" class="form-control" id="date_from" name="date_from"
                           value="{{ request('date_from') }}">
                </div>

                {{-- Date To --}}
                <div class="col-md-3">
                    <label for="date_to" class="form-label">Date To</label>
                    <input type="date" class="form-control" id="date_to" name="date_to"
                           value="{{ request('date_to') }}">
                </div>

                {{-- Filter Buttons --}}
                <div class="col-md-6 d-flex align-items-end">
                    <button type="submit" class="btn btn-outline-primary me-2">
                        <i class="fas fa-search me-1"></i> Filter
                    </button>
                    <a href="{{ route('purchase-orders.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-times me-1"></i> Clear
                    </a>
                </div>

            </form>
        </div>
    </div>
</div>



    {{-- Purchase Orders Table --}}
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Purchase Orders List</h5>
            <span class="badge bg-secondary">{{ $orders->total() }} orders</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>PO Number</th>
                            <th>Vendor</th>
                            <th>PO Date</th>
                            <th>Expected Delivery</th>
                            <th>Items Qty</th>
                            <th>Total Amount</th>
                            <th>GST Amount</th>
                            <th>Final Amount</th>
                            <th>Status</th>
                            <th width="150">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                            <tr>
                                <td>
                                    <strong>{{ $order->po_number ?? 'N/A' }}</strong>
                                </td>
                                <td>
    <div>
        <strong>{{ $order->vendor->name ?? 'Unknown Vendor' }}</strong>
        
        @if($order->items && $order->items->count())
            <br>
            <small class="text-muted">
                @foreach($order->items as $item)
                    {{ $item->item_name }}@if (!$loop->last), @endif
                @endforeach
            </small>
        @endif
    </div>
</td>

                                <td>
                                    @if($order->po_date)
                                        {{ \Carbon\Carbon::parse($order->po_date)->format('M d, Y') }}
                                        <br><small class="text-muted">{{ \Carbon\Carbon::parse($order->po_date)->diffForHumans() }}</small>
                                    @else
                                        <span class="text-muted">N/A</span>
                                    @endif
                                </td>
                                <td>
                                    @if($order->expected_delivery)
                                        {{ \Carbon\Carbon::parse($order->expected_delivery)->format('M d, Y') }}
                                        @if(\Carbon\Carbon::parse($order->expected_delivery)->isPast() && $order->status !== 'received')
                                            <br><small class="text-danger"><i class="fas fa-exclamation-triangle"></i> Overdue</small>
                                        @endif
                                    @else
                                        <span class="text-muted">N/A</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-info">
                                      {{ number_format($order->items->sum('quantity'), 0) }}
                                    </span>
                                </td>
                                <td>
                                    <strong>{{ number_format($order->total_amount ?? 0, 2) }}</strong>
                                </td>
                                <td>
                                    {{ number_format($order->gst_amount ?? 0, 2) }}
                                </td>
                                <td>
                                    <strong class="text-success">
                                        {{ number_format($order->final_amount ?? 0, 2) }}
                                    </strong>
                                </td>
                                <td>
                                    <span class="badge bg-{{ match($order->status) {
                                        'approved' => 'success',
                                        'pending' => 'warning',
                                        'received' => 'info',
                                        'ordered' => 'primary',
                                        'cancelled' => 'danger',
                                        default => 'secondary'
                                    } }}">
                                        {{ ucfirst($order->status ?? 'Unknown') }}
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        {{-- View Button --}}
                                        <a href="{{ route('purchase-orders.show', $order) }}" 
                                           class="btn btn-sm btn-outline-info" 
                                           title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        
                                        {{-- Edit Button --}}
                                        @if(!in_array($order->status, ['received', 'cancelled']))
                                            <a href="{{ route('purchase-orders.edit', $order) }}" 
                                               class="btn btn-sm btn-outline-warning" 
                                               title="Edit Order">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        @endif

                                        {{-- Status Update Dropdown --}}
                                        @if(!in_array($order->status, ['received', 'cancelled']))
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-sm btn-outline-primary dropdown-toggle" 
                                                        data-bs-toggle="dropdown" title="Update Status">
                                                    <i class="fas fa-tasks"></i>
                                                </button>
                                                <ul class="dropdown-menu">
                                                    @if($order->status === 'pending')
                                                        <li><a class="dropdown-item status-update" 
                                                               href="#" data-order-id="{{ $order->id }}" 
                                                               data-status="approved">
                                                            <i class="fas fa-check text-success"></i> Approve
                                                        </a></li>
                                                        <li><a class="dropdown-item status-update" 
                                                               href="#" data-order-id="{{ $order->id }}" 
                                                               data-status="cancelled">
                                                            <i class="fas fa-times text-danger"></i> Cancel
                                                        </a></li>
                                                    @elseif($order->status === 'approved')
                                                        <li><a class="dropdown-item status-update" 
                                                               href="#" data-order-id="{{ $order->id }}" 
                                                               data-status="ordered">
                                                            <i class="fas fa-shopping-cart text-primary"></i> Mark as Ordered
                                                        </a></li>
                                                    @elseif($order->status === 'ordered')
                                                        <li><a class="dropdown-item status-update" 
                                                               href="#" data-order-id="{{ $order->id }}" 
                                                               data-status="received">
                                                            <i class="fas fa-check-circle text-info"></i> Mark as Received
                                                        </a></li>
                                                    @endif
                                                </ul>
                                            </div>
                                        @endif

                                        {{-- Delete Button --}}
                                        @if(in_array($order->status, ['pending', 'cancelled']))
                                            <form action="{{ route('purchase-orders.destroy', $order) }}" 
                                                  method="POST" 
                                                  onsubmit="return confirm('Are you sure you want to delete this purchase order?');" 
                                                  style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" 
                                                        title="Delete Order">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="fas fa-inbox fa-3x mb-3"></i>
                                        <p>No purchase orders found.</p>
                                        <a href="{{ route('purchase-orders.create') }}" class="btn btn-primary">
                                            <i class="fas fa-plus"></i> Create First Purchase Order
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
   <!-- {{-- Pagination --}}
        @if($orders->hasPages())
            <div class="card-footer">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="text-muted">
                        Showing {{ $orders->firstItem() }} to {{ $orders->lastItem() }} of {{ $orders->total() }} results
                    </div>
                    {{ $orders->links() }}
                </div>
            </div>
        @endif
    </div>  -->
      
      {{-- Pagination --}}
@if($orders->hasPages())
    <div class="card-footer">
        <div class="d-flex justify-content-between align-items-center">
            <div class="text-muted">
                Showing {{ $orders->firstItem() }} to {{ $orders->lastItem() }} of {{ $orders->total() }} results
            </div>
            <div class="pagination pagination-lg mb-0">
                {{ $orders->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
@endif

    {{-- Summary Cards --}}
    <div class="row mt-4">
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6>Pending Orders</h6>
                            <h3>{{ $orders->where('status', 'pending')->count() }}</h3>
                        </div>
                        <i class="fas fa-clock fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6>Approved Orders</h6>
                            <h3>{{ $orders->where('status', 'approved')->count() }}</h3>
                        </div>
                        <i class="fas fa-check fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6>Received Orders</h6>
                            <h3>{{ $orders->where('status', 'received')->count() }}</h3>
                        </div>
                        <i class="fas fa-box fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6>Total Value</h6>
                            <h3>{{ number_format($orders->sum('final_amount'), 0) }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Status Update Modal --}}
<div class="modal fade" id="statusUpdateModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Update Order Status</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to update this order status?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="confirmStatusUpdate">Update Status</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Status update functionality
    let orderIdToUpdate = null;
    let newStatus = null;
    
    document.querySelectorAll('.status-update').forEach(function(link) {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            orderIdToUpdate = this.getAttribute('data-order-id');
            newStatus = this.getAttribute('data-status');
            
            // Show confirmation modal
            const modal = new bootstrap.Modal(document.getElementById('statusUpdateModal'));
            modal.show();
        });
    });
    
    document.getElementById('confirmStatusUpdate').addEventListener('click', function() {
        if (orderIdToUpdate && newStatus) {
            // Make AJAX request to update status
            fetch(`/purchase-orders/${orderIdToUpdate}/status`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    status: newStatus
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload(); // Reload page to show updated status
                } else {
                    alert('Error updating status: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while updating the status.');
            });
            
            // Hide modal
            bootstrap.Modal.getInstance(document.getElementById('statusUpdateModal')).hide();
        }
    });
});
</script>
@endpush
@endsection
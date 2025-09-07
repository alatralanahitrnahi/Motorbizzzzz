@extends('layouts.app')

@section('title', 'Inventory Management')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-md-6">
            <h2 class="mb-0">
                <i class="fas fa-boxes text-primary"></i>
                Inventory Management
            </h2>
            <p class="text-muted">Manage your inventory batches and stock levels</p>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ route('inventory.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Add New Batch
            </a>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-2">
            <div class="card bg-primary text-white">
                <div class="card-body text-center">
                    <h4>{{ $stats['total_batches'] ?? 0 }}</h4>
                    <small>Total Batches</small>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card bg-success text-white">
                <div class="card-body text-center">
                    <h4>{{ $stats['active_batches'] ?? 0 }}</h4>
                    <small>Active Batches</small>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card bg-warning text-white">
                <div class="card-body text-center">
                    <h4>{{ $stats['expired_batches'] ?? 0 }}</h4>
                    <small>Expired Batches</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body text-center">
                    <h4>₹{{ number_format($stats['total_value'] ?? 0, 2) }}</h4>
                    <small>Total Value</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-danger text-white">
                <div class="card-body text-center">
                    <h4>{{ $stats['low_stock_count'] ?? 0 }}</h4>
                    <small>Low Stock Items</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('inventory.index') }}" class="row g-3" id="filterForm">
                <div class="col-md-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="">All Status</option>
                        @if(isset($statuses) && is_array($statuses))
                            @foreach($statuses as $status)
                                <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>
                                    {{ ucfirst($status) }}
                                </option>
                            @endforeach
                        @else
                            <!-- Fallback options if $statuses is not available -->
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Expired</option>
                            <option value="damaged" {{ request('status') == 'damaged' ? 'selected' : '' }}>Damaged</option>
                            <option value="exhausted" {{ request('status') == 'exhausted' ? 'selected' : '' }}>Exhausted</option>
                        @endif
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Material</label>
                    <input type="text" name="material" class="form-control" 
                           value="{{ request('material') }}" placeholder="Search material...">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Batch Number</label>
                    <input type="text" name="batch_number" class="form-control" 
                           value="{{ request('batch_number') }}" placeholder="Search batch...">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Storage Location</label>
                    <input type="text" name="storage_location" class="form-control" 
                           value="{{ request('storage_location') }}" placeholder="Search location...">
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search"></i> Filter
                    </button>
                    <a href="{{ route('inventory.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Clear
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Inventory Table -->
    <div class="card">
        <div class="card-body">
            @if(isset($batches) && $batches->count() > 0)
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Batch Number</th>
                                <th>Material</th>
                                <th>PO Number</th>
                                <th>Current Stock</th>
                               <th>Recievd Qty</th>
                                <th>Unit Price</th>
                                <th>Total Value</th>
                                <th>Status</th>
                                <th>Warehouse Location</th>
                                <th>Expiry Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($batches as $batch)
                                <tr>
                                    <td>
                                        <strong>{{ $batch->batch_number ?? 'N/A' }}</strong>
                                        @if($batch->quality_grade)
                                            <br><small class="text-muted">Grade: {{ $batch->quality_grade }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        {{ optional($batch->material)->name ?? 'N/A' }}
                                        @if($batch->supplier_batch_number)
                                            <br><small class="text-muted">Supplier: {{ $batch->supplier_batch_number }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        {{ optional($batch->purchaseOrder)->po_number ?? 'N/A' }}
                                        @if(optional($batch->purchaseOrder)->vendor)
                               <br><small class="text-muted">{{ $batch->purchaseOrder->vendor->business_name ?? $batch->purchaseOrder->vendor->name }}</small>
                                    @endif
                                    </td>
                                    <td>
                                        <strong>{{ $batch->current_quantity ?? 0 }}</strong>
                                        @if($batch->current_weight)
                                            <br><small class="text-muted">{{ number_format($batch->current_weight, 3) }} kg</small>
                                        @endif
                                    </td>
                                  <td>
    <strong>{{ $batch->received_quantity ?? 0 }}</strong>
    @if($batch->received_weight)
        <br><small class="text-muted">{{ number_format($batch->received_weight, 3) }} kg</small>
    @endif
</td>

                                    <td>
                                        @if(isset($batch->unit_price) && is_numeric($batch->unit_price))
                                            ₹{{ number_format($batch->unit_price, 2) }}
                                        @else
                                            <span class="text-muted">Not Set</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if(isset($batch->current_quantity) && isset($batch->unit_price) && is_numeric($batch->unit_price))
                                            ₹{{ number_format(($batch->current_quantity ?? 0) * $batch->unit_price, 2) }}
                                        @else
                                            <span class="text-muted">N/A</span>
                                        @endif
                                    </td>
                                    <td>
                                        @php
                                            $statusClass = [
                                                'active' => 'success',
                                                'expired' => 'warning',
                                                'damaged' => 'danger',
                                                'exhausted' => 'secondary'
                                            ][$batch->status ?? 'active'] ?? 'primary';
                                        @endphp
                                        <span class="badge bg-{{ $statusClass }}">
                                            {{ ucfirst($batch->status ?? 'Unknown') }}
                                        </span>
                                    </td>
                                    <td>{{ $batch->warehouse->name ?? 'No Warehouse' }}</td>
                                    <td>
                                        @if($batch->expiry_date)
                                            @php
                                                $expiryDate = \Carbon\Carbon::parse($batch->expiry_date);
                                            @endphp
                                            {{ $expiryDate->format('d/m/Y') }}
                                            @if($expiryDate->isPast())
                                                <br><small class="text-danger">Expired</small>
                                            @elseif($expiryDate->diffInDays() <= 30)
                                                <br><small class="text-warning">Expiring Soon</small>
                                            @endif
                                        @else
                                            <span class="text-muted">No Expiry</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('inventory.show', $batch->id ?? $batch) }}" 
                                               class="btn btn-outline-primary" title="View Details">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('inventory.edit', $batch->id ?? $batch) }}" 
                                               class="btn btn-outline-warning" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            @if(($batch->status ?? '') == 'active')
                                                <button type="button" class="btn btn-outline-success" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#dispatchModal{{ $batch->id }}"
                                                        title="Dispatch">
                                                    <i class="fas fa-shipping-fast"></i>
                                                </button>
                                                <button type="button" class="btn btn-outline-danger" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#damageModal{{ $batch->id }}"
                                                        title="Mark Damaged">
                                                    <i class="fas fa-exclamation-triangle"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center">
                    {{ $batches->appends(request()->query())->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-boxes fa-3x text-muted mb-3"></i>
                    <h5>No inventory batches found</h5>
                    <p class="text-muted">
                        @if(request()->hasAny(['status', 'material', 'batch_number', 'storage_location']))
                            No batches match your search criteria. Try adjusting your filters.
                        @else
                            Start by creating your first inventory batch.
                        @endif
                    </p>
                    @if(!request()->hasAny(['status', 'material', 'batch_number', 'storage_location']))
                        <a href="{{ route('inventory.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Add New Batch
                        </a>
                    @else
                        <a href="{{ route('inventory.index') }}" class="btn btn-secondary me-2">
                            <i class="fas fa-times"></i> Clear Filters
                        </a>
                        <a href="{{ route('inventory.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Add New Batch
                        </a>
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Dispatch and Damage Modals -->
@if(isset($batches))
    @foreach($batches as $batch)
        @if(($batch->status ?? '') == 'active')

        <!-- Dispatch Modal -->
        <div class="modal fade" id="dispatchModal{{ $batch->id }}">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="{{ route('inventory.dispatch') }}" method="POST">
                        @csrf
                        <input type="hidden" name="batch_id" value="{{ $batch->id }}">
                        <div class="modal-header">
                            <h5 class="modal-title">Dispatch - {{ $batch->batch_number }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">

                            <div class="mb-3">
                                <label class="form-label">Available Quantity</label>
                                <input type="text" class="form-control" value="{{ $batch->current_quantity ?? 0 }}" readonly>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <label>Quantity to Dispatch <span class="text-danger">*</span></label>
                                    <input type="number" name="quantity" class="form-control" max="{{ $batch->current_quantity ?? 0 }}" min="1" required>
                                </div>
                                <div class="col-md-6">
                                    <label>Weight (kg) <span class="text-danger">*</span></label>
                                    <input type="number" name="weight" class="form-control" step="0.001" min="0.001" required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label>Dispatch To <span class="text-danger">*</span></label>
                                <select name="dispatch_to" id="dispatch_to_{{ $batch->id }}" class="form-control" onchange="toggleCustomDispatch({{ $batch->id }})" required>
                                    <option value="">-- Select Destination --</option>
                                    <option value="Main Warehouse">Main Warehouse</option>
                                    <option value="Site A">Site A</option>
                                    <option value="Project B">Project B</option>
                                    <option value="Vendor ABC">Vendor ABC</option>
                                    <option value="Customer XYZ">Customer XYZ</option>
                                    <option value="Other">Other (Type Manually)</option>
                                </select>
                            </div>

                            <div class="mb-3" id="custom_dispatch_div_{{ $batch->id }}" style="display: none;">
                                <label>Enter Custom Destination <span class="text-danger">*</span></label>
                                <input type="text" name="custom_dispatch_to" id="custom_dispatch_to_{{ $batch->id }}" class="form-control">
                            </div>

                            <div class="mb-3">
                                <label>Reference Number</label>
                                <input type="text" name="reference_number" class="form-control">
                            </div>

                            <div class="mb-3">
                                <label>Purpose</label>
                                <input type="text" name="purpose" class="form-control">
                            </div>

                            <div class="mb-3">
                                <label>Notes</label>
                                <textarea name="notes" class="form-control" rows="2"></textarea>
                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-shipping-fast"></i> Dispatch
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Damage Modal -->
        <div class="modal fade" id="damageModal{{ $batch->id }}">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="{{ route('inventory.markDamaged') }}" method="POST">
                        @csrf
                        <input type="hidden" name="batch_id" value="{{ $batch->id }}">
                        <div class="modal-header">
                            <h5 class="modal-title">Mark Damaged - {{ $batch->batch_number }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">

                            <div class="mb-3">
                                <label>Available Quantity</label>
                                <input type="text" class="form-control" value="{{ $batch->current_quantity ?? 0 }}" readonly>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <label>Damaged Quantity <span class="text-danger">*</span></label>
                                    <input type="number" name="quantity" class="form-control" max="{{ $batch->current_quantity ?? 0 }}" min="1" required>
                                </div>
                                <div class="col-md-6">
                                    <label>Damaged Weight (kg) <span class="text-danger">*</span></label>
                                    <input type="number" name="weight" class="form-control" step="0.001" min="0.001" required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label>Damage Type <span class="text-danger">*</span></label>
                                <select name="damage_type" class="form-control" required>
                                    <option value="">Select Type</option>
                                    <option value="expired">Expired</option>
                                    <option value="contaminated">Contaminated</option>
                                    <option value="physical_damage">Physical Damage</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label>Reason for Damage <span class="text-danger">*</span></label>
                                <textarea name="reason" class="form-control" rows="3" required></textarea>
                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-danger">
                                <i class="fas fa-exclamation-triangle"></i> Mark Damaged
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        @endif
    @endforeach
@endif

<script>
function toggleCustomDispatch(batchId) {
    var dispatchSelect = document.getElementById("dispatch_to_" + batchId);
    var customDiv = document.getElementById("custom_dispatch_div_" + batchId);
    var customInput = document.getElementById("custom_dispatch_to_" + batchId);
    
    if (dispatchSelect.value === "Other") {
        customDiv.style.display = "block";
        customInput.required = true;
    } else {
        customDiv.style.display = "none";
        customInput.required = false;
        customInput.value = "";
    }
}
</script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-submit filter form when select/input changes (optional)
    const filterForm = document.getElementById('filterForm');
    const autoSubmitInputs = filterForm.querySelectorAll('select[name="status"]');
    
    autoSubmitInputs.forEach(input => {
        input.addEventListener('change', function() {
            // Optional: auto-submit on status change
            // filterForm.submit();
        });
    });
    
    // Add search functionality for text inputs
    const searchInputs = filterForm.querySelectorAll('input[type="text"]');
    searchInputs.forEach(input => {
        input.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                filterForm.submit();
            }
        });
    });
});
</script>

@endsection
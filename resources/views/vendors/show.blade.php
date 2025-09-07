  @extends('layouts.app')

@section('title', 'Vendor Details')

@section('content')
<div class="container-fluid py-4">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm bg-white">
                <div class="card-body py-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h1 class="h2 mb-1 fw-bold text-dark">{{ $vendor->name }}</h1>
                            <p class="mb-0 text-muted">
                                <i class="fas fa-building me-2"></i>
                                {{ $vendor->business_name ?? 'Vendor Details' }}
                            </p>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="{{ route('vendors.index') }}" class="btn btn-outline-secondary btn-sm">
                                <i class="fas fa-arrow-left me-2"></i>Back to List
                            </a>
                            <a href="{{ route('vendors.edit', $vendor->id) }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-edit me-2"></i>Edit Vendor
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="row g-4">
        <!-- Vendor Information -->
        <div class="col-xl-3 col-lg-4 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-primary text-black border-0">
                    <h6 class="mb-0 fw-semibold">
                        <i class="fas fa-user me-2"></i>Vendor Information
                    </h6>
                </div>
                <div class="card-body">
                    <div class="info-item mb-3 text-black">
                        <label class="text-muted small fw-semibold">Full Name</label>
                        <p class="mb-0 fw-medium">{{ $vendor->name }}</p>
                    </div>
                    <div class="info-item mb-3 text-black">
                        <label class="text-muted small fw-semibold">Business Name</label>
                        <p class="mb-0">{{ $vendor->business_name ?? 'Not provided' }}</p>
                    </div>
                    <div class="info-item mb-3 text-black">
                        <label class="text-muted small fw-semibold">Email Address</label>
                        <p class="mb-0">
                            @if($vendor->email)
                                <a href="mailto:{{ $vendor->email }}" class="text-decoration-none">
                                    {{ $vendor->email }}
                                </a>
                            @else
                                Not provided
                            @endif
                        </p>
                    </div>
                    <div class="info-item">
                        <label class="text-muted small fw-semibold text-black">Phone Number</label>
                        <p class="mb-0">
                            <a href="tel:+91{{ $vendor->phone }}" class="text-decoration-none">
                                <i class="fas fa-phone me-2 text-success"></i>+91 {{ $vendor->phone }}
                            </a>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Company Address -->
        <div class="col-xl-3 col-lg-4 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-danger text-black border-0">
                    <h6 class="mb-0 fw-semibold">
                        <i class="fas fa-map-marker-alt me-2"></i>Company Address
                    </h6>
                </div>
                <div class="card-body">
                    <div class="info-item mb-3 text-black">
                        <label class="text-muted small fw-semibold">Address</label>
                        <p class="mb-0">{{ $vendor->company_address }}</p>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <div class="info-item mb-3 text-black">
                                <label class="text-muted small fw-semibold">State</label>
                                <p class="mb-0">{{ $companyState->name ?? 'Not specified' }}</p>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="info-item mb-3">
                                <label class="text-muted small fw-semibold">City</label>
                                <p class="mb-0">{{ $companyCity->name ?? 'Not specified' }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <div class="info-item mb-3 text-black">
                                <label class="text-muted small fw-semibold">Country</label>
                                <p class="mb-0">{{ $vendor->company_country ?? 'India' }}</p>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="info-item text-black">
                                <label class="text-muted small fw-semibold">Pincode</label>
                                <p class="mb-0">{{ $vendor->company_pincode ?? 'Not provided' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Warehouse Address -->
        <div class="col-xl-3 col-lg-4 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-info text-black border-0">
                    <h6 class="mb-0 fw-semibold">
                        <i class="fas fa-warehouse me-2"></i>Warehouse Address
                    </h6>
                </div>
                <div class="card-body">
                    <div class="info-item mb-3 text-black">
                        <label class="text-muted small fw-semibold">Address</label>
                        <p class="mb-0">{{ $vendor->warehouse_address ?? 'Not provided' }}</p>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <div class="info-item mb-3 text-black">
                                <label class="text-muted small fw-semibold">State</label>
                                <p class="mb-0">{{ $warehouseState->name ?? 'Not specified' }}</p>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="info-item mb-3 text-black">
                                <label class="text-muted small fw-semibold">City</label>
                                <p class="mb-0">{{ $warehouseCity->name ?? 'Not specified' }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <div class="info-item mb-3 text-black">
                                <label class="text-muted small fw-semibold">Country</label>
                                <p class="mb-0">{{ $vendor->warehouse_country ?? 'India' }}</p>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="info-item text-black">
                                <label class="text-muted small fw-semibold">Pincode</label>
                                <p class="mb-0">{{ $vendor->warehouse_pincode ?? 'Not provided' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bank Information -->
        @if($vendor->bank_holder_name)
        <div class="col-xl-3 col-lg-4 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-success text-black border-0">
                    <h6 class="mb-0 fw-semibold">
                        <i class="fas fa-university me-2"></i>Bank Details
                    </h6>
                </div>
                <div class="card-body">
                    <div class="info-item mb-3 text-black">
                        <label class="text-muted small fw-semibold">Account Holder</label>
                        <p class="mb-0 fw-medium">{{ $vendor->bank_holder_name }}</p>
                    </div>
                    <div class="info-item mb-3 text-black">
                        <label class="text-muted small fw-semibold">Bank Name</label>
                        <p class="mb-0">{{ $vendor->bank_name }}</p>
                    </div>
                    <div class="info-item mb-3 text-black">
                        <label class="text-muted small fw-semibold">Branch</label>
                        <p class="mb-0">{{ $vendor->branch_name }}</p>
                    </div>
                    <div class="info-item mb-3 text-black">
                        <label class="text-muted small fw-semibold">Account Number</label>
                        <p class="mb-0 font-monospace">{{ $vendor->account_number }}</p>
                    </div>
                    <div class="info-item text-black">
                        <label class="text-muted small fw-semibold">IFSC Code</label>
                        <p class="mb-0 font-monospace">{{ $vendor->ifsc_code }}</p>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- Materials Table -->
    <div class="row mt-5">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-warning text-dark border-0">
                    <h5 class="mb-0 fw-semibold">
                        <i class="fas fa-boxes me-2"></i>Materials Supplied
                    </h5>
                </div>
                <div class="card-body p-0">
                    @if($vendor->materials->isEmpty())
                        <div class="text-center py-5">
                            <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                            <h6 class="text-muted">No materials associated with this vendor</h6>
                            <p class="text-muted small">Materials will appear here once they are linked to this vendor.</p>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="border-0 fw-semibold text-center" style="width: 60px;">#</th>
                                        <th class="border-0 fw-semibold">Material Name</th>
                                        <th class="border-0 fw-semibold text-center">Quantity</th>
                                        <th class="border-0 fw-semibold text-end">Unit Price</th>
                                        <th class="border-0 fw-semibold text-center">GST Rate</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($vendor->materials as $index => $material)
                                        <tr>
                                            <td class="text-center">
                                                <span class="badge bg-light text-dark">{{ $index + 1 }}</span>
                                            </td>
                                            <td>
                                                <div class="fw-medium">{{ $material->pivot->material_name ?? $material->name }}</div>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-primary">{{ $material->pivot->quantity ?? '-' }}</span>
                                            </td>
                                            <td class="text-end">
                                                <span class="fw-medium text-success">
                                                    ₹{{ number_format($material->pivot->unit_price, 2) ?? '-' }}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-info">{{ number_format($material->pivot->gst_rate, 2) ?? '-' }}%</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
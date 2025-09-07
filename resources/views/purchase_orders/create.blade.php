@extends('layouts.app')
@section('title', 'Create Purchase Order')

@section('content')
<!-- Add CSRF token for AJAX requests -->
<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Create Purchase Order</h2>
        <a href="{{ route('purchase-orders.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to List
        </a>
    </div>

  <div id="global-error-message" class="alert alert-danger d-none" role="alert"></div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('purchase-orders.store') }}" method="POST" id="purchaseOrderForm">
        @csrf

        <!-- PO Details -->
        <div class="card mb-4">
            <div class="card-header">Purchase Order Details</div>
            <div class="card-body">
                <div class="row">
                    <!-- Vendor -->
                  <div class="col-md-6 mb-3">
    <label for="vendor_id" class="form-label">Vendor *</label>
    <select name="vendor_id" id="vendor_id" class="form-select @error('vendor_id') is-invalid @enderror" required>
        <option value="">Select Vendor</option>
        @foreach($vendors as $vendor)
            <option value="{{ $vendor->id }}" {{ old('vendor_id') == $vendor->id ? 'selected' : '' }}>
                {{ $vendor->id }}
            </option>
        @endforeach
    </select>
    @error('vendor_id')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>


                    <!-- PO Date -->
                    <div class="col-md-6 mb-3">
                        <label for="po_date" class="form-label">PO Date *</label>
                        <input type="date" name="po_date" id="po_date" class="form-control" value="{{ old('po_date', date('Y-m-d')) }}" required>
                    </div>

                    <!-- Order Date -->
                    <div class="col-md-6 mb-3">
                        <label for="order_date" class="form-label">Order Date *</label>
                        <input type="date" name="order_date" id="order_date" class="form-control" value="{{ old('order_date', date('Y-m-d')) }}" required>
                    </div>

                    <!-- Expected Delivery -->
                    <div class="col-md-6 mb-3">
                        <label for="expected_delivery" class="form-label">Expected Delivery</label>
                        <input type="date" name="expected_delivery" id="expected_delivery" class="form-control" value="{{ old('expected_delivery') }}">
                    </div>

                  
                    <!-- Shipping Address -->
                    <div class="col-md-6 mb-3">
                        <label for="shipping_address" class="form-label">Shipping Address *</label>
                        <textarea name="shipping_address" id="shipping_address" class="form-control" rows="3" required>{{ old('shipping_address') }}</textarea>
                    </div>

                    <!-- Status -->
                    <div class="col-md-6 mb-3">
                        <label for="status" class="form-label">Order Status *</label>
                        <select name="status" id="status" class="form-select" required>
                            @php
                                $statuses = ['pending', 'approved'];
                                $selectedStatus = old('status', 'pending');
                            @endphp
                            @foreach($statuses as $statusOption)
                                <option value="{{ $statusOption }}" {{ $selectedStatus == $statusOption ? 'selected' : '' }}>
                                    {{ ucfirst($statusOption) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Notes -->
                    <div class="col-md-12 mb-3">
                        <label for="notes" class="form-label">Notes</label>
                        <textarea name="notes" id="notes" class="form-control" rows="3">{{ old('notes') }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <!-- Materials Section -->
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>Materials</span>
                <button type="button" class="btn btn-sm btn-outline-success" id="addMaterialRow">+ Add Material</button>
            </div>
            <div class="card-body" id="materialsContainer">
                <div class="row material-row mb-2" data-index="0">
                    <div class="col-md-4">
                        <label for="material_0" class="form-label">Material *</label>
                        <select name="items[0][material_id]" id="material_0" class="form-select material-select" disabled required>
                            <option value="">Select Vendor First</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="unit_price_0" class="form-label">Unit Price *</label>
                        <input type="number" name="items[0][unit_price]" id="unit_price_0" class="form-control unit-price" step="0.01" readonly required>
                    </div>
                    <div class="col-md-2">
                        <label for="quantity_0" class="form-label">Available Quantity *</label>
<!-- Available Quantity (readonly) -->
<input type="number" name="items[${index}][available_quantity]" 
       id="available_quantity_${index}" 
       class="form-control available-quantity" readonly>
                      
                    </div>
                 <div class="col-md-2">
    <label for="order_quantity_${index}" class="form-label">Order Quantity *</label>
<!-- Order Quantity (user input) -->
<input type="number" name="items[${index}][quantity]" 
       id="order_quantity_${index}" 
       class="form-control order-quantity" min="1" step="0.01" required>
                  </div>
                    <div class="col-md-2">
    <label for="gst_rate_0" class="form-label">GST Rate (%)</label>
    <input 
        type="number" 
        name="items[0][gst_rate]" 
        id="gst_rate_0" 
        class="form-control gst-rate bg-light" 
        min="0" 
        max="100" 
        step="0.01" 
        readonly>
</div>
                    <div class="col-md-1">
                        <label for="total_0" class="form-label">Total</label>
                        <input type="number" name="items[0][total_price]" id="total_0" class="form-control total" readonly>
                    </div>
                    <div class="col-md-1 d-flex align-items-end">
                        <button type="button" class="btn btn-danger btn-sm remove-row" disabled> 
                          <i class="fas fa-trash"></i>
                         </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Order Summary -->
        <div class="card mb-4">
            <div class="card-header">Order Summary</div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <label for="total_amount" class="form-label">Subtotal</label>
                        <input type="number" name="total_amount" id="total_amount" class="form-control" readonly>
                    </div>
                    <div class="col-md-4">
                        <label for="gst_amount" class="form-label">GST Amount</label>
                        <input type="number" name="gst_amount" id="gst_amount" class="form-control" readonly>
                    </div>
                    <div class="col-md-4">
                        <label for="final_amount" class="form-label">Final Amount</label>
                        <input type="number" name="final_amount" id="final_amount" class="form-control" readonly>
                    </div>
                </div>
            </div>
        </div>

        <!-- Submit -->
        <button type="submit" class="btn btn-primary">Create Purchase Order</button>
    </form>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    let materialsData = [];
    let materialRowIndex = 0;

    updateRemoveButtons();
    updateOrderTotals();

    $('#vendor_id').change(function() {
        const vendorId = $(this).val();
        if (!vendorId) {
            materialsData = [];
            resetMaterialFields();
            return;
        }
        fetchVendorMaterials(vendorId);
    });

    function fetchVendorMaterials(vendorId) {
        const url = `/api/vendors/${vendorId}/materials`;
        $('.material-select').html('<option value="">Loading...</option>').prop('disabled', true);

        $.ajax({
            url: url,
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'Accept': 'application/json',
            },
            success: function(response) {
                materialsData = Array.isArray(response) ? response : (response.data || response.materials || []);
                if (materialsData.length === 0) {
                    $('.material-select').html('<option value="">No materials available</option>').prop('disabled', true);
                    return;
                }
                resetMaterialFields();
                populateMaterialSelects();
                updateRemoveButtons();
            },
            error: function(xhr) {
                showGlobalError('Error fetching materials. Please try again.');
                resetMaterialFields();
            }
        });
    }

    function showGlobalError(message) {
        const $box = $('#global-error-message');
        $box.text(message).removeClass('d-none');
        $('html, body').animate({ scrollTop: 0 }, 'fast');
    }

    function resetMaterialFields() {
        $('#materialsContainer').html(getMaterialRowHtml(0));
        materialRowIndex = 0;
        if (materialsData.length) populateMaterialSelects();
    }

    function getMaterialRowHtml(index) {
        return `
            <div class="row material-row mb-2" data-index="${index}">
                <div class="col-md-3">
                    <label class="form-label">Material *</label>
                    <select name="items[${index}][material_id]" class="form-select material-select" required>
                        <option value="">Select Material</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Unit Price *</label>
                    <input type="number" name="items[${index}][unit_price]" class="form-control unit-price" step="0.01" readonly required>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Available Qty</label>
                    <input type="number" class="form-control available-quantity" readonly>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Order Qty *</label>
                    <input type="number" name="items[${index}][quantity]" class="form-control order-quantity" min="1" step="0.01" required>
                    <div class="text-danger error-message mt-1 small" style="display: none;"></div>
                </div>
                <div class="col-md-1">
                    <label class="form-label">GST %</label>
                    <input type="number" name="items[${index}][gst_rate]" class="form-control gst-rate" min="0" max="100" step="0.01" readonly>
                </div>
                <div class="col-md-1">
                    <label class="form-label">Total</label>
                    <input type="number" name="items[${index}][total_price]" class="form-control total" readonly>
                </div>
                <div class="col-md-1 d-flex align-items-end">
                    <button type="button" class="btn btn-danger btn-sm remove-row">Remove</button>
                </div>
            </div>`;
    }

function populateMaterialSelects() {
    $('.material-select').each(function () {
        const $select = $(this);
        const currentVal = $select.val();
        const $row = $select.closest('.material-row');

        $select.html('<option value="">Select Material</option>').prop('disabled', false);

        materialsData.forEach(material => {
            const availQty = getAvailableQuantity(material.id, $row);

            $select.append(`
                <option 
                    value="${material.id}" 
                    data-price="${material.unit_price}" 
                    data-gst="${material.gst_rate || 0}" 
                    data-quantity="${availQty}">
                    ${material.name} (${availQty})
                </option>
            `);
        });

        if (currentVal) {
            $select.val(currentVal).trigger('change');
        }
    });
}
  
function getAvailableQuantity(materialId, currentRow) {
    const material = materialsData.find(m => m.id == materialId);
    if (!material) return 0;

    let totalUsed = 0;
    $('.material-row').each(function () {
        const row = $(this);
        const selectedId = row.find('.material-select').val();
        const qty = parseFloat(row.find('.order-quantity').val()) || 0;

        if (!row.is(currentRow) && selectedId == materialId) {
            totalUsed += qty;
        }
    });

    const available = parseFloat(material.quantity) || 0;
    return Math.max(0, available - totalUsed);
}

    $(document).on('change', '.material-select', function () {
        const selected = $(this).find('option:selected');
        const row = $(this).closest('.material-row');
        const materialId = $(this).val();
        const materialName = selected.text().split('(')[0].trim();
        row.find('.unit-price').val(selected.data('price') || '');
        row.find('.gst-rate').val(selected.data('gst') || 0);
        row.find('.order-quantity').val('');
        row.find('.total').val('');

        const availQty = getAvailableQuantity(materialId, row);
        row.find('.available-quantity').val(availQty);

        const qtyInput = row.find('.order-quantity');
        const errorBox = row.find('.error-message');

        if (availQty <= 0) {
const message = `Material '${materialName}' is out of stock. Remaining quantity is 0. You cannot create an order with out-of-stock materials.`;
            errorBox.text(message).show();
            qtyInput.prop('readonly', false).addClass('is-invalid');
            row.addClass('has-error');
            showGlobalError(message);
        } else {
            errorBox.hide();
            qtyInput.prop('readonly', false).removeClass('is-invalid');
            row.removeClass('has-error');

            if ($('.material-row.has-error').length === 0) {
                $('#global-error-message').addClass('d-none').text('');
            }
        }
    });

    $(document).on('input', '.order-quantity', function() {
        const row = $(this).closest('.material-row');
        const materialId = row.find('.material-select').val();
        const orderQty = parseFloat($(this).val()) || 0;
        const availQty = getAvailableQuantity(materialId, row);
        const errorBox = row.find('.error-message');

        row.find('.available-quantity').val(availQty);

        if (orderQty > availQty) {
            errorBox.text(`Available quantity is ${availQty}. You cannot order more.`).show();
            $(this).addClass('is-invalid');
        } else {
            errorBox.hide();
            $(this).removeClass('is-invalid');
        }

        calculateRowTotal(row);
        updateOrderTotals();
    });

    $(document).on('input', '.gst-rate', function() {
        const row = $(this).closest('.material-row');
        calculateRowTotal(row);
        updateOrderTotals();
    });

    function calculateRowTotal(row) {
        const quantity = parseFloat(row.find('.order-quantity').val()) || 0;
        const unitPrice = parseFloat(row.find('.unit-price').val()) || 0;
        const gstRate = parseFloat(row.find('.gst-rate').val()) || 0;

        const subtotal = quantity * unitPrice;
        const gstAmount = (subtotal * gstRate) / 100;
        const total = subtotal + gstAmount;

        row.find('.total').val(total.toFixed(2));
    }

    function updateOrderTotals() {
        let totalAmount = 0, gstAmount = 0, finalAmount = 0;

        $('.material-row').each(function() {
            const quantity = parseFloat($(this).find('.order-quantity').val()) || 0;
            const unitPrice = parseFloat($(this).find('.unit-price').val()) || 0;
            const gstRate = parseFloat($(this).find('.gst-rate').val()) || 0;

            const subtotal = quantity * unitPrice;
            const rowGst = (subtotal * gstRate) / 100;
            const rowTotal = subtotal + rowGst;

            totalAmount += subtotal;
            gstAmount += rowGst;
            finalAmount += rowTotal;
        });

        $('#total_amount').val(totalAmount.toFixed(2));
        $('#gst_amount').val(gstAmount.toFixed(2));
        $('#final_amount').val(finalAmount.toFixed(2));
    }

    $('#addMaterialRow').click(function() {
        if (materialsData.length === 0) {
            showGlobalError('Please select a vendor first.');
            return;
        }

        materialRowIndex++;
        $('#materialsContainer').append(getMaterialRowHtml(materialRowIndex));
        populateMaterialSelects();
        updateRemoveButtons();
    });

    $(document).on('click', '.remove-row', function() {
        $(this).closest('.material-row').remove();
        updateRemoveButtons();
        updateOrderTotals();
    });

    function updateRemoveButtons() {
        const rows = $('.material-row');
        $('.remove-row').prop('disabled', rows.length <= 1);
    }

    $('#purchaseOrderForm').on('submit', function(e) {
        let hasValidMaterials = false;
        let hasErrors = false;
        $('#global-error-message').addClass('d-none').text('');

        $('.material-row').each(function() {
            const row = $(this);
            const materialId = row.find('.material-select').val();
            const orderQty = parseFloat(row.find('.order-quantity').val()) || 0;
            const availQty = getAvailableQuantity(materialId, row);

            if (!materialId || orderQty <= 0) {
                hasErrors = true;
                row.find('.error-message').text('Please select a material and enter quantity.').show();
                row.find('.order-quantity').addClass('is-invalid');
            }

            if (orderQty > availQty) {
                row.find('.error-message').text(`Available quantity is ${availQty}. You cannot order more.`).show();
                row.find('.order-quantity').addClass('is-invalid');
                hasErrors = true;
            }

            if (materialId && orderQty > 0 && orderQty <= availQty) {
                hasValidMaterials = true;
            }
        });

        if (hasErrors || !hasValidMaterials) {
            e.preventDefault();
            const errorMsg = !hasValidMaterials
                ? 'Please add at least one valid material with quantity.'
                : 'Please fix the material quantity errors before submitting.';
            showGlobalError(errorMsg);
        }
    });
});
</script>
@endpush


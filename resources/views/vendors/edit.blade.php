@extends('layouts.app')

@section('content')
<div class="container">
 <div class="card-header d-flex justify-content-between align-items-center">
    <h1>Edit Vendor</h1>
  <a href="{{ route('vendors.index') }}" class="btn btn-primary btn-sm">
    <i class="fas fa-arrow-left me-2"></i>Back to List</a>
    </div>
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <form action="{{ route('vendors.update', $vendor->id) }}" method="POST">
        @csrf
        @method('PUT')

        <!-- Basic Information -->
        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="name" class="form-label">Name *</label>
                <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" 
                       value="{{ old('name', $vendor->name) }}" required minlength="2" maxlength="100">
                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-6 mb-3">
                <label for="business_name" class="form-label">Business Name</label>
                <input type="text" name="business_name" id="business_name" 
                       class="form-control @error('business_name') is-invalid @enderror" 
                       value="{{ old('business_name', $vendor->business_name) }}" maxlength="150">
                @error('business_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>

        <!-- Contact Information -->
        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" 
                       value="{{ old('email', $vendor->email) }}" maxlength="100">
                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-6 mb-3">
                <label for="phone" class="form-label">Phone *</label>
                <div class="input-group">
                    <span class="input-group-text">+91</span>
                    <input type="text" name="phone" id="phone" class="form-control @error('phone') is-invalid @enderror"
                           value="{{ old('phone', $vendor->phone) }}" maxlength="10" pattern="[6-9]\d{9}" required
                           title="Enter 10 digit mobile number starting with 6-9">
                    @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
        </div>
 
  <!-- 📍 Address Information -->
<div class="card mb-4">
    <div class="card-header">
        <h5 class="mb-0">Address Information</h5>
    </div>
    <div class="card-body">
        <!-- 📦 Company Address -->
        <div class="address-section mb-4">
            <h6><i class="fas fa-building"></i> Company Address *</h6>
            <div class="row">
                <div class="col-md-12 mb-3">
                    <label for="company_address" class="form-label">Street Address *</label>
                    <textarea name="company_address" id="company_address"
                              class="form-control @error('company_address') is-invalid @enderror"
                              rows="2" required minlength="10" maxlength="500"
                              placeholder="Enter street address, building name, etc.">{{ old('company_address', $vendor->company_address ?? '') }}</textarea>
                    @error('company_address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="company_state" class="form-label">State *</label>
                    <!-- Company State -->
<select name="company_state" id="company_state" class="form-select @error('company_state') is-invalid @enderror" required>
    <option value="">Select State</option>
    @foreach($states as $state)
        <option value="{{ $state->id }}"
            {{ old('company_state', $vendor->company_state) == $state->id ? 'selected' : '' }}>
            {{ $state->name }}
        </option>
    @endforeach
</select>
                    @error('company_state')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="company_city" class="form-label">City *</label>
                    <!-- Company City -->
<select name="company_city" id="company_city" class="form-select @error('company_city') is-invalid @enderror" required>
    <option value="">Select City</option>
    @foreach($cities as $city)
        <option value="{{ $city->id }}"
            {{ old('company_city', $vendor->company_city) == $city->id ? 'selected' : '' }}>
            {{ $city->name }}
        </option>
    @endforeach
</select>
                    @error('company_city')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="company_pincode" class="form-label">Pincode *</label>
                    <input type="text" name="company_pincode" id="company_pincode"
                           class="form-control @error('company_pincode') is-invalid @enderror"
                           value="{{ old('company_pincode', $vendor->company_pincode ?? '') }}" maxlength="6"
                           pattern="[0-9]{6}" required placeholder="Enter 6-digit pincode">
                    @error('company_pincode')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="company_country" class="form-label">Country *</label>
                    <select name="company_country" id="company_country"
                            class="form-select @error('company_country') is-invalid @enderror" required>
                        <option value="India" {{ old('company_country', $vendor->company_country ?? 'India') === 'India' ? 'selected' : '' }}>India</option>
                    </select>
                    @error('company_country')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
        </div>

        <!-- 🏬 Warehouse Address -->
        <div class="address-section">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6><i class="fas fa-warehouse"></i> Warehouse Address</h6>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="same_as_company" name="same_as_company">
                    <label class="form-check-label" for="same_as_company">
                        Same as Company Address
                    </label>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 mb-3">
                    <label for="warehouse_address" class="form-label">Street Address</label>
                    <textarea name="warehouse_address" id="warehouse_address"
                              class="form-control @error('warehouse_address') is-invalid @enderror"
                              rows="2" maxlength="500"
                              placeholder="Enter warehouse street address">{{ old('warehouse_address', $vendor->warehouse_address ?? '') }}</textarea>
                    @error('warehouse_address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="warehouse_state" class="form-label">State</label>
                   <select name="warehouse_state" id="warehouse_state"
        class="form-select @error('warehouse_state') is-invalid @enderror">
    <option value="">Select State</option>
    @foreach($states as $state)
        <option value="{{ $state->id }}"
            {{ old('warehouse_state', $vendor->warehouse_state) == $state->id ? 'selected' : '' }}>
            {{ $state->name }}
        </option>
    @endforeach
</select>
@error('warehouse_state')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="warehouse_city" class="form-label">City</label>
                 <select name="warehouse_city" id="warehouse_city"
        class="form-select @error('warehouse_city') is-invalid @enderror">
    <option value="">Select City</option>
    @foreach($cities as $city)
        <option value="{{ $city->id }}"
            {{ old('warehouse_city', $vendor->warehouse_city) == $city->id ? 'selected' : '' }}>
            {{ $city->name }}
        </option>
    @endforeach
</select>
@error('warehouse_city')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="warehouse_pincode" class="form-label">Pincode</label>
                    <input type="text" name="warehouse_pincode" id="warehouse_pincode"
                           class="form-control @error('warehouse_pincode') is-invalid @enderror"
                           value="{{ old('warehouse_pincode', $vendor->warehouse_pincode ?? '') }}" maxlength="6"
                           pattern="[0-9]{6}" placeholder="Enter 6-digit pincode">
                    @error('warehouse_pincode')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="warehouse_country" class="form-label">Country</label>
                    <select name="warehouse_country" id="warehouse_country"
                            class="form-select @error('warehouse_country') is-invalid @enderror">
                        <option value="India" {{ old('warehouse_country', $vendor->warehouse_country ?? 'India') === 'India' ? 'selected' : '' }}>India</option>
                    </select>
                    @error('warehouse_country')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
        </div>
    </div>
</div>

      
 <!-- Materials Table -->
<table id="materials-table" class="table">
    <thead>
        <tr>
            <th>Material</th>
            <th>SKU</th>
            <th>Barcode</th>
            <th>Unit</th>
            <th>Unit Price</th>
            <th>Quantity</th>
            <th>Remove</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($vendor->materials as $index => $row)
            <tr data-row-id="{{ $row->pivot->id ?? $row->id }}">
                <td>
                    <select name="materials[{{ $index }}][name]" class="form-control material-name-select">
                        @foreach ($materials as $material)
                            <option value="{{ $material->id }}"
                                data-sku="{{ $material->sku }}"
                                data-barcode="{{ $material->barcode }}"
                                data-unit="{{ $material->unit }}"
                                data-gst="{{ $material->gst_rate }}"
                                {{ $material->id == $row->id ? 'selected' : '' }}>
                                {{ $material->name }}
                            </option>
                        @endforeach
                    </select>
                </td>
                <td><input type="text" class="form-control sku-input" value="{{ $row->sku }}" readonly></td>
                <td><input type="text" class="form-control barcode-input" value="{{ $row->barcode }}" readonly></td>
                <td><input type="text" class="form-control unit-input" value="{{ $row->unit }}" readonly></td>
                <td>
                    <input type="number" step="0.01" class="form-control unit-price-input"
                           name="materials[{{ $index }}][price]"
                           value="{{ $row->pivot->unit_price }}">
                </td>
                <td>
                    <input type="number" step="1" min="1" class="form-control quantity-input"
                           name="materials[{{ $index }}][quantity]"
                           value="{{ $row->pivot->quantity ?? '' }}">
                </td>
                <td><button type="button" class="btn btn-danger btn-sm remove-row">Remove</button></td>
            </tr>
        @endforeach
    </tbody>
</table>

<!-- Add Material Button -->
<button type="button" class="btn btn-primary" id="add-material-row">Add Material</button>

<!-- Template for new material row -->
<template id="material-row-template">
  <tr>
    <td>
      <select class="form-control material-name-select">
        @foreach ($materials as $material)
          <option value="{{ $material->id }}"
            data-sku="{{ $material->sku }}"
            data-barcode="{{ $material->barcode }}"
            data-unit="{{ $material->unit }}"
            data-gst="{{ $material->gst_rate }}">
            {{ $material->name }}
          </option>
        @endforeach
      </select>
    </td>
    <td><input type="text" class="form-control sku-input" readonly></td>
    <td><input type="text" class="form-control barcode-input" readonly></td>
    <td><input type="text" class="form-control unit-input" readonly></td>
    <td><input type="number" step="0.01" class="form-control unit-price-input" value="0.00"></td>
    <td><input type="number" step="1" min="1" class="form-control quantity-input" value="1"></td>
    <td><button type="button" class="btn btn-danger btn-sm remove-row">Remove</button></td>
  </tr>
</template>
<!-- Load jQuery (place this BEFORE your custom script) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- JavaScript -->
<script>
$(document).ready(function () {
  // Track initial selections
  $('.material-name-select').each(function () {
    $(this).data('last-value', $(this).val());
  });

  // On material change
  $(document).on('change', '.material-name-select', function () {
    const select = $(this);
    const row = select.closest('tr');
    const rowId = row.data('row-id');
    const oldMaterialId = select.data('last-value');
    const vendorId = $('#selectedVendorId').val();
    const currentPrice = row.find('.unit-price-input').val();

    // Update old material if existing row
    if (vendorId && rowId) {
      $.ajax({
        url: `/api/material-row/${rowId}`,
        type: 'PUT',
        data: {
          material_id: oldMaterialId,
          vendor_id: vendorId,
          unit_price: currentPrice
        }
      }).always(function () {
        updateMaterialDetails(select, vendorId, row, rowId);
      });
    } else {
      updateMaterialDetails(select, vendorId, row, rowId);
    }
  });

  // Update material details (SKU, price, etc.)
  function updateMaterialDetails(select, vendorId, row, rowId) {
    const selected = select.find('option:selected');
    const newMaterialId = selected.val();

    row.find('.sku-input').val(selected.data('sku'));
    row.find('.barcode-input').val(selected.data('barcode'));
    row.find('.unit-input').val(selected.data('unit'));

    $.get(`/api/vendor-material-price?vendor_id=${vendorId}&material_id=${newMaterialId}`, function (res) {
      const price = res.unit_price ?? 0;
      row.find('.unit-price-input').val(price);

      if (rowId) {
        $.ajax({
          url: `/api/material-row/${rowId}`,
          type: 'PUT',
          data: {
            material_id: newMaterialId,
            vendor_id: vendorId,
            unit_price: price
          }
        });
      }

      select.data('last-value', newMaterialId);
    });
  }

  // Remove row
  $(document).on('click', '.remove-row', function () {
    $(this).closest('tr').remove();
  });

  // Add new row
  $('#add-material-row').on('click', function () {
    const index = $('#materials-table tbody tr').length;
    const $template = $($('#material-row-template').html());

    $template.find('.material-name-select').attr('name', `materials[${index}][name]`);
    $template.find('.unit-price-input').attr('name', `materials[${index}][price]`);
    $template.find('.quantity-input').attr('name', `materials[${index}][quantity]`);

    $('#materials-table tbody').append($template);
  });
});
</script>


<!-- Hidden input for vendor ID (used in JS) -->
<input type="hidden" id="selectedVendorId" value="{{ $vendor->id }}">

        <!-- Bank Details -->
        <hr class="my-4">
        <h3>Bank Details</h3>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="bank_holder_name" class="form-label">Account Holder Name *</label>
                <input type="text" name="bank_holder_name" id="bank_holder_name" class="form-control @error('bank_holder_name') is-invalid @enderror" 
                       value="{{ old('bank_holder_name', $vendor->bank_holder_name) }}" required>
                @error('bank_holder_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-6 mb-3">
                <label for="branch_name" class="form-label">Branch Name *</label>
                <input type="text" name="branch_name" id="branch_name" class="form-control @error('branch_name') is-invalid @enderror" 
                       value="{{ old('branch_name', $vendor->branch_name) }}" required minlength="2" maxlength="100">
                @error('branch_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>

<div class="row">
          
      <div class="col-md-6">
    <div class="mb-3">
        <label for="bank_search" class="form-label">
            Bank Name <span class="text-danger">*</span>
        </label>
        <div class="dropdown-container position-relative">
            <div class="search-container">
                <i class="fas fa-search search-icon"></i>
                <input 
                    type="text" 
                    id="bank_search" 
                    class="form-control search-input"
                    placeholder="Search or select bank..."
                    value="{{ old('bank_name', $vendor->bank_name) }}" 
                    autocomplete="off"
                >
            </div>
            <div class="bank-dropdown position-absolute bg-white border w-100" id="bankDropdown" style="max-height: 200px; overflow-y: auto; z-index: 1000;"></div>
            <input type="hidden" name="bank_name" id="bank_name" value="{{ old('bank_name', $vendor->bank_name) }}" required>
        </div>
        @error('bank_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
        <div class="invalid-feedback" id="bank_name-error"></div>
    </div>
</div>


            <div class="col-md-6 mb-3">
                <label for="account_number" class="form-label">Account Number *</label>
                <input type="text" name="account_number" id="account_number" class="form-control @error('account_number') is-invalid @enderror" 
                       value="{{ old('account_number', $vendor->account_number) }}" required>
                @error('account_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-6 mb-3">
                <label for="ifsc_code" class="form-label">IFSC Code *</label>
                <input type="text" name="ifsc_code" id="ifsc_code" class="form-control @error('ifsc_code') is-invalid @enderror" 
                       value="{{ old('ifsc_code', $vendor->ifsc_code) }}" style="text-transform: uppercase" required>
                @error('ifsc_code')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>

        <div class="mt-4">
            <button type="submit" class="btn btn-success">Update Vendor</button>
            <a href="{{ route('vendors.index') }}" class="btn btn-secondary">Cancel</a>
        </div>

    </form>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
document.getElementById('same_as_company').addEventListener('change', function () {
    const checked = this.checked;

    const fields = ['address', 'state', 'city', 'pincode', 'country'];
    fields.forEach(field => {
        const companyVal = document.getElementById(`company_${field}`).value;
        const warehouseInput = document.getElementById(`warehouse_${field}`);

        if (checked) {
            warehouseInput.value = companyVal;
            warehouseInput.disabled = true;
        } else {
            warehouseInput.disabled = false;
            warehouseInput.value = '';
        }

        // If it's a select, also trigger change for dependent fields
        if (warehouseInput.tagName === 'SELECT') {
            warehouseInput.dispatchEvent(new Event('change'));
        }
    });
});
  
  $(document).ready(function () {
    const companyStateID = $('#company_state').val();
    const companyCityID = "{{ old('company_city', $vendor->company_city ?? '') }}";

    const warehouseStateID = $('#warehouse_state').val();
    const warehouseCityID = "{{ old('warehouse_city', $vendor->warehouse_city ?? '') }}";

    // Auto-load cities if company state is pre-selected
    if (companyStateID) {
        $('#company_city').prop('disabled', false);
        $.get(`/locations/cities/${companyStateID}`, function (data) {
            $('#company_city').empty().append('<option value="">Select City</option>');
            data.forEach(city => {
                const selected = city.id == companyCityID ? 'selected' : '';
                $('#company_city').append(`<option value="${city.id}" ${selected}>${city.name}</option>`);
            });
        });
    }

    // Auto-load cities if warehouse state is pre-selected
    if (warehouseStateID) {
        $('#warehouse_city').prop('disabled', false);
        $.get(`/locations/cities/${warehouseStateID}`, function (data) {
            $('#warehouse_city').empty().append('<option value="">Select City</option>');
            data.forEach(city => {
                const selected = city.id == warehouseCityID ? 'selected' : '';
                $('#warehouse_city').append(`<option value="${city.id}" ${selected}>${city.name}</option>`);
            });
        });
    }
});

</script>





<script>
 document.addEventListener('DOMContentLoaded', () => {
    const bankSearch = document.getElementById('bank_search');
    const bankDropdown = document.getElementById('bankDropdown');
    const bankHiddenInput = document.getElementById('bank_name');

    const bankList = [
        { name: 'State Bank of India' }, { name: 'HDFC Bank' }, { name: 'ICICI Bank' },
        { name: 'Axis Bank' }, { name: 'Kotak Mahindra Bank' }, { name: 'Punjab National Bank' },
        { name: 'Bank of Baroda' }, { name: 'Canara Bank' }, { name: 'Union Bank of India' },
        { name: 'Bank of India' }, { name: 'Indian Bank' }, { name: 'Central Bank of India' },
        { name: 'Indian Overseas Bank' }, { name: 'UCO Bank' }, { name: 'Bank of Maharashtra' },
        { name: 'Punjab & Sind Bank' }, { name: 'IndusInd Bank' }, { name: 'Yes Bank' },
        { name: 'IDFC First Bank' }, { name: 'Federal Bank' }, { name: 'South Indian Bank' },
        { name: 'Karur Vysya Bank' }, { name: 'City Union Bank' }, { name: 'Dhanlaxmi Bank' },
        { name: 'RBL Bank' }, { name: 'Bandhan Bank' }, { name: 'IDBI Bank' },
        { name: 'Tamil Nadu Mercantile Bank' }, { name: 'DCB Bank' }, { name: 'Lakshmi Vilas Bank' }
    ];

    function populateDropdown(searchTerm = '') {
        bankDropdown.innerHTML = '';
        const filteredBanks = bankList.filter(bank => 
            bank.name.toLowerCase().includes(searchTerm.toLowerCase())
        );

        if (filteredBanks.length === 0) {
            const noResult = document.createElement('div');
            noResult.classList.add('dropdown-item', 'text-muted');
            noResult.textContent = 'No banks found';
            bankDropdown.appendChild(noResult);
            return;
        }

        filteredBanks.forEach(bank => {
            const div = document.createElement('div');
            div.classList.add('dropdown-item');
            div.style.cursor = 'pointer';
            div.textContent = bank.name;
            div.addEventListener('click', () => {
                bankSearch.value = bank.name;
                bankHiddenInput.value = bank.name;
                bankDropdown.innerHTML = '';
            });
            bankDropdown.appendChild(div);
        });
    }

    bankSearch.addEventListener('input', () => {
        populateDropdown(bankSearch.value);
    });

    // On load, make sure hidden input has correct value
    bankHiddenInput.value = bankSearch.value;

    // Close dropdown if clicked outside
    document.addEventListener('click', (e) => {
        if (!bankSearch.contains(e.target) && !bankDropdown.contains(e.target)) {
            bankDropdown.innerHTML = '';
        }
    });
});

</script>
@endsection

@extends('layouts.app')
@section('title', 'Create Vendor')
@section('content')

<style>
.dropdown-container {
    position: relative;
}

.bank-option {
    padding: 8px 16px;
    cursor: pointer;
    border-bottom: 1px solid #f0f0f0;
    transition: background-color 0.2s;
}

.bank-option:hover {
    background-color: #f8f9fa;
}

.bank-option:last-child {
    border-bottom: none;
}

.dropdown-menu.show {
    display: block;
}

#bankDropdown {
    top: 100%;
    left: 0;
    border: 1px solid #dee2e6;
    border-radius: 0.375rem;
    background-color: white;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
}

.bank-option.highlighted {
    background-color: #e9ecef;
}
</style>

<div class="container">
  <div class="card-header d-flex justify-content-between align-items-center">
            <h3> Create Vendor</h3>
  <a href="{{ route('vendors.index') }}" class="btn btn-primary btn-sm">
    <i class="fas fa-arrow-left me-2"></i>Back to List</a>
    </div>
    <!-- Success Message (Hidden by default) -->
    <div id="success-message" class="alert alert-success alert-dismissible fade" role="alert" style="display: none;">
        <strong>Success!</strong> Vendor created successfully!
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    
    <form id="vendor-form" action="{{ route('vendors.store') }}" method="POST">
        @csrf
        
        <!-- Basic Information -->
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="name" class="form-label">Name *</label>
                    <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" 
                           value="{{ old('name') }}" required minlength="2" maxlength="100">
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    <div class="invalid-feedback" id="name-error"></div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="business_name" class="form-label">Business Name</label>
                    <input type="text" name="business_name" id="business_name" 
                           class="form-control @error('business_name') is-invalid @enderror" 
                           value="{{ old('business_name') }}" maxlength="150">
                    @error('business_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    <div class="invalid-feedback" id="business_name-error"></div>
                </div>
            </div>
        </div>

        <!-- Contact Information -->
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" 
                           value="{{ old('email') }}" maxlength="100">
                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    <div class="invalid-feedback" id="email-error"></div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="phone" class="form-label">Phone *</label>
                    <div class="input-group">
                        <span class="input-group-text">+91</span>
                        <input type="text" name="phone" id="phone" class="form-control @error('phone') is-invalid @enderror"
                               value="{{ old('phone') }}" maxlength="10" pattern="[6-9]\d{9}" required
                               title="Enter 10 digit mobile number starting with 6-9">
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="invalid-feedback" id="phone-error"></div>
                    </div>
                </div>
            </div>
        </div>

         <!-- Address Information -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Address Information</h5>
            </div>
            <div class="card-body">
                <!-- Company Address -->
                <div class="address-section">
                    <h6><i class="fas fa-building"></i> Company Address *</h6>
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="company_address" class="form-label">Street Address *</label>
                            <textarea name="company_address" id="company_address" 
                                      class="form-control @error('company_address') is-invalid @enderror" rows="2"
                                      placeholder="Enter street address, building name, etc." required minlength="10" maxlength="500">{{ old('company_address') }}</textarea>
                            @error('company_address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            <div class="invalid-feedback" id="company_address-error"></div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="company_state" class="form-label">State *</label>
                            <select name="company_state" id="company_state" class="form-select @error('company_state') is-invalid @enderror" required>
                                <option value="">Select State</option>
                                <!-- States will be populated by JavaScript -->
                            </select>
                            @error('company_state')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            <div class="invalid-feedback" id="company_state-error"></div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="company_city" class="form-label">City *</label>
                            <select name="company_city" id="company_city" class="form-select @error('company_city') is-invalid @enderror" required disabled>
                                <option value="">Select City</option>
                            </select>
                            @error('company_city')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            <div class="invalid-feedback" id="company_city-error"></div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="company_pincode" class="form-label">Pincode *</label>
                            <input type="text" name="company_pincode" id="company_pincode" 
                                   class="form-control @error('company_pincode') is-invalid @enderror" 
                                   value="{{ old('company_pincode') }}" maxlength="6" pattern="[0-9]{6}" required
                                   placeholder="Enter 6-digit pincode">
                            @error('company_pincode')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            <div class="invalid-feedback" id="company_pincode-error"></div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="company_country" class="form-label">Country *</label>
                            <select name="company_country" id="company_country" class="form-select @error('company_country') is-invalid @enderror" required>
                                <option value="India" selected>India</option>
                            </select>
                            @error('company_country')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            <div class="invalid-feedback" id="company_country-error"></div>
                        </div>
                    </div>
                </div>

                <!-- Warehouse Address -->
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
                                      class="form-control @error('warehouse_address') is-invalid @enderror" rows="2"
                                      placeholder="Enter warehouse street address" maxlength="500">{{ old('warehouse_address') }}</textarea>
                            @error('warehouse_address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            <div class="invalid-feedback" id="warehouse_address-error"></div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="warehouse_state" class="form-label">State</label>
                            <select name="warehouse_state" id="warehouse_state" class="form-select @error('warehouse_state') is-invalid @enderror">
                                <option value="">Select State</option>
                                <!-- States will be populated by JavaScript -->
                            </select>
                            @error('warehouse_state')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            <div class="invalid-feedback" id="warehouse_state-error"></div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="warehouse_city" class="form-label">City</label>
                            <select name="warehouse_city" id="warehouse_city" class="form-select @error('warehouse_city') is-invalid @enderror" disabled>
                                <option value="">Select City</option>
                            </select>
                            @error('warehouse_city')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            <div class="invalid-feedback" id="warehouse_city-error"></div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="warehouse_pincode" class="form-label">Pincode</label>
                            <input type="text" name="warehouse_pincode" id="warehouse_pincode" 
                                   class="form-control @error('warehouse_pincode') is-invalid @enderror" 
                                   value="{{ old('warehouse_pincode') }}" maxlength="6" pattern="[0-9]{6}"
                                   placeholder="Enter 6-digit pincode">
                            @error('warehouse_pincode')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            <div class="invalid-feedback" id="warehouse_pincode-error"></div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="warehouse_country" class="form-label">Country</label>
                            <select name="warehouse_country" id="warehouse_country" class="form-select @error('warehouse_country') is-invalid @enderror">
                                <option value="">Select Country</option>
                                <option value="India">India</option>
                            </select>
                            @error('warehouse_country')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            <div class="invalid-feedback" id="warehouse_country-error"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
      
        <!-- 📦 Vendor Material Entry -->
        <hr>
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5>Material Entry</h5>
            <button type="button" class="btn btn-outline-primary btn-sm" id="add-new-material-btn">
                <i class="fas fa-plus"></i> Add Another Material
            </button>
        </div>

        <!-- Materials Container -->
        <div id="materials-container">
            <!-- First Material Entry (Always visible) -->
            <div class="material-entry border rounded p-3 mb-3" data-index="0">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h6 class="mb-0">Material #1</h6>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Material Name *</label>
                      <select class="form-select material-name-select" name="materials[0][name]" data-index="0">
    <option value="">Select Material</option>
    <!-- Dynamically populated -->
</select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Unit Price *</label>
                        <input type="number" step="0.01" class="form-control unit-price-input" 
                               name="materials[0][price]" placeholder="Enter unit price" disabled data-index="0">
                    </div>
                <div class="col-md-6 mb-3">
    <label class="form-label">Quantity *</label>
    <input type="number" step="1" min="1" class="form-control quantity-input" 
           name="materials[0][quantity]" placeholder="Enter quantity" disabled data-index="0">
</div>

                    <div class="col-md-12 mb-3 material-info" style="display: none;" data-index="0">
                        <div class="p-3 border rounded bg-light">
                            <div class="row">
                                <div class="col-md-3"><strong>Unit:</strong> <span class="unit"></span></div>
                                <div class="col-md-3"><strong>GST Rate:</strong> <span class="gst"></span></div>
                                <div class="col-md-3"><strong>SKU:</strong> <span class="sku"></span></div>
                                <div class="col-md-3"><strong>Barcode:</strong> <span class="barcode"></span></div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12 mb-3 material-warning" style="display: none;" data-index="0"></div>

                    <div class="col-md-12 mb-3 text-end">
                        <button type="button" class="btn btn-success add-item-btn" disabled data-index="0">
                            Add to List
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Added Materials List -->
        <div id="added-materials-section" style="display: none;">
            <hr>
            <h5>Added Materials</h5>
            <div class="table-responsive">
                <table class="table table-striped" id="added-materials-table">
                    <thead>
                        <tr>
                            <th>Material Name</th>
                            <th>Unit</th>
                            <th>Unit Price</th>
                          <th>Quantity</th>
                            <th>GST Rate</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="added-materials-body">
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Bank Details Section (Initially Hidden) -->
        <div id="bank-details-section" style="display: none;">
            <hr class="my-4">
            <h3>Bank Details</h3>
            <div class="row">
                <!-- Bank Holder Name -->
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="bank_holder_name" class="form-label">
                            Bank Account Holder Name <span class="text-danger">*</span>
                        </label>
                        <input 
                            type="text" 
                            name="bank_holder_name" 
                            id="bank_holder_name" 
                            class="form-control"
                            placeholder="Enter account holder name"
                            value="{{ old('bank_holder_name') }}"
                            required
                        >
                        @error('bank_holder_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        <div class="invalid-feedback" id="bank_holder_name-error"></div>
                    </div>
                </div>
              
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="branch_name" class="form-label">Branch Name <span class="text-danger">*</span></label>
                        <input 
                            type="text" 
                            name="branch_name" 
                            id="branch_name" 
                            class="form-control @error('branch_name') is-invalid @enderror" 
                            value="{{ old('branch_name') }}" 
                            required minlength="2" maxlength="100"
                            placeholder="Enter branch name">
                        @error('branch_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        <div class="invalid-feedback" id="branch_name-error"></div>
                    </div>
                </div>

                <!-- Bank Selection -->
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="bank_search" class="form-label">
                            Bank Name <span class="text-danger">*</span>
                        </label>
                        <div class="dropdown-container position-relative">
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-search"></i>
                                </span>
                                <input 
                                    type="text" 
                                    id="bank_search" 
                                    class="form-control"
                                    placeholder="Search or select bank..."
                                    value="{{ old('bank_name') }}"
                                    autocomplete="off"
                                >
                                <button class="btn btn-outline-secondary" type="button" id="bank_dropdown_toggle">
                                    <i class="fas fa-chevron-down"></i>
                                </button>
                            </div>
                            <ul class="dropdown-menu w-100 position-absolute" id="bankDropdown" style="max-height: 200px; overflow-y: auto; z-index: 1000;">
                                <!-- Bank options will be populated here -->
                            </ul>
                            <input type="hidden" name="bank_name" id="bank_name" value="{{ old('bank_name') }}" required>
                        </div>
                        @error('bank_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        <div class="invalid-feedback" id="bank_name-error"></div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Account Number -->
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="account_number" class="form-label">
                            Account Number <span class="text-danger">*</span>
                        </label>
                        <input 
                            type="text" 
                            name="account_number" 
                            id="account_number" 
                            class="form-control"
                            placeholder="Enter account number"
                            value="{{ old('account_number') }}"
                            required
                        >
                        @error('account_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        <div class="invalid-feedback" id="account_number-error"></div>
                    </div>
                </div>

                <!-- IFSC Code -->
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="ifsc_code" class="form-label">
                            IFSC Code <span class="text-danger">*</span>
                        </label>
                        <input 
                            type="text" 
                            name="ifsc_code" 
                            id="ifsc_code" 
                            class="form-control"
                            placeholder="Enter IFSC code"
                            value="{{ old('ifsc_code') }}"
                            style="text-transform: uppercase"
                            required
                        >
                        @error('ifsc_code')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        <div class="invalid-feedback" id="ifsc_code-error"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-4">
            <button type="button" id="continue-btn" class="btn btn-primary">Continue</button>
<button type="button" id="submit-btn" class="btn btn-success" style="display: none;">Save Vendor</button>
            <a href="{{ route('vendors.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>


<script>
document.addEventListener('DOMContentLoaded', function () {
    let materialIndex = 0;
    let materialsData = [];

    // Fetch materials
    fetch('/materials/all')
        .then(res => res.json())
        .then(materials => {
            materialsData = materials;
            populateMaterialSelect(materialIndex);
        })
        .catch(err => console.error('Error fetching materials:', err));

    // Populate <select> for a given index
    function populateMaterialSelect(index) {
        const select = document.querySelector(`select[name="materials[${index}][name]"]`);
        select.innerHTML = '<option value="">Select Material</option>';
        materialsData.forEach(mat => {
            const option = document.createElement('option');
            option.value = mat.name;
            option.textContent = mat.name;
            option.dataset.info = JSON.stringify(mat);
            select.appendChild(option);
        });

        // On change, fill fields
        select.addEventListener('change', function () {
            const selectedOption = this.options[this.selectedIndex];
            if (!selectedOption.value) return;
            const material = JSON.parse(selectedOption.dataset.info);
            fillMaterialFields(index, material);
        });
    }

    // Fill selected material details
    function fillMaterialFields(index, material) {
        const priceInput = document.querySelector(`input[name="materials[${index}][price]"]`);
        const qtyInput = document.querySelector(`input[name="materials[${index}][quantity]"]`);
        const infoBox = document.querySelector(`.material-info[data-index="${index}"]`);
        const addBtn = document.querySelector(`.add-item-btn[data-index="${index}"]`);

        priceInput.value = material.unit_price;
        priceInput.disabled = false;

        qtyInput.value = 1;
        qtyInput.disabled = false;

        infoBox.querySelector('.unit').textContent = material.unit;
        infoBox.querySelector('.gst').textContent = material.gst_rate + '%';
        infoBox.querySelector('.sku').textContent = material.sku || '—';
        infoBox.querySelector('.barcode').textContent = material.barcode || '—';
        infoBox.style.display = 'block';

        addBtn.disabled = false;
        addBtn.onclick = () => addMaterialToList(index, material);
    }

    // Add material to list
    function addMaterialToList(index, material) {
        const quantity = document.querySelector(`input[name="materials[${index}][quantity]"]`).value;
        const price = document.querySelector(`input[name="materials[${index}][price]"]`).value;

        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${material.name}</td>
            <td>${material.unit}</td>
            <td>₹${parseFloat(price).toFixed(2)}</td>
            <td>${quantity}</td>
            <td>${material.gst_rate}%</td>
            <td><button class="btn btn-sm btn-danger remove-material">Remove</button></td>
        `;

        document.querySelector('#added-materials-body').appendChild(row);
        document.querySelector('#added-materials-section').style.display = 'block';

        row.querySelector('.remove-material').addEventListener('click', function () {
            row.remove();
            if (document.querySelectorAll('#added-materials-body tr').length === 0) {
                document.querySelector('#added-materials-section').style.display = 'none';
            }
        });

        const warning = document.querySelector(`.material-warning[data-index="${index}"]`);
        warning.innerHTML = `<div class="alert alert-success">Added to list.</div>`;
        warning.style.display = 'block';

        const addBtn = document.querySelector(`.add-item-btn[data-index="${index}"]`);
        addBtn.disabled = true;
    }

    // Add new material entry UI
    document.getElementById('add-new-material-btn').addEventListener('click', function () {
        materialIndex++;
        const container = document.getElementById('materials-container');

        const div = document.createElement('div');
        div.classList.add('material-entry', 'border', 'rounded', 'p-3', 'mb-3');
        div.dataset.index = materialIndex;
        div.innerHTML = `
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h6 class="mb-0">Material #${materialIndex + 1}</h6>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Material Name *</label>
                    <select class="form-select material-name-select" name="materials[${materialIndex}][name]" data-index="${materialIndex}">
                        <option value="">Select Material</option>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Unit Price *</label>
                    <input type="number" step="0.01" class="form-control unit-price-input"
                           name="materials[${materialIndex}][price]" placeholder="Enter unit price" disabled data-index="${materialIndex}">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Quantity *</label>
                    <input type="number" step="1" min="1" class="form-control quantity-input"
                           name="materials[${materialIndex}][quantity]" placeholder="Enter quantity" disabled data-index="${materialIndex}">
                </div>
                <div class="col-md-12 mb-3 material-info" style="display: none;" data-index="${materialIndex}">
                    <div class="p-3 border rounded bg-light">
                        <div class="row">
                            <div class="col-md-3"><strong>Unit:</strong> <span class="unit"></span></div>
                            <div class="col-md-3"><strong>GST Rate:</strong> <span class="gst"></span></div>
                            <div class="col-md-3"><strong>SKU:</strong> <span class="sku"></span></div>
                            <div class="col-md-3"><strong>Barcode:</strong> <span class="barcode"></span></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 mb-3 material-warning" style="display: none;" data-index="${materialIndex}"></div>
                <div class="col-md-12 mb-3 text-end">
                    <button type="button" class="btn btn-success add-item-btn" disabled data-index="${materialIndex}">
                        Add to List
                    </button>
                </div>
            </div>
        `;
        container.appendChild(div);
        populateMaterialSelect(materialIndex);
    });
});
</script>


<!-- jQuery and Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>



<script>
document.addEventListener('DOMContentLoaded', function () {
    const stateSelectors = {
        company: {
            state: document.getElementById('company_state'),
            city: document.getElementById('company_city'),
        },
        warehouse: {
            state: document.getElementById('warehouse_state'),
            city: document.getElementById('warehouse_city'),
        }
    };

    // Fetch all states and populate both dropdowns
    fetch('/api/states')
        .then(res => res.json())
        .then(states => {
            Object.values(stateSelectors).forEach(section => {
                states.forEach(state => {
                    const option = new Option(state.name, state.id);
                    section.state.appendChild(option.cloneNode(true));
                });
            });
        });

    // Function to handle city fetching for a given section
    function handleCityPopulation(sectionKey) {
        const stateSelect = stateSelectors[sectionKey].state;
        const citySelect = stateSelectors[sectionKey].city;

        stateSelect.addEventListener('change', function () {
            const stateId = this.value;
            citySelect.innerHTML = '<option value="">Select City</option>';
            citySelect.disabled = true;

            if (stateId) {
                fetch(`/api/cities/${stateId}`)
                    .then(res => res.json())
                    .then(cities => {
                        cities.forEach(city => {
                            const option = new Option(city.name, city.id);
                            citySelect.appendChild(option);
                        });
                        citySelect.disabled = false;
                    });
            }
        });
    }

    handleCityPopulation('company');
    handleCityPopulation('warehouse');

    // Same as company address checkbox
    const sameCheckbox = document.getElementById('same_as_company');
    sameCheckbox.addEventListener('change', function () {
        if (this.checked) {
            document.getElementById('warehouse_address').value = document.getElementById('company_address').value;
            document.getElementById('warehouse_state').value = document.getElementById('company_state').value;

            // Trigger change to populate warehouse cities
            document.getElementById('warehouse_state').dispatchEvent(new Event('change'));

            // Wait a bit for city fetch then set selected value
            setTimeout(() => {
                document.getElementById('warehouse_city').value = document.getElementById('company_city').value;
            }, 300);

            document.getElementById('warehouse_pincode').value = document.getElementById('company_pincode').value;
            document.getElementById('warehouse_country').value = document.getElementById('company_country').value;
        } else {
            // Clear warehouse fields
            document.getElementById('warehouse_address').value = '';
            document.getElementById('warehouse_state').value = '';
            document.getElementById('warehouse_city').innerHTML = '<option value="">Select City</option>';
            document.getElementById('warehouse_city').disabled = true;
            document.getElementById('warehouse_pincode').value = '';
            document.getElementById('warehouse_country').value = '';
        }
    });
});
</script>

<script>
  
  
// Bank list for dropdown
const bankList = [
    { name: 'State Bank of India', code: 'SBIN' },
    { name: 'HDFC Bank', code: 'HDFC' },
    { name: 'ICICI Bank', code: 'ICIC' },
    { name: 'Axis Bank', code: 'UTIB' },
    { name: 'Kotak Mahindra Bank', code: 'KKBK' },
    { name: 'Punjab National Bank', code: 'PUNB' },
    { name: 'Bank of Baroda', code: 'BARB' },
    { name: 'Canara Bank', code: 'CNRB' },
    { name: 'Union Bank of India', code: 'UBIN' },
    { name: 'Bank of India', code: 'BKID' },
    { name: 'Indian Bank', code: 'IDIB' },
    { name: 'Central Bank of India', code: 'CBIN' },
    { name: 'Indian Overseas Bank', code: 'IOBA' },
    { name: 'UCO Bank', code: 'UCBA' },
    { name: 'Bank of Maharashtra', code: 'MAHB' },
    { name: 'Punjab & Sind Bank', code: 'PSIB' },
    { name: 'IndusInd Bank', code: 'INDB' },
    { name: 'Yes Bank', code: 'YESB' },
    { name: 'IDFC First Bank', code: 'IDFB' },
    { name: 'Federal Bank', code: 'FDRL' },
    { name: 'South Indian Bank', code: 'SIBL' },
    { name: 'Karur Vysya Bank', code: 'KVBL' },
    { name: 'City Union Bank', code: 'CIUB' },
    { name: 'Dhanlaxmi Bank', code: 'DLXB' },
    { name: 'RBL Bank', code: 'RATN' },
    { name: 'Bandhan Bank', code: 'BDBL' },
    { name: 'IDBI Bank', code: 'IBKL' },
    { name: 'Tamil Nadu Mercantile Bank', code: 'TMBL' },
    { name: 'DCB Bank', code: 'DCBL' },
    { name: 'Lakshmi Vilas Bank', code: 'LAVB' }
];

// Validation functions
function validateName(name) {
    if (!name || name.trim().length < 2) {
        return 'Name must be at least 2 characters long';
    }
    if (name.length > 100) {
        return 'Name must not exceed 100 characters';
    }
    if (!/^[a-zA-Z\s]+$/.test(name)) {
        return 'Name can only contain letters and spaces';
    }
    return null;
}

function validateEmail(email) {
    if (email && email.length > 0) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email)) {
            return 'Please enter a valid email address';
        }
        if (email.length > 100) {
            return 'Email must not exceed 100 characters';
        }
    }
    return null;
}

function validateBranchName(branchName) {
    if (!branchName || branchName.trim().length < 2) {
        return 'Branch name must be at least 2 characters long';
    }
    if (branchName.length > 100) {
        return 'Branch name must not exceed 100 characters';
    }
    if (!/^[a-zA-Z0-9\s\-&]+$/.test(branchName)) {
        return 'Branch name can only contain letters, numbers, spaces, hyphens, and ampersands';
    }
    return null;
}

function validatePhone(phone) {
    if (!phone || phone.trim().length === 0) {
        return 'Phone number is required';
    }
    if (!/^[6-9]\d{9}$/.test(phone)) {
        return 'Phone number must be 10 digits starting with 6-9';
    }
    return null;
}

function validateAddress(address, fieldName) {
    if (!address || address.trim().length < 10) {
        return `${fieldName} must be at least 10 characters long`;
    }
    if (address.length > 500) {
        return `${fieldName} must not exceed 500 characters`;
    }
    return null;
}

function validateIFSC(ifsc) {
    if (!ifsc || ifsc.trim().length === 0) {
        return 'IFSC code is required';
    }
    
    // Remove any spaces and convert to uppercase
    ifsc = ifsc.trim().toUpperCase();
    
    // IFSC format: 4 letters + 1 zero + 6 alphanumeric characters
    if (!/^[A-Z]{4}0[A-Z0-9]{6}$/.test(ifsc)) {
        return 'Invalid IFSC code format (e.g., SBIN0001234)';
    }
    
    return null;
}

function validateAccountNumber(accountNumber) {
    if (!accountNumber || accountNumber.trim().length === 0) {
        return 'Account number is required';
    }
    if (!/^\d{9,18}$/.test(accountNumber)) {
        return 'Account number must be 9-18 digits only';
    }
    return null;
}

function validateBankName(bankName) {
    if (!bankName || bankName.trim().length === 0) {
        return 'Bank name is required';
    }
    return null;
}

function validateBankHolderName(holderName) {
    if (!holderName || holderName.trim().length < 2) {
        return 'Bank holder name must be at least 2 characters long';
    }
    if (holderName.length > 100) {
        return 'Bank holder name must not exceed 100 characters';
    }
    if (!/^[a-zA-Z\s]+$/.test(holderName)) {
        return 'Bank holder name can only contain letters and spaces';
    }
    return null;
}

// Show error function
function showError(fieldId, message) {
    console.error(`Validation error in field "${fieldId}": ${message}`); // Add this
    const field = document.getElementById(fieldId);
    const errorElement = document.getElementById(fieldId + '-error');
    
    if (field && errorElement) {
        field.classList.add('is-invalid');
        errorElement.textContent = message;
        errorElement.style.display = 'block';
    }
}

// Clear error function
function clearError(fieldId) {
    const field = document.getElementById(fieldId);
    const errorElement = document.getElementById(fieldId + '-error');
    
    if (field) {
        field.classList.remove('is-invalid');
    }
    if (errorElement) {
        errorElement.style.display = 'none';
    }
}

// Bank dropdown functionality
function setupBankDropdown() {
    const bankSearch = document.getElementById('bank_search');
    const bankDropdown = document.getElementById('bankDropdown');
    const bankNameHidden = document.getElementById('bank_name');
    const dropdownToggle = document.getElementById('bank_dropdown_toggle');
    
    if (!bankSearch || !bankDropdown) {
        console.error('Bank search elements not found');
        return;
    }
    
    let currentHighlightIndex = -1;
    let filteredBanks = [];
    
    // Show dropdown on focus
    bankSearch.addEventListener('focus', function() {
        showBankOptions('');
    });
    
    // Show dropdown on toggle button click
    if (dropdownToggle) {
        dropdownToggle.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            if (bankDropdown.classList.contains('show')) {
                hideBankDropdown();
            } else {
                showBankOptions(bankSearch.value);
            }
        });
    }
    
    // Filter options on input
    bankSearch.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        showBankOptions(searchTerm);
        currentHighlightIndex = -1;
    });
    
    // Handle keyboard navigation
    bankSearch.addEventListener('keydown', function(e) {
        const options = bankDropdown.querySelectorAll('.bank-option');
        
        switch(e.key) {
            case 'ArrowDown':
                e.preventDefault();
                currentHighlightIndex = Math.min(currentHighlightIndex + 1, options.length - 1);
                updateHighlight(options);
                break;
            case 'ArrowUp':
                e.preventDefault();
                currentHighlightIndex = Math.max(currentHighlightIndex - 1, -1);
                updateHighlight(options);
                break;
            case 'Enter':
                e.preventDefault();
                if (currentHighlightIndex >= 0 && options[currentHighlightIndex]) {
                    selectBank(filteredBanks[currentHighlightIndex]);
                }
                break;
            case 'Escape':
                hideBankDropdown();
                break;
        }
    });
    
    // Hide dropdown when clicking outside
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.dropdown-container')) {
            hideBankDropdown();
        }
    });
    
    function showBankOptions(searchTerm) {
        filteredBanks = bankList.filter(bank => 
            bank.name.toLowerCase().includes(searchTerm.toLowerCase())
        );
        
        bankDropdown.innerHTML = '';
        
        if (filteredBanks.length === 0) {
            const noOption = document.createElement('div');
            noOption.className = 'bank-option text-muted';
            noOption.textContent = 'No banks found';
            bankDropdown.appendChild(noOption);
        } else {
            filteredBanks.forEach((bank, index) => {
                const option = document.createElement('div');
                option.className = 'bank-option';
                option.textContent = bank.name;
                option.addEventListener('click', function() {
                    selectBank(bank);
                });
                bankDropdown.appendChild(option);
            });
        }
        
        bankDropdown.classList.add('show');
        currentHighlightIndex = -1;
    }
    
    function selectBank(bank) {
        bankSearch.value = bank.name;
        bankNameHidden.value = bank.name;
        hideBankDropdown();
        clearError('bank_name');
        console.log('Bank selected:', bank.name);
    }
    
    function hideBankDropdown() {
        bankDropdown.classList.remove('show');
        currentHighlightIndex = -1;
    }
    
    function updateHighlight(options) {
        options.forEach((option, index) => {
            option.classList.toggle('highlighted', index === currentHighlightIndex);
        });
        
        // Scroll highlighted option into view
        if (currentHighlightIndex >= 0 && options[currentHighlightIndex]) {
            options[currentHighlightIndex].scrollIntoView({
                block: 'nearest',
                behavior: 'smooth'
            });
        }
    }
}

// Fetch bank details from IFSC code
function fetchBankDetails(ifscCode) {
    // Convert to uppercase and trim
    ifscCode = ifscCode.trim().toUpperCase();
    const ifscField = document.getElementById('ifsc_code');
    if (ifscField) {
        ifscField.value = ifscCode;
    }
    
    // Clear previous errors
    clearError('ifsc_code');
    
    if (ifscCode.length === 11) {
        // Validate IFSC format first
        const validationError = validateIFSC(ifscCode);
        if (validationError) {
            showError('ifsc_code', validationError);
            return;
        }
        
        // Make API call to fetch bank details
        fetch(`https://ifsc.razorpay.com/${ifscCode}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data && data.BANK) {
                    // Auto-fill bank name
                    const bankNameField = document.getElementById('bank_name');
                    const bankSearchField = document.getElementById('bank_search');
                    
                    if (bankNameField && bankSearchField) {
                        bankNameField.value = data.BANK;
                        bankSearchField.value = data.BANK;
                        clearError('bank_name');
                    }
                    
                    // Auto-fill branch name if available
                    const branchField = document.getElementById('branch_name');
                    if (branchField && data.BRANCH) {
                        branchField.value = data.BRANCH;
                        clearError('branch_name');
                    }
                    
                    console.log('Bank details fetched successfully:', data);
                } else {
                    throw new Error('Invalid IFSC code or bank not found');
                }
            })
            .catch(error => {
                console.log('IFSC lookup failed:', error);
                showError('ifsc_code', 'Invalid IFSC code or bank details not found');
                
                // Clear auto-filled fields
                const bankNameField = document.getElementById('bank_name');
                const bankSearchField = document.getElementById('bank_search');
                
                if (bankNameField) bankNameField.value = '';
                if (bankSearchField) bankSearchField.value = '';
            });
    } else if (ifscCode.length > 0) {
        // Partial IFSC entered, clear validation errors
        clearError('ifsc_code');
    }
}

  document.addEventListener('DOMContentLoaded', function () {
        setupBankDropdown();
    setupRealTimeValidation();
    setupInputFormatting();
    setupContinueButton();  // <-- This must be here
    setupFormSubmission();
    
// Setup real-time validation
function setupRealTimeValidation() {
    const validationFields = [
        { id: 'name', validator: validateName },
        { id: 'email', validator: validateEmail },
        { id: 'phone', validator: validatePhone },
        { id: 'company_address', validator: (val) => validateAddress(val, 'Company address') },
        { id: 'warehouse_address', validator: (val) => val ? validateAddress(val, 'Warehouse address') : null },
        { id: 'account_number', validator: validateAccountNumber },
        { id: 'ifsc_code', validator: validateIFSC },
        { id: 'bank_name', validator: validateBankName },
        { id: 'bank_holder_name', validator: validateBankHolderName },
        { id: 'branch_name', validator: validateBranchName },
    ];

    validationFields.forEach(field => {
        const element = document.getElementById(field.id);
        
        if (element) {
            element.addEventListener('blur', function() {
                const error = field.validator(this.value);
                if (error) {
                    showError(field.id, error);
                } else {
                    clearError(field.id);
                }
            });
        }
    });
    
    // Special handling for IFSC field with debouncing
    const ifscField = document.getElementById('ifsc_code');
    if (ifscField) {
        let ifscTimeout;
        
        ifscField.addEventListener('input', function() {
            clearTimeout(ifscTimeout);
            ifscTimeout = setTimeout(() => {
                fetchBankDetails(this.value);
            }, 500); // 500ms delay
        });
        
        ifscField.addEventListener('blur', function() {
            const error = validateIFSC(this.value);
            if (error) {
                showError('ifsc_code', error);
            } else {
                clearError('ifsc_code');
            }
        });
    }
}

// Input formatting functions
function setupInputFormatting() {
    // Format IFSC input
    const ifscField = document.getElementById('ifsc_code');
    if (ifscField) {
        ifscField.addEventListener('input', function(e) {
            // Convert to uppercase and remove any invalid characters
            let value = this.value.toUpperCase().replace(/[^A-Z0-9]/g, '');
            
            // Limit to 11 characters
            if (value.length > 11) {
                value = value.substring(0, 11);
            }
            
            this.value = value;
        });
    }

    // Restrict phone input to numbers only
    const phoneField = document.getElementById('phone');
    if (phoneField) {
        phoneField.addEventListener('input', function(e) {
            this.value = this.value.replace(/\D/g, '');
            if (this.value.length > 10) {
                this.value = this.value.substring(0, 10);
            }
        });
    }

    // Restrict account number to numbers only
    const accountField = document.getElementById('account_number');
    if (accountField) {
        accountField.addEventListener('input', function(e) {
            this.value = this.value.replace(/\D/g, '');
            if (this.value.length > 18) {
                this.value = this.value.substring(0, 18);
            }
        });
    }
}

// Continue button functionality
function setupContinueButton() {
    const continueBtn = document.getElementById('continue-btn');
    if (!continueBtn) return;
    
    continueBtn.addEventListener('click', function() {
        console.log('Continue button clicked');
        
        // Validate basic information first
        let isValid = true;
        const basicFields = ['name', 'phone', 'company_address'];
        
        basicFields.forEach(fieldId => {
            const field = document.getElementById(fieldId);
            
            if (field) {
                let error = null;
                
                switch(fieldId) {
                    case 'name':
                        error = validateName(field.value);
                        break;
                    case 'phone':
                        error = validatePhone(field.value);
                        break;
                    case 'company_address':
                        error = validateAddress(field.value, 'Company address');
                        break;
                }
                
                if (error) {
                    showError(fieldId, error);
                    isValid = false;
                } else {
                    clearError(fieldId);
                }
            }
        });
        
        // Validate email if provided
        const emailField = document.getElementById('email');
        if (emailField && emailField.value) {
            const emailError = validateEmail(emailField.value);
            if (emailError) {
                showError('email', emailError);
                isValid = false;
            } else {
                clearError('email');
            }
        }
        
        console.log('Validation result:', isValid);
        
        if (isValid) {
            // Show bank details section
            const bankSection = document.getElementById('bank-details-section');
            const submitBtn = document.getElementById('submit-btn');
            
            if (bankSection) {
                bankSection.style.display = 'block';
                console.log('Bank section shown');
            }
            
            // Hide continue button and show submit button
            this.style.display = 'none';
            if (submitBtn) {
                submitBtn.style.display = 'inline-block';
            }
            
            // Scroll to bank details section
            if (bankSection) {
                bankSection.scrollIntoView({ behavior: 'smooth' });
            }
        } else {
            console.log('Validation failed, scrolling to first error'); // Debug log
            // Scroll to first error
            const firstError = document.querySelector('.is-invalid');
            if (firstError) {
                firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                firstError.focus();
            }
        }
    });
}

// Form submission
function setupFormSubmission() {
    const form = document.getElementById('vendor-form');
    const submitBtn = document.getElementById('submit-btn');

    if (!form || !submitBtn) return;

    submitBtn.addEventListener('click', function(e) {
        e.preventDefault();
        console.log('Submit button clicked');

        let isValid = true;

        const allFields = [
            { id: 'name', validator: validateName },
            { id: 'email', validator: validateEmail },
            { id: 'phone', validator: validatePhone },
            { id: 'company_address', validator: (val) => validateAddress(val, 'Company address') },
            { id: 'ifsc_code', validator: validateIFSC },
            { id: 'account_number', validator: validateAccountNumber },
            { id: 'bank_name', validator: validateBankName },
            { id: 'bank_holder_name', validator: validateBankHolderName },
            { id: 'branch_name', validator: validateBranchName }
        ];

        // Validate main fields
        allFields.forEach(field => {
            const element = document.getElementById(field.id);
            if (element) {
                const error = field.validator(element.value);
                if (error) {
                    showError(field.id, error);
                    isValid = false;
                } else {
                    clearError(field.id);
                }
            }
        });

        // Optional field: warehouse_address
        const warehouseField = document.getElementById('warehouse_address');
        if (warehouseField && warehouseField.value.trim() !== '') {
            const warehouseError = validateAddress(warehouseField.value, 'Warehouse address');
            if (warehouseError) {
                showError('warehouse_address', warehouseError);
                isValid = false;
            } else {
                clearError('warehouse_address');
            }
        }

        if (isValid) {
            console.log('Validation passed. Submitting form...');
            form.submit();
        } else {
            console.warn('Validation failed. Not submitting.');
            const firstError = document.querySelector('.is-invalid');
            if (firstError) {
                firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                firstError.focus();
            }
        }
    });
}
    });

</script>
@endsection
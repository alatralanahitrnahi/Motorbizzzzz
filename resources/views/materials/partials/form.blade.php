<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>{{ isset($material) ? 'Edit' : 'Create' }} Materials</h2>
        <a href="{{ route('materials.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Details
        </a>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form id="materialForm" action="{{ isset($material) ? route('materials.update', $material->id) : route('materials.store') }}" method="POST">
        @csrf
        @if(isset($material))
            @method('PUT')
        @endif

        <div class="row">
            <!-- Material Name -->
            <div class="col-md-6 mb-3">
                <label for="name" class="form-label">Material Name *</label>
                <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $material->name ?? '') }}" required>
                <div class="invalid-feedback" id="nameError"></div>
            </div>

            <!-- Code -->
            <div class="col-md-6 mb-3">
                <label for="code" class="form-label">Code *</label>
                <div class="input-group">
                    <input type="text" name="code" id="code" class="form-control" value="{{ old('code', $material->code ?? '') }}" readonly required>
                    <button type="button" class="btn btn-outline-secondary" id="regenerateCode" title="Regenerate Code">
                        <i class="fas fa-refresh"></i>
                    </button>
                </div>
                <div class="invalid-feedback" id="codeError"></div>
            </div>

            <!-- SKU -->
            <div class="col-md-6 mb-3">
                <label for="sku" class="form-label">SKU</label>
                <div class="input-group">
                    <input type="text" name="sku" id="sku" class="form-control" value="{{ old('sku', $material->sku ?? '') }}" readonly>
                    <button type="button" class="btn btn-outline-secondary" id="previewSku" title="Preview SKU">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
                <small class="text-muted">SKU will be auto-generated based on category and name</small>
                <div class="invalid-feedback" id="skuError"></div>
            </div>

            <!-- Barcode -->
            <div class="col-md-6 mb-3">
                <label for="barcode" class="form-label">Barcode</label>
                <div class="input-group">
                    <input type="text" name="barcode" id="barcode" class="form-control" value="{{ old('barcode', $material->barcode ?? '') }}" readonly>
                    <button type="button" class="btn btn-outline-secondary" id="previewBarcode" title="Preview Barcode">
                        <i class="fas fa-barcode"></i>
                    </button>
                </div>
                <small class="text-muted">Barcode will be auto-generated (EAN-13 format)</small>
                <div class="invalid-feedback" id="barcodeError"></div>
            </div>

            <!-- Description -->
            <div class="col-md-12 mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea name="description" id="description" class="form-control" rows="3">{{ old('description', $material->description ?? '') }}</textarea>
                <div class="invalid-feedback" id="descriptionError"></div>
            </div>

            <!-- Category -->
            <div class="col-md-6 mb-3">
                <label for="category" class="form-label">Category</label>
                <input type="text" name="category" id="category" class="form-control" value="{{ old('category', $material->category ?? '') }}" placeholder="e.g., Electronics, Hardware, Raw Material">
                <div class="invalid-feedback" id="categoryError"></div>
            </div>

            <!-- Unit -->
            <div class="col-md-6 mb-3">
                <label for="unit" class="form-label">Unit *</label>
                <select name="unit" id="unit" class="form-select" required>
                    <option value="">Select Unit</option>

                    <!-- Weight -->
                    <option value="gram" {{ old('unit', $material->unit ?? '') == 'gram' ? 'selected' : '' }}>Gram (g)</option>
                    <option value="kg" {{ old('unit', $material->unit ?? '') == 'kg' ? 'selected' : '' }}>Kilogram (kg)</option>
                    <option value="ton" {{ old('unit', $material->unit ?? '') == 'ton' ? 'selected' : '' }}>Ton (t)</option>

                    <!-- Volume -->
                    <option value="milliliter" {{ old('unit', $material->unit ?? '') == 'milliliter' ? 'selected' : '' }}>Milliliter (ml)</option>
                    <option value="liter" {{ old('unit', $material->unit ?? '') == 'liter' ? 'selected' : '' }}>Liter (L)</option>
                    <option value="bucket" {{ old('unit', $material->unit ?? '') == 'bucket' ? 'selected' : '' }}>Bucket (bkt)</option>
                    <option value="drum" {{ old('unit', $material->unit ?? '') == 'drum' ? 'selected' : '' }}>Drum (dr)</option>

                    <!-- Length -->
                    <option value="millimeter" {{ old('unit', $material->unit ?? '') == 'millimeter' ? 'selected' : '' }}>Millimeter (mm)</option>
                    <option value="centimeter" {{ old('unit', $material->unit ?? '') == 'centimeter' ? 'selected' : '' }}>Centimeter (cm)</option>
                    <option value="meter" {{ old('unit', $material->unit ?? '') == 'meter' ? 'selected' : '' }}>Meter (m)</option>

                    <!-- Count -->
                    <option value="piece" {{ old('unit', $material->unit ?? '') == 'piece' ? 'selected' : '' }}>Piece (pc)</option>
                    <option value="bag" {{ old('unit', $material->unit ?? '') == 'bag' ? 'selected' : '' }}>Bag (bag)</option>
                    <option value="box" {{ old('unit', $material->unit ?? '') == 'box' ? 'selected' : '' }}>Box (box)</option>
                    <option value="roll" {{ old('unit', $material->unit ?? '') == 'roll' ? 'selected' : '' }}>Roll (roll)</option>
                    <option value="pack" {{ old('unit', $material->unit ?? '') == 'pack' ? 'selected' : '' }}>Pack (pk)</option>
                    <option value="set" {{ old('unit', $material->unit ?? '') == 'set' ? 'selected' : '' }}>Set (set)</option>
                    <option value="carton" {{ old('unit', $material->unit ?? '') == 'carton' ? 'selected' : '' }}>Carton (ctn)</option>
                    <option value="pallet" {{ old('unit', $material->unit ?? '') == 'pallet' ? 'selected' : '' }}>Pallet (plt)</option>
                </select>
                <div class="invalid-feedback" id="unitError"></div>
            </div>

            <!-- Dynamic Dimension Fields -->
            <div class="col-12 mb-3">
                <div class="row" id="dimension-fields"></div>
                <div class="row" id="previewDimensions"></div>
            </div>

            <!-- Unit Price -->
            <div class="col-md-6 mb-3">
                <label for="unit_price" class="form-label">Unit Price (₹) *</label>
                <input type="number" step="0.01" name="unit_price" id="unit_price" class="form-control" value="{{ old('unit_price', $material->unit_price ?? '') }}" required>
                <div class="invalid-feedback" id="unit_priceError"></div>
            </div>

            <!-- GST Rate -->
           <div class="col-md-6 mb-3">
                <label for="gst_rate" class="form-label">GST Rate (%) *</label>
                <input type="number" step="0.01" name="gst_rate" id="gst_rate" class="form-control" value="{{ old('gst_rate', $material->gst_rate ?? 18.00) }}" required>
                <div class="invalid-feedback" id="gst_rateError"></div>
            </div>

            <!-- Is Available -->
            <div class="col-md-6 mb-3">
                <label for="is_available" class="form-label">Is Available</label>
                <select name="is_available" id="is_available" class="form-select">
                    <option value="1" {{ old('is_available', $material->is_available ?? 1) == 1 ? 'selected' : '' }}>Yes</option>
                    <option value="0" {{ old('is_available', $material->is_available ?? 1) == 0 ? 'selected' : '' }}>No</option>
                </select>
                <div class="invalid-feedback" id="is_availableError"></div>
            </div>
        </div>

        <!-- Preview Section -->
        <div class="row mt-4" id="previewSection" style="display: none;">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-eye"></i> Material Preview</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <strong>Name:</strong> <span id="previewName">-</span><br>
                                <strong>Code:</strong> <span id="previewCode">-</span><br>
                                <strong>Category:</strong> <span id="previewCategory">-</span>
                            </div>
                            <div class="col-md-4">
                                <strong>SKU:</strong> <span id="previewSkuValue">-</span><br>
                                <strong>Unit:</strong> <span id="previewUnit">-</span><br>
                                <strong>Price:</strong> ₹<span id="previewPrice">0.00</span>
                            </div>
                            <div class="col-md-4">
                                <strong>Barcode:</strong> <span id="previewBarcodeValue">-</span><br>
                                <div id="barcodeDisplay" style="font-family: 'Libre Barcode 128', monospace; font-size: 40px; text-align: center; margin-top: 10px;"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="row mt-4">
            <div class="col-12">
                <button type="button" class="btn btn-info me-2" id="togglePreview">
                    <i class="fas fa-eye"></i> Toggle Preview
                </button>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> {{ isset($material) ? 'Update' : 'Save' }} Material
                </button>
                <a href="{{ route('materials.index') }}" class="btn btn-secondary ms-2">
                    <i class="fas fa-times"></i> Cancel
                </a>
            </div>  
        </div>
    </form>
</div>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.0.0/css/all.min.css">

<script>
document.addEventListener('DOMContentLoaded', function () {
 // Get dimension values from server (for edit mode)
    const dimensionValues = {
        length: {!! json_encode(old('dimensions.length', $material->dimensions['length'] ?? '')) !!},
        width: {!! json_encode(old('dimensions.width', $material->dimensions['width'] ?? '')) !!},
        height: {!! json_encode(old('dimensions.height', $material->dimensions['height'] ?? '')) !!}
    };
console.log(dimensionValues);

    const unitGroups = {
        boxUnits: ['box', 'carton', 'bag', 'pack', 'set'],
        cylinderUnits: ['drum', 'bucket', 'roll'],
        lengthUnits: ['millimeter', 'centimeter', 'meter'],
        weightUnits: ['gram', 'kg', 'ton'],
        volumeUnits: ['milliliter', 'liter'],
        countUnits: ['piece', 'pallet']
    };

    const unitSelect = document.getElementById('unit');

    function storeDimensionValues() {
        ['length', 'width', 'height'].forEach(id => {
            const el = document.getElementById(id);
            if (el) dimensionValues[id] = el.value;
        });
    }

    function createDimensionField(id, label, value = '', placeholder = '0.00') {
        return `
            <div class="col-md-4 mb-3 dimension-field">
                <label for="${id}" class="form-label">${label}</label>
                <input type="number" step="0.01" min="0" name="dimensions[${id}]" id="${id}" 
                       class="form-control" value="${value}" placeholder="${placeholder}">
                <div class="invalid-feedback" id="${id}Error"></div>
            </div>
        `;
    }

    function handleUnitChange() {
        storeDimensionValues();
        const unit = unitSelect.value;
        const container = document.getElementById('dimension-fields');
        container.innerHTML = '';

        if (!unit) {
            updateDimensionPreview();
            return;
        }

        let html = '';

        if (unitGroups.boxUnits.includes(unit) || unitGroups.weightUnits.includes(unit)) {
            html = `
                <div class="col-12 mb-2"><h6><i class="fas fa-cube"></i> Package Dimensions</h6></div>
                ${createDimensionField('length', 'Length (cm)', dimensionValues.length)}
                ${createDimensionField('width', 'Width (cm)', dimensionValues.width)}
                ${createDimensionField('height', 'Height (cm)', dimensionValues.height)}
            `;
        } else if (unitGroups.cylinderUnits.includes(unit) || unitGroups.volumeUnits.includes(unit)) {
            html = `
                <div class="col-12 mb-2"><h6><i class="fas fa-circle"></i> Cylindrical Dimensions</h6></div>
                ${createDimensionField('length', 'Diameter (cm)', dimensionValues.length)}
                ${createDimensionField('height', 'Height (cm)', dimensionValues.height)}
            `;
        } else if (unitGroups.lengthUnits.includes(unit)) {
            const unitLabel = unit === 'meter' ? 'm' : unit === 'centimeter' ? 'cm' : 'mm';
            html = `
                <div class="col-12 mb-2"><h6><i class="fas fa-ruler"></i> Linear Dimensions</h6></div>
                <div class="col-md-12 mb-3 dimension-field">
                    <label for="length" class="form-label">Length (${unitLabel})</label>
                    <input type="number" step="0.01" min="0" name="dimensions[length]" id="length" 
                           class="form-control" value="${dimensionValues.length}" placeholder="0.00">
                    <small class="text-muted">Standard length per unit</small>
                    <div class="invalid-feedback" id="lengthError"></div>
                </div>
            `;
        } else if (unit === 'pallet') {
            html = `
                <div class="col-12 mb-2"><h6><i class="fas fa-pallet"></i> Pallet Specifications</h6></div>
                <div class="col-md-12 mb-3">
                    <div class="alert alert-warning">
                        <strong>Standard Pallet Dimensions:</strong> 120 × 100 × 15 cm (L × W × H)
                        <br><small>Europallets standard size</small>
                    </div>
                    <input type="hidden" name="dimensions[length]" value="120">
                    <input type="hidden" name="dimensions[width]" value="100">
                    <input type="hidden" name="dimensions[height]" value="15">
                </div>
            `;
        } else if (unit === 'piece') {
            html = `
                <div class="col-12 mb-2"><h6><i class="fas fa-box"></i> Item Dimensions</h6></div>
                ${createDimensionField('length', 'Length (cm)', dimensionValues.length)}
                ${createDimensionField('width', 'Width (cm)', dimensionValues.width)}
                ${createDimensionField('height', 'Height (cm)', dimensionValues.height)}
            `;
        }

        container.innerHTML = html;

        // Add event listeners to new dimension inputs
        container.querySelectorAll('input[type="number"]').forEach(input => {
            input.addEventListener('input', updateDimensionPreview);
        });

        updateDimensionPreview();
    }

   function updateDimensionPreview() {
    const preview = document.getElementById('previewDimensions');
    const unit = unitSelect.value;

    if (!unit) {
        preview.innerHTML = '';
        return;
    }

    const length = parseFloat(document.getElementById('length')?.value || 0);
    const width = parseFloat(document.getElementById('width')?.value || 0);
    const height = parseFloat(document.getElementById('height')?.value || 0);

    let volumeCm3 = 0;
    let volumeL = 0;
    let html = '';
    let type = '';

    if (unit === 'pallet') {
        html = '120 × 100 × 15 cm (L × W × H)';
        type = 'Standard Europallet';
        volumeCm3 = 120 * 100 * 15;
    } else if (unitGroups.cylinderUnits.includes(unit) || unitGroups.volumeUnits.includes(unit)) {
        html = `Ø${length || 0} × ${height || 0} cm (Diameter × Height)`;
        type = 'Cylindrical Container';
        if (length && height) {
            volumeCm3 = Math.PI * Math.pow(length / 2, 2) * height;
        }
    } else if (unitGroups.lengthUnits.includes(unit)) {
        const unitLabel = unit === 'meter' ? 'm' : unit === 'centimeter' ? 'cm' : 'mm';
        html = `${length || 0} ${unitLabel}`;
        type = 'Linear Material';
    } else {
        html = `${length || 0} × ${width || 0} × ${height || 0} cm (L × W × H)`;
        type = unitGroups.weightUnits.includes(unit) ? 'Package Dimensions' :
               unit === 'piece' ? 'Item Dimensions' : 'Physical Dimensions';
        if (length && width && height) {
            volumeCm3 = length * width * height;
        }
    }

    volumeL = volumeCm3 / 1000;

    preview.innerHTML = `
        <div class="col-12">
            <h6><i class="fas fa-ruler-combined"></i> Dimension Summary</h6>
            <div class="alert alert-light border">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <strong>Dimensions:</strong> ${html}
                        ${volumeCm3 > 0 ? `<br><small class="text-muted">Volume: ${volumeCm3.toFixed(0)} cm³ </small>` : ''}
                    </div>
                    <span class="badge bg-secondary">${type}</span>
                </div>
            </div>
        </div>
    `;
}


    // Helper functions
    function generateCode(name) {
        if (!name) return '';
        const prefix = name.trim().substring(0, 3).toUpperCase();
        const randomNum = Math.floor(1000 + Math.random() * 9000);
        return prefix + randomNum;
    }

    function generateSKU(category, name) {
        if (!name) return '';
        
        const categoryCode = (category || 'GEN').trim().substring(0, 3).toUpperCase().padEnd(3, 'X');
        const nameCode = name.trim().substring(0, 3).toUpperCase().padEnd(3, 'X');
        const randomNum = Math.floor(100 + Math.random() * 900);
        
        return categoryCode + nameCode + randomNum.toString().padStart(3, '0');
    }

    function generateBarcode() {
        // Generate 12-digit number for EAN-13 (without check digit)
        const prefix = '2'; // Internal use prefix
        const randomPart = Math.floor(Math.random() * 99999999999).toString().padStart(11, '0');
        const barcode12 = prefix + randomPart;
        
        // Calculate EAN-13 check digit
        let sum = 0;
        for (let i = 0; i < 12; i++) {
            const digit = parseInt(barcode12[i]);
            sum += (i % 2 === 0) ? digit : digit * 3;
        }
        const checkDigit = (10 - (sum % 10)) % 10;
        
        return barcode12 + checkDigit;
    }

    // DOM elements
    const form = document.getElementById('materialForm');
    const nameInput = document.getElementById('name');
    const codeInput = document.getElementById('code');
    const skuInput = document.getElementById('sku');
    const barcodeInput = document.getElementById('barcode');
    const categoryInput = document.getElementById('category');
    const previewSection = document.getElementById('previewSection');

    // Initialize codes if empty (for create mode)
    if (nameInput && codeInput && !codeInput.value && nameInput.value) {
        codeInput.value = generateCode(nameInput.value);
    }

    // Event listeners
    if (nameInput) {
        nameInput.addEventListener('input', function () {
            if (!codeInput.value || codeInput.value.startsWith(nameInput.value.substring(0, 3).toUpperCase())) {
                codeInput.value = generateCode(nameInput.value);
            }
            updatePreview();
        });
    }

    if (categoryInput) {
        categoryInput.addEventListener('input', updatePreview);
    }

    document.getElementById('regenerateCode')?.addEventListener('click', function () {
        codeInput.value = generateCode(nameInput.value);
        updatePreview();
    });

    document.getElementById('previewSku')?.addEventListener('click', function () {
        const sku = generateSKU(categoryInput.value, nameInput.value);
        skuInput.value = sku;
        updatePreview();
    });

    document.getElementById('previewBarcode')?.addEventListener('click', function () {
        const barcode = generateBarcode();
        barcodeInput.value = barcode;
        updatePreview();
    });

    document.getElementById('togglePreview')?.addEventListener('click', function () {
        if (previewSection.style.display === 'none') {
            previewSection.style.display = 'block';
            updatePreview();
            this.innerHTML = '<i class="fas fa-eye-slash"></i> Hide Preview';
        } else {
            previewSection.style.display = 'none';
            this.innerHTML = '<i class="fas fa-eye"></i> Show Preview';
        }
    });

    // Update preview function
    function updatePreview() {
        if (previewSection.style.display === 'none') return;

        document.getElementById('previewName').textContent = nameInput?.value || '-';
        document.getElementById('previewCode').textContent = codeInput?.value || '-';
        document.getElementById('previewCategory').textContent = categoryInput?.value || '-';
        document.getElementById('previewSkuValue').textContent = skuInput?.value || '-';
        document.getElementById('previewUnit').textContent = unitSelect?.selectedOptions[0]?.text || '-';
        document.getElementById('previewPrice').textContent = document.getElementById('unit_price')?.value || '0.00';
        document.getElementById('previewBarcodeValue').textContent = barcodeInput?.value || '-';
        
        // Display barcode visually
        const barcodeDisplay = document.getElementById('barcodeDisplay');
        if (barcodeDisplay) {
            if (barcodeInput?.value) {
                barcodeDisplay.textContent = '*' + barcodeInput.value + '*';
            } else {
                barcodeDisplay.textContent = '';
            }
        }
    }

    // Form validation
    const validations = {
        name: {
            required: true,
            maxLength: 100,
        },
        code: {
            required: true,
            maxLength: 50,
            pattern: /^[A-Za-z0-9]+$/,
        },
        unit_price: {
            required: true,
            min: 0,
        },
        gst_rate: {
            required: true,
            min: 0,
            max: 100,
        }
    };

    function validateField(fieldName, value) {
        const rules = validations[fieldName];
        if (!rules) return '';

        let error = '';

        if (rules.required && !value.toString().trim()) {
            error = 'This field is required.';
        } else if (rules.maxLength && value.length > rules.maxLength) {
            error = `Maximum length is ${rules.maxLength} characters.`;
        } else if (rules.pattern && !rules.pattern.test(value)) {
            error = 'Invalid characters entered.';
        } else if (rules.min !== undefined && parseFloat(value) < rules.min) {
            error = `Minimum value is ${rules.min}.`;
        } else if (rules.max !== undefined && parseFloat(value) > rules.max) {
            error = `Maximum value is ${rules.max}.`;
        }

        return error;
    }

    function handleInput(event) {
        const input = event.target;
        const fieldName = input.name;
        const value = input.value;
        const error = validateField(fieldName, value);
        const errorDiv = document.getElementById(fieldName + 'Error');

        if (error) {
            input.classList.add('is-invalid');
            if (errorDiv) errorDiv.textContent = error;
        } else {
            input.classList.remove('is-invalid');
            if (errorDiv) errorDiv.textContent = '';
        }
    }

    // Add event listeners for validation
    Object.keys(validations).forEach(fieldName => {
        const input = form?.querySelector(`[name="${fieldName}"]`);
        if (input) {
            input.addEventListener('input', handleInput);
            input.addEventListener('blur', handleInput);
        }
    });

    // Form submission
    form?.addEventListener('submit', function (e) {
        // Auto-generate SKU and barcode if not set
        if (!skuInput.value) {
            skuInput.value = generateSKU(categoryInput.value, nameInput.value);
        }
        if (!barcodeInput.value) {
            barcodeInput.value = generateBarcode();
        }

        // Validate all fields
        let hasError = false;
        Object.keys(validations).forEach(fieldName => {
            const input = form.querySelector(`[name="${fieldName}"]`);
            if (input) {
                const error = validateField(fieldName, input.value);
                const errorDiv = document.getElementById(fieldName + 'Error');
                if (error) {
                    hasError = true;
                    input.classList.add('is-invalid');
                    if (errorDiv) errorDiv.textContent = error;
                } else {
                    input.classList.remove('is-invalid');
                    if (errorDiv) errorDiv.textContent = '';
                }
            }
        });

        if (hasError) {
            e.preventDefault();
            e.stopPropagation();
            alert('Please fix the validation errors before submitting.');
        }
    });

    // Auto-update preview when other fields change
    ['unit', 'unit_price'].forEach(fieldName => {
        const field = document.getElementById(fieldName);
        if (field) {
            field.addEventListener('change', updatePreview);
        }
    });

    // Initialize unit change handler and trigger it for edit mode
    if (unitSelect) {
        unitSelect.addEventListener('change', handleUnitChange);
        // Trigger initial load for edit mode
        handleUnitChange();
    }
});
</script>

<style>
@import url('https://fonts.googleapis.com/css2?family=Libre+Barcode+128&display=swap');

.input-group .btn {
    border-left: 0;
}

.card {
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
}

#barcodeDisplay {
    background: white;
    border: 1px solid #ddd;
    border-radius: 4px;
    padding: 5px;
    min-height: 50px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.form-control:read-only {
    background-color: #f8f9fa;
}

.btn-outline-secondary:hover {
    color: #fff;
    background-color: #6c757d;
    border-color: #6c757d;
}

.dimension-field {
    transition: all 0.3s ease;
}

.alert-light {
    background-color: #f8f9fa;
    border-color: #dee2e6;
}
</style>
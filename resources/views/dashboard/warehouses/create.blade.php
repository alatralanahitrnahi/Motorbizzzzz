@extends('layouts.app')

@section('title', 'Add New Warehouse')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Add New Warehouse</h1>
        <a href="{{ route('dashboard.warehouses.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to List
        </a>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Warehouse Information</h6>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('dashboard.warehouses.store') }}">
                        @csrf
                        
                        <!-- Basic Information -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">Warehouse Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name') }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
    <div class="form-group">
        <label for="type">Warehouse Type <span class="text-danger">*</span></label>
        <select class="form-control @error('type') is-invalid @enderror" 
                id="type" name="type" required>
            <option value="">Select Type</option>
            @foreach($types as $key => $label)
                <option value="{{ $key }}" {{ old('type') == $key ? 'selected' : '' }}>
                    {{ $label }}
                </option>
            @endforeach
        </select>
        @error('type')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>        
                        
</div>
                   <div class="row">
    <div class="col-md-6">
        <!-- Capacity -->
        <div class="form-group">
            <label for="capacity">Capacity <span class="text-danger">*</span></label>
            <input type="number" min="0" class="form-control @error('capacity') is-invalid @enderror" 
                   id="capacity" name="capacity" value="{{ old('capacity') }}" required>
            @error('capacity')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
       </div>

        <!-- Address -->
        <div class="form-group">
            <label for="address">Address <span class="text-danger">*</span></label>
            <textarea class="form-control @error('address') is-invalid @enderror" 
                      id="address" name="address" rows="2" required>{{ old('address') }}</textarea>
            @error('address')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>


   <div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label for="state">State <span class="text-danger">*</span></label>
       <!-- To this: -->
<select class="form-control @error('state') is-invalid @enderror"
        id="state" name="state" required>
    <option value="">Select State</option>
    @foreach($states as $code => $label)
        <option value="{{ $label }}" {{ old('state') == $label ? 'selected' : '' }}>
            {{ $label }}
        </option>
    @endforeach
</select>

            @error('state')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="city">City <span class="text-danger">*</span></label>
            <select class="form-control @error('city') is-invalid @enderror" 
                    id="city" name="city" required>
                <option value="">Select City</option>
            </select>
            @error('city')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
</div>
                        <!-- Contact Information -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="contact_phone">Contact Phone</label>
                                    <input type="text" class="form-control @error('contact_phone') is-invalid @enderror" 
                                           id="contact_phone" name="contact_phone" value="{{ old('contact_phone') }}">
                                    @error('contact_phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="contact_email">Contact Email</label>
                                    <input type="email" class="form-control @error('contact_email') is-invalid @enderror" 
                                           id="contact_email" name="contact_email" value="{{ old('contact_email') }}">
                                    @error('contact_email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Status and Options -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="is_active">Status <span class="text-danger">*</span></label>
<select name="is_active" id="is_active" class="form-control" required>
    <option value="1" {{ old('is_active') == '1' ? 'selected' : '' }}>Active</option>
    <option value="0" {{ old('is_active') == '0' ? 'selected' : '' }}>Inactive</option>
</select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="custom-control custom-checkbox mt-4">
                                        <input type="checkbox" class="custom-control-input" 
                                               id="is_default" name="is_default" value="1" 
                                               {{ old('is_default') ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="is_default">
                                            Set as Default Warehouse
                                        </label>
                                    </div>
                                    <small class="form-text text-muted">
                                        Only one warehouse can be set as default at a time.
                                    </small>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Create Warehouse
                            </button>
                            <a href="{{ route('dashboard.warehouses.index') }}" class="btn btn-secondary">
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Instructions</h6>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled">
                        <li><i class="fas fa-check text-success"></i> Fill in all required fields marked with <span class="text-danger">*</span></li>
                        <li><i class="fas fa-check text-success"></i> Choose appropriate warehouse type</li>
                        <li><i class="fas fa-check text-success"></i> Provide complete address information</li>
                        <li><i class="fas fa-check text-success"></i> Contact information is optional but recommended</li>
                        <li><i class="fas fa-check text-success"></i> Only one warehouse can be set as default</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
  
const stateCityData = {
  'Andhra Pradesh': [
    'Visakhapatnam', 'Vijayawada', 'Guntur', 'Nellore', 'Kurnool',
    'Rajahmundry', 'Tirupati', 'Kadapa', 'Kakinada', 'Anantapur',
    'Eluru', 'Machilipatnam', 'Chittoor', 'Tenali', 'Ongole'
  ],
  'Arunachal Pradesh': [
    'Itanagar', 'Naharlagun', 'Pasighat', 'Tezpur', 'Bomdila',
    'Ziro', 'Along', 'Tezu', 'Changlang', 'Roing'
  ],
  'Assam': [
    'Guwahati', 'Silchar', 'Dibrugarh', 'Jorhat', 'Nagaon',
    'Tinsukia', 'Tezpur', 'Bongaigaon', 'Dhubri', 'North Lakhimpur',
    'Karimganj', 'Sivasagar'
  ],
  'Bihar': [
    'Patna', 'Gaya', 'Bhagalpur', 'Muzaffarpur', 'Purnia',
    'Darbhanga', 'Bihar Sharif', 'Arrah', 'Begusarai', 'Katihar',
    'Chapra', 'Sasaram'
  ],
  'Chhattisgarh': [
    'Raipur', 'Bhilai', 'Bilaspur', 'Korba', 'Durg',
    'Rajnandgaon', 'Jagdalpur', 'Raigarh', 'Ambikapur', 'Dhamtari'
  ],
  'Goa': [
    'Panaji', 'Vasco da Gama', 'Margao', 'Mapusa', 'Ponda',
    'Bicholim', 'Curchorem', 'Sanquelim', 'Canacona', 'Valpoi'
  ],
  'Gujarat': [
    'Ahmedabad', 'Surat', 'Vadodara', 'Rajkot', 'Bhavnagar',
    'Jamnagar', 'Junagadh', 'Gandhinagar', 'Anand', 'Nadiad',
    'Morbi', 'Mehsana'
  ],
  'Haryana': [
    'Faridabad', 'Gurgaon', 'Panipat', 'Ambala', 'Yamunanagar',
    'Rohtak', 'Hisar', 'Karnal', 'Sonipat', 'Panchkula',
    'Sirsa', 'Rewari'
  ],
  'Himachal Pradesh': [
    'Shimla', 'Dharamshala', 'Solan', 'Mandi', 'Palampur',
    'Baddi', 'Nahan', 'Paonta Sahib', 'Sundernagar', 'Chamba',
    'Bilaspur'
  ],
  'Jharkhand': [
    'Ranchi', 'Jamshedpur', 'Dhanbad', 'Bokaro Steel City', 'Deoghar',
    'Phusro', 'Hazaribagh', 'Giridih', 'Ramgarh', 'Medininagar',
    'Chaibasa'
  ],
  'Karnataka': [
    'Bangalore', 'Mysore', 'Hubli', 'Mangalore', 'Belgaum',
    'Gulbarga', 'Davanagere', 'Bellary', 'Bijapur', 'Shimoga',
    'Tumkur', 'Raichur'
  ],
  'Kerala': [
    'Thiruvananthapuram', 'Kochi', 'Kozhikode', 'Thrissur', 'Kollam',
    'Palakkad', 'Alappuzha', 'Malappuram', 'Kannur', 'Kasaragod',
    'Kottayam', 'Pathanamthitta'
  ],
  'Madhya Pradesh': [
    'Indore', 'Bhopal', 'Jabalpur', 'Gwalior', 'Ujjain',
    'Sagar', 'Dewas', 'Satna', 'Ratlam', 'Rewa',
    'Chhindwara', 'Khargone'
  ],
  'Maharashtra': [
    'Mumbai', 'Pune', 'Nagpur', 'Thane', 'Nashik',
    'Aurangabad', 'Solapur', 'Amravati', 'Kolhapur', 'Sangli',
    'Nanded', 'Jalgaon', 'Akola', 'Latur', 'Dhule',
    'Chandrapur', 'Parbhani', 'Ahmednagar', 'Beed', 'Wardha',
    'Satara', 'Yavatmal', 'Bhiwandi', 'Ulhasnagar', 'Kalyan',
    'Panvel', 'Vasai-Virar', 'Malegaon', 'Ichalkaranji', 'Jalna',
    'Osmanabad', 'Nandurbar', 'Ratnagiri', 'Gondia', 'Hingoli',
    'Washim', 'Buldhana', 'Sindhudurg', 'Palghar', 'Raigad',
    'Baramati', 'Karad', 'Alibag', 'Navi Mumbai',
    'Dombivli', 'Ambarnath', 'Boisar', 'Mira-Bhayandar'
  ],
  'Manipur': [
    'Imphal', 'Bishnupur', 'Thoubal', 'Churachandpur', 'Ukhrul',
    'Senapati', 'Tamenglong', 'Chandel', 'Moreh'
  ],
  'Meghalaya': [
    'Shillong', 'Tura', 'Cherrapunji', 'Jowai', 'Baghmara',
    'Nongpoh', 'Mawkyrwat', 'Resubelpara', 'Williamnagar'
  ],
  'Mizoram': [
    'Aizawl', 'Lunglei', 'Saiha', 'Champhai', 'Kolasib',
    'Serchhip', 'Mamit', 'Lawngtlai', 'Saitual'
  ],
  'Nagaland': [
    'Kohima', 'Dimapur', 'Mokokchung', 'Tuensang', 'Wokha',
    'Zunheboto', 'Phek', 'Kiphire', 'Longleng', 'Peren', 'Mon',
    'Meluri'
  ],
  'Odisha': [
    'Bhubaneswar', 'Cuttack', 'Rourkela', 'Brahmapur', 'Sambalpur',
    'Puri', 'Balasore', 'Bhadrak', 'Baripada', 'Jharsuguda',
    'Angul'
  ],
  'Punjab': [
    'Ludhiana', 'Amritsar', 'Jalandhar', 'Patiala', 'Bathinda',
    'Mohali', 'Firozpur', 'Batala', 'Pathankot', 'Moga',
    'Hoshiarpur'
  ],
  'Rajasthan': [
    'Jaipur', 'Jodhpur', 'Kota', 'Bikaner', 'Ajmer',
    'Udaipur', 'Bhilwara', 'Alwar', 'Bharatpur', 'Sikar',
    'Tonk'
  ],
  'Sikkim': [
    'Gangtok', 'Namchi', 'Geyzing', 'Mangan', 'Jorethang',
    'Nayabazar', 'Singtam', 'Rangpo', 'Soreng'
  ],
  'Tamil Nadu': [
    'Chennai', 'Coimbatore', 'Madurai', 'Tiruchirappalli', 'Salem',
    'Tirunelveli', 'Tiruppur', 'Vellore', 'Erode', 'Thoothukkudi',
    'Dindigul', 'Nagercoil'
  ],
  'Telangana': [
    'Hyderabad', 'Warangal', 'Nizamabad', 'Khammam', 'Karimnagar',
    'Ramagundam', 'Mahbubnagar', 'Nalgonda', 'Adilabad', 'Suryapet',
    'Miryalaguda'
  ],
  'Tripura': [
    'Agartala', 'Dharmanagar', 'Udaipur', 'Kailashahar', 'Belonia',
    'Khowai', 'Amarpur', 'Teliamura', 'Sonamura'
  ],
  'Uttar Pradesh': [
    'Lucknow', 'Kanpur', 'Ghaziabad', 'Agra', 'Varanasi',
    'Meerut', 'Allahabad', 'Bareilly', 'Aligarh', 'Moradabad',
    'Noida', 'Gorakhpur'
  ],
  'Uttarakhand': [
    'Dehradun', 'Haridwar', 'Roorkee', 'Haldwani', 'Rudrapur',
    'Kashipur', 'Rishikesh', 'Pithoragarh', 'Jaspur', 'Kichha',
    'Tehri'
  ],
  'West Bengal': [
    'Kolkata', 'Howrah', 'Durgapur', 'Asansol', 'Siliguri',
    'Malda', 'Bardhaman', 'Kharagpur', 'Haldia', 'Raiganj',
    'Berhampore'
  ],
  // Union Territories
  'Andaman and Nicobar Islands': [
    'Port Blair', 'Diglipur', 'Mayabunder', 'Rangat', 'Car Nicobar',
    'Hut Bay'
  ],
  'Chandigarh': ['Chandigarh'],
  'Dadra and Nagar Haveli and Daman and Diu': [
    'Daman', 'Diu', 'Silvassa', 'Amli'
  ],
  'Delhi': [
    'New Delhi', 'North Delhi', 'South Delhi', 'East Delhi', 'West Delhi',
    'Central Delhi', 'North East Delhi', 'North West Delhi', 'South East Delhi', 
    'South West Delhi', 'Shahdara'
  ],
  'Jammu and Kashmir': [
    'Srinagar', 'Jammu', 'Anantnag', 'Baramulla', 'Sopore',
    'Kathua', 'Udhampur', 'Punch', 'Rajouri', 'Kupwara'
  ],
  'Ladakh': [
    'Leh', 'Kargil', 'Nubra', 'Zanskar', 'Drass', 'Diskit'
  ],
  'Lakshadweep': [
    'Kavaratti', 'Agatti', 'Minicoy', 'Amini', 'Andrott', 'Kalpeni'
  ],
  'Puducherry': [
    'Puducherry', 'Karaikal', 'Mahe', 'Yanam', 'Ozhukarai'
  ]
};

/**
 * Load cities based on selected state code
 * @param {string} stateCode - The selected state code (e.g., 'MH', 'GJ')
 */
function loadCities(stateCode) {
    console.log('Loading cities for state code:', stateCode);
    
    const citySelect = document.getElementById('city');
    const oldCity = @json(old('city', ''));
    
    // Clear existing options
    citySelect.innerHTML = '<option value="">Select City</option>';
    
    if (!stateCode) {
        citySelect.disabled = true;
        citySelect.classList.add('text-muted');
        return;
    }
    
    // Check if state code exists in our data
    if (stateCityData[stateCode] && Array.isArray(stateCityData[stateCode])) {
        console.log('Found', stateCityData[stateCode].length, 'cities for', stateCode);
        
        // Enable city select
        citySelect.disabled = false;
        citySelect.classList.remove('text-muted');
        
        // Sort cities alphabetically
        const sortedCities = stateCityData[stateCode].sort();
        
        // Add each city as an option
        sortedCities.forEach(city => {
            const option = document.createElement('option');
            option.value = city;
            option.textContent = city;
            
            // Preserve old selection on validation errors
            if (oldCity === city) {
                option.selected = true;
            }
            
            citySelect.appendChild(option);
        });
        
        console.log('Successfully loaded cities');
    } else {
        console.log('No cities found for state code:', stateCode);
        citySelect.innerHTML = '<option value="">No cities available for this state</option>';
        citySelect.disabled = true;
        citySelect.classList.add('text-muted');
    }
    
    // Clear validation errors when changing state
    citySelect.classList.remove('is-invalid');
    const feedback = citySelect.parentNode.querySelector('.invalid-feedback');
    if (feedback) {
        feedback.style.display = 'none';
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    const stateSelect = document.getElementById('state');
    const citySelect = document.getElementById('city');
    
    console.log('Initializing state-city functionality');
    
    // Initially disable city select
    citySelect.disabled = true;
    citySelect.classList.add('text-muted');
    
    // Load cities if state is already selected (for validation errors)
    if (stateSelect && stateSelect.value) {
        console.log('State already selected on load:', stateSelect.value);
        loadCities(stateSelect.value);
    }
    
    // Add change event listener
    if (stateSelect) {
        stateSelect.addEventListener('change', function() {
            console.log('State changed to:', this.value);
            loadCities(this.value);
        });
    }
    
    // Debug: Show available state codes
    console.log('Available state codes:', Object.keys(stateCityData));
});
  
</script>
@endsection
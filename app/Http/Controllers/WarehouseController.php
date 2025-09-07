<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Warehouse;
use App\Models\WarehouseBlock;
use App\Models\WarehouseSlot;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Pagination\LengthAwarePaginator;


class WarehouseController extends Controller
{
public function __construct()
{
    $this->middleware('auth');
    
    // Apply specific middleware to specific methods
    $this->middleware('can:viewAny,App\Models\Warehouse')->only(['index']);
    $this->middleware('can:create,App\Models\Warehouse')->only(['create', 'store']);
    $this->middleware('can:update,warehouse')->only(['edit', 'update']);
    $this->middleware('can:delete,warehouse')->only(['destroy']);
}

public function index(Request $request)
{
    $query = Warehouse::with('blocks'); // ← Start query here

    if ($request->filled('search')) {
        $search = $request->search;
        $query->where(function ($q) use ($search) {
            $q->where('name', 'LIKE', "%{$search}%")
              ->orWhere('address', 'LIKE', "%{$search}%")
              ->orWhere('city', 'LIKE', "%{$search}%")
              ->orWhere('state', 'LIKE', "%{$search}%");
        });
    }

    if ($request->filled('type')) {
        $query->where('type', $request->type);
    }

    if ($request->filled('status')) {
        $query->where('is_active', $request->status === 'active');
    }

    if ($request->filled('city')) {
        $query->where('city', 'LIKE', "%{$request->city}%");
    }

    // Apply pagination
    $warehouses = $query->latest()->paginate(15)->appends($request->query());

    // Other data
    $stats = [
        'total' => Warehouse::count(),
        'active' => Warehouse::where('is_active', true)->count(),
        'inactive' => Warehouse::where('is_active', false)->count(),
        'default' => Warehouse::where('is_default', true)->count(),
        'types' => Warehouse::groupBy('type')
                    ->selectRaw('type, count(*) as count')
                    ->pluck('count', 'type')
    ];

    $types = Warehouse::getTypes();
    $cities = Warehouse::select('city')->distinct()->orderBy('city')->pluck('city');

    return view('dashboard.warehouses.index', compact(
        'warehouses', 'stats', 'types', 'cities'
    ));
}


    /**
     * Show create warehouse form
     */
    public function create()
    {
        $types = Warehouse::getTypes();
        $states = $this->getStates(); // You can implement this method
        
        return view('dashboard.warehouses.create', compact('types', 'states'));
    }

    /**
     * Store new warehouse
     */
    public function store(Request $request)
{
    $validated = $this->validateWarehouse($request);

    try {
        DB::beginTransaction();

        // If this warehouse is set as default, remove default from others
        if (!empty($validated['is_default'])) {
            Warehouse::where('is_default', true)->update(['is_default' => false]);
        }

       $warehouse = Warehouse::create([
    'name' => $validated['name'],
    'address' => $validated['address'],
    'city' => $validated['city'],
    'state' => $validated['state'],
    'contact_phone' => $validated['contact_phone'],
    'contact_email' => $validated['contact_email'],
    'type' => $validated['type'],
    'is_default' => $validated['is_default'] ?? false,
    'is_active' => $validated['is_active'],
    'capacity' => $validated['capacity'], // <-- Added line
]);

        DB::commit();

        return $this->handleSuccess('Warehouse created successfully.', $request);

    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Warehouse creation failed: ' . $e->getMessage());
        return $this->handleError('Failed to create warehouse.', $request);
    }
}

    /**
     * Show warehouse details
     */
    public function show(Warehouse $warehouse)
    {
        $warehouse->load(['users' => function($query) {
            $query->select('users.*', 'warehouse_users.role');
        }]);

        return view('dashboard.warehouses.show', compact('warehouse'));
    }

    /**
     * Show edit form
     */
    public function edit(Warehouse $warehouse)
    {
        $types = Warehouse::getTypes();
        $states = $this->getStates();
        
      $stateCityData = [
  'Andhra Pradesh'=> [
    'Visakhapatnam', 'Vijayawada', 'Guntur', 'Nellore', 'Kurnool',
    'Rajahmundry', 'Tirupati', 'Kadapa', 'Kakinada', 'Anantapur',
    'Eluru', 'Machilipatnam', 'Chittoor', 'Tenali', 'Ongole'
  ],
  'Arunachal Pradesh'=> [
    'Itanagar', 'Naharlagun', 'Pasighat', 'Tezpur', 'Bomdila',
    'Ziro', 'Along', 'Tezu', 'Changlang', 'Roing'
  ],
  'Assam'=> [
    'Guwahati', 'Silchar', 'Dibrugarh', 'Jorhat', 'Nagaon',
    'Tinsukia', 'Tezpur', 'Bongaigaon', 'Dhubri', 'North Lakhimpur',
    'Karimganj', 'Sivasagar'
  ],
  'Bihar'=> [
    'Patna', 'Gaya', 'Bhagalpur', 'Muzaffarpur', 'Purnia',
    'Darbhanga', 'Bihar Sharif', 'Arrah', 'Begusarai', 'Katihar',
    'Chapra', 'Sasaram'
  ],
  'Chhattisgarh'=> [
    'Raipur', 'Bhilai', 'Bilaspur', 'Korba', 'Durg',
    'Rajnandgaon', 'Jagdalpur', 'Raigarh', 'Ambikapur', 'Dhamtari'
  ],
  'Goa'=> [
    'Panaji', 'Vasco da Gama', 'Margao', 'Mapusa', 'Ponda',
    'Bicholim', 'Curchorem', 'Sanquelim', 'Canacona', 'Valpoi'
  ],
  'Gujarat'=> [
    'Ahmedabad', 'Surat', 'Vadodara', 'Rajkot', 'Bhavnagar',
    'Jamnagar', 'Junagadh', 'Gandhinagar', 'Anand', 'Nadiad',
    'Morbi', 'Mehsana'
  ],
  'Haryana'=> [
    'Faridabad', 'Gurgaon', 'Panipat', 'Ambala', 'Yamunanagar',
    'Rohtak', 'Hisar', 'Karnal', 'Sonipat', 'Panchkula',
    'Sirsa', 'Rewari'
  ],
  'Himachal Pradesh'=> [
    'Shimla', 'Dharamshala', 'Solan', 'Mandi', 'Palampur',
    'Baddi', 'Nahan', 'Paonta Sahib', 'Sundernagar', 'Chamba',
    'Bilaspur'
  ],
  'Jharkhand'=> [
    'Ranchi', 'Jamshedpur', 'Dhanbad', 'Bokaro Steel City', 'Deoghar',
    'Phusro', 'Hazaribagh', 'Giridih', 'Ramgarh', 'Medininagar',
    'Chaibasa'
  ],
  'Karnataka'=> [
    'Bangalore', 'Mysore', 'Hubli', 'Mangalore', 'Belgaum',
    'Gulbarga', 'Davanagere', 'Bellary', 'Bijapur', 'Shimoga',
    'Tumkur', 'Raichur'
  ],
  'Kerala'=> [
    'Thiruvananthapuram', 'Kochi', 'Kozhikode', 'Thrissur', 'Kollam',
    'Palakkad', 'Alappuzha', 'Malappuram', 'Kannur', 'Kasaragod',
    'Kottayam', 'Pathanamthitta'
  ],
  'Madhya Pradesh'=> [
    'Indore', 'Bhopal', 'Jabalpur', 'Gwalior', 'Ujjain',
    'Sagar', 'Dewas', 'Satna', 'Ratlam', 'Rewa',
    'Chhindwara', 'Khargone'
  ],
  'Maharashtra'=> [
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
  'Manipur'=> [
    'Imphal', 'Bishnupur', 'Thoubal', 'Churachandpur', 'Ukhrul',
    'Senapati', 'Tamenglong', 'Chandel', 'Moreh'
  ],
  'Meghalaya'=> [
    'Shillong', 'Tura', 'Cherrapunji', 'Jowai', 'Baghmara',
    'Nongpoh', 'Mawkyrwat', 'Resubelpara', 'Williamnagar'
  ],
  'Mizoram'=> [
    'Aizawl', 'Lunglei', 'Saiha', 'Champhai', 'Kolasib',
    'Serchhip', 'Mamit', 'Lawngtlai', 'Saitual'
  ],
  'Nagaland'=> [
    'Kohima', 'Dimapur', 'Mokokchung', 'Tuensang', 'Wokha',
    'Zunheboto', 'Phek', 'Kiphire', 'Longleng', 'Peren', 'Mon',
    'Meluri'
  ],
  'Odisha'=> [
    'Bhubaneswar', 'Cuttack', 'Rourkela', 'Brahmapur', 'Sambalpur',
    'Puri', 'Balasore', 'Bhadrak', 'Baripada', 'Jharsuguda',
    'Angul'
  ],
  'Punjab'=> [
    'Ludhiana', 'Amritsar', 'Jalandhar', 'Patiala', 'Bathinda',
    'Mohali', 'Firozpur', 'Batala', 'Pathankot', 'Moga',
    'Hoshiarpur'
  ],
  'Rajasthan'=> [
    'Jaipur', 'Jodhpur', 'Kota', 'Bikaner', 'Ajmer',
    'Udaipur', 'Bhilwara', 'Alwar', 'Bharatpur', 'Sikar',
    'Tonk'
  ],
  'Sikkim'=> [
    'Gangtok', 'Namchi', 'Geyzing', 'Mangan', 'Jorethang',
    'Nayabazar', 'Singtam', 'Rangpo', 'Soreng'
  ],
  'Tamil Nadu'=> [
    'Chennai', 'Coimbatore', 'Madurai', 'Tiruchirappalli', 'Salem',
    'Tirunelveli', 'Tiruppur', 'Vellore', 'Erode', 'Thoothukkudi',
    'Dindigul', 'Nagercoil'
  ],
  'Telangana'=> [
    'Hyderabad', 'Warangal', 'Nizamabad', 'Khammam', 'Karimnagar',
    'Ramagundam', 'Mahbubnagar', 'Nalgonda', 'Adilabad', 'Suryapet',
    'Miryalaguda'
  ],
  'Tripura'=> [
    'Agartala', 'Dharmanagar', 'Udaipur', 'Kailashahar', 'Belonia',
    'Khowai', 'Amarpur', 'Teliamura', 'Sonamura'
  ],
  'Uttar Pradesh'=> [
    'Lucknow', 'Kanpur', 'Ghaziabad', 'Agra', 'Varanasi',
    'Meerut', 'Allahabad', 'Bareilly', 'Aligarh', 'Moradabad',
    'Noida', 'Gorakhpur'
  ],
  'Uttarakhand'=> [
    'Dehradun', 'Haridwar', 'Roorkee', 'Haldwani', 'Rudrapur',
    'Kashipur', 'Rishikesh', 'Pithoragarh', 'Jaspur', 'Kichha',
    'Tehri'
  ],
  'West Bengal'=> [
    'Kolkata', 'Howrah', 'Durgapur', 'Asansol', 'Siliguri',
    'Malda', 'Bardhaman', 'Kharagpur', 'Haldia', 'Raiganj',
    'Berhampore'
  ],
  // Union Territories
  'Andaman and Nicobar Islands'=> [
    'Port Blair', 'Diglipur', 'Mayabunder', 'Rangat', 'Car Nicobar',
    'Hut Bay'
  ],
  'Chandigarh'=> ['Chandigarh'],
  'Dadra and Nagar Haveli and Daman and Diu'=> [
    'Daman', 'Diu', 'Silvassa', 'Amli'
  ],
  'Delhi'=> [
    'New Delhi', 'North Delhi', 'South Delhi', 'East Delhi', 'West Delhi',
    'Central Delhi', 'North East Delhi', 'North West Delhi', 'South East Delhi', 
    'South West Delhi', 'Shahdara'
  ],
  'Jammu and Kashmir'=> [
    'Srinagar', 'Jammu', 'Anantnag', 'Baramulla', 'Sopore',
    'Kathua', 'Udhampur', 'Punch', 'Rajouri', 'Kupwara'
  ],
  'Ladakh'=> [
    'Leh', 'Kargil', 'Nubra', 'Zanskar', 'Drass', 'Diskit'
  ],
  'Lakshadweep'=> [
    'Kavaratti', 'Agatti', 'Minicoy', 'Amini', 'Andrott', 'Kalpeni'
  ],
  'Puducherry'=> [
    'Puducherry', 'Karaikal', 'Mahe', 'Yanam', 'Ozhukarai'
  ]
];
        return view('dashboard.warehouses.edit', compact('warehouse', 'types', 'states','stateCityData'));
    }

  public function update(Request $request, Warehouse $warehouse)
{
    // Validate input — note 'status' is string with 'active' or 'inactive'
    $validated = $request->validate([
        'name' => [
            'required',
            'string',
            'max:255',
            Rule::unique('warehouses')->ignore($warehouse->id),
        ],
        'address' => 'required|string|max:500',
        'city' => 'required|string|max:100',
        'state' => 'required|string|max:100',
      'contact_phone' => [
    'nullable',
    'regex:/^[6-9][0-9]{9}$/', // starts with 6-9, followed by 9 digits
],
        'contact_email' => 'nullable|email|max:255',
        'type' => 'required|in:main,cold_storage,transit,distribution,temporary',
        'status' => 'required|in:active,inactive',
        'is_default' => 'sometimes|boolean',
'capacity' => 'required|numeric|min:0',
    ], [
        'name.regex' => 'The name must contain only letters and spaces.',
'contact_phone.regex' => 'Phone number must be 10 digits and start with 6, 7, 8, or 9.',
        'contact_email.email' => 'Please enter a valid email address.',
    ]);

    try {
        DB::beginTransaction();

        // Handle default warehouse logic
        if (($validated['is_default'] ?? false) && !$warehouse->is_default) {
            Warehouse::where('is_default', true)->update(['is_default' => false]);
        }

        $warehouse->update([
            'name' => $validated['name'],
            'address' => $validated['address'],
            'city' => $validated['city'],
            'state' => $validated['state'],
            'contact_phone' => $validated['contact_phone'],
            'contact_email' => $validated['contact_email'],
            'type' => $validated['type'],
            'is_default' => $validated['is_default'] ?? false,
            'is_active' => $validated['status'] === 'active',
            'capacity' => $validated['capacity'], // <-- Added line
        ]);

        DB::commit();

        return $this->handleSuccess('Warehouse updated successfully.', $request);

    } catch (\Exception $e) {
        DB::rollBack();
        Log::error("Warehouse update failed for ID {$warehouse->id}: " . $e->getMessage());
        return $this->handleError('Failed to update warehouse.', $request);
    }
}

   /**
     * Delete a warehouse.
     */
    public function destroy(Warehouse $warehouse)
    {
        try {
            // Check if warehouse has assigned users/staff
            if ($warehouse->users()->exists()) {
                return redirect()->back()
                    ->with('error', 'Cannot delete warehouse with assigned staff.');
            }

            $warehouse->delete();
            
            return redirect()->route('dashboard.warehouses.index')
                ->with('success', 'Warehouse deleted successfully.');
                
        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            return redirect()->back()
                ->with('error', 'You are not authorized to delete this warehouse.');
                
        } catch (\Throwable $e) {
            \Log::error("Failed to delete warehouse [ID: {$warehouse->id}]: " . $e->getMessage(), [
                'warehouse_id' => $warehouse->id,
                'user_id' => auth()->id(),
                'stack' => $e->getTraceAsString(),
            ]);
            
            return redirect()->back()
                ->with('error', 'An unexpected error occurred. Warehouse could not be deleted.');
        }
    }

    /**
     * Toggle warehouse status
     */
    public function toggleStatus(Request $request, Warehouse $warehouse)
    {
        try {
            $request->validate([
                'status' => 'required|in:active,inactive',
            ]);

            $warehouse->update([
                'is_active' => $request->status === 'active',
            ]);

            return response()->json([
                'success' => true,
                'status' => $request->status,
                'message' => "Warehouse status updated to {$request->status}.",
            ]);

        } catch (\Exception $e) {
            Log::error("Toggle status failed for warehouse ID {$warehouse->id}: " . $e->getMessage());

            return response()->json([
                'success' => false,
                'error' => 'An error occurred while updating the warehouse status.'
            ], 500);
        }
    }

    /**
     * Show staff assignment form
     */
  public function assignStaff(Warehouse $warehouse)
{
    $this->authorize('assignStaff', $warehouse); // ✅ Explicit check

    $assignedUsers = $warehouse->users()->get();
    $availableUsers = User::whereNotIn('id', $assignedUsers->pluck('id'))
                         ->where('is_active', true)
                         ->get();
    $roles = Warehouse::getRoles();

    return view('dashboard.warehouses.assign-staff', compact(
        'warehouse', 'assignedUsers', 'availableUsers', 'roles'
    ));
}

    /**
     * Store staff assignment
     */
    public function storeStaffAssignment(Request $request, Warehouse $warehouse)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'role' => 'required|in:view_only,editor,manager,admin',
        ]);

        try {
            $warehouse->assignUser($request->user_id, $request->role);

            return $this->handleSuccess('Staff assigned successfully.', $request, 
                                      'dashboard.warehouses.assign-staff', $warehouse);

        } catch (\Exception $e) {
            Log::error("Staff assignment failed: " . $e->getMessage());
            return $this->handleError('Failed to assign staff.', $request);
        }
    }

    /**
     * Remove staff assignment
     */
    public function removeStaffAssignment(Request $request, Warehouse $warehouse, User $user)
    {
        try {
            $warehouse->removeUser($user->id);

            return response()->json([
                'success' => true,
                'message' => 'Staff removed successfully.',
            ]);

        } catch (\Exception $e) {
            Log::error("Staff removal failed: " . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'error' => 'Failed to remove staff assignment.'
            ], 500);
        }
    }

    /**
     * Update staff role
     */
    public function updateStaffRole(Request $request, Warehouse $warehouse, User $user)
    {
        $request->validate([
            'role' => 'required|in:view_only,editor,manager,admin',
        ]);

        try {
            $warehouse->users()->updateExistingPivot($user->id, ['role' => $request->role]);

            return response()->json([
                'success' => true,
                'message' => 'Staff role updated successfully.',
            ]);

        } catch (\Exception $e) {
            Log::error("Staff role update failed: " . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'error' => 'Failed to update staff role.'
            ], 500);
        }
    }

   /**
 * Warehouse validation
 */
private function validateWarehouse(Request $request, $warehouseId = null)
{
    $validated = $request->validate([
        'name' => [
            'required',
            'string',
            'max:255',
            'regex:/^[A-Za-z\s]+$/', // only letters and spaces
            Rule::unique('warehouses')->ignore($warehouseId),
        ],
        'address' => 'required|string|max:500',
        'city' => 'required|string|max:100',
        'state' => 'required|string|max:100',
        'contact_phone' => [
    'nullable',
    'regex:/^[6-9][0-9]{9}$/', // starts with 6-9, followed by 9 digits
],

        'contact_email' => 'nullable|email|max:255',
        'type' => 'required|in:main,cold_storage,transit,distribution,temporary',
        'is_active' => 'required|boolean',
        'is_default' => 'sometimes|boolean',
'capacity' => 'required|numeric|min:0',
    ], [
        // 🔽 Custom error messages
        'name.regex' => 'The name must contain only letters and spaces.',
       'contact_phone.regex' => 'Phone number must be 10 digits and start with 6, 7, 8, or 9.',
        'contact_email.email' => 'Please enter a valid email address.',
        'capacity.required' => 'The capacity field is required.',
        'capacity.integer' => 'The capacity must be an integer.',
        'capacity.min' => 'The capacity must be at least 0.',
    ]);

    return $validated;
}



   /**
 * Get Indian states list (you can customize this)
 */
private function getStates()
{
    return [
        'AP' => 'Andhra Pradesh',
        'AR' => 'Arunachal Pradesh',
        'AS' => 'Assam',
        'BR' => 'Bihar',
        'CT' => 'Chhattisgarh',
        'GA' => 'Goa',
        'GJ' => 'Gujarat',
        'HR' => 'Haryana',
        'HP' => 'Himachal Pradesh',
        'JH' => 'Jharkhand',
        'KA' => 'Karnataka',
        'KL' => 'Kerala',
        'MP' => 'Madhya Pradesh',
        'MH' => 'Maharashtra',
        'MN' => 'Manipur',
        'ML' => 'Meghalaya',
        'MZ' => 'Mizoram',
        'NL' => 'Nagaland',
        'OD' => 'Odisha',
        'PB' => 'Punjab',
        'RJ' => 'Rajasthan',
        'SK' => 'Sikkim',
        'TN' => 'Tamil Nadu',
        'TG' => 'Telangana',
        'TR' => 'Tripura',
        'UP' => 'Uttar Pradesh',
        'UK' => 'Uttarakhand',
        'WB' => 'West Bengal',
        'AN' => 'Andaman and Nicobar Islands',
        'CH' => 'Chandigarh',
        'DN' => 'Dadra and Nagar Haveli and Daman and Diu',
        'DL' => 'Delhi',
        'JK' => 'Jammu and Kashmir',
        'LA' => 'Ladakh',
        'LD' => 'Lakshadweep',
        'PY' => 'Puducherry'
    ];
}


    /**
     * Handle successful responses
     */
    private function handleSuccess($message, $request, $route = 'dashboard.warehouses.index', $parameters = null)
    {
        $redirectRoute = $parameters ? route($route, $parameters) : route($route);
        
        return $request->expectsJson()
            ? response()->json(['success' => true, 'message' => $message])
            : redirect($redirectRoute)->with('success', $message);
    }

    /**
     * Handle error responses
     */
    private function handleError($message, $request)
    {
        return $request->expectsJson()
            ? response()->json(['success' => false, 'error' => $message], 500)
            : redirect()->back()->with('error', $message)->withInput();
    }
  
  
  
   public function addBlock(Request $request, $warehouseId)
    {
        $block = WarehouseBlock::create([
            'warehouse_id' => $warehouseId,
            'name' => $request->name,
            'rows' => $request->rows,
            'columns' => $request->columns,
        ]);

        // Generate slots
        for ($r = 1; $r <= $block->rows; $r++) {
            for ($c = 1; $c <= $block->columns; $c++) {
                WarehouseSlot::create([
                    'block_id' => $block->id,
                    'row' => $r,
                    'column' => $c,
                    'status' => 'empty',
                ]);
            }
        }

        return redirect()->back();
    }
}

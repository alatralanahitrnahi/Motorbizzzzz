<?php

namespace App\Http\Controllers;
use App\Models\{QualityAnalysis, PurchaseOrder, PurchaseOrderItem, Vendor, Material, InventoryBatch};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{DB, Auth, Log};
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;


class QualityAnalysisController extends Controller
{
    public function index(Request $request)
    {
        $query = QualityAnalysis::with([
            'purchaseOrder:id,po_number,vendor_id',
            'purchaseOrderItem:id,item_name,quantity,material_id',
            'purchaseOrderItem.material:id,name,category,dimensions', // Added dimensions
            'vendor:id,name,business_name',
            'approvedBy:id,name'
        ]);

        // Apply filters
        if ($request->filled('status')) {
            $query->where('quality_status', $request->status);
        }

        if ($request->filled('vendor_id')) {
            $query->where('vendor_id', $request->vendor_id);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $analyses = $query->orderByDesc('created_at')->paginate(15);

        // Get summary statistics
        $summary = [
            'pending' => QualityAnalysis::where('quality_status', 'pending')->count(),
            'approved' => QualityAnalysis::where('quality_status', 'approved')->count(),
            'rejected' => QualityAnalysis::where('quality_status', 'rejected')->count(),
            'total' => QualityAnalysis::count()
        ];

        $vendors = Vendor::all();

        return view('quality_analysis.index', compact('analyses', 'summary', 'vendors'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Get vendors for filter
        $vendors = Vendor::orderBy('name')->get();
        
        // Get purchase orders that don't have quality analysis yet
        $purchaseOrders = PurchaseOrder::with('vendor')
            ->withoutQualityAnalysis()
            ->orderBy('created_at', 'desc')
            ->get();

        return view('quality_analysis.create', compact('vendors', 'purchaseOrders'));
    }

    /**
     * Store a newly created resource in storage.
     */
     public function store(Request $request)
{
    $validated = $request->validate([
        'purchase_order_id' => 'required|exists:purchase_orders,id',
        'items' => 'required|array|min:1',
        'items.*.purchase_order_item_id' => 'required|exists:purchase_order_items,id',
        'items.*.material_id' => 'required|exists:materials,id',
        'items.*.product_name' => 'required|string|max:255',
        'items.*.product_category' => 'required|string|max:255',
        'items.*.expected_volumetric_data' => 'required|numeric|min:0',
        'items.*.expected_weight' => 'required|numeric|min:0',
        'items.*.other_analysis_parameters' => 'nullable|string',
        'items.*.actual_volumetric_data' => 'nullable|numeric|min:0',
        'items.*.actual_weight' => 'nullable|numeric|min:0',
        'items.*.quantity_received' => 'required|numeric|min:0',
        'items.*.manufacturing_date' => 'nullable|date',
        'items.*.expiry_date' => 'nullable|date|after:manufacturing_date',
        'items.*.batch_number' => 'nullable|string|max:100',
        'items.*.remarks' => 'nullable|string'
    ]);

    DB::transaction(function () use ($validated) {
        $purchaseOrder = PurchaseOrder::with('vendor')->findOrFail($validated['purchase_order_id']);

        foreach ($validated['items'] as $item) {
            $purchaseOrderItem = PurchaseOrderItem::findOrFail($item['purchase_order_item_id']);

            // Just store the quality analysis — no InventoryBatch involved
            $qualityAnalysis = QualityAnalysis::create([
                'purchase_order_id' => $purchaseOrder->id,
                'purchase_order_item_id' => $purchaseOrderItem->id,
                'material_id' => $item['material_id'],
                'inventory_batch_id' => null, // Set to null since batch not tracked
                'product_name' => $item['product_name'],
                'product_category' => $item['product_category'],
                'expected_volumetric_data' => $item['expected_volumetric_data'],
                'expected_weight' => $item['expected_weight'],
                'other_analysis_parameters' => $item['other_analysis_parameters'],
                'actual_volumetric_data' => $item['actual_volumetric_data'],
                'actual_weight' => $item['actual_weight'],
                'vendor_id' => $purchaseOrder->vendor_id,
                'quantity_received' => $item['quantity_received'],
                'quality_status' => 'pending',
                'manufacturing_date' => $item['manufacturing_date'],
                'expiry_date' => $item['expiry_date'],
                'batch_number' => $item['batch_number'] ?? 'BATCH-' . now()->format('ymd-His'),
                'remarks' => $item['remarks'],
                'created_by' => Auth::id()
            ]);

            // Auto-generate SKU and Barcode
            $qualityAnalysis->update([
                'sku_id' => $qualityAnalysis->generateSkuId(),
                'barcode' => $qualityAnalysis->generateBarcode()
            ]);
        }
    });

    return redirect()->route('quality-analysis.index')
        ->with('success', 'Quality analysis records created successfully.');
}

    public function show($id)
    {
        $analysis = QualityAnalysis::with([
            'purchaseOrder.vendor',
            'purchaseOrderItem.material:id,name,category,unit,dimensions', // Added dimensions
            'inventoryBatch',
            'approvedBy',
            'createdBy'
        ])->findOrFail($id);

        return view('quality_analysis.show', compact('analysis'));
    }
   
   public function getPurchaseOrderItems($purchaseOrderId)
{
    try {
        $purchaseOrder = PurchaseOrder::with('vendor')->findOrFail($purchaseOrderId);
        
        // Get items with materials including dimensions
        $items = $purchaseOrder->items()
            ->with(['material:id,name,category,unit,dimensions']) // Added dimensions
            ->get();

        $mappedItems = $items->map(function ($item) use ($purchaseOrder) {
            // Fetch inventory batch based on purchase_order_item_id
            $existingBatch = InventoryBatch::where([
                'purchase_order_id' => $purchaseOrder->id,
                'material_id' => $item->material_id
            ])->orderByDesc('created_at')->first();

            // Process dimensions data
            $dimensions = null;
            if ($item->material && $item->material->dimensions) {
                $dimensions = is_string($item->material->dimensions) 
                    ? json_decode($item->material->dimensions, true) 
                    : $item->material->dimensions;
            }

            return [
                'id' => $item->id,
                'material_id' => $item->material_id,
                'item_name' => $item->item_name,
                'material_name' => $item->material->name ?? 'N/A',
                'material_category' => $item->material->category ?? 'N/A',
                'material_unit' => $item->material->unit ?? 'N/A',
                'material_dimensions' => $dimensions, // Added dimensions
                'quantity' => $item->quantity,
                'unit_price' => $item->unit_price,
                'total_price' => $item->total_price,
                // Batch details (if exist)
                'batch_number' => $existingBatch->batch_number ?? null,
                'manufacturing_date' => $existingBatch->manufacturing_date ?? null,
                'expiry_date' => $existingBatch->expiry_date ?? null,
                'has_existing_batch' => $existingBatch ? true : false
            ];
        });

        return response()->json([
            'success' => true,
            'purchase_order' => [
                'id' => $purchaseOrder->id,
                'po_number' => $purchaseOrder->po_number,
                'vendor_name' => optional($purchaseOrder->vendor)->name ?? 'N/A',
                'vendor_id' => $purchaseOrder->vendor_id,
                'po_date' => $purchaseOrder->created_at->format('Y-m-d'),
                'status' => $purchaseOrder->status
            ],
            'items' => $mappedItems
        ]);
    } catch (\Exception $e) {
        Log::error('Error fetching purchase order items: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Error fetching purchase order items: ' . $e->getMessage()
        ], 500);
    }
}

    /**
     * Show quality analysis for a specific purchase order
     */
    public function showByPurchaseOrder($purchaseOrderId)
    {
        $qualityAnalysis = QualityAnalysis::with([
            'purchaseOrder.vendor', 
            'items.purchaseOrderItem.material:id,name,category,unit,dimensions' // Added dimensions
        ])
            ->where('purchase_order_id', $purchaseOrderId)
            ->firstOrFail();

        return view('quality_analysis.show', compact('qualityAnalysis'));
    }

    public function edit($id)
    {
        $analysis = QualityAnalysis::with([
            'purchaseOrder.vendor',
            'purchaseOrderItem.material:id,name,category,unit,dimensions' // Added dimensions
        ])->findOrFail($id);

        return view('quality_analysis.edit', compact('analysis'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => 'required|string',
            'analysis_date' => 'nullable|date',
            'notes' => 'nullable|string',
            'analyst_name' => 'nullable|string',
            'approval_status' => 'nullable|string',

            'expected_volumetric_data' => 'nullable|numeric',
            'expected_weight' => 'nullable|numeric',
            'actual_volumetric_data' => 'nullable|numeric',
            'actual_weight' => 'nullable|numeric',
            'remarks' => 'nullable|string',
            'manufacturing_date' => 'nullable|date',
            'expiry_date' => 'nullable|date|after_or_equal:manufacturing_date',
        ]);

        DB::transaction(function () use ($validated, $id) {
            $analysis = QualityAnalysis::with(['purchaseOrderItem.material:id,name,dimensions'])->findOrFail($id);

            $analysis->update(array_merge($validated, [
                'updated_by' => Auth::id(),
            ]));
        });

        return redirect()->route('quality-analysis.index')
            ->with('success', 'Quality analysis updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(QualityAnalysis $qualityAnalysis)
    {
        try {
            $qualityAnalysis->delete();
            
            return redirect()->route('quality-analysis.index')
                ->with('success', 'Quality analysis deleted successfully.');
                
        } catch (\Exception $e) {
            \Log::error('Quality Analysis Deletion Error: ' . $e->getMessage());
            
            return redirect()->back()
                ->withErrors(['error' => 'Failed to delete quality analysis. Please try again.']);
        }
    }
    
    public function approve(Request $request, $id)
    {
        $request->validate([
            'remarks' => 'nullable|string',
            'approved_items' => 'required|array|min:1',
            'approved_items.*' => 'in:' . $id
        ]);

        DB::transaction(function () use ($request, $id) {
            $analysis = QualityAnalysis::with(['purchaseOrderItem.material:id,dimensions'])->findOrFail($id);
            
            $analysis->update([
                'quality_status' => 'approved',
                'approved_by' => Auth::id(),
                'approved_at' => now(),
                'remarks' => $request->remarks
            ]);

            // Update inventory batch status
            if ($analysis->inventoryBatch) {
                $analysis->inventoryBatch->update([
                   'status' => 'approved',
                    'approved_by' => Auth::id(),
                    'approved_at' => now()
                ]);
            }

            // Generate new barcode and SKU if not already generated
            if (!$analysis->barcode) {
                $analysis->update(['barcode' => $analysis->generateBarcode()]);
            }
            
            if (!$analysis->sku_id) {
                $analysis->update(['sku_id' => $analysis->generateSkuId()]);
            }
        });

        return redirect()->route('quality-analysis.index')
            ->with('success', 'Quality analysis approved successfully.');
    }

    public function reject(Request $request, $id)
    {
        $request->validate([
            'rejected_reason' => 'required|string|max:500'
        ]);

        DB::transaction(function () use ($request, $id) {
            $analysis = QualityAnalysis::with(['purchaseOrderItem.material:id,dimensions'])->findOrFail($id);
            
            $analysis->update([
                'quality_status' => 'rejected',
                'rejected_reason' => $request->rejected_reason,
                'approved_by' => Auth::id(),
                'approved_at' => now()
            ]);

            // Update inventory batch status
            if ($analysis->inventoryBatch) {
                $analysis->inventoryBatch->update([
                    'status' => 'rejected',
                    'rejected_reason' => $request->rejected_reason,
                    'approved_by' => Auth::id(),
                    'approved_at' => now()
                ]);
            }
        });

        return redirect()->route('quality-analysis.index')
            ->with('success', 'Quality analysis rejected.');
    }

    public function bulkApprove(Request $request)
    {
        $request->validate([
            'selected_items' => 'required|array|min:1',
            'selected_items.*' => 'exists:quality_analyses,id',
            'bulk_remarks' => 'nullable|string'
        ]);

        DB::transaction(function () use ($request) {
            $analyses = QualityAnalysis::with(['purchaseOrderItem.material:id,dimensions'])
                ->whereIn('id', $request->selected_items)
                ->where('quality_status', 'pending')
                ->get();

            foreach ($analyses as $analysis) {
                $analysis->update([
                    'quality_status' => 'approved',
                    'approved_by' => Auth::id(),
                    'approved_at' => now(),
                    'remarks' => $request->bulk_remarks
                ]);

                // Update inventory batch status
                if ($analysis->inventoryBatch) {
                    $analysis->inventoryBatch->update([
                        'status' => 'approved',
                        'approved_by' => Auth::id(),
                        'approved_at' => now()
                    ]);
                }

                // Generate barcodes and SKUs
                if (!$analysis->barcode) {
                    $analysis->update(['barcode' => $analysis->generateBarcode()]);
                }
                if (!$analysis->sku_id) {
                    $analysis->update(['sku_id' => $analysis->generateSkuId()]);
                }
            }
        });

        return redirect()->route('quality-analysis.index')
            ->with('success', count($request->selected_items) . ' items approved successfully.');
    }

    public function generateBarcodes(Request $request)
    {
        $request->validate([
            'selected_items' => 'required|array|min:1',
            'selected_items.*' => 'exists:quality_analyses,id'
        ]);

        $analyses = QualityAnalysis::with(['purchaseOrderItem.material:id,dimensions'])
            ->whereIn('id', $request->selected_items)
            ->where('quality_status', 'approved')
            ->get();

        foreach ($analyses as $analysis) {
            if (!$analysis->barcode) {
                $analysis->update(['barcode' => $analysis->generateBarcode()]);
            }
        }

        return redirect()->route('quality-analysis.index')
            ->with('success', 'Barcodes generated for ' . $analyses->count() . ' items.');
    }

    public function printBarcodes(Request $request)
    {
        $request->validate([
            'selected_items' => 'required|array|min:1',
            'selected_items.*' => 'exists:quality_analyses,id'
        ]);

        $analyses = QualityAnalysis::with([
            'purchaseOrder', 
            'vendor', 
            'inventoryBatch',
            'purchaseOrderItem.material:id,name,category,unit,dimensions' // Added dimensions
        ])
            ->whereIn('id', $request->selected_items)
            ->where('quality_status', 'approved')
            ->get();

        return view('quality_analysis.print_barcodes', compact('analyses'));
    }

    public function getBatchHistory($materialId, $purchaseOrderId = null)
    {
        $query = InventoryBatch::where('material_id', $materialId)
            ->with([
                'purchaseOrder:id,po_number', 
                'supplier:id,name',
                'material:id,name,category,unit,dimensions' // Added dimensions
            ])
            ->orderByDesc('created_at');

        if ($purchaseOrderId) {
            $query->where('purchase_order_id', $purchaseOrderId);
        }

        $batches = $query->get()->map(function ($batch) {
            // Process dimensions if available
            $dimensions = null;
            if ($batch->material && $batch->material->dimensions) {
                $dimensions = is_string($batch->material->dimensions) 
                    ? json_decode($batch->material->dimensions, true) 
                    : $batch->material->dimensions;
            }

            return [
                'id' => $batch->id,
                'batch_number' => $batch->batch_number,
                'material_name' => $batch->material->name ?? 'N/A',
                'material_category' => $batch->material->category ?? 'N/A',
                'material_unit' => $batch->material->unit ?? 'N/A',
                'material_dimensions' => $dimensions,
                'purchase_order' => $batch->purchaseOrder,
                'supplier' => $batch->supplier,
                'manufacturing_date' => $batch->manufacturing_date,
                'expiry_date' => $batch->expiry_date,
                'quantity' => $batch->quantity,
                'status' => $batch->status,
                'created_at' => $batch->created_at,
                'updated_at' => $batch->updated_at
            ];
        });

        return response()->json([
            'success' => true,
            'batches' => $batches
        ]);
    }

    /**
     * Get material details with dimensions for quality analysis
     */
    public function getMaterialDetails($materialId)
    {
        try {
            $material = Material::select('id', 'name', 'code', 'sku', 'category', 'unit', 'dimensions', 'unit_price')
                ->findOrFail($materialId);

            // Process dimensions
            $dimensions = null;
            if ($material->dimensions) {
                $dimensions = is_string($material->dimensions) 
                    ? json_decode($material->dimensions, true) 
                    : $material->dimensions;
            }

            return response()->json([
                'success' => true,
                'material' => [
                    'id' => $material->id,
                    'name' => $material->name,
                    'code' => $material->code,
                    'sku' => $material->sku,
                    'category' => $material->category,
                    'unit' => $material->unit,
                    'unit_price' => $material->unit_price,
                    'dimensions' => $dimensions,
                    'formatted_dimensions' => $this->formatDimensions($dimensions)
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching material details: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Material not found or error occurred'
            ], 404);
        }
    }

    /**
     * Format dimensions for display
     */
    private function formatDimensions($dimensions)
    {
        if (!$dimensions || !is_array($dimensions)) {
            return 'N/A';
        }

        $formatted = [];
        
        if (isset($dimensions['length']) && $dimensions['length'] > 0) {
            $formatted[] = "L: {$dimensions['length']}";
        }
        
        if (isset($dimensions['width']) && $dimensions['width'] > 0) {
            $formatted[] = "W: {$dimensions['width']}";
        }
        
        if (isset($dimensions['height']) && $dimensions['height'] > 0) {
            $formatted[] = "H: {$dimensions['height']}";
        }
        
        if (isset($dimensions['diameter']) && $dimensions['diameter'] > 0) {
            $formatted[] = "D: {$dimensions['diameter']}";
        }

        return !empty($formatted) ? implode(' × ', $formatted) : 'N/A';
    }
}
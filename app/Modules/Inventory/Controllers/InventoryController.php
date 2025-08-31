<?php

namespace App\Modules\Inventory\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Inventory\Models\Item;
use App\Models\Council;
use App\Models\Department;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function index(Request $request)
    {
        $query = Item::query();

        // Apply filters
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('sku', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('status')) {
            if ($request->status === 'low_stock') {
                $query->whereRaw('current_stock <= minimum_stock');
            } elseif ($request->status === 'out_of_stock') {
                $query->where('current_stock', 0);
            }
        }

        $items = $query->orderBy('name')->paginate(20);
        $categories = Item::with('category')->get()->pluck('category.name')->filter()->unique();

        $stats = [
            'total_items' => Item::count(),
            'low_stock_items' => Item::whereRaw('current_stock <= minimum_stock')->count(),
            'out_of_stock_items' => Item::where('current_stock', 0)->count(),
            'total_value' => Item::selectRaw('SUM(current_stock * unit_cost) as total')->value('total') ?? 0
        ];

        return view('inventory.index', compact('items', 'stats', 'categories'));
    }

    public function create()
    {
        $councils = Council::where('is_active', true)->get();
        $departments = Department::where('is_active', true)->get();
        $categories = Item::distinct()->pluck('category')->filter()->values();

        return view('inventory.create', compact('councils', 'departments', 'categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|string|max:100',
            'unit_of_measure' => 'required|string|max:50',
            'current_stock' => 'required|integer|min:0',
            'minimum_stock' => 'required|integer|min:0',
            'maximum_stock' => 'required|integer|min:1',
            'unit_cost' => 'required|numeric|min:0',
            'location' => 'nullable|string|max:255',
            'supplier_name' => 'nullable|string|max:255',
            'supplier_contact' => 'nullable|string|max:255',
            'council_id' => 'required|exists:councils,id',
            'department_id' => 'required|exists:departments,id',
            'expiry_date' => 'nullable|date|after:today',
        ]);

        $validated['total_value'] = $validated['current_stock'] * $validated['unit_cost'];

        Item::create($validated);

        return redirect()->route('inventory.index')
            ->with('success', 'Inventory item created successfully.');
    }

    public function show(Item $item)
    {
        $item->load(['council', 'department']);

        return view('inventory.show', compact('item'));
    }

    public function edit(Item $item)
    {
        $councils = Council::where('is_active', true)->get();
        $departments = Department::where('is_active', true)->get();
        $categories = Item::distinct()->pluck('category')->filter()->values();

        return view('inventory.edit', compact('item', 'councils', 'departments', 'categories'));
    }

    public function update(Request $request, Item $item)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|string|max:100',
            'unit_of_measure' => 'required|string|max:50',
            'current_stock' => 'required|integer|min:0',
            'minimum_stock' => 'required|integer|min:0',
            'maximum_stock' => 'required|integer|min:1',
            'unit_cost' => 'required|numeric|min:0',
            'location' => 'nullable|string|max:255',
            'supplier_name' => 'nullable|string|max:255',
            'supplier_contact' => 'nullable|string|max:255',
            'council_id' => 'required|exists:councils,id',
            'department_id' => 'required|exists:departments,id',
            'expiry_date' => 'nullable|date|after:today',
        ]);

        $validated['total_value'] = $validated['current_stock'] * $validated['unit_cost'];

        $item->update($validated);

        return redirect()->route('inventory.index')
            ->with('success', 'Inventory item updated successfully.');
    }

    public function destroy(Item $item)
    {
        $item->delete();

        return redirect()->route('inventory.index')
            ->with('success', 'Inventory item deleted successfully.');
    }

    public function stockIn(Request $request, Item $item)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
            'notes' => 'nullable|string',
        ]);

        $item->current_stock += $request->quantity;
        $item->total_value = $item->current_stock * $item->unit_cost;
        $item->save();

        return redirect()->route('inventory.show', $item)
            ->with('success', "Added {$request->quantity} {$item->unit_of_measure} to stock.");
    }

    public function stockOut(Request $request, Item $item)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1|max:' . $item->current_stock,
            'notes' => 'nullable|string',
        ]);

        $item->current_stock -= $request->quantity;
        $item->total_value = $item->current_stock * $item->unit_cost;
        $item->save();

        return redirect()->route('inventory.show', $item)
            ->with('success', "Removed {$request->quantity} {$item->unit_of_measure} from stock.");
    }

    public function lowStock()
    {
        $items = Item::whereColumn('current_stock', '<=', 'minimum_stock')
            ->with(['council', 'department'])
            ->orderBy('current_stock')
            ->get();

        return view('inventory.reports.low-stock', compact('items'));
    }

    public function expiringItems()
    {
        $items = Item::whereNotNull('expiry_date')
            ->where('expiry_date', '<=', now()->addDays(30))
            ->with(['council', 'department'])
            ->orderBy('expiry_date')
            ->get();

        return view('inventory.reports.expiring', compact('items'));
    }

    public function reports()
    {
        $stats = [
            'total_items' => Item::count(),
            'total_categories' => Item::distinct('category')->count(),
            'low_stock_items' => Item::whereColumn('current_stock', '<=', 'minimum_stock')->count(),
            'out_of_stock_items' => Item::where('current_stock', 0)->count(),
            'expiring_soon' => Item::whereNotNull('expiry_date')
                ->where('expiry_date', '<=', now()->addDays(30))->count(),
            'total_value' => Item::selectRaw('SUM(current_stock * unit_cost) as total')->value('total') ?? 0,
        ];

        $categoryStats = Item::selectRaw('category, COUNT(*) as count, SUM(current_stock * unit_cost) as value')
            ->groupBy('category')
            ->orderBy('count', 'desc')
            ->get();

        return view('inventory.reports.all', compact('stats', 'categoryStats'));
    }

    public function categories()
    {
        $categories = Item::distinct('category')
            ->whereNotNull('category')
            ->pluck('category')
            ->map(function ($category) {
                return [
                    'name' => $category,
                    'count' => Item::where('category', $category)->count(),
                    'value' => Item::where('category', $category)
                        ->selectRaw('SUM(current_stock * unit_cost) as total')
                        ->value('total') ?? 0,
                ];
            });

        return view('inventory.categories.index', compact('categories'));
    }

    public function createCategory()
    {
        return view('inventory.categories.create');
    }

    public function storeCategory(Request $request)
    {
        // Categories are created dynamically when items are added
        return redirect()->route('inventory.categories.index')
            ->with('success', 'Category functionality is handled through item creation.');
    }

    public function stockManagement()
    {
        $items = Item::with(['council', 'department'])
            ->orderBy('name')
            ->paginate(15);

        return view('inventory.stock.index', compact('items'));
    }

    public function stockAdjustments()
    {
        // This would typically fetch stock adjustment history
        $adjustments = collect(); // Placeholder for adjustment history

        return view('inventory.stock.adjustments', compact('adjustments'));
    }

    public function adjustStock(Request $request)
    {
        $request->validate([
            'item_id' => 'required|exists:items,id',
            'adjustment_type' => 'required|in:increase,decrease',
            'quantity' => 'required|integer|min:1',
            'reason' => 'required|string|max:255',
        ]);

        $item = Item::findOrFail($request->item_id);

        if ($request->adjustment_type === 'increase') {
            $item->current_stock += $request->quantity;
        } else {
            $item->current_stock = max(0, $item->current_stock - $request->quantity);
        }

        $item->total_value = $item->current_stock * $item->unit_cost;
        $item->save();

        return redirect()->route('inventory.stock.index')
            ->with('success', "Stock adjusted for {$item->name}");
    }

    public function valuationReport()
    {
        $items = Item::with(['council', 'department'])
            ->selectRaw('*, (current_stock * unit_cost) as total_value')
            ->orderBy('total_value', 'desc')
            ->get();

        $totalValue = $items->sum('total_value');

        return view('inventory.reports.valuation', compact('items', 'totalValue'));
    }
}
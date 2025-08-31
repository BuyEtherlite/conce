<?php

namespace App\Modules\Inventory\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Council;
use App\Models\Department;

class Item extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'inventory_items';

    protected $fillable = [
        'name',
        'description',
        'category',
        'unit_of_measure',
        'current_stock',
        'minimum_stock',
        'maximum_stock',
        'unit_cost',
        'total_value',
        'location',
        'supplier_name',
        'supplier_contact',
        'council_id',
        'department_id',
        'expiry_date',
        'is_active',
    ];

    protected $casts = [
        'current_stock' => 'integer',
        'minimum_stock' => 'integer',
        'maximum_stock' => 'integer',
        'unit_cost' => 'decimal:2',
        'total_value' => 'decimal:2',
        'expiry_date' => 'date',
        'is_active' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($item) {
            $item->total_value = $item->current_stock * $item->unit_cost;
        });
    }

    // Existing relationships
    public function council()
    {
        return $this->belongsTo(Council::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    // New relationships
    public function transactions()
    {
        return $this->hasMany(InventoryTransaction::class, 'item_id');
    }

    public function purchaseOrderItems()
    {
        return $this->hasMany(InventoryPurchaseOrderItem::class, 'item_id');
    }

    public function supplier()
    {
        return $this->belongsTo(InventorySupplier::class, 'supplier_id');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeLowStock($query)
    {
        return $query->whereColumn('current_stock', '<=', 'minimum_stock');
    }

    public function scopeOutOfStock($query)
    {
        return $query->where('current_stock', '<=', 0);
    }

    public function scopeExpiringSoon($query, $days = 30)
    {
        return $query->whereNotNull('expiry_date')
                     ->where('expiry_date', '<=', now()->addDays($days));
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    // Existing methods
    public function isLowStock()
    {
        return $this->current_stock <= $this->minimum_stock;
    }

    public function isOutOfStock()
    {
        return $this->current_stock == 0;
    }

    public function isExpiringSoon($days = 30)
    {
        if (!$this->expiry_date) {
            return false;
        }

        return $this->expiry_date <= now()->addDays($days);
    }

    public function getStockStatusAttribute()
    {
        if ($this->isOutOfStock()) {
            return 'out-of-stock';
        }

        if ($this->isLowStock()) {
            return 'low-stock';
        }

        return 'in-stock';
    }

    public function getStockPercentageAttribute()
    {
        if ($this->maximum_stock == 0) {
            return 0;
        }

        return ($this->current_stock / $this->maximum_stock) * 100;
    }

    // New methods
    public function addStock($quantity, $unitCost = null, $notes = null, $userId = null)
    {
        $this->current_stock += $quantity;
        $this->save();

        // Create transaction record
        InventoryTransaction::create([
            'item_id' => $this->id,
            'type' => 'in',
            'quantity' => $quantity,
            'unit_cost' => $unitCost ?? $this->unit_cost,
            'notes' => $notes,
            'user_id' => $userId ?? auth()->id(),
            'status' => 'approved',
        ]);

        return $this;
    }

    public function removeStock($quantity, $notes = null, $userId = null)
    {
        if ($quantity > $this->current_stock) {
            throw new \Exception('Insufficient stock available');
        }

        $this->current_stock -= $quantity;
        $this->save();

        // Create transaction record
        InventoryTransaction::create([
            'item_id' => $this->id,
            'type' => 'out',
            'quantity' => $quantity,
            'unit_cost' => $this->unit_cost,
            'notes' => $notes,
            'user_id' => $userId ?? auth()->id(),
            'status' => 'approved',
        ]);

        return $this;
    }

    public function adjustStock($newQuantity, $reason = null, $userId = null)
    {
        $adjustment = $newQuantity - $this->current_stock;
        $this->current_stock = $newQuantity;
        $this->save();

        // Create transaction record
        InventoryTransaction::create([
            'item_id' => $this->id,
            'type' => 'adjustment',
            'quantity' => $adjustment,
            'unit_cost' => $this->unit_cost,
            'notes' => $reason,
            'user_id' => $userId ?? auth()->id(),
            'status' => 'approved',
        ]);

        return $this;
    }

    public function getRecentTransactions($limit = 10)
    {
        return $this->transactions()
                    ->with(['user'])
                    ->orderBy('created_at', 'desc')
                    ->limit($limit)
                    ->get();
    }
}

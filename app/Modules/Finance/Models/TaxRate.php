<?php

namespace App\Modules\Finance\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TaxRate extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'finance_tax_rates';

    protected $fillable = [
        'name',
        'rate',
        'description',
        'is_active',
        'council_id'
    ];

    protected $casts = [
        'rate' => 'decimal:4',
        'is_active' => 'boolean'
    ];

    public function council()
    {
        return $this->belongsTo(\App\Models\Council::class);
    }
}
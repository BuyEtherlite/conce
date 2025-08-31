<?php

namespace App\Modules\Finance\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerLoyaltyPoints extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'points_balance',
        'points_earned_total',
        'points_redeemed_total',
        'tier',
        'tier_expiry_date'
    ];

    protected $casts = [
        'tier_expiry_date' => 'date'
    ];

    const TIERS = [
        'bronze' => ['name' => 'Bronze', 'min_points' => 0, 'benefits' => '1% discount'],
        'silver' => ['name' => 'Silver', 'min_points' => 1000, 'benefits' => '3% discount'],
        'gold' => ['name' => 'Gold', 'min_points' => 5000, 'benefits' => '5% discount'],
        'platinum' => ['name' => 'Platinum', 'min_points' => 15000, 'benefits' => '10% discount']
    ];

    /**
     * Get the customer
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Get loyalty point transactions
     */
    public function transactions()
    {
        return $this->hasMany(LoyaltyPointTransaction::class, 'customer_id', 'customer_id');
    }

    /**
     * Update tier based on total points earned
     */
    public function updateTier()
    {
        $newTier = 'bronze';
        
        foreach (array_reverse(self::TIERS, true) as $tier => $config) {
            if ($this->points_earned_total >= $config['min_points']) {
                $newTier = $tier;
                break;
            }
        }

        if ($this->tier !== $newTier) {
            $this->tier = $newTier;
            $this->tier_expiry_date = now()->addYear();
        }
    }

    /**
     * Get tier information
     */
    public function getTierInfoAttribute()
    {
        return self::TIERS[$this->tier] ?? self::TIERS['bronze'];
    }

    /**
     * Get tier name
     */
    public function getTierNameAttribute()
    {
        return $this->tier_info['name'];
    }

    /**
     * Get tier benefits
     */
    public function getTierBenefitsAttribute()
    {
        return $this->tier_info['benefits'];
    }

    /**
     * Get discount percentage for tier
     */
    public function getDiscountPercentageAttribute()
    {
        $discounts = [
            'bronze' => 1,
            'silver' => 3,
            'gold' => 5,
            'platinum' => 10
        ];

        return $discounts[$this->tier] ?? 0;
    }

    /**
     * Add points
     */
    public function addPoints($points, $description, $paymentId = null)
    {
        $this->points_balance += $points;
        $this->points_earned_total += $points;
        $this->updateTier();
        $this->save();

        LoyaltyPointTransaction::create([
            'customer_id' => $this->customer_id,
            'payment_id' => $paymentId,
            'type' => 'earned',
            'points' => $points,
            'description' => $description,
            'expiry_date' => now()->addYear()
        ]);

        return $this;
    }

    /**
     * Redeem points
     */
    public function redeemPoints($points, $description)
    {
        if ($this->points_balance < $points) {
            throw new \Exception('Insufficient points balance');
        }

        $this->points_balance -= $points;
        $this->points_redeemed_total += $points;
        $this->save();

        LoyaltyPointTransaction::create([
            'customer_id' => $this->customer_id,
            'type' => 'redeemed',
            'points' => $points,
            'description' => $description
        ]);

        return $this;
    }

    /**
     * Check if customer can redeem points
     */
    public function canRedeem($points)
    {
        return $this->points_balance >= $points;
    }

    /**
     * Get points value in currency
     */
    public function getPointsValue($points = null)
    {
        $points = $points ?? $this->points_balance;
        // 100 points = $1
        return $points / 100;
    }
}
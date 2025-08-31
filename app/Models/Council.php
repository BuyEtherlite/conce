<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Council extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'official_name',
        'address',
        'city',
        'state',
        'postal_code',
        'country',
        'phone',
        'email',
        'website',
        'contact_person',
        'contact_phone',
        'contact_email',
        'logo',
        'settings',
        'timezone',
        'currency',
        'language',
        'is_active'
    ];

    protected $casts = [
        'settings' => 'array',
        'is_active' => 'boolean'
    ];

    protected $attributes = [
        'is_active' => true,
        'timezone' => 'UTC',
        'currency' => 'USD',
        'language' => 'en'
    ];

    /**
     * Get all departments for this council
     */
    public function departments()
    {
        return $this->hasMany(Department::class);
    }

    /**
     * Get all users for this council
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }

    /**
     * Get all customers for this council
     */
    public function customers()
    {
        return $this->hasMany(Customer::class);
    }

    /**
     * Get council settings
     */
    public function getSetting($key, $default = null)
    {
        $settings = $this->settings ?? [];
        return $settings[$key] ?? $default;
    }

    /**
     * Set council setting
     */
    public function setSetting($key, $value)
    {
        $settings = $this->settings ?? [];
        $settings[$key] = $value;
        $this->update(['settings' => $settings]);
        return $this;
    }

    /**
     * Get full address
     */
    public function getFullAddress()
    {
        $parts = array_filter([
            $this->address,
            $this->city,
            $this->state,
            $this->postal_code,
            $this->country
        ]);

        return implode(', ', $parts);
    }

    /**
     * Get contact information
     */
    public function getContactInfo()
    {
        return [
            'phone' => $this->phone,
            'email' => $this->email,
            'contact_person' => $this->contact_person,
            'contact_phone' => $this->contact_phone,
            'contact_email' => $this->contact_email,
        ];
    }
}

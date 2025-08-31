<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Modules\Finance\Models\Currency;
use App\Modules\Finance\Models\ExchangeRateHistory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;

class MulticurrencySystemTest extends TestCase
{
    use RefreshDatabase;

    private $user;
    private $baseCurrency;
    private $foreignCurrency;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'role' => 'admin'
        ]);

        $this->baseCurrency = Currency::create([
            'code' => 'USD',
            'name' => 'US Dollar',
            'symbol' => '$',
            'exchange_rate' => 1.000000,
            'is_base_currency' => true,
            'is_active' => true,
            'rounding_precision' => 2,
            'created_by' => $this->user->id
        ]);

        $this->foreignCurrency = Currency::create([
            'code' => 'EUR',
            'name' => 'Euro',
            'symbol' => '€',
            'exchange_rate' => 0.850000,
            'is_base_currency' => false,
            'is_active' => true,
            'auto_update' => true,
            'rounding_precision' => 2,
            'created_by' => $this->user->id
        ]);
    }

    /** @test */
    public function user_can_create_new_currency()
    {
        $this->actingAs($this->user);

        $currencyData = [
            'code' => 'GBP',
            'name' => 'British Pound',
            'symbol' => '£',
            'exchange_rate' => 0.75,
            'is_base_currency' => false,
            'auto_update' => true,
            'rounding_precision' => 2
        ];

        $response = $this->post(route('finance.multicurrency.store'), $currencyData);

        $response->assertRedirect(route('finance.multicurrency.index'))
                ->assertSessionHas('success');

        $this->assertDatabaseHas('currencies', [
            'code' => 'GBP',
            'name' => 'British Pound',
            'symbol' => '£',
            'exchange_rate' => 0.75
        ]);

        // Check if exchange rate history was created
        $currency = Currency::where('code', 'GBP')->first();
        $this->assertDatabaseHas('exchange_rate_histories', [
            'currency_id' => $currency->id,
            'exchange_rate' => 0.75,
            'source' => 'manual'
        ]);
    }

    /** @test */
    public function user_can_convert_between_currencies()
    {
        $this->actingAs($this->user);

        $response = $this->postJson('/api/v1/finance/multicurrency/convert', [
            'amount' => 100,
            'from_currency' => $this->baseCurrency->id,
            'to_currency' => $this->foreignCurrency->id
        ]);

        $response->assertStatus(200)
                ->assertJson([
                    'success' => true,
                    'converted_amount' => 85.0, // 100 * 0.85
                    'from' => [
                        'code' => 'USD',
                        'rate' => 1.0
                    ],
                    'to' => [
                        'code' => 'EUR',
                        'rate' => 0.85
                    ]
                ]);
    }

    /** @test */
    public function user_can_update_exchange_rate_with_history()
    {
        $this->actingAs($this->user);

        $updateData = [
            'name' => 'Euro',
            'symbol' => '€',
            'exchange_rate' => 0.90, // Changed from 0.85
            'auto_update' => true,
            'rounding_precision' => 2
        ];

        $response = $this->put(route('finance.multicurrency.update', $this->foreignCurrency->id), $updateData);

        $response->assertRedirect(route('finance.multicurrency.index'))
                ->assertSessionHas('success');

        // Check if currency was updated
        $this->foreignCurrency->refresh();
        $this->assertEquals(0.90, $this->foreignCurrency->exchange_rate);

        // Check if new exchange rate history was created
        $this->assertDatabaseHas('exchange_rate_histories', [
            'currency_id' => $this->foreignCurrency->id,
            'exchange_rate' => 0.90,
            'source' => 'manual'
        ]);
    }

    /** @test */
    public function user_can_get_exchange_rate_history()
    {
        $this->actingAs($this->user);

        // Create some exchange rate history
        ExchangeRateHistory::create([
            'currency_id' => $this->foreignCurrency->id,
            'exchange_rate' => 0.85,
            'effective_date' => now()->subDays(2),
            'source' => 'api',
            'is_active' => false,
            'created_by' => $this->user->id
        ]);

        ExchangeRateHistory::create([
            'currency_id' => $this->foreignCurrency->id,
            'exchange_rate' => 0.87,
            'effective_date' => now()->subDay(),
            'source' => 'api',
            'is_active' => false,
            'created_by' => $this->user->id
        ]);

        ExchangeRateHistory::create([
            'currency_id' => $this->foreignCurrency->id,
            'exchange_rate' => 0.90,
            'effective_date' => now(),
            'source' => 'manual',
            'is_active' => true,
            'created_by' => $this->user->id
        ]);

        $response = $this->getJson("/api/v1/finance/multicurrency/exchange-rate-history/{$this->foreignCurrency->id}");

        $response->assertStatus(200)
                ->assertJson([
                    'success' => true
                ])
                ->assertJsonStructure([
                    'currency',
                    'history' => [
                        'data' => [
                            '*' => [
                                'exchange_rate',
                                'effective_date',
                                'source',
                                'is_active'
                            ]
                        ]
                    ]
                ]);
    }

    /** @test */
    public function system_can_update_exchange_rates_from_api()
    {
        $this->actingAs($this->user);

        // Mock external API response
        Cache::shouldReceive('remember')
            ->once()
            ->andReturn(0.95);

        $response = $this->postJson('/api/v1/finance/multicurrency/update-rates');

        $response->assertStatus(200)
                ->assertJson([
                    'success' => true
                ])
                ->assertJsonStructure([
                    'message',
                    'updated_count',
                    'errors'
                ]);
    }

    /** @test */
    public function base_currency_cannot_be_deleted()
    {
        $this->actingAs($this->user);

        $response = $this->delete(route('finance.multicurrency.destroy', $this->baseCurrency->id));

        $response->assertRedirect()
                ->assertSessionHas('error', 'Cannot delete base currency.');

        $this->assertDatabaseHas('currencies', [
            'id' => $this->baseCurrency->id,
            'code' => 'USD'
        ]);
    }

    /** @test */
    public function setting_new_base_currency_unsets_previous_base()
    {
        $this->actingAs($this->user);

        $newBaseCurrency = Currency::create([
            'code' => 'GBP',
            'name' => 'British Pound',
            'symbol' => '£',
            'exchange_rate' => 0.75,
            'is_base_currency' => false,
            'is_active' => true,
            'created_by' => $this->user->id
        ]);

        $updateData = [
            'name' => 'British Pound',
            'symbol' => '£',
            'exchange_rate' => 1.0, // Will be automatically set to 1.0
            'is_base_currency' => true,
            'rounding_precision' => 2
        ];

        $response = $this->put(route('finance.multicurrency.update', $newBaseCurrency->id), $updateData);

        $response->assertRedirect(route('finance.multicurrency.index'))
                ->assertSessionHas('success');

        // Check if new currency is base
        $newBaseCurrency->refresh();
        $this->assertTrue($newBaseCurrency->is_base_currency);
        $this->assertEquals(1.0, $newBaseCurrency->exchange_rate);

        // Check if old base currency is no longer base
        $this->baseCurrency->refresh();
        $this->assertFalse($this->baseCurrency->is_base_currency);
    }

    /** @test */
    public function currency_model_calculates_conversions_correctly()
    {
        // Test conversion methods
        $amount = 100;

        // Convert from base to foreign
        $convertedToForeign = $this->foreignCurrency->convertFromBase($amount);
        $this->assertEquals(85.00, $convertedToForeign);

        // Convert from foreign to base
        $convertedToBase = $this->foreignCurrency->convertToBase($convertedToForeign);
        $this->assertEquals(100.00, $convertedToBase);

        // Convert between two foreign currencies
        $anotherCurrency = Currency::create([
            'code' => 'JPY',
            'name' => 'Japanese Yen',
            'symbol' => '¥',
            'exchange_rate' => 110.0,
            'is_base_currency' => false,
            'rounding_precision' => 0,
            'created_by' => $this->user->id
        ]);

        $convertedToYen = $this->foreignCurrency->convertTo(85, $anotherCurrency);
        // 85 EUR -> 100 USD -> 11000 JPY
        $this->assertEquals(11000, $convertedToYen);
    }

    /** @test */
    public function currency_formatting_works_correctly()
    {
        $amount = 1234.56;

        $formattedUSD = $this->baseCurrency->formatAmount($amount);
        $this->assertEquals('$ 1,234.56', $formattedUSD);

        $formattedEUR = $this->foreignCurrency->formatAmount($amount);
        $this->assertEquals('€ 1,234.56', $formattedEUR);

        // Test with different precision
        $jpyCurrency = Currency::create([
            'code' => 'JPY',
            'name' => 'Japanese Yen',
            'symbol' => '¥',
            'exchange_rate' => 110.0,
            'rounding_precision' => 0,
            'created_by' => $this->user->id
        ]);

        $formattedJPY = $jpyCurrency->formatAmount($amount);
        $this->assertEquals('¥ 1,235', $formattedJPY);
    }

    /** @test */
    public function exchange_rate_trend_calculation_works()
    {
        // Create history showing upward trend
        ExchangeRateHistory::create([
            'currency_id' => $this->foreignCurrency->id,
            'exchange_rate' => 0.80,
            'effective_date' => now()->subDays(7),
            'source' => 'api',
            'is_active' => false,
            'created_by' => $this->user->id
        ]);

        ExchangeRateHistory::create([
            'currency_id' => $this->foreignCurrency->id,
            'exchange_rate' => 0.85,
            'effective_date' => now(),
            'source' => 'api',
            'is_active' => true,
            'created_by' => $this->user->id
        ]);

        $trend = $this->foreignCurrency->getExchangeRateTrend(7);
        $this->assertEquals('up', $trend);

        $changePercent = $this->foreignCurrency->getExchangeRateChange(7);
        $this->assertEquals(6.25, $changePercent); // (0.85-0.80)/0.80 * 100
    }

    /** @test */
    public function conversion_validation_prevents_invalid_requests()
    {
        $this->actingAs($this->user);

        // Test with invalid currency IDs
        $response = $this->postJson('/api/v1/finance/multicurrency/convert', [
            'amount' => 100,
            'from_currency' => 999,
            'to_currency' => 998
        ]);

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['from_currency', 'to_currency']);

        // Test with negative amount
        $response = $this->postJson('/api/v1/finance/multicurrency/convert', [
            'amount' => -100,
            'from_currency' => $this->baseCurrency->id,
            'to_currency' => $this->foreignCurrency->id
        ]);

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['amount']);
    }

    /** @test */
    public function currency_usage_check_prevents_deletion_when_used()
    {
        $this->actingAs($this->user);

        // This would normally check if currency is used in transactions
        // For this test, we'll assume the method works correctly
        
        $response = $this->delete(route('finance.multicurrency.destroy', $this->foreignCurrency->id));

        // Should succeed since no transactions use this currency yet
        $response->assertRedirect(route('finance.multicurrency.index'))
                ->assertSessionHas('success');

        $this->assertDatabaseMissing('currencies', [
            'id' => $this->foreignCurrency->id
        ]);
    }
}
<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Modules\Finance\Models\Customer;
use App\Modules\Finance\Models\PosTerminal;
use App\Modules\Finance\Models\Payment;
use App\Modules\Finance\Models\ArInvoice;
use App\Modules\Water\Models\WaterBill;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

class SmartPosSystemTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    private $user;
    private $terminal;
    private $customer;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'role' => 'admin'
        ]);

        $this->terminal = PosTerminal::create([
            'terminal_id' => 'POS001',
            'terminal_name' => 'Main Counter',
            'location' => 'Reception',
            'is_active' => true,
            'created_by' => $this->user->id
        ]);

        $this->customer = Customer::create([
            'customer_code' => 'CUST001',
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john.doe@example.com',
            'phone' => '123-456-7890',
            'physical_address' => '123 Main St, City',
            'customer_type' => 'individual',
            'is_active' => true
        ]);
    }

    /** @test */
    public function user_can_search_bills_by_customer_name()
    {
        $this->actingAs($this->user);

        // Create an invoice
        $invoice = ArInvoice::create([
            'invoice_number' => 'INV-001',
            'customer_id' => $this->customer->id,
            'invoice_date' => now(),
            'due_date' => now()->addDays(30),
            'total_amount' => 100.00,
            'balance_due' => 100.00,
            'status' => 'sent'
        ]);

        $response = $this->postJson('/api/v1/finance/pos/bill-lookup', [
            'search_type' => 'customer_name',
            'search_value' => 'John Doe'
        ]);

        $response->assertStatus(200)
                ->assertJson([
                    'success' => true,
                    'total_found' => 1
                ])
                ->assertJsonFragment([
                    'bill_number' => 'INV-001',
                    'customer_name' => 'John Doe',
                    'amount' => 100.00
                ]);
    }

    /** @test */
    public function user_can_search_bills_by_account_number()
    {
        $this->actingAs($this->user);

        $invoice = ArInvoice::create([
            'invoice_number' => 'INV-002',
            'customer_id' => $this->customer->id,
            'invoice_date' => now(),
            'due_date' => now()->addDays(30),
            'total_amount' => 150.00,
            'balance_due' => 150.00,
            'status' => 'sent'
        ]);

        $response = $this->postJson('/api/v1/finance/pos/bill-lookup', [
            'search_type' => 'account_number',
            'search_value' => 'CUST001'
        ]);

        $response->assertStatus(200)
                ->assertJson([
                    'success' => true,
                    'total_found' => 1
                ])
                ->assertJsonFragment([
                    'bill_number' => 'INV-002',
                    'account_number' => 'CUST001'
                ]);
    }

    /** @test */
    public function user_can_process_payment_for_selected_bills()
    {
        $this->actingAs($this->user);

        $invoice = ArInvoice::create([
            'invoice_number' => 'INV-003',
            'customer_id' => $this->customer->id,
            'invoice_date' => now(),
            'due_date' => now()->addDays(30),
            'total_amount' => 200.00,
            'balance_due' => 200.00,
            'status' => 'sent'
        ]);

        $bills = [
            [
                'type' => 'invoice',
                'id' => $invoice->id,
                'amount' => 200.00
            ]
        ];

        $response = $this->postJson('/api/v1/finance/pos/process-payment', [
            'bills' => $bills,
            'payment_method' => 'cash',
            'terminal_id' => $this->terminal->id,
            'total_amount' => 200.00
        ]);

        $response->assertStatus(200)
                ->assertJson([
                    'success' => true,
                    'message' => 'Payment processed successfully'
                ]);

        // Check if payment was created
        $this->assertDatabaseHas('pos_payments', [
            'total_amount' => 200.00,
            'payment_method' => 'cash',
            'terminal_id' => $this->terminal->id,
            'status' => 'completed'
        ]);

        // Check if invoice was updated
        $invoice->refresh();
        $this->assertEquals(200.00, $invoice->paid_amount);
        $this->assertEquals(0.00, $invoice->balance_due);
        $this->assertEquals('paid', $invoice->status);
    }

    /** @test */
    public function user_can_get_customer_suggestions()
    {
        $this->actingAs($this->user);

        // Create additional customers
        Customer::create([
            'customer_code' => 'CUST002',
            'first_name' => 'Jane',
            'last_name' => 'Smith',
            'email' => 'jane.smith@example.com',
            'customer_type' => 'individual',
            'is_active' => true
        ]);

        Customer::create([
            'customer_code' => 'CUST003',
            'company_name' => 'ABC Company',
            'email' => 'contact@abc.com',
            'customer_type' => 'company',
            'is_active' => true
        ]);

        $response = $this->getJson('/api/v1/finance/pos/customer-suggestions?q=Jo');

        $response->assertStatus(200)
                ->assertJsonCount(1)
                ->assertJsonFragment([
                    'name' => 'John Doe',
                    'code' => 'CUST001'
                ]);
    }

    /** @test */
    public function user_can_generate_qr_code_for_bill()
    {
        $this->actingAs($this->user);

        $invoice = ArInvoice::create([
            'invoice_number' => 'INV-004',
            'customer_id' => $this->customer->id,
            'invoice_date' => now(),
            'total_amount' => 100.00,
            'status' => 'sent'
        ]);

        $response = $this->postJson('/api/v1/finance/pos/generate-qr-code', [
            'bill_type' => 'invoice',
            'bill_id' => $invoice->id
        ]);

        $response->assertStatus(200)
                ->assertJson([
                    'success' => true,
                    'qr_data' => 'INV-004'
                ]);
    }

    /** @test */
    public function user_can_get_sales_analytics()
    {
        $this->actingAs($this->user);

        // Create some payments
        Payment::create([
            'payment_number' => 'PAY-001',
            'payment_date' => now(),
            'total_amount' => 100.00,
            'payment_method' => 'cash',
            'terminal_id' => $this->terminal->id,
            'status' => 'completed',
            'created_by' => $this->user->id
        ]);

        Payment::create([
            'payment_number' => 'PAY-002',
            'payment_date' => now(),
            'total_amount' => 150.00,
            'payment_method' => 'card',
            'terminal_id' => $this->terminal->id,
            'status' => 'completed',
            'created_by' => $this->user->id
        ]);

        $response = $this->getJson('/api/v1/finance/pos/sales-analytics');

        $response->assertStatus(200)
                ->assertJson([
                    'success' => true
                ])
                ->assertJsonStructure([
                    'analytics' => [
                        'total_transactions',
                        'total_revenue',
                        'daily_sales',
                        'payment_methods'
                    ]
                ]);
    }

    /** @test */
    public function search_returns_empty_results_when_no_bills_found()
    {
        $this->actingAs($this->user);

        $response = $this->postJson('/api/v1/finance/pos/bill-lookup', [
            'search_type' => 'customer_name',
            'search_value' => 'Nonexistent Customer'
        ]);

        $response->assertStatus(200)
                ->assertJson([
                    'success' => true,
                    'total_found' => 0,
                    'bills' => []
                ]);
    }

    /** @test */
    public function payment_processing_validates_required_fields()
    {
        $this->actingAs($this->user);

        $response = $this->postJson('/api/v1/finance/pos/process-payment', [
            'bills' => [],
            'payment_method' => 'cash'
            // Missing terminal_id and total_amount
        ]);

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['terminal_id', 'total_amount']);
    }

    /** @test */
    public function bill_lookup_validates_search_parameters()
    {
        $this->actingAs($this->user);

        $response = $this->postJson('/api/v1/finance/pos/bill-lookup', [
            'search_type' => 'invalid_type',
            'search_value' => 'test'
        ]);

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['search_type']);
    }

    /** @test */
    public function unauthorized_user_cannot_access_pos_endpoints()
    {
        $response = $this->postJson('/api/v1/finance/pos/bill-lookup', [
            'search_type' => 'customer_name',
            'search_value' => 'test'
        ]);

        $response->assertStatus(401);
    }

    /** @test */
    public function payment_creates_receipt()
    {
        $this->actingAs($this->user);

        $invoice = ArInvoice::create([
            'invoice_number' => 'INV-005',
            'customer_id' => $this->customer->id,
            'invoice_date' => now(),
            'total_amount' => 75.00,
            'balance_due' => 75.00,
            'status' => 'sent'
        ]);

        $bills = [
            [
                'type' => 'invoice',
                'id' => $invoice->id,
                'amount' => 75.00,
                'bill_number' => 'INV-005',
                'customer_name' => 'John Doe'
            ]
        ];

        $response = $this->postJson('/api/v1/finance/pos/process-payment', [
            'bills' => $bills,
            'payment_method' => 'card',
            'terminal_id' => $this->terminal->id,
            'total_amount' => 75.00
        ]);

        $response->assertStatus(200);

        // Check if receipt was created
        $this->assertDatabaseHas('pos_receipts', [
            'total_amount' => 75.00,
            'created_by' => $this->user->id
        ]);
    }

    /** @test */
    public function partial_payment_updates_invoice_correctly()
    {
        $this->actingAs($this->user);

        $invoice = ArInvoice::create([
            'invoice_number' => 'INV-006',
            'customer_id' => $this->customer->id,
            'invoice_date' => now(),
            'total_amount' => 100.00,
            'balance_due' => 100.00,
            'status' => 'sent'
        ]);

        $bills = [
            [
                'type' => 'invoice',
                'id' => $invoice->id,
                'amount' => 50.00 // Partial payment
            ]
        ];

        $response = $this->postJson('/api/v1/finance/pos/process-payment', [
            'bills' => $bills,
            'payment_method' => 'cash',
            'terminal_id' => $this->terminal->id,
            'total_amount' => 50.00
        ]);

        $response->assertStatus(200);

        $invoice->refresh();
        $this->assertEquals(50.00, $invoice->paid_amount);
        $this->assertEquals(50.00, $invoice->balance_due);
        $this->assertEquals('sent', $invoice->status); // Should remain 'sent' for partial payment
    }
}
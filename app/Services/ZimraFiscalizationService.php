<?php

namespace App\Services;

use App\Models\Finance\FiscalReceipt;
use App\Models\Finance\FiscalConfiguration;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ZimraFiscalizationService
{
    protected $baseUrl;
    protected $apiKey;
    protected $timeout;

    public function __construct()
    {
        $this->baseUrl = config('services.zimra.base_url', 'https://api.zimra.co.zw/fiscal/v1');
        $this->apiKey = config('services.zimra.api_key');
        $this->timeout = config('services.zimra.timeout', 30);
    }

    public function transmitReceipt(FiscalReceipt $receipt)
    {
        try {
            $fiscalConfig = FiscalConfiguration::getFiscalConfig();
            
            if (!$fiscalConfig->isFiscalizationEnabled()) {
                throw new \Exception('Fiscalization is not enabled');
            }

            // Validate device is registered and active
            if (!$this->validateDeviceStatus($receipt->fiscalDevice)) {
                throw new \Exception('Fiscal device is not properly registered or active');
            }

            $data = $this->formatReceiptForZimra($receipt, $fiscalConfig);
            
            // Add digital signature for enhanced security
            $data['digitalSignature'] = $this->generateDigitalSignature($data, $fiscalConfig);
            
            $response = Http::timeout($this->timeout)
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $this->apiKey,
                    'Content-Type' => 'application/json',
                    'X-TIN' => $fiscalConfig->company_tin,
                    'X-Device-ID' => $receipt->fiscalDevice->device_id,
                    'X-Transaction-ID' => $receipt->fiscal_number
                ])
                ->post($this->baseUrl . '/receipts', $data);

            if ($response->successful()) {
                $responseData = $response->json();
                
                $receipt->update([
                    'compliance_status' => FiscalReceipt::STATUS_TRANSMITTED,
                    'zimra_transmitted_at' => now(),
                    'zimra_response' => $responseData,
                    'verification_code' => $responseData['verification_code'] ?? null,
                    'zimra_receipt_number' => $responseData['zimra_receipt_number'] ?? null,
                    'fiscal_day_number' => $responseData['fiscal_day_number'] ?? null
                ]);

                // Update device counters
                $receipt->fiscalDevice->increment('total_receipts_issued');

                Log::info('Receipt transmitted to ZIMRA', [
                    'receipt_id' => $receipt->id,
                    'fiscal_number' => $receipt->fiscal_number,
                    'verification_code' => $responseData['verification_code'] ?? null,
                    'zimra_receipt_number' => $responseData['zimra_receipt_number'] ?? null
                ]);

                return [
                    'success' => true,
                    'verification_code' => $responseData['verification_code'] ?? null,
                    'zimra_receipt_number' => $responseData['zimra_receipt_number'] ?? null
                ];
            } else {
                $errorData = $response->json();
                
                $receipt->update([
                    'compliance_status' => FiscalReceipt::STATUS_REJECTED,
                    'zimra_response' => $errorData,
                    'rejection_reason' => $errorData['error']['message'] ?? 'Unknown error'
                ]);

                Log::error('ZIMRA rejected receipt', [
                    'receipt_id' => $receipt->id,
                    'error_code' => $errorData['error']['code'] ?? null,
                    'error_message' => $errorData['error']['message'] ?? null,
                    'response' => $errorData
                ]);

                return [
                    'success' => false,
                    'error' => $errorData['error']['message'] ?? 'ZIMRA rejected the receipt',
                    'error_code' => $errorData['error']['code'] ?? null
                ];
            }
        } catch (\Exception $e) {
            $receipt->update([
                'compliance_status' => FiscalReceipt::STATUS_ERROR,
                'zimra_response' => ['error' => $e->getMessage()],
                'rejection_reason' => $e->getMessage()
            ]);

            Log::error('Failed to transmit receipt to ZIMRA', [
                'receipt_id' => $receipt->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    protected function formatReceiptForZimra(FiscalReceipt $receipt, FiscalConfiguration $config)
    {
        $receiptLines = [];
        $receiptTaxes = [];
        $receiptPayments = [];
        
        // Format receipt lines according to ZIMRA specification
        foreach ($receipt->receiptItems as $index => $item) {
            $receiptLines[] = [
                'receiptLineType' => $item->line_type ?? 'Sale',
                'receiptLineNo' => $index + 1,
                'receiptLineHSCode' => $item->hs_code,
                'receiptLineName' => $item->item_name,
                'receiptLinePrice' => (float) $item->unit_price,
                'receiptLineQuantity' => (float) $item->quantity,
                'receiptLineTotal' => (float) $item->line_total,
                'taxCode' => $item->tax_code,
                'taxPercent' => (float) $item->tax_rate,
                'taxID' => (int) $item->tax_id
            ];
        }

        // Group taxes by tax rate and code
        $taxGroups = [];
        foreach ($receipt->receiptItems as $item) {
            $taxKey = $item->tax_rate . '_' . ($item->tax_code ?? '');
            if (!isset($taxGroups[$taxKey])) {
                $taxGroups[$taxKey] = [
                    'taxCode' => $item->tax_code,
                    'taxPercent' => (float) $item->tax_rate,
                    'taxID' => (int) $item->tax_id,
                    'taxAmount' => 0,
                    'salesAmountWithTax' => 0
                ];
            }
            $taxGroups[$taxKey]['taxAmount'] += (float) $item->tax_amount;
            $taxGroups[$taxKey]['salesAmountWithTax'] += (float) $item->line_total;
        }

        $receiptTaxes = array_values($taxGroups);

        // Format payment methods
        $receiptPayments[] = [
            'moneyTypeCode' => $this->mapPaymentMethod($receipt->payment_method),
            'paymentAmount' => (float) $receipt->total_amount
        ];

        // Generate device signature
        $deviceSignature = $this->generateDeviceSignature($receipt, $receiptTaxes);

        return [
            'deviceID' => (int) $receipt->fiscalDevice->device_id,
            'receipt' => [
                'receiptType' => $this->mapReceiptType($receipt->receipt_type),
                'receiptCurrency' => strtoupper($receipt->currency_code),
                'receiptCounter' => (int) $receipt->receipt_counter,
                'receiptGlobalNo' => (int) $receipt->fiscal_number,
                'invoiceNo' => $receipt->receipt_number,
                'receiptDate' => $receipt->transaction_date->format('Y-m-d\TH:i:s'),
                'receiptLinesTaxInclusive' => $receipt->lines_tax_inclusive ?? true,
                'receiptLines' => $receiptLines,
                'receiptTaxes' => $receiptTaxes,
                'receiptPayments' => $receiptPayments,
                'receiptTotal' => (float) $receipt->total_amount,
                'receiptPrintForm' => 'Receipt48',
                'receiptDeviceSignature' => $deviceSignature,
                'username' => $receipt->operator->name ?? null,
                'userNameSurname' => $receipt->operator->name ?? null
            ]
        ];
    }

    protected function mapReceiptType($type)
    {
        $mapping = [
            'sale' => 'FiscalInvoice',
            'refund' => 'CreditNote',
            'void' => 'CreditNote',
            'credit_note' => 'CreditNote',
            'debit_note' => 'DebitNote'
        ];

        return $mapping[$type] ?? 'FiscalInvoice';
    }

    protected function mapPaymentMethod($method)
    {
        $mapping = [
            'cash' => 'Cash',
            'card' => 'Card',
            'mobile_money' => 'MobileWallet',
            'bank_transfer' => 'BankTransfer',
            'credit' => 'Credit',
            'coupon' => 'Coupon'
        ];

        return $mapping[$method] ?? 'Other';
    }

    protected function generateDeviceSignature(FiscalReceipt $receipt, array $receiptTaxes)
    {
        // Build signature string according to ZIMRA specification
        $signatureData = '';
        $signatureData .= $receipt->fiscalDevice->device_id;
        $signatureData .= strtoupper($this->mapReceiptType($receipt->receipt_type));
        $signatureData .= strtoupper($receipt->currency_code);
        $signatureData .= $receipt->fiscal_number;
        $signatureData .= $receipt->transaction_date->format('Y-m-d\TH:i:s');
        
        // Add total amount in cents
        $signatureData .= (int) ($receipt->total_amount * 100);
        
        // Add tax information
        foreach ($receiptTaxes as $tax) {
            $signatureData .= $tax['taxCode'] ?? '';
            $signatureData .= number_format($tax['taxPercent'], 2, '.', '');
            $signatureData .= (int) ($tax['taxAmount'] * 100);
            $signatureData .= (int) ($tax['salesAmountWithTax'] * 100);
        }

        // Generate hash and signature
        $hash = hash('sha256', $signatureData);
        $signature = base64_encode($hash); // Simplified - should use device private key

        return [
            'hash' => base64_encode(hex2bin($hash)),
            'signature' => $signature
        ];
    }

    public function validateDevice($deviceId, $serialNumber)
    {
        try {
            $response = Http::timeout($this->timeout)
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $this->apiKey,
                    'Content-Type' => 'application/json'
                ])
                ->post($this->baseUrl . '/devices/validate', [
                    'deviceId' => $deviceId,
                    'serialNumber' => $serialNumber
                ]);

            return $response->successful();
        } catch (\Exception $e) {
            Log::error('Failed to validate device with ZIMRA', [
                'device_id' => $deviceId,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    public function registerDevice(FiscalDevice $device)
    {
        try {
            $fiscalConfig = FiscalConfiguration::getFiscalConfig();
            
            $deviceData = [
                'deviceId' => $device->device_id,
                'serialNumber' => $device->serial_number,
                'manufacturer' => $device->manufacturer,
                'model' => $device->model,
                'firmwareVersion' => $device->firmware_version,
                'certificateNumber' => $device->certification_number,
                'location' => $device->location,
                'deviceType' => $device->device_type,
                'companyTin' => $fiscalConfig->company_tin,
                'taxOfficeCode' => $device->tax_office_code ?? $fiscalConfig->tax_office_code
            ];

            $response = Http::timeout($this->timeout)
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $this->apiKey,
                    'Content-Type' => 'application/json',
                    'X-TIN' => $fiscalConfig->company_tin
                ])
                ->post($this->baseUrl . '/devices/register', $deviceData);

            if ($response->successful()) {
                $responseData = $response->json();
                
                $device->update([
                    'zimra_registration_number' => $responseData['registration_number'] ?? null,
                    'status' => FiscalDevice::STATUS_ACTIVE,
                    'is_active' => true,
                    'zimra_registered_at' => now()
                ]);

                Log::info('Device registered with ZIMRA', [
                    'device_id' => $device->device_id,
                    'registration_number' => $responseData['registration_number'] ?? null
                ]);

                return [
                    'success' => true,
                    'registration_number' => $responseData['registration_number'] ?? null
                ];
            } else {
                Log::error('Failed to register device with ZIMRA', [
                    'device_id' => $device->device_id,
                    'response' => $response->json()
                ]);

                return [
                    'success' => false,
                    'error' => $response->json()['error']['message'] ?? 'Registration failed'
                ];
            }
        } catch (\Exception $e) {
            Log::error('Exception during device registration', [
                'device_id' => $device->device_id,
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    public function validateDeviceStatus(FiscalDevice $device)
    {
        return $device->is_active && 
               $device->status === FiscalDevice::STATUS_ACTIVE &&
               !empty($device->zimra_registration_number);
    }

    public function generateDigitalSignature($data, $config)
    {
        $signatureString = $data['fiscalDeviceId'] . 
                          $data['receiptNumber'] . 
                          $data['transactionDate'] . 
                          $data['totals']['totalAmount'] . 
                          $config->company_tin;
        
        return hash_hmac('sha256', $signatureString, config('app.key'));
    }

    public function getDailyReport(\DateTime $date, $deviceId = null)
    {
        try {
            $params = ['date' => $date->format('Y-m-d')];
            if ($deviceId) {
                $params['deviceId'] = $deviceId;
            }

            $response = Http::timeout($this->timeout)
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $this->apiKey,
                    'Content-Type' => 'application/json'
                ])
                ->get($this->baseUrl . '/reports/daily', $params);

            if ($response->successful()) {
                return $response->json();
            }

            return null;
        } catch (\Exception $e) {
            Log::error('Failed to get daily report from ZIMRA', [
                'date' => $date->format('Y-m-d'),
                'device_id' => $deviceId,
                'error' => $e->getMessage()
            ]);
            return null;
        }
    }

    public function getDeviceStatus($deviceId)
    {
        try {
            $response = Http::timeout($this->timeout)
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $this->apiKey,
                    'Content-Type' => 'application/json'
                ])
                ->get($this->baseUrl . "/devices/{$deviceId}/status");

            if ($response->successful()) {
                return $response->json();
            }

            return null;
        } catch (\Exception $e) {
            Log::error('Failed to get device status from ZIMRA', [
                'device_id' => $deviceId,
                'error' => $e->getMessage()
            ]);
            return null;
        }
    }

    public function submitZReport($deviceId, $reportData)
    {
        try {
            $response = Http::timeout($this->timeout)
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $this->apiKey,
                    'Content-Type' => 'application/json'
                ])
                ->post($this->baseUrl . '/reports/z-report', [
                    'deviceId' => $deviceId,
                    'reportDate' => $reportData['date'],
                    'reportData' => $reportData
                ]);

            return $response->successful() ? $response->json() : null;
        } catch (\Exception $e) {
            Log::error('Failed to submit Z-report to ZIMRA', [
                'device_id' => $deviceId,
                'error' => $e->getMessage()
            ]);
            return null;
        }
    }

    public function verifyTaxpayerInformation($deviceId, $activationKey, $deviceSerialNo)
    {
        try {
            $response = Http::timeout($this->timeout)
                ->withHeaders([
                    'Content-Type' => 'application/json',
                    'DeviceModelName' => config('services.zimra.device_model_name', 'ERP_POS'),
                    'DeviceModelVersionNo' => config('services.zimra.device_version', '1.0')
                ])
                ->post($this->baseUrl . '/verifyTaxpayerInformation', [
                    'deviceID' => (int) $deviceId,
                    'activationKey' => $activationKey,
                    'deviceSerialNo' => $deviceSerialNo
                ]);

            return $response->successful() ? $response->json() : null;
        } catch (\Exception $e) {
            Log::error('Failed to verify taxpayer information', [
                'device_id' => $deviceId,
                'error' => $e->getMessage()
            ]);
            return null;
        }
    }

    public function getConfig($deviceId)
    {
        try {
            $response = Http::timeout($this->timeout)
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $this->apiKey,
                    'Content-Type' => 'application/json',
                    'DeviceModelName' => config('services.zimra.device_model_name', 'ERP_POS'),
                    'DeviceModelVersionNo' => config('services.zimra.device_version', '1.0')
                ])
                ->post($this->baseUrl . '/getConfig', [
                    'deviceID' => (int) $deviceId
                ]);

            return $response->successful() ? $response->json() : null;
        } catch (\Exception $e) {
            Log::error('Failed to get device config from ZIMRA', [
                'device_id' => $deviceId,
                'error' => $e->getMessage()
            ]);
            return null;
        }
    }

    public function getStatus($deviceId)
    {
        try {
            $response = Http::timeout($this->timeout)
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $this->apiKey,
                    'Content-Type' => 'application/json',
                    'DeviceModelName' => config('services.zimra.device_model_name', 'ERP_POS'),
                    'DeviceModelVersionNo' => config('services.zimra.device_version', '1.0')
                ])
                ->post($this->baseUrl . '/getStatus', [
                    'deviceID' => (int) $deviceId
                ]);

            return $response->successful() ? $response->json() : null;
        } catch (\Exception $e) {
            Log::error('Failed to get fiscal day status from ZIMRA', [
                'device_id' => $deviceId,
                'error' => $e->getMessage()
            ]);
            return null;
        }
    }

    public function openDay($deviceId, $fiscalDayNo = null)
    {
        try {
            $data = [
                'deviceID' => (int) $deviceId,
                'fiscalDayOpened' => now()->format('Y-m-d\TH:i:s')
            ];

            if ($fiscalDayNo) {
                $data['fiscalDayNo'] = (int) $fiscalDayNo;
            }

            $response = Http::timeout($this->timeout)
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $this->apiKey,
                    'Content-Type' => 'application/json',
                    'DeviceModelName' => config('services.zimra.device_model_name', 'ERP_POS'),
                    'DeviceModelVersionNo' => config('services.zimra.device_version', '1.0')
                ])
                ->post($this->baseUrl . '/openDay', $data);

            return $response->successful() ? $response->json() : null;
        } catch (\Exception $e) {
            Log::error('Failed to open fiscal day with ZIMRA', [
                'device_id' => $deviceId,
                'error' => $e->getMessage()
            ]);
            return null;
        }
    }

    public function closeDay($deviceId, $fiscalDayNo, $fiscalCounters, $receiptCounter)
    {
        try {
            // Generate fiscal day signature
            $signatureData = $this->generateFiscalDaySignature($deviceId, $fiscalDayNo, $fiscalCounters);

            $response = Http::timeout($this->timeout)
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $this->apiKey,
                    'Content-Type' => 'application/json',
                    'DeviceModelName' => config('services.zimra.device_model_name', 'ERP_POS'),
                    'DeviceModelVersionNo' => config('services.zimra.device_version', '1.0')
                ])
                ->post($this->baseUrl . '/closeDay', [
                    'deviceID' => (int) $deviceId,
                    'fiscalDayNo' => (int) $fiscalDayNo,
                    'fiscalDayCounters' => $fiscalCounters,
                    'fiscalDayDeviceSignature' => $signatureData,
                    'receiptCounter' => (int) $receiptCounter
                ]);

            return $response->successful() ? $response->json() : null;
        } catch (\Exception $e) {
            Log::error('Failed to close fiscal day with ZIMRA', [
                'device_id' => $deviceId,
                'error' => $e->getMessage()
            ]);
            return null;
        }
    }

    protected function generateFiscalDaySignature($deviceId, $fiscalDayNo, $fiscalCounters)
    {
        // Build signature string for fiscal day
        $signatureData = '';
        $signatureData .= $deviceId;
        $signatureData .= $fiscalDayNo;
        $signatureData .= now()->format('Y-m-d');

        // Add counter information
        foreach ($fiscalCounters as $counter) {
            $signatureData .= strtoupper($counter['fiscalCounterType']);
            $signatureData .= strtoupper($counter['fiscalCounterCurrency']);
            $signatureData .= $counter['fiscalCounterTaxPercent'] ?? $counter['fiscalCounterMoneyType'] ?? '';
            $signatureData .= (int) ($counter['fiscalCounterValue'] * 100);
        }

        $hash = hash('sha256', $signatureData);
        $signature = base64_encode($hash); // Should use device private key

        return [
            'hash' => base64_encode(hex2bin($hash)),
            'signature' => $signature
        ];
    }

    public function ping($deviceId)
    {
        try {
            $response = Http::timeout($this->timeout)
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $this->apiKey,
                    'Content-Type' => 'application/json',
                    'DeviceModelName' => config('services.zimra.device_model_name', 'ERP_POS'),
                    'DeviceModelVersionNo' => config('services.zimra.device_version', '1.0')
                ])
                ->post($this->baseUrl . '/ping', [
                    'deviceID' => (int) $deviceId
                ]);

            return $response->successful() ? $response->json() : null;
        } catch (\Exception $e) {
            Log::error('Failed to ping ZIMRA', [
                'device_id' => $deviceId,
                'error' => $e->getMessage()
            ]);
            return null;
        }
    }
}

<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InstallController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ServiceRequestController;
use App\Http\Controllers\OfficeController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\MunicipalBillingController;
use App\Http\Controllers\UtilitiesController;

// Admin Controllers
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\DepartmentController as AdminDepartmentController;

// Council Admin Controllers
use App\Http\Controllers\CouncilAdmin\DashboardController as CouncilAdminDashboardController;
use App\Http\Controllers\CouncilAdmin\ModuleController;
use App\Http\Controllers\CouncilAdmin\UserController as CouncilAdminUserController;

// Module Controllers
use App\Modules\Administration\Controllers\CoreModulesController;
use App\Modules\Administration\Controllers\CrmController;
use App\Modules\Administration\Controllers\DepartmentController as AdminModuleDepartmentController;
use App\Modules\Administration\Controllers\OfficeController as AdminModuleOfficeController;
use App\Modules\Administration\Controllers\UserController as AdminModuleUserController;

// Finance Module Controllers
use App\Http\Controllers\Finance\FinanceController;
use App\Http\Controllers\Finance\BudgetController;
use App\Http\Controllers\Finance\MulticurrencyController;
use App\Modules\Finance\Controllers\AccountsPayableController;
use App\Modules\Finance\Controllers\AccountsReceivableController;
use App\Modules\Finance\Controllers\AdvancedReportController;
use App\Modules\Finance\Controllers\AssetController;
use App\Modules\Finance\Controllers\BankManagerController;
use App\Modules\Finance\Controllers\BankReconciliationController;
use App\Modules\Finance\Controllers\BusinessIntelligenceController;
use App\Modules\Finance\Controllers\CashManagementController;
use App\Modules\Finance\Controllers\CashbookController;
use App\Modules\Finance\Controllers\ChartOfAccountController;
use App\Modules\Finance\Controllers\CostCenterController;
use App\Modules\Finance\Controllers\DebtorsController;
use App\Modules\Finance\Controllers\FdmsReceiptController;
use App\Modules\Finance\Controllers\FiscalizationController;
use App\Modules\Finance\Controllers\FixedAssetController;
use App\Modules\Finance\Controllers\GeneralJournalController;
use App\Modules\Finance\Controllers\GeneralLedgerController;
use App\Modules\Finance\Controllers\InvoiceController;
use App\Modules\Finance\Controllers\IpsasComplianceController;
use App\Modules\Finance\Controllers\IpsasReportController;
use App\Modules\Finance\Controllers\PaymentController;
use App\Modules\Finance\Controllers\PaymentMethodController;
use App\Modules\Finance\Controllers\PayrollController;
use App\Modules\Finance\Controllers\PosController;
use App\Modules\Finance\Controllers\ProcurementController;
use App\Modules\Finance\Controllers\ProgramReportController;
use App\Modules\Finance\Controllers\ReportController;
use App\Modules\Finance\Controllers\SupplierController;
use App\Modules\Finance\Controllers\TaxManagementController;
use App\Modules\Finance\Controllers\VendorController;
use App\Modules\Finance\Controllers\VoucherController;

// Housing Module Controllers
use App\Modules\Housing\Controllers\HousingController;
use App\Modules\Housing\Controllers\AllocationController;
use App\Modules\Housing\Controllers\CessionController;
use App\Modules\Housing\Controllers\HousingApplicationController;
use App\Modules\Housing\Controllers\PropertyController as HousingPropertyController;
use App\Modules\Housing\Controllers\StandAllocationController;
use App\Modules\Housing\Controllers\StandAreaController;
use App\Modules\Housing\Controllers\StandController;
use App\Modules\Housing\Controllers\TenantController;
use App\Modules\Housing\Controllers\WaitingListController;

// Water Module Controllers
use App\Modules\Water\Controllers\WaterController;

// Health Module Controllers
use App\Modules\Health\Controllers\HealthController;

// Committee Module Controllers
use App\Modules\Committee\Controllers\CommitteeController;

// Engineering Module Controllers
use App\Modules\Engineering\Controllers\EngineeringController;

// HR Module Controllers
use App\Modules\HR\Controllers\HrController;
use App\Modules\HR\Controllers\CurrencyRateController;
use App\Modules\HR\Controllers\PayrollController as HRPayrollController;

// Inventory Module Controllers
use App\Modules\Inventory\Controllers\InventoryController;

// Licensing Module Controllers
use App\Modules\Licensing\Controllers\LicensingController;
use App\Modules\Licensing\Controllers\OperatingLicenseController;

// Markets Module Controllers
use App\Modules\Markets\Controllers\StallController;

// Parking Module Controllers
use App\Modules\Parking\Controllers\ParkingController;

// Property Module Controllers
use App\Modules\Property\Controllers\PropertyController;
use App\Modules\Property\Controllers\PropertyManagementController;

// Survey Module Controllers
use App\Modules\Survey\Controllers\SurveyController;

// Utilities Module Controllers
use App\Modules\Utilities\Controllers\UtilitiesController as ModuleUtilitiesController;

// Events Controllers
use App\Http\Controllers\Events\EventPermitController;

// Cemetery Controllers
use App\Http\Controllers\Cemeteries\CemeteryController;

// Planning Controllers
use App\Http\Controllers\Planning\PlanningController;

// Property Tax Controllers
use App\Http\Controllers\PropertyTax\PropertyTaxController;
use App\Http\Controllers\PropertyTax\PropertyTaxationController;

// Facilities Controllers
use App\Http\Controllers\Facilities\FacilitiesController;
use App\Http\Controllers\Facilities\BookingController;

// Public Services Controllers
use App\Http\Controllers\PublicServices\PublicServicesController;

// Billing Controllers
use App\Http\Controllers\Billing\MunicipalBillingController as BillingController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Installation routes
Route::prefix('install')->group(function () {
    Route::get('/', [InstallController::class, 'index'])->name('install.index');
    Route::get('/step1', [InstallController::class, 'step1'])->name('install.step1');
    Route::post('/step1', [InstallController::class, 'processStep1'])->name('install.process-step1');
    Route::get('/step2', [InstallController::class, 'step2'])->name('install.step2');
    Route::post('/step2', [InstallController::class, 'processStep2'])->name('install.process-step2');
    Route::get('/step3', [InstallController::class, 'step3'])->name('install.step3');
    Route::post('/step3', [InstallController::class, 'processStep3'])->name('install.process-step3');
    Route::get('/complete', [InstallController::class, 'complete'])->name('install.complete');
});

// Authentication Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

// Admin Login
Route::get('/admin/login', [LoginController::class, 'showAdminLogin'])->name('admin.login');
Route::post('/admin/login', [LoginController::class, 'adminLogin'])->name('admin.login.submit');

// Root route
Route::get('/', [DashboardController::class, 'index'])->name('home');

// Dashboard
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// Protected routes
Route::middleware(['auth'])->group(function () {
    
    // Main dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Customer Management
    Route::resource('customers', CustomerController::class);
    
    // Service Requests
    Route::resource('service-requests', ServiceRequestController::class);
    Route::get('/service-requests/{serviceRequest}/attachments', [ServiceRequestController::class, 'attachments'])->name('service-requests.attachments');
    Route::post('/service-requests/{serviceRequest}/attachments', [ServiceRequestController::class, 'storeAttachment'])->name('service-requests.store-attachment');
    
    // Offices
    Route::resource('offices', OfficeController::class);
    
    // Reports
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [ReportsController::class, 'index'])->name('index');
        Route::get('/analytics', [ReportsController::class, 'analytics'])->name('analytics');
        Route::get('/service', [ReportsController::class, 'service'])->name('service');
        Route::get('/revenue', [ReportsController::class, 'revenue'])->name('revenue');
        Route::get('/operational', [ReportsController::class, 'operational'])->name('operational');
        Route::get('/compliance', [ReportsController::class, 'compliance'])->name('compliance');
        Route::get('/custom', [ReportsController::class, 'custom'])->name('custom');
        Route::get('/performance-metrics', [ReportsController::class, 'performanceMetrics'])->name('performance-metrics');
        Route::get('/revenue-analysis', [ReportsController::class, 'revenueAnalysis'])->name('revenue-analysis');
    });
    
    // Municipal Billing
    Route::prefix('billing')->name('billing.')->group(function () {
        Route::get('/', [BillingController::class, 'index'])->name('index');
        Route::get('/accounts', [BillingController::class, 'accounts'])->name('accounts');
        Route::get('/bills', [BillingController::class, 'bills'])->name('bills');
        Route::get('/bills/create', [BillingController::class, 'createBill'])->name('bills.create');
        Route::post('/bills', [BillingController::class, 'storeBill'])->name('bills.store');
        Route::get('/customers', [BillingController::class, 'customers'])->name('customers');
        Route::get('/customers/create', [BillingController::class, 'createCustomer'])->name('customers.create');
        Route::post('/customers', [BillingController::class, 'storeCustomer'])->name('customers.store');
        Route::get('/payments', [BillingController::class, 'payments'])->name('payments');
        Route::get('/payments/create', [BillingController::class, 'createPayment'])->name('payments.create');
        Route::post('/payments', [BillingController::class, 'storePayment'])->name('payments.store');
        Route::get('/services', [BillingController::class, 'services'])->name('services');
        Route::get('/reports', [BillingController::class, 'reports'])->name('reports');
    });
    
    // Utilities
    Route::prefix('utilities')->name('utilities.')->group(function () {
        Route::get('/', [UtilitiesController::class, 'index'])->name('index');
        Route::get('/electricity', [UtilitiesController::class, 'electricity'])->name('electricity');
        Route::get('/electricity/connections', [UtilitiesController::class, 'electricityConnections'])->name('electricity.connections');
        Route::get('/electricity/meters', [UtilitiesController::class, 'electricityMeters'])->name('electricity.meters');
        Route::get('/gas', [UtilitiesController::class, 'gas'])->name('gas');
        Route::get('/fleet', [UtilitiesController::class, 'fleet'])->name('fleet');
        Route::get('/infrastructure', [UtilitiesController::class, 'infrastructure'])->name('infrastructure');
        Route::get('/waste', [UtilitiesController::class, 'waste'])->name('waste');
    });
    
    // Events
    Route::prefix('events')->name('events.')->group(function () {
        Route::get('/', [EventPermitController::class, 'index'])->name('index');
        Route::get('/permits', [EventPermitController::class, 'permits'])->name('permits.index');
        Route::get('/permits/create', [EventPermitController::class, 'createPermit'])->name('permits.create');
        Route::post('/permits', [EventPermitController::class, 'storePermit'])->name('permits.store');
        Route::get('/permits/{permit}', [EventPermitController::class, 'showPermit'])->name('permits.show');
        Route::get('/permits/{permit}/edit', [EventPermitController::class, 'editPermit'])->name('permits.edit');
        Route::put('/permits/{permit}', [EventPermitController::class, 'updatePermit'])->name('permits.update');
        Route::delete('/permits/{permit}', [EventPermitController::class, 'destroyPermit'])->name('permits.destroy');
        Route::get('/permits/expired', [EventPermitController::class, 'expired'])->name('permits.expired');
        Route::get('/permits/rejected', [EventPermitController::class, 'rejected'])->name('permits.rejected');
        Route::get('/search', [EventPermitController::class, 'search'])->name('search');
        Route::get('/categories', [EventPermitController::class, 'categories'])->name('categories');
    });
    
    // Cemeteries
    Route::prefix('cemeteries')->name('cemeteries.')->group(function () {
        Route::get('/', [CemeteryController::class, 'index'])->name('index');
        Route::get('/plots', [CemeteryController::class, 'plots'])->name('plots');
        Route::get('/plots/create', [CemeteryController::class, 'createPlot'])->name('plots.create');
        Route::post('/plots', [CemeteryController::class, 'storePlot'])->name('plots.store');
        Route::get('/burials', [CemeteryController::class, 'burials'])->name('burials');
        Route::get('/burials/create', [CemeteryController::class, 'createBurial'])->name('burials.create');
        Route::post('/burials', [CemeteryController::class, 'storeBurial'])->name('burials.store');
        Route::get('/maintenance', [CemeteryController::class, 'maintenance'])->name('maintenance');
        Route::get('/maintenance/create', [CemeteryController::class, 'createMaintenance'])->name('maintenance.create');
        Route::post('/maintenance', [CemeteryController::class, 'storeMaintenance'])->name('maintenance.store');
        Route::get('/grave-register', [CemeteryController::class, 'graveRegister'])->name('grave-register');
    });
    
    // Planning
    Route::prefix('planning')->name('planning.')->group(function () {
        Route::get('/', [PlanningController::class, 'index'])->name('index');
        Route::get('/applications', [PlanningController::class, 'applications'])->name('applications');
        Route::get('/applications/create', [PlanningController::class, 'createApplication'])->name('applications.create');
        Route::post('/applications', [PlanningController::class, 'storeApplication'])->name('applications.store');
        Route::get('/applications/{application}', [PlanningController::class, 'showApplication'])->name('applications.show');
        Route::get('/applications/{application}/edit', [PlanningController::class, 'editApplication'])->name('applications.edit');
        Route::put('/applications/{application}', [PlanningController::class, 'updateApplication'])->name('applications.update');
        Route::delete('/applications/{application}', [PlanningController::class, 'destroyApplication'])->name('applications.destroy');
        Route::get('/approvals', [PlanningController::class, 'approvals'])->name('approvals');
        Route::get('/zoning', [PlanningController::class, 'zoning'])->name('zoning');
        Route::get('/zoning/create', [PlanningController::class, 'createZoning'])->name('zoning.create');
        Route::post('/zoning', [PlanningController::class, 'storeZoning'])->name('zoning.store');
    });
    
    // Property Tax
    Route::prefix('property-tax')->name('property-tax.')->group(function () {
        Route::get('/', [PropertyTaxController::class, 'index'])->name('index');
        Route::get('/assessments', [PropertyTaxController::class, 'assessments'])->name('assessments');
        Route::get('/assessments/create', [PropertyTaxController::class, 'createAssessment'])->name('assessments.create');
        Route::post('/assessments', [PropertyTaxController::class, 'storeAssessment'])->name('assessments.store');
        Route::get('/valuations', [PropertyTaxController::class, 'valuations'])->name('valuations');
    });
    
    // Facilities
    Route::prefix('facilities')->name('facilities.')->group(function () {
        Route::get('/', [FacilitiesController::class, 'index'])->name('index');
        Route::get('/create', [FacilitiesController::class, 'create'])->name('create');
        Route::post('/', [FacilitiesController::class, 'store'])->name('store');
        Route::get('/{facility}', [FacilitiesController::class, 'show'])->name('show');
        Route::get('/{facility}/edit', [FacilitiesController::class, 'edit'])->name('edit');
        Route::put('/{facility}', [FacilitiesController::class, 'update'])->name('update');
        Route::delete('/{facility}', [FacilitiesController::class, 'destroy'])->name('destroy');
        
        // Bookings
        Route::prefix('bookings')->name('bookings.')->group(function () {
            Route::get('/', [BookingController::class, 'index'])->name('index');
            Route::get('/create', [BookingController::class, 'create'])->name('create');
            Route::post('/', [BookingController::class, 'store'])->name('store');
            Route::get('/{booking}', [BookingController::class, 'show'])->name('show');
            Route::get('/{booking}/edit', [BookingController::class, 'edit'])->name('edit');
            Route::put('/{booking}', [BookingController::class, 'update'])->name('update');
            Route::delete('/{booking}', [BookingController::class, 'destroy'])->name('destroy');
            Route::get('/pools', [BookingController::class, 'pools'])->name('pools');
        });
    });
    
    // Public Services
    Route::prefix('public-services')->name('public-services.')->group(function () {
        Route::get('/', [PublicServicesController::class, 'index'])->name('index');
        Route::get('/requests', [PublicServicesController::class, 'requests'])->name('requests');
        Route::get('/requests/create', [PublicServicesController::class, 'createRequest'])->name('requests.create');
        Route::post('/requests', [PublicServicesController::class, 'storeRequest'])->name('requests.store');
        Route::get('/requests/{request}', [PublicServicesController::class, 'showRequest'])->name('requests.show');
    });
});

// Admin Routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    
    // Users
    Route::resource('users', AdminUserController::class);
    
    // Departments
    Route::resource('departments', AdminDepartmentController::class);
    
    // Offices
    Route::resource('offices', OfficeController::class);
});

// Council Admin Routes
Route::middleware(['auth', 'admin'])->prefix('council-admin')->name('council-admin.')->group(function () {
    
    // Dashboard
    Route::get('/', [CouncilAdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [CouncilAdminDashboardController::class, 'index'])->name('dashboard.index');
    Route::post('/dashboard/clear-cache', [CouncilAdminDashboardController::class, 'clearCache'])->name('dashboard.clear-cache');
    
    // Modules
    Route::resource('modules', ModuleController::class);
    Route::post('/modules/{module}/toggle', [ModuleController::class, 'toggle'])->name('modules.toggle');
    
    // Users
    Route::get('/users', [CouncilAdminUserController::class, 'index'])->name('users.index');
    
    // Settings
    Route::get('/settings', [ModuleController::class, 'settings'])->name('settings');
    Route::get('/settings/modules', [ModuleController::class, 'settingsModules'])->name('settings.modules');
    Route::get('/settings/security', [ModuleController::class, 'settingsSecurity'])->name('settings.security');
    
    // System
    Route::get('/system', [CouncilAdminDashboardController::class, 'system'])->name('system');
    
    // Reports
    Route::get('/reports', [CouncilAdminDashboardController::class, 'reports'])->name('reports');
    Route::get('/reports/audit-logs', [CouncilAdminDashboardController::class, 'auditLogs'])->name('reports.audit-logs');
});

// Administration Module Routes
Route::middleware(['auth', 'admin'])->prefix('administration')->name('administration.')->group(function () {
    
    // Main Administration Index
    Route::get('/', [CoreModulesController::class, 'index'])->name('index');
    
    // Core Modules
    Route::get('/core-modules', [CoreModulesController::class, 'index'])->name('core-modules.index');
    
    // CRM
    Route::prefix('crm')->name('crm.')->group(function () {
        Route::get('/', [CrmController::class, 'index'])->name('index');
        Route::get('/customers', [CrmController::class, 'customers'])->name('customers');
        Route::get('/customers/create', [CrmController::class, 'createCustomer'])->name('customers.create');
        Route::post('/customers', [CrmController::class, 'storeCustomer'])->name('customers.store');
        Route::get('/customers/{customer}', [CrmController::class, 'showCustomer'])->name('customers.show');
        Route::get('/customers/{customer}/edit', [CrmController::class, 'editCustomer'])->name('customers.edit');
        Route::put('/customers/{customer}', [CrmController::class, 'updateCustomer'])->name('customers.update');
        Route::delete('/customers/{customer}', [CrmController::class, 'destroyCustomer'])->name('customers.destroy');
        Route::get('/service-requests', [CrmController::class, 'serviceRequests'])->name('service-requests');
    });
    
    // Departments
    Route::resource('departments', AdminModuleDepartmentController::class);
    
    // Offices
    Route::resource('offices', AdminModuleOfficeController::class);
    
    // Users
    Route::resource('users', AdminModuleUserController::class);
});

// Finance Module Routes
Route::middleware(['auth'])->prefix('finance')->name('finance.')->group(function () {
    
    // Main Finance Routes
    Route::get('/', [FinanceController::class, 'index'])->name('index');
    Route::get('/create-invoice', [FinanceController::class, 'createInvoice'])->name('create-invoice');
    Route::get('/customers', [FinanceController::class, 'customers'])->name('customers');
    Route::get('/invoices', [FinanceController::class, 'invoices'])->name('invoices');
    Route::get('/payments', [FinanceController::class, 'payments'])->name('payments');
    Route::get('/receivables', [FinanceController::class, 'receivables'])->name('receivables');
    Route::get('/reports', [FinanceController::class, 'reports'])->name('reports');
    Route::get('/show-invoice', [FinanceController::class, 'showInvoice'])->name('show-invoice');
    
    // Accounts Payable
    Route::prefix('accounts-payable')->name('accounts-payable.')->group(function () {
        Route::get('/', [AccountsPayableController::class, 'index'])->name('index');
        Route::get('/bills', [AccountsPayableController::class, 'bills'])->name('bills');
        Route::get('/bills/create', [AccountsPayableController::class, 'createBill'])->name('bills.create');
        Route::post('/bills', [AccountsPayableController::class, 'storeBill'])->name('bills.store');
        Route::get('/suppliers', [AccountsPayableController::class, 'suppliers'])->name('suppliers');
        Route::get('/suppliers/create', [AccountsPayableController::class, 'createSupplier'])->name('suppliers.create');
        Route::post('/suppliers', [AccountsPayableController::class, 'storeSupplier'])->name('suppliers.store');
    });
    
    // Accounts Receivable
    Route::prefix('accounts-receivable')->name('accounts-receivable.')->group(function () {
        Route::get('/', [AccountsReceivableController::class, 'index'])->name('index');
        Route::get('/create-invoice', [AccountsReceivableController::class, 'createInvoice'])->name('create-invoice');
        Route::get('/customers', [AccountsReceivableController::class, 'customers'])->name('customers');
        Route::get('/customers/create', [AccountsReceivableController::class, 'createCustomer'])->name('customers.create');
        Route::post('/customers', [AccountsReceivableController::class, 'storeCustomer'])->name('customers.store');
        Route::get('/customers/{customer}', [AccountsReceivableController::class, 'showCustomer'])->name('customers.show');
        Route::get('/customers/{customer}/edit', [AccountsReceivableController::class, 'editCustomer'])->name('customers.edit');
        Route::put('/customers/{customer}', [AccountsReceivableController::class, 'updateCustomer'])->name('customers.update');
        Route::get('/invoices', [AccountsReceivableController::class, 'invoices'])->name('invoices');
        Route::get('/invoices/create', [AccountsReceivableController::class, 'createInvoice'])->name('invoices.create');
        Route::post('/invoices', [AccountsReceivableController::class, 'storeInvoice'])->name('invoices.store');
        Route::get('/payments', [AccountsReceivableController::class, 'payments'])->name('payments');
        Route::get('/receipts', [AccountsReceivableController::class, 'receipts'])->name('receipts');
        Route::get('/receipts/create', [AccountsReceivableController::class, 'createReceipt'])->name('receipts.create');
        Route::post('/receipts', [AccountsReceivableController::class, 'storeReceipt'])->name('receipts.store');
        Route::get('/receipts/{receipt}', [AccountsReceivableController::class, 'showReceipt'])->name('receipts.show');
        Route::get('/receipts/{receipt}/edit', [AccountsReceivableController::class, 'editReceipt'])->name('receipts.edit');
        Route::put('/receipts/{receipt}', [AccountsReceivableController::class, 'updateReceipt'])->name('receipts.update');
    });
    
    // Assets
    Route::prefix('assets')->name('assets.')->group(function () {
        Route::get('/', [AssetController::class, 'index'])->name('index');
        Route::get('/create', [AssetController::class, 'create'])->name('create');
        Route::post('/', [AssetController::class, 'store'])->name('store');
    });
    
    // Bank Manager
    Route::prefix('bank-manager')->name('bank-manager.')->group(function () {
        Route::get('/', [BankManagerController::class, 'index'])->name('index');
        Route::get('/reconciliation', [BankManagerController::class, 'reconciliation'])->name('reconciliation');
        Route::get('/{account}', [BankManagerController::class, 'show'])->name('show');
        Route::get('/statements', [BankManagerController::class, 'statements'])->name('statements');
        Route::get('/transfer', [BankManagerController::class, 'transfer'])->name('transfer');
    });
    
    // Bank Reconciliation
    Route::prefix('bank-reconciliation')->name('bank-reconciliation.')->group(function () {
        Route::get('/', [BankReconciliationController::class, 'index'])->name('index');
        Route::get('/create', [BankReconciliationController::class, 'create'])->name('create');
        Route::post('/', [BankReconciliationController::class, 'store'])->name('store');
        Route::get('/{reconciliation}', [BankReconciliationController::class, 'show'])->name('show');
        Route::get('/{reconciliation}/edit', [BankReconciliationController::class, 'edit'])->name('edit');
        Route::put('/{reconciliation}', [BankReconciliationController::class, 'update'])->name('update');
    });
    
    // Budgets
    Route::prefix('budgets')->name('budgets.')->group(function () {
        Route::get('/', [BudgetController::class, 'index'])->name('index');
        Route::get('/create', [BudgetController::class, 'create'])->name('create');
        Route::post('/', [BudgetController::class, 'store'])->name('store');
        Route::get('/{budget}', [BudgetController::class, 'show'])->name('show');
        Route::get('/{budget}/edit', [BudgetController::class, 'edit'])->name('edit');
        Route::put('/{budget}', [BudgetController::class, 'update'])->name('update');
        Route::delete('/{budget}', [BudgetController::class, 'destroy'])->name('destroy');
        Route::post('/{budget}/activate', [BudgetController::class, 'activate'])->name('activate');
        Route::get('/variance', [BudgetController::class, 'variance'])->name('variance');
    });
    
    // Business Intelligence
    Route::prefix('business-intelligence')->name('business-intelligence.')->group(function () {
        Route::get('/', [BusinessIntelligenceController::class, 'index'])->name('index');
        Route::get('/dashboard', [BusinessIntelligenceController::class, 'dashboard'])->name('dashboard');
        Route::get('/analytics', [BusinessIntelligenceController::class, 'analytics'])->name('analytics');
        Route::get('/kpi-reports', [BusinessIntelligenceController::class, 'kpiReports'])->name('kpi-reports');
    });
    
    // Cash Management
    Route::prefix('cash-management')->name('cash-management.')->group(function () {
        Route::get('/', [CashManagementController::class, 'index'])->name('index');
        Route::get('/bank-accounts', [CashManagementController::class, 'bankAccounts'])->name('bank-accounts');
        Route::get('/bank-accounts/create', [CashManagementController::class, 'createBankAccount'])->name('bank-accounts.create');
        Route::post('/bank-accounts', [CashManagementController::class, 'storeBankAccount'])->name('bank-accounts.store');
        Route::get('/cash-position', [CashManagementController::class, 'cashPosition'])->name('cash-position');
        Route::get('/create-cash-payment', [CashManagementController::class, 'createCashPayment'])->name('create-cash-payment');
        Route::get('/create-cash-receipt', [CashManagementController::class, 'createCashReceipt'])->name('create-cash-receipt');
    });
    
    // Cashbook
    Route::prefix('cashbook')->name('cashbook.')->group(function () {
        Route::get('/', [CashbookController::class, 'index'])->name('index');
        Route::get('/create', [CashbookController::class, 'create'])->name('create');
        Route::post('/', [CashbookController::class, 'store'])->name('store');
        Route::get('/{cashbook}', [CashbookController::class, 'show'])->name('show');
        Route::get('/reports', [CashbookController::class, 'reports'])->name('reports');
    });
    
    // Chart of Accounts
    Route::prefix('chart-of-accounts')->name('chart-of-accounts.')->group(function () {
        Route::get('/', [ChartOfAccountController::class, 'index'])->name('index');
        Route::get('/create', [ChartOfAccountController::class, 'create'])->name('create');
        Route::post('/', [ChartOfAccountController::class, 'store'])->name('store');
    });
    
    // Cost Centers
    Route::prefix('cost-centers')->name('cost-centers.')->group(function () {
        Route::get('/', [CostCenterController::class, 'index'])->name('index');
        Route::get('/create', [CostCenterController::class, 'create'])->name('create');
        Route::post('/', [CostCenterController::class, 'store'])->name('store');
        Route::get('/{costCenter}', [CostCenterController::class, 'show'])->name('show');
        Route::get('/{costCenter}/edit', [CostCenterController::class, 'edit'])->name('edit');
        Route::put('/{costCenter}', [CostCenterController::class, 'update'])->name('update');
    });
    
    // Debtors
    Route::prefix('debtors')->name('debtors.')->group(function () {
        Route::get('/', [DebtorsController::class, 'index'])->name('index');
        Route::get('/aging-report', [DebtorsController::class, 'agingReport'])->name('aging-report');
        Route::get('/statements', [DebtorsController::class, 'statements'])->name('statements');
    });
    
    // FDMS
    Route::prefix('fdms')->name('fdms.')->group(function () {
        Route::get('/', [FdmsReceiptController::class, 'index'])->name('index');
        Route::get('/create', [FdmsReceiptController::class, 'create'])->name('create');
        Route::post('/', [FdmsReceiptController::class, 'store'])->name('store');
        Route::get('/{receipt}', [FdmsReceiptController::class, 'show'])->name('show');
        Route::get('/{receipt}/print', [FdmsReceiptController::class, 'print'])->name('print');
    });
    
    // Fiscalization
    Route::prefix('fiscalization')->name('fiscalization.')->group(function () {
        Route::get('/', [FiscalizationController::class, 'index'])->name('index');
        Route::get('/configuration', [FiscalizationController::class, 'configuration'])->name('configuration');
        Route::get('/devices', [FiscalizationController::class, 'devices'])->name('devices');
        Route::get('/devices/create', [FiscalizationController::class, 'createDevice'])->name('devices.create');
        Route::post('/devices', [FiscalizationController::class, 'storeDevice'])->name('devices.store');
    });
    
    // Fixed Assets
    Route::prefix('fixed-assets')->name('fixed-assets.')->group(function () {
        Route::get('/', [FixedAssetController::class, 'index'])->name('index');
        Route::get('/create', [FixedAssetController::class, 'create'])->name('create');
        Route::post('/', [FixedAssetController::class, 'store'])->name('store');
        Route::get('/{asset}', [FixedAssetController::class, 'show'])->name('show');
        Route::get('/{asset}/edit', [FixedAssetController::class, 'edit'])->name('edit');
        Route::put('/{asset}', [FixedAssetController::class, 'update'])->name('update');
        Route::get('/depreciation', [FixedAssetController::class, 'depreciation'])->name('depreciation');
        Route::get('/register', [FixedAssetController::class, 'register'])->name('register');
    });
    
    // General Journals
    Route::prefix('general-journals')->name('general-journals.')->group(function () {
        Route::get('/', [GeneralJournalController::class, 'index'])->name('index');
        Route::get('/create', [GeneralJournalController::class, 'create'])->name('create');
        Route::post('/', [GeneralJournalController::class, 'store'])->name('store');
    });
    
    // General Ledger
    Route::prefix('general-ledger')->name('general-ledger.')->group(function () {
        Route::get('/', [GeneralLedgerController::class, 'index'])->name('index');
        Route::get('/create', [GeneralLedgerController::class, 'create'])->name('create');
        Route::post('/', [GeneralLedgerController::class, 'store'])->name('store');
    });
    
    // Invoices
    Route::prefix('invoices')->name('invoices.')->group(function () {
        Route::get('/', [InvoiceController::class, 'index'])->name('index');
        Route::get('/create', [InvoiceController::class, 'create'])->name('create');
        Route::post('/', [InvoiceController::class, 'store'])->name('store');
        Route::get('/{invoice}', [InvoiceController::class, 'show'])->name('show');
        Route::get('/{invoice}/edit', [InvoiceController::class, 'edit'])->name('edit');
        Route::put('/{invoice}', [InvoiceController::class, 'update'])->name('update');
        Route::delete('/{invoice}', [InvoiceController::class, 'destroy'])->name('destroy');
        Route::post('/{invoice}/send', [InvoiceController::class, 'send'])->name('send');
        Route::get('/{invoice}/print', [InvoiceController::class, 'print'])->name('print');
        Route::get('/{invoice}/pdf', [InvoiceController::class, 'pdf'])->name('pdf');
    });
    
    // IPSAS
    Route::prefix('ipsas')->name('ipsas.')->group(function () {
        Route::get('/', [IpsasComplianceController::class, 'index'])->name('index');
    });
    
    // IPSAS Reports
    Route::prefix('ipsas-reports')->name('ipsas-reports.')->group(function () {
        Route::get('/', [IpsasReportController::class, 'index'])->name('index');
        Route::get('/cash-flow-statement', [IpsasReportController::class, 'cashFlowStatement'])->name('cash-flow-statement');
        Route::get('/statement-changes-net-assets', [IpsasReportController::class, 'statementChangesNetAssets'])->name('statement-changes-net-assets');
        Route::get('/statement-financial-performance', [IpsasReportController::class, 'statementFinancialPerformance'])->name('statement-financial-performance');
        Route::get('/statement-financial-position', [IpsasReportController::class, 'statementFinancialPosition'])->name('statement-financial-position');
    });
    
    // Multicurrency
    Route::prefix('multicurrency')->name('multicurrency.')->group(function () {
        Route::get('/', [MulticurrencyController::class, 'index'])->name('index');
        Route::get('/create', [MulticurrencyController::class, 'create'])->name('create');
        Route::post('/', [MulticurrencyController::class, 'store'])->name('store');
        Route::get('/{currency}', [MulticurrencyController::class, 'show'])->name('show');
        Route::get('/{currency}/edit', [MulticurrencyController::class, 'edit'])->name('edit');
        Route::put('/{currency}', [MulticurrencyController::class, 'update'])->name('update');
        Route::get('/conversion', [MulticurrencyController::class, 'conversion'])->name('conversion');
        Route::get('/converter', [MulticurrencyController::class, 'converter'])->name('converter');
        Route::get('/rates', [MulticurrencyController::class, 'rates'])->name('rates');
    });
    
    // Payables
    Route::prefix('payables')->name('payables.')->group(function () {
        Route::get('/', [AccountsPayableController::class, 'payables'])->name('index');
        Route::get('/bills', [AccountsPayableController::class, 'payablesBills'])->name('bills');
        Route::get('/bills/create', [AccountsPayableController::class, 'createPayableBill'])->name('bills.create');
        Route::get('/payments', [AccountsPayableController::class, 'payablesPayments'])->name('payments');
        Route::get('/payments/create', [AccountsPayableController::class, 'createPayablesPayment'])->name('payments.create');
        Route::get('/vendors', [AccountsPayableController::class, 'payablesVendors'])->name('vendors');
        Route::get('/vendors/create', [AccountsPayableController::class, 'createPayablesVendor'])->name('vendors.create');
    });
    
    // Payments
    Route::prefix('payments')->name('payments.')->group(function () {
        Route::get('/', [PaymentController::class, 'index'])->name('index');
        Route::get('/create', [PaymentController::class, 'create'])->name('create');
        Route::post('/', [PaymentController::class, 'store'])->name('store');
    });
    
    // POS
    Route::prefix('pos')->name('pos.')->group(function () {
        Route::get('/', [PosController::class, 'index'])->name('index');
        Route::get('/daily-report', [PosController::class, 'dailyReport'])->name('daily-report');
        Route::get('/receipt', [PosController::class, 'receipt'])->name('receipt');
    });
    
    // Procurement
    Route::prefix('procurement')->name('procurement.')->group(function () {
        Route::get('/', [ProcurementController::class, 'index'])->name('index');
        Route::get('/create', [ProcurementController::class, 'create'])->name('create');
        Route::post('/', [ProcurementController::class, 'store'])->name('store');
        Route::get('/{procurement}', [ProcurementController::class, 'show'])->name('show');
        Route::get('/suppliers', [ProcurementController::class, 'suppliers'])->name('suppliers');
        Route::get('/suppliers/create', [ProcurementController::class, 'createSupplier'])->name('suppliers.create');
        Route::post('/suppliers', [ProcurementController::class, 'storeSupplier'])->name('suppliers.store');
        Route::get('/suppliers/{supplier}', [ProcurementController::class, 'showSupplier'])->name('suppliers.show');
        Route::get('/suppliers/{supplier}/edit', [ProcurementController::class, 'editSupplier'])->name('suppliers.edit');
        Route::put('/suppliers/{supplier}', [ProcurementController::class, 'updateSupplier'])->name('suppliers.update');
        Route::get('/reports', [ProcurementController::class, 'reports'])->name('reports');
    });
    
    // Program Reports
    Route::prefix('program-reports')->name('program-reports.')->group(function () {
        Route::get('/', [ProgramReportController::class, 'index'])->name('index');
        Route::get('/budget-execution', [ProgramReportController::class, 'budgetExecution'])->name('budget-execution');
        Route::get('/program-performance', [ProgramReportController::class, 'programPerformance'])->name('program-performance');
    });
    
    // Reports
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [ReportController::class, 'index'])->name('index');
        Route::get('/create', [ReportController::class, 'create'])->name('create');
        Route::post('/generate', [ReportController::class, 'generate'])->name('generate');
        Route::get('/{report}', [ReportController::class, 'show'])->name('show');
        Route::get('/trial-balance', [ReportController::class, 'trialBalance'])->name('trial-balance');
        Route::get('/balance-sheet', [ReportController::class, 'balanceSheet'])->name('balance-sheet');
        Route::get('/income-statement', [ReportController::class, 'incomeStatement'])->name('income-statement');
        Route::get('/cash-flow', [ReportController::class, 'cashFlow'])->name('cash-flow');
        Route::get('/budget-variance', [ReportController::class, 'budgetVariance'])->name('budget-variance');
        Route::get('/revenue', [ReportController::class, 'revenue'])->name('revenue');
        Route::get('/advanced', [ReportController::class, 'advanced'])->name('advanced');
    });
    
    // Tax Management
    Route::prefix('tax-management')->name('tax-management.')->group(function () {
        Route::get('/', [TaxManagementController::class, 'index'])->name('index');
        Route::get('/rates', [TaxManagementController::class, 'rates'])->name('rates');
        Route::get('/rates/create', [TaxManagementController::class, 'createRate'])->name('rates.create');
        Route::post('/rates', [TaxManagementController::class, 'storeRate'])->name('rates.store');
    });
    
    // Vouchers
    Route::prefix('vouchers')->name('vouchers.')->group(function () {
        Route::get('/', [VoucherController::class, 'index'])->name('index');
        Route::get('/create', [VoucherController::class, 'create'])->name('create');
        Route::post('/', [VoucherController::class, 'store'])->name('store');
        Route::get('/{voucher}', [VoucherController::class, 'show'])->name('show');
        Route::get('/{voucher}/edit', [VoucherController::class, 'edit'])->name('edit');
        Route::put('/{voucher}', [VoucherController::class, 'update'])->name('update');
        Route::get('/reports', [VoucherController::class, 'reports'])->name('reports');
    });
});

// Housing Module Routes
Route::middleware(['auth'])->prefix('housing')->name('housing.')->group(function () {
    Route::get('/', [HousingController::class, 'index'])->name('index');
    
    // Allocations
    Route::prefix('allocations')->name('allocations.')->group(function () {
        Route::get('/', [AllocationController::class, 'index'])->name('index');
        Route::get('/create', [AllocationController::class, 'create'])->name('create');
        Route::post('/', [AllocationController::class, 'store'])->name('store');
        Route::get('/{allocation}', [AllocationController::class, 'show'])->name('show');
        Route::get('/{allocation}/edit', [AllocationController::class, 'edit'])->name('edit');
        Route::put('/{allocation}', [AllocationController::class, 'update'])->name('update');
    });
    
    // Applications
    Route::prefix('applications')->name('applications.')->group(function () {
        Route::get('/', [HousingApplicationController::class, 'index'])->name('index');
        Route::get('/create', [HousingApplicationController::class, 'create'])->name('create');
        Route::post('/', [HousingApplicationController::class, 'store'])->name('store');
        Route::get('/{application}', [HousingApplicationController::class, 'show'])->name('show');
    });
    
    // Areas
    Route::prefix('areas')->name('areas.')->group(function () {
        Route::get('/', [StandAreaController::class, 'index'])->name('index');
        Route::get('/create', [StandAreaController::class, 'create'])->name('create');
        Route::post('/', [StandAreaController::class, 'store'])->name('store');
    });
    
    // Properties
    Route::prefix('properties')->name('properties.')->group(function () {
        Route::get('/', [HousingPropertyController::class, 'index'])->name('index');
        Route::get('/create', [HousingPropertyController::class, 'create'])->name('create');
        Route::post('/', [HousingPropertyController::class, 'store'])->name('store');
        Route::get('/{property}', [HousingPropertyController::class, 'show'])->name('show');
        Route::get('/{property}/edit', [HousingPropertyController::class, 'edit'])->name('edit');
        Route::put('/{property}', [HousingPropertyController::class, 'update'])->name('update');
    });
    
    // Stand Areas
    Route::prefix('stand-areas')->name('stand-areas.')->group(function () {
        Route::get('/', [StandAreaController::class, 'standAreas'])->name('index');
        Route::get('/create', [StandAreaController::class, 'createStandArea'])->name('create');
        Route::post('/', [StandAreaController::class, 'storeStandArea'])->name('store');
    });
    
    // Tenants
    Route::prefix('tenants')->name('tenants.')->group(function () {
        Route::get('/', [TenantController::class, 'index'])->name('index');
        Route::get('/create', [TenantController::class, 'create'])->name('create');
        Route::post('/', [TenantController::class, 'store'])->name('store');
    });
    
    // Waiting List
    Route::prefix('waiting-list')->name('waiting-list.')->group(function () {
        Route::get('/', [WaitingListController::class, 'index'])->name('index');
        Route::get('/create', [WaitingListController::class, 'create'])->name('create');
        Route::post('/', [WaitingListController::class, 'store'])->name('store');
    });
});

// Water Module Routes
Route::middleware(['auth'])->prefix('water')->name('water.')->group(function () {
    Route::get('/', [WaterController::class, 'index'])->name('index');
    
    // Billing
    Route::prefix('billing')->name('billing.')->group(function () {
        Route::get('/', [WaterController::class, 'billing'])->name('index');
        Route::get('/create', [WaterController::class, 'createBilling'])->name('create');
        Route::post('/', [WaterController::class, 'storeBilling'])->name('store');
        Route::get('/create-invoice', [WaterController::class, 'createInvoice'])->name('create-invoice');
        Route::get('/invoices', [WaterController::class, 'invoices'])->name('invoices');
    });
    
    // Connections
    Route::prefix('connections')->name('connections.')->group(function () {
        Route::get('/', [WaterController::class, 'connections'])->name('index');
        Route::get('/create', [WaterController::class, 'createConnection'])->name('create');
        Route::post('/', [WaterController::class, 'storeConnection'])->name('store');
        Route::get('/{connection}', [WaterController::class, 'showConnection'])->name('show');
        Route::get('/{connection}/edit', [WaterController::class, 'editConnection'])->name('edit');
        Route::put('/{connection}', [WaterController::class, 'updateConnection'])->name('update');
    });
    
    // Infrastructure
    Route::prefix('infrastructure')->name('infrastructure.')->group(function () {
        Route::get('/', [WaterController::class, 'infrastructure'])->name('index');
    });
    
    // Meters
    Route::prefix('meters')->name('meters.')->group(function () {
        Route::get('/', [WaterController::class, 'meters'])->name('index');
        Route::get('/create-reading', [WaterController::class, 'createReading'])->name('create-reading');
        Route::post('/readings', [WaterController::class, 'storeReading'])->name('store-reading');
        Route::get('/readings', [WaterController::class, 'readings'])->name('readings');
    });
    
    // Quality
    Route::prefix('quality')->name('quality.')->group(function () {
        Route::get('/', [WaterController::class, 'quality'])->name('index');
        Route::get('/create-test', [WaterController::class, 'createTest'])->name('create-test');
        Route::post('/tests', [WaterController::class, 'storeTest'])->name('store-test');
        Route::get('/tests', [WaterController::class, 'tests'])->name('tests');
    });
    
    // Rates
    Route::prefix('rates')->name('rates.')->group(function () {
        Route::get('/', [WaterController::class, 'rates'])->name('index');
        Route::get('/create', [WaterController::class, 'createRate'])->name('create');
        Route::post('/', [WaterController::class, 'storeRate'])->name('store');
        Route::get('/{rate}/edit', [WaterController::class, 'editRate'])->name('edit');
        Route::put('/{rate}', [WaterController::class, 'updateRate'])->name('update');
    });
});

// Health Module Routes
Route::middleware(['auth'])->prefix('health')->name('health.')->group(function () {
    Route::get('/', [HealthController::class, 'index'])->name('index');
    Route::get('/emergency', [HealthController::class, 'emergency'])->name('emergency');
    Route::get('/environmental', [HealthController::class, 'environmental'])->name('environmental');
    Route::get('/facilities', [HealthController::class, 'facilities'])->name('facilities.index');
    Route::get('/food-safety', [HealthController::class, 'foodSafety'])->name('food-safety');
    Route::get('/immunization', [HealthController::class, 'immunization'])->name('immunization');
    Route::get('/quality', [HealthController::class, 'quality'])->name('quality');
    Route::get('/records', [HealthController::class, 'records'])->name('records');
    Route::get('/services', [HealthController::class, 'services'])->name('services');
    
    // Inspections
    Route::prefix('inspections')->name('inspections.')->group(function () {
        Route::get('/', [HealthController::class, 'inspections'])->name('index');
        Route::get('/create', [HealthController::class, 'createInspection'])->name('create');
        Route::post('/', [HealthController::class, 'storeInspection'])->name('store');
    });
    
    // Permits
    Route::prefix('permits')->name('permits.')->group(function () {
        Route::get('/', [HealthController::class, 'permits'])->name('index');
        Route::get('/create', [HealthController::class, 'createPermit'])->name('create');
        Route::post('/', [HealthController::class, 'storePermit'])->name('store');
    });
});

// Committee Module Routes
Route::middleware(['auth'])->prefix('committee')->name('committee.')->group(function () {
    Route::get('/', [CommitteeController::class, 'index'])->name('index');
    
    // Agendas
    Route::prefix('agendas')->name('agendas.')->group(function () {
        Route::get('/', [CommitteeController::class, 'agendas'])->name('index');
        Route::get('/create', [CommitteeController::class, 'createAgenda'])->name('create');
        Route::post('/', [CommitteeController::class, 'storeAgenda'])->name('store');
        Route::get('/{agenda}/edit', [CommitteeController::class, 'editAgenda'])->name('edit');
        Route::put('/{agenda}', [CommitteeController::class, 'updateAgenda'])->name('update');
    });
    
    // Archive
    Route::prefix('archive')->name('archive.')->group(function () {
        Route::get('/', [CommitteeController::class, 'archive'])->name('index');
    });
    
    // Committees
    Route::prefix('committees')->name('committees.')->group(function () {
        Route::get('/', [CommitteeController::class, 'committees'])->name('index');
        Route::get('/create', [CommitteeController::class, 'createCommittee'])->name('create');
        Route::post('/', [CommitteeController::class, 'storeCommittee'])->name('store');
        Route::get('/{committee}', [CommitteeController::class, 'showCommittee'])->name('show');
        Route::get('/{committee}/edit', [CommitteeController::class, 'editCommittee'])->name('edit');
        Route::put('/{committee}', [CommitteeController::class, 'updateCommittee'])->name('update');
    });
    
    // Meetings
    Route::prefix('meetings')->name('meetings.')->group(function () {
        Route::get('/', [CommitteeController::class, 'meetings'])->name('index');
        Route::get('/create', [CommitteeController::class, 'createMeeting'])->name('create');
        Route::post('/', [CommitteeController::class, 'storeMeeting'])->name('store');
        Route::get('/{meeting}/edit', [CommitteeController::class, 'editMeeting'])->name('edit');
        Route::put('/{meeting}', [CommitteeController::class, 'updateMeeting'])->name('update');
    });
    
    // Members
    Route::prefix('members')->name('members.')->group(function () {
        Route::get('/', [CommitteeController::class, 'members'])->name('index');
        Route::get('/create', [CommitteeController::class, 'createMember'])->name('create');
        Route::post('/', [CommitteeController::class, 'storeMember'])->name('store');
        Route::get('/{member}', [CommitteeController::class, 'showMember'])->name('show');
        Route::get('/{member}/edit', [CommitteeController::class, 'editMember'])->name('edit');
        Route::put('/{member}', [CommitteeController::class, 'updateMember'])->name('update');
    });
    
    // Minutes
    Route::prefix('minutes')->name('minutes.')->group(function () {
        Route::get('/', [CommitteeController::class, 'minutes'])->name('index');
        Route::get('/create', [CommitteeController::class, 'createMinute'])->name('create');
        Route::post('/', [CommitteeController::class, 'storeMinute'])->name('store');
        Route::get('/{minute}', [CommitteeController::class, 'showMinute'])->name('show');
        Route::get('/{minute}/edit', [CommitteeController::class, 'editMinute'])->name('edit');
        Route::put('/{minute}', [CommitteeController::class, 'updateMinute'])->name('update');
    });
    
    // Public
    Route::prefix('public')->name('public.')->group(function () {
        Route::get('/', [CommitteeController::class, 'publicCommittees'])->name('index');
    });
    
    // Resolutions
    Route::prefix('resolutions')->name('resolutions.')->group(function () {
        Route::get('/', [CommitteeController::class, 'resolutions'])->name('index');
        Route::get('/{resolution}', [CommitteeController::class, 'showResolution'])->name('show');
    });
});

// Engineering Module Routes
Route::middleware(['auth'])->prefix('engineering')->name('engineering.')->group(function () {
    Route::get('/', [EngineeringController::class, 'index'])->name('index');
    
    // Assets
    Route::prefix('assets')->name('assets.')->group(function () {
        Route::get('/', [EngineeringController::class, 'assets'])->name('index');
    });
    
    // Facilities
    Route::prefix('facilities')->name('facilities.')->group(function () {
        Route::get('/', [EngineeringController::class, 'facilities'])->name('index');
        Route::get('/create', [EngineeringController::class, 'createFacility'])->name('create');
        Route::post('/', [EngineeringController::class, 'storeFacility'])->name('store');
        Route::get('/{facility}', [EngineeringController::class, 'showFacility'])->name('show');
        
        Route::prefix('availability')->name('availability.')->group(function () {
            Route::get('/', [EngineeringController::class, 'facilityAvailability'])->name('index');
        });
        
        Route::prefix('bookings')->name('bookings.')->group(function () {
            Route::get('/', [EngineeringController::class, 'facilityBookings'])->name('index');
            Route::get('/pools', [EngineeringController::class, 'poolBookings'])->name('pools');
        });
        
        Route::prefix('calendar')->name('calendar.')->group(function () {
            Route::get('/', [EngineeringController::class, 'facilityCalendar'])->name('index');
        });
        
        Route::prefix('gate-takings')->name('gate-takings.')->group(function () {
            Route::get('/', [EngineeringController::class, 'gateTakings'])->name('index');
        });
        
        Route::prefix('halls')->name('halls.')->group(function () {
            Route::get('/', [EngineeringController::class, 'halls'])->name('index');
            Route::get('/create', [EngineeringController::class, 'createHall'])->name('create');
            Route::post('/', [EngineeringController::class, 'storeHall'])->name('store');
        });
        
        Route::prefix('maintenance')->name('maintenance.')->group(function () {
            Route::get('/', [EngineeringController::class, 'facilityMaintenance'])->name('index');
        });
        
        Route::prefix('pools')->name('pools.')->group(function () {
            Route::get('/', [EngineeringController::class, 'pools'])->name('index');
            Route::get('/create', [EngineeringController::class, 'createPool'])->name('create');
            Route::post('/', [EngineeringController::class, 'storePool'])->name('store');
        });
        
        Route::prefix('schedule')->name('schedule.')->group(function () {
            Route::get('/', [EngineeringController::class, 'facilitySchedule'])->name('index');
        });
        
        Route::prefix('sports')->name('sports.')->group(function () {
            Route::get('/', [EngineeringController::class, 'sports'])->name('index');
        });
    });
    
    // Infrastructure
    Route::prefix('infrastructure')->name('infrastructure.')->group(function () {
        Route::get('/', [EngineeringController::class, 'infrastructure'])->name('index');
    });
    
    // Inspections
    Route::prefix('inspections')->name('inspections.')->group(function () {
        Route::get('/', [EngineeringController::class, 'inspections'])->name('index');
    });
    
    // Maintenance
    Route::prefix('maintenance')->name('maintenance.')->group(function () {
        Route::get('/', [EngineeringController::class, 'maintenance'])->name('index');
    });
    
    // Planning
    Route::prefix('planning')->name('planning.')->group(function () {
        Route::get('/applications/create', [EngineeringController::class, 'createPlanningApplication'])->name('applications.create');
        Route::post('/applications', [EngineeringController::class, 'storePlanningApplication'])->name('applications.store');
    });
    
    // Projects
    Route::prefix('projects')->name('projects.')->group(function () {
        Route::get('/', [EngineeringController::class, 'projects'])->name('index');
        Route::get('/create', [EngineeringController::class, 'createProject'])->name('create');
        Route::post('/', [EngineeringController::class, 'storeProject'])->name('store');
    });
    
    // Surveys
    Route::prefix('surveys')->name('surveys.')->group(function () {
        Route::get('/', [EngineeringController::class, 'surveys'])->name('index');
    });
    
    // Work Orders
    Route::prefix('work-orders')->name('work-orders.')->group(function () {
        Route::get('/', [EngineeringController::class, 'workOrders'])->name('index');
    });
});

// HR Module Routes
Route::middleware(['auth'])->prefix('hr')->name('hr.')->group(function () {
    Route::get('/', [HrController::class, 'index'])->name('index');
    
    // Attendance
    Route::prefix('attendance')->name('attendance.')->group(function () {
        Route::get('/', [HrController::class, 'attendance'])->name('index');
        Route::get('/face-scan', [HrController::class, 'faceScan'])->name('face-scan');
    });
    
    // Currency
    Route::prefix('currency')->name('currency.')->group(function () {
        Route::prefix('rates')->name('rates.')->group(function () {
            Route::get('/', [CurrencyRateController::class, 'index'])->name('index');
            Route::get('/create', [CurrencyRateController::class, 'create'])->name('create');
            Route::post('/', [CurrencyRateController::class, 'store'])->name('store');
            Route::get('/{rate}', [CurrencyRateController::class, 'show'])->name('show');
            Route::get('/{rate}/edit', [CurrencyRateController::class, 'edit'])->name('edit');
            Route::put('/{rate}', [CurrencyRateController::class, 'update'])->name('update');
        });
    });
    
    // Departments
    Route::prefix('departments')->name('departments.')->group(function () {
        Route::get('/', [HrController::class, 'departments'])->name('index');
        Route::get('/create', [HrController::class, 'createDepartment'])->name('create');
        Route::post('/', [HrController::class, 'storeDepartment'])->name('store');
        Route::get('/{department}', [HrController::class, 'showDepartment'])->name('show');
        Route::get('/{department}/edit', [HrController::class, 'editDepartment'])->name('edit');
        Route::put('/{department}', [HrController::class, 'updateDepartment'])->name('update');
    });
    
    // Employees
    Route::prefix('employees')->name('employees.')->group(function () {
        Route::get('/', [HrController::class, 'employees'])->name('index');
        Route::get('/{employee}/edit-salary', [HrController::class, 'editSalary'])->name('edit-salary');
        Route::put('/{employee}/salary', [HrController::class, 'updateSalary'])->name('update-salary');
    });
});

// Inventory Module Routes
Route::middleware(['auth'])->prefix('inventory')->name('inventory.')->group(function () {
    Route::get('/', [InventoryController::class, 'index'])->name('index');
    Route::get('/create', [InventoryController::class, 'create'])->name('create');
    Route::post('/', [InventoryController::class, 'store'])->name('store');
    Route::get('/{item}', [InventoryController::class, 'show'])->name('show');
    Route::get('/{item}/edit', [InventoryController::class, 'edit'])->name('edit');
    Route::put('/{item}', [InventoryController::class, 'update'])->name('update');
});

// Licensing Module Routes
Route::middleware(['auth'])->prefix('licensing')->name('licensing.')->group(function () {
    Route::get('/', [LicensingController::class, 'index'])->name('index');
    
    // Applications
    Route::prefix('applications')->name('applications.')->group(function () {
        Route::get('/', [LicensingController::class, 'applications'])->name('index');
        Route::get('/create', [LicensingController::class, 'createApplication'])->name('create');
        Route::post('/', [LicensingController::class, 'storeApplication'])->name('store');
        Route::get('/{application}', [LicensingController::class, 'showApplication'])->name('show');
    });
    
    // Operating
    Route::prefix('operating')->name('operating.')->group(function () {
        Route::get('/', [OperatingLicenseController::class, 'index'])->name('index');
        Route::prefix('applications')->name('applications.')->group(function () {
            Route::get('/create', [OperatingLicenseController::class, 'createApplication'])->name('create');
            Route::post('/', [OperatingLicenseController::class, 'storeApplication'])->name('store');
        });
    });
    
    // Shop Permits
    Route::prefix('shop-permits')->name('shop-permits.')->group(function () {
        Route::get('/', [LicensingController::class, 'shopPermits'])->name('index');
        Route::get('/create', [LicensingController::class, 'createShopPermit'])->name('create');
        Route::post('/', [LicensingController::class, 'storeShopPermit'])->name('store');
    });
});

// Markets Module Routes
Route::middleware(['auth'])->prefix('markets')->name('markets.')->group(function () {
    Route::get('/', [StallController::class, 'index'])->name('index');
    Route::get('/create', [StallController::class, 'create'])->name('create');
    Route::post('/', [StallController::class, 'store'])->name('store');
    Route::get('/dashboard', [StallController::class, 'dashboard'])->name('dashboard');
    
    // Vendors
    Route::prefix('vendors')->name('vendors.')->group(function () {
        Route::get('/', [StallController::class, 'vendors'])->name('index');
    });
});

// Parking Module Routes
Route::middleware(['auth'])->prefix('parking')->name('parking.')->group(function () {
    Route::get('/', [ParkingController::class, 'index'])->name('index');
    
    // Permits
    Route::prefix('permits')->name('permits.')->group(function () {
        Route::get('/', [ParkingController::class, 'permits'])->name('index');
        Route::get('/create', [ParkingController::class, 'createPermit'])->name('create');
        Route::post('/', [ParkingController::class, 'storePermit'])->name('store');
    });
    
    // Spaces
    Route::prefix('spaces')->name('spaces.')->group(function () {
        Route::get('/', [ParkingController::class, 'spaces'])->name('index');
    });
    
    // Violations
    Route::prefix('violations')->name('violations.')->group(function () {
        Route::get('/', [ParkingController::class, 'violations'])->name('index');
        Route::get('/create', [ParkingController::class, 'createViolation'])->name('create');
        Route::post('/', [ParkingController::class, 'storeViolation'])->name('store');
    });
    
    // Zones
    Route::prefix('zones')->name('zones.')->group(function () {
        Route::get('/', [ParkingController::class, 'zones'])->name('index');
    });
});

// Property Module Routes
Route::middleware(['auth'])->prefix('property')->name('property.')->group(function () {
    Route::get('/', [PropertyController::class, 'index'])->name('index');
    Route::get('/create', [PropertyController::class, 'create'])->name('create');
    Route::post('/', [PropertyController::class, 'store'])->name('store');
    Route::get('/land-records', [PropertyController::class, 'landRecords'])->name('land-records');
    Route::get('/leases', [PropertyController::class, 'leases'])->name('leases');
    Route::get('/valuations', [PropertyController::class, 'valuations'])->name('valuations');
});

// Survey Module Routes
Route::middleware(['auth'])->prefix('survey')->name('survey.')->group(function () {
    Route::get('/', [SurveyController::class, 'index'])->name('index');
    
    // Equipment
    Route::prefix('equipment')->name('equipment.')->group(function () {
        Route::get('/', [SurveyController::class, 'equipment'])->name('index');
    });
    
    // Projects
    Route::prefix('projects')->name('projects.')->group(function () {
        Route::get('/', [SurveyController::class, 'projects'])->name('index');
        Route::get('/create', [SurveyController::class, 'createProject'])->name('create');
        Route::post('/', [SurveyController::class, 'storeProject'])->name('store');
        Route::get('/{project}', [SurveyController::class, 'showProject'])->name('show');
        Route::get('/{project}/edit', [SurveyController::class, 'editProject'])->name('edit');
        Route::put('/{project}', [SurveyController::class, 'updateProject'])->name('update');
    });
});

// Utilities Module Routes  
Route::middleware(['auth'])->prefix('utilities')->name('utilities.')->group(function () {
    Route::get('/', [ModuleUtilitiesController::class, 'index'])->name('index');
    
    // Electricity
    Route::prefix('electricity')->name('electricity.')->group(function () {
        Route::get('/', [ModuleUtilitiesController::class, 'electricity'])->name('index');
        Route::get('/connections', [ModuleUtilitiesController::class, 'electricityConnections'])->name('connections');
        Route::get('/meters', [ModuleUtilitiesController::class, 'electricityMeters'])->name('meters');
    });
    
    // Fleet
    Route::prefix('fleet')->name('fleet.')->group(function () {
        Route::get('/', [ModuleUtilitiesController::class, 'fleet'])->name('index');
    });
    
    // Gas
    Route::prefix('gas')->name('gas.')->group(function () {
        Route::get('/', [ModuleUtilitiesController::class, 'gas'])->name('index');
    });
    
    // Infrastructure
    Route::prefix('infrastructure')->name('infrastructure.')->group(function () {
        Route::get('/', [ModuleUtilitiesController::class, 'infrastructure'])->name('index');
    });
    
    // Waste
    Route::prefix('waste')->name('waste.')->group(function () {
        Route::get('/', [ModuleUtilitiesController::class, 'waste'])->name('index');
    });
});

// Additional Core Routes
Route::middleware(['auth'])->group(function () {
    
    // Modules Management
    Route::get('/modules', [ModuleController::class, 'index'])->name('modules.index');
    Route::get('/modules/{module}', [ModuleController::class, 'show'])->name('modules.show');
});

// Fallback route for undefined routes
Route::fallback(function () {
    return redirect('/dashboard')->with('error', 'The requested page was not found.');
});

<?php

use App\Http\Controllers\InstallController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Housing\HousingApplicationController;
use App\Http\Controllers\Housing\PropertyController;
use App\Http\Controllers\Administration\CrmController;
use App\Http\Controllers\Planning\PlanningController;
use App\Http\Controllers\Finance\FinanceController;
use App\Http\Controllers\Finance\GeneralLedgerController;
use App\Http\Controllers\Finance\AccountsReceivableController;
use App\Http\Controllers\Finance\BudgetController;
use App\Http\Controllers\Finance\ReportController;
use App\Http\Controllers\Finance\BankReconciliationController;
use App\Http\Controllers\Finance\PayrollController;
use App\Http\Controllers\Finance\AssetController;
use App\Http\Controllers\Finance\PosController; // Import the POS controller
use App\Http\Controllers\Finance\PaymentMethodController; // Import the PaymentMethod controller
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DepartmentController;
use App\Http\Controllers\Admin\OfficeController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Housing\WaitingListController;
use App\Http\Controllers\Inventory\InventoryController;
use App\Http\Controllers\Facilities\BookingController;
use App\Http\Controllers\Cemeteries\CemeteryController;
use App\Http\Controllers\Property\PropertyManagementController;
use App\Http\Controllers\Water\WaterController;
use App\Http\Controllers\PublicServices\PublicServicesController;
use App\Http\Controllers\Committee\CommitteeController;
use App\Http\Controllers\Utilities\UtilitiesController;
use App\Http\Controllers\Health\HealthController;
use App\Http\Controllers\Licensing\LicensingController;
use App\Http\Controllers\Licensing\OperatingLicenseController;
use App\Http\Controllers\Events\EventPermitController;
use App\Http\Controllers\PropertyTax\PropertyTaxationController;
use App\Http\Controllers\Markets\MarketController;
use App\Http\Controllers\Markets\StallController;
use App\Http\Controllers\Parking\ParkingController;
use App\Http\Controllers\Engineering\EngineeringController;
use App\Http\Controllers\ServiceRequestController; // Corrected import
use App\Http\Controllers\Api\DashboardApiController; // Added for API routes
use App\Models\Finance\PaymentMethod; // Added for API routes
use App\Models\Finance\PosTerminal; // Added for API routes
use App\Http\Controllers\Survey\SurveyController; // Added for Survey routes
use App\Http\Controllers\HR\HrController; // Added for HR routes
use App\Http\Controllers\HR\PayrollController as PayrollControllerHR; // Alias for HR PayrollController
use App\Http\Controllers\Billing\MunicipalBillingController; // Added for Billing routes

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Installation routes
Route::middleware('ensure.not.installed')->group(function () {
    Route::get('/install', [InstallController::class, 'index'])->name('install.index');
    Route::post('/install', [InstallController::class, 'store'])->name('install.store');
    Route::post('/install/test-database', [InstallController::class, 'testDatabase'])->name('install.test-database');
    Route::get('/install/complete', [InstallController::class, 'complete'])->name('install.complete');
});

// Authentication routes
Route::middleware('ensure.installed')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});

// Protected routes
Route::middleware(['ensure.installed', 'auth'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Housing Management
    Route::prefix('housing')->name('housing.')->group(function () {
        Route::resource('applications', HousingApplicationController::class);
        Route::resource('properties', PropertyController::class);
        Route::get('waiting-list', [\App\Http\Controllers\Housing\WaitingListController::class, 'index'])->name('waiting-list');
    });

    // Customer Services / CRM
    Route::prefix('administration')->name('administration.')->group(function () {
        Route::get('crm', [CrmController::class, 'index'])->name('crm.index');
        Route::get('crm/customers', [CrmController::class, 'customers'])->name('crm.customers');
        Route::get('crm/customers/create', [CrmController::class, 'createCustomer'])->name('crm.customers.create');
        Route::post('crm/customers', [CrmController::class, 'storeCustomer'])->name('crm.customers.store');
    });

    // Planning
    Route::prefix('planning')->name('planning.')->group(function () {
        Route::get('/', [PlanningController::class, 'index'])->name('index');
        Route::get('applications', [PlanningController::class, 'applications'])->name('applications');
        Route::get('applications/create', [PlanningController::class, 'createApplication'])->name('applications.create');
        Route::post('applications', [PlanningController::class, 'storeApplication'])->name('applications.store');
        Route::get('applications/{application}', [PlanningController::class, 'showApplication'])->name('applications.show');
        Route::get('approvals', [PlanningController::class, 'approvals'])->name('approvals');
        Route::get('zoning', [PlanningController::class, 'zoning'])->name('zoning');
    });

    // Finance Management
    Route::prefix('finance')->name('finance.')->group(function () {
        Route::get('/', [FinanceController::class, 'index'])->name('index');
        Route::get('/create-invoice', [FinanceController::class, 'createInvoice'])->name('create-invoice');
        Route::post('/store-invoice', [FinanceController::class, 'storeInvoice'])->name('store-invoice');

        // General Ledger
        Route::prefix('general-ledger')->name('general-ledger.')->group(function () {
            Route::get('/', [GeneralLedgerController::class, 'index'])->name('index');
            Route::get('/create', [GeneralLedgerController::class, 'create'])->name('create');
            Route::post('/', [GeneralLedgerController::class, 'store'])->name('store');
            Route::get('/{generalLedger}', [GeneralLedgerController::class, 'show'])->name('show');
            Route::post('/{generalLedger}/approve', [GeneralLedgerController::class, 'approve'])->name('approve');
        });

        // Reports
        Route::get('/reports/trial-balance', [GeneralLedgerController::class, 'trialBalance'])->name('reports.trial-balance');

        // Accounts Receivable
        Route::prefix('accounts-receivable')->name('accounts-receivable.')->group(function () {
            Route::get('/', [AccountsReceivableController::class, 'index'])->name('index');
            Route::get('/customers', [AccountsReceivableController::class, 'customers'])->name('customers');
            Route::get('/invoices', [AccountsReceivableController::class, 'invoices'])->name('invoices');
            Route::post('/mark-as-paid/{invoice}', [AccountsReceivableController::class, 'markAsPaid'])->name('mark-as-paid');
        });

        // Point of Sale (POS) System
        Route::prefix('pos')->name('pos.')->group(function () {
            Route::get('/', [PosController::class, 'index'])->name('index');
            Route::get('/search-customer', [PosController::class, 'searchCustomer'])->name('search-customer');
            Route::get('/customer/{account}', [PosController::class, 'getCustomerDetails'])->name('customer-details');
            Route::post('/collect-payment', [PosController::class, 'collectPayment'])->name('collect-payment');
            Route::get('/receipt/{collection}', [PosController::class, 'printReceipt'])->name('receipt');
            Route::get('/daily-report', [PosController::class, 'dailyReport'])->name('daily-report');
            Route::get('/audit-trail', [PosController::class, 'auditTrail'])->name('audit-trail');
            Route::get('/real-time-collections', [PosController::class, 'realTimeCollections'])->name('real-time-collections');
        });

        // Payment Methods Management (Admin only)
        Route::prefix('payment-methods')->name('payment-methods.')->middleware('admin')->group(function () {
            Route::get('/', [PaymentMethodController::class, 'index'])->name('index');
            Route::get('/create', [PaymentMethodController::class, 'create'])->name('create');
            Route::post('/', [PaymentMethodController::class, 'store'])->name('store');
            Route::get('/{paymentMethod}/edit', [PaymentMethodController::class, 'edit'])->name('edit');
            Route::put('/{paymentMethod}', [PaymentMethodController::class, 'update'])->name('update');
            Route::patch('/{paymentMethod}/toggle-status', [PaymentMethodController::class, 'toggleStatus'])->name('toggle-status');
        });

        // API routes for AJAX calls
        Route::prefix('api')->name('api.')->group(function () {
            Route::get('/payment-methods', function () {
                return PaymentMethod::getActive();
            });
            Route::get('/pos-terminals', function () {
                return PosTerminal::getActive();
            });
            Route::get('/departments', [DashboardApiController::class, 'getDepartments']);
            Route::get('/offices/{department}', [DashboardApiController::class, 'getOffices']);
            Route::get('/dashboard-stats', [DashboardApiController::class, 'getStats']);
        });

        // Chart of Accounts
        Route::resource('accounts', ChartOfAccountController::class);

        // Budgets
        Route::resource('budgets', BudgetController::class);
        Route::get('budgets-variance', [BudgetController::class, 'variance'])->name('budgets.variance');

        // Financial Reports
        Route::prefix('reports')->name('reports.')->group(function () {
            Route::get('/', [ReportController::class, 'index'])->name('index');
            Route::get('create', [ReportController::class, 'create'])->name('create');
            Route::post('generate', [ReportController::class, 'generate'])->name('generate');
            Route::get('{report}', [ReportController::class, 'show'])->name('show');

            // Specific Reports
            Route::get('balance-sheet', [ReportController::class, 'balanceSheet'])->name('balance-sheet');
            Route::get('income-statement', [ReportController::class, 'incomeStatement'])->name('income-statement');
            Route::get('cash-flow', [ReportController::class, 'cashFlow'])->name('cash-flow');
            Route::get('trial-balance', [ReportController::class, 'trialBalance'])->name('trial-balance');
            Route::get('budget-variance', [ReportController::class, 'budgetVariance'])->name('budget-variance');
        });

        // Accounts Receivable
        Route::prefix('receivables')->name('receivables.')->group(function () {
            Route::get('/', [AccountsReceivableController::class, 'index'])->name('index');
            Route::get('invoices', [AccountsReceivableController::class, 'invoices'])->name('invoices');
            Route::get('invoices/create', [AccountsReceivableController::class, 'createInvoice'])->name('invoices.create');
            Route::post('invoices', [AccountsReceivableController::class, 'storeInvoice'])->name('invoices.store');
            Route::get('invoices/{invoice}', [AccountsReceivableController::class, 'showInvoice'])->name('invoices.show');
            Route::get('customers', [AccountsReceivableController::class, 'customers'])->name('customers');
            Route::get('payments', [AccountsReceivableController::class, 'payments'])->name('payments');
        });

        // Bank Reconciliation
        Route::resource('reconciliations', BankReconciliationController::class);
        Route::post('reconciliations/{reconciliation}/reconcile', [BankReconciliationController::class, 'reconcile'])->name('reconciliations.reconcile');

        // Payroll
        Route::prefix('payroll')->name('payroll.')->group(function () {
            Route::get('/', [PayrollController::class, 'index'])->name('index');
            Route::get('employees', [PayrollController::class, 'employees'])->name('employees');
            Route::get('employees/create', [PayrollController::class, 'createEmployee'])->name('employees.create');
            Route::post('employees', [PayrollController::class, 'storeEmployee'])->name('employees.store');
            Route::get('payroll-runs', [PayrollController::class, 'payrollRuns'])->name('runs');
            Route::get('payroll-runs/create', [PayrollController::class, 'createPayrollRun'])->name('runs.create');
            Route::post('payroll-runs', [PayrollController::class, 'storePayrollRun'])->name('runs.store');
        });

        // Asset Management
        Route::prefix('assets')->name('assets.')->group(function () {
            Route::get('/', [AssetController::class, 'index'])->name('index');
            Route::get('create', [AssetController::class, 'create'])->name('create');
            Route::post('/', [AssetController::class, 'store'])->name('store');
            Route::get('{asset}', [AssetController::class, 'show'])->name('show');
            Route::get('{asset}/edit', [AssetController::class, 'edit'])->name('edit');
            Route::put('{asset}', [AssetController::class, 'update'])->name('update');
            Route::delete('{asset}', [AssetController::class, 'destroy'])->name('destroy');
            Route::post('{asset}/depreciate', [AssetController::class, 'depreciate'])->name('depreciate');
        });

        // Cost Centers
        Route::resource('cost-centers', 'CostCenterController');

        // Accounts Payable
        Route::prefix('payables')->name('payables.')->group(function () {
            Route::get('/', 'AccountsPayableController@index')->name('index');
            Route::get('vendors', 'AccountsPayableController@vendors')->name('vendors');
            Route::get('bills', 'AccountsPayableController@bills')->name('bills');
            Route::get('payments', 'AccountsPayableController@payments')->name('payments');
        });

        // Bank Management
        Route::resource('banks', 'BankAccountController');

        // Tax Management
        Route::resource('tax-rates', 'TaxRateController');
    });

    // System Reports (admin only)
    Route::prefix('reports')->name('reports.')->middleware('admin')->group(function () {
        Route::get('/', [ReportsController::class, 'index'])->name('index');
        Route::get('/council-overview', [ReportsController::class, 'councilOverview'])->name('council-overview');
        Route::get('/department-performance', [ReportsController::class, 'departmentPerformance'])->name('department-performance');
        Route::get('/service-delivery', [ReportsController::class, 'serviceDelivery'])->name('service-delivery');
        Route::get('/financial-summary', [ReportsController::class, 'financialSummary'])->name('financial-summary');
    });

    // Admin routes (only for admin and super_admin)
    Route::prefix('admin')->name('admin.')->middleware('admin')->group(function () {
        Route::resource('users', UserController::class);
        Route::resource('departments', DepartmentController::class);
        Route::resource('offices', OfficeController::class);
    });

    // Utilities & Infrastructure
    Route::prefix('utilities')->name('utilities.')->group(function () {
        Route::get('/', [UtilitiesController::class, 'index'])->name('index');
        Route::get('water', [UtilitiesController::class, 'water'])->name('water.index');
        Route::get('electricity', [UtilitiesController::class, 'electricity'])->name('electricity.index');
        Route::get('waste', [UtilitiesController::class, 'waste'])->name('waste.index');
        Route::get('roads', [UtilitiesController::class, 'roads'])->name('roads.index');
        Route::get('lighting', [UtilitiesController::class, 'lighting'])->name('lighting.index');
        Route::get('parks', [UtilitiesController::class, 'parks'])->name('parks.index');
        Route::get('fleet', [UtilitiesController::class, 'fleet'])->name('fleet.index');
    });

    // Health & Safety Services
    Route::prefix('health')->name('health.')->group(function () {
        Route::get('/', [HealthController::class, 'index'])->name('index');
        Route::get('permits', [HealthController::class, 'permits'])->name('permits.index');
        Route::get('inspections', [HealthController::class, 'inspections'])->name('inspections.index');
        Route::get('food-safety', [HealthController::class, 'foodSafety'])->name('food-safety.index');
    });

    // Event Permits System
    Route::prefix('events/permits')->name('events.permits.')->group(function () {
        Route::get('/', [EventPermitController::class, 'index'])->name('index');
        Route::get('applications', [EventPermitController::class, 'applications'])->name('applications.index');
        Route::get('create', [EventPermitController::class, 'create'])->name('create');
        Route::post('/', [EventPermitController::class, 'store'])->name('store');
        Route::get('{permit}', [EventPermitController::class, 'show'])->name('show');
        Route::post('{permit}/review', [EventPermitController::class, 'review'])->name('review');
        Route::post('{permit}/inspection', [EventPermitController::class, 'requireInspection'])->name('require-inspection');
        Route::post('{permit}/approve', [EventPermitController::class, 'approve'])->name('approve');
        Route::post('{permit}/reject', [EventPermitController::class, 'reject'])->name('reject');
        Route::get('{permit}/print', [EventPermitController::class, 'printPermit'])->name('print');
        Route::get('documents/{document}/download', [EventPermitController::class, 'downloadDocument'])->name('documents.download');
    });

    // Licensing & Business Permits
    Route::prefix('licensing')->name('licensing.')->group(function () {
        Route::get('/', [LicensingController::class, 'index'])->name('index');

        // Operating Licenses
        Route::prefix('operating')->name('operating.')->group(function () {
            Route::get('/', [OperatingLicenseController::class, 'index'])->name('index');

            // Applications
            Route::prefix('applications')->name('applications.')->group(function () {
                Route::get('/', [OperatingLicenseController::class, 'applications'])->name('index');
                Route::get('create', [OperatingLicenseController::class, 'createApplication'])->name('create');
                Route::post('/', [OperatingLicenseController::class, 'storeApplication'])->name('store');
                Route::get('{application}', [OperatingLicenseController::class, 'showApplication'])->name('show');
                Route::post('{application}/review', [OperatingLicenseController::class, 'reviewApplication'])->name('review');
            });

            // Licenses
            Route::prefix('licenses')->name('licenses.')->group(function () {
                Route::get('/', [OperatingLicenseController::class, 'licenses'])->name('index');
                Route::get('{license}', [OperatingLicenseController::class, 'showLicense'])->name('show');
                Route::get('{license}/renew', [OperatingLicenseController::class, 'renewLicense'])->name('renew');
                Route::post('{license}/renew', [OperatingLicenseController::class, 'processRenewal'])->name('process-renewal');
                Route::post('{license}/suspend', [OperatingLicenseController::class, 'suspendLicense'])->name('suspend');
                Route::post('{license}/revoke', [OperatingLicenseController::class, 'revokeLicense'])->name('revoke');
            });

            // License Types
            Route::prefix('types')->name('types.')->group(function () {
                Route::get('/', [OperatingLicenseController::class, 'licenseTypes'])->name('index');
                Route::get('create', [OperatingLicenseController::class, 'createLicenseType'])->name('create');
                Route::post('/', [OperatingLicenseController::class, 'storeLicenseType'])->name('store');
            });
        });

        // Applications
        Route::get('applications', [LicensingController::class, 'applications'])->name('applications.index');
        Route::get('applications/create', [LicensingController::class, 'createApplication'])->name('applications.create');
        Route::post('applications', [LicensingController::class, 'storeApplication'])->name('applications.store');
        Route::get('applications/{application}', [LicensingController::class, 'showApplication'])->name('applications.show');
        Route::post('applications/{application}/review', [LicensingController::class, 'reviewApplication'])->name('applications.review');
        Route::post('applications/{application}/require-inspection', [LicensingController::class, 'requireInspection'])->name('applications.require-inspection');
        Route::post('applications/{application}/approve', [LicensingController::class, 'approveApplication'])->name('applications.approve');
        Route::post('applications/{application}/reject', [LicensingController::class, 'rejectApplication'])->name('applications.reject');

        // Licenses
        Route::get('licenses', [LicensingController::class, 'licenses'])->name('licenses.index');
        Route::get('licenses/{license}', [LicensingController::class, 'showLicense'])->name('licenses.show');
        Route::get('licenses/{license}/renew', [LicensingController::class, 'renewLicense'])->name('licenses.renew');
        Route::post('licenses/{license}/renew', [LicensingController::class, 'processRenewal'])->name('licenses.process-renewal');
        Route::post('licenses/{license}/suspend', [LicensingController::class, 'suspendLicense'])->name('licenses.suspend');
        Route::post('licenses/{license}/revoke', [LicensingController::class, 'revokeLicense'])->name('licenses.revoke');
        Route::get('licenses/{license}/print', [LicensingController::class, 'printLicense'])->name('licenses.print');

        // Inspections
        Route::get('inspections', [LicensingController::class, 'inspections'])->name('inspections.index');
        Route::post('inspections/{inspection}/complete', [LicensingController::class, 'completeInspection'])->name('inspections.complete');

        // Documents
        Route::get('documents/{document}/download', [LicensingController::class, 'downloadDocument'])->name('documents.download');

        // License Types Management (Admin)
        Route::get('license-types', [LicensingController::class, 'licenseTypes'])->name('license-types.index')->middleware('admin');
        Route::get('license-types/create', [LicensingController::class, 'createLicenseType'])->name('license-types.create')->middleware('admin');
        Route::post('license-types', [LicensingController::class, 'storeLicenseType'])->name('license-types.store')->middleware('admin');

        // Shop Permits
        Route::get('shop-permits', [LicensingController::class, 'shopPermits'])->name('shop-permits.index');
        Route::get('shop-permits/create', [LicensingController::class, 'createShopPermitApplication'])->name('shop-permits.create');
        Route::post('shop-permits', [LicensingController::class, 'storeShopPermitApplication'])->name('shop-permits.store');
        Route::get('shop-permits/{application}', [LicensingController::class, 'showShopPermitApplication'])->name('shop-permits.show');
        Route::post('shop-permits/{application}/review', [LicensingController::class, 'reviewShopPermitApplication'])->name('shop-permits.review');
        Route::post('shop-permits/{application}/schedule-inspection', [LicensingController::class, 'scheduleShopPermitInspection'])->name('shop-permits.schedule-inspection');

        // Shop Permit Types (Admin)
        Route::get('shop-permit-types', [LicensingController::class, 'shopPermitTypes'])->name('shop-permit-types.index')->middleware('admin');
        Route::get('shop-permit-types/create', [LicensingController::class, 'createShopPermitType'])->name('shop-permit-types.create')->middleware('admin');
        Route::post('shop-permit-types', [LicensingController::class, 'storeShopPermitType'])->name('shop-permit-types.store')->middleware('admin');
    });

    // Public Services
    Route::prefix('public-services')->name('public-services.')->group(function () {
        Route::get('/', [PublicServicesController::class, 'index'])->name('index');

        // Service Requests
        Route::get('requests', [PublicServicesController::class, 'serviceRequests'])->name('requests.index');
        Route::get('requests/create', [PublicServicesController::class, 'createServiceRequest'])->name('requests.create');
        Route::post('requests', [PublicServicesController::class, 'storeServiceRequest'])->name('requests.store');
        Route::get('requests/{serviceRequest}', [PublicServicesController::class, 'showServiceRequest'])->name('requests.show');

        // Public Complaints
        Route::get('complaints', [PublicServicesController::class, 'complaints'])->name('complaints.index');
        Route::get('complaints/create', [PublicServicesController::class, 'createComplaint'])->name('complaints.create');
        Route::post('complaints', [PublicServicesController::class, 'storeComplaint'])->name('complaints.store');

        // Event Permits
        Route::get('permits', [PublicServicesController::class, 'eventPermits'])->name('permits.index');
        Route::get('permits/create', [PublicServicesController::class, 'createEventPermit'])->name('permits.create');
        Route::post('permits', [PublicServicesController::class, 'storeEventPermit'])->name('permits.store');
        Route::get('permits/{permit}', [PublicServicesController::class, 'showEventPermit'])->name('permits.show');
    });

    // Engineering Services Routes
    Route::prefix('engineering')->name('engineering.')->group(function () {
        Route::get('/', [EngineeringController::class, 'index'])->name('index');

        // Projects
        Route::prefix('projects')->name('projects.')->group(function () {
            Route::get('/', [EngineeringController::class, 'projects'])->name('index');
            Route::get('create', [EngineeringController::class, 'createProject'])->name('create');
            Route::post('projects', [EngineeringController::class, 'storeProject'])->name('store');
        });

        // Work Orders
        Route::prefix('work-orders')->name('work-orders.')->group(function () {
            Route::get('/', [EngineeringController::class, 'workOrders'])->name('index');
            Route::get('create', [EngineeringController::class, 'createWorkOrder'])->name('create');
        });

        // Assets
        Route::prefix('assets')->name('assets.')->group(function () {
            Route::get('/', [EngineeringController::class, 'assets'])->name('index');
            Route::get('create', [EngineeringController::class, 'createAsset'])->name('create');
        });

        // Maintenance
        Route::prefix('maintenance')->name('maintenance.')->group(function () {
            Route::get('/', [EngineeringController::class, 'maintenance'])->name('index');
        });

        // Surveys
        Route::prefix('surveys')->name('surveys.')->group(function () {
            Route::get('/', [EngineeringController::class, 'surveys'])->name('index');
            Route::get('create', [EngineeringController::class, 'createSurvey'])->name('create');
            Route::post('surveys', [EngineeringController::class, 'storeSurvey'])->name('store');
        });

        // Architectural Services
        Route::prefix('architectural')->name('architectural.')->group(function () {
            Route::get('/', [EngineeringController::class, 'architecturalServices'])->name('index');
            Route::get('projects', [EngineeringController::class, 'architecturalProjects'])->name('projects.index');
            Route::get('projects/create', [EngineeringController::class, 'createArchitecturalProject'])->name('projects.create');
        });

        // Town Planning
        Route::prefix('planning')->name('planning.')->group(function () {
            Route::get('/', [EngineeringController::class, 'townPlanning'])->name('index');
            Route::get('applications', [EngineeringController::class, 'planningApplications'])->name('applications.index');
            Route::get('applications/create', [EngineeringController::class, 'createPlanningApplication'])->name('applications.create');
            Route::post('applications', [EngineeringController::class, 'storePlanningApplication'])->name('applications.store');
            Route::get('zoning', [EngineeringController::class, 'zoning'])->name('zoning.index');
            Route::get('approvals', [EngineeringController::class, 'planningApprovals'])->name('approvals.index');
        });
    });


    // Revenue Management
    Route::prefix('revenue')->name('revenue.')->group(function () {
        Route::get('/', [RevenueController::class, 'index'])->name('index');

        // Property Valuations
        Route::get('valuations', [RevenueController::class, 'valuations'])->name('valuations.index');
        Route::get('valuations/create', [RevenueController::class, 'createValuation'])->name('valuations.create');

        // Tax Assessments
        Route::get('assessments', [RevenueController::class, 'assessments'])->name('assessments.index');
        Route::get('assessments/create', [RevenueController::class, 'createAssessment'])->name('assessments.create');

        // Business Registrations
        Route::get('business-registrations', [RevenueController::class, 'businessRegistrations'])->name('business-registrations.index');
        Route::get('business-registrations/create', [RevenueController::class, 'createBusinessRegistration'])->name('business-registrations.create');

        // Market Stalls
        Route::get('market-stalls', [RevenueController::class, 'marketStalls'])->name('market-stalls.index');

        // Parking Management
        Route::get('parking', [RevenueController::class, 'parkingManagement'])->name('parking.index');
        Route::get('parking/violations', [RevenueController::class, 'parkingViolations'])->name('parking.violations.index');

        // Collections
        Route::get('collections', [RevenueController::class, 'collections'])->name('collections.index');
        Route::get('collections/create', [RevenueController::class, 'createCollection'])->name('collections.create');
    });

    // Enhanced Facilities Management
    Route::prefix('facilities')->name('facilities.')->group(function () {
        Route::get('bookings', [BookingController::class, 'index'])->name('bookings.index');
        Route::get('bookings/pools', [BookingController::class, 'pools'])->name('bookings.pools');
        Route::get('gate-takings', [BookingController::class, 'gateTakings'])->name('gate-takings.index');
    });

    // Enhanced Cemetery Management
    Route::prefix('cemeteries')->name('cemeteries.')->group(function () {
        Route::get('/', [CemeteryController::class, 'index'])->name('index');
        Route::get('graves', [CemeteryController::class, 'graves'])->name('graves.index');
        Route::get('permits', [CemeteryController::class, 'permits'])->name('permits.index');
        Route::get('management', [CemeteryController::class, 'management'])->name('management.index');
        Route::get('interments', [CemeteryController::class, 'interments'])->name('interments.index');
        Route::get('memorials', [CemeteryController::class, 'memorials'])->name('memorials.index');
        Route::get('maintenance', [CemeteryController::class, 'maintenance'])->name('maintenance.index');
    });

    // Enhanced Inventory Management
    Route::prefix('inventory')->name('inventory.')->group(function () {
        Route::get('/', [InventoryController::class, 'index'])->name('index');
        Route::get('stock', [InventoryController::class, 'stock'])->name('stock.index');
        Route::get('purchases', [InventoryController::class, 'purchases'])->name('purchases.index');
        Route::get('suppliers', [InventoryController::class, 'suppliers'])->name('suppliers.index');
        Route::get('requests', [InventoryController::class, 'requests'])->name('requests.index');
        Route::get('tracking', [InventoryController::class, 'tracking'])->name('tracking.index');
        Route::get('reports', [InventoryController::class, 'reports'])->name('reports.index');
        Route::get('warehouse', [InventoryController::class, 'warehouse'])->name('warehouse.index');
        Route::get('/create', [InventoryController::class, 'create'])->name('create');
        Route::post('/', [InventoryController::class, 'store'])->name('store');
        Route::get('/{item}', [InventoryController::class, 'show'])->name('show');
        Route::get('/{item}/edit', [InventoryController::class, 'edit'])->name('edit');
        Route::put('/{item}', [InventoryController::class, 'update'])->name('update');
        Route::delete('/{item}', [InventoryController::class, 'destroy'])->name('destroy');
    });

    // Enhanced Committee Management
    Route::prefix('committee')->name('committee.')->group(function () {
        Route::get('/', [CommitteeController::class, 'index'])->name('index');
        Route::get('meetings', [CommitteeController::class, 'meetings'])->name('meetings.index');
        Route::get('agendas', [CommitteeController::class, 'agendas'])->name('agendas.index');
        Route::get('minutes', [CommitteeController::class, 'minutes'])->name('minutes.index');
        Route::get('members', [CommitteeController::class, 'members'])->name('members.index');
        Route::get('resolutions', [CommitteeController::class, 'resolutions'])->name('resolutions.index');
        Route::get('public', [CommitteeController::class, 'public'])->name('public.index');
        Route::get('archive', [CommitteeController::class, 'archive'])->name('archive.index');
    });

    // Enhanced Administration Routes
    Route::prefix('administration')->name('administration.')->group(function () {
        Route::get('crm', [CrmController::class, 'index'])->name('crm.index');
        Route::get('crm/customers', [CrmController::class, 'customers'])->name('crm.customers');
        Route::get('crm/customers/create', [CrmController::class, 'createCustomer'])->name('crm.customers.create');
        Route::post('crm/customers', [CrmController::class, 'storeCustomer'])->name('crm.customers.store');
        Route::get('service-requests', [CrmController::class, 'serviceRequests'])->name('service-requests.index');
        Route::get('service-requests/create', [CrmController::class, 'createServiceRequest'])->name('service-requests.create');
        Route::get('communications', [CrmController::class, 'communications'])->name('communications.index');
        Route::get('documents', [CrmController::class, 'documents'])->name('documents.index');
        Route::get('workflows', [CrmController::class, 'workflows'])->name('workflows.index');
        Route::get('portal', [CrmController::class, 'portal'])->name('portal.index');
        Route::get('feedback', [CrmController::class, 'feedback'])->name('feedback.index');
    });

    // Enhanced Housing Routes
    Route::prefix('housing')->name('housing.')->group(function () {
        Route::resource('applications', HousingApplicationController::class);
        Route::resource('properties', PropertyController::class);
        Route::get('waiting-list', [WaitingListController::class, 'index'])->name('waiting-list');
        Route::get('allocations', [\App\Http\Controllers\Housing\AllocationController::class, 'index'])->name('allocations.index');
        Route::get('cessions', [\App\Http\Controllers\Housing\CessionController::class, 'index'])->name('cessions.index');
        Route::get('stalls', [\App\Http\Controllers\Housing\StallController::class, 'index'])->name('stalls.index');
    });

    // Enhanced Planning Routes
    Route::prefix('planning')->name('planning.')->group(function () {
        Route::get('/', [PlanningController::class, 'index'])->name('index');
        Route::get('applications', [PlanningController::class, 'applications'])->name('applications.index');
        Route::get('building-plans', [PlanningController::class, 'buildingPlans'])->name('building-plans.index');
        Route::get('applications/create', [PlanningController::class, 'createApplication'])->name('applications.create');
        Route::post('applications', [PlanningController::class, 'storeApplication'])->name('applications.store');
        Route::get('applications/{application}', [PlanningController::class, 'showApplication'])->name('applications.show');
        Route::get('approvals', [PlanningController::class, 'approvals'])->name('approvals.index');
        Route::get('zoning', [PlanningController::class, 'zoning'])->name('zoning.index');
        Route::get('land-bank', [PlanningController::class, 'landBank'])->name('land-bank.index');
        Route::get('valuations', [PlanningController::class, 'valuations'])->name('valuations.index');
        Route::get('leases', [PlanningController::class, 'leases'])->name('leases.index');
        Route::get('subdivisions', [PlanningController::class, 'subdivisions'])->name('subdivisions.index');
    });

    // Service Requests
    Route::prefix('services')->group(function () {
        Route::get('/', [ServiceRequestController::class, 'index'])->name('services.index');
        Route::get('/create', [ServiceRequestController::class, 'create'])->name('services.create');
        Route::post('/store', [ServiceRequestController::class, 'store'])->name('services.store');
        Route::get('/{request}', [ServiceRequestController::class, 'show'])->name('services.show');
        Route::put('/{request}', [ServiceRequestController::class, 'update'])->name('services.update');
    });

    // Property Taxation
    Route::prefix('property-tax')->name('property-tax.')->group(function () {
        Route::get('/', [PropertyTaxationController::class, 'index'])->name('index');
        Route::get('/valuations', [PropertyTaxationController::class, 'valuations'])->name('valuations.index');
        Route::get('/reports', [PropertyTaxationController::class, 'reports'])->name('reports');
        Route::get('/reports/collection', [PropertyTaxationController::class, 'collectionReport'])->name('reports.collection');
    });

    // Parking Management routes
    Route::middleware(['auth'])->group(function () {
        Route::prefix('parking')->name('parking.')->group(function () {
            Route::get('/', [ParkingController::class, 'index'])->name('index');

            // Zones
            Route::get('/zones', [ParkingController::class, 'zones'])->name('zones.index');
            Route::get('/zones/create', [ParkingController::class, 'createZone'])->name('zones.create');
            Route::post('/zones', [ParkingController::class, 'storeZone'])->name('zones.store');

            // Violations
            Route::get('/violations', [ParkingController::class, 'violations'])->name('violations.index');
            Route::get('/violations/create', [ParkingController::class, 'createViolation'])->name('violations.create');
            Route::post('/violations', [ParkingController::class, 'storeViolation'])->name('violations.store');
            Route::get('/violations/{violation}', [ParkingController::class, 'showViolation'])->name('violations.show');
            Route::post('/violations/{violation}/payment', [ParkingController::class, 'processPayment'])->name('violations.payment');

            // Reports
            Route::get('/reports', [ParkingController::class, 'reports'])->name('reports');
        });
    });

    // Events Management
    Route::prefix('events')->name('events.')->group(function () {
        Route::get('/', [EventPermitController::class, 'index'])->name('index');

        // Event Permits
        Route::get('permits', [EventPermitController::class, 'permits'])->name('permits.index');
        Route::get('permits/create', [EventPermitController::class, 'createPermit'])->name('permits.create');
        Route::post('permits', [EventPermitController::class, 'storePermit'])->name('permits.store');
        Route::get('permits/{permit}', [EventPermitController::class, 'showPermit'])->name('permits.show');
        Route::put('permits/{permit}', [EventPermitController::class, 'updatePermit'])->name('permits.update');
        Route::patch('permits/{permit}/status', [EventPermitController::class, 'updateStatus'])->name('permits.updateStatus');
    });

    // Survey Services
    Route::prefix('survey')->name('survey.')->group(function () {
        Route::get('/', [SurveyController::class, 'index'])->name('index');
        Route::get('/dashboard', [SurveyController::class, 'dashboard'])->name('dashboard');

        // Survey Projects
        Route::prefix('projects')->name('projects.')->group(function () {
            Route::get('/', [SurveyController::class, 'projects'])->name('index');
            Route::get('/create', [SurveyController::class, 'createProject'])->name('create');
            Route::post('/', [SurveyController::class, 'storeProject'])->name('store');
            Route::get('/{project}', [SurveyController::class, 'showProject'])->name('show');
            Route::get('/{project}/edit', [SurveyController::class, 'editProject'])->name('edit');
            Route::put('/{project}', [SurveyController::class, 'updateProject'])->name('update');
        });

        // Survey Equipment
        Route::prefix('equipment')->name('equipment.')->group(function () {
            Route::get('/', [SurveyController::class, 'equipment'])->name('index');
            Route::get('/create', [SurveyController::class, 'createEquipment'])->name('create');
            Route::post('/', [SurveyController::class, 'storeEquipment'])->name('store');
        });

        // Survey Types
        Route::prefix('types')->name('types.')->group(function () {
            Route::get('/', [SurveyController::class, 'types'])->name('index');
            Route::get('/create', [SurveyController::class, 'createType'])->name('create');
            Route::post('/', [SurveyController::class, 'storeType'])->name('store');
        });
    });

    // Market Management
    Route::prefix('markets')->name('markets.')->group(function () {
        Route::get('/', [MarketController::class, 'index'])->name('index');
        Route::get('dashboard', [MarketController::class, 'dashboard'])->name('dashboard');
        Route::get('create', [MarketController::class, 'create'])->name('create');
        Route::post('/', [MarketController::class, 'store'])->name('store');
        Route::get('{market}', [MarketController::class, 'show'])->name('show');
        Route::get('{market}/edit', [MarketController::class, 'edit'])->name('edit');
        Route::put('{market}', [MarketController::class, 'update'])->name('update');
        Route::delete('{market}', [MarketController::class, 'destroy'])->name('destroy');

        // Market sub-resources
        Route::get('{market}/stalls', [MarketController::class, 'stalls'])->name('stalls');
        Route::get('{market}/allocations', [MarketController::class, 'allocations'])->name('allocations');
        Route::get('{market}/revenue', [MarketController::class, 'revenue'])->name('revenue');

        // API routes for AJAX
        Route::get('{market}/sections/json', [StallController::class, 'getSectionsByMarket'])->name('sections.json');
    });

    // Market Stalls
    Route::prefix('stalls')->name('markets.stalls.')->group(function () {
        Route::get('/', [StallController::class, 'index'])->name('index');
        Route::get('create/{market?}', [StallController::class, 'create'])->name('create');
        Route::post('/', [StallController::class, 'store'])->name('store');
        Route::get('{stall}', [StallController::class, 'show'])->name('show');
        Route::get('{stall}/edit', [StallController::class, 'edit'])->name('edit');
        Route::put('{stall}', [StallController::class, 'update'])->name('update');
        Route::delete('{stall}', [StallController::class, 'destroy'])->name('destroy');
    });

    // Municipal Billing
    Route::prefix('billing')->name('billing.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Billing\MunicipalBillingController::class, 'index'])->name('index');
        Route::get('/bills', [\App\Http\Controllers\Billing\MunicipalBillingController::class, 'bills'])->name('bills.index');
        Route::get('/bills/create', [\App\Http\Controllers\Billing\MunicipalBillingController::class, 'createBill'])->name('bills.create');
        Route::post('/bills', [\App\Http\Controllers\Billing\MunicipalBillingController::class, 'storeBill'])->name('bills.store');
        Route::get('/customers', [\App\Http\Controllers\Billing\MunicipalBillingController::class, 'customers'])->name('customers.index');
        Route::get('/customers/create', [\App\Http\Controllers\Billing\MunicipalBillingController::class, 'createCustomer'])->name('customers.create');
        Route::post('/customers', [\App\Http\Controllers\Billing\MunicipalBillingController::class, 'storeCustomer'])->name('customers.store');
    });

    // HR Management
    Route::prefix('hr')->name('hr.')->group(function () {
        Route::get('/', [HrController::class, 'index'])->name('index');
        Route::get('/employees', [HrController::class, 'employees'])->name('employees');
        Route::get('/attendance', [HrController::class, 'attendance'])->name('attendance.index');
        Route::post('/attendance/record', [HrController::class, 'recordAttendance'])->name('attendance.record');
        Route::get('/attendance/face-scan', function() {
            return view('hr.attendance.face-scan');
        })->name('attendance.face-scan');


        // Employee Management
        Route::prefix('employees')->name('employees.')->group(function () {
            Route::get('/', [HrController::class, 'employees'])->name('index');
            Route::get('/create', [HrController::class, 'createEmployee'])->name('create');
            Route::post('/', [HrController::class, 'storeEmployee'])->name('store');
            Route::get('/{employee}', [HrController::class, 'showEmployee'])->name('show');
            Route::get('/{employee}/edit', [HrController::class, 'editEmployee'])->name('edit');
            Route::put('/{employee}', [HrController::class, 'updateEmployee'])->name('update');
            Route::get('/{employee}/edit-salary', [HrController::class, 'editEmployeeSalary'])->name('edit-salary');
            Route::put('/{employee}/salary', [HrController::class, 'updateEmployeeSalary'])->name('update-salary');
        });

        // Face Recognition & Enrollment
        Route::prefix('face')->name('face.')->group(function () {
            Route::get('/enrollment', [HrController::class, 'faceEnrollment'])->name('enrollment');
            Route::post('/enroll', [HrController::class, 'enrollFace'])->name('enroll');
            Route::delete('/enrollment/{enrollment}', [HrController::class, 'deleteFaceEnrollment'])->name('delete-enrollment');
        });

        // Attendance Management
        Route::prefix('attendance')->name('attendance.')->group(function () {
            Route::get('/', [HrController::class, 'attendance'])->name('index');
            Route::get('/face-scan', [HrController::class, 'faceAttendance'])->name('face-scan');
            Route::post('/process-face', [HrController::class, 'processFaceAttendance'])->name('process-face');
            Route::get('/recent-face-scans', [HrController::class, 'recentFaceScans'])->name('recent-face-scans');
            Route::post('/manual', [HrController::class, 'recordManualAttendance'])->name('manual');
        });

        // Payroll Management
        Route::prefix('payroll')->name('payroll.')->group(function () {
            Route::get('/', [PayrollControllerHR::class, 'index'])->name('index');
            Route::get('/runs', [PayrollControllerHR::class, 'runs'])->name('runs');
            Route::post('/runs', [PayrollControllerHR::class, 'createRun'])->name('create-run');
            Route::get('/runs/{run}', [PayrollControllerHR::class, 'showRun'])->name('show-run');
            Route::post('/runs/{run}/process', [PayrollControllerHR::class, 'processRun'])->name('process-run');
        });

        // Currency Management
        Route::prefix('currency')->name('currency.')->group(function () {
            Route::get('/rates', [HrController::class, 'currencyRates'])->name('rates');
            Route::post('/rates', [HrController::class, 'updateCurrencyRates'])->name('update-rates');
        });
    });

    // Survey Services
    Route::prefix('survey')->name('survey.')->group(function () {
        Route::get('/', [SurveyController::class, 'index'])->name('index');
        Route::get('/dashboard', [SurveyController::class, 'dashboard'])->name('dashboard');

        // Survey Projects
        Route::prefix('projects')->name('projects.')->group(function () {
            Route::get('/', [SurveyController::class, 'projects'])->name('index');
            Route::get('/create', [SurveyController::class, 'createProject'])->name('create');
            Route::post('/', [SurveyController::class, 'storeProject'])->name('store');
            Route::get('/{project}', [SurveyController::class, 'showProject'])->name('show');
            Route::get('/{project}/edit', [SurveyController::class, 'editProject'])->name('edit');
            Route::put('/{project}', [SurveyController::class, 'updateProject'])->name('update');
        });

        // Survey Equipment
        Route::prefix('equipment')->name('equipment.')->group(function () {
            Route::get('/', [SurveyController::class, 'equipment'])->name('index');
            Route::get('/create', [SurveyController::class, 'createEquipment'])->name('create');
            Route::post('/', [SurveyController::class, 'storeEquipment'])->name('store');
        });

        // Survey Types
        Route::prefix('types')->name('types.')->group(function () {
            Route::get('/', [SurveyController::class, 'types'])->name('index');
            Route::get('/create', [SurveyController::class, 'createType'])->name('create');
            Route::post('/', [SurveyController::class, 'storeType'])->name('store');
        });
    });

    // Market Management
    Route::prefix('markets')->name('markets.')->group(function () {
        Route::get('/', [MarketController::class, 'index'])->name('index');
        Route::get('dashboard', [MarketController::class, 'dashboard'])->name('dashboard');
        Route::get('create', [MarketController::class, 'create'])->name('create');
        Route::post('/', [MarketController::class, 'store'])->name('store');
        Route::get('{market}', [MarketController::class, 'show'])->name('show');
        Route::get('{market}/edit', [MarketController::class, 'edit'])->name('edit');
        Route::put('{market}', [MarketController::class, 'update'])->name('update');
        Route::delete('{market}', [MarketController::class, 'destroy'])->name('destroy');

        // Market sub-resources
        Route::get('{market}/stalls', [MarketController::class, 'stalls'])->name('stalls');
        Route::get('{market}/allocations', [MarketController::class, 'allocations'])->name('allocations');
        Route::get('{market}/revenue', [MarketController::class, 'revenue'])->name('revenue');

        // API routes for AJAX
        Route::get('{market}/sections/json', [StallController::class, 'getSectionsByMarket'])->name('sections.json');
    });

    // Market Stalls
    Route::prefix('stalls')->name('markets.stalls.')->group(function () {
        Route::get('/', [StallController::class, 'index'])->name('index');
        Route::get('create/{market?}', [StallController::class, 'create'])->name('create');
        Route::post('/', [StallController::class, 'store'])->name('store');
        Route::get('{stall}', [StallController::class, 'show'])->name('show');
        Route::get('{stall}/edit', [StallController::class, 'edit'])->name('edit');
        Route::put('{stall}', [StallController::class, 'update'])->name('update');
        Route::delete('{stall}', [StallController::class, 'destroy'])->name('destroy');
    });

});

// Fallback route
Route::fallback(function () {
    return view('welcome');
});
@extends('layouts.council-admin')

@section('title', 'Module Management')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">Module Management</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('council-admin.dashboard') }}">Admin</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('council-admin.settings.index') }}">Settings</a></li>
                        <li class="breadcrumb-item active">Modules</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h5><i class="fas fa-cogs me-2"></i>System Module Management</h5>
                    <button class="btn btn-success btn-sm" onclick="saveAllModules()">
                        <i class="fas fa-save me-1"></i>Save All Changes
                    </button>
                </div>
                <div class="card-body">
                    <p class="mb-4">Configure modules and their individual features for the entire system. Changes will apply to all departments unless overridden at department level.</p>

                    <div class="accordion" id="moduleAccordion">
                        <!-- Housing Management Module -->
                        <div class="card mb-2">
                            <div class="card-header" id="headingHousing">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="form-check">
                                        <input class="form-check-input module-toggle" type="checkbox" value="housing"
                                               id="moduleHousing" checked onchange="toggleModule('housing')">
                                        <label class="form-check-label fw-bold" for="moduleHousing">
                                            <i class="fas fa-home me-2 text-primary"></i>Housing Management
                                        </label>
                                    </div>
                                    <button class="btn btn-link" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#collapseHousing">
                                        <i class="fas fa-chevron-down"></i>
                                    </button>
                                </div>
                            </div>
                            <div id="collapseHousing" class="collapse" data-bs-parent="#moduleAccordion">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h6 class="text-muted mb-3">Core Features</h6>
                                            <div class="form-check mb-2">
                                                <input class="form-check-input feature-toggle" type="checkbox"
                                                       data-module="housing" value="waiting_list" id="housingWaitingList" checked>
                                                <label class="form-check-label" for="housingWaitingList">
                                                    Waiting List Management
                                                </label>
                                            </div>
                                            <div class="form-check mb-2">
                                                <input class="form-check-input feature-toggle" type="checkbox"
                                                       data-module="housing" value="applications" id="housingApplications" checked>
                                                <label class="form-check-label" for="housingApplications">
                                                    Housing Applications
                                                </label>
                                            </div>
                                            <div class="form-check mb-2">
                                                <input class="form-check-input feature-toggle" type="checkbox"
                                                       data-module="housing" value="allocations" id="housingAllocations" checked>
                                                <label class="form-check-label" for="housingAllocations">
                                                    Property Allocations
                                                </label>
                                            </div>
                                            <div class="form-check mb-2">
                                                <input class="form-check-input feature-toggle" type="checkbox"
                                                       data-module="housing" value="properties" id="housingProperties" checked>
                                                <label class="form-check-label" for="housingProperties">
                                                    Property Management
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <h6 class="text-muted mb-3">Advanced Features</h6>
                                            <div class="form-check mb-2">
                                                <input class="form-check-input feature-toggle" type="checkbox"
                                                       data-module="housing" value="tenant_management" id="housingTenants" checked>
                                                <label class="form-check-label" for="housingTenants">
                                                    Tenant Management
                                                </label>
                                            </div>
                                            <div class="form-check mb-2">
                                                <input class="form-check-input feature-toggle" type="checkbox"
                                                       data-module="housing" value="maintenance_requests" id="housingMaintenance">
                                                <label class="form-check-label" for="housingMaintenance">
                                                    Maintenance Requests
                                                </label>
                                            </div>
                                            <div class="form-check mb-2">
                                                <input class="form-check-input feature-toggle" type="checkbox"
                                                       data-module="housing" value="inspections" id="housingInspections">
                                                <label class="form-check-label" for="housingInspections">
                                                    Property Inspections
                                                </label>
                                            </div>
                                            <div class="form-check mb-2">
                                                <input class="form-check-input feature-toggle" type="checkbox"
                                                       data-module="housing" value="reports" id="housingReports" checked>
                                                <label class="form-check-label" for="housingReports">
                                                    Housing Reports
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Finance Module -->
                        <div class="card mb-2">
                            <div class="card-header" id="headingFinance">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="form-check">
                                        <input class="form-check-input module-toggle" type="checkbox" value="finance"
                                               id="moduleFinance" checked onchange="toggleModule('finance')">
                                        <label class="form-check-label fw-bold" for="moduleFinance">
                                            <i class="fas fa-dollar-sign me-2 text-success"></i>Finance Management
                                        </label>
                                    </div>
                                    <button class="btn btn-link" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#collapseFinance">
                                        <i class="fas fa-chevron-down"></i>
                                    </button>
                                </div>
                            </div>
                            <div id="collapseFinance" class="collapse" data-bs-parent="#moduleAccordion">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h6 class="text-muted mb-3">Core Accounting</h6>
                                            <div class="form-check mb-2">
                                                <input class="form-check-input feature-toggle" type="checkbox"
                                                       data-module="finance" value="general_ledger" id="financeGL" checked>
                                                <label class="form-check-label" for="financeGL">
                                                    General Ledger
                                                </label>
                                            </div>
                                            <div class="form-check mb-2">
                                                <input class="form-check-input feature-toggle" type="checkbox"
                                                       data-module="finance" value="chart_accounts" id="financeCoA" checked>
                                                <label class="form-check-label" for="financeCoA">
                                                    Chart of Accounts
                                                </label>
                                            </div>
                                            <div class="form-check mb-2">
                                                <input class="form-check-input feature-toggle" type="checkbox"
                                                       data-module="finance" value="budgets" id="financeBudgets" checked>
                                                <label class="form-check-label" for="financeBudgets">
                                                    Budget Management
                                                </label>
                                            </div>
                                            <div class="form-check mb-2">
                                                <input class="form-check-input feature-toggle" type="checkbox"
                                                       data-module="finance" value="cash_management" id="financeCash" checked>
                                                <label class="form-check-label" for="financeCash">
                                                    Cash Management
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <h6 class="text-muted mb-3">Advanced Features</h6>
                                            <div class="form-check mb-2">
                                                <input class="form-check-input feature-toggle" type="checkbox"
                                                       data-module="finance" value="accounts_receivable" id="financeAR" checked>
                                                <label class="form-check-label" for="financeAR">
                                                    Accounts Receivable
                                                </label>
                                            </div>
                                            <div class="form-check mb-2">
                                                <input class="form-check-input feature-toggle" type="checkbox"
                                                       data-module="finance" value="accounts_payable" id="financeAP" checked>
                                                <label class="form-check-label" for="financeAP">
                                                    Accounts Payable
                                                </label>
                                            </div>
                                            <div class="form-check mb-2">
                                                <input class="form-check-input feature-toggle" type="checkbox"
                                                       data-module="finance" value="fixed_assets" id="financeFA">
                                                <label class="form-check-label" for="financeFA">
                                                    Fixed Assets
                                                </label>
                                            </div>
                                            <div class="form-check mb-2">
                                                <input class="form-check-input feature-toggle" type="checkbox"
                                                       data-module="finance" value="ipsas_compliance" id="financeIPSAS">
                                                <label class="form-check-label" for="financeIPSAS">
                                                    IPSAS Compliance
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Administration CRM Module -->
                        <div class="card mb-2">
                            <div class="card-header" id="headingAdmin">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="form-check">
                                        <input class="form-check-input module-toggle" type="checkbox" value="administration"
                                               id="moduleAdmin" checked onchange="toggleModule('administration')">
                                        <label class="form-check-label fw-bold" for="moduleAdmin">
                                            <i class="fas fa-users me-2 text-info"></i>Administration CRM
                                        </label>
                                    </div>
                                    <button class="btn btn-link" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#collapseAdmin">
                                        <i class="fas fa-chevron-down"></i>
                                    </button>
                                </div>
                            </div>
                            <div id="collapseAdmin" class="collapse" data-bs-parent="#moduleAccordion">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h6 class="text-muted mb-3">Customer Management</h6>
                                            <div class="form-check mb-2">
                                                <input class="form-check-input feature-toggle" type="checkbox"
                                                       data-module="administration" value="customer_registry" id="adminCustomers" checked>
                                                <label class="form-check-label" for="adminCustomers">
                                                    Customer Registry
                                                </label>
                                            </div>
                                            <div class="form-check mb-2">
                                                <input class="form-check-input feature-toggle" type="checkbox"
                                                       data-module="administration" value="service_requests" id="adminServiceRequests" checked>
                                                <label class="form-check-label" for="adminServiceRequests">
                                                    Service Requests
                                                </label>
                                            </div>
                                            <div class="form-check mb-2">
                                                <input class="form-check-input feature-toggle" type="checkbox"
                                                       data-module="administration" value="communications" id="adminComms" checked>
                                                <label class="form-check-label" for="adminComms">
                                                    Communications
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <h6 class="text-muted mb-3">Service Delivery</h6>
                                            <div class="form-check mb-2">
                                                <input class="form-check-input feature-toggle" type="checkbox"
                                                       data-module="administration" value="service_tracking" id="adminTracking">
                                                <label class="form-check-label" for="adminTracking">
                                                    Service Tracking
                                                </label>
                                            </div>
                                            <div class="form-check mb-2">
                                                <input class="form-check-input feature-toggle" type="checkbox"
                                                       data-module="administration" value="escalations" id="adminEscalations">
                                                <label class="form-check-label" for="adminEscalations">
                                                    Escalation Management
                                                </label>
                                            </div>
                                            <div class="form-check mb-2">
                                                <input class="form-check-input feature-toggle" type="checkbox"
                                                       data-module="administration" value="feedback" id="adminFeedback">
                                                <label class="form-check-label" for="adminFeedback">
                                                    Customer Feedback
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Committee Module -->
                        <div class="card mb-2">
                            <div class="card-header" id="headingCommittee">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="form-check">
                                        <input class="form-check-input module-toggle" type="checkbox" value="committee"
                                               id="moduleCommittee" checked onchange="toggleModule('committee')">
                                        <label class="form-check-label fw-bold" for="moduleCommittee">
                                            <i class="fas fa-sitemap me-2 text-warning"></i>Committee Management
                                        </label>
                                    </div>
                                    <button class="btn btn-link" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#collapseCommittee">
                                        <i class="fas fa-chevron-down"></i>
                                    </button>
                                </div>
                            </div>
                            <div id="collapseCommittee" class="collapse" data-bs-parent="#moduleAccordion">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h6 class="text-muted mb-3">Core Features</h6>
                                            <div class="form-check mb-2">
                                                <input class="form-check-input feature-toggle" type="checkbox"
                                                       data-module="committee" value="committee_management" id="committeeManagement" checked>
                                                <label class="form-check-label" for="committeeManagement">
                                                    Committee Structure
                                                </label>
                                            </div>
                                            <div class="form-check mb-2">
                                                <input class="form-check-input feature-toggle" type="checkbox"
                                                       data-module="committee" value="member_management" id="committeeMemberMgmt" checked>
                                                <label class="form-check-label" for="committeeMemberMgmt">
                                                    Member Management
                                                </label>
                                            </div>
                                            <div class="form-check mb-2">
                                                <input class="form-check-input feature-toggle" type="checkbox"
                                                       data-module="committee" value="meeting_scheduling" id="committeeMeetings" checked>
                                                <label class="form-check-label" for="committeeMeetings">
                                                    Meeting Scheduling
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <h6 class="text-muted mb-3">Documentation</h6>
                                            <div class="form-check mb-2">
                                                <input class="form-check-input feature-toggle" type="checkbox"
                                                       data-module="committee" value="agenda_management" id="committeeAgendas" checked>
                                                <label class="form-check-label" for="committeeAgendas">
                                                    Agenda Management
                                                </label>
                                            </div>
                                            <div class="form-check mb-2">
                                                <input class="form-check-input feature-toggle" type="checkbox"
                                                       data-module="committee" value="minutes_recording" id="committeeMinutes" checked>
                                                <label class="form-check-label" for="committeeMinutes">
                                                    Minutes Recording
                                                </label>
                                            </div>
                                            <div class="form-check mb-2">
                                                <input class="form-check-input feature-toggle" type="checkbox"
                                                       data-module="committee" value="resolutions" id="committeeResolutions">
                                                <label class="form-check-label" for="committeeResolutions">
                                                    Resolution Tracking
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Water Management Module -->
                        <div class="card mb-2">
                            <div class="card-header" id="headingWater">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="form-check">
                                        <input class="form-check-input module-toggle" type="checkbox" value="water"
                                               id="moduleWater" onchange="toggleModule('water')">
                                        <label class="form-check-label fw-bold" for="moduleWater">
                                            <i class="fas fa-tint me-2 text-primary"></i>Water Management
                                        </label>
                                    </div>
                                    <button class="btn btn-link" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#collapseWater">
                                        <i class="fas fa-chevron-down"></i>
                                    </button>
                                </div>
                            </div>
                            <div id="collapseWater" class="collapse" data-bs-parent="#moduleAccordion">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h6 class="text-muted mb-3">Connection Management</h6>
                                            <div class="form-check mb-2">
                                                <input class="form-check-input feature-toggle" type="checkbox"
                                                       data-module="water" value="connections" id="waterConnections">
                                                <label class="form-check-label" for="waterConnections">
                                                    Water Connections
                                                </label>
                                            </div>
                                            <div class="form-check mb-2">
                                                <input class="form-check-input feature-toggle" type="checkbox"
                                                       data-module="water" value="meter_management" id="waterMeters">
                                                <label class="form-check-label" for="waterMeters">
                                                    Meter Management
                                                </label>
                                            </div>
                                            <div class="form-check mb-2">
                                                <input class="form-check-input feature-toggle" type="checkbox"
                                                       data-module="water" value="billing" id="waterBilling">
                                                <label class="form-check-label" for="waterBilling">
                                                    Water Billing
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <h6 class="text-muted mb-3">Quality & Infrastructure</h6>
                                            <div class="form-check mb-2">
                                                <input class="form-check-input feature-toggle" type="checkbox"
                                                       data-module="water" value="quality_testing" id="waterQuality">
                                                <label class="form-check-label" for="waterQuality">
                                                    Quality Testing
                                                </label>
                                            </div>
                                            <div class="form-check mb-2">
                                                <input class="form-check-input feature-toggle" type="checkbox"
                                                       data-module="water" value="infrastructure" id="waterInfrastructure">
                                                <label class="form-check-label" for="waterInfrastructure">
                                                    Infrastructure Management
                                                </label>
                                            </div>
                                            <div class="form-check mb-2">
                                                <input class="form-check-input feature-toggle" type="checkbox"
                                                       data-module="water" value="rate_management" id="waterRates">
                                                <label class="form-check-label" for="waterRates">
                                                    Rate Management
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Additional modules can be added here -->

                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Feature Access Summary -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5><i class="fas fa-chart-pie me-2"></i>Module Status Overview</h5>
                </div>
                <div class="card-body">
                    <div class="row" id="moduleStatusOverview">
                        <!-- Will be populated by JavaScript -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function toggleModule(moduleId) {
    const moduleCheckbox = document.getElementById('module' + moduleId.charAt(0).toUpperCase() + moduleId.slice(1));
    const featureToggles = document.querySelectorAll(`input[data-module="${moduleId}"]`);

    featureToggles.forEach(toggle => {
        toggle.disabled = !moduleCheckbox.checked;
        if (!moduleCheckbox.checked) {
            toggle.checked = false;
        }
    });

    updateModuleStatusOverview();
}

function saveAllModules() {
    const moduleSettings = {};

    document.querySelectorAll('.module-toggle').forEach(moduleToggle => {
        const moduleId = moduleToggle.value;
        const features = {};

        document.querySelectorAll(`input[data-module="${moduleId}"]`).forEach(featureToggle => {
            features[featureToggle.value] = featureToggle.checked;
        });

        moduleSettings[moduleId] = {
            enabled: moduleToggle.checked,
            features: features
        };
    });

    // Send to server
    fetch('/council-admin/settings/modules/save', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify(moduleSettings)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Module settings saved successfully!');
        } else {
            alert('Error saving settings: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error saving settings');
    });
}

function updateModuleStatusOverview() {
    const overview = document.getElementById('moduleStatusOverview');
    overview.innerHTML = '';

    document.querySelectorAll('.module-toggle').forEach(moduleToggle => {
        const moduleId = moduleToggle.value;
        const moduleName = moduleToggle.nextElementSibling.textContent.trim();
        const isEnabled = moduleToggle.checked;

        const enabledFeatures = document.querySelectorAll(`input[data-module="${moduleId}"]:checked`).length;
        const totalFeatures = document.querySelectorAll(`input[data-module="${moduleId}"]`).length;

        const statusDiv = document.createElement('div');
        statusDiv.className = 'col-md-4 mb-3';
        statusDiv.innerHTML = `
            <div class="card ${isEnabled ? 'border-success' : 'border-secondary'} h-100">
                <div class="card-body text-center">
                    <h6 class="card-title">${moduleName}</h6>
                    <div class="badge ${isEnabled ? 'bg-success' : 'bg-secondary'} mb-2">
                        ${isEnabled ? 'Enabled' : 'Disabled'}
                    </div>
                    <p class="card-text small">
                        ${enabledFeatures}/${totalFeatures} features active
                    </p>
                    <div class="progress" style="height: 6px;">
                        <div class="progress-bar ${isEnabled ? 'bg-success' : 'bg-secondary'}"
                             style="width: ${totalFeatures > 0 ? (enabledFeatures/totalFeatures)*100 : 0}%"></div>
                    </div>
                </div>
            </div>
        `;
        overview.appendChild(statusDiv);
    });
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    // Set up feature toggle listeners
    document.querySelectorAll('.feature-toggle').forEach(toggle => {
        toggle.addEventListener('change', updateModuleStatusOverview);
    });

    // Initialize module states
    document.querySelectorAll('.module-toggle').forEach(moduleToggle => {
        toggleModule(moduleToggle.value);
    });

    updateModuleStatusOverview();
});
</script>

<style>
.feature-toggle:disabled + label {
    color: #6c757d;
    opacity: 0.6;
}

.card-header .btn-link {
    color: #6c757d;
    text-decoration: none;
    padding: 0;
}

.card-header .btn-link:hover {
    color: #495057;
}

.accordion .card {
    border: 1px solid #dee2e6;
}

.progress {
    background-color: #e9ecef;
}
</style>
@endsection
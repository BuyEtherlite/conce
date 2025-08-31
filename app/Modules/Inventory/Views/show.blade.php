@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2><i class="bi bi-box"></i> {{ $item->name }}</h2>
                <div>
                    <a href="{{ route('inventory.edit', $item) }}" class="btn btn-warning">
                        <i class="bi bi-pencil"></i> Edit
                    </a>
                    <a href="{{ route('inventory.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Back to Inventory
                    </a>
                </div>
            </div>

            <div class="row">
                <!-- Item Details -->
                <div class="col-md-8">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5><i class="bi bi-info-circle"></i> Item Details</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <table class="table table-borderless">
                                        <tr>
                                            <td class="fw-bold">Name:</td>
                                            <td>{{ $item->name }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Category:</td>
                                            <td><span class="badge bg-secondary">{{ $item->category }}</span></td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Unit of Measure:</td>
                                            <td>{{ $item->unit_of_measure }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Location:</td>
                                            <td>{{ $item->location ?? 'Not specified' }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Council:</td>
                                            <td>{{ $item->council->name ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Department:</td>
                                            <td>{{ $item->department->name ?? 'N/A' }}</td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-md-6">
                                    <table class="table table-borderless">
                                        <tr>
                                            <td class="fw-bold">Unit Cost:</td>
                                            <td>${{ number_format($item->unit_cost, 2) }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Total Value:</td>
                                            <td class="fw-bold text-success">${{ number_format($item->total_value, 2) }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Supplier:</td>
                                            <td>{{ $item->supplier_name ?? 'Not specified' }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Supplier Contact:</td>
                                            <td>{{ $item->supplier_contact ?? 'Not specified' }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Expiry Date:</td>
                                            <td>
                                                @if($item->expiry_date)
                                                    {{ $item->expiry_date->format('M d, Y') }}
                                                    @if($item->isExpiringSoon())
                                                        <span class="badge bg-warning ms-1">Expiring Soon</span>
                                                    @endif
                                                @else
                                                    No expiry date
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Status:</td>
                                            <td>
                                                @if($item->isOutOfStock())
                                                    <span class="badge bg-danger">Out of Stock</span>
                                                @elseif($item->isLowStock())
                                                    <span class="badge bg-warning">Low Stock</span>
                                                @else
                                                    <span class="badge bg-success">In Stock</span>
                                                @endif
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            @if($item->description)
                                <div class="mt-3">
                                    <h6>Description:</h6>
                                    <p class="text-muted">{{ $item->description }}</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Recent Transactions -->
                    <div class="card">
                        <div class="card-header">
                            <h5><i class="bi bi-clock-history"></i> Recent Transactions</h5>
                        </div>
                        <div class="card-body">
                            @php
                                $recentTransactions = $item->getRecentTransactions(10);
                            @endphp
                            
                            @if($recentTransactions->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>Date</th>
                                                <th>Type</th>
                                                <th>Quantity</th>
                                                <th>Unit Cost</th>
                                                <th>User</th>
                                                <th>Notes</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($recentTransactions as $transaction)
                                                <tr>
                                                    <td>{{ $transaction->created_at->format('M d, Y H:i') }}</td>
                                                    <td>
                                                        @switch($transaction->type)
                                                            @case('in')
                                                                <span class="badge bg-success">Stock In</span>
                                                                @break
                                                            @case('out')
                                                                <span class="badge bg-danger">Stock Out</span>
                                                                @break
                                                            @case('adjustment')
                                                                <span class="badge bg-warning">Adjustment</span>
                                                                @break
                                                            @case('transfer')
                                                                <span class="badge bg-info">Transfer</span>
                                                                @break
                                                        @endswitch
                                                    </td>
                                                    <td class="{{ $transaction->quantity > 0 ? 'text-success' : 'text-danger' }}">
                                                        {{ $transaction->quantity > 0 ? '+' : '' }}{{ number_format($transaction->quantity) }}
                                                    </td>
                                                    <td>${{ number_format($transaction->unit_cost, 2) }}</td>
                                                    <td>{{ $transaction->user->name ?? 'System' }}</td>
                                                    <td>{{ $transaction->notes ?? '-' }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <p class="text-muted text-center">No transactions recorded yet.</p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Stock Information & Actions -->
                <div class="col-md-4">
                    <!-- Stock Levels -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5><i class="bi bi-bar-chart"></i> Stock Levels</h5>
                        </div>
                        <div class="card-body">
                            <div class="row text-center">
                                <div class="col-4">
                                    <h4 class="text-primary">{{ number_format($item->current_stock) }}</h4>
                                    <small class="text-muted">Current</small>
                                </div>
                                <div class="col-4">
                                    <h4 class="text-warning">{{ number_format($item->minimum_stock) }}</h4>
                                    <small class="text-muted">Minimum</small>
                                </div>
                                <div class="col-4">
                                    <h4 class="text-success">{{ number_format($item->maximum_stock) }}</h4>
                                    <small class="text-muted">Maximum</small>
                                </div>
                            </div>
                            
                            <!-- Stock Progress Bar -->
                            <div class="mt-3">
                                <label class="form-label">Stock Level</label>
                                <div class="progress">
                                    <div class="progress-bar {{ $item->isLowStock() ? 'bg-warning' : 'bg-success' }}" 
                                         style="width: {{ min(100, $item->stock_percentage) }}%">
                                        {{ number_format($item->stock_percentage, 1) }}%
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5><i class="bi bi-lightning"></i> Quick Actions</h5>
                        </div>
                        <div class="card-body">
                            <!-- Stock In Form -->
                            <form action="{{ route('inventory.stock.in', $item) }}" method="POST" class="mb-3">
                                @csrf
                                <div class="input-group input-group-sm mb-2">
                                    <input type="number" class="form-control" name="quantity" placeholder="Quantity" min="1" required>
                                    <button type="submit" class="btn btn-success">
                                        <i class="bi bi-plus"></i> Stock In
                                    </button>
                                </div>
                                <input type="text" class="form-control form-control-sm" name="notes" placeholder="Notes (optional)">
                            </form>

                            <!-- Stock Out Form -->
                            <form action="{{ route('inventory.stock.out', $item) }}" method="POST" class="mb-3">
                                @csrf
                                <div class="input-group input-group-sm mb-2">
                                    <input type="number" class="form-control" name="quantity" placeholder="Quantity" 
                                           min="1" max="{{ $item->current_stock }}" required>
                                    <button type="submit" class="btn btn-danger">
                                        <i class="bi bi-dash"></i> Stock Out
                                    </button>
                                </div>
                                <input type="text" class="form-control form-control-sm" name="notes" placeholder="Notes (optional)">
                            </form>

                            <div class="d-grid gap-2">
                                <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#adjustModal">
                                    <i class="bi bi-gear"></i> Adjust Stock
                                </button>
                                <a href="{{ route('inventory.stock.transactions') }}?item_id={{ $item->id }}" class="btn btn-info btn-sm">
                                    <i class="bi bi-list"></i> View All Transactions
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Alerts -->
                    @if($item->isOutOfStock() || $item->isLowStock() || $item->isExpiringSoon())
                        <div class="card">
                            <div class="card-header">
                                <h5><i class="bi bi-exclamation-triangle"></i> Alerts</h5>
                            </div>
                            <div class="card-body">
                                @if($item->isOutOfStock())
                                    <div class="alert alert-danger alert-sm">
                                        <i class="bi bi-x-circle"></i> Item is out of stock
                                    </div>
                                @elseif($item->isLowStock())
                                    <div class="alert alert-warning alert-sm">
                                        <i class="bi bi-exclamation-triangle"></i> Stock level is below minimum
                                    </div>
                                @endif

                                @if($item->isExpiringSoon())
                                    <div class="alert alert-warning alert-sm">
                                        <i class="bi bi-calendar-x"></i> Item expires on {{ $item->expiry_date->format('M d, Y') }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Stock Adjustment Modal -->
<div class="modal fade" id="adjustModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('inventory.stock.adjust') }}" method="POST">
                @csrf
                <input type="hidden" name="item_id" value="{{ $item->id }}">
                <div class="modal-header">
                    <h5 class="modal-title">Adjust Stock</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="adjustment_type" class="form-label">Adjustment Type</label>
                        <select class="form-select" id="adjustment_type" name="adjustment_type" required>
                            <option value="">Select type</option>
                            <option value="increase">Increase Stock</option>
                            <option value="decrease">Decrease Stock</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="quantity" class="form-label">Quantity</label>
                        <input type="number" class="form-control" id="quantity" name="quantity" min="1" required>
                        <div class="form-text">Current stock: {{ number_format($item->current_stock) }}</div>
                    </div>
                    <div class="mb-3">
                        <label for="reason" class="form-label">Reason</label>
                        <textarea class="form-control" id="reason" name="reason" rows="3" placeholder="Explain the reason for this adjustment" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning">Adjust Stock</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

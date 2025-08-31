<div class="budget-variance-report">
    <h4>Budget Variance Report</h4>
    <p><strong>Period:</strong> {{ $data['period'] ?? 'N/A' }}</p>

    @if(isset($data['variance_data']) && count($data['variance_data']) > 0)
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Account</th>
                        <th class="text-right">Budgeted</th>
                        <th class="text-right">Actual</th>
                        <th class="text-right">Variance</th>
                        <th class="text-right">% Variance</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data['variance_data'] as $item)
                        @php
                            $variancePercent = $item['budgeted'] != 0 ? (($item['variance'] / $item['budgeted']) * 100) : 0;
                            $isUnfavorable = $item['variance'] < 0;
                        @endphp
                        <tr>
                            <td>{{ $item['account_name'] }}</td>
                            <td class="text-right">{{ number_format($item['budgeted'], 2) }}</td>
                            <td class="text-right">{{ number_format($item['actual'], 2) }}</td>
                            <td class="text-right {{ $isUnfavorable ? 'text-danger' : 'text-success' }}">
                                {{ number_format($item['variance'], 2) }}
                            </td>
                            <td class="text-right {{ $isUnfavorable ? 'text-danger' : 'text-success' }}">
                                {{ number_format($variancePercent, 1) }}%
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="alert alert-warning">
            No budget variance data available for the selected period.
        </div>
    @endif
</div>

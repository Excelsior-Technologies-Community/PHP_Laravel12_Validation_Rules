<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>⚡ Validation Analytics & Heatmap Studio</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body { background: #f4f6f9; font-family: 'Segoe UI', system-ui, sans-serif; }
        .stat-card { border: none; border-radius: 15px; color: #fff; min-height: 135px; box-shadow: 0 5px 18px rgba(0,0,0,0.08); }
        .stat-card .number { font-size: 30px; font-weight: 700; }
        .stat-card .label { font-size: 14px; opacity: 0.9; }
        .card-total { background: linear-gradient(135deg, #4f46e5, #3730a3); }
        .card-attempts { background: linear-gradient(135deg, #0284c7, #0369a1); }
        .card-success { background: linear-gradient(135deg, #10b981, #047857); }
        .card-failure { background: linear-gradient(135deg, #ef4444, #b91c1c); }
        .card-bot { background: linear-gradient(135deg, #f59e0b, #d97706); }
        .dashboard-card { border: none; border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.06); }
        .dashboard-card .card-header { background: #fff; border-bottom: 1px solid #eee; font-weight: 700; padding: 16px 20px; }
    </style>

</head>

<body>

<nav class="navbar navbar-dark bg-dark shadow-sm">

    <div class="container">

        <a href="{{ route('validation.dashboard') }}" class="navbar-brand fw-bold">

            ⚡ Validation Rules Analytics Studio

        </a>


        <div class="d-flex gap-2">

            <a href="{{ route('order.create') }}" class="btn btn-primary btn-sm">

                ➕ Create Order

            </a>


            <a href="{{ route('orders.index') }}" class="btn btn-light btn-sm">

                📦 Orders List

            </a>


            <a href="{{ route('validation.history') }}" class="btn btn-warning btn-sm">

                📋 Security History

            </a>

        </div>

    </div>

</nav>


<div class="container py-4">

    {{-- Anomaly Alert Banner --}}

    @if($suspiciousFailuresCount > 0)

        <div class="alert alert-danger shadow-sm border-0 d-flex justify-content-between align-items-center mb-4">

            <div>

                <h5 class="fw-bold mb-1">

                    🚨 Bot Anomaly & Spam Threat Detected!

                </h5>


                <p class="mb-0">

                    Found <strong>{{ $suspiciousFailuresCount }}</strong> suspicious validation failure logs from high-frequency IP addresses.

                </p>

            </div>


            <a href="{{ route('validation.history', ['filter' => 'suspicious']) }}" class="btn btn-light text-danger fw-bold">

                Review Suspicious Logs →

            </a>

        </div>

    @endif


    {{-- Page Header --}}

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h2 class="fw-bold mb-1">

                Validation Analytics & Anomaly Studio

            </h2>


            <p class="text-muted mb-0">

                Monitor rule execution metrics, field error heatmaps, and security anomaly detection.

            </p>

        </div>


        <div>

            <a href="{{ route('validation.dashboard') }}" class="btn btn-outline-dark">

                🔄 Refresh Data

            </a>

        </div>

    </div>


    {{-- MAIN STATS --}}

    <div class="row g-4 mb-4">

        <div class="col-md-6 col-lg-3">

            <div class="stat-card card-total p-4">

                <div class="d-flex justify-content-between align-items-center">

                    <div>

                        <div class="label">Total Orders</div>

                        <div class="number">{{ number_format($totalOrders) }}</div>

                    </div>

                    <div class="fs-1">📦</div>

                </div>

            </div>

        </div>


        <div class="col-md-6 col-lg-3">

            <div class="stat-card card-attempts p-4">

                <div class="d-flex justify-content-between align-items-center">

                    <div>

                        <div class="label">Validation Attempts</div>

                        <div class="number">{{ number_format($totalValidationAttempts) }}</div>

                    </div>

                    <div class="fs-1">🔎</div>

                </div>

            </div>

        </div>


        <div class="col-md-6 col-lg-3">

            <div class="stat-card card-failure p-4">

                <div class="d-flex justify-content-between align-items-center">

                    <div>

                        <div class="label">Validation Failures</div>

                        <div class="number">{{ number_format($validationFailures) }}</div>

                    </div>

                    <div class="fs-1">❌</div>

                </div>

            </div>

        </div>


        <div class="col-md-6 col-lg-3">

            <div class="stat-card card-bot p-4">

                <div class="d-flex justify-content-between align-items-center">

                    <div>

                        <div class="label">Bot Anomaly Flags</div>

                        <div class="number">{{ number_format($suspiciousFailuresCount) }}</div>

                    </div>

                    <div class="fs-1">🚨</div>

                </div>

            </div>

        </div>

    </div>


    {{-- FEATURE 3: FIELD ERROR BREAKDOWN HEATMAP STUDIO --}}

    <div class="row g-4 mb-4">

        <div class="col-lg-7">

            <div class="card dashboard-card h-100">

                <div class="card-header bg-white d-flex justify-content-between align-items-center">

                    <span class="fs-5">

                        🔥 Field Error Breakdown (Validation Heatmap)

                    </span>


                    <span class="badge bg-danger">

                        Real-Time Frequency

                    </span>

                </div>


                <div class="card-body">

                    @forelse($fieldBreakdown as $item)

                        <div class="mb-3">

                            <div class="d-flex justify-content-between align-items-center mb-1">

                                <strong class="text-dark">{{ $item['field'] }}</strong>

                                <span class="badge bg-secondary">

                                    {{ $item['count'] }} failure(s) ({{ $item['percentage'] }}%)

                                </span>

                            </div>


                            <div class="progress" style="height: 12px;">

                                <div class="progress-bar bg-danger progress-bar-striped progress-bar-animated" role="progressbar" style="width: {{ $item['percentage'] }}%"></div>

                            </div>

                        </div>

                    @empty

                        <div class="text-center py-4 text-muted">

                            No validation failures recorded yet. Form validations are passing cleanly!

                        </div>

                    @endforelse

                </div>

            </div>

        </div>


        {{-- SUSPICIOUS BOT IP ACTIVITY TABLE --}}

        <div class="col-lg-5">

            <div class="card dashboard-card h-100">

                <div class="card-header bg-white d-flex justify-content-between align-items-center">

                    <span class="fs-5">

                        🤖 Suspicious IP Activity

                    </span>


                    <span class="badge bg-warning text-dark">

                        Bot Anomaly Flags

                    </span>

                </div>


                <div class="card-body">

                    @if(count($suspiciousIps) > 0)

                        <div class="table-responsive">

                            <table class="table table-hover align-middle">

                                <thead>

                                    <tr>

                                        <th>IP Address</th>

                                        <th>Failures</th>

                                        <th>Threat Status</th>

                                    </tr>

                                </thead>


                                <tbody>

                                    @foreach($suspiciousIps as $ipData)

                                        <tr>

                                            <td>

                                                <code class="fw-bold text-dark">{{ $ipData->ip_address }}</code>

                                            </td>


                                            <td>

                                                <span class="badge bg-danger fs-6">{{ $ipData->total_failures }}</span>

                                            </td>


                                            <td>

                                                <span class="badge bg-warning text-dark">High Frequency</span>

                                            </td>

                                        </tr>

                                    @endforeach

                                </tbody>

                            </table>

                        </div>

                    @else

                        <div class="text-center py-4 text-muted">

                            No suspicious bot IP activity detected.

                        </div>

                    @endif

                </div>

            </div>

        </div>

    </div>


    {{-- RECENT ORDERS & FAILURES --}}

    <div class="row g-4">

        <div class="col-lg-6">

            <div class="card dashboard-card">

                <div class="card-header bg-white">

                    Recent Orders

                </div>


                <div class="card-body p-0">

                    <div class="table-responsive">

                        <table class="table table-hover mb-0 align-middle">

                            <thead>

                                <tr>

                                    <th>#</th>

                                    <th>Country</th>

                                    <th>Currency</th>

                                    <th>GSTIN</th>

                                    <th>Status</th>

                                </tr>

                            </thead>


                            <tbody>

                                @forelse($recentOrders as $order)

                                    <tr>

                                        <td>#{{ $order->id }}</td>

                                        <td><span class="badge bg-secondary">{{ $order->country }}</span></td>

                                        <td><span class="badge bg-info text-dark">{{ $order->currency }}</span></td>

                                        <td><small class="badge bg-dark text-warning">{{ $order->gstin ?: 'N/A' }}</small></td>

                                        <td>

                                            <span class="badge bg-{{ $order->status === 'delivered' ? 'success' : ($order->status === 'processing' ? 'primary' : 'warning text-dark') }}">

                                                {{ ucfirst($order->status) }}

                                            </span>

                                        </td>

                                    </tr>

                                @empty

                                    <tr><td colspan="5" class="text-center text-muted">No orders created yet.</td></tr>

                                @endforelse

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>

        </div>


        <div class="col-lg-6">

            <div class="card dashboard-card">

                <div class="card-header bg-white">

                    Recent Validation Failures

                </div>


                <div class="card-body p-0">

                    <div class="table-responsive">

                        <table class="table table-hover mb-0 align-middle">

                            <thead>

                                <tr>

                                    <th>#</th>

                                    <th>Failed Fields</th>

                                    <th>IP Address</th>

                                    <th>Time</th>

                                </tr>

                            </thead>


                            <tbody>

                                @forelse($recentFailures as $failure)

                                    <tr>

                                        <td>#{{ $failure->id }}</td>

                                        <td>

                                            @foreach($failure->failed_fields as $f)

                                                <span class="badge bg-danger me-1">{{ $f }}</span>

                                            @endforeach

                                        </td>

                                        <td><code>{{ $failure->ip_address }}</code></td>

                                        <td><small>{{ $failure->created_at->diffForHumans() }}</small></td>

                                    </tr>

                                @empty

                                    <tr><td colspan="4" class="text-center text-muted">No recent validation failures.</td></tr>

                                @endforelse

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

</body>

</html>
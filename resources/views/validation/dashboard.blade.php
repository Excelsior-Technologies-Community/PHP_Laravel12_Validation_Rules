<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Validation Dashboard</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <style>
        body {
            background: #f4f6f9;
            font-family: Arial, sans-serif;
        }

        .navbar-brand {
            font-weight: 700;
        }

        .dashboard-title {
            font-weight: 700;
            color: #212529;
        }

        .stat-card {
            border: none;
            border-radius: 15px;
            color: #fff;
            min-height: 145px;
            box-shadow: 0 5px 18px rgba(0, 0, 0, 0.08);
        }

        .stat-card .number {
            font-size: 32px;
            font-weight: 700;
        }

        .stat-card .label {
            font-size: 15px;
            opacity: 0.9;
        }

        .card-total {
            background: linear-gradient(135deg, #667eea, #764ba2);
        }

        .card-attempts {
            background: linear-gradient(135deg, #11998e, #38ef7d);
        }

        .card-success {
            background: linear-gradient(135deg, #16a085, #2ecc71);
        }

        .card-failure {
            background: linear-gradient(135deg, #e74c3c, #c0392b);
        }

        .card-pending {
            background: linear-gradient(135deg, #f39c12, #f1c40f);
        }

        .card-processing {
            background: linear-gradient(135deg, #2980b9, #3498db);
        }

        .card-delivered {
            background: linear-gradient(135deg, #27ae60, #2ecc71);
        }

        .card-today {
            background: linear-gradient(135deg, #8e44ad, #9b59b6);
        }

        .card-week {
            background: linear-gradient(135deg, #34495e, #2c3e50);
        }

        .dashboard-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.07);
        }

        .dashboard-card .card-header {
            background: #fff;
            border-bottom: 1px solid #eee;
            font-weight: 700;
            padding: 16px 20px;
        }

        .table th {
            white-space: nowrap;
        }

        .badge-status {
            padding: 7px 11px;
            border-radius: 20px;
            font-size: 12px;
        }

        .empty-state {
            padding: 30px;
            text-align: center;
            color: #777;
        }

        .stat-icon {
            font-size: 30px;
        }

        .percentage-box {
            font-size: 14px;
            margin-top: 5px;
            opacity: 0.9;
        }
    </style>
</head>

<body>

<nav class="navbar navbar-dark bg-dark shadow-sm">
    <div class="container">
        <a
            href="{{ route('validation.dashboard') }}"
            class="navbar-brand"
        >
            ⚡ Validation Rules Dashboard
        </a>

        <div class="d-flex gap-2">

            <a
                href="{{ route('order.create') }}"
                class="btn btn-primary btn-sm"
            >
                ➕ Create Order
            </a>

            <a
                href="{{ route('orders.index') }}"
                class="btn btn-light btn-sm"
            >
                📦 Orders
            </a>

            <a
                href="{{ route('validation.history') }}"
                class="btn btn-warning btn-sm"
            >
                📋 Validation History
            </a>

        </div>
    </div>
</nav>


<div class="container py-4">

    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h2 class="dashboard-title mb-1">
                Validation Dashboard
            </h2>

            <p class="text-muted mb-0">
                Monitor orders, validation attempts and validation failures
            </p>
        </div>

        <div>
            <a
                href="{{ route('validation.dashboard') }}"
                class="btn btn-outline-dark"
            >
                🔄 Refresh
            </a>
        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- MAIN STATISTICS --}}
    {{-- ========================================================= --}}

    <div class="row g-4 mb-4">

        {{-- Total Orders --}}
        <div class="col-md-6 col-lg-3">

            <div class="stat-card card-total p-4">

                <div class="d-flex justify-content-between">

                    <div>
                        <div class="label">
                            Total Orders
                        </div>

                        <div class="number">
                            {{ number_format($totalOrders) }}
                        </div>
                    </div>

                    <div class="stat-icon">
                        📦
                    </div>

                </div>

            </div>

        </div>


        {{-- Validation Attempts --}}
        <div class="col-md-6 col-lg-3">

            <div class="stat-card card-attempts p-4">

                <div class="d-flex justify-content-between">

                    <div>
                        <div class="label">
                            Validation Attempts
                        </div>

                        <div class="number">
                            {{ number_format($totalValidationAttempts) }}
                        </div>
                    </div>

                    <div class="stat-icon">
                        🔎
                    </div>

                </div>

            </div>

        </div>


        {{-- Successful Validations --}}
        <div class="col-md-6 col-lg-3">

            <div class="stat-card card-success p-4">

                <div class="d-flex justify-content-between">

                    <div>
                        <div class="label">
                            Successful Validations
                        </div>

                        <div class="number">
                            {{ number_format($successfulValidations) }}
                        </div>
                    </div>

                    <div class="stat-icon">
                        ✅
                    </div>

                </div>

            </div>

        </div>


        {{-- Validation Failures --}}
        <div class="col-md-6 col-lg-3">

            <div class="stat-card card-failure p-4">

                <div class="d-flex justify-content-between">

                    <div>
                        <div class="label">
                            Validation Failures
                        </div>

                        <div class="number">
                            {{ number_format($validationFailures) }}
                        </div>
                    </div>

                    <div class="stat-icon">
                        ❌
                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- ORDER STATUS STATISTICS --}}
    {{-- ========================================================= --}}

    <div class="row g-4 mb-4">

        {{-- Pending --}}
        <div class="col-md-6 col-lg-3">

            <div class="stat-card card-pending p-4">

                <div class="d-flex justify-content-between">

                    <div>
                        <div class="label">
                            Pending Orders
                        </div>

                        <div class="number">
                            {{ number_format($pendingOrders) }}
                        </div>
                    </div>

                    <div class="stat-icon">
                        ⏳
                    </div>

                </div>

            </div>

        </div>


        {{-- Processing --}}
        <div class="col-md-6 col-lg-3">

            <div class="stat-card card-processing p-4">

                <div class="d-flex justify-content-between">

                    <div>
                        <div class="label">
                            Processing Orders
                        </div>

                        <div class="number">
                            {{ number_format($processingOrders) }}
                        </div>
                    </div>

                    <div class="stat-icon">
                        🔄
                    </div>

                </div>

            </div>

        </div>


        {{-- Delivered --}}
        <div class="col-md-6 col-lg-3">

            <div class="stat-card card-delivered p-4">

                <div class="d-flex justify-content-between">

                    <div>
                        <div class="label">
                            Delivered Orders
                        </div>

                        <div class="number">
                            {{ number_format($deliveredOrders) }}
                        </div>
                    </div>

                    <div class="stat-icon">
                        🚚
                    </div>

                </div>

            </div>

        </div>


        {{-- Today's Orders --}}
        <div class="col-md-6 col-lg-3">

            <div class="stat-card card-today p-4">

                <div class="d-flex justify-content-between">

                    <div>
                        <div class="label">
                            Today's Orders
                        </div>

                        <div class="number">
                            {{ number_format($todayOrders ?? 0) }}
                        </div>
                    </div>

                    <div class="stat-icon">
                        📅
                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- THIS WEEK --}}
    {{-- ========================================================= --}}

    <div class="row g-4 mb-4">

        <div class="col-md-6">

            <div class="stat-card card-week p-4">

                <div class="d-flex justify-content-between">

                    <div>
                        <div class="label">
                            Orders This Week
                        </div>

                        <div class="number">
                            {{ number_format($weekOrders ?? 0) }}
                        </div>
                    </div>

                    <div class="stat-icon">
                        📆
                    </div>

                </div>

            </div>

        </div>


        {{-- Success Percentage --}}
        <div class="col-md-6">

            <div class="card dashboard-card h-100">

                <div class="card-body">

                    <h6 class="fw-bold mb-3">
                        Validation Success Rate
                    </h6>

                    @php
                        $successRate = $totalValidationAttempts > 0
                            ? round(
                                ($successfulValidations / $totalValidationAttempts) * 100,
                                1
                            )
                            : 0;
                    @endphp

                    <div class="d-flex justify-content-between mb-2">

                        <span>
                            Successful
                        </span>

                        <strong>
                            {{ $successRate }}%
                        </strong>

                    </div>

                    <div
                        class="progress"
                        style="height: 12px;"
                    >

                        <div
                            class="progress-bar bg-success"
                            role="progressbar"
                            style="width: {{ $successRate }}%;"
                            aria-valuenow="{{ $successRate }}"
                            aria-valuemin="0"
                            aria-valuemax="100"
                        ></div>

                    </div>

                    <small class="text-muted d-block mt-2">
                        {{ $successfulValidations }}
                        successful out of
                        {{ $totalValidationAttempts }}
                        validation attempts
                    </small>

                </div>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- COUNTRY / CURRENCY / STATUS STATISTICS --}}
    {{-- ========================================================= --}}

    <div class="row g-4 mb-4">

        {{-- Country Statistics --}}
        <div class="col-lg-4">

            <div class="card dashboard-card">

                <div class="card-header">
                    🌍 Orders by Country
                </div>

                <div class="card-body p-0">

                    @if($countryStats->count())

                        <div class="table-responsive">

                            <table class="table table-hover mb-0">

                                <thead class="table-light">

                                    <tr>
                                        <th>Country</th>
                                        <th class="text-end">Orders</th>
                                    </tr>

                                </thead>

                                <tbody>

                                    @foreach($countryStats as $country)

                                        <tr>

                                            <td>
                                                <span class="badge bg-primary">
                                                    {{ $country->country }}
                                                </span>
                                            </td>

                                            <td class="text-end fw-bold">
                                                {{ number_format($country->total) }}
                                            </td>

                                        </tr>

                                    @endforeach

                                </tbody>

                            </table>

                        </div>

                    @else

                        <div class="empty-state">
                            No country statistics available.
                        </div>

                    @endif

                </div>

            </div>

        </div>


        {{-- Currency Statistics --}}
        <div class="col-lg-4">

            <div class="card dashboard-card">

                <div class="card-header">
                    💰 Orders by Currency
                </div>

                <div class="card-body p-0">

                    @if($currencyStats->count())

                        <div class="table-responsive">

                            <table class="table table-hover mb-0">

                                <thead class="table-light">

                                    <tr>
                                        <th>Currency</th>
                                        <th class="text-end">Orders</th>
                                    </tr>

                                </thead>

                                <tbody>

                                    @foreach($currencyStats as $currency)

                                        <tr>

                                            <td>
                                                <span class="badge bg-success">
                                                    {{ $currency->currency }}
                                                </span>
                                            </td>

                                            <td class="text-end fw-bold">
                                                {{ number_format($currency->total) }}
                                            </td>

                                        </tr>

                                    @endforeach

                                </tbody>

                            </table>

                        </div>

                    @else

                        <div class="empty-state">
                            No currency statistics available.
                        </div>

                    @endif

                </div>

            </div>

        </div>


        {{-- Status Statistics --}}
        <div class="col-lg-4">

            <div class="card dashboard-card">

                <div class="card-header">
                    📊 Orders by Status
                </div>

                <div class="card-body p-0">

                    @if($statusStats->count())

                        <div class="table-responsive">

                            <table class="table table-hover mb-0">

                                <thead class="table-light">

                                    <tr>
                                        <th>Status</th>
                                        <th class="text-end">Orders</th>
                                    </tr>

                                </thead>

                                <tbody>

                                    @foreach($statusStats as $status)

                                        <tr>

                                            <td>

                                                @if($status->status === 'pending')

                                                    <span class="badge bg-warning text-dark">
                                                        Pending
                                                    </span>

                                                @elseif($status->status === 'processing')

                                                    <span class="badge bg-info">
                                                        Processing
                                                    </span>

                                                @elseif($status->status === 'delivered')

                                                    <span class="badge bg-success">
                                                        Delivered
                                                    </span>

                                                @else

                                                    <span class="badge bg-secondary">
                                                        {{ ucfirst($status->status) }}
                                                    </span>

                                                @endif

                                            </td>

                                            <td class="text-end fw-bold">
                                                {{ number_format($status->total) }}
                                            </td>

                                        </tr>

                                    @endforeach

                                </tbody>

                            </table>

                        </div>

                    @else

                        <div class="empty-state">
                            No status statistics available.
                        </div>

                    @endif

                </div>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- RECENT ORDERS --}}
    {{-- ========================================================= --}}

    <div class="card dashboard-card mb-4">

        <div class="card-header d-flex justify-content-between align-items-center">

            <span>
                📦 Recent Orders
            </span>

            <a
                href="{{ route('orders.index') }}"
                class="btn btn-sm btn-primary"
            >
                View All Orders
            </a>

        </div>

        <div class="card-body p-0">

            @if($recentOrders->count())

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-light">

                            <tr>
                                <th>ID</th>
                                <th>Country</th>
                                <th>Currency</th>
                                <th>Status</th>
                                <th>Emails</th>
                                <th>Created</th>
                                <th>Action</th>
                            </tr>

                        </thead>

                        <tbody>

                            @foreach($recentOrders as $order)

                                <tr>

                                    <td>
                                        <strong>
                                            #{{ $order->id }}
                                        </strong>
                                    </td>

                                    <td>
                                        {{ $order->country }}
                                    </td>

                                    <td>
                                        {{ $order->currency }}
                                    </td>

                                    <td>

                                        @if($order->status === 'pending')

                                            <span class="badge-status bg-warning text-dark">
                                                Pending
                                            </span>

                                        @elseif($order->status === 'processing')

                                            <span class="badge-status bg-info text-white">
                                                Processing
                                            </span>

                                        @elseif($order->status === 'delivered')

                                            <span class="badge-status bg-success text-white">
                                                Delivered
                                            </span>

                                        @else

                                            <span class="badge-status bg-secondary text-white">
                                                {{ ucfirst($order->status) }}
                                            </span>

                                        @endif

                                    </td>

                                    <td>

                                        @if(is_array($order->emails))

                                            {{ count($order->emails) }} email(s)

                                        @else

                                            -

                                        @endif

                                    </td>

                                    <td>
                                        {{ $order->created_at?->format('d M Y H:i') }}
                                    </td>

                                    <td>

                                        <a
                                            href="{{ route('orders.show', $order) }}"
                                            class="btn btn-sm btn-outline-primary"
                                        >
                                            View
                                        </a>

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

            @else

                <div class="empty-state">
                    <div class="fs-1 mb-2">
                        📦
                    </div>

                    <h6>
                        No Orders Found
                    </h6>

                    <p class="mb-3">
                        Create your first order to see statistics here.
                    </p>

                    <a
                        href="{{ route('order.create') }}"
                        class="btn btn-primary"
                    >
                        Create Order
                    </a>
                </div>

            @endif

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- RECENT VALIDATION FAILURES --}}
    {{-- ========================================================= --}}

    <div class="card dashboard-card mb-4">

        <div class="card-header d-flex justify-content-between align-items-center">

            <span>
                ❌ Recent Validation Failures
            </span>

            <a
                href="{{ route('validation.history') }}"
                class="btn btn-sm btn-warning"
            >
                View History
            </a>

        </div>

        <div class="card-body p-0">

            @if($recentFailures->count())

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-light">

                            <tr>
                                <th>ID</th>
                                <th>Form Type</th>
                                <th>Failed Fields</th>
                                <th>IP Address</th>
                                <th>Date</th>
                            </tr>

                        </thead>

                        <tbody>

                            @foreach($recentFailures as $failure)

                                <tr>

                                    <td>
                                        #{{ $failure->id }}
                                    </td>

                                    <td>

                                        <span class="badge bg-danger">
                                            {{ ucfirst($failure->form_type) }}
                                        </span>

                                    </td>

                                    <td>

                                        @if(is_array($failure->failed_fields))

                                            @foreach($failure->failed_fields as $field)

                                                <span class="badge bg-light text-dark border me-1">
                                                    {{ $field }}
                                                </span>

                                            @endforeach

                                        @else

                                            -

                                        @endif

                                    </td>

                                    <td>
                                        {{ $failure->ip_address ?? '-' }}
                                    </td>

                                    <td>
                                        {{ $failure->created_at?->format('d M Y H:i') }}
                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

            @else

                <div class="empty-state">

                    <div class="fs-1 mb-2">
                        ✅
                    </div>

                    <h6>
                        No Validation Failures
                    </h6>

                    <p class="mb-0">
                        No validation failures have been recorded.
                    </p>

                </div>

            @endif

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- QUICK ACTIONS --}}
    {{-- ========================================================= --}}

    <div class="card dashboard-card">

        <div class="card-header">
            ⚡ Quick Actions
        </div>

        <div class="card-body">

            <div class="row g-3">

                <div class="col-md-4">

                    <a
                        href="{{ route('order.create') }}"
                        class="btn btn-primary w-100 py-3"
                    >
                        ➕ Create New Order
                    </a>

                </div>

                <div class="col-md-4">

                    <a
                        href="{{ route('orders.index') }}"
                        class="btn btn-dark w-100 py-3"
                    >
                        📦 Manage Orders
                    </a>

                </div>

                <div class="col-md-4">

                    <a
                        href="{{ route('validation.history') }}"
                        class="btn btn-warning w-100 py-3"
                    >
                        📋 Validation History
                    </a>

                </div>

            </div>

        </div>

    </div>

</div>


<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
></script>

</body>
</html>
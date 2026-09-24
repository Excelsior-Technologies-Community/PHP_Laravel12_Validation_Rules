<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Validation Analytics Dashboard</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

</head>

<body class="bg-light">

<nav class="navbar navbar-dark bg-dark">

    <div class="container">

        <a
            href="{{ route('order.create') }}"
            class="navbar-brand fw-bold"
        >
            Validation Rules Demo
        </a>

        <div>

            <a
                href="{{ route('order.create') }}"
                class="btn btn-outline-light btn-sm me-2"
            >
                + Create Order
            </a>

            <a
                href="{{ route('validation.history') }}"
                class="btn btn-outline-light btn-sm"
            >
                🛡️ Failure History
            </a>

        </div>

    </div>

</nav>


<div class="container py-5">

    <div class="mb-4">

        <h2 class="fw-bold">
            📊 Order Validation Analytics Dashboard
        </h2>

        <p class="text-muted">
            Monitor successful orders and validation failures.
        </p>

    </div>


    {{-- Main statistics --}}

    <div class="row g-4 mb-4">

        <div class="col-md-3">

            <div class="card shadow-sm border-0 h-100">

                <div class="card-body">

                    <div class="text-muted">
                        Total Validation Attempts
                    </div>

                    <h2 class="fw-bold mt-2">
                        {{ $totalValidationAttempts }}
                    </h2>

                </div>

            </div>

        </div>


        <div class="col-md-3">

            <div class="card shadow-sm border-0 h-100">

                <div class="card-body">

                    <div class="text-muted">
                        Successful Orders
                    </div>

                    <h2 class="fw-bold text-success mt-2">
                        {{ $successfulValidations }}
                    </h2>

                </div>

            </div>

        </div>


        <div class="col-md-3">

            <div class="card shadow-sm border-0 h-100">

                <div class="card-body">

                    <div class="text-muted">
                        Validation Failures
                    </div>

                    <h2 class="fw-bold text-danger mt-2">
                        {{ $validationFailures }}
                    </h2>

                </div>

            </div>

        </div>


        <div class="col-md-3">

            <div class="card shadow-sm border-0 h-100">

                <div class="card-body">

                    <div class="text-muted">
                        Failure Rate
                    </div>

                    <h2 class="fw-bold text-warning mt-2">

                        @if($totalValidationAttempts > 0)

                            {{ number_format(
                                ($validationFailures / $totalValidationAttempts) * 100,
                                1
                            ) }}%

                        @else

                            0%

                        @endif

                    </h2>

                </div>

            </div>

        </div>

    </div>


    {{-- Order status statistics --}}

    <div class="row g-4 mb-4">

        <div class="col-md-4">

            <div class="card shadow-sm border-0">

                <div class="card-body">

                    <h5 class="fw-bold">
                        Order Status
                    </h5>

                    <hr>

                    <div class="d-flex justify-content-between mb-3">

                        <span>
                            Pending
                        </span>

                        <span class="badge bg-warning text-dark">
                            {{ $pendingOrders }}
                        </span>

                    </div>

                    <div class="d-flex justify-content-between mb-3">

                        <span>
                            Processing
                        </span>

                        <span class="badge bg-primary">
                            {{ $processingOrders }}
                        </span>

                    </div>

                    <div class="d-flex justify-content-between">

                        <span>
                            Delivered
                        </span>

                        <span class="badge bg-success">
                            {{ $deliveredOrders }}
                        </span>

                    </div>

                </div>

            </div>

        </div>


        {{-- Country statistics --}}

        <div class="col-md-4">

            <div class="card shadow-sm border-0">

                <div class="card-body">

                    <h5 class="fw-bold">
                        Country Distribution
                    </h5>

                    <hr>

                    @forelse($countryStats as $item)

                        <div class="d-flex justify-content-between mb-2">

                            <span>
                                {{ $item->country }}
                            </span>

                            <span class="badge bg-secondary">
                                {{ $item->total }}
                            </span>

                        </div>

                    @empty

                        <p class="text-muted">
                            No data available.
                        </p>

                    @endforelse

                </div>

            </div>

        </div>


        {{-- Currency statistics --}}

        <div class="col-md-4">

            <div class="card shadow-sm border-0">

                <div class="card-body">

                    <h5 class="fw-bold">
                        Currency Distribution
                    </h5>

                    <hr>

                    @forelse($currencyStats as $item)

                        <div class="d-flex justify-content-between mb-2">

                            <span>
                                {{ $item->currency }}
                            </span>

                            <span class="badge bg-info text-dark">
                                {{ $item->total }}
                            </span>

                        </div>

                    @empty

                        <p class="text-muted">
                            No data available.
                        </p>

                    @endforelse

                </div>

            </div>

        </div>

    </div>


    {{-- Recent Orders --}}

    <div class="card shadow-sm border-0 mb-4">

        <div class="card-body">

            <div class="d-flex justify-content-between">

                <h5 class="fw-bold">
                    Recent Successful Orders
                </h5>

                <a
                    href="{{ route('orders.index') }}"
                    class="btn btn-sm btn-outline-primary"
                >
                    View All
                </a>

            </div>

            <hr>

            <div class="table-responsive">

                <table class="table table-hover">

                    <thead>

                        <tr>

                            <th>ID</th>
                            <th>Country</th>
                            <th>Currency</th>
                            <th>Status</th>
                            <th>Date</th>

                        </tr>

                    </thead>

                    <tbody>

                        @forelse($recentOrders as $order)

                            <tr>

                                <td>
                                    #{{ $order->id }}
                                </td>

                                <td>
                                    {{ $order->country }}
                                </td>

                                <td>
                                    {{ $order->currency }}
                                </td>

                                <td>
                                    {{ ucfirst($order->status) }}
                                </td>

                                <td>
                                    {{ $order->created_at->format('d M Y H:i') }}
                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="5"
                                    class="text-center text-muted"
                                >
                                    No orders available.
                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>


    {{-- Recent Validation Failures --}}

    <div class="card shadow-sm border-0">

        <div class="card-body">

            <div class="d-flex justify-content-between">

                <h5 class="fw-bold">
                    Recent Validation Failures
                </h5>

                <a
                    href="{{ route('validation.history') }}"
                    class="btn btn-sm btn-outline-danger"
                >
                    View History
                </a>

            </div>

            <hr>

            <div class="table-responsive">

                <table class="table table-hover">

                    <thead>

                        <tr>

                            <th>ID</th>
                            <th>Failed Fields</th>
                            <th>IP Address</th>
                            <th>Date</th>

                        </tr>

                    </thead>

                    <tbody>

                        @forelse($recentFailures as $failure)

                            <tr>

                                <td>
                                    #{{ $failure->id }}
                                </td>

                                <td>

                                    @foreach($failure->failed_fields as $field)

                                        <span class="badge bg-danger me-1">
                                            {{ $field }}
                                        </span>

                                    @endforeach

                                </td>

                                <td>
                                    {{ $failure->ip_address ?? 'N/A' }}
                                </td>

                                <td>
                                    {{ $failure->created_at->format('d M Y H:i') }}
                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="4"
                                    class="text-center text-muted"
                                >

                                    No validation failures recorded.

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>

</body>
</html>
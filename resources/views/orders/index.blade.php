<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Order Management</title>

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
                href="{{ route('validation.dashboard') }}"
                class="btn btn-outline-light btn-sm me-2"
            >
                📊 Dashboard
            </a>

            <a
                href="{{ route('validation.history') }}"
                class="btn btn-outline-light btn-sm"
            >
                🛡️ Validation History
            </a>

        </div>

    </div>

</nav>


<div class="container py-5">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h2 class="fw-bold">
                Order Management
            </h2>

            <p class="text-muted mb-0">
                Search, filter and paginate validated orders.
            </p>

        </div>

        <a
            href="{{ route('order.create') }}"
            class="btn btn-primary"
        >
            + Create Order
        </a>

    </div>


    {{-- Search and filters --}}

    <div class="card shadow-sm border-0 mb-4">

        <div class="card-body">

            <form
                method="GET"
                action="{{ route('orders.index') }}"
            >

                <div class="row g-3">

                    <div class="col-lg-4">

                        <label class="form-label">
                            Search
                        </label>

                        <input
                            type="text"
                            name="search"
                            value="{{ request('search') }}"
                            class="form-control"
                            placeholder="Country, currency or status"
                        >

                    </div>


                    <div class="col-lg-2">

                        <label class="form-label">
                            Status
                        </label>

                        <select
                            name="status"
                            class="form-select"
                        >

                            <option value="">
                                All
                            </option>

                            <option
                                value="pending"
                                {{ request('status') === 'pending' ? 'selected' : '' }}
                            >
                                Pending
                            </option>

                            <option
                                value="processing"
                                {{ request('status') === 'processing' ? 'selected' : '' }}
                            >
                                Processing
                            </option>

                            <option
                                value="delivered"
                                {{ request('status') === 'delivered' ? 'selected' : '' }}
                            >
                                Delivered
                            </option>

                        </select>

                    </div>


                    <div class="col-lg-2">

                        <label class="form-label">
                            Country
                        </label>

                        <input
                            type="text"
                            name="country"
                            value="{{ request('country') }}"
                            class="form-control"
                            placeholder="IN"
                        >

                    </div>


                    <div class="col-lg-2">

                        <label class="form-label">
                            Currency
                        </label>

                        <input
                            type="text"
                            name="currency"
                            value="{{ request('currency') }}"
                            class="form-control"
                            placeholder="INR"
                        >

                    </div>


                    <div class="col-lg-2 d-flex align-items-end">

                        <button
                            type="submit"
                            class="btn btn-primary w-100"
                        >
                            🔎 Search
                        </button>

                    </div>

                </div>

            </form>

        </div>

    </div>


    {{-- Orders table --}}

    <div class="card shadow-sm border-0">

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-hover align-middle">

                    <thead class="table-dark">

                        <tr>

                            <th>#</th>

                            <th>Country</th>

                            <th>Currency</th>

                            <th>Status</th>

                            <th>Products</th>

                            <th>Emails</th>

                            <th>Created</th>

                        </tr>

                    </thead>

                    <tbody>

                        @forelse($orders as $order)

                            <tr>

                                <td>
                                    {{ $order->id }}
                                </td>

                                <td>
                                    <span class="badge bg-secondary">
                                        {{ $order->country }}
                                    </span>
                                </td>

                                <td>
                                    <span class="badge bg-info text-dark">
                                        {{ $order->currency }}
                                    </span>
                                </td>

                                <td>

                                    @if($order->status === 'pending')

                                        <span class="badge bg-warning text-dark">
                                            Pending
                                        </span>

                                    @elseif($order->status === 'processing')

                                        <span class="badge bg-primary">
                                            Processing
                                        </span>

                                    @else

                                        <span class="badge bg-success">
                                            Delivered
                                        </span>

                                    @endif

                                </td>

                                <td>

                                    {{ count($order->product_ids ?? []) }}

                                    product(s)

                                </td>

                                <td>

                                    {{ count($order->emails ?? []) }}

                                    email(s)

                                </td>

                                <td>

                                    {{ $order->created_at->format('d M Y H:i') }}

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="7"
                                    class="text-center py-4"
                                >

                                    <div class="text-muted">

                                        No orders found.

                                    </div>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>


            {{-- Pagination --}}

            <div class="mt-3">

                {{ $orders->links('pagination::bootstrap-5') }}

            </div>

        </div>

    </div>

</div>

</body>
</html>
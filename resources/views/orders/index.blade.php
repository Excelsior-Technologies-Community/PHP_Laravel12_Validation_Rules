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
                href="{{ route('order.create') }}"
                class="btn btn-outline-light btn-sm me-2"
            >
                + Create Order
            </a>

            <a
                href="{{ route('validation.dashboard') }}"
                class="btn btn-outline-light btn-sm"
            >
                📊 Dashboard
            </a>

        </div>

    </div>

</nav>


<div class="container py-5">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h2 class="fw-bold">
                📦 Order Management
            </h2>

            <p class="text-muted mb-0">
                Search, filter, manage and export validated orders.
            </p>

        </div>

        <div>

            <a
                href="{{ route('order.create') }}"
                class="btn btn-primary"
            >
                + Create Order
            </a>

        </div>

    </div>


    {{-- Success Message --}}

    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show">

            {{ session('success') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
            ></button>

        </div>

    @endif


    {{-- Statistics --}}

    <div class="row g-3 mb-4">

        <div class="col-md-2">

            <div class="card shadow-sm border-0 h-100">

                <div class="card-body">

                    <small class="text-muted">
                        Total
                    </small>

                    <h3 class="fw-bold mb-0">
                        {{ $totalOrders }}
                    </h3>

                </div>

            </div>

        </div>


        <div class="col-md-2">

            <div class="card shadow-sm border-0 h-100">

                <div class="card-body">

                    <small class="text-muted">
                        Pending
                    </small>

                    <h3 class="fw-bold text-warning mb-0">
                        {{ $pendingOrders }}
                    </h3>

                </div>

            </div>

        </div>


        <div class="col-md-2">

            <div class="card shadow-sm border-0 h-100">

                <div class="card-body">

                    <small class="text-muted">
                        Processing
                    </small>

                    <h3 class="fw-bold text-primary mb-0">
                        {{ $processingOrders }}
                    </h3>

                </div>

            </div>

        </div>


        <div class="col-md-2">

            <div class="card shadow-sm border-0 h-100">

                <div class="card-body">

                    <small class="text-muted">
                        Delivered
                    </small>

                    <h3 class="fw-bold text-success mb-0">
                        {{ $deliveredOrders }}
                    </h3>

                </div>

            </div>

        </div>


        <div class="col-md-2">

            <div class="card shadow-sm border-0 h-100">

                <div class="card-body">

                    <small class="text-muted">
                        Today
                    </small>

                    <h3 class="fw-bold text-info mb-0">
                        {{ $todayOrders }}
                    </h3>

                </div>

            </div>

        </div>


        <div class="col-md-2">

            <div class="card shadow-sm border-0 h-100">

                <div class="card-body">

                    <small class="text-muted">
                        Showing
                    </small>

                    <h3 class="fw-bold mb-0">
                        {{ $orders->total() }}
                    </h3>

                </div>

            </div>

        </div>

    </div>


    {{-- Search / Filter --}}

    <div class="card shadow-sm border-0 mb-4">

        <div class="card-body">

            <form
                method="GET"
                action="{{ route('orders.index') }}"
            >

                <div class="row g-3">

                    {{-- Search --}}

                    <div class="col-lg-3">

                        <label class="form-label fw-bold">
                            Search
                        </label>

                        <input
                            type="text"
                            name="search"
                            value="{{ request('search') }}"
                            class="form-control"
                            placeholder="ID, country, currency..."
                        >

                    </div>


                    {{-- Status --}}

                    <div class="col-lg-2">

                        <label class="form-label fw-bold">
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


                    {{-- Country --}}

                    <div class="col-lg-2">

                        <label class="form-label fw-bold">
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


                    {{-- Currency --}}

                    <div class="col-lg-2">

                        <label class="form-label fw-bold">
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


                    {{-- Per Page --}}

                    <div class="col-lg-1">

                        <label class="form-label fw-bold">
                            Rows
                        </label>

                        <select
                            name="per_page"
                            class="form-select"
                        >

                            @foreach([5, 10, 25, 50] as $number)

                                <option
                                    value="{{ $number }}"
                                    {{ request('per_page', 10) == $number ? 'selected' : '' }}
                                >
                                    {{ $number }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    <div class="col-lg-2 d-flex align-items-end">

                        <button
                            type="submit"
                            class="btn btn-primary w-100"
                        >
                            🔎 Apply
                        </button>

                    </div>


                    {{-- Date From --}}

                    <div class="col-lg-3">

                        <label class="form-label fw-bold">
                            Date From
                        </label>

                        <input
                            type="date"
                            name="date_from"
                            value="{{ request('date_from') }}"
                            class="form-control"
                        >

                    </div>


                    {{-- Date To --}}

                    <div class="col-lg-3">

                        <label class="form-label fw-bold">
                            Date To
                        </label>

                        <input
                            type="date"
                            name="date_to"
                            value="{{ request('date_to') }}"
                            class="form-control"
                        >

                    </div>


                    {{-- Sort --}}

                    <div class="col-lg-2">

                        <label class="form-label fw-bold">
                            Sort By
                        </label>

                        <select
                            name="sort"
                            class="form-select"
                        >

                            <option
                                value="created_at"
                                {{ request('sort', 'created_at') === 'created_at' ? 'selected' : '' }}
                            >
                                Created Date
                            </option>

                            <option
                                value="id"
                                {{ request('sort') === 'id' ? 'selected' : '' }}
                            >
                                Order ID
                            </option>

                            <option
                                value="country"
                                {{ request('sort') === 'country' ? 'selected' : '' }}
                            >
                                Country
                            </option>

                            <option
                                value="currency"
                                {{ request('sort') === 'currency' ? 'selected' : '' }}
                            >
                                Currency
                            </option>

                            <option
                                value="status"
                                {{ request('sort') === 'status' ? 'selected' : '' }}
                            >
                                Status
                            </option>

                        </select>

                    </div>


                    {{-- Direction --}}

                    <div class="col-lg-2">

                        <label class="form-label fw-bold">
                            Direction
                        </label>

                        <select
                            name="direction"
                            class="form-select"
                        >

                            <option
                                value="desc"
                                {{ request('direction', 'desc') === 'desc' ? 'selected' : '' }}
                            >
                                Descending
                            </option>

                            <option
                                value="asc"
                                {{ request('direction') === 'asc' ? 'selected' : '' }}
                            >
                                Ascending
                            </option>

                        </select>

                    </div>


                    {{-- Buttons --}}

                    <div class="col-lg-2 d-flex align-items-end gap-2">

                        <button
                            type="submit"
                            class="btn btn-primary"
                        >
                            Apply
                        </button>

                        <a
                            href="{{ route('orders.index') }}"
                            class="btn btn-outline-secondary"
                        >
                            Reset
                        </a>

                    </div>

                </div>

            </form>

        </div>

    </div>


    {{-- Export --}}

    <div class="d-flex justify-content-between mb-3">

        <div>

            <strong>
                {{ $orders->total() }}
            </strong>

            order(s) found

        </div>

        <div>

            <a
                href="{{ route('orders.export', request()->query()) }}"
                class="btn btn-success"
            >
                📥 Export CSV
            </a>

        </div>

    </div>


    {{-- Bulk Delete --}}

    <form
        method="POST"
        action="{{ route('orders.bulk-delete') }}"
        id="bulkDeleteForm"
    >

        @csrf

        <div class="card shadow-sm border-0">

            <div class="card-body">

                <div class="table-responsive">

                    <table class="table table-hover align-middle">

                        <thead class="table-dark">

                            <tr>

                                <th>
                                    <input
                                        type="checkbox"
                                        id="selectAll"
                                    >
                                </th>

                                <th>#</th>

                                <th>Country</th>

                                <th>Currency</th>

                                <th>Status</th>

                                <th>Products</th>

                                <th>Emails</th>

                                <th>Created</th>

                                <th>Actions</th>

                            </tr>

                        </thead>


                        <tbody>

                            @forelse($orders as $order)

                                <tr>

                                    <td>

                                        <input
                                            type="checkbox"
                                            name="order_ids[]"
                                            value="{{ $order->id }}"
                                            class="order-checkbox"
                                        >

                                    </td>


                                    <td>
                                        <strong>
                                            #{{ $order->id }}
                                        </strong>
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
                                    </td>


                                    <td>
                                        {{ count($order->emails ?? []) }}
                                    </td>


                                    <td>
                                        {{ $order->created_at->format('d M Y H:i') }}
                                    </td>


                                    <td>

                                        <div class="btn-group">

                                            <a
                                                href="{{ route('orders.show', $order) }}"
                                                class="btn btn-sm btn-outline-primary"
                                            >
                                                View
                                            </a>

                                            <a
                                                href="{{ route('orders.edit', $order) }}"
                                                class="btn btn-sm btn-outline-warning"
                                            >
                                                Edit
                                            </a>

                                            <form
                                                method="POST"
                                                action="{{ route('orders.duplicate', $order) }}"
                                                class="d-inline"
                                            >

                                                @csrf

                                                <button
                                                    type="submit"
                                                    class="btn btn-sm btn-outline-info"
                                                >
                                                    Copy
                                                </button>

                                            </form>

                                            <form
                                                method="POST"
                                                action="{{ route('orders.destroy', $order) }}"
                                                class="d-inline"
                                                onsubmit="return confirm('Are you sure you want to delete this order?')"
                                            >

                                                @csrf

                                                @method('DELETE')

                                                <button
                                                    type="submit"
                                                    class="btn btn-sm btn-outline-danger"
                                                >
                                                    Delete
                                                </button>

                                            </form>

                                        </div>

                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td
                                        colspan="9"
                                        class="text-center py-5"
                                    >

                                        <h5>
                                            No orders found
                                        </h5>

                                        <p class="text-muted">
                                            Try changing your filters.
                                        </p>

                                    </td>

                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>


                @if($orders->count() > 0)

                    <button
                        type="submit"
                        class="btn btn-danger"
                        onclick="return confirm('Delete selected orders?')"
                    >
                        🗑️ Delete Selected
                    </button>

                @endif


                {{-- Numeric Pagination Only --}}

                <div class="mt-4">

                    {{ $orders->onEachSide(1)->links('pagination::bootstrap-5') }}

                </div>

            </div>

        </div>

    </form>

</div>


<script>

document
    .getElementById('selectAll')
    ?.addEventListener('change', function () {

        document
            .querySelectorAll('.order-checkbox')
            .forEach(function (checkbox) {

                checkbox.checked =
                    document.getElementById('selectAll').checked;

            });

    });

</script>


<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
></script>

</body>

</html>
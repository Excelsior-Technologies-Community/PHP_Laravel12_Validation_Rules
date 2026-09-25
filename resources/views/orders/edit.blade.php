<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Edit Order #{{ $order->id }}</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

</head>

<body class="bg-light">

<nav class="navbar navbar-dark bg-dark">

    <div class="container">

        <a
            href="{{ route('orders.index') }}"
            class="navbar-brand fw-bold"
        >
            Validation Rules Demo
        </a>

        <a
            href="{{ route('orders.index') }}"
            class="btn btn-outline-light btn-sm"
        >
            ← Orders
        </a>

    </div>

</nav>


<div class="container py-5">

    <div class="row justify-content-center">

        <div class="col-lg-7">

            <div class="card shadow border-0">

                <div class="card-header bg-warning">

                    <h4 class="mb-0">
                        ✏️ Edit Order #{{ $order->id }}
                    </h4>

                </div>


                <div class="card-body p-4">

                    @if($errors->any())

                        <div class="alert alert-danger">

                            <h6 class="fw-bold">
                                Validation Failed
                            </h6>

                            <ul class="mb-0">

                                @foreach($errors->all() as $error)

                                    <li>
                                        {{ $error }}
                                    </li>

                                @endforeach

                            </ul>

                        </div>

                    @endif


                    <form
                        method="POST"
                        action="{{ route('order.store') }}"
                    >

                        @csrf

                        <input
                            type="hidden"
                            name="order_id"
                            value="{{ $order->id }}"
                        >


                        {{-- Country --}}

                        <div class="mb-3">

                            <label class="form-label fw-bold">
                                Country Code
                            </label>

                            <input
                                type="text"
                                name="country"
                                value="{{ old('country', $order->country) }}"
                                class="form-control"
                                placeholder="IN"
                            >

                        </div>


                        {{-- Currency --}}

                        <div class="mb-3">

                            <label class="form-label fw-bold">
                                Currency
                            </label>

                            <input
                                type="text"
                                name="currency"
                                value="{{ old('currency', $order->currency) }}"
                                class="form-control"
                                placeholder="INR"
                            >

                        </div>


                        {{-- Status --}}

                        <div class="mb-3">

                            <label class="form-label fw-bold">
                                Order Status
                            </label>

                            <select
                                name="status"
                                class="form-select"
                            >

                                <option
                                    value="pending"
                                    {{ old('status', $order->status) === 'pending' ? 'selected' : '' }}
                                >
                                    Pending
                                </option>

                                <option
                                    value="processing"
                                    {{ old('status', $order->status) === 'processing' ? 'selected' : '' }}
                                >
                                    Processing
                                </option>

                                <option
                                    value="delivered"
                                    {{ old('status', $order->status) === 'delivered' ? 'selected' : '' }}
                                >
                                    Delivered
                                </option>

                            </select>

                        </div>


                        {{-- Products --}}

                        <div class="mb-3">

                            <label class="form-label fw-bold">
                                Products
                            </label>

                            <div class="border rounded p-3 bg-light">

                                @foreach($products as $product)

                                    <div class="form-check mb-2">

                                        <input
                                            type="checkbox"
                                            class="form-check-input"
                                            name="product_ids[]"
                                            value="{{ $product->id }}"
                                            id="product{{ $product->id }}"
                                            {{ in_array(
                                                $product->id,
                                                old('product_ids', $order->product_ids ?? [])
                                            ) ? 'checked' : '' }}
                                        >

                                        <label
                                            class="form-check-label"
                                            for="product{{ $product->id }}"
                                        >

                                            {{ $product->name }}

                                            <span class="text-muted">
                                                ₹{{ number_format($product->price, 2) }}
                                            </span>

                                        </label>

                                    </div>

                                @endforeach

                            </div>

                        </div>


                        {{-- Emails --}}

                        <div class="mb-4">

                            <label class="form-label fw-bold">
                                Emails
                            </label>

                            <input
                                type="text"
                                name="emails"
                                value="{{ old('emails', implode(',', $order->emails ?? [])) }}"
                                class="form-control"
                                placeholder="a@gmail.com,b@gmail.com"
                            >

                            <div class="form-text">
                                Multiple emails separated by commas.
                            </div>

                        </div>


                        <div class="d-flex gap-2">

                            <a
                                href="{{ route('orders.index') }}"
                                class="btn btn-secondary w-50"
                            >
                                Cancel
                            </a>

                            <button
                                type="submit"
                                class="btn btn-warning w-50"
                            >
                                Update Order
                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    </div>

</div>

</body>

</html>
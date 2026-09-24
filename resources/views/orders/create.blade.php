<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Create Order - Validation Rules</title>

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
                href="{{ route('orders.index') }}"
                class="btn btn-outline-light btn-sm"
            >
                🔎 Orders
            </a>

        </div>

    </div>

</nav>


<div class="container py-5">

    <div class="row justify-content-center">

        <div class="col-lg-7">

            <div class="card shadow border-0">

                <div class="card-header bg-primary text-white">

                    <h4 class="mb-0">
                        Create Order
                    </h4>

                </div>

                <div class="card-body p-4">

                    @if(session('success'))

                        <div class="alert alert-success">

                            {{ session('success') }}

                        </div>

                    @endif


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


                        {{-- Country --}}

                        <div class="mb-3">

                            <label class="form-label fw-bold">
                                Country Code
                            </label>

                            <input
                                type="text"
                                name="country"
                                value="{{ old('country') }}"
                                class="form-control"
                                placeholder="IN"
                            >

                            <div class="form-text">
                                ISO country code, e.g. IN, US, GB
                            </div>

                        </div>


                        {{-- Currency --}}

                        <div class="mb-3">

                            <label class="form-label fw-bold">
                                Currency
                            </label>

                            <input
                                type="text"
                                name="currency"
                                value="{{ old('currency') }}"
                                class="form-control"
                                placeholder="INR"
                            >

                            <div class="form-text">
                                ISO currency code, e.g. INR, USD, GBP
                            </div>

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

                                <option value="">
                                    Select Status
                                </option>

                                <option
                                    value="pending"
                                    {{ old('status') === 'pending' ? 'selected' : '' }}
                                >
                                    Pending
                                </option>

                                <option
                                    value="processing"
                                    {{ old('status') === 'processing' ? 'selected' : '' }}
                                >
                                    Processing
                                </option>

                                <option
                                    value="delivered"
                                    {{ old('status') === 'delivered' ? 'selected' : '' }}
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
                                            {{ in_array($product->id, old('product_ids', [])) ? 'checked' : '' }}
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
                                value="{{ old('emails') }}"
                                class="form-control"
                                placeholder="a@gmail.com,b@gmail.com"
                            >

                            <div class="form-text">
                                Enter multiple emails separated by commas.
                            </div>

                        </div>


                        <button
                            type="submit"
                            class="btn btn-primary w-100"
                        >
                            Submit & Validate Order
                        </button>

                    </form>

                </div>

            </div>

        </div>

    </div>

</div>

</body>
</html>
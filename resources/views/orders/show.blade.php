<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Order #{{ $order->id }}</title>

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

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h2 class="fw-bold">
                Order #{{ $order->id }}
            </h2>

            <p class="text-muted">
                Complete order information
            </p>

        </div>

        <div>

            <a
                href="{{ route('orders.edit', $order) }}"
                class="btn btn-warning"
            >
                ✏️ Edit
            </a>

        </div>

    </div>


    <div class="row g-4">

        <div class="col-md-6">

            <div class="card shadow-sm border-0">

                <div class="card-body">

                    <h5 class="fw-bold">
                        Order Information
                    </h5>

                    <hr>

                    <p>
                        <strong>Order ID:</strong>
                        #{{ $order->id }}
                    </p>

                    <p>
                        <strong>Country:</strong>
                        <span class="badge bg-secondary">
                            {{ $order->country }}
                        </span>
                    </p>

                    <p>
                        <strong>Currency:</strong>
                        <span class="badge bg-info text-dark">
                            {{ $order->currency }}
                        </span>
                    </p>

                    <p>
                        <strong>Status:</strong>

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

                    </p>

                    <p>
                        <strong>Created:</strong>
                        {{ $order->created_at->format('d M Y H:i:s') }}
                    </p>

                    <p class="mb-0">
                        <strong>Updated:</strong>
                        {{ $order->updated_at->format('d M Y H:i:s') }}
                    </p>

                </div>

            </div>

        </div>


        <div class="col-md-6">

            <div class="card shadow-sm border-0">

                <div class="card-body">

                    <h5 class="fw-bold">
                        Email Addresses
                    </h5>

                    <hr>

                    @forelse($order->emails ?? [] as $email)

                        <div class="badge bg-light text-dark border mb-2 p-2">
                            {{ $email }}
                        </div>

                    @empty

                        <p class="text-muted">
                            No email addresses.
                        </p>

                    @endforelse

                </div>

            </div>

        </div>


        <div class="col-12">

            <div class="card shadow-sm border-0">

                <div class="card-body">

                    <h5 class="fw-bold">
                        Selected Products
                    </h5>

                    <hr>

                    <div class="table-responsive">

                        <table class="table table-hover">

                            <thead>

                                <tr>

                                    <th>ID</th>

                                    <th>Product</th>

                                    <th>Price</th>

                                </tr>

                            </thead>

                            <tbody>

                                @forelse($products as $product)

                                    <tr>

                                        <td>
                                            #{{ $product->id }}
                                        </td>

                                        <td>
                                            {{ $product->name }}
                                        </td>

                                        <td>
                                            ₹{{ number_format($product->price, 2) }}
                                        </td>

                                    </tr>

                                @empty

                                    <tr>

                                        <td
                                            colspan="3"
                                            class="text-center text-muted"
                                        >
                                            No products found.
                                        </td>

                                    </tr>

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
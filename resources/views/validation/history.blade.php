<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Validation Failure History</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

</head>

<body class="bg-light">

<nav class="navbar navbar-dark bg-dark">

    <div class="container">

        <a
            href="{{ route('validation.dashboard') }}"
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
                href="{{ route('orders.index') }}"
                class="btn btn-outline-light btn-sm"
            >
                🔎 Orders
            </a>

        </div>

    </div>

</nav>


<div class="container py-5">

    <div class="mb-4">

        <h2 class="fw-bold">
            🛡️ Validation Failure History
        </h2>

        <p class="text-muted">
            Review failed validation attempts and the rules that rejected them.
        </p>

    </div>


    {{-- Search --}}

    <div class="card shadow-sm border-0 mb-4">

        <div class="card-body">

            <form
                method="GET"
                action="{{ route('validation.history') }}"
            >

                <div class="row g-3">

                    <div class="col-md-10">

                        <label class="form-label">
                            Search Validation Failures
                        </label>

                        <input
                            type="text"
                            name="search"
                            value="{{ request('search') }}"
                            class="form-control"
                            placeholder="Search field name, form type or IP address"
                        >

                    </div>

                    <div class="col-md-2 d-flex align-items-end">

                        <button
                            type="submit"
                            class="btn btn-danger w-100"
                        >
                            🔎 Search
                        </button>

                    </div>

                </div>

            </form>

        </div>

    </div>


    @forelse($failures as $failure)

        <div class="card shadow-sm border-0 mb-4">

            <div class="card-header bg-danger text-white">

                <div class="d-flex justify-content-between">

                    <span class="fw-bold">
                        Validation Failure #{{ $failure->id }}
                    </span>

                    <span>
                        {{ $failure->created_at->format('d M Y H:i:s') }}
                    </span>

                </div>

            </div>


            <div class="card-body">

                <div class="row g-4">

                    {{-- Failed fields --}}

                    <div class="col-md-4">

                        <h6 class="fw-bold">
                            Failed Fields
                        </h6>

                        @foreach($failure->failed_fields as $field)

                            <span class="badge bg-danger me-1 mb-1">
                                {{ $field }}
                            </span>

                        @endforeach

                    </div>


                    {{-- Request information --}}

                    <div class="col-md-4">

                        <h6 class="fw-bold">
                            Request Information
                        </h6>

                        <p class="mb-1">

                            <strong>Form:</strong>

                            {{ ucfirst($failure->form_type) }}

                        </p>

                        <p class="mb-1">

                            <strong>IP:</strong>

                            {{ $failure->ip_address ?? 'N/A' }}

                        </p>

                    </div>


                    {{-- Error count --}}

                    <div class="col-md-4">

                        <h6 class="fw-bold">
                            Validation Errors
                        </h6>

                        <span class="badge bg-warning text-dark fs-6">

                            {{ count($failure->errors ?? []) }}

                            error(s)

                        </span>

                    </div>

                </div>


                <hr>


                {{-- Error messages --}}

                <h6 class="fw-bold">
                    Validation Error Messages
                </h6>

                <ul class="list-group mb-3">

                    @foreach($failure->errors as $field => $messages)

                        @foreach($messages as $message)

                            <li class="list-group-item">

                                <span class="badge bg-secondary me-2">
                                    {{ $field }}
                                </span>

                                {{ $message }}

                            </li>

                        @endforeach

                    @endforeach

                </ul>


                {{-- Submitted input --}}

                <h6 class="fw-bold">
                    Submitted Input
                </h6>

                <div class="bg-light border rounded p-3">

                    <pre class="mb-0">{{ json_encode(
                        $failure->input_data,
                        JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES
                    ) }}</pre>

                </div>

            </div>

        </div>

    @empty

        <div class="card shadow-sm border-0">

            <div class="card-body text-center py-5">

                <h5>
                    No validation failures found.
                </h5>

                <p class="text-muted">
                    Failed order validation attempts will appear here.
                </p>

            </div>

        </div>

    @endforelse


    {{-- Pagination --}}

    <div class="mt-4">

        {{ $failures->links('pagination::bootstrap-5') }}

    </div>

</div>

</body>
</html>
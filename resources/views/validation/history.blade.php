<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>🛡️ Validation Failure & Bot Security History</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body class="bg-light">

<nav class="navbar navbar-dark bg-dark">

    <div class="container">

        <a href="{{ route('validation.dashboard') }}" class="navbar-brand fw-bold">

            ⚡ Validation Analytics & Security

        </a>


        <div>

            <a href="{{ route('validation.dashboard') }}" class="btn btn-outline-light btn-sm me-2">

                📊 Dashboard

            </a>


            <a href="{{ route('order.create') }}" class="btn btn-primary btn-sm me-2">

                + Create Order

            </a>


            <a href="{{ route('orders.index') }}" class="btn btn-outline-light btn-sm">

                🔎 Orders

            </a>

        </div>

    </div>

</nav>


<div class="container py-5">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h2 class="fw-bold">

                🛡️ Validation Failure & Bot Security Logs

            </h2>


            <p class="text-muted mb-0">

                Review failed validation attempts, security rule violations, and bot anomaly alerts.

            </p>

        </div>


        <div class="btn-group">

            <a href="{{ route('validation.history') }}" class="btn btn-sm {{ !request('filter') ? 'btn-dark' : 'btn-outline-dark' }}">

                All Failures

            </a>


            <a href="{{ route('validation.history', ['filter' => 'suspicious']) }}" class="btn btn-sm {{ request('filter') === 'suspicious' ? 'btn-danger' : 'btn-outline-danger' }}">

                🚨 Suspicious Bots Only

            </a>

        </div>

    </div>


    {{-- Search --}}

    <div class="card shadow-sm border-0 mb-4">

        <div class="card-body">

            <form method="GET" action="{{ route('validation.history') }}">

                @if(request('filter'))

                    <input type="hidden" name="filter" value="{{ request('filter') }}">

                @endif


                <div class="row g-3">

                    <div class="col-md-10">

                        <label class="form-label fw-bold">

                            Search Validation Failures

                        </label>


                        <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Search field name, form type or IP address (e.g. 127.0.0.1, emails, gstin)">

                    </div>


                    <div class="col-md-2 d-flex align-items-end">

                        <button type="submit" class="btn btn-danger w-100">

                            🔎 Search

                        </button>

                    </div>

                </div>

            </form>

        </div>

    </div>


    @forelse($failures as $failure)

        <div class="card shadow-sm border-0 mb-4">

            <div class="card-header {{ $failure->is_suspicious ? 'bg-dark text-warning border-start border-warning border-5' : 'bg-danger text-white' }}">

                <div class="d-flex justify-content-between align-items-center">

                    <span class="fw-bold fs-6">

                        Validation Failure #{{ $failure->id }}

                        @if($failure->is_suspicious)

                            <span class="badge bg-warning text-dark ms-2">

                                🚨 BOT ANOMALY SPAM DETECTED

                            </span>

                        @endif

                    </span>


                    <span>

                        📅 {{ $failure->created_at->format('d M Y H:i:s') }}

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

                            <strong>Form:</strong> {{ ucfirst($failure->form_type) }}

                        </p>


                        <p class="mb-1">

                            <strong>IP Address:</strong>

                            <span class="badge bg-secondary">{{ $failure->ip_address ?? 'N/A' }}</span>

                        </p>

                    </div>


                    {{-- Error count --}}

                    <div class="col-md-4">

                        <h6 class="fw-bold">

                            Validation Status

                        </h6>


                        <span class="badge bg-warning text-dark fs-6 me-2">

                            {{ count($failure->errors ?? []) }} error(s)

                        </span>


                        @if($failure->is_suspicious)

                            <span class="badge bg-danger fs-6">

                                High Frequency Fraud Risk

                            </span>

                        @endif

                    </div>

                </div>


                <hr>


                {{-- Error messages --}}

                <h6 class="fw-bold text-danger">

                    Validation Error Messages & Rule Violations

                </h6>


                <ul class="list-group mb-3">

                    @foreach($failure->errors as $field => $messages)

                        @foreach($messages as $message)

                            <li class="list-group-item list-group-item-light d-flex justify-content-between align-items-center">

                                <div>

                                    <span class="badge bg-secondary me-2">

                                        {{ $field }}

                                    </span>

                                    {{ $message }}

                                </div>


                                <span class="badge bg-outline-danger text-danger border border-danger">Rule Violation</span>

                            </li>

                        @endforeach

                    @endforeach

                </ul>


                {{-- Submitted input --}}

                <h6 class="fw-bold">

                    Submitted Raw Input Data

                </h6>


                <div class="bg-dark text-success border rounded p-3 font-monospace">

                    <pre class="mb-0 text-light">{{ json_encode($failure->input_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>

                </div>

            </div>

        </div>

    @empty

        <div class="card shadow-sm border-0">

            <div class="card-body text-center py-5">

                <h5>No validation failures found.</h5>

                <p class="text-muted">Failed order validation attempts and security violations will appear here.</p>

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
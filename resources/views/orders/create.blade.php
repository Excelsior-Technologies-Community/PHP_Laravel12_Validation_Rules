<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>⚡ Real-Time Create Order - Validation Studio</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        .live-badge {
            font-size: 12px;
            font-weight: 700;
            margin-top: 4px;
            display: inline-block;
            transition: all 0.25s ease;
        }

        .live-badge.valid {
            color: #198754;
            background: #e8f5e9;
            padding: 3px 8px;
            border-radius: 6px;
        }

        .live-badge.invalid {
            color: #dc3545;
            background: #ffebee;
            padding: 3px 8px;
            border-radius: 6px;
        }
    </style>

</head>

<body class="bg-light">

    <nav class="navbar navbar-dark bg-dark">

        <div class="container">

            <a href="{{ route('order.create') }}" class="navbar-brand fw-bold">

                ⚡ Validation Rules & Live Studio

            </a>


            <div>

                <a href="{{ route('validation.dashboard') }}" class="btn btn-outline-light btn-sm me-2">

                    📊 Analytics & Heatmap

                </a>


                <a href="{{ route('orders.index') }}" class="btn btn-outline-light btn-sm">

                    🔎 Orders List

                </a>

            </div>

        </div>

    </nav>


    <div class="container py-5">

        <div class="row justify-content-center">

            <div class="col-lg-8">

                <div class="card shadow border-0">

                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">

                        <h4 class="mb-0">

                            ✨ Create Order with Live Validation

                        </h4>


                        <span class="badge bg-light text-primary fw-bold">

                            ⚡ AJAX Instant Check

                        </span>

                    </div>


                    <div class="card-body p-4">

                        @if(session('success'))

                            <div class="alert alert-success alert-dismissible fade show">

                                {{ session('success') }}

                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>

                            </div>

                        @endif


                        @if($errors->any())

                            <div class="alert alert-danger">

                                <h6 class="fw-bold">

                                    ❌ Form Validation Failed:

                                </h6>


                                <ul class="mb-0">

                                    @foreach($errors->all() as $error)

                                        <li>{{ $error }}</li>

                                    @endforeach

                                </ul>

                            </div>

                        @endif


                        <form method="POST" action="{{ route('order.store') }}" id="orderForm">

                            @csrf


                            <div class="row">

                                {{-- Country --}}

                                <div class="col-md-6 mb-3">

                                    <label class="form-label fw-bold">

                                        Country Code <span class="text-danger">*</span>

                                    </label>


                                    <input type="text" name="country" id="field_country" value="{{ old('country') }}" class="form-control" placeholder="IN, US, GB" required onkeyup="validateLiveField('country', this.value)">


                                    <div id="feedback_country" class="live-badge"></div>


                                    <div class="form-text">

                                        ISO country code (e.g. IN, US, GB)

                                    </div>

                                </div>


                                {{-- Currency --}}

                                <div class="col-md-6 mb-3">

                                    <label class="form-label fw-bold">

                                        Currency <span class="text-danger">*</span>

                                    </label>


                                    <input type="text" name="currency" id="field_currency" value="{{ old('currency') }}" class="form-control" placeholder="INR, USD, EUR" required onkeyup="validateLiveField('currency', this.value)">


                                    <div id="feedback_currency" class="live-badge"></div>


                                    <div class="form-text">

                                        ISO currency code (e.g. INR, USD, EUR)

                                    </div>

                                </div>

                            </div>


                            <div class="row">

                                {{-- GSTIN / Tax ID --}}

                                <div class="col-md-6 mb-3">

                                    <label class="form-label fw-bold">

                                        GSTIN / Tax ID <span class="badge bg-secondary">Custom Rule</span>

                                    </label>


                                    <input type="text" name="gstin" id="field_gstin" value="{{ old('gstin') }}" class="form-control text-uppercase" placeholder="29AAAAA0000A1Z5" onkeyup="validateLiveField('gstin', this.value)">


                                    <div id="feedback_gstin" class="live-badge"></div>


                                    <div class="form-text">

                                        15-character GSTIN or Tax Identification Number

                                    </div>

                                </div>


                                {{-- International Phone --}}

                                <div class="col-md-6 mb-3">

                                    <label class="form-label fw-bold">

                                        Phone Number <span class="badge bg-secondary">Intl Rule</span>

                                    </label>


                                    <input type="text" name="phone" id="field_phone" value="{{ old('phone') }}" class="form-control" placeholder="+919876543210" onkeyup="validateLiveField('phone', this.value)">


                                    <div id="feedback_phone" class="live-badge"></div>


                                    <div class="form-text">

                                        Phone with country code (e.g. +919876543210)

                                    </div>

                                </div>

                            </div>


                            {{-- Order Status --}}

                            <div class="mb-3">

                                <label class="form-label fw-bold">

                                    Order Status <span class="text-danger">*</span>

                                </label>


                                <select name="status" id="field_status" class="form-select" onchange="validateLiveField('status', this.value)">

                                    <option value="">Select Status</option>

                                    <option value="pending" {{ old('status') === 'pending' ? 'selected' : '' }}>Pending</option>

                                    <option value="processing" {{ old('status') === 'processing' ? 'selected' : '' }}>Processing</option>

                                    <option value="delivered" {{ old('status') === 'delivered' ? 'selected' : '' }}>Delivered</option>

                                </select>


                                <div id="feedback_status" class="live-badge"></div>

                            </div>


                            {{-- Products --}}

                            <div class="mb-3">

                                <label class="form-label fw-bold">

                                    Products <span class="text-danger">*</span>

                                </label>


                                <div class="border rounded p-3 bg-light">

                                    @foreach($products as $product)

                                        <div class="form-check mb-2">

                                            <input type="checkbox" class="form-check-input product-check" name="product_ids[]" value="{{ $product->id }}" id="product{{ $product->id }}" {{ in_array($product->id, old('product_ids', [])) ? 'checked' : '' }} onchange="validateProductsLive()">


                                            <label class="form-check-label" for="product{{ $product->id }}">

                                                {{ $product->name }}

                                                <span class="text-muted fw-bold">

                                                    ₹{{ number_format($product->price, 2) }}

                                                </span>

                                            </label>

                                        </div>

                                    @endforeach

                                </div>


                                <div id="feedback_product_ids" class="live-badge"></div>

                            </div>


                            {{-- Emails --}}

                            <div class="mb-4">

                                <label class="form-label fw-bold">

                                    Recipient Emails <span class="text-danger">*</span>

                                </label>


                                <input type="text" name="emails" id="field_emails" value="{{ old('emails') }}" class="form-control" placeholder="admin@example.com, user@domain.com" required onkeyup="validateLiveField('emails', this.value)">


                                <div id="feedback_emails" class="live-badge"></div>


                                <div class="form-text">

                                    Delimited list of emails separated by commas (Spatie Delimited Rule)

                                </div>

                            </div>


                            <button type="submit" class="btn btn-primary w-100 btn-lg shadow-sm">

                                🚀 Submit & Validate Order

                            </button>

                        </form>

                    </div>

                </div>

            </div>

        </div>

    </div>


    <script>

        let debounceTimer;


        function validateLiveField(fieldName, value) {

            clearTimeout(debounceTimer);

            debounceTimer = setTimeout(() => {

                if (!value || value.trim() === '') {

                    const badge = document.getElementById(`feedback_${fieldName}`);

                    if (badge) { badge.innerHTML = ''; badge.className = 'live-badge'; }

                    return;

                }


                fetch('{{ route("order.validate-field") }}', {

                    method: 'POST',

                    headers: {

                        'Content-Type': 'application/json',

                        'X-CSRF-TOKEN': '{{ csrf_token() }}',

                        'Accept': 'application/json'

                    },

                    body: JSON.stringify({ field: fieldName, value: value })

                })

                .then(res => res.json())

                .then(data => {

                    const badge = document.getElementById(`feedback_${fieldName}`);

                    if (!badge) return;


                    if (data.valid) {

                        badge.innerHTML = `✓ ${data.message}`;

                        badge.className = 'live-badge valid';

                    } else {

                        badge.innerHTML = `✕ ${data.message}`;

                        badge.className = 'live-badge invalid';

                    }

                })

                .catch(err => console.error(err));

            }, 300);

        }


        function validateProductsLive() {

            const checkboxes = document.querySelectorAll('.product-check:checked');

            const selected = Array.from(checkboxes).map(cb => cb.value);

            validateLiveField('product_ids', selected);

        }

    </script>

</body>

</html>
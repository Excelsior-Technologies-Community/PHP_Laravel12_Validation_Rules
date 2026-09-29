<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>✏️ Edit Order #{{ $order->id }} - Validation Studio</title>

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

            <a href="{{ route('orders.index') }}" class="navbar-brand fw-bold">

                Validation Rules & Live Studio

            </a>


            <a href="{{ route('orders.index') }}" class="btn btn-outline-light btn-sm">

                ← Back to Orders

            </a>

        </div>

    </nav>


    <div class="container py-5">

        <div class="row justify-content-center">

            <div class="col-lg-8">

                <div class="card shadow border-0">

                    <div class="card-header bg-warning d-flex justify-content-between align-items-center">

                        <h4 class="mb-0">

                            ✏️ Edit Order #{{ $order->id }}

                        </h4>


                        <span class="badge bg-dark text-warning">

                            ⚡ Live Validation Active

                        </span>

                    </div>


                    <div class="card-body p-4">

                        @if($errors->any())

                            <div class="alert alert-danger">

                                <h6 class="fw-bold">

                                    ❌ Validation Failed:

                                </h6>


                                <ul class="mb-0">

                                    @foreach($errors->all() as $error)

                                        <li>{{ $error }}</li>

                                    @endforeach

                                </ul>

                            </div>

                        @endif


                        <form method="POST" action="{{ route('order.store') }}">

                            @csrf


                            <input type="hidden" name="order_id" value="{{ $order->id }}">


                            <div class="row">

                                {{-- Country --}}

                                <div class="col-md-6 mb-3">

                                    <label class="form-label fw-bold">

                                        Country Code <span class="text-danger">*</span>

                                    </label>


                                    <input type="text" name="country" id="field_country" value="{{ old('country', $order->country) }}" class="form-control" placeholder="IN" required onkeyup="validateLiveField('country', this.value)">


                                    <div id="feedback_country" class="live-badge"></div>

                                </div>


                                {{-- Currency --}}

                                <div class="col-md-6 mb-3">

                                    <label class="form-label fw-bold">

                                        Currency <span class="text-danger">*</span>

                                    </label>


                                    <input type="text" name="currency" id="field_currency" value="{{ old('currency', $order->currency) }}" class="form-control" placeholder="INR" required onkeyup="validateLiveField('currency', this.value)">


                                    <div id="feedback_currency" class="live-badge"></div>

                                </div>

                            </div>


                            <div class="row">

                                {{-- GSTIN / Tax ID --}}

                                <div class="col-md-6 mb-3">

                                    <label class="form-label fw-bold">

                                        GSTIN / Tax ID

                                    </label>


                                    <input type="text" name="gstin" id="field_gstin" value="{{ old('gstin', $order->gstin) }}" class="form-control text-uppercase" placeholder="29AAAAA0000A1Z5" onkeyup="validateLiveField('gstin', this.value)">


                                    <div id="feedback_gstin" class="live-badge"></div>

                                </div>


                                {{-- Phone --}}

                                <div class="col-md-6 mb-3">

                                    <label class="form-label fw-bold">

                                        Phone Number

                                    </label>


                                    <input type="text" name="phone" id="field_phone" value="{{ old('phone', $order->phone) }}" class="form-control" placeholder="+919876543210" onkeyup="validateLiveField('phone', this.value)">


                                    <div id="feedback_phone" class="live-badge"></div>

                                </div>

                            </div>


                            {{-- Status --}}

                            <div class="mb-3">

                                <label class="form-label fw-bold">

                                    Order Status <span class="text-danger">*</span>

                                </label>


                                <select name="status" id="field_status" class="form-select" onchange="validateLiveField('status', this.value)">

                                    <option value="pending" {{ old('status', $order->status) === 'pending' ? 'selected' : '' }}>Pending</option>

                                    <option value="processing" {{ old('status', $order->status) === 'processing' ? 'selected' : '' }}>Processing</option>

                                    <option value="delivered" {{ old('status', $order->status) === 'delivered' ? 'selected' : '' }}>Delivered</option>

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

                                            <input type="checkbox" class="form-check-input product-check" name="product_ids[]" value="{{ $product->id }}" id="product{{ $product->id }}" {{ in_array($product->id, old('product_ids', $order->product_ids ?? [])) ? 'checked' : '' }} onchange="validateProductsLive()">


                                            <label class="form-check-label" for="product{{ $product->id }}">

                                                {{ $product->name }}

                                                <span class="text-muted">

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

                                    Emails <span class="text-danger">*</span>

                                </label>


                                <input type="text" name="emails" id="field_emails" value="{{ old('emails', implode(',', $order->emails ?? [])) }}" class="form-control" placeholder="a@gmail.com,b@gmail.com" required onkeyup="validateLiveField('emails', this.value)">


                                <div id="feedback_emails" class="live-badge"></div>

                            </div>


                            <div class="d-flex gap-2">

                                <a href="{{ route('orders.index') }}" class="btn btn-secondary w-50">

                                    Cancel

                                </a>


                                <button type="submit" class="btn btn-warning w-50 fw-bold">

                                    Update Order

                                </button>

                            </div>

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
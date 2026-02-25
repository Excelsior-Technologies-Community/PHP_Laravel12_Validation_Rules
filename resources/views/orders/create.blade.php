<!DOCTYPE html>
<html>
<head>
    <title>Create Order</title>

    <style>

    * {
        box-sizing: border-box;
        font-family: "Segoe UI", Tahoma, sans-serif;
    }

    body {
        background: linear-gradient(135deg,#eef2f7,#dfe6ee);
        margin: 0;
        padding: 40px;
    }

    .container {
        width: 520px;
        margin: auto;
        background: #ffffff;
        padding: 35px;
        border-radius: 14px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.08);
        transition: 0.3s;
    }

    h2 {
        text-align: center;
        margin-bottom: 30px;
        color: #2c3e50;
        font-weight: 600;
    }

    label {
        font-weight: 600;
        color: #34495e;
        margin-top: 18px;
        display: block;
    }

    input[type="text"],
    select {
        width: 100%;
        padding: 12px;
        margin-top: 6px;
        border-radius: 8px;
        border: 1px solid #dcdfe6;
        background: #fafafa;
        transition: all 0.25s ease;
        font-size: 14px;
    }

    input:focus,
    select:focus {
        border-color: #4CAF50;
        background: #fff;
        box-shadow: 0 0 0 3px rgba(76,175,80,0.15);
        outline: none;
    }

    .products {
        margin-top: 8px;
        padding: 15px;
        border-radius: 10px;
        border: 1px solid #e1e5eb;
        background: #fafbfc;
    }

    .products label {
        display: flex;
        align-items: center;
        gap: 10px;
        font-weight: 500;
        margin-bottom: 8px;
        cursor: pointer;
    }

    input[type="checkbox"] {
        transform: scale(1.2);
        cursor: pointer;
    }

    button {
        width: 100%;
        margin-top: 25px;
        padding: 14px;
        border: none;
        border-radius: 10px;
        background: linear-gradient(135deg,#4CAF50,#43a047);
        color: white;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    button:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(76,175,80,0.3);
    }

    .success {
        background: #e8f5e9;
        color: #2e7d32;
        padding: 12px;
        border-radius: 8px;
        margin-bottom: 15px;
        font-weight: 500;
    }

    .errors {
        background: #ffebee;
        color: #c62828;
        padding: 12px;
        border-radius: 8px;
        margin-bottom: 15px;
    }

    ul {
        margin: 0;
        padding-left: 20px;
    }

    /* Responsive */
    @media(max-width:600px){
        .container{
            width:100%;
            padding:25px;
        }
    }

</style>
</head>

<body>

<div class="container">

<h2>Create Order</h2>

@if(session('success'))
<div class="success">
    {{ session('success') }}
</div>
@endif

@if($errors->any())
<div class="errors">
<ul>
@foreach($errors->all() as $error)
<li>{{ $error }}</li>
@endforeach
</ul>
</div>
@endif

<form method="POST" action="{{ route('order.store') }}">
@csrf

<label>Country Code</label>
<input type="text" name="country" placeholder="IN">

<label>Currency</label>
<input type="text" name="currency" placeholder="INR">

<label>Status</label>
<select name="status">
    <option value="pending">Pending</option>
    <option value="processing">Processing</option>
    <option value="delivered">Delivered</option>
</select>

<label>Products</label>
<div class="products">
@foreach($products as $product)
<label>
<input type="checkbox" name="product_ids[]" value="{{ $product->id }}">
{{ $product->name }}
</label>
@endforeach
</div>

<label>Emails (comma separated)</label>
<input type="text" name="emails" placeholder="a@gmail.com,b@gmail.com">

<button type="submit">Submit Order</button>

</form>

</div>

</body>
</html>
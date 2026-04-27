@extends('layouts.app')

@section('content')

<style>
    body {
        font-family: Arial, sans-serif;
        background: #f4f6f9;
    }

    .card {
        width: 500px;
        margin: 40px auto;
        background: #fff;
        padding: 25px;
        border-radius: 12px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }

    h2 {
        text-align: center;
        margin-bottom: 20px;
    }

    label {
        display: block;
        margin-top: 12px;
        font-weight: bold;
    }

    select, input {
        width: 100%;
        padding: 10px;
        margin-top: 5px;
        border: 1px solid #ccc;
        border-radius: 6px;
    }

    .btn-save {
        width: 100%;
        margin-top: 20px;
        padding: 10px;
        background: green;
        color: white;
        border: none;
        border-radius: 6px;
        cursor: pointer;
    }

    .back {
        display: block;
        text-align: center;
        margin-top: 15px;
        color: #555;
        text-decoration: none;
    }
</style>

<div class="card">

    <h2>Create Payment</h2>

    <form action="{{ route('payments.store') }}" method="POST">
        @csrf

        <!-- CUSTOMER -->
        <label>Customer</label>
        <select name="customer_id" required>
            <option value="">-- Select Customer --</option>
            @foreach($customers as $c)
                <option value="{{ $c->id }}">{{ $c->name }}</option>
            @endforeach
        </select>

        <!-- BILL -->
        <label>Bill</label>
        <select name="bill_id" required>
            <option value="">-- Select Bill --</option>
            @foreach($bills as $b)
                <option value="{{ $b->id }}">
                    {{ $b->usage->customer->name }} - {{ $b->usage->month }} (₱{{ $b->bill_amount }})
                </option>
            @endforeach
        </select>

        <!-- AMOUNT PAID -->
        <label>Amount Paid</label>
        <input type="number" name="amount_paid" required>

        <!-- DATE -->
        <label>Date Paid</label>
        <input type="date" name="date_paid" required>

        <button type="submit" class="btn-save">Save Payment</button>
    </form>

    <a href="{{ route('payments.index') }}" class="back">← Back</a>

</div>

@endsection

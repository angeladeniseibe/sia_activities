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

    input {
        width: 100%;
        padding: 10px;
        margin-top: 5px;
        border: 1px solid #ccc;
        border-radius: 6px;
    }

    .btn-update {
        width: 100%;
        margin-top: 20px;
        padding: 10px;
        background: green;
        color: white;
        border: none;
        border-radius: 6px;
        cursor: pointer;
    }

    .btn-cancel {
        display: block;
        text-align: center;
        margin-top: 15px;
        color: #555;
        text-decoration: none;
    }
</style>

<div class="card">

    <h2>Edit Payment</h2>

    <form action="{{ route('payments.update', $payment->id) }}" method="POST">
        @csrf
        @method('PUT')

        <!-- CUSTOMER (read-only display) -->
        <label>Customer</label>
        <input type="text" value="{{ $payment->customer->name }}" disabled>

        <!-- BILL (read-only display) -->
        <label>Bill</label>
        <input type="text" value="{{ $payment->bill->usage->month }} - ₱{{ $payment->bill->bill_amount }}" disabled>

        <!-- AMOUNT -->
        <label>Amount Paid</label>
        <input type="number" name="amount_paid" value="{{ $payment->amount_paid }}" required>

        <!-- DATE -->
        <label>Date Paid</label>
        <input type="date" name="date_paid" value="{{ $payment->date_paid }}" required>

        <button type="submit" class="btn-update">Update Payment</button>
    </form>

    <a href="{{ route('payments.index') }}" class="btn-cancel">← Cancel</a>

</div>

@endsection

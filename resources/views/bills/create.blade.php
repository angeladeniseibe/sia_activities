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
        color: #333;
    }

    label {
        display: block;
        margin-top: 12px;
        font-weight: bold;
        color: #444;
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

    .btn-save:hover {
        background: darkgreen;
    }

    .back {
        display: block;
        text-align: center;
        margin-top: 15px;
        text-decoration: none;
        color: #555;
    }

    .back:hover {
        color: #000;
    }
</style>

<div class="card">

    <h2>Create Bill</h2>

    <form action="{{ route('bills.store') }}" method="POST">
        @csrf

        <label>Select Usage:</label>
        <select name="usage_id" required>
            <option value="">-- Select Usage --</option>
            @foreach($usages as $u)
                <option value="{{ $u->id }}">
                    {{ $u->customer->name }} - {{ $u->month }} ({{ $u->kilowatts_used }} kWh)
                </option>
            @endforeach
        </select>

        <label>Bill Amount:</label>
        <input type="number" name="bill_amount" required>

        <label>Status:</label>
        <select name="status" required>
            <option value="Unpaid">Unpaid</option>
            <option value="Paid">Paid</option>
        </select>

        <label>Due Date:</label>
        <input type="date" name="due_date" required>

        <button type="submit" class="btn-save">Save Bill</button>
    </form>

    <a href="{{ route('bills.index') }}" class="back">← Back</a>

</div>

@endsection
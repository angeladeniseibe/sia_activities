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

    input, select {
        width: 100%;
        padding: 10px;
        margin-top: 5px;
        border: 1px solid #ccc;
        border-radius: 6px;
        outline: none;
        transition: 0.2s;
    }

    input:focus, select:focus {
        border-color: #007bff;
        box-shadow: 0 0 5px rgba(0,123,255,0.3);
    }

    .btn-save {
        width: 100%;
        margin-top: 20px;
        padding: 10px;
        background: linear-gradient(135deg, #28a745, #1e7e34);
        color: white;
        border: none;
        border-radius: 6px;
        font-size: 16px;
        cursor: pointer;
        transition: 0.3s;
    }

    .btn-save:hover {
        background: linear-gradient(135deg, #1e7e34, #155d27);
        transform: translateY(-2px);
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

    <h2>Add Electric Usage</h2>

    <form action="{{ route('usages.store') }}" method="POST">
        @csrf

        <label>Customer:</label>
        <select name="customer_id" required>
            @foreach($customers as $c)
                <option value="{{ $c->id }}">{{ $c->name }}</option>
            @endforeach
        </select>

        <label>KWH Used:</label>
        <input type="number" name="kilowatts_used" required>

        <label>Rate per KWH:</label>
        <input type="number" name="rate_per_kwh" step="0.01" required>

        <label>Month:</label>
        <select name="month" required>
            <option>January</option>
            <option>February</option>
            <option>March</option>
            <option>April</option>
            <option>May</option>
            <option>June</option>
            <option>July</option>
            <option>August</option>
            <option>September</option>
            <option>October</option>
            <option>November</option>
            <option>December</option>
        </select>

        <label>Year:</label>
        <input type="number" name="year" value="{{ date('Y') }}" required>

        <button type="submit" class="btn-save">Save Usage</button>
    </form>

    <a href="{{ route('usages.index') }}" class="back">← Back</a>

</div>

@endsection
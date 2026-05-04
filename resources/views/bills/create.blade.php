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
        box-sizing: border-box;
    }

    input[readonly] {
        background: #f0f0f0;
        color: #333;
        cursor: not-allowed;
    }

    .calc-hint {
        font-size: 12px;
        color: #888;
        margin-top: 4px;
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
        font-size: 14px;
        font-weight: 600;
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
        <select name="usage_id" id="usage_select" required>
            <option value="">-- Select Usage --</option>
            @foreach($usages as $u)
                <option value="{{ $u->id }}"
                        data-kwh="{{ $u->kilowatts_used }}"
                        data-rate="{{ $u->rate_per_kwh }}">
                    {{ $u->customer->name }} - {{ $u->month }} ({{ $u->kilowatts_used }} kWh)
                </option>
            @endforeach
        </select>

        <label>Bill Amount:</label>
        <input type="number"
               name="bill_amount"
               id="bill_amount"
               step="0.01"
               readonly
               placeholder="Auto-calculated"
               required>
        <span class="calc-hint">Automatically calculated: KWH Used × Rate per KWH</span>

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

<script>
    const usageSelect  = document.getElementById('usage_select');
    const billAmountInput = document.getElementById('bill_amount');

    usageSelect.addEventListener('change', function () {
        const selected = this.options[this.selectedIndex];
        const kwh  = parseFloat(selected.getAttribute('data-kwh'))  || 0;
        const rate = parseFloat(selected.getAttribute('data-rate')) || 0;

        if (kwh > 0 && rate > 0) {
            const total = (kwh * rate).toFixed(2);
            billAmountInput.value = total;
        } else {
            billAmountInput.value = '';
        }
    });
</script>

@endsection
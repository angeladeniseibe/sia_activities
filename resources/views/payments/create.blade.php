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
        box-sizing: border-box;
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
        color: #555;
        text-decoration: none;
    }

    .alert-success {
        background: #d4edda;
        color: #155724;
        padding: 10px 14px;
        border-radius: 6px;
        margin-bottom: 16px;
        font-size: 13px;
        text-align: center;
    }

    select:disabled {
        background: #f0f0f0;
        color: #999;
        cursor: not-allowed;
    }
</style>

<div class="card">

    <h2>Create Payment</h2>

    @if(session('success'))
        <div class="alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('payments.store') }}" method="POST">
        @csrf

        {{-- CUSTOMER --}}
        <label>Customer</label>
        <select name="customer_id" id="customer_select" required>
            <option value="">-- Select Customer --</option>
            @foreach($customers as $c)
                <option value="{{ $c->id }}">{{ $c->name }}</option>
            @endforeach
        </select>

        {{-- BILL — filtered by selected customer via JS --}}
        <label>Bill</label>
        <select name="bill_id" id="bill_select" required>
            <option value="">-- Select Customer First --</option>
            @foreach($bills as $b)
                <option value="{{ $b->id }}"
                        data-customer="{{ $b->usage->customer_id }}"
                        class="bill-option"
                        style="display:none;">
                    {{ $b->usage->month }} — ₱{{ $b->bill_amount }} ({{ $b->status }})
                </option>
            @endforeach
        </select>

        {{-- AMOUNT PAID --}}
        <label>Amount Paid</label>
        <input type="number"
               name="amount_paid"
               id="amount_paid"
               step="0.01"
               placeholder="Enter amount"
               required>

        {{-- ✅ FIXED — removed value so it shows mm/dd/yyyy placeholder --}}
        <label>Date Paid</label>
        <input type="date"
               name="date_paid"
               id="date_paid"
               required>

        <button type="submit" class="btn-save">Save Payment</button>
    </form>

    <a href="{{ route('payments.index') }}" class="back">← Back</a>

</div>

<script>
    const customerSelect = document.getElementById('customer_select');
    const billSelect     = document.getElementById('bill_select');
    const billOptions    = document.querySelectorAll('.bill-option');

    customerSelect.addEventListener('change', function () {
        const selectedCustomerId = this.value;

        billSelect.innerHTML = '<option value="">-- Select Bill --</option>';

        if (!selectedCustomerId) {
            billSelect.innerHTML = '<option value="">-- Select Customer First --</option>';
            return;
        }

        let found = false;
        billOptions.forEach(function (option) {
            if (option.getAttribute('data-customer') === selectedCustomerId) {
                const clone = option.cloneNode(true);
                clone.style.display = '';
                billSelect.appendChild(clone);
                found = true;
            }
        });

        if (!found) {
            billSelect.innerHTML = '<option value="">-- No unpaid bills found --</option>';
        }
    });
</script>

@endsection
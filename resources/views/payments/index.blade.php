@extends('layouts.app')

@section('content')

<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f4f6f9;
        margin: 20px;
    }

    h2 {
        text-align: center;
    }

    .container {
        width: 95%;
        margin: auto;
    }

    .top-bar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
    }

    .btn-group a {
        margin-right: 8px;
    }

    .btn-create {
        background: #28a745;
        color: white;
        padding: 8px 16px;
        text-decoration: none;
        border-radius: 6px;
    }

    .btn-pdf {
        background: #17a2b8;
        color: white;
        padding: 8px 16px;
        text-decoration: none;
        border-radius: 6px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        background: white;
        margin-top: 10px;
    }

    th {
        background: #343a40;
        color: white;
        padding: 10px;
        text-align: center;
    }

    td {
        padding: 10px;
        text-align: center;
    }

    tr:nth-child(even) {
        background: #f2f2f2;
    }

    .btn-edit {
        background: green;
        color: white;
        padding: 5px 10px;
        text-decoration: none;
        border-radius: 5px;
    }

    .btn-dashboard {
        background: linear-gradient(135deg, #6c757d, #343a40);
        color: white;
        padding: 8px 16px;
        border-radius: 8px;
        text-decoration: none;
    }

    .btn-dashboard:hover {
        background: linear-gradient(135deg, #343a40, #000);
    }
</style>

<div class="container">

    <h2>Payment Records</h2>

    <div class="top-bar">
        <a href="{{ route('dashboard') }}" class="btn-dashboard">← Dashboard</a>
        <a href="{{ route('payments.create') }}" class="btn-create">+ Create Payment</a>
    </div>

    <table>
        <thead>
            <tr>
                <th>Payment ID</th> {{-- ✅ ADDED --}}
                <th>Customer</th>
                <th>Month</th>
                <th>Amount Paid</th>
                <th>Date Paid</th>
                  @if(auth()->user()->role === 'admin')
            <th>Action</th>
                  @endif
            </tr>
        </thead>

        <tbody>
        @foreach($payments as $p)
            <tr>
                <td>{{ $p->id }}</td> {{-- ✅ ADDED --}}
                <td>{{ $p->customer->name }}</td>
                <td>{{ $p->bill->usage->month }}</td>
                <td>{{ $p->amount_paid }}</td>
                <td>{{ $p->date_paid }}</td>
                   @if(auth()->user()->role === 'admin')
            <td>
                <a href="{{ route('payments.edit', $p->id) }}" class="btn-edit">
                    Edit
                </a>
            </td>
        @endif
                
            </tr>
        @endforeach
        </tbody>
    </table>

    <a href="{{ route('payments.pdf') }}" class="btn-pdf">Download PDF</a>

</div>

@endsection

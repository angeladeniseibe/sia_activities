@extends('layouts.app')

@section('content')

<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f4f6f9;
        margin: 20px;
    }

    .container {
        width: 95%;
        margin: auto;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        background: white;
        margin-top: 15px;
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

    .btn-dashboard {
        background: linear-gradient(135deg, #6c757d, #343a40);
        color: white;
        padding: 8px 16px;
        border-radius: 8px;
        text-decoration: none;
        display: inline-block;
        font-weight: 500;
    }

    .btn-add {
        background: #007bff;
        color: white;
        padding: 8px 16px;
        text-decoration: none;
        border-radius: 6px;
        border: none;
        cursor: pointer;
    }

    .btn-pdf {
        background: #17a2b8;
        color: white;
        padding: 8px 16px;
        text-decoration: none;
        border-radius: 6px;
        margin-left: 5px;
        display: inline-block;
    }

    .btn-edit {
        background: green;
        color: white;
        padding: 5px 10px;
        text-decoration: none;
        border-radius: 4px;
    }

    .btn-delete {
        background: red;
        color: white;
        border: none;
        padding: 5px 10px;
        border-radius: 4px;
    }

    .top-bar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
    }

    .search-box {
        margin-bottom: 15px;
    }

    .search-box input {
        padding: 6px;
        width: 250px;
    }
</style>

<div class="container">

    <h2 class="text-center">Electric Bills</h2>

    <div class="top-bar">

        <a href="{{ route('dashboard') }}" class="btn-dashboard">
            ← Dashboard
        </a>

        {{-- STAFF + ADMIN: can add bill --}}
        @if(in_array(auth()->user()->role, ['admin', 'staff']))
            <a href="{{ route('bills.create') }}" class="btn-add">
                + Add Bill
            </a>
        @endif

    </div>

    {{-- ✅ SEARCH BAR (ADDED ONLY CHANGE) --}}
    <form method="GET" class="search-box">
        <input type="text"
               name="search"
               value="{{ request('search') }}"
               placeholder="Search bill amount, status, or date">

        <button type="submit" class="btn-add">
            Search
        </button>
    </form>

    <table>

        <thead>
            <tr>
                <th>Bill ID</th>
                <th>Customer</th>
                <th>Month</th>
                <th>Bill Amount</th>
                <th>Status</th>
                <th>Due Date</th>

                {{-- ADMIN ONLY --}}
                @if(auth()->user()->role === 'admin')
                    <th>Action</th>
                @endif
            </tr>
        </thead>

        <tbody>
        @foreach($bills as $b)
            <tr>
                <td>{{ $b->id }}</td>
                <td>{{ $b->usage->customer->name }}</td>
                <td>{{ $b->usage->month }}</td>
                <td>{{ $b->bill_amount }}</td>
                <td>{{ $b->status }}</td>
                <td>{{ $b->due_date }}</td>

                {{-- ADMIN ONLY ACTIONS --}}
                @if(auth()->user()->role === 'admin')
                <td>
                    <a href="{{ route('bills.edit', $b->id) }}" class="btn-edit">
                        Edit
                    </a>

                    <form action="{{ route('bills.destroy', $b->id) }}"
                          method="POST"
                          style="display:inline;"
                          onsubmit="return confirm('Delete this bill?')">

                        @csrf
                        @method('DELETE')

                        <button class="btn-delete">Delete</button>
                    </form>
                </td>
                @endif

            </tr>
        @endforeach
        </tbody>

    </table>

    {{-- STAFF + ADMIN ONLY PDF --}}
    @if(in_array(auth()->user()->role, ['admin', 'staff']))
        <a href="{{ route('bills.pdf') }}" class="btn-pdf">
            Download PDF
        </a>
    @endif

</div>

@endsection

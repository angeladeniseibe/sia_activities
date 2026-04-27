@extends('layouts.app')

@section('content')

<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f4f6f9;
        margin: 20px;
    }

    h1 {
        text-align: center;
    }

    .container {
        width: 95%;
        margin: auto;
    }

    .btn-add {
        background-color: #007bff;
        color: white;
        padding: 8px 20px;
        text-decoration: none;
        border-radius: 5px;
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

    .btn-edit {
        background: green;
        color: white;
        border: none;
        padding: 5px 10px;
        text-decoration: none;
    }

    .btn-delete {
        background: red;
        color: white;
        border: none;
        padding: 5px 10px;
        text-decoration: none;
    }

    .top-bar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
    }

    .btn-dashboard {
        background: linear-gradient(135deg, #6c757d, #343a40);
        color: white;
        padding: 8px 16px;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 500;
    }

    .btn-pdf {
        background: #17a2b8;
        color: white;
        padding: 8px 16px;
        text-decoration: none;
        border-radius: 6px;
        margin-left: 5px;
    }

    /* ✅ SEARCH STYLE */
    .search-box {
        margin: 15px 0;
        display: flex;
        gap: 10px;
    }

    .search-box input {
        padding: 6px;
        width: 250px;
    }
</style>

<div class="container mt-4">

    <h2 class="text-center mb-3">Electric Usages</h2>

    <div class="top-bar">
        <a href="{{ route('dashboard') }}" class="btn btn-dashboard">
            ← Dashboard
        </a>

        {{-- STAFF + ADMIN --}}
        @if(in_array(auth()->user()->role, ['admin', 'staff']))
            <a href="{{ route('usages.create') }}" class="btn-add">
                + Add Usage
            </a>
        @endif
    </div>

    {{-- ✅ SEARCH BAR (ADDED ONLY CHANGE) --}}
    <form method="GET" class="search-box">
        <input type="text"
               name="search"
               value="{{ request('search') }}"
               placeholder="Search month, year, or customer">

        <button type="submit" class="btn-add">
            Search
        </button>
    </form>

    <table class="table table-bordered text-center">

        <thead class="table-dark">
            <tr>
                <th>Usage ID</th>
                <th>Customer</th>
                <th>KWH Used</th>
                <th>Rate</th>
                <th>Month</th>
                <th>Year</th>

                @if(auth()->user()->role === 'admin')
                    <th>Actions</th>
                @endif
            </tr>
        </thead>

        <tbody>

        @foreach($usages as $usage)
            <tr>
                <td>{{ $usage->id }}</td>
                <td>{{ $usage->customer->name }}</td>
                <td>{{ $usage->kilowatts_used }}</td>
                <td>{{ $usage->rate_per_kwh }}</td>
                <td>{{ $usage->month }}</td>
                <td>{{ $usage->year }}</td>

                @if(auth()->user()->role === 'admin')
                <td>
                    <a href="{{ route('usages.edit', $usage->id) }}"
                       class="btn-edit">
                        Edit
                    </a>

                    <form action="{{ route('usages.destroy', $usage->id) }}"
                          method="POST"
                          style="display:inline;">

                        @csrf
                        @method('DELETE')

                        <button class="btn-delete"
                                onclick="return confirm('Delete this usage?')">
                            Delete
                        </button>

                    </form>
                </td>
                @endif

            </tr>
        @endforeach

        </tbody>

    </table>

    {{-- STAFF + ADMIN PDF --}}
    @if(in_array(auth()->user()->role, ['admin', 'staff']))
        <a href="{{ route('usages.pdf') }}" class="btn-pdf">
            Download PDF
        </a>
    @endif

</div>

@endsection

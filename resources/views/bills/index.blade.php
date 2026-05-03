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
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    }

    th {
        background: #343a40;
        color: white;
        padding: 10px;
        text-align: center;
        font-size: 13px;
    }

    th.actions-col {
        background: #1a2533;
    }

    td {
        padding: 10px;
        text-align: center;
        font-size: 13px;
        color: #333;
    }

    tr:nth-child(even) td {
        background: #f2f2f2;
    }

    tr:hover td {
        background: #eef2ff;
    }

    td.actions-cell {
        background: linear-gradient(135deg, #e8f5e9, #fff3e0);
        border-left: 3px solid #c8e6c9;
    }

    tr:nth-child(even) td.actions-cell {
        background: linear-gradient(135deg, #dcedc8, #fff8e1);
    }

    .btn-edit {
        background: #28a745;
        color: white;
        border: none;
        padding: 5px 12px;
        border-radius: 5px;
        text-decoration: none;
        font-size: 12px;
        font-weight: 500;
        cursor: pointer;
        display: inline-block;
    }

    .btn-delete {
        background: #dc3545;
        color: white;
        border: none;
        padding: 5px 12px;
        border-radius: 5px;
        font-size: 12px;
        font-weight: 500;
        cursor: pointer;
        display: inline-block;
    }

    .btn-dashboard {
        background: linear-gradient(135deg, #6c757d, #343a40);
        color: white;
        padding: 8px 16px;
        border-radius: 8px;
        text-decoration: none;
        display: inline-block;
        font-weight: 500;
        font-size: 13px;
    }

    .btn-add {
        background: #007bff;
        color: white;
        padding: 8px 16px;
        text-decoration: none;
        border-radius: 6px;
        border: none;
        cursor: pointer;
        font-size: 13px;
    }

    .btn-pdf {
        background: #17a2b8;
        color: white;
        padding: 8px 16px;
        text-decoration: none;
        border-radius: 6px;
        margin-top: 14px;
        display: inline-block;
        font-size: 13px;
    }

    .top-bar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
    }

    .search-box {
        margin: 15px 0;
        display: flex;
        gap: 10px;
    }

    .search-box input {
        padding: 6px 10px;
        width: 250px;
        border: 1px solid #ccc;
        border-radius: 6px;
        font-size: 13px;
    }

    /* Styled Pagination */
    .pagination-wrapper {
        margin-top: 20px;
        display: flex;
        justify-content: center;
        gap: 6px;
        flex-wrap: wrap;
    }

    .pagination-wrapper .page-item .page-link {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 34px;
        height: 34px;
        border-radius: 8px !important;
        font-size: 13px;
        font-weight: 500;
        border: 1px solid #dee2e6;
        color: #495057;
        background: #fff;
        transition: all 0.15s;
        text-decoration: none;
    }

    .pagination-wrapper .page-item .page-link:hover {
        background: #e9ecef;
        border-color: #adb5bd;
        color: #007bff;
    }

    .pagination-wrapper .page-item.active .page-link {
        background: #007bff;
        color: #fff;
        border-color: #007bff;
    }

    .pagination-wrapper .page-item.disabled .page-link {
        opacity: 0.4;
        pointer-events: none;
    }

    .pagination-wrapper nav {
        display: flex;
        justify-content: center;
    }

    .pagination-wrapper ul.pagination {
        display: flex;
        gap: 6px;
        list-style: none;
        padding: 0;
        margin: 0;
        flex-wrap: wrap;
        justify-content: center;
    }
</style>

<div class="container">

    <h2 class="text-center mb-3">Electric Bills</h2>

    <div class="top-bar">
        <a href="{{ route('dashboard') }}" class="btn-dashboard">← Dashboard</a>

        @if(in_array(auth()->user()->role, ['admin', 'staff']))
            <a href="{{ route('bills.create') }}" class="btn-add">+ Add Bill</a>
        @endif
    </div>

    <form method="GET" class="search-box">
        <input type="text"
               name="search"
               value="{{ request('search') }}"
               placeholder="Search bill amount, status, or date">
        <button type="submit" class="btn-add">Search</button>
    </form>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Customer</th>
                <th>Month</th>
                <th>Bill Amount</th>
                <th>Status</th>
                <th>Due Date</th>

                @if(auth()->user()->role === 'admin')
                    <th class="actions-col">Action</th>
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

                @if(auth()->user()->role === 'admin')
                <td class="actions-cell">
                    <a href="{{ route('bills.edit', $b->id) }}" class="btn-edit">Edit</a>
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

    {{-- ✅ appends() keeps search keyword across pages --}}
    <div class="pagination-wrapper">
        {{ $bills->appends(request()->query())->links() }}
    </div>

    @if(in_array(auth()->user()->role, ['admin', 'staff']))
        <a href="{{ route('bills.pdf') }}" class="btn-pdf">Download PDF</a>
    @endif

</div>

@endsection
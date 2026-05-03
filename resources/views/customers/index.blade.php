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

    .btn-add {
        background-color: #007bff;
        color: white;
        padding: 8px 20px;
        text-decoration: none;
        border-radius: 5px;
        font-size: 13px;
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
        font-size: 13px;
    }

    .btn-dashboard:hover {
        background: linear-gradient(135deg, #343a40, #000000);
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

    .success {
        color: green;
        text-align: center;
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

    <h2 class="text-center mb-3">Customer List</h2>

    <div class="top-bar">
        <a href="{{ route('dashboard') }}" class="btn-dashboard">← Dashboard</a>
        <a href="{{ route('customers.create') }}" class="btn-add">+ Add Customer</a>
    </div>

    @if(session('success'))
        <p class="success">{{ session('success') }}</p>
    @endif

    <form method="GET" action="{{ route('customers.index') }}" class="search-box">
        <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Search...">
        <button type="submit" class="btn-add">Search</button>
    </form>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Address</th>
                <th>Gender</th>
                <th>DOB</th>
                <th class="actions-col">Actions</th>
            </tr>
        </thead>

        <tbody>
        @foreach($customers as $customer)
            <tr>
                <td>{{ $customer->id }}</td>
                <td>{{ $customer->name }}</td>
                <td>{{ $customer->address }}</td>
                <td>{{ $customer->gender }}</td>
                <td>{{ $customer->dob }}</td>
                <td class="actions-cell">
                    <a href="{{ route('customers.edit', $customer->id) }}" class="btn-edit">Edit</a>
                    <form action="{{ route('customers.destroy', $customer->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button class="btn-delete" onclick="return confirm('Delete?')">Delete</button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <div class="pagination-wrapper">
        {{ $customers->appends(request()->query())->links() }}
    </div>

    <a href="{{ route('customers.pdf') }}" class="btn-pdf">Download PDF</a>

</div>

@endsection
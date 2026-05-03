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
        background: linear-gradient(135deg, #343a40, #000);
    }

    .btn-create {
        background: #28a745;
        color: white;
        padding: 8px 16px;
        text-decoration: none;
        border-radius: 6px;
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

    table {
        width: 100%;
        border-collapse: collapse;
        background: white;
        margin-top: 10px;
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

    <h2 class="text-center mb-3">Payment Records</h2>

    <div class="top-bar">
        <a href="{{ route('dashboard') }}" class="btn-dashboard">← Dashboard</a>
        <a href="{{ route('payments.create') }}" class="btn-create">+ Create Payment</a>
    </div>

    <table>
        <thead>
            <tr>
                <th>Payment ID</th>
                <th>Customer</th>
                <th>Month</th>
                <th>Amount Paid</th>
                <th>Date Paid</th>
                <th class="actions-col">Action</th>
            </tr>
        </thead>

        <tbody>
        @foreach($payments as $p)
            <tr>
                <td>{{ $p->id }}</td>
                <td>{{ $p->customer->name }}</td>
                <td>{{ $p->bill->usage->month }}</td>
                <td>{{ $p->amount_paid }}</td>
                <td>{{ $p->date_paid }}</td>
                <td class="actions-cell">
                    <a href="{{ route('payments.edit', $p->id) }}" class="btn-edit">Edit</a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <div class="pagination-wrapper">
        {{ $payments->links() }}
    </div>

    <a href="{{ route('payments.pdf') }}" class="btn-pdf">Download PDF</a>

</div>

@endsection
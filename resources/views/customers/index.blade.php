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
        padding: 5px 10px;
        text-decoration: none;
        border: none;
    }

    .btn-delete {
        background: red;
        color: white;
        border: none;
        padding: 5px 10px;
    }

    .success {
        color: green;
        text-align: center;
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
    }

    .btn-dashboard:hover {
        background: linear-gradient(135deg, #343a40, #000000);
        transform: translateY(-2px);
    }

    .btn-pdf {
        background: #17a2b8;
        color: white;
        padding: 8px 16px;
        text-decoration: none;
        border-radius: 6px;
        margin-left: 5px;
    }
</style>

<div class="container">

    <h1>Customer List</h1>

    <div class="top-bar">
        <a href="{{ route('dashboard') }}" class="btn-dashboard">
            ← Dashboard
        </a>

        <a href="{{ route('customers.create') }}" class="btn-add">+ Add Customer</a>
    </div>

    @if(session('success'))
        <p class="success">{{ session('success') }}</p>
    @endif

    <form method="GET" action="{{ route('customers.index') }}">
        <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Search...">
        <button type="submit">Search</button>
    </form>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Address</th>
                <th>Gender</th>
                <th>DOB</th>

                <!-- ✅ ADDED -->
                <th>User</th>
                <th>Assign User</th>

                <th>Actions</th>
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

                <!-- ✅ SHOW ASSIGNED USER -->
                <td>
                    {{ $customer->user->name ?? 'Unassigned' }}
                </td>

                <!-- ✅ ASSIGN USER DROPDOWN -->
                <td>
                    <form method="POST" action="{{ route('customers.assignUser', $customer->id) }}">
                        @csrf

                        <select name="user_id" required>
                            <option value="">Select User</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}"
                                    {{ $customer->user_id == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>

                        <button type="submit" class="btn-edit">Assign</button>
                    </form>
                </td>

                <!-- ACTIONS -->
                <td>
                    <a href="{{ route('customers.edit', $customer->id) }}" class="btn-edit">Edit</a>

                    <form action="{{ route('customers.destroy', $customer->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button class="btn-delete" onclick="return confirm('Delete?')">
                            Delete
                        </button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <div style="margin-top: 20px;">
        {{ $customers->appends(request()->query())->links() }}
    </div>

    <br>

    <a href="{{ route('customers.pdf') }}" class="btn-pdf">
        Download PDF
    </a>

</div>

@endsection

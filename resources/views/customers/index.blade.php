<!DOCTYPE html>
<html>
<head>
    <title>Customer List</title>

    <!-- BOOTSTRAP (IMPORTANT FOR PAGINATION) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

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
            margin-bottom: 10px;
        }
    </style>
</head>

<body>

<div class="container">

    <h1>Customer List</h1>

    <div class="top-bar">
        <a href="{{ route('customers.create') }}" class="btn-add">+ Add Customer</a>
    </div>

    @if(session('success'))
        <p class="success">{{ session('success') }}</p>
    @endif

    <!-- SEARCH -->
    <form method="GET" action="{{ route('customers.index') }}">
        <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Search...">
        <button type="submit">Search</button>
    </form>

    <!-- TABLE -->
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Address</th>
                <th>Gender</th>
                <th>DOB</th>
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

    <!-- PAGINATION (FIXED) -->
    <div style="margin-top: 20px;">
        {{ $customers->appends(request()->query())->links() }}
    </div>

    <br>

    <a href="{{ route('customers.pdf') }}">Download PDF</a>

</div>

</body>
</html>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Usage</title>

    <style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f4f6f9;
        margin: 0;
        padding: 0;
    }

    .container {
        max-width: 600px;
        margin: 50px auto;
        background-color: #fff;
        padding: 30px 40px;
        border-radius: 10px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }

    h1 {
        text-align: center;
        color: #333;
        margin-bottom: 30px;
    }

    label {
        display: block;
        margin-bottom: 6px;
        font-weight: bold;
        color: #555;
    }

    input[type="text"],
    input[type="number"],
    select {
        width: 100%;
        padding: 10px 12px;
        margin-bottom: 20px;
        border: 1px solid #ccc;
        border-radius: 6px;
        font-size: 14px;
        box-sizing: border-box;
        transition: border-color 0.3s;
    }

    input:focus,
    select:focus {
        border-color: #007bff;
        outline: none;
    }

    .button-group {
        display: flex;
        gap: 10px;
    }

    .btn-save {
        background-color: #28a745;
        color: white;
        padding: 10px 20px;
        border-radius: 6px;
        border: none;
        cursor: pointer;
        font-size: 14px;
    }

    .btn-save:hover {
        background-color: #218838;
    }

    .btn-cancel {
        background-color: #6c757d;
        color: white;
        text-align: center;
        padding: 10px 20px;
        border-radius: 6px;
        text-decoration: none;
        font-size: 14px;
    }

    .btn-cancel:hover {
        background-color: #5a6268;
    }
    </style>
</head>

<body>

<div class="container">

    <h1>Edit Usage</h1>

    <form action="{{ route('usages.update', $usage->id) }}" method="POST">
        @csrf
        @method('PUT')

        <label>Customer:</label>
        <select name="customer_id" required>
            <option value="">-- Select Customer --</option>
            @foreach($customers as $c)
                <option value="{{ $c->id }}"
                    {{ $usage->customer_id == $c->id ? 'selected' : '' }}>
                    {{ $c->name }}
                </option>
            @endforeach
        </select>

        <label>Kilowatts Used:</label>
        <input type="number" name="kilowatts_used" value="{{ $usage->kilowatts_used }}" required>

        <label>Rate per KWH:</label>
        <input type="number" step="0.01" name="rate_per_kwh" value="{{ $usage->rate_per_kwh }}" required>

        <label>Month:</label>
        <input type="text" name="month" value="{{ $usage->month }}" required>

        <label>Year:</label>
        <input type="number" name="year" value="{{ $usage->year }}" required>

        <div class="button-group">
            <button type="submit" class="btn-save">
                Update Usage
            </button>

            <a href="{{ route('usages.index') }}" class="btn-cancel">
                Cancel
            </a>
        </div>

    </form>

</div>

</body>
</html>
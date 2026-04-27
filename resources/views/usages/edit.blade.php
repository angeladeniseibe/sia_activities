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
        justify-content: flex-start;
        gap: 10px;
    }

    /* Remove default button styling (IMPORTANT FIX) */
    /* button {
        background-color: #28a745;
        color: white;
    }

    button:hover {
        background-color: #218838;
    } */

    .btn-cancel {
        background-color: #6c757d;
        color: white;
        text-align: center;
        padding: 10px 20px;
        border-radius: 6px;
        text-decoration: none;
    }

    .btn-cancel:hover {
        background-color: #5a6268;
    }

    /* ----------------------------------- */
    /* 🔵 EDIT & 🔴 DELETE BUTTONS          */
    /* ----------------------------------- */

    .action-btn {
        padding: 10px 20px;
        border-radius: 6px;
        border: none;
        color: white;
        cursor: pointer;
        text-decoration: none;
        font-size: 14px;
        transition: background-color 0.3s;
    }

    /* Edit button = blue */
    .edit-btn {
        background-color: #007bff;
    }

    .edit-btn:hover {
        background-color: #0069d9;
    }

    /* Delete button = red */
    .delete-btn {
        background-color: #dc3545;
    }

    .delete-btn:hover {
        background-color: #c82333;
    }
    </style>
</head>

<body>

<div class="container">

    <h1>Edit Usage</h1>

    <form action="{{ route('usages.update', $usage->id) }}" method="POST">
        @csrf
        @method('PUT')

        <label for="customer_id">Customer:</label>
        <select name="customer_id" id="customer_id" required>
            <option value="">-- Select Customer --</option>
            @foreach($customers as $c)
                <option value="{{ $c->id }}" {{ $usage->customer_id == $c->id ? 'selected' : '' }}>
                    {{ $c->name }}
                </option>
            @endforeach
        </select>

        <label for="kilowatts_used">Kilowatts Used:</label>
        <input type="number" id="kilowatts_used" name="kilowatts_used" value="{{ $usage->kilowatts_used }}" required>

        <label for="rate_per_kwh">Rate per KWH:</label>
        <input type="number" step="0.01" id="rate_per_kwh" name="rate_per_kwh" value="{{ $usage->rate_per_kwh }}" required>

        <label for="month">Month:</label>
        <input type="text" id="month" name="month" value="{{ $usage->month }}" required>

        <label for="year">Year:</label>
        <input type="number" id="year" name="year" value="{{ $usage->year }}" required>

        <div class="button-group">
            <button type="submit" class="action-btn edit-btn">
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

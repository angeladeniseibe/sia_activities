<!DOCTYPE html>
<html>
<head>
    <title>Edit Bill</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f6f9;
        }

        .container {
            max-width: 600px;
            margin: 50px auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        h2 {
            text-align: center;
        }

        label {
            font-weight: bold;
            display: block;
            margin-top: 10px;
        }

        input, select {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 6px;
        }

        input:focus, select:focus {
            border-color: #28a745;
            outline: none;
        }

        .btn-update {
            background: green;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }

        .btn-update:hover {
            background: darkgreen;
        }

        .btn-cancel {
            background: gray;
            color: white;
            padding: 10px 15px;
            text-decoration: none;
            border-radius: 6px;
            margin-left: 5px;
        }

        .btn-cancel:hover {
            background: #555;
        }
    </style>
</head>

<body>

<div class="container">

    <h2>Edit Bill</h2>

    <form action="{{ route('bills.update', $bill->id) }}" method="POST">
        @csrf
        @method('PUT')

        <label>Bill Amount</label>
        <input type="number" name="bill_amount" value="{{ $bill->bill_amount }}" required>

        <label>Status</label>
        <select name="status">
            <option value="Unpaid" {{ $bill->status == 'Unpaid' ? 'selected' : '' }}>Unpaid</option>
            <option value="Paid" {{ $bill->status == 'Paid' ? 'selected' : '' }}>Paid</option>
        </select>

        <label>Due Date</label>
        <input type="date" name="due_date" value="{{ $bill->due_date }}" required>

        <button type="submit" class="btn-update">Update Bill</button>
        <a href="{{ route('bills.index') }}" class="btn-cancel">Cancel</a>

    </form>

</div>

</body>
</html>
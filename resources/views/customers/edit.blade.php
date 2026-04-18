<!DOCTYPE html>
<html>
<head>
    <title>Edit Customer</title>
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
        input[type="date"],
        textarea,
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

        input[type="text"]:focus,
        input[type="date"]:focus,
        textarea:focus,
        select:focus {
            border-color: #007bff;
            outline: none;
        }

        textarea {
            resize: vertical;
            min-height: 80px;
        }

        .button-group {
            display: flex;
            justify-content: flex-start;
            gap: 10px;
        }

        button, .btn-cancel {
            padding: 10px 20px;
            font-size: 14px;
            border-radius: 6px;
            cursor: pointer;
            border: none;
            transition: background-color 0.3s;
            text-decoration: none;
        }

        button {
            background-color: #28a745; /* green */
            color: white;
        }

        button:hover {
            background-color: #218838;
        }

        .btn-cancel {
            background-color: #6c757d; /* gray */
            color: white;
            text-align: center;
            line-height: normal;
        }

        .btn-cancel:hover {
            background-color: #5a6268;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Edit Customer</h1>
    <form action="{{ route('customers.update', $customer->id) }}" method="POST">
        @csrf
        @method('PUT')

        <label for="name">Name:</label>
        <input type="text" id="name" name="name" value="{{ $customer->name }}" placeholder="Enter customer name" required>

        <label for="address">Address:</label>
        <textarea id="address" name="address" placeholder="Enter customer address" required>{{ $customer->address }}</textarea>

        <label for="gender">Gender:</label>
        <select id="gender" name="gender" required>
            <option value="">-- Select Gender --</option>
            <option value="Male" {{ $customer->gender == 'Male' ? 'selected' : '' }}>Male</option>
            <option value="Female" {{ $customer->gender == 'Female' ? 'selected' : '' }}>Female</option>
        </select>

        <label for="dob">Date of Birth:</label>
        <input type="date" id="dob" name="dob" value="{{ $customer->dob }}" required>

        <div class="button-group">
            <button type="submit">Update Customer</button>
            <a href="{{ route('customers.index') }}" class="btn-cancel">Cancel</a>
        </div>
    </form>
</div>

</body>
</html>
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
        }

        .button-group {
            display: flex;
            gap: 10px;
        }

        button, .btn-cancel {
            padding: 10px 20px;
            border-radius: 6px;
            border: none;
            cursor: pointer;
            text-decoration: none;
        }

        button {
            background-color: #28a745;
            color: white;
        }

        .btn-cancel {
            background-color: #6c757d;
            color: white;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Edit Customer</h1>

    <form action="{{ route('customers.update', $customer->id) }}" method="POST">
        @csrf
        @method('PUT')

        <label>Name:</label>
        <input type="text" name="name" value="{{ $customer->name }}" required>

        <label>Address:</label>
        <textarea name="address" required>{{ $customer->address }}</textarea>

        <label>Gender:</label>
        <select name="gender" required>
            <option value="">-- Select Gender --</option>
            <option value="Male" {{ $customer->gender == 'Male' ? 'selected' : '' }}>Male</option>
            <option value="Female" {{ $customer->gender == 'Female' ? 'selected' : '' }}>Female</option>
        </select>

        <label>Date of Birth:</label>
        <input type="date" name="dob" value="{{ $customer->dob }}" required>

        <!-- ✅ ADDED USER ASSIGNMENT -->
        <label>Assign User:</label>
        <select name="user_id">
            <option value="">-- Unassigned --</option>
            @foreach($users as $user)
                <option value="{{ $user->id }}"
                    {{ $customer->user_id == $user->id ? 'selected' : '' }}>
                    {{ $user->name }}
                </option>
            @endforeach
        </select>

        <div class="button-group">
            <button type="submit">Update Customer</button>
            <a href="{{ route('customers.index') }}" class="btn-cancel">Cancel</a>
        </div>
    </form>
</div>

</body>
</html>

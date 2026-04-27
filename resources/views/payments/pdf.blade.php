<!DOCTYPE html>
<html>
<head>
    <title>Payment Report</title>

    <style>
        body {
            font-family: Arial;
            font-size: 12px;
        }

        h2 {
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid #000;
            padding: 8px;
            text-align: center;
        }

        th {
            background: #333;
            color: white;
        }
    </style>
</head>

<body>

<h2>Payment Records Report</h2>

<table>
    <thead>
        <tr>
            <th>Customer</th>
            <th>Month</th>
            <th>Amount Paid</th>
            <th>Date Paid</th>
        </tr>
    </thead>

    <tbody>
        @foreach($payments as $p)
        <tr>
            <td>{{ $p->customer->name }}</td>
            <td>{{ $p->bill->usage->month }}</td>
            <td>{{ $p->amount_paid }}</td>
            <td>{{ $p->date_paid }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

</body>
</html>

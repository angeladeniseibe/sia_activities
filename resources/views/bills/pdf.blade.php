<h2>Electric Bills Report</h2>

<table border="1" width="100%" cellpadding="5">
    <tr>
        <th>Customer</th>
        <th>Month</th>
        <th>Amount</th>
        <th>Status</th>
        <th>Due Date</th>
    </tr>

    @foreach($bills as $b)
    <tr>
        <td>{{ $b->usage->customer->name }}</td>
        <td>{{ $b->usage->month }}</td>
        <td>{{ $b->bill_amount }}</td>
        <td>{{ $b->status }}</td>
        <td>{{ $b->due_date }}</td>
    </tr>
    @endforeach
</table>

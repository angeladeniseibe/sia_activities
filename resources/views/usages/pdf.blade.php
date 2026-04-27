<h2>Electric Usage Report</h2>

<table border="1" width="100%" cellpadding="5">
    <tr>
        <th>Customer</th>
        <th>KWH Used</th>
        <th>Rate</th>
        <th>Month</th>
        <th>Year</th>
    </tr>

    @foreach ($usages as $usage)
    <tr>
        <td>{{ $usage->customer->name }}</td>
        <td>{{ $usage->kilowatts_used }}</td>
        <td>{{ $usage->rate_per_kwh }}</td>
        <td>{{ $usage->month }}</td>
        <td>{{ $usage->year }}</td>
    </tr>
    @endforeach
</table>

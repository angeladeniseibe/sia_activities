<h2>Customer Report</h2>

<table border="1" width="100%" cellpadding="5">
    <tr>
        <th>Name</th>
        <th>Address</th>
        <th>Gender</th>
        <th>DOB</th>
    </tr>

    @foreach ($customers as $c)
    <tr>
        <td>{{ $c->name }}</td>
        <td>{{ $c->address }}</td>
        <td>{{ $c->gender }}</td>
        <td>{{ $c->dob }}</td>
    </tr>
    @endforeach
</table>
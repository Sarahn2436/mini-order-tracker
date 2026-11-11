<a href="{{ route('customers.create') }}">
    Add Customer
</a>
<!DOCTYPE html>
<html>

    <head>
        <title>Customers</title>
    </head>

    <body>

        <h1>All Customers</h1>

        @if (session('success'))
            <p role="status">{{ session('success') }}</p>
        @endif

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Company</th>
                    <th>Created At</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($customers as $customer)
                    <tr>
                        <td>{{ $customer->id }}</td>
                        <td>{{ $customer->name }}</td>
                        <td>{{ $customer->email }}</td>
                        <td>{{ $customer->company ?? 'N/A' }}</td>
                        <td>{{ $customer->created_at }}</td>

                        <td>
                            <a href="{{ route('customers.edit', $customer->id) }}">
                                Edit
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

    </body>

</html>

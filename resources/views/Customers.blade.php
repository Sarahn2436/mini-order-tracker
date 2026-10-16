@extends('layouts.app')

@section('content')
    <h1>Customers</h1>

    <table border="1" style="width: 100%; text-align: left; border-collapse: collapse; margin-top: 20px;">
        <thead>
            <tr style="background-color: #f2f2f2;">
                <th style="padding: 10px;">Name</th>
                <th style="padding: 10px;">Email</th>
                <th style="padding: 10px;">Company</th>
            </tr>
        </thead>
        <tbody>
            @foreach($customers as $customer)
                <tr>
                    <td style="padding: 10px;">{{ $customer->name }}</td>
                    <td style="padding: 10px;">{{ $customer->email }}</td>
                    <td style="padding: 10px;">{{ $customer->company ?? 'N/A' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection

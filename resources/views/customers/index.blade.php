@extends('layouts.app')

@section('content')
    <div class="container" style="font-family: 'Lato', sans-serif; margin-top: 20px;">
        <h1 style="font-size: 28px; color: #111; margin-bottom: 24px; font-weight: 700;">Customers</h1>

        <div style="margin-bottom: 20px;">
            <a href="{{ route('customers.create') }}"
                style="background: #333; color: white; padding: 8px 16px; text-decoration: none; border-radius: 4px; display: inline-block; font-size: 14px;">
                + Add New Customer
            </a>
        </div>

        <table border="1" cellpadding="10"
            style="width: 100%; border-collapse: collapse; text-align: left; font-size: 14px; border-color: black; margin-bottom: 20px;">
            <thead>
                <tr style="background-color: #f2f2f2; font-weight: 700; color: #111;">
                    <th>Name</th>
                    <th>Email</th>
                    <th>Company</th>
                    <th style="text-align: center;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($customers as $customer)
                    <tr>
                        <td>
                            <a href="{{ route('customers.show', $customer->id) }}" style="font-weight: 600;">
                                {{ $customer->name }}
                            </a>
                        </td>
                        <td>{{ $customer->email }}</td>
                        <td>{{ $customer->company ?? '—' }}</td>
                        <td style="text-align: center; white-space: nowrap;">
                            <a href="{{ route('customers.show', $customer->id) }}"
                                style="color: #0000ee; text-decoration: underline; margin-right: 10px;">View</a>
                            <a href="{{ route('customers.edit', $customer->id) }}"
                                style="color: #0000ee; text-decoration: underline; margin-right: 10px;">Edit</a>

                            <form action="{{ route('customers.destroy', $customer->id) }}" method="POST"
                                style="display: inline-block;"
                                onsubmit="return confirm('Are you sure you want to delete this customer?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    style="background: none; border: none; color: #0000ee; padding: 0; cursor: pointer; font-family: inherit; text-decoration: underline; font-size: inherit;">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" style="text-align: center; color: #888;">No customers found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if(method_exists($customers, 'links'))
            <style>
                .custom-pagination nav {
                    display: block !important;
                }

                .custom-pagination nav div p {
                    display: block !important;
                    font-size: 15px !important;
                    color: #111 !important;
                    margin: 0 0 12px 0 !important;
                    font-family: inherit;
                }

                .custom-pagination nav div p span {
                    border: none !important;
                    background: none !important;
                    padding: 0 !important;
                    display: inline !important;
                    min-width: 0 !important;
                    height: auto !important;
                    font-weight: bold !important;
                }

                .custom-pagination ul {
                    list-style-type: none !important;
                    padding: 0 !important;
                    margin: 0 !important;
                    display: flex !important;
                    flex-direction: row !important;
                    flex-wrap: wrap !important;
                    gap: 6px !important;
                    align-items: center !important;
                }

                .custom-pagination li {
                    display: inline-block !important;
                }

                .custom-pagination ul a,
                .custom-pagination ul span {
                    display: flex !important;
                    align-items: center !important;
                    justify-content: center !important;
                    text-decoration: none !important;
                    padding: 6px 12px !important;
                    min-width: 36px !important;
                    height: 36px !important;
                    border: 1px solid #ced4da !important;
                    border-radius: 4px !important;
                    color: #333 !important;
                    background-color: #fff !important;
                    font-size: 14px !important;
                    box-sizing: border-box !important;
                }

                .custom-pagination ul li[class*="active"] span,
                .custom-pagination ul span[aria-current="page"] {
                    background-color: #2d3748 !important;
                    color: white !important;
                    border-color: #2d3748 !important;
                    font-weight: 600 !important;
                }

                .custom-pagination ul li[class*="disabled"] span,
                .custom-pagination ul span[aria-disabled="true"] {
                    color: #a0aec0 !important;
                    background-color: #f7fafc !important;
                    border-color: #e2e8f0 !important;
                    cursor: not-allowed !important;
                }
            </style>


            <div class="custom-pagination" style="margin-top: 20px; margin-bottom: 40px;">
                {{ $customers->appends(request()->query())->links() }}
            </div>
        @endif
    </div>
@endsection
<form action="{{ route('customers.store') }}" method="PUT">

    @csrf  
    <input type="text" ; name="First Name" ; placeholder="First name">
    @error('name')
    <p>{{ $message }}</p>
@enderror

    <input type="text" ; name="Last Name" ; placeholder="Last name">
    @error('name')
    <p>{{ $message }}</p>
@enderror

    <input type="email"; name="Email"; placeholder="E-mail">
    @error('name')
    <p>{{ $message }}</p>
@enderror

    <input type="text"; name="Company"; placeholder="Company name (optional)">
    @error('name')
    <p>{{ $message }}</p>
@enderror

    <button type="submit">Create New Customer</button>
    
</form>
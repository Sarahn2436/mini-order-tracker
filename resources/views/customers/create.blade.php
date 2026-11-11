<form action="{{ route('customers.store') }}" method="POST">
    @csrf

    <input type="text" name="name" placeholder="Full Name" value="{{ old('name') }}">
    @error('name')
        <p>{{ $message }}</p>
    @enderror

    <input type="email" name="email" placeholder="E-mail" value="{{ old('email') }}">
    @error('email')
        <p>{{ $message }}</p>
    @enderror

    <input type="text" name="company" placeholder="Company name (optional)" value="{{ old('company') }}">
    @error('company')
        <p>{{ $message }}</p>
    @enderror

    <button type="submit">Add Customer</button>
    
</form>

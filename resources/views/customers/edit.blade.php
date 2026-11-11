<form action="{{ route('customers.update', $customer->id) }}" method="POST">

    @csrf
    @method('PUT')

    <fieldset>
        <legend>Edit Customer</legend>

        <label>Name</label><br>
        <input type="text" name="name"
            value="{{ old('name', $customer->name) }}">
              @error('name')
                     <p>{{  $message }}</p>
              @enderror        
        <br><br>

        <label>Email</label><br>
        <input type="email" name="email"
            value="{{ old('email', $customer->email) }}">
               @error('email')
                     <p>{{  $message }}</p>
              @enderror
        <br><br>

        <label>Company</label><br>
        <input type="text" name="company"
            value="{{ old('company', $customer->company) }}">
               @error('company')
                     <p>{{  $message }}</p>
              @enderror
        <br><br>

        <button type="submit">Update Customer</button>

    </fieldset>

</form>
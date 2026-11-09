<form action="{{ route('customers.store') }}" method="PUT">

    @csrf

    <fieldset>
        <legend>Edit Customer</legend>

        <label for="name">Name</label>
        <br>
        <input type="text" id="name" name="name"
               value="{{ $customer->name }}" size="100">

        <br><br>

        <label for="email">Email</label>
        <br>
        <input type="email" id="email" name="email"
               value="{{ $customer->email }}" size="100">

        <br><br>

        <label for="company">Company</label>
        <br>
        <input type="text" id="company" name="company"
               value="{{ $customer->company }}" size="100">

        <br><br>

        <button type="submit">Update Customer</button>

    </fieldset>

</form>
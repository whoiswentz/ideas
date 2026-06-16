<x-layout>
    <x-form title="Log in" description="Glad to have you back.">
        <form action="/login" method="POST" class="mt-10 space-y-4">
            @csrf

            <x-form.field name="name" label="Name" />
            <x-form.field type="email" name="email" label="Email" />

            <button type="submit" class="btn mt-2 h-10 w-full">Login</button>
        </form>
    </x-form>
</x-layout>

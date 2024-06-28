<x-layout>
    <x-page-heading>Register</x-page-heading>

    <x-forms.form method="POST" action="/register" enctype="multipart/form-data">
        <x-forms.input label="Name" name="name" value="{{$user->name}}" />
        <x-forms.input label="Email" name="email" type="email" value="{{$user->email}}" />
        <x-forms.divider />

        <x-forms.input label="Employer Name" name="employer" value="{{$user->employer->name}}" />
        <x-forms.input label="Employer Logo" name="logo" type="file" value="{{$user->logo}}" />

        <x-forms.button>Edit Profile</x-forms.button>
    </x-forms.form>
</x-layout>
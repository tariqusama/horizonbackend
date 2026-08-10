<x-mail::message>
# Welcome to Horizon!

Hello {{ $user->name }},

Thank you for registering. We are excited to help you achieve your goals.

<x-mail::button :url="config('app.url')">
Visit Dashboard
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>

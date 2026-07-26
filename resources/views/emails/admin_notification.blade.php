<x-mail::message>
# {{ $notificationTitle }}

{{ $notificationMessage }}

<x-mail::button :url="config('app.url') . '/admin'">
View Admin Dashboard
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>

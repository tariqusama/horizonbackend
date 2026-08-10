<x-mail::message>
# You have a new message

**From:** {{ $messageDetails['sender_name'] ?? 'System' }}

"{{ $messageDetails['message'] ?? 'You have received a new message.' }}"

<x-mail::button :url="config('app.url')">
View Message
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>

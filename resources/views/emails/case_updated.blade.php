<x-mail::message>
# Case Update

There has been an update to your case: **{{ $application->title ?? 'Application' }}**.

@if(!empty($changes))
**Updates:**
@foreach($changes as $key => $value)
- **{{ ucfirst($key) }}:** {{ is_array($value) ? 'Updated' : $value }}
@endforeach
@endif

<x-mail::button :url="config('app.url')">
View Case
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>

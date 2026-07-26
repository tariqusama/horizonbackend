<x-mail::message>
# Payment Received

Your payment of ${{ number_format($amount, 2) }} has been received successfully for application: {{ $application->title ?? 'Application' }}.

<x-mail::button :url="config('app.url')">
View Details
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>

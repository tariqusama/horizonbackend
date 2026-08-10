@component('mail::message')
# Beneficiary Intake Invitation

Hello,

You have been invited to complete the beneficiary portion of an immigration intake for the case titled **{{ $application->title ?? 'Application' }}**.

@if(!empty($messageText))
{{ $messageText }}

@endif

@component('mail::button', ['url' => $inviteUrl])
Complete Beneficiary Intake
@endcomponent

If the button does not work, copy and paste the following link into your browser:

{{ $inviteUrl }}

Thanks,

{{ $manager->name ?? config('app.name') }}
@endcomponent

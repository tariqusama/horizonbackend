<?php
use Illuminate\Support\Facades\Mail;

try {
    Mail::raw('This is a test email to verify your SMTP configuration from your Laravel application.', function ($message) {
        $message->to('shehryarshafique04@gmail.com')
                ->subject('Test Email from Laravel');
    });
    echo "Test email sent successfully!\n";
} catch (\Exception $e) {
    echo "Failed to send email. Error: " . $e->getMessage() . "\n";
}

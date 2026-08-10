<?php
$email = 'shehryarshafique655@gmail.com';

echo "Checking email: $email\n\n";

$invites = \App\Models\ApplicationInvite::with('application')->where('email', $email)->get();
$jsonInvites = \App\Models\Application::where('form_data', 'LIKE', '%'.$email.'%')->get();
$user = \App\Models\User::where('email', $email)->first();

echo "--- DIRECT APPLICATION INVITES (ApplicationInvite table) ---\n";
if ($invites->count() > 0) {
    foreach($invites as $i) {
        $appTitle = $i->application ? $i->application->title : 'Unknown Application';
        $appId = $i->application ? $i->application->id : 'Unknown';
        echo "Found Invite: App ID: $appId | Title: $appTitle | Role: {$i->role} | Status: {$i->status}\n";
    }
} else {
    echo "No direct invites found in the ApplicationInvite table.\n";
}
echo "\n";

echo "--- JSON/BENEFICIARY INVITES (Application form_data) ---\n";
if ($jsonInvites->count() > 0) {
    foreach($jsonInvites as $a) {
        $inviteData = $a->form_data['beneficiary_invite'] ?? null;
        if ($inviteData && isset($inviteData['email']) && strtolower($inviteData['email']) === strtolower($email)) {
             echo "Found Beneficiary Invite: App ID: {$a->id} | Title: {$a->title} | Status: " . ($inviteData['status'] ?? 'unknown') . "\n";
        } else {
             // Maybe it matched something else, ignore
        }
    }
} else {
    echo "No beneficiary invites found in application JSON form_data.\n";
}
echo "\n";

echo "--- PARTICIPANT RECORDS (If they already joined) ---\n";
if ($user) {
    $participantRecords = \App\Models\ApplicationParticipant::with('application')->where('user_id', $user->id)->get();
    if ($participantRecords->count() > 0) {
        foreach($participantRecords as $p) {
            $appTitle = $p->application ? $p->application->title : 'Unknown Application';
            $appId = $p->application ? $p->application->id : 'Unknown';
            echo "Participant in: App ID: $appId | Title: $appTitle | Role: {$p->role}\n";
        }
    } else {
        echo "User is registered but not an active participant in any applications (other than their own).\n";
    }
} else {
    echo "No user account exists yet for this email.\n";
}
echo "\n";


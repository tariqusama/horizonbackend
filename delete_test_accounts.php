<?php

$email = 'shehryarshafique655@gmail.com';

echo "Deleting test data for $email...\n";

// Delete participant records
$participantsDeleted = \App\Models\ApplicationParticipant::whereHas('user', function($q) use ($email) {
    $q->where('email', $email);
})->delete();
echo "Deleted $participantsDeleted ApplicationParticipant records.\n";

// Delete application invites
$invitesDeleted = \App\Models\ApplicationInvite::where('email', $email)->delete();
echo "Deleted $invitesDeleted ApplicationInvite records.\n";

// Delete user account
$usersDeleted = \App\Models\User::where('email', $email)->delete();
echo "Deleted $usersDeleted User account(s).\n";

echo "Done! You can now test from scratch.\n";

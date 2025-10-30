<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use App\Models\Quotation;
use App\Notifications\QuotationUpdatedNotification;

echo "Testing notification manually...\n";

$user = User::find(6);
$quotation = Quotation::find(3);

if ($user && $quotation) {
    echo "User: {$user->name} (ID: {$user->id})\n";
    echo "Quotation: {$quotation->nama_customer} (ID: {$quotation->id})\n";

    $user->notify(new QuotationUpdatedNotification($quotation, ['status'], 'sap'));
    echo "Notification sent successfully!\n";

    // Check notifications after sending
    echo "Notifications count after: " . $user->notifications()->count() . "\n";
} else {
    echo "User or quotation not found\n";
    if (!$user) echo "User 6 not found\n";
    if (!$quotation) echo "Quotation 3 not found\n";
}

<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\QuotationRevision;

echo "=== DEBUG NOTIFICATIONS ===\n\n";

echo "User ID 6 exists: " . (User::find(6) ? 'Yes' : 'No') . "\n";
$user = User::find(6);
if ($user) {
    echo "User role: " . $user->getRoleNames()->implode(', ') . "\n";
    echo "User status: " . $user->status . "\n";
    echo "Notifications count: " . $user->notifications()->count() . "\n";
}

echo "\nAll inputer_sap users:\n";
$inputerSapUsers = User::role('inputer_sap')->where('status', 'approved')->get();
foreach ($inputerSapUsers as $u) {
    echo "- ID: {$u->id}, Name: {$u->name}, Status: {$u->status}\n";
}

echo "\nNotifications in DB: " . DB::table('notifications')->count() . "\n";

echo "\nNotification details:\n";
$notifications = DB::table('notifications')->get();
foreach ($notifications as $notif) {
    echo "- ID: {$notif->id}, Type: {$notif->type}, Notifiable: {$notif->notifiable_id} ({$notif->notifiable_type}), Read: " . ($notif->read_at ? 'Yes' : 'No') . "\n";
    $data = json_decode($notif->data, true);
    echo "  Data: " . json_encode($data, JSON_PRETTY_PRINT) . "\n";
}

echo "\nRecent quotations updated (last 7 days): " . \App\Models\Quotation::where('updated_at', '>', now()->subDays(7))->count() . "\n";

echo "\nQuotation revisions count: " . QuotationRevision::count() . "\n";

echo "\nRecent revisions:\n";
$revisions = QuotationRevision::with('quotation', 'user')->latest()->take(5)->get();
foreach ($revisions as $r) {
    echo "- ID: {$r->id}, Quotation: {$r->quotation_id}, User: {$r->user_id} ({$r->user->name}), Action: {$r->action}, Date: {$r->created_at}\n";
    if ($r->quotation) {
        echo "  Quotation Customer: {$r->quotation->nama_customer}\n";
    }
}

echo "\nChecking if queue is configured...\n";
try {
    $queueConnection = config('queue.default');
    echo "Queue connection: {$queueConnection}\n";
} catch (Exception $e) {
    echo "Error checking queue: " . $e->getMessage() . "\n";
}

echo "\n=== END DEBUG ===\n";

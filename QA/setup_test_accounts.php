<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

$admin = User::firstOrNew(['email' => 'admin@sppc.com']);
$admin->name = 'Admin SPPC';
$admin->password = Hash::make('password123');
$admin->role = 'admin';
$admin->save();

$catering = User::firstOrNew(['email' => 'catering@sppc.com']);
$catering->name = 'Petugas Catering';
$catering->password = Hash::make('password123');
$catering->role = 'catering';
$catering->save();

$pengasuh = User::firstOrNew(['email' => 'pengasuh@sppc.com']);
$pengasuh->name = 'Pengasuh Pesantren';
$pengasuh->password = Hash::make('password123');
$pengasuh->role = 'pengasuh';
$pengasuh->save();

echo "TEST_ACCOUNTS_READY\n";
foreach (User::all() as $u) {
    echo "User: {$u->name} ({$u->email}) - Role: {$u->role}\n";
}

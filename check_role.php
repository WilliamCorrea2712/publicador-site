<?php
require __DIR__.'/vendor/autoload.php';
$app = require __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
$user = App\Models\User::where('email', 'william.correa.dev@gmail.com')->first();
if (! $user) {
    echo "not found\n";
    exit(0);
}
echo $user->role . "\n";

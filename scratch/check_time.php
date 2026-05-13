<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Timezone: " . config('app.timezone') . "\n";
echo "Current Time: " . now()->format('d/m/Y H:i:s') . "\n";
echo "Locale: " . config('app.locale') . "\n";

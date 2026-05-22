<?php

use Illuminate\Support\Facades\Artisan;

Artisan::command('jastipku:check', function () {
    $this->info('JastipKu console route aktif.');
});
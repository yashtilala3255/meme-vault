<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');


Schedule::command('memes:fetch-all --limit=50 --source=reddit --indian')->weekly()->mondays();
Schedule::command('memes:fetch-all --limit=100 --source=all')->weekly()->thursdays();
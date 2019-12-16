<?php

use Illuminate\Foundation\Inspiring;

/*
|
| Define all of your Closure based console commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspirequote', function () {
    $this->comment(Inspiring::quote());
})->describe('test quote');
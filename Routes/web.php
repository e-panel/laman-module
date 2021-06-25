<?php

Route::prefix('epanel/content')->as('epanel.')->middleware(['auth', 'check.permission:Laman'])->group(function() 
{
    Route::resources([
        'laman' => 'LamanController'
    ]);
});
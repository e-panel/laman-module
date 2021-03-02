<?php

/** 
 * @author Noviyanto Rahmadi <novay@btekno.id>
 * @copyright 2020 - Borneo Teknomedia
 */

Route::namespace('Epanel')->prefix('epanel')->as('epanel.')->middleware(['auth', 'check.permission:Laman'])->group(function() 
{
	Route::resources([
		'laman' => 'LamanController',
	]);
});

Route::namespace('Front')->prefix('page')->as('front.laman.')->group(function() 
{
	Route::get('{slug?}', 'IndexController@index')->name('index');
});
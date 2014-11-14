<?php

return [

	/*
	|--------------------------------------------------------------------------
	| Scan List
	|--------------------------------------------------------------------------
	|
	| Here, you'll want to list any controller that you are using breadcrumb
	| annotations in. The Scanner will parse each files and save them to
	| '/storage/framework/breadcrumbs.scanned.php'. It'll then be ran.
	|
	*/

	'scan' => [

		'App\Http\Controllers\AuthController',
		'App\Http\Controllers\HomeController',
		'App\Http\Controllers\PasswordController',

	],

	/*
	|--------------------------------------------------------------------------
	| Default Page Title
	|--------------------------------------------------------------------------
	|
	| To make setting the title of your pages simple, we've added a basic
	| getter/setter for a property that's accessible through the facade
	| and is easy to use. Here's the default title if one isn't given.
	|
	*/

	'page_title' => 'Default Page Title'

];

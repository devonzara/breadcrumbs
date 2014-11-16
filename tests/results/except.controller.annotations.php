<?php

$breadcrumbs->add('home', 'home', 'App\Http\Controllers\ExceptController@index', '');

$breadcrumbs->add('users', 'Users', 'App\Http\Controllers\ExceptController@users', '');

$breadcrumbs->add('filtered.users', 'Search Results', 'App\Http\Controllers\ExceptController@filteredUsers', 'users');

$breadcrumbs->add('profile', '{username}\'s Profile', 'App\Http\Controllers\ExceptController@profile', 'users');

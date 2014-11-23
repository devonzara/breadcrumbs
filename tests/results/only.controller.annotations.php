<?php

$breadcrumbs->add('home', 'home', '\App\Http\Controllers\OnlyController@index', '');

$breadcrumbs->add('users', 'Users', '\App\Http\Controllers\OnlyController@users', 'home');

$breadcrumbs->add('filtered.users', 'Search Results', '\App\Http\Controllers\OnlyController@filteredUsers', 'home');

$breadcrumbs->add('profile', '{username}\'s Profile', '\App\Http\Controllers\OnlyController@profile', 'users');

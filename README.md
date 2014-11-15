## Breadcrumbs

> **Note:** This project is currently unstable and under heavy development.

Breadcrumbs is a [Laravel 5](http://laravel.com) package to simplify the generation of breadcrumbs using annotations. Additionally, breadcrumbs may be built using class methods within' your code if preferred.

## Installation

Because this package is still in development, it has not yet been submit to packagist. To use the package, add the GitHub repository to composer.json.

```JSON
    "require": {
        "devonzara/breadcrumbs": "~1.0"
    },
    "repositories": [
        {
            "url": "https://github.com/devonzara/breadcrumbs.git",
            "type": "git"
        }
    ],
```

### Set Which Classes Are to Be Scanned

Now, like the `routes` and `events`, we need to define which classes should be scanned. There are two ways to do this.

#### Publish the Package Configurations

The Breadcrumbs configuration has an array where you can define these classes. To publish the config files, run:

> Note: The published files will be located in `config/packages/devonzara/breadcrumbs`.

```
php artisan publish:config devonzara/breadcrumbs
```

Simply update the `scan` array with the [qualified name](http://php.net/manual/en/language.namespaces.rules.php) of the controllers you wish to scan.

#### Extend the Service Provider

Alternatively, and similar to how `app/Providers/RouteServiceProvider` is set up, you can create a new `BreadcrumbsServiceProvider`, have it `use \Devonzara\Breadcrumbs\BreadcrumbsServiceProvider as ServiceProvider;`, and then `extend ServiceProvider`.

Once that is done, you simply have to override the `$scan` array like so:

```php
<?php namespace App\Providers;

use \Devonzara\Breadcrumbs\BreadcrumbsServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider {

	/**
	 * Determines if we will auto-scan in the local environment.
	 *
	 * @var bool
	 */
	protected $scanWhenLocal = true;

	/**
	 * The controllers to scan for breadcrumb annotations.
	 *
	 * @var array
	 */
	protected $scan = [
		'App\Http\Controllers\HomeController',
		'App\Namespace\Of\YourController',
	];

}

```

## Usage

### Annotations

Annotations are built to be flexible but simple.

#### Usage

To get started, we'll define a simple 'home' breadcrumb. The following will set the `key` or ***unique*** identifier that can be referenced by other annotations.

> Note: When only a key is set, it will also be used as the display name for that crumb.

```
/**
 * @Crumb("home")
 */
```

This package doesn't have a method to render the breadcrumbs; we're leaving that up to you. However, you can use `Breadcrumbs::getBreadcrumbs()` to fetch the `trail` or an array of the breadcrumbs for the current route. If used within' on the `home` page, you'll get the following output.

```
Array
(
    [home] => Array
        (
            [key] => home
            [name] => home
            [url] => http://localhost
        )

)
```

##### Custom Names

However, the `key` would not make very good breadcrumbs, so let's say you wanted define your own name to be displayed. In that case, you could use:

```
/**
 * @Crumb({"home", "Acme Homepage"})
 */
```

##### Ancestors (Parents)

Moving forward, we'll obviously want to define other breadcrumbs. For the purpose of this demonstration, we'll use a basic users system.

For instance, if you have a page that lists all users; you'll want to create a `users` breadcrumb and reference it back to `home`... We can accomplish that by using the `ancestor` attribute like so:

```
/**
 * @Crumb({"users", "Users"}, ancestor="home")
 */
```

`Breadcrumbs::getBreadcrumbs()` would now output:

```
Array
(
    [home] => Array
        (
            [key] => home
            [name] => home
            [url] => http://localhost
        )

    [users] => Array
        (
            [key] => users
            [name] => Users
            [url] => http://localhost/users
        )

)
```

##### Setting Dynamic Route/Title Parameters

This wouldn't be much use if we were restricted to static data. Therefore, we've added the ability to handle parameters. Continuing on our existing use-case, let's say we want to build individual user profiles. Let's take a look at the following example:

```
@Get("/users/{id}")
@Crumb({"profile", "{username}'s Profile"}, ancestor="users")
```

> Note: The route annotation was included to help illustrate the data we'll be passing in later.

Here, we've basically set a template for our breadcrumb's name. The `{username}` part will be automatically replaced later. To make use of this, we need to pass the data to the `getBreadcrumbs()` method.

```php
Breadcrumbs::getBreadcrumbs([$user->username, $user->id]);
```

Provided that the user's username is `Admin` and they have an `id` of 1, the output of the `getBreadcrumbs()` method in this case would be:

```
Array
(
    [home] => Array
        (
            [key] => home
            [name] => home
            [url] => http://localhost
        )

    [users] => Array
        (
            [key] => users
            [name] => Users
            [url] => http://localhost/users
        )

    [profile] => Array
        (
            [key] => profile
            [name] => Admin's Profile
            [url] => http://localhost/users/1
        )

)
```

#### Class-level Ancestors

Class-level ancestors allow you to set the ancestors for every breadcrumb within' the controller.

Let's assume you have a UsersController where each method should have a `users` breadcrumb as its ancestor. We can easily define this at the top of our controller, just above the `class` but below the `use` statements.

```
/**
 * @Ancestor("users")
 */
class UsersController {

   /**
    * @Get("/users")
    * @Crumb({"users", "Users"})
    */
    public function index()
    {
        //
    }

   /**
    * @Get("/users/{id}")
    * @Crumb({"profile", "{username}'s Profile"})
    */
    public function show()
    {
        //
    }

}

```

The `users` breadcrumb will be smart enough not to apply an ancestor to itself; however, the `profile` breadcrumb will have `users` as its ancestor.

Much like Middleware in Laravel 5, or Filters in Laravel 4, you can also apply `, only={"methodName"}` or `, except={"methodName"} to the `@Ancestor` annotation to exclude certain methods from inheriting a class-level ancestor.

> Note: Method-level ancestors will override class-level ancestors.

### Method Calls

> NOTE: SECTION INCOMPLETE

Breadcrumbs can be added through the `Breadcrumbs` facade. For example:

    // Breadcrumbs::push($key, $name, $url, array $customData)
    Breadcrumbs::push('home', 'Acme', route('home'));

## @todo

Tests, tests, and more tests!

## License

Breadcrumbs is an open-source package shared under the [MIT license](http://opensource.org/licenses/MIT).

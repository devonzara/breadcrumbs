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

## Usage

### Annotations

> NOTE: SECTION INCOMPLETE

Annotations are built to be flexible but simple.

#### Basic Usage

To get started, we can define a simple 'home' breadcrumb. The following will set the `key` or unique identifier that can be referenced by other annotations.

```PHP
/**
 * @Crumb("home")
 */
```

> Note: When only a key is set, it will also be used as the display name for that crumb.

This package doesn't currently have a method to render the breadcrumbs, we'll leave that up to you. However, you can use `Breadcrumbs::getBreadcrumbs()` to fetch an array of crumbs for the current route. If used on the `home` view with the above `@Crum` define, you'll get the following output.

```PHP
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

However, let's say you wanted to use a name that other than they key. In that case, you could use:

```PHP
/**
 * @Crumb({"home", "Acme Homepage"})
 */
```

Moving forward, we'll obviously want to define other breadcrums. We'll use the example of a basic users system. For instance, if you have a listing of all users; you'll want to create a `users` breadcrumb for it and reference it back to `home`... We accomplish that by using the `ancestor` attribute like so:

```PHP
/**
 * @Crumb({"users", "Users"}, ancestor="home")
 */
```

### Method Calls

> NOTE: SECTION INCOMPLETE

Breadcrumbs can be added through the `Breadcrumbs` facade. For example:

    // Breadcrumbs::push($key, $name, $url, array $customData)
    Breadcrumbs::push('home', 'Acme', route('home'));

## @todo

Tests, tests, and more tests!

## License

Breadcrums is an open-source package shared under the [MIT license](http://opensource.org/licenses/MIT).

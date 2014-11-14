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

However, the `key` would not make very good breadcrumsb, so let's say you wanted define your own name to be displayed. In that case, you could use:

```
/**
 * @Crumb({"home", "Acme Homepage"})
 */
```

##### Ancestors (Parents)

Moving forward, we'll obviously want to define other breadcrums. For the purpose of this demonstration, we'll use a basic users system. 

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

### Method Calls

> NOTE: SECTION INCOMPLETE

Breadcrumbs can be added through the `Breadcrumbs` facade. For example:

    // Breadcrumbs::push($key, $name, $url, array $customData)
    Breadcrumbs::push('home', 'Acme', route('home'));

## @todo

Tests, tests, and more tests!

## License

Breadcrums is an open-source package shared under the [MIT license](http://opensource.org/licenses/MIT).

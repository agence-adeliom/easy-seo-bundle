
![Adeliom](https://adeliom.com/public/uploads/2017/09/Adeliom_logo.png)
[![Quality gate](https://sonarcloud.io/api/project_badges/quality_gate?project=agence-adeliom_easy-common-bundle)](https://sonarcloud.io/dashboard?id=agence-adeliom_easy-common-bundle)

# Easy Common Bundle

Provide base utilities for Easyadmin.


## Features

- A Enum polyfill for PHP 8 (< 8.1.0)
- Some Traits for entities

## Versions

| Repository Branch | Version | Symfony Compatibility | PHP Compatibility | Status                     |
|-------------------|---------|-----------------------|-------------------|----------------------------|
| `3.x`             | `3.x`   | `6.4`, and `7.x`      | `8.2` or higher   | New features and bug fixes |
| `2.x`             | `2.x`   | `5.4`, and `6.x`      | `8.0.2` or higher | No longer maintained       |
| `1.x`             | `1.x`   | `4.4`, and `5.x`      | `7.2.5` or higher | No longer maintained       |

## Installation

Install with composer

```bash
composer require agence-adeliom/easy-common-bundle
```

### Traits

* [ID](src/Traits/EntityIdTrait.php)
* [Name and Slug](src/Traits/EntityNameSlugTrait.php)
* [Name](src/Traits/EntityNameTrait.php)
* [Publishable](src/Traits/EntityPublishableTrait.php)
* [Status](src/Traits/EntityStatusTrait.php)
* [Three State Status](src/Traits/EntityThreeStateStatusTrait.php)
* [Timestampable](src/Traits/EntityTimestampableTrait.php)
* [Soft Deletable](src/Traits/EntitySoftDeletableTrait.php)

### Enum helper

#### Declaration

```php
use Adeliom\EasyCommonBundle\Helper\Enum;

/**
 * Action enum
 */
final class Action extends Enum
{
    private const VIEW = 'view';
    private const EDIT = 'edit';
}
```

#### Usage

```php
$action = Action::VIEW();

// or with a dynamic key:
$action = Action::$key();
// or with a dynamic value:
$action = Action::from($value);
// or
$action = new Action($value);
```

As you can see, static methods are automatically implemented to provide quick access to an enum value.

One advantage over using class constants is to be able to use an enum as a parameter type:

```php
function setAction(Action $action) {
    // ...
}
```

#### Documentation

- `__construct()` The constructor checks that the value exist in the enum
- `__toString()` You can `echo $myValue`, it will display the enum value (value of the constant)
- `getValue()` Returns the current value of the enum
- `getKey()` Returns the key of the current value on Enum
- `equals()` Tests whether enum instances are equal (returns `true` if enum values are equal, `false` otherwise)

Static methods:

- `from()` Creates an Enum instance, checking that the value exist in the enum
- `toArray()` method Returns all possible values as an array (constant name in key, constant value in value)
- `keys()` Returns the names (keys) of all constants in the Enum class
- `values()` Returns instances of the Enum class of all Enum constants (constant name in key, Enum instance in value)
- `isValid()` Check if tested value is valid on enum set
- `isValidKey()` Check if tested key is valid on enum set
- `assertValidValue()` Assert the value is valid on enum set, throwing exception otherwise
- `search()` Return key for searched value

##### Static methods

```php
final class Action extends Enum
{
    private const VIEW = 'view';
    private const EDIT = 'edit';
}

// Static method:
$action = Action::VIEW();
$action = Action::EDIT();
```

Static method helpers are implemented using [`__callStatic()`](http://www.php.net/manual/en/language.oop5.overloading.php#object.callstatic).

If you care about IDE autocompletion, you can either implement the static methods yourself:

```php
final class Action extends Enum
{
    private const VIEW = 'view';

    /**
     * @return Action
     */
    public static function VIEW() {
        return new Action(self::VIEW);
    }
}
```

or you can use phpdoc (this is supported in PhpStorm for example):

```php
/**
 * @method static Action VIEW()
 * @method static Action EDIT()
 */
final class Action extends Enum
{
    private const VIEW = 'view';
    private const EDIT = 'edit';
}
```


## License

[MIT](https://choosealicense.com/licenses/mit/)


## Authors

- [@arnaud-ritti](https://github.com/arnaud-ritti)

  

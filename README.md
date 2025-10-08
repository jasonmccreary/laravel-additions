<p align="right">
    <a href="https://github.com/jasonmccreary/laravel-additions/actions"><img src="https://github.com/jasonmccreary/laravel-additions/workflows/Build/badge.svg" alt="Build Status"></a>
    <a href="https://packagist.org/packages/jasonmccreary/laravel-additions"><img src="https://poser.pugx.org/jasonmccreary/laravel-additions/v/stable.svg" alt="Latest Stable Version"></a>
    <a href="https://github.com/badges/poser/blob/master/LICENSE"><img src="https://poser.pugx.org/jasonmccreary/laravel-additions/license.svg" alt="License"></a>
</p>

# Additions for Laravel
This package contains "additions" to Laravel I have used within my Laravel applications over the years. All aim to improve the developer experience and code readability. Many of these additions have been attempted in the Laravel framework, but not yet merged.


## Requirements
A Laravel application running Laravel 12 or higher. _Not running a supported version of Laravel?_ [Upgrade with Shift](https://laravelshift.com).


## Installation
You can install this package by running the following command:

```sh
composer require -W jasonmccreary/laravel-additions
```


## Documentation
A brief description and code sample is provided for each available addition. For a full backstory, you may review their original PR.

---

### `status` helper for responses
The `status` helper (attempted in [#53691](https://github.com/laravel/framework/pull/53691)) is a simple helper to send raw HTTP status code responses. Much like the native `to_route` helper, its aim is to provide a more expressive way to send status codes.

```php
// may pass integer HTTP status code directly
return status(404);

// or chain camelCase HTTP status name
return status()->notFound();
```

**Note:** The `status` helper does not allow redirect status codes (3xx). You should use the native `redirect` helper for redirect responses.

---

### Dynamic `findBy*` for models
This package includes a `FindBy` trait which may be added to your Eloquent models to allow calling dynamic `findBy*` methods for the underlying column names. It is inspired by the [dynamic finders in Rails](https://guides.rubyonrails.org/active_record_querying.html#dynamic-finders), and behaves like the native `find` method with its parameters and return values.

```php
// find a single Post model by `title`
Post::findByTitle('Laravel Forever');

// find a set of Post models by `author_id`
Post::findByAuthorId([1, 3, 5]);

// find a single Post model by `author_id` and select only the `title`
Post::findByAuthorId(5, ['title']);
```

To use these dynamic `findBy*` methods, simply add the `FindBy` trait to your model class.

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use JMac\Additions\Traits\FindBy;

class Post extends Model
{
    use FindBy;
    
    // ...
}
```

---

### `SafeSave` for models
This package includes a `SafeSave` trait (attempted in [#50190](https://github.com/laravel/framework/pull/50190)) which may be added to your Eloquent models to save data directly from "safe" input without doing the _fillable/guarded dance_. It allows you to pass the Eloquent `create` or `update` methods `ValidatedInput` without triggering a `MassAssignment` exception, regardless of the values set in your model `$fillable` or `$guarded` properties.

`ValidatedInput` objects are readily available from calling the `safe` method on a `FormRequest`. This data has been validated, and, as such, ready to save to the database.

```php
public function store(UserCreateRequest $request)
{
     $user = User::create($request->safe());

     return redirect('user.show', $user);
}
```

While this is the intended use case, you may, of course, create your own `ValidatedInput` object directly. To use this behavior, simply add the `SafeSave` trait to your model class.

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use JMac\Additions\Traits\SafeSave;

class Post extends Model
{
    use SafeSave;
    
    // ...
}
```

---

### `WithFallback` for policies
This introduces a `WithFallback` trait (attempted in [#54495](https://github.com/laravel/framework/pull/50190)) which you may add to your model policies to streamline the repeated logic often found within these classes.

When using `WithFallback` you may add a `fallback` method to your policy class. This method will be called when the policy method is not found. The `fallback` method will receive the following arguments:

- The _kebab case_ name of the ability
- The authenticated user instance
- The model instance, if the policy is for a model
- An array of any additional arguments passed

Like any other policy method, `fallback` should return `true` if authorized, `false` if not authorized, or `null` to defer authorization.

**Note:** Due to the dynamic nature of this method, it does not support [guest users](https://laravel.com/docs/12.x/authorization#guest-users). If you require guest user support, you may implement the specific policy method.

To use this behavior, simply add the `WithFallback` trait to your policy class.

```php
<?php

namespace App\Policies;

use Illuminate\Database\Eloquent\Model;
use JMac\Additions\Traits\WithFallback;

class PostPolicy
{
    use WithFallback;
    
    public function fallback(string $ability, User $user, ?Post $post, array $arguments): ?bool
    {
          return null;
    }
}
```

---

### More `make:migration` guessing
This improves the _guessing_ when running `make:migration` (attempted in [#20760](https://github.com/laravel/framework/pull/20760)) to generate a more complete migration based on the provided name.

The following naming conventions may be used to make a fuller migration.

- **Drop a table**: `drop_users_table`, `remove_users_table`
- **Rename a table**: `rename_users_to_accounts_table`, `rename_users_to_accounts_table`
- **Drop a column**: `drop_email_verified_at_from_users_table`, `remove_email_verified_at_from_users_table`
- **Rename a column**: `rename_stripe_id_to_transaction_id_in_orders_table`
- **Change a column**: `change_status_in_orders_table`, `alter_status_in_orders_table`
- **Add a column**: `add_status_to_orders_table`

Using any of these naming conventions will make a migration that is mostly complete. Only the `change_` and `add_` column conventions may require adjustments for the column data type.

For all conventions, the `_table` suffix is optional. For example, `drop_users` instead of `drop_users_table`. Also, delimiting with dashes (`-`) is supported. For example, `rename-stripe-id-to-transactions-id-in-orders-table`. **Note:** the dashes will be preserved for the database names.

```php
<?php

namespace App\Policies;

use Illuminate\Database\Eloquent\Model;
use JMac\Additions\Traits\WithFallback;

class PostPolicy
{
    use WithFallback;
    
    public function fallback(string $ability, User $user, ?Post $post, array $arguments): ?bool
    {
          return null;
    }
}
```


## Contributing
Contributions to this project are welcome. You may open a Pull Request against the `main` branch. Please ensure you write a clear description (ideally with code samples) and all workflows are passing.





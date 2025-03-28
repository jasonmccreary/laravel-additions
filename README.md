<p align="right">
    <a href="https://github.com/jasonmccreary/laravel-additions/actions"><img src="https://github.com/jasonmccreary/laravel-additions/workflows/Build/badge.svg" alt="Build Status"></a>
    <a href="https://packagist.org/packages/jasonmccreary/laravel-additions"><img src="https://poser.pugx.org/jasonmccreary/laravel-additions/v/stable.svg" alt="Latest Stable Version"></a>
    <a href="https://github.com/badges/poser/blob/master/LICENSE"><img src="https://poser.pugx.org/jasonmccreary/laravel-additions/license.svg" alt="License"></a>
</p>

# Additions for Laravel
This package contains Laravel "additions" I have used within my Laravel applications over the years.

While some additions are available, this package is still a WIP (work in progress).


## Requirements
A Laravel application running Laravel 11 or higher. _Not running a stable version of Laravel?_ [Upgrade with Shift](https://laravelshift.com). 


## Installation
You can install this package by running the following command:

```sh
composer require -W jasonmccreary/laravel-additions
```


## Documentation
A simple description and code sample is provided for each available addition. Many of these additions have been attempted in the Laravel framework. For a full backstory, you may review their original PR.

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


## TODO
- [x] ~~`status` helper~~
- [x] ~~`findBy*` for models~~
- [x] ~~"safe objects" for models~~
- [ ] `fallback` for policies



## Contributing
Contributions to this project are welcome. You may open a Pull Request against the `main` branch. Please ensure you write a clear description (ideally with code samples) and all workflows are passing.





# FuseMVC

## Introduction

FuseMVC is a lightweight and minimal MVC framework designed for web applications that do not require the overhead of larger frameworks like Laravel. It provides essential functionality for small to medium-sized applications while maintaining simplicity and efficiency.

This is an alpha release, with planned enhancements including a graphical user interface (GUI) for database management and model/controller creation. Despite being in its early stages, FuseMVC is stable and has already been deployed in production environments.

Contributions, feature requests, and code improvements are welcome. Feel free to reach out with suggestions.

---

## Routing

Routing in FuseMVC is managed through the `routes.php` file, supporting standard HTTP methods such as `GET`, `POST`, `PATCH`, and `DELETE`. Routes are defined using static functions within the `Route` class, specifying the path and corresponding method to be executed.

### Defining Routes

```php
$routes->get('/', 'Web::index');
$routes->post('/add_post', 'Posts::add');
$routes->patch('/update_post', 'Posts::update');
$routes->delete('/delete_post', 'Posts::delete');
```

### Method Spoofing

As HTML forms support only `GET` and `POST` methods, `PATCH` and `DELETE` requests require method spoofing using a hidden input field:

```html
<input type="hidden" name="method" value="delete">
```

### Dynamic Routing

Dynamic routes allow for variable placeholders within paths, enclosed in square brackets. These values are passed to the controller via the request object.

```php
$routes->patch('/update_post/[post_id]', 'Posts::update');

// Controller function handling the request
static function update_post($request) {
   echo $request->post_id;
}
```

---

## Controllers

Controllers reside in the `/controllers` directory and are implemented as static classes containing methods to handle route requests.

### Request Object

When a controller method is invoked from a route, the request object is passed as the first parameter. This object contains all `GET`, `POST`, and dynamic route variables, accessible via their respective keys.

---

## Models

Models are located in the `/models` directory and extend the `Model` class. Each model defines the corresponding database table and its fields.

### Defining a Model

```php
class User extends Model {
    public $table = 'users';
    public $fields = ['id', 'username', 'password', 'role'];
}
```

### Model Methods

#### Retrieving All Records

Fetches all records from the database as an array of model objects.

```php
$user = new User();
$users = $user->getAll();

echo $users[0]->username;
```

#### Retrieving a Record by Primary Key

Loads a specific record using its primary key.

```php
$user = new User();
$user->getByPrimary(1);

echo $user->username;
```

#### Retrieving Records by Field

Fetches records where a field matches a given value.

```php
$user = new User();
$admins = $user->getWhereField('role', 'admin');

foreach ($admins as $admin) {
    echo $admin->username;
}
```

#### Retrieving Records with Custom SQL Conditions

Allows fetching records using custom SQL `WHERE` conditions.

```php
$user = new User();
$new_users = $user->getWhere("user_id > 2");

foreach ($new_users as $user) {
    echo $user->username;
}
```

#### Saving a Model

Creates a new record or updates an existing one, depending on whether the model instance contains data from the database.

```php
$user = new User();

$user->username = 'TommyTittlemouse';
$user->password = md5('password');
$user->role = 'admin';
$user->save();
```

---

## Views

FuseMVC utilizes the Blade templating engine for views. Views are rendered using the `view()` function, which accepts the view name and an optional data array.

### Rendering a View in a Controller

```php
static function list_posts() {
   $post = new Post();
   $posts = $post->getAll();

   return view('posts', ['posts' => $posts]);
}
```

### Blade Template Example

```blade
// /views/posts.blade.php
<div>
    @foreach($posts as $post)
    <div>
        <h1>{{ $post->title }}</h1>
    </div>
    @endforeach
</div>
```

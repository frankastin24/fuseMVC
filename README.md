# FuseMVC

## Introduction

FuseMVC is a lightweight PHP MVC (Model-View-Controller) framework designed for simplicity and efficiency. It provides a structured way to develop web applications by separating concerns into models, views, and controllers. With an intuitive routing system, database model handling, and Blade templating engine integration, FuseMVC helps developers build scalable applications quickly and efficiently.

This guide covers the key components of FuseMVC, including routing, controllers, models, and views, with practical examples to help you get started.

---

## Routing

Routes in FuseMVC are defined in the `routes.php` file. The framework supports HTTP methods such as `GET`, `POST`, `PATCH`, and `DELETE`. Routes are registered using static functions in the `Route` class, each taking two parameters: the route path and the method to be executed when the route is requested.

### Defining Routes

```php
$routes->get('/', 'Web::index');
$routes->post('/add_post', 'Posts::add');
$routes->patch('/update_post', 'Posts::update');
$routes->delete('/delete_post', 'Posts::delete');
```

### Method Spoofing

Since HTML forms only support `GET` and `POST` methods, `PATCH` and `DELETE` requests must use method spoofing. This requires including a hidden input field within the form:

```html
<input type="hidden" name="method" value="delete">
```

### Dynamic Routes

Dynamic routing allows for variable placeholders within route paths. These placeholders are enclosed in square brackets and are passed to the controller function via the request object.

```php
$routes->patch('/update_post/[post_id]', 'Posts::update');

// Controller function to handle the request
static function update_post($request) {
   echo $request->post_id;
}
```

---

## Controllers

Controllers are located in the `/controllers` directory. They are implemented as static classes containing methods that handle route requests.

---

## Models

Models are located in the `/models` directory and extend the `Model` class. Each model defines the database table it interacts with and its corresponding fields.

### Defining a Model

```php
class User extends Model {
    public $table = 'users';
    public $fields = ['id', 'username', 'password', 'role'];
}
```

### Model Methods

#### Retrieving All Records
Returns all records from the associated database table as an array of model objects.

```php
$user = new User();
$users = $user->getAll();

echo $users[0]->username;
```

#### Retrieving a Record by Primary Key
Loads a record using its primary key.

```php
$user = new User();
$user->getByPrimary(1);

echo $user->username;
```

#### Retrieving Records by Field
Fetches records where a specific field matches a given value.

```php
$user = new User();
$admins = $user->getWhereField('role', 'admin');

foreach ($admins as $admin) {
    echo $admin->username;
}
```

#### Retrieving Records with Custom SQL Conditions
Allows fetching records based on an SQL `WHERE` condition.

```php
$user = new User();
$new_users = $user->getWhere("user_id > 2");

foreach ($new_users as $user) {
    echo $user->username;
}
```

#### Saving a Model
If the model instance contains data from an existing database record, it updates the record. Otherwise, it creates a new record.

```php
$user = new User();

$user->username = 'TommyTittlemouse';
$user->password = md5('password');
$user->role = 'admin';
$user->save();
```

---

## Views

Views in FuseMVC use the Blade templating engine. A view is rendered using the `view()` function, which accepts the view name and an optional array of data to pass to the view.

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

---

## Conclusion

FuseMVC provides a streamlined approach to developing PHP applications with an MVC architecture. By leveraging its routing, controllers, models, and views, developers can create clean, maintainable, and scalable applications with minimal overhead. This guide covered the essential concepts to get started with FuseMVC, making it easier to build dynamic web applications efficiently.

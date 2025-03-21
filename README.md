# FuseMVC

## Introduction

FuseMVC is a lightweight and minimal MVC framework tailored for web applications that do not require the extensive overhead of larger frameworks like Laravel. It offers essential functionality for small to medium-sized applications while maintaining simplicity and efficiency.

Currently in its alpha release, FuseMVC has a stable core and has already been deployed in production environments. Future enhancements include a graphical user interface (GUI) for database management and streamlined model/controller creation.

We welcome contributions, feature requests, and code improvements. Feel free to reach out with suggestions.

---

## Routing

Routing in FuseMVC is handled via the `routes.php` file, supporting standard HTTP methods such as `GET`, `POST`, `PATCH`, and `DELETE`. Routes are defined using static functions within the `Route` class, linking specific paths to their corresponding methods.

### Defining Routes

```php
$routes->get('/', 'Web::index');
$routes->post('/add_post', 'Posts::add');
$routes->patch('/update_post', 'Posts::update');
$routes->delete('/delete_post', 'Posts::delete');
```

### Method Spoofing

Since HTML forms natively support only `GET` and `POST` methods, `PATCH` and `DELETE` requests require method spoofing via a hidden input field:

```html
<input type="hidden" name="method" value="delete">
```

### Dynamic Routing

Dynamic routes enable variable placeholders within paths, enclosed in square brackets. These values are automatically passed to the controller via the request object.

```php
$routes->patch('/update_post/[post_id]', 'Posts::update');

// Controller function handling the request
static function update_post($request) {
    echo $request->post_id;
}
```

---

## Controllers

Controllers are stored in the `/controllers` directory and implemented as static classes containing methods that process route requests.

### Request Object

When a route invokes a controller method, the request object is passed as the first parameter. This object contains all `GET`, `POST`, and dynamic route variables, accessible via their respective keys.

---

## Models

Models reside in the `/models` directory and extend the `Model` class. Each model defines its associated database table and fields.

### Defining a Model

```php
class User extends Model {
    public $table = 'users';
    public $fields = ['id', 'username', 'password', 'role'];
}
```

### Initializing a Model with a Primary Key

A model can be initialized with a primary key to load a specific record.

```php
$frank = new User(1);
echo $frank->username;
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

Fetches records where a specified field matches a given value.

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

#### One-to-One Relationships

##### The `hasOne` Method

The `hasOne` method assumes that the related model contains a column named after the original model, appended with `_id`.

Example: A `User` model has one related `Subscription`, where the `subscriptions` table has a `user_id` column.

```php
$user = new User(1);
$user->hasOne('Subscription');

echo $user->subscription->package_name;
```

##### The `isOne` Method

The `isOne` method works similarly to `hasOne`, except the foreign key is located in the original model.

Example: An `Invoice` model has one `Subscription`, with the foreign key stored in the `invoice` table as `subscription_id`.

```php
$invoice = new Invoice(1);
$invoice->isOne('Subscription');

echo $invoice->subscription->package_name;
```

#### One-to-Many Relationships

The `hasMany` method retrieves all related records as an array of model objects.

Example: A `Subscription` model has many `Invoices`, where each invoice contains a `subscription_id` column.

```php
$subscription = new Subscription(1);
$subscription->hasMany('Invoice');

foreach ($subscription->invoice as $invoice) {
    echo $invoice->amount;
}
```

#### Saving a Model

Creates a new record or updates an existing one, depending on whether the model instance already contains data from the database.

```php
$user = new User();

$user->username = 'TommyTittlemouse';
$user->password = md5('password');
$user->role = 'admin';
$user->save();
```

---

## Views

FuseMVC integrates the Blade templating engine for rendering views. Views are rendered using the `view()` function, which accepts the view name and an optional data array.

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
<div>
    @foreach($posts as $post)
    <div>
        <h1>{{ $post->title }}</h1>
    </div>
    @endforeach
</div>
```

# FuseMVC
## Lightweight PHP MVC Framework

## Routing

Routes are set in the 'routes.php' files.  Routes can be 'get','post','patch' or 'delete'.
To set a route you use the static functions in the Route class.  The function take two parameters first the route path and second the the method to be called when the path is requested.
The method is identifided by a string with the class name followed by two colons and the method name.

Example:

```php

$routes->get('/' , 'Web::index');

$routes->post('/add_post', 'Posts::add');
$routes->patch('/update_post', 'Posts:update');
$routes->delete('/delete_post','Posts::delete');

```

To use the patch and delete methods you must use method spoofing by sending a hidden field with your form with the 'name="method"' and the value as the method name 'value="delete"'.

### Dynamic Routes 

For dynamic routing you specify the dynamic part of the route by adding a variable name eclosed within square brackets.  The variable is passed to the controller function with the request object.

```php


$routes->patch('/update_post/[post_id]', 'Posts:update');

//Example controller function to capure the variable

stactic function update_post($request) {
   echo $request->post_id;
}

```

## Controllers

Controllers are files found within the '/controllers' folders.  They are static classes which contain functions which can be called by the routes.

## Models

Models are classes found in the '/models' folders.  They extend the Model class.  To create one you give the model a name and two public fields.  The first is the name of the database table you wish to access and the second is an array of the tables fields.
Models expect the first field in the array to be the primary key.

Example:

```php

class User extends Model {
    public $table = 'users';
    public fields = ['id', 'username','password','role'];
}

```

## Model methods

### Get all
This returns all of the rows found in the database as an array of models

Example:

```php

$user = new User();
$users = $user->getAll();

echo $users[0]->username;

```

### Get By Primary

Takes a primary key value in the method argument and loads the row into the model.


Example:

```php

$user = new User();
$user->getByPrimary(1);

echo $user->username;

```

### Get Where Field

Takes two values in the method arugment; first the field name and second the field value.  
Returns an all rows where the value is equal to the value provided.


Example:

```php

$user = new User();
$admins = $user->getWhereField('role','admin');

foreach($admins as $admin) {
  echo $user->username;
}

```

### Get Where 

Takes an SQL WHERE statement as the argument.  
Returns all found rows as an array of models.


Example:

```php

$user = new User();
$new_users = $user->getWhere("'user_id' > 2");

foreach($admins as $admin) {
  echo $new_users->username;
}

```

### Save 

If a database entry has been loaded it updates the database after changes made to the model.
If no database entry has been loaded it creates a new database entry with the information in the model.


Example:

```php

$user = new User();

$user->username = 'TommyTittlemouse';
$user->password = md5('password');
$user->role = 'admin';
$user->save();

```

## Views

Views are php files using the Blade templating engine.  To call a view from a controller you use the view function.
The view function take two parameters, the first is the name of the view and the second optional parameter is an array of varibles to be passed to the view.

Example:

```php

static function list_posts() {
   $post = new Post();
   $posts = $post->getAll();

   return view('posts', ['posts' => $posts]);
}

```

```blade

// /views/posts.blade.php

<div>
@foreach($posts as $post)
<div>
<h1>{{$post->title}}</h1>
</div>
@endforach
</div>

```








<?php
/* 
FuseMVC
Lightweight PHP MVC framework
Designed for http://fusionary.org
©2025 - Frank Astin
*/

/* Load enviromentals */

$dir = __DIR__;

$env_string = file_get_contents($dir . '/.env');

function clean_string($string) {
   return  str_replace("\n", '' ,str_replace('\n', '' ,str_replace(' ', '' , $string)));
}

preg_match('/Host:(?s)(.*)Database User/',$env_string,$database_host);
$database_host = clean_string($database_host[1]);

preg_match('/User:(?s)(.*)Database Password/',$env_string,$database_user);
$database_user = clean_string($database_user[1]);

preg_match('/Password:(?s)(.*)Database Name/',$env_string,$database_password);
$database_password = clean_string($database_password[1]);

preg_match('/Name:(?s)(.*)Display Admin/',$env_string,$database_name);
$database_name = clean_string($database_name[1]);

preg_match('/Display Admin:(?s)(.*)Admin URL/',$env_string,$display_admin);
$display_admin = clean_string(boolval($display_admin[1]));

$admin_url = explode('Admin URL:',$env_string);

$admin_url = $admin_url[1];

/* Setup DB */

include $dir . '/fuse/db.php';

global $db;

$db = new Database($database_host,$database_user,$database_password,$database_name);

/* Setup Model */

include $dir . '/fuse/model.php';

/* Load models */

$path = $dir . '/models/';

$models = array_diff(scandir($path), array('.', '..'));

foreach($models as $model) {
    include $path . $model;
}

/* Load controllers */

$path = $dir . '/controllers/';

$controllers = array_diff(scandir($path), array('.', '..'));

foreach($controllers as $controller) {
    include $path . $controller;
}

/* Routing */

include $dir . '/fuse/routing.php';

$route = new Route();

include $dir . '/routes.php';

$route->execute();
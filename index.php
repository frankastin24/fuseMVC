<?php
/* 
FuseMVC Version 0.1 
Lightweight PHP MVC framework
Designed for http://fusionary.org
©2025 - Frank Astin
*/
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
include __DIR__ . '/fuse/bladeone.php';
use eftec\bladeone\BladeOne;


session_start();

/* Load enviromentals */

$dir = __DIR__;

$env_string = file_get_contents($dir . '/.env');


$env_lines = explode(PHP_EOL, $env_string);

global $_ENV;

$_ENV = [];

foreach($env_lines as $line) {
    if($line == '') continue;

    $split_line = explode('=', $line);
   
    $_ENV[$split_line[0]] = $split_line[1];
    
} 


/* Setup DB */

include $dir . '/fuse/db.php';

global $db;

$db = new Database($_ENV['DatabaseHost'],$_ENV['DatabaseUser'],$_ENV['DatabasePassword'],$_ENV['DatabaseName']);

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

/* Setup Blade Rendering */

function view($view_name, $variables = []) {
    $views = __DIR__ . '/views';
    $cache = __DIR__ . '/cache';

    $blade = new BladeOne($views,$cache,BladeOne::MODE_DEBUG); // MODE_DEBUG allows to pinpoint troubles.

    echo $blade->run($view_name,$variables);
}

/* Routing */

include $dir . '/fuse/routing.php';

$route = new Route();

include $dir . '/routes.php';

$route->execute();
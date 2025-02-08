<?php
/* 
FuseMVC
Lightweight PHP MVC framework
Designed for http://fusionary.org
©2025 - Frank Astin
*/


/* Load enviromentals */

$directory = __DIR__;

$env_string = file_get_contents($directory . '/.env');

$env_lines = explode('\n' , $env_string);

foreach($env_lines as $line) {
    
    if(!str_contains($line,':')) continue;

    $line_array = explode(':', $line);
    $label = str_replace(' ' , '' , $line_array[0]);
    $value = str_replace(' ' , '' , $line_array[1]);

    switch ($label) {
        case 'Database Host' :

           $database_host = $value;
        
        break;
        case 'Database User' :

            $database_user = $value;
         
         break;
         case 'Database Password' :

            $database_password = $value;
         
         break;
         case 'Database Password' :

            $database_password = $value;
         
         break;
         case 'Database' :

            $database = $value;
         
         break;
         case 'Display Admin' :

            $display_admin = boolval($value);
         
         break;
         case 'Admin URL' :

            $admin_url = $value;
         
         break;
    }
}

/* Setup DB */

include $dir . '/fuse/db.php';

global $db;

$db = new Database($database_host,$database_user,$database_password,$database);

/* Setup Model */

include $dir . '/fuse/model.php';

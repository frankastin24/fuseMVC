<?php
class Route {
    private $routes;

    public function get($path, $callable) {

        if(!isset($this->routes)) {
            $this->routes = new stdClass();
        }

        $this->routes->{'GET'.str_replace('/','_', $path)} = $callable;  

    }

    public function post($path, $callable) {

        if(!isset($this->routes)) {
            $this->routes = new stdClass();
        }

        $this->routes->{'POST'.str_replace('/','_', $path)} = $callable;  

    }

    public function delete($path, $callable) {

        if(!isset($this->routes)) {
            $this->routes = new stdClass();
        }

        $this->routes->{'DELETE'.str_replace('/','_', $path)} = $callable;  

    }
    public function patch($path, $callable) {

        if(!isset($this->routes)) {
            $this->routes = new stdClass();
        }

        $this->routes->{'PATCH_'.$path} = $callable;  

    }

    public function execute() {
        $path = $_SERVER['REQUEST_URI'];

        $method = $_SERVER['REQUEST_METHOD'];

        if(isset($_POST['method']) && $_POST['method'] == 'DELETE') $method = 'DELETE';
        if(isset($_POST['method']) && $_POST['method'] == 'PATCH') $method = 'PATCH';


        if(str_contains($path,'[')) {
            preg_match('/\[(.*?)\]/',$path,$varibles);
        }

        $request = new stdClass();

        $callable = $this->routes->{$method .  str_replace('/','_',$path)};

        $callable = explode('::' , $callable);

        $class = new $callable[0];

        if(isset($varibles)) {
            $class->{$callable[1]}(...$varibles);
        } else {
            $class::{$callable[1]}();
        }
       
           
    
    
    }
}
<?php
class Route {
    private $routes;

    public function get($path, $callable) {

        if(!isset($routes)) {
            $this->routes = new stdClass();
        }

        $this->routes->{'GET'.str_replace('/','_', $path)} = $callable;  

    }

    public function post($path, $callable) {

        if(!isset($routes)) {
            $this->routes = new stdClass();
        }

        $this->routes->{'POST'.str_replace('/','_', $path)} = $callable;  

    }

    public function delete($path, $callable) {

        if(!isset($routes)) {
            $this->routes = new stdClass();
        }

        $this->routes->{'DELETE'.str_replace('/','_', $path)} = $callable;  

    }
    public function patch($path, $callable) {

        if(!isset($routes)) {
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

        $route = $this->routes->{$method . '_'. $path};

        if(is_callable($route)) {
            call_user_func($route, $varibles);
        } else {
            $callable = explode('->',$route);
            $class = new $callable[0];
            $class->{$callable[1]}(...$varibles);
        }
    
    }
}
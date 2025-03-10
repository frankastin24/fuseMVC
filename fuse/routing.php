<?php

class Route {
    private $routes;

    public function get($path, $callable) {

        if(!isset($this->routes)) {
            $this->routes = new stdClass();
        }

        $this->routes->{'GET'. $path} = $callable;  

    }

    public function post($path, $callable) {

        if(!isset($this->routes)) {
            $this->routes = new stdClass();
        }

        $this->routes->{'POST'. $path} = $callable;  

    }

    public function delete($path, $callable) {

        if(!isset($this->routes)) {
            $this->routes = new stdClass();
        }

        $this->routes->{'DELETE'. $path} = $callable;  

    }
    public function patch($path, $callable) {

        if(!isset($this->routes)) {
            $this->routes = new stdClass();
        }

        $this->routes->{'PATCH_'.$path} = $callable;  

    }

    public function execute() {
        $path = $_SERVER['REQUEST_URI'];
       
        if(substr($path, -1) == '/' && strlen($path) > 1) {
            $path = substr($path,0,-1);
        }


        $method = $_SERVER['REQUEST_METHOD'];

        if(isset($_POST['_method']) && $_POST['_method'] == 'DELETE') $method = 'DELETE';
        if(isset($_POST['_method']) && $_POST['_method'] == 'PATCH') $method = 'PATCH';

        
        $request = new stdClass();

        foreach($this->routes as $route_path => $route) {

            preg_match('/\[(.*?)\]/',$route_path,$matches);

            
            $route_path = str_replace($method,'',$route_path);
            $regex =  $route_path;
            
            if(count($matches)) {

                $dynamic_variable_keys = [];
                
                foreach($matches as $index => $match) {
                    if($index == 0) continue;
                    $regex = str_replace('/' , '\/' , $regex);
                    $regex = '/'. preg_replace('/\['.$match.'\]/','([^\/]++)$',$regex) . '/';

                  
                    $dynamic_variable_keys[] = $match;
                }
                 
                preg_match($regex,$path,$path_matches);
               

                if(count($path_matches)) {
                    
                    $path = $route_path;
                  

                    foreach($path_matches as $path_matches_index => $path_match) {
                        if($path_matches_index == 0 ) continue;
                        $request->{$dynamic_variable_keys[$path_matches_index - 1]} = $path_match;
                    }
                }
                

            }

        }

        foreach($_POST as $key => $value) {
            $request->{$key} = $value;
        }


        $callable = $this->routes->{$method . $path};

        $callable = explode('::' , $callable);

        $class = new $callable[0];

        if(isset($varibles)) {
            $class->{$callable[1]}(...$varibles);
        } else {
           $return = $class::{$callable[1]}($request);

           if(is_array($return)) {
            echo json_encode($return);
           }
        }
       
    
    }
}
<?php

include SRC_PHP . 'Request.php';
include SRC_PHP . 'Response.php';

class App {

    public static $get    = array();
    public static $post   = array();
    public static $put    = array();
    public static $delete = array();

    protected static function processURL($request_method, $request_method_args = null) {
        if($request_method_args) { # If method arguments are already set, don't get arguments from URL
            $request_method[$_GET['url']]['callback'](new Request($request_method_args), new Response);
            return;
        }

        else { # If arguments are not set, get them from URL
            $url_explode = explode("/", $_GET['url']); # Get url divided by slashes
        
            foreach($request_method as $route) {
                $path = $route['path']; # Route's path (string)
        
                if(sizeof(explode("/", $path)) == sizeof($url_explode)) { # Find a route that matches the length of URL
                    $args = array(); # Initialize array containing arguments for given page
                    
                    foreach(explode("/", $path) as $key => $field) {
                        if(strpos($field, "{") !== false && strpos($field, "}") !== false) { # If field contains { and }, it is an argument
                            array_push($args, [
                                "pos"  => $key, # Save argument's position
                                "name" => substr($field, 1, -1) # Save argument's name
                            ]);
                        }
                    }
        
                    $path_new = explode("/", $path); # Initialize new path, containing path exploded by slashes
                    $args_new = array(); # Initialize array, that is going to contain arguments with values under an index that is argument's name in request method's array
        
                    foreach($args as $arg) {
                        $path_new[$arg['pos']]  = $url_explode[$arg['pos']]; # Composing new path
                        $args_new[$arg['name']] = $url_explode[$arg['pos']]; # Adding arguments
                    }
        
                    $path_new = implode("/", $path_new); # Glue pieces together with slashes
                    
                    if($path_new == $_GET['url']) { # If new path is equal to current URL, execute callback
                        if(!isset($_SERVER['HTTP_X_REQUESTED_WITH']) || !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') # Check if request is not ajax
                            Logs::set(URL::getClientIp() . " – – [" . date("d/M/Y:H:i:s") . "] \"" . strtoupper($_SERVER['REQUEST_METHOD']) . " " . $_GET['app'] . "/$path_new\"", "access"); # Set log
                        
                        if(!is_string($route['callback'])) # If callback is not short method 
                            $route['callback'](new Request($args_new), new Response); # Execute callback with request and respone as params
                        else
                            self::short_method($route['callback'], $args_new); # Else call short method

                        return;
                    }
                }
            }
        }

        if(!empty(ERR_NOT_FOUND_REDIR))
            URL::redirect(ERR_NOT_FOUND_REDIR);

        die(ERR_NOT_FOUND);
    }

    protected static function short_method($callback, $params) {
        if(is_string($callback)) {
            $callback = explode("@", $callback);

            $path   = $callback[0];
            $method = $callback[1];
            
            preg_match_all('/\((.*?)\)/', $method, $args);
            
            if(!empty($args[1])) {
                $args = explode(",", $args[1][0]);
                
                foreach($args as $key => $arg) {
                    if(strpos($arg, ":") !== false) { # If argument is param variable
                        if(isset($params[explode(":", $arg)[1]])) # If param variable with arg's name exists
                            $args[$key] = $params[explode(":", $arg)[1]]; # Set arg to the value of the param
                    }
                }
            }

            $method = explode("(", $method)[0];

            Response::controller($path)->$method(...$args);
        }
    }

    public static function get(string $path, $callback) {
        self::$get[$path] = [
            "path"     => $path,
            "callback" => $callback
        ];
    }

    public static function post(string $path, $callback) {
        self::$post[$path] = [
            "path" => $path,
            "callback" => $callback
        ];
    }

    public static function put(string $path, $callback) {
        self::$put[$path] = [
            "path"     => $path,
            "callback" => $callback
        ];
    }

    public static function delete(string $path, $callback) {
        self::$delete[$path] = [
            "path" => $path,
            "callback" => $callback
        ];
    }

    public static function redirect(string $to) {
        header("Location:" . self::$url->link($to));
    }

    public static function init() {
        $request_method = strtolower($_SERVER['REQUEST_METHOD']); # Get request methdos, and convert it to lowercase
        $_GET['url'] = rtrim($_GET['url'], '/'); # Trim trailing slashes
        
        switch($request_method) {
            case "get":
                self::processURL(self::$get);
                break;
            case "post":
                self::processURL(self::$post, $_POST);
                break;
            case "put":
                self::processURL(self::$put, $_GET);
                break;
            case "delete":
                self::processURL(self::$delete, $_GET);
                break;
            default:
                die(ERR_NOT_FOUND);
                break;
        }
    }

}
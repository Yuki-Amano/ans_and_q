<?php

class Route
{
    static function start()
    {
        // по умолчанию
        $controller = 'question';
        $action = 'index';
        $routes = explode('/', $_SERVER['REQUEST_URI']);

        // имя контроллера
        if (!empty($routes[1]))
        {
            $controller = $routes[1];
        }

       
        if (!empty($routes[2]))
        {
            $actionUrl = explode('?', $routes[2]);
            $action = $actionUrl[0];
            if (isset($actionUrl[1])) {
                $params = [];
                foreach (explode('&', $actionUrl[1]) as  $value) {
                    $indexAdnValue = explode('=', $value);
                    $params[$indexAdnValue[0]] = $indexAdnValue[1];
                }
            }
        }
        
        $model = ucfirst($controller) . 'Model';
        $controller = ucfirst($controller) . 'Controller';
        $action = $action . 'Action';

        
        $model_file = $model . '.php';
        $model_path = 'models/' . $model_file;
        if(file_exists($model_path))
        {
            include_once $model_path;
        }
        // файл с классом контроллера
        $controller_file = $controller . '.php';
        $controller_path = 'controllers/' . $controller_file;
        if(file_exists($controller_path))
        {
            include_once $controller_path;
        }

        // создаем контроллер
        $controller = new $controller;
        if(method_exists($controller, $action))
        {
            
            (isset($params)) ? $controller->$action($params) : $controller->$action();
        }
    }
}

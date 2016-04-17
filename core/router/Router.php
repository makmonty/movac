<?php

namespace router;

use \controller\Controller as Controller;

class Router {

  private $routes = [];

  private $currentPath = "";

  public function route($route, $options) {
    if(is_callable($options)) {
      $current = $this->currentPath;
      $this->currentPath .= $route;

      $options($this);

      $this->currentPath = $current;
    } else {
      $this->routes[$this->currentPath . $route] = $options;
    }
  }

  public function dispatch($requestUri) {
    $path = explode("?", $requestUri)[0];
    $patternParams = "/:([^\/]+)/";
    $match = false;
    // preg_match_all("/:([^\/]+)/", "a/:id/:ab/:cd", $matches);
    foreach($this->routes as $route => $options) {
      preg_match_all($patternParams, $route, $matches);

      $routePattern = $route;
      $params = [];
      if(count($matches) > 0) {
        $params = $matches[1];
        $routePattern = preg_replace($patternParams, "([^/]+)", $route);
      }

      $routePattern = str_replace("/", "\/", $routePattern);

      if(preg_match("/^".$routePattern."$/", $path, $paramsMatches)) {
        array_shift($paramsMatches);
        $paramsAssoc = array_combine($params, $paramsMatches);
        $match = true;
        $this->callEndpoint($options, $paramsAssoc);
        break;
      }
    }

    if(!$match) {
      echo "404 Not found";
    }
  }

  public function callEndpoint($options, $params) {
    if(is_string($options)) {
      $actionArray = explode("::", $options);
      $controller = $actionArray[0];
      $action = $actionArray[1];
    } else if(is_array($options)) {
      $controller = $options["controller"];
      $action = $options["action"];
    }
    Controller::autoload($controller);
    $c = new $controller();
    $c->action = $action;

    $this->runFilters($c, $action, $params, $c->beforeFilters, $c->skipBeforeFilters);

    if(count($c->aroundFilters) == 0) {
      call_user_method_array($action, $c, $params);
    } else {
      $count = $this->runFilters($c, $action, $params, $c->aroundFilters, $c->skipAroundFilters);

      if(!$count) {
        call_user_method_array($action, $c, $params);
      }
    }

    $this->runFilters($c, $action, $params, $c->afterFilters, $c->skipAfterFilters);
  }

  public function runFilters($controller, $action, $params, $filters, $skip) {
    $count = 0;
    foreach($filters as $filter) {
      $runFilter = true;
      if(isset($skip[$filter])) {
        if(isset($skip[$filter]["only"]) && in_array($action, $skip[$filter]["only"]) ||
            isset($skip[$filter]["except"]) && !in_array($action, $skip[$filter]["except"])) {

          $runFilter = false;
        }
      }

      if($runFilter) {
        call_user_method_array($filter, $controller, $params);
        $count++;
      }
    }

    return $count;
  }
}

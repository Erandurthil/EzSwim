<?php
    error_reporting(0);
    require_once('config.php');
    require_once('routing.php');
    require_once('routes.php');
    
    $router = new Router('CKB'); //All requests must match host/CKB/<restoftheuristuff>
    setupRoutes($router);
    
    $router->route($_SERVER['REQUEST_URI']);
    exit;
?>
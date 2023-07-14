<?php

use controllers\UserController;

$path = explode('?',$_SERVER['REQUEST_URI'])[0];
$method = $_SERVER['REQUEST_METHOD'];
session_start();

spl_autoload_register(function ($class) {
    $class = str_replace('\\', '/', $class);
    require_once './'.$class . '.php';
});


if ($path == '/' && $method === 'GET') {
    $userController = new UserController();
    echo $userController->index();
} else if($path == '/user-get-statistic' && $method === 'GET'){
    $userController = new UserController();
    $statistic = $userController->getUserStatistic($_GET['id']);
    echo $statistic;
} else if($path == '/user-get-statistic-by-day' && $method === 'GET'){
    $userController = new UserController();
    $statistic = $userController->getUserStatisticByDay($_GET['id'], $_GET['date']);
    echo $statistic;
} else {
    header('Location: http://hoteliq.loc/');
}

<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);
define("APP_ROUTE_DIR", '');

require 'tpl/layout.php';
require 'framework/SessionManager.php';
require 'data/Database.php';
require 'app/routes/web.php';


$separators = ['/', '\\'];

$request_uri = explode("?", $_SERVER["REQUEST_URI"], 2);
$path = str_replace(APP_ROUTE_DIR, '', $request_uri[0]);
$path = trim(str_replace($separators, DIRECTORY_SEPARATOR, $path), DIRECTORY_SEPARATOR);
// $params = [];

// if (count($request_uri) > 1) {
//     $raw_params = explode("&", $request_uri[1]);

//     $params = [];

//     foreach ($raw_params as $param) {
//         $param = explode("=", $param);
//         $params[$param[0]] = $param[1];
//     }
// }

// echo $path;
// echo json_encode($request_uri);

SessionManager::start();

if (!route($path)){
    header("HTTP/1.0 404 Not Found");
    render("errors/404", ['title' => "Page Not Found"]);
}
<?php 

function render($view, $data = []) {
    $title = $view;
    extract($data);
    $content = "app/views/{$view}.php";
    require_once 'tpl/structure.php';
    // require_once "app/views/{$view}.php";
}

function content_render($view, $data = []) {
    extract($data);
    require_once "app/views/{$view}.php";
}
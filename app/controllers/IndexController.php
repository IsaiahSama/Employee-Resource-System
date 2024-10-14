<?php 

class IndexController{

    public function index() {
        header("HTTP/1.0 200 OK");
        render("index", ['title' => "Home"]);
    }
}
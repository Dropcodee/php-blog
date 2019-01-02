<?php 

class Posts extends Controller {
public function __construct() {

    //check if user is logged in to access post page
    if(!isLoggedIn()) {
        redirect('Users/login');
    }
}
    public function index() {
        $data = [];

        $this->view('posts/index', $data);
    }
}
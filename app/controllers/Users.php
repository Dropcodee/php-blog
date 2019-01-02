<?php
  class Users extends Controller {
    public function __construct(){
      $this->userModel = $this->model('User');
    }

    public function register(){
      // Check for POST
      if($_SERVER['REQUEST_METHOD'] == 'POST'){

        // Sanitize strings
        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
        // Process form
         $data =[
          'name' => trim($_POST['name']),
          'email' => trim($_POST['email']),
          'password' => trim($_POST['password']),
          'confirm_password' => trim($_POST['confirm_password']),
          'name_err' => "",
          'email_err' => "",
          'password_err' => "",
          'confirm_password_err' => ""
        ];

        // validate email
        if(empty($data['email'])) {
          $data['email_err'] = "Please Enter your Email";
        } else {
          // checks email in database
          if($this->userModel->findUserByEmail($data['email'])) {
            $data['email_err'] =  'Sorry This Email is already Registered';
          }
        }
        // validate name
         if(empty($data['name'])) {
          $data['name_err'] = "Please Enter your Name";
        }
        // validate password
         if(empty($data['password'])) {
          $data['password_err'] = "Please Enter your Password";
        } elseif(strlen($data['password']) < 6) {
          $data['password_err'] = "Password must be at least 6 characters long";
        }

        // validate confirm password
         if(empty($data['confirm_password'])) {
          $data['confirm_password_err'] = "Please Re-Enter your Password";
        } else {
          if($data['password'] != $data['confirm_password']){
            $data['confirm_password_err'] = "Password does not match";
          }
        }

        // make sure errors are empty
        if(empty($data['email_err']) && empty($data['name_err']) && empty($data['password__error']) && empty($data['confirm_password_err'])) {

          // hash password before going into DB
          $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
          
          // store all data in one model function which will then store data in the database
          if($this->userModel->register($data)) {
              flash('register_success', "You are now registered and can login");
              redirect('Users/login');
          } else {
            die("something went wrong");
          }
          
        } else {
          $this->view('Users/register', $data);
        }
      } else {
        // Init data
        $data =[
          'name' => '',
          'email' => '',
          'password' => '',
          'confirm_password' => '',
          'name_err' => '',
          'email_err' => '',
          'password_err' => '',
          'confirm_password_err' => ''
        ];

        // Load view
        $this->view('Users/register', $data);
      }
    }

    public function login(){
      // Check for POST
      if($_SERVER['REQUEST_METHOD'] == 'POST'){
        // Process form
        // Sanitize POST data
        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
        
        // Init data
        $data =[
          'email' => trim($_POST['email']),
          'password' => trim($_POST['password']),
          'email_err' => '',
          'password_err' => '',      
        ];

        // Validate Email
        if(empty($data['email'])){
          $data['email_err'] = 'Pleae enter email';
        } else {
          // cheking for email in the database.
          if($this->userModel->findUserByEmail($data['email'])) {
            // user found in the database.
          } else {
            // user not found in the database
            $data['email_err'] = 'Sorry User Does Not Exist';
          }
        }

        // Validate Password
        if(empty($data['password'])){
          $data['password_err'] = 'Please enter password';
        }

        // Make sure errors are empty
        if(empty($data['email_err']) && empty($data['password_err'])){
          // Validated
          // check and set logged in user
          $loggedIn = $this->userModel->login($data['email'], $data['password']);

          if($loggedIn) {
            // create session 
            $this->createUserSession($loggedIn);
          } else {
            $data['password_err'] = 'Password Is Incorrect';

            // render the view with errors
            $this->view('Users/login', $data);
          }
        } else {
          // Load view with errors
          $this->view('Users/login', $data);
        }


      } else {
        // Init data
        $data =[    
          'email' => '',
          'password' => '',
          'email_err' => '',
          'password_err' => '',        
        ];

        // Load view
        $this->view('Users/login', $data);
      }
    }

    // CREATE A SESSION FOR LOGGED IN USER
    public function createUserSession($user) {
      $_SESSION['user_id'] = $user->id;
      $_SESSION['user_email'] = $user->email;
      $_SESSION['user_name'] = $user->name;
      redirect('posts');
    }

    // logout function to logout the user
    public function logout() {
      unset($_SESSION['user_id']);
      unset($_SESSION['user_email']);
      unset($_SESSION['user_name']);
      session_destroy();
      redirect('Users/login');
    }
  }
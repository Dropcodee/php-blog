<?php

    session_start();

    // flash messages for after registration or logged in
    function flash($name = "", $message = "", $class = "alert alert-success") {

        // setting the session variables
        if(!empty($name)) {
            if(!empty($message) && empty($_SESSION[$name])) {

            // check to see if session name already exists then unset it before resetting session name and class to new variables values.
                if(!empty($_SESSION[$name])) {
                    unset($_SESSION[$name]);
                }
                if(!empty($_SESSION[$name. '_class'])) {
                    unset($_SESSION[$name. '_class']);
                }

            $_SESSION[$name] = $message;
            $_SESSION[$name. '_class'] = $class;
        } elseif(empty($message) && !empty($_SESSION[$name])) {
            $class = !empty($_SESSION[$name. '_class']) ? $_SESSION[$name. '_class'] : '';
            echo '<div class="'.$class.'" id="msg-flash">' . $_SESSION[$name].'</div>';

            // unset the session after use
            unset($_SESSION[$name]);
            unset($_SESSION[$name. '_class']);
             
        }
    }
    }


    // check if user is logged in or not in other to protect user from entering restricted routes
    function isLoggedIn() { 
      if(isset($_SESSION['user_id'])) {
        return true;
      } else {
        return false;
      }
    }
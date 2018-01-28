<?php
    session_start();

    if(isset($_SESSION['loggedin'])) {
        unset($_SESSION['loggedin']);
    }
    
    if(isset($_SESSION['loggedin_user'])) {
        unset($_SESSION['loggedin_user']);
    }
    
    if(isset($_SESSION['loggedin_user_access'])) {
        unset($_SESSION['loggedin_user_access']);
    }
    
    if(isset($_SESSION['loggedin_user_id'])) {
        unset($_SESSION['loggedin_user_id']);
    }
    
    header("Location: login.php");
?>
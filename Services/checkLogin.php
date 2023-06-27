<?php
include "../classes/Login.php";
session_start();
if( isset($_SESSION['user_logged_in']) &&  $_SESSION['user_logged_in'] == 1){
    echo 1;
}else{
    echo 0;
}
<?php 
include "File.php";
session_start();
if(isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] == 1){
    callFileObject(@$_REQUEST['file'] , @$_REQUEST['start'] , @$_REQUEST['paginate']);
}else{
    echo "Not Authenticated !!";
}

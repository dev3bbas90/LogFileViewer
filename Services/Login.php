<?php
include "../classes/Login.php";

$login = new Login();
$status = $login->loginWithPostData();

echo json_encode($login->response) ;

<?php
class Login{
    public      $user_is_logged_in          = false;                    // status of login
        
    public      $view_user_name             = "";

    public      $response                     = array();                  // collection of error messages
    public      $errors                     = array();                  // collection of error messages
    public      $messages                   = array();                  // collection of success / neutral messages
    
    public function __construct() {
        // START SESSION    
        session_start();        
        
        //COOKIE
        // cookie handling user name
        if (isset($_COOKIE['user_name'])) {
            $this->view_user_name = strip_tags($_COOKIE["user_name"]);
        } else {
            $this->view_user_name = "Username";
        }
        
        // First, logout request
        if (isset($_GET["logout"])) {
            $this->doLogout();						
            // if user pretend to be logged in.	            
        }elseif ((isset($_SESSION['user_logged_in'])) && ($_SESSION['user_logged_in'] == 1)) {
            $this->user_is_logged_in          = true;      
            $this->response = [
                'status' => 'success',
                'message' => "Logged in Before !!"
            ];
        }
                        
        // if user try to loggin (sending login form data)				    
        if ( isset($_POST["login"])) {
            if (empty($_POST['user_name']) || empty($_POST['password'])) {
                $this->response = [
                    'status' => 'error',
                    'message' => "Username or Password field was empty."
                ];
                // $this->errors[] = " Username or Password field was empty.";
                $this->doLogout();
            }
            // if user try to register ( sending login form data)
        }
    } 
   
    
    public function doLogout() {
		if(isset($_SESSION)){
            $_SESSION = array();
			session_regenerate_id();
		}
        $this->user_is_logged_in = false;			
    }

    public function loginWithPostData() 
    { 
        if($this->user_is_logged_in){
            $this->response = [
                'status' => 'success',
                'message' => "Logged in Successfully."
            ];
            return true;
        }
        if($_POST['user_name'] == 'admin:admin' && $_POST['password'] == 'admin:admin') {
            /**
             *  write user data into PHP SESSION [a file on your server]
            */
            $_SESSION['user_name']      = $_POST['user_name'];
            $_SESSION['user_logged_in'] = 1;
            
            // session security
            $_SESSION['agent'] = $_SERVER['HTTP_USER_AGENT'] ;
            $_SESSION['ip']    = $_SERVER['REMOTE_ADDR'];
            $_SESSION['count'] = 0;
                                 
            /**
             *  write user data into COOKIE [a file in user's browser]
            */
            setcookie("user_name", $_POST['user_name'] , time() + (3600*24*100));
            $this->user_is_logged_in = true;
            $this->response = [
                'status' => 'success',
                'message' => "Logged in successfuly."
            ];
            return true;
        } else {
            // $this->errors[] = "Wrong password or username. Try again.";
            $this->response = [
                'status' => 'error',
                'message' => "Wrong password or username. Try again."
            ];
            return false;
        }        
    }
    
	
    public function isUserLoggedIn() {    
        return $this->user_is_logged_in;
    }
}
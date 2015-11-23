<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Login
 *
 * @author Klaas
 */
class Login extends Main {
    function __construct() {
        
    }
    
    function render() {
        
        include(MODULE_PATH.'Login'.DIRECTORY_SEPARATOR.'view'.DIRECTORY_SEPARATOR.'login.phtml');
        
    }
    
    function Authorise(){
        
        include(LIBRARYPHP_PATH.DIRECTORY_SEPARATOR.'php'.DIRECTORY_SEPARATOR.'github_outh'.DIRECTORY_SEPARATOR.'github_outh.php');
        // Create our OAuth object
        $oauth = new GitHubOauth2(CLIENT_SCOPE, CLIENT_ID, CLIENT_SECRET);
        
        $oauth->get_user_authorization();
        
    }
    
    public function signup_github()
    {
        $client_id = CLIENT_ID;
        $redirect_url = 'your_callback_url';
         
        //get request , either code from github, or login request
        if($_SERVER['REQUEST_METHOD'] == 'GET')
        {
            //authorised at github
                    if(isset($_GET['code']))
            {
                $code = $_GET['code'];
                 
                //perform post request now
                $post = http_build_query(array(
                    'client_id' => $client_id ,
                    'redirect_uri' => $redirect_url ,
                    'client_secret' => 'your_client_secret',
                    'code' => $code ,
                ));
                 
                $context = stream_context_create(array("http" => array(
                    "method" => "POST",
                    "header" => "Content-Type: application/x-www-form-urlencodedrn" .
                                "Content-Length: ". strlen($post) . "rn".
                                "Accept: application/json" ,  
                    "content" => $post,
                ))); 
                 
                $json_data = file_get_contents("https://github.com/login/oauth/access_token", false, $context);
                 
                $r = json_decode($json_data , true);
                 
                $access_token = $r['access_token'];
                 
                $url = "https://api.github.com/user?access_token=$access_token";
                 
                $data =  file_get_contents($url);
                 
                //echo $data;
                $user_data  = json_decode($data , true);
                $username = $user_data['login'];
                 
                 
                $emails =  file_get_contents("https://api.github.com/user/emails?access_token=$access_token");
                $emails = json_decode($emails , true);
                $email = $emails[0];
                 
                $signup_data = array(
                    'username' => $username ,
                    'email' => $email ,
                    'source' => 'github' ,
                );
                 
                signup_login_user($signup_data);
            }
            else
            {
                $url = "https://github.com/login/oauth/authorize?client_id=$client_id&redirect_uri=$redirect_url&scope=user";
                header("Location: $url");
            }
        }
    }
    
}
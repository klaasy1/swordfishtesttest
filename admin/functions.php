<?php
    
    echo signup_github();
    
    /**
	 * This function Creates a new authorization for this app
	 */
    function signup_github()
    {
         
        //get request , either code from github, or login request
        if($_SERVER['REQUEST_METHOD'] == 'GET')
        {
            //authorised at github
            if(isset($_GET['code']))
            {
                $code = $_GET['code'];
                
                $fields = array( 'client_id'=>CLIENT_ID, 'client_secret'=>CLIENT_SECRET, 'code'=>$code);
                $postvars = '';
                foreach($fields as $key=>$value) {
                    $postvars .= $key . "=" . $value . "&";
                }
                
                $data = array('url' => 'https://github.com/login/oauth/access_token',
                              'data' => $postvars,
                              'header' => array("Content-Type: application/x-www-form-urlencoded","Accept: application/json"),
                              'method' => 'POST');
                
                $gitResponce = json_decode(curlRequest($data));
                
                if($gitResponce->access_token)
                {
                    $data = array('url' => 'https://api.github.com/user?access_token='.$gitResponce->access_token,
                                  'header' => array("Content-Type: application/x-www-form-urlencoded","User-Agent: ".APP_NAME,"Accept: application/json"),
                                  'method' => 'GET');
                    
                    $gitUser = json_decode(curlRequest($data));
                    
                    $signup_data = array(
                        'username' => $gitUser->login,
                        'email' => $gitUser->email,
                        'source' => 'github',
                        'token' => $gitResponce->access_token,
                    );

                    signup_login_user($signup_data);
                    
                }
                              
            }
        }
    }
    
    /**
	 * Initialise or check user session
	 * @param $signup_data the username, email and source
	 */
    function signup_login_user($signup_data = null){
        session_start();
        if($signup_data == null && isset($_SESSION['signup_data'])){
            header("Location: http://klaasy.koding.io/swordfish/");
        }
        elseif(isset($signup_data))
        {
            $_SESSION['signup_data'] = $signup_data;
            header("Location: http://klaasy.koding.io/swordfish/");
        }
        else
        {
            unset($_SESSION['signup_data']);
        }
    }
    
    /**
	 * Curl Request
	 * @param $data
	 * @return GitHubOauthAccessWithUser
	 */
    function curlRequest($data){
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $data['url']);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $data['header']);
        if(isset($data['data'])){
            curl_setopt($ch, CURLOPT_POST, count($data['data']));
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data['data']);
        }
        
        //receive server response ...
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        
        $server_output = curl_exec($ch);
        
        curl_close($ch);

        return $server_output;
        
    }
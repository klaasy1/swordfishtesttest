<?php

    include_once('config.php');
    
    //Create a new authorizatio
    $url = "https://github.com/login/oauth/authorize?client_id=".CLIENT_ID."&redirect_uri=".REDIRECT_URL."&scope=".CLIENT_SCOPE;
    header("Location: $url");

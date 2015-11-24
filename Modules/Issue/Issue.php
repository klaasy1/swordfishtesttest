<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Index
 *
 * @author Klaas
 */
class Issue extends Main {
    function __construct() {
        
    }
    
    /**
     * This function must be implemented, it is define in the abstract class
     *
     */
    function render() {
        
        include(LIBRARYPHP_PATH.DIRECTORY_SEPARATOR.'php'.DIRECTORY_SEPARATOR.'github-php-client-master'.DIRECTORY_SEPARATOR.'client'.DIRECTORY_SEPARATOR.'GitHubClient.php');

        $client = new GitHubClient();
        
        $client->setPage();
        $issuesPerPage = 100;
        
        $par = explode('/',$_REQUEST['params']);
        
        //
        if(isset($par[2])){
            if($par[2] == 'next'){
                $client->getNextPage();
            }
            elseif($par[2] == 'previous'){
                $client->getPreviousPage();
            }
            else{
                $issuesPerPage = $par[2];
            }
        }
        $client->setPageSize($issuesPerPage);
        
        //Authenticate user with username and password
        //$this->login($client);
        
        //Authenticate user TYPE OAUTH BASIC
        //$client->setAuthType(GitHubClientBase::GITHUB_AUTH_TYPE_OAUTH_BASIC);
        //$client->setOauthKey($_SESSION['signup_data']['token']); //This does the same this as $this->login($client);, but returns a 404 not sure why because the scope is user , repo (also added public_repo, repo_deployment, notifications, gist)
        //$client->setDebug(true);
        
        //Authenticate user TYPE OAUTH WEBFLOW
        $client->setAuthType(GitHubClientBase::GITHUB_AUTH_TYPE_OAUTH_WEBFLOW);
        $client->setOauthToken($_SESSION['signup_data']['token']);
            
        $issues = $client->issues->listIssues(OWNER, REPO);

        $row_content = "";
        foreach ($issues as $issue)
        {
            /* @var $issue GitHubIssue */
            $comments = $client->issues->comments->listCommentsOnAnIssue(OWNER, REPO, $issue->getNumber());

            $labels = $client->issues->labels->listLabelsOnAnIssue(OWNER, REPO, $issue->getNumber());

            //Get all Labels for this issue
            $priority = "";
            $client_name = "";
            $category = "";
            if(!empty($labels)){
                foreach($labels as $label):
                    if(strlen($label->getName()) > 2){
                        $tempArr = explode(":", $label->getName());
                        switch ($tempArr[0]){
                            case 'P':
                                $priority = $tempArr[1];
                                break;
                            case 'C':
                                $client_name = $tempArr[1];
                                break;
                            case 'Cat':
                                $category = $tempArr[1];
                                break;
                        }
                    }
                endforeach;
            }

            //Get all comments for this issue
            $comment_text = "";
            if(!empty($comments)){
                foreach($comments as $comment):
                    if($comment_text != "") 
                        '<br><br>'.$comment_text .= $comment->getBody();
                    else
                        $comment_text .= $comment->getBody();
                endforeach;
            }

            $assignee = "";
            if(!empty($issue->getAssignee()))
                $assignee = $issue->getAssignee()->getLogin();
            
            $row_content .= '<tr>
                                <td>'.$client_name.'</td><td>'.$issue->getTitle().'</td><td>'.$issue->getBody().'</td><td>#'.$issue->getNumber().'</td><td>'.$priority.'</td><td>'.$category.'</td><td>'.$assignee.'</td><td>'.$comment_text.'</td><td>'.$issue->getState().'</td>
                            </tr>';
            
        }
        
        include(MODULE_PATH.'Issue'.DIRECTORY_SEPARATOR.'view'.DIRECTORY_SEPARATOR.'issue.phtml');
    }
    
    function add(){
        
        if(isset($_POST['client_name'])){
            
            include(LIBRARYPHP_PATH.DIRECTORY_SEPARATOR.'php'.DIRECTORY_SEPARATOR.'github-php-client-master'.DIRECTORY_SEPARATOR.'client'.DIRECTORY_SEPARATOR.'GitHubClient.php');
        
            $client = new GitHubClient();
            //$client->setDebug(true);

            //Authenticate user TYPE OAUTH BASIC
            //$client->setAuthType(GitHubClientBase::GITHUB_AUTH_TYPE_OAUTH_BASIC);
            //$client->setOauthKey($_SESSION['signup_data']['token']); //This does the same this as $this->login($client);, but returns a 404 not sure why because the scope is user , repo
            
            //Authenticate user TYPE OAUTH WEBFLOW
            //$client->setAuthType(GitHubClientBase::GITHUB_AUTH_TYPE_OAUTH_WEBFLOW);
            //$client->setOauthToken($_SESSION['signup_data']['token']); //This does the same this as $this->login($client);, but returns a 404 not sure why because the scope is user , repo
            
            //Authenticate user with username and password
            $this->login($client); //Use top OAUTH WEBFLOW to authenticate with a token, but it returns a 404 not sure why uncomment $client->setDebug(true); to see what the server response is
            
            $client->issues->createAnIssue(OWNER, REPO, $_POST['title'], $_POST['description'], $_POST['assignee'], null, array($_POST['priority'], $_POST['category'], $_POST['client_name']));
            
            header('Content-Type: application/json');
            //Set "success" to true
            $jsonResponse['success'] = true;
            //Message to display after succesfull issue creation
            $jsonResponse['message'] = "Issue added successful";
            //Redirect to Issue
            //$jsonResponse['redirect'] = "Issue/success";
            
            echo json_encode($jsonResponse);
            
            
            return true;
        }
        
        include(LIBRARYPHP_PATH.DIRECTORY_SEPARATOR.'php'.DIRECTORY_SEPARATOR.'github-php-client-master'.DIRECTORY_SEPARATOR.'client'.DIRECTORY_SEPARATOR.'GitHubClient.php');
        
        $client = new GitHubClient();
        $this->login($client);
        
        $labels = $client->issues->labels->listAllLabelsForThisRepository(OWNER, REPO);
        //print_r($labels);
        
        $priority = "";
        $client_name = "";
        $category = "";
        foreach($labels as $label){
            $tempArr = explode(":", $label->getName());
            switch ($tempArr[0]){
                case 'P':
                    $priority .= '<option value="'.$label->getName().'">'.$tempArr[1].'</option>';
                    break;
                case 'C':
                    $client_name .= '<option value="'.$label->getName().'">'.$tempArr[1].'</option>';
                    break;
                case 'Cat':
                    $category .= '<option value="'.$label->getName().'">'.$tempArr[1].'</option>';
                    break;
            }
        }
        
        $assignees = $client->issues->assignees->listAssignees(OWNER, REPO);
        include(MODULE_PATH.'Issue'.DIRECTORY_SEPARATOR.'view'.DIRECTORY_SEPARATOR.'add.phtml');
        
    }
    
    /**
	 * Login with user credentials
	 * @param $client object
	 */
    function login($client){
        $client->setCredentials('swordfishtest', 'warr10r');
    }
    
    /**
	 * Logs out
	 */
    function logout(){
        session_destroy();
        header("Locaction http://klaasy.koding.io/swordfish/admin");
    }
    
    /**
	 * Display the success page on successful issue creation
	 */
    function success(){
        include(MODULE_PATH.'Issue'.DIRECTORY_SEPARATOR.'view'.DIRECTORY_SEPARATOR.'success.phtml');
    }
    
}
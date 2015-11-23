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
    
    function render() {
        
        include(LIBRARYPHP_PATH.DIRECTORY_SEPARATOR.'php'.DIRECTORY_SEPARATOR.'github-php-client-master'.DIRECTORY_SEPARATOR.'client'.DIRECTORY_SEPARATOR.'GitHubClient.php');

        $client = new GitHubClient();
        
        $client->setPage();
        $issuesPerPage = 1;
        
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
        
        $this->login($client);
        
        //Authenticate user
        //$client->setAuthType(GitHubClientBase::GITHUB_AUTH_TYPE_OAUTH_BASIC);
        //$client->setOauthKey($_SESSION['token']); 
        
        
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

            //Authenticate user
            //$client->setAuthType(GitHubClientBase::GITHUB_AUTH_TYPE_OAUTH_BASIC);
            //$client->setOauthKey($_SESSION['token']); //This does the same this as $this->login($client);, but returns a 404 not sure why because the scope is user , repo
            $this->login($client); //Use top 2 lines to authenticate with a token

            $client->issues->createAnIssue(OWNER, REPO, $_POST['title'], $_POST['description'], $_POST['Swordfishtest'], null, array($_POST['priority'], $_POST['category'], $_POST['client_name']));

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
        $assignees = $client->issues->assignees->listAssignees(OWNER, REPO);
        include(MODULE_PATH.'Issue'.DIRECTORY_SEPARATOR.'view'.DIRECTORY_SEPARATOR.'add.phtml');
        
    }
    
    function login($client){
        $client->setCredentials('swordfishtest', 'warr10r');
    }
    
    function logout(){
        session_destroy();
        header("Locaction http://klaasy.koding.io/swordfish/admin");
    }
    
    function success(){
        include(MODULE_PATH.'Issue'.DIRECTORY_SEPARATOR.'view'.DIRECTORY_SEPARATOR.'success.phtml');
    }
    
}
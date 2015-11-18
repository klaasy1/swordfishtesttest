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
        //$oauth = $client->oauth->getOrCreateAuthorizationForApp('user, repo, gist', 'Just a note', 'http://klaasy.koding.io/swordfish/', '8a6e684745810b594a77', 'e821fd28347d035c665cb99677ba16520652a0ee');
        //$oauth = $client->oauth->listYourAuthorizations();
        
        $this->login($client);
        
        $client->setPage();
        $issuesPerPage = 5;
        if(isset($_POST['issuesPerPage'])){
            $issuesPerPage = $_POST['issuesPerPage'];
        }
        $client->setPageSize($issuesPerPage);
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
        
        $jsonResponse = array();
        
        if(isset($_POST['client_name'])){
            
            header('Content-Type: application/json');
            
            include(LIBRARYPHP_PATH.DIRECTORY_SEPARATOR.'php'.DIRECTORY_SEPARATOR.'github-php-client-master'.DIRECTORY_SEPARATOR.'client'.DIRECTORY_SEPARATOR.'GitHubClient.php');
        
            $client = new GitHubClient();
        
            $this->login($client);
        
            //$client->issues->createAnIssue(OWNER, REPO, $_POST['client_name'], $_POST['description']);
            $client->issues->createAnIssue(OWNER, REPO, $_POST['title'], $_POST['description'], $_POST['Swordfishtest'], null, array($_POST['priority'], $_POST['category'], $_POST['client_name']));
            
            //Set "success" to true
            $jsonResponse['success'] = true;
            //Message to display after succesfull issue creation
            $jsonResponse['message'] = "Issue added successful";
            //Redirect to Issue
            $jsonResponse['redirect'] = "Issue";
            
            //echo json_encode($jsonResponse);
            
            include(MODULE_PATH.'Issue'.DIRECTORY_SEPARATOR.'view'.DIRECTORY_SEPARATOR.'success.phtml');
            
            return true;
        }
        
        
        $jsonResponse['success'] = false;
        //$jsonResponse['redirect'] = "";
            
        include(MODULE_PATH.'Issue'.DIRECTORY_SEPARATOR.'view'.DIRECTORY_SEPARATOR.'add.phtml');
        
    }
    
    function login($client){
        $client->setCredentials('swordfishtest', 'warr10r');
    }
    
}
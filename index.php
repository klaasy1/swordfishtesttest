<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
session_start();
if(!isset($_SESSION['signup_data'])){
    header("Location: http://klaasy.koding.io/swordfish/admin/");
}
include('config.php');
include(CLASS_PATH.'Main.php');
include(CLASS_PATH.'Router.php');
$router = new Router();
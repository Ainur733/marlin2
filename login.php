<?php
session_start();
require_once "functions.php";

$email = $_POST['email'];
$password = $_POST['password'];



if(login($email, $password)) {

    redirect_to("/users.php");
}

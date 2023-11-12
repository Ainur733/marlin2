<?php
session_start();

require_once "functions.php";

$id = $_POST['id'];
$status = $_POST['status'];

set_status($id, $status);
set_flash_message("success", "Статус успешно обновлен");

redirect_to("/users.php");
exit();


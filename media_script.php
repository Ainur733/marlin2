<?php
session_start();
require_once "functions.php";
$file = $_FILES['file'];

$id =$_POST['id'];


upload_avatar($id, $file);
set_flash_message("success", "Аватар успешно обновлен");
redirect_to("/media.php/?id=$id");

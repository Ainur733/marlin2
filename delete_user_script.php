<?php
session_start();

require_once "functions.php";

if (is_not_logged_in()) {
    redirect_to("page_login.php");
}


$id = $_GET['id'];
$user = get_user_by_id($id);



if(!is_admin(get_logged_user())) {

    if (!is_autor($user)) {
        var_dump('no autor');
        set_flash_message("danger", "Вам можно редактировать только свой профиль");
        redirect_to("/users.php");
        exit();
    }

}

remove_user($id);

set_flash_message("success", "Профиль удален");

redirect_to("/users.php");
exit();





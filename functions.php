<?php

const IMG_PATH = __DIR__ . "/img/demo/avatars/";
function get_user_by_email($email)
{
    $pdo = new PDO("mysql:dbname=marlin2;host=localhost", "root", "");
    $sql = "SELECT * FROM users WHERE email = :email";
    $statement = $pdo->prepare($sql);
    $statement->execute(['email' => $email]);
    return $statement->fetch(PDO::FETCH_ASSOC);
}

function add_user($email, $password)
{
    $pdo = new PDO("mysql:dbname=marlin2;host=localhost", "root", "");
    $password_hash = password_hash($password, PASSWORD_DEFAULT);
    $sql = "INSERT INTO users(email, password) VALUES(:email, :password)";
    $statement = $pdo->prepare($sql);
    $statement->execute(['email' => $email, 'password' => $password_hash]);
    return $pdo->lastInsertId();
}

function redirect_to($url)
{
    header("Location: $url");
}

function set_flash_message($name, $value)
{
    $_SESSION[$name] = $value;
}

function display_flash_message($name)
{
    if (isset($_SESSION[$name])) {
        echo "<div class=\"alert alert-{$name} text-dark\" role=\"alert\">{$_SESSION[$name]}</div>";
        unset($_SESSION[$name]);
    }
}

function login($email, $password)
{
    $user = get_user_by_email($email);

    if (empty($user)) {
        set_flash_message("danger", "Не правильный логин или пароль");
        redirect_to("/page_login.php");
        exit();
    }

    if (!password_verify($password, $user['password'])) {
        set_flash_message("danger", "Не правильный логин или пароль");
        redirect_to("/page_login.php");
        exit();
    }

    set_flash_message("user", $user);
    return true;
}


function is_logged_in()
{
    if (isset($_SESSION['user'])) {
        return true;
    }
    return false;
}

function is_not_logged_in()
{
    return !isset($_SESSION['user']);
}

function get_logged_user()
{
    if (is_logged_in()) {
        return $_SESSION['user'];
    }

}

function is_admin($user)
{
    if (is_logged_in()) {
        if ($user['role'] === 'admin') {
            return true;
        }

        return false;
    }
}

function is_autor($user)
{
    if (get_logged_user() == $user) {
        return true;
    } else
        return false;
}

function get_users()
{
    $pdo = new PDO("mysql:dbname=marlin2;host=localhost", "root", "");
    $sql = "SELECT * FROM users";
    $statement = $pdo->prepare($sql);
    $statement->execute();
    return $statement->fetchAll(PDO::FETCH_ASSOC);
}

function get_user_by_id($id)
{
    $pdo = new PDO("mysql:dbname=marlin2;host=localhost", "root", "");
    $sql = "SELECT * FROM users WHERE id=:id";
    $statement = $pdo->prepare($sql);
    $statement->execute(['id' => $id]);
    return $statement->fetch(PDO::FETCH_ASSOC);
}

function edit_user_info($id, $name, $work, $phone, $location)
{
    $pdo = new PDO("mysql:dbname=marlin2;host=localhost", "root", "");
    $sql = "UPDATE users SET 
                 `name` = :name,
                 `work` = :work,
                 `phone` =:phone,
                 `location` =:location
             WHERE id=:id";
    $statement = $pdo->prepare($sql);
    $statement->execute(['id' => $id, 'name' => $name, 'work' => $work, 'phone' => $phone, 'location' => $location]);
}

function set_status($id, $status)
{
    $pdo = new PDO("mysql:dbname=marlin2;host=localhost", "root", "");
    $sql = "UPDATE users SET 
                 `status` = :status
             WHERE id=:id";
    $statement = $pdo->prepare($sql);
    $statement->execute(['id' => $id, 'status' => $status]);
}

//function has_image($id, $image)
//{
//    $user = get_user_by_id($id);
//
//   if(!empty($user['image'])) {
//       return true;
//   }
//
//   else return false;
//
//}

function upload_avatar($id, $file)
{

    if (empty($file['name'])) {
        $image_link = "avatar-m.png";

    } else {
        $image_link = uniqid() . "." . pathinfo($file['name'])['extension'];
    }

    $upload_dir = __DIR__ . '/img/demo/avatars/';
    $pdo = new PDO("mysql:dbname=marlin2;host=localhost", "root", "");
    $sql = "UPDATE users SET 
                 `image` = :image_link
             WHERE id=:id";
    $statement = $pdo->prepare($sql);
    $statement->execute(['id' => $id, 'image_link' => $image_link]);
    if (!empty($file['name'])) {
        move_uploaded_file($file['tmp_name'], $upload_dir . $image_link);
    }

}

function add_social_links($id, $vk, $telegram, $instagram)
{
    $pdo = new PDO("mysql:dbname=marlin2;host=localhost", "root", "");
    $sql = "UPDATE users SET 
                 `vk_link` = :vk_link,
                 `tg_link` = :tg_link,
                 `instagram_link` = :instagram_link
             WHERE id=:id";
    $statement = $pdo->prepare($sql);
    $statement->execute(['id' => $id, 'vk_link' => $vk, 'tg_link' => $telegram, 'instagram_link' => $instagram]);
}


function update_credentials($id, $email, $password)
{
    $user = get_user_by_email($email);

    if (!empty($user) && $user['email'] == $email) {
        if (!empty($password)) {
            $pdo = new PDO("mysql:dbname=marlin2;host=localhost", "root", "");
            $password_hash = password_hash($password, PASSWORD_DEFAULT);
            $sql = "UPDATE users SET 
                 `email` = :email,
                 `password` = :password
             WHERE id=:id";
            $statement = $pdo->prepare($sql);
            $statement->execute(['email' => $email, 'password' => $password_hash, 'id' => $id]);
        } else {
            set_flash_message("danger", "Пароль не может быть пустым");
            redirect_to("/security.php?id=$id");
            exit();
        }



    } else {
        if (empty($user)) {
            if (!empty($password)) {
                $pdo = new PDO("mysql:dbname=marlin2;host=localhost", "root", "");
                $password_hash = password_hash($password, PASSWORD_DEFAULT);
                $sql = "UPDATE users SET 
                     `email` = :email,
                     `password` = :password
                 WHERE id=:id";
                $statement = $pdo->prepare($sql);
                $statement->execute(['email' => $email, 'password' => $password_hash, 'id' => $id]);
            }else {
                set_flash_message("danger", "Пароль не может быть пустым");
                redirect_to("/security.php?id=$id");
                exit();
            }


        } else {
            set_flash_message("danger", "Email уже занят");
            redirect_to("/security.php?id=$id");
            exit();
        }
    }

}

function remove_user($id)
{
    $pdo = new PDO("mysql:dbname=marlin2;host=localhost", "root", "");
    $sql = "DELETE FROM users WHERE   id=:id";
    $statement = $pdo->prepare($sql);
    $statement->execute(['id' => $id]);
}

function logout($id) {
    unset($_SESSION['user']);
    redirect_to("/login.php");
}

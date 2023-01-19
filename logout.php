<?php
if (isset($_COOKIE['token']) && !empty($_COOKIE['token'])) {
    setcookie('token', '', time() - 60);
    header('Location: /login.php');
}
?>

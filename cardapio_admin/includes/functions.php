<?php
// includes/functions.php

function sanitize($data) {
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

function display_error($message) {
    return '<div class="alert alert-danger">'.$message.'</div>';
}

function display_success($message) {
    return '<div class="alert alert-success">'.$message.'</div>';
}

function redirect($url) {
    header("Location: /cardapio_admin/" . $url);
    exit;
}

function require_login()
{
    if (!isset($_SESSION['user_id'])) {
        redirect('login.php');
    }
}

function is_logged_in()
{
    return isset($_SESSION['user_id']);
}

?>
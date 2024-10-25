<?php
session_start();
require_once 'includes/db_connect.php';

if (isset($_SESSION['user_id'])) {
    header('Location: user/home.php');
} else {
    header('Location: user/login.php');
}
exit();
?>

<?php

function logout()
{
    session_start();
    unset($_SESSION['username']);
    session_destroy();
    header('Location: ../../php/clientside/login.php');
    exit;
}

if (isset($_POST['submit'])) {
    logout();
}

?>

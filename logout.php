<?php

session_start();
unset($_SESSION['login']); 
unset($_SESSION['id_role']); 
session_destroy();
header('Location: http://'.$_SERVER['HTTP_HOST'].'/index.php');

?>
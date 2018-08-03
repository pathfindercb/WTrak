<?php
session_start();
unset($_SESSION["wuserid"]);
header("Location:Login.php");
?>
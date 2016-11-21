<?php 
session_start();
session_reset();
	//echo $_SESSION["usu_id"];
session_destroy();
header("location:../index.php")
 ?>
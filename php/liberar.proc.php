<?php
	//Cogemos la ID del recurso que queremos liberar
	$id_recu	=	$_GET['recu_id'];
	//Cogemos la ID del reserva
	$id_res		=	$_GET['res_id'];
	//echo $id_res;
	//echo $_GET['id'];
	//Cogemos la fecha actual en formato americano
	$fdate	= date("Y-m-d H:i:s");
	//echo $fdate;
	$mysqli = new mysqli("localhost", "root", "", "bd_proyecto2");
	$con_recu	=	"UPDATE `tbl_recurso` SET `rec_estado` = 'disponible' WHERE `tbl_recurso`.`rec_id` = ".$id_recu.";";
	$con_resu	=	"UPDATE `tbl_reserva` SET `res_fechafinal` = '$fdate' WHERE `tbl_reserva`.`res_id` =" . $id_res;
	//echo "<br/>" . $con_resu;
	$free_recur 	=	mysqli_query($mysqli,$con_recu);
	$finis_reser	=	mysqli_query($mysqli,$con_resu);
	header("location:misrecursos.php");
?>
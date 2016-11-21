<?php
//realizamos la conexión
		$conexion = mysqli_connect('localhost', 'root', '', 'bd_proyecto2');

		//le decimos a la conexión que los datos los devuelva diréctamente en utf8, así no hay que usar htmlentities
		$acentos = mysqli_query($conexion, "SET NAMES 'utf8'");

		if (!$conexion) {
		    echo "Error: No se pudo conectar a MySQL." . PHP_EOL;
		    echo "errno de depuración: " . mysqli_connect_errno() . PHP_EOL;
		    echo "error de depuración: " . mysqli_connect_error() . PHP_EOL;
		    exit;
		}


		extract($_REQUEST);

		$fecha = date("Y-m-d H:i:s");

		$sql_estado = " UPDATE tbl_recurso SET rec_estado='incidencia' WHERE rec_id='$rec_id'";

		$sql_incidencia = "INSERT INTO tbl_incidencia (inc_comentario, inc_recursoid, inc_tipinc, inc_fechaini, inc_fechafinal)  VALUES ('$comentario', '$rec_id', '$incidencia', '$fecha', NULL)";

		$sql_reserva = "UPDATE tbl_reserva SET res_fechafinal='$fecha' WHERE res_recursoid='$rec_id'";

	$estado=mysqli_query($conexion, $sql_estado);
	$incidencia=mysqli_query($conexion, $sql_incidencia);
	$reserva=mysqli_query($conexion, $sql_reserva);

	header('location: misrecursos.php');
?>

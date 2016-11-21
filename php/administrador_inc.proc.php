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

		$sql_estado = " UPDATE tbl_recurso SET rec_estado='disponible' WHERE rec_id='$rec_id'";
		$sql_incidencia = "UPDATE tbl_incidencia SET inc_fechafinal='$fecha' WHERE rec_id='$rec_id'";


	$estado=mysqli_query($conexion, $sql_estado);
	$incidencia=mysqli_query($conexion, $sql_incidencia);


	header('location: administrador_recursos.php');
?>

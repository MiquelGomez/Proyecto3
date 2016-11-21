<?php
		include 'conexion.php';
		extract($_REQUEST);
		session_start();
		$usuario = $_SESSION['usu_id'];
		$fecha_ini = "$fecha $hora_ini:01:00";

		$fecha_final = "$fecha $hora_fi:00:00";

		$verificar_reserva = "SELECT * FROM tbl_reserva WHERE res_recursoid=$rec_id AND (res_finalizada='ocupado' OR res_finalizada='reservado') AND res_fechainicio<'$fecha_final' AND res_fechafinal>'$fecha_ini'";

		$verificar_reservas = mysqli_query($conexion,$verificar_reserva);
		if (mysqli_num_rows($verificar_reservas)>0){
			echo "hola";
			header('location: recursos.php?res=2');
		} else {

		$insertar_reserva = "INSERT INTO tbl_reserva (res_fechainicio, res_fechafinal, res_finalizada, res_recursoid, res_usuarioid) VALUES ('$fecha_ini', '$fecha_final', 'reservado', '$rec_id', '$usuario')";

		$reservar_producto = mysqli_query($conexion, $insertar_reserva);
		header('location: recursos.php?res=1');
		echo "adios";
	}
?>
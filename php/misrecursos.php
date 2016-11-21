<?php 
	session_start();
		if(!isset($_SESSION["usu_id"])) {
			header("location:../index.php?nolog=2");
		}

		include 'conexion.php';
 ?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<link rel="stylesheet" type="text/css" href="../css/recursos.css">
		<title>Mis recursos</title>
		<script type="text/javascript">
		function destroy(){
			var respuesta = confirm("¿Está seguro que desea liberar este recurso?");
			if(respuesta){
				return true;
			}
			else{
				return false;
			}
			
		}

		</script>
		<script type="text/javascript">
		function logout()
		{
			var login_respuesta = confirm("¿Está seguro que desea cerrar la sesión?");
			if(login_respuesta){
				return true;
			}
			else{
				return false;
			}
		}
	</script>
	</head>
	<body>
	<?php

	//Cogemos el nombre de usuario y la imagen de forma dinámica en la BD
	$con =	"SELECT * FROM `tbl_usuario` WHERE `usu_id` = '". $_SESSION["usu_id"] ."'";
	//echo $con;
		//Lanzamos la consulta a la BD
		$result	=	mysqli_query($conexion,$con);
		while ($fila = mysqli_fetch_row($result)) 
			{
				$usu_nickname	=	$fila[1];
				$usu_img	=	$fila[6];
				
			}
			
	?>
		<div class="header">
			<div class="logo">
				<a href="#"></a>
			</div>
			<h1 align="center">Gestión de recursos</h1>
			<div class="profile">
			<p class="welcome">Hola bienvenido, <br /><b>
			<?php echo $usu_nickname; ?></b>
			
			</p>
			</div>
			<div class="logout">
				<a href="logout.proc.php" onclick="return logout();">
					<img class="img_logout" src="../img/logout_small.png" alt="Cerrar sesión">
				</a>
			</div>
		</div>
		<nav>
			<ul class="topnav">	
				<li class="li"><a href="recursos.php">Recursos</a></li>
				<li class="li"><a href="#">Mis recursos</a></li>
				<li class="li"><a href="historial_recursos.php">Historial de recursos</a></li>
			</ul>
		</nav>
		<div class="container">
			<h1> Reservas en curso</h1> <br/>	
		</div>
			<?php
				//Seleccionamos todas las reservas que tiene asignado nuestro usuario
				$reservas_encurso	=	"SELECT * FROM tbl_reserva INNER JOIN tbl_recurso ON tbl_recurso.rec_id = tbl_reserva.res_recursoid WHERE `res_usuarioid` = " . $_SESSION['usu_id'] . " AND res_finalizada='ocupado'";

				$result 	=	mysqli_query($conexion,$reservas_encurso);
	if(mysqli_num_rows($result)>0){ 
				while($fila	=	mysqli_fetch_array($result)){

						echo "<div class='content_rec'>";
								echo "<table border>";
									echo "<tr>";
										echo "<td colspan='2'>" . $fila['rec_nombre'] . "</td>";
									echo "</tr>";
									echo "<tr>";
										echo "<td rowspan='3'><img class='img_recu' src='../img/recursos/".$fila['rec_foto']."' width='100'></td>";
										echo "<td>".$fila['rec_descripcion']."</td>";
									echo "</tr>";
									echo "<tr>";
										echo "<td>Fecha de inicio:" .$fila['res_fechainicio']. "</td>";
									echo "</tr>";
									echo "<tr>";
										echo "<td>Fecha de Final: " .$fila['res_fechafinal']."</td>";
									echo "</tr>";
									echo "<td><a href='misrecursos.proc.php?res_id=".$fila['res_id']."&name='recurso'>LIBERAR RESERVA</td>";
									echo "</tr>";
								echo "</table>";
						echo "</div>";
					}
				} else {
					echo "No tienes ninguna reserva en curso";
				}

			?>
			<div class="container">
			<h1> Reservas </h1> <br/>	
		</div>
			<?php
				//Seleccionamos todas las reservas que tiene asignado nuestro usuario
				$reservas_encurso	=	"SELECT * FROM tbl_reserva INNER JOIN tbl_recurso ON tbl_recurso.rec_id = tbl_reserva.res_recursoid WHERE `res_usuarioid` = " . $_SESSION['usu_id'] . " AND res_finalizada='reservado'";

				$result 	=	mysqli_query($conexion,$reservas_encurso);
	if(mysqli_num_rows($result)>0){ 
				while($fila	=	mysqli_fetch_array($result)){

						echo "<div class='content_rec'>";
								echo "<table border>";
									echo "<tr>";
										echo "<td colspan='2'>" . $fila['rec_nombre'] . "</td>";
									echo "</tr>";
									echo "<tr>";
										echo "<td rowspan='3'><img class='img_recu' src='../img/recursos/".$fila['rec_foto']."' width='100'></td>";
										echo "<td>".$fila['rec_descripcion']."</td>";
									echo "</tr>";
									echo "<tr>";
										echo "<td>Fecha de inicio:" .$fila['res_fechainicio']. "</td>";
									echo "</tr>";
									echo "<tr>";
										echo "<td>Fecha de Final: " .$fila['res_fechafinal']."</td>";
									echo "</tr>";
									echo "<tr>";
										echo "<td><a href='misrecursos.proc.php?res_id=".$fila['res_id']."&name='reserva'>LIBERAR RESERVA</td>";
									echo "</tr>";
								echo "</table>";
						echo "</div>";
					}
				} else {
					echo "No tienes ninguna reserva en curso";
				}

			?>

	</body>
</html>
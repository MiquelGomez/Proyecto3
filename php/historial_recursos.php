<?php
		session_start();
		if(!isset($_SESSION["usu_id"])) {
			header("location:../index.php?nolog=2");
		}
		//realizamos la conexión
		include 'conexion.php';


		//session_start();
		$con =	"SELECT * FROM `tbl_usuario` WHERE `usu_id` = '". $_SESSION["usu_id"] ."'";
		//echo $con;
		//Lanzamos la consulta a la BD
		$result	=	mysqli_query($conexion,$con);
		while ($fila = mysqli_fetch_row($result)) 
			{
				$usu_nickname	=	$fila[1];
				$usu_img	=	$fila[6];
				$usuario = $_SESSION['usu_id'];
			}
			
	


		$sql = "SELECT * FROM tbl_tiporecurso ORDER BY tr_id";

		$finalizado_sql =	" SELECT * FROM tbl_reserva INNER JOIN tbl_recurso ON tbl_recurso.rec_id = tbl_reserva.res_recursoid WHERE res_finalizada='finalizada' AND  res_usuarioid='$usuario'";

		extract($_REQUEST);

		if(isset($enviar)){
		 	if($tr_id>0){
		 		$finalizado_sql .= " AND rec_tipoid='$tr_id '";
		 	}
		}
		$finalizado_sql .= "ORDER BY `tbl_reserva`.`res_id`  DESC";

		$finalizados = mysqli_query($conexion, $finalizado_sql);
		$tipos = mysqli_query($conexion, $sql);

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="../css/recursos.css">
	<title>Historial Recursos</title>

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
		<li class="li"><a href="misrecursos.php">Mis recursos</a></li>
		<li class="li"><a href="#">Historial de recursos</a></li>
	</ul>
</nav>
<div class="container">
	<?php
	if(mysqli_num_rows($finalizados)>0 && mysqli_num_rows($tipos)>0){
		?>
	<form action="historial_recursos.php" method="get" class="formtipo">
	Tipo de recurso:
		<select name="tr_id">
			<option value="0">-- Elegir tipo --</option>
			<?php
					while($tipo=mysqli_fetch_array($tipos)){
						echo "<option value=" . $tipo['tr_id'] . ">" . $tipo['tr_nombre'] . "</option>";
					}
				?>
		</select>
		<input type="submit" name="enviar" value="Filtrar">
	</form>
	<br/>
	<br/>
	<h1>Historial de recursos</h1>
	<br/>

	<?php
		if(mysqli_num_rows($finalizados)>0){
			
								while($finalizado=mysqli_fetch_array($finalizados)){
									echo "<div class='content_rec'>";
										//echo $fila[0]
									echo "<table border>";
										echo "<tr>";
											echo "<td colspan='2'>" . $finalizado['rec_nombre'] . "</td>";
										echo "</tr>";
										echo "<tr>";
											echo "<td rowspan='3'><img class='img_recu' src='../img/recursos/".$finalizado['rec_foto']."' width='100'></td>";
											echo "<td>".$finalizado['rec_descripcion']."</td>";
										echo "</tr>";
										echo "<tr>";
											echo "<td>Fecha de inicio: " .$finalizado['res_fechainicio']. "</td>";
										echo "</tr>";
										echo "<tr>";
											echo "<td>Fecha liberación: " .$finalizado['res_fechafinal']. "</td>";
											
											echo "</tr>"; 
											
														
									echo "</table>";
									echo "</div>";
									echo "</br>";

					} 
				} 
			} else {
				echo "Aún no has reservado ningun recurso";
			}

		?>
</div>
</body>
</html>
<?php
		session_start();
		if(!isset($_SESSION["usu_id"])) {
			header("location:../index.php?nolog=2");
		}
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

		//session_start();
		//$mysqli = new mysqli("localhost", "root", "", "bd_proyecto2");
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
			


		$sql = "SELECT * FROM tbl_tiporecurso ORDER BY tr_id";

		$disponible =	" SELECT * FROM tbl_recurso INNER JOIN tbl_tiporecurso ON tbl_tiporecurso.tr_id = tbl_recurso.rec_tipoid WHERE rec_estado='disponible' ";

		$ocupado =	" SELECT * FROM tbl_recurso INNER JOIN tbl_tiporecurso ON tbl_tiporecurso.tr_id = tbl_recurso.rec_tipoid WHERE rec_estado='ocupado' ";
		$incidencia = " SELECT * FROM tbl_recurso INNER JOIN tbl_tiporecurso ON tbl_tiporecurso.tr_id = tbl_recurso.rec_tipoid INNER JOIN tbl_incidencia ON tbl_incidencia.inc_recursoid = tbl_recurso.rec_id INNER JOIN tbl_tipoinc ON tbl_incidencia.inc_tipinc = tbl_tipoinc.ti_id WHERE rec_estado='incidencia' AND inc_fechafinal IS NULL ";

		extract($_REQUEST);

		if(isset($enviar)){
		 	if($tr_id>0){
		 		$disponible .= " AND rec_tipoid='$tr_id '";
		 		$ocupado .= " AND rec_tipoid='$tr_id' ";
		 		$incidencia .=" AND rec_tipoid= '$tr_id'";
		 	}
		}

		$tipos = mysqli_query($conexion, $sql);
		$recursos = mysqli_query($conexion, $disponible);
		$recursos1 = mysqli_query($conexion, $ocupado);
		$recursos2 = mysqli_query($conexion, $incidencia);

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="../css/recursos.css">
	<title>Recursos</title>
	<script type="text/javascript">
		function destroy(){
			var respuesta = confirm("¿Está seguro que desea liberar la incidencia?");
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
			<li class="li"><a href="#">Administrar recursos</a></li>
			<li class="li"><a href="administrador_reserva.php">Administrar reservas</a></li>
		</ul>
	</nav>
<div class="container">
	<?php
	if(mysqli_num_rows($tipos)>0){
		?>
	<form action="administrador_recursos.php" method="get" class="formtipo">
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
	<br/><br/>
	<h1>Recursos Disponibles</h1>
	<br/>
	<?php
		}
		if(mysqli_num_rows($recursos)>0){
			
								while($recurso	=	mysqli_fetch_array($recursos)){
									echo "<div class='content_rec'>";
										//echo $fila[0]
									echo "<table border>";
										echo "<tr>";
											echo "<td colspan='2'>" . $recurso['rec_nombre'] . "</td>";
										echo "</tr>";
										echo "<tr>";
											echo "<td rowspan='3'><img class='img_recu' src='../img/recursos/".$recurso['rec_foto']."' width='100'></td>";
											echo "<td>".$recurso['rec_descripcion']."</td>";
										echo "</tr>";
										echo "<tr>";
											echo "<td>Estado: " .$recurso['rec_estado']. "</td>";
										echo "</tr>";
											
														
									echo "</table>";
									echo "</div>";
									echo "</br>";
	 

								}

			} else {
				echo "No hay recursos disponibles";
			}

		?>

	<br/><br/>
	<h1>Recursos en uso</h1>
	<br/>
	<?php
		if(mysqli_num_rows($recursos1)>0){
			
								while($recurso1	=	mysqli_fetch_array($recursos1)){
									echo "<div class='content_rec'>";
										//echo $fila[0]
									echo "<table border>";
										echo "<tr>";
											echo "<td colspan='2'>" . $recurso1['rec_nombre'] . "</td>";
										echo "</tr>";
										echo "<tr>";
											echo "<td rowspan='3'><img class='img_recu' src='../img/recursos/".$recurso1['rec_foto']."' width='100'></td>";
											echo "<td>".$recurso1['rec_descripcion']."</td>";
										echo "</tr>";
										echo "<tr>";
											echo "<td>Estado: " .$recurso1['rec_estado']. "</td>";
										echo "</tr>";
										
											//echo $fila[2];
														
									echo "</table>";
									echo "</div>";
									echo "</br>";
	 

								}

			} else {
				echo "No hay recursos disponibles";
			}

		?>

		<br/><br/>
	<h1>Recursos con incidencia</h1>
	<br/>
	<?php
		if(mysqli_num_rows($recursos2)>0){
			
								while($recurso2	=	mysqli_fetch_array($recursos2)){
									echo "<div class='content_rec'>";
										//echo $fila[0]
									echo "<table border>";
										echo "<tr>";
											echo "<td colspan='2'>" . $recurso2['rec_nombre'] . "</td>";
										echo "</tr>";
										echo "<tr>";
											echo "<td rowspan='3'><img class='img_recu' src='../img/recursos/".$recurso2['rec_foto']."' width='100'></td>";
											echo "<td>".$recurso2['rec_descripcion']."</td>";
										echo "</tr>";
										echo "<tr>";
											echo "<td>Estado: " .$recurso2['rec_estado']. "</td>";
										echo "</tr>";
										echo "<tr>";
											echo "<td colspan='1'>Tipo incidencia: " .$recurso2['ti_nombre']. "</td>";
										echo "</tr>";
										if ($recurso2['inc_comentario']==""){

										} else {
											echo "<tr>";
											echo "<td colspan='2'>Comentario: " .$recurso2['inc_comentario']. "</td>";
											echo "</tr>";
										}
										echo "<tr>";
												echo "<td colspan='2'> <a class='free_recu' href='administrador_inc.proc.php?rec_id=" .$recurso2['rec_id']. " 'onclick='return destroy();'>LIBERAR INCIDENCIA</a></td>";
											echo "</tr>";
										
											//echo $fila[2];
														
									echo "</table>";
									echo "</div>";
									echo "</br>";
	 

								}

			} else {
				echo "No hay recursos disponibles";
			}

		?>
</div>
</body>
</html>
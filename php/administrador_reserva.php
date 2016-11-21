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
		extract($_REQUEST);
		//session_start();
		$mysqli = new mysqli("localhost", "root", "", "bd_proyecto2");
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
	
		$usuario_actual = $_SESSION['usu_id'];
		$sql = "SELECT * FROM tbl_tiporecurso ORDER BY tr_id";
		$usuario_sql = "SELECT * FROM tbl_usuario ORDER BY usu_id";
		$encurso =	" SELECT * FROM tbl_reserva INNER JOIN tbl_recurso ON tbl_recurso.rec_id = tbl_reserva.res_recursoid INNER JOIN tbl_usuario ON tbl_usuario.usu_id = tbl_reserva.res_usuarioid WHERE res_fechafinal IS NULL";
		$finaliza =	" SELECT * FROM tbl_reserva INNER JOIN tbl_recurso ON tbl_recurso.rec_id = tbl_reserva.res_recursoid INNER JOIN tbl_usuario ON tbl_usuario.usu_id = tbl_reserva.res_usuarioid WHERE res_fechafinal IS NOT NULL ";
		if(isset($enviar)){
		 	if($tr_id>0){
		 		$encurso .= " AND rec_tipoid='$tr_id' ";
		 		$finaliza .= " AND rec_tipoid='$tr_id' ";
		 	}
		 	if ($usu_id>0){
		 		$encurso .= " AND usu_id='$usu_id' ";
		 		$finaliza .= " AND usu_id='$usu_id' ";
		 	}
		}
		$reservas = mysqli_query($conexion, $encurso);
		$reservas1 = mysqli_query($conexion, $finaliza);
		$tipos = mysqli_query($conexion, $sql);
		$usuarios = mysqli_query($conexion, $usuario_sql);
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="../css/recursos.css">
	<title>Reservas</title>
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
		<li class="li"><a href="administrador_recursos.php">Administrar recursos</a></li>
		<li class="li"><a href="#">Adiministrar reservas</a></li>
	</ul>
</nav>
<div class="container">
	<?php
	if(mysqli_num_rows($tipos)>0){
		?>
	<form action="administrador_reserva.php" method="get" class="formtipo">
	Tipo de recurso:
		<select name="tr_id">
			<option value="0">-- Elegir tipo --</option>
			<?php
					while($tipo=mysqli_fetch_array($tipos)){
						echo "<option value=" . $tipo['tr_id'] . ">" . $tipo['tr_nombre'] . "</option>";
					}
				?>
		</select>
		Usuario:
		<select name="usu_id">
			<option value="0">-- Elegir usuario --</option>
			<?php
					while($usuario=mysqli_fetch_array($usuarios)){
						if ($usuario['usu_nickname']=="administrador") {
							
						}else{
						echo "<option value=" . $usuario['usu_id'] . ">" . $usuario['usu_nickname'] . "</option>";
					}
				}
			}
				?>
		</select>

		<input type="submit" name="enviar" value="Filtrar">
	</form>
	<h1>Reservas en curso</h1>
	<br/>
	<?php
		if (mysqli_num_rows($reservas)>0) { 
			echo "Número de reservas: " . mysqli_num_rows($reservas) . "<br/><br/>";
							while($reserva	=	mysqli_fetch_array($reservas)){
								echo "<div class='content_rec'>";
									echo "<table border>";
										echo "<tr>";
											echo "<td colspan='2'>" .$reserva['rec_nombre']. "</td>";
										echo "</tr>";
										echo "<tr>";
											echo "<td rowspan='3'><img class='img_recu'  width='100' src='../img/recursos/".$reserva['rec_foto']."'></td>";
											echo "<td>".$reserva['rec_descripcion']."</td>";
										echo "</tr>";
										echo "<tr>";
											echo "<td>Fecha de inicio: " .$reserva['res_fechainicio']. "</td>";
										echo "</tr>";
										echo "<tr>";
											echo "<td>Usuario: " .$reserva['usu_nickname']. "</td>";
										echo "</tr>";
									echo "</table>";
									echo "</div>";
									echo "<br/>";
								}
						} else {
							echo "No hay reservas en curso";
						}
		?>

		<h1>Reservas finalizadas</h1>
	<br/>
	<?php
		if (mysqli_num_rows($reservas1)>0) { 
			echo "Número de reservas: " . mysqli_num_rows($reservas1) . "<br/><br/>";
							while($reserva1	=	mysqli_fetch_array($reservas1)){
								echo "<div class='content_rec'>";
									echo "<table border>";
										echo "<tr>";
											echo "<td colspan='2'>" .$reserva1['rec_nombre']. "</td>";
										echo "</tr>";
										echo "<tr>";
											echo "<td rowspan='3'><img class='img_recu' width='100' src='../img/recursos/".$reserva1['rec_foto']."'></td>";
											echo "<td>".$reserva1['rec_descripcion']."</td>";
										echo "</tr>";
										echo "<tr>";
											echo "<td>Fecha de inicio: " .$reserva1['res_fechainicio']. "</td>";
										echo "</tr>";
										echo "<tr>";
											echo "<td>Fecha devolucion: " .$reserva1['res_fechafinal']. "</td>";
										echo "</tr>";
										echo "<tr>";
											echo "<td>Usuario: " .$reserva1['usu_nickname']. "</td>";
										echo "</tr>";
									echo "</table>";
									echo "</div>";
									echo "<br/>";
								}
						} else {
							echo "No se ha realizado ninguna reserva";
						}
		?>
</div>
</body>
</html>
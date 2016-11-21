<?php
		include 'conexion.php';
		session_start();

		if(!isset($_SESSION["usu_id"])) {
			header("location:../index.php?nolog=2");
		}
	
		$hoy= date('Y-m-d H:i:s');

		$sql_iniciar_reserva="UPDATE tbl_reserva SET res_finalizada='ocupado' WHERE res_fechainicio<'$hoy' AND res_fechafinal>'$hoy' AND res_finalizada='reservado'";
		mysqli_query($conexion, $sql_iniciar_reserva);

		$sql_finalizar_reserva="UPDATE tbl_reserva SET res_finalizada='finalizada' WHERE res_fechafinal<'$hoy' AND res_finalizada='ocupado'";
		mysqli_query($conexion, $sql_finalizar_reserva);


		$hora= date('H');
		$hora_reserva_fi = $hora + 1; 

		$fecha_actual = date('d-m-Y');


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
			

		extract($_REQUEST);

		$sql = "SELECT * FROM tbl_tiporecurso ORDER BY tr_id";

		$recurso =	" SELECT * FROM tbl_recurso INNER JOIN tbl_tiporecurso ON tbl_tiporecurso.tr_id = tbl_recurso.rec_tipoid ";

		if (isset($enviar)){
			$recurso .= " AND  rec_tipoid='$tr_id' ";
		}



		$tipos = mysqli_query($conexion, $sql);
		$recursos = mysqli_query($conexion, $recurso);


		//$verificar_reserva = "SELECT HOUR(res_fechainicio), HOUR(res_fechafinal), res_recursoid  FROM tbl_reserva WHERE res_finalizada='ocupado' OR res_finalizada='reservado'";

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="../css/recursos.css">
	<title>Recursos</title>
	<script type="text/javascript">
		function destroy(){
			var respuesta = confirm("¿Está seguro que desea reservar este recurso?");
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

		function validar(formulario){
			var today = new Date();
			var dd = today.getDate();
			var mm = today.getMonth()+1;
			var yyyy = today.getFullYear();

			var hoy = yyyy+"-"+mm+"-"+dd; 

			if (formulario.hora_ini.value>=formulario.hora_fi.value){
				alert('La  hora de inicio no puede ser superior o igual a la hora final')
				return false;
			}

			if (formulario.fecha.value=="" || formulario.fecha.value==hoy) {
				alert('Debes introducir una fecha correcta')

				return false;
			}
		
			return true;
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
			<li class="li"><a href="#">Recursos</a></li>
			<li class="li"><a href="misrecursos.php">Mis recursos</a></li>
			<li class="li"><a href="historial_recursos.php">Historial de recursos</a></li>
		</ul>
	</nav>
<div class="container">
	
	<?php
	if(isset($res)){
		if ($res==1) {
			echo "<span style='color:green'>La reserva se ha realizado correctamente</span>";
		} else {
			echo "<span style='color:red'>El recurso ya esta reservado ese dia y hora</span>";
		}
	}
	if(mysqli_num_rows($tipos)>0){
		?>
	<form action="recursos.php" method="get" class="formtipo">
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
	<h1>Recursos</h1>
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
		echo "<td>Realizar reserva <br/><br/><form action='recursos.proc.php'  method='GET' name='form_reserva' onsubmit='return validar(this);'>";
		echo "Fecha reserva:<input type='date' name='fecha'/><br/><br/>";
			echo "Hora inicial:<select name='hora_ini'>";
				for ($i=$hora; $i <=20 ; ++$i) {
				echo "<option value='$i'>$i:00</option><br/>";
				}
			echo "</select><br/><br/>";
		echo "Hora final:<select name='hora_fi'>";
			for ($j=$hora_reserva_fi; $j <=21 ; ++$j) {
				echo "<option value='$j'>$j:00</option><br/>";
			}
			echo "</select><br/><br/><input type='hidden' name='rec_id' value='$recurso[rec_id]'/>";
			echo "<input type='submit' name='enviar' value='Reservar' size='5'/></form></td></tr>";
		echo "</table>";
				echo "</div>";
				echo "</br>";
			 

			}

	} else {
				echo "No hay recursos disponibles";
	}

		?>
</body>
</html>
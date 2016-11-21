<?php 
    session_start();
        if(!isset($_SESSION["usu_id"])) {
            header("location:../index.php?nolog=2");
        }
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<link rel="stylesheet" type="text/css" href="../css/recursos.css">
		<title>Recursos</title>
		<script type="text/javascript">
		function validar() {
			alert("Su informe se ha enviado correctamente");
		}

		</script>
	</head>
	<body>
	<?php
	extract($_REQUEST);

	//session_start();
	$mysqli = new mysqli("localhost", "root", "", "bd_proyecto2");
	$acentos = mysqli_query($mysqli, "SET NAMES 'utf8'");

	$incidencia= "SELECT * FROM tbl_tipoinc";

		$incidencias=mysqli_query($mysqli, $incidencia);
			
	?>

        <h2>Reportar incidencia</h2>
      <div>

  <form  method='get' action="incidencia.proc.php" onsubmit='validar();'>
<?php
        if (mysqli_num_rows($incidencias)>0){
        	?>
        	Tipo incidencia:<br/>
        	<?php
        	while ($incidencia	=	mysqli_fetch_array($incidencias)) {
        		if ($incidencia['ti_id']==1){
        			echo "<input type='radio' name='incidencia' value='" .$incidencia['ti_id']. "' checked> " .$incidencia['ti_nombre']. "<br/>";
        		} else {
        		echo "<input type='radio' name='incidencia' value='" .$incidencia['ti_id']. "'> " .$incidencia['ti_nombre']. "<br/>";
        	}
        	}
        } 
        	?>
        	<br/>
        	Comentario: <br/>
        	<textarea cols="50" rows="3" name="comentario" maxlength="200"></textarea>
        	<br/><br/>
        	<input type="submit" name="enviar" value="Enviar">
        	<div class="invisible">
        	<?php
        	echo "<input type='radio' name='rec_id' value='$rec_id' checked>";
        	?>
        	</div>
        </form>
      </div>
	</body>
</html>
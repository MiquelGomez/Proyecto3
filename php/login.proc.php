<?php
extract($_POST);

//Realizamos la conexión a la BD
$mysqli = new mysqli("localhost", "root", "", "bd_proyecto2");
if ($mysqli->connect_errno) {
    echo "Fallo al conectar a MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
	}
//Consulta de busqueda del usuario
	$con =	"SELECT * FROM `tbl_usuario` WHERE `usu_nickname` = '". $name ."' AND `usu_contrasena` = '" . $pass . "'";	
	//Lanzamos la consulta a la BD
	$result	=	mysqli_query($mysqli,$con);
	//Contamos los resultados que nos devuelve
	$total  = mysqli_num_rows($result); 
	//Ponemos el condicional según el nombre de registros que nos devuelva
	while ($fila = mysqli_fetch_array($result)) 
		{
	if($total>=1 && $fila['usu_habilitado']){
		//Iniciamos sessión para las variables de sesion
		session_start();
			//Asignamos la variable de session "usu_id" al ID del usuario
			$_SESSION['usu_id']	=	$fila['usu_id'];
			//Redireccionamos
			if ($_SESSION['usu_id']!=5) {
			header("location:recursos.php");	
			}else{
			header("location:administrador_recursos.php");
			}
		}
	//Si no nos devuelve registros significa que el usuario o contraseña han sido incorrectos.
		else{		
			header("location: ../index.php?nolog=1");
		}
	}
?>
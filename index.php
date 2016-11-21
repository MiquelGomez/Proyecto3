<?php
session_start();
include '/php/conexion.php';

if (isset($_SESSION['usu_id'])){
	$sql = "SELECT * FROM tbl_usuario WHERE usu_id='" .$_SESSION['usu_id']."'";
	$usuarios	=	mysqli_query($conexion,$sql);
	while ($usuario = mysqli_fetch_array($usuarios)) {
		if ($usuario['usu_tipousuid']==1){
			header('location:./php/administrador_recursos.php');
		} else {
			header('location:./php/recursos.php');
		}
	}
} else {
?>
<!DOCTYPE html>
<meta charset="utf-8">
<html>
<head>
	<title>MAMProyect</title>
	<link rel="stylesheet" type="text/css" href="./css/style.css">
	    <script>
    //Funcion para validar  el formulario 
    function validar()
    {
      var error ="";

      if(document.getElementById("name").value=="")
      {
        error+="Error, el nombre no puede estar vacio \n";
        document.getElementById("name").style.borderColor="red";
      }
       if(document.getElementById("pass").value=="")
      {
        error+="Error, la contraseña no puede estar vacio \n";
        document.getElementById("pass").style.borderColor="red";
      }
      if(error!="")
      {
        alert(error);
        return false;
      }
      else{
        return true;
      }
    }
    //END VALIDAR()

    </script>
</head>
<body>
	<div class="login_logo">
		<img  class=" login_image" src="img/logo.jpg">
	</div>
		
	<?php
	extract($_GET);
		//En caso que exista la variable nolog, significa que ha habido un error de login o el usuario ha intentado entrar en una página directamente
		if (isset($nolog)) 
		{
			switch ($nolog) {
				case '1':
					?>
					<div class="login_failed">
						<p class="user_failed">
							Upps! parece que tu usuario y/o contraseña no son correctos
						</p>
					</div>
				<?php
					break;
				case '2':
				?>
				<div class="login_failed">
						<p class="user_failed">
							¡Necesita estar registrado!
						</p>
					</div>
				<?php
				default:
					//En caso que el usuario envie cualquier variable por URL, que salga del Switch sin que haga nada
					break;
			}
		}
	?>
	<form name="login" action="php/login.proc.php" method="POST" onsubmit="return validar();" >
		<div class="login">
			<p class="nickname">
				Nombre del trabajador:<br/>
				<input type="text" class="name" name="name" id="name" maxlength="15" />
			</p>
			<p class="password">
				Contraseña: <br/>
				<input type="password" class="pass" name="pass" id="pass" />
			</p>
			<input class="send" id="send" type="submit" value="Acceder" />
		</div>
	</form>
<?php
	}
?>
</body>
</html>
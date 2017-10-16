<?php
require_once("../clases/BD.php");
$msgerror="";

if(isset($_POST["okRestaurante"])){
	$cif=$_POST["CIF"];
	$pass=$_POST["passR"];
	if(BD::combrobarCIF($cif,$pass)){
		session_start();
		$_SESSION["cif"]=$cif;
		header("Location: dashRestaurante.php?cif=$cif");
	}
	else{
		$msgerror="El CIF o la contraseña son incorrectos.";
	}
}
if(isset($_POST["okCliente"])){
	$usuario=$_POST["usuario"];
	$pass=$_POST["passC"];
	if(BD::comprobarCliente($usuario,$pass)){
		session_start();
		$_SESSION["cliente"]=$usuario;
		header("Location: dashUsuario.php?user=$usuario");
	}
	else{
		$msgerror="El usuario o la contraseña son incorrectos.";
	}
}
$registro=$_GET["reg"];
?>


<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Airfood/Registro</title>
<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
<script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
<link rel="stylesheet" type="text/css" href="../estilo/general.css">
<link rel="stylesheet" type="text/css" href="../estilo/registro.css">
<script src="../js/funcion.js"></script>
<script src="../js/validacion.js"></script>
</head>

<body>

	<!--  Navbar  -->
	<nav class="navbar"> <!--navbar-fixed-top-->
	  	<div class="container-fluid">
			<div class="navbar-header">
  				<img src="../img/Logo.png" id="logo">
  				<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
					<span class="glyphicon glyphicon-menu-hamburger"></span>                      
			  	</button>
	  			<!--<a class="navbar-brand" href="#"><img src="./img/Logo.png"></a>-->
			</div>
			<div class="collapse navbar-collapse" id="myNavbar">
				<ul class="nav navbar-nav navbar-right">
					<li><a href="#" data-toggle="modal" data-target="#accessCliente">Eres el cliente</a></li>
					<li><a href="#" data-toggle="modal" data-target="#accessRestaurante">Acceso al restaurante</a></li>
				</ul>
			</div>
	  	</div>
	</nav>
	
	<!--  Panel de registro  -->
	<div class="container">
		<div class="panel panel-default" id="panel-form">
			<div class="panel-heading">
				<h1>Registrar <?= $registro?></h1>
				
				<span id="error"></span>
			</div>
			<div class="panel-body">
				<form action="../includes/registrar.php" method="POST">
					<input type="hidden" name="registro" value="<?=$registro?>">
					<div class="form-group" id="slider-container">
						<?php if($registro=="cliente"){ ?>
							<input type="hidden" id="registro" value="<?=$registro?>">
							<div id="slider1" class="slider-box">
								<table class="tablaRegister">
									<tr>
										<td>
											Usuario: <input type="text" class="form-control" name="usuario" id="usuario" placeholder="Escribe usuario">
										</td>
										<td>
											Correo electrónico:<input type="email" class="form-control" name="email" id="email" placeholder="Escribe tu email">
										</td>
									</tr>
									<tr>
										<td>
											Contraseña: <input type="password" class="form-control" name="pass" id="pass" placeholder="Escribe la contraseña">
											<div id="balloon">
												<span id="longitud">Debería tener 8 carácteres como mínimoo</span><br>
												<span id="letrasMin">Al menos debería tener una letra</span><br>
												<span id="letrasMay">Al menos debería tener una letra en mayúsculas</span><br>
												<span id="digitos">Al menos debería tener un número</span>
											</div>
										</td>
										<td>
											Repite la contraseña: <input type="password" class="form-control" name="pass2" id="pass2" placeholder="Repite la contraseña">
										</td>
									</tr>
								</table>
								<input type="button" class="next btn btn-default" id="unoRegister" value="Siguiente">
							</div>
							<div id="slider2" class="slider-box">
								<table class="tablaRegister">
									<tr>
										<td>
											Nombre:<input type="text" class="form-control" name="nombre" id="nombre" placeholder="Escribe tu nombre">
										</td>
										<td colspan="3">
											Apellidos:<input type="text" class="form-control" name="apellidos" id="apellidos" placeholder="Escribe tu nombre">
										</td>
										
									</tr>
									<tr>
										<?php include("../includes/calendario.php")?>
									</tr>
								</table>
								<input type="button" class="next btn btn-default" id="dosRegister" value="Siguiente"><input type="button" class="back btn btn-default" value="Atrás">
							</div>
							<div id="slider3" class="slider-box">
								<table class="tablaRegister">
									<tr>
										<td colspan="2">
											Localidad:<input type="text" class="form-control" name="localidad" id="localidad" placeholder="Escribe tu localidad">
											<div class="eligeLocalidad"></div>
										</td>
										<td colspan="2">
											Telefono:<input type="text" class="form-control" id="intTelefono" name="intTelefono" placeholder="Escribe tu número de telefono">
										</td>
									</tr>
									<tr>
										<td>Dirección:<input type="text" class="form-control" name="direccion" id="direccion" placeholder="Escribe tu dirección"></td>
										<td>Numero:<input type="number" class="form-control" name="numero" id="numero" placeholder="Nº"></td>
										<td>Piso:<input type="text" class="form-control" name="piso" id="piso" placeholder="Piso"></td>
										<td>Código Postal:<input type="text" class="form-control" name="codPostal" id="codPostal" placeholder="CP"></td>
									</tr>
								</table>
								<input type="button" class="btn btn-default" id="enviar" name="enviar" value="Registrar ya!"><input type="button" class="back btn btn-default" value="Atrás"> 
							</div>
							
						<?php }
						else{ ?>
							<input type="hidden" id="registro" value="<?=$registro?>">
							<div id="slider1" class="slider-box">
								<table class="tablaRegister">
									<tr>
										<td>
											CIF: <input type="text" class="form-control" name="cif" id="cif" placeholder="Escribe tu CIF">
										</td>
										<td>
											Correo electrónico:<input type="email" class="form-control" name="email" id="email" placeholder="Escribe tu email">
										</td>
									</tr>
									<tr>
										<td>
											Contraseña: <input type="password" class="form-control" name="pass" id="pass" placeholder="Escribe la contraseña">
											<div id="balloon">
												<span id="longitud">Debería tener 8 carácteres como mínimoo</span><br>
												<span id="letrasMin">Al menos debería tener una letra</span><br>
												<span id="letrasMay">Al menos debería tener una letra en mayúsculas</span><br>
												<span id="digitos">Al menos debería tener un número</span>
											</div>
										</td>
										<td>
											Repite la contraseña: <input type="password" class="form-control" name="pass2" id="pass2" placeholder="Repite la contraseña">
										</td>
									</tr>
								</table>
								<input type="button" class="next btn btn-default" id="unoRegister" value="Siguiente">
							</div>
							<div id="slider2" class="slider-box">
								<table class="tablaRegister">
									<tr>
										<td colspan=2>
											<div class="row">
												<div class="col-xs-4">
													Nombre de tu restaurante:<input type="text" class="form-control" name="restaurante" id="restaurante" placeholder="Escribe el nombre de tu restaurante">
												</div>
												<div class="col-xs-4">
													Categoría:<?php include("../includes/categoria.php");?>
												</div>
												<div class="col-xs-4">
													Telefono:<input type="text" class="form-control" id="intTelefono" name="intTelefono" placeholder="Escribe tu número de telefono">
												</div>
											</div>
										</td>
									</tr>
									<tr>
										<td colspan="4">
											<div class="row">
												<div class="col-xs-4">
													Localidad:<input type="text" class="form-control" name="localidad" id="localidad" placeholder="Escribe tu localidad">
													<div class="eligeLocalidad"></div>
												</div>
												<div class="col-xs-4">
													Dirección:<input type="text" class="form-control" name="direccion" id="direccion" placeholder="Escribe tu dirección">
												</div>
												<div class="col-xs-2">
													Numero:<input type="number" class="form-control" name="numero" id="numero" placeholder="Nº">
												</div>
												<div class="col-xs-2">
													CP:<input type="text" class="form-control" name="codPostal" id="codPostal" placeholder="CP">
												</div>
											</div>
										</td>
									</tr>
								</table>
								<input type="button" class="btn btn-default" id="enviar" name="enviar" value="Registrar ya!"><input type="button" class="back btn btn-default" value="Atrás"> 
							</div>
						<?php }?>
						<!--<input type="submit" class="btn btn-danger" name="enviar" id="enviar" value="Registrar ya!"> -->
					</div>
				</form>
			</div>
			
		</div>
	</div>
	
	<!-- Ventana emergente -->
	<div id="accessCliente" class="modal fade" role="dialog">
  		<div class="modal-dialog">

			<!-- Modal content-->
			<div class="modal-content">
      			<div class="modal-header">
        			<button type="button" class="close" data-dismiss="modal">&times;</button>
        			<h4 class="modal-title">Acceso al Cliente</h4><?=$msgerror?>
      			</div>
      			<div class="modal-body">
        			<form action="<?=$_SERVER['PHP_SELF']."?reg=".$registro?>" method="POST">
        				<div class="input-group">
        					<span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
							<input type="text" class="form-control acceso" name="usuario" placeholder="Usuario">
						</div>
						<div class="input-group">
							<span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
							<input type="password" class="form-control acceso" name="passC" placeholder="Contraseña">
						</div>
						
       					<input type="submit" class="btn btEnviar" name="okCliente" value="Entrar">
        			</form>
      			</div>
      			<div class="modal-footer">
       				No tienes cuenta. <a href="registro.php?reg=cliente">Registra aquí </a>
      			</div>
    		</div>

  		</div>
	</div>
	<div id="accessRestaurante" class="modal fade" role="dialog">
  		<div class="modal-dialog">

			<!-- Modal content-->
			<div class="modal-content">
      			<div class="modal-header">
        			<button type="button" class="close" data-dismiss="modal">&times;</button>
        			<h4 class="modal-title">Acceso al Restaurante</h4><?=$msgerror?>
      			</div>
      			<div class="modal-body">
        			<form action="<?=$_SERVER['PHP_SELF']."?reg=".$registro?>" method="POST">
        				<div class="input-group">
        					<span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
							<input type="text" class="form-control acceso" name="CIF" placeholder="CIF">
						</div>
						<div class="input-group">
							<span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
							<input type="password" class="form-control acceso" name="passR" placeholder="Contraseña">
						</div>
       					<input type="submit" class="btn btEnviar" name="okRestaurante" value="Entrar">
        			</form>
      			</div>
      			<div class="modal-footer">
       				No eres socío del restaurante. <a href="registro.php?reg=restaurante">Registra aquí </a>
      			</div>
    		</div>

  		</div>
	</div>
</body>
</html>
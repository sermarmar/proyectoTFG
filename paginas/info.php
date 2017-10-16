<?php
require_once("../clases/BD.php");
require_once("../clases/Restaurantes.php");

$nombre=$_GET["nombre"];

session_start();
$cliente=$_SESSION["cliente"]??"";

try{
	$tblcliente=BD::consulta("SELECT * FROM cliente
	WHERE Usuario='$cliente'");
	$filaCliente=$tblcliente->fetch();
}
catch(Exception $e){
	echo $e->getMessage();
}

$restaurante=Restaurante::buscarRestaurantes($nombre);

?>


<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Airfood</title>
<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
<!--<link type="text/css" rel="stylesheet" href="../estilo/bootstrap.min.css">-->
<script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
<!--<script src="../js/bootstrap.min.js"></script>-->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
<link rel="stylesheet" type="text/css" href="../estilo/general.css">
<link rel="stylesheet" type="text/css" href="../estilo/dashboard.css">
<link rel="stylesheet" type="text/css" href="../estilo/info.css">
<script src="../js/funcion.js"></script>
<script src="../js/pedidos.js"></script>
</head>

<body>
	<!--  Navbar  -->
	<nav class="navbar navbar-fixed-top"> <!--navbar-fixed-top-->
	  	<div class="container-fluid">
			<div class="navbar-header">
  				<h3 id="bienvenido">Bienvenido <?=$filaCliente[2]." ".$filaCliente[3]?></h3>
  				<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
					<span class="glyphicon glyphicon-menu-hamburger"></span>                      
			  	</button>
	  			<!--<a class="navbar-brand" href="#"><img src="./img/Logo.png"></a>-->
			</div>
			<div class="collapse navbar-collapse" id="myNavbar">
				<ul class="nav navbar-nav navbar-right">
					<li><a href="../includes/logout.php">Cerrar sesión</a></li>
				</ul>
			</div>
	  	</div>
	</nav>
	<div class="container" id="info">
		<div class="panel panel-default">
			<div class="panel-body">
				<div class="row">
					<div class="col-sm-4">
						<img src="<?=$restaurante->getFoto()?>" width="300" height="300">
					</div>
					<div class="col-sm-8">
						<div class="row">
							<div class="col-sm-12">
								<h1 id="titulo">
									<?=$restaurante->getRestaurante()?>
									<span id="estrellas">
										<?php 
										$puntos=5;
										for($j=1;$j<=$restaurante->getPopularidad();$j++){
											echo "<span class='glyphicon glyphicon-star'></span> ";
											$puntos--;
										}
										for($j=1;$j<=$puntos;$j++){
											echo "<span class='glyphicon glyphicon-star-empty'></span> ";
										}
										?>
									</span>
								</h1>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-5">
								Dirección: <?=$restaurante->getDireccion()?> nº <?=$restaurante->getNumero()?>
							</div>
							<div class="col-sm-7">
								Telefono: <?=$restaurante->getTelefono()?>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-12">
								Email: <?=$restaurante->getEmail()?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</body>
</html>
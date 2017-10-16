<?php
require_once("clases/BD.php");
require_once("clases/Restaurantes.php");

$msgerror="";

if(isset($_POST["okRestaurante"])){
	$cif=$_POST["CIF"];
	$pass=$_POST["passR"];
	if(BD::combrobarCIF($cif,$pass)){
		session_start();
		$_SESSION["cif"]=$cif;
		header("Location: paginas/dashRestaurante.php?cif=$cif");
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
		header("Location: paginas/dashUsuario.php?user=$usuario");
	}
	else{
		$msgerror="El usuario o la contraseña son incorrectos.";
	}
}

?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Airfood</title>
<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
<script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
<link rel="stylesheet" type="text/css" href="estilo/general.css">
<script src="./js/funcion.js"></script>
</head>
<body>

	<!--  Navbar  -->
	<nav class="navbar"> <!--navbar-fixed-top-->
	  	<div class="container-fluid">
			<div class="navbar-header">
  				<img src="./img/Logo.png" id="logo">
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
	
	
	<!--  Carousel-Slider  -->
	<div id="myCarousel" class="carousel slide" data-ride="carousel">
		<!-- Indicators -->
		<ol class="carousel-indicators">
			<li data-target="#myCarousel" data-slide-to="0" class="active"></li>
			<li data-target="#myCarousel" data-slide-to="1"></li>
			<li data-target="#myCarousel" data-slide-to="2"></li>
			<li data-target="#myCarousel" data-slide-to="3"></li>
		</ol>

		<!-- Wrapper for slides -->
		<div class="carousel-inner" role="listbox">
			<div class="item active" id="img1">
				<img src="img/portada/FamilyEatingDinner.jpeg">
			</div>

			<div class="item" id="img2">
				<img src="img/portada/imagen-sin-titulo.jpg">
			</div>

			<div class="item" id="img3">
				<img src="img/portada/outdoor_familydinner.jpg">
			</div>

			<div class="item" id="img4">
				<img src="img/portada/Repartidor-de-pizzas.jpg">
			</div>
		</div>
		<!-- Left and right controls -->
		<a class="left carousel-control" href="#myCarousel" role="button" data-slide="prev">
			<span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
			<span class="sr-only">Previous</span>
		</a>
		<a class="right carousel-control" href="#myCarousel" role="button" data-slide="next">
			<span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
			<span class="sr-only">Next</span>
		</a>
	</div>
	
	<!--Carousel de los restaurantes-->
	<div id="panelRest" class="carousel slide" data-ride="carousel">
		<div class="carousel-inner">
			<?php for($i=0;$i<=6;$i+=3){
				$active=($i==0)?"active":"";?>	
				<div class="item <?=$active?> centrado">
				 	<div class="container">
					<?php 
						$restaurantes=BD::consulta("SELECT * FROM restaurante LIMIT $i, 3");
						while($filaRestaurante=$restaurantes->fetch()){
							$fotos=BD::consulta("SELECT * FROM fotos 
							INNER JOIN restaurante ON restaurante.CIF=fotos.strCIF
							WHERE strCIF='".$filaRestaurante["CIF"]."'");
							$filaFotos=$fotos->fetch();
							$imagen=substr($filaFotos["imagen"],3);
					?>
							<div class='col-sm-4 restaurante'>
								<img src="<?=$imagen?>" width="100%" class="flip-1">
								<div class="flip-2">
									<h4><?=$filaFotos["Restaurante"]?></h4>
								</div>
							</div>
						<?php }
					?></div>
				</div>
			<?php }?>
		</div>
	</div>
	
	<div class="jumbotron" id="fil1">
		<h1 class="slides">Un cliente satisfecho es la mejor estrategia de negocio</h1>
		<h2 class="slides">Disfruta, come y bebe que la vida es breve.</h2>
	</div>
	
	<!--  Presentación  -->
	<div class="container-fluid present">
		<div class="row cajaIcon">
			<div class="col-sm-4 triCaja">
				<img src="./img/iconos/entrega.png" class="iconos"><br>
				<p class="pColor">Quedate en casa, podemos entregarlo a domicilio.</p>
			</div>
			<div class="col-sm-4 triCaja">
				<img src="./img/iconos/charlar.png" class="iconos">
				<p class="pColor">Opiniones de los clientes</p>
			</div>
			<div class="col-sm-4 triCaja">
				<img src="./img/iconos/hamburguesa.png" class="iconos">
				<p class="pColor">Miles de platos diferentes y exquisitos en cada restaurante y bares.</p>
			</div>
		</div>
		<div class="row cajaIcon">
			<div class="col-sm-4 triCaja">
				<img src="./img/iconos/reloj.png" class="iconos">
				<p class="pColor">Tiempo real de los pedidos para tener a los clientes satisfechos.</p>
			</div>
			<div class="col-sm-4 triCaja">
				<img src="./img/iconos/celiacos.png" class="iconos">
				<p class="pColor">Control de alergías y para celíacos.</p>
			</div>
			<div class="col-sm-4 triCaja">
				<img src="./img/iconos/cartera.png" class="iconos">
				<p class="pColor">Facilidad pagar en efectivo, con tarjeta o PayPal</p>
			</div>
		</div>
	</div>
	
	
	<div class="jumbotron" id="fil2">
		<h1 class="slides">La mejor red social <i>es una mesa</i> rodeada de las personas que tú más quieres.</h1>
		<h2 class="slides">Si un día sientes un vacío... come es hambre</h2>
	</div>
	
	<!--  Footer(Pie)  -->
	<footer>
		<h3>Todos los derechos reservados. <a href="mailto:smm_101995@outlook.es">Sergio Martín Martín.</a></h3>
	</footer>
	
	
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
        			<form action="<?=$_SERVER['PHP_SELF']?>" method="POST">
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
       				No tienes cuenta. <a href="./paginas/registro.php?reg=cliente">Registra aquí </a>
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
        			<form action="<?=$_SERVER['PHP_SELF']?>" method="POST">
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
       				No eres socío del restaurante. <a href="./paginas/registro.php?reg=restaurante">Registra aquí </a>
      			</div>
    		</div>

  		</div>
	</div>
	
</body>
</html>
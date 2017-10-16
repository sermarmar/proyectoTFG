<?php
require_once("../clases/BD.php");
require_once("../clases/Pedidos.php");

session_start();
$cif=$_SESSION["cif"]??"";

if(empty($cif)){
	header("Location: ../index.php");
}
$sql="SELECT * FROM pedido
WHERE Estado IN ('Pedido','Retrasado') ORDER BY intIDPedido DESC";

try{
	$consultaOrder=BD::consulta($sql);
	$consultaAnulacion=BD::consulta($sql);
	while($filaOrder=$consultaAnulacion->fetch()){
		if($filaOrder["dtFecha"]<date("Y-m-d")){
			BD::consulta("UPDATE pedido SET Estado='Anulado' WHERE intIDPedido=".$filaOrder["intIDPedido"]);
		}
	}
}
catch(Exception $e){
	echo $e->getMessage();
}

?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Airfood</title>
<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
<script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
<link rel="stylesheet" type="text/css" href="../estilo/general.css">
<link rel="stylesheet" type="text/css" href="../estilo/dashboard.css">
<script src="../js/funcion.js"></script>
<script src="../js/pedidos.js"></script>
</head>

<body>
	<!--  Navbar  -->
	<nav class="navbar navbar-fixed-top"> <!--navbar-fixed-top-->
	  	<div class="container-fluid">
			<div class="navbar-header">
  				<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
					<span class="glyphicon glyphicon-menu-hamburger"></span>                      
			  	</button>
	  			<!--<a class="navbar-brand" href="#"><img src="./img/Logo.png"></a>-->
			</div>
			<div class="collapse navbar-collapse" id="myNavbar">
				<ul class="nav navbar-nav navbar-right">
					<li><a href="#">Inicio</a></li>
					<li><a href="adminRestaurante.php?cif=<?=$cif?>">Administrar</a></li>
					<li><a href="../includes/logout.php">Cerrar sesión</a></li>
				</ul>
			</div>
	  	</div>
	  	
	</nav>
  	<div class="container">
  		<div class="row dashAdm">
			<h4 id="encabezado">Los pedidos en tiempo real.</h4>
			<div id="cajaPedido">
				<!--Los pedidos-->
			</div>
		</div>
	</div>
</body>
</html>
<?php
require_once("../clases/BD.php");
require_once("../clases/Restaurantes.php");
require_once("../clases/Comidas.php");
require_once("../clases/Pedidos.php");

session_start();
$cif=$_SESSION["cif"];
$msgComida="";
$alerta="";
try{
	$fotos=BD::consulta("SELECT * FROM fotos WHERE strCIF='$cif'");
	$filaFotos=$fotos->fetch();
	$imagen="..".substr($filaFotos["imagen"],23);
}
catch(Exception $e){
	echo $e->getMessage();
}

if(isset($_POST["anadeComida"])){
	$nombre=$_POST["comida"];
	$precio=$_POST["precio"];
	$ingredientes=$_POST["ingredientesComida"];
	$categoria=$_POST["categoria"];
	
	if(Comida::anadirComida($nombre,$precio,$ingredientes,$categoria,$cif)){
		$msgComida="Ha sido añadido la comida correctamente.";
	}
	else{
		$msgComida="No se ha añadido erronéamente.";
	}
}

$nombre=$_FILES["logofoto"]["name"]??"";
$tipo = $_FILES['logofoto']['type']??"";
$tamano = $_FILES['logofoto']['size']??"";

/*$directorio=$_SERVER['DOCUMENT_ROOT'].'/airfood/uploads/';
$directorio2=$_SERVER['DOCUMENT_ROOT'].'/airfood/uploads/'.$nombre;*/
$directorio='../uploads/';
$directorio2='../uploads/'.$nombre;
//move_uploaded_file($_FILES['foto']['tmp_name'],$directorio);
if(!empty($nombre)){
	if(strpos($tipo, "gif") || strpos($tipo,"jpeg") || strpos($tipo,"png")){
		if(move_uploaded_file($_FILES['logofoto']['tmp_name'],$directorio.$nombre)){
			if($cif!=$filaFotos["strCIF"]){
				BD::consulta("INSERT INTO fotos (imagen,strCIF) VALUES('$directorio2','$cif')");
				$fotos=BD::consulta("SELECT * FROM fotos WHERE strCIF='$cif'");
				$filaFotos=$fotos->fetch();
				BD::consulta("UPDATE restaurante SET foto=".$filaFotos["intIDImagen"]." WHERE CIF='$cif'");
			}
			else{
				BD::consulta("UPDATE fotos SET imagen='$directorio2' WHERE strCIF='$cif'");
			}
		}
		else{
			echo "No subio";
		}
	}
	else{
		$alerta="Solo las imagenes por favor";
	}
}

try{
	$fotos=BD::consulta("SELECT * FROM fotos WHERE strCIF='$cif'");
	$filaFotos=$fotos->fetch();
	$imagen=$filaFotos["imagen"];
	
	$tblrestaurante=BD::consulta("SELECT * FROM restaurante WHERE CIF='$cif'");
	$restaurante=$tblrestaurante->fetch();
	
	$tblmes=BD::consulta("SELECT * FROM tblmes");
	$datosCumplido="";
	$datosPerdido="";
	while($mes=$tblmes->fetch()){
		$pedidoCumplido=BD::consulta("SELECT count(DISTINCT pedido.intIDPedido) FROM pedido
		INNER JOIN lista ON lista.intIDPedido=pedido.intIDPedido
		INNER JOIN comidas ON comidas.intIDComida=lista.codComida
		WHERE Estado IN ('Cumplido') AND codCIF='$cif' AND pedido.dtFecha LIKE '".date("Y")."-%".$mes["intIDMes"]."-%'");
		$cumplido=$pedidoCumplido->fetch();
		$datosCumplido.=$cumplido[0].",";
		$pedidoPerdido=BD::consulta("SELECT count(DISTINCT pedido.intIDPedido) FROM pedido
		INNER JOIN lista ON lista.intIDPedido=pedido.intIDPedido
		INNER JOIN comidas ON comidas.intIDComida=lista.codComida
		WHERE Estado IN ('Anulado') AND codCIF='$cif' AND pedido.dtFecha LIKE '".date("Y")."-%".$mes["intIDMes"]."-%'");
		$perdido=$pedidoPerdido->fetch();
		$datosPerdido.=$perdido[0].",";
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
<link rel="stylesheet" type="text/css" href="../fonts/style.css">
<script src="../js/funcion.js"></script>
<script src="../js/Chart.js"></script>
<script src="../js/edicion.js"></script>
</head>

<body data-spy="scroll" data-target="#myScrollspy" data-offset="150">
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
					<li><a href="dashRestaurante.php">Inicio</a></li>
					<li><a href="adminRestaurante.php">Administrar</a></li>
					<li><a href="../includes/logout.php">Cerrar sesión</a></li>
				</ul>
			</div>
	  	</div>
	</nav>
	<!--<div class="container">-->
	<div class="row" id="admin">
		<div class="col-sm-3" id="fixed">
			<div class="panel panel-default">
				<div class="panel-heading">
					<div id="title"><?=$restaurante["Restaurante"]?></div>
					<form action="<?=$_SERVER['PHP_SELF']?>" method="POST" enctype="multipart/form-data" id="formfoto">
						<div id="custom" style="background-image: url(<?=$imagen?>);background-size:100% 100%">
							<input type="file" name="logofoto" id="cambioFoto" onChange="this.form.submit()">
							<?php if($imagen==""){?>
								<span class="glyphicon glyphicon-file"></span><br>
								<span style="font-size:16px">Subir foto</span>
							<?php }?>
							
						</div>
					</form>
				</div>
				<div class="panel-body" id="listaEnlace">
					<nav class="list-group" id="myScrollspy">
						<ul class="nav nav-pills nav-stacked">
							<li><a href="#anadirComida" class="list-group-item">Nueva comida</a></li>
							<li><a href="#edicionComida" class="list-group-item">Edición de comidas</a></li>
							<li><a href="#facturar" class="list-group-item">Factura y gráficas</a></li>
						</ul>
					</nav>
				</div>
			</div>
		</div>
	</div>
	<section class="filaAdmin">
		<div class="row administrar">
			<div class="col-sm-3">
				<div class="info red">
					<span class="icon-spoon-knife iconos"></span>
					<div class="num"><span class="nombre">Comidas</span><br>
								<?php 
									if($comida=Comida::contarComidas($cif)){
										echo "<span class='num'>".$comida."</span>";
									}
								?>
					</div>
				</div>
			</div>
			<div class="col-sm-3">
				<div class="info yellow">
					<span class="icon-star-full iconos"></span>
					<div class="num"><span class="nombre">Popularidad</span><br>
								<?php 
									if($comida=Restaurante::contarPopular($cif)){
										echo "<span class='num'>".$comida."</span>";
									}
								?>
					</div>
				</div>
			</div>
			<div class="col-sm-3">
				<div class="info blue">
					<span class="icon-cart iconos"></span>
					<div class="num"><span class="nombre">Pedidos</span><br>
								<?php 
									if($pedido=Pedido::contarPedidos($cif)){
										echo "<span class='num'>".$pedido."</span>";
									}
								?>
					</div>
				</div>
			</div>
			<div class="col-sm-3">
				<div class="info green">
					<span class="icon-coin-euro iconos"></span>
					<div class="num"><span class="nombre">Cartera</span><br>
								<?php 
									if($monedas=Restaurante::cartera($cif)){
										echo "<span class='num'>".number_format($monedas,2,",",".")."</span>";
									}
								?>
					</div>
				</div>
			</div>
		</div>
		<div class="row administrar" id="anadirComida">
			<div class="col-sm-4">
				<div class="panel panel-default">
					<div class="panel-heading">
						<div class="panel-title">Añadir nueva comida</div>
						<?=$msgComida?>
					</div>
					<div class="panel-body">
						<form action="<?=$_SERVER['PHP_SELF']?>" method="POST">
							<span class="error" id="nombre"></span>
							Nombre <input type=text class="form-control" name="comida" id="nombreComida"><br>
							<span class="error" id="precio"></span>
							Precio <input type="text" class="form-control" name="precio" id="precioComida"><br>
							<span class="error" id="ingredientes"></span>
							Ingredientes <input type="text" class="form-control" name="ingredientesComida" id="ingredientesComida"><br>
							Categoria <?php include("../includes/categoria.php");?>
							<input type="submit" class="btn btn-default" id="anadeComida" name="anadeComida" value="Añadir comida"  disabled>
						</form>
					</div>
				</div>
			</div>
			<div class="col-sm-8">
				<div class="panel panel-default">
					<div class="panel-heading">
						<div class="panel-title">Gestión de comidas recientes</div>
					</div>
					<div class="panel-body" id="tablaReciente">
						<table class="table table-striped">
							<?php if($comidas=Comida::obtenerComidas($cif)){
								foreach($comidas as $filaComida){?>
								<tr>
									<td><?=$filaComida->getComida()?></td>
									<td>
										<input type="hidden" id="idOculto" value="<?=$filaComida->getIDComida()?>">
										<input type="button" class="btn btn-default publicar" name="publicar" value="
										<?php
											if($filaComida->getEstado()=="Publicar"){
												echo "No publicar";
											}
											else{
												echo "Publicar";
											}
										?>
										">
									</td>
									<td><input type="button" class="btn btn-default borrar" name="borrar" value="Borrar"></td>
								</tr>
							<?php }}?>
						</table>
					</div>
				</div>
			</div>
		</div>
		<div class="row administrar" id="edicionComida">
			<div class="col-sm-12">
				<div class="panel panel-default">
					<div class="panel-heading">
						<div class="panel-title">Edición de comidas</div>
					</div>
					<div class="panel-body" id="edicionTable">
						<table class="table table-striped">
							<?php if($comidas=Comida::obtenerComidas($cif)){
								foreach($comidas as $filaComida){?>
								<tr><input type="hidden" class="idComida" value="<?=$filaComida->getIDComida()?>">
									<td width="15%"><input type="text" id="NombreComida" class="form-control edicion" value="<?=$filaComida->getComida()?>"></td>
									<td><input type="text" id="Ingredientes" class="form-control edicion" value="<?=$filaComida->getIngredientes()?>"></td>
									<td width="8%"><input type="text" id="Precio" class="form-control edicion" value="<?=$filaComida->getPrecio()?>"></td>
								</tr>
							<?php }}?>
						</table>
					</div>
				</div>
			</div>
		</div>
		<div class="row administrar" id="facturar">
			<div class="col-sm-6">
				<div class="panel panel-defaul">
					<div class="panel-heading">
						<div class="panel-title">Los pedidos al mes</div>
					</div>
					<div class="panel-body">
						<canvas id="graficoPedidos" width="100%" height="80%" style="margin-left: -10px"></canvas>
						<script>
							var lineChartData = {
								labels : ["Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"],
								datasets : [
									{
										label: "Pedidos cumplidos",
										fillColor : "transparent",
										strokeColor : "#C50801",
										pointColor : "#C50801",
										pointStrokeColor : "#C50801",
										pointHighlightFill : "hsla(48,100%,50%,1.00)",
										pointHighlightStroke : "#C50801",
										data : [<?=$datosCumplido?>]
									},
									{
										label: "Pedidos anulados",
										fillColor : "transparent",
										strokeColor : "hsla(48,100%,50%,1.00)",
										pointColor : "hsla(48,100%,50%,1.00)",
										pointStrokeColor : "hsla(48,100%,50%,1.00)",
										pointHighlightFill : "#C50801",
										pointHighlightStroke : "hsla(48,100%,50%,1.00)",
										data : [<?=$datosPerdido?>]
									}
								]
							}
							var ctx4 = document.getElementById("graficoPedidos").getContext("2d");
							window.myPie = new Chart(ctx4).Line(lineChartData, {
								responsive:true
							});
						</script>
						Servidos: <div id="rojoPedido"></div>
						Anulados: <div id="amarilloAnulado"></div>
					</div>
				</div>
			</div>
			<div class="col-sm-6">
				<div class="panel panel-defaul">
					<div class="panel-heading">
						<div class="panel-title">Comidas favoritas por clientes</div>
					</div>
					<div class="panel-body">
						<canvas id="graficoComida" width="300" height="300"></canvas>
						<script>
							var pieData = [
								<?php
									$tbllista=BD::consulta("SELECT comidas.NombreComida, sum(unidad) FROM lista
									INNER JOIN comidas ON comidas.intIDComida=lista.codComida
									WHERE codCif='$cif'
									GROUP BY intIDComida");
									while($listaComida=$tbllista->fetch()){
										$rand = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'a', 'b', 'c', 'd', 'e', 'f');
										$color = '#'.$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)];
								?>
									{
										value: <?=$listaComida[1]?>,
										color:"<?=$color?>",
										highlight: "hsla(48,100%,50%,1.00)",
										label: "<?=$listaComida[0]?>"
									},	
								<?php }
								?>
							];

							var ctx2 = document.getElementById("graficoComida").getContext("2d");
							window.myPie = new Chart(ctx2).Doughnut(pieData);
						</script>
					</div>
				</div>
				<a href="../includes/generarFactura.php?cif=<?=$cif?>" target="_blank"><button type="button" class="btn btn-default" id="generarFactura">Factura</button></a>
			</div>
		</div>
	</section>
	<script>
		if("<?=$alerta?>"!=""){
			alert("<?=$alerta?>");
		}
		
	</script>
</body>
</html>

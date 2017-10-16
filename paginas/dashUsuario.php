<?php
require_once("../clases/BD.php");
require_once("../clases/Restaurantes.php");
require_once("../clases/Comidas.php");
require_once("../clases/Pedidos.php");

session_start();
$cliente=$_SESSION["cliente"]??"";
if(empty($cliente)){
	header("Location: ../index.php");
}

try{
	$tblmes=BD::consulta("SELECT * FROM tblmes");
	$tblpedido=BD::consulta("SELECT intIDPedido FROM pedido
	WHERE Estado='Guardado' AND strCliente='$cliente'");
	$idPedido=$tblpedido->fetch();
	$tblcategoria=BD::consulta("SELECT * FROM categoria");
	$tblcliente=BD::consulta("SELECT * FROM cliente
	WHERE Usuario='$cliente'");
	$filaCliente=$tblcliente->fetch();
}
catch(Exception $e){
	echo $e->getMessage();
}

/*$bedidas=$_POST["bebida"]??"";
$restaurante=$_SESSION["restaurante"]??"";*/
$direccion=$_POST["direccion"]??"";
$preciosMax=$_POST["totalPrecio"]??"";
$order=$idPedido[0]??"";
$arrayBebidas=$_POST["bebida"]??"";
@$bebidas=implode(",",$arrayBebidas)??"";
$alergia=$_POST["alergia"]??"";
$celiaco=$_POST["celiaco"]??"";
$tipoPago=$_POST["TipoDePago"]??"";

if(isset($_POST["enviarPedido"])){
	Pedido::mandarPedido($preciosMax,$order,$bebidas,$alergia,$celiaco,$tipoPago);
}

$categoria=$_POST["categoria"]??"todo";
$estrellas=$_POST["estrella"]??"todo";

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
	<div class="container">
		<div class="row dashUsuario">
			<!--Restaurantes-->
			<div class="col-sm-9">
				<nav id="filtros">
					<form class="form-inline" action="<?=$_SERVER['PHP_SELF']?>" method="POST" enctype="multipart/form-data">
						<!--Buscador:&nbsp;<input type="text" class="input-select" id="buscador" name="buscador">-->
						Categoría:&nbsp;
						<select name="categoria" id="categoria"  class="input-select" onchange="this.form.submit()">
							<option value="todo">Todas</option>
							<?php
							while($filaCategoria=$tblcategoria->fetch()){?>
								<option value="<?=$filaCategoria[0]?>"
								<?php if($categoria==$filaCategoria[0]){echo "selected";}?>><?=$filaCategoria[1]?></option>
							<?php }
							?>
						</select>
						Estrellas:&nbsp;<select name="estrella" id="estrella" class="input-select"  onchange="this.form.submit()">
							<option value="todo">Cualquiera</option>
							<?php
							for($j=1;$j<=5;$j++){?>
								<option value="<?=$j?>"
								<?php if($j==$estrellas){echo "selected";}?>><?=$j?></option>
							<?php }
							?>
						</select>
					</form>
				</nav>
				<div class="panel-group" id="accordion">
				<?php 
				if($restaurantes=Restaurante::obtenerRestaurantes($categoria,$estrellas)){
					$i=0;
					foreach($restaurantes as $filaRes){?>
					<div class="panel panel-default">
						<div class="panel-heading">
							<!--<a href="info.php?nombre=<?=$filaRes->getRestaurante()?>" style="float:right">+info</a>-->
							<h4 class="panel-title">
							<a data-toggle="collapse" data-parent="#accordion" href="#collapse<?=$i?>"><?=$filaRes->getRestaurante()?> </a>
							<?php
								$puntos=5;
								for($j=1;$j<=$filaRes->getPopularidad();$j++){
									echo "<span class='glyphicon glyphicon-star'></span>";
									$puntos--;
								}
								for($j=1;$j<=$puntos;$j++){
									echo "<span class='glyphicon glyphicon-star-empty'></span>";
								}
							?>
							</h4>
						</div>
						<div id="collapse<?=$i?>" class="panel-collapse collapse">
							<div class="panel-body">
								<table class="comidasRestaurante">
								<?php 
								if($comidas=Comida::comidasPublicadas($filaRes->getCIF())){
									foreach($comidas as $filaComida){?>
										<tr>
											<td class="strComida">
												<?=$filaComida->getComida()?>
												<input type="hidden" name="comida" value="<?=$filaComida->getIDComida()?>"
											</td>
											<td class="intPrecio">
												<?=$filaComida->getPrecio()?> €
											</td>
											<td>
												<?=$filaComida->getIngredientes()?>
											</td>
											<td><span class="glyphicon glyphicon-minus-sign controlUds" id="menos"></span></td>
											<td width="60px"><input type="text" class="form-control" value=1 min=1 max=10 readonly></td>
											<td><span class="glyphicon glyphicon-plus-sign controlUds" id="mas"></span></td>
											<td><button type="button" class="btn btn-default anadePedido">Añadir al pedido</button></td>
										</tr>
									<?php }
								}
								?>
								</table>
								<input type="hidden" name="cliente" id="cliente" value="<?=$cliente?>">
							</div>
						</div>
					</div>
					<?php $i++;}
					}
					?>
				</div>
			</div>
			<!--Pedidos-->
			<div class="col-sm-3">
				<div class="panel panel-default" id="pedidos">
					<div class="panel-heading">
						<h4 class="panel-title">
							Pedidos <img id="load" src="../img/iconos/preloader.gif">
							<span class="glyphicon glyphicon-trash" style="float:right" id="borrarPedidos"></span>
						</h4>
					</div>
					<div class="panel-body">
						<table>
							<tr id="nombreCelda"><th>Comidas</th><th>Uds.</th><th>Precio</th></tr>
						</table>
						<button class="btn btn-default" data-toggle="modal" data-target="#modalPedido">Pedir ya!!</button>
					</div>
				</div>
			</div>
			</div>
			<!-- Modal -->
			<div id="modalPedido" class="modal fade" role="dialog">
  				<div class="modal-dialog">
					<form action="<?=$_SERVER['PHP_SELF']?>" method="POST" enctype="multipart/form-data" class="form-inline">
    				<!-- Modal content-->
    				<div class="modal-content">
      					<div class="modal-header">
        					<button type="button" class="close" data-dismiss="modal">&times;</button>
        					<h4 class="modal-title">Tus pedidos</h4>
      					</div>
     	 				<div class="modal-body">
        						<table class="table table-responsive" id="tableDecision">
        							<?php include("../includes/mostrarPedidoCliente.php")?>
        						</table>
        						<table>
        							<tr><td>¿Quieres beber algo?</td></tr>
        							<tr>
        								<td>
        									<label><input type="checkbox" name="bebida[]" id="bebidas" value="Agua">Agua</label>
        									<label><input type="checkbox" name="bebida[]" id="bebidas" value="Refresco">Refresco</label>
        								</td>
        							</tr>
        							<tr><td>¿Cuál es tu caso?</td></tr>
        							<tr>
        								<td>
        									<label>Alergía: <input type="text" name="alergia" id="alergia" class="form-control"></label>
        								</td>
        								<td>
        									<label>Celíaco: <input type="checkbox" name="celiaco" id="celiaco" value="Si"></label>
        								</td>
        							</tr>
        							<tr>
        								<td>
        									<label class="tarjeta">En efectivo: <input type="radio" name="TipoDePago" id="TipoDePago" value="En Efectivo" checked></label> &nbsp;
        									<label class="tarjeta">Tarjeta de créditos: <input type="radio" name="TipoDePago" id="TipoDePago2" value="Tarjeta"></label>
        								</td>
        							</tr>
        						</table>
        						<div id="cartilla">
        							<table>
        								<tr>
        									<td colspan="2">Nº de tarjeta: <input type="text" class="form-control" id="intTarjeta" name="intTarjeta" maxlength="20" placeholder="1234 1234 1234 1234 1234"></td>
        									<td>Titular de tarjeta: <input type="text" class="form-control" name="strTarjeta" id="strTarjeta"></td>
        								</tr>
        								<tr>
        									<td>
        										Fecha cadudicidad:
        										<select name="mes" id="mes" class="form-control">
        											<?php
													while($filaMes=$tblmes->fetch()){?>
														<option value="<?=$filaMes["intIDMes"]?>"><?=$filaMes["strMes"]?></option>
													<?php }
													?>
        										</select>
											</td>
       										<td>
       											&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        										<select name="anio" id="anio" class="form-control">
        											<?php
													for($i=date("Y");$i<=date("Y")+5;$i++){?>
														<option value="<?=$i?>"><?=$i?></option>
													<?php }
													?>
        										</select>
        									</td>
        									<td>
        										CVV/CID<input type="text" class="form-control" name="CVV" id="CVV" maxlength="3">
        									</td>
        								</tr>
        							</table>
        							<span id="error"></span>
        						</div>
      					</div>
      					<div class="modal-footer">
        					<input type="submit" class="btn btn-default" name="enviarPedido" id="enviarPedido" value="Enviar pedido">
      					</div>
    				</div>
					</form>
  				</div>
			</div>
	</div>
</body>
</html>
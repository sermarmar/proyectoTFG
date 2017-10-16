<?php
	require_once("../clases/BD.php");
	require_once("../clases/Restaurantes.php");
	require_once("../clases/Comidas.php");
	require_once("../clases/Pedidos.php");

	$cif=$_GET["cif"];
	$anio=date("Y");
	$mes=date("m");

	try{
		$restaurante=Restaurante::buscarRestaurante($cif);
		
		$listaFactura=BD::consulta("SELECT comidas.NombreComida,Precio, sum(unidad), sum(Precio) FROM comidas
		INNER JOIN lista ON lista.codComida=comidas.intIDComida
		INNER JOIN restaurante ON restaurante.CIF=comidas.codCif
		INNER JOIN pedido ON pedido.intIDPedido=lista.intIDPedido
		WHERE pedido.dtFecha LIKE '$anio-$mes-%' AND Estado='Cumplido'
		GROUP BY intIDComida");
		
		$pedidoFactura=BD::consulta("SELECT count(*) AS num , sum(dbPrecioTotal) as Precio, 
		sum((dbPrecioTotal*3)/100) AS comision, sum(dbPrecioTotal-((dbPrecioTotal*3)/100)) AS Total 
		FROM pedido
		WHERE dtFecha LIKE '2017-05%' AND Estado='Cumplido'");
		$pedido=$pedidoFactura->fetch();
		
		BD::consulta("UPDATE cartera SET intMoneda=0 WHERE idRestaurante='$cif'");
	}
	catch(Exception $e){
		echo $e->getMessage();
	}

	require_once '../dompdf/autoload.inc.php';

	use Dompdf\Dompdf;
	ob_start();
?>

<!doctype html>
<html>
<head>
	<meta charset="utf-8">
	<title>Airfood</title>
	<link rel="stylesheet" type="text/css" href="../estilo/factura.css">
</head>
<body>
	<table class="tabla">
		<tr>
			<td>CIF</td>
			<td><?=$cif?></td>
		</tr>
		<tr>
			<td>Fecha de factura:</td>
			<td><?=date("d/m/Y")?></td>
		</tr>
	</table>
	<div id="logotipo">
		<img src="../img/Logo.png" width="200px">
	</div>
	
	<table class="restaurante">
		<tr>
			<th>
				Restaurante:
			</th>
		</tr>
		<tr>
			<td rowspan="5" id="tdLogo">
				<img src="<?=$restaurante->getFoto()?>" width="100px" id="logo">
			</td>
			<td colspan="2"><?=$restaurante->getRestaurante()?></td>
		</tr>
		<tr>
			<td>Email:</td>
			<td><?=$restaurante->getEmail()?></td>
		</tr>
		<tr>
			<td>Localidad:</td>
			<td><?=$restaurante->getLocalidad()?></td>
		</tr>
		<tr>
			<td>Dirección:</td>
			<td><?=$restaurante->getDireccion()?> nº <?=$restaurante->getNumero()?></td>
		</tr>
		<tr>
			<td>Teléfono:</td>
			<td><?=$restaurante->getTelefono()?></td>
		</tr>
	</table>
	<table class="tablePedidos">
		<tr>
			<th>Comidas</th>
			<th>Precio</th>
			<th>Unidades</th>
			<th>Precio Total</th>
		</tr>
		<?php
		while($filaComida=$listaFactura->fetch()){?>
			<tr>
				<td><?=$filaComida[0]?></td>
				<td style="text-align: right"><?=number_format($filaComida[1],2,",",".")?> €</td>
				<td style="text-align: right"><?=$filaComida[2]?> uds</td>
				<td style="text-align: right"><?=number_format($filaComida[3],2,",",".")?> €</td>
			</tr>
		<?php }
		?>
	</table>
	<table class="tablePedidos">
		<tr>
			<th>Nº Pedidos</th>
			<th>Precio</th>
			<th>Comisión 3%</th>
			<th>Precio Total</th>
		</tr>
		<tr>
			<td><?=$pedido[0]?></td>
			<td style="text-align: right"><?=number_format($pedido[1],2,",",".")?> €</td>
			<td style="text-align: right"><?=number_format($pedido[2],2,",",".")?> €</td>
			<td style="text-align: right"><?=number_format($pedido[3],2,",",".")?> €</td>
		</tr>
	</table>
	
	<div id="info">
		Se transmitara la cartera digital a tu banco.
		<h2>Gracias por ser socio de nuestra página Web Airfood.</h2>
	</div>
	
</body>
</html>


<?php
$dompdf = new Dompdf();
$dompdf->loadHtml(ob_get_clean());
$dompdf->setPaper('A4', 'portrat'); // (Opcional) Configurar papel y orientación
$dompdf->render(); // Generar el PDF desde contenido HTML
$pdf = $dompdf->output(); // Obtener el PDF generado
file_put_contents("../archivos/factura$cif-$mes-$anio.pdf", $pdf);
header("Location: ../archivos/factura$cif-$mes-$anio.pdf");
?>
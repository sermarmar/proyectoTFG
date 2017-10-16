<?php
require_once("../clases/Pedidos.php");

$idComida=$_POST["comida"];
$unidades=$_POST["uds"];
$cliente=$_POST["cliente"];
$preciosTotales=0;

$alerta=Pedido::guardarPedidos($idComida,$unidades,$cliente);
if($pedidos=Pedido::mostrarPedidosCliente($cliente)){
	foreach($pedidos as $filaPedidos){
		$totalPrecios=$filaPedidos->getUnidades()*$filaPedidos->getPrecio();
		$preciosTotales+=$totalPrecios;
	?>
		<tr class="comidas">
			<td><?=$filaPedidos->getComida()?> <input type="hidden" name="order" class="order" value="<?=$filaPedidos->getPedido()?>"</td>
			<td class="drch"><?=$filaPedidos->getUnidades()?></td>
			<td class="drch"><?=$totalPrecios?> €</td>
		</tr>
	<?php }
}?>
		<tr id="sinBorde">
			<th colspan="2">Total: </th>
			<th class="drch"><?=$preciosTotales?> € <input type="hidden" name="totalPrecio" value="<?=$preciosTotales?>"></th>
		</tr>
<?php
	if(!empty($alerta)){?>
		<script>alert("<?=$alerta?>");</script>
<?php	}
?>
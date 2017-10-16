<?php
require_once("../clases/Pedidos.php");

$cliente=$_GET["cliente"]??$_SESSION["cliente"];
$preciosTotal=0;
$preciosTotales=0;

if($pedidos=Pedido::mostrarPedidosCliente($cliente)){
	foreach($pedidos as $filaPedidos){
		$totalPrecios=$filaPedidos->getUnidades()*$filaPedidos->getPrecio();
		$preciosTotales+=$totalPrecios;
	?>
		<tr class="comidas">
			<td><?=$filaPedidos->getComida()?> <input type="hidden" name="order" class="order" value="<?=$filaPedidos->getPedido()?>"></td>
			<td class="drch"><?=$filaPedidos->getUnidades()?></td>
			<td class="drch"><?=$totalPrecios?> €</td>
		</tr>
	<?php }?>
		<tr id="sinBorde">
			<th colspan="2">Total: </th>
			<th class="drch">
				<?=$preciosTotales?> € <input type="hidden" name="totalPrecio" value="<?=$preciosTotales?>">
			</th>
		</tr>
<?php }

else{
	echo "<tr><td>La lista está vacía.</td></tr>";
}
?>
<?php
require_once("../clases/BD.php");
require_once("../clases/Pedidos.php");
session_start();
$cif=$_SESSION["cif"]??"";


try{
	$consultaOrder=BD::consulta("SELECT * FROM pedido
	INNER JOIN cliente ON cliente.Usuario=pedido.strCliente
	INNER JOIN direccion ON direccion.strPersona=cliente.Usuario
	INNER JOIN lista ON lista.intIDPedido=pedido.intIDPedido
	INNER JOIN comidas ON comidas.intIDComida=lista.codComida
	WHERE Estado IN ('Pedido','Retrasado') AND codCIF='$cif' AND dtFecha='".date("Y-m-d")."'
	GROUP BY pedido.intIDPedido
	ORDER BY pedido.intIDPedido DESC");
}
catch(Exception $e){
	echo $e->getMessage();
}

while($filaOrder=$consultaOrder->fetch()){
	$totalPrecio=0;?>
	
	<div class="panel panel-default order">
		<div class="panel-heading">
			<div class="panel-title">
			<?php if(empty($filaOrder["strPiso"])){?>
				<?=$filaOrder["strCliente"]?> - <?=$filaOrder["strDireccion"]?> Nº <?=$filaOrder["intNumero"]?> TELEFONO: <?= $filaOrder["Telefono"]?>
				<span style="float:right"><?php echo substr($filaOrder["tmHora"],0,5);?></span>
			<?php }else{?>
				<?=$filaOrder["strCliente"]?> - <?=$filaOrder["strDireccion"]?> Nº <?=$filaOrder["intNumero"]?> Piso: <?=$filaOrder["strPiso"]?> TELEFONO: <?= $filaOrder["Telefono"]?>
				<span style="float:right"><?php echo substr($filaOrder["tmHora"],0,5);?></span>
			<?php }?>
			</div>
		</div>
		<div class="panel-body">
			<div class="row">
				<div class="col-xs-6">
					<?php if($pedidos=Pedido::obtenerPedidos($filaOrder["strCliente"],$filaOrder["intIDPedido"])){
						echo "<table class='table table-bordered' id='tablePedido'><tr><td>Comidas</td><td>Unidades</td><td>Precio</td></tr>";
						foreach($pedidos as $filaPedidos){
							$precioXuds=$filaPedidos->getUnidades()*$filaPedidos->getPrecio();
							$totalPrecio+=$precioXuds;
					?>
							<tr>
								<td><?=$filaPedidos->getComida()?></td>
								<td><?=$filaPedidos->getUnidades()?></td>
								<td><?=number_format($precioXuds, 2, ',', ' ')?> €</td>
							</tr>
						<?php }
						echo "</table>";
					}?>
				</div>
				<div class="col-xs-4">
					<h3 class="colorLetra">Total: <?=number_format($totalPrecio, 2, ',', ' ')?> €</h3>
					<p>Tipo de pago: <?=$filaOrder["TipoDePago"]?></p>
					<p>Bebidas: <?=$filaOrder["Bebidas"]?></p>
					<p>Alergia: <?=$filaOrder["Alergia"]?></p>
					<p>Celiaco: <?=$filaOrder["Celiacos"]?></p>
				</div>
				<div class="col-xs-1">
					<span class="controlPedido glyphicon glyphicon-ok" id="cumplir<?=$filaOrder["intIDPedido"]?>" name="<?=$filaOrder["intIDPedido"]?>"></span>
				</div>
				<div class="col-xs-1">
					<span class="controlPedido glyphicon glyphicon-remove" id="anular<?=$filaOrder["intIDPedido"]?>" name="<?=$filaOrder["intIDPedido"]?>"></span>
				</div>
			</div>
		</div>
	</div>
	
	<script>
		$(function(){
			//Control de pedidos
			$('#cumplir<?=$filaOrder["intIDPedido"]?>').click(function(){
				var idPedido=$(this).attr("name");
				$.post("../includes/controlPedido.php",{estado:"Cumplido",id:idPedido},function(datos,estado){
					if(estado=="success"){
						$('#cumplir'+idPedido).parents('.order').slideUp();
					}
				});
			});
			$('#anular<?=$filaOrder["intIDPedido"]?>').click(function(){
				var idPedido=$(this).attr("name");
				$.post("../includes/controlPedido.php",{estado:"Anulado",id:idPedido},function(datos,estado){
					if(estado=="success"){
						$('#cumplir'+idPedido).parents('.order').slideUp();
					}
				});
			});
		});
	</script>

<?php }
/*header("Cache-Control: no-cache");
header("Pragma: no-cache");*/
?>
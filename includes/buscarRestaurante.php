<?php
require_once("../clases/Comidas.php");
require_once("../clases/Restaurantes.php");


$valor=$_REQUEST["valor"];

if($restaurantes=Restaurante::buscarRestaurantes($valor)){
	$i=0;
	foreach($restaurantes as $filaRes){?>
	<div class="panel panel-default">
		<div class="panel-heading">
			<h4 class="panel-title">
			<a data-toggle="collapse" data-parent="#accordion" href="#collapse<?=$i?>"><?=$filaRes->getRestaurante()?> </a>
			<?php
				$puntos=5;
				for($i=1;$i<=$filaRes->getPopularidad();$i++){
					echo "<span class='glyphicon glyphicon-star'></span>";
					$puntos--;
				}
				for($i=1;$i<=$puntos;$i++){
					echo "<span class='glyphicon glyphicon-star-empty'></span>";
				}
			?>
			</h4>
		</div>
		<div id="collapse<?=$i?>" class="panel-collapse collapse">
			<div class="panel-body">
				<table class="comidasRestaurante">
				<?php 
				if($comidas=Comida::obtenerComidas($filaRes->getCIF())){
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
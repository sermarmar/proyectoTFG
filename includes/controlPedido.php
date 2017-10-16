<?php
require_once("../clases/BD.php");

$id=$_POST["id"];
$estado=$_POST["estado"];

try{

	$pago=BD::consulta("SELECT DISTINCT pedido.intIDPedido, TipoDePago,dbPrecioTotal, codCif FROM pedido
	INNER JOIN lista ON lista.intIDPedido=pedido.intIDPedido
	INNER JOIN comidas ON comidas.intIDComida=lista.codComida
	WHERE Estado='Pedido' AND pedido.intIDPedido=$id");
	$filaPago=$pago->fetch();
		
	$comision=($filaPago["dbPrecioTotal"]*3)/100;
	$precioTotal=$filaPago["dbPrecioTotal"]-$comision;	
	
	echo $filaPago["TipoDePago"];
	echo $filaPago["intIDPedido"];
	echo $filaPago["dbPrecioTotal"];
	echo $filaPago["codCif"];
	
	if($estado=="Cumplido"){
		if($filaPago["TipoDePago"]=="Tarjeta"){
			BD::consulta("UPDATE cartera SET intMoneda=intMoneda+$precioTotal WHERE idRestaurante='".$filaPago["codCif"]."'");
			//echo "Funciono la tarjeta.";
		}
		else{
			BD::consulta("UPDATE cartera SET intMoneda=intMoneda-$comision WHERE idRestaurante='".$filaPago["codCif"]."'");
			//echo "Funciono en efectivo.";
		}
	}
	BD::consulta("UPDATE pedido SET Estado='$estado' WHERE intIDPedido=$id");
}
catch(Exception $e){
	echo $e->getMessage();
}
?>
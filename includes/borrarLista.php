<?php
require_once("../clases/BD.php");

$numPedido=$_POST["numPedido"];

BD::consulta("DELETE FROM lista WHERE intIDPedido='$numPedido'");
BD::consulta("DELETE FROM pedido WHERE intIDPedido='$numPedido'");

echo "<tr><td>La lista está vacía.</td></tr>";

?>
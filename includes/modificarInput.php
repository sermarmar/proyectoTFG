<?php
require_once("../clases/BD.php");

$id=$_POST["id"];
$celda=$_POST["celda"];
$valor=$_POST["valor"];

BD::consulta("UPDATE comidas SET $celda='$valor' WHERE intIDComida=$id");

?>
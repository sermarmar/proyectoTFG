<?php
require_once("../clases/BD.php");

$id=$_POST["id"];
$publico=$_POST["publico"];

BD::consulta("UPDATE comidas SET enumPublicado='$publico' WHERE intIDComida=$id");

if($publico=="Publicar"){
	echo "No publicar";
}
else{
	echo "Publicar";
}
?>

<?php
session_start();
$cliente=$_SESSION["cliente"]??"";
$cif=$_SESSION["cif"]??"";
session_destroy();
if(empty($cliente)){
	header("Location: ../index.php");
}
else{
	echo "No se cerró correctamente. Cierra el navegador.";
}
if(empty($cif)){
	header("Location: ../index.php");
}
else{
	echo "No se cerró correctamente. Cierra el navegador.";
}

?>
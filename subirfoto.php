<?php
try{
	$conexion="mysql:host=localhost;dbname=airfood";
	$conUsuario="restaurante";
	$conPass="comida";
	$conn=new PDO($conexion,$conUsuario,$conPass);
}
catch(Exception $e){
	echo $e->getMessage();
}

$nombre=$_FILES["foto"]["name"];
$tipo = $_FILES['foto']['type'];
$tamano = $_FILES['foto']['size'];
	
$directorio=$_SERVER['DOCUMENT_ROOT'].'/Airfood/uploads/';
//move_uploaded_file($_FILES['foto']['tmp_name'],$directorio);
if(move_uploaded_file($_FILES['foto']['tmp_name'],$directorio.$nombre)){
	echo "Subio con exito";
}
else{
	echo "No subio";
}
/*$copiarFichero = false;
if (is_uploaded_file ($_FILES['foto']['tmp_name'])){
    $nombreFichero = $_FILES['foto']['name']; 
	$copiarFichero=true;
	echo "Subio";
}
else{
	echo "No subio";
}

if ($copiarFichero){
	move_uploaded_file ($_FILES['foto']['tmp_name'],$directorio . $nombreFichero);
	echo "<br>Ha movido";
}
else{
	echo "<br>No ha movido";
}*/
?>
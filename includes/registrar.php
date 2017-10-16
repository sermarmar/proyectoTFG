<?php
require_once("../clases/BD.php");
require_once("../clases/Restaurantes.php");
require_once("../clases/Clientes.php");

$registro=$_POST["registro"];

//Cliente
$usuario=$_POST["usuario"]??"";
$nombre=$_POST["nombre"]??"";
$apellidos=$_POST["apellidos"]??"";
$anio=$_POST["anio"]??"";
$mes=$_POST["mes"]??"";
$dia=$_POST["dia"]??"";
$piso=$_POST["piso"]??"";
$fechaNacimiento="$anio-$mes-$dia";

//Restaurante
$cif=$_POST["cif"]??"";
$restaurante=$_POST["restaurante"]??"";
$categoria=$_POST["categoria"]??"";

//General
$pass=$_POST["pass"];
$email=$_POST["email"];
$direccion=$_POST["direccion"];
$num=$_POST["numero"];
$localidad=$_POST["localidad"];
//$provincia=$_POST["provincia"];
$cp=$_POST["codPostal"];
$telefono=$_POST["intTelefono"];


if($registro=="restaurante"){
	echo "$cif<br>$pass<br>$restaurante<br>$email<br>$direccion<br>$num<br>$localidad<br>$telefono<br>$cp<br>$categoria";
	if(Restaurante::registrarRestaurante($cif,$pass,$restaurante,$email,$telefono,$direccion,$num,$localidad,$cp,$categoria)){
		echo "Se ha registrado correctamente";
		session_start();
		$_SESSION["cif"]=$cif;
		header("Location: ../paginas/dashRestaurante.php");
	}
	else{
		echo "No se ha registrado";
	}
}
else if($registro=="cliente"){
	echo "$usuario<br>$pass<br>$nombre<br>$apellidos<br>$email<br>$fechaNacimiento<br>$direccion<br>$num<br>$piso<br>$localidad<br>$telefono<br>$cp";	if(Cliente::registrarCliente($usuario,$pass,$nombre,$apellidos,$email,$fechaNacimiento,$direccion,$num,$piso,$localidad,$telefono,$cp)){
		echo "Se ha registrado correctamente";
		session_start();
		$_SESSION["cliente"]=$usuario;
		header("Location: ../paginas/dashUsuario.php");
	}
	else{
		echo "No se ha registrado";
	}
}


?>
<?php
require_once("../clases/BD.php");

$usuario=$_REQUEST["usuario"];

$sql="SELECT Usuario FROM cliente WHERE Usuario='$usuario'";
$cont="";

try{
	$resultado=BD::consulta($sql);
	if(!empty($existe=$resultado->fetch())){
		$cont=$existe[0];
	}
	echo $cont;
	
}
catch(Exception $e){
	echo $e->getMessage();
}



?>
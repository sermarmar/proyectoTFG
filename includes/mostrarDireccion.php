<?php
require_once("../clases/BD.php");

$cliente=$_SESSION["cliente"];

$sql="SELECT * FROM direccion
WHERE strPersona='$cliente'";

$consultaDireccion=BD::consulta($sql);
while($filaDireccion=$consultaDireccion->fetch()){?>
	<option value="<?=$filaDireccion["intIDDireccion"]?>"><?=$filaDireccion["strDireccion"]?></option>
<?php }
?>
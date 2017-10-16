<?php

require_once("../clases/BD.php");

$provincias=BD::consulta("SELECT * FROM categoria");?>
<select class="form-control" name="categoria">
<?php while($filaProvincia=$provincias->fetch()){?>
	<option value="<?=$filaProvincia["intIDCategoria"]?>"><?=$filaProvincia["strNombreCategoria"]?></option>
<?php }?>
</select>
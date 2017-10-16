<?php

require_once("../clases/BD.php");

$municipio=$_REQUEST["localidad"];

$localidad=BD::consulta("SELECT * FROM tblmunicipios
INNER JOIN tblprovincias ON tblmunicipios.id_provincia=tblprovincias.id_provincia
WHERE nombre LIKE '$municipio%'
LIMIT 7");?>

<table id="tableLocalidad">
<?php while($filaLocalidad=$localidad->fetch()){?>
	<tr class="click">
		<td>
			<input type="hidden" id="id_Municipio" value="<?=$filaLocalidad["id_municipio"]?>">
			<?=$filaLocalidad["nombre"]?>, <?=$filaLocalidad["provincia"]?>
		</td>
	</tr>
<?php }?>
</table>


<script>
	$('.click').click(function(){
		var localidad=$.trim($(this).text());
		$('#localidad').val(localidad);
		$('.eligeLocalidad').slideUp();
	});
</script>